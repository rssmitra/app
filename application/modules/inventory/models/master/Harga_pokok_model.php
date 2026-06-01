<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Harga_pokok_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _get_flag()
    {
        // POST (AJAX DataTable) diutamakan, fallback ke GET (page load / get_detail)
        $flag = isset($_POST['flag']) ? $_POST['flag'] : (isset($_GET['flag']) ? $_GET['flag'] : 'medis');
        return ($flag === 'non_medis') ? 'non_medis' : 'medis';
    }

    private function _get_table()
    {
        return ($this->_get_flag() === 'non_medis') ? 'mt_barang_nm' : 'mt_barang';
    }

    private function _main_query()
    {
        $flag  = $this->_get_flag();
        $table = $this->_get_table();

        $this->db->select('b.kode_brg, b.nama_brg, b.satuan_besar, b.satuan_kecil, b.content, b.harga_beli, b.updated_date');

        if ($flag === 'medis') {
            $this->db->select('mg.nama_golongan, ms.nama_sub_golongan, mp.nama_pabrik');
            $this->db->from($table . ' b');
            $this->db->join('mt_golongan mg',     'mg.kode_golongan = b.kode_golongan',         'left');
            $this->db->join('mt_sub_golongan ms', 'ms.kode_sub_gol  = b.kode_sub_golongan',     'left');
            $this->db->join('mt_pabrik mp',       'mp.id_pabrik     = b.id_pabrik',             'left');
        } else {
            $this->db->select('mg.nama_golongan, ms.nama_sub_golongan, mp.nama_pabrik');
            $this->db->from($table . ' b');
            $this->db->join('mt_golongan_nm mg',     'mg.kode_golongan = b.kode_golongan',      'left');
            $this->db->join('mt_sub_golongan_nm ms', 'ms.kode_sub_gol  = b.kode_sub_golongan',  'left');
            $this->db->join('mt_pabrik_nm mp',       'mp.id_pabrik     = b.id_pabrik',          'left');
        }

        $this->db->where('b.is_active', 1);
    }

    private function _apply_search()
    {
        $search = isset($_POST['search']['value']) ? $_POST['search']['value'] : '';
        if ($search) {
            $this->db->group_start();
            $this->db->like('b.kode_brg',  $search);
            $this->db->or_like('b.nama_brg', $search);
            $this->db->group_end();
        }
    }

    private function _apply_filter()
    {
        // DataTable kirim via POST, page load biasa via GET
        $kode_golongan = !empty($_POST['kode_golongan']) ? $_POST['kode_golongan'] : (isset($_GET['kode_golongan']) ? $_GET['kode_golongan'] : '');
        $kode_sub_gol  = !empty($_POST['kode_sub_gol'])  ? $_POST['kode_sub_gol']  : (isset($_GET['kode_sub_gol'])  ? $_GET['kode_sub_gol']  : '');

        if (!empty($kode_golongan)) {
            $this->db->where('b.kode_golongan', $kode_golongan);
        }
        if (!empty($kode_sub_gol)) {
            $this->db->where('b.kode_sub_golongan', $kode_sub_gol);
        }
    }

    public function get_datatables()
    {
        $this->_main_query();
        $this->_apply_filter();
        $this->_apply_search();
        $this->db->order_by('b.nama_brg', 'ASC');
        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        return $this->db->get()->result();
    }

    public function get_all_for_export()
    {
        $this->_main_query();
        $this->_apply_filter();
        $this->db->order_by('b.nama_brg', 'ASC');
        return $this->db->get()->result();
    }

    public function count_filtered()
    {
        $this->_main_query();
        $this->_apply_filter();
        $this->_apply_search();
        return $this->db->get()->num_rows();
    }

    public function count_all()
    {
        $this->_main_query();
        $this->_apply_filter();
        return $this->db->count_all_results();
    }

    /**
     * Batch: rata-rata harga sebelum & setelah diskon dari 3 PO terakhir per kode_brg
     * Returns associative array [kode_brg => stdClass{avg_harga_satuan, avg_harga_netto}]
     */
    public function get_po_stats_batch($kode_brg_list, $flag = 'medis')
    {
        if (empty($kode_brg_list)) return array();

        $t_po_det = ($flag === 'non_medis') ? 'tc_po_nm_det' : 'tc_po_det';

        // Build safe IN list (kode_brg is alphanumeric string)
        $in = implode(',', array_map(function ($k) {
            return "'" . addslashes($k) . "'";
        }, $kode_brg_list));

        $sql = "SELECT
                    kode_brg,
                    -- Raw avg harga_satuan (pre-PPN, pre-diskon) → dipakai untuk HPP
                    CAST(AVG(CAST(harga_satuan AS FLOAT)) AS INT) AS avg_harga_satuan,
                    -- Avg harga sebelum diskon (harga + PPN) → kolom 'Sebelum Diskon'
                    CAST(AVG(CAST(harga_satuan AS FLOAT) * (1 + CAST(ppn AS FLOAT) / 100)) AS INT)
                        AS avg_sblm_diskon,
                    -- Weighted Average harga modal setelah diskon → kolom 'Setelah Diskon'
                    -- Formula sama dengan detail.php:
                    --   setelah_diskon = (harga + PPN) * (1 - diskon%)  jika diskon > 0
                    --                 = harga_satuan                     jika tanpa diskon
                    CAST(
                        SUM(
                            CAST(jumlah_besar AS FLOAT) *
                            CASE WHEN CAST(discount AS FLOAT) > 0
                                THEN CAST(harga_satuan AS FLOAT)
                                     * (1 + CAST(ppn AS FLOAT) / 100)
                                     * (1 - CAST(discount AS FLOAT) / 100)
                                ELSE CAST(harga_satuan AS FLOAT)
                            END
                        ) / NULLIF(SUM(CAST(jumlah_besar AS FLOAT)), 0)
                    AS INT) AS wa_harga_modal
                FROM (
                    SELECT kode_brg, harga_satuan, ppn, discount, jumlah_besar,
                           ROW_NUMBER() OVER (PARTITION BY kode_brg ORDER BY id_tc_po_det DESC) AS rn
                    FROM {$t_po_det}
                    WHERE kode_brg IN ({$in})
                ) x
                WHERE rn <= 3
                GROUP BY kode_brg";

        $rows = $this->db->query($sql)->result();
        $map  = array();
        foreach ($rows as $r) {
            $map[$r->kode_brg] = $r;
        }
        return $map;
    }

    /**
     * Batch: harga transaksi TERAKHIR per nama_brg
     * Returns associative array [nama_brg => stdClass{harga_satuan_terakhir, harga_jual_terakhir, tgl_terakhir}]
     * harga_satuan_terakhir = harga sebelum margin/PPN (field harga_satuan)
     * harga_jual_terakhir   = harga setelah margin/PPN  (field bill_rs)
     * Sumber: tc_trans_pelayanan (nama_tindakan = nama_brg)
     */
    public function get_sales_last_batch($kode_brg_list)
    {
        if (empty($kode_brg_list)) return array();

        $in = implode(',', array_map(function ($n) {
            return "'" . addslashes($n) . "'";
        }, $kode_brg_list));

        $sql = "SELECT s.nama_tindakan,
                       s.tgl_terakhir,
                       CAST(
                           AVG(CAST(t.harga_satuan AS FLOAT)) AS INT) AS harga_satuan_terakhir,
                       CAST(
                           SUM(CAST(t.bill_rs AS FLOAT) - 500) / NULLIF(SUM(CAST(t.jumlah AS INT)), 0)
                       AS INT) AS harga_jual_terakhir
                FROM (
                    SELECT kode_barang, nama_tindakan,
                           MAX(CAST(tgl_transaksi AS DATE)) AS tgl_terakhir
                    FROM tc_trans_pelayanan
                    WHERE kode_barang IN ({$in})
                      AND bill_rs > 0
                      AND jumlah  > 0
                    GROUP BY kode_barang, nama_tindakan
                ) s
                JOIN tc_trans_pelayanan t
                    ON  t.nama_tindakan              = s.nama_tindakan
                    AND t.kode_barang = s.kode_barang
                    AND CAST(t.tgl_transaksi AS DATE) = s.tgl_terakhir
                    AND t.bill_rs > 0
                    AND t.jumlah  > 0
                GROUP BY s.nama_tindakan, s.tgl_terakhir";

        $rows = $this->db->query($sql)->result();
        $map  = array();
        foreach ($rows as $r) {
            $map[$r->nama_tindakan] = $r;
        }
        return $map;
    }

    /**
     * Ambil 3 PO terakhir untuk kode_brg tertentu
     * (medis: tc_po_det + tc_po; non_medis: tc_po_nm_det + tc_po_nm)
     */
    public function get_po_history($kode_brg, $flag = 'medis')
    {
        $t_po_det = ($flag === 'non_medis') ? 'tc_po_nm_det' : 'tc_po_det';
        $t_po     = ($flag === 'non_medis') ? 'tc_po_nm'     : 'tc_po';

        $this->db->select('d.id_tc_po_det, d.kode_brg, d.jumlah_besar_acc as jumlah_besar, d.harga_satuan,
                           d.harga_satuan_netto, d.discount, d.jumlah_harga_netto,
                           p.no_po, p.tgl_po, d.ppn, s.namasupplier');
        $this->db->from($t_po_det . ' d');
        $this->db->join($t_po . ' p',    'p.id_tc_po      = d.id_tc_po',      'left');
        $this->db->join('mt_supplier s', 's.kodesupplier  = p.kodesupplier',  'left');
        $this->db->where('d.kode_brg', $kode_brg);
        $this->db->order_by('d.id_tc_po_det', 'DESC');
        $this->db->limit(3);
        return $this->db->get()->result();
    }

    /**
     * Riwayat harga penjualan per tanggal dari tc_trans_pelayanan
     * Di-group per hari, harga satuan = bill_rs / jumlah
     */
    public function get_sales_history($kode_brg)
    {
        $sql = "SELECT
                    CAST(tgl_transaksi AS DATE) AS tgl,
                    SUM(CAST(jumlah AS INT)) AS total_qty,
                    CAST(AVG(CAST(harga_satuan AS FLOAT)) AS INT) AS harga_satuan_avg,
                    CAST(SUM(CAST(bill_rs AS FLOAT) - 500) / NULLIF(SUM(CAST(jumlah AS INT)), 0) AS INT) AS harga_satuan,
                    SUM(CAST(bill_rs AS INT) - 500) AS total_nilai
                FROM tc_trans_pelayanan
                WHERE kode_barang = ?
                  AND bill_rs > 0
                  AND jumlah  > 0
                  AND CAST(tgl_transaksi AS DATE) >= DATEADD(MONTH, -3, CAST(GETDATE() AS DATE))
                GROUP BY CAST(tgl_transaksi AS DATE)
                ORDER BY CAST(tgl_transaksi AS DATE) DESC";
        return $this->db->query($sql, array($kode_brg))->result();
    }

    /**
     * Stok persediaan per depo/bagian untuk kode_brg tertentu
     * Sumber: mt_depo_stok_v (medis) / mt_depo_stok_nm_v (non_medis)
     * Returns array of stdClass{nama_bagian, jml_sat_kcl, satuan_kecil}
     */
    public function get_stok_depo($kode_brg, $flag = 'medis')
    {
        $view = ($flag === 'non_medis') ? 'mt_depo_stok_nm_v' : 'mt_depo_stok_v';
        $sql  = "SELECT kode_bagian, nama_bagian, jml_sat_kcl, satuan_kecil
                 FROM {$view}
                 WHERE kode_brg = ?
                   AND is_active = 1
                 ORDER BY nama_bagian";
        return $this->db->query($sql, array($kode_brg))->result();
    }

    /**
     * Ambil 1 row barang by kode_brg (untuk keperluan get_detail)
     */
    public function get_by_kode($kode_brg)
    {
        $flag  = $this->_get_flag();
        $table = $this->_get_table();
        $this->db->where('kode_brg', $kode_brg);
        return $this->db->get($table)->row();
    }

    /**
     * Ambil data usulan/permohonan yang menjadi dasar sebuah PO item
     * Relasi: tc_po_det.id_tc_permohonan_det → tc_permohonan_det → tc_permohonan
     */
    public function get_po_permohonan($id_tc_po_det, $flag = 'medis')
    {
        $t_po_det   = ($flag === 'non_medis') ? 'tc_po_nm_det'         : 'tc_po_det';
        $t_perm_det = ($flag === 'non_medis') ? 'tc_permohonan_nm_det' : 'tc_permohonan_det';
        $t_perm     = ($flag === 'non_medis') ? 'tc_permohonan_nm'     : 'tc_permohonan';

        $sql = "SELECT
                    perm.kode_permohonan,
                    perm.tgl_permohonan,
                    perm.keterangan_permohonan,
                    mb.nama_bagian,
                    pdet.jumlah_besar           AS jumlah_usulan,
                    pdet.satuan_besar,
                    pdet.jumlah_besar_acc       AS jumlah_disetujui,
                    pdet.jml_acc_pemeriksa,
                    pdet.jml_acc_penyetuju,
                    pdet.jumlah_stok_sebelumnya,
                    pdet.status_po,
                    pdet.keterangan             AS keterangan_item,
                    pdet.created_date           AS tgl_usulan,
                    NULLIF(CAST(pod.content AS INT), 0) AS rasio
                FROM {$t_po_det} pod
                JOIN {$t_perm_det} pdet ON pdet.id_tc_permohonan_det = pod.id_tc_permohonan_det
                JOIN {$t_perm} perm     ON perm.id_tc_permohonan     = pdet.id_tc_permohonan
                LEFT JOIN mt_bagian mb  ON mb.kode_bagian             = perm.kode_bagian_pemohon
                WHERE pod.id_tc_po_det = ?";

        return $this->db->query($sql, array((int)$id_tc_po_det))->row();
    }

    /**
     * Riwayat mutasi stok per kode_brg + kode_bagian (depo)
     * Sumber: tc_kartu_stok (medis) / tc_kartu_stok_nm (non_medis)
     * Menampilkan 90 hari terakhir, maks 200 baris
     */
    public function get_mutasi_stok($kode_brg, $kode_bagian, $flag = 'medis')
    {
        $tbl = ($flag === 'non_medis') ? 'tc_kartu_stok_nm' : 'tc_kartu_stok';

        $sql = "SELECT TOP 200
                    k.id_kartu,
                    k.tgl_input,
                    CAST(k.stok_awal   AS INT) AS stok_awal,
                    CAST(k.pemasukan   AS INT) AS pemasukan,
                    CAST(k.pengeluaran AS INT) AS pengeluaran,
                    CAST(k.stok_akhir  AS INT) AS stok_akhir,
                    k.keterangan,
                    k.jenis_transaksi,
                    k.nama_petugas,
                    ISNULL(u.fullname, k.nama_petugas) AS nama_lengkap
                FROM {$tbl} k
                LEFT JOIN tmp_user u ON u.user_id = k.petugas
                WHERE k.kode_brg    = ?
                  AND k.kode_bagian = ?
                  AND k.tgl_input  >= DATEADD(MONTH, -6, GETDATE())
                ORDER BY k.id_kartu DESC";

        return $this->db->query($sql, array($kode_brg, $kode_bagian))->result();
    }

    /**
     * Detail transaksi penjualan per tanggal dari tc_trans_pelayanan
     * Digunakan untuk popup detail saat tanggal di klik pada riwayat penjualan
     */
    public function get_trans_by_date($kode_brg, $tgl)
    {
        $sql = "SELECT
                    no_mr,
                    nama_pasien_layan as nama_pasien,
                    nama_tindakan,
                    CAST(jumlah    AS INT)   AS jumlah,
                    CAST(harga_satuan AS INT) AS harga_satuan,
                    CAST(bill_rs   AS INT)   AS bill_rs,
                    CAST(bill_rs   AS INT) - 500 AS nilai_bersih,
                    CAST(bill_rs   AS INT) - 500 AS total,
                    tgl_transaksi
                FROM tc_trans_pelayanan
                WHERE kode_barang = ?
                  AND CAST(tgl_transaksi AS DATE) = ?
                  AND bill_rs > 0
                  AND jumlah  > 0
                ORDER BY tgl_transaksi, nama_pasien_layan";

        return $this->db->query($sql, array($kode_brg, $tgl))->result();
    }

}
