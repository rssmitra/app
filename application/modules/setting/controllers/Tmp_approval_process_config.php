<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tmp_approval_process_config extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'setting/Tmp_approval_process_config');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('Tmp_approval_process_config_model', 'Tmp_approval_process_config');
        /*enable profiler*/
        $this->output->enable_profiler(false);
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'Title';

    }

    public function index() { 
        /*define variable data*/
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show()
        );
        /*load view index*/
        $this->load->view('Tmp_approval_process_config/index', $data);
    }

    public function form($id='')
    {
        /*if id is not null then will show form edit*/
        if( $id != '' ){
            /*breadcrumbs for edit*/
            $this->breadcrumbs->push('Edit '.strtolower($this->title).'', 'Tmp_approval_process_config/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
            /*get value by id*/
            $data['value'] = $this->Tmp_approval_process_config->get_by_id($id);
            /*initialize flag for form*/
            $data['flag'] = "update";
        }else{
            /*breadcrumbs for create or add row*/
            $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Tmp_approval_process_config/'.strtolower(get_class($this)).'/form');
            /*initialize flag for form add*/
            $data['flag'] = "create";
        }
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Tmp_approval_process_config/form', $data);
    }
    /*function for view data only*/
    public function show($id)
    {
        /*breadcrumbs for view*/
        $this->breadcrumbs->push('View '.strtolower($this->title).'', 'Tmp_approval_process_config/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*define data variabel*/
        $data['value'] = $this->Tmp_approval_process_config->get_by_id($id);
        $data['title'] = $this->title;
        $data['flag'] = "read";
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Tmp_approval_process_config/form', $data);
    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->Tmp_approval_process_config->get_datatables();
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
            $row[] = '<div class="center">
                        '.$this->authuser->show_button('setting/Tmp_approval_process_config','R',$row_list->id,2).'
                        '.$this->authuser->show_button('setting/Tmp_approval_process_config','U',$row_list->id,2).'
                        '.$this->authuser->show_button('setting/Tmp_approval_process_config','D',$row_list->id,2).'
                      </div>'; 
            $row[] = '<div class="center">'.$row_list->id.'</div>';
            $row[] = strtoupper($row_list->function);
            $row[] = $row_list->fullname;
            // Secret code as password field with eye icon
            $row[] = '<div class="input-group" style="max-width:160px;">'
                .'<input type="password" class="form-control secret-code-field" value="'.htmlspecialchars($row_list->secret_code).'" readonly style="background:#fff;" />'
                .'<span class="input-group-addon" style="cursor:pointer;background:#fff;border-left:none;" onclick="toggleSecretCode(this)"><i class="fa fa-eye"></i></span>'
                .'</div>';
            $row[] = $row_list->description;
            $row[] = ($row_list->is_active == 'Y') ? '<div class="center"><span class="label label-sm label-success">Active</span></div>' : '<div class="center"><span class="label label-sm label-danger">Not active</span></div>';
            $row[] = $this->logs->show_logs_record_datatable($row_list);

            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Tmp_approval_process_config->count_all(),
                        "recordsFiltered" => $this->Tmp_approval_process_config->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function process()
    {
       
        $this->load->library('form_validation');
        $val = $this->form_validation;
        $val->set_rules('user_id', 'User ID', 'trim|required');
        $val->set_rules('function', 'Function', 'trim|required');
        $val->set_rules('secret_code', 'Secret Code', 'trim|required|is_unique[user_approval_modul.secret_code]');
        $val->set_rules('description', 'Description', 'trim|xss_clean');

        $val->set_message('is_unique', " \"%s\" sudah pernah digunakan");
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

            $dataexc = array(
                'user_id' => $this->regex->_genRegex($val->set_value('user_id'), 'RGXINT'),
                'function' => $this->regex->_genRegex($val->set_value('function'), 'RGXQSL'),
                'secret_code' => $this->regex->_genRegex($val->set_value('secret_code'), 'RGXQSL'),
                'description' => $this->regex->_genRegex($val->set_value('description'), 'RGXQSL'),
                'is_active' => $this->input->post('is_active'),
            );
            //print_r($dataexc);die;
            if($id==0){
                $dataexc['created_date'] = date('Y-m-d H:i:s');
                $dataexc['created_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                /*save post data*/
                $newId = $this->Tmp_approval_process_config->save($dataexc);
                /*save logs*/
                $this->logs->save('user_approval_modul', $newId, 'insert new record on '.$this->title.' module', json_encode($dataexc),'id');
            }else{
                $dataexc['updated_date'] = date('Y-m-d H:i:s');
                $dataexc['updated_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                /*update record*/
                $this->Tmp_approval_process_config->update(array('id' => $id), $dataexc);
                $newId = $id;
                /*save logs*/
                $this->logs->save('user_approval_modul', $newId, 'update record on '.$this->title.' module', json_encode($dataexc),'id');
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
            if($this->Tmp_approval_process_config->delete_by_id($toArray)){
                $this->logs->save('user_approval_modul', $id, 'delete record', '', 'id');
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
/* Location: ./application/functiones/example/controllers/example.php */
