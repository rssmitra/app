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
        // load model
        $this->load->model('ws_bpjs/Ws_index_model', 'Ws_index');
        $this->load->model('booking/Regon_booking_model', 'Regon_booking');
        // load module
        $this->load->module('templates/References');

        // default username and password
        $this->username = '4dm1nR55m';
        $this->password = 'P@s5W0rdR55m';
        
    }
    
    private function _queryJadwalOperasi($from_tgl='', $to_tgl=''){

        $this->db->select('tc_pesanan.id_tc_pesanan, tc_pesanan.nama, tc_pesanan.tgl_pesanan, tc_pesanan.no_mr, mt_bagian.nama_bagian, mt_karyawan.nama_pegawai, mt_perusahaan.nama_perusahaan, tc_pesanan.tgl_masuk, tc_pesanan.kode_tarif, tc_pesanan.diagnosa, tc_pesanan.keterangan, tc_pesanan.jam_pesanan, mt_master_tarif.nama_tarif, kode_poli_bpjs, nopesertabpjs');
		$this->db->from('tc_pesanan');
		$this->db->join('mt_bagian', 'mt_bagian.kode_bagian=tc_pesanan.no_poli','inner');
		$this->db->join('mt_karyawan', 'mt_karyawan.kode_dokter=tc_pesanan.kode_dokter','inner');
		$this->db->join('mt_master_pasien', 'mt_master_pasien.no_mr=tc_pesanan.no_mr','inner');
		$this->db->join('mt_perusahaan', 'mt_perusahaan.kode_perusahaan=mt_master_pasien.kode_perusahaan','left');
		$this->db->join('mt_master_tarif', 'mt_master_tarif.kode_tarif=tc_pesanan.kode_tarif','left');
		
		/*if isset parameter*/
		if (isset($from_tgl) AND $from_tgl != '' or isset($to_tgl) AND $to_tgl != '') {
            $this->db->where("CAST(tc_pesanan.tgl_pesanan as DATE) >= '".$from_tgl."'" );
            $this->db->where("CAST(tc_pesanan.tgl_pesanan as DATE) <= '".$to_tgl."'" );
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
                'metadata' => array(
                    'code' => 200,
                    'message' => 'Sukses',
                    )
            );
            echo json_encode($response);
            
        } catch ( Exception $err) {
            $response = array(
                'metadata' => array(
                    'code' => 300,
                    'message' => 'Username dan Password yagn anda masukan salah!',
                    )
            );
            echo json_encode($response);
            // 'Caught exception: ',  $err->getMessage(), "\n";
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

    public function getNoAntrian() {
        
        $content = file_get_contents("php://input");
        $post = json_decode($content);

        // check jenis referensi
        if( !in_array($post->jenisreferensi, array(1,2)) ){
            $response = array(
                'metadata' => array(
                    'code' => 300,
                    'message' => 'Jenis Referensi Salah!',
                    ),
            );
            echo json_encode($response);
            exit;
        }
        // check jenis request
        if( !in_array($post->jenisrequest, array(1,2)) ){
            $response = array(
                'metadata' => array(
                    'code' => 300,
                    'message' => 'Jenis Request Salah!',
                    ),
            );
            echo json_encode($response);
            exit;
        }
        // check tgl kunjungan 
        if($post->tanggalperiksa < date('Y-m-d')){
            $response = array(
                'metadata' => array(
                    'code' => 300,
                    'message' => 'Tanggal Kunjungan Expired!',
                    ),
            );
            echo json_encode($response);
            exit;
        }

        // search member by nik
        $result = $this->Ws_index->searchMemberByNIK($post->nik, $post->tanggalperiksa);
        if($result->metaData->code != 200){
            $response = array(
                'metadata' => array(
                    'code' => 300,
                    'message' => $result->metaData->message,
                    ),
            );
            echo json_encode($response);
            exit;
        }

        // search member by nomor kartu
        $result = $this->Ws_index->searchMemberByNomorKartu($post->nomorkartu, $post->tanggalperiksa);
        
        if($result->metaData->code != 200){
            $response = array(
                'metadata' => array(
                    'code' => 300,
                    'message' => $result->metaData->message,
                    ),
            );
            echo json_encode($response);
            exit;
        }
        $peserta_dt = $result->response->peserta;
        // print_r($peserta_dt);die;
        // cek nomor rujukan
        $rujukan = $this->Ws_index->searchRujukanRsByNomorRujukan($post->nomorreferensi);
        if($rujukan->metaData->code != 200){
            $response = array(
                'metadata' => array(
                    'code' => 300,
                    'message' => $rujukan->metaData->message,
                    ),
            );
            echo json_encode($response);
            exit;
        }

        // validasi tgl rujukan 90 hari
        $rujukan_dt = $rujukan->response;
        $max_date_rujukan = $this->tanggal->selisih($rujukan_dt->rujukan->tglKunjungan, '+90');
        if( $post->tanggalperiksa > $max_date_rujukan ){
            $response = array(
                'metadata' => array(
                    'code' => 300,
                    'message' => 'Nomor Rujukan Expired !',
                    ),
            );
            echo json_encode($response);
            exit;
        }

        // get kode internal poli
        $kode_poli = $this->getKodeInternalPoli($post->kodepoli);
        // get hari
        $timestamp = strtotime($post->tanggalperiksa);
        $day = date('D', $timestamp);
        $hari = $this->tanggal->getHari($day);

        /*getDokter*/
        $config = [
            'link' => base_url().'Templates/References/getDokterBySpesialisFromJadwalDefault/'.$kode_poli->kode_bagian.'/'.$hari.'',
            'data' => array(),
        ];

        $response_dokter = $this->api->getDataWs( $config ); 
        
        if ( count($response_dokter) == 0 ) {
            $response = array(
                'metadata' => array(
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
        // print_r($getJadwal);die;
        if( count( $getJadwal ) > 0 ){
            // cek kuota
            foreach( $getJadwal as $dt_jadwal ){

                $config_kuota = [
                    'link' => base_url().'Templates/References/CheckSelectedDate',
                    'data' => array(
                            'date' => $post->tanggalperiksa,
                            'kode_spesialis' => $kode_poli->kode_bagian,
                            'kode_dokter' => $dt_jadwal['jadwal_dokter']->kode_dokter,
                            'jadwal_id' => $dt_jadwal['jadwal_dokter']->jd_id,
                    ),
                ];

                $response_kuota = $this->api->getDataWs( $config_kuota ); 

                if ( $response_kuota->sisa <= 0 ) {
                    $response = array(
                        'metadata' => array(
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
                'metadata' => array(
                    'code' => 300,
                    'message' => 'Tidak ada jadwal praktek',
                    ),
            );
            echo json_encode($response);
            exit;
        }
        
        // print_r($getKuota);die;

        // get nomor antrian
        $dt_booking = $this->db->get_where('regon_booking', array('regon_booking_tanggal_perjanjian' => $post->tanggalperiksa, 'regon_booking_klinik' => $kode_poli->kode_bagian, 'regon_booking_kode_dokter' => $getKuota[0]['jadwal_dokter']->kode_dokter) );
        $no_antrian = $dt_booking->num_rows() + 1;
        // get kode booking
        $kode_booking = $this->create_kode_booking($post->nomorkartu, $post->tanggalperiksa);
        
        /*hitung estimasi waktu kedatangan pasien */
        $jam_mulai_praktek = $this->tanggal->formatFullTime($getKuota[0]['dt_jadwal']->jd_jam_mulai);
        $jam_selesai_praktek = $this->tanggal->formatFullTime($getKuota[0]['dt_jadwal']->jd_jam_selesai);
        $date = date_create($post->tanggalperiksa.' '.$jam_mulai_praktek );
        date_add($date, date_interval_create_from_date_string('-2 hours'));
        $waktu_datang = date_format($date, 'Y-m-d H:i:s');
        $milisecond = strtotime($waktu_datang) * 1000;
        // insert table regon booking
        $databooking = array(
            'regon_booking_nama_pasien' => isset($peserta_dt->nama)?$peserta_dt->nama:$post->nomorkartu,
            'regon_booking_kode' => $kode_booking,
            'regon_booking_tanggal_perjanjian' => $post->tanggalperiksa,
            'regon_booking_no_mr' => isset($peserta_dt->mr->noMR)?$peserta_dt->mr->noMR:'',
            'regon_booking_instalasi' => 'RJ',
            'regon_booking_klinik' => $kode_poli->kode_bagian,
            'regon_booking_kode_dokter' => $getKuota[0]['jadwal_dokter']->kode_dokter,
            'regon_booking_hari' => $hari,
            'regon_booking_jam' => $this->tanggal->formatTime($jam_mulai_praktek).' s/d '.$this->tanggal->formatTime($jam_selesai_praktek),
            'regon_booking_waktu_datang' => $waktu_datang,
            'regon_booking_keterangan' => 'Antrian Online Mobile JKN',
            'regon_booking_jenis_penjamin' => 'Jaminan Perusahaan',
            'regon_booking_penjamin' => 120,
            'regon_booking_status' => 0,
            'regon_booking_urutan' => 0,
            'regon_via' => 'mjkn',
            'jd_id' => $getKuota[0]['jadwal_dokter']->jd_id,
        );

        $detail_pasien = $this->db->get_where('mt_master_pasien', array('no_mr' => $databooking['regon_booking_no_mr']))->row();
        // print_r($detail_pasien);die;
        if($detail_pasien){
            $databooking['log_detail_pasien'] = json_encode( array('nama_pasien' => $detail_pasien->nama_pasien, 'tgl_lahir' => $this->tanggal->formatDate($detail_pasien->tgl_lhr), 'alamat' => $detail_pasien->almt_ttp_pasien, 'telp' => $detail_pasien->tlp_almt_ttp, 'jk' => $detail_pasien->jen_kelamin ) );
        }
        
        $log_transaksi = array(
            'klinik' => $this->master->get_custom_data('mt_bagian', array('nama_bagian','kode_bagian'), array('kode_bagian' => $kode_poli->kode_bagian ) , 'row'),
            'dokter' => $this->master->get_custom_data('mt_karyawan', array('nama_pegawai', 'kode_dokter'), array('kode_dokter' => $getKuota[0]['jadwal_dokter']->kode_dokter ) , 'row'),
            'penjamin' => $this->master->get_custom_data('mt_perusahaan', array('nama_perusahaan'), array('kode_perusahaan' => 120 ) , 'row'),
            );

        $databooking['log_transaksi'] = json_encode($log_transaksi);
        $databooking['created_date'] = date('Y-m-d H:i:s');
        $databooking['created_by'] = json_encode(array('user_id' => 0, 'fullname' => 'mjkn' ));
        /*save post data*/
        $this->Regon_booking->save($databooking);

        // print_r($databooking);die;
        // $this->insert_booking();
        // response
        $response = array(
            'response' => array(
                'nomorantrean' => $no_antrian,
                'kodebooking' => strtoupper($kode_booking),
                'jenisantrean' => 2,
                'estimasidilayani' => $milisecond,
                'namapoli' => ucwords($kode_poli->nama_bagian),
                'namadokter' => $getKuota[0]['jadwal_dokter']->nama_pegawai
                ),
            'metadata' => array(
                'code' => 200,
                'message' => 'Sukses',
                ),
        );
        
        echo json_encode($response);

    }

    public function getRekapAntrian() {
        
        $content = file_get_contents("php://input");
        $post = json_decode($content);
        
        // check tgl kunjungan 
        if( $this->tanggal->validateDate($post->tanggalperiksa) == false ){
            $response = array(
                'metadata' => array(
                    'code' => 300,
                    'message' => 'Tanggal Periksa Salah!',
                    ),
            );
            echo json_encode($response);
            exit;
        }

        // get kode internal poli
        $kode_poli = $this->getKodeInternalPoli($post->kodepoli);
        // jumlah terlayani
        $register = $this->db->get_where('tc_registrasi', array('CAST(tgl_jam_masuk as DATE) = ' => $post->tanggalperiksa, 'kode_bagian_masuk' => $kode_poli->kode_bagian) );
        // jumlah antrean
        $dt_booking = $this->db->get_where('regon_booking', array('CAST(regon_booking_tanggal_perjanjian as DATE) = ' => $post->tanggalperiksa, 'regon_booking_klinik' => $kode_poli->kode_bagian) );
        // response
        $response = array(
            'metadata' => array(
                'code' => 200,
                'message' => 'Sukses',
                ),
            'response' => array(
                'namapoli' => $kode_poli->nama_bagian,
                'totalantrean' => $dt_booking->num_rows(),
                'jumlahterlayani' => $register->num_rows(),
                'lastupdate' => strtotime('Y-m-d H:i:s'),
                'lastupdatetanggal' => date('Y-m-d H:i:s')
                ),
        );

        echo json_encode($response);
    }

    public function getListJadwalOperasi() {
       
        $content = file_get_contents("php://input");
        $post = json_decode($content);
        if( $post->tanggalakhir < $post->tanggalawal ){
            $response = array(
                'metadata' => array(
                    'code' => 300,
                    'message' => 'Tanggal akhir tidak boleh lebih kecil dari tanggal awal!',
                    ),
            );
            echo json_encode($response);
            exit;
        }
        $this->_queryJadwalOperasi($post->tanggalawal, $post->tanggalakhir);
        $this->db->where('tc_pesanan.flag', 'bedah');
        $this->db->order_by('tc_pesanan.jam_pesanan', 'DESC');
        $result = $this->db->get()->result();
        $getList = array();
        foreach($result as $row){
            $getList[] = array(
                    'kodebooking' => $row->id_tc_pesanan,
                    'tanggaloperasi' => $this->tanggal->sqlDateTimeToDate($row->jam_pesanan),
                    'jenistindakan' => $row->nama_tarif,
                    'kodepoli' => $row->kode_poli_bpjs,
                    'namapoli' => $row->nama_bagian,
                    'terlaksana' => ($row->tgl_masuk == NULL) ? 0 : 1,
                    'nopeserta' => $row->nopesertabpjs,
                    'lastupdate' => strtotime(date('Y-m-d H:i:s'))
            );
        }
        // response
        $response = array(
            'metadata' => array(
                'code' => 200,
                'message' => 'Sukses',
                ),
            'response' => array(
                'list' => $getList
            ),
            
        );
        
        echo json_encode($response);
    }

    public function getListJadwalOperasiByPasien() {
       
        $content = file_get_contents("php://input");
        $post = json_decode($content);
        
        $this->_queryJadwalOperasi();
        $this->db->where('tc_pesanan.flag', 'bedah');
        $this->db->where('tc_pesanan.tgl_masuk IS NULL');
        $this->db->where('tc_pesanan.nopesertabpjs', $post->nopeserta);
        $this->db->order_by('tc_pesanan.jam_pesanan', 'DESC');
        $result = $this->db->get()->result();
        
        $getList = array();
        foreach($result as $row){
            $getList[] = array(
                    'kodebooking' => $row->id_tc_pesanan,
                    'tanggaloperasi' => $this->tanggal->sqlDateTimeToDate($row->jam_pesanan),
                    'jenistindakan' => $row->nama_tarif,
                    'kodepoli' => $row->kode_poli_bpjs,
                    'namapoli' => $row->nama_bagian,
                    'terlaksana' => ($row->tgl_masuk == NULL) ? 0 : 1,
            );
        }
       $message = (count($getList) == 0) ? 'Tidak ada data ditemukan' : 'Sukses' ;
        // response
        $response = array(
            'response' => array(
                'list' => $getList
            ),
            'metadata' => array(
                'code' => 200,
                'message' => $message,
                ),
        );

        echo json_encode($response);
    }

    public function create_kode_booking($nokartu, $tgl_periksa){
        $string = $nokartu.$tgl_periksa.'abcdefghijklmnpqrstuvwxyz';
        $clean_string = str_replace(array('1','i','0','o','/','-'),'',$string);
        $s = substr(str_shuffle(str_repeat($clean_string, 6)), 0, 6);
        return strtoupper($s);

    }

    public function getKodeInternalPoli($kodepoli){
        
        $kode_poli = $this->db->get_where('mt_bagian', array('kode_poli_bpjs' => $kodepoli) )->row();
        if( empty($kode_poli) ){
            $response = array(
                'metadata' => array(
                    'code' => 300,
                    'message' => 'Kode Poli/Klinik belum terdaftar di RS. Setia Mitra',
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

