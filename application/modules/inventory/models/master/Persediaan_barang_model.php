<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Persediaan_barang_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _get_flag()
    {
        $flag = isset($_POST['flag']) ? $_POST['flag'] : (isset($_GET['flag']) ? $_GET['flag'] : 'medis');
        return ($flag === 'non_medis') ? 'non_medis' : 'medis';
    }

    private function _get_tables($flag = null)
    {
        if ($flag === null) $flag = $this->_get_flag();
        return array(
            'stok_v'  => ($flag === 'non_medis') ? 'mt_depo_stok_nm_v' : 'mt_depo_stok_v',
            'barang'  => ($flag === 'non_medis') ? 'mt_barang_nm'       : 'mt_barang',
            'po_det'  => ($flag === 'non_medis') ? 'tc_po_nm_det'       : 'tc_po_det',
            'po_hdr'  => ($flag === 'non_medis') ? 'tc_po_nm'           : 'tc_po',
            'kartu'   => ($flag === 'non_medis') ? 'tc_kartu_stok_nm'   : 'tc_kartu_stok',
        );
    }

    private function _get_filters()
    {
        $search      = isset($_POST['search']['value']) ? trim($_POST['search']['value']) : '';
        $kode_bagian = !empty($_POST['kode_bagian'])
            ? $_POST['kode_bagian']
            : (isset($_GET['kode_bagian']) ? $_GET['kode_bagian'] : '');
        $tgl_filter  = !empty($_POST['tgl_filter'])
            ? trim($_POST['tgl_filter'])
            : (!empty($_GET['tgl_filter']) ? trim($_GET['tgl_filter']) : '');

        if ($tgl_filter && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $tgl_filter)) {
            $tgl_filter = '';
        }

        return array(
            'search'      => $search,
            'kode_bagian' => $kode_bagian,
            'tgl_filter'  => $tgl_filter,
        );
    }

    /**
     * Hitung batas tanggal untuk query historis:
     *   upper = hari setelah tgl_filter (eksklusif)
     *   lower = N tahun sebelum tgl_filter (batas minimum scan, default 10 tahun)
     * Dikembalikan sebagai string YYYY-MM-DD agar SQL Server bisa pakai index range scan.
     */
    private function _date_range($tgl_filter, $years_back = 1)
    {
        return array(
            'upper' => date('Y-m-d', strtotime($tgl_filter . ' +1 day')),
            'lower' => date('Y-m-d', strtotime($tgl_filter . " -{$years_back} years")),
        );
    }

    // ── WHERE clause untuk query current (mt_depo_stok_v) ──
    private function _build_where($f, &$params)
    {
        $where = "WHERE ds.is_active = 1";
        if (!empty($f['kode_bagian'])) {
            $where  .= " AND ds.kode_bagian = ?";
            $params[] = $f['kode_bagian'];
        }
        if (!empty($f['search'])) {
            $where  .= " AND (b.kode_brg LIKE ? OR b.nama_brg LIKE ?)";
            $params[] = '%' . $f['search'] . '%';
            $params[] = '%' . $f['search'] . '%';
        }
        return $where;
    }

    // ── DataTable: list data (paginated) ──
    public function get_datatables()
    {
        $flag   = $this->_get_flag();
        $t      = $this->_get_tables($flag);
        $f      = $this->_get_filters();
        $length = isset($_POST['length']) && (int)$_POST['length'] > 0 ? (int)$_POST['length'] : 25;
        $start  = isset($_POST['start'])  ? (int)$_POST['start']  : 0;
        $from   = $start + 1;
        $to     = $start + $length;

        if (!empty($f['tgl_filter'])) {
            $dr = $this->_date_range($f['tgl_filter']);

            $params      = array();
            $inner_where = "WHERE tgl_input >= '{$dr['lower']}' AND tgl_input < '{$dr['upper']}'";
            if (!empty($f['kode_bagian'])) {
                $inner_where .= " AND kode_bagian = ?";
                $params[]    = $f['kode_bagian'];
            }
            $outer_where = '';
            if (!empty($f['search'])) {
                $outer_where = "AND (b.kode_brg LIKE ? OR b.nama_brg LIKE ?)";
                $params[]    = '%' . $f['search'] . '%';
                $params[]    = '%' . $f['search'] . '%';
            }

            $sql = "SELECT kode_brg, nama_brg, satuan_kecil, satuan_besar, rasio, total_stok
                    FROM (
                        SELECT
                            ROW_NUMBER() OVER (ORDER BY b.nama_brg) AS rn,
                            b.kode_brg, b.nama_brg, b.satuan_kecil, b.satuan_besar,
                            ISNULL(NULLIF(CAST(b.content AS INT), 0), 1) AS rasio,
                            SUM(ks.jml_sat_kcl) AS total_stok
                        FROM (
                            SELECT kode_brg, CAST(stok_akhir AS FLOAT) AS jml_sat_kcl
                            FROM (
                                SELECT kode_brg, kode_bagian, stok_akhir,
                                       ROW_NUMBER() OVER (PARTITION BY kode_brg, kode_bagian ORDER BY id_kartu DESC) AS rn2
                                FROM {$t['kartu']}
                                {$inner_where}
                            ) ki
                            WHERE ki.rn2 = 1 AND ki.stok_akhir > 0
                        ) ks
                        JOIN {$t['barang']} b ON b.kode_brg = ks.kode_brg
                        WHERE 1=1 {$outer_where}
                        GROUP BY b.kode_brg, b.nama_brg, b.satuan_kecil, b.satuan_besar, b.content
                    ) paged
                    WHERE paged.rn BETWEEN {$from} AND {$to}
                    ORDER BY paged.rn";
        } else {
            $params = array();
            $where  = $this->_build_where($f, $params);

            $sql = "SELECT kode_brg, nama_brg, satuan_kecil, satuan_besar, rasio, total_stok
                    FROM (
                        SELECT
                            ROW_NUMBER() OVER (ORDER BY b.nama_brg) AS rn,
                            b.kode_brg, b.nama_brg, b.satuan_kecil, b.satuan_besar,
                            ISNULL(NULLIF(CAST(b.content AS INT), 0), 1) AS rasio,
                            SUM(CAST(ds.jml_sat_kcl AS FLOAT)) AS total_stok
                        FROM {$t['stok_v']} ds
                        LEFT JOIN {$t['barang']} b ON b.kode_brg = ds.kode_brg
                        {$where}
                        GROUP BY b.kode_brg, b.nama_brg, b.satuan_kecil, b.satuan_besar, b.content
                    ) paged
                    WHERE paged.rn BETWEEN {$from} AND {$to}
                    ORDER BY paged.rn";
        }

        return $this->db->query($sql, $params)->result();
    }

    // ── DataTable: recordsFiltered ──
    public function count_filtered()
    {
        $flag = $this->_get_flag();
        $t    = $this->_get_tables($flag);
        $f    = $this->_get_filters();

        if (!empty($f['tgl_filter'])) {
            $dr = $this->_date_range($f['tgl_filter']);

            $params      = array();
            $inner_where = "WHERE tgl_input >= '{$dr['lower']}' AND tgl_input < '{$dr['upper']}'";
            if (!empty($f['kode_bagian'])) {
                $inner_where .= " AND kode_bagian = ?";
                $params[]    = $f['kode_bagian'];
            }
            $outer_where = '';
            if (!empty($f['search'])) {
                $outer_where = "AND (b.kode_brg LIKE ? OR b.nama_brg LIKE ?)";
                $params[]    = '%' . $f['search'] . '%';
                $params[]    = '%' . $f['search'] . '%';
            }

            $sql = "SELECT COUNT(*) AS cnt FROM (
                        SELECT ks.kode_brg
                        FROM (
                            SELECT kode_brg, CAST(stok_akhir AS FLOAT) AS jml_sat_kcl
                            FROM (
                                SELECT kode_brg, kode_bagian, stok_akhir,
                                       ROW_NUMBER() OVER (PARTITION BY kode_brg, kode_bagian ORDER BY id_kartu DESC) AS rn2
                                FROM {$t['kartu']}
                                {$inner_where}
                            ) ki
                            WHERE ki.rn2 = 1 AND ki.stok_akhir > 0
                        ) ks
                        JOIN {$t['barang']} b ON b.kode_brg = ks.kode_brg
                        WHERE 1=1 {$outer_where}
                        GROUP BY ks.kode_brg
                    ) x";
        } else {
            $params = array();
            $where  = $this->_build_where($f, $params);

            $sql = "SELECT COUNT(*) AS cnt FROM (
                        SELECT ds.kode_brg
                        FROM {$t['stok_v']} ds
                        LEFT JOIN {$t['barang']} b ON b.kode_brg = ds.kode_brg
                        {$where}
                        GROUP BY ds.kode_brg
                    ) x";
        }

        $r = $this->db->query($sql, $params)->row();
        return $r ? (int)$r->cnt : 0;
    }

    // ── DataTable: recordsTotal (filter bagian saja, tanpa search) ──
    public function count_all()
    {
        $flag = $this->_get_flag();
        $t    = $this->_get_tables($flag);
        $f    = $this->_get_filters();

        if (!empty($f['tgl_filter'])) {
            $dr = $this->_date_range($f['tgl_filter']);

            $params      = array();
            $inner_where = "WHERE tgl_input >= '{$dr['lower']}' AND tgl_input < '{$dr['upper']}'";
            if (!empty($f['kode_bagian'])) {
                $inner_where .= " AND kode_bagian = ?";
                $params[]    = $f['kode_bagian'];
            }

            $sql = "SELECT COUNT(DISTINCT kode_brg) AS cnt
                    FROM (
                        SELECT kode_brg, kode_bagian, stok_akhir,
                               ROW_NUMBER() OVER (PARTITION BY kode_brg, kode_bagian ORDER BY id_kartu DESC) AS rn2
                        FROM {$t['kartu']}
                        {$inner_where}
                    ) ki
                    WHERE ki.rn2 = 1 AND ki.stok_akhir > 0";
        } else {
            $where  = "WHERE ds.is_active = 1";
            $params = array();
            if (!empty($f['kode_bagian'])) {
                $where  .= " AND ds.kode_bagian = ?";
                $params[] = $f['kode_bagian'];
            }

            $sql = "SELECT COUNT(*) AS cnt FROM (
                        SELECT ds.kode_brg
                        FROM {$t['stok_v']} ds
                        {$where}
                        GROUP BY ds.kode_brg
                    ) x";
        }

        $r = $this->db->query($sql, $params)->row();
        return $r ? (int)$r->cnt : 0;
    }

    /**
     * Batch WA harga modal dari 3 PO terakhir (per satuan besar, setelah diskon)
     *
     * OPTIMISASI: kode_brg sudah di-filter via IN list (batch dari halaman DataTable).
     * Untuk mode tgl_filter: join ke PO header + batas tanggal langsung di SQL string.
     */
    public function get_wa_batch($kode_brg_list, $flag = 'medis', $tgl_filter = '')
    {
        if (empty($kode_brg_list)) return array();

        $t  = $this->_get_tables($flag);
        $in = implode(',', array_map(function ($k) {
            return "'" . addslashes($k) . "'";
        }, $kode_brg_list));

        $wa_calc = "CAST(
                        SUM(CAST(jumlah_besar AS FLOAT) *
                            CASE WHEN CAST(discount AS FLOAT) > 0
                                THEN CAST(harga_satuan AS FLOAT)
                                     * (1 + CAST(ppn AS FLOAT) / 100)
                                     * (1 - CAST(discount AS FLOAT) / 100)
                                ELSE CAST(harga_satuan AS FLOAT)
                            END
                        ) / NULLIF(SUM(CAST(jumlah_besar AS FLOAT)), 0)
                    AS FLOAT) AS wa_harga_modal";

        if (!empty($tgl_filter)) {
            $dr = $this->_date_range($tgl_filter);

            $sql = "SELECT kode_brg, {$wa_calc}
                    FROM (
                        SELECT d.kode_brg, d.harga_satuan, d.ppn, d.discount, d.jumlah_besar,
                               ROW_NUMBER() OVER (PARTITION BY d.kode_brg ORDER BY d.id_tc_po_det DESC) AS rn
                        FROM {$t['po_det']} d
                        JOIN {$t['po_hdr']} p ON p.id_tc_po = d.id_tc_po
                        WHERE d.kode_brg IN ({$in})
                          AND p.tgl_po >= '{$dr['lower']}'
                          AND p.tgl_po <= '{$tgl_filter}'
                    ) x
                    WHERE rn <= 3
                    GROUP BY kode_brg";
        } else {
            $sql = "SELECT kode_brg, {$wa_calc}
                    FROM (
                        SELECT kode_brg, harga_satuan, ppn, discount, jumlah_besar,
                               ROW_NUMBER() OVER (PARTITION BY kode_brg ORDER BY id_tc_po_det DESC) AS rn
                        FROM {$t['po_det']}
                        WHERE kode_brg IN ({$in})
                    ) x
                    WHERE rn <= 3
                    GROUP BY kode_brg";
        }

        $rows = $this->db->query($sql)->result();
        $map  = array();
        foreach ($rows as $r) {
            $map[$r->kode_brg] = $r;
        }
        return $map;
    }

    /**
     * Summary total di atas DataTable.
     *
     * OPTIMISASI key:
     *   - Mode current: WA subquery di-filter WHERE kode_brg IN (SELECT kode_brg FROM stok_v)
     *     sehingga tidak scan seluruh tc_po_det.
     *   - Mode historis: batas tanggal di stok & WA, mengurangi volume scan drastis.
     */
    public function get_summary($flag, $kode_bagian = '', $tgl_filter = '')
    {
        $t = $this->_get_tables($flag);

        $wa_calc = "CAST(
                        SUM(CAST(jumlah_besar AS FLOAT) *
                            CASE WHEN CAST(discount AS FLOAT) > 0
                                THEN CAST(harga_satuan AS FLOAT)
                                     * (1 + CAST(ppn AS FLOAT) / 100)
                                     * (1 - CAST(discount AS FLOAT) / 100)
                                ELSE CAST(harga_satuan AS FLOAT)
                            END
                        ) / NULLIF(SUM(CAST(jumlah_besar AS FLOAT)), 0)
                    AS FLOAT) AS wa_harga_modal";

        if (!empty($tgl_filter)) {
            $dr = $this->_date_range($tgl_filter);

            $params         = array();
            $stok_bagian_w  = '';
            if (!empty($kode_bagian)) {
                $stok_bagian_w = "AND kode_bagian = ?";
                $params[]      = $kode_bagian;
            }

            // Stok historis: last stok_akhir per (kode_brg, kode_bagian) dengan date range index-friendly
            // WA historis: filter tgl_po dengan batas tanggal agar tidak full-scan tc_po_det
            $sql = "SELECT
                        COUNT(DISTINCT ds.kode_brg) AS total_jenis,
                        SUM(ds.jml_sat_kcl) AS total_stok,
                        SUM(ds.jml_sat_kcl *
                            ISNULL(wa.wa_harga_modal, 0) / ISNULL(NULLIF(CAST(b.content AS FLOAT), 0), 1)
                        ) AS total_nilai
                    FROM (
                        SELECT kode_brg, kode_bagian, CAST(stok_akhir AS FLOAT) AS jml_sat_kcl
                        FROM (
                            SELECT kode_brg, kode_bagian, stok_akhir,
                                   ROW_NUMBER() OVER (PARTITION BY kode_brg, kode_bagian ORDER BY id_kartu DESC) AS rn
                            FROM {$t['kartu']}
                            WHERE tgl_input >= '{$dr['lower']}' AND tgl_input < '{$dr['upper']}'
                            {$stok_bagian_w}
                        ) ki
                        WHERE ki.rn = 1 AND ki.stok_akhir > 0
                    ) ds
                    LEFT JOIN {$t['barang']} b ON b.kode_brg = ds.kode_brg
                    LEFT JOIN (
                        SELECT kode_brg, {$wa_calc}
                        FROM (
                            SELECT d.kode_brg, d.harga_satuan, d.ppn, d.discount, d.jumlah_besar,
                                   ROW_NUMBER() OVER (PARTITION BY d.kode_brg ORDER BY d.id_tc_po_det DESC) AS rn
                            FROM {$t['po_det']} d
                            JOIN {$t['po_hdr']} p ON p.id_tc_po = d.id_tc_po
                            WHERE p.tgl_po >= '{$dr['lower']}' AND p.tgl_po <= '{$tgl_filter}'
                        ) x
                        WHERE rn <= 3
                        GROUP BY kode_brg
                    ) wa ON wa.kode_brg = ds.kode_brg";
        } else {
            // ── Mode current ──
            // OPTIMISASI: batasi scan tc_po_det hanya ke kode_brg yang ada di stok aktif
            $params          = array();
            $wa_brg_filter   = "WHERE kode_brg IN (SELECT DISTINCT kode_brg FROM {$t['stok_v']} WHERE is_active = 1)";
            $outer_where     = "WHERE ds.is_active = 1";

            if (!empty($kode_bagian)) {
                // kode_bagian untuk IN subquery (params pertama, karena muncul lebih awal di SQL)
                $wa_brg_filter = "WHERE kode_brg IN (
                                      SELECT DISTINCT kode_brg FROM {$t['stok_v']}
                                      WHERE is_active = 1 AND kode_bagian = ?
                                  )";
                $params[]       = $kode_bagian;  // param untuk IN subquery

                $outer_where   .= " AND ds.kode_bagian = ?";
                $params[]       = $kode_bagian;  // param untuk outer WHERE
            }

            $sql = "SELECT
                        COUNT(DISTINCT ds.kode_brg) AS total_jenis,
                        SUM(CAST(ds.jml_sat_kcl AS FLOAT)) AS total_stok,
                        SUM(CAST(ds.jml_sat_kcl AS FLOAT) *
                            ISNULL(wa.wa_harga_modal, 0) / ISNULL(NULLIF(CAST(b.content AS FLOAT), 0), 1)
                        ) AS total_nilai
                    FROM {$t['stok_v']} ds
                    LEFT JOIN {$t['barang']} b ON b.kode_brg = ds.kode_brg
                    LEFT JOIN (
                        SELECT kode_brg, {$wa_calc}
                        FROM (
                            SELECT kode_brg, harga_satuan, ppn, discount, jumlah_besar,
                                   ROW_NUMBER() OVER (PARTITION BY kode_brg ORDER BY id_tc_po_det DESC) AS rn
                            FROM {$t['po_det']}
                            {$wa_brg_filter}
                        ) x
                        WHERE rn <= 3
                        GROUP BY kode_brg
                    ) wa ON wa.kode_brg = ds.kode_brg
                    {$outer_where}";
        }

        return $this->db->query($sql, $params)->row();
    }

    /**
     * Stok per unit bagian untuk 1 kode_brg (child row detail)
     */
    public function get_stok_per_bagian($kode_brg, $flag = 'medis', $tgl_filter = '')
    {
        $t = $this->_get_tables($flag);

        if (!empty($tgl_filter)) {
            $dr = $this->_date_range($tgl_filter);

            $sql = "SELECT ks.kode_bagian,
                           ISNULL(mb.nama_bagian, ks.kode_bagian) AS nama_bagian,
                           CAST(ks.stok_akhir AS FLOAT) AS jml_sat_kcl,
                           b.satuan_kecil
                    FROM (
                        SELECT kode_brg, kode_bagian, stok_akhir,
                               ROW_NUMBER() OVER (PARTITION BY kode_bagian ORDER BY id_kartu DESC) AS rn
                        FROM {$t['kartu']}
                        WHERE kode_brg = ?
                          AND tgl_input >= '{$dr['lower']}' AND tgl_input < '{$dr['upper']}'
                    ) ks
                    JOIN {$t['barang']} b ON b.kode_brg = ks.kode_brg
                    LEFT JOIN mt_bagian mb ON mb.kode_bagian = ks.kode_bagian
                    WHERE ks.rn = 1 AND ks.stok_akhir > 0
                    ORDER BY ISNULL(mb.nama_bagian, ks.kode_bagian)";
            return $this->db->query($sql, array($kode_brg))->result();
        }

        $sql = "SELECT kode_bagian, nama_bagian,
                       CAST(jml_sat_kcl AS FLOAT) AS jml_sat_kcl,
                       satuan_kecil
                FROM {$t['stok_v']}
                WHERE kode_brg = ? AND is_active = 1
                ORDER BY nama_bagian";
        return $this->db->query($sql, array($kode_brg))->result();
    }

    /**
     * Riwayat mutasi stok (1 tahun).
     * Mode tgl_filter: 1 tahun sebelum s.d. tgl_filter.
     * Mode current: 1 tahun s.d. hari ini.
     */
    public function get_mutasi($kode_brg, $flag = 'medis', $tgl_filter = '')
    {
        $t = $this->_get_tables($flag);

        if (!empty($tgl_filter)) {
            $tgl_upper = date('Y-m-d', strtotime($tgl_filter . ' +1 day'));
            $tgl_1yr   = date('Y-m-d', strtotime($tgl_filter . ' -1 year'));
            $date_clause = "AND k.tgl_input >= '{$tgl_1yr}' AND k.tgl_input < '{$tgl_upper}'";
        } else {
            $date_clause = "AND k.tgl_input >= DATEADD(YEAR, -1, GETDATE())";
        }

        $sql = "SELECT
                    k.id_kartu,
                    k.kode_bagian,
                    ISNULL(mb.nama_bagian, k.kode_bagian) AS nama_bagian,
                    CONVERT(VARCHAR(10), k.tgl_input, 105) AS tgl_input,
                    CONVERT(VARCHAR(8),  k.tgl_input, 108) AS jam_input,
                    CAST(k.stok_awal   AS INT) AS stok_awal,
                    CAST(k.stok_akhir  AS INT) AS stok_akhir,
                    CAST(k.pemasukan   AS INT) AS pemasukan,
                    CAST(k.pengeluaran AS INT) AS pengeluaran,
                    k.jenis_transaksi, jks.nama_jenis,
                    k.keterangan,
                    ISNULL(u.fullname, k.nama_petugas) AS nama_petugas
                FROM {$t['kartu']} k
                LEFT JOIN mt_bagian mb ON mb.kode_bagian = k.kode_bagian
                LEFT JOIN tmp_user u   ON u.user_id      = k.petugas
                LEFT JOIN mt_jenis_kartu_stok jks ON jks.jenis_transaksi = k.jenis_transaksi
                WHERE k.kode_brg = ?
                  {$date_clause}
                ORDER BY k.id_kartu DESC";

        return $this->db->query($sql, array($kode_brg))->result();
    }

    /**
     * Ambil data barang (termasuk content/rasio) by kode_brg
     */
    public function get_by_kode($kode_brg, $flag = 'medis')
    {
        $t   = $this->_get_tables($flag);
        $sql = "SELECT kode_brg, nama_brg, satuan_kecil, satuan_besar, content
                FROM {$t['barang']}
                WHERE kode_brg = ?";
        return $this->db->query($sql, array($kode_brg))->row();
    }

    /**
     * List unit/bagian yang memiliki stok (untuk dropdown filter)
     */
    public function get_bagian_list($flag = 'medis')
    {
        $t   = $this->_get_tables($flag);
        $sql = "SELECT DISTINCT ds.kode_bagian, mb.nama_bagian
                FROM {$t['stok_v']} ds
                JOIN mt_bagian mb ON mb.kode_bagian = ds.kode_bagian
                WHERE ds.is_active = 1
                ORDER BY mb.nama_bagian";
        return $this->db->query($sql)->result();
    }

}

/* End of file Persediaan_barang_model.php */
