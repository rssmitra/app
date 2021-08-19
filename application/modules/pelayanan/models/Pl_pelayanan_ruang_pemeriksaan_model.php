<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pl_pelayanan_ruang_pemeriksaan_model extends CI_Model {

	var $table = 'tc_pesanan';
	var $column = array('tc_pesanan.nama','mt_bagian.nama_bagian','mt_karyawan.nama_pegawai','mt_perusahaan.nama_perusahaan');
	var $select = 'tc_pesanan.id_tc_pesanan, tc_pesanan.nama, tc_pesanan.tgl_pesanan, tc_pesanan.no_mr, mt_bagian.nama_bagian, mt_karyawan.nama_pegawai, mt_perusahaan.nama_perusahaan, tc_pesanan.tgl_masuk, tc_pesanan.kode_dokter, tc_pesanan.no_poli, tc_pesanan.kode_perjanjian, tc_pesanan.unique_code_counter, tc_pesanan.selected_day, tc_pesanan.no_telp, tc_pesanan.no_hp, mt_master_pasien.tlp_almt_ttp, mt_master_pasien.no_hp as no_hp_pasien, mt_master_tarif.nama_tarif, bulan_kunjungan, status_konfirmasi_kedatangan, tc_pesanan.keterangan, referensi_no_kunjungan, tc_pesanan.kode_perusahaan';
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
		$this->db->where('CAST(tc_pesanan.tgl_pesanan as DATE) = ', date('Y-m-d'));
		$this->db->where('tc_pesanan.no_poli', $_GET['bag']);
        /*end parameter*/

	}

	private function _get_datatables_query()
	{
		
		$this->_main_query();

		if(isset($_GET['no_mr']) AND $_GET['no_mr']!=0 ){
			if($_GET['no_mr']!='' or $_GET['no_mr']!=0){
				$this->db->where('tc_pesanan.no_mr', $_GET['no_mr']);
			}
		}

		if(isset($_GET['keyword']) AND $_GET['keyword'] != ''){
			$this->db->like('tc_pesanan.'.$_GET['search_by'].'', $_GET['keyword']);
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

        if (isset($_GET['tanggal']) AND $_GET['tanggal'] != '' ) {
            $this->db->where("CAST(tc_pesanan.tgl_pesanan as DATE) = '".$this->tanggal->sqlDateForm($_GET['tanggal'])."'" );
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
		$this->db->where_in(''.$this->table.'.id_tc_pesanan', $id);
		return $this->db->delete($this->table);
	}


	function get_data_antrian_pasien()
	{
		$this->_main_query();
		// $this->db->where('tgl_keluar_poli IS NULL');
		if( in_array($_GET['bag'], array('050201') ) ) {
			$this->db->where('tc_pesanan.no_poli='."'".$_GET['bag']."'".'');
		}else{
			$this->db->where('tc_pesanan.kode_bagian='."'".$this->session->userdata('kode_bagian')."'".'');
			$this->db->where('tc_pesanan.kode_dokter='."'".$this->session->userdata('sess_kode_dokter')."'".'');
		}

		if (isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' || isset($_GET['to_tgl']) AND $_GET['to_tgl'] != '') {
			$this->db->where("convert(varchar,tc_pesanan.tgl_pesanan,23) between '".$_GET['from_tgl']."' and '".$_GET['to_tgl']."'");					
        }else{
        	$this->db->where('CAST(tgl_pesanan as DATE) = ', date('Y-m-d'));
		}
        $this->db->order_by('input_tgl','ASC');
		$query = $this->db->get();
		return $query->result();
	}

	function get_next_antrian_pasien()
	{
		$this->_main_query();
		$this->db->where('tgl_masuk IS NULL');
		$this->db->where('(tc_pesanan.status_batal is null or tc_pesanan.status_batal = 0)');
		$this->db->where('tc_pesanan.kode_bagian='."'".$this->session->userdata('kode_bagian')."'".'');
		$this->db->where('tc_pesanan.kode_dokter='."'".$this->session->userdata('sess_kode_dokter')."'".'');
		$this->db->where(array('YEAR(tc_pesanan.tgl_pesanan)' => date('Y'), 'MONTH(tc_pesanan.tgl_pesanan)' => date('m'), 'DAY(tc_pesanan.tgl_pesanan)' => date('d') ) );
        $this->db->order_by('no_antrian','ASC');
		$query = $this->db->get();
		return $query->row();
	}

	public function get_pemeriksaan($no_kunjungan)
	{
		$this->db->select('pm_pemeriksaanpasien_v.*, a.kode_mt_hasilpm, b.hasil');
		$this->db->from('pm_pemeriksaanpasien_v');
		$this->db->join('pm_mt_standarhasil a','pm_pemeriksaanpasien_v.kode_tarif=a.kode_tarif','left');
		$this->db->join('pm_tc_hasilpenunjang b','b.kode_trans_pelayanan=pm_pemeriksaanpasien_v.kode_trans_pelayanan','left');
		$this->db->where( 'pm_pemeriksaanpasien_v.no_kunjungan',$no_kunjungan );
		$this->db->order_by('pm_pemeriksaanpasien_v.kode_trans_pelayanan','ASC');
		$query = $this->db->get()->result();
		// echo '<pre>';print_r($this->db->last_query());die;
		return $query;
		
	}



}
