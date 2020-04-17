<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pembentukan_saldo_dr_model extends CI_Model {

	var $table = 'log_billing_dr';
	var $column = array('a.no_registrasi');
	var $select = 'a.no_registrasi, a.tgl_jam, a.no_mr, a.nama_pasien_layan, b.seri_kuitansi, bill_dr1, bill_dr2';
	var $order = array('a.tgl_jam' => 'ASC');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _main_query(){

		$this->db->select($this->select);
		$this->db->from($this->table.' a');
		$this->db->join('tc_trans_kasir b','a.no_registrasi=b.no_registrasi','inner');

	}

	private function _get_datatables_query()
	{
		
		$this->_main_query();
		$this->db->select('SUM( bill_dr1 + bill_dr2 ) as total_billing');

		if( isset($_GET['kode_dokter']) AND $_GET['kode_dokter'] ){
			$this->db->where("(kode_dokter1=".$_GET['kode_dokter']." OR kode_dokter2=".$_GET['kode_dokter'].")");
		}else{
			$this->db->where("CAST(a.tgl_jam as DATE) = '".date('Y-m-d')."'");
		}

		if ( isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' AND isset($_GET['to_tgl']) AND $_GET['to_tgl'] != '' ) {
			$this->db->where("CAST(a.tgl_jam as DATE) BETWEEN '".$_GET['from_tgl']."' AND '".$_GET['to_tgl']."' ");			
		}

		$this->db->group_by($this->select);

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

	function get_data()
	{
		$this->_main_query();
		$query = $this->db->get(); 
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
		$this->_get_datatables_query();
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

	public function get_total_billing(){
		$this->db->select('CAST(SUM(bill_dr1 + bill_dr2) AS INT) as total_billing');
		$this->db->from($this->table);

		if( isset($_GET['kode_dokter']) AND $_GET['kode_dokter'] ){
			$this->db->where("(kode_dokter1=".$_GET['kode_dokter']." OR kode_dokter2=".$_GET['kode_dokter'].")");
		}else{
			$this->db->where("CAST(tgl_jam as DATE) = '".date('Y-m-d')."'");
		}

		if ( isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' AND isset($_GET['to_tgl']) AND $_GET['to_tgl'] != '' ) {
			$this->db->where("CAST(tgl_jam as DATE) BETWEEN '".$_GET['from_tgl']."' AND '".$_GET['to_tgl']."' ");			
		}

		$query = $this->db->get()->row();
        return $query;
	}

	public function get_total_billing_dr_current_day(){
		$this->db->select('CAST(SUM(bill_dr1 + bill_dr2) AS INT) as total_billing');
		$this->db->from('tc_trans_pelayanan a');
		$this->db->join('tc_registrasi b','b.no_registrasi=a.no_registrasi','inner');

		if( isset($_GET['kode_dokter']) AND $_GET['kode_dokter'] ){
			$this->db->where("(kode_dokter1=".$_GET['kode_dokter']." OR kode_dokter2=".$_GET['kode_dokter'].")");
		}

		$this->db->where("CAST(b.tgl_jam_masuk as DATE) = '".date('Y-m-d')."' ");
		$this->db->where('(b.status_batal is null or status_batal=0)');
		$this->db->where("(a.kode_bagian = '".$_GET['kode_bagian']."' or b.kode_bagian_masuk='".$_GET['kode_bagian']."') ");

		$query = $this->db->get()->row();
        return $query;
	}

	public function get_detail_kunjungan($no_registrasi){
		$this->_main_query();
		$this->db->select('mt_perusahaan.nama_perusahaan, mt_bagian.nama_bagian, a.bill_dr1, a.kode_dokter1, a.bill_dr2, a.kode_dokter2, nama_tindakan, a.status_paid, a.no_kunjungan, dr1.nama_pegawai as dokter1, dr2.nama_pegawai as dokter2');
		$this->db->join('mt_perusahaan', 'mt_perusahaan.kode_perusahaan=a.kode_perusahaan','left');
		$this->db->join('mt_bagian', 'mt_bagian.kode_bagian=a.kode_bagian','left');
		$this->db->join('mt_dokter_v dr1', 'dr1.kode_dokter=a.kode_dokter1','left');
		$this->db->join('mt_dokter_v dr2', 'dr2.kode_dokter=a.kode_dokter2','left');
		$this->db->where('a.no_registrasi', $no_registrasi);
		$this->db->order_by('no_kunjungan', 'ASC');
		$this->db->group_by($this->select);
		$this->db->group_by('mt_perusahaan.nama_perusahaan, mt_bagian.nama_bagian, a.bill_dr1, a.kode_dokter1, a.bill_dr2, a.kode_dokter2, nama_tindakan, a.status_paid, a.no_kunjungan, dr1.nama_pegawai, dr2.nama_pegawai');
		return $this->db->get();
	}

}
