<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reschedule_praktek_model extends CI_Model {

	var $table = 'mt_master_pasien';
	var $column = array('mt_master_pasien.no_mr');
	var $select = 'mt_master_pasien.*,villages_new.name as kelurahan,districts.name as kecamatan,regencies.name as kota, provinces.name as provinsi, mt_perusahaan.nama_perusahaan';

	var $order = array('mt_master_pasien.no_mr' => 'DESC', 'mt_master_pasien.created_date' => 'DESC');

	public function __construct()
	{
		parent::__construct();
	}

	public function save($table, $data)
	{
		/*insert mt_master_pasien*/
		$this->db->insert($table, $data);
		
		return $this->db->insert_id();
	}

	private function _main_query(){
		$this->db->select($this->select);
		$this->db->from($this->table);
		$this->db->join('villages_new',''.$this->table.'.id_dc_kelurahan=villages_new.id','left');
		$this->db->join('districts','villages_new.district_id=districts.id','left');
		$this->db->join('regencies','districts.regency_id=regencies.id','left');
		$this->db->join('provinces','regencies.province_id=provinces.id','left');
		$this->db->join('mt_perusahaan','mt_perusahaan.kode_perusahaan=mt_master_pasien.kode_perusahaan','left');
		
		/*check level user*/
		$this->authuser->filtering_data_by_level_user($this->table, $this->session->userdata('user')->user_id);

		/*default*/
		

		if (isset($_GET['nama_pasien']) AND $_GET['nama_pasien'] != '') {
			$this->db->where('nama_pasien LIKE %'.$_GET['nama_pasien'].'%');	
		}

		if (isset($_GET['dob']) AND $_GET['dob'] != 0) {
			$this->db->where('convert(varchar,tgl_lhr,23)='.$_GET['dob'].'');	
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

	public function get_by_id($id)
	{
		$this->_main_query();
		if(is_array($id)){
			$this->db->where_in(''.$this->table.'.no_mr',$id);
			$query = $this->db->get();
			return $query->result();
		}else{
			$this->db->where(''.$this->table.'.no_mr',$id);
			$query = $this->db->get();
			return $query->row();
		}
		
	}


	public function update($table, $data, $where)
	{
		$this->db->update($table, $data, $where);
		return $this->db->affected_rows();
	}

	public function delete_by_id($id)
	{
		$get_data = $this->get_by_id($id);
		$this->db->where_in(''.$this->table.'.no_registrasi', $id);
		return $this->db->update($this->table, array('is_deleted' => 'Y', 'is_active' => 'N'));
	}

	public function save_pm($table, $data)
	{
		/*insert mt_master_pasien*/
		$this->db->insert($table, $data);
		
		return $this->db->insert_id();;
	}

	public function get_by_mr($mr)
	
	{
		
		$this->_main_query();
		
		$this->db->where(''.$this->table.'.no_mr', $mr);
			
		$query = $this->db->get();
		
		return $query->row();
	
	}

	function get_datatables_by_limit($params='',$offset='')
	
	{
		
		$this->db->select('no_mr,no_ktp,nama_pasien,tgl_lhr,tempat_lahir,almt_ttp_pasien,jen_kelamin,no_hp,title, id_dc_propinsi, id_dc_kota, id_dc_kecamatan, id_dc_kelurahan');
		
		$this->db->from($this->table);

		$this->db->limit($params, $offset);

		$this->db->order_by('no_mr','DESC');

		$query = $this->db->get();

		//print_r($query->result());die;
		
		return $query->result();
	
	}

	public function count_all_by_limit($params='',$offset='')
	
	{
		
		$this->db->select('no_mr,no_ktp,nama_pasien,tgl_lhr,tempat_lahir,almt_ttp_pasien,jen_kelamin,no_hp,title, id_dc_propinsi, id_dc_kota, id_dc_kecamatan, id_dc_kelurahan');
		
		$this->db->from($this->table);

		$this->db->limit($params, $offset);
		
		$query = $this->db->get();
		
		return $query->num_rows();
	
	}

	function count_filtered_by_limit($params='',$offset='')
	
	{
		
		$this->db->select('no_mr,no_ktp,nama_pasien,tgl_lhr,tempat_lahir,almt_ttp_pasien,jen_kelamin,no_hp,title, id_dc_propinsi, id_dc_kota, id_dc_kecamatan, id_dc_kelurahan');
		
		$this->db->from($this->table);

		$this->db->limit($params, $offset);
		
		$query = $this->db->get();
		
		return $query->num_rows();
	
	}

	public function search_pasien_by_keyword($dob, $name)
	
	{
		
		$this->db->from($this->table);

		if (isset($dob) AND $dob != '') {			
			$this->db->where('convert(varchar,tgl_lhr,23)', $dob);	
		}

		if (isset($name) AND $name != '') {
			$this->db->like('nama_pasien',$name);		
		}

		//$this->db->query("select no_mr,no_ktp,nama_pasien,tgl_lhr,tempat_lahir,almt_ttp_pasien,jen_kelamin,no_hp,title, id_dc_propinsi, id_dc_kota, id_dc_kecamatan, id_dc_kelurahan from mt_master_pasien where convert(varchar,tgl_lhr,23)='".$dob."' and nama_pasien like '%'.$name.'%'");	

		return $this->db->get()->result();

		
	
	}


}
