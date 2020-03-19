<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Csm_klaim_model extends CI_Model {

	var $table = 'csm_klaim';
	var $column = array('csm_klaim.csm_klaim_bulan','csm_klaim.csm_klaim_tahun','csm_klaim.csm_klaim_total_ri','csm_klaim.csm_klaim_total_rj','csm_klaim.csm_klaim_total_rp','csm_klaim.csm_klaim_total_dokumen','csm_klaim.created_date', 'csm_klaim.created_by');
	var $select = 'csm_klaim_id,csm_klaim.csm_klaim_bulan,csm_klaim.csm_klaim_tahun,csm_klaim.csm_klaim_total_ri,csm_klaim.csm_klaim_total_rj,csm_klaim.csm_klaim_total_rp,csm_klaim.csm_klaim_total_dokumen,csm_klaim.created_date,csm_klaim.created_by,csm_klaim.csm_klaim_dari_tgl, csm_klaim.csm_klaim_sampai_tgl,csm_klaim_kode';

	var $order = array('csm_klaim.csm_klaim_id' => 'DESC');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _main_query(){
		$this->db->select($this->select);
		$this->db->from($this->table);
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
			$this->db->where_in(''.$this->table.'.csm_klaim_id',$id);
			$query = $this->db->get();
			return $query->result();
		}else{
			$this->db->where(''.$this->table.'.csm_klaim_id',$id);
			$query = $this->db->get();
			return $query->row();
		}
		
	}

	public function save($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function update($where, $data)
	{
		$this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}

	public function delete_by_id($id)
	{
		$this->db->where_in(''.$this->table.'.csm_klaim_id', $id);
		return $this->db->delete($this->table);
	}

	public function find_klaim_by_waktu_input($data)
	{
		$this->db->from('csm_dokumen_klaim');
		$this->db->where("created_date BETWEEN '".$this->tanggal->selisih($data['csm_klaim_dari_tgl'],'-1')."' AND '".$this->tanggal->selisih($data['csm_klaim_sampai_tgl'],'+1')."' ");
		$this->db->order_by("created_date ASC");
		$result = $this->db->get()->result();
		$total = array();
		$total['RJ'] = array();
		$total['RI'] = array();
		$total['RP'] = array();
		foreach ($result as $key => $value) {
			# code...
			$total['RJ'][] = ($value->csm_dk_tipe=='RJ')?1:0;
			$total['RI'][] = ($value->csm_dk_tipe=='RI')?1:0;
			$total['RP'][] = $value->csm_dk_total_klaim;
		}
		$ress = array(
				'total_rj' => array_sum($total['RJ']),
				'total_ri' => array_sum($total['RI']),
				'total_rp' => array_sum($total['RP']),
				'total_dok' => count($result),
			);

		return $ress;
	}

	public function update_dokumen_klaim($data, $klaim_id)
	{
		$this->db->where("created_date BETWEEN '".$this->tanggal->selisih($data['csm_klaim_dari_tgl'],'-1')."' AND '".$this->tanggal->selisih($data['csm_klaim_sampai_tgl'],'+1')."' ");
		return $this->db->update('csm_dokumen_klaim', array('csm_klaim_id'=>$klaim_id));;
	}


}
