<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Adm_kasir extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'adm_pasien/loket_kasir/Adm_kasir');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('adm_pasien/loket_kasir/Adm_kasir_model', 'Adm_kasir');
        $this->load->model('adm_pasien/Adm_pasien_model', 'Adm_pasien');
        /*enable profiler*/
        $this->output->enable_profiler(false);
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'Title';

    }

    public function index() { 
        //echo '<pre>';print_r($this->session->all_userdata());
        /*define variable data*/
        $data = array(
            'title' => 'Administrasi Pasien',
            'breadcrumbs' => $this->breadcrumbs->show(),
            'flag' => $_GET['flag'],
        );
        /*show datatables*/
        $data['dataTables'] = $this->load->view('loket_kasir/Adm_kasir/temp_trans_pasien', $data, true);
        /*load view index*/
        $this->load->view('loket_kasir/Adm_kasir/index', $data);
    }

    public function getTransPasien()
    {
        /*registrasi terakhir*/
        if(isset($_POST['keyword']) AND $_POST['keyword'] != ''){
            $last_reg = $this->db->select('a.no_registrasi, a.no_mr, a.tgl_jam_masuk, b.nama_bagian, c.nama_pegawai, d.nama_pasien, d.tgl_lhr, d.almt_ttp_pasien, d.jen_kelamin, d.tempat_lahir')
                 ->order_by('a.no_registrasi', 'DESC')
                 ->join('mt_bagian b', 'b.kode_bagian=a.kode_bagian_masuk','left')
                 ->join('mt_dokter_v c', 'c.kode_dokter=a.kode_dokter','left')
                 ->join('mt_master_pasien d', 'd.no_mr=a.no_mr','left')
                 ->where('a.status_batal is null')
                 ->where('(a.no_mr='.$_POST['keyword'].' or a.no_registrasi='.$_POST['keyword'].')')
                 ->limit(1)
                 ->get('tc_registrasi a')->row();
        }
        

        $data = array(
            'dt_pasien' => isset($last_reg)?$last_reg:array(),
            'flag' => $_POST['flag'],
            'search_by' => isset($_POST['search_by'])?$_POST['search_by']:'',
            'keyword' => isset($_POST['keyword'])?$_POST['keyword']:'',
            'is_with_date' => isset($_POST['is_with_date'])?$_POST['is_with_date']:0,
            'date' => isset($_POST['date'])?$_POST['date']:0,
            'month' => isset($_POST['month'])?$_POST['month']:0,
            'year' => isset($_POST['year'])?$_POST['year']:0,
        );

        $html = $this->load->view('loket_kasir/Adm_kasir/temp_trans_pasien', $data, true);

        echo json_encode(array('html' => $html));
                
    }

    public function find_data_trans_pasien()
    {
        /*get data from model*/
        $list = $this->Adm_pasien->get_datatables();
        
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center">
                        <label class="pos-rel">
                            <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->no_registrasi.'"/>
                            <span class="lbl"></span>
                        </label>
                      </div>';
            $row[] = '';
            $row[] = $row_list->no_registrasi;
            $row[] = '<a href="#" onclick="getMenu('."'billing/Billing/viewDetailBillingKasir/".$row_list->no_registrasi."/RJ"."'".')">'.$row_list->no_registrasi.'</div>';
            $row[] = $row_list->no_mr;
            $row[] = $row_list->nama_pasien;
            $row[] = ucwords($row_list->nama_bagian);
            if($_GET['flag']=='umum'){
                if($row_list->kode_perusahaan==120){
                    $row_cs = '<label class="label label-danger">'.$row_list->nama_perusahaan.'</label>';
                }else{
                    $row_cs = '<label class="label label-success">'.$row_list->nama_perusahaan.'</label>';
                }
            }else{
                if($row_list->kode_perusahaan!=120){
                    $row_cs = '<label class="label label-danger">'.$row_list->nama_perusahaan.'</label>';
                }else{
                    $row_cs = '<label class="label label-success">'.$row_list->nama_perusahaan.'</label>';
                }
            }
            $row[] = ($row_cs=='')?'UMUM':$row_cs;
            $row[] = $this->tanggal->formatDateTime($row_list->tgl_jam_masuk);
            /*status billing*/
            $status_billing = ($row_list->total_billing==0)? '<div class="center"><label class="label label-primary"><i class="fa fa-check"></i> Lunas</label></div>' :'<div class="pull-right">'.number_format($row_list->total_billing).',-</div>';
            $row[] = $status_billing;
                   
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Adm_pasien->count_all(),
                        "recordsFiltered" => $this->Adm_pasien->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function merge_data_registrasi(){
        $ex_arr = explode( ',' , $_POST['value']);
        $kode = $this->Adm_pasien->get_first_registrasi($ex_arr);
        $string = $this->Adm_pasien->merge_transaksi( $kode );
        return true;
    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->Adm_kasir->get_datatables();
        
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center">
                        <label class="pos-rel">
                            <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->no_registrasi.'"/>
                            <span class="lbl"></span>
                        </label>
                      </div>';
            $row[] = '';
            $row[] = $row_list->no_registrasi;
            $row[] = '<div class="center">'.$row_list->no_registrasi.'</div>';
            $row[] = $row_list->no_mr;
            $row[] = $row_list->nama_pasien;
            $row[] = ($row_list->nama_perusahaan)?$row_list->nama_perusahaan:'UMUM';
            $row[] = ucwords($row_list->nama_bagian);
            $row[] = $this->tanggal->formatDateTime($row_list->tgl_jam_masuk);
            $row[] = '<div class="pull-right">'.number_format($row_list->total).',-</div>';
                   
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Adm_kasir->count_all(),
                        "recordsFiltered" => $this->Adm_kasir->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }






















    












    public function form($id='')
    {
        $data['string'] = isset($_GET['flag'])?$_GET['flag']:'';
        /*if id is not null then will show form edit*/
        if( $id != '' ){
            /*breadcrumbs for edit*/
            $this->breadcrumbs->push('Edit '.strtolower($this->title).'', 'Adm_kasir/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
            /*get value by id*/
            $data['value'] = $this->Adm_kasir->get_by_id($id); //echo '<pre>'; print_r($data);die;
            /*initialize flag for form*/
            $data['flag'] = "update";
        }else{
            /*breadcrumbs for create or add row*/
            $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Adm_kasir/'.strtolower(get_class($this)).'/form');
            /*initialize flag for form add*/
            $data['flag'] = "create";
        }
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('loket_kasir/Adm_kasir/form', $data);
    }
    /*function for view data only*/
    public function show($id)
    {
        $data['string'] = isset($_GET['flag'])?$_GET['flag']:'';
        /*breadcrumbs for view*/
        $this->breadcrumbs->push('View '.strtolower($this->title).'', 'Adm_kasir/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*define data variabel*/
        //$data['value'] = $this->Adm_kasir->get_by_id($id);
        $data['title'] = $this->title;
        $data['flag'] = "read";
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('loket_kasir/Adm_kasir/form', $data);
    }


    
    

    public function get_detail($flag, $id){
        $result = $this->Adm_kasir->get_detail_brg_permintaan($flag, $id);
        $data = array(
            'dt_detail_brg' => $result,
            'flag' => $flag,
            );
        $temp_view = $this->load->view('loket_kasir/Adm_kasir/detail_table_view', $data, true);
        echo json_encode( array('html' => $temp_view) );
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
                $newId = $this->Adm_kasir->save($dataexc);
                /*save logs*/
                $this->logs->save('tc_stok_opname_agenda', $newId, 'insert new record on '.$this->title.' module', json_encode($dataexc),'id_tc_permohonan');
            }else{
                $dataexc['updated_date'] = date('Y-m-d H:i:s');
                $dataexc['updated_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                /*print_r($dataexc);die;*/
                /*update record*/
                $this->Adm_kasir->update(array('id_tc_permohonan' => $id), $dataexc);
                $newId = $id;
                $this->logs->save('tc_stok_opname_agenda', $newId, 'update record'.$this->title.' module', json_encode($dataexc), 'id_tc_permohonan');
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
            if($this->Adm_kasir->delete_by_id($toArray)){
                $this->logs->save('tc_stok_opname_agenda', $id, 'delete record', '', 'id_tc_permohonan');
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
