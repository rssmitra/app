<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Lapi_report extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'laporan/Lapi_report');
        
        /*load model*/
        $this->load->model('Lapi_report_model', 'Lapi_report');
        $this->load->model('Master_model', 'Master_model');
        /*enable profiler*/
        $this->output->enable_profiler(false);
        /*profile class*/
        $this->title = 'L A P I';
        $this->username = 'Lapi-2024';
        $this->password = 'lapi140324!';

    }

    public function index() { 
        /*define variable data*/
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show()
        );

        /*load view index*/
        $this->load->view('Lapi_report/index', $data);
    }

    public function form() { 
        // cek token auth
        $this->cekSessionToken();
        $bulan = 
        /*define variable data*/
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show()
        );

        /*load view index*/
        $this->load->view('Lapi_report/form', $data);
    }

    public function auth(){
        // auth
        // print_r($_POST);die;
        if($_POST['username'] == $this->username && $_POST['password'] == $this->password){
            $token = md5($_POST['username'].$_POST['password']);
            $this->session->set_userdata(['token' => $token]);
            redirect(base_url().'lapi/form?token='.$token.'');
        }else{
            redirect(base_url().'lapi?login=false');
        }
    }

    public function show_data(){
        // query should be here
        // echo "<pre>";print_r($_POST);exit;
        // cek token
        $this->cekSessionToken();

        $params = [
            'bulan' => $_POST['month'],
            'tahun' => $_POST['year'],
        ];
        $query_data = $this->Lapi_report->get_data($params);
        $data = array(
            'title' => 'LAPI',
            'result' => $query_data,
        );
        $this->load->view('Lapi_report/view_data', $data);
    }

    public function logout(){
        $this->session->unset_userdata(['token' => NULL]);
        $this->session->sess_destroy();
        redirect(base_url().'lapi');
    }

    public function cekSessionToken(){
        if( empty($this->session->userdata('token')) ){
            redirect(base_url().'lapi?login=false');
        }
    }
}

/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
