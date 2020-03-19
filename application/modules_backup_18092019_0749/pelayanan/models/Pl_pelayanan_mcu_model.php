<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pl_pelayanan_mcu_model extends CI_Model {

	var $table = 'pl_tc_poli';
	var $column = array('pl_tc_poli.nama_pasien','mt_karyawan.nama_pegawai');
	var $select = 'pl_tc_poli.kode_bagian,pl_tc_poli.no_kunjungan,pl_tc_poli.no_antrian,pl_tc_poli.nama_pasien, id_pl_tc_poli, pl_tc_poli.status_periksa, pl_tc_poli.status_daftar, pl_tc_poli.kode_gcu, tc_kunjungan.no_mr, mt_perusahaan.nama_perusahaan, mt_nasabah.nama_kelompok, pl_tc_poli.tgl_jam_poli, mt_karyawan.nama_pegawai,tc_registrasi.no_registrasi, tc_registrasi.kode_kelompok, tc_registrasi.kode_perusahaan, tc_kunjungan.kode_bagian_asal, tc_kunjungan.status_keluar, pl_tc_poli.kode_dokter, pl_tc_poli.status_batal, pl_tc_poli.created_by, pl_tc_poli.tgl_keluar_poli, tc_registrasi.tgl_jam_keluar, pl_tc_poli.flag_ri, pl_tc_poli.kelas_ri,tc_trans_pelayanan.status_selesai,tc_trans_pelayanan.kode_tarif,tc_trans_pelayanan.kode_trans_pelayanan,mt_master_tarif.nama_tarif';

	var $order = array('MONTH(pl_tc_poli.tgl_jam_poli)' => 'DESC');
	var $order2 = array('DAY(pl_tc_poli.tgl_jam_poli)' => 'DESC');
	var $order3 = array('pl_tc_poli.no_antrian' => 'ASC');

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
		$this->db->join('mt_karyawan','mt_karyawan.kode_dokter=pl_tc_poli.kode_dokter','left');
		$this->db->join('tc_registrasi','tc_registrasi.no_registrasi=tc_kunjungan.no_registrasi','left');
		$this->db->join('mt_perusahaan','tc_registrasi.kode_perusahaan=mt_perusahaan.kode_perusahaan','left');
		$this->db->join('mt_nasabah','tc_registrasi.kode_kelompok=mt_nasabah.kode_kelompok','left');
		$this->db->join('tc_trans_pelayanan','tc_registrasi.no_registrasi=tc_trans_pelayanan.no_registrasi','left');
		$this->db->join('mt_master_tarif','tc_trans_pelayanan.kode_tarif=mt_master_tarif.kode_tarif','left');
		$this->db->where("pl_tc_poli.kode_bagian='010901'");
		

		/*check level user*/
		//$this->authuser->filtering_data_by_level_user($this->table, $this->session->userdata('user')->user_id);

	}

	private function _get_datatables_query()
	{
		
		$this->_main_query();

		if(isset($_GET['search_by']) AND isset($_GET['keyword'])){
			if( $_GET['keyword'] != '' ){
				$this->db->like(''.$_GET['search_by'].'', $_GET['keyword']);
			}
		}

		if (isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' || isset($_GET['to_tgl']) AND $_GET['to_tgl'] != '') {
			$this->db->where("convert(varchar,pl_tc_poli.tgl_jam_poli,23) between '".$_GET['from_tgl']."' and '".$_GET['to_tgl']."'");					
        }else{
        	$this->db->where(array('YEAR(pl_tc_poli.tgl_jam_poli)' => date('Y'), 'MONTH(pl_tc_poli.tgl_jam_poli)' => date('m') ) );
		}

		//$this->db->where('(pl_tc_poli.status_periksa=0 or pl_tc_poli.status_periksa IS NULL)');	
		
		//$this->db->group_by($this->select);

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
			$order2 = $this->order2;
			$this->db->order_by(key($order), $order[key($order)]);
			$this->db->order_by(key($order2), $order2[key($order2)]);
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
		$this->_main_query();
		return $this->db->count_all_results();
	}

	public function get_by_id($id)
	{
		$this->_main_query();
		if(is_array($id)){
			$this->db->where_in(''.$this->table.'.id_pl_tc_poli',$id);
			$query = $this->db->get();
			return $query->result();
		}else{
			$this->db->where(''.$this->table.'.id_pl_tc_poli',$id);
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
	
	public function get_riwayat_pasien_by_id($no_kunjungan){
		return $this->db->get_where('th_riwayat_pasien', array('no_kunjungan' => $no_kunjungan) )->row();
	}


	public function get_mcu_tarif_detail($kode_tarif)
	{
		# code...
		$this->db->from('mt_mcu_tarif_detail_v');
		$this->db->where( array('kode_tarif' => $kode_tarif) );
		$this->db->order_by('kode_bagian','ASC');
		$query = $this->db->get();
		return $query->result();

	}

	public function get_dokter_by_bagian($kd_bagian='')
	{
		$query = "select a.kode_dokter as kode_dokter,a.nama_pegawai
					from mt_dokter_v a where a.kd_bagian=".$kd_bagian." and a.nama_pegawai != ' '
					group by a.kode_dokter,a.nama_pegawai";
        $exc = $this->db->query($query);
        return $exc->result();
		
	}
	
	public function get_tarif($kode_tarif)
	{
		$nama_tindakan = $this->db->get_where('mt_master_tarif',array('kode_tarif' => $kode_tarif))->row();
		return $nama_tindakan;
	}

	public function get_paket_detail($id_mt_mcu_detail)
	{
		$this->db->select('a.*,b.nama_tarif as tindakan_det,c.nama_tarif as tindakan_mcu');
		$this->db->from('mt_mcu_tarif_detail a');
		$this->db->join('mt_master_tarif b','a.kode_referensi=b.kode_tarif','left');
		$this->db->join('mt_master_tarif c','a.kode_tarif=c.kode_tarif','left');
		$this->db->where( array('id_mt_mcu_detail' => $id_mt_mcu_detail) );
		$query = $this->db->get();
		return $query->row();
		// $data = $this->db->get_where('mt_mcu_tarif_detail',array('id_mt_mcu_detail' => $id_mt_mcu_detail))->row();
		// return $data;
	}

	public function get_pemeriksaan($kode_gcu)
	{
		return $this->db->get_where('tc_pemeriksaan_fisik_mcu', array('kode_mcu' => $kode_gcu) )->row();
	}

	public function get_pemeriksaan_by_id($id)
	{
		return $this->db->get_where('tc_pemeriksaan_fisik_mcu', array('id_tc_pemeriksaan_fisik_mcu' => $id) )->row();
	}

	public function get_kunjungan_pm_by_no_reg($no_reg,$kode_bagian)
	{
		$this->db->select('*');
		$this->db->from('tc_kunjungan a');
		$this->db->join('pm_tc_penunjang b','a.no_kunjungan=b.no_kunjungan','left');
		$this->db->where( array('a.no_registrasi' => $no_reg) );
		$this->db->where( array('a.kode_bagian_tujuan' => $kode_bagian) );
		$query = $this->db->get();
		return $query->row();
		// $data = $this->db->get_where('mt_mcu_tarif_detail',array('id_mt_mcu_detail' => $id_mt_mcu_detail))->row();
		// return $data;
	}

	public function get_hasil_by_kode_gcu($kode)
	{
		return $this->db->get_where('mcu_tc_hasil', array('kode_mcu' => $kode) )->row();
	}

	function get_kunjungan_by_no_registrasi($no_registrasi)
	
	{
		/*data registrasi*/
		$this->db->select('*');
		$this->db->from('mcu_status_periksa_v');
		$this->db->where('no_registrasi', $no_registrasi);
		$this->db->order_by('kode_bagian_tujuan', 'ASC');
		$data = $this->db->get()->result();

		return $data;
		
	}

	public function cek_hasil($kode_gcu){
		$transaksi_pemeriksaan = $this->db->get_where('tc_pemeriksaan_fisik_mcu', array('kode_mcu' => $kode_gcu) )->num_rows();
		if( $transaksi_pemeriksaan > 0 ){
			return false;
		}else{
			return true;
		}
	}

	public function cek_kesimpulan($kode_gcu){
		$transaksi_kesimpulan = $this->db->get_where('mcu_tc_hasil', array('kode_mcu' => $kode_gcu) )->num_rows();
		if( $transaksi_kesimpulan > 0 ){
			return false;
		}else{
			return true;
		}
	}

	public function cek_pm_pulang($no_kunjungan){
		$this->db->from('pm_tc_penunjang');
		$this->db->where('no_kunjungan_asal', $no_kunjungan );
		$this->db->where("(status_isihasil IS NULL)");
		$query = $this->db->get();
		$cek_poli = $query->num_rows();

		if( $cek_poli > 0 ){
			return true;
		}else{
			return false;
		}
	}

	public function cek_poli_pulang($no_registrasi){
		$this->db->from('mcu_status_periksa_v');
		$this->db->where('no_registrasi', $no_registrasi );
		$this->db->where("kode_bagian_tujuan like '01%'" );
		$this->db->where("(status_daftar_poli IS NULL OR status_isihasil_poli IS NULL)");
		$query = $this->db->get();
		$cek_poli = $query->num_rows();

		if( $cek_poli > 0 ){
			return $query->result();
		}else{
			return false;
		}
	}

	public function get_param($table,$fields,$flag)
	{
		$this->db->select($fields);
		$this->db->from($table);
		$this->db->where($flag);
		$query = $this->db->get();
		return $query->result();
	}


}
