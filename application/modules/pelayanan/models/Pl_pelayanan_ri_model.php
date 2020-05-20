<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pl_pelayanan_ri_model extends CI_Model {

	var $table = 'ri_tc_rawatinap';
	var $column = array('ri_tc_rawatinap.nama_pasien','mt_karyawan.nama_pegawai');
	var $select = 'ri_tc_rawatinap.bag_pas,ri_tc_rawatinap.no_kunjungan,mt_master_pasien.nama_pasien, kode_ri, ri_tc_rawatinap.status_pulang, tc_kunjungan.no_mr, mt_perusahaan.nama_perusahaan, mt_nasabah.nama_kelompok, ri_tc_rawatinap.tgl_masuk, mt_karyawan.nama_pegawai,tc_registrasi.no_registrasi, tc_registrasi.kode_kelompok, tc_registrasi.kode_perusahaan, tc_kunjungan.kode_bagian_asal, tc_kunjungan.status_keluar, mt_bagian.nama_bagian, ri_tc_rawatinap.dr_merawat, ri_tc_rawatinap.kelas_pas,ri_tc_rawatinap.kelas_titipan, ri_tc_rawatinap.kode_ruangan, x.nama_klas as klas,y.nama_klas as klas_titip, tc_registrasi.tarif_inacbgs, tc_registrasi.ina_cbgs, pasien_titipan, c.no_kamar, c.no_bed';

	var $order = array('ri_tc_rawatinap.no_kunjungan' => 'DESC');

	public function __construct()
	{
		parent::__construct();
	}

	public function save($table, $data)
	{
		/*insert tc_registrasi*/
		$this->db->insert($table, $data);
		
		return $this->db->insert_id();;
	}

	private function _main_query(){
		$date = date('Y-m-d H:i:s', strtotime('-3 days', strtotime(date('Y-m-d H:i:s'))));
		$date2 = date('Y-m-d H:i:s', strtotime('-30 days', strtotime(date('Y-m-d H:i:s'))));
		$this->db->select($this->select);
		$this->db->from($this->table);
		$this->db->join('tc_kunjungan',''.$this->table.'.no_kunjungan=tc_kunjungan.no_kunjungan','left');
		$this->db->join('mt_karyawan','mt_karyawan.kode_dokter=ri_tc_rawatinap.dr_merawat','left');
		$this->db->join('tc_registrasi','tc_registrasi.no_registrasi=tc_kunjungan.no_registrasi','left');
		$this->db->join('mt_perusahaan','tc_registrasi.kode_perusahaan=mt_perusahaan.kode_perusahaan','left');
		$this->db->join('mt_nasabah','tc_registrasi.kode_kelompok=mt_nasabah.kode_kelompok','left');
		$this->db->join('mt_bagian',''.$this->table.'.bag_pas=mt_bagian.kode_bagian','left');
		$this->db->join('mt_master_pasien','tc_kunjungan.no_mr=mt_master_pasien.no_mr','left');
		$this->db->join('mt_klas x','x.kode_klas='.$this->table.'.kelas_pas','left');
		$this->db->join('mt_klas y','y.kode_klas='.$this->table.'.kelas_titipan','left');
		$this->db->join('mt_ruangan c','c.kode_ruangan='.$this->table.'.kode_ruangan','left');
		//$this->db->where('(ri_tc_rawatinap.status_pulang=0 or ri_tc_rawatinap.status_pulang IS NULL)');

		/*if isset parameter*/
		if( (isset($_GET['keyword']) AND $_GET['keyword']!='') OR ( (isset($_GET['from_tgl']) AND $_GET['from_tgl']!='') AND (isset($_GET['from_tgl']) AND $_GET['to_tgl']!='') ) OR (isset($_GET['status_ranap']) AND $_GET['status_ranap']!='') ) {

			if(isset($_GET['search_by']) AND $_GET['keyword'] != ''){
				if($_GET['search_by']=='no_mr' ){
					$this->db->where('mt_master_pasien.'.$_GET['search_by'].'', $_GET['keyword']);
				}
		
				if($_GET['search_by']=='nama_pasien'  ){
					$this->db->like('mt_master_pasien.'.$_GET['search_by'].'', $_GET['keyword']);
				}

				// $this->db->where(array('YEAR(ri_tc_rawatinap.tgl_masuk)' => date('Y'), 'MONTH(ri_tc_rawatinap.tgl_masuk)' => date('m')));
				// $this->db->where("ri_tc_rawatinap.tgl_masuk > '".$date2."' ");
			}

			if (isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' || isset($_GET['to_tgl']) AND $_GET['to_tgl'] != '') {
				$this->db->where("convert(varchar,ri_tc_rawatinap.tgl_masuk,23) between '".$_GET['from_tgl']."' and '".$_GET['to_tgl']."'");					
			}

			if(isset($_GET['status_ranap']) AND $_GET['status_ranap']!=''){

				if($_GET['status_ranap']=='sudah pulang' ){
					$this->db->where("ri_tc_rawatinap.status_pulang=1 and ri_tc_rawatinap.tgl_keluar < '".$date."' ");
				}
		
				if($_GET['status_ranap']=='masih dirawat'  ){
					$this->db->where('(ri_tc_rawatinap.status_pulang=0 or ri_tc_rawatinap.status_pulang IS NULL)');
					//$this->db->where(array('YEAR(ri_tc_rawatinap.tgl_masuk)' => date('Y'), 'MONTH(ri_tc_rawatinap.tgl_masuk)' => date('m')));
					$this->db->where("ri_tc_rawatinap.tgl_masuk > '".$date2."' ");
				}
				
			}
					
		}else{
			$this->db->where('(ri_tc_rawatinap.status_pulang=0 or ri_tc_rawatinap.status_pulang IS NULL)');
			//$this->db->where(array('YEAR(ri_tc_rawatinap.tgl_masuk)' => date('Y'), 'MONTH(ri_tc_rawatinap.tgl_masuk)' => date('m')));
			$this->db->where("ri_tc_rawatinap.tgl_masuk > '".$date2."' ");
		} 

		if((isset($_GET['is_icu']) AND $_GET['is_icu']=='Y')){
			$this->db->where("ri_tc_rawatinap.bag_pas = '031001' ");
		}else if((isset($_GET['is_icu']) AND $_GET['is_icu']=='N')){
			$this->db->where("ri_tc_rawatinap.bag_pas != '031001' ");
		}
        /*end parameter*/
		/*check level user*/
		//$this->authuser->filtering_data_by_level_user($this->table, $this->session->userdata('user')->user_id);

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

	public function get_by_id($id)
	{
		$this->db->select($this->select);
		$this->db->from($this->table);
		$this->db->join('tc_kunjungan',''.$this->table.'.no_kunjungan=tc_kunjungan.no_kunjungan','left');
		$this->db->join('mt_karyawan','mt_karyawan.kode_dokter=ri_tc_rawatinap.dr_merawat','left');
		$this->db->join('tc_registrasi','tc_registrasi.no_registrasi=tc_kunjungan.no_registrasi','left');
		$this->db->join('mt_perusahaan','tc_registrasi.kode_perusahaan=mt_perusahaan.kode_perusahaan','left');
		$this->db->join('mt_nasabah','tc_registrasi.kode_kelompok=mt_nasabah.kode_kelompok','left');
		$this->db->join('mt_bagian',''.$this->table.'.bag_pas=mt_bagian.kode_bagian','left');
		$this->db->join('mt_master_pasien','tc_kunjungan.no_mr=mt_master_pasien.no_mr','left');
		$this->db->join('mt_klas x','x.kode_klas='.$this->table.'.kelas_pas','left');
		$this->db->join('mt_klas y','y.kode_klas='.$this->table.'.kelas_titipan','left');
		
		$this->db->join('mt_ruangan c','c.kode_ruangan='.$this->table.'.kode_ruangan','left');
		if(is_array($id)){
			$this->db->where_in(''.$this->table.'.kode_ri',$id);
			$query = $this->db->get();
			return $query->result();
		}else{
			$this->db->where(''.$this->table.'.kode_ri',$id);
			$query = $this->db->get();
			return $query->row();
		}
		
	}

	public function update($table, $data, $where)
	{
		$this->db->update($table, $data, $where);
		return $this->db->affected_rows();
	}

	public function save_pm($table, $data)
	{
		/*insert tc_registrasi*/
		$this->db->insert($table, $data);
		
		return $this->db->insert_id();;
	}

	/*data riwayat diagnosa*/

	private function _main_query_riwayat_diagnosa(){
		$this->db->from('th_riwayat_pasien');
		$this->db->join('mt_bagian','mt_bagian.kode_bagian=th_riwayat_pasien.kode_bagian','left');
		$this->db->where( 'th_riwayat_pasien.no_registrasi',$_GET['no_registrasi'] );
	}

	private function _get_datatables_query_riwayat_diagnosa()
	{
		$column = array('th_riwayat_pasien.diagnosa_awal', 'th_riwayat_pasien.diagnosa_akhir');
		$select = 'th_riwayat_pasien.*,mt_bagian.nama_bagian';

		$this->db->select($select);
		$this->_main_query_riwayat_diagnosa();

		$i = 0;
		
		

		$order = array('kode_riwayat' => 'ASC');

		foreach ($column as $item) 
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
		else if(isset($order))
		{
			$order = $order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function get_datatables_riwayat_diagnosa()
	{
		$this->_get_datatables_query_riwayat_diagnosa();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		// print_r($this->db->last_query());die;
		return $query->result();
	}

	function count_filtered_tindakan_riwayat_diagnosa()
	{
		$this->_get_datatables_query_riwayat_diagnosa();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all_tindakan_riwayat_diagnosa()
	{
		$this->_main_query_riwayat_diagnosa();
		return $this->db->count_all_results();
	}



	/*data pesan vk*/

	private function _main_query_pesan_vk(){
		$this->db->from('ri_pasien_vk');
		$this->db->join('mt_bagian','mt_bagian.kode_bagian=ri_pasien_vk.kode_bagian_asal','left');
		$this->db->join('mt_klas','mt_klas.kode_klas=ri_pasien_vk.kode_klas','left');
		$this->db->where( array('ri_pasien_vk.kode_ri' => $_GET['kode_ri'], 'ri_pasien_vk.no_registrasi' => $_GET['no_registrasi']) );
	}

	private function _get_datatables_query_pesan_vk()
	{
		$column = array('ri_pasien_vk.nama_pasien', 'ri_pasien_vk.no_mr');
		$select = 'ri_pasien_vk.*,mt_bagian.nama_bagian,mt_klas.nama_klas';

		$this->db->select($select);
		$this->_main_query_pesan_vk();

		$i = 0;
		
		

		$order = array('id_pasien_vk' => 'DESC');

		foreach ($column as $item) 
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
		else if(isset($order))
		{
			$order = $order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function get_datatables_pesan_vk()
	{
		$this->_get_datatables_query_pesan_vk();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		//print_r($this->db->last_query());die;
		return $query->result();
	}

	function count_filtered_tindakan_pesan_vk()
	{
		$this->_get_datatables_query_pesan_vk();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all_tindakan_pesan_vk()
	{
		$this->_main_query_pesan_vk();
		return $this->db->count_all_results();
	}


	/*data pesan ok*/

	private function _main_query_pesan_ok(){
		$this->db->from('ri_pesan_bedah');
		$this->db->join('mt_karyawan','mt_karyawan.kode_dokter=ri_pesan_bedah.dokter1','left');
		$this->db->where( array('ri_pesan_bedah.kode_ri' => $_GET['kode_ri'], 'ri_pesan_bedah.no_registrasi' => $_GET['no_registrasi']) );
	}

	private function _get_datatables_query_pesan_ok()
	{
		$column = array('ri_pesan_bedah.nama_pasien', 'ri_pesan_bedah.no_mr');
		$select = 'ri_pesan_bedah.*,mt_karyawan.nama_pegawai';

		$this->db->select($select);
		$this->_main_query_pesan_ok();

		$i = 0;
		
		

		$order = array('id_pesan_bedah' => 'DESC');

		foreach ($column as $item) 
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
		else if(isset($order))
		{
			$order = $order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function get_datatables_pesan_ok()
	{
		$this->_get_datatables_query_pesan_ok();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		//print_r($this->db->last_query());die;
		return $query->result();
	}

	function count_filtered_tindakan_pesan_ok()
	{
		$this->_get_datatables_query_pesan_ok();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all_tindakan_pesan_ok()
	{
		$this->_main_query_pesan_ok();
		return $this->db->count_all_results();
	}

	public function get_data_bedah($id)
	{
		# code...
		$query = $this->db->get_where('mt_tarif_bedah_v', array('kode_master_tarif_detail' => $id));
		return $query->row();
	}


	
	/*data pesan pindah*/

	private function _main_query_pesan_pindah(){
		$this->db->from('ri_tc_riwayat_kelas');
		$this->db->join('mt_bagian','mt_bagian.kode_bagian=ri_tc_riwayat_kelas.bagian_tujuan','left');
		$this->db->join('mt_klas','mt_klas.kode_klas=ri_tc_riwayat_kelas.kelas_tujuan','left');
		$this->db->where( array('ri_tc_riwayat_kelas.kode_ri' => $_GET['kode_ri'], 'ri_tc_riwayat_kelas.no_registrasi' => $_GET['no_registrasi']) );
	}

	private function _get_datatables_query_pesan_pindah()
	{
		$column = array('mt_bagian.nama_bagian');
		$select = 'ri_tc_riwayat_kelas.*,mt_bagian.nama_bagian,mt_klas.nama_klas';

		$this->db->select($select);
		$this->_main_query_pesan_pindah();

		$i = 0;
		
		

		$order = array('kode_riw_klas' => 'DESC');

		foreach ($column as $item) 
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
		else if(isset($order))
		{
			$order = $order;
			$this->db->order_by(key($order), $order[key($order)]);
		}
	}

	function get_datatables_pesan_pindah()
	{
		$this->_get_datatables_query_pesan_pindah();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		//print_r($this->db->last_query());die;
		return $query->result();
	}

	function count_filtered_tindakan_pesan_pindah()
	{
		$this->_get_datatables_query_pesan_pindah();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all_tindakan_pesan_pindah()
	{
		$this->_main_query_pesan_pindah();
		return $this->db->count_all_results();
	}



	public function delete_by_id($table,$key,$id)
	{
		$this->db->get_where($table, array(''.$key.'' => $id));
		return $this->db->delete($table, array(''.$key.'' => $id));
	}

	public function cek_poli_pulang($no_registrasi){
		$this->db->from('tc_kunjungan');
		$this->db->where('no_registrasi', $no_registrasi );
		$this->db->where("kode_bagian_tujuan like '01%'" );
		$this->db->where("(tgl_keluar IS NULL OR status_keluar IS NULL)");
		$query = $this->db->get();
		$cek_poli = $query->num_rows();

		if( $cek_poli > 0 ){
			return true;
		}else{
			return false;
		}
	}

	public function cek_vk_pulang($kode_ri){
		$this->db->from('ri_pasien_vk');
		$this->db->where( array('kode_ri' => $kode_ri,'flag_vk' => 0) );
		$query = $this->db->get();
		$cek_vk = $query->num_rows();

		if( $cek_vk > 0 ){
			return true;
		}else{
			return false;
		}
	}

	public function cek_ok_pulang($kode_ri){
		$this->db->from('ri_pesan_bedah');
		$this->db->where( array('kode_ri' => $kode_ri,'flag_pesan' => 0) );
		$query = $this->db->get();
		$cek_ok = $query->num_rows();

		if( $cek_ok > 0 ){
			return true;
		}else{
			return false;
		}
	}

	public function cek_resep_progress($no_kunjungan){
		$transaksi_resep = $this->db->get_where('fr_tc_pesan_resep', array('no_kunjungan' => $no_kunjungan,'status_tebus' => NULL) )->num_rows();
		return $transaksi_resep;
	}

	public function cek_pemeriksaan_pm($no_registrasi){
		$transaksi_resep = $this->db->get_where('pm_pemeriksaanpasien_2v', array('no_registrasi' => $no_registrasi,'status_daftar' => NULL) )->result();
		return $transaksi_resep;
	}

	public function cek_biaya_obat($no_registrasi){
		$this->db->select('sum(case when bill_rs IS NULL then 0 else bill_rs end) as bi_apo,sum(case when lain_lain IS NULL then 0 else lain_lain end) as bi_lain,status_selesai');
		$this->db->from('tc_trans_pelayanan');
		$this->db->where('no_registrasi', $no_registrasi );
		$this->db->where("kode_bagian like '06%'" );
		$this->db->where("status_selesai <= 3");
		$this->db->where("(status_kredit = 0 OR status_kredit is null)");
		$this->db->group_by('status_selesai');
		$query = $this->db->get();
		return $query->row();
	}

	public function cek_biaya_obat_kredit($no_registrasi){
		$this->db->select('sum(case when bill_rs IS NULL then 0 else bill_rs end) as bi_apo,sum(case when lain_lain IS NULL then 0 else lain_lain end) as bi_lain');
		$this->db->from('tc_trans_pelayanan');
		$this->db->where('no_registrasi', $no_registrasi );
		$this->db->where("kode_bagian like '06%'" );
		$this->db->where("status_selesai <= 3");
		$this->db->where("status_kredit",1);
		$this->db->group_by('status_selesai');
		$query = $this->db->get();
		return $query->row();
	}

	public function cek_biaya_reg($no_registrasi){
		$this->db->select('sum(bill_rs) as biy_rs,sum(bill_dr1) as biy_dr1,sum(bill_dr2) as biy_dr2,sum(bill_dr3) as biy_dr3,sum(lain_lain) as biy_lain');
		$this->db->from('tc_trans_pelayanan');
		$this->db->where('no_registrasi', $no_registrasi );
		$this->db->where("kode_bagian not like '06%'" );
		$this->db->where("status_selesai < 3");
		$query = $this->db->get();
		return $query->row();
	}

	public function cek_trans_pelayanan($no_registrasi){
		$this->db->from('tc_trans_pelayanan');
		$this->db->where('no_registrasi', $no_registrasi );
		$this->db->where("kode_tc_trans_kasir is null" );
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function get_transaksi_pasien_by_id($no_kunjungan){
		$this->db->from('tc_trans_pelayanan');
		$this->db->where('no_kunjungan', $no_kunjungan );
		$this->db->where("kode_tc_trans_kasir is null" );
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function get_riwayat_pasien_by_id($no_kunjungan){
		return $this->db->get_where('th_riwayat_pasien', array('no_kunjungan' => $no_kunjungan) )->row();
	}

	public function get_ruangan_by_id($kode){
		return $this->db->get_where('mt_ruangan', array('kode_ruangan' => $kode) )->row();
	}



}
