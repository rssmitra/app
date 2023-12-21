<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Po_penerbitan_model extends CI_Model {

	var $table_nm = 'tc_permohonan_nm';
	var $table = 'tc_permohonan';
	var $column = array('a.kode_permohonan');
	var $select = 'a.id_tc_permohonan, a.kode_permohonan, a.tgl_permohonan,a.status_kirim, a.no_acc, a.tgl_acc, a.ket_acc, a.flag_proses, a.created_date, a.created_by, a.updated_date, a.updated_by, dd_user.username, user_acc.username as user_acc_name, a.status_batal, flag_jenis';
	var $order = array('a.id_tc_permohonan' => 'DESC');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _main_query(){
		$table = ($_GET['flag']=='non_medis')?$this->table_nm:$this->table;
		$this->db->select($this->select);
		$this->db->select('CASE
								WHEN flag_jenis = 1 THEN '."'Cito'".'
								WHEN flag_jenis = 2 THEN '."'Rutin'".'
								WHEN flag_jenis = 3 THEN '."'Non Rutin'".'
								ELSE '."'Rutin'".'
							END as jenis_permohonan_name');
		$this->db->from(''.$table.' a');
		$this->db->join('dd_user','dd_user.id_dd_user=a.user_id', 'left');
		$this->db->join('dd_user as user_acc','user_acc.id_dd_user=a.user_id_acc', 'left');
		// $this->db->join(''.$table.'_det d','d.id_tc_permohonan=a.id_tc_permohonan', 'inner');

		// $this->db->where('DATEDIFF(day,a.tgl_permohonan,GETDATE()) < 30');
		// $this->db->where('(d.status_po = 0 or d.status_po IS NULL)');
		$this->db->where('a.no_acc is not null');
		$this->db->where('a.status_batal', 0);
		$this->db->where('a.flag_proses != 3');
		
		if( ( isset( $_GET['keyword']) AND $_GET['keyword'] != '' )  ){
			if( isset( $_GET['search_by']) AND $_GET['search_by'] == 'kode_permohonan' ){
				$this->db->like( $_GET['search_by'], $_GET['keyword'] );
			}

			if( isset( $_GET['search_by']) AND $_GET['search_by'] == 'nama_brg' ){
				$tbrg = ($_GET['flag']=='non_medis')?'mt_barang_nm':'mt_barang';
				$this->db->join($table.'_det z', 'z.id_tc_permohonan = a.id_tc_permohonan', 'left');
				$this->db->join($tbrg.' v', 'v.kode_brg = z.kode_brg', 'left');
				$this->db->like( 'v.nama_brg', $_GET['keyword'] );
			}
		}

		if( isset( $_GET['search_by']) AND $_GET['search_by'] == 'month' ){
			$this->db->where( 'MONTH(a.tgl_permohonan)', $_GET['month'] );
		}

		if (isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' || isset($_GET['to_tgl']) AND $_GET['to_tgl'] != '') {
			$this->db->where("convert(varchar,a.tgl_permohonan,23) between '".$_GET['from_tgl']."' and '".$_GET['to_tgl']."'");
		}else{
			$this->db->where('DATEDIFF(day,a.tgl_permohonan,GETDATE()) < 14');
		}

		$this->db->group_by('a.id_tc_permohonan, a.kode_permohonan, a.tgl_permohonan,a.status_kirim, a.no_acc, a.tgl_acc, a.ket_acc, a.flag_proses, a.created_date, a.created_by, a.updated_date, a.updated_by, dd_user.username, user_acc.username, a.status_batal, flag_jenis');
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
		/*delete stok opname*/
		$this->db->where_in('id_tc_permohonan', $id)->delete('tc_stok_opname');
		$this->db->where_in('id_tc_permohonan', $id)->delete('tc_stok_opname_nm');
		$this->db->where_in('id_tc_permohonan', $id)->delete('tc_permohonan_nm');
		return true;
	}

	public function get_detail_brg_permintaan($flag, $id){
		$mt_barang = ($flag=='non_medis')?'mt_barang_nm':'mt_barang';
		$table = ($flag=='non_medis')?$this->table_nm:$this->table;
		$join = ($flag=='non_medis')?'tc_po_nm_det':'tc_po_det';
		$join_2 = ($flag=='non_medis')?'tc_po_nm':'tc_po';
		$join_3 = ($flag=='non_medis')?'mt_rekap_stok_nm':'mt_rekap_stok';

		$select = "a.id_tc_permohonan_det, a.id_tc_permohonan, a.kode_brg, a.jumlah_besar, a.satuan_besar, a.rasio, a.status_po, a.jumlah_besar_acc, a.status_batal, a.user_id, a.jumlah_stok_sebelumnya, a.created_date, a.created_by, a.updated_date, a.updated_by, jml_acc_pemeriksa, jml_acc_penyetuju, jml_besar_acc, c.nama_brg, e.no_po, e.tgl_po, c.satuan_besar";
		
		$this->db->select($select);
		$this->db->select('cast(a.keterangan as nvarchar(2000)) as keterangan,d.content AS content_po, d.harga_satuan AS harga_satuan_po, d.jumlah_harga AS jumlah_harga_po, c.harga_beli AS master_harga, CAST ( a.jml_besar AS FLOAT ) AS jml_besar, CAST ( a.jml_besar AS FLOAT ) AS jumlah_besar_po, CAST ( f.harga_beli AS INT ) AS harga_po_terakhir, CAST ( a.jml_besar_acc AS FLOAT ) AS jml_besar_acc, CAST ( a.jml_acc_pemeriksa AS FLOAT ) AS jml_acc_pemeriksa, CAST ( a.jml_acc_penyetuju AS FLOAT ) AS jml_acc_penyetuju ');

		$this->db->from(''.$table.'_det a');
		$this->db->join($table.' b', 'b.id_tc_permohonan=a.id_tc_permohonan', 'left');
		$this->db->join($mt_barang.' c', 'c.kode_brg=a.kode_brg', 'left');
		$this->db->join($join.' d', 'd.id_tc_permohonan_det=a.id_tc_permohonan_det', 'left');
		$this->db->join($join_2.' e', 'e.id_tc_po=d.id_tc_po', 'left');
		$this->db->join($join_3.' f', 'f.kode_brg=a.kode_brg', 'left');

		$id = (is_array($id)) ? implode(',', $id) : $id ;
		$this->db->where('a.id_tc_permohonan IN ('.$id.')');
		$this->db->where('a.jml_acc_penyetuju > 0');
		// $this->db->where('(a.status_po IS NULL or a.status_po = 0)');
		$this->db->group_by($select);
		$this->db->group_by('CAST ( f.harga_beli AS INT ),
								CAST ( a.jml_besar AS FLOAT ),
								CAST ( a.jml_besar_acc AS FLOAT ),
								CAST ( a.jml_acc_pemeriksa AS FLOAT ),
								CAST ( a.jml_acc_penyetuju AS FLOAT ), d.content, d.harga_satuan, d.jumlah_harga, c.harga_beli,cast(a.keterangan as nvarchar(2000)) ');
		$this->db->order_by('c.nama_brg ASC');
		$query = $this->db->get()->result();
		// print_r($this->db->last_query());die;
		return $query;
		// echo '<pre>'; print_r($this->db->get()->result());die;
	}

	public function get_po($flag, $id){
		$mt_barang = ($flag=='non_medis')?'mt_barang_nm':'mt_barang';
		$table = ($flag=='non_medis')?'tc_po_nm_det':'tc_po_det';
		$join_po = ($flag=='non_medis')?'tc_po_nm':'tc_po';
		$join = ($flag=='non_medis')?'mt_rekap_stok_nm':'mt_rekap_stok';
		$tc_permohonan = ($flag=='non_medis')?'tc_permohonan_nm':'tc_permohonan';

		$this->db->select('a.id_tc_po_det, a.id_tc_po, a.id_tc_permohonan_det, a.id_tc_permohonan, a.kode_brg, a.jumlah_besar, a.jumlah_besar_acc, a.content, a.harga_satuan as harga_satuan, a.harga_satuan_netto as harga_satuan_netto, a.jumlah_harga_netto as jumlah_harga_netto,a.jumlah_harga as jumlah_harga, a.discount, a.discount_rp as discount_rp, c.nama_brg, c.satuan_besar, b.no_po, b.tgl_po, b.ppn as ppn, a.ppn as ppn_brg, b.total_sbl_ppn as total_sbl_ppn, b.total_stl_ppn as total_stl_ppn, b.discount_harga as total_diskon, b.term_of_pay, b.diajukan_oleh, b.tgl_kirim as estimasi_kirim, e.namasupplier, e.alamat, e.kota, e.telpon1, b.no_urut_periodik, b.jenis_po');
		$this->db->from(''.$table.' a');
		$this->db->join($join_po.' b', 'b.id_tc_po=a.id_tc_po', 'left');
		$this->db->join($mt_barang.' c', 'c.kode_brg=a.kode_brg', 'left');
		$this->db->join($join.' d', 'd.kode_brg=a.kode_brg', 'left');
		$this->db->join('mt_supplier e', 'e.kodesupplier=b.kodesupplier', 'left');

		$id = (is_array($id)) ? implode(',', $id) : $id ;
		$this->db->where('a.id_tc_po IN ('.$id.')');
		$this->db->group_by('a.id_tc_po_det, a.id_tc_po, a.id_tc_permohonan_det, a.id_tc_permohonan, a.kode_brg, a.jumlah_besar, a.jumlah_besar_acc, a.content, a.harga_satuan, a.harga_satuan_netto, a.jumlah_harga_netto,a.jumlah_harga, a.discount, a.discount_rp, c.nama_brg, c.satuan_besar, b.no_po, b.tgl_po, b.ppn, b.total_sbl_ppn, b.total_stl_ppn, b.discount_harga, b.term_of_pay, b.diajukan_oleh, b.tgl_kirim, e.namasupplier, e.alamat, e.kota, e.telpon1, b.no_urut_periodik, b.jenis_po, a.ppn');
		$this->db->order_by('c.nama_brg ASC');
		return $this->db->get()->result();
	}

	public function rollback_po($flag, $id)
	{
		$tc_permohonan = ($flag=='non_medis')?'tc_permohonan_nm':'tc_permohonan';
		// update header permohonan
		$this->db->where('id_tc_permohonan', $id)->update($tc_permohonan, array('status_kirim' => null, 'ket_acc'  => null, 'no_acc' => null, 'tgl_pemeriksa' => null, 'tgl_penyetuju' => null, 'tgl_acc' => null ) );
		// update detail permohonan untuk jumlah acc
		$this->db->where('(status_po is null or status_po = 0)')->where('id_tc_permohonan', $id)->update($tc_permohonan.'_det', array('jumlah_besar_acc' => NULL, 'jml_besar_acc' => NULL, 'jml_acc_pemeriksa' => NULL, 'jml_acc_penyetuju' => NULL, 'status_po' => NULL) );
		// print_r($this->db->last_query());die;

		return true;
	}

	public function rollback_status($flag, $id)
	{
		$tc_permohonan = ($flag=='non_medis')?'tc_permohonan_nm':'tc_permohonan';
		// update detail permohonan untuk jumlah acc
		$this->db->where('id_tc_permohonan_det', $id)->update($tc_permohonan.'_det', array('status_po' => NULL) );

		return true;
	}

	function update_flag_proses($arr_id, $flag){

		$tc_permohonan = ($flag=='medis')?'tc_permohonan':'tc_permohonan_nm';
		$tc_po = ($flag=='medis')?'tc_po':'tc_po_nm';
		$arr_to_string = implode(',', $arr_id);

		$query = "select a.id_tc_permohonan, count(a.kode_brg)as total_permohonan, count(id_tc_po_det) as total_po
		from ".$tc_permohonan."_det a
		full outer join ".$tc_po."_det b on b.id_tc_permohonan_det=a.id_tc_permohonan_det
		where a.id_tc_permohonan in (".$arr_to_string.")
		group by a.id_tc_permohonan 
		order by id_tc_permohonan DESC";
		$exc_query = $this->db->query($query)->result();

		foreach ($exc_query as $key => $value) {
			if( $value->total_po >= $value->total_permohonan )
				$this->db->where('id_tc_permohonan', $value->id_tc_permohonan)->update($tc_permohonan, array('flag_proses' => 3) );
		}
		return true;
	}

	function getReferensiPO($kode_brg, $table){
		$query = "select top 3 * from (
			SELECT a.id_tc_po, a.discount, a.harga_satuan, b.kodesupplier, c.namasupplier
			FROM ".$table."_det as a
			LEFT JOIN ".$table." as b ON b.id_tc_po=a.id_tc_po
			LEFT JOIN mt_supplier as c ON c.kodesupplier=b.kodesupplier
			WHERE kode_brg = '".$kode_brg."'
			GROUP BY a.discount, a.harga_satuan, b.kodesupplier, c.namasupplier, a.id_tc_po
			) as tblx 
			ORDER BY id_tc_po DESC";
		return $this->db->query($query)->result_array();
	}

}
