<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class E_resep_model extends CI_Model {

	var $table = 'fr_listpesanan_v';
	var $column = array('nama_pasien', 'no_mr');
	var $select = 'fr_listpesanan_v.kode_bagian, fr_listpesanan_v.kode_bagian_asal, tgl_pesan, status_tebus, jumlah_r, lokasi_tebus, keterangan, fr_listpesanan_v.no_registrasi, fr_listpesanan_v.no_kunjungan, fr_listpesanan_v.kode_perusahaan, kode_klas, fr_listpesanan_v.kode_kelompok, nama_pegawai, nama_lokasi, nama_bagian, fr_listpesanan_v.kode_dokter, fr_listpesanan_v.kode_pesan_resep, fr_listpesanan_v.no_mr, fr_listpesanan_v.nama_pasien, mt_perusahaan.nama_perusahaan, mt_nasabah.nama_kelompok, fr_listpesanan_v.resep_farmasi, no_sep, status_resep';

	var $order = array('kode_pesan_resep' => 'ASC');

	public function __construct()
	{
		parent::__construct();
	}



	private function _main_query(){

		$this->db->select($this->select);
		$this->db->select('(SELECT top 1 diagnosa_akhir FROM th_riwayat_pasien WHERE no_kunjungan=fr_listpesanan_v.no_kunjungan) as diagnosa_akhir');
		$this->db->from($this->table);
		$this->db->join('mt_perusahaan','mt_perusahaan.kode_perusahaan=fr_listpesanan_v.kode_perusahaan','left');
		$this->db->join('mt_nasabah','mt_nasabah.kode_kelompok=fr_listpesanan_v.kode_kelompok','left');
		$this->db->join('tc_registrasi','tc_registrasi.no_registrasi=fr_listpesanan_v.no_registrasi','left');
		$this->db->where('kode_pesan_resep IN (select kode_pesan_resep from fr_tc_pesan_resep_detail group by kode_pesan_resep)');
	}

	private function _get_datatables_query()
	{
		
		$this->_main_query();

		if(isset($_GET['no_mr']) AND $_GET['no_mr']!=0 ){
			if($_GET['no_mr']!='' or $_GET['no_mr']!=0){
				$this->db->where('fr_listpesanan_v.no_mr', $_GET['no_mr']);
			}
		}

		if(isset($_GET['search_by']) AND isset($_GET['keyword'])){
			$this->db->like('fr_listpesanan_v.'.$_GET['search_by'].'', $_GET['keyword']);
		}

		if(isset($_GET['klinik'])){
			if($_GET['klinik']!='' or $_GET['klinik']!=0){
				$this->db->where('fr_listpesanan_v.kode_bagian', (int)$_GET['klinik']);
			}
		}

		if(isset($_GET['dokter']) AND $_GET['dokter']!=0 ){
			if($_GET['dokter']!='' or $_GET['dokter']!=0){
				$this->db->where('fr_listpesanan_v.kode_dokter', $_GET['dokter']);
			}
		}

		if (isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' or isset($_GET['to_tgl']) AND $_GET['to_tgl'] != '') {
            $this->db->where("CAST(fr_listpesanan_v.tgl_pesan as DATE) >= '".$_GET['from_tgl']."'" );
            $this->db->where("CAST(fr_listpesanan_v.tgl_pesan as DATE) <= '".$_GET['to_tgl']."'" );
        }else{
        	if( $_GET['flag']=='RJ' ){
				$this->db->where('DATEDIFF(Hour, tgl_pesan, getdate()) <= 24');			
        	}else{
				$this->db->where('DATEDIFF(Hour, tgl_pesan, getdate()) <= 72');			
        	}
        }

        // default for this modul
        // $this->db->where('(status_tebus is null or status_tebus = 0)');

		$i = 0;
		$col_str = '';
		foreach ($this->column as $item) 
		{
			if($_POST['search']['value'])
				$col_str .= ($i===0) ? $item." LIKE '%".$_POST['search']['value']."%' " : "OR ".$item." LIKE '%".$_POST['search']['value']."%'";
			$column[$i] = $item;
			$i++;
		}

		if( $col_str != ''){
			$this->db->where('('.$col_str.')');
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

	function get_data()
	{
		$this->_main_query();
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
		$this->_get_datatables_query();
		return $this->db->count_all_results();
	}

	public function get_by_id($id)
	{
		$this->_main_query();
		
		if(is_array($id)){
			$this->db->where_in(''.$this->table.'.kode_pesan_resep',$id);
			$query = $this->db->get();
			return $query->result();
		}else{
			$this->db->where(''.$this->table.'.kode_pesan_resep',$id);
			$query = $this->db->get();
			return $query->row();
		}
		
	}

	public function save($table, $data)
	{
		/*insert tc_registrasi*/
		$this->db->insert($table, $data);
		return $this->db->insert_id();;
	}

	public function update($table, $data, $where)
	{
		$this->db->update($table, $data, $where);
		return $this->db->affected_rows();
	}

	public function delete_by_id($id)
	{
		$get_data = $this->get_by_id($id);
		$this->db->where_in(''.$this->table.'.no_registrasi', $id);
		return $this->db->update($this->table, array('is_deleted' => 'Y', 'is_active' => 'N'));
	}
	
	public function get_cart_resep($kode_pesan_resep){
		return $this->db->order_by('id', 'ASC')->get_where('fr_tc_pesan_resep_detail', array('kode_pesan_resep' => $kode_pesan_resep, 'parent' => '0') )->result();
	}

	public function get_cart_resep_for_template($kode_pesan_resep){
		return $this->db->order_by('id', 'ASC')->get_where('fr_tc_pesan_resep_detail', array('kode_pesan_resep' => $kode_pesan_resep) )->result();
	}

	public function get_cart_detail_by_template_id($template_id){
		return $this->db->order_by('id', 'ASC')->get_where('fr_tc_template_resep_detail', array('id_template' => $template_id) )->result();
	}

	public function get_template_resep($kode_dokter){
		return $this->db->order_by('id', 'ASC')->get_where('fr_tc_template_resep', array('kode_dokter' => $kode_dokter) )->result();
	}

	public function get_row_template($id){
		return $this->db->get_where('fr_tc_template_resep', array('id' => $id) )->row();
	}



}
