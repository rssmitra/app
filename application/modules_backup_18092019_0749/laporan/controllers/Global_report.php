<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Global_report extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'laporan/Global_report');

        /*load model*/
        $this->load->model('Global_report_model', 'Global_report');
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
        $this->load->view('Global_report/index', $data);
    }

    public function pengadaan() { 
        /*define variable data*/
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show(),
            'flag' => 'pengadaan_mod_'.$_GET['mod'].''
        );

        /*load view index*/
        $this->load->view('Global_report/pengadaan_mod_'.$_GET['mod'].'', $data);
    }

    public function farmasi() { 
        /*define variable data*/
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show(),
            'flag' => 'farmasi_mod_'.$_GET['mod'].''
        );

        /*load view index*/
        $this->load->view('Global_report/farmasi_mod_'.$_GET['mod'].'', $data);
    }

    public function akunting() { 
        /*define variable data*/
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show(),
            'flag' => 'akunting_mod_'.$_GET['mod'].''
        );

        /*load view index*/
        $this->load->view('Global_report/akunting_mod_'.$_GET['mod'].'', $data);
    }

    public function so() { 
        /*define variable data*/
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show(),
            'flag' => 'so_mod_'.$_GET['mod'].''
        );

        /*load view index*/
        $this->load->view('Global_report/so_mod_'.$_GET['mod'].'', $data);
    }

    public function show_data(){

        $query_data = $this->Global_report->get_data();

        $data = array(
            'flag' => $_POST['flag'],
            'title' => $_POST['title'],
            'result' => $query_data,
        );

        if($_POST['submit']=='format_so'){
            $this->load->view('Global_report/'.$_POST['submit'].'', $data);
        }elseif($_POST['submit']=='input_so'){
            $this->load->view('Global_report/'.$_POST['submit'].'', $data);
        }else{
            $this->load->view('Global_report/view_data', $data);
        }

        
    }

    function getNamaKaryawan()
    {
        
        $result = $this->db->where("kode_dokter IS NULL AND nama_pegawai LIKE '%".$_POST['keyword']."%' ")
                          ->order_by('nama_pegawai', 'ASC')
                          ->get('mt_karyawan')->result();
        $arrResult = [];
        foreach ($result as $key => $value) {
            $arrResult[] = $value->no_induk.' : '.$value->nama_pegawai;
        }
        echo json_encode($arrResult);
        
        
    }

}

/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
