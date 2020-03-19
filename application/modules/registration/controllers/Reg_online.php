<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Reg_online extends MX_Controller {

    function __construct() {
        parent::__construct();
        //print_r($_POST);die;
        /*load libraries*/
        $this->load->library('bcrypt');
        $this->load->library('logs');
        $this->load->library('Form_validation');
        /*load model*/
        $this->load->model('Reg_online_model');
    }

    public function process_register(){

        // form validation
        $this->form_validation->set_rules('fullname', 'Nama Lengkap', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required|valid_email|is_unique[regon_acc_register.regon_accreg_email]', array('is_unique' => '"Email" ini sudah pernah digunakan sebelumnya') );
        $this->form_validation->set_rules('phone_number', 'Nomor Handphone', 'trim|required|regex_match[/^[0-9*#+]+$/]', array('regex_match' => '"Nomor Handphone" tidak sesuai dengan format'));
        $this->form_validation->set_rules('security_code', 'Kata Sandi', 'trim|required|min_length[6]');
        $this->form_validation->set_rules('confirm_security_code', 'Konfirmasi Kata Sandi', 'trim|required|matches[security_code]');

        // set message error
        $this->form_validation->set_message('required', "Silahkan isi field \"%s\"");
        $this->form_validation->set_message('min_length', "\"%s\" minimal 6 karakter");
        $this->form_validation->set_message('matches', "\"%s\" tidak sesuai dengan Kata Sandi");
        $this->form_validation->set_message('valid_email', "\"%s\" tidak sesuai dengan Format");

        if ($this->form_validation->run() == FALSE)
        {
            $this->form_validation->set_error_delimiters('<div style="color:white"><i>', '</i></div>');
            //die(validation_errors());
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                

            $dataexc = array(
                'fullname' => $this->regex->_genRegex($this->form_validation->set_value('fullname'),'RGXQSL'),
                'email' => $this->regex->_genRegex($this->form_validation->set_value('email'),'RGXQSL'),
                'phone_number' => $this->regex->_genRegex($this->form_validation->set_value('phone_number'),'RGXQSL'),
                'security_code' => $this->form_validation->set_value('security_code'),
                'confirm_security_code' => $this->form_validation->set_value('confirm_security_code'),
            );

            try {
                
                /*process with API*/
                $config = [
                    'link' => $this->api->base_api_ws().'registration/Reg_online/process_register',
                    'data' => $dataexc,
                ];

                $response = $this->api->getDataWs( $config );

                echo json_encode($response);

            } catch (Exception $e) {
                
                $response = $this->ws_auth->failed_response( $e );

                echo json_encode( $response );

            }
            

        }

    }

    public function process_verifikasi_data_pasien(){

        // form validation
        $this->form_validation->set_rules('fullname', 'Nama Lengkap', 'trim|required');
        $this->form_validation->set_rules('pob', 'Tempat Lahir', 'trim|required');
        $this->form_validation->set_rules('dob', 'Tanggal Lahir', 'trim|required');
        $this->form_validation->set_rules('address', 'Alamat', 'trim|required');
        $this->form_validation->set_rules('no_telp', 'no_telp', 'trim|required');
        $this->form_validation->set_rules('gender', 'gender', 'trim|required');
        $this->form_validation->set_rules('no_mr', 'No MR', 'trim|required');
        $this->form_validation->set_rules('no_ktp', 'No KTP', 'trim|required');
        $this->form_validation->set_rules('user_id', 'user_id', 'trim|required');

        // set message error
        $this->form_validation->set_message('required', "Silahkan isi field \"%s\"");

        if ($this->form_validation->run() == FALSE)
        {
            $this->form_validation->set_error_delimiters('<div style="color:white"><i>', '</i></div>');
            //die(validation_errors());
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {    

            $dataexc = array(
                'fullname' => $this->regex->_genRegex($this->form_validation->set_value('fullname'),'RGXQSL'),
                'pob' => $this->regex->_genRegex($this->form_validation->set_value('pob'),'RGXQSL'),
                'dob' => $this->regex->_genRegex($this->form_validation->set_value('dob'),'RGXQSL'),
                'address' => $this->regex->_genRegex($this->form_validation->set_value('address'),'RGXQSL'),
                'no_telp' => $this->regex->_genRegex($this->form_validation->set_value('no_telp'),'RGXQSL'),
                'no_hp' => $this->regex->_genRegex($this->form_validation->set_value('no_telp'),'RGXQSL'),
                'gender' => $this->regex->_genRegex($this->form_validation->set_value('gender'),'RGXAZ'),
                'no_mr' => $this->regex->_genRegex($this->form_validation->set_value('no_mr'),'RGXQSL'),
                'no_ktp' => $this->regex->_genRegex($this->form_validation->set_value('no_ktp'),'RGXQSL'),
                'user_id' => $this->regex->_genRegex($this->form_validation->set_value('user_id'),'RGXINT'),
                'token' => $_POST['token'],
            );
            
            try {
                
                /*process with API*/
                $config = [
                    'link' => $this->api->base_api_ws().'registration/Reg_online/process_verifikasi_data_pasien',
                    'data' => $dataexc,
                ];

                $response = $this->api->getDataWs( $config );

                /*save data on session*/
                $this->session->set_userdata(array('user_profile' => (object)$dataexc));

                echo json_encode($response);

            } catch (Exception $e) {
                
                $response = $this->ws_auth->failed_response( $e );

                echo json_encode( $response );

            }

        }

    }

    public function process_verification(){


        try {
            
            /*post data*/
            $post_data = array(
                'key' => $this->input->post('key'),
                'hash_code' => $this->input->post('hash_code'),
                );

            /*process with API*/
            $config = [
                'link' => $this->api->base_api_ws().'registration/Reg_online/process_verification',
                'data' => $post_data,
            ];

            $response = $this->api->getDataWs( $config );

            echo json_encode($response);

        } catch (Exception $e) {
            
            $response = $this->ws_auth->failed_response( $e );

            echo json_encode( $response );

        }

    }

    public function validate_captcha()
    {
        $recaptchaResponse = trim($this->input->post('g-recaptcha-response'));
 
        $userIp=$this->input->ip_address();
     
        $secret = $this->config->item('google_secret');
   
        $url="https://www.google.com/recaptcha/api/siteverify?secret=".$secret."&response=".$recaptchaResponse."&remoteip=".$userIp;
        $output = $this->api->getApiData($url);

        //print_r($output);die;
         
        $status= json_decode($output, true);
        if ($status['success']) {
            return true;
        }else{
            echo json_encode(array('status' => 301, 'message' => 'Google Recaptcha Unsuccessfull !'));
            exit;
        }
 
        //redirect('form', 'refresh');
    }  


    public function check_email($input_email){
        /*check email existing*/
        $result = $this->Reg_online_model->validate_email_existing($input_email);
        if ($result > 0) {
            $this->form_validation->set_message('check_email', 'Email ini sudah pernah digunakan sebelumnya');
            return true;
        }else{
            return true;
        }
    }

    public function verification() {
        $data = array();
        $security_code = $this->input->get('uid');
        $data['hash_code'] = $security_code;
        $this->load->view('verification_view', $data);

    }

}

/* End of file empty_module.php */
/* Location: ./application/modules/empty_module/controllers/empty_module.php */

