<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adm_tagihan_perusahaan_model extends CI_Model {

	var $table = 'tc_trans_kasir';
	var $column = array('b.nama_perusahaan');
	var $select = 'b.nama_perusahaan, b.kode_perusahaan, b.disc';
	var $order = array('b.nama_perusahaan' => 'DESC');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _main_query(){

		$this->db->select($this->select);
		$this->db->select('SUM(nk_perusahaan) AS jml_tghn, SUM(tunai) AS jml_tunai,SUM(debet) AS jml_debet, SUM(kredit) AS jml_kredit,SUM(bill) AS jml_bill');
		$this->db->from($this->table.' a');
		$this->db->join('mt_perusahaan b','b.kode_perusahaan=a.kode_perusahaan','left');
		$this->db->where('a.seri_kuitansi', 'RJ');
		$this->db->where('a.nk_perusahaan > 0 AND a.kd_inv_persh_tx IS NULL');
		$this->db->group_by($this->select);

		

		if(isset($_GET['keyword']) AND $_GET['keyword'] != ''){
			$this->db->where('c.'.$_GET['search_by'], $_GET['keyword']);		
		}
		
		if (isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' || isset($_GET['to_tgl']) AND $_GET['to_tgl'] != '') {
			$this->db->where("convert(varchar,tgl_jam.tgl_masuk,23) between '".$_GET['from_tgl']."' and '".$_GET['to_tgl']."'");					
		}else{
			$this->db->where("YEAR(tgl_jam)", date('Y'));
			$this->db->where("MONTH(tgl_jam)", date('m'));
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
		
		return $query;
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

}
