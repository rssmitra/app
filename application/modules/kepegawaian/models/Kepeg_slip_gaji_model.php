<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kepeg_slip_gaji_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _main_query(){
		$this->db->from('kepeg_rincian_gaji');
		$this->db->join('kepeg_gaji','kepeg_gaji.kg_id=kepeg_rincian_gaji.kg_id','left');
	}

	public function get_data()
	{
		$this->_main_query();
		$this->db->where('kg_periode_bln', $_GET['bulan']);
		$this->db->where('kg_periode_thn', $_GET['tahun']);
		$this->db->where('nip', $this->session->userdata('user_profile')->nip);
		return $this->db->get()->row();
		
	}

	
}
