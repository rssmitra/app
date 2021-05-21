<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Inv_master_barang extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'inventory/master/Inv_master_barang');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('master/Inv_master_barang_model', 'Inv_master_barang');
        /*enable profiler*/
        $this->output->enable_profiler(false);
        /*profile class*/
        $this->title = ( $_GET['flag'] == 'medis' ) ? 'Barang Medis' : 'Barang Non Medis';
        $this->kode_gudang_nm = '070101';
        $this->kode_gudang = '060201';

    }

    public function index() { 
        /*define variable data*/
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show(),
            'flag_string' => $_GET['flag']
        );
        /*load view index*/
        $this->load->view('master/Inv_master_barang/index', $data);
    }

    public function form($id='')
    {
        /*if id is not null then will show form edit*/
        if( $id != '' ){
            /*breadcrumbs for edit*/
            $this->breadcrumbs->push('Edit '.strtolower($this->title).'', 'Inv_master_barang/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
            /*get value by id*/
            $data['value'] = $this->Inv_master_barang->get_by_id($id); 
            /*initialize flag for form*/
            $data['flag'] = "update";
        }else{
            /*breadcrumbs for create or add row*/
            $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Inv_master_barang/'.strtolower(get_class($this)).'/form');
            /*initialize flag for form add*/
            $data['flag'] = "create";
        }
        // echo '<pre>'; print_r($data['value']);
        $data['flag_string'] = $_GET['flag'];
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('master/Inv_master_barang/form', $data);
    }
    /*function for view data only*/
    public function show($id)
    {
        /*breadcrumbs for view*/
        $this->breadcrumbs->push('View '.strtolower($this->title).'', 'Inv_master_barang/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*define data variabel*/
        $data['value'] = $this->Inv_master_barang->get_by_id($id);
        $data['history_po'] = $this->Inv_master_barang->get_history_po($id);
        $data['title'] = $this->title;
        $data['flag'] = "read";
        $data['flag_string'] = $_GET['flag'];
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('master/Inv_master_barang/form_show', $data);
    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->Inv_master_barang->get_datatables();
        
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            if( $_GET['flag'] == 'medis' ){
                $txt_keterangan = ($row_list->nama_generik)?$row_list->nama_generik.' <br>':'';
                $txt_keterangan .= ($row_list->nama_layanan)?$row_list->nama_layanan.' <br>':'';
                $txt_keterangan .= ($row_list->nama_pabrik)?$row_list->nama_pabrik.' <br>':'';
                $txt_keterangan .= ($row_list->nama_jenis)?$row_list->nama_jenis.' <br>':'';
                $txt_keterangan .= ($row_list->jenis_barang)?$row_list->jenis_barang:'';
                $txt_gol = $row_list->nama_golongan.'<br>('.strtolower($row_list->nama_sub_golongan).')';
            }
            if( $_GET['flag'] == 'non_medis' ){
                $txt_keterangan = ($row_list->nama_pabrik)?$row_list->nama_pabrik.' <br>':'';
                $txt_gol = $row_list->nama_golongan.'<br>('.strtolower($row_list->nama_sub_golongan).')';
            }
            $row[] = '<div class="center">
                        <label class="pos-rel">
                            <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->kode_brg.'"/>
                            <span class="lbl"></span>
                        </label>
                      </div>';
            $row[] = '';
            $row[] = $row_list->kode_brg;
            $row[] = '<div class="center">
                        <div class="btn-group">
                        <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">
                            <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-inverse">
                            <li>'.$this->authuser->show_button('inventory/master/Inv_master_barang?flag='.$_GET['flag'].'','R',$row_list->kode_brg,67).'</li>
                            <li>'.$this->authuser->show_button('inventory/master/Inv_master_barang?flag='.$_GET['flag'].'','U',$row_list->kode_brg,67).'</li>
                            <li>'.$this->authuser->show_button('inventory/master/Inv_master_barang?flag='.$_GET['flag'].'','D',$row_list->kode_brg,6).'</li>
                        </ul>
                        </div>
                    </div>';
            
            $link_image = ( $row_list->path_image != NULL ) ? PATH_IMG_MST_BRG.$row_list->path_image : PATH_IMG_MST_BRG.'no-image.jpg' ;
            $row[] = '<div class="center"><a href="'.base_url().$link_image.'" target="_blank"><img src="'.base_url().$link_image.'" width="100px"></a></div>';
            $is_prb = isset($row_list->is_prb) ? ($row_list->is_prb == 'Y') ? '<span style="background: gold; color: black; font-weight: bold; font-size: 10px">PRB</span>' : '' : '';
            $is_kronis = isset($row_list->is_kronis) ? ($row_list->is_kronis == 'Y') ? '<span style="background: green; color: white; font-weight: bold; font-size: 10px">Kronis</span>' : '' : '';
            $row[] = 'Kategori : '.ucfirst($row_list->nama_kategori).'<br><b>'.$row_list->kode_brg.'</b><br>'.$row_list->nama_brg.'<br>'.$is_prb.' '.$is_kronis;
            $row[] = ucfirst($txt_gol).'<br><span style="color: green">Rak : '.$row_list->rak.'</span>';
            $row[] = '<div class="center">'.strtoupper($row_list->satuan_besar).'/'.strtoupper($row_list->satuan_kecil).'</div>';
            $row[] = '<div class="center">'.$row_list->content.'</div>';
            $row[] = '<div align="right">'.number_format($row_list->harga_beli).'</div>';
            
            $row[] = '<div>'.$row_list->spesifikasi.'<br>'.$this->logs->show_logs_record_datatable($row_list).'</div>';
            $status_aktif = ($row_list->is_active == 1) ? '<span class="label label-sm label-success">Active</span>' : '<span class="label label-sm label-danger">Not active</span>';
            $row[] = '<div class="center">'.$status_aktif.'</div>';
                   
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Inv_master_barang->count_all(),
                        "recordsFiltered" => $this->Inv_master_barang->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function show_detail( $id )
    {   
        $table = ($_GET['flag'] == 'non_medis') ? 'mt_barang_nm' : 'mt_barang' ;
        $fields = $this->master->list_fields( $table );
        // print_r($fields);die;
        $data = $this->Inv_master_barang->get_by_id($id);
        $html = $this->master->show_detail_row_table( $fields, $data );      

        echo json_encode( array('html' => $html) );
    }

    public function process()
    {
        // print_r($_POST);die;
        $this->load->library('form_validation');
        $val = $this->form_validation;
	
        $val->set_rules('flag','flag', 'trim|required');
        $val->set_rules('kode_kategori','Kategori', 'trim|required');
        $val->set_rules('kode_golongan','Golongan', 'trim|required');
        $val->set_rules('kode_sub_gol','Sub Golongan', 'trim|required');
        $val->set_rules('id','Kode Barang', 'trim|required');
        $val->set_rules('nama_brg','Nama Barang', 'trim|required');
        $val->set_rules('content','Rasio Kemasan', 'trim|required');
        $val->set_rules('satuan_besar','Satuan Besar', 'trim|required');
        $val->set_rules('satuan_kecil','Satuan Kecil', 'trim|required');
        $val->set_rules('is_active','Status Aktif', 'trim|required');
        $val->set_rules('spesifikasi','Spesifikasi', 'trim|required');
        $val->set_rules('rak','rak', 'trim');
        $val->set_rules('id_pabrik','Pabrikan', 'trim');

        if( $_POST['flag'] == 'medis' ){
            $val->set_rules('kode_jenis', '','trim');
            $val->set_rules('kode_generik', '','trim|required');
            $val->set_rules('kode_layanan', '','trim');
            $val->set_rules('jenis_obat', '','trim');
            $val->set_rules('obat_khusus', '','trim');
            $val->set_rules('is_kronis', '','trim');
            $val->set_rules('is_prb', '','trim');
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
            $id = ( count($this->Inv_master_barang->get_by_id( $_POST['id'] )) > 0 ) ? $this->regex->_genRegex($this->input->post('id'),'RGXQSL'):'0';
            
            $table = ( $val->set_value('flag') == 'medis' ) ? 'mt_barang' : 'mt_barang_nm' ;
            
            $dataexc = array(
                'kode_brg' => $this->regex->_genRegex( $val->set_value('id'), 'RGXQSL' ),
                'nama_brg' => $this->regex->_genRegex( $val->set_value('nama_brg'), 'RGXQSL' ),
                'kode_kategori' => $this->regex->_genRegex( $val->set_value('kode_kategori'), 'RGXQSL' ),
                'satuan_besar' => $this->regex->_genRegex( $val->set_value('satuan_besar'), 'RGXQSL' ),
                'satuan_kecil' => $this->regex->_genRegex( $val->set_value('satuan_kecil'), 'RGXQSL' ),
                'kode_golongan' => $this->regex->_genRegex( $val->set_value('kode_golongan'), 'RGXQSL' ),
                'kode_sub_golongan' => $this->regex->_genRegex( $val->set_value('kode_sub_gol'), 'RGXQSL' ),
                'content' => $this->regex->_genRegex( $val->set_value('content'), 'RGXQSL' ),
                'spesifikasi' => $this->regex->_genRegex( $val->set_value('spesifikasi'), 'RGXQSL' ),
                'rak' => $this->regex->_genRegex( $val->set_value('rak'), 'RGXQSL' ),
                'is_active' => $this->regex->_genRegex( $val->set_value('is_active'), 'RGXQSL' ),
            );
            
            if( $_POST['flag'] == 'medis' ){
                $flag_medis = ( $_POST['kode_kategori'] == 'D' ) ? '0' : '1' ;
                $dataexc['kode_jenis'] = $this->regex->_genRegex( $val->set_value('kode_jenis'), 'RGXQSL' );
                $dataexc['kode_generik'] = $this->regex->_genRegex( $val->set_value('kode_generik'), 'RGXQSL' );
                $dataexc['kode_layanan'] = $this->regex->_genRegex( $val->set_value('kode_layanan'), 'RGXQSL' );
                $dataexc['flag_medis'] = $this->regex->_genRegex( $flag_medis, 'RGXQSL' );
                $dataexc['jenis_obat'] = $this->regex->_genRegex( $val->set_value('jenis_obat'), 'RGXQSL' );
                $dataexc['obat_khusus'] = $this->regex->_genRegex( $val->set_value('obat_khusus'), 'RGXQSL' );
                $dataexc['id_pabrik'] = $this->regex->_genRegex( $val->set_value('id_pabrik'), 'RGXQSL' );
                $dataexc['is_kronis'] = $this->regex->_genRegex( $val->set_value('is_kronis'), 'RGXQSL' );
                $dataexc['is_prb'] = $this->regex->_genRegex( $val->set_value('is_prb'), 'RGXQSL' );
            }else{
                $dataexc['id_pabrik'] = $this->regex->_genRegex( $val->set_value('id_pabrik'), 'RGXQSL' );
            }

            // upload file image
            if(isset($_FILES['path_image'] ['name']) AND $_FILES['path_image'] ['name'] != ''){
                /*hapus dulu file yang lama*/
                if( $id != 0 ){
                    $res_dt = $this->Inv_master_barang->get_by_id($id);
                    if($res_dt->path_image != NULL){
                        if (file_exists(PATH_IMG_MST_BRG.$res_dt->path_image.'')) {
                            unlink(PATH_IMG_MST_BRG.$res_dt->path_image.'');
                        }    
                    }
                }
                $path_url =  PATH_IMG_MST_BRG.'/'.$_POST['flag'].'/';
                $dataexc['path_image'] = $_POST['flag'].'/'.$this->upload_file->doUpload('path_image', $path_url);
            }

            // print_r($dataexc);die;

            if( $id == '0' ){
                $dataexc['created_date'] = date('Y-m-d H:i:s');
                $dataexc['created_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                /*save post data*/
                $this->db->insert( $table, $dataexc );
                $newId = $dataexc['kode_brg'];
                /*save logs*/
                $this->logs->save( $table, $newId, 'insert new record on '.$this->title.' module', json_encode($dataexc),'kode_brg');
                
            }else{
                $dataexc['updated_date'] = date('Y-m-d H:i:s');
                $dataexc['updated_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                /*update record*/
                // print_r($dataexc);die;
                $this->Inv_master_barang->update($table, array('kode_brg' => $id), $dataexc);
                $newId = $id;
                /*save logs*/
                $this->logs->save($table, $newId, 'update record on '.$this->title.' module', json_encode($dataexc),'kode_brg');
            }

            // default kode gudang
            $kode_gudang = ( $val->set_value('flag') == 'medis' ) ? $this->kode_gudang : $this->kode_gudang_nm ;

            // update or insert rekap stok
            $t_rekap_stok = ( $val->set_value('flag') == 'medis' ) ? 'mt_rekap_stok' : 'mt_rekap_stok_nm' ;

            $dt_rekap_stok = $this->db->get_where($t_rekap_stok, array('kode_brg' => $dataexc['kode_brg'], 'kode_bagian_gudang' => $kode_gudang ) );
            // print_r($dt_rekap_stok->row());
            if ( $dt_rekap_stok->num_rows() > 0  ) {
                $dt_rekap = $dt_rekap_stok->row();
                // update rekap stok
                $arr_dt_rekap_stok["jml_sat_kcl"] = (int)$dt_rekap->jml_sat_kcl;
                $arr_dt_rekap_stok["harga_beli"] = (int)$dt_rekap->harga_beli;
                $arr_dt_rekap_stok["harga_persediaan"] = (int)$dt_rekap->harga_persediaan;
                $arr_dt_rekap_stok['updated_date'] = date('Y-m-d H:i:s');
                $arr_dt_rekap_stok['updated_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                if( $val->set_value('flag') == 'medis' ){
                    $arr_dt_rekap_stok['id_profit'] = $_POST['id_profit'];
                }
                $this->db->update($t_rekap_stok, $arr_dt_rekap_stok , array('kode_rekap_stok' => $dt_rekap->kode_rekap_stok ) );
                /*save logs*/
                $this->logs->save($t_rekap_stok, $dt_rekap->kode_rekap_stok, 'update record on '.$this->title.' module', json_encode($arr_dt_rekap_stok),'kode_rekap_stok');
                $last_kode_rekap_stok = $dt_rekap->kode_rekap_stok;
            
            }else{
                // insert mt_rekap_stok
                $arr_dt_rekap_stok = array(
                    'kode_rekap_stok' => $this->master->get_max_number($t_rekap_stok, 'kode_rekap_stok'),
                    'kode_brg' => $newId,
                    'jml_sat_kcl' => 0,
                    'stok_minimum' => $_POST['stok_minimum'],
                    'stok_maksimum' => $_POST['stok_maksimum'],
                    'kode_bagian_gudang' => $kode_gudang, 
                    'created_date' => date('Y-m-d H:i:s'),
                    'created_by' => json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL'))),
                );

                if( $val->set_value('flag') == 'medis' ){
                    $arr_dt_rekap_stok['id_profit'] = $_POST['id_profit'];
                    $arr_dt_rekap_stok['id_obat'] = $this->master->get_max_number($t_rekap_stok, 'id_obat');
                }

                $this->db->insert($t_rekap_stok, $arr_dt_rekap_stok );
                /*save logs*/
                $this->logs->save($t_rekap_stok, $arr_dt_rekap_stok['kode_rekap_stok'], 'insert new record on '.$this->title.' module', json_encode($arr_dt_rekap_stok),'kode_rekap_stok');

                $last_kode_rekap_stok = $arr_dt_rekap_stok['kode_rekap_stok'];
            
            }

            // insert or update mt_depo_stok
            $mt_depo_stok = ( $val->set_value('flag') == 'medis' ) ? 'mt_depo_stok' : 'mt_depo_stok_nm' ;

            $dt_depo_stok = $this->db->get_where($mt_depo_stok, array('kode_brg' => $dataexc['kode_brg'], 'kode_bagian' => $kode_gudang ) );

            $arr_dt_depo = array(
                'stok_minimum' => $this->regex->_genRegex($_POST['stok_minimum'],'RGXQSL'),
                'stok_maksimum' => $this->regex->_genRegex($_POST['stok_maksimum'],'RGXQSL'),
                'kode_brg' => $this->regex->_genRegex($dataexc['kode_brg'],'RGXQSL'),
                'kode_bagian' => $this->regex->_genRegex($kode_gudang,'RGXQSL'),
                'kode_rekap_stok' => $this->regex->_genRegex($last_kode_rekap_stok,'RGXINT'),
            );

            if ( $dt_depo_stok->num_rows() > 0  ) {
                $dt_depo = $dt_depo_stok->row();
                $arr_dt_depo['updated_date'] = date('Y-m-d H:i:s');
                $arr_dt_depo['updated_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));

                $this->db->update($mt_depo_stok, $arr_dt_depo, array('kode_depo_stok' => $dt_depo->kode_depo_stok ) );
                $last_kode_depo_stok = $dt_depo->kode_depo_stok;
                /*save logs*/
                $this->logs->save($mt_depo_stok, $last_kode_depo_stok, 'insert new record on '.$this->title.' module', json_encode($arr_dt_depo),'kode_depo_stok');

            }else{
                $arr_dt_depo['kode_depo_stok'] = $this->regex->_genRegex($this->master->get_max_number($mt_depo_stok, 'kode_depo_stok'),'RGXINT');
                $arr_dt_depo['created_date'] = date('Y-m-d H:i:s');
                $arr_dt_depo['created_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                
                if( $val->set_value('flag') == 'medis' ){
                    $this->db->query('SET IDENTITY_INSERT '.$mt_depo_stok.' ON');
                }

                $this->db->insert($mt_depo_stok, $arr_dt_depo);
                $last_kode_depo_stok = $arr_dt_depo['kode_depo_stok'];
                /*save logs*/
                $this->logs->save($mt_depo_stok, $last_kode_depo_stok, 'insert new record on '.$this->title.' module', json_encode($arr_dt_depo),'kode_depo_stok');
            
            }
            
            // cek tc_kartu_stok
            $tc_kartu_stok = ( $val->set_value('flag') == 'medis' ) ? 'tc_kartu_stok' : 'tc_kartu_stok_nm' ;
            $ex_kartu_stok_dt = $this->db->get_where($tc_kartu_stok, array('kode_brg' => $dataexc['kode_brg'], 'kode_bagian' => $kode_gudang) );

            if( $ex_kartu_stok_dt->num_rows() == 0 ){
                $arr_dt_kartu_stok = array(
                    'id_kartu' => $this->master->get_max_number($tc_kartu_stok, 'id_kartu'),
                    'tgl_input' => date('Y-m-d H:i:s'),
                    'kode_brg' => $this->regex->_genRegex($dataexc['kode_brg'],'RGXQSL'),
                    'stok_awal' => 0,
                    'pemasukan' => 0,
                    'pengeluaran' => 0,
                    'stok_akhir' => 0,
                    'jenis_transaksi' => 0,
                    'keterangan' => 0,
                    'petugas' => 0,
                    'kode_bagian' => $kode_gudang,
                );
                $this->db->insert($tc_kartu_stok, $arr_dt_kartu_stok);
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

    public function delete()
    {
        $id=$this->input->post('ID')?$this->regex->_genRegex($this->input->post('ID',TRUE),'RGXQSL'):null;
        $toArray = explode(',',$id);
        if($id!=null){
            if($this->Inv_master_barang->delete_by_id($toArray)){
                // $this->logs->save('Inv_master_barang', $id, 'delete record', '', 'kode_brg');
                echo json_encode(array('status' => 200, 'message' => 'Proses Hapus Data Berhasil Dilakukan'));

            }else{
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Hapus Data Gagal Dilakukan'));
            }
        }else{
            echo json_encode(array('status' => 301, 'message' => 'Tidak ada item yang dipilih'));
        }
        
    }

    public function find_data()
    {   
        $output = array( "data" => http_build_query($_POST) . "\n" );
        echo json_encode($output);
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

        $result = $this->Inv_master_barang->get_by_id($_GET['id']);
        // print_r($result);die;
        $table = ($_GET['flag']=='non_medis')?'mt_barang_nm':'mt_barang';
        $title = ($_GET['flag']=='non_medis')?'Barang Non Medis':'Barang Medis';
        $subtitle = str_replace('_',' ',$_GET['flag']);
        $data = array(
            'barang' => $result,
            'flag' => $_GET['flag'],
            'title' => $title,
            'subtitle' => $subtitle,
            'count' => count($result),
            );
        // echo '<pre>'; print_r($data);
        if(isset($_GET['tipe']) AND $_GET['tipe']=='kartu_stok'){
            $this->load->view('inventory/master/Inv_master_barang/print_label_kartu_stok', $data);
        }else{
            $this->load->view('inventory/master/Inv_master_barang/print_label', $data);
        }
    }


}

/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
