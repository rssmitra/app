<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class C_bagian extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'master_data/C_bagian');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('master_data/M_bagian_model', 'M_bagian');
        /*enable profiler*/
        $this->output->enable_profiler(false);
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'Title';

    }

    public function index() {
        /*define variable data*/
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show(),
        );
        /*load view index*/
        $this->load->view('Bagian/index', $data);
    }

    public function form($id='')
    {
        /*if id is not null then will show form edit*/
        if( $id != '' ){
            /*breadcrumbs for edit*/
            $this->breadcrumbs->push('Edit '.strtolower($this->title).'', 'master_data/C_bagian/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
            /*get value by id*/
            $data['value'] = $this->M_bagian->get_by_id($id);
            /*initialize flag for form*/
            $data['flag'] = "update";
        }else{
            /*breadcrumbs for create or add row*/
            $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'master_data/C_bagian/'.strtolower(get_class($this)).'/form');
            /*initialize flag for form add*/
            $data['flag'] = "create";
        }
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();

        // echo "<pre>"; print_r($data);die;
        
        $this->load->view('Bagian/form', $data);
    }

    /*function for view data only*/
    public function show($id)
    {
        /*breadcrumbs for view*/
        $this->breadcrumbs->push('View '.strtolower($this->title).'', 'master_data/C_bagian/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id.'?'.http_build_query($_GET));
        /*define data variabel*/
        $data['value'] = $this->M_bagian->get_by_id($id);
        $data['title'] = $this->title;
        $data['flag'] = "read";
        $data['flag_string'] = $_GET['flag'];
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Bagian/form', $data);
    }

    public function get_detail( $id )
    {
        $fields = $this->M_bagian->list_fields();
        $data = $this->M_bagian->get_by_id( $id );
        $html = $this->master->show_detail_row_table( $fields, $data );      

        echo json_encode( array('html' => $html) );
    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->M_bagian->get_datatables();
        // echo "<pre>"; print_r($list);die;
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center"><label class="pos-rel">
                        <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->id_mt_bagian.'"/>
                        <span class="lbl"></span>
                    </label></div>';
            $row[] = '';
            $row[] = $row_list->id_mt_bagian;
            $row[] = '<div class="center"><div class="btn-group">
                        <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">
                            <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-inverse">
                            <li>'.$this->authuser->show_button('master_data/C_bagian','R',$row_list->id_mt_bagian,67).'</li>
                            <li>'.$this->authuser->show_button('master_data/C_bagian','U',$row_list->id_mt_bagian,67).'</li>
                            <li>'.$this->authuser->show_button('master_data/C_bagian','D',$row_list->id_mt_bagian,6).'</li>
                        </ul>
                      </div></div>';
            
            $row[] = '<div class="center">'.$row_list->id_mt_bagian.'</div>';
            $row[] = $row_list->nama_bagian;
            $row[] = '<div class="center">'.$row_list->kode_bagian.'</div>';
            $row[] = '<div class="center"><span class="label label-sm '.($row_list->group_bag == 'Group' ? 'label-info' : 'label-warning').'">'.$row_list->group_bag.'</span></div>';
            $row[] = '<div class="center">'.($row_list->validasi ?? '-').'</div>';
            $row[] = '<div class="center">'.($row_list->depo_group ?? '-').'</div>';
            if((string)$row_list->is_active == 'Y'){
                $row[] = '<div class="center"><span class="label label-sm label-success">Active</span></div>'; 
            }else{
                $row[] = '<div class="center"><span class="label label-sm label-danger">Not active</span></div>';
            }
            $row[] = $this->logs->show_logs_record_datatable($row_list);
                   
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->M_bagian->count_all(),
                        "recordsFiltered" => $this->M_bagian->count_filtered(),
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
        $val->set_rules('nama_bagian', 'Nama Bagian', 'trim|required');
        $val->set_rules('group_bag', 'Group Bag', 'trim|required');
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
                'kode_bagian' => $this->regex->_genRegex( $this->input->post('kode_bagian') , 'RGXQSL'),
                'nama_bagian' => $this->regex->_genRegex( $val->set_value('nama_bagian') , 'RGXQSL'),
                'group_bag' => $this->regex->_genRegex( $this->input->post('group_bag') , 'RGXQSL'),
                'validasi' => $this->regex->_genRegex( $this->input->post('validasi') , 'RGXQSL'),
                'depo_group' => $this->regex->_genRegex( $this->input->post('depo_group') , 'RGXQSL'),
                'pelayanan' => $this->regex->_genRegex( $this->input->post('pelayanan') , 'RGXINT'),
                'status_aktif' => $this->regex->_genRegex( $this->input->post('status_aktif') , 'RGXINT'),
                'kode_poli_bpjs' => $this->regex->_genRegex( $this->input->post('kode_poli_bpjs') , 'RGXQSL'),
                'has_observe_room' => $this->regex->_genRegex( $this->input->post('has_observe_room') , 'RGXQSL'),
                'is_public' => $this->regex->_genRegex( $this->input->post('is_public') , 'RGXINT'),
                'short_name' => $this->regex->_genRegex( $this->input->post('short_name') , 'RGXQSL'),
                'id_satu_sehat' => $this->regex->_genRegex( $this->input->post('id_satu_sehat') , 'RGXQSL'),
                'location_id' => $this->regex->_genRegex( $this->input->post('location_id') , 'RGXQSL'),
                'is_active' => $this->regex->_genRegex( $this->input->post('is_active') , 'RGXQSL'),
            );

            // DEBUG: Log the data being saved
            error_log('[BAGIAN_PROCESS] ID: ' . $id . ' | GROUP: ' . $dataexc['group_bag'] . ' | KODE: ' . $dataexc['kode_bagian'] . ' | DEPO: ' . $dataexc['depo_group']);

            if($id==0){
                $dataexc['created_date'] = date('Y-m-d H:i:s');
                $dataexc['created_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                /*save post data*/
                $this->M_bagian->save($dataexc);
                $newId = $this->db->insert_id();
            }else{
                $dataexc['updated_date'] = date('Y-m-d H:i:s');
                $dataexc['updated_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                /*update record*/
                $this->M_bagian->update(array('id_mt_bagian' => $id), $dataexc);
                $newId = $id;
            }

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                $error = $this->db->error();
                $error_msg = isset($error['message']) ? $error['message'] : json_encode($error);
                error_log('[BAGIAN_DB_ERROR] ' . $error_msg . ' | Last Query: ' . $this->db->last_query());
                echo json_encode(array('status' => 301, 'message' => 'DB Error: ' . $error_msg));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan'));
            }
        }
    }

    public function get_bagian_dropdown()
    {
        $this->db->select('id_mt_bagian as id, kode_bagian, nama_bagian, group_bag');
        $this->db->from('mt_bagian');
        $this->db->where('group_bag', 'Group');
        $this->db->where('is_active', 'Y');
        $this->db->order_by('kode_bagian', 'ASC');
        $query = $this->db->get();
        
        error_log('[GET_BAGIAN_DROPDOWN] Query: ' . $this->db->last_query() . ' | Result count: ' . $query->num_rows());
        
        $result = $query->result();
        echo json_encode($result);
    }


    public function get_next_kode_bagian_by_validasi()
    {
        $selected_kode = $this->input->get('kode_bagian');
        
        // Validasi = first 4 digits of selected kode_bagian (parent)
        // e.g., if parent is 10000, validasi = 1000
        $validasi = substr($selected_kode, 0, 4);
        
        // Build a pattern: for parent kode 10000, we want detail kodes like 1000001, 1000002, etc.
        // So we search for kodes that start with the parent kode (as prefix)
        $pattern = $selected_kode . '%';
        
        // Find the MAX numeric kode_bagian that starts with selected_kode, then add 1
        $query = "SELECT MAX(CAST(kode_bagian AS INT)) as last_kode 
                  FROM mt_bagian 
                  WHERE kode_bagian LIKE ? AND is_active = 'Y'";
        $result = $this->db->query($query, array($pattern))->row();
        
        // If max exists, increment by 1; otherwise start with parent + 001
        if ($result && $result->last_kode) {
            $next_kode = $result->last_kode + 1;
        } else {
            // First detail under this parent: parent_kode + 001
            $next_kode = intval($selected_kode . '001');
        }
        
        // Pad to ensure it's a valid width (7 digits for kode_bagian)
        $next_kode_str = str_pad((string)$next_kode, 7, '0', STR_PAD_LEFT);
        
        echo json_encode(array(
            'next_kode' => $next_kode_str,
            'validasi' => $validasi
        ));
    }

    public function delete()
    {
        $id=$this->input->post('ID')?$this->input->post('ID',TRUE):null;
        $toArray = explode(',',$id);
        if($id!=null){
            if($this->M_bagian->delete_by_id($toArray)){
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
