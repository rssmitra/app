<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reg_klinik_model extends CI_Model {

	var $table = 'tc_registrasi';
	var $column = array('tc_registrasi.no_registrasi');
	var $select = 'tc_registrasi.*, mt_dokter_v.nama_pegawai, mt_bagian.nama_bagian, mt_perusahaan.nama_perusahaan, mt_master_pasien.nama_pasien';

	var $order = array('tc_registrasi.no_registrasi' => 'DESC', 'tc_registrasi.updated_date' => 'DESC');

	public function __construct()
	{
		parent::__construct();
	}

	public function save($table, $data)
	{
		/*insert tc_registrasi*/
		$this->db->insert($table, $data);
		
		return $this->db->insert_id();
	}

	private function _main_query(){
		$this->db->select($this->select);
		$this->db->from($this->table);
		$this->db->join('mt_dokter_v','mt_dokter_v.kode_dokter=tc_registrasi.kode_dokter','left');
		$this->db->join('mt_bagian','mt_bagian.kode_bagian=tc_registrasi.kode_bagian_masuk','left');
		$this->db->join('mt_master_pasien','mt_master_pasien.no_mr=tc_registrasi.no_mr','left');
		$this->db->join('mt_perusahaan','mt_perusahaan.kode_perusahaan=tc_registrasi.kode_perusahaan','left');
		// $this->db->where($this->table.".is_deleted != 'Y'");
		/*check level user*/
		// $this->authuser->filtering_data_by_level_user($this->table, $this->session->userdata('user')->user_id);

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
		$this->db->select('jd_jam_mulai, jd_jam_selesai');
		$this->db->join('tr_jadwal_dokter','tr_jadwal_dokter.jd_id=tc_registrasi.jd_id','left');
		if(is_array($id)){
			$this->db->where_in(''.$this->table.'.no_registrasi',$id);
			$query = $this->db->get();
			return $query->result();
		}else{
			$this->db->where(''.$this->table.'.no_registrasi',$id);
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
		/*insert tc_registrasi*/
		$this->db->insert($table, $data);
		
		return $this->db->insert_id();;
	}

}
