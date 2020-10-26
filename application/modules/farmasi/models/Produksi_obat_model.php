<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Produksi_obat_model extends CI_Model {

	var $table = 'tc_prod_obat';
	var $column = array('tc_prod_obat.kode_brg');
	var $select = ' tc_prod_obat.id_tc_prod_obat, tc_prod_obat.nama_brg_prod, tc_prod_obat.satuan_prod, tc_prod_obat.kode_brg_prod, tc_prod_obat.id_obat_prod, tc_prod_obat.jasa_r, tc_prod_obat.jasa_prod, tc_prod_obat.jumlah_prod, tc_prod_obat.flag_proses, tc_prod_obat.harga_prod, tc_prod_obat.tgl_prod, tc_prod_obat.tgl_expired, tc_prod_obat.input_id, tc_prod_obat.input_tgl,rasio,harga_satuan, mt_barang.nama_brg';

	var $order = array('tc_prod_obat.id_tc_prod_obat' => 'DESC');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _main_query(){
		$this->db->select($this->select);
		$this->db->from($this->table);
		$this->db->join('mt_barang', 'mt_barang.kode_brg=tc_prod_obat.kode_brg_prod' , 'left');
		$this->db->where('kode_brg_prod IS NOT NULL');

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

	// data tables komposisi obat
	private function _get_datatables_query_komposisi_obat()
	{
		
		$this->db->from('tc_prod_obat_det');
		if(isset($_GET['id_tc_prod_obat']) AND $_GET['id_tc_prod_obat'] != ''){
			$this->db->where('id_tc_prod_obat', $_GET['id_tc_prod_obat']);
		}
		$this->db->order_by('id_tc_prod_obat_det', 'DESC');
	}
	
	function get_datatables_komposisi_obat()
	{
		$this->_get_datatables_query_komposisi_obat();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		// print_r($this->db->last_query());die;
		return $query->result();
	}

	function count_filtered_komposisi_obat()
	{
		$this->_get_datatables_query_komposisi_obat();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all_komposisi_obat()
	{
		$this->_get_datatables_query_komposisi_obat();
		return $this->db->count_all_results();
	}


	public function get_by_id($id)
	{
		$this->_main_query();
		if(is_array($id)){
			$this->db->where_in(''.$this->table.'.id_tc_prod_obat',$id);
			$query = $this->db->get();
			return $query->result();
		}else{
			$this->db->where(''.$this->table.'.id_tc_prod_obat',$id);
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
		$this->db->where_in(''.$this->table.'.id_tc_prod_obat', $id);
		return $this->db->update($this->table, array('is_deleted' => 'Y', 'is_active' => 'N'));
	}

	public function get_komposisi_obat($id){
		$this->_get_datatables_query_komposisi_obat();
		$this->db->where('id_tc_prod_obat', $id);
		$query = $this->db->get()->result();
		return $query;
	}

}
