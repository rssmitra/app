<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Jurnal_umum extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'akunting/Jurnal_umum');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('akunting/Jurnal_umum_model', 'Jurnal_umum');
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
        /*load view index*/
        $this->load->view('akunting/Jurnal_umum/index', $data);
    }

    public function form_penyesuaian($id_ak_tc_transaksi) { 
        $akunting = $this->Jurnal_umum->getDetailData($id_ak_tc_transaksi);
        /*define variable data*/
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show(),
            'jurnal' => $akunting['data'],
            'value' => $this->Jurnal_umum->get_data_by_id($id_ak_tc_transaksi),
        );
        /*load view index*/
        $this->load->view('akunting/Jurnal_umum/form_penyesuaian', $data);
    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->Jurnal_umum->get_datatables();
        
        $data = array();
        $arr_total = array();
        $tgl_keluar_null = [];
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            
            $no++;
            $row = array();
            $row[] = '<div class="center"></div>';
            $row[] = $row_list->id_ak_tc_transaksi;
            $row[] = '<div class="center">'.$no.'</div>';
            $row[] = $row_list->no_bukti;
            $row[] = $this->tanggal->formatDateTime($row_list->tgl_transaksi);
            $row[] = $row_list->no_mr;
            $row[] = $row_list->nama_pasien;
            $row[] = '<div align="right">'.number_format($row_list->total).'</div>';
            $row[] = '<div align="center" style="background: orange; color: white; padding: 2px">Unverified</div>';
            $data[] = $row;
        }
        
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Jurnal_umum->count_all(),
                        "recordsFiltered" => $this->Jurnal_umum->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }


    public function getDetailTransaksi($id_ak_tc_transaksi){
        $akunting = $this->Jurnal_umum->getDetailData($id_ak_tc_transaksi);
        $data = array(
            'id_ak_tc_transaksi' => $id_ak_tc_transaksi,
            'jurnal' => $akunting['data'],
        );
        // echo '<pre>';print_r($akunting);die;
        $html = $this->load->view('akunting/Jurnal_umum/detail_transaksi_view', $data, true);
        echo json_encode(array('html' => $html));
        
    }

    public function export_excel(){
        $akunting = $this->Jurnal_umum->get_jurnal_akunting();
        $data = array(
            'jurnal' => $akunting['data'],
        );
        // echo '<pre>';print_r($akunting);die;
        $this->load->view('akunting/Jurnal_umum/excel_view', $data);
        
    }

    public function find_data()
    {   
        $output = array( "data" => http_build_query($_POST) . "\n" );
        echo json_encode($output);
    }

}


/* End of file example.php */
/* Location: ./application/modules/example/controllers/example.php */
