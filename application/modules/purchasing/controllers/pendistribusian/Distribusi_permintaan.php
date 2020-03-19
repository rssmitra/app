<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Distribusi_permintaan extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'purchasing/pendistribusian/Distribusi_permintaan');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('purchasing/pendistribusian/Distribusi_permintaan_model', 'Distribusi_permintaan');
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
        $this->load->view('pendistribusian/Distribusi_permintaan/index', $data);
    }

    public function form($id='')
    {
        $data['string'] = isset($_GET['flag'])?$_GET['flag']:'';
        /*if id is not null then will show form edit*/
        if( $id != '' ){
            /*breadcrumbs for edit*/
            $this->breadcrumbs->push('Edit '.strtolower($this->title).'', 'Distribusi_permintaan/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
            /*get value by id*/
            $data['value'] = $this->Distribusi_permintaan->get_by_id($id);
            // print_r($data);die;
            $data['total_brg'] = $this->Distribusi_permintaan->get_brg_permintaan($_GET['flag'], $id); 
            /*initialize flag for form*/
            $data['flag'] = "update";
        }else{
            /*breadcrumbs for create or add row*/
            $data['nomor_permintaan'] = $this->master->format_nomor_permintaan($_GET['flag']);
            $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Distribusi_permintaan/'.strtolower(get_class($this)).'/form');
            /*initialize flag for form add*/
            $data['flag'] = "create";
        }
        // print_r($data);die;
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('pendistribusian/Distribusi_permintaan/form', $data);
    }

    /*function for view data only*/
    public function show($id)
    {
        $data['string'] = isset($_GET['flag'])?$_GET['flag']:'';
        /*breadcrumbs for view*/
        $this->breadcrumbs->push('View '.strtolower($this->title).'', 'Distribusi_permintaan/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*define data variabel*/
        //$data['value'] = $this->Distribusi_permintaan->get_by_id($id);
        $data['title'] = $this->title;
        $data['flag'] = "read";
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('pendistribusian/Distribusi_permintaan/form', $data);
    }


    public function get_data()
    {
        /*get data from model*/
        $list = $this->Distribusi_permintaan->get_datatables();
        
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center">
                        <label class="pos-rel">
                            <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->id_tc_permintaan_inst.'"/>
                            <span class="lbl"></span>
                        </label>
                      </div>';
            $row[] = '';
            $row[] = $row_list->id_tc_permintaan_inst;
            if( $row_list->status_selesai != 4 ){
                $row[] = '<div class="center">
                            <div class="btn-group">
                                <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">
                                    <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-inverse">
                                <li>'.$this->authuser->show_button('purchasing/pendistribusian/Distribusi_permintaan?flag='.$_GET['flag'].'','R',$row_list->id_tc_permintaan_inst,67).'</li>
                                <li>'.$this->authuser->show_button('purchasing/pendistribusian/Distribusi_permintaan?flag='.$_GET['flag'].'','U',$row_list->id_tc_permintaan_inst,67).'</li>
                                <li>'.$this->authuser->show_button('purchasing/pendistribusian/Distribusi_permintaan?flag='.$_GET['flag'].'','D',$row_list->id_tc_permintaan_inst,6).'</li>
                                </ul>
                            </div>
                        </div>';
            }else{
                $row[] = '<div class="center"><a href="#" onclick="PopupCenter('."'".base_url().'purchasing/pendistribusian/Distribusi_permintaan/print_preview/'.$row_list->id_tc_permintaan_inst.'?flag='.$_GET['flag']."'".', '."'PERMINTAAN PEMBELIAN'".', 1000, 550)" ><i class="fa fa-print bigger-150 inverse"></a></div>';
            }
            $row[] = '<div class="center">'.$row_list->id_tc_permintaan_inst.'</div>';
            $row[] = $row_list->nomor_permintaan;
            $row[] = $this->tanggal->formatDate($row_list->tgl_permintaan);
            $row[] = '<div class="left">'.ucwords($row_list->bagian_minta).'</div>';
            $row[] = '<div class="left">'.ucfirst($row_list->username).'</div>';
            $jenis_permintaan = ($row_list->jenis_permintaan==0)?'Rutin':'Cito';
            $row[] = '<div class="center">'.ucfirst($jenis_permintaan).'</div>';
            $style_status = ($row_list->status_selesai == 4) ? '<span style="color: green">Selesai</span>' :'<span style="color: red">Dalam Proses</span>';
            $row[] = '<div class="center">'.$style_status.'</div>';
            $row[] = '<div class="left">
                        Barang diterima oleh '.ucfirst($row_list->yg_terima).'<br>tanggal '.$this->tanggal->formatDateTime($row_list->tgl_input_terima).'
                      </div>';
                  
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Distribusi_permintaan->count_all(),
                        "recordsFiltered" => $this->Distribusi_permintaan->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function process()
    {
        // print_r($_POST);die;
        $this->load->library('form_validation');
        $val = $this->form_validation;
        if( $_POST['submit'] == 'header' ){
            $val->set_rules('tgl_permintaan', 'Tanggal Permintaan', 'trim|required');
            $val->set_rules('nomor_permintaan', 'Nomor Permintaan', 'trim|required');
            $val->set_rules('kode_bagian_minta', 'Bagian Unit', 'trim|required');
            $val->set_rules('jenis_permintaan', 'Jenis Permintaan', 'trim|required');
            $val->set_rules('catatan', 'Catatan', 'trim|required');
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
                
                $table = ($_POST['flag']=='medis')?'tc_permintaan_inst':'tc_permintaan_inst_nm';

                $dataexc = array(
                    'tgl_permintaan' => $this->regex->_genRegex($val->set_value('tgl_permintaan'),'RGXQSL'),
                    'nomor_permintaan' => $this->regex->_genRegex($this->master->format_nomor_permintaan($_POST['flag']),'RGXQSL'),
                    'kode_bagian_minta' => $this->regex->_genRegex($val->set_value('kode_bagian_minta'),'RGXQSL'),
                    'jenis_permintaan' => $this->regex->_genRegex($val->set_value('jenis_permintaan'),'RGXQSL'),
                    'catatan' => $this->regex->_genRegex($val->set_value('catatan'),'RGXQSL'),
                );
                // print_r($dataexc);die;
                if($id==0){
                    $dataexc['created_date'] = date('Y-m-d H:i:s');
                    $dataexc['created_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                    $newId = $this->Distribusi_permintaan->save($table, $dataexc);
                    /*save logs*/
                    $this->logs->save($table, $newId, 'insert new record on '.$this->title.' module', json_encode($dataexc),'id_tc_permintaan_inst');
                }else{
                    $dataexc['updated_date'] = date('Y-m-d H:i:s');
                    $dataexc['updated_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                    /*print_r($dataexc);die;*/
                    /*update record*/
                    $this->Distribusi_permintaan->update($table, array('id_tc_permintaan_inst' => $id), $dataexc);
                    $newId = $id;
                    $this->logs->save($table, $newId, 'update record'.$this->title.' module', json_encode($dataexc), 'id_tc_permintaan_inst');
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

    public function process_add_brg()
    {
        // print_r($_POST);die;
        $this->load->library('form_validation');
        $val = $this->form_validation;
        
        $val->set_rules('id_tc_permintaan_inst', 'ID', 'trim|required');
        $val->set_rules('kode_brg', 'Kode Barang', 'trim|required');
        $val->set_rules('jumlah_permintaan', 'Jumlah Permintaan', 'trim|required');
        $val->set_rules('satuan', 'Satuan Besar', 'trim|required');
        
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

            $table = ($_POST['flag']=='medis')?'tc_permintaan_inst':'tc_permintaan_inst_nm';

            $dataexc = array(
                'id_tc_permintaan_inst' => $this->regex->_genRegex($val->set_value('id_tc_permintaan_inst'),'RGXINT'),
                'kode_brg' => $this->regex->_genRegex($val->set_value('kode_brg'),'RGXQSL'),
                'jumlah_permintaan' => $this->regex->_genRegex($val->set_value('jumlah_permintaan'),'RGXINT'),
                'satuan' => $this->regex->_genRegex($val->set_value('satuan'),'RGXQSL'),
                'tgl_input' => $this->regex->_genRegex(date('Y-m-d H:i:s'),'RGXQSL'),
            );
            // print_r($dataexc);die;
            if($id==0){
                $dataexc['created_date'] = date('Y-m-d H:i:s');
                $dataexc['created_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                $newId = $this->Distribusi_permintaan->save($table.'_det', $dataexc);
                /*save logs*/
                // $this->logs->save($table.'_det', $newId, 'insert new record on '.$this->title.' module', json_encode($dataexc),'id_tc_permintaan_inst');
            }else{
                $dataexc['updated_date'] = date('Y-m-d H:i:s');
                $dataexc['updated_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                /*print_r($dataexc);die;*/
                /*update record*/
                $this->Distribusi_permintaan->update($table.'_det', array('id_tc_permintaan_det' => $id), $dataexc);
                $newId = $id;
                // $this->logs->save($table.'_det', $newId, 'update record'.$this->title.' module', json_encode($dataexc), 'id_tc_permintaan_inst');
            }
            
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                // get total barang
                $total_brg = $this->db->get_where($table.'_det', array('id_tc_permintaan_inst' => $_POST['id_tc_permintaan_inst']) );
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'flag' => $_POST['flag'], 'id_tc_permintaan_inst' => $_POST['id_tc_permintaan_inst'], 'total_brg' => $total_brg->num_rows() ));
            }
        }
    }

    public function get_detail($flag, $id){
        $result = $this->Distribusi_permintaan->get_brg_permintaan($flag, $id);
        $data = array(
            'dt_detail_brg' => $result,
            'flag' => $flag,
            'id' => $id,
            );
        $temp_view = $this->load->view('pendistribusian/Distribusi_permintaan/detail_table_view', $data, true);
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

        $result = $this->Distribusi_permintaan->get_detail_brg_permintaan_multiple($_GET['flag'], $_GET['id']);

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
        $this->load->view('pendistribusian/Distribusi_permintaan/print_preview_multiple', $data);
    }

    public function print_preview($id){
        $result = $this->Distribusi_permintaan->get_brg_permintaan($_GET['flag'], $id);
        $table = ($_GET['flag']=='non_medis')?'tc_permintaan_inst_nm':'tc_permintaan_inst';
        $title = ($_GET['flag']=='non_medis')?'Gudang Non Medis':'Gudang Medis';
        $subtitle = str_replace('_',' ',$_GET['flag']);
        $data = array(
            'dt_detail_brg' => $result,
            'permintaan' => $this->db->get_where($table, array('id_tc_permintaan_inst' => $id))->row(),
            'flag' => $_GET['flag'],
            'title' => $title,
            'subtitle' => $subtitle,
            );
        // echo '<pre>'; print_r($data);
        $this->load->view('pendistribusian/Distribusi_permintaan/print_preview', $data);
    }

    public function print_preview_retur($id){
        $result = $this->Distribusi_permintaan->get_brg_retur($_GET['flag'], $id);
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
        $this->load->view('pendistribusian/Distribusi_permintaan/print_preview_retur', $data);
    }

    public function find_data()
    {   
        $output = array( "data" => http_build_query($_POST) . "\n" );
        echo json_encode($output);
    }

    public function get_detail_permintaan_brg($id){
        $result = $this->Distribusi_permintaan->get_brg_permintaan($_GET['flag'], $id);
        $data = array(
            'dt_detail_brg' => $result,
            'flag' => $_GET['flag'],
            'id_tc_permintaan_inst' => $id,
            );
        // echo '<pre>'; print_r($data);
        $this->load->view('pendistribusian/Distribusi_permintaan/detail_permintaan_brg', $data);
    }

    public function delete()
    {
        $id=$this->input->post('ID')?$this->regex->_genRegex($this->input->post('ID',TRUE),'RGXQSL'):null;
        $toArray = explode(',',$id);
        if($id!=null){
            $table = ($_GET['flag']=='non_medis')?'tc_permintaan_inst_nm':'tc_permintaan_inst';
            if($this->Distribusi_permintaan->delete_by_id($table, $toArray)){
                $this->logs->save($table, $id, 'delete record', '', 'id_tc_permintaan_inst');
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
            if($this->Distribusi_permintaan->delete_brg_permintaan($table, $id)){
                $this->logs->save($table, $id, 'delete record', '', 'id_tc_permintaan_inst_det');
                $ttl = $this->Distribusi_permintaan->get_brg_permintaan($_POST['flag'], $_POST['id_tc_permintaan_inst']);
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
