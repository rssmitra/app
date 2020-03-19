<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');


class Reg_bedah extends MX_Controller {

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

        $this->form_validation->set_rules('pb_dokter_pengirim', 'Dokter pengirim', 'trim');
        $this->form_validation->set_rules('pb_diagnosa_masuk', 'Diagnosa Masuk', 'trim|required');
        $this->form_validation->set_rules('pb_paket_tindakan', 'Paket Bedah', 'trim|required');
        $this->form_validation->set_rules('pb_ruangan', 'Nama Ruangan', 'trim|required');
        $this->form_validation->set_rules('pb_klas_ruangan', 'Kelas', 'trim|required');
        $this->form_validation->set_rules('pb_no_ruangan', 'Pilih bed', 'trim|required');
        $this->form_validation->set_rules('pb_no_bed_hidden', 'Pilih bed', 'trim');
        $this->form_validation->set_rules('pb_dokter_ruangan', 'Dokter yang merawat', 'trim|required');
        $this->form_validation->set_rules('pb_deposit', 'Deposit', 'trim');
        $this->form_validation->set_rules('pb_harga_ruangan_hidden', 'Harga Kamar', 'trim');
        $this->form_validation->set_rules('pb_harga_ruangan_bpjs_hidden', 'Harga Kamar BPJS', 'trim');
   
        $this->form_validation->set_rules('pb_nama_kel', 'Nama', 'trim');
        $this->form_validation->set_rules('pb_alamat_kel', 'Alamat', 'trim');
        $this->form_validation->set_rules('pb_telp_kel', 'Phone', 'trim');
        $this->form_validation->set_rules('pb_hubungan_kel', 'Hubungan', 'trim');

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

            $title = 'Pendaftaran Paket Bedah';
            $no_mr = $this->regex->_genRegex($this->form_validation->set_value('noMrHidden'),'RGXQSL');
            $kode_perusahaan = $this->regex->_genRegex($this->form_validation->set_value('kode_perusahaan_hidden'),'RGXINT');
            $kode_kelompok =  $this->regex->_genRegex($this->form_validation->set_value('kode_kelompok_hidden'),'RGXINT');
            $kode_dokter = $this->regex->_genRegex($this->form_validation->set_value('pb_dokter_ruangan'),'RGXINT');
            $kode_bagian_masuk = $this->regex->_genRegex($this->form_validation->set_value('pb_ruangan'),'RGXQSL');
            $umur_saat_pelayanan = $this->regex->_genRegex($this->form_validation->set_value('umur_saat_pelayanan_hidden'),'RGXINT');
            $no_sep = $this->regex->_genRegex($this->form_validation->set_value('noSep'),'RGXQSL');

            $data_registrasi = $this->daftar_pasien->daftar_registrasi($title,$no_mr, $kode_perusahaan, $kode_kelompok, $kode_dokter, $kode_bagian_masuk, $umur_saat_pelayanan,$no_sep);
            $no_registrasi = $data_registrasi['no_registrasi'];
            $no_kunjungan = $data_registrasi['no_kunjungan'];
        
            /*insert ri_tc_rawatinap*/
            $nama_dokter_pengirim = $this->db->get_where('mt_karyawan', array('kode_dokter' => $this->regex->_genRegex($this->form_validation->set_value('ri_dokter_pengirim'),'RGXINT')))->row();
            $data_ri_tc_ranap = array(
                'no_kunjungan' => $no_kunjungan,
                'kode_ruangan' => $this->regex->_genRegex($this->form_validation->set_value('pb_no_ruangan'),'RGXINT'),
                'bag_pas' => $kode_bagian_masuk,
                'kelas_pas' => $this->regex->_genRegex($this->form_validation->set_value('pb_klas_ruangan'),'RGXINT'),
                'dr_merawat' => $this->regex->_genRegex($this->form_validation->set_value('pb_dokter_ruangan'),'RGXINT'),
                'dr_pengirim' => $nama_dokter_pengirim['nama_pegawai'],
                'nama_kel' => $this->regex->_genRegex($this->form_validation->set_value('pb_nama_kel'),'RGXQSL'),
                'alamat_kel' => $this->regex->_genRegex($this->form_validation->set_value('pb_alamat_kel'),'RGXQSL'),
                'telepon_kel' => $this->regex->_genRegex($this->form_validation->set_value('pb_telp_kel'),'RGXQSL'),
                'hubungan_kel' => $this->regex->_genRegex($this->form_validation->set_value('pb_hubungan_kel'),'RGXQSL'),
                'nilai_deposit' => $this->regex->_genRegex($this->form_validation->set_value('pb_deposit'),'RGXQSL'),
                'tgl_masuk' => date('Y-m-d H:i:s'),
                'input_tgl' => date('Y-m-d H:i:s'),
                'user_dtg' => 1,
                'pasien_titipan' => $this->input->post('ri_status_pasien'),
                'jatah_klas' => $this->regex->_genRegex($this->form_validation->set_value('pb_klas_ruangan'),'RGXINT'),
                'flag_paket' => 1,
                'kode_tarif_paket' => $this->regex->_genRegex($this->form_validation->set_value('pb_paket_tindakan'),'RGXINT'),
            );

            
            $this->db->trans_complete();

            /*save ri_tc_ranap*/
            $kode_ri = $this->Reg_klinik->save('ri_tc_rawatinap', $data_ri_tc_ranap);

            /*update ruangan*/
            $this->Reg_klinik->update('mt_ruangan', array('status' => 'ISI'), array('kode_ruangan' =>  $this->regex->_genRegex($this->form_validation->set_value('pb_no_ruangan'),'RGXINT')));

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
                'bagian_tujuan' => $kode_bagian_masuk,
                'kelas_tujuan' => $data_ri_tc_ranap['kelas_pas'],
                'no_bed_tujuan' => $this->regex->_genRegex($this->form_validation->set_value('pb_no_bed_hidden'),'RGXINT'),
                'tgl_masuk' => date('Y-m-d H:i:s'),
                'ket_masuk' => "0",
            ); 
            
            /*insert pesan bedah */
            $kode_master_tarif_detail = $this->db->get_where('mt_master_tarif_detail', array('kode_tarif' => $this->regex->_genRegex($this->form_validation->set_value('pb_paket_tindakan'),'RGXQSL')))->row();
            $data_pesan_bedah = array(
                'jenis_layanan' => 0,
                'kode_master_tarif_detail' => $kode_master_tarif_detail->kode_master_tarif_detail,
                'dokter1' => $kode_dokter,
                'kode_ri' => $data_ri_tc_riwayat_kelas['kode_ri'],
                'no_kunjungan' => $no_kunjungan,
                'no_registrasi' => $no_registrasi,
                'no_mr' =>  $no_mr,
                'tgl_pesan' => date('Y-m-d H:i:s'),
                'kode_klas' => $this->regex->_genRegex($this->form_validation->set_value('pb_klas_ruangan'),'RGXINT'),
                'kode_bagian' => $this->regex->_genRegex('030901','RGXQSL'),
            );
            //print_r($data_pesan_bedah);die;

            /*save ri_tc_riwayat_kelas*/
            $this->Reg_klinik->save('ri_tc_riwayat_kelas', $data_ri_tc_riwayat_kelas);

            /*save pesan_bedah*/
            $this->Reg_klinik->save('ri_pesan_bedah', $data_pesan_bedah);

            /*insert penunjang medis*/
            /*cek if exist*/
            $cek = $this->db->get_where('mt_tarif_paketbedah_detail', array('kode_tarif' => $this->regex->_genRegex($this->form_validation->set_value('pb_paket_tindakan'),'RGXQSL'),'kode_bagian' => '050101'))->num_rows();
            //echo $cek;die;
            if($cek!=0){
                /*insert kunjungan*/
                //$this->penunjang_medis($dataexc);

                $no_kunjungan_pm = $this->master->get_max_number('tc_kunjungan', 'no_kunjungan');
                $datakunjungan_pm = array(
                    'no_kunjungan' => $no_kunjungan_pm,
                    'no_registrasi' => $no_registrasi,
                    'no_mr' => $no_mr,
                    'kode_dokter' => $kode_dokter,
                    'kode_bagian_tujuan' =>  $this->regex->_genRegex('050101','RGXQSL'),
                    'kode_bagian_asal' => $kode_bagian_masuk,
                    'tgl_masuk' => date('Y-m-d H:i:s'),
                    'status_masuk' => 0,
                    'status_cito' => 0,
                    'tgl_keluar' => date('Y-m-d H:i:s'),
                    'status_keluar' => 1,
                );
                //print_r($datakunjungan_pm);die;

                /*save kunjungan pm*/
                $this->Reg_klinik->save_pm('tc_kunjungan', $datakunjungan_pm);

                /*insert penunjang medis*/
                $kode_penunjang = $this->master->get_max_number('pm_tc_penunjang', 'kode_penunjang');
                $data_pm = array(
                    'kode_penunjang' => $kode_penunjang,
                    'tgl_daftar' => date('Y-m-d H:i:s'),
                    'kode_bagian' => $datakunjungan_pm['kode_bagian_tujuan'],
                    'no_kunjungan' => $datakunjungan_pm['no_kunjungan'],
                    'asal_daftar' => $kode_bagian_masuk,
                    'no_antrian' =>  $this->master->get_no_antrian_pm($datakunjungan_pm['kode_bagian_tujuan']),
                    'kode_klas' => $this->regex->_genRegex($this->form_validation->set_value('pb_klas_ruangan'),'RGXINT'),
                );

               // print_r($data_pm);

                /*save penunjang medis*/
                $this->Reg_klinik->save('pm_tc_penunjang', $data_pm);

                $sql_paket = $this->db->get_where('mt_tarif_paketbedah_detail', array('kode_tarif' => $this->regex->_genRegex($this->form_validation->set_value('pb_paket_tindakan'),'RGXQSL'),'kode_bagian' => '050101','status' => 1));
                $rows_paket = $sql_paket->result();
                //print_r($rows_paket);die;
                foreach ($rows_paket as $value) {
                    # code...
                    $pasien_pm = $this->db->get_where('pm_pasienpm_v', array('kode_penunjang' => $data_pm['kode_penunjang']))->row();
                    /*update status cito*/
                    $this->Reg_klinik->update('tc_kunjungan', array('status_cito' => 0),array('no_kunjungan' => $pasien_pm->no_kunjungan));

                    /*update pm_tc_penunjang*/
                    $this->Reg_klinik->update('pm_tc_penunjang', array('dr_pengirim' => $kode_dokter),array('kode_penunjang' => $data_pm['kode_penunjang']));

                    /*perhitungan billing*/
                    $kode_pemeriksaan = substr($value->referensi,-2);
                    $kode_pemeriksaan = $kode_pemeriksaan*1;
                    
                    if($kode_pemeriksaan==0)
                    {
                        $sqlo = $this->db->get_where('mt_master_tarif', array('referensi' => $value->referensi));
                        $sql=$sqlo->result();

                        foreach($sql as $val)
                        {
                            $kode_trans_pelayanan= $this->master->get_max_number('tc_trans_pelayanan','kode_trans_pelayanan');

                            $kode_anak= $val->kode_tarif;
                            $jenis_tindakan=3;
                           
                            $tarifUmum=new Tarif();
                            $tarifUmum->set("kode_tarif",$val->kode_tarif);
                            $tarifUmum->set("jumlah",1);
                            $tarifUmum->set("kode_klas",$pasien_pm->kode_klas);
                            $tarifUmum->set("kode_kelompok",$pasien_pm->kode_kelompok);
                            $tarifUmum->set("kode_bagian",'050101');
                            //$tarifUmum->set("cito",0);
                            $insertXxTrins=$tarifUmum->hitung();


                            $insertXxTrins["kode_trans_pelayanan"] = $kode_trans_pelayanan;
                            $insertXxTrins["no_kunjungan"] = $pasien_pm->no_kunjungan;
                            $insertXxTrins["no_registrasi"] = $no_registrasi;
                            $insertXxTrins["no_mr"] = $no_mr;
                            $insertXxTrins["nama_pasien_layan"] =$this->regex->_genRegex($this->form_validation->set_value('nama_pasien_hidden'),'RGXQSL');
                            $insertXxTrins["kode_kelompok"] = $pasien_pm->kode_kelompok;
                            //$insertXxTrins["kode_dokter"] =  $pasien_pm->kode_dokter;
                            $insertXxTrins["kode_perusahaan"] = $pasien_pm->kode_perusahaan;
                            $insertXxTrins["tgl_transaksi"] = date("Y-m-d H:i:s");
                            $insertXxTrins["jenis_tindakan"] = $jenis_tindakan;
                            $insertXxTrins["kode_dokter1"] = $data_pesan_bedah['dokter1'];
                            $insertXxTrins["kode_dokter2"] = 0;
                            //$insertXxTrins["kode_ri"] = 0;
                            //$insertXxTrins["kode_poli"] = 0;
                            $insertXxTrins["jumlah"] = 1;
                            $insertXxTrins["kode_barang"] = '';
                            $insertXxTrins["kode_penunjang"] = $data_pm['kode_penunjang'];
                            //$insertXxTrins["kode_depo_stok"] = 0;
                            //$insertXxTrins["kode_gd"] = 0;
                            $insertXxTrins["kd_tr_resep"] = 0;
                            $insertXxTrins["status_selesai"] = 2;
                            $insertXxTrins["kode_bagian"] = '050101';
                            $insertXxTrins["id_dd_user"] = '';
                            $insertXxTrins["kode_bagian_asal"] = $kode_bagian_masuk;
                            
                            $this->Reg_klinik->save('tc_trans_pelayanan', $insertXxTrins);
                        }//end while

                            


                    }else
                    {
                        $kode_trans_pelayanan= $this->master->get_max_number('tc_trans_pelayanan','kode_trans_pelayanan');
                        
                        $tarifUmum=new Tarif();
                        $tarifUmum->set("kode_tarif",$value->referensi);
                        $tarifUmum->set("jumlah",1);
                        $tarifUmum->set("kode_klas",$pasien_pm->kode_klas);
                        $tarifUmum->set("kode_kelompok",$pasien_pm->kode_kelompok);
                        $tarifUmum->set("kode_bagian",'050101');
                        //$tarifUmum->set("cito",0);
                        $insertXxTrins=$tarifUmum->hitung();
                        //show($insertXxTrins);

                        $jenis_tindakan=3;

                        $insertXxTrins["kode_trans_pelayanan"] = $kode_trans_pelayanan;
                        $insertXxTrins["no_kunjungan"] = $pasien_pm->no_kunjungan;
                        $insertXxTrins["no_registrasi"] = $no_registrasi;
                        $insertXxTrins["no_mr"] = $no_mr;
                        $insertXxTrins["nama_pasien_layan"] =$this->regex->_genRegex($this->form_validation->set_value('nama_pasien_hidden'),'RGXQSL');
                        $insertXxTrins["kode_kelompok"] = $pasien_pm->kode_kelompok;
                        //$insertXxTrins["kode_dokter"] =  $data_pesan_bedah['dokter1'];
                        $insertXxTrins["kode_perusahaan"] = $pasien_pm->kode_perusahaan;
                        $insertXxTrins["tgl_transaksi"] = date("Y-m-d H:i:s");
                        $insertXxTrins["jenis_tindakan"] = $jenis_tindakan;
                        $insertXxTrins["kode_dokter1"] = $data_pesan_bedah['dokter1'];
                        $insertXxTrins["kode_dokter2"] = 0;
                        //$insertXxTrins["kode_ri"] = 0;
                        //$insertXxTrins["kode_poli"] = 0;
                        $insertXxTrins["jumlah"] = 1;
                        $insertXxTrins["kode_barang"] = '';
                        $insertXxTrins["kode_penunjang"] = $data_pm['kode_penunjang'];
                        //$insertXxTrins["kode_depo_stok"] = 0;
                        //$insertXxTrins["kode_gd"] = 0;
                        $insertXxTrins["kd_tr_resep"] = 0;
                        $insertXxTrins["status_selesai"] = 2;
                        $insertXxTrins["kode_bagian"] = '050101';
                        $insertXxTrins["id_dd_user"] = '';
                        $insertXxTrins["kode_bagian_asal"] = $kode_bagian_masuk;
                        $this->Reg_klinik->save('tc_trans_pelayanan', $insertXxTrins);

                    }//end if kopem

                
                $this->Reg_klinik->update('pm_tc_penunjang', array('status_daftar' => 1), array('kode_penunjang' =>  $data_pm['kode_penunjang']));
                
               
                $this->Reg_klinik->update('tc_trans_pelayanan', array('status_selesai' => 2), array('kode_penunjang' =>  $data_pm['kode_penunjang']));

                }
            }

            $this->Reg_klinik->update('tc_trans_pelayanan', array('bill_rs' => 0,'bill_dr1' => 0,'bill_dr2' => 0, 'bill_dr3' =>0), array('no_registrasi' =>  $no_registrasi,'jenis_tindakan' <> 14));

         
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'no_mr' => $_POST['noMrHidden'], 'no_registrasi' => $no_registrasi, 'is_new' => $this->input->post('is_new')));
            }

        
        }

    }

    public function penunjang_medis($dataexc)
    {
        # code...
       
        $no_kunjungan_pm = $this->master->get_max_number('tc_kunjungan','no_kunjungan');
        $datakunjungan_pm = array(
            'no_kunjungan' => $no_kunjungan_pm,
            'no_registrasi' => $no_registrasi,
            'no_mr' => $no_mr,
            'kode_dokter' => $kode_dokter,
            'kode_bagian_tujuan' =>  $this->regex->_genRegex('050101','RGXQSL'),
            'kode_bagian_asal' => $kode_bagian_masuk,
            'tgl_masuk' => date('Y-m-d H:i:s'),
            'status_masuk' => 0,
            'status_cito' => 0,
            'tgl_keluar' => date('Y-m-d H:i:s'),
            'status_keluar' => 1,
        );

        print_r($datakunjungan_pm);die;
    }


}



/* End of file example.php */

/* Location: ./application/functiones/example/controllers/example.php */
