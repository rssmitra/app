<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pl_pelayanan_vk_model extends CI_Model {

	var $table = 'ri_pasien_vk_v';
	var $column = array('ri_pasien_vk_v.nama_pasien');
	var $select = 'ri_pasien_vk_v.*, mt_perusahaan.nama_perusahaan, mt_nasabah.nama_kelompok, mt_karyawan.nama_pegawai, nama_bagian, nama_klas';

	var $order = array('ri_pasien_vk_v.no_kunjungan' => 'desc');

	public function __construct()
	{
		parent::__construct();
	}

	public function save($table, $data)
	{
		/*insert tc_registrasi*/
		$this->db->insert($table, $data);
		
		return $this->db->insert_id();;
	}

	private function _main_query(){
		
		$this->db->select($this->select);
		$this->db->from($this->table);
		// $this->db->join('tc_kunjungan',''.$this->table.'.no_kunjungan=tc_kunjungan.no_kunjungan','left');
		$this->db->join('mt_karyawan','mt_karyawan.kode_dokter=ri_pasien_vk_v.dr_merawat','left');
		$this->db->join('mt_perusahaan','ri_pasien_vk_v.kode_perusahaan=mt_perusahaan.kode_perusahaan','left');
		$this->db->join('mt_nasabah','ri_pasien_vk_v.kode_kelompok=mt_nasabah.kode_kelompok','left');
		$this->db->join('mt_bagian','mt_bagian.kode_bagian=ri_pasien_vk_v.bag_pas','left');
		$this->db->join('mt_klas','mt_klas.kode_klas=ri_pasien_vk_v.kelas_pas','left');

	}

	private function _get_datatables_query()
	{
		$range_date = 
		$this->_main_query();

		if(isset($_GET['search_by']) AND isset($_GET['keyword'])){
			if( $_GET['keyword'] != '' ){
				$this->db->like('ri_pasien_vk_v.'.$_GET['search_by'].'', $_GET['keyword']);
			}
		}

		if (isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' || isset($_GET['to_tgl']) AND $_GET['to_tgl'] != '') {
			$this->db->where("CAST(ri_pasien_vk_v.tgl_masuk AS DATE) between '".$_GET['from_tgl']."' and '".$_GET['to_tgl']."'");					
        }else{
			if(isset($_GET['flag']) AND $_GET['flag'] == 'history'){
				$this->db->where("ri_pasien_vk_v.tgl_keluar is not null");
				$this->db->where('DATEDIFF(Day, ri_pasien_vk_v.tgl_masuk, getdate()) <= 30');	
			}else{
				// $this->db->where("flag_vk", 0);
				$this->db->where("ri_pasien_vk_v.tgl_keluar is null OR flag_vk=0");
			}
		}

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
		$this->_get_datatables_query();
		return $this->db->count_all_results();
	}

	public function get_by_id($id)
	{
		$this->_main_query();
		if(is_array($id)){
			$this->db->where_in(''.$this->table.'.id_pasien_vk',$id);
			$query = $this->db->get();
			return $query->result();
		}else{
			$this->db->where(''.$this->table.'.id_pasien_vk',$id);
			$query = $this->db->get();
			//print_r($this->db->last_query());die;
			return $query->row();
		}
		
	}

	public function update($table, $data, $where)
	{
		$this->db->update($table, $data, $where);
		return $this->db->affected_rows();
	}

	public function save_pm($table, $data)
	{
		/*insert tc_registrasi*/
		$this->db->insert($table, $data);
		
		return $this->db->insert_id();;
	}

	public function get_riwayat_pasien_by_id($no_kunjungan){
		return $this->db->get_where('th_riwayat_pasien', array('no_kunjungan' => $no_kunjungan) )->row();
	}

	public function cek_transaksi_minimal($no_kunjungan){
		$transaksi_min = $this->db->get_where('tc_trans_pelayanan', array('no_kunjungan' => $no_kunjungan) )->num_rows();
		if( $transaksi_min > 0 ){
			return true;
		}else{
			return false;
		}
	}

	public function cek_pm_pulang($no_registrasi){
		$this->db->from('tc_kunjungan');
		$this->db->where('no_registrasi', $no_registrasi );
		$this->db->where("kode_bagian_tujuan like '05%'" );
		$this->db->where("(tgl_keluar IS NULL OR status_keluar IS NULL)");
		$query = $this->db->get();
		$cek_pm = $query->num_rows();

		if( $cek_pm > 0 ){
			return true;
		}else{
			return false;
		}
	}

	public function cekRujuk($no_kunjungan)
	{
		return $this->db->get_where('rg_tc_rujukan', array('no_kunjungan_lama' => $no_kunjungan))->row();
	}

	public function get_transaksi_pasien_by_id($no_kunjungan){
		$this->db->from('tc_trans_pelayanan');
		$this->db->where('no_kunjungan', $no_kunjungan );
		$this->db->where("kode_tc_trans_kasir is null" );
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function get_data_bayi($no_mr_ibu){
		$this->db->from('ri_bayi_lahir');
		$this->db->join('mt_dokter_v', 'mt_dokter_v.kode_dokter=ri_bayi_lahir.dokter_penolong', 'left' );
		$this->db->where('mr_ibu', $no_mr_ibu );
		$this->db->order_by('id_bayi', 'DESC' );
		$query = $this->db->get();
		return $query->row();
	}

}
