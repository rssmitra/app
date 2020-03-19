<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Riwayat_pasien_bedah extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'registration/Riwayat_pasien_bedah');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('Riwayat_pasien_bedah_model', 'Riwayat_pasien_bedah');
        /*enable profiler*/
        $this->output->enable_profiler(false);
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'Title';

    }

    public function index() { 
        /*define variable data*/
        $kode_bagian = (isset($_GET['kode_bagian']))?$_GET['kode_bagian']:0;
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show(),
            'kode_bagian' => $kode_bagian,
        );
        /*load view index*/
        $this->load->view('Riwayat_pasien_bedah/index', $data);
    }
    
    public function show_detail( $id )
    {
        $data = $this->Riwayat_pasien_bedah->get_by_id( $id );
        $html = $this->master->show_detail_row_table( $data );      

        echo json_encode( array('html' => $html) );
    }

    public function get_data()
    {
        /*akan di filter berdasarkan pasien pada klinik masing2*/
        /*get data from model*/
        $list = $this->Riwayat_pasien_bedah->get_datatables();
        //echo '<pre>'; print_r($list);die;
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center">
                        <label class="pos-rel">
                            <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->no_kunjungan.'"/>
                            <span class="lbl"></span>
                        </label>
                    </div>';
            $row[] = '';
            $row[] = $row_list->id_pesan_bedah;
            $row[] = '<div class="center"><div class="btn-group">
                        <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">
                            <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-inverse">
                            <li><a href="#" onclick="rollback('.$row_list->id_pesan_bedah.')">Rollback</a></li>
                            <li><a href="#">Selengkapnya</a></li>
                        </ul>
                    </div></div>';

            /*$row[] = '<div class="center"><a href="#" onclick="getMenu('."'pelayanan/Pl_pelayanan_bedah/form/".$row_list->id_pesan_bedah."/".$row_list->no_kunjungan."?riwayat=1'".')">'.$row_list->no_kunjungan.'</a></div>';*/
            $row[] = $row_list->no_mr;
            $row[] = strtoupper($row_list->nama_pasien).' ('.$row_list->jen_kelamin.')';
            //$row[] = $row_list->nama_kelompok.' '.$row_list->nama_perusahaan;
            $row[] = ($row_list->nama_perusahaan=='')?$row_list->nama_kelompok:$row_list->nama_perusahaan;
            $row[] = $this->tanggal->formatDate($row_list->tgl_jadwal).' '.$row_list->jam_bedah;
            $row[] = $row_list->nama_pegawai;
            $row[] = $row_list->nama_tarif;
            $row[] = '<div class="center">'.$row_list->no_kamar.'</div>';

            if($row_list->tgl_keluar==NULL){
                $status_periksa = '<label class="label label-warning"><i class="fa fa-info-circle"></i> Belum dilayani</label>';
            }else{
                $status_periksa = '<label class="label label-success"><i class="fa fa-times-circle"></i> Selesai</label>';
            }

            $row[] = '<div class="center">'.$status_periksa.'</div>';
           
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Riwayat_pasien_bedah->count_all(),
                        "recordsFiltered" => $this->Riwayat_pasien_bedah->count_filtered(),
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
