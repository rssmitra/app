<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pb_riwayat_model extends CI_Model {

	var $table_nm = 'tc_penerimaan_barang_nm';
	var $table = 'tc_penerimaan_barang';
	var $column = array('a.kode_permohonan');
	var $select = 'a.id_penerimaan, a.kode_penerimaan, a.no_po, a.tgl_penerimaan, a.petugas, a.no_faktur, a.dikirim, c.namasupplier';
	var $order = array('a.id_penerimaan' => 'DESC');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _main_query(){
		$table = ($_GET['flag']=='non_medis')?$this->table_nm:$this->table;
		$this->db->select($this->select);
		$this->db->from(''.$table.' a');
		$this->db->join('mt_supplier c','c.kodesupplier=a.kodesupplier', 'left');
		$this->db->where('YEAR(a.tgl_penerimaan)', date('Y'));
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
		//echo '<pre>';print_r($this->db->last_query());die;
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
		$table = ($_GET['flag']=='non_medis')?$this->table_nm:$this->table;
		$this->_main_query();
		if(is_array($id)){
			$this->db->where_in('a.id_tc_permohonan',$id);
			$query = $this->db->get();
			return $query->result();
		}else{
			$this->db->where('a.id_tc_permohonan',$id);
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
		/*delete stok opname*/
		$this->db->where_in('id_tc_permohonan', $id)->delete('tc_stok_opname');
		$this->db->where_in('id_tc_permohonan', $id)->delete('tc_stok_opname_nm');
		$this->db->where_in('id_tc_permohonan', $id)->delete('tc_permohonan_nm');
		return true;
	}

	public function get_brg_penerimaan($flag, $id){
		$mt_barang = ($flag=='non_medis')?'mt_barang_nm':'mt_barang';
		$table = ($flag=='non_medis')?$this->table_nm:$this->table;
		$join = ($flag=='non_medis')?'tc_penerimaan_barang_nm_detail':'tc_penerimaan_barang_detail';
		$join_2 = ($flag=='non_medis')?'tc_penerimaan_barang_nm':'tc_penerimaan_barang';

		$this->db->select('a.*, c.nama_brg, b.no_po, b.tgl_penerimaan ,c.satuan_besar, a.content as rasio_penerimaan ');
		$this->db->from(''.$table.'_detail a');
		$this->db->join($table.' b', 'b.id_penerimaan=a.id_penerimaan', 'left');
		$this->db->join($mt_barang.' c', 'c.kode_brg=a.kode_brg', 'left');
		$id = (is_array($id)) ? implode(',', $id) : $id ;
		$this->db->where('a.id_penerimaan IN ('.$id.')');
		$this->db->order_by('c.nama_brg ASC');
		return $this->db->get()->result();
	}


}
