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
        if( $id != '' ){
            /*breadcrumbs for edit*/
            $this->breadcrumbs->push('Edit '.strtolower($this->title).'', 'Kepeg_dt_pegawai/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
            /*get value by id*/
            $data['value'] = $this->Kepeg_dt_pegawai->get_by_id($id);
            /*initialize flag for form*/
            $data['flag'] = "update";
        }else{
            /*breadcrumbs for create or add row*/
            $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Kepeg_dt_pegawai/'.strtolower(get_class($this)).'/form');
            /*initialize flag for form add*/
            $data['flag'] = "create";
        }
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Kepeg_dt_pegawai/form', $data);
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
                        </ul>
                        </div>
                    </div>';
            
            $link_image = ( $row_list->pas_foto != NULL ) ? PATH_PHOTO_PEGAWAI.$row_list->pas_foto : PATH_PHOTO_PEGAWAI.'no-image.jpg' ;
            $jk = ($row_list->jk == 'P')?'Perempuan':'Laki-laki';
            $status_kepegawaian = ($row_list->kepeg_status_kerja == '211')?'Karyawan Tetap':'Karyawan KKWT';
            $row[] = '<div class="center"><a href="'.base_url().$link_image.'" target="_blank"><img src="'.base_url().$link_image.'" width="100px"></a><br> <b>'.$row_list->kepeg_nip.'</b></div>';
            $row[] = $row_list->kepeg_nik.'<br>'.$row_list->nama_pegawai.'<br>'.$jk;
            $row[] = $row_list->pendidikan_terakhir.'<br>'.ucwords($row_list->kepeg_tenaga_medis);
            $row[] = $row_list->nama_unit;
            $row[] = $row_list->nama_level.'<br>'.$row_list->kepeg_gol;
            $row[] = $status_kepegawaian.'<br>Aktif kerja : <br>'.$row_list->kepeg_tgl_aktif;
            $status_aktif = ($row_list->kepeg_status_aktif == 'Y') ? '<span class="label label-sm label-success">Active</span>' : '<span class="label label-sm label-danger">Not active</span>';
            $row[] = '<div class="center">'.$status_aktif.'</div>';
            $row[] = '<div class="center">
                        <a href="#" class="label label-xs label-success" onclick="getMenu('."'kepegawaian/Kepeg_dt_pegawai/form_jabatan/".$row_list->kepeg_id."'".')">Update Jabatan</a>
            </div>';
                   
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
        $val->set_rules('kepeg_nip','kepeg_nip', 'trim|required');
        $val->set_rules('nama_pegawai','nama_pegawai', 'trim|required');
        $val->set_rules('kepeg_no_telp','kepeg_no_telp', 'trim');
        $val->set_rules('kepeg_email','kepeg_email', 'trim|valid_email');
        $val->set_rules('kepeg_gol','kepeg_gol', 'trim|required');
        $val->set_rules('kepeg_pendidikan_terakhir','kepeg_pendidikan_terakhir', 'trim|required');
        $val->set_rules('kepeg_unit','kepeg_unit', 'trim|required');
        $val->set_rules('kepeg_level','kepeg_level', 'trim|required');
        $val->set_rules('kepeg_hak_perawatan','kepeg_hak_perawatan', 'trim');
        $val->set_rules('kepeg_tenaga_medis','kepeg_tenaga_medis', 'trim|required');
        $val->set_rules('kepeg_status_kerja','kepeg_status_kerja', 'trim|required');
        $val->set_rules('kepeg_tgl_aktif','kepeg_tgl_aktif', 'trim|required');
        $val->set_rules('kepeg_masa_kontrak','kepeg_masa_kontrak', 'trim');
        $val->set_rules('kepeg_status_aktif','kepeg_status_aktif', 'trim|required');

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

    public function delete()
    {
        $id=$this->input->post('ID')?$this->regex->_genRegex($this->input->post('ID',TRUE),'RGXQSL'):null;
        $toArray = explode(',',$id);
        if($id!=null){
            if($this->Kepeg_dt_pegawai->delete_by_id($toArray)){
                $this->logs->save('tmp_mst_level', $id, 'delete record', '', 'nik');
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
