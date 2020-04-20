<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Csm_data_perbandingan_model extends CI_Model {


	var $table = 'tc_registrasi';
	var $column = array('a.nama_pasien');
	var $select = 'a.no_registrasi, a.no_mr, d.nama_pasien, a.tgl_jam_masuk, a.no_sep, a.kode_perusahaan, b.csm_uhvd_no_sep, c.csm_rp_no_sep';
	var $order = array('a.no_registrasi' => 'ASC');
	

	public function __construct()
	{
		parent::__construct();
		$this->load->database('default', TRUE);
	}

	private function _main_query(){
		
		if (isset($_GET['month']) AND $_GET['month'] != '' ) {
			$month = $_GET['month'];
		}else{
			$month = 1;
		}

		if (isset($_GET['year']) AND $_GET['year'] != '' ) {
			$year = $_GET['year'];
		}else{
			$year = 2020;
		}

		$query = "select ".$this->select."
					from ".$this->table." a
					left join mt_master_pasien d on d.no_mr=a.no_mr
					full outer join csm_upload_hasil_verif_detail b on a.no_sep=b.csm_uhvd_no_sep
					full outer join csm_reg_pasien c on c.csm_rp_no_sep=a.no_sep
					where MONTH(a.tgl_jam_masuk) = ".$month." AND YEAR(a.tgl_jam_masuk) = ".$year." and a.kode_perusahaan = 120
						and (no_sep is not null or no_sep != '-' or no_sep !='') 
					order by no_sep ASC";


		$this->db->query($query);

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
		if (isset($_GET['month']) AND $_GET['month'] != '' ) {
			$month = $_GET['month'];
		}else{
			$month = 1;
		}

		if (isset($_GET['year']) AND $_GET['year'] != '' ) {
			$year = $_GET['year'];
		}else{
			$year = 2020;
		}

		// $query = "select a.no_registrasi, a.no_mr, d.nama_pasien, a.tgl_jam_masuk, a.no_sep, a.kode_perusahaan, b.csm_uhvd_no_sep, c.csm_rp_no_sep 
		// 	from  tc_trans_kasir e 
		// 	full outer join tc_registrasi a on a.no_registrasi=e.no_registrasi
		// 	left join mt_master_pasien d on d.no_mr=a.no_mr 
		// 	full outer join csm_upload_hasil_verif_detail b on a.no_sep=b.csm_uhvd_no_sep 
		// 	full outer join csm_reg_pasien c on c.csm_rp_no_sep=a.no_sep 
		// 	where MONTH(a.tgl_jam_masuk) = 1 
		// 	AND YEAR(a.tgl_jam_masuk) = 2020 and a.kode_perusahaan = 120 and (no_sep like '0112R034%') 
		// 	and (tgl_jam_keluar is not null or a.status_batal != 1 or a.status_registrasi != 0) 
		// 	order by no_sep ASC";
		$query = "select ".$this->select."
					from ".$this->table." a
					left join mt_master_pasien d on d.no_mr=a.no_mr
					full outer join csm_upload_hasil_verif_detail b on a.no_sep=b.csm_uhvd_no_sep
					full outer join csm_reg_pasien c on c.csm_rp_no_sep=a.no_sep
					where MONTH(a.tgl_jam_masuk) = ".$month." AND YEAR(a.tgl_jam_masuk) = ".$year." and a.kode_perusahaan = 120
						and (no_sep like '0112R034%') and (tgl_jam_keluar is not null or a.status_batal != 1 or a.status_registrasi != 0)
					order by no_sep ASC";


		$query = $this->db->query($query);
		// print_r($this->db->last_query());die;
		return $query->result();
	}

	function get_data()
	{
		$this->_main_query();
		$this->db->group_by('no_sep');
		$this->db->order_by('tc_registrasi.tgl_transaksi_kasir', 'ASC');
		$this->db->order_by('tc_registrasi.no_sep', 'ASC');
		$query = $this->db->get();

		return $query->result();
	}

	function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		//print_r($query);die;
		return $query->num_rows();
	}

	public function count_all()
	{
		$this->_main_query();
		return $this->db->count_all_results();
	}

	public function get_by_id($id)
	{
		$this->_main_query();
		if(is_array($id)){
			$this->db->where_in(''.$this->table.'.csm_uhv_id',$id);
			$query = $this->db->get();
			return $query->result();
		}else{
			$this->db->where(''.$this->table.'.csm_uhv_id',$id);
			$query = $this->db->get();
			//echo '<pre>';print_r($this->db->last_query());die;
			return $query->row();
		}
		
	}

	
}
