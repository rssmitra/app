<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pelayanan_publik_model extends CI_Model {

	var $table = 'pl_tc_poli';
	var $column = array('pl_tc_poli.nama_pasien','mt_karyawan.nama_pegawai');
	var $select = 'pl_tc_poli.kode_bagian,pl_tc_poli.no_kunjungan,pl_tc_poli.no_antrian,pl_tc_poli.nama_pasien, id_pl_tc_poli, pl_tc_poli.status_periksa, tc_kunjungan.no_mr, mt_perusahaan.nama_perusahaan, mt_nasabah.nama_kelompok, pl_tc_poli.tgl_jam_poli, mt_karyawan.nama_pegawai,tc_registrasi.no_registrasi, tc_registrasi.kode_kelompok, tc_registrasi.kode_perusahaan, tc_kunjungan.kode_bagian_asal, tc_kunjungan.status_keluar, pl_tc_poli.kode_dokter, pl_tc_poli.status_batal, pl_tc_poli.created_by, pl_tc_poli.tgl_keluar_poli, tc_registrasi.tgl_jam_keluar, pl_tc_poli.flag_ri, pl_tc_poli.flag_mcu, pl_tc_poli.flag_bayar_konsul, pl_tc_poli.kelas_ri, tc_registrasi.no_sep, tc_registrasi.kodebookingantrol';
	var $order = array('pl_tc_poli.no_antrian' => 'ASC');

	public function __construct()
	{
		parent::__construct();
	}

	private function _main_query(){
		$this->db->select($this->select);
		$this->db->from($this->table);
		$this->db->join('tc_kunjungan',''.$this->table.'.no_kunjungan=tc_kunjungan.no_kunjungan','left');
		$this->db->join('mt_karyawan','mt_karyawan.kode_dokter=pl_tc_poli.kode_dokter','left');
		$this->db->join('tc_registrasi','tc_registrasi.no_registrasi=tc_kunjungan.no_registrasi','left');
		$this->db->join('mt_perusahaan','tc_registrasi.kode_perusahaan=mt_perusahaan.kode_perusahaan','left');
		$this->db->join('mt_nasabah','tc_registrasi.kode_kelompok=mt_nasabah.kode_kelompok','left');

	}

	function get_data_antrian_pasien()
	{
		$this->_main_query();
		if( in_array($_GET['kode_bagian'], array('012801','013101') ) ) {
			$this->db->where('pl_tc_poli.kode_bagian='."'".$_GET['kode_bagian']."'".'');
		}else{
			$this->db->where('pl_tc_poli.kode_bagian='."'".$_GET['kode_bagian']."'".'');
			$this->db->where('pl_tc_poli.kode_dokter='."'".$_GET['kode_dokter']."'".'');
		}

		$this->db->where( array('CAST(pl_tc_poli.tgl_jam_poli as DATE) = ' => date('Y-m-d') ) );
        $this->db->order_by('no_antrian','ASC');
		$query = $this->db->get();
		return $query->result();
	}

	
}
