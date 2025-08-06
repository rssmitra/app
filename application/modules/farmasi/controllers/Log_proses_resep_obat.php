<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Log_proses_resep_obat extends MX_Controller {

    /*function constructor*/
    function __construct() 
    {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'farmasi/Log_proses_resep_obat');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('Log_proses_resep_obat_model', 'Log_proses_resep_obat');
        // load library
        $this->load->library('Print_direct');
        $this->load->library('Print_escpos'); 
        $this->load->library('stok_barang');
        $this->load->library('tanggal'); // Ensure tanggal library is loaded
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
        $this->load->view('Log_proses_resep_obat/index', $data);
    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->Log_proses_resep_obat->get_datatables();
        // if(isset($_GET['search']) AND $_GET['search']==TRUE){
        //     $this->find_data(); exit;
        // }
        $data = array();
        $no = $_POST['start'];
        $atts = array('class' => 'btn btn-xs btn-warning','width'       => 900,'height'      => 500,'scrollbars'  => 'no','status'      => 'no','resizable'   => 'no','screenx'     => 1000,'screeny'     => 80,'window_name' => '_blank'
            );
        
        foreach ($list as $row_list) {
            $no++;
            // $flag = $this->regex->_genRegex($row_list->no_resep, 'RQXAZ');
            $flag = preg_replace('/[^A-Za-z\?!]/', '', $row_list->no_resep);

            $row = array();
            $row[] = '<div class="center">'.$no.'</div>';
            $row[] = '<div class="center"><b><a style="color: blue" href="#" onclick="getMenu('."'farmasi/Process_entry_resep/preview_entry/".$row_list->kode_trans_far."?flag=".$flag."&status_lunas=1'".')">'.$row_list->kode_trans_far.'</a></b></div>';

            // $row[] = '<div class="center">'.$row_list->no_resep.'</div>';
            $row[] = $this->tanggal->formatDateTimeFormDmy($row_list->tgl_trans);
            $row[] = $row_list->no_mr.' - '.strtoupper($row_list->nama_pasien);
            $jenis_resep = ($row_list->jenis_resep == 'racikan')?'<span style="font-weight: bold; color: red">Racikan</span>':'<span style="font-weight: bold; color: blue">Non Racikan</span>';
            $row[] = '<div class="center">'.$jenis_resep.'</div>';
            $row[] = ($row_list->log_time_1 == null) ? '<div class="center"><a href="#" class="btn btn-sm btn-primary" onclick="exc_process('.$row_list->kode_trans_far.', 1, '."'".$row_list->jenis_resep."'".')"> <i class="fa fa-check-circle"></i> Selesai </a></div>' : '<div class="center">'.$this->tanggal->formatDateTimeFormDmy($row_list->log_time_1).'</div>';
            $row[] = ($row_list->log_time_2 == null) ? '<div class="center"><a href="#" class="btn btn-sm btn-primary" onclick="exc_process('.$row_list->kode_trans_far.', 2, '."'".$row_list->jenis_resep."'".')"> <i class="fa fa-check-circle"></i> Selesai </a></div>' : '<div class="center">'.$this->tanggal->formatDateTimeFormDmy($row_list->log_time_2).'</div>';
            if($row_list->jenis_resep == 'racikan') {
                $row[] = ($row_list->log_time_3 == null) ? '<div class="center"><a href="#" class="btn btn-sm btn-primary" onclick="exc_process('.$row_list->kode_trans_far.', 3, '."'".$row_list->jenis_resep."'".')"> <i class="fa fa-check-circle"></i> Selesai </a></div>' : '<div class="center">'.$this->tanggal->formatDateTimeFormDmy($row_list->log_time_3).'</div>';
            }else{
                $row[] = '<div class="center">-</div>';
            }
            $row[] = ($row_list->log_time_4 == null) ? '<div class="center"><a href="#" class="btn btn-sm btn-primary" onclick="exc_process('.$row_list->kode_trans_far.', 4, '."'".$row_list->jenis_resep."'".')"> <i class="fa fa-check-circle"></i> Selesai </a></div>' : '<div class="center">'.$this->tanggal->formatDateTimeFormDmy($row_list->log_time_4).'</div>';
            $row[] = ($row_list->log_time_5 == null) ? '<div class="center"><a href="#" class="btn btn-sm btn-primary" onclick="exc_process('.$row_list->kode_trans_far.', 5, '."'".$row_list->jenis_resep."'".')"> <i class="fa fa-check-circle"></i> Selesai </a></div>' : '<div class="center">'.$this->tanggal->formatDateTimeFormDmy($row_list->log_time_5).'</div>';
            $row[] = ($row_list->log_time_6 == null) ? '<div class="center"><a href="#" class="btn btn-sm btn-primary" onclick="exc_process('.$row_list->kode_trans_far.', 6, '."'".$row_list->jenis_resep."'".')"> <i class="fa fa-check-circle"></i> Selesai </a></div>' : '<div class="center">'.$this->tanggal->formatDateTimeFormDmy($row_list->log_time_6).'</div>';
            // selisih log_time_1 sampai dengan log_time_6 tanpa library tanggal, gunakan fungsi PHP
            if ($row_list->log_time_6 != null && strtotime($row_list->log_time_1) !== false && strtotime($row_list->log_time_6) !== false) {
                $start = new DateTime($row_list->log_time_1);
                $end = new DateTime($row_list->log_time_6);
                $diff = $start->diff($end);
                $hours = $diff->h + ($diff->days * 24);
                $minutes = $diff->i;
                $row[] = '<div class="center">'.sprintf('%02d:%02d', $hours, $minutes).'</div>';
            } else {
                $row[] = '';
            }

            
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        // "recordsTotal" => $this->Log_proses_resep_obat->count_all(),
                        // "recordsFiltered" => $this->Log_proses_resep_obat->count_filtered(),
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

        $this->form_validation->set_rules('ID', 'Kode Transaksi', 'trim|required');
        $this->form_validation->set_rules('proses', 'Proses', 'trim|required');
        $this->form_validation->set_rules('jenis', 'Jenis Resep', 'trim|required');

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
            $dataexc = [];
            // cek proses
            
            if($_POST['jenis'] == 'non_racikan' && $_POST['proses'] == 4){
                $proses = $_POST['proses'] - 2;
            }else{
                $proses = ($_POST['proses'] == 1) ? 1 : $_POST['proses'] - 1;
            }
            
            if($_POST['proses'] > 1) {
                $cek_proses_sebelumnya = $this->db->where('log_time_'.$proses.' is not null')->get_where('fr_tc_far', ['kode_trans_far' => $_POST['ID']])->row();
                // echo $this->db->last_query();die;
                if (!$cek_proses_sebelumnya) {
                    echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Sebelumnya Belum Dilakukan'));
                    return;
                }
            }

            $dataexc['log_time_'.$_POST['proses'].''] = date('Y-m-d H:i:s');
            $this->db->where('kode_trans_far', $_POST['ID'])->update('fr_tc_far', $dataexc);

            // echo $this->db->last_query();
                        
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

}


/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
