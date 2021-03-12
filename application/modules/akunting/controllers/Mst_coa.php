<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mst_coa extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'akunting/Mst_coa');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('Mst_coa_model', 'Mst_coa');
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
        $this->load->view('Mst_coa/index', $data);
    }

    public function form($id='')
    {
        /*if id is not null then will show form edit*/
        if( $id != '' ){
            /*breadcrumbs for edit*/
            $this->breadcrumbs->push('Edit '.strtolower($this->title).'', 'Mst_coa/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
            /*get value by id*/
            $data['value'] = $this->Mst_coa->get_by_id($id);
            $data['parent'] = $this->Mst_coa->get_parent_coa($id);
            /*initialize flag for form*/
            $data['flag'] = "update";
        }else{
            /*breadcrumbs for create or add row*/
            $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Mst_coa/'.strtolower(get_class($this)).'/form');
            /*initialize flag for form add*/
            $data['flag'] = "create";
            $data['acc_no'] = $this->master->getNewKodeAkun(1);
        }
       // echo '<pre>'; print_r($data);die; 
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Mst_coa/form', $data);
    }
    /*function for view data only*/
    public function show($id)
    {
        /*breadcrumbs for view*/
        $this->breadcrumbs->push('View '.strtolower($this->title).'', 'Mst_coa/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*define data variabel*/
        $data['value'] = $this->Mst_coa->get_by_id($id);
        $data['title'] = $this->title;
        $data['flag'] = "read";
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Mst_coa/form', $data);
    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->Mst_coa->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center">
                        <label class="pos-rel">
                            <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->acc_no.'"/>
                            <span class="lbl"></span>
                        </label>
                      </div>';
            
            $row[] = '<div class="center">'.$row_list->acc_no_rs.'</div>';
            $padding = ($row_list->level_coa != 1) ? $row_list->level_coa * 8 : 0;
            $icon = ($row_list->level_coa != 5) ? '<i class="fa fa-angle-down"></i>' : '';
            $saldo = ($row_list->acc_type == 'D') ? 'Debet' : 'Kredit';
            $row[] = '<span style="padding-left: '.$padding.'px !important"> '.$icon.' '.ucfirst($row_list->acc_nama).'</span>';
            $row[] = '<div class="center">'.$saldo.'</div>';
            $row[] = '<div class="center">'.$row_list->level_coa.'</div>';
            $row[] = ($row_list->is_active == 'Y') ? '<div class="center"><span class="label label-sm label-success">Active</span></div>' : '<div class="center"><span class="label label-sm label-danger">Not active</span></div>';
            $row[] = '<div class="center">
                        '.$this->authuser->show_button('akunting/Mst_coa','R',$row_list->acc_no,2).'
                        '.$this->authuser->show_button('akunting/Mst_coa','U',$row_list->acc_no,2).'
                        '.$this->authuser->show_button('akunting/Mst_coa','D',$row_list->acc_no,2).'
                      </div>'; 

            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Mst_coa->count_all(),
                        "recordsFiltered" => $this->Mst_coa->count_filtered(),
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
        $val->set_rules('acc_no_rs', 'Kode Akun', 'trim|required');
        $val->set_rules('acc_ref', 'Ref', 'trim|required');
        $val->set_rules('acc_nama', 'Nama Akun', 'trim|required');
        $val->set_rules('acc_type', 'Saldo Normal', 'trim|required');
        $val->set_rules('level_coa', 'Level', 'trim|required');

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

            $acc_no = str_replace('.','',$_POST['acc_no_rs']);
            $dataexc = array(
                'acc_no' => $this->regex->_genRegex($acc_no, 'RGXQSL'),
                'acc_no_rs' => $this->regex->_genRegex($val->set_value('acc_no_rs'), 'RGXQSL'),
                'acc_nama' => $this->regex->_genRegex($val->set_value('acc_nama'), 'RGXQSL'),
                'acc_type' => $this->regex->_genRegex($val->set_value('acc_type'), 'RGXQSL'),
                'acc_ref' => $this->regex->_genRegex($val->set_value('acc_ref'), 'RGXQSL'),
                'level_coa' => $this->regex->_genRegex($val->set_value('level_coa'), 'RGXQSL'),
                'is_active' => $this->input->post('is_active'),
            );
            
            if($id==0){
                $dataexc['created_date'] = date('Y-m-d H:i:s');
                $dataexc['created_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                /*save post data*/
                $newId = $this->Mst_coa->save($dataexc);
            }else{
                $dataexc['updated_date'] = date('Y-m-d H:i:s');
                $dataexc['updated_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                /*update record*/
                $this->Mst_coa->update(array('id_mt_account' => $id), $dataexc);
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
            if($this->Mst_coa->delete_by_id($toArray)){
                $this->logs->save('tmp_mst_function', $id, 'delete record', '', 'id_mt_account');
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
