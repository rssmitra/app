<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Csm_upload_hasil_verif extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'casemix/Csm_upload_hasil_verif');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            redirect(base_url().'login');exit;
        }
        /*load model*/
        $this->load->model('Csm_upload_hasil_verif_model', 'Csm_upload_hasil_verif');
        $this->load->model('Csm_billing_pasien_model', 'Csm_billing_pasien');
        // load module
        $this->load->module('wizard/Import_hasil_verif_bpjs');
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
        $this->load->view('Csm_upload_hasil_verif/index', $data);
    }

    public function form($id='')
    {
        /*if id is not null then will show form edit*/
        if( $id != '' ){
            /*breadcrumbs for edit*/
            $this->breadcrumbs->push('Edit function', 'Csm_upload_hasil_verif/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
            /*get value by id*/
            $data['value'] = $this->Csm_upload_hasil_verif->get_by_id($id);
            /*initialize flag for form*/
            $data['flag'] = "update";
        }else{
            /*breadcrumbs for create or add row*/
            $this->breadcrumbs->push('Add function', 'Csm_upload_hasil_verif/'.strtolower(get_class($this)).'/form');
            /*initialize flag for form add*/
            $data['flag'] = "create";
        }
        // attachment
        $data['attachment'] = $this->upload_file->CsmgetUploadedFile($id);
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Csm_upload_hasil_verif/form', $data);
    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->Csm_upload_hasil_verif->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center">'.$row_list->csm_uhv_id.'</div>';
            $row[] = '<div class="left"><a href="#">'.$this->tanggal->getBulan($row_list->csm_uhv_month_periode).'</a></div>';
            $row[] = $row_list->csm_uhv_year;
            $row[] = $row_list->csm_uhv_flag;
            $row[] = $row_list->csm_uhv_total_row;
            $row[] = $row_list->csm_uhv_file;
            $row[] = $this->logs->show_logs_record_datatable($row_list);
            $data[] = $row;
        }
        $output = array(
                    "draw" => $_POST['draw'],
                    "recordsTotal" => $this->Csm_upload_hasil_verif->count_all(),
                    "recordsFiltered" => $this->Csm_upload_hasil_verif->count_filtered(),
                    "data" => $data,
        );
    
        
        //output to json format
        echo json_encode($output);
    }

    public function process()
    {
        // echo '<pre>';print_r($_POST);die;

        $this->load->library('form_validation');
        $val = $this->form_validation;
        $val->set_rules('csm_uhv_month_periode', 'Bulan', 'trim|required');
        $val->set_rules('csm_uhv_year', 'Tahun', 'trim|required');
        $val->set_message('required', "Silahkan isi field \"%s\"");

        if ($val->run() == FALSE)
        {
            $val->set_error_delimiters('<div style="color:white">', '</div>');
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            $this->db->trans_begin();

            $id = isset($_POST['id'])?$_POST['id']:0;
            /*csm_upload_hasil_verif*/
            $dataexc = array(
                'csm_uhv_month_periode' => $this->regex->_genRegex($val->set_value('csm_uhv_month_periode'), 'RGXQSL'),
                'csm_uhv_year' => $this->regex->_genRegex($val->set_value('csm_uhv_year'), 'RGXQSL'),
            );

            if(isset($_FILES['csm_uhv_file']['name'])){
                /*hapus dulu file yang lama*/
                if( $id != 0 ){
                    $dt = $this->Csm_upload_hasil_verif->get_by_id($id);
                    if ($dt->csm_uhv_file != NULL) {
                        unlink(PATH_HASIL_VERIF_BPJS.$dt->csm_uhv_file.'');
                    }
                }

                $dataexc['csm_uhv_file'] = $this->upload_file->doUpload('csm_uhv_file', PATH_HASIL_VERIF_BPJS);
            }

            if($id==0){
                $dataexc['created_date'] = date('Y-m-d H:i:s');
                $dataexc['created_by'] = $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL');
                $exc_qry = $this->db->insert('csm_upload_hasil_verif', $dataexc);
                $newId = $this->db->insert_id();
                $this->logs->save('csm_upload_hasil_verif', $newId, 'insert new record', json_encode($dataexc), 'csm_uhv_id');
            }else{
                $dataexc['updated_date'] = date('Y-m-d H:i:s');
                $dataexc['updated_by'] = $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL');
                $exc_qry = $this->db->update('csm_upload_hasil_verif', $dataexc, array('csm_uhv_id' => $id));
                $newId = $id;
                $this->logs->save('csm_upload_hasil_verif', $newId, 'update record', json_encode($dataexc), 'csm_uhv_id');
            }

            // import file hasil verif
            // delete data sebelumnya
            $this->db->delete('csm_upload_hasil_verif_detail', array('csm_uhv_id' => $newId) );
            // import 
            $importFile = new Import_hasil_verif_bpjs;
            $rowResult = $importFile->import($dataexc['csm_uhv_file'], $newId);
            // update header
            $this->db->update('csm_upload_hasil_verif', array('csm_uhv_total_row' => $rowResult['totalData'], 'csm_uhv_keterangan' => $rowResult['keterangan'] ), array('csm_uhv_id' => $newId) );

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

    public function find_data()
    {   
        $output = array(
                        "recordsTotal" => $this->Csm_upload_hasil_verif->count_all(),
                        /*"recordsFiltered" => $this->Csm_upload_hasil_verif->count_filtered(),*/
                        "data" => $_POST,
                );
        echo json_encode($output);
    }

}


/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
