<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Input_dt_so_model extends CI_Model {

	var $table = 'mt_depo_stok_v';
	var $column = array('mt_depo_stok_v.nama_brg');
	var $select = 'kode_depo_stok, mt_depo_stok_v.kode_brg, nama_brg, mt_depo_stok_v.kode_bagian, nama_bagian, jml_sat_kcl, satuan_kecil,satuan_besar, mt_golongan.nama_golongan,mt_sub_golongan.nama_sub_golongan, nama_jenis, nama_layanan, nama_petugas, tgl_stok_opname, stok_exp, will_stok_exp, stok_sekarang, stok_sebelum, path_image';

	/*non medis*/
	var $table_nm = 'mt_depo_stok_nm_v';
	var $column_nm = array('mt_depo_stok_nm_v.nama_brg');
	var $select_nm = 'kode_depo_stok,mt_depo_stok_nm_v.kode_brg, nama_brg, mt_depo_stok_nm_v.kode_bagian, nama_bagian, jml_sat_kcl, satuan_kecil,satuan_besar, nama_petugas, tgl_stok_opname, stok_exp, will_stok_exp, stok_sekarang, stok_sebelum';

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _main_query(){

		$this->db->select('mt_depo_stok_v.is_active as status_aktif');
		$this->db->select($this->select);
		$this->db->from($this->table);
		$this->db->join('mt_golongan', 'mt_golongan.kode_golongan=mt_depo_stok_v.kode_golongan');
		$this->db->join('mt_sub_golongan', 'mt_sub_golongan.kode_sub_gol=mt_depo_stok_v.kode_sub_golongan');
		$this->db->join('(SELECT * FROM tc_stok_opname WHERE agenda_so_id='.$this->session->userdata('session_input_so')['agenda_so_id'].' AND kode_bagian='."'".$_GET['bag']."'".') as agenda_so', 'agenda_so.kode_brg='.$this->table.'.kode_brg','left');
		$this->db->where($this->table.'.kode_bagian', $_GET['bag']);
		$this->db->where($this->table.'.nama_brg LIKE '."'%'".' ');
		
		
	}

	private function _get_datatables_query()
	{
		
		$this->_main_query();

		if( isset($_GET['gol']) AND $_GET['gol'] != '' ){
			$this->db->where('kode_kategori', $_GET['gol']);
		}

		if( isset($_GET['rak']) AND $_GET['rak'] != '' ){
			$this->db->where('rak', $_GET['rak']);
		}

		$this->db->group_by('is_active');
		$this->db->group_by($this->select);

		$this->db->order_by( 'nama_brg','ASC' );
		$this->db->order_by( 'nama_jenis','ASC' );
		$this->db->order_by( 'nama_layanan','ASC' );

		$i = 0;
	
		foreach ($this->column as $item) 
		{
			if($_POST['search']['value'])
				($i===0) ? $this->db->like($item, $_POST['search']['value']) : $this->db->or_like($item, $_POST['search']['value']);
			$column[$i] = $item;
			$i++;
		}
		
		$this->db->group_by($this->select);
		
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


	private function _main_query_nm(){

		$this->db->select('mt_depo_stok_nm_v.is_active as status_aktif, nama_sub_golongan as nama_golongan');
		$this->db->select($this->select_nm);
		$this->db->from($this->table_nm);
		$this->db->join('mt_bagian', 'mt_bagian.kode_bagian=mt_depo_stok_nm_v.kode_bagian');
		$this->db->join('(SELECT * FROM tc_stok_opname_nm WHERE agenda_so_id='.$this->session->userdata('session_input_so')['agenda_so_id'].' AND kode_bagian='."'".$_GET['bag']."'".') as agenda_so', 'agenda_so.kode_brg='.$this->table_nm.'.kode_brg','left');
		$this->db->where('mt_depo_stok_nm_v.kode_bagian', $_GET['bag']);
		$this->db->where('nama_brg LIKE '."'%'".' ');
		
		$this->db->order_by( 'nama_brg','ASC' );
		$this->db->order_by( 'nama_sub_golongan','ASC' );
		
		$this->db->group_by('is_active, nama_sub_golongan');
		$this->db->group_by($this->select_nm);
	}

	private function _get_datatables_query_nm()
	{
		
		$this->_main_query_nm();

		if( isset($_GET['gol']) AND $_GET['gol'] != '' ){
			$this->db->where('kode_golongan', $_GET['gol']);
		}

		if( isset($_GET['rak']) AND $_GET['rak'] != '' ){
			$this->db->where('rak', $_GET['rak']);
		}

		$i = 0;
	
		foreach ($this->column_nm as $item) 
		{
			if($_POST['search']['value'])
				($i===0) ? $this->db->like($item, $_POST['search']['value']) : $this->db->or_like($item, $_POST['search']['value']);
			$column[$i] = $item;
			$i++;
		}
		
	}
	
	function get_datatables_nm()
	{
		$this->_get_datatables_query_nm();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		// print_r($this->db->last_query());die;
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
		$this->db->select('AVG(CAST(harga_satuan AS INT))as harga');
		$this->db->from('tc_po_nm_det');
		$this->db->where('id_tc_po_det IN (select top 3 id_tc_po_det from tc_po_nm_det, tc_po_nm where tc_po_nm_det.id_tc_po=tc_po_nm.id_tc_po AND kode_brg='."'".$_POST['kode_brg']."'".' order by tgl_po DESC) ');
		$harga_terakhir = $this->db->get()->row();

		if(empty($harga_terakhir->harga)){
			$dt_brg = $this->db->get_where('mt_barang_nm', array('kode_brg' => $_POST['kode_brg']) )->row();
		}

		$fld = array();
		$fld['tgl_stok_opname'] = date('Y-m-d H:i:s'); // $tgl_stok_opname;
		$fld['agenda_so_id'] = $_POST['agenda_so_id'];
		$fld['kode_bagian'] = $_POST['kode_bagian'];
		$fld['kode_brg'] = $_POST['kode_brg'];
		$fld['stok_sebelum'] = $last_stok->jml_sat_kcl;
		$fld['stok_sekarang'] = $_POST['input_stok_so'];
		$fld['stok_exp'] = ($_POST['exp_stok'])?$_POST['exp_stok']:0;
		$fld['will_stok_exp'] = ($_POST['will_exp_stok'])?$_POST['will_exp_stok']:0;
		$fld['set_status_aktif'] = $_POST['status_aktif'];
		$fld['nama_petugas'] = $this->session->userdata('session_input_so')['nama_pegawai'];
		$fld['harga_pembelian_terakhir'] = ($harga_terakhir->harga)?$harga_terakhir->harga:$dt_brg->harga_beli;

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
		$this->db->select('AVG(CAST(harga_satuan AS INT))as harga');
		$this->db->from('tc_po_det');
		$this->db->where('id_tc_po_det IN (select top 3 id_tc_po_det from tc_po_det, tc_po where tc_po_det.id_tc_po=tc_po.id_tc_po AND kode_brg='."'".$_POST['kode_brg']."'".' order by tgl_po DESC) ');
		$harga_terakhir = $this->db->get()->row();
		
		$fld = array();
		$fld['agenda_so_id'] = $_POST['agenda_so_id']; // agenda_so_id
		$fld['tgl_stok_opname'] = date('Y-m-d H:i:s'); // $tgl_stok_opname;
		$fld['kode_bagian'] = $_POST['kode_bagian'];
		$fld['kode_brg'] = $_POST['kode_brg'];
		if( $_POST['input_stok_so'] >= 0 ){
			$fld['stok_sekarang'] = $_POST['input_stok_so'];
		}
		$fld['stok_exp'] = ($_POST['exp_stok'])?$_POST['exp_stok']:0;
		$fld['will_stok_exp'] = ($_POST['will_exp_stok'])?$_POST['will_exp_stok']:0;
		$fld['nama_petugas'] = $this->session->userdata('session_input_so')['nama_pegawai'];
		$fld['harga_pembelian_terakhir'] = $harga_terakhir->harga;
		$fld['set_status_aktif'] = $_POST['status_aktif'];
		// print_r($fld);die;
		/*cek dulu sudah ada atau blm sebelumnya*/
		$cek_existing = $this->cek_input_stok_before('tc_stok_opname', array('kode_brg' => $_POST['kode_brg'], 'kode_bagian' => $_POST['kode_bagian'], 'agenda_so_id' => $_POST['agenda_so_id']) );

		if( $cek_existing->num_rows() > 0 ){
			/*jika ada maka update*/
			$this->db->update('tc_stok_opname', $fld, array('kode_brg' => $_POST['kode_brg'], 'kode_bagian' => $_POST['kode_bagian'], 'agenda_so_id' => $_POST['agenda_so_id']) );
			$last_id_tc_so = $cek_existing->row()->id_tc_stok_opname;
		}else{
			/*then insert*/
			$fld['stok_sebelum'] = $last_stok->jml_sat_kcl;
			$this->db->insert("tc_stok_opname", $fld);
			$last_id_tc_so = $this->db->insert_id();
		}

		if( $_POST['input_stok_so'] >= 0 ){
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
		}
		
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
