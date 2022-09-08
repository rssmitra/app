<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class History_resep_prb extends MX_Controller {

    /*function constructor*/
    function __construct() 
    {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'farmasi/History_resep_prb');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('Etiket_obat_model', 'Etiket_obat');
        $this->load->model('Entry_resep_racikan_model', 'Entry_resep_racikan');
        $this->load->model('History_resep_prb_model', 'History_resep_prb');
        $this->load->model('Dokumen_klaim_prb_model', 'Dokumen_klaim_prb');
        $this->load->model('Verifikasi_resep_prb_model', 'Verifikasi_resep_prb');
        $this->load->model('Proses_resep_prb_model', 'Proses_resep_prb');
        // load library
        $this->load->library('Print_direct');
        $this->load->library('Print_escpos'); 
        $this->load->library('Stok_barang');
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
        $this->load->view('History_resep_prb/index', $data);
    }

    public function form($id='')
    {
        /*if id is not null then will show form edit*/
        /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Form  '.strtolower($this->title).'', 'History_resep_prb/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*get value by id*/
        $data['value'] = $this->Etiket_obat->get_by_id($id);
        $detail_log = $this->History_resep_prb->get_detail($id);
        $data['resep'] = $detail_log;
        
        // echo '<pre>';print_r($data);die;
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('History_resep_prb/form', $data);
    }

    public function get_detail($id){
        $flag = $_GET['flag'];
        
        $data = array(
            'title' => 'Preview Transaksi' ,
            'breadcrumbs' => $this->breadcrumbs->show(),
            'flag' => $_GET['flag']
        );
        $data['value'] = $this->Etiket_obat->get_by_id($id);
        $detail_log = $this->Dokumen_klaim_prb->get_detail($id);
        $data['resep'] = $detail_log;
        $log_mutasi = $this->Proses_resep_prb->get_log_mutasi($id);
        $data['log_mutasi'] = $log_mutasi;
        // echo '<pre>';print_r($this->db->last_query());die;
        // get dokumen klaim
        $data['dokumen'] = $this->db->get_where('fr_tc_far_dokumen_klaim_prb', array('kode_trans_far' => $id))->result();
        $month = date("M",strtotime($data['value']->tgl_trans));
        $year = date("Y",strtotime($data['value']->tgl_trans));
        $data['path_dok_klaim'] = PATH_DOK_KLAIM_FARMASI.'merge-'.$month.'-'.$year.'/'.$data['value']->no_sep.'.pdf';
        $temp_view = $this->load->view('farmasi/History_resep_prb/detail_table_view', $data, true);
        echo json_encode( array('html' => $temp_view) );
    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->History_resep_prb->get_datatables();
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
            $row[] = '<div class="center">'.$no.'</div>';
            $row[] = '';
            $row[] = $row_list->kode_trans_far;

            // $row[] = '<div class="center"><a href="#" onclick="getMenu('."'farmasi/History_resep_prb/form/".$row_list->kode_trans_far."?flag=RJ'".')">'.$row_list->kode_trans_far.'</a></div>';
            $row[] = '<div class="center">'.$row_list->kode_trans_far.'</div>';
            $row[] = '<div class="left">'.$row_list->no_sep.'</div>';
            $row[] = $this->tanggal->formatDateTime($row_list->tgl_trans);
            $row[] = '<div class="center">'.$row_list->no_mr.'</div>';
            $row[] = strtoupper($row_list->nama_pasien);
            $row[] = $row_list->dokter_pengirim;
            $row[] = $row_list->nama_pelayanan;
            $verifikasi = ($row_list->verifikasi_prb==NULL)?'<i class="fa fa-warning orange bigger-150"></i>':'<i class="fa fa-check-circle green bigger-150"></i>';
            $mutasi = ($row_list->proses_mutasi_prb==NULL)?'<i class="fa fa-warning orange bigger-150"></i>':'<i class="fa fa-check-circle green bigger-150"></i>';
            $row[] = '<div class="center">'.$verifikasi.'</div>';             
            $row[] = '<div class="center">'.$mutasi.'</div>';             
            $row[] = '<div class="center">'.$mutasi.'</div>';             
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->History_resep_prb->count_all(),
                        "recordsFiltered" => $this->History_resep_prb->count_filtered(),
                        "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function process()
    {
        // print_r($_POST);die;
        $this->load->library('form_validation');
        // form validation

        $this->form_validation->set_rules('kode_trans_far', 'Kode Transaksi', 'trim|required');

        // set message error
        $this->form_validation->set_message('required', "Silahkan isi field \"%s\"");        

        if ($this->form_validation->run() == FALSE)
        {
            $this->form_validation->set_error_delimiters('<div style="color:white"><i>', '</i></div>');
            //die(validation_errors());
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            /*execution*/
            $this->db->trans_begin();
            $kode_bagian = '060101';
            $arr_log_jml_mutasi = [];
            foreach ($_POST['id_fr_tc_far_detail_log_prb'] as $key => $value) {
                $log_jml_mutasi = $_POST['jumlah_'.$value.''] + $_POST['log_jml_mutasi_'.$value.''];
                $sisa_mutasi = $_POST['jumlah_tebus_'.$value.''] - $log_jml_mutasi;
                if ( $sisa_mutasi > 0 ) {
                    $arr_log_jml_mutasi[] = 1;
                }
                $data_update = array(
                    'log_jml_mutasi' => $log_jml_mutasi,
                    'log_tgl_mutasi' => date('Y-m-d H:i:s'),
                    'log_user_mutasi' => $this->session->userdata('user')->fullname,
                );
                $this->db->update('fr_tc_far_detail_log_prb', $data_update, array('id_fr_tc_far_detail_log_prb' => $value) );
                // commit transaction
                $this->db->trans_commit();
                // kurangi stok depo, update kartu stok dan rekap stok
                $this->stok_barang->stock_process($_POST['kode_brg_'.$value.''], $_POST['jumlah_'.$value.''], $kode_bagian, 14, " Resep PRB No Transaksi : ".$_POST['kode_trans_far']."", 'reduce');

            }

            if (count($arr_log_jml_mutasi) == 0) {
                $this->db->update('fr_tc_far', array('proses_mutasi_prb' => 1), array('kode_trans_far' => $_POST['kode_trans_far']) );
            }

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan'));
            }
        
        }

    }

    public function find_data()
    {   
        $output = array( "data" => http_build_query($_POST) . "\n" );
        echo json_encode($output);
    }

    function preview_mutasi($kode_trans_far){

        /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Preview Mutasi', 'History_resep_prb/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$kode_trans_far);

        $data = array(
            'title' => 'Preview Transaksi' ,
            'breadcrumbs' => $this->breadcrumbs->show(),
            'flag' => $_GET['flag']
        );
        $data['value'] = $this->Etiket_obat->get_by_id($kode_trans_far);
        $detail_log = $this->Verifikasi_resep_prb->get_detail($kode_trans_far);
        $data['resep'] = $detail_log;

        // echo '<pre>'; print_r($data);die;
        $this->load->view('farmasi/History_resep_prb/preview_mutasi', $data);

    }

}


/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
