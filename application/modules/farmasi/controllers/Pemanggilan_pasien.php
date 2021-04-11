<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pemanggilan_pasien extends MX_Controller {

    /*function constructor*/
    function __construct() 
    {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'farmasi/Pemanggilan_pasien');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('Pemanggilan_pasien_model', 'Pemanggilan_pasien');
        // load library
        $this->load->library('Print_direct');
        $this->load->library('Print_escpos'); 
        $this->load->library('stok_barang');
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
        $this->load->view('Pemanggilan_pasien/index', $data);
    }

    public function form($id='')
    {
        /*if id is not null then will show form edit*/
        /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Entry Resep '.strtolower($this->title).'', 'Pemanggilan_pasien/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*get value by id*/
        $data['value'] = $this->Pemanggilan_pasien->get_by_id($id);
        $data['detail_obat'] = $this->Pemanggilan_pasien->get_detail_resep_data($id)->result();
        // echo '<pre>';print_r($data);die;
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Pemanggilan_pasien/form', $data);
    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->Pemanggilan_pasien->get_datatables();
        if(isset($_GET['search']) AND $_GET['search']==TRUE){
            $this->find_data(); exit;
        }
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
            $row[] = '<div class="center"><a href="#" onclick="getMenu('."'farmasi/Process_entry_resep/preview_entry/".$row_list->kode_trans_far."?flag=".$flag."&status_lunas=1'".')">'.$row_list->kode_trans_far.'</a></div>';

            // $row[] = '<div class="center">'.$row_list->no_resep.'</div>';
            $row[] = $this->tanggal->formatDateTime($row_list->tgl_trans);
            $row[] = '<div style="font-size: 16px; font-weight: bold">'.$row_list->no_mr.' - '.strtoupper($row_list->nama_pasien).'</div>';
            // $row[] = $row_list->dokter_pengirim;
            $row[] = $row_list->nama_pelayanan;
            // $row[] = ($row_list->status_terima == 1)?'Selesai':'Pending';
            $row[] = '<div class="center">
                         <a href="#" class="btn btn-sm btn-warning" onclick="exc_process('.$row_list->kode_trans_far.', 2)"> <i class="fa fa-check-circle"></i> Pending</a>
                         <a href="#" class="btn btn-sm btn-primary" onclick="exc_process('.$row_list->kode_trans_far.', 1)"> <i class="fa fa-check-circle"></i> Selesai</a>
                       </div>';
            
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        // "recordsTotal" => $this->Pemanggilan_pasien->count_all(),
                        // "recordsFiltered" => $this->Pemanggilan_pasien->count_filtered(),
                        "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function get_data_pending()
    {
        /*get data from model*/
        $list = $this->Pemanggilan_pasien->get_datatables_pending();
        if(isset($_GET['search']) AND $_GET['search']==TRUE){
            $this->find_data(); exit;
        }
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
            $row[] = '<div class="center"><a href="#" onclick="getMenu('."'farmasi/Process_entry_resep/preview_entry/".$row_list->kode_trans_far."?flag=".$flag."&status_lunas=1'".')">'.$row_list->kode_trans_far.'</a></div>';

            // $row[] = '<div class="center">'.$row_list->no_resep.'</div>';
            $row[] = $this->tanggal->formatDateTime($row_list->tgl_trans);
            $row[] = '<div class="center">'.$row_list->no_mr.'</div>';
            $row[] = strtoupper($row_list->nama_pasien);
            // $row[] = $row_list->dokter_pengirim;
            $row[] = $row_list->nama_pelayanan;
            $row[] = ($row_list->status_terima == 1)?'Selesai':'Pending';
            $row[] = '<div class="center">
                        <a href="#" class="btn btn-sm btn-danger" onclick="exc_process('.$row_list->kode_trans_far.', 0)"> <i class="fa fa-undo"></i> Rollback</a>
                        <a href="#" class="btn btn-sm btn-primary" onclick="exc_process('.$row_list->kode_trans_far.', 1)"> <i class="fa fa-check-circle"></i> Selesai</a>
                       </div>';
            
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Pemanggilan_pasien->count_all(),
                        "recordsFiltered" => $this->Pemanggilan_pasien->count_filtered(),
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
        $this->form_validation->set_rules('code', 'Flag', 'trim|required');

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
            $this->db->where('kode_trans_far', $_POST['ID'])->update('fr_tc_far', array('status_terima' => $_POST['code']));
                        
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

    public function process_undo()
    {
        // print_r($_POST);die;
        $this->load->library('form_validation');
        // form validation

        $this->form_validation->set_rules('kode_trans_far', 'Kode Transaksi', 'trim|required');
        $this->form_validation->set_rules('kd_his', 'Kode History', 'trim|required');
        $this->form_validation->set_rules('kd_tr_resep', 'Kode Resep Barang', 'trim|required');

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
            $dt_existing = $this->db->join('fr_tc_far_detail', 'fr_tc_far_detail.kd_tr_resep=fr_tc_far_his.kd_tr_resep', 'left')->get_where('fr_tc_far_his', array('kd_his' => $_POST['kd_his']) )->row();

            $jumlah_his_retur = $dt_existing->jumlah_retur - $dt_existing->jumlah_retur_his;
            $sisa = $dt_existing->jumlah_tebus + $dt_existing->jumlah_retur_his;
            $harga_satuan = $dt_existing->harga_jual;                    
            $subtotal_stl_retur = $harga_satuan * $sisa;
            $data_farmasi_detail = array(
                'jumlah_pesan' => $sisa,
                'jumlah_tebus' => $sisa,
                'jumlah_retur' => $jumlah_his_retur,
                'sub_total' => $subtotal_stl_retur,
                'total' => ($sisa > 0) ? $subtotal_stl_retur + $dt_existing->harga_r : 0,
                'tgl_retur' => date('Y-m-d H:i:s'),
                'retur_by' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL'),
                'updated_date' => date('Y-m-d H:i:s'),
                'updated_by' => json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL'))),
            );
            // print_r($data_farmasi_detail);die;
            //  fr_tc_far_detail_log
            $this->db->update('fr_tc_far_detail_log', $data_farmasi_detail, array('relation_id' => $_POST['kd_tr_resep'], 'kode_trans_far' => $_POST['kode_trans_far']) );
            /*save log*/
            $this->logs->save('fr_tc_far_detail_log', $_POST['kd_tr_resep'], 'update record on entry resep module', json_encode($data_farmasi_detail), 'relation_id');

            //  fr_tc_far_detail
            $harga_retur = ($jumlah_his_retur > 0) ? $harga_satuan * $jumlah_his_retur : 0;
            $data_farmasi = array();
            $data_farmasi['jumlah_pesan'] = $sisa;
            $data_farmasi['jumlah_tebus'] = $sisa;
            $data_farmasi['jumlah_retur'] = $jumlah_his_retur;
            $data_farmasi['harga_r_retur'] = $harga_retur;
            $data_farmasi['biaya_tebus'] = ($sisa > 0) ? $subtotal_stl_retur + $dt_existing->harga_r : 0;
            $data_farmasi['status_retur'] = 0;
            // print_r($data_farmasi);die;
            $this->db->update('fr_tc_far_detail', $data_farmasi, array('kd_tr_resep' => $_POST['kd_tr_resep'], 'kode_trans_far' => $_POST['kode_trans_far']) );
            /*save log*/
            $this->logs->save('fr_tc_far_detail', $_POST['kd_tr_resep'], 'update record on entry resep module', json_encode($data_farmasi),'kd_tr_resep');

            $this->stok_barang->stock_process($dt_existing->kode_brg, $dt_existing->jumlah_retur_his, '060101', 8 ," (Undo Retur) Kode. ".$_POST['kode_trans_far']." ", 'reduce');

            $this->db->delete('fr_tc_far_his', array('kd_his' => $_POST['kd_his']));
                        
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
        $resep_log = $this->Pemanggilan_pasien->get_etiket_data();
        // echo '<pre>';print_r($resep_log->result());die;
        $data = array();
        $data['result'] = $resep_log->result();
        $this->load->view('farmasi/Pemanggilan_pasien/preview_etiket', $data);

    }

    public function preview_copy_resep($kode_trans_far){
        
        // get etiket data from query string
        $resep_log = $this->db->join('mt_master_pasien','mt_master_pasien.no_mr=fr_tc_far.no_mr','left')->get_where('fr_tc_far', array('kode_trans_far' => $kode_trans_far) )->row();
        // echo '<pre>';print_r($resep_log);die;
        $data = array();
        $data['result'] = $resep_log;
        $this->load->view('farmasi/Pemanggilan_pasien/preview_copy_resep', $data);

    }

    function nota_retur($no_retur){
        $his_retur = $this->Pemanggilan_pasien->get_history_retur_by_no_retur($no_retur);
        $data = array(
            'retur_data' => $his_retur,
        );
        // echo '<pre>';print_r($data);die;
        $this->load->view('farmasi/Pemanggilan_pasien/preview_nota_retur', $data);

    }

    public function print_tracer_obat($kode_trans_far)
    {   
        $resep_log = $this->Pemanggilan_pasien->get_detail_resep_data($kode_trans_far);
        // echo '<pre>';print_r($resep_log->result());die;
        $this->print_escpos->print_resep_gudang($resep_log->result());
        $data = array();
        $data['result'] = $resep_log->result();
        $this->load->view('farmasi/Pemanggilan_pasien/preview_tracer_obat', $data);
    }

    public function show_retur_data($id){
        
        $data = $this->Farmasi_pesan_resep->get_detail_by_id($id);
        
        $html = '';
        $html_btn = '';
        if(count($data) > 0){
            $his_retur = $this->Pemanggilan_pasien->get_history_retur($data[0]->kode_trans_far);
            
            if(count($his_retur) > 0){
                $html .= '<p class="center">
                            <span style="font-size: 16px; font-weight: bold">RETUR OBAT FARMASI</span><br>
                            <span>Riwayat Retur Obat Farmasi dengan Kode Transaksi '.$data[0]->kode_trans_far.'</span>
                          </p>';
                foreach ($his_retur as $khr => $vhr) {
                    $html .= 'No. Retur : '.$khr.' &nbsp;&nbsp;&nbsp; Tgl. '.$this->tanggal->formatDateTimeFormDmy($vhr[0]->tgl_his_retur).'';
                    $html .= '&nbsp;&nbsp;&nbsp;&nbsp;<a href="#" onclick="PopupCenter('."'farmasi/Pemanggilan_pasien/nota_retur/".$khr."'".')"><i class="fa fa-print dark bigger-150"></i> </a>';
                    $html .= '<table class="table" style="width: 100%">';
                    $html .= '<thead>';
                    $html .= '<tr>';
                    $html .= '<th class="center">No</th><th>Nama Obat</th><th width="100px">Jumlah Retur</th><th class="center">Subtotal</th><th class="center">Undo</th>';
                    $html .= '</tr>';
                    $html .= '</thead>';
                    $no = 1;
                    foreach ($vhr as $k_sub_dt => $v_sub_dt) {
                        # code...
                        $html .= '<tr>';
                        $html .= '<td align="center">'.$no.'</td>';
                        $html .= '<td>'.$v_sub_dt->nama_brg.'</td>';
                        $html .= '<td align="center">'.$v_sub_dt->jumlah_retur_his.'</td>';
                        $html .= '<td align="right">'.number_format($v_sub_dt->biaya_retur_his).'</td>';
                        $html .= '<td align="center"><a href="#" onclick="undo_retur('.$v_sub_dt->kd_his.', '.$v_sub_dt->kd_tr_resep.', '.$data[0]->kode_trans_far.')" class="btn btn-xs btn-danger"><i class="fa fa-undo"></i></a></td>';
                        $html .= '</tr>';
                        $no++;
                    }
                    $html .= '</table>';
                }
            }
        }else{
            $html .= '<div style="border-bottom:1px solid #333;"><b>Belum diproses</b></div><br>';
        }
        
        echo json_encode(array('html' => $html));
    }
    
}


/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
