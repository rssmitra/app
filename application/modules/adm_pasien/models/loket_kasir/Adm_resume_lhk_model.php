<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Adm_resume_lhk_model extends CI_Model {

	var $table = 'tc_trans_kasir';
	var $column = array('a.no_registrasi', 'b.no_sep');
	var $select = 'no_kuitansi, seri_kuitansi, a.no_registrasi, a.kode_tc_trans_kasir, CAST(tgl_jam as DATE)as tgl_transaksi, nama_pasien, pembayar, CAST(tunai as FLOAT) as tunai, CAST(debet as FLOAT) as debet, CAST(kredit as FLOAT) as kredit, CAST(nk_perusahaan as FLOAT) as piutang, CAST(bill as FLOAT)as billing, CAST(nk_karyawan as FLOAT)as nk_karyawan,CAST(potongan as FLOAT)as potongan, nama_pegawai';
	var $order = array('a.no_registrasi' => 'DESC');

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
	}

	private function _main_query(){

		$this->db->select($this->select);
		$this->db->from($this->table.' a');
		$this->db->join('mt_karyawan b','b.no_induk=a.no_induk','left');
		$this->db->join('tc_registrasi c','c.no_registrasi=a.no_registrasi','left');

		if ( isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' ) {
			$this->db->where("CAST(a.tgl_jam as DATE) = '".$_GET['from_tgl']."'");			
		}else{
			$this->db->where("CAST(a.tgl_jam as DATE) = '".date('Y-m-d')."'");
		}

		$this->db->where('a.seri_kuitansi', $_GET['flag']);

		if ( isset($_GET['kode_perusahaan']) AND $_GET['kode_perusahaan'] != '' ) {
			$this->db->where('a.kode_perusahaan', $_GET['kode_perusahaan']);
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
		print_r($this->db->last_query());die;
		return $query;
	}

	function get_index_data()
	{
		// resume by unit
		$from_tgl_str = '';
		if ( isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' ) {
			$from_tgl_str .= "CAST(a.tgl_jam as DATE) = '".$_GET['from_tgl']."'";			
		}else{
			$from_tgl_str .= "CAST(a.tgl_jam as DATE) = '".date('Y-m-d')."'";
		}
		$query = "SELECT a.kode_tc_trans_kasir, mt_bagian.kode_bagian, mt_bagian.nama_bagian, mt_karyawan.nama_pegawai, mt_jenis_tindakan.jenis_tindakan, kode_master_tarif_detail, nama_tindakan, SUM(bill_rs) as bill_rs, SUM(bill_dr1) as bill_dr1, SUM(bill_dr2) as bill_dr2, SUM(kamar_tindakan) as kamar_tindakan, SUM(pendapatan_rs) as pendapatan_rs, SUM(bhp) as bhp, SUM(biaya_lain) as biaya_lain, SUM(alkes) as alkes, SUM(alat_rs) as alat_rs, SUM(adm) as adm, SUM(obat) as obat, SUM(bill_bs_rs)as bill_bs_rs, SUM(lain_lain) as lain_lain 
			FROM tc_trans_pelayanan a 
			LEFT JOIN mt_bagian ON mt_bagian.kode_bagian=a.kode_bagian 
			LEFT JOIN mt_jenis_tindakan ON mt_jenis_tindakan.kode_jenis_tindakan=a.jenis_tindakan 
			LEFT JOIN tc_trans_kasir ON tc_trans_kasir.kode_tc_trans_kasir=a.kode_tc_trans_kasir 
			LEFT JOIN mt_karyawan ON mt_karyawan.no_induk=tc_trans_kasir.no_induk 
			WHERE a.kode_tc_trans_kasir in 
			( SELECT a.kode_tc_trans_kasir FROM tc_trans_kasir a WHERE ".$from_tgl_str." AND a.seri_kuitansi = 'RJ' AND (".$_GET['method']." >0) GROUP BY a.kode_tc_trans_kasir) 
			GROUP BY a.kode_tc_trans_kasir,mt_bagian.kode_bagian, mt_bagian.nama_bagian,mt_karyawan.nama_pegawai, mt_jenis_tindakan.jenis_tindakan, kode_master_tarif_detail, nama_tindakan ORDER BY nama_bagian ASC";
		$exc_qry_1 = $this->db->query( $query );
		// print_r($this->db->last_query());die;

		// resume 
		$query_2 = "SELECT  nama_pegawai, SUM(bill) as bill, SUM(tunai) as tunai, SUM(debet) as debet, SUM(kredit) as kredit, SUM(nk_perusahaan) as piutang, SUM(bill)as billing, SUM(nk_karyawan)as nk_karyawan, SUM(cetak_kartu) as cetak_kartu, SUM(potongan)as potongan
			FROM tc_trans_kasir a
			LEFT JOIN mt_karyawan b ON b.no_induk=a.no_induk
			WHERE ".$from_tgl_str."
			AND a.seri_kuitansi = 'RJ' AND ".$_GET['method']." > 0
			GROUP BY  nama_pegawai";

		$exc_qry_2 = $this->db->query( $query_2 );


		$query_3 = "SELECT a.*, mt_jenis_tindakan.jenis_tindakan as nama_jenis_tindakan, mt_bagian.nama_bagian, mt_karyawan.nama_pegawai as nama_dokter, mt_klas.nama_klas 
			FROM tc_trans_pelayanan a 
			LEFT JOIN mt_bagian ON mt_bagian.kode_bagian=a.kode_bagian 
			LEFT JOIN mt_jenis_tindakan ON mt_jenis_tindakan.kode_jenis_tindakan=a.jenis_tindakan 
			LEFT JOIN tc_trans_kasir ON tc_trans_kasir.kode_tc_trans_kasir=a.kode_tc_trans_kasir 
			LEFT JOIN mt_karyawan ON mt_karyawan.no_induk=tc_trans_kasir.no_induk 
			LEFT JOIN mt_klas ON mt_klas.kode_klas=a.kode_klas 
			WHERE a.kode_tc_trans_kasir in 
			( SELECT a.kode_tc_trans_kasir FROM tc_trans_kasir a WHERE ".$from_tgl_str." AND a.seri_kuitansi = 'RJ' AND (".$_GET['method']." >0) GROUP BY a.kode_tc_trans_kasir) ORDER BY nama_bagian ASC";
		$exc_qry_3 = $this->db->query( $query_3 );

		$data = array(
			'trans_data' => $exc_qry_3,
			'data' => $exc_qry_1->result_array(),
			'resume' => $exc_qry_2->result_array(),
			'fields' => $exc_qry_1->field_data(),
		);
		return $data;
	}

	function get_index_data_by_pasien($method)
	{
		$from_tgl_str = '';
		if ( isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' ) {
			$from_tgl_str .= "CAST(a.tgl_jam as DATE) = '".$_GET['from_tgl']."'";			
		}else{
			$from_tgl_str .= "CAST(a.tgl_jam as DATE) = '".date('Y-m-d')."'";
		}
		// resume by pasien
		$query = "SELECT a.*, mt_jenis_tindakan.jenis_tindakan as nama_jenis_tindakan, mt_bagian.nama_bagian, tc_trans_kasir.bill, tc_trans_kasir.tunai, tc_trans_kasir.debet,tc_trans_kasir.kredit, tc_trans_kasir.potongan , tc_trans_kasir.nk_perusahaan, tc_trans_kasir.nk_karyawan, tc_trans_kasir.cetak_kartu, tc_trans_kasir.tgl_jam
			FROM tc_trans_pelayanan a 
			LEFT JOIN mt_bagian ON mt_bagian.kode_bagian=a.kode_bagian 
			LEFT JOIN mt_jenis_tindakan ON mt_jenis_tindakan.kode_jenis_tindakan=a.jenis_tindakan 
			LEFT JOIN tc_trans_kasir ON tc_trans_kasir.kode_tc_trans_kasir=a.kode_tc_trans_kasir 
			WHERE a.kode_tc_trans_kasir in 
			( SELECT a.kode_tc_trans_kasir FROM tc_trans_kasir a WHERE ".$from_tgl_str." AND a.seri_kuitansi = 'RJ' AND (".$method." >0) GROUP BY a.kode_tc_trans_kasir) ORDER BY tgl_jam ASC";
		$exc_qry = $this->db->query( $query );
		// print_r($this->db->last_query());die;
		$data = array(
			'trans_data' => $exc_qry,
			'fields' => array('bill_kamar_perawatan', 'bill_kamar_icu', 'bill_tindakan_inap', 'bill_tindakan_oksigen', 'bill_tindakan_bedah', 'bill_tindakan_vk', 'bill_obat','bill_ambulance', 'bill_dokter', 'bill_apotik', 'bill_lain_lain', 'bill_adm', 'bill_ugd', 'bill_rad', 'bill_lab', 'bill_fisio', 'bill_klinik', 'bill_pemakaian_alat', 'bill_tindakan_hd','bill_tindakan_luar_rs'),
		);
		return $data;
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

	public function get_resume_kasir(){
		$this->db->select('CAST(SUM(tunai) as INT) as tunai, CAST(SUM(debet) as INT) as debet, CAST(SUM(kredit) as INT) as kredit, CAST(SUM(nk_perusahaan) as INT) as nk_perusahaan, CAST(SUM(nk_karyawan) as INT) as nk_karyawan, CAST(SUM(bill) as INT) as bill');
		$this->db->from($this->table);
		if ( isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' ) {
			$this->db->where("CAST(tgl_jam as DATE) = '".$_GET['from_tgl']."'");			
		}else{
			$this->db->where("CAST(tgl_jam as DATE) = '".date('Y-m-d')."'");
		}

		$this->db->where('seri_kuitansi', $_GET['flag']);

		if ( isset($_GET['kode_perusahaan']) AND $_GET['kode_perusahaan'] != '' ) {
			$this->db->where('kode_perusahaan', $_GET['kode_perusahaan']);
		}
		$query = $this->db->get()->row();
        return $query;
	}
	
	public function get_jurnal_akunting($kode_tc_trans_kasir){
    	$query = "select a.*, b.acc_nama, c.acc_nama as acc_ref, c.acc_no as acc_no_ref, d.tgl_transaksi from ak_tc_transaksi_det a
    				inner join ak_tc_transaksi d on d.id_ak_tc_transaksi=a.id_ak_tc_transaksi
					inner join mt_account b on b.acc_no=a.acc_no
					inner join mt_account c on c.acc_no=b.acc_ref
					where a.id_ak_tc_transaksi 
					in( select id_ak_tc_transaksi from ak_tc_transaksi where kode_tc_trans_kasir = ".$kode_tc_trans_kasir.") order by a.acc_no ASC";
		$exc = $this->db->query($query)->result();
		$getData = array();
		foreach( $exc as $row_exc ){
			$getData[$row_exc->acc_ref][] = $row_exc;
		}

		return array('result' => $exc, 'data' => $getData);
    }


    public function sumByResumeBill($array, $column, $field) {
		$sum = array();
	   	foreach ($array as $key => $val) {
	   		if( $val[key($column[0])] == $column[0][key($column[0])] )
	   		{
	   			$sum[] = $val[$field];
	   			$getData[] = $val;
	   		}
	   	}
		// echo '<pre>';print_r(array_sum($sum));die;
	   	return array_sum($sum);
	}

	public function getResumeBilling( $getColumn ){
		
		$resume_billing = array();
        foreach ($getColumn as $key => $value) {
            foreach($value as $key_col => $row_col){
                $resume_billing[$key] = 
                    array(
                        'no_mr' => $row_col->no_mr, 
                        'nama_pasien' => $row_col->nama_pasien_layan,
                        'tgl_jam' => $row_col->tgl_jam,
                        'bill' => $row_col->bill, 
                        'potongan' => $row_col->potongan, 
                        'tunai' => $row_col->tunai, 
                        'debet' => $row_col->debet, 
                        'kredit' => $row_col->kredit, 
                        'nk_karyawan' => $row_col->nk_karyawan, 
                        'nk_perusahaan' => $row_col->nk_perusahaan, 
                        'cetak_kartu' => $row_col->cetak_kartu
                    );

                $getResume[$key][] = $this->Csm_billing_pasien->resumeBillingRI($row_col);
            }
            $resume_billing[$key]['resume_billing'] = $getResume;
        }

        // echo '<pre>';print_r($resume_billing);die;

        // split
        $getDataBilling = array();
        if(count($resume_billing) > 0){
            foreach ($resume_billing as $key => $value) {
                
                $arr_split_dt = $value['resume_billing'][$key];
                $split_billing[$key] = $this->Csm_billing_pasien->splitResumeBillingRI($arr_split_dt);
                // echo '<pre>';print_r($split_billing[$key]);die;
                if(count($split_billing[$key]) > 0){
                    foreach ($split_billing[$key] as $k => $val) {
                        /*total*/
                        if((int)$val['subtotal'] > 0){
                            $getDataBilling[$key] = 
                                array(
                                    'no_mr' => $value['no_mr'],
                                    'nama_pasien' => $value['nama_pasien'], 
                                    'tgl_jam' => $value['tgl_jam'], 
                                    'bill' => $value['bill'], 
                                    'potongan' => $value['potongan'], 
                                    'tunai' => $value['tunai'], 
                                    'debet' => $value['debet'], 
                                    'kredit' => $value['kredit'], 
                                    'nk_karyawan' => $value['nk_karyawan'], 
                                    'nk_perusahaan' => $value['nk_perusahaan'], 
                                    'cetak_kartu' => $value['cetak_kartu']
                                );
                            $getDetailBill[$key][] = $val;
                        }  
                    }

                    $getDataBilling[$key]['detail_bill'] = $getDetailBill[$key];
                }

            }
        }

        return $getDataBilling;
	}

}
