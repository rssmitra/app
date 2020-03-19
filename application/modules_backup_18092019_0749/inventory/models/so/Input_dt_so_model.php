<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Input_dt_so_model extends CI_Model {

	var $table = 'mt_depo_stok_v';
	var $column = array('mt_depo_stok_v.nama_brg');
	var $select = 'kode_depo_stok,kode_brg, nama_brg, kode_bagian, nama_bagian, jml_sat_kcl, satuan_kecil,satuan_besar, mt_golongan.nama_golongan,mt_sub_golongan.nama_sub_golongan, status_aktif';
	var $order = array('mt_depo_stok_v.kode_sub_golongan' => 'DESC', 'mt_depo_stok_v.nama_brg' => 'ASC');

	/*non medis*/
	var $table_nm = 'mt_depo_stok_nm_v';
	var $column_nm = array('mt_depo_stok_nm_v.nama_brg');
	var $select_nm = 'kode_depo_stok,kode_brg, nama_brg, mt_depo_stok_nm_v.kode_bagian, nama_bagian, jml_sat_kcl, satuan_kecil,satuan_besar,nama_sub_golongan as nama_golongan, is_active as status_aktif';
	var $order_nm = array('mt_depo_stok_nm_v.nama_sub_golongan' => 'ASC', 'mt_depo_stok_nm_v.nama_brg' => 'ASC');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _main_query(){

		$this->db->select($this->select);
		$this->db->from($this->table);
		$this->db->join('mt_golongan', 'mt_golongan.kode_golongan=mt_depo_stok_v.kode_golongan');
		$this->db->join('mt_sub_golongan', 'mt_sub_golongan.kode_sub_gol=mt_depo_stok_v.kode_sub_golongan');
		$this->db->where('kode_bagian', $_GET['bag']);
		$this->db->where('nama_brg LIKE '."'%'".' ');
		$this->db->group_by($this->select);
		$this->db->order_by( 'mt_depo_stok_v.nama_brg', 'ASC' );
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
		
		/*if(isset($_POST['order']))
		{
			$this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($this->order))
		{
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}*/
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


	private function _main_query_nm(){

		$this->db->select($this->select_nm);
		$this->db->from($this->table_nm);
		$this->db->join('mt_bagian', 'mt_bagian.kode_bagian=mt_depo_stok_nm_v.kode_bagian');
		$this->db->where('mt_depo_stok_nm_v.kode_bagian', $_GET['bag']);
		$this->db->where('nama_brg LIKE '."'%'".' ');
		$this->db->order_by('nama_golongan', 'ASC');
		$this->db->order_by('nama_brg', 'ASC');
	}

	private function _get_datatables_query_nm()
	{
		
		$this->_main_query_nm();

		$i = 0;
	
		foreach ($this->column_nm as $item) 
		{
			if($_POST['search']['value'])
				($i===0) ? $this->db->like($item, $_POST['search']['value']) : $this->db->or_like($item, $_POST['search']['value']);
			$column[$i] = $item;
			$i++;
		}
		
		/*if(isset($_POST['order']))
		{
			$this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($this->order_nm))
		{
			$order = $this->order_nm;
			$this->db->order_by(key($order), $order[key($order)]);
		}*/
	}
	
	function get_datatables_nm()
	{
		$this->_get_datatables_query_nm();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		//print_r($this->db->last_query());die;
		return $query->result();
	}

	function count_filtered_nm()
	{
		$this->_get_datatables_query_nm();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all_nm()
	{
		$this->_main_query_nm();
		return $this->db->count_all_results();
	}

	public function get_agenda_by_id($agenda_so_id){
		return $this->db->get_where('tc_stok_opname_agenda', array('agenda_so_id' => $agenda_so_id) )->row();
	}

	public function save_dt_so_nm(){
		$this->load->library('inventory_lib');
		/*cek stok terakhir*/
		$last_stok = $this->db->get_where('mt_depo_stok_nm_v', array('kode_bagian' => $_POST['kode_bagian'], 'kode_brg' => $_POST['kode_brg']) )->row();

		/*cek harga pembelian terakhir*/
		$harga_terakhir = $this->db->select('CAST(harga_beli as int) as harga')->get_where('mt_rekap_stok_nm', array('kode_brg' => $_POST['kode_bagian'], 'kode_bagian_gudang' => '070101') )->row();

		$fld = array();
		$fld['tgl_stok_opname'] = date('Y-m-d H:i:s'); // $tgl_stok_opname;
		$fld['agenda_so_id'] = $_POST['agenda_so_id'];
		$fld['kode_bagian'] = $_POST['kode_bagian'];
		$fld['kode_brg'] = $_POST['kode_brg'];
		$fld['stok_sebelum'] = $last_stok->jml_sat_kcl;
		$fld['stok_sekarang'] = $_POST['input_stok_so'];
		$fld['nama_petugas'] = $this->session->userdata('session_input_so')['nama_pegawai'];
		$fld['harga_pembelian_terakhir'] = $harga_terakhir->harga;

		/*cek dulu sudah ada atau blm sebelumnya*/
		$cek_existing = $this->cek_input_stok_before('tc_stok_opname_nm', array('kode_brg' => $_POST['kode_brg'], 'kode_bagian' => $_POST['kode_bagian'], 'agenda_so_id' => $_POST['agenda_so_id']) );
		if( $cek_existing->num_rows() > 0 ){
			/*jika ada maka update*/
			$this->db->update('tc_stok_opname_nm', $fld, array('kode_brg' => $_POST['kode_brg'], 'kode_bagian' => $_POST['kode_bagian'], 'agenda_so_id' => $_POST['agenda_so_id']) );
			$last_id_tc_so = $cek_existing->row()->id_tc_stok_opname;
		}else{
			/*then insert*/
			$this->db->insert("tc_stok_opname_nm", $fld);
			$last_id_tc_so = $this->db->insert_id();
		}

		/*config for inventory lib*/
		$config = array(
			'id_tc_stok_opname' => $last_id_tc_so,
			'agenda_so_id' => $_POST['agenda_so_id'],
			'last_stok' => $last_stok->jml_sat_kcl,
			'new_stok' => $_POST['input_stok_so'],
			'kode_bagian' => $_POST['kode_bagian'],
			'kode_brg' => $_POST['kode_brg'],
			'table_depo_flag' => 'mt_depo_stok_nm',
			'table_kartu_flag' => 'tc_kartu_stok_nm',
			'petugas' => $this->session->userdata('session_input_so')['nama_pegawai'],
		);
		/*catat kartu stok*/
		$this->inventory_lib->save_mutasi_stok($config);

		return true;

	}

	public function save_dt_so(){
		$this->load->library('inventory_lib');
		/*cek stok terakhir*/
		$last_stok = $this->db->get_where('mt_depo_stok_v', array('kode_bagian' => $_POST['kode_bagian'], 'kode_brg' => $_POST['kode_brg']) )->row();

		/*cek harga pembelian terakhir*/
		$harga_terakhir = $this->db->select('CAST(harga_beli as int) as harga')->get_where('mt_rekap_stok')->row();
		$fld = array();
		$fld['agenda_so_id'] = $_POST['agenda_so_id']; // agenda_so_id
		$fld['tgl_stok_opname'] = date('Y-m-d H:i:s'); // $tgl_stok_opname;
		$fld['kode_bagian'] = $_POST['kode_bagian'];
		$fld['kode_brg'] = $_POST['kode_brg'];
		$fld['stok_sebelum'] = $last_stok->jml_sat_kcl;
		$fld['stok_sekarang'] = $_POST['input_stok_so'];
		$fld['nama_petugas'] = $this->session->userdata('session_input_so')['nama_pegawai'];
		$fld['harga_pembelian_terakhir'] = $harga_terakhir->harga;
		/*cek dulu sudah ada atau blm sebelumnya*/
		$cek_existing = $this->cek_input_stok_before('tc_stok_opname', array('kode_brg' => $_POST['kode_brg'], 'kode_bagian' => $_POST['kode_bagian'], 'agenda_so_id' => $_POST['agenda_so_id']) );
		if( $cek_existing->num_rows() > 0 ){
			/*jika ada maka update*/
			$this->db->update('tc_stok_opname', $fld, array('kode_brg' => $_POST['kode_brg'], 'kode_bagian' => $_POST['kode_bagian'], 'agenda_so_id' => $_POST['agenda_so_id']) );
			$last_id_tc_so = $cek_existing->row()->id_tc_stok_opname;
		}else{
			/*then insert*/
			$this->db->insert("tc_stok_opname", $fld);
			$last_id_tc_so = $this->db->insert_id();
		}

		$config = array(
			'id_tc_stok_opname' => $last_id_tc_so,
			'agenda_so_id' => $_POST['agenda_so_id'],
			'last_stok' => $last_stok->jml_sat_kcl,
			'new_stok' => $_POST['input_stok_so'],
			'kode_bagian' => $_POST['kode_bagian'],
			'kode_brg' => $_POST['kode_brg'],
			'table_depo_flag' => 'mt_depo_stok',
			'table_kartu_flag' => 'tc_kartu_stok',
			'petugas' => $this->session->userdata('session_input_so')['nama_pegawai'],
		);
		/*catat kartu stok*/
		$this->inventory_lib->save_mutasi_stok($config);


		

		return true;

	}

	public function cek_input_stok_before($table, $where){
		$dt = $this->db->get_where($table, $where);
		return $dt;
	}

	public function update_status_brg($table, $field_data, $where){

		return $this->db->update($table, $field_data, $where);

	}



}
