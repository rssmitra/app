<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inv_stok_depo_model extends CI_Model {

	var $table = 'mt_depo_stok';
	var $column = array('b.nama_brg');
	var $select = 'b.kode_brg, b.nama_brg, b.content, kartu_stok.stok_akhir, b.satuan_kecil,  kartu_stok.tgl_input, kartu_stok.keterangan,  b.satuan_besar, b.path_image, a.stok_minimum, c.harga_beli, b.is_prb, b.is_kronis, a.is_active';
	var $order = array('kartu_stok.tgl_input' => 'DESC');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _main_query($tgl, $params_kode_bagian){

		$params_tgl = date('Y-m-d', strtotime('+1 days', strtotime($tgl)));
		$this->db->select($this->select);
		$this->db->from($this->table.' as a');
		$this->db->join('mt_barang b','b.kode_brg=a.kode_brg','left');
		$this->db->join('mt_rekap_stok c','c.kode_brg=b.kode_brg','left');
		$this->db->join('( SELECT * FROM tc_kartu_stok WHERE id_kartu IN (SELECT MAX(id_kartu) AS id_kartu FROM tc_kartu_stok WHERE CAST(tgl_input as DATE) <= '."'".$params_tgl."'".' AND kode_bagian='."'".$params_kode_bagian."'".' GROUP BY kode_brg) ) AS kartu_stok', 'kartu_stok.kode_brg=a.kode_brg','left');
		$this->db->where('a.kode_bagian', $params_kode_bagian);
		$this->db->where('nama_brg is not null');
		// $this->db->where('kartu_stok.tgl_input is not null');
		$this->db->group_by($this->select);
		
	}

	private function _get_datatables_query()
	{
		$params_tgl = isset($_GET['tgl']) ? $_GET['tgl'] : date('Y-m-d')  ;
		$params_kode_bagian = isset($_GET['kode_bagian']) ? $_GET['kode_bagian'] : '060101' ;

		$this->_main_query($params_tgl, $params_kode_bagian);
		if (isset($_GET['id_pabrik']) AND $_GET['id_pabrik'] != '' ) {
			$this->db->where('b.id_pabrik', $_GET['id_pabrik']);				
		}
		
		if (isset($_GET['kode_layanan']) AND $_GET['kode_layanan'] != '' ) {
			$this->db->where('b.kode_layanan', $_GET['kode_layanan']);				
		}

		if( ( isset( $_GET['prb']) AND $_GET['prb'] != '' )  ){
			$this->db->where('b.is_prb', $_GET['prb']);
		}

		if( ( isset( $_GET['kronis']) AND $_GET['kronis'] != '' )  ){
			$this->db->where('b.is_kronis', $_GET['kronis']);
		}

		if( ( isset( $_GET['status']) AND $_GET['status'] != '' )  ){
			$this->db->where('a.is_active', $_GET['status']);
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
		// print_r($this->db->last_query());die;
		return $query->result();
	}

	function get_data($tgl, $params_kode_bagian)
	{
		$this->_main_query($tgl, $params_kode_bagian);
		if (isset($_GET['id_pabrik']) AND $_GET['id_pabrik'] != '' ) {
			$this->db->where('b.id_pabrik', $_GET['id_pabrik']);				
		}

		if (isset($_GET['kode_layanan']) AND $_GET['kode_layanan'] != '' ) {
			$this->db->where('b.kode_layanan', $_GET['kode_layanan']);				
		}
		$query = $this->db->get();
		// print_r($this->db->last_query());die;
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
		$this->db->select($this->select);
		$this->db->from($this->table);
		if(is_array($id)){
			$this->db->where_in(''.$this->table.'.kode_brg',$id);
			$query = $this->db->get();
			return $query->result();
		}else{
			$this->db->where(''.$this->table.'.kode_brg',$id);
			$query = $this->db->get();
			return $query->row();
		}
		
	}
	public function get_mutasi_stok($kode_brg, $kode_bagian)
	{
		$this->db->from($this->table.' as a');
		$this->db->join('mt_barang b','b.kode_brg=a.kode_brg','left');
		$this->db->where('a.kode_brg', $kode_brg);
		$this->db->where('a.kode_bagian', $kode_bagian);
		$query = $this->db->get();
		return $query->row();
		// echo '<pre>'; print_r($this->db->last_query());die;
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
		$this->db->where_in(''.$this->table.'.education_id', $id);
		return $this->db->delete($this->table);
	}


}
