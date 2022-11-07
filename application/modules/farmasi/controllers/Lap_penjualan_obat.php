<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Lap_penjualan_obat extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'farmasi/Lap_penjualan_obat');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('Lap_penjualan_obat_model', 'Lap_penjualan_obat');
        /*enable profiler*/
        $this->output->enable_profiler(false);
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'Title';;

    }

    public function index() { 
        /*define variable data*/
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show(),
        );
        /*load view index*/
        $this->load->view('Lap_penjualan_obat/index', $data);
    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->Lap_penjualan_obat->get_datatables();
        
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center">'.$no.'</div>';
            $row[] = $row_list->kode_brg;
            $row[] = $row_list->nama_brg;
            $row[] = '<div align="right">'.number_format($row_list->jml_terjual).'</div>';
            $row[] = '<div class="left">'.$row_list->satuan_kecil.'</div>';
            $row[] = '<div align="right">'.number_format($row_list->harga_rata_satuan).'</div>';
            $total_jual = $row_list->harga_rata_satuan * $row_list->jml_terjual;
            $row[] = '<div align="right">'.number_format($total_jual).'</div>';

            // $row[] = '<div align="right">'.number_format($row_list->stok_gdg).'</div>';
            // $row[] = '<div align="right">'.number_format($row_list->stok_dp).'</div>';
            $data[] = $row;
            $total[] = $total_jual;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Lap_penjualan_obat->count_all(),
                        "recordsFiltered" => $this->Lap_penjualan_obat->count_filtered(),
                        "data" => $data,
                        "total_penjualan" => array_sum($total),
                );
        //output to json format
        echo json_encode($output);
    }

    public function show_detail( $id )
    {   
        $table = ($_GET['flag'] == 'non_medis') ? 'mt_barang_nm' : 'mt_barang' ;
        $fields = $this->master->list_fields( $table );
        // print_r($fields);die;
        $data = $this->Lap_penjualan_obat->get_by_id($id);
        $html = $this->master->show_detail_row_table( $fields, $data );      

        echo json_encode( array('html' => $html) );
    }

    public function find_data()
    {   
        $output = array( "data" => http_build_query($_POST) . "\n" );
        echo json_encode($output);
    }

    public function export_excel()
    {   
        $data = array();
        $list = $this->Lap_penjualan_obat->get_data();
        $data['data'] = $list;
        $this->load->view('Lap_penjualan_obat/export_excel', $data);
    }

}

/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
