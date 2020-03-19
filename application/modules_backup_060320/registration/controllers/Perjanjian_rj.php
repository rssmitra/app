<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Perjanjian_rj extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'registration/Perjanjian_rj');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('Perjanjian_rj_model', 'Perjanjian_rj');
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
        $this->load->view('Perjanjian_rj/index', $data);
    }

    public function get_by_mr($no_mr) { 
        /*define variable data*/
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show(),
            'no_mr' => $no_mr
        );
        /*load view index*/
        $this->load->view('Perjanjian_rj/index_by_mr', $data);
    }
    
    public function get_data()
    {
        /*get data from model*/
        if(isset($_GET['search']) AND $_GET['search']==TRUE ){
            $this->find_data();
        }else{
            $list = $this->Perjanjian_rj->get_datatables(); 
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $row_list) {
                $no++;
                $row = array();
                $html = '';

                if( isset($_GET['no_mr']) AND $_GET['no_mr'] != '' ){
                    $html .= '<li><a href="#" onclick="changeModulRjFromPerjanjian('.$row_list->id_tc_pesanan.','.$row_list->kode_dokter.','."'".$row_list->no_poli."'".','."'".$row_list->kode_perjanjian."'".')">Daftarkan Pasien</a></li>';
                }else{
                    $html .= '<li><a href="#" onclick="getMenu('."'registration/Reg_klinik?idp=".$row_list->id_tc_pesanan."&kode_dokter=".$row_list->kode_dokter."&poli=".$row_list->no_poli."&kode_perjanjian=".$row_list->kode_perjanjian."&no_mr=".$row_list->no_mr."'".')">Daftarkan Pasien</a></li>';
                }
                
                $row[] = '<div class="center">'.$no.'</div>';
                $row[] = '<div class="center"><div class="btn-group">
                            <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">
                                <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-inverse">
                                '.$html.'
                                <li><a href="#" onclick="cetak_surat_kontrol('.$row_list->id_tc_pesanan.')">Cetak Surat Kontrol</a></li>
                            </ul>
                        </div></div>';
                $no_mr = ($row_list->no_mr == NULL)?'<i class="fa fa-user green bigger-150"></i>':$row_list->no_mr;
                $row[] = '<div class="center">'.$no_mr.'</div>';
                $row[] = '<a href="#">'.strtoupper($row_list->nama).'</>';
                $row[] = '<a href="#">'.$row_list->no_hp.'</>';
                $row[] = ($row_list->nama_perusahaan==NULL)?'<div class="left">PRIBADI/UMUM</div>':'<div class="left">'.$row_list->nama_perusahaan.'</div>';
                $row[] = '<div class="left">'.$row_list->nama_bagian.'</div>';
                $row[] = '<div class="left">'.$row_list->nama_pegawai.'</div>';
                if( isset($_GET['flag']) AND $_GET['flag']=='HD' ){
                    $row[] = $row_list->selected_day;
                }else{
                    $row[] = $this->tanggal->formatDate($row_list->tgl_pesanan);
                }
                $row[] = $row_list->unique_code_counter;
                $row[] = ($row_list->tgl_masuk == NULL) ? '<div class="center"><span class="label label-sm label-danger"><i class="fa fa-times-circle"></i></span></div>' : '<div class="center"><span class="label label-sm label-success"><i class="fa fa-check"></i></span></div>';


                $data[] = $row;
            }

            $output = array(
                            "draw" => $_POST['draw'],
                            "recordsTotal" => $this->Perjanjian_rj->count_all(),
                            "recordsFiltered" => $this->Perjanjian_rj->count_filtered(),
                            "data" => $data,
                    );
            //output to json format
            echo json_encode($output);
        }
        
    }

    public function find_data()
    {   
        $output = array( "data" => http_build_query($_POST) . "\n" );
        echo json_encode($output);
    }


}

/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
