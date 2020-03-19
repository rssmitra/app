<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Antrian_pasien extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'laporan/Antrian_pasien');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('Antrian_pasien_model', 'Antrian_pasien');
        /*enable profiler*/
        $this->output->enable_profiler(false);
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'Title';

    }

    public function index() { 
        /*define variable data*/
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show()
        );
        /*load view index*/
        $this->load->view('Antrian_pasien/index', $data);
    }

    public function find_data()
    {   
        $list = $this->Antrian_pasien->get_search_data($_POST);

        echo json_encode( $list );
    }

    public function export_detail()
    {
        //print_r($_POST);die;

        if($_POST['export_by']=='detail'){
            $list = $this->Antrian_pasien->get_search_data($_POST);
        } else {
            $list = $this->Antrian_pasien->get_export_data($_POST);
        }

        $data = array();

        $data['result']=$list;

        $from_ = explode('-',$this->tanggal->formatDateTime($_POST['from_tgl']));
        $from = $from_[0];

        $to_ = explode('-',$this->tanggal->formatDateTime($_POST['to_tgl']));
        $to = $to_[0];

        $now_ = explode('-',$this->tanggal->formatDateTime(date('Y-m-d')));
        $now = $now_[0];

        $data['from_tgl']=($_POST['from_tgl'])?$from:$now;

        $data['to_tgl']=($_POST['to_tgl'])?$to:$now;

        if($_POST['export_by']=='detail'){
            $this->load->view('Antrian_pasien/export',$data);
        }else{
            $this->load->view('Antrian_pasien/export_general',$data);
        }
        

    }

}

/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
