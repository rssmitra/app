<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pl_rekap_kunjungan_model extends CI_Model {

	var $table = 'pl_tc_poli';
	var $column = array('tc_kunjungan.no_kunjungan','tc_kunjungan.no_mr');
	var $select = 'pl_tc_poli.no_kunjungan,tc_kunjungan.no_mr,tc_kunjungan.no_registrasi,mt_karyawan.nama_pegawai as dokter, asal.nama_bagian as asal_bagian, tujuan.nama_bagian as tujuan_bagian, mt_master_pasien.nama_pasien, pl_tc_poli.tgl_jam_poli, pl_tc_poli.id_pl_tc_poli,pl_tc_poli.no_antrian, pl_tc_poli.status_batal, pl_tc_poli.status_periksa, pl_tc_poli.kode_gcu, pl_tc_poli.tgl_keluar_poli, pl_tc_poli.kode_bagian, pl_tc_poli.kode_dokter';

	var $order = array('pl_tc_poli.tgl_jam_poli' => 'DESC');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _main_query(){
		$this->db->select($this->select);
		$this->db->from($this->table);
		$this->db->join('tc_kunjungan','pl_tc_poli.no_kunjungan=tc_kunjungan.no_kunjungan','left');
		$this->db->join('mt_master_pasien','mt_master_pasien.no_mr=tc_kunjungan.no_mr','left');
		$this->db->join('mt_karyawan','mt_karyawan.kode_dokter=pl_tc_poli.kode_dokter','left');
		$this->db->join('mt_bagian as asal','asal.kode_bagian=tc_kunjungan.kode_bagian_asal','left');
		$this->db->join('mt_bagian as tujuan','tujuan.kode_bagian=pl_tc_poli.kode_bagian','left');
		
		/*default*/
		if( $_GET ) {

			if(isset($_GET['keyword']) AND $_GET['keyword'] != ''){
				$this->db->like(''.$_GET['search_by'].'', $_GET['keyword']);
			}

			if (isset($_GET['bulan']) AND $_GET['bulan'] != 0) {
	            $this->db->where('MONTH(tgl_jam_poli)='.$_GET['bulan'].'');	
	        }

	        if (isset($_GET['tahun']) AND $_GET['tahun'] != 0) {
	            $this->db->where('YEAR(tgl_jam_poli)='.$_GET['tahun'].'');	
	        }

			if (isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' || isset($_GET['to_tgl']) AND $_GET['to_tgl'] != '') {
				$this->db->where("convert(varchar,pl_tc_poli.tgl_jam_poli,23) between '".$_GET['from_tgl']."' and '".$_GET['to_tgl']."'");
	        }
		}else{
			$this->db->where('DAY(tgl_jam_poli)='.date('d').'');	
			$this->db->where('MONTH(tgl_jam_poli)='.date('m').'');	
			$this->db->where('YEAR(tgl_jam_poli)='.date('Y').'');
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
		// print_r($this->db->last_query());die;
		return $query->result();
	}

	function get_data()
	{
		$this->_main_query();
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
		$this->_main_query();
		return $this->db->count_all_results();
	}


}
