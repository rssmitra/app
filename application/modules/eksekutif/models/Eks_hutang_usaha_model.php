<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Eks_hutang_usaha_model extends CI_Model {

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
			// hutang
			$prd_hutang_medis = $this->db->select('SUM(dpp) as total')->join('tc_penerimaan_barang', 'tc_penerimaan_barang.id_penerimaan=tc_penerimaan_barang_detail.id_penerimaan','left')->where('CAST(tgl_penerimaan as DATE) BETWEEN '."'".$_GET['from_tgl']."'".' AND '."'".$_GET['to_tgl']."'".' ')->get('tc_penerimaan_barang_detail');

			$day_hutang_medis = $this->db->select('SUM(dpp) as total')->join('tc_penerimaan_barang', 'tc_penerimaan_barang.id_penerimaan=tc_penerimaan_barang_detail.id_penerimaan','left')->where('CAST(tgl_penerimaan as DATE) = '."'".date('Y-m-d')."'".' ')->get('tc_penerimaan_barang_detail');

			$mth_hutang_medis = $this->db->select('SUM(dpp) as total')->join('tc_penerimaan_barang', 'tc_penerimaan_barang.id_penerimaan=tc_penerimaan_barang_detail.id_penerimaan','left')->where('MONTH(tgl_penerimaan) = '."'".date('m')."'".' AND YEAR(tgl_penerimaan) = '.date('Y').' ')->get('tc_penerimaan_barang_detail');

			$yr_hutang_medis = $this->db->select('SUM(dpp) as total')->join('tc_penerimaan_barang', 'tc_penerimaan_barang.id_penerimaan=tc_penerimaan_barang_detail.id_penerimaan','left')->where('YEAR(tgl_penerimaan) = '.date('Y').' ')->get('tc_penerimaan_barang_detail');


			$prd_hutang_nm = $this->db->select('SUM(dpp) as total')->join('tc_penerimaan_barang_nm', 'tc_penerimaan_barang_nm.id_penerimaan=tc_penerimaan_barang_nm_detail.id_penerimaan','left')->where('CAST(tgl_penerimaan as DATE) BETWEEN '."'".$_GET['from_tgl']."'".' AND '."'".$_GET['to_tgl']."'".' ')->get('tc_penerimaan_barang_nm_detail');

			$day_hutang_nm = $this->db->select('SUM(dpp) as total')->join('tc_penerimaan_barang_nm', 'tc_penerimaan_barang_nm.id_penerimaan=tc_penerimaan_barang_nm_detail.id_penerimaan','left')->where('CAST(tgl_penerimaan as DATE) = '."'".date('Y-m-d')."'".' ')->get('tc_penerimaan_barang_nm_detail');

			$mth_hutang_nm = $this->db->select('SUM(dpp) as total')->join('tc_penerimaan_barang_nm', 'tc_penerimaan_barang_nm.id_penerimaan=tc_penerimaan_barang_nm_detail.id_penerimaan','left')->where('MONTH(tgl_penerimaan) = '."'".date('m')."'".' AND YEAR(tgl_penerimaan) = '.date('Y').' ')->get('tc_penerimaan_barang_nm_detail');

			$yr_hutang_nm = $this->db->select('SUM(dpp) as total')->join('tc_penerimaan_barang_nm', 'tc_penerimaan_barang_nm.id_penerimaan=tc_penerimaan_barang_nm_detail.id_penerimaan','left')->where('YEAR(tgl_penerimaan) = '.date('Y').' ')->get('tc_penerimaan_barang_nm_detail');


			$data['pendapatan'] = array(
				'periode_medis' => $prd_hutang_medis->row()->total,
				'day_medis' => $day_hutang_medis->row()->total,
				'month_medis' => $mth_hutang_medis->row()->total,
				'year_medis' => $yr_hutang_medis->row()->total,

				'periode_nm' => $prd_hutang_nm->row()->total,
				'day_nm' => $day_hutang_nm->row()->total,
				'month_nm' => $mth_hutang_nm->row()->total,
				'year_nm' => $yr_hutang_nm->row()->total,

			);
			$fields = array();
			$title = '<span style="font-size: 16px;">Laporan Hutang Usaha Berdasarkan Barang yang sudah diterima</span>';
			$subtitle = 'Source: RSSM - SIRS';
		}

		if($params['prefix']==2){
			$title = '<span style="font-size:13.5px">Laporan Hutang Usaha Berdasarkan Penerimaan Barang Tahun '.date('Y').'</span>';
				$subtitle = 'Source: RSSM - SIRS';
	
				// medis
				$query = $this->db->select('MONTH(tgl_penerimaan) as bulan, SUM(dpp) as total')->join('tc_penerimaan_barang', 'tc_penerimaan_barang.id_penerimaan=tc_penerimaan_barang_detail.id_penerimaan','left')->where('YEAR(tgl_penerimaan) = '.date('Y').' ')->group_by('MONTH(tgl_penerimaan)')->get('tc_penerimaan_barang_detail');
	
				$fields[0] = array('Medis'=>'total');
				$data[0] = $query->result_array();
	
				// data2
				$query2 = $this->db->select('MONTH(tgl_penerimaan) as bulan, SUM(dpp) as total')->join('tc_penerimaan_barang_nm', 'tc_penerimaan_barang_nm.id_penerimaan=tc_penerimaan_barang_nm_detail.id_penerimaan','left')->where('YEAR(tgl_penerimaan) = '.date('Y').' ')->group_by('MONTH(tgl_penerimaan)')->get('tc_penerimaan_barang_nm_detail');	
				$fields[1] = array('Non_Medis'=>'total');
				$data[1] = $query2->result_array();
			
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
