<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Riwayat_kunjungan_pm_model extends CI_Model {

	var $table = 'tc_kunjungan';
	var $column = array('tc_kunjungan.no_kunjungan','tc_kunjungan.no_mr');
	var $select = 'tc_kunjungan.no_kunjungan,tc_kunjungan.no_mr,tc_kunjungan.no_registrasi,mt_karyawan.nama_pegawai as dokter, asal.nama_bagian as asal_bagian, tujuan.nama_bagian as tujuan_bagian, mt_master_pasien.nama_pasien, tc_kunjungan.tgl_masuk, tc_kunjungan.tgl_keluar,status_isihasil,kode_penunjang,pm_tc_penunjang.flag_mcu, status_daftar, kode_bagian_tujuan, pm_tc_penunjang.status_batal';

	var $order = array('pm_tc_penunjang.kode_penunjang' => 'desc');

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
		$this->db->join('pm_tc_penunjang','pm_tc_penunjang.no_kunjungan=tc_kunjungan.no_kunjungan','left');
	
		/*default*/
		if( $_GET ) {

			if( (isset($_GET['keyword']) AND $_GET['keyword']!='')  ){
				if(isset($_GET['search_by']) AND $_GET['keyword'] != ''){
					if($_GET['search_by']=='no_mr' ){
						$this->db->where('mt_master_pasien.'.$_GET['search_by'].'', $_GET['keyword']);
					}
			
					if($_GET['search_by']=='nama_pasien'  ){
						$this->db->like('mt_master_pasien.'.$_GET['search_by'].'', $_GET['keyword']);
					}
	
				}
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
				
				if( (!isset($_GET['bulan'])) OR (!isset($_GET['tahun'])) OR ( (!isset($_GET['from_tgl'])) AND (!isset($_GET['to_tgl'])) )){
					$this->db->where('DATEDIFF(Day, tgl_masuk, getdate()) <= 0');	
					// $this->db->where('DAY(tgl_masuk)='.date('d').'');	
					// $this->db->where('MONTH(tgl_masuk)='.date('m').'');	
					// $this->db->where('YEAR(tgl_masuk)='.date('Y').'');
				}

				if( (isset($_GET['bulan']) AND $_GET['bulan'] == 0) AND (isset($_GET['tahun']) AND $_GET['tahun']== 0) AND ( (isset($_GET['from_tgl']) AND $_GET['from_tgl']=='') AND (isset($_GET['to_tgl']) AND $_GET['to_tgl']=='') )){
					if(isset($_GET['search_by']) AND $_GET['keyword'] == ''){
						$this->db->where('MONTH(tgl_masuk)='.date('m').'');	
						$this->db->where('YEAR(tgl_masuk)='.date('Y').'');
					}
				}	
	        }

			if (isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' || isset($_GET['to_tgl']) AND $_GET['to_tgl'] != '') {
				$this->db->where("convert(varchar,tc_kunjungan.tgl_masuk,23) between '".$_GET['from_tgl']."' and '".$_GET['to_tgl']."'");
	        }

		}else{
			$this->db->where('DATEDIFF(Day, tgl_masuk, getdate()) <= 0');	
		}

		$this->db->where("(pm_tc_penunjang.status_daftar is not null or pm_tc_penunjang.status_daftar != 0 )");

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

	function get_data_by_nomr($no_mr)
	{
		$this->db->select($this->select);
		$this->db->from($this->table);
		$this->db->join('mt_master_pasien','mt_master_pasien.no_mr=tc_kunjungan.no_mr','left');
		$this->db->join('mt_karyawan','mt_karyawan.kode_dokter=tc_kunjungan.kode_dokter','left');
		$this->db->join('mt_bagian as asal','asal.kode_bagian=tc_kunjungan.kode_bagian_asal','left');
		$this->db->join('mt_bagian as tujuan','tujuan.kode_bagian=tc_kunjungan.kode_bagian_tujuan','left');
		$this->db->join('pm_tc_penunjang','pm_tc_penunjang.no_kunjungan=tc_kunjungan.no_kunjungan','left');
		$this->db->where("(pm_tc_penunjang.status_daftar is not null or pm_tc_penunjang.status_daftar != 0 )");
		$this->db->where("tc_kunjungan.no_mr", $no_mr);
		$this->db->order_by('kode_penunjang', 'DESC');
		$this->db->limit(10);
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

	public function cek_transaksi_kasir($no_registrasi, $no_kunjungan){
		$this->db->select('tc_trans_kasir.*');
		$this->db->from('tc_trans_kasir');
		$this->db->join('tc_trans_pelayanan','tc_trans_kasir.kode_tc_trans_kasir=tc_trans_pelayanan.kode_tc_trans_kasir','left');
		$this->db->where(array('tc_trans_kasir.no_registrasi' => $no_registrasi,'tc_trans_pelayanan.no_kunjungan' => $no_kunjungan,'kode_bagian' => $_GET['bagian_tujuan']));
		$query = $this->db->get();
		$trans_kasir = $query->num_rows();
		//$trans_kasir = $this->db->get_where('tc_trans_kasir', array('no_registrasi' => $no_registrasi,'kode_bagian' => $_GET['bagian_tujuan']))->num_rows();
		if($trans_kasir > 0){
			return false;
		}else{
			return true;
		}
	}

	// get data by mr and detail pemeriksaan
	function get_datatables_by_mr()
	{
		$this->_get_datatables_query_by_mr();
		// if($_POST['length'] != -1)
		// $this->db->limit(10);
		$query = $this->db->get();
		// print_r($this->db->last_query());die;
		return $query->result();
	}

	private function _get_datatables_query_by_mr()
	{
		
		$this->_main_query_by_mr();

		$i = 0;
	
		foreach ($this->column as $item) 
		{
			if($_POST['search']['value'])
				($i===0) ? $this->db->like($item, $_POST['search']['value']) : $this->db->or_like($item, $_POST['search']['value']);
			$column[$i] = $item;
			$i++;
		}
		
		// if(isset($_POST['order']))
		// {
		// 	$this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		// } 
		// else if(isset($this->order))
		// {
		// 	$order = $this->order;
		// 	$this->db->order_by(key($order), $order[key($order)]);
		// }
	}

	private function _main_query_by_mr(){
		$this->db->select($this->select);
		$this->db->select('tgl_daftar, tgl_isihasil, tgl_periksa');

		$this->db->select("CAST((
			SELECT '|' + nama_tindakan
			FROM tc_trans_pelayanan
			LEFT JOIN pm_tc_penunjang ON pm_tc_penunjang.no_kunjungan=tc_trans_pelayanan.no_kunjungan
			  LEFT JOIN tc_kunjungan s ON s.no_kunjungan=pm_tc_penunjang.no_kunjungan
			WHERE s.no_kunjungan = tc_kunjungan.no_kunjungan
			FOR XML PATH(''))as varchar(max)) as nama_tarif");

		$this->db->from($this->table);
		$this->db->join('mt_master_pasien','mt_master_pasien.no_mr=tc_kunjungan.no_mr','left');
		$this->db->join('mt_karyawan','mt_karyawan.kode_dokter=tc_kunjungan.kode_dokter','left');
		$this->db->join('mt_bagian as asal','asal.kode_bagian=tc_kunjungan.kode_bagian_asal','left');
		$this->db->join('mt_bagian as tujuan','tujuan.kode_bagian=tc_kunjungan.kode_bagian_tujuan','left');
		$this->db->join('pm_tc_penunjang','pm_tc_penunjang.no_kunjungan=tc_kunjungan.no_kunjungan','left');
	
		/*default*/
		if( $_GET ) {

			if( (isset($_GET['keyword']) AND $_GET['keyword']!='')  ){
				if(isset($_GET['search_by']) AND $_GET['keyword'] != ''){
					if($_GET['search_by']=='no_mr' ){
						$this->db->where('mt_master_pasien.'.$_GET['search_by'].'', $_GET['keyword']);
					}
			
					if($_GET['search_by']=='nama_pasien'  ){
						$this->db->like('mt_master_pasien.'.$_GET['search_by'].'', $_GET['keyword']);
					}
	
				}
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
				
				if( (isset($_GET['bulan']) AND $_GET['bulan'] == 0) AND (isset($_GET['tahun']) AND $_GET['tahun']== 0) AND ( (isset($_GET['from_tgl']) AND $_GET['from_tgl']=='') AND (isset($_GET['to_tgl']) AND $_GET['to_tgl']=='') )){
					if(isset($_GET['search_by']) AND $_GET['keyword'] == ''){
						$this->db->where('MONTH(tgl_masuk)='.date('m').'');	
						$this->db->where('YEAR(tgl_masuk)='.date('Y').'');
					}
				}	
	        }

			if (isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' || isset($_GET['to_tgl']) AND $_GET['to_tgl'] != '') {
				$this->db->where("convert(varchar,tc_kunjungan.tgl_masuk,23) between '".$_GET['from_tgl']."' and '".$_GET['to_tgl']."'");
	        }

		}

		// $this->db->where("(pm_tc_penunjang.status_daftar is not null or pm_tc_penunjang.status_daftar != 0 )");
		$this->db->where("pm_tc_penunjang.kode_bagian in ('050101','050201')");
		$this->db->order_by("pm_tc_penunjang.tgl_daftar", 'DESC');
		$this->db->order_by("pm_tc_penunjang.status_daftar", 'DESC');
		$this->db->where('DATEDIFF(Year, tgl_masuk, getdate()) < 2');	

		/*end parameter*/
		
	}

	function count_filtered_by_mr()
	{
		$this->_get_datatables_query_by_mr();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all_by_mr()
	{
		$this->_main_query_by_mr();
		return $this->db->count_all_results();
	}


}
