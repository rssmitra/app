<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adm_lhk_model extends CI_Model {

	var $table = 'tc_trans_kasir';
	var $column = array('a.no_registrasi', 'b.no_sep');
	var $select = 'no_kuitansi, seri_kuitansi, a.no_registrasi, a.kode_tc_trans_kasir, CAST(tgl_jam as DATE)as tgl_transaksi, nama_pasien, pembayar, CAST(tunai as FLOAT) as tunai, CAST(debet as FLOAT) as debet, CAST(kredit as FLOAT) as kredit, CAST(nk_perusahaan as FLOAT) as piutang, CAST(bill as FLOAT)as billing, CAST(nk_karyawan as FLOAT)as nk_karyawan,CAST(potongan as FLOAT)as potongan, nama_pegawai';
	var $order = array('a.no_registrasi' => 'DESC');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _main_query(){

		$this->db->select($this->select);
		$this->db->from($this->table.' a');
		$this->db->join('mt_karyawan b','b.no_induk=a.no_induk','left');
		$this->db->join('tc_registrasi c','c.no_registrasi=a.no_registrasi','left');

		if ( isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' ) {
			$this->db->where("CAST(a.tgl_jam as DATE) = '".$_GET['from_tgl']."'");			
		}else{
			$this->db->where("CAST(a.tgl_jam as DATE) = '".date('Y-m-d')."'");
		}

		$this->db->where('a.seri_kuitansi', $_GET['flag']);


		if ( isset($_GET['penjamin']) AND $_GET['penjamin'] == 120 ) {
			$this->db->where('a.kode_perusahaan', $_GET['penjamin']);
		}

		if ( isset($_GET['penjamin']) AND $_GET['penjamin'] == 'um' ) {
			$this->db->where('a.kode_perusahaan', 0);
		}

		if ( isset($_GET['penjamin']) AND $_GET['penjamin'] == 'asuransi' ) {
			$this->db->where('a.kode_perusahaan not in(0, 120)');
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

	public function get_resume_kasir(){
		$this->db->select('CAST(SUM(tunai) as INT) as tunai, CAST(SUM(debet) as INT) as debet, CAST(SUM(kredit) as INT) as kredit, CAST(SUM(nk_perusahaan) as INT) as nk_perusahaan, CAST(SUM(nk_karyawan) as INT) as nk_karyawan, CAST(SUM(bill) as INT) as bill');
		$this->db->from($this->table);
		if ( isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' ) {
			$this->db->where("CAST(tgl_jam as DATE) = '".$_GET['from_tgl']."'");			
		}else{
			$this->db->where("CAST(tgl_jam as DATE) = '".date('Y-m-d')."'");
		}

		$this->db->where('seri_kuitansi', $_GET['flag']);

		if ( isset($_GET['penjamin']) AND $_GET['penjamin'] == 120 ) {
			$this->db->where('kode_perusahaan', $_GET['penjamin']);
		}

		if ( isset($_GET['penjamin']) AND $_GET['penjamin'] == 'um' ) {
			$this->db->where('kode_perusahaan', 0);
		}

		if ( isset($_GET['penjamin']) AND $_GET['penjamin'] == 'asuransi' ) {
			$this->db->where('kode_perusahaan not in(0, 120)');
		}
		
		$query = $this->db->get()->row();
        return $query;
	}
	
	public function get_jurnal_akunting($kode_tc_trans_kasir){
    	$query = "select a.*, b.acc_nama, c.acc_nama as acc_ref, c.acc_no as acc_no_ref, d.tgl_transaksi from ak_tc_transaksi_det a
    				inner join ak_tc_transaksi d on d.id_ak_tc_transaksi=a.id_ak_tc_transaksi
					inner join mt_account b on b.acc_no=a.acc_no
					inner join mt_account c on c.acc_no=b.acc_ref
					where a.id_ak_tc_transaksi 
					in( select id_ak_tc_transaksi from ak_tc_transaksi where kode_tc_trans_kasir = ".$kode_tc_trans_kasir.") order by a.acc_no ASC";
		$exc = $this->db->query($query)->result();
		$getData = array();
		foreach( $exc as $row_exc ){
			$getData[$row_exc->acc_ref][] = $row_exc;
		}

		return array('result' => $exc, 'data' => $getData);
    }


}
