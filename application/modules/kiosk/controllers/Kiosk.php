<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Kiosk extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('antrian/antrian_model'); 
        $this->load->model('registration/Reg_pasien_model', 'Reg_pasien');
        $this->load->model('registration/Reg_klinik_model', 'Reg_klinik');
        $this->load->model('antrian/loket_model','loket');
        $this->load->model('display_loket/main_model','Main');
        $this->load->model('ws_bpjs/Ws_index_model', 'Ws_index');
        $this->load->model('information/Regon_info_jadwal_dr_model', 'Regon_info_jadwal_dr');
        $this->load->model('registration/Input_pasien_baru_model', 'Input_pasien_baru');
        
        $this->load->library('Daftar_pasien');
        $this->load->library('Form_validation');
        $this->load->library('Print_direct');
        $this->load->library('Print_escpos');  
        $this->load->library('tarif');   

        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->
        get_menu_by_class(get_class($this))->name : 'Title';

        $this->username = '4dm1nR55m';
        $this->password = 'P@s5W0rdR55m';
 
        $this->load->model('Kiosk_model','Kiosk'); 

    }

    public function index() {
        
        $data = array();
        $data['app'] = $this->db->get_where('tmp_profile_app', array('id' => 1))->row();
        $this->load->view('Kiosk/index', $data);
    }

    public function main() {
        $this->load->view('Kiosk/main_view');
    }

    public function pasien_lama() {
        $this->load->view('Kiosk/form_search_pasien');
    }

    public function pasien_baru() {
        $this->load->view('Kiosk/form_create_pasien');
    }

    public function umum_asuransi() {
        $this->load->view('Kiosk/asum_view');
    }

    public function bpjs() {
        $this->load->view('Kiosk/bpjs_view');
    }

    public function checkIn() {
        $data = array();
        $data['registrasi'] = $this->Kiosk->getRegisterPasienByCurrentDate($_GET['no_mr']);
        // echo '<pre>';print_r($data);die;
        $this->load->view('Kiosk/checkin_view', $data);
    }

    // modul antrian
    public function antrian() {
        
        $data = array();
        
        $data_loket = $this->loket->get_open_loket();

        foreach ($data_loket as $key => $value) {
            # code...
            $kuota = $this->loket->get_sisa_kuota($value);
            if($kuota<0)$kuota=0;
            $data_loket[$key]->kuota = $kuota;
        }

        $data['type'] = isset($_GET['type'])?$_GET['type']:'bpjs';
        $data['klinik'] = $data_loket;

        $this->load->view('kiosk/Kiosk/antrian_view', $data);
    }

    public function antrian_front() {
        
        $data = array();
        
        $data_loket = $this->loket->get_open_loket();

        foreach ($data_loket as $key => $value) {
            # code...
            $kuota = $this->loket->get_sisa_kuota($value);
            if($kuota<0)$kuota=0;
            $data_loket[$key]->kuota = $kuota;
        }

        $data['type'] = isset($_GET['type'])?$_GET['type']:'bpjs';
        $data['klinik'] = $data_loket;

        $this->load->view('kiosk/Kiosk/antrian_front_view', $data);
    }
    // end modul antrian

    // ========================================================================================================= //

    // modul perjanjian
    public function spesialis() { 
        /*define variable data*/
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show(),
            'spesialis' => $this->db->select('kode_bagian, nama_bagian')->like('nama_bagian', 'klinik')->get_where('mt_bagian', array('validasi' => 100, 'status_aktif' => 1))->result(),
        );
        /*load view index*/
        $this->load->view('kiosk/Kiosk/spesialis_view', $data);
    }

    public function spesialis_front() { 
        /*define variable data*/
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show(),
            'spesialis' => $this->db->select('kode_bagian, nama_bagian')->like('nama_bagian', 'klinik')->get_where('mt_bagian', array('validasi' => 100, 'status_aktif' => 1))->result(),
        );
        /*load view index*/
        $this->load->view('kiosk/Kiosk/spesialis_front_view', $data);
    }

    public function jadwal_dokter() { 
        /*define variable data*/

        /*get data from model*/
        $list = $this->Regon_info_jadwal_dr->get_data();
        $arrData = array();
        /*format data*/
        foreach ($list as $key => $value) {
            $arrData[$value['jd_kode_dokter']][$value['jd_kode_spesialis']][] = $value;
        }

        $data = array();

        foreach ($arrData as $key => $row_list) {

            foreach ($row_list as $key_2 => $value_2) {
                $main_data = $value_2[0];
                $row = array();
                $row['nama_dr'] = $main_data['nama_pegawai'];

                for ($i=1; $i < 8; $i++) { 
                    if(count($value_2) > 0){
                        $day_lib = $this->tanggal->getDayByNum($i);
                        $key = array_search($day_lib, array_column($value_2, 'jd_hari'));

                        if(isset($value_2[$key]['jd_hari'])){
                            if($day_lib==$value_2[$key]['jd_hari']){
                                $note = ($value_2[$key]['jd_keterangan'] != '')?' <br> '.$value_2[$key]['jd_keterangan'].'':'';
                                $end = ($value_2[$key]['jd_jam_selesai'] != '')?' - '.$value_2[$key]['jd_jam_selesai'].'':'';
                                $val_result = $this->tanggal->formatTime($value_2[$key]['jd_jam_mulai']).' s/d '.$this->tanggal->formatTime($value_2[$key]['jd_jam_selesai']).'';

                                $row[strtolower($value_2[$key]['jd_hari'])] = array('time' => $val_result, 'jd_id' => $value_2[$key]['jd_id']);
                            }
                        }
                        
                    }
                    
                }

                $getData[] = $row;
            }
            
        }

        $data = array(
            'nama_bagian' => $this->master->get_string_data('nama_bagian', 'mt_bagian', array('kode_bagian' => $_GET['kode'])),
            'result' => $getData,
        );

        // echo '<pre>';print_r($data);die;
        /*load view index*/
        $this->load->view('kiosk/Kiosk/jadwal_dokter_view', $data);
    }

    public function jadwal_dokter_front() { 
        /*define variable data*/

        /*get data from model*/
        $list = $this->Regon_info_jadwal_dr->get_data();
        $arrData = array();
        /*format data*/
        foreach ($list as $key => $value) {
            $arrData[$value['jd_kode_dokter']][$value['jd_kode_spesialis']][] = $value;
        }

        $data = array();

        foreach ($arrData as $key => $row_list) {

            foreach ($row_list as $key_2 => $value_2) {
                $main_data = $value_2[0];
                $row = array();
                $row['nama_dr'] = $main_data['nama_pegawai'];

                for ($i=1; $i < 8; $i++) { 
                    if(count($value_2) > 0){
                        $day_lib = $this->tanggal->getDayByNum($i);
                        $key = array_search($day_lib, array_column($value_2, 'jd_hari'));

                        if(isset($value_2[$key]['jd_hari'])){
                            if($day_lib==$value_2[$key]['jd_hari']){
                                $note = ($value_2[$key]['jd_keterangan'] != '')?' <br> '.$value_2[$key]['jd_keterangan'].'':'';
                                $end = ($value_2[$key]['jd_jam_selesai'] != '')?' - '.$value_2[$key]['jd_jam_selesai'].'':'';
                                $val_result = $this->tanggal->formatTime($value_2[$key]['jd_jam_mulai']).' s/d '.$this->tanggal->formatTime($value_2[$key]['jd_jam_selesai']).'';

                                $row[strtolower($value_2[$key]['jd_hari'])] = array('time' => $val_result, 'jd_id' => $value_2[$key]['jd_id']);
                            }
                        }
                        
                    }
                    
                }

                $getData[] = $row;
            }
            
        }

        $data = array(
            'nama_bagian' => $this->master->get_string_data('nama_bagian', 'mt_bagian', array('kode_bagian' => $_GET['kode'])),
            'result' => $getData,
        );

        // echo '<pre>';print_r($data);die;
        /*load view index*/
        $this->load->view('kiosk/Kiosk/jadwal_dokter_front_view', $data);
    }

    public function get_data_jadwal_dokter()
    {
        /*get data from model*/
        $list = $this->Regon_info_jadwal_dr->get_datatables();
        $arrData = array();
        /*format data*/
        foreach ($list as $key => $value) {
            $arrData[$value['jd_kode_dokter']][$value['jd_kode_spesialis']][] = $value;
        }

        $data = array();
        $no = $_POST['start'];

        foreach ($arrData as $key => $row_list) {

            foreach ($row_list as $key_2 => $value_2) {
                $main_data = $value_2[0];
                // echo '<pre>';print_r($value_2);die;
                $no++;
                $row = array();
                $row[] = '<div style="text-align: left !important; font-size: 24px">'.$main_data['nama_pegawai'].'</div>';

                for ($i=1; $i < 8; $i++) { 

                    if(count($value_2) > 0){
                        $day_lib = $this->tanggal->getDayByNum($i);
                        $key = array_search($day_lib, array_column($value_2, 'jd_hari'));

                        if(isset($value_2[$key]['jd_hari'])){
                            if($day_lib==$value_2[$key]['jd_hari']){
                                $note = ($value_2[$key]['jd_keterangan'] != '')?' <br> '.$value_2[$key]['jd_keterangan'].'':'';
                                $end = ($value_2[$key]['jd_jam_selesai'] != '')?' - '.$value_2[$key]['jd_jam_selesai'].'':'';
                                $val_result = $this->tanggal->formatTime($value_2[$key]['jd_jam_mulai']).' s/d '.$this->tanggal->formatTime($value_2[$key]['jd_jam_selesai']).'';

                                $row[] = '<div class="center" style="font-weight: bold;background: #0780001f; padding: 4px; cursor: pointer" onclick="getMenu('."'kiosk/Kiosk/form_perjanjian/".$value_2[$key]['jd_id']."/".$value_2[$key]['jd_hari']."'".')"><a href="#">'.$val_result.'</a></div>';
                                /*$row[] = '<div class="center"><span class="btn btn-info btn-sm tooltip-info" data-rel="tooltip" data-placement="bottom" title="" data-original-title="Bottm Info">Bottom</span></div>';*/
                            }else{
                                $row[] = '<div class="center"><i class="fa fa-info-circle orange bigger-150"></i></div>';
                            }
                        }
                        
                    }else{
                        $row[] = '<div class="center"><i class="fa fa-info-circle orange bigger-150"></i></div>';
                    }
                    
                }

                //$row[] = $this->logs->show_logs_record_datatable($row_list);

                $data[] = $row;
            }
            
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Regon_info_jadwal_dr->count_all(),
                        "recordsFiltered" => $this->Regon_info_jadwal_dr->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function form_perjanjian($jd_id) {
        
        $data = array();
        // jadwal by dr spesialis
        $row_jadwal = $this->db->join('mt_bagian','mt_bagian.kode_bagian=tr_jadwal_dokter.jd_kode_spesialis','left')->join('mt_karyawan','mt_karyawan.kode_dokter=tr_jadwal_dokter.jd_kode_dokter','left')->get_where('tr_jadwal_dokter', array('jd_id' => $jd_id) )->row();
        $obj = new stdClass();
        $obj->jd_kode_dokter = $row_jadwal->jd_kode_dokter;
        $obj->jd_kode_spesialis = $row_jadwal->jd_kode_spesialis;
        $jadwal_dr = $this->Regon_info_jadwal_dr->get_jadwal_by_dr_spesialis($obj);
        foreach ($jadwal_dr as $key => $value) {
            $getData[$value['jd_hari']] = $value;
        }

        $data['jadwal_dr_spesialis'] =  $getData;
        $data['value'] =  $row_jadwal;
        
        // echo '<pre>'; print_r($data);die;
        $this->load->view('kiosk/Kiosk/form_perjanjian_view', $data);
    }
    // end modul perjanjian

    // umum asuransi
    public function asum_mod_poli() {
        
        $data = array();
        $data_loket = $this->loket->get_open_loket();

        foreach ($data_loket as $key => $value) {
            # code...
            $kuota = $this->loket->get_sisa_kuota($value);
            if($kuota<0)$kuota=0;
            $data_loket[$key]->kuota = $kuota;
        }

        $data['klinik'] = $data_loket;
        // echo '<pre>';print_r($data);die;

        $this->load->view('kiosk/Kiosk/asum_tab_poli_view', $data);
    }
    // end umum asuransi


    // pasien baru
    public function process_register_pasien(){

        //print_r($_POST);die;
        // form validation
        $this->form_validation->set_rules('nama_pasien', 'Nama Pasien', 'trim|required');
        $this->form_validation->set_rules('nik_pasien', 'NIK', 'trim|required');
        $this->form_validation->set_rules('pob_pasien', 'Tempat Lahir', 'trim|required');
        $this->form_validation->set_rules('dob_pasien', 'Tanggal Lahir', 'trim|required');
        $this->form_validation->set_rules('alamat_pasien', 'Alamat', 'trim|required');
        $this->form_validation->set_rules('gender', 'Jenis Kelamin', 'trim|required');
        $this->form_validation->set_rules('telp_pasien', 'HP', 'trim|required');
        $this->form_validation->set_rules('gelar_nama', 'gelar_nama', 'trim');
       
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
            
            $dob = $this->input->post('dob_pasien');
            $today = date("Y-m-d");
            $diff = date_diff(date_create($dob), date_create($today));
            $age = $diff->format('%y');

            $dataexc = array(
                'nama_pasien' => strtoupper($this->regex->_genRegex($this->form_validation->set_value('nama_pasien'),'RGXQSL')),
                'no_ktp' => $this->regex->_genRegex($this->form_validation->set_value('nik_pasien'),'RGXQSL'),
                'title' => $this->regex->_genRegex($this->form_validation->set_value('gelar_nama'),'RGXQSL'),
                'tgl_lhr' => $this->input->post('dob_pasien'),
                'tempat_lahir' => strtoupper($this->regex->_genRegex($this->form_validation->set_value('pob_pasien'),'RGXQSL')),
                'umur_pasien' => $age,
                'almt_ttp_pasien' => strtoupper($this->regex->_genRegex($this->form_validation->set_value('alamat_pasien'),'RGXQSL')),
                'no_hp' => ($this->regex->_genRegex($this->form_validation->set_value('telp_pasien'),'RGXQSL'))?$this->regex->_genRegex($this->form_validation->set_value('telp_pasien'),'RGXQSL'):'',
                'jen_kelamin' => ($this->regex->_genRegex($this->form_validation->set_value('gender'),'RGXQSL')==1)?'L':'P',
                'no_kartu_bpjs' => isset($_POST['no_kartu_bpjs'])?$this->regex->_genRegex($this->form_validation->set_value('no_kartu_bpjs'),'RGXQSL'):NULL,
                'keterangan' => '',
                'is_active' => 1,
            );

            // cek pasien by ni
            $mr = $this->db->get_where('mt_master_pasien', array('no_ktp' => $dataexc['no_ktp'], 'tgl_lhr' => $dataexc['tgl_lhr']))->row();

            if(empty($mr)){

                /*get max no mr*/
                $cekMaxMr = $this->db->query("select TOP 1 no_mr from mt_master_pasien where LEN(no_mr)=8 order by no_mr desc ")->row();
                    
                $mrID = $cekMaxMr->no_mr + 1;
                
                $panjang_mr=strlen($mrID);
                $sisa_panjang=8-$panjang_mr;
                $tambah_nol="";
                
                for ($i=1;$i<=$sisa_panjang;$i++){
                    $tambah_nol=$tambah_nol."0";
                }
    
                $mrID=$tambah_nol.$mrID;

                $dataexc['no_mr'] = $mrID;
                $dataexc['create_date'] = date('Y-m-d H:i:s');
                $dataexc['no_mr_barcode'] = $mrID;
                $dataexc['sirs_v1'] = 2;

                // print_r($dataexc);die;

                /* save pasien*/
                $newId = $this->Input_pasien_baru->save('mt_master_pasien', $dataexc);

                /*save logs*/
                $this->logs->save('mt_master_pasien', $newId, 'insert new record on '.$this->title.' module', json_encode($dataexc),'id_mt_master_pasien');

                if ($this->db->trans_status() === FALSE)
                {
                    $this->db->trans_rollback();
                    echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
                }
                else
                {
                    $this->db->trans_commit();
                    echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'nama_pasien' => $dataexc['nama_pasien'], 'no_mr' => $dataexc['no_mr'], 'no_hp' => $dataexc['no_hp'], 'jen_kelamin' => $dataexc['jen_kelamin'], 'almt_ttp_pasien' => $dataexc['almt_ttp_pasien']));
                }


            } else {       

                echo json_encode(array('status' => 301, 'message' => 'NIK anda sudah pernah terdaftar dengan Nomor Rekam Medis "'.$mr->no_mr.'"'));
               
            }
        
        }

    }

    public function process_register_penunjang(){

        // print_r($_POST);die;
        // form validation
        $this->form_validation->set_rules('no_mr', 'No MR Pasien', 'trim|required', array('required' => 'No MR Pasien tidak ditemukan') );
        $this->form_validation->set_rules('nama_pasien', 'Nama Pasien', 'trim');
        $this->form_validation->set_rules('kode_kelompok_hidden', 'Kode Kelompok', 'trim');
        $this->form_validation->set_rules('kode_perusahaan_hidden', 'Kode Kelompok', 'trim');
        $this->form_validation->set_rules('umur_saat_pelayanan_hidden', 'Umur', 'trim');
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
            $no_mr = $this->regex->_genRegex($this->form_validation->set_value('no_mr'),'RGXQSL');
            $kode_perusahaan = $this->regex->_genRegex($this->form_validation->set_value('kode_perusahaan_hidden'),'RGXINT');
            $kode_kelompok =  $this->regex->_genRegex($this->form_validation->set_value('kode_kelompok_hidden'),'RGXINT');
            $kode_dokter = $this->regex->_genRegex(0,'RGXINT');
            $kode_bagian_masuk = $this->regex->_genRegex($this->form_validation->set_value('pm_tujuan'),'RGXQSL');
            $umur_saat_pelayanan = $this->regex->_genRegex($this->form_validation->set_value('umur_saat_pelayanan_hidden'),'RGXINT');

            $tipe_pasien = ($kode_perusahaan==120) ? 'bpjs' : 'umum';

            $data_registrasi = $this->daftar_pasien->daftar_registrasi($title,$no_mr, $kode_perusahaan, $kode_kelompok, $kode_dokter, $kode_bagian_masuk, $umur_saat_pelayanan,'');
            $no_registrasi = $data_registrasi['no_registrasi'];
            $no_kunjungan = $data_registrasi['no_kunjungan'];
              
            /*insert penunjang medis*/
            $kode_penunjang = $this->master->get_max_number('pm_tc_penunjang', 'kode_penunjang');
            $no_antrian = $this->master->get_no_antrian_pm($this->regex->_genRegex($this->form_validation->set_value('pm_tujuan'),'RGXQSL'));
            $klas = 16;
            $data_pm_tc_penunjang = array(
                'kode_penunjang' => $kode_penunjang,
                'tgl_daftar' => date('Y-m-d H:i:s'),
                'kode_bagian' => $this->regex->_genRegex($this->form_validation->set_value('pm_tujuan'),'RGXQSL'),
                'no_kunjungan' => $no_kunjungan,
                'no_antrian' => $no_antrian,
                'kode_klas' => $klas,
                'petugas_input' => 0,
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

                // get detail data
                $dt = $this->Reg_klinik->get_by_id($no_registrasi);
                $this->print_bukti_registrasi($no_registrasi, $no_antrian, $tipe_pasien);

                // print tracer
                $data_tracer = [
                    'no_mr' => $no_mr,
                    'nama_pasien' => $_POST['nama_pasien'],
                    'unit' => $this->master->get_string_data('nama_bagian', 'mt_bagian', array('kode_bagian' => $kode_bagian_masuk)),
                ];
                
                if($kode_bagian_masuk == '050101'){
                    $tracer = $this->print_escpos->print_barcode($data_tracer);
                    if( $tracer == 1 ) {
                        $this->db->update('tc_registrasi', array('print_tracer' => 'Y'), array('no_registrasi' => $no_registrasi) );
                    }
                }

                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'no_mr' => $_POST['no_mr'], 'no_registrasi' => $no_registrasi, 'no_antrian' => $no_antrian, 'tipe' => $tipe_pasien, 'type_pelayanan' => 'Penunjang Medis'));

            }
            
        }

    }

    function print_bukti_registrasi($no_registrasi, $no_antrian, $tipe_pasien){
        $registrasi = $this->Reg_klinik->get_by_id($no_registrasi);
        // echo '<pre>';print_r($registrasi);die;
        $tracer = $this->print_escpos->print_bukti_registrasi($registrasi, $no_antrian, $tipe_pasien);
        // echo '<pre>';print_r($tracer);die;
        if( $tracer == 1 ) {
            $this->db->update('tc_registrasi', array('print_tracer' => 'Y'), array('no_registrasi' => $no_registrasi) );        
        }
        return true;
    }

    // bpjs
    public function bpjs_mod_poli() {
        
        $data = array();
        $data_loket = $this->loket->get_open_loket();

        foreach ($data_loket as $key => $value) {
            # code...
            $kuota = $this->loket->get_sisa_kuota($value);
            if($kuota<0)$kuota=0;
            $data_loket[$key]->kuota = $kuota;
        }

        $data['klinik'] = $data_loket;
        // echo '<pre>';print_r($data);die;

        $this->load->view('kiosk/Kiosk/bpjs_tab_poli_view', $data);
    }


    
    

}

/* End of file empty_module.php */
/* Location: ./application/modules/empty_module/controllers/empty_module.php */

