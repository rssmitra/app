<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Eks_poli_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _main_query(){
		$this->db->select('b.no_kunjungan, d.nama_bagian,  (SUM(bill_rs) + SUM(bill_dr1) + SUM(bill_dr2) + SUM(bill_dr3)) as total');
		$this->db->from('tc_trans_pelayanan b');
		$this->db->join('tc_kunjungan c', 'c.no_kunjungan=b.no_kunjungan','left');
		$this->db->join('mt_bagian d', 'd.kode_bagian=c.kode_bagian_tujuan','left');
		$this->db->group_by('b.no_kunjungan, d.nama_bagian');
		
	}

	function get_content_data($params) {

		/*total klaim berdasarkan nomor sep per tahun existing*/
		/*based query*/
		if($params['prefix']==1){
			$data = array();
			// periode
			$this->_main_query();			
			$this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
			FROM tc_kunjungan a
			where CAST(tgl_masuk as DATE) BETWEEN '."'".$_GET['from_tgl']."'".' AND '."'".$_GET['to_tgl']."'".'   )');
			$prd_dt = $this->db->get();
			// echo '<pre>';print_r($prd_dt->result());die;
			// day
			$this->_main_query();			
			$this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
			FROM tc_kunjungan a
			where CAST(tgl_masuk as DATE) = '."'".date('Y-m-d')."'".'  )');			
			$dy_dt = $this->db->get();

			// month
			$this->_main_query();			
			$this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
			FROM tc_kunjungan a
			where MONTH(tgl_masuk) = '.date('m').' AND YEAR(tgl_masuk) = '.date('Y').' )');			
			$mth_dt = $this->db->get();

			// year
			$this->_main_query();			
			$this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
			FROM tc_kunjungan a
			where YEAR(tgl_masuk) = '.date('Y').' )');			
			$yr_dt = $this->db->get();

			$data = array(
				'prd_dt' => $prd_dt->result(),
				'dy_dt' => $dy_dt->result(),
				'mth_dt' => $mth_dt->result(),
				'yr_dt' => $yr_dt->result(),
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

			// periode
			$this->db->select('b.kode_perusahaan, SUBSTRING(c.kode_bagian_tujuan, 1, 2) as kode_unit');
			$this->_main_query();			
			$this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
			FROM tc_kunjungan a
			where CAST(tgl_masuk as DATE) BETWEEN '."'".$_GET['from_tgl']."'".' AND '."'".$_GET['to_tgl']."'".'   )');
			$this->db->group_by('b.kode_perusahaan, SUBSTRING(c.kode_bagian_tujuan, 1, 2)');
			$prd_dt = $this->db->get();
			
			// echo '<pre>'; print_r($prd_dt->result());die;

			$title = 'PERIODE, '.$this->tanggal->formatDateDmy($_GET['from_tgl']).' s/d '.$this->tanggal->formatDateDmy($_GET['to_tgl']).' ';
		}

		if($_GET['flag'] == 'day'){
			$this->db->select('b.kode_perusahaan, SUBSTRING(c.kode_bagian_tujuan, 1, 2) as kode_unit');
			$this->_main_query();			
			$this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
			FROM tc_kunjungan a
			where CAST(tgl_masuk as DATE) = '."'".date('Y-m-d')."'".' )');
			$this->db->group_by('b.kode_perusahaan, SUBSTRING(c.kode_bagian_tujuan, 1, 2)');
			$prd_dt = $this->db->get();

			$title = 'HARIAN, '.date('d/m/Y').'';
		}

		if($_GET['flag'] == 'month'){
			$this->db->select('b.kode_perusahaan, SUBSTRING(c.kode_bagian_tujuan, 1, 2) as kode_unit');
			$this->_main_query();			
			$this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
			FROM tc_kunjungan a
			where MONTH(tgl_masuk) = '.date('m').' AND YEAR(tgl_masuk) = '.date('Y').' )');
			$this->db->group_by('b.kode_perusahaan, SUBSTRING(c.kode_bagian_tujuan, 1, 2)');
			$prd_dt = $this->db->get();

			$title = 'BULANAN, '.strtoupper($this->tanggal->getBulan(date('m'))).'';
		}

		if($_GET['flag'] == 'year'){
			$this->db->select('b.kode_perusahaan, SUBSTRING(c.kode_bagian_tujuan, 1, 2) as kode_unit');
			$this->_main_query();			
			$this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
			FROM tc_kunjungan a
			where YEAR(tgl_masuk) = '.date('Y').' )');
			$this->db->group_by('b.kode_perusahaan, SUBSTRING(c.kode_bagian_tujuan, 1, 2)');
			$prd_dt = $this->db->get();

			$title = 'TAHUNAN, '.date('Y').'';
		}

		// split by unit
		// print_r($this->db->last_query());die;
		$data['flag'] = $_GET['flag'];
		$data['title'] = $title;
		// kunjungan
		$data['result'] = $prd_dt->result();

		return $data;
	}

	function get_detail_data_unit()
	{
		$data = array();
		$this->db->select('c.kode_bagian_tujuan, SUBSTRING(c.kode_bagian_tujuan, 1, 2) as kode_unit');
		$this->_main_query();	
		$this->db->where('SUBSTRING(c.kode_bagian_tujuan, 1, 2) = '."'".$_GET['kode']."'".' ');
		if($_GET['flag'] == 'periode'){

			// periode
			$this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
			FROM tc_kunjungan a
			where CAST(tgl_masuk as DATE) BETWEEN '."'".$_GET['from_tgl']."'".' AND '."'".$_GET['to_tgl']."'".'   )');
			$this->db->group_by('c.kode_bagian_tujuan, SUBSTRING(c.kode_bagian_tujuan, 1, 2)');
			$prd_dt = $this->db->get();

			$title = 'PERIODE, '.$this->tanggal->formatDateDmy($_GET['from_tgl']).' s/d '.$this->tanggal->formatDateDmy($_GET['to_tgl']).' ';
		}

		if($_GET['flag'] == 'day'){			
			$this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
			FROM tc_kunjungan a
			where CAST(tgl_masuk as DATE) = '."'".date('Y-m-d')."'".' )');
			$this->db->group_by('c.kode_bagian_tujuan, SUBSTRING(c.kode_bagian_tujuan, 1, 2)');
			$prd_dt = $this->db->get();

			$title = 'HARIAN, '.date('d/m/Y').'';
		}

		if($_GET['flag'] == 'month'){			
			$this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
			FROM tc_kunjungan a
			where MONTH(tgl_masuk) = '.date('m').' AND YEAR(tgl_masuk) = '.date('Y').' )');
			$this->db->group_by('c.kode_bagian_tujuan, SUBSTRING(c.kode_bagian_tujuan, 1, 2)');
			$prd_dt = $this->db->get();

			$title = 'BULANAN, '.strtoupper($this->tanggal->getBulan(date('m'))).'';
		}

		if($_GET['flag'] == 'year'){		
			$this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
			FROM tc_kunjungan a
			where YEAR(tgl_masuk) = '.date('Y').' )');
			$this->db->group_by('c.kode_bagian_tujuan, SUBSTRING(c.kode_bagian_tujuan, 1, 2)');
			$prd_dt = $this->db->get();

			$title = 'TAHUNAN, '.date('Y').'';
		}

		// split by unit
		// print_r($this->db->last_query());die;
		$data['flag'] = $_GET['flag'];
		$data['title'] = $title;
		// kunjungan
		$data['result'] = $prd_dt->result();

		return $data;
	}

	function get_detail_data_pasien()
	{
		$data = array();
		$this->db->select('b.nama_pasien_layan, b.no_mr');
		$this->_main_query();	
		$this->db->where('c.kode_bagian_tujuan = '."'".$_GET['kode']."'".' ');
		$this->db->group_by('b.nama_pasien_layan, b.no_mr');

		if($_GET['flag'] == 'periode'){

			// periode
			$this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
			FROM tc_kunjungan a
			where CAST(tgl_masuk as DATE) BETWEEN '."'".$_GET['from_tgl']."'".' AND '."'".$_GET['to_tgl']."'".'   )');
			$prd_dt = $this->db->get();

			$title = 'PERIODE, '.$this->tanggal->formatDateDmy($_GET['from_tgl']).' s/d '.$this->tanggal->formatDateDmy($_GET['to_tgl']).' ';
		}

		if($_GET['flag'] == 'day'){			
			$this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
			FROM tc_kunjungan a
			where CAST(tgl_masuk as DATE) = '."'".date('Y-m-d')."'".' )');
			$prd_dt = $this->db->get();

			$title = 'HARIAN, '.date('d/m/Y').'';
		}

		if($_GET['flag'] == 'month'){			
			$this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
			FROM tc_kunjungan a
			where MONTH(tgl_masuk) = '.date('m').' AND YEAR(tgl_masuk) = '.date('Y').' )');
			$prd_dt = $this->db->get();

			$title = 'BULANAN, '.strtoupper($this->tanggal->getBulan(date('m'))).'';
		}

		if($_GET['flag'] == 'year'){		
			$this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
			FROM tc_kunjungan a
			where YEAR(tgl_masuk) = '.date('Y').' )');
			$prd_dt = $this->db->get();

			$title = 'TAHUNAN, '.date('Y').'';
		}

		// split by unit
		// print_r($this->db->last_query());die;
		$data['flag'] = $_GET['flag'];
		$data['title'] = $title;
		// kunjungan
		$data['result'] = $prd_dt->result();

		return $data;
	}


}
