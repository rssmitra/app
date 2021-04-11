<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ao_setoran extends MX_Controller {

    /*function constructor*/
    function __construct() 
    {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'farmasi/Ao_setoran');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('Ao_setoran_model', 'Ao_setoran');
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
            'kurir' => $this->db->select('pickup_by as kurir')->group_by('pickup_by')->get_where('fr_pickup_obat')->result(),
        );
        /*load view index*/
        $this->load->view('Ao_setoran/index', $data);
    }

    public function get_data()
    {
        /*get data from model*/
        $list = ($_GET) ? $this->Ao_setoran->get_datatables() : [];

        $data = array();
        $no = $_POST['start'];
        $arr_total = [];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $status = ($row_list->bagi_hasil == NULL) ? '<label class="pos-rel">
                            <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->pickup_id.'"/>
                                <span class="lbl"></span>
                        </label>' : '<i class="fa fa-check-circle bigger-120 green"></i>';
            $arr_total[] = $row_list->cost;
            $row[] = '<div class="center">'.$status.'</div>';
            $row[] = '<div class="center">'.$no.'</div>';
            $row[] = '<div class="center">'.$row_list->kode_trans_far.'</div>';
            $row[] = '<b>'.$row_list->no_mr.' - '.strtoupper($row_list->nama_pasien).'</b>';
            $row[] = $this->tanggal->formatDateTimeFormDmy($row_list->pickup_time);
            $row[] = $row_list->received_by;
            $row[] = $this->tanggal->formatDateTimeFormDmy($row_list->received_time);
            // $row[] = $row_list->distance;
            $row[] = '<div class="pull-right">'.number_format($row_list->cost).'</div>';
            
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Ao_setoran->count_all(),
                        "recordsFiltered" => $this->Ao_setoran->count_filtered(),
                        "data" => $data,
                        "total_pickup" => count((array)$arr_total),
                        "total_cost" => array_sum($arr_total),
                        "from_tgl" => isset($_GET['from_tgl']) ? $this->tanggal->formatDatedmY($_GET['from_tgl']) : '-',
                        "to_tgl" => isset($_GET['to_tgl']) ? $this->tanggal->formatDatedmY($_GET['to_tgl']) : '-',
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
        
        $this->form_validation->set_rules('kurir', 'Kurir', 'trim|required');
        $this->form_validation->set_rules('from_tgl', 'Dari tanggal', 'trim|required');
        $this->form_validation->set_rules('to_tgl', 's/d tanggal', 'trim|required');
        $this->form_validation->set_rules('pickup_id', 'Selected Item', 'trim|required', array('required' => 'Tidak ada item yang dipilih'));
        
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

            $dataexc = array(
                'periode_dr_tgl' => isset($_POST['from_tgl'])?$this->regex->_genRegex($_POST['from_tgl'], 'RGXQSL'):0,
                'periode_sd_tgl' => isset($_POST['to_tgl'])?$this->regex->_genRegex($_POST['to_tgl'], 'RGXQSL'):0,
                'total_delivery' => isset($_POST['total_delivery'])?$this->regex->_genRegex($_POST['total_delivery'], 'RGXINT'):0,
                'total_pendapatan' => isset($_POST['total_cost'])?$this->regex->_genRegex($_POST['total_cost'], 'RGXINT'):0,
                'persentase' => 50,
                'nama_kurir' => isset($_POST['kurir'])?$this->regex->_genRegex($_POST['kurir'], 'RGXQSL'):0,
            );
            
            // echo '<pre>';print_r($dataexc);die;

            $dataexc['created_date'] = date('Y-m-d H:i:s');
            $dataexc['created_by'] = $this->session->userdata('user')->fullname;
            $this->db->insert( 'fr_bagi_hasil', $dataexc );
            $newId = $this->db->insert_id();

            // update 
            $arr_explode = explode(",", $_POST['pickup_id']);
            $this->db->where_in('pickup_id', $arr_explode)->update('fr_pickup_obat', array('bagi_hasil' => $newId) );

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

    public function delete()
    {
        $id=$this->input->post('ID')?$this->input->post('ID',TRUE):null;
        $toArray = explode(',',$id);
        if($id!=null){
            if($this->Ao_setoran->delete_by_id($toArray)){
                echo json_encode(array('status' => 200, 'message' => 'Proses Hapus Data Berhasil Dilakukan'));
            }else{
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Hapus Data Gagal Dilakukan'));
            }
        }else{
            echo json_encode(array('status' => 301, 'message' => 'Tidak ada item yang dipilih'));
        }
        
    }

}


/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
