<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Inv_stok_gdg extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'inventory/stok/Inv_stok_gdg');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('stok/Inv_stok_gdg_model', 'Inv_stok_gdg');
        $this->load->model('stok/Inv_mutasi_gdg_model', 'Inv_mutasi_gdg');
        /*enable profiler*/
        $this->output->enable_profiler(false);
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'Title';
        
    }

    public function index() {
        // define parameter
        $kode_bagian = ( $_GET['flag'] == 'medis' ) ? '060201' : '070101' ;
        $title = ( $_GET['flag'] == 'non_medis' ) ? 'Non Medis' : 'Medis' ;
        /*define variable data*/
        $data = array(
            'title' => 'Stok Gudang '.$title.'',
            'breadcrumbs' => $this->breadcrumbs->show(),
            'kode_bagian' => $kode_bagian,
            'flag_string' => $_GET['flag'],
        );
        /*load view index*/
        $this->load->view('stok/Inv_stok_gdg/index', $data);
    }

    public function form($id='')
    {
        /*if id is not null then will show form edit*/
        if( $id != '' ){
            /*breadcrumbs for edit*/
            $this->breadcrumbs->push('Edit '.strtolower($this->title).'', 'inventory/stok/Inv_stok_gdg/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
            /*get value by id*/
            $data['value'] = $this->Inv_stok_gdg->get_by_id($id);
            /*initialize flag for form*/
            $data['flag'] = "update";
        }else{
            /*breadcrumbs for create or add row*/
            $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'inventory/stok/Inv_stok_gdg/'.strtolower(get_class($this)).'/form');
            /*initialize flag for form add*/
            $data['flag'] = "create";
        }
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('stok/Inv_stok_gdg/form', $data);
    }
    /*function for view data only*/
    public function show($id)
    {
        /*breadcrumbs for view*/
        $this->breadcrumbs->push('View '.strtolower($this->title).'', 'inventory/stok/Inv_stok_gdg/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*define data variabel*/
        $data['value'] = $this->Inv_stok_gdg->get_by_id($id);
        $data['title'] = $this->title;
        $data['flag'] = "read";
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('stok/Inv_stok_gdg/form', $data);
    }

    public function detail($kode_brg, $kode_bagian)
    {
        $title = ( $_GET['flag'] == 'non_medis' ) ? 'Non Medis' : 'Medis' ;
        /*breadcrumbs for view*/
        $this->breadcrumbs->push('View '.strtolower($title).'', 'inventory/stok/Inv_stok_gdg/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$kode_brg);
        /*define data variabel*/
        $data['unit'] = $this->db->get_where('mt_bagian', array('kode_bagian' => $kode_bagian) )->row();
        $data['value'] = $this->Inv_stok_gdg->get_mutasi_stok($kode_brg, $kode_bagian);
        // echo '<pre>'; print_r($data);die;
        $data['title'] = 'Mutasi Stok Gudang '.$title;
        $data['flag'] = "read";
        $data['flag_string'] = $_GET['flag'];
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('stok/Inv_stok_gdg/form_mutasi', $data);
    }


    public function get_data()
    {
        /*get data from model*/
        $list = $this->Inv_stok_gdg->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center"><label class="pos-rel">
                        <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->kode_brg.'"/>
                        <span class="lbl"></span>
                    </label></div>';
            $row[] = '<div class="center">'.$no.'</div>';
            $link_image = ( $row_list->path_image != NULL ) ? PATH_IMG_MST_BRG.$row_list->path_image : PATH_IMG_MST_BRG.'no-image.jpg' ;
            $row[] = '<div class="center"><a href="'.base_url().$link_image.'" target="_blank"><img src="'.base_url().$link_image.'" width="100px"></a></div>';
            $row[] = '<a href="#" onclick="click_detail('."'".$row_list->kode_brg."'".')">'.$row_list->kode_brg.'<br>'.strtoupper($row_list->nama_brg).'</a>';
            $row[] = '<div class="center">'.strtoupper($row_list->content).'</div>';
            // labeling stok minimum
            $label_color = ( $row_list->stok_minimum > $row_list->stok_akhir || $row_list->stok_akhir == 0 ) ? 'style="background-color: #d15b476b; height: 25px"' : '' ;
            $row[] = '<div class="center">'.$row_list->stok_minimum.'</div>';
            $row[] = '<div class="center" '.$label_color.'><span style="font-size: 17px">'.number_format($row_list->jml_sat_kcl).'</span></div>';
            $row[] = '<div class="left">'.strtoupper($row_list->satuan_kecil).'/'.strtoupper($row_list->satuan_besar).'</div>';
            $row[] = '<div style="text-align: right">'.number_format($row_list->harga_beli).'</div>';
            $row[] = $this->tanggal->formatDateTime($row_list->tgl_input);
            $status_aktif = ($row_list->is_active == 1) ? '<span class="label label-sm label-success">Active</span>' : '<span class="label label-sm label-danger">Not active</span>';
            $row[] = '<div class="center">'.$status_aktif.'</div>';
                   
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Inv_stok_gdg->count_all(),
                        "recordsFiltered" => $this->Inv_stok_gdg->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    public function get_data_mutasi()
    {
        /*get data from model*/
        $list = $this->Inv_mutasi_gdg->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center">'.$no.'</div>';
            $row[] = $this->tanggal->formatDateTime($row_list->tgl_input);
            $row[] = '<div class="center">'.number_format($row_list->stok_awal).'</div>';
            $row[] = '<div class="center">'.number_format($row_list->pemasukan).'</div>';
            $row[] = '<div class="center">'.number_format($row_list->pengeluaran).'</div>';
            $row[] = '<div class="center">'.number_format($row_list->stok_akhir).'</div>';
            $row[] = $row_list->keterangan;
                   
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Inv_mutasi_gdg->count_all(),
                        "recordsFiltered" => $this->Inv_mutasi_gdg->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function process()
    {
        $this->load->library('form_validation');
        $val = $this->form_validation;
        $val->set_rules('education_name', 'Nama Pendidikan', 'trim|required');

        $val->set_message('required', "Silahkan isi field \"%s\"");

        if ($val->run() == FALSE)
        {
            $val->set_error_delimiters('<div style="color:white">', '</div>');
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            $this->db->trans_begin();
            $id = ($this->input->post('id'))?$this->input->post('id'):0;

            $dataexc = array(
                'education_name' => $val->set_value('education_name'),
                'is_active' => $this->input->post('is_active'),
            );
            if($id==0){
                $dataexc['created_date'] = date('Y-m-d H:i:s');
                $dataexc['created_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                /*save post data*/
                $this->Inv_stok_gdg->save($dataexc);
                $newId = $this->db->insert_id();
                /*save logs*/
                $this->logs->save('Inv_stok_gdg', $newId, 'insert new record on '.$this->title.' module', json_encode($dataexc),'kode_brg');
            }else{
                $dataexc['updated_date'] = date('Y-m-d H:i:s');
                $dataexc['updated_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                /*update record*/
                $this->Inv_stok_gdg->update(array('kode_brg' => $id), $dataexc);
                $newId = $id;
                /*save logs*/
                $this->logs->save('Inv_stok_gdg', $newId, 'update record on '.$this->title.' module', json_encode($dataexc),'kode_brg');
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
        $id=$this->input->post('ID')?$this->input->post('ID',TRUE):null;
        $toArray = explode(',',$id);
        if($id!=null){
            if($this->Inv_stok_gdg->delete_by_id($toArray)){
                $this->logs->save('Inv_stok_gdg', $id, 'delete record', '', 'kode_brg');
                echo json_encode(array('status' => 200, 'message' => 'Proses Hapus Data Berhasil Dilakukan'));
            }else{
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Hapus Data Gagal Dilakukan'));
            }
        }else{
            echo json_encode(array('status' => 301, 'message' => 'Tidak ada item yang dipilih'));
        }
        
    }

    public function find_data()
    {   
        $output = array( "data" => http_build_query($_POST) . "\n" );
        echo json_encode($output);
    }

    public function print_label(){

        $t_barang = ( $_GET['flag'] == 'non_medis' ) ? 'mt_barang_nm' : 'mt_barang' ;
        $result = $this->db->get_where($t_barang, array('kode_brg' => $_GET['kode_brg']) )->result();
        $data = array(
            'barang' => $result,
            'count' => count($result),
            );
        $this->load->view('inventory/stok/Inv_stok_gdg/print_label', $data);
    }

    public function cetak_kartu_stok(){

        $result = $this->Inv_mutasi_gdg->get_by_params(); 
        // print_r($this->db->last_query());die;
        $data = array(
            'unit' => $this->db->get_where('mt_bagian', array('kode_bagian' => $_GET['kode_bagian']) )->row(),
            'header' => $this->Inv_stok_gdg->get_mutasi_stok($_GET['kode_brg'], $_GET['kode_bagian']),
            'value' => $result,
            'count' => count($result),
            );
            // echo '<pre>';print_r($data);die;
        $this->load->view('inventory/stok/Inv_stok_gdg/kartu_stok', $data);
    }


}


/* End of file Pendidikan.php */
/* Location: ./application/modules/Inv_stok_gdg/controllers/Inv_stok_gdg.php */
