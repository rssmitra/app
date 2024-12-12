<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pl_pelayanan_ri extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'pelayanan/Pl_pelayanan_ri');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('Pl_pelayanan_ri_model', 'Pl_pelayanan_ri');
        $this->load->model('Pl_pelayanan_model', 'Pl_pelayanan');
        $this->load->model('adm_pasien/loket_kasir/Adm_kasir_ri_model', 'Adm_kasir_ri');
        /*load library*/
        $this->load->library('Form_validation');
        $this->load->library('stok_barang');
        $this->load->library('tarif');
        $this->load->library('daftar_pasien');
        /*enable profiler*/
        $this->output->enable_profiler(false);
        // load other module
        $this->load->model('casemix/Csm_billing_pasien_model', 'Csm_billing_pasien');
        $this->load->module('casemix/Csm_billing_pasien');
        $this->cbpModule = new Csm_billing_pasien;
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'Title';

    }

    public function index() { 
        /*define variable data*/
        $is_icu = (isset($_GET['is_icu']))?$_GET['is_icu']:'N';
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show(),
            'is_icu' => $is_icu,
        );

        $this->load->view('Pl_pelayanan_ri/index', $data);
    }

    public function form($kode_ri='', $no_kunjungan)
    {
         /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Lembar Kerja '.strtolower($this->title).'', 'Pl_pelayanan_ri/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$kode_ri);
        /*get value by id*/
        $data['value'] = $this->Pl_pelayanan_ri->get_by_id($kode_ri);
        //echo '<pre>';print_r($data['value']);die;
        $data['riwayat'] = $this->Pl_pelayanan_ri->get_riwayat_pasien_by_id($no_kunjungan);
        $data['transaksi'] = $this->Pl_pelayanan_ri->get_transaksi_pasien_by_id($no_kunjungan);
        $data['ruangan'] = $this->Pl_pelayanan_ri->get_ruangan_by_id($data['value']->kode_ruangan);
        /*variable*/
        $data['no_mr'] = $data['value']->no_mr;
        $data['id'] = $kode_ri;
        $data['kode_klas'] = $data['value']->kelas_pas;
        $data['klas_titipan'] = ($data['value']->kelas_titipan!=NULL)?$data['value']->kelas_titipan:0;
        $data['kode_profit'] = 2000;
        $data['no_kunjungan'] = $no_kunjungan;
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();

        // echo '<pre>';print_r($data);die;
        /*load form view*/
        $this->load->view('Pl_pelayanan_ri/form', $data);
    }

    public function form_main($kode_ri='', $no_kunjungan)
    {
         /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Pl_pelayanan_ri/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$kode_ri);
        /*get value by id*/
        $data['value'] = $this->Pl_pelayanan_ri->get_by_id($kode_ri);
        // echo '<pre>';print_r($data['value']);die;
        $data['riwayat'] = $this->Pl_pelayanan_ri->get_riwayat_pasien_by_id($no_kunjungan);
        $data['transaksi'] = $this->Pl_pelayanan_ri->get_transaksi_pasien_by_id($no_kunjungan);
        $data['ruangan'] = $this->Pl_pelayanan_ri->get_ruangan_by_id($data['value']->kode_ruangan);
        // cek transaksi
        $cek_trans = $this->Pl_pelayanan_ri->cek_trans_pelayanan($data['value']->no_registrasi);
        if($cek_trans==0){
            $status_pasien = '<span style="color: blue; font-weight: bold; font-size: 14px">SUDAH LUNAS </span>';
        }else{
            $status_pasien = ($data['value']->status_pulang== 0 || NULL)?($data['value']->pasien_titipan== 1)?'<span style="color: darkgoldenrod; font-weight: bold; font-size: 10px">PASIEN TITIPAN '.strtoupper($data['value']->klas_titip).' </span>':'<span style="color: red; font-weight: bold; font-size: 14px">MASIH DIRAWAT</span>':'<span style="color: green; font-weight: bold; font-size: 14px">SUDAH PULANG</span>';
        }

        $data['status_rawat'] = $status_pasien;

        /*variable*/
        $data['no_mr'] = $data['value']->no_mr;
        $data['id'] = $kode_ri;
        $data['kode_klas'] = ($data['value']->pasien_titipan == 1) ? $data['value']->kelas_titipan : $data['value']->kelas_pas;
        $data['klas_titipan'] = ($data['value']->kelas_titipan!=NULL)?$data['value']->kelas_titipan:0;
        $data['kode_profit'] = 2000;
        $data['no_kunjungan'] = $no_kunjungan;
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();

        // echo '<pre>';print_r($data);die;
        /*load form view*/
        $this->load->view('Pl_pelayanan_ri/form_main', $data);
    }

    public function tindakan($id='', $no_kunjungan='')
    {
         /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Pl_pelayanan_ri/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*get value by id*/
        $data['value'] = $this->Pl_pelayanan_ri->get_by_id($id);
        /*mr*/
        $data['no_mr'] = $data['value']->no_mr;
        $data['no_kunjungan'] = $no_kunjungan;
        $data['kode_ri'] = $id;
        $data['sess_kode_bag'] = ( $data['value']->bag_pas)? $data['value']->bag_pas:0;
        $data['type']='Ranap';
        $data['status_pulang'] = $data['value']->status_pulang;
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();

        /*load form view*/
        $this->load->view('Pl_pelayanan/form_tindakan', $data);
    }


    public function pesan($id='', $no_registrasi='')
    {
         /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Pl_pelayanan_ri/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*get value by id*/
        $data['value'] = $this->Pl_pelayanan_ri->get_by_id($id);
        /*mr*/
        $data['no_mr'] = $data['value']->no_mr;
        $data['no_kunjungan'] = $data['value']->no_kunjungan;
        $data['no_registrasi'] = $no_registrasi;
        $data['kode_ri'] = $id;
        $data['type']='Ranap';
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Pl_pelayanan_ri/form_pesan', $data);
    }

    public function get_data()
    {
        /*akan di filter berdasarkan pasien pada klinik masing2*/
        /*get data from model*/
        $list = $this->Pl_pelayanan_ri->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $link = 'billing/Billing';
            $str_type = 'RI';
            $cek_trans = $this->Pl_pelayanan_ri->cek_trans_pelayanan($row_list->no_registrasi);
            $cek_total_billing = $this->Adm_kasir_ri->cek_total_billing($row_list->no_registrasi);
            $rollback_btn = ($cek_trans>0 AND $row_list->status_pulang!= 0 || NULL)?'<li><a href="#" onclick="rollback('.$row_list->no_registrasi.','.$row_list->no_kunjungan.')">Rollback</a></li>':'';

            /*color of type Ruangan RI*/
            /*LB*/
            // if ( in_array($row_list->bag_pas, array('030101','031401','031301','030801','030401','031601') ) ) {
            //     $color = 'red';
            // /*LA*/
            // }elseif( in_array($row_list->bag_pas,  array('030701','030301','030601','030201') )){
            //     $color = 'green';
            // /*VK Ruang Bersalin*/
            // }elseif( in_array($row_list->bag_pas,  array('031201','031701','030501') )){
            //     $color = 'blue';
            // }else{
            //     $color = 'black';
            // }
            $color = 'black';
            $row[] = $row_list->no_registrasi;
            $row[] = $str_type;
            $row[] = '';
            $row[] = '<div class="center"><div class="btn-group">
                        <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">
                            <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-inverse">
                            '.$rollback_btn.' 
                            <li><a href="#" onclick="show_modal('."'registration/reg_pasien/view_detail_resume_medis/".$row_list->no_registrasi."'".', '."'RESUME MEDIS'".')">Selengkapnya</a></li>
                        </ul>
                    </div></div>';
            $row[] = '<div class="center"><a style="font-weight: bold; color: blue" href="#" onclick="getMenu('."'pelayanan/Pl_pelayanan_ri/form/".$row_list->kode_ri."/".$row_list->no_kunjungan."'".')">'.$row_list->no_mr.'</a></div>';
            $row[] = '<span style="color:'.$color.'">'.strtoupper($row_list->nama_pasien).'</span>';
            $row[] = $this->tanggal->formatDateTime($row_list->tgl_masuk);
            $row[] = $row_list->nama_bagian.'<br>'.$row_list->klas.'  [Kamar '.$row_list->no_kamar.'/'.$row_list->no_bed.']';
            $row[] = ($row_list->nama_perusahaan)?$row_list->nama_perusahaan:$row_list->nama_kelompok;
            // $row[] = $row_list->klas;
            // $row[] = ($row_list->klas_titip)?$row_list->klas_titip:'-';
            $row[] = $row_list->nama_pegawai;
            $row[] = number_format($row_list->tarif_inacbgs);
            if($row_list->tarif_inacbgs > 0){
                $color_bill = ($row_list->tarif_inacbgs < $cek_total_billing->total_billing) ? 'red' : 'green' ;
            }else{
                $color_bill = 'black';
            }

            $row[] = '<span style="font-weight: bold; color: '.$color_bill.'">'.number_format($cek_total_billing->total_billing).'</span>';

            if($cek_trans==0){
                $status_pulang = '<label class="label label-primary"><i class="fa fa-money"></i> Lunas </label>';
            }else{
                $status_pulang = ($row_list->status_pulang== 0 || NULL)?($row_list->pasien_titipan== 1)?'<label class="label label-yellow">Pasien Titipan</label>':'<label class="label label-danger">Masih dirawat</label>':'<label class="label label-success">Pulang</label>';
            }

            $row[] = '<div class="center">'.$status_pulang.'</div>';
           
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Pl_pelayanan_ri->count_all(),
                        "recordsFiltered" => $this->Pl_pelayanan_ri->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function get_list_pasien()
    {
        /*akan di filter berdasarkan pasien pada klinik masing2*/
        /*get data from model*/
        $list = $this->Pl_pelayanan_ri->get_list_data();
        $data = array();
        $no=0;
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            /*color of type Ruangan RI*/
            
            /*LB*/
            if ( in_array($row_list->bag_pas, array('030101','031401','031301','030801','030401','031601') ) ) {
                $color = 'red';
            /*LA*/
            }elseif( in_array($row_list->bag_pas,  array('030701','030301','030601','030201') )){
                $color = 'green';
            /*VK Ruang Bersalin*/
            }elseif( in_array($row_list->bag_pas,  array('031201','031701','030501') )){
                $color = 'blue';
            }else{
                $color = 'black';
            }
            // $row[] = '<li><span style="color:'.$color.'" onclick="getMenu('."'pelayanan/Pl_pelayanan_ri/form/".$row_list->kode_ri."/".$row_list->no_kunjungan."'".')">'.$row_list->no_mr.' - '.strtoupper($row_list->nama_pasien).'</span></li>';
            
            $row[] = array('no_kunjungan' => $row_list->no_kunjungan, 'kode_ri' => $row_list->kode_ri, 'no_mr' => $row_list->no_mr, 'nama_pasien' => strtoupper($row_list->nama_pasien), 'color_txt' => $color);
            $data[] = $row;
        }

        $output = array("data" => $data );
        //output to json format
        echo json_encode($output);
    }

    public function get_riwayat_diagnosa()
    {
        /*get data from model*/
        $list = $this->Pl_pelayanan_ri->get_datatables_riwayat_diagnosa();
        // echo '<pre>'; print_r($list);die;
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            if($_GET['no_kunjungan']==$row_list->no_kunjungan){
                $row[] = '<div class="center">
                            <a href="#" class="btn btn-minier btn-success" onclick="edit_diagnosa('.$row_list->kode_riwayat.')"><i class="fa fa-pencil"></i></a>
                            <a href="#" class="btn btn-minier btn-danger" onclick="delete_diagnosa('.$row_list->kode_riwayat.')"><i class="fa fa-times-circle"></i></a>
                          </div>';
            }else{
                $row[] = '<div class="center"><i class="fa fa-check-circle red"></i></div>';
            }

            $row[] = $this->tanggal->formatDatedmY($row_list->tgl_periksa);
            $row[] = strtoupper($row_list->nama_bagian);
            $row[] = $row_list->anamnesa;
            $row[] = ($row_list->diagnosa_awal == $row_list->diagnosa_akhir) ? $row_list->diagnosa_awal : $row_list->diagnosa_awal.'<br>'.$row_list->diagnosa_akhir;
            $row[] = $row_list->pemeriksaan;
            $row[] = nl2br($row_list->pengobatan);
           
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Pl_pelayanan_ri->count_all_tindakan_riwayat_diagnosa(),
                        "recordsFiltered" => $this->Pl_pelayanan_ri->count_filtered_tindakan_riwayat_diagnosa(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function get_pesan_vk()
    {
        /*get data from model*/
        $list = $this->Pl_pelayanan_ri->get_datatables_pesan_vk();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            if($row_list->tgl_keluar==NULL){
                $row[] = '<div class="center"><a href="#" class="btn btn-xs btn-danger" onclick="delete_transaksi('.$row_list->id_pasien_vk.',1)"><i class="fa fa-times-circle"></i></a></div>';
            }else{
                $row[] = '<div class="center"><i class="fa fa-check-circle green"></i></div>';
            }

            $row[] = $row_list->id_pasien_vk;
            $row[] = $this->tanggal->formatDate($row_list->tgl_masuk);
            $row[] = strtoupper($row_list->nama_bagian);
            $row[] = strtoupper($row_list->nama_klas);
            $row[] = $row_list->no_kamar_vk;
           
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Pl_pelayanan_ri->count_all_tindakan_pesan_vk(),
                        "recordsFiltered" => $this->Pl_pelayanan_ri->count_filtered_tindakan_pesan_vk(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function get_pesan_ok()
    {
        /*get data from model*/
        $list = $this->Pl_pelayanan_ri->get_datatables_pesan_ok();
        //print_r($list);die;
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $data_bedah = $this->Pl_pelayanan_ri->get_data_bedah($row_list->kode_master_tarif_detail);

            $row = array();
            if($row_list->tgl_keluar==NULL){
                $row[] = '<div class="center"><a href="#" class="btn btn-xs btn-danger" onclick="delete_transaksi('.$row_list->id_pesan_bedah.',2)"><i class="fa fa-times-circle"></i></a></div>';
            }else{
                $row[] = '<div class="center"><i class="fa fa-check-circle green"></i></div>';
            }

            $row[] = $row_list->id_pesan_bedah;
            $row[] = $this->tanggal->formatDate($row_list->tgl_pesan);
            $jenis_layanan = ($row_list->jenis_layanan==0)?'Biasa':'Cito';
            $row[] = $jenis_layanan;
            $row[] = isset($data_bedah->level3)?$data_bedah->level3:'';
            $row[] = isset($data_bedah->level5)?$data_bedah->level5:'';
            $row[] = $row_list->nama_pegawai;
            $status = ($row_list->flag_pesan==0)?'Belum ditindak':'Sudah Ditindak';
            $row[] = $status;
           
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Pl_pelayanan_ri->count_all_tindakan_pesan_ok(),
                        "recordsFiltered" => $this->Pl_pelayanan_ri->count_filtered_tindakan_pesan_ok(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function get_pesan_pindah()
    {
        /*get data from model*/
        $list = $this->Pl_pelayanan_ri->get_datatables_pesan_pindah();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
    
            $row[] = $row_list->kode_riw_klas;
            $s =  $row_list->tgl_masuk;
            $dt = new DateTime($s);

            $date = $this->tanggal->formatDate($dt->format('Y-m-d'));
            $time = $dt->format('H:i:s');
            $row[] = $date;
            $row[] = $time;
            $row[] = $row_list->nama_bagian;
            $row[] = $row_list->nama_klas;
            $row[] = $row_list->no_kamar_tujuan;
            $row[] = $row_list->no_bed_tujuan;
                    
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Pl_pelayanan_ri->count_all_tindakan_pesan_ok(),
                        "recordsFiltered" => $this->Pl_pelayanan_ri->count_filtered_tindakan_pesan_ok(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function get_data_cppt()
    {
        /*get data from model*/
        $list = $this->Pl_pelayanan_ri->get_datatables_cppt($_GET['no_mr']);
        //print_r($list);die;
        $data = array();
        $no=0;

        if (isset($_GET['type'])) {
            # untuk menampilkan catatan pengkajian saja
            foreach ($list as $row_list) {
                $row = array();
                if($row_list->jenis_form != null){
                    $no++;
                    $row[] = $no;
                    $row[] = $this->tanggal->formatDateTime($row_list->tanggal);
                    
                    $row[] = '['.strtoupper($row_list->ppa).']<br>'.$row_list->nama_ppa.'<br><label class="label label-success">'.$row_list->tipe.'</label><br>'.$txt_resume.'';
                    // $row[] = '<a href="#" onclick="show_modal_pengkajian('.$row_list->id.')">'.strtoupper($row_list->jenis_pengkajian).'</a>';
                    // $row[] = '<a href="#" onclick="show_modal_medium_return_json('."'pelayanan/Pl_pelayanan_ri/show_catatan_pengkajian/".$row_list->id."'".', '."'".$row_list->jenis_pengkajian."'".')">'.strtoupper($row_list->jenis_pengkajian).'</a>';
                    $row[] = '<a href="#" onclick="show_edit('.$row_list->id.', '."'".$row_list->tipe."'".', '.$row_list->no_kunjungan.', '.$row_list->reff_id.')">'.strtoupper($row_list->jenis_pengkajian).'</a>';
                    
                    $checked = ($row_list->is_verified == 1) ? 'checked' : '' ;
                    $desc = ($row_list->is_verified == 1) ? ''.$row_list->verified_by.'<br>'.$this->tanggal->formatDateTime($row_list->verified_date).'' : '' ;

                    $row[] = '<div class="center"><input name="is_verified" id="is_verified_'.$row_list->id.'" value="1" class="ace ace-switch ace-switch-5" type="checkbox" onclick="verif_dpjp('.$row_list->id.', this.value)" '.$checked.' ><span class="lbl"></span><br><span id="verif_id_'.$row_list->id.'">'.$desc.'</span></div>';
                    
                    $row[] = '<div class="center"><a href="#" class="btn btn-xs btn-success" onclick="show_edit('.$row_list->id.', '."'".$row_list->tipe."'".', '.$row_list->no_kunjungan.', '.$row_list->reff_id.')"><i class="fa fa-pencil"></i></a><a href="#" onclick="delete_cppt('.$row_list->id.','."'".$row_list->flag."'".')" class="btn btn-xs btn-danger"><i class="fa fa-times-circle"></i></a></div>';
                    $data[] = $row;
                }
            }
        }else{
            // menampilkan riwayat dan cppt keseluruhan
            foreach ($list as $row_list) {
                $no++;
                $row = array();
                $row[] = $no;
                $class_label = ($row_list->tipe == 'RJ')?'success':'primary';
                $status = '';
                if($row_list->tipe == 'RJ' && $row_list->no_kunjungan == null && $row_list->reff_id == null){
                    $status = '&nbsp;<label class="label label-danger">BATAL</label>';
                }
                if($row_list->flag == 'resume'){
                    $txt_resume = '<br>(RESUME MEDIS '.$row_list->tipe.')';
                }else{
                    $txt_resume = '<br>('.$row_list->jenis_pengkajian.')';
                }
                $row[] = $this->tanggal->formatDateTime($row_list->tanggal).'<br>'.strtoupper($row_list->ppa).'<br>'.$row_list->nama_ppa.'<br><label class="label label-'.$class_label.'">'.$row_list->tipe.'</label>'.$txt_resume.'<br>'.$status.'';

                $arr_text = isset($row_list->diagnosa_sekunder) ? str_replace('|',',',$row_list->diagnosa_sekunder) : '';
                // $diagnosa_sekunder = '';
                // foreach ($arr_text as $k => $v) {
                //     $len = strlen(trim($v));
                //     if($len > 0){
                //         $diagnosa_sekunder += $v;
                //     }
                // }
                $ttv = '';
                if($row_list->flag == 'resume'){
                    $ttv .= 'Vital Sign :<br>';
                    if($row_list->tinggi_badan != ''){
                        $ttv .= 'tb. '.$row_list->tinggi_badan.' cm<br>';
                    }
                    if($row_list->berat_badan != ''){
                        $ttv .= 'bb. '.$row_list->berat_badan.' kg<br>';
                    }
                    if($row_list->tekanan_darah != ''){
                        $ttv .= 'td. '.$row_list->tekanan_darah.' mm<br>';
                    }
                    if($row_list->nadi != ''){
                        $ttv .= 'nadi. '.$row_list->nadi.' bpm<br>';
                    }
                    if($row_list->suhu != ''){
                        $ttv .= 'suhu. '.$row_list->suhu.' &deg;C<br>';
                    }
                }
                if($row_list->jenis_form != null){
                    $row[] = '<b>Terlampir:</b><br><a href="#" onclick="show_modal_medium_return_json('."'pelayanan/Pl_pelayanan_ri/show_catatan_pengkajian/".$row_list->id."'".', '."'".$row_list->jenis_pengkajian."'".')">'.strtoupper($row_list->jenis_pengkajian).'</a>';
                }else{
                    $row[] = '<b>S (Subjective) : </b><br>'.nl2br($row_list->subjective).'<br><br>'.'<b>O (Objective) : </b><br>'.nl2br($row_list->objective).'<br>'.$ttv.'<br><br>'.'<b>A (Assesment) : </b><br>'.nl2br($row_list->assesment).'<br>'.$arr_text.''.'<br><br><b>P (Planning) : </b><br>'.nl2br($row_list->planning).'<br>';
                }
    
                $checked = ($row_list->is_verified == 1) ? 'checked' : '' ;
                $desc = ($row_list->is_verified == 1) ? ''.$row_list->verified_by.'<br>'.$this->tanggal->formatDateTime($row_list->verified_date).'' : '' ;
                $row[] = '<div class="center"><input name="is_verified" id="is_verified_'.$row_list->id.'" value="1" class="ace ace-switch ace-switch-5" type="checkbox" onclick="verif_dpjp('.$row_list->id.', this.value)" '.$checked.' ><span class="lbl"></span><br><span id="verif_id_'.$row_list->id.'">'.$desc.'</span></div>';
                if($row_list->jenis_form == null){
                $row[] = '<div class="center"><a href="#" class="btn btn-xs btn-success" onclick="show_edit('.$row_list->id.', '."'".$row_list->tipe."'".', '.$row_list->no_kunjungan.', '.$row_list->reff_id.')"><i class="fa fa-pencil"></i></a><a href="#" onclick="delete_cppt('.$row_list->id.', '."'".$row_list->flag."'".')" class="btn btn-xs btn-danger"><i class="fa fa-times-circle"></i></a></div>';
                }else{
                    $row[] = '<div class="center"></div>';
                }
               
                $data[] = $row;
            }
        }

        

        $output = array("data" => $data);
        //output to json format
        echo json_encode($output);
    }

    public function form_end_visit()
    {
        $data = array(
            'no_mr' => isset($_GET['mr'])?$_GET['mr']:'',
            'kode_ri' => isset($_GET['kode_ri'])?$_GET['kode_ri']:'',
            'no_kunjungan' => isset($_GET['no_kunjungan'])?$_GET['no_kunjungan']:'',
            'cppt' => $this->Pl_pelayanan_ri->get_datatables_cppt($_GET['mr']),
            'riwayat' => $this->Pl_pelayanan_ri->get_riwayat_pasien_by_id($_GET['no_kunjungan']),
            );
            // echo '<pre>';print_r($data);die;
        /*load form view*/
        $this->load->view('Pl_pelayanan_ri/form_end_visit', $data);
    }

    public function cppt($id='', $no_kunjungan='')
    {
         /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Pl_pelayanan_ri/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        // get kunjungan
        $kunjungan = $this->db->get_where('tc_kunjungan', ['no_kunjungan' => $no_kunjungan])->row();
        /*get value by id*/
        $data['value'] = $this->Pl_pelayanan_ri->get_by_id($id);
        $data['kunjungan'] = $kunjungan;
        // echo '<pre>';print_r($data);die;
        /*mr*/
        $data['no_mr'] = $kunjungan->no_mr;
        $data['no_kunjungan'] = $no_kunjungan;
        $data['no_registrasi'] = $kunjungan->no_registrasi;
        $data['kode_ri'] = $id;
        $data['sess_kode_bag'] = ( $kunjungan->kode_bagian_tujuan)? $kunjungan->kode_bagian_tujuan:0;
        $data['type']='Ranap';
        $data['status_pulang'] = ($kunjungan->tgl_keluar == null) ? 0 : 1;
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        // echo '<pre>';print_r($data);die;
        /*load form view*/
        $this->load->view('Pl_pelayanan_ri/form_cppt', $data);
    }

    public function monitoring_perkembangan($id='', $no_kunjungan='')
    {
         /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Pl_pelayanan_ri/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*get value by id*/
        $data['value'] = $this->Pl_pelayanan_ri->get_by_id($id);
        /*mr*/
        $data['no_mr'] = $data['value']->no_mr;
        $data['no_kunjungan'] = $no_kunjungan;
        $data['kode_ri'] = $id;
        $data['sess_kode_bag'] = ( $data['value']->bag_pas)? $data['value']->bag_pas:0;
        $data['type']='Ranap';
        $data['status_pulang'] = $data['value']->status_pulang;
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        // monitor perkembangan pasie
        $riwayat_monitoring = $this->db->order_by('id', 'DESC')->get_where('th_monitor_perkembangan_pasien_ri', ['no_kunjungan' => $no_kunjungan, 'type' => 'UMUM'])->result();
        $getDtMonitoring = [];
        foreach($riwayat_monitoring as $key=>$row){
            $getDtMonitoring[$row->tgl_monitor][] = $row;
        }
        $data['perkembangan'] = $getDtMonitoring;
        // echo '<pre>';print_r($data);die;
        /*load form view*/
        $this->load->view('Pl_pelayanan_ri/form_monitoring', $data);
    }

    public function pengawasan_khusus($id='', $no_kunjungan='')
    {
         /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Pl_pelayanan_ri/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*get value by id*/
        $data['value'] = $this->Pl_pelayanan_ri->get_by_id($id);
        /*mr*/
        $data['no_mr'] = $data['value']->no_mr;
        $data['no_kunjungan'] = $no_kunjungan;
        $data['kode_ri'] = $id;
        $data['sess_kode_bag'] = ( $data['value']->bag_pas)? $data['value']->bag_pas:0;
        $data['type']='Ranap';
        $data['status_pulang'] = $data['value']->status_pulang;
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        // monitor perkembangan pasie
        $riwayat_monitoring = $this->db->order_by('id', 'DESC')->get_where('th_monitor_perkembangan_pasien_ri', ['no_kunjungan' => $no_kunjungan, 'type' => 'KHUSUS'])->result();
        $getDtMonitoring = [];
        foreach($riwayat_monitoring as $key=>$row){
            $getDtMonitoring[$row->tgl_monitor][] = $row;
        }
        $data['perkembangan'] = $getDtMonitoring;
        // echo '<pre>';print_r($data);die;
        /*load form view*/
        $this->load->view('Pl_pelayanan_ri/form_pengawasan_khusus', $data);
    }

    public function pemberian_obat($id='', $no_kunjungan='')
    {
         /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Pl_pelayanan_ri/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*get value by id*/
        $data['value'] = $this->Pl_pelayanan_ri->get_by_id($id);
        /*mr*/
        $data['no_mr'] = $data['value']->no_mr;
        $data['no_kunjungan'] = $no_kunjungan;
        $data['kode_ri'] = $id;
        $data['sess_kode_bag'] = ( $data['value']->bag_pas)? $data['value']->bag_pas:0;
        $data['type']='Ranap';
        $data['status_pulang'] = $data['value']->status_pulang;
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        // monitor perkembangan pasie
        $pemberian_obat = $this->db->order_by('id', 'DESC')->get_where('th_monitor_pemberian_obat', ['no_kunjungan' => $no_kunjungan])->result();
        $data['obat'] = $pemberian_obat;
        // echo '<pre>';print_r($data);die;
        /*load form view*/
        $this->load->view('Pl_pelayanan_ri/form_pemberian_obat', $data);
    }

    public function askep($id='', $no_kunjungan='')
    {
         /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Pl_pelayanan_ri/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*get value by id*/
        $data['value'] = $this->Pl_pelayanan_ri->get_by_id($id);
        /*mr*/
        $data['no_mr'] = $data['value']->no_mr;
        $data['no_kunjungan'] = $no_kunjungan;
        $data['kode_ri'] = $id;
        $data['sess_kode_bag'] = ( $data['value']->bag_pas)? $data['value']->bag_pas:0;
        $data['type']='Ranap';
        $data['status_pulang'] = $data['value']->status_pulang;
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        // monitor perkembangan pasie
        $askep = $this->db->order_by('id', 'DESC')->get_where('th_asuhan_keperawatan', ['no_kunjungan' => $no_kunjungan])->result();
        $data['askep'] = $askep;
        // echo '<pre>';print_r($data);die;
        /*load form view*/
        $this->load->view('Pl_pelayanan_ri/form_askep', $data);
    }

    public function ews($id='', $no_kunjungan='')
    {
         /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Pl_pelayanan_ri/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*get value by id*/
        $data['value'] = $this->Pl_pelayanan_ri->get_by_id($id);
        /*mr*/
        $data['no_mr'] = $data['value']->no_mr;
        $data['no_kunjungan'] = $no_kunjungan;
        $data['kode_ri'] = $id;
        $data['sess_kode_bag'] = ( $data['value']->bag_pas)? $data['value']->bag_pas:0;
        $data['type']='Ranap';
        $data['status_pulang'] = $data['value']->status_pulang;
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        // monitor perkembangan pasie
        $askep = $this->db->order_by('id', 'DESC')->get_where('th_asuhan_keperawatan', ['no_kunjungan' => $no_kunjungan])->result();
        $data['askep'] = $askep;
        // echo '<pre>';print_r($data);die;
        /*load form view*/
        $this->load->view('Pl_pelayanan_ri/form_ews', $data);
    }

    public function note($id='', $no_kunjungan='')
    {
         /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Pl_pelayanan_ri/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*get value by id*/
        $data['value'] = $this->Pl_pelayanan_ri->get_by_id($id);
        /*mr*/
        $data['no_mr'] = $data['value']->no_mr;
        $data['no_kunjungan'] = $no_kunjungan;
        $data['kode_ri'] = $id;
        $data['sess_kode_bag'] = ( $data['value']->bag_pas)? $data['value']->bag_pas:0;
        $data['type']='Ranap';
        $data['status_pulang'] = $data['value']->status_pulang;
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        // monitor perkembangan pasie
        $note = $this->db->order_by('id', 'DESC')->get_where('th_drawing_notes', ['no_kunjungan' => $no_kunjungan])->result();
        $data['note'] = $note;
        // echo '<pre>';print_r($data);die;
        /*load form view*/
        $this->load->view('Pl_pelayanan_ri/form_note', $data);
    }

    public function riwayat_medis($id='', $no_kunjungan='')
    {
         /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Pl_pelayanan_ri/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        // get kunjungan
        $kunjungan = $this->db->get_where('tc_kunjungan', ['no_kunjungan' => $no_kunjungan])->row();
        /*get value by id*/
        $data['value'] = $this->Pl_pelayanan_ri->get_by_id($id);
        /*mr*/
        $data['no_mr'] = $kunjungan->no_mr;
        $data['no_kunjungan'] = $no_kunjungan;
        $data['kode_ri'] = $id;
        $data['sess_kode_bag'] = ( $kunjungan->kode_bagian_tujuan)? $kunjungan->kode_bagian_tujuan:0;
        $data['type']='Ranap';
        $data['status_pulang'] = ($kunjungan->tgl_keluar == null) ? 0 : 1;
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        // echo '<pre>';print_r($data);die;
        /*load form view*/
        $this->load->view('Pl_pelayanan_ri/view_riwayat_medis', $data);
    }

    public function view_cppt()
    {
        $data = array(
            'cppt' => $this->Pl_pelayanan_ri->get_datatables_cppt($_GET['no_mr']),
            );
            // echo '<pre>';print_r($data);die;
        /*load form view*/
        $this->load->view('Pl_pelayanan_ri/view_cppt', $data);
    }

    public function find_data()
    {   
        $output = array( "data" => http_build_query($_POST) . "\n" );
        echo json_encode($output);
    }

    public function process_add_diagnosa(){

        // form validation
        $this->form_validation->set_rules('noMrHidden', 'No MR', 'trim|required');
        $this->form_validation->set_rules('pl_diagnosa', 'Diagnosa', 'trim');
               

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

            $dataexc = array(
                'diagnosa_akhir' => $this->regex->_genRegex($this->input->post('pl_diagnosa'),'RGXQSL'), 
                'diagnosa_awal' => $this->regex->_genRegex($this->input->post('pl_diagnosa_awal'),'RGXQSL'),
                'kode_icd_diagnosa' =>  $this->regex->_genRegex($this->input->post('pl_diagnosa_hidden'),'RGXQSL'),
                'anamnesa' => $this->regex->_genRegex($this->input->post('pl_anamnesa'),'RGXQSL'),
                'pengobatan' => $this->regex->_genRegex($this->input->post('pl_pengobtan'),'RGXQSL'),
            );

            if($this->input->post('kode_riwayat')!=0){
                $this->Pl_pelayanan_ri->update('th_riwayat_pasien', $dataexc, array('no_kunjungan' => $this->input->post('no_kunjungan')));
            }else{
                $dataexc["no_kunjungan"] = $this->regex->_genRegex($this->input->post('no_kunjungan'),'RGXINT');
                $dataexc["no_registrasi"] = $this->regex->_genRegex($this->input->post('no_registrasi'),'RGXINT');
                $dataexc["no_mr"] = $this->regex->_genRegex($this->input->post('noMrHidden'),'RGXQSL');
                $dataexc["nama_pasien"] = $this->regex->_genRegex($this->input->post('nama_pasien_hidden'),'RGXQSL');
                $dataexc["diagnosa_awal"] = $this->regex->_genRegex($this->input->post('pl_diagnosa_awal'),'RGXQSL');
                $dataexc["dokter_pemeriksa"] = $this->regex->_genRegex($this->input->post('dr_merawat'),'RGXQSL');
                $dataexc["tgl_periksa"] = date('Y-m-d H:i:s');
                $dataexc["kode_bagian"] = $this->regex->_genRegex($this->input->post('kode_bagian'),'RGXQSL');

                $this->Pl_pelayanan_ri->save('th_riwayat_pasien', $dataexc);
            }
                        
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'type_pelayanan' => 'Diagnosa'));
            }
        
        }

    }

    public function process_add_pesan_vk(){

        // form validation
        $this->form_validation->set_rules('noMrHidden', 'No MR', 'trim|required');
        $this->form_validation->set_rules('pl_ri_kamar_vk', 'Kamar', 'trim|required');
        $this->form_validation->set_rules('pl_tgl_pesan', 'Tanggal', 'trim|required');
        

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

           // $id_pasien_vk = $this->master->get_max_number('ri_pasien_vk', 'id_pasien_vk');
      
            $dataexc = array(
                'tgl_masuk' => $this->tanggal->sqlDateForm($this->regex->_genRegex($this->input->post('pl_tgl_pesan'),'RGXQSL')), 
                'no_kamar_vk' =>  $this->regex->_genRegex($this->input->post('pl_ri_kamar_vk'),'RGXQSL'),
                /*form hidden input default*/
                'nama_pasien' => $this->regex->_genRegex($this->input->post('nama_pasien_hidden'),'RGXQSL'),
                'no_mr' => $this->regex->_genRegex($this->input->post('noMrHidden'),'RGXQSL'),
                'no_kunjungan' => $this->regex->_genRegex($this->input->post('no_kunjungan'),'RGXINT'),
                'no_registrasi' => $this->regex->_genRegex($this->input->post('no_registrasi'),'RGXINT'),
                'kode_ri' => $this->regex->_genRegex($this->input->post('kode_ri'),'RGXINT'),           
                'kode_bagian_asal' => $this->regex->_genRegex($this->input->post('kode_bagian_asal'),'RGXQSL'),
                'kode_klas' => $this->regex->_genRegex($this->input->post('kode_klas'),'RGXINT'),
                'flag_vk' => $this->regex->_genRegex(0,'RGXINT'),
            );

            //print_r($dataexc);die;

            $this->Pl_pelayanan_ri->save('ri_pasien_vk', $dataexc);
            
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'type_pelayanan' => 'Pesan VK'));
            }
        
        }

    }

    public function process_add_pesan_ok(){

        // form validation
        $this->form_validation->set_rules('noMrHidden', 'No MR', 'trim|required');
        $this->form_validation->set_rules('pl_tgl_pesan', 'Tanggal', 'trim|required');
        // $this->form_validation->set_rules('selected_time', 'Jam', 'trim|required');
        $this->form_validation->set_rules('pl_tindakan_pesan_ok', 'Nama Tindakan', 'trim|required');
        $this->form_validation->set_rules('pl_dokter_ok', 'Dokter', 'trim|required');

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

           // $id_pasien_vk = $this->master->get_max_number('ri_pasien_vk', 'id_pasien_vk');

           /*Tanggal Jam Pesanan */
           $tgl = $this->tanggal->sqlDateForm($this->input->post('pl_tgl_pesan'));
           $date = date_create($tgl.' '.date('H:i:s') );
           $jam_pesanan = date_format($date, 'Y-m-d H:i:s');

           /*cek kelas titipan */
            $kelas = ($this->input->post('klas_titipan'))?$this->input->post('klas_titipan'):$this->input->post('kode_klas');
           
            $dataexc = array(
                'tgl_pesan' => $jam_pesanan, 
                'jenis_layanan' =>  $this->regex->_genRegex($this->input->post('jenis_layanan_pesan_ok'),'RGXINT'),
                'dokter1' => $this->regex->_genRegex($this->input->post('pl_dokter_ok'),'RGXINT'),
                /*form hidden input default*/
                'kode_master_tarif_detail' => $this->regex->_genRegex($this->input->post('kode_master_tarif_detail'),'RGXINT'),
                'kode_klas' => $kelas,
                'no_mr' => $this->regex->_genRegex($this->input->post('noMrHidden'),'RGXQSL'),
                'no_kunjungan' => $this->regex->_genRegex($this->input->post('no_kunjungan'),'RGXINT'),
                'no_registrasi' => $this->regex->_genRegex($this->input->post('no_registrasi'),'RGXINT'),
                'kode_ri' => $this->regex->_genRegex($this->input->post('kode_ri'),'RGXINT'),           
                'kode_bagian' => $this->regex->_genRegex('030901','RGXQSL'),
            );

            //print_r($dataexc);die;

            $this->Pl_pelayanan_ri->save('ri_pesan_bedah', $dataexc);
            
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'type_pelayanan' => 'Pesan OK'));
            }
        
        }

    }

    public function process_add_pesan_pindah(){
              
        /*execution*/
        $this->db->trans_begin();           

        // $id_pasien_vk = $this->master->get_max_number('ri_pasien_vk', 'id_pasien_vk');
       
        $this->Pl_pelayanan_ri->update('ri_tc_rawatinap', array('status_pindah' => 1), array('kode_ri' => $this->regex->_genRegex($this->input->post('kode_ri'),'RGXINT')));
        
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
        }
        else
        {
            $this->db->trans_commit();
            echo json_encode(array('status' => 200, 'message' => 'Proses Pesan Pindah Berhasil Dilakukan', 'type_pelayanan' => 'Pesan Pindah'));
        }
        
    }

    public function process_cppt(){

        // echo '<pre>';print_r($_POST);die;
        // form validation
        $this->form_validation->set_rules('subjective', 'Subjective', 'trim|required');
        $this->form_validation->set_rules('objective', 'Objective', 'trim|required');
        $this->form_validation->set_rules('plan', 'Plan', 'trim|required');
        $this->form_validation->set_rules('assesment', 'Assesment', 'trim|required');
               

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

            $cppt_id = ($_POST['cppt_id'])?$_POST['cppt_id']:0;
            $tgl_jam = $_POST['cppt_tgl'].' '.$_POST['cppt_jam'];

            $dataexc = array(
                'cppt_tgl_jam' => $this->regex->_genRegex($tgl_jam,'RGXQSL'), 
                'cppt_ppa' => $this->regex->_genRegex($this->input->post('ppa'),'RGXQSL'), 
                'cppt_nama_ppa' => $this->regex->_genRegex($this->input->post('nama_ppa'),'RGXQSL'), 
                'cppt_subjective' => $this->regex->_genRegex($this->master->br2nl($this->input->post('subjective')),'RGXQSL'), 
                'cppt_objective' => $this->regex->_genRegex($this->master->br2nl($this->input->post('objective')),'RGXQSL'), 
                'cppt_assesment' => $this->regex->_genRegex($this->master->br2nl($this->input->post('assesment')),'RGXQSL'), 
                'cppt_plan' => $this->regex->_genRegex($this->master->br2nl($this->input->post('plan')),'RGXQSL'), 
                'kode_ri' => $this->regex->_genRegex($this->input->post('kode_ri'),'RGXQSL'), 
                'no_kunjungan' => $this->regex->_genRegex($this->input->post('no_kunjungan'),'RGXQSL'), 
                'no_registrasi' => $this->regex->_genRegex($this->input->post('no_registrasi'),'RGXQSL'), 
            );

            if( $cppt_id == 0 ){
                $dataexc['created_date'] = date('Y-m-d H:i:s');
                $dataexc['created_by'] = $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL');
                $dataexc['updated_date'] = date('Y-m-d H:i:s');
                $dataexc['updated_by'] = $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL');
                $this->db->insert('th_cppt', $dataexc);
                $newId = $this->db->insert_id();
            }else{
                $dataexc['updated_date'] = date('Y-m-d H:i:s');
                $dataexc['updated_by'] = $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL');
                $this->db->where('cppt_id', $cppt_id)->update('th_cppt', $dataexc);
                $newId = $cppt_id;
            }
                        
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'type_pelayanan' => 'cppt'));
            }
        
        }

    }

    public function delete_cppt()
    {
        $id=$this->input->post('ID')?$this->input->post('ID',TRUE):null;
        if($id!=null){
            $table = ($_POST['flag'] == 'resume')?'th_riwayat_pasien':'th_cppt';
            $kode = ($_POST['flag'] == 'resume')?'kode_riwayat':'cppt_id';
            if($this->db->where($kode, $id)->delete($table)){
                echo json_encode(array('status' => 200, 'message' => 'Proses Hapus Data Berhasil Dilakukan'));
            }else{
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Hapus Data Gagal Dilakukan'));
            }
        }else{
            echo json_encode(array('status' => 301, 'message' => 'Tidak ada item yang dipilih'));
        }
        
    }

    public function verif_cppt()
    {
        $id=$this->input->post('ID')?$this->input->post('ID',TRUE):null;
        // print_r($_POST);die;
        if($id!=null){
            $post = array(
                'is_verified' => $_POST['status_verif'],
                'verified_by' => ($_POST['status_verif'] != 0) ? $this->session->userdata('user')->fullname : '',
                'verified_date' => ($_POST['status_verif'] != 0) ? date('Y-m-d H:i:s') : '',
            );
            if($this->db->where('cppt_id', $id)->update('th_cppt', $post)){
                echo json_encode(array('status' => 200, 'message' => 'Proses Hapus Data Berhasil Dilakukan'));
            }else{
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Hapus Data Gagal Dilakukan'));
            }
        }else{
            echo json_encode(array('status' => 301, 'message' => 'Tidak ada item yang dipilih'));
        }
        
    }

    


    public function delete()
    {
        $id=$this->input->post('ID')?$this->input->post('ID',TRUE):null;
        if($id!=null){
            if($this->input->post('type')==1){
                if($this->Pl_pelayanan_ri->delete_by_id('ri_pasien_vk','id_pasien_vk',$id)){
                    echo json_encode(array('status' => 200, 'message' => 'Proses Hapus Data Berhasil Dilakukan'));
                }else{
                    echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Hapus Data Gagal Dilakukan'));
                }
            }else if($this->input->post('type')==2){
                if($this->Pl_pelayanan_ri->delete_by_id('ri_pesan_bedah','id_pesan_bedah',$id)){
                    echo json_encode(array('status' => 200, 'message' => 'Proses Hapus Data Berhasil Dilakukan'));
                }else{
                    echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Hapus Data Gagal Dilakukan'));
                }
            } 
        }else{
            echo json_encode(array('status' => 301, 'message' => 'Tidak ada item yang dipilih'));
        }
        
    }

    public function processPelayananSelesai(){

        // print_r($_POST);die;
        // form validation
        $this->form_validation->set_rules('no_mr', 'Pasien', 'trim|required');        
        $this->form_validation->set_rules('pl_anamnesa', 'Anamnesa', 'trim');        
        $this->form_validation->set_rules('pl_pemeriksaan', 'Pemeriksaan', 'trim');        
        $this->form_validation->set_rules('pl_anjuran_dokter', 'Anjuran Dokter', 'trim');        
        $this->form_validation->set_rules('pl_pengobatan', 'Pengobatan', 'trim');        
        $this->form_validation->set_rules('pl_diagnosa', 'Diagnosa', 'trim|required');        
        $this->form_validation->set_rules('pl_diagnosa_sekunder', 'Diagnosa Sekunder', 'trim');        
        $this->form_validation->set_rules('pl_tindakan_prosedur', 'Tindakan / Prosedur', 'trim');        
        $this->form_validation->set_rules('pl_alergi_obat', 'Alergi Obat', 'trim');        
        $this->form_validation->set_rules('pl_diet', 'Diet', 'trim');        
        $this->form_validation->set_rules('obat_diberikan', 'Obat yang diberikan', 'trim');        
        $this->form_validation->set_rules('tgl_kontrol_kembali', 'Obat yang diberikan', 'trim');        
        $this->form_validation->set_rules('no_registrasi', 'No Registrasi', 'trim|required');        
        $this->form_validation->set_rules('no_kunjungan', 'No Kunjungan', 'trim|required');        
        $this->form_validation->set_rules('kode_bagian_asal', 'Kode Bagian Asal', 'trim|required');        
        //$this->form_validation->set_rules('cara_keluar', 'Cara Keluar Pasien', 'trim|required');        
        $this->form_validation->set_rules('pasca_pulang', 'Pasca Pulang', 'trim|required');        

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
            $kode_ri = $this->regex->_genRegex($this->input->post('kode_ri'),'RGXINT');
            $no_mr = $this->form_validation->set_value('no_mr');

            /*cek kunjungan poli */
            $cek_poli = $this->Pl_pelayanan_ri->cek_poli_pulang($no_registrasi);
            if($cek_poli){
                echo json_encode(array('status' => 301, 'message' => 'Maaf pasien masih dalam antrian Poli !'));
                exit;
            }

            /*cek kunjungan vk */
            $cek_vk = $this->Pl_pelayanan_ri->cek_vk_pulang($kode_ri);
            if($cek_vk){
                echo json_encode(array('status' => 301, 'message' => 'Maaf pasien masih dalam antrian VK !'));
                exit;
            }

             /*cek kunjungan ok */
             $cek_ok = $this->Pl_pelayanan_ri->cek_ok_pulang($kode_ri);

             if($cek_ok){
                 echo json_encode(array('status' => 301, 'message' => 'Maaf pasien masih dalam antrian OK !'));
                 exit;
             }

            /*cek pesan resep*/
            $cek_resep = $this->Pl_pelayanan_ri->cek_resep_progress($no_kunjungan);

            /*jika sudah tidak ada resep yang belum diproses, maka lanjutkan*/
            if($cek_resep > 0){
                echo json_encode(array('status' => 301, 'message' => 'Masih ada Resep yang belum selesai'));
                exit;
            }

            /*proses utama pasien selesai*/
            if( $_POST['status_pulang'] == 0 ) : 
                /*cek pemeriksaan pm */
                $cek_pm = $this->Pl_pelayanan_ri->cek_pemeriksaan_pm($no_registrasi);

                //print_r($cek_pm);die;

                if($cek_pm){
                    foreach ($cek_pm as $value) {
                        # code...
                        $this->Pl_pelayanan_ri->delete_by_id('pm_tc_penunjang','kode_penunjang',$value->kode_penunjang);
                        $this->Pl_pelayanan_ri->delete_by_id('tc_trans_pelayanan','kode_trans_pelayanan',$value->kode_trans_pelayanan);
                    }
                }
    
                /*update mt_ruangan*/
                $this->Pl_pelayanan_ri->update('mt_ruangan', array('status' => NULL), array('kode_ruangan' => $this->regex->_genRegex($this->input->post('kode_ruangan'),'RGXINT') ) );
                /*save logs*/
                //$this->logs->save('pl_tc_poli', $no_kunjungan, 'update pl_tc_poli Modul Pelayanan', json_encode($arrPlTcPoli),'no_kunjungan');

                /*cek if meninggal */
                if ( $this->form_validation->set_value('pasca_pulang') == 'Meninggal' ){
                    $ket_keluar = 3;
                    $status_keluar=4;
                    $status_meninggal=1;

                    /*update mt_master_pasien */
                    $mt_master_pasien["status_meninggal"] = $status_meninggal;
                    $this->Pl_pelayanan_ri->update('mt_master_pasien', $mt_master_pasien, array('no_mr' => $no_mr ) );
                    /*save logs*/
                    $this->logs->save('mt_master_pasien', $no_mr, 'update mt_master_pasien modul pelayanan', json_encode($mt_master_pasien),'no_mr');
                }else{
                    $ket_keluar = 4;
                    $status_keluar=3;
                    $status_meninggal="";
                }

                /*update ri_tc_riwayat_kelas */
                $riwayat_kelas = array(
                    'tgl_pindah' => date('Y-m-d H:i:s'),
                    'ket_keluar' => $ket_keluar,
                );
                $this->Pl_pelayanan_ri->update('ri_tc_riwayat_kelas', $riwayat_kelas, array('kode_ri' => $kode_ri, 'tgl_pindah' => NULL ) );

                /*update ri_tc_rwatinap */
                $ri_tc_rawatinap = array(
                    'tgl_keluar' => date('Y-m-d H:i:s'),
                    'status_pulang' => 1,
                    'user_plg' => $this->session->userdata('user')->user_id,
                );
                $this->db->update('ri_tc_rawatinap', $ri_tc_rawatinap, array('kode_ri' => $kode_ri) );

                /*insert biaya administrasi */

                /*cek biaya farmasi*/
                $biaya_obat = $this->Pl_pelayanan_ri->cek_biaya_obat($no_registrasi); 
                if($biaya_obat){
                    $_bi_apoA = $biaya_obat->bi_apo;
                    $_bi_lainA = $biaya_obat->bi_lain;
                }else{
                    $_bi_apoA = 0;
                    $_bi_lainA = 0;
                }
                
                $biaya_obat_kredit = $this->Pl_pelayanan_ri->cek_biaya_obat_kredit($no_registrasi); 
                if($biaya_obat_kredit){
                    $_bi_apoB = $biaya_obat_kredit->bi_apo;
                    $_bi_lainB = $biaya_obat_kredit->bi_lain;
                }else{
                    $_bi_apoB = 0;
                    $_bi_lainB = 0;
                }
            
                $bi_apo = ( $_bi_apoA + $_bi_lainA ) - ( $_bi_apoB + $_bi_lainB ) ;
                $billApo = $bi_apo;

                /*cek semua biaya by reg */
                $biaya_by_registrasi = $this->Pl_pelayanan_ri->cek_biaya_reg($no_registrasi); 
                $biaya_rs = $biaya_by_registrasi->biy_rs + $biaya_by_registrasi->biy_lain;
                $biaya_dr1 = $biaya_by_registrasi->biy_dr1;
                $biaya_dr2 = $biaya_by_registrasi->biy_dr2 + $biaya_by_registrasi->biy_dr3;
                
                $total_adm = ($biaya_rs + $biaya_dr1 + $biaya_dr2 + $billApo);
                $materai = ($total_adm > 5000000) ? 10000 : 0;
                $biy_adm_x = 0.06 * ($total_adm + $materai);
                $biy_adm = ($biy_adm_x > 2400000) ? 2400000 : $biy_adm_x;
                

                /*save tc_trans_pelayanan */
                $kode_tc_trans_pelayanan = $this->master->get_max_number('tc_trans_pelayanan', 'kode_trans_pelayanan');
                $data_tc_trans_pelayanan = array(
                    'kode_trans_pelayanan' => $kode_tc_trans_pelayanan,
                    'no_kunjungan' => $no_kunjungan,
                    'no_registrasi' => $no_registrasi,
                    'no_mr' => $no_mr,
                    'nama_pasien_layan' => $this->input->post('nama_pasien_layan'),
                    'kode_perusahaan' => $this->regex->_genRegex($this->input->post('kode_perusahaan'),'RGXINT'),
                    'kode_kelompok' => $this->regex->_genRegex($this->input->post('kode_kelompok'),'RGXINT'),
                    'tgl_transaksi' => date('Y-m-d H:i:s'),
                    'jenis_tindakan' => 2,
                    'nama_tindakan' => 'Biaya Administrasi',
                    'bill_rs' =>  $biy_adm,
                    'jumlah' =>  1,
                    'kode_bagian' => $this->regex->_genRegex($this->input->post('kode_bagian'),'RGXQSL'),
                    'kode_bagian_asal' => $this->regex->_genRegex($this->input->post('kode_bagian'),'RGXQSL'),
                    'status_selesai' => 1,
                    'id_dd_user' => $this->session->userdata('user')->user_id,
                ); 
                $this->Pl_pelayanan_ri->save('tc_trans_pelayanan', $data_tc_trans_pelayanan);
                /*save logs tc_trans_pelayanan*/
                $this->logs->save('tc_trans_pelayanan', $kode_tc_trans_pelayanan, 'insert tc_trans_pelayanan modul pelayanan', json_encode($data_tc_trans_pelayanan),'kode_trans_pelayanan');

                /*update kunjungan by no_kunjungan */
                $this->daftar_pasien->pulangkan_pasien($no_kunjungan,$status_keluar);

                /*update kunjungan by no_registrasi */
                $kunjungan["tgl_keluar"] = date('Y-m-d H:i:s');
                $kunjungan["status_keluar"] = ($status_keluar=="")?3:$status_keluar;
                $this->db->update('tc_kunjungan', $kunjungan, array('no_registrasi' => $no_registrasi,'no_mr' => $no_mr,'tgl_keluar' => NULL) );
                /*save logs tc_kunjungan*/
                $this->logs->save('tc_kunjungan', $no_kunjungan, 'update tc_kunjungan modul pelayanan', json_encode($kunjungan),'no_kunjungan');

                /*update bagian_keluar */
                $this->Pl_pelayanan_ri->update('tc_registrasi', array('kode_bagian_keluar' => $this->input->post('kode_bagian') ), array('no_registrasi' => $no_registrasi ) );

            endif; 
            
            /*insert log diagnosa pasien th_riwayat pasien*/
            $riwayat_diagnosa = array(
                'no_registrasi' => $this->form_validation->set_value('no_registrasi'),
                'no_kunjungan' => $no_kunjungan,
                'no_mr' => $this->form_validation->set_value('no_mr'),
                'nama_pasien' => $this->input->post('nama_pasien_layan'),
                'kode_bagian' => $this->form_validation->set_value('kode_bagian'),
                'tgl_periksa' => date('Y-m-d H:i:s'),
                'kategori_tindakan' => 3,
                'dokter_pemeriksa' => $this->input->post('dr_merawat'),
                'kode_bagian' => $this->input->post('kode_bagian_asal'),
                'anamnesa' => $this->form_validation->set_value('pl_anamnesa'),
                'pengobatan' => $this->form_validation->set_value('pl_pengobatan'),
                'pemeriksaan' => $this->form_validation->set_value('pl_pemeriksaan'),
                'anjuran_dokter' => $this->form_validation->set_value('pl_anjuran_dokter'),
                'diagnosa_awal' => $this->form_validation->set_value('pl_diagnosa'),
                'kode_icd_diagnosa' => $this->input->post('pl_diagnosa_hidden'),
                'diagnosa_akhir' => $this->form_validation->set_value('pl_diagnosa'),
                'diagnosa_sekunder' => $this->form_validation->set_value('pl_diagnosa_sekunder'),
                'tindakan_prosedur' => $this->form_validation->set_value('pl_tindakan_prosedur'),
                'alergi_obat' => $this->form_validation->set_value('pl_alergi_obat'),
                'diet' => $this->form_validation->set_value('pl_diet'),
                'obat_diberikan' => $this->form_validation->set_value('obat_diberikan'),
                'tgl_kontrol_kembali' => $this->form_validation->set_value('tgl_kontrol_kembali'),
                'cara_keluar' => $this->input->post('cara_keluar'),
                'pasca_pulang' => $this->input->post('pasca_pulang'),

            );

            if($this->input->post('kode_riwayat')==0){
                $this->Pl_pelayanan_ri->save('th_riwayat_pasien', $riwayat_diagnosa);
            }else{
                $this->Pl_pelayanan_ri->update('th_riwayat_pasien', $riwayat_diagnosa, array('kode_riwayat' => $this->input->post('kode_riwayat') ) );
            }

            // generate file resume medis
            // $this->generateResumeMedisRI($no_mr, $no_registrasi);


            
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'type_pelayanan' => 'pulangkan_pasien'));
            }

        
        }

    }

    public function generateResumeMedisRI($no_mr, $no_registrasi){
        // generate resume medis
        $filename = 'Resume_Medis_RI-'.$no_mr.'-'.$no_registrasi.'-'.date('dmY').'';
        $this->cbpModule->generateSingleDoc($filename);
    }

    public function rollback()
    {   
        $this->db->trans_begin();  

        /*update tc_registrasi*/
        // $reg_data = array('tgl_jam_keluar' => NULL, 'kode_bagian_keluar' => NULL, 'status_batal' => NULL );
        // $this->db->update('tc_registrasi', $reg_data, array('no_registrasi' => $_POST['no_registrasi'] ) );
        // $this->logs->save('tc_registrasi', $_POST['no_registrasi'], 'update tc_registrasi Modul Pelayanan', json_encode($reg_data),'no_registrasi');


        /*tc_kunjungan*/
        $kunj_data = array('tgl_keluar' => NULL, 'status_keluar' => NULL, 'status_batal' => NULL );
        $this->db->update('tc_kunjungan', $kunj_data, array('no_registrasi' => $_POST['no_registrasi'], 'no_kunjungan' => $_POST['no_kunjungan'] ) );
        $this->logs->save('tc_kunjungan', $_POST['no_kunjungan'], 'update tc_kunjungan Modul Pelayanan', json_encode($kunj_data),'no_kunjungan');

        /*pl_tc_poli*/
        $data_ri = array('tgl_keluar' => NULL, 'status_pulang' => 0, 'user_plg' => NULL );
        $this->db->update('ri_tc_rawatinap', $data_ri, array('no_kunjungan' => $_POST['no_kunjungan']) );
        //$this->logs->save('ri_tc_rawatinap', $_POST['no_kunjungan'], 'update ri_tc_rawatinap Modul Pelayanan', json_encode($data_ri),'no_kunjungan');

        /*tc_trans_pelayanan*/
        $trans_data = array('status_selesai' => 2, 'status_nk' => NULL, 'kode_tc_trans_kasir' => NULL );
        $this->db->update('tc_trans_pelayanan', $trans_data, array('no_kunjungan' => $_POST['no_kunjungan'], 'no_registrasi' => $_POST['no_registrasi'] ) );
        $this->db->delete('tc_trans_pelayanan', array('no_kunjungan' => $_POST['no_kunjungan'], 'jenis_tindakan' => 2, 'nama_tindakan' => 'Biaya Administrasi'));


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

    public function export_pdf_cppt()
    {   
        // box data
        $data = array();
        $list = [];
        $list = $this->Pl_pelayanan_ri->get_cppt_by_id($_GET['id']);
        $data['data'] = $list;
        // echo '<pre>'; print_r($data);die;
        $this->load->view('Pl_pelayanan_ri/export_pdf_cppt', $data);
    }

    public function get_cppt_dt(){

        $query = $this->db->get_where('view_cppt', array('id' => $_GET['id']))->row();
        // echo "<pre>"; print_r($query);die;
        // echo "<pre>"; print_r($convert_to_array);die;
        if($query->flag == 'cppt'){
            if($query->jenis_form != null){
                $convert_to_array = explode('|', $query->value_form);
                for($i=0; $i < count($convert_to_array ); $i++){
                    $key_value = explode('=', $convert_to_array [$i]);
                    $end_array[trim($key_value[0])] = isset($key_value [1])?$key_value [1]:'';
                }
                $data = [
                    "cppt_id" => $_GET['id'],
                    "value_form" => $end_array,
                    "result" => $query,
                    "jenis_form" => 'form_'.$query->jenis_form,
                ];
                // echo "<pre>"; print_r($data);die;
                $html = $this->load->view('Pl_pelayanan/form_'.$query->jenis_form.'', $data, true);
                echo json_encode(array('html' => $html, 'result' => $query, 'value_form' => $end_array));
            }else{
                echo json_encode($query);
            }
            
        }else{
            echo json_encode($query);
        }
        
        

        // echo json_encode($result);
    }

    public function show_catatan_pengkajian($cppt_id){
        $query = $this->db->get_where('view_cppt', array('id' => $cppt_id))->row();
        if($query->flag == 'cppt'){
            if($query->jenis_form != null){
                $convert_to_array = explode('|', $query->value_form);
                for($i=0; $i < count($convert_to_array ); $i++){
                    $key_value = explode('=', $convert_to_array [$i]);
                    $end_array[trim($key_value[0])] = isset($key_value [1])?$key_value [1]:'';
                }
                $data = [
                    "cppt_id" => $cppt_id,
                    "value_form" => $end_array,
                    "result" => $query,
                    "jenis_form" => 'form_'.$query->jenis_form,
                ];
                // echo "<pre>"; print_r($data);die;
                $data["html_form"] = $this->load->view('Pl_pelayanan/'.$data['jenis_form'].'', $data, true);

                $html = $this->load->view('Pl_pelayanan/form_show_pengkajian', $data, true);
                echo json_encode(array('html' => $html, 'result' => $query, 'value_form' => $end_array));
            }else{
                echo json_encode($query);
            }
            
        }else{
            echo json_encode($query);
        }

        // $btn_print = '<div class="pull-right"><a href="'.base_url().'Templates/Export_data/exportContent?type=pdf&flag=catatan_pengkajian&mod=Pl_pelayanan_ri&cppt_id='.$cppt_id.'&paper=P" target="_blank" class="btn btn-xs btn-primary"><i class="fa fa-print"></i> Print PDF</a></div><br>';
        // echo json_encode(array('html' => $btn_print.$data->catatan_pengkajian));
    }

    public function get_content_data(){
        $data = $this->db->get_where('th_cppt', array('cppt_id' => $_GET['cppt_id']))->row();
        // echo '<pre>'; print_r($data->catatan_pengkajian);die;
        return $data;
    }

    public function html_content($params){
        $this->load->module('Templates/Templates.php');
        $temp = new Templates;
        $data = json_decode($this->Csm_billing_pasien->getDetailData($params->no_registrasi));
        $data->nama_ppa = $params->cppt_nama_ppa;
        $data->kode_dr = $params->cppt_kode_dr;

        return $params->catatan_pengkajian;
    }

    public function process_note()
    {
        //  print_r($_POST);die;
         $this->load->library('form_validation');
         $val = $this->form_validation;
     
         $val->set_rules('no_mr', 'MR Pasien', 'trim|required', array('required' => 'MR Pasien tidak ditemukan'));
         $val->set_rules('no_registrasi', 'No Registrasi', 'trim|required');
         $val->set_rules('no_kunjungan', 'No Kunjungan', 'trim|required');
 
         $val->set_message('required', "Silahkan isi field \"%s\"");
 
         if ($val->run() == FALSE)
         {
             $val->set_error_delimiters('<div style="color:white">', '</div>');
             echo json_encode(array('status' => 301, 'message' => validation_errors()));
         }
         else
         {                       
 
            $this->db->trans_begin();
            
            /*insert drawing*/
            $dataexc = [
                'no_registrasi' => $_POST['no_registrasi'],
                'no_kunjungan' => $_POST['no_kunjungan'],
                'no_mr' => $_POST['no_mr'],
                'notes' => $_POST['paramsSignature'],
                'jenis_catatan_draw' => $_POST['note_type'],
                'created_date' => date('Y-m-d H:i:s'),
                'created_by' => $_POST['created_name'],
                'type_owner' => $_POST['created_by'],
            ];
            $this->db->insert('th_drawing_notes', $dataexc);

             if ($this->db->trans_status() === FALSE)
             {
                 $this->db->trans_rollback();
                 echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
             }
             else
             {
                 $this->db->trans_commit();
                 echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'no_mr' => $_POST['no_mr_notes'], 'ttd' => $_POST['paramsSignature']));
             }
 
         }
    }

    public function process_monitoring()
    {
        //  print_r($_POST);die;
         $this->load->library('form_validation');
         $val = $this->form_validation;
     
         $val->set_rules('no_mr', 'MR Pasien', 'trim|required', array('required' => 'MR Pasien tidak ditemukan'));
         $val->set_rules('no_registrasi', 'No Registrasi', 'trim|required');
         $val->set_rules('no_kunjungan', 'No Kunjungan', 'trim|required');
 
         $val->set_message('required', "Silahkan isi field \"%s\"");
 
         if ($val->run() == FALSE)
         {
             $val->set_error_delimiters('<div style="color:white">', '</div>');
             echo json_encode(array('status' => 301, 'message' => validation_errors()));
         }
         else
         {                       
 
            $this->db->trans_begin();
            
            /*insert drawing*/
            $dataexc = [
                'tgl_monitor' => $_POST['tgl_monitor'],
                'jam_monitor' => $_POST['jam_monitor'],
                'no_registrasi' => $_POST['no_registrasi'],
                'no_kunjungan' => $_POST['no_kunjungan'],
                'no_mr' => $_POST['no_mr'],
                'kesadaran' => isset($_POST['kesadaran'])?$_POST['kesadaran']:'',
                'cvp' => isset($_POST['cvp'])?$_POST['cvp']:'',
                'infus' => isset($_POST['infus'])?$_POST['infus']:'',
                'urine' => isset($_POST['urine'])?$_POST['urine']:'',
                'obat' => isset($_POST['obat'])?$_POST['obat']:'',
                'sistolik' => isset($_POST['sistolik'])?$_POST['sistolik']:'',
                'diastolik' => isset($_POST['diastolik'])?$_POST['diastolik']:'',
                'nd' => isset($_POST['nd'])?$_POST['nd']:'',
                'sh' => isset($_POST['sh'])?$_POST['sh']:'',
                'oral' => isset($_POST['oral'])?$_POST['oral']:'',
                'nafas' => isset($_POST['pernafasan'])?$_POST['pernafasan']:'',
                'jumlah_a' => isset($_POST['jumlah'])?$_POST['jumlah']:'',
                'bak' => isset($_POST['bak'])?$_POST['bak']:'',
                'bab' => isset($_POST['bab'])?$_POST['bab']:'',
                'muntah' => isset($_POST['muntah'])?$_POST['muntah']:'',
                'drainage' => isset($_POST['drainage'])?$_POST['drainage']:'',
                'jumlah_b' => isset($_POST['jumlah_b'])?$_POST['jumlah_b']:'',
                'balance_cairan' => isset($_POST['cairan'])?$_POST['cairan']:'',
                'diet' => isset($_POST['diet'])?$_POST['diet']:'',
                'catatan' => isset($_POST['catatan'])?$_POST['catatan']:'',
                'type' => isset($_POST['tipe_monitoring'])?$_POST['tipe_monitoring']:'UMUM',
                'created_date' => date('Y-m-d H:i:s'),
                'created_by' => $this->session->userdata('user')->fullname,

            ];
            $this->db->insert('th_monitor_perkembangan_pasien_ri', $dataexc);

             if ($this->db->trans_status() === FALSE)
             {
                 $this->db->trans_rollback();
                 echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan', 'type' => $_POST['tipe_monitoring'], 'type_pelayanan' => 'monitoring'));
             }
             else
             {
                 $this->db->trans_commit();
                 echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan'));
             }
 
         }
    }

    public function process_pemberian_obat()
    {
        //  print_r($_POST);die;
         $this->load->library('form_validation');
         $val = $this->form_validation;
     
         $val->set_rules('no_mr', 'MR Pasien', 'trim|required', array('required' => 'MR Pasien tidak ditemukan'));
         $val->set_rules('no_registrasi', 'No Registrasi', 'trim|required');
         $val->set_rules('no_kunjungan', 'No Kunjungan', 'trim|required');
         $val->set_rules('nama_obat', 'Nama Obat', 'trim|required');
         $val->set_rules('dosis', 'Dosis', 'trim|required');
         $val->set_rules('frek', 'Frekuensi', 'trim|required');
         $val->set_rules('jenis_terapi', 'Jenis Terapi', 'trim|required');
         $val->set_rules('waktu_pemberian_obat', 'Waktu', 'trim|required');
 
         $val->set_message('required', "Silahkan isi field \"%s\"");
 
         if ($val->run() == FALSE)
         {
             $val->set_error_delimiters('<div style="color:white">', '</div>');
             echo json_encode(array('status' => 301, 'message' => validation_errors()));
         }
         else
         {                       
 
            $this->db->trans_begin();
            
            /*insert drawing*/
            $dataexc = [
                'tgl_obat' => $_POST['tgl_obat'],
                'jam_obat' => $_POST['jam_obat'],
                'no_registrasi' => $_POST['no_registrasi'],
                'no_kunjungan' => $_POST['no_kunjungan'],
                'no_mr' => $_POST['no_mr'],
                'nama_obat' => isset($_POST['nama_obat'])?$_POST['nama_obat']:'',
                'kode_brg' => isset($_POST['kode_brg'])?$_POST['kode_brg']:'',
                'dosis' => isset($_POST['dosis'])?$_POST['dosis']:'',
                'frek' => isset($_POST['frek'])?$_POST['frek']:'',
                'rute' => isset($_POST['rute'])?$_POST['rute']:'',
                'jenis_terapi' => isset($_POST['jenis_terapi'])?$_POST['jenis_terapi']:'',
                'waktu' => isset($_POST['waktu_pemberian_obat'])?$_POST['waktu_pemberian_obat']:'',
                'created_date' => date('Y-m-d H:i:s'),
                'created_by' => $this->session->userdata('user')->fullname,

            ];
            $this->db->insert('th_monitor_pemberian_obat', $dataexc);

             if ($this->db->trans_status() === FALSE)
             {
                 $this->db->trans_rollback();
                 echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan', 'type' => $_POST['tipe_monitoring'], 'type_pelayanan' => 'monitoring'));
             }
             else
             {
                 $this->db->trans_commit();
                 echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan'));
             }
 
         }
    }

    public function process_askep()
    {
        //  print_r($_POST);die;
         $this->load->library('form_validation');
         $val = $this->form_validation;
     
         $val->set_rules('no_mr', 'MR Pasien', 'trim|required', array('required' => 'MR Pasien tidak ditemukan'));
         $val->set_rules('no_registrasi', 'No Registrasi', 'trim|required');
         $val->set_rules('no_kunjungan', 'No Kunjungan', 'trim|required');
         $val->set_rules('catatan_askep', 'Catatan Asuhan Keperawatan', 'trim|required');
         $val->set_rules('jenis_catatan_askep', 'Jenis Catatan', 'trim|required');
 
         $val->set_message('required', "Silahkan isi field \"%s\"");
 
         if ($val->run() == FALSE)
         {
             $val->set_error_delimiters('<div style="color:white">', '</div>');
             echo json_encode(array('status' => 301, 'message' => validation_errors()));
         }
         else
         {                       
 
            $this->db->trans_begin();
            
            /*insert drawing*/
            $dataexc = [
                'tgl_askep' => $_POST['tgl_askep'],
                'jam_askep' => $_POST['jam_askep'],
                'no_registrasi' => $_POST['no_registrasi'],
                'no_kunjungan' => $_POST['no_kunjungan'],
                'no_mr' => $_POST['no_mr'],
                'catatan_askep' => isset($_POST['catatan_askep'])?$_POST['catatan_askep']:'',
                'jenis_catatan' => isset($_POST['jenis_catatan_askep'])?$_POST['jenis_catatan_askep']:'',
                'created_date' => date('Y-m-d H:i:s'),
                'created_by' => $this->session->userdata('user')->fullname,

            ];
            $this->db->insert('th_asuhan_keperawatan', $dataexc);

             if ($this->db->trans_status() === FALSE)
             {
                 $this->db->trans_rollback();
                 echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan', 'type' => $_POST['tipe_monitoring']));
             }
             else
             {
                 $this->db->trans_commit();
                 echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan'));
             }
 
         }
    }

    public function update_status_dt_monitoring()
    {
        $id=$this->input->get('ID')?$this->input->get('ID',TRUE):null;
        if($id!=null){
            if($this->db->where('id', $id)->update($_GET['table'], ['is_deleted' => $_GET['deleted']])){
                if($_GET['table'] == 'th_drawing_notes'){
                    $this->db->delete($_GET['table'], ['id' => $id]);
                }
                echo json_encode(array('status' => 200, 'message' => 'Proses Hapus Data Berhasil Dilakukan'));
            }else{
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Hapus Data Gagal Dilakukan'));
            }
        }else{
            echo json_encode(array('status' => 301, 'message' => 'Tidak ada item yang dipilih'));
        }
        
    }

    public function get_content_chart_monitoring(){

        $output = http_build_query($_GET) . "\n";
        $data[6] = array(
            'nameid' => 'graph-trend-kunjungan',
            'style' => 'line',
            'col_size' => 12,
            'url' => 'pelayanan/Pl_pelayanan_ri/content_chart_data?prefix=1&TypeChart=line&style=6&'.$output.'',
        );
        echo json_encode($data);
    }

    public function content_chart_data(){
        echo json_encode($this->Pl_pelayanan_ri->get_content_chart_data($_GET), JSON_NUMERIC_CHECK);
    }

    public function show_drawing($id){
        $dt = $this->db->get_where('th_drawing_notes', ['id' => $id])->row();
        $data = [
            'draw' => $dt,
        ];
        $this->load->view('pelayanan/Pl_pelayanan_ri/view_drawing', $data);
    }


}

/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
