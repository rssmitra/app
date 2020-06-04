<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Proses_resep_prb extends MX_Controller {

    /*function constructor*/
    function __construct() 
    {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'farmasi/Proses_resep_prb');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('Etiket_obat_model', 'Etiket_obat');
        $this->load->model('Entry_resep_racikan_model', 'Entry_resep_racikan');
        $this->load->model('Proses_resep_prb_model', 'Proses_resep_prb');
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
        $this->load->view('Proses_resep_prb/index', $data);
    }

    public function form($id='')
    {
        /*if id is not null then will show form edit*/
        /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Entry Resep '.strtolower($this->title).'', 'Proses_resep_prb/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*get value by id*/
        $data['value'] = $this->Proses_resep_prb->get_by_id($id);
        // detail obat
        $resep_log = $this->Etiket_obat->get_detail_resep_data($id)->result_array();
        foreach($resep_log as $row){
            $racikan = ($row['flag_resep']=='racikan') ? $this->Entry_resep_racikan->get_detail_by_id($row['relation_id']) : [] ;
            $row['racikan'][] = $racikan;
            $getData[] = $row;
        }
        $data['resep'] = $getData;
        
        // echo '<pre>';print_r($data);die;
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Proses_resep_prb/form', $data);
    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->Proses_resep_prb->get_datatables();
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
            $row[] = '<div class="center"><a href="#" onclick="getMenu('."'farmasi/Proses_resep_prb/form/".$row_list->kode_trans_far."'".')">'.$row_list->kode_trans_far.'</a></div>';
            $row[] = '<div class="left">'.$row_list->no_sep.'</div>';
            $row[] = $this->tanggal->formatDateTime($row_list->tgl_trans);
            $row[] = '<div class="center">'.$row_list->no_mr.'</div>';
            $row[] = strtoupper($row_list->nama_pasien);
            $row[] = $row_list->dokter_pengirim;
            $row[] = $row_list->nama_pelayanan;
            $row[] = '<div class="center">
                        <a href="#" onclick="getMenu('."'farmasi/Proses_resep_prb/form/".$row_list->kode_trans_far."'".')" class="btn btn-xs btn-primary" title="etiket">
                          <i class="fa fa-check-square-o"></i> Porses Resep
                        </a>
                      </div>';
            
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Proses_resep_prb->count_all(),
                        "recordsFiltered" => $this->Proses_resep_prb->count_filtered(),
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

        $this->form_validation->set_rules('jumlah_obat', 'Jumlah', 'trim|required');
        $this->form_validation->set_rules('satuan_obat', 'Satuan Obat', 'trim|required');
        $this->form_validation->set_rules('harga_satuan', 'Harga Satuan', 'trim|required');
        $this->form_validation->set_rules('catatan', 'Catatan', 'trim');

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
                'dosis_obat' => isset($_POST['dosis_start'])?$this->regex->_genRegex($_POST['dosis_start'], 'RGXQSL'):0,
                'dosis_per_hari' => isset($_POST['dosis_end'])?$this->regex->_genRegex($_POST['dosis_end'], 'RGXQSL'):0,
                'aturan_pakai' => isset($_POST['satuan_obat'])?$this->regex->_genRegex($_POST['satuan_obat'], 'RGXQSL'):0,
                'catatan_lainnya' => isset($_POST['catatan'])?$this->regex->_genRegex($_POST['catatan'], 'RGXQSL'):0,
                'relation_id' => isset($_POST['kd_tr_resep'])?$this->regex->_genRegex($_POST['kd_tr_resep'], 'RGXINT'):0,
                'satuan_obat' => isset($_POST['satuan_obat'])?$this->regex->_genRegex($_POST['satuan_obat'], 'RGXQSL'):0,
                'anjuran_pakai' => isset($_POST['anjuran_pakai'])?$this->regex->_genRegex($_POST['anjuran_pakai'], 'RGXQSL'):0,
                'jumlah_obat' => isset($_POST['jumlah_obat'])?$this->regex->_genRegex($_POST['jumlah_obat'], 'RGXQSL'):0,
            );
            
            // print_r($_POST);die;
            
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

    public function find_data()
    {   
        $output = array( "data" => http_build_query($_POST) . "\n" );
        echo json_encode($output);
    }

}


/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
