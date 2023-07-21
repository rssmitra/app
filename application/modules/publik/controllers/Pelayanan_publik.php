<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pelayanan_publik extends MX_Controller {

    function __construct() {
        parent::__construct();
        // load model
        $this->load->model('antrian/antrian_model'); 
        $this->load->model('registration/Reg_pasien_model', 'Reg_pasien');
        $this->load->model('registration/Reg_klinik_model', 'Reg_klinik');
        $this->load->model('antrian/loket_model','loket');
        $this->load->model('display_loket/main_model','Main');
        $this->load->model('ws_bpjs/Ws_index_model', 'Ws_index');
        $this->load->model('ws/AntrianOnlineModel', 'AntrianOnline');
        $this->load->model('information/Regon_info_jadwal_dr_model', 'Regon_info_jadwal_dr');
        $this->load->model('registration/Input_pasien_baru_model', 'Input_pasien_baru');
        $this->load->model('Pelayanan_publik_model','Pelayanan_publik');
        
        // load library
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
 
    }

    public function index() {
        $data = array();
        $data['app'] = $this->db->get_where('tmp_profile_app', array('id' => 1))->row();
        $this->load->view('Pelayanan_publik/index', $data);
    }

    public function registrasi_rj() {
        $this->load->view('Pelayanan_publik/form_registrasi');
    }

    public function riwayat_kunjungan() {
        $this->load->view('Pelayanan_publik/form_search_kunjungan');
    }

    public function antrian_poli() {
        $this->load->view('Pelayanan_publik/form_antrian_poli');
    }

    public function jadwal_dokter() {
        $this->load->view('Pelayanan_publik/form_jadwal_dokter');
    }

    public function pasien_baru() {
        $this->load->view('Pelayanan_publik/form_create_pasien');
    }

    public function get_data_antrian_pasien(){
        $list = $this->Pelayanan_publik->get_data_antrian_pasien();
        echo json_encode($list);
    }

    public function get_data_jadwal_dokter()
    {
        /*get data from model*/
        $list = $this->Regon_info_jadwal_dr->get_data();
        
        $arrData = array();
        /*format data*/
        foreach ($list as $key => $value) {
            $arrData[$value['nama_pegawai']][] = $value;
        }

        // echo '<pre>';print_r($arrData);die;
        $data = array(
            'jadwal_dokter' => $arrData,
        );
        $html = $this->load->view('Pelayanan_publik/jadwal_dokter_view', $data, true);
        $output = array("html" => $html);
        //output to json format
        echo json_encode($output);
    }

    public function konfirmasi_kunjungan($no_kunjungan) {
        $query = $this->Pelayanan_publik->get_data_kunjungan($no_kunjungan);
        // echo '<pre>'; print_r($query);die;
        $data = array(
            'result' => $query,
        );
        $this->load->view('Pelayanan_publik/form_konfirmasi', $data);
    }

    public function proses_registrasi(){

        // form validation
        $this->form_validation->set_rules('tgl_registrasi', 'Tanggal Registrasi', 'trim|required');
        $this->form_validation->set_rules('reg_klinik_rajal', 'Poli/Klinik', 'trim|required');
        $this->form_validation->set_rules('reg_dokter_rajal', 'Dokter', 'trim|required');
        $this->form_validation->set_rules('noMrHidden', 'No MR', 'trim|required');
        $this->form_validation->set_rules('umur_saat_pelayanan_hidden', 'Umur', 'trim');
        
        if(isset($_POST['jenis_pasien']) && $_POST['jenis_pasien']=='asuransi'){
            $this->form_validation->set_rules('kode_perusahaan_hidden', 'Asuransi', 'trim|required', array('required' => 'Silahkan pilih asuransi anda'));
        }

        if(isset($_POST['jenis_pasien']) && $_POST['jenis_pasien']=='bpjs'){
            $this->form_validation->set_rules('noKartuBpjs', 'No Kartu BPJS', 'trim|required', array('required' => 'Data kepesertaan BPJS anda tidak ditemukan'));
            $this->form_validation->set_rules('noRujukan', 'No Rujukan', 'trim|required');
        }

        // set message error
        $this->form_validation->set_message('required', "Silahkan isi field \"%s\"");        

        if ($this->form_validation->run() == FALSE)
        {
            $this->form_validation->set_error_delimiters('<div style="color:white"><i>', '</i></div>');
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            /*execution*/
            $this->db->trans_begin();
            // validasi tanggal kunjungan
            $max_date = date('Y-m-d', strtotime('+1 day'));
            if($max_date < $_POST['tgl_registrasi']){
                echo json_encode(array('status' => 301, 'message' => 'Anda belum bisa melakukan pendaftaran pada tanggal '.$_POST['tgl_registrasi'].''));
                exit;
            }
            
            if($_POST['is_expired'] == 1){
                echo json_encode(array('status' => 301, 'message' => 'Udah dikasih informasi rujukan expired masih aja dilanjutin!'));
                exit;
            }

            $datapoli = array();
            $title = $this->title;
            $no_mr = $this->regex->_genRegex($this->form_validation->set_value('noMrHidden'),'RGXQSL');
            $kode_perusahaan = ($_POST['jenis_pasien'] == 'bpjs') ? 120 : $this->regex->_genRegex($this->form_validation->set_value('kode_perusahaan_hidden'),'RGXINT');
            $kode_kelompok =  ($_POST['jenis_pasien'] == 'umum') ? 1 : 3;
            $kode_dokter = $this->regex->_genRegex($this->form_validation->set_value('reg_dokter_rajal'),'RGXINT');
            $kode_bagian_masuk = $this->regex->_genRegex($this->form_validation->set_value('reg_klinik_rajal'),'RGXQSL');
            $umur_saat_pelayanan = $this->regex->_genRegex($this->form_validation->set_value('umur_saat_pelayanan_hidden'),'RGXINT');
            $no_sep = '111111';
            $jd_id =  $this->input->post('jd_id');
            $kode_faskes =  ($this->input->post('kode_faskes_hidden'))?$this->input->post('kode_faskes_hidden'):'';
            $tgl_registrasi = $this->input->post('tgl_registrasi').' '.date('H:i:s');

            // cek register by date
            $cek_kunjungan_by_date = $this->Pelayanan_publik->cek_kunjungan_by_date($_POST['tgl_registrasi'], $no_mr);
            if($cek_kunjungan_by_date->num_rows() > 0){
                $obj = $cek_kunjungan_by_date->row();
                echo json_encode(array('status' => 202, 'message' => 'Anda sudah pernah terdaftar pada tanggal yang sama, yaitu tanggal <b>'.$obj->tgl_jam_poli.'</b> tujuan ke <b>'.ucwords($obj->nama_bagian).'</b> ', 'no_kunjungan' => $obj->no_kunjungan));
                exit;
            }

            /*save tc_registrasi*/
            $data_registrasi = $this->daftar_pasien->daftar_registrasi($title,$no_mr, $kode_perusahaan, $kode_kelompok, $kode_dokter, $kode_bagian_masuk, $umur_saat_pelayanan,$no_sep,$jd_id, $kode_faskes, $tgl_registrasi);
            $no_registrasi = $data_registrasi['no_registrasi'];
            $no_kunjungan = $data_registrasi['no_kunjungan'];
            
            /*insert pl tc poli*/
            $kode_poli = $this->master->get_max_number('pl_tc_poli', 'kode_poli');
            $tipe_antrian = ($kode_perusahaan != 120) ? 'umum' : 'bpjs';
            $no_antrian = $this->master->get_no_antrian_poli($this->form_validation->set_value('reg_klinik_rajal'),$this->form_validation->set_value('reg_dokter_rajal'), $tipe_antrian, $tgl_registrasi);
            
            $datapoli['kode_poli'] = $kode_poli;
            $datapoli['no_kunjungan'] = $no_kunjungan;
            $datapoli['kode_bagian'] = $this->regex->_genRegex($this->form_validation->set_value('reg_klinik_rajal'),'RGXQSL');
            $datapoli['tgl_jam_poli'] = $tgl_registrasi;
            $datapoli['kode_dokter'] = $this->regex->_genRegex($this->form_validation->set_value('reg_dokter_rajal'),'RGXINT');
            $datapoli['flag_antrian'] = $tipe_antrian;
            $datapoli['no_antrian'] = $no_antrian;
            $datapoli['nama_pasien'] = $_POST['nama_pasien_hidden'];
            $datapoli['tipe_daftar'] = 'online_web';
            
            //print_r($datapoli);die;
            /*save poli*/
            $this->Reg_klinik->save('pl_tc_poli', $datapoli);

            /*save logs*/
            $this->logs->save('pl_tc_poli', $datapoli['kode_poli'], 'insert new record on '.$this->title.' module', json_encode($datapoli),'kode_poli');
            
            /*jika terdapat id_tc_pesanan maka update tgl_masuk pada table tc_pesanan*/
            $get_data_perjanjian = $this->db->get_where('tc_pesanan', array('no_mr' => $no_mr, 'CAST(tgl_pesanan as DATE) = ' => $this->input->post('tgl_registrasi'), 'kode_dokter' => $kode_dokter, 'no_poli' => $kode_poli) )->row();

            if( isset($get_data_perjanjian->id_tc_pesanan) ){
                
                $this->db->update('tc_pesanan', array('tgl_masuk' => $tgl_registrasi, 'nopesertabpjs' => $_POST['noKartuBpjs'] ), array('id_tc_pesanan' => $get_data_perjanjian->id_tc_pesanan ) );

                // update kuota dokter used
                $this->logs->update_status_kuota(array('kode_dokter' => $datapoli['kode_dokter'], 'kode_spesialis' => $datapoli['kode_bagian'], 'tanggal' => date('Y-m-d'), 'keterangan' => null, 'flag' => 'perjanjian', 'status' => NULL ), 1);
                $kode_booking = $get_data_perjanjian->kode_perjanjian;

            }

            // update no kartu bpjs
            $this->db->where('no_mr', $no_mr)->update('mt_master_pasien', array('no_kartu_bpjs' => $_POST['noKartuBpjs'], 'no_ktp' => $_POST['nikPasien']));

            $config = array(
                'no_registrasi' => $no_registrasi,
                'kode_booking' => isset($kode_booking) ? $kode_booking : $no_registrasi,
                'tgl_registrasi' => $_POST['tgl_registrasi'],
                'no_antrian' => $no_antrian,
                'no_mr' => $no_mr,
                'jeniskunjungan' => 3,
                'norujukan' => isset($_POST['noRujukan'])?$_POST['noRujukan']:"",
            );

            $antrol = $this->processAntrol($config);

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
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'no_mr' => $no_mr, 'no_registrasi' => $no_registrasi, 'dokter' => $dt->nama_pegawai, 'poli' => $dt->nama_bagian, 'nama_pasien' => $dt->nama_pasien, 'no_kunjungan' => $no_kunjungan, 'no_antrian' => $datapoli['no_antrian'], 'no_antrian_poli' => $no_antrian, 'antrol' => $antrol, 'tgl_registrasi' => $_POST['tgl_registrasi'], 'jam_praktek' => $antrol['jampraktek'] ));
            }
        }
    }

    public function getKuotaDokter($kode_dokter='',$kode_spesialis='', $tanggal=''){
        $getData = $this->Pelayanan_publik->getKuotaDokter($kode_dokter, $kode_spesialis, $tanggal);
        echo json_encode($getData);
    }

    public function processAntrol($params){
        // echo '<pre>'; print_r($_POST);die;
        // estimasi dilayani
        $jam_mulai_praktek = $this->tanggal->formatFullTime($_POST['jam_praktek_mulai']);
        $jam_selesai_praktek = $this->tanggal->formatFullTime($_POST['jam_praktek_selesai']);
        $date = date_create($this->tanggal->formatDateTimeToSqlDate($params['tgl_registrasi']).' '.$jam_mulai_praktek );
        
        $est_hour = ceil($params['no_antrian'] / 12);
        $estimasi = ($params['no_antrian'] <= 12) ? 1 : $est_hour; 
        
        // estimasi dilayani
        date_add($date, date_interval_create_from_date_string('+'.$estimasi.' hours'));
        $estimasidilayani = date_format($date, 'Y-m-d H:i:s');
        $milisecond = strtotime($estimasidilayani) * 1000;
        
        // add antrian
        $post_antrol = array(
            "kodebooking" => $params['kode_booking'],
            "jenispasien" => "NON JKN",
            "nomorkartu" => $_POST['noKartuBpjs'],
            "nik" => $_POST['nikPasien'],
            "nohp" => $_POST['hpPasien'],
            "kodepoli" => $_POST['kode_poli_bpjs'],
            "namapoli" => $_POST['reg_klinik_rajal_txt'],
            "pasienbaru" => 0,
            "norm" => $params['no_mr'],
            "tanggalperiksa" => $this->tanggal->formatDateBPJS($params['tgl_registrasi']),
            "kodedokter" => $_POST['kode_dokter_bpjs'],
            "namadokter" => $_POST['reg_dokter_rajal_txt'],
            "jampraktek" => $this->tanggal->formatTime($_POST['jam_praktek_mulai']).'-'.$this->tanggal->formatTime($_POST['jam_praktek_selesai']),
            "jeniskunjungan" => $params['jeniskunjungan'],
            "nomorreferensi" => ($params['jeniskunjungan'] == 1) ? $params['norujukan'] : "",
            "nomorantrean" => $_POST['kode_poli_bpjs'].'-'.$params['no_antrian'],
            "angkaantrean" => $params['no_antrian'],
            "estimasidilayani" => $milisecond,
            "sisakuotajkn" => $_POST['sisa_kuota'],
            "kuotajkn" => $_POST['kuotadr'],
            "sisakuotanonjkn" => $_POST['sisa_kuota'],
            "kuotanonjkn" => $_POST['kuotadr'],
            "keterangan" => "Silahkan tensi dengan perawat"
        );
        // echo '<pre>'; print_r($post_antrol); die;
        // add antrian lainnya
        $this->AntrianOnline->addAntrianOnsite($post_antrol);

        // update kodebooking
        $this->db->where('no_registrasi', $params['no_registrasi'])->update('tc_registrasi', array('kodebookingantrol' => $params['kode_booking']) );

        // update task antrian online
        $waktukirim = strtotime($params['tgl_registrasi']) * 1000;
        $exc_antrol = $this->AntrianOnline->postDataWs('antrean/updatewaktu', array('kodebooking' => $params['kode_booking'], 'taskid' => 3, 'waktu' => $waktukirim));

        return $post_antrol;
    }

    // pasien baru
    public function process_register_pasien(){

        //print_r($_POST);die;
        // form validation
        $this->form_validation->set_rules('nama_pasien', 'Nama Pasien', 'trim|required');
        $this->form_validation->set_rules('nik_pasien', 'NIK', 'trim|required|min_length[16]|max_length[16]|is_unique[mt_master_pasien.no_ktp]', array('is_unique' => 'NIK anda sudah pernah terdaftar', 'min_length' => 'NIK harus berisi 16 angka', 'max_length' => 'Maksimal NIK berisi 16 angka'));
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
                'keterangan' => 'registrasi pasien via online',
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

    public function checkin($no_registrasi, $no_mr, $flag){
        
        $detail_data = $this->Reg_pasien->get_detail_resume_medis($no_registrasi);
        $data_tracer = [
            'no_mr' => $no_mr,
            'result' => $detail_data,
        ];
        if($flag == 'checkin'){
            $tracer = $this->print_escpos->print_direct($data_tracer);
            $status_tracer = ( $tracer == 1 ) ? 'Y' : 'N' ;
            $konfirm_fp = ($detail_data['registrasi']->kode_perusahaan != 120) ? '1' : null;
            $this->db->update('tc_registrasi', array('print_tracer' => $status_tracer, 'konfirm_fp' => $konfirm_fp, 'status_checkin' => 1, 'checkin_date' => date('Y-m-d H:i:s')), array('no_registrasi' => $no_registrasi) );
        }else{
            // cancel
            $this->db->update('tc_registrasi', array('status_checkin' => 1, 'checkin_date' => date('Y-m-d H:i:s'), 'status_batal' => 1), array('no_registrasi' => $no_registrasi) );
            $this->db->update('pl_tc_poli', array('status_batal' => 1), array('no_kunjungan' => $detail_data['registrasi']->no_kunjungan) );
            $this->db->update('tc_kunjungan', array('status_batal' => 1), array('no_kunjungan' => $detail_data['registrasi']->no_kunjungan) );
        }
        

        echo json_encode( array('status' => 200, 'message' => 'Proses berhasil') );
        
    }

    public function getKlinikFromJadwal($day='', $date='')
	{
		
		$query = "select a.jd_kode_spesialis as kode_bagian,c.nama_bagian
					from tr_jadwal_dokter a
					left join mt_bagian c on c.kode_bagian=a.jd_kode_spesialis
					where a.jd_hari='".$day."' or (kode_bagian = '012801' or kode_bagian='012901')
					group by  a.jd_kode_spesialis,c.nama_bagian";
		$exc = $this->db->query($query);
		// echo $this->db->last_query(); die;
        echo json_encode($exc->result());
	}

    public function search_pasien_public() { 
        
		$this->load->model('registration/Reg_pasien_model', 'Reg_pasien');
        /*define variable data*/
        $keyword = $this->input->get('keyword');

        if(isset($_GET['search_by'])){
			$search_by = array($_GET['search_by']);
		}else{
			$search_by = array('no_mr','nama_pasien','no_ktp','no_kartu_bpjs');
		}
		
        /*return search pasien*/
        $data_pasien = $this->Reg_pasien->search_pasien_by_keyword( $keyword, $search_by ); 
        $no_mr = isset( $data_pasien[0]->no_mr ) ? $data_pasien[0]->no_mr : 0 ;
        $log_kunjungan = $this->Reg_pasien->cek_riwayat_kunjungan_pasien_by_current_day( $no_mr );
        // echo '<pre>'; print_r($log_kunjungan);die;
        $data = array(
            'count' => count($data_pasien),
            'result' => $data_pasien,
            'count_kunjungan' => count($log_kunjungan),
            'log_kunjungan' => $log_kunjungan,
        );
        echo json_encode( $data );
    }

    

}

/* End of file empty_module.php */
/* Location: ./application/modules/empty_module/controllers/empty_module.php */

