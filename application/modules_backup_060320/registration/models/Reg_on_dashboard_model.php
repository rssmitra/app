<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reg_on_dashboard_model extends CI_Model {

	var $table = 'regon_booking';
	var $column = array('regon_booking.regon_booking_kode','regon_booking.regon_booking_tanggal_perjanjian','regon_booking.regon_booking_no_mr','regon_booking.regon_booking_instalasi','regon_booking.regon_booking_hari','regon_booking.regon_booking_jenis_penjamin');
	var $select = 'regon_booking.*';

	var $order = array('regon_booking.regon_booking_id' => 'DESC');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _main_query(){
		$this->db->select($this->select);
		$this->db->from($this->table);
		/*if isset parameter*/
		if(isset($_GET['klinik'])){
			if($_GET['klinik']!='' || $_GET['klinik']!=0){
				$this->db->where('regon_booking_klinik', $_GET['klinik']);
			}
		}

		if(isset($_GET['dokter'])){
			if($_GET['dokter']!='' || $_GET['dokter']!=0){
				$this->db->where('regon_booking_kode_dokter', $_GET['dokter']);
			}
		}

		if (isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' || isset($_GET['to_tgl']) AND $_GET['to_tgl'] != '') {
            $this->db->where("regon_booking.regon_booking_tanggal_perjanjian >= '".$this->tanggal->selisih($_GET['from_tgl'],'-1')."'" );
            $this->db->where("regon_booking.regon_booking_tanggal_perjanjian <= '".$this->tanggal->selisih($_GET['to_tgl'],'+1')."'" );
        }
        /*end parameter*/


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
		
		if(isset($_POST['order']))
		{
			$this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} 
		else if(isset($this->order))
		{
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}
	
	function get_datatables()
	{
		$this->_get_datatables_query();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
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
