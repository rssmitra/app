<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Po_revisi_model extends CI_Model {

	var $table_nm = 'tc_po_nm';
	var $table = 'tc_po';
	var $column = array('a.no_po','c.namasupplier');
	var $select = 'a.id_tc_po, a.no_po, a.tgl_po, a.ppn, a.total_sbl_ppn, a.total_stl_ppn, a.discount_harga, a.term_of_pay, b.username, a.diajukan_oleh, a.disetujui_oleh, c.namasupplier, a.jenis_po';
	var $order = array('a.id_tc_po' => 'DESC');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _main_query(){
		$table = ($_GET['flag']=='non_medis')?$this->table_nm:$this->table;
		$this->db->select($this->select);
		$this->db->from(''.$table.' a');
		$this->db->join('dd_user b','b.id_dd_user=a.user_id', 'left');
		$this->db->join('mt_supplier c','c.kodesupplier=a.kodesupplier', 'left');
		$this->db->where('YEAR(a.tgl_po)', date('Y'));
	}

	private function _get_datatables_query()
	{
		
		$this->_main_query();

		if( ( isset( $_GET['keyword']) AND $_GET['keyword'] != '' )  ){
			if( isset( $_GET['search_by']) AND $_GET['search_by'] == 'no_po' ){
				$this->db->like( $_GET['search_by'], $_GET['keyword'] );
			}
		}

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
		//echo '<pre>';print_r($this->db->last_query());die;
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
			$this->db->where_in('a.id_tc_permohonan',$id);
			$query = $this->db->get();
			return $query->result();
		}else{
			$this->db->where('a.id_tc_permohonan',$id);
			$query = $this->db->get();
			return $query->row();
		}
		
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

	public function delete_by_id($id)
	{
		/*delete stok opname*/
		$this->db->where_in('id_tc_permohonan', $id)->delete('tc_stok_opname');
		$this->db->where_in('id_tc_permohonan', $id)->delete('tc_stok_opname_nm');
		$this->db->where_in('id_tc_permohonan', $id)->delete('tc_permohonan_nm');
		return true;
	}

	public function get_po($flag, $id){
		$mt_barang = ($flag=='non_medis')?'mt_barang_nm':'mt_barang';
		$table = ($flag=='non_medis')?'tc_po_nm_det':'tc_po_det';
		$join_po = ($flag=='non_medis')?'tc_po_nm':'tc_po';
		$join = ($flag=='non_medis')?'mt_rekap_stok_nm':'mt_rekap_stok';

		$this->db->select('a.id_tc_po_det, a.id_tc_po, a.id_tc_permohonan_det, a.id_tc_permohonan, a.kode_brg, a.jumlah_besar, a.jumlah_besar_acc, a.content, a.harga_satuan as harga_satuan, a.harga_satuan_netto as harga_satuan_netto, a.jumlah_harga_netto as jumlah_harga_netto,a.jumlah_harga as jumlah_harga, a.discount, a.discount_rp as discount_rp, c.nama_brg, c.satuan_besar, b.no_po, b.tgl_po, b.ppn as ppn, b.total_sbl_ppn as total_sbl_ppn, b.total_stl_ppn as total_stl_ppn, b.discount_harga as total_diskon, b.term_of_pay, b.diajukan_oleh, b.tgl_kirim as estimasi_kirim, e.namasupplier, e.alamat, e.kota, e.telpon1, b.no_urut_periodik');
		$this->db->from(''.$table.' a');
		$this->db->join($join_po.' b', 'b.id_tc_po=a.id_tc_po', 'left');
		$this->db->join($mt_barang.' c', 'c.kode_brg=a.kode_brg', 'left');
		$this->db->join($join.' d', 'd.kode_brg=a.kode_brg', 'left');
		$this->db->join('mt_supplier e', 'e.kodesupplier=b.kodesupplier', 'left');

		$id = (is_array($id)) ? implode(',', $id) : $id ;
		$this->db->where('a.id_tc_po IN ('.$id.')');
		$this->db->order_by('c.nama_brg ASC');
		return $this->db->get()->result();
	}
	
	public function get_brg_po($flag, $id){
		$mt_barang = ($flag=='non_medis')?'mt_barang_nm':'mt_barang';
		$table = ($flag=='non_medis')?$this->table_nm:$this->table;
		$join = ($flag=='non_medis')?'tc_po_nm_det':'tc_po_det';
		$join_2 = ($flag=='non_medis')?'tc_po_nm':'tc_po';
		$join_3 = ($flag=='non_medis')?'mt_rekap_stok_nm':'mt_rekap_stok';

		$this->db->select('a.*, c.nama_brg, b.no_po, b.tgl_po, c.satuan_besar, CAST(a.harga_satuan_netto as INT) as harga_satuan_po, CAST(a.jumlah_harga_netto as int) as total_harga, a.content as rasio_po, CAST(a.discount AS INT) as discount, CAST(a.discount_rp as INT) as discount_rp, a.ppn, e.tgl_kirim, e.krs, e.diajukan_oleh, e.disetujui_oleh, e.term_of_pay');
		$this->db->from($table.'_det a');
		$this->db->join($table.' b', 'b.id_tc_po=a.id_tc_po', 'left');
		$this->db->join($mt_barang.' c', 'c.kode_brg=a.kode_brg', 'left');
		$this->db->join($join_2.' e', 'e.id_tc_po=a.id_tc_po', 'left');
		$this->db->join($join_3.' f', 'f.kode_brg=a.kode_brg', 'left');
		$id = (is_array($id)) ? implode(',', $id) : $id ;
		$this->db->where('a.id_tc_po IN ('.$id.')');
		$this->db->order_by('c.nama_brg ASC');
		return $this->db->get()->result();
	}

	public function rollback_po($flag, $id)
	{
		$tc_po = ($flag=='non_medis')?'tc_po_nm':'tc_po';
		$tc_permohonan = ($flag=='non_medis')?'tc_permohonan_nm':'tc_permohonan';
		// masih terindikasi bugs
		$dt_po = $this->db->get_where($tc_po.'_det', array('id_tc_po' => $id) )->result();

		foreach ($dt_po as $key => $value) {
			# code...
			$this->db->where( array('id_tc_permohonan_det' => $value->id_tc_permohonan_det) )->update($tc_permohonan.'_det', array('status_po' => NULL) );
			$id_tc_permohonan_det[] = $value->id_tc_permohonan_det;
		}
		
		// get permohonan
		$dt_permohonan = $this->db->where_in('id_tc_permohonan_det', $id_tc_permohonan_det)->get($tc_permohonan.'_det')->result();
		foreach ($dt_permohonan as $key => $value) {
			$id_tc_permohonan[] = $value->id_tc_permohonan;
		}

		$this->db->where_in('id_tc_permohonan', $id_tc_permohonan)->update($tc_permohonan, array('flag_proses' => 2) );

		/*delete po detail*/
		$this->db->where('id_tc_po', $id)->delete($tc_po);
		$this->db->where('id_tc_po', $id)->delete($tc_po.'_det');

		return true;
	}

	public function takeOutBrg($params)
	{
		$tc_po = ($params['flag']=='non_medis')?'tc_po_nm':'tc_po';
		$tc_permohonan = ($params['flag']=='non_medis')?'tc_permohonan_nm':'tc_permohonan';
		$toArray = implode(',',$params['kode_brg']);
		
		$this->db->where('id_tc_permohonan_det IN (SELECT id_tc_permohonan_det FROM '.$tc_po.'_det WHERE id_tc_po='.$params['id_tc_po'].' AND kode_brg IN ('."'".$toArray."'".'))')->update($tc_permohonan.'_det', array('status_po' => NULL) );

		/*delete brg po*/
		$this->db->where('id_tc_po', $params['id_tc_po'])->where_in('kode_brg', $toArray)->delete($tc_po.'_det');
		
		

		return true;
	}

	public function get_detail_brg_po_multiple($flag, $id){

		$mt_barang = ($flag=='non_medis')?'mt_barang_nm':'mt_barang';
		$table = ($flag=='non_medis')?$this->table_nm:$this->table;

		$this->db->from(''.$table.'_det');
		$this->db->join($table, ''.$table.'.id_tc_po='.$table.'_det.id_tc_po', 'left');
		$this->db->join($mt_barang, ''.$mt_barang.'.kode_brg='.$table.'_det.kode_brg', 'left');
		$this->db->join('mt_supplier', 'mt_supplier.kodesupplier='.$table.'.kodesupplier', 'left');
		$this->db->order_by(''.$table.'.tgl_po', 'DESC');
		$this->db->where_in(''.$table.'_det.id_tc_po', $id);
		$result = $this->db->get()->result();
		$getData = [];
		foreach($result as $row){
			$getData[$row->id_tc_po][] = array(
				'id_tc_po' => $row->id_tc_po,
				'no_po' => $row->no_po,
				'namasupplier' => $row->namasupplier,
				'tgl_po' => $row->tgl_po,
				'barang' => $row,
			);
		}
		// echo '<pre>';print_r($getData);die;
		return $getData;
		
	}


}
