<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Inv_stok_gdg_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->table = ($_GET['flag'] == 'non_medis') ? 'mt_depo_stok_nm' : 'mt_depo_stok' ;
		$this->column = array('b.kode_brg','b.nama_brg');
		$this->select = 'b.kode_brg, b.nama_brg, b.content, kartu_stok.stok_akhir, b.satuan_kecil, 
		c.harga_beli, kartu_stok.tgl_input, kartu_stok.keterangan,c.stok_minimum, b.satuan_besar, b.is_active, b.path_image, c.jml_sat_kcl';
		$this->order = array('kartu_stok.tgl_input' => 'DESC');
		
	}

	private function _main_query($params_tgl, $params_kode_bagian){

		$is_nm = ($_GET['flag'] == 'non_medis') ? '_nm' : '' ;
		$t_kartu_stok = ($_GET['flag'] == 'non_medis') ? 'tc_kartu_stok_nm' : 'tc_kartu_stok' ;
		$date = date('Y-m-d', strtotime('+1 days', strtotime($params_tgl)));

		$this->db->select($this->select);
		$this->db->from($this->table.' as a');
		$this->db->join('mt_barang'.$is_nm.' b','b.kode_brg=a.kode_brg','left');
		$this->db->join('mt_rekap_stok'.$is_nm.' c','c.kode_brg=b.kode_brg','left');
		$this->db->join('( SELECT * FROM '.$t_kartu_stok.' WHERE id_kartu IN (SELECT MAX(id_kartu) AS id_kartu FROM '.$t_kartu_stok.' WHERE tgl_input <= '."'".$date."'".' AND tgl_input is not null AND kode_bagian='."'".$params_kode_bagian."'".' GROUP BY kode_brg) AND kode_bagian='."'".$params_kode_bagian."'".' ) AS kartu_stok', 'kartu_stok.kode_brg=a.kode_brg','left');

		$this->db->where('kartu_stok.kode_bagian', $params_kode_bagian);
		// $this->db->where('b.nama_brg is not null');
		// $this->db->where('kartu_stok.tgl_input is not null');
		$this->db->group_by($this->select);
		
	}

	private function _get_datatables_query()
	{
		$params_tgl = isset($_GET['tgl']) ? $_GET['tgl'] : date('Y-m-d') ;
		$this->_main_query($params_tgl, $_GET['kode_bagian']);		

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
		$this->_main_query(date('Y-m-d'), $kode_bagian);
		$this->db->where('kartu_stok.kode_brg', $kode_brg);
		$query = $this->db->get();
		// print_r($this->db->last_query());die;
		return $query->row();
		
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
