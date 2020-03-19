<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Tarif_pelayanan_model extends CI_Model {

	var $table = 'pl_tc_poli';
	var $column = array('pl_tc_poli.nama_pasien','mt_karyawan.nama_pegawai');
	var $select = 'pl_tc_poli.kode_bagian,pl_tc_poli.no_kunjungan,pl_tc_poli.no_antrian,pl_tc_poli.nama_pasien, id_pl_tc_poli, pl_tc_poli.status_periksa, tc_kunjungan.no_mr, mt_perusahaan.nama_perusahaan, mt_nasabah.nama_kelompok, pl_tc_poli.tgl_jam_poli, mt_karyawan.nama_pegawai,tc_registrasi.no_registrasi, tc_registrasi.kode_kelompok, tc_registrasi.kode_perusahaan, tc_kunjungan.kode_bagian_asal, tc_kunjungan.status_keluar, pl_tc_poli.kode_dokter, pl_tc_poli.status_batal, pl_tc_poli.created_by';

	var $order = array('pl_tc_poli.no_antrian' => 'ASC');

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
		$this->db->select($this->select);
		$this->db->from($this->table);
		$this->db->join('tc_kunjungan',''.$this->table.'.no_kunjungan=tc_kunjungan.no_kunjungan','left');
		$this->db->join('mt_karyawan','mt_karyawan.kode_dokter=pl_tc_poli.kode_dokter','left');
		$this->db->join('tc_registrasi','tc_registrasi.no_registrasi=tc_kunjungan.no_registrasi','left');
		$this->db->join('mt_perusahaan','tc_registrasi.kode_perusahaan=mt_perusahaan.kode_perusahaan','left');
		$this->db->join('mt_nasabah','tc_registrasi.kode_kelompok=mt_nasabah.kode_kelompok','left');
		$this->db->where('pl_tc_poli.kode_bagian='."'".$this->session->userdata('kode_bagian')."'".'');
		$this->db->where('pl_tc_poli.kode_dokter='."'".$this->session->userdata('sess_kode_dokter')."'".'');
		//$this->db->where('(pl_tc_poli.status_periksa=0 or pl_tc_poli.status_periksa IS NULL)');

		if(isset($_GET['search_by']) AND isset($_GET['keyword'])){
			if( $_GET['keyword'] != '' ){
				$this->db->like(''.$_GET['search_by'].'', $_GET['keyword']);
			}
		}

		if (isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' || isset($_GET['to_tgl']) AND $_GET['to_tgl'] != '') {
			$this->db->where("convert(varchar,pl_tc_poli.tgl_jam_poli,23) between '".$_GET['from_tgl']."' and '".$_GET['to_tgl']."'");					
        }else{
        	$this->db->where(array('YEAR(pl_tc_poli.tgl_jam_poli)' => date('Y'), 'MONTH(pl_tc_poli.tgl_jam_poli)' => date('m'), 'DAY(pl_tc_poli.tgl_jam_poli)' => date('d') ) );
        }



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
		//print_r($this->db->last_query());die;
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
			$this->db->where_in(''.$this->table.'.id_pl_tc_poli',$id);
			$query = $this->db->get();
			return $query->result();
		}else{
			$this->db->where(''.$this->table.'.id_pl_tc_poli',$id);
			$query = $this->db->get();
			//print_r($this->db->last_query());die;
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

	/*data tindakan hd*/

	private function _main_query_tindakan(){
		$this->db->from('tc_trans_pelayanan');
		$this->db->join('tc_kunjungan','tc_kunjungan.no_kunjungan=tc_trans_pelayanan.no_kunjungan','left');
		$this->db->join('mt_karyawan','mt_karyawan.kode_dokter=tc_trans_pelayanan.kode_dokter1','left');
		$this->db->join('mt_karyawan as dokter2','dokter2.kode_dokter=tc_trans_pelayanan.kode_dokter2','left');
		$this->db->join('mt_karyawan as dokter3','dokter3.kode_dokter=tc_trans_pelayanan.kode_dokter3','left');
		$this->db->where( array('tc_trans_pelayanan.no_kunjungan' => $_GET['kode'], 'tc_trans_pelayanan.kode_bagian' => $_GET['bagian'], 'flag_perawat' => 1) );

		if($_GET['jenis']=='tindakan'){
			$this->db->where_in('jenis_tindakan', array(3,4,10,12,13,7,5,8,14) );
		}else{
			$this->db->where_in('jenis_tindakan', array(9,11) );
		}
	}

	private function _get_datatables_query_tindakan()
	{
		$column = array('tc_trans_pelayanan.no_kunjungan', 'tc_trans_pelayanan.kode_bagian', 'tc_trans_pelayanan.nama_tindakan', 'mt_karyawan.nama_pegawai', 'tc_trans_pelayanan.tgl_transaksi', 'tc_trans_pelayanan.bill_rs', 'tc_trans_pelayanan.bill_dr1', 'tc_trans_pelayanan.bill_dr2');
		$select = 'tc_trans_pelayanan.kode_tc_trans_kasir,tc_trans_pelayanan.no_kunjungan, tc_trans_pelayanan.kode_bagian, tc_trans_pelayanan.nama_tindakan, mt_karyawan.nama_pegawai, dokter2.nama_pegawai as dokter_2, dokter3.nama_pegawai as dokter_3, tc_trans_pelayanan.tgl_transaksi, tc_trans_pelayanan.bill_rs, tc_trans_pelayanan.bhp, tc_trans_pelayanan.pendapatan_rs, tc_trans_pelayanan.alat_rs, tc_trans_pelayanan.bill_dr1, tc_trans_pelayanan.bill_dr2, tc_trans_pelayanan.bill_dr3, tc_trans_pelayanan.harga_satuan, tc_trans_pelayanan.kode_trans_pelayanan, tc_trans_pelayanan.jumlah';

		$this->db->select($select);
		$this->_main_query_tindakan();

		$i = 0;
		
		

		$order = array('kode_trans_pelayanan' => 'DESC');

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

	function get_datatables_tindakan()
	{
		$this->_get_datatables_query_tindakan();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		//print_r($this->db->last_query());die;
		return $query->result();
	}

	function count_filtered_tindakan()
	{
		$this->_get_datatables_query_tindakan();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all_tindakan()
	{
		$this->_main_query_tindakan();
		return $this->db->count_all_results();
	}

	public function delete_trans_pelayanan($id)
	{
		$this->load->library('stok_barang');

		/*get data by kode_trans_pelayanan*/
		$data = $this->db->get_where('tc_trans_pelayanan', array('kode_trans_pelayanan' => $id) )->row();
		if( in_array( $data->jenis_tindakan, array(9, 11) ) ){
			/*restore stok barang*/
			$this->stok_barang->stock_process($data->kode_barang, (int)$data->jumlah, $data->kode_bagian,6, 'Dihapus', 'restore');
		}

		/*delete trans pelayanan*/
		$this->db->where('tc_trans_pelayanan.kode_trans_pelayanan', $id);
		return $this->db->delete('tc_trans_pelayanan');
	}

	public function cek_transaksi_minimal($no_kunjungan){
		$transaksi_min = $this->db->get_where('tc_trans_pelayanan', array('no_kunjungan' => $no_kunjungan) )->num_rows();
		if( $transaksi_min > 0 ){
			return true;
		}else{
			return false;
		}
	}

	public function get_riwayat_pasien_by_id($no_kunjungan){
		return $this->db->get_where('th_riwayat_pasien', array('no_kunjungan' => $no_kunjungan) )->row();
	}

	public function cek_transaksi_kasir($no_registrasi, $no_kunjungan){
		$trans_kasir = $this->db->get_where('tc_trans_kasir', array('no_registrasi' => $no_registrasi))->num_rows();
		if($trans_kasir > 0){
			return false;
		}else{
			return true;
		}
	}



}
