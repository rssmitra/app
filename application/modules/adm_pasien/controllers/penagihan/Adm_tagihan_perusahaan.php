<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Adm_tagihan_perusahaan extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'adm_pasien/penagihan/Adm_tagihan_perusahaan');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('adm_pasien/penagihan/Adm_tagihan_perusahaan_model', 'Adm_tagihan_perusahaan');
        $this->load->model('adm_pasien/Adm_pasien_model', 'Adm_pasien');
        /*enable profiler*/
        $this->output->enable_profiler(false);
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'Title';

    }

    public function index() { 
        //echo '<pre>';print_r($this->session->all_userdata());
        /*define variable data*/
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show(),
        );
        /*load view index*/
        $this->load->view('penagihan/Adm_tagihan_perusahaan/index', $data);
    }

    public function form($id='')
    {

        $qry_url = http_build_query($_GET);
        /*if id is not null then will show form edit*/
            /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Create Invoice '.strtolower($this->title).'', 'Adm_tagihan_perusahaan/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id.'?'.$qry_url);
        /*get value by id*/
        $data['value'] = $this->Adm_tagihan_perusahaan->get_by_id($id); 
        
        /*initialize flag for form*/
        $data['flag'] = "update";
    
        /*title header*/
        $data['qry_url'] = $qry_url;
        $data['no_invoice'] = $this->master->format_no_invoice($_GET['jenis_pelayanan']);
        $data['detail_pasien'] = $this->Adm_tagihan_perusahaan->get_detail_pasien($id); 
        $data['title'] = $this->title;
        // echo '<pre>'; print_r($data);die;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('penagihan/Adm_tagihan_perusahaan/form', $data);
    }


    public function find_data()
    {   
        $output = array( "data" => http_build_query($_POST) . "\n" );
        echo json_encode($output);
    }
    
    public function get_data()
    {
        /*get data from model*/
        $list = ($_GET) ? $this->Adm_tagihan_perusahaan->get_datatables() : [] ;
        // $qry_url = ($_GET) ? '?keyword='.$_GET['keyword'].'&from_tgl='.$_GET['from_tgl'].'&to_tgl='.$_GET['to_tgl'].'&jenis_pelayanan='.$_GET['jenis_pelayanan'].'' : '' ;
        $qry_url = ($_GET) ? '?'.http_build_query($_GET) : '' ;
        // print_r($list);die;
        $data = array();
        $arr_total = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $jumlah_tagihan = $row_list->jml_tghn;
            $no++;
            $row = array();
            $row[] = '<div class="center"></div>';
            $row[] = $row_list->kode_perusahaan;
            $row[] = '<div class="center">'.$no.'</div>';
            $row[] = $row_list->nama_perusahaan;
            $row[] = '<div class="pull-right"><a href="#">'.number_format($jumlah_tagihan).',-</a></div>';
            $row[] = '<div class="center"><a href="#" onclick="getMenu('."'adm_pasien/penagihan/Adm_tagihan_perusahaan/form/".$row_list->kode_perusahaan.$qry_url."'".')" class="label label-xs label-primary">Buat Invoice</a></div>';
            $data[] = $row;
              
        }
        
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Adm_tagihan_perusahaan->count_all(),
                        "recordsFiltered" => $this->Adm_tagihan_perusahaan->count_filtered(),
                        "data" => $data,
                        "total_billing" => array_sum($arr_total),
                );
        //output to json format
        echo json_encode($output);
    }

    public function get_hist_inv($kode_perusahaan)
    {
        /*get data from model*/
        $list = $this->Adm_tagihan_perusahaan->get_hist_inv($kode_perusahaan);
        $data = array(
            'kode_perusahaan' => $kode_perusahaan,
            'result' => $list,
        ); 
        $html = $this->load->view('penagihan/Adm_tagihan_perusahaan/detail_table', $data, true);

        echo json_encode(array('html' => $html, 'data' => $list));
    }

    public function get_invoice_detail($id_tagih)
    {
        /*get data from model*/
        $list = $this->Adm_tagihan_perusahaan->get_invoice_detail($id_tagih);
        $no_invoice = $list[0]->no_invoice_tagih;
        echo json_encode(array('data' => $list, 'no_invoice' => $no_invoice));
    }

}


/* End of file example.php */
/* Location: ./application/modules/example/controllers/example.php */
