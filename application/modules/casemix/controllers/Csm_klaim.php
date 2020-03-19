<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Csm_klaim extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'casemix/Csm_klaim');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            redirect(base_url().'login');exit;
        }
        /*load model*/
        $this->load->model('Csm_klaim_model', 'Csm_klaim');
        /*enable profiler*/
        $this->output->enable_profiler(false);

    }

    public function index() { 
        //echo '<pre>';print_r($this->session->all_userdata());
        /*define variable data*/
        $data = array(
            'title' => 'Klaim',
            'breadcrumbs' => $this->breadcrumbs->show()
        );
        /*load view index*/
        $this->load->view('Csm_klaim/index', $data);
    }

    public function form($id='')
    {
        /*if id is not null then will show form edit*/
        if( $id != '' ){
            /*breadcrumbs for edit*/
            $this->breadcrumbs->push('Edit function', 'Csm_klaim/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
            /*get value by id*/
            $data['value'] = $this->Csm_klaim->get_by_id($id);
            /*initialize flag for form*/
            $data['flag'] = "update";
        }else{
            /*breadcrumbs for create or add row*/
            $this->breadcrumbs->push('Add function', 'Csm_klaim/'.strtolower(get_class($this)).'/form');
            /*initialize flag for form add*/
            $data['flag'] = "create";
        }
        /*title header*/
        $data['title'] = 'Klaim';
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Csm_klaim/form', $data);
    }
    /*function for view data only*/
    public function show($id)
    {
        /*breadcrumbs for view*/
        $this->breadcrumbs->push('View function', 'Csm_klaim/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*define data variabel*/
        $data['value'] = $this->Csm_klaim->get_by_id($id);
        $data['title'] = 'Klaim';
        $data['flag'] = "read";
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Csm_klaim/form', $data);
    }


    public function get_data()
    {
        /*get data from model*/
        $list = $this->Csm_klaim->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center">
                        <label class="pos-rel">
                            <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->csm_klaim_id.'"/>
                            <span class="lbl"></span>
                        </label>
                      </div>'; 
            $row[] = '<div class="center"><a href="#" onclick="getMenu('."'".'casemix/Csm_klaim/form/'.$row_list->csm_klaim_id.''."'".')">'.$row_list->csm_klaim_kode.'</a></div>';
            $row[] = $this->tanggal->getBulan($row_list->csm_klaim_bulan).'&nbsp;'.$row_list->csm_klaim_tahun;
            $row[] = $this->tanggal->formatDate($row_list->csm_klaim_dari_tgl).' s/d '.$this->tanggal->formatDate($row_list->csm_klaim_sampai_tgl);
            $row[] = $row_list->created_by;
            $row[] = $this->tanggal->formatDateTime($row_list->created_date);
            $row[] = '<div class="center">'.number_format($row_list->csm_klaim_total_dokumen).'</div>';
            $row[] = '<div class="center">'.number_format($row_list->csm_klaim_total_rj).'</div>';
            $row[] = '<div class="center">'.number_format($row_list->csm_klaim_total_ri).'</div>';
            $row[] = '<div align="right">'.number_format($row_list->csm_klaim_total_rp).'</div>';
            $row[] = '<div class="center"><a href="">Download</a></div>';
            $row[] = '<div class="center"><a href="">Download</a></div>';
            $row[] = '<div class="center"><a href="">Download</a></div>';
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Csm_klaim->count_all(),
                        "recordsFiltered" => $this->Csm_klaim->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function process()
    {
       
        $this->load->library('form_validation');
        $val = $this->form_validation;
        $val->set_rules('csm_klaim_bulan', 'Periode Bulan', 'trim|required');
        $val->set_rules('csm_klaim_tahun', 'Periode Tahun', 'trim|required');
        $val->set_rules('csm_klaim_dari_tgl', 'Dari Tanggal', 'trim|required');
        $val->set_rules('csm_klaim_sampai_tgl', 'Sampai Tanggal', 'trim|required');
        $val->set_rules('csm_klaim_total_ri', 'Total Rawat Inap', 'trim|required');
        $val->set_rules('csm_klaim_total_rj', 'Total Rawat Jalan', 'trim|required');
        $val->set_rules('csm_klaim_total_rp_hidden', 'Total Klaim', 'trim|required');
        $val->set_rules('csm_klaim_total_dokumen', 'Total Dokumen', 'trim|required');

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

            /*csm_klaim_kode*/
            $csm_klaim_kode = 'CSM'.$val->set_value('csm_klaim_bulan').$val->set_value('csm_klaim_tahun').rand(9,99);
            $dataexc = array(
                'csm_klaim_kode' => $csm_klaim_kode,
                'csm_klaim_bulan' => $this->regex->_genRegex($val->set_value('csm_klaim_bulan'), 'RGXQSL'),
                'csm_klaim_tahun' => $this->regex->_genRegex($val->set_value('csm_klaim_tahun'), 'RGXINT'),
                'csm_klaim_dari_tgl' => $this->regex->_genRegex($val->set_value('csm_klaim_dari_tgl'), 'RGXQSL'),
                'csm_klaim_sampai_tgl' => $this->regex->_genRegex($val->set_value('csm_klaim_sampai_tgl'), 'RGXQSL'),
                'csm_klaim_total_ri' => $this->regex->_genRegex($val->set_value('csm_klaim_total_ri'), 'RGXINT'),
                'csm_klaim_total_rj' => $this->regex->_genRegex($val->set_value('csm_klaim_total_rj'), 'RGXINT'),
                'csm_klaim_total_dokumen' => $this->regex->_genRegex($val->set_value('csm_klaim_total_dokumen'), 'RGXINT'),
                'csm_klaim_total_rp' => $this->regex->_genRegex($val->set_value('csm_klaim_total_rp_hidden'), 'RGXINT'),
            );
            //print_r($dataexc);die;
            if($id==0){
                $dataexc['created_date'] = date('Y-m-d H:i:s');
                $dataexc['created_by'] = $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL');
                $this->db->insert('csm_klaim', $dataexc);
                $newId = $this->db->insert_id();
                $this->logs->save('csm_klaim', $newId, 'insert new record', json_encode($dataexc));
            }else{
                $dataexc['updated_date'] = date('Y-m-d H:i:s');
                $dataexc['updated_by'] = $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL');
                $this->db->update('csm_klaim', $dataexc, array('csm_klaim_id' => $id));
                $newId = $id;
                $this->logs->save('csm_klaim', $newId, 'update record', json_encode($dataexc));
            }
            $this->Csm_klaim->update_dokumen_klaim($dataexc, $id);

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
            if($this->Csm_klaim->delete_by_id($toArray)){
                $this->logs->save('csm_klaim', $id, 'delete record', '');
                echo json_encode(array('status' => 200, 'message' => 'Proses Hapus Data Berhasil Dilakukan'));

            }else{
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Hapus Data Gagal Dilakukan'));
            }
        }else{
            echo json_encode(array('status' => 301, 'message' => 'Tidak ada item yang dipilih'));
        }
        
    }

    public function find_klaim_by_waktu_input()
    {
        //print_r($_POST);die;
        /*find it*/
        $find_data = $this->Csm_klaim->find_klaim_by_waktu_input($_POST);
        echo json_encode($find_data);
        
    }

    


}


/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
