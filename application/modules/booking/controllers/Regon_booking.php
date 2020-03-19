<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Regon_booking extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();

        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'booking/Regon_booking');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }

        /*load model*/
        $this->load->model('Regon_booking_model', 'Regon_booking');
        $this->load->model('registration/Reg_pasien_model', 'Reg_pasien');
        /*enable profiler*/
        $this->output->enable_profiler(false);
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'Title';
        /*define no mr*/
    }

    public function index() { 

        /*define variable data*/
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show(),
        );

        try {
                
            /*process with API*/
            $config = [
                'link' => $this->api->base_api_ws().'booking/regon_booking/listPasienByOwner',
                'data' => array('token' => $this->session->userdata('token'), 'cons_id' => $this->session->userdata('user_profile')->no_mr ),
            ];

            $response = $this->api->getDataWs( $config ); 

            $data['value'] = $response;

        } catch (Exception $e) {
            
            $response = $this->ws_auth->failed_response( $e );

            echo json_encode( $response ); exit;

        }  
        /*load view index*/
        $this->load->view('Regon_booking/index', $data);
    }

    public function addNewPasien($id='')
    {
        /*if id is not null then will show form edit*/
        /*breadcrumbs for create or add row*/
        $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Regon_booking/'.strtolower(get_class($this)).'/form');
        /*initialize flag for form add*/
        $data['flag'] = "create";
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Regon_booking/form_add_pasien', $data);
    }

    public function process_add_new_pasien()
    {
        

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

            /*post data*/
            $dataexc = array(
                'fullname' => $this->input->post('fullname'),
                'pob' => $this->input->post('pob'),
                'dob' => $this->tanggal->sqlDate($this->input->post('dob')),
                'address' => $this->input->post('address'),
                'no_hp' => $this->input->post('no_hp'),
                'gender' => $this->input->post('gender'),
                'no_mr' => $this->input->post('no_mr'),
                'no_mr' => $this->regex->_genRegex($val->set_value('no_mr'), 'RGXQSL'),
                'mrOwner' => $this->regex->_genRegex($val->set_value('mrOwner'), 'RGXQSL'),
                'hubungan_relasi' => $this->regex->_genRegex($val->set_value('hubungan_relasi'), 'RGXQSL'),
                'cons_id' => $this->session->userdata('user_profile')->no_mr,
                'token' => $this->session->userdata('token'),
                'user_id' => $this->session->userdata('user')->user_id,
            );

            //print_r($dataexc);die;

            try {
                
                /*process with API*/
                $config = [
                    'link' => $this->api->base_api_ws().'booking/regon_booking/process_add_new_pasien',
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

    public function formBookingPasien($no_mr, $relasi_id)
    {
        /*if id is not null then will show form edit*/
        /*breadcrumbs for create or add row*/
        $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Regon_booking/'.strtolower(get_class($this)).'/form');
        /*initialize flag for form add*/
        $data['flag'] = "create";
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();

        try {
                
            /*process with API*/
            $config = [
                'link' => $this->api->base_api_ws().'booking/regon_booking/getPasienRelasi',
                'data' => array('token' => $this->session->userdata('token'), 'cons_id' => $this->session->userdata('user_profile')->no_mr, 'relationID' => $relasi_id ),
            ];

            $response = $this->api->getDataWs( $config ); 

            $data['pasien'] = $response->result;

        } catch (Exception $e) {
            
            $response = $this->ws_auth->failed_response( $e );

            echo json_encode( $response ); exit;

        } 

        /*load form view*/
        $this->load->view('Regon_booking/form_booking_pasien', $data);
    }

    public function show_modul($modul_id) { 
        
        switch ($modul_id) {
            case 'RJ':
                $view_modul = 'Regon_booking/form_rajal';
                break;

            case 2:
                $view_modul = 'Regon_booking/form_ranap';
                break;

            case 3:
                $view_modul = 'Regon_booking/form_pm';
                break;

            case 4:
                $view_modul = 'Regon_booking/form_igd';
                break;

            case 5:
                $view_modul = 'Regon_booking/form_mcu';
                break;

            case 6:
                $view_modul = 'Regon_booking/form_odc';
                break;

            case 7:
                $view_modul = 'Regon_booking/form_paket_bedah';
                break;
            
            default:
                $view_modul = 'Regon_booking/index';
                break;
        }

        $this->load->view($view_modul);
    
    }

    public function process()
    {
        /*print_r($_POST);die;*/
        $this->load->library('form_validation');
        $val = $this->form_validation;

        $val->set_rules('tanggal_kunjungan', 'Tanggal Kunjungan', 'trim|required');
        $val->set_rules('no_mr', 'MR Pasien', 'trim|required', array('required' => 'MR Pasien tidak ditemukan'));
        $val->set_rules('jenis_instalasi', 'Instalasi', 'trim|required');
        $val->set_rules('klinik_rajal', 'Klinik', 'trim|required');
        $val->set_rules('dokter_rajal', 'Dokter', 'trim|required');
        $val->set_rules('selected_day', 'Hari', 'trim|required');
        $val->set_rules('selected_time', 'Jam Praktek', 'trim|required');
        $val->set_rules('keterangan', 'Keterangan', 'trim');
        $val->set_rules('jenis_penjamin', 'Jenis Penjamin', 'trim|required');
        $val->set_rules('jd_id', 'Jadwal ID', 'trim|required');

        if($this->input->post('jenis_penjamin')=='Jaminan Perusahaan'){
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
            $id = ($this->input->post('regon_booking_id'))?$this->regex->_genRegex($this->input->post('regon_booking_id'),'RGXINT'):0;

            $urutan = $this->input->post('last_counter') + 1;

            /*hitung waktu buka loket*/
            $date = date_create($val->set_value('tanggal_kunjungan').' '.$this->input->post('time_start') );
            date_add($date, date_interval_create_from_date_string('-2 hours'));
            $waktu_datang = date_format($date, 'Y-m-d H:i:s');

            $dataexc = array(
                'regon_booking_tanggal_perjanjian' => $this->regex->_genRegex($this->tanggal->sqlDateForm($val->set_value('tanggal_kunjungan')), 'RGXQSL'),
                'regon_booking_no_mr' => $this->regex->_genRegex($val->set_value('no_mr'), 'RGXQSL'),
                'regon_booking_instalasi' => $this->regex->_genRegex($val->set_value('jenis_instalasi'), 'RGXQSL'),
                'regon_booking_klinik' => $this->regex->_genRegex($val->set_value('klinik_rajal'), 'RGXQSL'),
                'regon_booking_kode_dokter' => $this->regex->_genRegex($val->set_value('dokter_rajal'), 'RGXQSL'),
                'regon_booking_hari' => $this->regex->_genRegex($val->set_value('selected_day'), 'RGXQSL'),
                'regon_booking_jam' => $this->regex->_genRegex($val->set_value('selected_time'), 'RGXQSL'),
                'regon_booking_waktu_datang' => $waktu_datang,
                'regon_booking_keterangan' => $this->regex->_genRegex($val->set_value('keterangan'), 'RGXQSL'),
                'regon_booking_jenis_penjamin' => $this->regex->_genRegex($val->set_value('jenis_penjamin'), 'RGXQSL'),
                'regon_booking_penjamin' => $this->regex->_genRegex($val->set_value('kode_perusahaan'), 'RGXINT'),
                'regon_booking_status' => '0',
                'regon_booking_urutan' => $this->regex->_genRegex($urutan, 'RGXINT'),
                'regon_via' => 'onsite',
                'jd_id' => $this->regex->_genRegex($val->set_value('jd_id'), 'RGXINT'),
            );

            /*detail mr*/
            $detail_pasien = $this->Reg_pasien->get_by_mr($val->set_value('no_mr'));
            $dataexc['log_detail_pasien'] = json_encode( array('nama_pasien' => $detail_pasien->nama_pasien, 'tgl_lahir' => $this->tanggal->formatDate($detail_pasien->tgl_lhr), 'alamat' => $detail_pasien->almt_ttp_pasien, 'telp' => $detail_pasien->tlp_almt_ttp, 'jk' => $detail_pasien->jen_kelamin ) );

            $log_transaksi = array(
                'klinik' => $this->master->get_custom_data('mt_bagian', array('nama_bagian','kode_bagian'), array('kode_bagian' => $val->set_value('klinik_rajal') ) , 'row'),
                'dokter' => $this->master->get_custom_data('mt_karyawan', array('nama_pegawai', 'kode_dokter'), array('kode_dokter' => $val->set_value('dokter_rajal') ) , 'row'),
                'penjamin' => $this->master->get_custom_data('mt_perusahaan', array('nama_perusahaan'), array('kode_perusahaan' => $val->set_value('kode_perusahaan') ) , 'row'),
                );

            $dataexc['log_transaksi'] = json_encode($log_transaksi);

            if($id==0){
                /*create kode booking*/
                $kode_booking = $this->create_kode_booking();
                $dataexc['regon_booking_kode'] = $this->regex->_genRegex(strtoupper($kode_booking), 'RGXQSL');
                $dataexc['created_date'] = date('Y-m-d H:i:s');
                $dataexc['created_by'] = json_encode(array('user_id' => $this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                /*save post data*/
                $newId = $this->Regon_booking->save($dataexc);
                /*save logs*/
                $this->logs->save('regon_booking', $newId, 'insert new record on '.$this->title.' module', json_encode($dataexc),'regon_booking_id');
                /*save log kuota dokter*/
                $this->logs->save_log_kuota(array('kode_dokter' => $dataexc['regon_booking_kode_dokter'], 'kode_spesialis' => $dataexc['regon_booking_klinik'], 'tanggal' => $dataexc['regon_booking_tanggal_perjanjian'] ));
                
            }else{
                $kode_booking = $this->input->post('kode_booking');
                $dataexc['updated_date'] = date('Y-m-d H:i:s');
                $dataexc['updated_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                /*update record*/
                $this->Regon_booking->update(array('regon_booking_id' => $id), $dataexc);
                $newId = $id;
                /*save logs*/
                $this->logs->save('regon_booking', $newId, 'update record on '.$this->title.' module', json_encode($dataexc),'regon_booking_id');
            }

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'redirect' => 'booking/regon_booking/qr_code?kode='.strtoupper($kode_booking).''));
            }

        }
    }

    public function create_kode_booking(){
        $string = $_POST['no_mr'].$_POST['tanggal_kunjungan'].'abcdefghijklmnpqrstuvwxyz';
        $clean_string = str_replace(array('1','i','0','o','/','-'),'',$string);
        $s = substr(str_shuffle(str_repeat($clean_string, 6)), 0, 6);
        return $s;
    }

    public function success_confirmation() { 
        /*define variable data*/
        $data = array(
            'kode' => $this->input->get('kode'),
        );
        /*load view index*/
        $this->load->view('Regon_booking/success_confirmation_view', $data);
    }

    public function qr_code() { 
        /*define booking code*/
        $code = $this->input->get('kode');
        /*get profile by kode booking*/
        $profile = $this->Regon_booking->get_by_kode_booking($code);
        /*define variable data*/
        $data = array(
            'profile' => $profile,
            'kode_booking' => $this->input->get('kode'),
            'qr_code' => $profile->regon_booking_kode.'-'.$profile->regon_booking_no_mr.''.$profile->regon_booking_klinik.''.$profile->regon_booking_kode_dokter.''.$profile->regon_booking_instalasi,
        );
        /*load view index*/
        $this->load->view('Regon_booking/qr_code', $data);
    }

    

    public function get_data_booking()
    {
        /*kode booking*/
        $kode_booking = $this->input->get('kode'); 
        /*get data from model*/
        $list = $this->Regon_booking->get_datatables($kode_booking);
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            
            $log_pasien = json_decode($row_list->log_detail_pasien);
            $log_transaksi = json_decode($row_list->log_transaksi);
            $qr_code = 'qwertywertyuiopsdfghjkcvbnm,';
            $no++;
            $row = array();
            $row[] = '<img class="center" src="'.base_url().'assets/barcode.php?s=qrh&d='.$qr_code.'"> <h5><b>'.$row_list->regon_booking_kode.'</b></h5>';
            $row[] = ''.$row_list->regon_booking_no_mr.' <br> '.$log_pasien->nama_pasien.'<br>'.$log_pasien->tgl_lahir.' ( '.$log_pasien->jk.' )';
            $row[] = $this->tanggal->formatDate($row_list->regon_booking_tanggal_perjanjian).'<br>'.$row_list->regon_booking_hari.', '.$row_list->regon_booking_jam;
            $row[] = ucwords($log_transaksi->klinik->nama_bagian).'<br>'.$log_transaksi->dokter->nama_pegawai;
            $row[] = $row_list->regon_booking_jenis_penjamin;
            $row[] = $row_list->regon_booking_keterangan;

            $data[] = $row;
        }

        $output = array(
                "draw" => $_POST['draw'],
                "recordsTotal" => $this->Regon_booking->count_all($kode_booking),
                "recordsFiltered" => $this->Regon_booking->count_filtered($kode_booking),
                "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function search_booking(){
        $kode_booking = $this->input->get('kode');
        $data = $this->Regon_booking->get_by_kode_booking($kode_booking);
        //print_r($data);die;
        if(count($data)>0){
            $pasien = json_decode($data->log_detail_pasien);
            $html = $this->Regon_booking->booking_view($data);
            echo json_encode(array('result' => $data, 'html' => $html, 'count' => count($data), 'nama_pasien' => $pasien->nama_pasien ));
        }else{
            echo json_encode(array('count' => 0));
        }
        
    }

    

}

/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
