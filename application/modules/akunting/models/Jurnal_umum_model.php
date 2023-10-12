<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jurnal_umum_model extends CI_Model {

	var $table = 'ak_tc_transaksi';
	var $column = array('a.no_mr', 'a.nama_pasien', 'a.no_bukti');
	var $select = 'a.id_ak_tc_transaksi, a.no_bukti, a.tgl_transaksi, a.uraian_transaksi, a.tgl_ver, a.user_ver, a.total_nominal, a.no_mr, a.nama_pasien, a.status_ver';
	var $order = array('a.no_bukti' => 'DESC');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _main_query(){

		$this->db->select($this->select);
		$this->db->select('CAST(a.total_nominal as INT) as total');
		$this->db->from($this->table.' a');

		if ( isset($_GET['search_by']) ) {
			if(isset($_GET['keyword']) AND $_GET['keyword'] != ''){
				if($_GET['search_by'] == 'c.nama_pasien'){
					$this->db->like($_GET['search_by'], $_GET['keyword']);		
				}else{
					$this->db->where($_GET['search_by'], $_GET['keyword']);		
				}
			}		

			if( isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' AND isset($_GET['to_tgl']) AND $_GET['to_tgl'] != ''){
				$this->db->where("CAST(a.tgl_transaksi as DATE) between '".$_GET['from_tgl']."' and '".$_GET['to_tgl']."'");
			}
			
		}else{
			$this->db->where("CAST(a.tgl_transaksi as DATE) = ", date('Y-m-d'));
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
		// echo '<pre>';print_r($this->db->last_query());die;
		
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

	public function get_data_by_id($id_ak_tc_transaksi)
	{
		$this->_main_query();
		$this->db->where('a.id_ak_tc_transaksi', $id_ak_tc_transaksi);
		$query = $this->db->get()->row();
		return $query;
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

	
    public function getDetailData($id_ak_tc_transaksi){
		$this->db->select('a.acc_no, a.tipe_tx, b.acc_nama, SUM(nominal) as nominal');
		$this->db->from('ak_tc_transaksi_det a');
		$this->db->join('ak_tc_transaksi d', 'd.id_ak_tc_transaksi=a.id_ak_tc_transaksi', 'inner');
		$this->db->join('mt_account b', 'b.acc_no=a.acc_no', 'inner');
		$this->db->where('a.id_ak_tc_transaksi', $id_ak_tc_transaksi);
		$this->db->order_by('a.acc_no', 'ASC');

		$this->db->group_by('a.acc_no, a.tipe_tx, b.acc_nama');
		$exc = $this->db->get()->result();
		return array('data' => $exc);
    }


}
