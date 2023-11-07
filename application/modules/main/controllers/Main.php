<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Main extends MX_Controller {

    function __construct() {
        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Home', 'main');

        if($this->session->userdata('logged')!=TRUE){
            redirect(base_url().'login');
        }
        /*load other module*/
        $this->load->model('kepegawaian/Kepeg_dt_pegawai_model', 'Kepeg_dt_pegawai');
        $this->load->module('setting/Tmp_user');
        $this->load->model('setting/Tmp_user_model','Tmp_user');

    }

    public function index() {
        $this->load->library('lib_menus');
        $this->output->enable_profiler(false);
        /*breadcrumb*/
        $this->breadcrumbs->push('Welcome', 'main/'.strtolower(get_class($this)));
         $data = array(
            'title' => 'Home',
            'subtitle' => 'Welcome '.$this->session->userdata('user')->fullname,
            'breadcrumbs' => $this->breadcrumbs->show(),
            'app' => $this->db->get_where('tmp_profile_app', array('id' => 1))->row(),
            'user' => $this->Tmp_user->get_by_id($this->session->userdata('user')->user_id),
            'profile_user' => $this->db->get_where('tmp_user_profile', array('user_id' => $this->session->userdata('user')->user_id))->row(),
            'modul' => $this->lib_menus->get_modules_by_user_id($this->session->userdata('user')->user_id),            
        );

        $this->load->view('Main/main_view', $data);

    }

    public function form_profile() {
        $this->output->enable_profiler(false);
        /*breadcrumb*/
        $this->breadcrumbs->push('Welcome', 'main/'.strtolower(get_class($this)));
         $data = array(
            'title' => 'Form Profil',
            'subtitle' => 'Silahkan isi data lengkap anda',
            'breadcrumbs' => $this->breadcrumbs->show(),
            'app' => $this->db->get_where('tmp_profile_app', array('id' => 1))->row(),
            'user' => $this->Tmp_user->get_by_id($this->session->userdata('user')->user_id),
            'profile_user' => $this->db->get_where('tmp_user_profile', array('user_id' => $this->session->userdata('user')->user_id))->row(),
            'modul' => $this->lib_menus->get_modules_by_user_id($this->session->userdata('user')->user_id),
            
        );

        $this->load->view('Main/form_profile', $data);

    }

    public function modul_view() {
        $this->load->library('lib_menus');
        $this->output->enable_profiler(false);
        /*breadcrumb*/
        $this->breadcrumbs->push('Welcome', 'main/'.strtolower(get_class($this)));
         $data = array(
            'title' => 'Home',
            'subtitle' => 'Welcome Amin',
            'breadcrumbs' => $this->breadcrumbs->show(),
            'app' => $this->db->get_where('tmp_profile_app', array('id' => 1))->row(),
            'user' => $this->Tmp_user->get_by_id($this->session->userdata('user')->user_id),
            'profile_user' => $this->db->get_where('tmp_user_profile', array('user_id' => $this->session->userdata('user')->user_id))->row(),
            'modul' => $this->lib_menus->get_modules_by_user_id($this->session->userdata('user')->user_id),
            
        );

        //echo '<pre>';print_r($data);die;

        $this->load->view('Main/modul_view', $data);
        
    }


    public function process_update_kepegawaian()
    {
        echo '<pre>';print_r($_POST);die;
        $this->load->library('form_validation');
        $val = $this->form_validation;

        $val->set_rules('kepeg_nip','NIP Pegawai', 'integer|trim|required'); 
        $val->set_rules('nama_pegawai','Nama Pegawai', 'trim|required');
        $val->set_rules('kepeg_no_telp','No Telpon Pegawai', 'integer|trim|required');
        $val->set_rules('kepeg_email','Email Pegawai', 'trim|valid_email');
        $val->set_rules('kepeg_gol','Golongan Pegawai', 'trim|required');
        $val->set_rules('kepeg_pendidikan_terakhir','Pendidikan Terakhir', 'trim|required');
        $val->set_rules('kepeg_unit','Unit Pegawai', 'trim|required');
        $val->set_rules('kepeg_level','Jabatan Pegawai', 'trim|required');
        $val->set_rules('kepeg_hak_perawatan','Hak Keperawatan', 'trim');
        $val->set_rules('kepeg_tenaga_medis','Jenis Pegawai', 'trim|required');
        $val->set_rules('kepeg_status_kerja','Status Kepegawaian', 'trim|required');
        $val->set_rules('kepeg_tgl_aktif','Tanggal Aktif Pegawai', 'trim|required');
        $val->set_rules('kepeg_tgl_selesai','Tanggal Berakhir Kerja', 'trim');
        $val->set_rules('kepeg_masa_kontrak','Masa Kontrak (bulan)', 'trim');
        $val->set_rules('kepeg_status_aktif','Status Aktif Pegawai', 'trim|required');

        $val->set_message('required', "Silahkan isi field \"%s\"");

        if ($val->run() == FALSE)
        {
            $val->set_error_delimiters('<div style="color:white">', '</div>');
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            $this->db->trans_begin();
            
            $id = ($this->input->post('kepeg_id'))?$this->regex->_genRegex($this->input->post('kepeg_id'),'RGXINT'):0;
            $nik = ($this->input->post('nik'))?$this->regex->_genRegex($this->input->post('nik'),'RGXINT'):0;
            $profil_id = ($this->input->post('profil_id'))?$this->regex->_genRegex($this->input->post('profil_id'),'RGXINT'):0;
            $no_induk = ($this->input->post('no_induk'))?$this->regex->_genRegex($this->input->post('no_induk'),'RGXQSL'):0;

            $dataktp = array(
                'ktp_nik' => $this->regex->_genRegex($nik, 'RGXQSL'),
                'ktp_nama_lengkap' => $this->regex->_genRegex($_POST['nama_pegawai'], 'RGXQSL'),
                'religion_id' => $this->regex->_genRegex($_POST['religion'], 'RGXQSL'),
                'ms_id' => $this->regex->_genRegex($_POST['marital_status'], 'RGXQSL'),
                'tb_id' => $this->regex->_genRegex($_POST['type_blood'], 'RGXQSL'),
                'province_id' => $this->regex->_genRegex($_POST['provinsiHidden'], 'RGXQSL'),
                'city_id' => $this->regex->_genRegex($_POST['kotaHidden'], 'RGXQSL'),
                'district_id' => $this->regex->_genRegex($_POST['kecamatanHidden'], 'RGXQSL'),
                'sub_district_id' => $this->regex->_genRegex($_POST['kelurahanHidden'], 'RGXQSL'),
                'ktp_jk' => $this->regex->_genRegex($_POST['gender'], 'RGXQSL'),
                'ktp_tempat_lahir' => $this->regex->_genRegex($_POST['tmp_lahir'], 'RGXQSL'),
                'ktp_tanggal_lahir' => $this->regex->_genRegex($_POST['dob_pegawai'], 'RGXQSL'),
                'ktp_alamat' => $this->regex->_genRegex($_POST['alamat'], 'RGXQSL'),
                'ktp_rt' => $this->regex->_genRegex($_POST['rt'], 'RGXQSL'),
                'ktp_rw' => $this->regex->_genRegex($_POST['rw'], 'RGXQSL'),
                'zipcode' => $this->regex->_genRegex($_POST['zipcode'], 'RGXQSL'),
            );

            $dataexc = array(
                'kepeg_nik' => $this->regex->_genRegex($nik, 'RGXQSL'),
                'kepeg_nip' => $this->regex->_genRegex($val->set_value('kepeg_nip'), 'RGXQSL'),
                'kepeg_no_telp' => $this->regex->_genRegex($val->set_value('kepeg_no_telp'), 'RGXQSL'),
                'kepeg_email' => $this->regex->_genRegex($val->set_value('kepeg_email'), 'RGXQSL'),
                'kepeg_gol' => $this->regex->_genRegex($val->set_value('kepeg_gol'), 'RGXQSL'),
                'kepeg_pendidikan_terakhir' => $this->regex->_genRegex($val->set_value('kepeg_pendidikan_terakhir'), 'RGXQSL'),
                'kepeg_unit' => $this->regex->_genRegex($val->set_value('kepeg_unit'), 'RGXQSL'),
                'kepeg_level' => $this->regex->_genRegex($val->set_value('kepeg_level'), 'RGXQSL'),
                'kepeg_hak_perawatan' => $this->regex->_genRegex($val->set_value('kepeg_hak_perawatan'), 'RGXQSL'),
                'kepeg_tenaga_medis' => $this->regex->_genRegex($val->set_value('kepeg_tenaga_medis'), 'RGXQSL'),
                'kepeg_status_kerja' => $this->regex->_genRegex($val->set_value('kepeg_status_kerja'), 'RGXQSL'),
                'kepeg_tgl_aktif' => $this->regex->_genRegex($val->set_value('kepeg_tgl_aktif'), 'RGXQSL'),
                'kepeg_tgl_selesai' => $this->regex->_genRegex($val->set_value('kepeg_tgl_selesai'), 'RGXQSL'),
                'kepeg_masa_kontrak' => $this->regex->_genRegex($val->set_value('kepeg_masa_kontrak'), 'RGXQSL'),
                'kepeg_status_aktif' => $this->regex->_genRegex($val->set_value('kepeg_status_aktif'), 'RGXQSL'),
            );

            $datakaryawan = array(
                'no_induk' => $this->regex->_genRegex($val->set_value('kepeg_nip'), 'RGXQSL'),
                'nama_pegawai' => $this->regex->_genRegex($val->set_value('nama_pegawai'), 'RGXQSL'),
                'kode_jabatan' => $this->regex->_genRegex($val->set_value('kepeg_level'), 'RGXQSL'),
                'kode_bagian' => $this->regex->_genRegex($val->set_value('kepeg_unit'), 'RGXQSL'),
                'is_active' => $this->regex->_genRegex($val->set_value('kepeg_status_aktif'), 'RGXQSL'),
                'integration' => 1,
            );

            $dataprofil = array(
                'fullname' => $this->regex->_genRegex($_POST['nama_pegawai'],'RGXQSL'),
                'pob' => $this->regex->_genRegex($_POST['tmp_lahir'],'RGXQSL'),
                'dob' => $this->regex->_genRegex($_POST['dob_pegawai'],'RGXQSL'),
                'address' => $this->regex->_genRegex($_POST['alamat'],'RGXQSL'),
                'no_telp' => $this->regex->_genRegex($_POST['kepeg_no_telp'],'RGXQSL'),
                'gender' => $this->regex->_genRegex($_POST['gender'],'RGXQSL'),
                'no_ktp' => $this->regex->_genRegex($nik,'RGXQSL'),
                'nip' => $this->regex->_genRegex($val->set_value('kepeg_nip'), 'RGXQSL'),
                'user_id' => $this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'),
            );
            
            if(isset($_FILES['pas_foto']['name'])){
                /*hapus dulu file yang lama*/
                if( $id != 0 ){
                    $profile = $this->db->get_where('tmp_user_profile', array('user_id' => $this->session->userdata('user')->user_id) )->row();
                    if ($profile->path_foto != NULL) {
                        unlink(PATH_PHOTO_PROFILE_DEFAULT.$profile->path_foto.'');
                    }
                }

                $dataprofil['path_foto'] = $this->upload_file->doUpload('pas_foto', PATH_PHOTO_PROFILE_DEFAULT);
            }

            
            if(isset($_FILES['pas_foto']['name'])){
                /*hapus dulu file yang lama*/
                if( $id != 0 ){
                    $pas_foto = $this->db->get_where('ktp', array('ktp_nik' => $nik) )->row();
                    if ($pas_foto->ktp_foto != NULL) {
                        unlink(PATH_PHOTO_PEGAWAI.$pas_foto->ktp_foto.'');
                    }
                }
                $dataktp['ktp_foto'] = $this->upload_file->doUpload('pas_foto', PATH_PHOTO_PEGAWAI);
            }

            // insert ktp
            if($nik==0){
                $dataktp['created_date'] = date('Y-m-d H:i:s');
                $dataktp['created_by'] = $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL');
                $this->db->insert('ktp', $dataktp);
            }else{
                $dataktp['updated_date'] = date('Y-m-d H:i:s');
                $dataktp['updated_by'] = $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL');
                /*update record*/
                $this->db->where(array('ktp_nik' => $nik))->update('ktp', $dataktp);
                $newId = $id;
            }

            // insert pegawai
            if($id==0){
                $dataexc['created_date'] = date('Y-m-d H:i:s');
                $dataexc['created_by'] = $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL');
                // save data pegawai
                $newId = $this->db->insert('kepeg_dt_pegawai', $dataexc);
            }else{
                $dataexc['updated_date'] = date('Y-m-d H:i:s');
                $dataexc['updated_by'] = $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL');
                /*update record*/
                $this->db->where(array('kepeg_id' => $id))->update('kepeg_dt_pegawai', $dataexc);
            }

            // insert karyawan
            if($no_induk==0){
                $datakaryawan['created_date'] = date('Y-m-d H:i:s');
                $datakaryawan['created_by'] = $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL');
                // save data pegawai
                $newId = $this->db->insert('mt_karyawan', $datakaryawan);
            }else{
                $datakaryawan['updated_date'] = date('Y-m-d H:i:s');
                $datakaryawan['updated_by'] = $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL');
                /*update record*/
                $this->db->where(array('no_induk' => $no_induk))->update('mt_karyawan', $datakaryawan);
            }

            //echo '<pre>';print_r($id);die;
            if($profil_id==0){
                $dataprofil['created_date'] = date('Y-m-d H:i:s');
                $dataprofil['created_by'] = $this->session->userdata('user')->fullname;
                $this->db->insert('tmp_user_profile', $dataprofil);
            }else{
                $dataprofil['updated_date'] = date('Y-m-d H:i:s');
                $dataprofil['updated_by'] = $this->session->userdata('user')->fullname;
                $this->db->where(array('user_id' => $id))->update('tmp_user_profile', $dataprofil);
            }

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

    public function process()
    {
        // echo '<pre>';print_r($_POST);die;
        $this->load->library('form_validation');
        $val = $this->form_validation;

        $val->set_rules('kepeg_nip','NIP Pegawai', 'integer|trim|required'); 
        $val->set_rules('nama_pegawai','Nama Pegawai', 'trim|required');
        $val->set_rules('kepeg_no_telp','No Telpon Pegawai', 'integer|trim|required');
        $val->set_rules('kepeg_email','Email Pegawai', 'trim|valid_email');
        $val->set_rules('kepeg_gol','Golongan Pegawai', 'trim|required');
        $val->set_rules('kepeg_pendidikan_terakhir','Pendidikan Terakhir', 'trim|required');
        $val->set_rules('kepeg_unit','Unit Pegawai', 'trim|required');
        $val->set_rules('kepeg_level','Jabatan Pegawai', 'trim|required');
        $val->set_rules('kepeg_hak_perawatan','Hak Keperawatan', 'trim');
        $val->set_rules('kepeg_tenaga_medis','Jenis Pegawai', 'trim|required');
        $val->set_rules('kepeg_status_kerja','Status Kepegawaian', 'trim|required');
        $val->set_rules('kepeg_tgl_aktif','Tanggal Aktif Pegawai', 'trim|required');
        $val->set_rules('kepeg_tgl_selesai','Tanggal Berakhir Kerja', 'trim');
        $val->set_rules('kepeg_masa_kontrak','Masa Kontrak (bulan)', 'trim');
        $val->set_rules('kepeg_status_aktif','Status Aktif Pegawai', 'trim|required');

        $val->set_message('required', "Silahkan isi field \"%s\"");

        if ($val->run() == FALSE)
        {
            $val->set_error_delimiters('<div style="color:white">', '</div>');
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            $this->db->trans_begin();
            
            $id = ($this->input->post('kepeg_id'))?$this->regex->_genRegex($this->input->post('kepeg_id'),'RGXINT'):0;
            $nik = ($this->input->post('nik'))?$this->regex->_genRegex($this->input->post('nik'),'RGXINT'):0;
            $profil_id = ($this->input->post('profil_id'))?$this->regex->_genRegex($this->input->post('profil_id'),'RGXINT'):0;
            $no_induk = ($this->input->post('no_induk'))?$this->regex->_genRegex($this->input->post('no_induk'),'RGXQSL'):0;

            $dataktp = array(
                'ktp_nik' => $this->regex->_genRegex($nik, 'RGXQSL'),
                'ktp_nama_lengkap' => $this->regex->_genRegex($_POST['nama_pegawai'], 'RGXQSL'),
                'religion_id' => $this->regex->_genRegex($_POST['religion'], 'RGXQSL'),
                'ms_id' => $this->regex->_genRegex($_POST['marital_status'], 'RGXQSL'),
                'tb_id' => $this->regex->_genRegex($_POST['type_blood'], 'RGXQSL'),
                'province_id' => $this->regex->_genRegex($_POST['provinsiHidden'], 'RGXQSL'),
                'city_id' => $this->regex->_genRegex($_POST['kotaHidden'], 'RGXQSL'),
                'district_id' => $this->regex->_genRegex($_POST['kecamatanHidden'], 'RGXQSL'),
                'sub_district_id' => $this->regex->_genRegex($_POST['kelurahanHidden'], 'RGXQSL'),
                'ktp_jk' => $this->regex->_genRegex($_POST['gender'], 'RGXQSL'),
                'ktp_tempat_lahir' => $this->regex->_genRegex($_POST['tmp_lahir'], 'RGXQSL'),
                'ktp_tanggal_lahir' => $this->regex->_genRegex($_POST['dob_pegawai'], 'RGXQSL'),
                'ktp_alamat' => $this->regex->_genRegex($_POST['alamat'], 'RGXQSL'),
                'ktp_rt' => $this->regex->_genRegex($_POST['rt'], 'RGXQSL'),
                'ktp_rw' => $this->regex->_genRegex($_POST['rw'], 'RGXQSL'),
                'zipcode' => $this->regex->_genRegex($_POST['zipcode'], 'RGXQSL'),
            );

            // echo '<pre>';print_r($dataktp);die;

            $dataexc = array(
                'kepeg_nik' => $this->regex->_genRegex($nik, 'RGXQSL'),
                'kepeg_nip' => $this->regex->_genRegex($val->set_value('kepeg_nip'), 'RGXQSL'),
                'kepeg_no_telp' => $this->regex->_genRegex($val->set_value('kepeg_no_telp'), 'RGXQSL'),
                'kepeg_email' => $this->regex->_genRegex($val->set_value('kepeg_email'), 'RGXQSL'),
                'kepeg_gol' => $this->regex->_genRegex($val->set_value('kepeg_gol'), 'RGXQSL'),
                'kepeg_pendidikan_terakhir' => $this->regex->_genRegex($val->set_value('kepeg_pendidikan_terakhir'), 'RGXQSL'),
                'kepeg_unit' => $this->regex->_genRegex($val->set_value('kepeg_unit'), 'RGXQSL'),
                'kepeg_level' => $this->regex->_genRegex($val->set_value('kepeg_level'), 'RGXQSL'),
                'kepeg_hak_perawatan' => $this->regex->_genRegex($val->set_value('kepeg_hak_perawatan'), 'RGXQSL'),
                'kepeg_tenaga_medis' => $this->regex->_genRegex($val->set_value('kepeg_tenaga_medis'), 'RGXQSL'),
                'kepeg_status_kerja' => $this->regex->_genRegex($val->set_value('kepeg_status_kerja'), 'RGXQSL'),
                'kepeg_tgl_aktif' => $this->regex->_genRegex($val->set_value('kepeg_tgl_aktif'), 'RGXQSL'),
                'kepeg_tgl_selesai' => $this->regex->_genRegex($val->set_value('kepeg_tgl_selesai'), 'RGXQSL'),
                'kepeg_masa_kontrak' => $this->regex->_genRegex($val->set_value('kepeg_masa_kontrak'), 'RGXQSL'),
                'kepeg_status_aktif' => $this->regex->_genRegex($val->set_value('kepeg_status_aktif'), 'RGXQSL'),
            );
            // echo '<pre>';print_r($dataexc);die;

            $datakaryawan = array(
                'no_induk' => $this->regex->_genRegex($val->set_value('kepeg_nip'), 'RGXQSL'),
                'nama_pegawai' => $this->regex->_genRegex($val->set_value('nama_pegawai'), 'RGXQSL'),
                'kode_jabatan' => $this->regex->_genRegex($val->set_value('kepeg_level'), 'RGXQSL'),
                'kode_bagian' => $this->regex->_genRegex($val->set_value('kepeg_unit'), 'RGXQSL'),
                'is_active' => $this->regex->_genRegex($val->set_value('kepeg_status_aktif'), 'RGXQSL'),
                'integration' => 1,
            );
            // echo '<pre>';print_r($datakaryawan);die;

            $dataprofil = array(
                'fullname' => $this->regex->_genRegex($_POST['nama_pegawai'],'RGXQSL'),
                'pob' => $this->regex->_genRegex($_POST['tmp_lahir'],'RGXQSL'),
                'dob' => $this->regex->_genRegex($_POST['dob_pegawai'],'RGXQSL'),
                'address' => $this->regex->_genRegex($_POST['alamat'],'RGXQSL'),
                'no_telp' => $this->regex->_genRegex($_POST['kepeg_no_telp'],'RGXQSL'),
                'gender' => $this->regex->_genRegex($_POST['gender'],'RGXQSL'),
                'no_ktp' => $this->regex->_genRegex($nik,'RGXQSL'),
                'nip' => $this->regex->_genRegex($val->set_value('kepeg_nip'), 'RGXQSL'),
                'user_id' => $this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'),
            );
            // echo '<pre>';print_r($dataprofil);die;
            
            if(isset($_FILES['pas_foto']['name'])){
                /*hapus dulu file yang lama*/
                if( $profile_id != 0 ){
                    $profile = $this->db->get_where('tmp_user_profile', array('user_id' => $this->session->userdata('user')->user_id) )->row();
                    if ($profile->path_foto != NULL) {
                        unlink(PATH_PHOTO_PROFILE_DEFAULT.$profile->path_foto.'');
                    }
                }

                if( $nik != 0 ){
                    $pas_foto = $this->db->get_where('ktp', array('ktp_nik' => $nik) )->row();
                    if ($pas_foto->ktp_foto != NULL) {
                        unlink(PATH_PHOTO_PEGAWAI.$pas_foto->ktp_foto.'');
                    }
                }
                
                
                $foto_name = $this->upload_file->doUpload('pas_foto', PATH_PHOTO_PROFILE_DEFAULT);

                $dataprofil['path_foto'] = $foto_name;
                $dataktp['ktp_foto'] = $foto_name;
            }

            // insert ktp
            if($nik==0){
                $dataktp['created_date'] = date('Y-m-d H:i:s');
                $dataktp['created_by'] = $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL');
                $this->db->insert('ktp', $dataktp);
            }else{
                $dataktp['updated_date'] = date('Y-m-d H:i:s');
                $dataktp['updated_by'] = $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL');
                /*update record*/
                $this->db->where(array('ktp_nik' => $nik))->update('ktp', $dataktp);
                $newId = $id;
            }

            // insert pegawai
            if($id==0){
                $dataexc['created_date'] = date('Y-m-d H:i:s');
                $dataexc['created_by'] = $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL');
                // save data pegawai
                $newId = $this->db->insert('kepeg_dt_pegawai', $dataexc);
            }else{
                $dataexc['updated_date'] = date('Y-m-d H:i:s');
                $dataexc['updated_by'] = $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL');
                /*update record*/
                $this->db->where(array('kepeg_id' => $id))->update('kepeg_dt_pegawai', $dataexc);
            }

            // insert karyawan
            if($no_induk==0){
                $datakaryawan['created_date'] = date('Y-m-d H:i:s');
                $datakaryawan['created_by'] = $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL');
                // save data pegawai
                $newId = $this->db->insert('mt_karyawan', $datakaryawan);
            }else{
                $datakaryawan['updated_date'] = date('Y-m-d H:i:s');
                $datakaryawan['updated_by'] = $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL');
                /*update record*/
                $this->db->where(array('no_induk' => $no_induk))->update('mt_karyawan', $datakaryawan);
            }

            if($profil_id==0){
                $dataprofil['created_date'] = date('Y-m-d H:i:s');
                $dataprofil['created_by'] = $this->session->userdata('user')->fullname;
                $this->db->insert('tmp_user_profile', $dataprofil);
            }else{
                $dataprofil['updated_date'] = date('Y-m-d H:i:s');
                $dataprofil['updated_by'] = $this->session->userdata('user')->fullname;
                $this->db->where(array('up_id' => $profil_id))->update('tmp_user_profile', $dataprofil);
            }

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




}

/* End of file empty_module.php */
/* Location: ./application/modules/empty_module/controllers/empty_module.php */

