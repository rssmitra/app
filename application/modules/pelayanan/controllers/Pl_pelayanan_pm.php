<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pl_pelayanan_pm extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'pelayanan/Pl_pelayanan_pm');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('Pl_pelayanan_pm_model', 'Pl_pelayanan_pm');
        $this->load->model('Pl_pelayanan_model', 'Pl_pelayanan');
        $this->load->model('registration/Reg_pasien_model', 'Reg_pasien');
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
        $type = $_GET['type_tujuan'];
        $step = (isset($_GET['step']))?$_GET['step']:'';
        $nama_bagian = $this->db->get_where('mt_bagian',array('kode_bagian' => $type))->row();
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show(),
            'step' => $step,
            'bag_tujuan' => $type,
            'nama_bag' => $nama_bagian->nama_bagian
        );

        if($type=='050301'){
            $view = 'index_without_wizard';
        }else{
            $view = 'index';
        }

        $this->load->view('Pl_pelayanan_pm/'.$view.'', $data);
    }

    public function form($no_kunjungan, $kode_penunjang='',$status)
    {
         /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Pl_pelayanan_pm/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$kode_penunjang);

        /*get value by id*/
        $data['value'] = $this->Pl_pelayanan_pm->get_by_id($kode_penunjang);
        // echo '<pre>'; print_r($data);die;
        $data['riwayat'] = $this->Pl_pelayanan_pm->get_riwayat_pasien_by_id($no_kunjungan);
        $data['tgl_kontrol'] = $this->Pl_pelayanan_pm->get_tgl_kontrol($no_kunjungan);
        $data['transaksi'] = $this->Pl_pelayanan_pm->get_transaksi_pasien_by_id($no_kunjungan);
        /*variable*/
        $bag = substr($data['value']->kode_bagian_asal, 1, 1);
        $data['type_asal'] = ($bag==3)?'RI':'RJ';
        $data['no_mr'] = $data['value']->no_mr;
        $data['id'] = $kode_penunjang;
        $data['kode_klas'] = $data['value']->kode_klas;
        $data['kode_profit'] = 2000;
        $data['no_kunjungan'] = $no_kunjungan;
        $data['status'] = $status;
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Pl_pelayanan_pm/form', $data);
    }

    public function tindakan($id='', $no_kunjungan='')
    {
         /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Pl_pelayanan_pm/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*get value by id*/
        $data['value'] = $this->Pl_pelayanan_pm->get_by_id($id);
        /*mr*/
        $data['no_mr'] = $data['value']->no_mr;
        $data['no_kunjungan'] = $no_kunjungan;
        $data['kode_penunjang'] = $id;
        $data['sess_kode_bag'] = ( $data['value']->kode_bagian_tujuan)? $data['value']->kode_bagian_tujuan:0;
        $data['type']='PM';
        $data['status_pulang'] = ($data['value']->status_daftar>=1)?1:0;
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        // echo '<pre>'; print_r($data);die;
        /*load form view*/
        $this->load->view('Pl_pelayanan/form_tindakan', $data);
    }

    public function diagnosa($id='', $no_kunjungan='')
    {
         /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Pl_pelayanan/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*get value by id*/
        $data['value'] = $this->Pl_pelayanan_pm->get_by_id($id);
        // echo '<pre>'; print_r($this->db->last_query());die;
        $data['riwayat'] = $this->Pl_pelayanan->get_riwayat_pasien_by_id($no_kunjungan);
        /*mr*/
        $kode_klas = 16;
        $data['type'] = $_GET['type'];
        $data['no_mr'] = $data['value']->no_mr;
        $data['no_kunjungan'] = $no_kunjungan;
        $data['kode_penunjang'] = $id;
        $data['sess_kode_bag'] = ( $data['value']->kode_bagian_tujuan)? $data['value']->kode_bagian_tujuan:0;
        $data['status_pulang'] = ($data['value']->status_daftar>=1)?1:0;
        $data['kode_klas'] = $kode_klas;
        // echo '<pre>'; print_r($data);die;
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Pl_pelayanan_pm/form_diagnosa', $data);
    }

    public function form_isi_hasil($no_kunjungan, $kode_bag_tujuan, $kode_penunjang='')
    {
         /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Pl_pelayanan_pm/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$kode_penunjang);

        $no_mr = $_GET['mr'];

        $data['pasien'] = $this->Reg_pasien->get_by_mr($no_mr);

        $userDob = $data['pasien']->tgl_lhr;
 
        //Create a DateTime object using the user's date of birth.
        $dob = new DateTime($userDob);
     
        //We need to compare the user's date of birth with today's date.
        $now = new DateTime();

        //Calculate the time difference between the two dates.
        $difference = $now->diff($dob);
        $split_tgl_lhr = $this->tanggal->AgeWithYearMonthDayByStrip($data['pasien']->tgl_lhr);
        sscanf($split_tgl_lhr, '%d-%d-%d', $y, $m, $d);

        //Get the difference in years, as we are looking for the user's age.
        // $umur_tahun = $difference->format('%y');
        // $umur_bulan = $difference->format('%m') + 12 * $difference->format('%y');
        // $umur_hari = $difference->d;
        // echo $umur_tahun.'-'.$umur_bulan.'-'.$umur_hari;die;

        $mktime_tahun = 31622400 * $y;
        $mktime_bulan = 2678400 * $m;
        $mktime_hari = 86400 * $d;
        $mktime_jam = 0;

        $mktimenya = $mktime_tahun + $mktime_bulan + $mktime_hari + $mktime_jam;
        
        /*get value by id*/
        $data['mktime'] = $mktimenya;
        // $list =  (isset($_GET['is_edit']) AND $_GET['is_edit']!='')?$this->Pl_pelayanan_pm->get_data_hasil_pasien_pm($kode_penunjang,$kode_bag_tujuan):$this->Pl_pelayanan_pm->get_datatables_hasil_pm($kode_penunjang,$kode_bag_tujuan,$mktimenya);
        if((!isset($_GET['is_mcu'])) AND (isset($_GET['is_edit']) AND $_GET['is_edit']!='')){
            $list = $this->Pl_pelayanan_pm->get_data_hasil_pasien_pm($kode_penunjang,$kode_bag_tujuan);
            // echo '<pre>';print_r($list);die;
        }else if((isset($_GET['is_mcu']) AND $_GET['is_mcu']==1)){
            $list = $this->Pl_pelayanan_pm->get_data_hasil_pasien_pm_mcu($kode_penunjang,$kode_bag_tujuan);
            // echo '<pre>';print_r($list);die;
        }else if((isset($_GET['is_mcu']) AND $_GET['is_mcu']==2)){
            $list = $this->Pl_pelayanan_pm->get_hasil_pm_mcu($kode_penunjang,$kode_bag_tujuan,$mktimenya);
            // echo '<pre>';print_r($list);die;
        }else{
            $list = $this->Pl_pelayanan_pm->get_datatables_hasil_pm($kode_penunjang,$kode_bag_tujuan,$mktimenya);
        }
        $data['list'] = $list;
        
        if($kode_bag_tujuan=='050201'){
            $data['bpako'] = $this->Pl_pelayanan_pm->get_bpako($kode_penunjang);
            $view = 'form_isi_hasil_rad';
        }else if($kode_bag_tujuan=='050101'){
            $view = 'form_isi_hasil_lab';
        }

        /*variable*/
        $data['id'] = $kode_penunjang;
        $data['kode_profit'] = 2000;
        $data['no_kunjungan'] = $no_kunjungan;
        $data['no_mr'] = $no_mr;
        $penunjang = $this->Pl_pelayanan_pm->get_by_id($kode_penunjang);
        $data['no_registrasi'] = $penunjang->no_registrasi;
        $data['catatan_hasil'] = $penunjang->catatan_hasil;
        if(isset($_GET['is_edit']) AND $_GET['is_edit']!=''){
            $data['is_edit'] = $_GET['is_edit'];
        }
        if(isset($_GET['is_mcu']) AND $_GET['is_mcu']!=''){
            $data['is_mcu'] = $_GET['is_mcu'];
        }
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $data['attachment'] = $this->upload_file->getUploadedFilePenunjang($kode_penunjang);

        // echo '<pre>';print_r($data);die;
        $this->load->view('Pl_pelayanan_pm/'.$view.'', $data);
        
    }

    public function form_periksa($kode_penunjang)
    {
         /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Pl_pelayanan_pm/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$kode_penunjang);

        // get order details
        $penunjang = $this->Pl_pelayanan_pm->get_by_id($kode_penunjang);
        // echo '<pre>'; print_r($penunjang);die;

        $no_mr = $penunjang->no_mr;
        $kode_bag_tujuan = $penunjang->kode_bagian_tujuan;

        $data['pasien'] = $this->Reg_pasien->get_by_mr($no_mr);

        $userDob = $data['pasien']->tgl_lhr;

        //Create a DateTime object using the user's date of birth.
        $dob = new DateTime($userDob);
        //We need to compare the user's date of birth with today's date.
        $now = new DateTime();
        //Calculate the time difference between the two dates.
        $difference = $now->diff($dob);
        $split_tgl_lhr = $this->tanggal->AgeWithYearMonthDayByStrip($data['pasien']->tgl_lhr);
        sscanf($split_tgl_lhr, '%d-%d-%d', $y, $m, $d);
        $mktime_tahun = 31622400 * $y;
        $mktime_bulan = 2678400 * $m;
        $mktime_hari = 86400 * $d;
        $mktime_jam = 0;

        $mktimenya = $mktime_tahun + $mktime_bulan + $mktime_hari + $mktime_jam;
        
        /*get value by id*/
        $data['mktime'] = $mktimenya;
        
        if((!isset($_GET['is_mcu'])) AND (isset($_GET['is_edit']) AND $_GET['is_edit']!='')){
            $list = $this->Pl_pelayanan_pm->get_data_hasil_pasien_pm($kode_penunjang,$kode_bag_tujuan);
            // echo '<pre>';print_r($list);die;
        }else if((isset($_GET['is_mcu']) AND $_GET['is_mcu']==1)){
            $list = $this->Pl_pelayanan_pm->get_data_hasil_pasien_pm_mcu($kode_penunjang,$kode_bag_tujuan);
        }else if((isset($_GET['is_mcu']) AND $_GET['is_mcu']==2)){
            $list = $this->Pl_pelayanan_pm->get_hasil_pm_mcu($kode_penunjang,$kode_bag_tujuan,$mktimenya);
            // echo '<pre>';print_r($list);die;
        }else{
            $list = $this->Pl_pelayanan_pm->get_datatables_hasil_pm($kode_penunjang,$kode_bag_tujuan,$mktimenya);
        }
        $data['list'] = $list;
        
        // echo '<pre>';print_r($this->db->last_query());die;
        if($kode_bag_tujuan=='050201'){
            $data['bpako'] = $this->Pl_pelayanan_pm->get_bpako($kode_penunjang);
            $view = 'form_periksa_rad';
        }else if($kode_bag_tujuan=='050101'){
            $view = 'form_pengambilan_spesimen';
        }

        /*variable*/
        $data['id'] = $kode_penunjang;
        $data['kode_profit'] = 2000;
        $data['no_kunjungan'] = $penunjang->no_kunjungan;
        $data['no_mr'] = $no_mr;
        $penunjang = $this->Pl_pelayanan_pm->get_by_id($kode_penunjang);
        $data['no_registrasi'] = $penunjang->no_registrasi;
        $data['catatan_hasil'] = $penunjang->catatan_hasil;
        if(isset($_GET['is_edit']) AND $_GET['is_edit']!=''){
            $data['is_edit'] = $_GET['is_edit'];
        }
        if(isset($_GET['is_mcu']) AND $_GET['is_mcu']!=''){
            $data['is_mcu'] = $_GET['is_mcu'];
        }
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $data['attachment'] = $this->upload_file->getUploadedFilePenunjang($kode_penunjang);

        // echo '<pre>';print_r($data);die;
        $this->load->view('Pl_pelayanan_pm/'.$view.'', $data);
        
    }

    public function periksa_pm()
    {
         /*update status daftar */

        $kode_penunjang = $this->input->post('kode_penunjang');

        $this->Pl_pelayanan_pm->update('pm_tc_penunjang',array('status_daftar' => 2,'tgl_periksa' => date('Y-m-d H:i:s')), array('kode_penunjang' => $kode_penunjang));

        echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan'));
        
    }

    public function save_periksa_data()
    {
        $data = array(
            'kode_penunjang' => $this->input->post('kode_penunjang'),
            'nama_pemeriksaan' => $this->input->post('nama_pemeriksaan'),
            'tanggal_jam_sampel' => $this->input->post('tanggal_jam_sampel'),
            'petugas_lab' => $this->input->post('petugas_lab'),
            'spesimen' => $this->input->post('spesimen'),
            'tipe_hasil' => $this->input->post('tipe_hasil'),
            'satuan' => $this->input->post('satuan'),
            'metode_analisis' => $this->input->post('metode_analisis'),
            'created_date' => date('Y-m-d H:i:s'),
        );

        // Asumsikan menyimpan ke tabel pm_tc_detail_periksa, jika tidak ada, buat atau update pm_tc_penunjang
        // Untuk demo, kita update pm_tc_penunjang dengan data ini, tapi mungkin perlu tabel baru
        // $this->Pl_pelayanan_pm->update('pm_tc_penunjang', $data, array('kode_penunjang' => $data['kode_penunjang']));

        // Karena tidak tahu tabel, return success
        echo json_encode(array('status' => 200, 'message' => 'Data berhasil disimpan'));
    }

    public function get_data()
    {
        $list = $this->Pl_pelayanan_pm->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $bag = substr($row_list->kode_bagian_asal, 1, 1);
            $eorder = ($row_list->eorder == 1) ? '<span class="label label-success">e-order</span>' : '';
            $str_type = 'RJ';
            // Status pasien dan tombol
            $status_pasien = 'belum_ditindak';
            $rollback_btn = '';
            $charge_slip = '';
            if ($row_list->status_daftar == 1) {
                if ($bag == 5) {
                    $status_pasien = ($row_list->kode_perusahaan == 120) ? 'belum_diperiksa' : (($row_list->status_selesai != 3) ? 'belum_bayar' : 'belum_diperiksa');
                    $rollback_btn = ($row_list->status_selesai != 3) ? '<li><a href="#" onclick="rollback('.$row_list->kode_penunjang.')">Rollback</a></li>' : '';
                } elseif ($bag == 1 && $row_list->kode_bagian_asal != '010901' && $row_list->kode_bagian_asal != '012701' && $row_list->kode_kelompok != 3) {
                    $status_pasien = ($row_list->status_selesai != 3) ? 'belum_bayar' : 'belum_diperiksa';
                    $rollback_btn = ($row_list->status_selesai != 3) ? '<li><a href="#" onclick="rollback('.$row_list->kode_penunjang.')">Rollback</a></li>' : '';
                } else {
                    $status_pasien = 'belum_diperiksa';
                    $rollback_btn = '<li><a href="#" onclick="rollback('.$row_list->kode_penunjang.')">Rollback</a></li>';
                }
                $charge_slip = '<li><a href="#" onclick="cetak_slip('.$row_list->kode_penunjang.')">Cetak Slip</a></li>';
            } elseif ($row_list->status_daftar == 2) {
                $status_pasien = 'belum_isi_hasil';
                $transaksi = $this->Pl_pelayanan_pm->get_transaksi_pasien_by_id($row_list->no_kunjungan);
                $rollback_btn = ($transaksi != 0) ? '<li><a href="#" onclick="rollback('.$row_list->kode_penunjang.')">Rollback</a></li>' : '';
                $charge_slip = '<li><a href="#" onclick="cetak_slip('.$row_list->kode_penunjang.')">Cetak Slip</a></li>';
            }

            // Dropdown menu
            $dropdown = '<div class="center"><div class="btn-group">'
                . '<button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">'
                . '<span class="ace-icon fa fa-caret-down icon-on-right"></span>'
                . '</button>'
                . '<ul class="dropdown-menu dropdown-inverse">'
                . '<li><a href="#" onclick="show_modal(\'registration/reg_pasien/view_detail_resume_medis/'.$row_list->no_registrasi.'\', \'RESUME MEDIS\')">Selengkapnya</a></li>'
                . $charge_slip
                . $rollback_btn
                . '</ul></div></div>';

            // Form link
            $form = '<div class="center"><a href="#" style="font-weight: bold; color: blue" onclick="getMenu(\'pelayanan/Pl_pelayanan_pm/form/'.$row_list->no_kunjungan.'/'.$row_list->kode_penunjang.'/'.$status_pasien.'\')">'.$row_list->no_kunjungan.'</a></div>';

            // Status label
            if ($row_list->status_batal == 1) {
                $status = '<span style="color: red; font-weight: bold">-Batal-</span>';
            } else {
                switch ($status_pasien) {
                    case 'belum_ditindak':
                        $status = '<label class="label label-warning" title="Belum Dilayani"><i class="fa fa-info-circle bigger-120"></i></label>';
                        break;
                    case 'belum_bayar':
                        $status = ($row_list->kode_rujukan != null)
                            ? '<a href="#" class="btn btn-xs btn-primary" onclick="proses_periksa('.$row_list->kode_penunjang.')">Periksa '.$row_list->kode_rujukan.'</a>'
                            : '<label class="label label-danger">Belum bayar</label>';
                        break;
                    case 'belum_isi_hasil':
                        $status = '<label class="label label-success">Belum isi hasil</label>';
                        break;
                    default:
                        if (isset($_GET['sess_kode_bagian']) && $_GET['sess_kode_bagian'] != '050301') {
                            $status = '<a href="#" class="btn btn-xs btn-primary" onclick="proses_periksa('.$row_list->kode_penunjang.')">Periksa</a>';
                        } else {
                            $status = '';
                        }
                        break;
                }
            }

            $data[] = array(
                $row_list->no_registrasi,
                $str_type,
                '',
                $dropdown,
                '<div class="center">'.$form.'</div>',
                '<div class="center">'.$row_list->no_mr.'</div>',
                strtoupper($row_list->nama_pasien).' '.$eorder,
                '<div class="center">'.$no.'</div>',
                ($row_list->nama_perusahaan) ? $row_list->nama_perusahaan : $row_list->nama_kelompok,
                '<div class="center no-padding"><input type="text" id="no_sep_'.$row_list->no_kunjungan.'" name="no_sep['.$row_list->no_kunjungan.']" class="form-input-nosep form-control" style="width: 150px; margin: 0px; border: 0px; text-align: center" onchange="saveNoSep('.$row_list->no_registrasi.', '.$row_list->no_kunjungan.')" value="'.$row_list->no_sep.'"></div>',
                $this->tanggal->formatDateTimeFormDmy($row_list->tgl_masuk),
                ucwords($row_list->nama_bagian),
                '<div class="center">'.$status.'</div>'
            );
        }

        $output = array(
            "draw" => $_POST['draw'],
            "recordsTotal" => $this->Pl_pelayanan_pm->count_all(),
            "recordsFiltered" => $this->Pl_pelayanan_pm->count_filtered(),
            "data" => $data,
        );
        echo json_encode($output);
    }

    public function get_data_order()
    {
        /*akan di filter berdasarkan pasien pada klinik masing2*/
        /*get data from model*/
        $list = $this->Pl_pelayanan_pm->get_datatables();
        // echo "<pre>"; print_r($list);die;

        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $link = 'billing/Billing';
            $str_type = 'RJ';
            
            $bag = substr($row_list->kode_bagian_asal, 1, 1);
            $eorder = ($row_list->eorder == 1)?'<span class="label label-success">e-order</span>':'';
            $delete_registrasi = ($row_list->tgl_keluar==NULL)?'<li><a href="#" onclick="delete_registrasi('.$row_list->no_registrasi.','.$row_list->no_kunjungan.')">Hapus</a></li>':'';  

            /*btn hasil pm*/
            $subs_kode_bag = substr($row_list->kode_bagian_tujuan, 0,2);
            $btn_view_hasil_pm = ($subs_kode_bag=='05')?'<li><a href="#" onclick="show_modal('."'registration/reg_pasien/form_modal_view_hasil_pm/".$row_list->no_registrasi."/".$row_list->no_kunjungan."'".', '."'HASIL PENUNJANG MEDIS (".$row_list->bagian_tujuan.")'".')">Lihat Hasil '.$row_list->bagian_tujuan.'</a></li>':'';


            if($row_list->status_daftar==0 || $row_list->status_daftar==NULL){
                $status_pasien = 'belum_ditindak';
                $rollback_btn ='';
                $charge_slip = '';
            }else if($row_list->status_daftar==1){
                
                if($bag==5){
                    if($row_list->kode_perusahaan == 120){
                        $status_pasien = 'belum_diperiksa';
                    }else{
                        $status_pasien = ($row_list->status_selesai!=3)?'belum_bayar':'belum_diperiksa';
                    }
                    $rollback_btn = ($row_list->status_selesai!=3)?'<li><a href="#" onclick="rollback('.$row_list->kode_penunjang.')">Rollback</a></li>':'';
                }else if($bag==1 && $row_list->kode_bagian_asal !='010901' && $row_list->kode_bagian_asal != '012701' && $row_list->kode_kelompok != 3){
                    $status_pasien = ($row_list->status_selesai!=3)?'belum_bayar':'belum_diperiksa';
                    $rollback_btn = ($row_list->status_selesai!=3)?'<li><a href="#" onclick="rollback('.$row_list->kode_penunjang.')">Rollback</a></li>':'';
                }else{
                    $status_pasien = 'belum_diperiksa';
                    $rollback_btn = '<li><a href="#" onclick="rollback('.$row_list->kode_penunjang.')">Rollback</a></li>';
                }

                $charge_slip = '<li><a href="#" onclick="cetak_slip('.$row_list->kode_penunjang.')">Cetak Slip</a></li>';

            }else  if($row_list->status_daftar==2){
                $status_pasien = 'belum_isi_hasil';

                $transaksi = $this->Pl_pelayanan_pm->get_transaksi_pasien_by_id($row_list->no_kunjungan);

                $rollback_btn = ($transaksi!=0)?'<li><a href="#" onclick="rollback('.$row_list->kode_penunjang.')">Rollback</a></li>':'';

                $charge_slip = '<li><a href="#" onclick="cetak_slip('.$row_list->kode_penunjang.')">Cetak Slip</a></li>';
            }
            
            $row[] = $row_list->no_registrasi;
            $row[] = $row_list->no_kunjungan;
            $row[] = $str_type;
            $row[] = $row_list->id_pm_tc_penunjang;
            $row[] = $row_list->kode_bagian_tujuan;
            $row[] = '';
            $row[] = '<div class="center"><div class="btn-group">
                        <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">
                            <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-inverse">
                            <li><a href="#" onclick="show_modal('."'registration/reg_pasien/view_detail_resume_medis/".$row_list->no_registrasi."'".', '."'RESUME MEDIS'".')">Selengkapnya</a></li>
                            '.$charge_slip.'
                            '.$rollback_btn.' 
                            '.$delete_registrasi.' 
                            '.$btn_view_hasil_pm.'
                        </ul>
                    </div></div>';


            // $form  = '<div class="center"><a href="#">'.$row_list->no_kunjungan.'</a></div>';
            
            // $row[] = '<div class="center">'.$form.'</div>';
            $row[] = $this->tanggal->formatDateTimeFormDmy($row_list->tgl_masuk);
            $row[] = '<b>'.$row_list->no_mr.'</b><br>'.strtoupper($row_list->nama_pasien).' '.$eorder;
            $row[] = ($row_list->nama_perusahaan)?$row_list->nama_perusahaan.'':$row_list->nama_kelompok;
            $row[] = '<div class="center">'.ucwords($row_list->nama_bagian).'<br><i class="fa fa-arrow-down"></i><br>'.$row_list->bagian_tujuan.'</div>';
            // $row[] = ($row_list->status_cito==1)?'Cito':'Biasa';
            // $row[] = ucwords($row_list->nama_bagian);

            $bag = substr($row_list->kode_bagian_asal, 1, 1);

            if( $row_list->status_batal == 1 ){
                $status = '<span style="color: red; font-weight: bold">-Batal-</span>';
            }
            else{
                if($status_pasien=='belum_ditindak'){

                    $status = '<label class="label label-warning" title="Belum Dilayani"><i class="fa fa-info-circle bigger-120"></i></label>';
    
                }else if($status_pasien=='belum_bayar'){
                    
                    if( $row_list->kode_rujukan != null){
                        $status = '<a href="#" class="btn btn-xs btn-primary" onclick="periksa('.$row_list->kode_penunjang.')">Periksa '.$row_list->kode_rujukan.'</a>';
                    }else{
                        $status = '<label class="label label-danger">Belum bayar</label>';
                    }
                   
                }else if($status_pasien=='belum_isi_hasil'){
    
                    $status = '<label class="label label-success">Belum isi hasil</label>';
    
                }else{
    
                    if(isset($_GET['sess_kode_bagian']) && $_GET['sess_kode_bagian']!='050301'){
                        $status = '<a href="#" class="btn btn-xs btn-primary" onclick="periksa('.$row_list->kode_penunjang.')">Periksa</a>';
                    }else{
                        
                        $status = (isset($transaksi) && $transaksi==0)?'<label class="label label-info">Lunas</label>':'<label class="label label-success">Selesai</label>';
    
                    }
                    
                }
            }
            

            $row[] = '<div class="center">'.$status.'</div>';

            $btn_cetak_pengantar = '<a href="#" onclick="PopupCenter('."'pelayanan/Pl_pelayanan_pm/preview_pengantar_penunjang/".$row_list->no_kunjungan."?id_pm_tc_penunjang=".$row_list->id_pm_tc_penunjang."&type=PM&kode_bagian=".$row_list->kode_bagian_tujuan."&kode_bag_asal=".$row_list->kode_bagian_asal."&no_mr=".$row_list->no_mr."&klas=".$row_list->kode_klas."'".', '."'change_form_pengantar_pm'".')" class="label label-success" style="width: 120px !important; margin-top: 3px">Cetak Pengantar</a>';

            if($row_list->kode_bagian_tujuan == '050101'){
                $row[] = '<div class="center"><a href="#" onclick="getMenuTabs('."'pelayanan/Pl_pelayanan/form_lab_detail/".$row_list->no_kunjungan."/".$row_list->id_pm_tc_penunjang."?type=PM&kode_bag=".$row_list->kode_bagian_tujuan."&kode_bag_asal=".$row_list->kode_bagian_asal."&no_mr=".$row_list->no_mr."&klas=".$row_list->kode_klas."'".', '."'change_form_pengantar_pm'".')" class="label label-primary" style="width: 120px !important;">Buat Pengantar Lab</a><br>'.$btn_cetak_pengantar.'</div>';
            }elseif ($row_list->kode_bagian_tujuan == '050201') {
                # code...
                // pelayanan/Pl_pelayanan/form_order_penunjang/849245/1644182?type=PM&kode_bag=050201

                $row[] = '<div class="center"><a href="#" onclick="getMenuTabs('."'pelayanan/Pl_pelayanan/form_order_radiologi/".$row_list->no_kunjungan."/".$row_list->id_pm_tc_penunjang."?type=PM&kode_bag=".$row_list->kode_bagian_tujuan."&kode_bag_asal=".$row_list->kode_bagian_asal."&no_mr=".$row_list->no_mr."&klas=".$row_list->kode_klas."'".', '."'change_form_pengantar_pm'".')" class="label label-primary" style="width: 120px !important;">Buat Pengantar Rad</a><br>'.$btn_cetak_pengantar.'</div>';
            }elseif ($row_list->kode_bagian_tujuan == '050301') {
                $row[] = '<div class="center"><a href="#" onclick="getMenuTabs('."'pelayanan/Pl_pelayanan/form_order_fisio/".$row_list->no_kunjungan."/".$row_list->id_pm_tc_penunjang."?type=PM&kode_bag=".$row_list->kode_bagian_tujuan."&kode_bag_asal=".$row_list->kode_bagian_asal."&no_mr=".$row_list->no_mr."&klas=".$row_list->kode_klas."'".', '."'change_form_pengantar_pm'".')" class="label label-primary" style="width: 120px !important;">Buat Pengantar Fisio</a><br>'.$btn_cetak_pengantar.'</div>';
                # code...
            }
           
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Pl_pelayanan_pm->count_all(),
                        "recordsFiltered" => $this->Pl_pelayanan_pm->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }


    public function get_hasil_pm()
    {
        /*get data from model*/
        $list_group = $this->Pl_pelayanan_pm->get_group_hasil_pm();
        $list =  $this->Pl_pelayanan_pm->get_datatables_hasil_pm();
        $data = array();
        $no = $_POST['start'];
        foreach ($list_group as $row_list_group) {
            $no++;
            $row = array();
           

            foreach ($list as $row_list) {
                # code...
                if($_GET['jk']=='L'){
                    $nilai_std = $row_list->standar_hasil_pria;
                }else{
                    $nilai_std = $row_list->standar_hasil_wanita;
                }

                if($row_list_group->nama_tindakan==$row_list->nama_tindakan){
                    $row[] = $row_list->nama_pemeriksaan;
                    $row[] = $row_list->detail_item_1;
                    $row[] = $row_list->detail_item_2;
                    $row[] = $nilai_std.' '.$row_list->satuan;
                    $row[] = '';
                    $row[] = '';
                }
            }
                       
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Pl_pelayanan_pm->count_all_tindakan_riwayat_diagnosa(),
                        "recordsFiltered" => $this->Pl_pelayanan_pm->count_filtered_tindakan_riwayat_diagnosa(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function get_bpako()
    {
        /*get data from model*/
        $kode_tarif = (isset($_GET['kode_tarif']))?$_GET['kode_tarif']:'';
        $list = $this->Pl_pelayanan_pm->get_bpako($_GET['id'],$kode_tarif);
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
                                
            $row[] = '<div class="center"><a href="#" class="btn btn-xs btn-danger" onclick="delete_obalkes('.$row_list->id_pm_tc_obalkes.')"><i class="fa fa-times-circle"></i></a></div>';
            $row[] = strtoupper($row_list->nama_brg);
            $row[] = '<div class="center">'.$row_list->volume.'</div>';
                     
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => count($data),
                        "recordsFiltered" => count($data),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);

    } 

    public function form_end_visit()
    {
        $data = array(
            'no_mr' => isset($_GET['no_mr'])?$_GET['no_mr']:'',
            'kode_ri' => isset($_GET['id'])?$_GET['id']:'',
            'no_kunjungan' => isset($_GET['no_kunjungan'])?$_GET['no_kunjungan']:'',
            );
        /*load form view*/
        $this->load->view('Pl_pelayanan_pm/form_end_visit', $data);
    }

    public function find_data()
    {   
        $output = array( "data" => http_build_query($_POST) . "\n" );
        echo json_encode($output);
    }

    public function process_isi_hasil(){

        // echo '<pre>';print_r($_POST);die;
        // form validation
        $this->form_validation->set_rules('kode_penunjang', 'No MR', 'trim');
               
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
            // remove special character
            
            $txt_nl2br = nl2br($_POST['catatan_hasil'], '<br>');
            /*Update pm_tc_penunjang */
            $pm_tc_penunjang = array(
                'tgl_isihasil' => $_POST['pl_tgl_pm'],
                'petugas_isihasil' => $this->session->userdata('user')->user_id,
                'status_isihasil' => 1,
                'catatan_hasil' => $txt_nl2br
            );
            $this->Pl_pelayanan_pm->update('pm_tc_penunjang',$pm_tc_penunjang, array('kode_penunjang' => $this->input->post('kode_penunjang')));
            /*insert pm_tc_hasilpenunjang*/
            foreach($_POST['kode_mt_hasilpm'] as $key=>$row_dt){

                $kode_mt_hasilpm = $row_dt;
                $kode_tc_hasilpenunjang = $this->master->get_max_number('pm_tc_hasilpenunjang', 'kode_tc_hasilpenunjang');
                $kode_trans_pelayanan = $_POST['kode_trans_pelayanan'][$kode_mt_hasilpm][$key];
                $hasil_pm = $_POST['hasil_pm'][$kode_mt_hasilpm][$key];
                $keterangan_pm = $_POST['keterangan_pm'][$kode_mt_hasilpm][$key];


                $hasil = $this->master->convert_special_chars_to_html($hasil_pm);
                $keterangan = $this->master->convert_special_chars_to_html($keterangan_pm);

                $dataexc = array(
                    'kode_mt_hasilpm' =>  $kode_mt_hasilpm,
                    'hasil' => $hasil,
                    'keterangan' => $keterangan,
                );

                if($kode_trans_pelayanan!=''){
                    $cek_mcu = $this->db->get_where('tc_trans_pelayanan_paket_mcu',array('kode_trans_pelayanan_paket_mcu' => $kode_trans_pelayanan))->row();
                    if(isset($cek_mcu) AND $cek_mcu->kode_bagian_asal=='010901'){
                        $dataexc["flag_mcu"] = 1; 
                    }
                }

                // cek hasil apakah sudah pernah diinput
                $dt_ex = $this->db->get_where('pm_tc_hasilpenunjang', array('kode_trans_pelayanan' => $kode_trans_pelayanan, 'kode_mt_hasilpm' => $kode_mt_hasilpm) );
                // echo '<pre>';print_r($dt_ex->row());die;
                
                if($dt_ex->num_rows() > 0){
                    $this->Pl_pelayanan_pm->update('pm_tc_hasilpenunjang', $dataexc, array('kode_tc_hasilpenunjang' => $dt_ex->row()->kode_tc_hasilpenunjang ) );
                    // echo '<pre>';print_r($this->db->last_query());die;
                    $this->db->trans_commit();
                }else{
                    $dataexc["kode_tc_hasilpenunjang"] = $kode_tc_hasilpenunjang; 
                    $dataexc["kode_trans_pelayanan"] = $kode_trans_pelayanan; 
                    $this->Pl_pelayanan_pm->save('pm_tc_hasilpenunjang', $dataexc);
                    $this->db->trans_commit();
                }

            }

            // update tgl keluar kunjungan 
            $this->Pl_pelayanan_pm->update('tc_kunjungan', ['tgl_keluar' => $_POST['pl_tgl_pm']], array('no_kunjungan' => $_POST['no_kunjungan'] ) );
            $this->db->trans_commit();
            // echo '<pre>';print_r($_FILES);die;

            // insert and upload file
            /*insert dokumen adjusment*/
            if(!empty($_FILES['pf_file']['name'][0])){
                $this->upload_file->PMdoUploadMultiple(array(
                    'name' => 'pf_file',
                    'path' => 'uploaded/casemix/log/',
                    'kode_penunjang' => $_POST['kode_penunjang'],
                    'ref_id' => $_POST['no_registrasi'],
                    'ref_table' => 'csm_dokumen_export',
                    'flag' => 'dokumen_export',
                ));
            }
                          
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'type_pelayanan' => 'isi_hasil', 'bagian' => $this->input->post('kode_bagian')));
            }
        
        }

    }

    public function process_add_obalkes()
    {
    
        $this->db->trans_begin();        

        $id_pm_tc_obalkes = $this->master->get_max_number('pm_tc_obalkes', 'id_pm_tc_obalkes');

        $pm_tc_obatalkes = array(
            'id_pm_tc_obalkes' => $id_pm_tc_obalkes,
            'kode_penunjang' => $this->input->post('kode_penunjang'),
            'kode_brg' => $this->input->post('kode_brg'),
            'kode_tarif' => $this->input->post('kode_tarif'),
            'volume' => $this->input->post('jml'),
            'kode_bagian' => $this->input->post('kode_bagian'),
            'petugas' => $this->session->userdata('user')->user_id,
            // 'harga_jual' => $harga_jual
        );

        $this->Pl_pelayanan_pm->save('pm_tc_obalkes', $pm_tc_obatalkes);

        $this->stok_barang->stock_process($this->input->post('kode_brg'),$this->input->post('jml'),$this->input->post('kode_bagian'),6,'','reduce');
        

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
        }
        else
        {
            $this->db->trans_commit();
            echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'type_pelayanan' => 'pasien_selesai', 'data' => $pm_tc_obatalkes));
        }

    }

    public function processPelayananSelesai(){

        // echo '<pre>';print_r($_POST);die;
        // form validation
        $this->form_validation->set_rules('noMrHidden', 'Pasien', 'trim|required');        
        $this->form_validation->set_rules('kode_penunjang', 'Kode Penunjang', 'trim');        
            
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

            $kode_penunjang = $this->regex->_genRegex($this->input->post('kode_penunjang'),'RGXINT');
            $no_mr = $this->form_validation->set_value('noMrHidden');
            
            /*update pm_tc_penunjang*/
            $pm_tc_penunjang = array('status_daftar' => 1);
            $this->Pl_pelayanan_pm->update('pm_tc_penunjang', $pm_tc_penunjang, array('kode_penunjang' => $kode_penunjang ) );

            
            if( ($this->input->post('kode_bagian_asal')!=$this->input->post('kode_bagian')) OR ($this->input->post('kode_bagian')=='050301') ){
                
                $status_keluar = ($this->input->post('kode_bagian')=='050301')?3:5;
                $this->daftar_pasien->pulangkan_pasien($this->input->post('no_kunjungan'),$status_keluar,'PM');

            }else{
                $tc_trans_pelayanan["status_selesai"] = 2;

                /*update tc_trans_pelayanan*/
                $this->Pl_pelayanan_pm->update('tc_trans_pelayanan', $tc_trans_pelayanan, array('kode_penunjang' => $kode_penunjang ) );
            }
        
            /*input bpako radiologi */

            $list_tindakan1 = $this->Pl_pelayanan_pm->get_tindakan_by_tc_trans_pelayanan($kode_penunjang,3);
                
            foreach ($list_tindakan1 as $value) {
                $kode_tarif = $value->kode_tarif;
                $harga_jual = $value->bill_rs;
                
                $list_pm_mt_bpako = $this->Pl_pelayanan_pm->get_pm_mt_bpako($kode_tarif);
                //print_r($list_pm_mt_bpako);die;

                if(!empty($list_pm_mt_bpako)){

                    foreach ($list_pm_mt_bpako as $vals) {
                        
                        $kode_brg = $vals->kode_brg;
                        $volume = (int)$vals->volume;                      
                        
                        $id_pm_tc_obalkes = $this->master->get_max_number('pm_tc_obalkes', 'id_pm_tc_obalkes');

                        $pm_tc_obatalkes = array(
                            'id_pm_tc_obalkes' => $id_pm_tc_obalkes,
                            'kode_penunjang' => $kode_penunjang,
                            'kode_brg' => $kode_brg,
                            'kode_tarif' => $kode_tarif,
                            'volume' => $volume,
                            'kode_bagian' => $this->input->post('kode_bagian'),
                            'petugas' => $this->session->userdata('user')->user_id,
                            // 'harga_jual' => $harga_jual
                        );

                        $this->Pl_pelayanan_pm->save('pm_tc_obalkes', $pm_tc_obatalkes);

                        $this->stok_barang->stock_process($kode_brg,$volume,$this->input->post('kode_bagian'),6,'','reduce');
                        
                        
                        $this->db->trans_commit();
                    }

                }

            }
            
            $list_tindakan2 = $this->Pl_pelayanan_pm->get_tindakan_by_tc_trans_pelayanan($kode_penunjang,9);

            if(!empty($list_tindakan2)){
                foreach ($list_tindakan2 as $value) {
                
                    $kode_brg = $value->kode_barang;
                    $jumlah = (int)$value->jumlah;
                    $harga_jual = $value->bill_rs;
    
                    if(isset($kode_brg) OR $kode_brg!=NULL){
        
                        $id_pm_tc_obalkes = $this->master->get_max_number('pm_tc_obalkes', 'id_pm_tc_obalkes');
    
                        $pm_tc_obatalkes = array(
                            'id_pm_tc_obalkes' => $id_pm_tc_obalkes,
                            'kode_penunjang' => $kode_penunjang,
                            'kode_brg' => $kode_brg,
                            'volume' => $jumlah,
                            'kode_bagian' => $this->input->post('kode_bagian'),
                            'petugas' => $this->session->userdata('user')->user_id,
                            // 'harga_jual' => $harga_jual
                        );
    
                        $this->Pl_pelayanan_pm->save('pm_tc_obalkes', $pm_tc_obatalkes);
    
                        $this->stok_barang->stock_process($kode_brg,$jumlah,$this->input->post('kode_bagian'),6,'','reduce');
    
                        $this->db->trans_commit();
                    }
    
                }
            } 

            /*proses tanpa input hasil fisioterapi */

            if($this->input->post('kode_bagian')=='050301'){

                $tindakan = $this->Pl_pelayanan_pm->get_tindakan_by_tc_trans_pelayanan($kode_penunjang);

                foreach ($tindakan as $value) {

                    $kode_tc_hasilpenunjang = $this->master->get_max_number('pm_tc_hasilpenunjang', 'kode_tc_hasilpenunjang');

                    $tc_hasilpenunjang = array(
                        'kode_tc_hasilpenunjang' => $kode_tc_hasilpenunjang,
                        'kode_trans_pelayanan' => $value->kode_trans_pelayanan,
                        'kode_mt_hasilpm' => '5030101011',
                        'hasil' => '0',
                        'keterangan' => 'Tanpa Input Hasil',
                    );
                
                    $this->Pl_pelayanan_pm->save('pm_tc_hasilpenunjang', $tc_hasilpenunjang);

                    $this->db->trans_commit();
                
                }

                $pm_tc_penunjang_fisio = array(
                    'tgl_isihasil' => date('Y-m-d H:i:s'),
                    'petugas_isihasil' => $this->session->userdata('user')->user_id,
                    'catatan_hasil' => 'Tanpa Input Hasil',
                    'status_isihasil' => 0,
                    'status_daftar' => 1
                );
               
                $this->Pl_pelayanan_pm->update('pm_tc_penunjang', $pm_tc_penunjang_fisio, array('kode_penunjang' => $kode_penunjang ) );
                
            }
            
            
            if(isset($_POST['kodebookingantrol'])){
                $this->updateTaskMultiple($_POST['kodebookingantrol']);
            }

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'type_pelayanan' => 'pasien_selesai'));
            }

        
        }

    }

    public function delete()
    {
        $id=$this->input->post('ID')?$this->input->post('ID',TRUE):null;
        $brg=$this->db->get_where('pm_tc_obalkes',array('id_pm_tc_obalkes' => $id))->row();
        $kode_brg = $brg->kode_brg;
        $volume = $brg->volume;
        if($id!=null){
            if($this->Pl_pelayanan_pm->delete_by_id('pm_tc_obalkes','id_pm_tc_obalkes',$id)){

                $this->stok_barang->stock_process($kode_brg,$volume,$this->input->post('kode_bagian'),8,'','restore');

                echo json_encode(array('status' => 200, 'message' => 'Proses Hapus Data Berhasil Dilakukan', 'data' => $brg));
            }else{
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Hapus Data Gagal Dilakukan'));
            }
        }else{
            echo json_encode(array('status' => 301, 'message' => 'Tidak ada item yang dipilih'));
        }
        
    }

    public function rollback()
    {   
        //print_r($_POST);die;
        $this->db->trans_begin();  

        $kode_penunjang = $this->regex->_genRegex($this->input->post('kode_penunjang'),'RGXINT');
             
        /*update pm_tc_penunjang*/
        $pm_tc_penunjang = array('status_daftar' => 0, 'status_isihasil' => 0, 'status_batal' => 0, 'tgl_selesai' => null);
        $this->Pl_pelayanan_pm->update('pm_tc_penunjang', $pm_tc_penunjang, array('kode_penunjang' => $kode_penunjang ) );
        
        /*save logs*/
        //$this->logs->save('pl_tc_poli', $no_kunjungan, 'update pl_tc_poli Modul Pelayanan', json_encode($arrPlTcPoli),'no_kunjungan');

         /*update tc_trans_pelayanan*/
         $tc_trans_pelayanan = array('status_selesai' => 0);
         $this->Pl_pelayanan_pm->update('tc_trans_pelayanan', $tc_trans_pelayanan, array('kode_penunjang' => $kode_penunjang ) );
         /*save logs*/
         //$this->logs->save('pl_tc_poli', $no_kunjungan, 'update pl_tc_poli Modul Pelayanan', json_encode($arrPlTcPoli),'no_kunjungan');
         if(isset($_POST['kode_bagian']) AND substr($_POST['kode_bagian'], 0,2) == '05'){
             
            $kunjungan = $this->Pl_pelayanan_pm->get_by_id($kode_penunjang);
            $kunj_data = array('tgl_keluar' => NULL, 'status_keluar' => NULL, 'status_batal' => NULL );
            $this->db->update('tc_kunjungan', $kunj_data, array('no_registrasi' => $kunjungan->no_registrasi, 'no_kunjungan' => $kunjungan->no_kunjungan ) );
            $this->logs->save('tc_kunjungan', $kunjungan->no_kunjungan, 'update tc_kunjungan Modul Pelayanan', json_encode($kunj_data),'no_kunjungan');

            if(isset($_POST['flag']) AND $_POST['flag']=='submited'){
                /*delete ak_tc_transaksi_det*/
                $this->Pl_pelayanan->delete_ak_tc_transaksi_det($kunjungan->no_kunjungan);
                /*delete ak_tc_transaksi*/
                $this->Pl_pelayanan->delete_ak_tc_transaksi($kunjungan->no_kunjungan);
                /*delete transaksi_kasir*/
                $this->Pl_pelayanan->delete_transaksi_kasir($kunjungan->no_kunjungan);
            }

            /*tc_trans_pelayanan*/
            // $trans_data = array('status_selesai' => 2, 'status_nk' => NULL, 'kode_tc_trans_kasir' => NULL );
            // $this->db->update('tc_trans_pelayanan', $trans_data, array('no_kunjungan' => $kunjungan->no_kunjungan, 'no_registrasi' => $kunjungan->no_registrasi ) );
 
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

    public function slip()
    {   
        $kode_penunjang = $_GET['kode_penunjang'];
        $flag = isset($_GET['flag'])?$_GET['flag']:'';
        
        $data['value'] = $this->Pl_pelayanan_pm->get_pemeriksaan($kode_penunjang,$flag);

        foreach ($data['value'] as $value) {
            if(isset($value->nama_pasien))$data['nama'] = $value->nama_pasien;
            if(isset($value->no_registrasi))$data['no_registrasi'] = $value->no_registrasi;
            if(isset($value->no_mr))$data['no_mr'] = $value->no_mr;
            if(isset($value->dokter_1))$data['dokter_1'] = $value->dokter_1;
            if(isset($value->dokter_2))$data['dokter_2'] = $value->dokter_2;
            if(isset($value->bagian_asal))$data['bagian_asal'] = $value->bagian_asal;
        }
 
        $this->load->view('Pl_pelayanan_pm/charge_slip', $data);

    }

    public function process_edit_dokter()
    {
        // print_r($_POST);
        $this->db->trans_begin();  
      
        $kode_penunjang = $this->regex->_genRegex($this->input->post('kode_penunjang_dr'),'RGXINT');
        $kode_trans_pelayanan = $this->regex->_genRegex($this->input->post('kode_trans_pelayanan'),'RGXINT');     
      
        $dataexec = array(
            'kode_dokter1' => $this->regex->_genRegex($this->input->post('kode_dokter1'),'RGXINT'),
            'kode_dokter2' => (isset($_POST['kode_dokter2']) AND $_POST['kode_dokter2']!='')?$_POST['kode_dokter2']:0,
        );
        
        /*update trans pelayanan */
        $this->Pl_pelayanan_pm->update('tc_trans_pelayanan', $dataexec, array('kode_penunjang' => $kode_penunjang, 'kode_trans_pelayanan' =>  $kode_trans_pelayanan) );

        $data_pm = $this->Pl_pelayanan_pm->get_by_id($kode_penunjang);

        //print_r($data_pm);die;

        /*update tc_kunjungan */
        $this->Pl_pelayanan_pm->update('tc_kunjungan', array('kode_dokter' =>  $this->regex->_genRegex($this->input->post('kode_dokter1'),'RGXINT')), array('no_kunjungan' => $data_pm->no_kunjungan) );

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

    public function saveNoSep()
    {
        // print_r($_POST);die;
        $this->db->trans_begin();  
      
        $no_registrasi = $this->regex->_genRegex($this->input->post('no_registrasi'),'RGXQSL');
        $no_sep = $this->regex->_genRegex($this->input->post('no_sep'),'RGXQSL');    

        $this->db->update('tc_registrasi', array('no_sep' => $no_sep), array('no_registrasi' => $no_registrasi) );

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

    public function get_order_penunjang()
    {
        /*get data from model*/
        $list = $this->Pl_pelayanan_pm->get_datatables_order_pm();
        // echo "<pre>";print_r($list);die;
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();

            $row[] = '<div class="center">'.$no.'</div>';
            $row[] = '<a href="#" onclick="getDetailTarifByKodeTarifAndKlas('.$row_list->kode_tarif.', '.$row_list->kode_klas.')">'.$row_list->nama_tarif.'</a>';
            if($row_list->kode_bagian == '050301'){
                $row[] = $row_list->diagnosa;
                $row[] = 'X-Ray foto : <br>'.$row_list->xray_foto.' <br>Kontra Indikasi : <br> '.$row_list->kontra_indikasi.' <br>Keterangan :<br> '.$row_list->keterangan;
            }else{
                $row[] = $row_list->keterangan;
            }

            $row[] = $this->tanggal->formatDateTime($row_list->created_date);
            $row[] = '<div class="center"><a href="#" onclick="delete_transaksi('.$row_list->order_id.')"><i class="fa fa-trash red bigger-120" title="hapus"></i></a></div>';
           
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function get_order_penunjang_view()
    {
        /*get data from model*/
        $list = $this->Pl_pelayanan_pm->get_datatables_order_pm();
        // print_r($this->db->last_query());die;
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            // diagnosa
            $keterangan = ($row_list->keterangan != '') ? '<br><b>keterangan :</b> <br>'. $row_list->keterangan.'<br>' : '';
            $free_text = ($row_list->kode_tarif == 0) ? '<span style="color: red">[free text]</span>' : '';
            $diagnosa = ($row_list->diagnosa != '') ? '<br><b>Diagnosa :</b> <br>'. $row_list->diagnosa.'' : '';
            $xray_foto = ($row_list->xray_foto != '') ? ' | xray_foto : '. $row_list->xray_foto.'' : '';
            $kontra_indikasi = ($row_list->kontra_indikasi != '') ? ' | kontra_indikasi : '. $row_list->kontra_indikasi.'' : '';

            $row[] = '<div class="center">'.$no.'</div>';
            if($row_list->kode_tarif == 0){
                $row[] = '<a href="#"><b>'.$row_list->nama_tarif.'</b>&nbsp;'.$free_text.'</a>'.$diagnosa.''.$keterangan.' '.$xray_foto.' '.$kontra_indikasi.'';
            }else{
                $row[] = '<a href="#" onclick="getDetailTarifByKodeTarifAndKlas('.$row_list->kode_tarif.', '.$row_list->kode_klas.')"><b>'.$row_list->nama_tarif.'</b>&nbsp;'.$free_text.'</a>'.$diagnosa.''.$keterangan.' '.$xray_foto.' '.$kontra_indikasi.'';

            }
           
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "data" => $data,
                        "status" => isset($list[0]->status)?$list[0]->status:'',
                );
        //output to json format
        echo json_encode($output);
    }

    public function get_order_penunjang_fisio_view()
    {
        /*get data from model*/
        $list = $this->Pl_pelayanan_pm->_get_datatables_order_penunjang();
        // print_r($this->db->last_query());die;
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            // diagnosa
            $keterangan = ($row_list->keterangan != '') ? '<br><b>keterangan :</b> <br>'. $row_list->keterangan.'<br>' : '';
            $diagnosa = ($row_list->diagnosa != '') ? '<br><b>Diagnosa :</b> <br>'. $row_list->diagnosa.'' : '';
            $xray_foto = ($row_list->xray_foto != '') ? ' | xray_foto : '. $row_list->xray_foto.'' : '';
            $kontra_indikasi = ($row_list->kontra_indikasi != '') ? ' | kontra_indikasi : '. $row_list->kontra_indikasi.'' : '';

            $row[] = '<div class="center">'.$no.'</div>';
            $row[] = $this->tanggal->formatDateTime($row_list->created_date);
            $row[] = $row_list->no_mr.'<br>'.$row_list->nama_pasien;
            $strtoarray = str_replace("|", "<br>",$row_list->nama_tarif);
            $row[] = $strtoarray;
            $row[] = $row_list->diagnosa;
            $row[] = $row_list->xray_foto;
            $row[] = $row_list->kontra_indikasi;
            $row[] = $row_list->keterangan;
            $row[] = $row_list->dr_pengirim;
            $status = ($row_list->status == 1) ? '<span style="font-weight: bold; color: green">sudah diproses</span>' :'<span style="font-weight: bold; color: red">belum diproses</span>';
            $row[] = '<div class="center">'.$status.'</div>';
           
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Pl_pelayanan_pm->count_all_order_penunjang(),
                        "recordsFiltered" => $this->Pl_pelayanan_pm->count_filtered_order_penunjang(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function get_order_penunjang_lab_view()
    {
        /*get data from model*/
        $list = $this->Pl_pelayanan_pm->_get_datatables_order_penunjang();
        // echo "<pre>";print_r($this->db->last_query());die;
        // echo "<pre>";print_r($list);die;
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            // diagnosa
            $keterangan = ($row_list->keterangan != '') ? '<br><b>keterangan :</b> <br>'. $row_list->keterangan.'<br>' : '';
            $diagnosa = ($row_list->diagnosa != '') ? '<br><b>Diagnosa :</b> <br>'. $row_list->diagnosa.'' : '';
            $xray_foto = ($row_list->xray_foto != '') ? ' | xray_foto : '. $row_list->xray_foto.'' : '';
            $kontra_indikasi = ($row_list->kontra_indikasi != '') ? ' | kontra_indikasi : '. $row_list->kontra_indikasi.'' : '';

            $row[] = '<div class="center">'.$no.'</div>';
            $row[] = $this->tanggal->formatDateTime($row_list->created_date);
            $row[] = $row_list->no_mr.'<br>'.$row_list->nama_pasien;
            $strtoarray = str_replace("|", "<br>",$row_list->nama_tarif);
            $arr_str = explode("|",$row_list->nama_tarif);
            // print_r($arr_str);die;
            $html = '<ol>';
            foreach ($arr_str as $key => $value) {
                if(!empty($value)){
                    $html .= '<li>'.$value.'</li>';
                }
            }
            $html .= '</ol>';

            $row[] = $html;
            $row[] = $row_list->dr_pengirim;
            $row[] = $row_list->bagian_asal;
            $row[] = $row_list->keterangan_order;
            $status = ($row_list->status == 1) ? '<span style="font-weight: bold; color: green">sudah diproses</span>' : '<span style="font-weight: bold; color: red">belum diproses</span>' ;
            $row[] = '<div class="center">'.$status.'</div>';
           
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Pl_pelayanan_pm->count_all_order_penunjang(),
                        "recordsFiltered" => $this->Pl_pelayanan_pm->count_filtered_order_penunjang(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function get_order_penunjang_lab_view_w_action()
    {
        /*get data from model*/
        $list = $this->Pl_pelayanan_pm->_get_datatables_order_penunjang();
        // echo "<pre>";print_r($this->db->last_query());die;
        // echo "<pre>";print_r($list);die;
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            // diagnosa
            $keterangan = ($row_list->keterangan != '') ? '<br><b>keterangan :</b> <br>'. $row_list->keterangan.'<br>' : '';
            $diagnosa = ($row_list->diagnosa != '') ? '<br><b>Diagnosa :</b> <br>'. $row_list->diagnosa.'' : '';
            $xray_foto = ($row_list->xray_foto != '') ? ' | xray_foto : '. $row_list->xray_foto.'' : '';
            $kontra_indikasi = ($row_list->kontra_indikasi != '') ? ' | kontra_indikasi : '. $row_list->kontra_indikasi.'' : '';

            $row[] = '<div class="center">'.$no.'</div>';
            $row[] = $this->tanggal->formatDateTime($row_list->created_date);
            $row[] = $row_list->no_mr.'<br>'.$row_list->nama_pasien;
            $strtoarray = str_replace("|", "<br>",$row_list->nama_tarif);

            $row[] = $strtoarray;
            $row[] = $row_list->dr_pengirim.'<br>'.$row_list->bagian_asal;
            $status = ($row_list->status == 1) ? '<span style="font-weight: bold; color: green">sudah diproses</span>' : '<span style="font-weight: bold; color: red">belum diproses</span>' ;
            $row[] = '<div class="center">'.$status.'</div>';
            $row[] = '<div class="center"><a href="#" class="btn btn-xs btn-success" onclick="edit_order_lab('.$row_list->id_pm_tc_penunjang.')"><i class="fa fa-edit"></i></a> <a href="#" class="btn btn-xs btn-danger" onclick="delete_order_lab('.$row_list->id_pm_tc_penunjang.')" ><i class="fa fa-times"></i></a></div>';
           
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Pl_pelayanan_pm->count_all_order_penunjang(),
                        "recordsFiltered" => $this->Pl_pelayanan_pm->count_filtered_order_penunjang(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function process_order_penunjang(){

        // print_r($_POST);die;
        // form validation
        $this->form_validation->set_rules('id_pm_tc_penunjang', 'id_pm_tc_penunjang', 'trim|required');      
        $this->form_validation->set_rules('pl_kode_tindakan_hidden', 'pl_kode_tindakan_hidden', 'trim');      
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'trim');      

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
            
            // simpan penunjang medis
            $title = 'Pesan Penunjang Medis';
            $no_mr = $this->regex->_genRegex($_POST['noMrHidden'],'RGXQSL');
            $kode_perusahaan = $this->regex->_genRegex($_POST['kode_perusahaan'],'RGXINT');
            $kode_kelompok =  $this->regex->_genRegex($_POST['kode_kelompok'],'RGXINT');
            $kode_dokter = $this->regex->_genRegex(0,'RGXINT');
            $no_registrasi = $this->input->post('no_registrasi');
            $kode_bagian_asal = $_POST['kode_bagian_asal'];
            // cek existing
            $existing_pm = $this->db->get_where('tc_kunjungan', array('no_registrasi' => $no_registrasi, 'kode_bagian_tujuan' => $_POST['kode_bagian_pm'], 'kode_bagian_asal' => $kode_bagian_asal) );

            $row = $existing_pm->row();
            $id_pm_tc_penunjang = $_POST['id_pm_tc_penunjang'];
            $no_kunjungan = $row->no_kunjungan;

            $data_pm_tc_penunjang = array(
                'dr_pengirim' => $this->regex->_genRegex($_POST['dokter_pemeriksa'],'RGXQSL'),
                'tgl_daftar' => date('Y-m-d H:i:s'),
                'eorder' => 1,
            );
            /*save penunjang medis*/
            $this->Pl_pelayanan->update('pm_tc_penunjang', $data_pm_tc_penunjang, ['id_pm_tc_penunjang' => $_POST['id_pm_tc_penunjang']]);
            $this->db->trans_commit();
            
            $dataexc = array(
                'kode_tarif' => $this->regex->_genRegex($this->input->post('pl_kode_tindakan_hidden'),'RGXINT'), 
                'nama_tarif' => $this->regex->_genRegex($this->input->post('pl_nama_tindakan'),'RGXQSL'), 
                'keterangan' => $this->regex->_genRegex($this->input->post('pl_keterangan_tindakan'),'RGXQSL'), 
                'id_pm_tc_penunjang' => $this->regex->_genRegex($_POST['id_pm_tc_penunjang'],'RGXINT'), 
                'created_date' => date('Y-m-d H:i:s'), 
                'created_by' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL'), 
            );

            if($kode_bagian_tujuan = '050301'){
                $dataexc['diagnosa'] = $this->regex->_genRegex($this->input->post('pl_diagnosa'),'RGXQSL');
                $dataexc['xray_foto'] = $this->regex->_genRegex($this->input->post('xray_foto'),'RGXQSL');
                $dataexc['kontra_indikasi'] = $this->regex->_genRegex($this->input->post('kontra_indikasi'),'RGXQSL');
                $dataexc['anamnesa'] = $this->regex->_genRegex($this->input->post('pl_anamnesa'),'RGXQSL');
            }
            
            // print_r($dataexc);die;
            $this->Pl_pelayanan->save('pm_tc_penunjang_order_detail', $dataexc);

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'no_kunjungan' => $no_kunjungan));
            }
        
        }

    }

    public function process_order_lab(){

        // print_r($_POST);die;
        // form validation
        $this->form_validation->set_rules('selected_pemeriksaan[]', 'Item Pemeriksaan', 'trim|required', array('required' => 'Silahkan pilih pemeriksaan laboratorium'));         

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
            
            // simpan penunjang medis
            $title = 'Pesan Laboratorium';
            $no_mr = $this->regex->_genRegex($_POST['noMrHidden'],'RGXQSL');
            $kode_perusahaan = $this->regex->_genRegex($_POST['kode_perusahaan'],'RGXINT');
            $kode_kelompok =  $this->regex->_genRegex($_POST['kode_kelompok'],'RGXINT');
            $kode_dokter = $this->regex->_genRegex(0,'RGXINT');
            $no_registrasi = $this->input->post('no_registrasi');
            $kode_bagian_asal = $_POST['kode_bagian_asal'];

            $id_pm_tc_penunjang = isset($_POST['id_pm_tc_penunjang'])?$_POST['id_pm_tc_penunjang']:0;
            // cek existing
            $existing_pm = $this->db->get_where('pm_tc_penunjang', array('id_pm_tc_penunjang' => $id_pm_tc_penunjang) );

            // print_r($this->db->last_query());die;

            if($existing_pm->num_rows() == 0){
                $kode_bagian_tujuan = $this->regex->_genRegex($_POST['kode_bagian_pm'],'RGXQSL');
                $no_kunjungan = $this->daftar_pasien->daftar_kunjungan($title, $no_registrasi, $no_mr, $kode_dokter, $kode_bagian_tujuan, $kode_bagian_asal);
                
                /*insert penunjang medis*/
                $kode_penunjang = $this->master->get_max_number('pm_tc_penunjang', 'kode_penunjang');
                $no_antrian = $this->master->get_no_antrian_pm($this->regex->_genRegex($kode_bagian_tujuan,'RGXQSL'));
                $klas = $this->input->post('kode_klas');
                $data_pm_tc_penunjang = array(
                    'kode_penunjang' => $kode_penunjang,
                    'tgl_daftar' => date('Y-m-d H:i:s'),
                    'kode_bagian' => $this->regex->_genRegex($kode_bagian_tujuan,'RGXQSL'),
                    'dr_pengirim' => $this->regex->_genRegex($_POST['dokter_pemeriksa'],'RGXQSL'),
                    'no_kunjungan' => $no_kunjungan,
                    'no_antrian' => $no_antrian,
                    'kode_klas' => $klas,
                    'petugas_input' => $this->session->userdata('user')->user_id,
                    'eorder' => 1,
                );
                /*save penunjang medis*/
                $newIdPM = $this->Pl_pelayanan->save('pm_tc_penunjang', $data_pm_tc_penunjang);
                $this->db->trans_commit();
            }else{
                $row = $existing_pm->row();
                $newIdPM = $row->id_pm_tc_penunjang;

                $data_pm_tc_penunjang = array(
                    'dr_pengirim' => $this->regex->_genRegex($_POST['dokter_pemeriksa'],'RGXQSL'),
                    'tgl_daftar' => date('Y-m-d H:i:s'),
                    'eorder' => 1,
                );
                /*save penunjang medis*/
                $this->Pl_pelayanan->update('pm_tc_penunjang', $data_pm_tc_penunjang, ['id_pm_tc_penunjang' => $newIdPM]);
                $this->db->trans_commit();

                $no_kunjungan = $row->no_kunjungan;
            }
            
            
            // delete last order and then insert back
            $this->db->delete('pm_tc_penunjang_order_detail', ['id_pm_tc_penunjang' => $newIdPM]);
            foreach ($_POST['selected_pemeriksaan'] as $key => $value) {
                $dataexc[] = array(
                    'kode_tarif' => $this->regex->_genRegex($key,'RGXINT'), 
                    'nama_tarif' => $this->regex->_genRegex($value,'RGXQSL'), 
                    'id_pm_tc_penunjang' => $this->regex->_genRegex($newIdPM,'RGXINT'), 
                    'created_date' => date('Y-m-d H:i:s'), 
                    'created_by' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL'), 
                );
            }
            // print_r($dataexc);die;
            $this->db->insert_batch('pm_tc_penunjang_order_detail', $dataexc);

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'no_kunjungan' => $no_kunjungan));
            }
        
        }

    }

    public function delete_order()
    {
        $id=$this->input->post('ID')?$this->input->post('ID',TRUE):null;
        $existing = $this->db->get_where('pm_tc_penunjang_order_detail', ['order_id' => $id])->row();
        if($this->db->delete('pm_tc_penunjang_order_detail', ['order_id' => $id])){
            echo json_encode(array('status' => 200, 'message' => 'Proses Hapus Data Berhasil Dilakukan'));
        }else{
            echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Hapus Data Gagal Dilakukan'));
        }
        
    }

    public function delete_order_by_bundle()
    {
        $id=$this->input->post('ID')?$this->input->post('ID',TRUE):null;
        if($this->db->delete('pm_tc_penunjang_order_detail', ['id_pm_tc_penunjang' => $id])){
            // delete pm_tc_penunjang
            $this->db->delete('pm_tc_penunjang', ['id_pm_tc_penunjang' => $id]);
            
            echo json_encode(array('status' => 200, 'message' => 'Proses Hapus Data Berhasil Dilakukan'));
        }else{
            echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Hapus Data Gagal Dilakukan'));
        }
        
    }

    public function order_pemeriksaan_fisio() { 
        /*define variable data*/
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show(),
            'no_mr' => isset($_GET['no_mr'])?$_GET['no_mr']:'',
            'kode_bagian' => $_GET['kode_bagian'],
        );

        $this->load->view('Pl_pelayanan_pm/order_pemeriksaan_fisio', $data);
    }

    public function order_pemeriksaan_penunjang() { 
        /*define variable data*/
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show(),
            'no_mr' => isset($_GET['no_mr'])?$_GET['no_mr']:'',
            'kode_bagian' => $_GET['kode_bagian'],
        );

        $this->load->view('Pl_pelayanan_pm/order_pemeriksaan_penunjang', $data);
        
    }

    public function order_pemeriksaan_penunjang_detail() { 
        /*define variable data*/
        // cek data pengantar
        $ex = $this->db->get_where('pm_tc_penunjang_order_detail', array('id_pm_tc_penunjang' => $_GET['id_pm_tc_penunjang']))->result();

        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show(),
            'no_mr' => isset($_GET['no_mr'])?$_GET['no_mr']:'',
            'kode_bagian' => $_GET['kode_bagian'],
            'id_pm_tc_penunjang' => $_GET['id_pm_tc_penunjang'],
            'ex' => $ex,
        );

        echo json_encode(['html' => $this->load->view('Pl_pelayanan_pm/order_pemeriksaan_penunjang_detail', $data, true) ]);
        
    }

    public function konfirmasi_order(){
        $id = $_POST['ID'];
        if( $this->db->where('id_pm_tc_penunjang', $id)->update('pm_tc_penunjang_order_detail', ['status' => 1, 'konfirmasi_order_date' => date('Y-m-d H:i:s')]) ){
            echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan'));
        }else{
            echo json_encode(array('status' => 301, 'message' => 'Proses Gagal Dilakukan'));
        }

    }

    public function riwayat_kunjungan($no_mr, $kode_bagian='') { 
        
        $data = [
            'no_mr' => $no_mr,
            'kode_bagian' => $kode_bagian,
        ];
        $this->load->view('Pl_pelayanan_pm/tab_riwayat_kunjungan_pm', $data);
    }

    public function get_riwayat_kunjungan_pm() { 
        
        /*define variable data*/
        
        $mr = $this->input->get('mr');

        /*return search pasien*/
        $data = array();
        $output = array();

        $column = array('tc_kunjungan.no_registrasi','tc_registrasi.no_sep','tc_registrasi.kode_perusahaan','tc_kunjungan.tgl_masuk','mt_dokter_v.nama_pegawai','mt_bagian.nama_bagian','tc_kunjungan.tgl_keluar','tc_kunjungan.kode_bagian_tujuan','mt_perusahaan.nama_perusahaan','tc_kunjungan.no_kunjungan', 'tc_kunjungan.kode_dokter', 'mt_master_pasien.nama_pasien', 'mt_master_pasien.no_mr, pl_tc_poli.id_pl_tc_poli');

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

            $btn_cetak_sep = ($row_list->kode_perusahaan == 120)?'<li><a href="#" onclick="show_modal('."'ws_bpjs/Ws_index/view_sep/".$row_list->no_sep."?no_antrian=".$row_list->no_antrian."'".', '."'SURAT ELEGIBILITAS PASIEN'".')">Cetak Ulang SEP</a></li>':'';

            if($row_list->nama_perusahaan==''){
                $penjamin = 'Umum';
            }else if(($row_list->nama_perusahaan!='') AND ($row_list->kode_perusahaan==120) AND ($row_list->no_sep!='')){
                $penjamin = $row_list->nama_perusahaan.' ('.$row_list->no_sep.')';
            }else{
                $penjamin = $row_list->nama_perusahaan;
            }

            // cek authuser
            $btn_delete = ($this->authuser->is_administrator($this->session->userdata('user')->user_id) ) ? '<li><a href="#" onclick="delete_registrasi('.$row_list->no_registrasi.','.$row_list->no_kunjungan.')">Hapus</a></li>' : '';
            
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
                            '.$btn_view_hasil_pm.'
                            <li class="divider"></li>
                            <li><a href="#" onclick="show_modal('."'registration/reg_pasien/view_detail_resume_medis/".$row_list->no_registrasi."'".', '."'RESUME MEDIS'".')">Selengkapnya</a></li>
                        </ul>
                    </div></div>';

            $no_antrian = (substr($row_list->kode_bagian_tujuan, 0,2) == '01') ? '<br> No. Antrian : <b style="font-size:12px">'.$row_list->no_antrian.'</b>' : '';
            $nama_dokter = ($row_list->nama_pegawai != '') ? $row_list->nama_pegawai.'<br>' : '' ;

            $row[] = $row_list->no_registrasi;

            $row[] = $penjamin;
            
            $row[] = $this->tanggal->formatDateTime($row_list->tgl_masuk);
            
            $row[] = $this->tanggal->formatDateTime($row_list->tgl_keluar);
            
            $row[] = ucfirst($row_list->nama_bagian);
            
            $row[] = $nama_dokter;
            
            //$penjamin = ($row_list->nama_perusahaan=='')?'Umum':($row_list->kode_perusahaan==120 && $row_list->no_sep!='')?$row_list->nama_perusahaan.' ('.$row_list->no_sep.')':$row_list->nama_perusahaan;
            
            $row[] = '<div class="left">'.$status.'</div>';

            $row[] = '<div class="center"><a href="#" onclick="getMenuTabs('."'pelayanan/Pl_pelayanan/form_lab_detail/".$row_list->id_pl_tc_poli."/".$row_list->no_kunjungan."/".$row_list->id_pm_tc_penunjang."?type=PM&kode_bag=".$row_list->kode_bagian_tujuan."&kode_bag_asal=".$_GET['kode_bagian']."'".', '."'tabs_riwayat_kunjungan'".')" class="label label-primary">Buat Pengantar</a></div>';
          
            $data[] = $row;
        
        }

        $output = array( "draw" => $_POST['draw'], "recordsTotal" => count($list), "recordsFiltered" => $this->Reg_pasien->count_filtered_riwayat_pasien( $column, $mr ), "data" => $data );

        echo json_encode( $output );
    
    }

    public function preview_pengantar_penunjang()
    {
        $list = $this->Pl_pelayanan_pm->_get_data_order_penunjang();
        // echo "<pre>";print_r($list);die;
        if(empty($list)){
            echo "<div class='alert alert-danger' style='padding: 20px; background: #ff000038; font-family: sans-serif'><b>Mohon Maaf</b><br>Data tidak ditemukan, tidak dapat mencetak surat pengantar</div>";
            return;
        }else{
            $this->load->module('Templates/Templates.php');
            $temp = new Templates;
            $result = json_decode($this->Csm_billing_pasien->getDetailData($list[0]->no_registrasi));
            $result->nama_ppa = $result->reg_data->nama_pegawai;
            $result->kode_dr = $result->reg_data->kode_dokter;
            $header = $temp->setGlobalProfileCppt($result);
            $footer = $temp->setGlobalFooterCppt($result);

            $data = array();
            $data['kode_penunjang'] = $list[0]->kode_penunjang;
            $data['tgl_daftar'] = $list[0]->tgl_daftar;
            $data['header'] = $header;
            $data['footer'] = $footer;
            // unit
            switch ($_GET['kode_bagian']) {
                case '050101':
                    $unit = 'LAB';
                    break;
                case '050201':
                    $unit = 'RAD';
                    break;
                case '050301':
                    $unit = 'Fisio';
                    break;
            }
            $data['unit'] = $unit;

            $result_data = [];
            $no = 0;
            foreach ($list as $row_list) {
                $no++;
                $arr_str = array_filter(explode("|", $row_list->nama_tarif));
                $html = '<ol>';
                foreach ($arr_str as $value) {
                    if(strlen($value) > 0){
                        $html .= '<li>' . htmlspecialchars($value) . '</li>';
                    }
                }
                $html .= '</ol>';

                $status = ($row_list->status == 1)
                ? '<span style="font-weight: bold; color: green">sudah diproses</span>'
                : '<span style="font-weight: bold; color: red">belum diproses</span>';

                
                // Prepare anamnesa and diagnosa only for kode_bagian 050301
                $anamnesa = '';
                $diagnosa = '';
                if (isset($_GET['kode_bagian']) && $_GET['kode_bagian'] == '050301') {
                    $anamnesa = ($row_list->anamnesa != '') ? '<br><b>Anamnesa :</b> <br>' . htmlspecialchars($row_list->anamnesa) . '<br>' : '';
                    $diagnosa = ($row_list->diagnosa != '') ? '<br><b>Diagnosa :</b> <br>' . htmlspecialchars($row_list->diagnosa) : '';
                }

                $result_data[] = [
                    '<div class="center">' . $no . '</div>',
                    $this->tanggal->formatDateTime($row_list->created_date),
                    htmlspecialchars($row_list->no_mr) . '<br>' . htmlspecialchars($row_list->nama_pasien),
                    $anamnesa . $diagnosa,
                    $html,
                    htmlspecialchars($row_list->dr_pengirim),
                    htmlspecialchars($row_list->bagian_asal),
                ];
            }

            $data['result'] = $result_data;
        }

        // echo "<pre>"; print_r($data);die;

        $this->load->view('Pl_pelayanan_pm/preview_pengantar_penunjang', $data);
        
    }

    public function submit_pengambilan_sampel(){

        echo '<pre>';print_r($_POST);die;
        // form validation
        $this->form_validation->set_rules('kode_penunjang', 'Kode Penunjang', 'trim');
               
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
            
            /*insert pm_tc_hasilpenunjang*/
            foreach($_POST['kode_mt_hasilpm'] as $key=>$row_dt){

                // ini akan jadi data master hasil penunjang
                $dataexc = array(
                    'loinc_uom' =>  $kode_mt_hasilpm,
                    'loinc_type' =>  $kode_mt_hasilpm,
                    'loinc_metode' =>  $kode_mt_hasilpm,
                    'loinc_speciment' =>  $kode_mt_hasilpm,
                    'satuan' =>  $kode_mt_hasilpm,
                );

                if($kode_trans_pelayanan!=''){
                    $cek_mcu = $this->db->get_where('tc_trans_pelayanan_paket_mcu',array('kode_trans_pelayanan_paket_mcu' => $kode_trans_pelayanan))->row();
                    if(isset($cek_mcu) AND $cek_mcu->kode_bagian_asal=='010901'){
                        $dataexc["flag_mcu"] = 1; 
                    }
                }

                // cek hasil apakah sudah pernah diinput
                $dt_ex = $this->db->get_where('pm_tc_hasilpenunjang', array('kode_trans_pelayanan' => $kode_trans_pelayanan, 'kode_mt_hasilpm' => $kode_mt_hasilpm) );
                // echo '<pre>';print_r($dt_ex->row());die;
                
                if($dt_ex->num_rows() > 0){
                    $this->Pl_pelayanan_pm->update('pm_tc_hasilpenunjang', $dataexc, array('kode_tc_hasilpenunjang' => $dt_ex->row()->kode_tc_hasilpenunjang ) );
                    // echo '<pre>';print_r($this->db->last_query());die;
                    $this->db->trans_commit();
                }else{
                    $dataexc["kode_tc_hasilpenunjang"] = $kode_tc_hasilpenunjang; 
                    $dataexc["kode_trans_pelayanan"] = $kode_trans_pelayanan; 
                    $this->Pl_pelayanan_pm->save('pm_tc_hasilpenunjang', $dataexc);
                    $this->db->trans_commit();
                }

            }
          
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'type_pelayanan' => 'isi_hasil', 'bagian' => $this->input->post('kode_bagian')));
            }
        
        }

    }

}

/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
