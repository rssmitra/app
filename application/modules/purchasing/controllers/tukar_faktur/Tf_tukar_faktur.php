<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Tf_tukar_faktur extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'purchasing/tukar_faktur/Tf_tukar_faktur');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('purchasing/tukar_faktur/Tf_tukar_faktur_model', 'Tf_tukar_faktur');
        $this->load->model('purchasing/penerimaan/Riwayat_penerimaan_brg_model', 'Riwayat_penerimaan_brg');
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
            'title' => 'Tukar Faktur PO '.$title,
            'breadcrumbs' => $this->breadcrumbs->show(),
            'flag' => $_GET['flag'],
        );
        /*load view index*/
        $this->load->view('tukar_faktur/Tf_tukar_faktur/index', $data);
    }

    public function form($id='')
    {
        $flag = $_GET['flag'];
        $qry_url = http_build_query($_GET);
        /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Form '.strtolower($this->title).'', 'Tf_tukar_faktur/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        $title = ($flag=='medis')?'Medis':'Non Medis';
        /*get value by id*/
        $format_ttf = $this->master->format_ttf($flag);
        $response_dt = $this->Tf_tukar_faktur->get_selected_item($_GET['IDS']);
        $data['qry_url'] = $qry_url;
        $data['format_ttf'] = $format_ttf;
        $data['result'] = $response_dt;
        $data['flag'] = $flag;
        /*title header*/
        $data['title'] = 'Tukar Faktur PO '.$title;
         /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        // echo '<pre>';print_r($data);die;
        $this->load->view('tukar_faktur/Tf_tukar_faktur/form', $data, false);

    }
        
    public function find_data()
    {   
        $output = array( "data" => http_build_query($_POST) . "\n" );
        echo json_encode($output);
    }

    public function get_data()
    {
        /*get data from model*/
        $list = ($_GET['nama_perusahaan'] != '') ? $this->Tf_tukar_faktur->get_datatables() : []; 
        
        $data = array();
        $arr_total = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $arr_total[] = $row_list->total;
            if($row_list->status_tukar_faktur == 1){
                $row[] = '<div align="center"><i class="fa fa-check-circle bigger-120 green"></i></div>';
            }else{
                $row[] = '<div class="center">
                        <label class="pos-rel">
                            <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->id_penerimaan.'"/>
                            <span class="lbl"></span>
                        </label>
                      </div>';
            }
            
            $row[] = '';
            $row[] = $row_list->id_penerimaan;
            $row[] = '<div class="center">'.$row_list->id_penerimaan.'</div>';
            $row[] = $row_list->kode_penerimaan;
            $row[] = $row_list->no_po;
            $row[] = $this->tanggal->formatDate($row_list->tgl_penerimaan);
            $row[] = '<div class="left">'.$row_list->namasupplier.'</div>';
            $row[] = $row_list->no_faktur;
            $row[] = $row_list->petugas;
            $row[] = $row_list->keterangan;
            $row[] = '<div align="right">'.number_format($row_list->total).'</div>';
            $row[] = ($row_list->status_tukar_faktur == 1) ? '<div align="center"><i class="fa fa-check-circle bigger-120 green"></i></div>' : '<div align="center"><i class="fa fa-times-circle bigger-120 red"></i></div>';
            $row[] = '<div class="center">
                        <a href="#" onclick="PopupCenter('."'purchasing/penerimaan/Riwayat_penerimaan_brg/print_preview_penerimaan?ID=".$row_list->id_penerimaan."&flag=".$_GET['flag']."'".','."'Cetak'".',900,650);" class="btn btn-xs btn-default" title="cetak po"><i class="fa fa-print dark"></i></a>
                      </div>';

                  
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => isset($_GET['nama_perusahaan']) ? $this->Tf_tukar_faktur->count_all() : [],
                        "recordsFiltered" => isset($_GET['nama_perusahaan']) ? $this->Tf_tukar_faktur->count_filtered() : [],
                        "data" => $data,
                        "total" => array_sum($arr_total),
                );
        //output to json format
        echo json_encode($output);
    }

    public function process()
    {
        
        $this->load->library('form_validation');
        $val = $this->form_validation;
        
        $val->set_rules('kodesupplier', 'Kode Supplier', 'trim|required');
        $val->set_rules('tgl_faktur', 'Tanggal Faktur', 'trim|required');
        $val->set_rules('tgl_rencana_bayar', 'Tanggal Jth Tempo', 'trim|required');
        $val->set_rules('no_ttf', 'Nomor Tanda Terima Faktur', 'trim|required');
        $val->set_rules('no_seri_pajak', 'Disetujui Oleh', 'trim|required');
        $val->set_rules('diskon', 'KARS', 'trim|required');
        $val->set_rules('ppn', 'SIK AA', 'trim|required');
        $val->set_rules('biaya_materai', 'Biaya Materai', 'trim|required');
        $val->set_rules('keterangan', 'Keterangan', 'trim');
        
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

            // data header po
            $dataexc = array(
                'total_harga' => $this->regex->_genRegex($_POST['total_harga'],'RGXINT'),
                'total_sbl_ppn' => $this->regex->_genRegex($_POST['total_sbl_ppn'],'RGXINT'),
                'total_ppn' => $this->regex->_genRegex($_POST['total_ppn'],'RGXINT'),
                'diskon' => $this->regex->_genRegex($_POST['total_diskon'],'RGXINT'),
                'no_seri_pajak' => $this->regex->_genRegex($_POST['no_seri_pajak'],'RGXQSL'),
                'biaya_materai' => $this->regex->_genRegex($_POST['total_biaya_materai'],'RGXINT'),
                'kodesupplier' => $this->regex->_genRegex($_POST['kodesupplier'],'RGXINT'),
                'tgl_faktur' => $this->regex->_genRegex($_POST['tgl_faktur'],'RGXQSL'),
                'no_terima_faktur' => $this->regex->_genRegex($_POST['no_ttf'],'RGXQSL'),
                'tgl_rencana_bayar' => $this->regex->_genRegex($_POST['tgl_rencana_bayar'],'RGXQSL'),
                'flag' => $this->regex->_genRegex($_POST['flag'],'RGXQSL'),
            );
            
            if($id==0){
                $dataexc['created_date'] = date('Y-m-d H:i:s');
                $dataexc['created_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                $newId = $this->Tf_tukar_faktur->save('tc_hutang_supplier_inv', $dataexc);
                /*save logs*/
                $this->logs->save('tc_hutang_supplier_inv', $newId, 'insert new record on '.$this->title.' module', json_encode($dataexc),'id_tc_hutang_supplier_inv');
            }else{
                $dataexc['updated_date'] = date('Y-m-d H:i:s');
                $dataexc['updated_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                /*print_r($dataexc);die;*/
                /*update record*/
                $this->Tf_tukar_faktur->update('tc_hutang_supplier_inv', array('id_tc_hutang_supplier_inv' => $id), $dataexc);
                $newId = $id;
                $this->logs->save('tc_hutang_supplier_inv', $newId, 'update record'.$this->title.' module', json_encode($dataexc), 'id_tc_hutang_supplier_inv');
            }
            
            foreach( $_POST['id_penerimaan'] as $key=>$row_checked ){

                $insertBatch[] = array(
                    "id_tc_hutang_supplier_inv" => $newId,
                    "id_penerimaan" => $row_checked,
                    "total_hutang" => $_POST['subtotal'][$key],
                    "kode_penerimaan" => $_POST['kode_penerimaan'][$key],
                    "no_faktur" => $_POST['no_faktur'][$key],
                );
                $arr_id[] = $row_checked;
            }
            // insert batch
            $this->db->insert_batch('tc_hutang_supplier_inv_det', $insertBatch);
            // update status tukar faktur
            $tc_penerimaan_brg = ($_POST['flag'] == 'medis') ? 'tc_penerimaan_barang' : 'tc_penerimaan_barang_nm' ;
            $this->db->where_in('id_penerimaan', $arr_id)->update($tc_penerimaan_brg, array('status_tukar_faktur' => 1) );

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
    
    public function get_detail($id)
    {
        $flag = $_GET['flag'];
        
        $result = $this->Riwayat_penerimaan_brg->get_penerimaan_brg($flag, $id);
        // echo '<pre>';print_r($this->db->last_query());
        $data = array(
            'po_data' => $result,
            'flag' => $flag,
            'id' => $id,
            );
        $temp_view = $this->load->view('penerimaan/Riwayat_penerimaan_brg/detail_table_view', $data, true);
        echo json_encode( array('html' => $temp_view) );
    }

    public function print_preview(){

        $result = $this->Tf_tukar_faktur->get_log_data_by_id($_GET['flag'], $_GET['ID']);
        $getData = array();
        
        $data = array(
            'result' => $result,
            'flag' => $_GET['flag'],
            );
        // echo '<pre>'; print_r($data);die;
        $this->load->view('tukar_faktur/Tf_tukar_faktur/print_preview', $data);
    }

}


/* End of file example.php */
/* Location: ./application/modules/example/controllers/example.php */
