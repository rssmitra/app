<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tf_tukar_faktur_model extends CI_Model {

	var $table_nm = 'tc_penerimaan_barang_nm';
	var $table = 'tc_penerimaan_barang';
	var $column = array('a.kode_penerimaan','a.no_po');
	var $select = 'a.kode_penerimaan, a.no_po, a.id_tc_po, a.tgl_penerimaan, a.keterangan, a.no_faktur, a.dikirim, a.id_penerimaan, c.namasupplier, c.alamat, a.status_tukar_faktur, a.petugas, c.kodesupplier';
	var $order = array('a.id_penerimaan' => 'DESC');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _main_query(){

		$table = ($_GET['flag']=='non_medis')?$this->table_nm:$this->table;
		$tc_po = ($_GET['flag']=='non_medis')?'tc_po_nm':'tc_po';
		$this->db->select($this->select);
		$this->db->select('SUM(jumlah_harga) as total');
		$this->db->from(''.$table.' a');
		$this->db->join('mt_supplier c','c.kodesupplier=a.kodesupplier', 'left');
		$this->db->join($table.'_detail d', 'd.id_penerimaan=a.id_penerimaan', 'left');
		$this->db->join($tc_po.'_det e','e.id_tc_po_det=d.id_tc_po_det', 'left');
		// $this->db->where('a.status_tukar_faktur IS NULL');
		$this->db->group_by($this->select);

	}

	private function _get_datatables_query()
	{
		
		$this->_main_query();

		if(isset($_GET['checked_nama_perusahaan']) AND $_GET['checked_nama_perusahaan'] == 1){
			if( ( isset( $_GET['nama_perusahaan']) AND $_GET['nama_perusahaan'] != '' )  ){
				$this->db->like( 'c.namasupplier', trim($_GET['nama_perusahaan']) );
			}
		}

		if(isset($_GET['checked_no_po']) AND $_GET['checked_no_po'] == 1){
			if( ( isset( $_GET['no_po']) AND $_GET['no_po'] != '' )  ){
				$this->db->like( 'a.no_po', $_GET['no_po'] );
			}
		}

		if( ( isset( $_GET['tahun']) AND $_GET['tahun'] != '' )  ){
			$this->db->where( 'YEAR(a.tgl_penerimaan)', $_GET['tahun'] );
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

	public function get_selected_item($arr_ids){
		$this->_main_query();
		$this->db->where_in('a.id_penerimaan', $arr_ids);
		$query = $this->db->get()->result();
		return $query;
	}

	public function get_log_data_by_id($flag, $id){

		$tc_penerimaan_brg = ($flag=='medis')?'tc_penerimaan_barang':'tc_penerimaan_barang_nm';
		$this->db->select('a.*, b.total_hutang, c.no_faktur, c.kode_penerimaan');
		$this->db->from('tc_hutang_supplier_inv_det b');
		$this->db->join('tc_hutang_supplier_inv a', 'b.id_tc_hutang_supplier_inv=a.id_tc_hutang_supplier_inv' ,'left');
		$this->db->join($tc_penerimaan_brg.' c', 'c.id_penerimaan=b.id_penerimaan', 'left');
		$this->db->where('b.id_tc_hutang_supplier_inv', $id);
		$this->db->order_by('b.id_tc_hutang_supplier_inv_det ASC');
		$query = $this->db->get()->result();
		// echo $this->db->last_query();
		return $query;
	}

}
