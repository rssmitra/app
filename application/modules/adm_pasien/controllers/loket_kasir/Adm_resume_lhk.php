<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Adm_resume_lhk extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'adm_pasien/loket_kasir/Adm_resume_lhk');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('adm_pasien/loket_kasir/Adm_resume_lhk_model', 'Adm_resume_lhk');
        $this->load->model('adm_pasien/Adm_pasien_model', 'Adm_pasien');
        $this->load->model('billing/Billing_model', 'Billing');
        $this->load->model('casemix/Csm_billing_pasien_model', 'Csm_billing_pasien');
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
            'result' => '',
            'flag' => $_GET['flag'],
            'date' => isset($_GET['from_tgl']) ? $_GET['from_tgl'] : date('Y-m-d'),
        );

        /*load view index*/
        $this->load->view('loket_kasir/Adm_resume_lhk/index', $data);
    }

    public function publish_report() { 
        /*define variable data*/
        $result = $this->Adm_resume_lhk->get_index_data();
        
        $data = array(
            'title' => 'Publish Laporan',
            'flag' => $_GET['flag'],
            'date' => isset($_GET['from_tgl']) ? $_GET['from_tgl'] : date('Y-m-d'),
            'resume' => $result['resume'],
        );

        $existing = $this->db->get_where('ak_tc_publish_report', array('tanggal_transaksi' => $data['date'], 'flag' => $_GET['flag'] ) );

        $data['is_published'] = ($existing->num_rows() > 0) ? true : false;
        $data['publish_data'] = $existing->row();

        /*load view index*/
        $this->load->view('loket_kasir/Adm_resume_lhk/form_publish_report', $data);
    }

    public function get_data()
    {
        
        $result = $this->Adm_resume_lhk->get_index_data();
        
        // column 
        $getColumn = array();
        foreach ($result['data'] as $key => $value) {
            $getColumn[$value['nama_pegawai']][] = $value;
        }

        $resume_billing = array();
        foreach ($result['trans_data']->result() as $key => $value) {
            $resume_billing[] = $this->Csm_billing_pasien->resumeBillingRI($value);
        }
        // echo '<pre>';print_r($resume_billing);die;
        // split
        $getDataBilling = array();
        if(count($resume_billing) > 0){
            $split_billing = $this->Csm_billing_pasien->splitResumeBillingRI($resume_billing);
            if(count($split_billing) > 0){
                foreach ($split_billing as $k => $val) {
                    /*total*/
                    if((int)$val['subtotal'] > 0){
                        $getDataBilling[] = $val;
                    }  
                }
            }
        }
        
        // get unit
        $getRow = array();
        foreach ($result['data'] as $key_unit => $val_unit) {
            $getRow[$val_unit['nama_bagian']][$val_unit['jenis_tindakan']][] = $val_unit;
        }
        
        $data['resume_billing'] = $getDataBilling;
        $data['resume'] = $result['resume'];
        $data['rowData'] = $result;
        $data['rowColumn'] = $getRow;
        $data['column'] = $getColumn;
        $data['method'] = $_GET['method'];
        $data['date'] = isset($_GET['from_tgl']) ? $_GET['from_tgl'] : date('Y-m-d');

        $existing = $this->db->get_where('ak_tc_publish_report', array('tanggal_transaksi' => $data['date'], 'flag' => $_GET['flag'] ) );

        $data['is_published'] = ($existing->num_rows() > 0) ? true : false;
        $this->load->view('loket_kasir/Adm_resume_lhk/view_lhk_detail', $data);
    }

    public function get_data_by_pasien()
    {
        $bill_data = $this->Adm_resume_lhk->get_index_data_by_pasien($_GET['method']);
        // column 
        $getColumn = array();
        foreach ($bill_data['trans_data']->result() as $key => $value) {
            $getColumn[$value->kode_tc_trans_kasir][] = $value;
        }

        // get resume billing
        $getDataBilling = $this->Adm_resume_lhk->getResumeBilling( $getColumn );
        // echo '<pre>';print_r($getColumn);die;
        
        // get field column
        foreach ($bill_data['fields'] as $row_field) {
            # code...
            foreach($getDataBilling as $key_dt=>$row_dt){

                $count_field = $this->searchFieldExist($row_field, $row_dt['detail_bill']);
            
                if( count($count_field) > 0 ){
                    $field[$row_field] = $count_field[0]['title'];
                }
            }
            
        }
        
        $data['column'] = isset($field)?$field:array();
        $data['rowData'] = $getDataBilling;
        $data['method'] = $_GET['method'];
        $data['date'] = isset($_GET['from_tgl']) ? $_GET['from_tgl'] : date('Y-m-d');


        // echo '<pre>';print_r($getDataBilling);die;

        $this->load->view('loket_kasir/Adm_resume_lhk/view_lhk_pasien', $data);
    }

    public function searchFieldExist($value, $array){

        $sum_field = array();
        foreach ($array as $row_dt_bill) {
                # code...
            if($row_dt_bill['field'] == $value){
                $sum_field[] = array('title' => $row_dt_bill['title']);
            }
            
        }
        // echo '<pre>';print_r($sum_field);die;
        return $sum_field;

    }

    public function find_data()
    {   
        $output = array( "data" => http_build_query($_POST) . "\n" );
        echo json_encode($output);
    }
    
    public function process_publish(){

        $this->load->library('form_validation');
        // form validation
        $this->form_validation->set_rules('tanggal_transaksi', 'Tanggal Transaksi', 'trim|required');
        $this->form_validation->set_rules('pembulatan', 'Pembulatan', 'trim|required');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'trim|required');
        
        // set message error
        $this->form_validation->set_message('required', "Silahkan isi field \"%s\"");

        if ($this->form_validation->run() == FALSE)
        {
            $this->form_validation->set_error_delimiters('<div style="color:white"><i>', '</i></div>');
            //die(validation_errors());
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            /*execution*/
            $this->db->trans_begin();           

            $kode_laporan = $this->master->get_max_number('ak_tc_publish_report', 'id_ak_tc_publish_report');

            $format = $_POST['flag'].'-'.date('dmy').'-0'.$kode_laporan;
            $dataexc = array(
                /*form hidden input default*/
                'kode_report' => $this->regex->_genRegex($format,'RGXQSL'),
                'tanggal_transaksi' => $this->regex->_genRegex($this->form_validation->set_value('tanggal_transaksi'),'RGXQSL'),
                'pembulatan' => $this->regex->_genRegex($this->form_validation->set_value('pembulatan'),'RGXINT'),
                'keterangan' => $this->regex->_genRegex($this->form_validation->set_value('keterangan'),'RGXQSL'),
                'total_pendapatan' => $this->regex->_genRegex($_POST['total_pendapatan'],'RGXINT'),
                'total_stlh_pembulatan' => $this->regex->_genRegex($_POST['total_akhir'],'RGXINT'),
                'tunai' => $this->regex->_genRegex($_POST['tunai'],'RGXINT'),
                'debet' => $this->regex->_genRegex($_POST['debet'],'RGXINT'),
                'kredit' => $this->regex->_genRegex($_POST['kredit'],'RGXINT'),
                'nk_perusahaan' => $this->regex->_genRegex($_POST['nk_perusahaan'],'RGXINT'),
                'nk_karyawan' => $this->regex->_genRegex($_POST['nk_karyawan'],'RGXINT'),
                'potongan' => $this->regex->_genRegex($_POST['potongan'],'RGXINT'),
                'status' => $this->regex->_genRegex(1,'RGXINT'),
                'flag' => $this->regex->_genRegex($_POST['flag'],'RGXQSL'),
            );

            // print_r($dataexc);die;
            // cek existing
            $existing = $this->db->get_where('ak_tc_publish_report', array('tanggal_transaksi' => $this->form_validation->set_value('tanggal_transaksi'), 'flag' => $_POST['flag'] ) );

            if( $existing->num_rows() == 0 ){
                $dataexc['created_date'] = date('Y-m-d H:i:s');
                $dataexc['created_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                /*save post data*/
                $this->db->insert( 'ak_tc_publish_report', $dataexc );
                $newId = $this->db->insert_id();
                /*save logs*/
                $this->logs->save( 'ak_tc_publish_report', $newId, 'insert new record on '.$this->title.' module', json_encode($dataexc), 'id_ak_tc_publish_report');
                
            }else{
                $dataexc['updated_date'] = date('Y-m-d H:i:s');
                $dataexc['updated_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                /*update record*/
                $this->db->update( 'ak_tc_publish_report', $dataexc, array('id_ak_tc_publish_report' => $existing->row()->id_ak_tc_publish_report) );
                $newId = $existing->row()->id_ak_tc_publish_report;
                /*save logs*/
                $this->logs->save('ak_tc_publish_report', $newId, 'update record on '.$this->title.' module', json_encode($dataexc),'id_ak_tc_publish_report');
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

    public function export_excel(){
        $list = $this->Adm_resume_lhk->get_data(); 
        $data = array(
            'title'     => 'Perjanjian Rawat Jalan',
            'fields'    => $list->field_data(),
            'data'      => $list->result(),
        );
        // echo '<pre>';print_r($data);die;
        $this->load->view('loket_kasir/Adm_resume_lhk/excel_view', $data);

    }

}


/* End of file example.php */
/* Location: ./application/modules/example/controllers/example.php */
