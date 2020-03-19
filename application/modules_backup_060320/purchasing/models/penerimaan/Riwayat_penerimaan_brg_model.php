<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Riwayat_penerimaan_brg_model extends CI_Model {

	var $table_nm = 'tc_penerimaan_barang_nm';
	var $table = 'tc_penerimaan_barang';
	var $column = array('a.kode_penerimaan','a.no_po');
	var $select = 'a.kode_penerimaan, a.no_po, a.id_tc_po, a.tgl_penerimaan, a.keterangan, a.no_faktur, a.dikirim, a.id_penerimaan, c.namasupplier, total_brg.jml_diterima';
	var $order = array('a.id_penerimaan' => 'DESC');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _main_query(){
		$table = ($_GET['flag']=='non_medis')?$this->table_nm:$this->table;
		$backmonth = date('m') - 3;

		$this->db->select($this->select);
		$this->db->from(''.$table.' a');
		$this->db->join('mt_supplier c','c.kodesupplier=a.kodesupplier', 'left');
		$this->db->join('(SELECT id_penerimaan, count(kode_detail_penerimaan_barang)as jml_diterima FROM tc_penerimaan_barang_detail GROUP BY id_penerimaan) as total_brg','total_brg.id_penerimaan=a.id_penerimaan', 'left');

		if( ( isset( $_GET['keyword']) AND $_GET['keyword'] != '' )  ){
			if( isset( $_GET['search_by']) AND $_GET['search_by'] == 'no_po' ){
				$this->db->like( $_GET['search_by'], $_GET['keyword'] );
			}
		}

		if( isset( $_GET['search_by']) AND $_GET['search_by'] == 'month' ){
			$this->db->where( 'MONTH(a.tgl_penerimaan)', $_GET['month'] );
			$this->db->where('YEAR(a.tgl_penerimaan)', date('Y'));
		}

		if( isset( $_GET['search_by']) AND $_GET['search_by'] == 'supplier' ){
			$this->db->where( 'a.kodesupplier', $_GET['kodesupplier'] );
		}

		if (isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' || isset($_GET['to_tgl']) AND $_GET['to_tgl'] != '') {
			$this->db->where("convert(varchar,a.tgl_penerimaan,23) between '".$_GET['from_tgl']."' and '".$_GET['to_tgl']."'");
		}else{
			// $this->db->where('MONTH(a.tgl_penerimaan) >= '.$backmonth.'');
			$this->db->where('YEAR(a.tgl_penerimaan)', date('Y'));
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
		$query = $this->db->get();
		// echo '<pre>';print_r($this->db->last_query());die;
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

	public function get_by_id($id)
	{
		$table = ($_GET['flag']=='non_medis')?$this->table_nm:$this->table;
		$this->_main_query();
		if(is_array($id)){
			$this->db->where_in('a.id_penerimaan_det',$id);
			$query = $this->db->get();
			return $query->result();
		}else{
			$this->db->where('a.id_penerimaan_det',$id);
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


	public function get_detail_table($flag, $id){
		$t_barang = ($flag=='non_medis')?'mt_barang_nm':'mt_barang';
		$table = ($flag=='non_medis')?$this->table_nm:$this->table;
		$t_po = ($flag=='non_medis')?'tc_po_nm':'tc_po';
		$t_rekap_stok = ($flag=='non_medis')?'mt_rekap_stok_nm':'mt_rekap_stok';

		$this->db->select('y.kode_penerimaan, z.no_po, a.id_penerimaan, a.id_tc_po_det, a.kode_brg, a.content, c.nama_brg, c.satuan_besar, c.satuan_kecil, jumlah_besar_acc as jumlah_besar, SUM(a.jumlah_kirim) as jumlah_kirim, CAST(d.harga_satuan_netto as INT) as harga_satuan_netto, CAST(d.jumlah_harga_netto as INT) as total_harga_netto, CAST(d.harga_satuan as INT) as harga_satuan, CAST(d.jumlah_harga as INT) as total_harga, batch_log.jml_diterima, batch_log.kode_box, batch_log.kode_pcs, batch_log.no_batch, batch_log.jenis_satuan, batch_log.tgl_expired, batch_log.is_expired, batch_log.id_tc_batch_log, d.discount, d.ppn');
		$this->db->from(''.$table.'_detail a');
		$this->db->join($table.' Y', 'y.id_penerimaan=a.id_penerimaan', 'left');
		$this->db->join($t_po.' z', 'z.id_tc_po=y.id_tc_po', 'left');
		$this->db->join($t_po.'_det d', 'd.id_tc_po_det=a.id_tc_po_det', 'left');
		$this->db->join($t_barang.' c', 'c.kode_brg=a.kode_brg', 'left');
		$this->db->join('(SELECT * FROM tc_penerimaan_brg_batch_log WHERE reff_table='."'".$table."'".') as batch_log','batch_log.id_tc_po_det=a.id_tc_po_det','left');
		$id = (is_array($id)) ? implode(',', $id) : $id ;
		$this->db->where('a.id_penerimaan IN ('.$id.')');
		$this->db->group_by('y.kode_penerimaan, z.no_po, a.id_penerimaan, a.id_tc_po_det, a.kode_brg, a.content, c.nama_brg, jumlah_besar_acc as jumlah_besar, c.satuan_besar, c.satuan_kecil, CAST(d.harga_satuan_netto as INT), CAST(d.jumlah_harga_netto as INT), CAST(d.harga_satuan as INT), CAST(d.jumlah_harga as INT), batch_log.jml_diterima, batch_log.kode_box, batch_log.kode_pcs, batch_log.no_batch, batch_log.jenis_satuan, batch_log.tgl_expired, batch_log.is_expired, batch_log.id_tc_batch_log, d.discount, d.ppn');
		$this->db->order_by('c.nama_brg ASC');
		return $this->db->get()->result();
	}

	function get_brg_po_by_id($params){
		$t_po_det = ($params['flag']=='medis')?'tc_penerimaan_barang_det':'tc_penerimaan_barang_nm_det';
		$t_brg = ($params['flag']=='medis')?'mt_barang':'mt_barang_nm';
		$this->db->select('a.*, b.*, c.no_batch, c.jml_diterima, c.kode_box, c.kode_pcs, c.no_batch, c.jenis_satuan, c.tgl_expired, c.is_expired, c.id_tc_batch_log');
		$this->db->from($t_po_det.' a');
		$this->db->join($t_brg.' b', 'b.kode_brg=a.kode_brg', 'left');
		$this->db->join('tc_penerimaan_brg_batch_log c', 'c.id_penerimaan_det=a.id_penerimaan_det', 'left');
		$this->db->where('a.id_penerimaan_det', $params['id_penerimaan_det']);
		return $this->db->get()->row();
	}

}
