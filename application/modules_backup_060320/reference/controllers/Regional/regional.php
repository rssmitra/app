<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Regional extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'master_data/regional');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load library*/
        $this->load->library('lib_menus');
        /*enable profiler*/
        $this->output->enable_profiler(false);

    }

    public function index() {
        /*define variable data*/
        $data = array(
            'menu' => $this->lib_menus->get_module_by_class(strtolower(get_class($this))), 
            'title' => 'Regional',
            'breadcrumbs' => $this->breadcrumbs->show()
        );
        /*load view index*/
        $this->load->view('regional/index', $data);
    }

}


/* End of file Jabatan.php */
/* Location: ./application/modules/regional/controllers/regional.php */
