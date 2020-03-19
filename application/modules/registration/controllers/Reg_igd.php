<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');


class Reg_igd extends MX_Controller {

    /*function constructor*/
    
    function __construct() {

        parent::__construct();
        
        /*breadcrumb default*/
        
        $this->breadcrumbs->push('Index', 'registrasi/Reg_klinik');
        
        /*session redirect login if not login*/
        
        if($this->session->userdata('logged')!=TRUE){
            
            echo 'Session Expired !'; exit;
        
        }
        
        /*load model*/
        
        $this->load->model('Reg_klinik_model', 'Reg_klinik');

        $this->load->model('Reg_pasien_model', 'Reg_pasien');

        $this->load->model('ws_bpjs/Ws_index_model', 'Ws_index');

        /*load library*/

        $this->load->library('Daftar_pasien');

        $this->load->library('Form_validation');

        $this->load->library('Print_direct');

        $this->load->library('Print_escpos');
            
        /*enable profiler*/
        
        $this->output->enable_profiler(false);
        
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->
        get_menu_by_class(get_class($this))->name : 'Title';
    
    
    }

    public function process(){

        // form validation
        $this->form_validation->set_rules('tgl_registrasi', 'Tanggal Registrasi', 'trim|required');
        //$this->form_validation->set_rules('klinik_rajal', 'Poli/Klinik', 'trim|required');
        //$this->form_validation->set_rules('dokter_rajal', 'Dokter', 'trim|required');
        $this->form_validation->set_rules('noMrHidden', 'Dokter', 'trim|required');
        $this->form_validation->set_rules('kode_kelompok_hidden', 'Kode Kelompok', 'trim');
        $this->form_validation->set_rules('kode_perusahaan_hidden', 'Kode Perusahaan', 'trim');
        $this->form_validation->set_rules('umur_saat_pelayanan_hidden', 'Umur', 'trim');
        $this->form_validation->set_rules('nama_pasien_hidden', 'Nama', 'trim');

        if($_POST['kode_perusahaan_hidden']==120){
            $this->form_validation->set_rules('noSep', 'Nomor SEP', 'trim|required');
        }
        
        $this->form_validation->set_rules('igd_jns_kejadian', 'Jenis Kejadian', 'trim|required');
        $this->form_validation->set_rules('igd_tempat_kejadian', 'Tempat Kejadian', 'trim');
        $this->form_validation->set_rules('igd_status_diterima', 'Status Diterima', 'trim|required');
        $this->form_validation->set_rules('igd_rujukan', 'Rujukan', 'trim|required');
        $this->form_validation->set_rules('igd_dokter_jaga', 'Dokter', 'trim|required');

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
            /*execution*/
            $this->db->trans_begin();

            $title = 'Pendaftaran IGD';
            $no_mr = $this->regex->_genRegex($this->form_validation->set_value('noMrHidden'),'RGXQSL');
            $kode_perusahaan = $this->regex->_genRegex($this->form_validation->set_value('kode_perusahaan_hidden'),'RGXINT');
            $kode_kelompok =  $this->regex->_genRegex($this->form_validation->set_value('kode_kelompok_hidden'),'RGXINT');
            $kode_dokter = $this->regex->_genRegex($this->form_validation->set_value('igd_dokter_jaga'),'RGXINT');
            $kode_bagian_masuk = $this->regex->_genRegex('020101','RGXQSL');
            $umur_saat_pelayanan = $this->regex->_genRegex($this->form_validation->set_value('umur_saat_pelayanan_hidden'),'RGXINT');
            $no_sep = $this->regex->_genRegex($this->form_validation->set_value('noSep'),'RGXQSL');

            $data_registrasi = $this->daftar_pasien->daftar_registrasi($title,$no_mr, $kode_perusahaan, $kode_kelompok, $kode_dokter, $kode_bagian_masuk, $umur_saat_pelayanan,$no_sep);
            $no_registrasi = $data_registrasi['no_registrasi'];
            $no_kunjungan = $data_registrasi['no_kunjungan'];
            
            /*insert gawat darurat*/
            $kode_gd = $this->master->get_max_number('gd_tc_gawat_darurat', 'kode_gd');
            $tgl_kecelakaan =$this->tanggal->sqlDateTime($this->input->post('igd_tgl_kejadian'));  
            $data_gd_tc_gawat_darurat = array(
                'kode_gd' => $kode_gd,
                'no_kunjungan' => $no_kunjungan,
                'jns_celaka' => $this->regex->_genRegex($this->form_validation->set_value('igd_jns_kejadian'),'RGXQSL'),
                'tanggal_gd' => date('Y-m-d H:i:s'),
                'tgl_kecelakaan' => ($this->input->post('igd_tgl_kejadian')=='')?date('Y-m-d H:i:s'):$tgl_kecelakaan ,
                'dibawa_oleh' => $this->regex->_genRegex($this->input->post('igd_diantar_oleh'),'RGXQSL'),
                'tgl_jam_msk' => date('Y-m-d H:i:s'),
                'kd_tind_igd' => 0,
                'dikirim_oleh' => $this->regex->_genRegex($this->input->post('igd_dikirim_oleh'),'RGXQSL'),
                'dibawa_dgn' => $this->regex->_genRegex($this->input->post('igd_dibawa_dengan'),'RGXQSL'),
                'kasus_polisi' => $this->regex->_genRegex($this->form_validation->set_value('igd_rujukan'),'RGXQSL'),
                'dokter_jaga' => $this->regex->_genRegex($this->form_validation->set_value('igd_dokter_jaga'),'RGXINT'),
                'nama_org_dekat' => $this->regex->_genRegex($this->input->post('igd_nama_keluarga'),'RGXQSL'),
                'telp_org_dekat' => $this->regex->_genRegex($this->input->post('igd_telp_keluarga'),'RGXQSL'),
                'alamat_org_dekat' => $this->regex->_genRegex($this->input->post('igd_alamat_keluarga'),'RGXQSL'),
                'status_diterima' => $this->regex->_genRegex($this->form_validation->set_value('igd_status_diterima'),'RGXQSL'),
                'kode_klas' => 19,
                'no_mr' => $this->regex->_genRegex($this->form_validation->set_value('noMrHidden'),'RGXQSL'),
                'nama_pasien_igd' => $this->regex->_genRegex($this->form_validation->set_value('nama_pasien_hidden'),'RGXQSL'),
                'no_induk' => $this->session->userdata('user')->user_id,
            );

        
             /*save gawat darurat*/
             $this->Reg_klinik->save('gd_tc_gawat_darurat', $data_gd_tc_gawat_darurat);
 
             /*save logs*/
             $this->logs->save('gd_tc_gawat_darurat', $data_gd_tc_gawat_darurat['kode_gd'], 'insert new record on Pendaftaran IGD module', json_encode($data_gd_tc_gawat_darurat),'kode_gd');

            $detail_data = $this->Reg_pasien->get_detail_resume_medis($no_registrasi);
                
            $data_tracer = [
                'no_mr' => $no_mr,
                'result' => $detail_data,
            ];

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                if($this->input->post('is_new')!='Yes'){
                    $tracer = $this->print_escpos->print_direct($data_tracer);
                    if( $tracer==1 ) {
                         $this->db->update('tc_registrasi', array('print_tracer' => 'Y'), array('no_registrasi' => $no_registrasi) );
                    }
                }
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'no_mr' => $no_mr, 'no_registrasi' => $no_registrasi, 'is_new' => $this->input->post('is_new')));
            }

        
        }

    }

}



/* End of file example.php */

/* Location: ./application/functiones/example/controllers/example.php */
