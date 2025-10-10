<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pl_pelayanan_vk extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'pelayanan/Pl_pelayanan_vk');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('Pl_pelayanan_vk_model', 'Pl_pelayanan_vk');
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

    }

    public function index() { 
        /*define variable data*/
        $data = array(
            'sess_kode_bagian' => '030501',
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show()
        );

        $this->load->view('Pl_pelayanan_vk/index', $data);
    }

    public function index_riwayat() { 
        /*define variable data*/
        $data = array(
            'sess_kode_bagian' => '030501',
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show()
        );

        $this->load->view('Pl_pelayanan_vk/index_riwayat', $data);
    }

    public function form($id, $no_kunjungan)
    {
         /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Pl_pelayanan_vk/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*get value by id*/
        $data['value'] = $this->Pl_pelayanan_vk->get_by_id($id);
        //print_r($this->db->last_query());
        //echo '<pre>';print_r($id);die;
        $data['riwayat'] = $this->Pl_pelayanan_vk->get_riwayat_pasien_by_id($no_kunjungan);
        $kunjungan = $this->Reg_pasien->get_detail_kunjungan_by_no_kunjungan($no_kunjungan);
        //$data['transaksi'] = $this->Pl_pelayanan_vk->get_transaksi_pasien_by_id($no_kunjungan);
        /*variable*/
         /*type*/
        $kode_klas = $data['value']->kode_klas;

        $data['no_mr'] = $data['value']->no_mr;
        $data['id'] = $id;
        $data['kode_klas'] = $kode_klas;
        $data['kode_profit'] = 2000;
        $data['no_kunjungan'] = $no_kunjungan;
        // echo '<pre>';print_r($data);die;
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Pl_pelayanan_vk/form', $data);
    }

    public function form_main($id, $no_kunjungan)
    {
         /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Pl_pelayanan_vk/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*get value by id*/
        $data['value'] = $this->Pl_pelayanan_vk->get_by_id($id);
        //print_r($this->db->last_query());
        // echo '<pre>';print_r($data);die;
        $data['riwayat'] = $this->Pl_pelayanan_vk->get_riwayat_pasien_by_id($no_kunjungan);
        $kunjungan = $this->Reg_pasien->get_detail_kunjungan_by_no_kunjungan($no_kunjungan);
        //$data['transaksi'] = $this->Pl_pelayanan_vk->get_transaksi_pasien_by_id($no_kunjungan);
        /*variable*/
         /*type*/
        $kode_klas = $data['value']->kode_klas;

        $data['no_mr'] = $data['value']->no_mr;
        $data['id'] = $id;
        $data['kode_klas'] = $kode_klas;
        $data['kode_profit'] = 2000;
        $data['no_kunjungan'] = $no_kunjungan;
        // echo '<pre>';print_r($data);die;
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Pl_pelayanan_vk/form_main', $data);
    }

    public function tindakan($id='', $no_kunjungan='')
    {
         /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Pl_pelayanan_vk/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*get value by id*/
        $data['value'] = $this->Pl_pelayanan_vk->get_by_id($id); //echo '<pre>'; print_r($this->db->last_query());die;
        /*mr*/
        /*type*/
        $kode_klas = 16;

        $data['type'] = $_GET['type'];
        if(isset($_GET['cito'])) $data['cito'] = $_GET['cito'];
        $data['no_mr'] = $data['value']->no_mr;
        $data['no_kunjungan'] = $no_kunjungan;
        $data['id_pasien_vk'] = $id;
        $data['status_pulang'] = ($data['value']->status_pulang > 0)?1:0;
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

    public function form_bayi()
    {
         /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Pl_pelayanan_vk/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$_GET['no_mr_ibu']);
        /*get value by id*/
        $data['value'] = $this->Pl_pelayanan_vk->get_data_bayi($_GET['no_mr_ibu']); 
        $data['orgtuaby'] = $this->db->get_where('mt_master_pasien', array('no_mr' => $_GET['no_mr_ibu']))->row(); 
        // echo '<pre>'; print_r($data);die;
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Pl_pelayanan_vk/form_bayi', $data);
    }

    public function diagnosa($id='', $no_kunjungan='')
    {
         /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Pl_pelayanan_vk/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*get value by id*/
        $data['value'] = $this->Pl_pelayanan_vk->get_by_id($id); 
        $data['riwayat'] = $this->Pl_pelayanan->get_riwayat_pasien_by_id($no_kunjungan);
        /*mr*/
        /*type*/
        $kode_klas = 16;

        $data['type'] = $_GET['type'];
        if(isset($_GET['cito'])) $data['cito'] = $_GET['cito'];
        $data['no_mr'] = $data['value']->no_mr;
        $data['no_kunjungan'] = $no_kunjungan;
        $data['id_pasien_vk'] = $id;
        $data['status_pulang'] = ($data['value']->status_pulang > 0)?1:0;
        $data['kode_klas'] = $kode_klas;
        $data['sess_kode_bag'] = ($_GET['kode_bag'])?$_GET['kode_bag']:$this->session->userdata('kode_bagian');
        // echo '<pre>'; print_r($data);die;
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Pl_pelayanan_vk/form_diagnosa', $data);
    }

    public function form_end_visit()
    {
        $data = array(
            'no_mr' => isset($_GET['no_mr'])?$_GET['no_mr']:'',
            'id_pasien_vk' => isset($_GET['id'])?$_GET['id']:'',
            'no_kunjungan' => isset($_GET['no_kunjungan'])?$_GET['no_kunjungan']:'',
            );
        /*load form view*/
        $this->load->view('Pl_pelayanan_vk/form_end_visit', $data);
    }


    public function get_data()
    {
        /*akan di filter berdasarkan pasien pada klinik masing2*/
        /*get data from model*/
        $list = $this->Pl_pelayanan_vk->get_datatables();
        // echo '<pre>';print_r($list);die;
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
            $trans_kasir = $this->Pl_pelayanan_vk->get_transaksi_pasien_by_id($row_list->no_kunjungan);
            $rollback_btn = ($trans_kasir!=0)?'<li><a href="#" onclick="rollback('.$row_list->no_registrasi.','.$row_list->no_kunjungan.')">Rollback</a></li>':'';
            // $rollback_btn = '<li><a href="#" onclick="rollback('.$row_list->no_registrasi.','.$row_list->no_kunjungan.')">Rollback</a></li>';
            
                        
            $row[] = '<div class="center"><div class="btn-group">
                        <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">
                            <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-inverse">
                            '.$rollback_btn.'                        
                            <li><a href="#" onclick="show_modal('."'registration/reg_pasien/view_detail_resume_medis/".$row_list->no_registrasi."'".', '."'RESUME MEDIS'".')">Selengkapnya</a></li>
                        </ul>
                    </div></div>';

            $row[] = '<div class="center"><a href="#" onclick="getMenu('."'pelayanan/Pl_pelayanan_vk/form/".$row_list->id_pasien_vk."/".$row_list->no_kunjungan."'".')" style="font-weight: bold; color: blue">'.$row_list->no_kunjungan.'</a></div>';
            $row[] = '<div class="center">'.$row_list->no_mr.'</div>';
            $row[] = strtoupper($row_list->nama_pasien);
            $row[] = ($row_list->nama_perusahaan)?$row_list->nama_perusahaan:$row_list->nama_kelompok;
            $row[] = $this->tanggal->formatDateTime($row_list->tgl_masuk);
            $row[] = $row_list->nama_pegawai;
            $row[] = $row_list->nama_bagian.'/ '.$row_list->nama_klas;
            // $row[] = '<div class="center">'.$row_list->fullname.'</div>';

            if($row_list->status_batal == 1){
                $status_periksa = '<label class="label label-danger"><i class="fa fa-times"></i> Batal Kunjungan</label>';
            }else{
                if($row_list->tgl_keluar_vk == NULL || empty($row_list->tgl_keluar_vk)){
                    $status_periksa = '<label class="label label-warning"><i class="fa fa-info-circle"></i> Belum diperiksa</label>';
                }else {
                    $status_periksa = ($trans_kasir==0)?'<label class="label label-info"><i class="fa fa-money"></i> Lunas</label>':'<label class="label label-success"><i class="fa fa-check-circle"></i> Selesai</label>';
                }  
            }

            $row[] = '<div class="center">'.$status_periksa.'</div>';
           
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Pl_pelayanan_vk->count_all(),
                        "recordsFiltered" => $this->Pl_pelayanan_vk->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function get_data_history()
    {
        /*akan di filter berdasarkan pasien pada klinik masing2*/
        /*get data from model*/
        $list = $this->Pl_pelayanan_vk->get_datatables();
        //print_r($this->db->last_query());die;
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            // $row[] = '<div class="center">
            //             <label class="pos-rel">
            //                 <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->no_kunjungan.'"/>
            //                 <span class="lbl"></span>
            //             </label>
            //         </div>';
            $row[] = '<div class="center">'.$no.'</div>';
            /*fungsi rollback pasien, jika belum disubmit kasir maka poli masih bisa melakukan rollback*/
            /*cek transaksi*/
            $trans_kasir = $this->Pl_pelayanan_vk->get_transaksi_pasien_by_id($row_list->no_kunjungan);
            $rollback_btn = ($trans_kasir!=0)?'<li><a href="#" onclick="rollback('.$row_list->no_registrasi.','.$row_list->no_kunjungan.')">Rollback</a></li>':'';
            // $rollback_btn = '<li><a href="#" onclick="rollback('.$row_list->no_registrasi.','.$row_list->no_kunjungan.')">Rollback</a></li>';
            
                        
            $row[] = '<div class="center"><div class="btn-group">
                        <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">
                            <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-inverse">                     
                            <li><a href="#" onclick="show_modal('."'registration/reg_pasien/view_detail_resume_medis/".$row_list->no_registrasi."'".', '."'RESUME MEDIS'".')">Selengkapnya</a></li>
                        </ul>
                    </div></div>';

            $row[] = '<div class="center"><a href="#" onclick="getMenu('."'pelayanan/Pl_pelayanan_vk/form/".$row_list->id_pasien_vk."/".$row_list->no_kunjungan."'".')">'.$row_list->no_kunjungan.'</a></div>';
            $row[] = '<div class="center">'.$row_list->no_mr.'</div>';
            $row[] = strtoupper($row_list->nama_pasien);
            $row[] = ($row_list->nama_perusahaan)?$row_list->nama_kelompok.'<br>'.$row_list->nama_perusahaan:$row_list->nama_kelompok;
            $row[] = $this->tanggal->formatDateTime($row_list->tgl_masuk);
            $row[] = $row_list->nama_pegawai;
            $row[] = $row_list->nama_bagian.'<br>'.$row_list->nama_klas;
            // $row[] = '<div class="center">'.$row_list->fullname.'</div>';


           
            if($row_list->tgl_keluar==NULL || empty($row_list->tgl_keluar)){
                $status_periksa = '<label class="label label-warning"><i class="fa fa-info-circle"></i> Belum diperiksa</label>';
            }else {

                if($row_list->status_batal == 1){
                    $status_periksa = '<label class="label label-danger"><i class="fa fa-times"></i> Batal Kunjungan</label>';
                }else{
                    $status_periksa = ($trans_kasir==0)?'<label class="label label-info"><i class="fa fa-money"></i> Lunas</label>':'<label class="label label-success"><i class="fa fa-check-circle"></i> Selesai</label>';
                }
                
            }
            

            $row[] = '<div class="center">'.$status_periksa.'</div>';
           
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Pl_pelayanan_vk->count_all(),
                        "recordsFiltered" => $this->Pl_pelayanan_vk->count_filtered(),
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

    public function delete()
    {
        $id=$this->input->post('ID')?$this->input->post('ID',TRUE):null;
        if($id!=null){
            if($this->Pl_pelayanan_vk->delete_trans_pelayanan($id)){
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
        $this->form_validation->set_rules('kode_bagian_asal', 'Kode Bagian Asal', 'trim|required');        
        $this->form_validation->set_rules('cara_keluar', 'Cara Keluar Pasien', 'trim|required');        
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
            $id_pasien_vk = $this->regex->_genRegex($this->input->post('id_pasien_vk'),'RGXINT');

            /*cek transaksi minimal apakah sudah ada tindakan*/
            $cek_transaksi = $this->Pl_pelayanan_vk->cek_transaksi_minimal($no_kunjungan);

            /*jika sudah ada minimal 1 transaksi atau tindakan, maka lanjutkan proses*/
            if($cek_transaksi){

                /*cek pm selesai */
                $cek_pm = $this->Pl_pelayanan_vk->cek_pm_pulang($no_registrasi);

                /*if($cek_pm){
                    echo json_encode(array('status' => 301, 'message' => 'Maaf pasien masih dalam antrian Penunjang !', 'err' => 'antrian_pm'));
                    exit;
                }*/

                /*proses utama pasien selesai*/
                /*update ri_pasien_vk*/
                $arrGdTc = array('tgl_keluar' => date('Y-m-d H:i:s') );
                $this->Pl_pelayanan_vk->update('ri_pasien_vk', $arrGdTc, array('id_pasien_vk' => $id_pasien_vk ) );
                /*save logs ri_pasien_vk*/
                

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
                );

                if($this->input->post('kode_riwayat')==0){
                    $this->Pl_pelayanan_vk->save('th_riwayat_pasien', $riwayat_diagnosa);
                }else{
                    $this->Pl_pelayanan_vk->update('th_riwayat_pasien', $riwayat_diagnosa, array('kode_riwayat' => $this->input->post('kode_riwayat') ) );
                }

                $status = $this->input->post('cara_keluar');
                $txt_rujuk_poli = 'Rujuk ke Poli Lain';
                $txt_rujuk_ri = 'Rujuk ke Rawat Inap';

                $cek_rujuk = $this->Pl_pelayanan_vk->cekRujuk($_POST['no_kunjungan']);

                if( empty($cek_rujuk) ){
                    /*kondisi jika pasien dirujuk RI/RJ*/
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
                        $this->Pl_pelayanan_vk->save('rg_tc_rujukan', $rujukan_data );
                                        
                    }

                }

                /*kondisi jika pasien Meninggal*/
                if($status=='Meninggal'){
                    
                    $tgl = $this->tanggal->sqlDateForm($this->input->post('tgl_meninggal'));
                    $jam = $this->input->post('jam_meninggal');
                    $date = date_create($tgl.' '.$jam );
                    $jam_tgl_meninggal = date_format($date, 'Y-m-d H:i:s');

                    $gd_th_kematian = array(
                        'no_mr' => $this->form_validation->set_value('noMrHidden'),
                        'no_registrasi' => $no_registrasi,
                        'kode_bagian' => $this->input->post('kode_bagian_pasien_meninggal'),
                        'meninggal_hari' => $this->input->post('hari_meninggal'),
                        'tgl_keluar' => $jam_tgl_meninggal,
                        'no_kunjungan' => $no_kunjungan, 
                        'id_pasien_vk' => $id_pasien_vk,
                        'dokter_asal' => $this->input->post('dokter_pasien_meninggal'),
                        'meninggal_instruksi' => $this->input->post('instruksi_meninggal'),
                    );

                    /*insert gd_th_kematian*/
                    $kode_meninggal = $this->Pl_pelayanan_vk->save('gd_th_kematian', $gd_th_kematian );

                    /*update mt_master_pasien */
                    $this->Pl_pelayanan_vk->update('mt_master_pasien', array('status_meninggal' => 1), array('no_mr' => $this->form_validation->set_value('noMrHidden') ) );

                    $status_pulang = 4;
                    $type_pelayanan = 'Pasien Meninggal';

                }else{
                    $status_pulang = 3;
                    $type_pelayanan = 'pasien_selesai';
                    $kode_meninggal = 0;
                }

                /*last func to finsih visit*/
                $this->daftar_pasien->pulangkan_pasien($no_kunjungan,$status_pulang);

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
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'type_pelayanan' => $type_pelayanan, 'kode_meninggal' => $kode_meninggal));
            }

        
        }

    }

    public function processSaveDiagnosa(){

        //print_r($_POST);die;
        // form validation
        $this->form_validation->set_rules('noMrHidden', 'Pasien', 'trim|required', array('required' => 'No MR Pasien Tidak ditemukan!') );        
        $this->form_validation->set_rules('pl_anamnesa', 'Anamnesa', 'trim');        
        $this->form_validation->set_rules('pl_diagnosa', 'Diagnosa', 'trim|required');        
        $this->form_validation->set_rules('pl_pemeriksaan', 'Pemeriksaan', 'trim');        
        $this->form_validation->set_rules('pl_pengobatan', 'Pengobatan', 'trim');        
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
            $id_pasien_vk = $this->regex->_genRegex($this->input->post('id_pasien_vk'),'RGXINT');

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
            );

            if($this->input->post('kode_riwayat')==0){
                $this->Pl_pelayanan_vk->save('th_riwayat_pasien', $riwayat_diagnosa);
            }else{
                $this->Pl_pelayanan_vk->update('th_riwayat_pasien', $riwayat_diagnosa, array('kode_riwayat' => $this->input->post('kode_riwayat') ) );
            }

            
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'type_pelayanan' => 'save_diagnosa'));
            }

        
        }

    }

    public function process_data_bayi(){

        // form validation
        $this->form_validation->set_rules('nama_bayi', 'Nama Bayi', 'trim|required' );        
        $this->form_validation->set_rules('no_mr_ibu', 'No MR Ibu', 'trim|required');
        $this->form_validation->set_rules('nama_ibu_kandung', 'Nama Ibu', 'trim|required');
        $this->form_validation->set_rules('panjang_badan', 'Panjang Badan', 'trim|required');
        $this->form_validation->set_rules('berat_badan', 'Berat Badan', 'trim|required');
        $this->form_validation->set_rules('anus', 'Anus', 'trim');
        $this->form_validation->set_rules('apgar', 'APGAR', 'trim|required');
        $this->form_validation->set_rules('jenis_kelamin', 'Jenis Kelamin', 'trim|required');
        $this->form_validation->set_rules('dokter_penolong', 'Dokter Penolong', 'trim|required');
        $this->form_validation->set_rules('no_gelang', 'No Gelang', 'trim');
        $this->form_validation->set_rules('tempat_lahir', 'Tempat Lahir', 'trim|required');
        $this->form_validation->set_rules('tgl_jam_lahir', 'Tanggal Lahir', 'trim|required');
        $this->form_validation->set_rules('jam_lahir', 'Jam Lahir', 'trim|required');

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

            $dataexc = array(
                'nama_bayi' => $this->form_validation->set_value('nama_bayi'),
                'mr_ibu' => $this->form_validation->set_value('no_mr_ibu'),
                'nama_ibu' => $this->form_validation->set_value('nama_ibu_kandung'),
                'panjang_badan' => $this->form_validation->set_value('panjang_badan'),
                'berat_badan' => $this->form_validation->set_value('berat_badan'),
                'anus' => $this->form_validation->set_value('anus'),
                'apgar' => $this->form_validation->set_value('apgar'),
                'jenis_kelamin' => ($this->form_validation->set_value('jenis_kelamin') == 1)?'L':'P',
                'dokter_penolong' => $this->form_validation->set_value('dokter_penolong'),
                'no_gelang' => $this->form_validation->set_value('no_gelang'),
                'tempat_lahir' => $this->form_validation->set_value('tempat_lahir'),
                'tgl_jam_lahir' => $this->form_validation->set_value('tgl_jam_lahir').' '.$this->form_validation->set_value('jam_lahir'),
                'flag_lahir' => NULL,
            );

            // echo "<pre>"; print_r($_POST);die;

            if($this->input->post('id_bayi')==0){
                $this->Pl_pelayanan_vk->save('ri_bayi_lahir', $dataexc);
            }else{
                $this->Pl_pelayanan_vk->update('ri_bayi_lahir', $dataexc, array('id_bayi' => $this->input->post('id_bayi') ) );
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

    public function surat_kematian(){
        
        $kode_meninggal = isset($_GET['kode_meninggal'])?$_GET['kode_meninggal']:'';
        $no_kunjungan = $_GET['no_kunjungan'];
        $no_registrasi = $_GET['no_registrasi'];
        $umur = $_GET['umur'];
        $data['umur'] = $umur;
        $data['value'] = $this->Pl_pelayanan_vk->get_meninggal($no_kunjungan,$no_registrasi);

        $tgl_explode = explode(' ',$data['value']->tgl_keluar);
        $jam = date_create($tgl_explode[1]);
        $date = date_create($tgl_explode[0]);
        $jam_meninggal = date_format($jam, 'H:i:s');
        $date_meninggal = date_format($date, 'Y-m-d');

        $data['jam'] = $jam_meninggal;
        $data['tgl'] = $date_meninggal;

        $this->load->view('Pl_pelayanan_vk/surat_kematian', $data);

    }

    public function rollback()
    {   
        $this->db->trans_begin();  

        /*tc_kunjungan*/
        $kunj_data = array('tgl_keluar' => NULL, 'status_keluar' => NULL, 'status_batal' => NULL );
        $this->db->update('tc_kunjungan', $kunj_data, array('no_registrasi' => $_POST['no_registrasi'], 'no_kunjungan' => $_POST['no_kunjungan'] ) );

        /*update ri_pasien_vk*/
        $arrGdTc = array('tgl_keluar' => NULL, 'status_batal' => NULL );
        $this->Pl_pelayanan_vk->update('ri_pasien_vk', $arrGdTc, array('no_kunjungan' => $_POST['no_kunjungan'] ) );

        /*tc_trans_pelayanan*/
        $trans_data = array('status_selesai' => 2, 'status_nk' => NULL, 'kode_tc_trans_kasir' => NULL );
        $this->db->update('tc_trans_pelayanan', $trans_data, array('no_kunjungan' => $_POST['no_kunjungan'], 'no_registrasi' => $_POST['no_registrasi'] ) );

        $cek_rujuk = $this->Pl_pelayanan_vk->cekRujuk($_POST['no_kunjungan']);

        if(!isset($cek_rujuk) OR (isset($cek_rujuk) AND $cek_rujuk->status!=1)){
            
            if( isset($cek_rujuk) AND $cek_rujuk->status!=1 ){
                $this->db->where('rg_tc_rujukan.no_kunjungan_lama', $_POST['no_kunjungan']);
                $this->db->delete('rg_tc_rujukan');
            }

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

    public function get_list_pasien()
    {
        /*akan di filter berdasarkan pasien pada klinik masing2*/
        /*get data from model*/
        $list = $this->Pl_pelayanan_vk->get_list_data();
        // echo "<pre>"; print_r($list);die;
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
            // get umur from tgl lahir
            $umur = $this->tanggal->AgeWithYearMonthDay($row_list->tgl_lhr);
            // $row[] = '<li><span style="color:'.$color.'" onclick="getMenu('."'pelayanan/Pl_pelayanan_ri/form/".$row_list->kode_ri."/".$row_list->no_kunjungan."'".')">'.$row_list->no_mr.' - '.strtoupper($row_list->nama_pasien).'</span></li>';
            $penjamin = ($row_list->nama_perusahaan)?$row_list->nama_perusahaan:$row_list->nama_kelompok;
            $row[] = array('no_kunjungan' => $row_list->no_kunjungan, 'kode_ri' => $row_list->kode_ri, 'no_mr' => $row_list->no_mr, 'nama_pasien' => strtoupper($row_list->nama_pasien), 'color_txt' => $color, 'penjamin' => $penjamin,'kode_dokter' => trim($row_list->dr_merawat), 'dokter' => $row_list->nama_pegawai, 'kelas' => $row_list->nama_klas, 'kamar' => $row_list->nama_bagian, 'no_kamar' => $row_list->no_kamar_vk, 'kode_perusahaan' => $row_list->kode_perusahaan, 'jk' => $row_list->jen_kelamin, 'umur' => $umur, 'id_pasien_vk' => $row_list->id_pasien_vk );
            $data[] = $row;
        }

        // echo "<pre>"; print_r($data);die;

        $output = array("data" => $data );
        //output to json format
        echo json_encode($output);
    }


}

/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */


