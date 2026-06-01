<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rm_soap_model extends CI_Model {

    var $table  = 'view_cppt';
    var $column = array('v.no_mr', 'mt_master_pasien.nama_pasien', 'v.tanggal');
    var $order  = array('v.tanggal' => 'DESC');

    public function __construct()
    {
        parent::__construct();
    }

    private function _main_query()
    {
        $this->db->select('
            v.id,
            v.no_mr,
            v.no_kunjungan,
            v.no_registrasi,
            v.tanggal,
            v.tipe,
            v.flag,
            v.ppa,
            v.nama_ppa,
            v.subjective,
            v.objective,
            v.assesment,
            v.planning,
            v.kode_icd_diagnosa,
            v.diagnosa_sekunder,
            v.kode_icd9,
            v.text_icd9,
            v.tinggi_badan,
            v.berat_badan,
            v.tekanan_darah,
            v.nadi,
            v.suhu,
            v.resep_farmasi,
            v.tgl_kontrol_kembali,
            v.catatan_kontrol_kembali,
            v.riwayat_penyakit_dahulu,
            v.riwayat_penyakit_dahulu_ket,
            v.riwayat_operasi,
            v.riwayat_operasi_ket,
            v.riwayat_alergi,
            v.riwayat_alergi_ket,
            v.catatan_assesmen,
            v.resep_iter,
            v.jumlah_iter,
            v.jenis_form,
            v.jenis_pengkajian,
            v.is_verified,
            v.verified_by,
            v.verified_date,
            v.created_by,
            v.created_date,
            mt_master_pasien.nama_pasien,
            mt_master_pasien.tgl_lhr,
            mt_master_pasien.jen_kelamin
        ', FALSE);
        $this->db->from('view_cppt v');
        $this->db->join('mt_master_pasien', 'mt_master_pasien.no_mr = v.no_mr', 'left');
        // $this->db->where("v.flag = 'cppt'");
        $this->db->where('(v.jenis_form IS NULL OR v.jenis_form = 0)', NULL, FALSE);

        $search_by = isset($_GET['search_by']) ? $_GET['search_by'] : 'no_mr';
        $keyword   = isset($_GET['keyword'])   ? trim($_GET['keyword']) : '';

        if ($keyword !== '') {
            if ($search_by === 'nama_pasien') {
                $this->db->like('mt_master_pasien.nama_pasien', $keyword);
            } else {
                // Default: cari berdasarkan No. MR
                $this->db->where('v.no_mr', $keyword);
            }
        }

        if (isset($_GET['from_tgl']) && $_GET['from_tgl'] != '') {
            $from = $_GET['from_tgl'];
            $to   = (isset($_GET['to_tgl']) && $_GET['to_tgl'] != '') ? $_GET['to_tgl'] : $from;
            $this->db->where("CAST(v.tanggal AS DATE) BETWEEN '$from' AND '$to'", NULL, FALSE);
        } else {
            // Default: data bulan berjalan saja agar tidak scan semua record
            $this->db->where("YEAR(v.tanggal) = YEAR(GETDATE()) AND MONTH(v.tanggal) = MONTH(GETDATE())", NULL, FALSE);
        }

        if (isset($_GET['tipe']) && $_GET['tipe'] != '') {
            $this->db->where('v.tipe', $_GET['tipe']);
        }
    }

    private function _get_datatables_query()
    {
        $this->_main_query();

        $i = 0;
        foreach ($this->column as $item) {
            if ($_POST['search']['value']) {
                ($i === 0)
                    ? $this->db->like($item, $_POST['search']['value'])
                    : $this->db->or_like($item, $_POST['search']['value']);
            }
            $column[$i] = $item;
            $i++;
        }

        if (isset($_POST['order'])) {
            $this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } else if (isset($this->order)) {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }

    /**
     * Ambil data SOAP langsung per no_mr (tanpa DataTables POST context).
     * Digunakan oleh embed viewer (Rm_soap_embed).
     */
    public function get_by_nomr($no_mr, $limit = 50)
    {
        $this->db->select('
            v.id, v.no_mr, v.no_kunjungan, v.no_registrasi, v.tanggal,
            v.tipe, v.flag, v.ppa, v.nama_ppa, v.subjective, v.objective,
            v.assesment, v.planning, v.kode_icd_diagnosa, v.diagnosa_sekunder,
            v.kode_icd9, v.text_icd9, v.tinggi_badan, v.berat_badan,
            v.tekanan_darah, v.nadi, v.suhu, v.tgl_kontrol_kembali,
            v.catatan_kontrol_kembali, v.riwayat_penyakit_dahulu,
            v.riwayat_penyakit_dahulu_ket, v.riwayat_operasi,
            v.riwayat_operasi_ket, v.riwayat_alergi, v.riwayat_alergi_ket,
            v.catatan_assesmen, v.resep_iter, v.jumlah_iter,
            v.jenis_form, v.jenis_pengkajian, v.is_verified,
            mt_master_pasien.nama_pasien, mt_master_pasien.tgl_lhr,
            mt_master_pasien.jen_kelamin
        ', FALSE);
        $this->db->from('view_cppt v');
        $this->db->join('mt_master_pasien', 'mt_master_pasien.no_mr = v.no_mr', 'left');
        $this->db->where('(v.jenis_form IS NULL OR v.jenis_form = 0)', NULL, FALSE);
        $this->db->where('v.no_mr', $no_mr);
        $this->db->order_by('v.tanggal', 'DESC');
        $this->db->limit($limit);

        $this->db->db_debug = FALSE;
        $query = $this->db->get();
        $this->db->db_debug = TRUE;

        if (!$query) return array();
        return $query->result();
    }

    public function get_datatables()
    {
        $this->_get_datatables_query();
        if ($_POST['length'] != -1) {
            $this->db->limit($_POST['length'], $_POST['start']);
        }
        $query = $this->db->get();
        
        // echo $this->db->last_query();die;
        return $query->result();
    }

    public function count_filtered()
    {
        $this->_get_datatables_query();
        return $this->db->count_all_results();
    }

    public function count_all()
    {
        $this->_main_query();
        return $this->db->count_all_results();
    }

    public function get_for_export()
    {
        $this->_main_query();
        $this->db->order_by('v.tanggal', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    /**
     * Batch: eresep per no_kunjungan
     * Returns array indexed by no_kunjungan
     */
    public function get_eresep_by_kunjungan(array $no_kunjungan)
    {
        if (empty($no_kunjungan)) return array();
        $this->db->select('
            fr_tc_pesan_resep_detail.no_kunjungan,
            fr_tc_pesan_resep_detail.kode_pesan_resep,
            fr_tc_pesan_resep_detail.kode_brg,
            fr_tc_pesan_resep_detail.nama_brg,
            fr_tc_pesan_resep_detail.jml_dosis,
            fr_tc_pesan_resep_detail.jml_dosis_obat,
            fr_tc_pesan_resep_detail.satuan_obat,
            fr_tc_pesan_resep_detail.aturan_pakai,
            fr_tc_pesan_resep_detail.jml_pesan,
            fr_tc_pesan_resep_detail.keterangan,
            fr_tc_far.kode_trans_far,
            fr_tc_far.tgl_trans
        ', FALSE);
        $this->db->from('fr_tc_pesan_resep_detail');
        $this->db->join('fr_tc_far', 'fr_tc_far.kode_pesan_resep = fr_tc_pesan_resep_detail.kode_pesan_resep', 'left');
        $this->db->where_in('fr_tc_pesan_resep_detail.no_kunjungan', $no_kunjungan);
        $this->db->where('fr_tc_pesan_resep_detail.parent', '0');
        $this->db->order_by('fr_tc_far.kode_trans_far', 'ASC');
        $this->db->order_by('fr_tc_pesan_resep_detail.id', 'ASC');
        $this->db->db_debug = FALSE;
        $query = $this->db->get();
        $this->db->db_debug = TRUE;
        if ( ! $query) return array();
        $out = array();
        foreach ($query->result() as $r) {
            $out[$r->no_kunjungan][] = $r;
        }
        return $out;
    }

    /**
     * Batch: EMR files per no_registrasi
     * Returns array indexed by no_registrasi
     */
    public function get_emr_files_by_kunjungan(array $no_registrasi)
    {
        if (empty($no_registrasi)) return array();
        $this->db->select('csm_dokumen_export.no_registrasi, csm_dex_nama_dok, csm_dex_fullpath, base_url_dok');
        $this->db->where_in('csm_dokumen_export.no_registrasi', $no_registrasi);
        $this->db->db_debug = FALSE;
        $query = $this->db->get('csm_dokumen_export');
        $this->db->db_debug = TRUE;
        if ( ! $query) return array();
        $out = array();
        foreach ($query->result() as $r) {
            $out[$r->no_registrasi][] = $r;
        }
        return $out;
    }

    /**
     * Batch: pemeriksaan penunjang per no_registrasi
     * Returns array indexed by no_registrasi
     */
    public function get_penunjang_by_kunjungan(array $no_registrasi)
    {
        if (empty($no_registrasi)) return array();

        $this->db->select('
            tc_kunjungan.no_registrasi,
            tc_kunjungan.no_kunjungan,
            mt_karyawan.nama_pegawai AS dokter,
            asal.nama_bagian AS asal_bagian,
            tujuan.nama_bagian AS tujuan_bagian,
            tc_kunjungan.tgl_masuk,
            pm_tc_penunjang.status_isihasil,
            pm_tc_penunjang.kode_penunjang,
            pm_tc_penunjang.status_daftar,
            tc_kunjungan.kode_bagian_tujuan,
            pm_tc_penunjang.tgl_daftar,
            pm_tc_penunjang.tgl_isihasil,
            pm_tc_penunjang.tgl_periksa
        ', FALSE);

        $this->db->select("CAST((
            SELECT '|' + nama_tindakan
            FROM tc_trans_pelayanan
            LEFT JOIN pm_tc_penunjang p2 ON p2.no_kunjungan = tc_trans_pelayanan.no_kunjungan
            LEFT JOIN tc_kunjungan s ON s.no_kunjungan = p2.no_kunjungan
            WHERE s.no_kunjungan = tc_kunjungan.no_kunjungan
            FOR XML PATH('')) AS varchar(max)) AS nama_tarif", FALSE);

        $this->db->from('tc_kunjungan');
        $this->db->join('mt_karyawan', 'mt_karyawan.kode_dokter = tc_kunjungan.kode_dokter', 'left');
        $this->db->join('mt_bagian AS asal', 'asal.kode_bagian = tc_kunjungan.kode_bagian_asal', 'left');
        $this->db->join('mt_bagian AS tujuan', 'tujuan.kode_bagian = tc_kunjungan.kode_bagian_tujuan', 'left');
        $this->db->join('pm_tc_penunjang', 'pm_tc_penunjang.no_kunjungan = tc_kunjungan.no_kunjungan', 'left');

        $this->db->where_in('tc_kunjungan.no_registrasi', $no_registrasi);
        $this->db->where('pm_tc_penunjang.tgl_isihasil IS NOT NULL', NULL, FALSE);
        $this->db->where("SUBSTRING(tc_kunjungan.kode_bagian_tujuan, 1, 2) = '05'", NULL, FALSE);
        $this->db->order_by('pm_tc_penunjang.tgl_isihasil', 'DESC');

        $this->db->db_debug = FALSE;
        $query = $this->db->get();
        $this->db->db_debug = TRUE;

        // echo $this->db->last_query();die;

        if ( ! $query) return array();
        $out = array();
        foreach ($query->result() as $r) {
            $out[$r->no_registrasi][] = $r;
        }
        return $out;
    }
}
