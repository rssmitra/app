<?php
require_once 'vendor/autoload.php';
defined('BASEPATH') OR exit('No direct script access allowed');

class Ws_index_v2_model extends CI_Model {

	/*production*/
	// var $consID 		= "17971";
	// var $secretKey 		= "H9139FJ385";
	// var $base_api_url	= "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest";

	var $consID 		= "12973";
	var $secretKey 		= "4aT7357418xxChanged";
	var $user_key 		= "0dd67b5d8c5863dadb0cb9a6cf266e98";
	var $base_api_url	= "https://apijkn-dev.bpjs-kesehatan.go.id/vclaim-rest-dev";

	// aplicare
	// var $base_api_url_applicare = "http://api.bpjs-kesehatan.go.id/aplicaresws";
	var $base_api_url_applicare = "https://new-api.bpjs-kesehatan.go.id/aplicaresws";



	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function getSignatureHeader(){

		$stamp		= time();
		$data 		= $this->consID.'&'.$stamp;
		
		$signature = hash_hmac('sha256', $data, $this->secretKey, true);
		$encodedSignature = base64_encode($signature);	
		$headers = array( 
	            /*"Accept: application/json", */
	            "X-cons-id: ".$this->consID, 
	            "X-timestamp: ".$stamp, 
	            "X-signature: ".$encodedSignature,
	            "user-key: ".$this->user_key,
	            // 'Content-Type: application/x-www-form-urlencoded'
	        ); 
		// echo '<pre>';print_r($headers);die;
		return $headers;

	}

	function getSignatureHeaderApplicare(){

		$stamp		= time();
		$data 		= $this->consID.'&'.$stamp;
		
		$signature = hash_hmac('sha256', $data, $this->secretKey, true);
		$encodedSignature = base64_encode($signature);	
		$headers = array( 
	            /*"Accept: application/json", */
	            "X-cons-id:".$this->consID, 
	            "X-timestamp: ".$stamp, 
	            "X-signature: ".$encodedSignature,
	            'Content-Type: application/json'
	        ); 
		// echo '<pre>';print_r($headers);die;
		return $headers;

	}

	function getData($service_name){

		$header = $this->getSignatureHeader();
		$key = $this->consID.$this->secretKey.time();
		$uri = $this->base_api_url.'/'.$service_name;
		$ch = curl_init($uri);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header); 
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
		$data = curl_exec($ch);
		$result = json_decode($data);
		// echo '<pre>';print_r($result);die;
		// decrypt
		$strdecrpt = $this->stringDecrypt($key, $result->response);
		$decompress = $this->decompress($strdecrpt);
		$getData = array(
			'response' => $result,
			'data' => json_decode($decompress),
		);
		curl_close($ch);
		return $getData;
		

	}

	function postDataWs($service_name, $post_data='', $method=''){

		$uri = $this->base_api_url.'/'.$service_name;

		$json = json_encode($post_data); 
		
		$c = curl_init();

		curl_setopt($c, CURLOPT_URL, $uri);
		$certificate_location = 'assets/cacert.pem';
		curl_setopt($c, CURLOPT_SSL_VERIFYHOST, $certificate_location);
		curl_setopt($c, CURLOPT_SSL_VERIFYPEER, $certificate_location);

		if($method!=''){
		    curl_setopt($c, CURLOPT_CUSTOMREQUEST, "$method");
		}
		curl_setopt($c, CURLOPT_VERBOSE, true);
		curl_setopt($c, CURLOPT_TIMEOUT, 30);
		curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
		$headers = $this->getSignatureHeader();
		curl_setopt($c, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($c, CURLOPT_POST, true);
		curl_setopt($c, CURLOPT_POSTFIELDS, $json);

		$result = curl_exec($c);
		// print_r(curl_error($c));die;
		curl_close($c); 
		return json_decode($result);

	}

	function postDataWsApplicare($service_name, $post_data='', $method=''){

		$uri = $this->base_api_url_applicare.'/'.$service_name;
		
		$json = json_encode($post_data); 
		
		$c = curl_init();

		curl_setopt($c, CURLOPT_URL, $uri);
		$certificate_location = 'assets/cacert.pem';
		curl_setopt($c, CURLOPT_SSL_VERIFYHOST, $certificate_location);
		curl_setopt($c, CURLOPT_SSL_VERIFYPEER, $certificate_location);
		if($method!=''){
		    curl_setopt($c, CURLOPT_CUSTOMREQUEST, "$method");
		}
		curl_setopt($c, CURLOPT_VERBOSE, true);
		curl_setopt($c, CURLOPT_TIMEOUT, 30);
		curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
		$headers = $this->getSignatureHeaderApplicare();
		curl_setopt($c, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($c, CURLOPT_POST, true);
		curl_setopt($c, CURLOPT_POSTFIELDS, $json);

		$result = curl_exec($c);
		// echo 'Curl error: ' . curl_error($c);
		// print_r($result);die;
		curl_close($c); 
		return json_decode($result);

	}

	/*referensi module*/
	function refDiagnosa(){
		$service_name = "referensi/diagnosa/".$_POST['keyword'];
		$result = $this->getData($service_name); 
		// echo '<pre>';print_r($result);die;
		if($result['response']->metaData->code==200){
			foreach ($result['data']->diagnosa as $key => $value) {
				$arrResult[] = $value->kode.' : '.$value->nama;
			}
			return $arrResult;
		}
	}

	function refPoli(){
		$service_name = "referensi/poli/".$_POST['keyword'];
		$result = $this->getData($service_name);
		if($result['response']->metaData->code==200){
			foreach ($result['data']->poli as $key => $value) {
				$arrResult[] = $value->kode.' : '.$value->nama;
			}
			return $arrResult;
		}
	}

	function refFaskes(){
		$service_name = "referensi/faskes/".$_POST['keyword']."/".$_POST['jf'];
		$result = $this->getData($service_name);
		if($result['response']->metaData->code==200){
			foreach ($result['data']->faskes as $key => $value) {
				$arrResult[] = $value->kode.' : '.$value->nama;
			}
			return $arrResult;
		}
	}

	function refProcedure(){
		$service_name = "referensi/procedure/".$_POST['keyword'];
		$result = $this->getData($service_name);
		if($result['response']->metaData->code==200){
			foreach ($result['data']->procedure as $key => $value) {
				$arrResult[] = $value->nama;
			}
			return $arrResult;
		}
	}

	function refDokter(){
		$service_name = "referensi/dokter/".$_POST['keyword'];
		$result = $this->getData($service_name);
		if($result['response']->metaData->code==200){
			foreach ($result['data']->list as $key => $value) {
				$arrResult[] = ''.$value->kode.' : '.$value->nama;
			}
			return $arrResult;
		}
	}

	function RefDokterDPJP(){
		$service_name = "referensi/dokter/pelayanan/".$_POST['jp']."/tglPelayanan/".$this->tanggal->sqlDateForm($_POST['tgl'])."/Spesialis/".$_POST['spesialis'];
		$result = $this->getData($service_name);
		// print_r($result);die;
		if($result['response']->metaData->code==200){
			foreach ($result['data']->list as $key => $value) {
				$arrResult[] = ''.$value->kode.' : '.$value->nama;
			}
			return $arrResult;
		}
	}

	function GetRefDokterDPJPRandom(){
		$service_name = "referensi/dokter/pelayanan/".$_GET['jp']."/tglPelayanan/".$this->tanggal->sqlDateForm($_GET['tgl'])."/Spesialis/".$_GET['spesialis'];
		$result = $this->getData($service_name);
		// echo '<pre>';print_r($result->response);die;
		if($result['response']->metaData->code==200){
			shuffle($result['data']->list);
			$row = $result['data']->list;
			return $row[0];
		}
	}

	function refKelasRawat(){
		$service_name = "referensi/kelasrawat";
		$result = $this->getData($service_name);
		if($result['response']->metaData->code==200){
			return $result['data']->list;
		}
	}

	function refSpesialistik(){
		$service_name = "referensi/spesialistik";
		$result = $this->getData($service_name);
		if($result['response']->metaData->code==200){
			return $result['data']->list;
		}
	}

	function refRuangRawat(){
		$service_name = "referensi/ruangrawat";
		$result = $this->getData($service_name);
		if($result['response']->metaData->code==200){
			return $result['data']->list;
		}
	}

	function refCaraKeluar(){
		$service_name = "referensi/carakeluar";
		$result = $this->getData($service_name);
		if($result['response']->metaData->code==200){
			return $result['data']->list;
		}
	}

	function refPascaPulang(){
		$service_name = "referensi/pascapulang";
		$result = $this->getData($service_name);
		if($result['response']->metaData->code==200){
			return $result['data']->list;
		}
	}

	/*kepesertaan*/
	function searchMember(){
		if($_POST['jenis_kartu']=='bpjs'){
			$service_name = "Peserta/nokartu/".$_POST['nokartu']."/tglSEP/".$this->tanggal->sqlDateForm($_POST['tglSEP'])."";
		}else{
			$service_name = "Peserta/nik/".$_POST['nokartu']."/tglSEP/".$this->tanggal->sqlDateForm($_POST['tglSEP'])."";
		}
		// print_r($service_name);die;
		return $this->getData($service_name);
	}

	function searchMemberByNIK($nik, $tgl_periksa){
		$service_name = "Peserta/nik/".$nik."/tglSEP/".$tgl_periksa."";
		// print_r($service_name);die;
		return $this->getData($service_name);
	}

	function searchMemberByNomorKartu($no_kartu, $tgl_periksa){
		$service_name = "Peserta/nokartu/".$no_kartu."/tglSEP/".$tgl_periksa."";
		// print_r($service_name);die;
		return $this->getData($service_name);
	}

	/*rujukan*/
	function searchRujukan(){

		/*puskesmas*/
		if($_POST['jenis_faskes']=='1') {

			if($_POST['flag']=='noRujukan'){
				$service_name = "Rujukan/".$_POST['noRujukan'];
			}else{
				$service_name = "Rujukan/List/Peserta/".$_POST['nokartu'];
			}

		/*ini untuk rs*/
		}else{

			if($_POST['flag']=='noRujukan'){
				$service_name = "Rujukan/RS/".$_POST['noRujukan'];
			}else{
				$service_name = "Rujukan/RS/List/Peserta/".$_POST['nokartu'];
			}

		}
				
		return $this->getData($service_name);
	}

	function searchRujukanRsByNomorRujukan($no_rujukan){

		/*rs*/
		$service_name = "Rujukan/".$no_rujukan;
				
		return $this->getData($service_name);
	}
	

	function insertRujukan($request){
		$service_name = "Rujukan/insert";
		return $this->postDataWs($service_name, $request);
	}

	function updateRuangan($request, $kodeppk){
		$service_name = "rest/bed/update/".$kodeppk."";
		// print_r($service_name);die;
		return $this->postDataWsApplicare($service_name, $request);
	}

	function deleteRuangan($request, $kodeppk){
		$service_name = "rest/bed/delete/".$kodeppk."";
		// print_r($service_name);die;
		return $this->postDataWsApplicare($service_name, $request);
	}


	/*ketersediaan kamar*/
	function daftarRuanganRs($kode_ppk='0112R034', $start_data=1, $limit=10){
		$service_name = "aplicaresws/rest/bed/read/".$kode_ppk."/".$start_data."/".$limit."";
		$result = $this->getData($service_name);
// 
		if($result['response']->metaData->code==200){
			return $result['data']->list;
		}
	}

	/*monitoring*/

	function get_datatables_kunjungan()
    {
    	$tglSep = isset($_GET['tglSep'])?$_GET['tglSep']:date('m/d/Y');
    	$jnsPelayanan = isset($_GET['jnsPelayanan'])?$_GET['jnsPelayanan']:2;
    	$service_name = "Monitoring/Kunjungan/Tanggal/".$this->tanggal->sqlDateForm($tglSep)."/JnsPelayanan/".$jnsPelayanan."";
		$result = $this->getData($service_name);
		// echo '<pre>';print_r($result);die;
		return $result;
    }

    /*SEP*/
    function insertSep($request){
		$service_name = "SEP/1.1/insert";
		return $this->postDataWs($service_name, $request);
	}

	function updateSep($request){
		$service_name = "SEP/1.1/Update";
		return $this->postDataWs($service_name, $request,"PUT");
	}

	function deleteSep($no_sep, $request){
		$service_name = "SEP/Delete";
		$method = "DELETE";
		return $this->postDataWs($service_name, $request, $method);
	}

	function updateTglPulang($request){
		$service_name = "Sep/updtglplg";
		return $this->postDataWs($service_name, $request,"PUT");
	}

	function pengajuanSEP($request){
		$service_name = "Sep/pengajuanSEP";
		return $this->postDataWs($service_name, $request,"POST");
	}

	function approvalSep($request){
		$service_name = "Sep/aprovalSEP";
		return $this->postDataWs($service_name, $request,"POST");
	}

	function deleteSepLocal($no_sep){
		return $this->db->delete('ws_bpjs_sep', array('noSep' => $no_sep) );
	}


	function findSep($no_sep){
		$service_name = "SEP/".$no_sep;
		$result = $this->getData($service_name);
		// echo '<pre>';print_r($result);die;
			return $result;
		// if($result['response']->metaData->code==200){
		// 	return $result;
		// }
	}

	function findSepFromLocal($no_sep){
		try {
		    $result = $this->db->get_where('ws_bpjs_sep', array('noSep' => $no_sep))->row();
		    $code = 200;
		    $message = 'Data berhasil diproses';			
		} catch (Exception $e) {
			$code = 201;
			$message = $e->getMessage();
			$result = new stdClass;
		}
		$data = new stdClass;
		$data->metaData = new stdClass;
		$data->metaData->code = $code;
		$data->metaData->message = $message;
		$data->response = $result;
		return $data;
	}


	function insert_tbl_sep($table, $data){
		/*cek dulu existing data*/
		$count = $this->db->get_where('ws_bpjs_sep', array('noSep' => $data['noSep']))->num_rows();
	 	if( $count > 0){
	 		/*update*/
	 		$this->db->update($table, $data, array('noSep' => $data['noSep']));
	 	    return true;
	 	}else{
	 		/*insert*/
	 	    $this->db->insert($table, $data);
	 	    return $this->db->insert_id();
	 	}
	}

	function get_data_sep($no_sep){
		$data = $this->findSep($no_sep);
		// $data = $this->db->get_where('ws_bpjs_sep', array('noSep' => $sep) )->row();
		return $data;
	}

	function count_sep_by_day(){
		$count = $this->db->where( 'tglSep=CAST(CURRENT_TIMESTAMP AS DATE)')->get('ws_bpjs_sep')->num_rows();
		return $count;
	}

	/*history SEP*/
	function get_datatables_history_sep()
	{
		$this->_get_datatables_query_history_sep();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	private function _get_datatables_query_history_sep()
	{
		
		$this->_main_query_history_sep();

		$i = 0;
		$reff_column = array('nama','hakKelas','jnsPeserta','kelamin','noKartu','asuransi','noTelp','noMr','tglLahir','kodePPPKPerujuk','PPKPerujuk','asalRujukan','noRujukan','tglRujukan','catatan','kodeDiagnosa','diagnosa','kodeJnsPelayanan','jnsPelayanan','kodeKelasRawat','kelasRawat','noSep','kodePoli','poli','tglSep','created_by','updated_by');

		$reff_order = array('noSep' => 'ASC');

		foreach ($reff_column as $item) 
		{
			if($_POST['search']['value'])
				($i===0) ? $this->db->like($item, $_POST['search']['value']) : $this->db->or_like($item, $_POST['search']['value']);
			$column[$i] = $item;
			$i++;
		}
		
		if(isset($_POST['order']))
		{
			$this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($reff_order))
		{
			$order = $reff_order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	private function _main_query_history_sep(){
		$this->db->select('ws_bpjs_sep.*');
		$this->db->from('ws_bpjs_sep');
	}

	function count_filtered_history_sep()
	{
		$this->_get_datatables_query_history_sep();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all_history_sep()
	{
		$this->_main_query_history_sep();
		return $this->db->count_all_results();
	}

	public function updateStatusSep($data, $sep)
	{
		return $this->db->update('ws_bpjs_sep', $data, array('noSep' => $sep));
	}

	/*ketersediaan ruangan*/

	/*history SEP*/
	function get_datatables_ruangan_rs()
	{
		$this->_get_datatables_query_ruangan_rs();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		// print_r($this->db->last_query());die;
		return $query->result();
	}

	private function _get_datatables_query_ruangan_rs()
	{
		
		$this->_main_query_ruangan_rs();

		$i = 0;
		$reff_column = array('a.kode_ruangan', 'a.kode_bagian' , 'b.nama_bagian', 'c.kode_klas_bpjs','c.nama_klas_bpjs','c.kode_klas' , 'c.nama_klas', 'a.no_kamar', 'a.no_bed', 'a.status', 'a.keterangan', 'a.gender', '');

		$reff_order = array('nama_bagian' => 'ASC');

		foreach ($reff_column as $item) 
		{
			if($_POST['search']['value'])
				($i===0) ? $this->db->like($item, $_POST['search']['value']) : $this->db->or_like($item, $_POST['search']['value']);
			$column[$i] = $item;
			$i++;
		}
		
		if(isset($_POST['order']))
		{
			$this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($reff_order))
		{
			$order = $reff_order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function get_data_ruangan(){
		$this->_main_query_ruangan_rs();
		$query = $this->db->get();
		// print_r($this->db->last_query());die;
		return $query;
	}

	function countRuangan($kode_bagian, $kode_klas){
		$this->_main_query_ruangan_rs();
		$this->db->where('a.kode_bagian', $kode_bagian);
		$this->db->where('c.kode_klas_bpjs', $kode_klas);
		$this->db->where('a.flag_cad', 0);
		// $this->db->where("(a.status !='ISI' OR a.status IS NULL)");
		$query = $this->db->get()->result();
		$kamarTerisi = array();
		$kamarTersedia = array();
		$pria = array();
		$wanita = array();
		$priawanita = array();
		foreach($query as $row){

			if($row->status == 'ISI'){
				$kamarTerisi[] = $row;
			}else{
				$kamarTersedia[] = $row;
			}

			if($row->gender == 3 || $row->gender == NULL){
				$priawanita[] = $row;
			}

			if($row->gender == 1){
				$pria[] = $row;
			}

			if($row->gender == 2){
				$wanita[] = $row;
			}

		}
		$data = array(
			'terisi' => count($kamarTerisi),
			'tersedia' => count($kamarTersedia),
			'kapasitas' => count($query),
			'pria' => count($pria),
			'wanita' => count($wanita),
			'priawanita' => count($priawanita),
		);
		
		return $data;
	}

	private function _main_query_ruangan_rs(){
		$this->db->select('a.kode_ruangan, a.kode_bagian, b.nama_bagian, c.kode_klas_bpjs, c.nama_klas_bpjs, c.kode_klas, c.nama_klas, a.no_kamar, a.no_bed, a.status, a.keterangan, a.gender, e.nama_pasien, e.no_mr, ri.tgl_masuk, f.nama_pegawai as dokter, g.deposit, g.harga_r, g.harga_bpjs, c.kode_klas_dinkes');
		$this->db->from('mt_ruangan a');
		$this->db->join('mt_bagian b','b.kode_bagian=a.kode_bagian','left');
		$this->db->join('mt_klas c','c.kode_klas=a.kode_klas','left');
		$this->db->join('(select * from ri_tc_rawatinap where (ri_tc_rawatinap.status_pulang=0 or ri_tc_rawatinap.status_pulang IS NULL) AND DATEDIFF(day,ri_tc_rawatinap.tgl_masuk,GETDATE()) < 60 ) as ri','ri.kode_ruangan=a.kode_ruangan','left');
		$this->db->join('tc_kunjungan d','d.no_kunjungan=ri.no_kunjungan','left');
		$this->db->join('mt_master_pasien e','e.no_mr=d.no_mr','left');
		$this->db->join('mt_karyawan f','f.kode_dokter=ri.dr_merawat','left');
		$this->db->join('mt_master_tarif_ruangan g','(g.kode_bagian=a.kode_bagian AND g.kode_klas=a.kode_klas)','left');
		$this->db->group_by('a.kode_ruangan, a.kode_bagian, b.nama_bagian, c.kode_klas_bpjs, c.nama_klas_bpjs, c.kode_klas, c.nama_klas, a.no_kamar, a.no_bed, a.status, a.keterangan, a.gender, e.nama_pasien, e.no_mr, ri.tgl_masuk, f.nama_pegawai, g.deposit, g.harga_r, g.harga_bpjs, c.kode_klas_dinkes');
		$this->db->where('a.flag_cad', 0);
	}

	function count_filtered_ruangan_rs()
	{
		$this->_get_datatables_query_ruangan_rs();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all_ruangan_rs()
	{
		$this->_main_query_history_sep();
		return $this->db->count_all_results();
	}

	function getBedData(){
		$this->_main_query_ruangan_rs();
		$this->db->where('a.flag_cad', 0);
		// $this->db->where("(a.status !='ISI' OR a.status IS NULL)");
		$query = $this->db->get()->result();
		$kapasitas = array();
		$kapasitas_kosong = array();
		$kapasitas_by_gender = array();
		$kapasitas_kosong_by_gender = array();
		$kapasitas_icu = array();
		foreach($query as $row){

			if( $row->kode_klas_dinkes != '' ){
				$kapasitas[$row->kode_klas_dinkes][] = count($row);
				if($row->status == NULL){
					$kapasitas_kosong[$row->kode_klas_dinkes] = array();
					$kapasitas_kosong[$row->kode_klas_dinkes][] = count($row);
				}
			}

			if( $row->kode_klas_dinkes != '' ){
				if(in_array($row->gender, array(1,2,3) )){
					$kapasitas_by_gender[$row->kode_klas_dinkes] = array();
					$kapasitas_by_gender[$row->kode_klas_dinkes][$row->gender] = array();
					$kapasitas_by_gender[$row->kode_klas_dinkes][$row->gender][] = count($row);
					if($row->status == NULL){
						$kapasitas_kosong_by_gender[$row->kode_klas_dinkes][$row->gender] = array();
						$kapasitas_kosong_by_gender[$row->kode_klas_dinkes][$row->gender][] = count($row);
					}
				}
			}

			if(in_array($row->kode_klas, array(10,11) )){
				$kapasitas_icu[$row->kode_klas][] = count($row);
				if($row->status == NULL){
					$kapasitas_kosong_icu[$row->kode_klas] = array();
					$kapasitas_kosong_icu[$row->kode_klas][] = count($row);
				}
			}
			
		}
		// echo '<pre>'; print_r($kapasitas);die;

		$fields = array(
			// kapasitas bed
			"kapasitas_vip" => count($kapasitas['vip']),
			"kapasitas_kelas_1" => count($kapasitas['kelas_1']),
			"kapasitas_kelas_2" => count($kapasitas['kelas_2']),
			"kapasitas_kelas_3" => count($kapasitas['kelas_3']),
			"kapasitas_kelas_1_l" => count($kapasitas_by_gender['kelas_1'][1]),
			"kapasitas_kelas_2_l" => count($kapasitas_by_gender['kelas_2'][1]),
			"kapasitas_kelas_3_l" => count($kapasitas_by_gender['kelas_3'][1]),
			"kapasitas_kelas_1_p" => count($kapasitas_by_gender['kelas_1'][2]),
			"kapasitas_kelas_2_p" => count($kapasitas_by_gender['kelas_2'][2]),
			"kapasitas_kelas_3_p" => count($kapasitas_by_gender['kelas_3'][2]),
			"kapasitas_hcu" => count($kapasitas_icu[11]),
			"kapasitas_iccu" => count($kapasitas_icu[10]),
			"kapasitas_icu_negatif_ventilator" => 0,
			"kapasitas_icu_negatif_tanpa_ventilator" => 0,
			"kapasitas_icu_tanpa_negatif_ventilator" => 0,
			"kapasitas_icu_tanpa_negatif_tanpa_ventilator" => 0,
			"kapasitas_icu_covid_negatif_ventilator" => 0,
			"kapasitas_icu_covid_negatif_tanpa_ventilator" => 0,
			"kapasitas_icu_covid_tanpa_negatif_ventilator" => 0,
			"kapasitas_icu_covid_tanpa_negatif_tanpa_ventilator" => 0,
			"kapasitas_isolasi_negatif" => 0,
			"kapasitas_isolasi_tanpa_negatif" => 0,
			"kapasitas_nicu_covid" => 0,
			"kapasitas_perina_covid" => 0,
			"kapasitas_picu_covid" => 0,
			"kapasitas_ok_covid" => 0,
			"kapasitas_hd_covid" => 0,
			"kosong_vip" => 0,
			"kosong_kelas_1" => count($kapasitas_kosong['kelas_1']),
			"kosong_kelas_2" => count($kapasitas_kosong['kelas_2']),
			"kosong_kelas_3" => count($kapasitas_kosong['kelas_3']),
			"kosong_kelas_1_l" => count($kapasitas_kosong_by_gender['kelas_1'][1]),
			"kosong_kelas_2_l" => count($kapasitas_kosong_by_gender['kelas_2'][1]),
			"kosong_kelas_3_l" => count($kapasitas_kosong_by_gender['kelas_3'][1]),
			"kosong_kelas_1_p" => count($kapasitas_kosong_by_gender['kelas_1'][2]),
			"kosong_kelas_2_p" => count($kapasitas_kosong_by_gender['kelas_2'][2]),
			"kosong_kelas_3_p" => count($kapasitas_kosong_by_gender['kelas_3'][3]),
			"kosong_hcu" => count($kapasitas_kosong_icu[11]),
			"kosong_iccu" => count($kapasitas_kosong_icu[10]),
			"kosong_icu_negatif_ventilator" => 0,
			"kosong_icu_negatif_tanpa_ventilator" => 0,
			"kosong_icu_tanpa_negatif_ventilator" => 0,
			"kosong_icu_tanpa_negatif_tanpa_ventilator" => 0,
			"kosong_icu_covid_negatif_ventilator" => 0,
			"kosong_icu_covid_negatif_tanpa_ventilator" => 0,
			"kosong_icu_covid_tanpa_negatif_ventilator" => 0,
			"kosong_icu_covid_tanpa_negatif_tanpa_ventilator" => 0,
			"kosong_isolasi_negatif" => 0,
			"kosong_isolasi_tanpa_negatif" => 0,
			"kosong_nicu_covid" => 0,
			"kosong_perina_covid" => 0,
			"kosong_picu_covid" => 0,
			"kosong_ok_covid" => 0,
			"kosong_hd_covid" => 0,
			"updated_time" => date('Y-m-d H:i:s'),

		);
		
		// echo '<pre>'; print_r($fields);die;
		// $encode_data = json_encode($fields);
		$this->postDataBed($fields);


		return $fields;
	}

	public function postDataBed($fields){
		header('Content-Type: application/json;'); 

		$url = "http://eis.dinkes.jakarta.go.id/api-bed/bed";

		$header = array();
		$header[] = 'Api-Bed-User: 3171450';
		$header[] = 'Api-Bed-Key: $2y$10$nAboXj5XrMiW5wif9\/86PeFJmgHnOrqMGuJsviNtXpV323XX.uVyi';
		$header[] = 'Accept: application/json;';

		$post_fields = http_build_query($fields);

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_fields);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

		//execute post
		$result = curl_exec($ch);

		$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		if($httpCode == 404) {
			//echo "URL Not Found<br />";
			echo $result;
		}
		elseif($httpCode == 500) {
			//echo "Internal Server Error<br />";
			echo $result;
		}
		elseif($httpCode == 200) {
			echo $result;
		}
		else {
			//echo "Unknown Error<br />";
			echo $result;
		}
		//close connection
		curl_close($ch);
	}

	
    
	// function decrypt
	function stringDecrypt($key, $string){
		
	
		$encrypt_method = 'AES-256-CBC';

		// hash
		$key_hash = hex2bin(hash('sha256', $key));
	
		// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
		$iv = substr(hex2bin(hash('sha256', $key)), 0, 16);

		$output = openssl_decrypt(base64_decode($string), $encrypt_method, $key_hash, OPENSSL_RAW_DATA, $iv);
	
		return $output;
	}

	// function lzstring decompress 
	// download libraries lzstring : https://github.com/nullpunkt/lz-string-php
	function decompress($string){
	
		return \LZCompressor\LZString::decompressFromEncodedURIComponent($string);

	}


}
