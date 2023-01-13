<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Csm_resume_billing_model extends CI_Model {


	var $table = 'csm_resume_billing_pasien';
	var $column = array('csm_resume_billing_pasien.no_registrasi','csm_reg_pasien.csm_rp_no_sep','csm_reg_pasien.csm_rp_no_mr','csm_reg_pasien.csm_rp_nama_pasien');
	var $select = 'csm_resume_billing_pasien.no_registrasi, csm_reg_pasien.csm_rp_no_sep, csm_reg_pasien.csm_rp_no_mr, csm_reg_pasien.csm_rp_tgl_keluar, csm_reg_pasien.csm_rp_nama_pasien, csm_rp_nama_dokter, csm_rp_tgl_masuk';
	var $order = array('csm_reg_pasien.csm_rp_no_sep' => 'ASC', 'csm_reg_pasien.csm_rp_tgl_keluar' => 'ASC');
	

	public function __construct()
	{
		parent::__construct();
		$this->load->database('default', TRUE);
	}

	private function _main_query(){
		
		$month = date('m') - 3;

		$this->db->select($this->select);
		$this->db->select('CAST(csm_brp_bill_dr AS INT) as csm_brp_bill_dr, CAST(csm_brp_bill_adm AS INT) as csm_brp_bill_adm, CAST(csm_brp_bill_pm AS INT) as csm_brp_bill_pm, CAST(csm_brp_bill_tindakan AS INT) as csm_brp_bill_tindakan, CAST(csm_brp_bill_far AS INT) as csm_brp_bill_far');
		$this->db->select('CAST(diagnosa_akhir as NVARCHAR(255)) as diagnosa_akhir');
		$this->db->from($this->table);
		$this->db->join('csm_reg_pasien', 'csm_reg_pasien.no_registrasi='.$this->table.'.no_registrasi', 'left');
		$this->db->join('th_riwayat_pasien', 'th_riwayat_pasien.no_registrasi=csm_reg_pasien.no_registrasi', 'left');
		$this->db->where("csm_reg_pasien.csm_rp_no_sep != '' ");
		if (isset($_GET['frmdt']) AND $_GET['frmdt'] != '' || isset($_GET['todt']) AND $_GET['todt'] != '') {
			$this->db->where("CAST(csm_reg_pasien.".$_GET['field']." as DATE) BETWEEN '".$_GET['frmdt']."' AND '".$_GET['todt']."' " );
		}else{
			$this->db->where("MONTH(csm_reg_pasien.csm_rp_tgl_keluar) > ".$month."");
			$this->db->where("YEAR(csm_reg_pasien.csm_rp_tgl_keluar) = ".date('Y')."");
		}

		$this->db->where('csm_reg_pasien.csm_rp_tipe', 'RJ');
		$this->db->where('csm_reg_pasien.is_submitted', 'Y');
		$this->db->group_by($this->select);
		$this->db->group_by('csm_brp_bill_dr,csm_brp_bill_adm, csm_brp_bill_pm, csm_brp_bill_tindakan, csm_brp_bill_far');
		$this->db->group_by('CAST(diagnosa_akhir as NVARCHAR(255))');

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
