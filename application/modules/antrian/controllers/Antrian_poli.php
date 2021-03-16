<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Antrian_poli extends MX_Controller {

    function __construct() {
        parent::__construct();
 
        $this->load->model('antrian_model'); 
        $this->load->model('loket_model','loket'); 

        $this->load->library('Print_direct');

    }

    public function index() {
        
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

        $this->load->view('Antrian/index', $data);
    }


}

/* End of file empty_module.php */
/* Location: ./application/modules/empty_module/controllers/empty_module.php */

