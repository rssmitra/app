<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kepeg_slip_gaji_model extends CI_Model {

	var $table = 'kepeg_pengajuan_cuti';
	var $column = array();
	var $select = 'kepeg_pengajuan_cuti.*, view_dt_pegawai.kepeg_nip, view_dt_pegawai.nama_pegawai, view_dt_pegawai.nama_unit, view_Dt_pegawai.nama_level, log_acc.*';

	var $order = array('kepeg_pengajuan_cuti.pengajuan_cuti_id' => 'DESC');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _main_query(){
		$this->db->select($this->select);
		$this->db->from($this->table);
		$this->db->join('view_dt_pegawai','view_dt_pegawai.kepeg_id=kepeg_pengajuan_cuti.kepeg_id','left');
		$this->db->join('(SELECT * FROM kepeg_log_acc_pengajuan WHERE type='."'pengajuan_cuti'".' and acc_date IS NULL) as log_acc', 'log_acc.ref_id=pengajuan_cuti_id', 'LEFT');
	}

	private function _get_datatables_query()
	{
		
		$this->_main_query();
		if(isset($_GET['checked_unit']) AND $_GET['checked_unit'] == 1){
			if(isset($_GET['unit']) AND $_GET['unit'] != ''){
				$this->db->where('kepeg_pengajuan_cuti.unit_bagian', $_GET['unit']);
			}
		}

		if(isset($_GET['checked_level_jabatan']) AND $_GET['checked_level_jabatan'] == 1){
			if(isset($_GET['level_jabatan']) AND $_GET['level_jabatan'] != ''){
				$this->db->where('kepeg_pengajuan_cuti.level_pegawai', $_GET['level_jabatan']);
			}
		}

		if(isset($_GET['checked_nama_pegawai']) AND $_GET['checked_nama_pegawai'] == 1){
			if(isset($_GET['nama_pegawai']) AND $_GET['nama_pegawai'] != ''){
				$this->db->like('view_dt_pegawai.nama_pegawai', $_GET['nama_pegawai']);
			}
		}


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
		// print_r($this->db->last_query());die;
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
		$this->_main_query();
		if(is_array($id)){
			$this->db->where_in(''.$this->table.'.pengajuan_cuti_id',$id);
			$query = $this->db->get();
			return $query->result();
		}else{
			$this->db->where(''.$this->table.'.pengajuan_cuti_id',$id);
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
		return $this->db->affected_rows();
	}

	public function delete_by_id($id)
	{
		// delete log acc
		$this->db->where_in('ref_id', $id);
		$this->db->where('type', 'pengajuan_cuti');
		$this->db->delete('kepeg_log_acc_pengajuan');

		$this->db->where_in(''.$this->table.'.pengajuan_cuti_id', $id);
		return $this->db->delete($this->table);

	}

	public function get_spv($unit, $level){
		$qry = "SELECT * FROM view_dt_pegawai WHERE kepeg_unit = (SELECT kepeg_unit_parent FROM kepeg_mt_unit WHERE kepeg_unit_id=".$unit.") and kepeg_level = ".$level."";
		$dt_spv = $this->db->query($qry)->row();
		return $dt_spv;
	}


}
