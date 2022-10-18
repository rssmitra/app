<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Eks_kinerja_dokter extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'eksekutif/Eks_kinerja_dokter');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('eksekutif/Eks_kinerja_dokter_model', 'Eks_kinerja_dokter');
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
        );
        /*load view index*/
        $this->load->view('Eks_kinerja_dokter/index', $data);
    }


    public function get_content_page(){

        $output = http_build_query($_GET) . "\n";
        $data[0] = array(
            'nameid' => 'tbl-kinerja-dokter',
            'style' => 'table',
            'col_size' => 12,
            'url' => 'eksekutif/Eks_kinerja_dokter/data?prefix=1&TypeChart=table&style=TableKinerjaDokter&'.$output.'',
        );
        
        echo json_encode($data);
    }

    public function data(){
        echo json_encode($this->Eks_kinerja_dokter->get_content_data($_GET), JSON_NUMERIC_CHECK);
    }

    public function show_detail(){
        $data = array();
        $data['value'] = $this->Eks_kinerja_dokter->get_detail_data();
        // echo '<pre>';print_r($data);die;
        $this->load->view('Eks_kinerja_dokter/ViewDetailData', $data);
    }

    public function show_detail_unit(){
        $data = array();
        $data['value'] = $this->Eks_kinerja_dokter->get_detail_data_unit();
        // echo '<pre>';print_r($data);die;
        $this->load->view('Eks_kinerja_dokter/ViewDetailDataUnit', $data);
    }

    public function show_detail_pasien(){
        $data = array();
        $data['value'] = $this->Eks_kinerja_dokter->get_detail_data_pasien();
        // echo '<pre>';print_r($data);die;
        $this->load->view('Eks_kinerja_dokter/ViewDetailDataPasien', $data);
    }


}


/* End of file example.php */
/* Location: ./application/modules/example/controllers/example.php */
