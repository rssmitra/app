<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kepeg_riwayat_pendidikan_model extends CI_Model {

	var $table = 'Kepeg_riwayat_pendidikan';
	var $column = array('Kepeg_riwayat_pendidikan.kepeg_rpd_nama_sekolah');
	var $select = 'Kepeg_riwayat_pendidikan.*';

	var $order = array('Kepeg_riwayat_pendidikan.kepeg_rpd_tahun_lulus' => 'DESC');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _main_query(){
		$this->db->select($this->select);
		$this->db->from($this->table);
	}

	private function _get_datatables_query()
	{
		
		$this->_main_query();
		// filter by kepeg_id
		$this->db->where('kepeg_id', $_GET['kepeg_id']);
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
		$this->_main_query();
		if(is_array($id)){
			$this->db->where_in(''.$this->table.'.kepeg_rpd_id',$id);
			$query = $this->db->get();
			return $query->result();
		}else{
			$this->db->where(''.$this->table.'.kepeg_rpd_id',$id);
			$query = $this->db->get();
			return $query->row();
		}
		
	}

	public function save($table, $data)
	{
		$this->db->insert($table, $data);
		// print_r($this->db->last_query());die;
		return $this->db->insert_id();
	}

	public function update($table, $where, $data)
	{
		$this->db->update($table, $data, $where);
		return $this->db->affected_rows();
	}

	public function delete_by_id($id)
	{
		// delete riwayat pekerjaan
		$this->db->where_in('Kepeg_riwayat_pendidikan.kepeg_rpd_id', $id);
		return $this->db->delete('Kepeg_riwayat_pendidikan');
	}


}
