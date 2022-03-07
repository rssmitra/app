<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Csm_verifikasi_costing_model extends CI_Model {


	var $table = 'csm_reg_pasien';
	var $column = array('csm_reg_pasien.csm_rp_no_sep','csm_reg_pasien.csm_rp_nama_pasien','csm_reg_pasien.csm_rp_no_mr', 'csm_dokumen_klaim.no_sep');
	var $select = 'csm_reg_pasien.no_registrasi,csm_dokumen_klaim.no_sep,csm_dokumen_klaim.tgl_transaksi_kasir,csm_dokumen_klaim.csm_dk_filename,csm_dokumen_klaim.csm_dk_fullpath,csm_dokumen_klaim.csm_dk_total_klaim,csm_dokumen_klaim.csm_dk_tipe, csm_reg_pasien.csm_rp_no_sep, csm_reg_pasien.csm_rp_no_mr, csm_reg_pasien.csm_rp_nama_pasien, csm_reg_pasien.csm_rp_tgl_masuk, csm_reg_pasien.csm_rp_tgl_keluar, csm_reg_pasien.csm_rp_nama_dokter, csm_reg_pasien.csm_rp_bagian, csm_reg_pasien.csm_rp_tipe, csm_reg_pasien.is_submitted, csm_reg_pasien.csm_rp_kode_bagian, csm_reg_pasien.created_date, csm_reg_pasien.created_by';
	var $order = array('csm_reg_pasien.csm_rp_no_sep' => 'ASC');
	

	public function __construct()
	{
		parent::__construct();
		$this->load->database('default', TRUE);
	}

	private function _main_query(){
		

		$curr_month = date('m')-1;

		$this->db->select($this->select);
		$this->db->from($this->table);
		$this->db->join('csm_dokumen_klaim', 'csm_dokumen_klaim.no_registrasi='.$this->table.'.no_registrasi', 'LEFT');


		if(isset($_GET['search_by'])) {
			if (isset($_GET['search_by']) AND $_GET['search_by'] != '' || isset($_GET['keyword']) AND $_GET['keyword'] != '' ) {
				if($_GET['search_by'] == 'csm_reg_pasien.csm_rp_nama_pasien'){
					$this->db->like($_GET['search_by'], $_GET['keyword']);	
				}else{
					$this->db->where("".$_GET['search_by']." = '".$_GET['keyword']."' " );
				}
			}
	
	
			if( isset($_GET['search_by_field']) AND $_GET['search_by_field'] != 'month_year' ){
				if (isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' || isset($_GET['to_tgl']) AND $_GET['to_tgl'] != '') {
					$this->db->where("CAST(".$_GET['search_by_field']." as DATE) BETWEEN '".$_GET['from_tgl']."' AND '".$_GET['to_tgl']."' " );
				}
			}
			
			if (isset($_GET['tipe']) AND $_GET['tipe'] != '' ) {
				if( $_GET['tipe']!='all' ){
					$this->db->where("csm_rp_tipe = '".$_GET['tipe']."' " );
				}
			}
	
			if (isset($_GET['kode_bagian']) AND $_GET['kode_bagian'] != '' ) {
				$this->db->where("csm_rp_kode_bagian = '".$_GET['kode_bagian']."' " );
			}
			
		}else{
			$this->db->where(" MONTH(csm_reg_pasien.created_date) > ".$curr_month." " );
			$this->db->where(" YEAR(csm_reg_pasien.created_date) = ".date('Y')." " );
		}

		

		$this->db->where('csm_reg_pasien.is_submitted', 'Y');
			
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
		// print_r($this->db->last_query());die;
		return $query->result();
	}

	function get_data()
	{
		$this->_main_query();
		$this->db->group_by('no_sep');
		$this->db->order_by('csm_dokumen_klaim.tgl_transaksi_kasir', 'ASC');
		$this->db->order_by('csm_dokumen_klaim.no_sep', 'ASC');
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
			$this->db->where_in(''.$this->table.'.no_registrasi',$id);
			$query = $this->db->get();
			return $query->result();
		}else{
			$this->db->where(''.$this->table.'.no_registrasi',$id);
			$query = $this->db->get();
			//echo '<pre>';print_r($this->db->last_query());die;
			return $query->row();
		}
		
	}

	
}
