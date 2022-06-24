<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Lap_penjualan_obat_model extends CI_Model {

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->table = 'view_lap_penjualan_farmasi';
		$this->column = array('view_lap_penjualan_farmasi.kode_brg','view_lap_penjualan_farmasi.kode_brg','nama_brg','satuan_kecil', 'AVG(harga_jual_satuan)', 'kartu_stok_gdg.stok_akhir', 'kartu_stok_dp.stok_akhir', 'SUM(jumlah_pesan)');
		$this->select = 'view_lap_penjualan_farmasi.kode_brg,nama_brg,satuan_kecil, SUM(jumlah_pesan) as jml_terjual, AVG(harga_jual_satuan) as harga_rata_satuan';
		$this->order = array('nama_brg' => 'ASC');

	}

	private function _main_query(){

		$this->db->select($this->select);
		$this->db->select('kartu_stok_gdg.stok_akhir as stok_gdg');
		$this->db->select('kartu_stok_dp.stok_akhir as stok_dp');

		$this->db->from($this->table);
		$this->db->join('( SELECT * FROM tc_kartu_stok WHERE id_kartu IN (SELECT MAX(id_kartu) AS id_kartu FROM tc_kartu_stok WHERE CAST(tgl_input as DATE) <= '."'".date('Y-m-d')."'".' AND kode_bagian='."'060201'".' GROUP BY kode_brg) ) AS kartu_stok_gdg', 'kartu_stok_gdg.kode_brg=view_lap_penjualan_farmasi.kode_brg','left');

		$this->db->join('( SELECT * FROM tc_kartu_stok WHERE id_kartu IN (SELECT MAX(id_kartu) AS id_kartu FROM tc_kartu_stok WHERE CAST(tgl_input as DATE) <= '."'".date('Y-m-d')."'".' AND kode_bagian='."'060101'".' GROUP BY kode_brg) ) AS kartu_stok_dp', 'kartu_stok_dp.kode_brg=view_lap_penjualan_farmasi.kode_brg','left');

		$this->db->group_by('view_lap_penjualan_farmasi.kode_brg,nama_brg,satuan_kecil, kartu_stok_gdg.stok_akhir, kartu_stok_dp.stok_akhir');

		$this->db->having('SUM(jumlah_pesan) > 0');
		

	}

	private function _get_datatables_query()
	{
		
		$this->_main_query();
		if (isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' or isset($_GET['to_tgl']) AND $_GET['to_tgl'] != '') {
			$this->db->where("tanggal BETWEEN '".$_GET['from_tgl']."' AND '".$_GET['to_tgl']."'" );
        }else{
			$this->db->where('tanggal', date('Y-m-d'));
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
	
	function get_data()
	{
		$this->_main_query();
		if (isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' or isset($_GET['to_tgl']) AND $_GET['to_tgl'] != '') {
			$this->db->where("tanggal BETWEEN '".$_GET['from_tgl']."' AND '".$_GET['to_tgl']."'" );
        }else{
			$this->db->where('tanggal', date('Y-m-d'));
		}
		$query = $this->db->get();
		return $query->result();
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
