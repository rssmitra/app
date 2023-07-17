<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pl_pelayanan_bedah_model extends CI_Model {

	var $table = 'ok_riwayat_pasien_bedah_v';
	var $column = array('mt_master_pasien.no_mr','mt_master_pasien.nama_pasien');
	var $select = 'ok_riwayat_pasien_bedah_v.no_mr, nama_pasien, no_registrasi, no_kunjungan, kode_ri,jenis_layanan, ok_riwayat_pasien_bedah_v.kode_master_tarif_detail, dokter1, tgl_pesan, id_pesan_bedah, jen_kelamin, tgl_lhr, ok_riwayat_pasien_bedah_v.kode_kelompok, ok_riwayat_pasien_bedah_v.kode_perusahaan, flag_jadwal, ok_riwayat_pasien_bedah_v.kode_klas, mt_karyawan.nama_pegawai, mt_perusahaan.nama_perusahaan, mt_nasabah.nama_kelompok, mt_klas.nama_klas, mt_master_tarif_detail.kode_tarif, mt_master_tarif.nama_tarif, mt_master_tarif_detail.total, ok_riwayat_pasien_bedah_v.kode_ruangan, ok_riwayat_pasien_bedah_v.tgl_jadwal, ok_riwayat_pasien_bedah_v.no_kamar, ok_riwayat_pasien_bedah_v.jam_bedah, ok_riwayat_pasien_bedah_v.tgl_keluar, ok_riwayat_pasien_bedah_v.status_pulang';

	var $order = array('ok_riwayat_pasien_bedah_v.tgl_pesan' => 'DESC');

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
		$this->db->join('mt_karyawan', 'ok_riwayat_pasien_bedah_v.dokter1=mt_karyawan.kode_dokter', 'left');
		$this->db->join('mt_perusahaan', 'ok_riwayat_pasien_bedah_v.kode_perusahaan=mt_perusahaan.kode_perusahaan', 'left');
		$this->db->join('mt_nasabah', 'ok_riwayat_pasien_bedah_v.kode_kelompok=mt_nasabah.kode_kelompok', 'left');
		$this->db->join('mt_klas', 'ok_riwayat_pasien_bedah_v.kode_klas=mt_klas.kode_klas', 'left');
		$this->db->join('mt_master_tarif_detail', 'ok_riwayat_pasien_bedah_v.kode_master_tarif_detail=mt_master_tarif_detail.kode_master_tarif_detail', 'left');
		$this->db->join('mt_master_tarif', 'mt_master_tarif_detail.kode_tarif=mt_master_tarif.kode_tarif', 'left');
		$this->db->group_by($this->select);
		/*if isset parameter*/
		
        /*end parameter*/
		/*check level user*/
		//$this->authuser->filtering_data_by_level_user($this->table, $this->session->userdata('user')->user_id);

	}

	private function _get_datatables_query()
	{
		
		$this->_main_query();
		
		if( $_GET ) {

			if(isset($_GET['search_by']) AND isset($_GET['keyword'])){
				if( $_GET['keyword'] != '' ){
					$this->db->like('ok_riwayat_pasien_bedah_v.'.$_GET['search_by'], $_GET['keyword']);
				}
			}

			if (isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' || isset($_GET['to_tgl']) AND $_GET['to_tgl'] != '') {
				$this->db->where("convert(varchar,ok_riwayat_pasien_bedah_v.tgl_pesan,23) between '".$_GET['from_tgl']."' and '".$_GET['to_tgl']."'");					
	        }
		
		}else{
			$this->db->where('ok_riwayat_pasien_bedah_v.flag_jadwal = 1');
			$this->db->where('ok_riwayat_pasien_bedah_v.flag_pesan = 0');
			$this->db->where('ok_riwayat_pasien_bedah_v.tgl_keluar IS NULL');
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

	function get_data()
	{
		$this->_main_query();
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
		$this->_get_datatables_query();
		return $this->db->count_all_results();
	}

	public function get_by_id($id)
	{
		$this->_main_query();
		if(is_array($id)){
			$this->db->where_in(''.$this->table.'.id_pesan_bedah',$id);
			$query = $this->db->get();
			return $query->result();
		}else{
			$this->db->where(''.$this->table.'.id_pesan_bedah',$id);
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



}
