<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Rm_pasien_ri extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'rekam_medis/Rm_pasien_ri');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('Rm_pasien_ri_model', 'Rm_pasien_ri');
        /*enable profiler*/
        $this->output->enable_profiler(false);
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'Title';

    }

    public function index() { 
        /*define variable data*/
        $kode_bagian = (isset($_GET['kode_bagian']))?$_GET['kode_bagian']:0;
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show(),
            'kode_bagian' => $kode_bagian,
        );
        /*load view index*/
        $this->load->view('Rm_pasien_ri/index', $data);
    }
    
    public function get_data()
    {
        /*get data from model*/
        $list = $this->Rm_pasien_ri->get_datatables();
        //print_r($this->db->last_query());die;
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $link = 'billing/Billing';
            $str_type = 'RI';
            $row[] = $row_list->no_registrasi;
            $row[] = $str_type;
            $row[] = '';
           
            $row[] = '<div class="center"><div class="btn-group">
                        <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">
                            <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-inverse">
                            <li><a href="#" onclick="show_modal('."'rekam_medis/reg_pasien/view_detail_resume_medis/".$row_list->no_registrasi."'".', '."'RESUME MEDIS'".')">Selengkapnya</a></li>
                        </ul>
                    </div></div>';
            $row[] = '<div class="center"><a href="#" onclick="getMenu('."'".'rekam_medis/Rm_pasien/form/'.$row_list->no_registrasi.''."'".')">'.$row_list->no_registrasi.'</a></div>';
            $row[] = $row_list->no_mr.' - '.$row_list->nama_pasien;
            $row[] = $row_list->asal_bagian;
            $row[] = $row_list->nama_klas;
            $row[] = (isset($row_list->nama_perusahaan))?$row_list->nama_perusahaan:$row_list->nama_kelompok;
            $row[] = $row_list->dokter_merawat;
            //$row[] = $row_list->dr_pengirim;
            $row[] = '<span style="color: blue">In</span>. &nbsp;&nbsp; '.$this->tanggal->formatDateTime($row_list->tgl_masuk).'<br><span style="color: red">Out</span>. '.$this->tanggal->formatDateTime($row_list->tgl_keluar);

            if($row_list->status_pulang==1){
                $status_pulang = '<label class="label label-success"><i class="fa fa-check-circle"></i> Sudah Pulang</label>';
            }else{
                $status_pulang = '<label class="label label-warning"><i class="fa fa-info-circle"></i> Masih dirawat</label>';
            }
            $row[] = '<div class="center">'.$status_pulang.'</div>';


            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Rm_pasien_ri->count_all(),
                        "recordsFiltered" => $this->Rm_pasien_ri->count_filtered(),
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
