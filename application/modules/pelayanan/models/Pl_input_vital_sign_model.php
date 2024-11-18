<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pl_input_vital_sign_model extends CI_Model {

	var $table = 'pl_tc_poli';
	var $column = array('pl_tc_poli.nama_pasien','tc_kunjungan.no_mr');
	var $select = 'pl_tc_poli.kode_bagian,pl_tc_poli.no_kunjungan,pl_tc_poli.no_antrian,pl_tc_poli.nama_pasien, id_pl_tc_poli, pl_tc_poli.status_periksa, tc_kunjungan.no_mr, mt_perusahaan.nama_perusahaan, mt_nasabah.nama_kelompok, pl_tc_poli.tgl_jam_poli,tc_registrasi.no_registrasi, tc_registrasi.kode_kelompok, tc_registrasi.kode_perusahaan, tc_kunjungan.kode_bagian_asal, tc_kunjungan.status_keluar, pl_tc_poli.kode_dokter, pl_tc_poli.status_batal, pl_tc_poli.created_by, pl_tc_poli.tgl_keluar_poli, tc_registrasi.tgl_jam_keluar, pl_tc_poli.flag_ri, pl_tc_poli.flag_mcu, pl_tc_poli.flag_bayar_konsul, pl_tc_poli.kelas_ri, tc_registrasi.no_sep, tc_registrasi.kodebookingantrol, diagnosa_rujukan, kode_diagnosa_rujukan, tipe_daftar, pl_tc_poli.nama_pasien, tinggi_badan, tekanan_darah, berat_badan, suhu, nadi, kode_riwayat, nama_bagian';
	var $order = array('pl_tc_poli.no_antrian' => 'ASC');

	public function __construct()
	{
		parent::__construct();
	}

	private function _main_query(){
		$this->db->select($this->select);
		$this->db->from($this->table);
		$this->db->join('tc_kunjungan',''.$this->table.'.no_kunjungan=tc_kunjungan.no_kunjungan','left');
		$this->db->join('tc_registrasi','tc_registrasi.no_registrasi=tc_kunjungan.no_registrasi','left');
		$this->db->join('mt_perusahaan','tc_registrasi.kode_perusahaan=mt_perusahaan.kode_perusahaan','left');
		$this->db->join('mt_nasabah','tc_registrasi.kode_kelompok=mt_nasabah.kode_kelompok','left');
		$this->db->join('th_riwayat_pasien','(th_riwayat_pasien.no_kunjungan=pl_tc_poli.no_kunjungan AND th_riwayat_pasien.no_registrasi=tc_kunjungan.no_registrasi)','left');
		$this->db->join('mt_bagian','mt_bagian.kode_bagian=pl_tc_poli.kode_bagian','left');

	}

	private function _get_datatables_query()
	{
		
		$this->_main_query();


		if(isset($_GET['search_by']) AND isset($_GET['keyword'])){
			if( $_GET['keyword'] != '' ){
				$this->db->like(''.$_GET['search_by'].'', $_GET['keyword']);
			}
		}
		
		if (isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '') {
			$this->db->where("CAST(tgl_jam_poli as DATE) = ", $_GET['from_tgl']);	
		}else{
			$this->db->where( 'CAST(pl_tc_poli.tgl_jam_poli as DATE) = ', date('Y-m-d') );
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
	
}
