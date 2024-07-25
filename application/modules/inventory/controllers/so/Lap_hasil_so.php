<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Lap_hasil_so extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'inventory/so/Lap_hasil_so');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('inventory/so/Lap_hasil_so_model', 'Lap_hasil_so');
        $this->load->model('inventory/so/Dt_bag_so_model', 'Dt_bag_so');
        $this->load->model('inventory/so/Dt_bag_so_rs_model', 'Dt_bag_so_rs');
        $this->load->model('inventory/so/Dt_hasil_so_model', 'Dt_hasil_so');
        /*enable profiler*/
        $this->output->enable_profiler(false);
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'Title';

    }

    public function index() { 
        //echo '<pre>';print_r($this->session->all_userdata());
        /*define variable data*/
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show()
        );
        /*load view index*/
        $this->load->view('so/Lap_hasil_so/index', $data);
    }

    public function form($id='')
    {
        /*if id is not null then will show form edit*/
        if( $id != '' ){
            /*breadcrumbs for edit*/
            $this->breadcrumbs->push('Edit '.strtolower($this->title).'', 'Lap_hasil_so/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
            /*get value by id*/
            $data['value'] = $this->Lap_hasil_so->get_by_id($id);
            /*initialize flag for form*/
            $data['flag'] = "update";
        }else{
            /*breadcrumbs for create or add row*/
            $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Lap_hasil_so/'.strtolower(get_class($this)).'/form');
            /*initialize flag for form add*/
            $data['flag'] = "create";
        }
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('so/Lap_hasil_so/form', $data);
    }
    /*function for view data only*/
    public function view_so_bag($id)
    {
        /*breadcrumbs for view*/
        $this->breadcrumbs->push('View '.strtolower($this->title).'', 'Lap_hasil_so/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*define data variabel*/
        $data['value'] = $this->Lap_hasil_so->get_by_id($id);
        $data['title'] = $this->title;
        $data['flag'] = "read";
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('so/Lap_hasil_so/form', $data);
    }

    public function view_data_bag_so($agenda_so_id, $flag)
    {
        $data = array();
        /*define data variabel*/
        $data['title'] = $this->title.' '.ucfirst($flag);
        $data['flag'] = $flag;
        $data['agenda_so_id'] = $agenda_so_id;
        /*load form view*/
        $this->load->view('so/Lap_hasil_so/form_data_bag_so', $data);
    }

    public function view_data_bag_so_rs($agenda_so_id, $flag)
    {
        $data = array();
        /*define data variabel*/
        $data['title'] = $this->title.' '.ucfirst($flag);
        $data['flag'] = $flag;
        $data['agenda_so_id'] = $agenda_so_id;
        /*load form view*/
        $this->load->view('so/Lap_hasil_so/form_data_bag_so_rs', $data);
    }

    public function view_data_hasil_so($agenda_so_id, $kode_bagian, $flag)
    {
        $data = array();
        /*define data variabel*/
        $data['title'] = $this->master->get_string_data('nama_bagian', 'mt_bagian', array('kode_bagian' => $kode_bagian));
        $data['kode_bagian'] = $kode_bagian;
        $data['agenda_so_id'] = $agenda_so_id;
        $data['flag'] = $flag;
        /*load form view*/
        $this->load->view('so/Lap_hasil_so/form_hasil_data_bag_so', $data);
    }

    public function get_total_rp_hasil_so()
    {
        /*get data from model*/
        $list = $this->Dt_hasil_so->get_all_data(); 
        
        $arr_harga_not_active = array();
        $arr_harga = array();
        $arr_harga_brg_exp = array();
        $arr_harga_brg_will_exp = array();
        foreach($list as $row){
            
            if( $row->harga_pembelian_terakhir != 0 AND $row->stok_sekarang != 0 ){
                $harga_pembelian_terakhir = ( $row->harga_pembelian_terakhir != 0 ) ? ($row->harga_pembelian_terakhir / $row->content) : 0;
                // barang aktif
                if( $row->set_status_aktif == 1 ){
                    $arr_harga[] = round($harga_pembelian_terakhir * $row->stok_sekarang);
                }

                if( $row->set_status_aktif == 0 ){
                    $arr_harga_not_active[] = round($harga_pembelian_terakhir * $row->stok_sekarang);
                }

                if( $row->stok_exp > 0 ){
                    $arr_harga_brg_exp[] = round($harga_pembelian_terakhir * $row->stok_exp);
                }

                if( $row->will_stok_exp > 0 ){
                    $arr_harga_brg_will_exp[] = round($harga_pembelian_terakhir * $row->will_stok_exp);
                }

            }
        }

        // echo '<pre>'; print_r($arr_harga);die;
        $result = array(
            'total_rp_aktif' => array_sum($arr_harga),
            'total_rp_not_aktif' => array_sum($arr_harga_not_active),
            'total_rp_exp' => array_sum($arr_harga_brg_exp),
            'total_rp_will_exp' => array_sum($arr_harga_brg_will_exp),
        );

        echo json_encode($result);
    }

    public function total_aset_barang_rs()
    {
        /*get data from model*/
        $list = $this->Dt_bag_so_rs->get_all_data(); 
        
        $arr_harga_not_active = array();
        $arr_harga = array();
        $arr_harga_not_active = array();
        
        foreach($list as $row){
            
            $hpa = ($row->harga_pembelian_terakhir > 0 AND $row->content > 0) ? $row->harga_pembelian_terakhir / $row->content : 0;

            if( $row->set_status_aktif == 1 || $row->set_status_aktif != 0 ){
                if( $hpa > 0 AND $row->stok_sekarang > 0 ){
                    $arr_harga[] = round($hpa * $row->stok_sekarang);
                }
            }
            else{
                if( $hpa > 0 AND $row->stok_sekarang > 0 ){
                    $arr_harga_not_active[] = round($hpa * $row->stok_sekarang);
                }
            }

            if( $row->stok_exp > 0 ){
                $arr_harga_not_active[] = round($hpa * $row->stok_exp);
            }

            if( $row->will_stok_exp > 0 ){
                $arr_harga_will_exp[] = round($hpa * $row->will_stok_exp);
            }

        }

        $result = array(
            'total_aset_barang_rs' => array_sum($arr_harga),
            'total_aset_barang_rs_not_active' => array_sum($arr_harga_not_active),
            'total_exp_barang_rs' => array_sum($arr_harga_not_active),
            'total_will_exp_barang_rs' => array_sum($arr_harga_will_exp),
        );

        echo json_encode($result);
    }

    public function log_barang(){
        $data = array();
        /*define data variabel*/
        $mt_barang = ($_GET['flag']=='medis')?'mt_barang':'mt_barang_nm';
        $tc_stok_opname = ($_GET['flag']=='medis')?'tc_stok_opname':'tc_stok_opname_nm';
        $data['title'] = $this->master->get_string_data('nama_brg', $mt_barang, array('kode_brg' => $_GET['kode_brg']));
        $data['kode_brg'] = $_GET['kode_brg'];
        $data['log_barang'] = $this->Lap_hasil_so->get_log_barang($_GET['agenda_so_id'], $_GET['kode_brg'], $_GET['flag']); 
        $data['agenda_so_id'] = $_GET['agenda_so_id'];
        $data['flag'] = $_GET['flag'];
        // echo '<pre>';print_r($data);die;
        $this->load->view('so/Lap_hasil_so/log_brg', $data);
    }

    public function excel()
    {
        $data = array();
        /*define data variabel*/
        $data['title'] = $this->master->get_string_data('nama_bagian','mt_bagian', array('kode_bagian' => $_GET['kode_bagian']));
        $data['value'] = $this->Lap_hasil_so->get_by_id($_GET['agenda_so_id']);
        $data['result_content'] = $this->Dt_hasil_so->get_all_data();
        // echo '<pre>';print_r($data);die;
        $data['kode_bagian'] = $_GET['kode_bagian'];
        $data['agenda_so_id'] = $_GET['agenda_so_id'];
        $data['flag'] = $_GET['flag'];
        /*load form view*/
        $this->load->view('so/Lap_hasil_so/hasil_excel_so', $data);
    }

    public function excel_rs()
    {
        $data = array();
        /*define data variabel*/
        $data['title'] = 'Hasil Stok Opname Rumah Sakit';
        $data['value'] = $this->Lap_hasil_so->get_by_id($_GET['agenda_so_id']);
        $data['result_content'] = $this->Dt_bag_so_rs->get_all_data();
        $data['agenda_so_id'] = $_GET['agenda_so_id'];
        $data['flag'] = $_GET['flag'];
        /*load form view*/
        // echo '<pre>';print_r($data);die;
        $this->load->view('so/Lap_hasil_so/hasil_excel_so_rs', $data);
    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->Lap_hasil_so->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center">
                        <label class="pos-rel">
                            <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->agenda_so_id.'"/>
                            <span class="lbl"></span>
                        </label>
                      </div>';
            $row[] = '<div class="center">'.$row_list->agenda_so_id.'</div>';
            $row[] = '<a href="#" onclick="getMenu('."'inventory/so/Lap_hasil_so/view_so_bag/".$row_list->agenda_so_id."'".')">'.ucwords($row_list->agenda_so_name).'</a>';
            $row[] = $this->tanggal->formatDate($row_list->agenda_so_date).' - '.$row_list->agenda_so_time;
            $row[] = $row_list->agenda_so_spv;
            $row[] = $row_list->agenda_so_desc;
            $row[] = ($row_list->is_active == 'Y') ? '<div class="center"><span class="label label-sm label-success">Active</span></div>' : '<div class="center"><span class="label label-sm label-danger">Not active</span></div>';
            $row[] = $this->logs->show_logs_record_datatable($row_list);
                   
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Lap_hasil_so->count_all(),
                        "recordsFiltered" => $this->Lap_hasil_so->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function get_data_bag_so()
    {
        /*get data from model*/
        $list = $this->Dt_bag_so->get_datatables();
        $list_dt = $this->Dt_bag_so->_main_query_all_dt();
        // echo '<pre>';print_r($list_dt);die;
        $data = array();
        $no = $_POST['start'];

        foreach ($list_dt as $row_dt) {
            
            if( $row_dt->set_status_aktif == 1 || $row_dt->set_status_aktif != 0 ){
                $arr_aktif[$row_dt->kode_bagian][] = true;
            }
            if( $row_dt->set_status_aktif == 0 ){
                $arr_not_aktif[$row_dt->kode_bagian][] = true;
            }

            if( $row_dt->stok_exp > 0 ){
                $arr_exp[$row_dt->kode_bagian][] = true;
            }

            if( $row_dt->will_stok_exp > 0 ){
                $arr_will_exp[$row_dt->kode_bagian][] = true;
            }
        }
        // echo '<pre>';print_r($arr_not_aktif);die;

        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $count_aktif = isset($arr_aktif[$row_list->kode_bagian]) ? count($arr_aktif[$row_list->kode_bagian]) : 0;
            $count_not_aktif = isset($arr_not_aktif[$row_list->kode_bagian])?count($arr_not_aktif[$row_list->kode_bagian]): 0;
            $count_exp = isset($arr_exp[$row_list->kode_bagian])?count($arr_exp[$row_list->kode_bagian]): 0;
            $count_will_exp = isset($arr_will_exp[$row_list->kode_bagian])?count($arr_will_exp[$row_list->kode_bagian]): 0;
            $total = $count_aktif + $count_not_aktif + $count_exp + $count_will_exp;
            $row[] = '<div class="center">'.$no.'</div>';
            $row[] = '<div class="center">'.$row_list->kode_bagian.'</div>';
            $row[] = '<a href="#" onclick="getMenuTabs('."'inventory/so/Lap_hasil_so/view_data_hasil_so/".$_GET['agenda_so_id']."/".$row_list->kode_bagian."/".$_GET['flag']."'".', '."'tabs_so'".')">'.ucwords($row_list->nama_bagian).'</a>';
            $row[] = '<div class="center">'.$count_aktif.'</div>';
            $row[] = '<div class="center">'.$count_not_aktif.'</div>';
            $row[] = '<div class="center">'.$count_will_exp.'</div>';
            $row[] = '<div class="center">'.$count_exp.'</div>';
            $row[] = '<div class="center">'.$total.'</div>';
                   
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Dt_bag_so->count_all(),
                        "recordsFiltered" => $this->Dt_bag_so->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function get_data_bag_so_rs()
    {
        /*get data from model*/
        $list = $this->Dt_bag_so_rs->get_datatables();
        $data = array();
        $no = $_POST['start'];

        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center">'.$no.'</div>';
            $row[] = '<div class="center"><a href="#" onclick="get_rincian_log('."'".$row_list->kode_brg."'".')">'.$row_list->kode_brg.'</a></div>';
            $row[] = '<div class="left">'.$row_list->nama_brg.'</div>';
            $row[] = '<div class="center">'.number_format($row_list->stok_sebelum).'</div>';
            $row[] = '<div class="center">'.number_format($row_list->stok_sekarang).'</div>';
            $row[] = '<div class="center">'.number_format($row_list->stok_exp).'</div>';
            $row[] = '<div class="center">'.number_format($row_list->will_stok_exp).'</div>';
            $harga = isset($row_list->harga_pembelian_terakhir)?$row_list->harga_pembelian_terakhir:0;
            $content = isset($row_list->content)?$row_list->content:0;
            $hpa = ($content > 0) ? $harga / $content : 0;
            $total = $row_list->stok_sekarang * $hpa;
            $row[] = '<div align="right">'.number_format($hpa).'</div>';
            $row[] = '<div align="right">'.number_format($total).'</div>';
            $data[] = $row;
            // hasil so 
            if($row_list->stok_sekarang > 0){
                $arr_hasil_so[] = $total;
            }
            if($row_list->stok_exp > 0){
                $total = $row_list->stok_exp * $hpa;
                $arr_hasil_expired[] = $total;
            }
            if($row_list->will_stok_exp > 0){
                $total = $row_list->will_stok_exp * $hpa;
                $arr_hasil_expired_soon[] = $total;
            }
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Dt_bag_so_rs->count_all(),
                        "recordsFiltered" => $this->Dt_bag_so_rs->count_filtered(),
                        "data" => $data,
                        "total_active" => array_sum($arr_hasil_so),
                        "total_expired" => array_sum($arr_hasil_expired),
                        "total_expired_soon" => array_sum($arr_hasil_expired_soon),
                );
        //output to json format
        echo json_encode($output);
    }

    public function get_data_hasil_bag_so()
    {
        /*get data from model*/
        $list = $this->Dt_hasil_so->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $hpa = ( !empty($row_list->harga_pembelian_terakhir) ) ? $row_list->harga_pembelian_terakhir : 0 ;
            $harga_pembelian_terakhir = ( $hpa > 0 AND $row_list->content > 0) ? ($hpa / $row_list->content) : 0;
            $total = $row_list->stok_sekarang * $hpa;
            $totalr = $row_list->stok_sekarang * $harga_pembelian_terakhir;
            $no++;
            $row = array();
            $row[] = '<div class="center">'.$no.'</div>';
            $row[] = '<div class="center">'.$row_list->kode_brg.'</div>';
            $row[] = $row_list->nama_brg;
            // $row[] = '<div align="right">'.number_format($hpa).'</div>';
            $row[] = '<div align="right">'.number_format($harga_pembelian_terakhir).'</div>';
            $row[] = '<div class="center">'.$row_list->satuan_kecil.'</div>';
            // $row[] = '<div class="center">'.$row_list->content.'</div>';
            $row[] = '<div class="center">'.$row_list->stok_sebelum.'</div>';
            $row[] = '<div class="center">'.$row_list->stok_sekarang.'</div>';
            $row[] = '<div class="center">'.$row_list->will_stok_exp.'</div>';
            $row[] = '<div class="center">'.$row_list->stok_exp.'</div>';
            // $row[] = '<div align="right">'.number_format($total).'</div>';
            $row[] = '<div align="right">'.number_format($totalr).'</div>';
            $status_aktif = ($row_list->set_status_aktif==0)?'<span style="color:red; font-weight: bold;">Not Active</span>':'<span style="color:green; font-weight: bold;">Active</span>';
            $row[] = '<div class="center">'.$status_aktif.'</div>';
            $row[] = $row_list->nama_petugas.'<br>'.$this->tanggal->formatDateTime($row_list->tgl_stok_opname);
                   
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Dt_hasil_so->count_all(),
                        "recordsFiltered" => $this->Dt_hasil_so->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function process()
    {
       
        $this->load->library('form_validation');
        $val = $this->form_validation;
        $val->set_rules('agenda_so_name', 'Nama Agenda', 'trim|required');
        $val->set_rules('agenda_so_date', 'Tanggal', 'trim|required');
        $val->set_rules('agenda_so_time', 'Waktu/Jam', 'trim|required');
        $val->set_rules('agenda_so_spv', 'Penananggung Jawab', 'trim|required|xss_clean');
        $val->set_rules('agenda_so_desc', 'Keterangan', 'trim|xss_clean');
        $val->set_rules('is_active', 'Status Aktif', 'trim|required|xss_clean');

        $val->set_message('required', "Silahkan isi field \"%s\"");
        $val->set_message('integer', "Field \"%s\" harus diisi dengann angka");

        if ($val->run() == FALSE)
        {
            $val->set_error_delimiters('<div style="color:white">', '</div>');
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            $this->db->trans_begin();
            $id = ($this->input->post('id'))?$this->regex->_genRegex($this->input->post('id'),'RGXINT'):0;

            $dataexc = array(
                'agenda_so_name' => $this->regex->_genRegex($val->set_value('agenda_so_name'),'RGXQSL'),
                'agenda_so_date' => $this->regex->_genRegex($val->set_value('agenda_so_date'),'RGXQSL'),
                'agenda_so_time' => $this->regex->_genRegex($val->set_value('agenda_so_time'),'RGXQSL'),
                'agenda_so_spv' => $this->regex->_genRegex($val->set_value('agenda_so_spv'),'RGXQSL'),
                'agenda_so_desc' => $this->regex->_genRegex($val->set_value('agenda_so_desc'),'RGXQSL'),
                'is_active' => $this->regex->_genRegex($val->set_value('is_active'),'RGXQSL'),
            );
            
            if($id==0){
                $dataexc['created_date'] = date('Y-m-d H:i:s');
                $dataexc['created_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                $newId = $this->Lap_hasil_so->save($dataexc);
                /*save logs*/
                $this->logs->save('tc_stok_opname_agenda', $newId, 'insert new record on '.$this->title.' module', json_encode($dataexc),'agenda_so_id');
            }else{
                $dataexc['updated_date'] = date('Y-m-d H:i:s');
                $dataexc['updated_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                /*print_r($dataexc);die;*/
                /*update record*/
                $this->Lap_hasil_so->update(array('agenda_so_id' => $id), $dataexc);
                $newId = $id;
                $this->logs->save('tc_stok_opname_agenda', $newId, 'update record'.$this->title.' module', json_encode($dataexc), 'agenda_so_id');
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

    public function delete()
    {
        $id=$this->input->post('ID')?$this->regex->_genRegex($this->input->post('ID',TRUE),'RGXQSL'):null;
        $toArray = explode(',',$id);
        if($id!=null){
            if($this->Lap_hasil_so->delete_by_id($toArray)){
                $this->logs->save('tc_stok_opname_agenda', $id, 'delete record', '', 'agenda_so_id');
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
/* Location: ./application/modules/example/controllers/example.php */
