<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Kepeg_pengajuan_cuti extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'kepegawaian/Kepeg_pengajuan_cuti');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('Kepeg_pengajuan_cuti_model', 'Kepeg_pengajuan_cuti');
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
        $this->load->view('Kepeg_pengajuan_cuti/index', $data);
    }

    public function form($id='')
    {
        /*if id is not null then will show form edit*/
        if( $id != '' ){
            /*breadcrumbs for edit*/
            $this->breadcrumbs->push('Edit '.strtolower($this->title).'', 'Kepeg_pengajuan_cuti/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
            /*get value by id*/
            $data['value'] = $this->Kepeg_pengajuan_cuti->get_by_id($id);
            /*initialize flag for form*/
            $data['flag'] = "update";
        }else{
            /*breadcrumbs for create or add row*/
            $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Kepeg_pengajuan_cuti/'.strtolower(get_class($this)).'/form');
            /*initialize flag for form add*/
            $data['flag'] = "create";
        }
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Kepeg_pengajuan_cuti/form', $data);
    }
    
    /*function for view data only*/
    public function show($id)
    {
        /*breadcrumbs for view*/
        $this->breadcrumbs->push('View '.strtolower($this->title).'', 'Kepeg_pengajuan_cuti/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*define data variabel*/
        $data['value'] = $this->Kepeg_pengajuan_cuti->get_by_id($id);
        $data['title'] = $this->title;
        $data['flag'] = "read";
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Kepeg_pengajuan_cuti/form', $data);
    }

    public function show_detail( $id )
    {   
        $fields = $this->master->list_fields( 'kepeg_pengajuan_cuti' );
        // print_r($fields);die;
        $data = $this->Kepeg_pengajuan_cuti->get_by_id($id);
        $html = $this->master->show_detail_row_table( $fields, $data );      

        echo json_encode( array('html' => $html) );
    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->Kepeg_pengajuan_cuti->get_datatables();
        
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center">
                        <label class="pos-rel">
                            <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->pengajuan_cuti_id.'"/>
                            <span class="lbl"></span>
                        </label>
                      </div>';
            $row[] = '';
            $row[] = $row_list->pengajuan_cuti_id;
            $row[] = '<div class="center">
                        <div class="btn-group">
                        <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">
                            <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-inverse">
                            <li>'.$this->authuser->show_button('kepegawaian/Kepeg_pengajuan_cuti','R',$row_list->pengajuan_cuti_id,6).'</li>
                            <li>'.$this->authuser->show_button('kepegawaian/Kepeg_pengajuan_cuti','U',$row_list->pengajuan_cuti_id,6).'</li>
                            <li>'.$this->authuser->show_button('kepegawaian/Kepeg_pengajuan_cuti','D',$row_list->pengajuan_cuti_id,6).'</li>
                        </ul>
                        </div>
                    </div>';
            
            $status = (!empty($row_list->acc_by_name)) ? '<span class="label label-xs label-warning">Menunggu persetujuan</span><br>'.$row_list->acc_by_level.' '.$row_list->acc_by_unit.' ('.$row_list->acc_by_name.')' : 'Cuti anda telah disetujui';
            $row[] = $row_list->kode.'<br>'.$this->tanggal->formatDatedmY($row_list->tgl_pengajuan_cuti);
            $row[] = '<b>NIP : '.$row_list->kepeg_nip.'</b><br>'.$row_list->nama_pegawai;
            $row[] = $row_list->nama_level.'<br>'.$row_list->nama_unit;
            $row[] = $this->tanggal->formatDatedmY($row_list->cuti_dari_tgl).' s/d '.$this->tanggal->formatDatedmY($row_list->cuti_sd_tgl);
            $row[] = '<b>'.$row_list->jenis_cuti.'</b><br>'.$row_list->alasan_cuti;
            $row[] = $status;
            // $status_aktif = ($row_list->kepeg_status_aktif == 'Y') ? '<span class="label label-sm label-success">Active</span>' : '<span class="label label-sm label-danger">Not active</span>';
            // $row[] = '<div class="center">'.$status_aktif.'</div>';
            // $row[] = '<div class="center">
            //             <a href="#" style="width: 100% !important" class="label label-xs label-success" onclick="getMenu('."'kepegawaian/Kepeg_pengajuan_cuti/form_jabatan/".$row_list->kepeg_pengajuan_cuti."'".')"><i class="fa fa-pencil"></i> Update Kepegawaian</a>
            // </div>';
                   
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Kepeg_pengajuan_cuti->count_all(),
                        "recordsFiltered" => $this->Kepeg_pengajuan_cuti->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function process()
    {
        // echo '<pre>';print_r($_POST);die;
        $this->load->library('form_validation');
        $val = $this->form_validation;
        $val->set_rules('nama_pegawai', 'nama_pegawai', 'trim|required');
        $val->set_rules('kepeg_id', 'kepeg_id', 'trim|required');
        $val->set_rules('cuti_dari_tgl', 'cuti_dari_tgl', 'trim|required');
        $val->set_rules('cuti_sd_tgl', 'cuti_sd_tgl', 'trim|required');
        $val->set_rules('jenis_cuti', 'jenis_cuti', 'trim|required');
        $val->set_rules('alasan_cuti', 'alasan_cuti', 'trim|required');

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
                'tgl_pengajuan_cuti' => $this->regex->_genRegex(date('Y-m-d'), 'RGXQSL'),
                'kepeg_id' => $this->regex->_genRegex($val->set_value('kepeg_id'), 'RGXQSL'),
                'cuti_dari_tgl' => $this->regex->_genRegex($val->set_value('cuti_dari_tgl'), 'RGXQSL'),
                'cuti_sd_tgl' => $this->regex->_genRegex($val->set_value('cuti_sd_tgl'), 'RGXQSL'),
                'jenis_cuti' => $this->regex->_genRegex($val->set_value('jenis_cuti'), 'RGXQSL'),
                'alasan_cuti' => $this->regex->_genRegex($val->set_value('alasan_cuti'), 'RGXQSL'),
            );

            // get detail pegawai
            $dt_pegawai = $this->db->get_where('view_dt_pegawai', array('kepeg_id' => $val->set_value('kepeg_id') ) )->row();
            if(!empty($dt_pegawai)){
                $acc_level = $dt_pegawai->kepeg_level - 1;
                $dataexc['level_pegawai'] = $dt_pegawai->kepeg_level;
                $dataexc['unit_bagian'] = $dt_pegawai->kepeg_unit;
            }
            

            if($id==0){
                // kode cuti
                $kode_cuti = $this->master->get_kode_cuti($dataexc['kepeg_id']);

                $dataexc['kode'] = $kode_cuti;
                $dataexc['created_date'] = date('Y-m-d H:i:s');
                $dataexc['created_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                // save data pegawai
                $newId = $this->Kepeg_pengajuan_cuti->save('kepeg_pengajuan_cuti', $dataexc);
                // log acc
                $spv = $this->Kepeg_pengajuan_cuti->get_spv($dt_pegawai->kepeg_unit, $acc_level);
                $dataacc = array(
                    'acc_by_level' => $spv->nama_level,
                    'acc_by_unit' => $spv->nama_unit,
                    'acc_by_name' => $spv->nama_pegawai,
                    'acc_by_kepeg_id' => $spv->kepeg_id,
                    'ref_id' => $newId,
                    'type' => 'pengajuan_cuti',
                );
                $this->Kepeg_pengajuan_cuti->save('kepeg_log_acc_pengajuan', $dataacc);

            }else{
                $dataexc['updated_date'] = date('Y-m-d H:i:s');
                $dataexc['updated_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                /*update record*/
                $this->Kepeg_pengajuan_cuti->update('kepeg_pengajuan_cuti', array('pengajuan_cuti_id' => $id), $dataexc);
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
            if($this->Kepeg_pengajuan_cuti->delete_by_id($toArray)){
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
