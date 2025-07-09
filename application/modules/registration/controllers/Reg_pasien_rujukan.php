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
        // echo "<pre>"; print_r($list);die;
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();

            $btn_daftarkan = ($row_list->status == '0') ? '<li><a href="#" onclick="getMenu('."'registration/Reg_klinik?kode_rujukan=".$row_list->kode_rujukan."&no_reg=".$row_list->no_registrasi."&mr=".$row_list->no_mr."&no_kunj=".$row_list->no_kunjungan_lama."'".')">Daftarkan Pasien</a></li>' : '' ;

            $row[] = '<div class="center">'.$no.'</div>';
            $row[] = '<div class="center"><a href="#" onclick="getMenu('."'registration/Reg_klinik?kode_rujukan=".$row_list->kode_rujukan."&no_reg=".$row_list->no_registrasi."&mr=".$row_list->no_mr."&no_kunj=".$row_list->no_kunjungan_lama."'".')" style="color: blue; font-weight: bold;">'.$row_list->no_mr.'</a></div>';
            $row[] = $row_list->nama_pasien;
            $row[] = $this->tanggal->formatDateTime($row_list->tgl_input);
            $row[] = ucwords($row_list->nama_rujukan_dari);
            $row[] = $row_list->nama_pegawai;
            $row[] = ucwords($row_list->tujuan_bagian_rujuk);
            $row[] = "<div class='center'><a href='#' class='label label-xs label-primary' onclick=\"show_modal_medium_return_json('pelayanan/Pl_pelayanan_ri/show_catatan_pengkajian_by_no_form/".$row_list->no_kunjungan_lama."?no=36|50', 'Surat Permohonan Rawat Inap')\"><i class='fa fa-eye'></i> Surat pengantar</a></div>";
            $row[] = ($row_list->status == '0') ? '<div class="center"><span class="label label-danger"><i class="fa fa-times-circle"></i> Dalam proses</span></div>' : '<div class="center"><span class="label label-success"><i class="fa fa-check-circle"></i> Sudah didaftarkan</span></div>';

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
