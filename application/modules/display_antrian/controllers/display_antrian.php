<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Display_antrian extends MX_Controller {

    function __construct() {
        parent::__construct();
 
        $this->load->model('Display_antrian_model','display_antrian'); 
        $this->load->model('farmasi/Log_proses_resep_obat_model', 'Log_proses_resep_obat');
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

        $resep_diterima = $this->db->select('mt_master_pasien.nama_pasien, tgl_pesan as tgl_trans, fr_tc_far.kode_trans_far')->order_by('tgl_pesan', 'ASC')->join('mt_master_pasien','mt_master_pasien.no_mr=fr_tc_pesan_resep.no_mr','left')->join('fr_tc_far','fr_tc_far.kode_pesan_resep=fr_tc_pesan_resep.kode_pesan_resep','left')->get_where('fr_tc_pesan_resep', ['CAST(tgl_pesan as DATE) = ' => $date, 'fr_tc_pesan_resep.kode_profit' => 2000, 'substring(fr_tc_pesan_resep.kode_bagian_asal, 1,2) = ' => '01', 'status_batal' => NULL])->result();

        // echo '<pre>';print_r($resep_diterima);die;
        $resep = $this->Log_proses_resep_obat->get_data();
        $data['resep_diterima'] = $resep_diterima;
        $data['resep'] = $resep;
        $data['text_hide'] = ['NY.','AN.','BY.', ', NY.',', AN.', ', TN.','TN.', ',NY'];
        $this->load->view('display_antrian/index_farmasi', $data);
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

