<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pemakaian_bhp_model extends CI_Model {

	var $table_nm = 'tc_permintaan_inst_nm';
	var $table = 'tc_permintaan_inst';
	var $column = array('a.nomor_permintaan');
	var $select = 'a.id_tc_permintaan_inst, a.nomor_permintaan, a.tgl_permintaan, a.kode_bagian_minta, a.kode_bagian_kirim, a.status_batal, a.tgl_input, a.id_dd_user, a.nomor_pengiriman, a.tgl_pengiriman, a.yg_serah, a.yg_terima, a.tgl_input_terima, a.id_dd_user_terima, a.keterangan_kirim, a.status_selesai, c.username, a.jenis_permintaan, a.catatan';
	var $order = array('a.tgl_permintaan' => 'DESC');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _main_query(){
		$table = ($_GET['flag']=='non_medis')?$this->table_nm:$this->table;
		$this->db->select($this->select);
		$this->db->select('b.nama_bagian as bagian_minta');
		$this->db->from(''.$table.' a');
		$this->db->join('mt_bagian b','b.kode_bagian=a.kode_bagian_minta', 'left');
		$this->db->join('dd_user c','c.id_dd_user=a.id_dd_user', 'left');
	}

	private function _get_datatables_query()
	{
		
		$this->_main_query();

		if (isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' || isset($_GET['to_tgl']) AND $_GET['to_tgl'] != '') {
			$this->db->where("convert(varchar,a.tgl_permintaan,23) between '".$_GET['from_tgl']."' and '".$_GET['to_tgl']."'");
		}else{
			$this->db->where('YEAR(a.tgl_permintaan)='.date('Y').'');
			if( $_GET['flag'] == 'medis' ){
				$this->db->where('YEAR(a.tgl_permintaan)', date('Y'));
			}
		}

		if( ( isset( $_GET['kode_bagian']) AND $_GET['kode_bagian'] != '' )  ){
			$this->db->where('kode_bagian_minta', $_GET['kode_bagian']);
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
		$this->_get_datatables_query();
		return $this->db->count_all_results();
	}

	public function get_by_id($id)
	{
		$table = ($_GET['flag']=='non_medis')?$this->table_nm:$this->table;
		$this->_main_query();
		if(is_array($id)){
			$this->db->where_in('a.id_tc_permintaan_inst',$id);
			$query = $this->db->get();
			return $query->result();
		}else{
			$this->db->where('a.id_tc_permintaan_inst',$id);
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
		$this->db->where_in('id_tc_permintaan_inst', $id)->delete($table);
		$this->db->where_in('id_tc_permintaan_inst', $id)->delete($table.'_det');
		return true;
	}

	public function delete_brg_permintaan($table, $id)
	{
		$this->db->where_in('id_tc_permintaan_inst_det', $id)->delete($table);
		return true;
	}

	public function get_brg_permintaan($flag, $id){
		$mt_barang = ($flag=='non_medis')?'mt_barang_nm':'mt_barang';
		$table = ($flag=='non_medis')?$this->table_nm:$this->table;
		$join = ($flag=='non_medis')?'tc_permintaan_inst_nm_det':'tc_permintaan_inst_det';
		$join_2 = ($flag=='non_medis')?'tc_permintaan_inst_nm':'tc_permintaan_inst';
		$join_3 = ($flag=='non_medis')?'mt_rekap_stok_nm':'mt_rekap_stok';

		$this->db->select('a.*, c.nama_brg, f.harga_beli, c.content as rasio, c.satuan_kecil, c.satuan_besar, e.nomor_permintaan, e.jenis_permintaan, f.jml_sat_kcl as jumlah_stok_sebelumnya');
		$this->db->from(''.$table.'_det a');
		$this->db->join($mt_barang.' c', 'c.kode_brg=a.kode_brg', 'left');
		$this->db->join($join_2.' e', 'e.id_tc_permintaan_inst=a.id_tc_permintaan_inst', 'left');
		$this->db->join($join_3.' f', 'f.kode_brg=a.kode_brg', 'left');
		$id = (is_array($id)) ? implode(',', $id) : $id ;
		$this->db->where('a.id_tc_permintaan_inst IN ('.$id.')');
		$this->db->order_by('c.nama_brg ASC');
		return $this->db->get()->result();
	}

	public function get_penerimaan_brg($flag, $id){
		$t_barang = ($flag=='non_medis')?'mt_barang_nm':'mt_barang';
		$t_penerimaan = ($flag=='non_medis')?'tc_penerimaan_barang_nm':'tc_penerimaan_barang';
		$t_rekap_stok = ($flag=='non_medis')?'mt_rekap_stok_nm':'mt_rekap_stok';
		$select = 'c.kode_brg, c.nama_brg, c.satuan_besar, c.satuan_kecil, d.jml_sat_kcl, d.stok_minimum, batch_log.jml_diterima, batch_log.kode_box, batch_log.kode_pcs, batch_log.no_batch, batch_log.jenis_satuan, batch_log.tgl_expired, batch_log.is_expired, batch_log.id_tc_batch_log';
		$this->db->select($select);
		$this->db->select('CAST(c.path_image as NVARCHAR(255)) as path_image');
		$this->db->select('CAST(c.spesifikasi as NVARCHAR(1000)) as spesifikasi');
		$this->db->select('CAST(d.harga_beli as INT ) as harga_beli');
		$this->db->from($t_penerimaan.'_detail b');
		$this->db->join($t_penerimaan.' y', 'y.id_penerimaan=b.id_penerimaan', 'left');
		$this->db->join($t_barang.' c', 'c.kode_brg=b.kode_brg', 'left');
		$this->db->join($t_rekap_stok.' d', 'd.kode_brg=b.kode_brg', 'left');
		$this->db->join('(SELECT * FROM tc_penerimaan_brg_batch_log WHERE reff_table='."'".$t_penerimaan."'".') as batch_log','batch_log.id_tc_po_det=b.id_tc_po_det','left');
		$id = (is_array($id)) ? implode(',', $id) : $id ;
		$this->db->where('b.id_penerimaan IN ('.$id.')');
		$this->db->group_by($select.',CAST(c.path_image as NVARCHAR(255)),CAST(c.spesifikasi as NVARCHAR(1000)), CAST(d.harga_beli as INT)');
		$this->db->order_by('c.nama_brg ASC');
		return $this->db->get()->result();
	}

	public function checkBarcode(){
		
		$flag = $_POST['flag'];
		$kode_brg = $_POST['kode_brg'];
		$mt_barang = ($flag=='non_medis')?'mt_barang_nm':'mt_barang';
		$mt_rekap_stok = ($flag=='non_medis')?'mt_rekap_stok_nm':'mt_rekap_stok';
		// check barcode
		$this->db->from( $mt_barang.' a' );
		$this->db->join($mt_rekap_stok.' b', 'b.kode_brg=a.kode_brg', 'left');
		if( !empty($_POST['kode_brg']) ){
			$this->db->where('a.kode_brg', $_POST['kode_brg']);
		}else{
			$this->db->where('a.kode_brg IN ( SELECT kode_brg FROM tc_penerimaan_brg_batch_log WHERE flag='."'".$flag."'".' AND kode_box = '."'".$_POST['barcode']."'".' OR kode_pcs = '."'".$_POST['barcode']."'".' )');
		}
		$result = $this->db->get()->result();
		// print_r($this->db->last_query());die;
		return $result;
	}

	public function get_cart_data($flag_form){
		$this->db->select('kode_brg, nama_brg, SUM(qty) as qty, satuan, cast(harga as int) as harga, flag, nama_bagian, qtyBefore, reff_kode, satuan, tc_pemakaian_bhp_cart_log.kode_bagian');
		$this->db->from('tc_pemakaian_bhp_cart_log');
		$this->db->join('mt_bagian', 'mt_bagian.kode_bagian=tc_pemakaian_bhp_cart_log.kode_bagian');
		$this->db->where('user_id_session', $this->session->userdata('user')->user_id);
		$this->db->where('flag_form', $flag_form);
		$this->db->group_by('kode_brg, tc_pemakaian_bhp_cart_log.kode_bagian, nama_brg, satuan, harga, flag, nama_bagian, qtyBefore, reff_kode');
		return $this->db->get()->result();

	}
	


}
