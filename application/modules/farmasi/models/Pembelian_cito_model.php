<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pembelian_cito_model extends CI_Model {

	var $table = 'fr_pengadaan_cito';
	var $column = array('fr_pengadaan_cito.kode_brg');
	var $select = 'id_fr_pengadaan_cito, kode_pengadaan,fr_pengadaan_cito.tgl_pembelian,fr_pengadaan_cito.kode_brg,fr_pengadaan_cito.jumlah_kcl,fr_pengadaan_cito.harga_beli,fr_pengadaan_cito.total_harga,fr_pengadaan_cito.harga_jual,fr_pengadaan_cito.flag_jurnal,fr_pengadaan_cito.induk_cito,fr_pengadaan_cito.tempat_pembelian,fr_pengadaan_cito.status_transaksi,fr_pengadaan_cito.brg_konsinyasi,mt_barang.nama_brg';

	var $order = array('fr_pengadaan_cito.id_fr_pengadaan_cito' => 'DESC');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _main_query(){
		$this->db->select($this->select);
		$this->db->from($this->table);
		$this->db->join('mt_barang', 'mt_barang.kode_brg=fr_pengadaan_cito.kode_brg' , 'left');

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

	public function get_by_id($id)
	{
		$this->_main_query();
		if(is_array($id)){
			$this->db->where_in(''.$this->table.'.id_fr_pengadaan_cito',$id);
			$query = $this->db->get();
			return $query->result();
		}else{
			$this->db->where(''.$this->table.'.id_fr_pengadaan_cito',$id);
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
		$this->db->where_in(''.$this->table.'.id_fr_pengadaan_cito', $id);
		return $this->db->delete($this->table);
	}

	// list cito

	private function _get_datatables_query_list_cito()
	{
		
		$this->_main_query();
		$this->db->where('induk_cito', $_GET['induk']);
		
	}
	
	function get_datatables_list_cito()
	{
		$this->_get_datatables_query_list_cito();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered_list_cito()
	{
		$this->_get_datatables_query_list_cito();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all_list_cito()
	{
		$this->_get_datatables_query_list_cito();
		return $this->db->count_all_results();
	}

	function get_data_list_cito()
	{
		$this->_get_datatables_query_list_cito();
		$query = $this->db->get();
		return $query->result();
	}

}
