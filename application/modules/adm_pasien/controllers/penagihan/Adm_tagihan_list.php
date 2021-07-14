<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Adm_tagihan_list extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'adm_pasien/penagihan/Adm_tagihan_list');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('adm_pasien/penagihan/Adm_tagihan_list_model', 'Adm_tagihan_list');
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
        $this->load->view('penagihan/Adm_tagihan_list/index', $data);
    }

    public function form($id='')
    {

        $qry_url = http_build_query($_GET);
        /*if id is not null then will show form edit*/
            /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Create Invoice '.strtolower($this->title).'', 'Adm_tagihan_list/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id.'?'.$qry_url);
        /*get value by id*/
        $data['value'] = $this->Adm_tagihan_list->get_by_id($id); 
        
        /*initialize flag for form*/
        $data['flag'] = "update";
    
        /*title header*/
        $data['qry_url'] = $qry_url;
        $data['no_invoice'] = $this->master->format_no_invoice($_GET['jenis_pelayanan']);
        $data['detail_pasien'] = $this->Adm_tagihan_list->get_detail_pasien($id); 
        $data['title'] = $this->title;
        // echo '<pre>'; print_r($data);die;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('penagihan/Adm_tagihan_list/form', $data);
    }


    public function find_data()
    {   
        $output = array( "data" => http_build_query($_POST) . "\n" );
        echo json_encode($output);
    }
    
    public function get_data()
    {
        /*get data from model*/
        $list = $this->Adm_tagihan_list->get_datatables();
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
            $row[] = $row_list->no_invoice_tagih;
            $row[] = '<div class="center">'.$this->tanggal->formatDateDmy($row_list->tgl_tagih).'</div>';
            $row[] = '<div class="center">'.$this->tanggal->formatDateDmy($row_list->tgl_jt_tempo).'</div>';
            $row[] = $row_list->nama_tertagih;
            $row[] = '<div class="pull-right">'.number_format($row_list->jumlah_tagihan).'</div>';
            $row[] = '<div class="center">'.$row_list->diskon.' %</div>';

            $setelah_diskon = $row_list->jumlah_tagihan - $row_list->tr_yg_diskon;
            $row[] = '<div class="pull-right">'.number_format($setelah_diskon).'</div>';

            $saldo = $setelah_diskon - $row_list->jumlah_bayar;
            if ($saldo == 0) {
                $status = '<label class="label label-xs label-success">Lunas</label>';
            }elseif ($saldo == $setelah_diskon) {
                $status = '<label class="label label-xs label-danger">Belum Bayar</label>';
            }elseif ($saldo != 0 AND $saldo < $setelah_diskon) {
                $status = '<label class="label label-xs label-danger">Cicil</label>';
            }
            $row[] = '<div class="center">'.$status.'</div>';
            $row[] = '<div class="center"><a href="#" onclick="PopupCenter('."'adm_pasien/penagihan/Adm_tagihan_list/preview_invoice?ID=".$row_list->id_tc_tagih."&".$qry_url."'".','."'Preview Invoice'".',900,650);"><i class="fa fa-print green bigger-150"></i></a></div>';
            $row[] = '<div class="center"><a href="#" onclick="PopupCenter('."'adm_pasien/penagihan/Adm_tagihan_list/preview_kuitansi?nm=".$row_list->nama_tertagih."&inv=".$row_list->no_invoice_tagih."&tgl=".$row_list->tgl_tagih."&jml=".$setelah_diskon."'".','."'Preview Kuitansi'".',900,650);"><i class="fa fa-print blue bigger-150"></i></a></div>';
            $row[] = '<div class="center"><a href="#" onclick="delete_data('.$row_list->id_tc_tagih.')"><i class="fa fa-remove red bigger-150"></a></div>';
            $data[] = $row;
              
        }
        
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Adm_tagihan_list->count_all(),
                        "recordsFiltered" => $this->Adm_tagihan_list->count_filtered(),
                        "data" => $data,
                        "total_billing" => array_sum($arr_total),
                );
        //output to json format
        echo json_encode($output);
    }

    public function get_hist_inv($id_tc_tagih)
    {
        /*get data from model*/
        $list = $this->Adm_tagihan_list->get_invoice_detail($id_tc_tagih);
        // echo '<pre>';print_r($list);die;
        $data = array(
            'id_tc_tagih' => $id_tc_tagih,
            'result' => $list,
        ); 
        $html = $this->load->view('penagihan/Adm_tagihan_list/detail_table', $data, true);

        echo json_encode(array('html' => $html, 'data' => $list));
    }

    public function get_invoice_detail($id_tc_tagih)
    {
        /*get data from model*/
        $list = $this->Adm_tagihan_list->get_invoice_detail($id_tc_tagih);

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
        $list = $this->Adm_tagihan_list->get_invoice_detail($_GET['ID']);
        $data['result'] = $list;
        // echo "<pre>"; print_r($data); die;
        
        $this->load->view('penagihan/Adm_tagihan_list/preview_invoice', $data);
    }

    public function preview_kuitansi(){
   
        $data = array(
            'inv' => $_GET['inv'],
            'name' => $_GET['nm'],
            'tgl' => $_GET['tgl'],
            'total' => $_GET['jml'],
        );
        $this->load->view('penagihan/Adm_tagihan_list/preview_kuitansi', $data, false);
         
    }

    public function delete()
    {
        $id=$this->input->post('ID')?$this->regex->_genRegex($this->input->post('ID',TRUE),'RGXQSL'):null;
        if($id!=null){
            if($this->Adm_tagihan_list->delete_by_id($id)){
                echo json_encode(array('status' => 200, 'message' => 'Proses Hapus Data Berhasil Dilakukan'));

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
