<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pl_pelayanan_igd_model extends CI_Model {

	var $table = 'gd_tc_gawat_darurat';
	var $column = array('gd_tc_gawat_darurat.nama_pasien_igd','mt_karyawan.nama_pegawai');
	var $select = 'gd_tc_gawat_darurat.no_kunjungan,gd_tc_gawat_darurat.nama_pasien_igd,tgl_jam_kel, kode_gd, gd_tc_gawat_darurat.status_diterima, tc_kunjungan.no_mr, mt_perusahaan.nama_perusahaan, mt_nasabah.nama_kelompok, gd_tc_gawat_darurat.tanggal_gd, gd_tc_gawat_darurat.tgl_kecelakaan, mt_karyawan.nama_pegawai,tc_registrasi.no_registrasi, tc_registrasi.kode_kelompok, tc_registrasi.kode_perusahaan, tc_kunjungan.kode_bagian_asal, tc_kunjungan.status_keluar, gd_tc_gawat_darurat.dokter_jaga, gd_tc_gawat_darurat.no_induk,tc_kunjungan.tgl_keluar,tmp_user.fullname';

	var $order = array('gd_tc_gawat_darurat.kode_gd' => 'desc');

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
		$this->db->join('tc_kunjungan',''.$this->table.'.no_kunjungan=tc_kunjungan.no_kunjungan','left');
		$this->db->join('mt_karyawan','mt_karyawan.kode_dokter=gd_tc_gawat_darurat.dokter_jaga','left');
		$this->db->join('tc_registrasi','tc_registrasi.no_registrasi=tc_kunjungan.no_registrasi','left');
		$this->db->join('mt_perusahaan','tc_registrasi.kode_perusahaan=mt_perusahaan.kode_perusahaan','left');
		$this->db->join('mt_nasabah','tc_registrasi.kode_kelompok=mt_nasabah.kode_kelompok','left');
		$this->db->join('tmp_user','tmp_user.user_id='.$this->table.'.no_induk','left');
		$this->db->where("tc_kunjungan.no_mr is not null");


		/*check level user*/
		//$this->authuser->filtering_data_by_level_user($this->table, $this->session->userdata('user')->user_id);

	}

	private function _get_datatables_query()
	{
		$date = date('Y-m-d H:i:s', strtotime('-3 days', strtotime(date('Y-m-d H:i:s'))));
		$this->_main_query();

		if(isset($_GET['search_by']) AND isset($_GET['keyword'])){
			if( $_GET['keyword'] != '' ){
				$this->db->like(''.$_GET['search_by'].'', $_GET['keyword']);
			}
		}

		if (isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' || isset($_GET['to_tgl']) AND $_GET['to_tgl'] != '') {
			$this->db->where("convert(varchar,gd_tc_gawat_darurat.tanggal_gd,23) between '".$_GET['from_tgl']."' and '".$_GET['to_tgl']."'");					
        }else{
			$this->db->where("gd_tc_gawat_darurat.tanggal_gd > '".$date."' ");
			//$this->db->where(array('YEAR(gd_tc_gawat_darurat.tanggal_gd)' => date('Y'), 'MONTH(gd_tc_gawat_darurat.tanggal_gd)' => date('m'), 'DAY(gd_tc_gawat_darurat.tanggal_gd)' => date('d') ) );
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
		//print_r($this->db->last_query());die;
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
			$this->db->where_in(''.$this->table.'.kode_gd',$id);
			$query = $this->db->get();
			return $query->result();
		}else{
			$this->db->where(''.$this->table.'.kode_gd',$id);
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

	public function get_vital_sign($no_registrasi)
	{
		return $this->db->get_where('gd_th_rujuk_ri', array('no_registrasi' => $no_registrasi) )->result();
	}

	public function get_laporan_dr($no_kunjungan)
	{
		return $this->db->get_where('th_laporan_dr', array('no_kunjungan' => $no_kunjungan) )->row();
	}

	public function get_laporan_perawat($no_kunjungan)
	{
		return $this->db->get_where('th_laporan_perawat', array('no_kunjungan' => $no_kunjungan) )->row();
	}

	public function get_keracunan($no_kunjungan)
	{
		//return $this->db->get_where('gd_tc_cetak_racun', array('no_kunjungan' => $no_kunjungan) )->row();

		$this->db->select('gd_tc_cetak_racun.*,mt_master_pasien.nama_pasien,mt_master_pasien.jen_kelamin,mt_master_pasien.almt_ttp_pasien,mt_karyawan.nama_pegawai');
		$this->db->from('gd_tc_cetak_racun');
		$this->db->join('mt_master_pasien','mt_master_pasien.no_mr=gd_tc_cetak_racun.no_mr','left');
		$this->db->join('gd_tc_gawat_darurat','gd_tc_gawat_darurat.no_kunjungan=gd_tc_cetak_racun.no_kunjungan','left');
		$this->db->join('mt_karyawan','mt_karyawan.kode_dokter=gd_tc_gawat_darurat.dokter_jaga','left');
		$this->db->where('gd_tc_cetak_racun.no_kunjungan', $no_kunjungan );
		$query = $this->db->get();
		return $query->row();
	}

	public function get_meninggal($no_kunjungan,$no_registrasi)
	{
	
		$this->db->select('gd_th_kematian.*,mt_master_pasien.nama_pasien,mt_master_pasien.almt_ttp_pasien,mt_bagian.nama_bagian,mt_karyawan.nama_pegawai');
		$this->db->from('gd_th_kematian');
		$this->db->join('mt_master_pasien','mt_master_pasien.no_mr=gd_th_kematian.no_mr','left');
		$this->db->join('mt_bagian','mt_bagian.kode_bagian=gd_th_kematian.kode_bagian','left');
		$this->db->join('mt_karyawan','mt_karyawan.kode_dokter=gd_th_kematian.dokter_asal','left');
		$this->db->where('gd_th_kematian.no_kunjungan', $no_kunjungan );
		$this->db->where('gd_th_kematian.no_registrasi', $no_registrasi );
		$query = $this->db->get();
		return $query->row();
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

}
