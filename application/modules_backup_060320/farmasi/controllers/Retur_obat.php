<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Retur_obat extends MX_Controller {

    /*function constructor*/
    function __construct() 
    {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'farmasi/Retur_obat');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('Retur_obat_model', 'Retur_obat');
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
        $this->load->view('Retur_obat/index', $data);
    }

    public function form($id='')
    {
        /*if id is not null then will show form edit*/
        /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Entry Resep '.strtolower($this->title).'', 'Retur_obat/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*get value by id*/
        $data['value'] = $this->Retur_obat->get_by_id($id);
        $data['detail_obat'] = $this->Retur_obat->get_detail_resep_data($id)->result();
        // echo '<pre>';print_r($data);die;
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Retur_obat/form', $data);
    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->Retur_obat->get_datatables();
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
            $row[] = '<div class="center"><a href="#" onclick="getMenu('."'farmasi/Retur_obat/form/".$row_list->kode_trans_far."'".')">'.$row_list->kode_trans_far.'</a></div>';
            $row[] = $this->tanggal->formatDateTime($row_list->tgl_trans);
            $row[] = '<div class="center">'.$row_list->no_mr.'</div>';
            $row[] = strtoupper($row_list->nama_pasien);
            $row[] = $row_list->dokter_pengirim;
            $row[] = $row_list->nama_pelayanan;
            $row[] = '<div class="center">
                        <a href="#" onclick="getMenu('."'farmasi/Retur_obat/form/".$row_list->kode_trans_far."'".')" class="btn btn-xs btn-primary" title="etiket">
                          Retur Obat
                        </a>
                      </div>';
            
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Retur_obat->count_all(),
                        "recordsFiltered" => $this->Retur_obat->count_filtered(),
                        "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function process()
    {
        print_r($_POST);die;
        $this->load->library('form_validation');
        // form validation

        $this->form_validation->set_rules('retur_obat', 'Jumlah Retur', 'trim|required');

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
            
            // check existing data
            $dt_existing = $this->db->get_where('fr_tc_far_detail_log', array('relation_id' => $_POST['kd_tr_resep']) );
           

            $data_farmasi = array(
                'jumlah_retur' => isset($_POST['jumlah_retur'])?$this->regex->_genRegex($_POST['jumlah_retur'], 'RGXINT'):0,
            );
            
            if( $dt_existing->num_rows() > 0 ){
                /*update existing*/
                $data_farmasi['updated_date'] = date('Y-m-d H:i:s');
                $data_farmasi['updated_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                $this->db->update('fr_tc_far_detail_log', $data_farmasi, array('relation_id' => $_POST['kd_tr_resep'], 'kode_trans_far' => $_POST['kode_trans_far']) );
                /*save log*/
                $this->logs->save('fr_tc_far_detail_log', $_POST['kd_tr_resep'], 'update record on entry resep module', json_encode($data_farmasi),'relation_id');
            
            }else{
                $dt_existing_obat = $this->db->get_where('fr_hisbebasluar_v', array('kd_tr_resep' => $_POST['kd_tr_resep']) )->row();
                // print_r($dt_existing_obat);die;
                /*sub total*/
                $sub_total = ceil($dt_existing_obat->jumlah_pesan * $dt_existing_obat->harga_jual);
                /*total biaya*/
                $total_biaya = ($sub_total + $dt_existing_obat->jumlah_pesan);
                
                $data_farmasi['relation_id'] = $dt_existing_obat->kd_tr_resep;
                $data_farmasi['kode_trans_far'] = $dt_existing_obat->kode_trans_far;
                $data_farmasi['kode_pesan_resep'] = $dt_existing_obat->kode_pesan_resep;
                $data_farmasi['tgl_input'] = ($dt_existing_obat->tgl_input)?$dt_existing_obat->tgl_input:$dt_existing_obat->tgl_trans;
                $data_farmasi['kode_brg'] = $dt_existing_obat->kode_brg;
                $data_farmasi['nama_brg'] = $dt_existing_obat->nama_brg;
                $data_farmasi['satuan_kecil'] = $dt_existing_obat->satuan_kecil;
                $data_farmasi['jumlah_pesan'] = $dt_existing_obat->jumlah_pesan;
                $data_farmasi['jumlah_tebus'] = $dt_existing_obat->jumlah_tebus;
                $data_farmasi['harga_jual_satuan'] = $dt_existing_obat->harga_jual;
                $data_farmasi['sub_total'] = $sub_total;
                $data_farmasi['total'] = $total_biaya;
                $data_farmasi['jasa_r'] = $dt_existing_obat->harga_r;
                $data_farmasi['total'] = $dt_existing_obat->harga_beli;
                $data_farmasi['urgensi'] = 'biasa';
                $data_farmasi['flag_resep'] = ( $dt_existing_obat->id_tc_far_racikan == 0 )?'biasa':'racikan';

                $data_farmasi['created_date'] = date('Y-m-d H:i:s');
                $data_farmasi['created_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                // print_r($data_farmasi);die;

                $this->db->insert( 'fr_tc_far_detail_log', $data_farmasi );
                /*save log*/
                $this->logs->save('fr_tc_far_detail_log', $_POST['kd_tr_resep'], 'insert new record on entry resep module', json_encode($data_farmasi),'relation_id');

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

    public function process_copy_resep()
    {
        
        $this->load->library('form_validation');
        // form validation

        $this->form_validation->set_rules('content', 'Tulis Resep', 'trim|required');
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
            
             /*update existing*/
             $data_farmasi['copy_resep_text'] = $_POST['content'];
             $data_farmasi['updated_date'] = date('Y-m-d H:i:s');
             $data_farmasi['updated_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
             $this->db->update('fr_tc_far', $data_farmasi, array('kode_trans_far' => $_POST['kode_trans_far']) );
             /*save log*/
             $this->logs->save('fr_tc_far', $_POST['kode_trans_far'], 'update record on entry resep module', json_encode($data_farmasi),'kode_trans_far');

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'kode_trans_far' => $_POST['kode_trans_far']));
            }
        
        }

    }

    public function find_data()
    {   
        $output = array( "data" => http_build_query($_POST) . "\n" );
        echo json_encode($output);
    }

    public function get_kode_eticket(){
        /*string to array*/
        // $arr_id = explode(',', $_POST['ID']);
        $url_qry = http_build_query($_POST['ID']);
        echo json_encode(array('params' => $url_qry));
    }

    public function preview_etiket(){
        
        // get etiket data from query string
        $resep_log = $this->Retur_obat->get_etiket_data();
        // echo '<pre>';print_r($resep_log->result());die;
        $data = array();
        $data['result'] = $resep_log->result();
        $this->load->view('farmasi/Retur_obat/preview_etiket', $data);

    }

    public function preview_copy_resep($kode_trans_far){
        
        // get etiket data from query string
        $resep_log = $this->db->join('mt_master_pasien','mt_master_pasien.no_mr=fr_tc_far.no_mr','left')->get_where('fr_tc_far', array('kode_trans_far' => $kode_trans_far) )->row();
        // echo '<pre>';print_r($resep_log);die;
        $data = array();
        $data['result'] = $resep_log;
        $this->load->view('farmasi/Retur_obat/preview_copy_resep', $data);

    }

    public function print_tracer_obat($kode_trans_far)
    {   
        $resep_log = $this->Retur_obat->get_detail_resep_data($kode_trans_far);
        // echo '<pre>';print_r($resep_log->result());die;
        $this->print_escpos->print_resep_gudang($resep_log->result());
        $data = array();
        $data['result'] = $resep_log->result();
        $this->load->view('farmasi/Retur_obat/preview_tracer_obat', $data);
    }


}


/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
