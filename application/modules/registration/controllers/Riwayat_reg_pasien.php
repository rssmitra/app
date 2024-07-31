<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Riwayat_reg_pasien extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'registration/Riwayat_reg_pasien');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('Riwayat_reg_pasien_model', 'Riwayat_reg_pasien');
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
        $this->load->view('Riwayat_reg_pasien/index', $data);
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
        $list = $this->Riwayat_reg_pasien->get_datatables();
        $data = array();
        $resume = array();
        $rekap_batal = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center">'.$no.'</div>';
            $row[] = '<div class="center"><a href="#" onclick="show_modal('."'registration/reg_pasien/view_detail_resume_medis/".$row_list->no_registrasi."'".', '."'RESUME MEDIS PASIEN'".')" style="font-weight: bold; color: blue">'.$row_list->no_registrasi.'</a></div>';
            $row[] = '<div class="center">'.$row_list->no_mr.'</div>';
            $row[] = strtoupper($row_list->nama_pasien);
            $row[] = ($row_list->nama_perusahaan)?$row_list->nama_perusahaan:'UMUM';
            $row[] = $this->tanggal->formatDateTime($row_list->tgl_jam_masuk);
            $row[] = ucwords($row_list->nama_bagian);
            $row[] = $row_list->nama_pegawai;
            $row[] = '<div class="center">'.$row_list->no_sep.'</div>';
            $data[] = $row;
            $resume[$row_list->nama_bagian][] = $row;
            $rekap_dr[$row_list->nama_pegawai][] = $row;
            // substring
            $substring = substr($row_list->kode_bagian_masuk, 0,2);
            $substr[$substring][] = $row;
            if($row_list->status_batal == 1){
                $rekap_batal[] = $row;
            }
        }
        
        // rekap berdasarkan unit
        foreach($resume as $key=>$val){
            $total_unit[] = ['unit' => $key, 'total' => count($resume[$key])];
        }

        // rekap berdasarkan dokter
        foreach($rekap_dr as $key=>$val){
            $nama_dr = (!empty($key))?$key:'<span class="red bold">-tidak ada dokter-</spam>';
            $total_dr[] = ['nama_dr' => $nama_dr, 'total' => count($rekap_dr[$key])];
        }
        // rekap berdasarkan instalasi
        foreach($substr as $key=>$val){
            if($key == '01'){
                $total_kunjungan[] = ['unit' => 'POLIKLINIK', 'total' => count($substr[$key])];
            }
            if($key == '05'){
                $total_kunjungan[] = ['unit' => 'PENUNJANG MEDIS', 'total' => count($substr[$key])];
            }
            if($key == '02'){
                $total_kunjungan[] = ['unit' => 'IGD', 'total' => count($substr[$key])];
            }
            if($key == '03'){
                $total_kunjungan[] = ['unit' => 'RAWAT INAP', 'total' => count($substr[$key])];
            }
        }
        // echo "<pre>"; print_r($total_dr);die;

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Riwayat_reg_pasien->count_all(),
                        "recordsFiltered" => $this->Riwayat_reg_pasien->count_filtered(),
                        "data" => $data,
                        "resume" => $total_unit,
                        "rekap" => $total_kunjungan,
                        "rekap_dr" => $total_dr,
                        "rekap_batal" => count($rekap_batal),
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
