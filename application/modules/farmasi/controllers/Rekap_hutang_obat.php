<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Rekap_hutang_obat extends MX_Controller {

    /*function constructor*/
    function __construct() 
    {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'farmasi/Rekap_hutang_obat');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('Rekap_hutang_obat_model', 'Rekap_hutang_obat');

        /*enable profiler*/
        $this->output->enable_profiler(false);
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'Title';

    }

    public function index() 
    { 
        /*define variable data*/
        $data = array(
            'title' => $this->title ,
            'breadcrumbs' => $this->breadcrumbs->show(),
        );
        /*load view index*/
        $this->load->view('Rekap_hutang_obat/index', $data);
    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->Rekap_hutang_obat->get_datatables();
        if(isset($_GET['search']) AND $_GET['search']==TRUE){
            $this->find_data(); exit;
        }
        $data = array();
        $no = $_POST['start'];
        $atts = array('class' => 'btn btn-xs btn-warning','width'       => 900,'height'      => 500,'scrollbars'  => 'no','status'      => 'no','resizable'   => 'no','screenx'     => 1000,'screeny'     => 80,'window_name' => '_blank'
            );

        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center">'.$no.'</div>';
            $row[] = '<div class="left">'.$row_list->kode_brg.'</div>';
            $row[] = '<div class="left">'.$row_list->nama_brg.'</div>';
            $jml_hutang = $row_list->jumlah_obat_biasa + $row_list->jumlah_obat_kronis;
            $row[] = '<div class="center">'.$jml_hutang.'</div>';
            $row[] = '<div class="center">'.$row_list->total_mutasi.'</div>';
            $sisa_hutang = $jml_hutang - $row_list->total_mutasi;
            $row[] = '<div class="center">'.$sisa_hutang.'</div>';
            // txt_clr_stok_far
            $txt_clr_fr = ($row_list->total_stok_farmasi > $sisa_hutang)?'green':'red';
            $row[] = '<div class="center" ><a style="color: '.$txt_clr_fr.'" href="#" onclick="getMenu('."'inventory/stok/Inv_stok_depo/detail/".$row_list->kode_brg."/060101'".')">'.$row_list->total_stok_farmasi.'</a></div>';
            $stok_gudang = $row_list->total_stok_gdg;
            $txt_clr_fr_gdg = ($stok_gudang > $sisa_hutang)?'green':'red';
            $row[] = '<div class="center" ><a style="color: '.$txt_clr_fr_gdg.'" href="#" onclick="getMenu('."'inventory/stok/Inv_stok_depo/detail/".$row_list->kode_brg."/060201'".')">'.$stok_gudang.'</a></div>';
            $jml_stok_all = ($row_list->total_stok_farmasi + $stok_gudang);
            $kekurangan = ($jml_stok_all > $sisa_hutang)?'0':$sisa_hutang - $jml_stok_all;
            $row[] = '<div class="center">'.$kekurangan.'</div>';
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Rekap_hutang_obat->count_all(),
                        "recordsFiltered" => $this->Rekap_hutang_obat->count_filtered(),
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
