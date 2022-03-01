<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Perjanjian_rj_model extends CI_Model {

	var $table = 'tc_pesanan';
	var $column = array('tc_pesanan.nama','mt_bagian.nama_bagian','mt_karyawan.nama_pegawai','mt_perusahaan.nama_perusahaan');
	var $select = 'tc_pesanan.id_tc_pesanan, tc_pesanan.nama, tc_pesanan.tgl_pesanan, tc_pesanan.no_mr, mt_bagian.nama_bagian, mt_karyawan.nama_pegawai, mt_perusahaan.nama_perusahaan, tc_pesanan.tgl_masuk, tc_pesanan.kode_dokter, tc_pesanan.no_poli, tc_pesanan.kode_perjanjian, tc_pesanan.unique_code_counter, tc_pesanan.selected_day, tc_pesanan.no_telp, tc_pesanan.no_hp, tc_pesanan.keterangan, mt_master_pasien.tlp_almt_ttp, mt_master_pasien.no_hp as no_hp_pasien, no_kartu_bpjs, input_tgl, tc_pesanan.jd_id, is_bridging';

	var $order = array('tc_pesanan.tgl_pesanan' => 'DESC', 'tc_pesanan.id_tc_pesanan' => 'DESC');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _main_query(){
		$year_limit = date('Y') - 2;
		$this->db->select($this->select);
		$this->db->select('(Select top 1 no_sep from tc_registrasi where no_mr=tc_pesanan.no_mr AND kode_perusahaan=120 order by no_registrasi DESC) as no_sep, fullname as petugas');
		$this->db->from('tc_pesanan');
		$this->db->join('mt_bagian', 'mt_bagian.kode_bagian=tc_pesanan.no_poli','inner');
		$this->db->join('mt_karyawan', 'mt_karyawan.kode_dokter=tc_pesanan.kode_dokter','left');
		$this->db->join('tmp_user', 'tmp_user.user_id=tc_pesanan.no_induk','left');
		$this->db->join('mt_master_pasien', 'mt_master_pasien.no_mr=tc_pesanan.no_mr','left');
		$this->db->join('mt_perusahaan', 'mt_perusahaan.kode_perusahaan=tc_pesanan.kode_perusahaan','left');
		$this->db->where('tc_pesanan.tgl_masuk IS NULL');

		if(isset($_GET['no_mr']) AND $_GET['no_mr']!=0 ){
			if($_GET['no_mr']!='' or $_GET['no_mr']!=0){
				$this->db->where('tc_pesanan.no_mr', $_GET['no_mr']);
			}
		}
		
		/*if isset parameter*/
		if(isset($_GET['search_by'])) {

			if(isset($_GET['flag']) AND $_GET['flag']=='bedah'){
				$this->db->where('tc_pesanan.flag', $_GET['flag']);

			}else if(isset($_GET['flag']) AND $_GET['flag']=='HD'){
				$this->db->where('tc_pesanan.flag', $_GET['flag']);
				
			}else{
				$this->db->where('tc_pesanan.flag IS NULL');
			}

			if(isset($_GET['keyword']) AND $_GET['keyword'] != ''){
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
				$this->db->where("CAST(tc_pesanan.tgl_pesanan as DATE) >= '".$_GET['from_tgl']."'" );
				$this->db->where("CAST(tc_pesanan.tgl_pesanan as DATE) <= '".$_GET['to_tgl']."'" );
			}

			if (isset($_GET['tgl_input_prj']) AND $_GET['tgl_input_prj'] != '') {
				$this->db->where("CAST(tc_pesanan.input_tgl as DATE) = '".$_GET['tgl_input_prj']."'" );
			}
			
		}else {
			$this->db->where("CAST(tc_pesanan.input_tgl as DATE) = '".date('Y-m-d')."'" );
			// $this->db->where('DAY(tgl_pesanan) >= '.date('d').'');	
			// $this->db->where('MONTH(tgl_pesanan) >= '.date('m').'');	
			// $this->db->where('YEAR(tgl_pesanan) ='.date('Y').'');
		}


        // if (isset($_GET['tanggal']) AND $_GET['tanggal'] != '' ) {
        //     $this->db->where("CAST(tc_pesanan.tgl_pesanan as DATE) = '".$_GET['tanggal']."'" );
		// }
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
		// print_r($this->db->last_query());die;
		return $query->result();
	}

	function get_data()
	{
		$this->_main_query();
		$query = $this->db->get(); 
		return $query;
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


}
