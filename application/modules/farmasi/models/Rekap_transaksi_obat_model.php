<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rekap_transaksi_obat_model extends CI_Model {

	var $table = 'fr_tc_far_detail_log';
	var $column = array('nama_pasien', 'dokter_pengirim','nama_brg');
	var $select = 'tgl_trans,fr_tc_far.kode_trans_far, nama_pasien,fr_tc_far.no_mr,nama_brg, dokter_pengirim,"nama_pelayanan",';

	var $order = array('tgl_trans' => 'DESC');

	public function __construct()
	{
		parent::__construct();
	}

	private function _main_query(){

		$this->db->select($this->select);
		$this->db->select('CAST(((jumlah_tebus + jumlah_obat_23) - jumlah_retur) AS INT) as jumlah');
		$this->db->select('(SELECT TOP 1 diagnosa_akhir FROM th_riwayat_pasien WHERE no_kunjungan = fr_tc_far.no_kunjungan ) AS diagnosa_akhir');
		$this->db->from($this->table);
		$this->db->join('fr_tc_far','fr_tc_far.kode_trans_far=fr_tc_far_detail_log.kode_trans_far','left');
		$this->db->join('fr_mt_profit_margin','fr_mt_profit_margin.kode_profit=fr_tc_far.kode_profit','left');
		$this->db->group_by($this->select);
		$this->db->group_by('jumlah_tebus, jumlah_obat_23, jumlah_retur, no_kunjungan');

	}

	private function _filter(){
		if(isset($_GET['search_by']) AND $_GET['search_by'] != '' AND isset($_GET['keyword']) AND $_GET['keyword'] != '' ){
			if (in_array($_GET['search_by'], array('no_mr', 'nama_pasien') )) {
				// no action
				$this->db->like('fr_tc_far.'.$_GET['search_by'].'', $_GET['keyword']);
			}else{
				$this->db->like('fr_tc_far.'.$_GET['search_by'].'', $_GET['keyword']);
			}
		}

		if( isset($_GET['poliklinik']) AND $_GET['poliklinik'] != 0 ){
			$this->db->where('fr_tc_far.kode_bagian_asal', $_GET['poliklinik']);
		}

		if( isset($_GET['select_dokter']) AND $_GET['select_dokter'] != 0 ){
			$this->db->where('fr_tc_far.kode_dokter', $_GET['select_dokter']);
		}

		if (isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' or isset($_GET['to_tgl']) AND $_GET['to_tgl'] != '') {
			$this->db->where("CAST(fr_tc_far.tgl_trans AS DATE) BETWEEN '".$_GET['from_tgl']."' AND '".$_GET['to_tgl']."' " );
        }else{
			$this->db->where('DATEDIFF(Day, tgl_trans, getdate())<=7');
		}

		if( isset($_GET['no_mr']) AND $_GET['no_mr'] != 0 ){
			$this->db->where('fr_tc_far.no_mr', $_GET['no_mr']);
		}
		if(isset($_GET['flag'])){
			if( isset($_GET['flag']) AND $_GET['flag'] != 'All' ){
				$this->db->like('fr_tc_far.no_resep', $_GET['flag']);
			}
		}
		
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
		$query = $this->db->get();
		// print_r($this->db->last_query());die;
		return $query->result();
	}

	function get_data()
	{
		$this->_main_query();
		$this->_filter();
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

	public function get_by_id($id)
	{
		$this->_main_query();
		if(is_array($id)){
			$this->db->where_in(''.$this->table.'.kode_trans_far',$id);
			$query = $this->db->get();
			return $query->result();
		}else{
			$this->db->where(''.$this->table.'.kode_trans_far',$id);
			$query = $this->db->get();
			// print_r($this->db->last_query());die;
			return $query->row();
		}
		
	}

	public function save($table, $data)
	{
		/*insert tc_registrasi*/
		$this->db->insert($table, $data);
		return $this->db->insert_id();;
	}

	public function update($table, $data, $where)
	{
		$this->db->update($table, $data, $where);
		return $this->db->affected_rows();
	}

	public function delete_by_id($id)
	{
		$get_data = $this->get_by_id($id);
		$this->db->where_in(''.$this->table.'.no_registrasi', $id);
		return $this->db->update($this->table, array('is_deleted' => 'Y', 'is_active' => 'N'));
	}

	public function save_pm($table, $data)
	{
		/*insert tc_registrasi*/
		$this->db->insert($table, $data);
		
		return $this->db->insert_id();;
	}

	public function get_detail_by_kode_brg($kode_brg)
	{
		return $this->db->join('mt_jenis_obat','mt_jenis_obat.kode_jenis=mt_barang.kode_jenis','left')
						->join('mt_pabrik','mt_pabrik.id_pabrik=mt_barang.id_pabrik','left')
						->get_where('mt_barang', array('kode_brg' => $kode_brg))->row();		
	}

	public function get_detail_by_kode_tr_resep($id)
	{
		$this->db->from('fr_tc_far_detail_log a');	
		$this->db->where('relation_id', $id);	
		return $this->db->get()->row();	
	}

	public function get_detail_resep_data($kode_trans_far){
		$this->db->select("CASE WHEN a.id_tc_far_racikan = 0 THEN c.nama_brg ELSE b.nama_brg END as nama_brg", false);
		$this->db->select("CASE WHEN b.harga_jual_satuan IS NULL THEN a.harga_jual ELSE b.harga_jual_satuan END as harga_jual", false);
		$this->db->select("CASE WHEN b.sub_total IS NULL THEN a.biaya_tebus ELSE b.sub_total END as sub_total", false);
		$this->db->select("CASE WHEN b.total IS NULL THEN (a.harga_jual + a.harga_r) ELSE b.total END as total", false);
		$this->db->select("CASE WHEN b.jasa_r IS NULL THEN a.harga_r ELSE (b.jasa_r + b.jasa_produksi) END as jasa_r", false);
		$this->db->select("CASE WHEN a.id_tc_far_racikan = 0 THEN a.kd_tr_resep ELSE a.id_tc_far_racikan END as relation_id", false);
		$this->db->select("CASE WHEN a.id_tc_far_racikan = 0 THEN 'biasa' ELSE 'racikan' END as flag_resep", false);
		$this->db->select('a.kd_tr_resep, a.kode_trans_far, a.kode_brg, a.id_tc_far_racikan, 
		a.jumlah_tebus, a.jumlah_pesan, c.satuan_kecil, b.urgensi, b.dosis_obat, b.dosis_per_hari, b.aturan_pakai, b.anjuran_pakai, b.catatan_lainnya, b.status_tebus, b.tgl_input, b.status_input, b.prb_ditangguhkan, b.jumlah_obat_23, b.satuan_obat, a.jumlah_retur, tgl_retur, d.no_registrasi, d.no_mr, d.nama_pasien, d.kode_bagian_asal, d.kode_bagian, d.kode_profit, no_kunjungan, d.flag_trans, a.resep_ditangguhkan');
		$this->db->from('fr_tc_far_detail a');
		$this->db->join('fr_tc_far_detail_log b','(a.kode_brg=b.kode_brg AND a.kode_trans_far=b.kode_trans_far)','left');
		$this->db->join('fr_tc_far d','d.kode_trans_far=a.kode_trans_far','left');
		$this->db->join('mt_barang c','c.kode_brg=a.kode_brg','left');
		$this->db->where('a.kode_trans_far', $kode_trans_far);
		return $this->db->get();
	}

	public function get_etiket_data(){
		$data = ($_GET)?$_GET:$_POST;
		$this->db->select('b.kode_brg, b.nama_brg, dosis_obat, aturan_pakai, satuan_obat, jumlah_obat, catatan_lainnya, anjuran_pakai, dosis_per_hari, a.nama_pasien, a.no_mr, a.tgl_trans');
		$this->db->from('fr_tc_far_detail_log b');
		$this->db->join('fr_tc_far a', 'a.kode_trans_far=b.kode_trans_far','left');
		$this->db->where_in('relation_id', $data);
		return $this->db->get();
	}

	public function get_history_retur($kode_trans_far){
		$this->db->from('fr_tc_far_his a');
		$this->db->join('fr_tc_far_detail_log b','b.relation_id=a.kd_tr_resep','left');
		$this->db->where('kd_tr_resep IN (select kd_tr_resep from fr_tc_far_detail where kode_trans_far = '.$kode_trans_far.')');
		$data = $this->db->get()->result();
		$getData = [];
		foreach ($data as $key => $value) {
			$getData[$value->no_retur][] = $value;
		}
		return $getData;
	}

	public function get_history_retur_by_no_retur($no_retur){
		$this->db->from('fr_tc_far_his a');
		$this->db->join('fr_tc_far c','c.kode_trans_far=a.kode_trans_far','left');
		$this->db->join('mt_bagian d','d.kode_bagian=c.kode_bagian_asal','left');
		$this->db->join('fr_tc_far_detail_log b','b.relation_id=a.kd_tr_resep','left');
		$this->db->where('no_retur', $no_retur);
		$data = $this->db->get()->result();
		return $data;
	}

	
}
