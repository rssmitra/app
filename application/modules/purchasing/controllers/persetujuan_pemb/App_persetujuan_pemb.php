<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class App_persetujuan_pemb extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'purchasing/App_persetujuan_pemb/App_persetujuan_pemb');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('purchasing/persetujuan_pemb/App_persetujuan_pemb_model', 'App_persetujuan_pemb');
        /*enable profiler*/
        $this->output->enable_profiler(false);
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'Title';
        $this->format_no_acc_nm = '/ACC-PPNM/'.date('m/Y').'';
        $this->format_no_acc_m = '/ACC-PP/'.date('m/Y').'';
        
    }

    public function index() { 
        //echo '<pre>';print_r($this->session->all_userdata());
        /*define variable data*/
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show(),
        );
        /*load view index*/
        $this->load->view('persetujuan_pemb/App_persetujuan_pemb/index_2', $data);
    }

    public function view_data() { 
        
        $title = ($_GET['flag']=='medis')?'Medis':'Non Medis';
        /*define variable data*/
        $data = array(
            'title' => 'Persetujuan Pembelian '.$title,
            'breadcrumbs' => $this->breadcrumbs->show(),
            'flag' => $_GET['flag'],
        );
        /*load view index*/
        $this->load->view('persetujuan_pemb/App_persetujuan_pemb/index', $data);
    }

    public function form($id='')
    {
        $data['string'] = isset($_GET['flag'])?$_GET['flag']:'';
        /*if id is not null then will show form edit*/
        if( $id != '' ){
            /*breadcrumbs for edit*/
            $this->breadcrumbs->push('Edit '.strtolower($this->title).'', 'App_persetujuan_pemb/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
            /*get value by id*/
            $data['value'] = $this->App_persetujuan_pemb->get_by_id($id); //echo '<pre>'; print_r($data);die;
            /*initialize flag for form*/
            $data['flag'] = "update";
        }else{
            /*breadcrumbs for create or add row*/
            $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'App_persetujuan_pemb/'.strtolower(get_class($this)).'/form');
            /*initialize flag for form add*/
            $data['flag'] = "create";
        }
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('persetujuan_pemb/App_persetujuan_pemb/form', $data);
    }
    /*function for view data only*/
    public function show($id)
    {
        $data['string'] = isset($_GET['flag'])?$_GET['flag']:'';
        /*breadcrumbs for view*/
        $this->breadcrumbs->push('View '.strtolower($this->title).'', 'App_persetujuan_pemb/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*define data variabel*/
        //$data['value'] = $this->App_persetujuan_pemb->get_by_id($id);
        $data['title'] = $this->title;
        $data['flag'] = "read";
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('persetujuan_pemb/App_persetujuan_pemb/form', $data);
    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->App_persetujuan_pemb->get_datatables();
        
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $flag = ($_GET['flag']=='medis')?'m':'nm';
            if( $row_list->tgl_acc == NULL ){

                if ( $row_list->status_kirim == NULL ) {
                    $status = '<div class="center"><i class="fa fa-times-circle bigger-150 red"></i></div>';
                    $text = '<a href="#" target="_blank" class="btn btn-xs btn-white btn-warning" onclick="proses_persetujuan('.$row_list->id_tc_permohonan.')">Proses Persetujuan</a>';
                }else{
                    if($row_list->tgl_pemeriksa == NULL){
                        $status = '<div class="left"><i class="fa fa-exclamation-triangle bigger-150 orange"></i></div>';
                        $text = '<span class="red">Menunggu Proses<br>'.$this->master->get_ttd_data('verifikator_'.$flag.'_1','value').'</span>';
                    }
                    if($row_list->tgl_pemeriksa != NULL AND $row_list->tgl_penyetuju == NULL){
                        $status = '<div class="left"><i class="fa fa-exclamation-triangle bigger-150 orange"></i></div>';
                        $text = '<span class="green">Menunggu Persetujuan<br>'.$this->master->get_ttd_data('verifikator_'.$flag.'_2','value').'</span>';
                    }
                }
                
            }else{

                if ( $row_list->status_batal == "1" ) {
                    $status = '<div class="center"><i class="fa fa-times-circle bigger-150 red"></i></div>';
                    $text = 'Tidak disetujui';
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
                                <li>'.$this->authuser->show_button('purchasing/permintaan/App_persetujuan_pemb?flag='.$_GET['flag'].'','R',$row_list->id_tc_permohonan,67).'</li>
                                <li>'.$this->authuser->show_button('purchasing/permintaan/App_persetujuan_pemb?flag='.$_GET['flag'].'','U',$row_list->id_tc_permohonan,67).'</li>
                                <li>'.$this->authuser->show_button('purchasing/permintaan/App_persetujuan_pemb?flag='.$_GET['flag'].'','D',$row_list->id_tc_permohonan,6).'</li>
                            </ul>
                            </div>
                        </div>';
            }else{
                $row[] = '<div class="center"><a href="#" onclick="PopupCenter('."'".base_url().'purchasing/permintaan/Req_pembelian/print_preview/'.$row_list->id_tc_permohonan.'?flag='.$_GET['flag']."'".', '."'PERMINTAAN PEMBELIAN'".', 1000, 550)" ><i class="fa fa-print bigger-150 inverse"></a></div>';
            }
            
            $row[] = '<div class="center">'.$row_list->id_tc_permohonan.'</div>';
            $row[] = '<a href="#" onclick="getMenu('."'purchasing/persetujuan_pemb/App_persetujuan_pemb/get_detail_view/".$_GET['flag']."/".$row_list->id_tc_permohonan."'".')">'.$row_list->kode_permohonan.'</a>';
            $row[] = $this->tanggal->formatDate($row_list->tgl_permohonan);
            // log
            $log = json_decode($row_list->created_by);
            $petugas = isset($log->fullname)?$log->fullname:$row_list->username;
            $row[] = '<div class="center">'.ucwords($petugas).'</div>';
            $row[] = '<div class="left">'.$row_list->keterangan_permohonan.'</div>';
            $row[] = '<div class="left">'.$row_list->no_acc.'<br>'.$this->tanggal->formatDate($row_list->tgl_acc).'</div>';
            $row[] = '<div class="center">'.$row_list->total_brg.'</div>';
            
            $row[] = '<div class="center">'.$status.'</div>';
            $row[] = '<div class="left">'.$text.'</div>';
                   
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->App_persetujuan_pemb->count_all(),
                        "recordsFiltered" => $this->App_persetujuan_pemb->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    
    public function prosess_approval()
    {
        // echo '<pre>';print_r($_POST);die;
        $this->load->library('form_validation');
        $val = $this->form_validation;
        $val->set_rules('id_tc_permohonan', 'Relation ID', 'trim|required');
        $val->set_message('required', "Error \"%s\"");

        if ($val->run() == FALSE)
        {
            $val->set_error_delimiters('<div style="color:white">', '</div>');
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            $this->db->trans_begin();
            // parameter
            $table = ($_POST['flag']=='medis')?'tc_permohonan':'tc_permohonan_nm';
            $format = ($_POST['flag']=='medis')?$this->format_no_acc_m:$this->format_no_acc_nm;
            $max_num_acc = $this->App_persetujuan_pemb->get_max_number_acc($table);
            // id
            $id = ($this->input->post('id_tc_permohonan'))?$this->regex->_genRegex($this->input->post('id_tc_permohonan'),'RGXINT'):0;
            // data existing
            $data_existing = $this->db->get_where($table, array('id_tc_permohonan' => $id) )->row();
            // keterangan existing
            $txt_ket_existing = ($data_existing->ket_acc!=NULL)?$data_existing->ket_acc.'/':'';
            // post data
            $dataexc = array();
            // keterangan acc
            $status_app = ( $this->input->post('flag_approval') == 'Y' ) ? "Disetujui" : "Tidak Disetujui";
            $txt_ket_curr = $status_app.' oleh '.$_POST['approval_by'].' tanggal '.$this->tanggal->formatDateTime(date('Y-m-d H:i:s'));
            $dataexc['ket_acc'] = $txt_ket_existing.$txt_ket_curr;

            if( in_array($this->input->post('verifikator'), array('verifikator_nm_1','verifikator_m_1') ) ){
                $dataexc['tgl_pemeriksa'] = date('Y-m-d H:i:s');
                $dataexc['catatan_pemeriksa'] = $this->input->post('catatan');
                $dataexc['pemeriksa'] = $this->input->post('approval_by');
                $dataexc['status_batal'] = ( $this->input->post('flag_approval') == 'Y' ) ? "0" : "1";
            }else{
                $dataexc['tgl_acc'] = date('Y-m-d H:i:s');
                $dataexc['no_acc'] = $max_num_acc.$format;
                $dataexc['flag_proses'] = "2";
                $dataexc['status_batal'] = ( $this->input->post('flag_approval') == 'Y' ) ? "0" : "1";
                $dataexc['tgl_penyetuju'] = date('Y-m-d H:i:s');
                $dataexc['catatan_penyetuju'] = $this->input->post('catatan');
                $dataexc['penyetuju'] = $this->input->post('approval_by');
            }

            $dataexc['updated_date'] = date('Y-m-d H:i:s');
            $dataexc['updated_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
            
            /*update record*/
            $this->App_persetujuan_pemb->update($table, array('id_tc_permohonan' => $id), $dataexc);
            $newId = $id;
            $this->logs->save($table, $newId, 'update record'.$this->title.' module', json_encode($dataexc), 'id_tc_permohonan');
            
            // update detail
            $rincian_brg = $this->db->where('(status_po is null or status_po=0)')->get_where($table.'_det', array('id_tc_permohonan' => $id) )->result();
            if( in_array($this->input->post('verifikator'), array('verifikator_nm_1','verifikator_m_1')) ){
                $field_verifikator = 'jml_acc_pemeriksa';
            }else{
                $field_verifikator = 'jml_acc_penyetuju';
            }
            // print_r($rincian_brg); die;
            $keys = [];
            $rincian = [];
            foreach($rincian_brg as $key=>$row){
                $keys = array_search($row->kode_brg, $_POST['selected']);
                if((string)$keys != ''){
                    $jml_acc[$field_verifikator] = $_POST['acc_value'][$keys];
                    // $jml_acc['jumlah_besar_acc'] = $_POST['acc_value'][$keys];
                    $jml_acc['jml_besar_acc'] = $_POST['acc_value'][$keys];
                }else{
                    $jml_acc[$field_verifikator] = 0;
                    // $jml_acc['jumlah_besar_acc'] = 0;
                    $jml_acc['jml_besar_acc'] = 0;
                }
                
                $jml_acc['updated_date'] = date('Y-m-d H:i:s');
                $jml_acc['updated_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                // print_r($jml_acc);die;
                $this->App_persetujuan_pemb->update($table.'_det', array('id_tc_permohonan' => $id, 'kode_brg' => $_POST['selected'][$keys]), $jml_acc);
                $newId = $id;
                $this->logs->save($table.'_det', $row->id_tc_permohonan_det, 'update record', json_encode($jml_acc), 'id_tc_permohonan_det');
                // print_r($jml_acc);die;
            }
            
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan'));
            }
        }
    }

    public function get_detail($id){
        $flag = $_GET['flag'];
        $result = $this->App_persetujuan_pemb->get_detail_brg_permintaan($flag, $id);
        $data = array(
            'dt_detail_brg' => $result,
            'flag' => $flag,
            'id' => $id,
            'format_no_acc' => ($flag=='medis')?$this->format_no_acc_m:$this->format_no_acc_nm,
            );
        // echo '<pre>';print_r($data);die;
        $temp_view = $this->load->view('persetujuan_pemb/App_persetujuan_pemb/detail_table_view', $data, true);
        echo json_encode( array('html' => $temp_view) );
    }

    public function get_detail_view($flag, $id){
        $title = ($flag=='medis')?'Medis':'Non Medis';
        $result = $this->App_persetujuan_pemb->get_detail_brg_permintaan($flag, $id);
        $data = array(
            'dt_detail_brg' => $result,
            'flag' => $flag,
            'id' => $id,
            'format_no_acc' => ($flag=='medis')?$this->format_no_acc_m:$this->format_no_acc_nm,
            'title' => 'Persetujuan Pembelian '.$title,
            'breadcrumbs' => $this->breadcrumbs->show(),
            );

        // echo '<pre>';print_r($result);die;
        $this->load->view('persetujuan_pemb/App_persetujuan_pemb/detail_table_view', $data, false);
    }

    public function find_data()
    {   
        $output = array( "data" => http_build_query($_POST) . "\n" );
        echo json_encode($output);
    }

}


/* End of file example.php */
/* Location: ./application/modules/example/controllers/example.php */
