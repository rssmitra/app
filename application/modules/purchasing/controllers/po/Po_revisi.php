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
        $this->load->model('purchasing/po/Po_penerbitan_model', 'Po_penerbitan');
        /*enable profiler*/
        $this->output->enable_profiler(false);
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'Title';

    }

    public function view_data() { 
        //echo '<pre>';print_r($this->session->all_userdata());
        /*define variable data*/
        $title = ($_GET['flag']=='medis')?'Medis':'Non Medis';
        $data = array(
            'title' => 'Revisi PO '.$title,
            'breadcrumbs' => $this->breadcrumbs->show(),
            'flag' => $_GET['flag'],
        );
        /*load view index*/
        $this->load->view('po/Po_revisi/index', $data);
    }

    public function form($id='')
    {
        $flag = $_GET['flag'];
        /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Edit '.strtolower($this->title).'', 'Po_revisi/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);

        $format = ($flag=='medis')?'PO':'PO-NM';
        $table = ($flag=='medis')?'tc_po':'tc_po_nm';
        $title = ($flag=='medis')?'Medis':'Non Medis';
        
        $data['value'] = $this->db->get_where($table, array('id_tc_po' => $id) )->row();
        /*get value by id*/
        $format_no_po = $this->master->get_max_number_per_month($table, 'no_urut_periodik', 'tgl_po');
        // $dt_detail_brg = $this->Po_penerbitan->get_po($flag, $id);

        $data['flag'] = $flag;
        // echo '<pre>';print_r($data);die;
        // $getData = array();
        // foreach($dt_detail_brg as $row){
        //     $getData[$row->kode_brg][] = $row;
        // }
        
        // $data['dt_detail_brg'] = $getData;
        // $data['view_brg_po'] = $this->load->view('po/Po_revisi/view_brg_po', $data, true);
        /*title header*/
        $data['title'] = 'Revisi PO '.$title;
         /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();

        $this->load->view('po/Po_revisi/form', $data, false);

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

    public function find_data()
    {   
        $output = array( "data" => http_build_query($_POST) . "\n" );
        echo json_encode($output);
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
            $row[] = '<div class="left">'.$row_list->jenis_po.'</div>';
            $row[] = '<div class="left">'.$row_list->namasupplier.'</div>';
            $row[] = '<div class="left">'.$row_list->diajukan_oleh.'</div>';
            $row[] = '<div class="left">'.$row_list->disetujui_oleh.'</div>';
            $row[] = '<div class="pull-right">'.number_format($row_list->total_stl_ppn, 2).',-</div>';
            
            $row[] = '<div class="center">
                        <a href="#" onclick="PopupCenter('."'purchasing/po/Po_penerbitan/print_preview?ID=".$row_list->id_tc_po."&flag=".$_GET['flag']."'".','."'Cetak'".',900,650);" class="btn btn-xs btn-yellow" title="CETAK PO"><i class="fa fa-print dark"></i></a>
                        <a href="#" onclick="PopupCenter('."'purchasing/po/Po_penerbitan/usulan_preview?ID=".$row_list->id_tc_po."&flag=".$_GET['flag']."'".','."'Cetak'".',900,650);" class="btn btn-xs btn-yellow dark" title="CETAK USULAN PO"><i class="fa fa-file dark"></i></a>
                      </div>';  
            $row[] = '<div class="center">
            <a href="#" onclick="getMenu('."'purchasing/po/Po_revisi/form/".$row_list->id_tc_po."?flag=".$_GET['flag']."'".')" class="btn btn-xs btn-primary" title="REVISI PO"><i class="fa fa-pencil"></i></a>
            <a href="#" onclick="rollback('.$row_list->id_tc_po.')" class="btn btn-xs btn-success" title="ROLLBACK"><i class="fa fa-refresh"></i></a>
            <a href="#" onclick="delete_po('.$row_list->id_tc_po.')" class="btn btn-xs btn-danger" title="HAPUS PO GANDA"><i class="fa fa-trash"></i></a>
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

    public function get_detail($id){
        $flag = $_GET['flag'];
        $result = $this->Po_penerbitan->get_po($flag, $id);
        $getData = array();
        foreach($result as $row_dt){
            $getData[$row_dt->kode_brg][] = $row_dt;
        }
        // echo '<pre>';print_r($getData);
        $data = array(
            'po' => $result[0],
            'po_data' => $getData,
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
        //echo '<pre>';print_r($data);die;
        $this->load->view('po/Po_revisi/form', $data, false);
    }

    public function view_brg_po($flag, $id){
        
        $dt_detail_brg = $this->Po_revisi->get_po($flag, $id);

        $data['flag'] = $flag;
        
        $getData = array();
        foreach($dt_detail_brg as $row){
            $getData[$row->kode_brg][] = $row;
        }
        // echo '<pre>';print_r($getData);die;
        $data['dt_detail_brg'] = $getData;

        $this->load->view('po/Po_revisi/view_brg_po', $data, false);
    }

    public function rollback()
    {
        if($_POST['ID']!=null){
            if($this->Po_revisi->rollback_po($_POST['flag'], $_POST['ID'])){
                echo json_encode(array('status' => 200, 'message' => 'Proses Rollback Data Berhasil Dilakukan'));
            }else{
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Rollback Data Gagal Dilakukan'));
            }
        }else{
            echo json_encode(array('status' => 301, 'message' => 'Tidak ada item yang dipilih'));
        }
    }

    public function delete()
    {
        if($_POST['ID']!=null){
            if($this->Po_revisi->delete_po($_POST['flag'], $_POST['ID'])){
                echo json_encode(array('status' => 200, 'message' => 'Proses Rollback Data Berhasil Dilakukan'));
            }else{
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Rollback Data Gagal Dilakukan'));
            }
        }else{
            echo json_encode(array('status' => 301, 'message' => 'Tidak ada item yang dipilih'));
        }
    }

    public function takeOutBrg()
    {

        // echo json_encode(print_r($_POST)); exit;

        if( $_POST['kode_brg'] != null ){
            $config = array('kode_brg' => $_POST['kode_brg'], 'flag' => $_POST['flag'], 'id_tc_po' => $_POST['id_tc_po']);
            if( $this->Po_revisi->takeOutBrg( $config ) ){
                echo json_encode(array('status' => 200, 'message' => 'Proses Data Berhasil Dilakukan'));
            }else{
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Data Gagal Dilakukan'));
            }
        }else{
            echo json_encode(array('status' => 301, 'message' => 'Tidak ada item yang dipilih'));
        }
    }

    public function print_multiple()
    {   
        $toArray['id'] = explode(',', $_POST['ID']);
        $toArray['flag'] = str_replace('flag=','',$_POST['flag']);
        //print_r($toArray);die;
        $output = array( "queryString" => http_build_query($toArray) . "\n" );
        echo json_encode( $output );
    }

    public function print_multiple_preview(){

        $result = $this->Po_revisi->get_detail_brg_po_multiple($_GET['flag'], $_GET['id']);

        $table = ($_GET['flag']=='non_medis')?'tc_permohonan_nm':'tc_permohonan';
        $title = ($_GET['flag']=='non_medis')?'Gudang Non Medis':'Gudang Medis';
        $subtitle = str_replace('_',' ',$_GET['flag']);
        $data = array(
            'permohonan' => $result,
            'flag' => $_GET['flag'],
            'title' => $title,
            'subtitle' => $subtitle,
            );
        // echo '<pre>'; print_r($data);
        $this->load->view('po/Po_revisi/print_preview_multiple', $data);
    }

}


/* End of file example.php */
/* Location: ./application/modules/example/controllers/example.php */
