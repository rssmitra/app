<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');


class Reg_mcu extends MX_Controller {

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
        $this->load->model('pelayanan/Pl_pelayanan_mcu_model', 'Pl_pelayanan_mcu');
        $this->load->model('ws_bpjs/Ws_index_model', 'Ws_index');

        /*load library*/

        $this->load->library('Daftar_pasien');

        $this->load->library('Form_validation');

        $this->load->library('Print_direct');

        $this->load->library('Print_escpos');
            
    
    }

    public function print_checklist_mcu(){
        
        $kode_tarif = $_GET['kode_tarif'];
        $paket_mcu = $this->Pl_pelayanan_mcu->get_detail_mcu_bagian($kode_tarif);
        $paket = array();
        foreach( $paket_mcu as $key=>$row ){
            $paket[$row->nama_bagian][] = $row;
        }
        $data['paket_mcu'] = $paket_mcu[0]->paket_mcu;
        $data['value'] = $paket;
        // echo '<pre>';print_r($data);die;
        
        $this->load->view('Reg_pasien/print_out_mcu_view', $data);

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
        
        $this->form_validation->set_rules('mcu_paket_tindakan', 'Paket Tindakan', 'trim|required');
        $this->form_validation->set_rules('mcu_kode_dokter', 'Dokter MCU', 'trim|required');
      
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

            $title = 'Pendaftaran MCU';
            $no_mr = $this->regex->_genRegex($this->form_validation->set_value('noMrHidden'),'RGXQSL');
            $kode_perusahaan = $this->regex->_genRegex($this->form_validation->set_value('kode_perusahaan_hidden'),'RGXINT');
            $kode_kelompok =  $this->regex->_genRegex($this->form_validation->set_value('kode_kelompok_hidden'),'RGXINT');
            $kode_dokter = $this->regex->_genRegex($this->form_validation->set_value('mcu_kode_dokter'),'RGXINT');
            $kode_bagian_masuk = $this->regex->_genRegex('010901','RGXQSL');
            $umur_saat_pelayanan = $this->regex->_genRegex($this->form_validation->set_value('umur_saat_pelayanan_hidden'),'RGXINT');
            $no_sep = $this->regex->_genRegex($this->form_validation->set_value('noSep'),'RGXQSL');

            $data_registrasi = $this->daftar_pasien->daftar_registrasi($title,$no_mr, $kode_perusahaan, $kode_kelompok, $kode_dokter, $kode_bagian_masuk, $umur_saat_pelayanan,$no_sep);
            $no_registrasi = $data_registrasi['no_registrasi'];
            $no_kunjungan = $data_registrasi['no_kunjungan'];
            

            /*insert poli pl_tc_poli*/
            $kode_poli = $this->master->get_max_number('pl_tc_poli', 'kode_poli');
            $kode_mcu = $this->master->get_max_number('pl_tc_poli', 'kode_gcu');
            $no_antrian = $this->master->get_no_antrian_poli('010901',$this->form_validation->set_value('mcu_kode_dokter'));
            $data_pl_tc_poli = array(
                'kode_poli' => $kode_poli,
                'kode_gcu' => $kode_mcu,
                'no_kunjungan' => $no_kunjungan,
                'kode_bagian' => $kode_bagian_masuk,
                'tgl_jam_poli' => date('Y-m-d H:i:s'),
                'kode_dokter' => $kode_dokter,
                'no_antrian' => $no_antrian,
                'nama_pasien' => $_POST['nama_pasien_hidden'],
            );

            //echo"<pre>";print_r($dataexc);echo"<pre>";print_r($datakunjungan);echo"<pre>";print_r($data_gd_tc_gawat_darurat);die;

           /*insert mcu*/
           $data_mcu = array(
                'kode_mcu' => $kode_mcu
            );

            /*save poli*/
             $this->Reg_klinik->save('pl_tc_poli', $data_pl_tc_poli);

            /*save logs*/
            $this->logs->save('pl_tc_poli', $data_pl_tc_poli['kode_poli'], 'insert new record on Pendaftaran MCU module', json_encode($data_pl_tc_poli),'kode_poli');

            /*save mcu*/
             $this->Reg_klinik->save('mcu_tc_registrasi', $data_mcu);

            /*save logs*/
            $this->logs->save('mcu_tc_registrasi', $data_mcu['kode_mcu'], 'insert new record on Pendaftaran MCU module', json_encode($data_mcu),'kode_mcu');

            /*insert tc_trans_pelayanan*/

            // ------------------- Ini Bagian Tarif -------------------
            
			$tarifUmum=new Tarif();
			$tarifUmum->set("kode_tarif",$this->regex->_genRegex($this->form_validation->set_value('mcu_paket_tindakan'),'RGXINT'));
			$tarifUmum->set("kode_klas","16");
			$tarifUmum->set("kode_kelompok",$this->regex->_genRegex($this->form_validation->set_value('kode_kelompok_hidden'),'RGXINT'));
			//$tarifUmum->set("acc_pola",$acc_pola);
			$data_tc_trans_pelayanan=$tarifUmum->hitung();

			//show($fld,"Tarif yang dicari");

			$kode_tc_trans_pelayanan = $this->master->get_max_number('tc_trans_pelayanan', 'kode_trans_pelayanan');
			if ($data_tc_trans_pelayanan["jenis_tindakan"]=="")
                $data_tc_trans_pelayanan["jenis_tindakan"]="14";
            ($kode_perusahaan=="")?"0":$this->regex->_genRegex($this->form_validation->set_value('kode_perusahaan_hidden'),'RGXINT');
            
            $data_tc_trans_pelayanan['kode_trans_pelayanan'] = $kode_tc_trans_pelayanan;
            $data_tc_trans_pelayanan['no_kunjungan'] = $no_kunjungan;
            $data_tc_trans_pelayanan['no_registrasi'] = $no_registrasi;
            $data_tc_trans_pelayanan['no_mr'] = $no_mr;
            $data_tc_trans_pelayanan['kode_perusahaan'] = $kode_perusahaan;
            $data_tc_trans_pelayanan['tgl_transaksi'] = date('Y-m-d H:i:s');
            $data_tc_trans_pelayanan['kode_dokter1'] = $kode_dokter;
            $data_tc_trans_pelayanan['kode_bagian'] =$kode_bagian_masuk;
            $data_tc_trans_pelayanan['kode_bagian_asal'] = $kode_bagian_masuk;
            $data_tc_trans_pelayanan['status_selesai'] = 2;
            $data_tc_trans_pelayanan['kode_mcu'] = $kode_mcu;
            $data_tc_trans_pelayanan['flag_mcu'] = 1;
            $data_tc_trans_pelayanan['nama_pasien_layan'] = $_POST['nama_pasien_hidden'];
           
            //echo"<pre>";print_r($data_tc_trans_pelayanan);die;
           
             /*save tc_trans_pelayanan*/
            $this->Reg_klinik->save('tc_trans_pelayanan', $data_tc_trans_pelayanan);

             /*save logs*/
             $this->logs->save('tc_trans_pelayanan', $data_tc_trans_pelayanan['kode_trans_pelayanan'], 'insert new record on Pendaftaran MCU module', json_encode($data_tc_trans_pelayanan),'kode_trans_pelayanan');
             
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

                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'no_mr' => $_POST['noMrHidden'], 'no_registrasi' => $no_registrasi, 'is_new' => $this->input->post('is_new'), 'kode_tarif_mcu' => $_POST['mcu_paket_tindakan'], 'nama' => $data_tc_trans_pelayanan['nama_pasien_layan'], 'no_reg' => $data_tc_trans_pelayanan['no_registrasi']));
            }

        
        }

    }

}



/* End of file example.php */

/* Location: ./application/functiones/example/controllers/example.php */
