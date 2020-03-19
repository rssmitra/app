<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ruangan_rawat_inap_model extends CI_Model {

	var $table = 'mt_ruangan';
	var $column = array('mt_ruangan.kode_ruangan','mt_ruangan.no_kamar','mt_ruangan.no_bed','mt_ruangan.status','mt_bagian.nama_bagian','mt_klas.nama_klas');
	var $select = 'mt_ruangan.kode_ruangan,mt_ruangan.no_kamar,mt_ruangan.no_bed,mt_ruangan.status,mt_bagian.nama_bagian,mt_klas.nama_klas';

	var $order = array('mt_ruangan.kode_ruangan' => 'asc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _main_query(){
		$this->db->select($this->select);
		$this->db->from($this->table);
		$this->db->join('mt_bagian','mt_bagian.kode_bagian=mt_ruangan.kode_bagian','left');
		$this->db->join('mt_klas','mt_klas.kode_klas=mt_ruangan.kode_klas','left');
		
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
		//print_r($this->db->last_query());die;
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
