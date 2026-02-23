<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Log_proses_resep_obat_model extends CI_Model {

	var $table = 'fr_tc_far';
	var $column = array('nama_pasien', 'fr_tc_far.no_mr');
	var $select = 'fr_tc_far.no_registrasi, fr_tc_far.kode_trans_far,nama_pasien,dokter_pengirim,no_resep,fr_tc_far.no_kunjungan,fr_tc_far.no_mr, fr_tc_far.kode_pesan_resep, tgl_trans, nama_pelayanan, alamat_pasien, telpon_pasien, fr_tc_far.status_transaksi, fr_tc_far.jenis_resep, status_terima, flag_trans, tgl_pesan, log_time_1, log_time_2, log_time_3, log_time_4, log_time_5, log_time_6, fr_tc_pesan_resep.status_batal, status_tebus, status_ambil_obat, nama_bagian, fr_tc_pesan_resep.kode_bagian_asal, kode_perusahaan';

	var $order = array('tgl_pesan' => 'ASC');

	public function __construct()
	{
		parent::__construct();
	}

	private function _main_query(){

		$this->db->select($this->select);
		$this->db->from($this->table);
		$this->db->join('fr_mt_profit_margin','fr_mt_profit_margin.kode_profit=fr_tc_far.kode_profit','left');
		$this->db->join('fr_tc_pesan_resep','fr_tc_pesan_resep.kode_pesan_resep=fr_tc_far.kode_pesan_resep','left');
		$this->db->join('mt_bagian','mt_bagian.kode_bagian=fr_tc_pesan_resep.kode_bagian_asal','left');
		$this->db->where('status_terima NOT IN (1,2)');
		$this->db->where('flag_trans', 'RJ');
		
		$this->db->where('SUBSTRING(fr_tc_pesan_resep.kode_bagian_asal, 1,2) !=', '03'); // exc rawap inap

	}

	private function _get_datatables_query()
	{
		
		$this->_main_query();
		if(isset($_GET['tanggal'])){
			$this->db->where('CAST(tgl_pesan as DATE) = '."'".$_GET['tanggal']."'".'');
		}else{
			$this->db->where('CAST(tgl_pesan as DATE) = '."'".date('Y-m-d')."'".'');
		}

		if(isset($_GET['flag']) AND $_GET['flag'] != ''){
			if($_GET['flag'] == 'resep_diterima'){
				$this->db->where('(log_time_1 is not null AND log_time_2 is null)');
				$this->db->order_by('tgl_trans', 'ASC');
			}else if($_GET['flag'] == 'proses_racikan'){
				$this->db->where('fr_tc_far.jenis_resep', 'racikan');
				$this->db->where('(log_time_2 is not null AND log_time_3 is null)');
				$this->db->order_by('log_time_2', 'ASC');
			}else if($_GET['flag'] == 'proses_etiket'){
				$this->db->where('fr_tc_far.jenis_resep', 'non_racikan');
				$this->db->where('(log_time_2 is not null AND (log_time_4 is null or log_time_4 is not null AND log_time_5 is null))');
				$this->db->order_by('log_time_2', 'ASC');
			}else if($_GET['flag'] == 'siap_diambil'){
				$this->db->where('(log_time_5 is not null AND log_time_6 is null)');
				$this->db->order_by('log_time_5', 'ASC');
			}else if($_GET['flag'] == 'selesai'){
				$this->db->where('(log_time_6 is not null)');
				$this->db->order_by('log_time_6', 'ASC');
			}
		}


		$i = 0;
	
		foreach ($this->column as $item) 
		{
			if($_POST['search']['value'])
				($i===0) ? $this->db->like($item, $_POST['search']['value']) : $this->db->or_like($item, $_POST['search']['value']);
			$column[$i] = $item;
			$i++;
		}
	}
	
	function get_datatables()
	{
		$this->_get_datatables_query();
		
		// $this->db->where('log_time_6 is null');
		$this->db->where('fr_tc_pesan_resep.status_batal is null');
		$query = $this->db->get();
		// print_r($this->db->last_query());die;
		return $query->result();
	}

	function get_data()
	{
		$this->_main_query();
		if(isset($_GET['tanggal'])){
			$this->db->where('CAST(tgl_pesan as DATE) = '."'".$_GET['tanggal']."'".'');
		}else{
			$this->db->where('CAST(tgl_pesan as DATE) = '."'".date('Y-m-d')."'".'');
		}
		$this->db->where('fr_tc_pesan_resep.status_batal is null');
		$this->db->order_by('tgl_trans', 'ASC');
		// print_r($this->db->last_query());die;
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered()
	{
		$this->_get_datatables_query_dt_pending();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all()
	{
		$this->_get_datatables_query_dt_pending();
		return $this->db->count_all_results();
	}
	

	
}
