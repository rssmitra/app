<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tracer extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'rekam_medis/Tracer');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('Tracer_model', 'Tracer');
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
        $this->load->view('Tracer/index', $data);
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
        $list = $this->Tracer->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center">
                        <a href="'.base_url().'registration/Reg_pasien/tracer/'.$row_list->no_registrasi.'/'.$row_list->no_mr.'" class="btn btn-xs btn-success" target="_blank" onclick="return popUnder(this);" alt="Cetak Tracer"><i class="fa fa-print"></i></a>
                        <a href="'.base_url().'registration/Reg_pasien/barcode_pasien/'.$row_list->no_mr.'/1" class="btn btn-xs btn-primary" target="_blank" onclick="return popUnder(this);" alt="Cetak Barcode"><i class="fa fa-barcode"></i></a>
                     </div>';
            $row[] = '<div class="center">'.$row_list->no_registrasi.'</div>';
            $row[] = '<div class="center">'.$row_list->no_mr.'</div>';
            $row[] = '<a href="#">'.strtoupper($row_list->nama_pasien).'</>';
            $row[] = ($row_list->nama_perusahaan)?$row_list->nama_perusahaan:'UMUM';
            $row[] = $this->tanggal->formatDateTime($row_list->tgl_jam_masuk);
            $row[] = ucwords($row_list->nama_bagian);
            $row[] = $row_list->nama_pegawai;
            $is_kiosk = isset($row_list->fullname) ? 1 : 2 ;
            if($row_list->tipe_daftar == null){
                $row[] = isset($row_list->fullname)?$row_list->fullname:'<div class="center"><span class="label label-success">KIOSK</span></div>';
            }else{
                $row[] = '<div class="center"><span class="label label-primary">Web Checkin</span></div>';
            }

            $row[] = '<div class="center">'.strtoupper($row_list->stat_pasien).'</div>';
            if($is_kiosk == 2){
                if(in_array($row_list->kode_bagian_tujuan, array('050101','050201','050301'))){
                    $row[] = '<div class="center" style="cursor: pointer !important"><span class="label label-success" onclick="PopupCenter('."'".base_url()."registration/Reg_pasien/barcode_pasien/".$row_list->no_mr."/1'".', '."'PRINT BARCODE'".', 350, 500)">print barcode</span></div>';
                }else{
                    $row[] = '<div class="center"><span class="red" style="font-weight: bold">-no tracer-</span></div>';
                }
            }else{
                $row[] = (($row_list->print_tracer == 'N') || ($row_list->print_tracer == NULL)) ? '<div class="center"><i class="fa fa-times-circle red"></i></div>' : '<div class="center"><i class="fa fa-check-circle green"></i></div>';
            }
            
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Tracer->count_all(),
                        "recordsFiltered" => $this->Tracer->count_filtered(),
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


}

/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
