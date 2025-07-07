<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
class Billing extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'billing/Billing');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('Billing_model', 'Billing');
        $this->load->model('adm_pasien/loket_kasir/Adm_kasir_model', 'Adm_kasir');
        /*load library*/
        $this->load->library('Form_validation');
        $this->load->library('stok_barang');
        $this->load->library('tarif');
        

        $this->load->module('Templates/Templates.php');
        $this->load->module('Templates/Export_data.php');
        Modules::run('Templates/Export_data');

        /*load model*/
        $this->load->model('casemix/Csm_admin_costing_model', 'Csm_admin_costing');
        $this->load->model('casemix/Migration_model', 'Migration');
        $this->load->model('casemix/Csm_billing_pasien_model', 'Csm_billing_pasien');
        /*load module*/
        $this->load->module('casemix/Csm_billing_pasien');
        $this->cbpModule = new Csm_billing_pasien;
        
        /*enable profiler*/
        $this->output->enable_profiler(false);
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'Title';

    }

    public function index() { 
        /*define variable data*/
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show()
        );

        $this->load->view('Billing/index', $data);
    }

    public function form_billing($no_registrasi) { 
        /*define variable data*/
        $data = array(
            'title' => 'Form Pembayaran Pasien',
            'breadcrumbs' => $this->breadcrumbs->show(),
            'result' => json_decode($this->Billing->getDetailData($no_registrasi)),
        );
        //echo '<pre>';print_r($data);die;

        $this->load->view('Billing/form', $data);
    }

    public function payment_view($no_registrasi, $tipe) { 
        /*define variable data*/
        $result = json_decode($this->Billing->getDetailData($no_registrasi));
        // get total sudah dibayar
        $arr_sum_total = array();
        foreach($result->kasir_data as $row){
            $arr_sum_total[] = (int)$row->bill;
        }

        $data = array(
            'title' => 'Form Pembayaran Pasien',
            'no_registrasi' => $no_registrasi,
            'tipe' => $tipe,
            'breadcrumbs' => $this->breadcrumbs->show(),
            'result' => $result,
            'total_paid' => array_sum($arr_sum_total),
        );
        // echo '<pre>'; print_r($data);die;

        $this->load->view('Billing/form_payment', $data);
    }

    public function payment_apt_view($kode_trans_far, $tipe) { 
        /*define variable data*/
        $result = json_decode($this->Billing->getDetailDataApt($kode_trans_far));
        // get total sudah dibayar
        $arr_sum_total = array();
        foreach($result->trans_data as $row){
            $arr_sum_total[] = (int)$row->bill_rs;
        }

        $data = array(
            'title' => 'Form Pembayaran Pasien',
            'kode_trans_far' => $kode_trans_far,
            'tipe' => $tipe,
            'breadcrumbs' => $this->breadcrumbs->show(),
            'result' => $result,
            'total_paid' => array_sum($arr_sum_total),
        );
        // echo '<pre>'; print_r($data);die;

        $this->load->view('Billing/form_payment_apt', $data);
    }

    public function payment_um_view($no_registrasi, $tipe) { 
        /*get detail data billing*/
        
        /*define variable data*/
        $result = json_decode($this->Billing->getDetailData($no_registrasi));
        // get total sudah dibayar
        $arr_sum_total = array();
        foreach($result->kasir_data as $row){
            $arr_sum_total[] = (int)$row->bill;
        }
        $uang_muka = $this->db->get_where('ri_tc_rawatinap', array('kode_ri' => $_GET['ID']))->row();
        $ex_um = $this->db->get_where('ks_tc_trans_um', array('no_registrasi' => $no_registrasi))->result();

        $data = array(
            'title' => 'Form Pembayaran Uang Muka Pasien',
            'no_registrasi' => $no_registrasi,
            'tipe' => $tipe,
            'breadcrumbs' => $this->breadcrumbs->show(),
            'result' => $result,
            'total_paid' => array_sum($arr_sum_total),
            'deposit' => $uang_muka,
            'um' => $ex_um,
        );
        // echo '<pre>'; print_r($uang_muka);die;

        $this->load->view('Billing/form_payment_um', $data);
    }

    public function form_update_klas($no_registrasi, $tipe) { 
        /*get detail data billing*/
        
        $data = array(
            'title' => 'Form Update Klas Tarif',
            'no_registrasi' => $no_registrasi,
            'tipe' => $tipe,
            'breadcrumbs' => $this->breadcrumbs->show(),
        );
        // echo '<pre>'; print_r($uang_muka);die;

        $this->load->view('Billing/form_update_klas', $data);
    }

    public function add_tindakan($no_registrasi, $no_mr)
    {
        $data = array();
        $data['no_mr'] = $no_mr;
        $data['no_registrasi'] = $no_registrasi;
        $data['kunjungan'] = $this->Billing->getLogActivity($no_registrasi);
        // echo '<pre>'; print_r($data);die;

        /*load form view*/
        $this->load->view('Billing/form_add_tindakan', $data);
    }

    public function payment_success($no_registrasi){
        $data = array(
            'no_registrasi' => $no_registrasi,
            'result' => json_decode($this->Billing->getDetailData($no_registrasi)),
        );
        $this->load->view('Billing/form_success', $data);
    }

    public function get_data_ri(){
        /*akan di filter berdasarkan pasien pada klinik masing2*/
        /*get data from model*/
        $list = $this->Billing->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $link = 'billing/Billing';
            $str_type = 'RI';
            $row[] = '<div class="center">
                        <label class="pos-rel">
                            <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->no_kunjungan.'"/>
                            <span class="lbl"></span>
                        </label>
                    </div>';
            $row[] = $row_list->no_registrasi;
            $row[] = $str_type;
            $row[] = '';
            $row[] = '<div class="center"><div class="btn-group">
                            <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">
                                <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                            </button>
                            <ul class="dropdown-menu dropdown-inverse">
                                <li><a href="#">Rollback</a></li>
                                <li><a href="#">Selengkapnya</a></li>
                            </ul>
                        </div></div>';
            $row[] = '<div class="center"><a href="#" onclick="getMenu('."'pelayanan/Billing/form/".$row_list->kode_ri."/".$row_list->no_kunjungan."'".')">'.$row_list->no_kunjungan.'</a></div>';
            $row[] = '<div class="center">'.$row_list->no_mr.'</div>';
            $row[] = strtoupper($row_list->nama_pasien);
            $row[] = ($row_list->nama_perusahaan)?$row_list->nama_perusahaan:$row_list->nama_kelompok;
            $row[] = $row_list->nama_bagian;
            $row[] = $row_list->nama_klas;
            $row[] = $this->tanggal->formatDateTime($row_list->tgl_masuk);
            $row[] = $row_list->nama_pegawai;
            $status_pulang = ($row_list->status_pulang== 0 || NULL)?'<label class="label label-danger">Masih dirawat</label>':'<label class="label label-success">Pulang</label>';
            $row[] = '<div class="center">'.$status_pulang.'</div>';
           
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Billing->count_all(),
                        "recordsFiltered" => $this->Billing->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function getDetail($no_registrasi, $tipe){
        
        /*get detail data billing*/
        $data = json_decode($this->Billing->getDetailData($no_registrasi));
        
        // /*cek apakah data sudah pernah diinsert ke database atau blm*/
        // if( $this->Billing->checkExistingData($no_registrasi) ){
        //     /*no action if data exist, continue to view data*/
        // }else{
        // /*jika data belum ada atau belum pernah diinsert, maka insert ke table*/
        //     /*insert data untuk pertama kali*/
        //     if( $data->group && $data->kasir_data && $data->trans_data )
        //     $this->Billing->insertDataFirstTime($data, $no_registrasi);
            // }
        // echo '<pre>';print_r($data);die;

        if($tipe=='RJ'){
            $html = $this->Billing->getDetailBillingRJ($no_registrasi, $tipe, $data);
        }else{
            $html = $this->Billing->getDetailBillingRI($no_registrasi, $tipe, $data);
        }

        echo json_encode(array('html' => $html));
        
    }

    public function print_billing_resume($no_registrasi, $tipe, $field=''){
        
        // $data = json_decode($this->Billing->getDetailData($no_registrasi));
        // echo '<pre>';print_r($html);die;
        $temp = new Templates;

        $result = json_decode($this->Billing->getDetailData($no_registrasi));
        $tipe = $this->Billing->cek_tipe_pasien($no_registrasi);
        $grouping = $this->Billing->groupingTransaksiByDate($result->trans_data);
        $header = $temp->setGlobalProfilePasienTemplateRI($result);
        
        $resume_billing = $this->Billing->getDetailBillingRILess($no_registrasi, $tipe, $result);
        $rincian_billing = $temp->TemplateRincianRI($no_registrasi, $tipe, $field);

        $content = ($field != '')?$rincian_billing:$resume_billing;


        $data = array(
            'title' => 'Billing Pasien Sementara',
            'breadcrumbs' => $this->breadcrumbs->show(),
            'no_registrasi' => $no_registrasi,
            'content' => $content,
            'data' => $result,
        );
        $data['header'] = $header;
        $this->load->view('Billing/cetak_resume_billing', $data, false);

    }

    public function getDetailLess($no_registrasi, $tipe){
        
        /*get detail data billing*/
        $data = json_decode($this->Billing->getDetailData($no_registrasi));
        // echo '<pre>';print_r($data);die;
        if($tipe=='RJ'){
            $html = $this->Billing->getDetailBillingRJLess($no_registrasi, $tipe, $data);
        }else{
            $html = $this->Billing->getDetailBillingRILess($no_registrasi, $tipe, $data);
        }

        echo json_encode(array('html' => $html));
        
    }

    public function getDetailLessApt($kode_trans_far, $tipe){
      /* Get detail data Farmasi */
      $result = json_decode($this->Billing->getDetailDataApt($kode_trans_far));
      
      $html = $this->Billing->getDetailBillingRILess($kode_trans_far, $tipe, $result);

      echo json_encode(array('html' => $html));
    }

    public function getRincianBilling($noreg, $tipe, $field){
        $temp = new Templates;
        /*header html*/
        $html = '';
        $html .= $temp->TemplateRincianRI($noreg, $tipe, $field);
        
        echo json_encode(array('html' => $html));
    }

    public function getDetailBillingKasir($no_registrasi){
        
        /*cek tipe pasien RI/RJ*/
        $tipe = $this->Billing->cek_tipe_pasien($no_registrasi);
        /*get detail data billing*/
        $data = json_decode($this->Billing->getDetailData($no_registrasi));

        $data = array(
            'no_registrasi' => $no_registrasi,
            'tipe' => $tipe,
            'data' => $data,
            'kunjungan' => $this->Billing->getRiwayatKunjungan($no_registrasi),
            'riwayat' => $this->Csm_billing_pasien->get_by_id($no_registrasi),
        );
        // echo '<pre>';print_r($data);die;

        $html = $this->load->view('Billing/temp_trans_kasir', $data, true);

        echo json_encode(array('html' => $html));

    }

    public function viewDetailBillingKasirApt($kode_trans_far, $tipe){
        
        /*get detail data billing*/
        $result = json_decode($this->Billing->getDetailDataApt($kode_trans_far));
        $grouping = $this->Billing->groupingTransaksiByDate($result->trans_data);

        $data = array(
            'title' => 'Billing Pasien',
            'breadcrumbs' => $this->breadcrumbs->show(),
            'kode_trans_far' => $kode_trans_far,
            'tipe' => $tipe,
            'flag' => isset($_GET['flag'])?$_GET['flag']:'',
            'data' => $result,
            'kunjungan' => $grouping,
        );
        // echo '<pre>';print_r($data);die;
        $data['header'] = $this->load->view('Billing/temp_header_dt_apt', $data, true);

        $this->load->view('Billing/temp_trans_kasir_apt', $data, false);

    }

    public function viewDetailBillingKasir($no_registrasi, $tipe){
        
        /*get detail data billing*/
        $result = json_decode($this->Billing->getDetailData($no_registrasi));
        $grouping = $this->Billing->groupingTransaksiByDate($result->trans_data);

        $data = array(
            'title' => 'Billing Pasien',
            'breadcrumbs' => $this->breadcrumbs->show(),
            'no_registrasi' => $no_registrasi,
            'tipe' => $tipe,
            'flag' => isset($_GET['flag'])?$_GET['flag']:'',
            'data' => $result,
            'kunjungan' => $grouping,
            'riwayat' => $this->Csm_billing_pasien->get_by_id($no_registrasi),
        );
        // echo '<pre>';print_r($data);die;
        $data['header'] = $this->load->view('Billing/temp_header_dt', $data, true);

        $this->load->view('Billing/temp_trans_kasir', $data, false);

    }

    public function viewDetailBillingKasirByUnit($no_registrasi, $tipe){
        
        /*get detail data billing*/
        $result = json_decode($this->Billing->getDetailData($no_registrasi));
        $grouping = $this->Billing->groupingTransaksiByDate($result->trans_data);

        $data = array(
            'title' => 'Billing Pasien',
            'breadcrumbs' => $this->breadcrumbs->show(),
            'no_registrasi' => $no_registrasi,
            'tipe' => $tipe,
            'flag' => isset($_GET['flag'])?$_GET['flag']:'',
            'data' => $result,
            'kunjungan' => $grouping,
        );
        // echo '<pre>';print_r($data);die;
        $data['header'] = $this->load->view('Billing/temp_header_dt', $data, true);

        $this->load->view('Billing/temp_trans_kasir_by_unit', $data, false);

    }

    public function viewDetailBillingKasirRI($no_registrasi, $tipe){
        
        /*get detail data billing*/
        $result = json_decode($this->Billing->getDetailData($no_registrasi));
        $grouping = $this->Billing->groupingTransaksiByDate($result->trans_data);

        $data = array(
            'title' => 'Billing Pasien',
            'breadcrumbs' => $this->breadcrumbs->show(),
            'no_registrasi' => $no_registrasi,
            'tipe' => $tipe,
            'flag' => isset($_GET['flag'])?$_GET['flag']:'',
            'data' => $result,
            'kunjungan' => $grouping,
        );
        // echo '<pre>';print_r($result);die;
        $this->load->view('Billing/temp_trans_kasir_ri', $data, false);

    }

    public function load_billing_view($no_registrasi, $tipe){
        /*get detail data billing*/
        $result = json_decode($this->Billing->getDetailData($no_registrasi));
        $log_activity = $this->Billing->getLogActivity($no_registrasi);
        // echo '<pre>';print_r($log_activity);die;
        $grouping = $this->Billing->groupingTransaksiByDate($result->trans_data);

        $data = array(
            'title' => 'Billing Pasien',
            'breadcrumbs' => $this->breadcrumbs->show(),
            'no_registrasi' => $no_registrasi,
            'tipe' => $tipe,
            'data' => $result,
            'kunjungan' => $grouping,
            'log_activity' => $log_activity,
        );
        // echo '<pre>';print_r($result);die;
        $this->load->view('Billing/data_billing_view', $data, false);

    }

    public function load_billing_view_apt($kode_trans_far, $tipe){
        /*get detail data billing*/
        $result = json_decode($this->Billing->getDetailDataApt($kode_trans_far));
        // echo '<pre>';print_r($log_activity);die;
        $grouping = $this->Billing->groupingTransaksiByDate($result->trans_data);

        $data = array(
            'title' => 'Billing Pasien',
            'breadcrumbs' => $this->breadcrumbs->show(),
            'kode_trans_far' => $kode_trans_far,
            'tipe' => $tipe,
            'data' => $result,
            'kunjungan' => $grouping,
        );
        // echo '<pre>';print_r($data);die;
        $this->load->view('Billing/data_billing_view_apt', $data, false);

    }

    public function getBillingByPenjamin($no_registrasi, $tipe){
        
        /*get detail data billing*/
        $result = json_decode($this->Billing->getDetailData($no_registrasi));

        $data = array(
            'no_registrasi' => $no_registrasi,
            'tipe' => $tipe,
            'data_billing' => $result,
        );
        
        $html = $this->load->view('Billing/temp_billing_by_penjamin', $data, true);

        echo json_encode(array('html' => $html));

    }

    public function proses_update_billing(){
        $result = json_decode($this->Billing->getDetailData($_POST['no_registrasi']));
        /*echo '<pre>';
        print_r($_POST);die;*/
        echo json_encode(array('no_registrasi' => $_POST['no_registrasi'], 'total_bayar' => $_POST['sisa_bayar_hidden'], 'tipe' => $_POST['tipe_pasien']));
    }

    public function rollback_kasir(){
        $exc = $this->Billing->rollback_kasir( $_POST['no_reg'] );
        // delete casemix data if exist
        $this->db->update('csm_reg_pasien', array('is_submitted' => 'N'), array('no_registrasi' => $_POST['no_reg']) );
        echo json_encode( array('status' => 200) );
    }

    public function rollback_kasir_apt(){
        $exc = $this->Billing->rollback_kasir_apt( $_POST['kode_trans_far'] );
        echo json_encode( array('status' => 200) );
    }

    public function save_transaction($data){

        /*total billing*/

        $required_data = array(
                        'kode_trans_pelayanan' => $this->master->get_max_number('tc_trans_pelayanan', 'kode_trans_pelayanan'),
                        'no_kunjungan' => isset($data['no_kunjungan'])?$data['no_kunjungan']:0,
                        'no_registrasi' => isset($data['no_registrasi'])?$data['no_registrasi']:0,
                        'bill_rs' => isset($data['bill_rs'])?$data['bill_rs']:0,
                        'bill_dr1' => isset($data['bill_dr1'])?$data['bill_dr1']:0,
                        'bill_dr2' => isset($data['bill_dr2'])?$data['bill_dr2']:0,
                        'bill_dr3' => isset($data['bill_dr3'])?$data['bill_dr3']:0,
                        'bill_rs_askes' => isset($data['bill_rs_askes'])?$data['bill_rs_askes']:0,
                        'bill_dr1_askes' => isset($data['bill_dr1_askes'])?$data['bill_dr1_askes']:0,
                        'bill_dr2_askes' => isset($data['bill_dr2_askes'])?$data['bill_dr2_askes']:0,
                        'bill_rs_jatah' => isset($data['bill_rs_jatah'])?$data['bill_rs_jatah']:0,
                        'bill_dr1_jatah' => isset($data['bill_dr1_jatah'])?$data['bill_dr1_jatah']:0,
                        'bill_dr2_jatah' => isset($data['bill_dr2_jatah'])?$data['bill_dr2_jatah']:0,
                    );
        /*jumlah*/
        $jumlah = $required_data['bill_rs'] + $required_data['bill_dr1'] + $required_data['bill_dr2'] + $required_data['bill_dr3'];

        $required_data['jumlah'] = $jumlah;

        $merge_data_for_insert = array_merge($data, $required_data);

        //print_r($merge_data_for_insert);die;
        $this->db->insert('tc_trans_pelayanan', $merge_data_for_insert);

    }

    public function print_preview(){
 
        $result = json_decode($this->Billing->getDetailData($_GET['no_registrasi']));
        $tipe = $this->Billing->cek_tipe_pasien($_GET['no_registrasi']);
        $grouping = $this->Billing->groupingTransaksiByDate($result->trans_data);

        $data = array(
            'title' => 'Billing Pasien Sementara',
            'breadcrumbs' => $this->breadcrumbs->show(),
            'no_registrasi' => $_GET['no_registrasi'],
            'tipe' => $tipe,
            'flag_bill' => isset($_GET['flag_bill'])?$_GET['flag_bill']:'temporary',
            'data' => $result,
            'kasir_data' => $result->kasir_data,
            'kunjungan' => $grouping,
        );
        // echo '<pre>';print_r($data['data']);die;
        $data['header'] = $this->load->view('Billing/temp_header_dt', $data, true);
        $this->load->view('Billing/cetakBilling_sem_rssm', $data, false);

    }

    public function print_preview_apt(){
 
        $result = json_decode($this->Billing->getDetailDataApt($_GET['kode_trans_far']));
        $tipe = $this->Billing->cek_tipe_pasien($_GET['kode_trans_far']);
        $grouping = $this->Billing->groupingTransaksiByDate($result->trans_data);

        $data = array(
            'title' => 'Billing Pasien Sementara',
            'breadcrumbs' => $this->breadcrumbs->show(),
            'kode_trans_far' => $_GET['kode_trans_far'],
            'tipe' => $tipe,
            'flag_bill' => isset($_GET['flag_bill'])?$_GET['flag_bill']:'temporary',
            'data' => $result,
            'kasir_data' => $result->kasir_data,
            'kunjungan' => $grouping,
        );
        // echo '<pre>';print_r($data);die;
        $data['header'] = $this->load->view('Billing/temp_header_dt_apt', $data, true);
        $this->load->view('Billing/cetakBilling_sem_rssm_apt', $data, false);

    }

    public function print_kuitansi(){
        
        $result = json_decode($this->Billing->getDetailData($_GET['no_registrasi']));
        $tipe = $this->Billing->cek_tipe_pasien($_GET['no_registrasi']);
        $grouping = $this->Billing->groupingTransaksiByDate($result->trans_data);
        $data = array(
            'title' => 'Billing Pasien Sementara',
            'breadcrumbs' => $this->breadcrumbs->show(),
            'no_registrasi' => $_GET['no_registrasi'],
            'tipe' => $tipe,
            'data' => $result,
            'kunjungan' => $grouping,
            'total_payment' => $_GET['payment'],
        );
        // echo '<pre>';print_r($result);die;
        //$this->load->view('Billing/cetakKuitansi_view', $data, false);
        //$data['header'] = $this->load->view('Billing/temp_header_dt', $data, true);
        $this->load->view('Billing/cetakKuitansi_view', $data, false);
         
    }
    
    public function print_kuitansi_pasien(){
 
        $result = json_decode($this->Billing->getDetailData($_GET['no_registrasi']));
        $tipe = $this->Billing->cek_tipe_pasien($_GET['no_registrasi']);
        $grouping = $this->Billing->groupingTransaksiByDate($result->trans_data);

        $data = array(
            'title' => 'Billing Pasien Sementara',
            'breadcrumbs' => $this->breadcrumbs->show(),
            'no_registrasi' => $_GET['no_registrasi'],
            'tipe' => $tipe,
            'data' => $result,
            'kunjungan' => $grouping,
            'total_payment' => $_GET['payment'],
        );
        // echo '<pre>';print_r($kasir_data);die;
        //$this->load->view('Billing/cetakKuitansi_view', $data, false);
        //$data['header'] = $this->load->view('Billing/temp_header_dt', $data, true);
        $this->load->view('Billing/cetakKuitansiPasien_view', $data, false);
         
    }

    public function process(){
        
        // print_r($_POST);die;
        // print("<pre>".print_r($_POST,true)."</pre>");die;
        $this->load->library('accounting');

        $this->db->trans_begin();
        
        // get no seri kuitansi
        $seri_kuitansi_dt = $this->master->no_seri_kuitansi($_POST['no_registrasi']);
        // print_r($seri_kuitansi_dt);die;
        // insert tc_trans_kasir
        $dataTranskasir["kode_tc_trans_kasir"] = $this->master->get_max_number('tc_trans_kasir','kode_tc_trans_kasir');
        $dataTranskasir["seri_kuitansi"] = $seri_kuitansi_dt['seri_kuitansi'];
        $dataTranskasir["no_kuitansi"] = $seri_kuitansi_dt['no_kuitansi'];
        $dataTranskasir["no_induk"] = $this->session->userdata('user')->user_id; 
        $dataTranskasir["tgl_jam"] = $_POST['tgl_trans_kasir'].' '.date('H:i:s');

        $change = ( $_POST['uang_dibayarkan_tunai'] > $_POST['jumlah_bayar_tunai'] ) ? $_POST['uang_dibayarkan_tunai'] - $_POST['jumlah_bayar_tunai'] : 0;

        $cash = isset($_POST['uang_dibayarkan_tunai']) ? (float)$_POST['uang_dibayarkan_tunai']:(float)0;
        $dataTranskasir["cash"] = isset($_POST['uang_dibayarkan_tunai']) ? (float)$_POST['uang_dibayarkan_tunai']:(float)0;
        $dataTranskasir["change"] = $_POST['change'];

        // Tunai
        $tunai = $cash - $_POST['change'];
        // $dataTranskasir["tunai"] = isset($_POST['jumlah_bayar_tunai']) ? (float)$_POST['jumlah_bayar_tunai']:(float)0;
        $dataTranskasir["tunai"] = $tunai;
        // debet
        $dataTranskasir["debet"] = isset($_POST['jumlah_bayar_debet']) ? (float)$_POST['jumlah_bayar_debet'] : (float)0;
        $dataTranskasir["no_debet"] = $_POST['nomor_kartu_debet'];
        $dataTranskasir["no_batch_dc"] = $_POST['nomor_batch_debet'];
        $dataTranskasir["kd_bank_dc"] = $_POST['kd_bank_dc'];
        // kredit
        $dataTranskasir["kredit"] = isset($_POST['jumlah_bayar_kredit'])?(float)$_POST['jumlah_bayar_kredit']:(float)0;
        $dataTranskasir["no_kredit"] = $_POST['nomor_kartu_kredit'];
        $dataTranskasir["no_batch_cc"] = $_POST['nomor_batch_kredit'];
        $dataTranskasir["kd_bank_cc"] = $_POST['kd_bank_cc'];
        
        $dataTranskasir["no_mr"] = $_POST['no_mr_val'];
        $dataTranskasir["nama_pasien"] = $_POST['nama_pasien_val'];
        $dataTranskasir["no_registrasi"] = $_POST['no_registrasi'];
        $dataTranskasir["kode_perusahaan"] = $_POST['kode_perusahaan_val'];
        $dataTranskasir["keterangan"] = 'Pembayaran Administrasi Pasien pada Loket';

        if( in_array($_POST['kode_kelompok_val'], array(4,7,8,11,12,13,14,15,16)) ){
            // nk_karyawan
            $dataTranskasir["nk_karyawan"] = $_POST['total_nk'];
            $dataTranskasir["no_mr_karyawan"] = $_POST['no_mr_val'];
        }else{
            if(isset($_POST['metode_bon_karyawan'])){
                $dataTranskasir["nk_karyawan"] = $_POST['total_nk'];
                $dataTranskasir["no_mr_karyawan"] = $_POST['no_mr_val'];
            }else{
                $dataTranskasir["nk_karyawan"] = 0;
            }
        }

        if( !in_array($_POST['kode_kelompok_val'], array(1,4,7,8,11,12,13,14,15,16)) ){
            if($_POST['kode_perusahaan_val'] == 120){
                $dataTranskasir["nk_perusahaan"] = $_POST['total_nk'];
                $dataTranskasir["kode_perusahaan"] = $_POST['kode_perusahaan_val'];
                $dataTranskasir["pembayar"] = $_POST['perusahaan_penjamin'];
            }else{
                if($_POST['kode_penjamin_pasien'] == 3){
                    $dataTranskasir["kode_perusahaan"] = $_POST['kode_perusahaan_val'];
                    $dataTranskasir["pembayar"] = $_POST['perusahaan_penjamin'];
                    $nk_asuransi_lainnya = isset($_POST['jumlah_nk_asuransi'])?$_POST['jumlah_nk_asuransi']:0;
                    $dataTranskasir["nk_perusahaan"] = ($nk_asuransi_lainnya > 0) ? $nk_asuransi_lainnya : $_POST['total_nk'] ;
                }
            }
        }else{
            $dataTranskasir["pembayar"] = $_POST['nama_pasien_val'];
            $dataTranskasir["nk_perusahaan"] = 0;
        }

        // NK Karyawan untuk Pasien Keluarga Karyawan
        if( in_array($_POST['kode_penjamin_pasien'], array(4, 7, 8, 11, 12, 13, 14, 15, 16)) ){
          $dataTranskasir["nk_karyawan"] = $_POST['jumlah_nk'];
          $dataTranskasir["pembayar"] = $_POST['pembayar'];
        }

        $potongan_diskon = ($_POST['total_payment'] * ($_POST['jumlah_diskon']/100));
        $sisa_bill = $_POST['total_payment'] - $potongan_diskon;
        $dataTranskasir["potongan"] = $potongan_diskon;
        $dataTranskasir["discount"] = ($_POST['jumlah_diskon'])?$_POST['jumlah_diskon']:0;
        $dataTranskasir["bill"] = $sisa_bill;

        // print_r($dataTranskasir);die;

        // kode shift
        $dataTranskasir["kode_shift"] = $_POST['shift'];
        $dataTranskasir["kode_loket"] = $_POST['loket'];
        // insert tc trans kasir
        $this->db->insert('tc_trans_kasir', $dataTranskasir);

        // update status NK checked
        $str_to_array_nk = explode(',', $_POST['array_data_nk_checked']);
        foreach ($str_to_array_nk as $key_nk => $kode_trans_pelayanan_nk) {
            $this->db->update('tc_trans_pelayanan', array('status_nk' => 1, 'kode_tc_trans_kasir' => $dataTranskasir["kode_tc_trans_kasir"]), array('kode_trans_pelayanan' => $kode_trans_pelayanan_nk));
            $this->db->trans_commit();
        }

        // insert trans bagian
        $str_to_array = explode(',', $_POST['array_data_checked']);
        foreach ($str_to_array as $key => $kode_trans_pelayanan) {
            $data_trans = $this->db->get_where('tc_trans_pelayanan', array('kode_trans_pelayanan' => $kode_trans_pelayanan))->row();
            $dataTransKasirBagian["kode_tc_trans_kasir"] = $dataTranskasir["kode_tc_trans_kasir"];
            $dataTransKasirBagian["kode_bagian"] = $data_trans->kode_bagian;
            $this->db->insert('tc_trans_kasir_bagian', $dataTransKasirBagian);
            // update status, kode_tc_trans_kasir tc_trans_pelayanan per item
            $this->db->update('tc_trans_pelayanan', array('status_selesai' => 3, 'kode_tc_trans_kasir' => $dataTranskasir["kode_tc_trans_kasir"]), array('kode_trans_pelayanan' => $kode_trans_pelayanan));
            // update status bayar farmasi
            if($data_trans->kode_trans_far != null || $data_trans->kode_trans_far != 0){
                $this->db->update('fr_tc_far', array('status_bayar' => 1), array('kode_trans_far' => $data_trans->kode_trans_far)  );
            }
            $this->db->trans_commit();
        }

        if(isset($_POST['diagnosa_akhir'])) {
            // update diagnosa
            $riwayat_diagnosa = array();
            $riwayat_diagnosa['kode_icd_diagnosa'] = isset($_POST['diagnosa_akhir_hidden'])?$_POST['diagnosa_akhir_hidden']:'';
            $riwayat_diagnosa['diagnosa_akhir'] = isset($_POST['diagnosa_akhir'])?$_POST['diagnosa_akhir']:'';

            if(isset($_POST['kode_riwayat_hidden']) AND !empty($_POST['kode_riwayat_hidden']) ){
                $this->db->update('th_riwayat_pasien', $riwayat_diagnosa, array('kode_riwayat' => $_POST['kode_riwayat_hidden']) );
            }else{
                $riwayat_diagnosa['no_registrasi'] = $_POST['no_registrasi'];
                $riwayat_diagnosa['no_mr'] = $_POST['no_mr_val'];
                $riwayat_diagnosa['nama_pasien'] = $_POST['nama_pasien_val'];
                $riwayat_diagnosa['diagnosa_awal'] = $_POST['diagnosa_akhir'];
                $riwayat_diagnosa['dokter_pemeriksa'] = $_POST['nama_dokter_val'];
                $riwayat_diagnosa['tgl_periksa'] = $_POST['tgl_jam_keluar'];
                $riwayat_diagnosa['kode_bagian'] = $_POST['kode_bag_val'];
                $riwayat_diagnosa['kategori_tindakan'] = 3;
                $this->db->insert('th_riwayat_pasien', $riwayat_diagnosa );
            }
        }
        
        // untuk masuk ke akunting
        $dataAkunting["seri_kuitansi"] = $seri_kuitansi_dt['seri_kuitansi'];
        $dataAkunting["no_bukti"] = $seri_kuitansi_dt['seri_kuitansi'].  $seri_kuitansi_dt['no_kuitansi'];
        $dataAkunting["tgl_transaksi"] = $_POST['tgl_trans_kasir'].' '.date('H:i:s');
        $dataAkunting["uraian_transaksi"] = 'Pendapatan Pasien '.$seri_kuitansi_dt['seri_kuitansi'].' '.$_POST['no_mr_val'].' - '.$_POST['nama_pasien_val'];
        $dataAkunting["total_nominal"] = $_POST['total_payment_all'];
        $dataAkunting["nama_pasien"] = $_POST['nama_pasien_val'];
        $dataAkunting["no_kuitansi"] = $seri_kuitansi_dt['no_kuitansi'];
        $dataAkunting["no_mr"] = $_POST['no_mr_val'];
        $dataAkunting["kode_tc_trans_kasir"] = $dataTranskasir["kode_tc_trans_kasir"];
        $this->db->insert('ak_tc_transaksi', $dataAkunting);
        $new_id_ak_tc_transaksi = $this->db->insert_id();
        
        // jurnal accounting
        $this->create_jurnal($dataTranskasir, $dataAkunting, $new_id_ak_tc_transaksi);

        // update tgl keluar registrasi
        $tgl_keluar_pasien = $_POST['tgl_jam_keluar'].' '.date('H:i:s');

        $this->db->update('tc_registrasi', array('tgl_jam_keluar' => $tgl_keluar_pasien), array('no_registrasi' => $_POST['no_registrasi']) );
        
        $preview_billing_nk = $this->db->where_in('kode_trans_pelayanan', $str_to_array_nk)->get_where('tc_trans_pelayanan', array('no_registrasi' => $_POST['no_registrasi'], 'status_nk' => 1))->result();
        $preview_billing_um = $this->db->where_not_in('kode_trans_pelayanan', $str_to_array_nk)->where('(status_nk IS NULL or status_nk = 0) ')->get_where('tc_trans_pelayanan', array('no_registrasi' => $_POST['no_registrasi']))->result();

        $return = array();
        // costing billing untuk bpjs
        if( $_POST['kode_perusahaan_val'] == 120 ){
            $return_costing = $this->costing_billing($seri_kuitansi_dt['seri_kuitansi']);
            $return['redirect'] = $return_costing['redirect'];
        }

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
        }
        else
        {
            $this->db->trans_commit();
            $return['status'] = 200;
            $return['message'] = 'Proses Berhasil Dilakukan';
            $return['kode_perusahaan'] = $_POST['kode_perusahaan_val'];
            $return['billing_nk'] = count($preview_billing_nk);
            $return['billing_um'] = count($preview_billing_um);
            $return['kode_tc_trans_kasir'] = $dataTranskasir["kode_tc_trans_kasir"] ;
            echo json_encode($return);
        }
        
    }

    public function process_um(){
        
        // print_r($_POST);die;
        // print("<pre>".print_r($_POST,true)."</pre>");die;

        $this->load->library('accounting');

        $this->db->trans_begin();
        
        // get no seri kuitansi
        $seri_kuitansi_dt = $this->master->no_seri_kuitansi($_POST['no_registrasi']);
        // print_r($seri_kuitansi_dt);die;
        // insert tc_trans_kasir
        $dataTranskasir["kode_tc_trans_kasir"] = $this->master->get_max_number('tc_trans_kasir','kode_tc_trans_kasir');
        $dataTranskasir["seri_kuitansi"] = $seri_kuitansi_dt['seri_kuitansi'];
        $dataTranskasir["no_kuitansi"] = $seri_kuitansi_dt['no_kuitansi'];
        $dataTranskasir["no_induk"] = $this->session->userdata('user')->user_id; 
        $dataTranskasir["tgl_jam"] = $_POST['tgl_trans_kasir'].' '.date('H:i:s');

        $dataTranskasir["cash"] = isset($_POST['uang_dibayarkan_tunai']) ? (float)$_POST['uang_dibayarkan_tunai']:(float)0;
        $dataTranskasir["change"] = 0;

        // Tunai
        $dataTranskasir["tunai"] = isset($_POST['uang_dibayarkan_tunai']) ? (float)$_POST['uang_dibayarkan_tunai'] : (float)0;

        if($_POST['jenis_kartu'] == 'debet'){
            // debet
            $dataTranskasir["debet"] = isset($_POST['jumlah_bayar_non_tunai']) ? (float)$_POST['jumlah_bayar_non_tunai'] : (float)0;
            $dataTranskasir["no_debet"] = $_POST['nomor_kartu_non_tunai'];
            $dataTranskasir["no_batch_dc"] = $_POST['nomor_batch_non_tunai'];
            $dataTranskasir["kd_bank_dc"] = $_POST['kd_bank_non_tunai'];
        }else{
             // debet
             $dataTranskasir["debet"] = 0;
        }

        if($_POST['jenis_kartu'] == 'kredit'){
           // kredit
           $dataTranskasir["kredit"] = isset($_POST['jumlah_bayar_non_tunai']) ? (float)$_POST['jumlah_bayar_non_tunai'] : (float)0;
           $dataTranskasir["no_kredit"] = $_POST['nomor_kartu_non_tunai'];
           $dataTranskasir["no_batch_cc"] = $_POST['nomor_batch_non_tunai'];
           $dataTranskasir["kd_bank_cc"] = $_POST['kd_bank_non_tunai'];
        }else{
            // kredit
            $dataTranskasir["kredit"] = 0;
        }

        
        $dataTranskasir["no_mr"] = $_POST['no_mr_val'];
        $dataTranskasir["nama_pasien"] = $_POST['nama_pasien_val'];
        $dataTranskasir["no_registrasi"] = $_POST['no_registrasi'];
        $dataTranskasir["kode_perusahaan"] = $_POST['kode_perusahaan_val'];
        $dataTranskasir["keterangan"] = 'Pembayaran Uang Muka Pasien pada Loket';

        // NK Asuransi
        $dataTranskasir["nk_perusahaan"] = 0;
        $dataTranskasir["nk_karyawan"] = 0;
        $dataTranskasir["pembayar"] = $_POST['nama_pasien_val'];
        $dataTranskasir["potongan"] = 0;
        $dataTranskasir["discount"] = 0;
        $dataTranskasir["bill"] = $_POST['total_payment'];

        
        // kode shift
        $dataTranskasir["kode_shift"] = $_POST['shift'];
        $dataTranskasir["kode_loket"] = $_POST['loket'];
        // print_r($dataTranskasir);die;
        // insert tc trans kasir
        $this->db->insert('tc_trans_kasir', $dataTranskasir);
        $this->db->trans_commit();

        // insert trans bagian
        $dataTransKasirBagian["kode_tc_trans_kasir"] = $dataTranskasir["kode_tc_trans_kasir"];
        $dataTransKasirBagian["kode_bagian"] = $_POST['kode_bag_ri']; // kode bagian ruangan rawat inap
        $this->db->insert('tc_trans_kasir_bagian', $dataTransKasirBagian);
        $this->db->trans_commit();

        // insert ks_tc_trans_um
        $insertKsTcTransUm["kode_tc_trans_kasir"] =  $dataTranskasir["kode_tc_trans_kasir"];
        $insertKsTcTransUm["no_registrasi"] = $dataTranskasir["no_registrasi"];
        $insertKsTcTransUm["no_kunjungan"] = $_POST['no_kunjungan'];
        $insertKsTcTransUm["no_mr"] = $dataTranskasir["no_mr"];
        $insertKsTcTransUm["nama_pasien"] = $dataTranskasir["nama_pasien"];
        $insertKsTcTransUm["kode_bagian"] = $_POST['kode_bag_ri'];
        $insertKsTcTransUm["no_kuitansi"] = $dataTranskasir["no_kuitansi"];
        $insertKsTcTransUm["tunai"] = $dataTranskasir["tunai"];
        $insertKsTcTransUm["debit"] = isset($dataTranskasir["debet"]) ? $dataTranskasir["debet"] : 0;
        $insertKsTcTransUm["kredit"] = isset($dataTranskasir["kredit"]) ? $dataTranskasir["kredit"] : 0;
        $insertKsTcTransUm["jumlah"] = $dataTranskasir["bill"];
        // debet
        $insertKsTcTransUm["kd_bank_dc"] = isset($dataTranskasir["kd_bank_dc"]) ? $dataTranskasir["kd_bank_dc"] : '';
        $insertKsTcTransUm["kd_bank_cc"] = isset($dataTranskasir["kd_bank_cc"]) ? $dataTranskasir["kd_bank_cc"] : '';
        $insertKsTcTransUm["tgl_bayar"] = $dataTranskasir["tgl_jam"];
        $this->db->insert('ks_tc_trans_um', $insertKsTcTransUm);
        $this->db->trans_commit();
    

        // untuk masuk ke akunting
        $dataAkunting["seri_kuitansi"] = $seri_kuitansi_dt['seri_kuitansi'];
        $dataAkunting["no_bukti"] = $seri_kuitansi_dt['seri_kuitansi'].  $seri_kuitansi_dt['no_kuitansi'];
        $dataAkunting["tgl_transaksi"] = $_POST['tgl_trans_kasir'].' '.date('H:i:s');
        $dataAkunting["uraian_transaksi"] = 'Penerimaan UM Rawat Inap '.$seri_kuitansi_dt['seri_kuitansi'].' '.$_POST['no_mr_val'].' - '.$_POST['nama_pasien_val'];
        $dataAkunting["total_nominal"] = $dataTranskasir["bill"];
        $dataAkunting["nama_pasien"] = $_POST['nama_pasien_val'];
        $dataAkunting["no_kuitansi"] = $seri_kuitansi_dt['no_kuitansi'];
        $dataAkunting["no_mr"] = $_POST['no_mr_val'];
        $dataAkunting["kode_tc_trans_kasir"] = $dataTranskasir["kode_tc_trans_kasir"];
        $this->db->insert('ak_tc_transaksi', $dataAkunting);
        $new_id_ak_tc_transaksi = $this->db->insert_id();
        
        // jurnal accounting
        $this->create_jurnal_um($dataTranskasir, $dataAkunting, $new_id_ak_tc_transaksi);
        
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
        }
        else
        {
            $this->db->trans_commit();
            
            $return['status'] = 200;
            $return['message'] = 'Proses Berhasil Dilakukan';
            $return['kode_tc_trans_kasir'] = $dataTranskasir["kode_tc_trans_kasir"] ;
            echo json_encode($return);
        }
        
    }

    public function process_apt(){
        
        // print_r($_POST);die;

        $this->load->library('accounting');

        $this->db->trans_begin();
        
        // get no seri kuitansi
        $seri_kuitansi_dt = $this->master->no_seri_kuitansi_apt($_POST['kode_trans_far']);
        // print_r($seri_kuitansi_dt);die;
        // insert tc_trans_kasir
        $dataTranskasir["kode_tc_trans_kasir"] = $this->master->get_max_number('tc_trans_kasir','kode_tc_trans_kasir');
        $dataTranskasir["seri_kuitansi"] = $seri_kuitansi_dt['seri_kuitansi'];
        $dataTranskasir["no_kuitansi"] = $seri_kuitansi_dt['no_kuitansi'];
        $dataTranskasir["no_induk"] = $this->session->userdata('user')->user_id; 
        $dataTranskasir["tgl_jam"] = $_POST['tgl_trans_kasir'].' '.date('H:i:s');

        $change = ( $_POST['uang_dibayarkan_tunai'] > $_POST['jumlah_bayar_tunai'] ) ? $_POST['uang_dibayarkan_tunai'] - $_POST['jumlah_bayar_tunai'] : 0;

        $dataTranskasir["cash"] = isset($_POST['uang_dibayarkan_tunai']) ? (float)$_POST['uang_dibayarkan_tunai']:(float)0;
        $dataTranskasir["change"] = $change;

        // tunai
        $dataTranskasir["tunai"] = isset($_POST['uang_dibayarkan_tunai']) ? (float)$_POST['uang_dibayarkan_tunai']:(float)0;
        // debet
        $dataTranskasir["debet"] = isset($_POST['jumlah_bayar_debet']) ? (float)$_POST['jumlah_bayar_debet'] : (float)0;
        $dataTranskasir["no_debet"] = $_POST['nomor_kartu_debet'];
        $dataTranskasir["no_batch_dc"] = $_POST['nomor_batch_debet'];
        $dataTranskasir["kd_bank_dc"] = $_POST['kd_bank_dc'];
        // kredit
        $dataTranskasir["kredit"] = isset($_POST['jumlah_bayar_kredit'])?(float)$_POST['jumlah_bayar_kredit']:(float)0;
        $dataTranskasir["no_kredit"] = $_POST['nomor_kartu_kredit'];
        $dataTranskasir["no_batch_cc"] = $_POST['nomor_batch_kredit'];
        $dataTranskasir["kd_bank_cc"] = $_POST['kd_bank_cc'];
        
        $dataTranskasir["no_mr"] = $_POST['no_mr_val'];
        $dataTranskasir["nama_pasien"] = $_POST['nama_pasien_val'];
        // $dataTranskasir["no_registrasi"] = $_POST['no_registrasi'];
        $dataTranskasir["kode_perusahaan"] = $_POST['kode_perusahaan_val'];
        
        $dataTranskasir["pembayar"] = $_POST['nama_pasien_val'];

        // nk karyawan
        if(isset($_POST['metode_bon_karyawan'])){
            $dataTranskasir["nk_karyawan"] = $_POST['jumlah_nk'];
            $dataTranskasir["no_mr_karyawan"] = $_POST['no_mr_val'];
            $dataTranskasir["keterangan"] = 'Bon Karyawan a.n '.$_POST['nama_pasien_val'];
        }else{
            $dataTranskasir["nk_karyawan"] = 0;
            $dataTranskasir["no_mr_karyawan"] = '';
            $dataTranskasir["keterangan"] = 'Pembayaran Administrasi Pasien pada Loket';
        }
        $dataTranskasir["nk_perusahaan"] = 0;
        $dataTranskasir["kode_perusahaan"] = $_POST['kode_perusahaan_val'];
        

        $potongan_diskon = ($_POST['total_payment'] * ($_POST['jumlah_diskon']/100));
        $sisa_bill = $_POST['total_payment'] - $potongan_diskon;
        $dataTranskasir["potongan"] = $potongan_diskon;
        $dataTranskasir["discount"] = ($_POST['jumlah_diskon'])?$_POST['jumlah_diskon']:0;
        $dataTranskasir["bill"] = $sisa_bill;

        // print_r($dataTranskasir);die;
        // kode shift
        $dataTranskasir["kode_shift"] = $_POST['shift'];
        $dataTranskasir["kode_loket"] = $_POST['loket'];
        // insert tc trans kasir
        $this->db->insert('tc_trans_kasir', $dataTranskasir);

        // insert trans bagian
        $bon_karyawan_nk = isset($_POST['metode_bon_karyawan']) ? 1 : NULL;
        $str_to_array = explode(',', $_POST['array_data_checked']);
        foreach ($str_to_array as $key => $kode_trans_pelayanan) {
            $data_trans = $this->db->get_where('tc_trans_pelayanan', array('kode_trans_pelayanan' => $kode_trans_pelayanan))->row();
            $dataTransKasirBagian["kode_tc_trans_kasir"] = $dataTranskasir["kode_tc_trans_kasir"];
            $dataTransKasirBagian["kode_bagian"] = $data_trans->kode_bagian;
            $this->db->insert('tc_trans_kasir_bagian', $dataTransKasirBagian);
            // update status, kode_tc_trans_kasir tc_trans_pelayanan per item

            $data_tr_pl = array('status_selesai' => 3, 'kode_tc_trans_kasir' => $dataTranskasir["kode_tc_trans_kasir"], 'status_nk' => $bon_karyawan_nk);
            $this->db->update('tc_trans_pelayanan', $data_tr_pl, array('kode_trans_pelayanan' => $kode_trans_pelayanan));
            // update status bayar farmasi
            if($data_trans->kode_trans_far != null || $data_trans->kode_trans_far != 0){
                $this->db->update('fr_tc_far', array('status_bayar' => 1), array('kode_trans_far' => $data_trans->kode_trans_far)  );
            }
            $this->db->trans_commit();
        }
        
        // untuk masuk ke akunting
        $dataAkunting["seri_kuitansi"] = $seri_kuitansi_dt['seri_kuitansi'];
        $dataAkunting["no_bukti"] = $seri_kuitansi_dt['seri_kuitansi'].  $seri_kuitansi_dt['no_kuitansi'];
        $dataAkunting["tgl_transaksi"] = $_POST['tgl_trans_kasir'].' '.date('H:i:s');
        $dataAkunting["uraian_transaksi"] = 'Pendapatan Pasien '.$seri_kuitansi_dt['seri_kuitansi'].' '.$_POST['no_mr_val'].' - '.$_POST['nama_pasien_val'];
        $dataAkunting["total_nominal"] = $dataTranskasir["bill"];
        $dataAkunting["nama_pasien"] = $_POST['nama_pasien_val'];
        $dataAkunting["no_kuitansi"] = $seri_kuitansi_dt['no_kuitansi'];
        $dataAkunting["no_mr"] = $_POST['no_mr_val'];
        $dataAkunting["kode_tc_trans_kasir"] = $dataTranskasir["kode_tc_trans_kasir"];
        $this->db->insert('ak_tc_transaksi', $dataAkunting);
        $new_id_ak_tc_transaksi = $this->db->insert_id();
        
        // jurnal accounting
        $this->create_jurnal($dataTranskasir, $dataAkunting, $new_id_ak_tc_transaksi);

        $preview_billing_nk = $this->db->get_where('tc_trans_pelayanan', array('kode_trans_far' => $_POST['kode_trans_far'], 'status_nk' => 1))->result();

        
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
        }
        else
        {
            $this->db->trans_commit();
            
            $return['status'] = 200;
            $return['message'] = 'Proses Berhasil Dilakukan';
            $return['kode_perusahaan'] = $_POST['kode_perusahaan_val'];
            $return['billing_nk'] = count($preview_billing_nk);
            echo json_encode($return);
        }
        
    }

    public function process_update_klas_tarif(){
        
        // print_r($_POST);die;

        $this->db->trans_begin();
        $kode_ruangan_new = $_POST['ri_no_ruangan'];
        $kode_bagian_new = $_POST['ri_ruangan'];
        $kode_klas_new = $_POST['ri_klas_ruangan'];
        // get detail transaksi
        $transaksi = $this->db->where('kode_tarif is not null')->get_where('tc_trans_pelayanan', array('no_registrasi' => $_POST['no_registrasi']))->result();

        $transaksi_kamar = $this->db->get_where('tc_trans_pelayanan', array('no_registrasi' => $_POST['no_registrasi'], 'jenis_tindakan' => 1))->result();

        // echo '<pre>';print_r($transaksi_kamar);die;

        // get data rawat inap
        $ri = $this->db->get_where('view_ri_tc_rawatinap', array('no_registrasi' => $_POST['no_registrasi']))->row();

        $getData = [];
        foreach ($transaksi as $key => $value) {

            // get new tarif from new kode klas
            $new_tarif = $this->db->order_by('revisi_ke', 'DESC')->get_where('mt_master_tarif_detail', array('kode_tarif' => $value->kode_tarif, 'kode_klas' => $kode_klas_new))->row();

            if(!empty($new_tarif)){
                // oksigen
                if(in_array($value->jenis_tindakan, array(7))){
                    $bill_rs = $new_tarif->bill_rs * $value->jumlah;
                }else{
                    $bill_rs = $new_tarif->bill_rs;
                }

                $getData[] = array(
                    "kode_trans_pelayanan" => $value->kode_trans_pelayanan,
                    "bill_rs" => $bill_rs,
                    "bill_dr1" => $new_tarif->bill_dr1,
                    "bill_dr2" => $new_tarif->bill_dr2,
                    "bill_dr3" => $new_tarif->bill_dr3,
                    "kamar_tindakan" => $new_tarif->kamar_tindakan,
                    "biaya_lain" => $new_tarif->biaya_lain,
                    "obat" => $new_tarif->obat,
                    "alkes" => $new_tarif->alkes,
                    "alat_rs" => $new_tarif->alat_rs,
                    "adm" => $new_tarif->adm,
                    "overhead" => $new_tarif->overhead,
                    "bhp" => $new_tarif->bhp,
                    "pendapatan_rs" => $new_tarif->pendapatan_rs,
                );

            }

        }

        // echo '<pre>';print_r($getData);die;
        foreach ($transaksi_kamar as $key => $val_kmr) {
            
            // ICU
            if(!in_array($val_kmr->kode_bagian, array('031001') )){
                 // tarif ruangan
                $tarif_ruangan = $this->db->join('mt_bagian', 'mt_bagian.kode_bagian=mt_master_tarif_ruangan.kode_bagian', 'left')->get_where('mt_master_tarif_ruangan', array('kode_klas' => $kode_klas_new, 'mt_master_tarif_ruangan.kode_bagian' => $kode_bagian_new))->row();

                $tarif = isset($tarif_ruangan->harga_r)?$tarif_ruangan->harga_r:0;
                
                $kamar[] = array(
                    "kode_trans_pelayanan" => $val_kmr->kode_trans_pelayanan,
                    "nama_tindakan" => 'Ruangan '.$tarif_ruangan->nama_bagian,
                    "bill_rs" => $tarif,
                );
            }


        }

        // echo '<pre>';print_r($kamar);die;

        // update tarif kamar
        if(count($kamar) > 0){
            $this->db->update_batch('tc_trans_pelayanan', $kamar, 'kode_trans_pelayanan'); 
        }
        // update tarif tindakan
        if(count($getData) > 0){
            $this->db->update_batch('tc_trans_pelayanan', $getData, 'kode_trans_pelayanan'); 
        }
        

        // update klas pasien
        $this->db->where('kode_ri', $ri->kode_ri)->update('ri_tc_rawatinap', array('kode_ruangan' => $kode_ruangan_new, 'kelas_pas' => $kode_klas_new, 'bag_pas' => $kode_bagian_new));


        
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
        }
        else
        {
            $this->db->trans_commit();
            
            $return['status'] = 200;
            $return['message'] = 'Proses Berhasil Dilakukan';
            echo json_encode($return);
        }
        
    }

    public function costing_billing($type)
    {
        $this->db->trans_begin();
        $no_registrasi = ($this->input->post('no_registrasi'))?$this->regex->_genRegex($this->input->post('no_registrasi'),'RGXINT'):0;

        /*get data trans pelayanan by no registrasi from sirs*/
        $sirs_data = json_decode($this->Csm_billing_pasien->getDetailData($no_registrasi));
        // echo "<pre>"; print_r($sirs_data);die;
        // insert or update data
        $this->Csm_billing_pasien->insertDataFirstTime($sirs_data, $no_registrasi);

        // if( $sirs_data->group && $sirs_data->kasir_data && $sirs_data->trans_data )
        //     $this->Csm_billing_pasien->insertDataFirstTime($sirs_data, $no_registrasi);
        if( $this->input->post('no_sep_val') ){
            $dataexc = array(
                'csm_rp_no_sep' => $this->regex->_genRegex(strtoupper($this->input->post('no_sep_val')), 'RGXQSL'),
                'is_submitted' => $this->regex->_genRegex('Y', 'RGXAZ'),
            );
            $dataexc['updated_date'] = date('Y-m-d H:i:s');
            $dataexc['updated_by'] = $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL');
            $exc_qry = $this->db->update('csm_reg_pasien', $dataexc, array('no_registrasi' => $no_registrasi));
            $newId = $no_registrasi;
        }
        
        // $this->db->delete('csm_dokumen_export', array('no_registrasi' => $no_registrasi, 'is_adjusment' => NULL));
        /*created document name*/
        // $createDocument = $this->Csm_billing_pasien->createDocument($no_registrasi, $type);
        // foreach ($createDocument as $k_cd => $v_cd) {
        //     # code...
        //     $explode = explode('-', $v_cd);
        //     /*explode result*/
        //     $named = str_replace('BILL','',$explode[0]);
        //     $no_mr = $explode[1];
        //     $exp_no_registrasi = $explode[2];
        //     $unique_code = $explode[3];
        //     $no_kunjungan = isset($explode[4])?$explode[4]:0;
            
        //     /*create and save download file pdf*/
        //     //$cbpModule = new Csm_billing_pasien;
        //     if( $this->cbpModule->getContentPDF($exp_no_registrasi, $named, $unique_code, 'F', $no_kunjungan) ) :
        //         /*save document to database*/
        //         /*csm_reg_pasien*/
        //         $filename = $named.'-'.$no_mr.$exp_no_registrasi.$unique_code.'.pdf';
        //         $doc_save = array(
        //             'no_registrasi' => $this->regex->_genRegex($exp_no_registrasi, 'RGXQSL'),
        //             'csm_dex_nama_dok' => $this->regex->_genRegex($filename, 'RGXQSL'),
        //             'csm_dex_jenis_dok' => $this->regex->_genRegex($v_cd, 'RGXQSL'),
        //             'csm_dex_fullpath' => $this->regex->_genRegex('uploaded/casemix/log/'.$filename.'', 'RGXQSL'),
        //         );
                
        //         $doc_save['created_date'] = date('Y-m-d H:i:s');
        //         $doc_save['created_by'] = $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL');
        //         /*check if exist*/
        //         if ( $this->Csm_billing_pasien->checkIfDokExist($exp_no_registrasi, $filename) == FALSE ) {
        //             $this->db->insert('csm_dokumen_export', $doc_save);
        //         }
        //     endif;
        //     /*insert database*/
        // }
        
        // return array('redirect' => 'casemix/Csm_billing_pasien/mergePDFFiles/'.$no_registrasi.'/'.$type.'', 'created_by' => $doc_save['created_by'], 'created_date' => $this->tanggal->formatDateTime($doc_save['created_date']));
        // redirect show nota bill
        return array('redirect' => 'billing/Billing/print_preview?no_registrasi='.$no_registrasi.'');
    }

    public function create_jurnal($trans, $akunting, $id_ak_tc_transaksi)
    {
        
        $kode_tc_trans_kasir = $trans['kode_tc_trans_kasir'];
                
        $config_jurnal = array(
            'kode_tc_trans_kasir' => $kode_tc_trans_kasir,
            'seri_kuitansi' => $trans['seri_kuitansi'],
            'id_ak_tc_transaksi' => $id_ak_tc_transaksi,
            'transaksi' => $trans,
        );
        
        // jurnal debet
        $jurnal_debet_data = $this->accounting->get_jurnal_debet($config_jurnal);
        if( count($jurnal_debet_data) > 0 ){
            $this->db->insert_batch('ak_tc_transaksi_det', $jurnal_debet_data);
        }
        // echo '<pre>';print_r($jurnal_debet_data);die;
        
        // jurnal kredit
        $jurnal_kredit_data = $this->accounting->get_jurnal_kredit($config_jurnal);
        if( count($jurnal_kredit_data) > 0 ){
            $this->db->insert_batch('ak_tc_transaksi_det', $jurnal_kredit_data);
        }
        // print_r($jurnal_kredit_data);die;
        
        // jurnal um
        $jurnal_um = $this->accounting->get_jurnal_um($config_jurnal);
        if( count($jurnal_um) > 0 ){
            $this->db->insert_batch('ak_tc_transaksi_det', $jurnal_um);
        }
        // print_r($jurnal_um);die;
        // jurnak kredit dr
        $jurnal_kredit_dr = $this->accounting->get_jurnal_kredit_dokter($config_jurnal);
        if( count($jurnal_kredit_dr) > 0 ){
            $this->db->insert_batch('ak_tc_transaksi_det', $jurnal_kredit_dr);
        }
        // print_r($jurnal_kredit_dr);die;

        $jurnal_obat = $this->accounting->get_jurnal_obat($config_jurnal);
        if( count($jurnal_obat) > 0 ){
            $this->db->insert_batch('ak_tc_transaksi_det', $jurnal_obat);
        }
        // print_r($jurnal_obat);die;

        return true;

    }

    public function create_jurnal_um($trans, $akunting, $id_ak_tc_transaksi){
        
        $kode_tc_trans_kasir = $trans['kode_tc_trans_kasir'];
                
        $config_jurnal = array(
            'kode_tc_trans_kasir' => $kode_tc_trans_kasir,
            'seri_kuitansi' => $trans['seri_kuitansi'],
            'id_ak_tc_transaksi' => $id_ak_tc_transaksi,
            'transaksi' => $trans,
        );
        
        // jurnal debet
        $jurnal_debet_um_data = $this->accounting->get_jurnal_transaksi_um($config_jurnal, 'debet');
        $this->db->insert('ak_tc_transaksi_det', $jurnal_debet_um_data);

        // jurnal kredit_um
        $jurnal_kredit_um_data = $this->accounting->get_jurnal_transaksi_um($config_jurnal, 'kredit');
        $this->db->insert('ak_tc_transaksi_det', $jurnal_kredit_um_data);

        return true;

    }

    function update_penjamin(){
        
        $data = array('kode_perusahaan' => $_POST['kode_perusahaan']);
        
        $this->db->trans_begin();
        // tc_registrasi
        $this->db->update('tc_registrasi', $data, array('no_registrasi' => $_POST['no_reg']));

        // tc_trans_pelayanan
        $this->db->update('tc_trans_pelayanan', $data, array('no_registrasi' => $_POST['no_reg']));

        // tc_trans_kasir
        $this->db->update('tc_trans_kasir', $data, array('no_registrasi' => $_POST['no_reg']));

        // fr_tc_pesan_resep
        $this->db->update('fr_tc_pesan_resep', $data, array('no_registrasi' => $_POST['no_reg']));

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
        }
        else
        {
            $this->db->trans_commit();
            
            $return['status'] = 200;
            $return['message'] = 'Proses Berhasil Dilakukan';
            echo json_encode($return);
        }

    }

    public function process_add_tindakan(){

        // print_r($_POST);die;
        // form validation
        if($_POST['kode_jenis_tindakan'] == 1){
            $this->form_validation->set_rules('total_ruangan', 'Total Ruangan', 'trim|required');
        }else{
            $this->form_validation->set_rules('pl_kode_tindakan_hidden', 'Tindakan', 'trim|required');
            $this->form_validation->set_rules('no_mr_val', 'No MR', 'trim|required');
            $this->form_validation->set_rules('pl_tgl_transaksi', 'Tanggal', 'trim');
            $this->form_validation->set_rules('no_kunjungan', 'Unit/Bagian', 'trim|required');
        }
        
        
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

            $kode_trans_pelayanan = $this->master->get_max_number('tc_trans_pelayanan', 'kode_trans_pelayanan');
            $data_kunjungan = $this->db->get_where('tc_kunjungan', array('no_kunjungan' => $_POST['no_kunjungan']))->row();
            
            $dataexc = array(
                /*form hidden input default*/
                'no_kunjungan' => $this->regex->_genRegex($this->input->post('no_kunjungan'),'RGXINT'),
                'no_registrasi' => $this->regex->_genRegex($this->input->post('no_registrasi'),'RGXINT'),
                'kode_kelompok' => $this->regex->_genRegex($this->input->post('kode_kelompok_val'),'RGXINT'),
                'kode_perusahaan' => $this->regex->_genRegex($this->input->post('kode_perusahaan_val'),'RGXINT'),
                'no_mr' => $this->regex->_genRegex($this->input->post('no_mr_val'),'RGXQSL'),
                'nama_pasien_layan' => $this->regex->_genRegex($this->input->post('nama_pasien_val'),'RGXQSL'),
                'kode_bagian_asal' => $this->regex->_genRegex($data_kunjungan->kode_bagian_asal,'RGXQSL'),
                /*end form hidden input default*/
                'kode_bagian' => $this->regex->_genRegex($data_kunjungan->kode_bagian_tujuan,'RGXQSL'),
                'kode_klas' => $this->regex->_genRegex($this->input->post('kode_klas'),'RGXINT'),
                'tgl_transaksi' => $this->regex->_genRegex($this->input->post('pl_tgl_transaksi'),'RGXQSL'),   
            );
            
            $dataexc['kode_trans_pelayanan'] = $kode_trans_pelayanan;
            /*form hidden after select tindakan*/
            $dataexc['kode_tarif'] = $this->regex->_genRegex($this->input->post('kode_tarif'),'RGXINT');
            $dataexc['jenis_tindakan'] = ($this->regex->_genRegex($this->input->post('jenis_tindakan'),'RGXINT')!=0)?$this->regex->_genRegex($this->input->post('jenis_tindakan'),'RGXINT'):3;
            $dataexc['nama_tindakan'] = ($_POST['kode_jenis_tindakan'] == 1) ? $this->regex->_genRegex($this->input->post('nama_ruangan'),'RGXQSL') : $this->regex->_genRegex($this->input->post('nama_tindakan'),'RGXQSL');
            $dataexc['kode_master_tarif_detail'] = $this->regex->_genRegex($this->input->post('kode_master_tarif_detail'),'RGXQSL');

            /*status NK*/
            $status_nk = ( $this->input->post('jenis_tarif')==120 ) ? 1 : 0 ;
            $dataexc['status_nk'] = $status_nk;
            $dataexc['is_update_by_kasir'] = 1;
            $dataexc['jumlah'] = 1;

            /*Penunjang Medis */
            if( isset($_POST['kode_penunjang']) AND $_POST['kode_penunjang']!=0 ){
                $dataexc['kode_penunjang'] = $this->input->post('kode_penunjang');
            }

            if($_POST['kode_jenis_tindakan'] != 1){
                 /*get tarif mulitiple kode dokter*/
                 $tarifDokter = $this->tarif->getTarifMultipleDokter( $this->input->post('pl_kode_dokter_hidden') );

                 $tarifInsert = $this->tarif->getTarifForinsert();
 
                 $mergeData = array_merge($dataexc, $tarifDokter, $tarifInsert);
            }else{
                $dataexc['bill_rs'] = $_POST['total_ruangan'];
                $dataexc['total'] = $_POST['total_ruangan'];
            }
           

            // echo "<pre>";print_r($dataexc);die;
            
            /*save tc_trans_pelayanan*/
            $this->db->insert('tc_trans_pelayanan', $mergeData);

            /*save logs*/
            $this->logs->save('tc_trans_pelayanan', $kode_trans_pelayanan, 'insert new record on '.$this->title.' module', json_encode($dataexc),'kode_trans_pelayanan');

            
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

    public function verify_code() {
        $this->load->library('bcrypt');
        $code = $this->input->post('kode_verifikasi');
        $password = $this->input->post('password');
        $no_registrasi = $this->input->post('no_registrasi');
        // Cek ke tabel user_approval_modul
        $this->db->where('function', 'cashier');
        $this->db->where('secret_code', $code);
        $this->db->join('tmp_user', 'tmp_user.user_id = user_approval_modul.user_id');
        $userApproval = $this->db->get('user_approval_modul')->row();

        if (!$userApproval) {
            echo json_encode(['status' => 401, 'message' => 'Password dan kode verifikasi salah']);
            exit;
        }

        if(!$this->bcrypt->check_password($password,$userApproval->password)){
            echo json_encode(['status' => 401, 'message' => 'Password dan kode verifikasi salah']);
            exit;
        }

        // Update status is_print_kuitansi pada tc_trans_kasir
        if ($no_registrasi) {
            $query = "UPDATE tc_trans_kasir set is_print_kuitansi = (is_print_kuitansi + 1) WHERE no_registrasi = ".$no_registrasi." ";
            $this->db->query($query);
        }
        echo json_encode(['status' => 200, 'message' => 'Password benar, status print kuitansi diupdate']);
    }

    
    public function update_is_print_kuitansi() {
        if (!$this->input->is_ajax_request()) {
            show_404();
        }
        $no_registrasi = $this->input->post('no_registrasi');
        if (!$no_registrasi) {
            echo json_encode(['status' => 400, 'message' => 'No registrasi tidak ditemukan']);
            return;
        }
        // Update is_print_kuitansi +1
        $this->db->set('is_print_kuitansi', 'is_print_kuitansi+1', false);
        $this->db->where('no_registrasi', $no_registrasi);
        $this->db->update('tc_trans_kasir');
        // echo $this->db->last_query();die;
        if ($this->db->affected_rows() > 0) {
            echo json_encode(['status' => 200, 'message' => 'Status print kuitansi berhasil diupdate']);
        } else {
            echo json_encode(['status' => 500, 'message' => 'Gagal update status print kuitansi']);
        }
    }

}

/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */

