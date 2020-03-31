<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auto_hitung_ruangan_model extends CI_Model {


	public function __construct()
	{
		parent::__construct();
	}

    public function get_data(){
		$this->db->from('ri_cari_pasien_v');
		$this->db->order_by('no_registrasi');
		$query = $this->db->get();
		return $query->result();
    }

    public function get_tarif_by_klas($kode_klas)
    {
        $this->db->from('mt_master_tarif_ruangan');
		$this->db->where('kode_klas',$kode_klas);
		$query = $this->db->get();
		return $query->row();
    }

    public function get_tarif_by_kode_bag($kode_bagian,$kode_klas)
    {
        $this->db->from('mt_master_tarif_ruangan');
        $this->db->where('kode_bagian',$kode_bagian);
		$this->db->where('kode_klas',$kode_klas);
		$query = $this->db->get();
		return $query->row();
    }

    public function get_ruangan($kode_ruangan)
    {
        $this->db->from('mt_ruangan');
        $this->db->where('kode_ruangan',$kode_ruangan);
		$query = $this->db->get();
		return $query->row();
    }

    public function cek_trans($no_kunjungan){
		$this->db->from('tc_trans_pelayanan');
        $this->db->where(array('no_kunjungan' => $no_kunjungan, 'YEAR(tgl_transaksi)' => date('Y'), 'MONTH(tgl_transaksi)' => date('m'), 'DAY(tgl_transaksi)' => date('d')));
        $this->db->where("jenis_tindakan = 1 and nama_tindakan like 'Ruangan%'");
		$query = $this->db->get();
		return $query->row();

	
    }
    
    public function save($table, $data)
	{
		/*insert tc_registrasi*/
		$this->db->insert($table, $data);
		
		return $this->db->insert_id();;
	}
}
