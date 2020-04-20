<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Adm_publish_report extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'adm_pasien/publish_report/Adm_publish_report');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('adm_pasien/publish_report/Adm_publish_report_model', 'adm_publish_report');
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
        $this->load->view('publish_report/adm_publish_report/index', $data);
    }

    public function view_only() { 
        //echo '<pre>';print_r($this->session->all_userdata());
        /*define variable data*/
        
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show(),
            'result' => '',
            'flag' => $_GET['flag'],
            'date' => isset($_GET['from_tgl']) ? $_GET['from_tgl'] : date('Y-m-d'),
        );

        /*load view index*/
        $this->load->view('loket_kasir/Adm_resume_lhk/view_only', $data);
    }
    
    public function form($id='')
    {
        /*if id is not null then will show form edit*/
        if( $id != '' ){
            /*breadcrumbs for edit*/
            $this->breadcrumbs->push('Edit '.strtolower($this->title).'', 'adm_pasien/publish_report/Adm_publish_report/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
            /*get value by id*/
            $data['value'] = $this->adm_publish_report->get_by_id($id);
            /*initialize flag for form*/
            $data['flag'] = "update";
        }else{
            /*breadcrumbs for create or add row*/
            $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'adm_pasien/publish_report/Adm_publish_report/'.strtolower(get_class($this)).'/form');
            /*initialize flag for form add*/
            $data['flag'] = "create";
        }
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        
        /*load form view*/
        $this->load->view('publish_report/adm_publish_report/form', $data);
    }

    /*function for view data only*/
    public function show($id)
    {
        /*breadcrumbs for view*/
        $this->breadcrumbs->push('View '.strtolower($this->title).'', 'adm_pasien/publish_report/Adm_publish_report/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*define data variabel*/
        $data['value'] = $this->adm_publish_report->get_by_id($id);
        $data['title'] = $this->title;
        $data['flag'] = "read";
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('publish_report/adm_publish_report/form', $data);
    }

    public function show_view_json($id)
    {
        /*breadcrumbs for view*/
        $this->breadcrumbs->push('View '.strtolower($this->title).'', 'adm_pasien/publish_report/Adm_publish_report/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*define data variabel*/
        $data['value'] = $this->adm_publish_report->get_by_id($id);
        $data['title'] = $this->title;
        $data['flag'] = "update";
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $html = $this->load->view('publish_report/adm_publish_report/form_json', $data, true);
        echo json_encode(array('html' => $html));
    }

    public function show_detail( $id )
    {
        $fields = $this->adm_publish_report->list_fields();
        $data = $this->adm_publish_report->get_by_id( $id );
        $html = $this->master->show_detail_row_table( $fields, $data );      

        echo json_encode( array('html' => $html) );
    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->adm_publish_report->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center"><label class="pos-rel">
                        <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->id.'"/>
                        <span class="lbl"></span>
                    </label></div>';
            $row[] = '';
            $row[] = $row_list->id;
            $row[] = '<div class="center"><div class="btn-group">
                        <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">
                            <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-inverse">
                            '.$this->authuser->show_button_dropdown('adm_pasien/publish_report/Adm_publish_report', array('R','U','D') ,$row_list->id).'   
                        </ul>
                      </div></div>';
            
            $row[] = '<div class="center">'.$row_list->id.'</div>';
            $row[] = $row_list->principal_name;
            $row[] = $this->tanggal->formatDateFormDMY($row_list->date);
            $row[] = $row_list->title;
            $row[] = ($row_list->is_active == 'Y') ? '<div class="center"><span class="label label-sm label-success">Active</span></div>' : '<div class="center"><span class="label label-sm label-danger">Not active</span></div>';
            $row[] = $this->logs->show_logs_record_datatable($row_list);
             
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->adm_publish_report->count_all(),
                        "recordsFiltered" => $this->adm_publish_report->count_filtered(),
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
        $val->set_rules('date', 'Date', 'trim|required');
        $val->set_rules('end_date', 'End Date', 'trim|required');
        $val->set_rules('principal_id', 'Principal', 'trim');
        $val->set_rules('title', 'Description', 'trim|required');
        $val->set_rules('category', 'Categori', 'trim|required');

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
                'category' => $val->set_value('category'),
                'title' => $val->set_value('title'),
                'date' => $val->set_value('date'),
                'end_date' => $val->set_value('end_date'),
                'principal_id' => $val->set_value('principal_id'),
                'is_active' => $this->input->post('is_active'),
            );
            // print_r($dataexc);die;
            if($id==0){
                $dataexc['created_date'] = date('Y-m-d H:i:s');
                $dataexc['created_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                /*save post data*/
                $newId = $this->adm_publish_report->save($dataexc);
            }else{
                $dataexc['updated_date'] = date('Y-m-d H:i:s');
                $dataexc['updated_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                /*update record*/
                $this->adm_publish_report->update(array('id' => $id), $dataexc);
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
            if($this->adm_publish_report->delete_by_id($toArray)){
                echo json_encode(array('status' => 200, 'message' => 'Proses Hapus Data Berhasil Dilakukan'));
            }else{
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Hapus Data Gagal Dilakukan'));
            }
        }else{
            echo json_encode(array('status' => 301, 'message' => 'Tidak ada item yang dipilih'));
        }
        
    }

    public function check_existing(){
        /*cek existing data*/
        $count = $this->adm_publish_report->check_existing_by_agenttype_and_product();
        echo json_encode( array('count' => $count) );
    }

    public function get_highseason_date(){

        // get data
        $calendar_event = $this->adm_publish_report->get_data();
        $event = [];
        foreach ($calendar_event as $key => $value) {
            $event[] =  array(
                            'id' => $value->id_ak_tc_publish_report,
                            'title' => $value->kode_report,
                            'start' => $value->tanggal_transaksi,
                            'date_exist' => $value->tanggal_transaksi,
                            'end' => $value->tanggal_transaksi,
                            'className' => $this->get_label($value->flag),
                            'categoryId' => $value->flag,
                            'allDay' => false,
                        );
        }
        echo json_encode($event);
    }

    public function get_label($category){
        switch ($category) {
            case 'RI':
                $label = 'label label-danger';
                break;
            case 'RJ':
                $label = 'label label-warning';
                break;            
            default:
                $label = 'label label-info';
                break;
        }
        return $label;
    }


}


/* End of file Gender.php */
/* Location: ./application/modules/product_type/controllers/product_type.php */
