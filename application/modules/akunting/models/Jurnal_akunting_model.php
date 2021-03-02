<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Jurnal_akunting_model extends CI_Model {

	var $table = 'tc_trans_pelayanan';
	var $column = array('a.no_registrasi', 'b.no_sep');
	var $select = 'a.no_registrasi, a.no_mr, b.tgl_jam_masuk, b.kode_perusahaan, b.kode_kelompok, b.kode_dokter, b.kode_bagian_masuk, c.nama_pasien, d.nama_bagian, e.nama_perusahaan, a.kode_tc_trans_kasir, b.no_sep, f.nama_kelompok';
	var $order = array('a.no_registrasi' => 'DESC');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _main_query(){

		$this->db->select($this->select);
		$this->db->select('CAST(bill_rs as INT) as bill_rs, CAST(bill_dr1 as INT) as bill_dr1, CAST(bill_dr2 as INT) as bill_dr2, CAST(bill_dr3 as INT) as bill_dr3, CAST(lain_lain as INT) as lain_lain');
		$this->db->from($this->table.' a');
		$this->db->join('tc_registrasi b','b.no_registrasi=a.no_registrasi','left');
		$this->db->join('mt_master_pasien c','c.no_mr=b.no_mr','left');
		$this->db->join('mt_bagian d','d.kode_bagian=b.kode_bagian_masuk','left');
		$this->db->join('mt_perusahaan e','e.kode_perusahaan=b.kode_perusahaan','left');
		$this->db->join('mt_nasabah f','f.kode_kelompok=b.kode_kelompok','left');

		if ( isset($_GET['search_by']) ) {
			if(isset($_GET['keyword']) AND $_GET['keyword'] != ''){
				if($_GET['search_by'] == 'c.nama_pasien'){
					$this->db->like($_GET['search_by'], $_GET['keyword']);		
				}else{
					$this->db->where($_GET['search_by'], $_GET['keyword']);		
				}
			}		

			if( isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' AND isset($_GET['to_tgl']) AND $_GET['to_tgl'] != ''){
				$this->db->where("CAST(b.tgl_jam_masuk as DATE) between '".$_GET['from_tgl']."' and '".$_GET['to_tgl']."'");
			}
			
		}else{
			$this->db->where("CAST(tgl_jam_masuk as DATE) = ", date('Y-m-d'));
		}

			

		// if( $_GET['pelayanan']=='RJ' ){
		// 	if($_GET['flag']=='bpjs'){
		// 		$this->db->where('b.kode_perusahaan', 120);
		// 	}
		// 	if($_GET['flag']=='umum'){
		// 		$this->db->where('b.kode_perusahaan != 120');
		// 	}
		// }
		
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
		$query = $this->db->get()->result();
		// echo '<pre>';print_r($this->db->last_query());die;
		$result = $this->getTotalRow($query);
		
		return $result;
	}

	function getTotalRow($array){
		// print_r($array);die;
		$getData = array();
		foreach ($array as $key => $value) {
			$total = ($value->bill_rs + $value->bill_dr1 + $value->bill_dr2 + $value->bill_dr3 + $value->lain_lain);
			$status_lunas = ($value->kode_tc_trans_kasir == NULL) ? $total : 'Lunas' ;
			if( $_GET['pelayanan'] == 'RI' ){
				if( substr($array[0]->kode_bagian_masuk, 0, 2) == '03'){
					$getData[$value->no_registrasi][] = array(
						'kode_tc_trans_kasir' => $value->kode_tc_trans_kasir,
						'no_sep' => $value->no_sep,
						'no_registrasi' => $value->no_registrasi,
						'no_mr' => $value->no_mr,
						'tgl_jam_masuk' => $value->tgl_jam_masuk,
						'kode_perusahaan' => $value->kode_perusahaan,
						'kode_kelompok' => $value->kode_kelompok,
						'kode_dokter' => $value->kode_dokter,
						'kode_bagian_masuk' => $value->kode_bagian_masuk,
						'nama_pasien' => $value->nama_pasien,
						'nama_bagian' => $value->nama_bagian,
						'nama_perusahaan' => ($value->nama_perusahaan != NULL) ? $value->nama_perusahaan : $value->nama_kelompok,
						'total' => $status_lunas,
						'total_billing' => $total,
					);
				}
			}else{

				if( substr($array[0]->kode_bagian_masuk, 0, 2) != '03'){
					$getData[$value->no_registrasi][] = array(
						'kode_tc_trans_kasir' => $value->kode_tc_trans_kasir,
						'no_sep' => $value->no_sep,
						'no_registrasi' => $value->no_registrasi,
						'no_mr' => $value->no_mr,
						'tgl_jam_masuk' => $value->tgl_jam_masuk,
						'kode_perusahaan' => $value->kode_perusahaan,
						'kode_kelompok' => $value->kode_kelompok,
						'kode_dokter' => $value->kode_dokter,
						'kode_bagian_masuk' => $value->kode_bagian_masuk,
						'nama_pasien' => $value->nama_pasien,
						'nama_bagian' => $value->nama_bagian,
						'nama_perusahaan' => ($value->nama_perusahaan != NULL) ? $value->nama_perusahaan : $value->nama_kelompok,
						'total' => $status_lunas,
						'total_billing' => $total,
					);
				}
			}
		}

		return $getData;
	}

	function count_filtered()
	{
		$this->_get_datatables_query();
		$query = $this->db->get()->result();
		$result = $this->getTotalRow($query);
		return count($result);
	}

	public function count_all()
	{
		$this->_get_datatables_query();
		$query = $this->db->get()->result();
		$result = $this->getTotalRow($query);
		return count($result);
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

	public function get_total_billing(){
		$this->_main_query();
		$this->db->where("YEAR(tgl_jam_masuk)", date('Y'));
		$this->db->where("MONTH(tgl_jam_masuk)", date('m'));
		$this->db->where("DAY(tgl_jam_masuk)", date('d'));
		$query = $this->db->get()->result();
		// print_r($this->db->last_query());
		$result = $this->getTotalRow($query);
        return $result;
    }

    public function get_jurnal_akunting(){
		$this->db->select('a.acc_no, a.tipe_tx, b.acc_nama, SUM(nominal) as nominal, c.acc_nama as acc_ref, c.acc_no as acc_no_ref');
		$this->db->from('ak_tc_transaksi_det a');
		$this->db->join('ak_tc_transaksi d', 'd.id_ak_tc_transaksi=a.id_ak_tc_transaksi', 'inner');
		$this->db->join('mt_account b', 'b.acc_no=a.acc_no', 'inner');
		$this->db->join('mt_account c', 'c.acc_no=b.acc_ref', 'inner');
		$this->db->order_by('a.acc_no', 'ASC');

		if(isset($_GET['checked_kode_akun']) AND $_GET['checked_kode_akun'] == 1){
			if(isset($_GET['from_tgl']) AND $_GET['from_tgl'] != ''){
				$this->db->where('a.acc_no', $_GET['acc_no']);
				$this->db->or_where('b.acc_ref', $_GET['acc_no']);
			}
		}

		if(isset($_GET['checked_tgl']) AND $_GET['checked_tgl'] == 1){
			if(isset($_GET['from_tgl']) AND $_GET['from_tgl'] != ''){
				$this->db->where('CAST(d.tgl_transaksi as DATE) BETWEEN '."'".$_GET['from_tgl']."'".' AND '."'".$_GET['to_tgl']."'".' ');
			}
		}
		$this->db->group_by('a.acc_no, a.tipe_tx, b.acc_nama, c.acc_nama, c.acc_no');
		$exc = $this->db->get()->result();
		// print_r($this->db->last_query());die;
		$getData = array();
		foreach( $exc as $row_exc ){
			$getData[$row_exc->acc_ref][] = $row_exc;
		}

		return array('result' => $exc, 'data' => $getData);
    }


}
