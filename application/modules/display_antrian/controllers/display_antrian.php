<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Display_antrian extends MX_Controller {

    function __construct() {
        parent::__construct();
 
        $this->load->model('Display_antrian_model','display_antrian'); 
        $this->load->model('antrian/loket_model','loket');

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

    public function poli_farmasi() {
        $data = [
            'data_loket' => $this->loket->get_open_loket(),
        ];
        $this->load->view('display_antrian/index_poli_farmasi', $data);
    }

    public function poli_group_w_unit() {
        
        $data = [
            'data_loket' => $this->loket->get_open_loket(),
        ];
        // echo "<pre>"; print_r($data);die;
        $this->load->view('display_antrian/index_poli', $data);
    }

    public function poli_utama() {
        
        $data = [
            'data_loket' => $this->loket->get_open_loket(),
        ];
        // echo "<pre>"; print_r($data);die;
        $this->load->view('display_antrian/index_poli_utama', $data);
    }

    public function poli_paru_farmasi() {
        
        $data = [
            'data_loket' => $this->loket->get_open_loket(),
        ];
        // echo "<pre>"; print_r($data);die;
        $this->load->view('display_antrian/index_poli_paru_farmasi', $data);
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
    //    echo '<pre>';print_r($data);die;

       echo json_encode(array('total' => $total,'result' => $data));
       //echo $nomor_loket;
   }

   public function reload_antrian_poli()
   {

        $data = $this->display_antrian->get_antrian_poli();
        // echo '<pre>';print_r($data);die;

        echo json_encode(array('result' => $data));
   }

}

/* End of file empty_module.php */
/* Location: ./application/modules/empty_module/controllers/empty_module.php */

