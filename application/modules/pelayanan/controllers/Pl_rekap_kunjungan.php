<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pl_rekap_kunjungan extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'registration/Pl_rekap_kunjungan');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            redirect(base_url().'login');exit;
        }
        /*load model*/
        $this->load->model('Pl_rekap_kunjungan_model', 'Pl_rekap_kunjungan');
        $this->load->model('pelayanan/Pl_pelayanan_model', 'Pl_pelayanan');
        $this->load->model('pelayanan/Pl_pelayanan_pm_model', 'Pl_pelayanan_pm');
        $this->load->model('pelayanan/Pl_pelayanan_igd_model', 'Pl_pelayanan_igd');
        $this->load->model('pelayanan/Pl_pelayanan_mcu_model', 'Pl_pelayanan_mcu');
        $this->load->model('registration/Reg_pasien_model', 'Reg_pasien');
        /*enable profiler*/
        $this->output->enable_profiler(false);
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'Title';

    }

    public function index() { 
        /*define variable data*/
        /*get data from model*/
        $list = $this->Pl_rekap_kunjungan->get_data();
       // print_r($this->db->last_query());die;
        $data = array();
        $rekap_batal = array();
        foreach ($list as $row_list) {
            $getData[$row_list->tujuan_bagian][$row_list->dokter][] = $row_list;
            $resume[$row_list->tujuan_bagian][] = $row_list;
            $rekap_dr[$row_list->dokter][] = $row_list;
            // substring
            $substring = substr($row_list->kode_bagian, 0,2);
            $substr[$substring][] = $row_list;
            if($row_list->status_batal == 1){
                $rekap_batal[] = $row_list;
            }

        }

        // rekap berdasarkan unit
        foreach($resume as $key=>$val){
            $total_unit[] = ['unit' => $key, 'total' => count($resume[$key])];
        }

        // rekap berdasarkan dokter
        foreach($rekap_dr as $key=>$val){
            $nama_dr = (!empty($key))?$key:'<span class="red bold">-tidak ada dokter-</spam>';
            $total_dr[] = ['nama_dr' => $nama_dr, 'total' => count($rekap_dr[$key])];
        }
        // rekap berdasarkan instalasi
        foreach($substr as $key=>$val){
            if($key == '01'){
                $total_kunjungan[] = ['unit' => 'POLIKLINIK', 'total' => count($substr[$key])];
            }
        }

        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show(),
            'getData' => $getData,
            "resume" => $total_unit,
            "rekap" => $total_kunjungan,
            "rekap_dr" => $total_dr,
            "rekap_batal" => count($rekap_batal),
        );

        // echo "<pre>";print_r($data);die;

        /*load view index*/
        $this->load->view('Pl_rekap_kunjungan/index', $data);
    }
    
    public function get_data()
    {
        /*get data from model*/
        $list = $this->Pl_rekap_kunjungan->get_datatables();
       // print_r($this->db->last_query());die;
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            if(isset($_GET['bagian_tujuan']) and $_GET['bagian_tujuan']=='020101'){
                $kunjungan = $this->Reg_pasien->get_detail_kunjungan_by_no_kunjungan($row_list->no_kunjungan);
                $bday = new Datetime($kunjungan->tgl_lhr);
                $today = new Datetime(date('Y-m-d H:i:s'));
                $diff = $today->diff($bday);
                $cetak = ($kunjungan->status_keluar==4)?'<li><a href="#" onclick="cetak_surat_kematian('.$row_list->no_registrasi.','.$row_list->no_kunjungan.','.$diff->y.')">Cetak Surat Kematian</a></li>':'';

                $keracunan = $this->Pl_pelayanan_igd->get_keracunan($row_list->no_kunjungan);
                $cetak .= !empty($keracunan)?'<li><a href="#" onclick="cetak_surat_keracunan('.$row_list->no_kunjungan.','.$row_list->no_mr.')">Cetak Surat Keracunan</a></li>':'';
            }else if(isset($_GET['bagian_tujuan']) and $_GET['bagian_tujuan']=='010901'){
                $hasil = $this->Pl_pelayanan_mcu->get_hasil_by_kode_gcu($row_list->kode_gcu);
                $cetak = ($hasil)?'<li><a href="#" onclick="cetak_hasil('.$row_list->kode_gcu.','.$row_list->id_pl_tc_poli.')" > Cetak Hasil</a></li>':'';
            }else{
                $cetak = '';
            }

            // $trans_kasir = $this->Pl_pelayanan->cek_transaksi_kasir($row_list->no_registrasi, $row_list->no_kunjungan);
            $flag_rollback ='unsubmit';
            // $rollback_btn = ($trans_kasir==true)?'<li><a href="#" onclick="rollback('.$row_list->no_registrasi.','.$row_list->no_kunjungan.')">Rollback</a></li>':'';
            $rollback_btn = '<li><a href="#" onclick="rollback('.$row_list->no_registrasi.','.$row_list->no_kunjungan.','."'".$flag_rollback."'".')">Rollback</a></li>';
            
            $row[] = '<div class="center">'.$no.'</div>';
            $row[] = $row_list->no_mr;
            $row[] = $row_list->nama_pasien;
            $row[] = $row_list->tujuan_bagian;
            $row[] = $row_list->dokter;
            $row[] = '<div class="left"><span class="green" style="font-weight: bold">In&nbsp;&nbsp;&nbsp;</span> '.$this->tanggal->formatDateTimeFormDmy($row_list->tgl_jam_poli).'<br><span class="red" style="font-weight: bold">Out&nbsp;</span> '.$this->tanggal->formatDateTimeFormDmy($row_list->tgl_keluar_poli).' </div>';

            if($row_list->status_batal==1){
                $status_periksa = '<label class="label label-danger"><i class="fa fa-times-circle"></i> Batal Berobat</label>';
            }else{
                $status_periksa = ($row_list->tgl_keluar_poli==NULL)?'<label class="label label-warning"><i class="fa fa-info-circle"></i> Belum diperiksa</label>':'<label class="label label-success"><i class="fa fa-check-circle"></i> Selesai</label>';
            }

            $row[] = '<div class="center">'.$status_periksa.'</div>';

            $data[] = $row;

            $resume[$row_list->tujuan_bagian][] = $row;
            $rekap_dr[$row_list->dokter][] = $row;
            // substring
            $substring = substr($row_list->kode_bagian, 0,2);
            $substr[$substring][] = $row;
            if($row_list->status_batal == 1){
                $rekap_batal[] = $row;
            }

        }

        // rekap berdasarkan unit
        foreach($resume as $key=>$val){
            $total_unit[] = ['unit' => $key, 'total' => count($resume[$key])];
        }

        // rekap berdasarkan dokter
        foreach($rekap_dr as $key=>$val){
            $nama_dr = (!empty($key))?$key:'<span class="red bold">-tidak ada dokter-</spam>';
            $total_dr[] = ['nama_dr' => $nama_dr, 'total' => count($rekap_dr[$key])];
        }
        // rekap berdasarkan instalasi
        foreach($substr as $key=>$val){
            if($key == '01'){
                $total_kunjungan[] = ['unit' => 'POLIKLINIK', 'total' => count($substr[$key])];
            }
        }
        

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Pl_rekap_kunjungan->count_all(),
                        "recordsFiltered" => $this->Pl_rekap_kunjungan->count_filtered(),
                        "data" => $data,
                        "resume" => $total_unit,
                        "rekap" => $total_kunjungan,
                        "rekap_dr" => $total_dr,
                        "rekap_batal" => count($rekap_batal),
                );
        //output to json format
        echo json_encode($output);
    }

    public function find_data()
    {   
        $output = array( "data" => http_build_query($_POST) . "\n" );
        echo json_encode($output);
    }


}

/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
