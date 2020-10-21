<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Harga_jual_obat_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->table = ($_GET['flag'] == 'non_medis') ? 'mt_barang_nm' : 'mt_barang' ;
		$this->column = array('table_brg.nama_brg','table_brg.kode_brg','nama_kategori','nama_golongan','nama_sub_golongan');
		$this->select = 'table_brg.kode_brg, table_brg.nama_brg, table_brg.content, table_brg.satuan_besar, table_brg.satuan_kecil, table_brg.flag_medis, table_brg.harga_beli, table_brg.is_active, table_brg.path_image, table_brg.updated_date, table_brg.updated_by, table_brg.created_date, table_brg.created_by, table_brg.spesifikasi, rak';
		$this->order = array('table_brg.created_date' => 'DESC', 'table_brg.updated_date' => 'DESC');

	}

	private function _main_query(){
		$this->db->select($this->select);
		$this->db->from($this->table.' as table_brg');

		if( $_GET['flag'] == 'medis' ){
			$this->db->select('nama_kategori,nama_golongan,nama_sub_golongan,nama_generik,nama_layanan,nama_pabrik,nama_jenis,jenis_barang, Margin_percent as margin_percent, stok_minimum, stok_maksimum, mt_rekap_stok.id_profit, table_brg.id_pabrik, table_brg.kode_generik, table_brg.kode_kategori, table_brg.kode_sub_golongan, table_brg.kode_golongan, table_brg.kode_layanan');
			$this->db->join('mt_rekap_stok','mt_rekap_stok.kode_brg=table_brg.kode_brg','left');
			$this->db->join('mt_kategori','mt_kategori.kode_kategori=table_brg.kode_kategori','left');
			$this->db->join('mt_golongan','mt_golongan.kode_golongan=table_brg.kode_golongan','left');
			$this->db->join('mt_sub_golongan','mt_sub_golongan.kode_sub_gol=table_brg.kode_sub_golongan','left');
			$this->db->join('mt_generik','mt_generik.kode_generik=table_brg.kode_generik','left');
			$this->db->join('mt_layanan_obat','mt_layanan_obat.kode_layanan=table_brg.kode_layanan','left');
			$this->db->join('mt_pabrik','mt_pabrik.id_pabrik=table_brg.id_pabrik','left');
			$this->db->join('mt_jenis_obat','mt_jenis_obat.kode_jenis=table_brg.jenis_obat','left');
			$this->db->join('dd_jenis_barang','dd_jenis_barang.id_dd_jenis_barang=table_brg.kode_jenis','left');
		}

		if( $_GET['flag'] == 'non_medis' ){
			$this->db->select('nama_kategori,nama_golongan,nama_sub_golongan,nama_pabrik, stok_minimum, stok_maksimum, table_brg.id_pabrik as kode_pabrik, table_brg.kode_generik, table_brg.kode_kategori, table_brg.kode_sub_golongan, table_brg.kode_golongan, table_brg.kode_layanan');
			$this->db->join('mt_rekap_stok_nm','mt_rekap_stok_nm.kode_brg=table_brg.kode_brg','left');
			$this->db->join('mt_kategori_nm','mt_kategori_nm.kode_kategori=table_brg.kode_kategori','left');
			$this->db->join('mt_golongan_nm','mt_golongan_nm.kode_golongan=table_brg.kode_golongan','left');
			$this->db->join('mt_sub_golongan_nm','mt_sub_golongan_nm.kode_sub_gol=table_brg.kode_sub_golongan','left');
			$this->db->join('mt_pabrik_nm','mt_pabrik_nm.id_pabrik=table_brg.id_pabrik','left');
			
		}

	}

	private function _get_datatables_query()
	{
		
		$this->_main_query();
		// default filter
		$this->db->where('table_brg.kode_brg is not null');

		if( ( isset( $_GET['kode_golongan']) AND $_GET['kode_golongan'] != '' )  ){
			$this->db->where('table_brg.kode_golongan', $_GET['kode_golongan']);
		}

		if( ( isset( $_GET['kode_sub_gol']) AND $_GET['kode_sub_gol'] != '' )  ){
			$this->db->where('table_brg.kode_sub_golongan', $_GET['kode_sub_gol']);
		}

		if( ( isset( $_GET['is_active']) AND $_GET['is_active'] != '' )  ){
			$this->db->where('table_brg.is_active', $_GET['is_active']);
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
		// if( $_GET['flag'] == 'medis' ){
		// 	$this->db->select( 'table_brg. *, Margin_percent as margin_percent' );
		// }
		// $this->db->from( $this->table.' as table_brg' );
		$this->_main_query();
		if(is_array($id)){
			$this->db->where_in('table_brg.kode_brg',$id);
			$query = $this->db->get();
			return $query->result();
		}else{
			$this->db->where('table_brg.kode_brg',$id);
			$query = $this->db->get();
			return $query->row();
		}
		
	}

	public function save($data)
	{
		$this->db->insert($this->table, $data);
		return $this->db->insert_id();
	}

	public function update($table, $where, $data)
	{
		$this->db->update($table, $data, $where);
		return $this->db->affected_rows();
	}

	public function delete_by_id($id)
	{
		$get_data = $this->get_by_id($id);
		if( $this->delete_image_default($get_data[0]) ){
			$this->db->where_in('kode_brg', $id);
			return $this->db->delete($this->table);
		}else{
			return false;
		}
		
	}

	public function delete_image_default($data){
		/*print_r($data);die;*/
		/*if file images exist*/
		if ( file_exists(PATH_IMG_MST_BRG.$data->path_image) ) {
			if($data->path_image != NULL){
				/*delete first path_image file*/
	            unlink(PATH_IMG_MST_BRG.$data->path_image);
			}
        }
        return true;
	}

	public function get_history_po($kode_brg){
		$tc_po = ($_GET['flag']=='medis')?'tc_po':'tc_po_nm';
		$this->db->select('a.*, b.no_po, b.tgl_po, c.namasupplier, b.tgl_kirim, b.petugas, c.alamat, c.telpon1');
		$this->db->from($tc_po.'_det a');
		$this->db->join($tc_po.' b', 'a.id_tc_po=b.id_tc_po','left');
		$this->db->join('mt_supplier c', 'c.kodesupplier=b.kodesupplier','left');
		$this->db->where('kode_brg', $kode_brg);
		$this->db->order_by('a.id_tc_po_det', 'DESC');
		return $this->db->get();
	}


}
