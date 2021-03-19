<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Eks_poli_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function get_content_data($params) {

		/*total klaim berdasarkan nomor sep per tahun existing*/
		/*based query*/
		if($params['prefix']==1){
			$data = array();
			// kunjungan
			$prd_kunjungan = $this->db->where('status_batal IS NULL')->where('CAST(tgl_masuk as DATE) BETWEEN '."'".$_GET['from_tgl']."'".' AND '."'".$_GET['to_tgl']."'".' ')->get('tc_kunjungan');
			$day_kunjungan = $this->db->where('status_batal IS NULL')->where('CAST(tgl_masuk as DATE) = '."'".date('Y-m-d')."'".' ')->get('tc_kunjungan');
			$mth_kunjungan = $this->db->where('status_batal IS NULL')->where('YEAR(tgl_masuk)', date('Y'))->where('MONTH(tgl_masuk)', date('m'))->get('tc_kunjungan');
			$yr_kunjungan = $this->db->where('status_batal IS NULL')->where('YEAR(tgl_masuk)', date('Y'))->get('tc_kunjungan');

			// pendapatan
			$prd_pendapatan = $this->db->select('(SUM(bill_rs) + SUM(bill_dr1) + SUM(bill_dr2) + SUM(bill_dr3)) as total')->where('CAST(tgl_transaksi as DATE) BETWEEN '."'".$_GET['from_tgl']."'".' AND '."'".$_GET['to_tgl']."'".' ')->get('tc_trans_pelayanan');
			$day_pendapatan = $this->db->select('(SUM(bill_rs) + SUM(bill_dr1) + SUM(bill_dr2) + SUM(bill_dr3)) as total')->where('CAST(tgl_transaksi as DATE) = '."'".date('Y-m-d')."'".' ')->get('tc_trans_pelayanan');
			$mth_pendapatan = $this->db->select('(SUM(bill_rs) + SUM(bill_dr1) + SUM(bill_dr2) + SUM(bill_dr3)) as total')->where('YEAR(tgl_transaksi)', date('Y'))->where('MONTH(tgl_transaksi)', date('m'))->get('tc_trans_pelayanan');
			$yr_pendapatan = $this->db->select('(SUM(bill_rs) + SUM(bill_dr1) + SUM(bill_dr2) + SUM(bill_dr3)) as total')->where('YEAR(tgl_transaksi)', date('Y'))->get('tc_trans_pelayanan');

			$data['kunjungan'] = array(
				'periode' => $prd_kunjungan->num_rows(),
				'day' => $day_kunjungan->num_rows(),
				'month' => $mth_kunjungan->num_rows(),
				'year' => $yr_kunjungan->num_rows(),
			);

			$data['pendapatan'] = array(
				'periode' => $prd_pendapatan->row()->total,
				'day' => $day_pendapatan->row()->total,
				'month' => $mth_pendapatan->row()->total,
				'year' => $yr_pendapatan->row()->total,
			);
			$fields = array();
			$title = '<span style="font-size: 14px">LAPORAN KUNJUNGAN PASIEN</span>';
			$subtitle = 'Source: RSSM - SIRS';
		}

		if($params['prefix']==2){
			$query = "SELECT MONTH(tgl_masuk) AS bulan, COUNT(id_tc_kunjungan) AS total FROM tc_kunjungan WHERE YEAR(tgl_masuk)=".date('Y')." GROUP BY MONTH(tgl_masuk)";	
			$fields = array('Jumlah_Pasien'=>'total');
			$title = '<span style="font-size:13.5px">Grafik Kunjungan Pasien Tahun '.date('Y').' RS. Setia Mitra</span>';
			$subtitle = 'Source: RSSM - SIRS';
			/*excecute query*/
			$data = $this->db->query($query)->result_array();
		}

		if($params['prefix']==3){
			$query = "SELECT TOP 10 YEAR(tc_registrasi.tgl_jam_masuk) AS tahun, mt_perusahaan.nama_perusahaan as name, COUNT(no_registrasi) as total FROM tc_registrasi LEFT JOIN mt_perusahaan ON mt_perusahaan.kode_perusahaan=tc_registrasi.kode_perusahaan WHERE YEAR(tc_registrasi.tgl_jam_masuk)=".date('Y')." GROUP BY tc_registrasi.kode_perusahaan, YEAR(tc_registrasi.tgl_jam_masuk), mt_perusahaan.nama_perusahaan ORDER BY COUNT(no_registrasi) DESC";	
			$fields = array('name' => 'total');
			$title = '<span style="font-size:13.5px">Persentase Perusahaan Asuransi Aktif</span>';
			$subtitle = 'Source: RSSM - SIRS';
			/*excecute query*/
			$data = $this->db->query($query)->result_array();
		}

		

		/*find and set type chart*/
		$chart = $this->graph_master->chartTypeData($params['TypeChart'], $fields, $params, $data);
		$chart_data = array(
			'title' 	=> $title,
			'subtitle' 	=> $subtitle,
			'xAxis' 	=> isset($chart['xAxis'])?$chart['xAxis']:'',
			'series' 	=> isset($chart['series'])?$chart['series']:'',
			);

		return $chart_data;
		
    }

	
	function get_detail_data()
	{
		$data = array();

		if($_GET['flag'] == 'periode'){
			$kunjungan = $this->db->select('COUNT(id_tc_kunjungan) as total, SUBSTRING(kode_bagian_tujuan, 1,2) as kode_unit')->where('status_batal IS NULL')->where('CAST(tgl_masuk as DATE) BETWEEN '."'".$_GET['from_tgl']."'".' AND '."'".$_GET['to_tgl']."'".' ')->group_by('SUBSTRING(kode_bagian_tujuan, 1,2)')->get('tc_kunjungan');

			$pendapatan = $this->db->select('mt_jenis_tindakan.jenis_tindakan, (SUM(bill_rs) + SUM(bill_dr1) + SUM(bill_dr2) + SUM(bill_dr3)) as total')->join('mt_jenis_tindakan', 'mt_jenis_tindakan.kode_jenis_tindakan=tc_trans_pelayanan.jenis_tindakan', 'left')->where('CAST(tgl_transaksi as DATE) BETWEEN '."'".$_GET['from_tgl']."'".' AND '."'".$_GET['to_tgl']."'".' ')->group_by('mt_jenis_tindakan.jenis_tindakan')->get('tc_trans_pelayanan');

			$pendapatan_2 = $this->db->select('mt_perusahaan.nama_perusahaan, (SUM(bill_rs) + SUM(bill_dr1) + SUM(bill_dr2) + SUM(bill_dr3)) as total')->join('mt_perusahaan', 'mt_perusahaan.kode_perusahaan=tc_trans_pelayanan.kode_perusahaan', 'left')->where('CAST(tgl_transaksi as DATE) BETWEEN '."'".$_GET['from_tgl']."'".' AND '."'".$_GET['to_tgl']."'".' ')->group_by('mt_perusahaan.nama_perusahaan')->get('tc_trans_pelayanan');

			$title = 'PERIODE, '.$this->tanggal->formatDateDmy($_GET['from_tgl']).' s/d '.$this->tanggal->formatDateDmy($_GET['to_tgl']).' ';
		}

		if($_GET['flag'] == 'day'){
			$kunjungan = $this->db->select('COUNT(id_tc_kunjungan) as total, SUBSTRING(kode_bagian_tujuan, 1,2) as kode_unit')->where('status_batal IS NULL')->where('CAST(tgl_masuk as DATE) = '."'".date('Y-m-d')."'".' ')->group_by('SUBSTRING(kode_bagian_tujuan, 1,2)')->get('tc_kunjungan');

			$pendapatan = $this->db->select('mt_jenis_tindakan.jenis_tindakan, (SUM(bill_rs) + SUM(bill_dr1) + SUM(bill_dr2) + SUM(bill_dr3)) as total')->join('mt_jenis_tindakan', 'mt_jenis_tindakan.kode_jenis_tindakan=tc_trans_pelayanan.jenis_tindakan', 'left')->where('CAST(tgl_transaksi as DATE) = '."'".date('Y-m-d')."'".'')->group_by('mt_jenis_tindakan.jenis_tindakan')->get('tc_trans_pelayanan');

			$pendapatan_2 = $this->db->select('mt_perusahaan.nama_perusahaan, (SUM(bill_rs) + SUM(bill_dr1) + SUM(bill_dr2) + SUM(bill_dr3)) as total')->join('mt_perusahaan', 'mt_perusahaan.kode_perusahaan=tc_trans_pelayanan.kode_perusahaan', 'left')->where('CAST(tgl_transaksi as DATE) = '."'".date('Y-m-d')."'".' ')->group_by('mt_perusahaan.nama_perusahaan')->get('tc_trans_pelayanan');

			$title = 'HARIAN, '.date('d/m/Y').'';
		}

		if($_GET['flag'] == 'month'){
			$kunjungan = $this->db->select('COUNT(id_tc_kunjungan) as total, SUBSTRING(kode_bagian_tujuan, 1,2) as kode_unit')->where('status_batal IS NULL')->where('MONTH(tgl_masuk) = '.date('m').' AND YEAR(tgl_masuk) = '.date('Y').'')->group_by('SUBSTRING(kode_bagian_tujuan, 1,2)')->get('tc_kunjungan');

			$pendapatan = $this->db->select('mt_jenis_tindakan.jenis_tindakan, (SUM(bill_rs) + SUM(bill_dr1) + SUM(bill_dr2) + SUM(bill_dr3)) as total')->join('mt_jenis_tindakan', 'mt_jenis_tindakan.kode_jenis_tindakan=tc_trans_pelayanan.jenis_tindakan', 'left')->where('MONTH(tgl_transaksi) = '.date('m').' AND YEAR(tgl_transaksi) = '.date('Y').'')->group_by('mt_jenis_tindakan.jenis_tindakan')->get('tc_trans_pelayanan');

			$pendapatan_2 = $this->db->select('mt_perusahaan.nama_perusahaan, (SUM(bill_rs) + SUM(bill_dr1) + SUM(bill_dr2) + SUM(bill_dr3)) as total')->join('mt_perusahaan', 'mt_perusahaan.kode_perusahaan=tc_trans_pelayanan.kode_perusahaan', 'left')->where('MONTH(tgl_transaksi) = '.date('m').' AND YEAR(tgl_transaksi) = '.date('Y').' ')->group_by('mt_perusahaan.nama_perusahaan')->get('tc_trans_pelayanan');

			$title = 'BULANAN, '.strtoupper($this->tanggal->getBulan(date('m'))).'';
		}

		if($_GET['flag'] == 'year'){
			$kunjungan = $this->db->select('COUNT(id_tc_kunjungan) as total, SUBSTRING(kode_bagian_tujuan, 1,2) as kode_unit')->where('status_batal IS NULL')->where('YEAR(tgl_masuk) = '.date('Y').'')->group_by('SUBSTRING(kode_bagian_tujuan, 1,2)')->get('tc_kunjungan');

			$pendapatan = $this->db->select('mt_jenis_tindakan.jenis_tindakan, (SUM(bill_rs) + SUM(bill_dr1) + SUM(bill_dr2) + SUM(bill_dr3)) as total')->join('mt_jenis_tindakan', 'mt_jenis_tindakan.kode_jenis_tindakan=tc_trans_pelayanan.jenis_tindakan', 'left')->where('YEAR(tgl_transaksi) = '.date('Y').'')->group_by('mt_jenis_tindakan.jenis_tindakan')->get('tc_trans_pelayanan');

			$pendapatan_2 = $this->db->select('mt_perusahaan.nama_perusahaan, (SUM(bill_rs) + SUM(bill_dr1) + SUM(bill_dr2) + SUM(bill_dr3)) as total')->join('mt_perusahaan', 'mt_perusahaan.kode_perusahaan=tc_trans_pelayanan.kode_perusahaan', 'left')->where('YEAR(tgl_transaksi) = '.date('Y').' ')->group_by('mt_perusahaan.nama_perusahaan')->get('tc_trans_pelayanan');
			$title = 'TAHUNAN, '.date('Y').'';
		}

		// split by unit
		// print_r($this->db->last_query());die;
		$data['title'] = $title;
		// kunjungan
		$data['kunjungan'] = $kunjungan->result();
		$data['pendapatan'] = $pendapatan->result();
		$data['pendapatan_2'] = $pendapatan_2->result();

		// pendapatan
		// foreach ($pendapatan->result() as $key => $value) {
		// 	if($value->kode_tc_trans_kasir == null){
		// 		$getData['belum_submit'][] = $value;
		// 	}else{
		// 		$getData['submit'][] = $value;
		// 	}
		// }

		// // array sum
		// $sumArraySubmit = array();
		// foreach ($getData['submit'] as $k=>$subArray) {
		//   foreach ($subArray as $id=>$value) {
		//   	if($id != 'kode_tc_trans_kasir'){
		//     	$sumArraySubmit[$id] += $value;
		//   	}
		//   }
		// }

		// $sumArrayBlmSubmit = array();
		// foreach ($getData['belum_submit'] as $k2=>$subArray2) {
		//   foreach ($subArray2 as $id2=>$value2) {
		//   	if($id2 != 'kode_tc_trans_kasir'){
		//     	$sumArrayBlmSubmit[$id2] += $value2;
		//   	}
		//   }
		// }

		// $data['pendapatan'] = array('submit' => $sumArraySubmit, 'blm_submit' => $sumArrayBlmSubmit);

		return $data;
	}


}
