<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Dokumen_klaim_prb extends MX_Controller {

    /*function constructor*/
    function __construct() 
    {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'farmasi/Dokumen_klaim_prb');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('Etiket_obat_model', 'Etiket_obat');
        $this->load->model('Entry_resep_racikan_model', 'Entry_resep_racikan');
        $this->load->model('Dokumen_klaim_prb_model', 'Dokumen_klaim_prb');
        $this->load->model('ws_bpjs/Ws_index_model', 'Ws_index');
        $this->load->model('Retur_obat_model', 'Retur_obat');
        // load library
        $this->load->library('Print_direct');
        $this->load->library('Print_escpos'); 
        // load module
        $this->load->module('Templates/Templates.php');

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
        $this->load->view('Dokumen_klaim_prb/index', $data);
    }

    public function get_detail($id){
        $flag = $_GET['flag'];
        
        $data = array(
            'title' => 'Preview Transaksi' ,
            'breadcrumbs' => $this->breadcrumbs->show(),
            'flag' => $_GET['flag']
        );
        $data['value'] = $this->Etiket_obat->get_by_id($id);
        $detail_log = $this->Dokumen_klaim_prb->get_detail($id);
        $data['resep'] = $detail_log;
        // get dokumen klaim
        $data['dokumen'] = $this->db->get_where('fr_tc_far_dokumen_klaim_prb', array('kode_trans_far' => $id))->result();
        $month = date("M",strtotime($data['value']->tgl_trans));
        $year = date("Y",strtotime($data['value']->tgl_trans));
        $data['path_dok_klaim'] = PATH_DOK_KLAIM_FARMASI.'merge-'.$month.'-'.$year.'/'.$data['value']->no_sep.'.pdf';
        // echo '<pre>';print_r($data);
        $temp_view = $this->load->view('farmasi/Dokumen_klaim_prb/detail_table_view', $data, true);
        echo json_encode( array('html' => $temp_view) );
    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->Dokumen_klaim_prb->get_datatables();
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
            $row[] = '';
            $row[] = $row_list->kode_trans_far;
            $row[] = '<div class="center"><a href="#" onclick="getMenu('."'farmasi/Verifikasi_resep_prb/preview_verifikasi/".$row_list->kode_trans_far."?flag=RJ'".')">'.$row_list->kode_trans_far.'</a></div>';
            $row[] = '<div class="left">'.$row_list->no_sep.'</div>';
            $row[] = $this->tanggal->formatDateTime($row_list->tgl_trans);
            $row[] = '<div class="center">'.$row_list->no_mr.'</div>';
            $row[] = strtoupper($row_list->nama_pasien);
            $row[] = $row_list->dokter_pengirim;
            $row[] = $row_list->nama_pelayanan;
            // $row[] = '<div class="pull-right">'.number_format($row_list->total).'</div>';            
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Dokumen_klaim_prb->count_all(),
                        "recordsFiltered" => $this->Dokumen_klaim_prb->count_filtered(),
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

    function preview_verifikasi($kode_trans_far){

        /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Preview Transaksi Farmasi', 'Entry_resep_ri_rj/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$kode_trans_far);

        $data = array(
            'title' => 'Preview Transaksi' ,
            'breadcrumbs' => $this->breadcrumbs->show(),
            'flag' => $_GET['flag']
        );
        $data['value'] = $this->Etiket_obat->get_by_id($kode_trans_far);
        $detail_log = $this->Dokumen_klaim_prb->get_detail($kode_trans_far);
        $data['resep'] = $detail_log;

        // echo '<pre>'; print_r($data);die;
        $this->load->view('farmasi/Dokumen_klaim_prb/preview_verifikasi', $data);

    }
    
}


/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
