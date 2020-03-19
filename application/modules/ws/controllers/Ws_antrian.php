<?php
 header("Access-Control-Allow-Origin: *");

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ws_antrian extends MX_Controller {

    function __construct(){
        date_default_timezone_set("Asia/Jakarta");
        // Construct the parent class
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        parent::__construct();
        // default authentication
        $this->username = '4dm1nR55m';
        $this->password = 'P@s5W0rdR55m';

    }
  
    public function getToken() {
       
        $this->load->library('form_validation');
        $val = $this->form_validation;

        $val->set_rules('username', 'Username', 'trim|required');
        $val->set_rules('password', 'Password', 'trim|required');

        $val->set_message('required', "Silahkan isi field \"%s\"");

        if ($val->run() == FALSE)
        {
            $val->set_error_delimiters('', '');
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            $this->db->trans_begin();

            if ( $_POST['username'] != $this->username ) {
                die("Username tidak sesuai!");
            }

            if ( $_POST['password'] != $this->password ) {
                die("Password tidak sesuai!");
            }

            // get token
            $concat = $_POST['username'].'/'.$_POST['password'];
            $token = md5($concat);
            // response
            $response = array(
                'response' => array(
                    'token' => $token
                    ),
                'metaData' => array(
                    'code' => 200,
                    'message' => 'Sukses',
                    )
            );

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode($response);
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode($response);
            }
        }

    }

    public function getNoAntrean() {
       
        $this->load->library('form_validation');
        $val = $this->form_validation;

        $val->set_rules('nomorkartu', 'nomorkartu', 'trim|required');
        $val->set_rules('nik', 'nik', 'trim|required');
        $val->set_rules('nomorrm', 'nomorrm', 'trim|required');
        $val->set_rules('notelp', 'notelp', 'trim|required');
        $val->set_rules('tanggalperiksa', 'tanggalperiksa', 'trim|required');
        $val->set_rules('kodepoli', 'kodepoli', 'trim|required');
        $val->set_rules('nomorreferensi', 'nomorreferensi', 'trim|required');
        $val->set_rules('jenisreferensi', 'jenisreferensi', 'trim|required');
        $val->set_rules('jenisrequest', 'jenisrequest', 'trim|required');
        $val->set_rules('polieksekutif', 'polieksekutif', 'trim|required');

        $val->set_message('required', "Silahkan isi field \"%s\"");

        if ($val->run() == FALSE)
        {
            $val->set_error_delimiters('', '');
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            $this->db->trans_begin();

            // cek pasien existing di rs
            $dt_pasien = $this->db->get_where('mt_master_pasien', array('no_mr' => $_POST['nomorrm'] ) );
            if( $dt_pasien->num_rows() == 0 ){
                $response = array(
                    'metaData' => array(
                        'code' => 300,
                        'message' => 'Pasien belum terdaftar',
                        ),
                );
                echo json_encode($response);
                exit;
            }

            // cek jadwal praktek dokter
            // get kode internal poli
            $kode_poli = $this->db->get_where('mt_bagian', array('kode_poli_bpjs' => $_POST['kodepoli']) )->row();
            if( empty($kode_poli) ){
                $response = array(
                    'metaData' => array(
                        'code' => 300,
                        'message' => 'Kode Poli belum terdaftar di rs',
                        ),
                );
                echo json_encode($response);
                exit;
            }

            // get hari
            $timestamp = strtotime($_POST['tanggalperiksa']);
            $day = date('D', $timestamp);
            $hari = $this->tanggal->getHari($day);

            /*getDokter*/
            $config = [
                'link' => $this->api->base_api_ws().'Templates/References/getDokterBySpesialisFromJadwal/'.$kode_poli->kode_bagian.'/'.$hari.'',
                'data' => array(),
            ];
            $response_dokter = $this->api->getDataWs( $config ); 
            // print_r($response_dokter);
            if ( count($response_dokter) == 0 ) {
                $response = array(
                    'metaData' => array(
                        'code' => 300,
                        'message' => 'Tidak ada jadwal praktek',
                        ),
                );
                echo json_encode($response);
                exit;
            }
            
            // cek jadwal dokter
            $jadwal = $this->db->get_where('tr_jadwal_dokter', array('jd_kode_dokter' => $response_dokter[0]->kode_dokter, 'jd_hari' => $hari, 'jd_kode_spesialis' => $kode_poli->kode_bagian) );
            // print_r($this->db->last_query());
            
            if( $jadwal->num_rows() > 0 ){
                // cek kuota
                $config_kuota = [
                    'link' => $this->api->base_api_ws().'Templates/References/CheckSelectedDate',
                    'data' => array(
                            'date' => $_POST['tanggalperiksa'],
                            'kode_spesialis' => $kode_poli->kode_bagian,
                            'kode_dokter' => $response_dokter[0]->kode_dokter,
                            'jadwal_id' => $jadwal->row()->jd_id,
                        ),
                ];
                $response_kuota = $this->api->getDataWs( $config_kuota ); 
                // print_r($response_kuota);
                if ( $response_kuota->sisa <= 0 ) {
                    $response = array(
                        'metaData' => array(
                            'code' => 300,
                            'message' => 'Kuota Penuh',
                            ),
                    );
                    echo json_encode($response);
                    exit;
                }
            }else{
                $response = array(
                    'metaData' => array(
                        'code' => 300,
                        'message' => 'Tidak ada jadwal praktek',
                        ),
                );
                echo json_encode($response);
                exit;
            }
            
            // get nomor antrian
            $dt_booking = $this->db->get_where('regon_booking', array('regon_booking_tanggal_perjanjian' => $_POST['tanggalperiksa'], 'regon_booking_klinik' => $kode_poli->kode_bagian, 'regon_booking_kode_dokter' => $response_dokter[0]->kode_dokter) );
            $no_antrian = $dt_booking->num_rows() + 1;
            // get kode booking
            $kode_booking = $this->create_kode_booking($_POST['nomorrm'], $_POST['tanggalperiksa']);

            /*hitung estimasi waktu kedatangan pasien */
            $date = date_create($val->set_value('tanggalperiksa').' '.date('H:i:s') );
            date_add($date, date_interval_create_from_date_string('-2 hours'));
            $waktu_datang = date_format($date, 'Y-m-d H:i:s');

            // response
            $response = array(
                'response' => array(
                    'nomr' => $dt_pasien->row()->no_mr,
                    'namapasien' => $dt_pasien->row()->nama_pasien,
                    'nomorantrean' => $no_antrian,
                    'kodebooking' => $kode_booking,
                    'jenisantrean' => 2,
                    'estimasidilayani' => strtotime($waktu_datang),
                    'namapoli' => $kode_poli->nama_bagian
                    ),
                'metaData' => array(
                    'code' => 200,
                    'message' => 'Sukses',
                    ),
            );

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode($response);
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode($response);
            }
        }

    }

    public function getRekapAntrean() {
       
        $this->load->library('form_validation');
        $val = $this->form_validation;

        $val->set_rules('tanggalperiksa', 'tanggal periksa', 'trim|required');
        $val->set_rules('kodepoli', 'poli', 'trim|required');
        $val->set_rules('polieksekutif', 'polie ksekutif', 'trim|required');

        $val->set_message('required', "Silahkan isi field \"%s\"");

        if ($val->run() == FALSE)
        {
            $val->set_error_delimiters('', '');
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            $this->db->trans_begin();

            // response
            $response = array(
                'response' => array(
                    'namapoli' => 'Poli Jantung',
                    'totalantrean' => 100,
                    'jumlahterlayani' => 46,
                    'lastupdate' => strtotime('Y-m-d H:i:s'),
                    'lastupdatetanggal' => date('Y-m-d H:i:s')
                    ),
                'metaData' => array(
                    'code' => 200,
                    'message' => 'Sukses',
                    ),
            );

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode($response);
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode($response);
            }
        }

    }

    public function create_kode_booking($no_mr, $tgl_periksa){
        $string = $no_mr.$tgl_periksa.'abcdefghijklmnpqrstuvwxyz';
        $clean_string = str_replace(array('1','i','0','o','/','-'),'',$string);
        $s = substr(str_shuffle(str_repeat($clean_string, 6)), 0, 6);
        return $s;

    }

}

/* End of file empty_module.php */
/* Location: ./application/modules/empty_module/controllers/empty_module.php */

