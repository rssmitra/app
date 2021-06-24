<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Kepeg_riwayat_pendidikan extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'kepegawaian/Kepeg_riwayat_pendidikan');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('Kepeg_riwayat_pendidikan_model', 'Kepeg_riwayat_pendidikan');
        /*enable profiler*/
        $this->output->enable_profiler(false);
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'Title';

    }

    public function get_data_by_id( $id )
    {   
        $data = $this->Kepeg_riwayat_pendidikan->get_by_id($id);   
        echo json_encode($data);
    }

    // get data tables riwayat pendidikan
    public function get_data()
    {
        /*get data from model*/
        $list = $this->Kepeg_riwayat_pendidikan->get_datatables();
        
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = $row_list->kepeg_rpd_nama_sekolah;
            $row[] = $row_list->kepeg_rpd_kota;
            $row[] = $row_list->kepeg_rpd_jenjang_pendidikan;
            $row[] = $row_list->kepeg_rpd_nilai_akhir;
            $row[] = $row_list->kepeg_rpd_tahun_lulus;
            $row[] = '<div class="center">
            <a href="#" onclick="update_row_pendidikan('.$row_list->kepeg_rpd_id.')"><span class="btn btn-xs btn-success"><i class="fa fa-pencil"></i></span></a>
            <a href="#" onclick="delete_row_pendidikan('.$row_list->kepeg_rpd_id.')"><span class="btn btn-xs btn-danger"><i class="fa fa-trash"></i></span></a>
            </div>';
                   
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Kepeg_riwayat_pendidikan->count_all(),
                        "recordsFiltered" => $this->Kepeg_riwayat_pendidikan->count_filtered(),
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
        $val->set_rules('kepeg_id_frm_rpd','ID Pegawai', 'trim|required'); 
        $val->set_rules('kepeg_rpd_nama_sekolah','Nama Sekolah', 'trim|required'); 
        $val->set_rules('kepeg_rpd_kota','Kota Asal Sekolah', 'trim|required'); 
        $val->set_rules('kepeg_rpd_jenjang_pendidikan','Jenjang Pendidikan', 'trim|required'); 
        $val->set_rules('kepeg_rpd_nilai_akhir','Nilai Akhir', 'trim|required'); 
        $val->set_rules('kepeg_rpd_tahun_lulus','Tahun Lulus', 'trim|required'); 

        $val->set_message('required', "Silahkan isi field \"%s\"");

        if ($val->run() == FALSE)
        {
            $val->set_error_delimiters('<div style="color:white">', '</div>');
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            $this->db->trans_begin();
            
            $id = ($this->input->post('kepeg_rpd_id'))?$this->regex->_genRegex($this->input->post('kepeg_rpd_id'),'RGXINT'):0;

            $dataexc = array(
                'kepeg_id' => $this->regex->_genRegex($val->set_value('kepeg_id_frm_rpd'), 'RGXQSL'),
                'kepeg_rpd_nama_sekolah' => $this->regex->_genRegex($val->set_value('kepeg_rpd_nama_sekolah'), 'RGXQSL'),
                'kepeg_rpd_kota' => $this->regex->_genRegex($val->set_value('kepeg_rpd_kota'), 'RGXQSL'),
                'kepeg_rpd_jenjang_pendidikan' => $this->regex->_genRegex($val->set_value('kepeg_rpd_jenjang_pendidikan'), 'RGXQSL'),
                'kepeg_rpd_nilai_akhir' => $this->regex->_genRegex($val->set_value('kepeg_rpd_nilai_akhir'), 'RGXQSL'),
                'kepeg_rpd_tahun_lulus' => $this->regex->_genRegex($val->set_value('kepeg_rpd_tahun_lulus'), 'RGXQSL'),
            );

            
            if($id==0){
                $dataexc['created_date'] = date('Y-m-d H:i:s');
                $dataexc['created_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                // save data riwayat pekerjaan
                $newId = $this->Kepeg_riwayat_pendidikan->save('Kepeg_riwayat_pendidikan', $dataexc);
            }else{
                $dataexc['updated_date'] = date('Y-m-d H:i:s');
                $dataexc['updated_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                /*update record*/
                $this->Kepeg_riwayat_pendidikan->update('Kepeg_riwayat_pendidikan', array('kepeg_rpd_id' => $id), $dataexc);
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
            if($this->Kepeg_riwayat_pendidikan->delete_by_id($id)){
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
