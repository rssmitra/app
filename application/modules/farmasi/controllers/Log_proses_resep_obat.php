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
        $this->load->model('Turn_around_time_model', 'Turn_around_time');
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
        $max_layan = (isset($_GET['max_layan']) && is_numeric($_GET['max_layan'])) ? (int)$_GET['max_layan'] : 45;
        
        foreach ($list as $row_list) {
            $no++;
            // $flag = $this->regex->_genRegex($row_list->no_resep, 'RQXAZ');
            $flag = preg_replace('/[^A-Za-z\?!]/', '', $row_list->no_resep);

            $row = array();
            $row[] = '';
            $row[] = $row_list->kode_pesan_resep;
            $row[] = '<div class="center">'.$no.'</div>';

            // jika kondisi bagian asal ada maka tampilkan nama bagian asal
            $bagian_asal = '';
            if (!empty($row_list->kode_bagian_asal)) {
                $kode_bagian_asal = substr($row_list->kode_bagian_asal, 0, 2);
                if ($kode_bagian_asal === '02') {
                    $bagian_asal = 'IGD';
                } elseif ($kode_bagian_asal === '01') {
                    $bagian_asal = 'Poliklinik';
                } elseif ($kode_bagian_asal === '03') {
                    $bagian_asal = 'RI';
                } else {
                    // fallback: tampilkan kode asli jika tidak cocok
                    $bagian_asal = $row_list->kode_bagian_asal;
                }
            }

            $row[] = '<div class="center"><b><a style="color: blue" href="#" onclick="getMenu('."'farmasi/Process_entry_resep/preview_entry/".$row_list->kode_trans_far."?flag=".$flag."&status_lunas=1'".')">'.$row_list->kode_trans_far.'</a></b><br>['.$bagian_asal.']</div>';

            // $row[] = '<div class="center">'.$row_list->no_resep.'</div>';
            $row[] = '<div class="center">'.$this->tanggal->formatDateTimeFormDmy($row_list->tgl_trans).'</div>';
            $row[] = $row_list->no_mr.' - '.strtoupper($row_list->nama_pasien);
            $jenis_resep = ($row_list->jenis_resep == 'racikan')?'<span style="font-weight: bold; color: red">Racikan</span>':'<span style="font-weight: bold; color: blue">Non Racikan</span>';
            $row[] = '<div class="center">'.$jenis_resep.'</div>';
            $row[] = ($row_list->log_time_1 == null) ? '<div class="center"><a href="#" class="btn btn-sm btn-primary" onclick="exc_process('.$row_list->kode_trans_far.', 1, '."'".$row_list->jenis_resep."'".')"> <i class="fa fa-check-circle"></i> Selesai </a></div>' : '<div class="center">'.$this->tanggal->formatDateTimeFormDmy($row_list->log_time_1).'</div>';
            $row[] = ($row_list->log_time_2 == null) ? '<div class="center"><a href="#" class="btn btn-sm btn-primary" onclick="exc_process('.$row_list->kode_trans_far.', 2, '."'".$row_list->jenis_resep."'".')"> <i class="fa fa-check-circle"></i> Selesai </a></div>' : '<div class="center">'.$this->tanggal->formatDateTimeFormDmy($row_list->log_time_2).'</div>';
            if($row_list->jenis_resep == 'racikan') {
                $row[] = ($row_list->log_time_3 == null) ? '<div class="center"><a href="#" class="btn btn-sm btn-success" onclick="exc_process('.$row_list->kode_trans_far.', 3, '."'".$row_list->jenis_resep."'".')"> <i class="fa fa-play"></i> Mulai Racik </a></div>' : '<div class="center">'.$this->tanggal->formatDateTimeFormDmy($row_list->log_time_3).'</div>';
            }else{
                $row[] = '<div class="center">-</div>';
            }
            if($row_list->log_time_4 == null){
                $row[] = ($row_list->log_time_4 == null) ? '<div class="center"><a href="#" class="btn btn-sm btn-success" onclick="exc_process('.$row_list->kode_trans_far.', 4, '."'".$row_list->jenis_resep."'".')"> <i class="fa fa-play"></i> Mulai eTiket </a></div>' : '<div class="center">'.$this->tanggal->formatDateTimeFormDmy($row_list->log_time_4).'</div>';
            }else{
                $row[] = ($row_list->log_time_5 == null) ? '<div class="center"><a href="#" class="btn btn-sm btn-primary" onclick="exc_process('.$row_list->kode_trans_far.', 5, '."'".$row_list->jenis_resep."'".')"> <i class="fa fa-pause"></i> Selesai eTiket </a></div>' : '<div class="center">'.$this->tanggal->formatDateTimeFormDmy($row_list->log_time_4).'</div>';
            }

            $row[] = ($row_list->log_time_5 == null) ? '<div class="center"><a href="#" class="btn btn-sm btn-primary" onclick="exc_process('.$row_list->kode_trans_far.', 5, '."'".$row_list->jenis_resep."'".')"> <i class="fa fa-check-circle"></i> Siap Diambil </a></div>' : '<div class="center">'.$this->tanggal->formatDateTimeFormDmy($row_list->log_time_5).'</div>';

            if($row_list->status_ambil_obat == 1){
                $title_status = 'Sudah diambil';
                $class_btn = 'btn-success';

            }else if($row_list->status_ambil_obat == 2){
                $title_status = 'Ditinggal';
                $class_btn = 'btn-danger';
            }else{
                $title_status = 'Belum diambil';
                $class_btn = 'btn-warning';
            }

            $btn_status = '<div class="btn-group">
                                <button data-toggle="dropdown" class="btn btn-xs '.$class_btn.' dropdown-toggle">
                                    '.$title_status.'
                                    <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-warning">
                                    <li><a href="#" onclick="exc_process('.$row_list->kode_trans_far.', 6, '."'".$row_list->jenis_resep."'".', 2)">Ditinggal</a></li>
                                    <li><a href="#" onclick="exc_process('.$row_list->kode_trans_far.', 6, '."'".$row_list->jenis_resep."'".', 1)">Sudah diambil</a></li>
                                </ul>
                            </div>';

            $row[] = '<div class="center">'.$btn_status.'</div>';
            // selisih log_time_1 sampai dengan log_time_6 tanpa library tanggal, gunakan fungsi PHP
            if ($row_list->log_time_5 != null && strtotime($row_list->log_time_1) !== false && strtotime($row_list->log_time_5) !== false) {
                
                $tat = $this->tanggal->diffHourMinuteReturnSecond($row_list->log_time_1, $row_list->log_time_5);
                $tat = $this->tanggal->convertHourMinutesSecond($tat, $max_layan);
                $color = ($tat < $max_layan) ? 'green' : 'red';
                $row[] = '<div class="center"><span style="color:'.$color.'; font-weight: bold; font-size: 14px">'.$tat.'</span></div>';
            } else {
                $row[] = '';
            }

            $data[] = $row;
        }

        $querytat = $this->Turn_around_time->get_datatables();
        $arr_seconds = [];
        foreach ($querytat as $row_tat) {
            $arr_seconds[] = $this->tanggal->diffHourMinuteReturnSecond($row_tat->log_time_1, $row_tat->log_time_5);
        }
        $avgTat = (count($arr_seconds) > 0) ? array_sum($arr_seconds)/count($arr_seconds) : 0;
        $output = array(
                        "draw" => $_POST['draw'],
                        "data" => $data,
                        "count_data" => count($list),
                        "tat" => $this->tanggal->convertHourMinutesSecond($avgTat, $max_layan),
                        "count_selesai" => (count($arr_seconds) > 0) ? count($arr_seconds) : '00:00:00',
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
        $this->form_validation->set_rules('jenis', 'Jenis Resep', 'trim');

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
            $jenis_resep = (empty($_POST['jenis'])) ? 'non_racikan' : $_POST['jenis'] ;

            if(!empty($jenis_resep)){
                if($jenis_resep == 'non_racikan' && $_POST['proses'] == 4){
                    $proses = $_POST['proses'] - 2;
                }else{
                    $proses = ($_POST['proses'] == 1) ? 1 : $_POST['proses'] - 1;
                }
            }else{
                if($jenis_resep == 'non_racikan' && $_POST['proses'] == 4){
                    $proses = $_POST['proses'] - 2;
                }else{
                    $proses = $_POST['proses'] - 1;
                }
            }
            
            // echo $proses;die;
            if($_POST['proses'] > 1) {
                $cek_proses_sebelumnya = $this->db->where('log_time_'.$proses.' is not null')->get_where('fr_tc_far', ['kode_trans_far' => $_POST['ID']])->row();
                // echo $this->db->last_query();die;
                if (!$cek_proses_sebelumnya) {
                    echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Sebelumnya Belum Dilakukan'));
                    return;
                }
            }

            if($_POST['proses'] == 6){
                if($_POST['status_ambil'] == 1){
                    $dataexc['status_ambil_obat'] = 1; // sudah diambil
                }elseif($_POST['status_ambil'] == 2){
                    $dataexc['status_ambil_obat'] = 2; // ditinggal
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
