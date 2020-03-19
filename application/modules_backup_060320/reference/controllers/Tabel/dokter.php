<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dokter extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'reference/tabel/dokter');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('tabel/Dokter_model', 'dokter');
        /*enable profiler*/
        $this->output->enable_profiler(false);
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'dokter';

    }

    public function index() {
        /*define variable data*/
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show()
        );
        /*load view index*/
        $this->load->view('tabel/dokter/index', $data);
    }
    
    

    public function form($id='')
    {
        /*if id is not null then will show form edit*/
        if( $id != '' ){
            /*breadcrumbs for edit*/
            $this->breadcrumbs->push('Edit '.$this->title.'', 'reference/tabel/dokter/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
            /*get value by id*/
            $data['value'] = $this->dokter->get_by_id($id);
            // print_r($data); die;
            /*initialize flag for form*/
            $data['flag'] = "update";
        }else{
            /*breadcrumbs for create or add row*/
            $this->breadcrumbs->push('Add '.$this->title.'', 'reference/tabel/dokter/'.strtolower(get_class($this)).'/form');
            /*initialize flag for form add*/
            $data['flag'] = "create";
        }
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('tabel/dokter/form', $data);
    }
    /*function for view data only*/
    public function show($id)
    {
        /*breadcrumbs for view*/
        $this->breadcrumbs->push('View '.$this->title.'', 'reference/tabel/dokter/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*define data variabel*/
        $data['value'] = $this->dokter->get_by_id($id);
        $data['title'] = $this->title;
        $data['flag'] = "read";
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('tabel/dokter/form', $data);
    }


    public function get_data()
    {
        /*get data from model*/
        $list = $this->dokter->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
           
            $no++;
            $row = array();
            $ket = ($row_list->status==0)?'Aktif': 'Tidak Aktif';
            $row[] = '<div class="center"><label class="pos-rel">
                        <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->no_induk.'"/>
                        <span class="lbl"></span>
                    </label></div>';
                    $row[] = '<div class="center"><div class="hidden-sm hidden-xs action-buttons">
                        '.$this->authuser->show_button('reference/tabel/dokter','R',$row_list->no_induk,2).'
                        '.$this->authuser->show_button('reference/tabel/dokter','U',$row_list->no_induk,2).'
                        '.$this->authuser->show_button('reference/tabel/dokter','D',$row_list->no_induk,2).'
                      </div>
                      <div class="hidden-md hidden-lg">
                        <div class="inline pos-rel">
                            <button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto"><i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
                            </button>
                            <ul class="dropdown-dokter dropdown-only-icon dropdown-yellow dropdown-dokter-right dropdown-caret dropdown-close">
                                <li>'.$this->authuser->show_button('reference/tabel/dokter','R','',4).'</li>
                                <li>'.$this->authuser->show_button('reference/tabel/dokter','U','',4).'</li>
                                <li>'.$this->authuser->show_button('reference/tabel/dokter','D','',4).'</li>
                            </ul>
                        </div>
                    </div></div>'; 
            $row[] = $row_list->no_mr;
            $row[] = $row_list->no_induk;
            $row[] = strtoupper($row_list->nama_pegawai);
            $row[] = $row_list->nama_spesialisasi;
            $row[] = $this->dokter->get_bagian($row_list->kode_dokter);
            //$row[] = $row_list->nama_bagian;
            $row[] = $row_list->status_dr;
            $row[] = $row_list->status;
             
                   
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->dokter->count_all(),
                        "recordsFiltered" => $this->dokter->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function process()
    {
        $this->load->library('form_validation');
        $val = $this->form_validation;
        $val->set_rules('no_induk', 'No Induk', 'trim|required');
        $val->set_rules('nama_pegawai', 'Nama Pegawai', 'trim|required');

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
            //printf($id);
            //break;
            $dataexc = array(
                'no_induk' => $val->set_value('no_induk'),
                'nama_pegawai' => $val->set_value('nama_pegawai'),
                'kode_bagian' => $this->input->post('kodebagian'),
                'kode_Jabatan' => $this->input->post('kodejabatan'),
                'no_mr' => $this->input->post('no_mr'),
                'status' => $this->input->post('status'),
            );
            if($id==0){
               $this->dokter->save('mt_dokter', $dataexc);
                $newId = $this->db->insert_id();
               
            }else{
                 /*update record*/
                $this->dokter->update('mt_dokter', array('no_induk' => $id), $dataexc);
                
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
            if($this->dokter->delete_by_id($toArray)){
                //$this->logs->save('dokter', $id, 'delete record', '', 'id_mt_dokter');
                echo json_encode(array('status' => 200, 'message' => 'Proses Hapus Data Berhasil Dilakukan'));
            }else{
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Hapus Data Gagal Dilakukan'));
            }
        }else{
            echo json_encode(array('status' => 301, 'message' => 'Tidak ada item yang dipilih'));
        }
        
    }


}


/* End of file dokter.php */
/* Location: ./application/modules/dokter/controllers/dokter.php */
