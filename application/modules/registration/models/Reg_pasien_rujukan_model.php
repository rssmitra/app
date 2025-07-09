<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reg_pasien_rujukan_model extends CI_Model {

	var $table = 'rg_tc_rujukan';
	var $column = array('kode_rujukan','rujukan_dari','no_kunjungan_lama','tc_kunjungan.no_registrasi','rg_tc_rujukan.status','rg_tc_rujukan.tgl_input','nama_rujukan_dari','rg_tc_rujukan.no_mr');
	var $select = '
	kode_rujukan, rujukan_dari, no_kunjungan_lama, tc_kunjungan.no_registrasi, rg_tc_rujukan.status, rg_tc_rujukan.tgl_input, asal.nama_bagian as nama_rujukan_dari, rg_tc_rujukan.no_mr, mt_master_pasien.nama_pasien as nama_pasien,rujukan_tujuan, tujuan.nama_bagian as tujuan_bagian_rujuk, nama_pegawai';

	var $order = array('rg_tc_rujukan.tgl_input' => 'DESC');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _main_query(){

		$date = date('Y-m-d H:i:s', strtotime('-3 days', strtotime(date('Y-m-d H:i:s'))));

		$this->db->distinct();
		$this->db->select($this->select, false);
		$this->db->from($this->table);
		$this->db->join('tc_kunjungan','tc_kunjungan.no_kunjungan=rg_tc_rujukan.no_kunjungan_lama','left');
		$this->db->join('mt_master_pasien','mt_master_pasien.no_mr=rg_tc_rujukan.no_mr','left');
		$this->db->join('mt_bagian as asal','asal.kode_bagian=rg_tc_rujukan.rujukan_dari','left');
		$this->db->join('mt_bagian as tujuan','tujuan.kode_bagian=rg_tc_rujukan.rujukan_tujuan','left');
		$this->db->join('mt_dokter_v','mt_dokter_v.kode_dokter=tc_kunjungan.kode_dokter','left');

		$this->db->where("rg_tc_rujukan.tgl_input > '".$date."' ");
		
		/*default*/
		if( $_GET ) {

			if (isset($_GET['bulan']) AND $_GET['bulan'] != 0) {
				$this->db->where('MONTH(rg_tc_rujukan.tgl_input)='.$_GET['bulan'].'');	
			}

			if (isset($_GET['tahun']) AND $_GET['tahun'] != 0) {
				$this->db->where('YEAR(rg_tc_rujukan.tgl_input)='.$_GET['tahun'].'');	
			}

			if (isset($_GET['bagian_asal']) AND $_GET['bagian_asal'] != '') {
				$this->db->where('kode_bagian_asal', $_GET['bagian_asal']);	
			}

			if (isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' || isset($_GET['to_tgl']) AND $_GET['to_tgl'] != '') {
				$this->db->where("convert(varchar,rg_tc_rujukan.tgl_input,23) between '".$_GET['from_tgl']."' and '".$_GET['to_tgl']."'");
			}
		}

		/*end parameter*/
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
		//print_r($this->db->last_query());die;
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
		$this->db->where('kode_rujukan', $id);
		$query = $this->db->get();
		return $query->row();
	}

	public function update($where, $data)
	
	{
		$this->db->update($this->table, $data, $where);
		
		return $this->db->affected_rows();	
	}


}
