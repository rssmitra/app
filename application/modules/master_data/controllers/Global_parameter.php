<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Global_parameter extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'master_data/Global_parameter?'.http_build_query($_GET).'');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('master_data/Global_parameter_model', 'Global_parameter');
        /*enable profiler*/
        $this->output->enable_profiler(false);
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'Title';

    }

    public function index() {
        /*define variable data*/
        $string = isset($_GET['flag'])?'flag='.$_GET['flag'].'':'?';
        $string .= isset($_GET['kode_bagian'])?'&kode_bagian='.$_GET['kode_bagian'].'':'';
        $data = array(
            'title' => ucwords(str_replace('_',' ',$_GET['flag'])),
            'breadcrumbs' => $this->breadcrumbs->show(),
            'flag_string' => $string,
            'kode_bagian' => isset($_GET['kode_bagian'])?$_GET['kode_bagian']:'',
        );
        /*load view index*/
        $this->load->view('Global_parameter/index', $data);
    }

    public function form($id='')
    {
        /*if id is not null then will show form edit*/
        if( $id != '' ){
            /*breadcrumbs for edit*/
            $this->breadcrumbs->push('Edit '.strtolower($this->title).'', 'master_data/Global_parameter/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id.'?'.http_build_query($_GET));
            /*get value by id*/
            $data['value'] = $this->Global_parameter->get_by_id($id);
            /*initialize flag for form*/
            $data['flag'] = "update";
        }else{
            /*breadcrumbs for create or add row*/
            $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'master_data/Global_parameter/'.strtolower(get_class($this)).'/form');
            /*initialize flag for form add*/
            $data['flag'] = "create";
        }
        $data['flag_string'] = $_GET['flag'];
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        if( in_array($_GET['flag'], array('banner_regon') )){
            $this->load->view('Global_parameter/form_'.$_GET['flag'].'', $data);
        }else{
            $this->load->view('Global_parameter/form', $data);
        }
    }

    /*function for view data only*/
    public function show($id)
    {
        /*breadcrumbs for view*/
        $this->breadcrumbs->push('View '.strtolower($this->title).'', 'master_data/Global_parameter/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id.'?'.http_build_query($_GET));
        /*define data variabel*/
        $data['value'] = $this->Global_parameter->get_by_id($id);
        $data['title'] = $this->title;
        $data['flag'] = "read";
        $data['flag_string'] = $_GET['flag'];
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Global_parameter/form', $data);
    }

    public function get_detail( $id )
    {
        $fields = $this->Global_parameter->list_fields();
        $data = $this->Global_parameter->get_by_id( $id );
        $html = $this->master->show_detail_row_table( $fields, $data );      

        echo json_encode( array('html' => $html) );
    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->Global_parameter->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center"><label class="pos-rel">
                        <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->auto_id.'"/>
                        <span class="lbl"></span>
                    </label></div>';
            $row[] = '';
            $row[] = $row_list->auto_id;
            $row[] = '<div class="center"><div class="btn-group">
                        <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">
                            <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-inverse">
                            <li>'.$this->authuser->show_button('master_data/Global_parameter?'.http_build_query($_GET).'','R',$row_list->auto_id,67).'</li>
                            <li>'.$this->authuser->show_button('master_data/Global_parameter?'.http_build_query($_GET).'','U',$row_list->auto_id,67).'</li>
                            <li>'.$this->authuser->show_button('master_data/Global_parameter?'.http_build_query($_GET).'','D',$row_list->auto_id,6).'</li>
                        </ul>
                      </div></div>';
            
            $row[] = '<div class="center">'.$row_list->auto_id.'</div>';
            $row[] = $row_list->label;
            $row[] = $row_list->value;
            $row[] = $row_list->desc_text;
            $row[] = ($row_list->is_active == 'Y') ? '<div class="center"><span class="label label-sm label-success">Active</span></div>' : '<div class="center"><span class="label label-sm label-danger">Not active</span></div>';
            $row[] = $this->logs->show_logs_record_datatable($row_list);
                   
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Global_parameter->count_all(),
                        "recordsFiltered" => $this->Global_parameter->count_filtered(),
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
        $val->set_rules('label', 'Label', 'trim|required');
        $val->set_rules('desc_text', 'Keterangan', 'trim');
        if( in_array( $_GET['flag'], array('banner_regon') ) ){
            $val->set_rules('value', 'Value', 'trim');
        }else{
            $val->set_rules('value', 'Value', 'trim|required');
        }
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

            if(isset($_FILES['value']['name'])){
                /*hapus dulu file yang lama*/
                if( $id != 0 ){
                    $profile = $this->Global_parameter->get_by_id($id);
                    if ($profile->value != NULL) {
                        unlink(PATH_IMG_DEFAULT.$profile->value.'');
                    }
                }

                $datavalue = $this->upload_file->doUpload('value', PATH_IMG_DEFAULT);
            }

            $dataexc = array(
                'label' => $this->regex->_genRegex( $val->set_value('label') , 'RGXQSL'), 
                'value' => isset($datavalue) ? $datavalue : $this->regex->_genRegex( $val->set_value('value') , 'RGXQSL'), 
                'desc_text' => $this->regex->_genRegex( $val->set_value('desc_text') , 'RGXQSL'), 
                'is_active' => $this->regex->_genRegex( $this->input->post('is_active') , 'RGXQSL'), 
                'flag' => $this->regex->_genRegex( $this->input->get('flag') , 'RGXQSL'), 
            );

            foreach ($_GET as $key => $row_get) {
                $dataexc[$key] = $row_get;
            }
            

            if($id==0){
                $dataexc['created_date'] = date('Y-m-d H:i:s');
                $dataexc['created_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                /*save post data*/
                $this->Global_parameter->save($dataexc);
                $newId = $this->db->insert_id();
            }else{
                $dataexc['updated_date'] = date('Y-m-d H:i:s');
                $dataexc['updated_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                /*update record*/
                $this->Global_parameter->update(array('auto_id' => $id), $dataexc);
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
        $id=$this->input->post('ID')?$this->input->post('ID',TRUE):null;
        $toArray = explode(',',$id);
        if($id!=null){
            if($this->Global_parameter->delete_by_id($toArray)){
                echo json_encode(array('status' => 200, 'message' => 'Proses Hapus Data Berhasil Dilakukan'));
            }else{
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Hapus Data Gagal Dilakukan'));
            }
        }else{
            echo json_encode(array('status' => 301, 'message' => 'Tidak ada item yang dipilih'));
        }
        
    }

}


/* End of file Gender.php */
/* Location: ./application/modules/product_type/controllers/product_type.php */
