<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Penerimaan_brg extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'purchasing/penerimaan/Penerimaan_brg');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('purchasing/penerimaan/Penerimaan_brg_model', 'Penerimaan_brg');
        $this->load->model('purchasing/po/Po_penerbitan_model', 'Po_penerbitan');
        // load library
        $this->load->library('Stok_barang');
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
            'title' => 'Penerimaan Barang '.$title,
            'breadcrumbs' => $this->breadcrumbs->show(),
            'flag' => $_GET['flag'],
        );
        /*load view index*/
        $this->load->view('penerimaan/Penerimaan_brg/index', $data);
    }

    public function form($id='')
    {
        $flag = $_GET['flag'];
        /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Edit '.strtolower($this->title).'', 'Penerimaan_brg/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);

        $format = ($flag=='medis')?'PO':'PO-NM';
        $table = ($flag=='medis')?'tc_po':'tc_po_nm';
        $t_penerimaan = ($flag=='medis')?'tc_penerimaan_barang':'tc_penerimaan_barang_nm';
        $title = ($flag=='medis')?'Medis':'Non Medis';
        
        $data['format_nomor_penerimaan'] = $this->master->format_nomor_penerimaan_brg($flag);

        $data['value'] = $this->db->get_where($table, array('id_tc_po' => $id) )->row();
        // cek existing
        $data['existing'] = $this->db->get_where($t_penerimaan, array('id_tc_po' => $id, 'convert(varchar,tgl_penerimaan,23)' => date('Y-m-d') ) )->row();
        // print_r($this->db->last_query());die;
        /*get value by id*/
        $format_no_po = $this->master->get_max_number_per_month($table, 'no_urut_periodik', 'tgl_po');

        $data['flag'] = $flag;
        
        /*title header*/
        $data['title'] = 'Penerimaan Barang '.$title;
         /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();

        $this->load->view('penerimaan/Penerimaan_brg/form', $data, false);

    }
    
    function get_barang_po_penerimaan(){
        
        $dt_detail_brg = $this->Penerimaan_brg->get_detail_table($_GET['flag'], $_GET['id']);
        $getData = array();
        foreach($dt_detail_brg as $row){
            $getData[$row->kode_brg][] = $row;
        }
        // echo '<pre>';print_r($getData);die;
        $data['dt_detail_brg'] = $getData;
        $this->load->view('penerimaan/Penerimaan_brg/view_brg_po', $data);

    }
    public function form_input_batch()
    {
        $flag = $_GET['flag'];
        $t_penerimaan = ($_GET['flag']=='medis')?'tc_penerimaan_barang':'tc_penerimaan_barang_nm';
        // define
        $data['id_tc_po_det'] = $_GET['id_tc_po_det'];
        $data['flag'] = $flag;
        // search data
        $dt = $this->Penerimaan_brg->get_brg_po_by_id($_GET);

        if($_GET['id_penerimaan'] != ''){
            $data['penerimaan'] = $this->db->get_where( $t_penerimaan, array('id_penerimaan' => $_GET['id_penerimaan']) )->row();
        }
        $data['value'] = $dt;
        $data['title'] = $dt->kode_brg.' - '.$dt->nama_brg;
        $this->load->view('penerimaan/Penerimaan_brg/form_input_batch', $data, false);

    }
    
    /*function for view data only*/
    public function show_penerimaan_brg($id)
    {
        $t_penerimaan = ($_GET['flag']=='medis')?'tc_penerimaan_barang':'tc_penerimaan_barang_nm';
        $data['string'] = isset($_GET['flag'])?$_GET['flag']:'';
        $data['id_penerimaan_existing'] = $id;
        $data['value'] = $this->db->get_where($t_penerimaan, array('id_penerimaan' => $id) )->row();
        /*load form view*/
        $this->load->view('penerimaan/Penerimaan_brg/view_penerimaan_brg', $data);
    }


    public function get_data()
    {
        /*get data from model*/
        $list = $this->Penerimaan_brg->get_datatables();
        
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
            $row[] = '<div class="left">'.$row_list->namasupplier.'</div>';
            $row[] = '<div class="left">'.$row_list->diajukan_oleh.'</div>';
            $row[] = '<div class="left">'.$row_list->disetujui_oleh.'</div>';
            // $row[] = '<div class="pull-right">'.number_format((int)$row_list->total_stl_ppn).',-</div>';
            $row[] = '<div class="center">
                        <a href="#" onclick="PopupCenter('."'purchasing/po/Po_penerbitan/print_preview?ID=".$row_list->id_tc_po."&flag=".$_GET['flag']."'".','."'Cetak'".',900,650);" class="btn btn-xs btn-default" title="cetak po"><i class="fa fa-print dark"></i></a>
                      </div>';
            $row[] = '<div class="center">
                      <a href="#" onclick="getMenu('."'purchasing/penerimaan/Penerimaan_brg/form/".$row_list->id_tc_po."?flag=".$_GET['flag']."'".')" class="btn btn-xs btn-primary" title="revisi po">Terima </a>
                    </div>';

                  
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Penerimaan_brg->count_all(),
                        "recordsFiltered" => $this->Penerimaan_brg->count_filtered(),
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
            $val->set_rules('no_po', 'Nomor PO', 'trim|required');
            $val->set_rules('id_tc_po', 'ID Tc_PO', 'trim|required');
            $val->set_rules('kode_penerimaan', 'Kode Penerimaan', 'trim|required');
            $val->set_rules('tgl_penerimaan', 'Tanggal Penerimaan', 'trim|required');
            $val->set_rules('supplier_id_hidden', 'Supplier', 'trim|required');
            $val->set_rules('petugas', 'Petugas', 'trim|required');
            $val->set_rules('keterangan', 'Keterangan', 'trim|required');
            $val->set_rules('no_faktur', 'No Faktur', 'trim|required');
            $val->set_rules('dikirim', 'Penerima', 'trim|required');
        }

        if( $_POST['submit'] == 'penerimaan_selesai' ){
            if(isset($_POST['is_checked']) AND is_array($_POST['is_checked']) ){
                $val->set_rules('id', 'ID Penerimaan', 'trim|required');
                foreach ($_POST['is_checked'] as $key => $value) {
                    $val->set_rules('terima_'.$value.'', 'Jumlah Diterima ('.$value.')', 'trim|required'); 
                }
            }
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

            // id penerimaan
            $id = ($this->input->post('id'))?$this->regex->_genRegex($this->input->post('id'),'RGXINT'):0;

            $table = ($_POST['flag']=='medis')?'tc_penerimaan_barang':'tc_penerimaan_barang_nm';
            $mt_rekap_stok = ($_POST['flag']=='medis')?'mt_rekap_stok':'mt_rekap_stok_nm';
            $mt_depo_stok = ($_POST['flag']=='medis')?'mt_depo_stok':'mt_depo_stok_nm';
            $mt_barang = ($_POST['flag']=='medis')?'mt_barang':'mt_barang_nm';
            $tc_po = ($_POST['flag']=='medis')?'tc_po':'tc_po_nm';

            // submit header penerimaan barang
            if( $_POST['submit'] == 'header' ){
                
                $dataexc = array(
                    'no_po' => $this->regex->_genRegex( $val->set_value('no_po'), 'RGXQSL'),
                    'id_tc_po' => $this->regex->_genRegex( $val->set_value('id_tc_po'), 'RGXQSL'),
                    'tgl_penerimaan' => $this->regex->_genRegex( $val->set_value('tgl_penerimaan').' '.date('H:i:s'), 'RGXQSL'),
                    'kodesupplier' => $this->regex->_genRegex( $val->set_value('supplier_id_hidden'), 'RGXQSL'),
                    'petugas' => $this->regex->_genRegex( $val->set_value('petugas'), 'RGXQSL'),
                    'keterangan' => $this->regex->_genRegex( $val->set_value('keterangan'), 'RGXQSL'),
                    'no_faktur' => $this->regex->_genRegex( $val->set_value('no_faktur'), 'RGXQSL'),
                    'dikirim' => $this->regex->_genRegex( $val->set_value('dikirim'), 'RGXQSL'),
                );
                
                
                if($id==0){
                    if( $_POST['flag'] == 'non_medis' ){
                        $dataexc['id_penerimaan'] = $this->master->get_max_number($table, 'id_penerimaan' );
                    }
                    $dataexc['kode_penerimaan'] = $this->regex->_genRegex( $_POST['kode_penerimaan'], 'RGXQSL');
                    $dataexc['created_date'] = date('Y-m-d H:i:s');
                    $dataexc['created_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                    $last_id = $this->Penerimaan_brg->save($table, $dataexc);
                    $newId = ( $_POST['flag'] == 'non_medis' ) ? $dataexc['id_penerimaan'] : $last_id;
                    /*save logs*/
                    $this->logs->save($table, $newId, 'insert new record on '.$this->title.' module', json_encode($dataexc),'id_penerimaan');
                }else{
                    // $dataexc['id_penerimaan'] = $id;
                    $dataexc['updated_date'] = date('Y-m-d H:i:s');
                    $dataexc['updated_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                    // print_r($dataexc);die;
                    /*update record*/
                    $this->Penerimaan_brg->update($table, array('id_penerimaan' => $id), $dataexc);
                    $newId = $id;
                    $this->logs->save($table, $newId, 'update record'.$this->title.' module', json_encode($dataexc), 'id_penerimaan');
                }

            }
            
            // submit penyelesaian barang
            if( $_POST['submit'] == 'penerimaan_selesai' ){
                $dataexc = array();
                
                foreach ($_POST['is_checked'] as $keys => $rows) {
                    
                    // ============= proses mutasi stok
                    $konversi_satuan_kecil = $_POST['terima_'.$rows.''] * $_POST['rasio'][$rows];
                    $kartu_stok = $this->stok_barang->stock_process($rows, $konversi_satuan_kecil, $_POST['kode_bagian'], 1 ,"Nomor PO ".$_POST['no_po']."", 'restore');
                    // ============= end proses mutasi stok
                    
                    // ============= update mt_rekap_stok
                    // harga satuan kecil
                    // $harga_satuan_kecil = $_POST['harga_satuan'][$rows] / $_POST['rasio'][$rows]; 
                    // $harga_satuan_kecil_netto = $_POST['harga_satuan_netto'][$rows] / $_POST['rasio'][$rows]; 
                    
                    // ============= insert penerimaan barang detail
                    $config = array(
                        'hna' => $_POST['harga_satuan'][$rows],
                        'disc' => $_POST['discount'][$rows],
                        'ppn' => $_POST['ppn'][$rows],
                        'qty' => $_POST['terima_'.$rows.''],
                        'rasio' => $_POST['rasio'][$rows],
                    );
                    // eksekusi rumus untuk mencari harga
                    $harga = $this->master->rumus_harga($config);
                    // print_r($harga);die;
                    $dataexc["kode_brg"] = $rows;
                    $dataexc["id_penerimaan"] = $_POST['id'];
                    $dataexc["kode_penerimaan"] = $_POST['kode_penerimaan'];
                    $dataexc["jumlah_pesan"] = ceil((int)$_POST['jml_pesan'][$rows]);
                    $dataexc["jumlah_kirim"] = ceil((int)$_POST['terima_'.$rows.'']);
                    $dataexc["jumlah_pesan_decimal"] = $_POST['jml_pesan'][$rows];
                    $dataexc["jumlah_kirim_decimal"] = $_POST['terima_'.$rows.''];
                    $dataexc["content"] = $_POST['rasio'][$rows];
                    $dataexc["id_tc_po_det"] = $_POST['id_tc_po_det'][$rows];
                    $dataexc["harga"] = $harga['hna'];
                    $dataexc["disc"] = $harga['disc'];
                    $dataexc["harga_net"] = $harga['harga_satuan_netto'];
                    $dataexc["persediaan"] = $harga['harga_persediaan'];
                    $dataexc["dpp"] = $harga['dpp'];
                    $dataexc["ppn"] = $harga['harga_total_ppn'];
                    $dataexc["updated_date"] = date('Y-m-d H:i:s');
                    $dataexc["updated_by"] = $this->session->userdata('user')->fullname;
                    // insert to table
                    $exc = $this->Penerimaan_brg->save($table.'_detail', $dataexc);
                    // ============= end insert penerimaan barang
                                        
                    // insert rekap stok
                    $rekap_stok = array(
                        'harga_beli' => $harga['harga_jual'], 
                        'harga_beli_supplier' => $harga['harga_satuan_kecil'], 
                        'harga_persediaan' => $harga['harga_satuan_kecil'] * $kartu_stok['stok_akhir'], 
                        'updated_date' => date('Y-m-d H:i:s'),
                        'updated_by' => json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL'))),
                    );
                    
                    $this->db->update($mt_rekap_stok, $rekap_stok, array('kode_brg' => $rows, 'kode_bagian_gudang' => $_POST['kode_bagian']) );
                    // save log mt_rekap_stok
                    $this->logs->save($mt_rekap_stok, $rows, 'update data on '.$this->title.' module', json_encode($rekap_stok), 'kode_brg');
                    // ============= end update mt_rekap_stok

                    // ============= update mt_barang (rasio, harga, satuan)
                    $data_brg = array(
                        'is_active' => 1,
                        'content' => $_POST['rasio'][$rows],
                        'harga_beli' => $harga['harga_jual'],
                        'updated_date' => date('Y-m-d H:i:s'),
                        'updated_by' => json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL'))),
                    );
                    $this->db->update($mt_barang, $data_brg, array('kode_brg' => $rows) );

                    // update status aktif depo stok
                    $this->db->update($mt_depo_stok, array('is_active' => 1), array('kode_brg' => $rows, 'kode_bagian' => $_POST['kode_bagian']) );

                    // save log id barang
                    $this->logs->save($mt_barang, $rows, 'update data on '.$this->title.' module', json_encode($data_brg),'kode_brg');
                    // ============= update mt_barang (rasio, harga, satuan)
                    
                    // update tc_po status kirim jika jumlah pesan dan jumlah kirim sudah sesuai
                    $po_dt = $this->Penerimaan_brg->get_sisa_penerimaan($tc_po.'_det', $table.'_detail', $_POST['id_tc_po']);
                    
                    if ( $po_dt == 0 ) {
                        $update_po = array(
                            'status_kirim' => 1,
                            'status_selesai' => 1,
                            'kondisi' => 'Baik',
                            'kirim_via' => 'Kurir',
                            'di_kirim_ke' => 'Gudang '.COMP_SORT,
                            'petugas' => $this->session->userdata('user')->fullname,
                            'tgl_kirim' => date('Y-m-d H:i:s'),
                            'updated_date' => date('Y-m-d H:i:s'),
                            'updated_by' => json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL'))),
                        );
                        // print_r($update_po);die;
                        $this->db->update($tc_po, $update_po, array('id_tc_po' => $_POST['id_tc_po']) );
                        // save log id barang
                        $this->logs->save($tc_po, $_POST['id_tc_po'], 'update data on '.$this->title.' module ', json_encode($update_po),'id_tc_po');
                        
                    }
                    
                    $this->db->trans_commit();
                
                }
                // echo '<pre>'; print_r($_POST);die;
                
            }

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'flag' => $_POST['flag'], 'id' => isset($newId)?$newId:$id, 'id_tc_po' => $_POST['id_tc_po'], 'action'=> $_POST['submit'] , 'data' => $dataexc));
            }
        }
    }

    public function process_input_batch()
    {
        
        $this->load->library('form_validation');
        $val = $this->form_validation;

        if( $_POST['submit'] == 'input_batch' ){
            $val->set_rules('tgl_expired', 'Tanggal Expired', 'trim');
            $val->set_rules('no_batch', 'No Batch', 'trim|required');
            $val->set_rules('kode_box', 'Scan Kemasan Besar', 'trim|required');
            $val->set_rules('kode_pcs', 'Scan Kemasan Kecil', 'trim|required');
            $val->set_rules('jml_diterima', 'Jumlah diterima', 'trim|required');
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

            $id = ($this->input->post('id_tc_batch_log'))?$this->regex->_genRegex($this->input->post('id_tc_batch_log'),'RGXINT'):0;

            // print_r($_POST);die;
            
            if( $_POST['submit'] == 'input_batch' ){
                
                $table = ($_POST['flag']=='medis')?'tc_penerimaan_barang':'tc_penerimaan_barang_nm';
                
                $dataexc = array(
                    'reff_table' => $this->regex->_genRegex( $table, 'RGXQSL'),
                    'reff_id' => $this->regex->_genRegex( $_POST['id_penerimaan'], 'RGXINT'),
                    'kode_brg' => $this->regex->_genRegex( $_POST['kode_brg'], 'RGXQSL'),
                    'nama_brg' => $this->regex->_genRegex( $_POST['nama_brg'], 'RGXQSL'),
                    'kode_box' => $this->regex->_genRegex( $val->set_value('kode_box'), 'RGXQSL'),
                    'kode_pcs' => $this->regex->_genRegex( $val->set_value('kode_pcs'), 'RGXQSL'),
                    'jml_diterima' => $this->regex->_genRegex( $val->set_value('jml_diterima'), 'RGXINT'),
                    'jenis_satuan' => $this->regex->_genRegex( $_POST['satuan_brg'], 'RGXQSL'),
                    'nama_satuan' => $this->regex->_genRegex( $_POST[$_POST['satuan_brg']], 'RGXQSL'),
                    'rasio' => $this->regex->_genRegex( $_POST['rasio'], 'RGXINT'),
                    'tgl_expired' => $this->regex->_genRegex( $val->set_value('tgl_expired'), 'RGXQSL'),
                    'no_batch' => $this->regex->_genRegex( $val->set_value('no_batch'), 'RGXQSL'),
                    'id_tc_po_det' => $this->regex->_genRegex( $_POST['id_tc_po_det'], 'RGXQSL'),
                    'is_expired' => $this->regex->_genRegex( isset($_POST['no_expired']) ? ($_POST['no_expired']=='N')?'N':'Y' :'Y', 'RGXQSL'),
                    'flag' => $this->regex->_genRegex( $_POST['flag'], 'RGXQSL'),
                    
                );
                // print_r($dataexc);die;
                if($id==0){
                    $dataexc['created_date'] = date('Y-m-d H:i:s');
                    $dataexc['created_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                    $this->Penerimaan_brg->save('tc_penerimaan_brg_batch_log', $dataexc);
                    $newId = $this->db->insert_id();
                    /*save logs*/
                    $this->logs->save('tc_penerimaan_brg_batch_log', $newId, 'insert new record on '.$this->title.' module', json_encode($dataexc),'id_tc_batch_log');
                }else{
                    $dataexc['updated_date'] = date('Y-m-d H:i:s');
                    $dataexc['updated_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                    /*print_r($dataexc);die;*/
                    /*update record*/
                    $this->Penerimaan_brg->update('tc_penerimaan_brg_batch_log', array('id_tc_batch_log' => $id), $dataexc);
                    $newId = $id;
                    $this->logs->save('tc_penerimaan_brg_batch_log', $newId, 'update record'.$this->title.' module', json_encode($dataexc), 'id_tc_batch_log');
                }
                // update barcode barang
                $mt_barang = ($_POST['flag']=='medis')?'mt_barang':'mt_barang_nm';
                $this->db->update($mt_barang, array('barcode_box' => $dataexc['kode_box'], 'barcode_pcs' => $dataexc['kode_pcs']), array('kode_brg' => $dataexc['kode_brg']) );

            }
            
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'flag' => $_POST['flag'], 'id' => $newId, 'kode_brg' => $dataexc['kode_brg'], 'jml_diterima' => $val->set_value('jml_diterima'), 'no_batch' => $val->set_value('no_batch'), 'id_tc_po_det' => $_POST['id_tc_po_det']));
            }
        }
    }

    public function get_detail_brg_po(){
        /*string to array*/
        $arr_id = explode(',', $_POST['ID']);
        $url_qry = http_build_query($arr_id);
        echo json_encode(array('params' => $url_qry));
    }

    public function find_data()
    {   
        $output = array( "data" => http_build_query($_POST) . "\n" );
        echo json_encode($output);
    }

    public function get_detail($id){
        $flag = $_GET['flag'];
        
        $result = $this->Penerimaan_brg->get_detail_table($flag, $id);
        foreach ($result as $key => $value) {
            $getData[$value->kode_brg][] = $value; 
        }
        // echo '<pre>';print_r($getData);die;
        $data = array(
            'po_data' => $result,
            'flag' => $flag,
            'id' => $id,
            );
        $temp_view = $this->load->view('penerimaan/Penerimaan_brg/detail_table_view', $data, true);
        echo json_encode( array('html' => $temp_view) );
    }

    public function preview_penerimaan(){

        $title = ($_GET['flag']=='non_medis')?'Gudang Non Medis':'Gudang Medis';
        $result = $this->Penerimaan_brg->get_penerimaan_brg($_GET['flag'], $_GET['ID']);
        $getData = array();
        foreach($result as $row_dt){
            $getData[$row_dt->kode_brg][] = $row_dt;
        }
        // echo '<pre>'; print_r($getData);die;
        $data = array(
            'id_penerimaan' => $_GET['ID'],
            'penerimaan' => $result[0],
            'penerimaan_data' => $getData,
            'flag' => $_GET['flag'],
            'title' => $title,
        );
        // echo '<pre>'; print_r($data);die;
        $this->load->view('penerimaan/Penerimaan_brg/preview_penerimaan_brg', $data);
    }

}


/* End of file example.php */
/* Location: ./application/modules/example/controllers/example.php */
