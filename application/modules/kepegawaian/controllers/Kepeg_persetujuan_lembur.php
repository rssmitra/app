<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Kepeg_persetujuan_lembur extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'kepegawaian/Kepeg_persetujuan_lembur');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('Kepeg_persetujuan_lembur_model', 'Kepeg_persetujuan_lembur');
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
        $this->load->view('Kepeg_persetujuan_lembur/index', $data);
    }

    public function form($id='')
    {
        /*if id is not null then will show form edit*/
        if( $id != '' ){
            /*breadcrumbs for edit*/
            $this->breadcrumbs->push('Edit '.strtolower($this->title).'', 'Kepeg_persetujuan_lembur/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
            /*get value by id*/
            $data['value'] = $this->Kepeg_persetujuan_lembur->get_by_id($id);
            /*initialize flag for form*/
            $data['flag'] = "update";
        }else{
            /*breadcrumbs for create or add row*/
            $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Kepeg_persetujuan_lembur/'.strtolower(get_class($this)).'/form');
            /*initialize flag for form add*/
            $data['flag'] = "create";
        }
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Kepeg_persetujuan_lembur/form', $data);
    }
    
    /*function for view data only*/
    public function form_rincian_lembur($id)
    {
        /*breadcrumbs for view*/
        $this->breadcrumbs->push('View '.strtolower($this->title).'', 'Kepeg_persetujuan_lembur/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*define data variabel*/
        $data['value'] = $this->Kepeg_persetujuan_lembur->get_by_id($id);
        // atasan
        $data['acc_flow'] = $this->master->kepeg_acc_flow($data['value']->kepeg_id, $id, 'pengajuan_lembur');
        $data['title'] = $this->title;
        $data['flag'] = "read";
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        // echo '<pre>';print_r($data);
        /*load form view*/
        $this->load->view('Kepeg_persetujuan_lembur/form_rincian_lembur', $data);
    }

    public function show($id)
    {
        /*breadcrumbs for view*/
        $this->breadcrumbs->push('View '.strtolower($this->title).'', 'Kepeg_persetujuan_lembur/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*define data variabel*/
        $data['value'] = $this->Kepeg_persetujuan_lembur->get_by_id($id);
        $data['title'] = $this->title;
        $data['flag'] = "read";
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Kepeg_persetujuan_lembur/form', $data);
    }

    public function show_detail( $id )
    {   
        $fields = $this->master->list_fields( 'kepeg_pengajuan_lembur' );
        // print_r($fields);die;
        $data = $this->Kepeg_persetujuan_lembur->get_by_id($id);
        $html = $this->master->show_detail_row_table( $fields, $data );      

        echo json_encode( array('html' => $html) );
    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->Kepeg_persetujuan_lembur->get_datatables();
        
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
            $status = (!empty($row_list->acc_by_name)) ? '<span class="label label-xs label-warning">Menunggu persetujuan</span><br>'.$row_list->acc_by_level.' - '.$row_list->acc_by_unit.' ('.$row_list->acc_by_name.')' : 'Cuti anda telah disetujui';
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
                        <a href="#" class="label label-xs label-success" onclick="getMenu('."'kepegawaian/Kepeg_persetujuan_lembur/form_rincian_lembur/".$row_list->pengajuan_lembur_id."'".')"><i class="fa fa-edit"></i> Persetujuan </a>
            </div>';
                   
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Kepeg_persetujuan_lembur->count_all(),
                        "recordsFiltered" => $this->Kepeg_persetujuan_lembur->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function rincian_lembur_dt()
    {
        /*get data from model*/
        $list = $this->Kepeg_persetujuan_lembur->get_datatables_rincian_lembur();
        
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $disabled = ($row_list->is_active == 'N') ? 'disabled' : '';
            $row[] = '<div class="center">
                        <label class="pos-rel">
                            <input type="checkbox" class="ace" name="selected_id['.$row_list->lembur_dtl_id.']" value="'.$row_list->lembur_dtl_id.'" '.$disabled.'/>
                            <span class="lbl"></span>
                        </label>
                      </div>';
            $row[] = '<div class="center">'.$no.'</div>';
            $row[] = $this->tanggal->formatDatedmY($row_list->tgl_lembur);
            $row[] = $this->tanggal->formatTime($row_list->dari_jam);
            $row[] = $this->tanggal->formatTime($row_list->sd_jam);
            $row[] = $row_list->jml_jam_lembur;
            $row[] = $row_list->deskripsi_pekerjaan.' - '.$row_list->kepeg_unit_nama;
            $status = ($row_list->is_active == 'Y') ? '<i class="fa fa-check-circle bigger-120 green"></i>' : '<i class="fa fa-times-circle bigger-120 red"></i>';
            $acc_by = (!empty($row_list->acc_by)) ? $row_list->acc_by : '' ;
            $row[] = '<div class="center">'.$status.'<br>'.$acc_by.'</div>';
                   
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
        // echo '<pre>';print_r($_POST);die;
        $this->load->library('form_validation');
        $val = $this->form_validation;
        $val->set_rules('acc_by_kepeg_id', 'kepeg_id', 'trim|required');
        $val->set_rules('acc_status', 'Persetujuan', 'trim|required');
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
                'acc_date' => date('Y-m-d'),
                'acc_status' => $this->input->post('acc_status'),
            );

            $this->Kepeg_persetujuan_lembur->update('kepeg_log_acc_pengajuan', array('log_acc_id' => $id), $dataexc );
            // get detail atasan
            $dt_pegawai = $this->db->get_where('view_dt_pegawai', array('kepeg_id' => $val->set_value('acc_by_kepeg_id') ) )->row();
             if(!empty($dt_pegawai)){
                $acc_level = $dt_pegawai->kepeg_level - 1;
                // log acc
                $spv = $this->Kepeg_persetujuan_lembur->get_spv($dt_pegawai->kepeg_unit, $acc_level);
                if(!empty($spv)){
                    $dataacc = array(
                        'acc_by_level' => $spv->nama_level,
                        'acc_by_unit' => $spv->nama_unit,
                        'acc_by_name' => $spv->nama_pegawai,
                        'acc_by_kepeg_id' => $spv->kepeg_id,
                        'ref_id' => $this->input->post('pengajuan_lembur_id'),
                        'type' => 'pengajuan_lembur',
                    );
                    $this->Kepeg_persetujuan_lembur->save('kepeg_log_acc_pengajuan', $dataacc);
                }
            }

            $pengajuan_lembur = $this->db->get_where('kepeg_pengajuan_lembur_rincian', array('pengajuan_lembur_id' => $this->input->post('pengajuan_lembur_id')) )->result();
            // acc lembur by atasan
            foreach ($pengajuan_lembur as $key => $value) {
                $selected_id = isset($_POST['selected_id'][$value->lembur_dtl_id]) ? $_POST['selected_id'][$value->lembur_dtl_id] : 0 ;
                $is_acc = ($_POST['acc_status'] == 'Y') ? ($value->lembur_dtl_id == $selected_id) ? 'Y' : 'N' : 'N';
                if($value->is_active != 'N'){
                    $dataupdate = array(
                        'is_active' => $is_acc,
                        'acc_by' => $_POST['nama_pegawai'],
                        'updated_date' => date('Y-m-d H:i:s'),
                        'updated_by' => json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')))
                    );
                    $this->db->update('kepeg_pengajuan_lembur_rincian', $dataupdate, array('lembur_dtl_id' => $value->lembur_dtl_id) );
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



}


/* End of file example.php */
/* Location: ./application/modules/example/controllers/example.php */
