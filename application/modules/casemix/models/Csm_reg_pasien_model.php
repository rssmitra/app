<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Csm_billing_pasien_model extends CI_Model {


	var $table = 'csm_reg_pasien';
	var $column = array();
	var $select = 'csm_reg_pasien.*';
	var $order = array('csm_reg_pasien.no_sep' => 'DESC');
	

	public function __construct()
	{
		parent::__construct();
		$this->load->database('default', TRUE);
		$this->schema = $this->lib_table->schema($this->table);
	}

	
}
