<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Distribusi_permintaan_model extends CI_Model {

	var $table_nm = 'tc_permintaan_inst_nm';
	var $table = 'tc_permintaan_inst';
	var $column = array('a.nomor_permintaan', 'b.nama_bagian');
	var $select = 'a.id_tc_permintaan_inst, a.nomor_permintaan, a.tgl_permintaan, a.kode_bagian_minta, a.kode_bagian_kirim, a.status_batal, a.tgl_input, a.nomor_pengiriman, a.tgl_pengiriman, a.yg_serah, a.yg_terima, a.tgl_input_terima, a.id_dd_user_terima, a.keterangan_kirim, a.status_selesai, a.jenis_permintaan, a.tgl_acc, a.acc_by, a.status_acc';
	var $order = array('a.id_tc_permintaan_inst' => 'DESC');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _main_query(){
		$table = ($_GET['flag']=='non_medis') ? $this->table_nm : $this->table;

		$this->db->select($this->select);
		$this->db->select('CONVERT(NVARCHAR(1000), a.catatan) as catatan', FALSE);
		$this->db->select('b.nama_bagian as bagian_minta, c.fullname as nama_user_input');
		$this->db->select('det_agg.total_permintaan, det_agg.total_diterima');

		$this->db->from($table.' a');
		$this->db->join('mt_bagian b', 'b.kode_bagian=a.kode_bagian_minta', 'left');
		$this->db->join('tmp_user c', 'c.user_id=a.id_dd_user', 'left');

		// Ganti correlated subquery dengan single pre-aggregated JOIN
		// Sebelumnya: 2 subquery dieksekusi ulang per baris (N×2 round-trip ke tabel detail)
		// Sekarang: tabel detail di-scan sekali, hasil di-JOIN ke query utama
		$det_subquery = "(SELECT id_tc_permintaan_inst, SUM(jumlah_permintaan) as total_permintaan, SUM(jumlah_penerimaan) as total_diterima FROM {$table}_det GROUP BY id_tc_permintaan_inst)";
		$this->db->join("{$det_subquery} det_agg", 'det_agg.id_tc_permintaan_inst = a.id_tc_permintaan_inst', 'left', FALSE);
	}

	private function _get_datatables_query()
	{
		$this->_main_query();

		// Optimized date range filter - avoid function on column
		if (isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' || isset($_GET['to_tgl']) AND $_GET['to_tgl'] != '') {
			$this->db->where("a.tgl_permintaan >=", $_GET['from_tgl']);
			$this->db->where("a.tgl_permintaan <=", $_GET['to_tgl'].' 23:59:59');
		} else {
			// Use range instead of YEAR() function
			$year = date('Y');
			$this->db->where("a.tgl_permintaan >=", $year.'-01-01');
			$this->db->where("a.tgl_permintaan <=", $year.'-12-31 23:59:59');
		}

		if (isset($_GET['kode_bagian']) AND $_GET['kode_bagian'] != '') {
			$this->db->where('a.kode_bagian_minta', $_GET['kode_bagian']);
		}

		$this->db->where('a.status_acc', 1);
		$this->db->where('a.version', 1);
		
		// FILTER STATUS PENERIMAAN
		if (isset($_GET['status_penerimaan']) && $_GET['status_penerimaan'] != '') {
			switch ($_GET['status_penerimaan']) {
				case 'selesai':
					$this->db->where('a.tgl_input_terima IS NOT NULL');
					$this->db->where('a.yg_terima IS NOT NULL');
					break;
				
				case 'belum_diterima':
					$this->db->where('a.tgl_pengiriman IS NOT NULL');
					$this->db->where('a.tgl_input_terima IS NULL');
					$this->db->where('a.yg_terima IS NULL');
					break;
				
				case 'belum_dikirim':
					$this->db->where('a.tgl_pengiriman IS NULL');
					break;
			}
		}

		// Remove GROUP BY since we no longer join detail table
		// GROUP BY is not needed with subqueries

		$i = 0;
		foreach ($this->column as $item) {
			if (isset($_POST['search']['value']) && $_POST['search']['value']) {
				if ($i === 0) {
					$this->db->like($item, $_POST['search']['value']);
				} else {
					$this->db->or_like($item, $_POST['search']['value']);
				}
			}
			$column[$i] = $item;
			$i++;
		}
		
		if (isset($_POST['order'])) {
			$this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
		} else if (isset($this->order)) {
			$order = $this->order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}
	
	function get_datatables()
	{
		$this->_get_datatables_query();
		if ($_POST['length'] != -1) {
			$this->db->limit($_POST['length'], $_POST['start']);
		}
		$query = $this->db->get();
		return $query->result();
	}

	function count_filtered()
	{
		$this->_get_datatables_query();
		// count_all_results() menjalankan SELECT COUNT(*) di DB, bukan ambil semua baris ke PHP
		return $this->db->count_all_results();
	}

	public function count_all()
	{
		$this->_main_query();
		
		// Apply same filters as _get_datatables_query for accurate count
		if (isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' || isset($_GET['to_tgl']) AND $_GET['to_tgl'] != '') {
			$this->db->where("a.tgl_permintaan >=", $_GET['from_tgl']);
			$this->db->where("a.tgl_permintaan <=", $_GET['to_tgl'].' 23:59:59');
		} else {
			$year = date('Y');
			$this->db->where("a.tgl_permintaan >=", $year.'-01-01');
			$this->db->where("a.tgl_permintaan <=", $year.'-12-31 23:59:59');
		}
		
		$this->db->where('a.status_acc', 1);
		$this->db->where('a.version', 1);
		
		return $this->db->count_all_results();
	}

	public function get_by_id($id)
	{
		$table = ($_GET['flag']=='non_medis') ? $this->table_nm : $this->table;
		$this->_main_query();
		
		if (is_array($id)) {
			$this->db->where_in('a.id_tc_permintaan_inst', $id);
			$query = $this->db->get();
			return $query->result();
		} else {
			$this->db->where('a.id_tc_permintaan_inst', $id);
			$query = $this->db->get();
			return $query->row();
		}
	}

	public function save($table, $data)
	{
		$this->db->insert($table, $data);
		return $this->db->insert_id();
	}

	public function update($table, $where, $data)
	{
		$this->db->update($table, $data, $where);
		return $this->db->affected_rows();
	}

	public function delete_by_id($table, $id)
	{
		// Use transaction for data consistency
		$this->db->trans_start();
		$this->db->where_in('id_tc_permintaan_inst', $id)->delete($table);
		$this->db->where_in('id_tc_permintaan_inst', $id)->delete($table.'_det');
		$this->db->trans_complete();
		
		return $this->db->trans_status();
	}

	public function get_cart_data(){
		$this->db->select('kode_brg, nama_brg, qty, satuan, CAST(harga as INT) as harga, flag, nama_bagian, qtyBefore as jumlah_stok_sebelumnya, reff_kode, retur_type, is_bhp, is_restock, tc_permintaan_inst_cart_log.kode_bagian, keterangan as keterangan_permintaan, id as id_tc_permintaan_inst_det, '."'cart_log'".' as type_tbl');
		$this->db->from('tc_permintaan_inst_cart_log');
		$this->db->join('mt_bagian', 'mt_bagian.kode_bagian=tc_permintaan_inst_cart_log.kode_bagian', 'inner'); // Use INNER JOIN if nama_bagian is required
		$this->db->where('user_id_session', $this->session->userdata('user')->user_id);
		$this->db->where('flag_form', 'distribusi');
		$this->db->order_by('tc_permintaan_inst_cart_log.id', 'ASC');
		
		return $this->db->get()->result();
	}

	public function get_brg_permintaan($flag, $id){
		$mt_barang = ($flag=='non_medis') ? 'mt_barang_nm' : 'mt_barang';
		$table = ($flag=='non_medis') ? $this->table_nm : $this->table;
		$join_3 = ($flag=='non_medis') ? 'mt_depo_stok_nm' : 'mt_depo_stok';

		// Fixed: removed duplicate comma in select
		$this->db->select('a.id_tc_permintaan_inst_det, e.kode_bagian_kirim, a.jumlah_permintaan, a.jumlah_penerimaan, a.kode_brg, c.nama_brg, c.content as rasio, c.satuan_kecil, c.satuan_besar, e.nomor_permintaan, e.jenis_permintaan, e.tgl_permintaan, f.jml_sat_kcl as jumlah_stok_sebelumnya, g.nama_bagian, CAST(c.harga_beli as INT) as harga_beli');
		$this->db->from($table.'_det a');
		$this->db->join($mt_barang.' c', 'c.kode_brg=a.kode_brg', 'left');
		$this->db->join($table.' e', 'e.id_tc_permintaan_inst=a.id_tc_permintaan_inst', 'left');
		$this->db->join('mt_bagian g', 'g.kode_bagian=e.kode_bagian_minta', 'left');
		$this->db->join($join_3.' f', 'f.kode_brg=a.kode_brg AND f.kode_bagian=e.kode_bagian_kirim', 'left');
		
		// Use where_in for better performance
		if (is_array($id)) {
			$this->db->where_in('a.id_tc_permintaan_inst', $id);
		} else {
			$this->db->where('a.id_tc_permintaan_inst', $id);
		}
		
		// Simplified GROUP BY - only include non-aggregated columns actually needed
		$this->db->group_by('a.id_tc_permintaan_inst_det, e.kode_bagian_kirim, a.jumlah_permintaan, a.jumlah_penerimaan, a.kode_brg, c.nama_brg, c.content, c.satuan_kecil, c.satuan_besar, e.nomor_permintaan, e.jenis_permintaan, e.tgl_permintaan, f.jml_sat_kcl, g.nama_bagian, CAST(c.harga_beli as INT)');
		$this->db->order_by('c.nama_brg', 'ASC');
		
		return $this->db->get()->result();
	}
}