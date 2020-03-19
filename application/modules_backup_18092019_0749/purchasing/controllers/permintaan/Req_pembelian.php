<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Req_pembelian extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'purchasing/permintaan/Req_pembelian');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('purchasing/permintaan/Req_pembelian_model', 'Req_pembelian');
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
            'breadcrumbs' => $this->breadcrumbs->show(),
            'flag' => $_GET['flag']
        );
        /*load view index*/
        $this->load->view('permintaan/Req_pembelian/index', $data);
    }

    public function form($id='')
    {
        $data['string'] = isset($_GET['flag'])?$_GET['flag']:'';
        /*if id is not null then will show form edit*/
        if( $id != '' ){
            /*breadcrumbs for edit*/
            $this->breadcrumbs->push('Edit '.strtolower($this->title).'', 'Req_pembelian/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
            /*get value by id*/
            $data['value'] = $this->Req_pembelian->get_by_id($id); //echo '<pre>'; print_r($data);die;
            /*initialize flag for form*/
            $data['flag'] = "update";
        }else{
            /*breadcrumbs for create or add row*/
            $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Req_pembelian/'.strtolower(get_class($this)).'/form');
            /*initialize flag for form add*/
            $data['flag'] = "create";
        }
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('permintaan/Req_pembelian/form', $data);
    }
    /*function for view data only*/
    public function show($id)
    {
        $data['string'] = isset($_GET['flag'])?$_GET['flag']:'';
        /*breadcrumbs for view*/
        $this->breadcrumbs->push('View '.strtolower($this->title).'', 'Req_pembelian/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*define data variabel*/
        //$data['value'] = $this->Req_pembelian->get_by_id($id);
        $data['title'] = $this->title;
        $data['flag'] = "read";
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('permintaan/Req_pembelian/form', $data);
    }


    public function get_data()
    {
        /*get data from model*/
        $list = $this->Req_pembelian->get_datatables();
        
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center">
                        <label class="pos-rel">
                            <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->id_tc_permohonan.'"/>
                            <span class="lbl"></span>
                        </label>
                      </div>';
            $row[] = '';
            $row[] = $row_list->id_tc_permohonan;
            $row[] = '<div class="center"><div class="btn-group">
                        <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">
                            <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-inverse">
                            <li>'.$this->authuser->show_button('purchasing/permintaan/Req_pembelian?flag='.$_GET['flag'].'','R',$row_list->id_tc_permohonan,67).'</li>
                            <li>'.$this->authuser->show_button('purchasing/permintaan/Req_pembelian?flag='.$_GET['flag'].'','U',$row_list->id_tc_permohonan,67).'</li>
                            <li>'.$this->authuser->show_button('purchasing/permintaan/Req_pembelian?flag='.$_GET['flag'].'','D',$row_list->id_tc_permohonan,6).'</li>
                        </ul>
                    </div></div>';
            $row[] = '<div class="center">'.$row_list->id_tc_permohonan.'</div>';
            $row[] = $row_list->kode_permohonan;
            $row[] = $this->tanggal->formatDate($row_list->tgl_permohonan);
            $row[] = '<div class="center">'.ucwords($row_list->username).'</div>';
            $row[] = '<div class="left">'.$row_list->no_acc.'</div>';
            $row[] = $this->tanggal->formatDate($row_list->tgl_acc);

            if ($row_list->status_batal=="0") {
                $status = '<div class="center"><i class="fa fa-check-circle bigger-150 green"></i></div>';
                $text = 'Disetujui';
            } elseif ($row_list->status_batal=="1") {
                $status = '<div class="center"><i class="fa fa-times-circle bigger-150 red"></i></div>';
                $text = 'Tidak disetujui';
            } else {
                $status = '<div class="center"><i class="fa fa-exclamation-triangle bigger-150 orange"></i></div>';
                $text = 'Menunggu Persetujuan';
            }

            $row[] = '<div class="center">'.$status.'</div>';
            $row[] = $text.' '.ucfirst($row_list->user_acc_name);
                   
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Req_pembelian->count_all(),
                        "recordsFiltered" => $this->Req_pembelian->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function get_detail($flag, $id){
        $result = $this->Req_pembelian->get_detail_brg_permintaan($flag, $id);
        $data = array(
            'dt_detail_brg' => $result,
            'flag' => $flag,
            );
        $temp_view = $this->load->view('permintaan/Req_pembelian/detail_table_view', $data, true);
        echo json_encode( array('html' => $temp_view) );
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
                $newId = $this->Req_pembelian->save($dataexc);
                /*save logs*/
                $this->logs->save('tc_stok_opname_agenda', $newId, 'insert new record on '.$this->title.' module', json_encode($dataexc),'id_tc_permohonan');
            }else{
                $dataexc['updated_date'] = date('Y-m-d H:i:s');
                $dataexc['updated_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                /*print_r($dataexc);die;*/
                /*update record*/
                $this->Req_pembelian->update(array('id_tc_permohonan' => $id), $dataexc);
                $newId = $id;
                $this->logs->save('tc_stok_opname_agenda', $newId, 'update record'.$this->title.' module', json_encode($dataexc), 'id_tc_permohonan');
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
            if($this->Req_pembelian->delete_by_id($toArray)){
                $this->logs->save('tc_stok_opname_agenda', $id, 'delete record', '', 'id_tc_permohonan');
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
