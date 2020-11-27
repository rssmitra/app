<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');


class Reg_pm extends MX_Controller {

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
    
    }

    public function rujuk_pm($no_reg='',$bag_asal='',$klas='',$type_asal='')
    {
        /*get value by no_kunj*/
        $data_reg = $this->Reg_pasien->get_detail_resume_medis($no_reg);
        $data['value'] = $data_reg['registrasi'];
        $data['bagian_asal'] = $bag_asal;
        $data['no_reg'] = $no_reg;
        $data['type'] = $type_asal;
        $data['klas'] = $klas;
        /*load form view*/
        $this->load->view('Reg_klinik/form_pm', $data);
    }

    public function process(){

        // print_r($_POST);die;
        // form validation
        $this->form_validation->set_rules('noMrHidden', 'Dokter', 'trim|required', array('required' => 'No MR Pasien tidak ditemukan') );
        $this->form_validation->set_rules('kode_perusahaan_hidden', 'Kode Perusahaan', 'trim');
        $this->form_validation->set_rules('kode_kelompok_hidden', 'Kode Kelompok', 'trim');
        $this->form_validation->set_rules('umur_saat_pelayanan_hidden', 'Umur', 'trim');
        $this->form_validation->set_rules('nama_pasien_hidden', 'Nama', 'trim');
        //$this->form_validation->set_rules('noSep', 'Nomor SEP', 'trim|required');

        if( !$this->input->post('no_registrasi_rujuk') && empty($this->input->post('no_registrasi_rujuk')) ){
            $this->form_validation->set_rules('tgl_registrasi', 'Tanggal Registrasi', 'trim|required');
            if($this->input->post['kode_perusahaan_hidden']==120){
                $this->form_validation->set_rules('noSep', 'Nomor SEP', 'trim|required');
            }
        }

        
        if(($_POST['asal_pasien_pm']=='') && ($_POST['is_pasien_luar']=='')){
            $this->form_validation->set_rules('asal_pasien_pm', 'Asal Pasien', 'trim|required');
        }

        $this->form_validation->set_rules('pm_tujuan', 'Penunjang Medis', 'trim|required');
        

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
            
            $title = 'Pendaftaran Penunjang Medis';
            $no_mr = $this->regex->_genRegex($this->form_validation->set_value('noMrHidden'),'RGXQSL');
            $kode_perusahaan = $this->regex->_genRegex($this->form_validation->set_value('kode_perusahaan_hidden'),'RGXINT');
            $kode_kelompok =  $this->regex->_genRegex($this->form_validation->set_value('kode_kelompok_hidden'),'RGXINT');
            $kode_dokter = $this->regex->_genRegex(0,'RGXINT');
            $kode_bagian_masuk = $this->regex->_genRegex($this->form_validation->set_value('pm_tujuan'),'RGXQSL');
            $umur_saat_pelayanan = $this->regex->_genRegex($this->form_validation->set_value('umur_saat_pelayanan_hidden'),'RGXINT');
            $no_sep = $this->regex->_genRegex($_POST['noSep'],'RGXQSL');


            if( !$this->input->post('no_registrasi_rujuk') && empty($this->input->post('no_registrasi_rujuk')) ){
                /*save tc_registrasi*/
                $data_registrasi = $this->daftar_pasien->daftar_registrasi($title,$no_mr, $kode_perusahaan, $kode_kelompok, $kode_dokter, $kode_bagian_masuk, $umur_saat_pelayanan,$no_sep);
                $no_registrasi = $data_registrasi['no_registrasi'];
                $no_kunjungan = $data_registrasi['no_kunjungan'];
            }else{
                $no_registrasi = $this->input->post('no_registrasi_rujuk');
                $kode_bagian_asal = $_POST['asal_pasien_pm'];
                $kode_bagian_tujuan = $this->regex->_genRegex($this->form_validation->set_value('pm_tujuan'),'RGXQSL');
                $no_kunjungan = $this->daftar_pasien->daftar_kunjungan($title,$no_registrasi,$no_mr,$kode_dokter,$kode_bagian_tujuan,$kode_bagian_asal);
            }
              
            /*insert penunjang medis*/
            $kode_penunjang = $this->master->get_max_number('pm_tc_penunjang', 'kode_penunjang');
            $no_antrian = $this->master->get_no_antrian_pm($this->regex->_genRegex($this->form_validation->set_value('pm_tujuan'),'RGXQSL'));
            $klas = ($this->input->post('klas_rujuk')!=0)?$this->input->post('klas_rujuk'):16;
            $data_pm_tc_penunjang = array(
                'kode_penunjang' => $kode_penunjang,
                'tgl_daftar' => date('Y-m-d H:i:s'),
                'kode_bagian' => $this->regex->_genRegex($this->form_validation->set_value('pm_tujuan'),'RGXQSL'),
                'no_kunjungan' => $no_kunjungan,
                'no_antrian' => $no_antrian,
                'kode_klas' => $klas,
                'petugas_input' => $this->session->userdata('user')->user_id,
            );

            /*save penunjang medis*/
            $this->Reg_klinik->save('pm_tc_penunjang', $data_pm_tc_penunjang);

            /*save logs*/
            $this->logs->save('pm_tc_penunjang', $data_pm_tc_penunjang['kode_penunjang'], 'insert new record on Pendaftaran Penunjang Medis module', json_encode($data_pm_tc_penunjang),'kode_penunjang');

            
            
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();

                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'no_mr' => $_POST['noMrHidden'], 'no_registrasi' => $no_registrasi, 'is_new' => $this->input->post('is_new'), 'type_pelayanan' => 'Penunjang Medis', 'no_sep' => $no_sep));

                if(!$this->input->post('no_registrasi_rujuk') && $this->input->post('pm_tujuan') == '050301'){
                    $detail_data = $this->Reg_pasien->get_detail_resume_medis($no_registrasi);
                    
                    $data_tracer = [
                        'no_mr' => $no_mr,
                        'result' => $detail_data,
                    ];
        
                    if($this->input->post('is_new')!='Yes'){
                        $tracer = $this->print_escpos->print_direct($data_tracer);
                        if( $tracer == 1 ) {
                            $this->db->update('tc_registrasi', array('print_tracer' => 'Y'), array('no_registrasi' => $no_registrasi) );
                        }
                        
                    } 
                }
                
            }
            
        }

    }


}



/* End of file example.php */

/* Location: ./application/functiones/example/controllers/example.php */
