<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ao_receipt extends MX_Controller {

    /*function constructor*/
    function __construct() 
    {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'farmasi/Ao_receipt');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('Ao_receipt_model', 'Ao_receipt');
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
        );
        /*load view index*/
        $this->load->view('Ao_receipt/index', $data);
    }

    public function form($id='')
    {
        /*if id is not null then will show form edit*/
        /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Receipt '.strtolower($this->title).'', 'Ao_receipt/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*get value by id*/
        $data['value'] = $this->Ao_receipt->get_by_id($id);
        // echo '<pre>';print_r($data);die;
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Ao_receipt/form', $data);
    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->Ao_receipt->get_datatables();
        if(isset($_GET['search']) AND $_GET['search']==TRUE){
            $this->find_data(); exit;
        }
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $status = ($row_list->received_time == NULL) ? '<a href="#" class="btn btn-xs btn-primary" onclick="getMenu('."'farmasi/Ao_receipt/form/".$row_list->pickup_id."'".')"><i class="fa fa-check-square-o"></i></a>' : '<i class="fa fa-check-circle bigger-120 green"></i>';
            $row[] = '<div class="center">'.$status.'</div>';
            $row[] = '<div class="center">'.$no.'</div>';
            $row[] = '<div class="center">'.$row_list->kode_trans_far.'</div>';
            $row[] = '<b>'.$row_list->no_mr.' - '.strtoupper($row_list->nama_pasien).'</b>';
            $row[] = $row_list->received_by;
            $row[] = $this->tanggal->formatDateTimeFormDmy($row_list->received_time);
            $row[] = '<div class="pull-right">'.number_format($row_list->cost).'</div>';
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Ao_receipt->count_all(),
                        "recordsFiltered" => $this->Ao_receipt->count_filtered(),
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

    public function process()
    {
        // print_r($_POST);die;
        $this->load->library('form_validation');
        
        $this->form_validation->set_rules('kode', 'Kode Transaksi', 'trim|required');
        $this->form_validation->set_rules('distance', 'Jarak Tempuh', 'trim|required');
        $this->form_validation->set_rules('received_by', 'Penerima', 'trim|required');
        $this->form_validation->set_rules('note', 'Keterangan', 'trim');
        
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

            $dtpickup = array(
                'distance' => isset($_POST['distance'])?$this->regex->_genRegex($_POST['distance'], 'RGXQSL'):0,
                'received_by' => isset($_POST['received_by'])?$this->regex->_genRegex($_POST['received_by'], 'RGXQSL'):0,
                'received_time' => date('Y-m-d H:i:s'),
                'cost' => $_POST['distance'] * 1000,
            );

            $dtpickup['updated_date'] = date('Y-m-d H:i:s');
            $dtpickup['updated_by'] = $this->session->userdata('user')->fullname;

            $this->db->update('fr_pickup_obat', $dtpickup, array('pickup_id' => $_POST['pickup_id']) );


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

    public function get_summary(){
        $list = $this->Ao_receipt->get_summary();
        foreach ($list as $key => $row_list) {
            if( ($row_list->received_time != NULL) ){
                $received[] = $row_list->cost;
            }
            $pickup[] = $row_list;
        }
        $output = array(
                        "pickup" => count((array) $pickup),
                        "received" => count((array) $received),
                        "cost" => array_sum($received),
        );
        echo json_encode($output);
    }

}


/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
