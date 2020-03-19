<?php
 header("Access-Control-Allow-Origin: *");

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Global_ws extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->library('Form_validation');
        $this->load->library('logs');
        $this->load->library('mailer_2');
        $this->load->library('Print_direct');
        /*load model*/
        $this->load->model('Global_ws_model');
        $this->load->model('registration/Reg_online_model');
        $this->load->model('Global_ws_pasien_model', 'Reg_pasien');

        $this->load->model('booking/Regon_booking_model', 'Regon_booking');

       
    }

    public function index() {

        $email = $this->input->get('key');
        $data = array(
                'email' => $email,
            );
    
        $this->load->view('index', $data);

    }

    public function get_data_pasien_by_mr(){
        /*params*/
        $no_mr = $this->input->get('mr');
        /*check token*/
        /*function to get and check token*/

        $data = $this->Global_ws_model->get_data_pasien_by_mr($no_mr);
        if (isset($data)){
            echo json_encode(array('status' => 200, 'message' => 'Sukses', 'data' => $data));
        } else {
            echo json_encode(array('status' => 301, 'message' => 'MR tidak ditemukan'));
        }
       
    }

    public function get_data_pasien_relasi(){
        /*params*/
        $no_mr = $this->input->get('no_mr');
        /*check token*/
        /*function to get and check token*/

        $data = $this->Global_ws_model->get_data_pasien_relasi($no_mr);

        //print_r($data);die;
        
        if (!empty($data)){
            //$array = json_decode(json_encode($data), True);
            foreach ($data as $value) 
            $array[] = json_decode($value->log_det_no_mr);
            //echo '<pre>';print_r($array);

         //   $out = array_values($array);
            echo stripslashes(json_encode(array('status' => 200, 'message' => 'Sukses', 'data' => $array)));
        } else {
            echo json_encode(array('status' => 301, 'message' => 'MR tidak ditemukan'));
        }
       
    }

    public function reg_relasi(){
    
        /*check token*/
        /*function to get and check token*/

        $this->form_validation->set_rules('no_mr', 'No MR', 'trim|required');

        $this->form_validation->set_message('required', "Silahkan isi field \"%s\"");

        if ($this->form_validation->run() == FALSE)
        {
            $this->form_validation->set_error_delimiters('<div style="color:white"><i>', '</i></div>');
            //die(validation_errors());
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            $this->db->trans_begin();
            $dataexc = array(
                'regon_relasi_pasien_no_mr' => $this->regex->_genRegex($this->form_validation->set_value('no_mr'),'RGXQSL'),
                'regon_relasi_pasien_ref_no_mr' => $this->regex->_genRegex($this->input->post('ref_mr'),'RGXQSL'),
            );

            $cek = $this->Global_ws_model->check_mr($dataexc['regon_relasi_pasien_no_mr'],$dataexc['regon_relasi_pasien_ref_no_mr']);
            if($cek==0){
                $newMr = $this->Global_ws_model->save_reg_relasi($dataexc);
                if ($this->db->trans_status() === FALSE)
                {
                    $this->db->trans_rollback();
                    echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
                }
                else
                {
                    $this->db->trans_commit();
                    echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'data' => $dataexc));
                }
            } else if($cek==5){
                echo json_encode(array('status' => 301, 'message' => 'Maximal hanya 5 pasien'));
            }else{
                echo json_encode(array('status' => 301, 'message' => 'Sudah Didaftarkan'));
            }
        }
       
    }

    public function get_klinik()
    {
        # code...
        $data = $this->Global_ws_model->get_klinik();

        echo json_encode(array('status' => 200, 'message' => 'Sukses', 'data' => $data));
    }

    public function get_dokter()
    {
        # code...
        $data = $this->Global_ws_model->get_dokter();

        echo json_encode(array('status' => 200, 'message' => 'Sukses', 'data' => $data));
    }

    public function get_dokter_by_bagian()
    {
        # code...
        $kode_bag = $this->input->get('kode_bag');
        $data = $this->Global_ws_model->get_dokter_by_bagian($kode_bag);

        echo json_encode(array('status' => 200, 'message' => 'Sukses', 'data' => $data));
    }

    public function get_jd_dokter()
    {
        # code...
        $data = $this->Global_ws_model->get_jd_dokter();

        echo json_encode(array('status' => 200, 'message' => 'Sukses', 'data' => $data));
    }

    public function get_jd_dokter_by_kode()
    {
        # code...
        $kode = $this->input->get('kode');
        $data = $this->Global_ws_model->get_jd_dokter_by_kode($kode);

        echo json_encode(array('status' => 200, 'message' => 'Sukses', 'data' => $data));
    }

    public function get_penjamin()
    {
        # code...
        $data = $this->Global_ws_model->get_penjamin();

        echo json_encode(array('status' => 200, 'message' => 'Sukses', 'data' => $data));
    }

    public function process()
    {

        $this->load->library('form_validation');
        $val = $this->form_validation;

        $val->set_rules('tanggal_kunjungan', 'Tanggal Kunjungan', 'trim|required');
        $val->set_rules('no_mr', 'MR Pasien', 'trim|required', array('required' => 'MR Pasien tidak ditemukan'));
        $val->set_rules('jenis_instalasi', 'Instalasi', 'trim|required');
        $val->set_rules('klinik_rajal', 'Klinik', 'trim|required');
        $val->set_rules('dokter_rajal', 'Dokter', 'trim|required');
        $val->set_rules('selected_day', 'Hari', 'trim|required');
        $val->set_rules('selected_time', 'Jam Praktek', 'trim|required');
        $val->set_rules('no_mr_ref', 'no_mr_ref', 'trim');
        $val->set_rules('jenis_penjamin', 'Jenis Penjamin', 'trim|required');

        if($this->input->post('jenis_penjamin')=='Perusahaan'){
            $val->set_rules('kode_perusahaan', 'Nama Perusahaan', 'trim|required');
        }

        $val->set_message('required', "Silahkan isi field \"%s\"");

        if ($val->run() == FALSE)
        {
            $val->set_error_delimiters('<div style="color:white">', '</div>');
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            $this->db->trans_begin();
            $id = ($this->input->post('id'))?$this->regex->_genRegex($this->input->post('id'),'RGXINT'):0;
            
            $date = date_create($val->set_value('tanggal_kunjungan').' '.$this->input->post('time_start') );
            date_add($date, date_interval_create_from_date_string('-2 hours'));
            $waktu_datang = date_format($date, 'Y-m-d H:i:s');
           
            /*create kode booking*/
            $kode_booking = $this->create_kode_booking();
            $urutan = $this->input->post('last_counter') + 1;
            $dataexc = array(
                'regon_booking_tanggal_perjanjian' => $this->regex->_genRegex($val->set_value('tanggal_kunjungan'), 'RGXQSL'),
                'regon_booking_no_mr' => $this->regex->_genRegex($val->set_value('no_mr'), 'RGXQSL'),
                'regon_booking_instalasi' => $this->regex->_genRegex($val->set_value('jenis_instalasi'), 'RGXQSL'),
                'regon_booking_klinik' => $this->regex->_genRegex($val->set_value('klinik_rajal'), 'RGXQSL'),
                'regon_booking_kode_dokter' => $this->regex->_genRegex($val->set_value('dokter_rajal'), 'RGXQSL'),
                'regon_booking_hari' => $this->regex->_genRegex($val->set_value('selected_day'), 'RGXQSL'),
                'regon_booking_waktu_datang' => $waktu_datang,
                'regon_booking_jam' => $this->regex->_genRegex($val->set_value('selected_time'), 'RGXQSL'),
                'regon_booking_no_mr_ref' => $this->regex->_genRegex($val->set_value('no_mr_ref'), 'RGXQSL'),
                'regon_booking_jenis_penjamin' => $this->regex->_genRegex($val->set_value('jenis_penjamin'), 'RGXQSL'),
                'regon_booking_penjamin' => $this->regex->_genRegex($val->set_value('kode_perusahaan'), 'RGXINT'),
                'regon_booking_status' => '0',
                'regon_booking_urutan' => $this->regex->_genRegex($urutan, 'RGXINT'),
            );

            $detail_pasien = $this->Reg_pasien->get_by_mr($val->set_value('no_mr'),$this->input->post('user_id'));
            $dataexc['log_detail_pasien'] = json_encode( array('nama_pasien' => $detail_pasien->nama_pasien, 'tgl_lahir' => $this->tanggal->formatDate($detail_pasien->tgl_lhr), 'alamat' => $detail_pasien->almt_ttp_pasien, 'telp' => $detail_pasien->tlp_almt_ttp, 'jk' => $detail_pasien->jen_kelamin ) );

            $log_transaksi = array(
                'klinik' => $this->master->get_custom_data('mt_bagian', array('nama_bagian','kode_bagian'), array('kode_bagian' => $val->set_value('klinik_rajal') ) , 'row'),
                'dokter' => $this->master->get_custom_data('mt_karyawan', array('nama_pegawai', 'kode_dokter'), array('kode_dokter' => $val->set_value('dokter_rajal') ) , 'row'),
                'penjamin' => $this->master->get_custom_data('mt_perusahaan', array('nama_perusahaan'), array('kode_perusahaan' => $val->set_value('kode_perusahaan') ) , 'row'),
                );
            
            $datamobile = array(
                'user_id' => $this->input->post('user_id'),
                'fullname' => $this->input->post('fullname')
                ); 

            $dataexc['log_transaksi'] = json_encode($log_transaksi);
                        
            if($id==0){
                $dataexc['regon_booking_kode'] = $this->regex->_genRegex(strtoupper($kode_booking), 'RGXQSL');
                $dataexc['created_date'] = date('Y-m-d H:i:s');
                $dataexc['created_by'] = json_encode(array('user_id' => $this->input->post('user_id'), 'fullname' => $this->input->post('fullname')));
                //print_r($dataexc);die;
                /*save post data*/
                $newId = $this->Regon_booking->save($dataexc);
                /*save logs*/
                $this->logs->save('regon_booking', $newId, 'insert new record on booking module', json_encode($dataexc),'regon_booking_id', $this->input->post('user_id'), $this->input->post('fullname'));
                /*save log kuota dokter*/
                $this->logs->save_log_kuota(array('kode_dokter' => $dataexc['regon_booking_kode_dokter'], 'kode_spesialis' => $dataexc['regon_booking_klinik'], 'tanggal' => $dataexc['regon_booking_tanggal_perjanjian'] ));
            }else{
                $dataexc['updated_date'] = date('Y-m-d H:i:s');
                $dataexc['updated_by'] = json_encode(array('user_id' =>$this->input->post('user_id'), 'fullname' => $this->input->post('fullname')));
                /*update record*/
                $this->Regon_booking->update(array('function_id' => $id), $dataexc);
                $newId = $id;
                /*save logs*/
                $this->logs->save('regon_booking', $newId, 'update record on booking module', json_encode($dataexc),'function_id');
            }

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan','data' => $dataexc, 'redirect' => 'booking/regon_booking/qr_code?kode='.strtoupper($kode_booking).''));
            }
        }
    }

    public function create_kode_booking(){
        $string = $_POST['no_mr'].$_POST['tanggal_kunjungan'].'abcdefghijklmnpqrstuvwxyz';
        $clean_string = str_replace(array('1','i','0','o','/','-'),'',$string);
        $s = substr(str_shuffle(str_repeat($clean_string, 6)), 0, 6);
        return $s;
    }

    public function process_add_new_pasien()
    {
        //print_r($_POST);die;

        $this->load->library('form_validation');
        $val = $this->form_validation;

        $val->set_rules('mrOwner', 'Owner', 'trim|required', array('required' => 'Anda tidak memiliki relasi dengan pasien utama') );
        $val->set_rules('no_mr', 'MR Pasien', 'trim|required', array('required' => 'MR Pasien tidak ditemukan'));
        $val->set_rules('hubungan_relasi', 'Hubungan Pasien', 'trim|required');

        $val->set_message('required', "Silahkan isi field \"%s\"");

        if ($val->run() == FALSE)
        {
            $val->set_error_delimiters('<div style="color:white">', '</div>');
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            $this->db->trans_begin();
            /*log_det_no_mr*/
            $log_mr = array(
                'fullname' => $this->input->post('fullname'),
                'pob' => $this->input->post('pob'),
                'dob' => $this->input->post('dob'),
                'address' => $this->input->post('address'),
                'no_hp' => $this->input->post('no_hp'),
                'gender' => $this->input->post('gender'),
                'no_mr' => $this->input->post('no_mr'),
                );
            /*log_det_ref_no_mr*/
            $log_det_ref_no_mr = $this->Regon_booking->get_log_mr($val->set_value('mrOwner'));

            $dataexc = array(
                'regon_rp_no_mr' => $this->regex->_genRegex($val->set_value('no_mr'), 'RGXQSL'),
                'regon_rp_ref_no_mr' => $this->regex->_genRegex($val->set_value('mrOwner'), 'RGXQSL'),
                'regon_rp_status_relasi' => $this->regex->_genRegex($val->set_value('hubungan_relasi'), 'RGXQSL'),
                'regon_rp_status_aktif' => 1,
                'log_det_no_mr' => json_encode( $log_mr ),
                'log_det_ref_no_mr' => $log_det_ref_no_mr,
            );

            $dataexc['created_date'] = date('Y-m-d H:i:s');
            $dataexc['created_by'] = $this->input->post('nama_pasien');
            /*save post data*/
            $newId = $this->Regon_booking->save_new_pasien($dataexc);
            /*save logs*/
            //$this->logs->save('regon_relasi_pasien', $newId, 'insert new record on '.$this->title.' module', json_encode($dataexc),'regon_rp_id');

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'redirect' => 'booking/regon_booking') );
            }
        }
    }

    public function get_booked()
    {
        # code...
        $no_mr = $this->input->get('no_mr');

        $data = $this->Global_ws_model->get_booked($no_mr);

        echo json_encode(array('status' => 200, 'message' => 'Sukses', 'data' => $data));
    }

    public function get_kuota()
    {
        # code...
        $date = $this->input->get('date');
        $dok = $this->input->get('dok');
        $klinik = $this->input->get('klinik');

        $data = $this->db->get_where('log_kuota_dokter',array('tanggal' => $date, 'kode_dokter' => $dok, 'kode_spesialis' => $klinik))->num_rows();

        echo json_encode(array('status' => 200, 'message' => 'Sukses', 'data' => $data));
    }

    public function send_link()
    {
        # code...
        $email = $this->input->post('email');
        $qry = $this->db->get_where('regon_acc_register', array('regon_accreg_email' => $email));
        $cek =  $qry->num_rows();
        $data =$qry->row();

        if($cek!=0)
        {
            $this->mailer_2->sendemail($data->regon_accreg_email);
        } else {
            echo json_encode(array('status' => 301, 'message' => 'Maaf email tidak terdaftar !!'));
        }
    }

    public function process_reset_pwd(){

        // form validation
        $this->form_validation->set_rules('security_code', 'Kata Sandi', 'trim|required|min_length[6]');
        $this->form_validation->set_rules('confirm_security_code', 'Konfirmasi Kata Sandi', 'trim|required|matches[security_code]');

        // set message error
        $this->form_validation->set_message('required', "Silahkan isi field \"%s\"");
        $this->form_validation->set_message('min_length', "\"%s\" minimal 6 karakter");
        $this->form_validation->set_message('matches', "\"%s\" tidak sesuai dengan Kata Sandi");

        if ($this->form_validation->run() == FALSE)
        {
            $this->form_validation->set_error_delimiters('<div style="color:white"><i>', '</i></div>');
            //die(validation_errors());
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            /*execution*/
            $this->db->trans_begin();

            $dataexc = array(
                'regon_accreg_email' => $this->input->post('email'),
                'regon_accreg_password' => $this->bcrypt->hash_password($this->form_validation->set_value('security_code'))
            );
            /*validate recaptcha*/
            //$this->validate_captcha();

            /*save post data*/
            $newId = $this->Reg_online_model->update_acc_register($dataexc);
    
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan'));
            }

        
        }

    }

    public function get_global_param()
    {
        # code...
        $flag = $this->input->get('flag');

        $data = $this->Global_ws_model->get_global_param($flag);

        echo json_encode(array('status' => 200, 'message' => 'Sukses', 'data' => $data));
    }

    public function test()
    {
        # code...
        $data_tracer = [
            'no_mr' => $_POST['no_mr'],
            'result' => $_POST['result'],
        ];

         $this->print_direct->printer_php($data_tracer);

    }
}

/* End of file empty_module.php */
/* Location: ./application/modules/empty_module/controllers/empty_module.php */

