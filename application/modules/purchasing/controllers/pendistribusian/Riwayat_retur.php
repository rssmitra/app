<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Riwayat_retur extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'purchasing/pendistribusian/Riwayat_retur');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('purchasing/pendistribusian/Riwayat_retur_model', 'Riwayat_retur');
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
            'flag' => $_GET['flag'],
        );
        /*load view index*/
        $this->load->view('pendistribusian/Riwayat_retur/index', $data);
    }

    
    public function get_data()
    {
        /*get data from model*/
        $list = $this->Riwayat_retur->get_datatables();
        
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center">
                        <label class="pos-rel">
                            <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->id_tc_retur_unit.'"/>
                            <span class="lbl"></span>
                        </label>
                      </div>';
            $row[] = '';
            $row[] = $row_list->id_tc_retur_unit;
            $row[] = '<div class="center"><a href="#" onclick="PopupCenter('."'".base_url().'purchasing/pendistribusian/Riwayat_retur/print_preview/'.$row_list->id_tc_retur_unit.'?flag='.$_GET['flag']."'".', '."'PERMINTAAN PEMBELIAN'".', 1000, 550)" ><i class="fa fa-print bigger-150 inverse"></a></div>';
            $row[] = '<div class="center">'.$row_list->id_tc_retur_unit.'</div>';
            $row[] = $row_list->kode_retur;
            $row[] = $this->tanggal->formatDate($row_list->tgl_retur);
            $row[] = '<div class="left">'.ucwords($row_list->bagian_minta).'</div>';
            $row[] = '<div class="left">'.ucfirst($row_list->petugas_unit).'</div>';
            $row[] = '<div class="left">'.ucfirst($row_list->petugas_gudang).'</div>';
            $row[] = '<div class="left">'.ucfirst($row_list->catatan).'</div>';
                  
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Riwayat_retur->count_all(),
                        "recordsFiltered" => $this->Riwayat_retur->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function get_detail($flag, $id){
        $result = $this->Riwayat_retur->get_brg_retur($flag, $id);
        // echo "<pre>";print_r($result);die;

        $data = array(
            'dt_detail_brg' => $result,
            'flag' => $flag,
            'id' => $id,
            );
        $temp_view = $this->load->view('pendistribusian/Riwayat_retur/detail_table_view', $data, true);
        echo json_encode( array('html' => $temp_view) );
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

        $result = $this->Riwayat_retur->get_detail_brg_permintaan_multiple($_GET['flag'], $_GET['id']);

        $table = ($_GET['flag']=='non_medis')?'tc_permintaan_inst_nm':'tc_permintaan_inst';
        $title = ($_GET['flag']=='non_medis')?'Gudang Non Medis':'Gudang Medis';
        $subtitle = str_replace('_',' ',$_GET['flag']);
        $data = array(
            'permintaan' => $result,
            'flag' => $_GET['flag'],
            'title' => $title,
            'subtitle' => $subtitle,
            );
        // echo '<pre>'; print_r($data);
        $this->load->view('pendistribusian/Riwayat_retur/print_preview_multiple', $data);
    }

    public function print_preview($id){
        $result = $this->Riwayat_retur->get_brg_retur($_GET['flag'], $id);
        $table = 'tc_retur_unit';
        $title = ($_GET['flag']=='non_medis')?'Gudang Non Medis':'Gudang Medis';
        $subtitle = str_replace('_',' ',$_GET['flag']);
        $data = array(
            'dt_detail_brg' => $result,
            'flag' => $_GET['flag'],
            'title' => $title,
            'subtitle' => $subtitle,
            );
        // echo '<pre>'; print_r($data);
        $this->load->view('pendistribusian/Riwayat_retur/print_preview', $data);
    }

    public function print_preview_retur($id){
        $result = $this->Riwayat_retur->get_brg_retur($_GET['flag'], $id);
        // echo '<pre>'; print_r($result);die;
        $title = ($_GET['flag']=='non_medis')?'Gudang Non Medis':'Gudang Medis';
        $subtitle = str_replace('_',' ',$_GET['flag']);
        $data = array(
            'dt_detail_brg' => $result,
            'retur' => $result[0],
            'flag' => $_GET['flag'],
            'title' => $title,
            'subtitle' => $subtitle,
            );
        // echo '<pre>'; print_r($data);
        $this->load->view('pendistribusian/Riwayat_retur/print_preview_retur', $data);
    }

    public function find_data()
    {   
        $output = array( "data" => http_build_query($_POST) . "\n" );
        echo json_encode($output);
    }

    public function get_detail_permintaan_brg($id){
        $result = $this->Riwayat_retur->get_brg_permintaan($_GET['flag'], $id);
        $data = array(
            'dt_detail_brg' => $result,
            'flag' => $_GET['flag'],
            'id_tc_retur_unit' => $id,
            );
        // echo '<pre>'; print_r($data);
        $this->load->view('pendistribusian/Riwayat_retur/detail_permintaan_brg', $data);
    }

    public function delete()
    {
        $id=$this->input->post('ID')?$this->regex->_genRegex($this->input->post('ID',TRUE),'RGXQSL'):null;
        $toArray = explode(',',$id);
        if($id!=null){
            $table = ($_GET['flag']=='non_medis')?'tc_permintaan_inst_nm':'tc_permintaan_inst';
            if($this->Riwayat_retur->delete_by_id($table, $toArray)){
                $this->logs->save($table, $id, 'delete record', '', 'id_tc_retur_unit');
                echo json_encode(array('status' => 200, 'message' => 'Proses Hapus Data Berhasil Dilakukan'));

            }else{
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Hapus Data Gagal Dilakukan'));
            }
        }else{
            echo json_encode(array('status' => 301, 'message' => 'Tidak ada item yang dipilih'));
        }
    }

    public function delete_row_brg()
    {
        $id=$this->input->post('ID')?$this->regex->_genRegex($this->input->post('ID',TRUE),'RGXQSL'):null;
        $table = ($_POST['flag']=='medis')?'tc_permintaan_inst_det':'tc_permintaan_inst_nm_det';
        if($id!=null){
            if($this->Riwayat_retur->delete_brg_permintaan($table, $id)){
                $this->logs->save($table, $id, 'delete record', '', 'id_tc_retur_unit_det');
                $ttl = $this->Riwayat_retur->get_brg_permintaan($_POST['flag'], $_POST['id_tc_retur_unit']);
                echo json_encode(array('status' => 200, 'message' => 'Proses Hapus Data Berhasil Dilakukan', 'total_brg' => count($ttl) ));

            }else{
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Hapus Data Gagal Dilakukan'));
            }
        }else{
            echo json_encode(array('status' => 301, 'message' => 'Tidak ada item yang dipilih'));
        }
        
    }



}


/* End of file example.php */
/* Location: ./application/modules/example/controllers/example.php */
