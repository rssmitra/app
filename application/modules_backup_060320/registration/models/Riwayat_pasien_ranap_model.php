<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Riwayat_pasien_ranap_model extends CI_Model {

	var $table = 'ri_tc_rawatinap';
	var $column = array('mt_master_pasien.no_mr','mt_master_pasien.nama_pasien');
	var $select = 'ri_tc_rawatinap.no_kunjungan,tc_kunjungan.no_mr,tc_kunjungan.no_registrasi,dr_merawat.nama_pegawai as dokter_merawat,dr_pengirim.nama_pegawai as dr_pengirim, asal.nama_bagian as asal_bagian, mt_master_pasien.nama_pasien, mt_klas.nama_klas, ri_tc_rawatinap.tgl_masuk,ri_tc_rawatinap.tgl_keluar, ri_tc_rawatinap.status_pulang, mt_perusahaan.nama_perusahaan, mt_nasabah.nama_kelompok ';

	var $order = array('ri_tc_rawatinap.no_kunjungan' => 'DESC');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _main_query(){
		$this->db->select($this->select);
		$this->db->from($this->table);
		$this->db->join('tc_kunjungan','tc_kunjungan.no_kunjungan=ri_tc_rawatinap.no_kunjungan','left');
		$this->db->join('mt_master_pasien','mt_master_pasien.no_mr=tc_kunjungan.no_mr','left');
		$this->db->join('mt_karyawan as dr_merawat','dr_merawat.kode_dokter=ri_tc_rawatinap.dr_merawat','left');
		$this->db->join('mt_karyawan as dr_pengirim','dr_pengirim.nama_pegawai=ri_tc_rawatinap.dr_pengirim','left');
		$this->db->join('mt_bagian as asal','asal.kode_bagian=ri_tc_rawatinap.bag_pas','left');
		$this->db->join('mt_klas','mt_klas.kode_klas=ri_tc_rawatinap.kelas_pas','left');
		$this->db->join('tc_registrasi','tc_registrasi.no_registrasi=tc_kunjungan.no_registrasi','left');
		$this->db->join('mt_perusahaan','mt_perusahaan.kode_perusahaan=tc_registrasi.kode_perusahaan','left');
		$this->db->join('mt_nasabah','mt_nasabah.kode_kelompok=tc_registrasi.kode_kelompok','left');
						
		/*default*/
		if( $_GET ) {

			if (isset($_GET['bulan']) AND $_GET['bulan'] != 0) {
	            $this->db->where('MONTH(ri_tc_rawatinap.tgl_masuk)='.$_GET['bulan'].'');	
	        }

	        if (isset($_GET['tahun']) AND $_GET['tahun'] != 0) {
	            $this->db->where('YEAR(ri_tc_rawatinap.tgl_masuk)='.$_GET['tahun'].'');	
	        }

	        if (isset($_GET['bagian_asal']) AND $_GET['bagian_asal'] != '') {
	            $this->db->where('bag_pas', $_GET['bagian_asal']);	
			}
			
			if (isset($_GET['status_ranap']) AND $_GET['status_ranap'] != '') {
				if($_GET['status_ranap'] == 'masih dirawat'){
					$this->db->where('(ri_tc_rawatinap.status_pulang=0 or ri_tc_rawatinap.status_pulang IS NULL)');
					// $this->db->where(array('YEAR(ri_tc_rawatinap.tgl_masuk)' => date('Y'), 'MONTH(ri_tc_rawatinap.tgl_masuk)' => date('m')));
				}

				if($_GET['status_ranap'] == 'sudah pulang'){
					$this->db->where('ri_tc_rawatinap.status_pulang=1');
					// $this->db->where(array('YEAR(ri_tc_rawatinap.tgl_masuk)' => date('Y'), 'MONTH(ri_tc_rawatinap.tgl_masuk)' => date('m')));
				}

	        }

			if (isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' || isset($_GET['to_tgl']) AND $_GET['to_tgl'] != '') {
				$this->db->where("convert(varchar,ri_tc_rawatinap.tgl_masuk,23) between '".$_GET['from_tgl']."' and '".$_GET['to_tgl']."'");
			}
			
		}else{
			$this->db->where('ri_tc_rawatinap.status_pulang=1');
			$this->db->where('YEAR(ri_tc_rawatinap.tgl_masuk)='.date('Y').'');
		}

		if(isset($_GET['bagian_tujuan']) AND $_GET['bagian_tujuan'] != 0){
			$this->db->where('ri_tc_rawatinap.bag_pas', $_GET['bagian_tujuan']);	
		}

		$this->db->group_by('ri_tc_rawatinap.no_kunjungan,tc_kunjungan.no_mr,tc_kunjungan.no_registrasi,dr_merawat.nama_pegawai,dr_pengirim.nama_pegawai, asal.nama_bagian, mt_master_pasien.nama_pasien, mt_klas.nama_klas, ri_tc_rawatinap.tgl_masuk, ri_tc_rawatinap.tgl_keluar, ri_tc_rawatinap.status_pulang, mt_perusahaan.nama_perusahaan, mt_nasabah.nama_kelompok');

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
		$query = $this->db->get();
		// print_r($this->db->last_query());die;
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


}
