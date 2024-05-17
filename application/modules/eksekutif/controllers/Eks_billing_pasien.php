<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Eks_billing_pasien extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'eksekutif/Eks_billing_pasien');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('eksekutif/Eks_billing_pasien_model', 'Eks_billing_pasien');

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
        $this->load->view('Eks_billing_pasien/index', $data);
    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->Eks_billing_pasien->get_datatables();
        // echo '<pre>';print_r($list);die;
        $data = array();
        $arr_total = array();
        $arr_paid = array();
        $arr_unpaid = array();
        $arr_total_cancel = array();
        $arr_total_billing = array();
        $no = 0;
        foreach ($list as $row_list) {
            // var_dump($row_list);die;
            $row = array();
            // sum total
            $total = $this->master->sumArrayByKey($row_list, 'total');

            $no++;
                $total_billing = $this->master->sumArrayByKey($row_list, 'total_billing');
                $arr_total[] = $total;
                $arr_total_billing[] = $total_billing;
                $row[] = '<div class="center">'.$no.'</div>';
                $row[] = $row_list[0]['no_mr'];
                $row[] = $row_list[0]['nama_pasien'];
                $row[] = ($row_list[0]['nama_perusahaan'])?$row_list[0]['nama_perusahaan']:'UMUM';
                $row[] = ucwords($row_list[0]['nama_bagian']);
                $row[] = ($row_list[0]['nama_dokter'])?$row_list[0]['nama_dokter']:'-';
                $row[] = $this->tanggal->formatDateTime($row_list[0]['tgl_jam_masuk']);
                if( $row_list[0]['status_batal'] == 1 ){
                    $row[] = '<div class="center"><span style="color: red; font-weight: bold">Batal</span></div>';
                    $arr_total_cancel[] = $total_billing;
                }else{
                    if( $total > 0 ){
                        $row[] = '<div class="center"><span style="color: red; font-weight: bold"> '.number_format($total).' </span></div>';
                    }else{
                        $row[] = '<div class="center"><label class="label label-success"><i class="fa fa-check-circle"></i> Lunas</label></div>';
                        $arr_paid[] = $total_billing;
                    }
                }
                $row[] = '<div class="pull-right"><b>'.number_format($total_billing).'</b></div>';
                $data[] = $row;

              
        }

        // echo '<pre>';print_r($tgl_keluar);die;
        
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Eks_billing_pasien->count_all(),
                        "recordsFiltered" => $this->Eks_billing_pasien->count_filtered(),
                        "data" => $data,
                        "total_paid" => array_sum($arr_paid),
                        "total_unpaid" => array_sum($arr_total),
                        "total_cancel" => array_sum($arr_total_cancel),
                        "total_billing" => array_sum($arr_total_billing),
                );
        //output to json format
        echo json_encode($output);
    }


    public function get_content_page(){

        $data[0] = array(
            'nameid' => 'tbl-resume-kunjungan',
            'style' => 'table',
            'col_size' => 12,
            'url' => 'eksekutif/Eks_billing_pasien/data?prefix=1&TypeChart=table&style=TableResumeHutang&from_tgl='.$_GET['from_tgl'].'&to_tgl='.$_GET['to_tgl'].'',
            );

        $data[1] = array(
            'nameid' => 'graph-line-1',
            'style' => 'line',
            'col_size' => 12,
            'url' => 'eksekutif/Eks_billing_pasien/data?prefix=2&TypeChart=line&style=2&from_tgl='.$_GET['from_tgl'].'&to_tgl='.$_GET['to_tgl'].'',
        );

        echo json_encode($data);
    }

    public function data(){
        echo json_encode($this->Eks_billing_pasien->get_content_data($_GET), JSON_NUMERIC_CHECK);
    }

    public function show_detail(){
        $data = array();
        $data['value'] = $this->Eks_billing_pasien->get_detail_data();
        // echo '<pre>';print_r($data);die;
        $this->load->view('Eks_billing_pasien/ViewDetailData', $data);
    }

    public function find_data()
    {   
        $output = array( "data" => http_build_query($_POST) . "\n" );
        echo json_encode($output);
    }


}


/* End of file example.php */
/* Location: ./application/modules/example/controllers/example.php */
