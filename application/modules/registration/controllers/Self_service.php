<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Self_service extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('Print_direct');
        $this->load->model('antrian/antrian_model'); 
        $this->load->model('antrian/loket_model','loket');
        $this->load->model('display_loket/main_model','Main');  

    }

    public function index() {
        
        $data = array();

        $this->load->view('Self_service/index', $data);
    }

    public function mandiri_bpjs() {
        
        $data = array();

        $this->load->view('Self_service/index_bpjs', $data);
    }

    public function mandiri_umum() {
        
        $data = array();

        $this->load->view('Self_service/index_umum', $data);
    }

    public function antrian_poli() {
        
        $data_loket = $this->loket->get_open_loket();

        foreach ($data_loket as $key => $value) {
            # code...
            $kuota = $this->loket->get_sisa_kuota($value);
            if($kuota<0)$kuota=0;
            $data_loket[$key]->kuota = $kuota;
        }

        //print_r($_GET['type']);die;
        $data['type'] = isset($_GET['type'])?$_GET['type']:'bpjs';
        
        $data['klinik'] = $data_loket;

        $this->load->view('Self_service/index_antrian_poli', $data);
    }

    public function pmp_bpjs() {
        
        $data = array();

        $this->load->view('Self_service/form_pmp_bpjs', $data);
    }

}

/* End of file empty_module.php */
/* Location: ./application/modules/empty_module/controllers/empty_module.php */

