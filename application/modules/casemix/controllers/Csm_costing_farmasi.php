<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Csm_costing_farmasi extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'casemix/Csm_costing_farmasi');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            redirect(base_url().'login');exit;
        }
        /*load model*/
        /*enable profiler*/
        $this->output->enable_profiler(false);

    }

    public function index() { 
        //echo '<pre>';print_r($this->session->all_userdata());
        /*define variable data*/
        $data = array(
            'title' => 'Costing Farmasi',
            'breadcrumbs' => $this->breadcrumbs->show()
        );
        /*load view index*/
        $this->load->view('Csm_costing_farmasi/index', $data);
    }

    public function run_scheduler(){

        // Menentukan skrip CLI yang ingin dijalankan
        $cliScript = 'scheduler/Auto_merge_farmasi/index '.$_POST['date'].'';

        // Menjalankan skrip CLI menggunakan perintah shell_exec
        $output = shell_exec("D:");
        $output = shell_exec("cd xampp/htdocs/sirs/app/");
        $output = shell_exec("php index.php $cliScript");

        // Menampilkan output dari skrip CLI
        echo json_encode(str_replace("\n", "<br>", $output));
    }

}


/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
