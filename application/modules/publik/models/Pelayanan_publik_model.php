<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pelayanan_publik_model extends CI_Model {

	var $table = 'pl_tc_poli';
	var $column = array('pl_tc_poli.nama_pasien','mt_karyawan.nama_pegawai');
	var $select = 'pl_tc_poli.kode_bagian,pl_tc_poli.no_kunjungan,pl_tc_poli.no_antrian,pl_tc_poli.nama_pasien, id_pl_tc_poli, pl_tc_poli.status_periksa, tc_kunjungan.no_mr, mt_perusahaan.nama_perusahaan, mt_nasabah.nama_kelompok, pl_tc_poli.tgl_jam_poli, mt_karyawan.nama_pegawai,tc_registrasi.no_registrasi, tc_registrasi.kode_kelompok, tc_registrasi.kode_perusahaan, tc_kunjungan.kode_bagian_asal, tc_kunjungan.status_keluar, pl_tc_poli.kode_dokter, pl_tc_poli.status_batal, pl_tc_poli.created_by, pl_tc_poli.tgl_keluar_poli, tc_registrasi.tgl_jam_keluar, pl_tc_poli.flag_ri, pl_tc_poli.flag_mcu, pl_tc_poli.flag_bayar_konsul, pl_tc_poli.kelas_ri, tc_registrasi.no_sep, tc_registrasi.kodebookingantrol, umur, mt_bagian.nama_bagian';
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
		$this->db->join('mt_bagian','mt_bagian.kode_bagian=tc_kunjungan.kode_bagian_tujuan','left');

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

	public function getKuotaDokter($kode_dokter='',$kode_spesialis='', $tanggal='')
	{
		$date = ($tanggal=='')?date('Y-m-d'):$tanggal;
		$day = $this->tanggal->getHariFromDateSql($date);
		
		// terdaftar
		$log_kuota_current = $this->db->get_where('tc_registrasi', array('CAST(tgl_jam_masuk as DATE) = ' => $date, 'kode_dokter' => $kode_dokter, 'kode_bagian_masuk' => $kode_spesialis) )->num_rows();

        /*kuota dokter*/
        $kuota_dokter = $this->db->select('tr_jadwal_dokter.*, kode_dokter_bpjs, kode_poli_bpjs')->join('mt_karyawan', 'mt_karyawan.kode_dokter = tr_jadwal_dokter.jd_kode_dokter', 'left')->join('mt_bagian', 'mt_bagian.kode_bagian = tr_jadwal_dokter.jd_kode_spesialis')->get_where('tr_jadwal_dokter', array('jd_hari' => $day, 'jd_kode_dokter' => $kode_dokter, 'jd_kode_spesialis' => $kode_spesialis) )->row(); 
		// echo $this->db->last_query(); exit;
		$id = $kuota_dokter->jd_id; 
		$kuota_dr = $kuota_dokter->jd_kuota;
		$sisa = $kuota_dokter->jd_kuota - $log_kuota_current;

		$data = array(
			'kuota' => $kuota_dr,
			'terdaftar' => $log_kuota_current,
			'sisa_kuota' => $sisa,
			'kode_dokter' => $kode_dokter,
			'kode_dokter_bpjs' => $kuota_dokter->kode_dokter_bpjs,
			'jam_praktek_mulai' => $this->tanggal->formatFullTime($kuota_dokter->jd_jam_mulai),
			'jam_praktek_selesai' => $this->tanggal->formatFullTime($kuota_dokter->jd_jam_selesai),
			'kode_poli_bpjs' => $kuota_dokter->kode_poli_bpjs,
			'kode_bagian' => $kode_spesialis,
			'tgl_registrasi' => $date,
		);

		$message = ($sisa==0)?'<label class="label label-danger"><i class="fa fa-times-circle"></i> Maaf, Kuota sudah penuh !</label>':'<label class="label label-success"><i class="fa fa-check"></i> Kuota Terpenuhi</label>';

		return array('sisa_kuota' => $sisa, 'jd_id' => $id, 'data' => $data);
        
	}

	public function get_data_kunjungan($no_kunjungan){
		$this->_main_query();
		$this->db->select('konfirm_fp, tr_jadwal_dokter.*, tc_registrasi.status_checkin, checkin_date');
		$this->db->join('tr_jadwal_dokter','tr_jadwal_dokter.jd_id=tc_registrasi.jd_id','left');
		$this->db->where('pl_tc_poli.no_kunjungan', $no_kunjungan);
		$query = $this->db->get()->row();
		// echo '<pre>'; print_r($this->db->last_query());die;
		return $query;
	}

	public function cek_kunjungan_by_date($tgl, $no_mr){
		$this->_main_query();
		$this->db->where('CAST(tc_registrasi.tgl_jam_masuk as DATE) = ', $tgl);
		$this->db->where('tc_registrasi.no_mr', $no_mr);
		$query = $this->db->get();
		return $query;

	}

	
}
