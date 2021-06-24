<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Self_service extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('Print_direct');

    }

    public function index() {
        
        $data = array();

        $this->load->view('Self_service/index', $data);
    }

    public function pmp_bpjs() {
        
        $data = array();

        $this->load->view('Self_service/form_pmp_bpjs', $data);
    }

}

/* End of file empty_module.php */
/* Location: ./application/modules/empty_module/controllers/empty_module.php */

