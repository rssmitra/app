<?php
 header("Access-Control-Allow-Origin: *");

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class AntrianBPJS extends MX_Controller {

    function __construct(){

        date_default_timezone_set("Asia/Jakarta");
        // Construct the parent class
        // header('Access-Control-Allow-Origin: *');
        // header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        // header('Content-Type: application/json');
        parent::__construct();
        // default authentication
        $this->username = '4dm1nR55m';
        $this->password = 'P@s5W0rdR55m';
        
    }
    
    private function _queryJadwalOperasi(){

        $this->db->select('tc_pesanan.id_tc_pesanan, tc_pesanan.nama, tc_pesanan.tgl_pesanan, tc_pesanan.no_mr, mt_bagian.nama_bagian, mt_karyawan.nama_pegawai, mt_perusahaan.nama_perusahaan, tc_pesanan.tgl_masuk, tc_pesanan.kode_tarif, tc_pesanan.diagnosa, tc_pesanan.keterangan, tc_pesanan.jam_pesanan, mt_master_tarif.nama_tarif');
		$this->db->from('tc_pesanan');
		$this->db->join('mt_bagian', 'mt_bagian.kode_bagian=tc_pesanan.no_poli','inner');
		$this->db->join('mt_karyawan', 'mt_karyawan.kode_dokter=tc_pesanan.kode_dokter','inner');
		$this->db->join('mt_master_pasien', 'mt_master_pasien.no_mr=tc_pesanan.no_mr','inner');
		$this->db->join('mt_perusahaan', 'mt_perusahaan.kode_perusahaan=mt_master_pasien.kode_perusahaan','left');
		$this->db->join('mt_master_tarif', 'mt_master_tarif.kode_tarif=tc_pesanan.kode_tarif','left');
		
		/*if isset parameter*/

		if (isset($_POST['tanggalawal']) AND $_POST['tanggalawal'] != '' or isset($_POST['tanggalakhir']) AND $_POST['tanggalakhir'] != '') {
            $this->db->where("tc_pesanan.tgl_pesanan >= '".$this->tanggal->selisih($_POST['tanggalawal'],'-1')."'" );
            $this->db->where("tc_pesanan.tgl_pesanan <= '".$this->tanggal->selisih($_POST['tanggalakhir'],'+1')."'" );
        }
        /*end parameter*/


    }

    public function getToken() {
       
        $content = file_get_contents("php://input");
        $post = json_decode($content);
        
        try {
            $token = $this->checkAccount($post);
            $response = array(
                'response' => array(
                    'token' => $token
                    ),
                'metaData' => array(
                    'code' => 200,
                    'message' => 'Sukses',
                    )
            );
            echo json_encode($response);
            
        } catch ( Exception $err) {
            echo 'Caught exception: ',  $err->getMessage(), "\n";
        } 

    }

    public function checkAccount($post){

        if ( $post->username != $this->username ) {
            throw new Exception("Incorrect Key!");
        }

        if ( $post->password != $this->password ) {
            throw new Exception("Incorrect Key!");
        }

        $concat = $post->username.'/'.$post->password;
        $token = md5($concat);

        return $token;

    }

    public function getNoAntrean() {
        
        $content = file_get_contents("php://input");
        $post = json_decode($content);
        print_r($post);die;
        // cek data pasien, apakah sudah terdaftar atau belum
        $dt_pasien = $this->db->get_where('mt_master_pasien', array('no_mr' => $post->nomorrm ) );
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
        $kode_poli = $this->getKodeInternalPoli();

        // get hari
        $timestamp = strtotime($post->tanggalperiksa);
        $day = date('D', $timestamp);
        $hari = $this->tanggal->getHari($day);

        /*getDokter*/
        $config = [
            'link' => $this->api->base_api_ws().'Templates/References/getDokterBySpesialisFromJadwal/'.$kode_poli->kode_bagian.'/'.$hari.'',
            'data' => array(),
        ];

        $response_dokter = $this->api->getDataWs( $config ); 
        // print_r($config); exit;
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
        $getJadwal = array();
        foreach ($response_dokter as $key => $val_dokter) {
            $jadwal = $this->db->get_where('tr_jadwal_dokter', array('jd_kode_dokter' => $val_dokter->kode_dokter, 'jd_hari' => $hari, 'jd_kode_spesialis' => $kode_poli->kode_bagian) );
            if( $jadwal->num_rows() > 0 ){
                $getJadwal[] = array('jadwal_dokter' => $val_dokter, 'dt_jadwal' => $jadwal->row());
            }
        }
        
        if( count( $getJadwal ) > 0 ){
            // cek kuota
            foreach( $getJadwal as $dt_jadwal ){

                $config_kuota = [
                    'link' => $this->api->base_api_ws().'Templates/References/CheckSelectedDate',
                    'data' => array(
                            'date' => $post->tanggalperiksa,
                            'kode_spesialis' => $kode_poli->kode_bagian,
                            'kode_dokter' => $dt_jadwal['jadwal_dokter']->kode_dokter,
                            'jadwal_id' => $dt_jadwal['jadwal_dokter']->jd_id,
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
                }else{
                    $getKuota[] = $dt_jadwal;
                }

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
        // print_r($getKuota);
        // get nomor antrian
        $dt_booking = $this->db->get_where('regon_booking', array('regon_booking_tanggal_perjanjian' => $post->tanggalperiksa, 'regon_booking_klinik' => $kode_poli->kode_bagian, 'regon_booking_kode_dokter' => $getKuota[0]['jadwal_dokter']->kode_dokter) );
        $no_antrian = $dt_booking->num_rows() + 1;
        // get kode booking
        $kode_booking = $this->create_kode_booking($post->nomorrm, $post->tanggalperiksa);

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
                'kodebooking' => strtoupper($kode_booking),
                'jenisantrean' => 2,
                'estimasidilayani' => strtotime($waktu_datang),
                'namapoli' => $kode_poli->nama_bagian
                ),
            'metaData' => array(
                'code' => 200,
                'message' => 'Sukses',
                ),
        );

    }

    public function getRekapAntrean() {
        
        $content = file_get_contents("php://input");
        $post = json_decode($content);

        echo $post;
        
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
            // get kode internal poli
            $kode_poli = $this->getKodeInternalPoli();
            // jumlah terlayani
            $register = $this->db->get_where('tc_registrasi', array('tgl_jam_masuk' => $_POST['tanggalperiksa'], 'kode_bagian_masuk' => $kode_poli->kode_bagian) );
            // jumlah antrean
            $dt_booking = $this->db->get_where('regon_booking', array('regon_booking_tanggal_perjanjian' => $_POST['tanggalperiksa'], 'regon_booking_klinik' => $kode_poli->kode_bagian) );
            // response
            $response = array(
                'response' => array(
                    'namapoli' => $kode_poli->nama_bagian,
                    'totalantrean' => $dt_booking->num_rows(),
                    'jumlahterlayani' => $register->num_rows(),
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

    public function getListJadwalOperasi() {
       
        $this->load->library('form_validation');
        $val = $this->form_validation;

        $val->set_rules('tanggalawal', 'tanggal periksa', 'trim|required');
        $val->set_rules('tanggalakhir', 'tanggal periksa', 'trim|required');

        $val->set_message('required', "Silahkan isi field \"%s\"");

        if ($val->run() == FALSE)
        {
            $val->set_error_delimiters('', '');
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            $this->db->trans_begin();
            
            $this->_queryJadwalOperasi();
            $this->db->where('tc_pesanan.flag IS NOT NULL');
            $this->db->where('CONVERT(VARCHAR(11),tc_pesanan.tgl_pesanan,102) >= CONVERT(VARCHAR(11),getdate(),102) ');
            $result = $this->db->get()->result();
            
            $getList = array();
            foreach($result as $row){
                $getList[] = array(
                        'kodebooking' => $row->no_mr,
                        'tanggaloperasi' => $this->tanggal->formatDate($row->jam_pesanan),
                        'jenistindakan' => $row->nama_tarif,
                        'kodepoli' => strtotime('Y-m-d H:i:s'),
                        'namapoli' => $row->nama_bagian,
                        'terlaksana' => ($row->tgl_masuk == NULL) ? 0 : 1,
                        'nopeserta' => $row->no_mr,
                        'lastupdate' => strtotime(date('Y-m-d H:i:s'))
                );
            }
            // response
            $response = array(
                'response' => array(
                    'list' => $getList
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

    public function getListJadwalOperasiByPasien() {
       
        $this->load->library('form_validation');
        $val = $this->form_validation;

        $val->set_rules('nopeserta', 'Nomor MR', 'trim|required');

        $val->set_message('required', "Silahkan isi field \"%s\"");

        if ($val->run() == FALSE)
        {
            $val->set_error_delimiters('', '');
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            $this->db->trans_begin();
            
            $this->_queryJadwalOperasi();
            $this->db->where('tc_pesanan.flag IS NOT NULL');
            $this->db->where('tc_pesanan.no_mr', $_POST['nopeserta']);
            $result = $this->db->get()->result();
            
            $getList = array();
            foreach($result as $row){
                $getList[] = array(
                        'kodebooking' => $row->no_mr,
                        'tanggaloperasi' => $this->tanggal->formatDate($row->jam_pesanan),
                        'jenistindakan' => $row->nama_tarif,
                        'kodepoli' => strtotime('Y-m-d H:i:s'),
                        'namapoli' => $row->nama_bagian,
                        'terlaksana' => ($row->tgl_masuk == NULL) ? 0 : 1,
                        'nopeserta' => $row->no_mr,
                        'lastupdate' => strtotime(date('Y-m-d H:i:s'))
                );
            }
            // response
            $response = array(
                'response' => array(
                    'list' => $getList
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
        return $s.'-MBLJKN';

    }

    public function getKodeInternalPoli(){
        
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
        }else{
            return $kode_poli;
        }
        
    }

    
    
}

/* End of file empty_module.php */
/* Location: ./application/modules/empty_module/controllers/empty_module.php */

