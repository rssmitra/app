<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Req_riwayat_permintaan_pemb extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'purchasing/permintaan/Req_riwayat_permintaan_pemb');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('purchasing/permintaan/Req_riwayat_permintaan_pemb_model', 'Req_riwayat_permintaan_pemb');
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
        $this->load->view('permintaan/Req_riwayat_permintaan_pemb/index_2', $data);
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
        $this->load->view('permintaan/Req_riwayat_permintaan_pemb/index', $data);
    }

    public function form($id='')
    {
        $data['string'] = isset($_GET['flag'])?$_GET['flag']:'';
        /*if id is not null then will show form edit*/
        if( $id != '' ){
            /*breadcrumbs for edit*/
            $this->breadcrumbs->push('Edit '.strtolower($this->title).'', 'Req_riwayat_permintaan_pemb/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
            /*get value by id*/
            $data['value'] = $this->Req_riwayat_permintaan_pemb->get_by_id($id); //echo '<pre>'; print_r($data);die;
            /*initialize flag for form*/
            $data['flag'] = "update";
        }else{
            /*breadcrumbs for create or add row*/
            $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Req_riwayat_permintaan_pemb/'.strtolower(get_class($this)).'/form');
            /*initialize flag for form add*/
            $data['flag'] = "create";
        }
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('permintaan/Req_riwayat_permintaan_pemb/form', $data);
    }
    /*function for view data only*/
    public function show($id)
    {
        $data['string'] = isset($_GET['flag'])?$_GET['flag']:'';
        /*breadcrumbs for view*/
        $this->breadcrumbs->push('View '.strtolower($this->title).'', 'Req_riwayat_permintaan_pemb/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*define data variabel*/
        //$data['value'] = $this->Req_riwayat_permintaan_pemb->get_by_id($id);
        $data['title'] = $this->title;
        $data['flag'] = "read";
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('permintaan/Req_riwayat_permintaan_pemb/form', $data);
    }


    public function get_data()
    {
        /*get data from model*/
        $list = $this->Req_riwayat_permintaan_pemb->get_datatables();
        
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center">
                        <label class="pos-rel">
                            <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->id_tc_permohonan.'"/>
                            <span class="lbl"></span>
                        </label>
                      </div>';
            $row[] = '';
            $row[] = $row_list->id_tc_permohonan;
            $row[] = '<div class="center"><div class="btn-group">
                        <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">
                            <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-inverse">
                            <li>'.$this->authuser->show_button('purchasing/permintaan/Req_riwayat_permintaan_pemb?flag='.$_GET['flag'].'','R',$row_list->id_tc_permohonan,67).'</li>
                            <li>'.$this->authuser->show_button('purchasing/permintaan/Req_riwayat_permintaan_pemb?flag='.$_GET['flag'].'','U',$row_list->id_tc_permohonan,67).'</li>
                            <li>'.$this->authuser->show_button('purchasing/permintaan/Req_riwayat_permintaan_pemb?flag='.$_GET['flag'].'','D',$row_list->id_tc_permohonan,6).'</li>
                        </ul>
                    </div></div>';
            $row[] = '<div class="center">'.$row_list->id_tc_permohonan.'</div>';
            $row[] = $row_list->kode_permohonan;
            $row[] = $this->tanggal->formatDate($row_list->tgl_permohonan);
            $row[] = '<div class="center">'.ucwords($row_list->username).'</div>';
            $row[] = '<div class="left">'.$row_list->no_acc.'</div>';
            $row[] = $this->tanggal->formatDate($row_list->tgl_acc);

            if ($row_list->status_batal=="0") {
                $status = '<div class="center"><i class="fa fa-check-circle bigger-150 green"></i></div>';
                $text = 'Disetujui';
            } elseif ($row_list->status_batal=="1") {
                $status = '<div class="center"><i class="fa fa-times-circle bigger-150 red"></i></div>';
                $text = 'Tidak disetujui';
            } else {
                $status = '<div class="center"><i class="fa fa-exclamation-triangle bigger-150 orange"></i></div>';
                $text = 'Menunggu Persetujuan';
            }

            $row[] = '<div class="center">'.$status.'</div>';
            $row[] = $text.' '.ucfirst($row_list->user_acc_name);
                   
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Req_riwayat_permintaan_pemb->count_all(),
                        "recordsFiltered" => $this->Req_riwayat_permintaan_pemb->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function get_detail($flag, $id){
        $result = $this->Req_riwayat_permintaan_pemb->get_detail_brg_permintaan($flag, $id);
        $data = array(
            'dt_detail_brg' => $result,
            'flag' => $flag,
            );
        $temp_view = $this->load->view('permintaan/Req_riwayat_permintaan_pemb/detail_table_view', $data, true);
        echo json_encode( array('html' => $temp_view) );
    }



}


/* End of file example.php */
/* Location: ./application/modules/example/controllers/example.php */
