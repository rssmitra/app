<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penyerahan_obat_model extends CI_Model {

	var $table = 'fr_tc_far';
	var $column = array('nama_pasien', 'fr_tc_far.no_mr');
	var $select = 'no_registrasi, fr_tc_far.kode_trans_far,nama_pasien,dokter_pengirim,no_resep,fr_tc_far.no_kunjungan,fr_tc_far.no_mr, kode_pesan_resep, tgl_trans, nama_pelayanan, alamat_pasien, telpon_pasien, fr_tc_far.status_transaksi, status_terima, flag_trans';

	var $order = array('kode_trans_far' => 'ASC');

	public function __construct()
	{
		parent::__construct();
	}

	private function _main_query(){

		$this->db->select($this->select);
		$this->db->from($this->table);
		$this->db->join('fr_mt_profit_margin','fr_mt_profit_margin.kode_profit=fr_tc_far.kode_profit','left');
		$this->db->where('status_terima NOT IN (1,2)');
		$this->db->where('flag_trans', 'RJ');
		$this->db->where('CAST(tgl_trans as DATE) = '."'".date('Y-m-d')."'".'');

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
		// $this->db->limit(5, 0);
		$query = $this->db->get();
		// print_r($this->db->last_query());die;
		return $query->result();
	}


	private function _main_query_dt_pending(){

		$this->db->select($this->select);
		$this->db->from($this->table);
		$this->db->join('fr_mt_profit_margin','fr_mt_profit_margin.kode_profit=fr_tc_far.kode_profit','left');
		$this->db->where('status_terima = 2 ');
		$this->db->where('flag_trans', 'RJ');
		$this->db->where('CAST(tgl_trans as DATE) = '."'".date('Y-m-d')."'".'');
		$this->db->group_by($this->select);

	}

	private function _get_datatables_query_dt_pending()
	{
		
		$this->_main_query_dt_pending();

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

	function get_datatables_pending()
	{
		$this->_get_datatables_query_dt_pending();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		// print_r($this->db->last_query());die;
		return $query->result();
	}

	function count_filtered()
	{
		$this->_get_datatables_query_dt_pending();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all()
	{
		$this->_get_datatables_query_dt_pending();
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
		a.jumlah_tebus, a.jumlah_pesan, c.satuan_kecil, b.urgensi, b.dosis_obat, b.dosis_per_hari, b.aturan_pakai, b.anjuran_pakai, b.catatan_lainnya, b.status_tebus, b.tgl_input, b.status_input, b.prb_ditangguhkan, b.jumlah_obat_23, b.satuan_obat, b.jumlah_retur, tgl_retur, d.no_registrasi, d.no_mr, d.nama_pasien, d.kode_bagian_asal, d.kode_bagian, d.kode_profit, no_kunjungan, d.flag_trans, a.resep_ditangguhkan');
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
