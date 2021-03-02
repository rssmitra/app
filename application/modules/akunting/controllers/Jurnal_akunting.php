<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Jurnal_akunting extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'akunting/Jurnal_akunting');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('akunting/Jurnal_akunting_model', 'Jurnal_akunting');
        $this->load->model('billing/Billing_model', 'Billing');
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
        );
        /*show datatables*/
        // $data['dataTables'] = $this->load->view('akunting/Jurnal_akunting/temp_trans_pasien', $data, true);
        /*load view index*/
        $this->load->view('akunting/Jurnal_akunting/index', $data);
    }

    public function getDetailTransaksi(){
        $akunting = $this->Jurnal_akunting->get_jurnal_akunting();
        $data = array(
            'transaksi' => $akunting['data'],
            'jurnal' => $akunting['data'],
        );
        // echo '<pre>';print_r($akunting);die;
        $this->load->view('akunting/Jurnal_akunting/detail_transaksi_view', $data);
        
    }

    public function export_excel(){
        $akunting = $this->Jurnal_akunting->get_jurnal_akunting();
        $data = array(
            'transaksi' => $akunting['data'],
            'jurnal' => $akunting['data'],
        );
        // echo '<pre>';print_r($akunting);die;
        $this->load->view('akunting/Jurnal_akunting/excel_view', $data);
        
    }

    public function find_data()
    {   
        $output = array( "data" => http_build_query($_POST) . "\n" );
        echo json_encode($output);
    }

}


/* End of file example.php */
/* Location: ./application/modules/example/controllers/example.php */
