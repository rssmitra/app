<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ao_pickup_obat_model extends CI_Model {

	var $table = 'fr_pickup_obat';
	var $column = array('fr_pickup_obat.kode_trans_far', 'fr_pickup_obat.no_mr', 'fr_pickup_obat.nama_pasien');
	var $select = 'pickup_id, fr_pickup_obat.kode_trans_far,fr_pickup_obat.no_mr,fr_pickup_obat.nama_pasien,fr_pickup_obat.pickup_time,fr_pickup_obat.pickup_by,fr_pickup_obat.received_time,fr_pickup_obat.received_by,fr_pickup_obat.distance, fr_pickup_obat.cost, fr_pickup_obat.note, fr_pickup_obat.jenis_resep, almt_ttp_pasien  as alamat, tlp_almt_ttp as no_telp, no_hp';

	var $order = array('pickup_id' => 'DESC');

	public function __construct()
	{
		parent::__construct();
	}



	private function _main_query(){

		$this->db->select($this->select);
		$this->db->from($this->table);
		$this->db->join('fr_tc_far','fr_tc_far.kode_trans_far=fr_pickup_obat.kode_trans_far','left');	
		$this->db->join('mt_master_pasien', 'mt_master_pasien.no_mr=fr_tc_far.no_mr','left');	
	}

	private function _get_datatables_query()
	{
		
		$this->_main_query();
		$date = isset($_GET['tgl']) ? $_GET['tgl'] : date('Y-m-d');
        $this->db->where("CAST(fr_pickup_obat.pickup_time as DATE) <= '".$date."'" );
		
		$i = 0;
		$col_str = '';
		foreach ($this->column as $item) 
		{
			if($_POST['search']['value'])
				$col_str .= ($i===0) ? $item." LIKE '%".$_POST['search']['value']."%' " : "OR ".$item." LIKE '%".$_POST['search']['value']."%'";
			$column[$i] = $item;
			$i++;
		}

		if( $col_str != ''){
			$this->db->where('('.$col_str.')');
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

	function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all()
	{
		$this->_get_datatables_query();
		return $this->db->count_all_results();
	}

	public function get_by_id($id)
	{
		$this->_main_query();
		if(is_array($id)){
			$this->db->where_in(''.$this->table.'.kode_trans_far',$id);
			$query = $this->db->get();
			return $query->result();
		}else{
			$this->db->where(''.$this->table.'.kode_trans_far',$id);
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

	public function update($table, $data, $where)
	{
		$this->db->update($table, $data, $where);
		return $this->db->affected_rows();
	}

	public function delete_by_id($id)
	{
		$this->db->where_in(''.$this->table.'.pickup_id', $id);
		return $this->db->delete($this->table);
	}

	public function find_pasien($kode_trans_far)
	{
		$this->db->from('fr_tc_far');
		$this->db->select('almt_ttp_pasien  as alamat, tlp_almt_ttp as no_telp, no_hp');
		$this->db->join('mt_master_pasien', 'mt_master_pasien.no_mr=fr_tc_far.no_mr','left');
		$this->db->where('fr_tc_far.kode_trans_far', $kode_trans_far);
		return $this->db->get()->row();


	}

}
