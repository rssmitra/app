<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pembentukan_saldo_dr extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'adm_pasien/pembayaran_dr/Pembentukan_saldo_dr');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('adm_pasien/pembayaran_dr/Pembentukan_saldo_dr_model', 'Pembentukan_saldo_dr');
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
        );
        /*load view index*/
        $this->load->view('pembayaran_dr/Pembentukan_saldo_dr/index', $data);
    }

    public function find_data()
    {   
        $output = array( "data" => http_build_query($_POST) . "\n" );
        echo json_encode($output);
    }
    
    public function get_data()
    {
        /*get data from model*/
        $list = $this->Pembentukan_saldo_dr->get_datatables();
        // print_r($list);die;
        $no = $_POST['start'];
        $data = array();
        foreach ($list as $row_list) {
            $row = array();
            $no++;
            $row[] = '<div class="center"></div>';
            $row[] = $row_list->no_registrasi;
            $row[] = '<div class="center">'.$no.'</div>';
            $row[] = '<a href="#" onclick="getMenu('."'billing/Billing/viewDetailBillingKasir/".$row_list->no_registrasi."'".')">'.$row_list->kode_trans_pelayanan.'</div>';
            $row[] = $this->tanggal->formatDate($row_list->tgl_transaksi);
            $row[] = $row_list->no_mr;
            $row[] = $row_list->nama_pasien_layan;
            $row[] = $row_list->nama_tindakan;
            $row[] = '<div style="text-align: right">'.number_format($row_list->total_billing).'</div>';
            $data[] = $row;
                 
        }
        
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Pembentukan_saldo_dr->count_all(),
                        "recordsFiltered" => $this->Pembentukan_saldo_dr->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function get_total_billing()
    {
        /*get data from model*/
        $list = $this->Pembentukan_saldo_dr->get_total_billing(); 
        echo json_encode($list);
    }

    public function get_total_billing_dr_current_day()
    {
        /*get data from model*/
        $list = $this->Pembentukan_saldo_dr->get_total_billing_dr_current_day(); 
        echo json_encode($list);
    }

    public function getDetailTransaksi($no_registrasi){
        $result = json_decode($this->Billing->getDetailData($no_registrasi));
        $akunting = $this->Pembentukan_saldo_dr->get_jurnal_akunting($no_registrasi);
        $data = array(
            'result' => $result,
            'transaksi' => $akunting['data'],
            'jurnal' => $akunting['data'],
        );
        // echo '<pre>';print_r($akunting);die;
        $html = $this->load->view('pembayaran_dr/Pembentukan_saldo_dr/detail_transaksi_view', $data, true);
        echo json_encode(array('html' => $html));
    }

}


/* End of file example.php */
/* Location: ./application/modules/example/controllers/example.php */
