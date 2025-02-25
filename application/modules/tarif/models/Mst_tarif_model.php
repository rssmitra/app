<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Mst_tarif_model extends CI_Model {


	var $table = 'view_tarif_update';
	var $column = array('nama_tarif');
	var $select = 'view_tarif_update.*';
	var $order = array('nama_tarif' => 'ASC');

	public function __construct()
	{
		parent::__construct();
	}


	private function _main_query(){
		$this->db->select($this->select);
		$this->db->from($this->table);

	}

	private function _get_datatables_query()
	{
		
		$this->_main_query();

		if(isset($_GET['unit']) AND $_GET['unit'] != ''){
			$this->db->where('view_tarif_update.kode_bagian', $_GET['unit']);
		}
    
		if(isset($_GET['nama_tarif']) AND $_GET['nama_tarif'] != ''){
      		$this->db->like('view_tarif_update.nama_tarif', $_GET['nama_tarif']);
		}

		if(isset($_GET['jenis_tarif']) AND $_GET['jenis_tarif'] != ''){
			if($_GET['jenis_tarif'] == 'bpjs'){
				$this->db->like('view_tarif_update.nama_tarif', 'bpjs');
			}else{
				  $this->db->not_like('view_tarif_update.nama_tarif', 'bpjs');
			}
	  	}

		if(isset($_GET['is_active']) AND $_GET['is_active'] != ''){
			$this->db->where('view_tarif_update.is_active', $_GET['is_active']);
		}

		$i = 0;
	
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

		$query = $this->db->get()->result();
		$getData = [];
		foreach ($query as $key => $value) {
			# code...
			// $getData[$value->kode_tarif][$value->kode_klas] = $value;
			$getData[$value->kode_tarif]['nama_tarif'] = $value->nama_tarif;
			$getData[$value->kode_tarif]['nama_bagian'] = $value->nama_bagian;
			$getData[$value->kode_tarif]['nama_jenis_tindakan'] = $value->nama_jenis_tindakan;
			$getData[$value->kode_tarif]['klas'][$value->kode_klas] = $value;
			$getData[$value->kode_tarif]['is_active'] = $value->is_active;
		}
		// print_r($this->db->last_query());die;
		return $getData;
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
		if(is_array($id)){
			$this->_main_query();
			$this->db->where_in(''.$this->table.'.kode_tarif',$id);
			$query = $this->db->get();
			return $query->result();
		}else{
			$this->db->select($this->select);
			$this->db->from($this->table);
			$this->db->where(''.$this->table.'.kode_tarif',$id);
			$query = $this->db->get();
			// print_r($this->db->last_query());die;
			return $query->row();
		}
	}

	public function delete_by_id($id)
	{
		// $this->db->where('mt_master_tarif_detail.kode_tarif', $id);
		// $this->db->delete('mt_master_tarif_detail');
		
		$this->db->where('kode_tarif', $id)->update('mt_master_tarif_detail', ['is_active' => 'N']);
		$this->db->where('kode_tarif', $id)->update('mt_master_tarif', ['is_active' => 'N']);

		return true;
	}

	public function delete_tarif_klas($id)
	{
		$this->db->where('mt_master_tarif_detail.kode_master_tarif_detail', $id);
		return $this->db->delete('mt_master_tarif_detail');
	}


	public function get_detail_by_kode_tarif($kode_tarif)
	{
		$this->db->select('kode_master_tarif_detail, mt_master_tarif_detail.kode_tarif, nama_tarif, nama_bagian, mt_master_tarif_detail.kode_klas, jenis_tindakan, nama_klas, CAST(bill_dr1 as INT) as bill_dr1, CAST(bill_dr2 as INT) as bill_dr2, CAST(bill_dr3 as INT) as bill_dr3, CAST(kamar_tindakan as INT) as kamar_tindakan, CAST(biaya_lain as INT) as biaya_lain, CAST(obat as INT) as obat, CAST(alkes as INT) as alkes, CAST(alat_rs as INT) as alat_rs, CAST(adm as INT) as adm, CAST(bhp as INT) as bhp, CAST(pendapatan_rs as INT) as pendapatan_rs, CAST(total as INT) as total, mt_master_tarif_detail.is_active, mt_master_tarif_detail.revisi_ke');
		$this->db->from('mt_master_tarif_detail');
		$this->db->join('mt_klas', 'mt_klas.kode_klas=mt_master_tarif_detail.kode_klas', 'left');
		$this->db->join('mt_master_tarif', 'mt_master_tarif.kode_tarif=mt_master_tarif_detail.kode_tarif', 'left');
		$this->db->join('mt_bagian', 'mt_bagian.kode_bagian=mt_master_tarif.kode_bagian', 'left');
		$this->db->where('mt_master_tarif_detail.kode_tarif', $kode_tarif);
		// $this->db->where('mt_master_tarif_detail.is_active', 'Y');
		$this->db->order_by('nama_klas', 'ASC');
		$this->db->group_by('kode_master_tarif_detail, mt_master_tarif_detail.kode_tarif, nama_tarif, nama_bagian, mt_master_tarif_detail.kode_klas, jenis_tindakan, nama_klas, CAST(bill_dr1 as INT), CAST(bill_dr2 as INT), CAST(bill_dr3 as INT), CAST(kamar_tindakan as INT), CAST(biaya_lain as INT), CAST(obat as INT), CAST(alkes as INT), CAST(alat_rs as INT), CAST(adm as INT), CAST(bhp as INT), CAST(pendapatan_rs as INT), CAST(total as INT), mt_master_tarif_detail.is_active, mt_master_tarif_detail.revisi_ke');
		$result = $this->db->get()->result();
		foreach ($result as $key => $value) {
			$getData[$value->nama_klas][] = $value;
		}
		if(count($result) > 0){
			return array('nama_tarif' => $result[0]->nama_tarif, 'unit'=>$result[0]->nama_bagian, 'result' => $getData);
		}else{
			return [];
		}

		
	}

	public function get_detail_by_kode_tarif_detail($kode_master_tarif_detail)
	{
		$this->db->select('kode_master_tarif_detail, mt_master_tarif_detail.kode_tarif, nama_tarif, mt_master_tarif_detail.kode_klas, kode_bagian, jenis_tindakan, nama_klas, CAST(bill_dr1 as INT) as bill_dr1, CAST(bill_dr2 as INT) as bill_dr2, CAST(bill_dr3 as INT) as bill_dr3, CAST(kamar_tindakan as INT) as kamar_tindakan, CAST(biaya_lain as INT) as biaya_lain, CAST(obat as INT) as obat, CAST(alkes as INT) as alkes, CAST(alat_rs as INT) as alat_rs, CAST(adm as INT) as adm, CAST(bhp as INT) as bhp, CAST(pendapatan_rs as INT) as pendapatan_rs, CAST(total as INT) as total, mt_master_tarif_detail.is_active, mt_master_tarif_detail.revisi_ke');
		$this->db->from('mt_master_tarif_detail');
		$this->db->join('mt_klas', 'mt_klas.kode_klas=mt_master_tarif_detail.kode_klas', 'left');
		$this->db->join('mt_master_tarif', 'mt_master_tarif.kode_tarif=mt_master_tarif_detail.kode_tarif', 'left');
		// $this->db->where('mt_master_tarif_detail.is_active', 'Y');
		$this->db->where('kode_master_tarif_detail', $kode_master_tarif_detail);
		$this->db->order_by('nama_tarif', 'ASC');

		return $this->db->get()->row();
		
	}

}
