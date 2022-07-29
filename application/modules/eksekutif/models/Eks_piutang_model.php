<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Eks_piutang_model extends CI_Model {

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
			$prd_piutang = $this->db->select('SUM(tc_tagih_det.jumlah_tagih) as total')->join('tc_tagih_det', 'tc_tagih_det.id_tc_tagih=tc_tagih.id_tc_tagih','left')->where('(tc_tagih.id_tertagih NOT IN (120, 221, 0, 299))')->where('CAST(tgl_tagih as DATE) BETWEEN '."'".$_GET['from_tgl']."'".' AND '."'".$_GET['to_tgl']."'".' ')->get('tc_tagih');

			$day_piutang = $this->db->select('SUM(tc_tagih_det.jumlah_tagih) as total')->join('tc_tagih_det', 'tc_tagih_det.id_tc_tagih=tc_tagih.id_tc_tagih','left')->where('(tc_tagih.id_tertagih NOT IN (120, 221, 0, 299))')->where('CAST(tgl_tagih as DATE) = '."'".date('Y-m-d')."'".' ')->get('tc_tagih');

			$mth_piutang = $this->db->select('SUM(tc_tagih_det.jumlah_tagih) as total')->join('tc_tagih_det', 'tc_tagih_det.id_tc_tagih=tc_tagih.id_tc_tagih','left')->where('(tc_tagih.id_tertagih NOT IN (120, 221, 0, 299))')->where('MONTH(tgl_tagih) = '."'".date('m')."'".' AND YEAR(tgl_tagih) = '.date('Y').' ')->get('tc_tagih');

			$yr_piutang = $this->db->select('SUM(tc_tagih_det.jumlah_tagih) as total')->join('tc_tagih_det', 'tc_tagih_det.id_tc_tagih=tc_tagih.id_tc_tagih','left')->where('(tc_tagih.id_tertagih NOT IN (120, 221, 0, 299))')->where('YEAR(tgl_tagih) = '.date('Y').' ')->get('tc_tagih');

			$data['piutang'] = array(
				'periode' => $prd_piutang->row()->total,
				'day' => $day_piutang->row()->total,
				'month' => $mth_piutang->row()->total,
				'year' => $yr_piutang->row()->total,

			);
			$fields = array();
			$title = '<span style="font-size: 16px;">Laporan Piutang Usaha Berdasarkan Tanggal Penagihan</span>';
			$subtitle = 'Source: RSSM - SIRS';
		}

		if($params['prefix']==2){
	
			$query = $this->db->select('MONTH(tgl_tagih) as bulan, SUM(tc_tagih_det.jumlah_tagih) as total')->join('tc_tagih_det', 'tc_tagih_det.id_tc_tagih=tc_tagih.id_tc_tagih','left')->where('YEAR(tgl_tagih) = '.date('Y').' ')->where('(tc_tagih.id_tertagih NOT IN (120, 221, 0, 299))')->group_by('MONTH(tgl_tagih)')->get('tc_tagih');
			$fields = array('Total_Piutang'=>'total');
			$title = '<span style="font-size:13.5px">Piutang Perusahaan Berdasarkan Tanggal Penagihan Invoice '.date('Y').' '.COMP_LONG.'</span>';
			$subtitle = 'Source: RSSM - SIRS';
			/*excecute query*/
			$data = $query->result_array();
			
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
			$piutang = $this->db->select('no_invoice_tagih, tgl_tagih, nama_tertagih, SUM(tc_tagih_det.jumlah_tagih) as total')->join('tc_tagih_det', 'tc_tagih_det.id_tc_tagih=tc_tagih.id_tc_tagih','left')->where('(tc_tagih.id_tertagih NOT IN (120, 221, 0, 299))')->where('CAST(tgl_tagih as DATE) BETWEEN '."'".$_GET['from_tgl']."'".' AND '."'".$_GET['to_tgl']."'".' ')->group_by('no_invoice_tagih, tgl_tagih, nama_tertagih')->order_by('tgl_tagih')->get('tc_tagih');

			$resume_piutang = $this->db->select('nama_tertagih, SUM(tc_tagih_det.jumlah_tagih) as total')->join('tc_tagih_det', 'tc_tagih_det.id_tc_tagih=tc_tagih.id_tc_tagih','left')->where('(tc_tagih.id_tertagih NOT IN (120, 221, 0, 299))')->where('CAST(tgl_tagih as DATE) BETWEEN '."'".$_GET['from_tgl']."'".' AND '."'".$_GET['to_tgl']."'".' ')->group_by('nama_tertagih')->order_by('nama_tertagih', 'ASC')->get('tc_tagih');

			$title = 'PERIODE, '.$this->tanggal->formatDateDmy($_GET['from_tgl']).' s/d '.$this->tanggal->formatDateDmy($_GET['to_tgl']).' ';
		}

		if($_GET['flag'] == 'day'){
			$piutang = $this->db->select('no_invoice_tagih, tgl_tagih, nama_tertagih, SUM(tc_tagih_det.jumlah_tagih) as total')->join('tc_tagih_det', 'tc_tagih_det.id_tc_tagih=tc_tagih.id_tc_tagih','left')->where('CAST(tgl_tagih as DATE) = '.date('Y-m-d').' ')->group_by('no_invoice_tagih, tgl_tagih, nama_tertagih')->order_by('tgl_tagih')->get('tc_tagih');

			$resume_piutang = $this->db->select('nama_tertagih, SUM(tc_tagih_det.jumlah_tagih) as total')->join('tc_tagih_det', 'tc_tagih_det.id_tc_tagih=tc_tagih.id_tc_tagih','left')->where('(tc_tagih.id_tertagih NOT IN (120, 221, 0, 299))')->where('CAST(tgl_tagih) = '.date('Y-mm-d').' ')->group_by('nama_tertagih')->order_by('nama_tertagih', 'ASC')->get('tc_tagih');

			$title = 'HARIAN, '.date('d/m/Y').'';
		}

		if($_GET['flag'] == 'month'){
			$piutang = $this->db->select('no_invoice_tagih, tgl_tagih, nama_tertagih, SUM(tc_tagih_det.jumlah_tagih) as total')->join('tc_tagih_det', 'tc_tagih_det.id_tc_tagih=tc_tagih.id_tc_tagih','left')->where('(tc_tagih.id_tertagih NOT IN (120, 221, 0, 299))')->where('YEAR(tgl_tagih) = '.date('Y').' AND MONTH(tgl_tagih)='.date('m').' ')->group_by('no_invoice_tagih, tgl_tagih, nama_tertagih')->order_by('tgl_tagih')->get('tc_tagih');

			$resume_piutang = $this->db->select('nama_tertagih, SUM(tc_tagih_det.jumlah_tagih) as total')->join('tc_tagih_det', 'tc_tagih_det.id_tc_tagih=tc_tagih.id_tc_tagih','left')->where('(tc_tagih.id_tertagih NOT IN (120, 221, 0, 299))')->where('YEAR(tgl_tagih) = '.date('Y').' AND MONTH(tgl_tagih)='.date('m').' ')->group_by('nama_tertagih')->order_by('nama_tertagih', 'ASC')->get('tc_tagih');

			$title = 'BULANAN, '.strtoupper($this->tanggal->getBulan(date('m'))).'';
		}

		if($_GET['flag'] == 'year'){
			$piutang = $this->db->select('no_invoice_tagih, tgl_tagih, nama_tertagih, SUM(tc_tagih_det.jumlah_tagih) as total')->join('tc_tagih_det', 'tc_tagih_det.id_tc_tagih=tc_tagih.id_tc_tagih','left')->where('(tc_tagih.id_tertagih NOT IN (120, 221, 0, 299))')->where('YEAR(tgl_tagih) = '.date('Y').' ')->group_by('no_invoice_tagih, tgl_tagih, nama_tertagih')->order_by('tgl_tagih')->get('tc_tagih');

			$resume_piutang = $this->db->select('nama_tertagih, SUM(tc_tagih_det.jumlah_tagih) as total')->join('tc_tagih_det', 'tc_tagih_det.id_tc_tagih=tc_tagih.id_tc_tagih','left')->where('(tc_tagih.id_tertagih NOT IN (120, 221, 0, 299))')->where('YEAR(tgl_tagih) = '.date('Y').' ')->group_by('nama_tertagih')->order_by('nama_tertagih', 'ASC')->get('tc_tagih');

			$title = 'TAHUNAN, '.date('Y').'';
		}

		// split by unit
		// print_r($this->db->last_query());die;
		$data['title'] = $title;
		// kunjungan
		$data['piutang'] = $piutang->result();
		$data['resume_piutang'] = $resume_piutang->result();

		return $data;
	}


}
