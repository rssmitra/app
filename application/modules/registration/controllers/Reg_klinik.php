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
        $this->load->model('counter/Counter_model', 'Counter');

        /*load library*/

        $this->load->library('Daftar_pasien');
        $this->load->library('Form_validation');
        $this->load->library('Print_direct');
        $this->load->library('Print_escpos');        
        
        /*enable profiler*/
        
        $this->output->enable_profiler(false);
        
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->
        get_menu_by_class(get_class($this))->name : 'Title';
    
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
        //echo'<pre>';print_r($data);die;
        /*load view index*/
        
        $this->load->view('Reg_klinik/index', $data);
    
    }

    public function print_bukti_pendaftaran_pasien(){
        
        $data = array();
        $this->load->view('Reg_klinik/print_bukti_pendaftaran_pasien_view', $data);

    }

    public function search_pasien() { 
        
        /*define variable data*/
        
        $keyword = $this->input->get('keyword');

        /*return search pasien*/

        $data_pasien = $this->Reg_pasien->search_pasien_by_keyword( $keyword, array('no_mr','nama_pasien') ); 
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

    public function rujuk_klinik($no_reg='',$bag_asal='',$type_asal='',$klas='')
    {
        /*get value by no_kunj*/
        $data_reg = $this->Reg_pasien->get_detail_resume_medis($no_reg);
        $data['value'] = $data_reg['registrasi'];
        $data['bagian_asal'] = $bag_asal;
        $data['no_reg'] = $no_reg;
        $data['type'] = $type_asal;
        $data['klas'] = $klas;
        /*load form view*/
        $this->load->view('Reg_klinik/form_rajal', $data);
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
            
            default:
                $view_modul = 'Reg_klinik/index';
                break;
        }

        $this->load->view($view_modul, $data);
    
    }

    public function process(){

        //print_r($_POST);die;
        // form validation
        $this->form_validation->set_rules('tgl_registrasi', 'Tanggal Registrasi', 'trim|required');
        $this->form_validation->set_rules('reg_klinik_rajal', 'Poli/Klinik', 'trim|required');
        $this->form_validation->set_rules('reg_dokter_rajal', 'Dokter', 'trim|required');
        $this->form_validation->set_rules('noMrHidden', 'Dokter', 'trim|required');
        $this->form_validation->set_rules('kode_perusahaan_hidden', 'Kode Perusahaan', 'trim');
        $this->form_validation->set_rules('kode_kelompok_hidden', 'Kode Perusahaan', 'trim');
        $this->form_validation->set_rules('umur_saat_pelayanan_hidden', 'Umur', 'trim');

        if(isset($_POST['kode_perusahaan_hidden']) && $_POST['kode_perusahaan_hidden']==120){
            $this->form_validation->set_rules('noSep', 'Nomor SEP', 'trim|required');
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
            $no_sep = $this->regex->_genRegex($this->form_validation->set_value('noSep'),'RGXQSL');
            $jd_id =  $this->input->post('jd_id');

            if( !$this->input->post('no_registrasi_hidden') && !$this->input->post('no_registrasi_rujuk')){
                /*save tc_registrasi*/
                $data_registrasi = $this->daftar_pasien->daftar_registrasi($title,$no_mr, $kode_perusahaan, $kode_kelompok, $kode_dokter, $kode_bagian_masuk, $umur_saat_pelayanan,$no_sep,$jd_id);
                $no_registrasi = $data_registrasi['no_registrasi'];
                $no_kunjungan = $data_registrasi['no_kunjungan'];
            }else{
                $no_registrasi = ($this->input->post('no_registrasi_hidden'))?$this->input->post('no_registrasi_hidden'):$this->input->post('no_registrasi_rujuk');
                $kode_bagian_asal = ($this->input->post('kode_bagian_asal'))?$this->input->post('kode_bagian_asal'):$this->input->post('asal_pasien_rujuk');
                $kode_bagian_tujuan = $this->regex->_genRegex($this->form_validation->set_value('reg_klinik_rajal'),'RGXQSL');
                $no_kunjungan = $this->daftar_pasien->daftar_kunjungan($title,$no_registrasi,$no_mr,$kode_dokter,$kode_bagian_tujuan,$kode_bagian_asal);
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
            $this->logs->save_log_kuota(array('kode_dokter' => $datapoli['kode_dokter'], 'kode_spesialis' => $datapoli['kode_bagian'], 'tanggal' => $datapoli['tgl_jam_poli'], 'keterangan' => null, 'flag' => 'on_the_spot' ));

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
                

            }

            /*jika terdapat kode_booking maka update tgl_masuk pada table regon_booking*/
            if( $this->input->post('kode_booking') ){
                $this->db->update('regon_booking', array('regon_booking_tgl_registrasi_ulang' => date('Y-m-d H:i:s'), 'regon_booking_status' => 1, 'updated_date' => date('Y-m-d H:i:s'), 'updated_by' => $this->session->userdata('user')->fullname ), array('regon_booking_kode' => $this->input->post('kode_booking') ) );
            }

            /*jika dari pasien rujukan*/
            if( $this->input->post('kode_rujukan_hidden') ){
                $this->db->update('rg_tc_rujukan', array('status' => 1, 'rujukan_tujuan' => $this->regex->_genRegex($this->form_validation->set_value('reg_klinik_rajal'),'RGXQSL')), array('kode_rujukan' => $this->input->post('kode_rujukan_hidden') ) );
            }

            if ( $this->input->post('cetak_kartu') == 'Y' ) {
                /*insert tagihan cetak kartu*/    
                //$this->Billing->add_billing('cetak_kartu');
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

                if($this->input->post('is_new')!='Yes'){
                    $tracer = $this->print_escpos->print_direct($data_tracer);
                    if( $tracer==1 ) {
                         $this->db->update('tc_registrasi', array('print_tracer' => 'Y'), array('no_registrasi' => $no_registrasi) );
                    }
                }

                // get detail data
                $dt = $this->Reg_klinik->get_by_id($no_registrasi);
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'no_mr' => $no_mr, 'no_registrasi' => $no_registrasi, 'is_new' => $this->input->post('is_new'), 'type_pelayanan' => 'Rawat Jalan', 'dokter' => $dt->nama_pegawai, 'poli' => $dt->nama_bagian, 'nasabah' => $dt->nama_perusahaan, 'nama_pasien' => $dt->nama_pasien ));
            }
        
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

        if($this->input->post('is_new')!='Yes'){

            if( $this->print_direct->printer_php($data_tracer) ){
                
            }else{
                $this->db->update('tc_registrasi', array('print_tracer' => 'N'), array('no_registrasi' => $_POST['no_registrasi'] ) );
            }
        }      

        $this->print_escpos->print_direct($data_tracer);

        echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'no_mr' => $_POST['no_mr']));
    }

}



/* End of file example.php */

/* Location: ./application/functiones/example/controllers/example.php */
