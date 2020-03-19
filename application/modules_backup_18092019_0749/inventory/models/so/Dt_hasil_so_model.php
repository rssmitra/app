<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dt_hasil_so_model extends CI_Model {

	var $table = 'tc_stok_opname';
	var $table_nm = 'tc_stok_opname_nm';
	var $column = array('nama_bagian');
	var $select = 'mt_bagian.kode_bagian, nama_bagian, nama_brg, stok_sebelum, stok_sekarang, tgl_stok_opname, nama_petugas';
	var $order = array('tgl_stok_opname' => 'ASC');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _main_query(){
		$table = ($_GET['flag']=='medis') ? $this->table : $this->table_nm;
		$join_t_brg = ($_GET['flag']=='medis') ? 'mt_barang' : 'mt_barang_nm';
		
		$this->db->select($this->select);
		$this->db->select(''.$join_t_brg.'.kode_brg');
		$this->db->select(''.$join_t_brg.'.satuan_besar');
		$this->db->select(''.$join_t_brg.'.satuan_kecil');
		$this->db->from($table);
		$this->db->where('agenda_so_id', $_GET['agenda_so_id']);
		$this->db->where(''.$table.'.kode_bagian', $_GET['kode_bagian']);
		$this->db->join('mt_bagian', 'mt_bagian.kode_bagian='.$table.'.kode_bagian');
		$this->db->join($join_t_brg, ''.$join_t_brg.'.kode_brg='.$table.'.kode_brg');
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

	function get_all_data()
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

}
