<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pl_pelayanan_model extends CI_Model {

	var $table = 'pl_tc_poli';
	var $column = array('pl_tc_poli.nama_pasien','mt_karyawan.nama_pegawai');
	var $select = 'pl_tc_poli.kode_bagian,pl_tc_poli.no_kunjungan,pl_tc_poli.no_antrian,pl_tc_poli.nama_pasien, id_pl_tc_poli, pl_tc_poli.status_periksa, tc_kunjungan.no_mr, mt_perusahaan.nama_perusahaan, mt_nasabah.nama_kelompok, pl_tc_poli.tgl_jam_poli, mt_karyawan.nama_pegawai,tc_registrasi.no_registrasi, tc_registrasi.kode_kelompok, tc_registrasi.kode_perusahaan, tc_kunjungan.kode_bagian_asal, tc_kunjungan.status_keluar, pl_tc_poli.kode_dokter, pl_tc_poli.status_batal, pl_tc_poli.created_by, pl_tc_poli.tgl_keluar_poli, tc_registrasi.tgl_jam_keluar, pl_tc_poli.flag_ri, pl_tc_poli.flag_mcu, pl_tc_poli.flag_bayar_konsul, pl_tc_poli.kelas_ri, tc_registrasi.no_sep, tc_registrasi.kodebookingantrol, short_name, mt_master_pasien.jen_kelamin, mt_master_pasien.title';
	var $order = array('pl_tc_poli.no_antrian' => 'ASC');

	public function __construct()
	{
		parent::__construct();
	}

	public function save($table, $data)
	{
		/*insert tc_registrasi*/
		$this->db->insert($table, $data);
		
		return $this->db->insert_id();
	}

	private function _main_query(){
		$this->db->select($this->select);
		$this->db->from($this->table);
		$this->db->join('tc_kunjungan',''.$this->table.'.no_kunjungan=tc_kunjungan.no_kunjungan','left');
		$this->db->join('mt_karyawan','mt_karyawan.kode_dokter=pl_tc_poli.kode_dokter','left');
		$this->db->join('tc_registrasi','tc_registrasi.no_registrasi=tc_kunjungan.no_registrasi','left');
		$this->db->join('mt_perusahaan','tc_registrasi.kode_perusahaan=mt_perusahaan.kode_perusahaan','left');
		$this->db->join('mt_nasabah','tc_registrasi.kode_kelompok=mt_nasabah.kode_kelompok','left');
		$this->db->join('mt_bagian','mt_bagian.kode_bagian=pl_tc_poli.kode_bagian','left');
		$this->db->join('mt_master_pasien','mt_master_pasien.no_mr=tc_kunjungan.no_mr','left');

	}

	private function _get_datatables_query()
	{
		
		$this->_main_query();

		if($_GET['bag'] == 0){
			
			if(isset($_GET['poliklinik']) AND $_GET['poliklinik'] != ''){
				$this->db->where('pl_tc_poli.kode_bagian='."'".$_GET['poliklinik']."'".'');
			}

			if(isset($_GET['select_dokter']) AND $_GET['select_dokter'] != 0){
				$this->db->where('pl_tc_poli.kode_dokter='."'".$_GET['select_dokter']."'".'');
			}

			$this->db->where('pl_tc_poli.kode_bagian not in ('."'012801'".', '."'013101'".') ');
			$this->db->where('pl_tc_poli.tgl_keluar_poli is not null');
		}else{
			if( in_array($_GET['bag'], array('012801','013101') ) ) {
				$this->db->where('pl_tc_poli.kode_bagian='."'".$_GET['bag']."'".'');
			}else{
				$this->db->where('pl_tc_poli.kode_bagian='."'".$this->session->userdata('kode_bagian')."'".'');
				$this->db->where('pl_tc_poli.kode_dokter='."'".$this->session->userdata('sess_kode_dokter')."'".'');
			}
		}
		

		if(isset($_GET['search_by']) AND isset($_GET['keyword'])){
			if( $_GET['keyword'] != '' ){
				$this->db->like(''.$_GET['search_by'].'', $_GET['keyword']);
			}
		}
		
		if (isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' || isset($_GET['to_tgl']) AND $_GET['to_tgl'] != '') {
			$this->db->where("convert(varchar,pl_tc_poli.tgl_jam_poli,23) between '".$_GET['from_tgl']."' and '".$_GET['to_tgl']."'");	
			$this->db->order_by('pl_tc_poli.tgl_jam_poli', 'ASC');
		}else{
			if(isset($_GET['search_by']) AND isset($_GET['keyword'])){
				if( $_GET['keyword'] != '' ){
					$this->db->where( 'YEAR(pl_tc_poli.tgl_jam_poli) = ', date('Y') );
					$this->db->order_by('pl_tc_poli.tgl_jam_poli', 'DESC');
				}
			}else{
				$this->db->where( 'CAST(pl_tc_poli.tgl_jam_poli as DATE) = ', date('Y-m-d') );
			}
		}

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

	function get_data_antrian_pasien()
	{

		$kode_bagian = ($this->session->userdata('kode_bagian')) ? $this->session->userdata('kode_bagian') : 0;
		$sess_kode_dokter = ($this->session->userdata('sess_kode_dokter'))?$this->session->userdata('sess_kode_dokter') : 0 ;


		$this->_main_query();
		// $this->db->where('tgl_keluar_poli IS NULL');
		if( in_array($_GET['bag'], array('012801','013101') ) ) {
			$this->db->where('pl_tc_poli.kode_bagian='."'".$_GET['bag']."'".'');
		}else{
			$this->db->where('pl_tc_poli.kode_bagian='."'".$kode_bagian."'".'');
			$this->db->where('pl_tc_poli.kode_dokter='."'".$sess_kode_dokter."'".'');
		}

		if (isset($_GET['tgl'])) {
			$this->db->where('CAST(pl_tc_poli.tgl_jam_poli as DATE) = ', $_GET['tgl'] );
        }else{
        	$this->db->where('CAST(pl_tc_poli.tgl_jam_poli as DATE)  = ', date('Y-m-d') );
		}

        // $this->db->order_by('status_periksa','ASC');
        $this->db->order_by('no_antrian','ASC');
		$query = $this->db->get();
		// print_r($this->db->last_query());die;
		if($query->num_rows() > 0){
			return $query->result();
		}else{
			return [];
		}
	}

	function get_next_antrian_pasien()
	{
		$this->_main_query();
		$this->db->where('tgl_keluar_poli IS NULL');
		$this->db->where('(pl_tc_poli.status_batal is null or pl_tc_poli.status_batal = 0)');
		$this->db->where('pl_tc_poli.kode_bagian='."'".$this->session->userdata('kode_bagian')."'".'');
		$this->db->where('pl_tc_poli.kode_dokter='."'".$this->session->userdata('sess_kode_dokter')."'".'');

		if (isset($_GET['tgl'])) {
			$this->db->where('CAST(pl_tc_poli.tgl_jam_poli as DATE) = ', $_GET['tgl'] );
        }else{
        	$this->db->where('CAST(pl_tc_poli.tgl_jam_poli as DATE)  = ', date('Y-m-d') );
		}

        $this->db->order_by('no_antrian','ASC');
		$query = $this->db->get();
		return $query->row();
	}

	function get_next_antrian_pasien_sess_dr()
	{
		$this->_main_query();
		$this->db->where('status_periksa IS NULL');
		$this->db->where('tgl_keluar_poli IS NULL');
		$this->db->where('(pl_tc_poli.status_batal is null or pl_tc_poli.status_batal = 0)');
		$this->db->where('pl_tc_poli.kode_bagian='."'".$this->session->userdata('kode_bagian')."'".'');
		$this->db->where('pl_tc_poli.kode_dokter='."'".$this->session->userdata('sess_kode_dokter')."'".'');
		if (isset($_GET['tgl'])) {
			$this->db->where('CAST(pl_tc_poli.tgl_jam_poli as DATE) = ', $_GET['tgl'] );
        }else{
        	$this->db->where('CAST(pl_tc_poli.tgl_jam_poli as DATE)  = ', date('Y-m-d') );
		}
        $this->db->order_by('no_antrian','ASC');
		$query = $this->db->get();
		return $query->row();
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
		$this->db->select("(SELECT top 1 kode_perjanjian FROM tc_pesanan WHERE no_mr=tc_kunjungan.no_mr AND CAST(tgl_jam_masuk as DATE) = CAST(pl_tc_poli.tgl_jam_poli as DATE) ORDER BY id_tc_pesanan DESC) as kode_perjanjian");

		if(is_array($id)){
			$this->db->where_in(''.$this->table.'.id_pl_tc_poli',$id);
			$query = $this->db->get();
			return $query->result();
		}else{
			$this->db->where(''.$this->table.'.id_pl_tc_poli',$id);
			$query = $this->db->get();
			// print_r($this->db->last_query());die;
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
		
	}

	private function _get_datatables_query_tindakan()
	{
		$column = array('tc_trans_pelayanan.no_kunjungan', 'tc_trans_pelayanan.kode_bagian', 'tc_trans_pelayanan.nama_tindakan', 'mt_karyawan.nama_pegawai', 'tc_trans_pelayanan.tgl_transaksi', 'tc_trans_pelayanan.bill_rs', 'tc_trans_pelayanan.bill_dr1', 'tc_trans_pelayanan.bill_dr2');
		$select = 'tc_trans_pelayanan.kode_tc_trans_kasir,tc_trans_pelayanan.no_kunjungan, tc_trans_pelayanan.kode_bagian, tc_trans_pelayanan.nama_tindakan, mt_karyawan.nama_pegawai, tc_trans_pelayanan.tgl_transaksi, tc_trans_pelayanan.bill_rs, tc_trans_pelayanan.bhp, tc_trans_pelayanan.pendapatan_rs, tc_trans_pelayanan.alat_rs, tc_trans_pelayanan.bill_dr1, tc_trans_pelayanan.bill_dr2, tc_trans_pelayanan.bill_dr3, tc_trans_pelayanan.harga_satuan, tc_trans_pelayanan.kode_trans_pelayanan, tc_trans_pelayanan.jumlah, tc_trans_pelayanan.satuan_tindakan, tc_trans_pelayanan.tindakan_luar, mt_barang.satuan_kecil, mt_barang.satuan_besar';

		$this->_main_query_tindakan();
		$this->db->select($select);
		$this->db->select('dokter2.nama_pegawai as dokter_2, dokter3.nama_pegawai as dokter_3');
		$this->db->join('mt_barang', 'mt_barang.kode_brg=tc_trans_pelayanan.kode_barang','left');
		$this->db->where( array('tc_trans_pelayanan.no_kunjungan' => $_GET['kode'], 'flag_perawat' => 1) );
		$this->db->group_by('dokter2.nama_pegawai, dokter3.nama_pegawai');
		$this->db->group_by($select);

		if( $_GET['bagian'] == '030901' ){
			$this->db->where('(id_pesan_bedah='.$_GET['id_pesan_bedah'].' or tc_trans_pelayanan.kode_bagian='."'".$_GET['bagian']."'".')');
		}else{
			$this->db->where('(tc_trans_pelayanan.kode_bagian = '."'".$_GET['bagian']."'".' OR tc_trans_pelayanan.kode_bagian_asal = '."'".$_GET['bagian']."'".')');
		}

		if($_GET['jenis']=='tindakan'){
			$this->db->where_in('jenis_tindakan', array(3,4,10,12,13,7,5,8,14) );
		}else{
			$this->db->where_in('jenis_tindakan', array(9) );
		}

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

	public function get_tindakan_by_id($id)
	{
		$select = 'tc_trans_pelayanan.kode_tc_trans_kasir,tc_trans_pelayanan.no_kunjungan, tc_trans_pelayanan.kode_bagian, tc_trans_pelayanan.nama_tindakan, mt_karyawan.nama_pegawai, dokter2.nama_pegawai as dokter_2, dokter3.nama_pegawai as dokter_3, tc_trans_pelayanan.tgl_transaksi, tc_trans_pelayanan.bill_rs, tc_trans_pelayanan.bhp, tc_trans_pelayanan.pendapatan_rs, tc_trans_pelayanan.alat_rs, tc_trans_pelayanan.bill_dr1, tc_trans_pelayanan.bill_dr2, tc_trans_pelayanan.bill_dr3, tc_trans_pelayanan.harga_satuan, tc_trans_pelayanan.kode_trans_pelayanan, tc_trans_pelayanan.jumlah, tc_trans_pelayanan.satuan_tindakan, jenis_tindakan, kode_klas, kode_tarif, kamar_tindakan';

		$this->db->select($select);
		$this->_main_query_tindakan();
		$this->db->where('tc_trans_pelayanan.kode_trans_pelayanan',$id);
		$query = $this->db->get();
		//print_r($this->db->last_query());die;
		return $query->row();
		
	}

	function get_datatables_tindakan()
	{
		$this->_get_datatables_query_tindakan();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		// print_r($this->db->last_query());die;
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
		$this->db->where( array('tc_trans_pelayanan.no_kunjungan' => $_GET['kode'], 'tc_trans_pelayanan.kode_bagian' => $_GET['bagian'], 'flag_perawat' => 1) );

		if($_GET['jenis']=='tindakan'){
			$this->db->where_in('jenis_tindakan', array(3,4,10,12,13,7,5,8,14) );
		}else{
			$this->db->where_in('jenis_tindakan', array(9,11) );
		}
		return $this->db->count_all_results();
	}

	/*get data tindakan mcu */

	private function _main_query_tindakan_mcu(){
		$this->db->from('tc_trans_pelayanan_paket_mcu');
		$this->db->join('tc_kunjungan','tc_kunjungan.no_kunjungan=tc_trans_pelayanan_paket_mcu.no_kunjungan','left');
		$this->db->join('mt_karyawan','mt_karyawan.kode_dokter=tc_kunjungan.kode_dokter','left');
		$this->db->join('mt_karyawan as dokter2','dokter2.kode_dokter=tc_trans_pelayanan_paket_mcu.kode_dokter_mcu_2','left');
		
	}

	private function _get_datatables_query_tindakan_mcu()
	{
		$column = array('tc_trans_pelayanan_paket_mcu.no_kunjungan', 'tc_trans_pelayanan_paket_mcu.kode_bagian', 'tc_trans_pelayanan_paket_mcu.nama_tindakan', 'mt_karyawan.nama_pegawai', 'tc_trans_pelayanan_paket_mcu.tgl_transaksi', 'tc_trans_pelayanan_paket_mcu.bill_rs', 'tc_trans_pelayanan_paket_mcu.bill_dr');
		$select = 'tc_trans_pelayanan_paket_mcu.no_kunjungan, tc_trans_pelayanan_paket_mcu.kode_bagian, tc_trans_pelayanan_paket_mcu.nama_tindakan, mt_karyawan.nama_pegawai, dokter2.nama_pegawai as dokter_2, tc_trans_pelayanan_paket_mcu.tgl_transaksi, tc_trans_pelayanan_paket_mcu.bill_rs, tc_trans_pelayanan_paket_mcu.bill_rs as pendapatan_rs, tc_trans_pelayanan_paket_mcu.bill_dr as bill_dr1, tc_trans_pelayanan_paket_mcu.kode_trans_pelayanan_paket_mcu as kode_trans_pelayanan, tc_trans_pelayanan_paket_mcu.jumlah';

		$this->db->select($select);
		$this->_main_query_tindakan_mcu();

		$this->db->where( array('tc_trans_pelayanan_paket_mcu.no_kunjungan' => $_GET['kode'], 'tc_trans_pelayanan_paket_mcu.kode_bagian' => $_GET['bagian']) );

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

	public function get_tindakan_mcu_by_id($id)
	{
		$select = 'tc_trans_pelayanan_paket_mcu.no_kunjungan, tc_trans_pelayanan_paket_mcu.kode_bagian, tc_trans_pelayanan_paket_mcu.nama_tindakan, mt_karyawan.nama_pegawai, dokter2.nama_pegawai as dokter_2, tc_trans_pelayanan_paket_mcu.tgl_transaksi, tc_trans_pelayanan_paket_mcu.bill_rs, 0 as bhp, tc_trans_pelayanan_paket_mcu.bill_rs as pendapatan_rs, 0 as alat_rs, tc_trans_pelayanan_paket_mcu.bill_dr, NULL as harga_satuan, tc_trans_pelayanan_paket_mcu.kode_trans_pelayanan_paket_mcu as kode_trans_pelayanan, tc_trans_pelayanan_paket_mcu.jumlah, NULL as satuan_tindakan, NULL as tindakan_luar';

		$this->db->select($select);
		$this->_main_query_tindakan_mcu();
		$this->db->where('tc_trans_pelayanan_paket_mcu.kode_trans_pelayanan_paket_mcu',$id);
		$query = $this->db->get();
		//print_r($this->db->last_query());die;
		return $query->row();
		
	}

	function get_datatables_tindakan_mcu()
	{
		$this->_get_datatables_query_tindakan_mcu();
		if($_POST['length'] != -1)
		$this->db->limit($_POST['length'], $_POST['start']);
		$query = $this->db->get();
		//print_r($this->db->last_query());die;
		return $query->result();
	}

	function count_filtered_tindakan_mcu()
	{
		$this->_get_datatables_query_tindakan_mcu();
		$query = $this->db->get();
		return $query->num_rows();
	}

	public function count_all_tindakan_mcu()
	{
		$this->_main_query_tindakan_mcu();
		$this->db->where( array('tc_trans_pelayanan_paket_mcu.no_kunjungan' => $_GET['kode'], 'tc_trans_pelayanan_paket_mcu.kode_bagian' => $_GET['bagian']) );

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

	public function delete_diagnosa($kode_riwayat)
	{
		/*delete riwayat_diagnosa*/
		$this->db->where('th_riwayat_pasien.kode_riwayat', $kode_riwayat);
		return $this->db->delete('th_riwayat_pasien');
	}

	public function delete_ak_tc_transaksi_det($no_kunjungan)
	{
		/*delete ak_tc_transaksi_det*/
		$this->db->where("ak_tc_transaksi_det.id_ak_tc_transaksi in (select id_ak_tc_transaksi from ak_tc_transaksi 
		where kode_tc_trans_kasir in (select kode_tc_trans_kasir from tc_trans_pelayanan where no_kunjungan = ".$no_kunjungan."))");
		return $this->db->delete('ak_tc_transaksi_det');
	}

	public function delete_ak_tc_transaksi($no_kunjungan)
	{
		/*delete ak_tc_transaksi*/
		$this->db->where("ak_tc_transaksi.id_ak_tc_transaksi in (select id_ak_tc_transaksi from ak_tc_transaksi 
		where kode_tc_trans_kasir in (select kode_tc_trans_kasir from tc_trans_pelayanan where no_kunjungan = ".$no_kunjungan."))");
		return $this->db->delete('ak_tc_transaksi');
	}

	public function delete_transaksi_kasir($no_kunjungan)
	{
		/*delete transaksi_kasir*/
		$this->db->where("tc_trans_kasir.kode_tc_trans_kasir in (select kode_tc_trans_kasir from tc_trans_pelayanan where no_kunjungan = ".$no_kunjungan.")");
		return $this->db->delete('tc_trans_kasir');
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
	
	public function get_riwayat_expertise_pasien($no_kunjungan){
		return $this->db->get_where('th_expertise_pasien', array('no_kunjungan' => $no_kunjungan,'jenis_expertise' => $_GET['title'], 'kode_bagian_tujuan' => $_GET['kode_bag_input']) )->row();
	}

	public function cek_transaksi_kasir($no_registrasi, $no_kunjungan){
		// $trans_kasir = $this->db->get_where('tc_trans_kasir', array('no_registrasi' => $no_registrasi))->num_rows();
		$trans_kasir = $this->db->where('kode_tc_trans_kasir IS NOT NULL')->get_where('tc_trans_pelayanan', array('no_registrasi' => $no_registrasi))->num_rows();
		if($trans_kasir > 0){
			return false;
		}else{
			return true;
		}
	}

	public function getComponentTarif($kode_tarif,$kode_klas='')
	{
		# code...
		$this->db->select('bill_dr1, bill_dr2, bill_dr3, bhp, pendapatan_rs, alat_rs, kamar_tindakan');
		$this->db->from('mt_master_tarif_detail');
		$this->db->where( array('kode_tarif' => $kode_tarif) );
		if($kode_klas!=''){
			$this->db->where( '(kode_klas='.$kode_klas.' or kode_klas=0)' );
		}
		$this->db->order_by('revisi_ke','desc');
		$query = $this->db->get()->row();
		return $query;

	}

	public function getComponentTarifLain($kode_trans_pelayanan)
	{
		# code...
		$this->db->select('bill_dr1, bill_dr2, bill_dr3, bhp, pendapatan_rs, alat_rs, kamar_tindakan');
		$this->db->from('tc_trans_pelayanan');
		$this->db->where( array('kode_trans_pelayanan' => $kode_trans_pelayanan) );
		$query = $this->db->get();
		return $query->row();

	}

	public function check_rujukan_pm($pm, $kode_bagian_asal, $no_registrasi){
		$query = $this->db->where( array('kode_bagian_tujuan' => $pm, 'kode_bagian_asal' => $kode_bagian_asal, 'no_registrasi' => $no_registrasi))->get('tc_kunjungan')->result();
		// print_r($this->db->last_query());die;
		if(!empty($query)){
			return true;
		}else{
			return false;
		}
	}

	public function check_resep_fr($kode_bagian_asal, $no_registrasi){
		$query = $this->db->where( array('kode_bagian_asal' => $kode_bagian_asal, 'no_registrasi' => $no_registrasi))->get('fr_tc_pesan_resep')->result();
		// print_r($this->db->last_query());die;
		if(!empty($query)){
			return true;
		}else{
			return false;
		}
	}

	public function callPatient($params){
		// update null all antrian aktif pasien di poli dan dokter aktif
		if(($this->session->userdata('sess_kode_dokter'))){
			$this->db->where('CAST(pl_tc_poli.tgl_jam_poli as DATE) = ', date('Y-m-d'));
			$this->db->where('pl_tc_poli.kode_bagian='."'".$this->session->userdata('kode_bagian')."'".'');
			$this->db->where('pl_tc_poli.kode_dokter='."'".$this->session->userdata('sess_kode_dokter')."'".'');
			$this->db->update('pl_tc_poli', array('antrian_aktif' => 0) );

			// update current to active
			$this->db->where('no_kunjungan', $params['no_kunjungan'])->update('pl_tc_poli', array('antrian_aktif' => 1) );
			return true;
		}else{
			return false;
		}
	}

	


}
