<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Eks_pendapatan extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'eksekutif/Eks_pendapatan');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('eksekutif/Eks_pendapatan_model', 'Eks_pendapatan');
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
            'flag' => isset($_GET['flag'])?$_GET['flag']:'RJ',
        );
        /*show datatables*/
        $data['dataTables'] = $this->load->view('eksekutif/Eks_pendapatan/temp_trans_pasien', $data, true);
        /*load view index*/
        $this->load->view('eksekutif/Eks_pendapatan/index', $data);
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
        $list = $this->Eks_pendapatan->get_datatables();
        // print_r($list);die;
        $data = array();
        $no = $_POST['start'];

        foreach ($list['trans_1'] as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center">'.$no.'</div>';
            $row[] = $row_list->seri_kuitansi.' - '.$row_list->no_kuitansi;
            $row[] = '<div class="center">'.$this->tanggal->formatDateTime($row_list->tgl_jam).'</div>';
            $row[] = strtoupper($row_list->nama_pasien);
            $row[] = $row_list->nama_perusahaan;
            $row[] = $row_list->nama_bagian;
            // non tunai
            $nontunai = (int)$row_list->debet + (int)$row_list->kredit;
            $row[] = '<div style="text-align: right">'.number_format((int)$row_list->tunai).'</div>';
            $row[] = '<div style="text-align: right">'.number_format((int)$nontunai).'</div>';
            // $row[] = '<div style="text-align: right">'.number_format((int)$row_list->kredit).'</div>';
            $row[] = '<div style="text-align: right">'.number_format((int)$row_list->potongan).'</div>';
            $row[] = '<div style="text-align: right">'.number_format((int)$row_list->piutang).'</div>';
            $row[] = '<div style="text-align: right">'.number_format((int)$row_list->nk_karyawan).'</div>';
            $row[] = '<div style="text-align: right">'.number_format((int)$row_list->billing).'</div>';
            $petugas = ($row_list->fullname)?$row_list->fullname:$row_list->nama_pegawai.'<small style="color: red; font-weight:bold"> (av)</small>';
            $row[] = '<div class="center" style="font-size:11px">'.ucfirst($petugas).'</div>';
            $data[] = $row;

            $arr_tunai[] = (int)$row_list->tunai;
            $arr_nontunai[] = (int)$nontunai;
            $arr_potongan[] = (int)$row_list->potongan;
            $arr_piutang[] = (int)$row_list->piutang;
            $arr_nk_karyawan[] = (int)$row_list->nk_karyawan;
            $arr_billing[] = (int)$row_list->billing;
        }
        $getDataPasien = [];
        $getJenisTindaka = [];
        $getDataJenisTindakan = [];

        foreach ($list['trans_2'] as $row_dt){
            $getDataPasien[$row_dt->no_mr] = [
                'no_registrasi' => $row_dt->no_registrasi,
                'kode_tc_trans_kasir' => $row_dt->kode_tc_trans_kasir,
                'jenis_tindakan' => $row_dt->jenis_tindakan,
                'kode_jenis_tindakan' => $row_dt->kode_jenis_tindakan,
                'no_mr' => $row_dt->no_mr,
                'nama_pasien' => $row_dt->nama_pasien,
                'nama_perusahaan' => $row_dt->nama_perusahaan,
                'kode_perusahaan' => $row_dt->kode_perusahaan,
                'no_sep' => $row_dt->no_sep,
                'nama_bagian' => $row_dt->nama_bagian,
                'tgl_jam' => $row_dt->tgl_jam,
                'seri_kuitansi' => $row_dt->seri_kuitansi,            
                'no_kuitansi' => $row_dt->no_kuitansi                   
            ];
            $getJenisTindakan[$row_dt->kode_jenis_tindakan] = $row_dt->jenis_tindakan;
            $getDataJenisTindakan[$row_dt->no_mr][$row_dt->kode_jenis_tindakan] = $row_dt;
        }
        ksort($getJenisTindakan);
        $respons = array(
            'data_pasien' => $getDataPasien,
            'data_trans' => $getDataJenisTindakan,
            'fields' => $getJenisTindakan,
        );
        // echo "<pre>";print_r($getJenisTindakan);die;
        $html = $this->load->view('eksekutif/Eks_pendapatan/index_2', $respons, true);
        
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Eks_pendapatan->count_all(),
                        "recordsFiltered" => $this->Eks_pendapatan->count_filtered(),
                        "data" => $data,
                        'tunai' => array_sum($arr_tunai),
                        'nontunai' => array_sum($arr_nontunai),
                        'potongan' => array_sum($arr_potongan),
                        'piutang' => array_sum($arr_piutang),
                        'nk_karyawan' => array_sum($arr_nk_karyawan),
                        'billing' => array_sum($arr_billing),
                        'html_trans' => $html,
                        'from_tgl' => isset($_GET['from_tgl'])?$_GET['from_tgl']:date('Y-m-d'),
                        'to_tgl' => isset($_GET['to_tgl'])?$_GET['to_tgl']:date('Y-m-d'),
                        
                );
        //output to json format
        echo json_encode($output);
    }

    public function getDetailTransaksi($kode_tc_trans_kasir, $no_registrasi, $jenis_tindakan=''){
        if($no_registrasi != 0){
            $result = json_decode($this->Billing->getDetailData($no_registrasi));
        }else{
            $result = array();
        }
        $akunting = $this->Eks_pendapatan->get_jurnal_akunting($kode_tc_trans_kasir);
        $data = array(
            'result' => $result,
            'trans_data' => $result->trans_data,
            'transaksi' => $akunting['data'],
            'jurnal' => $akunting['data'],
            'jenis_tindakan' => $jenis_tindakan,
        );
        // echo '<pre>';print_r($data['trans_data']);die;
        $html = $this->load->view('eksekutif/Eks_pendapatan/detail_transaksi_view', $data, true);
        echo json_encode(array('html' => $html));
    }

    public function export_excel(){
        if($_GET['type'] == 1){
            $list = $this->Eks_pendapatan->get_data(); 
            $data = array(
                'title'    => $_GET['title'],
                'fields'    => $list->field_data(),
                'data'      => $list->result(),
            );
            $this->load->view('eksekutif/Eks_pendapatan/excel_view', $data);
        }
        
        
        if($_GET['type'] == 2){
            $list = $this->Eks_pendapatan->get_data2(); 

            foreach ($list as $row_dt){
                $getDataPasien[$row_dt->no_mr] = [
                    'no_registrasi' => $row_dt->no_registrasi,
                    'kode_tc_trans_kasir' => $row_dt->kode_tc_trans_kasir,
                    'jenis_tindakan' => $row_dt->jenis_tindakan,
                    'kode_jenis_tindakan' => $row_dt->kode_jenis_tindakan,
                    'no_mr' => $row_dt->no_mr,
                    'nama_pasien' => $row_dt->nama_pasien,
                    'nama_perusahaan' => $row_dt->nama_perusahaan,
                    'kode_perusahaan' => $row_dt->kode_perusahaan,
                    'no_sep' => $row_dt->no_sep,
                    'nama_bagian' => $row_dt->nama_bagian,
                    'tgl_jam' => $row_dt->tgl_jam,
                    'seri_kuitansi' => $row_dt->seri_kuitansi,            
                    'no_kuitansi' => $row_dt->no_kuitansi                   
                ];
                $getJenisTindakan[$row_dt->kode_jenis_tindakan] = $row_dt->jenis_tindakan;
                $getDataJenisTindakan[$row_dt->no_mr][$row_dt->kode_jenis_tindakan] = $row_dt;
            }
            ksort($getJenisTindakan);
            $data = array(
                'title'    => $_GET['title'],
                'data_pasien' => $getDataPasien,
                'data_trans' => $getDataJenisTindakan,
                'fields' => $getJenisTindakan,
            );
            // echo '<pre>';print_r($data);die;

            $this->load->view('eksekutif/Eks_pendapatan/excel_view_2', $data);
        }

    }

}


/* End of file example.php */
/* Location: ./application/modules/example/controllers/example.php */
