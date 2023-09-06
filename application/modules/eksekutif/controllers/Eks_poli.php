<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Eks_poli extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'eksekutif/Eks_poli');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('eksekutif/Eks_poli_model', 'Eks_poli');
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
        $this->load->view('Eks_poli/index', $data);
    }


    public function get_content_page(){

        $output = http_build_query($_GET) . "\n";
        if(isset($_GET['tbl-resume-kunjungan'])){
            $data[0] = array(
                'nameid' => 'tbl-resume-kunjungan',
                'style' => 'table',
                'col_size' => 12,
                'url' => 'eksekutif/Eks_poli/data?prefix=1&TypeChart=table&style=TableResumeKunjungan&'.$output.'',
            );
        }
        
        if(isset($_GET['graph-line-1'])){
            $data[1] = array(
                'nameid' => 'graph-line-1',
                'style' => 'line',
                'col_size' => 12,
                'url' => 'eksekutif/Eks_poli/data?prefix=2&TypeChart=line&style=1&'.$output.'',
            );
        }

        if(isset($_GET['tbl-resume-kunjungan-harian'])){
            $data[2] = array(
                'nameid' => 'tbl-resume-kunjungan-harian',
                'style' => 'table',
                'col_size' => 12,
                'url' => 'eksekutif/Eks_poli/data?prefix=4&TypeChart=table&style=TableResumeKunjunganHarian&'.$output.'',
            );
        }

        if(isset($_GET['tbl-resume-kunjungan-pasien'])){
            $data[3] = array(
                'nameid' => 'tbl-resume-kunjungan-pasien',
                'style' => 'table',
                'col_size' => 12,
                'url' => 'eksekutif/Eks_poli/data?prefix=5&TypeChart=table&style=TableResumeKunjunganPasien&'.$output.'',
            );
        }

        if(isset($_GET['tbl-resume-kunjungan-pasien-asuransi'])){
            $data[4] = array(
                'nameid' => 'tbl-resume-kunjungan-pasien-asuransi',
                'style' => 'table',
                'col_size' => 12,
                'url' => 'eksekutif/Eks_poli/data?prefix=6&TypeChart=table&style=TableResumeKunjunganPasienAsuransi&'.$output.'',
            );
        }

        if(isset($_GET['tbl-resume-kinerja-dokter'])){
            $data[4] = array(
                'nameid' => 'tbl-resume-kinerja-dokter',
                'style' => 'table',
                'col_size' => 12,
                'url' => 'eksekutif/Eks_poli/data?prefix=7&TypeChart=table&style=TableResumeKinerjaDokter&'.$output.'',
            );
        }

        if(isset($_GET['tbl-resume-pasien-harian'])){
            $data[5] = array(
                'nameid' => 'tbl-resume-pasien-harian',
                'style' => 'table',
                'col_size' => 12,
                'url' => 'eksekutif/Eks_poli/data?prefix=8&TypeChart=table&style=TableResumePasienHarian&'.$output.'',
            );
        }

        if(isset($_GET['tbl-resume-akunting-byjurnal'])){
            $data[5] = array(
                'nameid' => 'tbl-resume-akunting-byjurnal',
                'style' => 'table',
                'col_size' => 12,
                'url' => 'eksekutif/Eks_poli/data?prefix=9&TypeChart=table&style=TableResumeByJurnal&'.$output.'',
            );
        }

        // $data[2] = array(
        //     'mod' => $_GET['mod'],
        //     'nameid' => 'graph-pie-1',
        //     'style' => 'pie',
        //     'col_size' => 12,
        //     'url' => 'eksekutif/Eks_poli/data?prefix=3&TypeChart=pie&style=1&mod='.$_GET['mod'].'',
        // );

        // if(isset($_GET['graph-pie-1'])){
        //     $data[3] = array(
        //         'mod' => $_GET['mod'],
        //         'nameid' => 'graph-pie-1',
        //         'style' => 'pie',
        //         'col_size' => 6,
        //         'url' => 'templates/Templates/graph?prefix=112&TypeChart=pie&style=1&mod=11',
        //         );
        // }

        // if(isset($_GET['graph-table-1'])){
        //     $data[4] = array(
        //         'mod' => $_GET['mod'],
        //         'nameid' => 'graph-table-1',
        //         'style' => 'table',
        //         'col_size' => 6,
        //         'url' => 'templates/Templates/graph?prefix=113&TypeChart=table&style=1&mod=11',
        //         );
        // }

        

        // $data[2] = array(
        //     'mod' => $_GET['mod'],
        //     'nameid' => 'graph-table-1',
        //     'style' => 'table',
        //     'col_size' => 6,
        //     'url' => 'templates/Templates/graph?prefix=3&TypeChart=table&style=1&mod='.$_GET['mod'].'',
        // );

        echo json_encode($data);
    }

    public function data(){
        echo json_encode($this->Eks_poli->get_content_data($_GET), JSON_NUMERIC_CHECK);
    }

    public function show_detail(){
        $data = array();
        $data['value'] = $this->Eks_poli->get_detail_data();
        // echo '<pre>';print_r($data);die;
        $this->load->view('Eks_poli/ViewDetailData', $data);
    }

    public function show_detail_unit(){
        $data = array();
        $data['value'] = $this->Eks_poli->get_detail_data_unit();
        // echo '<pre>';print_r($data);die;
        $this->load->view('Eks_poli/ViewDetailDataUnit', $data);
    }

    public function show_detail_pasien(){
        $data = array();
        $data['value'] = $this->Eks_poli->get_detail_data_pasien();
        // echo '<pre>';print_r($data);die;
        $this->load->view('Eks_poli/ViewDetailDataPasien', $data);
    }


}


/* End of file example.php */
/* Location: ./application/modules/example/controllers/example.php */
