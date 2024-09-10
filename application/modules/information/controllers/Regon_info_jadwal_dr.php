<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Regon_info_jadwal_dr extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'information/Regon_info_jadwal_dr');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('Regon_info_jadwal_dr_model', 'Regon_info_jadwal_dr');
        $this->load->model('Regon_info_jadwal_cuti_dr_model', 'Regon_info_jadwal_cuti_dr');

        /*enable profiler*/
        $this->output->enable_profiler(false);
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'Title';

    }

    public function index() { 
        //echo '<pre>';print_r($this->session->all_userdata());die;
        /*define variable data*/
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show()
        );
        /*load view index*/
        $this->load->view('Regon_info_jadwal_dr/index', $data);
    }

    public function lihat_jadwal_dokter() { 
        
        $this->load->view('Regon_info_jadwal_dr/tab_lihat_dokter');
    
    }

    public function cuti_dokter() { 
        
        $this->load->view('Regon_info_jadwal_dr/tab_cuti_dokter');
    
    }

    public function jadwal_dokter() { 
        
        $this->load->view('Regon_info_jadwal_dr/tab_jadwal_dokter');
    
    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->Regon_info_jadwal_dr->get_datatables();
        $arrData = array();
        /*format data*/
        foreach ($list as $key => $value) {
            $arrData[$value['jd_kode_dokter']][$value['jd_kode_spesialis']][] = $value;
        }

        $data = array();
        $no = $_POST['start'];

        foreach ($arrData as $key => $row_list) {

            foreach ($row_list as $key_2 => $value_2) {
                $main_data = $value_2[0];
                //print_r($main_data);die;
                $no++;
                $row = array();
                $row[] = '<div class="center">
                            <label class="pos-rel">
                                <input type="checkbox" class="ace" name="selected_id[]" value="'.$main_data['jd_id'].'"/>
                                <span class="lbl"></span>
                            </label>
                          </div>';
                $row[] = '<div class="center">
                            '.$this->authuser->show_button('information/Regon_info_jadwal_dr','U',$main_data['jd_id'],2).'
                            '.$this->authuser->show_button('information/Regon_info_jadwal_dr','D',$main_data['jd_id'],7).'
                          </div>'; 
                $row[] = '<div class="left">'.$main_data['nama_pegawai'].'</div>';
                $row[] = '<div class="left">'.ucwords($main_data['nama_bagian']).'</div>';

                for ($i=1; $i < 8; $i++) { 

                    if(count($value_2) > 0){
                        $day_lib = $this->tanggal->getDayByNum($i);
                        $key = array_search($day_lib, array_column($value_2, 'jd_hari'));

                        if(isset($value_2[$key]['jd_hari'])){
                            if($day_lib==$value_2[$key]['jd_hari']){
                                $note = ($value_2[$key]['jd_keterangan'] != '')?' <br> '.$value_2[$key]['jd_keterangan'].'':'';
                                $end = ($value_2[$key]['jd_jam_selesai'] != '')?' - '.$value_2[$key]['jd_jam_selesai'].'':'';
                                $val_result = $this->tanggal->formatTime($value_2[$key]['jd_jam_mulai']).' s/d '.$this->tanggal->formatTime($value_2[$key]['jd_jam_selesai']).'<span style="font-size:11px">'.$note.'</span>';
                                $row[] = '<div class="center">'.$val_result.'</div>';
                                /*$row[] = '<div class="center"><span class="btn btn-info btn-sm tooltip-info" data-rel="tooltip" data-placement="bottom" title="" data-original-title="Bottm Info">Bottom</span></div>';*/
                            }else{
                                $row[] = '<div class="center"></div>';
                            }
                        }
                        
                    }else{
                        $row[] = '<div class="center"></div>';
                    }
                    
                }

                //$row[] = $this->logs->show_logs_record_datatable($row_list);

                $data[] = $row;
            }
            
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Regon_info_jadwal_dr->count_all(),
                        "recordsFiltered" => $this->Regon_info_jadwal_dr->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function form($id='')
    {
        /*if id is not null then will show form edit*/
        if( $id != '' ){
            /*breadcrumbs for edit*/
            $this->breadcrumbs->push('Edit '.strtolower($this->title).'', 'Regon_info_jadwal_dr/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
            /*get value by id*/
            $data['value'] = $this->Regon_info_jadwal_dr->get_by_id($id);
            $data['jadwal'] = $this->Regon_info_jadwal_dr->get_jadwal_by_dr_spesialis($data['value']);
            /*initialize flag for form*/
            $data['flag'] = "update";
        }else{
            /*breadcrumbs for create or add row*/
            $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Regon_info_jadwal_dr/'.strtolower(get_class($this)).'/form');
            /*initialize flag for form add*/
            $data['flag'] = "create";
        }
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();

        //echo '<pre>';print_r($data);die;
        /*load form view*/
        $this->load->view('Regon_info_jadwal_dr/form', $data);
    }
    /*function for view data only*/
    public function show($id)
    {
        /*breadcrumbs for view*/
        $this->breadcrumbs->push('View '.strtolower($this->title).'', 'Regon_info_jadwal_dr/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*define data variabel*/
        $data['value'] = $this->Regon_info_jadwal_dr->get_by_id($id);
        $data['jadwal'] = $this->Regon_info_jadwal_dr->get_jadwal_by_dr_spesialis($data['value']);
        $data['title'] = $this->title;
        $data['flag'] = "read";
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Regon_info_jadwal_dr/form', $data);
    }

    public function process()
    {

        // echo '<pre>';print_r($_POST);die;

        $this->load->library('form_validation');
        $val = $this->form_validation;

        $val->set_rules('spesialis', 'Spesialis', 'trim|required');
        $val->set_rules('dokter', 'Dokter', 'trim|required');

        $val->set_message('required', "Silahkan isi field \"%s\"");

        if ($val->run() == FALSE)
        {
            $val->set_error_delimiters('<div style="color:white">', '</div>');
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            $this->db->trans_begin();

            $jd_id = $this->input->post('jd_id');
            $jd_hari = $this->input->post('jd_hari');
            $start = $this->input->post('start');
            $end = $this->input->post('end');
            $keterangan = $this->input->post('keterangan');
            $kuota_dr = $this->input->post('kuota_dr');
            $delete = $this->input->post('delete');
            $curr_edit = $this->input->post('curr_edit');

            /*check dokter and spesialis exist*/
            if( $this->input->post('flag')=='create' ){
                $is_exist = $this->Regon_info_jadwal_dr->is_exist( array('jd_kode_dokter' => $val->set_value('dokter'), 'jd_kode_spesialis' => $val->set_value('spesialis') ) );

                if($is_exist->num_rows() > 0){
                    echo json_encode(array('status' => 301, 'message' => 'Dokter dan Spesialis yang dipilih sudah memiliki Jadwal Praktek, silahkan lakukan update data pada list jadwal dokter'));
                    exit;
                }
            }
            
            foreach ($jd_hari as $key => $value) {

                if($delete[$value] != ''){
                    
                    $this->db->delete('tr_jadwal_dokter', array('jd_id' => $delete[$value]) );

                    $data_sms_regon = $this->Regon_info_jadwal_dr->get_no_booking($val->set_value('dokter'),$val->set_value('spesialis'),$jd_hari[$key]);

                    //print_r($this->db->last_query());                    
                    $days = $this->tanggal->getHariTranslate($jd_hari[$key]);

                    $data_sms_pesanan = $this->Regon_info_jadwal_dr->get_no_pesanan($val->set_value('dokter'),$val->set_value('spesialis'),$days);

                    //print_r($this->db->last_query());die;
                    $data_sms = array_merge($data_sms_regon,$data_sms_pesanan);


                    $nama_dokter = $this->db->get_where('mt_karyawan', array('kode_dokter' => $val->set_value('dokter')))->row();
                    /*send notification by sms*/
                
                    $config_sms = array(
                        'from' => COMP_SORT,
                        'data' => $data_sms,
                        'message' => '(no-reply) '.COMP_SORT.' : Mohon maaf, jadwal praktek '.$nama_dokter->nama_pegawai.' setiap hari ' .$jd_hari[$key].' sudah ditiadakan, silahkan hubungi bagian informasi',
                    );

                    $send_sms = $this->api->adsmedia_send_sms_blast($config_sms);

                }else{


                    if($start[$key] != ''){
                        
                        $dataexc = array(
                            'jd_kode_dokter' => $val->set_value('dokter'),
                            'jd_kode_spesialis' => $val->set_value('spesialis'),
                            'jd_hari' => $jd_hari[$key],
                            'jd_jam_mulai' => $this->tanggal->formatTime($start[$key]),
                            'jd_jam_selesai' => $this->tanggal->formatTime($end[$key]),
                            'jd_keterangan' => $keterangan[$key],
                            'jd_kuota' => $kuota_dr[$key],
                        );


                        if($jd_id[$key] != 0){

                            if($curr_edit[$key]!=''){

                                //print_r($jd_id[$key]);die;
                                $dataexc['updated_date'] = date('Y-m-d H:i:s');
                                $dataexc['updated_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                                $this->db->update('tr_jadwal_dokter', $dataexc, array('jd_id' => $jd_id[$key] ) );
                                /*update record*/
                                $this->Regon_info_jadwal_dr->update(array('jd_id' => $jd_id[$key]), $dataexc);
                                $newId = $jd_id[$key];
                                /*save logs*/
                                $this->logs->save('tr_jadwal_dokter', $newId, 'update record on '.$this->title.' module', json_encode($dataexc),'jd_id');

                                $data_sms_regon = $this->Regon_info_jadwal_dr->get_no_booking($val->set_value('dokter'),$val->set_value('spesialis'),$jd_hari[$key]);

                                //print_r($this->db->last_query());                    
                                $days = $this->tanggal->getHariTranslate($jd_hari[$key]);

                                $data_sms_pesanan = $this->Regon_info_jadwal_dr->get_no_pesanan($val->set_value('dokter'),$val->set_value('spesialis'),$days);

                                //print_r($this->db->last_query());die;
                                $data_sms = array_merge($data_sms_regon,$data_sms_pesanan);


                                $nama_dokter = $this->db->get_where('mt_karyawan', array('kode_dokter' => $val->set_value('dokter')))->row();
                                /*send notification by sms*/
                            
                                $config_sms = array(
                                    'from' => 'RSSM',
                                    'data' => $data_sms,
                                    'message' => '(no-reply) RSSM : Mohon maaf, jadwal praktek '.$nama_dokter->nama_pegawai.' setiap hari ' .$jd_hari[$key].' reschedule ke jam '.$this->tanggal->formatTime($start[$key]),
                                );

                                //print_r($config_sms);die;
                                $send_sms = $this->api->adsmedia_send_sms_blast($config_sms);
                            }

                        }else{
                            $dataexc['created_date'] = date('Y-m-d H:i:s');
                            $dataexc['created_by'] = json_encode(array('user_id' => $this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                            //print_r($dataexc);die;
                            /*save post data*/
                            $newId = $this->Regon_info_jadwal_dr->save($dataexc);
                            /*save logs*/
                            $this->logs->save('tr_jadwal_dokter', $newId, 'insert new record on '.$this->title.' module', json_encode($dataexc),'jd_id');

                        }
                    }
                    
                }

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
            if($this->Regon_info_jadwal_dr->delete_by_id($toArray)){
                $this->logs->save('tr_jadwal_dokter', $id, 'delete record', '', 'jd_id');
                echo json_encode(array('status' => 200, 'message' => 'Proses Hapus Data Berhasil Dilakukan'));

            }else{
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Hapus Data Gagal Dilakukan'));
            }
        }else{
            echo json_encode(array('status' => 301, 'message' => 'Tidak ada item yang dipilih'));
        }
        
    }

    public function show_data_cuti($id)
    {
        $result = $this->Regon_info_jadwal_cuti_dr->get_by_id($id);
        echo json_encode($result);
    }

    public function get_data_cuti_dr()
    {
        /*get data from model*/
        $list = $this->Regon_info_jadwal_cuti_dr->get_datatables();
        
        $data = array();
        $no = $_POST['start'];

        foreach ($list as $key => $row_list) {
            
            $no++;
            $row = array();
            $row[] = '<div class="center">'.$no.'</div>';
            $row[] = '<div class="center">
                        <div class="center">
                        <button type="button" class="btn btn-xs btn-success" onclick="show_data('.$row_list->cuti_id.')"><i class="ace-icon fa fa-edit bigger-50"></i></button>
                        <button type="button" class="btn btn-xs btn-danger" onclick="delete_data_cuti('.$row_list->cuti_id.')"><i class="ace-icon fa fa-times bigger-50"></i></button>
                      </div>
                      </div>'; 
            $row[] = '<div class="left">'.$row_list->nama_pegawai.'</div>';
            $row[] = '<div class="left">'.ucwords($row_list->nama_bagian).'</div>';
            $row[] = '<div class="center">'.$this->tanggal->formatDateDmy($row_list->from_tgl).'</div>';
            $row[] = '<div class="center">'.$this->tanggal->formatDateDmy($row_list->to_tgl).'</div>';
            $row[] = '<div class="left">'.$row_list->keterangan_cuti.'</div>';
            if(date('Y-m-d') < $row_list->from_tgl){
                $status = '<span class="label label-warning">Akan datang</span>';
            }else{
                if($row_list->from_tgl <= date('Y-m-d') && $row_list->to_tgl >= date('Y-m-d')){
                    $status = '<span class="label label-success">Sedang Cuti</span>';
                }else{
                    $status = '<span class="label label-danger">Expired</span>';
                }
            }
            
            $row[] = '<div class="center">'.$status.'</div>';

            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Regon_info_jadwal_cuti_dr->count_all(),
                        "recordsFiltered" => $this->Regon_info_jadwal_cuti_dr->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function process_cuti_dr()
    {

        // echo '<pre>';print_r($_POST);die;

        $this->load->library('form_validation');
        $val = $this->form_validation;

        $val->set_rules('klinik_rajal', 'Klinik', 'trim|required');
        $val->set_rules('dokter_rajal', 'Dokter', 'trim|required');
        $val->set_rules('from_tgl', 'Tanggal Cuti', 'trim|required');
        $val->set_rules('to_tgl', 'sd Tanggal Cuti', 'trim|required');
        $val->set_rules('keterangan_cuti', 'Keterangan Cuti', 'trim|required');

        $val->set_message('required', "Silahkan isi field \"%s\"");

        if ($val->run() == FALSE)
        {
            $val->set_error_delimiters('<div style="color:white">', '</div>');
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            $this->db->trans_begin();

            // id
            $id = ($_POST['cuti_id'])?$_POST['cuti_id']:0;

            $dataexc = array(
                'kode_bag' => $this->regex->_genRegex($val->set_value('klinik_rajal'),'RGXQSL'),
                'kode_dr' => $this->regex->_genRegex($val->set_value('dokter_rajal'),'RGXINT'),
                'from_tgl' => $this->regex->_genRegex($val->set_value('from_tgl'),'RGXQSL'),
                'to_tgl' => $this->regex->_genRegex($val->set_value('to_tgl'),'RGXQSL'),
                'keterangan_cuti' => $this->regex->_genRegex($val->set_value('keterangan_cuti'),'RGXQSL'),
                'is_active' => $this->regex->_genRegex('Y','RGXQSL'),
            );


            if($id == 0){

                $dataexc['created_date'] = date('Y-m-d H:i:s');
                $dataexc['created_by'] = json_encode(array('user_id' => $this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                $dataexc['updated_date'] = date('Y-m-d H:i:s');
                $dataexc['updated_by'] = json_encode(array('user_id' => $this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                $this->db->insert('tr_jadwal_cuti_dr', $dataexc);
                $newId = $this->db->insert_id();

            }else{

                $dataexc['updated_date'] = date('Y-m-d H:i:s');
                $dataexc['updated_by'] = json_encode(array('user_id' => $this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                
                $this->db->where('cuti_id', $id)->update('tr_jadwal_cuti_dr', $dataexc);
                $newId = $id;

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

    public function delete_data_cuti()
    {
        $id=$this->input->post('ID')?$this->regex->_genRegex($this->input->post('ID',TRUE),'RGXQSL'):null;
        $toArray = explode(',',$id);
        if($id!=null){
            if($this->Regon_info_jadwal_cuti_dr->delete_by_id($toArray)){
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
