<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Log_proses_resep_obat_model extends CI_Model {

	var $table = 'fr_tc_far';
	var $column = array('nama_pasien', 'fr_tc_far.no_mr');
	var $select = 'fr_tc_far.no_registrasi, fr_tc_far.kode_trans_far,nama_pasien,dokter_pengirim,no_resep,fr_tc_far.no_kunjungan,fr_tc_far.no_mr, fr_tc_far.kode_pesan_resep, tgl_trans, nama_pelayanan, alamat_pasien, telpon_pasien, fr_tc_far.status_transaksi, fr_tc_far.jenis_resep, status_terima, flag_trans, tgl_pesan, log_time_1, log_time_2, log_time_3, log_time_4, log_time_5, log_time_6';

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
		$this->db->where('status_terima NOT IN (1,2)');
		$this->db->where('flag_trans', 'RJ');
		// $this->db->where('e_resep', 1);
		$this->db->where('CAST(tgl_pesan as DATE) = '."'".date('Y-m-d')."'".'');
		// $this->db->where('log_time_6 is null');

	}

	private function _get_datatables_query()
	{
		
		$this->_main_query();

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
		$this->db->order_by('tgl_trans', 'ASC');
		$this->db->where('log_time_6 is null');
		$query = $this->db->get();
		// print_r($this->db->last_query());die;
		return $query->result();
	}

	function get_data()
	{
		$this->_main_query();
		$this->db->order_by('tgl_trans', 'ASC');
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
