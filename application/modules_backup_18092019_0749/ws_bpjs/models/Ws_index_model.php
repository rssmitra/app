<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ws_index_model extends CI_Model {

	/*development*/
	/*var $consID 		= "12973";
	var $secretKey 		= "4aT7357418";
	var $base_api_url	= "https://dvlp.bpjs-kesehatan.go.id/VClaim-Rest";*/

	/*production*/
	var $consID 		= "17971";
	var $secretKey 		= "9bK292AE57";
	//var $base_api_url	= "http://api.bpjs-kesehatan.go.id:8080/vclaim-rest";
	var $base_api_url	= "https://new-api.bpjs-kesehatan.go.id:8080/new-vclaim-rest";


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
	            "X-cons-id:".$this->consID, 
	            "X-timestamp: ".$stamp, 
	            "X-signature: ".$encodedSignature,
	            'Content-Type: application/x-www-form-urlencoded'
	        ); 
		//echo '<pre>';print_r($headers);die;
		return $headers;

	}

	function getData($service_name){

		$uri = $this->base_api_url.'/'.$service_name;
		$ch = curl_init($uri);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $this->getSignatureHeader()); 
		$data = curl_exec($ch);
		//print_r(curl_errno($ch));die;
		curl_close($ch);
		return json_decode($data);

	}

	function postDataWs($service_name, $post_data='', $method=''){

		$uri = $this->base_api_url.'/'.$service_name;

		$json = json_encode($post_data); //print_r($json);die;
		$c = curl_init();

		curl_setopt($c, CURLOPT_URL, $uri);
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
		curl_close($c); 

		return json_decode($result);

	}

	/*referensi module*/
	function refDiagnosa(){
		$service_name = "referensi/diagnosa/".$_POST['keyword'];
		$result = $this->getData($service_name); 
		if($result->metaData->code==200){
			foreach ($result->response->diagnosa as $key => $value) {
				$arrResult[] = $value->kode.' : '.$value->nama;
			}
			return $arrResult;
		}
	}

	function refPoli(){
		$service_name = "referensi/poli/".$_POST['keyword'];
		$result = $this->getData($service_name);
		if($result->metaData->code==200){
			foreach ($result->response->poli as $key => $value) {
				$arrResult[] = $value->kode.' : '.$value->nama;
			}
			return $arrResult;
		}
	}

	function refFaskes(){
		$service_name = "referensi/faskes/".$_POST['keyword']."/".$_POST['jf'];
		$result = $this->getData($service_name);
		if($result->metaData->code==200){
			foreach ($result->response->faskes as $key => $value) {
				$arrResult[] = $value->kode.' : '.$value->nama;
			}
			return $arrResult;
		}
	}

	function refProcedure(){
		$service_name = "referensi/procedure/".$_POST['keyword'];
		$result = $this->getData($service_name);
		if($result->metaData->code==200){
			foreach ($result->response->procedure as $key => $value) {
				$arrResult[] = $value->nama;
			}
			return $arrResult;
		}
	}

	function refDokter(){
		$service_name = "referensi/dokter/".$_POST['keyword'];
		$result = $this->getData($service_name);
		if($result->metaData->code==200){
			foreach ($result->response->list as $key => $value) {
				$arrResult[] = ''.$value->kode.' : '.$value->nama;
			}
			return $arrResult;
		}
	}

	function RefDokterDPJP(){
		$service_name = "referensi/dokter/pelayanan/".$_POST['jp']."/tglPelayanan/".$this->tanggal->sqlDateForm($_POST['tgl'])."/Spesialis/".$_POST['spesialis'];
		$result = $this->getData($service_name);
		//print_r($result);die;
		if($result->metaData->code==200){
			foreach ($result->response->list as $key => $value) {
				$arrResult[] = ''.$value->kode.' : '.$value->nama;
			}
			return $arrResult;
		}
	}

	function refKelasRawat(){
		$service_name = "referensi/kelasrawat";
		$result = $this->getData($service_name);
		if($result->metaData->code==200){
			return $result->response->list;
		}
	}

	function refSpesialistik(){
		$service_name = "referensi/spesialistik";
		$result = $this->getData($service_name);
		if($result->metaData->code==200){
			return $result->response->list;
		}
	}

	function refRuangRawat(){
		$service_name = "referensi/ruangrawat";
		$result = $this->getData($service_name);
		if($result->metaData->code==200){
			return $result->response->list;
		}
	}

	function refCaraKeluar(){
		$service_name = "referensi/carakeluar";
		$result = $this->getData($service_name);
		if($result->metaData->code==200){
			return $result->response->list;
		}
	}

	function refPascaPulang(){
		$service_name = "referensi/pascapulang";
		$result = $this->getData($service_name);
		if($result->metaData->code==200){
			return $result->response->list;
		}
	}

	/*kepesertaan*/
	function searchMember(){
		if($_POST['jenis_kartu']=='bpjs'){
			$service_name = "Peserta/nokartu/".$_POST['nokartu']."/tglSEP/".$this->tanggal->sqlDateForm($_POST['tglSEP'])."";
		}else{
			$service_name = "Peserta/nik/".$_POST['nokartu']."/tglSEP/".$this->tanggal->sqlDateForm($_POST['tglSEP'])."";
		}
		//print_r($service_name);die;
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

	function insertRujukan($request){
		$service_name = "Rujukan/insert";
		return $this->postDataWs($service_name, $request);
	}


	/*ketersediaan kamar*/
	function daftarRuanganRs($kode_ppk='0112R034', $start_data=1, $limit=10){
		$service_name = "aplicaresws/rest/bed/read/".$kode_ppk."/".$start_data."/".$limit."";
		$result = $this->getData($service_name);

		if($result->metaData->code==200){
			return $result->response->list;
		}
	}

	/*monitoring*/

	function get_datatables_kunjungan()
    {
    	$tglSep = isset($_GET['tglSep'])?$_GET['tglSep']:date('m/d/Y');
    	$jnsPelayanan = isset($_GET['jnsPelayanan'])?$_GET['jnsPelayanan']:2;
    	$service_name = "Monitoring/Kunjungan/Tanggal/".$this->tanggal->sqlDateForm($tglSep)."/JnsPelayanan/".$jnsPelayanan."";
		return $this->getData($service_name);
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
		if($result->metaData->code==200){
			return $result;
		}
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

	function get_data_sep($sep){
		$data = $this->db->get_where('ws_bpjs_sep', array('noSep' => $sep) )->row();
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
		return $query->result();
	}

	private function _get_datatables_query_ruangan_rs()
	{
		
		$this->_main_query_ruangan_rs();

		$i = 0;
		$reff_column = array('a.kode_ruangan', 'a.kode_bagian' , 'b.nama_bagian', 'c.kode_klas_bpjs','c.nama_klas_bpjs','c.kode_klas' , 'c.nama_klas', 'a.no_kamar', 'a.no_bed', 'a.status', 'a.keterangan', 'a.gender');

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
		return $query;
	}

	function countRuangan($kode_bagian, $kode_klas){
		$this->_main_query_ruangan_rs();
		$this->db->where('a.kode_bagian', $kode_bagian);
		$this->db->where('a.kode_klas', $kode_klas);
		$this->db->where("a.status !='ISI'");
		$query = $this->db->get()->num_rows();
		return $query;
	}

	private function _main_query_ruangan_rs(){
		$this->db->select('a.kode_ruangan, a.kode_bagian, b.nama_bagian, c.kode_klas_bpjs, c.nama_klas_bpjs, c.kode_klas, c.nama_klas, a.no_kamar, a.no_bed, a.status, a.keterangan, a.gender');
		$this->db->from('mt_ruangan a');
		$this->db->join('mt_bagian b','b.kode_bagian=a.kode_bagian','left');
		$this->db->join('mt_klas c','c.kode_klas=a.kode_klas','left');
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





}
