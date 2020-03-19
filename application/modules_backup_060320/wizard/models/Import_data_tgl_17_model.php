<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Import_data_tgl_17_model extends CI_Model {

	private $sqlsrv;

	public function __construct()
	{
		parent::__construct();
		$this->sqlsrv = $this->load->database('sqlsrv', TRUE);
	}

    public function insert_multiple($table, $data){
        $this->sqlsrv->insert_batch($table, $data);
        return true;
    }

    public function get_no_reg($num){
        $max_no_reg = $this->sqlsrv->query("select MAX(no_registrasi) as max_num from tc_registrasi")->row();
        $no_registrasi = $max_no_reg->max_num + $num;
        return $no_registrasi;
    }

    public function get_no_kunjungan($num){
        $max_no_kunjungan = $this->sqlsrv->query("select max(no_kunjungan)as max_num from tc_kunjungan")->row();
        $no_kunjungan = $max_no_kunjungan->max_num + $num;
        return $no_kunjungan;
    }

     public function get_kode_trans_pelayanan($num){
        $max_kode_tr = $this->sqlsrv->query("select MAX(kode_trans_pelayanan) as max_num from tc_trans_pelayanan")->row();
        $kode_trans_pelayanan = $max_kode_tr->max_num + $num;
        return $kode_trans_pelayanan;
    }

    public function update_tgl_kunjungan($date){
        $data = array('tgl_masuk' => $date, 'tgl_keluar' => $date);
        $this->sqlsrv->update("tc_kunjungan", $data, array('is_17' => 1));
        return true;
    }


}
