<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adm_kasir_model extends CI_Model {

	var $table = 'tc_trans_pelayanan';
	var $column = array('a.no_registrasi');
	var $select = 'a.no_registrasi, a.no_mr, b.tgl_jam_masuk, b.kode_perusahaan, b.kode_kelompok, b.kode_dokter, b.kode_bagian_masuk, c.nama_pasien, d.nama_bagian, e.nama_perusahaan';
	var $order = array('a.no_registrasi' => 'DESC');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _main_query(){

		$this->db->select($this->select);
		$this->db->select('CAST((SUM(bill_rs) + SUM(bill_dr1) + SUM(bill_dr2) + SUM(bill_dr3)) AS INT) as total');
		$this->db->from($this->table.' a');
		$this->db->join('tc_registrasi b','b.no_registrasi=a.no_registrasi','left');
		$this->db->join('mt_master_pasien c','c.no_mr=b.no_mr','left');
		$this->db->join('mt_bagian d','d.kode_bagian=b.kode_bagian_masuk','left');
		$this->db->join('mt_perusahaan e','e.kode_perusahaan=b.kode_perusahaan','left');
		$this->db->where('a.no_registrasi IN (select no_registrasi from tc_registrasi where YEAR(tgl_jam_masuk)='.date('Y').')');
		$this->db->where('a.kode_tc_trans_kasir IS NULL');

		if(isset($_GET['date']) AND $_GET['date'] != 0){
			$this->db->where('DAY(b.tgl_jam_masuk)', 120);
		}

		if(isset($_GET['month']) AND $_GET['month'] != 0){
			$this->db->where('MONTH(b.tgl_jam_masuk)', $_GET['month']);
		}else{
			$this->db->where('MONTH(b.tgl_jam_masuk)', $_POST['month']);
		}

		if(isset($_GET['year']) AND $_GET['year'] != 0){
			$this->db->where('YEAR(b.tgl_jam_masuk)', $_GET['year']);
		}else{
			$this->db->where('YEAR(b.tgl_jam_masuk)', $_POST['year']);
		}

		if($_GET['flag']=='bpjs'){
			$this->db->where('b.kode_perusahaan', 120);
		}
		if($_GET['flag']=='umum'){
			$this->db->where('b.kode_perusahaan != 120');
		}
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

	public function get_detail_brg_permintaan($flag, $id){
		$mt_barang = ($flag=='non_medis')?'mt_barang_nm':'mt_barang';
		$table = ($flag=='non_medis')?$this->table_nm:$this->table;

		$this->db->from(''.$table.'_det');
		$this->db->join($table, ''.$table.'.id_tc_permohonan='.$table.'_det.id_tc_permohonan', 'left');
		$this->db->join($mt_barang, ''.$mt_barang.'.kode_brg='.$table.'_det.kode_brg', 'left');
		$this->db->where(''.$table.'_det.id_tc_permohonan', $id);
		return $this->db->get()->result();
	}


}
