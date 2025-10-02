
<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Turn_around_time extends MX_Controller {
    
    /*function constructor*/
    function __construct() 
    {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'farmasi/Turn_around_time');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
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
        $this->load->view('Turn_around_time/index', $data);
    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->Turn_around_time->get_datatables();
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

            $row[] = '<div class="center">('.$this->tanggal->formatDateTimeToTime($row_list->log_time_1).' - '.$this->tanggal->formatDateTimeToTime($row_list->log_time_2).')<br>'.$this->diffHourMinute($row_list->log_time_1, $row_list->log_time_2).'</div>';

            if($row_list->jenis_resep == 'racikan'){
                $row[] = '<div class="center">('.$this->tanggal->formatDateTimeToTime($row_list->log_time_2).' - '.$this->tanggal->formatDateTimeToTime($row_list->log_time_3).')<br>'.$this->diffHourMinute($row_list->log_time_2, $row_list->log_time_3).'</div>';
            }else{
                $row[] = '<div class="center">('.$this->tanggal->formatDateTimeToTime($row_list->log_time_2).' - '.$this->tanggal->formatDateTimeToTime($row_list->log_time_4).')<br>'.$this->diffHourMinute($row_list->log_time_2, $row_list->log_time_4).'</div>';
            }

            if($row_list->jenis_resep == 'racikan'){
                $row[] = '<div class="center">('.$this->tanggal->formatDateTimeToTime($row_list->log_time_3).' - '.$this->tanggal->formatDateTimeToTime($row_list->log_time_4).')<br>'.$this->diffHourMinute($row_list->log_time_3, $row_list->log_time_4).'</div>';
            }else{
                $row[] = '<div class="center">-</div>';
            }

            $row[] = '<div class="center">('.$this->tanggal->formatDateTimeToTime($row_list->log_time_4).' - '.$this->tanggal->formatDateTimeToTime($row_list->log_time_5).')<br>'.$this->diffHourMinute($row_list->log_time_4, $row_list->log_time_5).'</div>';
            $row[] = '<div class="center">('.$this->tanggal->formatDateTimeToTime($row_list->log_time_5).' - '.$this->tanggal->formatDateTimeToTime($row_list->log_time_6).')<br>'.$this->diffHourMinute($row_list->log_time_5, $row_list->log_time_6).'</div>';
            $row[] = '<div class="center">('.$this->tanggal->formatDateTimeToTime($row_list->log_time_1).' - '.$this->tanggal->formatDateTimeToTime($row_list->log_time_5).')<br>'.$this->diffHourMinute($row_list->log_time_1, $row_list->log_time_5).'</div>';
            $data[] = $row;

            $arr_seconds[] = $this->diffHourMinuteReturnSecond($row_list->log_time_1, $row_list->log_time_5);
        }

        $output = array(
            "count_data" => count($list),
            "tat" => $this->convertHourMinutesSecond(array_sum($arr_seconds)/count($arr_seconds)),
            "draw" => $_POST['draw'],
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

        /**
     * Hitung selisih waktu antara dua waktu (format: Y-m-d H:i:s) dalam format H:m
     * @param string $start
     * @param string $end
     * @return string
     */
    public function diffHourMinute($start, $end) {
        if ($start && $end && strtotime($start) !== false && strtotime($end) !== false) {
            $start_ts = strtotime($start);
            $end_ts = strtotime($end);
            $diff = $end_ts - $start_ts;
            $hours = floor($diff / 3600);
            $minutes = floor(($diff % 3600) / 60);
            $seconds = $diff % 60;
            if($minutes > 45){
                $color = 'red';
            }else{
                $color = 'green';
            }
            return '<span style="color: '.$color.'; font-weight: bold">'.sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds).'</span>';
        }
        return '-';
    }

    public function diffHourMinuteReturnSecond($start, $end) {
        if ($start && $end && strtotime($start) !== false && strtotime($end) !== false) {
            $start_ts = strtotime($start);
            $end_ts = strtotime($end);
            $diff = $end_ts - $start_ts;
            $hours = floor($diff / 3600);
            $minutes = floor(($diff % 3600) / 60);
            $seconds = $diff % 60;
            return $diff;
        }
        return '-';
    }

    public function convertHourMinutesSecond($second) {
        if ($second) {
            $hours = floor($second / 3600);
            $minutes = floor(($second % 3600) / 60);
            $seconds = $second % 60;
            return sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
        }
        return '-';
    }
    
}


/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
