<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Riwayat_retur_model extends CI_Model {

	var $table = 'tc_retur_unit';
	var $column = array('a.kode_retur');
	var $select = 'a.id_tc_retur_unit, a.kode_retur, a.kode_bagian, a.tgl_retur, a.status, a.tgl_input, a.petugas_unit, a.petugas_gudang, a.flag';
	var $order = array('a.id_tc_retur_unit' => 'DESC');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _main_query(){
		$this->db->select($this->select);
		$this->db->select('CAST(a.catatan as NVARCHAR(1000)) as catatan');
		$this->db->select('b.nama_bagian as bagian_minta');
		$this->db->from(''.$this->table.' a');
		$this->db->join('mt_bagian b','b.kode_bagian=a.kode_bagian', 'left');
		
	}

	private function _get_datatables_query()
	{
		
		$this->_main_query();

		if (isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' || isset($_GET['to_tgl']) AND $_GET['to_tgl'] != '') {
			$this->db->where("convert(varchar,a.tgl_retur,23) between '".$_GET['from_tgl']."' and '".$_GET['to_tgl']."'");
		}else{
			$this->db->where('YEAR(a.tgl_retur)='.date('Y').'');
		}

		if( ( isset( $_GET['kode_bagian']) AND $_GET['kode_bagian'] != '' )  ){
			$this->db->where('a.kode_bagian', $_GET['kode_bagian']);
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

	public function get_brg_retur($flag, $id){
		$mt_barang = ($flag=='non_medis')?'mt_barang_nm':'mt_barang';

		$this->db->select('a.*, c.nama_brg, c.content as rasio, c.satuan_kecil, c.satuan_besar, e.kode_retur, e.tgl_retur, g.nama_bagian');
		$this->db->from('tc_retur_unit_det a');
		$this->db->join($mt_barang.' c', 'c.kode_brg=a.kode_brg', 'left');
		$this->db->join('tc_retur_unit e', 'e.id_tc_retur_unit=a.id_tc_retur_unit', 'left');
		$this->db->join('mt_bagian g', 'g.kode_bagian=e.kode_bagian', 'left');
		$id = (is_array($id)) ? implode(',', $id) : $id ;
		$this->db->where('a.id_tc_retur_unit IN ('.$id.')');
		$this->db->order_by('c.nama_brg ASC');
		return $this->db->get()->result();
	}

	public function get_detail_brg_permintaan_multiple($flag, $id){

		$result = $this->get_brg_retur($flag, $id);
		$getData = [];
		foreach($result as $row){
			$getData[$row->kode_retur][] = array(
				'kode_retur' => $row->kode_retur,
				'tgl_retur' => $row->tgl_retur,
				'nama_bagian' => $row->nama_bagian,
				'barang' => $row,
			);
		}
		// echo '<pre>';print_r($getData);die;
		return $getData;
		
	}
	
}
