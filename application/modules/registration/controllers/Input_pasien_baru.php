<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');


class Input_pasien_baru extends MX_Controller {

    /*function constructor*/
    
    function __construct() {

        parent::__construct();
        
        /*breadcrumb default*/
        
        $this->breadcrumbs->push('Index', 'registrasi/Input_pasien_baru');
        
        /*session redirect login if not login*/
        
        if($this->session->userdata('logged')!=TRUE){
            
            echo 'Session Expired !'; exit;
        
        }
        
        /*load model*/
        
        $this->load->model('Input_pasien_baru_model', 'Input_pasien_baru');


        /*load library*/

        $this->load->library('Form_validation');
        
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
            
            'breadcrumbs' => $this->breadcrumbs->show()
        
        );
        
        /*load view index*/
        
        $this->load->view('Input_pasien_baru/index', $data);
    
    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->Input_pasien_baru->get_datatables_by_limit(10,10);
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center"><div class="hidden-sm hidden-xs action-buttons">
                        '.$this->authuser->show_button('registration/Input_pasien_baru','R',$row_list->no_mr,2).'
                        '.$this->authuser->show_button('registration/Input_pasien_baru','U',$row_list->no_mr,2).'
                        
                    </div>
                    <div class="hidden-md hidden-lg">
                        <div class="inline pos-rel">
                            <button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto"><i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
                            </button>
                            <ul class="dropdown-mst_marital_status dropdown-only-icon dropdown-yellow dropdown-mst_marital_status-right dropdown-caret dropdown-close">
                                <li>'.$this->authuser->show_button('registration/Input_pasien_baru','R','',4).'</li>
                                <li>'.$this->authuser->show_button('registration/Input_pasien_baru','U','',4).'</li>
                               
                            </ul>
                        </div>
                    </div></div>';
            $row[] = '<div class="center">'.$row_list->no_mr.'</div>';
            $row[] = '<div class="center">'.$row_list->no_ktp.'</div>';
            $row[] = $row_list->nama_pasien;
            $row[] = $row_list->jen_kelamin;
            $row[] = $this->tanggal->formatDateTime($row_list->tgl_lhr);
            $row[] = $row_list->tempat_lahir;
            $row[] = $row_list->almt_ttp_pasien;
            
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Input_pasien_baru->count_all_by_limit(10,10),
                        "recordsFiltered" => $this->Input_pasien_baru->count_filtered_by_limit(10,10),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    
    /*function for view data only*/
    
    public function form($noMr='')
    
    {
        /*if id is not null then will show form edit*/
        if( $noMr != '' ){
            /*breadcrumbs for edit*/
            $this->breadcrumbs->push('Edit '.strtolower($this->title).'', 'registration/Input_pasien_baru/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$noMr);
            /*get value by id*/
            $data['value'] = $this->Input_pasien_baru->get_by_mr($noMr);
            $data['flag'] = "update";
        }else{
            /*breadcrumbs for create or add row*/
            $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'registration/Input_pasien_baru/'.strtolower(get_class($this)).'/form');
            /*initialize flag for form add*/
            $data['flag'] = "create";
        }

        /*breadcrumbs for view*/
        //$this->breadcrumbs->show();
        
        /*define data variabel*/
        
        $data['breadcrumbs'] = $this->breadcrumbs->show();

        $data['title'] = $this->title;
        
        /*load form view*/
        
        $this->load->view('Input_pasien_baru/form', $data);
    
    }

    public function form_bayi_rs()
    
    {
        
        $data['breadcrumbs'] = $this->breadcrumbs->show();

        $data['title'] = $this->title;
        
        /*load form view*/
        
        $this->load->view('Input_pasien_baru/form_bayi_rs', $data);
    
    }

    public function show($noMr)
    {
        /*breadcrumbs for view*/
        $this->breadcrumbs->push('View '.strtolower($this->title).'', 'registration/Input_pasien_baru/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$noMr);
        /*define data variabel*/
        $data['value'] = $this->Input_pasien_baru->get_by_id($noMr);
        $data['title'] = $this->title;
        $data['flag'] = "read";
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Input_pasien_baru/form', $data);
    }

    public function process(){

        //print_r($_POST);die;
        // form validation
        $this->form_validation->set_rules('nama_pasien', 'Nama Pasien', 'trim|required');
        $this->form_validation->set_rules('nik_pasien', 'NIK', 'trim|required');
        $this->form_validation->set_rules('pob_pasien', 'Tempat Lahir', 'trim|required');
        $this->form_validation->set_rules('dob_pasien', 'Tanggal Lahir', 'trim|required');
        $this->form_validation->set_rules('alamat_pasien', 'Alamat', 'trim|required');
       
        $this->form_validation->set_rules('zipcode', 'Kode Pos', 'trim');
        $this->form_validation->set_rules('gender', 'Jenis Kelamin', 'trim|required');
        $this->form_validation->set_rules('tlp_almt_ttp', 'Telp', 'trim|required');
        $this->form_validation->set_rules('telp_pasien', 'HP', 'trim|required');
        $this->form_validation->set_rules('gelar_nama', 'gelar_nama', 'trim');
        $this->form_validation->set_rules('kelompok_pasien', 'Nasabah', 'trim');
        $this->form_validation->set_rules('keterangan_pasien', 'Catatan Pasien', 'trim');

        /*regional*/
        $this->form_validation->set_rules('provinsiHidden', 'Provinsi', 'trim');
        $this->form_validation->set_rules('kotaHidden', 'Kota / Kabupaten', 'trim');
        $this->form_validation->set_rules('kecamatanHidden', 'Kecamatan', 'trim|required');
        $this->form_validation->set_rules('kelurahanHidden', 'Kelurahan', 'trim|required');


        if($_POST['kelompok_pasien']==3){
            $this->form_validation->set_rules('kode_perusahaan', 'Nama Perusahaan', 'trim|required');
        }

        if($_POST['tipe_pasien_baru']=='bayi'){
            $this->form_validation->set_rules('nama_ayah_pasien', 'Nama Ayah', 'trim|required');
            $this->form_validation->set_rules('nama_ibu_pasien', 'Nama Ibu', 'trim|required');
            $this->form_validation->set_rules('job', 'Pekerjaan Ayah', 'trim|required');
        }

        if($_POST['tipe_pasien_baru']=='dewasa'){
            $this->form_validation->set_rules('marital_status', 'Status Perkawinan', 'trim|required');
            $this->form_validation->set_rules('religion', 'Agama', 'trim|required');
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
            
            $mr = ($this->input->post('noMrHiddenPasien'))?$this->input->post('noMrHiddenPasien'):0;

            switch ($this->input->post('marital_status')) {
                case 1:
                    $ms = 'K';
                break;
                
                case 2:
                    $ms = 'TK';
                break;

                case 3:
                    $ms = 'JD';
                break;

                case 4:
                    $ms = 'DD';
                break;
                
                default:
                    $ms = 'TK';
                    break;
            }

            $dob = $this->input->post('dob_pasien');
            $today = date("Y-m-d");
            $diff = date_diff(date_create($dob), date_create($today));
            $age = $diff->format('%y');

            $dataexc = array(
                'nama_pasien' => strtoupper($this->regex->_genRegex($this->form_validation->set_value('nama_pasien'),'RGXQSL')),
                'no_ktp' => $this->regex->_genRegex($this->form_validation->set_value('nik_pasien'),'RGXQSL'),
                'title' => $this->regex->_genRegex($this->form_validation->set_value('gelar_nama'),'RGXQSL'),
                'tgl_lhr' => $this->tanggal->sqlDateTime($this->input->post('dob_pasien')),
                'tempat_lahir' => strtoupper($this->regex->_genRegex($this->form_validation->set_value('pob_pasien'),'RGXQSL')),
                'umur_pasien' => $age,
                'almt_ttp_pasien' => strtoupper($this->regex->_genRegex($this->form_validation->set_value('alamat_pasien'),'RGXQSL')),
                'tlp_almt_ttp' => ($this->regex->_genRegex($this->form_validation->set_value('tlp_almt_ttp'),'RGXQSL'))?$this->regex->_genRegex($this->form_validation->set_value('tlp_almt_ttp'),'RGXQSL'):'',
                'no_hp' => ($this->regex->_genRegex($this->form_validation->set_value('telp_pasien'),'RGXQSL'))?$this->regex->_genRegex($this->form_validation->set_value('telp_pasien'),'RGXQSL'):'',
                'jen_kelamin' => ($this->regex->_genRegex($this->form_validation->set_value('gender'),'RGXQSL')==1)?'L':'P',
                'status_perkaw' => $ms,
                'kode_agama' => ($this->regex->_genRegex($this->form_validation->set_value('religion'),'RGXINT'))?$this->regex->_genRegex($this->form_validation->set_value('Agama'),'RGXINT'):NULL,
                'id_dc_gol_darah' => $this->input->post('type_blood'),
                'id_dc_kota' => $this->regex->_genRegex($this->form_validation->set_value('kotaHidden'),'RGXQSL'),
                'id_dc_kecamatan' => $this->regex->_genRegex($this->form_validation->set_value('kecamatanHidden'),'RGXQSL'),
                'id_dc_kelurahan' => $this->regex->_genRegex($this->form_validation->set_value('kelurahanHidden'),'RGXQSL'),
                'id_dc_propinsi' => $this->regex->_genRegex($this->form_validation->set_value('provinsiHidden'),'RGXQSL'),
                'id_dc_kawin' => ($this->regex->_genRegex($this->form_validation->set_value('marital_status'),'RGXINT'))?$this->regex->_genRegex($this->form_validation->set_value('marital_status'),'RGXINT'):1,
                'id_dc_agama' => $this->regex->_genRegex($this->form_validation->set_value('religion'),'RGXINT'),
                'kode_pos' => $this->regex->_genRegex($this->form_validation->set_value('zipcode'),'RGXQSL'),
                'is_active' => 1,
                'kode_kelompok' => $this->regex->_genRegex($this->form_validation->set_value('kelompok_pasien'),'RGXINT'),
                'kode_perusahaan' => isset($_POST['kode_perusahaan'])?$this->regex->_genRegex($this->form_validation->set_value('kode_perusahaan'),'RGXINT'):NULL,
                'keterangan' => isset($_POST['keterangan_pasien'])?$this->regex->_genRegex($this->form_validation->set_value('keterangan_pasien'),'RGXQSL'):NULL,
            );

            if(isset($_FILES['path_foto']['name'])){
                /*hapus dulu file yang lama*/
                if( $id != 0 ){
                    $path_foto = $this->db->get_where('mt_master_pasien', array('no_mr' => $mr) )->row();
                    if ($path_foto->url_foto_pasien != NULL) {
                        unlink(PATH_PHOTO_PROFILE_DEFAULT.$path_foto->url_foto_pasien.'');
                    }
                }
                $dataexc['url_foto_pasien'] = $this->upload_file->doUpload('path_foto', PATH_PHOTO_PROFILE_DEFAULT);
            }
          
            if($mr==0){
                /*cek existing pasien*/
                $cek1 = $this->db->query("select * from mt_master_pasien where tgl_lhr='".$this->tanggal->sqlDateTime($this->input->post('dob_pasien'))."' and nama_pasien like '%".strtoupper($this->regex->_genRegex($this->form_validation->set_value('nama_pasien'),'RGXQSL'))."%' and almt_ttp_pasien like '%".$this->input->post('alamat_pasien')."%' ")->num_rows();
                
                if($cek1==0){
                    /*get max no mr*/
                    $cekMaxMr = $this->db->query("select TOP 1 no_mr from mt_master_pasien where LEN(no_mr)=8 order by no_mr desc ")->row();
                    
                    $mrID = $cekMaxMr->no_mr + 1;
                    
                    $panjang_mr=strlen($mrID);
                    $sisa_panjang=8-$panjang_mr;
                    $tambah_nol="";
                    
                    for ($i=1;$i<=$sisa_panjang;$i++){
                        $tambah_nol=$tambah_nol."0";
                    }
        
                    $mrID=$tambah_nol.$mrID;

                    $dataexc['no_mr'] = $mrID;
                    $dataexc['create_date'] = date('Y-m-d H:i:s');
                    $dataexc['no_mr_barcode'] = $mrID;
                    $dataexc['masa_mulai'] = $this->input->post('tgl_mulai_kepemilikan');
                    $dataexc['masa_selesai'] = $this->input->post('tgl_akhir_kepemilikan');
                    $dataexc['sirs_v1'] = 1;

                    //print_r($dataexc);die;

                    /* save pasien*/
                    $newId = $this->Input_pasien_baru->save('mt_master_pasien', $dataexc);

                    /*save logs*/
                    $this->logs->save('mt_master_pasien', $newId, 'insert new record on '.$this->title.' module', json_encode($dataexc),'id_mt_master_pasien');

                    if ($this->db->trans_status() === FALSE)
                    {
                        $this->db->trans_rollback();
                        echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
                    }
                    else
                    {
                        $this->db->trans_commit();
                        echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'no_mr' => $dataexc['no_mr']));
                    }
                } else {
                
                    echo json_encode(array('status' => 301, 'message' => 'Maaf Pasien Sudah Terdaftar, silahkan cek kembali data pasien tersebut'));            

                }

            } else {       
                
                
                $data = $this->Input_pasien_baru->get_by_id($mr);
                $newId = $data->id_mt_master_pasien;

                /*update record*/
                $this->Input_pasien_baru->update('mt_master_pasien',$dataexc, array('no_mr' => $mr));

                 /*save logs*/
                 $this->logs->save('mt_master_pasien', $newId, 'update record on '.$this->title.' module', json_encode($dataexc),'id_mt_master_pasien');

                if ($this->db->trans_status() === FALSE)
                {
                    $this->db->trans_rollback();
                    echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
                }
                else
                {
                    $this->db->trans_commit();
                    echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'no_mr' => $_POST['noMrHiddenPasien'], 'is_new' => 'Yes'));
                }

               
            }
        
        }

    }

    public function process_bayi_rs(){

        //print_r($_POST);die;
        // form validation
        $this->form_validation->set_rules('nama_pasien', 'Nama Pasien', 'trim|required');
        $this->form_validation->set_rules('nik_pasien', 'NIK', 'trim|required');
        $this->form_validation->set_rules('pob_pasien', 'Tempat Lahir', 'trim|required');
        $this->form_validation->set_rules('dob_pasien', 'Tanggal Lahir', 'trim|required');
        $this->form_validation->set_rules('alamat_pasien', 'Alamat', 'trim|required');
        $this->form_validation->set_rules('provinsiHidden', 'Provinsi', 'trim');
        $this->form_validation->set_rules('kotaHidden', 'Kota / Kabupaten', 'trim');
        $this->form_validation->set_rules('kecamatanHidden', 'Kecamatan', 'trim');
        $this->form_validation->set_rules('kelurahanHidden', 'Kelurahan', 'trim');
        $this->form_validation->set_rules('zipcode', 'Kode Pos', 'trim');
        $this->form_validation->set_rules('gender', 'Jenis Kelamin', 'trim|required');
        $this->form_validation->set_rules('telp_pasien', 'Telp/HP', 'trim|required');
        $this->form_validation->set_rules('gelar_nama', 'gelar_nama', 'trim');
        $this->form_validation->set_rules('kelompok_pasien', 'Nasabah', 'trim|required');

        if($_POST['kelompok_pasien']==3){
            $this->form_validation->set_rules('kode_perusahaan', 'Nama Perusahaan', 'trim|required');
        }

        if($_POST['tipe_pasien_baru']=='bayi'){
            $this->form_validation->set_rules('nama_ayah_pasien', 'Nama Ayah', 'trim|required');
            $this->form_validation->set_rules('nama_ibu_pasien', 'Nama Ibu', 'trim|required');
            $this->form_validation->set_rules('job', 'Pekerjaan Ayah', 'trim|required');
            $this->form_validation->set_rules('mr_ibu', 'No MR Ibu', 'trim');
        }

        if($_POST['tipe_pasien_baru']=='dewasa'){
            $this->form_validation->set_rules('marital_status', 'Status Perkawinan', 'trim|required');
            $this->form_validation->set_rules('religion', 'Agama', 'trim|required');
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

            $cek1 = $this->db->query("select * from mt_master_pasien where tgl_lhr='".$this->input->post('dob_pasien')."' and nama_pasien like '%".strtoupper($this->regex->_genRegex($this->form_validation->set_value('nama_pasien'),'RGXQSL'))."%'")->num_rows();
        
            switch ($this->input->post('marital_status')) {
                case 1:
                    $ms = 'K';
                break;
                
                case 2:
                    $ms = 'TK';
                break;

                case 3:
                    $ms = 'JD';
                break;

                case 4:
                    $ms = 'DD';
                break;
                
                default:
                    $ms = 'TK';
                    break;
            }

            $dob = $this->input->post('dob_pasien');
            $today = date("Y-m-d");
            $diff = date_diff(date_create($dob), date_create($today));
            $age = $diff->format('%y');

            $dataexc = array(
                'nama_pasien' => strtoupper($this->regex->_genRegex($this->form_validation->set_value('nama_pasien'),'RGXQSL')),
                'no_ktp' => $this->regex->_genRegex($this->form_validation->set_value('nik_pasien'),'RGXQSL'),
                'title' => 'By.',
                'tgl_lhr' => $this->input->post('dob_pasien'),
                'tempat_lahir' => strtoupper($this->regex->_genRegex($this->form_validation->set_value('pob_pasien'),'RGXQSL')),
                'umur_pasien' => $age,
                'almt_ttp_pasien' => strtoupper($this->regex->_genRegex($this->form_validation->set_value('alamat_pasien'),'RGXQSL')),
                'tlp_almt_ttp' => (strlen($this->regex->_genRegex($this->form_validation->set_value('telp_pasien'),'RGXINT'))<10)?$this->regex->_genRegex($this->form_validation->set_value('telp_pasien'),'RGXINT'):'',
                'jen_kelamin' => $this->regex->_genRegex($this->form_validation->set_value('gender'),'RGXQSL'),
                'status_perkaw' => $ms,
                'kode_agama' => ($this->regex->_genRegex($this->form_validation->set_value('religion'),'RGXINT'))?$this->regex->_genRegex($this->form_validation->set_value('Agama'),'RGXINT'):NULL,
                'id_dc_gol_darah' => $this->input->post('type_blood'),
                'id_dc_kota' => $this->regex->_genRegex($this->form_validation->set_value('kotaHidden'),'RGXQSL'),
                'id_dc_kecamatan' => $this->regex->_genRegex($this->form_validation->set_value('kecamatanHidden'),'RGXQSL'),
                'id_dc_kelurahan' => $this->regex->_genRegex($this->form_validation->set_value('kelurahanHidden'),'RGXQSL'),
                'id_dc_propinsi' => $this->regex->_genRegex($this->form_validation->set_value('provinsiHidden'),'RGXQSL'),
                'no_hp' => (strlen($this->regex->_genRegex($this->form_validation->set_value('telp_pasien'),'RGXINT'))>=10)?$this->regex->_genRegex($this->form_validation->set_value('telp_pasien'),'RGXINT'):'',
                'id_dc_kawin' => ($this->regex->_genRegex($this->form_validation->set_value('marital_status'),'RGXINT'))?$this->regex->_genRegex($this->form_validation->set_value('marital_status'),'RGXINT'):1,
                'id_dc_agama' => $this->regex->_genRegex($this->form_validation->set_value('religion'),'RGXINT'),
                'kode_pos' => $this->regex->_genRegex($this->form_validation->set_value('zipcode'),'RGXQSL'),
                'is_active' => 1,
                'kode_kelompok' => $this->regex->_genRegex($this->form_validation->set_value('kelompok_pasien'),'RGXINT'),
                'kode_perusahaan' => isset($_POST['kode_perusahaan'])?$this->regex->_genRegex($this->form_validation->set_value('kode_perusahaan'),'RGXINT'):NULL,
            );

          //print_r($dataexc);die;
            
            if(isset($_FILES['path_foto']['name'])){
                /*hapus dulu file yang lama*/
                if( $id != 0 ){
                    $path_foto = $this->db->get_where('mt_master_pasien', array('no_mr' => $mr) )->row();
                    if ($path_foto->url_foto_pasien != NULL) {
                        unlink(PATH_PHOTO_PROFILE_DEFAULT.$path_foto->url_foto_pasien.'');
                    }
                }
                $dataexc['url_foto_pasien'] = $this->upload_file->doUpload('path_foto', PATH_PHOTO_PROFILE_DEFAULT);
            }
                            
            if($cek1==0){
                /*get max no mr*/
                $cekMaxMr = $this->db->query("select TOP 1 no_mr from mt_master_pasien where LEN(no_mr)=8 order by no_mr desc ")->row();
                
                $mrID = $cekMaxMr->no_mr + 1;
                
                $panjang_mr=strlen($mrID);
                $sisa_panjang=8-$panjang_mr;
                $tambah_nol="";
                
                for ($i=1;$i<=$sisa_panjang;$i++){
                    $tambah_nol=$tambah_nol."0";
                }

                $mrID=$tambah_nol.$mrID;

                $dataexc['no_mr'] = $mrID;
                $dataexc['create_date'] = date('Y-m-d H:i:s');
                $dataexc['no_mr_barcode'] = $mrID;
                $dataexc['masa_mulai'] = date('Y-m-d h:i:s');
                $dataexc['masa_selesai'] = $this->input->post('tgl_akhir_kepemilikan');
                $dataexc['jam_lahir'] = $this->input->post('dob_pasien');

                $dataexc['nama_ayah'] = $this->input->post('nama_ayah_pasien');
                $dataexc['pekerjaan_ayah'] = $this->input->post('job');

                $dataexc['mr_ibu'] = $this->input->post('mr_ibu');
                $dataexc['nama_ibu'] = $this->input->post('nama_ibu_pasien');

                $dataexc['berat_badan'] = $this->input->post('berat_badan');
                $dataexc['panjang_badan'] = $this->input->post('panjang_badan');

                //print_r($dataexc);die;

                /*save pasien */
                $this->Input_pasien_baru->save('mt_master_pasien', $dataexc);

                /*save logs*/
                $this->logs->save('mt_master_pasien', $newId, 'insert new record on '.$this->title.' module', json_encode($dataexc),'id_mt_master_pasien');

                $this->Input_pasien_baru->update('ri_bayi_lahir', array('flag_lahir' => 1), array('id_bayi' => $this->input->post('id_bayi')));

                if ($this->db->trans_status() === FALSE)
                {
                    $this->db->trans_rollback();
                    echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
                }
                else
                {
                    $this->db->trans_commit();
                    echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'no_mr' => $dataexc['no_mr']));
                }

            } else {

                echo json_encode(array('status' => 301, 'message' => 'Maaf Pasien Sudah Terdaftar, silahkan cek kembali data pasien tersebut'));            

            }
        
        }

    }

    public function search_pasien() { 
        
        /*define variable data*/
        
        $dob = $this->input->get('dob');

        $name = $this->input->get('name');
        
        //print_r($name);die;

        /*return search pasien*/

        $data_pasien = $this->Input_pasien_baru->search_pasien_by_keyword($dob, $name); 
        

        $data = array(

            'count' => count($data_pasien),

            'result' => $data_pasien,
            
        );

        echo json_encode( $data );
    
    }

}



/* End of file example.php */

/* Location: ./application/functiones/example/controllers/example.php */
