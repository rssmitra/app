<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Sie extends MX_Controller {

    function __construct() {
        parent::__construct();
        /*breadcrumb default*/

        // if($this->session->userdata('logged')!=TRUE){
        //     redirect(base_url().'login');
        // }

    }

    public function index() {
        
        $this->output->enable_profiler(false);
        /*breadcrumb*/
        $this->breadcrumbs->push('Welcome', 'main/'.strtolower(get_class($this)));
        $data = array(
            'title' => 'Home',
            'subtitle' => 'Welcome Amin',
            'breadcrumbs' => $this->breadcrumbs->show(),
            'app' => $this->db->get_where('tmp_profile_app', array('id' => 1))->row(),
        );
        $this->load->view('Sie/sie_view', $data);
    }

}

/* End of file empty_module.php */
/* Location: ./application/modules/empty_module/controllers/empty_module.php */

