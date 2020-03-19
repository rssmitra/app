<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Permintaan_unit_model extends CI_Model {

	var $table = 'tc_permintaan_inst_nm';
	var $column = array('tc_permintaan_inst_nm.nomor_permintaan','tc_permintaan_inst_nm.tgl_permintaan','mt_bagian.nama_bagian','tc_permintaan_inst_nm.status_selesai','dd_user.username');
	var $select = 'tc_permintaan_inst_nm.id_tc_permintaan_inst,tc_permintaan_inst_nm.nomor_permintaan,tc_permintaan_inst_nm.tgl_permintaan,tc_permintaan_inst_nm.status_selesai,mt_bagian.nama_bagian,dd_user.username';

	var $order = array('tc_permintaan_inst_nm.id_tc_permintaan_inst' => 'DESC');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _main_query(){
		$this->db->select($this->select);
		$this->db->from($this->table);
		$this->db->join('mt_bagian',''.$this->table.'.kode_bagian_minta=mt_bagian.kode_bagian','left');
		$this->db->join('dd_user',''.$this->table.'.id_dd_user=dd_user.id_dd_user','left');
		$this->db->where("status_selesai < 2 and id_tc_permintaan_inst in (select id_tc_permintaan_inst from tc_permintaan_inst_nm_det group by id_tc_permintaan_inst)");
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
			$this->db->where_in(''.$this->table.'.id_tc_permintaan_inst',$id);
			$query = $this->db->get();
			return $query->result();
		}else{
			$this->db->where(''.$this->table.'.id_tc_permintaan_inst',$id);
			$query = $this->db->get();
			return $query->row();
		}
		
	}

	public function save($table, $data)
	{
		/*insert tc_registrasi*/
		$this->db->insert($table, $data);
		
		return $this->db->insert_id();;
	}

	public function update($where, $data)
	{
		$this->db->update($this->table, $data, $where);
		return $this->db->affected_rows();
	}

	public function delete_by_id($id)
	{
		$this->db->where_in(''.$this->table.'.education_id', $id);
		return $this->db->delete($this->table);
	}

	public function get_detail_by_id($id)
	{
		# code...
		$query = "select tc_permintaan_inst_nm_det.*,mt_depo_stok_nm_v.nama_brg from tc_permintaan_inst_nm_det 
				join mt_depo_stok_nm_v on mt_depo_stok_nm_v.kode_brg=tc_permintaan_inst_nm_det.kode_brg
				where tc_permintaan_inst_nm_det.id_tc_permintaan_inst = '".$id."'";
		$exc = $this->db->query($query);
		return $exc->result();
	}

	public function get_numrow_detail_by_id($id)
	{
		# code...
		$query = "select tc_permintaan_inst_nm_det.*,mt_depo_stok_nm_v.nama_brg from tc_permintaan_inst_nm_det 
					join mt_depo_stok_nm_v on mt_depo_stok_nm_v.kode_brg=tc_permintaan_inst_nm_det.kode_brg
					where tc_permintaan_inst_nm_det.id_tc_permintaan_inst = '".$id."'";
		$exc = $this->db->query($query);
		return $exc->num_rows();
	}

}
