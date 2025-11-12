<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Perjanjian_rj_model extends CI_Model {

	var $table = 'tc_pesanan';
	var $column = array('tc_pesanan.nama','mt_bagian.nama_bagian','mt_karyawan.nama_pegawai','mt_perusahaan.nama_perusahaan');
	var $select = 'tc_pesanan.id_tc_pesanan, tc_pesanan.nama, tc_pesanan.tgl_pesanan, tc_pesanan.no_mr, mt_bagian.nama_bagian, mt_karyawan.nama_pegawai, mt_perusahaan.nama_perusahaan, tc_pesanan.tgl_masuk, tc_pesanan.kode_dokter, tc_pesanan.no_poli, tc_pesanan.kode_perjanjian, tc_pesanan.unique_code_counter, tc_pesanan.selected_day, tc_pesanan.no_telp, tc_pesanan.no_hp, tc_pesanan.keterangan, mt_master_pasien.tlp_almt_ttp, mt_master_pasien.no_hp as no_hp_pasien, no_kartu_bpjs, input_tgl, tc_pesanan.jd_id, is_bridging';

	var $order = array('tc_pesanan.tgl_pesanan' => 'DESC', 'tc_pesanan.id_tc_pesanan' => 'DESC');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _main_query() {
		$this->db->select($this->select);
		$this->db->select('fullname as petugas, jeniskunjunganjkn, jeniskunjungan.label as namajeniskunjungan');
		$this->db->from('tc_pesanan');
		$this->db->join('mt_bagian', 'mt_bagian.kode_bagian=tc_pesanan.no_poli', 'inner');
		$this->db->join('mt_karyawan', 'mt_karyawan.kode_dokter=tc_pesanan.kode_dokter', 'left');
		$this->db->join('tmp_user', 'tmp_user.user_id=tc_pesanan.no_induk', 'left');
		$this->db->join('mt_master_pasien', 'mt_master_pasien.no_mr=tc_pesanan.no_mr', 'left');
		$this->db->join('mt_perusahaan', 'mt_perusahaan.kode_perusahaan=tc_pesanan.kode_perusahaan', 'left');
		$this->db->join("(SELECT * FROM global_parameter WHERE flag = 'jeniskunjunganbpjs') as jeniskunjungan", 'jeniskunjungan.value=tc_pesanan.jeniskunjunganjkn', 'left');
		$this->db->where('tc_pesanan.tgl_masuk IS NULL');

		// Filter by no_mr
		if (!empty($_GET['no_mr'])) {
			$this->db->where('tc_pesanan.no_mr', $_GET['no_mr']);
		}

		// Parameterized search
		if (!empty($_GET['search_by'])) {
			// Flag filter
			if (!empty($_GET['flag'])) {
				if ($_GET['flag'] === 'bedah' || $_GET['flag'] === 'HD') {
					$this->db->where('tc_pesanan.flag', $_GET['flag']);
				}
			} else {
				$this->db->where('tc_pesanan.flag IS NULL');
			}

			// Keyword search
			if (!empty($_GET['keyword'])) {
				$this->db->like('tc_pesanan.' . $_GET['search_by'], $_GET['keyword']);
			}

			// Klinik filter
			if (!empty($_GET['klinik'])) {
				$this->db->where('tc_pesanan.no_poli', (int)$_GET['klinik']);
			}

			// Dokter filter
			if (!empty($_GET['dokter'])) {
				$this->db->where('tc_pesanan.kode_dokter', $_GET['dokter']);
			}

			// Tanggal pesanan range
			if (!empty($_GET['from_tgl']) && !empty($_GET['to_tgl'])) {
				$this->db->where('CAST(tc_pesanan.tgl_pesanan as DATE) >=', $_GET['from_tgl']);
				$this->db->where('CAST(tc_pesanan.tgl_pesanan as DATE) <=', $_GET['to_tgl']);
			} else if (!empty($_GET['from_tgl'])) {
				$this->db->where('CAST(tc_pesanan.tgl_pesanan as DATE) >=', $_GET['from_tgl']);
			} else if (!empty($_GET['to_tgl'])) {
				$this->db->where('CAST(tc_pesanan.tgl_pesanan as DATE) <=', $_GET['to_tgl']);
			}

			// Tanggal input
			if (!empty($_GET['tgl_input_prj'])) {
				$this->db->where('CAST(tc_pesanan.input_tgl as DATE) = ', $_GET['tgl_input_prj']);
			}
		} else {
			// Default: hari ini
			// Default: next 3 months (including today)
			$this->db->where('CAST(tc_pesanan.input_tgl as DATE) >=', date('Y-m-d', strtotime('-3 month')));
			// $this->db->where('CAST(tc_pesanan.input_tgl as DATE) <=', date('Y-m-d', strtotime('+3 months')));
		}
	}

	private function _get_datatables_query()
	{
		$this->_main_query();

		$searchValue = isset($_POST['search']['value']) ? trim($_POST['search']['value']) : '';
		$column = $this->column;

		if ($searchValue !== '') {
			$this->db->group_start();
			foreach ($column as $idx => $item) {
				if ($idx === 0) {
					$this->db->like($item, $searchValue);
				} else {
					$this->db->or_like($item, $searchValue);
				}
			}
			$this->db->group_end();
		}

		if (isset($_POST['order']) && isset($_POST['order'][0]['column']) && isset($_POST['order'][0]['dir'])) {
			$orderColIdx = (int)$_POST['order'][0]['column'];
			$orderDir = $_POST['order'][0]['dir'] === 'asc' ? 'asc' : 'desc';
			if (isset($column[$orderColIdx])) {
				$this->db->order_by($column[$orderColIdx], $orderDir);
			}
		} else if (!empty($this->order)) {
			foreach ($this->order as $key => $val) {
				$this->db->order_by($key, $val);
			}
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
