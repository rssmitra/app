<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Kiosk_model extends CI_Model {

	var $table = 'tr_jadwal_dokter';
	var $column = array('mt_bagian.nama_bagian','mt_karyawan.nama_pegawai');
	var $select = 'tr_jadwal_dokter.jd_id,tr_jadwal_dokter.jd_kode_spesialis,tr_jadwal_dokter.jd_kode_dokter,tr_jadwal_dokter.jd_jam_mulai,tr_jadwal_dokter.jd_jam_selesai,mt_bagian.nama_bagian,mt_karyawan.nama_pegawai, jd_kuota,status_jadwal, status_loket, keterangan, jd_keterangan';
	var $order = array('tr_jadwal_dokter.jd_jam_mulai' => 'ASC');
	

	public function __construct()
	{
		parent::__construct();
	}

	private function _main_query(){

		$day = $this->tanggal->getHari(date('D'));

		$this->db->select("(select SUM(id) from log_kuota_dokter where kode_dokter=tr_jadwal_dokter.jd_kode_dokter 
							and kode_spesialis=tr_jadwal_dokter.jd_kode_spesialis and tanggal='".date('Y-m-d')."') as kuota_terpenuhi");
		$this->db->select($this->select);
		$this->db->from($this->table);
		$this->db->join('mt_bagian',''.$this->table.'.jd_kode_spesialis=mt_bagian.kode_bagian','left');
		$this->db->join('mt_karyawan',''.$this->table.'.jd_kode_dokter=mt_karyawan.kode_dokter','left');
		$this->db->where('jd_hari', $day);

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
		//print_r($this->db->last_query());
		return $query->result();
	}

	function get_datatables_display()
	{
		$this->_main_query();
		$order = $this->order;
		$this->db->order_by(key($order), $order[key($order)]);
		$query = $this->db->get();
		//print_r($this->db->last_query());
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
		$this->_main_query();
		if(is_array($id)){
			$this->db->where_in(''.$this->table.'.jd_id',$id);
			$query = $this->db->get();
			return $query->result();
		}else{
			$this->db->where(''.$this->table.'.jd_id',$id);
			$query = $this->db->get();
			return $query->row();
		}
		
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
		$this->db->where_in(''.$this->table.'.jd_id', $id);
		return $this->db->update($this->table, array('is_deleted' => 'Y', 'is_active' => 'N'));
	}

	public function get_loket()
	{
		$query="select ant_loket from tr_antrian where ant_loket is not null and ant_loket<>0 group by ant_loket";
		$exc = $this->db->query($query);
        return $exc->num_rows();
	}

	public function get_antrian_by_loket($loket)
	{
		# code...
		$cek = $this->db->query('select TOP 1 ant_no,ant_type from tr_antrian where ant_aktif=1 and ant_loket='.$loket.' order by  ant_id desc')->row();
		// print_r($cek);
		if(isset($cek)){
			return array('ant_no' => $cek->ant_no, 'ant_type' => $cek->ant_type);
		} else {
			$no_antrian = $this->db->query('select TOP 1 ant_no,ant_type from tr_antrian where ant_aktif=2 and ant_loket='.$loket.' order by ant_no desc')->row();
        	return array('ant_no' => isset($no_antrian->ant_no)?$no_antrian->ant_no:0, 'ant_type' => isset($no_antrian->ant_type)?$no_antrian->ant_type:0);
		}
		
	}

	public function get_antrian_farmasi()
	{
		# code...
		$query = "select * from fr_tc_far where CAST(tgl_trans as DATE) = '".date('Y-m-d')."' and status_terima not in(1,2) and resep_diantar != 'Y' order by kode_trans_far ASC";
		// $query = "select * from fr_tc_far where CAST(tgl_trans as DATE) = '2021-04-12' and status_terima not in(1,2) and flag_trans='RJ' order by kode_trans_far ASC";
		$result = $this->db->query($query);
		$no=0;
		$num = [];
		$getData = [];
		foreach($result->result() as $row){
			$no++;
			$num[$no] = array('nama_pasien' => $row->nama_pasien, 'kode_trans_far' => $row->kode_trans_far);
		}
		return $num;
		
	}

	public function getRegisterPasienByCurrentDate($no_mr){
		$this->db->select('tc_registrasi.no_mr, no_registrasi, CAST(tgl_jam_masuk as DATE) as tgl_jam_masuk, nama_pasien, mt_bagian.nama_bagian, nama_pegawai as nama_dokter ');
		$this->db->where("CAST(tgl_jam_masuk as DATE) = '".date('Y-m-d')."'");
		$this->db->where(array('tc_registrasi.no_mr'=> $no_mr));
		$this->db->join('mt_bagian', 'mt_bagian.kode_bagian=tc_registrasi.kode_bagian_masuk', 'left');
		$this->db->join('mt_dokter_v', 'mt_dokter_v.kode_dokter=tc_registrasi.kode_dokter', 'left');
		$this->db->join('mt_master_pasien', 'mt_master_pasien.no_mr=tc_registrasi.no_mr', 'left');
		$this->db->from('tc_registrasi');
		return $this->db->get()->row();
	}

	
}
