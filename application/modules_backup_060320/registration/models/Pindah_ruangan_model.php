
<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pindah_ruangan_model extends CI_Model {

	var $table = 'ri_tc_rawatinap';
	var $column = array('mt_bagian.nama_bagian','mt_karyawan.nama_pegawai','mt_perusahaan.nama_perusahaan');
	var $select = 'ri_tc_rawatinap.*, mt_master_pasien.nama_pasien, tc_kunjungan.no_mr, tc_kunjungan.no_registrasi, tc_registrasi.kode_kelompok, tc_registrasi.kode_perusahaan, tc_kunjungan.no_kunjungan, mt_klas.nama_klas, mt_bagian.nama_bagian, mt_karyawan.nama_pegawai, mt_perusahaan.nama_perusahaan, ri_tc_rawatinap.kode_ruangan';

	var $order = array('ri_tc_rawatinap.kode_ri' => 'DESC');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _main_query(){
		$this->db->select($this->select);
		$this->db->from('ri_tc_rawatinap');
		$this->db->join('mt_bagian', 'mt_bagian.kode_bagian=ri_tc_rawatinap.bag_pas','inner');
		$this->db->join('mt_karyawan', 'mt_karyawan.kode_dokter=ri_tc_rawatinap.dr_merawat','inner');
		$this->db->join('tc_kunjungan', 'tc_kunjungan.no_kunjungan=ri_tc_rawatinap.no_kunjungan','inner');
		$this->db->join('tc_registrasi', 'tc_kunjungan.no_registrasi=tc_registrasi.no_registrasi','inner');
		$this->db->join('mt_master_pasien', 'mt_master_pasien.no_mr=tc_kunjungan.no_mr','inner');
		$this->db->join('mt_klas', 'mt_klas.kode_klas=ri_tc_rawatinap.kelas_pas','inner');
		$this->db->join('mt_perusahaan', 'mt_perusahaan.kode_perusahaan=mt_master_pasien.kode_perusahaan','left');
		$this->db->where('ri_tc_rawatinap.tgl_keluar IS NULL');
		$this->db->where('ri_tc_rawatinap.status_pindah = 1');


		/*if isset parameter*/
		
		if(isset($_GET['search_by']) AND isset($_GET['keyword'])){
			$this->db->like('ri_tc_rawatinap.'.$_GET['search_by'].'', $_GET['keyword']);
		}

		if(isset($_GET['klinik'])){
			if($_GET['klinik']!='' or $_GET['klinik']!=0){
				$this->db->where('ri_tc_rawatinap.no_poli', (int)$_GET['klinik']);
			}
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

	public function save($table, $data)
	{
		/*insert tc_registrasi*/
		$this->db->insert($table, $data);
		
		return $this->db->insert_id();;
	}

	public function update($table, $data, $where)
	{
		$this->db->update($table, $data, $where);
		return $this->db->affected_rows();
	}

	public function get_by_id($id)
	
	{
		
		$this->_main_query();
		
		if(is_array($id)){
			
			$this->db->where_in(''.$this->table.'.kode_ri',$id);
			
			$query = $this->db->get();
			
			return $query->result();
		
		}else{
			
			$this->db->where(''.$this->table.'.kode_ri',$id);
			
			$query = $this->db->get();
			
			return $query->row();
		
		}
	
	}


}
