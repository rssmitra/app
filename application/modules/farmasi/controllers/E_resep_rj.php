<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class E_resep_rj extends MX_Controller {

    /*function constructor*/
    function __construct() 
    {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'farmasi/E_resep_rj');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('E_resep_rj_model', 'E_resep_rj');
        $this->load->model('Etiket_obat_model', 'Etiket_obat');
        $this->load->model('Retur_obat_model', 'Retur_obat');
        $this->load->model('registration/Reg_pasien_model', 'Reg_pasien');
        $this->load->model('pelayanan/Pl_pelayanan_model', 'Pl_pelayanan');

        $this->load->module('farmasi/E_resep');
        // load library
        $this->load->library('Print_direct');
        $this->load->library('Print_escpos'); 
        /*enable profiler*/
        $this->output->enable_profiler(false);
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'Title';

    }

    public function index() 
    { 
        /*define variable data*/
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show(),
            'flag' => 'RJ'
        );
        /*load view index*/
        $this->load->view('E_resep_rj/index', $data);
    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->E_resep_rj->get_datatables();
        if(isset($_GET['search']) AND $_GET['search']==TRUE){
            $this->find_data(); exit;
        }
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center" width="30px">'.$no.'</div>';
            $row[] = '<div class="center"><a href="#" onclick="getMenu('."'farmasi/Entry_resep_ri_rj/form/".$row_list->kode_pesan_resep."?mr=".$row_list->no_mr."&tipe_layanan=RJ'".')" class="label label-primary">'.$row_list->kode_pesan_resep.'</a></div>';
            $row[] = '<div class="center">'.$this->tanggal->formatDateTimeFormDmy($row_list->tgl_pesan).'</div>';
            $row[] = '<div class="center"><b>'.$row_list->no_mr.'</b></div>';
            $row[] = strtoupper($row_list->nama_pasien);
            $row[] = $row_list->nama_pegawai.'<br>'.ucwords($row_list->nama_bagian);
            $penjamin = (!empty($row_list->nama_perusahaan))?$row_list->nama_perusahaan:$row_list->nama_kelompok;
            $no_sep = ($row_list->kode_perusahaan == 120) ? '<br>('.$row_list->no_sep.')' : '';
            $row[] = ucwords($penjamin).$no_sep;
            $row[] = $row_list->diagnosa_akhir;
            // $status_tebus = ($row_list->status_tebus ==  1)?'<label class="label label-xs label-success">Selesai</label>':'<label class="label label-xs label-warning">Belum diproses</label>';
            // $row[] = '<div class="center">'.$status_tebus.'</div>';
            
            // $row[] = $row_list->diagnosa_akhir;
            if($row_list->status_bayar != 1) {
                if($row_list->status_transaksi == 1){
                    $row[] = '<div class="center">
                            <label class="label lebel-xs label-success"> <i class="fa fa-check-circle"></i> Selesai</label>
                          </div>';    
                }else{
                    $row[] = '<div class="center">
                            <label class="label lebel-xs label-warning" alt="Belum diselesaikan"> <i class="fa fa-exclamation-circle"></i> Pending </label>
                          </div>';
                }
                
            }else{
                $row[] = '<div class="center"><a href="#" class="label lebel-xs label-primary" style="cursor: pointer !important"><i class="fa fa-money"></i> Lunas </a></div>';
            }

            // if( $row_list->status_resep == 'telaah_resep' ) {
            //     $btn_action = '<a href="#">Telaah Resep</a>';    
            // }elseif ( $row_list->status_resep == 'pengambilan_obat' ) {
            //     $btn_action = '<a href="#">Pengambilan Obat</a>';
            // }elseif ( $row_list->status_resep == 'proses_racik_obat') {
            //     $btn_action = '<a href="#">Proses Racik Obat</a>';
            // }elseif ( $row_list->status_resep == 'penyerahan_obat') {
            //     $btn_action = '<a href="#">Penyerahan Obat</a>';
            // }else{
            //     $btn_action = '<a href="#" class="btn btn-xs btn-inverse" onclick="getMenu('."'farmasi/Entry_resep_ri_rj/form/".$row_list->kode_pesan_resep."?mr=".$row_list->no_mr."&tipe_layanan=RJ'".')">Telaah Resep</a>'; 
            // }

            // $row[] = '<div class="center">'.$btn_action.'</div>';

            // $row[] = '<div class="center">'.$row_list->jumlah_r.'</div>';
            // $row[] = $row_list->nama_lokasi;
            
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->E_resep_rj->count_all(),
                        "recordsFiltered" => $this->E_resep_rj->count_filtered(),
                        "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function getAntrianResep()
    {
        /*get data from model*/
        $list = $this->E_resep_rj->get_data();
        //output to json format
        echo json_encode($list);
    }

    public function form($id='')
    {
        /*if id is not null then will show form edit*/
        /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Entry Resep '.strtolower($this->title).'', 'E_resep_rj/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*get value by id*/
        $tipe_layanan = strtoupper($_GET['tipe_layanan']);
        $data['kode_pesan_resep'] = $id;
        $data['value'] = $this->E_resep_rj->get_by_id($id);
        $data['trans_farmasi'] = $this->E_resep_rj->get_trans_farmasi($id);
        
        $data['riwayat_penunjang'] = $this->get_riwayat_penunjang($_GET['mr']);
        // echo '<pre>';print_r($data['riwayat_penunjang']);die;
        /*no mr default*/
        $data['no_mr'] = $_GET['mr'];
        /*initialize flag for form*/
        $data['tipe_layanan'] = $tipe_layanan;
        $data['str_tipe_layanan'] = ($tipe_layanan=='RJ')?'Rajal':'Ranap';
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('E_resep_rj/form', $data);
    }

    public function frm_telaah_resep($id='')
    {
        /*if id is not null then will show form edit*/
        /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Entry Resep '.strtolower($this->title).'', 'E_resep_rj/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*get value by id*/
        $tipe_layanan = strtoupper($_GET['tipe_layanan']);
        $data['kode_pesan_resep'] = $id;
        $data['value'] = $this->E_resep_rj->get_by_id($id);
        $data['trans_farmasi'] = $this->E_resep_rj->get_trans_farmasi($id);
        $eresep = new E_resep;
        $data['resep_cart'] = $eresep->get_cart_resep($data['value']->no_kunjungan);
        
        $data['riwayat_penunjang'] = $this->get_riwayat_penunjang($_GET['mr']);
        // echo '<pre>';print_r($data['riwayat_penunjang']);die;
        /*no mr default*/
        $data['no_mr'] = $_GET['mr'];
        /*initialize flag for form*/
        $data['tipe_layanan'] = $tipe_layanan;
        $data['str_tipe_layanan'] = ($tipe_layanan=='RJ')?'Rajal':'Ranap';
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('E_resep_rj/form_telaah_resep', $data);
    }

}


/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
