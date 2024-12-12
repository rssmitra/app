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

    public function getMedicalExam(){

        $content = file_get_contents("php://input");
        $post = json_decode($content);
        // check token
        $checkToken = $this->checkToken($this->input->request_headers());

        $year = date('Y') - 1;
		$no_mr = (string)$post->nomr;

        // file emr pasien

        // file emr pasien
		$emr = $this->db->select('csm_dokumen_export.*, tc_kunjungan.no_mr, tc_kunjungan.no_kunjungan')
        ->join('pm_tc_penunjang', 'pm_tc_penunjang.kode_penunjang=csm_dokumen_export.kode_penunjang', 'left')
        ->join('tc_kunjungan', 'tc_kunjungan.no_kunjungan=pm_tc_penunjang.no_kunjungan', 'left')
        ->get_where('csm_dokumen_export', array('tc_kunjungan.no_mr' => $no_mr))->result();

        $getDataFile = [];
        foreach ($emr as $key_file => $val_file) {
            $getDataFile[$val_file->kode_penunjang][] = [
                'filename' => $val_file->csm_dex_nama_dok,
                'fileurl' => $val_file->base_url_dok.''.$val_file->csm_dex_fullpath,
            ];
        }

        // penunjang medis
        if( ! $penunjang = $this->cache->get('rm_penunjang_medis_'.$no_mr.'_'.date('Y-m-d').'') )
		{
			$this->db->select('tc_kunjungan.no_kunjungan,tc_kunjungan.no_mr,tc_kunjungan.no_registrasi,mt_karyawan.nama_pegawai as dokter, asal.nama_bagian as asal_bagian, tujuan.nama_bagian as tujuan_bagian, mt_master_pasien.nama_pasien, tc_kunjungan.tgl_masuk, tc_kunjungan.tgl_keluar,status_isihasil,kode_penunjang,pm_tc_penunjang.flag_mcu, status_daftar, kode_bagian_tujuan, flag_mcu');
			$this->db->select('tgl_daftar, tgl_isihasil, tgl_periksa');
			$this->db->select("CAST((
				SELECT '|' + nama_tindakan
				FROM tc_trans_pelayanan
				LEFT JOIN pm_tc_penunjang n ON n.no_kunjungan=tc_trans_pelayanan.no_kunjungan
				LEFT JOIN tc_kunjungan s ON s.no_kunjungan=n.no_kunjungan
				WHERE s.no_kunjungan = tc_kunjungan.no_kunjungan AND n.kode_penunjang = pm_tc_penunjang.kode_penunjang
				FOR XML PATH(''))as varchar(max)) as nama_tarif");
			$this->db->from('tc_kunjungan');
			$this->db->join('mt_master_pasien','mt_master_pasien.no_mr=tc_kunjungan.no_mr','left');
			$this->db->join('mt_karyawan','mt_karyawan.kode_dokter=tc_kunjungan.kode_dokter','left');
			$this->db->join('mt_bagian as asal','asal.kode_bagian=tc_kunjungan.kode_bagian_asal','left');
			$this->db->join('mt_bagian as tujuan','tujuan.kode_bagian=tc_kunjungan.kode_bagian_tujuan','left');
			$this->db->join('pm_tc_penunjang','pm_tc_penunjang.no_kunjungan=tc_kunjungan.no_kunjungan','left');
			$this->db->where('tc_kunjungan.no_mr', $no_mr);
			$this->db->where('tgl_isihasil is not null');
			$this->db->where('DATEDIFF(year,tgl_masuk,GETDATE()) < 2 ');
			$this->db->where('SUBSTRING(kode_bagian_tujuan, 1, 2) =', '05');
			$this->db->order_by('tgl_masuk', 'DESC');
			$penunjang = $this->db->get()->result();
			$this->cache->save('rm_penunjang_medis_'.$no_mr.'_'.date('Y-m-d').'', $penunjang, 3600);
		}

        $getDataPenunjang = [];
        foreach ($penunjang as $key => $value) {
            $exp = explode("|", $value->nama_tarif);
            $pemeriksaan = array_diff(array_unique($exp),array(''));
            // lampiran file
            $getFile = isset($getDataFile[$value->kode_penunjang])?$getDataFile[$value->kode_penunjang]:'';
            $getDataPenunjang[] = [
                'no_registrasi' => $value->no_registrasi,
                'no_kunjungan' => $value->no_kunjungan,
                'kode_penunjang' => $value->kode_penunjang,
                'kode_unit' => $value->kode_bagian_tujuan,
                'no_mr' => $value->no_mr,
                'tanggal_daftar' => $value->tgl_daftar,
                'jenis_penunjang' => $value->tujuan_bagian,
                'jenis_pemeriksaan' => $pemeriksaan,
                'flag_mcu' => $value->flag_mcu,
                'lampiran_hasil' => $getFile,
            ];
        }
        

        if(count($getDataPenunjang) == 0){
            $response = array(
                'response' => array(
                    'code' => 202,
                    'message' => 'Data Pemeriksaan Penunjang Medis Pasien tidak ditemukan',
                    ),
            );
            echo json_encode($response);
            exit;
        }else{
            $response = array(
                'response' => array(
                    'code' => 200,
                    'message' => 'Sukses',
                    'metadata' => $getDataPenunjang,
                    ),
            );
            echo json_encode($response);
            exit;
        }

    }

    public function getMedicalExamResult(){
        
        $content = file_get_contents("php://input");
        $post = json_decode($content);
        // check token
        $checkToken = $this->checkToken($this->input->request_headers());
        // define variabel
        $no_registrasi = $post->no_registrasi;
        $no_kunjungan = $post->no_kunjungan;
        $kode_penunjang = $post->kode_penunjang;
        $kode_bagian_pm = $post->kode_unit;
        $flag_mcu = $post->flag_mcu;

        // get hasil penunjang
        if($flag_mcu==''){
            $table = 'pm_hasilpasien_v as a';
            $where = 'a.kode_trans_pelayanan IN (SELECT kode_trans_pelayanan FROM tc_trans_pelayanan WHERE kode_penunjang='.$kode_penunjang.')';
        }else{
            $table = 'mcu_hasilpasien_pm_v as a';
            $where = 'a.kode_trans_pelayanan IN (SELECT kode_trans_pelayanan_paket_mcu FROM tc_trans_pelayanan_paket_mcu WHERE kode_penunjang='.$kode_penunjang.')';
        }
        $this->db->select("a.kode_trans_pelayanan, a.kode_tarif, a.nama_pemeriksaan, REPLACE(a.nama_tindakan, 'BPJS' , '') as nama_tindakan, a.hasil, a.standar_hasil_pria, a.standar_hasil_wanita, a.satuan, a.keterangan, a.detail_item_1, a.detail_item_2,b.referensi,d.urutan");
        $this->db->from($table);
        $this->db->join('mt_master_tarif b', 'a.kode_tarif=b.kode_tarif', 'left');
        $this->db->join('pm_mt_standarhasil d', 'a.kode_mt_hasilpm=d.kode_mt_hasilpm', 'left');
        $this->db->where($where);
        $this->db->where(' a.hasil != '."''".' ');
        $this->db->group_by('a.kode_tarif, a.nama_pemeriksaan,a.nama_tindakan, a.hasil, a.standar_hasil_pria, a.standar_hasil_wanita, a.satuan, a.keterangan, a.detail_item_1, a.detail_item_2,b.referensi,d.urutan, a.kode_trans_pelayanan');
        $this->db->order_by('d.urutan ASC');
        $result_pm = $this->db->get()->result();

        $getTindakan = [];
        foreach($result_pm as $row_pm){
            $getTindakan[$row_pm->kode_trans_pelayanan][$row_pm->nama_tindakan][] = [
                'urutan' => $row_pm->urutan,
                'jenis_pemeriksaan' => $row_pm->nama_pemeriksaan,
                'standar_hasil_pria' => $row_pm->standar_hasil_pria,
                'standar_hasil_wanita' => $row_pm->standar_hasil_wanita,
                'satuan' => $row_pm->satuan,
                'hasil' => $row_pm->hasil,
                'keterangan' => $row_pm->keterangan,
            ];
        }

        if(count($getTindakan) == 0){
            $response = array(
                'response' => array(
                    'code' => 202,
                    'message' => 'Data Hasil Pemeriksaan Penunjang Medis Pasien tidak ditemukan',
                    ),
            );
            echo json_encode($response);
            exit;
        }else{
            $response = array(
                'response' => array(
                    'code' => 200,
                    'message' => 'Sukses',
                    'metadata' => $getTindakan,
                    ),
            );
            echo json_encode($response);
            exit;
        }

    }

}

/* End of file empty_module.php */
/* Location: ./application/modules/empty_module/controllers/empty_module.php */

