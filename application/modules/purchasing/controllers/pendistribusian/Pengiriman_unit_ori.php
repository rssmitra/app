<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pengiriman_unit extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'purchasing/pendistribusian/Pengiriman_unit');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('purchasing/penerimaan/Penerimaan_brg_model', 'Penerimaan_brg');
        $this->load->model('purchasing/pendistribusian/Pengiriman_unit_model', 'Pengiriman_unit');
        // load libraries
        $this->load->library('stok_barang');
        /*enable profiler*/
        $this->output->enable_profiler(false);
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'Title';

    }


    public function form_distribusi()
    {
        
        $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Pengiriman_unit/'.strtolower(get_class($this)).'/form');
        /*initialize flag for form add*/
        $data['flag'] = "create";
        // print_r($data);die;
        /*title header*/
        $data['title'] = $this->title;
        
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('pendistribusian/Pengiriman_unit/form_distribusi', $data);
    }

    public function process()
    {

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
                'jenis_permintaan' => $this->regex->_genRegex(0,'RGXQSL'),
                'catatan' => $this->regex->_genRegex($val->set_value('catatan'),'RGXQSL'),
            );
            // print_r($dataexc);die;
            $dataexc['created_date'] = date('Y-m-d H:i:s');
            $dataexc['created_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
            $newId = $this->Pengiriman_unit->save($table, $dataexc);
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
                $id_permintaan_inst_det = $this->Pengiriman_unit->save($table.'_det', $dt_detail);
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
                
                $this->Pengiriman_unit->update($table, array('id_tc_permintaan_inst' => $newId), $dt_upd_permintaan );
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

    public function process_pengiriman_brg_unit()
    {
        
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
            );
            // print_r($dataexc);die;
            $dataexc['created_date'] = date('Y-m-d H:i:s');
            $dataexc['created_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
            $newId = $this->Pengiriman_unit->save($table, $dataexc);
            /*save logs*/
            $this->logs->save($table, $newId, 'insert new record on '.$this->title.' module', json_encode($dataexc),'id_tc_permintaan_inst');
            
            // get data from cart
            $cart_data = $this->Pengiriman_unit->get_cart_data();
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

    public function delete_row_brg()
    {
        $id=$this->input->post('ID')?$this->regex->_genRegex($this->input->post('ID',TRUE),'RGXQSL'):null;
        $table = ($_POST['flag']=='medis')?'tc_permintaan_inst_det':'tc_permintaan_inst_nm_det';
        if($id!=null){
            if($this->Pengiriman_unit->delete_brg_permintaan($table, $id)){
                $this->logs->save($table, $id, 'delete record', '', 'id_tc_permintaan_inst_det');
                $ttl = $this->Pengiriman_unit->get_brg_permintaan($_POST['flag'], $_POST['id_tc_permintaan_inst']);
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
        $data['cart_data'] = $this->Pengiriman_unit->get_cart_data();
        $data['flag'] = $_GET['flag'];
        // print_r($this->db->last_query());die;
        $this->load->view('pendistribusian/Pengiriman_unit/form_cart', $data);
    }

    public function form_input_qty(){
        $barcode = $this->Pengiriman_unit->checkBarcode($_GET['barcode'], $_GET['flag']);
        $data = array();
        $data['barcode'] = $_GET['barcode'];
        $this->load->view('pendistribusian/Pengiriman_unit/form_input_qty', $data);
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
                'barcode' => $_POST['barcode'],
                'kode_bagian' => isset($_POST['kode_bagian'])?$_POST['kode_bagian']:'',
            );
            $this->db->insert('tc_permintaan_inst_cart_log', $dataexc);
            $this->db->trans_commit();
            echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'flag' => $dataexc['flag'], 'kode_brg' => $dataexc['kode_brg'], 'nama_brg' => $dataexc['nama_brg']));
        }

    }

    public function check_barcode(){

        $barcode = $this->Pengiriman_unit->checkBarcode();
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
            if($this->db->where(array('kode_brg' => $id, 'user_id_session' => $this->session->userdata('user')->user_id))->delete('tc_permintaan_inst_cart_log')){
                echo json_encode(array('status' => 200, 'message' => 'Proses Hapus Data Berhasil Dilakukan' ));
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
