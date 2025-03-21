<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pl_pelayanan_pm_model extends CI_Model {

	var $table = 'pm_tc_penunjang';
	var $column = array('mt_master_pasien.nama_pasien');
	var $select = 'mt_master_pasien.no_mr,mt_master_pasien.nama_pasien, tc_kunjungan.no_kunjungan, tc_kunjungan.kode_bagian_tujuan, tc_kunjungan.kode_bagian_asal, tc_kunjungan.tgl_masuk, tc_kunjungan.tgl_keluar, tc_kunjungan.status_masuk, tc_kunjungan.status_keluar, tc_kunjungan.status_cito, pm_tc_penunjang.kode_penunjang, pm_tc_penunjang.tgl_daftar,pm_tc_penunjang.tgl_periksa, pm_tc_penunjang.no_antrian, pm_tc_penunjang.kode_klas, pm_tc_penunjang.status_daftar, pm_tc_penunjang.flag_mcu, pm_tc_penunjang.status_isihasil, tc_registrasi.kode_perusahaan, tc_registrasi.no_registrasi, tc_registrasi.kode_kelompok, tc_registrasi.no_sep, nama_perusahaan, nama_kelompok, mt_bagian.nama_bagian, pm_tc_penunjang.status_batal, kode_rujukan, eorder, id_pm_tc_penunjang, status_selesai';
	var $group = 'mt_master_pasien.no_mr,mt_master_pasien.nama_pasien, tc_kunjungan.no_kunjungan, tc_kunjungan.kode_bagian_tujuan, tc_kunjungan.kode_bagian_asal, tc_kunjungan.tgl_masuk, tc_kunjungan.tgl_keluar, tc_kunjungan.status_masuk, tc_kunjungan.status_keluar, tc_kunjungan.status_cito, pm_tc_penunjang.kode_penunjang, pm_tc_penunjang.tgl_daftar,pm_tc_penunjang.tgl_periksa, pm_tc_penunjang.no_antrian, pm_tc_penunjang.kode_klas, pm_tc_penunjang.status_daftar, pm_tc_penunjang.flag_mcu, pm_tc_penunjang.status_isihasil, tc_registrasi.kode_perusahaan, tc_registrasi.no_registrasi, tc_registrasi.kode_kelompok, tc_registrasi.no_sep, nama_perusahaan, nama_kelompok, mt_bagian.nama_bagian, pm_tc_penunjang.status_batal, kode_rujukan, kodebookingantrol, eorder, id_pm_tc_penunjang, bagian_tujuan.nama_bagian, status_selesai';
	var $order = array('tc_kunjungan.no_kunjungan' => 'DESC');

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
		$date = date('Y-m-d H:i:s', strtotime('-1 days', strtotime(date('Y-m-d H:i:s'))));
		$date_2 = date('Y-m-d H:i:s', strtotime('-31 days', strtotime(date('Y-m-d H:i:s'))));

		$this->db->select($this->select);
		$this->db->select('bagian_tujuan.nama_bagian as bagian_tujuan');
		$this->db->from($this->table);
		$this->db->join('tc_kunjungan',''.$this->table.'.no_kunjungan=tc_kunjungan.no_kunjungan','left');
		$this->db->join('tc_registrasi','tc_registrasi.no_registrasi=tc_kunjungan.no_registrasi','left');
		$this->db->join('rg_tc_rujukan','rg_tc_rujukan.no_registrasi=tc_registrasi.no_registrasi','left');
		$this->db->join('mt_master_pasien','tc_registrasi.no_mr=mt_master_pasien.no_mr','left');
		$this->db->join('mt_perusahaan','tc_registrasi.kode_perusahaan=mt_perusahaan.kode_perusahaan','left');
		$this->db->join('mt_nasabah','tc_registrasi.kode_kelompok=mt_nasabah.kode_kelompok','left');
		$this->db->join('mt_bagian','tc_kunjungan.kode_bagian_asal=mt_bagian.kode_bagian','left');
		$this->db->join('mt_bagian as bagian_tujuan','tc_kunjungan.kode_bagian_tujuan=bagian_tujuan.kode_bagian','left');
		$this->db->join('tc_trans_pelayanan',''.$this->table.'.kode_penunjang=tc_trans_pelayanan.kode_penunjang','left');
		
		/*if isset parameter*/
		if( (isset($_GET['status_pasien']) AND $_GET['status_pasien']!='') ) {

			if(isset($_GET['status_pasien']) AND $_GET['status_pasien']!=''){

				if($_GET['status_pasien']=='belum_ditindak' ){
					$this->db->where("(pm_tc_penunjang.status_daftar is null or pm_tc_penunjang.status_daftar = 0 )");

					if( (isset($_GET['keyword']) AND $_GET['keyword']!='') OR ( (isset($_GET['from_tgl']) AND $_GET['from_tgl']!='') AND (isset($_GET['from_tgl']) AND $_GET['to_tgl']!='') ) ){
						if(isset($_GET['search_by']) AND $_GET['keyword'] != ''){
							if($_GET['search_by']=='no_mr' ){
								$this->db->where('mt_master_pasien.'.$_GET['search_by'].'', $_GET['keyword']);
							}
					
							if($_GET['search_by']=='nama_pasien'  ){
								$this->db->like('mt_master_pasien.'.$_GET['search_by'].'', $_GET['keyword']);
							}
			
							$this->db->where('DATEDIFF(Day, tc_kunjungan.tgl_masuk, getdate()) <= 120');	
						}
			
						if (isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' || isset($_GET['to_tgl']) AND $_GET['to_tgl'] != '') {
							$this->db->where("convert(varchar,tc_kunjungan.tgl_masuk,23) between '".$_GET['from_tgl']."' and '".$_GET['to_tgl']."'");					
						}			
					}else{
						$this->db->where('DATEDIFF(Day, tc_kunjungan.tgl_masuk, getdate()) <= 30');	
					}

				}
		
				if($_GET['status_pasien']=='belum_diperiksa'  ){
					$this->db->where('(pm_tc_penunjang.status_daftar=1) and (status_isihasil=0 or status_isihasil is null)');
					// $this->db->where('status_selesai<=3');
					
					if( (isset($_GET['keyword']) AND $_GET['keyword']!='') OR ( (isset($_GET['from_tgl']) AND $_GET['from_tgl']!='') AND (isset($_GET['from_tgl']) AND $_GET['to_tgl']!='') ) ){
						if(isset($_GET['search_by']) AND $_GET['keyword'] != ''){
							$this->db->like('mt_master_pasien.'.$_GET['search_by'].'', $_GET['keyword']);
							$this->db->where('DATEDIFF(Day, tc_kunjungan.tgl_masuk, getdate()) <= 120');	
						}
			
						if (isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' || isset($_GET['to_tgl']) AND $_GET['to_tgl'] != '') {
							$this->db->where("convert(varchar,tc_kunjungan.tgl_masuk,23) between '".$_GET['from_tgl']."' and '".$_GET['to_tgl']."'");					
						}			
					}else{
						$this->db->where('DATEDIFF(Day, tc_kunjungan.tgl_masuk, getdate()) <= 30');	
					}
				}

				if($_GET['status_pasien']=='belum_isi_hasil'  ){
					$this->db->where('(pm_tc_penunjang.status_daftar=2) AND (status_isihasil=0 or status_isihasil is null)');
					// $this->db->where('status_selesai<=3');
	
					if( (isset($_GET['keyword']) AND $_GET['keyword']!='') OR ( (isset($_GET['from_tgl']) AND $_GET['from_tgl']!='') AND (isset($_GET['to_tgl']) AND $_GET['to_tgl']!='') ) ){
						if(isset($_GET['search_by']) AND $_GET['keyword'] != ''){
							$this->db->like('mt_master_pasien.'.$_GET['search_by'].'', $_GET['keyword']);
						}
			
						if (isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' || isset($_GET['to_tgl']) AND $_GET['to_tgl'] != '') {
							$this->db->where("convert(varchar,tc_kunjungan.tgl_masuk,23) between '".$_GET['from_tgl']."' and '".$_GET['to_tgl']."'");					
						}			
					}else{
						// if($_GET['sess_kode_bagian']=='050201'){
						// 	$this->db->where("tc_kunjungan.tgl_masuk > '".$date_2."'");
						// }
						$this->db->where('DATEDIFF(Day, tc_kunjungan.tgl_masuk, getdate()) <= 30');	
					}
				}
				
			}
								
		}else{

			if( (isset($_GET['keyword']) AND $_GET['keyword']!='') OR ( (isset($_GET['from_tgl']) AND $_GET['from_tgl']!='') AND (isset($_GET['from_tgl']) AND $_GET['to_tgl']!='') ) ){
				if(isset($_GET['search_by']) AND $_GET['keyword'] != ''){
					if($_GET['search_by']=='no_mr' ){
						$this->db->where('mt_master_pasien.'.$_GET['search_by'].'', $_GET['keyword']);
					}else{
						$this->db->like('mt_master_pasien.'.$_GET['search_by'].'', $_GET['keyword']);
					}
					$this->db->where(array('YEAR(tc_kunjungan.tgl_masuk)' => date('Y')));
				}
	
				if (isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' || isset($_GET['to_tgl']) AND $_GET['to_tgl'] != '') {
					$this->db->where("convert(varchar,tc_kunjungan.tgl_masuk,23) between '".$_GET['from_tgl']."' and '".$_GET['to_tgl']."'");					
				}			
			}else{
				if($_GET['sess_kode_bagian'] == '050301'){
					$this->db->where('CAST(tc_kunjungan.tgl_masuk as DATE) = ', date('Y-m-d'));	
				}else{
					$this->db->where('DATEDIFF(Day, tc_kunjungan.tgl_masuk, getdate()) <= 7');	
				}
			}
			
			// $this->db->where("(pm_tc_penunjang.status_daftar is null or pm_tc_penunjang.status_daftar = 0 )");
			
		}

		if(isset($_GET['sess_kode_bagian']) && $_GET['sess_kode_bagian'] != ''){
			$this->db->where("tc_kunjungan.kode_bagian_tujuan = '".$_GET['sess_kode_bagian']."'");
		}

		if(isset($_GET['bagian_asal']) && $_GET['bagian_asal'] != ''){
			$this->db->where("tc_kunjungan.kode_bagian_asal = '".$_GET['bagian_asal']."'");
		}

		if(isset($_GET['no_reg']) && $_GET['no_reg'] != 'undefined' && $_GET['no_reg'] != ''){
			$this->db->where("tc_kunjungan.no_registrasi = '".$_GET['no_reg']."'");
		}

		$this->db->group_by($this->select);
		$this->db->group_by($this->group);
        /*end parameter*/
		/*check level user*/
		$this->authuser->filtering_data_by_level_user($this->table, $this->session->userdata('user')->user_id);

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

		$this->db->select('mt_master_pasien.no_mr,mt_master_pasien.nama_pasien, tc_kunjungan.no_kunjungan, tc_kunjungan.kode_bagian_tujuan, tc_kunjungan.kode_bagian_asal, tc_kunjungan.tgl_masuk, tc_kunjungan.tgl_keluar, tc_kunjungan.status_masuk, tc_kunjungan.status_keluar, tc_kunjungan.status_cito, pm_tc_penunjang.kode_penunjang, pm_tc_penunjang.tgl_daftar,pm_tc_penunjang.tgl_periksa, pm_tc_penunjang.no_antrian, pm_tc_penunjang.kode_klas, pm_tc_penunjang.status_daftar,pm_tc_penunjang.catatan_hasil, pm_tc_penunjang.flag_mcu, pm_tc_penunjang.status_isihasil, tc_registrasi.kode_perusahaan, tc_registrasi.no_registrasi, tc_registrasi.kode_kelompok, tc_trans_pelayanan.status_selesai, nama_perusahaan, nama_kelompok, nama_bagian, kodebookingantrol, dr_pengirim, pm_tc_penunjang.id_pm_tc_penunjang');
		$this->db->from($this->table);
		$this->db->join('tc_kunjungan',''.$this->table.'.no_kunjungan=tc_kunjungan.no_kunjungan','left');
		$this->db->join('tc_registrasi','tc_registrasi.no_registrasi=tc_kunjungan.no_registrasi','left');
		$this->db->join('mt_master_pasien','tc_registrasi.no_mr=mt_master_pasien.no_mr','left');
		$this->db->join('mt_perusahaan','tc_registrasi.kode_perusahaan=mt_perusahaan.kode_perusahaan','left');
		$this->db->join('mt_nasabah','tc_registrasi.kode_kelompok=mt_nasabah.kode_kelompok','left');
		$this->db->join('mt_bagian','tc_kunjungan.kode_bagian_asal=mt_bagian.kode_bagian','left');
		$this->db->join('tc_trans_pelayanan',''.$this->table.'.kode_penunjang=tc_trans_pelayanan.kode_penunjang','left');
		if(is_array($id)){
			$this->db->where_in(''.$this->table.'.kode_penunjang',$id);
			$query = $this->db->get();
			return $query->result();
		}else{
			$this->db->where(''.$this->table.'.kode_penunjang',$id);
			$query = $this->db->get();
			return $query->row();
		}
		
	}

	public function get_by_no_kunjungan($no_kunjungan, $flag_mcu='')
	{
		$select = 'mt_master_pasien.no_mr,mt_master_pasien.nama_pasien, tc_kunjungan.no_kunjungan, tc_kunjungan.kode_bagian_tujuan, tc_kunjungan.kode_bagian_asal, tc_kunjungan.tgl_masuk, tc_kunjungan.tgl_keluar, tc_kunjungan.status_masuk, tc_kunjungan.status_keluar, tc_kunjungan.status_cito, pm_tc_penunjang.kode_penunjang, pm_tc_penunjang.tgl_daftar,pm_tc_penunjang.tgl_periksa, pm_tc_penunjang.no_antrian, pm_tc_penunjang.kode_klas, pm_tc_penunjang.status_daftar, pm_tc_penunjang.catatan_hasil, pm_tc_penunjang.tgl_isihasil, pm_tc_penunjang.status_isihasil, tc_registrasi.kode_perusahaan, tc_registrasi.no_registrasi, tc_registrasi.kode_kelompok, tp.status_selesai,tp.tgl_transaksi, nama_perusahaan, nama_kelompok, nama_bagian, nama_klas';
		$this->db->select($select);
		$this->db->from($this->table);
		$this->db->join('tc_kunjungan',''.$this->table.'.no_kunjungan=tc_kunjungan.no_kunjungan','left');
		$this->db->join('tc_registrasi','tc_registrasi.no_registrasi=tc_kunjungan.no_registrasi','left');
		$this->db->join('mt_master_pasien','tc_registrasi.no_mr=mt_master_pasien.no_mr','left');
		$this->db->join('mt_perusahaan','tc_registrasi.kode_perusahaan=mt_perusahaan.kode_perusahaan','left');
		$this->db->join('mt_nasabah','tc_registrasi.kode_kelompok=mt_nasabah.kode_kelompok','left');
		$this->db->join('mt_bagian','tc_kunjungan.kode_bagian_asal=mt_bagian.kode_bagian','left');
		$this->db->join('mt_klas',''.$this->table.'.kode_klas=mt_klas.kode_klas','left');
		
		if($flag_mcu==''){
			$this->db->join('tc_trans_pelayanan tp',''.$this->table.'.kode_penunjang=tp.kode_penunjang','left');
		}else{
			$this->db->join('tc_trans_pelayanan_paket_mcu tp',''.$this->table.'.kode_penunjang=tp.kode_penunjang','left');
		}
		
		if(is_array($no_kunjungan)){
			$this->db->where_in(''.$this->table.'.no_kunjungan',$no_kunjungan);
			$query = $this->db->get();
			return $query->result();
		}else{
			$this->db->where(''.$this->table.'.no_kunjungan',$no_kunjungan);
			$query = $this->db->get();
			return $query->row();
		}
		
	}

	public function get_by_kode_penunjang($kode_penunjang, $flag_mcu='')
	{
		$select = 'mt_master_pasien.no_mr,mt_master_pasien.nama_pasien, tc_kunjungan.no_kunjungan, tc_kunjungan.kode_bagian_tujuan, tc_kunjungan.kode_bagian_asal, tc_kunjungan.tgl_masuk, tc_kunjungan.tgl_keluar, tc_kunjungan.status_masuk, tc_kunjungan.status_keluar, tc_kunjungan.status_cito, pm_tc_penunjang.kode_penunjang, pm_tc_penunjang.tgl_daftar,pm_tc_penunjang.tgl_periksa, pm_tc_penunjang.no_antrian, pm_tc_penunjang.kode_klas, pm_tc_penunjang.status_daftar, pm_tc_penunjang.catatan_hasil, pm_tc_penunjang.tgl_isihasil, pm_tc_penunjang.status_isihasil, tc_registrasi.kode_perusahaan, tc_registrasi.no_registrasi, tc_registrasi.kode_kelompok, tp.status_selesai,tp.tgl_transaksi, nama_perusahaan, nama_kelompok, nama_bagian, nama_klas, fullname';
		$this->db->select($select);
		$this->db->from($this->table);
		$this->db->join('tc_kunjungan',''.$this->table.'.no_kunjungan=tc_kunjungan.no_kunjungan','left');
		$this->db->join('tc_registrasi','tc_registrasi.no_registrasi=tc_kunjungan.no_registrasi','left');
		$this->db->join('mt_master_pasien','tc_registrasi.no_mr=mt_master_pasien.no_mr','left');
		$this->db->join('mt_perusahaan','tc_registrasi.kode_perusahaan=mt_perusahaan.kode_perusahaan','left');
		$this->db->join('mt_nasabah','tc_registrasi.kode_kelompok=mt_nasabah.kode_kelompok','left');
		$this->db->join('mt_bagian','tc_kunjungan.kode_bagian_asal=mt_bagian.kode_bagian','left');
		$this->db->join('mt_klas',''.$this->table.'.kode_klas=mt_klas.kode_klas','left');
		$this->db->join('tmp_user',''.$this->table.'.petugas_input=tmp_user.user_id','left');
		
		if($flag_mcu==''){
			$this->db->join('tc_trans_pelayanan tp',''.$this->table.'.kode_penunjang=tp.kode_penunjang','left');
		}else{
			$this->db->join('tc_trans_pelayanan_paket_mcu tp',''.$this->table.'.kode_penunjang=tp.kode_penunjang','left');
		}
		
		if(is_array($kode_penunjang)){
			$this->db->where_in(''.$this->table.'.kode_penunjang',$kode_penunjang);
			$query = $this->db->get();
			return $query->result();
		}else{
			$this->db->where(''.$this->table.'.kode_penunjang',$kode_penunjang);
			$query = $this->db->get();
			return $query->row();
		}
		
	}

	public function get_tgl_kontrol($no_kunjungan){
		$kunjungan = $this->db->get_where('tc_kunjungan', array('no_kunjungan' => $no_kunjungan))->row();
		$last_kontrol = $this->db->select('CAST(tgl_pesanan as DATE) as tgl_kontrol')->order_by('id_tc_pesanan', 'DESC')->get_where('tc_pesanan', array('no_mr' => $kunjungan->no_mr, 'no_poli' => $kunjungan->kode_bagian_asal) )->row();
		if(!empty($last_kontrol)){
			return $last_kontrol->tgl_kontrol;			
		}else{
			return false;
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
		//print_r($this->db->last_query());die;
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

	public function delete_by_id($table,$key,$id)
	{
		$this->db->get_where($table, array(''.$key.'' => $id));
		return $this->db->delete($table, array(''.$key.'' => $id));
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

	/*data hasil pm*/

	private function _main_query_hasil_pm($kode_penunjang,$kode_bag_tujuan){
		$this->db->from('pm_isihasil_v');
		$this->db->join('pm_standarhasil_detail_v','pm_standarhasil_detail_v.kode_mt_hasilpm=pm_isihasil_v.kode_mt_hasilpm','left');
		$this->db->join('mt_master_tarif b', 'pm_isihasil_v.kode_tarif=b.kode_tarif', 'left');
		$this->db->join('mt_master_tarif c', 'c.kode_tarif=b.referensi', 'left');
		$this->db->join('mt_karyawan d', 'd.kode_dokter=pm_isihasil_v.kode_dokter1', 'left');
		$this->db->join('mt_karyawan e', 'e.kode_dokter=pm_isihasil_v.kode_dokter2', 'left');
		$this->db->where('pm_isihasil_v.kode_penunjang', $kode_penunjang );
							
	}

	function get_datatables_hasil_pm($kode_penunjang,$kode_bag_tujuan,$mktimenya='')
	{
		$column = array('pm_isihasil_v.nama_tindakan');
		$select = 'pm_isihasil_v.*, pm_standarhasil_detail_v.kode_mt_hasilpm, 
      pm_standarhasil_detail_v.nama_pemeriksaan, 
      pm_standarhasil_detail_v.kode_bagian, 
      pm_standarhasil_detail_v.urutan, 
      pm_standarhasil_detail_v.nilai_awal, 
      pm_standarhasil_detail_v.nilai_akhir, 
      pm_standarhasil_detail_v.mktime_umur_mulai, 
      b.nama_tarif AS nama_tarif, 
      pm_standarhasil_detail_v.standar_hasil_wanita, 
      pm_standarhasil_detail_v.standar_hasil_pria, 
      pm_standarhasil_detail_v.satuan, 
      pm_standarhasil_detail_v.umur_mulai, 
      pm_standarhasil_detail_v.satuan_umur_mulai, 
      pm_standarhasil_detail_v.umur_akhir, 
      pm_standarhasil_detail_v.satuan_umur_akhir, 
      pm_standarhasil_detail_v.satuan_waktu_mulai, 
      pm_standarhasil_detail_v.satuan_waktu_akhir, 
      pm_standarhasil_detail_v.keterangan, 
      pm_standarhasil_detail_v.mktime_umur_akhir, 
      pm_standarhasil_detail_v.detail_item_1, 
      pm_standarhasil_detail_v.detail_item_2 , c.kode_tarif as referensi, pm_isihasil_v.kode_dokter1, d.nama_pegawai as dokter1, pm_isihasil_v.kode_dokter2, e.nama_pegawai as dokter2';

		$this->db->select($select);
		$this->db->select('(select top 1 hasil from pm_tc_hasilpenunjang where (kode_trans_pelayanan=pm_isihasil_v.kode_trans_pelayanan AND kode_mt_hasilpm=pm_isihasil_v.kode_mt_hasilpm) order by kode_tc_hasilpenunjang DESC ) as hasil');
		$this->db->select('(select top 1 keterangan from pm_tc_hasilpenunjang where (kode_trans_pelayanan=pm_isihasil_v.kode_trans_pelayanan AND kode_mt_hasilpm=pm_isihasil_v.kode_mt_hasilpm) order by kode_tc_hasilpenunjang DESC ) as keterangan_pm');
		$this->_main_query_hasil_pm($kode_penunjang,$kode_bag_tujuan);
		if($kode_bag_tujuan=='050101'){
			$this->db->where("mktime_umur_mulai <= '".$mktimenya."' " );
			$this->db->where("mktime_umur_akhir > '".$mktimenya."' " );
		}
		$this->db->order_by('pm_isihasil_v.kode_trans_pelayanan','ASC');
		$this->db->order_by('pm_isihasil_v.urutan','ASC');

		$query = $this->db->get();
		// print_r($this->db->last_query());die;
		return $query->result();
	}

	function get_data_hasil_pasien_pm($kode_penunjang,$kode_bag_tujuan)
	{
		$column = array('pm_hasilpasien_v.nama_tindakan');
		$select = 'pm_hasilpasien_v.*,pm_mt_standarhasil.urutan,kode_dokter1,d.nama_pegawai as dokter1,kode_dokter2,e.nama_pegawai as dokter2';

		$this->db->select($select);
		$this->db->from('pm_hasilpasien_v');
		$this->db->join('pm_mt_standarhasil','pm_mt_standarhasil.kode_mt_hasilpm=pm_hasilpasien_v.kode_mt_hasilpm','left');
		$this->db->join('mt_karyawan d', 'd.kode_dokter=pm_hasilpasien_v.kode_dokter1', 'left');
		$this->db->join('mt_karyawan e', 'e.kode_dokter=pm_hasilpasien_v.kode_dokter2', 'left');
		$this->db->where('pm_hasilpasien_v.kode_penunjang', $kode_penunjang );
		$this->db->order_by('pm_hasilpasien_v.kode_trans_pelayanan','ASC');
		$this->db->order_by('pm_mt_standarhasil.urutan', 'ASC');
		
		$query = $this->db->get();
		// print_r($this->db->last_query());die;
		return $query->result();
	}

	function get_data_hasil_pasien_pm_mcu($kode_penunjang,$kode_bag_tujuan)
	{
		$column = array('mcu_hasilpasien_pm_v.nama_tindakan');
		$select = 'mcu_hasilpasien_pm_v.*,pm_mt_standarhasil.urutan,tc_kunjungan.kode_dokter as kode_dokter1,d.nama_pegawai as dokter1';

		$this->db->select($select);
		$this->db->from('mcu_hasilpasien_pm_v');
		$this->db->join('pm_mt_standarhasil','pm_mt_standarhasil.kode_mt_hasilpm=mcu_hasilpasien_pm_v.kode_mt_hasilpm','left');
		$this->db->join('tc_kunjungan', 'mcu_hasilpasien_pm_v.no_kunjungan=tc_kunjungan.no_kunjungan', 'left');
		$this->db->join('mt_karyawan d', 'd.kode_dokter=tc_kunjungan.kode_dokter', 'left');
		$this->db->where('mcu_hasilpasien_pm_v.kode_penunjang', $kode_penunjang );
		$this->db->order_by('mcu_hasilpasien_pm_v.kode_trans_pelayanan','ASC');
		$this->db->order_by('pm_mt_standarhasil.urutan', 'ASC');
		
		$query = $this->db->get();
		//print_r($this->db->last_query());die;
		return $query->result();
	}
	
	function get_hasil_pm_mcu($kode_penunjang,$kode_bag_tujuan,$mktimenya='')
	{
		$column = array('mcu_isihasil_pm2_v.nama_tindakan');
		$select = 'mcu_isihasil_pm2_v.*,pm_standarhasil_detail_v.*,tc_kunjungan.kode_dokter as kode_dokter1,d.nama_pegawai as dokter1';

		$this->db->select($select);
		$this->db->from('mcu_isihasil_pm2_v');
		$this->db->join('pm_standarhasil_detail_v','pm_standarhasil_detail_v.kode_mt_hasilpm=mcu_isihasil_pm2_v.kode_mt_hasilpm','left');
		//$this->db->join('pm_mt_standarhasil','pm_mt_standarhasil.kode_mt_hasilpm=mcu_isihasil_pm2_v.kode_mt_hasilpm','left');
		$this->db->join('pm_tc_penunjang', 'pm_tc_penunjang.kode_penunjang=mcu_isihasil_pm2_v.kode_penunjang', 'left');
		$this->db->join('tc_kunjungan', 'pm_tc_penunjang.no_kunjungan=tc_kunjungan.no_kunjungan', 'left');
		$this->db->join('mt_karyawan d', 'd.kode_dokter=tc_kunjungan.kode_dokter', 'left');
		$this->db->where('mcu_isihasil_pm2_v.kode_penunjang', $kode_penunjang );
		if($kode_bag_tujuan=='050101'){
			$this->db->where("mktime_umur_mulai <= '".$mktimenya."' " );
			$this->db->where("mktime_umur_akhir > '".$mktimenya."' " );
		}
		$this->db->order_by('mcu_isihasil_pm2_v.kode_tarif','ASC');
		$this->db->order_by('pm_standarhasil_detail_v.urutan', 'ASC');
		
		$query = $this->db->get();
		//print_r($this->db->last_query());die;
		return $query->result();
	}

	function get_group_hasil_pm($kode_penunjang,$kode_bag_tujuan)
	{
		$this->db->select('nama_tindakan,pm_isihasil_v.kode_tarif,c.nama_tarif as referensi');
		$this->_main_query_hasil_pm($kode_penunjang,$kode_bag_tujuan);
		$this->db->group_by('nama_tindakan,pm_isihasil_v.kode_tarif,c.nama_tarif');
		$query = $this->db->get();
		// print_r($this->db->last_query());die;
		return $query->result();
	}
	

	public function get_tindakan_by_tc_trans_pelayanan($kode_penunjang,$jenis_tindakan='')
	{
		# code...
				
		$this->db->select('tc_trans_pelayanan.*');
		$this->db->from('tc_trans_pelayanan');
		$this->db->where( 'tc_trans_pelayanan.kode_penunjang',$kode_penunjang );
		if($jenis_tindakan!=''){
			$this->db->where( 'tc_trans_pelayanan.jenis_tindakan',$jenis_tindakan );
		}
		$query = $this->db->get();
		return $query->result();
		
	}	

	public function get_pm_mt_bpako($kode_tarif)
	{
		# code...
		return $this->db->get_where('pm_mt_bpako', array('kode_tarif' => $kode_tarif))->result();
	
	}	

	public function get_bpako($kode_penunjang,$kode_tarif='')
	{
		
		$this->db->select('pm_tc_obalkes.*,mt_barang.nama_brg,mt_barang.satuan_kecil');
		$this->db->from('pm_tc_obalkes');
		$this->db->join('mt_barang','pm_tc_obalkes.kode_brg=mt_barang.kode_brg','left');
		$this->db->where( 'pm_tc_obalkes.kode_penunjang',$kode_penunjang );
		if($kode_tarif!=''){
			$this->db->where( 'pm_tc_obalkes.kode_tarif',$kode_tarif );
		}
		$query = $this->db->get();
		return $query->result();
	
	}	

	public function get_pemeriksaan($kode_penunjang,$flag)
	{
		if($flag!=''){
			$select = 'mcu_pemeriksaan_pm_v.*,a.nama_bagian as bagian,b.nama_bagian as bagian_asal,c.nama_pegawai as dokter_1, d.nama_pegawai as dokter_2';
			$from = 'mcu_pemeriksaan_pm_v';
		}else{
			$select = 'pm_pemeriksaanpasien_v.*,a.nama_bagian as bagian,b.nama_bagian as bagian_asal,c.nama_pegawai as dokter_1, d.nama_pegawai as dokter_2';
			$from = 'pm_pemeriksaanpasien_v';
		}
		
		$this->db->select($select);
		$this->db->from($from);
		$this->db->join('mt_bagian a','pm_pemeriksaanpasien_v.kode_bagian=a.kode_bagian','left');
		$this->db->join('mt_bagian b','pm_pemeriksaanpasien_v.kode_bagian_asal=b.kode_bagian','left');
		$this->db->join('mt_karyawan c','pm_pemeriksaanpasien_v.kode_dokter1=c.kode_dokter','left');
		$this->db->join('mt_karyawan d','pm_pemeriksaanpasien_v.kode_dokter2=d.kode_dokter','left');
		$this->db->where( 'kode_penunjang',$kode_penunjang );
		$this->db->order_by('pm_pemeriksaanpasien_v.kode_trans_pelayanan','ASC');
		$query = $this->db->get();
		return $query->result();
		// echo '<pre>';print_r($query);die;
		
	}

	public function get_master_tindakan($kode_bagian){
		$query = "select a.kode_tarif, c.nama_tarif as referensi, a.nama_tarif, 'UMUM' as flag
		from pm_standar_hasil_v a 
		left join mt_master_tarif b on b.kode_tarif = a.kode_tarif 
		left join mt_master_tarif c on c.kode_tarif = b.referensi
		where a.kode_bagian = '050101' and b.is_active = 'Y' and a.nama_tarif not like '%BPJS%'
		group by a.kode_tarif, a.nama_tarif, c.nama_tarif
		UNION ALL
		select a.kode_tarif, c.nama_tarif as referensi, a.nama_tarif, 'BPJS' as flag
		from pm_standar_hasil_v a 
		left join mt_master_tarif b on b.kode_tarif = a.kode_tarif 
		left join mt_master_tarif c on c.kode_tarif = b.referensi
		where a.kode_bagian = '050101' and b.is_active = 'Y' and a.nama_tarif like '%BPJS%'
		group by a.kode_tarif, a.nama_tarif, c.nama_tarif order by a.nama_tarif ASC";

        $dt = $this->db->query($query)->result();
        $ref = [];
		foreach ($dt as $key => $value) {
			$string = $this->trimed($value->referensi);
			$tingkatan[$value->flag][($string == '')?'LAINNYA' : str_replace(" ","_",$string)][] = $value->kode_tarif.'|'.$value->nama_tarif;
			// $tingkatan[($string == '')?'LAINNYA' : str_replace(" ","_",$string)][] = $value->kode_tarif.'|'.$value->nama_tarif;
        }
		// echo "<pre>";print_r($tingkatan);die;

		return $tingkatan;
    }

	function trimed($txt){ 
		$txt = trim($txt); 
		while( strpos($txt, '  ') ){ 
		$txt = str_replace('  ', ' ', $txt); 
		} 
		return $txt; 
	} 

	// order penunjang
	function get_datatables_order_pm()
	{
		$this->db->from('pm_tc_penunjang_order_detail');
		$this->db->join('pm_tc_penunjang','pm_tc_penunjang.id_pm_tc_penunjang=pm_tc_penunjang_order_detail.id_pm_tc_penunjang','left');
		$this->db->where('pm_tc_penunjang.id_pm_tc_penunjang', $_GET['id_pm_tc_penunjang'] );
		$query = $this->db->get();
		// print_r($this->db->last_query());die;
		return $query->result();
	}




	// datatables order fisio


	private function _main_query_order_penunjang(){

		$this->db->select("CAST((
			SELECT  nama_tarif+' | '
			FROM pm_tc_penunjang_order_detail
			LEFT JOIN pm_tc_penunjang ON pm_tc_penunjang.id_pm_tc_penunjang=pm_tc_penunjang_order_detail.id_pm_tc_penunjang
			  LEFT JOIN tc_kunjungan ON tc_kunjungan.no_kunjungan=pm_tc_penunjang.no_kunjungan
			WHERE tc_kunjungan.no_kunjungan = t.no_kunjungan
			FOR XML PATH(''))as varchar(max)) as nama_tarif");
		$this->db->select('pm_tc_penunjang_order_detail.created_date, CAST(xray_foto as NVARCHAR(2000)) as xray_foto, CAST(diagnosa as NVARCHAR(2000)) as diagnosa, CAST(kontra_indikasi as NVARCHAR(2000)) as kontra_indikasi, CAST(pm_tc_penunjang_order_detail.keterangan as NVARCHAR(2000)) as keterangan, kode_penunjang, dr_pengirim, t.no_mr, nama_pasien, t.no_kunjungan, t.kode_bagian_asal, mt_bagian.nama_bagian as bagian_asal, pm_tc_penunjang_order_detail.status, pm_tc_penunjang_order_detail.id_pm_tc_penunjang');
		$this->db->from('pm_tc_penunjang_order_detail');
		$this->db->join('pm_tc_penunjang','pm_tc_penunjang.id_pm_tc_penunjang=pm_tc_penunjang_order_detail.id_pm_tc_penunjang','left');
		$this->db->join('tc_kunjungan t','t.no_kunjungan=pm_tc_penunjang.no_kunjungan','left');
		$this->db->join('mt_bagian','mt_bagian.kode_bagian=t.kode_bagian_asal','left');
		$this->db->join('mt_master_pasien','mt_master_pasien.no_mr=t.no_mr','left');

		$this->db->where('pm_tc_penunjang.kode_bagian', $_GET['kode_bagian'] );
		$this->db->order_by('pm_tc_penunjang_order_detail.created_date', 'DESC');

		if(isset($_GET['no_mr']) && !empty($_GET['no_mr'])){
			$this->db->where('t.no_mr', $_GET['no_mr'] );
		}

		if(isset($_GET['id_pm_tc_penunjang']) && !empty($_GET['id_pm_tc_penunjang'])){
			$this->db->where('pm_tc_penunjang_order_detail.id_pm_tc_penunjang', $_GET['id_pm_tc_penunjang'] );
		}

		if(isset($_GET['search_by']) && $_GET['search_by'] != '' && isset($_GET['keyword']) && $_GET['keyword'] != ''){	
			if($_GET['search_by'] == 'no_mr'){
				$this->db->where('t.no_mr', $_GET['keyword'] );
			}
			if($_GET['search_by'] == 'nama_pasien'){
				$this->db->like('mt_master_pasien.nama_pasien', $_GET['keyword'] );
			}
		}

		$this->db->group_by('pm_tc_penunjang_order_detail.created_date, CAST(xray_foto as NVARCHAR(2000)), CAST(diagnosa as NVARCHAR(2000)), CAST(kontra_indikasi as NVARCHAR(2000)), CAST(pm_tc_penunjang_order_detail.keterangan as NVARCHAR(2000)), kode_penunjang, dr_pengirim, t.no_mr, nama_pasien, t.no_kunjungan, t.kode_bagian_asal, mt_bagian.nama_bagian, pm_tc_penunjang_order_detail.status, pm_tc_penunjang_order_detail.id_pm_tc_penunjang');

	}

	public function _get_datatables_order_penunjang()
	{
		
		$this->_main_query_order_penunjang();

		$i = 0;
		// search column array
		$column = ['t.no_mr','nama_pasien','nama_tarif'];

		foreach ($column as $item) 
		{
			if($_POST['search']['value'])
				($i===0) ? $this->db->like($item, $_POST['search']['value']) : $this->db->or_like($item, $_POST['search']['value']);
			$column[$i] = $item;
			$i++;
		}

		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		// print_r($this->db->last_query());die;
		return $query->result();

	}
	
	function count_filtered_order_penunjang()
	{
		return count($this->_get_datatables_order_penunjang());
	}

	public function count_all_order_penunjang()
	{
		$this->_main_query_order_penunjang();
		return $this->db->count_all_results();
	}

	function get_datatables_order_penunjang()
	{
		
		$query = $this->db->get();
		// print_r($this->db->last_query());die;
		return $query->result();
	}


	function get_penunjang_by_no_mr($no_mr){
		if( ! $penunjang = $this->cache->get('rm_penunjang_medis_'.$no_mr.'_'.date('Y-m-d').'') )
		{
			$this->db->select('tc_kunjungan.no_kunjungan,tc_kunjungan.no_mr,tc_kunjungan.no_registrasi,mt_karyawan.nama_pegawai as dokter, asal.nama_bagian as asal_bagian, tujuan.nama_bagian as tujuan_bagian, mt_master_pasien.nama_pasien, tc_kunjungan.tgl_masuk, tc_kunjungan.tgl_keluar,status_isihasil,kode_penunjang,pm_tc_penunjang.flag_mcu, status_daftar, kode_bagian_tujuan');
			$this->db->select('tgl_daftar, tgl_isihasil, tgl_periksa');
			$this->db->select("CAST((
				SELECT '|' + nama_tindakan
				FROM tc_trans_pelayanan
				LEFT JOIN pm_tc_penunjang ON pm_tc_penunjang.no_kunjungan=tc_trans_pelayanan.no_kunjungan
				LEFT JOIN tc_kunjungan s ON s.no_kunjungan=pm_tc_penunjang.no_kunjungan
				WHERE s.no_kunjungan = tc_kunjungan.no_kunjungan
				FOR XML PATH(''))as varchar(max)) as nama_tarif");
			$this->db->from('tc_kunjungan');
			$this->db->join('mt_master_pasien','mt_master_pasien.no_mr=tc_kunjungan.no_mr','left');
			$this->db->join('mt_karyawan','mt_karyawan.kode_dokter=tc_kunjungan.kode_dokter','left');
			$this->db->join('mt_bagian as asal','asal.kode_bagian=tc_kunjungan.kode_bagian_asal','left');
			$this->db->join('mt_bagian as tujuan','tujuan.kode_bagian=tc_kunjungan.kode_bagian_tujuan','left');
			$this->db->join('pm_tc_penunjang','pm_tc_penunjang.no_kunjungan=tc_kunjungan.no_kunjungan','left');
			$this->db->where('tc_kunjungan.no_mr', $no_mr);
			$this->db->where('tgl_isihasil is not null');
			$this->db->where('DATEDIFF(year,tgl_masuk,GETDATE()) < 2 ');
			$this->db->where('SUBSTRING(kode_bagian_tujuan, 1, 2) =', '05');
			$this->db->order_by('tgl_masuk', 'DESC');
			$penunjang = $this->db->get()->result();
			$this->cache->save('rm_penunjang_medis_'.$no_mr.'_'.date('Y-m-d').'', $penunjang, 3600);
		}

		$getDataPm = [];
		foreach ($penunjang as $key_pm => $val_pm) {
			$getDataPm[strtolower($val_pm->tujuan_bagian)][] = $val_pm;
		}

		return $getDataPm;
	}
	


}
