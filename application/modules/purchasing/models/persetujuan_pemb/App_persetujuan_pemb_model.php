<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class App_persetujuan_pemb_model extends CI_Model {

	var $table_nm = 'tc_permohonan_nm';
	var $table = 'tc_permohonan';
	var $column = array('a.kode_permohonan');
	var $select = 'a.id_tc_permohonan, a.kode_permohonan, a.tgl_permohonan,a.status_kirim, a.no_acc, a.tgl_acc, a.ket_acc, a.flag_proses, a.created_date, a.created_by, a.updated_date, a.updated_by, dd_user.username, user_acc.username as user_acc_name, a.status_batal, a.flag_jenis, a.tgl_pemeriksa, a.tgl_penyetuju';
	var $order = array('a.id_tc_permohonan' => 'DESC');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _main_query(){

		$table = ($_GET['flag']=='non_medis')?$this->table_nm:$this->table;
		$this->db->select($this->select);
		$this->db->select('t_total.total_brg');
		$this->db->select('CASE
								WHEN flag_jenis = 1 THEN '."'Cito'".'
								WHEN flag_jenis = 2 THEN '."'Rutin'".'
								ELSE '."'Rutin'".'
							END as jenis_permohonan_name');
		$this->db->from(''.$table.' a');
		$this->db->join('dd_user','dd_user.id_dd_user=a.user_id', 'left');
		$this->db->join('dd_user as user_acc','user_acc.id_dd_user=a.user_id_acc', 'left');
		$this->db->join('(SELECT id_tc_permohonan, COUNT(id_tc_permohonan_det) as total_brg FROM '.$table.'_det GROUP BY id_tc_permohonan ) as t_total', 't_total.id_tc_permohonan=a.id_tc_permohonan', 'left');
		$this->db->where('status_kirim', 1);
		$this->db->where('(status_batal IS NULL or status_batal=0)');
		$this->db->where('(no_acc IS NULL AND tgl_acc IS NULL)');
		$this->db->where('t_total.total_brg > 0');

		if( ( isset( $_GET['keyword']) AND $_GET['keyword'] != '' )  ){
			if( isset( $_GET['search_by']) AND $_GET['keyword'] != '' ){
				if( isset( $_GET['search_by']) AND $_GET['search_by'] == 'kode_permohonan' ){
					$this->db->like( $_GET['search_by'], $_GET['keyword'] );
				}
			}
		}

		if( ( isset( $_GET['status_persetujuan']) AND $_GET['status_persetujuan'] != '' )  ){
			if( $_GET['status_persetujuan'] == 'NULL' ){
				$this->db->where('status_batal IS NULL');
			}else{
				$this->db->where('status_batal', $_GET['status_persetujuan']);
			}
		}

		if (isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' || isset($_GET['to_tgl']) AND $_GET['to_tgl'] != '') {
			$this->db->where("convert(varchar,a.tgl_permohonan,23) between '".$_GET['from_tgl']."' and '".$_GET['to_tgl']."'");
		}else{
			$this->db->where('DATEDIFF(day,a.tgl_permohonan,GETDATE()) < 120');
		}

		// if( $_GET['flag'] == 'medis' ){
		// 	$curr_month = date('m') - 2;
		// 	$this->db->where('MONTH(a.tgl_permohonan) >= '.$curr_month.'');
		// }

		$this->db->group_by('a.id_tc_permohonan, a.kode_permohonan, a.tgl_permohonan,a.status_kirim, a.no_acc, a.tgl_acc, a.ket_acc, a.flag_proses, a.created_date, a.created_by, a.updated_date, a.updated_by, dd_user.username, user_acc.username, a.status_batal, t_total.total_brg, a.flag_jenis, a.tgl_pemeriksa, a.tgl_penyetuju');
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
			$this->db->where_in('a.id_tc_permohonan',$id);
			$query = $this->db->get();
			return $query->result();
		}else{
			$this->db->where('a.id_tc_permohonan',$id);
			$query = $this->db->get();
			// echo '<pre>';print_r($this->db->last_query());die;
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

	public function delete_by_id($id)
	{
		$table = ($_GET['flag']=='non_medis')?$this->table_nm:$this->table;
		/*delete permohonan*/
		$this->db->where_in('id_tc_permohonan', $id)->delete( $table.'_det');
		$this->db->where_in('id_tc_permohonan', $id)->delete( $table );
		return true;
	}

	public function get_detail_brg_permintaan($flag, $id){
		$mt_barang = ($flag=='non_medis')?'mt_barang_nm':'mt_barang';
		$table = ($flag=='non_medis')?$this->table_nm:$this->table;
		$mt_rekap_stok = ($flag=='non_medis')?'mt_rekap_stok_nm':'mt_rekap_stok';

		$this->db->from(''.$table.'_det');
		$this->db->join($table, ''.$table.'.id_tc_permohonan='.$table.'_det.id_tc_permohonan', 'left');
		$this->db->join($mt_barang, ''.$mt_barang.'.kode_brg='.$table.'_det.kode_brg', 'left');
		$this->db->join($mt_rekap_stok, ''.$mt_rekap_stok.'.kode_brg='.$table.'_det.kode_brg', 'left');
		$this->db->where(''.$table.'_det.id_tc_permohonan', $id);
		return $this->db->get()->result();
	}

	public function get_detail_brg_permintaan_multiple($flag, $id){

		$mt_barang = ($flag=='non_medis')?'mt_barang_nm':'mt_barang';
		$mt_rekap_stok = ($flag=='non_medis')?'mt_rekap_stok_nm':'mt_rekap_stok';
		$table = ($flag=='non_medis')?$this->table_nm:$this->table;

		$this->db->from(''.$table.'_det');
		$this->db->join($table, ''.$table.'.id_tc_permohonan='.$table.'_det.id_tc_permohonan', 'left');
		$this->db->join($mt_barang, ''.$mt_barang.'.kode_brg='.$table.'_det.kode_brg', 'left');
		$this->db->join($mt_rekap_stok, ''.$mt_rekap_stok.'.kode_brg='.$table.'_det.kode_brg', 'left');
		$this->db->where_in(''.$table.'_det.id_tc_permohonan', $id);
		$result = $this->db->get()->result();
		$getData = [];
		foreach($result as $row){
			$getData[$row->kode_permohonan][] = array(
				'kode_permohonan' => $row->kode_permohonan,
				'tgl_permohonan' => $row->tgl_permohonan,
				'flag_jenis' => $row->flag_jenis,
				'barang' => $row,
			);
		}
		// echo '<pre>';print_r($getData);die;
		return $getData;

		
	}

	function get_max_number_acc($table){
		$this->db->where('MONTH(tgl_permohonan)', date('m'));
		$this->db->where('YEAR(tgl_permohonan)', date('Y'));
		$row_num = $this->db->get($table)->num_rows();
		$max = $row_num + 1;
		return $max;
	}

}
