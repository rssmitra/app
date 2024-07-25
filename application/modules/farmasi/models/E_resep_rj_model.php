<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class E_resep_rj_model extends CI_Model {

	var $table = 'fr_listpesanan_v';
	var $column = array('nama_pasien', 'no_mr');
	var $select = 'fr_listpesanan_v.kode_bagian, fr_listpesanan_v.kode_bagian_asal, tgl_pesan, status_tebus, jumlah_r, lokasi_tebus, keterangan, fr_listpesanan_v.no_registrasi, fr_listpesanan_v.no_kunjungan, fr_listpesanan_v.kode_perusahaan, kode_klas, fr_listpesanan_v.kode_kelompok, nama_pegawai, nama_lokasi, nama_bagian, fr_listpesanan_v.kode_dokter, fr_listpesanan_v.kode_pesan_resep, fr_listpesanan_v.no_mr, fr_listpesanan_v.nama_pasien, mt_perusahaan.nama_perusahaan, mt_nasabah.nama_kelompok, fr_listpesanan_v.resep_farmasi, no_sep, status_resep';

	var $order = array('kode_pesan_resep' => 'ASC');

	public function __construct()
	{
		parent::__construct();
	}



	private function _main_query(){

		$this->db->select($this->select);
		$this->db->select('(SELECT top 1 diagnosa_akhir FROM th_riwayat_pasien WHERE no_kunjungan=fr_listpesanan_v.no_kunjungan) as diagnosa_akhir');
		$this->db->from($this->table);
		$this->db->join('mt_perusahaan','mt_perusahaan.kode_perusahaan=fr_listpesanan_v.kode_perusahaan','left');
		$this->db->join('mt_nasabah','mt_nasabah.kode_kelompok=fr_listpesanan_v.kode_kelompok','left');
		$this->db->join('tc_registrasi','tc_registrasi.no_registrasi=fr_listpesanan_v.no_registrasi','left');
		$this->db->where('e_resep', 1);
	}

	private function _get_datatables_query()
	{
		
		$this->_main_query();

		if(isset($_GET['no_mr']) AND $_GET['no_mr']!=0 ){
			if($_GET['no_mr']!='' or $_GET['no_mr']!=0){
				$this->db->where('fr_listpesanan_v.no_mr', $_GET['no_mr']);
			}
		}

		if(isset($_GET['search_by']) AND isset($_GET['keyword'])){
			$this->db->like('fr_listpesanan_v.'.$_GET['search_by'].'', $_GET['keyword']);
		}

		if(isset($_GET['klinik'])){
			if($_GET['klinik']!='' or $_GET['klinik']!=0){
				$this->db->where('fr_listpesanan_v.kode_bagian', (int)$_GET['klinik']);
			}
		}

		if(isset($_GET['dokter']) AND $_GET['dokter']!=0 ){
			if($_GET['dokter']!='' or $_GET['dokter']!=0){
				$this->db->where('fr_listpesanan_v.kode_dokter', $_GET['dokter']);
			}
		}

		if (isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' or isset($_GET['to_tgl']) AND $_GET['to_tgl'] != '') {
            $this->db->where("CAST(fr_listpesanan_v.tgl_pesan as DATE) >= '".$_GET['from_tgl']."'" );
            $this->db->where("CAST(fr_listpesanan_v.tgl_pesan as DATE) <= '".$_GET['to_tgl']."'" );
        }else{
        	if( $_GET['flag']=='RJ' ){
				$this->db->where('DATEDIFF(Hour, tgl_pesan, getdate()) <= 24');			
        	}else{
				$this->db->where('DATEDIFF(Hour, tgl_pesan, getdate()) <= 72');			
        	}
        }

        // default for this modul
        // $this->db->where('(status_tebus is null or status_tebus = 0)');

		$i = 0;
		$col_str = '';
		foreach ($this->column as $item) 
		{
			if($_POST['search']['value'])
				$col_str .= ($i===0) ? $item." LIKE '%".$_POST['search']['value']."%' " : "OR ".$item." LIKE '%".$_POST['search']['value']."%'";
			$column[$i] = $item;
			$i++;
		}

		if( $col_str != ''){
			$this->db->where('('.$col_str.')');
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
		$this->_get_datatables_query();
		return $this->db->count_all_results();
	}

	public function get_by_id($id)
	{
		$this->_main_query();
		if($_GET['tipe_layanan']=='RJ'){
			$this->db->select('kode_poli');
			$this->db->join('pl_tc_poli','pl_tc_poli.no_kunjungan=fr_listpesanan_v.no_kunjungan','left');
		}else if($_GET['tipe_layanan']=='RI'){
			$this->db->select('kode_ri');
			$this->db->join('ri_tc_rawatinap','ri_tc_rawatinap.no_kunjungan=fr_listpesanan_v.no_kunjungan','left');
		}
		if(is_array($id)){
			$this->db->where_in(''.$this->table.'.kode_pesan_resep',$id);
			$query = $this->db->get();
			return $query->result();
		}else{
			$this->db->where(''.$this->table.'.kode_pesan_resep',$id);
			$query = $this->db->get();
			return $query->row();
		}
		
	}

	public function save($table, $data)
	{
		/*insert tc_registrasi*/
		$this->db->insert($table, $data);
		return $this->db->insert_id();;
	}

	public function update($table, $data, $where)
	{
		$this->db->update($table, $data, $where);
		return $this->db->affected_rows();
	}

	public function delete_by_id($id)
	{
		$get_data = $this->get_by_id($id);
		$this->db->where_in(''.$this->table.'.no_registrasi', $id);
		return $this->db->update($this->table, array('is_deleted' => 'Y', 'is_active' => 'N'));
	}

	public function save_pm($table, $data)
	{
		/*insert tc_registrasi*/
		$this->db->insert($table, $data);
		
		return $this->db->insert_id();;
	}

	public function get_detail_by_kode_brg($kode_brg)
	{
		return $this->db->join('mt_jenis_obat','mt_jenis_obat.kode_jenis=mt_barang.kode_jenis','left')
						->join('mt_pabrik','mt_pabrik.id_pabrik=mt_barang.id_pabrik','left')
						->get_where('mt_barang', array('kode_brg' => $kode_brg))->row();		
	}

	public function get_detail_by_kode_tr_resep($id)
	{
		$this->_main_query_detail();
		$this->db->where('(a.kd_tr_resep = '.$id.' OR a.id_tc_far_racikan = '.$id.')');	
		return $this->db->get()->row();	
	}

	private function _main_query_detail(){
		$this->db->select("CASE WHEN a.id_tc_far_racikan = 0 THEN c.nama_brg ELSE b.nama_brg END as nama_brg", false);
		$this->db->select("CASE WHEN b.harga_jual_satuan IS NULL THEN a.harga_jual ELSE b.harga_jual_satuan END as harga_jual", false);
		$this->db->select("CASE WHEN b.sub_total IS NULL THEN a.biaya_tebus ELSE b.sub_total END as sub_total", false);
		$this->db->select("CASE WHEN b.total IS NULL THEN (a.biaya_tebus + a.harga_r) ELSE b.total END as total", false);
		// $this->db->select("CASE WHEN b.jasa_r IS NULL THEN a.harga_r ELSE (b.jasa_r + b.jasa_produksi) END as jasa_r", false);
		$this->db->select("CASE WHEN a.id_tc_far_racikan = 0 THEN a.kd_tr_resep ELSE a.id_tc_far_racikan END as relation_id", false);
		$this->db->select("CASE WHEN a.id_tc_far_racikan = 0 THEN 'biasa' ELSE 'racikan' END as flag_resep", false);
		$this->db->select("CASE WHEN b.status_input IS NULL THEN a.status_input ELSE b.status_input END as status_input", false);
		$this->db->select('a.kd_tr_resep, a.kode_trans_far, a.kode_brg, a.id_tc_far_racikan, c.satuan_kecil, b.urgensi, b.dosis_obat, b.dosis_per_hari, b.aturan_pakai, b.anjuran_pakai, b.catatan_lainnya, b.status_tebus, a.tgl_input, b.prb_ditangguhkan, b.jumlah_obat_23, b.satuan_obat, a.resep_ditangguhkan, a.jumlah_tebus, a.jumlah_pesan, b.jasa_r, d.kode_pesan_resep, e.id_tc_log_mutasi_obat');
		$this->db->from('fr_tc_far_detail a');
		$this->db->join('fr_tc_far d','(d.kode_trans_far=a.kode_trans_far)','left');
		$this->db->join('fr_tc_far_detail_log b','(a.kd_tr_resep=b.relation_id)','left');
		$this->db->join('mt_barang c','c.kode_brg=a.kode_brg','left');
		$this->db->join('fr_tc_log_mutasi_obat e','e.kd_tr_resep=a.kd_tr_resep','left');

	}

	private function _get_datatables_query_detail()
	{
		$this->_main_query_detail();
		$this->db->where('a.kode_trans_far', $_GET['relationId']);
		$this->db->order_by('a.kd_tr_resep','ASC');
		// $this->db->where('fr_tc_far_detail_log.flag_resep', $_GET['flag']);
	}
	
	function get_detail_resep_data()
	{
		$this->_get_datatables_query_detail();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		// print_r($this->db->last_query());die;
		return $query->result();
	}

	function count_filtered_detail()
	{
		$this->_get_datatables_query_detail();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all_detail()
	{
		$this->_main_query_detail();
		return $this->db->count_all_results();
	}

	public function get_trans_farmasi($id){
		
		$this->db->select('table_custom.*, tc_trans_pelayanan.kode_tc_trans_kasir');
		$this->db->from('(select a.*, (select count(kd_tr_resep) from fr_tc_far_detail b where b.kode_trans_far=a.kode_trans_far) as total from fr_tc_far a
		where a.kode_pesan_resep='.$id.') as table_custom');
		$this->db->join('(SELECT kode_tc_trans_kasir, kode_trans_far FROM tc_trans_pelayanan GROUP BY kode_tc_trans_kasir,kode_trans_far) as tc_trans_pelayanan','tc_trans_pelayanan.kode_trans_far=table_custom.kode_trans_far','left');
		$this->db->where('total > 0');
		$exc = $this->db->get()->row();
		return $exc;
	}



}
