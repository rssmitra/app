<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Riwayat_reg_sep_fail extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'registration/Riwayat_reg_sep_fail');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('Riwayat_reg_sep_fail_model', 'Riwayat_reg_sep_fail');
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
        $this->load->view('Riwayat_reg_sep_fail/index', $data);
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

    public function get_data()
    {
        /*get data from model*/
        $list = $this->Riwayat_reg_sep_fail->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center">
                        <label class="pos-rel">
                            <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->no_registrasi.'"/>
                            <span class="lbl"></span>
                        </label>
                    </div>';
            $row[] = '<div class="center">'.$no.'</div>';
            $status_fp = ($row_list->konfirm_fp == 1)?'<span style="color: green"><i class="fa fa-thumbs-up"></i></span>':'<span style="color: red"><i class="fa fa-thumbs-down"></i></span>';

            $row[] = '<div class="center"><a href="#" onclick="getMenu('."'registration/Reg_klinik?update_sep=1&no_reg=".$row_list->no_registrasi."&mr=".$row_list->no_mr."'".')">'.$row_list->no_mr.'</a></div>';
            $row[] = strtoupper($row_list->nama_pasien);
            $row[] = strtoupper($row_list->no_kartu_bpjs);
            // $row[] = ($row_list->nama_perusahaan)?$row_list->nama_perusahaan:'UMUM';
            $row[] = $this->tanggal->formatDateTime($row_list->tgl_jam_masuk);
            $row[] = ucwords($row_list->nama_bagian);
            $row[] = $row_list->nama_pegawai;
            $row[] = '<div class="center"><input type="text" name="no_sep_'.$row_list->no_registrasi.'" id="no_sep_'.$row_list->no_registrasi.'" value="'.$row_list->no_sep.'" style="border: 1px solid white !important" onchange="saveRow('.$row_list->no_registrasi.')"></div>';
            $row[] = '<div class="center">'.$status_fp.'</div>';
           
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Riwayat_reg_sep_fail->count_all(),
                        "recordsFiltered" => $this->Riwayat_reg_sep_fail->count_filtered(),
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

    public function updateNoSEP(){
        $this->db->where('no_registrasi', $_POST['ID'])->update('tc_registrasi', array('no_sep' => $_POST['no_sep']));
        echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan'));
    }


}

/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
