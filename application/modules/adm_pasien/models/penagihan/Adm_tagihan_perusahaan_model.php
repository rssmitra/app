<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adm_tagihan_perusahaan_model extends CI_Model {

	var $table = 'tc_trans_kasir';
	var $column = array('b.nama_perusahaan');
	var $select = 'b.nama_perusahaan, b.kode_perusahaan';
	var $order = array('b.nama_perusahaan' => 'DESC');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _main_query(){

		$this->db->select($this->select);
		$this->db->select('SUM(nk_perusahaan) AS jml_tghn, SUM(tunai) AS jml_tunai,SUM(debet) AS jml_debet, SUM(kredit) AS jml_kredit,SUM(bill) AS jml_bill, CAST(b.disc as INT) as disc');
		$this->db->from($this->table.' a');
		$this->db->join('mt_perusahaan b','b.kode_perusahaan=a.kode_perusahaan','left');
		$this->db->where('(a.nk_perusahaan > 0 AND a.kd_inv_persh_tx IS NULL AND a.kode_perusahaan NOT IN (120, 221, 0, 299))');
		$this->db->group_by('b.disc');
		$this->db->group_by($this->select);

		if(isset($_GET['keyword']) AND $_GET['keyword'] != ''){
			$this->db->like('b.nama_perusahaan', $_GET['keyword']);		
		}

		if(isset($_GET['jenis_pelayanan']) AND $_GET['jenis_pelayanan'] != ''){
			$this->db->where('a.seri_kuitansi', $_GET['jenis_pelayanan']);		
		}
		
		if (isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' || isset($_GET['to_tgl']) AND $_GET['to_tgl'] != '') {
			$this->db->where("convert(varchar,tgl_jam,23) between '".$_GET['from_tgl']."' and '".$_GET['to_tgl']."'");					
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
		// print_r($this->db->last_query());die;
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

	public function save($table, $data)
	{
		$this->db->insert($table, $data);
		return $this->db->insert_id();
	}

	public function update($where, $data)
	{
		$this->db->update($this->table, $data, $where);
		
		return $this->db->affected_rows();
	}

	public function get_by_id($id)
	{
		$this->_main_query();
		if(is_array($id)){
			$this->db->where_in('a.kode_perusahaan',$id);
			$query = $this->db->get();
			return $query->result();
		}else{
			$this->db->where('a.kode_perusahaan',$id);
			$query = $this->db->get();
			return $query->row();
		}
		
	}

	public function get_hist_inv($kode_perusahaan){
		$this->db->from('tc_tagih a');
		$this->db->where('a.id_tertagih', $kode_perusahaan);
		$this->db->where('MONTH(a.tgl_tagih)', date('m'));
		$this->db->order_by('tgl_tagih', 'DESC');

		return $this->db->get()->result();
	}

	public function get_invoice_detail($id_tagih){
		$this->db->select('a.*, b.no_invoice_tagih, c.tgl_jam');
		$this->db->from('tc_tagih_det a');
		$this->db->join('tc_tagih b', 'b.id_tc_tagih=a.id_tc_tagih','left');
		$this->db->join('tc_trans_kasir c', 'c.kode_tc_trans_kasir=a.kode_tc_trans_kasir','left');
		$this->db->where('a.id_tc_tagih', $id_tagih);
		$this->db->order_by('kode_tc_trans_kasir', 'ASC');
		$query = $this->db->get()->result();
		// print_r($this->db->last_query());die;
		return $query;
	}

	public function get_detail_pasien($kode_perusahaan){

		$this->db->select('a.*');
		$this->db->select('c.jen_kelamin');
		$this->db->select('CAST(bill as INT) as bill_int, (CAST(tunai as INT) + CAST(debet as INT) + CAST(kredit as INT)) as beban_pasien, CAST(nk_perusahaan as INT) as nk_perusahaan_int');
		$this->db->from($this->table.' a');
		$this->db->join('mt_perusahaan b','b.kode_perusahaan=a.kode_perusahaan','left');
		$this->db->join('mt_master_pasien c','c.no_mr=a.no_mr','left');
		$this->db->where('(a.nk_perusahaan > 0 AND a.kd_inv_persh_tx IS NULL AND a.kode_perusahaan NOT IN (120, 221, 0, 299))');
		$this->db->where('a.kode_perusahaan', $kode_perusahaan);		
		$this->db->where('a.seri_kuitansi', $_GET['jenis_pelayanan']);		
		$this->db->where("convert(varchar,tgl_jam,23) between '".$_GET['from_tgl']."' and '".$_GET['to_tgl']."'");	
		$this->db->order_by("a.tgl_jam", "ASC");	
		$query = $this->db->get()->result();
		return $query;
	}

}
