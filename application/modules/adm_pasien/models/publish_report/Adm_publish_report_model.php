<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adm_publish_report_model extends CI_Model {

	var $table = 'ak_tc_publish_report';
	var $column = array('ak_tc_publish_report.kode_report');
	var $select = 'ak_tc_publish_report.*';

	var $order = array('ak_tc_publish_report.tanggal_transaksi' => 'DESC');

	public function __construct()
	{
		parent::__construct();
	}

	private function _main_query(){
		$this->db->select($this->select);
		$this->db->from($this->table);
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

	function get_data()
	{
		$this->_main_query();
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
			$this->db->where_in(''.$this->table.'.id_ak_tc_publish_report',$id);
			$query = $this->db->get();
			return $query->result();
		}else{
			$this->db->where(''.$this->table.'.id_ak_tc_publish_report',$id);
			$query = $this->db->get();
			return $query->row();
		}
		
	}


	public function save($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function update($where, $data)
	{
		$this->db->update($this->table, $data, $where);
		// print_r($this->db->last_query());die;
		return $this->db->affected_rows();
	}

	public function delete_by_id($id)
	{
		$this->db->where_in(''.$this->table.'.id_ak_tc_publish_report', $id);
		return $this->db->delete($this->table);
		
	}

	public function list_fields(){
		return $this->db->list_fields( $this->table );
	}


}
