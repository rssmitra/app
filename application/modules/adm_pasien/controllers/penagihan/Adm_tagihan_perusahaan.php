<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Adm_tagihan_perusahaan extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'adm_pasien/penagihan/Adm_tagihan_perusahaan');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('adm_pasien/penagihan/Adm_tagihan_perusahaan_model', 'Adm_tagihan_perusahaan');
        $this->load->model('adm_pasien/Adm_pasien_model', 'Adm_pasien');
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
        /*show datatables*/
        $data['dataTables'] = $this->load->view('penagihan/Adm_tagihan_perusahaan/temp_trans_pasien', $data, true);
        /*load view index*/
        $this->load->view('penagihan/Adm_tagihan_perusahaan/index', $data);
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
        $list = $this->Adm_tagihan_perusahaan->get_datatables();
        // print_r($list);die;
        $data = array();
        $arr_total = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $jumlah_tagihan = $row_list->jml_bill - $row_list->jml_tunai - $row_list->jml_debet - $row_list->jml_kredit;
            $no++;
            $row = array();
            $row[] = '<div class="center">'.$no.'</div>';
            $row[] = $row_list->nama_perusahaan;
            $row[] = '<div class="pull-right"><a href="#">'.number_format($jumlah_tagihan).',-</a></div>';
            $row[] = '<div class="center">'.$row_list->disc.'</div>';
            $row[] = '<div class="center"><a href="#" class="btn btn-xs btn-primary">Data Pasien</a></div>';
            $row[] = '<div class="center"><a href="#" class="btn btn-xs btn-primary">Tagih</a></div>';
            $data[] = $row;
              
        }
        
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Adm_tagihan_perusahaan->count_all(),
                        "recordsFiltered" => $this->Adm_tagihan_perusahaan->count_filtered(),
                        "data" => $data,
                        "total_billing" => array_sum($arr_total),
                );
        //output to json format
        echo json_encode($output);
    }

    public function get_total_billing()
    {
        /*get data from model*/
        $list = $this->Adm_tagihan_perusahaan->get_total_billing(); 
        
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

}


/* End of file example.php */
/* Location: ./application/modules/example/controllers/example.php */
