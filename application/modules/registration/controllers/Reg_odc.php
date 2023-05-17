<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');


class Reg_odc extends MX_Controller {

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
            
    
    }

    public function process(){

        // form validation
        $this->form_validation->set_rules('tgl_registrasi', 'Tanggal Registrasi', 'trim|required');
        //$this->form_validation->set_rules('klinik_rajal', 'Poli/Klinik', 'trim|required');
        //$this->form_validation->set_rules('dokter_rajal', 'Dokter', 'trim|required');
        $this->form_validation->set_rules('noMrHidden', 'Dokter', 'trim|required');
        $this->form_validation->set_rules('kode_perusahaan_hidden', 'Kode Perusahaan', 'trim');
        $this->form_validation->set_rules('kode_kelompok_hidden', 'Kode Kelompok', 'trim');
        $this->form_validation->set_rules('umur_saat_pelayanan_hidden', 'Umur', 'trim');
        $this->form_validation->set_rules('nama_pasien_hidden', 'Nama', 'trim');

        if($_POST['kode_perusahaan_hidden']==120){
            $this->form_validation->set_rules('noSep', 'Nomor SEP', 'trim|required');
        }

        $this->form_validation->set_rules('odc_bagian', 'Klinik', 'trim|required');

        if($_POST['is_paket']==1){
            $this->form_validation->set_rules('odc_paket_tindakan', 'Rencana Tindakan', 'trim|required');
        }
        
        $this->form_validation->set_rules('odc_kode_dokter', 'Dokter', 'trim|required');
      
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

            $title = 'Pendaftaran ODC';
            $no_mr = $this->regex->_genRegex($this->form_validation->set_value('noMrHidden'),'RGXQSL');
            $kode_perusahaan = $this->regex->_genRegex($this->form_validation->set_value('kode_perusahaan_hidden'),'RGXINT');
            $kode_kelompok =  $this->regex->_genRegex($this->form_validation->set_value('kode_kelompok_hidden'),'RGXINT');
            $kode_dokter = $this->regex->_genRegex($this->form_validation->set_value('odc_kode_dokter'),'RGXINT');
            $kode_bagian_masuk = $this->regex->_genRegex($this->form_validation->set_value('odc_bagian'),'RGXQSL');
            $umur_saat_pelayanan = $this->regex->_genRegex($this->form_validation->set_value('umur_saat_pelayanan_hidden'),'RGXINT');
            $no_sep = $this->regex->_genRegex($this->form_validation->set_value('noSep'),'RGXQSL');

            $data_registrasi = $this->daftar_pasien->daftar_registrasi($title,$no_mr, $kode_perusahaan, $kode_kelompok, $kode_dokter, $kode_bagian_masuk, $umur_saat_pelayanan,$no_sep);
            $no_registrasi = $data_registrasi['no_registrasi'];
            $no_kunjungan = $data_registrasi['no_kunjungan'];
            
            /*insert poli pl_tc_poli*/
            $kode_poli = $this->master->get_max_number('pl_tc_poli', 'kode_poli');
            $tipe_antrian = ($kode_perusahaan != 120) ? 'umum' : '';
            $no_antrian = $this->master->get_no_antrian_poli($this->regex->_genRegex($this->form_validation->set_value('odc_bagian'),'RGXQSL'),$this->form_validation->set_value('odc_kode_dokter'), $tipe_antrian);
            $data_pl_tc_poli = array(
                'kode_poli' => $kode_poli,
                'no_kunjungan' => $no_kunjungan,
                'kode_bagian' => $kode_bagian_masuk,
                'tgl_jam_poli' => date('Y-m-d H:i:s'),
                'kode_dokter' => $kode_dokter,
                'no_antrian' => $no_antrian,
                'flag_antrian' => $tipe_antrian,
                'nama_pasien' => $_POST['nama_pasien_hidden'],
                'flag_odc' => 1,
            );

            //echo"<pre>";print_r($dataexc);echo"<pre>";print_r($datakunjungan);echo"<pre>";print_r($data_gd_tc_gawat_darurat);die;

           /*insert odc*/
           $data_odc = array(
                'kode_poli' => $data_pl_tc_poli['kode_poli'],
                'kode_tarif' => $this->regex->_genRegex($this->form_validation->set_value('odc_paket_tindakan'),'RGXQSL'),
                'kode_dokter' =>  $kode_dokter,
                'no_registrasi' => $no_registrasi,
                'no_kunjungan' => $no_kunjungan,
                'no_mr' => $no_mr,
                'flag_paket' =>  $this->regex->_genRegex($this->input->post('is_paket'),'RGXINT'),
                'kode_bagian' =>  $kode_bagian_masuk,
            );

             //echo"<pre>";print_r($dataexc);echo"<pre>";print_r($datakunjungan);echo"<pre>";print_r($data_pl_tc_poli);echo"<pre>";print_r($data_odc);die;

            /*save poli*/
            $this->Reg_klinik->save('pl_tc_poli', $data_pl_tc_poli);

            /*save logs*/
            $this->logs->save('pl_tc_poli', $data_pl_tc_poli['kode_poli'], 'insert new record on Pendaftaran ODC module', json_encode($data_pl_tc_poli),'kode_poli');
            
            /*save odc*/
            $newId_ODC = $this->Reg_klinik->save('odc_tc_registrasi', $data_odc);

            /*save logs*/
            $this->logs->save('odc_tc_registrasi', $newId_ODC, 'insert new record on Pendaftaran ODC module', json_encode($data_odc),'id_odc_tc_registrasi');

             if ($this->db->trans_status() === FALSE)
             {
                 $this->db->trans_rollback();
                 echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
             }
             else
             {
                 $this->db->trans_commit();
                 echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'no_mr' => $_POST['noMrHidden']));
             }

        }

    }

}



/* End of file example.php */

/* Location: ./application/functiones/example/controllers/example.php */
