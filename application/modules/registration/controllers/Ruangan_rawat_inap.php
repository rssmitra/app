<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ruangan_rawat_inap extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'registration/Ruangan_rawat_inap');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('Ruangan_rawat_inap_model', 'Ruangan_rawat_inap');
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
        $this->load->view('Ruangan_rawat_inap/index', $data);
    }
    
    public function get_data()
    {
        /*get data from model*/
        $list = $this->Ruangan_rawat_inap->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = strtoupper($row_list->nama_bagian);
            $row[] = $row_list->nama_klas;
            $row[] = $row_list->no_kamar;
            $row[] = $row_list->no_bed;
            $row[] = ($row_list->status==NULL)?'KOSONG':$row_list->status;

            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Ruangan_rawat_inap->count_all(),
                        "recordsFiltered" => $this->Ruangan_rawat_inap->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }


}

/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
