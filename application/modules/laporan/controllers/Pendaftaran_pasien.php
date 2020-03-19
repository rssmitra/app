<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pendaftaran_pasien extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'laporan/Pendaftaran_pasien');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('Pendaftaran_pasien_model', 'Pendaftaran_pasien');
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
        $this->load->view('Pendaftaran_pasien/index', $data);
    }

    public function form($id='')
    {
        /*if id is not null then will show form edit*/
        if( $id != '' ){
            /*breadcrumbs for edit*/
            $this->breadcrumbs->push('Edit '.strtolower($this->title).'', 'Reg_on_dashboard/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
            /*get value by id*/
            $data['value'] = $this->Reg_on_dashboard->get_by_id($id);
            /*initialize flag for form*/
            $data['flag'] = "update";
        }else{
            /*breadcrumbs for create or add row*/
            $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Reg_on_dashboard/'.strtolower(get_class($this)).'/form');
            /*initialize flag for form add*/
            $data['flag'] = "create";
        }
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Reg_on_dashboard/form', $data);
    }
    /*function for view data only*/
    public function show($id)
    {
        /*breadcrumbs for view*/
        $this->breadcrumbs->push('View '.strtolower($this->title).'', 'Reg_on_dashboard/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*define data variabel*/
        $data['value'] = $this->Reg_on_dashboard->get_by_id($id);
        $data['title'] = $this->title;
        $data['flag'] = "read";
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Reg_on_dashboard/form', $data);
    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->Pendaftaran_pasien->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = $no;
            $row[] = '<div class="center">'.$row_list->no_registrasi.'</div>';
            $row[] = '<div class="center">'.$row_list->no_mr.'</div>';
            $row[] = '<a href="#">'.strtoupper($row_list->nama_pasien).'</>';
            $row[] = ($row_list->nama_perusahaan)?$row_list->nama_perusahaan:'UMUM';
            $row[] = $this->tanggal->formatDateTime($row_list->tgl_jam_masuk);
            $row[] = ucwords($row_list->nama_bagian);
            $row[] = $row_list->nama_pegawai;
            $row[] = $row_list->stat_pasien;
                        
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Pendaftaran_pasien->count_all(),
                        "recordsFiltered" => $this->Pendaftaran_pasien->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }


    public function find_data()
    {   
        $output = array( "data" => http_build_query($_POST) . "\n" );
        //print_r($this->db->last_query());die;
        echo json_encode($output);
    }

    public function export_detail()
    {
        //print_r($_POST);die;
        
        $list = $this->Pendaftaran_pasien->get_exported_data($_POST);

        $data = array();

        $data['result']=$list;

        $from_ = explode('-',$this->tanggal->formatDateTime($_POST['from_tgl']));
        $from = $from_[0];

        $to_ = explode('-',$this->tanggal->formatDateTime($_POST['to_tgl']));
        $to = $to_[0];

        $now_ = explode('-',$this->tanggal->formatDateTime(date('Y-m-d')));
        $now = $now_[0];

        $data['from_tgl']=($_POST['from_tgl'])?$from:$now;

        $data['to_tgl']=($_POST['to_tgl'])?$to:$now;

        //print_r($this->db->last_query());die;

        if($_POST['export_by']=='detail'){
            $this->load->view('Pendaftaran_pasien/export',$data);
        }else{
            $this->load->view('Pendaftaran_pasien/export_general',$data);
        }
        

    }

}

/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
