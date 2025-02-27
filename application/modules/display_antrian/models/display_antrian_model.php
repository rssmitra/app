<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Display_antrian_model extends CI_Model {

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
		$this->db->select("fr_tc_pesan_resep.kode_pesan_resep, tgl_pesan,fr_tc_far.no_registrasi,dokter_pengirim,nama_pasien,flag_trans,status_terima,e_resep, status_transaksi, kode_trans_far");
		$this->db->from("fr_tc_pesan_resep");
		$this->db->join("fr_tc_far","fr_tc_far.kode_pesan_resep = fr_tc_pesan_resep.kode_pesan_resep", "LEFT");
		$this->db->where("CAST(tgl_pesan as DATE) = '".date("Y-m-d")."'");
		$this->db->where("status_terima NOT IN (1,2)");
		$this->db->where("resep_diantar != 'Y'");
		$this->db->where("e_resep", 1);
		$this->db->where("flag_trans", "RJ");
		$this->db->order_by("tgl_pesan", "ASC");
		$result = $this->db->get()->result();

		// echo '<pre>';print_r($result);die;
		$no=0;
		$get_data = [];
		foreach($result as $row){
			$no++;
			// resep_masuk
			if($row->kode_trans_far == null){
				$get_data['resep_masuk'][] = array(
					'nama_pasien' => $row->nama_pasien, 
					'kode_trans_far' => $row->kode_trans_far,
					'status_transaksi' => $row->status_transaksi,
					'status_terima' => $row->status_terima,
				);
			}
			// dalam proses
			if($row->kode_trans_far != null && $row->status_transaksi != 1){
				$get_data['dalam_proses'][] = array(
					'nama_pasien' => $row->nama_pasien, 
					'kode_trans_far' => $row->kode_trans_far,
					'status_transaksi' => $row->status_transaksi,
					'status_terima' => $row->status_terima,
				);
			}

			// siap ambil
			if($row->kode_trans_far != null && $row->status_transaksi == 1){
				$get_data['pengambilan'][] = array(
					'nama_pasien' => $row->nama_pasien, 
					'kode_trans_far' => $row->kode_trans_far,
					'status_transaksi' => $row->status_transaksi,
					'status_terima' => $row->status_terima,
				);
			}

		}
		// resep masuk
		$resep_masuk = isset($get_data['resep_masuk'])?$get_data['resep_masuk']:[];
		$total_resep_masuk = count($resep_masuk);
		$dalam_proses = isset($get_data['dalam_proses'])?$get_data['dalam_proses']:[];
		$total_dalam_proses = count($dalam_proses);
		$pengambilan = isset($get_data['pengambilan'])?$get_data['pengambilan']:[];
		$total_pengambilan = count($pengambilan);

		$return_data = [
			'resep_masuk' => $resep_masuk,
			'total_resep_masuk' => $total_resep_masuk,
			'dalam_proses' => $dalam_proses,
			'total_dalam_proses' => $total_dalam_proses,
			'pengambilan' => $pengambilan,
			'total_pengambilan' => $total_pengambilan,
		];

		return $return_data;
		
	}

	public function get_antrian_poli()
	{
		$this->db->select('pl_tc_poli.no_antrian,pl_tc_poli.nama_pasien, tc_kunjungan.no_mr, antrian_aktif, kode_poli_bpjs, pl_tc_poli.kode_dokter, tgl_keluar_poli, nama_pegawai as nama_dokter, pl_tc_poli.kode_bagian, tc_registrasi.kode_perusahaan');
		$this->db->from('pl_tc_poli');
		$this->db->join('tc_kunjungan','pl_tc_poli.no_kunjungan=tc_kunjungan.no_kunjungan','left');
		$this->db->join('mt_karyawan','mt_karyawan.kode_dokter=pl_tc_poli.kode_dokter','left');
		$this->db->join('mt_bagian','mt_bagian.kode_bagian=pl_tc_poli.kode_bagian','left');
		$this->db->join('tc_registrasi','tc_registrasi.no_registrasi=tc_kunjungan.no_registrasi','left');
		$this->db->join('mt_perusahaan','tc_registrasi.kode_perusahaan=mt_perusahaan.kode_perusahaan','left');
		$this->db->join('mt_nasabah','tc_registrasi.kode_kelompok=mt_nasabah.kode_kelompok','left');
		$this->db->where( 'CAST(pl_tc_poli.tgl_jam_poli as DATE) = ', date('Y-m-d') );
		$this->db->where('pl_tc_poli.status_batal IS NULL');
		$this->db->where("pl_tc_poli.kode_bagian != '014001' ");
		// $this->db->where('status_periksa IS NULL');
		// $this->db->where('(pl_tc_poli.status_batal is null or pl_tc_poli.status_batal = 0)');
		$this->db->where('tgl_keluar_poli is null');
		$this->db->order_by('antrian_aktif', 'DESC');
		$this->db->order_by('no_antrian', 'ASC');
		$this->db->order_by('kode_perusahaan', 'ASC');

		$query = $this->db->get();
		// echo '<pre>';print_r($this->db->last_query());die;
		$result = $query->result();
		$getData = [];
		foreach($result as $key=>$row){
			$getData[$row->kode_poli_bpjs][$row->kode_dokter][] = $row;
		}

		// echo '<pre>';print_r($getData);die;

		return $getData;
		
	}

	public function get_antrian_poli_original()
	{
		$this->db->select('pl_tc_poli.no_antrian,pl_tc_poli.nama_pasien, tc_kunjungan.no_mr');
		$this->db->from('pl_tc_poli');
		$this->db->join('tc_kunjungan','pl_tc_poli.no_kunjungan=tc_kunjungan.no_kunjungan','left');
		$this->db->join('mt_karyawan','mt_karyawan.kode_dokter=pl_tc_poli.kode_dokter','left');
		$this->db->join('tc_registrasi','tc_registrasi.no_registrasi=tc_kunjungan.no_registrasi','left');
		$this->db->join('mt_perusahaan','tc_registrasi.kode_perusahaan=mt_perusahaan.kode_perusahaan','left');
		$this->db->join('mt_nasabah','tc_registrasi.kode_kelompok=mt_nasabah.kode_kelompok','left');
		$this->db->where( 'CAST(pl_tc_poli.tgl_jam_poli as DATE) = ', date('Y-m-d') );
		$this->db->where('pl_tc_poli.kode_bagian='."'".$this->session->userdata('kode_bagian')."'".'');
		$this->db->where('pl_tc_poli.kode_dokter='."'".$this->session->userdata('sess_kode_dokter')."'".'');
		// $this->db->where('tgl_keluar_poli IS NULL');
		// $this->db->where('status_periksa IS NULL');
		// $this->db->where('(pl_tc_poli.status_batal is null or pl_tc_poli.status_batal = 0)');
		$this->db->where('pl_tc_poli.antrian_aktif = 1');
		$this->db->order_by('no_antrian', 'ASC');

		$query = $this->db->get();
		$result = $query->row();
		// echo '<pre>';print_r($this->db->last_query());die;
		
		$praktek = ($this->session->userdata('sess_nama_dokter')) ? 'DOKTER DATANG' : 'BELUM MULAI';
		$nama_pasien = explode(" ", $result->nama_pasien);
		$txt_nama_pasien = $nama_pasien[0].' '.$nama_pasien[1];
		$response = array(
			'no_mr' => isset($result->no_mr) ? $result->no_mr : '',
			'no_antrian' => isset($result->no_antrian) ? $result->no_antrian : '',
			'nama_pasien' => isset($result->nama_pasien) ? $txt_nama_pasien : $praktek,
			'poli' => ($this->session->userdata('nama_bagian')) ? $this->session->userdata('nama_bagian') : 'Tutup',
			'dokter' => ($this->session->userdata('sess_nama_dokter')) ? $this->session->userdata('sess_nama_dokter') : '-',
			'kode_dokter' => ($this->session->userdata('sess_kode_dokter')) ? $this->session->userdata('sess_kode_dokter') : '0',
		);
		return $response;
		
	}


	
}
