<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Self_service extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('Print_direct');
        $this->load->model('antrian/antrian_model'); 
        $this->load->model('antrian/loket_model','loket');
        $this->load->model('display_loket/main_model','Main');  

    }

    public function index() {
        
        $data = array();

        $this->load->view('Self_service/index', $data);
    }

    public function mandiri_bpjs() {
        
        $data = array();

        $this->load->view('Self_service/index_bpjs', $data);
    }

    public function mandiri_umum() {
        
        $data = array();

        $this->load->view('Self_service/index_umum', $data);
    }

    public function antrian_poli() {
        
        $data_loket = $this->loket->get_open_loket();

        foreach ($data_loket as $key => $value) {
            # code...
            $kuota = $this->loket->get_sisa_kuota($value);
            if($kuota<0)$kuota=0;
            $data_loket[$key]->kuota = $kuota;
        }

        //print_r($_GET['type']);die;
        $data['type'] = isset($_GET['type'])?$_GET['type']:'bpjs';
        
        $data['klinik'] = $data_loket;

        $this->load->view('Self_service/index_antrian_poli', $data);
    }

    public function form_rujukan() {
        
        $data = array();
        $data['profile'] = $this->findKodeBooking($_GET['kode']);
        $data['kode'] = $_GET['kode'];
        // echo '<pre>'; print_r($data);die;
        $this->load->view('Self_service/form_rujukan', $data);
    }
    
    public function findKodeBooking($kode)
	{
        
		$this->db->select('no_mr, nama, jam_pesanan, mt_dokter_v.nama_pegawai as nama_dr, mt_bagian.nama_bagian, kode_poli_bpjs');
		$this->db->from('tc_pesanan');
		$this->db->where('unique_code_counter', $kode);
		$this->db->join('mt_bagian', 'mt_bagian.kode_bagian=tc_pesanan.no_poli','left');
		$this->db->join('mt_dokter_v', 'mt_dokter_v.kode_dokter=tc_pesanan.kode_dokter','left');
        $exc = $this->db->get();
		if ($exc->num_rows() == 0) {
            return false;
		}else{
			$dt = $exc->row();
			$result = array(
				'kode' => $kode,
				'no_mr' => $dt->no_mr,
				'nama' => $dt->nama,
				'kode_poli_bpjs' => $dt->kode_poli_bpjs,
				'tgl_kunjungan' => $this->tanggal->formatDatedmY($dt->jam_pesanan),
				'nama_dr' => strtoupper($dt->nama_dr),
				'poli' => strtoupper($dt->nama_bagian),
				'jam_praktek' => $this->tanggal->formatDateTimeToTime($dt->jam_pesanan),
			);
			return $result;
		}
    }
    

}

/* End of file empty_module.php */
/* Location: ./application/modules/empty_module/controllers/empty_module.php */

