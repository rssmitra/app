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
		$this->db->join('kepeg_dt_pegawai','kepeg_dt_pegawai.kepeg_nip=kepeg_rincian_gaji.nip','left');
	}

	public function get_data()
	{
		$this->_main_query();
		$this->db->where('kg_periode_bln', $_GET['bulan']);
		$this->db->where('kg_periode_thn', $_GET['tahun']);
		$this->db->where('nip', $this->session->userdata('user_profile')->nip);
		$query = $this->db->get()->row();
		// echo $this->db->last_query();die;
		return $query; 
		
	}

	public function get_bon_karyawan($params)
	{
		$this->db->select('a.kode_trans_far, a.tgl_trans, c.nama_brg, CAST(c.jumlah_pesan as INT) as jumlah_pesan, c.satuan_kecil, CAST(c.total as INT) as total, a.no_resep, a.nama_pasien');
		$this->db->from('fr_tc_far_detail_log c');
		$this->db->join('fr_tc_far a', 'c.kode_trans_far = a.kode_trans_far','left');
		$this->db->join('mt_master_pasien b', 'b.no_mr = a.no_mr','left');
		$this->db->join('tc_trans_pelayanan d', 'd.kode_trans_far=a.kode_trans_far','left');
		$this->db->join('tc_trans_kasir e', 'e.kode_tc_trans_kasir=d.kode_tc_trans_kasir','left');
		$this->db->where('MONTH(a.tgl_trans)', $params['periode']-1);
		$this->db->where('YEAR(a.tgl_trans)', $params['tahun']);
		$this->db->where('b.no_ktp', $params['nik']);
		$this->db->where('e.nk_karyawan > 0');
		$this->db->group_by('a.kode_trans_far, a.tgl_trans, c.nama_brg, CAST(c.jumlah_pesan as INT), c.satuan_kecil, CAST(c.total as INT), a.no_resep, a.nama_pasien');

		$query = $this->db->get()->result();
		// echo '<pre>'; print_r($this->db->last_query());die;
		$getData = [];
		foreach($query as $row){
			$getData[$row->kode_trans_far][] = $row;
		}
		return $getData; 
		
	}

	
}
