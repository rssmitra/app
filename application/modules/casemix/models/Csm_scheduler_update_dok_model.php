<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Csm_scheduler_update_dok_model extends CI_Model {


	public function __construct()
	{
		parent::__construct();
	}

    public function get_data(){
		$this->db->from('csm_reg_pasien');
		$this->db->where('is_scheduler', 1);
		$this->db->where('csm_rp_tipe', 'RJ');
		if(isset($_GET['tglMasuk'])){
			$this->db->where('csm_rp_tgl_masuk', $_GET['tglMasuk']);
		}
		$this->db->order_by('csm_rp_no_sep', 'ASC');
		$this->db->limit(1);
		$query = $this->db->get();
		return $query->row();
    }
}
