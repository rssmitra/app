<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Req_pembelian_model extends CI_Model {

	var $table_nm = 'tc_permohonan_nm';
	var $table = 'tc_permohonan';
	var $column = array('a.kode_permohonan', 'a.created_by', 'a.updated_by', 'dd_user.username', 'user_acc.username');
	var $select = 'a.id_tc_permohonan, a.kode_permohonan, a.tgl_permohonan,a.status_kirim, a.no_acc, a.tgl_acc, a.ket_acc, a.flag_proses, a.created_date, a.created_by, a.updated_date, a.updated_by, dd_user.username, user_acc.username as user_acc_name, a.status_batal, a.flag_jenis, a.tgl_pemeriksa, a.tgl_penyetuju, a.keterangan_permohonan';
	var $order = array('a.id_tc_permohonan' => 'DESC');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _main_query(){

		$table = ($_GET['flag']=='non_medis')?$this->table_nm:$this->table;
		$mt_barang = ($_GET['flag']=='non_medis')?'mt_barang_nm':'mt_barang';
		$this->db->select($this->select);
		$this->db->select('t_total.total_brg');
		$this->db->select('CASE
								WHEN flag_jenis = 1 THEN '."'Cito'".'
								WHEN flag_jenis = 2 THEN '."'Rutin'".'
								WHEN flag_jenis = 3 THEN '."'Non Rutin'".'
								ELSE '."'Rutin'".'
							END as jenis_permohonan_name');
		$this->db->from(''.$table.' a');
		$this->db->join('dd_user','dd_user.id_dd_user=a.user_id', 'left');
		$this->db->join('dd_user as user_acc','user_acc.id_dd_user=a.user_id_acc', 'left');
		$this->db->join('(SELECT id_tc_permohonan, COUNT(id_tc_permohonan_det) as total_brg FROM '.$table.'_det GROUP BY id_tc_permohonan ) as t_total', 't_total.id_tc_permohonan=a.id_tc_permohonan', 'left');


	
		if( ( isset( $_GET['keyword']) AND $_GET['keyword'] != '' )  ){
			if( isset( $_GET['search_by']) AND $_GET['keyword'] != '' ){
				if( isset( $_GET['search_by']) AND $_GET['search_by'] == 'kode_permohonan' ){
					$this->db->like( $_GET['search_by'], $_GET['keyword'] );
				}

				if( isset( $_GET['search_by']) AND $_GET['search_by'] == 'nama_barang' ){
					$this->db->join(''.$table.'_det f','f.id_tc_permohonan=t_total.id_tc_permohonan', 'left');
					$this->db->join($mt_barang,''.$mt_barang.'.kode_brg=f.kode_brg', 'left');
					$this->db->like( 'nama_brg', $_GET['keyword'] );
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
			$this->db->where('DATEDIFF(day,a.tgl_permohonan,GETDATE()) < 14');
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
		$tc_po = ($flag=='non_medis')?'tc_po_nm':'tc_po';
		$table = ($flag=='non_medis')?$this->table_nm:$this->table;

		$this->db->select(''.$table.'_det.*');
		$this->db->select($table.'.*');
		$this->db->select($mt_barang.'.*');
		$this->db->select('po.total_po');
		$this->db->from(''.$table.'_det');
		$this->db->join($table, ''.$table.'.id_tc_permohonan='.$table.'_det.id_tc_permohonan', 'left');
		$this->db->join($mt_barang, ''.$mt_barang.'.kode_brg='.$table.'_det.kode_brg', 'left');
		$this->db->join('(SELECT id_tc_permohonan_det, sum(convert(decimal(18,2),jumlah_besar_acc)) as total_po FROM '.$tc_po.'_det GROUP BY id_tc_permohonan_det) as po', 'po.id_tc_permohonan_det='.$table.'_det.id_tc_permohonan_det', 'left');
		$this->db->order_by($mt_barang.'.nama_brg', 'ASC');
		$this->db->where(''.$table.'_det.id_tc_permohonan', $id);
		return $this->db->get()->result();
	}

	public function get_detail_brg_permintaan_multiple($flag, $id){

		$mt_barang = ($flag=='non_medis')?'mt_barang_nm':'mt_barang';
		$table = ($flag=='non_medis')?$this->table_nm:$this->table;

		$this->db->from(''.$table.'_det');
		$this->db->join($table, ''.$table.'.id_tc_permohonan='.$table.'_det.id_tc_permohonan', 'left');
		$this->db->join($mt_barang, ''.$mt_barang.'.kode_brg='.$table.'_det.kode_brg', 'left');
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

	function show_log_brg(){
		$mt_barang = ($_GET['flag']=='non_medis')?'mt_barang_nm':'mt_barang';
		$tc_permohonan = ($_GET['flag']=='non_medis')?'tc_permohonan_nm':'tc_permohonan';
		$tc_po = ($_GET['flag']=='non_medis')?'tc_po_nm':'tc_po';
		$tc_penerimaan = ($_GET['flag']=='non_medis')?'tc_penerimaan_barang_nm':'tc_penerimaan_barang';
		// permohonan
		$permohonan = $this->db->select('b.id_tc_permohonan, b.kode_permohonan, b.tgl_permohonan,b.status_kirim, b.no_acc, b.tgl_acc, b.ket_acc, b.flag_proses, b.created_date, b.created_by, b.updated_date, b.updated_by, dd_user.username, user_acc.username as user_acc_name, b.status_batal, b.flag_jenis, b.tgl_pemeriksa, b.tgl_penyetuju, b.keterangan_permohonan, c.nama_brg, a.satuan_besar, a.jumlah_besar, a.jumlah_besar_acc, b.pemeriksa, b.tgl_pemeriksa, b.penyetuju, b.tgl_penyetuju, a.kode_brg, a.jml_acc_penyetuju')
		->join($tc_permohonan.' as b', 'b.id_tc_permohonan=a.id_tc_permohonan','left')
		->join($mt_barang.' as c', 'c.kode_brg=a.kode_brg','left')
		->join('dd_user','dd_user.id_dd_user=b.user_id', 'left')
		->join('dd_user as user_acc','user_acc.id_dd_user=b.user_id_acc', 'left')
		->get_where($tc_permohonan.'_det as a', 
					array('id_tc_permohonan_det' => $_GET['id'], 'a.kode_brg' => $_GET['kode_brg']) )
		->row();

		$po = $this->db->select('a.jumlah_besar, a.content, a.harga_satuan harga_satuan, a.discount, a.jumlah_harga_netto jumlah_harga_netto, a.diajukan_oleh, a.disetujui_oleh, b.no_po, b.tgl_po, c.namasupplier, b.ppn, a.jumlah_besar_acc')
		->join($tc_po.' as b','b.id_tc_po=a.id_tc_po','left')
		->join('mt_supplier c','c.kodesupplier=b.kodesupplier','left')
		->get_where($tc_po.'_det as a', array('id_tc_permohonan_det' => $_GET['id'], 'kode_brg' => $_GET['kode_brg'] ) )
		->result();

		$penerimaan = $this->db->select('*')
		->join($tc_penerimaan.' as b','b.kode_penerimaan=a.kode_penerimaan','left')
		->where('a.id_tc_po_det IN (SELECT id_tc_po_det FROM '.$tc_po.'_det WHERE id_tc_permohonan_det='.$_GET['id'].' AND kode_brg='."'".$_GET['kode_brg']."'".' )')
		->from($tc_penerimaan.'_detail as a')
		->get()->result();

		// echo '<pre>';print_r($penerimaan);die;
		// echo '<pre>';print_r($this->db->last_query());die;
		$data = array();
		$data['permohonan'] = $permohonan;
		$data['po'] = $po;
		$data['penerimaan'] = $penerimaan;
		return $data;
	}

	public function data_template(){
		$mt_barang = ($_GET['flag']=='non_medis')?'mt_barang_nm':'mt_barang';
		$temp_name = str_replace('-',' ',$_GET['name']);
		$query = $this->db->select('tc_permohonan_temp.*, a.nama_brg')->join($mt_barang.' a', 'a.kode_brg=tc_permohonan_temp.kode_brg','left')->order_by('a.nama_brg', 'ASC')->get_where('tc_permohonan_temp', array('temp_name' => $temp_name, 'flag' => $_GET['flag']) )->result();
		return $query;

	}

}
