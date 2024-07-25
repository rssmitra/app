<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Antrol_model extends CI_Model {

	var $table = 'tr_log_antrian';
	var $column = array('tr_log_antrian.kodebooking');
	var $select = 'tr_log_antrian.kodebooking, taskid, response_code, response_msg, timestamp, tc_registrasi.tgl_jam_masuk, no_registrasi';

	var $order = array('kodebooking' => 'ASC');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _main_query(){
		$this->db->select($this->select);
		$this->db->from($this->table);
		$this->db->join('tc_registrasi', 'tc_registrasi.kodebookingantrol = tr_log_antrian.kodebooking', 'left');
		$this->db->where('kode_perusahaan ', 120);
		$this->db->where('SUBSTRING(kode_bagian_masuk, 1,2) = ', '01');
		$this->db->where('kode_bagian_masuk !=', '013101');
		$this->db->where('response_code != ', 208);
	}

	private function _get_datatables_query()
	{
		
		$this->_main_query();
		// filter by parameter
		if (isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' || isset($_GET['to_tgl']) AND $_GET['to_tgl'] != '') {
			$this->db->where("CAST(tgl_jam_masuk as DATE) between '".$_GET['from_tgl']."' and '".$_GET['to_tgl']."'");
		}else{
			$this->db->where('CAST(tgl_jam_masuk as DATE) = ', date('Y-m-d'));
		}

		$i = 0;
	
		foreach ($this->column as $item) 
		{
			if($_POST['search']['value'])
				($i===0) ? $this->db->like($item, $_POST['search']['value']) : $this->db->or_like($item, $_POST['search']['value']);
			$column[$i] = $item;
			$i++;
		}
		
		$order = $this->order;
		$this->db->order_by(key($order), $order[key($order)]);
	}
	
	function get_datatables()
	{
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		// print_r($this->db->last_query());die;
		return $query->result();
	}

	function get_data_registrasi()
	{

		if (isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' || isset($_GET['to_tgl']) AND $_GET['to_tgl'] != '') {
			$where = "CAST(tgl_jam_masuk as DATE) between '".$_GET['from_tgl']."' AND '".$_GET['to_tgl']."'";
		}else{
			$where = "CAST(tgl_jam_masuk as DATE) = '".date('Y-m-d')."'";
		}
		$this->db->select('no_registrasi as kodebookingantrol', 'tgl_jam_masuk', '', '', '','');
		$this->db->from('tc_registrasi');
		$this->db->where('kode_perusahaan', 120);
		$this->db->where('SUBSTRING(kode_bagian_masuk, 1,2) = ', '01');
		$this->db->where('kode_bagian_masuk !=', '013101');
		$this->db->where('task_id is null');
		// $this->db->where("CAST(no_registrasi as VARCHAR(255)) not in (select kodebooking from tr_log_antrian where ".$where." GROUP BY kodebooking)");
		// filter by parameter
		if (isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' || isset($_GET['to_tgl']) AND $_GET['to_tgl'] != '') {
			$this->db->where("CAST(tgl_jam_masuk as DATE) between '".$_GET['from_tgl']."' and '".$_GET['to_tgl']."'");
		}else{
			$this->db->where('CAST(tgl_jam_masuk as DATE) = ', date('Y-m-d'));
		}
		
		$query = $this->db->get();
		// print_r($this->db->last_query());die;
		return $query->result();
	}



}
