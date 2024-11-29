<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Manifest_model extends CI_Model {

	var $table = 'pl_tc_poli';
	var $column = array('mt_bagian.nama_bagian', 'mt_karyawan.nama_pegawai');
	var $select = 'mt_bagian.nama_bagian, mt_karyawan.nama_pegawai, pl_tc_poli.kode_dokter, pl_tc_poli.kode_bagian';

	var $order = array('mt_karyawan.nama_pegawai' => 'ASC');

	public function __construct()
	{
		parent::__construct();
	}

	public function save($table, $data)
	{
		/*insert tc_registrasi*/
		$this->db->insert($table, $data);
		
		return $this->db->insert_id();;
	}

	private function _main_query(){
		$this->db->select($this->select);
		$this->db->select('CAST(tgl_jam_poli as DATE) as tgl_jam_poli');
		$this->db->select('COUNT(pl_tc_poli.no_kunjungan) as total_pasien');
		$this->db->from($this->table);
		$this->db->join('tc_kunjungan',''.$this->table.'.no_kunjungan=tc_kunjungan.no_kunjungan','left');
		$this->db->join('mt_bagian',''.$this->table.'.kode_bagian=mt_bagian.kode_bagian','left');
		$this->db->join('mt_karyawan',''.$this->table.'.kode_dokter=mt_karyawan.kode_dokter','left');
		
	}

	private function _filterQuery(){
		/*if isset parameter*/
		if( $_GET ) {

			if (isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '') {
				$this->db->where("CAST(pl_tc_poli.tgl_jam_poli as DATE) = ", $_GET['from_tgl']);	
				
			}

	        if (isset($_GET['bagian']) AND $_GET['bagian'] != 0) {
	            $this->db->where('pl_tc_poli.kode_bagian', $_GET['bagian']);	
	        }

	        if (isset($_GET['dokter']) AND $_GET['dokter'] != 0) {
	            $this->db->where('pl_tc_poli.kode_dokter', $_GET['dokter']);	
	        }
		
		}else{
			$this->db->where(array('CAST(pl_tc_poli.tgl_jam_poli as DATE) = ' => date('Y-m-d')));
		} 
	}

	private function _get_datatables_query()
	{
		
		$this->_main_query();
		$this->_filterQuery();
		$this->db->group_by($this->select);
		$this->db->group_by('CAST(tgl_jam_poli as DATE)');
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
		// print_r($this->db->last_query());die;
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
		$this->_filterQuery();
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

	public function get_detail_pasien($params){
		$this->db->select('pl_tc_poli.*, mt_bagian.nama_bagian, mt_karyawan.nama_pegawai');
		$this->db->from('pl_tc_poli');
		$this->db->join('mt_bagian','pl_tc_poli.kode_bagian=mt_bagian.kode_bagian','left');
		$this->db->join('mt_karyawan','pl_tc_poli.kode_dokter=mt_karyawan.kode_dokter','left');
		$this->db->where('pl_tc_poli.kode_dokter', $params['kode_dokter']);
		$this->db->where('pl_tc_poli.kode_bagian', $params['kode_bagian']);
		$this->db->where('CAST(tgl_jam_poli as DATE) = ', $params['tgl_kunjungan']);
		$this->db->order_by('no_antrian', 'ASC');
		$result = $this->db->get()->result();
		return $result;

	}

}
