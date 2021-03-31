<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dokter_model extends CI_Model {

	var $table = 'mt_karyawan';
	var $column = array('mt_karyawan.nama_pegawai');
	var $select = 'mt_karyawan.no_induk,mt_karyawan.urutan_karyawan,mt_karyawan.nama_pegawai,mt_karyawan.kode_jabatan,mt_karyawan.kode_bagian,mt_karyawan.kode_dokter,mt_karyawan.kode_spesialisasi,mt_karyawan.status_dr,mt_karyawan.status,mt_karyawan.available,mt_karyawan.jatah_kelas,mt_karyawan.level_id,mt_karyawan.no_mr,mt_karyawan.flag_tenaga_medis,mt_karyawan.url_foto_karyawan,mt_karyawan.kode_perawat,mt_karyawan.id_mt_karyawan,mt_spesialisasi_dokter.nama_spesialisasi, no_sip, ttd, stamp';

	//var $order = array('no_induk' => 'ASC');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}


	private function _main_query(){
		$this->db->select($this->select);
		$this->db->from($this->table);
		$this->db->join('mt_spesialisasi_dokter', 'mt_spesialisasi_dokter.kode_spesialisasi=mt_karyawan.kode_spesialisasi','left');
		$this->db->join('mt_bagian', 'mt_bagian.kode_bagian=mt_karyawan.kode_bagian','left');
		$this->db->where('kode_dokter <> ');	
		$this->db->group_by($this->select);
		
		//$this->db->where("CASE WHEN 'mt_karyawan.kode_dokter IS NULL' THEN 1 ELSE 0 END <> 0");
	}
	
	private function _get_datatables_query()
	{
		
		$this->_main_query();

		$i = 0;
	
		foreach ($this->column as $item) 
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
		else if(isset($this->order))
		{
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}
	
	function get_datatables()
	{
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all()
	{
		$this->_main_query();
		return $this->db->count_all_results();
	}

	public function get_by_id($id)
	{
		$this->db->select($this->select);
		$this->db->from($this->table);
		$this->db->join('mt_spesialisasi_dokter', 'mt_spesialisasi_dokter.kode_spesialisasi=mt_karyawan.kode_spesialisasi','left');
		$this->db->join('mt_bagian', 'mt_bagian.kode_bagian=mt_karyawan.kode_bagian','left');
		if(is_array($id)){
			$this->db->where_in(''.$this->table.'.kode_dokter',$id);
			$query = $this->db->get();
			return $query->result();
		}else{
			$this->db->where(''.$this->table.'.kode_dokter',$id);
			$query = $this->db->get();
			return $query->row();
		}
		
	}

	public function save($table, $data)
	{
		$this->db->insert($table, $data);
		return $this->db->insert_id();
	}

	public function update($table, $where, $data)
	{
		$this->db->update($table, $data, $where);
		//$this->db->where('kode_dokter', $data);
		return $this->db->affected_rows();
	}

	public function delete_by_id($id)
	{
		$this->db->where_in('mt_karyawan.kode_dokter', $id);
		return $this->db->delete('mt_karyawan');
	}

	public function get_bagian($id){
		$this->db->select('nama_bagian');
		$this->db->from('mt_dokter_bagian');
		$this->db->join('mt_bagian','mt_bagian.kode_bagian=mt_dokter_bagian.kd_bagian','left');
		$this->db->where('mt_dokter_bagian.kode_dokter', $id);
		$mt_dokter_bagian = $this->db->get()->result();
		$html = '';
		foreach ($mt_dokter_bagian as $key => $value) {
			# code...
			$html .= '<li>'.$value->nama_bagian.' </li>';
		}
		return $html;
	}

}
