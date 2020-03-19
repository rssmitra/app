<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Req_selected_detail_brg_model extends CI_Model {

	var $table_nm = 'mt_barang_nm';
	var $table = 'mt_barang';
	var $column = array('kode_brg','nama_brg');
	var $select = 'a.nama_brg, a.satuan_besar, a.satuan_kecil, b.jml_sat_kcl, a.content, a.kode_brg, b.stok_minimum, b.stok_maksimum, a.path_image, a.is_active, c.stok_akhir';
	var $order = array('a.nama_brg' => 'ASC');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _main_query(){
		$table = ($_GET['flag']=='non_medis')?$this->table_nm:$this->table;
		$join = ($_GET['flag']=='non_medis') ? 'tc_kartu_stok_nm_v' : 'tc_kartu_stok_v' ;
		$join_2 = ($_GET['flag']=='non_medis') ? 'mt_rekap_stok_nm' : 'mt_rekap_stok' ;
		$this->db->select('b.harga_beli as harga_beli_terakhir');
		$this->db->select($this->select);
		$this->db->from($table.' a');
		$this->db->join($join.' c' ,'c.kode_brg=a.kode_brg','left');
		$this->db->join($join_2.' b' ,'b.kode_brg=a.kode_brg','left');

		$kd_bagian = ($_GET['flag']=='non_medis')?'070101':'060201';

		if(isset($_GET['search_by']) AND $_GET['search_by'] != '' AND isset($_GET['key']) AND $_GET['key'] != '' ){
			$this->db->like($_GET['search_by'], $_GET['key']);
		}

		if( $_GET['flag'] == 'non_medis'){
			$this->db->where( ' (c.kode_bagian = '."'".$kd_bagian."'".' OR c.kode_bagian IS NULL ) ' );
		}else{
			$this->db->where('c.kode_bagian', $kd_bagian);
		}

		$this->db->group_by('b.harga_beli');
		$this->db->group_by($this->select);

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
		$this->_main_query();
		return $this->db->count_all_results();
	}

	public function save($table, $data)
	{
		$this->db->insert($table, $data);
		return $this->db->insert_id();
	}

	public function update($table, $where, $data)
	{
		$this->db->update($table, $data, $where);
		
		return $this->db->affected_rows();
	}

	public function delete_by_id($table, $id)
	{
		/*delete stok opname*/
		$this->db->where_in('id_tc_permohonan_det', $id)->delete( $table );
		return true;
	}

	
}
