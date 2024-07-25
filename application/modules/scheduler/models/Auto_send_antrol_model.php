<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auto_send_antrol_model extends CI_Model {


	public function __construct()
	{
		parent::__construct();
	}

    public function get_data($month, $year){

		$query = "SELECT TOP 1 * FROM (SELECT kodebooking,
					MAX ( taskid ) AS taskid,
					MAX ( created_date ) AS created_date 
				FROM
					tr_log_antrian 
				WHERE
				response_code = 200
				GROUP BY
					kodebooking
				HAVING MAX(taskid) < 5
				) as tbl WHERE CAST(created_date as DATE) != '".date('Y-m-d')."' ORDER BY created_date DESC ";

		$execute = $this->db->query($query)->result();
		return $execute;
    }

	public function execute_all_task(){

		$antrol = new Antrol;

		$query = "SELECT top 10 kodebookingantrol, no_registrasi FROM tc_registrasi WHERE CAST(tgl_jam_masuk as DATE) = '".date('Y-m-d')."' AND kode_perusahaan = 120 AND SUBSTRING(kode_bagian_masuk, 1,2) = '01' AND task_id < 6 AND autosendantrol != 1  ORDER BY no_registrasi ASC";
		$execute = $this->db->query($query)->result();
		// echo "<pre>"; print_r($query);die;
		return $execute;
    }

	public function execute_first_task(){

		$query = "SELECT top 10 kodebookingantrol, no_registrasi FROM tc_registrasi WHERE CAST(tgl_jam_masuk as DATE) = '".date('Y-m-d')."' AND kode_perusahaan = 120 AND SUBSTRING(kode_bagian_masuk, 1,2) = '01' AND task_id is null ORDER BY no_registrasi ASC";
		$execute = $this->db->query($query)->result();
		// echo "<pre>"; print_r($query);die;
		return $execute;
    }

	function get_detail_resume_medis($no_registrasi, $no_kunjungan='')
	
	{
		/*data registrasi*/
		$this->db->select('tc_registrasi.no_registrasi, tc_kunjungan.no_kunjungan, nama_pegawai, tc_registrasi.kode_dokter, nama_perusahaan, mt_bagian.nama_bagian, th_riwayat_pasien.diagnosa_awal, th_riwayat_pasien.diagnosa_akhir, th_riwayat_pasien.anamnesa, th_riwayat_pasien.pengobatan, th_riwayat_pasien.kategori_tindakan, tc_registrasi.tgl_jam_masuk, tc_kunjungan.tgl_masuk, th_riwayat_pasien.pemeriksaan, tinggi_badan, tekanan_darah, nadi, th_riwayat_pasien.berat_badan, suhu, mt_master_pasien.nama_pasien, mt_master_pasien.no_mr,mt_master_pasien.almt_ttp_pasien,mt_master_pasien.tempat_lahir,mt_master_pasien.tgl_lhr, kode_bagian_tujuan, tujuan_poli.nama_bagian as poli_tujuan_kunjungan, asal_poli.nama_bagian as poli_asal_kunjungan, kode_bagian_asal, tc_registrasi.kode_perusahaan, print_tracer, tc_registrasi.jd_id, tc_registrasi.norujukan, kodebookingantrol, mt_master_pasien.no_kartu_bpjs, no_ktp, no_hp, mt_bagian.kode_poli_bpjs, mt_karyawan.kode_dokter_bpjs, tc_registrasi.jeniskunjunganbpjs, jen_kelamin, tc_registrasi.no_sep, tlp_almt_ttp, tc_registrasi.kode_bagian_masuk ');
		$this->db->from('tc_kunjungan');
		$this->db->join('tc_registrasi','tc_registrasi.no_registrasi=tc_kunjungan.no_registrasi','left');
		$this->db->join('mt_master_pasien','mt_master_pasien.no_mr=tc_registrasi.no_mr','left');
		$this->db->join('mt_bagian','mt_bagian.kode_bagian=tc_registrasi.kode_bagian_masuk','left');
		$this->db->join('mt_bagian as tujuan_poli','tujuan_poli.kode_bagian=tc_kunjungan.kode_bagian_tujuan','left');
		$this->db->join('mt_bagian as asal_poli','asal_poli.kode_bagian=tc_kunjungan.kode_bagian_asal','left');
		$this->db->join('mt_perusahaan','mt_perusahaan.kode_perusahaan=tc_registrasi.kode_perusahaan','left');
		$this->db->join('mt_karyawan','mt_karyawan.kode_dokter=tc_registrasi.kode_dokter','left');
		$this->db->join('th_riwayat_pasien','th_riwayat_pasien.no_kunjungan=tc_kunjungan.no_kunjungan','left');
		$this->db->where('tc_kunjungan.no_registrasi', $no_registrasi);
		if($no_kunjungan != ''){
			$this->db->where('tc_kunjungan.no_kunjungan', $no_kunjungan);
		}
		$this->db->order_by('tc_kunjungan.tgl_masuk', 'DESC');
		$registrasi = $this->db->get()->result();

		/*data transaksi*/
		$transaksi = $this->db->select('kode_trans_pelayanan, no_kunjungan, nama_tindakan, mt_jenis_tindakan.jenis_tindakan, kode_jenis_tindakan, tgl_transaksi, kode_tc_trans_kasir, nama_pegawai, fr_tc_far_detail_log.jumlah_tebus, fr_tc_far_detail_log.jumlah_obat_23, tc_trans_pelayanan.kode_bagian, satuan_obat, anjuran_pakai, dosis_per_hari, dosis_obat, catatan_lainnya,satuan_kecil')->join('mt_jenis_tindakan','mt_jenis_tindakan.kode_jenis_tindakan=tc_trans_pelayanan.jenis_tindakan','left')->join('mt_karyawan','mt_karyawan.kode_dokter=tc_trans_pelayanan.kode_dokter1','left')->join('fr_tc_far_detail_log','fr_tc_far_detail_log.relation_id=tc_trans_pelayanan.kd_tr_resep','left')->get_where('tc_trans_pelayanan', array('no_registrasi' => $no_registrasi) )->result();

		// echo $this->db->last_query();die;

		// data pembayaran kasir
		$trans_kasir = $this->db->get_where('tc_trans_kasir', array('no_registrasi' => $no_registrasi))->result();

		$antrian_poli = $this->db->query("select no_antrian from pl_tc_poli where no_kunjungan in (select no_kunjungan from tc_kunjungan where no_registrasi = ".$no_registrasi.")")->row();

		$this->db->select('tmp_user.fullname');
		$this->db->from('tc_registrasi');
		$this->db->join('tmp_user','tc_registrasi.no_induk=tmp_user.user_id','left');
		$this->db->where('tc_registrasi.no_registrasi', $no_registrasi);
		$petugas = $this->db->get()->row();

		// penunjang
		$penunjang = $this->db->where('SUBSTRING(kode_bagian_tujuan, 1, 2) =', '05')->join('mt_bagian', 'mt_bagian.kode_bagian=tc_kunjungan.kode_bagian_tujuan','left')->join('pm_tc_penunjang', 'pm_tc_penunjang.no_kunjungan=tc_kunjungan.no_kunjungan','left')->get_where('tc_kunjungan', array('no_registrasi' => $no_registrasi) )->result();
		$getDataPm = [];
		foreach ($penunjang as $key_pm => $val_pm) {
			$getDataPm[$val_pm->no_registrasi][] = $val_pm;
		}

		// jadwal dokter
		// get jd id
		$params = [
			'tgl' => $this->tanggal->formatDateTimeToSqlDate($registrasi[0]->tgl_jam_masuk),
			'kode_dokter' => $registrasi[0]->kode_dokter,
			'poli' => $registrasi[0]->kode_bagian_masuk,
		];
		$jadwal = ($registrasi[0]->jd_id == 0) ? $this->get_jd_id($params) : $this->db->get_where('tr_jadwal_dokter', array('jd_id' => $registrasi[0]->jd_id))->row();

		// echo "<pre>"; print_r($jadwal);die;
		// echo $this->db->last_query();die;
		
		
		$data = array(
			'registrasi' =>  isset($registrasi[0])?$registrasi[0]:[],
			'riwayat_medis' =>  $registrasi,
			'tindakan' =>  $transaksi,
			'no_antrian' => $antrian_poli,
			'petugas' => $petugas,
			'trans_kasir' => $trans_kasir,
			'penunjang' => $getDataPm,
			'jadwal' => $jadwal,
			);
		return $data;
		
	}

	public function get_jd_id($params){
		// convert date to day
		$day = $this->tanggal->getDayFromDate($params['tgl']);
		$query = $this->db->get_where('tr_jadwal_dokter', ['jd_kode_dokter' => $params['kode_dokter'], 'jd_kode_spesialis' => $params['poli'], 'jd_hari' => $day])->row();
		return $query;

	}


}
