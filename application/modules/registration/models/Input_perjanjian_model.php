<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Input_perjanjian_model extends CI_Model {

	var $table = 'tc_pesanan';
	var $column = array('tc_pesanan.nama','mt_bagian.nama_bagian','mt_karyawan.nama_pegawai','mt_perusahaan.nama_perusahaan');
	var $select = 'tc_pesanan.id_tc_pesanan, tc_pesanan.nama, tc_pesanan.tgl_pesanan, tc_pesanan.no_mr, mt_bagian.nama_bagian, mt_karyawan.nama_pegawai, mt_perusahaan.nama_perusahaan, tc_pesanan.tgl_masuk, tc_pesanan.kode_dokter, tc_pesanan.no_poli, tc_pesanan.kode_perjanjian, tc_pesanan.unique_code_counter, tc_pesanan.selected_day, tc_pesanan.no_telp, tc_pesanan.no_hp, mt_master_pasien.tlp_almt_ttp, mt_master_pasien.no_hp as no_hp_pasien, mt_master_tarif.nama_tarif';

	var $order = array('tc_pesanan.id_tc_pesanan' => 'DESC');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _main_query(){
		$this->db->select($this->select);
		$this->db->from('tc_pesanan');
		$this->db->join('mt_bagian', 'mt_bagian.kode_bagian=tc_pesanan.no_poli','left');
		$this->db->join('mt_karyawan', 'mt_karyawan.kode_dokter=tc_pesanan.kode_dokter','left');
		$this->db->join('mt_master_pasien', 'mt_master_pasien.no_mr=tc_pesanan.no_mr','left');
		$this->db->join('mt_perusahaan', 'mt_perusahaan.kode_perusahaan=tc_pesanan.kode_perusahaan','left');
		$this->db->join('mt_master_tarif', 'mt_master_tarif.kode_tarif=tc_pesanan.kode_tarif','left');
		if( isset($_GET['kode_bagian']) AND $_GET['kode_bagian'] != '' ){
			$this->db->where('tc_pesanan.no_poli', $_GET['kode_bagian']);
		}else{
			$this->db->where('tc_pesanan.no_mr IS NULL');
		}
		$this->db->where('DAY(input_tgl)='.date('d').'');	
		$this->db->where('MONTH(input_tgl)='.date('m').'');	
		$this->db->where('YEAR(input_tgl)='.date('Y').'');

        /*end parameter*/

	}

	private function _get_datatables_query()
	{
		
		$this->_main_query();

		$i = 0;
	
		foreach ($this->column as $item) 
		{
			if( $_POST['search']['value'] )
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
		//print_r($this->db->last_query());die;
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
		$this->db->where_in(''.$this->table.'.id_tc_pesanan', $id);
		return $this->db->delete($this->table);
	}

}
