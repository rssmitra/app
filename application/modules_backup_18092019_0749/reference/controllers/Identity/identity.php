<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Identity extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'master_data/identity');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
       // $this->load->model('identity_model', 'identity');
        /*load library*/
        $this->load->library('lib_menus');
        /*enable profiler*/
        $this->output->enable_profiler(false);
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'Title';

    }

    public function index() {
        /*define variable data*/
        $data = array(
            'menu' => $this->lib_menus->get_module_by_class(strtolower(get_class($this))), 
            'title' => 'Data Kependudukan',
            'breadcrumbs' => $this->breadcrumbs->show()
        );
        /*load view index*/
        $this->load->view('identity/index', $data);
    }

}


/* End of file Jabatan.php */
/* Location: ./application/modules/identity/controllers/identity.php */
