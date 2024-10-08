<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Adm_kasir extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'adm_pasien/loket_kasir/Adm_kasir');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('adm_pasien/loket_kasir/Adm_kasir_model', 'Adm_kasir');
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
        $title = ($_GET['pelayanan'] == 'RI') ? 'Rawat Inap (RI)' : ($_GET['flag']=='umum') ? 'Umum dan Asuransi' : 'BPJS Kesehatan';
        $data = array(
            'title' => 'Kasir '.$title,
            'breadcrumbs' => $this->breadcrumbs->show(),
            'flag' => $_GET['flag'],
            'pelayanan' => $_GET['pelayanan'],
        );
        /*show datatables*/
        $data['dataTables'] = $this->load->view('loket_kasir/Adm_kasir/temp_trans_pasien', $data, true);
        /*load view index*/
        $this->load->view('loket_kasir/Adm_kasir/index', $data);
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
        $list = $this->Adm_kasir->get_datatables();
        // echo '<pre>';print_r($list);die;
        $data = array();
        $arr_total = array();
        $tgl_keluar_null = [];
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            
            if( $_GET['pelayanan'] != 'RI' ){
                if( substr($row_list[0]['kode_bagian_masuk'], 0, 2) != '03'){
                    $no++;
                    $row = array();
                    // sum total
                    $total = $this->master->sumArrayByKey($row_list, 'total');
                    $arr_total[] = $total;
                    $row[] = '<div class="center"></div>';
                    $row[] = $row_list[0]['no_registrasi'];
                    $row[] = '<div class="center">'.$no.'</div>';
                    $row[] = '<a style="color: blue; font-weight: bold" href="#" onclick="getMenu('."'billing/Billing/viewDetailBillingKasir/".$row_list[0]['no_registrasi']."/".$_GET['pelayanan']."?flag=".$_GET['flag']."'".')">'.$row_list[0]['no_mr'].'</div>';
                    $row[] = $row_list[0]['nama_pasien'];
                    if($_GET['flag']=='bpjs'){
                        $row[] = $row_list[0]['no_sep'];
                    }
                    // $row[] = $row_list[0]['no_mr'];
                    $row[] = ucwords($row_list[0]['nama_bagian']);
                    $row[] = ($row_list[0]['nama_perusahaan'])?$row_list[0]['nama_perusahaan']:'UMUM';
                    $row[] = $this->tanggal->formatDateTimeFormDmy($row_list[0]['tgl_jam_masuk']);
                    $row[] = $this->tanggal->formatDateTimeFormDmy($row_list[0]['tgl_transaksi']);
                    $row[] = ucwords($row_list[0]['petugas']);
                    if( $row_list[0]['status_batal'] == 1 ){
                        $row[] = '<div class="center"><span style="color: red; font-weight: bold">Batal</span></div>';
                    }else{
                        if( $total > 0 ){
                            $row[] = '<div class="pull-right">
                                        <a style="color: blue; font-weight: bold" href="#" onclick="show_modal_medium_return_json('."'billing/Billing/getDetailLess/".$row_list[0]['no_registrasi']."/".$_GET['pelayanan']."'".', '."'RINCIAN BILLING PASIEN'".')">'.number_format($total).',-</a>
                                        <input type="hidden" class="total_billing_class" value="'.$total.'">
                                      </div>';
                        }else{
                            $row[] = '<div class="center"><i class="fa fa-check-circle bigger-150 green"></i></div>';
                        }
                    }
                    
                    $data[] = $row;
                }
            }
              
        }

        // echo '<pre>';print_r($tgl_keluar);die;
        
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Adm_kasir->count_all(),
                        "recordsFiltered" => $this->Adm_kasir->count_filtered(),
                        "data" => $data,
                        "total_billing" => array_sum($arr_total),
                );
        //output to json format
        echo json_encode($output);
    }

    public function get_total_billing()
    {
        /*get data from model*/
        $list = $this->Adm_kasir->get_total_billing(); 
        
        $arr_submit = array();
        $arr_non_submit = array();
        foreach($list as $val){
            foreach($val as $row){
                $arr_submit[] = ( $row['kode_tc_trans_kasir'] != NULL ) ? $row['total_billing'] : 0;
                $arr_non_submit[] = ( $row['kode_tc_trans_kasir'] == NULL ) ? $row['total_billing'] : 0;
            }
        }
        // echo '<pre>'; print_r($arr_submit);die;

        $result = array(
            'total_submit' => array_sum($arr_submit),
            'total_non_submit' => array_sum($arr_non_submit),
        );

        echo json_encode($result);
    }

    public function getDetailTransaksi($no_registrasi){
        $result = json_decode($this->Billing->getDetailData($no_registrasi));
        $akunting = $this->Adm_kasir->get_jurnal_akunting($no_registrasi);
        $log_activity = $this->Billing->getLogActivity($no_registrasi);
        $data = array(
            'result' => $result,
            'transaksi' => $akunting['data'],
            'jurnal' => $akunting['data'],
            'log_activity' => $log_activity,
        );
        // echo '<pre>';print_r($data);die;
        $html = $this->load->view('loket_kasir/Adm_kasir/detail_transaksi_view', $data, true);
        echo json_encode(array('html' => $html));
    }

    public function export_excel(){
      $list = $this->Adm_kasir->get_data(); 
      // echo "<pre>";print_r($list);die;
      foreach ($list as $row_list) {
            
        if( $_GET['pelayanan'] != 'RI' ){
          $no = 0;
            if( substr($row_list[0]['kode_bagian_masuk'], 0, 2) != '03'){
                
                $no++;
                $row = array();
                // sum total
                $total = $this->master->sumArrayByKey($row_list, 'total');
                $status = ($total > 0) ? 'Belum dibayar' : 'Lunas';
                $arr_total[] = $total;
                $getData[] = array(
                  'no_mr' =>  $row_list[0]['no_mr'],
                  'nama_pasien' =>  $row_list[0]['nama_pasien'],
                  'no_sep' =>  $row_list[0]['no_sep'],
                  'nama_bagian' =>  $row_list[0]['nama_bagian'],
                  'tgl_jam_masuk' =>  $this->tanggal->formatDateTimeFormDmy($row_list[0]['tgl_jam_masuk']),
                  'total' =>  $total,
                  'status' =>  $status,
                );
            }
        }
          
    }
      
      $data = array(
          'title'     => 'Kasir Rawat Jalan BPJS',
          'fields'    => ['no_mr', 'nama_pasien', 'no_sep', 'nama_bagian', 'tgl_jam_masuk', 'status', 'total'],
          'data'      => $getData,
      );
      
      $this->load->view('loket_kasir/Adm_kasir/excel_view', $data);
    
    }

  }


/* End of file example.php */
/* Location: ./application/modules/example/controllers/example.php */
