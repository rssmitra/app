<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Kepeg_log_aktifitas extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'kepegawaian/Kepeg_log_aktifitas');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('Kepeg_log_aktifitas_model', 'Kepeg_log_aktifitas');
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
        $this->load->view('Kepeg_log_aktifitas/index', $data);
    }

    public function form($id='')
    {
        /*if id is not null then will show form edit*/
        if($id != ''){
            /*breadcrumbs for edit*/
            $this->breadcrumbs->push('Edit '.strtolower($this->title).'', 'Kepeg_log_aktifitas/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
            /*get value by id*/
            $data['value'] = $this->Kepeg_log_aktifitas->get_by_id($id);
            // echo '<pre>';print_r($data);die;
            /*initialize flag for form*/
            $data['flag'] = "update";
        }else{
            /*breadcrumbs for create or add row*/
            $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Kepeg_log_aktifitas/'.strtolower(get_class($this)).'/form');
            /*initialize flag for form add*/
            $data['flag'] = "create";
        }
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Kepeg_log_aktifitas/form', $data);
    }
    
    /*function for view data only*/
    public function show($id)
    {
        /*breadcrumbs for view*/
        $this->breadcrumbs->push('View '.strtolower($this->title).'', 'Kepeg_log_aktifitas/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*define data variabel*/
        $data['value'] = $this->Kepeg_log_aktifitas->get_by_id($id);
        $data['title'] = $this->title;
        $data['flag'] = "read";
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Kepeg_log_aktifitas/form', $data);
    }

    public function show_detail( $id )
    {   
        $fields = $this->master->list_fields( 'kepeg_log_aktifitas' );
        // print_r($fields);die;
        $data = $this->Kepeg_log_aktifitas->get_by_id($id);
        $html = $this->master->show_detail_row_table( $fields, $data );      

        echo json_encode( array('html' => $html) );
    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->Kepeg_log_aktifitas->get_datatables();
        
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center">
                        <label class="pos-rel">
                            <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->id.'"/>
                            <span class="lbl"></span>
                        </label>
                      </div>';
            $row[] = '';
            $row[] = $row_list->id;
            $row[] = '<div class="center">
                        <div class="btn-group">
                        <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">
                            <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-inverse">
                            <li>'.$this->authuser->show_button('kepegawaian/Kepeg_log_aktifitas','R',$row_list->id,6).'</li>
                            <li>'.$this->authuser->show_button('kepegawaian/Kepeg_log_aktifitas','U',$row_list->id,6).'</li>
                            <li>'.$this->authuser->show_button('kepegawaian/Kepeg_log_aktifitas','D',$row_list->id,6).'</li>
                        </ul>
                        </div>
                    </div>';
            $row[] = '<div class="center">'.$row_list->id.'</div>';
            $row[] = $row_list->nama_pegawai;
            $row[] = $this->tanggal->formatDate($row_list->tanggal);
            $row[] = '<div class="center">'.strtoupper($row_list->jenis_pekerjaan).'</div>';
            $row[] = $this->master->br2nl($row_list->deskripsi_pekerjaan);
            switch ($row_list->status_pekerjaan) {
                case 'selesai':
                    $status = '<span class="label label-success">Selesai</span>';
                    break;
                case 'pending':
                    $status = '<span class="label label-warning">Pending</span>';
                    break;
                case 'on progress':
                    $status = '<span class="label label-info">On Progress</span>';
                    break;
                
                default:
                    $status = '<span class="label label-success">Selesai</span>';
                    break;
            }
            $row[] = '<div class="center">'.$status.'</div>';
            $row[] = $row_list->catatan;
                   
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Kepeg_log_aktifitas->count_all(),
                        "recordsFiltered" => $this->Kepeg_log_aktifitas->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function process()
    {
        // echo '<pre>';print_r($_POST);die;
        $this->load->library('form_validation');
        $val = $this->form_validation;

        $val->set_rules('tanggal','Tanggal', 'trim|required');
        $val->set_rules('nama_pegawai','Nama Pegawai', 'trim|required');
        $val->set_rules('status_pekerjaan','Status Pekerjaan', 'trim|required'); 
        $val->set_rules('jenis_pekerjaan','Jenis Pekerjaan', 'trim|required'); 
        $val->set_rules('catatan','Catatan', 'trim|required'); 
        $val->set_rules('deskripsi_pekerjaan','Deksiprsi Pekerjaan', 'trim|required'); 
        $val->set_rules('is_active','is_active', 'trim|required'); 

        $val->set_message('required', "Silahkan isi field \"%s\"");

        if ($val->run() == FALSE)
        {
            $val->set_error_delimiters('<div style="color:white">', '</div>');
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            $this->db->trans_begin();
            
            $id = ($this->input->post('id'))?$this->regex->_genRegex($this->input->post('id'),'RGXINT'):0;
            $deskripsi_pekerjaan = $this->master->br2nl($val->set_value('deskripsi_pekerjaan'));
            $catatan = $this->master->br2nl($val->set_value('catatan'));

            $dataexc = array(
                'tanggal' => $this->regex->_genRegex($val->set_value('tanggal'), 'RGXQSL'),
                'nama_pegawai' => $this->regex->_genRegex($val->set_value('nama_pegawai'), 'RGXQSL'),
                'status_pekerjaan' => $this->regex->_genRegex($val->set_value('status_pekerjaan'), 'RGXQSL'),
                'jenis_pekerjaan' => $this->regex->_genRegex($val->set_value('jenis_pekerjaan'), 'RGXQSL'),
                'deskripsi_pekerjaan' => $this->regex->_genRegex($val->set_value('deskripsi_pekerjaan'), 'RGXQSL'),
                'catatan' => $this->regex->_genRegex($catatan, 'RGXQSL'),
            );

            if($id==0){
                $dataexc['created_date'] = date('Y-m-d H:i:s');
                $dataexc['created_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                
                // save data pegawai
                $newId = $this->Kepeg_log_aktifitas->save('kepeg_log_aktifitas', $dataexc);
                // print_r($this->db->last_query());die;
            }else{
                $dataexc['updated_date'] = date('Y-m-d H:i:s');
                $dataexc['updated_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                /*update record*/
                $this->Kepeg_log_aktifitas->update('kepeg_log_aktifitas', array('id' => $id), $dataexc);
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
            if($this->Kepeg_log_aktifitas->delete_by_id($toArray)){
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
