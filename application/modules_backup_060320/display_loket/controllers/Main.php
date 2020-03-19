<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Main extends MX_Controller {

    function __construct() {
        parent::__construct();
 
        $this->load->model('main_model','Main'); 

    }

    public function index() {
        $data = array();
        $data['app'] = $this->db->get_where('tmp_profile_app', array('id' => 1))->row();
        $this->load->view('Main/main_view', $data);
    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->Main->get_datatables_display();
       // $data = array();
        $no = 0;;
        foreach ($list as $row_list) {
            $no++;
            $row = array();
          
            $row[] = '<div class="center">'.$no.'</div>';
            $row[] = '<p>'.strtoupper($row_list->nama_bagian).'</p>';
            $row[] = $row_list->nama_pegawai;
            $row[] = $this->tanggal->formatTime($row_list->jd_jam_mulai).' s/d '.$this->tanggal->formatTime($row_list->jd_jam_selesai);
            $row[] = '<div class="center">'.$row_list->jd_kuota.'</div>';
            /*cek sisa kuota*/
            $sisa_kuota = $row_list->jd_kuota - $row_list->kuota_terpenuhi;
            /*sisa kuota*/
            /*$get_kuota = $this->Main->get_sisa_kuota($row_list); */
            //print_r($get_kuota);die;

            $row[] = '<div class="center">'.$sisa_kuota.'</div>';
            //$row[] = $row_list->status_jadwal;
            $row[] = ($row_list->status_jadwal == 'Loket dibuka') ? '<div class="center"><button style="background-color:#00d166;">Open</buton></div>' : '<div class="center"><button style="background-color:red;">Close</button></div>';
            $status_jadwal = '';
            if(!in_array($row_list->status_jadwal, array('Loket dibuka','Loket ditutup') )){
                $status_jadwal = $row_list->status_jadwal.'<br>';
            }
            $row[] = '<p style="font-size:14px">'.strtoupper($status_jadwal).''.strtoupper($row_list->jd_keterangan).'<br>'.strtoupper($row_list->keterangan).'</p>';
                   
            $data[] = $row;
        }

        $output = array(
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

}

/* End of file empty_module.php */
/* Location: ./application/modules/empty_module/controllers/empty_module.php */

