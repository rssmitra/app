<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pemakaian_bhp extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'inventory/stok/Pemakaian_bhp');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('purchasing/penerimaan/Penerimaan_brg_model', 'Penerimaan_brg');
        $this->load->model('inventory/stok/Pemakaian_bhp_model', 'Pemakaian_bhp');
        // load libraries
        $this->load->library('stok_barang');
        /*enable profiler*/
        $this->output->enable_profiler(false);
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'Title';

    }


    public function index()
    {
        
        $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Pemakaian_bhp/'.strtolower(get_class($this)).'/form');
        /*initialize flag for form add*/
        $data['flag'] = "create";
        // print_r($data);die;
        /*title header*/
        $data['title'] = $this->title;
        $data['flag'] = isset($_GET['flag'])?$_GET['flag']:'non_medis';
        
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('stok/Pemakaian_bhp/form_distribusi', $data);
    }

    public function form_retur()
    {
        
        $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Pemakaian_bhp/'.strtolower(get_class($this)).'/form');
        /*initialize flag for form add*/
        $data['flag'] = "create";
        // print_r($data);die;
        /*title header*/
        $data['title'] = 'Retur Barang ke Gudang';
        $data['flag'] = isset($_GET['flag'])?$_GET['flag']:'non_medis';
        
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('stok/Pemakaian_bhp/form_retur', $data);
    }

    public function form_pengiriman_unit()
    {
        /*breadcrumbs for view*/
        $this->breadcrumbs->push('Pengimriman Barang ', 'Pemakaian_bhp/'.strtolower(get_class($this)).'/'.__FUNCTION__.'?ID='.$_GET['ID'].'&flag='.$_GET['flag']);

        /*define data variabel*/
        $result = $this->Penerimaan_brg->get_penerimaan_brg($_GET['flag'], $_GET['ID']);
        $data['id'] = $_GET['ID'];
        $data['nomor_permintaan'] = $this->master->format_nomor_permintaan($_GET['flag']);
        $data['flag'] = isset($_GET['flag'])?$_GET['flag']:'';
        $data['penerimaan'] = $result;
        $data['value'] = $this->Pemakaian_bhp->get_by_id($_GET['ID']);
        $data['title'] = 'Pengiriman Barang ke Unit/Depo';
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        // echo '<pre>'; print_r($data);die;
        /*load form view*/
        $this->load->view('stok/Pemakaian_bhp/form_pengiriman_unit', $data);
    }

    public function show_penerimaan_brg()
    {
        $id = $_GET['ID'];
        $t_penerimaan = ($_GET['flag']=='medis')?'tc_penerimaan_barang':'tc_penerimaan_barang_nm';
        $data['string'] = isset($_GET['flag'])?$_GET['flag']:'';
        $data['id_penerimaan_existing'] = $id;
        $data['value'] = $this->db->get_where($t_penerimaan, array('id_penerimaan' => $id) )->row();
        /*load form view*/
        $this->load->view('penerimaan/Penerimaan_brg/view_penerimaan_brg', $data);
    }

    public function show_detail_brg()
    {
        $result = $this->Pemakaian_bhp->get_penerimaan_brg($_GET['flag'], $_GET['ID']);
        // print_r($this->db->last_query());die;
        $data['id'] = $_GET['ID'];
        $data['flag'] = $_GET['flag'];
        $data['value'] = $result;
        /*load form view*/
        $this->load->view('stok/Pemakaian_bhp/view_detail_brg', $data);
    }
    
    public function process()
    {
        // print_r($_POST);die;
        $this->load->library('form_validation');
        $val = $this->form_validation;
        
        $val->set_rules('kode_bagian_minta', 'Bagian/Unit', 'trim|required');
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
                'kode_bagian_kirim' => $this->regex->_genRegex($kode_bagian,'RGXQSL'),
                'jenis_permintaan' => $this->regex->_genRegex(0,'RGXQSL'),
                'catatan' => $this->regex->_genRegex($val->set_value('catatan'),'RGXQSL'),
            );
            // print_r($dataexc);die;
            $dataexc['created_date'] = date('Y-m-d H:i:s');
            $dataexc['created_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
            $newId = $this->Pemakaian_bhp->save($table, $dataexc);
            /*save logs*/
            $this->logs->save($table, $newId, 'insert new record on '.$this->title.' module', json_encode($dataexc),'id_tc_permintaan_inst');
            

            foreach( $_POST['kode_brg'] as $row_brg ){

                // insert detail barang
                $dt_detail = array(
                    'id_tc_permintaan_inst' => $this->regex->_genRegex($newId,'RGXINT'),
                    'jumlah_permintaan' => $this->regex->_genRegex($_POST['total_dikirim'][$row_brg],'RGXINT'),
                    'kode_brg' => $this->regex->_genRegex($row_brg,'RGXQSL'),
                    'satuan' => $this->regex->_genRegex($_POST['satuan'][$row_brg],'RGXQSL'),
                    'tgl_kirim' => $this->regex->_genRegex(date('Y-m-d H:i:s'),'RGXQSL'),
                    'tgl_input' => $this->regex->_genRegex(date('Y-m-d H:i:s'),'RGXQSL'),
                    'jumlah_penerimaan' => $this->regex->_genRegex($_POST['total_dikirim'][$row_brg],'RGXINT'),
                    'kekurangan' => $this->regex->_genRegex(0,'RGXINT'),
                    'jml_acc_atasan' => $this->regex->_genRegex($_POST['total_dikirim'][$row_brg],'RGXINT'),
                    'jml_acc_umu' => $this->regex->_genRegex($_POST['total_dikirim'][$row_brg],'RGXINT'),
                );
                
                $dt_detail['created_date'] = date('Y-m-d H:i:s');
                $dt_detail['created_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                $id_permintaan_inst_det = $this->Pemakaian_bhp->save($table.'_det', $dt_detail);
                /*save logs*/
                $this->logs->save($table.'_det', $id_permintaan_inst_det, 'insert new record on '.$this->title.' module', json_encode($dt_detail),'id_tc_permintaan_inst_det');
                
                // kurang stok gudang
                $this->stok_barang->stock_process($row_brg, $_POST['total_dikirim'][$row_brg], $kode_bagian, 3 ," ".$nama_bagian." ", 'reduce');

                // tambah stok depo
                $this->stok_barang->stock_process_depo($row_brg, $_POST['total_dikirim'][$row_brg], $kode_bagian, 3 ," ".$nama_bagian." ", 'restore', $val->set_value('kode_bagian_minta'));

                
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
                    'keterangan_kirim' => 'Distribusi langsung dari penerimaan barang',
                    'status_selesai' => 4,
                    'jenis_permintaan' => 0,
                    // 'jml_acc_atasan' => $_POST['total_dikirim'][$row_brg],
                    // 'jml_acc_umu' => $_POST['total_dikirim'][$row_brg],
                    'no_kirim' => $newId,
                    'no_urut' => $newId,
                    'no_acc' => 'ACC/'.$newId.'',
                    'tgl_acc' => date('Y-m-d H:i:s'),
                    'created_date' => date('Y-m-d H:i:s'),
                    'created_by' => json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL'))),
                );
                
                $this->Pemakaian_bhp->update($table, array('id_tc_permintaan_inst' => $newId), $dt_upd_permintaan );
                /*save logs*/
                $this->logs->save($table, $newId, 'update record on '.$this->title.' module', json_encode($dt_upd_permintaan),'id_tc_permintaan_inst');

                $this->db->trans_commit();

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

    public function process_pemakaian_bhp()
    {
        // print_r($_POST);die;
        $this->load->library('form_validation');
        $val = $this->form_validation;
        
        $val->set_rules('tgl_pemakaian_bhp', 'Tanggal', 'trim');
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
            
            // get data from cart
            $cart_data = $this->Pemakaian_bhp->get_cart_data($_POST['flag_form']);
            // print_r($cart_data);die;
            foreach( $cart_data as $row_brg ){
                $kode_bagian_gdg = ($row_brg->flag=='non_medis')?'070101':'060201';
                $nama_bagian = $this->master->get_string_data('nama_bagian', 'mt_bagian', array('kode_bagian' => $row_brg->kode_bagian ) );
                $tgl_input = $val->set_value('tgl_pemakaian_bhp').' '.date('H:i:s');
                // kurang stok depo
                $this->stok_barang->stock_process_depo($row_brg->kode_brg, $row_brg->qty, $kode_bagian_gdg, 7 ," ".$nama_bagian." ", 'reduce', $row_brg->kode_bagian, '', $tgl_input);
                $this->db->trans_commit();
            }

            // delete cart session
            $this->db->delete('tc_pemakaian_bhp_cart_log', array('user_id_session' => $this->session->userdata('user')->user_id, 'flag_form' => 'bhp') );

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'flag' => $_POST['flag']));
            }
        }
    }
    
    public function delete_row_brg()
    {
        $id=$this->input->post('ID')?$this->regex->_genRegex($this->input->post('ID',TRUE),'RGXQSL'):null;
        $table = ($_POST['flag']=='medis')?'tc_permintaan_inst_det':'tc_permintaan_inst_nm_det';
        if($id!=null){
            if($this->Pemakaian_bhp->delete_brg_permintaan($table, $id)){
                $this->logs->save($table, $id, 'delete record', '', 'id_tc_permintaan_inst_det');
                $ttl = $this->Pemakaian_bhp->get_brg_permintaan($_POST['flag'], $_POST['id_tc_permintaan_inst']);
                echo json_encode(array('status' => 200, 'message' => 'Proses Hapus Data Berhasil Dilakukan', 'total_brg' => count($ttl) ));

            }else{
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Hapus Data Gagal Dilakukan'));
            }
        }else{
            echo json_encode(array('status' => 301, 'message' => 'Tidak ada item yang dipilih'));
        }
        
    }

    public function show_cart(){
        $data = array();
        $data['cart_data'] = $this->Pemakaian_bhp->get_cart_data($_GET['form']);
        $data['form'] = 'bhp';
        $this->load->view('stok/Pemakaian_bhp/form_cart', $data);
    }


    public function form_input_qty(){
        $barcode = $this->Pemakaian_bhp->checkBarcode($_GET['barcode'], $_GET['flag']);
        $data = array();
        $data['barcode'] = $_GET['barcode'];
        $this->load->view('stok/Pemakaian_bhp/form_input_qty', $data);
    }

    public function insert_cart_log(){
        // print_r($_POST);die;
        // search data barang by barcode
        $barcode = $this->Pemakaian_bhp->checkBarcode($_POST['barcode'], $_POST['flag']);

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
                'qtyBefore' => $_POST['qtyBefore'],
                'satuan' => $_POST['satuan'],
                'harga' => (float)$_POST['harga'],
                'total' => $total,
                'user_id_session' => $this->session->userdata('user')->user_id,
                'flag' => $_POST['flag'],
                'barcode' => $_POST['barcode'],
                'kode_bagian' => isset($_POST['from_unit']) ? $_POST['from_unit'] : '',
                'flag_form' => isset($_POST['flag_form'])?$_POST['flag_form']:'',
                'reff_kode' => isset($_POST['reff_kode'])?$_POST['reff_kode']:'',
            );
            $this->db->insert('tc_pemakaian_bhp_cart_log', $dataexc);
            $this->db->trans_commit();
            echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'flag' => $dataexc['flag'], 'kode_brg' => $dataexc['kode_brg'], 'nama_brg' => $dataexc['nama_brg']));
        }

    }

    public function check_barcode(){

        $barcode = $this->Pemakaian_bhp->checkBarcode();
        $data = array();
        $data['result'] = $barcode;
        // print_r($data);die;
        $html = $this->load->view('Templates/templates/detail_item_pengiriman_brg', $data, true);

        if ( count($barcode) < 1 ) {

            echo json_encode( array('status' => 300, 'message' => 'Barang tidak ditemukan') ); exit;

        }else{

            echo json_encode(array('status' => 200, 'message' => 'Sukses', 'flag' => $_POST['flag'], 'html' => $html, 'count' => count($barcode), 'data_brg' => $barcode[0]));

        }

    }

    public function delete_cart()
    {
        $id=$this->input->post('ID')?$this->regex->_genRegex($this->input->post('ID',TRUE),'RGXQSL'):null;
        if($id!=null){
            if($this->db->where(array('kode_brg' => $id, 'user_id_session' => $this->session->userdata('user')->user_id, 'flag_form' => $_POST['flag_form']))->delete('tc_pemakaian_bhp_cart_log')){
                echo json_encode(array('status' => 200, 'message' => 'Proses Hapus Data Berhasil Dilakukan' ));
            }else{
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Hapus Data Gagal Dilakukan'));
            }
        }else{
            echo json_encode(array('status' => 301, 'message' => 'Tidak ada item yang dipilih'));
        }
        
    }

    public function process_pemakaian_bhp_unit()
    {
        // print_r($_POST);die;
        $this->load->library('form_validation');
        $val = $this->form_validation;
        
        $val->set_rules('tgl_trx', 'Tanggal', 'trim|required');
        $val->set_rules('jam_trx', 'Jam', 'trim|required');
        $val->set_rules('pl_jumlah_obat', 'Jam', 'trim|required|numeric', ['required' => 'Silahkan isi jumlah obat yang akan dipakai!', 'numeric' => 'Jumlah obat harus berupa angka!']);
        $val->set_rules('kode_brg', 'Nama Barang', 'trim|required', ['required' => 'Silahkan cari nama barang dengan benar!']);
        
        $val->set_message('required', "Silahkan isi field \"%s\"");

        if ($val->run() == FALSE)
        {
            $val->set_error_delimiters('<div style="color:white">', '</div>');
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            $this->db->trans_begin();
            
            $kode_bagian = $_POST['kode_bagian_depo'];
            $nama_bagian = $this->master->get_string_data('nama_bagian', 'mt_bagian', array('kode_bagian' => $kode_bagian ) );
            // kurang stok depo
            $this->stok_barang->stock_process_depo($_POST['kode_brg'], $_POST['pl_jumlah_obat'], $kode_bagian, 7 ," ".$nama_bagian." ", 'reduce', $kode_bagian, $_POST['no_kunjungan']);
            

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


}


/* End of file example.php */
/* Location: ./application/modules/example/controllers/example.php */
