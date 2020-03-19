<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inv_mutasi_gdg_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->table = ($_GET['flag'] == 'non_medis') ? 'tc_kartu_stok_nm' : 'tc_kartu_stok' ;
		$this->column = array('b.kode_brg','b.nama_brg');
		$this->select = 'tgl_input, stok_awal, stok_akhir, pemasukan, pengeluaran, kode_bagian, keterangan, petugas, id_kartu, kode_brg';
		$this->order = array('a.tgl_input' => 'DESC');
	}

	private function _main_query(){

		$this->db->select($this->select);
		$this->db->from($this->table.' as a');
		$this->db->where('kode_bagian', $_GET['kode_bagian']);
		$this->db->where('kode_brg', $_GET['kode_brg']);
		if (isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' || isset($_GET['to_tgl']) AND $_GET['to_tgl'] != '') {
			$this->db->where("convert(varchar,a.tgl_input,23) between '".$_GET['from_tgl']."' and '".$_GET['to_tgl']."'");					
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
