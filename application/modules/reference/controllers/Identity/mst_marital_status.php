<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mst_marital_status extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'reference/identity/mst_marital_status');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('identity/mst_marital_status_model', 'mst_marital_status');
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
        $this->load->view('identity/mst_marital_status/index', $data);
    }

    public function form($id='')
    {
        /*if id is not null then will show form edit*/
        if( $id != '' ){
            /*breadcrumbs for edit*/
            $this->breadcrumbs->push('Edit '.strtolower($this->title).'', 'reference/identity/mst_marital_status/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
            /*get value by id*/
            $data['value'] = $this->mst_marital_status->get_by_id($id);
            /*initialize flag for form*/
            $data['flag'] = "update";
        }else{
            /*breadcrumbs for create or add row*/
            $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'reference/identity/mst_marital_status/'.strtolower(get_class($this)).'/form');
            /*initialize flag for form add*/
            $data['flag'] = "create";
        }
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('identity/mst_marital_status/form', $data);
    }
    /*function for view data only*/
    public function show($id)
    {
        /*breadcrumbs for view*/
        $this->breadcrumbs->push('View '.strtolower($this->title).'', 'reference/identity/mst_marital_status/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*define data variabel*/
        $data['value'] = $this->mst_marital_status->get_by_id($id);
        $data['title'] = $this->title;
        $data['flag'] = "read";
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('identity/mst_marital_status/form', $data);
    }


    public function get_data()
    {
        /*get data from model*/
        $list = $this->mst_marital_status->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center"><label class="pos-rel">
                        <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->ms_id.'"/>
                        <span class="lbl"></span>
                    </label></div>';
            $row[] = '<div class="center"><div class="hidden-sm hidden-xs action-buttons">
                        '.$this->authuser->show_button('reference/identity/mst_marital_status','R',$row_list->ms_id,2).'
                        '.$this->authuser->show_button('reference/identity/mst_marital_status','U',$row_list->ms_id,2).'
                        '.$this->authuser->show_button('reference/identity/mst_marital_status','D',$row_list->ms_id,2).'
                      </div>
                      <div class="hidden-md hidden-lg">
                        <div class="inline pos-rel">
                            <button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto"><i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
                            </button>
                            <ul class="dropdown-mst_marital_status dropdown-only-icon dropdown-yellow dropdown-mst_marital_status-right dropdown-caret dropdown-close">
                                <li>'.$this->authuser->show_button('reference/identity/mst_marital_status','R','',4).'</li>
                                <li>'.$this->authuser->show_button('reference/identity/mst_marital_status','U','',4).'</li>
                                <li>'.$this->authuser->show_button('reference/identity/mst_marital_status','D','',4).'</li>
                            </ul>
                        </div>
                    </div></div>';
            $row[] = $row_list->ms_id;
            $row[] = strtoupper($row_list->ms_name);
            $row[] = ($row_list->is_active == 'Y') ? '<div class="center"><span class="label label-sm label-success">Active</span></div>' : '<div class="center"><span class="label label-sm label-danger">Not active</span></div>';
            $row[] = $this->logs->show_logs_record_datatable($row_list);
                    
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->mst_marital_status->count_all(),
                        "recordsFiltered" => $this->mst_marital_status->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function process()
    {
        $this->load->library('form_validation');
        $val = $this->form_validation;
        $val->set_rules('ms_name', 'Nama Marital Status', 'trim|required');

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

            $dataexc = array(
                'ms_name' => $val->set_value('ms_name'),
                'is_active' => $this->input->post('is_active'),
            );
            if($id==0){
                $dataexc['created_date'] = date('Y-m-d H:i:s');
                $dataexc['created_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                /*save post data*/
                $this->mst_marital_status->save($dataexc);
                $newId = $this->db->insert_id();
                /*save logs*/
                $this->logs->save('mst_marital_status', $newId, 'insert new record on '.$this->title.' module', json_encode($dataexc),'ms_id');
            }else{
                $dataexc['updated_date'] = date('Y-m-d H:i:s');
                $dataexc['updated_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                /*update record*/
                $this->mst_marital_status->update(array('ms_id' => $id), $dataexc);
                $newId = $id;
                /*save logs*/
                $this->logs->save('mst_marital_status', $newId, 'update record on '.$this->title.' module', json_encode($dataexc),'ms_id');
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
            if($this->mst_marital_status->delete_by_id($toArray)){
                $this->logs->save('mst_marital_status', $id, 'delete record', '', 'ms_id');
                echo json_encode(array('status' => 200, 'message' => 'Proses Hapus Data Berhasil Dilakukan'));
            }else{
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Hapus Data Gagal Dilakukan'));
            }
        }else{
            echo json_encode(array('status' => 301, 'message' => 'Tidak ada item yang dipilih'));
        }
        
    }


}


/* End of file Marital Status.php */
/* Location: ./application/modules/mst_marital_status/controllers/mst_marital_status.php */
