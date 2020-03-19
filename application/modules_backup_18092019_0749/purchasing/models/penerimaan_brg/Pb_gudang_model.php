<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pb_gudang_model extends CI_Model {

	var $table_nm = 'tc_po_nm';
	var $table = 'tc_po';
	var $column = array('a.kode_permohonan');
	var $select = 'a.id_tc_po, a.no_po, a.tgl_po, a.ppn, a.total_sbl_ppn, a.total_stl_ppn, a.discount_harga, a.term_of_pay, b.username, a.diajukan_oleh, a.disetujui_oleh, c.namasupplier';
	var $order = array('a.id_tc_po' => 'DESC');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _main_query(){
		$table = ($_GET['flag']=='non_medis')?$this->table_nm:$this->table;
		$this->db->select($this->select);
		$this->db->from(''.$table.' a');
		$this->db->join('dd_user b','b.id_dd_user=a.user_id', 'left');
		$this->db->join('mt_supplier c','c.kodesupplier=a.kodesupplier', 'left');
		$this->db->where('YEAR(a.tgl_po)', date('Y'));
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

	public function get_brg_po($flag, $id){
		$mt_barang = ($flag=='non_medis')?'mt_barang_nm':'mt_barang';
		$table = ($flag=='non_medis')?$this->table_nm:$this->table;
		$join = ($flag=='non_medis')?'tc_po_nm_det':'tc_po_det';
		$join_2 = ($flag=='non_medis')?'tc_po_nm':'tc_po';
		$join_3 = ($flag=='non_medis')?'mt_rekap_stok_nm':'mt_rekap_stok';

		$this->db->select('a.*, c.nama_brg, b.no_po, b.tgl_po, c.satuan_besar, CAST(a.harga_satuan_netto as INT) as harga_satuan_po, CAST(a.jumlah_harga_netto as int) as total_harga, a.content as rasio_po, CAST(a.discount AS INT) as discount, CAST(a.discount_rp as INT) as discount_rp, a.ppn, e.tgl_kirim, e.krs, e.diajukan_oleh, e.disetujui_oleh, e.term_of_pay');
		$this->db->from(''.$table.'_det a');
		$this->db->join($table.' b', 'b.id_tc_po=a.id_tc_po', 'left');
		$this->db->join($mt_barang.' c', 'c.kode_brg=a.kode_brg', 'left');
		$this->db->join($join_2.' e', 'e.id_tc_po=a.id_tc_po', 'left');
		$this->db->join($join_3.' f', 'f.kode_brg=a.kode_brg', 'left');
		$id = (is_array($id)) ? implode(',', $id) : $id ;
		$this->db->where('a.id_tc_po IN ('.$id.')');
		$this->db->order_by('c.nama_brg ASC');
		return $this->db->get()->result();
	}


}
