<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Display_antrian extends MX_Controller {

    function __construct() {
        parent::__construct();
 
        $this->load->model('Display_antrian_model','display_antrian'); 

    }

    public function index() {
        
        $data = array(
            'app' => $this->db->get_where('tmp_profile_app', array('id' => 1))->row(),
        );

        $this->load->view('display_antrian/index', $data);
    }

    public function farmasi() {
   
        $this->load->view('display_antrian/index_farmasi');
    }

    public function poli() {
   
        $this->load->view('display_antrian/index_poli');
    }

   public function process()
   {
       # code...
       //$loket = $this->display_antrian->get_loket();
       //$nomor_loket = array();

       for ($i=1; $i <= 4; $i++) { 
           # code...
           $data = $this->display_antrian->get_antrian_by_loket($i);
        //    print_r($data);die;
           $nomor_loket[$i] = $data;
       }

       //print_r($nomor_loket);die;

       echo json_encode($nomor_loket);
       //echo $nomor_loket;
   }

    public function reload_antrian_farmasi()
   {
       # code...
       $data = $this->display_antrian->get_antrian_farmasi();
       $total = count($data);
       // echo '<pre>';print_r($data);die;

       echo json_encode(array('total' => $total,'result' => $data));
       //echo $nomor_loket;
   }

}

/* End of file empty_module.php */
/* Location: ./application/modules/empty_module/controllers/empty_module.php */

