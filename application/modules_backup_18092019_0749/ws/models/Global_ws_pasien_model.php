<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Global_ws_pasien_model extends CI_Model {

	var $table = 'mt_master_pasien';

	var $column = array('mt_master_pasien.no_mr','mt_master_pasien.no_ktp','mt_master_pasien.nama_pasien','mt_master_pasien.tgl_lhr','mt_master_pasien.tempat_lahir','mt_master_pasien.almt_ttp_pasien','mt_master_pasien.tlp_almt_ttp', 'mt_master_pasien.jen_kelamin', 'mt_master_pasien.no_hp', 'mt_master_pasien.title', 'mt_master_pasien.status_meninggal', 'mt_master_pasien.kode_perusahaan
		','mt_perusahaan.nama_perusahaan','no_kartu_bpjs');

	var $select = 'mt_master_pasien.no_mr,mt_master_pasien.no_ktp,mt_master_pasien.nama_pasien,mt_master_pasien.tgl_lhr,mt_master_pasien.tempat_lahir,mt_master_pasien.almt_ttp_pasien,mt_master_pasien.tlp_almt_ttp,mt_master_pasien.jen_kelamin,mt_master_pasien.no_hp,mt_master_pasien.title,mt_master_pasien.
	status_meninggal,mt_master_pasien.kode_perusahaan,mt_perusahaan.nama_perusahaan,no_kartu_bpjs, mt_master_pasien.nama_panggilan, mt_master_pasien.nama_kel_pasien, mt_master_pasien.no_ktp, mt_master_pasien.almt_ttp_pasien, mt_master_pasien.id_dc_propinsi, mt_master_pasien.id_dc_kota, mt_master_pasien.id_dc_kecamatan, mt_master_pasien.id_dc_kelurahan, mt_master_pasien.kode_pos, kode_kelompok';

	var $order = array('mt_master_pasien.nama_pasien' => 'ASC');

	public function __construct()
	
	{
		
		parent::__construct();
	
	}

	private function _main_query($params){
		
		$this->db->select($this->select);
		
		$this->db->from($this->table);

		$this->db->join('mt_perusahaan','mt_perusahaan.kode_perusahaan=mt_master_pasien.kode_perusahaan','left');
		
		/*check level user*/
		$this->authuser->filtering_data_by_level_user($this->table, $params);
	
	}

	private function _get_datatables_query($params='')
	
	{
		
		$this->_main_query();

		$i = 0;

		foreach ($this->column as $item) 
		
		{
			
			if($_POST['search']['value'])
				($i===0) ? $this->db->like($item, $_POST['search']['value']) : $this->db->or_like($item
					, $_POST['search']['value']);
			
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

	function get_datatables($params='')
	
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
			
			$this->db->where_in(''.$this->table.'.id_mt_master_pasien',$id);
			
			$query = $this->db->get();
			
			return $query->result();
		
		}else{
			
			$this->db->where(''.$this->table.'.id_mt_master_pasien',$id);
			
			$query = $this->db->get();
			
			return $query->row();
		
		}
	
	}

	public function get_by_mr($mr,$user_id)
	
	{
		
		$this->_main_query($user_id);
		
		$this->db->where(''.$this->table.'.no_mr', $mr);
			
		$query = $this->db->get();
		
		return $query->row();
	
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
		
		$this->db->where_in(''.$this->table.'.id_mt_master_pasien', $id);
		
		return $this->db->update($this->table, array('is_deleted' => 'Y', 'is_active' => 'N'));
	
	}

	public function search_pasien_by_keyword($keyword, $field)
	
	{
		
		$this->_main_query();

		$i = 0;

		foreach ($field as $item) 
		
		{

			if($keyword)
				($i===0) ? $this->db->like($item, $keyword) : $this->db->or_like($item
					, $keyword);
			
			$column[$i] = $item;
			
			$i++;
		
		}

		if(isset($this->order))
		
		{
			
			$order = $this->order;
			
			$this->db->order_by(key($order), $order[key($order)]);
		
		}


		return $this->db->get()->result();

		
	
	}

	public function query_get_riwayat_pasien($select, $column, $mr)
	
	{

		$this->db->select($select);

		$this->db->from( 'tc_registrasi' );

		$this->db->join('mt_dokter_v','mt_dokter_v.kode_dokter=tc_registrasi.kode_dokter','left');

		$this->db->join('mt_bagian','mt_bagian.kode_bagian=tc_registrasi.kode_bagian_masuk','left');

		$this->db->join('mt_perusahaan','mt_perusahaan.kode_perusahaan=tc_registrasi.kode_perusahaan','left');

		$this->db->where( 'tc_registrasi.no_mr', $mr );

		$this->db->group_by( $select );

	}

	private function _get_datatables_query_riwayat_pasien($select, $column, $mr)
	
	{
		
		$this->query_get_riwayat_pasien($select, $column, $mr);

		$i = 0;

		foreach ($column as $item) 
		
		{
			
			if($_POST['search']['value'])
				($i===0) ? $this->db->like($item, $_POST['search']['value']) : $this->db->or_like($item
					, $_POST['search']['value']);
			
			$column[$i] = $item;
			
			$i++;
		
		}

		foreach ($column as $item) 
		
		{
			
			if($_POST['search']['value'])
				($i===0) ? $this->db->like($item, $_POST['search']['value']) : $this->db->or_like($item
					, $_POST['search']['value']);
			
			$column[$i] = $item;
			
			$i++;
		
		}


	}

	function get_riwayat_pasien($column, $mr)
	
	{
		
		$this->_get_datatables_query_riwayat_pasien(join($column,','), $column, $mr);
		
		if($_POST['length'] != -1)
		
		$this->db->limit($_POST['length'], $_POST['start']);
		
		$query = $this->db->get();
		
		return $query->result();
	
	}

	function count_filtered_riwayat_pasien($column, $mr)
	
	{
		
		$this->_get_datatables_query_riwayat_pasien(join($column,','), $column, $mr);
		
		$query = $this->db->get();
		
		return $query->num_rows();
	
	}

	function get_data_pasien_bpjs($params)
	
	{
		if(!empty($params->no_kartu_bpjs)){

			$service_name = "Peserta/nokartu/".$params->no_kartu_bpjs."/tglSEP/".$this->tanggal->sqlDateForm(date('Y-m-d'))."";

			$response_ws = $this->Ws_index->getData($service_name);
			
			return $response_ws;
			
		}else{

			return false;

		}
	
	}

	public function query_get_riwayat_transaksi_pasien($select, $column, $mr)
	
	{

		$this->db->select($select);

		$this->db->from( 'tc_trans_kasir' );

		$this->db->join('tc_registrasi','tc_registrasi.no_registrasi=tc_trans_kasir.no_registrasi','left');

		$this->db->join('mt_dokter_v','mt_dokter_v.kode_dokter=tc_registrasi.kode_dokter','left');

		$this->db->join('mt_bagian','mt_bagian.kode_bagian=tc_registrasi.kode_bagian_masuk','left');

		$this->db->join('mt_perusahaan','mt_perusahaan.kode_perusahaan=tc_registrasi.kode_perusahaan','left');

		$this->db->where( 'tc_registrasi.no_mr', $mr );

		$this->db->group_by( $select );

	}

	private function _get_datatables_query_riwayat_transaksi_pasien($select, $column, $mr)
	
	{
		
		$this->query_get_riwayat_transaksi_pasien($select, $column, $mr);

		$i = 0;

		foreach ($column as $item) 
		
		{
			
			if($_POST['search']['value'])
				($i===0) ? $this->db->like($item, $_POST['search']['value']) : $this->db->or_like($item
					, $_POST['search']['value']);
			
			$column[$i] = $item;
			
			$i++;
		
		}

		foreach ($column as $item) 
		
		{
			
			if($_POST['search']['value'])
				($i===0) ? $this->db->like($item, $_POST['search']['value']) : $this->db->or_like($item
					, $_POST['search']['value']);
			
			$column[$i] = $item;
			
			$i++;
		
		}


	}

	function get_riwayat_transaksi_pasien($column, $mr)
	
	{
		
		$this->_get_datatables_query_riwayat_transaksi_pasien(join($column,','), $column, $mr);
		
		if($_POST['length'] != -1)
		
		$this->db->limit($_POST['length'], $_POST['start']);
		
		$query = $this->db->get(); //print_r($this->db->last_query());die;
		
		return $query->result();
	
	}

	function count_filtered_riwayat_transaksi_pasien($column, $mr)
	
	{
		
		$this->_get_datatables_query_riwayat_transaksi_pasien(join($column,','), $column, $mr);
		
		$query = $this->db->get();
		
		return $query->num_rows();
	
	}



}
