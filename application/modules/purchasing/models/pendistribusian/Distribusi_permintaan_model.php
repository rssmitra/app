<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Distribusi_permintaan_model extends CI_Model {

	var $table_nm = 'tc_permintaan_inst_nm';
	var $table = 'tc_permintaan_inst';
	var $column = array('a.nomor_permintaan', 'b.nama_bagian');
	var $select = 'a.id_tc_permintaan_inst, a.nomor_permintaan, a.tgl_permintaan, a.kode_bagian_minta, a.kode_bagian_kirim, a.status_batal, a.tgl_input, a.nomor_pengiriman, a.tgl_pengiriman, a.yg_serah, a.yg_terima, a.tgl_input_terima, a.id_dd_user_terima, a.keterangan_kirim, a.status_selesai, a.jenis_permintaan';
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
		$this->db->select('b.nama_bagian as bagian_minta');
		$this->db->from(''.$table.' a');
		$this->db->join('mt_bagian b','b.kode_bagian=a.kode_bagian_minta', 'left');
		
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
		}

		$this->db->group_by('CAST(a.catatan as NVARCHAR(1000))');
		$this->db->group_by('b.nama_bagian');
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
		$join_3 = ($flag=='non_medis')?'mt_rekap_stok_nm':'mt_rekap_stok';

		$this->db->select('a.id_tc_permintaan_inst_det, jumlah_permintaan, jumlah_penerimaan , a.kode_brg,, c.nama_brg, f.harga_beli, c.content as rasio, c.satuan_kecil, c.satuan_besar, e.nomor_permintaan, e.jenis_permintaan, e.tgl_permintaan, f.jml_sat_kcl as jumlah_stok_sebelumnya, g.nama_bagian');
		$this->db->from(''.$table.'_det a');
		$this->db->join($mt_barang.' c', 'c.kode_brg=a.kode_brg', 'left');
		$this->db->join($table.' e', 'e.id_tc_permintaan_inst=a.id_tc_permintaan_inst', 'left');
		$this->db->join('mt_bagian g', 'g.kode_bagian=e.kode_bagian_minta', 'left');
		$this->db->join($join_3.' f', 'f.kode_brg=a.kode_brg', 'left');
		$id = (is_array($id)) ? implode(',', $id) : $id ;
		$this->db->where('a.id_tc_permintaan_inst IN ('.$id.')');
		$this->db->group_by('a.id_tc_permintaan_inst_det, jumlah_permintaan, jumlah_penerimaan , a.kode_brg,, c.nama_brg, f.harga_beli, c.content, c.satuan_kecil, c.satuan_besar, e.nomor_permintaan, e.jenis_permintaan, e.tgl_permintaan, f.jml_sat_kcl, g.nama_bagian');
		$this->db->order_by('c.nama_brg ASC');
		$query = $this->db->get()->result();
		// print_r($this->db->last_query());
		return $query;
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


}
