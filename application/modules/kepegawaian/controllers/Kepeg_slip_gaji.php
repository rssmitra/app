<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Kepeg_slip_gaji extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'kepegawaian/Kepeg_slip_gaji');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('Kepeg_slip_gaji_model', 'Kepeg_slip_gaji');
        /*enable profiler*/
        $this->output->enable_profiler(false);
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'Title';

    }

    public function index() { 
        // echo '<pre>';print_r($this->session->all_userdata());
        /*define variable data*/
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show()
        );
        /*load view index*/
        $this->load->view('Kepeg_slip_gaji/index', $data);
    }

    public function find_data()
    {   
        $output = array( "data" => http_build_query($_POST) . "\n" );
        echo json_encode($output);
    }

    
    /*function for view data only*/
    public function slip_gaji()
    {
        /*define data variabel*/
        $data['value'] = $this->Kepeg_slip_gaji->get_data();
        $data['title'] = $this->title;
        /*load form view*/
        $this->load->view('Kepeg_slip_gaji/view_slip_gaji', $data);
    }

    public function slip_gaji_view()
    {
        /*define data variabel*/
        $data['value'] = $this->Kepeg_slip_gaji->get_data();
        $data['title'] = $this->title;
        /*load form view*/
        $this->load->view('Kepeg_slip_gaji/cetak_slip_gaji', $data);
    }


}


/* End of file example.php */
/* Location: ./application/modules/example/controllers/example.php */
