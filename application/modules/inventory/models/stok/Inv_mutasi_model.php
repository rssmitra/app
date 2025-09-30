<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inv_mutasi_model extends CI_Model {

	var $table = 'tc_kartu_stok';
	var $column = array('keterangan');
	var $select = 'tgl_input, stok_awal, stok_akhir, pemasukan, pengeluaran, tc_kartu_stok.kode_bagian, keterangan, petugas, id_kartu, kode_brg';
	var $order = array('tc_kartu_stok.id_kartu' => 'DESC');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _main_query(){

		$this->db->select($this->select);
		$this->db->select('fullname');
		$this->db->from($this->table);
		$this->db->where('tc_kartu_stok.kode_bagian', $_GET['kode_bagian']);
		$this->db->where('kode_brg', $_GET['kode_brg']);
		$this->db->join('tmp_user', 'tmp_user.user_id=tc_kartu_stok.petugas', 'left' );
		if (isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' || isset($_GET['to_tgl']) AND $_GET['to_tgl'] != '') {
			$this->db->where("convert(varchar,tc_kartu_stok.tgl_input,23) between '".$_GET['from_tgl']."' and '".$_GET['to_tgl']."'");					
		}else{
			$this->db->where('DATEDIFF(day,tgl_input,GETDATE()) < 120');
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
		$this->_get_datatables_query();
		return $this->db->count_all_results();
	}

	public function get_by_params()
	{
		$this->_main_query();
		$this->db->order_by('tgl_input', 'DESC');
		return $this->db->get()->result();
	}

	
}
