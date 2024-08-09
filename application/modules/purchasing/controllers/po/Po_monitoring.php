<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Po_monitoring extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'purchasing/po/Po_monitoring');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('purchasing/po/Po_monitoring_model', 'Po_monitoring');
        $this->load->model('purchasing/po/Po_penerbitan_model', 'Po_penerbitan');
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
        $this->load->view('po/Po_monitoring/index', $data);
    }

    public function find_data()
    {   
        $output = array( "data" => http_build_query($_POST) . "\n" );
        echo json_encode($output);
    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->Po_monitoring->get_datatables();
        
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center">'.$no.'</div>';
            $row[] = $row_list->no_po;
            $row[] = $this->tanggal->formatDateDmy($row_list->tgl_po);
            $row[] = '<div class="left">'.$row_list->jenis_po.'</div>';
            $row[] = '<div class="left">'.$row_list->namasupplier.'</div>';
            $row[] = '<div class="left">'.$row_list->kode_brg.'</div>';
            $row[] = '<div class="left">'.$row_list->nama_brg.'</div>';
            $row[] = '<div class="center">'.$row_list->content.'</div>';
            $row[] = '<div class="center">'.$row_list->satuan_besar.'</div>';
            $row[] = '<div class="right" style="text-align: right !important">'.number_format($row_list->jumlah_besar, 2).'</div>';
            $row[] = '<div class="right" style="text-align: right !important">'.number_format($row_list->harga_satuan, 2).'</div>';
            $row[] = '<div class="right" style="text-align: right !important">'.number_format($row_list->discount, 2).'</div>';
            $row[] = '<div class="right" style="text-align: right !important">'.number_format($row_list->jumlah_harga, 2).'</div>';     
            if($row_list->jumlah_kirim > 0){
                $status = ($row_list->jumlah_kirim == $row_list->jumlah_besar) ? '<span class="label label-success">Selesai</span>' : '<span style="font-size: 11px; height:100% !important" class="label label-warning">Diterima '.$row_list->jumlah_kirim.' '.$row_list->satuan_besar.' <br> '.$this->tanggal->formatDateTime($row_list->tgl_terima).'</span>' ;
            }else{
                $status = ($row_list->jumlah_kirim == $row_list->jumlah_besar) ? '<span class="label label-success">Selesai</span>' : '<span class="label label-danger">Belum dikirim</span>' ;
            }
            $row[] = '<div class="center">'.$status.'</div>';     
            $data[] = $row;
            $arr_total[] = $row_list->jumlah_harga;
            $arr_barang[$row_list->nama_brg][] = $row_list->jumlah_besar;
        }

        // berdasarkan barang
        foreach ($arr_barang as $key => $value) {
            $count_brg[$key] = array_sum($arr_barang[$key]);
        }
        arsort($count_brg);
        $brg_terbanyak = array_search(max($count_brg), $count_brg);
        $ttl_brg_terbanyak = $count_brg[$brg_terbanyak];

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Po_monitoring->count_all(),
                        "recordsFiltered" => $this->Po_monitoring->count_filtered(),
                        "data" => $data,
                        "total_po" => array_sum($arr_total),
                        "nm_brg_max" => $brg_terbanyak,
                        "ttl_brg_max" => $ttl_brg_terbanyak,
                );
        //output to json format
        echo json_encode($output);
    }

    public function export_excel()
    {
        /*get data from model*/
        $list = $this->Po_monitoring->get_data();
        
        $data = array();
        foreach ($list as $row_list) {
            $arr_total[] = $row_list->jumlah_harga;
        }

        $data = array(
                        "data" => $list,
                        "total_po" => array_sum($arr_total),
                );
        //output to json format
        $this->load->view('po/Po_monitoring/index_excel', $data);
    }

}


/* End of file example.php */
/* Location: ./application/modules/example/controllers/example.php */
