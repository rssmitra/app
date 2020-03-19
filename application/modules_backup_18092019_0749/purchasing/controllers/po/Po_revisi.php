<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Po_revisi extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'purchasing/po/Po_revisi');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('purchasing/po/Po_revisi_model', 'Po_revisi');
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
        $this->load->view('po/Po_revisi/index_2', $data);
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
        $this->load->view('po/Po_revisi/index', $data);
    }

    public function form($id='')
    {
        $data['string'] = isset($_GET['flag'])?$_GET['flag']:'';
        /*if id is not null then will show form edit*/
        if( $id != '' ){
            /*breadcrumbs for edit*/
            $this->breadcrumbs->push('Edit '.strtolower($this->title).'', 'Po_revisi/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
            /*get value by id*/
            $data['value'] = $this->Po_revisi->get_by_id($id); //echo '<pre>'; print_r($data);die;
            /*initialize flag for form*/
            $data['flag'] = "update";
        }else{
            /*breadcrumbs for create or add row*/
            $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Po_revisi/'.strtolower(get_class($this)).'/form');
            /*initialize flag for form add*/
            $data['flag'] = "create";
        }
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('po/Po_revisi/form', $data);
    }
    /*function for view data only*/
    public function show($id)
    {
        $data['string'] = isset($_GET['flag'])?$_GET['flag']:'';
        /*breadcrumbs for view*/
        $this->breadcrumbs->push('View '.strtolower($this->title).'', 'Po_revisi/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*define data variabel*/
        //$data['value'] = $this->Po_revisi->get_by_id($id);
        $data['title'] = $this->title;
        $data['flag'] = "read";
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('po/Po_revisi/form', $data);
    }


    public function get_data()
    {
        /*get data from model*/
        $list = $this->Po_revisi->get_datatables();
        
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center">
                        <label class="pos-rel">
                            <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->id_tc_po.'"/>
                            <span class="lbl"></span>
                        </label>
                      </div>';
            $row[] = '';
            $row[] = $row_list->id_tc_po;
            $row[] = '<div class="center">'.$row_list->id_tc_po.'</div>';
            $row[] = $row_list->no_po;
            $row[] = $this->tanggal->formatDate($row_list->tgl_po);
            $row[] = '<div class="left">'.$row_list->namasupplier.'</div>';
            $row[] = '<div class="left">'.$row_list->diajukan_oleh.'</div>';
            $row[] = '<div class="left">'.$row_list->disetujui_oleh.'</div>';
            $row[] = '<div class="pull-right">'.number_format((int)$row_list->total_stl_ppn).',-</div>';
            $row[] = '<div class="center">
                        <a href="#" onclick="getMenuTabs('."'purchasing/po/Po_revisi/revisi_form/".$_GET['flag']."/".$row_list->id_tc_po."'".','."'tabs_form_po'".')" class="btn btn-xs btn-primary" title="revisi po"><i class="fa fa-pencil"></i></a>
                        <a href="#" class="btn btn-xs btn-success" title="cetak po"><i class="fa fa-print"></i></a>
                      </div>';
                  
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Po_revisi->count_all(),
                        "recordsFiltered" => $this->Po_revisi->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function get_detail($flag, $id){
        $result = $this->Po_revisi->get_brg_po($flag, $id);
        $data = array(
            'dt_detail_brg' => $result,
            'flag' => $flag,
            'id' => $id,
            );
        $temp_view = $this->load->view('po/Po_revisi/detail_table_view', $data, true);
        echo json_encode( array('html' => $temp_view) );
    }

    public function get_detail_brg_po(){
        /*string to array*/
        $arr_id = explode(',', $_POST['ID']);
        $url_qry = http_build_query($arr_id);
        echo json_encode(array('params' => $url_qry));
    }

    public function revisi_form($flag, $id){
        
        /*title header*/
        $data['title'] = 'Revisi PO';
        /*get value by id*/
        $data['dt_detail_brg'] = $this->Po_revisi->get_brg_po($flag, $id);
        $data['flag'] = $flag;
        $data['view_brg_po'] = $this->load->view('po/Po_revisi/view_brg_po', $data, true);;
        //echo '<pre>';print_r($data);die;
        $this->load->view('po/Po_revisi/form', $data, false);
    }



}


/* End of file example.php */
/* Location: ./application/modules/example/controllers/example.php */
