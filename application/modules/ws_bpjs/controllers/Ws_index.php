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

    }

    public function index() { 
        clearstatcache();
        $mod = $this->input->get('modWs');
        /*define variable data*/
        $data = array(
            'title' => $mod,
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

    public function updateNoPesertaPerjanjianOperasi(){
        $query = $this->db->query("select a.*,b.no_ktp from tc_pesanan a
                            left join mt_master_pasien b on b.no_mr=a.no_mr
                            where flag='bedah' and a.kode_perusahaan=120 and b.no_ktp is not null and b.no_ktp != '-' and b.no_ktp != ''")->result();

        foreach($query as $row){

            $result = $this->Ws_index->searchMemberByNIK($row->no_ktp, $row->tgl_pesanan);
            print_r($result);die;
        }
        print_r($result);die;
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

            $result = $this->Ws_index->searchMember();
            // print_r($result);die;
            if($result->metaData->code==200){
                $response = $result->response->peserta;
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
            
            echo json_encode(array('status' => $result->metaData->code, 'message' => $result->metaData->message, 'result' => $profile));

        }
    }

    public function searchRujukan()
    {
       
        $this->load->library('form_validation');

        $val = $this->form_validation;

        if( $this->input->post('flag')=='noRujukan' ){
            $val->set_rules('noRujukan', 'No Rujukan', 'trim|required');
        }else{
            $val->set_rules('nokartu', 'Nomor Kartu BPJS', 'trim|required|integer');
        }

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

            if($result->metaData->code==200){

                $response = $result->response->rujukan;
                $peserta = $response->peserta;

                $result_data['rujukan'] = $response;
                $result_data['peserta'] = $response->peserta;
                $result_data['diagnosa'] = $response->diagnosa;
                $result_data['pelayanan'] = $response->pelayanan;
                $result_data['poliRujukan'] = $response->poliRujukan;
                $result_data['provPerujuk'] = $response->provPerujuk;


            }else{
                $result_data = array();
            }
            //print_r($result_data);die;
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => $result->metaData->code, 'message' => $result->metaData->message));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => $result->metaData->code, 'message' => $result->metaData->message, 'result' => $result_data));
            }
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

    public function insertRujukan()
    {
        
        $this->load->library('form_validation');
        $val = $this->form_validation;
        $val->set_rules('noSep', 'No SEP', 'trim|required');
        $val->set_rules('tglRujukan', 'Tanggal Rujukan', 'trim|required');
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
                        'tglRujukan' => $this->tanggal->sqlDateForm($val->set_value('tglRujukan')),
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
            /*print_r($result);die;*/

            if($result->metaData->code==200){
                $response = $result->response->rujukan;
                $profile['noRujukan'] = $response->noRujukan;
                $profile['tglRujukan'] = $this->tanggal->formatDate($response->tglRujukan);
                $profile['AsalRujukan'] = $response->AsalRujukan->kode.' : '. $response->AsalRujukan->nama;
                $profile['tujuanRujukan'] = $response->tujuanRujukan->kode.' : '. $response->tujuanRujukan->nama;
                $profile['diagnosa'] = $response->diagnosa->nama;
                $profile['poliTujuan'] = $response->poliTujuan->kode.' : '. $response->poliTujuan->nama;

                /*peserta*/
                $profile['nama'] = $response->peserta->nama;
                $profile['noKartu'] = $response->peserta->noKartu;
                $profile['noMr'] = $response->peserta->noMr;
                $profile['tglLahir'] = $response->peserta->tglLahir;
                $profile['hakKelas'] = $response->peserta->hakKelas;
                $profile['jnsPeserta'] = $response->peserta->jnsPeserta;
                $profile['kelamin'] = $response->peserta->kelamin;
            }else{
                $profile = array('code' => $result->metaData->code,'message' => $result->metaData->message);
            }
            
            if ($result->metaData->code==200)
            {
                echo json_encode(array('status' => $result->metaData->code, 'message' => $result->metaData->message, 'result' => $profile));
            }
            else
            {
                echo json_encode(array('status' => $result->metaData->code, 'message' => $result->metaData->message, 'result' => $profile));
            }
        }
    }

    public function insertSep()
    {
        //print_r($_POST);die;
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
        $val->set_rules('dokterDPJP', 'Dokter DPJP', 'trim|required');
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
            // print_r($result);die;

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

            $response = json_encode(array('status' => $result->metaData->code, 'message' => $result->metaData->message, 'result' => $sep, 'no_sep' => isset($sep->noSep)?$sep->noSep:'', 'redirect' => base_url().'ws_bpjs/ws_index?modWs=InsertSep' ));

            return $response;
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

        if( $result->metaData->code==200 ){
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
            //$response = json_encode(array('status' => $result->metaData->code, 'message' => 'No SEP '.$result->response.' Berhasil diproses', 'result' => $result->response, 'noSep' => $result->response));
        }

        $response = json_encode(array('status' => $result->metaData->code, 'message' => 'No SEP '.$result->response.' ('.$result->metaData->message.')', 'result' => $result->response, 'redirect' => base_url().'ws_bpjs/ws_index?modWs=HistorySep' ));

        return $response;
    }

    public function get_data_kunjungan()
    {
        /*get data from model*/
        
        $data = array();
        $list = $this->Ws_index->get_datatables_kunjungan();
        /*echo '<pre>';print_r($list);die;*/
          if ($list->metaData->code ==  200) {
            # code...
            $no = 0;
            foreach ($list->response->sep as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center">
                        <a href="#" title="Delete SEP" class="btn btn-xs btn-danger" onclick="delete_sep('."'".$row_list->noSep."'".')"><i class="fa fa-times-circle"></i></a>
                        <a href="#" title="Update SEP" class="btn btn-xs btn-success" onclick="getMenu('."'ws_bpjs/ws_index?modWs=DetailSEP&sep=".$row_list->noSep."'".')"><i class="fa fa-edit"></i></a>
                        <a href="#" title="View SEP" class="btn btn-xs btn-primary" onclick="view_sep('."'".$row_list->noSep."'".')"><i class="fa fa-eye"></i></a>
                     </div>';
            $row[] = strtoupper($row_list->nama);
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

    public function find_data()
    {   
        $output = array(
                        "recordsTotal" => 0,
                        "data" => $_POST,
                        "params_string" => http_build_query($_POST),
                );
        echo json_encode($output);
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


    public function view_sep($noSep)
    {   
        /*data sep*/
        $row_sep = $this->Ws_index->get_data_sep($noSep);
        $cetakan_ke = $this->Ws_index->count_sep_by_day();

        $data = array('sep'=>$row_sep, 'cetakan_ke' => $cetakan_ke);

        $this->load->view('Ws_index/barcode', $data);

        //echo $html;
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
        $excecute = $this->Ws_index->deleteSep($no_sep, $request);

        if ($excecute->metaData->code == 200) {
            /*delete local*/
            $this->Ws_index->deleteSepLocal($no_sep);
            echo json_encode(array('status' => $excecute->metaData->code, 'message' => 'No SEP '.$excecute->response.' berhasil dihapus', 'data' => $excecute->response, 'params_string' => array('params_string' => $params_string) ));
        }else{
            echo json_encode(array('status' => $excecute->metaData->code, 'message' => $excecute->metaData->message, 'data' => '', 'params_string'=> array('params_string' => $params_string) ));
        }
    }

    function show_detail_sep($no_sep){
        $sep_data = $this->Ws_index->findSepFromLocal($no_sep);
        /*print_r($sep_data);die;*/
        
        echo json_encode(array('status'=>$sep_data->metaData->code,'message' => $sep_data->metaData->message,'data'=>$sep_data->response));
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
                    if( $result->metaData->code == 200 ){
                        $this->Ws_index->updateStatusSep( array('statusPengajuanSep' => 'NEED_APPROVAL'), $sep->response->noSep );
                    }
                    $error[] = 'No SEP '.$value.' '.$result->metaData->message.'<br>';
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

                    if( $result->metaData->code == 200 ){
                        $this->Ws_index->updateStatusSep( array('statusPengajuanSep' => 'APPROVED'), $sep->response->noSep );
                    }
                    $error[] = 'No SEP '.$value.' '.$result->metaData->message.'<br>';
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
            $gender = ($row_list->gender == 2)?'P':($row_list->gender == 1)?'L':'L & P';
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
}


/* End of file example.php */
/* Location: ./application/modules/example/controllers/example.php */
