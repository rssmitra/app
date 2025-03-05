<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Riwayat_pasien_ranap extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'registration/Riwayat_pasien_ranap');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('Riwayat_pasien_ranap_model', 'Riwayat_pasien_ranap');
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
        $this->load->view('Riwayat_pasien_ranap/index', $data);
    }
    
    public function get_data()
    {
        /*get data from model*/
        $list = $this->Riwayat_pasien_ranap->get_datatables();
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
                            <li><a href="#">Cetak Tracer</a></li>
                            <li><a href="#" onclick="show_modal('."'registration/reg_pasien/view_detail_resume_medis/".$row_list->no_registrasi."'".', '."'RESUME MEDIS'".')">Selengkapnya</a></li>
                        </ul>
                    </div></div>';
            $row[] = '<div class="center">'.$row_list->no_registrasi.'</div>';
            $row[] = $row_list->no_mr.' - '.$row_list->nama_pasien;
            $row[] = $row_list->asal_bagian;
            $row[] = $row_list->nama_klas;
            $row[] = (isset($row_list->nama_perusahaan))?$row_list->nama_perusahaan:$row_list->nama_kelompok;
            $row[] = $row_list->dokter_merawat;
            //$row[] = $row_list->dr_pengirim;
            $row[] = $this->tanggal->formatDateTime($row_list->tgl_masuk);
            $row[] = $this->tanggal->formatDateTime($row_list->tgl_keluar);
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
                        "recordsTotal" => $this->Riwayat_pasien_ranap->count_all(),
                        "recordsFiltered" => $this->Riwayat_pasien_ranap->count_filtered(),
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

    public function export_excel(){
        /*get data from model*/
        $list = $this->Riwayat_pasien_ranap->get_data();
        $data =array();
        $data['list'] = $list;
  
        // echo "<pre>";print_r($list); die;
  
        $this->load->view('Riwayat_pasien_ranap/export_excel', $data);
      }


}

/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
