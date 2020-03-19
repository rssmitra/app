<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Reg_pasien_rujukan extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'registration/Reg_pasien_rujukan');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('Reg_pasien_rujukan_model', 'Reg_pasien_rujukan');
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
        $this->load->view('Reg_pasien_rujukan/index', $data);
    }
    
    public function get_data()
    {
        /*get data from model*/
        $list = $this->Reg_pasien_rujukan->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();

            $btn_daftarkan = ($row_list->status == '0') ? '<li><a href="#" onclick="getMenu('."'registration/Reg_klinik?kode_rujukan=".$row_list->kode_rujukan."&no_reg=".$row_list->no_registrasi."&mr=".$row_list->no_mr."&no_kunj=".$row_list->no_kunjungan_lama."'".')">Daftarkan Pasien</a></li>' : '' ;

            $row[] = '<div class="center">
                        <label class="pos-rel">
                            <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->kode_rujukan.'"/>
                            <span class="lbl"></span>
                        </label>
                      </div>';
            $row[] = '<div class="center"><div class="btn-group">
                        <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">
                            <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-inverse">
                            <li><a href="#" onclick="getMenu('."'registration/Reg_klinik?kode_rujukan=".$row_list->kode_rujukan."&no_reg=".$row_list->no_registrasi."&mr=".$row_list->no_mr."&no_kunj=".$row_list->no_kunjungan_lama."'".')">Daftarkan Pasien</a></li>
                            <li><a href="#">Selengkapnya</a></li>
                        </ul>
                    </div></div>';
            $row[] = '<div class="center">'.$row_list->kode_rujukan.'</div>';
            $row[] = '<div class="center">'.$row_list->no_mr.'</div>';
            $row[] = $row_list->nama_pasien;
            $row[] = $this->tanggal->formatDateTime($row_list->tgl_input);
            $row[] = ucwords($row_list->nama_rujukan_dari);
            $row[] = ucwords($row_list->tujuan_bagian_rujuk);
            $row[] = ($row_list->status == '0') ? '<div class="center"><i class="fa fa-times-circle bigger-150 red"></i></div>' : '<div class="center"><i class="fa fa-check-circle bigger-150 green"></i></div>';

            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Reg_pasien_rujukan->count_all(),
                        "recordsFiltered" => $this->Reg_pasien_rujukan->count_filtered(),
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
