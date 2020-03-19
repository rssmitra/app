<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Antrian_pasien_model extends CI_Model {

	var $table = 'tc_registrasi';
	var $column = array('tc_registrasi.no_registrasi','tc_registrasi.no_mr','mt_master_pasien.nama_pasien','mt_perusahaan.nama_perusahaan','tc_registrasi.tgl_jam_masuk','mt_bagian.nama_bagian', 'mt_karyawan.nama_pegawai');
	var $select = 'tc_registrasi.no_registrasi,tc_registrasi.no_mr,mt_master_pasien.nama_pasien,mt_perusahaan.nama_perusahaan,tc_registrasi.tgl_jam_masuk,mt_bagian.nama_bagian, mt_karyawan.nama_pegawai, print_tracer, stat_pasien';

	var $order = array('tc_registrasi.no_registrasi' => 'DESC', 'tc_registrasi.updated_date' => 'DESC');

	public function __construct()
	{
		parent::__construct();
	}

	public function get_search_data($data)
	{
		# code...
		//print_r($data);die;
		$this->db->select('*');
		$this->db->from('log_antrian a');
		$this->db->join("(select max(created_date) AS created,tanggal from log_antrian group by tanggal) as b",'b.tanggal = a.tanggal','left');

		if (isset($data['from_tgl']) AND $data['from_tgl'] != '' || isset($data['to_tgl']) AND $data['to_tgl'] != '') {
			$this->db->where("a.tanggal between '".$data['from_tgl']."' and '".$data['to_tgl']."'");	
			
		}else{
			$this->db->where('a.tanggal >= '.date('Y-m-d').' ');
		}

		$this->db->where('a.created_date = b.created');

		$query = $this->db->get();
		$result = $query->result();

		$alldata = array();
		foreach ($result as $value) {
			# code...
			$res = array();
			$data_umum = json_decode($value->log_umum);
			$data_bpjs = json_decode($value->log_bpjs);
			$res['tanggal'] = $value->tanggal;
			$res['jumlah_umum'] = $value->jumlah_umum;
			$res['jumlah_bpjs'] = $value->jumlah_bpjs;
			$merge = array_merge($data_umum,$data_bpjs);
				
				if( $data['bagian']!='' && $data['dokterHidden']=='' ){
					$datas = array();
					foreach ($merge as $value) {
						if($data['bagian'] == $value->klinik){
							$datas[] = $value;
						}
					}
					$res['data'] = $datas;
				}else if( $data['dokterHidden']!='' && $data['bagian']=='' ){
					$datas = array();
					foreach ($merge as $value) {
						if(trim($data['dokterHidden']) == trim($value->dokter)){
							$datas[] = $value;
						}
					}
					$res['data'] = $datas;
				}else if( $data['dokterHidden']!=''  && $data['bagian']!='' ){
					$datas = array();
					foreach ($merge as $value) {
						if((trim($data['dokterHidden']) == trim($value->dokter)) && ($data['bagian'] == $value->klinik)){
							$datas[] = $value;
						}
					}
					$res['data'] = $datas;
					
				}else{
					$res['data'] = $merge;
				}			
						
			$alldata[] = $res;
		}
	
		// print_r($alldata);die;
		return $alldata;


	}

	public function get_export_data($data)
	{
		# code...
		//print_r($data);die;
		$this->db->select('*');
		$this->db->from('log_antrian a');
		$this->db->join("(select max(created_date) AS created,tanggal from log_antrian group by tanggal) as b",'b.tanggal = a.tanggal','left');

		if (isset($data['from_tgl']) AND $data['from_tgl'] != '' || isset($data['to_tgl']) AND $data['to_tgl'] != '') {
			$this->db->where("a.tanggal between '".$data['from_tgl']."' and '".$data['to_tgl']."'");	
			
		}else{
			$this->db->where('a.tanggal >= '.date('Y-m-d').' ');
		}

		$this->db->where('a.created_date = b.created');

		$query = $this->db->get();
		$result = $query->result();

		$alldata = array();
		foreach ($result as $value) {
			# code...
			$res = array();
			$data_umum = json_decode($value->log_umum);
			$data_bpjs = json_decode($value->log_bpjs);
			$res['tanggal'] = $value->tanggal;
			$res['jumlah_umum'] = $value->jumlah_umum;
			$res['jumlah_bpjs'] = $value->jumlah_bpjs;
			$merge = array_merge($data_umum,$data_bpjs);
				
				if( $data['bagian']!='' && $data['dokterHidden']=='' ){
					$datas = array();
					foreach ($merge as $value) {
						if($data['bagian'] == $value->klinik){
							$datas[] = $value;
						}
					}
					$byGroup = $this->group_by("klinik", $datas);
					$res['data'] = $byGroup;
				}else if( $data['dokterHidden']!='' && $data['bagian']=='' ){
					$datas = array();
					foreach ($merge as $value) {
						if(trim($data['dokterHidden']) == trim($value->dokter)){
							$datas[] = $value;
						}
					}
					$byGroup = $this->group_by("klinik", $datas);
					$res['data'] = $byGroup;
				}else if( $data['dokterHidden']!=''  && $data['bagian']!='' ){
					$datas = array();
					foreach ($merge as $value) {
						if((trim($data['dokterHidden']) == trim($value->dokter)) && ($data['bagian'] == $value->klinik)){
							$datas[] = $value;
						}
					}
					$byGroup = $this->group_by("klinik", $datas);
					$res['data'] = $byGroup;
					
				}else{
					$byGroup = $this->group_by("klinik", $merge);
					$res['data'] = $byGroup;
				}			
						
			$alldata[] = $res;
		}
	
		$allmerge = array_merge($alldata);
		//echo "<pre>";print_r($allmerge);echo "</pre>";die;
		return $alldata;


	}

	// function group_by($key, $data) {
	// 	$result = array();
	// 	$total = 0;
	
	// 	foreach($data as $val) {
	// 		if(array_key_exists($key, $val)){
	// 			$result[$val->$key][] = count($val);
	// 			$total = count($result[$val->$key]);
	// 			$res[$val->$key] = $total;
	// 		}else{
	// 			$result[""][] = $val;
	// 		}
	// 	}
	
	// 	return $res;
	// }

	function group_by($key, $data) {
		$result = array();
		$res = array();
		$total_umum = 0;
		$total_bpjs = 0;
	
		foreach($data as $val) {
			
			if(array_key_exists($key, $val)){
				if($val->type == 'umum'){
					$result[$val->$key][] = count($val);
					$total_umum = count($result[$val->$key]);
					$res[$val->$key]['umum'] = $total_umum;
				}else{
					$result[$val->$key][] = count($val);
					$total_bpjs = count($result[$val->$key]);
					$res[$val->$key]['bpjs'] = $total_bpjs;
				}				
			}else{
				$result[""][] = $val;
			}
		}
	
		return $res;
	}



}
