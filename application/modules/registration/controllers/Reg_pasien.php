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
        
        // if($this->session->userdata('logged')!=TRUE){
            
        //     echo 'Session Expired !'; exit;
        
        // }
        
        $this->load->module('casemix/Csm_billing_pasien');

        $this->load->module('booking/Regon_booking');

        /*load model*/
        $this->load->model('booking/Regon_booking_model');

        $this->load->model('Reg_pasien_model', 'Reg_pasien');

        $this->load->model('Reg_pm_model', 'Reg_pm');

        $this->load->model('Perjanjian_rj_model', 'Perjanjian');

        $this->load->model('Input_pasien_baru_model', 'Input_pasien_baru');

        $this->load->model('casemix/Migration_model', 'Migration');

        $this->load->model('ws/AntrianOnlineModel', 'AntrianOnline');


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

    public function get_riwayat_pasien_only_bpjs() { 
        
        /*define variable data*/
        
        $mr = $this->input->get('mr');

        /*return search pasien*/
        $data = array();
        $output = array();

        $column = array('tc_kunjungan.no_registrasi','tc_registrasi.no_sep','tc_registrasi.kode_perusahaan','tc_kunjungan.tgl_masuk','mt_dokter_v.nama_pegawai','mt_bagian.nama_bagian','tc_kunjungan.tgl_keluar','tc_kunjungan.kode_bagian_tujuan','mt_perusahaan.nama_perusahaan','tc_kunjungan.no_kunjungan', 'tc_kunjungan.kode_dokter', 'mt_master_pasien.nama_pasien', 'mt_master_pasien.no_mr');

        $list = $this->Reg_pasien->get_riwayat_pasien( $column, $mr ); 

        $no = 0;

        $atts = array('width'       => 900,'height'      => 500,'scrollbars'  => 'no','status'      => 'no','resizable'   => 'no','screenx'     => 1000,'screeny'     => 80,'window_name' => '_blank'
            );

        foreach ($list as $row_list) {
            
            $subs_kode_bag = substr($row_list->kode_bagian_tujuan, 0,2);
            if($row_list->kode_perusahaan == 120 && $subs_kode_bag == '01'){
                $no++;
                $row = array();
                /*status pasien*/
                $status_icon = ($row_list->tgl_keluar==NULL)?'<div class="center"><i class="fa fa-times-circle bigger-150 red"></i></div>':'<div class="center"><i class="fa fa-check-circle bigger-150 green"></i></div>';  

                if( $row_list->status_batal == 1 ){
                    $is_batal = '<span style="font-weight: bold; color: red">Batal Berobat</span>';
                }else{
                    $is_batal = '';
                }
                $nama_dokter = ($row_list->nama_pegawai != '') ? $row_list->nama_pegawai.'<br>' : '' ;

                $row[] = '<div class="center">'.$no.'</div>';            
                $row[] = $this->tanggal->formatDateTime($row_list->tgl_masuk);
                $row[] = '<a href="#" class="label label-default" onclick="selectSep('."'".$row_list->no_sep."'".')">'.$row_list->no_sep.'</a>';
                $row[] = ucfirst($row_list->nama_bagian);
                $row[] = $nama_dokter;
                $row[] = $is_batal;
                $row[] = '<div class="left">'.$status_icon.'</div>';            
                $data[] = $row;
            }
            
        }

        $output = array( "draw" => $_POST['draw'], "recordsTotal" => count($list), "recordsFiltered" => $this->Reg_pasien->count_filtered_riwayat_pasien( $column, $mr ), "data" => $data );

        echo json_encode( $output );
    
    }

    public function get_riwayat_pasien() { 
        
        /*define variable data*/
        
        $mr = $this->input->get('mr');

        /*return search pasien*/
        $data = array();
        $output = array();

        $column = array('tc_kunjungan.no_registrasi','tc_registrasi.no_sep','tc_registrasi.kode_perusahaan','tc_kunjungan.tgl_masuk','mt_dokter_v.nama_pegawai','mt_bagian.nama_bagian','tc_kunjungan.tgl_keluar','tc_kunjungan.kode_bagian_tujuan','mt_perusahaan.nama_perusahaan','tc_kunjungan.no_kunjungan', 'tc_kunjungan.kode_dokter', 'mt_master_pasien.nama_pasien', 'mt_master_pasien.no_mr');

        $list = $this->Reg_pasien->get_riwayat_pasien( $column, $mr ); 

        $no = 0;

        $atts = array('width'       => 900,'height'      => 500,'scrollbars'  => 'no','status'      => 'no','resizable'   => 'no','screenx'     => 1000,'screeny'     => 80,'window_name' => '_blank'
            );

        foreach ($list as $row_list) {
            
            $no++;
            
            $row = array();

            /*status pasien*/
            $status = ($row_list->tgl_keluar==NULL)?'<div class="center"><label class="label label-danger">Proses Menunggu...</label></div>':'<div class="center"><label class="label label-success">Sudah Pulang</label></div>';  
            $status_icon = ($row_list->tgl_keluar==NULL)?'<div class="center"><i class="fa fa-times-circle bigger-150 red"></i></div>':'<div class="center"><i class="fa fa-check-circle bigger-150 green"></i></div>';  

            $delete_registrasi = ($row_list->tgl_keluar==NULL)?'<li><a href="#" onclick="delete_registrasi('.$row_list->no_registrasi.','.$row_list->no_kunjungan.')">Hapus</a></li>':'';  
            
            /*btn hasil pm*/
            $subs_kode_bag = substr($row_list->kode_bagian_tujuan, 0,2);
            $btn_view_hasil_pm = ($subs_kode_bag=='05')?'<li><a href="#" onclick="show_modal('."'registration/reg_pasien/form_modal_view_hasil_pm/".$row_list->no_registrasi."/".$row_list->no_kunjungan."'".', '."'HASIL PENUNJANG MEDIS (".$row_list->nama_bagian.")'".')">Lihat Hasil '.$row_list->nama_bagian.'</a></li>':'';
            $btn_print_out_checklist_mcu = '';
            /*btn for medical checkup*/
            if( $row_list->kode_bagian_tujuan=='010901' ){
                /*get data from trans_pelayanan*/
                $dt_trans_mcu = $this->db->get_where('tc_trans_pelayanan', array('no_registrasi' => $row_list->no_registrasi, 'no_kunjungan' => $row_list->no_kunjungan) )->row();
                $btn_print_out_checklist_mcu = '<li><a href="#" onclick="PopupCenter('."'registration/Reg_mcu/print_checklist_mcu?kode_tarif=".$dt_trans_mcu->kode_tarif."&nama=".$dt_trans_mcu->nama_pasien_layan."&no_mr=".$dt_trans_mcu->no_mr."&no_reg=".$row_list->no_registrasi."'".', '."'FORM CHEKLIST MCU'".', 850, 500)">Cetak Form Cheklist MCU</a></li>';
            }

            $btn_perjanjian = ( $subs_kode_bag == '01') ? '<li><a href="#" onclick="getMenu('."'pelayanan/Pl_pelayanan/form_perjanjian_view/".$row_list->no_mr."?kode_bagian=".$row_list->kode_bagian_tujuan."&kode_dokter=".$row_list->kode_dokter."&kode_perusahaan=".$row_list->kode_perusahaan."&no_sep=".$row_list->no_sep."'".')">Surat Kontrol Pasien</a></li>' : '';

            $btn_cetak_sep = ($row_list->kode_perusahaan == 120)?'<li><a href="#" onclick="getMenuTabs('."'ws_bpjs/Ws_index/view_sep/".$row_list->no_sep."?no_antrian=".$row_list->no_antrian."'".', '."'divLoadSEP'".')">Cetak SEP</a></li>':'';

            if($row_list->nama_perusahaan==''){
                $penjamin = 'Umum';
            }else if(($row_list->nama_perusahaan!='') AND ($row_list->kode_perusahaan==120) AND ($row_list->no_sep!='')){
                $penjamin = $row_list->nama_perusahaan.' (<b>'.$row_list->no_sep.'</b>)';
            }else{
                $penjamin = $row_list->nama_perusahaan;
            }

            // cek authuser
            $btn_delete = ($this->authuser->is_administrator($this->session->userdata('user')->user_id) ) ? '<li><a href="#" onclick="delete_registrasi('.$row_list->no_registrasi.','.$row_list->no_kunjungan.')">Hapus</a></li>' : '';

            // tipe daftar
            $tipe_daftar = ($row_list->tipe_daftar != null) ? '<span style="font-weight: bold; color: green"> [ '.$row_list->tipe_daftar.' ] </span>' : '';
            
            if( $row_list->status_batal == 1 ){
                $row[] = '<div class="center"><i class="fa fa-times-circle bigger-150 red"></i></div>';
                $is_batal = '<span style="font-weight: bold; color: red">Batal Berobat</span>';
            }else{
                $row[] = '<div class="center">'.$status_icon.'</div>';
                $is_batal = '';
            }
            $row[] = '<div class="center"><div class="btn-group">
                        <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">
                            <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-inverse">
                            '.$btn_delete.'
                            <li><a href="#" onclick="ubah_penjamin_pasien('.$row_list->no_registrasi.','.$row_list->no_kunjungan.')">Ubah Penjamin Pasien</a></li>
                            <li><a href="#" onclick="show_modal('."'registration/reg_pasien/form_modal_edit_dokter/".$row_list->no_registrasi."/".$row_list->no_kunjungan."'".' ,'."'UBAH DOKTER PEMERIKSA'".')">Ubah Dokter Pemeriksa</a></li>
                            '.$btn_view_hasil_pm.'
                            '.$btn_print_out_checklist_mcu.'
                            '.$btn_cetak_sep.'
                            <li><a href="#" onclick="PopupCenter('."'registration/Reg_klinik/print_bukti_pendaftaran_pasien_small?nama=".$row_list->nama_pasien."&no_mr=".$row_list->no_mr."&no_reg=".$row_list->no_registrasi."&poli=".$row_list->nama_bagian."&dokter=".$row_list->nama_pegawai."&nasabah=".$penjamin."'".', '."'FORM BUKTI PENDAFTARAN PASIEN'".', 950, 550)">Cetak Bukti Pendaftaran</a></li>
                            <li class="divider"></li>
                            <li><a href="#" onclick="show_modal('."'registration/reg_pasien/view_detail_resume_medis/".$row_list->no_registrasi."'".', '."'RESUME MEDIS'".')">Selengkapnya</a></li>
                        </ul>
                    </div></div>';

            $no_antrian = (substr($row_list->kode_bagian_tujuan, 0,2) == '01') ? '<br> No. Antrian : <b style="font-size:12px">'.$row_list->no_antrian.'</b>' : '';
            $nama_dokter = ($row_list->nama_pegawai != '') ? $row_list->nama_pegawai.'<br>' : '' ;
            $row[] = $row_list->no_registrasi.' - '.$penjamin.'<br>'.ucfirst($row_list->nama_bagian).'<br>'.$nama_dokter.'<small style="font-size:11px">'.$this->tanggal->formatDateTime($row_list->tgl_masuk).' s/d '.$this->tanggal->formatDateTime($row_list->tgl_keluar).'</small>'.$no_antrian.' '.$tipe_daftar.'<br>'.$is_batal;
            
            $row[] = $this->tanggal->formatDateTime($row_list->tgl_masuk);
            
            $row[] = $this->tanggal->formatDateTime($row_list->tgl_keluar);
            
            $row[] = ucfirst($row_list->nama_bagian);
            
            $row[] = $row_list->nama_pegawai;
            
            //$penjamin = ($row_list->nama_perusahaan=='')?'Umum':($row_list->kode_perusahaan==120 && $row_list->no_sep!='')?$row_list->nama_perusahaan.' ('.$row_list->no_sep.')':$row_list->nama_perusahaan;
            

            
            
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

            $label_status = ($row_list->tgl_jam_keluar!=NULL) ? '<label class="label label-sm label-success" >Selesai</label>' : '<label class="label label-danger" >Masih Dalam Proses</label>';

            $row[] = '<a href="#" onclick="show_modal('."'registration/reg_pasien/view_detail_resume_medis/".$row_list->no_registrasi."'".', '."'RESUME MEDIS'".')">'.$row_list->no_registrasi.' ('.$row_list->seri_kuitansi.'-'.$row_list->no_kuitansi.')</a><br>'.$row_list->nama_pegawai.'<br>'.$this->tanggal->formatDate($row_list->tgl_jam).'<br>'.number_format($row_list->bill).' '.$label_status;

            $row[] = $row_list->seri_kuitansi.'-'.$row_list->no_kuitansi;

            $row[] = $this->tanggal->formatDate($row_list->tgl_jam);
                        
            $row[] = number_format($row_list->bill);
            
            $row[] = $row_list->nama_pegawai;
            
            /*status keluar pasien*/
            
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

        $column = array('tc_pesanan.id_tc_pesanan, tc_pesanan.nama, tc_pesanan.tgl_pesanan, tc_pesanan.no_mr, mt_bagian.nama_bagian, mt_karyawan.nama_pegawai, mt_perusahaan.nama_perusahaan, tc_pesanan.tgl_masuk, tc_pesanan.kode_dokter, tc_pesanan.no_poli, tc_pesanan.kode_perjanjian, tc_pesanan.unique_code_counter, tc_pesanan.selected_day, tc_pesanan.jd_id, tc_pesanan.kode_perusahaan');

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
                $html = '';

                if( isset($_GET['no_mr']) AND $_GET['no_mr'] != '' ){
                    // $html .= '<li><a href="#" onclick="changeModulRjFromPerjanjian('.$row_list->id_tc_pesanan.','.$row_list->kode_dokter.','."'".$row_list->no_poli."'".','."'".$row_list->kode_perjanjian."'".')">Daftarkan Pasien</a></li>';
                }else{
                    $html .= '<li><a href="#" onclick="getMenu('."'registration/Reg_klinik?idp=".$row_list->id_tc_pesanan."&kode_dokter=".$row_list->kode_dokter."&poli=".$row_list->no_poli."&kode_perjanjian=".$row_list->kode_perjanjian."&no_mr=".$row_list->no_mr."'".')">Daftarkan Pasien</a></li>';
                }

                if( isset($_GET['flag']) AND $_GET['flag']=='HD' ){
                    $tgl = $row_list->selected_day;
                }else{
                    $tgl = $this->tanggal->formatDate($row_list->tgl_pesanan);
                }
                $penjamin = ($row_list->nama_perusahaan==NULL)?'<div class="left">PRIBADI/UMUM</div>':'<div class="left">'.$row_list->nama_perusahaan.'</div>';

                $label_code = ($row_list->tgl_masuk == NULL) ? '<div class="pull-right"><span class="red" style="font-weight: bold; font-size: 16px;">'.$row_list->unique_code_counter.'</span></div>' : '<div class="pull-right"><span class="green" style="font-weight: bold; font-size: 16px; cursor: pointer">'.$row_list->unique_code_counter.'</span></div>';

                $row[] = '<div class="center">
                            <label class="pos-rel">
                                <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->id_tc_pesanan.'"/>
                                <span class="lbl"></span>
                            </label>
                          </div>';
                $row[] = '<div class="center"><div class="btn-group">
                            <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">
                                <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-inverse">
                                '.$html.'
                                <li><a href="#" onclick="show_modal('."'registration/Reg_pasien/surat_control?id_tc_pesanan=".$row_list->id_tc_pesanan."&jd_id=".$row_list->jd_id."'".')">Cetak Surat Kontrol</a></li>
                                <li><a href="#" onclick="delete_perjanjian('.$row_list->id_tc_pesanan.')" >Hapus</a></li>
                            </ul>
                        </div></div>';
                if( $row_list->kode_perusahaan == 120){
                    $row[] = $row_list->nama_pegawai.'<br><small style="font-size: 11px">'.ucwords($row_list->nama_bagian).'</small><br>'.$tgl.'<br>'.$penjamin.'<br><small class="pull-right">Kode Booking : </small><br><a href="#" onclick="changeModulRjFromPerjanjianBPJS('.$row_list->id_tc_pesanan.','.$row_list->kode_dokter.','."'".$row_list->no_poli."'".','."'".$row_list->kode_perjanjian."'".')">'.$label_code.'</a>';
                }else{
                    $row[] = $row_list->nama_pegawai.'<br><small style="font-size: 11px">'.ucwords($row_list->nama_bagian).'</small><br>'.$tgl.'<br>'.$penjamin.'<a href="#" onclick="changeModulRjFromPerjanjian('.$row_list->id_tc_pesanan.','.$row_list->kode_dokter.','."'".$row_list->no_poli."'".','."'".$row_list->kode_perjanjian."'".')">'.$label_code.'</a>';
                }

                $row[] = '<div class="center">'.$row_list->no_mr.'</div>';
                $row[] = '<a href="#">'.strtoupper($row_list->nama).'</>';
                

                
                $row[] = '<div class="left">'.$row_list->nama_pegawai.'</div>';
                
                $row[] = $row_list->kode_perjanjian;
                $row[] = ($row_list->tgl_masuk == NULL) ? '<div class="center"><span class="label label-sm label-danger"><i class="fa fa-times-circle"></i></span></div>' : '<div class="center"><span class="label label-sm label-success"><i class="fa fa-check"></i></span></div>';


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

    public function konfirmasi_fp($no_kartu_bpjs) { 
        
        // $result = $this->db->join('tc_kunjungan', 'tc_kunjungan.no_kunjungan = pl_tc_poli.no_kunjungan', 'left')->join('mt_master_pasien', 'mt_master_pasien.no_mr=tc_kunjungan.no_mr AND CAST(tc_kunjungan.tgl_masuk as DATE) = '."'".date('Y-m-d')."'".'', 'left')->get_where('pl_tc_poli', array('mt_master_pasien.no_kartu_bpjs' => $no_kartu_bpjs))->row();
        // echo $this->db->last_query();
        $data = [
            'no_kartu_bpjs' => $no_kartu_bpjs,
        ];
        $this->load->view('Reg_pasien/form_konfirm_fp', $data);
    
    }

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
        // echo '<pre>'; print_r($data);die;
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

    public function form_modal_edit_dokter($no_registrasi, $no_kunjungan){
        
        $data = [
            'result' => $this->Reg_pasien->get_detail_resume_medis($no_registrasi, $no_kunjungan),
            'no_registrasi' => $no_registrasi,
            'no_kunjungan' => $no_kunjungan,
        ];
        
        /*load form view*/
        
        $this->load->view('Reg_pasien/form_modal_edit_dokter', $data);

    }

    public function form_modal_view_hasil_pm($no_registrasi, $no_kunjungan, $kode_penunjang='', $kode_bagian_pm=''){
        
        /*load class*/
        $csm_bp = new Csm_billing_pasien;
        $reg_pm = new Reg_pm_model;
        $data = [];
        $flag = $this->master->get_short_name_pm($kode_bagian_pm);
        /*get content html*/
        $html = json_decode($csm_bp->getHtmlData($data, $no_registrasi, $flag, $kode_penunjang, '', $no_kunjungan, ''));
        /*load form view*/
        $data = array(
            // 'html' => $hasil_pm['html'],
            'html' => $html->html,
        );
        // echo '<pre>';print_r($data);die;
        if(isset($_GET['format']) && $_GET['format'] == 'html'){
            echo json_encode(['html' => $this->load->view('Reg_pasien/form_modal_view_hasil_pm', $data, true)]);
        }else{
            $this->load->view('Reg_pasien/form_modal_view_hasil_pm', $data);
        }

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

        /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Edit '.strtolower($this->title).'', 'registration/Input_pasien_baru/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$noMr);

        $data['flag'] = "update";

        $data['breadcrumbs'] = '';

        $data['title'] = 'Edit data pasien';

        $data['value'] = $this->Input_pasien_baru->get_by_mr($noMr);
        //echo '<pre>';print_r($data);die;
        /*load form view*/
        
        //$this->load->view('Input_pasien_baru/form', $data);

        $this->load->view('Reg_pasien/form_modal', $data);
    
    }

    public function form_modal_ttd($noMr='')
    {
        
        $data = array();
        
        /*if id is not null then will show form edit*/
        
        //$data_pasien = $this->Input_pasien_baru->get_by_mr($noMr);

        /*echo '<pre>'; print_r($data_pasien);*/

        /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Edit '.strtolower($this->title).'', 'registration/Input_pasien_baru/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$noMr);

        $data['flag'] = "update";

        $data['breadcrumbs'] = '';

        $data['title'] = 'Edit data pasien';

        $data['value'] = $this->Input_pasien_baru->get_by_mr($noMr);
        //echo '<pre>';print_r($data);die;
        /*load form view*/
        
        //$this->load->view('Input_pasien_baru/form', $data);

        $this->load->view('Reg_pasien/form_modal_ttd', $data);
    
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

    public function form_perjanjian_ontabs($id='')
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
        
        $this->load->view('Reg_pasien/form_perjanjian_ontabs', $data);
    
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
        
        $data = array(
            'kode_bagian' => isset($_GET['kode_bagian'])?$_GET['kode_bagian']:'',
            'kode_dokter' => isset($_GET['kode_dokter'])?$_GET['kode_dokter']:'',
        );
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
            case 'RJ-PJ':
                $view_modul = 'Reg_pasien/form_rajal_pj';
                break;
            default:
                $view_modul = 'Reg_pasien/index';
                break;
        }

        $this->load->view($view_modul, $data);
    
    }

    public function getDetailTransaksi($no_registrasi){
        
        $tipe = 'RJ';
        /*get detail data billing*/
        $data = json_decode($this->Migration->getDetailData($no_registrasi));
        
        if($tipe=='RJ'){
            $html = $this->Migration->getDetailBillingRJContentOnly($no_registrasi, $tipe, $data);
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
        // echo '<pre>'; print_r($_POST);die;
         $this->load->library('form_validation');
         $val = $this->form_validation;
 
         if($_POST['jenis_instalasi']=='BD'){
             $tgl = $this->tanggal->sqlDateForm($this->input->post('tanggal_perjanjian_bedah'));
             $bag = '30901';
         }else if($_POST['jenis_instalasi']=='PM'){
             $tgl = $this->tanggal->sqlDateForm($this->input->post('tanggal_perjanjian_pm'));
             $bag = $this->input->post('pm_tujuan');
         }else{
             $tgl = $this->input->post('tanggal_kunjungan');
             $bag =  $this->input->post('klinik_rajal');
         }
         
         if(isset($_POST['is_no_mr']) AND $_POST['is_no_mr']=='Y'){
             $val->set_rules('nama_pasien', 'Nama Pasien', 'trim|required');
             $val->set_rules('alamat', 'Alamat', 'trim|required');

         }else{
             $val->set_rules('no_mr', 'MR Pasien', 'trim|required', array('required' => 'MR Pasien tidak ditemukan'));
         }
         
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
         }else if($_POST['jenis_instalasi']=='RJ' || $_POST['jenis_instalasi']=='RJ-PJ'){
             $val->set_rules('klinik_rajal', 'Klinik', 'trim|required');
             $val->set_rules('tanggal_kunjungan', 'Tanggal Kunjungan', 'trim|required');
             $val->set_rules('dokter_rajal', 'Dokter', 'trim|required');
             $val->set_rules('selected_time', 'Jam Praktek', 'trim|required');
             $date = date_create($this->input->post('tanggal_kunjungan').' '.$this->input->post('time_start') );
             $jam_pesanan = date_format($date, 'Y-m-d H:i:s');
         }else{
             $val->set_rules('perjanjian_tindakan_pm', 'Nama Tindakan', 'trim');
             if($_POST['pm_tujuan']=='050201'){
                 $val->set_rules('bulan_kunjungan', 'Bulan', 'trim|required');
             }else{
                 $val->set_rules('tanggal_perjanjian_pm', 'Tanggal Perjanjian', 'trim|required');
             }
             $val->set_rules('dokter_rajal', 'Dokter', 'trim|required');
             $val->set_rules('pm_tujuan', 'Penunjang Medis', 'trim|required');
             $jam_pesanan = $tgl;
         }
        
         if($this->input->post('jenis_penjamin')=='Jaminan Perusahaan'){
             $val->set_rules('kode_perusahaan', 'Nama Perusahaan', 'trim|required');
         }

        if($this->input->post('kode_perusahaan')==120){
            if(isset($_POST['is_no_mr']) AND $_POST['is_no_mr']=='Y'){
                $val->set_rules('no_rujukan', 'No Rujukan', 'trim|required');    
            }else{
                $val->set_rules('no_sep_lama', 'No SEP Lama', 'trim|required', array('required' => 'Silahkan masukan No SEP Lama/Terakhir'));   
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

            $id = ($this->input->post('id_tc_pesanan'))?$this->regex->_genRegex($this->input->post('id_tc_pesanan'),'RGXINT'):0;
            $ex_no_surat_kontrol = ($this->input->post('no_surat_kontrol'))?$this->regex->_genRegex($this->input->post('no_surat_kontrol'),'RGXQSL'):0;

            $check_surat_kontrol = $this->Ws_index->check_surat_kontrol_by_no($ex_no_surat_kontrol);
            $response_dt = isset($check_surat_kontrol['response']) ? $check_surat_kontrol : false;
            $is_bridging = ( $response_dt['response']->metaData->code == 200 ) ? 1 : 0;

            $kode_faskes = '0112R034';
            /*get_unique_code*/
            $string = $kode_faskes.$this->input->post('nama_pasien').$this->input->post('no_mr');
            $unique_code_max = date('my').$this->master->generateRandomString($string, 6);

            $ket_rujukan_internal = isset($_POST['jenis_perjanjian']) ? ($_POST['jenis_perjanjian'] == 1) ? 'Rujukan Internal - ' : '' : '';
            $dataexc = array(
                'nama' => $this->regex->_genRegex($this->input->post('nama_pasien'), 'RGXQSL'),
                'keterangan' => $ket_rujukan_internal.$this->regex->_genRegex($this->input->post('keterangan'), 'RGXQSL'),
                'alamat' => $this->regex->_genRegex($this->input->post('alamat'), 'RGXQSL'),
                'no_poli' => $bag,
                'kode_dokter' => $this->regex->_genRegex($val->set_value('dokter_rajal'), 'RGXQSL'),
                'penjamin' => $this->regex->_genRegex($this->input->post('jenis_penjamin'), 'RGXQSL'),
                'no_induk' => isset($this->session->userdata('user')->user_id)?$this->session->userdata('user')->user_id:0,
                'input_tgl' => date('Y-m-d h:i:s'),
                'unique_code_counter' =>  strtoupper($unique_code_max),
                'no_telp' => $this->input->post('no_telp'),
                'no_hp' => $this->input->post('no_hp'),
                'jd_id' => $this->input->post('jd_id'),
                'is_bridging' => $is_bridging,
            );

            if($dataexc['no_poli'] != '50201'){
                $dataexc['tgl_pesanan'] = $tgl;
                $dataexc['jam_pesanan'] = $jam_pesanan;
            }

            if(isset($_POST['is_no_mr']) AND $_POST['is_no_mr']=='Y'){
                $dataexc['norujukan'] = $this->input->post('no_rujukan');
            }else{
                $dataexc['no_mr'] = $this->regex->_genRegex($val->set_value('no_mr'), 'RGXQSL');
                $dataexc['no_sep_lama'] = $this->input->post('no_sep_lama');
            }

             if($this->input->post('jenis_penjamin')=='Jaminan Perusahaan'){
                $dataexc['kode_perusahaan'] = $this->regex->_genRegex($val->set_value('kode_perusahaan'), 'RGXINT');
             }
 
             if($_POST['jenis_instalasi']=='BD'){
                 $dataexc['flag']='bedah';
                 $dataexc['diagnosa']=$this->regex->_genRegex($val->set_value('diagnosa_perjanjian_bedah'), 'RGXQSL');
                 $dataexc['kode_tarif'] = $this->regex->_genRegex($val->set_value('perjanjian_tindakan_bedah'), 'RGXQSL');
             }
             
             if($_POST['jenis_instalasi']=='PM'){
                $dataexc['kode_tarif'] = $this->regex->_genRegex($val->set_value('perjanjian_tindakan_pm'), 'RGXQSL');
                $dataexc['bulan_kunjungan'] = $this->regex->_genRegex($this->input->post('bulan_kunjungan'), 'RGXQSL');
                $dataexc['referensi_no_kunjungan'] = $this->input->post('no_kunjungan');
             }
            //  print_r($dataexc);die;
             if($id==0){
                $kode_perjanjian = ($ex_no_surat_kontrol != 0) ? $ex_no_surat_kontrol : $kode_faskes.$unique_code_max;
                $dataexc['kode_perjanjian'] = $kode_perjanjian;
                /*save post data*/
                $newId = $this->Perjanjian->save($dataexc);
                 /*save log kuota dokter*/
                if( !isset($_POST['is_no_mr']) AND $_POST['is_no_mr'] != 'Y' ){
                    $this->logs->save_log_kuota(array('kode_dokter' => $dataexc['kode_dokter'], 'kode_spesialis' => $dataexc['no_poli'], 'tanggal' => $dataexc['tgl_pesanan'], 'keterangan' => $dataexc['keterangan'], 'flag' => 'perjanjian' ));
                }

                // add antrian to mobile jkn
                $dataexc['jampraktek'] = str_replace(' s/d ', '-', $_POST['selected_time']);
                $dataexc['rujukan_internal'] = isset($_POST['no_ktp'])?$_POST['no_ktp']:'';
                $dataexc['no_ktp'] = isset($_POST['no_ktp'])?$_POST['no_ktp']:'';
                $dataexc['nomorkartubpjs'] = isset($_POST['no_kartu_bpjs'])?$_POST['no_kartu_bpjs']:'';
                $dataexc['jeniskunjungan'] = isset($_POST['jeniskunjungan'])?$_POST['jeniskunjungan']:'';
                $dataexc['no_sep_lama'] =isset( $_POST['no_sep_lama'])? $_POST['no_sep_lama']:'';
                $dataexc['nomorreferensi'] = (!empty($_POST['no_surat_kontrol']))?$_POST['no_surat_kontrol']:0;
                
             }else{
                 /*update record*/
                 $dataexc['keterangan'] = 'Reschedule Perjanjian';
                 $this->Perjanjian->update(array('id_tc_pesanan' => $id), $dataexc);
                 $newId = $id;
                 /*save logs*/
                 $this->logs->save('tc_pesanan', $newId, 'update record on '.$this->title.' module', json_encode($dataexc),'id_tc_pesanan');
             }
 
             if ($this->db->trans_status() === FALSE)
             {
                 $this->db->trans_rollback();
                 echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
             }
             else
             {
                 $this->db->trans_commit();
                 $is_kiosk = isset($_POST['kiosk'])?$_POST['kiosk']:0;
                 echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'id_tc_pesanan' => $newId, 'jd_id' => $_POST['jd_id'], 'redirect' => 'registration/Reg_pasien/surat_control?id_tc_pesanan='.$newId.'&jd_id='.$_POST['jd_id'].'&kiosk='.$is_kiosk.'', 'type_pelayanan' => 'create_perjanjian'));
             }
 
         }

    }


    public function surat_control(){
        
        $id_tc_pesanan = $_GET['id_tc_pesanan'];
        $jd_id = $_GET['jd_id'];
        $data['jd_id'] = $jd_id;
        $data['id_tc_pesanan'] = $id_tc_pesanan;
        $data['value'] = $this->Reg_pasien->get_pesanan_pasien_($id_tc_pesanan);
        $data['jadwal'] = $this->Reg_pasien->get_jadwal_dokter($jd_id);
        $data['jenis_printer'] = $this->db->get_where('global_parameter', array('flag' => 'printer_booking'))->result();
        $this->load->view('Reg_pasien/surat_kontrol', $data);
    }

    public function surat_kontrol_popup(){
        
        $id_tc_pesanan = $_GET['id_tc_pesanan'];
        $jd_id = $_GET['jd_id'];
        $data['jd_id'] = $jd_id;
        $data['id_tc_pesanan'] = $id_tc_pesanan;
        $data['value'] = $this->Reg_pasien->get_pesanan_pasien_($id_tc_pesanan);
        $data['jadwal'] = $this->Reg_pasien->get_jadwal_dokter($jd_id);
        // echo "<pre>"; print_r($data);die;
        $this->load->view('Reg_pasien/surat_kontrol_popup', $data);
    }

    public function print_booking($jd_id, $id_tc_pesanan){
        
        $booking = $this->Reg_pasien->get_pesanan_pasien_($id_tc_pesanan);
        $data['value'] = $booking;
        $data['jadwal'] = $this->Reg_pasien->get_jadwal_dokter($jd_id);
        // echo '<pre>';print_r($data);die;
        $this->print_escpos->print_booking($data);
        echo json_encode($_POST);

    }

    public function delete_registrasi(){
        
        $no_registrasi = $_POST['ID'];
        $no_kunjungan = $_POST['KunjunganID'];
        $this->Reg_pasien->delete_registrasi($no_registrasi, $no_kunjungan);
        echo json_encode( array('status'=>200, 'message'=>'Proses hapus data berhasil dilakukan') );

    }

    public function process_edit_transaksi_penjamin_pasien()
    {
        
        
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
                'no_sep' => ($val->set_value('noSepEditPenjamin'))?$this->regex->_genRegex($val->set_value('noSepEditPenjamin'),'RGXALNUM'):'',
                'kode_kelompok' => $this->regex->_genRegex($val->set_value('kode_kelompok_hidden_edit_penjamin'),'RGXINT'),
                'kode_perusahaan' => isset($_POST['kode_perusahaan_hidden_edit_penjamin'])?$this->regex->_genRegex($val->set_value('kode_perusahaan_hidden_edit_penjamin'),'RGXINT'):NULL,
            );

            //print_r($dataexc);die;

            $this->db->update('tc_registrasi', $dataexc, array('no_registrasi' => $val->set_value('no_registrasi_hidden_edit_penjamin') ) );

            $dataexc2 = array(
                'kode_kelompok' => $this->regex->_genRegex($val->set_value('kode_kelompok_hidden_edit_penjamin'),'RGXINT'),
                'kode_perusahaan' => isset($_POST['kode_perusahaan_hidden_edit_penjamin'])?$this->regex->_genRegex($val->set_value('kode_perusahaan_hidden_edit_penjamin'),'RGXINT'):NULL,
            );

            $this->db->update('fr_tc_pesan_resep', $dataexc2, array('no_registrasi' => $val->set_value('no_registrasi_hidden_edit_penjamin') ) );


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

    public function process_edit_dokter_pemeriksa()
    {
        // print_r($_POST);die;
        $this->load->library('form_validation');
        $val = $this->form_validation;

        $val->set_rules('no_kunjungan_hidden_edit_dokter', 'No Kunjungan', 'trim|required');
        $val->set_rules('no_registrasi_hidden_edit_dokter', 'No Registrasi', 'trim|required');
        $val->set_rules('kode_edit_dokter_hidden', 'Dokter Pemeriksa', 'trim|required');
        
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
                'kode_dokter' => isset($_POST['kode_edit_dokter_hidden'])?$this->regex->_genRegex($val->set_value('kode_edit_dokter_hidden'),'RGXINT'):NULL,
            );

            // update tc_registrasi
            $this->db->update('tc_registrasi', $dataexc, array('no_registrasi' => $val->set_value('no_registrasi_hidden_edit_dokter') ) );

            // update tc_kunjungan
            $this->db->update('tc_kunjungan', $dataexc, array('no_registrasi' => $val->set_value('no_registrasi_hidden_edit_dokter'), 'no_kunjungan' => $val->set_value('no_kunjungan_hidden_edit_dokter') ) );

            // update pl_tc_poli
            $this->db->update('pl_tc_poli', $dataexc, array('no_kunjungan' => $val->set_value('no_kunjungan_hidden_edit_dokter') ) );

            // update ri_tc_rawatinap
            $this->db->update('ri_tc_rawatinap', array('dr_merawat' => $val->set_value('kode_edit_dokter_hidden') ) , array('no_kunjungan' => $val->set_value('no_kunjungan_hidden_edit_dokter') ) );

            // update ri_tc_rawatinap
            $this->db->update('tc_trans_pelayanan', array('kode_dokter1' => $val->set_value('kode_edit_dokter_hidden') ) , array('no_kunjungan' => $val->set_value('no_kunjungan_hidden_edit_dokter') ) );

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'no_mr' => $_POST['no_mr_hidden_edit_dokter'] ));
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

    public function process_ttd()
    {
        //  print_r($_POST);die;
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
            
            $this->db->update('mt_master_pasien', array('ttd' => $_POST['paramsSignature']), array('no_mr' => $_POST['noMrHiddenPasien'])  );

             if ($this->db->trans_status() === FALSE)
             {
                 $this->db->trans_rollback();
                 echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
             }
             else
             {
                 $this->db->trans_commit();
                 echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'no_mr' => $_POST['noMrHiddenPasien'], 'ttd' => $_POST['paramsSignature']));
             }
 
         }
    }

    public function get_riwayat_medis($no_mr){
        
        $result = $this->db->join('mt_bagian', 'mt_bagian.kode_bagian=th_riwayat_pasien.kode_bagian','left')->order_by('tgl_periksa','DESC')->get_where('th_riwayat_pasien', array('no_mr' => $no_mr))->result();
        
        $transaksi = $this->db->select('kode_trans_pelayanan, no_registrasi, no_kunjungan, nama_tindakan, mt_jenis_tindakan.jenis_tindakan, kode_jenis_tindakan, tgl_transaksi, kode_tc_trans_kasir, nama_pegawai, jumlah_tebus')->join('mt_jenis_tindakan','mt_jenis_tindakan.kode_jenis_tindakan=tc_trans_pelayanan.jenis_tindakan','left')->join('mt_karyawan','mt_karyawan.kode_dokter=tc_trans_pelayanan.kode_dokter1','left')->join('fr_tc_far_detail','fr_tc_far_detail.kd_tr_resep=tc_trans_pelayanan.kd_tr_resep','left')->get_where('tc_trans_pelayanan', array('tc_trans_pelayanan.no_mr' => $no_mr, 'kode_jenis_tindakan' => 11) )->result();

        $penunjang = $this->db->where('SUBSTRING(kode_bagian_tujuan, 1, 2) =', '05')->join('mt_bagian', 'mt_bagian.kode_bagian=tc_kunjungan.kode_bagian_tujuan','left')->join('pm_tc_penunjang', 'pm_tc_penunjang.no_kunjungan=tc_kunjungan.no_kunjungan','left')->get_where('tc_kunjungan', array('no_mr' => $no_mr) )->result();
        $getDataPm = [];
        foreach ($penunjang as $key_pm => $val_pm) {
            $getDataPm[$val_pm->no_registrasi][] = $val_pm;
        }
        // echo '<pre>';print_r($getDataPm);die;

        $getData = array();
        foreach ($transaksi as $key => $value) {
            $getData[$value->no_registrasi] [] = $value;
        }

        $data = array(
            'penunjang' => $getDataPm,
            'result' => $result,
            'obat' => $getData,
        );
        
        $this->load->view('registration/Reg_pasien/view_riwayat_medis_sidebar', $data);
    }

}

/* End of file example.php */

/* Location: ./application/functiones/example/controllers/example.php */

