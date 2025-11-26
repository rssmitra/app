<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Display_antrian extends MX_Controller {

    function __construct() {
        parent::__construct();
 
        $this->load->model('Display_antrian_model','display_antrian'); 
        $this->load->model('farmasi/Log_proses_resep_obat_model', 'Log_proses_resep_obat');
        $this->load->model('farmasi/Turn_around_time_model', 'Turn_around_time');
        $this->load->model('farmasi/E_resep_rj_model', 'E_resep_rj');
        $this->load->model('antrian/loket_model','loket');

    }

    public function index() {
        
        $data = array(
            'app' => $this->db->get_where('tmp_profile_app', array('id' => 1))->row(),
        );

        $this->load->view('display_antrian/index', $data);
    }

    public function farmasi() {
        
        $data = array();
        $date = isset($_GET['tanggal'])?$_GET['tanggal']:date('Y-m-d');
        $resep_diterima = $this->E_resep_rj->get_data_resep_diterima();
        $resep = $this->Log_proses_resep_obat->get_data();
        // echo '<pre>';print_r($resep);die;
        $data['resep_diterima'] = $resep_diterima;
        $data['resep'] = $resep;
        $data['text_hide'] = ['NY.','AN.','BY.', ', NY.',', AN.', ', TN.','TN.', ',NY'];

        $querytat = $this->Turn_around_time->get_data();
        $arr_seconds = [];
        foreach ($querytat as $row_tat) {
            $arr_seconds[] = $this->tanggal->diffHourMinuteReturnSecond($row_tat->log_time_1, $row_tat->log_time_5);
        }
        $data['avg_tat'] = (count($arr_seconds) > 0) ? $this->tanggal->convertHourMinutesSecond(array_sum($arr_seconds)/count($arr_seconds), 45) : '00:00:00';
        $this->load->view('display_antrian/index_farmasi', $data);
    }

    public function farmasi_publik() {
        
        $data = array();
        $date = isset($_GET['tanggal'])?$_GET['tanggal']:date('Y-m-d');
        $resep_diterima = $this->E_resep_rj->get_data_resep_diterima();
        // echo '<pre>';print_r($this->db->last_query());die;
        $resep = $this->Log_proses_resep_obat->get_data();
        $data['resep_diterima'] = $resep_diterima;
        $data['resep'] = $resep;
        $data['text_hide'] = ['NY.','AN.','BY.', ', NY.',', AN.', ', TN.','TN.', ',NY'];
        $this->load->view('display_antrian/index_farmasi_publik', $data);
    }

    public function getAntrianReturnObject() {
        
        $data = array();
        $date = isset($_GET['tanggal'])?$_GET['tanggal']:date('Y-m-d');
        $resep_diterima = $this->E_resep_rj->get_data_resep_diterima();
        // echo '<pre>';print_r($this->db->last_query());die;
        $resep = $this->Log_proses_resep_obat->get_data();
        $data['resep_diterima'] = $resep_diterima;
        $data['resep'] = $resep;
        $data['text_hide'] = ['NY.','AN.','BY.', ', NY.',', AN.', ', TN.','TN.', ',NY'];
        echo json_encode(['code' => '200', 'data' => $data]);
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
      

       $data = $this->display_antrian->get_antrian_farmasi_original();
       $resep = $this->Log_proses_resep_obat->get_data();
       $total = count($data);
    //    echo '<pre>';print_r($resep);die;

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

