<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Eks_profit_by_close_bill extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'eksekutif/Eks_profit_by_close_bill');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('eksekutif/Eks_profit_by_close_bill_model', 'Eks_profit_by_close_bill');
        $this->load->model('adm_pasien/Adm_pasien_model', 'Adm_pasien');
        $this->load->model('billing/Billing_model', 'Billing');
        $this->load->model('casemix/Csm_billing_pasien_model', 'Csm_billing_pasien');
        /*enable profiler*/
        $this->output->enable_profiler(false);
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'Title';

    }

    public function index() { 
        //echo '<pre>';print_r($this->session->all_userdata());
        /*define variable data*/
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show(),
            'flag' => isset($_GET['flag'])?$_GET['flag']:'RJ',
        );
        /*show datatables*/
        $data['dataTables'] = $this->load->view('eksekutif/Eks_profit_by_close_bill/temp_trans_pasien', $data, true);
        /*load view index*/
        $this->load->view('eksekutif/Eks_profit_by_close_bill/index', $data);
    }

    public function find_data()
    {   
        $output = array( "data" => http_build_query($_POST) . "\n" );
        echo json_encode($output);
    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->Eks_profit_by_close_bill->get_datatables();
        // list PRB
        $list_prb = $this->Eks_profit_by_close_bill->get_trx_prb();
        // list pembelian bebas
        $list_pb = $this->Eks_profit_by_close_bill->get_trx_pembelian_bebas();
        // echo "<pre>";print_r($list_prb);die;
        $data = [];
        $ttl_bill_dr1 = [];
        $ttl_bill_dr2 = [];
        $ttl_bhp = [];
        $ttl_bhp_apotik = [];
        $ttl_bill_kamar = [];
        $ttl_bill_lab = [];
        $ttl_bill_rad = [];
        $ttl_kamar_tindakan = [];
        $ttl_alat_rs = [];
        $ttl_profit = [];
        $ttl_total_bill = [];
        $getDtByKategori = [];
        $getRevenueByKategori = [];
        $getCostByKategori = [];
        $getProfitByKategori = [];
        $totalPasienKlaim = [];
        $totalRpKlaimInacbgs = [];
        $totalRpKlaimRs = [];
        $totalRpBillRsNKlaim = [];
        $totalPasienNoKlaim = [];
        $totalRpNoKlaimInacbgs = [];
        $totalRpNoKlaimRs = [];
        $totalRpBillRsNKlaim = [];

        // trx PB
        $no= 0;
        foreach ($list_pb as $kpb => $vpb) {
            $no++;
            $row = array();
            $row[] = '<div class="center">'.$no.'</div>';
            $row[] = '<div class="center">PB</div>';
            $row[] = '<div class="center">'.$this->tanggal->formatDateDmy($vpb->tgl_jam).'</div>';
            $row[] = '<div class="center">'.$this->tanggal->formatDateDmy($vpb->tgl_jam).'</div>';
            $row[] = strtoupper($vpb->no_mr);
            $row[] = strtoupper($vpb->nama_pasien);
            $row[] = '';
            $row[] = '';
            $row[] = '';
            $row[] = '';
            $row[] = '';
            // $row[] = '<div style="text-align: right">0</div>';
            $row[] = '<div style="text-align: right">0</div>';
            $row[] = '<div style="text-align: right">0</div>';
            $row[] = '<div style="text-align: right">'.number_format((int)$vpb->total).'</div>';
            $row[] = '<div style="text-align: right">0</div>';
            $row[] = '<div style="text-align: right">0</div>';
            $row[] = '<div style="text-align: right">0</div>';
            $row[] = '<div style="text-align: right">0</div>';
            $row[] = '<div style="text-align: right">0</div>';
            $row[] = '<div style="text-align: right">0</div>';
            $row[] = '<div style="text-align: right">'.number_format((int)$vpb->total).'</div>';
            $row[] = '<div style="text-align: right">0</div>';
            $row[] = '<div style="text-align: right">0</div>';
            $data[] = $row;

            $getDtByTipe['PB'][] = $vpb;
            $getRevenueByTipe['PB'][] = $vpb->total;

        }

        // trx PRB
        $no = 0;
        foreach ($list_prb as $key => $value) {
            $no++;
            $row = array();
            $row[] = '<div class="center">'.$no.'</div>';
            $row[] = '<div class="center">PRB</div>';
            $row[] = '<div class="center">'.$this->tanggal->formatDateDmy($value->tgl_trans).'</div>';
            $row[] = '<div class="center">'.$this->tanggal->formatDateDmy($value->tgl_trans).'</div>';
            $row[] = strtoupper($value->no_mr);
            $row[] = strtoupper($value->nama_pasien);
            $row[] = $value->dokter_pengirim;
            $row[] = $value->bagian_asal;
            $row[] = 'BPJS';
            $row[] = 'BPJS KESEHATAN';
            $row[] = $value->no_sep;
            
            // $row[] = '<div style="text-align: right">0</div>';
            $row[] = '<div style="text-align: right">0</div>';
            $row[] = '<div style="text-align: right">0</div>';
            $row[] = '<div style="text-align: right">'.number_format((int)$value->total).'</div>';
            $row[] = '<div style="text-align: right">0</div>';
            $row[] = '<div style="text-align: right">0</div>';
            $row[] = '<div style="text-align: right">0</div>';
            $row[] = '<div style="text-align: right">0</div>';
            $row[] = '<div style="text-align: right">0</div>';
            $row[] = '<div style="text-align: right">0</div>';
            $row[] = '<div style="text-align: right">'.number_format((int)$value->total).'</div>';
            $row[] = '<div style="text-align: right">0</div>';
            $row[] = '<div style="text-align: right">0</div>';
            $data[] = $row;

            $getDtByTipe['PRB'][] = $value;
            $getRevenueByTipe['PRB'][] = $value->total;

        }

        // trx pelayanan rj/ri
        foreach ($list as $key_list=>$row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center">'.$no.'</div>';
            $row[] = '<div class="center">'.$row_list->tipe.'</div>';
            $row[] = '<div class="center">'.$this->tanggal->formatDateDmy($row_list->tgl_masuk).'</div>';
            $row[] = '<div class="center">'.$this->tanggal->formatDateDmy($row_list->tgl_keluar).'</div>';
            $row[] = strtoupper($row_list->no_mr);
            $row[] = strtoupper($row_list->nama_pasien);
            $row[] = $row_list->dokter;
            $row[] = $row_list->spesialis_dokter;
            $row[] = $row_list->penjamin;
            $row[] = $row_list->nama_asuransi;
            $row[] = $row_list->no_sep;
            // total bill
            $total_bill = $row_list->total_billing;
            // bhp apotik
            $margin_apt = 33/100;
            $margin_apotik = $row_list->bill_apotik * $margin_apt;
            $bhp_apotik = $row_list->bill_apotik;
            // total pendapatan
            // $profit = $row_list->bill_rs - ($row_list->bhp + $row_list->bill_kamar + $row_list->kamar_tindakan + $row_list->alat_rs + $bhp_apotik);
            $profit = $row_list->pendapatan_rs + $row_list->bill_lab + $row_list->bill_rad;
            $cost = $row_list->bill_dr1 + $row_list->bill_dr2 + $row_list->bhp + $row_list->bill_kamar + $row_list->kamar_tindakan + $row_list->alat_rs + $bhp_apotik;
            $jasa_dokter = $row_list->bill_dr1 + $row_list->bill_dr2;
            $row[] = '<div style="text-align: right">'.number_format((int)$jasa_dokter).'</div>';
            // $row[] = '<div style="text-align: right">'.number_format((int)$row_list->bill_dr2).'</div>';
            $row[] = '<div style="text-align: right">'.number_format((int)$row_list->bhp).'</div>';
            $row[] = '<div style="text-align: right">'.number_format((int)$bhp_apotik).'</div>';
            $row[] = '<div style="text-align: right">'.number_format((int)$row_list->bill_lab).'</div>';
            $row[] = '<div style="text-align: right">'.number_format((int)$row_list->bill_rad).'</div>';
            $row[] = '<div style="text-align: right">'.number_format((int)$row_list->bill_kamar).'</div>';
            $row[] = '<div style="text-align: right">'.number_format((int)$row_list->kamar_tindakan).'</div>';
            $row[] = '<div style="text-align: right">'.number_format((int)$row_list->alat_rs).'</div>';
            $row[] = '<div style="text-align: right">'.number_format((int)$profit).'</div>';
            $row[] = '<div style="text-align: right">'.number_format((int)$row_list->total_billing).'</div>';
            $row[] = '<div style="text-align: right">'.number_format((int)$row_list->tarif_inacbgs).'</div>';
            $row[] = '<div style="text-align: right">'.number_format((int)$row_list->tarif_rs_klaim_ncc).'</div>';

            $ttl_bill_dr1[] = $row_list->bill_dr1;
            $ttl_bill_dr2[] = $row_list->bill_dr2;
            $ttl_bhp[] = $row_list->bhp;
            $ttl_bhp_apotik[] = $bhp_apotik;
            $ttl_bill_kamar[] = $row_list->bill_kamar;
            $ttl_bill_lab[] = $row_list->bill_lab;
            $ttl_bill_rad[] = $row_list->bill_rad;
            $ttl_kamar_tindakan[] = $row_list->kamar_tindakan;
            $ttl_alat_rs[] = $row_list->alat_rs;
            $ttl_profit[] = $profit;
            $ttl_total_bill[] = $total_bill;

            $data[] = $row;
            // jumlah pasien berdasarkan kategori penjamin
            $getDtByKategori[$row_list->tipe][$row_list->penjamin][] = $row_list;
            $getRevenueByKategori[$row_list->tipe][$row_list->penjamin][] = $total_bill;
            $getCostByKategori[$row_list->tipe][$row_list->penjamin][] = $cost;
            $getProfitByKategori[$row_list->tipe][$row_list->penjamin][] = $profit;

            $getDtByTipe[$row_list->tipe][] = $row_list;
            $getRevenueByTipe[$row_list->tipe][] = $total_bill;
            $getCostByTipe[$row_list->tipe][] = $cost;
            $getProfitByTipe[$row_list->tipe][] = $profit;

            // rekap bpjs
            if($row_list->tarif_rs_klaim_ncc > 0){
                $totalPasienKlaim[$row_list->tipe][] = $row_list;
                $totalRpKlaimInacbgs[$row_list->tipe][] = $row_list->tarif_inacbgs;
                $totalRpKlaimRs[$row_list->tipe][] = $row_list->tarif_rs_klaim_ncc;
                $totalRpBillRsKlaim[$row_list->tipe][] = $total_bill;
            }else{
                if($row_list->penjamin == 'BPJS'){
                    $totalPasienNoKlaim[$row_list->tipe][] = $row_list;
                    $totalRpNoKlaimInacbgs[$row_list->tipe][] = $row_list->tarif_inacbgs;
                    $totalRpNoKlaimRs[$row_list->tipe][] = $row_list->tarif_rs_klaim_ncc;
                    $totalRpBillRsNKlaim[$row_list->tipe][] = $total_bill;
                }
            }

            // rekap poliklinik
            if($row_list->kode_bag == '01' && $row_list->tipe == 'RJ'){
                // poliklinik
                if(!in_array($row_list->kode_bagian, ['013101', '010901']) ){
                    $getDtByTipe['INSTALASI_RJ'][] = $row_list;
                    $getRevenueByTipe['INSTALASI_RJ'][] = $total_bill;
                }
                // HD
                if(in_array($row_list->kode_bagian, ['013101']) ){
                    $getDtByTipe['HD'][] = $row_list;
                    $getRevenueByTipe['HD'][] = $total_bill;
                }

                // MCU
                if(in_array($row_list->kode_bagian, ['010901']) ){
                    $getDtByTipe['MCU'][] = $row_list;
                    $getRevenueByTipe['MCU'][] = $total_bill;
                }

            }


            if($row_list->kode_bag == '02' && $row_list->tipe == 'RJ'){
                // IGD
                $getDtByTipe['IGD'][] = $row_list;
                $getRevenueByTipe['IGD'][] = $total_bill;
            }

            if($row_list->kode_bagian == '050101' && $row_list->tipe == 'RJ'){
                // LAB
                $getDtByTipe['LAB'][] = $row_list;
                $getRevenueByTipe['LAB'][] = $total_bill;
            }

            if($row_list->kode_bagian == '050201' && $row_list->tipe == 'RJ'){
                // RAD
                $getDtByTipe['RAD'][] = $row_list;
                $getRevenueByTipe['RAD'][] = $total_bill;
            }

            if($row_list->kode_bagian == '050301' && $row_list->tipe == 'RJ'){
                // FISIO
                $getDtByTipe['FISIO'][] = $row_list;
                $getRevenueByTipe['FISIO'][] = $total_bill;
            }

        }

        // echo "<pre>";print_r($getDtByKategori);die;

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => count($list),
                        "recordsFiltered" => count($list),
                        "data" => $data,
                        "ttl_bill_dr1" => array_sum($ttl_bill_dr1),
                        "ttl_bill_dr2" => array_sum($ttl_bill_dr2),
                        "ttl_bhp" => array_sum($ttl_bhp),
                        "ttl_bhp_apotik" => array_sum($ttl_bhp_apotik),
                        "ttl_bill_kamar" => array_sum($ttl_bill_kamar),
                        "ttl_bill_lab" => array_sum($ttl_bill_lab),
                        "ttl_bill_rad" => array_sum($ttl_bill_rad),
                        "ttl_kamar_tindakan" => array_sum($ttl_kamar_tindakan),
                        "ttl_alat_rs" => array_sum($ttl_alat_rs),
                        "ttl_profit" => array_sum($ttl_profit),
                        "ttl_total_bill" => array_sum($ttl_total_bill),
                        'start_date' => isset($_GET['start_date'])?$this->tanggal->formatDateDmy($_GET['start_date']):date('d/m/Y'),
                        'end_date' => isset($_GET['end_date'])?$this->tanggal->formatDateDmy($_GET['end_date']):date('d/m/Y'),
                        // rekap kategori rajal
                        "um_ttl_pasien" => isset($getDtByKategori['RJ']['UMUM']) ? count($getDtByKategori['RJ']['UMUM']) : 0,
                        "asuransi_ttl_pasien" => isset($getDtByKategori['RJ']['ASURANSI']) ? count($getDtByKategori['RJ']['ASURANSI']) : 0,
                        "bpjs_ttl_pasien" => isset($getDtByKategori['RJ']['BPJS']) ? count($getDtByKategori['RJ']['BPJS']) : 0,
                        "naker_ttl_pasien" => isset($getDtByKategori['RJ']['NAKER']) ? count($getDtByKategori['RJ']['NAKER']) : 0,
                        
                        "um_ttl_revenue" => isset($getRevenueByKategori['RJ']['UMUM']) ? array_sum($getRevenueByKategori['RJ']['UMUM']) : 0,
                        "asuransi_ttl_revenue" => isset($getRevenueByKategori['RJ']['ASURANSI']) ? array_sum($getRevenueByKategori['RJ']['ASURANSI']) : 0,
                        "bpjs_ttl_revenue" => isset($getRevenueByKategori['RJ']['BPJS']) ? array_sum($getRevenueByKategori['RJ']['BPJS']) : 0,
                        "naker_ttl_revenue" => isset($getRevenueByKategori['RJ']['NAKER']) ? array_sum($getRevenueByKategori['RJ']['NAKER']) : 0,
                        
                        "um_ttl_cost" => isset($getCostByKategori['RJ']['UMUM']) ? array_sum($getCostByKategori['RJ']['UMUM']) : 0,
                        "asuransi_ttl_cost" => isset($getCostByKategori['RJ']['ASURANSI']) ? array_sum($getCostByKategori['RJ']['ASURANSI']) : 0,
                        "bpjs_ttl_cost" => isset($getCostByKategori['RJ']['BPJS']) ? array_sum($getCostByKategori['RJ']['BPJS']) : 0,
                        "naker_ttl_cost" => isset($getCostByKategori['RJ']['NAKER']) ? array_sum($getCostByKategori['RJ']['NAKER']) : 0,

                        "um_ttl_profit" => isset($getProfitByKategori['RJ']['UMUM']) ? array_sum($getProfitByKategori['RJ']['UMUM']) : 0,
                        "asuransi_ttl_profit" => isset($getProfitByKategori['RJ']['ASURANSI']) ? array_sum($getProfitByKategori['RJ']['ASURANSI']) : 0,
                        "bpjs_ttl_profit" => isset($getProfitByKategori['RJ']['BPJS']) ? array_sum($getProfitByKategori['RJ']['BPJS']) : 0,
                        "naker_ttl_profit" => isset($getProfitByKategori['RJ']['NAKER']) ? array_sum($getProfitByKategori['RJ']['NAKER']) : 0,

                        // rekap kategori ranap
                        "ri_um_ttl_pasien" => isset($getDtByKategori['RI']['UMUM']) ? count($getDtByKategori['RI']['UMUM']) : 0,
                        "ri_asuransi_ttl_pasien" => isset($getDtByKategori['RI']['ASURANSI']) ? count($getDtByKategori['RI']['ASURANSI']) : 0,
                        "ri_bpjs_ttl_pasien" => isset($getDtByKategori['RI']['BPJS']) ? count($getDtByKategori['RI']['BPJS']) : 0,
                        "ri_naker_ttl_pasien" => isset($getDtByKategori['RI']['NAKER']) ? count($getDtByKategori['RI']['NAKER']) : 0,
                        
                        "ri_um_ttl_revenue" => isset($getRevenueByKategori['RI']['UMUM']) ? array_sum($getRevenueByKategori['RI']['UMUM']) : 0,
                        "ri_asuransi_ttl_revenue" => isset($getRevenueByKategori['RI']['ASURANSI']) ? array_sum($getRevenueByKategori['RI']['ASURANSI']) : 0,
                        "ri_bpjs_ttl_revenue" => isset($getRevenueByKategori['RI']['BPJS']) ? array_sum($getRevenueByKategori['RI']['BPJS']) : 0,
                        "ri_naker_ttl_revenue" => isset($getRevenueByKategori['RI']['NAKER']) ? array_sum($getRevenueByKategori['RI']['NAKER']) : 0,
                        
                        "ri_um_ttl_cost" => isset($getCostByKategori['RI']['UMUM']) ? array_sum($getCostByKategori['RI']['UMUM']) : 0,
                        "ri_asuransi_ttl_cost" => isset($getCostByKategori['RI']['ASURANSI']) ? array_sum($getCostByKategori['RI']['ASURANSI']) : 0,
                        "ri_bpjs_ttl_cost" => isset($getCostByKategori['RI']['BPJS']) ? array_sum($getCostByKategori['RI']['BPJS']) : 0,
                        "ri_naker_ttl_cost" => isset($getCostByKategori['RI']['NAKER']) ? array_sum($getCostByKategori['RI']['NAKER']) : 0,
                        
                        "ri_um_ttl_profit" => isset($getProfitByKategori['RI']['UMUM']) ? array_sum($getProfitByKategori['RI']['UMUM']) : 0,
                        "ri_asuransi_ttl_profit" => isset($getProfitByKategori['RI']['ASURANSI']) ? array_sum($getProfitByKategori['RI']['ASURANSI']) : 0,
                        "ri_bpjs_ttl_profit" => isset($getProfitByKategori['RI']['BPJS']) ? array_sum($getProfitByKategori['RI']['BPJS']) : 0,
                        "ri_naker_ttl_profit" => isset($getProfitByKategori['RI']['NAKER']) ? array_sum($getProfitByKategori['RI']['NAKER']) : 0,

                        // rekap all kategori
                        "all_ttl_pasien" => isset($getDtByTipe['RJ']) ? count($getDtByTipe['RJ']) : 0,
                        "all_ttl_revenue" => isset($getRevenueByTipe['RJ']) ? array_sum($getRevenueByTipe['RJ']) : 0,
                        "all_ttl_cost" => isset($getCostByTipe['RJ']) ? array_sum($getCostByTipe['RJ']) : 0,
                        "all_ttl_profit" => isset($getProfitByTipe['RJ']) ? array_sum($getProfitByTipe['RJ']) : 0,

                        "all_ri_ttl_pasien" => isset($getDtByTipe['RI']) ? count($getDtByTipe['RI']) : 0,
                        "all_ri_ttl_revenue" => isset($getRevenueByTipe['RI']) ? array_sum($getRevenueByTipe['RI']) : 0,
                        "all_ri_ttl_cost" => isset($getCostByTipe['RI']) ? array_sum($getCostByTipe['RI']) : 0,
                        "all_ri_ttl_profit" => isset($getProfitByTipe['RI']) ? array_sum($getProfitByTipe['RI']) : 0,

                        "jml_resep_prb" => isset($getDtByTipe['PRB']) ? count($getDtByTipe['PRB']) : 0,
                        "ttl_billing_prb" => isset($getRevenueByTipe['PRB']) ? array_sum($getRevenueByTipe['PRB']) : 0,

                        "on_going_pasien" => isset($getDtByTipe['ONGOING']) ? count($getDtByTipe['ONGOING']) : 0,
                        "on_going_revenue" => isset($getRevenueByTipe['ONGOING']) ? array_sum($getRevenueByTipe['ONGOING']) : 0,

                        "unbill_pasien" => isset($getDtByTipe['UNBILL']) ? count($getDtByTipe['UNBILL']) : 0,
                        "unbill_revenue" => isset($getRevenueByTipe['UNBILL']) ? array_sum($getRevenueByTipe['UNBILL']) : 0,

                        "jml_resep_pb" => isset($getDtByTipe['PB']) ? count($getDtByTipe['PB']) : 0,
                        "ttl_billing_pb" => isset($getRevenueByTipe['PB']) ? array_sum($getRevenueByTipe['PB']) : 0,

                        // rekap bpjs
                        "totalPasienKlaimRJ" => isset($totalPasienKlaim['RJ']) ? count($totalPasienKlaim['RJ']) : 0,
                        "totalRpKlaimInacbgsRJ" => isset($totalRpKlaimInacbgs['RJ']) ? array_sum($totalRpKlaimInacbgs['RJ']) : 0,
                        "totalRpKlaimRsRJ" => isset($totalRpKlaimRs['RJ']) ? array_sum($totalRpKlaimRs['RJ']) : 0,
                        "totalRpBillRsKlaimRJ" => isset($totalRpBillRsKlaim['RJ']) ? array_sum($totalRpBillRsKlaim['RJ']) : 0,
                        "totalPasienKlaimRI" => isset($totalPasienKlaim['RI']) ? count($totalPasienKlaim['RI']) : 0,
                        "totalRpKlaimInacbgsRI" => isset($totalRpKlaimInacbgs['RI']) ? array_sum($totalRpKlaimInacbgs['RI']) : 0,
                        "totalRpKlaimRsRI" => isset($totalRpKlaimRs['RI']) ? array_sum($totalRpKlaimRs['RI']) : 0,
                        "totalRpBillRsKlaimRI" => isset($totalRpBillRsKlaim['RI']) ? array_sum($totalRpBillRsKlaim['RI']) : 0,

                        "totalPasienNoKlaimRJ" => isset($totalPasienNoKlaim['RJ'])?count($totalPasienNoKlaim['RJ']):0,
                        "totalRpNoKlaimInacbgsRJ" => isset($totalRpNoKlaimInacbgs['RJ'])?array_sum($totalRpNoKlaimInacbgs['RJ']):0,
                        "totalRpNoKlaimRsRJ" => isset($totalRpNoKlaimRs['RJ'])?array_sum($totalRpNoKlaimRs['RJ']):0,
                        "totalRpBillRsNKlaimRJ" => isset($totalRpBillRsNKlaim['RJ'])?array_sum($totalRpBillRsNKlaim['RJ']):0,
                        "totalPasienNoKlaimRI" => isset($totalPasienNoKlaim['RI'])?count($totalPasienNoKlaim['RI']):0,
                        "totalRpNoKlaimInacbgsRI" => isset($totalRpNoKlaimInacbgs['RI'])?array_sum($totalRpNoKlaimInacbgs['RI']):0,
                        "totalRpNoKlaimRsRI" => isset($totalRpNoKlaimRs['RI'])?array_sum($totalRpNoKlaimRs['RI']):0,
                        "totalRpBillRsNKlaimRI" => isset($totalRpBillRsNKlaim['RI'])?array_sum($totalRpBillRsNKlaim['RI']):0,
                        
                        // rekap by instalasi
                        "instalasi_rj_ttl_pasien" => isset($getDtByTipe['INSTALASI_RJ']) ? count($getDtByTipe['INSTALASI_RJ']) : 0,
                        "instalasi_rj_ttl_revenue" => isset($getRevenueByTipe['INSTALASI_RJ']) ? array_sum($getRevenueByTipe['INSTALASI_RJ']) : 0,
                        "hd_ttl_pasien" => isset($getDtByTipe['HD']) ? count($getDtByTipe['HD']) : 0,   
                        "hd_ttl_revenue" => isset($getRevenueByTipe['HD']) ? array_sum($getRevenueByTipe['HD']) : 0,
                        "mcu_ttl_pasien" => isset($getDtByTipe['MCU']) ? count($getDtByTipe['MCU']) : 0,
                        "mcu_ttl_revenue" => isset($getRevenueByTipe['MCU']) ? array_sum($getRevenueByTipe['MCU']) : 0,
                        "igd_ttl_pasien" => isset($getDtByTipe['IGD']) ? count($getDtByTipe['IGD']) : 0,
                        "igd_ttl_revenue" => isset($getRevenueByTipe['IGD']) ? array_sum($getRevenueByTipe['IGD']) : 0,
                        "lab_ttl_pasien" => isset($getDtByTipe['LAB']) ? count($getDtByTipe['LAB']) : 0,
                        "lab_ttl_revenue" => isset($getRevenueByTipe['LAB']) ? array_sum($getRevenueByTipe['LAB']) : 0,
                        "rad_ttl_pasien" => isset($getDtByTipe['RAD']) ? count($getDtByTipe['RAD']) : 0,
                        "rad_ttl_revenue" => isset($getRevenueByTipe['RAD']) ? array_sum($getRevenueByTipe['RAD']) : 0,
                        "fisio_ttl_pasien" => isset($getDtByTipe['FISIO']) ? count($getDtByTipe['FISIO']) : 0,
                        "fisio_ttl_revenue" => isset($getRevenueByTipe['FISIO']) ? array_sum($getRevenueByTipe['FISIO']) : 0,

                        
                        
                );
        //output to json format
        echo json_encode($output);
    }

    public function export_excel()
    {
        /*get data from model*/
        $list = $this->Eks_profit_by_close_bill->get_datatables();
        // list PRB
        $list_prb = $this->Eks_profit_by_close_bill->get_trx_prb();
        // list pembelian bebas
        $list_pb = $this->Eks_profit_by_close_bill->get_trx_pembelian_bebas();
        
        $data = [];
        $ttl_bill_dr1 = [];
        $ttl_bill_dr2 = [];
        $ttl_bhp = [];
        $ttl_bhp_apotik = [];
        $ttl_bill_kamar = [];
        $ttl_kamar_tindakan = [];
        $ttl_alat_rs = [];
        $ttl_profit = [];
        $ttl_total_bill = [];
        $getDtByKategori = [];
        $getRevenueByKategori = [];
        $getCostByKategori = [];
        $getProfitByKategori = [];
        $totalPasienKlaim = [];
        $totalRpKlaimInacbgs = [];
        $totalRpKlaimRs = [];
        $totalRpBillRsNKlaim = [];
        $totalPasienNoKlaim = [];
        $totalRpNoKlaimInacbgs = [];
        $totalRpNoKlaimRs = [];
        $totalRpBillRsNKlaim = [];

        // trx PB
        $no= 0;
        foreach ($list_pb as $kpb => $vpb) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = '';
            $row[] = 'PB';
            $row[] = $this->tanggal->formatDateDmy($vpb->tgl_jam);
            $row[] = $this->tanggal->formatDateDmy($vpb->tgl_jam);
            $row[] = strtoupper($vpb->no_mr);
            $row[] = strtoupper($vpb->nama_pasien);
            // $row[] = '';
            $row[] = '';
            $row[] = '';
            $row[] = '';
            $row[] = '';
            $row[] = '';
            $row[] = '';
            $row[] = '';
            $row[] = (int)$vpb->total;
            $row[] = '';
            $row[] = '';
            $row[] = '';
            $row[] = '';
            $row[] = '';
            $row[] = '';
            $row[] = (int)$vpb->total;
            $row[] = '';
            $row[] = '';
            $data[] = $row;

        }

        // trx PRB
        $no = 0;
        foreach ($list_prb as $key => $value) {
            $no++;
            $row = array();

            $row[] = $no;
            $row[] = '';
            $row[] = 'PRB';
            $row[] = $this->tanggal->formatDateDmy($value->tgl_trans);
            $row[] = $this->tanggal->formatDateDmy($value->tgl_trans);
            $row[] = strtoupper($value->no_mr);
            $row[] = strtoupper($value->nama_pasien);
            // $row[] = '';
            $row[] = '';
            $row[] = '';
            $row[] = '';
            $row[] = '';
            $row[] = '';
            $row[] = '';
            $row[] = '';
            $row[] = (int)$value->total;
            $row[] = '';
            $row[] = '';
            $row[] = '';
            $row[] = '';
            $row[] = '';
            $row[] = '';
            $row[] = (int)$value->total;
            $row[] = '';
            $row[] = '';

            $data[] = $row;

        }

        $no = 0;
        foreach ($list as $key_list=>$row_list) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $row_list->no_registrasi;
            $row[] = $row_list->tipe;
            $row[] = $this->tanggal->formatDateDmy($row_list->tgl_masuk);
            $row[] = $this->tanggal->formatDateDmy($row_list->tgl_keluar);
            $row[] = strtoupper($row_list->no_mr);
            $row[] = strtoupper($row_list->nama_pasien);
            $row[] = $row_list->dokter;
            $row[] = $row_list->spesialis_dokter;
            $row[] = $row_list->penjamin;
            $row[] = $row_list->nama_asuransi;
            $row[] = $row_list->no_sep;
            // total bill
            $total_bill = $row_list->total_billing;
            // bhp apotik
            $margin_apt = 33/100;
            $margin_apotik = $row_list->bill_apotik * $margin_apt;
            $bhp_apotik = $row_list->bill_apotik;
            // total pendapatan
            // $profit = $row_list->bill_rs - ($row_list->bhp + $row_list->bill_kamar + $row_list->kamar_tindakan + $row_list->alat_rs + $bhp_apotik);
            $profit = $row_list->pendapatan_rs;
            $cost = $row_list->bill_dr1 + $row_list->bill_dr2 + $row_list->bhp + $row_list->bill_kamar + $row_list->kamar_tindakan + $row_list->alat_rs + $bhp_apotik;
            
            $jasa_dokter = $row_list->bill_dr1 + $row_list->bill_dr2;
            $row[] = (int)$jasa_dokter;
            // $row[] = (int)$row_list->bill_dr2;
            $row[] = (int)$row_list->bhp;
            $row[] = (int)$bhp_apotik;
            $row[] = (int)$row_list->bill_lab;
            $row[] = (int)$row_list->bill_rad;
            $row[] = (int)$row_list->bill_kamar;
            $row[] = (int)$row_list->kamar_tindakan;
            $row[] = (int)$row_list->alat_rs;
            $row[] = (int)$profit;
            $row[] = (int)$row_list->total_billing;
            $row[] = (int)$row_list->tarif_inacbgs;
            $row[] = (int)$row_list->tarif_rs_klaim_ncc;

            $ttl_bill_dr1[] = $row_list->bill_dr1;
            $ttl_bill_dr2[] = $row_list->bill_dr2;
            $ttl_bhp[] = $row_list->bhp;
            $ttl_bhp_apotik[] = $bhp_apotik;
            $ttl_bill_kamar[] = $row_list->bill_kamar;
            $ttl_kamar_tindakan[] = $row_list->kamar_tindakan;
            $ttl_alat_rs[] = $row_list->alat_rs;
            $ttl_profit[] = $profit;
            $ttl_total_bill[] = $total_bill;

            $data[] = $row;
            // jumlah pasien berdasarkan kategori penjamin
            $getDtByKategori[$row_list->tipe][$row_list->penjamin][] = $row_list;
            $getRevenueByKategori[$row_list->tipe][$row_list->penjamin][] = $total_bill;
            $getCostByKategori[$row_list->tipe][$row_list->penjamin][] = $cost;
            $getProfitByKategori[$row_list->tipe][$row_list->penjamin][] = $profit;

            $getDtByTipe[$row_list->tipe][] = $row_list;
            $getRevenueByTipe[$row_list->tipe][] = $total_bill;
            $getCostByTipe[$row_list->tipe][] = $cost;
            $getProfitByTipe[$row_list->tipe][] = $profit;

            // rekap bpjs
            if($row_list->tarif_rs_klaim_ncc > 0){
                $totalPasienKlaim[$row_list->tipe][] = $row_list;
                $totalRpKlaimInacbgs[$row_list->tipe][] = $row_list->tarif_inacbgs;
                $totalRpKlaimRs[$row_list->tipe][] = $row_list->tarif_rs_klaim_ncc;
                $totalRpBillRsKlaim[$row_list->tipe][] = $total_bill;
            }else{
                if($row_list->penjamin == 'BPJS'){
                    $totalPasienNoKlaim[$row_list->tipe][] = $row_list;
                    $totalRpNoKlaimInacbgs[$row_list->tipe][] = $row_list->tarif_inacbgs;
                    $totalRpNoKlaimRs[$row_list->tipe][] = $row_list->tarif_rs_klaim_ncc;
                    $totalRpBillRsNKlaim[$row_list->tipe][] = $total_bill;
                }
            }
        }

        $output = array(
            "result" => $data,        
        );
        // echo "<pre>";print_r($output);die;
        $this->load->view('eksekutif/Eks_profit_by_close_bill/excel_view', $output);
        
    }

    public function export_excel_2(){
        $list = $this->Eks_profit_by_close_bill->get_datatables();

        

        $no = 0;
        foreach ($list as $key_list=>$row_list) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $row_list->tipe;
            $row[] = $this->tanggal->formatDateDmy($row_list->tgl_masuk);
            $row[] = $this->tanggal->formatDateDmy($row_list->tgl_keluar);
            $row[] = strtoupper($row_list->no_mr);
            $row[] = strtoupper($row_list->nama_pasien);
            $row[] = $row_list->dokter;
            $row[] = $row_list->spesialis_dokter;
            $row[] = $row_list->penjamin;
            $row[] = $row_list->nama_asuransi;
            $row[] = $row_list->no_sep;
            // total bill
            $total_bill = $row_list->bill_rs + $row_list->bill_dr1 + $row_list->bill_dr2;
            // bhp apotik
            $margin_apt = 33/100;
            $margin_apotik = $row_list->bill_apotik * $margin_apt;
            $bhp_apotik = $row_list->bill_apotik - $margin_apotik;
            // total pendapatan
            $profit = $row_list->bill_rs - ($row_list->bhp + $row_list->bill_kamar + $row_list->kamar_tindakan + $row_list->alat_rs + $bhp_apotik);
            $cost = $row_list->bill_dr1 + $row_list->bill_dr2 + $row_list->bhp + $row_list->bill_kamar + $row_list->kamar_tindakan + $row_list->alat_rs + $bhp_apotik;
            $row[] = (int)$row_list->bill_dr1;
            $row[] = (int)$row_list->bill_dr2;
            $row[] = (int)$row_list->bhp;
            $row[] = (int)$bhp_apotik;
            $row[] = (int)$row_list->bill_kamar;
            $row[] = (int)$row_list->kamar_tindakan;
            $row[] = (int)$row_list->alat_rs;
            $row[] = (int)$profit;
            $row[] = (int)$total_bill;
            $row[] = (int)$row_list->tarif_inacbgs;
            $row[] = (int)$row_list->tarif_rs_klaim_ncc;

            $ttl_bill_dr1[] = $row_list->bill_dr1;
            $ttl_bill_dr2[] = $row_list->bill_dr2;
            $ttl_bhp[] = $row_list->bhp;
            $ttl_bhp_apotik[] = $bhp_apotik;
            $ttl_bill_kamar[] = $row_list->bill_kamar;
            $ttl_kamar_tindakan[] = $row_list->kamar_tindakan;
            $ttl_alat_rs[] = $row_list->alat_rs;
            $ttl_profit[] = $profit;
            $ttl_total_bill[] = $total_bill;

            $data[] = $row;

            // jumlah pasien berdasarkan kategori penjamin
            $getDtByKategori[$row_list->tipe][$row_list->penjamin][] = $row_list;
            $getRevenueByKategori[$row_list->tipe][$row_list->penjamin][] = $total_bill;
            $getCostByKategori[$row_list->tipe][$row_list->penjamin][] = $cost;
            $getProfitByKategori[$row_list->tipe][$row_list->penjamin][] = $profit;

            $getDtByTipe[$row_list->tipe][] = $row_list;
            $getRevenueByTipe[$row_list->tipe][] = $total_bill;
            $getCostByTipe[$row_list->tipe][] = $cost;
            $getProfitByTipe[$row_list->tipe][] = $profit;
        }

        $return_data = array(
            "recordsTotal" => count($list),
            "recordsFiltered" => count($list),
            "result" => $data,

            "ttl_bill_dr1" => array_sum($ttl_bill_dr1),
            "ttl_bill_dr2" => array_sum($ttl_bill_dr2),
            "ttl_bhp" => array_sum($ttl_bhp),
            "ttl_bhp_apotik" => array_sum($ttl_bhp_apotik),
            "ttl_bill_kamar" => array_sum($ttl_bill_kamar),
            "ttl_kamar_tindakan" => array_sum($ttl_kamar_tindakan),
            "ttl_alat_rs" => array_sum($ttl_alat_rs),
            "ttl_profit" => array_sum($ttl_profit),
            "ttl_total_bill" => array_sum($ttl_total_bill),
            'start_date' => isset($_GET['start_date'])?$_GET['start_date']:date('Y-m-d'),
            'end_date' => isset($_GET['end_date'])?$_GET['end_date']:date('Y-m-d'),
            // rekap kategori rajal
            "um_ttl_pasien" => isset($getDtByKategori['RJ']['UMUM']) ? count($getDtByKategori['RJ']['UMUM']) : 0,
            "asuransi_ttl_pasien" => isset($getDtByKategori['RJ']['ASURANSI']) ? count($getDtByKategori['RJ']['ASURANSI']) : 0,
            "bpjs_ttl_pasien" => isset($getDtByKategori['RJ']['BPJS']) ? count($getDtByKategori['RJ']['BPJS']) : 0,
            "naker_ttl_pasien" => isset($getDtByKategori['RJ']['NAKER']) ? count($getDtByKategori['RJ']['NAKER']) : 0,


            "um_ttl_revenue" => isset($getRevenueByKategori['RJ']['UMUM']) ? array_sum($getRevenueByKategori['RJ']['UMUM']) : 0,
            "asuransi_ttl_revenue" => isset($getRevenueByKategori['RJ']['ASURANSI']) ? array_sum($getRevenueByKategori['RJ']['ASURANSI']) : 0,
            "bpjs_ttl_revenue" => isset($getRevenueByKategori['RJ']['BPJS']) ? array_sum($getRevenueByKategori['RJ']['BPJS']) : 0,
            "naker_ttl_revenue" => isset($getRevenueByKategori['RJ']['NAKER']) ? array_sum($getRevenueByKategori['RJ']['NAKER']) : 0,

            "um_ttl_cost" => isset($getCostByKategori['RJ']['UMUM']) ? array_sum($getCostByKategori['RJ']['UMUM']) : 0,
            "asuransi_ttl_cost" => isset($getCostByKategori['RJ']['ASURANSI']) ? array_sum($getCostByKategori['RJ']['ASURANSI']) : 0,
            "bpjs_ttl_cost" => isset($getCostByKategori['RJ']['BPJS']) ? array_sum($getCostByKategori['RJ']['BPJS']) : 0,
            "naker_ttl_cost" => isset($getCostByKategori['RJ']['NAKER']) ? array_sum($getCostByKategori['RJ']['NAKER']) : 0,

            "um_ttl_profit" => isset($getProfitByKategori['RJ']['UMUM']) ? array_sum($getProfitByKategori['RJ']['UMUM']) : 0,
            "asuransi_ttl_profit" => isset($getProfitByKategori['RJ']['ASURANSI']) ? array_sum($getProfitByKategori['RJ']['ASURANSI']) : 0,
            "bpjs_ttl_profit" => isset($getProfitByKategori['RJ']['BPJS']) ? array_sum($getProfitByKategori['RJ']['BPJS']) : 0,
            "naker_ttl_profit" => isset($getProfitByKategori['RJ']['NAKER']) ? array_sum($getProfitByKategori['RJ']['NAKER']) : 0,
            // rekap kategori ranap
            "ri_um_ttl_pasien" => isset($getDtByKategori['RI']['UMUM']) ? count($getDtByKategori['RI']['UMUM']) : 0,
            "ri_asuransi_ttl_pasien" => isset($getDtByKategori['RI']['ASURANSI']) ? count($getDtByKategori['RI']['ASURANSI']) : 0,
            "ri_bpjs_ttl_pasien" => isset($getDtByKategori['RI']['BPJS']) ? count($getDtByKategori['RI']['BPJS']) : 0,
            "ri_naker_ttl_pasien" => isset($getDtByKategori['RI']['NAKER']) ? count($getDtByKategori['RI']['NAKER']) : 0,

            "ri_um_ttl_revenue" => isset($getRevenueByKategori['RI']['UMUM']) ? array_sum($getRevenueByKategori['RI']['UMUM']) : 0,
            "ri_asuransi_ttl_revenue" => isset($getRevenueByKategori['RI']['ASURANSI']) ? array_sum($getRevenueByKategori['RI']['ASURANSI']) : 0,
            "ri_bpjs_ttl_revenue" => isset($getRevenueByKategori['RI']['BPJS']) ? array_sum($getRevenueByKategori['RI']['BPJS']) : 0,
            "ri_naker_ttl_revenue" => isset($getRevenueByKategori['RI']['NAKER']) ? array_sum($getRevenueByKategori['RI']['NAKER']) : 0,
            
            "ri_um_ttl_cost" => isset($getCostByKategori['RI']['UMUM']) ? array_sum($getCostByKategori['RI']['UMUM']) : 0,
            "ri_asuransi_ttl_cost" => isset($getCostByKategori['RI']['ASURANSI']) ? array_sum($getCostByKategori['RI']['ASURANSI']) : 0,
            "ri_bpjs_ttl_cost" => isset($getCostByKategori['RI']['BPJS']) ? array_sum($getCostByKategori['RI']['BPJS']) : 0,
            "ri_naker_ttl_cost" => isset($getCostByKategori['RI']['NAKER']) ? array_sum($getCostByKategori['RI']['NAKER']) : 0,

            "ri_um_ttl_profit" => isset($getProfitByKategori['RI']['UMUM']) ? array_sum($getProfitByKategori['RI']['UMUM']) : 0,
            "ri_asuransi_ttl_profit" => isset($getProfitByKategori['RI']['ASURANSI']) ? array_sum($getProfitByKategori['RI']['ASURANSI']) : 0,
            "ri_bpjs_ttl_profit" => isset($getProfitByKategori['RI']['BPJS']) ? array_sum($getProfitByKategori['RI']['BPJS']) : 0,
            "ri_naker_ttl_profit" => isset($getProfitByKategori['RI']['NAKER']) ? array_sum($getProfitByKategori['RI']['NAKER']) : 0,

            // rekap all kategori
            "all_ttl_pasien" => isset($getDtByTipe['RJ']) ? count($getDtByTipe['RJ']) : 0,
            "all_ri_ttl_pasien" => isset($getDtByTipe['RI']) ? count($getDtByTipe['RI']) : 0,
            "all_ttl_revenue" => isset($getRevenueByTipe['RJ']) ? array_sum($getRevenueByTipe['RJ']) : 0,
            "all_ri_ttl_revenue" => isset($getRevenueByTipe['RI']) ? array_sum($getRevenueByTipe['RI']) : 0,
            "all_ttl_cost" => isset($getCostByTipe['RJ']) ? array_sum($getCostByTipe['RJ']) : 0,
            "all_ttl_profit" => isset($getProfitByTipe['RJ']) ? array_sum($getProfitByTipe['RJ']) : 0,
            "all_ri_ttl_cost" => isset($getCostByTipe['RI']) ? array_sum($getCostByTipe['RI']) : 0,
            "all_ri_ttl_profit" => isset($getProfitByTipe['RI']) ? array_sum($getProfitByTipe['RI']) : 0,
            
        );

        //output to json format
        // echo "<pre>";print_r($data);die;
        $this->load->view('eksekutif/Eks_profit_by_close_bill/excel_view', $return_data);
    }

}


/* End of file example.php */
/* Location: ./application/modules/example/controllers/example.php */
