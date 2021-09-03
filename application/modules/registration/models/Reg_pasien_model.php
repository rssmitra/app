<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Reg_pasien_model extends CI_Model {

	var $table = 'mt_master_pasien';

	var $column = array('mt_master_pasien.no_mr','mt_master_pasien.no_ktp','mt_master_pasien.nama_pasien','mt_master_pasien.tgl_lhr','mt_master_pasien.tempat_lahir','mt_master_pasien.almt_ttp_pasien','mt_master_pasien.tlp_almt_ttp', 'mt_master_pasien.jen_kelamin', 'mt_master_pasien.no_hp', 'mt_master_pasien.title', 'mt_master_pasien.status_meninggal', 'mt_master_pasien.kode_perusahaan','mt_perusahaan.nama_perusahaan','no_kartu_bpjs');

	var $select = 'mt_master_pasien.no_mr,mt_master_pasien.no_ktp,mt_master_pasien.nama_pasien,mt_master_pasien.tgl_lhr,mt_master_pasien.tempat_lahir,mt_master_pasien.almt_ttp_pasien,mt_master_pasien.pekerjaan_ayah,mt_master_pasien.nama_ayah,mt_master_pasien.nama_ibu,mt_master_pasien.tlp_almt_ttp,mt_master_pasien.jen_kelamin,mt_master_pasien.no_hp,mt_master_pasien.title,mt_master_pasien.status_meninggal,mt_master_pasien.kode_perusahaan,mt_perusahaan.nama_perusahaan,no_kartu_bpjs, mt_master_pasien.nama_panggilan, mt_master_pasien.nama_kel_pasien, mt_master_pasien.no_ktp, mt_master_pasien.almt_ttp_pasien, mt_master_pasien.id_dc_propinsi, mt_master_pasien.id_dc_kota, mt_master_pasien.id_dc_kecamatan, mt_master_pasien.id_dc_kelurahan, mt_master_pasien.id_dc_agama, mt_master_pasien.id_dc_kawin, villages_new.name as kelurahan, districts.name as kecamatan, regencies.name as kota, provinces.name as provinsi, mt_master_pasien.kode_pos, mt_master_pasien.kode_kelompok, mt_nasabah.nama_kelompok, mt_master_pasien.url_foto_pasien, mt_master_pasien.keterangan, mst_religion.religion_name, mst_marital_status.ms_name, ttd';

	var $order = array('mt_master_pasien.nama_pasien' => 'ASC');

	public function __construct()
	
	{
		
		parent::__construct();
	
	}

	private function _main_query($params=''){
		
		$this->db->select($this->select);
		
		$this->db->from($this->table);

		$this->db->join('mst_marital_status','mst_marital_status.ms_id=mt_master_pasien.id_dc_kawin','left');
		$this->db->join('mst_religion','mst_religion.religion_id=mt_master_pasien.id_dc_agama','left');
		$this->db->join('mt_perusahaan','mt_perusahaan.kode_perusahaan=mt_master_pasien.kode_perusahaan','left');
		$this->db->join('mt_nasabah','mt_nasabah.kode_kelompok=mt_master_pasien.kode_kelompok','left');
		$this->db->join('districts','districts.id=mt_master_pasien.id_dc_kecamatan','left');
		$this->db->join('regencies','regencies.id=mt_master_pasien.id_dc_kota','left');
		$this->db->join('provinces','provinces.id=mt_master_pasien.id_dc_propinsi','left');
		$this->db->join('villages_new','villages_new.id=mt_master_pasien.id_dc_kelurahan','left');
		
		/*check level user*/
		if(isset($this->session->userdata('user')->user_id)){
			$this->authuser->filtering_data_by_level_user($this->table, $this->session->userdata('user')->user_id);
		}
	
	}

	private function _get_datatables_query($params='')
	
	{
		
		$this->_main_query();

		$i = 0;

		foreach ($this->column as $item) 
		
		{
			
			if($_POST['search']['value'])
				($i===0) ? $this->db->like($item, $_POST['search']['value']) : $this->db->or_like($item
					, $_POST['search']['value']);
			
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

	function get_datatables($params='')
	
	{
		
		$this->_get_datatables_query();
		
		if($_POST['length'] != -1)
		
		$this->db->limit($_POST['length'], $_POST['start']);
		
		$query = $this->db->get();
		
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
			
			$this->db->where_in(''.$this->table.'.id_mt_master_pasien',$id);
			
			$query = $this->db->get();
			
			return $query->result();
		
		}else{
			
			$this->db->where(''.$this->table.'.id_mt_master_pasien',$id);
			
			$query = $this->db->get();
			
			return $query->row();
		
		}
	
	}

	public function get_by_mr($mr)
	
	{
		
		$this->_main_query();
		
		$this->db->where(''.$this->table.'.no_mr', $mr);
			
		$query = $this->db->get();
		
		return $query->row();
	
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
		
		$this->db->where_in(''.$this->table.'.id_mt_master_pasien', $id);
		
		return $this->db->update($this->table, array('is_deleted' => 'Y', 'is_active' => 'N'));
	
	}

	public function search_pasien_by_keyword($keyword, $field)
	
	{
		
		$this->_main_query();

		$i = 0;

		foreach ($field as $item) 
		
		{

			if($keyword)
				($i===0) ? $this->db->like($item, $keyword) : $this->db->or_like($item
					, $keyword);
			
			$column[$i] = $item;
			
			$i++;
		
		}

		if(isset($this->order))
		
		{
			
			$order = $this->order;
			
			$this->db->order_by(key($order), $order[key($order)]);
		
		}


		return $this->db->get()->result();

	}

	public function search_pasien_by_mr($keyword, $field)
	
	{
		
		$this->_main_query();

		$i = 0;

		foreach ($field as $item) 
		
		{

			if($keyword)
				($i===0) ? $this->db->where($item, $keyword) : $this->db->or_where($item
					, $keyword);
			
			$column[$i] = $item;
			
			$i++;
		
		}

		if(isset($this->order))
		
		{
			
			$order = $this->order;
			
			$this->db->order_by(key($order), $order[key($order)]);
		
		}


		return $this->db->get()->result();

	}

	public function query_get_riwayat_pasien($select, $column, $mr)
	
	{
		$this->db->select($select);

		$this->db->from( 'tc_kunjungan' );

		$this->db->join('tc_registrasi','tc_kunjungan.no_registrasi=tc_registrasi.no_registrasi','left');

		$this->db->join('mt_dokter_v','mt_dokter_v.kode_dokter=tc_kunjungan.kode_dokter','left');

		$this->db->join('mt_bagian','mt_bagian.kode_bagian=tc_kunjungan.kode_bagian_tujuan','left');
		
		$this->db->join('mt_master_pasien','mt_master_pasien.no_mr=tc_kunjungan.no_mr','left');

		$this->db->join('mt_perusahaan','mt_perusahaan.kode_perusahaan=tc_registrasi.kode_perusahaan','left');

		$this->db->where( 'tc_kunjungan.no_mr', $mr );
	
		if(isset($_GET['kode_bagian']) AND $_GET['kode_bagian'] != ''){
			
			$this->db->where( 'tc_kunjungan.kode_bagian_asal', $_GET['kode_bagian']);

			$this->db->where( 'SUBSTRING("tc_kunjungan"."kode_bagian_tujuan", 1,2) = '.'05'.'');

		}else if(isset($_GET['no_reg']) AND $_GET['no_reg'] != ''){
			
			$this->db->where( 'tc_kunjungan.no_registrasi', $_GET['no_reg']);

			if(isset($_GET['tujuan']) AND $_GET['tujuan'] == 'pm'){

				$this->db->where( 'SUBSTRING("tc_kunjungan"."kode_bagian_tujuan", 1,2) = '.'05'.'');
	
			}else if(isset($_GET['tujuan']) AND $_GET['tujuan'] == 'rajal'){
	
				$this->db->where( 'SUBSTRING("tc_kunjungan"."kode_bagian_tujuan", 1,2) = '.'01'.'');
	
			}
					
		}

		$this->db->group_by( $select );

		$this->db->order_by( 'tgl_masuk', 'DESC' );

	}

	private function _get_datatables_query_riwayat_pasien($select, $column, $mr)
	
	{
		
		$this->query_get_riwayat_pasien($select, $column, $mr);

		$i = 0;

		foreach ($column as $item) 
		
		{
			
			if($_POST['search']['value'])
				($i===0) ? $this->db->like($item, $_POST['search']['value']) : $this->db->or_like($item
					, $_POST['search']['value']);
			
			$column[$i] = $item;
			
			$i++;
		
		}

		foreach ($column as $item) 
		
		{
			
			if($_POST['search']['value'])
				($i===0) ? $this->db->like($item, $_POST['search']['value']) : $this->db->or_like($item
					, $_POST['search']['value']);
			
			$column[$i] = $item;
			
			$i++;
		
		}


	}

	function get_riwayat_pasien($column, $mr)
	
	{
		
		$this->_get_datatables_query_riwayat_pasien(join($column,','), $column, $mr);
		
		if($_POST['length'] != -1)
		
		$this->db->limit($_POST['length'], $_POST['start']);
		
		$query = $this->db->get(); //print_r($this->db->last_query());
		
		return $query->result();
	
	}

	function count_filtered_riwayat_pasien($column, $mr)
	
	{
		
		$this->_get_datatables_query_riwayat_pasien(join($column,','), $column, $mr);
		
		$query = $this->db->get();
		
		return $query->num_rows();
	
	}

	function get_data_pasien_bpjs($params)
	
	{
		if(!empty($params->no_kartu_bpjs)){

			$service_name = "Peserta/nokartu/".$params->no_kartu_bpjs."/tglSEP/".$this->tanggal->sqlDateForm(date('Y-m-d'))."";

			$response_ws = $this->Ws_index->getData($service_name);
			
			return $response_ws;
			
		}else{

			return false;

		}
	
	}

	public function query_get_riwayat_transaksi_pasien($select, $column, $mr)
	
	{

		$this->db->select($select);

		$this->db->from( 'tc_trans_kasir' );

		$this->db->join('tc_registrasi','tc_registrasi.no_registrasi=tc_trans_kasir.no_registrasi','left');

		$this->db->join('mt_dokter_v','mt_dokter_v.kode_dokter=tc_registrasi.kode_dokter','left');

		$this->db->join('mt_bagian','mt_bagian.kode_bagian=tc_registrasi.kode_bagian_masuk','left');

		$this->db->join('mt_perusahaan','mt_perusahaan.kode_perusahaan=tc_registrasi.kode_perusahaan','left');

		$this->db->where( 'tc_registrasi.no_mr', $mr );

		$this->db->group_by( $select );

		$this->db->order_by( 'tgl_jam', 'DESC' );

	}

	private function _get_datatables_query_riwayat_transaksi_pasien($select, $column, $mr)
	
	{
		
		$this->query_get_riwayat_transaksi_pasien($select, $column, $mr);

		$i = 0;

		foreach ($column as $item) 
		{
			
			if($_POST['search']['value'])
				($i===0) ? $this->db->like($item, $_POST['search']['value']) : $this->db->or_like($item
					, $_POST['search']['value']);
			
			$column[$i] = $item;
			
			$i++;
		
		}
		

	}

	function get_riwayat_transaksi_pasien($column, $mr)
	
	{
		
		$this->_get_datatables_query_riwayat_transaksi_pasien(join($column,','), $column, $mr);
		
		if($_POST['length'] != -1)
		
		$this->db->limit($_POST['length'], $_POST['start']);
		
		$query = $this->db->get(); //print_r($this->db->last_query());die;
		
		return $query->result();
	
	}

	function count_filtered_riwayat_transaksi_pasien($column, $mr)
	
	{
		
		$this->_get_datatables_query_riwayat_transaksi_pasien(join($column,','), $column, $mr);
		
		$query = $this->db->get();
		
		return $query->num_rows();
	
	}


	public function query_get_riwayat_perjanjian_pasien($select, $column, $mr)
	
	{

		$year_limit = date('Y') - 2;
		$this->db->select($select);
		$this->db->from('tc_pesanan');
		$this->db->join('mt_bagian', 'mt_bagian.kode_bagian=tc_pesanan.no_poli','inner');
		$this->db->join('mt_karyawan', 'mt_karyawan.kode_dokter=tc_pesanan.kode_dokter','left');
		$this->db->join('mt_master_pasien', 'mt_master_pasien.no_mr=tc_pesanan.no_mr','inner');
		$this->db->join('mt_perusahaan', 'mt_perusahaan.kode_perusahaan=tc_pesanan.kode_perusahaan','left');
		/*$this->db->where('tc_pesanan.tgl_masuk IS NULL');*/
		
		/*if isset parameter*/
		if(isset($_GET['flag']) AND $_GET['flag']=='bedah'){
			$this->db->where('tc_pesanan.flag', $_GET['flag']);

		}else if(isset($_GET['flag']) AND $_GET['flag']=='HD'){
			$this->db->where('tc_pesanan.flag', $_GET['flag']);
		}else{
			$this->db->where('tc_pesanan.flag IS NULL');
		}

		$this->db->where('tc_pesanan.no_mr', $mr);
		$this->db->order_by('tgl_pesanan', 'DESC');

	}

	private function _get_datatables_query_riwayat_perjanjian_pasien($select, $column, $mr)
	
	{
		
		$this->query_get_riwayat_perjanjian_pasien($select, $column, $mr);

		$i = 0;

		foreach ($column as $item) 
		
		{
			
			if($_POST['search']['value'])
				($i===0) ? $this->db->like($item, $_POST['search']['value']) : $this->db->or_like($item
					, $_POST['search']['value']);
			
			$column[$i] = $item;
			
			$i++;
		
		}

		foreach ($column as $item) 
		
		{
			
			if($_POST['search']['value'])
				($i===0) ? $this->db->like($item, $_POST['search']['value']) : $this->db->or_like($item
					, $_POST['search']['value']);
			
			$column[$i] = $item;
			
			$i++;
		
		}


	}

	function get_riwayat_perjanjian($column, $mr)
	
	{
		
		$this->_get_datatables_query_riwayat_perjanjian_pasien(join($column,','), $column, $mr);
		
		if($_POST['length'] != -1)
		
		$this->db->limit($_POST['length'], $_POST['start']); 
		
		$query = $this->db->get(); 
		
		return $query->result();
	
	}

	function count_filtered_riwayat_perjanjian_pasien($column, $mr)
	
	{
		
		$this->_get_datatables_query_riwayat_perjanjian_pasien(join($column,','), $column, $mr);
		
		$query = $this->db->get();
		
		return $query->num_rows();
	
	}


	function cek_status_pasien($mr)
	
	{
		
		$this->db->select('*');
		$this->db->select('(SELECT cast(SUM(bill_rs) AS INT) FROM tc_trans_pelayanan WHERE no_registrasi=tc_kunjungan.no_registrasi AND kode_tc_trans_kasir IS NULL) as total_tangguhan');
		$this->db->from('tc_kunjungan');
		$this->db->join('tc_registrasi','tc_registrasi.no_registrasi=tc_kunjungan.no_registrasi','left');
		$this->db->join('mt_bagian','mt_bagian.kode_bagian=tc_kunjungan.kode_bagian_tujuan','left');
		$this->db->join('mt_perusahaan','mt_perusahaan.kode_perusahaan=tc_registrasi.kode_perusahaan','left');
		$this->db->join('mt_karyawan','mt_karyawan.kode_dokter=tc_kunjungan.kode_dokter','left');
		$this->db->where("tgl_jam_keluar is null and tgl_keluar is null and tc_registrasi.no_mr='".$mr."'");
		$query = $this->db->get()->result();
		$result = array();
		foreach ($query as $key => $value) {
			$result[] = array('no_kunjungan' => $value->no_kunjungan, 'kode_bagian_tujuan' => $value->kode_bagian_tujuan, 'poli' => $value->nama_bagian,'tgl_masuk' => $value->tgl_masuk, 'no_registrasi' => $value->no_registrasi, 'penjamin' => $value->nama_perusahaan, 'dokter' => $value->nama_pegawai, 'total_ditangguhkan' => $value->total_tangguhan);
		}

		return $result;
		
	}

	function get_detail_resume_medis($no_registrasi, $no_kunjungan='')
	
	{
		/*data registrasi*/
		$this->db->select('tc_registrasi.no_registrasi, tc_kunjungan.no_kunjungan, nama_pegawai, nama_perusahaan, mt_bagian.nama_bagian, th_riwayat_pasien.diagnosa_awal, th_riwayat_pasien.diagnosa_akhir, th_riwayat_pasien.anamnesa, th_riwayat_pasien.kategori_tindakan, tc_registrasi.tgl_jam_masuk, tc_kunjungan.tgl_masuk, th_riwayat_pasien.pemeriksaan, tinggi_badan, tekanan_darah, nadi, th_riwayat_pasien.berat_badan, suhu, mt_master_pasien.nama_pasien, mt_master_pasien.no_mr,mt_master_pasien.almt_ttp_pasien,mt_master_pasien.tempat_lahir,mt_master_pasien.tgl_lhr, kode_bagian_tujuan, tujuan_poli.nama_bagian as poli_tujuan_kunjungan, asal_poli.nama_bagian as poli_asal_kunjungan, kode_bagian_asal, tc_registrasi.kode_perusahaan, ');
		$this->db->from('tc_kunjungan');
		$this->db->join('tc_registrasi','tc_registrasi.no_registrasi=tc_kunjungan.no_registrasi','left');
		$this->db->join('mt_master_pasien','mt_master_pasien.no_mr=tc_registrasi.no_mr','left');
		$this->db->join('mt_bagian','mt_bagian.kode_bagian=tc_registrasi.kode_bagian_masuk','left');
		$this->db->join('mt_bagian as tujuan_poli','tujuan_poli.kode_bagian=tc_kunjungan.kode_bagian_tujuan','left');
		$this->db->join('mt_bagian as asal_poli','asal_poli.kode_bagian=tc_kunjungan.kode_bagian_asal','left');
		$this->db->join('mt_perusahaan','mt_perusahaan.kode_perusahaan=tc_registrasi.kode_perusahaan','left');
		$this->db->join('mt_karyawan','mt_karyawan.kode_dokter=tc_kunjungan.kode_dokter','left');
		$this->db->join('th_riwayat_pasien','th_riwayat_pasien.no_kunjungan=tc_kunjungan.no_kunjungan','left');
		$this->db->where('tc_kunjungan.no_registrasi', $no_registrasi);
		if($no_kunjungan != ''){
			$this->db->where('tc_kunjungan.no_kunjungan', $no_kunjungan);
		}
		$this->db->order_by('tc_kunjungan.tgl_masuk', 'DESC');
		$registrasi = $this->db->get()->result();

		/*data transaksi*/
		$transaksi = $this->db->select('kode_trans_pelayanan, no_kunjungan, nama_tindakan, mt_jenis_tindakan.jenis_tindakan, kode_jenis_tindakan, tgl_transaksi, kode_tc_trans_kasir, nama_pegawai, jumlah_tebus')->join('mt_jenis_tindakan','mt_jenis_tindakan.kode_jenis_tindakan=tc_trans_pelayanan.jenis_tindakan','left')->join('mt_karyawan','mt_karyawan.kode_dokter=tc_trans_pelayanan.kode_dokter1','left')->join('fr_tc_far_detail','fr_tc_far_detail.kd_tr_resep=tc_trans_pelayanan.kd_tr_resep','left')->get_where('tc_trans_pelayanan', array('no_registrasi' => $no_registrasi) )->result();

		// data pembayaran kasir
		$trans_kasir = $this->db->get_where('tc_trans_kasir', array('no_registrasi' => $no_registrasi))->result();

		$antrian_poli = $this->db->query("select no_antrian from pl_tc_poli where no_kunjungan in (select no_kunjungan from tc_kunjungan where no_registrasi = ".$no_registrasi.")")->row();

		$this->db->select('tmp_user.fullname');
		$this->db->from('tc_registrasi');
		$this->db->join('tmp_user','tc_registrasi.no_induk=tmp_user.user_id','left');
		$this->db->where('tc_registrasi.no_registrasi', $no_registrasi);
		$petugas = $this->db->get()->row();

		// penunjang
		$penunjang = $this->db->where('SUBSTRING(kode_bagian_tujuan, 1, 2) =', '05')->join('mt_bagian', 'mt_bagian.kode_bagian=tc_kunjungan.kode_bagian_tujuan','left')->join('pm_tc_penunjang', 'pm_tc_penunjang.no_kunjungan=tc_kunjungan.no_kunjungan','left')->get_where('tc_kunjungan', array('no_registrasi' => $no_registrasi) )->result();
		$getDataPm = [];
		foreach ($penunjang as $key_pm => $val_pm) {
			$getDataPm[$val_pm->no_registrasi][] = $val_pm;
		}

		
		$data = array(
			'registrasi' =>  $registrasi[0],
			'riwayat_medis' =>  $registrasi,
			'tindakan' =>  $transaksi,
			'no_antrian' => $antrian_poli,
			'petugas' => $petugas,
			'trans_kasir' => $trans_kasir,
			'penunjang' => $getDataPm,
			);
		return $data;
		
	}

	function get_detail_kunjungan_by_no_kunjungan($no_kunjungan)
	
	{
		/*data registrasi*/
		$this->db->select('tc_registrasi.no_registrasi, tc_kunjungan.no_kunjungan,status_keluar, tc_registrasi.kode_dokter, nama_pegawai, nama_perusahaan, mt_bagian.nama_bagian, th_riwayat_pasien.diagnosa_awal, th_riwayat_pasien.diagnosa_akhir, th_riwayat_pasien.anamnesa, th_riwayat_pasien.kategori_tindakan, tc_registrasi.tgl_jam_masuk, tc_kunjungan.tgl_masuk, th_riwayat_pasien.pemeriksaan, mt_master_pasien.nama_pasien, mt_master_pasien.no_mr, mt_master_pasien.tgl_lhr, kode_bagian_tujuan, tujuan_poli.nama_bagian as poli_tujuan_kunjungan, asal_poli.nama_bagian as poli_asal_kunjungan, kode_bagian_asal, tc_registrasi.kode_perusahaan');
		$this->db->from('tc_kunjungan');
		$this->db->join('tc_registrasi','tc_registrasi.no_registrasi=tc_kunjungan.no_registrasi','left');
		$this->db->join('mt_master_pasien','mt_master_pasien.no_mr=tc_registrasi.no_mr','left');
		$this->db->join('mt_bagian','mt_bagian.kode_bagian=tc_registrasi.kode_bagian_masuk','left');
		$this->db->join('mt_bagian as tujuan_poli','tujuan_poli.kode_bagian=tc_kunjungan.kode_bagian_tujuan','left');
		$this->db->join('mt_bagian as asal_poli','asal_poli.kode_bagian=tc_kunjungan.kode_bagian_asal','left');
		$this->db->join('mt_perusahaan','mt_perusahaan.kode_perusahaan=tc_registrasi.kode_perusahaan','left');
		$this->db->join('mt_karyawan','mt_karyawan.kode_dokter=tc_registrasi.kode_dokter','left');
		$this->db->join('th_riwayat_pasien','th_riwayat_pasien.no_kunjungan=tc_kunjungan.no_kunjungan','left');
		$this->db->where('tc_kunjungan.no_kunjungan', $no_kunjungan);
		$this->db->order_by('tc_kunjungan.tgl_masuk', 'DESC');
		$data = $this->db->get()->row();

		return $data;
		
	}

	public function get_pesanan_pasien($id_tc_pesanan){
		return $this->db->get_where('tc_pesanan', array('id_tc_pesanan' => $id_tc_pesanan))->row();
	}

	public function get_pesanan_pasien_($id_tc_pesanan){
		$qry = "
				SELECT a.no_mr, a.nama, a.kode_perjanjian, a.unique_code_counter as counter, CONVERT(date, a.tgl_pesanan) as tgl_kembali, a.keterangan,
				(SELECT diagnosa_akhir FROM th_riwayat_pasien 
				WHERE kode_riwayat=(SELECT MAX(z.kode_riwayat) FROM th_riwayat_pasien z 
				WHERE z.no_mr=a.no_mr)) as diagnosa_akhir, (SELECT no_registrasi FROM tc_registrasi 
				WHERE no_registrasi=(SELECT MAX(x.no_registrasi) FROM tc_registrasi x 
				WHERE x.no_mr=a.no_mr)) as no_registrasi, c.nama_bagian, d.nama_pegawai as dokter,
				e.nama_perusahaan
				FROM tc_pesanan a
				LEFT JOIN mt_master_pasien b on b.no_mr=a.no_mr
				LEFT JOIN mt_perusahaan e on e.kode_perusahaan=b.kode_perusahaan
				LEFT JOIN mt_bagian c on c.kode_bagian=a.no_poli
				LEFT JOIN mt_karyawan d on d.kode_dokter=a.kode_dokter
				WHERE a.id_tc_pesanan=".$id_tc_pesanan."";
		return $this->db->query($qry)->row();
		//return $this->db->get_where('tc_pesanan', array('id_tc_pesanan' => $id_tc_pesanan))->row();
	}

	public function delete_registrasi($no_reg, $no_kunjungan){

		/*jika registrasi dan kunjungan hanya sekali atau jika tidak ada rujukan maka hapus registrasi dan kunjungannya*/
		/*cek kunjungan*/
		if( $no_reg != 0 ){
			$dt_kunjungan = $this->db->get_where('tc_kunjungan', array('no_registrasi' => $no_reg));
			if($dt_kunjungan->num_rows() <= 1 ){
				/*then delete tc_registrasi and tc_kunjungan*/
				$this->delete_registrasi_and_kunjungan($no_reg, $no_kunjungan);
			}else{
				$this->delete_kunjungan($no_kunjungan);
			}
		}else{
			$this->delete_kunjungan($no_kunjungan);
		}

		return true;

	}

	public function delete_kunjungan($no_kunjungan){


		/*delete tc_trans_kasir*/
		$this->db->where("kode_tc_trans_kasir in (select kode_tc_trans_kasir from tc_trans_pelayanan where no_kunjungan=".$no_kunjungan.")")->delete('tc_trans_kasir');

		/*find data transaksi kasir*/
		// $trans_kasir = $this->db->query("select * from tc_trans_kasir 
		// where kode_tc_trans_kasir in (select kode_tc_trans_kasir from tc_trans_pelayanan where no_kunjungan=".$no_kunjungan.")");
		
		/*delete ak_tc_transaksi_det*/
		$this->db->where(" id_ak_tc_transaksi in (select id_ak_tc_transaksi from ak_tc_transaksi 
		where kode_tc_trans_kasir in (select kode_tc_trans_kasir from tc_trans_pelayanan where no_kunjungan=".$no_kunjungan."))")->delete("ak_tc_transaksi_det");

		/*delete ak_tc_transaksi*/
		$this->db->where("id_ak_tc_transaksi in (select id_ak_tc_transaksi from ak_tc_transaksi 
		where kode_tc_trans_kasir in (select kode_tc_trans_kasir from tc_trans_pelayanan where no_kunjungan=".$no_kunjungan."))")->delete('ak_tc_transaksi');
		

		/*delete tc_registrasi*/
		//$this->db->delete('tc_registrasi', array('no_registrasi' => $no_reg) );

		/*delete tc_kunjungan*/
		$this->db->delete('tc_kunjungan', array('no_kunjungan' => $no_kunjungan) );

		/*delete pl_tc_poli*/
		$this->db->delete('pl_tc_poli', array('no_kunjungan' => $no_kunjungan) );

		/*delete tc_trans_pelayanan*/
		$this->db->delete('tc_trans_pelayanan', array('no_kunjungan' => $no_kunjungan) );

		/*pm tc penunjang*/
		$this->db->delete('pm_tc_penunjang', array('no_kunjungan' => $no_kunjungan) );

		/*delete ri_tc_rawat_inap*/
		$this->db->delete('ri_tc_rawatinap', array( 'no_kunjungan' => $no_kunjungan) );

	}

	public function delete_registrasi_and_kunjungan($no_reg, $no_kunjungan){

		// delete akunting and trans pelayanan
		$this->deleteAkunting($no_reg, $no_kunjungan);		
		
		// kunjungan poli
		$this->deletePoli($no_reg, $no_kunjungan);		
		
		/*pm tc penunjang*/
		$this->deletePenunjang($no_reg, $no_kunjungan);				

		/*delete ri_tc_rawat_inap*/
		$this->deleteRawatInap($no_reg, $no_kunjungan);
		
		// delete farmasi
		$this->deleteFarmasi($no_reg, $no_kunjungan);
		
		// delete mcu
		$this->deleteMcu($no_reg, $no_kunjungan);
		
		// igd
		$this->db->delete('gd_tc_gawat_darurat', array( 'no_kunjungan' => $no_kunjungan) );
		$this->db->delete('rg_tc_rujukan', array( 'no_registrasi' => $no_reg) );

		/*delete tc_trans_pelayanan*/
		$this->db->delete('tc_trans_pelayanan', array('no_registrasi' => $no_reg) );

		/*delete tc_kunjungan*/
		$this->db->delete('tc_kunjungan', array('no_registrasi' => $no_reg) );

		/*delete tc_registrasi*/
		$this->db->delete('tc_registrasi', array('no_registrasi' => $no_reg) );
		

	}

	function deleteRawatInap($no_reg, $no_kunjungan){

		$this->db->where("kode_ri in (select kode_ri from ri_tc_rawatinap where no_kunjungan=".$no_kunjungan.")")->delete('tc_rekam_medis');

		$this->db->where("kode_ri in (select kode_ri from ri_tc_rawatinap where no_kunjungan=".$no_kunjungan.")")->delete('ri_pasien_vk');

		$this->db->where("kode_ri in (select kode_ri from ri_tc_rawatinap where no_kunjungan=".$no_kunjungan.")")->delete('gz_tc_pesanan');

		$this->db->where("kode_ri in (select kode_ri from ri_tc_rawatinap where no_kunjungan=".$no_kunjungan.")")->delete('ri_tc_riwayat_kelas');

		$this->db->delete('ri_tc_rawatinap', array( 'no_kunjungan' => $no_kunjungan) );

	}

	function deletePoli($no_reg, $no_kunjungan){
		$this->db->delete('pl_tc_poli', array('no_kunjungan' => $no_kunjungan) );
		$this->db->delete('th_riwayat_pasien', array('no_kunjungan' => $no_kunjungan) );
	}

	function deleteAkunting($no_reg, $no_kunjungan){

		$this->db->where("kode_tc_trans_kasir in (select kode_tc_trans_kasir from tc_trans_pelayanan where no_registrasi=".$no_reg.")")->delete('tc_trans_kasir');

		/*delete ak_tc_transaksi_det*/
		$this->db->where(" id_ak_tc_transaksi in (select id_ak_tc_transaksi from ak_tc_transaksi 
		where kode_tc_trans_kasir in (select kode_tc_trans_kasir from tc_trans_pelayanan where no_registrasi=".$no_reg."))")->delete("ak_tc_transaksi_det");

		/*delete ak_tc_transaksi*/
		$this->db->where("id_ak_tc_transaksi in (select id_ak_tc_transaksi from ak_tc_transaksi 
		where kode_tc_trans_kasir in (select kode_tc_trans_kasir from tc_trans_pelayanan where no_registrasi=".$no_reg."))")->delete('ak_tc_transaksi');

	}
	function deletePenunjang($no_reg, $no_kunjungan){

		// cek no_kunjungan by no registrasi
		$this->db->where('no_kunjungan IN (select no_kunjungan from tc_kunjungan where no_registrasi='.$no_reg.')')->delete('pm_tc_penunjang');
		$this->db->where('no_kunjungan IN (select no_kunjungan from tc_kunjungan where no_registrasi='.$no_reg.')')->delete('tc_trans_pelayanan_paket_lab');

		// $this->db->delete('pm_terima_sample_lab', array('no_kunjungan' => $no_kunjungan) );
		// $this->db->delete('pm_pasienlab_sample', array('no_kunjungan' => $no_kunjungan) );
		$this->db->where("kode_trans_pelayanan in (select kode_trans_pelayanan from tc_trans_pelayanan where no_registrasi=".$no_reg." and no_kunjungan=".$no_kunjungan.")")->delete('pm_tc_hasilpenunjang');

	}

	function deleteMcu($no_reg, $no_kunjungan){
		// delete mcu
		$this->db->delete('tc_trans_pelayanan_paket_mcu', array( 'no_registrasi' => $no_reg) );
		$this->db->delete('mcu_tc_registrasi', array( 'no_registrasi' => $no_reg) );
		$this->db->where("kode_trans_pelayanan in (select kode_trans_pelayanan from tc_trans_pelayanan where no_registrasi=".$no_reg.")")->delete('mcu_tc_hasil');
	}

	function deleteFarmasi($no_reg, $no_kunjungan){
		
		// racikan
		$this->db->where("kode_trans_far in (select kode_trans_far from fr_tc_far where no_kunjungan IN (select no_kunjungan from tc_kunjungan where no_registrasi=".$no_reg."))")->delete('tc_far_racikan');
		
		// detail obat
		$this->db->where("kode_trans_far in (select kode_trans_far from fr_tc_far where no_kunjungan IN (select no_kunjungan from tc_kunjungan where no_registrasi=".$no_reg."))")->delete('fr_tc_far_detail');

		// detail log
		$this->db->where("kode_pesan_resep in (select kode_pesan_resep from fr_tc_far where no_kunjungan IN (select no_kunjungan from tc_kunjungan where no_registrasi=".$no_reg."))")->delete('fr_tc_far_detail_log');

		$this->db->where('no_kunjungan IN (select no_kunjungan from tc_kunjungan where no_registrasi='.$no_reg.')')->delete('fr_tc_far');
		$this->db->where('no_kunjungan IN (select no_kunjungan from tc_kunjungan where no_registrasi='.$no_reg.')')->delete('fr_tc_pesan_resep');

	}

	function mergeNoMr(){

		// cek no mr baru exist
		$exist = $this->db->get_where('mt_master_pasien', array('no_mr' => $_POST['no_mr_baru']));
		
		if($exist->num_rows() > 0) :

			$this->db->query("delete from mt_master_pasien where no_mr = "."'".$_POST['no_mr_lama']."'"."");

			$this->db->query("update mt_master_pasien set keterangan = "."'Asal No MR ".$_POST['no_mr_lama']."'"." where no_mr="."'".$_POST['no_mr_baru']."'"."");

			$this->db->query("update tc_tagih_det set no_mr = "."'".$_POST['no_mr_baru']."'"." where no_mr="."'".$_POST['no_mr_lama']."'"."");

			$this->db->query("update fr_tc_far set no_mr = "."'".$_POST['no_mr_baru']."'"." where no_mr="."'".$_POST['no_mr_lama']."'"."");

			$this->db->query("update fr_tc_far_batal set no_mr = "."'".$_POST['no_mr_baru']."'"." where no_mr="."'".$_POST['no_mr_lama']."'"."");

			$this->db->query("update ak_tc_transaksi set no_mr = "."'".$_POST['no_mr_baru']."'"." where no_mr="."'".$_POST['no_mr_lama']."'"."");

			$this->db->query("update tc_trans_kartu set no_mr = "."'".$_POST['no_mr_baru']."'"." where no_mr="."'".$_POST['no_mr_lama']."'"."");

			$this->db->query("update tc_kunjungan set no_mr = "."'".$_POST['no_mr_baru']."'"." where no_mr="."'".$_POST['no_mr_lama']."'"."");

			$this->db->query("update gd_th_rujuk_ri set no_mr = "."'".$_POST['no_mr_baru']."'"." where no_mr="."'".$_POST['no_mr_lama']."'"."");

			$this->db->query("update th_riwayat_pasien set no_mr = "."'".$_POST['no_mr_baru']."'"." where no_mr="."'".$_POST['no_mr_lama']."'"."");

			$this->db->query("update rg_tc_rujukan set no_mr = "."'".$_POST['no_mr_baru']."'"." where no_mr="."'".$_POST['no_mr_lama']."'"."");

			$this->db->query("update tc_trans_kasir set no_mr = "."'".$_POST['no_mr_baru']."'"." where no_mr="."'".$_POST['no_mr_lama']."'"."");

			$this->db->query("update ri_tc_riwayat_kelas set no_mr = "."'".$_POST['no_mr_baru']."'"." where no_mr="."'".$_POST['no_mr_lama']."'"."");
			$this->db->query("update tc_trans_pelayanan set no_mr = "."'".$_POST['no_mr_baru']."'"." where no_mr="."'".$_POST['no_mr_lama']."'"."");

			$this->db->query("update fr_tc_pesan_resep set no_mr = "."'".$_POST['no_mr_baru']."'"." where no_mr="."'".$_POST['no_mr_lama']."'"."");

			$this->db->query("update tc_registrasi set no_mr = "."'".$_POST['no_mr_baru']."'"." where no_mr="."'".$_POST['no_mr_lama']."'"."");

			$this->db->query("update tc_trans_pelayanan_paket_odc set no_mr="."'".$_POST['no_mr_baru']."'"." where no_mr="."'".$_POST['no_mr_lama']."'"."");

			$this->db->query("update gd_tc_status_harian set no_mr="."'".$_POST['no_mr_baru']."'"." where no_mr="."'".$_POST['no_mr_lama']."'"."");

			$this->db->query("update gd_tc_cetak_racun set no_mr="."'".$_POST['no_mr_baru']."'"." where no_mr="."'".$_POST['no_mr_lama']."'"."");

			$this->db->query("update odc_tc_registrasi set no_mr="."'".$_POST['no_mr_baru']."'"." where no_mr="."'".$_POST['no_mr_lama']."'"."");

			$this->db->query("update tc_trans_pelayanan_paket_mcu set no_mr="."'".$_POST['no_mr_baru']."'"." where no_mr="."'".$_POST['no_mr_lama']."'"."");

			$this->db->query("update gd_th_kematian set no_mr="."'".$_POST['no_mr_baru']."'"." where no_mr="."'".$_POST['no_mr_lama']."'"."");

			$this->db->query("update mt_karyawan set no_mr="."'".$_POST['no_mr_baru']."'"." where no_mr="."'".$_POST['no_mr_lama']."'"."");

			$this->db->query("update tc_trans_pelayanan_paket_lab set no_mr="."'".$_POST['no_mr_baru']."'"." where no_mr="."'".$_POST['no_mr_lama']."'"."");

			$this->db->query("update gd_tc_cetak_visum set no_mr="."'".$_POST['no_mr_baru']."'"." where no_mr="."'".$_POST['no_mr_lama']."'"."");

			$this->db->query("update ri_pesan_bedah set no_mr="."'".$_POST['no_mr_baru']."'"." where no_mr="."'".$_POST['no_mr_lama']."'"."");

			$this->db->query("update ri_pasien_vk set no_mr="."'".$_POST['no_mr_baru']."'"." where no_mr="."'".$_POST['no_mr_lama']."'"."");

			$this->db->query("update mcu_tc_registrasi set no_mr="."'".$_POST['no_mr_baru']."'"." where no_mr="."'".$_POST['no_mr_lama']."'"."");

			// $this->db->query("delete from tc_tagih_det where no_mr = "."'".$_POST['no_mr_lama']."'"."");
			// $this->db->query("delete from fr_tc_far where no_mr = "."'".$_POST['no_mr_lama']."'"."");
			// $this->db->query("delete from fr_tc_far_batal where no_mr = "."'".$_POST['no_mr_lama']."'"."");
			// $this->db->query("delete from ak_tc_transaksi where no_mr = "."'".$_POST['no_mr_lama']."'"."");
			// $this->db->query("delete from tc_trans_kartu where no_mr = "."'".$_POST['no_mr_lama']."'"."");
			// $this->db->query("delete from tc_kunjungan where no_mr = "."'".$_POST['no_mr_lama']."'"."");
			// $this->db->query("delete from gd_th_rujuk_ri where no_mr = "."'".$_POST['no_mr_lama']."'"."");
			// $this->db->query("delete from th_riwayat_pasien where no_mr = "."'".$_POST['no_mr_lama']."'"."");
			// $this->db->query("delete from rg_tc_rujukan where no_mr = "."'".$_POST['no_mr_lama']."'"."");
			// $this->db->query("delete from tc_trans_kasir where no_mr = "."'".$_POST['no_mr_lama']."'"."");
			// $this->db->query("delete from ri_tc_riwayat_kelas where no_mr = "."'".$_POST['no_mr_lama']."'"."");
			// $this->db->query("delete from tc_trans_pelayanan where no_mr = "."'".$_POST['no_mr_lama']."'"."");
			// $this->db->query("delete from fr_tc_pesan_resep where no_mr = "."'".$_POST['no_mr_lama']."'"."");
			// $this->db->query("delete from tc_registrasi where no_mr = "."'".$_POST['no_mr_lama']."'"."");
			// $this->db->query("delete from tc_trans_pelayanan_paket_odc where no_mr="."'".$_POST['no_mr_lama']."'"."");
			// $this->db->query("delete from gd_tc_status_harian where no_mr="."'".$_POST['no_mr_lama']."'"."");
			// $this->db->query("delete from gd_tc_cetak_racun where no_mr="."'".$_POST['no_mr_lama']."'"."");
			// $this->db->query("delete from odc_tc_registrasi where no_mr="."'".$_POST['no_mr_lama']."'"."");
			// $this->db->query("delete from tc_trans_pelayanan_paket_mcu where no_mr="."'".$_POST['no_mr_lama']."'"."");
			// $this->db->query("delete from gd_th_kematian where no_mr="."'".$_POST['no_mr_lama']."'"."");
			// $this->db->query("delete from mt_karyawan where no_mr="."'".$_POST['no_mr_lama']."'"."");
			// $this->db->query("delete from tc_trans_pelayanan_paket_lab where no_mr="."'".$_POST['no_mr_lama']."'"."");
			// $this->db->query("delete from gd_tc_cetak_visum where no_mr="."'".$_POST['no_mr_lama']."'"."");
			// $this->db->query("delete from ri_pesan_bedah where no_mr="."'".$_POST['no_mr_lama']."'"."");
			// $this->db->query("delete from ri_pasien_vk where no_mr="."'".$_POST['no_mr_lama']."'"."");
			// $this->db->query("delete from mcu_tc_registrasi where no_mr="."'".$_POST['no_mr_lama']."'"."");

			return true;
		else:
			return false;
		endif;
	}

	public function get_jadwal_dokter($jd_id){
		return $this->db->get_where('tr_jadwal_dokter', array('jd_id' => $jd_id) )->row();
	}


}
