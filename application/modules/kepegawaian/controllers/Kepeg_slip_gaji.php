<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Kepeg_slip_gaji extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'kepegawaian/Kepeg_slip_gaji');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('Kepeg_slip_gaji_model', 'Kepeg_slip_gaji');
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
        $this->load->view('Kepeg_slip_gaji/index', $data);
    }

    public function form($id='')
    {
        /*if id is not null then will show form edit*/
        /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Edit '.strtolower($this->title).'', 'Kepeg_slip_gaji/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*get value by id*/
        $data['value'] = $this->Kepeg_slip_gaji->get_by_id($id);
        /*initialize flag for form*/
        $data['flag'] = "update";
        // atasan
        $data['acc_flow'] = $this->master->kepeg_acc_flow($data['value']->kepeg_id, $id, 'pengajuan_cuti');
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        // echo '<pre>'; print_r($data);die;
        /*load form view*/
        $this->load->view('Kepeg_slip_gaji/form', $data);
    }
    
    /*function for view data only*/
    public function show($id)
    {
        /*breadcrumbs for view*/
        $this->breadcrumbs->push('View '.strtolower($this->title).'', 'Kepeg_slip_gaji/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*define data variabel*/
        $data['value'] = $this->Kepeg_slip_gaji->get_by_id($id);
        $data['title'] = $this->title;
        $data['flag'] = "read";
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Kepeg_slip_gaji/form', $data);
    }

    public function show_detail( $id )
    {   
        $fields = $this->master->list_fields( 'kepeg_pengajuan_cuti' );
        // print_r($fields);die;
        $data = $this->Kepeg_slip_gaji->get_by_id($id);
        $html = $this->master->show_detail_row_table( $fields, $data );      

        echo json_encode( array('html' => $html) );
    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->Kepeg_slip_gaji->get_datatables();
        
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
            
            $status = (!empty($row_list->acc_by_name)) ? '<span class="label label-xs label-warning">Menunggu persetujuan</span><br>'.$row_list->acc_by_level.' '.$row_list->acc_by_unit.' ('.$row_list->acc_by_name.')' : 'Cuti telah disetujui';
            $row[] = '<b>NIP : '.$row_list->kepeg_nip.'</b><br>'.$row_list->nama_pegawai;
            $row[] = $row_list->nama_level.'<br>'.$row_list->nama_unit;
            $row[] = $this->tanggal->formatDatedmY($row_list->tgl_pengajuan_cuti);
            $row[] = $this->tanggal->formatDatedmY($row_list->cuti_dari_tgl).' s/d '.$this->tanggal->formatDatedmY($row_list->cuti_sd_tgl);
            $row[] = '<b>'.$row_list->jenis_cuti.'</b><br>'.$row_list->alasan_cuti;
            $row[] = $status;
            $row[] = '<div class="center">
                        <a href="#" style="width: 100% !important" class="label label-xs label-success" onclick="getMenu('."'kepegawaian/Kepeg_slip_gaji/form/".$row_list->pengajuan_cuti_id."'".')"><i class="fa fa-pencil"></i> Persetujuan</a>
                    </div>';
                   
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Kepeg_slip_gaji->count_all(),
                        "recordsFiltered" => $this->Kepeg_slip_gaji->count_filtered(),
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

            $this->Kepeg_slip_gaji->update('kepeg_log_acc_pengajuan', array('log_acc_id' => $id), $dataexc );
            // get detail atasan
            $dt_pegawai = $this->db->get_where('view_dt_pegawai', array('kepeg_id' => $val->set_value('acc_by_kepeg_id') ) )->row();
             if(!empty($dt_pegawai)){
                $acc_level = $dt_pegawai->kepeg_level - 1;
                // log acc
                $spv = $this->Kepeg_slip_gaji->get_spv($dt_pegawai->kepeg_unit, $acc_level);
                if(!empty($spv)){
                    $dataacc = array(
                        'acc_by_level' => $spv->nama_level,
                        'acc_by_unit' => $spv->nama_unit,
                        'acc_by_name' => $spv->nama_pegawai,
                        'acc_by_kepeg_id' => $spv->kepeg_id,
                        'ref_id' => $this->input->post('pengajuan_cuti_id'),
                        'type' => 'pengajuan_cuti',
                    );
                    $this->Kepeg_slip_gaji->save('kepeg_log_acc_pengajuan', $dataacc);
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
            if($this->Kepeg_slip_gaji->delete_by_id($toArray)){
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
