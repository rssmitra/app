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
        $this->load->model('purchasing/pendistribusian/Pengiriman_unit_model', 'Pengiriman_unit');
        // load libraries
        $this->load->library('stok_barang');
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
            'flag' => isset($_GET['flag'])?$_GET['flag']:'medis',
        );
        /*load view index*/
        $this->load->view('pendistribusian/Distribusi_permintaan/index', $data);
    }

    public function form($id='')
    {
        
        $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Pengiriman_unit/'.strtolower(get_class($this)).'/form');
        /*initialize flag for form add*/
        $data['flag'] = "create";
        // print_r($data);die;
        /*title header*/
        $data['title'] = $this->title;
        $data['form'] = 'distribusi';
        
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('pendistribusian/Pengiriman_unit/form_distribusi', $data);
    }

    public function show_cart(){
        $data = array();
        $data['cart_data'] = $this->Distribusi_permintaan->get_cart_data();
        $data['flag'] = $_GET['flag'];
        $data['form'] = 'distribusi';
        // print_r($this->db->last_query());die;
        $this->load->view('pendistribusian/Pengiriman_unit/form_cart', $data);
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
            
             // === KOLOM ACTION : DISTRIBUSI ===

            if ($row_list->total_diterima == 0) {

            // belum ada penerimaan sama sekali
            $row[] = '<div class="center">
                <a href="#" onclick="getMenu(\''.base_url().'purchasing/pendistribusian/Pengiriman_unit/form/'.$row_list->id_tc_permintaan_inst.'?flag='.$_GET['flag'].'\')" 
                   class="label label-xs label-primary" style="width:100%">
                   Distribusi
                </a>
              </div>';

            } elseif ($row_list->total_diterima < $row_list->total_permintaan) {

            // sudah sebagian
            $row[] = '<div class="center">
                <a href="#" onclick="getMenu(\''.base_url().'purchasing/pendistribusian/Pengiriman_unit/form/'.$row_list->id_tc_permintaan_inst.'?flag='.$_GET['flag'].'\')" 
                   class="label label-xs label-warning" style="width:100%">
                   Distribusi (Partial)
                </a>
              </div>';

            } else {

            // sudah full
            $row[] = '<div class="center">
                <i class="fa fa-check green bigger-120"></i>
              </div>';
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
            $row[] = '<div class="center">'.$this->tanggal->formatDateTimeFormDmy($row_list->tgl_pengiriman).'</div>';
            $row[] = '<div class="center">'.$this->tanggal->formatDateTimeFormDmy($row_list->tgl_input_terima).'</div>';
            $row[] = '<div class="center">'.ucfirst($row_list->yg_terima).'</div>';
            
            //Edit by amelia 12-01-2026
            // STATUS PENERIMAAN
            if ($row_list->tgl_input_terima != null && $row_list->yg_terima != null) {

                // Selesai
                $status_penerimaan = '
                    <div class="center">
                        <label class="label label-xs label-success">
                            <i class="fa fa-check-circle"></i> Selesai
                        </label>
                    </div>';

            } elseif ($row_list->tgl_pengiriman != null && $row_list->tgl_input_terima == null && $row_list->yg_terima == null) {

                // Belum diterima user
                $status_penerimaan = '
                    <div class="center">
                        <label class="label label-xs label-warning">
                            <i class="fa fa-exclamation-circle"></i> Belum diterima user
                        </label>
                    </div>';

            } else {

                // Belum dikirim
                $status_penerimaan = '
                    <div class="center">
                        <label class="label label-xs label-danger">
                            <i class="fa fa-truck"></i> Belum dikirim
                        </label>
                    </div>';
            }
            $row[] = $status_penerimaan;
                  
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

    public function insert_cart_log(){
        // print_r($_POST);die;
        // search data barang by barcode
        $barcode = $this->Pengiriman_unit->checkBarcode($_POST['barcode'], $_POST['flag']);

        if ( empty($barcode)) {

            echo json_encode( array('status' => 300, 'message' => 'Barang tidak ditemukan') ); exit;

        }else{

            if( empty($_POST['qty'])){
                echo json_encode( array('status' => 300, 'message' => 'Masukan qty !') ); exit;
            }

            $this->db->trans_begin();
            $total = $_POST['qty'] * (float)$_POST['harga'];
            $dataexc = array(
                'kode_brg' => $_POST['kode_brg'],
                'nama_brg' => $_POST['nama_brg'],
                'qty' => $_POST['qty'],
                'satuan' => $_POST['satuan'],
                'harga' => (float)$_POST['harga'],
                'total' => $total,
                'user_id_session' => $this->session->userdata('user')->user_id,
                'flag' => $_POST['flag'],
                'flag_form' => $_POST['flag_form'],
                'barcode' => $_POST['barcode'],
                'kode_bagian' => isset($_POST['dari_unit'])?$_POST['dari_unit']:'',
            );
            $this->db->insert('tc_permintaan_inst_cart_log', $dataexc);
            $this->db->trans_commit();
            echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'flag' => $dataexc['flag'], 'kode_brg' => $dataexc['kode_brg'], 'nama_brg' => $dataexc['nama_brg']));
        }

    }

    public function process_pengiriman_brg_unit()
    {
        // print_r($_POST);die;
        $this->load->library('form_validation');
        $val = $this->form_validation;
        
        $val->set_rules('kode_bagian_minta', 'Bagian/Unit', 'trim|required');
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
            $nama_bagian = $this->master->get_string_data('nama_bagian', 'mt_bagian', array('kode_bagian' => $val->set_value('kode_bagian_minta') ) );
            
            $table = ($_POST['flag']=='medis')?'tc_permintaan_inst':'tc_permintaan_inst_nm';
            $kode_bagian = ($_POST['flag']=='medis')?'060201':'070101';

            $dataexc = array(
                'tgl_permintaan' => date('Y-m-d'),
                'nomor_permintaan' => $this->regex->_genRegex($this->master->format_nomor_permintaan($_POST['flag']),'RGXQSL'),
                'kode_bagian_minta' => $this->regex->_genRegex($val->set_value('kode_bagian_minta'),'RGXQSL'),
                'jenis_permintaan' => $this->regex->_genRegex(0,'RGXQSL'),
                'catatan' => $this->regex->_genRegex($val->set_value('catatan'),'RGXQSL'),
                'version' => 1,
            );
            // print_r($dataexc);die;
            $dataexc['created_date'] = date('Y-m-d H:i:s');
            $dataexc['created_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
            $newId = $this->Pengiriman_unit->save($table, $dataexc);
            /*save logs*/
            // $this->logs->save($table, $newId, 'insert new record on '.$this->title.' module', json_encode($dataexc),'id_tc_permintaan_inst');
            
            // get data from cart
            $cart_data = $this->Distribusi_permintaan->get_cart_data();
            // print_r($cart_data);die;
            foreach( $cart_data as $row_brg ){

                // insert detail barang
                $dt_detail = array(
                    'id_tc_permintaan_inst' => $this->regex->_genRegex($newId,'RGXINT'),
                    'jumlah_permintaan' => $this->regex->_genRegex($row_brg->qty,'RGXINT'),
                    'jumlah_penerimaan' => $this->regex->_genRegex($row_brg->qty,'RGXINT'),
                    'kode_brg' => $this->regex->_genRegex($row_brg->kode_brg,'RGXQSL'),
                    'satuan' => $this->regex->_genRegex($row_brg->satuan,'RGXQSL'),
                    'tgl_kirim' => $this->regex->_genRegex(date('Y-m-d H:i:s'),'RGXQSL'),
                    'tgl_input' => $this->regex->_genRegex(date('Y-m-d H:i:s'),'RGXQSL'),
                    'kekurangan' => $this->regex->_genRegex(0,'RGXINT'),
                    'jml_acc_atasan' => $this->regex->_genRegex($row_brg->qty,'RGXINT'),
                    'jml_acc_umu' => $this->regex->_genRegex($row_brg->qty,'RGXINT'),
                );
                
                $dt_detail['created_date'] = date('Y-m-d H:i:s');
                $dt_detail['created_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                $id_permintaan_inst_det = $this->Pengiriman_unit->save($table.'_det', $dt_detail);
                /*save logs*/
                $this->logs->save($table.'_det', $id_permintaan_inst_det, 'insert new record on '.$this->title.' module', json_encode($dt_detail),'id_tc_permintaan_inst_det');
                
                // kurang stok gudang
                $this->stok_barang->stock_process($row_brg->kode_brg, $row_brg->qty, $kode_bagian, 3 ," ".$nama_bagian." ", 'reduce');

                // tambah stok depo
                $this->stok_barang->stock_process_depo($row_brg->kode_brg, $row_brg->qty, $kode_bagian, 3 ," ".$nama_bagian." ", 'restore', $val->set_value('kode_bagian_minta'));

                // update header permintaan_inst
                $dt_upd_permintaan = array(
                    'kode_bagian_kirim' => $kode_bagian,
                    'status_batal' => 0,
                    'nomor_pengiriman' => $newId,
                    'tgl_input' => date('Y-m-d H:i:s'),
                    'tgl_pengiriman' => date('Y-m-d H:i:s'),
                    'yg_serah' => $this->session->userdata('user')->fullname,
                    'yg_terima' => 'Staf '.$nama_bagian,
                    'tgl_input_terima' => date('Y-m-d H:i:s'),
                    'keterangan_kirim' => 'Transaksi pengiriman barang dari gudang ke unit',
                    'status_selesai' => 4,
                    'jenis_permintaan' => 0,
                    'no_kirim' => $newId,
                    'no_urut' => $newId,
                    'no_acc' => 'ACC/'.$newId.'',
                    'tgl_acc' => date('Y-m-d H:i:s'),
                    'created_date' => date('Y-m-d H:i:s'),
                    'created_by' => json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL'))),
                );
                
                $this->Pengiriman_unit->update($table, array('id_tc_permintaan_inst' => $newId), $dt_upd_permintaan );
                /*save logs*/
                $this->logs->save($table, $newId, 'update record on '.$this->title.' module', json_encode($dt_upd_permintaan),'id_tc_permintaan_inst');

                $this->db->trans_commit();

            }

            // delete cart session
            $this->db->delete('tc_permintaan_inst_cart_log', array('user_id_session' => $this->session->userdata('user')->user_id) );

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
        // echo "<pre>";print_r($result);die;

        $data = array(
            'dt_detail_brg' => $result,
            'flag' => $flag,
            'id' => $id,
            );
        $temp_view = $this->load->view('pendistribusian/Distribusi_permintaan/detail_table_view', $data, true);
        echo json_encode( array('html' => $temp_view) );
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
