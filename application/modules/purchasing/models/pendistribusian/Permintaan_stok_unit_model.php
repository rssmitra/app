<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Permintaan_stok_unit_model extends CI_Model {

	var $table_nm = 'tc_permintaan_inst_nm';
	var $table = 'tc_permintaan_inst';
	var $column = array('a.nomor_permintaan', 'b.nama_bagian');
	var $select = 'a.id_tc_permintaan_inst, a.nomor_permintaan, a.tgl_permintaan, a.kode_bagian_minta, a.kode_bagian_kirim, a.status_batal, a.tgl_input, a.nomor_pengiriman, a.tgl_pengiriman, a.yg_serah, a.yg_terima, a.tgl_input_terima, a.id_dd_user_terima, a.keterangan_kirim, a.status_selesai, a.jenis_permintaan, tgl_acc, acc_by, status_acc, send_to_verify';
	var $order = array('a.id_tc_permintaan_inst' => 'DESC');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _main_query(){
		$table = ($_GET['flag']=='non_medis')?$this->table_nm:$this->table;
		$this->db->select($this->select);
		$this->db->select('CAST(a.catatan as NVARCHAR(1000)) as catatan');
		$this->db->select('b.nama_bagian as bagian_minta, c.fullname as nama_user_input');
		$this->db->from(''.$table.' a');
		$this->db->join('mt_bagian b','b.kode_bagian=a.kode_bagian_minta', 'left');
		$this->db->join('tmp_user c','c.user_id=a.id_dd_user', 'left');
		
	}

	private function _get_datatables_query()
	{
		
		$this->_main_query();

		if (isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' || isset($_GET['to_tgl']) AND $_GET['to_tgl'] != '') {
			$this->db->where("convert(varchar,a.tgl_permintaan,23) between '".$_GET['from_tgl']."' and '".$_GET['to_tgl']."'");
		}else{
			$this->db->where('YEAR(a.tgl_permintaan)='.date('Y').'');
		}

		if( ( isset( $_GET['kode_bagian']) AND $_GET['kode_bagian'] != '' )  ){
			$this->db->where('kode_bagian_minta', $_GET['kode_bagian']);
		}else{
			if (strpos(strtolower($this->session->userdata('user')->role), 'sysadmin') === false) {
				$this->db->where('a.id_dd_user', $this->session->userdata('user')->user_id);
			}
		}

		// session user bagian
		

		$this->db->where('version', 1);

		$this->db->group_by('CAST(a.catatan as NVARCHAR(1000))');
		$this->db->group_by('b.nama_bagian, c.fullname');
		$this->db->group_by($this->select);

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

	public function get_item_detail($id, $flag, $type=''){
		$mt_barang = ($flag=='non_medis')?'mt_barang_nm':'mt_barang';
		$table = ($flag=='non_medis')?$this->table_nm:$this->table;
		$join_3 = ($flag=='non_medis')?'mt_depo_stok_nm':'mt_depo_stok';

		if($type == 'tc_permintaan_inst'){
			$this->db->select('a.id_tc_permintaan_inst_det, e.kode_bagian_minta, jumlah_permintaan, jumlah_penerimaan , a.kode_brg,, c.nama_brg, c.content as rasio, c.satuan_kecil, c.satuan_besar, e.nomor_permintaan, e.jenis_permintaan, e.tgl_permintaan, f.jml_sat_kcl as jumlah_stok_sebelumnya, g.nama_bagian,CAST(c.harga_beli as INT) as harga_beli, CAST(e.catatan as NVARCHAR(1000)) as catatan, e.tgl_acc, e.acc_by, e.status_acc, e.no_acc, e.yg_terima, e.tgl_pengiriman, e.yg_serah, e.nomor_pengiriman, e.tgl_input_terima, acc_note, status_verif, keterangan_verif, a.is_bhp, a.keterangan_permintaan');
			$this->db->from(''.$table.'_det a');
			$this->db->join($mt_barang.' c', 'c.kode_brg=a.kode_brg', 'left');
			$this->db->join($table.' e', 'e.id_tc_permintaan_inst=a.id_tc_permintaan_inst', 'left');
			$this->db->join('mt_bagian g', 'g.kode_bagian=e.kode_bagian_minta', 'left');
			$this->db->join($join_3.' f', '(f.kode_brg=a.kode_brg AND f.kode_bagian=e.kode_bagian_minta)', 'left');
			$this->db->where('a.id_tc_permintaan_inst_det', $id);
			$query = $this->db->get()->row();
		}else{
			$this->db->select('kode_brg, nama_brg, qty as jumlah_permintaan, satuan, cast(harga as int) as harga, flag, nama_bagian, qtyBefore as jumlah_stok_sebelumnya, reff_kode, retur_type, is_bhp, is_restock, tc_permintaan_inst_cart_log.kode_bagian, keterangan as keterangan_permintaan, id as id_tc_permintaan_inst_det');
			$this->db->from('tc_permintaan_inst_cart_log');
			$this->db->join('mt_bagian', 'mt_bagian.kode_bagian=tc_permintaan_inst_cart_log.kode_bagian');
			$this->db->where('user_id_session', $this->session->userdata('user')->user_id);
			$this->db->where('flag_form', 'permintaan_stok_unit');
			$this->db->where('id', $id);
			$query = $this->db->get()->row();
		}
		

		// print_r($this->db->last_query());die;
		return $query;
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

	public function delete_cart_log($table, $id)
	{
		$this->db->where_in('id', $id)->delete($table);
		return true;
	}
	

	public function get_brg_permintaan($flag, $id){
		$mt_barang = ($flag=='non_medis')?'mt_barang_nm':'mt_barang';
		$table = ($flag=='non_medis')?$this->table_nm:$this->table;
		$join_3 = ($flag=='non_medis')?'mt_depo_stok_nm':'mt_depo_stok';

		$this->db->select('a.id_tc_permintaan_inst_det, e.kode_bagian_minta, jumlah_permintaan, jumlah_penerimaan , a.kode_brg,, c.nama_brg, c.content as rasio, c.satuan_kecil, c.satuan_besar, e.nomor_permintaan, e.jenis_permintaan, e.tgl_permintaan, f.jml_sat_kcl as jumlah_stok_sebelumnya, g.nama_bagian,CAST(c.harga_beli as INT) as harga_beli, CAST(e.catatan as NVARCHAR(1000)) as catatan, e.tgl_acc, e.acc_by, e.status_acc, e.no_acc, e.yg_terima, e.tgl_pengiriman, e.yg_serah, e.nomor_pengiriman, e.tgl_input_terima, acc_note, status_verif, keterangan_verif, a.is_bhp, a.keterangan_permintaan, a.jml_acc_atasan, h.nama_brg as revisi_nama_brg, a.rev_kode_brg, a.rev_qty, a.jumlah_kirim, a.petugas_kirim, a.jumlah_penerimaan, a.petugas_terima');
		$this->db->from(''.$table.'_det a');
		$this->db->join($mt_barang.' c', 'c.kode_brg=a.kode_brg', 'left');
		$this->db->join($mt_barang.' h', 'h.kode_brg=a.rev_kode_brg', 'left');
		$this->db->join($table.' e', 'e.id_tc_permintaan_inst=a.id_tc_permintaan_inst', 'left');
		$this->db->join('mt_bagian g', 'g.kode_bagian=e.kode_bagian_minta', 'left');
		$this->db->join($join_3.' f', '(f.kode_brg=a.kode_brg AND f.kode_bagian=e.kode_bagian_minta)', 'left');
		$id = (is_array($id)) ? implode(',', $id) : $id ;
		$this->db->where('a.id_tc_permintaan_inst IN ('.$id.')');
		$this->db->group_by('a.id_tc_permintaan_inst_det, e.kode_bagian_minta, jumlah_permintaan, jumlah_penerimaan , a.kode_brg,, c.nama_brg, c.content, c.satuan_kecil, c.satuan_besar, e.nomor_permintaan, e.jenis_permintaan, e.tgl_permintaan, f.jml_sat_kcl, g.nama_bagian, CAST(c.harga_beli as INT), CAST(e.catatan as NVARCHAR(1000)), e.no_acc, e.tgl_acc, e.acc_by, e.status_acc, e.yg_terima, e.tgl_pengiriman, e.yg_serah, e.nomor_pengiriman, e.tgl_input_terima, acc_note, status_verif, keterangan_verif, a.is_bhp, a.keterangan_permintaan, a.jml_acc_atasan, h.nama_brg, a.rev_kode_brg, a.rev_qty, a.jumlah_kirim, a.petugas_kirim, a.petugas_terima, a.jumlah_penerimaan');
		$this->db->order_by('c.nama_brg ASC');
		$query = $this->db->get()->result();
		// print_r($this->db->last_query());die;
		return $query;
	}

	public function get_detail_brg_permintaan_multiple($flag, $id){

		$result = $this->get_brg_permintaan($flag, $id);
		$getData = [];
		foreach($result as $row){
			$getData[$row->nomor_permintaan][] = array(
				'nomor_permintaan' => $row->nomor_permintaan,
				'tgl_permintaan' => $row->tgl_permintaan,
				'jenis_permintaan' => $row->jenis_permintaan,
				'nama_bagian' => $row->nama_bagian,
				'barang' => $row,
			);
		}
		// echo '<pre>';print_r($getData);die;
		return $getData;
		
	}

	public function get_cart_data(){

		if(isset($_GET['id']) && $_GET['id'] != ''){
			$mt_barang = ($_GET['flag']=='non_medis')?'mt_barang_nm':'mt_barang';
			$table = ($_GET['flag']=='non_medis')?$this->table_nm:$this->table;
			$join_3 = ($_GET['flag']=='non_medis')?'mt_depo_stok_nm':'mt_depo_stok';

			$this->db->select('a.id_tc_permintaan_inst_det, e.kode_bagian_minta as kode_bagian, jumlah_permintaan as qty, jumlah_penerimaan , a.kode_brg, c.nama_brg, c.content as rasio, c.satuan_kecil as satuan, c.satuan_besar, e.nomor_permintaan, e.jenis_permintaan, e.tgl_permintaan, f.jml_sat_kcl as jumlah_stok_sebelumnya, g.nama_bagian, CAST(c.harga_beli as INT) as harga, CAST(e.catatan as NVARCHAR(1000)) as catatan, e.tgl_acc, e.acc_by, e.status_acc, e.no_acc, e.yg_terima, e.tgl_pengiriman, e.yg_serah, e.nomor_pengiriman, e.tgl_input_terima, a.is_bhp, a.keterangan_permintaan, a.tgl_kirim, a.jumlah_kirim, a.petugas_kirim, '."'tc_permintaan_inst'".' as type_tbl');
			$this->db->from(''.$table.'_det a');
			$this->db->join($mt_barang.' c', 'c.kode_brg=a.kode_brg', 'left');
			$this->db->join($table.' e', 'e.id_tc_permintaan_inst=a.id_tc_permintaan_inst', 'left');
			$this->db->join('mt_bagian g', 'g.kode_bagian=e.kode_bagian_minta', 'left');
			$this->db->join($join_3.' f', '(f.kode_brg=a.kode_brg AND f.kode_bagian=e.kode_bagian_minta)', 'left');
			$id = (is_array($_GET['id'])) ? implode(',', $_GET['id']) : $_GET['id'] ;
			$this->db->where('a.id_tc_permintaan_inst IN ('.$_GET['id'].')');
			$this->db->group_by('a.id_tc_permintaan_inst_det, e.kode_bagian_minta, jumlah_permintaan, jumlah_penerimaan , a.kode_brg,, c.nama_brg, c.content, c.satuan_kecil, c.satuan_besar, e.nomor_permintaan, e.jenis_permintaan, e.tgl_permintaan, f.jml_sat_kcl, g.nama_bagian, CAST(c.harga_beli as INT), CAST(e.catatan as NVARCHAR(1000)), e.no_acc, e.tgl_acc, e.acc_by, e.status_acc, e.yg_terima, e.tgl_pengiriman, e.yg_serah, e.nomor_pengiriman, e.tgl_input_terima, a.is_bhp, a.keterangan_permintaan, a.tgl_kirim, a.jumlah_kirim, a.petugas_kirim');
			$this->db->order_by('c.nama_brg ASC');
			$cart_data = $this->db->get()->result();
			return $cart_data;
		}else{
			$this->db->select('kode_brg, nama_brg, qty, satuan, cast(harga as int) as harga, flag, nama_bagian, qtyBefore as jumlah_stok_sebelumnya, reff_kode, retur_type, is_bhp, is_restock, tc_permintaan_inst_cart_log.kode_bagian, keterangan as keterangan_permintaan, id as id_tc_permintaan_inst_det, '."'cart_log'".' as type_tbl');
			$this->db->from('tc_permintaan_inst_cart_log');
			$this->db->join('mt_bagian', 'mt_bagian.kode_bagian=tc_permintaan_inst_cart_log.kode_bagian');
			$this->db->where('user_id_session', $this->session->userdata('user')->user_id);
			$this->db->where('flag_form', 'permintaan_stok_unit');
			$this->db->order_by('tc_permintaan_inst_cart_log.id', 'ASC');
			// $this->db->group_by('tc_permintaan_inst_cart_log.id, kode_brg, nama_brg, satuan, harga, flag, nama_bagian, qtyBefore, reff_kode, retur_type, is_bhp, is_restock, tc_permintaan_inst_cart_log.kode_bagian, keterangan');
			return $this->db->get()->result();
		}

	}


}
