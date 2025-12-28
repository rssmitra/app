<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Po_penerbitan extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'purchasing/po/Po_penerbitan');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('purchasing/po/Po_penerbitan_model', 'Po_penerbitan');
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
        );
        /*load view index*/
        $this->load->view('po/Po_penerbitan/index_2', $data);
    }

    public function view_data() { 
        //echo '<pre>';print_r($this->session->all_userdata());
        /*define variable data*/
        $title = ($_GET['flag']=='medis')?'Medis':'Non Medis';
        /*define variable data*/
        $data = array(
            'title' => 'Penerbitan PO '.$title,
            'breadcrumbs' => $this->breadcrumbs->show(),
            'flag' => $_GET['flag'],
        );
        /*load view index*/
        $this->load->view('po/Po_penerbitan/index', $data);
    }

    public function form($id='')
    {
        $data['string'] = isset($_GET['flag'])?$_GET['flag']:'';
        /*if id is not null then will show form edit*/
        if( $id != '' ){
            /*breadcrumbs for edit*/
            $this->breadcrumbs->push('Edit '.strtolower($this->title).'', 'Po_penerbitan/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
            /*get value by id*/
            $data['value'] = $this->Po_penerbitan->get_by_id($id); 
            // echo '<pre>'; print_r($data);die;
            /*initialize flag for form*/
            $data['flag'] = "update";
        }else{
            /*breadcrumbs for create or add row*/
            $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Po_penerbitan/'.strtolower(get_class($this)).'/form');
            /*initialize flag for form add*/
            $data['flag'] = "create";
        }
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('po/Po_penerbitan/form', $data);
    }
    /*function for view data only*/
    public function show($id)
    {
        $data['string'] = isset($_GET['flag'])?$_GET['flag']:'';
        /*breadcrumbs for view*/
        $this->breadcrumbs->push('View '.strtolower($this->title).'', 'Po_penerbitan/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*define data variabel*/
        //$data['value'] = $this->Po_penerbitan->get_by_id($id);
        $data['title'] = $this->title;
        $data['flag'] = "read";
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('po/Po_penerbitan/form', $data);
    }

    public function process()
    {
        
        $this->load->library('form_validation');
        $val = $this->form_validation;
        
        // print_r($_POST);die;
        $val->set_rules('kodesupplier', 'Supplier', 'trim|required');
        $val->set_rules('tgl_po', 'Tanggal PO', 'trim|required');
        $val->set_rules('tgl_kirim', 'Tanggal Kirim', 'trim|required');
        $val->set_rules('diajukan_oleh', 'Diajukan Oleh', 'trim|required');
        $val->set_rules('disetujui_oleh', 'Disetujui Oleh', 'trim|required');
        $val->set_rules('krs', 'KARS', 'trim|required');
        $val->set_rules('sipa', 'SIK AA', 'trim|required');
        $val->set_rules('term_of_pay', 'Syarat Pembayaran', 'trim|required');
        $val->set_rules('is_checked[]', 'is_checked', 'trim|required');
        
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

            $table = ($_POST['flag']=='medis')?'tc_po':'tc_po_nm';
            $tc_permohonan_det = ($_POST['flag']=='medis')?'tc_permohonan_det':'tc_permohonan_nm_det';
            // data header po
            $dataexc = array(
                'no_po' => $this->regex->_genRegex($_POST['no_po'],'RGXQSL'),
                'no_urut_periodik' => $this->regex->_genRegex($_POST['no_urut_periodik'],'RGXQSL'),
                'sipa' => $this->regex->_genRegex($val->set_value('sipa'),'RGXQSL'),
                'tgl_po' => $this->regex->_genRegex($val->set_value('tgl_po'),'RGXQSL'),
                'ppn' => $_POST['total_ppn_val'],
                'total_sbl_ppn' => $_POST['total_dpp_val'],
                'total_stl_ppn' => $_POST['total_nett_val'],
                'discount_harga' => $_POST['total_potongan_diskon_val'],
                'user_id' => $this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'),
                'kodesupplier' => $this->regex->_genRegex($val->set_value('kodesupplier'),'RGXINT'),
                'term_of_pay' => $this->regex->_genRegex($val->set_value('term_of_pay'),'RGXQSL'),
                'diajukan_oleh' => $this->regex->_genRegex($val->set_value('diajukan_oleh'),'RGXQSL'),
                'sipa' => $this->regex->_genRegex($val->set_value('sipa'),'RGXQSL'),
                'disetujui_oleh' => $this->regex->_genRegex($val->set_value('disetujui_oleh'),'RGXQSL'),
                'tgl_kirim' => $this->regex->_genRegex($val->set_value('tgl_kirim'),'RGXQSL'),
                'krs' => $this->regex->_genRegex($val->set_value('krs'),'RGXQSL'),
                'jenis_po' => $this->regex->_genRegex($_POST['jenis_po'],'RGXQSL'),
            );
            
            if($id==0){
                $dataexc['created_date'] = date('Y-m-d H:i:s');
                $dataexc['created_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                $newId = $this->Po_penerbitan->save($table, $dataexc);
                /*save logs*/
                $this->logs->save($table, $newId, 'insert new record on '.$this->title.' module', json_encode($dataexc),'id_tc_po');
            }else{
                $dataexc['updated_date'] = date('Y-m-d H:i:s');
                $dataexc['updated_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                // print_r($dataexc);die;
                /*update record*/
                $this->Po_penerbitan->update($table, array('id_tc_po' => $id), $dataexc);
                $newId = $id;
                $this->logs->save($table, $newId, 'update record'.$this->title.' module', json_encode($dataexc), 'id_tc_po');
            }
            
            // define table permohonan
            $id_tc_permohonan_det = array();
            $table_permohonan = ($_POST['flag']=='medis')?'tc_permohonan':'tc_permohonan_nm';
            $table_barang = ($_POST['flag']=='medis')?'mt_barang':'mt_barang_nm';
            $table_rekap_stok = ($_POST['flag']=='medis')?'mt_rekap_stok':'mt_rekap_stok_nm';

            // jika action adalah penerbitan po baru
            if( $_POST['action'] == 'create'){

                // loop data by checked form
                foreach( $_POST['is_checked'] as $row_checked ){
                    // get id_tc_permohonan_det
                    foreach( $_POST['id_tc_permohonan_det'][$row_checked] as $key_id=>$row_id_tc_det ){
                        $id_tc_permohonan[] = $_POST['id_tc_permohonan'][$row_checked];
                        $id_tc_permohonan_det[] = $row_id_tc_det;
                        // insert tc_po_det
                        $config = array(
                            'kode_brg' => $row_checked,
                            'hna' => $_POST['harga_satuan_val'][$row_checked],
                            'disc' => $_POST['diskon'][$row_checked],
                            'disc_rp' => $_POST['potongan_diskon'][$row_checked],
                            'ppn' => $_POST['ppn'][$row_checked],
                            'qty' => $_POST['jml_permohonan'][$row_checked],
                            'rasio' => $_POST['rasio'][$row_checked],
                        );
                        // eksekusi rumus untuk mencari harga
                        $harga[$row_checked] = $this->master->rumus_harga($config);
                    }
                    // print_r($harga);die;

                    $total_potongan_diskon = $harga[$row_checked]['disc_rp'];

                    $insertBatch[] = array(
                        "id_tc_po" => $newId,
                        "id_tc_permohonan_det" => $row_id_tc_det,
                        "id_tc_permohonan" => $_POST['id_tc_permohonan'][$row_checked],
                        "kode_brg" => $row_checked,
                        "jumlah_besar" => (int)$_POST['jml_permohonan'][$row_checked],
                        "jumlah_besar_acc" => (float)$_POST['jml_permohonan'][$row_checked],
                        "content" => $_POST['rasio'][$row_checked],
                        "sipa" => $_POST['sipa'],
                        "diajukan_oleh" => $_POST['diajukan_oleh'],
                        "disetujui_oleh" => $_POST['disetujui_oleh'],
                        "harga_satuan" => $harga[$row_checked]['hna'],
                        "harga_satuan_netto" => $harga[$row_checked]['harga_satuan_netto'],
                        "jumlah_harga" => $harga[$row_checked]['total_harga_satuan'],
                        "jumlah_harga_netto" => $harga[$row_checked]['total_harga_netto'],
                        "discount" => $harga[$row_checked]['disc'],
                        "discount_rp" => $total_potongan_diskon,
                        "ppn" => $harga[$row_checked]['ppn'],
                    );
                }

                // print_r($insertBatch);die;

                // truncate data by id_tc_po then insert
                $this->db->delete($table.'_det', array('id_tc_po' => $newId) );

                // insert batch
                $this->db->insert_batch($table.'_det', $insertBatch);
                $this->db->trans_commit();

            }

            if( $_POST['action'] == 'revisi'){
                
                foreach( $_POST['is_checked'] as $row_checked ){
                    // hanya barang yang belum diterima yang diproses
                    
                      // print_r($_POST['brg_diterima'][$row_checked]);die;
                      // get id_tc_permohonan_det
                      foreach( $_POST['id_tc_permohonan_det'][$row_checked] as $row_id_tc_det ){
                        $id_tc_permohonan[] = $_POST['id_tc_permohonan'][$row_checked];
                        $id_tc_permohonan_det[] = $row_id_tc_det;
                      
                        // gte data detail permohonan
                        $permohonan_det = $this->db->join($table.'_det',''.$table.'_det.id_tc_permohonan_det='.$table_permohonan.'_det.id_tc_permohonan_det','left')->get_where($table_permohonan.'_det', array(''.$table_permohonan.'_det.id_tc_permohonan_det' => $row_id_tc_det) )->row();
                        // print_r($permohonan_det);die;
                        // update tc_po_det
                        // insert tc_po_det
                        $config = array(
                            'kode_brg' => $row_checked,
                            'hna' => $_POST['harga_satuan_val'][$row_checked],
                            'disc' => $_POST['diskon'][$row_checked],
                            'disc_rp' => $_POST['potongan_diskon'][$row_checked],
                            'ppn' => $_POST['ppn'][$row_checked],
                            'qty' => $_POST['jml_permohonan'][$row_checked],
                            'rasio' => $_POST['rasio'][$row_checked],
                        );
                        // eksekusi rumus untuk mencari harga
                        $harga[$row_checked] = $this->master->rumus_harga($config);

                        // echo "<pre>";print_r($harga);die;
                        $total_potongan_diskon = $harga[$row_checked]['disc_rp'];
                        $updateBatch = array(
                            "id_tc_po" => $newId,
                            "id_tc_permohonan_det" => $row_id_tc_det,
                            "id_tc_permohonan" => $_POST['id_tc_permohonan'][$row_checked],
                            "kode_brg" => $row_checked,
                            // "jumlah_besar" => $_POST['jml_permohonan'][$row_checked],
                            "jumlah_besar_acc" => $_POST['jml_permohonan'][$row_checked],
                            "content" => $_POST['rasio'][$row_checked],
                            "sipa" => $_POST['sipa'],
                            "diajukan_oleh" => $_POST['diajukan_oleh'],
                            "disetujui_oleh" => $_POST['disetujui_oleh'],
                            "harga_satuan" => $harga[$row_checked]['hna'],
                            "jumlah_harga" => $harga[$row_checked]['total_harga_satuan'],
                            "harga_satuan_netto" => $harga[$row_checked]['harga_satuan_netto'],
                            "jumlah_harga_netto" => $harga[$row_checked]['total_harga_netto'],
                            "discount" => $harga[$row_checked]['disc'],
                            "discount_rp" => $total_potongan_diskon,
                            "ppn" => $harga[$row_checked]['ppn'],
                        );

                        // if(isset($_POST['brg_diterima'][$row_checked]) AND $_POST['brg_diterima'][$row_checked] != ''){
                            // print_r($_POST);die;
                          $id_tc_permohonan_det[] = $row_id_tc_det;
                          $this->db->update($table.'_det', $updateBatch, array('id_tc_po_det' => $permohonan_det->id_tc_po_det) );
                        //   echo '<pre>';print_r($this->db->last_query());die;
                          $this->db->trans_commit();
                        // }
                        
                      }
                  
                }
                // print_r($id_tc_permohonan_det);die;
            }
            // update status permohonan detail sudah di PO kan
            $this->db->where_in('id_tc_permohonan_det', $id_tc_permohonan_det)->update($table_permohonan.'_det', array('status_po' => 1) );
            
            // update flag process
            $this->Po_penerbitan->update_flag_proses($id_tc_permohonan, $_POST['flag']);
            
            // print_r($id_tc_permohonan);die;
            
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

    public function get_data()
    {
        /*get data from model*/
        $list = $this->Po_penerbitan->get_datatables();
        
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center">
                        <label class="pos-rel">
                            <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->id_tc_permohonan.'"/>
                            <span class="lbl"></span>
                        </label>
                      </div>';
            $row[] = '';
            $row[] = $row_list->id_tc_permohonan;
            $row[] = '<div class="center">'.$row_list->id_tc_permohonan.'</div>';
            $row[] = $row_list->kode_permohonan;
            $row[] = $this->tanggal->formatDate($row_list->tgl_permohonan);
            // log
            $log = json_decode($row_list->created_by);
            $petugas = isset($log->fullname)?$log->fullname:$row_list->username;
            $row[] = '<div class="center">'.ucwords($petugas).'</div>';
            $row[] = '<div class="center">'.$row_list->jenis_permohonan_name.'</div>';
            $row[] = '<div class="left">'.$row_list->no_acc.'</div>';
            $row[] = $this->tanggal->formatDate($row_list->tgl_acc);

            if ($row_list->status_batal=="0") {
                $status = '<div class="center"><i class="fa fa-check-circle bigger-150 green"></i></div>';
                $text = 'Disetujui';
            } elseif ($row_list->status_batal=="1") {
                $status = '<div class="center"><i class="fa fa-times-circle bigger-150 red"></i></div>';
                $text = 'Tidak disetujui';
            } else {
                $status = '<div class="center"><i class="fa fa-exclamation-triangle bigger-150 orange"></i></div>';
                $text = 'Menunggu Persetujuan';
            }

            $row[] = '<div class="center">'.$status.'</div>';
            $row[] = $text.' '.ucfirst($row_list->user_acc_name);
            $row[] = '<div class="center"><a href="#" onclick="rollback('.$row_list->id_tc_permohonan.')" class="btn btn-xs btn-danger" title="Rollback"><i class="fa fa-refresh"></i></a></div>';
                   
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Po_penerbitan->count_all(),
                        "recordsFiltered" => $this->Po_penerbitan->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function get_detail($id){
        $flag = $_GET['flag'];
        
        $result = $this->Po_penerbitan->get_detail_brg_permintaan($flag, $id);
        // echo '<pre>';print_r($result);die;
        $data = array(
            'dt_detail_brg' => $result,
            'flag' => $flag,
            'id' => $id,
            );
            // echo $result[0]->jml_besar_decimal;
        $temp_view = $this->load->view('po/Po_penerbitan/detail_table_view', $data, true);
        echo json_encode( array('html' => $temp_view) );
    }

    public function get_detail_brg_po(){
        /*string to array*/
        $arr_id = explode(',', $_POST['ID']);
        $url_qry = http_build_query($arr_id);
        echo json_encode(array('params' => $url_qry));
    }

    public function create_po($flag){
        
        $format = ($flag=='medis')?'PO':'PO-NM';
        $table = ($flag=='medis')?'tc_po':'tc_po_nm';
        $title = ($flag=='medis')?'Medis':'Non Medis';
        /*title header*/
        $data['title'] = 'Penerbitan PO '.$title;
        /*get value by id*/
        $format_no_po = $this->master->get_max_number_per_month($table, 'no_urut_periodik', 'tgl_po');
        $dt_detail_brg = $this->Po_penerbitan->get_detail_brg_permintaan($flag, $_GET);
         // echo '<pre>';print_r($dt_detail_brg);die;

        $data['no_po'] = $format.'/'.$format_no_po['format'];
        $data['no_urut_periodik'] = $format_no_po['max_num'];
        $data['flag'] = $flag;
        $getData = array();
        foreach($dt_detail_brg as $row){
            if( $row->status_po != 1 ){
                $history = $this->Po_penerbitan->getReferensiPO($row->kode_brg, $table);
                // echo '<pre>';print_r($this->db->last_query());die;
                $history_po[$row->kode_brg] = $history;
                $getData[$row->kode_brg][] = $row;
                
            }
        }
        $data['dt_detail_brg'] = $getData;
        $data['history_po'] = $history_po;
        // echo '<pre>';print_r($data['dt_detail_brg']);die;
        $data['view_brg_po'] = $this->load->view('po/Po_penerbitan/view_brg_po', $data, true);

        $this->load->view('po/Po_penerbitan/form', $data, false);
    }

    public function find_data()
    {   
        $output = array( "data" => http_build_query($_POST) . "\n" );
        echo json_encode($output);
    }

    public function print_preview(){

        $table = ($_GET['flag']=='non_medis')?'tc_po_nm':'tc_po';
        $title = ($_GET['flag']=='non_medis')?'Gudang Non Medis':'Gudang Medis';
        $result = $this->Po_penerbitan->get_po($_GET['flag'], $_GET['ID']);
        $getData = array();
        foreach($result as $row_dt){
            $getData[$row_dt->kode_brg][] = $row_dt;
        }
        $data = array(
            'po' => $result[0],
            'po_data' => $getData,
            'flag' => $_GET['flag'],
            'title' => $title,
            );
        // echo '<pre>'; print_r($data);die;
        $this->load->view('po/Po_penerbitan/print_preview', $data);
    }

    public function usulan_preview(){

        $table = ($_GET['flag']=='non_medis')?'tc_po_nm':'tc_po';
        $title = ($_GET['flag']=='non_medis')?'Gudang Non Medis':'Gudang Medis';
        $result = $this->Po_penerbitan->get_po($_GET['flag'], $_GET['ID']);
        
		// echo '<pre>'; print_r($this->db->last_query());die;
        $getData = array();
        foreach($result as $row_dt){
            $getData[$row_dt->kode_brg][] = $row_dt;
        }
        $data = array(
            'po' => $result[0],
            'po_data' => $getData,
            'flag' => $_GET['flag'],
            'title' => $title,
            );
        // echo '<pre>'; print_r($data);die;
        $this->load->view('po/Po_penerbitan/usulan_preview', $data);
    }

    public function rollback()
    {
        
        if($_POST['ID']!=null){
            if($this->Po_penerbitan->rollback_po($_POST['flag'], $_POST['ID'])){
                echo json_encode(array('status' => 200, 'message' => 'Proses Rollback Data Berhasil Dilakukan'));

            }else{
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Rollback Data Gagal Dilakukan'));
            }
        }else{
            echo json_encode(array('status' => 301, 'message' => 'Tidak ada item yang dipilih'));
        }
    }

    public function rollback_status()
    {
        
        if($_POST['ID']!=null){
            if($this->Po_penerbitan->rollback_status($_POST['flag'], $_POST['ID'])){
                echo json_encode(array('status' => 200, 'message' => 'Proses Rollback Data Berhasil Dilakukan'));

            }else{
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Rollback Data Gagal Dilakukan'));
            }
        }else{
            echo json_encode(array('status' => 301, 'message' => 'Tidak ada item yang dipilih'));
        }
    }
    

}


/* End of file example.php */
/* Location: ./application/modules/example/controllers/example.php */
