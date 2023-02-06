<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Kepeg_dt_pegawai extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'kepegawaian/Kepeg_dt_pegawai');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('Kepeg_dt_pegawai_model', 'Kepeg_dt_pegawai');
        $this->load->model('setting/Tmp_user_model', 'Tmp_user');
        /*enable profiler*/
        $this->output->enable_profiler(false);
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'Title';

    }

    public function index() { 
        //echo '<pre>';print_r($this->session->all_userdata());
        /*define variable data*/
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show()
        );
        /*load view index*/
        $this->load->view('Kepeg_dt_pegawai/index', $data);
    }

    public function form($id='')
    {
        /*if id is not null then will show form edit*/
        if($id != ''){
            /*breadcrumbs for edit*/
            $this->breadcrumbs->push('Edit '.strtolower($this->title).'', 'Kepeg_dt_pegawai/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
            /*get value by id*/
            $data['value'] = $this->Kepeg_dt_pegawai->get_by_id($id);
            // echo '<pre>';print_r($data);die;
            /*initialize flag for form*/
            $data['flag'] = "update";
        }else{
            /*breadcrumbs for create or add row*/
            $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Kepeg_dt_pegawai/'.strtolower(get_class($this)).'/form');
            /*initialize flag for form add*/
            $data['flag'] = "create";
        }
        $data['user'] = $this->Tmp_user->get_by_id($this->session->userdata('user')->user_id);
        $data['profile_user'] = $this->db->get_where('tmp_user_profile', array('user_id' => $this->session->userdata('user')->user_id))->row();
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Kepeg_dt_pegawai/form_profile', $data);
    }
    
    /*function for view data only*/
    public function show($id)
    {
        /*breadcrumbs for view*/
        $this->breadcrumbs->push('View '.strtolower($this->title).'', 'Kepeg_dt_pegawai/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*define data variabel*/
        $data['value'] = $this->Kepeg_dt_pegawai->get_by_id($id);
        $data['title'] = $this->title;
        $data['flag'] = "read";
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Kepeg_dt_pegawai/form', $data);
    }

    public function form_jabatan($id)
    {
        /*breadcrumbs for view*/
        $this->breadcrumbs->push('View '.strtolower($this->title).'', 'Kepeg_dt_pegawai/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*define data variabel*/
        $data['value'] = $this->Kepeg_dt_pegawai->get_by_id($id);
        $data['title'] = $this->title;
        $data['flag'] = "update";
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Kepeg_dt_pegawai/form_jabatan', $data);
    }

    public function form_riwayat_pegawai($id)
    {
        /*breadcrumbs for view*/
        $this->breadcrumbs->push(strtolower($this->title).' - Riwayat Pekerjaan', 'Kepeg_dt_pegawai/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*define data variabel*/
        $data['value'] = $this->Kepeg_dt_pegawai->get_by_id($id);
        // echo '<pre>';print_r($data);die;
        $data['title'] = 'Riwayat Pekerjaan';
        $data['flag'] = "update";
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Kepeg_dt_pegawai/form_riwayat_pegawai', $data);
    }
    
    public function form_riwayat_pendidikan($id)
    {
        /*breadcrumbs for view*/
        $this->breadcrumbs->push(strtolower($this->title).' - Riwayat pendidikan', 'Kepeg_dt_pegawai/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*define data variabel*/
        $data['value'] = $this->Kepeg_dt_pegawai->get_by_id($id);
        $data['title'] = $this->title;
        $data['flag'] = "update";
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Kepeg_dt_pegawai/form_riwayat_pendidikan', $data);
    }
    public function show_detail( $id )
    {   
        $fields = $this->master->list_fields( 'view_dt_pegawai' );
        // print_r($fields);die;
        $data = $this->Kepeg_dt_pegawai->get_by_id($id);
        $html = $this->master->show_detail_row_table( $fields, $data );      

        echo json_encode( array('html' => $html) );
    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->Kepeg_dt_pegawai->get_datatables();
        
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center">
                        <label class="pos-rel">
                            <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->kepeg_id.'"/>
                            <span class="lbl"></span>
                        </label>
                      </div>';
            $row[] = '';
            $row[] = $row_list->kepeg_id;
            $row[] = '<div class="center">
                        <div class="btn-group">
                        <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">
                            <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-inverse">
                            <li>'.$this->authuser->show_button('kepegawaian/Kepeg_dt_pegawai','R',$row_list->kepeg_id,6).'</li>
                            <li>'.$this->authuser->show_button('kepegawaian/Kepeg_dt_pegawai','U',$row_list->kepeg_id,6).'</li>
                            <li>'.$this->authuser->show_button('kepegawaian/Kepeg_dt_pegawai','D',$row_list->kepeg_id,6).'</li>
                            <li><a href="#" onclick="getMenu('."'kepegawaian/Kepeg_dt_pegawai/form_jabatan/".$row_list->kepeg_id."'".')">Update Data Kepegawaian</a></li>
                            <li><a href="#" onclick="getMenu('."'kepegawaian/Kepeg_dt_pegawai/form_riwayat_pegawai/".$row_list->kepeg_id."'".')">Riwayat Pegawai</a></li>
                        </ul>
                        </div>
                    </div>';
            
            $link_image = ( $row_list->pas_foto != NULL ) ? PATH_PHOTO_PEGAWAI.$row_list->pas_foto : PATH_ASSETS_IMG.'avatar.png' ;
            $jk = ($row_list->jk == 'P' || $row_list->jk == 2 )?'Perempuan':'Laki-laki';
            $status_kepegawaian = ($row_list->kepeg_status_kerja == '211')?'Karyawan Tetap':'Karyawan KKWT';
            $row[] = '<div class="center"><a href="'.base_url().$link_image.'" target="_blank"><img src="'.base_url().$link_image.'" width="80px"></a><br> <b>'.$row_list->kepeg_nip.'</b></div>';
            $row[] = $row_list->kepeg_nik.'<br>'.$row_list->nama_pegawai.'<br>'.$jk;
            $row[] = $row_list->pendidikan_terakhir.' ('.ucwords($row_list->kepeg_tenaga_medis).')';
            $row[] = $row_list->nama_unit;
            $row[] = $row_list->nama_level.'<br>Gol. '.$row_list->kepeg_gol.'<br>'.$row_list->nama_klas;
            $row[] = $status_kepegawaian.'<br>Aktif kerja : <br>'.$row_list->kepeg_tgl_aktif;
            $status_aktif = ($row_list->kepeg_status_aktif == 'Y') ? '<span class="label label-sm label-success">Active</span>' : '<span class="label label-sm label-danger">Not active</span>';
            $row[] = '<div class="center">'.$status_aktif.'</div>';
                   
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Kepeg_dt_pegawai->count_all(),
                        "recordsFiltered" => $this->Kepeg_dt_pegawai->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function process_update_kepegawaian()
    {
        // echo '<pre>';print_r($_POST);die;
        $this->load->library('form_validation');
        $val = $this->form_validation;

        $val->set_rules('kepeg_nip','NIP Pegawai', 'integer|trim|required'); 
        $val->set_rules('nama_pegawai','Nama Pegawai', 'trim|required');
        $val->set_rules('kepeg_no_telp','No Telpon Pegawai', 'integer|trim|required');
        $val->set_rules('kepeg_email','Email Pegawai', 'trim|valid_email');
        $val->set_rules('kepeg_gol','Golongan Pegawai', 'trim|required');
        $val->set_rules('kepeg_pendidikan_terakhir','Pendidikan Terakhir', 'trim|required');
        $val->set_rules('kepeg_unit','Unit Pegawai', 'trim|required');
        $val->set_rules('kepeg_level','Jabatan Pegawai', 'trim|required');
        $val->set_rules('kepeg_hak_perawatan','Hak Keperawatan', 'trim');
        $val->set_rules('kepeg_tenaga_medis','Jenis Pegawai', 'trim|required');
        $val->set_rules('kepeg_status_kerja','Status Kepegawaian', 'trim|required');
        $val->set_rules('kepeg_tgl_aktif','Tanggal Aktif Pegawai', 'trim|required');
        $val->set_rules('kepeg_tgl_selesai','Tanggal Berakhir Kerja', 'trim');
        $val->set_rules('kepeg_masa_kontrak','Masa Kontrak (bulan)', 'trim');
        $val->set_rules('kepeg_status_aktif','Status Aktif Pegawai', 'trim|required');

        $val->set_message('required', "Silahkan isi field \"%s\"");

        if ($val->run() == FALSE)
        {
            $val->set_error_delimiters('<div style="color:white">', '</div>');
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            $this->db->trans_begin();
            
            $id = ($this->input->post('kepeg_id'))?$this->regex->_genRegex($this->input->post('kepeg_id'),'RGXINT'):0;
            $nik = ($this->input->post('kepeg_nik'))?$this->regex->_genRegex($this->input->post('kepeg_nik'),'RGXINT'):0;

            $dataktp = array(
                'ktp_nik' => $this->regex->_genRegex($nik, 'RGXQSL'),
                'ktp_nama_lengkap' => $this->regex->_genRegex($val->set_value('nama_pegawai'), 'RGXQSL'),
            );

            $dataexc = array(
                'kepeg_nip' => $this->regex->_genRegex($val->set_value('kepeg_nip'), 'RGXQSL'),
                'kepeg_no_telp' => $this->regex->_genRegex($val->set_value('kepeg_no_telp'), 'RGXQSL'),
                'kepeg_email' => $this->regex->_genRegex($val->set_value('kepeg_email'), 'RGXQSL'),
                'kepeg_gol' => $this->regex->_genRegex($val->set_value('kepeg_gol'), 'RGXQSL'),
                'kepeg_pendidikan_terakhir' => $this->regex->_genRegex($val->set_value('kepeg_pendidikan_terakhir'), 'RGXQSL'),
                'kepeg_unit' => $this->regex->_genRegex($val->set_value('kepeg_unit'), 'RGXQSL'),
                'kepeg_level' => $this->regex->_genRegex($val->set_value('kepeg_level'), 'RGXQSL'),
                'kepeg_hak_perawatan' => $this->regex->_genRegex($val->set_value('kepeg_hak_perawatan'), 'RGXQSL'),
                'kepeg_tenaga_medis' => $this->regex->_genRegex($val->set_value('kepeg_tenaga_medis'), 'RGXQSL'),
                'kepeg_status_kerja' => $this->regex->_genRegex($val->set_value('kepeg_status_kerja'), 'RGXQSL'),
                'kepeg_tgl_aktif' => $this->regex->_genRegex($val->set_value('kepeg_tgl_aktif'), 'RGXQSL'),
                'kepeg_tgl_selesai' => $this->regex->_genRegex($val->set_value('kepeg_tgl_selesai'), 'RGXQSL'),
                'kepeg_masa_kontrak' => $this->regex->_genRegex($val->set_value('kepeg_masa_kontrak'), 'RGXQSL'),
                'kepeg_status_aktif' => $this->regex->_genRegex($val->set_value('kepeg_status_aktif'), 'RGXQSL'),
            );
            
            if(isset($_FILES['pas_foto']['name'])){
                /*hapus dulu file yang lama*/
                if( $id != 0 ){
                    $pas_foto = $this->db->get_where('ktp', array('ktp_nik' => $nik) )->row();
                    if ($pas_foto->ktp_foto != NULL) {
                        unlink(PATH_PHOTO_PEGAWAI.$pas_foto->ktp_foto.'');
                    }
                }
                $dataktp['ktp_foto'] = $this->upload_file->doUpload('pas_foto', PATH_PHOTO_PEGAWAI);
            }

            if($id==0){
                $dataexc['created_date'] = date('Y-m-d H:i:s');
                $dataexc['created_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                // save data pegawai
                $newId = $this->Kepeg_dt_pegawai->save('kepeg_dt_pegawai', $dataexc);
                // save data ktp 
                $this->Kepeg_dt_pegawai->save('ktp', $dataktp);
            }else{
                $dataexc['updated_date'] = date('Y-m-d H:i:s');
                $dataexc['updated_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                /*update record*/
                $this->Kepeg_dt_pegawai->update('kepeg_dt_pegawai', array('kepeg_id' => $id), $dataexc);
                $this->Kepeg_dt_pegawai->update('ktp', array('ktp_nik' => $nik), $dataktp);
                $newId = $id;
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

    // public function process()
    // {
    //     // echo '<pre>';print_r($_FILES);die;
    //     $this->load->library('form_validation');
    //     $val = $this->form_validation;

    //     // Validasi Form Data Pribadi
    //     if($_POST['kepeg_id'] == 0){
    //         $val->set_rules('nik','NIK Pegawai', 'integer|trim|required|is_unique[ktp.ktp_nik]'); 
    //     }else{
    //         $val->set_rules('nik','NIK Pegawai', 'integer|trim|required'); 
    //     }
    //     $val->set_rules('nama_pegawai','Nama Lengkap Pegawai', 'trim|required');
    //     $val->set_rules('tmp_lahir','Tempat Lahir Pegawai', 'trim|required'); 
    //     $val->set_rules('dob_pegawai','Tanggal Lahir Pegawai', 'trim|required');
    //     $val->set_rules('alamat','Alamat Pegawai', 'trim|required'); 
    //     $val->set_rules('rt','RT', 'integer|trim|required'); 
    //     $val->set_rules('rw','RW', 'integer|trim|required'); 
    //     $val->set_rules('kecamatanHidden','Kecamatan', 'trim|required'); 
    //     $val->set_rules('kelurahanHidden','Kelurahan', 'trim|required'); 
    //     $val->set_rules('provinsiHidden','Provinsi', 'trim|required');
    //     $val->set_rules('kotaHidden','Kota / Kabupaten', 'trim|required');
    //     $val->set_rules('zipcode','Kode Pos', 'integer|trim|required'); 
    //     $val->set_rules('gender','Jenis Kelamin', 'trim|required'); 
    //     $val->set_rules('type_blood','Golongan Darah', 'trim|required'); 
    //     $val->set_rules('marital_status','Status Pernikahan', 'trim|required'); 
    //     $val->set_rules('religion','Agama', 'trim|required'); 
    //     $val->set_rules('is_active','Status Aktif Pegawai', 'trim|required'); 
    //     $val->set_rules('created_by','Created By', 'trim'); 

    //     // Validasi Data Pegawai
    //     if($_POST['kepeg_id'] == 0){
    //         $val->set_rules('kepeg_nip','NIP Pegawai', 'integer|trim|required|is_unique[kepeg_dt_pegawai.kepeg_nip]');
    //     }else{
    //         $val->set_rules('kepeg_nip','NIP Pegawai', 'integer|trim|required');
    //     }
    //     $val->set_rules('kepeg_no_telp','No. Telpon Pegawai', 'integer|trim|required');
    //     $val->set_rules('kepeg_email','Email Pegawai', 'trim|valid_email');
    //     $val->set_rules('kepeg_pendidikan_terakhir','Pendidikan Terakhir', 'trim|required');
    //     $val->set_rules('kepeg_unit','Unit / Bagian', 'trim|required');
    //     $val->set_rules('kepeg_level','Level Jabatan', 'trim|required');
    //     $val->set_rules('kepeg_gol','Golongan', 'trim|required');  
    //     $val->set_rules('kepeg_hak_perawatan','Hak Keperawatan', 'trim');
    //     $val->set_rules('kepeg_tenaga_medis','Jenis Pegawai', 'trim|required');
    //     $val->set_rules('kepeg_status_kerja','Status Kepegawaian', 'trim|required');
    //     $val->set_rules('kepeg_tgl_aktif','Tanggal Aktif Kerja', 'trim|required');
    //     $val->set_rules('kepeg_masa_kontrak','Masa Kontrak (bulan)', 'trim');
    //     $val->set_rules('kepeg_tgl_selesai','Tanggal Berakhir Kerja', 'trim');
    //     $val->set_rules('kepeg_status_aktif','Status Aktif Pegawai', 'trim|required');

    //     $val->set_message('required', "Silahkan isi field \"%s\"");

    //     if ($val->run() == FALSE)
    //     {
    //         $val->set_error_delimiters('<div style="color:white">', '</div>');
    //         echo json_encode(array('status' => 301, 'message' => validation_errors()));
    //     }
    //     else
    //     {                       
    //         $this->db->trans_begin();
            
    //         $id = ($this->input->post('kepeg_id'))?$this->regex->_genRegex($this->input->post('kepeg_id'),'RGXINT'):0;

    //         $nik = ($this->input->post('nik'))?$this->regex->_genRegex($this->input->post('nik'),'RGXINT'):0;

    //         $dataktp = array(
    //             'ktp_nik' => $this->regex->_genRegex($val->set_value('nik'), 'RGXQSL'),
    //             'ktp_nama_lengkap' => $this->regex->_genRegex($val->set_value('nama_pegawai'), 'RGXQSL'),
    //             'ktp_tempat_lahir' => $this->regex->_genRegex($val->set_value('tmp_lahir'), 'RGXQSL'),
    //             'ktp_tanggal_lahir' => $this->regex->_genRegex($val->set_value('dob_pegawai'), 'RGXQSL'),
    //             'ktp_alamat' => $this->regex->_genRegex($val->set_value('alamat'), 'RGXQSL'),
    //             'ktp_rt' => $this->regex->_genRegex($val->set_value('rt'), 'RGXQSL'),
    //             'ktp_rw' => $this->regex->_genRegex($val->set_value('rw'), 'RGXQSL'),
    //             'district_id' => $this->regex->_genRegex($val->set_value('kecamatanHidden'), 'RGXQSL'),
    //             'sub_district_id' => $this->regex->_genRegex($val->set_value('kelurahanHidden'), 'RGXQSL'),
    //             'province_id' => $this->regex->_genRegex($val->set_value('provinsiHidden'), 'RGXQSL'),
    //             'city_id' => $this->regex->_genRegex($val->set_value('kotaHidden'), 'RGXQSL'),
    //             'zipcode' => $this->regex->_genRegex($val->set_value('zipcode'), 'RGXQSL'),
    //             'ktp_jk' => $this->regex->_genRegex($val->set_value('gender'), 'RGXQSL'),
    //             'tb_id' => $this->regex->_genRegex($val->set_value('type_blood'), 'RGXQSL'),
    //             'ms_id' => $this->regex->_genRegex($val->set_value('marital_status'), 'RGXQSL'),
    //             'religion_id' => $this->regex->_genRegex($val->set_value('religion'), 'RGXQSL'),
    //             'is_active' => $this->regex->_genRegex($val->set_value('is_active'), 'RGXQSL'),
    //         );

    //         $dataexc = array(
    //             'kepeg_nik' => $this->regex->_genRegex($val->set_value('nik'), 'RGXQSL'),
    //             'kepeg_nip' => $this->regex->_genRegex($val->set_value('kepeg_nip'), 'RGXQSL'),
    //             'kepeg_no_telp' => $this->regex->_genRegex($val->set_value('kepeg_no_telp'), 'RGXQSL'),
    //             'kepeg_email' => $this->regex->_genRegex($val->set_value('kepeg_email'), 'RGXQSL'),
    //             'kepeg_pendidikan_terakhir' => $this->regex->_genRegex($val->set_value('kepeg_pendidikan_terakhir'), 'RGXQSL'),
    //             'kepeg_unit' => $this->regex->_genRegex($val->set_value('kepeg_unit'), 'RGXQSL'),
    //             'kepeg_level' => $this->regex->_genRegex($val->set_value('kepeg_level'), 'RGXQSL'),
    //             'kepeg_gol' => $this->regex->_genRegex($val->set_value('kepeg_gol'), 'RGXQSL'),
    //             'kepeg_hak_perawatan' => $this->regex->_genRegex($val->set_value('kepeg_hak_perawatan'), 'RGXQSL'),
    //             'kepeg_tenaga_medis' => $this->regex->_genRegex($val->set_value('kepeg_tenaga_medis'), 'RGXQSL'),
    //             'kepeg_status_kerja' => $this->regex->_genRegex($val->set_value('kepeg_status_kerja'), 'RGXQSL'),
    //             'kepeg_tgl_selesai' => $this->regex->_genRegex($val->set_value('kepeg_tgl_selesai'), 'RGXQSL'),
    //             'kepeg_status_aktif' => $this->regex->_genRegex($val->set_value('kepeg_status_aktif'), 'RGXQSL'),
    //         );

    //         if($_POST['kepeg_status_kerja'] == 212){
    //             $dataexc['kepeg_tgl_aktif'] = $this->regex->_genRegex($val->set_value('kepeg_tgl_aktif'), 'RGXQSL');
    //             $dataexc['kepeg_masa_kontrak'] = $this->regex->_genRegex($val->set_value('kepeg_masa_kontrak'), 'RGXQSL');
    //         }
            
    //         if(isset($_FILES['pas_foto']['name'])){
    //             /*hapus dulu file yang lama*/
    //             if( $id != 0 ){
    //                 $pas_foto = $this->Kepeg_dt_pegawai->get_by_id($id);
    //                 if ($pas_foto->pas_foto != NULL AND $pas_foto->pas_foto != 0) {
    //                     unlink(PATH_PHOTO_PEGAWAI.$pas_foto->pas_foto.'');
    //                 }
    //             }
    //             $dataktp['ktp_foto'] = $this->upload_file->doUpload('pas_foto', PATH_PHOTO_PEGAWAI);
    //         }

    //         if($id==0){
    //             $dataexc['created_date'] = date('Y-m-d H:i:s');
    //             $dataexc['created_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                
    //             // save data pegawai
    //             $newId = $this->Kepeg_dt_pegawai->save('kepeg_dt_pegawai', $dataexc);
                
    //             // save data ktp 
    //             $this->Kepeg_dt_pegawai->save('ktp', $dataktp);
    //             // print_r($this->db->last_query());die;
    //         }else{
    //             $dataexc['updated_date'] = date('Y-m-d H:i:s');
    //             $dataexc['updated_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
    //             /*update record*/
    //             $this->Kepeg_dt_pegawai->update('kepeg_dt_pegawai', array('kepeg_id' => $id), $dataexc);
    //             // print_r($this->db->last_query());die;
    //             $this->Kepeg_dt_pegawai->update('ktp', array('ktp_nik' => $nik), $dataktp);
    //             $newId = $id;
    //         }
    //         if ($this->db->trans_status() === FALSE)
    //         {
    //             $this->db->trans_rollback();
    //             echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
    //         }
    //         else
    //         {
    //             $this->db->trans_commit();
    //             echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan'));
    //         }
    //     }
    // }

    public function process()
    {
        // echo '<pre>';print_r($_POST);die;
        $this->load->library('form_validation');
        $val = $this->form_validation;


        $val->set_rules('kepeg_nip','NIP Pegawai', 'integer|trim|required'); 
        $val->set_rules('nama_pegawai','Nama Pegawai', 'trim|required');
        $val->set_rules('kepeg_no_telp','No Telpon Pegawai', 'integer|trim|required');
        $val->set_rules('kepeg_email','Email Pegawai', 'trim|valid_email');
        $val->set_rules('kepeg_gol','Golongan Pegawai', 'trim|required');
        $val->set_rules('kepeg_pendidikan_terakhir','Pendidikan Terakhir', 'trim|required');
        $val->set_rules('kepeg_unit','Unit Pegawai', 'trim|required');
        $val->set_rules('kepeg_level','Jabatan Pegawai', 'trim|required');
        $val->set_rules('kepeg_hak_perawatan','Hak Keperawatan', 'trim');
        $val->set_rules('kepeg_tenaga_medis','Jenis Pegawai', 'trim|required');
        $val->set_rules('kepeg_status_kerja','Status Kepegawaian', 'trim|required');
        $val->set_rules('kepeg_tgl_aktif','Tanggal Aktif Pegawai', 'trim|required');
        $val->set_rules('kepeg_tgl_selesai','Tanggal Berakhir Kerja', 'trim');
        $val->set_rules('kepeg_masa_kontrak','Masa Kontrak (bulan)', 'trim');
        $val->set_rules('kepeg_status_aktif','Status Aktif Pegawai', 'trim|required');

        $val->set_message('required', "Silahkan isi field \"%s\"");

        if ($val->run() == FALSE)
        {
            $val->set_error_delimiters('<div style="color:white">', '</div>');
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            $this->db->trans_begin();
            
            $id = ($this->input->post('kepeg_id'))?$this->regex->_genRegex($this->input->post('kepeg_id'),'RGXINT'):0;
            $nik = ($this->input->post('nik'))?$this->regex->_genRegex($this->input->post('nik'),'RGXINT'):0;
            $profil_id = ($this->input->post('profil_id'))?$this->regex->_genRegex($this->input->post('profil_id'),'RGXINT'):0;
            $no_induk = ($this->input->post('no_induk'))?$this->regex->_genRegex($this->input->post('no_induk'),'RGXQSL'):0;

            $dataktp = array(
                'ktp_nik' => $this->regex->_genRegex($nik, 'RGXQSL'),
                'ktp_nama_lengkap' => $this->regex->_genRegex($_POST['nama_pegawai'], 'RGXQSL'),
                'religion_id' => $this->regex->_genRegex($_POST['religion'], 'RGXQSL'),
                'ms_id' => $this->regex->_genRegex($_POST['marital_status'], 'RGXQSL'),
                'tb_id' => $this->regex->_genRegex($_POST['type_blood'], 'RGXQSL'),
                'province_id' => $this->regex->_genRegex($_POST['provinsiHidden'], 'RGXQSL'),
                'city_id' => $this->regex->_genRegex($_POST['kotaHidden'], 'RGXQSL'),
                'district_id' => $this->regex->_genRegex($_POST['kecamatanHidden'], 'RGXQSL'),
                'sub_district_id' => $this->regex->_genRegex($_POST['kelurahanHidden'], 'RGXQSL'),
                'ktp_jk' => $this->regex->_genRegex($_POST['gender'], 'RGXQSL'),
                'ktp_tempat_lahir' => $this->regex->_genRegex($_POST['tmp_lahir'], 'RGXQSL'),
                'ktp_tanggal_lahir' => $this->regex->_genRegex($_POST['dob_pegawai'], 'RGXQSL'),
                'ktp_alamat' => $this->regex->_genRegex($_POST['alamat'], 'RGXQSL'),
                'ktp_rt' => $this->regex->_genRegex($_POST['rt'], 'RGXQSL'),
                'ktp_rw' => $this->regex->_genRegex($_POST['rw'], 'RGXQSL'),
                'zipcode' => $this->regex->_genRegex($_POST['zipcode'], 'RGXQSL'),
            );

            $dataexc = array(
                'kepeg_nik' => $this->regex->_genRegex($nik, 'RGXQSL'),
                'kepeg_nip' => $this->regex->_genRegex($val->set_value('kepeg_nip'), 'RGXQSL'),
                'kepeg_no_telp' => $this->regex->_genRegex($val->set_value('kepeg_no_telp'), 'RGXQSL'),
                'kepeg_email' => $this->regex->_genRegex($val->set_value('kepeg_email'), 'RGXQSL'),
                'kepeg_gol' => $this->regex->_genRegex($val->set_value('kepeg_gol'), 'RGXQSL'),
                'kepeg_pendidikan_terakhir' => $this->regex->_genRegex($val->set_value('kepeg_pendidikan_terakhir'), 'RGXQSL'),
                'kepeg_unit' => $this->regex->_genRegex($val->set_value('kepeg_unit'), 'RGXQSL'),
                'kepeg_level' => $this->regex->_genRegex($val->set_value('kepeg_level'), 'RGXQSL'),
                'kepeg_hak_perawatan' => $this->regex->_genRegex($val->set_value('kepeg_hak_perawatan'), 'RGXQSL'),
                'kepeg_tenaga_medis' => $this->regex->_genRegex($val->set_value('kepeg_tenaga_medis'), 'RGXQSL'),
                'kepeg_status_kerja' => $this->regex->_genRegex($val->set_value('kepeg_status_kerja'), 'RGXQSL'),
                'kepeg_tgl_aktif' => $this->regex->_genRegex($val->set_value('kepeg_tgl_aktif'), 'RGXQSL'),
                'kepeg_tgl_selesai' => $this->regex->_genRegex($val->set_value('kepeg_tgl_selesai'), 'RGXQSL'),
                'kepeg_masa_kontrak' => $this->regex->_genRegex($val->set_value('kepeg_masa_kontrak'), 'RGXQSL'),
                'kepeg_status_aktif' => $this->regex->_genRegex($val->set_value('kepeg_status_aktif'), 'RGXQSL'),
            );

            $datakaryawan = array(
                'no_induk' => $this->regex->_genRegex($val->set_value('kepeg_nip'), 'RGXQSL'),
                'nama_pegawai' => $this->regex->_genRegex($val->set_value('nama_pegawai'), 'RGXQSL'),
                'kode_jabatan' => $this->regex->_genRegex($val->set_value('kepeg_level'), 'RGXQSL'),
                'kode_bagian' => $this->regex->_genRegex($val->set_value('kepeg_unit'), 'RGXQSL'),
                'is_active' => $this->regex->_genRegex($val->set_value('kepeg_status_aktif'), 'RGXQSL'),
                'integration' => 1,
            );

            // $dataprofil = array(
            //     'fullname' => $this->regex->_genRegex($_POST['nama_pegawai'],'RGXQSL'),
            //     'pob' => $this->regex->_genRegex($_POST['tmp_lahir'],'RGXQSL'),
            //     'dob' => $this->regex->_genRegex($_POST['dob_pegawai'],'RGXQSL'),
            //     'address' => $this->regex->_genRegex($_POST['alamat'],'RGXQSL'),
            //     'no_telp' => $this->regex->_genRegex($_POST['kepeg_no_telp'],'RGXQSL'),
            //     'gender' => $this->regex->_genRegex($_POST['gender'],'RGXQSL'),
            //     'no_ktp' => $this->regex->_genRegex($nik,'RGXQSL'),
            //     'nip' => $this->regex->_genRegex($val->set_value('kepeg_nip'), 'RGXQSL'),
            //     'user_id' => $this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'),
            // );
            
            if(isset($_FILES['pas_foto']['name'])){
                /*hapus dulu file yang lama*/
                if( $profile_id != 0 ){
                    $profile = $this->db->get_where('tmp_user_profile', array('user_id' => $this->session->userdata('user')->user_id) )->row();
                    if ($profile->path_foto != NULL) {
                        unlink(PATH_PHOTO_PROFILE_DEFAULT.$profile->path_foto.'');
                    }
                }

                if( $nik != 0 ){
                    $pas_foto = $this->db->get_where('ktp', array('ktp_nik' => $nik) )->row();
                    if ($pas_foto->ktp_foto != NULL) {
                        unlink(PATH_PHOTO_PEGAWAI.$pas_foto->ktp_foto.'');
                    }
                }
                
                
                $foto_name = $this->upload_file->doUpload('pas_foto', PATH_PHOTO_PROFILE_DEFAULT);

                $dataprofil['path_foto'] = $foto_name;
                $dataktp['ktp_foto'] = $foto_name;
            }

            // insert ktp
            if($nik==0){
                $dataktp['created_date'] = date('Y-m-d H:i:s');
                $dataktp['created_by'] = $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL');
                $this->db->insert('ktp', $dataktp);
            }else{
                $dataktp['updated_date'] = date('Y-m-d H:i:s');
                $dataktp['updated_by'] = $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL');
                /*update record*/
                $this->db->where(array('ktp_nik' => $nik))->update('ktp', $dataktp);
                $newId = $id;
            }

            // insert pegawai
            if($id==0){
                $dataexc['created_date'] = date('Y-m-d H:i:s');
                $dataexc['created_by'] = $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL');
                // save data pegawai
                $newId = $this->db->insert('kepeg_dt_pegawai', $dataexc);
            }else{
                $dataexc['updated_date'] = date('Y-m-d H:i:s');
                $dataexc['updated_by'] = $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL');
                /*update record*/
                $this->db->where(array('kepeg_id' => $id))->update('kepeg_dt_pegawai', $dataexc);
            }

            // insert karyawan
            if($no_induk==0){
                $datakaryawan['created_date'] = date('Y-m-d H:i:s');
                $datakaryawan['created_by'] = $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL');
                // save data pegawai
                $newId = $this->db->insert('mt_karyawan', $datakaryawan);
            }else{
                $datakaryawan['updated_date'] = date('Y-m-d H:i:s');
                $datakaryawan['updated_by'] = $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL');
                /*update record*/
                $this->db->where(array('no_induk' => $no_induk))->update('mt_karyawan', $datakaryawan);
            }

            // if($profil_id==0){
            //     $dataprofil['created_date'] = date('Y-m-d H:i:s');
            //     $dataprofil['created_by'] = $this->session->userdata('user')->fullname;
            //     $this->db->insert('tmp_user_profile', $dataprofil);
            // }else{
            //     $dataprofil['updated_date'] = date('Y-m-d H:i:s');
            //     $dataprofil['updated_by'] = $this->session->userdata('user')->fullname;
            //     $this->db->where(array('user_id' => $id))->update('tmp_user_profile', $dataprofil);
            // }

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

    public function process_riwayat_pekerjaan()
    {
        $this->load->library('form_validation');
        $val = $this->form_validation;

        // Validasi form riwayat perkerjaan
        $val->set_rules();

        // set messages if error
        $val->set_messages();

        // 

    }

    public function delete()
    {
        $id=$this->input->post('ID')?$this->regex->_genRegex($this->input->post('ID',TRUE),'RGXQSL'):null;
        $toArray = explode(',',$id);
        if($id!=null){
            if($this->Kepeg_dt_pegawai->delete_by_id($toArray)){
                echo json_encode(array('status' => 200, 'message' => 'Proses Hapus Data Berhasil Dilakukan'));

            }else{
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Hapus Data Gagal Dilakukan'));
            }
        }else{
            echo json_encode(array('status' => 301, 'message' => 'Tidak ada item yang dipilih'));
        }
        
    }


}


/* End of file example.php */
/* Location: ./application/modules/example/controllers/example.php */
