<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Regon_riwayat extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'booking/Regon_riwayat');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('Regon_riwayat_model', 'Regon_riwayat');
        /*enable profiler*/
        $this->output->enable_profiler(false);
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'Title';

    }

    public function index() { 
        //echo '<pre>';print_r($this->session->all_userdata());die;
        /*define variable data*/
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show(),
            'riwayat' => $this->Regon_riwayat->get_riwayat_pasien()
        );
        //echo '<pre>'; print_r($data);die;
        /*load view index*/
        $this->load->view('Regon_riwayat/index', $data);
    }

    public function detail_riwayat($kode_booking) { 

        /*define variable data*/
        $data = array(
            'kode_booking' => $kode_booking,
            'booking' => $this->Regon_riwayat->get_by_kode_booking($kode_booking)
        );
        //echo '<pre>';print_r($data);die;
        /*load view index*/
        $this->load->view('Regon_riwayat/detail_riwayat_view', $data);
    }

    public function form($id='')
    {
        /*if id is not null then will show form edit*/
        if( $id != '' ){
            /*breadcrumbs for edit*/
            $this->breadcrumbs->push('Edit '.strtolower($this->title).'', 'Regon_riwayat/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
            /*get value by id*/
            $data['value'] = $this->Regon_riwayat->get_by_id($id);
            /*initialize flag for form*/
            $data['flag'] = "update";
        }else{
            /*breadcrumbs for create or add row*/
            $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Regon_riwayat/'.strtolower(get_class($this)).'/form');
            /*initialize flag for form add*/
            $data['flag'] = "create";
        }
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Regon_riwayat/form', $data);
    }
    /*function for view data only*/
    public function show($id)
    {
        /*breadcrumbs for view*/
        $this->breadcrumbs->push('View '.strtolower($this->title).'', 'Regon_riwayat/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*define data variabel*/
        $data['value'] = $this->Regon_riwayat->get_by_id($id);
        $data['title'] = $this->title;
        $data['flag'] = "read";
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Regon_riwayat/form', $data);
    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->Regon_riwayat->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center">
                        <label class="pos-rel">
                            <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->function_id.'"/>
                            <span class="lbl"></span>
                        </label>
                      </div>';
            $row[] = '<div class="center">
                        '.$this->authuser->show_button('booking/Regon_riwayat','R',$row_list->function_id,2).'
                        '.$this->authuser->show_button('booking/Regon_riwayat','U',$row_list->function_id,2).'
                        '.$this->authuser->show_button('booking/Regon_riwayat','D',$row_list->function_id,2).'
                      </div>'; 
            $row[] = '<div class="center">'.$row_list->function_id.'</div>';
            $row[] = '<div class="center">'.strtoupper($row_list->code).'</div>';
            $row[] = strtoupper($row_list->name);
            $row[] = $row_list->description;
            $row[] = ($row_list->is_active == 'Y') ? '<div class="center"><span class="label label-sm label-success">Active</span></div>' : '<div class="center"><span class="label label-sm label-danger">Not active</span></div>';
            $row[] = $this->logs->show_logs_record_datatable($row_list);

            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Regon_riwayat->count_all(),
                        "recordsFiltered" => $this->Regon_riwayat->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    

    public function delete()
    {
        $id=$this->input->post('ID')?$this->regex->_genRegex($this->input->post('ID',TRUE),'RGXQSL'):null;
        $toArray = explode(',',$id);
        if($id!=null){
            if($this->Regon_riwayat->delete_by_id($toArray)){
                $this->logs->save('tmp_mst_function', $id, 'delete record', '', 'function_id');
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
