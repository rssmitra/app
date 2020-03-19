<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Master_model extends CI_Model {


	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	public function dr_anak(){
		$hasil=$this->db->query("SELECT count(mt_spesialisasi_dokter.kode_spesialisasi) as jmlanak FROM mt_karyawan
			inner join mt_spesialisasi_dokter ON mt_karyawan.kode_spesialisasi=mt_spesialisasi_dokter.kode_spesialisasi");
		
	return $hasil;
	}

}
