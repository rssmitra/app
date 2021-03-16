<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Antrian extends MX_Controller {

    function __construct() {
        parent::__construct();
 
        $this->load->model('antrian_model'); 
        $this->load->model('loket_model','loket'); 

        $this->load->library('Print_direct');

    }

    public function index() {
        
        $data_loket = $this->loket->get_open_loket();

        foreach ($data_loket as $key => $value) {
            # code...
            $kuota = $this->loket->get_sisa_kuota($value);
            if($kuota<0)$kuota=0;
            $data_loket[$key]->kuota = $kuota;
        }

        //print_r($_GET['type']);die;
        $data['type'] = isset($_GET['type'])?$_GET['type']:'bpjs';
        
        $data['klinik'] = $data_loket;

        $this->load->view('Antrian/index', $data);
    }

    public function poli() {
        
        $data_loket = $this->loket->get_open_loket();

        foreach ($data_loket as $key => $value) {
            # code...
            $kuota = $this->loket->get_sisa_kuota($value);
            if($kuota<0)$kuota=0;
            $data_loket[$key]->kuota = $kuota;
        }

        //print_r($_GET['type']);die;
        $data['type'] = isset($_GET['type'])?$_GET['type']:'bpjs';
        
        $data['klinik'] = $data_loket;

        $this->load->view('Antrian/index_poli', $data);
    }

    public function process()
    {
        # code...
        $data = $_POST['data'];
           
        $booking = $this->antrian_model->get_booked($data[1]);

        $jam_praktek = explode(" s/d ",$booking->regon_booking_jam);
              
        $jam_ = date("H:i",strtotime($jam_praktek[1]));
        $jam_now = date('H:i');

        // print_r($jam_);echo "<br>";print_r($jam_now);die;

        if(isset($booking)){
            if($booking->regon_booking_tanggal_perjanjian==date('Y-m-d')){
                // if($jam_now <= $jam_){

                    $query="select * from tr_antrian where ant_type ='online'";
                    $no_ = $this->db->query($query)->num_rows();
                    $no = $no_ + 1;

                    $dataexc = array(
                        'ant_kode_spesialis' => $booking->regon_booking_kode_dokter,
                        'ant_kode_dokter' => $booking->regon_booking_klinik,
                        'ant_status' => 0,
                        'ant_type' => 'online',
                        'ant_date' => date('Y-m-d H:i:s'),
                        'ant_no' => $no,
                        'ant_panggil' => 0,
                        'log' => json_encode(array('dokter' => $booking->nama_pegawai,'klinik' => $booking->nama_bagian, 'jam_praktek' => $jam_praktek[0])),
                    );
                    $this->loket->save('tr_antrian',$dataexc);

                    echo json_encode(array('status' => 200, 'message' => 'Berhasil'));

                    $this->print_direct->printer_antrian_php($dataexc);
                // } else {
                //     echo json_encode(array('status' => 301, 'message' => 'Maaf, Loket hanya tersedia sesuai jadwal waktu kedatangan di aplikasi anda'));
                // }
            }else{
                echo json_encode(array('status' => 301, 'message' => 'Perjanjian anda tidak terdaftar di hari ini, silahkan ambil antrian reguler jika ingin registrasi'));
            }
        } else {
            echo json_encode(array('status' => 301, 'message' => 'Kode booking tidak valid'));
        }

    }

}

/* End of file empty_module.php */
/* Location: ./application/modules/empty_module/controllers/empty_module.php */

