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
        $data['flag'] = isset($_GET['flag'])?$_GET['flag']:'non_medis';
        $data['form'] = 'distribusi';
        
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('pendistribusian/Pengiriman_unit/form_distribusi', $data);
    }

    public function form_retur()
    {
        
        $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Pengiriman_unit/'.strtolower(get_class($this)).'/form');
        /*initialize flag for form add*/
        $data['flag'] = "create";
        // print_r($data);die;
        /*title header*/
        $data['title'] = 'Retur Barang ke Gudang';
        $data['flag'] = isset($_GET['flag'])?$_GET['flag']:'non_medis';
        $data['form'] = 'retur';
        
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('pendistribusian/Pengiriman_unit/form_retur', $data);
    }

    public function form_pengiriman_unit()
    {
        /*breadcrumbs for view*/
        $this->breadcrumbs->push('Pengimriman Barang ', 'Pengiriman_unit/'.strtolower(get_class($this)).'/'.__FUNCTION__.'?ID='.$_GET['ID'].'&flag='.$_GET['flag']);

        /*define data variabel*/
        $result = $this->Penerimaan_brg->get_penerimaan_brg($_GET['flag'], $_GET['ID']);
        $data['id'] = $_GET['ID'];
        $data['nomor_permintaan'] = $this->master->format_nomor_permintaan($_GET['flag']);
        $data['flag'] = isset($_GET['flag'])?$_GET['flag']:'';
        $data['penerimaan'] = $result;
        $data['value'] = $this->Pengiriman_unit->get_by_id($_GET['ID']);
        $data['title'] = 'Pengiriman Barang ke Unit/Depo';
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        // echo '<pre>'; print_r($data);die;
        /*load form view*/
        $this->load->view('pendistribusian/Pengiriman_unit/form_pengiriman_unit', $data);
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
        $result = $this->Pengiriman_unit->get_penerimaan_brg($_GET['flag'], $_GET['ID']);
        // print_r($this->db->last_query());die;
        $data['id'] = $_GET['ID'];
        $data['flag'] = $_GET['flag'];
        $data['value'] = $result;
        /*load form view*/
        $this->load->view('pendistribusian/Pengiriman_unit/view_detail_brg', $data);
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
            $newId = $this->Pengiriman_unit->save($table, $dataexc);
            /*save logs*/
            $this->logs->save($table, $newId, 'insert new record on '.$this->title.' module', json_encode($dataexc),'id_tc_permintaan_inst');
            

            foreach( $_POST['kode_brg'] as $row_brg ){

                // insert detail barang
                $dt_detail = array(
                    'id_tc_permintaan_inst' => $this->regex->_genRegex($newId,'RGXINT'),
                    'jumlah_permintaan' => $this->regex->_genRegex($_POST['total_dikirim'][$row_brg],'RGXQSL'),
                    'kode_brg' => $this->regex->_genRegex($row_brg,'RGXQSL'),
                    'satuan' => $this->regex->_genRegex($_POST['satuan'][$row_brg],'RGXQSL'),
                    'tgl_kirim' => $this->regex->_genRegex(date('Y-m-d H:i:s'),'RGXQSL'),
                    'tgl_input' => $this->regex->_genRegex(date('Y-m-d H:i:s'),'RGXQSL'),
                    'jumlah_penerimaan' => $this->regex->_genRegex($_POST['total_dikirim'][$row_brg],'RGXQSL'),
                    'kekurangan' => $this->regex->_genRegex(0,'RGXQSL'),
                    'jml_acc_atasan' => $this->regex->_genRegex($_POST['total_dikirim'][$row_brg],'RGXQSL'),
                    'jml_acc_umu' => $this->regex->_genRegex($_POST['total_dikirim'][$row_brg],'RGXQSL'),
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
            $mt_depo_stok = ($_POST['flag']=='medis')?'mt_depo_stok':'mt_depo_stok_nm';

            $cart_data = $this->Pengiriman_unit->get_cart_data($_POST['flag_form']);
            $kode_bagian_kirim = isset($cart_data[0]->kode_bagian)?$cart_data[0]->kode_bagian:0;

            if( $kode_bagian_kirim != 0 ) :

                $dataexc = array(
                    'tgl_permintaan' => date('Y-m-d'),
                    'nomor_permintaan' => $this->regex->_genRegex($this->master->format_nomor_permintaan($_POST['flag']),'RGXQSL'),
                    'kode_bagian_minta' => $this->regex->_genRegex($val->set_value('kode_bagian_minta'),'RGXQSL'),
                    'kode_bagian_kirim' => $this->regex->_genRegex($kode_bagian_kirim,'RGXQSL'),
                    'jenis_permintaan' => $this->regex->_genRegex(0,'RGXQSL'),
                    'catatan' => $this->regex->_genRegex($val->set_value('catatan'),'RGXQSL'),
                );
                // print_r($dataexc);die;
                $dataexc['created_date'] = date('Y-m-d H:i:s');
                $dataexc['created_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                $newId = $this->Pengiriman_unit->save($table, $dataexc);
                /*save logs*/
                $this->logs->save($table, $newId, 'insert new record on '.$this->title.' module', json_encode($dataexc),'id_tc_permintaan_inst');
                
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
                    if(in_array($row_brg->kode_bagian, array('060201', '070101') )){
                        $this->stok_barang->stock_process($row_brg->kode_brg, $row_brg->qty, $row_brg->kode_bagian, 3 ," ".$nama_bagian." ", 'reduce');
                    }else{
                        // kurang stok depo kirim
                        $this->stok_barang->kurang_stok_depo($row_brg->kode_brg, $row_brg->qty, $val->set_value('kode_bagian_minta'), 5 ," ".$nama_bagian." ", 'reduce', $row_brg->kode_bagian);
                    }

                    if($row_brg->is_bhp == 1){
                        // jika bhp maka langsung di mutasi stok nya tambah stok depo dan kurang
                        $this->stok_barang->stock_process_depo_bhp($row_brg->kode_brg, $row_brg->qty, $row_brg->kode_bagian, 3 ," ".$nama_bagian." ", 'restore', $val->set_value('kode_bagian_minta'));
                    }else{
                        // tambah stok depo minta
                        $nama_bagian_kirim = $this->master->get_string_data('nama_bagian', 'mt_bagian', array('kode_bagian' => $val->set_value('kode_bagian_minta') ) );
                        $jenis_kartu_stok = (in_array($row_brg->kode_bagian, array('060201', '070101') )) ? 3 : 5 ;
                        $this->stok_barang->stock_process_depo($row_brg->kode_brg, $row_brg->qty, $row_brg->kode_bagian, $jenis_kartu_stok ," ".$nama_bagian_kirim." ", 'restore', $val->set_value('kode_bagian_minta'));

                    }
                    
                    // update status aktif depo unit
                    $this->db->update($mt_depo_stok, array('is_active' => 1), array('kode_brg' => $row_brg->kode_brg, 'kode_bagian' => $row_brg->kode_bagian) );

                    // update header permintaan_inst
                    $dt_upd_permintaan = array(
                        'kode_bagian_kirim' => $row_brg->kode_bagian,
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
                $this->db->delete('tc_permintaan_inst_cart_log', array('user_id_session' => $this->session->userdata('user')->user_id, 'flag_form' => 'distribusi') );

            endif; 

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

    public function process_retur_brg_unit()
    {
        $this->load->library('form_validation');
        $val = $this->form_validation;
        
        $val->set_rules('dari_unit_hidden', 'Dari Unit', 'trim|required');
        $val->set_rules('tgl_retur', 'Tanggal', 'trim');
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
            $nama_bagian = $this->master->get_string_data('nama_bagian', 'mt_bagian', array('kode_bagian' => $val->set_value('dari_unit_hidden') ) );
            
            $kode_bagian_gudang = ($_POST['flag']=='medis')?'060201':'070101';
            $tc_po = ($_POST['flag']=='medis')?'tc_po':'tc_po_nm';
            $nama_gudang = ($_POST['flag']=='medis')?'Medis':'Non Medis';
            

            $dataexc = array(
                'kode_retur' => $this->master->format_nomor_retur($_POST['flag']),
                'kode_bagian' => $this->regex->_genRegex($val->set_value('dari_unit_hidden'),'RGXQSL'),
                'tgl_retur' => $val->set_value('tgl_retur'),
                'status' => 1,
                'no_induk' => $this->session->userdata('user')->user_id,
                'tgl_input' => date('Y-m-d'),
                'petugas_unit' => $this->session->userdata('user')->fullname,
                'petugas_gudang' => $this->session->userdata('user')->fullname,
                'no_urut_periodik' => $this->master->get_max_number('tc_retur_unit','id_tc_retur_unit'),
                'status_selesai' => 1,
                'flag' => $_POST['flag'],
                'catatan' => $this->regex->_genRegex($val->set_value('catatan'),'RGXQSL'),
            );
            
            $dataexc['created_date'] = date('Y-m-d H:i:s');
            $dataexc['created_by'] = json_encode(array('user_id' => $this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
            $newId = $this->Pengiriman_unit->save('tc_retur_unit', $dataexc);
            /*save logs*/
            $this->logs->save('tc_retur_unit', $newId, 'insert new record on '.$this->title.' module', json_encode($dataexc),'id_tc_retur_unit');
            
            // get data from cart
            $cart_data = $this->Pengiriman_unit->get_cart_data($_POST['flag_form']);
            
            foreach( $cart_data as $row_brg ){

                // retur penerimaan dan penjualan
                if( !empty($row_brg->reff_kode) || $row_brg->reff_kode != NULL ){

                    if($row_brg->retur_type == 'penerimaan_brg'){
                        // select penerimaan
                        $tc_penerimaan = ($_POST['flag']=='medis')?'tc_penerimaan_barang':'tc_penerimaan_barang_nm';
                        $dt_penerimaan = $this->db->get_where($tc_penerimaan.'_detail', array('kode_detail_penerimaan_barang' => $row_brg->reff_kode, 'kode_brg' => $row_brg->kode_brg ) )->row();
                        // convert to satuan besar
                        $jml_convert = $row_brg->qty / $dt_penerimaan->content;
                        if(!empty($dt_penerimaan)){
                            $jml_retur = $dt_penerimaan->jumlah_kirim - $jml_convert;
                            $jml_retur_decimal = $dt_penerimaan->jumlah_kirim_decimal - $jml_convert;
                            // print_r($jml_convert);die;
                            // update data penerimaan
                            $this->db->update($tc_penerimaan.'_detail', array('jumlah_kirim' => $jml_retur, 'jumlah_kirim_decimal' => $jml_retur_decimal, 'keterangan' => 'Retur barang '.$jml_convert.'' ), array('kode_detail_penerimaan_barang' => $row_brg->reff_kode, 'kode_brg' => $row_brg->kode_brg) );
                            // kurang stok gudang
                            $konversi = $row_brg->qty;
                            $this->stok_barang->stock_process($row_brg->kode_brg, $konversi, $kode_bagian_gudang, 21 ," ".$nama_gudang." Nomor ".$row_brg->reff_kode." ", 'reduce');
                        }
                        $catatan = 'Retur penerimaan kode '. $row_brg->reff_kode.'';
                        // update status selesai po
                        $this->db->where('id_tc_po IN (SELECT id_tc_po FROM '.$tc_po.'_det WHERE id_tc_po_det = '.$dt_penerimaan->id_tc_po_det.')')->update($tc_po, array('status_selesai' => NULL));
                    }

                    if($row_brg->retur_type == 'penjualan_brg'){
                        // select tr resep
                        $resep = $this->db->get_where('fr_tc_far_detail', array('kd_tr_resep' => $row_brg->reff_kode) )->row();
                        $harga_retur = $row_brg->qty * $resep->harga_jual;
                        $data_for_update = array(
                            'jumlah_retur' => $row_brg->qty,
                            'harga_r_retur' => $harga_retur,
                            'status_retur' => 1,
                        );
                        // fr_tc_far
                        $this->db->where(array('kd_tr_resep' => $row_brg->reff_kode))->update('fr_tc_far_detail', $data_for_update);

                        $data_log_for_update = array(
                            'jumlah_retur' => $row_brg->qty,
                            'is_restock' => $row_brg->is_restock,
                            'tgl_retur' => date('Y-m-d'),
                            'retur_by' => $this->session->userdata('user')->fullname,
                        );
                        
                        $this->db->where(array('relation_id' => $row_brg->reff_kode))->update('fr_tc_far_detail_log', $data_log_for_update);

                        // retur stok ke farmasi
                        if($row_brg->is_restock == 1){
                            $this->stok_barang->stock_process($row_brg->kode_brg, $row_brg->qty, '060101' , 8 ," (Retur Penjualan) - . ".$row_brg->reff_kode." ", 'restore'); 
                        }

                        $this->db->trans_commit();

                    }
                    

                }else{

                    // tambah stok gudang
                    $this->stok_barang->stock_process($row_brg->kode_brg, $row_brg->qty, $kode_bagian_gudang, 4 ,"", 'restore');

                    // kurang stok depo
                    $this->stok_barang->stock_process_depo($row_brg->kode_brg, $row_brg->qty, $kode_bagian_gudang, 4 ,"", 'reduce', $val->set_value('dari_unit_hidden'));
                    
                }

                // insert detail barang
                $dt_detail = array(
                    'kode_retur' => $this->regex->_genRegex($dataexc['kode_retur'],'RGXQSL'),
                    'kode_brg' => $this->regex->_genRegex($row_brg->kode_brg,'RGXQSL'),
                    'jumlah' => $row_brg->qty,
                    'jml_sebelum' => $row_brg->qtyBefore,
                    'alasan' => isset($catatan) ? $catatan : 'Retur barang karena kesalahan stok',
                    'id_tc_retur_unit' => $this->regex->_genRegex($newId,'RGXINT'),
                );
                
                $dt_detail['created_date'] = date('Y-m-d H:i:s');
                $dt_detail['created_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                $id_tc_retur_unit_det = $this->Pengiriman_unit->save('tc_retur_unit_det', $dt_detail);
                /*save logs*/
                $this->logs->save('tc_retur_unit_det', $id_tc_retur_unit_det, 'insert new record on '.$this->title.' module', json_encode($dt_detail),'id_tc_retur_unit_det');
                
                $this->db->trans_commit();

            }

            // delete cart session
            $this->db->delete('tc_permintaan_inst_cart_log', array('user_id_session' => $this->session->userdata('user')->user_id, 'flag_form' => 'retur') );

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
        $data['cart_data'] = $this->Pengiriman_unit->get_cart_data($_GET['form']);
        $data['form'] = ($_GET['form']=='retur')?$_GET['form']:'distribusi';
        // print_r($data);die;
        if($_GET['form'] == 'retur'){
            $this->load->view('pendistribusian/Pengiriman_unit/form_cart_retur', $data);
        }else{
            $this->load->view('pendistribusian/Pengiriman_unit/form_cart', $data);
        }
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
            $kode_bagian = isset($_POST['dari_unit'])?$_POST['dari_unit']:'';
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
                'kode_bagian' => isset($_POST['kode_bagian']) ? $_POST['kode_bagian'] : $kode_bagian,
                'flag_form' => isset($_POST['flag_form'])?$_POST['flag_form']:'',
                'reff_kode' => isset($_POST['reff_kode'])?$_POST['reff_kode']:'',
                'retur_type' => isset($_POST['retur_type'])?$_POST['retur_type']:'',
                'is_bhp' => isset($_POST['is_bhp'])?$_POST['is_bhp']:'',
                'is_restock' => isset($_POST['restock'])?$_POST['restock']:'',
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
            if($this->db->where(array('kode_brg' => $id, 'user_id_session' => $this->session->userdata('user')->user_id, 'flag_form' => $_POST['flag_form']))->delete('tc_permintaan_inst_cart_log')){
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

