<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Input_perjanjian_pm_model extends CI_Model {

	var $table = 'tc_pesanan';
	var $column = array('tc_pesanan.nama','mt_bagian.nama_bagian','mt_karyawan.nama_pegawai','mt_perusahaan.nama_perusahaan');
	var $select = 'tc_pesanan.id_tc_pesanan, tc_pesanan.nama, tc_pesanan.tgl_pesanan, tc_pesanan.no_mr, mt_bagian.nama_bagian, mt_karyawan.nama_pegawai, mt_perusahaan.nama_perusahaan, tc_pesanan.tgl_masuk, tc_pesanan.kode_dokter, tc_pesanan.no_poli, tc_pesanan.kode_perjanjian, tc_pesanan.unique_code_counter, tc_pesanan.selected_day, tc_pesanan.no_telp, tc_pesanan.no_hp, mt_master_pasien.tlp_almt_ttp, mt_master_pasien.no_hp as no_hp_pasien, mt_master_tarif.nama_tarif, bulan_kunjungan, status_konfirmasi_kedatangan, tc_pesanan.keterangan, referensi_no_kunjungan';
	var $order = array('tc_pesanan.id_tc_pesanan' => 'ASC');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _main_query(){
		$year_limit = date('Y') - 2;
		$this->db->select($this->select);
		$this->db->from('tc_pesanan');
		$this->db->join('mt_bagian', 'mt_bagian.kode_bagian=tc_pesanan.no_poli','inner');
		$this->db->join('mt_karyawan', 'mt_karyawan.kode_dokter=tc_pesanan.kode_dokter','left');
		$this->db->join('mt_master_pasien', 'mt_master_pasien.no_mr=tc_pesanan.no_mr','left');
		$this->db->join('mt_perusahaan', 'mt_perusahaan.kode_perusahaan=tc_pesanan.kode_perusahaan','left');
		$this->db->join('mt_master_tarif', 'mt_master_tarif.kode_tarif=tc_pesanan.kode_tarif','left');
		$this->db->where('tc_pesanan.tgl_masuk IS NULL');
		$this->db->where('tc_pesanan.no_poli','50201');
        /*end parameter*/

	}

	private function _get_datatables_query()
	{
		
		$this->_main_query();

		if(isset($_GET['no_mr']) AND $_GET['no_mr']!=0 ){
			
		}

		if(isset($_GET['keyword']) AND $_GET['keyword'] != ''){
			if($_GET['no_mr']!='' or $_GET['no_mr']!=0){
				$this->db->where('tc_pesanan.'.$_GET['search_by'].'', $_GET['keyword']);
			}else{
				$this->db->like('tc_pesanan.'.$_GET['search_by'].'', $_GET['keyword']);
			}
			
		}

		if(isset($_GET['dokter']) AND $_GET['dokter'] != '' ){
			$this->db->where('tc_pesanan.kode_dokter', $_GET['dokter']);
		}

        if (isset($_GET['bulan']) AND $_GET['bulan'] != '' ) {
            $this->db->where("bulan_kunjungan = '".$_GET['bulan']."'" );
		}
		else{
        	$this->db->where('MONTH(input_tgl) >= '.date('m').'');	
			$this->db->where('YEAR(input_tgl)='.date('Y').'');
		}
		
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
		// print_r($this->db->last_query());die;
		return $query->result();
	}

	function get_data()
	{
		$this->_main_query();
		$query = $this->db->get(); 
		return $query;
	}

	public function get_by_id($id)
	{
		$this->_main_query();
		if(is_array($id)){
			$this->db->where_in(''.$this->table.'.id_tc_pesanan',$id);
			$query = $this->db->get();
			return $query->result();
		}else{
			$this->db->where(''.$this->table.'.id_tc_pesanan',$id);
			$query = $this->db->get();
			return $query->row();
		}		
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
		$get_data = $this->get_by_id($id);
		$this->db->where_in(''.$this->table.'.id_tc_pesanan', $id);
		return $this->db->delete($this->table);
	}


}
