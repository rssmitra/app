<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Reg_on_dashboard extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'registration/Reg_on_dashboard');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('Reg_on_dashboard_model', 'Reg_on_dashboard');
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
        $this->load->view('Reg_on_dashboard/index', $data);
    }
    
    public function get_data()
    {
        /*get data from model*/
        $list = $this->Reg_on_dashboard->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $pasien = json_decode($row_list->log_detail_pasien);
            $transaksi = json_decode($row_list->log_transaksi);
            $row = array();
            $row[] = '<div class="center">
                        <label class="pos-rel">
                            <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->regon_booking_id.'"/>
                            <span class="lbl"></span>
                        </label>
                      </div>';
            $row[] = '<div class="center"><div class="btn-group">
                        <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">
                            <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-inverse">
                            <li><a href="#" onclick="getMenu('."'registration/Reg_klinik?kode=".$row_list->regon_booking_kode."'".')">Daftarkan Pasien</a></li>
                            <li><a href="#" onclick="showModalDaftarPerjanjian('."'".$row_list->regon_booking_id."'".','."'".$row_list->regon_booking_no_mr."'".')" >Reschedule Booking</a></li>
                            <li class="divider"></li>
                            <li>
                                <a href="#">Selengkapnya</a>
                            </li>
                        </ul>
                    </div></div>';
            $row[] = '<div class="center">'.$row_list->regon_booking_kode.'</div>';
            $row[] = '<a href="#">'.strtoupper($pasien->nama_pasien).'</>';
            $row[] = $this->tanggal->formatDate($row_list->regon_booking_tanggal_perjanjian);
            $row[] = ucwords($transaksi->klinik->nama_bagian);
            $row[] = $transaksi->dokter->nama_pegawai;
            $row[] = $row_list->regon_booking_jam;
            $row[] = ($row_list->regon_booking_status == '0') ? '<div class="center"><span class="label label-sm label-danger"><i class="fa fa-times-circle"></i> Menunggu..</span></div>' : '<div class="center"><span class="label label-sm label-success">Selesai</span></div>';

            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Reg_on_dashboard->count_all(),
                        "recordsFiltered" => $this->Reg_on_dashboard->count_filtered(),
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
