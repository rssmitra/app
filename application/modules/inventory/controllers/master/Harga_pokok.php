<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Harga_pokok extends MX_Controller {

    function __construct()
    {
        parent::__construct();

        $this->breadcrumbs->push('Index', 'inventory/master/Harga_pokok');

        if ($this->session->userdata('logged') != TRUE) {
            echo 'Session Expired !'; exit;
        }

        $this->load->model('master/Harga_pokok_model', 'Harga_pokok_model');
        $this->output->enable_profiler(false);

        // POST (AJAX DataTable) diutamakan, fallback ke GET (page load biasa)
        $flag        = isset($_POST['flag']) ? $_POST['flag'] : (isset($_GET['flag']) ? $_GET['flag'] : 'medis');
        $flag        = ($flag === 'non_medis') ? 'non_medis' : 'medis';
        $this->flag  = $flag;
        $this->title = ($flag === 'non_medis') ? 'Harga Pokok Barang Non Medis' : 'Harga Pokok Barang Medis';
    }

    public function index()
    {
        $data = array(
            'title'       => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show(),
            'flag_string' => $this->flag,
        );
        $this->load->view('master/Harga_pokok/index', $data);
    }

    public function get_data()
    {
        $list = $this->Harga_pokok_model->get_datatables();
        $data = array();
        $no   = (int)$_POST['start'];

        // ── Batch-fetch stats untuk semua barang di halaman ini ──
        $kode_list = array();
        foreach ($list as $row) {
            $kode_list[] = $row->kode_brg;
        }
        $po_stats = $this->Harga_pokok_model->get_po_stats_batch($kode_list, $this->flag);

        foreach ($list as $row) {
            $no++;

            // ── Rasio kemasan (satuan_besar → satuan_kecil) ──
            $rasio = (isset($row->content) && (int)$row->content > 0) ? (int)$row->content : 1;

            // ── Harga PO (avg 3 terakhir, dikonversi ke satuan kecil) ──
            $ps = isset($po_stats[$row->kode_brg]) ? $po_stats[$row->kode_brg] : null;
            $dash = '<span class="text-muted" style="font-size:11px">-</span>';

            // ── Harga PO & HPP & Est. Harga Jual (per satuan kecil) ──
            // HPP         = avg_harga_satuan / rasio × 1.11  (harga sblm diskon + PPN 11% tetap)
            // Est. Hj     = HPP × 1.3333                     (HPP + margin 33.33%)
            // Trend arrow = bandingkan HPP vs harga_beli master (kolom "HPP per Hari ini")

            // harga per hari ini setelah ditambah PPN
            $harga_beli_ref = (float)$row->harga_beli * 1.11;

            if ($ps) {
                $po_per_kecil_raw = (float)$ps->avg_harga_satuan / $rasio;
                $hpp_val          = $po_per_kecil_raw * 1.11;
                $harga_jual_est   = (int)round($hpp_val * (1 + 33.33 / 100));
                $sat_kecil_label  = '<br><small class="text-muted">/ ' . strtolower($row->satuan_kecil) . '</small>';

                // Trend arrow: bandingkan HPP (dari PO × 1.11) vs harga_beli master
                if ($harga_beli_ref > 0 && abs($hpp_val - $harga_beli_ref) >= 1) {
                    if ($hpp_val > $harga_beli_ref) {
                        $hpp_arrow = ' <i class="fa fa-arrow-up" style="color:#16a34a;font-size:10px"'
                            . ' title="HPP naik dari Rp ' . number_format($harga_beli_ref, 0, ',', '.') . '"></i>';
                    } else {
                        $hpp_arrow = ' <i class="fa fa-arrow-down" style="color:#dc2626;font-size:10px"'
                            . ' title="HPP turun dari Rp ' . number_format($harga_beli_ref, 0, ',', '.') . '"></i>';
                    }
                } else {
                    $hpp_arrow = '';
                }

                // Harga Modal: Sebelum Diskon = avg(harga+PPN), Setelah Diskon = WA setelah diskon
                $po_sblm_diskon = 'Rp ' . number_format((int)round($ps->avg_sblm_diskon / $rasio), 0, ',', '.') . $sat_kecil_label;
                $po_stlh_diskon = 'Rp ' . number_format((int)round($ps->wa_harga_modal  / $rasio), 0, ',', '.') . $sat_kecil_label;
                $hpp_display    = 'Rp ' . number_format((int)round($hpp_val), 0, ',', '.') . $hpp_arrow . $sat_kecil_label;
                $trx_harga_jual = '<strong>Rp ' . number_format($harga_jual_est, 0, ',', '.') . '</strong>' . $sat_kecil_label;
            } else {
                $po_sblm_diskon = $dash;
                $po_stlh_diskon = $dash;
                $hpp_display    = $dash;
                $trx_harga_jual = $dash;
            }

            $data[] = array(
                '', // 0
                $no,                                                                                             // 1
                $row->kode_brg,                                                                                  // 2
                '<strong>' . $row->nama_brg . '</strong>'
                    . '<br><small class="text-muted">' . ($row->nama_pabrik ? $row->nama_pabrik : '-') . '</small>', // 3
                strtoupper($row->satuan_besar) . ' / ' . strtoupper($row->satuan_kecil),                        // 4
                '<div class="text-right"><strong>Rp ' . number_format($harga_beli_ref, 0, ',', '.') . '</strong></div>', // 5: HPP per Hari ini
                '<div class="text-right">' . $po_sblm_diskon . '</div>',                                        // 6: Harga Modal Sebelum Diskon
                '<div class="text-right">' . $po_stlh_diskon . '</div>',                                        // 7: Harga Modal Setelah Diskon (WA)
                '<div class="text-right">' . $hpp_display    . '</div>',                                        // 8: Harga Jual - HPP
                '<div class="text-right">' . $trx_harga_jual . '</div>',                                        // 9: Harga Jual - Est.
                '<small class="text-muted">' . ($row->updated_date ? date('d/m/Y', strtotime($row->updated_date)) : '-') . '</small>', // 10
            );
        }

        echo json_encode(array(
            'draw'            => isset($_POST['draw']) ? (int)$_POST['draw'] : 1,
            'recordsTotal'    => $this->Harga_pokok_model->count_all(),
            'recordsFiltered' => $this->Harga_pokok_model->count_filtered(),
            'data'            => $data,
        ));
    }

    /**
     * Dipanggil via AJAX untuk memuat detail modal:
     * - 3 PO terakhir
     * - Riwayat harga penjualan per tanggal
     */
    public function get_detail()
    {
        $kode_brg = $this->input->get('kode_brg');
        $nama_brg = $this->input->get('nama_brg');
        $flag     = $this->input->get('flag', TRUE);

        if (!$kode_brg) {
            echo json_encode(array('status' => 400, 'message' => 'Kode barang tidak valid'));
            return;
        }

        $po_history    = $this->Harga_pokok_model->get_po_history($kode_brg, $flag);
        $sales_history = $this->Harga_pokok_model->get_sales_history($kode_brg);
        $stok_depo     = $this->Harga_pokok_model->get_stok_depo($kode_brg, $flag);

        $barang       = $this->Harga_pokok_model->get_by_kode($kode_brg);
        $rasio        = ($barang && (int)$barang->content > 0) ? (int)$barang->content : 1;
        $satuan_kecil = $barang ? strtolower($barang->satuan_kecil) : '';

        $data = array(
            'kode_brg'      => $kode_brg,
            'nama_brg'      => $nama_brg,
            'flag'          => $flag,
            'po_history'    => $po_history,
            'sales_history' => $sales_history,
            'stok_depo'     => $stok_depo,
            'rasio'         => $rasio,
            'satuan_kecil'  => $satuan_kecil,
        );

        $html = $this->load->view('master/Harga_pokok/detail', $data, TRUE);
        echo json_encode(array('status' => 200, 'html' => $html));
    }

    /**
     * AJAX: Riwayat mutasi stok per depo (popup dari tabel stok persediaan)
     */
    public function get_mutasi_stok()
    {
        $kode_brg   = $this->input->get('kode_brg');
        $kode_bagian = $this->input->get('kode_bagian');
        $nama_bagian = $this->input->get('nama_bagian');
        $flag        = $this->input->get('flag', TRUE);
        $flag        = ($flag === 'non_medis') ? 'non_medis' : 'medis';

        if (!$kode_brg || !$kode_bagian) {
            echo json_encode(array('status' => 400, 'message' => 'Parameter tidak valid'));
            return;
        }

        $rows = $this->Harga_pokok_model->get_mutasi_stok($kode_brg, $kode_bagian, $flag);

        $html = $this->load->view(
            'master/Harga_pokok/mutasi_stok',
            array(
                'rows'        => $rows,
                'kode_brg'   => $kode_brg,
                'kode_bagian' => $kode_bagian,
                'nama_bagian' => $nama_bagian,
            ),
            TRUE
        );
        echo json_encode(array('status' => 200, 'html' => $html));
    }

    /**
     * AJAX: Detail transaksi penjualan per tanggal (popup dari riwayat penjualan)
     */
    public function get_trans_detail()
    {
        $kode_brg = $this->input->get('kode_brg');
        $tgl      = $this->input->get('tgl');

        if (!$kode_brg || !$tgl) {
            echo json_encode(array('status' => 400, 'message' => 'Parameter tidak valid'));
            return;
        }

        $rows = $this->Harga_pokok_model->get_trans_by_date($kode_brg, $tgl);

        $html = $this->load->view(
            'master/Harga_pokok/trans_detail',
            array('rows' => $rows, 'kode_brg' => $kode_brg, 'tgl' => $tgl),
            TRUE
        );
        echo json_encode(array('status' => 200, 'html' => $html));
    }

    /**
     * AJAX: Ambil data permohonan/usulan dasar PO berdasarkan id_tc_po_det
     */
    public function get_po_permohonan()
    {
        $id   = (int)$this->input->get('id_tc_po_det');
        $flag = $this->input->get('flag', TRUE);
        $flag = ($flag === 'non_medis') ? 'non_medis' : 'medis';

        if (!$id) {
            echo json_encode(array('status' => 400, 'message' => 'ID tidak valid'));
            return;
        }

        $perm = $this->Harga_pokok_model->get_po_permohonan($id, $flag);

        if (!$perm) {
            echo json_encode(array(
                'status'  => 404,
                'message' => 'Tidak ditemukan data permohonan untuk PO ini. PO mungkin dibuat langsung tanpa usulan.',
            ));
            return;
        }

        $html = $this->load->view('master/Harga_pokok/po_permohonan', array('perm' => $perm), TRUE);
        echo json_encode(array('status' => 200, 'html' => $html));
    }

}

/* End of file Harga_pokok.php */
