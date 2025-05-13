<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Rekap_transaksi_obat extends MX_Controller {

    /*function constructor*/
    function __construct() 
    {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'farmasi/Rekap_transaksi_obat');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('Rekap_transaksi_obat_model', 'Rekap_transaksi_obat');
        $this->load->model('Farmasi_pesan_resep_model', 'Farmasi_pesan_resep');
        // load library
        $this->load->library('Print_direct');
        $this->load->library('Print_escpos'); 
        $this->load->library('stok_barang');
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
        $this->load->view('Rekap_transaksi_obat/index', $data);
    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->Rekap_transaksi_obat->get_datatables();
        // if(isset($_GET['search']) AND $_GET['search']==TRUE){
        //     $this->find_data(); exit;
        // }
        $data = array();
        $no = $_POST['start'];
        $atts = array('class' => 'btn btn-xs btn-warning','width'       => 900,'height'      => 500,'scrollbars'  => 'no','status'      => 'no','resizable'   => 'no','screenx'     => 1000,'screeny'     => 80,'window_name' => '_blank'
            );
        
        foreach ($list as $row_list) {
            $no++;

            $row = array();
            $row[] = '<div class="center">'.$no.'</div>';
            $row[] = $this->tanggal->formatDateTime($row_list->tgl_trans);
            $row[] = '<div class="center">'.$row_list->no_mr.'</div>';
            $row[] = strtoupper($row_list->nama_pasien);
            $row[] = strtoupper($row_list->nama_brg);
            $row[] = '<div class="center">'.$row_list->jumlah.'</div>';
            $row[] = $row_list->dokter_pengirim;
            $row[] = $row_list->nama_pelayanan;
            $row[] = $row_list->diagnosa_akhir;
            
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Rekap_transaksi_obat->count_all(),
                        "recordsFiltered" => $this->Rekap_transaksi_obat->count_filtered(),
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

    public function export_excel()
    {
        $list = $this->Rekap_transaksi_obat->get_data();
        $data = array(
            'fields' 	=> $list->field_data(),
			'data' 	=> $list->result(),
		);
        // echo "<pre>"; print_r($data); die;

        $this->load->view('Rekap_transaksi_obat/view_data', $data);

    }



    
}


/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
