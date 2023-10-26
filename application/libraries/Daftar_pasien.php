<?php

/*
 * To change this template, choose Tools | templates
 * and open the template in the editor.
 */

final Class Daftar_pasien {

    public function daftar_registrasi($title='',$no_mr='', $kode_perusahaan='', $kode_kelompok='', $kode_dokter='', $kode_bagian_masuk='', $umur_saat_pelayanan='',$no_sep='',$jd_id='', $kode_faskes='', $tgl_registrasi='', $nomorrujukan='', $jeniskunjunganbpjs='') {
        
        $CI =& get_instance();
        $CI->load->library('session'); 
        $CI->load->library('master'); 
        $CI->load->library('logs');               
        $CI->load->database('default', TRUE);

        $no_registrasi = $CI->master->get_max_number('tc_registrasi', 'no_registrasi');

        $stat_pasien = $CI->master->check_pasien_lama_baru($no_mr); 

        /*save logs*/
    	  $data = array(
          'no_registrasi' => $no_registrasi,
          'no_mr' => $no_mr,
          'kode_perusahaan' => $kode_perusahaan,
          'kode_kelompok' => $kode_kelompok,
          'kode_dokter' => $kode_dokter,
          'tgl_jam_masuk' => ($tgl_registrasi == '') ? date('Y-m-d H:i:s') : $tgl_registrasi,
          'stat_pasien' => $stat_pasien,
          'kode_bagian_masuk' => $kode_bagian_masuk,
          'status_registrasi' => 0,
          'umur' => $umur_saat_pelayanan,
          'no_sep' => $no_sep,
          'sirs_v1' => 1,
          'jd_id' => $jd_id,
          'kode_faskes' => $kode_faskes,
          'norujukan' => $nomorrujukan,
          'jeniskunjunganbpjs' => $jeniskunjunganbpjs,
          'no_induk' => isset($CI->session->userdata('user')->user_id)?$CI->session->userdata('user')->user_id:0,
          'created_date' => date('Y-m-d H:i:s'),
          'updated_date' => date('Y-m-d H:i:s'),
        );
        
        /*print_r($data);die;*/
        $CI->db->insert('tc_registrasi', $data);
        $newId = $CI->db->insert_id();

        $CI->logs->save('tc_registrasi', $newId, 'insert new record on '.$title.' module', json_encode($data),'id_tc_registrasi');

        $no_kunjungan = $this->daftar_kunjungan($title,$no_registrasi,$no_mr,$kode_dokter,$kode_bagian_masuk,$kode_bagian_masuk, $tgl_registrasi);

        // get detail no sep
        
        
        return array('no_registrasi' => $no_registrasi, 'no_kunjungan' => $no_kunjungan);
    }

    public function daftar_kunjungan($title,$no_registrasi='',$no_mr='',$kode_dokter='',$kode_bagian_tujuan='',$kode_bagian_asal='', $tgl_kunjungan='') {
        
        $CI =& get_instance();
        $CI->load->library('session');          
        $CI->load->library('master'); 
        $CI->load->library('logs');         
        $CI->load->database('default', TRUE);  

        $no_kunjungan = $CI->master->get_max_number('tc_kunjungan', 'no_kunjungan');

        $datakunjungan = array(
            'no_kunjungan' => $no_kunjungan,
            'no_registrasi' => $no_registrasi,
            'no_mr' => $no_mr,
            'kode_dokter' => $kode_dokter,
            'kode_bagian_tujuan' => $kode_bagian_tujuan,
            'kode_bagian_asal' => $kode_bagian_asal,
            'tgl_masuk' => ($tgl_kunjungan == '') ? date('Y-m-d H:i:s') : $tgl_kunjungan,
            'status_masuk' => ($kode_bagian_asal!=$kode_bagian_tujuan)?1:0,
            'created_date' => date('Y-m-d H:i:s'),
            'updated_date' => date('Y-m-d H:i:s'),
        );

        
        $CI->db->insert('tc_kunjungan', $datakunjungan);
        $newId = $CI->db->insert_id();

        $CI->logs->save('tc_kunjungan', $newId, 'insert new record on '.$title.' module', json_encode($datakunjungan),'id_tc_kunjungan');

        return $no_kunjungan;
    }

    public function pulangkan_pasien($no_kunjungan,$status_keluar='') {
        
        $CI =& get_instance();
        $CI->load->library('session');          
        $CI->load->library('master'); 
        $CI->load->library('logs');         
        $CI->load->database('default', TRUE);  

        /*update kode_bagian_keluar*/
        $arrRegistrasi = array('kode_bagian_keluar' => $_POST['kode_bagian_asal']);
        $CI->db->update('tc_registrasi', $arrRegistrasi, array('no_registrasi' => $_POST['no_registrasi']) );
        /*save logs tc_registrasi*/
        $CI->logs->save('tc_registrasi', $_POST['no_registrasi'], 'update tc_registrasi modul pelayanan', json_encode($arrRegistrasi),'no_registrasi');

        /*update tc_kunjungan*/
        $arrKunjungan = array(
            'status_keluar' => ($status_keluar!='')?$status_keluar:3, 
            'tgl_keluar' => date('Y-m-d H:i:s'), 
            'cara_keluar_pasien' => isset($_POST['cara_keluar'])?$_POST['cara_keluar']:'', 
            'pasca_pulang' => isset($_POST['pasca_pulang'])?$_POST['pasca_pulang']:'',
            'updated_date' => date('Y-m-d H:i:s'),
        );

        $CI->db->update('tc_kunjungan', $arrKunjungan, array('no_kunjungan' => $no_kunjungan) );
        
        /*save logs tc_kunjungan*/
        $CI->logs->save('tc_kunjungan', $no_kunjungan, 'update tc_kunjungan modul pelayanan', json_encode($arrKunjungan),'no_kunjungan');

        /*update tc_trans_pelayanan*/
        $CI->db->update('tc_trans_pelayanan', array('status_selesai' => 2), array('no_kunjungan' => $no_kunjungan ) );
        $CI->logs->save('tc_trans_pelayanan', $no_kunjungan, 'update tc_kunjungan modul pelayanan', json_encode(array('status_selesai' => 2)),'no_kunjungan');

        return true;
    }


}
    
?>