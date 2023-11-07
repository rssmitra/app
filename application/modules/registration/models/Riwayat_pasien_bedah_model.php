<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Riwayat_pasien_bedah_model extends CI_Model {

	var $table = 'ok_riwayat_pasien_bedah_v';
	var $column = array('ok_riwayat_pasien_bedah_v.no_mr','ok_riwayat_pasien_bedah_v.nama_pasien');
	var $select = 'ok_riwayat_pasien_bedah_v.no_mr, nama_pasien, no_registrasi, no_kunjungan, kode_ri,jenis_layanan, ok_riwayat_pasien_bedah_v.kode_master_tarif_detail, dokter1, tgl_pesan, id_pesan_bedah, jen_kelamin, tgl_lhr, ok_riwayat_pasien_bedah_v.kode_kelompok, ok_riwayat_pasien_bedah_v.kode_perusahaan, flag_jadwal, ok_riwayat_pasien_bedah_v.kode_klas, mt_karyawan.nama_pegawai, mt_perusahaan.nama_perusahaan, mt_nasabah.nama_kelompok, mt_klas.nama_klas, mt_master_tarif_detail.kode_tarif, mt_master_tarif.nama_tarif, mt_master_tarif_detail.total, ok_riwayat_pasien_bedah_v.kode_ruangan, ok_riwayat_pasien_bedah_v.tgl_jadwal, ok_riwayat_pasien_bedah_v.no_kamar, ok_riwayat_pasien_bedah_v.jam_bedah, ok_riwayat_pasien_bedah_v.tgl_keluar, ok_riwayat_pasien_bedah_v.status_pulang';

	var $order = array('ok_riwayat_pasien_bedah_v.tgl_pesan' => 'DESC');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _main_query(){
		$this->db->select($this->select);
		$this->db->from($this->table);

		$this->db->join('mt_karyawan', 'ok_riwayat_pasien_bedah_v.dokter1=mt_karyawan.kode_dokter', 'left');
		$this->db->join('mt_perusahaan', 'ok_riwayat_pasien_bedah_v.kode_perusahaan=mt_perusahaan.kode_perusahaan', 'left');
		$this->db->join('mt_nasabah', 'ok_riwayat_pasien_bedah_v.kode_kelompok=mt_nasabah.kode_kelompok', 'left');
		$this->db->join('mt_klas', 'ok_riwayat_pasien_bedah_v.kode_klas=mt_klas.kode_klas', 'left');
		$this->db->join('mt_master_tarif_detail', 'ok_riwayat_pasien_bedah_v.kode_master_tarif_detail=mt_master_tarif_detail.kode_master_tarif_detail', 'left');
		$this->db->join('mt_master_tarif', 'mt_master_tarif_detail.kode_tarif=mt_master_tarif.kode_tarif', 'left');

        /*end parameter*/
        $this->db->where('ok_riwayat_pasien_bedah_v.flag_jadwal = 1');
		$this->db->where('ok_riwayat_pasien_bedah_v.flag_pesan = 1');
		$this->db->where('ok_riwayat_pasien_bedah_v.tgl_keluar IS NOT NULL');
						
		/*default*/
		if( $_GET ) {

			if ($_GET['search_by'] != '' AND$_GET['keyword'] != '') {
	            $this->db->where(''.$_GET['search_by'].' LIKE '."'%".$_GET['keyword']."%'".'');	
	        }

			if (isset($_GET['bulan']) AND $_GET['bulan'] != 0) {
	            $this->db->where('MONTH(ok_riwayat_pasien_bedah_v.tgl_pesan)='.$_GET['bulan'].'');	
	        }

	        if (isset($_GET['tahun']) AND $_GET['tahun'] != 0) {
	            $this->db->where('YEAR(ok_riwayat_pasien_bedah_v.tgl_pesan)='.$_GET['tahun'].'');	
	        }

			if (isset($_GET['status_ranap']) AND $_GET['status_ranap'] != '') {
				if($_GET['status_ranap'] == 'masih dirawat'){
					$this->db->where('(ok_riwayat_pasien_bedah_v.status_pulang=0 or ok_riwayat_pasien_bedah_v.status_pulang IS NULL)');
					$this->db->where(array('YEAR(ok_riwayat_pasien_bedah_v.tgl_pesan)' => date('Y'), 'MONTH(ok_riwayat_pasien_bedah_v.tgl_pesan)' => date('m')));
				}

				if($_GET['status_ranap'] == 'sudah pulang'){
					$this->db->where('ok_riwayat_pasien_bedah_v.status_pulang=1');
					$this->db->where(array('YEAR(ok_riwayat_pasien_bedah_v.tgl_pesan)' => date('Y'), 'MONTH(ok_riwayat_pasien_bedah_v.tgl_pesan)' => date('m')));
				}

	        }

			if (isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' || isset($_GET['to_tgl']) AND $_GET['to_tgl'] != '') {
				$this->db->where("convert(varchar,ok_riwayat_pasien_bedah_v.tgl_pesan,23) between '".$_GET['from_tgl']."' and '".$_GET['to_tgl']."'");
	        }
		}else{
			$this->db->where('YEAR(ok_riwayat_pasien_bedah_v.tgl_pesan)='.date('Y').'');
			$this->db->where('MONTH(ok_riwayat_pasien_bedah_v.tgl_pesan)='.date('m').'');
		}

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
		
		$this->db->group_by( $this->select );

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

	function get_data()
	{
		$this->_main_query();
		$query = $this->db->get();
		//print_r($this->db->last_query());die;
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

	public function get_by_id($id)
	{
		$this->_main_query();
		$this->db->where(''.$this->table.'.id_pesan_bedah',$id);
		$query = $this->db->get();
		return $query;
	}


}
