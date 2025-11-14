<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Permintaan_stok_unit extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'purchasing/pendistribusian/Permintaan_stok_unit');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('purchasing/pendistribusian/Permintaan_stok_unit_model', 'Permintaan_stok_unit');
        /*enable profiler*/
        $this->output->enable_profiler(false);
        // load library inventory
        $this->load->library('Inventory_lib');
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'Title';

    }

    public function index() { 
        // echo '<pre>';print_r($this->session->all_userdata());
        /*define variable data*/
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show(),
        );
        /*load view index*/
        $this->load->view('pendistribusian/Permintaan_stok_unit/index', $data);
    }

    public function form($id='')
    {
        $flag = isset($_GET['flag'])?$_GET['flag']:'medis';

        $data['flag_type'] = $flag; 
        /*if id is not null then will show form edit*/
        if( $id != '' ){
            /*breadcrumbs for edit*/
            $this->breadcrumbs->push('Edit '.strtolower($this->title).'', 'Permintaan_stok_unit/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
            /*get value by id*/
            $data['id'] = $id; 
            
            $data['value'] = $this->Permintaan_stok_unit->get_by_id($id); 
            /*initialize flag for form*/
            $data['flag'] = "update";
        }else{
            /*breadcrumbs for create or add row*/
            $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Permintaan_stok_unit/'.strtolower(get_class($this)).'/form');
            /*initialize flag for form add*/
            $data['flag'] = "create";
        }
        $data['cart_data'] = $this->Permintaan_stok_unit->get_cart_data();
        // echo "<pre>";print_r($data);die;
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('pendistribusian/Permintaan_stok_unit/form', $data);
    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->Permintaan_stok_unit->get_datatables();
        
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
            if( $row_list->send_to_verify != 1 ){
                $row[] = '<div class="center">
                            <div class="btn-group">
                                <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">
                                    <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-inverse">
                                <li>'.$this->authuser->show_button('purchasing/pendistribusian/Permintaan_stok_unit','R',$row_list->id_tc_permintaan_inst,67).'</li>
                                <li>'.$this->authuser->show_button('purchasing/pendistribusian/Permintaan_stok_unit','U',$row_list->id_tc_permintaan_inst,67).'</li>
                                <li>'.$this->authuser->show_button('purchasing/pendistribusian/Permintaan_stok_unit','D',$row_list->id_tc_permintaan_inst,6).'</li>
                                </ul>
                            </div>
                        </div>';
            }else{
                $row[] = '<div class="center"><a href="#" onclick="PopupCenter('."'".base_url().'purchasing/pendistribusian/Permintaan_stok_unit/print_preview/'.$row_list->id_tc_permintaan_inst.'?flag='.$_GET['flag']."'".', '."'PERMINTAAN PEMBELIAN'".', 1000, 550)" ><i class="fa fa-print bigger-150 inverse"></a></div>';
            }
            $row[] = '<div class="center">'.$row_list->id_tc_permintaan_inst.'</div>';
            // $row[] = $row_list->nomor_permintaan;
            
            // Determine label based on flag parameter
            $flag = isset($_GET['flag']) ? $_GET['flag'] : '';
            if ($flag == 'medis') {
                $label_flag = '<span style="color: green; font-weight: bold">Medis</span>';
            } elseif ($flag == 'non_medis') {
                $label_flag = '<span style="color: blue; font-weight: bold">Non Medis</span>';
            } else {
                $label_flag = '';
            }

            $jenis_permintaan = ($row_list->jenis_permintaan==0)?'Rutin':'Cito';
            $row[] = $this->tanggal->formatDateDmy($row_list->tgl_permintaan).'<br>'.$label_flag.' - '.ucfirst($jenis_permintaan).'';
            $row[] = '<div class="left">'.ucwords($row_list->bagian_minta).'</div>';
            $row[] = '<div class="left">'.ucfirst($row_list->nama_user_input).'</div>';
            $row[] = '<div class="left">'.$row_list->catatan.'</div>';
            $tgl_acc = ($row_list->tgl_acc ==null) ? '<i class="fa fa-exclamation-triangle bigger-150 orange"></i>' : $this->tanggal->formatDateDmy($row_list->tgl_acc);
            $acc_by = ($row_list->acc_by ==null) ? '<i class="fa fa-exclamation-triangle bigger-150 orange"></i>' : $row_list->acc_by;
            $row[] = '<div class="center">'.$tgl_acc.'</div>';
            $row[] = '<div class="center">'.$acc_by.'</div>';
            if($row_list->tgl_acc == null)
            {
                $style_status = '<span style="width: 100% !important" class="label label-warning"><i class="fa fa-exclamation-triangle"></i> Belum diverifikasi</span>';
            }else{
                $style_status = ($row_list->status_acc == 1) ? '<span class="label label-success" style="width: 100% !important"><i class="fa fa-check"></i> Disetujui</span>' :'<span style="width: 100% !important" class="label label-danger"><i class="fa fa-times"></i> Tidak disetujui</span>';
            }
            
            $row[] = '<div class="center">'.$style_status.'</div>';
            $row[] = '<div class="center">'.$this->tanggal->formatDateTime($row_list->tgl_pengiriman).'</div>';
            $row[] = '<div class="center">'.$this->tanggal->formatDateTime($row_list->tgl_input_terima).'</div>';
            $row[] = '<div class="center">'.ucfirst($row_list->yg_terima).'</div>';
            $btn_kirim_permintaan = ($row_list->status_acc == null) ? '<a href="#" onclick="kirim_permintaan('."'".$row_list->id_tc_permintaan_inst."'".', '."'".$flag."'".')" title="Kirim Permintaan" class="label label-xs label-primary"><i class="fa fa-paper-plane"></i> Kirim</a>' : '';
            if($row_list->send_to_verify == 1){
                $btn_kirim_permintaan = '<i class="fa fa-check bigger-150 green" title="Terkirim"></i>';
            }
            $row[] = '<div class="center">'.$btn_kirim_permintaan.'</div>';
                  
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Permintaan_stok_unit->count_all(),
                        "recordsFiltered" => $this->Permintaan_stok_unit->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function process()
    {
        // echo "<pre>";print_r($_POST);die;
        $this->load->library('form_validation');
        $val = $this->form_validation;
        
        $val->set_rules('dari_unit', 'Bagian/Unit', 'trim|required');
        $val->set_rules('tgl_pengiriman', 'Tanggal', 'trim');
        $val->set_rules('catatan', 'Catatan', 'trim');
        
        $val->set_message('required', "Silahkan isi field \"%s\"");

        if ($val->run() == FALSE)
        {
            $val->set_error_delimiters('<div style="color:white">', '</div>');
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            $this->db->trans_begin();
            // nama bagian
            $table = ($_POST['flag']=='medis')?'tc_permintaan_inst':'tc_permintaan_inst_nm';
            
            $id = isset($_POST['id'])?$_POST['id']:0;
            $dataexc = array(
                'id_dd_user' => $this->session->userdata('user')->user_id,
                'tgl_permintaan' => date('Y-m-d'),
                'nomor_permintaan' => $this->regex->_genRegex($this->master->format_nomor_permintaan($_POST['flag']),'RGXQSL'),
                'kode_bagian_minta' => $this->regex->_genRegex($val->set_value('dari_unit'),'RGXQSL'),
                'jenis_permintaan' => $this->regex->_genRegex(0,'RGXQSL'),
                'catatan' => $this->regex->_genRegex($val->set_value('catatan'),'RGXQSL'),
                'version' => 1,
            );
            $dataexc['created_date'] = date('Y-m-d H:i:s');
            $dataexc['created_by'] = json_encode(array('user_id' => $this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
            if($id == 0){
                $newId = $this->Permintaan_stok_unit->save($table, $dataexc);
            }else{
                $this->Permintaan_stok_unit->update($table, ['id_tc_permintaan_inst' => $id], $dataexc);
                $newId = $id;
            }
            /*save logs*/
            $this->logs->save($table, $newId, 'insert new record on '.$this->title.' module', json_encode($dataexc),'id_tc_permintaan_inst');
            
            if($id == 0){
                $cart_data = $this->Permintaan_stok_unit->get_cart_data($_POST['flag_form']);
                // print_r($this->db->last_query());die;
                foreach( $cart_data as $row_brg ){
                    // insert detail barang
                    $dt_detail = array(
                        'id_dd_user' => $this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'),
                        'id_tc_permintaan_inst' => $this->regex->_genRegex($newId,'RGXINT'),
                        'jumlah_permintaan' => $this->regex->_genRegex($row_brg->qty,'RGXINT'),
                        'keterangan_permintaan' => $this->regex->_genRegex($row_brg->keterangan_permintaan,'RGXQSL'),
                        'kode_brg' => $this->regex->_genRegex($row_brg->kode_brg,'RGXQSL'),
                        'satuan' => $this->regex->_genRegex($row_brg->satuan,'RGXQSL'),
                        'is_bhp' => $this->regex->_genRegex($row_brg->is_bhp,'RGXINT'),
                        'tgl_input' => $this->regex->_genRegex(date('Y-m-d H:i:s'),'RGXQSL'),
                    );
                    $dt_detail['created_date'] = date('Y-m-d H:i:s');
                    $dt_detail['created_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                    $id_permintaan_inst_det = $this->Permintaan_stok_unit->save($table.'_det', $dt_detail);

                    $this->db->trans_commit();
                }
                // delete cart session
                $this->db->delete('tc_permintaan_inst_cart_log', array('user_id_session' => $this->session->userdata('user')->user_id, 'flag_form' => 'permintaan_stok_unit') );
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

    public function insert_cart_log(){
        
        if( empty($_POST['qty'])){
            echo json_encode( array('status' => 300, 'message' => 'Masukan qty !') ); exit;
        }
        // echo "<pre>";print_r($_POST);die;
        

        $this->db->trans_begin();
        $total = $_POST['qty'] * (float)$_POST['harga'];
        $kode_bagian = isset($_POST['dari_unit'])?$_POST['dari_unit']:'';
        $table = ($_POST['flag']=='medis')?'tc_permintaan_inst':'tc_permintaan_inst_nm';
        $tc_kartu_stok = ($_POST['flag']=='medis')?'tc_kartu_stok':'tc_kartu_stok_nm';

        if($_POST['id_tc_permintaan_inst'] !=''){

            $dt_detail = array(
                'id_dd_user' => $this->session->userdata('user')->user_id,
                'id_tc_permintaan_inst' => $_POST['id_tc_permintaan_inst'],
                'jumlah_permintaan' => $_POST['qty'],
                'kode_brg' => $_POST['kode_brg'],
                'satuan' => $_POST['satuan'],
                'keterangan_permintaan' => $_POST['keterangan_permintaan'],
                'is_bhp' => isset($_POST['is_bhp'])?$_POST['is_bhp']:0,
                'tgl_input' => date('Y-m-d H:i:s'),
            );
            $dt_detail['created_date'] = date('Y-m-d H:i:s');
            $dt_detail['created_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
            
            if($_POST['id_tc_permintaan_inst_det'] != ''){
                $this->Permintaan_stok_unit->update($table.'_det', ['id_tc_permintaan_inst_det' => $_POST['id_tc_permintaan_inst_det']], $dt_detail);
                $id_permintaan_inst_det = $_POST['id_tc_permintaan_inst_det'];
            }else{
                $id_permintaan_inst_det = $this->Permintaan_stok_unit->save($table.'_det', $dt_detail);
            }
            $this->db->trans_commit();

        }else{

            $dataexc = array(
                'kode_brg' => $_POST['kode_brg'],
                'nama_brg' => $_POST['nama_brg'],
                'qty' => (float)$_POST['qty'],
                'qtyBefore' => (float)$_POST['qtyBefore'],
                'satuan' => $_POST['satuan'],
                'harga' => (float)$_POST['harga'],
                'total' => (float)$total,
                'keterangan' => $_POST['keterangan_permintaan'],
                'user_id_session' => $this->session->userdata('user')->user_id,
                'flag' => $_POST['flag'],
                'kode_bagian' => $kode_bagian,
                'flag_form' => isset($_POST['flag_form'])?$_POST['flag_form']:'',
                'is_bhp' => isset($_POST['is_bhp'])?$_POST['is_bhp']:'',
                'is_restock' => isset($_POST['restock'])?$_POST['restock']:1,
            );
            // print_r($dataexc);die;
            $this->db->insert('tc_permintaan_inst_cart_log', $dataexc);

            if($_POST['stock_card'] == 0){
                // create kartu stok
                $id_kartu = $this->inventory_lib->write_kartu_stok(['table' => $tc_kartu_stok, 'kode_brg' => $_POST['kode_brg'], 'last_stok' => 0, 'new_stok' => 0, 'kode_bagian' => $kode_bagian, 'petugas' => $this->session->userdata('user')->fullname ], 0, 'pemasukan', 9);
                // create depo stok
                $this->inventory_lib->create_depo_stok(['id_kartu' => $id_kartu, 'kode_brg' => $_POST['kode_brg'], 'kode_bagian' => $kode_bagian, 'flag' => $_POST['flag']]);
            }
            
            $this->db->trans_commit();
        }

        echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'flag' => $_POST['flag'], 'kode_brg' => $_POST['kode_brg'], 'nama_brg' => $_POST['nama_brg'], 'id' => ''));

    }

    public function delete_cart()
    {
        $id=$this->input->post('ID')?$this->regex->_genRegex($this->input->post('ID',TRUE),'RGXQSL'):null;
        if($id!=null){

            if($_POST['id_tc_permintaan_inst_det'] != ''){
                if($_POST['type'] == 'cart_log'){
                    if($this->Permintaan_stok_unit->delete_cart_log('tc_permintaan_inst_cart_log', array($_POST['id_tc_permintaan_inst_det']))){
                        echo json_encode(array('status' => 200, 'message' => 'Proses Hapus Data Berhasil Dilakukan'));
                    }else{
                        echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Hapus Data Gagal Dilakukan'));
                    }
                    exit;
                }else{
                    $table = ($_POST['flag']=='medis')?'tc_permintaan_inst':'tc_permintaan_inst_nm';
                    if($this->Permintaan_stok_unit->delete_brg_permintaan($table.'_det', array($_POST['id_tc_permintaan_inst_det']))){
                        echo json_encode(array('status' => 200, 'message' => 'Proses Hapus Data Berhasil Dilakukan'));
                    }else{
                        echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Hapus Data Gagal Dilakukan'));
                    }
                    exit;
                }
                
            }else{
                if($this->db->where(array('kode_brg' => $id, 'user_id_session' => $this->session->userdata('user')->user_id, 'flag_form' => $_POST['flag_form']))->delete('tc_permintaan_inst_cart_log')){
                    echo json_encode(array('status' => 200, 'message' => 'Proses Hapus Data Berhasil Dilakukan' ));
                }else{
                    echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Hapus Data Gagal Dilakukan'));
                }
            }

            
        }else{
            echo json_encode(array('status' => 301, 'message' => 'Tidak ada item yang dipilih'));
        }
        
    }

    public function get_detail($id){
        $flag = isset($_GET['flag'])?$_GET['flag']:'medis';
        $result = $this->Permintaan_stok_unit->get_brg_permintaan($flag, $id);
        // echo "<pre>";print_r($result);die;

        $data = array(
            'dt_detail_brg' => $result,
            'flag' => $flag,
            'id' => $id,
            );
        $temp_view = $this->load->view('pendistribusian/Permintaan_stok_unit/detail_table_view', $data, true);
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

        $result = $this->Permintaan_stok_unit->get_detail_brg_permintaan_multiple($_GET['flag'], $_GET['id']);

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
        $this->load->view('pendistribusian/Permintaan_stok_unit/print_preview_multiple', $data);
    }

    public function print_preview($id){
        $result = $this->Permintaan_stok_unit->get_brg_permintaan($_GET['flag'], $id);
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
        $this->load->view('pendistribusian/Permintaan_stok_unit/print_preview', $data);
    }

    public function find_data()
    {   
        $output = array( "data" => http_build_query($_POST) . "\n" );
        echo json_encode($output);
    }

    public function get_detail_permintaan_brg($id){
        $result = $this->Permintaan_stok_unit->get_brg_permintaan($_GET['flag'], $id);
        $data = array(
            'dt_detail_brg' => $result,
            'flag' => $_GET['flag'],
            'id_tc_permintaan_inst' => $id,
            );
        // echo '<pre>'; print_r($data);
        $this->load->view('pendistribusian/Permintaan_stok_unit/detail_permintaan_brg', $data);
    }

    public function delete()
    {
        $id=$this->input->post('ID')?$this->regex->_genRegex($this->input->post('ID',TRUE),'RGXQSL'):null;
        $toArray = explode(',',$id);
        if($id!=null){
            $table = ($_GET['flag']=='non_medis')?'tc_permintaan_inst_nm':'tc_permintaan_inst';
            if($this->Permintaan_stok_unit->delete_by_id($table, $toArray)){
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
            if($this->Permintaan_stok_unit->delete_brg_permintaan($table, $id)){
                $this->logs->save($table, $id, 'delete record', '', 'id_tc_permintaan_inst_det');
                $ttl = $this->Permintaan_stok_unit->get_brg_permintaan($_POST['flag'], $_POST['id_tc_permintaan_inst']);
                echo json_encode(array('status' => 200, 'message' => 'Proses Hapus Data Berhasil Dilakukan', 'total_brg' => count($ttl) ));

            }else{
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Hapus Data Gagal Dilakukan'));
            }
        }else{
            echo json_encode(array('status' => 301, 'message' => 'Tidak ada item yang dipilih'));
        }
        
    }

    public function kirim_permintaan()
    {
        $id=$this->input->post('ID')?$this->regex->_genRegex($this->input->post('ID',TRUE),'RGXQSL'):null;
        $table = ($_POST['flag']=='medis')?'tc_permintaan_inst':'tc_permintaan_inst_nm';
        if($id!=null){
            if($this->Permintaan_stok_unit->update($table, ['id_tc_permintaan_inst' => $id], ['send_to_verify' => 1])){
                echo json_encode(array('status' => 200, 'message' => 'Proses Kirim Data Berhasil Dilakukan'));

            }else{
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Kirim Data Gagal Dilakukan'));
            }
        }else{
            echo json_encode(array('status' => 301, 'message' => 'Tidak ada item yang dipilih'));
        }
        
    }

    public function get_detail_cart()
    {
        /*get data from model*/
        $list = $this->Permintaan_stok_unit->get_cart_data();
        // echo '<pre>';print_r($this->db->last_query());die;
        
        $data = array();
        $no=0;
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center">'.$no.'</div>';
            $row[] = '<div class="left">'.$row_list->kode_brg.'</div>';

            $is_bhp = ($row_list->is_bhp==1)?'<i class="fa fa-check green bigger-120"></i>':'';

            $row[] = '<div class="left">'.$row_list->nama_brg.'</div>';
            $row[] = '<div class="center">'.$is_bhp.'</div>';
            $row[] = '<div class="center">'.$row_list->jumlah_stok_sebelumnya.'</div>';
            $row[] = '<div class="center">'.$row_list->qty.'</div>';
            $row[] = '<div class="center">'.$row_list->satuan.'</div>';
            $row[] = '<div style="text-align: right">'.number_format($row_list->harga).'</div>';
            $id_tc_permintaan_inst_det = isset($row_list->id_tc_permintaan_inst_det)?$row_list->id_tc_permintaan_inst_det:'';
            $row[] = '<div class="left">'.$row_list->keterangan_permintaan.'</div>';
            $row[] = '<div class="center"><a href="#" class="label label-danger" onclick="delete_cart('."'".trim($row_list->kode_brg)."'".', '.$id_tc_permintaan_inst_det.', '."'".$row_list->type_tbl."'".')"><i class="fa fa-times-circle bigger-120"></i></a> <a class="label label-success" href="#" onclick="update_cart('."'".trim($row_list->kode_brg)."'".', '.$id_tc_permintaan_inst_det.', '."'".$row_list->type_tbl."'".')"><i class="fa fa-pencil bigger-120"></i></a></div>';

            $data[] = $row;
        }

        $output = array(
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function get_item_detail()
    {
        $ID = $this->input->get('ID')?$this->regex->_genRegex($this->input->get('ID',TRUE),'RGXQSL'):null;
        $flag = $this->input->get('flag')?$this->regex->_genRegex($this->input->get('flag',TRUE),'RGXQSL'):null;
        $type = $this->input->get('flag')?$this->regex->_genRegex($this->input->get('type',TRUE),'RGXQSL'):null;
        $item = $this->Permintaan_stok_unit->get_item_detail($ID, $flag, $type);
        echo json_encode(['status' => 200, 'data' => $item]);
        
    }


}


/* End of file example.php */
/* Location: ./application/modules/example/controllers/example.php */
