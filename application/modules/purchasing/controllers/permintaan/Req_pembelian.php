<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Req_pembelian extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'purchasing/permintaan/Req_pembelian');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('purchasing/permintaan/Req_pembelian_model', 'Req_pembelian');
        /*enable profiler*/
        $this->output->enable_profiler(false);
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'Title';
    }

    public function index() { 
        // echo '<pre>';print_r($this->session->all_userdata());
        /*define variable data*/
        $data = array(
            'title' => 'Permintaan Pembelian',
            'breadcrumbs' => $this->breadcrumbs->show(),
            'flag' => $_GET['flag']
        );
        /*load view index*/
        $this->load->view('permintaan/Req_pembelian/index', $data);
    }

    public function form($id='')
    {
        $data['string'] = isset($_GET['flag'])?$_GET['flag']:'';
        /*if id is not null then will show form edit*/
        if( $id != '' ){
            /*breadcrumbs for edit*/
            $this->breadcrumbs->push('Edit '.strtolower($this->title).'', 'Req_pembelian/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
            /*get value by id*/
            $data['value'] = $this->Req_pembelian->get_by_id($id);
            // print_r($data);die;
            $data['total_brg'] = $this->Req_pembelian->get_detail_brg_permintaan($_GET['flag'], $id); 
            /*initialize flag for form*/
            $data['flag'] = "update";
        }else{
            /*breadcrumbs for create or add row*/
            $data['kode_permohonan'] = $this->master->format_kode_permohonan($_GET['flag']);
            $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Req_pembelian/'.strtolower(get_class($this)).'/form');
            /*initialize flag for form add*/
            $data['flag'] = "create";
        }
        /*title header*/
        $title = ($_GET['flag']=='medis') ? 'Medis' : 'Non Medis' ;

        $data['title'] = 'Permintaan Pembelian Barang '.$title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('permintaan/Req_pembelian/form', $data);
    }
    /*function for view data only*/
    public function show($id)
    {
        $data['string'] = isset($_GET['flag'])?$_GET['flag']:'';
        /*breadcrumbs for view*/
        $this->breadcrumbs->push('View '.strtolower($this->title).'', 'Req_pembelian/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*define data variabel*/
        //$data['value'] = $this->Req_pembelian->get_by_id($id);
        $data['title'] = $this->title;
        $data['flag'] = "read";
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('permintaan/Req_pembelian/form', $data);
    }

    public function find_data()
    {   
        $output = array( "data" => http_build_query($_POST) . "\n" );
        echo json_encode($output);
    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->Req_pembelian->get_datatables();
        
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $flag = ($_GET['flag']=='medis')?'m':'nm';
            
            if ( $row_list->status_batal == "1" ) {
                    $status = '<div class="center"><i class="fa fa-times-circle bigger-150 red"></i></div>';
                    $text = 'Tidak disetujui';
            }else{

                if( $row_list->tgl_acc == NULL ){

                    if ( $row_list->status_kirim == NULL ) {
                        $status = '<div class="center"><i class="fa fa-times-circle bigger-150 red"></i></div>';
                        $text = '<a href="#" target="_blank" class="btn btn-xs btn-white btn-warning" onclick="proses_persetujuan('.$row_list->id_tc_permohonan.')">Kirim Pengadaan</a>';
                    }else{
                        if($row_list->tgl_pemeriksa == NULL){
                            $status = '<div class="left"><i class="fa fa-exclamation-triangle bigger-150 orange"></i></div>';
                            $text = 'Menunggu Persetujuan<br>'.$this->master->get_ttd_data('verifikator_'.$flag.'_1','value');
                        }
            
                        if($row_list->tgl_pemeriksa != NULL AND $row_list->tgl_penyetuju == NULL){
                            $status = '<div class="left"><i class="fa fa-exclamation-triangle bigger-150 orange"></i></div>';
                            $text = 'Menunggu Persetujuan<br>'.$this->master->get_ttd_data('verifikator_'.$flag.'_2','value');
                        }
                    }
                    
                }else{
                    if ( $row_list->status_kirim == NULL ) {
                        $status = '<div class="center"><i class="fa fa-times-circle bigger-150 red"></i></div>';
                        $text = 'Persetujuan';
                    }else{
                        $status = '<div class="center"><i class="fa fa-check-circle bigger-150 green"></i></div>';
                        $text = 'Disetujui';
                    }
                }
                
            }


            

            $row[] = '<div class="center">
                        <label class="pos-rel">
                            <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->id_tc_permohonan.'"/>
                            <span class="lbl"></span>
                        </label>
                      </div>';
            $row[] = '';
            $row[] = $row_list->id_tc_permohonan;
            if( $row_list->status_kirim != 1 ){
                $row[] = '<div class="center">
                            <div class="btn-group">
                            <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">
                                <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-inverse">
                                <li>'.$this->authuser->show_button('purchasing/permintaan/Req_pembelian?flag='.$_GET['flag'].'','R',$row_list->id_tc_permohonan,67).'</li>
                                <li>'.$this->authuser->show_button('purchasing/permintaan/Req_pembelian?flag='.$_GET['flag'].'','U',$row_list->id_tc_permohonan,67).'</li>
                                <li>'.$this->authuser->show_button('purchasing/permintaan/Req_pembelian?flag='.$_GET['flag'].'','D',$row_list->id_tc_permohonan,6).'</li>
                            </ul>
                            </div>
                        </div>';
            }else{
                $row[] = '<div class="center"><a href="#" onclick="PopupCenter('."'".base_url().'purchasing/permintaan/Req_pembelian/print_preview/'.$row_list->id_tc_permohonan.'?flag='.$_GET['flag']."'".', '."'PERMINTAAN PEMBELIAN'".', 1000, 550)" ><i class="fa fa-print bigger-150 inverse"></a></div>';
            }
            
            $row[] = '<div class="center">'.$row_list->id_tc_permohonan.'</div>';
            $row[] = $row_list->kode_permohonan;
            $row[] = $this->tanggal->formatDate($row_list->tgl_permohonan);
            // log
            $log = json_decode($row_list->created_by);
            $petugas = isset($log->fullname)?$log->fullname:$row_list->username;
            $row[] = '<div class="center">'.ucwords($petugas).'</div>';
            $row[] = '<div class="left">'.$row_list->no_acc.'<br>'.$this->tanggal->formatDate($row_list->tgl_acc).'</div>';
            $row[] = '<div class="center">'.$row_list->total_brg.'</div>';
            
            $row[] = '<div class="center">'.$status.'</div>';
            $row[] = '<div class="left">'.$text.'</div>';
                   
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Req_pembelian->count_all(),
                        "recordsFiltered" => $this->Req_pembelian->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function get_detail($id){
        $result = $this->Req_pembelian->get_detail_brg_permintaan($_GET['flag'], $id);
        $data = array(
            'dt_detail_brg' => $result,
            'flag' => $_GET['flag'],
            );
        // echo '<pre>'; print_r($data);
        $temp_view = $this->load->view('permintaan/Req_pembelian/detail_table_view', $data, true);
        echo json_encode( array('html' => $temp_view) );
    }

    public function get_detail_permintaan_brg($id){
        $result = $this->Req_pembelian->get_detail_brg_permintaan($_GET['flag'], $id);
        $data = array(
            'dt_detail_brg' => $result,
            'flag' => $_GET['flag'],
            );
        // echo '<pre>'; print_r($this->db->last_query());
        $this->load->view('permintaan/Req_pembelian/detail_permintaan_brg', $data);
    }

    public function print_preview($id){
        $result = $this->Req_pembelian->get_detail_brg_permintaan($_GET['flag'], $id);
        $table = ($_GET['flag']=='non_medis')?'tc_permohonan_nm':'tc_permohonan';
        $title = ($_GET['flag']=='non_medis')?'Gudang Non Medis':'Gudang Medis';
        $subtitle = str_replace('_',' ',$_GET['flag']);
        $data = array(
            'dt_detail_brg' => $result,
            'permohonan' => $this->db->get_where($table, array('id_tc_permohonan' => $id))->row(),
            'flag' => $_GET['flag'],
            'title' => $title,
            'subtitle' => $subtitle,
            );
        // echo '<pre>'; print_r($data);
        $this->load->view('permintaan/Req_pembelian/print_preview', $data);
    }

    public function print_multiple_preview(){

        $result = $this->Req_pembelian->get_detail_brg_permintaan_multiple($_GET['flag'], $_GET['id']);

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
        $this->load->view('permintaan/Req_pembelian/print_preview_multiple', $data);
    }

    public function process()
    {
        // print_r($_POST);die;
        $this->load->library('form_validation');
        $val = $this->form_validation;
        if( $_POST['submit'] == 'header' ){
            $val->set_rules('tgl_permohonan', 'Tanggal Permintaan', 'trim|required');
            $val->set_rules('kode_permohonan', 'Kode Permohonan', 'trim|required');
            $val->set_rules('flag_jenis', 'Jenis Permintaan', 'trim|required');
            $val->set_rules('ket_acc', 'Keterangan', 'trim');
        }
        
        $val->set_message('required', "Silahkan isi field \"%s\"");

        if ($val->run() == FALSE)
        {
            $val->set_error_delimiters('<div style="color:white">', '</div>');
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            $this->db->trans_begin();
            $id = ($this->input->post('id'))?$this->regex->_genRegex($this->input->post('id'),'RGXINT'):0;

            if( $_POST['submit'] == 'header' ){
                
                $table = ($_POST['flag']=='medis')?'tc_permohonan':'tc_permohonan_nm';
                $dataexc = array(
                    'kode_permohonan' => $this->regex->_genRegex($this->master->format_kode_permohonan($_GET['flag']),'RGXQSL'),
                    'tgl_permohonan' => $this->regex->_genRegex($val->set_value('tgl_permohonan').' '.date('H:i:s'),'RGXQSL'),
                    'flag_jenis' => $this->regex->_genRegex($val->set_value('flag_jenis'),'RGXQSL'),
                    'keterangan_permohonan' => $this->regex->_genRegex($val->set_value('ket_acc'),'RGXQSL'),
                );
                
                if($id==0){
                    $dataexc['created_date'] = date('Y-m-d H:i:s');
                    $dataexc['created_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                    $newId = $this->Req_pembelian->save($table, $dataexc);
                    /*save logs*/
                    $this->logs->save($table, $newId, 'insert new record on '.$this->title.' module', json_encode($dataexc),'id_tc_permohonan');
                }else{
                    $dataexc['updated_date'] = date('Y-m-d H:i:s');
                    $dataexc['updated_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                    /*print_r($dataexc);die;*/
                    /*update record*/
                    $this->Req_pembelian->update($table, array('id_tc_permohonan' => $id), $dataexc);
                    $newId = $id;
                    $this->logs->save($table, $newId, 'update record'.$this->title.' module', json_encode($dataexc), 'id_tc_permohonan');
                }

            }
            
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'flag' => $_POST['flag'], 'id' => $newId));
            }
        }
    }

    public function delete()
    {
        $id=$this->input->post('ID')?$this->regex->_genRegex($this->input->post('ID',TRUE),'RGXQSL'):null;
        $toArray = explode(',',$id);
        if($id!=null){
            if($this->Req_pembelian->delete_by_id($toArray)){
                $table = ($_GET['flag']=='non_medis')?'tc_permohonan_nm':'tc_permohonan';
                // $this->logs->save($table, $id, 'delete record', '', 'id_tc_permohonan');
                echo json_encode(array('status' => 200, 'message' => 'Proses Hapus Data Berhasil Dilakukan'));

            }else{
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Hapus Data Gagal Dilakukan'));
            }
        }else{
            echo json_encode(array('status' => 301, 'message' => 'Tidak ada item yang dipilih'));
        }
    }

    public function process_persetujuan()
    {
        $id=$this->input->post('ID')?$this->regex->_genRegex($this->input->post('ID',TRUE),'RGXQSL'):null;
        $toArray = explode(',',$id);
        if($id!=null){
            $table = ($_GET['flag']=='non_medis')?'tc_permohonan_nm':'tc_permohonan';
            if( $this->Req_pembelian->update($table, array('id_tc_permohonan' => $id), array('status_kirim' => 1 ) ) ){
                $this->logs->save($table, $id, 'proses persetujuan', '', 'id_tc_permohonan');
                echo json_encode(array('status' => 200, 'message' => 'Proses Persetujuan Berhasil Dikirim'));

            }else{
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Persetujuan Gagal Dikirim'));
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

    public function log_brg()
    {   
        $data = array();
        $data['log'] = $this->Req_pembelian->show_log_brg();
        // echo '<pre>';print_r($data);die;
        $this->load->view('permintaan/Req_pembelian/log_brg', $data);
    }

    public function load_template()
    {   
        $data = array();
        $template = $this->db->select('temp_name')->group_by('temp_name')->get_where('tc_permohonan_temp', array('user_id' => $this->session->userdata('user')->user_id, 'flag' => $_GET['flag']))->result();
        $data['template'] = $template;
        $this->load->view('permintaan/Req_pembelian/load_template', $data);
    }

    public function load_data_from_template(){
        $data = array();
        $data['temp_data'] = $this->Req_pembelian->data_template();
        $this->load->view('permintaan/Req_pembelian/data_template_view', $data);
    }

    public function delete_template()
    {
        if($_POST['ID']!=null){
            if($this->db->delete('tc_permohonan_temp', array('id_tc_permohonan_temp' => $_POST['ID']) )){
                echo json_encode(array('status' => 200, 'message' => 'Proses Hapus Data Berhasil Dilakukan'));

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
