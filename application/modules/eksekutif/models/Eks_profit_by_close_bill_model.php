<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Eks_profit_by_close_bill_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	function get_datatables()
	{
		$query = $this->db->query("EXEC get_trx_report @start_date = '".$_GET['start_date']."', @end_date = '".$_GET['end_date']."' ");
		return $query->result();
	}

	function get_trx_prb()
	{
		$query = $this->db->query("EXEC get_trx_report_obat_prb_group_by_sep @start_date = '".$_GET['start_date']."', @end_date = '".$_GET['end_date']."' ");
		return $query->result();
	}

	function get_trx_pembelian_bebas()
	{
		$query = $this->db->query("EXEC get_trx_report_obat_pb @start_date = '".$_GET['start_date']."', @end_date = '".$_GET['end_date']."' ");
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
	
}
