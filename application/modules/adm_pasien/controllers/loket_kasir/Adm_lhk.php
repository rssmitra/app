<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Adm_lhk extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'adm_pasien/loket_kasir/Adm_lhk');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('adm_pasien/loket_kasir/Adm_lhk_model', 'Adm_lhk');
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
            'flag' => $_GET['flag'],
        );
        /*show datatables*/
        $data['dataTables'] = $this->load->view('loket_kasir/Adm_lhk/temp_trans_pasien', $data, true);
        /*load view index*/
        $this->load->view('loket_kasir/Adm_lhk/index', $data);
    }

    public function find_data()
    {   
        $output = array( "data" => http_build_query($_POST) . "\n" );
        echo json_encode($output);
    }
    
    public function merge_data_registrasi(){
        $ex_arr = explode( ',' , $_POST['value']);
        $kode = $this->Adm_pasien->get_first_registrasi($ex_arr);
        $string = $this->Adm_pasien->merge_transaksi( $kode );
        return true;
    }
    
    public function get_data()
    {
        /*get data from model*/
        $list = $this->Adm_lhk->get_datatables();
        // echo "<pre>";print_r($list);die;
        $data = array();
        $no = $_POST['start'];

        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center"></div>';
            $row[] = $row_list->kode_tc_trans_kasir;
            $row[] = $row_list->no_registrasi;
            $row[] = '<div class="center">'.$no.'</div>';
            $row[] = $row_list->seri_kuitansi.' - '.$row_list->no_kuitansi;
            $row[] = $this->tanggal->formatDatedmY($row_list->tgl_transaksi);
            $row[] = $row_list->no_mr;
            $row[] = $row_list->nama_pasien;
            $row[] = '<div style="text-align: right">'.number_format((int)$row_list->tunai).'</div>';
            $row[] = '<div style="text-align: right">'.number_format((int)$row_list->debet).'</div>';
            // $row[] = '<div style="text-align: right">'.number_format((int)$row_list->kredit).'</div>';
            $row[] = '<div style="text-align: right">'.number_format((int)$row_list->potongan).'</div>';
            $row[] = '<div style="text-align: right">'.number_format((int)$row_list->piutang).'</div>';
            $row[] = '<div style="text-align: right">'.number_format((int)$row_list->nk_karyawan).'</div>';
            $row[] = '<div style="text-align: right">'.number_format((int)$row_list->billing).'</div>';
            $petugas = ($row_list->fullname)?$row_list->fullname:$row_list->nama_pegawai.'<small style="color: red; font-weight:bold"> (av)</small>';
            $row[] = '<small style="font-size: 10px !important">'.ucfirst($petugas).'<br>'.$this->tanggal->formatDateTimeFormDmy($row_list->tgl_transaksi).'</small>';
            $data[] = $row;

            $arr_tunai[] = $row_list->tunai;
            $arr_debet[] = $row_list->debet;
            $arr_nk_perusahaan[] = $row_list->piutang;
            $arr_nk_karyawan[] = $row_list->nk_karyawan;
            $arr_total_billing[] = $row_list->billing;


            
        }
        
        
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Adm_lhk->count_all(),
                        "recordsFiltered" => $this->Adm_lhk->count_filtered(),
                        "label_tunai" => array_sum($arr_tunai),
                        "label_debet" => array_sum($arr_debet),
                        "label_nk_perusahaan" => array_sum($arr_nk_perusahaan),
                        "label_nk_karyawan" => array_sum($arr_nk_karyawan),
                        "label_total_billing" => array_sum($arr_total_billing),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function get_resume_kasir()
    {
        /*get data from model*/
        $list = $this->Adm_lhk->get_resume_kasir(); 
        echo json_encode($list);
    }

    public function getDetailTransaksi($kode_tc_trans_kasir, $no_registrasi){
        if($no_registrasi != 0){
            $result = json_decode($this->Billing->getDetailData($no_registrasi));
        }else{
            $result = array();
        }
        $akunting = $this->Adm_lhk->get_jurnal_akunting($kode_tc_trans_kasir);
        $data = array(
            'result' => $result,
            'transaksi' => $akunting['data'],
            'jurnal' => $akunting['data'],
        );
        // echo '<pre>';print_r($akunting);die;
        $html = $this->load->view('loket_kasir/Adm_lhk/detail_transaksi_view', $data, true);
        echo json_encode(array('html' => $html));
    }

    public function export_excel(){
        $list = $this->Adm_lhk->get_data(); 
        $data = array(
            'title'     => 'Perjanjian Rawat Jalan',
            'fields'    => $list->field_data(),
            'data'      => $list->result(),
        );
        // echo '<pre>';print_r($data);die;
        $this->load->view('loket_kasir/Adm_lhk/excel_view', $data);

    }

}


/* End of file example.php */
/* Location: ./application/modules/example/controllers/example.php */
