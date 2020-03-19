<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Reg_online_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function save_acc_register($dataexc){
        $this->db->insert('regon_acc_register', $dataexc);
        return $this->db->insert_id();
    }

    public function update_acc_register($dataexc){
        return $this->db->update('regon_acc_register', array('regon_accreg_password' => $dataexc->regon_accreg_password), array('regon_accreg_email' => $dataexc->regon_accreg_email));
    }

    public function save_profile_user($dataexc){
        $this->db->insert('tmp_user_profile', $dataexc);
        return $this->db->insert_id();
    }

    public function update_data_pasien($dataexc){
        return $this->db->update('mt_master_pasien', array('jen_kelamin' => $dataexc['gender'],'no_ktp' => $dataexc['no_ktp'], 'tempat_lahir' => $dataexc['pob'], 'tgl_lhr' => $this->tanggal->sqlDatetoDateTime($dataexc['dob']),'almt_ttp_pasien' => $dataexc['address'], 'no_hp' => $dataexc['no_hp']), array('no_mr' => $dataexc['no_mr']));
    }

    public function get_by_id($id){
        $query = $this->db->get_where('regon_acc_register', array('regon_accreg_id' => $id));
        return $query->row();
    }

    public function verifikasi_security_code($security_code){
        $query = $this->db->get_where('regon_acc_register', array('regon_accreg_security_code' => $security_code));
        return $query->row();
    }

    public function validate_email_existing($email){
        $query = $this->db->get_where('regon_acc_register', array('regon_accreg_email' => $email))->num_rows();
        return $query;
    }

    public function update_status_account($ref_id, $flag_active){
        return $this->db->update('regon_acc_register', array('regon_accreg_status' => $flag_active), array('regon_accreg_id' => $ref_id) );
    }

    public function check_security_code($ref_id, $flag_active){
        return $this->db->update('regon_acc_register', array('regon_accreg_status' => $flag_active), array('regon_accreg_id' => $ref_id) );
    }

    public function create_account($data){
    	$dataexc = array(
    		'email' => $data->regon_accreg_email,
    		'fullname' => $data->regon_accreg_fullname,
    		'username' => $data->regon_accreg_email,
    		'password' => $data->regon_accreg_password,
    		'is_active' => 'Y',
    		'is_deleted' => 'N',
    		'flag_user' => 'Publik',
    		'created_date' => date('Y-m-d H:i:s'),
    		'created_by' => $data->regon_accreg_fullname,
    		);
    	$this->db->insert('tmp_user', $dataexc);
    	$new_id = $this->db->insert_id();
    	/*save user role*/
    	$this->db->insert('tmp_user_has_role', array('user_id' => $new_id, 'role_id' => 2));
    }

    public function create_pasien_owner($data){

        $dataexc = array(
            'regon_rp_no_mr' => $data['no_mr'],
            'regon_rp_ref_no_mr' => 'Owner',
            'regon_rp_status_relasi' => 'Owner',
            'regon_rp_status_aktif' => 1,
            'log_det_no_mr' => json_encode($data),
            'log_det_ref_no_mr' => '',
            'created_date' => date('Y-m-d H:i:s'),
            'created_by' => $data['fullname'],
            );

        return $this->db->insert('regon_relasi_pasien', $dataexc);

    }


}
