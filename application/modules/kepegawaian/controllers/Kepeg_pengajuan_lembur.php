<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Kepeg_pengajuan_lembur extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'kepegawaian/Kepeg_pengajuan_lembur');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('Kepeg_pengajuan_lembur_model', 'Kepeg_pengajuan_lembur');
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
        $this->load->view('Kepeg_pengajuan_lembur/index', $data);
    }

    public function form($id='')
    {
        /*if id is not null then will show form edit*/
        if( $id != '' ){
            /*breadcrumbs for edit*/
            $this->breadcrumbs->push('Edit '.strtolower($this->title).'', 'Kepeg_pengajuan_lembur/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
            /*get value by id*/
            $data['value'] = $this->Kepeg_pengajuan_lembur->get_by_id($id);
            /*initialize flag for form*/
            $data['flag'] = "update";
        }else{
            /*breadcrumbs for create or add row*/
            $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Kepeg_pengajuan_lembur/'.strtolower(get_class($this)).'/form');
            /*initialize flag for form add*/
            $data['flag'] = "create";
        }
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Kepeg_pengajuan_lembur/form', $data);
    }
    
    /*function for view data only*/
    public function form_rincian_lembur($id)
    {
        /*breadcrumbs for view*/
        $this->breadcrumbs->push('View '.strtolower($this->title).'', 'Kepeg_pengajuan_lembur/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*define data variabel*/
        $data['value'] = $this->Kepeg_pengajuan_lembur->get_by_id($id);
        $data['title'] = $this->title;
        $data['flag'] = "read";
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        // echo '<pre>';print_r($data);
        /*load form view*/
        $this->load->view('Kepeg_pengajuan_lembur/form_rincian_lembur', $data);
    }

    public function show($id)
    {
        /*breadcrumbs for view*/
        $this->breadcrumbs->push('View '.strtolower($this->title).'', 'Kepeg_pengajuan_lembur/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*define data variabel*/
        $data['value'] = $this->Kepeg_pengajuan_lembur->get_by_id($id);
        $data['title'] = $this->title;
        $data['flag'] = "read";
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Kepeg_pengajuan_lembur/form', $data);
    }

    public function show_detail( $id )
    {   
        $fields = $this->master->list_fields( 'kepeg_pengajuan_lembur' );
        // print_r($fields);die;
        $data = $this->Kepeg_pengajuan_lembur->get_by_id($id);
        $html = $this->master->show_detail_row_table( $fields, $data );      

        echo json_encode( array('html' => $html) );
    }

    public function get_dt_by_id()
    {   
        $id = $_POST['ID'];
        $data = $this->Kepeg_pengajuan_lembur->get_rincian_by_id($id);    
        echo json_encode( $data );
    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->Kepeg_pengajuan_lembur->get_datatables();
        
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center">
                        <label class="pos-rel">
                            <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->pengajuan_lembur_id.'"/>
                            <span class="lbl"></span>
                        </label>
                      </div>';
            $row[] = '';
            $row[] = $row_list->pengajuan_lembur_id;
            $row[] = '<div class="center">
                        <div class="btn-group">
                        <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">
                            <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-inverse">
                            <li>'.$this->authuser->show_button('kepegawaian/Kepeg_pengajuan_lembur','R',$row_list->pengajuan_lembur_id,6).'</li>
                            <li>'.$this->authuser->show_button('kepegawaian/Kepeg_pengajuan_lembur','U',$row_list->pengajuan_lembur_id,6).'</li>
                            <li>'.$this->authuser->show_button('kepegawaian/Kepeg_pengajuan_lembur','D',$row_list->pengajuan_lembur_id,6).'</li>
                        </ul>
                        </div>
                    </div>';
            
            $status = (!empty($row_list->acc_by_name)) ? '<span class="label label-xs label-warning">Menunggu persetujuan</span><br>'.$row_list->acc_by_level.' '.$row_list->acc_by_unit.' ('.$row_list->acc_by_name.')' : 'Lembur anda telah disetujui';
            $row[] = $row_list->kode;
            $row[] = $this->tanggal->formatDatedmY($row_list->tgl_pengajuan_lembur);
            $row[] = '<b>NIP : '.$row_list->kepeg_nip.'</b><br>'.$row_list->nama_pegawai;
            $row[] = $row_list->nama_level.'<br>'.$row_list->nama_unit;
            $row[] = $this->tanggal->getBulan($row_list->periode_lembur_bln);
            $row[] = $row_list->keterangan;
            $row[] = $status;
            // $status_aktif = ($row_list->kepeg_status_aktif == 'Y') ? '<span class="label label-sm label-success">Active</span>' : '<span class="label label-sm label-danger">Not active</span>';
            // $row[] = '<div class="center">'.$status_aktif.'</div>';
            $row[] = '<div class="center">
                        <a href="#" class="label label-xs label-success" onclick="getMenu('."'kepegawaian/Kepeg_pengajuan_lembur/form_rincian_lembur/".$row_list->pengajuan_lembur_id."'".')"><i class="fa fa-file"></i> Rincian Lembur</a>
            </div>';
                   
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Kepeg_pengajuan_lembur->count_all(),
                        "recordsFiltered" => $this->Kepeg_pengajuan_lembur->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function rincian_lembur_dt()
    {
        /*get data from model*/
        $list = $this->Kepeg_pengajuan_lembur->get_datatables_rincian_lembur();
        
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center">'.$no.'</div>';
            $row[] = $row_list->kepeg_unit_nama;
            $row[] = $this->tanggal->formatDatedmY($row_list->tgl_lembur);
            $row[] = $this->tanggal->formatTime($row_list->dari_jam);
            $row[] = $this->tanggal->formatTime($row_list->sd_jam);
            $row[] = $row_list->jml_jam_lembur;
            $row[] = $row_list->deskripsi_pekerjaan;
            $status = ($row_list->status_lembur == 'Y') ? '<i class="fa fa-check-circle bigger-120 green">Disetujui</span>' : '<i class="fa fa-times-circle bigger-120 red"></i>';
            $row[] = '<div class="center">'.$status.'</div>';
            $row[] = '<div class="center">
                        <a href="#" class="btn btn-xs btn-success" onclick="get_dt_update('.$row_list->lembur_dtl_id.')"><i class="fa fa-edit"></i></a> - 
                        <a href="#" class="btn btn-xs btn-danger" onclick="delete_data('.$row_list->lembur_dtl_id.')"><i class="fa fa-times-circle "></i></a>
            </div>';
                   
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => count($list),
                        "recordsFiltered" => count($list),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function process()
    {
        
        $this->load->library('form_validation');
        $val = $this->form_validation;
        $val->set_rules('nama_pegawai', 'nama_pegawai', 'trim|required');
        $val->set_rules('kepeg_id', 'kepeg_id', 'trim|required');
        $val->set_rules('periode_lembur_bln', 'periode_lembur_bln', 'trim|required');
        $val->set_rules('tahun', 'Tahun', 'trim|required');
        $val->set_rules('keterangan', 'keterangan', 'trim|required');

        $val->set_message('required', "Silahkan isi field \"%s\"");

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
                'tgl_pengajuan_lembur' => $this->regex->_genRegex(date('Y-m-d'), 'RGXQSL'),
                'kepeg_id' => $this->regex->_genRegex($val->set_value('kepeg_id'), 'RGXQSL'),
                'periode_lembur_bln' => $this->regex->_genRegex($val->set_value('periode_lembur_bln'), 'RGXQSL'),
                'keterangan' => $this->regex->_genRegex($val->set_value('keterangan'), 'RGXQSL'),
                'tahun' => $this->regex->_genRegex($val->set_value('tahun'), 'RGXQSL'),
            );

            // get detail pegawai
            $dt_pegawai = $this->db->get_where('view_dt_pegawai', array('kepeg_id' => $val->set_value('kepeg_id') ) )->row();
            // echo '<pre>';print_r($dt_pegawai);die;

            if(!empty($dt_pegawai)){
                $acc_level = $dt_pegawai->kepeg_level - 1;
                $dataexc['level_pegawai'] = $dt_pegawai->kepeg_level;
                $dataexc['unit_bagian'] = $dt_pegawai->kepeg_unit;
            }

            if($id==0){
                // kode lembur
                $kode_lembur = $this->master->get_kode_lembur($dataexc['kepeg_id']);

                $dataexc['kode'] = $kode_lembur;
                $dataexc['created_date'] = date('Y-m-d H:i:s');
                $dataexc['created_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                
                if(empty($dt_pegawai->kepeg_unit)){
                    echo json_encode(array('status' => 301, 'message' => 'Data kepegawaian anda belum di perbaharui'));
                    exit;
                }
                // save data pegawai
                $newId = $this->Kepeg_pengajuan_lembur->save('kepeg_pengajuan_lembur', $dataexc);
                // log acc
                $spv = $this->Kepeg_pengajuan_lembur->get_spv($dt_pegawai->kepeg_unit, $acc_level);
                $dataacc = array(
                    'acc_by_level' => $spv->nama_level,
                    'acc_by_unit' => $spv->nama_unit,
                    'acc_by_name' => $spv->nama_pegawai,
                    'acc_by_kepeg_id' => $spv->kepeg_id,
                    'ref_id' => $newId,
                    'type' => 'pengajuan_lembur',
                );
                $this->Kepeg_pengajuan_lembur->save('kepeg_log_acc_pengajuan', $dataacc);

            }else{
                $dataexc['updated_date'] = date('Y-m-d H:i:s');
                $dataexc['updated_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                /*update record*/
                $this->Kepeg_pengajuan_lembur->update('kepeg_pengajuan_lembur', array('pengajuan_lembur_id' => $id), $dataexc);
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

    public function process_rincian_lembur()
    {
        // echo '<pre>';print_r($_POST);die;
        $this->load->library('form_validation');
        $val = $this->form_validation;
        $val->set_rules('pengajuan_lembur_id', 'pengajuan_lembur_id', 'trim|required');
        $val->set_rules('unit_tugas', 'Ditugaskan di Unit/Bagian', 'trim|required');
        $val->set_rules('dari_jam', 'Dari Jam', 'trim|required');
        $val->set_rules('sd_jam', 'sd Jam', 'trim|required');
        $val->set_rules('tgl_lembur', 'Tgl Lembur', 'trim|required');
        $val->set_rules('deskripsi_pekerjaan', 'Deskripsi Pekerjaan', 'trim|required');

        $val->set_message('required', "Silahkan isi field \"%s\"");

        if ($val->run() == FALSE)
        {
            $val->set_error_delimiters('<div style="color:white">', '</div>');
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            $this->db->trans_begin();
            $id = ($this->input->post('lembur_dtl_id'))?$this->regex->_genRegex($this->input->post('lembur_dtl_id'),'RGXINT'):0;

            $dataexc = array(
                'pengajuan_lembur_id' => $this->regex->_genRegex($val->set_value('pengajuan_lembur_id'), 'RGXQSL'),
                'unit_tugas' => $this->regex->_genRegex($val->set_value('unit_tugas'), 'RGXQSL'),
                'dari_jam' => $this->regex->_genRegex($val->set_value('dari_jam'), 'RGXQSL'),
                'sd_jam' => $this->regex->_genRegex($val->set_value('sd_jam'), 'RGXQSL'),
                'tgl_lembur' => $this->regex->_genRegex($val->set_value('tgl_lembur'), 'RGXQSL'),
                'deskripsi_pekerjaan' => $this->regex->_genRegex($val->set_value('deskripsi_pekerjaan'), 'RGXQSL'),
            );

           // perhitungan jml jam lembur
            $start_time = strtotime($val->set_value('tgl_lembur').' '.$val->set_value('dari_jam'));
            $end_time = strtotime($val->set_value('tgl_lembur').' '.$val->set_value('sd_jam'));
            // selisih
            $diff = $end_time - $start_time;
            $jam = floor($diff / (60 * 60));
            $diff_menit = $diff - $jam * (60 * 60);
            $menit = floor($diff_menit/60);

            $dataexc['jml_jam_lembur'] = $jam.'h '.$menit.'m';
            $dataexc['pembulatan_jam_lembur'] = $jam;
            // echo '<pre>';print_r($dataexc);die;

            if($id==0){
                $dataexc['created_date'] = date('Y-m-d H:i:s');
                $dataexc['created_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                // save data lembur
                $newId = $this->Kepeg_pengajuan_lembur->save('kepeg_pengajuan_lembur_rincian', $dataexc);

            }else{
                $dataexc['updated_date'] = date('Y-m-d H:i:s');
                $dataexc['updated_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                /*update record*/
                $this->Kepeg_pengajuan_lembur->update('kepeg_pengajuan_lembur_rincian', array('lembur_dtl_id' => $id), $dataexc);
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

    public function delete()
    {
        $id=$this->input->post('ID')?$this->regex->_genRegex($this->input->post('ID',TRUE),'RGXQSL'):null;
        $toArray = explode(',',$id);
        if($id!=null){
            if($this->Kepeg_pengajuan_lembur->delete_by_id($toArray)){
                echo json_encode(array('status' => 200, 'message' => 'Proses Hapus Data Berhasil Dilakukan'));

            }else{
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Hapus Data Gagal Dilakukan'));
            }
        }else{
            echo json_encode(array('status' => 301, 'message' => 'Tidak ada item yang dipilih'));
        }
        
    }

    public function delete_lembur()
    {
        $id=$this->input->post('ID')?$this->regex->_genRegex($this->input->post('ID',TRUE),'RGXQSL'):null;
        
        if($this->Kepeg_pengajuan_lembur->delete_lembur_by_id($id)){
            echo json_encode(array('status' => 200, 'message' => 'Proses Hapus Data Berhasil Dilakukan'));

        }else{
            echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Hapus Data Gagal Dilakukan'));
        }
        
    }


}


/* End of file example.php */
/* Location: ./application/modules/example/controllers/example.php */
