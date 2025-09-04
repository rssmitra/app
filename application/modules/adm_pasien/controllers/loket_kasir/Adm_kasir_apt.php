<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Adm_kasir_apt extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'adm_pasien/loket_kasir/Adm_kasir');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('adm_pasien/loket_kasir/Adm_kasir_apt_model', 'Adm_kasir_apt');
        $this->load->model('adm_pasien/Adm_pasien_model', 'Adm_pasien');
        /*enable profiler*/
        $this->output->enable_profiler(false);
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'Title';

    }

    public function index() { 
        //echo '<pre>';print_r($this->session->all_userdata());
        /*define variable data*/
        $title = 'Apotik/ Pembelian Bebas';
        $data = array(
            'title' => 'Kasir '.$title,
            'breadcrumbs' => $this->breadcrumbs->show(),
            'flag' => $_GET['flag'],
            'pelayanan' => $_GET['pelayanan'],
        );
        /*show datatables*/
        $data['dataTables'] = $this->load->view('loket_kasir/Adm_kasir_apt/temp_trans_pasien', $data, true);
        /*load view index*/
        $this->load->view('loket_kasir/Adm_kasir_apt/index', $data);
    }

    public function find_data()
    {   
        $output = array( "data" => http_build_query($_POST) . "\n" );
        echo json_encode($output);
    }
    
    public function get_data()
    {
        /*get data from model*/
        $list = $this->Adm_kasir_apt->get_datatables();
        // echo "<pre>";print_r($list);die;
        $data = array();
        $arr_total = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            
            $no++;
            $row = array();
            $row[] = '<div class="center">'.$no.'</div>';
            $row[] = '<div class="center"><a href="#" onclick="getMenu('."'billing/Billing/viewDetailBillingKasirApt/".$row_list->kode_trans_far."/".$_GET['pelayanan'].""."'".')" style="color: blue; font-weight: bold">'.$row_list->kode_trans_far.'</div>';
            $row[] = strtoupper($row_list->nama_pasien);
            $row[] = $this->tanggal->formatDateTime($row_list->tgl_trans);
            $total = $row_list->bill_rs + $row_list->bill_dr1 + $row_list->bill_dr2 + $row_list->bill_dr3 + $row_list->lain_lain;
            // if( $total > 0 ){
            //     $row[] = '<div class="pull-right"><a href="#" onclick="show_modal_medium_return_json('."'billing/Billing/getDetailLessApt/".$row_list->kode_trans_far."/".$_GET['pelayanan']."'".', '."'RINCIAN BILLING PASIEN'".')"  style="color: blue; font-weight: bold">'.number_format($total).',-</a><input type="hidden" class="total_billing_class" value="'.$total.'"></div>';
            // }else{
            //     $row[] = '<div class="center"><i class="fa fa-check-circle bigger-150 green"></i></div>';
            // }
            $row[] = $this->tanggal->formatDateTime($row_list->tgl_bayar);
            if( $row_list->bill_rs == $row_list->bill_kasir ){
                // $row[] = '<div class="center"><a href="#" class="btn btn-xs btn-primary" onclick="getMenu('."'billing/Billing/viewDetailBillingKasirApt/".$row_list->kode_trans_far."/RJ?flag=umum'".')"><i class="fa fa-money"></i> Bayar</a></div>';
                $row[] = '<div class="left"><i class="fa fa-check bigger-120 green"></i> <span style="color: green; font-weight: bold">'.number_format($total).',-</span> </div>';
            }else{
                $row[] = '<div class="left"><a href="#" style="color: red; font-weight: bold"><i class="fa fa-warning bigger-120 orange"></i> '.number_format($total).',-</a></div>';
            }

            $data[] = $row;
              
        }
        
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Adm_kasir_apt->count_all(),
                        "recordsFiltered" => $this->Adm_kasir_apt->count_filtered(),
                        "data" => $data,
                        "total_billing" => array_sum($arr_total),
                );
        //output to json format
        echo json_encode($output);
    }

}


/* End of file example.php */
/* Location: ./application/modules/example/controllers/example.php */
