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
        // echo '<pre>'; print_r($arr_sum_total);die;

        $this->load->view('Billing/form_payment', $data);
    }

    public function payment_um_view($no_registrasi, $tipe) { 
        /*define variable data*/
        $result = json_decode($this->Billing->getDetailData($no_registrasi));
        // get total sudah dibayar
        $arr_sum_total = array();
        foreach($result->kasir_data as $row){
            $arr_sum_total[] = (int)$row->bill;
        }

        $data = array(
            'title' => 'Form Pembayaran UM Pasien',
            'no_registrasi' => $no_registrasi,
            'tipe' => $tipe,
            'breadcrumbs' => $this->breadcrumbs->show(),
            'result' => $result,
            'total_paid' => array_sum($arr_sum_total),
        );
        // echo '<pre>'; print_r($result->kasir_data);die;

        $this->load->view('Billing/form_payment_um', $data);
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
        //print_r($data);die;

        if($tipe=='RJ'){
            $html = $this->Billing->getDetailBillingRJ($no_registrasi, $tipe, $data);
        }else{
            $html = $this->Billing->getDetailBillingRI($no_registrasi, $tipe, $data);
        }

        echo json_encode(array('html' => $html));
        
    }

    public function getDetailLess($no_registrasi, $tipe){
        
        /*get detail data billing*/
        $data = json_decode($this->Billing->getDetailData($no_registrasi));

        if($tipe=='RJ'){
            $html = $this->Billing->getDetailBillingRJLess($no_registrasi, $tipe, $data);
        }else{
            $html = $this->Billing->getDetailBillingRILess($no_registrasi, $tipe, $data);
        }

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
        );
        //echo '<pre>';print_r($data['kunjungan']);die;

        $html = $this->load->view('Billing/temp_trans_kasir', $data, true);

        echo json_encode(array('html' => $html));

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
        );
        // echo '<pre>';print_r($data);die;
        $data['header'] = $this->load->view('Billing/temp_header_dt', $data, true);

        $this->load->view('Billing/temp_trans_kasir', $data, false);

    }

    public function load_billing_view($no_registrasi, $tipe){
        /*get detail data billing*/
        $result = json_decode($this->Billing->getDetailData($no_registrasi));
        $grouping = $this->Billing->groupingTransaksiByDate($result->trans_data);

        $data = array(
            'title' => 'Billing Pasien',
            'breadcrumbs' => $this->breadcrumbs->show(),
            'no_registrasi' => $no_registrasi,
            'tipe' => $tipe,
            'data' => $result,
            'kunjungan' => $grouping,
        );
        // echo '<pre>'; print_r($data);die;
        $this->load->view('Billing/data_billing_view', $data, false);

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
            'kunjungan' => $grouping,
        );
        // echo '<pre>';print_r($data);die;
        $data['header'] = $this->load->view('Billing/temp_header_dt', $data, true);
        $this->load->view('Billing/cetakBilling_sem_rssm', $data, false);

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
        // echo '<pre>';print_r($grouping);die;
        //$this->load->view('Billing/cetakKuitansi_view', $data, false);
        //$data['header'] = $this->load->view('Billing/temp_header_dt', $data, true);
        $this->load->view('Billing/cetakKuitansi_view', $data, false);
         
    }

    public function process(){
        
        $this->load->library('accounting');

        $this->db->trans_begin();
        // print_r($_POST);die;
        // get no seri kuitansi
        $seri_kuitansi_dt = $this->master->no_seri_kuitansi($_POST['no_registrasi']);
        // insert tc_trans_kasir
        $dataTranskasir["kode_tc_trans_kasir"] = $this->master->get_max_number('tc_trans_kasir','kode_tc_trans_kasir');
        $dataTranskasir["seri_kuitansi"] = $seri_kuitansi_dt['seri_kuitansi'];
        $dataTranskasir["no_kuitansi"] = $seri_kuitansi_dt['no_kuitansi'];
        $dataTranskasir["no_induk"] = $this->session->userdata('user')->user_id; 
        $dataTranskasir["tgl_jam"] = date("Y-m-d H:i:s");
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
        $dataTranskasir["no_registrasi"] = $_POST['no_registrasi'];
        $dataTranskasir["kode_perusahaan"] = $_POST['kode_perusahaan_val'];
        $dataTranskasir["keterangan"] = 'Pembayaran Administrasi Pasien pada Loket';

        if( in_array($_POST['kode_kelompok_val'], array(4,7,8,11,12,13,14,15,16)) ){
            // nk_karyawan
            $dataTranskasir["nk_karyawan"] = $_POST['total_nk'];
            $dataTranskasir["no_mr_karyawan"] = $_POST['no_mr_val'];
            $potongan_nk = $_POST['total_nk'];
        }else{
            $dataTranskasir["nk_karyawan"] = 0;
        }

        if( $_POST['kode_kelompok_val'] != 1 ){
            $dataTranskasir["nk_perusahaan"] = $_POST['total_nk'];
            $dataTranskasir["kode_perusahaan"] = $_POST['kode_perusahaan_val'];
            $dataTranskasir["pembayar"] = $_POST['perusahaan_penjamin'];
            $potongan_nk = $_POST['total_nk'];
        }else{
            $dataTranskasir["pembayar"] = $_POST['nama_pasien_val'];
            $dataTranskasir["nk_perusahaan"] = 0;
        }

        $potongan_diskon = ($_POST['total_payment'] * ($_POST['jumlah_diskon']/100));
        $sisa_bill = $_POST['total_payment'] - $potongan_diskon;
        $dataTranskasir["potongan"] = $potongan_diskon;
        $dataTranskasir["bill"] = $sisa_bill;

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
            $this->db->trans_commit();
        }
        
        // untuk masuk ke akunting
        $dataAkunting["seri_kuitansi"] = $seri_kuitansi_dt['seri_kuitansi'];
        $dataAkunting["no_bukti"] = $seri_kuitansi_dt['seri_kuitansi'].  $seri_kuitansi_dt['no_kuitansi'];
        $dataAkunting["tgl_transaksi"] = date("Y-m-d H:i:s");
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

        $return = array();
        // costing billing untuk bpjs
        if( $_POST['kode_perusahaan_val'] == 120 ){
            $return_costing = $this->costing_billing($seri_kuitansi_dt['seri_kuitansi']);
            $return['redirect'] = $return_costing['redirect'];
        }else{
            $sirs_data = json_decode($this->Csm_billing_pasien->getDetailData($_POST['no_registrasi'], true));
            $this->Csm_billing_pasien->insertDataFirstTime($sirs_data, $_POST['no_registrasi']);
        }

        $preview_billing_nk = $this->db->where_in('kode_trans_pelayanan', $str_to_array_nk)->get_where('tc_trans_pelayanan', array('no_registrasi' => $_POST['no_registrasi'], 'status_nk' => 1))->result();
        $preview_billing_um = $this->db->where_not_in('kode_trans_pelayanan', $str_to_array_nk)->where('(status_nk IS NULL or status_nk = 0) ')->get_where('tc_trans_pelayanan', array('no_registrasi' => $_POST['no_registrasi']))->result();
        // print_r($preview_billing_um);die;
        
        // update tgl keluar registrasi
        $this->db->update('tc_registrasi', array('tgl_jam_keluar' => date('Y-m-d H:i:s')), array('no_registrasi' => $_POST['no_registrasi']) );

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
            echo json_encode($return);
        }
        
    }


    public function create_jurnal($trans, $akunting, $id_ak_tc_transaksi){
        
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

    public function costing_billing($type)
    {

        $this->db->trans_begin();
        $no_registrasi = ($this->input->post('no_registrasi'))?$this->regex->_genRegex($this->input->post('no_registrasi'),'RGXINT'):0;

        /*get data trans pelayanan by no registrasi from sirs*/
        $sirs_data = json_decode($this->Csm_billing_pasien->getDetailData($no_registrasi));

        if( $sirs_data->group && $sirs_data->kasir_data && $sirs_data->trans_data )
            $this->Csm_billing_pasien->insertDataFirstTime($sirs_data, $no_registrasi);

        if( $this->input->post('no_sep_val') ){
            /*csm_reg_pasien*/
            $dataexc = array(
                'csm_rp_no_sep' => $this->regex->_genRegex(strtoupper($this->input->post('no_sep_val')), 'RGXQSL'),
                'is_submitted' => $this->regex->_genRegex('Y', 'RGXAZ'),
            );
            $dataexc['updated_date'] = date('Y-m-d H:i:s');
            $dataexc['updated_by'] = $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL');
            $exc_qry = $this->db->update('csm_reg_pasien', $dataexc, array('no_registrasi' => $no_registrasi));
            $newId = $no_registrasi;
            $this->logs->save('csm_reg_pasien', $newId, 'update record', json_encode($dataexc), 'csm_rp_id');
            
        }
        
        $this->db->delete('csm_dokumen_export', array('no_registrasi' => $no_registrasi, 'is_adjusment' => NULL));
        /*created document name*/
        $createDocument = $this->Csm_billing_pasien->createDocument($no_registrasi, $type);
        // print_r($createDocument);die;
        foreach ($createDocument as $k_cd => $v_cd) {
            # code...
            $explode = explode('-', $v_cd);
            /*explode result*/
            $named = str_replace('BILL','',$explode[0]);
            $no_mr = $explode[1];
            $exp_no_registrasi = $explode[2];
            $unique_code = $explode[3];

            /*create and save download file pdf*/
            //$cbpModule = new Csm_billing_pasien;
            if( $this->cbpModule->getContentPDF($exp_no_registrasi, $named, $unique_code, 'F') ) :
            /*save document to database*/
            /*csm_reg_pasien*/
            $filename = $named.'-'.$no_mr.$exp_no_registrasi.$unique_code.'.pdf';
            
            $doc_save = array(
                'no_registrasi' => $this->regex->_genRegex($exp_no_registrasi, 'RGXQSL'),
                'csm_dex_nama_dok' => $this->regex->_genRegex($filename, 'RGXQSL'),
                'csm_dex_jenis_dok' => $this->regex->_genRegex($v_cd, 'RGXQSL'),
                'csm_dex_fullpath' => $this->regex->_genRegex('uploaded/casemix/'.$filename.'', 'RGXQSL'),
            );
            $doc_save['created_date'] = date('Y-m-d H:i:s');
            $doc_save['created_by'] = $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL');
            /*check if exist*/
            if ( $this->Csm_billing_pasien->checkIfDokExist($exp_no_registrasi, $filename) == FALSE ) {
                $this->db->insert('csm_dokumen_export', $doc_save);
            }
            endif;
            /*insert database*/
        }
        
        return array('redirect' => 'casemix/Csm_billing_pasien/mergePDFFiles/'.$no_registrasi.'/'.$type.'', 'created_by' => $doc_save['created_by'], 'created_date' => $this->tanggal->formatDateTime($doc_save['created_date']));

    }


}

/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
