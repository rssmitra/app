<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ws_index extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'ws_bpjs/Ws_index');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            //echo 'Session Expired !'; exit;
        }
        
        /*load model*/
        $this->load->model('ws_bpjs/Ws_index_model', 'Ws_index');
        
        /*enable profiler*/
        $this->output->enable_profiler(false);
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'Title';
        $this->kode_faskses = '0112R034';

    }

    public function index() { 
        clearstatcache();
        $mod = $this->input->get('modWs');
        /*define variable data*/
        $data = array(
            'title' => 'Bridging BPJS Versi 2.0',
            'mod' => $mod,
            'breadcrumbs' => $this->breadcrumbs->show()
        );
        if( $this->input->get('sep') ){
            $data_sep = $this->Ws_index->findSepFromLocal( $this->input->get('sep') );
            $response = $data_sep->response; 
            $data['value'] = $response;
            /*get data peserta*/
            $peserta = $this->Ws_index->getData("Peserta/nokartu/".$response->noKartu."/tglSEP/".$response->tglSep."");
            $data['peserta'] = $peserta->response->peserta ;

        }
        /*load view index*/
        $this->load->view('Ws_index/'.$mod.'', $data);
    }

    public function getRef() { 
        /*modul*/
        $ref = (string)$this->input->get('ref');
        /*parameter*/
        $keyword = $this->input->get('keyword');
        /*get data from api*/
        $data = $this->Ws_index->$ref();
        
        echo json_encode($data);

    }

    public function searchRujukan()
    {
        // print_r($_POST);die;
        $this->load->library('form_validation');

        $val = $this->form_validation;

        $val->set_rules('keyvalue', 'No Rujukan', 'trim|required');
        $val->set_rules('jenis_faskes', 'Jenis Faskes', 'trim|required');
        $val->set_rules('flag', 'Flag', 'trim|required');

        $val->set_message('required', "Silahkan isi field \"%s\"");
        $val->set_message('integer', "Field \"%s\" harus diisi dengann angka");

        if ($val->run() == FALSE)
        {
            $val->set_error_delimiters('<div style="color:white">', '</div>');
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            $this->db->trans_begin();
            $result = $this->Ws_index->searchRujukan();
            $response = isset($result['response']) ? $result : false;

            if($response == false){
                echo json_encode(array('status' => 0, 'message' => 'Error API ! Silahkan cek koneksi anda!'));
                exit;
            }

            if($response['response']->metaData->code == 200){

                $response = $result['data']->rujukan;
                $peserta = $response->peserta;
                $result_data['rujukan'] = $response;
                $result_data['peserta'] = $response->peserta;
                $result_data['diagnosa'] = $response->diagnosa;
                $result_data['pelayanan'] = $response->pelayanan;
                $result_data['poliRujukan'] = $response->poliRujukan;
                $result_data['provPerujuk'] = $response->provPerujuk;

                // cek no kartu bpjs
                // if($result_data['peserta']->noKartu != $_POST['noKartuBPJS']){
                //     echo json_encode(array('status' => 201, 'message' => 'No Kartu BPJS Pasien dari Data Rujukan tidak sesuai dengan data Perjanjian Pasien'));
                // }

                echo json_encode(array('status' => $result['response']->metaData->code, 'message' => $result['response']->metaData->message, 'result' => $result_data));

            }else{

                echo json_encode(array('status' => $result['response']->metaData->code, 'message' => $result['response']->metaData->message));

            }

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
        if ($this->input->post('kodePoliHidden') != 'IGD') {
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
                    'tglSep' => $val->set_value('tglSEP'),
                    'ppkPelayanan' => $this->kode_faskses, 
                    'jnsPelayanan' => $val->set_value('jnsPelayanan'),
                    'klsRawat' => array(
                        'klsRawatHak' => ( $val->set_value('jnsPelayanan') == 1 ) ? $val->set_value('kelasRawat') : "3",
                        'klsRawatNaik' => "",
                        'pembiayaan' => "",
                        'penanggungJawab' => ""
                    ),
                    'noMR' => $val->set_value('noMR'),
                    'rujukan' => array(
                        'asalRujukan' => ($val->set_value('jenis_faskes') == 'pcare') ? 1 : 2 ,
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
                            "tglKejadian" => $val->set_value('tglKejadian'),
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
                    'tujuanKunj' => $_POST['tujuanKunj'],
                    'flagProcedure' => $_POST['flagProcedure'],
                    'kdPenunjang' => $_POST['kdPenunjang'],
                    'assesmentPel' => $_POST['assesmentPel'],
                    'skdp' => array('noSurat' => $val->set_value('noSuratSKDP'), "kodeDPJP" => $val->set_value('KodedokterDPJP') ),
                    'dpjpLayan' => $val->set_value('KodedokterDPJP'),
                    'noTelp' => $val->set_value('noTelp'),
                    'user' => $val->set_value('user'),
                    ),
                ),
        );
        
        $result = $this->Ws_index->insertSep($data);

        $response = isset($result['response']) ? $result : false;

        if($response == false){
            echo json_encode(array('status' => 0, 'message' => 'Error API ! Silahkan cek koneksi anda!'));
            exit;
        }

        if($response['response']->metaData->code == 200){

            // print_r($response);die;
            /*simpan data sep*/
            $sep = $response['data']->sep;
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
                'asalRujukan' => ($val->set_value('jenis_faskes') == 'pcare') ? 1 : 2 ,
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
            
            echo json_encode( array('status' => $response['response']->metaData->code, 'message' => $response['response']->metaData->message, 'result' => $sep, 'no_sep' => $sep->noSep, 'data' => $response['data'] ) );

        }else{
            echo json_encode(array('status' => $response['response']->metaData->code, 'message' => $response['response']->metaData->message));
        }
                
    }

    public function get_data_kunjungan()
    {
        /*get data from model*/
        
        $data = array();
        $list = $this->Ws_index->get_datatables_kunjungan();
        // echo '<pre>';print_r($list);die;
          if ($list['response']->metaData->code ==  200) {
            # code...
            $no = 0;
            foreach ($list['data']->sep as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center">
                        <a href="#" title="Delete SEP" class="btn btn-xs btn-danger" onclick="delete_sep('."'".$row_list->noSep."'".')"><i class="fa fa-times-circle"></i></a>
                        <a href="#" title="Update SEP" class="btn btn-xs btn-success" onclick="getMenu('."'ws_bpjs/ws_index?modWs=DetailSEP&sep=".$row_list->noSep."'".')"><i class="fa fa-edit"></i></a>
                        <a href="#" title="View SEP" class="btn btn-xs btn-primary" onclick="view_sep('."'".$row_list->noSep."'".')"><i class="fa fa-eye"></i></a>
                     </div>';
            $row[] = strtoupper($row_list->nama);
            $row[] = $row_list->noRujukan;
            $row[] = $row_list->noKartu;
            $row[] = $row_list->noSep;
            $row[] = $row_list->tglSep;
            $row[] = $row_list->tglPlgSep;
            $row[] = $row_list->diagnosa;
            $row[] = $row_list->jnsPelayanan;
            $row[] = $row_list->kelasRawat;
            $row[] = $row_list->poli;

            $data[] = $row;
          }
        }
        
        
        $output = array(
                        "draw" => isset($_POST['draw'])?$_POST['draw']:0,
                        "recordsTotal" => 0,
                        "recordsFiltered" => 0,
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    function delete_sep(){
        $no_sep = $this->input->post('ID');
        $jnsPelayanan = $this->input->post('jnsPelayanan');
        $tglSep = $this->tanggal->sqlDateForm($this->input->post('tglSep'));
        $params_string = 'jnsPelayanan='.$jnsPelayanan.'&tglSep='.$tglSep.'';
        /*print_r($params_string);die;*/
        $request = array(
            'request' => array(
                't_sep' => array('noSep' => $no_sep, 'user' => $this->session->userdata('user')->fullname )
                )
            );

        /*print_r($request);die;*/
        $result = $this->Ws_index->deleteSep($no_sep, $request);
        $response = isset($result['response']) ? $result : false;

        if($response['response']->metaData->code == 200){

            // action 
            echo json_encode(array('status' => $result['response']->metaData->code, 'message' => $result['response']->metaData->message ));


        }else{

            echo json_encode(array('status' => $result['response']->metaData->code, 'message' => $result['response']->metaData->message));

        }

    }

    public function view_sep($noSep)
    {   
        $this->load->library('Print_escpos');
        /*data sep*/
        
        $jenis_printer = $this->db->get_where('global_parameter', array('flag' => 'printer_booking'))->result();
        $result = $this->Ws_index->get_data_sep($noSep);
        $response = isset($result['response']) ? $result : false;
        $no_kunjungan = isset($_GET['no_kunjungan'])?$_GET['no_kunjungan']:'';
        $no_registrasi = isset($_GET['no_registrasi'])?$_GET['no_registrasi']:'';
        $no_antrian = isset($_GET['no_antrian'])?$_GET['no_antrian']:'';
        
        if($response == false){
            echo json_encode(array('status' => 0, 'message' => 'Error API ! Silahkan cek koneksi anda!'));
            exit;
        }
        
        if($response['response']->metaData->code == 200){

            $cetakan_ke = $this->Ws_index->count_sep_by_day();
            // get detail kunjungan by sep
            // echo '<pre>'; print_r($row_sep);die;
            $data = array('sep'=>$response['data'], 'cetakan_ke' => $cetakan_ke, 'jenis_printer' => $jenis_printer, 'no_registrasi' => $no_registrasi, 'no_kunjungan' => $no_kunjungan, 'no_antrian' => $no_antrian);
            // print sep
            // $this->print_escpos->print_sep($data);
            $this->load->view('Ws_index/previewSep', $data, false);
            
        }else{

            echo json_encode(array('status' => $result['response']->metaData->code, 'message' => $result['response']->metaData->message));

        }

    }

    public function print_sep($noSep)
    {   
        $this->load->library('Print_escpos');
        /*data sep*/
        
        $result = $this->Ws_index->get_data_sep($noSep);
        $response = isset($result['response']) ? $result : false;
        $registrasi = $this->Ws_index->get_data_registrasi($noSep);
        $rujukan = $this->Ws_index->check_surat_kontrol_by_sep($noSep);
        $response_rujukan = isset($rujukan['response']) ? $rujukan : false;

        if($response == false){
            echo json_encode(array('status' => 0, 'message' => 'Error API ! Silahkan cek koneksi anda!'));
            exit;
        }
        
        if($response['response']->metaData->code == 200){

            $cetakan_ke = $this->Ws_index->count_sep_by_day();
            // get detail kunjungan by sep
            
            // echo '<pre>'; print_r($row_sep);die;
            $data = array('sep'=>$response['data'], 'registrasi' => $registrasi, 'cetakan_ke' => $cetakan_ke, 'rujukan' => $response_rujukan['data']);
            // print sep
            $this->print_escpos->print_sep($data);
            
        }else{

            echo json_encode(array('status' => $result['response']->metaData->code, 'message' => $result['response']->metaData->message));

        }

    }

    public function findSepReturnArray($noSep)
    {   

		$result = $this->Ws_index->findSep($noSep);
        $response = isset($result['response']) ? $result : false;
        // echo '<pre>'; print_r($response['data']);die;

        if($response['response']->metaData->code == 200){
            return $response['data'];
        }else{
            return false;
        }
        
        

    }

    public function find_data()
    {   
        $output = array("data" => http_build_query($_POST),);
        echo json_encode($output);
    }


    public function show_detail_sep($noSep)
    {   
        $this->load->library('Print_escpos');
        /*data sep*/
        $result = $this->Ws_index->get_data_sep($noSep);
        $response = isset($result['response']) ? $result : false;

        if($response == false){
            echo json_encode(array('status' => 0, 'message' => 'Error API ! Silahkan cek koneksi anda!'));
            exit;
        }
        
        if($response['response']->metaData->code == 200){

            echo json_encode(array('status'=> $result['response']->metaData->code,'message' => $result['response']->metaData->message,'data'=> $result['data']));
            
        }else{

            echo json_encode(array('status' => $result['response']->metaData->code, 'message' => $result['response']->metaData->message));

        }

    }

    public function find_sep_internal()
    {
        /*get data from model*/
        
        $data = array();
        $result = $this->Ws_index->find_sep_internal();
        $response = isset($result['response']) ? $result : false;

        if($response['response']->metaData->code == 200){

            // action 
            echo json_encode(array('status' => $result['response']->metaData->code, 'message' => $result['response']->metaData->message, 'data' => $response['data'] ));


        }else{

            echo json_encode(array('status' => $result['response']->metaData->code, 'message' => $result['response']->metaData->message));

        }
        
    }



    // ======================= modul rencana kontrol ========================= //

    public function insertSuratKontrol()
    {
        
        $this->load->library('form_validation');
        $val = $this->form_validation;

        if($_POST['jnsPelayanan'] == 2){
            $val->set_rules('noSEP', 'No SEP', 'trim|required');
        }

        if($_POST['jnsPelayanan'] == 1){
            $val->set_rules('noKartu', 'No Kartu BPJS', 'trim|required');
        }

        $val->set_rules('KodedokterDPJP', 'Kode Dokter', 'trim|required');
        $val->set_rules('kodePoliHidden', 'Poli/Klinik', 'trim|required');
        /*endcob*/
        
        $val->set_message('required', "Silahkan isi field \"%s\"");
        $val->set_message('integer', "Field \"%s\" harus diisi dengann angka");

        if ($val->run() == FALSE)
        {
            $val->set_error_delimiters('<div style="color:white">', '</div>');
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {    
            // echo '<pre>';print_r($_POST);die;
            // cek no surat kontrol
            $noSuratKontrol = isset($_POST['noSuratKontrol'])?$_POST['noSuratKontrol']:0;
            $check_surat_kontrol = $this->Ws_index->check_surat_kontrol_by_no($noSuratKontrol);
            $response_dt = isset($check_surat_kontrol['response']) ? $check_surat_kontrol : false;

            if( $response_dt['response']->metaData->code == 201 ){

                if($_POST['jnsPelayanan'] == 2){
                    $data = array(
                        'request' => array(
                            'noSEP' => trim($_POST['noSEP']),
                            'kodeDokter' => $_POST['KodedokterDPJP'],
                            'poliKontrol' => $_POST['kodePoliHidden'],
                            'tglRencanaKontrol' => $_POST['tglRencanaKontrol'],
                            'user' => 'ws-'.$this->session->userdata('user')->fullname,
                        ),
                    );
                    // echo '<pre>';print_r($data);die;
                    $result = $this->Ws_index->insertRencanaKontrol($data);
                }

                if($_POST['jnsPelayanan'] == 1){
                    $data = array(
                        'request' => array(
                            'noKartu' => trim($_POST['noKartu']),
                            'kodeDokter' => $_POST['KodedokterDPJP'],
                            'poliKontrol' => $_POST['kodePoliHidden'],
                            'tglRencanaKontrol' => $_POST['tglRencanaKontrol'],
                            'user' => 'ws-'.$this->session->userdata('user')->fullname,
                        ),
                    );
                    $result = $this->Ws_index->insertRencanaKontrolRI($data);
                }

            }else{

                if($_POST['jnsPelayanan'] == 2){
                    $data = array(
                        'request' => array(
                            'noSuratKontrol' => trim($_POST['noSuratKontrol']),
                            'noSEP' => trim($_POST['noSEP']),
                            'kodeDokter' => $_POST['KodedokterDPJP'],
                            'poliKontrol' => $_POST['kodePoliHidden'],
                            'tglRencanaKontrol' => $_POST['tglRencanaKontrol'],
                            'user' => 'ws-'.$this->session->userdata('user')->fullname,
                        ),
                    );
                    // print_r($data);die;
                    $result = $this->Ws_index->updateRencanaKontrol($data);
                }

                if($_POST['jnsPelayanan'] == 1){
                    $data = array(
                        'request' => array(
                            'noSPRI' => trim($_POST['noSuratKontrol']),
                            'kodeDokter' => $_POST['KodedokterDPJP'],
                            'poliKontrol' => $_POST['kodePoliHidden'],
                            'tglRencanaKontrol' => $_POST['tglRencanaKontrol'],
                            'user' => 'ws-'.$this->session->userdata('user')->fullname,
                        ),
                    );
                    // print_r($data);die;
                    $result = $this->Ws_index->updateRencanaKontrolRI($data);
                }

            }
            
            $response = isset($result['response']) ? $result : false;

            if($response == false){
                echo json_encode(array('status' => 0, 'message' => 'Error API ! Silahkan cek koneksi anda!'));
                exit;
            }
            
            if($response['response']->metaData->code == 200){

                // get kode dokter dpjp internal
                $dokter_internal = $this->db->get_where('mt_karyawan', array('kode_dokter_bpjs' => $_POST['KodedokterDPJP']))->row();
                // get kode poli internal
                $poli_internal = $this->db->get_where('mt_bagian', array('kode_poli_bpjs' => $_POST['kodePoliHidden']) )->row();
                if($noSuratKontrol != 0){
                    // update kode_perjanjian tc_pesanan
                    $this->db->where('kode_perjanjian', $noSuratKontrol)->update('tc_pesanan', array('kode_perjanjian' => $response['data']->noSuratKontrol, 'is_bridging' => 1, 'tgl_pesanan' => $_POST['tglRencanaKontrol'], 'kode_dokter' => $dokter_internal->kode_dokter, 'no_poli' => $poli_internal->kode_bagian ) );
                }
                

                echo json_encode(array('status' => $result['response']->metaData->code, 'message' => $result['response']->metaData->message, 'data' => $response['data']));
                // $this->load->view('Ws_index/previewRencanaKontrol', $data, false);
                
            }else{

                echo json_encode(array('status' => $result['response']->metaData->code, 'message' => $result['response']->metaData->message));

            }

        }
    }

    public function get_data_rencana_kontrol()
    {
        /*get data from model*/
        
        $data = array();
        $list = $this->Ws_index->get_data_rencana_kontrol();
        // echo '<pre>';print_r($list);die;
          if ($list['response']->metaData->code ==  200) {
            # code...
            $no = 0;
            foreach ($list['data']->list as $row_list) {
            $no++;
            $row = array();
            
            $row[] = '<div class="center">'.$no.'</div>';
            $row[] = $row_list->noSuratKontrol;
            $row[] = '<b>'.$row_list->noKartu.'</b><br>'.$row_list->nama;
            $row[] = $row_list->jnsPelayanan;
            $row[] = $row_list->namaJnsKontrol.'<br>'.$row_list->tglTerbitKontrol;
            $row[] = $row_list->tglRencanaKontrol;
            $row[] = $row_list->noSepAsalKontrol.'<br>'.$row_list->tglSEP;
            $row[] = $row_list->namaPoliAsal;
            $row[] = $row_list->namaPoliTujuan;
            $row[] = $row_list->namaDokter;
            $row[] = '<div class="center">
                        <a href="#" title="Delete SEP" class="btn btn-xs btn-danger" onclick="delete_surat_kontrol('."'".$row_list->noSuratKontrol."'".')"><i class="fa fa-times-circle"></i></a>
                        <a href="#" title="Update SEP" class="btn btn-xs btn-success" onclick="show_data_surat_kontrol('."'".$row_list->noSuratKontrol."'".')"><i class="fa fa-edit"></i></a>
                        <a href="#" title="View SEP" class="btn btn-xs btn-primary" onclick="view_sep('."'".$row_list->noSuratKontrol."'".')"><i class="fa fa-eye"></i></a>
                     </div>';

            $data[] = $row;
          }
        }
        
        
        $output = array(
                        "draw" => isset($_POST['draw'])?$_POST['draw']:0,
                        "recordsTotal" => 0,
                        "recordsFiltered" => 0,
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    function delete_surat_kontrol(){
        $no_surat_kontrol = $this->input->post('ID');
        /*print_r($params_string);die;*/
        $request = array(
            'request' => array(
                't_suratkontrol' => array('noSuratKontrol' => $no_surat_kontrol, 'user' => $this->session->userdata('user')->fullname )
                )
            );

        /*print_r($request);die;*/
        $result = $this->Ws_index->deleteSuratKontrol($no_surat_kontrol, $request);

        $response = isset($result['response']) ? $result : false;

        if($response['response']->metaData->code == 200){

            // action 
            echo json_encode(array('status' => $result['response']->metaData->code, 'message' => $result['response']->metaData->message ));


        }else{

            echo json_encode(array('status' => $result['response']->metaData->code, 'message' => $result['response']->metaData->message));

        }

    }

    function show_data_surat_kontrol(){
        $no_surat_kontrol = $this->input->post('ID');
        $result = $this->Ws_index->editSuratKontrol($no_surat_kontrol);
        // print_r($result);die;

        $response = isset($result['response']) ? $result : false;

        if($response['response']->metaData->code == 200){

            // action 
            echo json_encode(array('status' => $result['response']->metaData->code, 'message' => $result['response']->metaData->message, 'data' => $response['data'] ));


        }else{

            echo json_encode(array('status' => $result['response']->metaData->code, 'message' => $result['response']->metaData->message));

        }

    }

    public function get_jadwal_praktek_dokter()
    {
        /*get data from model*/
        
        $data = array();
        if(isset($_GET['KdPoli'])) :
            $list = $this->Ws_index->get_jadwal_praktek_dokter();
            // echo '<pre>';print_r($list);die;
            if ($list['response']->metaData->code ==  200) {
                # code...
                $no = 0;
                foreach ($list['data']->list as $row_list) {
                    $no++;
                    $row = array();
                    
                    $row[] = '<div class="center">'.$no.'</div>';
                    $row[] = $row_list->kodeDokter;
                    $row[] = $row_list->namaDokter;
                    $row[] = $row_list->jadwalPraktek;
                    $row[] = $row_list->kapasitas;
                    $data[] = $row;
                }
            }
        endif;
        
        
        $output = array(
                        "draw" => isset($_POST['draw'])?$_POST['draw']:0,
                        "recordsTotal" => 0,
                        "recordsFiltered" => 0,
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function get_jadwal_praktek_dokter_html()
    {
        /*get data from model*/
        
        $html = '';
        $html .= '<div style="margin-left: 10%; width: 80%">';
        $html .= '<span style="font-weight: bold">JADWAL DOKTER</span>';
        $html .= '<table class="table">';
        $html .= '<tr>';
        $html .= '<th class="center" width="30px">No</th>';
        $html .= '<th width="50px">Kode Dokter</th>';
        $html .= '<th width="100px">Nama Dokter</th>';
        $html .= '<th width="80px">Jam Praktek</th>';
        $html .= '<th width="80px">Kapasitas</th>';
        $html .= '<th width="30px"></th>';
        $html .= '</tr>';
        
        if(isset($_GET['KdPoli'])) :
            $list = $this->Ws_index->get_jadwal_praktek_dokter();
            // echo '<pre>';print_r($list);die;
            if ($list['response']->metaData->code ==  200) {
                # code...
                $no = 0;
                foreach ($list['data']->list as $row_list) {
                    $no++;
                    $row = array();
                    
                    $html .= '<tr>';
                    $html .= '<td align="center">'.$no.'</td>';
                    $html .= '<td>'.$row_list->kodeDokter.'</td>';
                    $html .= '<td>'.$row_list->namaDokter.'</td>';
                    $html .= '<td>'.$row_list->jadwalPraktek.'</td>';
                    $html .= '<td>'.$row_list->kapasitas.'</td>';
                    $html .= '<td align="center"><a href="#" class="btn btn-xs btn-success" onclick="selected_jadwal_dokter('."'".$row_list->kodeDokter."'".', '."'".$row_list->namaDokter."'".')"><i class="fa fa-check-square-o bigger-120"></i></a></td>';
                    $html .= '</tr>';
                }
                
            }
        endif;
        $html .= '</table>';
        $html .= '</div>';
        
        
        $output = array("html" => $html);
        //output to json format
        echo json_encode($output);
    }

    public function get_rencana_kontrol_poli()
    {
        /*get data from model*/
        
        $data = array();
        if(isset($_GET['JnsKontrol'])) :
            $list = $this->Ws_index->get_rencana_kontrol_poli();
            // echo '<pre>';print_r($list);die;
            if ($list['response']->metaData->code ==  200) {
                # code...
                $no = 0;
                foreach ($list['data']->list as $row_list) {
                    $no++;
                    $row = array();
                    
                    $row[] = '<div class="center">'.$no.'</div>';
                    $row[] = '<a href="#" onclick="show_jadwal_dokter('."'".$row_list->kodePoli."'".')">'.$row_list->kodePoli.'</a>';
                    $row[] = $row_list->namaPoli;
                    $row[] = $row_list->kapasitas;
                    $row[] = $row_list->jmlRencanaKontroldanRujukan;
                    $row[] = $row_list->persentase;
                    $data[] = $row;
                }
            }
        endif;
        
        
        $output = array(
                        "draw" => isset($_POST['draw'])?$_POST['draw']:0,
                        "recordsTotal" => 0,
                        "recordsFiltered" => 0,
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function get_rencana_kontrol_poli_with_detail()
    {
        /*get data from model*/
        
        $data = array();
        if(isset($_GET['JnsKontrol'])) :
            $list = $this->Ws_index->get_rencana_kontrol_poli();
            // echo '<pre>';print_r($list);die;
            if(isset($list['response']->metaData)){
                if ($list['response']->metaData->code ==  200) {
                    # code...
                    $no = 0;
                    foreach ($list['data']->list as $row_list) {
                        $no++;
                        $row = array();
                        
                        $row[] = '<div class="center">'.$no.'</div>';
                        $row[] = '<div class="center"></div>';
                        $row[] = $row_list->kodePoli;
                        $row[] = '<a href="#" onclick="show_jadwal_dokter('."'".$row_list->kodePoli."'".')">'.$row_list->kodePoli.'</a>';
                        $row[] = strtoupper($row_list->namaPoli);
                        $row[] = $row_list->kapasitas;
                        $row[] = $row_list->jmlRencanaKontroldanRujukan;
                        $row[] = $row_list->persentase;
                        $data[] = $row;
                    }
                }
            }
            
        endif;
        
        
        $output = array(
                        "draw" => isset($_POST['draw'])?$_POST['draw']:0,
                        "recordsTotal" => 0,
                        "recordsFiltered" => 0,
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function find_surat_kontrol_by_noka()
    {
        /*get data from model*/
        
        $data = array();
        if(isset($_GET['JnsKontrol'])) :
            $list = $this->Ws_index->find_surat_kontrol_by_noka();
            // echo '<pre>';print_r($list);die;
            if ($list['response']->metaData->code ==  200) {
                # code...
                $no = 0;
                foreach ($list['data']->list as $row_list) {
                    $no++;
                    $row = array();
                    
                    $row[] = '<div class="center">'.$no.'</div>';
                    $row[] = $row_list->nama;
                    $row[] = $row_list->noKartu;
                    $row[] = $row_list->noSuratKontrol;
                    $row[] = $row_list->jnsPelayanan;
                    $row[] = $row_list->namaJnsKontrol;
                    $row[] = $row_list->tglRencanaKontrol;
                    $row[] = $row_list->noSepAsalKontrol;
                    $row[] = $row_list->namaPoliTujuan;
                    $row[] = $row_list->namaDokter;
                    $data[] = $row;
                }
            }
        endif;
        
        
        $output = array(
                        "draw" => isset($_POST['draw'])?$_POST['draw']:0,
                        "recordsTotal" => 0,
                        "recordsFiltered" => 0,
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function find_rencana_kontrol_by_sep()
    {
        /*get data from model*/
        
        $data = array();
        $result = $this->Ws_index->find_surat_kontrol_by_sep();
        $response = isset($result['response']) ? $result : false;

        if($response['response']->metaData->code == 200){

            // action 
            echo json_encode(array('status' => $result['response']->metaData->code, 'message' => $result['response']->metaData->message, 'data' => $response['data'] ));


        }else{

            echo json_encode(array('status' => $result['response']->metaData->code, 'message' => $result['response']->metaData->message));

        }
        
    }

    public function find_rencana_kontrol_by_no()
    {
        /*get data from model*/
        
        $data = array();
        $result = $this->Ws_index->find_surat_kontrol_by_no();
        $response = isset($result['response']) ? $result : false;

        if($response['response']->metaData->code == 200){

            // action 
            echo json_encode(array('status' => $result['response']->metaData->code, 'message' => $result['response']->metaData->message, 'data' => $response['data'] ));


        }else{

            echo json_encode(array('status' => $result['response']->metaData->code, 'message' => $result['response']->metaData->message));

        }
        
    }

    // ======================= end modul rencana kontrol ========================= //




    // ======================== modul rujukan ========================== //
    public function insertRujukan()
    {
        
        $this->load->library('form_validation');
        $val = $this->form_validation;
        $val->set_rules('noSep', 'No SEP', 'trim|required');
        $val->set_rules('tglRujukan', 'Tanggal Rujukan', 'trim|required');
        $val->set_rules('tglRencanaKunjungan', 'Tanggal Rencana Kunjungan', 'trim|required');
        $val->set_rules('ppkDirujuk', 'Faskes Dirujuk', 'trim|required');
        $val->set_rules('jnsPelayanan', 'Jenis Pelayanan', 'trim|required');
        $val->set_rules('catatan', 'Catatan', 'trim|xss_clean');
        $val->set_rules('diagRujukan', 'Diagnosa', 'trim|required');
        $val->set_rules('tipeRujukan', 'Tipe Rujukan', 'trim|required');
        $val->set_rules('poliRujukan', 'Poli Rujukan', 'trim|required');
        $val->set_rules('user', 'Pengguna', 'trim|required');

        $val->set_message('required', "Silahkan isi field \"%s\"");
        $val->set_message('integer', "Field \"%s\" harus diisi dengann angka");

        if ($val->run() == FALSE)
        {
            $val->set_error_delimiters('<div style="color:white">', '</div>');
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            $this->db->trans_begin();

            /*PPK Dirujuk*/
            $impl_ppk = explode(':', $val->set_value('ppkDirujuk'));
            $impl_poli = explode(':', $val->set_value('poliRujukan'));
            $impl_diag = explode(':', $val->set_value('diagRujukan'));

            $data = array(
                'request' => array(
                    't_rujukan' => array(
                        'noSep' => $val->set_value('noSep'),
                        'tglRujukan' => $val->set_value('tglRujukan'),
                        'tglRencanaKunjungan' => $val->set_value('tglRencanaKunjungan'),
                        'ppkDirujuk' => trim($impl_ppk[0]),
                        'jnsPelayanan' => $val->set_value('jnsPelayanan'),
                        'catatan' => $val->set_value('catatan'),
                        'diagRujukan' => trim($impl_diag[0]),
                        'tipeRujukan' => $val->set_value('tipeRujukan'),
                        'poliRujukan' => trim($impl_poli[0]),
                        'user' => $val->set_value('user'),
                        ),
                    ),
                );

            $result = $this->Ws_index->insertRujukan($data);
            
            $response = isset($result['response']) ? $result : false;

            if($response == false){
                echo json_encode(array('status' => 0, 'message' => 'Error API ! Silahkan cek koneksi anda!'));
                exit;
            }
            
            if($response['response']->metaData->code == 200){

                echo json_encode(array('status' => $result['response']->metaData->code, 'message' => $result['response']->metaData->message, 'data' => $response));
                
            }else{

                echo json_encode(array('status' => $result['response']->metaData->code, 'message' => $result['response']->metaData->message));

            }
            
        }
    }

    public function insertRujukanKhusus()
    {
        
        $this->load->library('form_validation');
        $val = $this->form_validation;
        $val->set_rules('noRujukan', 'No Rujukan', 'trim|required');
        $val->set_rules('diagnosa_primer', 'Diagnosa Primer', 'trim|required');
        $val->set_rules('diagnosa_sekunder', 'Diagnosa Sekunder', 'trim|required');
        $val->set_rules('procedure', 'Prosedur', 'trim|required');
        $val->set_rules('user', 'User', 'trim|required');

        $val->set_message('required', "Silahkan isi field \"%s\"");
        $val->set_message('integer', "Field \"%s\" harus diisi dengann angka");

        if ($val->run() == FALSE)
        {
            $val->set_error_delimiters('<div style="color:white">', '</div>');
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            $this->db->trans_begin();

            /*PPK Dirujuk*/
            $diagnosa_primer = explode(':', $val->set_value('diagnosa_primer'));
            $diagnosa_sekunder = explode(':', $val->set_value('diagnosa_sekunder'));
            $procedure = explode('-', $val->set_value('procedure'));

            $data = array(
                        'noRujukan' => $val->set_value('noRujukan'),
                        'diagnosa' => array(
                            array('kode' => 'P;'.trim($diagnosa_primer[0])),
                            array('kode' => 'S;'.trim($diagnosa_sekunder[0]))
                        ),
                        'procedure' => array(
                            'kode' => trim($procedure[0]),
                        ),
                        'user' => $val->set_value('user'),
                    );
            
            // echo '<pre>'; print_r(json_encode($data));die;
            $result = $this->Ws_index->insertRujukanKhusus($data);
            
            $response = isset($result['response']) ? $result : false;

            if($response == false){
                echo json_encode(array('status' => 0, 'message' => 'Error API ! Silahkan cek koneksi anda!'));
                exit;
            }
            
            if($response['response']->metaData->code == 200){

                echo json_encode(array('status' => $result['response']->metaData->code, 'message' => $result['response']->metaData->message, 'data' => $response));
                
            }else{

                echo json_encode(array('status' => $result['response']->metaData->code, 'message' => $result['response']->metaData->message));

            }
            
        }
    }

    public function updateRujukan()
    {
        
        $this->load->library('form_validation');
        $val = $this->form_validation;
        $val->set_rules('noRujukan', 'No Rujukan', 'trim|required');
        $val->set_rules('noSep', 'No SEP', 'trim|required');
        $val->set_rules('tglRujukan', 'Tanggal Rujukan', 'trim|required');
        $val->set_rules('tglRencanaKunjungan', 'Tanggal Rencana Kunjungan', 'trim|required');
        $val->set_rules('ppkDirujuk', 'Faskes Dirujuk', 'trim|required');
        $val->set_rules('jnsPelayanan', 'Jenis Pelayanan', 'trim|required');
        $val->set_rules('catatan', 'Catatan', 'trim|xss_clean');
        $val->set_rules('diagRujukan', 'Diagnosa', 'trim|required');
        $val->set_rules('tipeRujukan', 'Tipe Rujukan', 'trim|required');
        $val->set_rules('poliRujukan', 'Poli Rujukan', 'trim|required');
        $val->set_rules('user', 'Pengguna', 'trim|required');

        $val->set_message('required', "Silahkan isi field \"%s\"");
        $val->set_message('integer', "Field \"%s\" harus diisi dengann angka");

        if ($val->run() == FALSE)
        {
            $val->set_error_delimiters('<div style="color:white">', '</div>');
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            $this->db->trans_begin();

            /*PPK Dirujuk*/
            $impl_ppk = explode(':', $val->set_value('ppkDirujuk'));
            $impl_poli = explode(':', $val->set_value('poliRujukan'));
            $impl_diag = explode(':', $val->set_value('diagRujukan'));

            $data = array(
                'request' => array(
                    't_rujukan' => array(
                        'noRujukan' => $val->set_value('noRujukan'),
                        'tglRujukan' => $val->set_value('tglRujukan'),
                        'tglRencanaKunjungan' => $val->set_value('tglRencanaKunjungan'),
                        'ppkDirujuk' => trim($impl_ppk[0]),
                        'jnsPelayanan' => $val->set_value('jnsPelayanan'),
                        'catatan' => $val->set_value('catatan'),
                        'diagRujukan' => trim($impl_diag[0]),
                        'tipeRujukan' => $val->set_value('tipeRujukan'),
                        'poliRujukan' => trim($impl_poli[0]),
                        'user' => $val->set_value('user'),
                        ),
                    ),
                );
            // echo '<pre>';print_r($data);die;
            $result = $this->Ws_index->updateRujukan($data);
            
            $response = isset($result['response']) ? $result : false;

            if($response == false){
                echo json_encode(array('status' => 0, 'message' => 'Error API ! Silahkan cek koneksi anda!'));
                exit;
            }
            
            if($response['response']->metaData->code == 200){

                echo json_encode(array('status' => $result['response']->metaData->code, 'message' => $result['response']->metaData->message, 'data' => $response));
                
            }else{

                echo json_encode(array('status' => $result['response']->metaData->code, 'message' => $result['response']->metaData->message));

            }
            
        }
    }

    public function get_data_rujukan_keluar_rs()
    {
        /*get data from model*/
        
        $data = array();
        $list = $this->Ws_index->get_data_rujukan_keluar_rs();
        // echo '<pre>';print_r($list);die;
          if ($list['response']->metaData->code ==  200) {
            # code...
            $no = 0;
            foreach ($list['data']->list as $row_list) {
            $no++;
            $row = array();
            
            $row[] = '<div class="center">'.$no.'</div>';
            $row[] = $row_list->noRujukan;
            $row[] = $row_list->tglRujukan;
            $row[] = $row_list->noSep;
            $row[] = $row_list->noKartu;
            $row[] = $row_list->nama;
            $row[] = $row_list->ppkDirujuk.' - '.$row_list->namaPpkDirujuk;
            $row[] = '<div class="center"><a href="#" class="btn btn-xs btn-success" onclick="getMenu('."'ws_bpjs/Ws_index/edit_rujukan_keluar_rs?noRujukan=".$row_list->noRujukan."'".')"><i class="fa fa-pencil"></i> edit </a></div>';

            $data[] = $row;
          }
        }
        
        
        $output = array(
                        "draw" => isset($_POST['draw'])?$_POST['draw']:0,
                        "recordsTotal" => 0,
                        "recordsFiltered" => 0,
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function edit_rujukan_keluar_rs()
    {
        /*get data from model*/
        
        $data = array();
        $result = $this->Ws_index->edit_rujukan_keluar_rs();
        $response = isset($result['response']) ? $result : false;

        if($response['response']->metaData->code == 200){

            $data = array(
                'title' => 'Bridging BPJS Versi 2.0',
                'breadcrumbs' => $this->breadcrumbs->show(),
                'value' => $response['data'],
            );
            // echo '<pre>';print_r($data);die;
            $this->load->view('Ws_index/updateRujukanKeluarRS', $data);


        }else{

            echo json_encode(array('status' => $result['response']->metaData->code, 'message' => $result['response']->metaData->message));

        }
        
    }

    public function list_rujukan_khusus()
    {
        /*get data from model*/
        
        $data = array();
        if(isset($_GET['Bulan'])) :
            $list = $this->Ws_index->list_rujukan_khusus();
            // echo '<pre>';print_r($list);die;
            if ($list['response']->metaData->code ==  200) {
                # code...
                $no = 0;
                foreach ($list['data']->list as $row_list) {
                    $no++;
                    $row = array();
                    
                    $row[] = '<div class="center">'.$no.'</div>';
                    $row[] = $row_list->idrujukan;
                    $row[] = $row_list->norujukan;
                    $row[] = $row_list->nokapst;
                    $row[] = $row_list->nmpst;
                    $row[] = $row_list->diagppk;
                    $row[] = $row_list->tglrujukan_awal;
                    $row[] = $row_list->tglrujukan_berakhir;
                    $data[] = $row;
                }
            }
        endif;
        
        
        $output = array(
                        "draw" => isset($_POST['draw'])?$_POST['draw']:0,
                        "recordsTotal" => 0,
                        "recordsFiltered" => 0,
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function list_spesialistik_rujukan()
    {
        /*get data from model*/
        
        $data = array();
        if(isset($_GET['Bulan'])) :
            $list = $this->Ws_index->list_spesialistik_rujukan();
            // echo '<pre>';print_r($list);die;
            if ($list['response']->metaData->code ==  200) {
                # code...
                $no = 0;
                foreach ($list['data']->list as $row_list) {
                    $no++;
                    $row = array();
                    
                    $row[] = '<div class="center">'.$no.'</div>';
                    $row[] = $row_list->idrujukan;
                    $row[] = $row_list->norujukan;
                    $row[] = $row_list->nokapst;
                    $row[] = $row_list->nmpst;
                    $row[] = $row_list->diagppk;
                    $row[] = $row_list->tglrujukan_awal;
                    $row[] = $row_list->tglrujukan_berakhir;
                    $data[] = $row;
                }
            }
        endif;
        
        
        $output = array(
                        "draw" => isset($_POST['draw'])?$_POST['draw']:0,
                        "recordsTotal" => 0,
                        "recordsFiltered" => 0,
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }




































































    function prosesUpdateSep($val){
        /*insert sep*/
        $data = array(
                'request' => array(
                    't_sep' => array(
                        'noSep' => $_POST['noSep'],
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
                        'katarak' => array('katarak' => $val->set_value('katarak')?$val->set_value('katarak'):"0" ),
                        'skdp' => array('noSurat' => $val->set_value('noSuratSKDP'), "kodeDPJP" => "34646" ),
                        'jaminan' => array(
                            'lakaLantas' => ($val->set_value('lakalantas'))?$val->set_value('lakalantas'):"0", 
                            'penjamin' => array(
                                "penjamin" => $val->set_value('penjamin'),
                                "tglKejadian" => $this->tanggal->sqlDateForm( $val->set_value('tglKejadian') ),
                                "keterangan" => $val->set_value('keteranganKejadian'),
                                "suplesi" => array(
                                    'suplesi' => $val->set_value('suplesi'),
                                    "noSepSuplesi"  => $val->set_value('noSepSuplesi'),
                                    "lokasiLaka" => array(
                                        'kdPropinsi' => $val->set_value('provinceId'),
                                        'kdKabupaten' => $val->set_value('regencyId'),
                                        'kdKecamatan' => $val->set_value('districtId'),
                                        ),
                                    ),
                                ), 
                        ),
                        'noTelp' => $val->set_value('noTelp'),
                        'user' => $val->set_value('user'),
                        ),
                    ),
                );

        //print_r($data);die;
        $result = $this->Ws_index->updateSep($data);

        if( $result['response']->metaData->code==200 ){
            /*simpan data sep*/
            $update_sep = array(
                'noSep' => $_POST['noSep'],
                'kelasRawat' => ($val->set_value('jnsPelayanan')==1)?$val->set_value('kelasRawat'):"3",
                'noMR' => $val->set_value('noMR'),
                'asalRujukan' => $val->set_value('jenis_faskes'),
                'tglRujukan' => $val->set_value('tglRujukan'),
                'noRujukan' => $val->set_value('noRujukan'),
                'kodePPPKPerujuk' => $val->set_value('kodeFaskesHidden'),
                'catatan' => $val->set_value('catatan'),
                'kodeDiagnosa' => $val->set_value('kodeDiagnosaHidden'),
                'kodePoli' => $val->set_value('kodePoliHidden'),
                'poliEksekutif' => $val->set_value('eksekutif')?$val->set_value('eksekutif'):"0",
                'cob' => $val->set_value('cob')?$val->set_value('cob'):"0",
                'katarak' => $val->set_value('katarak')?$val->set_value('katarak'):"0",
                'noSuratSKDP' => $val->set_value('noSuratSKDP'),
                'kodeDokterDPJP' => "34646",
                'namaDokterDPJP' => "dr. Sumidi, SpB",
                'lakalantas' => ($val->set_value('lakalantas'))?$val->set_value('lakalantas'):"0", 
                "penjamin" => $val->set_value('penjamin'),
                "tglKejadian" => "2018-11-11",
                "keteranganLakaLantas" => $val->set_value('keteranganKejadian'),
                'suplesi' => $val->set_value('suplesi'),
                "noSepSuplesi"  => $val->set_value('noSepSuplesi'),
                'kdPropinsi' => $val->set_value('provinceId'),
                'kdKabupaten' => $val->set_value('regencyId'),
                'kdKecamatan' => $val->set_value('districtId'),
                'noTelp' => $val->set_value('noTelp'),
            );

            $this->Ws_index->insert_tbl_sep('ws_bpjs_sep', $update_sep);
        }

        $response = json_encode(array('status' => $result['response']->metaData->code, 'message' => 'No SEP '.$result->response.' ('.$reresponse->sult->metaData->message.')', 'result' => $result->response, 'redirect' => base_url().'ws_bpjs/ws_index?modWs=HistorySep' ));

        return $response;
    }



    public function updateNoPesertaPerjanjianOperasi(){
        $query = $this->db->query("select a.*,b.no_ktp from tc_pesanan a
                            left join mt_master_pasien b on b.no_mr=a.no_mr
                            where flag='bedah' and a.kode_perusahaan=120 and b.no_ktp is not null and b.no_ktp != '-' and b.no_ktp != ''")->result();

        foreach($query as $row){

            $result = $this->Ws_index->searchMemberByNIK($row->no_ktp, $row->tgl_pesanan);
            print_r($result);die;
        }
        // print_r($result);die;
    }

    public function searchMember()
    {
       
        $this->load->library('form_validation');
        $val = $this->form_validation;
        if( $this->input->post('jenis_kartu')=='bpjs' ){
            $val->set_rules('nokartu', 'No Kartu BPJS', 'trim|required');
        }else{
            $val->set_rules('nokartu', 'NIK', 'trim|required|integer');
        }
        $val->set_rules('jenis_kartu', 'Jenis Kartu', 'trim|required');
        $val->set_rules('tglSEP', 'Tanggal Pelayanan SEP', 'trim|required');

        $val->set_message('required', "Silahkan isi field \"%s\"");
        $val->set_message('integer', "Field \"%s\" harus diisi dengann angka");

        if ($val->run() == FALSE)
        {
            $val->set_error_delimiters('<div style="color:white">', '</div>');
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            $this->db->trans_begin();

            // print_r($_POST);die;
            $result = $this->Ws_index->searchMember();
            if($result != false){
                $response = $result['data']->peserta;
                $profile['nama'] = strtoupper($response->nama);
                $profile['jk'] = $response->sex;
                $profile['nik'] = $response->nik;
                $profile['noKartu'] = $response->noKartu;
                $profile['ppkAsalRujukan'] = $response->provUmum->kdProvider.' : '.$response->provUmum->nmProvider;
                $profile['kodePpkAsalRujukanHidden'] = $response->provUmum->kdProvider;
                $profile['tglLahir'] = $response->tglLahir;
                $profile['umur'] = $response->umur->umurSaatPelayanan;
                $profile['jenisPeserta'] = '[ '.$response->jenisPeserta->kode.' ] '.$response->jenisPeserta->keterangan;
                $profile['statusPeserta'] = '[ '.$response->statusPeserta->kode.' ] '.$response->statusPeserta->keterangan;
                $profile['noMR'] = $response->mr->noMR;
                $profile['hakKelas'] = '[ '.$response->hakKelas->kode.' ] '.$response->hakKelas->keterangan;
            }else{
                $profile = array();
            }
            
            echo json_encode(array('status' => $result['response']->metaData->code, 'message' => $result['response']->metaData->message, 'result' => $profile));

        }
    }

    

    public function searchSep()
    {
       
        $this->load->library('form_validation');

        $val = $this->form_validation;
        $val->set_rules('sep', 'No SEP', 'trim|required');

        $val->set_message('required', "Silahkan isi field \"%s\"");

        if ($val->run() == FALSE)
        {
            $val->set_error_delimiters('<div style="color:white">', '</div>');
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       


            $data_sep = $this->Ws_index->findSepFromLocal( $val->set_value('sep') );
            $response = $data_sep->response; 
            /*get data peserta*/
            $data_peserta = $this->Ws_index->getData("Peserta/nokartu/".$response->noKartu."/tglSEP/".$response->tglSep."");
            $peserta = $data_peserta->response->peserta;
            //echo '<pre>';print_r($peserta);die;

            $result_data['value'] = $response;
            $result_data['peserta'] = $peserta;
            //echo '<pre>';print_r($result_data);die;
            echo json_encode(array('status' => 200, 'message' => 'Sukses', 'result' => $result_data));


        }
    }

    public function update_sep() { 
        clearstatcache();
        /*define variable data*/
        $data = array(
            'title' => 'Update SEP',
            'breadcrumbs' => $this->breadcrumbs->show()
        );
        if( $this->input->get('sep') ){
            $data_sep = $this->Ws_index->findSepFromLocal( $this->input->get('sep') );
            $response = $data_sep->response; 
            $data['value'] = $response;
            /*get data peserta*/
            $peserta = $this->Ws_index->getData("Peserta/nokartu/".$response->noKartu."/tglSEP/".$response->tglSep."");
            $data['peserta'] = $peserta->response->peserta ;

        }
        //echo '<pre>'; print_r($data);die;
        /*load view index*/
        $this->load->view('Ws_index/UpdateSep', $data);
    }

    function generate_barcode($text){
        require APPPATH.'third_party/barcode_generator/BarcodeBase.php';
        require APPPATH.'third_party/barcode_generator/Code128.php';
        $bcode = array();
        $bcode['c128']  = array('name' => 'Code128', 'obj' => new emberlabs\Barcode\Code128());

            foreach($bcode as $k => $value)
            {
                try
                {
                    $bcode[$k]['obj']->setData($text);
                    $bcode[$k]['obj']->setDimensions(300, 30);
                    $bcode[$k]['obj']->draw();
                    $b64 = $bcode[$k]['obj']->base64();

                    $barcode = $this->bcode_img64($b64);
                }
                catch (Exception $e)
                {
                    $barcode = $e->getMessage();
                }
            }
        return $barcode;
    }

    function bcode_img64($b64str){
        return "<img style='margin-left:-28px;' src='data:image/png;base64,$b64str' /><br />";
    }

    public function get_data_history_sep()
    {
        /*get data from model*/
        $list = $this->Ws_index->get_datatables_history_sep();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center">
                        <label class="pos-rel">
                            <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->noSep.'"/>
                            <span class="lbl"></span>
                        </label>
                      </div>';
            $row[] = '<button type="button" class="btn btn-xs btn-danger" onclick="delete_sep('."'".$row_list->noSep."','".$row_list->kodeJnsPelayanan."','".$row_list->tglSep."'".')" ><i class="fa fa-times-circle"></i></button>';
           
            $row[] = '<a href="#" onclick="getMenu('."'ws_bpjs/ws_index/update_sep?sep=".$row_list->noSep."'".')">'.$row_list->noSep.'</a>';
            $row[] = strtoupper($row_list->nama);
            $row[] = $this->tanggal->formatDate($row_list->tglSep);
            $row[] = $this->tanggal->formatDate($row_list->tglPulang);
            $row[] = $row_list->poli.'<br><span style="font-size:11px">'.$row_list->jnsPelayanan.' ('.$row_list->kelasRawat.')</span>';
            $row[] = $row_list->diagnosa;
            $row[] = $row_list->statusPengajuanSep;
             $row[] = '<button type="button" class="btn btn-xs btn-inverse" onclick="showModal('."'".$row_list->noSep."'".')">Pulangkan</button>';
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Ws_index->count_all_history_sep(),
                        "recordsFiltered" => $this->Ws_index->count_filtered_history_sep(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function updateTglPulang()
    {
        //print_r($_POST);die;
        $this->load->library('form_validation');
        $val = $this->form_validation;

        $val->set_rules('tglPulang', 'Tanggal Pulang', 'trim|required');
        $val->set_rules('noSep', 'Nomor SEP', 'trim|required');
        $val->set_message('required', "Silahkan isi field \"%s\"");

        if ($val->run() == FALSE)
        {
            $val->set_error_delimiters('<div style="color:white">', '</div>');
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {    
            /*trans begin*/
            $this->db->trans_begin();

            $data = array(
                'request' => array(
                    't_sep' => array(
                        'noSep' => $val->set_value('noSep'),
                        'tglPulang' => $this->tanggal->sqlDateForm($val->set_value('tglPulang')),
                        'user' => $this->session->userdata('user')->fullname,
                        ),
                    ),
                );
            $request = $this->Ws_index->updateTglPulang($data);

            if($request->metaData->code == 200){
                $params = array('noSep' => $request->response,'tglPulang' => $this->tanggal->sqlDateForm($val->set_value('tglPulang')));
                
                $this->Ws_index->insert_tbl_sep('ws_bpjs_sep', $params);

                /*cek transaction*/
                if ($this->db->trans_status() === FALSE)
                {
                    $this->db->trans_rollback();
                    echo json_encode(array('status' => $request->metaData->code, 'message' => $request->metaData->message));
                }
                else
                {
                    $this->db->trans_commit();
                    echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan'));
                }

            }else{
                echo json_encode(array('status' => $request->metaData->code, 'message' => $request->metaData->message));
            }

            

        }
    }

    public function prosesPengajuanSep()
    {
        /*looping*/
        if( $this->input->post('arrNoSep') ){
            $response = '';
            foreach ($this->input->post('arrNoSep') as $key => $value) {
                /*get detail sep*/
                $sep = $this->Ws_index->findSepFromLocal($value);
                if($sep->metaData->code==200){
                    $request = array(
                        'request' => array(
                            't_sep' => array(
                                'noKartu' => $sep->response->noKartu,
                                'tglSep' => $sep->response->tglSep,
                                'jnsPelayanan' => $sep->response->kodeJnsPelayanan,
                                'keterangan' => "",
                                'user' => $this->session->userdata('user')->fullname,
                                ),
                            ),
                        );
                    $result = $this->Ws_index->pengajuanSEP($request);
                    if( $result['response']->metaData->code == 200 ){
                        $this->Ws_index->updateStatusSep( array('statusPengajuanSep' => 'NEED_APPROVAL'), $sep->response->noSep );
                    }
                    $error[] = 'No SEP '.$value.' '.$reresponse->sult->metaData->message.'<br>';
                }
            }
            echo json_encode(array('status' => 200, 'message' => join($error) ) );
        }else{
            return false;
        }
        
    }

    public function prosesApprovalSep()
    {
        /*looping*/
        if( $this->input->post('arrNoSep') ){
            $response = '';
            foreach ($this->input->post('arrNoSep') as $key => $value) {
                /*get detail sep*/
                $sep = $this->Ws_index->findSepFromLocal($value);
                if($sep->metaData->code==200){
                    $request = array(
                        'request' => array(
                            't_sep' => array(
                                'noKartu' => $sep->response->noKartu,
                                'tglSep' => $sep->response->tglSep,
                                'jnsPelayanan' => $sep->response->kodeJnsPelayanan,
                                'keterangan' => "",
                                'user' => $this->session->userdata('user')->fullname,
                                ),
                            ),
                        );
                    $result = $this->Ws_index->approvalSep($request);

                    if( $result['response']->metaData->code == 200 ){
                        $this->Ws_index->updateStatusSep( array('statusPengajuanSep' => 'APPROVED'), $sep->response->noSep );
                    }
                    $error[] = 'No SEP '.$value.' '.$reresponse->sult->metaData->message.'<br>';
                }
            }
            echo json_encode(array('status' => 200, 'message' => join($error) ) );
        }else{
            return false;
        }
        
    }

    /*ketersediaan kamar*/

    public function get_data_ruangan()
    {

        /*get data from model*/
        $list = $this->Ws_index->get_datatables_ruangan_rs();
        // echo '<pre>'; print_r($list); die;
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            /*get pasien by kode ruangan*/
            // $pasien = Modules::run('Templates/References/get_data_pasien_ri_existing', $row_list->kode_ruangan);
            // print_r($pasien);die;
            $row[] = ($row_list->status=='ISI')?'<div class="center">
                        <img src="'.base_url().'assets/images/bed/bed_green.png" width="70px"><br><b>'.$row_list->kode_ruangan.'</b></div>':'<div class="center"><img src="'.base_url().'assets/images/bed/bed_red.png" width="70px"><br><b>'.$row_list->kode_ruangan.'</b></div>';
            $row[] = '<span style="font-weight: bold">'.$row_list->nama_bagian.'</span><br>'.$row_list->nama_klas.'';
            $row[] = '<div class="center">'.$row_list->no_kamar.' / '.$row_list->no_bed.'</div>';
            $row[] = '<div style="text-align: right">'.number_format($row_list->deposit).'</div>';
            $row[] = '<div style="text-align: right">'.number_format($row_list->harga_r).'</div>';
            $row[] = '<div style="text-align: right">'.number_format($row_list->harga_bpjs).'</div>';
            $row[] = '<div><b>'.$row_list->no_mr.'</b><br>'.$row_list->nama_pasien.'<br>'.$row_list->dokter.'</div>';
            $row[] = $row_list->keterangan;
            $gender = ($row_list->gender == 2) ? 'P' : 'L';
            $row[] = '<div class="center">'.$gender.'</div>';
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Ws_index->count_all_ruangan_rs(),
                        "recordsFiltered" => $this->Ws_index->count_filtered_ruangan_rs(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }


    public function postDataRuanganRs(){

        $list = $this->Ws_index->get_data_ruangan();
        $getData = [];
        foreach ($list->result() as $key => $value) {
            $count = $this->countRuangan($value->kode_bagian, $value->kode_klas);
            $getData[$value->kode_bagian][$value->kode_klas_bpjs] = array(
                'kodekelas' => $value->nama_klas_bpjs,
                'koderuang' => $value->kode_bagian,
                'namaruang' => $value->nama_bagian,
                'kapasitas' => $count['kapasitas'],
                'tersedia' => $count['tersedia'],
                'tersediapria' => $count['pria'],
                'tersediapriawanita' => $count['priawanita'],
                'tersediawanita' => $count['wanita'],
                // 'kodekelas' => $value->nama_klas_bpjs,
                );
        }
        
        foreach ($getData as $k => $v) {
            foreach ($v as $vv) {
                $data[] = $vv;
            }
        }
        
        $result['list'] = $data;
        // echo '<pre>'; print_r($result);die;
        echo json_encode(array('response' => $result));

    }

    public function updateRuangan($kodeppk){

        $list = $this->Ws_index->get_data_ruangan();
        $getData = [];
        foreach ($list->result() as $key => $value) {
            $count = $this->countRuangan($value->kode_bagian, $value->kode_klas);
            $getData[$value->kode_bagian][$value->nama_klas_bpjs] = array(
                'kodekelas' => $value->nama_klas_bpjs,
                'koderuang' => $value->kode_bagian,
                'namaruang' => $value->nama_bagian,
                'kapasitas' => $count['kapasitas'],
                'tersedia' => $count['tersedia'],
                'tersediapria' => $count['pria'],
                'tersediapriawanita' => $count['priawanita'],
                'tersediawanita' => $count['wanita'],
                // 'kodekelas' => $value->nama_klas_bpjs,
                );
        }
        
        foreach ($getData as $k => $v) {
            foreach ($v as $vv) {
                $data[] = $vv;
            }
        }
        $response = array();
        echo '<pre>';print_r($data);die;
        foreach($data as $row_dt){
            $post_data = array(
                'kodekelas' => $row_dt['kodekelas'],
                'koderuang' => $row_dt['koderuang'],
                'namaruang' => $row_dt['namaruang'],
                'kapasitas' => $row_dt['kapasitas'],
                'tersedia' => $row_dt['tersedia'],
                'tersediapria' => $row_dt['tersediapria'],
                'tersediawanita' => $row_dt['tersediawanita'],
                'tersediapriawanita' => $row_dt['tersediapriawanita'],
                // 'kodekelas' => $row_dt['nama_klas_bpjs'],
                );
                // print_r($post_data);die;
                $response[$row_dt['kodekelas']][] = $this->Ws_index->updateRuangan($post_data, $kodeppk);
        }
        // echo '<pre>';print_r($post_data);die;
        echo json_encode(array('response' => $response));

    }

    public function deleteRuangan($kodeppk){

        $post_data = array(
            'kodekelas' => 'VIP',
            'koderuang' => '030701',
        );
        // print_r($post_data);die;
        $response = $this->Ws_index->deleteRuangan($post_data, $kodeppk);

        echo json_encode(array('response' => $response));

    }

    function countRuangan($kode_bagian, $kode_klas){
        return $count = $this->Ws_index->countRuangan($kode_bagian, $kode_klas);
    }

    function getSignatureHeader(){
        echo json_encode($this->Ws_index->getSignatureHeader());
    }

    public function getKetersedianBedRs(){

        $result = $this->Ws_index->getBedData();
        // echo '<pre>'; print_r($result);die;
        echo json_encode(array('response' => $result));

    }

    function countCategoryBed($kode_bagian, $kode_klas){
        return $count = $this->Ws_index->countCategoryBed($kode_bagian, $kode_klas);
    }


}


/* End of file example.php */
/* Location: ./application/modules/example/controllers/example.php */


