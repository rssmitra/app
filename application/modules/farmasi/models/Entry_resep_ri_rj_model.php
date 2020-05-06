<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Entry_resep_ri_rj_model extends CI_Model {

	var $table = 'fr_listpesanan_v';
	var $column = array('nama_pasien', 'no_mr');
	var $select = 'fr_listpesanan_v.kode_bagian, fr_listpesanan_v.kode_bagian_asal, tgl_pesan, status_tebus, jumlah_r, lokasi_tebus, keterangan, fr_listpesanan_v.no_registrasi, fr_listpesanan_v.no_kunjungan, fr_listpesanan_v.kode_perusahaan, kode_klas, fr_listpesanan_v.kode_kelompok, nama_pegawai, nama_lokasi, nama_bagian, fr_listpesanan_v.kode_dokter, fr_listpesanan_v.kode_pesan_resep, fr_listpesanan_v.no_mr, fr_listpesanan_v.nama_pasien, mt_perusahaan.nama_perusahaan, mt_nasabah.nama_kelompok';

	var $order = array('kode_pesan_resep' => 'ASC');

	public function __construct()
	{
		parent::__construct();
	}



	private function _main_query(){

		$this->db->select($this->select);
		$this->db->from($this->table);
		$this->db->join('mt_perusahaan','mt_perusahaan.kode_perusahaan=fr_listpesanan_v.kode_perusahaan','left');
		$this->db->join('mt_nasabah','mt_nasabah.kode_kelompok=fr_listpesanan_v.kode_kelompok','left');
		
	}

	private function _get_datatables_query()
	{
		
		$this->_main_query();

		if($_GET['flag']=='RJ'){
			$this->db->where('(fr_listpesanan_v.kode_bagian_asal like '."'02%'".' OR fr_listpesanan_v.kode_bagian_asal like '."'01%'".')');
		}else{
			$this->db->where('(fr_listpesanan_v.kode_bagian_asal like '."'03%'".')');
		}


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
			// $this->db->where('DATEDIFF(Hour, tgl_pesan, getdate()) <= 24');
			$this->db->where('CAST(tgl_pesan as DATE) = ', date('Y-m-d'));
			$this->db->where('(status_tebus is null or status_tebus = 0)');
        }

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
		}else{
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
		$this->db->from('fr_tc_far_detail_log a');	
		$this->db->where('relation_id', $id);	
		return $this->db->get()->row();	
	}

	private function _main_query_detail(){
		$this->db->select('*');
		$this->db->from('fr_tc_far_detail_log');
	}

	private function _get_datatables_query_detail()
	{
		
		$this->_main_query_detail();
		$this->db->where('fr_tc_far_detail_log.kode_trans_far', $_GET['relationId']);
		$this->db->order_by('id_fr_tc_far_detail_log','DESC');
		// $this->db->where('fr_tc_far_detail_log.flag_resep', $_GET['flag']);

	}
	
	function get_detail_resep_data()
	{
		$this->_get_datatables_query_detail();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
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



}
