<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tf_riwayat_tukar_faktur_model extends CI_Model {

	var $table = 'tc_hutang_supplier_inv';
	var $column = array('b.namasupuplier');
	var $select = 'a.id_tc_hutang_supplier_inv, b.namasupplier, no_terima_faktur, tgl_faktur, tgl_rencana_bayar, a.kodesupplier, a.flag_bayar, a.biaya_materai, a.created_by';
	var $order = array('tgl_faktur' => 'DESC');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _main_query(){

		$this->db->select($this->select);
		$this->db->select('SUM(CAST(a.total_harga as INT)) as total_harga');
		$this->db->from($this->table.' a');
		$this->db->join('mt_supplier b', 'b.kodesupplier=a.kodesupplier', 'left');

		$this->db->group_by($this->select);

		if(isset($_GET['checked_nama_perusahaan']) AND $_GET['checked_nama_perusahaan'] == 1){
			if( ( isset( $_GET['nama_perusahaan']) AND $_GET['nama_perusahaan'] != '' )  ){
				$this->db->like( 'b.namasupplier', trim($_GET['nama_perusahaan']) );
			}
		}

		if(isset($_GET['checked_no_ttf']) AND $_GET['checked_no_ttf'] == 1){
			if( ( isset( $_GET['no_ttf']) AND $_GET['no_ttf'] != '' )  ){
				$this->db->like( 'a.no_terima_faktur', trim($_GET['no_ttf']) );
			}
		}

		if(isset($_GET['checked_from_tgl']) AND $_GET['checked_from_tgl'] == 1){
			if (isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' || isset($_GET['to_tgl']) AND $_GET['to_tgl'] != '') {
				$this->db->where("convert(varchar,tgl_faktur,23) between '".$_GET['from_tgl']."' and '".$_GET['to_tgl']."'");					
			}
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
		$query = $this->db->get()->result();
		// print_r($this->db->last_query());die;
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

	public function get_by_id($id)
	{
		$this->_main_query();
		if(is_array($id)){
			$this->db->where_in('a.id_tc_hutang_supplier_inv',$id);
			$query = $this->db->get();
			return $query->result();
		}else{
			$this->db->where('a.id_tc_hutang_supplier_inv',$id);
			$query = $this->db->get();
			return $query->row();
		}
		
	}

	public function get_log_data($id_tc_hutang_supplier_inv){
		$this->db->from('tc_hutang_supplier_inv_det a');
		$this->db->join('tc_hutang_supplier_inv b', 'b.id_tc_hutang_supplier_inv=a.id_tc_hutang_supplier_inv','left');
		$this->db->join('mt_supplier c', 'c.kodesupplier=b.kodesupplier','left');
		$this->db->where('a.id_tc_hutang_supplier_inv', $id_tc_hutang_supplier_inv);
		$this->db->order_by('id_tc_hutang_supplier_inv_det', 'ASC');

		return $this->db->get()->result();
	}

	public function get_penerimaan_detail($id_penerimaan, $flag){
		$tc_po = ( $flag == 'medis' ) ? 'tc_po' : 'tc_po_nm' ;
		$tc_penerimaan_barang = ( $flag == 'medis' ) ? 'tc_penerimaan_barang' : 'tc_penerimaan_barang_nm' ;
		$tc_penerimaan_barang_detail = ( $flag == 'medis' ) ? 'tc_penerimaan_barang_detail' : 'tc_penerimaan_barang_nm_detail' ;
		$mt_barang = ( $flag == 'medis' ) ? 'mt_barang' : 'mt_barang_nm' ;

		$this->db->select('a.*, c.tgl_penerimaan, b.nama_brg, b.satuan_besar, d.jumlah_harga');
		$this->db->from(''.$tc_penerimaan_barang_detail.' a');
		$this->db->join(''.$tc_penerimaan_barang.' c', 'c.id_penerimaan=a.id_penerimaan','left');
		$this->db->join(''.$mt_barang.' b', 'b.kode_brg=a.kode_brg','left');
		$this->db->join(''.$tc_po.'_det d', 'd.id_tc_po_det=a.id_tc_po_det','left');
		$this->db->where('a.id_penerimaan', $id_penerimaan);
		$this->db->order_by('kode_detail_penerimaan_barang', 'ASC');
		$query = $this->db->get()->result();
		// print_r($this->db->last_query());die;
		return $query;
	}

	// public function get_billing_detail($kode_tc_trans_kasir){
	// 	$this->db->select('a.*, CAST((bill_rs + bill_dr1 + bill_dr2 + bill_dr3) as INT) as subtotal');
	// 	$this->db->from('tc_trans_pelayanan a');
	// 	$this->db->where('a.kode_tc_trans_kasir', $kode_tc_trans_kasir);
	// 	$this->db->order_by('tgl_transaksi', 'ASC');
	// 	$query = $this->db->get()->result();
	// 	print_r($this->db->last_query());die;
	// 	return $query;
	// }

	public function get_detail_pasien($kode_perusahaan){

		$this->db->select('a.*');
		$this->db->select('CAST(bill as INT) as bill_int, (CAST(tunai as INT) + CAST(debet as INT) + CAST(kredit as INT)) as beban_pasien, CAST(nk_perusahaan as INT) as nk_perusahaan_int');
		$this->db->from($this->table.' a');
		$this->db->join('mt_perusahaan b','b.kode_perusahaan=a.kode_perusahaan','left');
		$this->db->where('(a.nk_perusahaan > 0 AND a.kd_inv_persh_tx IS NULL AND a.kode_perusahaan NOT IN (120, 221, 0, 299))');
		$this->db->where('a.kode_perusahaan', $kode_perusahaan);		
		$this->db->where('a.seri_kuitansi', $_GET['jenis_pelayanan']);		
		$this->db->where("convert(varchar,tgl_jam,23) between '".$_GET['from_tgl']."' and '".$_GET['to_tgl']."'");	
		$query = $this->db->get()->result();
		return $query;
	}

}
