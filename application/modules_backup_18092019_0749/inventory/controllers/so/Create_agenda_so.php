<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Create_agenda_so extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'inventory/so/Create_agenda_so');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('inventory/so/Create_agenda_so_model', 'Create_agenda_so');
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
        $this->load->view('so/Create_agenda_so/index', $data);
    }

    public function form($id='')
    {
        /*if id is not null then will show form edit*/
        if( $id != '' ){
            /*breadcrumbs for edit*/
            $this->breadcrumbs->push('Edit '.strtolower($this->title).'', 'Create_agenda_so/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
            /*get value by id*/
            $data['value'] = $this->Create_agenda_so->get_by_id($id);
            /*initialize flag for form*/
            $data['flag'] = "update";
        }else{
            /*breadcrumbs for create or add row*/
            $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Create_agenda_so/'.strtolower(get_class($this)).'/form');
            /*initialize flag for form add*/
            $data['flag'] = "create";
        }
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('so/Create_agenda_so/form', $data);
    }
    /*function for view data only*/
    public function show($id)
    {
        /*breadcrumbs for view*/
        $this->breadcrumbs->push('View '.strtolower($this->title).'', 'Create_agenda_so/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*define data variabel*/
        $data['value'] = $this->Create_agenda_so->get_by_id($id);
        $data['title'] = $this->title;
        $data['flag'] = "read";
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('so/Create_agenda_so/form', $data);
    }


    public function get_data()
    {
        /*get data from model*/
        $list = $this->Create_agenda_so->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center">
                        <label class="pos-rel">
                            <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->agenda_so_id.'"/>
                            <span class="lbl"></span>
                        </label>
                      </div>';
            $row[] = '<div class="center">
                        '.$this->authuser->show_button('inventory/so/Create_agenda_so','R',$row_list->agenda_so_id,2).'
                        '.$this->authuser->show_button('inventory/so/Create_agenda_so','U',$row_list->agenda_so_id,2).'
                        '.$this->authuser->show_button('inventory/so/Create_agenda_so','D',$row_list->agenda_so_id,2).'
                      </div>'; 
            $row[] = '<div class="center">'.$row_list->agenda_so_id.'</div>';
            $row[] = ucwords($row_list->agenda_so_name);
            $row[] = $this->tanggal->formatDate($row_list->agenda_so_date).' - '.$row_list->agenda_so_time;
            $row[] = $row_list->agenda_so_spv;
            $row[] = $row_list->agenda_so_desc;
            $row[] = ($row_list->is_active == 'Y') ? '<div class="center"><span class="label label-sm label-success">Active</span></div>' : '<div class="center"><span class="label label-sm label-danger">Not active</span></div>';
            $row[] = $this->logs->show_logs_record_datatable($row_list);
                   
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Create_agenda_so->count_all(),
                        "recordsFiltered" => $this->Create_agenda_so->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function process()
    {
       
        $this->load->library('form_validation');
        $val = $this->form_validation;
        $val->set_rules('agenda_so_name', 'Nama Agenda', 'trim|required');
        $val->set_rules('agenda_so_date', 'Tanggal', 'trim|required');
        $val->set_rules('agenda_so_time', 'Waktu/Jam', 'trim|required');
        $val->set_rules('agenda_so_spv', 'Penananggung Jawab', 'trim|required|xss_clean');
        $val->set_rules('agenda_so_desc', 'Keterangan', 'trim|xss_clean');
        $val->set_rules('is_active', 'Status Aktif', 'trim|required|xss_clean');

        $val->set_message('required', "Silahkan isi field \"%s\"");
        $val->set_message('integer', "Field \"%s\" harus diisi dengann angka");

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
                'agenda_so_name' => $this->regex->_genRegex($val->set_value('agenda_so_name'),'RGXQSL'),
                'agenda_so_date' => $this->regex->_genRegex($val->set_value('agenda_so_date'),'RGXQSL'),
                'agenda_so_time' => $this->regex->_genRegex($val->set_value('agenda_so_time'),'RGXQSL'),
                'agenda_so_spv' => $this->regex->_genRegex($val->set_value('agenda_so_spv'),'RGXQSL'),
                'agenda_so_desc' => $this->regex->_genRegex($val->set_value('agenda_so_desc'),'RGXQSL'),
                'is_active' => $this->regex->_genRegex($val->set_value('is_active'),'RGXQSL'),
            );
            
            if($id==0){
                $dataexc['created_date'] = date('Y-m-d H:i:s');
                $dataexc['created_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                $newId = $this->Create_agenda_so->save($dataexc);
                /*save logs*/
                $this->logs->save('tc_stok_opname_agenda', $newId, 'insert new record on '.$this->title.' module', json_encode($dataexc),'agenda_so_id');
            }else{
                $dataexc['updated_date'] = date('Y-m-d H:i:s');
                $dataexc['updated_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                /*print_r($dataexc);die;*/
                /*update record*/
                $this->Create_agenda_so->update(array('agenda_so_id' => $id), $dataexc);
                $newId = $id;
                $this->logs->save('tc_stok_opname_agenda', $newId, 'update record'.$this->title.' module', json_encode($dataexc), 'agenda_so_id');
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
            if($this->Create_agenda_so->delete_by_id($toArray)){
                $this->logs->save('tc_stok_opname_agenda', $id, 'delete record', '', 'agenda_so_id');
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
