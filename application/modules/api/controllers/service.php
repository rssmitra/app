<?php
 header("Access-Control-Allow-Origin: *");

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Service extends MX_Controller {

    function __construct(){

        date_default_timezone_set("Asia/Jakarta");
        // Construct the parent class
        // header('Access-Control-Allow-Origin: *');
        // header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        // header('Content-Type: application/json');
        parent::__construct();
        // load model
        $this->load->model('ws_bpjs/Ws_index_model', 'Ws_index');
        $this->load->model('ws/AntrianOnlineModel', 'AntrianOnline');
        $this->load->model('booking/Regon_booking_model', 'Regon_booking');
        $this->load->model('registration/Reg_pasien_model', 'Reg_pasien');
        // load module
        $this->load->module('templates/References');

        $this->load->library('Daftar_pasien');
        $this->load->library('Print_direct');
        $this->load->library('Print_escpos');  

        // default username and password
        $this->username = 'AppsWebService';
        $this->password = 'P@s5W0rdR55m!@#';
        $this->kode_faskses = '0112R034';
        
    }

    public function getToken() {
       
        $post = new StdClass;
        $post->username = $this->input->request_headers()['x-username'];
        $post->password = $this->input->request_headers()['x-password'];

        
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
                    'code' => 201,
                    'message' => 'Username dan Password yang anda masukan salah!',
                    )
            );
            echo json_encode($response);
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

    public function checkToken($header){
        # code...
        $concat = $this->username.'/'.$this->password;
        $tokenCurrent = md5($concat);
        $tokenSent = $this->input->request_headers()['x-token'];
        $usernameSent = $this->input->request_headers()['x-username'];

        // check username
        if ($usernameSent != $this->username) {
            $response = array(
                'metadata' => array(
                    'code' => 201,
                    'message' => 'Incorrect username !',
                    ),
            );
            echo json_encode($response); exit;
        }


        if ($tokenSent != $tokenCurrent) {
            $response = array(
                'metadata' => array(
                    'code' => 201,
                    'message' => 'Incorrect token !',
                    ),
            );
            echo json_encode($response); exit;
        }

        return $tokenSent;
    }

    public function getPatient($field, $key){

        $content = file_get_contents("php://input");
        $post = json_decode($content);
        
        // check token
        $checkToken = $this->checkToken($this->input->request_headers());

        if(!in_array($field, ['nomr', 'nik', 'name', 'nobpjs'])){
            $response = array(
                'metadata' => array(
                    'code' => 202,
                    'message' => 'Parameter data tidak ditemukan, gunakan parameter [nomr/nik/name]',
                    ),
            );
            echo json_encode($response);
            exit;
        }

        if($field == 'nomr'){
            $this->db->where('no_mr', $key);
        }
        if($field == 'nik'){
            $this->db->where('no_ktp', $key);
        }
        if($field == 'name'){
            $this->db->like('nama_pasien', $key);
        }
        if($field == 'nobpjs'){
            $this->db->where('no_kartu_bpjs', $key);
        }

        $this->db->from('mt_master_pasien');
        $result = $this->db->get();
        
        if($result->num_rows() == 0){
            $response = array(
                'response' => array(
                    'code' => 202,
                    'message' => 'Data pasien tidak ditemukan',
                    ),
            );
            echo json_encode($response);
            exit;
        }else{
            $pasien = $result->result();
            foreach($pasien as $row){
                $data_pasien[] = [
                    'no_mr' => $row->no_mr,
                    'nik' => $row->no_ktp,
                    'title' => $row->title,
                    'nama_pasien' => $row->nama_pasien,
                    'tmt_lhr' => $row->tempat_lahir,
                    'tgl_lhr' => $this->tanggal->formatDateTimeToSqlDate($row->tgl_lhr),
                    'jk' => $row->jen_kelamin,
                    'alamat' => $row->almt_ttp_pasien,
                    'no_tlp' => $row->tlp_almt_ttp,
                    'no_hp' => $row->no_hp,
                    'no_kartu_bpjs' => $row->no_kartu_bpjs,
                ];
            }
            
            $response = array(
                'response' => array(
                    'code' => 200,
                    'message' => 'Sukses',
                    'metadata' => $data_pasien,
                    ),
            );
            echo json_encode($response);
            exit;
        }

    }

    public function getMedicalRecord(){

        $content = file_get_contents("php://input");
        $post = json_decode($content);
        // check token
        $checkToken = $this->checkToken($this->input->request_headers());

        $year = date('Y') - 1;
		$no_mr = (string)$post->nomr;
        $limit = isset($post->limit)?$post->limit:20;
        // riwayat medis
        $this->db->from('view_cppt');
        $this->db->where('no_mr', $no_mr);
        $this->db->where('jenis_form', 0);
        $this->db->where('YEAR(tanggal) >=', $year);
        $this->db->limit($limit);
        $riwayat_medis = $this->db->get()->result();

        // file emr pasien
		$emr = $this->db->select('csm_dokumen_export.*, tc_kunjungan.no_mr, tc_kunjungan.no_kunjungan')->join('tc_kunjungan', 'tc_kunjungan.no_registrasi=csm_dokumen_export.no_registrasi', 'left')->get_where('csm_dokumen_export', array('tc_kunjungan.no_mr' => $no_mr))->result();
		$getDataFile = [];
		foreach ($emr as $key_file => $val_file) {
			$getDataFile[$val_file->no_kunjungan][] = [
                'filename' => $val_file->csm_dex_nama_dok,
                'fileurl' => $val_file->base_url_dok.''.$val_file->csm_dex_fullpath,
            ];
		}

        // eresep
		$eresep = $this->db->get_where('fr_tc_pesan_resep_detail', ['no_mr' => $no_mr])->result();
        foreach ($eresep as $key => $val_resep) {
            if($val_resep->tipe_obat == 'racikan'){
                // get child
                $getChildRacikan[$val_resep->parent][] = [
                    'nama_obat' => $val_resep->nama_brg,
                    'dosis' => $val_resep->jml_pesan,
                    'satuan' => $val_resep->satuan_obat,
                ];
            }
        }

        foreach ($eresep as $key => $val_resep) {
            if($val_resep->parent == 0){
                // get resep
                $child_racikan = [];
                if($val_resep->tipe_obat == 'racikan'){
                    if(isset($getChildRacikan[$val_resep->kode_brg])){
                        $child_racikan = $getChildRacikan[$val_resep->kode_brg];
                    }
                }
                $getResep[$val_resep->no_kunjungan][] = 
                    [
                        'nama_obat' => $val_resep->nama_brg,
                        'dosis' => $val_resep->jml_dosis.'x'.$val_resep->jml_dosis_obat.' '.$val_resep->aturan_pakai,
                        'qty' => $val_resep->jml_pesan,
                        'satuan' => $val_resep->satuan_obat,
                        'keterangan' => $val_resep->keterangan,
                        'komposisi_racikan' => $child_racikan
                    ];
            }
        }

        foreach($riwayat_medis as $row_rm){
            // resep obat
            $resep = isset($getResep[$row_rm->no_kunjungan])?$getResep[$row_rm->no_kunjungan]:'';
            $file_mr = isset($getDataFile[$row_rm->no_kunjungan])?$getDataFile[$row_rm->no_kunjungan]:'';
            $getDataRm[] = [
                'no_registrasi' => $row_rm->no_registrasi,
                'no_kunjungan' => $row_rm->no_kunjungan,
                'no_mr' => $row_rm->no_mr,
                'tanggal' => $row_rm->tanggal,
                'tipe_layan' => $row_rm->tipe,
                'jenis_mr' => $row_rm->jenis_pengkajian,
                'flag_mr' => $row_rm->flag,
                'ppa' => $row_rm->ppa,
                'nama_ppa' => $row_rm->nama_ppa,
                'subjective' => $row_rm->subjective,
                'objective' => $row_rm->objective,
                'assesment' => $row_rm->assesment,
                'diagnosa_sekunder' => $row_rm->diagnosa_sekunder,
                'planning' => $row_rm->planning,
                'td' => $row_rm->tekanan_darah,
                'tb' => $row_rm->tinggi_badan,
                'bb' => $row_rm->berat_badan,
                'suhu' => $row_rm->suhu,
                'nadi' => $row_rm->nadi,
                'resep_obat' => $resep,
                'files' => $file_mr,
            ];
        }
        
        if(count($getDataRm) == 0){
            $response = array(
                'response' => array(
                    'code' => 202,
                    'message' => 'Data Medical Record Pasien tidak ditemukan',
                    ),
            );
            echo json_encode($response);
            exit;
        }else{
            $response = array(
                'response' => array(
                    'code' => 200,
                    'message' => 'Sukses',
                    'metadata' => $getDataRm,
                    ),
            );
            echo json_encode($response);
            exit;
        }

    }

    public function get_riwayat_medis($mr){
		
		$year = date('Y') - 1;
		$no_mr = (string)$mr;

        $this->db->from('view_cppt');

		// resume medis pasien
		$limit = isset($_GET['key'])?$_GET['key']:20;
		$result = $this->db->select('th_riwayat_pasien.*, mt_bagian.nama_bagian, tc_kunjungan.no_kunjungan as status_kunjungan, tc_kunjungan.cara_keluar_pasien')->join('tc_kunjungan', 'tc_kunjungan.no_kunjungan = th_riwayat_pasien.no_kunjungan', 'left')->join('mt_bagian', 'mt_bagian.kode_bagian=th_riwayat_pasien.kode_bagian','left')->order_by('no_kunjungan','DESC')->where_in('SUBSTRING(th_riwayat_pasien.kode_bagian, 1,2)', ['01','02'])->where('DATEDIFF(year,tgl_periksa,GETDATE()) < 2 ')->limit($limit)->get_where('th_riwayat_pasien', array('th_riwayat_pasien.no_mr' => $no_mr))->result(); 
		// echo '<pre>';print_r($result);die;

		// eresep
		$eresep = $this->db->get_where('fr_tc_pesan_resep_detail', ['no_mr' => $no_mr, 'parent' => '0'])->result();

		// file emr pasien
		$emr = $this->db->select('csm_dokumen_export.*, tc_kunjungan.no_mr, tc_kunjungan.no_kunjungan')->join('tc_kunjungan', 'tc_kunjungan.no_registrasi=csm_dokumen_export.no_registrasi', 'left')->get_where('csm_dokumen_export', array('tc_kunjungan.no_mr' => $no_mr))->result();
		$getDataFile = [];
		foreach ($emr as $key_file => $val_file) {
			$getDataFile[$val_file->no_registrasi][$val_file->no_kunjungan][] = $val_file;
		}


		// form pengkajian pasien / form rekam medis
		$file_pengkajian = $this->db->get_where('view_cppt', array('view_cppt.no_mr' => $no_mr, 'jenis_form !=' => 0))->result();
		$getDataFilePengkajian = [];
		foreach ($file_pengkajian as $key_file_pkj => $val_file_pkj) {
			$getDataFilePengkajian[$val_file_pkj->no_registrasi][$val_file_pkj->no_kunjungan][] = $val_file_pkj;
		}

		$getDataResep = [];
		foreach ($eresep as $key_resep => $value_resep) {
			$getDataResep[$value_resep->no_registrasi][$value_resep->no_kunjungan][$value_resep->kode_pesan_resep][] = $value_resep;
		}

		$data = array(
			'file' => $getDataFile,
			'file_pkj' => $getDataFilePengkajian,
			'result' => $result,
			'eresep' => $getDataResep,
			'no_mr' => $no_mr,
			
		);

		return $data;
	}







}

/* End of file empty_module.php */
/* Location: ./application/modules/empty_module/controllers/empty_module.php */

