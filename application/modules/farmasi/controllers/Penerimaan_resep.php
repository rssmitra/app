<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Penerimaan_resep extends MX_Controller {

    /*function constructor*/
    function __construct() 
    {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'farmasi/Penerimaan_resep');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('Penerimaan_resep_model', 'Penerimaan_resep');
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
            'title' => $this->title ,
            'breadcrumbs' => $this->breadcrumbs->show(),
        );
        /*load view index*/
        $this->load->view('Penerimaan_resep/index', $data);
    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->Penerimaan_resep->get_datatables();
        if(isset($_GET['search']) AND $_GET['search']==TRUE){
            $this->find_data(); exit;
        }
        $data = array();
        $no = $_POST['start'];
        $atts = array('class' => 'btn btn-xs btn-warning','width'       => 900,'height'      => 500,'scrollbars'  => 'no','status'      => 'no','resizable'   => 'no','screenx'     => 1000,'screeny'     => 80,'window_name' => '_blank'
            );
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center"><div class="btn-group">
            <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">
            <span class="ace-icon fa fa-caret-down icon-on-right"></span>
            </button>
            <ul class="dropdown-menu dropdown-inverse">
            <li><a href="#" onclick="cetak_surat_kontrol('.$row_list->kode_pesan_resep.')">Kembalikan</a></li>
            </ul>
            </div></div>';
            $row[] = '<div class="center">'.$no.'</div>';
            $row[] = $row_list->kode_trans_far;
            $row[] = $this->tanggal->formatDateTime($row_list->tgl_trans);
            $row[] = '<div class="center">'.$row_list->no_mr.'</div>';
            $row[] = strtoupper($row_list->nama_pasien);
            $row[] = $row_list->dokter_pengirim;
            $row[] = $row_list->nama_pelayanan;
            if( $row_list->status_terima == 0 ){
                $row[] = '<div class="center">
                        <a href="#" onclick="terima_resep('.$row_list->kode_trans_far.')" class="btn btn-xs btn-primary" title="Terima Resep">
                          Terima Resep
                        </a>
                      </div>';
            }else{
                $row[] = '<div class="center"><i class="fa fa-check-circle bigger-150 green"></i></div>';
            }
            
            
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Penerimaan_resep->count_all(),
                        "recordsFiltered" => $this->Penerimaan_resep->count_filtered(),
                        "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function terima_resep()
    {
        $id=$this->input->post('ID')?$this->input->post('ID',TRUE):null;
        if($id!=null){
            if(  $this->db->update('fr_tc_far', array('status_terima' => 1) , array('kode_trans_far' => $id ) ) ){
                echo json_encode(array('status' => 200, 'message' => 'Proses Penerimaan Resep Berhasil Dilakukan'));
            }else{
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Penerimaan Resep Gagal Dilakukan'));
            }
        }else{
            echo json_encode(array('status' => 301, 'message' => 'Tidak ada item yang dipilih'));
        }
        
    }

    public function rollback()
    {
        $id=$this->input->post('ID')?$this->input->post('ID',TRUE):null;
        if($id!=null){
            if(  $this->db->update('fr_tc_far', array('status_terima' => 1) , array('kode_trans_far' => $id ) ) ){
                echo json_encode(array('status' => 200, 'message' => 'Proses Penerimaan Resep Berhasil Dilakukan'));
            }else{
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Penerimaan Resep Gagal Dilakukan'));
            }
        }else{
            echo json_encode(array('status' => 301, 'message' => 'Tidak ada item yang dipilih'));
        }
        
    }

}


/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
