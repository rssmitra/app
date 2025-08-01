
<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');


class Reg_klinik extends MX_Controller {

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
        $this->load->model('Reg_pasien_rujukan_model', 'Reg_pasien_rujukan');
        $this->load->model('ws_bpjs/Ws_index_model', 'Ws_index');
        $this->load->model('ws/AntrianOnlineModel', 'AntrianOnline');
        $this->load->model('counter/Counter_model', 'Counter');

        /*load library*/

        $this->load->library('Form_validation');
        $this->load->library('Daftar_pasien');
        $this->load->library('Print_direct');
        $this->load->library('Print_escpos');  
        $this->load->library('tarif');      
        
        /*enable profiler*/
        
        $this->output->enable_profiler(false);
        
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->
        get_menu_by_class(get_class($this))->name : 'Title';

        $this->kode_faskes = '0112R034';

        $this->load->module('casemix/Csm_billing_pasien');
        $this->cbpModule = new Csm_billing_pasien;

        $this->load->module('Templates/References');
        $this->reff = new References;
    
    }

    public function index() { 
        
        /*define variable data*/

        $data = array(
            
            'title' => $this->title,
            
            'breadcrumbs' => $this->breadcrumbs->show(),

            /*current counter number*/
            'current_counter_number' => $this->Counter->get_current_counter_number(),
            
            //'counter_info' => $this->Counter->get_counter_info(),
        
        );
        
        /*booking online*/
        if(isset($_GET['kode'])){
            $data['kode_booking'] = $_GET['kode'];
            $value = new stdClass;
            $value->is_active = 'online';
            $data['value'] = $value;
        }

        /*jika dari pesanan*/
        if(isset($_GET['idp'])){
            $data['no_mr'] = $_GET['no_mr'];
            $data['id_tc_pesanan'] = $_GET['idp'];
            $data['kode_dokter'] = $_GET['kode_dokter'];
            $data['poli'] = $_GET['poli'];
            $data['kode_perjanjian'] = $_GET['kode_perjanjian'];
            $data['data_pesanan'] = $this->Reg_pasien->get_pesanan_pasien($_GET['idp']);
            $value = new stdClass;
            $value->is_active = 'onsite';
            $data['value'] = $value;
        }

         /*jika dari rujukan*/
        if(isset($_GET['kode_rujukan'])){
            $data['kode_rujukan'] = $_GET['kode_rujukan'];
            $data['no_registrasi'] = $_GET['no_reg'];
            $data['no_mr'] = $_GET['mr'];
            $data['data_rujukan'] = $this->Reg_pasien_rujukan->get_by_id($_GET['kode_rujukan']);
            $value = new stdClass;
            $value->is_active = 'onsite';
            $data['value'] = $value;
        }

        /*daftar dari pm */
        if(isset($_GET['pm'])){
            $data['pm'] = $_GET['pm'];
            $value = new stdClass;
            $value->is_active = 'onsite';
            $data['value'] = $value;
        }

        /*pasien baru*/
        if(isset($_GET['is_new'])){
            $data['no_mr'] = $_GET['mr'];
            $data['is_new'] = $_GET['is_new'];
        }

        /*update sep*/
        if(isset($_GET['update_sep'])){
            $data['no_mr'] = $_GET['mr'];
        }
        // echo'<pre>';print_r($this->session->all_userdata());die;
        /*load view index*/
        
        $this->load->view('Reg_klinik/index', $data);
    
    }

    public function print_bukti_pendaftaran_pasien(){
        
        $data = [
            'result' => $this->Reg_pasien->get_detail_resume_medis($no_registrasi),
            'no_registrasi' => $no_registrasi,
            'registrasi' => $this->db->get_where('tc_registrasi', array('no_registrasi' => $_GET['no_reg']))->row(),
        ];

        // echo '<pre>'; print_r($data);die;
        $this->load->view('Reg_klinik/print_bukti_pendaftaran_pasien_view', $data);

    }

    public function print_bukti_pendaftaran_pasien_small(){
        
        
        $data = [
            'result' => $this->Reg_pasien->get_detail_resume_medis($_GET['no_reg']),
            'no_registrasi' => $_GET['no_reg'],
            'registrasi' => $this->db->get_where('tc_registrasi', array('no_registrasi' => $_GET['no_reg']))->row(),
        ];

        $userDob = isset($data['result']['registrasi']->tgl_lhr)?$data['result']['registrasi']->tgl_lhr:'1990-01-01';
 
        //Create a DateTime object using the user's date of birth.
        $dob = new DateTime($userDob);
     
        //We need to compare the user's date of birth with today's date.
        $now = new DateTime();

        //Calculate the time difference between the two dates.
        $difference = $now->diff($dob);

        //Get the difference in years, as we are looking for the user's age.
        $umur = $difference->format('%y');

        $data['umur'] = $umur;


        // echo '<pre>'; print_r($data);die;
        $this->load->view('Reg_klinik/print_bukti_pendaftaran_pasien_view_small', $data);

    }

    public function search_pasien() { 
        
        /*define variable data*/
        
        $keyword = $this->input->get('keyword');
        $tgl_kunjungan = isset($_GET['tgl_kunjungan']) ? $_GET['tgl_kunjungan'] : date('Y-m-d');


        /*return search pasien*/

        $data_pasien = $this->Reg_pasien->search_pasien_by_keyword( $keyword, array('no_mr','nama_pasien','no_ktp', 'no_kartu_bpjs') ); 
        // echo $this->db->last_query(); die;
        
        $no_mr = isset( $data_pasien[0]->no_mr ) ? $data_pasien[0]->no_mr : 0 ;
        
        $data_transaksi_pending = $this->Reg_pasien->cek_status_pasien( $no_mr );
        // cek pasien bpjs apakah lebih dari 31 hari
        $last_visit = $this->Reg_pasien->cek_last_visit( $no_mr );
        // cek konsul internal
        $data_konsul_internal = $this->Reg_pasien->cek_konsul_internal( $no_mr, $tgl_kunjungan );
        // echo '<pre>'; print_r($data_konsul_internal);die;

        $data = array(

            'count' => count($data_pasien),
            'result' => $data_pasien,
            'count_pending' => count($data_transaksi_pending),
            'pending' => $data_transaksi_pending,
            'last_visit' => $last_visit,
            'konsul_internal' => $data_konsul_internal,

        );
        
        echo json_encode( $data );
    
    }

    public function search_pasien_by_mr() { 
        
        /*define variable data*/
        
        $keyword = $this->input->get('keyword');

        /*return search pasien*/
        if( ! $data_pasien = $this->cache->get('data_pasien_by_mr_'.$keyword.'_'.date('Y-m-d').'') )
		{
			$data_pasien = $this->Reg_pasien->search_pasien_by_keyword( $keyword, array('no_mr') ); 
			$this->cache->save('data_pasien_by_mr_'.$keyword.'_'.date('Y-m-d').'', $data_pasien, 3600);
		}

        // echo '<pre>'; print_r($data_pasien);die;
        $no_mr = isset( $data_pasien[0]->no_mr ) ? $data_pasien[0]->no_mr : 0 ;
        $data_transaksi_pending = $this->Reg_pasien->cek_status_pasien( $no_mr );

        $data = array(
            'count' => count($data_pasien),
            'result' => $data_pasien,
            'count_pending' => count($data_transaksi_pending),
            'pending' => $data_transaksi_pending,
        );
        
        echo json_encode( $data );
    
    }

    
    /*function for view data only*/
    
    public function form_sep($noMr='')
    
    {
        
        /*breadcrumbs for view*/
        $this->breadcrumbs->push('View '.strtolower($this->title).'', 'Reg_klinik/'.strtolower(
            get_class($this)).'/'.__FUNCTION__.'/'.$noMr);
        
        /*define data variabel*/
        
        $data['title'] = $this->title;
        
        $data_pasien = $this->Reg_pasien->search_pasien_by_keyword( $noMr, array('no_mr') );

        $data_pasien_bpjs = $this->Reg_pasien->get_data_pasien_bpjs( $data_pasien );
        /*echo '<pre>'; print_r($data_pasien_bpjs);*/

        $data['value'] = array('pasien' => array('data' => $data_pasien, 'bpjs' => $data_pasien_bpjs) );
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        
        /*load form view*/
        
        $this->load->view('Reg_klinik/form_sep', $data);
    
    }

    public function form_create_sep()
    
    {
        
        $this->load->view('Reg_klinik/form_create_sep');
    
    }

    public function rujuk_klinik($no_reg='',$bag_asal='',$type_asal='',$klas='')
    {
        /*get value by no_kunj*/
        $data_reg = $this->db->get_where('tc_registrasi', ['no_registrasi' => $no_reg])->row();
        $data['value'] = $data_reg;
        $data['bagian_asal'] = $bag_asal;
        $data['no_reg'] = $no_reg;
        $data['type'] = $type_asal;
        $data['klas'] = $klas;
        /*load form view*/
        $this->load->view('Reg_klinik/form_rajal', $data);
    }

    public function process_sep_success($no_sep)
    {
        $data = array();
        $data['no_sep'] = $no_sep;
        /*load form view*/
        $this->load->view('Reg_klinik/form_sep_success', $data);
    }
    

    public function show_modul($modul_id, $id_tc_pesanan='') { 
        
        $data = array();
        $data['id_tc_pesanan'] = $id_tc_pesanan;

        switch ($modul_id) {
            case 1:
                $view_modul = 'Reg_klinik/form_rajal';
                break;

            case 2:
                $view_modul = 'Reg_klinik/form_ranap';
                break;

            case 3:
                $view_modul = 'Reg_klinik/form_pm';
                break;

            case 4:
                $view_modul = 'Reg_klinik/form_igd';
                break;

            case 5:
                $view_modul = 'Reg_klinik/form_mcu';
                break;

            case 6:
                $view_modul = 'Reg_klinik/form_odc';
                break;

            case 7:
                $view_modul = 'Reg_klinik/form_paket_bedah';
                break;

            case 8:
                $view_modul = 'Reg_klinik/form_create_sep';
                break;
            
            default:
                $view_modul = 'Reg_klinik/index';
                break;
        }

        $this->load->view($view_modul, $data);
    
    }

    public function process(){

        // print_r($_POST);die;
        // form validation
        $this->form_validation->set_rules('tgl_registrasi', 'Tanggal Registrasi', 'trim|required');
        $this->form_validation->set_rules('reg_klinik_rajal', 'Poli/Klinik', 'trim|required');
        $this->form_validation->set_rules('reg_dokter_rajal', 'Dokter', 'trim|required');
        $this->form_validation->set_rules('noMrHidden', 'Dokter', 'trim|required');
        $this->form_validation->set_rules('kode_perusahaan_hidden', 'Kode Perusahaan', 'trim');
        $this->form_validation->set_rules('kode_kelompok_hidden', 'Kode Perusahaan', 'trim');
        $this->form_validation->set_rules('umur_saat_pelayanan_hidden', 'Umur', 'trim');
        $this->form_validation->set_rules('nikPasien', 'NIK Pasien', 'trim|required|min_length[16]|max_length[16]', array('min_length' => 'NIK pasien salah (kurang dari 16 digit)', 'max_length' => 'NIK pasien salah (lebih dari 16 digit)'));

        if(isset($_POST['kode_perusahaan_hidden']) && $_POST['kode_perusahaan_hidden']==120){
            $this->form_validation->set_rules('noSep', 'Nomor SEP', 'trim|required');
            $this->form_validation->set_rules('noKartuBpjs', 'No Kartu BPJS', 'trim|required|min_length[13]|max_length[13]', array('min_length' => 'No Kartu BPJS pasien salah (kurang dari 13 digit)', 'max_length' => 'No Kartu BPJS pasien salah (lebih dari 13 digit)'));
            $this->form_validation->set_rules('jeniskunjunganbpjs', 'Jenis Kunjungan', 'trim|required');
            $this->form_validation->set_rules('norujukanbpjs', 'Nomor Rujukan', 'trim|required|min_length[19]|max_length[19]', array('min_length' => 'Nomor Rujukan harus 19 karakter', 'max_length' => 'Nomor Rujukan harus 19 karakter' ));
        }

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
            $datapoli = array();

            $title = $this->title;
            $no_mr = $this->regex->_genRegex($this->form_validation->set_value('noMrHidden'),'RGXQSL');
            $kode_perusahaan = $this->regex->_genRegex($this->form_validation->set_value('kode_perusahaan_hidden'),'RGXINT');
            $kode_kelompok =  $this->regex->_genRegex($this->form_validation->set_value('kode_kelompok_hidden'),'RGXINT');
            $kode_dokter = $this->regex->_genRegex($this->form_validation->set_value('reg_dokter_rajal'),'RGXINT');
            $kode_bagian_masuk = $this->regex->_genRegex($this->form_validation->set_value('reg_klinik_rajal'),'RGXQSL');
            $umur_saat_pelayanan = $this->regex->_genRegex($this->form_validation->set_value('umur_saat_pelayanan_hidden'),'RGXINT');
            $no_sep = $this->regex->_genRegex($this->form_validation->set_value('noSep'),'RGXALNUM');
            $jd_id =  $this->input->post('jd_id');
            $kode_faskes =  ($this->input->post('kode_faskes_hidden'))?$this->input->post('kode_faskes_hidden'):'';
            $tgl_registrasi = $this->input->post('tgl_registrasi').' '.date('H:i:s');
            $nomorrujukan =  ($this->input->post('norujukanbpjs'))?$this->input->post('norujukanbpjs'):'';
            $jeniskunjunganbpjs = ($this->input->post('jeniskunjunganbpjs'))?$this->input->post('jeniskunjunganbpjs'):'';

            if( !$this->input->post('no_registrasi_hidden') && !$this->input->post('no_registrasi_rujuk')){
                /*save tc_registrasi*/
                $data_registrasi = $this->daftar_pasien->daftar_registrasi($title,$no_mr, $kode_perusahaan, $kode_kelompok, $kode_dokter, $kode_bagian_masuk, $umur_saat_pelayanan,$no_sep,$jd_id, $kode_faskes, $tgl_registrasi, $nomorrujukan, $jeniskunjunganbpjs);
                $no_registrasi = $data_registrasi['no_registrasi'];
                $no_kunjungan = $data_registrasi['no_kunjungan'];
            }else{
                $no_registrasi = ($this->input->post('no_registrasi_hidden'))?$this->input->post('no_registrasi_hidden'):$this->input->post('no_registrasi_rujuk');
                $kode_bagian_asal = ($this->input->post('kode_bagian_asal')) ? $this->input->post('kode_bagian_asal') : $this->input->post('asal_pasien_rujuk');
                $kode_bagian_tujuan = $this->regex->_genRegex($this->form_validation->set_value('reg_klinik_rajal'),'RGXQSL');
                $no_kunjungan = $this->daftar_pasien->daftar_kunjungan($title,$no_registrasi,$no_mr,$kode_dokter,$kode_bagian_tujuan,$kode_bagian_asal, $tgl_registrasi);
                $bag = substr($this->input->post('kode_bagian_asal'), 1, 1);
                //print_r($bag);die;
                if($bag==3){
                    $datapoli['flag_ri']=1;
                    $datapoli['kelas_ri']=$this->input->post('klas');
                }else if($bag==2){
                    $datapoli['flag_igd']=1;
                }
                
            }
            
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
            $datapoli['created_date'] = date('Y-m-d H:i:s');
            $datapoli['updated_date'] = date('Y-m-d H:i:s');
            
            //print_r($datapoli);die;
            /*save poli*/
            $this->Reg_klinik->save('pl_tc_poli', $datapoli);

            /*save logs*/
            // $this->logs->save('pl_tc_poli', $datapoli['kode_poli'], 'insert new record on '.$this->title.' module', json_encode($datapoli),'kode_poli');
            
            /*parameter untuk print tracer*/
            $detail_data = $this->Reg_pasien->get_detail_resume_medis($no_registrasi);
            $data_tracer = [
                'no_mr' => $no_mr,
                'result' => $detail_data,
            ];

            /*jika terdapat id_tc_pesanan maka update tgl_masuk pada table tc_pesanan*/
            if( $this->input->post('id_tc_pesanan') ){
                $get_data_perjanjian = $this->db->get_where('tc_pesanan', array('id_tc_pesanan' => $this->input->post('id_tc_pesanan')) )->row();
                /*jika perjanjian HD maka harus diupdate kembali kode perjanjian nya*/
                if( $get_data_perjanjian->flag=='HD'){
                    $kode_perjanjian = $this->master->get_kode_perjanjian( date_create( $tgl_registrasi ) );
                    $udpate_data = array(
                        'kode_perjanjian' => $kode_perjanjian,
                        'unique_code_counter' => $this->master->get_max_number('tc_pesanan', 'unique_code_counter'),
                        );
                    $this->db->update('tc_pesanan', $udpate_data, array('id_tc_pesanan' => $this->input->post('id_tc_pesanan') ) );

                }else{
                    $this->db->update('tc_pesanan', array('tgl_masuk' => $tgl_registrasi, 'nopesertabpjs' => $_POST['noKartuBpjs'] ), array('id_tc_pesanan' => $this->input->post('id_tc_pesanan') ) );
                }
                // update kuota dokter used
                $this->logs->update_status_kuota(array('kode_dokter' => $datapoli['kode_dokter'], 'kode_spesialis' => $datapoli['kode_bagian'], 'tanggal' => date('Y-m-d'), 'keterangan' => null, 'flag' => 'perjanjian', 'status' => NULL ), 1);

                $kode_booking = $get_data_perjanjian->kode_perjanjian;

            }

            // jika tidak terdapat perjanjian dan mobile
            // if( $this->input->post('tipe_registrasi') == 'onsite' ){
            //     // update kuota dokter used
            //     $this->logs->update_status_kuota(array('kode_dokter' => $datapoli['kode_dokter'], 'kode_spesialis' => $datapoli['kode_bagian'], 'tanggal' => date('Y-m-d'), 'flag' => 'mesin_antrian', 'status' => NULL ), 1);
            // }
            /*jika dari pasien rujukan*/
            if( $this->input->post('kode_rujukan_hidden') ){
                $this->db->update('rg_tc_rujukan', array('status' => 1, 'rujukan_tujuan' => $this->regex->_genRegex($this->form_validation->set_value('reg_klinik_rajal'),'RGXQSL')), array('kode_rujukan' => $this->input->post('kode_rujukan_hidden') ) );
            }

            // if ( $this->input->post('cetak_kartu') == 'Y' ) {
            //     /*insert tagihan cetak kartu*/    
            //     $this->Billing->add_billing('cetak_kartu');
            // }

            // update no kartu bpjs
            $this->db->where('no_mr', $no_mr)->update('mt_master_pasien', array('no_kartu_bpjs' => $_POST['noKartuBpjs']));

            // if($kode_perusahaan == 120){
            //     if(in_array($_POST['jenis_pendaftaran'], array(1,4))){
            //         $filename = 'SEP-'.$no_mr.'-'.$no_registrasi.'-'.date('dmY').'';;
            //         $this->cbpModule->generateSingleDoc($filename);
            //     }
            // }

            // $config = array(
            //     'no_registrasi' => $no_registrasi,
            //     'kode_booking' => isset($kode_booking) ? $kode_booking : $no_registrasi,
            //     'tgl_registrasi' => $tgl_registrasi,
            //     'no_antrian' => $no_antrian,
            //     'no_mr' => $no_mr,
            //     'jeniskunjungan' => $_POST['jeniskunjunganbpjs'],
            //     'norujukan' => isset($_POST['norujukanbpjs'])?$_POST['norujukanbpjs']:"",
            // );


            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                
                /*jika transaksi berhasil maka print tracer*/
                $tracer = $this->print_escpos->print_direct($data_tracer);
                if( $tracer == 1 ) {
                    $this->db->update('tc_registrasi', array('print_tracer' => 'Y'), array('no_registrasi' => $no_registrasi) );
                }

                // PROSES ANTRIAN ONLINE
                // $detail_data = $this->Reg_pasien->get_detail_resume_medis($no_registrasi);
                // $dt_reg = $detail_data['registrasi'];
                // $dt_antrian = $detail_data['no_antrian'];
                // $dt_jadwal = $detail_data['jadwal'];
                // $jam_praktek_mulai = isset($dt_jadwal->jd_jam_mulai) ? $this->tanggal->formatTime($dt_jadwal->jd_jam_mulai) : '08:00';
                // $jam_praktek_selesai = isset($dt_jadwal->jd_jam_selesai) ? $this->tanggal->formatTime($dt_jadwal->jd_jam_selesai) : '10:00';
                // post antrian online
                // $params_dt = array(
                //   "no_registrasi" => $dt_reg->no_registrasi,
                //   'jam_praktek_mulai' => $jam_praktek_mulai,
                //   'jam_praktek_selesai' => $jam_praktek_selesai,
                //   'kuota_dr' => isset($dt_jadwal->jd_kuota) ? $dt_jadwal->jd_kuota : 10,
                // );

                // $jeniskunjungan = ($dt_reg->jeniskunjunganbpjs > 0) ? $dt_reg->jeniskunjunganbpjs : 3;
                // $config_antrol = array(
                //   "kodebooking" => $config['kode_booking'],
                //   "jenispasien" => "JKN",
                //   "nomorkartu" => $dt_reg->no_kartu_bpjs,
                //   "nik" => $dt_reg->no_ktp,
                //   "nohp" => $dt_reg->no_hp,
                //   "kodepoli" => $dt_reg->kode_poli_bpjs,
                //   "namapoli" => $dt_reg->nama_bagian,
                //   "pasienbaru" => 0,
                //   "norm" => $dt_reg->no_mr,
                //   "tanggalperiksa" => $this->tanggal->formatDateBPJS($this->tanggal->formatDateTimeToSqlDate($dt_reg->tgl_jam_masuk)),
                //   "kodedokter" => $dt_reg->kode_dokter_bpjs,
                //   "namadokter" => $dt_reg->nama_pegawai,
                //   "jampraktek" => $jam_praktek_mulai.'-'.$jam_praktek_selesai,
                //   "jeniskunjungan" => $jeniskunjungan,
                //   "nomorreferensi" => $dt_reg->norujukan,
                //   "nomorantrean" => $dt_reg->kode_poli_bpjs.'-'.$dt_antrian->no_antrian,
                //   "angkaantrean" => $dt_antrian->no_antrian,
                // );

                // $arr_data = array_merge($config_antrol, $params_dt);
                // $antrol = $this->reff->processAntrol($arr_data);
                // END PROSES ANTROL

                // get detail data
                $dt = $this->Reg_klinik->get_by_id($no_registrasi);

                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'no_mr' => $no_mr, 'no_registrasi' => $no_registrasi, 'is_new' => $this->input->post('is_new'), 'type_pelayanan' => 'rawat_jalan', 'dokter' => $dt->nama_pegawai, 'poli' => $dt->nama_bagian, 'nasabah' => $dt->nama_perusahaan, 'nama_pasien' => $dt->nama_pasien, 'kode_perusahaan' => $kode_perusahaan, 'no_kunjungan' => $no_kunjungan, 'no_antrian' => $datapoli['no_antrian'] ));

            }
        
        }

    }

    public function processRegisterNSEP(){

        // echo "<pre>";print_r($_POST);die;
        // form validation
        $this->form_validation->set_rules('tgl_registrasi', 'Tanggal Registrasi', 'trim|required');
        $this->form_validation->set_rules('noMrHidden', 'No MR', 'trim|required');
        $this->form_validation->set_rules('jenis_pendaftaran', 'Jenis Pendaftaran', 'trim|required');
        $this->form_validation->set_rules('kode_perusahaan_hidden', 'Kode Perusahaan', 'trim');
        $this->form_validation->set_rules('kode_kelompok_hidden', 'Kode Perusahaan', 'trim');

        if(!isset($_POST['post_ranap'])){
            if($_POST['kode_perusahaan_hidden'] == 120){
                $this->form_validation->set_rules('noRujukan', 'Nomor Rujukan', 'trim');
                if($_POST['jeniskunjunganbpjs'] == 3){
                    $this->form_validation->set_rules('noSuratSKDP', 'No Surat Kontrol', 'trim|required');   
                }else{
                    $this->form_validation->set_rules('rujukan_baru', 'Cheklist Rujukan Baru', 'trim|required', ['required' => 'Silahkan ceklis Rujukan Baru']);   
                }
            }else{
                $this->form_validation->set_rules('noRujukan', 'Nomor Rujukan', 'trim');
            }
        }

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
            
            // cek data registrasi today
            $this->db->select('a.no_registrasi, b.nama_pasien, b.no_mr, b.no_kartu_bpjs, c.nama_bagian, d.nama_pegawai as nama_dokter, a.tgl_jam_masuk, a.umur, CAST (b.tgl_lhr as DATE) AS tgl_lahir, a.no_sep, a.print_tracer, a.norujukan, a.jd_id, a.jeniskunjunganbpjs');
            $this->db->from('tc_registrasi a');
            $this->db->join('mt_master_pasien b', 'a.no_mr=b.no_mr','left');
            $this->db->join('mt_bagian c', 'c.kode_bagian=a.kode_bagian_masuk','left');
            $this->db->join('mt_dokter_v d', 'd.kode_dokter=a.kode_dokter','left');
            $this->db->where('a.kode_bagian_masuk', $_POST['reg_klinik_rajal']);
            $this->db->where('a.kode_dokter', $_POST['reg_dokter_rajal']);
            $this->db->where('a.no_mr', $_POST['noMrHidden']);
            $this->db->where('CAST(a.tgl_jam_masuk as DATE) = ', $_POST['tgl_registrasi']);
            $query = $this->db->get()->row();
            // echo "<pre>";print_r($query);die;

            $title = $this->title;
            $no_mr = $this->regex->_genRegex($_POST['noMrHidden'],'RGXQSL');
            $kode_perusahaan = $this->regex->_genRegex($_POST['kode_perusahaan_hidden'],'RGXINT');
            $kode_kelompok =  $this->regex->_genRegex($_POST['kode_kelompok_hidden'],'RGXINT');
            $umur_saat_pelayanan = $this->regex->_genRegex($_POST['umur_saat_pelayanan_hidden'],'RGXINT');
            $kode_faskes =  ($this->input->post('kodeFaskesHidden'))?$this->input->post('kodeFaskesHidden'):'';
            $tgl_registrasi = $this->input->post('tgl_registrasi').' '.date('H:i:s');
            $nomorrujukan =  ($this->input->post('noRujukan'))?$this->input->post('noRujukan'):'';
            $jeniskunjunganbpjs =  ($this->input->post('jeniskunjunganbpjs'))?$this->input->post('jeniskunjunganbpjs'):'';
            $mth = date('m');
            $yr = date('y');
            $no_sep = $this->regex->_genRegex('0112R034'.$mth.''.$yr.'Vxxxxx','RGXALNUM');
            $kode_dokter = $this->regex->_genRegex($_POST['reg_dokter_rajal'],'RGXINT');
            $kode_bagian_masuk = $this->regex->_genRegex($_POST['reg_klinik_rajal'],'RGXQSL');
            $jd_id =  $this->input->post('jd_id');

            if(empty($query)){
                
                $data_registrasi = $this->daftar_pasien->daftar_registrasi($title, $no_mr, $kode_perusahaan, $kode_kelompok, $kode_dokter, $kode_bagian_masuk, $umur_saat_pelayanan,$no_sep,$jd_id, $kode_faskes, $tgl_registrasi, $nomorrujukan, $jeniskunjunganbpjs);
                $no_registrasi = $data_registrasi['no_registrasi'];
                $no_kunjungan = $data_registrasi['no_kunjungan'];

                /*insert pl tc poli*/
                $kode_poli = $this->master->get_max_number('pl_tc_poli', 'kode_poli');
                $tipe_antrian = ($kode_perusahaan != 120) ? 'umum' : 'bpjs';
                $no_antrian = $this->master->get_no_antrian_poli($_POST['reg_klinik_rajal'],$_POST['reg_dokter_rajal'], $tipe_antrian, $tgl_registrasi);
                
                $datapoli['kode_poli'] = $kode_poli;
                $datapoli['no_kunjungan'] = $no_kunjungan;
                $datapoli['kode_bagian'] = $kode_bagian_masuk;
                $datapoli['tgl_jam_poli'] = $tgl_registrasi;
                $datapoli['kode_dokter'] = $kode_dokter;
                $datapoli['flag_antrian'] = $tipe_antrian;
                $datapoli['no_antrian'] = $no_antrian;
                $datapoli['nama_pasien'] = $_POST['nama_pasien_hidden'];
                $datapoli['created_date'] = date('Y-m-d H:i:s');
                $datapoli['updated_date'] = date('Y-m-d H:i:s');
                $datapoli['post_ranap'] = isset($_POST['post_ranap']) ? $_POST['post_ranap'] : 'N';
                
                //print_r($datapoli);die;
                /*save poli*/
                $this->Reg_klinik->save('pl_tc_poli', $datapoli);
                $this->db->trans_commit();

            }else{
                $no_registrasi = $query->no_registrasi;
                // update tc_registrasi
                $this->db->where('no_registrasi', $no_registrasi)->update('tc_registrasi', ['jd_id' => $_POST['jd_id'], 'kode_bagian_masuk' => $_POST['reg_klinik_rajal'], 'kode_dokter' => $_POST['reg_dokter_rajal'], 'tgl_jam_masuk' => $tgl_registrasi]);
                $this->db->trans_commit();
            }

            // add antrol
            $detail_data = $this->Reg_pasien->get_detail_resume_medis($no_registrasi);
            $dt_reg = $detail_data['registrasi'];
            
            // print_r($dt_reg);die;
            $dt_antrian = $detail_data['no_antrian'];
            $dt_jadwal = $detail_data['jadwal'];
            $jam_praktek_mulai = ($dt_jadwal->jd_jam_mulai) ? $this->tanggal->formatTime($dt_jadwal->jd_jam_mulai) : '08:00';
            $jam_praktek_selesai = ($dt_jadwal->jd_jam_selesai) ? $this->tanggal->formatTime($dt_jadwal->jd_jam_selesai) : '10:00';
            $kuota_dr = ($dt_jadwal->jd_kuota) ? $dt_jadwal->jd_kuota : 10;
            $jeniskunjungan = ($_POST['jeniskunjunganbpjs'] > 0) ? $_POST['jeniskunjunganbpjs'] : 3;
            $nomorreferensi = ($jeniskunjungan == 3) ? $_POST['noSuratSKDP'] : $_POST['noRujukan'];

            if(!isset($_POST)) : 

                $config_antrol = array(
                    "no_registrasi" => $dt_reg->no_registrasi,
                    'jam_praktek_mulai' => $jam_praktek_mulai,
                    'jam_praktek_selesai' => $jam_praktek_selesai,
                    'kuota_dr' => $kuota_dr,
                    "kodebooking" => $no_registrasi,
                    "jenispasien" => "JKN",
                    "nomorkartu" => $dt_reg->no_kartu_bpjs,
                    "nik" => $dt_reg->no_ktp,
                    "nohp" => $dt_reg->no_hp,
                    "kodepoli" => $_POST['kodePoliHidden'],
                    "namapoli" => $dt_reg->nama_bagian,
                    "pasienbaru" => 0,
                    "norm" => $no_mr,
                    "tanggalperiksa" => $this->tanggal->formatDateBPJS($this->tanggal->formatDateTimeToSqlDate($dt_reg->tgl_jam_masuk)),
                    "kodedokter" => trim($_POST['kodeDokterDPJPPerjanjianBPJS']),
                    "namadokter" => $dt_reg->nama_pegawai,
                    "jampraktek" => $jam_praktek_mulai.'-'.$jam_praktek_selesai,
                    "jeniskunjungan" => $jeniskunjungan,
                    "nomorreferensi" => $nomorreferensi,
                    "nomorantrean" => $_POST['kodePoliHidden'].'-'.$dt_antrian->no_antrian,
                    "angkaantrean" => $dt_antrian->no_antrian,
                );

                $antrol = $this->reff->processAntrol($config_antrol);
                // echo "<pre>"; print_r($antrol);die;
                
                /*insert sep*/
                $data = array(
                    'request' => array(
                        't_sep' => array(
                            'noKartu' => $_POST['noKartuHidden'],
                            'tglSep' => $_POST['tglSEP'],
                            'ppkPelayanan' => $this->kode_faskes, 
                            'jnsPelayanan' => $_POST['jnsPelayanan'],
                            'klsRawat' => array(
                                'klsRawatHak' => ( $_POST['jnsPelayanan'] == 1 ) ? $_POST['kelasRawat'] : "3",
                                'klsRawatNaik' => "",
                                'pembiayaan' => "",
                                'penanggungJawab' => ""
                            ),
                            'noMR' => $no_mr,
                            'rujukan' => array(
                                'asalRujukan' => ($_POST['jenis_faskes_pasien'] == 'pcare') ? 1 : 2 ,
                                'tglRujukan' => $_POST['tglRujukan'],
                                'noRujukan' => $_POST['noRujukan'],
                                'ppkRujukan' => $_POST['kodeFaskesHidden'], //blom ada
                                ),
                            'catatan' => $_POST['catatan'],
                            'diagAwal' => $_POST['kodeDiagnosaHidden'],
                            'poli' => array(
                                'tujuan' => $_POST['kodePoliHidden'],
                                'eksekutif' => isset($_POST['eksekutif'])?$_POST['eksekutif']:"0",
                                ),
                            'cob' => array('cob' => isset($_POST['cob'])?$_POST['cob']:"0"),
                            'katarak' => array('katarak' => isset($_POST['katarak'])?$_POST['katarak']:"0"),
                            'jaminan' => array(
                                'lakaLantas' => ($_POST['lakalantas'])?$_POST['lakalantas']:"0", 
                                'penjamin' => array(
                                    "penjamin" => isset($_POST['penjamin'])?$_POST['penjamin']:"",
                                    "tglKejadian" => isset($_POST['tglKejadian'])?$_POST['tglKejadian']:"",
                                    "keterangan" => isset($_POST['keteranganKejadian'])?$_POST['keteranganKejadian']:"",
                                    "suplesi" => array(
                                        'suplesi' => isset($_POST['suplesi'])?$_POST['suplesi']:"0",
                                        "noSepSuplesi"  => isset($_POST['noSepSuplesi'])?$_POST['noSepSuplesi']:"0",
                                        "lokasiLaka" => array(
                                            'kdPropinsi' => isset($_POST['provinceId'])?$_POST['provinceId']:"0",
                                            'kdKabupaten' => isset($_POST['regencyId'])?$_POST['regencyId']:"0",
                                            'kdKecamatan' => isset($_POST['districtId'])?$_POST['districtId']:"0",
                                            ),
                                        ),
                                    ), 
                                ),
                            'tujuanKunj' => $_POST['tujuanKunj'],
                            'flagProcedure' => $_POST['flagProcedure'],
                            'kdPenunjang' => $_POST['kdPenunjang'],
                            'assesmentPel' => $_POST['assesmentPel'],
                            'skdp' => array('noSurat' => $_POST['noSuratSKDP'], "kodeDPJP" => trim($_POST['kodeDokterDPJPPerjanjianBPJS']) ),
                            'dpjpLayan' => trim($_POST['kodeDokterDPJPPerjanjianBPJS']),
                            'noTelp' => $_POST['noTelp'],
                            'user' => $_POST['user'],
                            ),
                        ),
                );

                // echo "<pre>"; print_r($data);die;
                $result = $this->Ws_index->insertSep($data);
                $response = isset($result['response']) ? $result : false;

                if($response == false){
                    echo json_encode(array('status' => 0, 'message' => 'Error API ! Silahkan cek koneksi anda!'));
                    exit;
                }

                if($response['response']->metaData->code == 200){
                    // save log data sep
                    $sep = $response['data']->sep;
                    $insert_sep = array(
                        'catatan' => $sep->catatan,
                        'diagnosa' => $sep->diagnosa,
                        'jnsPelayanan' => $sep->jnsPelayanan,
                        'kelasRawat' => ($this->form_validation->set_value('jnsPelayanan')==1)?$sep->kelasRawat:"Kelas 1",
                        'noSep' => $sep->noSep,
                        'penjamin' => $sep->penjamin,
                        'poli' => $sep->poli,
                        'poliEksekutif' => $sep->poliEksekutif,
                        'tglSep' => $sep->tglSep,
                        /*peserta*/
                        'asuransi' => $sep->peserta->asuransi,
                        'hakKelas' => $sep->peserta->hakKelas,
                        'jnsPeserta' => $sep->peserta->jnsPeserta,
                        'kelamin' => $sep->peserta->kelamin,
                        'nama' => $sep->peserta->nama,
                        'noKartu' => $sep->peserta->noKartu,
                        'noMr' => $sep->peserta->noMr,
                        'tglLahir' => $sep->peserta->tglLahir,
                        'kodePPPKPerujuk' => $_POST['kodeFaskesHidden'],
                        'PPKPerujuk' => $_POST['ppkRujukan'],
                        'asalRujukan' => ($this->form_validation->set_value('jenis_faskes') == 'pcare') ? 1 : 2 ,
                        'tglRujukan' => $this->form_validation->set_value('tglRujukan'),
                        'noRujukan' => $this->form_validation->set_value('noRujukan'),
                        'kodeDiagnosa' => $this->form_validation->set_value('kodeDiagnosaHidden'),
                        'kodeJnsPelayanan' => $this->form_validation->set_value('jnsPelayanan'),
                        'kodeKelasRawat' => ($this->form_validation->set_value('jnsPelayanan')==1)?$this->form_validation->set_value('kelasRawat'):"3",
                        'kodePoli' =>$this->form_validation->set_value('kodePoliHidden'),
                        'noTelp' =>  $this->form_validation->set_value('noTelp'),
                        'lakaLantas' => ($this->form_validation->set_value('lakalantas'))?$this->form_validation->set_value('lakalantas'):"0", 
                        'penjamin' => $this->form_validation->set_value('penjamin'), 
                        'lokasiLaka' => $this->form_validation->set_value('lokasiLaka'),
                        'find_member_by' => $this->form_validation->set_value('find_member_by'),
                        'created_date' => date('Y-m-d H:i:s'),
                        'created_by' => $this->session->userdata('user')->fullname,
                        'noSuratSKDP' => $this->form_validation->set_value('noSuratSKDP'),
                        'KodedokterDPJP' =>  $this->form_validation->set_value('KodedokterDPJP'),
                        'namaDokterDPJP' => $this->form_validation->set_value('dokterDPJP'),
                    );
                    $this->Ws_index->insert_tbl_sep('ws_bpjs_sep', $insert_sep);

                    // udpate no sep
                    $this->db->where('no_registrasi', $no_registrasi)->update('tc_registrasi', ['no_sep' => $sep->noSep, 'diagnosa_rujukan' => $_POST['diagAwal'], 'kode_diagnosa_rujukan' => $_POST['kodeDiagnosaHidden']]);
                    $this->db->trans_commit();
                    echo json_encode( array('status' => 200, 'message' => 'Proses berhasil dilakukan!', 'result' => $sep, 'no_sep' => $sep->noSep, 'no_kartu' => $_POST['noKartuHidden'], 'data' => $response['data'], 'type_pelayanan' => 'create_sep', 'kode_perusahaan' => 120, 'no_antrian' => $dt_antrian ) );

                }else{
                    // udpate no sep
                    $this->db->where('no_registrasi', $no_registrasi)->update('tc_registrasi', ['diagnosa_rujukan' => $_POST['diagAwal'], 'kode_diagnosa_rujukan' => $_POST['kodeDiagnosaHidden']]);
                    $this->db->trans_commit();
                    echo json_encode(array('status' => 201, 'message' => $response['response']->metaData->message, 'type_pelayanan' => 'create_sep', 'no_sep' => $no_sep, 'no_kartu' => $_POST['noKartuHidden']));
                } 
            else: 
                echo json_encode(array('status' => 201, 'message' => 'Proses berhasil !', 'type_pelayanan' => 'create_sep', 'no_sep' => $no_sep, 'no_kartu' => $_POST['noKartuHidden']));
            endif;

        }

    }

    public function print_tracer()
    {
        # code...
       
        $detail_data = $this->Reg_pasien->get_detail_resume_medis($_POST['no_registrasi']);
            
        $data_tracer = [
            'no_mr' => $_POST['no_mr'],
            'result' => $detail_data,
        ];

        // if($this->input->post('is_new')!='Yes'){

            if( $this->print_direct->printer_php($data_tracer) ){
                
            }else{
                $this->db->update('tc_registrasi', array('print_tracer' => 'N'), array('no_registrasi' => $_POST['no_registrasi'] ) );
            }
        // }      

        $this->print_escpos->print_direct($data_tracer);

        echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'no_mr' => $_POST['no_mr']));
    }

    public function get_no_antrian(){
        $no_antrian = $this->master->get_no_antrian_poli($_GET['poli'],$_GET['dokter'], 0, '2023-05-17');
        echo json_encode($no_antrian);
    }

    public function search_rujukan_by_kartu($no_kartu_bpjs){
        $rujukan = $this->Ws_index->get_rujukan_by_kartu($no_kartu_bpjs);
        // echo "<pre>"; print_r($rujukan);die;
        if($rujukan['response']->metaData->code != 200){
            echo "<div class='alert alert-danger'><b>Pemberitahuan !</b><br>".$rujukan['response']->metaData->message." atau Nomor Kartu BPJS tidak sesuai.</div>";
            exit;
        }
        $data = array(
            'rujukan' => ($rujukan['data']->rujukan)?$rujukan['data']->rujukan:0,
        );
        $this->load->view('Reg_klinik/view_data_rujukan', $data);
    }

    public function print_bukti_pendaftaran($no_registrasi) { 
        
        $data = [
            'result' => $this->Reg_pasien->get_detail_resume_medis($no_registrasi),
            'no_registrasi' => $no_registrasi,
        ];

        $userDob = isset($data['result']['registrasi']->tgl_lhr)?$data['result']['registrasi']->tgl_lhr:'1990-01-01';
 
        //Create a DateTime object using the user's date of birth.
        $dob = new DateTime($userDob);
     
        //We need to compare the user's date of birth with today's date.
        $now = new DateTime();

        //Calculate the time difference between the two dates.
        $difference = $now->diff($dob);

        //Get the difference in years, as we are looking for the user's age.
        $umur = $difference->format('%y');

        $data['umur'] = $umur;
        // echo '<pre>';print_r($data);die;
        $this->print_escpos->print_registrasi($data);

    }

    public function upload_foto_pasien() {
        $img = $this->input->post('image');
        $no_mr = $this->input->post('no_mr');
        if (!$img || !$no_mr) {
            echo json_encode(['status'=>500, 'message'=>'Data tidak lengkap']);
            return;
        }
        $img = str_replace('data:image/png;base64,', '', $img);
        $img = str_replace(' ', '+', $img);
        $data = base64_decode($img);
        $fileName = 'uploaded/images/photo/'.$no_mr.'_'.date('YmdHis').'.png';
        file_put_contents(FCPATH.$fileName, $data);
        // Simpan ke database jika perlu
        $file_foto = str_replace('uploaded/images/photo/','',$fileName);
        $this->db->update('mt_master_pasien', ['url_foto_pasien' => $file_foto]);
        echo json_encode(['status'=>200, 'message'=>'Foto berhasil diupload', 'url_foto'=>base_url($fileName)]);
    }


}



/* End of file example.php */

/* Location: ./application/functiones/example/controllers/example.php */

