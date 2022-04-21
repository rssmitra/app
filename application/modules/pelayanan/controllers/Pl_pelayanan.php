<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pl_pelayanan extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'pelayanan/Pl_pelayanan');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('Pl_pelayanan_model', 'Pl_pelayanan');
        $this->load->model('registration/Reg_pasien_model', 'Reg_pasien');
        $this->load->model('ws/AntrianOnlineModel', 'AntrianOnline');
        /*load library*/
        $this->load->library('Form_validation');
        $this->load->library('stok_barang');
        $this->load->library('tarif');
        $this->load->library('daftar_pasien');
        /*enable profiler*/
        $this->output->enable_profiler(false);
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'Title';
        $this->load->module('casemix/Csm_billing_pasien');
        $this->cbpModule = new Csm_billing_pasien;
    }

    public function index() { 
        /*define variable data*/
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show()
        );
        // echo '<pre>'; print_r($this->session->all_userdata());die;
        if( $this->session->userdata('kode_bagian') ){
            /*load view index*/
            $data['kode_bagian'] = $this->session->userdata('kode_bagian');
            $data['nama_bagian'] = $this->session->userdata('nama_bagian');
            $data['nama_dokter'] = $this->session->userdata('sess_nama_dokter');
            // get antrian pasien
            // $antrian_pasien = $this->Pl_pelayanan->get_next_antrian_pasien();
            // $next_pasien = isset($antrian_pasien)?$antrian_pasien: ''; 
            // $this->form($next_pasien->id_pl_tc_poli, $next_pasien->no_kunjungan);
            $this->load->view('Pl_pelayanan/index', $data);
        }else{
            if( isset($_GET['bag']) AND $_GET['bag'] != '' ){
                $data['kode_bagian'] = $_GET['bag'];
                $data['nama_bagian'] = $this->master->get_string_data('nama_bagian','mt_bagian', array('kode_bagian' => $_GET['bag']) );
                $this->load->view('Pl_pelayanan/index', $data);
            }else{
                $this->load->view('Pl_pelayanan/index_no_session_yet', $data);
            }
        }
    }

    public function entry_billing() { 
        /*define variable data*/
        $data = array(
            'title' => 'Entry Billing',
            'breadcrumbs' => $this->breadcrumbs->show()
        );

        $this->load->view('Pl_pelayanan/index_entry_billing', $data);
    }

    public function form($id='', $no_kunjungan)
    {
        /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Pl_pelayanan/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*get value by id*/
        $data['value'] = $this->Pl_pelayanan->get_by_id($id);
        $data['riwayat'] = $this->Pl_pelayanan->get_riwayat_pasien_by_id($no_kunjungan);
        $data['kode_bagian'] = $this->session->userdata('kode_bagian');
        $data['nama_bagian'] = $this->session->userdata('nama_bagian');
        $data['nama_dokter'] = $this->session->userdata('sess_nama_dokter');

        // echo '<pre>';print_r($data['riwayat']);die;
        //$data['transaksi'] = $this->Pl_pelayanan->get_transaksi_pasien_by_id($no_kunjungan);
        /*variable*/
         /*type*/
        if($data['value']->flag_ri==1){
            $kode_klas = $data['value']->kelas_ri;
        }else{
            $kode_klas = 16;
        }

        $data['no_mr'] = $data['value']->no_mr;
        $data['id'] = $id;
        $data['kode_klas'] = $kode_klas;
        $data['kode_profit'] = 2000;
        $data['no_kunjungan'] = $no_kunjungan;
        $data['form_type'] = $_GET['form'];
        
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        // echo '<pre>';print_r($data);die;
        if($_GET['form'] == 'billing_entry'){
            $this->load->view('Pl_pelayanan/form', $data);
        }else{
            if($this->session->userdata('flag_form_pelayanan') == 'dokter'){
                $this->load->view('Pl_pelayanan/form_dr', $data);
            }else{
                $this->load->view('Pl_pelayanan/form', $data);
            }
        }

    }

    public function tindakan($id='', $no_kunjungan='')
    {
         /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Pl_pelayanan/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*get value by id*/
        $data['value'] = $this->Pl_pelayanan->get_by_id($id); 
        // echo '<pre>'; print_r($this->db->last_query());die;
        /*mr*/
        /*type*/
        if($data['value']->flag_ri==1){
            $kode_klas = $data['value']->kelas_ri;
        }else{
            $kode_klas = 16;
        }

        $data['type'] = $_GET['type'];
        if(isset($_GET['cito'])) $data['cito'] = $_GET['cito'];
        $data['no_mr'] = $data['value']->no_mr;
        $data['no_kunjungan'] = $no_kunjungan;
        $data['id_pl_tc_poli'] = $id;
        $data['status_pulang'] = (empty($data['value']->tgl_keluar_poli))?0:1;
        $data['kode_klas'] = $kode_klas;
        $data['sess_kode_bag'] = ($_GET['kode_bag'])?$_GET['kode_bag']:$this->session->userdata('kode_bagian');
        // echo '<pre>'; print_r($data);die;
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Pl_pelayanan/form_tindakan', $data);
    }

    public function diagnosa($id='', $no_kunjungan='')
    {
         /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Pl_pelayanan/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*get value by id*/
        $data['value'] = $this->Pl_pelayanan->get_by_id($id); 
        // echo '<pre>'; print_r($this->db->last_query());die;
        $data['riwayat'] = $this->Pl_pelayanan->get_riwayat_pasien_by_id($no_kunjungan);
        /*mr*/
        /*type*/
        if(isset($data['value']->flag_ri) AND $data['value']->flag_ri==1){
            $kode_klas = $data['value']->kelas_ri;
        }else{
            $kode_klas = 16;
        }

        $data['type'] = $_GET['type'];
        if(isset($_GET['cito'])) $data['cito'] = $_GET['cito'];
        $data['no_mr'] = $data['value']->no_mr;
        $data['no_kunjungan'] = $no_kunjungan;
        $data['id_pl_tc_poli'] = $id;
        $data['status_pulang'] = (empty($data['value']->tgl_keluar_poli))?0:1;
        $data['kode_klas'] = $kode_klas;
        $data['sess_kode_bag'] = ($_GET['kode_bag'])?$_GET['kode_bag']:$this->session->userdata('kode_bagian');
        //echo '<pre>'; print_r($data);die;
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Pl_pelayanan/form_diagnosa', $data);
    }

    public function diagnosa_dr($id='', $no_kunjungan='')
    {
         /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Pl_pelayanan/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*get value by id*/
        $data['value'] = $this->Pl_pelayanan->get_by_id($id); 
        // echo '<pre>'; print_r($this->db->last_query());die;
        $data['riwayat'] = $this->Pl_pelayanan->get_riwayat_pasien_by_id($no_kunjungan);
        /*mr*/
        /*type*/
        if(isset($data['value']->flag_ri) AND $data['value']->flag_ri==1){
            $kode_klas = $data['value']->kelas_ri;
        }else{
            $kode_klas = 16;
        }

        $data['type'] = $_GET['type'];
        if(isset($_GET['cito'])) $data['cito'] = $_GET['cito'];
        $data['no_mr'] = $data['value']->no_mr;
        $data['no_kunjungan'] = $no_kunjungan;
        $data['id_pl_tc_poli'] = $id;
        $data['status_pulang'] = (empty($data['value']->tgl_keluar_poli))?0:1;
        $data['kode_klas'] = $kode_klas;
        $data['sess_kode_bag'] = ($_GET['kode_bag'])?$_GET['kode_bag']:$this->session->userdata('kode_bagian');
        //echo '<pre>'; print_r($data);die;
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Pl_pelayanan/form_diagnosa_dr', $data);
    }

    public function input_hasil_pemeriksaan($id='', $no_kunjungan='')
    {
         /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Pl_pelayanan/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*get value by id*/
        $data['value'] = $this->Pl_pelayanan->get_by_id($id); 
        $data['riwayat'] = $this->Pl_pelayanan->get_riwayat_expertise_pasien($no_kunjungan);
        // echo '<pre>'; print_r($this->db->last_query());die;
        /*mr*/
        /*type*/
        if($data['value']->flag_ri==1){
            $kode_klas = $data['value']->kelas_ri;
        }else{
            $kode_klas = 16;
        }

        $data['type'] = $_GET['type'];
        if(isset($_GET['cito'])) $data['cito'] = $_GET['cito'];
        $data['no_mr'] = $data['value']->no_mr;
        $data['no_kunjungan'] = $no_kunjungan;
        $data['id_pl_tc_poli'] = $id;
        $data['status_pulang'] = (empty($data['value']->tgl_keluar_poli))?0:1;
        $data['kode_klas'] = $kode_klas;
        $data['sess_kode_bag'] = ($_GET['kode_bag'])?$_GET['kode_bag']:$this->session->userdata('kode_bagian');
        //echo '<pre>'; print_r($data);die;
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        $data['jenis_expertise'] = $_GET['title'];
        $data['kode_bag_expertise'] = $_GET['kode_bag_input'];
        /*load form view*/
        $this->load->view('Pl_pelayanan/form_input_hasil', $data);
    }

    public function form_end_visit()
    {
        $no_kunjungan = isset($_GET['no_kunjungan'])?$_GET['no_kunjungan']:'';
        $riwayat = $this->Reg_pasien->get_detail_kunjungan_by_no_kunjungan($no_kunjungan);
        $data = array(
            'no_mr' => isset($_GET['no_mr'])?$_GET['no_mr']:'',
            'id' => isset($_GET['id'])?$_GET['id']:'',
            'no_kunjungan' => $no_kunjungan,
            'riwayat' => $riwayat,
            );

        /*load form view*/
        $this->load->view('Pl_pelayanan/form_end_visit', $data);
    }

    public function form_tindakan_lain()
    {

        $flag = $_GET['flag'];
        $data = array(
            'title' => 'Tambah Tindakan '.ucfirst($flag).'',
            'breadcrumbs' => $this->breadcrumbs->show(),
            'flag' => $flag
            );

        /*load form view*/
        $this->load->view('Pl_pelayanan/form_tindakan_lain', $data);
    }

    public function get_data_antrian_pasien(){
        $list = $this->Pl_pelayanan->get_data_antrian_pasien();
        echo json_encode($list);

    }
    public function get_data()
    {
        /*akan di filter berdasarkan pasien pada klinik masing2*/
        /*get data from model*/
        $list = $this->Pl_pelayanan->get_datatables();
        $form_type = isset($_GET['form'])?$_GET['form']:'form_rj';
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center">
                        <label class="pos-rel">
                            <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->no_kunjungan.'"/>
                            <span class="lbl"></span>
                        </label>
                    </div>';
            /*fungsi rollback pasien, jika belum disubmit kasir maka poli masih bisa melakukan rollback*/
            /*cek transaksi*/
            //$trans_kasir = ($row_list->status_periksa!=NULL)?TRUE:FALSE;
            $trans_kasir = $this->Pl_pelayanan->cek_transaksi_kasir($row_list->no_registrasi,$row_list->no_kunjungan);
            //$rollback_btn = ($trans_kasir==true)?'<li><a href="#" onclick="rollback('.$row_list->no_registrasi.','.$row_list->no_kunjungan.')">Rollback</a></li>':'';
            $flag_rollback = ($trans_kasir!=true)?'submited':'unsubmit';

            $rollback_btn = ($flag_rollback == 'unsubmit') ? '<li><a href="#" onclick="rollback('.$row_list->no_registrasi.','.$row_list->no_kunjungan.','."'".$flag_rollback."'".')">Rollback</a></li>' : '';
            

            $cancel_btn = ($row_list->status_periksa==NULL) ? '<li><a href="#" onclick="cancel_visit('.$row_list->no_registrasi.','.$row_list->no_kunjungan.')">Batalkan Kunjungan</a></li>' : '' ;

            $row[] = '<div class="center"><div class="btn-group">
                        <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">
                            <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-inverse">
                            '.$rollback_btn.' '.$cancel_btn.'                            
                            <li><a href="#" onclick="show_modal('."'pelayanan/Pl_pelayanan/form_perjanjian_modal/".$row_list->no_mr."?kode_bagian=".$row_list->kode_bagian."&kode_dokter=".$row_list->kode_dokter."&kode_perusahaan=".$row_list->kode_perusahaan."'".', '."'PERJANJIAN PASIEN'".')">Perjanjian Pasien</a></li>
                            <li><a href="#" onclick="show_modal('."'registration/reg_pasien/view_detail_resume_medis/".$row_list->no_registrasi."'".', '."'RESUME MEDIS'".')">Selengkapnya</a></li>
                        </ul>
                    </div></div>';

            $row[] = '<div class="center"><a href="#" onclick="getMenu('."'pelayanan/Pl_pelayanan/form/".$row_list->id_pl_tc_poli."/".$row_list->no_kunjungan."?no_mr=".$row_list->no_mr."&form=".$form_type."'".')">'.$row_list->no_kunjungan.'</a></div>';
            $row[] = '<div class="center">'.$row_list->no_mr.'</div>';
            $row[] = strtoupper($row_list->nama_pasien);

            if($row_list->kode_perusahaan == 120){
                $nm_perusahaan = $row_list->nama_perusahaan;
            }else{
                $nm_perusahaan = ($row_list->nama_perusahaan)?$row_list->nama_perusahaan:'<b style="color: green !important">'.strtoupper($row_list->nama_kelompok).'</b>';
            }
            $row[] = $nm_perusahaan;

            $row[] = '<div class="left"><span class="green" style="font-weight: bold">In&nbsp;&nbsp;&nbsp;</span> '.$this->tanggal->formatDateTimeFormDmy($row_list->tgl_jam_poli).'<br><span class="red" style="font-weight: bold">Out&nbsp;</span> '.$this->tanggal->formatDateTimeFormDmy($row_list->tgl_keluar_poli).' </div>';
            $row[] = $row_list->nama_pegawai;
            $row[] = '<div class="center">'.$row_list->no_antrian.'</div>';
            $row[] = '<div class="center">'.$row_list->created_by.'</div>';

            if($row_list->status_batal==1){
                $status_periksa = '<label class="label label-danger"><i class="fa fa-times-circle"></i> Batal Berobat</label>';
            }else{
                if($row_list->tgl_keluar_poli==NULL || empty($row_list->tgl_keluar_poli)){
                    $status_periksa = '<label class="label label-warning"><i class="fa fa-info-circle"></i> Belum diperiksa</label>';
                }else {
                    if($flag_rollback != 'submited'){
                        $status_periksa = '<label class="label label-success"><i class="fa fa-check-circle"></i> Selesai</label>';
                        
                    }else{
                        $status_periksa = '<label class="label label-primary"><i class="fa fa-money"></i> Lunas</label>';

                    }
                }
            }

            $row[] = '<div class="center">'.$status_periksa.'</div>';
           
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Pl_pelayanan->count_all(),
                        "recordsFiltered" => $this->Pl_pelayanan->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function get_data_entry_billing()
    {
        /*akan di filter berdasarkan pasien pada klinik masing2*/
        /*get data from model*/
        $list = $this->Pl_pelayanan->get_datatables();
        $form_type = isset($_GET['form'])?$_GET['form']:'form_rj';
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '';
            $row[] = '';
            $row[] = $row_list->no_kunjungan;
            $row[] = $row_list->no_registrasi;
            /*fungsi rollback pasien, jika belum disubmit kasir maka poli masih bisa melakukan rollback*/
            /*cek transaksi*/
            //$trans_kasir = ($row_list->status_periksa!=NULL)?TRUE:FALSE;
            $trans_kasir = $this->Pl_pelayanan->cek_transaksi_kasir($row_list->no_registrasi,$row_list->no_kunjungan);
            //$rollback_btn = ($trans_kasir==true)?'<li><a href="#" onclick="rollback('.$row_list->no_registrasi.','.$row_list->no_kunjungan.')">Rollback</a></li>':'';
            $flag_rollback = ($trans_kasir!=true)?'submited':'unsubmit';

            $rollback_btn = ($flag_rollback == 'unsubmit') ? '<li><a href="#" onclick="rollback('.$row_list->no_registrasi.','.$row_list->no_kunjungan.','."'".$flag_rollback."'".')">Rollback</a></li>' : '';
            

            $cancel_btn = ($row_list->status_periksa==NULL) ? '<li><a href="#" onclick="cancel_visit('.$row_list->no_registrasi.','.$row_list->no_kunjungan.')">Batalkan Kunjungan</a></li>' : '' ;

            // <li><a href="#" onclick="show_modal('."'pelayanan/Pl_pelayanan/form_perjanjian_modal/".$row_list->no_mr."?kode_bagian=".$row_list->kode_bagian."&kode_dokter=".$row_list->kode_dokter."&kode_perusahaan=".$row_list->kode_perusahaan."&no_sep=".$row_list->no_sep."'".', '."'PERJANJIAN PASIEN'".')">Perjanjian Pasien</a></li> 

            $row[] = '<div class="center"><div class="btn-group">
                        <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">
                            <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-inverse">
                            '.$rollback_btn.' '.$cancel_btn.'                            

                            <li><a href="#" onclick="getMenu('."'pelayanan/Pl_pelayanan/form_perjanjian_view/".$row_list->no_mr."?kode_bagian=".$row_list->kode_bagian."&kode_dokter=".$row_list->kode_dokter."&kode_perusahaan=".$row_list->kode_perusahaan."&no_sep=".$row_list->no_sep."'".')">Perjanjian Pasien</a></li>

                            <li><a href="#" onclick="show_modal('."'registration/reg_pasien/view_detail_resume_medis/".$row_list->no_registrasi."'".', '."'RESUME MEDIS'".')">Selengkapnya</a></li>
                        </ul>
                    </div></div>';

            $row[] = '<div class="center"><a href="#" onclick="getMenu('."'pelayanan/Pl_pelayanan/form/".$row_list->id_pl_tc_poli."/".$row_list->no_kunjungan."?no_mr=".$row_list->no_mr."&form=".$form_type."'".')">'.$row_list->no_kunjungan.'</a></div>';
            $row[] = '<div class="center">'.$row_list->no_mr.'</div>';
            $row[] = strtoupper($row_list->nama_pasien);
            $no_sep = ($row_list->kode_perusahaan==120)?'<br><b>'.$row_list->no_sep.'</b>':'';
            $row[] = ($row_list->nama_perusahaan)?$row_list->nama_perusahaan.''.$no_sep:$row_list->nama_kelompok;
            $row[] = '<div class="left"><span class="green" style="font-weight: bold">In&nbsp;&nbsp;&nbsp;</span> '.$this->tanggal->formatDateTimeFormDmy($row_list->tgl_jam_poli).'<br><span class="red" style="font-weight: bold">Out&nbsp;</span> '.$this->tanggal->formatDateTimeFormDmy($row_list->tgl_keluar_poli).' </div>';
            $row[] = $row_list->nama_pegawai;
            // $row[] = '<div class="center">'.$row_list->no_antrian.'</div>';
            $row[] = '<div class="center">'.$row_list->created_by.'</div>';

            if($row_list->status_batal==1){
                $status_periksa = '<label class="label label-danger"><i class="fa fa-times-circle"></i> Batal Berobat</label>';
            }else{
                if($row_list->tgl_keluar_poli==NULL || empty($row_list->tgl_keluar_poli)){
                    $status_periksa = '<label class="label label-warning"><i class="fa fa-info-circle"></i> Belum diperiksa</label>';
                }else {
                    if($flag_rollback != 'submited'){
                        $status_periksa = '<label class="label label-success"><i class="fa fa-check-circle"></i> Selesai</label>';
                        
                    }else{
                        $status_periksa = '<label class="label label-primary"><i class="fa fa-money"></i> Lunas</label>';

                    }
                }
            }

            $row[] = '<div class="center">'.$status_periksa.'</div>';
           
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Pl_pelayanan->count_all(),
                        "recordsFiltered" => $this->Pl_pelayanan->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function get_data_tindakan()
    {
        /*get data from model*/
        $list = $this->Pl_pelayanan->get_datatables_tindakan();
        // print_r($this->db->last_query());die;
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();

            $row[] = '';
            if($row_list->kode_tc_trans_kasir==NULL){
                $btn_edit = '<a href="#" class="btn btn-xs btn-success" onclick="edit_transaksi('.$row_list->kode_trans_pelayanan.')"><i class="fa fa-edit"></i></a>';

                $row[] = '<div class="center"><a href="#" class="btn btn-xs btn-danger" onclick="delete_transaksi('.$row_list->kode_trans_pelayanan.')"><i class="fa fa-times-circle"></i></a></div>';
            }else{
                $row[] = '<div class="center"><i class="fa fa-check-circle green"></i></div>';
            }

            $row[] = $row_list->kode_trans_pelayanan;
            $row[] = $this->tanggal->tgl_indo($row_list->tgl_transaksi);
            /*tindakan luar?*/
            $text_tl = ($row_list->tindakan_luar==1) ? '(Tindakan Lain)' : '' ;
            $row[] = strtoupper($row_list->nama_tindakan).'&nbsp;'.$text_tl;

            $html_dr = '';
            if($row_list->bill_dr1 > 0){
                $html_dr .= '1. '.$row_list->nama_pegawai.'<br><span class="pull-right">'.number_format($row_list->bill_dr1 * $row_list->jumlah ).',-</span><br>';
            }

            if($row_list->bill_dr2 > 0){
                $html_dr .= '2. '.$row_list->dokter_2.'<br><span class="pull-right">'.number_format($row_list->bill_dr2* $row_list->jumlah).',-</span><br>';
            }

            if($row_list->bill_dr3 > 0){
                $html_dr .= '3. '.$row_list->dokter_3.'<br><span class="pull-right">'.number_format($row_list->bill_dr3 * $row_list->jumlah).',-</span><br>';
            }
            $row[] = '<div align="center">'.(int)$row_list->jumlah.' '.$row_list->satuan_tindakan.'</div>';
            $row[] = '<div align="left">'.$row_list->nama_pegawai.'</div>';
            /*$row[] = '<div align="right">'.number_format($row_list->bhp).',-</div>';
            $row[] = '<div align="right">'.number_format($row_list->alat_rs).',-</div>';
            $bill_rs = (isset($row_list->pendapatan_rs))?$row_list->pendapatan_rs:$row_list->bill_rs;
            $row[] = '<div align="right">'.number_format($bill_rs).',-</div>';*/

            $bill_total = ($row_list->bill_rs) + ($row_list->bill_dr1) + ($row_list->bill_dr2) + ($row_list->bill_dr3);
            
            $row[] = '<div align="right">'.number_format($bill_total).',-</div>';
            $row[] = $row_list->nama_pegawai;
            $row[] = $row_list->dokter_2;
            $row[] = $row_list->dokter_3;
           
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Pl_pelayanan->count_all_tindakan(),
                        "recordsFiltered" => $this->Pl_pelayanan->count_filtered_tindakan(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function get_data_tindakan_mcu()
    {
        /*get data from model*/
        $list = $this->Pl_pelayanan->get_datatables_tindakan_mcu();
        // echo "<pre>";print_r($this->db->last_query());echo "<pre>";print_r($list);die;
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();

            $row[] = '';
            if((isset($row_list->kode_tc_trans_kasir))AND($row_list->kode_tc_trans_kasir==NULL)){

                $row[] = '
                        <a href="#" class="btn btn-xs btn-danger" onclick="delete_transaksi('.$row_list->kode_trans_pelayanan.')"><i class="fa fa-times-circle"></i></a>
                        <a href="#" class="btn btn-xs btn-success" onclick="edit_transaksi('.$row_list->kode_trans_pelayanan.')"><i class="fa fa-edit"></i></a>
                        ';
            }else{
                $row[] = '<div class="center"><i class="fa fa-check-circle green"></i></div>';
            }

            $row[] = $row_list->kode_trans_pelayanan;
            $row[] = $this->tanggal->tgl_indo($row_list->tgl_transaksi);
            /*tindakan luar?*/
            $text_tl = (isset($row_list->tindakan_luar) AND ($row_list->tindakan_luar==1)) ? '(Tindakan Lain)' : '' ;
            $row[] = strtoupper($row_list->nama_tindakan).'&nbsp;'.$text_tl;

            $html_dr = '';
            if($row_list->bill_dr1 > 0){
                $html_dr .= '1. '.$row_list->nama_pegawai.'<br><span class="pull-right">'.number_format($row_list->bill_dr1 * $row_list->jumlah ).',-</span><br>';
            }

            if((isset($row_list->bill_dr2)) AND ($row_list->bill_dr2 > 0)){
                $html_dr .= '2. '.$row_list->dokter_2.'<br><span class="pull-right">'.number_format($row_list->bill_dr2* $row_list->jumlah).',-</span><br>';
            }

            if((isset($row_list->bill_dr2)) AND ($row_list->bill_dr3 > 0)){
                $html_dr .= '3. '.$row_list->dokter_3.'<br><span class="pull-right">'.number_format($row_list->bill_dr3 * $row_list->jumlah).',-</span><br>';
            }
            $satuan_tindakan = isset($row_list->satuan_tindakan)?$row_list->satuan_tindakan:'';
            $row[] = '<div align="center">'.(int)$row_list->jumlah.' '.$satuan_tindakan.'</div>';
            $row[] = '<div align="left">'.$row_list->nama_pegawai.'</div>';
            /*$row[] = '<div align="right">'.number_format($row_list->bhp).',-</div>';
            $row[] = '<div align="right">'.number_format($row_list->alat_rs).',-</div>';
            $bill_rs = (isset($row_list->pendapatan_rs))?$row_list->pendapatan_rs:$row_list->bill_rs;
            $row[] = '<div align="right">'.number_format($bill_rs).',-</div>';*/

            $bill_dr2 = isset($row_list->bill_dr2)?$row_list->bill_dr2:0;
            $bill_dr3 = isset($row_list->bill_dr3)?$row_list->bill_dr3:0;

            $bill_total = ($row_list->bill_rs) + ($row_list->bill_dr1) + $bill_dr2 + $bill_dr3;
            
            $row[] = '<div align="right">'.number_format($bill_total).',-</div>';
            $row[] = $row_list->nama_pegawai;
            $row[] = isset($row_list->dokter_2)?$row_list->dokter_2:'';
            $row[] = isset($row_list->dokter_3)?$row_list->dokter_3:'';
           
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Pl_pelayanan->count_all_tindakan_mcu(),
                        "recordsFiltered" => $this->Pl_pelayanan->count_filtered_tindakan_mcu(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function get_transaksi_by_id()
    {
        # code...
        $id=$this->input->post('ID')?$this->input->post('ID',TRUE):$_GET['kode'];
        $data = $this->Pl_pelayanan->get_tindakan_by_id($id);

        //echo '<pre>'; print_r($this->db->last_query());die;

        $kode_klas = ($data->jenis_tindakan==13)?'':$data->kode_klas;

        if($data->kode_tarif!=NULL){
            $komponen = $this->Pl_pelayanan->getComponentTarif($data->kode_tarif,$kode_klas);
        }else{
            $komponen = $this->Pl_pelayanan->getComponentTarifLain($id);            
        }
        $text_cito = isset($_GET['urgensi'])? ($_GET['urgensi']==1) ? '( Cito + 25% )' : '' : '' ;

        $html_form = array();
        $html_tag = '';
        $html_tag .= '<div class="row">';
        $html_tag .= '<form id="form_update_billing_'.$id.'" action="POST" enctype="multipart/form-data">';
        $html_tag .= '<div class="col-md-8">';
        $html_tag .= '<p><b><i class="fa fa-circle-o"></i> RINCIAN TRANSAKSI '.$text_cito.'</b></p>';
        $html_tag .= '<table class="table table-hover">';
        $html_tag .= '<tr style="background-color:#e4e6e6">';
        $html_tag .= '<th class="center" width="30px">No</th>';
        $html_tag .= '<th>Field Name</th>';
        $html_tag .= '<th style="text-align:center;width:120px">Biaya</th>';
        $html_tag .= '<th style="text-align:center;width:120px">Kenaikan<br>Tarif (%)</th>';
        $html_tag .= '<th style="text-align:right;width:120px">Perubahan</th>';
        $html_tag .= '</tr>';

        $no=1; 
        foreach ($komponen as $key => $value) {

            /*result html form*/
            $vals = number_format($value);
            if($vals != '0'){
                $html_form[] = '<div class="form-group">
                            <label class="control-label col-sm-2" for="">'.ucwords($key).'</label>
                            <div class="col-md-3"><input type="text" class="form-control" name="'.$key.'" id="'.$key.'" value="'.number_format($data->$key).'" ></div>
                        </div>';
            }

            /*result html tags*/
            if($vals != '0'){

                $dr = '';
                if ($key=='bill_dr1') {
                    $dr = ' ('.$data->nama_pegawai.')';
                }
                if ($key=='bill_dr2') {
                    $dr = ' ('.$data->dokter_2.')';
                }
                if ($key=='bill_dr3') {
                    $dr = ' ('.$data->dokter_3.')';
                }

                // tag readonly
                if( in_array($key, array('pendapatan_rs', 'bhp', 'kamar_tindakan')) ){
                    $readonly = 'readonly';
                    $text = 'hidden';
                }else{
                    $readonly = '' ;
                    $text = 'text' ;
                }
                $html_tag .= '<tr>';
                $html_tag .= '<td align="center">'.$no.'</td>';
                $html_tag .= '<td>'.str_replace('_',' ', strtoupper($key)) .' '.$dr.'</td>';
                //$html_tag .= '<td align="right">Rp. '.number_format($data->$key).',-</td>';
                $html_tag .= '<td align="right"><input type="text" value="'.(int)$data->$key.'" name="'.$key.'_'.$id.'" id="'.$key.'_'.$id.'" style="text-align:right;width:100px !important" '.$readonly.'></td>';
                $html_tag .= '<td align="right"><input type="'.$text.'" onchange="changeTotalBiaya('."'".$key."'".','.$id.')" class="format_number" style="text-align:center;margin-bottom:5px;width:70px" value="0" id="diskon_'.$key.'_'.$id.'" '.$readonly.'></td>';
                $html_tag .= '<td align="right" id="text_total_diskon_'.$key.'_'.$id.'">Rp. '.number_format($data->$key).',-</td>';
                $html_tag .= '</tr>';
                $arr_sum[] = $data->$key;

                /*hidden form*/
                $html_tag .= '<input type="hidden" value="'.$key.'" name="fields_'.$id.'[]" id="'.$key.'_'.$id.'" >';
                $html_tag .= '<input type="hidden" class="total_diskon_'.$id.'" value="'.(int)$data->$key.'" name="total_diskon_'.$key.'_'.$id.'" id="total_diskon_'.$key.'_'.$id.'" >';
                $no++;
            }

        }

        /*sum array*/
        $total = array_sum($arr_sum);
        $html_tag .= '<tr>';
        $html_tag .= '<td align="right" colspan="4"><b>TOTAL</b></td>';
        $html_tag .= '<td align="right" id="total_biaya_'.$id.'"><b>Rp. '.number_format($total).',-</b></td>';
        $html_tag .= '</tr>';

        $html_tag .= '</table>';
        $html_tag .= '</div>';
        $html_tag .= '<div class="col-md-4">';
        $html_tag .= '<br><br>';
        $html_tag .= '<p>
                        Keterangan :<br>
                        <ol>
                            <li>Operasi dengan resiko besar, Jasa Dokter Spesialis Bedah dinaikkan sampai dengan 20% - 25% 
                            </li>
                            <li>Operasi cito, besar biaya ditambah 25% dari tarif operasi yang direncanakan
                            </li>
                        </ol>
                        <div class="center">
                            
                        </div>
                      </p>';
        $html_tag .= '<center>';
        $html_tag .= '<a href="#" class="btn btn-xs btn-primary" onclick="submitUpdateTransaksi('.$id.')"><i class="fa fa-angle-double-left"></i> UBAH BIAYA TRANSAKSI <i class="fa fa-angle-double-right"></i> </a>';
        $html_tag .= '</center>';
        $html_tag .= '</form>';
        $html_tag .= '</div>';

        $html_result = isset($_GET['type'])?$html_tag:$html_form;

        echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'tgl' => $this->tanggal->formatDateTimeForm($data->tgl_transaksi), 'html' => $html_result));

    } 

    public function get_data_obat()
    {
        /*get data from model*/
        $list = $this->Pl_pelayanan->get_datatables_tindakan();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center">
                        <label class="pos-rel">
                            <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->kode_trans_pelayanan.'"/>
                            <span class="lbl"></span>
                        </label>
                    </div>';
            $row[] = '<div class="center"><a href="#" class="btn btn-xs btn-danger" onclick="delete_transaksi('.$row_list->kode_trans_pelayanan.')"><i class="fa fa-times-circle"></i></a></div>';
            $row[] = $row_list->kode_trans_pelayanan;
            $row[] = strtoupper($row_list->nama_tindakan);
            $row[] = '<div class="center">'.(int)$row_list->jumlah.' ('.$row_list->satuan_kecil.') </div>';
            $row[] = '<div align="right">'.number_format($row_list->harga_satuan).',-</div>';
            $bill_total = $row_list->bill_rs + $row_list->bill_dr1 + $row_list->bill_dr2;
            $row[] = '<div align="right">'.number_format($bill_total).',-</div>';
           
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Pl_pelayanan->count_all_tindakan(),
                        "recordsFiltered" => $this->Pl_pelayanan->count_filtered_tindakan(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function find_data()
    {   
        $output = array( "data" => http_build_query($_POST) . "\n" );
        echo json_encode($output);
    }

    public function process_add_tindakan(){

        // print_r($_POST);die;
        // form validation
        if( (isset($_GET['type']) AND ($_GET['type']=='konsultasi' OR $_GET['type']=='sarana_fisio')) OR ((isset($_POST['tindakan_lainnya'])))){
            $this->form_validation->set_rules('pl_kode_tindakan_hidden', 'Tindakan', 'trim');
        }else{
            $this->form_validation->set_rules('pl_kode_tindakan_hidden', 'Tindakan', 'trim|required');
        }
        $this->form_validation->set_rules('noMrHidden', 'No MR', 'trim|required');
        $this->form_validation->set_rules('pl_tgl_transaksi', 'Tanggal', 'trim');
        

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

            $kode_trans_pelayanan = $this->master->get_max_number('tc_trans_pelayanan', 'kode_trans_pelayanan');
            
            $dataexc = array(
                /*form hidden input default*/
                'no_kunjungan' => $this->regex->_genRegex($this->input->post('no_kunjungan'),'RGXINT'),
                'no_registrasi' => $this->regex->_genRegex($this->input->post('no_registrasi'),'RGXINT'),
                'kode_kelompok' => $this->regex->_genRegex($this->input->post('kode_kelompok'),'RGXINT'),
                'kode_perusahaan' => $this->regex->_genRegex($this->input->post('kode_perusahaan'),'RGXINT'),
                'no_mr' => $this->regex->_genRegex($this->input->post('noMrHidden'),'RGXQSL'),
                'nama_pasien_layan' => $this->regex->_genRegex($this->input->post('nama_pasien_hidden'),'RGXQSL'),
                'kode_bagian_asal' => $this->regex->_genRegex($this->input->post('kode_bagian_asal'),'RGXQSL'),
                /*end form hidden input default*/
                'kode_bagian' => $this->regex->_genRegex($this->input->post('kode_bagian'),'RGXQSL'),
                'kode_klas' => $this->regex->_genRegex($this->input->post('kode_klas'),'RGXINT'),
                'kode_bagian_asal' => $this->regex->_genRegex($this->input->post('kode_bagian_asal'),'RGXQSL'),
                'tgl_transaksi' => $this->tanggal->sqlDateForm($this->regex->_genRegex($this->input->post('pl_tgl_transaksi'),'RGXQSL')),                
                'jumlah' => $this->input->post('pl_jumlah'),  
                'satuan_tindakan' => $this->input->post('satuan_tindakan'),  
            );

            /*jika pasien hanya untuk konsultasi saja*/
            if( isset($_GET['type']) AND ($_GET['type']=='konsultasi' OR $_GET['type']=='sarana_fisio')){

                /*tarif sarana rs*/
                $tarif_sarana = $this->tarif->insert_tarif_by_jenis_tindakan($dataexc, 13);

                if($_GET['type']=='konsultasi'){
                    $dataexc['kode_dokter1'] = $_POST['pl_kode_dokter_hidden'][0];
                    /*tarif konsultasi*/
                    $tarif_konsultasi = $this->tarif->insert_tarif_by_jenis_tindakan($dataexc, 12);
                }
                
            /*jika tidak atau ada tambahan tindakan lainnya*/
            }else if(isset($_POST['tindakan_lainnya']) AND $_POST['tindakan_lainnya']=='tindakan_luar'){

                $dataexc['kode_trans_pelayanan'] = $kode_trans_pelayanan;
                /*form hidden after select tindakan*/
                $dataexc['jenis_tindakan'] = 10;
                $dataexc['nama_tindakan'] = ucwords($this->regex->_genRegex($this->input->post('nama_tindakan'),'RGXQSL'));
                $dataexc["bill_rs"] = (isset($_POST['bill_rs']))?(int)$_POST['bill_rs']:0;
                $dataexc["bill_dr1"] = (isset($_POST['bill_dr1']))?(int)$_POST['bill_dr1']:NULL;
                $dataexc["bill_dr2"] = (isset($_POST['bill_dr2']))?(int)$_POST['bill_dr2']:NULL;
                $dataexc['kode_dokter1'] = $_POST['pl_kode_dokter_hidden'][0];
                $dataexc['kode_dokter2'] = isset($_POST['pl_kode_dokter_hidden'][1])?$_POST['pl_kode_dokter_hidden'][1]:0;
                $dataexc["tindakan_luar"] = 1;

                $this->Pl_pelayanan->save('tc_trans_pelayanan', $dataexc);

            }else if(isset($_POST['tindakan_lainnya']) AND $_POST['tindakan_lainnya']=='lain_lain'){

                $dataexc['kode_trans_pelayanan'] = $kode_trans_pelayanan;
                /*form hidden after select tindakan*/
                $dataexc['jenis_tindakan'] = 10;
                $dataexc['nama_tindakan'] = ucwords($this->regex->_genRegex($this->input->post('nama_tindakan'),'RGXQSL'));
                $dataexc["bill_rs"] = (isset($_POST['bill_rs']))?(int)$_POST['bill_rs']:0;
                $dataexc["bill_dr1"] = (isset($_POST['bill_dr1']))?(int)$_POST['bill_dr1']:NULL;
                $dataexc["bill_dr2"] = (isset($_POST['bill_dr2']))?(int)$_POST['bill_dr2']:NULL;
                $dataexc["kamar_tindakan"] = (isset($_POST['kamar_tindakan']))?(int)$_POST['kamar_tindakan']:NULL;
                $dataexc['kode_dokter1'] = $_POST['pl_kode_dokter_hidden'][0];
                $dataexc['kode_dokter2'] = isset($_POST['pl_kode_dokter_hidden'][1])?$_POST['pl_kode_dokter_hidden'][1]:0;
                
                $this->Pl_pelayanan->save('tc_trans_pelayanan', $dataexc);

            }else{
                $dataexc['kode_trans_pelayanan'] = $kode_trans_pelayanan;
                /*form hidden after select tindakan*/
                $dataexc['kode_tarif'] = $this->regex->_genRegex($this->input->post('kode_tarif'),'RGXINT');
                $dataexc['jenis_tindakan'] = ($this->regex->_genRegex($this->input->post('jenis_tindakan'),'RGXINT')!=0)?$this->regex->_genRegex($this->input->post('jenis_tindakan'),'RGXINT'):3;
                $dataexc['nama_tindakan'] = $this->regex->_genRegex($this->input->post('nama_tindakan'),'RGXQSL');
                $dataexc['kode_master_tarif_detail'] = $this->regex->_genRegex($this->input->post('kode_master_tarif_detail'),'RGXQSL');

                /*status NK*/
                $status_nk = ( $this->input->post('jenis_tarif')==120 ) ? 1 : 0 ;
                $dataexc['status_nk'] = $status_nk;

                /*Penunjang Medis */
                if( isset($_POST['kode_penunjang']) AND $_POST['kode_penunjang']!=0 ){
                    $dataexc['kode_penunjang'] = $this->input->post('kode_penunjang');
                }

                /*get tarif mulitiple kode dokter*/
                $tarifDokter = $this->tarif->getTarifMultipleDokter( $this->input->post('pl_kode_dokter_hidden') );

                $tarifInsert = $this->tarif->getTarifForinsert();

                $mergeData = array_merge($dataexc, $tarifDokter, $tarifInsert);

                //print_r($mergeData);die;

                /*save tc_trans_pelayanan*/
                $this->Pl_pelayanan->save('tc_trans_pelayanan', $mergeData);

            }

            /*save logs*/
            $this->logs->save('tc_trans_pelayanan', $kode_trans_pelayanan, 'insert new record on '.$this->title.' module', json_encode($dataexc),'kode_trans_pelayanan');

            
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

    public function process_edit_tindakan()
    {
        # code...
        //print_r($_POST);die;
        if(isset($_POST['bill_dr1']) OR isset($_POST['bill_dr2']) OR isset($_POST['bhp']) OR isset($_POST['pendapatan_rs'])){
            $bill_dr1 = (isset($_POST['bill_dr1']))?(int)str_replace(",", "", $this->input->post('bill_dr1')):0;
            $bill_dr2 = (isset($_POST['bill_dr2']))?(int)str_replace(",", "", $this->input->post('bill_dr2')):0;
            $bhp = (isset($_POST['bhp']))?(int)str_replace(",", "", $this->input->post('bhp')):0;
            $pendapatan_rs = (isset($_POST['pendapatan_rs']))?(int)str_replace(",", "", $this->input->post('pendapatan_rs')):0;

            $data = $this->db->get_where('tc_trans_pelayanan',array('kode_trans_pelayanan' => $this->input->post('kode_trans_pelayanan')))->row();

            $bill_rs = $pendapatan_rs + $bhp + $data->kamar_tindakan + $data->alat_rs;

            $dataexc = array(
                'bill_rs' => $bill_rs,
                'bill_dr1' => $bill_dr1,
                'bill_dr2' => $bill_dr2,
                'bhp' => $bhp,
                'pendapatan_rs' => $pendapatan_rs,
            );
        }

        $dataexc['tgl_transaksi'] = $this->tanggal->sqlDateForm($this->input->post('pl_tgl_transaksi_edit'));
        //print_r($dataexc);die;
        $this->Pl_pelayanan->update('tc_trans_pelayanan', $dataexc, array('kode_trans_pelayanan' => $this->input->post('kode_trans_pelayanan')));
        echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan'));
    }

    public function process_add_obat(){

        // print_r($_POST);die;
        // form validation
        $this->form_validation->set_rules('pl_kode_brg_hidden', 'Obat', 'trim|required');        

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

            $kode_trans_pelayanan = $this->master->get_max_number('tc_trans_pelayanan', 'kode_trans_pelayanan');

            /*get bill_rs*/
            $bill_rs = $this->input->post('pl_harga_satuan') * $this->input->post('pl_jumlah_obat');

            $dataexc = array(
                'kode_trans_pelayanan' => $kode_trans_pelayanan,
                /*form hidden input default*/
                'no_kunjungan' => $this->regex->_genRegex($this->input->post('no_kunjungan'),'RGXINT'),
                'no_registrasi' => $this->regex->_genRegex($this->input->post('no_registrasi'),'RGXINT'),
                'kode_kelompok' => $this->regex->_genRegex($this->input->post('kode_kelompok'),'RGXINT'),
                'kode_perusahaan' => $this->regex->_genRegex($this->input->post('kode_perusahaan'),'RGXINT'),
                'no_mr' => $this->regex->_genRegex($this->input->post('no_mr'),'RGXQSL'),
                'nama_pasien_layan' => $this->regex->_genRegex($this->input->post('nama_pasien_layan'),'RGXQSL'),
                'kode_bagian_asal' => $this->regex->_genRegex($this->input->post('kode_bagian_asal'),'RGXQSL'),
                /*end form hidden input default*/

                /*form hidden after select tindakan*/
                'kode_barang' => $this->regex->_genRegex($this->input->post('pl_kode_brg_hidden'),'RGXQSL'),
                'jenis_tindakan' => 9,
                'nama_tindakan' => $this->regex->_genRegex($this->input->post('nama_tindakan'),'RGXQSL'),
                'kode_bagian' => $this->regex->_genRegex($this->input->post('kode_bagian'),'RGXQSL'),
                'kode_klas' => $this->regex->_genRegex(16,'RGXINT'),
                'bill_rs' => $bill_rs,
                'kode_profit' => $this->regex->_genRegex(2000,'RGXINT'),
                /*end form hidden after select obat*/
                'kode_bagian_asal' => $this->regex->_genRegex($this->input->post('kode_bagian_asal'),'RGXQSL'),
                'tgl_transaksi' => date('Y-m-d H:i:s'),                
                'jumlah' => $this->input->post('pl_jumlah_obat'),
                'harga_satuan' => $this->regex->_genRegex($this->input->post('pl_harga_satuan'),'RGXINT'),
                
            );
            
            //print_r($dataexc);die;

            /*save tc_trans_pelayanan*/
            $this->Pl_pelayanan->save('tc_trans_pelayanan', $dataexc);

            $bagian = ($this->input->post('kode_bagian_depo'))?$this->input->post('kode_bagian_depo'):$dataexc['kode_bagian'];

            $this->stok_barang->stock_process($dataexc['kode_barang'], $dataexc['jumlah'], $bagian,6, '', 'reduce');


            /*save logs*/
            $this->logs->save('tc_trans_pelayanan', $kode_trans_pelayanan, 'insert new record on '.$this->title.' module', json_encode($dataexc),'kode_trans_pelayanan');

            //print_r($dataexc);die;

            
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

    public function delete()
    {
        $id=$this->input->post('ID')?$this->input->post('ID',TRUE):null;
        if($id!=null){
            if($this->Pl_pelayanan->delete_trans_pelayanan($id)){
                $this->logs->save('tc_trans_pelayanan', $id, 'delete record', '', 'kode_trans_pelayanan');
                echo json_encode(array('status' => 200, 'message' => 'Proses Hapus Data Berhasil Dilakukan'));
            }else{
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Hapus Data Gagal Dilakukan'));
            }
        }else{
            echo json_encode(array('status' => 301, 'message' => 'Tidak ada item yang dipilih'));
        }
        
    }

    public function delete_diagnosa()
    {
        $id=$this->input->post('ID')?$this->input->post('ID',TRUE):null;
        if($id!=null){
            if($this->Pl_pelayanan->delete_diagnosa($id)){
                $this->logs->save('tc_trans_pelayanan', $id, 'delete record', '', 'kode_trans_pelayanan');
                echo json_encode(array('status' => 200, 'message' => 'Proses Hapus Data Berhasil Dilakukan'));
            }else{
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Hapus Data Gagal Dilakukan'));
            }
        }else{
            echo json_encode(array('status' => 301, 'message' => 'Tidak ada item yang dipilih'));
        }
        
    }

    public function processPelayananSelesai(){

        // print_r($_POST);die;
        // form validation
        $this->form_validation->set_rules('noMrHidden', 'Pasien', 'trim|required', array('required' => 'No MR Pasien Tidak ditemukan!') );        
        $this->form_validation->set_rules('pl_anamnesa', 'Anamnesa', 'trim');        
        $this->form_validation->set_rules('pl_diagnosa', 'Diagnosa', 'trim|required');        
        $this->form_validation->set_rules('pl_pemeriksaan', 'Pemeriksaan', 'trim');        
        $this->form_validation->set_rules('pl_pengobatan', 'Pengobatan', 'trim');        
        $this->form_validation->set_rules('no_registrasi', 'No Registrasi', 'trim|required');        
        $this->form_validation->set_rules('no_kunjungan', 'No Kunjungan', 'trim|required');        
        $this->form_validation->set_rules('kode_bagian_asal', 'Kode Bagian Asal', 'trim');        
        $this->form_validation->set_rules('cara_keluar', 'Cara Keluar Pasien', 'trim|required');        
        $this->form_validation->set_rules('pasca_pulang', 'Pasca Pulang', 'trim|required');        
        // form assesment
        $this->form_validation->set_rules('pl_tb', 'Tinggi Badan', 'trim');        
        $this->form_validation->set_rules('pl_bb', 'Berat Badan', 'trim');        
        $this->form_validation->set_rules('pl_td', 'Tekanan Darah', 'trim');        
        $this->form_validation->set_rules('pl_suhu', 'Suhu', 'trim');        
        $this->form_validation->set_rules('pl_nadi', 'Nadi', 'trim');        

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

            $no_kunjungan = $this->form_validation->set_value('no_kunjungan');
            $no_registrasi = $this->form_validation->set_value('no_registrasi');

            /*cek transaksi minimal apakah sudah ada tindakan*/
            $cek_transaksi = $this->Pl_pelayanan->cek_transaksi_minimal($no_kunjungan);

            /*jika sudah ada minimal 1 transaksi atau tindakan, maka lanjutkan proses*/
            if($cek_transaksi OR $this->input->post('flag_mcu')==1){

                /*proses utama pasien selesai*/
                /*update pl_tc_poli*/
                $arrPlTcPoli = array('status_periksa' => 3, 'tgl_keluar_poli' => date('Y-m-d H:i:s'), 'no_induk' => $this->session->userdata('user')->user_id, 'created_by' => $this->session->userdata('user')->fullname );
                if($this->input->post('flag_mcu')==1){
                    $arrPlTcPoli['status_daftar'] = 2;
                    $arrPlTcPoli['status_isihasil'] = 1;
                }
                $this->Pl_pelayanan->update('pl_tc_poli', $arrPlTcPoli, array('no_kunjungan' => $no_kunjungan ) );
                /*save logs pl_tc_poli*/
                $this->logs->save('pl_tc_poli', $no_kunjungan, 'update pl_tc_poli Modul Pelayanan', json_encode($arrPlTcPoli),'no_kunjungan');
                               

                /*insert log diagnosa pasien th_riwayat pasien*/
                $riwayat_diagnosa = array(
                    'no_registrasi' => $this->form_validation->set_value('no_registrasi'),
                    'no_kunjungan' => $no_kunjungan,
                    'no_mr' => $this->form_validation->set_value('noMrHidden'),
                    'nama_pasien' => $this->input->post('nama_pasien_layan'),
                    'diagnosa_awal' => $this->form_validation->set_value('pl_diagnosa'),
                    'anamnesa' => $this->form_validation->set_value('pl_anamnesa'),
                    'pengobatan' => $this->form_validation->set_value('pl_pengobatan'),
                    'dokter_pemeriksa' => $this->input->post('dokter_pemeriksa'),
                    'pemeriksaan' => $this->form_validation->set_value('pl_pemeriksaan'),
                    'tgl_periksa' => date('Y-m-d H:i:s'),
                    'kode_bagian' => ($this->form_validation->set_value('kode_bagian_asal'))?$this->form_validation->set_value('kode_bagian_asal'):$_GET['bag'],
                    'diagnosa_akhir' => $this->form_validation->set_value('pl_diagnosa'),
                    'kategori_tindakan' => 3,
                    'kode_icd_diagnosa' => $this->input->post('pl_diagnosa_hidden'),
                    'tinggi_badan' => (float)$this->input->post('pl_tb'),
                    'tekanan_darah' => (float)$this->input->post('pl_td'),
                    'berat_badan' => (float)$this->input->post('pl_bb'),
                    'suhu' => (float)$this->input->post('pl_suhu'),
                    'nadi' => (float)$this->input->post('pl_nadi'),
                );

                if($this->input->post('kode_riwayat')==0){
                    $this->Pl_pelayanan->save('th_riwayat_pasien', $riwayat_diagnosa);
                }else{
                    $this->Pl_pelayanan->update('th_riwayat_pasien', $riwayat_diagnosa, array('kode_riwayat' => $this->input->post('kode_riwayat') ) );
                }

                /*kondisi jika pasien dirujuk RI/RJ*/
                $status = $this->input->post('cara_keluar');
                $txt_rujuk_poli = 'Rujuk ke Poli Lain';
                $txt_rujuk_ri = 'Rujuk ke Rawat Inap';
                if( in_array($status, array($txt_rujuk_ri,$txt_rujuk_poli) ) ){
                    $max_kode_rujukan = $this->master->get_max_number('rg_tc_rujukan', 'kode_rujukan');
                    $tujuan = ($status==$txt_rujuk_poli)?$this->input->post('rujukan_tujuan'):'030001';
                    $rujukan_data = array(
                        'kode_rujukan' => $max_kode_rujukan,
                        'rujukan_dari' => $this->form_validation->set_value('kode_bagian_asal'),
                        'no_mr' => $this->form_validation->set_value('noMrHidden'),
                        'no_kunjungan_lama' => $no_kunjungan,
                        'no_registrasi' => $this->form_validation->set_value('no_registrasi'),
                        'rujukan_tujuan' => $tujuan,
                        'status' => 0,
                        'tgl_input' => date('Y-m-d H:i:s'),
                    );
                    /*insert rg_tc_rujukan*/
                    $this->Pl_pelayanan->save('rg_tc_rujukan', $rujukan_data );
                   
                }

                /*last func to finsih visit*/
                $this->daftar_pasien->pulangkan_pasien($no_kunjungan,3);

            }else{
                echo json_encode(array('status' => 301, 'message' => 'Tidak ada data transaksi, Silahkan klik Batal Berobat jika tidak ada tindakan atau minimal konsultasi dokter'));
                exit;
            }

            
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                // search pasien berikutnya
                $antrian_pasien = $this->Pl_pelayanan->get_next_antrian_pasien();
                $next_pasien = isset($antrian_pasien)?$antrian_pasien: ''; 
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'type_pelayanan' => 'Pasien Selesai', 'next_pasien' => isset($next_pasien->no_mr)?$next_pasien->no_mr:'', 'next_id_pl_tc_poli' => isset($next_pasien->id_pl_tc_poli)?$next_pasien->id_pl_tc_poli:'', 'next_no_kunjungan' => isset($next_pasien->no_kunjungan)?$next_pasien->no_kunjungan:''));
            }

        
        }

    }

    public function processSelesaikanKunjungan(){

        // print_r($_POST);die;
        // form validation
        $this->form_validation->set_rules('no_kunjungan', 'No Kunjungan', 'trim|required', array('required' => 'No Kunjungan Tidak ditemukan!') );               
        $this->form_validation->set_rules('no_registrasi', 'No Registrasi', 'trim|required', array('required' => 'No Registrasi Tidak ditemukan!') );               

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

            $no_kunjungan = $this->form_validation->set_value('no_kunjungan');
            $no_registrasi = $this->form_validation->set_value('no_registrasi');

            /*update pl_tc_poli*/
            $arrPlTcPoli = array('status_periksa' => 3, 'tgl_keluar_poli' => date('Y-m-d H:i:s'), 'no_induk' => $this->session->userdata('user')->user_id, 'created_by' => $this->session->userdata('user')->fullname );
            /*cek transaksi minimal apakah sudah ada tindakan*/
            $cek_transaksi = $this->Pl_pelayanan->cek_transaksi_minimal($no_kunjungan);

            /*jika sudah ada minimal 1 transaksi atau tindakan, maka lanjutkan proses*/
            if($cek_transaksi){

                /*proses utama pasien selesai*/
                $this->Pl_pelayanan->update('pl_tc_poli', $arrPlTcPoli, array('no_kunjungan' => $no_kunjungan ) );
                /*save logs pl_tc_poli*/
                $this->logs->save('pl_tc_poli', $no_kunjungan, 'update pl_tc_poli Modul Pelayanan', json_encode($arrPlTcPoli),'no_kunjungan');
                       
                /*last func to finsih visit*/
                /*update tc_kunjungan*/
                $arrKunjungan = array(
                    'status_keluar' => 3, 
                    'tgl_keluar' => date('Y-m-d H:i:s'), 
                    'cara_keluar_pasien' => isset($_POST['cara_keluar'])?$_POST['cara_keluar']:'', 
                    'pasca_pulang' => isset($_POST['pasca_pulang'])?$_POST['pasca_pulang']:''
                );

                $this->db->update('tc_kunjungan', $arrKunjungan, array('no_kunjungan' => $no_kunjungan) );

            }else{
                echo json_encode(array('status' => 301, 'message' => 'Tidak ada data transaksi, Silahkan klik Batal Berobat jika tidak ada tindakan atau minimal konsultasi dokter'));
                exit;
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

    public function processSaveDiagnosa(){

        // print_r($_POST);die;
        // form validation
        $this->form_validation->set_rules('noMrHidden', 'Pasien', 'trim|required', array('required' => 'No MR Pasien Tidak ditemukan!') );

        $required = isset($_POST['flag_form_pelayanan'])?($_POST['flag_form_pelayanan'] == 'dokter') ? 'required' : 'xss_clean' : '';
        $this->form_validation->set_rules('pl_anamnesa', 'Anamnesa', 'trim|required');        
        $this->form_validation->set_rules('pl_diagnosa', 'Diagnosa', 'trim|required');        
        $this->form_validation->set_rules('pl_pemeriksaan', 'Pemeriksaan', 'trim');        
        $this->form_validation->set_rules('pl_pengobatan', 'Pengobatan', 'trim');        
        $this->form_validation->set_rules('no_registrasi', 'No Registrasi', 'trim|required');        
        $this->form_validation->set_rules('no_kunjungan', 'No Kunjungan', 'trim|required');        
        $this->form_validation->set_rules('kode_bagian_asal', 'Kode Bagian Asal', 'trim|required');             
        // form assesment
        $this->form_validation->set_rules('pl_tb', 'Tinggi Badan', 'trim');        
        $this->form_validation->set_rules('pl_bb', 'Berat Badan', 'trim');        
        $this->form_validation->set_rules('pl_td', 'Tekanan Darah', 'trim');        
        $this->form_validation->set_rules('pl_suhu', 'Suhu', 'trim');        
        $this->form_validation->set_rules('pl_nadi', 'Nadi', 'trim');        

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

            $no_kunjungan = $this->form_validation->set_value('no_kunjungan');
            $no_registrasi = $this->form_validation->set_value('no_registrasi');

            /*insert log diagnosa pasien th_riwayat pasien*/
            $riwayat_diagnosa = array(
                'no_registrasi' => $this->form_validation->set_value('no_registrasi'),
                'no_kunjungan' => $no_kunjungan,
                'no_mr' => $this->form_validation->set_value('noMrHidden'),
                'nama_pasien' => $this->input->post('nama_pasien_layan'),
                'diagnosa_awal' => $this->form_validation->set_value('pl_diagnosa'),
                'anamnesa' => $this->form_validation->set_value('pl_anamnesa'),
                'pengobatan' => $this->form_validation->set_value('pl_pengobatan'),
                'dokter_pemeriksa' => $this->input->post('dokter_pemeriksa'),
                'pemeriksaan' => $this->form_validation->set_value('pl_pemeriksaan'),
                'tgl_periksa' => date('Y-m-d H:i:s'),
                'kode_bagian' => $this->form_validation->set_value('kode_bagian_asal'),
                'diagnosa_akhir' => $this->form_validation->set_value('pl_diagnosa'),
                'kategori_tindakan' => 3,
                'kode_icd_diagnosa' => $this->input->post('pl_diagnosa_hidden'),
                'tinggi_badan' => (float)$this->input->post('pl_tb'),
                'tekanan_darah' => (float)$this->input->post('pl_td'),
                'berat_badan' => (float)$this->input->post('pl_bb'),
                'suhu' => (float)$this->input->post('pl_suhu'),
                'nadi' => (float)$this->input->post('pl_nadi'),
            );

            if($this->input->post('kode_riwayat')==0){
                $this->Pl_pelayanan->save('th_riwayat_pasien', $riwayat_diagnosa);
            }else{
                $this->Pl_pelayanan->update('th_riwayat_pasien', $riwayat_diagnosa, array('kode_riwayat' => $this->input->post('kode_riwayat') ) );
            }



            
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                // jika session dokter
                if(isset($_POST['flag_form_pelayanan']) AND $_POST['flag_form_pelayanan'] == 'dokter'){
                    /*update pl_tc_poli*/
                    $arrPlTcPoli = array('status_periksa' => 3, 'tgl_keluar_poli' => date('Y-m-d H:i:s'), 'no_induk' => $this->session->userdata('user')->user_id, 'created_by' => $this->session->userdata('user')->fullname );
                    $this->db->where('id_pl_tc_poli', $_POST['id_pl_tc_poli'])->update('pl_tc_poli', $arrPlTcPoli );
                    // search pasien berikutnya
                    $antrian_pasien = $this->Pl_pelayanan->get_next_antrian_pasien_sess_dr();
                    $next_pasien = isset($antrian_pasien)?$antrian_pasien: ''; 
                    echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'type_pelayanan' => 'Pasien Selesai', 'next_pasien' => isset($next_pasien->no_mr)?$next_pasien->no_mr:'', 'next_id_pl_tc_poli' => isset($next_pasien->id_pl_tc_poli)?$next_pasien->id_pl_tc_poli:'', 'next_no_kunjungan' => isset($next_pasien->no_kunjungan)?$next_pasien->no_kunjungan:''));
                }else{
                    echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan')); 
                }
            }

        
        }

    }

    public function processSaveDiagnosaDr(){

        
        // form validation
        $this->form_validation->set_rules('noMrHidden', 'Pasien', 'trim|required', array('required' => 'No MR Pasien Tidak ditemukan!') );

        $required = isset($_POST['flag_form_pelayanan'])?($_POST['flag_form_pelayanan'] == 'dokter') ? 'required' : 'xss_clean' : '';
        $this->form_validation->set_rules('pl_anamnesa', 'Anamnesa', 'trim|required|min_length[8]');        
        $this->form_validation->set_rules('pl_diagnosa', 'Diagnosa', 'trim|required');        
        $this->form_validation->set_rules('pl_pemeriksaan', 'Pemeriksaan', 'trim');        
        $this->form_validation->set_rules('pl_pengobatan', 'Pengobatan', 'trim');        
        $this->form_validation->set_rules('no_registrasi', 'No Registrasi', 'trim|required');        
        $this->form_validation->set_rules('no_kunjungan', 'No Kunjungan', 'trim|required');        
        $this->form_validation->set_rules('kode_bagian_asal', 'Kode Bagian Asal', 'trim|required');             
        $this->form_validation->set_rules('kode_dokter_poli', 'Dokter Poli', 'trim');             
        $this->form_validation->set_rules('check_pm[]', 'Pilih Penunjang Medis', 'trim|required', array('required' => 'Apakah ada penunjang? Jika "Tidak Ada" silahkan pilih "Tidak ada Penunjang"'));             
        $this->form_validation->set_rules('check_resep[]', 'Dokter Poli', 'trim|required', array('required' => 'Apakah ada Resep? Jika "Tidak ada" maka pilih "Tidak"'));     
                
        // form assesment
        $this->form_validation->set_rules('pl_tb', 'Tinggi Badan', 'trim');        
        $this->form_validation->set_rules('pl_bb', 'Berat Badan', 'trim');        
        $this->form_validation->set_rules('pl_td', 'Tekanan Darah', 'trim');        
        $this->form_validation->set_rules('pl_suhu', 'Suhu', 'trim');        
        $this->form_validation->set_rules('pl_nadi', 'Nadi', 'trim');        
        $this->form_validation->set_rules('pl_resep_farmasi', 'Nadi', 'trim');        

        // set message error
        $this->form_validation->set_message('required', "Silahkan isi field \"%s\"");        
        $this->form_validation->set_message('min_length', "\"%s\" Minimal 8 Karakter");        

        if ($this->form_validation->run() == FALSE)
        {
            $this->form_validation->set_error_delimiters('<div style="color:white"><i>', '</i></div>');
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            /*execution*/
            $this->db->trans_begin();           
            // print_r($_POST);die;
            $no_kunjungan = $this->form_validation->set_value('no_kunjungan');
            $no_registrasi = $this->form_validation->set_value('no_registrasi');

            /*insert log diagnosa pasien th_riwayat pasien*/
            $riwayat_diagnosa = array(
                'no_registrasi' => $this->form_validation->set_value('no_registrasi'),
                'no_kunjungan' => $no_kunjungan,
                'no_mr' => $this->form_validation->set_value('noMrHidden'),
                'nama_pasien' => $this->input->post('nama_pasien_layan'),
                'diagnosa_awal' => $this->master->br2nl($_POST['pl_diagnosa']),
                'anamnesa' => $this->master->br2nl($_POST['pl_anamnesa']),
                'pengobatan' => $this->master->br2nl($_POST['pl_pengobatan']),
                'resep_farmasi' => $this->master->br2nl($_POST['pl_resep_farmasi']),
                'dokter_pemeriksa' => $this->input->post('dokter_pemeriksa'),
                'pemeriksaan' => $this->master->br2nl($_POST['pl_pemeriksaan']),
                'tgl_periksa' => date('Y-m-d H:i:s'),
                'kode_bagian' => $this->form_validation->set_value('kode_bagian_asal'),
                'diagnosa_akhir' => $this->form_validation->set_value('pl_diagnosa'),
                'kategori_tindakan' => 3,
                'kode_icd_diagnosa' => $this->input->post('pl_diagnosa_hidden'),
                'tinggi_badan' => $this->input->post('pl_tb'),
                'tekanan_darah' => $this->input->post('pl_td'),
                'berat_badan' => $this->input->post('pl_bb'),
                'suhu' => $this->input->post('pl_suhu'),
                'nadi' => $this->input->post('pl_nadi'),
            );

            if($this->input->post('kode_riwayat')==0){
                $this->Pl_pelayanan->save('th_riwayat_pasien', $riwayat_diagnosa);
            }else{
                $this->Pl_pelayanan->update('th_riwayat_pasien', $riwayat_diagnosa, array('kode_riwayat' => $this->input->post('kode_riwayat') ) );
            }

            // save billing pasien 

            /*tarif sarana rs*/
            $kode_trans_pelayanan = $this->master->get_max_number('tc_trans_pelayanan', 'kode_trans_pelayanan');
            
            $dataexc = array(
                /*form hidden input default*/
                'no_kunjungan' => $this->regex->_genRegex($no_kunjungan,'RGXINT'),
                'no_registrasi' => $this->regex->_genRegex($this->input->post('no_registrasi'),'RGXINT'),
                'kode_kelompok' => $this->regex->_genRegex($this->input->post('kode_kelompok'),'RGXINT'),
                'kode_perusahaan' => $this->regex->_genRegex($this->input->post('kode_perusahaan'),'RGXINT'),
                'no_mr' => $this->regex->_genRegex($this->input->post('noMrHidden'),'RGXQSL'),
                'nama_pasien_layan' => $this->regex->_genRegex($this->input->post('nama_pasien_layan'),'RGXQSL'),
                'kode_bagian_asal' => $this->regex->_genRegex($this->input->post('kode_bagian_asal'),'RGXQSL'),
                /*end form hidden input default*/
                'kode_bagian' => $this->regex->_genRegex($this->input->post('kode_bagian_asal'),'RGXQSL'),
                'kode_klas' => $this->regex->_genRegex($this->input->post('kode_klas'),'RGXINT'),
                'tgl_transaksi' => date('Y-m-d'),                
                'jumlah' => 1,  
                'satuan_tindakan' => '',  
            );

            // cek tarif sarana
            $cek_tarif_sarana = $this->db->get_where('tc_trans_pelayanan', array('no_registrasi' => $_POST['no_registrasi'], 'kode_bagian_asal' => $_POST['kode_bagian_asal'], 'jenis_tindakan' => 13) )->num_rows();
            if($cek_tarif_sarana == 0){
                // tarif sarana
                $tarif_sarana = $this->tarif->insert_tarif_by_jenis_tindakan($dataexc, 13);            
            }
            
            // cek tarif konsultasi
            $cek_tarif_konsultasi = $this->db->get_where('tc_trans_pelayanan', array('no_registrasi' => $_POST['no_registrasi'], 'kode_bagian_asal' => $_POST['kode_bagian_asal'], 'jenis_tindakan' => 12) )->num_rows();
            if($cek_tarif_konsultasi == 0){
                /*tarif konsultasi*/
                $dataexc['kode_dokter1'] = $_POST['kode_dokter_poli'];
                $tarif_konsultasi = $this->tarif->insert_tarif_by_jenis_tindakan($dataexc, 12);
            }

            // pulangkan pasien
            $this->daftar_pasien->pulangkan_pasien($no_kunjungan,3);

            // simpan penunjang medis
            if(isset($_POST['check_pm'])) :
                $title = 'Rujuk Penunjang Medis';
                $no_mr = $this->regex->_genRegex($this->form_validation->set_value('noMrHidden'),'RGXQSL');
                $kode_perusahaan = $this->regex->_genRegex($this->form_validation->set_value('kode_perusahaan'),'RGXINT');
                $kode_kelompok =  $this->regex->_genRegex($this->form_validation->set_value('kode_kelompok'),'RGXINT');
                $kode_dokter = $this->regex->_genRegex(0,'RGXINT');
                $no_registrasi = $this->input->post('no_registrasi');
                $kode_bagian_asal = $_POST['kode_bagian_asal'];

                foreach ($_POST['check_pm'] as $row_val) {
                    
                    if($row_val != 0):
                        // cek existing
                        $existing_pm = $this->db->get_where('tc_kunjungan', array('no_registrasi' => $no_registrasi, 'kode_bagian_tujuan' => $row_val, 'kode_bagian_asal' => $kode_bagian_asal) )->num_rows();
                        if($existing_pm == 0){
                            $kode_bagian_tujuan = $this->regex->_genRegex($row_val,'RGXQSL');
                            $no_kunjungan = $this->daftar_pasien->daftar_kunjungan($title, $no_registrasi, $no_mr, $kode_dokter, $kode_bagian_tujuan, $kode_bagian_asal);
                            
                            /*insert penunjang medis*/
                            $kode_penunjang = $this->master->get_max_number('pm_tc_penunjang', 'kode_penunjang');
                            $no_antrian = $this->master->get_no_antrian_pm($this->regex->_genRegex($kode_bagian_tujuan,'RGXQSL'));
                            $klas = $this->input->post('kode_klas');
                            $data_pm_tc_penunjang = array(
                                'kode_penunjang' => $kode_penunjang,
                                'tgl_daftar' => date('Y-m-d H:i:s'),
                                'kode_bagian' => $this->regex->_genRegex($kode_bagian_tujuan,'RGXQSL'),
                                'no_kunjungan' => $no_kunjungan,
                                'no_antrian' => $no_antrian,
                                'kode_klas' => $klas,
                                'petugas_input' => $this->session->userdata('user')->user_id,
                            );

                            /*save penunjang medis*/
                            $this->Pl_pelayanan->save('pm_tc_penunjang', $data_pm_tc_penunjang);

                            /*save logs*/
                            $this->logs->save('pm_tc_penunjang', $kode_penunjang, 'insert new record on Pendaftaran Penunjang Medis module', json_encode($data_pm_tc_penunjang),'kode_penunjang');

                            $this->db->trans_commit();
                        }
                    endif;
                    
                }
                
            endif; 
            
            // simpan pesan farmasi
            if(isset($_POST['check_resep']) AND $_POST['check_resep'] != 0) :

                $dataexc_fr = array(
                    'kode_bagian' => $this->regex->_genRegex('060101', 'RGXQSL'),
                    'no_registrasi' => $this->input->post('no_registrasi'),
                    'no_kunjungan' => $this->input->post('no_kunjungan'),
                    'no_mr' => $this->input->post('noMrHidden'),
                    'resep_farmasi' => $this->master->br2nl($_POST['pl_resep_farmasi']),
                    'kode_perusahaan' => ($this->input->post('kode_perusahaan'))?$this->input->post('kode_perusahaan'):0,
                    'kode_kelompok' => $this->input->post('kode_kelompok'),
                    'kode_klas' => $this->input->post('kode_klas'),
                    'kode_profit' => 2000,
                    'kode_bagian_asal' => $this->input->post('kode_bagian_asal'),
                );
                $dataexc_fr['created_date'] = date('Y-m-d H:i:s');
                $dataexc_fr['created_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                
                $kode_pesan_resep = $this->master->get_max_number('fr_tc_pesan_resep', 'kode_pesan_resep');

                $dataexc_fr['kode_pesan_resep'] = $kode_pesan_resep;
                $dataexc_fr['tgl_pesan'] = date("Y-m-d h:i:s");
                $dataexc_fr['kode_dokter'] = $this->regex->_genRegex($this->form_validation->set_value('kode_dokter_poli'), 'RGXQSL');
                $dataexc_fr['jumlah_r'] = 1;
                $dataexc_fr['lokasi_tebus'] = 1;

                // cek existing pesan resep
                $existing_fr = $this->db->get_where('fr_tc_pesan_resep', array('no_registrasi' => $no_registrasi, 'kode_bagian_asal' => $this->input->post('kode_bagian_asal')) );

                if($existing_fr->num_rows() == 0){
                    /*save post data*/
                    $this->Pl_pelayanan->save('fr_tc_pesan_resep',$dataexc_fr);

                    /*save logs*/
                    $this->logs->save('fr_tc_pesan_resep', $kode_pesan_resep, 'update record on '.$this->title.' module', json_encode($dataexc_fr),'kode_pesan_resep');

                    $this->db->trans_commit();
                }else{
                    $this->Pl_pelayanan->update('fr_tc_pesan_resep',$dataexc_fr, array('kode_pesan_resep' => $existing_fr->row()->kode_pesan_resep) );
                    $this->db->trans_commit();
                }
                

            endif; 

            /*update pl_tc_poli*/
            $arrPlTcPoli = array('status_periksa' => 3, 'tgl_keluar_poli' => date('Y-m-d H:i:s'), 'no_induk' => $this->session->userdata('user')->user_id, 'created_by' => $this->session->userdata('user')->fullname );
            $this->db->where('id_pl_tc_poli', $_POST['id_pl_tc_poli'])->update('pl_tc_poli', $arrPlTcPoli );

            // generate dokumen resume
            // if($_POST['kode_perusahaan'] == 120){
            //     $filename = 'RESUME-'.$_POST['no_mr'].'-'.$_POST['no_registrasi'].'-'.date('dmY').'';;
            //     $this->cbpModule->generateSingleDoc($filename);
            // }

            // update task
            // update task antrian online
            $waktukirim = strtotime(date('Y-m-d H:i:s')) * 1000;
            $this->AntrianOnline->postDataWs('antrean/updatewaktu', array('kodebooking' => $_POST['kode_perjanjian'], 'taskid' => 5, 'waktu' => $waktukirim));


            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                
                // search pasien berikutnya
                $antrian_pasien = $this->Pl_pelayanan->get_next_antrian_pasien_sess_dr();
                $next_pasien = isset($antrian_pasien)?$antrian_pasien: ''; 

                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'type_pelayanan' => 'Pasien Selesai', 'next_pasien' => isset($next_pasien->no_mr)?$next_pasien->no_mr:'', 'next_id_pl_tc_poli' => isset($next_pasien->id_pl_tc_poli)?$next_pasien->id_pl_tc_poli:'', 'next_no_kunjungan' => isset($next_pasien->no_kunjungan)?$next_pasien->no_kunjungan:''));
            }

        
        }

    }

    public function saveSessionPoli(){

        //print_r($_POST);die;
        // form validation
        $this->form_validation->set_rules('poliklinik', 'Poli/Klinik', 'trim|required');      
        $this->form_validation->set_rules('select_dokter', 'Dokter', 'trim|required');      
        $this->form_validation->set_rules('submit', 'Submit', 'trim|required');      

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
            $bagian = $this->db->get_where('mt_bagian', array('kode_bagian' => $this->form_validation->set_value('poliklinik')) )->row();
            $dokter = $this->db->get_where('mt_karyawan', array('kode_dokter' => $this->form_validation->set_value('select_dokter')) )->row();

            $this->session->set_userdata('flag_form_pelayanan', $this->form_validation->set_value('submit'));
            $this->session->set_userdata('kode_bagian', $this->form_validation->set_value('poliklinik'));
            $this->session->set_userdata('nama_bagian', $bagian->nama_bagian );
            $this->session->set_userdata('sess_kode_dokter', $this->form_validation->set_value('select_dokter'));
            $this->session->set_userdata('sess_nama_dokter', $dokter->nama_pegawai );

            echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'type_pelayanan' => 'Pasien Selesai'));
        
        }

    }

    public function destroy_session_kode_bagian()
    {
        $this->session->unset_userdata('kode_bagian');
        $this->session->unset_userdata('flag_form_pelayanan');
        echo json_encode(array('status' => 200, 'message' => 'Silahkan pilih Poli/Klinik kembali'));

        
    }

    public function cancel_visit()
    {   
        // echo '<pre>';print_r($_POST);die;
        $this->db->trans_begin();   
        /*update tc_registrasi*/
        $reg_data = array('tgl_jam_keluar' => date('Y-m-d H:i:s'), 'kode_bagian_keluar' => $_POST['kode_bag'], 'status_batal' => 1 );
        $this->db->update('tc_registrasi', $reg_data, array('no_registrasi' => $_POST['no_registrasi'] ) );
        $this->logs->save('tc_registrasi', $_POST['no_registrasi'], 'update tc_registrasi Modul Pelayanan', json_encode($reg_data),'no_registrasi');


        /*tc_kunjungan*/
        $kunj_data = array('tgl_keluar' => date('Y-m-d H:i:s'), 'status_keluar' => 1, 'status_batal' => 1 );
        $this->db->update('tc_kunjungan', $kunj_data, array('no_registrasi' => $_POST['no_registrasi'], 'no_kunjungan' => $_POST['no_kunjungan'] ) );
        $this->logs->save('tc_kunjungan', $_POST['no_kunjungan'], 'update tc_kunjungan Modul Pelayanan', json_encode($kunj_data),'no_kunjungan');

        // igd batal
        if($_POST['kode_bag']=='020101'){
            // echo '<pre>';print_r($_POST);die;
            $gd_dt = array('status_batal' => 1, 'tgl_jam_kel' => date('Y-m-d H:i:s') );
            $this->db->update('gd_tc_gawat_darurat', $gd_dt, array('no_kunjungan' => $_POST['no_kunjungan']) );
            $this->logs->save('gd_tc_gawat_darurat', $_POST['no_kunjungan'], 'update gd_tc_gawat_darurat Modul Pelayanan', json_encode($gd_dt),'no_kunjungan');
        }elseif($_POST['kode_bag']=='030501'){
            $vk_dt = array('status_batal' => 1, 'tgl_keluar' => date('Y-m-d H:i:s') );
            $this->db->update('ri_pasien_vk', $vk_dt, array('no_kunjungan' => $_POST['no_kunjungan']) );
        }
        else{
            /*pl_tc_poli*/
            $poli_data = array('status_batal' => 1, 'no_induk' => $this->session->userdata('user')->user_id, 'created_by' => $this->session->userdata('user')->fullname );
            $this->db->update('pl_tc_poli', $poli_data, array('no_kunjungan' => $_POST['no_kunjungan']) );
            $this->logs->save('pl_tc_poli', $_POST['no_kunjungan'], 'update pl_tc_poli Modul Pelayanan', json_encode($poli_data),'no_kunjungan');
        }

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
        }
        else
        {
            $this->db->trans_commit();
            echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan' ) );
        }
        
    }

    public function rollback()
    {   
        $this->db->trans_begin();  

        /*update tc_registrasi*/
        $reg_data = array('tgl_jam_keluar' => NULL, 'kode_bagian_keluar' => NULL, 'status_batal' => NULL );
        $this->db->update('tc_registrasi', $reg_data, array('no_registrasi' => $_POST['no_registrasi'] ) );
        $this->logs->save('tc_registrasi', $_POST['no_registrasi'], 'update tc_registrasi Modul Pelayanan', json_encode($reg_data),'no_registrasi');


        /*tc_kunjungan*/
        $kunj_data = array('tgl_keluar' => NULL, 'status_keluar' => NULL, 'status_batal' => NULL );
        $this->db->update('tc_kunjungan', $kunj_data, array('no_registrasi' => $_POST['no_registrasi'], 'no_kunjungan' => $_POST['no_kunjungan'] ) );
        $this->logs->save('tc_kunjungan', $_POST['no_kunjungan'], 'update tc_kunjungan Modul Pelayanan', json_encode($kunj_data),'no_kunjungan');

        /*pl_tc_poli*/
        $poli_data = array('tgl_keluar_poli' => NULL, 'status_periksa' => NULL, 'status_batal' => NULL );
        $this->db->update('pl_tc_poli', $poli_data, array('no_kunjungan' => $_POST['no_kunjungan']) );
        $this->logs->save('pl_tc_poli', $_POST['no_kunjungan'], 'update pl_tc_poli Modul Pelayanan', json_encode($poli_data),'no_kunjungan');

        if($_POST['flag']=='submited'){

            /*delete ak_tc_transaksi_det*/
            $this->Pl_pelayanan->delete_ak_tc_transaksi_det($_POST['no_kunjungan']);
            /*delete ak_tc_transaksi*/
            $this->Pl_pelayanan->delete_ak_tc_transaksi($_POST['no_kunjungan']);
            /*delete transaksi_kasir*/
            $this->Pl_pelayanan->delete_transaksi_kasir($_POST['no_kunjungan']);

        }

        /*tc_trans_pelayanan*/
        $trans_data = array('status_selesai' => 2, 'status_nk' => NULL, 'kode_tc_trans_kasir' => NULL );
        $this->db->update('tc_trans_pelayanan', $trans_data, array('no_kunjungan' => $_POST['no_kunjungan'], 'no_registrasi' => $_POST['no_registrasi'] ) );


        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
        }
        else
        {
            $this->db->trans_commit();
            echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan' ) );
        }
        
    }

    function getDiagnosaByKode($kode_riwayat){

        $result = $this->db->get_where('th_riwayat_pasien', array('kode_riwayat' => $kode_riwayat) )->row();
        echo json_encode($result);
    }

    function updateBilling(){


        $this->db->trans_begin();  
        // print_r($_POST);die;
        $data = array();
        foreach ($_POST['fields_'.$_GET['kode'].''] as $field) {
            $data[$field] = $_POST['total_diskon_'.$field.'_'.$_GET['kode'].''];
            if(!in_array($field, array('bill_dr1', 'bill_dr2', 'bill_dr3') )){
                $total[] = $_POST['total_diskon_'.$field.'_'.$_GET['kode'].''];
            }else{
                $bill_dr[] = $_POST['total_diskon_'.$field.'_'.$_GET['kode'].''];
            }
        }
        /*total bill_rs*/
        $data['bill_rs'] = array_sum($total);

        //print_r($data);die;

        /*insert data*/
        $this->db->update('tc_trans_pelayanan', $data, array('kode_trans_pelayanan' => $_GET['kode']) );

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
        }
        else
        {
            $this->db->trans_commit();
            echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan' ) );
        }

    }

    public function process_add_tindakan_lain(){

        // print_r($_POST);die;
        // form validation
        $this->form_validation->set_rules('noMrHidden', 'No MR', 'trim|required');      
        $this->form_validation->set_rules('kode_jenis_tindakan', 'Jenis Tindakan', 'trim|required');      

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

            $kode_trans_pelayanan = $this->master->get_max_number('tc_trans_pelayanan', 'kode_trans_pelayanan');

            $dataexc = array(
                /*form hidden input default*/
                'no_kunjungan' => $this->regex->_genRegex($this->input->post('no_kunjungan'),'RGXINT'),
                'no_registrasi' => $this->regex->_genRegex($this->input->post('no_registrasi'),'RGXINT'),
                'kode_kelompok' => $this->regex->_genRegex($this->input->post('kode_kelompok'),'RGXINT'),
                'kode_perusahaan' => $this->regex->_genRegex($this->input->post('kode_perusahaan'),'RGXINT'),
                'no_mr' => $this->regex->_genRegex($this->input->post('noMrHidden'),'RGXQSL'),
                'nama_pasien_layan' => $this->regex->_genRegex($this->input->post('nama_pasien_hidden'),'RGXQSL'),
                'kode_bagian_asal' => $this->regex->_genRegex($this->input->post('kode_bagian_asal'),'RGXQSL'),
                /*end form hidden input default*/
                'kode_bagian' => $this->regex->_genRegex($this->input->post('kode_bagian'),'RGXQSL'),
                'kode_klas' => $this->regex->_genRegex($this->input->post('kode_klas'),'RGXINT'),
                'kode_bagian_asal' => $this->regex->_genRegex($this->input->post('kode_bagian_asal'),'RGXQSL'),
                'tgl_transaksi' => $this->tanggal->sqlDateForm($this->regex->_genRegex($this->input->post('pl_tgl_transaksi'),'RGXQSL')),                
                'jumlah' => 1,  
                'satuan_tindakan' => 'Pkt',  
            );

            /*detail tindakan*/
            $dataexc['kode_trans_pelayanan'] = $kode_trans_pelayanan;
            $dataexc['jenis_tindakan'] = $_POST['kode_jenis_tindakan'];
            $dataexc['nama_tindakan'] = ucwords($this->regex->_genRegex($this->input->post('nama_tindakan'),'RGXQSL'));
            $dataexc["bill_rs"] = (isset($_POST['bill_rs']))?(int)$_POST['bill_rs']:0;
            $dataexc["bill_dr1"] = (isset($_POST['bill_dr1']))?(int)$_POST['bill_dr1']:NULL;
            $dataexc["bill_dr2"] = (isset($_POST['bill_dr2']))?(int)$_POST['bill_dr2']:NULL;
            $dataexc["bill_dr3"] = (isset($_POST['bill_dr3']))?(int)$_POST['bill_dr3']:NULL;
            $dataexc["kode_dokter1"] = (isset($_POST['kode_dokter_1_hidden']))?(int)$_POST['kode_dokter_1_hidden']:NULL;
            $dataexc["kode_dokter2"] = (isset($_POST['kode_dokter_2_hidden']))?(int)$_POST['kode_dokter_2_hidden']:NULL;
            $dataexc["kode_dokter3"] = (isset($_POST['kode_dokter_3_hidden']))?(int)$_POST['kode_dokter_3_hidden']:NULL;
            $dataexc["bhp"] = (isset($_POST['bhp']))?(int)$_POST['bhp']:NULL;
            $dataexc["alat_rs"] = (isset($_POST['alat_rs']))?(int)$_POST['alat_rs']:NULL;
            $dataexc["pendapatan_rs"] = (isset($_POST['pendapatan_rs']))?(int)$_POST['pendapatan_rs']:NULL;
            $dataexc["kamar_tindakan"] = (isset($_POST['kamar_tindakan']))?(int)$_POST['kamar_tindakan']:NULL;
            //$dataexc["tindakan_luar"] = $this->input->post('tindakan_luar');
            
            // print_r($dataexc);die;

            $this->Pl_pelayanan->save('tc_trans_pelayanan', $dataexc);

            /*save logs*/
            $this->logs->save('tc_trans_pelayanan', $kode_trans_pelayanan, 'insert new record on '.$this->title.' module', json_encode($dataexc),'kode_trans_pelayanan');

            
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

    public function process_input_expertise(){

        
        // form validation
        $this->form_validation->set_rules('noMrHidden', 'Pasien', 'trim|required', array('required' => 'No MR Pasien Tidak ditemukan!') );        
        $this->form_validation->set_rules('konten', 'Input Hasil Expertise', 'trim|required');        
        $this->form_validation->set_rules('no_registrasi', 'No Registrasi', 'trim|required');        
        $this->form_validation->set_rules('no_kunjungan', 'No Kunjungan', 'trim|required');        
        $this->form_validation->set_rules('kode_bagian_asal', 'Kode Bagian Asal', 'trim|required');
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

            $no_kunjungan = $this->form_validation->set_value('no_kunjungan');
            $no_registrasi = $this->form_validation->set_value('no_registrasi');

            /*insert log diagnosa pasien th_riwayat pasien*/
            $dtexpertise = array(
                'no_registrasi' => $this->form_validation->set_value('no_registrasi'),
                'no_kunjungan' => $no_kunjungan,
                'no_mr' => $this->form_validation->set_value('noMrHidden'),
                'nama_pasien' => $this->input->post('nama_pasien_layan'),
                'hasil_expertise' => $this->input->post('konten'),
                'jenis_expertise' => $this->input->post('jenis_expertise'),
                'kode_bagian_asal' => $this->input->post('kode_bagian_asal'),
                'kode_bagian_tujuan' => $this->input->post('kode_bag_expertise'),
                'nama_dokter' => $this->input->post('dokter_pemeriksa'),
            );
            // print_r($dtexpertise);die;

            if($this->input->post('kode_expertise')==0){
                $dtexpertise['created_date'] = date('Y-m-d H:i:s');
                $dtexpertise['created_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                $newId = $this->Pl_pelayanan->save('th_expertise_pasien', $dtexpertise);
            }else{
                $dtexpertise['updated_date'] = date('Y-m-d H:i:s');
                $dtexpertise['updated_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                $this->Pl_pelayanan->update('th_expertise_pasien', $dtexpertise, array('kode_expertise' => $this->input->post('kode_expertise') ) );
                $newId = $this->input->post('kode_expertise');
            }
            
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'title' => $dtexpertise['jenis_expertise'], 'kode_bagian_asal' => $dtexpertise['kode_bagian_asal'], 'kode_bagian_tujuan' => $dtexpertise['kode_bagian_tujuan'], 'type_pelayanan' => 'Expertise', 'ID' => $newId));
            }

        
        }

    }

    public function form_perjanjian_modal($id='') 
    {
        
        $data = array();
        
        /*if id is not null then will show form edit*/
        
        $data_pasien = $this->Reg_pasien->search_pasien_by_keyword( $id, array('no_mr') );

        /*echo '<pre>'; print_r($data_pasien);*/

        $data['value'] = $data_pasien[0];
        $data['kode_bagian'] = $_GET['kode_bagian'];
        $data['kode_dokter'] = $_GET['kode_dokter'];
        $data['kode_perusahaan'] = $_GET['kode_perusahaan'];

        $booking_id = ($this->input->get('ID'))?$this->input->get('ID'):0;
        
        // $data['booking_id'] = $booking_id;

        // if($booking_id!=0){

        //     $booking_data = $this->db->get_where('regon_booking', array('regon_booking_id' => $booking_id) )->row();
        //     $data['booking'] = $booking_data;

        // }
        
        /*load form view*/
        
        $this->load->view('Pl_pelayanan/form_perjanjian_rj_modal', $data);
    
    }

    public function form_perjanjian_view($id='') 
    {
        /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Pl_pelayanan/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        $data = array();
        
        /*if id is not null then will show form edit*/
        
        $data_pasien = $this->Reg_pasien->search_pasien_by_keyword( $id, array('no_mr') );

        /*echo '<pre>'; print_r($data_pasien);*/

        $data['title'] = 'Perjanjian Pasien Rawat Jalan';
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        $data['value'] = $data_pasien[0];
        $data['kode_bagian'] = $_GET['kode_bagian'];
        $data['kode_dokter'] = $_GET['kode_dokter'];
        $data['kode_perusahaan'] = $_GET['kode_perusahaan'];

        // echo '<pre>'; print_r($data);
        
        $booking_id = ($this->input->get('ID'))?$this->input->get('ID'):0;
        
        // $data['booking_id'] = $booking_id;

        // if($booking_id!=0){

        //     $booking_data = $this->db->get_where('regon_booking', array('regon_booking_id' => $booking_id) )->row();
        //     $data['booking'] = $booking_data;

        // }
        
        /*load form view*/
        
        $this->load->view('Pl_pelayanan/form_perjanjian_rj_view', $data);
    
    }

    public function form_perjanjian_view_ontabs($id='') 
    {
        /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Pl_pelayanan/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        $data = array();
        
        /*if id is not null then will show form edit*/
        
        $data_pasien = $this->Reg_pasien->search_pasien_by_keyword( $id, array('no_mr') );

        /*echo '<pre>'; print_r($data_pasien);*/

        $data['title'] = 'Perjanjian Pasien Rawat Jalan';
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        $data['value'] = $data_pasien[0];
        $data['kode_bagian'] = $_GET['kode_bagian'];
        $data['kode_dokter'] = $_GET['kode_dokter'];
        $data['kode_perusahaan'] = $_GET['kode_perusahaan'];

        $booking_id = ($this->input->get('ID'))?$this->input->get('ID'):0;
        
        // $data['booking_id'] = $booking_id;

        // if($booking_id!=0){

        //     $booking_data = $this->db->get_where('regon_booking', array('regon_booking_id' => $booking_id) )->row();
        //     $data['booking'] = $booking_data;

        // }
        
        /*load form view*/
        
        $this->load->view('Pl_pelayanan/form_perjanjian_rj_view_2', $data);
    
    }

    public function view_detail_resume_medis($no_registrasi, $no_kunjungan='') { 
        
        $resume = $this->Reg_pasien->get_detail_resume_medis($no_registrasi, $no_kunjungan);
        $data = [
            'result' => $resume,
            'penunjang' => $resume['penunjang'],
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

        // echo '<pre>';print_r($data);die;

        $html = $this->load->view('Pl_pelayanan/view_resume_medis', $data, true);
        echo json_encode(array('html' => $html, 'data' => $data));
    
    }



}

/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
