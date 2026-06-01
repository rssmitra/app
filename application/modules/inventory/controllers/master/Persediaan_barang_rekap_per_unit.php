<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Persediaan_barang_rekap_per_unit extends MX_Controller {

    function __construct()
    {
        parent::__construct();
        $this->breadcrumbs->push('Index', 'inventory/master/Persediaan_barang_rekap_per_unit');

        if ($this->session->userdata('logged') != TRUE) {
            echo 'Session Expired !'; exit;
        }

        $this->load->model(
            'master/Persediaan_barang_rekap_per_unit_model',
            'PBRekap_model'
        );
        $this->output->enable_profiler(false);

        $flag        = isset($_POST['flag']) ? $_POST['flag'] : (isset($_GET['flag']) ? $_GET['flag'] : 'medis');
        $flag        = ($flag === 'non_medis') ? 'non_medis' : 'medis';
        $this->flag  = $flag;
        $this->title = ($flag === 'non_medis')
            ? 'Rekap Persediaan Per Unit – Barang Non Medis'
            : 'Rekap Persediaan Per Unit – Barang Medis';
    }

    // ── Halaman Index ──
    public function index()
    {
        $data = array(
            'title'       => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show(),
            'flag_string' => $this->flag,
        );
        $this->load->view('master/Persediaan_barang_rekap_per_unit/index', $data);
    }

    // ── AJAX: DataTable server-side ──
    public function get_data()
    {
        $list = $this->PBRekap_model->get_datatables();
        $data = array();
        $no   = (int)$_POST['start'];
        $dash = '<span class="text-muted" style="font-size:11px">-</span>';

        // tgl_dari / tgl_sampai untuk kolom finansial
        $tgl_dari   = !empty($_POST['tgl_dari'])   ? trim($_POST['tgl_dari'])   : date('Y-m-01');
        $tgl_sampai = !empty($_POST['tgl_sampai']) ? trim($_POST['tgl_sampai']) : date('Y-m-d');
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $tgl_dari))   $tgl_dari   = date('Y-m-01');
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $tgl_sampai)) $tgl_sampai = date('Y-m-d');

        // Batch financial summary untuk semua bagian di halaman ini
        $kode_list = array();
        foreach ($list as $r) { $kode_list[] = $r->kode_bagian; }
        $fin = $kode_list
            ? $this->PBRekap_model->get_financial_summary_for_bagians(
                $kode_list, $this->flag, $tgl_dari, $tgl_sampai)
            : array();

        foreach ($list as $row) {
            $no++;
            $f = isset($fin[$row->kode_bagian]) ? $fin[$row->kode_bagian] : null;

            // Total nilai persediaan = saldo awal + pembelian + penerimaan − penjualan
            $total_nilai = $f
                ? ((float)$f->saldo_awal_nilai + (float)$f->pembelian_nilai
                   + (float)$f->penerimaan_nilai - (float)$f->penjualan_nilai)
                : (float)$row->total_nilai;

            $nilai_display = $total_nilai > 0
                ? '<strong>Rp ' . number_format(round($total_nilai), 0, ',', '.') . '</strong>'
                : $dash;

            $fmtNilai = function($v) use ($dash) {
                $v = (float)$v;
                return $v != 0
                    ? 'Rp ' . number_format(round(abs($v)), 0, ',', '.')
                    : $dash;
            };

            $data[] = array(
                '<i class="fa fa-plus-circle pbr-dc-icon"'
                    . ' data-kode-bagian="' . htmlspecialchars($row->kode_bagian) . '"'
                    . ' style="color:#0891b2;cursor:pointer;font-size:14px"></i>',  // 0
                $no,                                                                  // 1
                htmlspecialchars($row->kode_bagian),                                  // 2 (hidden)
                '<strong><a href="#" onclick="show_detail(\'' . htmlspecialchars($row->kode_bagian) . '\')">' . htmlspecialchars($row->nama_bagian) . '</a></strong>'
                    . '<br><small class="text-muted" style="font-size:10px">'
                    . htmlspecialchars($row->kode_bagian) . '</small>',               // 3
                number_format((int)$row->jumlah_item, 0, ',', '.'),                   // 4
                $f ? $fmtNilai($f->saldo_awal_nilai)  : $dash,                       // 5 Saldo Awal
                $f ? $fmtNilai($f->pembelian_nilai)   : $dash,                       // 6 Pembelian
                $f ? $fmtNilai($f->penerimaan_nilai)  : $dash,                       // 7 Penerimaan
                $f ? $fmtNilai($f->penjualan_nilai)   : $dash,                       // 8 Penjualan
                $nilai_display,                                                        // 9 Total Nilai Persediaan
            );
        }

        echo json_encode(array(
            'draw'            => isset($_POST['draw']) ? (int)$_POST['draw'] : 1,
            'recordsTotal'    => $this->PBRekap_model->count_all(),
            'recordsFiltered' => $this->PBRekap_model->count_filtered(),
            'data'            => $data,
        ));
    }

    // ── AJAX: Summary cards (GET) ──
    public function get_summary()
    {
        $flag       = $this->flag;
        $tgl_filter = !empty($_GET['tgl_filter']) ? trim($_GET['tgl_filter']) : '';
        if ($tgl_filter && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $tgl_filter)) $tgl_filter = '';

        $s = $this->PBRekap_model->get_summary($flag, $tgl_filter);

        echo json_encode(array(
            'status'      => 200,
            'total_unit'  => $s ? (int)$s->total_unit    : 0,
            'total_jenis' => $s ? (int)$s->total_jenis   : 0,
            'total_nilai' => $s ? (float)$s->total_nilai  : 0,
        ));
    }

    // ── Halaman Laporan Cetak ──
    public function laporan()
    {
        $flag       = $this->flag;
        $tgl_filter = $this->input->get('tgl_filter', TRUE);
        if ($tgl_filter && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $tgl_filter)) {
            $tgl_filter = '';
        }

        $items   = $this->PBRekap_model->get_laporan_items($flag, $tgl_filter);
        $summary = $this->PBRekap_model->get_summary($flag, $tgl_filter);

        $data = array(
            'title'       => $this->title,
            'flag_string' => $flag,
            'tgl_filter'  => $tgl_filter,
            'items'       => $items,
            'summary'     => $summary,
        );

        $this->load->view('master/Persediaan_barang_rekap_per_unit/laporan', $data);
    }

    // ── Halaman Laporan Detail Per Unit (mutasi stok) ──
    public function laporan_detail()
    {
        $kode_bagian = $this->input->get('kode_bagian', TRUE);
        $flag        = $this->input->get('flag', TRUE);
        $flag        = ($flag === 'non_medis') ? 'non_medis' : 'medis';
        $tgl_dari    = $this->input->get('tgl_dari',   TRUE);
        $tgl_sampai  = $this->input->get('tgl_sampai', TRUE);

        if ($tgl_dari   && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $tgl_dari))   $tgl_dari   = '';
        if ($tgl_sampai && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $tgl_sampai)) $tgl_sampai = '';

        // Default: bulan berjalan
        if (empty($tgl_sampai)) $tgl_sampai = date('Y-m-d');
        if (empty($tgl_dari))   $tgl_dari   = date('Y-m-01');

        if (!$kode_bagian) {
            echo 'Kode bagian tidak valid'; return;
        }

        $bagian = $this->PBRekap_model->get_bagian_info($kode_bagian);
        $items  = $this->PBRekap_model->get_laporan_detail_items(
            $kode_bagian, $flag, $tgl_dari, $tgl_sampai
        );

        $title_flag = ($flag === 'non_medis')
            ? 'Rekap Persediaan Per Unit – Barang Non Medis'
            : 'Rekap Persediaan Per Unit – Barang Medis';

        $data = array(
            'title'       => $title_flag,
            'flag_string' => $flag,
            'kode_bagian' => $kode_bagian,
            'nama_bagian' => $bagian ? $bagian->nama_bagian : $kode_bagian,
            'tgl_dari'    => $tgl_dari,
            'tgl_sampai'  => $tgl_sampai,
            'items'       => $items,
        );

        $this->load->view('master/Persediaan_barang_rekap_per_unit/laporan_detail', $data);
    }

    // ── AJAX: Stok terakhir satu item (untuk modal konfirmasi) (GET) ──
    public function get_stok_item()
    {
        $kode_brg    = $this->input->get('kode_brg',    TRUE);
        $kode_bagian = $this->input->get('kode_bagian', TRUE);
        $flag        = $this->input->get('flag',        TRUE);
        $flag        = ($flag === 'non_medis') ? 'non_medis' : 'medis';

        if (!$kode_brg || !$kode_bagian) {
            echo json_encode(array('status' => 400, 'message' => 'Parameter tidak valid'));
            return;
        }

        $data = $this->PBRekap_model->get_last_stok_item($kode_brg, $kode_bagian, $flag);

        if (!$data) {
            echo json_encode(array('status' => 404, 'message' => 'Data stok tidak ditemukan'));
            return;
        }

        echo json_encode(array('status' => 200, 'data' => $data));
    }

    // ── AJAX: Kosongkan stok satu item (POST) ──
    public function kosongkan_stok()
    {
        $kode_brg    = $this->input->post('kode_brg',    TRUE);
        $kode_bagian = $this->input->post('kode_bagian', TRUE);
        $flag        = $this->input->post('flag',        TRUE);
        $keterangan  = $this->input->post('keterangan',  TRUE);
        $flag        = ($flag === 'non_medis') ? 'non_medis' : 'medis';

        if (!$kode_brg || !$kode_bagian) {
            echo json_encode(array('status' => 400, 'message' => 'Parameter tidak valid'));
            return;
        }

        $result = $this->PBRekap_model->kosongkan_stok_item(
            $kode_brg, $kode_bagian, $flag, $keterangan
        );
        echo json_encode($result);
    }

    // ── AJAX: Detail child-row (item stok per bagian) (GET) ──
    public function get_detail()
    {
        $kode_bagian = $this->input->get('kode_bagian', TRUE);
        $flag        = $this->input->get('flag', TRUE);
        $flag        = ($flag === 'non_medis') ? 'non_medis' : 'medis';
        $tgl_filter  = $this->input->get('tgl_filter', TRUE);
        $tgl_filter  = ($tgl_filter && preg_match('/^\d{4}-\d{2}-\d{2}$/', $tgl_filter))
            ? $tgl_filter : '';

        if (!$kode_bagian) {
            echo json_encode(array('status' => 400, 'message' => 'Kode bagian tidak valid'));
            return;
        }

        $items       = $this->PBRekap_model->get_detail_items($kode_bagian, $flag, $tgl_filter);
        $bagian      = $this->PBRekap_model->get_bagian_info($kode_bagian);
        $nama_bagian = $bagian ? $bagian->nama_bagian : $kode_bagian;

        $html = $this->load->view(
            'master/Persediaan_barang_rekap_per_unit/detail',
            array(
                'items'       => $items,
                'kode_bagian' => $kode_bagian,
                'nama_bagian' => $nama_bagian,
                'flag_string' => $flag,
                'tgl_filter'  => $tgl_filter,
            ),
            TRUE
        );

        echo json_encode(array('status' => 200, 'html' => $html));
    }

}

/* End of file Persediaan_barang_rekap_per_unit.php */
