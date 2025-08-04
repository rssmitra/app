<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Req_selected_detail_brg_model extends CI_Model {

	var $table_nm = 'mt_barang_nm';
	var $table = 'mt_barang';
	var $column = array('kode_brg','nama_brg');
	var $select = 'a.nama_brg, a.satuan_besar, a.satuan_kecil, b.jml_sat_kcl, a.content, a.kode_brg, b.stok_minimum, b.stok_maksimum, a.path_image, a.is_active, kartu_stok.stok_akhir';
	var $order = array('a.nama_brg' => 'ASC');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _main_query(){
		$mt_barang = ($_GET['flag']=='non_medis')?$this->table_nm:$this->table;
		$mt_depo_stok = ($_GET['flag']=='non_medis') ? 'mt_depo_stok_nm' : 'mt_depo_stok' ;
		$tc_kartu_stok = ($_GET['flag']=='non_medis') ? 'tc_kartu_stok_nm_v' : 'tc_kartu_stok_v' ;
		$mt_rekap_stok = ($_GET['flag']=='non_medis') ? 'mt_rekap_stok_nm' : 'mt_rekap_stok' ;
		$date = date('Y-m-d');
		$kd_bagian = ($_GET['flag']=='non_medis')?'070101':'060201';

		$this->db->select('b.harga_beli as harga_beli_terakhir');
		$this->db->select($this->select);
		$this->db->from($mt_depo_stok.' d');
		$this->db->join($mt_barang.' a' ,'d.kode_brg=a.kode_brg','left');
		$this->db->join($mt_rekap_stok.' b' ,'b.kode_brg=d.kode_brg','left');
		$this->db->join('( SELECT * FROM '.$tc_kartu_stok.' WHERE id_kartu IN (SELECT MAX(id_kartu) AS id_kartu FROM '.$tc_kartu_stok.' WHERE tgl_input <= '."'".$date."'".' AND tgl_input is not null AND kode_bagian='."'".$kd_bagian."'".' GROUP BY kode_brg) AND kode_bagian='."'".$kd_bagian."'".' ) AS kartu_stok', 'kartu_stok.kode_brg=d.kode_brg','left');
		

		if(isset($_GET['search_by']) AND $_GET['search_by'] != '' AND isset($_GET['key']) AND $_GET['key'] != '' ){
			$this->db->like($_GET['search_by'], $_GET['key']);
		}

		if( $_GET['flag'] == 'non_medis'){
			$this->db->where( ' (d.kode_bagian = '."'".$kd_bagian."'".' OR d.kode_bagian IS NULL ) ' );
		}else{
			$this->db->where('d.kode_bagian', $kd_bagian);
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
		if($table == 'tc_permohonan_det_log'){
			$this->db->where_in('id', $id);
			$this->db->delete('tc_permohonan_det_log');
			return true;
		}else{
			$this->db->where_in('id_tc_permohonan_det', $id)->delete( $table );
			return true;
		}
	}

	
}
