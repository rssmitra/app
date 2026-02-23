<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Riwayat_kunjungan_pm_model extends CI_Model {

	var $table = 'tc_kunjungan';
	var $column = array('tc_kunjungan.no_kunjungan','tc_kunjungan.no_mr');
	var $select = 'tc_kunjungan.no_kunjungan,tc_kunjungan.no_mr,tc_kunjungan.no_registrasi,mt_karyawan.nama_pegawai as dokter, asal.nama_bagian as asal_bagian, tujuan.nama_bagian as tujuan_bagian, mt_master_pasien.nama_pasien, tc_kunjungan.tgl_masuk, tc_kunjungan.tgl_keluar,status_isihasil,kode_penunjang,pm_tc_penunjang.flag_mcu, status_daftar, kode_bagian_tujuan, pm_tc_penunjang.status_batal';

	var $order = array('pen.kode_penunjang' => 'desc');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _main_query()
	{
		/*
		 * Subquery pm_tc_penunjang: filter status_daftar dulu SEBELUM join
		 * agar optimizer tidak perlu scan seluruh tabel penunjang.
		 * Alias kolom disesuaikan agar tidak ambigu dengan tc_kunjungan.
		 */
		$penunjang_sub = $this->db
			->select('no_kunjungan, status_isihasil, kode_penunjang, flag_mcu, status_daftar, status_batal')
			->from('pm_tc_penunjang')
			->where('status_daftar IS NOT NULL AND status_daftar > 0', NULL, FALSE)
			->get_compiled_select();

		$this->db->select("
			tc_kunjungan.no_kunjungan, tc_kunjungan.no_mr, tc_kunjungan.no_registrasi,
			mt_karyawan.nama_pegawai   AS dokter,
			asal.nama_bagian           AS asal_bagian,
			tujuan.nama_bagian         AS tujuan_bagian,
			mt_master_pasien.nama_pasien,
			tc_kunjungan.tgl_masuk, tc_kunjungan.tgl_keluar,
			pen.status_isihasil, pen.kode_penunjang, pen.flag_mcu, pen.status_daftar,
			tc_kunjungan.kode_bagian_tujuan, pen.status_batal
		", FALSE);

		$this->db->from($this->table);
		// INNER JOIN subquery — filter penunjang dulu, baru join ke kunjungan
		$this->db->join("($penunjang_sub) pen", 'pen.no_kunjungan = tc_kunjungan.no_kunjungan', 'inner', FALSE);
		$this->db->join('mt_master_pasien', 'mt_master_pasien.no_mr = tc_kunjungan.no_mr', 'left');
		$this->db->join('mt_karyawan', 'mt_karyawan.kode_dokter = tc_kunjungan.kode_dokter', 'left');
		$this->db->join('mt_bagian as asal', 'asal.kode_bagian = tc_kunjungan.kode_bagian_asal', 'left');
		$this->db->join('mt_bagian as tujuan', 'tujuan.kode_bagian = tc_kunjungan.kode_bagian_tujuan', 'left');

		/*default*/
		if ($_GET) {

			if (isset($_GET['keyword']) AND $_GET['keyword'] != '') {
				if (isset($_GET['search_by']) AND $_GET['keyword'] != '') {
					if ($_GET['search_by'] == 'no_mr') {
						$this->db->where('mt_master_pasien.' . $_GET['search_by'], $_GET['keyword']);
					}
					if ($_GET['search_by'] == 'nama_pasien') {
						$this->db->like('mt_master_pasien.' . $_GET['search_by'], $_GET['keyword']);
					}
				}
			}

			if (isset($_GET['bulan']) AND $_GET['bulan'] != 0) {
				$this->db->where('MONTH(tc_kunjungan.tgl_masuk)=' . intval($_GET['bulan']));
			}

			if (isset($_GET['tahun']) AND $_GET['tahun'] != 0) {
				$this->db->where('YEAR(tc_kunjungan.tgl_masuk)=' . intval($_GET['tahun']));
			}

			if (isset($_GET['bagian_asal']) AND $_GET['bagian_asal'] != '') {
				$this->db->where('tc_kunjungan.kode_bagian_asal', $_GET['bagian_asal']);
			}

			if (isset($_GET['dokter']) AND $_GET['dokter'] != 0) {
				$this->db->where('tc_kunjungan.kode_dokter', $_GET['dokter']);
			}

			if (isset($_GET['bagian_tujuan']) AND $_GET['bagian_tujuan'] != '') {
				$this->db->where('tc_kunjungan.kode_bagian_tujuan', $_GET['bagian_tujuan']);

				// Default: tampilkan hari ini jika tidak ada filter tanggal
				if ((!isset($_GET['bulan'])) OR (!isset($_GET['tahun'])) OR ((!isset($_GET['from_tgl'])) AND (!isset($_GET['to_tgl'])))) {
					// Sargable — index pada tgl_masuk bisa digunakan (vs DATEDIFF pada kolom)
					$this->db->where("tc_kunjungan.tgl_masuk >= CAST(GETDATE() AS DATE) AND tc_kunjungan.tgl_masuk < DATEADD(DAY, 1, CAST(GETDATE() AS DATE))", NULL, FALSE);
				}

				if ((isset($_GET['bulan']) AND $_GET['bulan'] == 0) AND (isset($_GET['tahun']) AND $_GET['tahun'] == 0)
					AND ((isset($_GET['from_tgl']) AND $_GET['from_tgl'] == '') AND (isset($_GET['to_tgl']) AND $_GET['to_tgl'] == ''))) {
					if (isset($_GET['search_by']) AND $_GET['keyword'] == '') {
						$this->db->where('MONTH(tc_kunjungan.tgl_masuk)=' . intval(date('m')));
						$this->db->where('YEAR(tc_kunjungan.tgl_masuk)=' . intval(date('Y')));
					}
				}
			}

			if (isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' || isset($_GET['to_tgl']) AND $_GET['to_tgl'] != '') {
				$from = $this->db->escape($_GET['from_tgl'] . ' 00:00:00');
				$to   = $this->db->escape($_GET['to_tgl'] . ' 23:59:59');
				$this->db->where("tc_kunjungan.tgl_masuk BETWEEN $from AND $to");
			}

		} else {
			// Sargable — index pada tgl_masuk bisa digunakan
			$this->db->where("tc_kunjungan.tgl_masuk >= CAST(GETDATE() AS DATE) AND tc_kunjungan.tgl_masuk < DATEADD(DAY, 1, CAST(GETDATE() AS DATE))", NULL, FALSE);
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
		return $this->db->count_all_results();
	}

	public function count_all()
	{
		$this->_main_query();
		return $this->db->count_all_results();
	}

	/**
	 * Batch check transaksi kasir untuk semua kunjungan sekaligus.
	 * Menghindari N+1 query pada looping get_data().
	 * Mengembalikan array asosiatif: key = "no_registrasi_no_kunjungan" => true (lunas)
	 */
	public function get_trans_kasir_batch(array $no_kunjungan_list, $kode_bagian)
	{
		if (empty($no_kunjungan_list) || empty($kode_bagian)) return [];

		$this->db->select('tc_trans_kasir.no_registrasi, tc_trans_pelayanan.no_kunjungan');
		$this->db->from('tc_trans_kasir');
		$this->db->join('tc_trans_pelayanan', 'tc_trans_kasir.kode_tc_trans_kasir=tc_trans_pelayanan.kode_tc_trans_kasir', 'left');
		$this->db->where_in('tc_trans_pelayanan.no_kunjungan', $no_kunjungan_list);
		$this->db->where('kode_bagian', $kode_bagian);
		$rows = $this->db->get()->result();

		$map = [];
		foreach ($rows as $row) {
			$map[$row->no_registrasi . '_' . $row->no_kunjungan] = true;
		}
		return $map;
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
		$this->db->join('pm_tc_penunjang','pm_tc_penunjang.no_kunjungan=tc_kunjungan.no_kunjungan','inner');

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
	            $this->db->where('MONTH(tgl_masuk)='.intval($_GET['bulan']).'');
	        }

	        if (isset($_GET['tahun']) AND $_GET['tahun'] != 0) {
	            $this->db->where('YEAR(tgl_masuk)='.intval($_GET['tahun']).'');
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
						$this->db->where('MONTH(tgl_masuk)='.intval(date('m')).'');
						$this->db->where('YEAR(tgl_masuk)='.intval(date('Y')).'');
					}
				}
	        }

			if (isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' || isset($_GET['to_tgl']) AND $_GET['to_tgl'] != '') {
				$from = $this->db->escape($_GET['from_tgl'].' 00:00:00');
				$to   = $this->db->escape($_GET['to_tgl'].' 23:59:59');
				$this->db->where("tc_kunjungan.tgl_masuk BETWEEN $from AND $to");
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
		return $this->db->count_all_results();
	}

	public function count_all_by_mr()
	{
		$this->_main_query_by_mr();
		return $this->db->count_all_results();
	}


}
