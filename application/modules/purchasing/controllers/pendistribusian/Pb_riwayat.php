<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pb_riwayat extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'purchasing/penerimaan_brg/Pb_riwayat');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('purchasing/penerimaan_brg/Pb_riwayat_model', 'Pb_riwayat');
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
        $this->load->view('penerimaan_brg/Pb_riwayat/index_2', $data);
    }

    public function view_data() { 
        //echo '<pre>';print_r($this->session->all_userdata());
        /*define variable data*/
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show(),
            'flag' => $_GET['flag'],
        );
        /*load view index*/
        $this->load->view('penerimaan_brg/Pb_riwayat/index', $data);
    }

    public function form($id='')
    {
        $data['string'] = isset($_GET['flag'])?$_GET['flag']:'';
        /*if id is not null then will show form edit*/
        if( $id != '' ){
            /*breadcrumbs for edit*/
            $this->breadcrumbs->push('Edit '.strtolower($this->title).'', 'Pb_riwayat/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
            /*get value by id*/
            $data['value'] = $this->Pb_riwayat->get_by_id($id); //echo '<pre>'; print_r($data);die;
            /*initialize flag for form*/
            $data['flag'] = "update";
        }else{
            /*breadcrumbs for create or add row*/
            $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Pb_riwayat/'.strtolower(get_class($this)).'/form');
            /*initialize flag for form add*/
            $data['flag'] = "create";
        }
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('penerimaan_brg/Pb_riwayat/form', $data);
    }
    /*function for view data only*/
    public function show($id)
    {
        $data['string'] = isset($_GET['flag'])?$_GET['flag']:'';
        /*breadcrumbs for view*/
        $this->breadcrumbs->push('View '.strtolower($this->title).'', 'Pb_riwayat/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*define data variabel*/
        //$data['value'] = $this->Pb_riwayat->get_by_id($id);
        $data['title'] = $this->title;
        $data['flag'] = "read";
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('penerimaan_brg/Pb_riwayat/form', $data);
    }


    public function get_data()
    {
        /*get data from model*/
        $list = $this->Pb_riwayat->get_datatables();
        
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center">
                        <label class="pos-rel">
                            <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->id_penerimaan.'"/>
                            <span class="lbl"></span>
                        </label>
                      </div>';
            $row[] = '';
            $row[] = $row_list->id_penerimaan;
            $row[] = '<div class="center">'.$row_list->id_penerimaan.'</div>';
            $row[] = $row_list->kode_penerimaan;
            $row[] = $this->tanggal->formatDate($row_list->tgl_penerimaan);
            $row[] = '<div class="left">'.$row_list->no_po.'</div>';
            $row[] = '<div class="left">'.$row_list->namasupplier.'</div>';
            $row[] = '<div class="left">'.$row_list->no_faktur.'</div>';
            $row[] = '<div class="left">'.$row_list->petugas.'</div>';
            $row[] = '<div class="left">'.$row_list->dikirim.'</div>';
                  
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Pb_riwayat->count_all(),
                        "recordsFiltered" => $this->Pb_riwayat->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function get_detail($flag, $id){
        $result = $this->Pb_riwayat->get_brg_penerimaan($flag, $id);
        $data = array(
            'dt_detail_brg' => $result,
            'flag' => $flag,
            'id' => $id,
            );
        //echo '<pre>';print_r($data);die;
        $temp_view = $this->load->view('penerimaan_brg/Pb_riwayat/detail_table_view', $data, true);
        echo json_encode( array('html' => $temp_view) );
    }

    public function get_detail_brg_po(){
        /*string to array*/
        $arr_id = explode(',', $_POST['ID']);
        $url_qry = http_build_query($arr_id);
        echo json_encode(array('params' => $url_qry));
    }

    public function terima_barang($flag, $id){
        
        /*title header*/
        $data['title'] = 'Revisi PO';
        /*get value by id*/
        $data['dt_detail_brg'] = $this->Pb_riwayat->get_brg_penerimaan($flag, $id);
        $data['flag'] = $flag;
        $data['view_brg_po'] = $this->load->view('penerimaan_brg/Pb_riwayat/view_brg_po', $data, true);;
        //echo '<pre>';print_r($data);die;
        $this->load->view('penerimaan_brg/Pb_riwayat/form', $data, false);
    }



}


/* End of file example.php */
/* Location: ./application/modules/example/controllers/example.php */
