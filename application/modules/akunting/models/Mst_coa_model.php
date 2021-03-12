<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mst_coa_model extends CI_Model {

	var $table = 'mt_account';
	var $column = array('mt_account.acc_no','mt_account.acc_nama','mt_account.acc_ref', 'acc_no_rs');
	var $select = 'acc_no, acc_no_rs, acc_nama, acc_type, acc_ref, level_coa, id_mt_account, is_active';

	var $order = array('mt_account.acc_no_rs' => 'ASC');

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

		$i = 0;
	
		foreach ($this->column as $item) 
		{
			if($_POST['search']['value'])
				($i===0) ? $this->db->where(''.$item.' LIKE '."'".$_POST['search']['value']."%'".'') : $this->db->or_where(''.$item.' LIKE '."'".$_POST['search']['value']."%'".'');
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
			$this->db->where_in(''.$this->table.'.acc_no',$id);
			$query = $this->db->get();
			return $query->result();
		}else{
			$this->db->where(''.$this->table.'.acc_no',$id);
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
		return $this->db->affected_rows();
	}

	public function delete_by_id($id)
	{
		$get_data = $this->get_by_id($id);
		$this->db->where_in(''.$this->table.'.acc_no', $id);
		return $this->db->update($this->table, array('is_deleted' => 'Y', 'is_active' => 'N'));
	}

	public function get_komponen_up($kode){
		$dt = $this->db->order_by('id_komponen', 'ASC')->get_where('sakip_mst_komponen', array('kode' => $kode))->row_array();
		$getData = [];
		if($dt['parent'] != '')
			$getData = $this->get_komponen_up($dt['parent']);
			
		$getData[] = array('id' => $dt['id_komponen'], 'name' => $dt['nama_komponen'], 'kode' => $dt['kode'], 'parent' => $dt['parent']);
		return $getData;
	}

	public function get_parent_coa($acc_no){
		// get data row
		$parent = $this->db->get_where('mt_account', array('acc_no' => $acc_no))->row();
		$getData = [];
		if($parent->acc_ref != '')
			$getData = $this->get_parent_coa($parent->acc_ref);
		
		$getData[$parent->level_coa] = array('acc_no' => $parent->acc_no, 'acc_nama' => $parent->acc_nama);
		return $getData;
	}


}
