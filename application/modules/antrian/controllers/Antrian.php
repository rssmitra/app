<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Antrian extends MX_Controller {

    function __construct() {
        parent::__construct();
 
        $this->load->model('antrian_model'); 
        $this->load->model('loket_model','loket');
        $this->load->model('display_loket/main_model','Main');  
        $this->load->model('ws/AntrianOnlineModel','AntrianOnline');  

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

    public function pasien() {
        
        $data = [];
        $this->load->view('Antrian/index_pasien', $data);
    }

    public function antrian_pendaftaran_pasien() {
        
        $data = [];
        $data['list_jadwal'] = $this->Main->get_datatables_display();
        $this->load->view('Antrian/pendaftaran_pasien', $data);
    }

     public function antrian_pendaftaran_pasien_dt() {
        $data = $this->Main->get_datatables_display();
        echo json_encode($data);
    }

    public function antrian_instalasi_farmasi() {
        
        $data = [];
        $this->load->view('Antrian/instalasi_farmasi', $data);
    }

    // public function antrian_poli() {
        
    //     $data = [];
    //     $this->load->view('Antrian/poli', $data);
    // }

    // public function antrian_poli() {
        
    //     $data = [];
    //     $this->load->view('Antrian/antrian_poli', $data);
    // }

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

        $this->load->view('Antrian/poli', $data);
    }

    public function process()
    {
        # code...

        // print_r($_POST);die;
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

    public function process_cek_kode_booking()
    {
        # code...

        $data = $_POST['data'];
        
        $kode_dokter = $data[1];
        $kode_poli = $data[3];
        $kode_booking = $data[8];
        
        // cek kode perjanjian
        $perjanjian = $this->antrian_model->cek_kode_perjanjian($kode_booking, $kode_dokter, $kode_poli);

        // echo '<pre>'; print_r($_POST);die;
        // echo '<pre>'; print_r($perjanjian->row());die;
        if( $perjanjian->num_rows() == 0){
            echo json_encode(array('status' => 301, 'message' => 'Data Perjanjian/Booking tidak ditemukan untuk hari ini!'));
            exit;
        }else{

            $obj_data = $perjanjian->row();
            // cek kode poli
            if( $obj_data->no_poli != (int)$kode_poli ){
                echo json_encode(array('status' => 301, 'message' => 'Poli yang anda pilih tidak sesuai dengan data perjanjian anda!'));
                exit;
            }

            // cek kode dokter
            if( $obj_data->kode_dokter != (int)$kode_dokter ){
                echo json_encode(array('status' => 301, 'message' => 'Dokter yang anda pilih tidak sesuai dengan data perjanjian anda!'));
                exit;
            }

            // cek tgl available
            if( $obj_data->tgl_pesanan != date('Y-m-d') ){
                echo json_encode(array('status' => 301, 'message' => 'Tanggal kontrol anda tidak sesuai!'));
                exit;
            }

            $this->db->trans_begin();

            // print_r($datakuota);die;

            // prosess 
            $no_ = $this->db->get_where('tr_antrian',array('ant_type' => 'bpjs'))->num_rows();
            $no = $no_ + 1;
    
            $dataexc = array(
                'ant_kode_spesialis' => $data[3],
                'ant_kode_dokter' => $data[1],
                'ant_status' => 0,
                'ant_type' => $data[0],
                'ant_date' => date('Y-m-d H:i:s'),
                'ant_panggil' => 0,
                'log' => json_encode(array('dokter' => $data[2],'klinik' => $data[4], 'jam_praktek' => $data[6], 'kode_booking' => $obj_data->unique_code_counter)),
            );

            /*save antrian  jika belum pernah diprint, maka print baru*/
            if($obj_data->status_konfirmasi_kedatangan != 1){

                $dataexc['ant_no'] = $no;
                $datakuota = array(
                    'tanggal' => date('Y-m-d'),
                    'kode_dokter' => $dataexc['ant_kode_dokter'],
                    'kode_spesialis' => $dataexc['ant_kode_spesialis'], 
                    'flag' => 'mesin_antrian', 
                );

                $this->loket->save('tr_antrian',$dataexc);
                $this->loket->save('log_kuota_dokter',$datakuota);

                // update task antrian online
                $waktukirim = strtotime(date('Y-m-d H:i:s')) * 1000;
                $this->AntrianOnline->postDataWs('antrean/updatewaktu', array('kodebooking' => $perjanjian->kode_perjanjian, 'taskid' => 2, 'waktu' => $waktukirim));

            }else{
                $dataexc['ant_no'] = $obj_data->no_antrian;
            }
            
            // udpate perjanjian
            $this->db->where('id_tc_pesanan', $obj_data->id_tc_pesanan)->update('tc_pesanan', array('status_konfirmasi_kedatangan' => 1, 'no_antrian' => $dataexc['ant_no']));
            // print_r($this->db->last_query());die;

            $this->print_direct->printer_antrian_php_kiosk($dataexc);

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses berhasil dilakukan!', 'dokter' => $data[2],'klinik' => $data[4], 'jam_praktek' => $data[6],'type' => $data[0],'no' => $dataexc['ant_no']));
            }

        }

    }

}

/* End of file empty_module.php */
/* Location: ./application/modules/empty_module/controllers/empty_module.php */

