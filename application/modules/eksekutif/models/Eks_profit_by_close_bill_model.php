<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Eks_profit_by_close_bill_model extends CI_Model {

	var $table = 'tc_trans_kasir';
	var $column = array('a.no_registrasi', 'b.no_sep');
	var $select = 'no_kuitansi,seri_kuitansi,a.no_registrasi,a.kode_tc_trans_kasir, nama_pasien,e.nama_perusahaan,f.nama_bagian,a.tgl_jam, c.no_sep, c.no_mr, h.kode_jenis_tindakan, h.jenis_tindakan, c.kode_bagian_masuk, g.kode_bagian, c.kode_perusahaan';
	var $order = array('a.no_kuitansi' => 'ASC');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _filter(){

		if ( isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' AND isset($_GET['to_tgl']) AND $_GET['to_tgl'] != '' ) {
			$this->db->where('CAST(a.tgl_jam as DATE) BETWEEN '."'".$_GET['from_tgl']."'".' AND '."'".$_GET['to_tgl']."'".' ');
		}else{
			$this->db->where("CAST(a.tgl_jam as DATE) = '".date('Y-m-d')."'");
		}
		if($_GET['flag'] != 'all'){
			$this->db->where('a.seri_kuitansi', $_GET['flag']);
		}

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

	private function _main_query(){

		$this->db->select('CAST ( tunai AS FLOAT ) AS tunai,
							CAST ( debet AS FLOAT ) AS debet,
							CAST ( kredit AS FLOAT ) AS kredit,
							CAST ( nk_perusahaan AS FLOAT ) AS piutang,
							CAST ( bill AS FLOAT ) AS billing,
							CAST ( nk_karyawan AS FLOAT ) AS nk_karyawan,
							CAST ( potongan AS FLOAT ) AS potongan');

		$this->db->select('CAST(tgl_jam AS DATE ) AS tgl_transaksi,
							SUM(CAST(bill_rs as INT)) as bill_rs, 
							SUM(CAST(bill_dr1 as INT)) as bill_dr1, 
							SUM(CAST(bill_dr2 as INT)) as bill_dr2, 
							SUM(CAST(bhp as INT)) as bhp, 
							SUM(CAST(obat as INT)) as obat, 
							SUM(CAST(alat_rs as INT)) as alat_rs, 
							SUM(CAST(alkes as INT)) as alkes, 
							SUM(CAST(adm as INT)) as adm, 
							SUM(CAST(kamar_tindakan as INT)) as kamar_tindakan, 
							SUM(CAST(pendapatan_rs as INT)) as pendapatan_rs, 
							( SUM(CAST(bill_rs as INT)) + SUM(CAST(bill_dr1 as INT)) + SUM(CAST(bill_dr2 as INT)) ) as total_billing');
		$this->db->select('CAST(c.tgl_jam_masuk AS DATE ) AS tgl_masuk');
		$this->db->select('CAST(c.tgl_jam_keluar AS DATE ) AS tgl_keluar');
		$this->db->select($this->select);
		$this->db->from($this->table.' a');
		$this->db->join('mt_karyawan b','b.no_induk=a.no_induk','left');
		$this->db->join('tmp_user d','d.user_id=a.no_induk','left');
		$this->db->join('tc_registrasi c','c.no_registrasi=a.no_registrasi','left');
		$this->db->join('mt_bagian f','f.kode_bagian=c.kode_bagian_masuk','left');
		$this->db->join('mt_perusahaan e','e.kode_perusahaan=a.kode_perusahaan','left');
		$this->db->join('tc_trans_pelayanan g','g.kode_tc_trans_kasir=a.kode_tc_trans_kasir','left');
		$this->db->join('mt_jenis_tindakan h','h.kode_jenis_tindakan=g.jenis_tindakan','left');
		$this->db->group_by('CAST ( tunai AS FLOAT ),
							CAST ( debet AS FLOAT ),
							CAST ( kredit AS FLOAT ),
							CAST ( nk_perusahaan AS FLOAT ),
							CAST ( bill AS FLOAT ),
							CAST ( nk_karyawan AS FLOAT ),
							CAST ( potongan AS FLOAT )');
		$this->db->group_by('CAST(tgl_jam AS DATE )');
		$this->db->group_by($this->select);
		$this->db->group_by('CAST(c.tgl_jam_masuk AS DATE )');
		$this->db->group_by('CAST(c.tgl_jam_keluar AS DATE )');
		// $this->db->where('c.no_mr', '00305569');

	}

	private function _get_datatables_query()
	{
		
		$this->_main_query();
		$this->_filter();

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
		$this->_filter();
		$this->db->select($this->select);
		$query = $this->db->get(); 
		return $query;
	}

	function get_data2()
	{
		// query 2
		$this->db->select('a.kode_tc_trans_kasir, h.jenis_tindakan, g.jenis_tindakan as kode_jenis_tindakan, SUM(CAST(bill_rs as INT)) as bill_rs, SUM(CAST(bill_dr1 as INT)) as bill_dr1, SUM(CAST(bill_dr2 as INT)) as bill_dr2, ( SUM(CAST(bill_rs as INT)) + SUM(CAST(bill_dr1 as INT)) + SUM(CAST(bill_dr2 as INT)) ) as total_billing, nama_pasien, e.nama_perusahaan, f.nama_bagian, a.tgl_jam, a.seri_kuitansi, g.no_mr, a.no_kuitansi, c.no_sep, c.kode_perusahaan, c.no_registrasi');
		$this->db->from('tc_trans_pelayanan g');
		$this->db->join('tc_trans_kasir a', 'g.kode_tc_trans_kasir = a.kode_tc_trans_kasir', 'left');
		$this->db->join('mt_karyawan b','b.no_induk=a.no_induk','left');
		$this->db->join('tmp_user d','d.user_id=a.no_induk','left');
		$this->db->join('tc_registrasi c','c.no_registrasi=a.no_registrasi','left');
		$this->db->join('mt_bagian f','f.kode_bagian=c.kode_bagian_masuk','left');
		$this->db->join('mt_perusahaan e','e.kode_perusahaan=a.kode_perusahaan','left');
		$this->db->join('mt_jenis_tindakan h', 'h.kode_jenis_tindakan = g.jenis_tindakan', 'left');
		$this->_filter();
		$this->db->where('g.kode_tc_trans_kasir is not null AND g.jenis_tindakan is not null');
		$this->db->group_by('a.kode_tc_trans_kasir, h.jenis_tindakan, g.jenis_tindakan, nama_pasien, e.nama_perusahaan, f.nama_bagian, a.tgl_jam, a.seri_kuitansi, g.no_mr, a.no_kuitansi, c.no_sep, c.kode_perusahaan, c.no_registrasi');
		$this->db->order_by('a.no_kuitansi');
		$query2 = $this->db->get()->result();
		// print_r($this->db->last_query());die;
		return $query2;
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
