<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dokter extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'reference/tabel/dokter');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('tabel/Dokter_model', 'dokter');
        /*enable profiler*/
        $this->output->enable_profiler(false);
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'dokter';

    }

    public function index() {
        /*define variable data*/
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show()
        );
        /*load view index*/
        $this->load->view('tabel/dokter/index', $data);
    }
    
    

    public function form($id='')
    {
        /*if id is not null then will show form edit*/
        if( $id != '' ){
            /*breadcrumbs for edit*/
            $this->breadcrumbs->push('Edit '.$this->title.'', 'reference/tabel/dokter/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
            /*get value by id*/
            $data['value'] = $this->dokter->get_by_id($id);
            // print_r($data); die;
            /*initialize flag for form*/
            $data['flag'] = "update";
        }else{
            /*breadcrumbs for create or add row*/
            $this->breadcrumbs->push('Add '.$this->title.'', 'reference/tabel/dokter/'.strtolower(get_class($this)).'/form');
            /*initialize flag for form add*/
            $data['flag'] = "create";
        }
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('tabel/dokter/form', $data);
    }
    /*function for view data only*/
    public function show($id)
    {
        /*breadcrumbs for view*/
        $this->breadcrumbs->push('View '.$this->title.'', 'reference/tabel/dokter/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*define data variabel*/
        $data['value'] = $this->dokter->get_by_id($id);
        $data['title'] = $this->title;
        $data['flag'] = "read";
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('tabel/dokter/form', $data);
    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->dokter->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();

            $row[] = '';
            $row[] = '';
            $row[] = $row_list->kode_dokter;
            $row[] = $row_list->kode_dokter;
            $row[] = strtoupper($row_list->nama_pegawai);
            $row[] = $row_list->no_sip;
            $row[] = $row_list->nama_spesialisasi;
            $file_ttd = (file_exists(PATH_TTD_FILE.$row_list->ttd)) ? '<a href="'.base_url().PATH_TTD_FILE.$row_list->ttd.'" target="_blank"><img src="'.PATH_TTD_FILE.$row_list->ttd.'" width="150px"></a>' : '';
            $file_stamp = (file_exists(PATH_TTD_FILE.$row_list->stamp)) ? '<a href="'.base_url().PATH_TTD_FILE.$row_list->stamp.'" target="_blank"><img src="'.PATH_TTD_FILE.$row_list->stamp.'" width="180px"></a>' : '';

            // $row[] = $this->dokter->get_bagian($row_list->kode_dokter);
            //$row[] = $row_list->nama_bagian;
            $row[] = $file_ttd;
            $row[] = $file_stamp;
            $status_dr = ($row_list->status_dr == 1)? '<span class="label label-xs label-success"> Aktif </span>': '<span class="label label-xs label-danger"> Tidak Aktif </span>';
            $row[] = '<div class="center">'.$status_dr.'</div>';
            $row[] = '<div class="center"><div class="hidden-sm hidden-xs action-buttons">
                        '.$this->authuser->show_button('reference/tabel/dokter','R',$row_list->kode_dokter,2).'
                        '.$this->authuser->show_button('reference/tabel/dokter','U',$row_list->kode_dokter,2).'
                        '.$this->authuser->show_button('reference/tabel/dokter','D',$row_list->kode_dokter,2).'
                      </div>
                      <div class="hidden-md hidden-lg">
                        <div class="inline pos-rel">
                            <button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto"><i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
                            </button>
                            <ul class="dropdown-dokter dropdown-only-icon dropdown-yellow dropdown-dokter-right dropdown-caret dropdown-close">
                                <li>'.$this->authuser->show_button('reference/tabel/dokter','R',$row_list->kode_dokter,4).'</li>
                                <li>'.$this->authuser->show_button('reference/tabel/dokter','U',$row_list->kode_dokter,4).'</li>
                                <li>'.$this->authuser->show_button('reference/tabel/dokter','D',$row_list->kode_dokter,4).'</li>
                            </ul>
                        </div>
                    </div></div>'; 

            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->dokter->count_all(),
                        "recordsFiltered" => $this->dokter->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }


    public function process()
    {
        // print_r($_POST);die;
        $this->load->library('form_validation');
        $val = $this->form_validation;
        $val->set_rules('id', 'Kode Dokter', 'trim|required');
        $val->set_rules('nama_pegawai', 'Nama Pegawai', 'trim|required');
        $val->set_rules('no_sip', 'No SIP', 'trim|required');
        $val->set_rules('kode_spesialisasi', 'Spesialisasi', 'trim|required');
        $val->set_rules('no_mr', 'No MR', 'trim');
        $val->set_rules('status', 'No MR', 'trim');

        $val->set_message('required', "Silahkan isi field \"%s\"");

        if ($val->run() == FALSE)
        {
            $val->set_error_delimiters('<div style="color:white">', '</div>');
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            $this->db->trans_begin();
            $id = ($this->input->post('id'))?$this->input->post('id'):0;
            //printf($id);
            //break;
            $dataexc = array(
                'nama_pegawai' => $val->set_value('nama_pegawai'),
                'no_sip' => $val->set_value('no_sip'),
                'kode_spesialisasi' => $val->set_value('kode_spesialisasi'),
                'no_mr' => $val->set_value('no_mr'),
                'status_dr' => $val->set_value('status'),
            );

            // ttd
            if(isset($_FILES['ttd']['name'])){
                /*hapus dulu file yang lama*/
                if( $id != 0 ){
                    $profile = $this->dokter->get_by_id($id);
                    if ($profile->ttd != NULL || $profile->ttd != 0) {
                        unlink(PATH_TTD_FILE.$profile->ttd.'');
                    }
                }
                $dataexc['ttd'] = $this->upload_file->doUpload('ttd', PATH_TTD_FILE);
            }

            if(isset($_FILES['stamp']['name'])){
                /*hapus dulu file yang lama*/
                if( $id != 0 ){
                    $profile = $this->dokter->get_by_id($id);
                    // if ($profile->stamp != NULL || $profile->stamp != 0) {
                    if (file_exists(PATH_TTD_FILE.$profile->stamp)) {
                        unlink(PATH_TTD_FILE.$profile->stamp);
                    }
                }
                $dataexc['stamp'] = $this->upload_file->doUpload('stamp', PATH_TTD_FILE);
            }

            if($id==0){
               $this->dokter->save('mt_karyawan', $dataexc);
                $newId = $this->db->insert_id();
               
            }else{
                 /*update record*/
                $this->dokter->update('mt_karyawan', array('kode_dokter' => $id), $dataexc);
                
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
        $id=$this->input->post('ID')?$this->input->post('ID',TRUE):null;
        $toArray = explode(',',$id);
        if($id!=null){
            if($this->dokter->delete_by_id($toArray)){
                //$this->logs->save('dokter', $id, 'delete record', '', 'id_mt_dokter');
                echo json_encode(array('status' => 200, 'message' => 'Proses Hapus Data Berhasil Dilakukan'));
            }else{
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Hapus Data Gagal Dilakukan'));
            }
        }else{
            echo json_encode(array('status' => 301, 'message' => 'Tidak ada item yang dipilih'));
        }
        
    }


}


/* End of file dokter.php */
/* Location: ./application/modules/dokter/controllers/dokter.php */
