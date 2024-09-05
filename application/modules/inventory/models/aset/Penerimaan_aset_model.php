<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Penerimaan_aset_model extends CI_Model {

	var $table = 'tc_po_nm';
	var $column = array('c.namasupplier','a.no_po','a.diajukan_oleh');
	var $select = 'a.id_tc_po, a.no_po, a.tgl_po, a.ppn, a.total_sbl_ppn, a.total_stl_ppn, a.discount_harga, a.term_of_pay, a.diajukan_oleh, a.disetujui_oleh, c.namasupplier, d.nama_brg, b.jumlah_besar, b.kode_brg, b.harga_satuan, b.content, d.satuan_besar, e.nama_sub_golongan, e.kode_sub_gol';
	var $order = array('a.id_tc_po' => 'DESC');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _main_query(){
		$table = $this->table;
		$backmonth = date('m') - 3;

		$this->db->select($this->select);
		$this->db->from(''.$table.'_det b');
		$this->db->join(''.$table.' a','a.id_tc_po=b.id_tc_po', 'left');
		$this->db->join('mt_supplier c','c.kodesupplier=a.kodesupplier', 'left');
		$this->db->join('mt_barang_nm d','d.kode_brg=b.kode_brg', 'left');
		$this->db->join('mt_sub_golongan_nm e','e.kode_sub_gol=d.kode_sub_golongan', 'left');
		$this->db->where('(a.status_selesai IS NULL or a.status_selesai=0)');
		$this->db->where('d.kode_kategori','J');
		
		if( ( isset( $_GET['keyword']) AND $_GET['keyword'] != '' )  ){
			if( isset( $_GET['search_by']) AND $_GET['search_by'] == 'no_po' ){
				$this->db->like( $_GET['search_by'], $_GET['keyword'] );
			}
		}

		if( isset( $_GET['search_by']) AND $_GET['search_by'] == 'month' ){
			$this->db->where( 'MONTH(a.tgl_po)', $_GET['month'] );
			$this->db->where('YEAR(a.tgl_po)', date('Y'));
		}

		if( isset( $_GET['search_by']) AND $_GET['search_by'] == 'supplier' ){
			$this->db->where( 'a.kodesupplier', $_GET['kodesupplier'] );
		}

		if (isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' || isset($_GET['to_tgl']) AND $_GET['to_tgl'] != '') {
			$this->db->where("convert(varchar,a.tgl_po,23) between '".$_GET['from_tgl']."' and '".$_GET['to_tgl']."'");
		}else{
			$this->db->where('DATEDIFF(day,a.tgl_po,GETDATE()) < 120');
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
			$this->db->where_in('a.id_tc_po_det',$id);
			$query = $this->db->get();
			return $query->result();
		}else{
			$this->db->where('a.id_tc_po_det',$id);
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


	public function get_detail_table($id){
		$t_barang = 'mt_barang_nm';
		$table = $this->table;
		$t_penerimaan = 'tc_penerimaan_barang_nm';
		$t_po = 'tc_po_nm';
		$t_rekap_stok = 'mt_rekap_stok_nm';

		$this->db->select('z.no_po, a.id_tc_po, a.id_tc_po_det, a.kode_brg, a.content, c.nama_brg, c.satuan_besar, c.satuan_kecil, jumlah_besar, SUM(b.jumlah_kirim) as jumlah_kirim, SUM(b.jumlah_kirim_decimal) as jumlah_kirim_decimal, CAST(a.harga_satuan_netto as INT) as harga_satuan_netto, CAST(a.jumlah_harga_netto as INT) as total_harga_netto, CAST(a.harga_satuan as INT) as harga_satuan, CAST(a.jumlah_harga as INT) as total_harga, batch_log.jml_diterima, batch_log.kode_box, batch_log.kode_pcs, batch_log.no_batch, batch_log.jenis_satuan, batch_log.tgl_expired, batch_log.is_expired, batch_log.id_tc_batch_log, a.discount, a.ppn, a.jumlah_besar_acc');
		$this->db->from(''.$table.'_det a');
		$this->db->join($table.' z', 'z.id_tc_po=a.id_tc_po', 'left');
		$this->db->join($t_penerimaan.'_detail b', 'b.id_tc_po_det=a.id_tc_po_det', 'left');
		$this->db->join($t_penerimaan.' y', 'y.id_penerimaan=b.id_penerimaan', 'left');
		$this->db->join($t_barang.' c', 'c.kode_brg=a.kode_brg', 'left');
		$this->db->join('(SELECT * FROM tc_penerimaan_brg_batch_log WHERE reff_table='."'".$t_penerimaan."'".') as batch_log','batch_log.id_tc_po_det=a.id_tc_po_det','left');
		$id = (is_array($id)) ? implode(',', $id) : $id ;
		$this->db->where('a.id_tc_po IN ('.$id.')');
		$this->db->group_by('z.no_po, a.id_tc_po, a.id_tc_po_det, a.kode_brg, a.content, c.nama_brg, jumlah_besar, c.satuan_besar, c.satuan_kecil, CAST(a.harga_satuan_netto as INT), CAST(a.jumlah_harga_netto as INT), CAST(a.harga_satuan as INT), CAST(a.jumlah_harga as INT), batch_log.jml_diterima, batch_log.kode_box, batch_log.kode_pcs, batch_log.no_batch, batch_log.jenis_satuan, batch_log.tgl_expired, batch_log.is_expired, batch_log.id_tc_batch_log, a.discount, a.ppn, a.jumlah_besar_acc');
		$this->db->order_by('c.nama_brg ASC');
		// echo '<pre>';print_r($this->db->last_query());die;
		return $this->db->get()->result();
	}

	function get_brg_po_by_id($params){
		$t_po_det = ($params['flag']=='medis')?'tc_po_det':'tc_po_nm_det';
		$t_brg = ($params['flag']=='medis')?'mt_barang':'mt_barang_nm';
		$this->db->select('a.*, b.*, c.no_batch, c.jml_diterima, c.kode_box, c.kode_pcs, c.no_batch, c.jenis_satuan, c.tgl_expired, c.is_expired, c.id_tc_batch_log');
		$this->db->from($t_po_det.' a');
		$this->db->join($t_brg.' b', 'b.kode_brg=a.kode_brg', 'left');
		$this->db->join('tc_penerimaan_brg_batch_log c', 'c.id_tc_po_det=a.id_tc_po_det', 'left');
		$this->db->where('a.id_tc_po_det', $params['id_tc_po_det']);
		return $this->db->get()->row();
	}

	function get_sisa_penerimaan($table, $join, $id_tc_po){
		$query = "select b.id_tc_po_det, b.jumlah_besar_acc, SUM(jumlah_kirim) as total_kirim 
		from ".$table." b
		left join ".$join." a on b.id_tc_po_det=a.id_tc_po_det
		where b.id_tc_po=".$id_tc_po."
		GROUP BY b.id_tc_po_det, b.jumlah_besar_acc
		HAVING (b.jumlah_besar_acc > SUM(jumlah_kirim) or SUM(jumlah_kirim) is null)";
		
		return $this->db->query($query)->num_rows();
	}

	public function get_penerimaan_brg($flag, $id){
		$t_barang = ($flag=='non_medis')?'mt_barang_nm':'mt_barang';
		$table = ($flag=='non_medis')?$this->table_nm:$this->table;
		$t_penerimaan = ($flag=='non_medis')?'tc_penerimaan_barang_nm':'tc_penerimaan_barang';
		$t_po = ($flag=='non_medis')?'tc_po_nm':'tc_po';
		$t_rekap_stok = ($flag=='non_medis')?'mt_rekap_stok_nm':'mt_rekap_stok';

		$this->db->select('b.kode_detail_penerimaan_barang, y.kode_penerimaan, y.tgl_penerimaan, z.no_po, a.id_tc_po, a.id_tc_po_det, a.kode_brg, a.content, c.nama_brg, c.satuan_besar, c.satuan_kecil, jumlah_besar, SUM(b.jumlah_kirim) as jumlah_kirim, CAST(a.harga_satuan_netto as INT) as harga_satuan_netto, CAST(a.jumlah_harga_netto as INT) as total_harga_netto, CAST(a.harga_satuan as INT) as harga_satuan, CAST(a.jumlah_harga as INT) as total_harga, batch_log.jml_diterima, batch_log.kode_box, batch_log.kode_pcs, batch_log.no_batch, batch_log.jenis_satuan, batch_log.tgl_expired, batch_log.is_expired, batch_log.id_tc_batch_log, a.discount,a.discount_rp, z.ppn, p.namasupplier, p.alamat, p.telpon1, z.total_sbl_ppn, z.total_stl_ppn, z.discount_harga, y.petugas, y.dikirim, b.jumlah_kirim_decimal, b.jumlah_pesan_decimal');
		$this->db->from($t_penerimaan.'_detail b');
		$this->db->join(''.$table.'_det a', 'b.id_tc_po_det=a.id_tc_po_det' ,'left');
		$this->db->join($table.' z', 'z.id_tc_po=a.id_tc_po', 'left');
		$this->db->join($t_penerimaan.' y', 'y.id_penerimaan=b.id_penerimaan', 'left');
		$this->db->join('mt_supplier p', 'p.kodesupplier=y.kodesupplier', 'left');
		$this->db->join($t_barang.' c', 'c.kode_brg=a.kode_brg', 'left');
		$this->db->join('(SELECT * FROM tc_penerimaan_brg_batch_log WHERE reff_table='."'".$t_penerimaan."'".') as batch_log','batch_log.id_tc_po_det=a.id_tc_po_det','left');
		$id = (is_array($id)) ? implode(',', $id) : $id ;
		$this->db->where('b.id_penerimaan IN ('.$id.')');
		$this->db->group_by('b.kode_detail_penerimaan_barang, y.kode_penerimaan, y.tgl_penerimaan, z.no_po, a.id_tc_po, a.id_tc_po_det, a.kode_brg, a.content, c.nama_brg, jumlah_besar, c.satuan_besar, c.satuan_kecil, CAST(a.harga_satuan_netto as INT), CAST(a.jumlah_harga_netto as INT), CAST(a.harga_satuan as INT), CAST(a.jumlah_harga as INT), batch_log.jml_diterima, batch_log.kode_box, batch_log.kode_pcs, batch_log.no_batch, batch_log.jenis_satuan, batch_log.tgl_expired, batch_log.is_expired, batch_log.id_tc_batch_log, a.discount, z.ppn, p.namasupplier, p.alamat, p.telpon1, z.total_sbl_ppn, z.total_stl_ppn, z.discount_harga, a.discount_rp, y.petugas, y.dikirim, b.jumlah_kirim_decimal, b.jumlah_pesan_decimal');
		$this->db->order_by('c.nama_brg ASC');
		return $this->db->get()->result();
	}


}
