<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Manifest extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'rekam_medis/Manifest');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('Manifest_model', 'Manifest');
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
        $this->load->view('Manifest/index', $data);
    }

    public function form($id='')
    {
        /*if id is not null then will show form edit*/
        if( $id != '' ){
            /*breadcrumbs for edit*/
            $this->breadcrumbs->push('Edit '.strtolower($this->title).'', 'Reg_on_dashboard/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
            /*get value by id*/
            $data['value'] = $this->Reg_on_dashboard->get_by_id($id);
            /*initialize flag for form*/
            $data['flag'] = "update";
        }else{
            /*breadcrumbs for create or add row*/
            $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Reg_on_dashboard/'.strtolower(get_class($this)).'/form');
            /*initialize flag for form add*/
            $data['flag'] = "create";
        }
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Reg_on_dashboard/form', $data);
    }
    /*function for view data only*/
    public function show($id)
    {
        /*breadcrumbs for view*/
        $this->breadcrumbs->push('View '.strtolower($this->title).'', 'Reg_on_dashboard/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*define data variabel*/
        $data['value'] = $this->Reg_on_dashboard->get_by_id($id);
        $data['title'] = $this->title;
        $data['flag'] = "read";
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Reg_on_dashboard/form', $data);
    }

    // public function get_data()
    // {
    //     /*get data from model*/
    //     $list = $this->Manifest->get_datatables();
    //     $data = array();
    //     $no = $_POST['start'];
    //     foreach ($list as $row_list) {
    //         $no++;
    //         $row = array();
    //         $row[] = '<div class="center">
    //                     <label class="pos-rel">
    //                         <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->no_registrasi.'"/>
    //                         <span class="lbl"></span>
    //                     </label>
    //                 </div>';
    //         $row[] = '<div class="center">
    //                     <a href="'.base_url().'registration/Reg_pasien/barcode_pasien/'.$row_list->no_mr.'/1" class="btn btn-xs btn-primary" target="_blank" onclick="return popUnder(this);" alt="Cetak Barcode"><i class="fa fa-barcode"></i></a>
    //                  </div>';
    //         $row[] = '<div class="center">'.$no.'</div>';
    //         $row[] = '<div class="center"><a href="#" style="color: blue; font-weight: bold">'.$row_list->no_mr.'</a></div>';
    //         $row[] = strtoupper($row_list->nama_pasien);
    //         $row[] = ($row_list->nama_perusahaan)?$row_list->nama_perusahaan:'UMUM';
    //         $row[] = $this->tanggal->formatDateTime($row_list->tgl_jam_masuk);
    //         $row[] = ucwords($row_list->nama_bagian);
    //         $row[] = $row_list->nama_pegawai;
    //         $is_kiosk = isset($row_list->fullname) ? 1 : 2 ;
    //         if($row_list->tipe_daftar == null){
    //             $row[] = isset($row_list->fullname)?$row_list->fullname:'<div class="center"><span class="label label-success">KIOSK</span></div>';
    //         }else{
    //             $row[] = '<div class="center"><span class="label label-primary">Web Checkin</span></div>';
    //         }

    //         $row[] = '<div class="center">'.strtoupper($row_list->stat_pasien).'</div>';
    //         if($is_kiosk == 2){
    //             if(in_array($row_list->kode_bagian_tujuan, array('050101','050201','050301'))){
    //                 $row[] = '<div class="center" style="cursor: pointer !important"><span class="label label-success" onclick="PopupCenter('."'".base_url()."registration/Reg_pasien/barcode_pasien/".$row_list->no_mr."/1'".', '."'PRINT BARCODE'".', 350, 500)">print barcode</span></div>';
    //             }else{
    //                 $row[] = '<div class="center"><span class="red" style="font-weight: bold">-no tracer-</span></div>';
    //             }
    //         }else{
    //             $row[] = (($row_list->print_tracer == 'N') || ($row_list->print_tracer == NULL)) ? '<div class="center"><i class="fa fa-times-circle red"></i></div>' : '<div class="center"><i class="fa fa-check-circle green"></i></div>';
    //         }
            
    //         $data[] = $row;
    //     }

    //     $output = array(
    //                     "draw" => $_POST['draw'],
    //                     "recordsTotal" => $this->Manifest->count_all(),
    //                     "recordsFiltered" => $this->Manifest->count_filtered(),
    //                     "data" => $data,
    //             );
    //     //output to json format
    //     echo json_encode($output);
    // }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->Manifest->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center">'.$no.'</div>';
            $row[] = '';
            $row[] = $row_list->kode_dokter;
            $row[] = $row_list->kode_bagian;
            $row[] = $row_list->tgl_jam_poli;
            $row[] = $this->tanggal->formatDate($row_list->tgl_jam_poli);
            $row[] = strtoupper($row_list->nama_pegawai);
            $row[] = ucwords($row_list->nama_bagian);
            $row[] = '<div class="center">'.$row_list->total_pasien.'</div>';
            $row[] = '<div class="center" style="cursor: pointer !important"><span class="label label-success" onclick="PopupCenter('."'".base_url()."rekam_medis/Manifest/print_worklist/".$row_list->kode_dokter."/".$row_list->kode_bagian."/".$row_list->tgl_jam_poli."'".', '."'PRINT WORKLIST'".', 350, 500)">Print Worklist</span></div>';
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Manifest->count_all(),
                        "recordsFiltered" => $this->Manifest->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function getDetail($kode_dokter, $kode_bagian, $tgl_kunjungan){
        
        $params = [
            'kode_dokter' => $kode_dokter,
            'kode_bagian' => $kode_bagian,
            'tgl_kunjungan' => $tgl_kunjungan,
        ];
        $list = $this->Manifest->get_detail_pasien($params);
        $html = '';
        $html .= '<div style="margin-left: 50px">';
        $html .= '<span style="text-align: center; "><b>ANTRIAN PASIEN</b><br>'.$list[0]->nama_bagian.'<br>'.$list[0]->nama_pegawai.'<br>Tgl.'.$this->tanggal->formatDate($list[0]->tgl_jam_poli).'</span>';
        $html .= '<table class="table table-bordered" style="width: 50%;">';
        $html .= '<tr>';
        $html .= '<th align="center">NO</th>';
        $html .= '<th>NAMA PASIEN</th>';
        $html .= '<th align="center">STATUS</th>';
        $html .= '</tr>';
        foreach ($list as $key => $value) {
            # code...
            $html .= '<tr>';
            $html .= '<td align="center">'.$value->no_antrian.'</td>';
            $html .= '<td>'.$value->nama_pasien.'</td>';
            $html .= '<td align="center">'.strtoupper($value->flag_antrian).'</td>';
            $html .= '</tr>';
        }
        $html .= '</table>';
        $html .= '</div>';
        
        echo json_encode(array('html' => $html));
    }


    public function find_data()
    {   
        $output = array( "data" => http_build_query($_POST) . "\n" );
        echo json_encode($output);
    }

    public function print_worklist($kode_dokter, $kode_bagian, $tgl_kunjungan){
        
        $params = [
            'kode_dokter' => $kode_dokter,
            'kode_bagian' => $kode_bagian,
            'tgl_kunjungan' => $tgl_kunjungan,
        ];
        $list = $this->Manifest->get_detail_pasien($params);
        $params['dokter'] = $list[0]->nama_pegawai;
        $params['poli'] = $list[0]->nama_bagian;
        $params['list'] = $list;
        $this->load->view('Manifest/print_worklist', $params);
    }

}

/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
