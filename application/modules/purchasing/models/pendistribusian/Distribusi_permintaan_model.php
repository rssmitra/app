<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Distribusi_permintaan_model extends CI_Model {

	var $table_nm = 'tc_permintaan_inst_nm';
	var $table = 'tc_permintaan_inst';
	var $column = array('a.nomor_permintaan', 'b.nama_bagian');
	var $select = 'a.id_tc_permintaan_inst, a.nomor_permintaan, a.tgl_permintaan, a.kode_bagian_minta, a.kode_bagian_kirim, a.status_batal, a.tgl_input, a.nomor_pengiriman, a.tgl_pengiriman, a.yg_serah, a.yg_terima, a.tgl_input_terima, a.id_dd_user_terima, a.keterangan_kirim, a.status_selesai, a.jenis_permintaan, tgl_acc, acc_by, status_acc';
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
		}

		$this->db->where('status_acc', 1);
		$this->db->where('version', 1);

		$this->db->group_by('CAST(a.catatan as NVARCHAR(1000))');
		$this->db->group_by('b.nama_bagian, c.fullname ');
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

	public function get_cart_data(){

		$this->db->select('kode_brg, nama_brg, qty, satuan, cast(harga as int) as harga, flag, nama_bagian, qtyBefore as jumlah_stok_sebelumnya, reff_kode, retur_type, is_bhp, is_restock, tc_permintaan_inst_cart_log.kode_bagian, keterangan as keterangan_permintaan, id as id_tc_permintaan_inst_det, '."'cart_log'".' as type_tbl');
		$this->db->from('tc_permintaan_inst_cart_log');
		$this->db->join('mt_bagian', 'mt_bagian.kode_bagian=tc_permintaan_inst_cart_log.kode_bagian');
		$this->db->where('user_id_session', $this->session->userdata('user')->user_id);
		$this->db->where('flag_form', 'distribusi');
		$this->db->order_by('tc_permintaan_inst_cart_log.id', 'ASC');
		// $this->db->group_by('tc_permintaan_inst_cart_log.id, kode_brg, nama_brg, satuan, harga, flag, nama_bagian, qtyBefore, reff_kode, retur_type, is_bhp, is_restock, tc_permintaan_inst_cart_log.kode_bagian, keterangan');
		return $this->db->get()->result();

	}

	


}
