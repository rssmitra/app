<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');


class Hd_jadwal_pasien extends MX_Controller {

    /*function constructor*/
    
    function __construct() {

        parent::__construct();
        
        /*breadcrumb default*/
        
        $this->breadcrumbs->push('Index', 'registrasi/Hd_jadwal_pasien');
        
        /*session redirect login if not login*/
        
        if($this->session->userdata('logged')!=TRUE){
            
            echo 'Session Expired !'; exit;
        
        }
        
        /*load model*/
        
        $this->load->model('Hd_jadwal_pasien_model', 'Hd_jadwal_pasien');

        $this->load->model('registration/Perjanjian_rj_model', 'Perjanjian');

        /*load library*/

        $this->load->library('Form_validation');

        $this->load->library('Print_direct');        
        
        /*enable profiler*/
        
        $this->output->enable_profiler(false);
        
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->
        get_menu_by_class(get_class($this))->name : 'Title';
    
    }

    public function index() { 
        
        /*define variable data*/

        $data = array(
            
            'title' => $this->title,
            
            'breadcrumbs' => $this->breadcrumbs->show(),
        
        );
        
        $this->load->view('Hd_jadwal_pasien/index', $data);
    
    }

    public function show_modul($modul_id, $id_tc_pesanan='') { 
        
        $data = array();
        $data['id_tc_pesanan'] = $id_tc_pesanan;

        switch ($modul_id) {
            case 8:
                $view_modul = 'Hd_jadwal_pasien/form_perjanjian_hd';
                break;
            
            default:
                $view_modul = 'Hd_jadwal_pasien/index';
                break;
        }

        $this->load->view($view_modul, $data);
    
    }

    public function show_modul_perjanjian($modul_id) { 
        
        switch ($modul_id) {
            case 'HD':
                $view_modul = 'hd_jadwal_pasien/form_hd';
                break;
            default:
                $view_modul = 'hd_jadwal_pasien/index';
                break;
        }

        $this->load->view($view_modul);
    
    }

    public function process_perjanjian()
    {
        //print_r($_POST);die;
        $this->load->library('form_validation');
        $val = $this->form_validation;
        $val->set_rules('no_mr', 'MR Pasien', 'trim|required', array('required' => 'MR Pasien tidak ditemukan'));
        $val->set_rules('dokter_rajal', 'Dokter', 'trim|required');
        $val->set_rules('catatan', 'Catatan', 'trim');
        $val->set_rules('jenis_penjamin', 'Jenis Penjamin', 'trim|required');
        $val->set_rules('klinik_rajal', 'Klinik', 'trim|required');

       
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

            $id = ($this->input->post('id_tc_pesanan'))?$this->regex->_genRegex($this->input->post('id_tc_pesanan'),'RGXINT'):0;

            $date = date_create( date('Y-m-d H:i:s') );
            $kode_perjanjian = $this->master->get_kode_perjanjian( $date );

            $dataexc = array(
                'tgl_pesanan' => date('Y-m-d'),
                'no_mr' => $this->regex->_genRegex($val->set_value('no_mr'), 'RGXQSL'),
                'nama' => $this->regex->_genRegex($this->input->post('nama_pasien'), 'RGXQSL'),
                'alamat' => $this->regex->_genRegex($this->input->post('alamat'), 'RGXQSL'),
                'no_poli' => $this->regex->_genRegex($val->set_value('klinik_rajal'), 'RGXQSL'),
                'kode_dokter' => $this->regex->_genRegex($val->set_value('dokter_rajal'), 'RGXQSL'),
                'jam_pesanan' => date_format($date, 'Y-m-d H:i:s'),
                'no_induk' => $this->session->userdata('user')->user_id,
                'input_tgl' => date('Y-m-d h:i:s'),
                'kode_perjanjian' => $kode_perjanjian,
                'unique_code_counter' => $this->master->get_max_number('tc_pesanan', 'unique_code_counter'),
                'flag' =>  'HD',
                'selected_day' => implode($_POST['selected_days'], ','),
            );

            //print_r($dataexc);die;

            if($id==0){
                /*save post data*/
                $newId = $this->Perjanjian->save($dataexc);
                /*save logs*/
                $this->logs->save('tc_pesanan', $newId, 'update record on '.$this->title.' module', json_encode($dataexc),'id_tc_pesanan');
                
            }else{
                /*update record*/
                $this->Perjanjian->update(array('id_tc_pesanan' => $id), $dataexc);
                $newId = $id;
                /*save logs*/
                $this->logs->save('tc_pesanan', $newId, 'update record on '.$this->title.' module', json_encode($dataexc),'id_tc_pesanan');
            }

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'redirect' => 'registration/Reg_pasien/surat_control?id_tc_pesanan='.$newId.'', 'no_mr' => $dataexc['no_mr'] ));
            }

        }
    }


}



/* End of file example.php */

/* Location: ./application/functiones/example/controllers/example.php */
