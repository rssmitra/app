<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Eks_piutang extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'eksekutif/Eks_piutang');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('eksekutif/Eks_piutang_model', 'Eks_piutang');
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
        $this->load->view('Eks_piutang/index', $data);
    }


    public function get_content_page(){

        $data[0] = array(
            'nameid' => 'tbl-resume-kunjungan',
            'style' => 'table',
            'col_size' => 12,
            'url' => 'eksekutif/Eks_piutang/data?prefix=1&TypeChart=table&style=TableResumePiutang&from_tgl='.$_GET['from_tgl'].'&to_tgl='.$_GET['to_tgl'].'',
            );

        $data[1] = array(
            'nameid' => 'graph-line-1',
            'style' => 'line',
            'col_size' => 12,
            'url' => 'eksekutif/Eks_piutang/data?prefix=2&TypeChart=line&style=1&from_tgl='.$_GET['from_tgl'].'&to_tgl='.$_GET['to_tgl'].'',
        );

        echo json_encode($data);
    }

    public function data(){
        echo json_encode($this->Eks_piutang->get_content_data($_GET), JSON_NUMERIC_CHECK);
    }

    public function show_detail(){
        $data = array();
        $data['value'] = $this->Eks_piutang->get_detail_data();
        // echo '<pre>';print_r($data);die;
        $this->load->view('Eks_piutang/ViewDetailData', $data);
    }


}


/* End of file example.php */
/* Location: ./application/modules/example/controllers/example.php */
