<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');


class Reg_ranap extends MX_Controller {

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

        //print_r($_POST);die;
        // form validation

        $this->form_validation->set_rules('noMrHidden', 'Dokter', 'trim|required');
        $this->form_validation->set_rules('kode_perusahaan_hidden', 'Kode Perusahaan', 'trim');
        $this->form_validation->set_rules('kode_kelompok_hidden', 'Kode Kelompok', 'trim');
        $this->form_validation->set_rules('umur_saat_pelayanan_hidden', 'Umur', 'trim');
        $this->form_validation->set_rules('nama_pasien_hidden', 'Nama', 'trim');

        if($_POST['kode_perusahaan_hidden']==120){
            $this->form_validation->set_rules('noSep', 'Nomor SEP', 'trim|required');
        }

        $this->form_validation->set_rules('ri_diagnosa_masuk', 'Diagnosa Masuk', 'trim');
        $this->form_validation->set_rules('ri_ruangan', 'Nama Ruangan', 'trim|required');
        $this->form_validation->set_rules('ri_klas_ruangan', 'Kelas', 'trim|required');
        $this->form_validation->set_rules('ri_deposit', 'Deposit', 'trim');
        $this->form_validation->set_rules('ri_harga_ruangan_hidden', 'Harga Kamar', 'trim');
        $this->form_validation->set_rules('ri_harga_ruangan_bpjs_hidden', 'Harga Kamar BPJS', 'trim');
        $this->form_validation->set_rules('ri_no_ruangan', 'Pilih bed', 'trim|required');
        $this->form_validation->set_rules('ri_no_bed_hidden', 'Pilih bed', 'trim');
        $this->form_validation->set_rules('ri_dokter_ruangan', 'Dokter yang merawat', 'trim|required');
        $this->form_validation->set_rules('ri_dokter_pengirim', 'Dokter pengirim', 'trim');
        $this->form_validation->set_rules('ri_nama_kel', 'NNama', 'trim');
        $this->form_validation->set_rules('ri_alamat_kel', 'Alamat', 'trim');
        $this->form_validation->set_rules('ri_telp_kel', 'Phone', 'trim');
        $this->form_validation->set_rules('ri_hubungan_kel', 'Hubungan', 'trim');

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

            $title = 'Pendaftaran Rawat Inap';
            $no_mr = $this->regex->_genRegex($this->form_validation->set_value('noMrHidden'),'RGXQSL');
            $kode_perusahaan = $this->regex->_genRegex($this->form_validation->set_value('kode_perusahaan_hidden'),'RGXINT');
            $kode_kelompok =  $this->regex->_genRegex($this->form_validation->set_value('kode_kelompok_hidden'),'RGXINT');
            $kode_dokter = $this->regex->_genRegex($this->form_validation->set_value('ri_dokter_ruangan'),'RGXINT');
            $kode_bagian_masuk = $this->regex->_genRegex($this->form_validation->set_value('ri_ruangan'),'RGXQSL');
            $umur_saat_pelayanan = $this->regex->_genRegex($this->form_validation->set_value('umur_saat_pelayanan_hidden'),'RGXINT');
            $no_sep = $this->regex->_genRegex($this->form_validation->set_value('noSep'),'RGXQSL');

            if( !$this->input->post('no_registrasi_hidden') ){
                /*save tc_registrasi*/
                $data_registrasi = $this->daftar_pasien->daftar_registrasi($title,$no_mr, $kode_perusahaan, $kode_kelompok, $kode_dokter, $kode_bagian_masuk, $umur_saat_pelayanan,$no_sep);
                $no_registrasi = $data_registrasi['no_registrasi'];
                $no_kunjungan = $data_registrasi['no_kunjungan'];
            }else{
                $no_registrasi = $this->input->post('no_registrasi_hidden');
                $kode_bagian_asal = $this->input->post('kode_bagian_asal_hidden');
                $kode_bagian_tujuan = $this->regex->_genRegex($this->form_validation->set_value('ri_ruangan'),'RGXQSL');
                $no_kunjungan = $this->daftar_pasien->daftar_kunjungan($title,$no_registrasi,$no_mr,$kode_dokter,$kode_bagian_tujuan,$kode_bagian_asal);
                $data_ri_tc_ranap['rujukan_dari'] = $this->input->post('kode_bagian_asal_hidden');
            }
            
            /*insert ri_tc_rawatinap*/
            if ($_POST['ri_dokter_pengirim']){
                $nama_dokter_pengirim = $this->db->get_where('mt_karyawan', array('kode_dokter' => $this->regex->_genRegex($this->form_validation->set_value('ri_dokter_pengirim'),'RGXINT')))->row();
                $dr_pengirim = $nama_dokter_pengirim->nama_pegawai;
            }else{
                $dr_pengirim = '';
            }
            
            $data_ri_tc_ranap = array(
                'no_kunjungan' => $no_kunjungan,
                'kode_ruangan' => $this->regex->_genRegex($this->form_validation->set_value('ri_no_ruangan'),'RGXINT'),
                'bag_pas' => $this->regex->_genRegex($this->form_validation->set_value('ri_ruangan'),'RGXQSL'),
                'kelas_pas' => $this->regex->_genRegex($this->form_validation->set_value('ri_klas_ruangan'),'RGXINT'),
                'dr_merawat' => $this->regex->_genRegex($this->form_validation->set_value('ri_dokter_ruangan'),'RGXINT'),
                'dr_pengirim' => $dr_pengirim,
                'nama_kel' => $this->regex->_genRegex($this->form_validation->set_value('ri_nama_kel'),'RGXQSL'),
                'alamat_kel' => $this->regex->_genRegex($this->form_validation->set_value('ri_alamat_kel'),'RGXQSL'),
                'telepon_kel' => $this->regex->_genRegex($this->form_validation->set_value('ri_telp_kel'),'RGXQSL'),
                'hubungan_kel' => $this->regex->_genRegex($this->form_validation->set_value('ri_hubungan_kel'),'RGXQSL'),
                'nilai_deposit' => ($this->form_validation->set_value('ri_deposit')!='')?$this->form_validation->set_value('ri_deposit'):0,
                'tgl_masuk' => date('Y-m-d H:i:s'),
                'input_tgl' => date('Y-m-d H:i:s'),
                'user_dtg' => '1',
                'pasien_titipan' => $this->input->post('ri_status_pasien'),
                'jatah_klas' => 0
            );

            if($data_ri_tc_ranap['pasien_titipan'] == 1 ){
                $data_ri_tc_ranap["kelas_titipan"] =  $this->input->post('ri_klas_titipan_ruangan');
            }

            //print_r($data_ri_tc_ranap);die;

            /*save ri_tc_ranap*/
            $kode_ri = $this->Reg_klinik->save('ri_tc_rawatinap', $data_ri_tc_ranap);

            /*update ruangan*/
            $this->Reg_klinik->update('mt_ruangan', array('status' => 'ISI'), array('kode_ruangan' =>  $this->regex->_genRegex($this->form_validation->set_value('ri_no_ruangan'),'RGXINT')));

            /*insert ri_tc_riwayat_kelas*/
            $data_ri_tc_riwayat_kelas = array(
                'kode_ri' => $kode_ri,
                'kode_kunjungan' => $no_kunjungan,
                'no_registrasi' =>  $no_registrasi,
                'no_mr' => $no_mr,
                'kode_kelompok' => $kode_kelompok,
                'kode_perusahaan' => $kode_perusahaan,
                'kode_dokter' => $kode_dokter,
                'kode_ruangan' => $data_ri_tc_ranap['kode_ruangan'],
                'bagian_tujuan' =>  $this->regex->_genRegex($this->form_validation->set_value('ri_ruangan'),'RGXQSL'),
                'kelas_tujuan' => $data_ri_tc_ranap['kelas_pas'],
                'no_bed_tujuan' => $this->regex->_genRegex($this->form_validation->set_value('ri_no_bed_hidden'),'RGXINT'),
                'tgl_masuk' => date('Y-m-d H:i:s'),
                'ket_masuk' => "0",
            ); 
            
            /*insert harga kamar tc_trans_pelayanan*/
            if( $this->regex->_genRegex($this->form_validation->set_value('kode_perusahaan_hidden'),'RGXINT') == 120){

                $tarif = $this->regex->_genRegex($this->form_validation->set_value('ri_harga_ruangan_bpjs_hidden'),'RGXQSL');

                if(!isset($tarif)){

                    $tarif = $this->regex->_genRegex($this->form_validation->set_value('ri_harga_ruangan_hidden'),'RGXQSL');

                }

            } else {

                $tarif = $this->regex->_genRegex($this->form_validation->set_value('ri_harga_ruangan_hidden'),'RGXQSL');

            }

            $nama_ruangan = $this->db->get_where('mt_bagian', array('kode_bagian' => $this->regex->_genRegex($this->form_validation->set_value('ri_ruangan'),'RGXQSL')))->row();
            $kode_tc_trans_pelayanan = $this->master->get_max_number('tc_trans_pelayanan', 'kode_trans_pelayanan');
            $data_tc_trans_pelayanan = array(
                'kode_trans_pelayanan' => $kode_tc_trans_pelayanan,
                'no_kunjungan' => $no_kunjungan,
                'no_registrasi' => $no_registrasi,
                'no_mr' => $no_mr,
                'nama_pasien_layan' => $this->input->post('nama_pasien_hidden'),
                'kode_perusahaan' => $kode_perusahaan,
                'kode_kelompok' => $kode_kelompok,
                'tgl_transaksi' => date('Y-m-d H:i:s'),
                'jenis_tindakan' => 1,
                'nama_tindakan' => 'Ruangan '. $nama_ruangan->nama_bagian,
                'bill_rs' =>  ($tarif!='')?$tarif:0,
                'jumlah' =>  1,
                'kode_bagian' => $this->regex->_genRegex($this->form_validation->set_value('ri_ruangan'),'RGXQSL'),
                'kode_klas' =>  $data_ri_tc_ranap['kelas_pas'],
                'no_kamar' => $data_ri_tc_ranap['kode_ruangan'],
                'no_bed' => $this->regex->_genRegex($this->form_validation->set_value('ri_no_bed_hidden'),'RGXINT'),
            ); 

            /*save ri_tc_riwayat_kelas*/
            $this->Reg_klinik->save('ri_tc_riwayat_kelas', $data_ri_tc_riwayat_kelas);

           /*save tc_trans_pelayanan*/
             $this->Reg_klinik->save('tc_trans_pelayanan', $data_tc_trans_pelayanan);
            
            /*insert riwayat pasien th_riwayat_pasien*/
            $nama_dokter = $this->db->get_where('mt_karyawan', array('kode_dokter' => $this->regex->_genRegex($this->form_validation->set_value('ri_dokter_ruangan'),'RGXINT')))->row();

            $data_th_riwayat_pasien = array(
                'diagnosa_awal' =>$this->regex->_genRegex($this->form_validation->set_value('ri_diagnosa_masuk'),'RGXQSL'),
                'no_registrasi' =>$no_registrasi,
                'no_kunjungan' => $no_kunjungan,
                'no_mr' => $no_mr,
                'nama_pasien' => $this->regex->_genRegex($this->form_validation->set_value('nama_pasien_hidden'),'RGXQSL'),
                'tgl_periksa' => date('Y-m-d H:i:s'),
                'dokter_pemeriksa' => $nama_dokter->nama_pegawai,
                'kode_bagian' =>  $this->regex->_genRegex($this->form_validation->set_value('ri_ruangan'),'RGXQSL'),
            ); 

            $qry_riwayat_pasien = $this->db->get_where('th_riwayat_pasien', array('no_registrasi' => $no_registrasi, 'no_kunjungan' => $no_kunjungan));
            
            $cek_riwayat_pasien =  $qry_riwayat_pasien->num_rows();

            if($cek_riwayat_pasien==0){
                /*save th_riwayat_pasien*/
                 $this->Reg_klinik->save('th_riwayat_pasien', $data_th_riwayat_pasien);
            }

            /*jika dari pasien rujukan*/
            if( $this->input->post('kode_rujukan_hidden') ){
                $this->db->update('rg_tc_rujukan', array('status' => 1, 'rujukan_tujuan' => $this->regex->_genRegex($this->form_validation->set_value('ri_ruangan'),'RGXQSL')), array('kode_rujukan' => $this->input->post('kode_rujukan_hidden') ) );
            }

            
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
