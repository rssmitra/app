<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Kepeg_riwayat_pekerjaan extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'kepegawaian/Kepeg_riwayat_pekerjaan');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('Kepeg_riwayat_pekerjaan_model', 'Kepeg_riwayat_pekerjaan');
        /*enable profiler*/
        $this->output->enable_profiler(false);
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'Title';
    }

    public function get_data_by_id($id)
    {   
        $data = $this->Kepeg_riwayat_pekerjaan->get_by_id($id);   
        echo json_encode($data);
    }

    // get data tables
    public function get_data()
    {
        /*get data from model*/
        $list = $this->Kepeg_riwayat_pekerjaan->get_datatables();
        // print_r($list);die;
        
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $row_list->kepeg_rpj_nama_perusahaan;
            $row[] = $row_list->kepeg_rpj_jabatan;
            $row[] = $row_list->kepeg_rpj_dari_tahun.' s.d '.$row_list->kepeg_rpj_sd_tahun;
            $row[] = $row_list->kepeg_rpj_deskripsi_pekerjaan;
            $row[] = '<div class="center">
            <a href="#" onclick="update_row_pekerjaan('.$row_list->kepeg_rpj_id.')"><span class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></span></a>
            <a href="#" onclick="delete_row('.$row_list->kepeg_rpj_id.')"><span class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></span></a>
            </div>';
                   
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Kepeg_riwayat_pekerjaan->count_all(),
                        "recordsFiltered" => $this->Kepeg_riwayat_pekerjaan->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function process()
    {
        // echo '<pre>';print_r($_FILES);die;
        $this->load->library('form_validation');
        $val = $this->form_validation;

        // Validasi Form Data Pribadi
        $val->set_rules('kepeg_id','ID Pegawai', 'trim|required'); 
        $val->set_rules('kepeg_rpj_nama_perusahaan','Nama Perusahaan', 'trim|required'); 
        $val->set_rules('kepeg_rpj_jabatan','Jabatan', 'trim|required'); 
        $val->set_rules('kepeg_rpj_deskripsi_pekerjaan','Deskripsi Pekerjaan', 'trim'); 
        $val->set_rules('kepeg_rpj_dari_tahun','Dari Tahun', 'trim|required'); 
        $val->set_rules('kepeg_rpj_sd_tahun','S.d Tahun', 'trim|required'); 

        $val->set_message('required', "Silahkan isi field \"%s\"");

        if ($val->run() == FALSE)
        {
            $val->set_error_delimiters('<div style="color:white">', '</div>');
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            $this->db->trans_begin();
            
            $id = ($this->input->post('kepeg_rpj_id'))?$this->regex->_genRegex($this->input->post('kepeg_rpj_id'),'RGXINT'):0;

            $dataexc = array(
                'kepeg_id' => $this->regex->_genRegex($val->set_value('kepeg_id'), 'RGXQSL'),
                'kepeg_rpj_nama_perusahaan' => $this->regex->_genRegex($val->set_value('kepeg_rpj_nama_perusahaan'), 'RGXQSL'),
                'kepeg_rpj_jabatan' => $this->regex->_genRegex($val->set_value('kepeg_rpj_jabatan'), 'RGXQSL'),
                'kepeg_rpj_deskripsi_pekerjaan' => $this->regex->_genRegex($val->set_value('kepeg_rpj_deskripsi_pekerjaan'), 'RGXQSL'),
                'kepeg_rpj_dari_tahun' => $this->regex->_genRegex($val->set_value('kepeg_rpj_dari_tahun'), 'RGXQSL'),
                'kepeg_rpj_sd_tahun' => $this->regex->_genRegex($val->set_value('kepeg_rpj_sd_tahun'), 'RGXQSL'),
            );

            
            if($id==0){
                $dataexc['created_date'] = date('Y-m-d H:i:s');
                $dataexc['created_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                // save data riwayat pekerjaan
                $newId = $this->Kepeg_riwayat_pekerjaan->save('kepeg_riwayat_pekerjaan', $dataexc);
            }else{
                $dataexc['updated_date'] = date('Y-m-d H:i:s');
                $dataexc['updated_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                /*update record*/
                $this->Kepeg_riwayat_pekerjaan->update('kepeg_riwayat_pekerjaan', array('kepeg_rpj_id' => $id), $dataexc);
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
        if($id!=null){
            if($this->Kepeg_riwayat_pekerjaan->delete_by_id($id)){
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
