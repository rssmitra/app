<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Perjanjian_bedah_model extends CI_Model {

	var $table = 'tc_pesanan';
	var $column = array('tc_pesanan.nama','tc_pesanan.no_mr');
	var $select = 'tc_pesanan.id_tc_pesanan, tc_pesanan.nama, tc_pesanan.tgl_pesanan, tc_pesanan.no_mr, mt_bagian.nama_bagian, mt_karyawan.nama_pegawai, mt_perusahaan.nama_perusahaan, tc_pesanan.tgl_masuk, tc_pesanan.kode_tarif, tc_pesanan.diagnosa, tc_pesanan.keterangan, tc_pesanan.jam_pesanan, mt_master_tarif.nama_tarif';

	var $order = array('tc_pesanan.id_tc_pesanan' => 'DESC');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _main_query(){
		$this->db->select($this->select);
		$this->db->from('tc_pesanan');
		$this->db->join('mt_bagian', 'mt_bagian.kode_bagian=tc_pesanan.no_poli','inner');
		$this->db->join('mt_karyawan', 'mt_karyawan.kode_dokter=tc_pesanan.kode_dokter','inner');
		$this->db->join('mt_master_pasien', 'mt_master_pasien.no_mr=tc_pesanan.no_mr','inner');
		$this->db->join('mt_perusahaan', 'mt_perusahaan.kode_perusahaan=mt_master_pasien.kode_perusahaan','left');
		$this->db->join('mt_master_tarif', 'mt_master_tarif.kode_tarif=tc_pesanan.kode_tarif','left');
		$this->db->where('tc_pesanan.tgl_masuk IS NULL AND tc_pesanan.flag IS NOT NULL');
		$this->db->where('CONVERT(VARCHAR(11),tc_pesanan.tgl_pesanan,102) >= CONVERT(VARCHAR(11),getdate(),102) ');


		/*if isset parameter*/
		
		if(isset($_GET['search_by']) AND isset($_GET['keyword'])){
			$this->db->like('tc_pesanan.'.$_GET['search_by'].'', $_GET['keyword']);
		}

		if(isset($_GET['klinik'])){
			if($_GET['klinik']!='' or $_GET['klinik']!=0){
				$this->db->where('tc_pesanan.no_poli', (int)$_GET['klinik']);
			}
		}

		if(isset($_GET['dokter']) AND $_GET['dokter']!=0 ){
			if($_GET['dokter']!='' or $_GET['dokter']!=0){
				$this->db->where('tc_pesanan.kode_dokter', $_GET['dokter']);
			}
		}

		if (isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' or isset($_GET['to_tgl']) AND $_GET['to_tgl'] != '') {
            $this->db->where("tc_pesanan.tgl_pesanan >= '".$this->tanggal->selisih($_GET['from_tgl'],'-1')."'" );
            $this->db->where("tc_pesanan.tgl_pesanan <= '".$this->tanggal->selisih($_GET['to_tgl'],'+1')."'" );
        }
        /*end parameter*/


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
		$query = $this->db->get(); //print_r($this->db->last_query());die;
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


}
