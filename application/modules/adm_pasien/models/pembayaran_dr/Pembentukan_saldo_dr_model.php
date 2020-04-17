<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pembentukan_saldo_dr_model extends CI_Model {

	var $table = 'log_billing_dr';
	var $column = array('a.no_registrasi');
	var $select = 'a.no_registrasi, a.tgl_jam, a.no_mr, a.nama_pasien_layan, b.seri_kuitansi, bill_dr1, bill_dr2';
	var $order = array('a.tgl_jam' => 'ASC');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}
	
	function get_datatables()
	{
		$str_where_1 = '';
		$str_where_2 = '';
		$str_where_date = '';
		if( isset($_GET['kode_dokter']) AND $_GET['kode_dokter'] ){
			$str_where_1 .= 'kode_dokter1 = '.$_GET['kode_dokter'].'';
			$str_where_2 .= 'kode_dokter2 = '.$_GET['kode_dokter'].'';
		}else{
			$str_where_1 .= 'kode_dokter1 IS NOT NULL';
			$str_where_2 .= 'kode_dokter2 IS NOT NULL';
		}

		if ( isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' AND isset($_GET['to_tgl']) AND $_GET['to_tgl'] != '' ) {
			$str_where_date .= "CAST(a.tgl_jam as DATE) BETWEEN '".$_GET['from_tgl']."' AND '".$_GET['to_tgl']."' ";	
		}else{
			$str_where_date .= "MONTH(a.tgl_jam) = '".date('m')."' AND YEAR(a.tgl_jam) = '".date('Y')."'";
		}

		$query = "select 
		CASE
			WHEN t_dr_1.kode_dokter1 is NOT null THEN kode_dokter1
			ELSE kode_dokter2
		END AS kode_dokter,

		CASE
			WHEN dr1.nama_pegawai IS NOT NULL THEN dr1.nama_pegawai
			ELSE dr2.nama_pegawai
		END AS nama_dokter,

		bill_dr1, bill_dr2
		from (
			select a.kode_dokter1,  SUM(CONVERT(BIGINT, bill_dr1)) as bill_dr1
			from log_billing_dr a
			where a.status_paid IS NULL AND ".$str_where_1." AND ".$str_where_date."
			group by a.kode_dokter1
		) as t_dr_1
		
		FULL OUTER JOIN(
			select a.kode_dokter2, SUM(CONVERT(BIGINT, bill_dr2)) as bill_dr2
			from log_billing_dr a
			where a.status_paid IS NULL AND ".$str_where_2." AND ".$str_where_date."
			group by a.kode_dokter2
		) as t_dr_2 on t_dr_1.kode_dokter1=t_dr_2.kode_dokter2
		LEFT JOIN mt_dokter_v dr1 on dr1.kode_dokter=t_dr_1.kode_dokter1
		LEFT JOIN mt_dokter_v dr2 on dr2.kode_dokter=t_dr_2.kode_dokter2
		WHERE (kode_dokter1 != 0 or kode_dokter2 != 0)
		GROUP BY kode_dokter1, kode_dokter2, dr1.nama_pegawai, dr2.nama_pegawai, bill_dr1, bill_dr2
		ORDER BY dr1.nama_pegawai, dr2.nama_pegawai ASC
		";

		$query = $this->db->query( $query );
		// print_r($this->db->last_query());die;
		return $query;
	}

	function count_filtered()
	{
		$query = $this->get_datatables();
		return $query->num_rows();
	}

	public function count_all()
	{
		$query = $this->get_datatables()->result();
		return $this->db->count_all_results();
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
		$query = $this->get_datatables();
		foreach($query->result() as $row){
			$arr_total[] = $row->bill_dr1 + $row->bill_dr2;
		}
		$total = array_sum($arr_total);
        return $total;
	}

	public function get_total_billing_dr_current_day(){
		$this->db->select('CAST(SUM(bill_dr1 + bill_dr2) AS INT) as total_billing');
		$this->db->from('tc_trans_pelayanan a');
		$this->db->join('tc_registrasi b','b.no_registrasi=a.no_registrasi','inner');

		if( isset($_GET['kode_dokter']) AND $_GET['kode_dokter'] ){
			$this->db->where("(kode_dokter1=".$_GET['kode_dokter']." OR kode_dokter2=".$_GET['kode_dokter'].")");
		}

		$this->db->where("CAST(b.tgl_jam_masuk as DATE) = '".date('Y-m-d')."' ");
		$this->db->where('(b.status_batal is null or status_batal=0)');
		$this->db->where("(a.kode_bagian = '".$_GET['kode_bagian']."' or b.kode_bagian_masuk='".$_GET['kode_bagian']."') ");

		$query = $this->db->get()->row();
        return $query;
	}

	public function get_detail_pasien($kode_dokter='', $from_tgl='', $to_tgl=''){

		$query = "SELECT a.tgl_jam, a.no_mr, a.nama_pasien_layan, mt_perusahaan.nama_perusahaan, mt_bagian.nama_bagian, nama_tindakan, a.status_paid, a.no_kunjungan,
		CASE
			WHEN a.kode_dokter1 = ".$kode_dokter." THEN kode_dokter1
			WHEN a.kode_dokter2 = ".$kode_dokter." THEN kode_dokter2
			WHEN a.kode_dokter1 = a.kode_dokter2 THEN kode_dokter1
		END AS kode_dokter,
		CASE
			WHEN a.kode_dokter1 = ".$kode_dokter." THEN bill_dr1
			WHEN a.kode_dokter2 = ".$kode_dokter." THEN bill_dr2
			WHEN a.kode_dokter1 = a.kode_dokter2 THEN (bill_dr1 + bill_dr2)
		END AS billing
		FROM log_billing_dr a 
		LEFT JOIN mt_perusahaan ON mt_perusahaan.kode_perusahaan=a.kode_perusahaan 
		LEFT JOIN mt_bagian ON mt_bagian.kode_bagian=a.kode_bagian 
		LEFT JOIN mt_dokter_v dr1 ON dr1.kode_dokter=a.kode_dokter1 
		LEFT JOIN mt_dokter_v dr2 ON dr2.kode_dokter=a.kode_dokter2 
		WHERE (a.kode_dokter1 = ".$kode_dokter." OR a.kode_dokter2 = ".$kode_dokter.") 
		AND CAST(a.tgl_jam as DATE) BETWEEN '".$from_tgl."' AND '".$to_tgl."' 
		GROUP BY a.tgl_jam, a.no_mr, a.nama_pasien_layan, mt_perusahaan.nama_perusahaan, mt_bagian.nama_bagian, a.bill_dr1, a.kode_dokter1, a.bill_dr2, a.kode_dokter2, nama_tindakan, a.status_paid, a.no_kunjungan, dr1.nama_pegawai, dr2.nama_pegawai ORDER BY no_kunjungan ASC";
		$exc_query = $this->db->query($query);

		return $exc_query;
		
	}

}
