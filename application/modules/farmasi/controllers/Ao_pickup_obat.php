<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ao_pickup_obat extends MX_Controller {

    /*function constructor*/
    function __construct() 
    {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'farmasi/Ao_pickup_obat');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('Ao_pickup_obat_model', 'Ao_pickup_obat');
        /*enable profiler*/
        $this->output->enable_profiler(false);
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'Title';

    }

    public function index() 
    { 
        /*define variable data*/
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show(),
        );
        /*load view index*/
        $this->load->view('Ao_pickup_obat/index', $data);
    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->Ao_pickup_obat->get_datatables();
        if(isset($_GET['search']) AND $_GET['search']==TRUE){
            $this->find_data(); exit;
        }
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $status = ($row_list->received_time == NULL) ? '<a href="#" class="label label-xs label-danger" onclick="delete_data('.$row_list->pickup_id.')"><i class="fa fa-times-circle"></i></a>' : '<i class="fa fa-check-circle bigger-120 green"></i>';
            $row[] = '<div class="center">'.$status.'</div>';
            $row[] = '<div class="center">'.$no.'</div>';
            $row[] = '<div class="center">'.$row_list->kode_trans_far.'</div>';
            $row[] = '<b>'.$row_list->no_mr.' - '.strtoupper($row_list->nama_pasien).'</b>';
            $row[] = $this->tanggal->formatDateTimeFormDmy($row_list->pickup_time);
            $row[] = $row_list->pickup_by;
            $row[] = '<div class="center">'.$row_list->jenis_resep.'</div>';
            
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Ao_pickup_obat->count_all(),
                        "recordsFiltered" => $this->Ao_pickup_obat->count_filtered(),
                        "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function find_data()
    {   
        $output = array( "data" => http_build_query($_POST) . "\n" );
        echo json_encode($output);
    }

    public function process()
    {
        // print_r($_POST);die;
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('kode', 'Kode Transaksi', 'trim|required');
        $this->form_validation->set_rules('jenis', 'Jenis Resep', 'trim|required');
        
        // set message error
        $this->form_validation->set_message('required', "Silahkan isi field \"%s\"");        

        if ($this->form_validation->run() == FALSE)
        {
            $this->form_validation->set_error_delimiters('<div style="color:white"><i>', '</i></div>');
            //die(validation_errors());
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            /*execution*/
            $this->db->trans_begin();
            $dt = $this->db->get_where('fr_tc_far', array('kode_trans_far' => $_POST['kode']) )->row();

            $dtpickup = array(
                'kode_trans_far' => isset($_POST['kode'])?$this->regex->_genRegex($_POST['kode'], 'RGXQSL'):0,
                'jenis_resep' => isset($_POST['jenis'])?$this->regex->_genRegex($_POST['jenis'], 'RGXQSL'):0,
                'no_mr' => isset($dt->no_mr)?$this->regex->_genRegex($dt->no_mr, 'RGXQSL'):0,
                'nama_pasien' => isset($dt->nama_pasien)?$this->regex->_genRegex($dt->nama_pasien, 'RGXQSL'):0,
                'pickup_time' => date('Y-m-d H:i:s'),
                'pickup_by' => $this->session->userdata('user')->fullname,
            );

            $cek_existing = $this->db->get_where('fr_pickup_obat', array('kode_trans_far' => $_POST['kode'], 'jenis_resep' => $_POST['jenis']) )->row();

            // echo '<pre>';print_r($dtpickup);die;

            if( !empty($cek_existing) ){
                $dtpickup['updated_date'] = date('Y-m-d H:i:s');
                $dtpickup['updated_by'] = $this->session->userdata('user')->fullname;
                $this->db->update('fr_pickup_obat', $dtpickup, array('kode_trans_far' => $_POST['kode']) );
            
            }else{
                $dtpickup['created_date'] = date('Y-m-d H:i:s');
                $dtpickup['created_by'] = $this->session->userdata('user')->fullname;
                $this->db->insert( 'fr_pickup_obat', $dtpickup );
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
            if($this->Ao_pickup_obat->delete_by_id($toArray)){
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
