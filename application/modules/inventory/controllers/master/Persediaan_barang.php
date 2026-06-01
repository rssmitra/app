<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Persediaan_barang extends MX_Controller {

    function __construct()
    {
        parent::__construct();
        $this->breadcrumbs->push('Index', 'inventory/master/Persediaan_barang');

        if ($this->session->userdata('logged') != TRUE) {
            echo 'Session Expired !'; exit;
        }

        $this->load->model('master/Persediaan_barang_model', 'Persediaan_barang_model');
        $this->output->enable_profiler(false);

        // POST (AJAX DataTable) diutamakan, fallback ke GET (page load biasa)
        $flag       = isset($_POST['flag']) ? $_POST['flag'] : (isset($_GET['flag']) ? $_GET['flag'] : 'medis');
        $flag       = ($flag === 'non_medis') ? 'non_medis' : 'medis';
        $this->flag  = $flag;
        $this->title = ($flag === 'non_medis') ? 'Persediaan Barang Non Medis' : 'Persediaan Barang Medis';
    }

    // ── Halaman Index ──
    public function index()
    {
        $bagian_list = $this->Persediaan_barang_model->get_bagian_list($this->flag);

        $data = array(
            'title'        => $this->title,
            'breadcrumbs'  => $this->breadcrumbs->show(),
            'flag_string'  => $this->flag,
            'bagian_list'  => $bagian_list,
        );
        $this->load->view('master/Persediaan_barang/index', $data);
    }

    // ── AJAX: DataTable server-side ──
    public function get_data()
    {
        $list = $this->Persediaan_barang_model->get_datatables();
        $data = array();
        $no   = (int)$_POST['start'];

        // Batch-fetch WA untuk semua item di halaman ini
        $tgl_filter = !empty($_POST['tgl_filter']) ? trim($_POST['tgl_filter']) : '';
        if ($tgl_filter && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $tgl_filter)) $tgl_filter = '';

        $kode_list = array();
        foreach ($list as $row) {
            $kode_list[] = $row->kode_brg;
        }
        $wa_map = $this->Persediaan_barang_model->get_wa_batch($kode_list, $this->flag, $tgl_filter);

        $dash = '<span class="text-muted" style="font-size:11px">-</span>';

        foreach ($list as $row) {
            $no++;
            $rasio = ($row->rasio > 0) ? (int)$row->rasio : 1;
            $stok  = (float)$row->total_stok;

            $wa       = isset($wa_map[$row->kode_brg]) ? $wa_map[$row->kode_brg] : null;
            $harga_wa = $wa ? ((float)$wa->wa_harga_modal / $rasio) : 0;
            $total_nilai = $stok * $harga_wa;

            $harga_display = $harga_wa > 0
                ? 'Rp ' . number_format(round($harga_wa), 0, ',', '.')
                : $dash;
            $nilai_display = $total_nilai > 0
                ? '<strong>Rp ' . number_format(round($total_nilai), 0, ',', '.') . '</strong>'
                : $dash;

            $data[] = array(
                '<i class="fa fa-plus-circle pb-dc-icon" style="color:#0891b2;cursor:pointer;font-size:14px"></i>', // 0
                $no,                                                                                              // 1
                $row->kode_brg,                                                                                   // 2
                '<strong>' . htmlspecialchars($row->nama_brg) . '</strong>'
                    . '<br><small class="text-muted">'
                    . strtoupper($row->satuan_besar) . ' / ' . strtoupper($row->satuan_kecil)
                    . '</small>',                                                                                  // 3
                strtoupper($row->satuan_kecil),                                                                   // 4
                number_format((int)round($stok), 0, ',', '.'),                                                    // 5
                $harga_display,                                                                                   // 6
                $nilai_display,                                                                                   // 7
            );
        }

        echo json_encode(array(
            'draw'            => isset($_POST['draw']) ? (int)$_POST['draw'] : 1,
            'recordsTotal'    => $this->Persediaan_barang_model->count_all(),
            'recordsFiltered' => $this->Persediaan_barang_model->count_filtered(),
            'data'            => $data,
        ));
    }

    // ── AJAX: Ringkasan total nilai persediaan (GET) ──
    public function get_summary()
    {
        $flag        = $this->flag;
        $kode_bagian = isset($_GET['kode_bagian']) ? $_GET['kode_bagian'] : '';
        $tgl_filter  = !empty($_GET['tgl_filter']) ? trim($_GET['tgl_filter']) : date('Y-m-d');
        if ($tgl_filter && !preg_match('/^\d{4}-\d{2}-\d{2}$/', $tgl_filter)) $tgl_filter = '';

        $s = $this->Persediaan_barang_model->get_summary($flag, $kode_bagian, $tgl_filter);

        echo json_encode(array(
            'status'       => 200,
            'total_jenis'  => $s ? (int)$s->total_jenis        : 0,
            'total_stok'   => $s ? (float)$s->total_stok       : 0,
            'total_nilai'  => $s ? (float)$s->total_nilai       : 0,
        ));
    }

    // ── AJAX: Detail child-row (stok per bagian + mutasi) ──
    public function get_detail()
    {
        $kode_brg   = $this->input->get('kode_brg');
        $flag       = $this->input->get('flag', TRUE);
        $flag       = ($flag === 'non_medis') ? 'non_medis' : 'medis';
        $tgl_filter = $this->input->get('tgl_filter', TRUE);
        $tgl_filter = ($tgl_filter && preg_match('/^\d{4}-\d{2}-\d{2}$/', $tgl_filter)) ? $tgl_filter : '';

        if (!$kode_brg) {
            echo json_encode(array('status' => 400, 'message' => 'Kode barang tidak valid'));
            return;
        }

        // WA harga untuk header detail (sesuai tgl_filter)
        $wa_map   = $this->Persediaan_barang_model->get_wa_batch(array($kode_brg), $flag, $tgl_filter);
        $wa       = isset($wa_map[$kode_brg]) ? $wa_map[$kode_brg] : null;

        // Rasio kemasan (satuan_besar → satuan_kecil) dari mt_barang.content
        $barang         = $this->Persediaan_barang_model->get_by_kode($kode_brg, $flag);
        $rasio          = ($barang && (int)$barang->content > 0) ? (int)$barang->content : 1;
        $harga_wa_kecil = ($wa) ? (float)$wa->wa_harga_modal / $rasio : 0;

        $stok_bagian = $this->Persediaan_barang_model->get_stok_per_bagian($kode_brg, $flag, $tgl_filter);
        $mutasi      = $this->Persediaan_barang_model->get_mutasi($kode_brg, $flag, $tgl_filter);

        // Hitung total stok dari stok_bagian
        $total_stok = 0;
        foreach ($stok_bagian as $s) {
            $total_stok += (float)$s->jml_sat_kcl;
        }

        $html = $this->load->view('master/Persediaan_barang/detail', array(
            'stok_bagian'    => $stok_bagian,
            'mutasi'         => $mutasi,
            'kode_brg'       => $kode_brg,
            'wa'             => $wa,
            'total_stok'     => $total_stok,
            'harga_wa_kecil' => $harga_wa_kecil,
            'tgl_filter'     => $tgl_filter,
        ), TRUE);

        echo json_encode(array('status' => 200, 'html' => $html));
    }

}

/* End of file Persediaan_barang.php */
