<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Adm_tagihan_pelunasan extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'adm_pasien/penagihan/Adm_tagihan_pelunasan');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('adm_pasien/penagihan/Adm_tagihan_pelunasan_model', 'Adm_tagihan_pelunasan');
        $this->load->model('billing/Billing_model', 'Billing');
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
        $this->load->view('penagihan/Adm_tagihan_pelunasan/index', $data);
    }

    public function form($id='')
    {

        $qry_url = http_build_query($_GET);
        /*if id is not null then will show form edit*/
            /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Create Invoice '.strtolower($this->title).'', 'Adm_tagihan_pelunasan/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id.'?'.$qry_url);
        /*get value by id*/
        $data['value'] = $this->Adm_tagihan_pelunasan->get_by_id($id); 
        
        /*initialize flag for form*/
        $data['flag'] = "update";
    
        /*title header*/
        $data['qry_url'] = $qry_url;
        $data['detail_pasien'] = $this->Adm_tagihan_pelunasan->get_detail_pasien($id); 
        $data['title'] = $this->title;
        // echo '<pre>'; print_r($data);die;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('penagihan/Adm_tagihan_pelunasan/form', $data);
    }


    public function find_data()
    {   
        $output = array( "data" => http_build_query($_POST) . "\n" );
        echo json_encode($output);
    }
    
    public function get_data()
    {
        /*get data from model*/
        $list = isset($_GET['no_invoice']) ? $this->Adm_tagihan_pelunasan->get_datatables() : [];
        $qry_url = ($_GET) ? http_build_query($_GET) : '' ;
        // print_r($list);die;
        $data = array();
        $arr_total = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $arr_total[] = $row_list->jumlah_tagihan;
            $row[] = '<div class="center"></div>';
            $row[] = $row_list->id_tc_tagih;
            $row[] = '<div class="center">'.$no.'</div>';
            $row[] = '<div class="center"><a href="#" onclick="PopupCenter('."'adm_pasien/penagihan/Adm_tagihan_pelunasan/preview_invoice?ID=".$row_list->id_tc_tagih."&".$qry_url."'".','."'Preview Invoice'".',900,650);"><i class="fa fa-print green bigger-150"></i></a></div>';
            $row[] = '<div class="center"><a href="#" onclick="PopupCenter('."'adm_pasien/penagihan/Adm_tagihan_pelunasan/preview_kuitansi?nm=".$row_list->nama_tertagih."&inv=".$row_list->no_invoice_tagih."&tgl=".$row_list->tgl_tagih."&jml=".$row_list->jumlah_tagihan."'".','."'Preview Kuitansi'".',900,650);"><i class="fa fa-print blue bigger-150"></i></a></div>';
            $row[] = $row_list->no_invoice_tagih;
            $row[] = '<div class="center">'.$this->tanggal->formatDateDmy($row_list->tgl_tagih).'</div>';
            $row[] = '<div class="center">'.$this->tanggal->formatDateDmy($row_list->tgl_jt_tempo).'</div>';
            $row[] = $row_list->nama_tertagih;
            $row[] = '<div class="pull-right">'.number_format($row_list->jumlah_tagihan).'</div>';
            $row[] = '<div class="pull-right">'.number_format($row_list->tr_yg_diskon).'</div>';
            $row[] = '<div class="pull-right">'.number_format($row_list->jumlah_tagih).'</div>';
            $saldo = $row_list->jumlah_tagihan - $row_list->jumlah_bayar;
            if ($saldo == 0) {
                $status = '<label class="label label-xs label-success">Lunas</label>';
            }elseif ($saldo == $row_list->jumlah_tagihan) {
                $status = '<label class="label label-xs label-danger">Belum Bayar</label>';
            }elseif ($saldo != 0 AND $saldo < $row_list->jumlah_tagihan) {
                $status = '<label class="label label-xs label-danger">Cicil</label>';
            }
            $row[] = '<div class="center">'.$status.'</div>';
            $row[] = '<div class="center"><a href="#" class="label label-xs label-primary" onclick="getMenu('."'adm_pasien/penagihan/Adm_tagihan_pelunasan/form/".$row_list->id_tc_tagih."?".$qry_url."'".')">Bayar Tagihan</a></div>';

            $data[] = $row;
              
        }
        
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Adm_tagihan_pelunasan->count_all(),
                        "recordsFiltered" => $this->Adm_tagihan_pelunasan->count_filtered(),
                        "data" => $data,
                        "total_billing" => array_sum($arr_total),
                );
        //output to json format
        echo json_encode($output);
    }

    public function get_hist_inv($id_tc_tagih)
    {
        /*get data from model*/
        $list = $this->Adm_tagihan_pelunasan->get_invoice_detail($id_tc_tagih);
        // echo '<pre>';print_r($list);die;
        $data = array(
            'id_tc_tagih' => $id_tc_tagih,
            'result' => $list,
        ); 
        $html = $this->load->view('penagihan/Adm_tagihan_pelunasan/detail_table', $data, true);

        echo json_encode(array('html' => $html, 'data' => $list));
    }

    public function get_invoice_detail($id_tc_tagih)
    {
        /*get data from model*/
        $list = $this->Adm_tagihan_pelunasan->get_invoice_detail($id_tc_tagih);

        $no_invoice = $list[0]->no_invoice_tagih;
        echo json_encode(array('data' => $list, 'no_invoice' => $no_invoice));
    }

    public function get_billing_detail($kode_tc_trans_kasir)
    {
        /*get data from model*/
        $list = $this->db->get_where('tc_trans_kasir', array('kode_tc_trans_kasir' => $kode_tc_trans_kasir) )->row();
        // echo '<pre>';print_r($list);die;
        $billing = json_decode($this->Billing->getDetailData($list->no_registrasi));

        foreach ($billing->group as $k => $val) {
            foreach ($val as $value_data) {
                $subtotal = $this->Billing->get_total_tagihan($value_data);
                $resume_billing[] = $this->Billing->resumeBillingRI($value_data);
            }        
        }
        // split billing
        $split_billing = $this->Billing->splitResumeBillingRI($resume_billing);
        $no = 0;
        foreach ($split_billing as $key => $value) {
            $arr_subtotal[] = $value['subtotal'];
            if($value['subtotal'] > 0) {
                $no++;
                $getData[] = array(
                    'count_num' => $no,
                    'title' => $value['title'],
                    'subtotal' => $value['subtotal'],
                );
            }
            
        }
        echo json_encode(array('data' => $getData, 'no_registrasi' => $list->no_registrasi, 'total' => array_sum($arr_subtotal)));
    }

    public function preview_invoice(){
        $data = array();
        $list = $this->Adm_tagihan_pelunasan->get_invoice_detail($_GET['ID']);
        $data['result'] = $list;
        
        $this->load->view('penagihan/Adm_tagihan_pelunasan/preview_invoice', $data);
    }
    
    public function create_invoice(){
        $data = array();
        $list = $this->Adm_tagihan_pelunasan->get_inv_lunas_detail($_GET['ID'], $_GET['id_tc_tagih']);
        $data['result'] = $list;
		// echo '<pre>'; print_r($data);die;
        
        $this->load->view('penagihan/Adm_tagihan_pelunasan/create_invoice', $data);
    }

    public function preview_kuitansi(){
 
        
        $data = array(
            'inv' => $_GET['inv'],
            'name' => $_GET['nm'],
            'tgl' => $_GET['tgl'],
            'total' => $_GET['jml'],
        );
        $this->load->view('penagihan/Adm_tagihan_pelunasan/preview_kuitansi', $data, false);
         
    }

    public function process()
    {
        // echo '<pre>'; print_r($_POST);die;
        
        $this->load->library('form_validation');
        $val = $this->form_validation;
        
        $val->set_rules('no_kui', 'No Kuitansi', 'trim|required');
        $val->set_rules('tgl_pby', 'Tanggal Pembayaran', 'trim|required');
        $val->set_rules('metode_pembayaran', 'Jenis Pembayaran', 'trim|required');
        $val->set_rules('bank', 'Nama Bank', 'trim|required');
        $val->set_rules('is_checked[]', 'Is Checked', 'trim|required', array('required' => 'Silahkan ceklist terlebih dahulu!'));
        
        
        $val->set_message('required', "Silahkan isi data \"%s\"");
        
        if ($val->run() == FALSE)
        {
            $val->set_error_delimiters('<div style="color:white">', '</div>');
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            // print_r($_POST);die;
            $this->db->trans_begin();
            $id = ($this->input->post('id'))?$this->regex->_genRegex($this->input->post('id'),'RGXINT'):0;
            
            // data header tagihan
            $dataexc = array(
                'id_tc_tagih' => $this->regex->_genRegex($_POST['id_tc_tagih'],'RGXINT'),
                'no_kuitansi_bayar' => $this->regex->_genRegex($_POST['no_kui'],'RGXSQL'),
                'tgl_bayar' => date('Y-m-d'),
                'subtotal' => $this->regex->_genRegex($_POST['subtotal_hidden'],'RGXINT'),
                'tr_yg_diskon' => $this->regex->_genRegex($_POST['total_diskon_hidden'],'RGXINT'),
                'jumlah_bayar' => $this->regex->_genRegex($_POST['subtotal_hidden'],'RGXINT'),
                'metode_pembayaran' => $this->regex->_genRegex($_POST['metode_pembayaran'],'RGXSQL'),
                'bank' => $this->regex->_genRegex($_POST['bank'],'RGXSQL'),
                'id_dd_user' => $this->regex->_genRegex($this->session->userdata('user')->user_id, 'RGXQSL'),
                'tgl_input' => date('Y-m-d H:i:s'),

                // HAPUS!
                // 'no_invoice_tagih' => $this->regex->_genRegex($_POST['no_invoice'],'RGXQSL'),
                // 'jenis_tagih' => $this->regex->_genRegex(3,'RGXINT'),
                // 'tgl_tagih' => $this->regex->_genRegex($_POST['tgl_tagihan'],'RGXQSL'),
                // 'jumlah_tagih' => $this->regex->_genRegex($_POST['total_tagihan'],'RGXINT'),
                // 'diskon' => $this->regex->_genRegex($_POST['diskon'],'RGXINT'),
                // 'nama_tertagih' => $this->regex->_genRegex($_POST['nama_perusahaan'],'RGXQSL'),
                // 'id_tertagih' => $this->regex->_genRegex($_POST['kode_perusahaan'],'RGXINT'),
                // 'tgl_jt_tempo' => $this->regex->_genRegex($_POST['tgl_jatuh_tempo'],'RGXQSL'),
                // 'tr_yg_diskon' => $this->regex->_genRegex($_POST['total_diskon_val'],'RGXINT'),
            );
            
            if($id==0){
                $dataexc['created_date'] = date('Y-m-d H:i:s');
                $dataexc['created_by'] = $this->regex->_genRegex($this->session->userdata('user')->fullname, 'RGXQSL');
                $newId = $this->Adm_tagihan_pelunasan->save('tc_bayar_tagih', $dataexc);
                /*save logs*/
                $this->logs->save('tc_bayar_tagih', $newId, 'insert new record on '.$this->title.' module', json_encode($dataexc),'id_tc_bayar_tagih');
                // print_r($_POST);die;
            }else{
                // $dataexc['updated_date'] = date('Y-m-d H:i:s');
                // $dataexc['updated_by'] = $this->regex->_genRegex($this->session->userdata('user')->fullname);
                /*print_r($dataexc);die;*/
                /*update record*/
                $this->Adm_tagihan_pelunasan->update('tc_bayar_tagih', array('id_tc_bayar_tagih' => $id), $dataexc);
                $newId = $id;
                $this->logs->save('tc_bayar_tagih', $newId, 'update record'.$this->title.' module', json_encode($dataexc), 'id_tc_bayar_tagih');
            }

            if(isset($_POST['is_checked'])){
                $this->db->delete('tc_bayar_tagih_det', array('id_tc_bayar_tagih' => $newId) );
                foreach ($_POST['is_checked'] as $key => $value) {
                    $data_detail[] = array(
                        'id_tc_bayar_tagih' => $newId,
                        'id_tc_tagih_det' => $value,
                        'jumlah_bayar' => $_POST['jml_tagihan_per_pasien'][$value],
                        'keterangan' => $_POST['keterangan'][$value],
                        
                        // 'no_mr' => $_POST['no_mr'][$value],
                        // 'no_registrasi' => $_POST['no_registrasi'][$value],
                        // 'nama_pasien' => $_POST['nama_pasien'][$value],
                        // 'kode_perusahaan' => $_POST['kode_perusahaan'],
                        // 'jumlah_billing' => $_POST['jumlah_billing'][$value],
                        // 'jumlah_dijamin' => $_POST['jumlah_ditagih'][$value],
                        // 'jumlah_tagih' => $_POST['beban_pasien'][$value],
                        // 'penyesuaian' => $_POST['input_penyesuaian_'.$value.''],
                    );
                    $data_update[] = array(
                        'StatusLunas' => 1, // 1 Lunas 0/Null Belum Lunas
                        'id_tc_tagih_det' => $value,

                        // 'kode_tc_trans_kasir' => $value,
                        // 'kd_inv_persh_tx' => $newId,
                    );
                    // Update tc_trans_kasir -> kd_inv_persh_tx
                    // $this->db->where('kode_tc_trans_kasir', $value);
                    // $this->db->update('tc_trans_kasir', ['kd_inv_persh_tx' => $newId, 'nama_pasien' => $_POST['nama_pasien'][$value]]);
                }
                $this->db->insert_batch('tc_bayar_tagih_det', $data_detail);
                $this->db->update_batch('tc_tagih_det', $data_update, 'id_tc_tagih_det');
            }
            
            // mapping jurnal
            // code here
            // $this->accounting->create_jurnal_piutang($dataexc);
            
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                $id_tc_tagih = $_POST['id_tc_tagih'];
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'id' => $newId, 'id_tc_tagih' => $id_tc_tagih));
            }
        }
    }

}


/* End of file example.php */
/* Location: ./application/modules/example/controllers/example.php */
