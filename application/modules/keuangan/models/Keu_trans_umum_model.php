<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Keu_trans_umum_model extends CI_Model {

	var $table = 'bd_tc_trans';
	var $column = array('uraian');
	var $select = 'a.*, b.nama_bank, c.nama_bagian';
	var $order = array('tgl_transaksi' => 'DESC');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _main_query(){

		$this->db->select($this->select);
		$this->db->from($this->table.' a');
		$this->db->join('mt_bank b', 'b.acc_no=a.kode_bank', 'left');
		$this->db->join('mt_bagian c', 'c.kode_bagian=a.kode_bagian', 'left');

		if (isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' || isset($_GET['to_tgl']) AND $_GET['to_tgl'] != '') {
			$this->db->where("convert(varchar,tgl_transaksi,23) between '".$_GET['from_tgl']."' and '".$_GET['to_tgl']."'");					
		}else{
			$this->db->where("YEAR(tgl_transaksi)", date('Y'));
		}
		
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
		$query = $this->db->get()->result();
		// print_r($this->db->last_query());die;
		return $query;
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
		$this->db->where('id_bd_tc_trans', $id);
		return $this->db->delete('bd_tc_trans');
		
	}

	public function get_by_id($id)
	{
		$this->_main_query();
		if(is_array($id)){
			$this->db->where_in('a.id_bd_tc_trans',$id);
			$query = $this->db->get();
			return $query->result();
		}else{
			$this->db->where('a.id_bd_tc_trans',$id);
			$query = $this->db->get();
			return $query->row();
		}
		
	}


}
