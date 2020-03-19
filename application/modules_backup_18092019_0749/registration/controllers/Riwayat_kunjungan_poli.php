<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Riwayat_kunjungan_poli extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'registration/Riwayat_kunjungan_poli');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            redirect(base_url().'login');exit;
        }
        /*load model*/
        $this->load->model('Riwayat_kunjungan_poli_model', 'Riwayat_kunjungan_poli');
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

        $kode_bagian = (isset($_GET['kode_bagian']))?$_GET['kode_bagian']:0;
        $type = (isset($_GET['type']))?$_GET['type']:'';
        /*define variable data*/
        $data = array(
            'title' => 'Riwayat Kunjungan',
            'breadcrumbs' => $this->breadcrumbs->show(),
            'kode_bagian' => $kode_bagian ,
            'type' => $type
        );
        /*load view index*/
        $this->load->view('Riwayat_kunjungan_poli/index', $data);
    }
    
    public function get_data()
    {
        /*get data from model*/
        $list = $this->Riwayat_kunjungan_poli->get_datatables();
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

            $trans_kasir = $this->Pl_pelayanan->cek_transaksi_kasir($row_list->no_registrasi, $row_list->no_kunjungan);
            $flag_rollback = ($trans_kasir!=true)?'submited':'unsubmit';
            // $rollback_btn = ($trans_kasir==true)?'<li><a href="#" onclick="rollback('.$row_list->no_registrasi.','.$row_list->no_kunjungan.')">Rollback</a></li>':'';
            $rollback_btn = '<li><a href="#" onclick="rollback('.$row_list->no_registrasi.','.$row_list->no_kunjungan.','."'".$flag_rollback."'".')">Rollback</a></li>';
            
            $row[] = '<div class="center">
                        <label class="pos-rel">
                            <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->no_kunjungan.'"/>
                            <span class="lbl"></span>
                        </label>
                      </div>';
            $row[] = '<div class="center"><div class="btn-group">
                        <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">
                            <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-inverse">
                            <li><a href="#">Cetak Tracer</a></li>
                            <li><a href="#" onclick="show_modal('."'registration/reg_pasien/view_detail_resume_medis/".$row_list->no_registrasi."'".', '."'RESUME MEDIS'".')">Selengkapnya</a></li>
                            '.$cetak.'
                            '.$rollback_btn.'
                        </ul>
                    </div></div>';
            $row[] = '<div class="center">'.$row_list->no_kunjungan.'</div>';
            $row[] = $row_list->no_mr.' - '.$row_list->nama_pasien;
            $row[] = $row_list->asal_bagian;
            $row[] = $row_list->tujuan_bagian;
            $row[] = $row_list->dokter;
            $row[] = $this->tanggal->formatDateTime($row_list->tgl_masuk);
            $row[] = $this->tanggal->formatDateTime($row_list->tgl_keluar);

            if($trans_kasir==false){
                $status_periksa = '<label class="label label-primary"><i class="fa fa-money"></i> Lunas </label>';
            }else{
                if($row_list->status_batal==1){
                    $status_periksa = '<label class="label label-danger"><i class="fa fa-times-circle"></i> Batal Berobat</label>';
                }else{
                    $status_periksa = ($row_list->tgl_keluar==NULL)?'<label class="label label-warning"><i class="fa fa-info-circle"></i> Belum diperiksa</label>':'<label class="label label-success"><i class="fa fa-check-circle"></i> Selesai</label>';
                }
            }

            $row[] = '<div class="center">'.$status_periksa.'</div>';

            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Riwayat_kunjungan_poli->count_all(),
                        "recordsFiltered" => $this->Riwayat_kunjungan_poli->count_filtered(),
                        "data" => $data,
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
