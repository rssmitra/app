<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Antrian_model extends CI_Model {

	var $table = 'tr_jadwal_dokter';
	var $column = array('mt_bagian.nama_bagian','mt_karyawan.nama_pegawai');
	var $select = 'tr_jadwal_dokter.jd_id,tr_jadwal_dokter.jd_kode_spesialis,tr_jadwal_dokter.jd_kode_dokter,tr_jadwal_dokter.jd_jam_mulai,tr_jadwal_dokter.jd_jam_selesai,mt_bagian.nama_bagian,mt_karyawan.nama_pegawai, jd_kuota,status_jadwal, sisa_kuota, status_loket';
	var $order = array('tr_jadwal_dokter.jd_jam_mulai' => 'ASC');
	

	public function __construct()
	{
		parent::__construct();
	}

	private function _main_query(){

		$day = $this->tanggal->getHariFromDate(date('D'));

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

	public function get_open_loket($day)
	{
		$this->db->select("a.jd_id, a.jd_kode_spesialis, b.nama_bagian, a.jd_kode_dokter,c.nama_pegawai, a.jd_jam_mulai, a.jd_jam_selesai");
		$this->db->from('tr_jadwal_dokter a');
		$this->db->join('mt_bagian b', 'b.kode_bagian=a.jd_kode_spesialis', 'left');
		$this->db->join('mt_karyawan c', 'c.kode_dokter=a.jd_kode_dokter', 'left');
		$this->db->where('a.jd_hari', $day);
		$this->db->order_by('a.jd_jam_mulai', 'ASC');
		$data = $this->db->get()->result();

		$getData = array();

		foreach ($data as $key => $value) {
			$time = ($this->tanggal->formatTime($value->jd_jam_mulai)=='-')?'-':$this->tanggal->formatTime($value->jd_jam_mulai);
		 	$getData[$time] = $this->get_loket_klinik($value->jd_jam_mulai, $data); 
		} 
		/*echo '<pre>';print_r($getData);die;*/
		return $getData; 
	}

	public function get_loket_klinik($time, $data){

		$getData = array();

		foreach ($data as $key => $value) {
			if($time==$value->jd_jam_mulai){
				$getData[] = $value;
			}
		}
		return $getData;
	}

	function proses_update_loket($data){
		if($data->status_loket == 'off'){
			$this->db->update('tr_jadwal_dokter', array('status_loket' => 'on', 'status_jadwal' => 'Loket dibuka'), array('jd_id' => $data->jd_id) );
		}else{
			$this->db->update('tr_jadwal_dokter', array('status_loket' => 'off', 'status_jadwal' => 'Loket ditutup'), array('jd_id' => $data->jd_id) );
		}

		return true;
	}

	function get_sisa_kuota($data){
		/*get data from registrasi*/
		$result = $this->db->get_where('tc_registrasi', array('tgl_jam_masuk' => date('Y-m-d'), 'kode_dokter' => $data->jd_kode_dokter, 'kode_bagian_masuk' => $data->jd_kode_spesialis ) )->num_rows();
		/*sisa*/
		$sisa = $data->jd_kuota - $result;

		return $sisa;
	}

	public function get_booked($kode)
    {
        # code...
       
        $qry = "SELECT regon_booking.regon_booking_waktu_datang, regon_booking.regon_booking_kode,regon_booking.regon_booking_tanggal_perjanjian, regon_booking.regon_booking_no_mr, regon_booking.regon_booking_klinik, regon_booking.regon_booking_kode_dokter, regon_booking.regon_booking_hari, regon_booking.regon_booking_jam, regon_booking.regon_booking_penjamin, regon_booking.regon_booking_status, mt_bagian.nama_bagian,p1.nama_pasien,p2.nama_pegawai FROM regon_booking
        LEFT JOIN mt_master_pasien p1 ON p1.no_mr=regon_booking.regon_booking_no_mr
        LEFT JOIN mt_karyawan p2 ON p2.kode_dokter=regon_booking.regon_booking_kode_dokter
        LEFT JOIN mt_bagian on mt_bagian.kode_bagian=regon_booking.regon_booking_klinik
        where regon_booking.regon_booking_kode='$kode' order by created_date DESC";
        return $this->db->query($qry)->row();
        //$qry = $this->db->get_where('regon_booking', array('regon_booking_keterangan' => $no_mr));
        //return $qry->result();
    }

	public function cek_kode_perjanjian($keyword, $kode_dokter, $kode_poli)
    {
        $this->db->select('CAST(tgl_pesanan as DATE) as tgl_pesanan, tc_pesanan.no_mr, no_ktp, kode_perjanjian, unique_code_counter, nama_pasien, kode_dokter, no_poli, status_konfirmasi_kedatangan');
        $this->db->from('tc_pesanan');
        $this->db->join('mt_master_pasien', 'mt_master_pasien.no_mr=tc_pesanan.no_mr', 'left');
		$this->db->order_by('id_tc_pesanan', 'DESC');
		$this->db->where("CAST(tgl_pesanan as DATE) = '".date('Y-m-d')."'");
        $this->db->where("(unique_code_counter = '".$keyword."' OR no_ktp = '".$keyword."' OR mt_master_pasien.no_mr='".$keyword."') ");
        return $this->db->get();
    }


}
