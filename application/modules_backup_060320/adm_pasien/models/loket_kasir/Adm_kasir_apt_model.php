<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adm_kasir_apt_model extends CI_Model {

	var $table = 'tc_trans_pelayanan';
	var $column = array('a.nama_pasien_layan');
	var $select = 'a.kode_tc_trans_kasir, a.no_registrasi, a.kode_trans_far, nama_pasien_layan AS nama_pasien, b.tgl_trans';
	var $order = array('a.kode_trans_far' => 'DESC');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _main_query(){

		
		$this->db->select($this->select);
		$this->db->select('SUM((CASE WHEN status_kredit = 1 THEN (-1) ELSE 1 END) * bill_rs) AS bill_rs, SUM((CASE WHEN status_kredit = 1 THEN (-1) ELSE 1 END) * bill_dr1) AS bill_dr1, SUM((CASE WHEN status_kredit = 1 THEN (-1) ELSE 1 END) * bill_dr2) AS bill_dr2, SUM((CASE WHEN status_kredit = 1 THEN (-1) ELSE 1 END) * bill_dr3) AS bill_dr3, SUM((CASE WHEN status_kredit = 1 THEN (-1) ELSE 1 END) * lain_lain) AS lain_lain ');
		$this->db->from($this->table.' a');
		$this->db->join('fr_tc_far b','b.kode_trans_far=a.kode_trans_far','left');
		$this->db->where('a.kode_bagian', '060101');
		$this->db->where('a.no_registrasi', 0);
		$this->db->where('status_selesai', 2);
		$this->db->where('kode_tc_trans_kasir IS NULL');
		$this->db->group_by('nama_pasien_layan, a.kode_trans_far, a.no_registrasi, a.kode_tc_trans_kasir, b.tgl_trans');

		if ( isset($_GET['search_by']) ) {
			
			
			if(isset($_GET['keyword']) AND $_GET['keyword'] != ''){
				$this->db->where('c.'.$_GET['search_by'], $_GET['keyword']);		
			}
			
			if( isset($_GET['is_with_date']) AND $_GET['is_with_date'] == 1 ){

				$this->db->where("convert(varchar,b.tgl_jam_masuk,23) between '".$_GET['from_tgl']."' and '".$_GET['to_tgl']."'");

			}
			
						
		}else{

			$this->db->where("YEAR(tgl_trans)", date('Y'));
			$this->db->where("MONTH(tgl_trans)", date('m'));
			$this->db->where("DAY(tgl_trans)", date('d'));
			
		}
		
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
		$query = $this->db->get()->result();
		
		return $query;
	}

	function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get()->result();
		return count($query);
	}

	public function count_all()
	{
		$this->_get_datatables_query();
		$query = $this->db->get()->result();
		return count($query);
	}

}
