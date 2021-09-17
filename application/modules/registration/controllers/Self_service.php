<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Self_service extends MX_Controller {

    function __construct() {
        parent::__construct();
        $this->load->model('antrian/antrian_model'); 
        $this->load->model('registration/Reg_pasien_model', 'Reg_pasien');
        $this->load->model('registration/Reg_klinik_model', 'Reg_klinik');
        $this->load->model('antrian/loket_model','loket');
        $this->load->model('display_loket/main_model','Main');
        $this->load->model('ws_bpjs/Ws_index_model', 'Ws_index');
        $this->load->model('information/Regon_info_jadwal_dr_model', 'Regon_info_jadwal_dr');
        
        $this->load->library('Daftar_pasien');
        $this->load->library('Form_validation');
        $this->load->library('Print_direct');
        $this->load->library('Print_escpos');  
        $this->load->library('tarif');   

        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->
        get_menu_by_class(get_class($this))->name : 'Title';

    }

    public function index() {
        
        $data = array();

        $this->load->view('Self_service/index', $data);
    }

    public function mandiri_bpjs() {
        
        $data = array();

        $this->load->view('Self_service/index_bpjs', $data);
    }

    public function mandiri_umum() {
        
        $data = array();
        // $data_loket = $this->loket->get_open_loket();

        // foreach ($data_loket as $key => $value) {
        //     # code...
        //     $kuota = $this->loket->get_sisa_kuota($value);
        //     if($kuota<0)$kuota=0;
        //     $data_loket[$key]->kuota = $kuota;
        // }

        // $data['type'] = isset($_GET['type'])?$_GET['type']:'bpjs';
        // $data['klinik'] = $data_loket;
        // echo '<pre>';print_r($data);die;

        $this->load->view('Self_service/index_umum', $data);
    }

    public function tab_poli() {
        
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

        $this->load->view('Self_service/tab_poli', $data);
    }

    public function tab_pm() {        
        $data = array();
        $this->load->view('Self_service/tab_pm', $data);
    }

    public function antrian_poli() {
        
        $data_loket = $this->loket->get_open_loket();

        foreach ($data_loket as $key => $value) {
            # code...
            $kuota = $this->loket->get_sisa_kuota($value);
            if($kuota<0)$kuota=0;
            $data_loket[$key]->kuota = $kuota;
        }

        //print_r($_GET['type']);die;
        $data['type'] = isset($_GET['type'])?$_GET['type']:'bpjs';
        
        $data['klinik'] = $data_loket;

        $this->load->view('Self_service/index_antrian_poli', $data);
    }

    public function jadwal_dokter() { 
        //echo '<pre>';print_r($this->session->all_userdata());die;
        /*define variable data*/
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show()
        );
        /*load view index*/
        $this->load->view('Self_service/index_jadwal_dokter', $data);
    }

     public function detail_jadwal($jd_id, $hari) { 
        //echo '<pre>';print_r($this->session->all_userdata());die;
        /*define variable data*/
        $data = array(
            'title' => $hari,
            'breadcrumbs' => $this->breadcrumbs->show(),
            'value' => $this->db->join('mt_bagian','mt_bagian.kode_bagian=tr_jadwal_dokter.jd_kode_spesialis','left')->join('mt_karyawan','mt_karyawan.kode_dokter=tr_jadwal_dokter.jd_kode_dokter','left')->get_where('tr_jadwal_dokter', array('jd_id' => $jd_id) )->row(),
        );
        // echo '<pre>';print_r($data);die;
        /*load view index*/
        $this->load->view('Self_service/detail_jadwal', $data);
    }

    public function form_rujukan() {
        
        $data = array();
        $data['profile'] = $this->findKodeBooking($_GET['kode']);
        $data['kode'] = $_GET['kode'];
        // echo '<pre>'; print_r($data);die;
        $this->load->view('Self_service/form_rujukan', $data);
    }

    public function form_perjanjian($jd_id) {
        
        $data = array();
        $data['value'] =  $this->db->join('mt_bagian','mt_bagian.kode_bagian=tr_jadwal_dokter.jd_kode_spesialis','left')->join('mt_karyawan','mt_karyawan.kode_dokter=tr_jadwal_dokter.jd_kode_dokter','left')->get_where('tr_jadwal_dokter', array('jd_id' => $jd_id) )->row();
        
        // echo '<pre>'; print_r($data);die;
        $this->load->view('Self_service/form_perjanjian', $data);
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
                $row[] = '<div class="center" style="font-size: 14px">'.$no.'</div>';
                $row[] = '<div class="left" style="font-size: 14px">'.$main_data['nama_pegawai'].'</div>';
                $row[] = '<div class="left" style="font-size: 14px">'.ucwords($main_data['nama_bagian']).'</div>';

                for ($i=1; $i < 8; $i++) { 

                    if(count($value_2) > 0){
                        $day_lib = $this->tanggal->getDayByNum($i);
                        $key = array_search($day_lib, array_column($value_2, 'jd_hari'));

                        if(isset($value_2[$key]['jd_hari'])){
                            if($day_lib==$value_2[$key]['jd_hari']){
                                $note = ($value_2[$key]['jd_keterangan'] != '')?' <br> '.$value_2[$key]['jd_keterangan'].'':'';
                                $end = ($value_2[$key]['jd_jam_selesai'] != '')?' - '.$value_2[$key]['jd_jam_selesai'].'':'';
                                $val_result = $this->tanggal->formatTime($value_2[$key]['jd_jam_mulai']).' s/d '.$this->tanggal->formatTime($value_2[$key]['jd_jam_selesai']).'';

                                $row[] = '<div class="center" style="font-weight: bold"><a href="#" onclick="show_modal('."'Self_service/detail_jadwal/".$value_2[$key]['jd_id']."/".$value_2[$key]['jd_hari']."'".')">'.$val_result.'</a></div>';
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
    
    public function findKodeBooking($kode)
	{
        
		$this->db->select('no_mr, nama, jam_pesanan, mt_dokter_v.nama_pegawai as nama_dr, mt_bagian.nama_bagian, kode_poli_bpjs, kode_bagian, tc_pesanan.kode_dokter, id_tc_pesanan ');
		$this->db->from('tc_pesanan');
		$this->db->where('unique_code_counter', $kode);
		$this->db->join('mt_bagian', 'mt_bagian.kode_bagian=tc_pesanan.no_poli','left');
		$this->db->join('mt_dokter_v', 'mt_dokter_v.kode_dokter=tc_pesanan.kode_dokter','left');
        $exc = $this->db->get();
        // echo '<pre>'; print_r($this->db->last_query());die;
		if ($exc->num_rows() == 0) {
            return false;
		}else{
			$dt = $exc->row();
			$result = array(
				'kode' => $kode,
				'id_tc_pesanan' => $dt->id_tc_pesanan,
				'no_mr' => $dt->no_mr,
				'nama' => $dt->nama,
				'kode_bagian' => $dt->kode_bagian,
				'kode_dokter' => $dt->kode_dokter,
				'kode_poli_bpjs' => $dt->kode_poli_bpjs,
				'tgl_kunjungan' => $this->tanggal->formatDatedmY($dt->jam_pesanan),
				'nama_dr' => strtoupper($dt->nama_dr),
				'poli' => strtoupper($dt->nama_bagian),
				'jam_praktek' => $this->tanggal->formatDateTimeToTime($dt->jam_pesanan),
			);
			return $result;
		}
    }

    public function insertSep()
    {
        // print_r($_POST);die;
        $this->load->library('form_validation');
        $val = $this->form_validation;
        /*global*/
        if( $this->input->post('find_member_by')=='bpjs'){
            $val->set_rules('noKartu', 'No Kartu BPJS', 'trim|required');
        }
        $val->set_rules('noKartuHidden', 'No Kartu Hidden', 'trim|required');
        $val->set_rules('tglSEP', 'Tanggal SEP', 'trim|required');
        /*$val->set_rules('ppkPelayanan', 'Faskes', 'trim|required');*/
        $val->set_rules('jnsPelayanan', 'Jenis Pelayanan', 'trim|required');
        /*untuk rawat inap*/
        if( $this->input->post('jnsPelayanan')==1 ){
            $val->set_rules('kelasrawat', 'Kelas Rawat', 'trim|required');
        }

        $val->set_rules('noMR', 'No MR', 'trim|required');
        $val->set_rules('catatan', 'Catatan', 'trim|xss_clean');
        $val->set_rules('diagAwal', 'Diagnosa Awal', 'trim|required');
        $val->set_rules('kodeDiagnosaHidden', 'Diagnosa Awal', 'trim|required');
        $val->set_rules('noTelp', 'No Telp', 'trim|required');
        $val->set_rules('user', 'Pengguna', 'trim|required');
        $val->set_rules('noSuratSKDP', 'Nomor Surat Kontrol', 'trim|required');
        $val->set_rules('KodedokterDPJP', 'Dokter DPJP', 'trim|required');
        $val->set_rules('dokterDPJP', 'Dokter DPJP', 'trim');
        $val->set_rules('find_member_by', 'Cari Peserta Berdasarkan', 'trim|required');

        /*poli*/
        $val->set_rules('kodePoliHidden', 'Poli', 'trim|required');
        $val->set_rules('eksekutif', 'Eksekutif', 'trim|xss_clean');
        /*endpoli*/

        /*rujukan*/
        if ($this->input->post('kodePoliHidden')!='IGD') {
            $val->set_rules('jenis_faskes', 'Jenis Faskes Rujukan', 'trim|required');
            $val->set_rules('tglRujukan', 'Tanggal Rujukan', 'trim|required');
            $val->set_rules('noRujukan', 'Nomor Rujukan', 'trim|required');
            $val->set_rules('ppkRujukan', 'Faskes Rujukan', 'trim|required');
            $val->set_rules('kodeFaskesHidden', 'Faskes Rujukan', 'trim|required');
        }
        /*end rujukan*/

        /*cob*/
        $val->set_rules('cob', 'Peserta COB', 'trim|xss_clean');
        /*endcob*/

        /*jaminan*/
        if($this->input->post('penjaminKLL')==1){
            $val->set_rules('penjamin', 'Penjamin ', 'trim|required');
            $val->set_rules('lakalantas', 'Kejadian lalu lintas', 'trim|required');
            $val->set_rules('lokasiLaka', 'Lokasi Kejadian', 'trim|xss_clean');
        }
        /*end jaminan*/

        /*untuk update sep*/
        if(isset($_POST['proses']) AND $_POST['proses']=='update'){
            $val->set_rules('noSep', 'No SEP', 'trim|required');
        }
        
        $val->set_message('required', "Silahkan isi field \"%s\"");
        $val->set_message('integer', "Field \"%s\" harus diisi dengann angka");

        if ($val->run() == FALSE)
        {
            $val->set_error_delimiters('<div style="color:white">', '</div>');
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {    
            if(isset($_POST['proses']) AND $_POST['proses']=='update'){
                /*proses insert sep*/
                $result = $this->prosesUpdateSep($val);
            }else{
                /*proses insert sep*/
                $result = $this->prosesInsertSep($val);
                //print_r($result);die;

            }

            echo $result;

        }
    }

    function prosesInsertSep($val){
        /*insert sep*/
            $data = array(
                'request' => array(
                    't_sep' => array(
                        'noKartu' => $val->set_value('noKartuHidden'),
                        'tglSep' => $this->tanggal->sqlDateForm($val->set_value('tglSEP')),
                        'ppkPelayanan' => '0112R034', // kode faskes
                        'jnsPelayanan' => $val->set_value('jnsPelayanan'),
                        'klsRawat' => ($val->set_value('jnsPelayanan')==1)?$val->set_value('kelasRawat'):"3",
                        'noMR' => $val->set_value('noMR'),
                        'rujukan' => array(
                            'asalRujukan' => $val->set_value('jenis_faskes'),
                            'tglRujukan' => $val->set_value('tglRujukan'),
                            'noRujukan' => $val->set_value('noRujukan'),
                            'ppkRujukan' => $val->set_value('kodeFaskesHidden'),
                            ),
                        'catatan' => $val->set_value('catatan'),
                        'diagAwal' => $val->set_value('kodeDiagnosaHidden'),
                        'poli' => array(
                            'tujuan' => $val->set_value('kodePoliHidden'),
                            'eksekutif' => $val->set_value('eksekutif')?$val->set_value('eksekutif'):"0",
                            ),
                        'cob' => array('cob' => $val->set_value('cob')?$val->set_value('cob'):"0"),
                        'katarak' => array('katarak' => $val->set_value('katarak')?$val->set_value('katarak'):"0"),
                        'jaminan' => array(
                            'lakaLantas' => ($val->set_value('lakalantas'))?$val->set_value('lakalantas'):"0", 
                            'penjamin' => array(
                                "penjamin" => $val->set_value('penjamin')?$val->set_value('penjamin'):"",
                                "tglKejadian" => $this->tanggal->sqlDateForm($val->set_value('tglKejadian')),
                                "keterangan" => $val->set_value('keteranganKejadian')?$val->set_value('keteranganKejadian'):"",
                                "suplesi" => array(
                                    'suplesi' => $val->set_value('suplesi')?$val->set_value('suplesi'):"0",
                                    "noSepSuplesi"  => $val->set_value('noSepSuplesi')?$val->set_value('noSepSuplesi'):"0",
                                    "lokasiLaka" => array(
                                        'kdPropinsi' => $val->set_value('provinceId')?$val->set_value('provinceId'):"0",
                                        'kdKabupaten' => $val->set_value('regencyId')?$val->set_value('regencyId'):"0",
                                        'kdKecamatan' => $val->set_value('districtId')?$val->set_value('districtId'):"0",
                                        ),
                                    ),
                                ), 
                            ),
                        'skdp' => array('noSurat' => $val->set_value('noSuratSKDP'), "kodeDPJP" => "34646" ),
                        'noTelp' => $val->set_value('noTelp'),
                        'user' => $val->set_value('user'),
                        ),
                    ),
                );
                $result = $this->Ws_index->insertSep($data);
                // print_r($data);die;

            if( $result->metaData->code==200 ){
                /*simpan data sep*/
                $sep = $result->response->sep;
                $insert_sep = array(
                    'catatan' => $sep->catatan,
                    'diagnosa' => $sep->diagnosa,
                    'jnsPelayanan' => $sep->jnsPelayanan,
                    'kelasRawat' => ($val->set_value('jnsPelayanan')==1)?$sep->kelasRawat:"Kelas 1",
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
                    'kodePPPKPerujuk' => $val->set_value('kodeFaskesHidden'),
                    'PPKPerujuk' => $val->set_value('ppkRujukan'),
                    'asalRujukan' => $val->set_value('jenis_faskes'),
                    'tglRujukan' => $val->set_value('tglRujukan'),
                    'noRujukan' => $val->set_value('noRujukan'),
                    'kodeDiagnosa' => $val->set_value('kodeDiagnosaHidden'),
                    'kodeJnsPelayanan' => $val->set_value('jnsPelayanan'),
                    'kodeKelasRawat' => ($val->set_value('jnsPelayanan')==1)?$val->set_value('kelasRawat'):"3",
                    'kodePoli' =>$val->set_value('kodePoliHidden'),
                    'noTelp' =>  $val->set_value('noTelp'),
                    'lakaLantas' => ($val->set_value('lakalantas'))?$val->set_value('lakalantas'):"0", 
                    'penjamin' => $val->set_value('penjamin'), 
                    'lokasiLaka' => $val->set_value('lokasiLaka'),
                    'find_member_by' => $val->set_value('find_member_by'),
                    'created_date' => date('Y-m-d H:i:s'),
                    'created_by' => $this->session->userdata('user')->fullname,
                    'noSuratSKDP' => $val->set_value('noSuratSKDP'),
                    'KodedokterDPJP' =>  $val->set_value('KodedokterDPJP'),
                    'namaDokterDPJP' => $val->set_value('dokterDPJP'),
                );
                $this->Ws_index->insert_tbl_sep('ws_bpjs_sep', $insert_sep);
            }else{
                $sep = new stdClass;
            }
            // save registration
            $this->processRegistrasi($sep);

            $response = json_encode(array('status' => $result->metaData->code, 'message' => $result->metaData->message, 'result' => $sep, 'no_sep' => isset($sep->noSep)?$sep->noSep:'', 'redirect' => base_url().'ws_bpjs/ws_index?modWs=InsertSep', 'data' => $data ));

            return $response;
    }

    public function processRegistrasi($sep=''){

        // print_r($_POST);die;
        // form validation
        $this->form_validation->set_rules('tgl_registrasi', 'Tanggal Registrasi', 'trim|required');
        $this->form_validation->set_rules('reg_klinik_rajal', 'Poli/Klinik', 'trim|required');
        $this->form_validation->set_rules('reg_dokter_rajal', 'Dokter', 'trim|required');
        $this->form_validation->set_rules('noMRBooking', 'Dokter', 'trim|required');
        $this->form_validation->set_rules('kode_perusahaan_hidden', 'Kode Perusahaan', 'trim');
        $this->form_validation->set_rules('kode_kelompok_hidden', 'Kode Perusahaan', 'trim');
        $this->form_validation->set_rules('umur_saat_pelayanan_hidden', 'Umur', 'trim');

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
            $no_mr = $this->regex->_genRegex($this->form_validation->set_value('noMRBooking'),'RGXQSL');
            $kode_perusahaan = $this->regex->_genRegex($this->form_validation->set_value('kode_perusahaan_hidden'),'RGXINT');
            $kode_kelompok =  $this->regex->_genRegex($this->form_validation->set_value('kode_kelompok_hidden'),'RGXINT');
            $kode_dokter = $this->regex->_genRegex($this->form_validation->set_value('reg_dokter_rajal'),'RGXINT');
            $kode_bagian_masuk = $this->regex->_genRegex($this->form_validation->set_value('reg_klinik_rajal'),'RGXQSL');
            $umur_saat_pelayanan = $this->regex->_genRegex($this->form_validation->set_value('umur_saat_pelayanan_hidden'),'RGXINT');
            $no_sep = isset($sep->noSep) ? $this->regex->_genRegex($sep->noSep,'RGXALNUM') : '';
            $jd_id =  $this->input->post('jd_id');

            /*save tc_registrasi*/
            $data_registrasi = $this->daftar_pasien->daftar_registrasi($title,$no_mr, $kode_perusahaan, $kode_kelompok, $kode_dokter, $kode_bagian_masuk, $umur_saat_pelayanan,$no_sep, $jd_id);

            $no_registrasi = $data_registrasi['no_registrasi'];
            $no_kunjungan = $data_registrasi['no_kunjungan'];
            
            /*insert pl tc poli*/
            $kode_poli = $this->master->get_max_number('pl_tc_poli', 'kode_poli');
            $no_antrian = $this->master->get_no_antrian_poli($this->form_validation->set_value('reg_klinik_rajal'),$this->form_validation->set_value('reg_dokter_rajal'));
            
            $datapoli['kode_poli'] = $kode_poli;
            $datapoli['no_kunjungan'] = $no_kunjungan;
            $datapoli['kode_bagian'] = $this->regex->_genRegex($this->form_validation->set_value('reg_klinik_rajal'),'RGXQSL');
            $datapoli['tgl_jam_poli'] = date('Y-m-d H:i:s');
            $datapoli['kode_dokter'] = $this->regex->_genRegex($this->form_validation->set_value('reg_dokter_rajal'),'RGXINT');
            $datapoli['no_antrian'] = $no_antrian;
            $datapoli['nama_pasien'] = $_POST['nama_pasien_hidden'];
            
            //print_r($datapoli);die;
            /*save poli*/
            $this->Reg_klinik->save('pl_tc_poli', $datapoli);

            /*save logs*/
            $this->logs->save('pl_tc_poli', $datapoli['kode_poli'], 'insert new record on '.$this->title.' module', json_encode($datapoli),'kode_poli');
            
            // log kuota dokter
            // $this->logs->save_log_kuota(array('kode_dokter' => $datapoli['kode_dokter'], 'kode_spesialis' => $datapoli['kode_bagian'], 'tanggal' => $datapoli['tgl_jam_poli'], 'keterangan' => null, 'flag' => 'on_the_spot' ));

            // save biaya APD
            $datatarif = array(
                /*form hidden input default*/
                'no_kunjungan' => $this->regex->_genRegex($no_kunjungan,'RGXINT'),
                'no_registrasi' => $this->regex->_genRegex($no_registrasi,'RGXINT'),
                'kode_kelompok' => $this->regex->_genRegex($kode_kelompok,'RGXINT'),
                'kode_perusahaan' => $this->regex->_genRegex($kode_perusahaan,'RGXINT'),
                'no_mr' => $this->regex->_genRegex($no_mr,'RGXQSL'),
                'nama_pasien_layan' => $this->regex->_genRegex($_POST['nama_pasien_hidden'],'RGXQSL'),
                'kode_bagian_asal' => $this->regex->_genRegex($this->input->post('kode_bagian_asal'),'RGXQSL'),
                /*end form hidden input default*/
                'kode_bagian' => $this->regex->_genRegex($this->input->post('reg_klinik_rajal'),'RGXQSL'),
                'kode_klas' => $this->regex->_genRegex(16,'RGXINT'),
                'tgl_transaksi' =>  date('Y-m-d H:i:s'),                
                'jumlah' => 1,   
            );

            if( in_array($_POST['jenis_pendaftaran'], array(1,4)) ){
                if($kode_perusahaan != 120){
                    $this->tarif->insert_tarif_APD($datatarif, 8);
                }
            }

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

                    $kode_perjanjian = $this->master->get_kode_perjanjian( date_create( date('Y-m-d H:i:s') ) );
                    $udpate_data = array(
                        'kode_perjanjian' => $kode_perjanjian,
                        'unique_code_counter' => $this->master->get_max_number('tc_pesanan', 'unique_code_counter'),
                        );
                    $this->db->update('tc_pesanan', $udpate_data, array('id_tc_pesanan' => $this->input->post('id_tc_pesanan') ) );

                }else{
                    $this->db->update('tc_pesanan', array('tgl_masuk' => date('Y-m-d H:i:s') ), array('id_tc_pesanan' => $this->input->post('id_tc_pesanan') ) );
                }
                // update kuota dokter used
                $this->logs->update_status_kuota(array('kode_dokter' => $datapoli['kode_dokter'], 'kode_spesialis' => $datapoli['kode_bagian'], 'tanggal' => date('Y-m-d'), 'keterangan' => null, 'flag' => 'perjanjian', 'status' => NULL ), 1);

            }


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

                // get detail data
                $dt = $this->Reg_klinik->get_by_id($no_registrasi);
                return (array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'no_mr' => $no_mr, 'no_registrasi' => $no_registrasi, 'type_pelayanan' => 'Rawat Jalan', 'dokter' => $dt->nama_pegawai, 'poli' => $dt->nama_bagian, 'nasabah' => $dt->nama_perusahaan, 'nama_pasien' => $dt->nama_pasien ));
            }
        
        }

    }
    

}

/* End of file empty_module.php */
/* Location: ./application/modules/empty_module/controllers/empty_module.php */

