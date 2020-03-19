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
        /*load library*/
        $this->load->library('Form_validation');
        $this->load->library('stok_barang');
        $this->load->library('tarif');

        $this->load->module('Templates/Templates.php');
        $this->load->module('Templates/Export_data.php');
        Modules::run('Templates/Export_data');
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

    public function get_data_ri()
    {
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

        $data = array(
            'title' => 'Billing Pasien',
            'breadcrumbs' => $this->breadcrumbs->show(),
            'no_registrasi' => $no_registrasi,
            'tipe' => $tipe,
            'data' => $result,
            'kunjungan' => $this->Billing->getRiwayatKunjungan($no_registrasi),
        );
        //echo '<pre>';print_r($result);die;
        $data['header'] = $this->load->view('Billing/temp_header_dt', $data, true);

        if($tipe=='RJ'){
            $this->load->view('Billing/temp_trans_kasir', $data, false);
            //$html = $this->Billing->getDetailBillingRJKasir($no_registrasi, $tipe, $data);
        }else{
            $html = $this->Billing->getDetailBillingRI($no_registrasi, $tipe, $data);
        }

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


}

/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
