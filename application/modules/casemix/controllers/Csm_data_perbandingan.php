<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Csm_data_perbandingan extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'casemix/Csm_data_perbandingan');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            redirect(base_url().'login');exit;
        }
        /*load model*/
        $this->load->model('Csm_data_perbandingan_model', 'Csm_data_perbandingan');
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
        $this->load->view('Csm_data_perbandingan/index', $data);
    }

    public function analyze_data()
    {
        $list = $this->Csm_data_perbandingan->get_datatables();
        $data = array();
        foreach ($list as $row_list) {
            // hasil verif
            if( !empty($row_list->csm_uhvd_no_sep) ){
                $count_verif[] = $row_list->csm_uhvd_no_sep;
                $count_verif_duplicate[$row_list->csm_uhvd_no_sep][] = $row_list;
            }
            // find duplicate


            // hasil costing
            if( !empty($row_list->csm_rp_no_sep)  ){
                $count_costing[] = $row_list->csm_rp_no_sep;
            }

            // data sirs
            if( !empty($row_list->no_sep) ){
                $count_sirs[] = $row_list->no_sep;
            }

            // total data pendaftaran yang sudah di klaim
            if( !empty($row_list->csm_uhvd_no_sep) ){
                if( $row_list->no_sep == $row_list->csm_uhvd_no_sep ){
                    $count_claimed[] = $row_list->no_sep;
                }else{
                    $dt_not_claimed[] = $row_list;
                }
            }
            
        }

        // data sep duplicate
        foreach ($count_verif_duplicate as $key=>$value) {
            if(count($value) > 1){
                $duplicate_sep[$key] = $value;
            }
        }
        $data["duplicate_sep"] = isset($duplicate_sep) ? $duplicate_sep : [];
        $data["count_verif"] = isset($count_verif) ? count($count_verif) : 0;

        $data["count_sirs"] = isset($count_sirs) ? count($count_sirs) : 0;
        
        $data["count_costing"] = isset($count_costing) ? count($count_costing) : 0;
        $data["count_claimed"] = isset($count_claimed) ? count($count_claimed) : 0;
        $data["dt_not_claimed"] = isset($dt_not_claimed) ? $dt_not_claimed : 0;

        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Csm_data_perbandingan/form', $data);
    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->Csm_data_perbandingan->get_datatables();
        // print_r($list);die;
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center">'.$no.'</div>';
            $row[] = '<div class="center">'.$row_list->no_registrasi.'</div>';
            $row[] = $row_list->no_mr;
            $row[] = $row_list->nama_pasien;
            $row[] = $row_list->tgl_jam_masuk;
            $row[] = $row_list->no_sep;
            $row[] = $row_list->csm_uhvd_no_sep;
            $row[] = $row_list->csm_rp_no_sep;
            $data[] = $row;
            // hasil verif
            if( !empty($row_list->csm_uhvd_no_sep)  ){
                $count_verif[] = $row_list->csm_uhvd_no_sep;
            }

            // hasil costing
            if( !empty($row_list->csm_rp_no_sep)  ){
                $count_costing[] = $row_list->csm_rp_no_sep;
            }

            // data sirs
            if( !empty($row_list->no_sep)  ){
                $count_sirs[] = $row_list->no_sep;
            }
        }
        $output = array(
                    "draw" => $_POST['draw'],
                    "data" => $data,
                    "count_sirs" => isset($count_sirs) ? count($count_sirs) : 0,
                    "count_verif" => isset($count_verif) ? count($count_verif) : 0,
                    "count_costing" => isset($count_costing) ? count($count_costing) : 0,
        );
    
        
        //output to json format
        echo json_encode($output);
    }

    public function find_data()
    {   
        $output = array( "data" => http_build_query($_POST) . "\n" );
        echo json_encode($output);
    }

}


/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
