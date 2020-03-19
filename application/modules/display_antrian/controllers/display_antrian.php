<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Display_antrian extends MX_Controller {

    function __construct() {
        parent::__construct();
 
        $this->load->model('Display_antrian_model','display_antrian'); 

    }

    public function index() {
   
        $this->load->view('display_antrian/index');
    }

   public function process()
   {
       # code...
       //$loket = $this->display_antrian->get_loket();
       //$nomor_loket = array();

       for ($i=1; $i <= 4; $i++) { 
           # code...
           $nomor_loket[$i] = $this->display_antrian->get_antrian_by_loket($i);
       }

       //print_r($nomor_loket);die;

       echo json_encode($nomor_loket);
       //echo $nomor_loket;
   }

}

/* End of file empty_module.php */
/* Location: ./application/modules/empty_module/controllers/empty_module.php */

