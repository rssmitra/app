<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Perjanjian_bedah extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'registration/Perjanjian_bedah');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('Perjanjian_bedah_model', 'Perjanjian_bedah');
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
        $this->load->view('Perjanjian_bedah/index', $data);
    }
    
    public function get_data()
    {
        /*get data from model*/
        $list = $this->Perjanjian_bedah->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center">
                        <label class="pos-rel">
                            <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->id_tc_pesanan.'"/>
                            <span class="lbl"></span>
                        </label>
                      </div>';
            /*$row[] = '<div class="center"><div class="btn-group">
                        <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">
                            <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-inverse">
                            <li><a href="#" onclick="getMenu('."'registration/Reg_klinik?idp=".$row_list->id_tc_pesanan."'".')">Daftarkan Pasien</a></li>
                            <li class="divider"></li>
                            <li>
                                <a href="#">Selengkapnya</a>
                            </li>
                        </ul>
                    </div></div>';*/
            $row[] = $row_list->no_mr;
            $row[] = '<a href="#">'.strtoupper($row_list->nama).'</a>';
            $row[] = ($row_list->nama_perusahaan==NULL)?'<div class="left">PRIBADI/UMUM</div>':'<div class="left">'.$row_list->nama_perusahaan.'</div>';
            $row[] = '<div class="left">'.$row_list->nama_pegawai.'</div>';
            $row[] = $this->tanggal->formatDateTime($row_list->jam_pesanan);
            $row[] = $row_list->nama_tarif;
            $row[] = $row_list->diagnosa;


            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Perjanjian_bedah->count_all(),
                        "recordsFiltered" => $this->Perjanjian_bedah->count_filtered(),
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
