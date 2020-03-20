<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Riwayat_kunjungan_poli_model extends CI_Model {

	var $table = 'tc_kunjungan';
	var $column = array('tc_kunjungan.no_kunjungan','tc_kunjungan.no_mr');
	var $select = 'tc_kunjungan.no_kunjungan,tc_kunjungan.no_mr,tc_kunjungan.no_registrasi,mt_karyawan.nama_pegawai as dokter, asal.nama_bagian as asal_bagian, tujuan.nama_bagian as tujuan_bagian, mt_master_pasien.nama_pasien, tc_kunjungan.tgl_masuk, pl_tc_poli.id_pl_tc_poli,pl_tc_poli.no_antrian, pl_tc_poli.status_batal, pl_tc_poli.status_periksa, pl_tc_poli.kode_gcu, tc_kunjungan.tgl_keluar';

	var $order = array('pl_tc_poli.tgl_jam_poli' => 'DESC');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _main_query(){
		$this->db->select($this->select);
		$this->db->from($this->table);
		$this->db->join('mt_master_pasien','mt_master_pasien.no_mr=tc_kunjungan.no_mr','left');
		$this->db->join('mt_karyawan','mt_karyawan.kode_dokter=tc_kunjungan.kode_dokter','left');
		$this->db->join('mt_bagian as asal','asal.kode_bagian=tc_kunjungan.kode_bagian_asal','left');
		$this->db->join('mt_bagian as tujuan','tujuan.kode_bagian=tc_kunjungan.kode_bagian_tujuan','left');
		$this->db->join('pl_tc_poli','pl_tc_poli.no_kunjungan=tc_kunjungan.no_kunjungan','left');
		
		/*default*/
		if( $_GET ) {

			if(isset($_GET['keyword']) AND $_GET['keyword'] != ''){
				$this->db->like(''.$_GET['search_by'].'', $_GET['keyword']);
			}

			if (isset($_GET['bulan']) AND $_GET['bulan'] != 0) {
	            $this->db->where('MONTH(tgl_masuk)='.$_GET['bulan'].'');	
	        }

	        if (isset($_GET['tahun']) AND $_GET['tahun'] != 0) {
	            $this->db->where('YEAR(tgl_masuk)='.$_GET['tahun'].'');	
	        }

	        if (isset($_GET['bagian_asal']) AND $_GET['bagian_asal'] != '') {
	            $this->db->where('kode_bagian_asal', $_GET['bagian_asal']);	
	        }

	        if (isset($_GET['dokter']) AND $_GET['dokter'] != 0) {
	            $this->db->where('tc_kunjungan.kode_dokter', $_GET['dokter']);	
	        }

	        if (isset($_GET['bagian_tujuan']) AND $_GET['bagian_tujuan'] != '') {
				$this->db->where('kode_bagian_tujuan', $_GET['bagian_tujuan']);	
				
				if( (!isset($_GET['bulan'])) OR (!isset($_GET['tahun'])) OR ( (!isset($_GET['from_tgl'])) AND (!isset($_GET['from_tgl'])) )){
					$this->db->where('DAY(tgl_masuk)='.date('d').'');	
					$this->db->where('MONTH(tgl_masuk)='.date('m').'');	
					$this->db->where('YEAR(tgl_masuk)='.date('Y').'');
				}
	        }

			if (isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' || isset($_GET['to_tgl']) AND $_GET['to_tgl'] != '') {
				$this->db->where("convert(varchar,tc_kunjungan.tgl_masuk,23) between '".$_GET['from_tgl']."' and '".$_GET['to_tgl']."'");
	        }
		}else{
			$this->db->where('DAY(tgl_masuk)='.date('d').'');	
			$this->db->where('MONTH(tgl_masuk)='.date('m').'');	
			$this->db->where('YEAR(tgl_masuk)='.date('Y').'');
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


}
