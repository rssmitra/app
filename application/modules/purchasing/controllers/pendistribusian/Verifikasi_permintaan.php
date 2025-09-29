<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Verifikasi_permintaan extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'purchasing/pendistribusian/Verifikasi_permintaan');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('purchasing/pendistribusian/Verifikasi_permintaan_model', 'Verifikasi_permintaan');
        $this->load->model('purchasing/pendistribusian/Permintaan_stok_unit_model', 'Permintaan_stok_unit');
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
            'breadcrumbs' => $this->breadcrumbs->show(),
            'flag' => $_GET['flag'],
        );
        /*load view index*/
        $this->load->view('pendistribusian/Verifikasi_permintaan/index', $data);
    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->Verifikasi_permintaan->get_datatables();
        
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center">'.$no.'</div>';
            $row[] = '';
            $row[] = $row_list->id_tc_permintaan_inst;
            $row[] = '<div class="center">'.$row_list->id_tc_permintaan_inst.'</div>';
            // $row[] = $row_list->nomor_permintaan;

            // Determine label based on flag parameter
            $flag = isset($_GET['flag']) ? $_GET['flag'] : '';
            if ($flag == 'medis') {
                $label_flag = '<span style="background: green; color: white; padding: 4px; border-radius: 5px">Medis</span>';
            } elseif ($flag == 'non_medis') {
                $label_flag = '<span style="background: blue; color: white; padding: 4px; border-radius: 5px">Non Medis</span>';
            } else {
                $label_flag = '';
            }

            $row[] = $this->tanggal->formatDateDmy($row_list->tgl_permintaan).' &nbsp; '.$label_flag;
            $row[] = '<div class="left">'.ucwords($row_list->bagian_minta).'</div>';
            $row[] = '<div class="left">'.ucfirst($row_list->nama_user_input).'</div>';
            $jenis_permintaan = ($row_list->jenis_permintaan==0)?'Rutin':'Cito';
            $row[] = '<div class="center">'.ucfirst($jenis_permintaan).'</div>';
            $row[] = '<div class="left">'.$row_list->catatan.'</div>';
            $tgl_acc = ($row_list->tgl_acc ==null) ? '<i class="fa fa-exclamation-triangle bigger-150 orange"></i>' : $this->tanggal->formatDateDmy($row_list->tgl_acc);
            $acc_by = ($row_list->acc_by ==null) ? '<i class="fa fa-exclamation-triangle bigger-150 orange"></i>' : $row_list->acc_by;
            $row[] = '<div class="center">'.$tgl_acc.'</div>';
            $row[] = '<div class="center">'.$acc_by.'</div>';

            if($row_list->status_acc == null)
            {
                $style_status = '<span style="width: 100% !important" class="label label-warning"><i class="fa fa-exclamation-triangle"></i> Belum diverifikasi</span>';
            }else{
                $style_status = ($row_list->status_acc == 1) ? '<span class="label label-success" style="width: 100% !important"><i class="fa fa-check"></i> Disetujui</span>' :'<span style="width: 100% !important" class="label label-danger"><i class="fa fa-times"></i> Tidak disetujui</span>';
            }

            $row[] = '<div class="center">'.$style_status.'</div>';
                  
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Verifikasi_permintaan->count_all(),
                        "recordsFiltered" => $this->Verifikasi_permintaan->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function prosess_approval()
    {
        // print_r($_POST);die;
        $this->load->library('form_validation');
        $val = $this->form_validation;

        if(isset($_POST['selected']) && count($_POST['selected'])==0){
            echo json_encode(array('status' => 301, 'message' => 'Silahkan pilih item yang akan di proses'));
            exit;
        }

        $val->set_rules('id_tc_permintaan_inst', 'ID', 'trim|required');
        $val->set_rules('catatan', 'Catatan', 'trim');
        
        $val->set_message('required', "Silahkan isi field \"%s\"");

        if ($val->run() == FALSE)
        {
            $val->set_error_delimiters('<div style="color:white">', '</div>');
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            $this->db->trans_begin();

            // nama bagian
            $table = ($_POST['flag']=='medis')?'tc_permintaan_inst':'tc_permintaan_inst_nm';
            
            // echo "<pre>";print_r($cart_data);die;
            $dataexc = array(
                'no_acc' => 'ACC/'.date('dMY').'/'.$_POST['id_tc_permintaan_inst'],
                'tgl_acc' => date('Y-m-d'),
                'acc_by' =>  $this->session->userdata('user')->fullname,
                'acc_note' =>  $_POST['catatan_verifikator_m_1'],
                'status_acc' =>  $_POST['flag_approval'],
                'updated_date' => date('Y-m-d H:i:s'),
                'updated_by' => json_encode(array('user_id' => $this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL'))),
            );

            $dataexc['send_to_verify'] = ($_POST['flag_approval'] == 0) ? 0 : 1; // kembalikan ke user

            $newId = $this->Verifikasi_permintaan->update($table, ['id_tc_permintaan_inst' => $_POST['id_tc_permintaan_inst']], $dataexc);
            /*save logs*/
            $this->logs->save($table, $newId, 'insert new record on '.$this->title.' module', json_encode($dataexc),'id_tc_permintaan_inst');
            
            foreach( $_POST['list_brg'] as $id_tc_permintaan_inst_det ){
                // insert detail barang
                $dt_detail= array(
                    'status_verif' => isset($_POST['selected'][$id_tc_permintaan_inst_det]) ? $this->regex->_genRegex(1,'RGXINT') : 0,
                    'jml_acc_atasan' => $this->regex->_genRegex($_POST['jml_acc'][$id_tc_permintaan_inst_det],'RGXINT'),
                    'keterangan_verif' => $this->regex->_genRegex($_POST['keterangan_verif'][$id_tc_permintaan_inst_det],'RGXQSL'),
                    'created_date' => date('Y-m-d H:i:s'),
                    'created_by' => json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL'))),
                );

                $this->Verifikasi_permintaan->update($table.'_det', ['id_tc_permintaan_inst_det' => $id_tc_permintaan_inst_det], $dt_detail);

                $this->db->trans_commit();
            }

            // echo "<pre>"; print_r($dt_detail);die;

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'flag' => $_POST['flag'], 'id' => $newId));
            }
        }
    }

    public function get_detail($id){
        $flag = isset($_GET['flag'])?$_GET['flag']:'medis';
        $result = $this->Permintaan_stok_unit->get_brg_permintaan($flag, $id);
        // echo "<pre>";print_r($result);die;

        $data = array(
            'dt_detail_brg' => $result,
            'flag' => $flag,
            'id' => $id,
        );
        if($result[0]->tgl_pengiriman == null){
            $temp_view = $this->load->view('pendistribusian/Verifikasi_permintaan/detail_table_view', $data, true);
        }else{
            $temp_view = $this->load->view('pendistribusian/Permintaan_stok_unit/detail_table_view', $data, true);
        }
        echo json_encode( array('html' => $temp_view) );
    }

    public function find_data()
    {   
        $output = array( "data" => http_build_query($_POST) . "\n" );
        echo json_encode($output);
    }

}


/* End of file example.php */
/* Location: ./application/modules/example/controllers/example.php */
