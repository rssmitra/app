<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');
//include_once (dirname(__FILE__) . "../casemix/Csm_billing_pasien.php");

class Reg_pasien extends MX_Controller {

    /*function constructor*/
    
    function __construct() {

        parent::__construct();
        
        /*breadcrumb default*/
        
        $this->breadcrumbs->push('Index', 'registrasi/Reg_pasien');
        
        /*session redirect login if not login*/
        
        if($this->session->userdata('logged')!=TRUE){
            
            echo 'Session Expired !'; exit;
        
        }
        
        $this->load->module('casemix/Csm_billing_pasien');

        $this->load->module('booking/Regon_booking');

        /*load model*/
        $this->load->model('booking/Regon_booking_model');

        $this->load->model('Reg_pasien_model', 'Reg_pasien');

        $this->load->model('Reg_pm_model', 'Reg_pm');

        $this->load->model('Perjanjian_rj_model', 'Perjanjian');

        $this->load->model('Input_pasien_baru_model', 'Input_pasien_baru');

        $this->load->model('casemix/Migration_model', 'Migration');


        $this->load->library('Print_direct');

        $this->load->library('Print_escpos');

         $this->load->helper('url');
        
        /*enable profiler*/
        
        $this->output->enable_profiler(false);
        
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->
        get_menu_by_class(get_class($this))->name : 'Title';
    
    }

    public function search_pasien() { 
        
        /*define variable data*/
        
        $keyword = $this->input->get('keyword');

        /*return search pasien*/

        $data_pasien = $this->Reg_pasien->search_pasien_by_keyword( $keyword, array('no_mr','nama_pasien') );

        $data = array(

            'count' => count($data_pasien),

            'result' => $data_pasien,

        );

        echo json_encode( $data );
    
    }

    public function get_riwayat_pasien() { 
        
        /*define variable data*/
        
        $mr = $this->input->get('mr');

        /*return search pasien*/
        $data = array();

        $output = array();

        $column = array('tc_kunjungan.no_registrasi','tc_registrasi.no_sep','tc_registrasi.kode_perusahaan','tc_kunjungan.tgl_masuk','mt_dokter_v.nama_pegawai','mt_bagian.nama_bagian','tc_kunjungan.tgl_keluar','tc_kunjungan.kode_bagian_tujuan','mt_perusahaan.nama_perusahaan','tc_kunjungan.no_kunjungan');

        $list = $this->Reg_pasien->get_riwayat_pasien( $column, $mr ); 

        $no = 0;

        $atts = array('width'       => 900,'height'      => 500,'scrollbars'  => 'no','status'      => 'no','resizable'   => 'no','screenx'     => 1000,'screeny'     => 80,'window_name' => '_blank'
            );

        foreach ($list as $row_list) {
            
            $no++;
            
            $row = array();

            /*status pasien*/
            $status = ($row_list->tgl_keluar==NULL)?'<div class="center"><label class="label label-danger">Proses Menunggu...</label></div>':'<div class="center"><label class="label label-success">Sudah Pulang</label></div>';                        
            $delete_registrasi = ($row_list->tgl_keluar==NULL)?'<li><a href="#" onclick="delete_registrasi('.$row_list->no_registrasi.','.$row_list->no_kunjungan.')">Hapus</a></li>':'';  
            
            /*btn hasil pm*/
            $subs_kode_bag = substr($row_list->kode_bagian_tujuan, 0,2);
            $btn_view_hasil_pm = ($subs_kode_bag=='05')?'<li><a href="#" onclick="show_modal('."'registration/reg_pasien/form_modal_view_hasil_pm/".$row_list->no_registrasi."/".$row_list->no_kunjungan."'".', '."'HASIL PENUNJANG MEDIS (".$row_list->nama_bagian.")'".')">Lihat Hasil '.$row_list->nama_bagian.'</a></li>':'';

            $row[] = '<div class="center">'.$no.'</div>';
            $row[] = '<div class="center"><div class="btn-group">
                        <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">
                            <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-inverse">
                            <li><a href="#" onclick="delete_registrasi('.$row_list->no_registrasi.','.$row_list->no_kunjungan.')">Hapus</a></li>
                            <li><a href="#" onclick="ubah_penjamin_pasien('.$row_list->no_registrasi.','.$row_list->no_kunjungan.')">Ubah Penjamin Pasien</a></li>
                            '.$btn_view_hasil_pm.'
                            <li class="divider"></li>
                            <li><a href="#" onclick="show_modal('."'registration/reg_pasien/view_detail_resume_medis/".$row_list->no_registrasi."'".', '."'RESUME MEDIS'".')">Selengkapnya</a></li>
                        </ul>
                    </div></div>';

            $row[] = $row_list->no_registrasi;
            
            $row[] = $this->tanggal->formatDateTime($row_list->tgl_masuk);
            
            $row[] = $this->tanggal->formatDateTime($row_list->tgl_keluar);
            
            $row[] = ucfirst($row_list->nama_bagian);
            
            $row[] = $row_list->nama_pegawai;
            
            //$penjamin = ($row_list->nama_perusahaan=='')?'Umum':($row_list->kode_perusahaan==120 && $row_list->no_sep!='')?$row_list->nama_perusahaan.' ('.$row_list->no_sep.')':$row_list->nama_perusahaan;
            if($row_list->nama_perusahaan==''){
                $penjamin = 'Umum';
            }else if(($row_list->nama_perusahaan!='') AND ($row_list->kode_perusahaan==120) AND ($row_list->no_sep!='')){
                $penjamin = $row_list->nama_perusahaan.' ('.$row_list->no_sep.')';
            }else{
                $penjamin = $row_list->nama_perusahaan;
            }
            $row[] = $penjamin;

            
            
            $row[] = '<div class="left">'.$status.'</div>';

            $row[] = anchor_popup('registration/reg_pasien/tracer/'.$row_list->no_registrasi.'/'.$mr.'', '<div class="center"><button class="btn btn-xs btn-inverse" ><i class="fa fa-print"></i></button></div>', $atts);
          
            $data[] = $row;
        
        }

        $output = array( "draw" => $_POST['draw'], "recordsTotal" => count($list), "recordsFiltered" => $this->Reg_pasien->count_filtered_riwayat_pasien( $column, $mr ), "data" => $data );

        echo json_encode( $output );
    
    }

    public function get_riwayat_transaksi_pasien() { 
        
        /*define variable data*/
        
        $mr = $this->input->get('mr');

        /*return search pasien*/
        $data = array();

        $output = array();

        $column = array('tc_registrasi.no_registrasi','tc_registrasi.tgl_jam_masuk','mt_dokter_v.nama_pegawai','mt_bagian.nama_bagian','tc_registrasi.tgl_jam_keluar','mt_perusahaan.nama_perusahaan','tc_trans_kasir.seri_kuitansi','tc_trans_kasir.no_kuitansi','tc_trans_kasir.bill','tc_trans_kasir.tgl_jam');

        $list = $this->Reg_pasien->get_riwayat_transaksi_pasien( $column, $mr ); 

        $no = 0;

        foreach ($list as $row_list) {
            
            $no++;
            
            $row = array();
            
            $row[] = '';

            $row[] = $row_list->no_registrasi;

            $row[] = $row_list->seri_kuitansi.'-'.$row_list->no_kuitansi;

            $row[] = $this->tanggal->formatDate($row_list->tgl_jam);
                        
            $row[] = number_format($row_list->bill);
            
            $row[] = $row_list->nama_pegawai;
            
            /*status keluar pasien*/
            $label_status = ($row_list->tgl_jam_keluar!=NULL) ? '<span style="color:green">Selesai</span>' : '<span style="color:red">Masih Dalam Proses</span>';
            $row[] = '<div class="center">'.$label_status.'</div>';

            $data[] = $row;
        
        }

        $output = array( "draw" => $_POST['draw'], "recordsTotal" => count($list), "recordsFiltered" => $this->Reg_pasien->count_filtered_riwayat_transaksi_pasien( $column, $mr ), "data" => $data );

        echo json_encode( $output );
    
    }

    public function get_riwayat_perjanjian() { 
        
        /*define variable data*/
        
        $mr = $this->input->get('mr');

        /*return search pasien*/
        $data = array();

        $output = array();

        $column = array('regon_booking_id','regon_booking.regon_booking_kode','regon_booking.regon_booking_tanggal_perjanjian','regon_booking.regon_booking_no_mr','regon_booking.regon_booking_instalasi','regon_booking.regon_booking_hari','regon_booking.regon_booking_jenis_penjamin','log_detail_pasien','log_transaksi','regon_booking_jam','regon_booking_status');

        $list = $this->Reg_pasien->get_riwayat_perjanjian( $column, $mr ); 

        $no = 0;

        $atts = array(
                    'width'       => 900,
                    'height'      => 500,
                    'scrollbars'  => 'no',
                    'status'      => 'no',
                    'resizable'   => 'no',
                    'screenx'     => 1000,
                    'screeny'     => 80,
                    'window_name' => '_blank'
            );

        foreach ($list as $row_list) {
            
            $no++;
            
            $row = array();
                        
            $pasien = json_decode($row_list->log_detail_pasien);
            $transaksi = json_decode($row_list->log_transaksi);
            $row[] = '<div class="center"><div class="btn-group">
                        <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">
                            <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-inverse">
                            <li><a href="#" onclick="getMenu('."'registration/Reg_klinik?kode=".$row_list->regon_booking_kode."'".')">Daftarkan Pasien</a></li>
                            <li><a href="#" onclick="showModalDaftarPerjanjian('."'".$row_list->regon_booking_id."'".','."'".$row_list->regon_booking_no_mr."'".')" >Reschedule Booking</a></li>
                            <li class="divider"></li>
                            <li>
                                <a href="#">Selengkapnya</a>
                            </li>
                        </ul>
                    </div></div>';
            $row[] = '<div class="center">'.$row_list->regon_booking_kode.'</div>';
            $row[] = $this->tanggal->formatDate($row_list->regon_booking_tanggal_perjanjian);
            $row[] = ucwords($transaksi->klinik->nama_bagian);
            $row[] = $transaksi->dokter->nama_pegawai;
            $row[] = $row_list->regon_booking_jam;
            $row[] = ($row_list->regon_booking_status == '0') ? '<div class="center"><span class="label label-sm label-danger"><i class="fa fa-times-circle"></i> Menunggu..</span></div>' : '<div class="center"><span class="label label-sm label-success">Selesai</span></div>';

            $data[] = $row;
        
        }

        $output = array( "draw" => $_POST['draw'], "recordsTotal" => count($list), "recordsFiltered" => $this->Reg_pasien->count_filtered_riwayat_perjanjian_pasien( $column, $mr ), "data" => $data );

        echo json_encode( $output );
    
    }



    public function index() { 
        
        /*define variable data*/
        
        $data = array(
            
            'title' => $this->title,
            
            'breadcrumbs' => $this->breadcrumbs->show()
        
        );
        
        /*load view index*/
        
        $this->load->view('Reg_pasien/index', $data);
    
    }

    public function riwayat_kunjungan($no_mr, $kode_bagian='') { 
        
        $data = [
            'no_mr' => $no_mr,
            'kode_bagian' => $kode_bagian,
        ];
        $this->load->view('Reg_pasien/tab_riwayat_kunjungan', $data);
    
    }

    public function riwayat_kunjungan_by_reg($no_mr, $tujuan='', $no_reg='') { 
        
        $data = [
            'no_mr' => $no_mr,
            'tujuan' => $tujuan,
            'no_reg' => $no_reg,
        ];
        $this->load->view('Reg_pasien/tab_riwayat_kunjungan', $data);
    
    }

    public function riwayat_perjanjian($no_mr) { 
        
        $data = [
            'no_mr' => $no_mr,
        ];
        $this->load->view('Reg_pasien/tab_riwayat_perjanjian', $data);
    
    }

    /*public function tracer($no_registrasi,$no_mr='') { 
        
        $detail_data = $this->Reg_pasien->get_detail_resume_medis($no_registrasi);
        if(!empty($detail_data['registrasi'])){
        }

        //print_r($no_mr);die;

        $data = [
            'no_mr' => $no_mr,
            'result' => $detail_data,
        ];
        //echo '<pre>'; print_r($data);die;
        if( $this->print_direct->printer_php($data) ) {
            $this->db->update('tc_registrasi', array('print_tracer' => 'Y'), array('no_registrasi' => $no_registrasi) );
        }

        $this->load->view('Reg_pasien/tracer_view', $data);
    }*/

    public function tracer($no_registrasi,$no_mr='') { 
        
        $detail_data = $this->Reg_pasien->get_detail_resume_medis($no_registrasi);
        if(!empty($detail_data['registrasi'])){
            /*get jadwal praktek dokter*/
        }

        //print_r($no_mr);die;

        $data = [
            'no_mr' => $no_mr,
            'result' => $detail_data,
        ];
        //echo '<pre>'; print_r($data);die;
        if( $this->print_escpos->print_direct($data) ) {
             $this->db->update('tc_registrasi', array('print_tracer' => 'Y'), array('no_registrasi' => $no_registrasi) );
        }

        //$this->print_escpos->print_direct($data);

        $this->load->view('Reg_pasien/tracer_view', $data);
    }

    public function riwayat_transaksi($no_mr) { 
        
        $data = [
            'no_mr' => $no_mr,
        ];

        $this->load->view('Reg_pasien/tab_riwayat_transaksi', $data);
    
    }

    public function view_detail_resume_medis($no_registrasi) { 
        
        $data = [
            'result' => $this->Reg_pasien->get_detail_resume_medis($no_registrasi),
            'no_registrasi' => $no_registrasi,
        ];

        $userDob = $data['result']['registrasi']->tgl_lhr;
 
        //Create a DateTime object using the user's date of birth.
        $dob = new DateTime($userDob);
     
        //We need to compare the user's date of birth with today's date.
        $now = new DateTime();

        //Calculate the time difference between the two dates.
        $difference = $now->diff($dob);

        //Get the difference in years, as we are looking for the user's age.
        $umur = $difference->format('%y');

        $data['umur'] = $umur;

        //echo '<pre>';print_r($data);die;

        $this->load->view('Reg_pasien/view_resume_medis', $data);
    
    }

    public function form_modal_edit_penjamin($no_registrasi, $no_kunjungan){
        
        $detail_data = $this->Reg_pasien->get_detail_resume_medis($no_registrasi);

        $data = [
            'result' => $this->Reg_pasien->get_detail_resume_medis($no_registrasi),
            'no_registrasi' => $no_registrasi,
            'no_kunjungan' => $no_kunjungan,
        ];
        
        /*load form view*/
        
        $this->load->view('Reg_pasien/form_modal_edit_penjamin', $data);

    }

    public function form_modal_view_hasil_pm($no_registrasi, $no_kunjungan){
        
        $hasil_pm = $this->Reg_pm->get_hasil_pm($no_registrasi, $no_kunjungan);

        /*load form view*/
        $data = array(
            'html' => $hasil_pm['html'],
        );
        
        $this->load->view('Reg_pasien/form_modal_view_hasil_pm', $data);

    }

    public function form_modal($id='')
    
    {
        
        $data = array();
        
        /*if id is not null then will show form edit*/
        
        $data_pasien = $this->Reg_pasien->search_pasien_by_keyword( $id, array('no_mr') );

        /*echo '<pre>'; print_r($data_pasien);*/

        $data['value'] = $data_pasien[0];
        
        /*load form view*/
        
        $this->load->view('Reg_pasien/form_modal', $data);
    
    }

    public function form_modal_($noMr='')
    
    {
        
        $data = array();
        
        /*if id is not null then will show form edit*/
        
        //$data_pasien = $this->Input_pasien_baru->get_by_mr($noMr);

        /*echo '<pre>'; print_r($data_pasien);*/

        $data['value'] = $this->Input_pasien_baru->get_by_mr($noMr);
        
        /*load form view*/
        
        $this->load->view('Reg_pasien/form_modal', $data);
    
    }

    public function form_modal_merge_pasien($noMr='')
    
    {
        
        $data = array();
        
        /*if id is not null then will show form edit*/
        
        //$data_pasien = $this->Input_pasien_baru->get_by_mr($noMr);

        /*echo '<pre>'; print_r($data_pasien);*/

        $data['value'] = $this->Input_pasien_baru->get_by_mr($noMr);
        
        /*load form view*/
        
        $this->load->view('Reg_pasien/form_modal_merge_pasien', $data);
    
    }

    public function form_perjanjian_modal($id='')
    
    {
        
        $data = array();
        
        /*if id is not null then will show form edit*/
        
        $data_pasien = $this->Reg_pasien->search_pasien_by_keyword( $id, array('no_mr') );

        /*echo '<pre>'; print_r($data_pasien);*/

        $data['value'] = $data_pasien[0];

        $booking_id = ($this->input->get('ID'))?$this->input->get('ID'):0;
        
        $data['booking_id'] = $booking_id;


        if($booking_id!=0){

            $booking_data = $this->db->get_where('regon_booking', array('regon_booking_id' => $booking_id) )->row();
            $data['booking'] = $booking_data;

        }
        
        /*load form view*/
        
        $this->load->view('Reg_pasien/form_perjanjian_modal_3', $data);
    
    }

    public function form_reschedule_modal($id='')
    
    {
        
        $data = array();
        
        /*if id is not null then will show form edit*/
        
        $data_pasien = $this->Reg_pasien->search_pasien_by_keyword( $id, array('no_mr') );

        /*echo '<pre>'; print_r($data_pasien);*/

        $data['value'] = $data_pasien[0];

        $booking_id = ($this->input->get('ID'))?$this->input->get('ID'):0;
        
        $data['booking_id'] = $booking_id;


        if($booking_id!=0){

            $booking_data = $this->db->get_where('regon_booking', array('regon_booking_id' => $booking_id) )->row();
            $data['booking'] = $booking_data;

        }
        
        /*load form view*/
        
        $this->load->view('Reg_pasien/form_perjanjian_modal_2', $data);
    
    }

    public function show_modul($modul_id) { 
        
        switch ($modul_id) {
            case 'RJ':
                $view_modul = 'Reg_pasien/form_rajal';
                break;
            case 'BD':
                $view_modul = 'Reg_pasien/form_perjanjian_bedah';
                break;
            case 'PM':
                $view_modul = 'Reg_pasien/form_perjanjian_pm';
                break;
            default:
                $view_modul = 'Reg_pasien/index';
                break;
        }

        $this->load->view($view_modul);
    
    }

    public function getDetailTransaksi($no_registrasi){
        
        $tipe = 'RJ';
        /*get detail data billing*/
        $data = json_decode($this->Migration->getDetailData($no_registrasi));
        
        if($tipe=='RJ'){
            $html = $this->Migration->getDetailBillingRJ($no_registrasi, $tipe, $data);
        }else{
            $html = $this->Migration->getDetailBillingRI($no_registrasi, $tipe, $data);
        }

        echo json_encode(array('html' => $html));
    }

    public function cek_status_pasien($no_mr){
        
        $result = $this->Reg_pasien->cek_status_pasien($no_mr);

        echo json_encode($result);

    }

    public function barcode_pasien($no_mr, $count=''){
        
        $data = array(
            'count' => $count,
            'no_mr' => $no_mr,
            'pasien' => $this->Reg_pasien->get_by_mr($no_mr),
            );
        $this->load->view('Reg_pasien/barcode_pasien', $data);

    }

    public function label_nama_pasien($no_mr, $count=''){
        
        $data = array(
            'count' => $count,
            'no_mr' => $no_mr,
            'pasien' => $this->Reg_pasien->get_by_mr($no_mr),
            );
        $this->load->view('Reg_pasien/label_nama_pasien', $data);

    }

    public function gelang_pasien($no_mr){
        
        $data = array(
            'no_mr' => $no_mr,
            'pasien' => $this->Reg_pasien->get_by_mr($no_mr),
            );
        $this->load->view('Reg_pasien/gelang_pasien', $data);

    }

    public function card_member($no_mr, $type=''){
        
        $data = array(
            'no_mr' => $no_mr,
            'pasien' => $this->Reg_pasien->get_by_mr($no_mr),
            );
        if($type=='temp'){
            $this->load->view('Reg_pasien/card_member_temp', $data);
        }else{
            $this->load->view('Reg_pasien/card_member', $data);
        }

    }

    public function identitas_berobat_pasien($no_mr){
        
        $data = array(
            'no_mr' => $no_mr,
            'pasien' => $this->Reg_pasien->get_by_mr($no_mr),
            );
        $this->load->view('Reg_pasien/identitas_berobat_pasien', $data);

    }

    public function process_perjanjian()
    {
         /*print_r($_POST);die;*/
         $this->load->library('form_validation');
         $val = $this->form_validation;
 
         if($_POST['jenis_instalasi']=='BD'){
             $tgl = $this->tanggal->sqlDateForm($this->input->post('tanggal_perjanjian_bedah'));
             $bag = '30901';
         }else if($_POST['jenis_instalasi']=='PM'){
             $tgl = $this->tanggal->sqlDateForm($this->input->post('tanggal_perjanjian_pm'));
             $bag = $this->input->post('pm_tujuan');
         }else{
             $tgl = $this->tanggal->sqlDateForm($this->input->post('tanggal_kunjungan'));
             $bag =  $this->input->post('klinik_rajal');
         }
                 
         $val->set_rules('no_mr', 'MR Pasien', 'trim|required', array('required' => 'MR Pasien tidak ditemukan'));
         $val->set_rules('jenis_instalasi', 'Instalasi', 'trim|required');
         $val->set_rules('keterangan', 'Keterangan', 'trim');
         $val->set_rules('jenis_penjamin', 'Jenis Penjamin', 'trim|required');
 
         if($_POST['jenis_instalasi']=='BD'){
             $val->set_rules('tanggal_perjanjian_bedah', 'Tanggal Perjanjian', 'trim|required');
             $val->set_rules('perjanjian_tindakan_bedah', 'Nama Tindakan', 'trim|required');
             $val->set_rules('diagnosa_perjanjian_bedah', 'Diagnosa', 'trim|required');
             $val->set_rules('dokter_rajal', 'Dokter', 'trim|required');
             $val->set_rules('selected_time', 'Jam Praktek', 'trim|required');
             $date = date_create($tgl.' '.$this->tanggal->formatTime($this->input->post('selected_time')) );
             $jam_pesanan = date_format($date, 'Y-m-d H:i:s');
         }else if($_POST['jenis_instalasi']=='RJ'){
             $val->set_rules('klinik_rajal', 'Klinik', 'trim|required');
             $val->set_rules('tanggal_kunjungan', 'Tanggal Kunjungan', 'trim|required');
             $val->set_rules('dokter_rajal', 'Dokter', 'trim|required');
             $val->set_rules('selected_time', 'Jam Praktek', 'trim|required');
             $date = date_create($this->input->post('tanggal_kunjungan').' '.$this->input->post('time_start') );
             $jam_pesanan = date_format($date, 'Y-m-d H:i:s');
         }else{
             $val->set_rules('tanggal_perjanjian_pm', 'Tanggal Perjanjian', 'trim|required');
             $val->set_rules('pm_tujuan', 'Penunjang Medis', 'trim|required');
             $jam_pesanan = $tgl;
         }
        
         if($this->input->post('jenis_penjamin')=='Jaminan Perusahaan'){
             $val->set_rules('kode_perusahaan', 'Nama Perusahaan', 'trim|required');
         }
 
         $val->set_message('required', "Silahkan isi field \"%s\"");
 
         if ($val->run() == FALSE)
         {
             $val->set_error_delimiters('<div style="color:white">', '</div>');
             echo json_encode(array('status' => 301, 'message' => validation_errors()));
         }
         else
         {                       
 
             $this->db->trans_begin();
             $id = ($this->input->post('id_tc_pesanan'))?$this->regex->_genRegex($this->input->post('id_tc_pesanan'),'RGXINT'):0;
  
             /*get_unique_code*/
             $kode_faskes = '0112R034';
             $unique_code_max = $this->master->get_max_number('tc_pesanan', 'unique_code_counter');
             $length = strlen((string)$unique_code_max);
             $less = 6-$length;
             $null = '';
             for ($i=0; $i < $less; $i++) { 
                 $null .= '0';
             }
             $kode_perjanjian = $kode_faskes.date('my').$null.$unique_code_max;
 
             $dataexc = array(
                 'tgl_pesanan' => $tgl,
                 'no_mr' => $this->regex->_genRegex($val->set_value('no_mr'), 'RGXQSL'),
                 'nama' => $this->regex->_genRegex($this->input->post('nama_pasien'), 'RGXQSL'),
                 'alamat' => $this->regex->_genRegex($this->input->post('alamat'), 'RGXQSL'),
                 'no_poli' => $bag,
                 'kode_dokter' => $this->regex->_genRegex($val->set_value('dokter_rajal'), 'RGXQSL'),                 
                 'penjamin' => $this->regex->_genRegex($this->input->post('jenis_penjamin'), 'RGXQSL'),                 
                 'jam_pesanan' => $jam_pesanan,
                 'no_induk' => $this->session->userdata('user')->user_id,
                 'input_tgl' => date('Y-m-d h:i:s'),
                 'kode_perjanjian' => $kode_perjanjian,
                 'unique_code_counter' =>  $unique_code_max,
             );

             if($this->input->post('jenis_penjamin')=='Jaminan Perusahaan'){
                 $dataexc['kode_perusahaan'] = $this->regex->_genRegex($val->set_value('kode_perusahaan'), 'RGXINT');
             }
 
             if($_POST['jenis_instalasi']=='BD'){
                 $dataexc['flag']='bedah';
                 $dataexc['diagnosa']=$this->regex->_genRegex($val->set_value('diagnosa_perjanjian_bedah'), 'RGXQSL');
                 $dataexc['kode_tarif']=$this->regex->_genRegex($val->set_value('perjanjian_tindakan_bedah'), 'RGXQSL');
             }
 
             if($id==0){
                 /*save post data*/
                 $newId = $this->Perjanjian->save($dataexc);
                 /*save logs*/
                // $this->logs->save('log', $newId, 'insert new record on '.$this->title.' module', json_encode($dataexc),'id_tc_pesanan');
                 /*save log kuota dokter*/
                $this->logs->save_log_kuota(array('kode_dokter' => $dataexc['kode_dokter'], 'kode_spesialis' => $dataexc['no_poli'], 'tanggal' => $dataexc['tgl_pesanan'] ));
                 
             }else{
                 /*update record*/
                 $this->Perjanjian->update(array('id_tc_pesanan' => $id), $dataexc);
                 $newId = $id;
                 /*save logs*/
                 //$this->logs->save('log', $newId, 'update record on '.$this->title.' module', json_encode($dataexc),'id_tc_pesanan');
             }
 
             if ($this->db->trans_status() === FALSE)
             {
                 $this->db->trans_rollback();
                 echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
             }
             else
             {
                 $this->db->trans_commit();
                 echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'redirect' => 'registration/Reg_pasien/surat_control?id_tc_pesanan='.$newId.''));
             }
 
         }
    }

    public function surat_control(){
        
        $id_tc_pesanan = $_GET['id_tc_pesanan'];
        $data['value'] = $this->Reg_pasien->get_pesanan_pasien_($id_tc_pesanan);
        //print_r($data);die;

        $this->load->view('Reg_pasien/surat_kontrol', $data);

    }

    public function delete_registrasi(){
        
        $no_registrasi = $_POST['ID'];
        $no_kunjungan = $_POST['KunjunganID'];
        $this->Reg_pasien->delete_registrasi($no_registrasi, $no_kunjungan);
        echo json_encode( array('status'=>200, 'message'=>'Proses hapus data berhasil dilakukan') );

    }

     public function process_edit_transaksi_penjamin_pasien()
    {
        /*print_r($_POST);die;*/
        $this->load->library('form_validation');
        $val = $this->form_validation;

        $val->set_rules('kode_kelompok_hidden_edit_penjamin', 'Nasabah', 'trim|required');
        $val->set_rules('no_registrasi_hidden_edit_penjamin', 'No Registrasi', 'trim|required');
        

        if($_POST['kode_kelompok_hidden_edit_penjamin']==3){
            $val->set_rules('kode_perusahaan_hidden_edit_penjamin', 'Nama Perusahaan', 'trim|required');
            if($_POST['kode_perusahaan_hidden_edit_penjamin']==120){
                $val->set_rules('noSepEditPenjamin', 'No SEP', 'trim|required');
            }
        }

        $val->set_message('required', "Silahkan isi field \"%s\"");

        if ($val->run() == FALSE)
        {
            $val->set_error_delimiters('<div style="color:white">', '</div>');
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       

            $this->db->trans_begin();
            
            /*process*/

            $dataexc = array(
                'no_sep' => ($val->set_value('noSepEditPenjamin'))?$this->regex->_genRegex($val->set_value('noSepEditPenjamin'),'RGXQSL'):'',
                'kode_kelompok' => $this->regex->_genRegex($val->set_value('kode_kelompok_hidden_edit_penjamin'),'RGXINT'),
                'kode_perusahaan' => isset($_POST['kode_perusahaan_hidden_edit_penjamin'])?$this->regex->_genRegex($val->set_value('kode_perusahaan_hidden_edit_penjamin'),'RGXINT'):NULL,
            );

            //print_r($dataexc);die;

            $this->db->update('tc_registrasi', $dataexc, array('no_registrasi' => $val->set_value('no_registrasi_hidden_edit_penjamin') ) );


            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'no_mr' => $_POST['no_mr_hidden_edit_penjamin'] ));
            }

        }
    }

    public function process_merge_pasien()
    {
         //print_r($_POST);die;
         $this->load->library('form_validation');
         $val = $this->form_validation;
     
         $val->set_rules('noMrHiddenPasien', 'MR Pasien', 'trim|required', array('required' => 'MR Pasien tidak ditemukan'));
 
         $val->set_message('required', "Silahkan isi field \"%s\"");
 
         if ($val->run() == FALSE)
         {
             $val->set_error_delimiters('<div style="color:white">', '</div>');
             echo json_encode(array('status' => 301, 'message' => validation_errors()));
         }
         else
         {                       
 
            $this->db->trans_begin();
            
            /*eksekusi proses merge pasien*/
            
            $this->Reg_pasien->mergeNoMr();

             if ($this->db->trans_status() === FALSE)
             {
                 $this->db->trans_rollback();
                 echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
             }
             else
             {
                 $this->db->trans_commit();
                 echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'no_mr' => $_POST['no_mr_baru']));
             }
 
         }
    }

}

/* End of file example.php */

/* Location: ./application/functiones/example/controllers/example.php */
