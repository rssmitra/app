<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Produksi_obat extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'farmasi/Produksi_obat');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('farmasi/Produksi_obat_model', 'Produksi_obat');
        /*enable profiler*/
        $this->output->enable_profiler(false);
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'Title';

    }

    public function index() { 
        /*define variable data*/
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show()
        );
        /*load view index*/
        $this->load->view('Produksi_obat/index', $data);
    }

    public function form($id='')
    {
        /*if id is not null then will show form edit*/
        if( $id != '' ){
            /*breadcrumbs for edit*/
            $this->breadcrumbs->push('Edit '.strtolower($this->title).'', 'Produksi_obat/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
            /*get value by id*/
            $data['value'] = $this->Produksi_obat->get_by_id($id);
            // print_r($data);die;
            /*initialize flag for form*/
            $data['flag'] = "update";
        }else{
            /*breadcrumbs for create or add row*/
            $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Produksi_obat/'.strtolower(get_class($this)).'/form');
            /*initialize flag for form add*/
            $data['flag'] = "create";
        }
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Produksi_obat/form', $data);
    }
    /*function for view data only*/
    public function show($id)
    {
        /*breadcrumbs for view*/
        $this->breadcrumbs->push('View '.strtolower($this->title).'', 'Produksi_obat/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*define data variabel*/
        $data['value'] = $this->Produksi_obat->get_by_id($id);
        $data['title'] = $this->title;
        $data['flag'] = "read";
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Produksi_obat/form', $data);
    }

    public function get_detail($id){
        $flag = $_GET['flag'];
        
        $data = array(
            'title' => 'Preview Transaksi' ,
            'breadcrumbs' => $this->breadcrumbs->show(),
            'flag' => $_GET['flag']
        );
        $data['value'] = $this->Produksi_obat->get_by_id($id);
        $komposisi_obat_dt = $this->Produksi_obat->get_komposisi_obat($id);
        $data['komposisi'] = $komposisi_obat_dt;
        // echo '<pre>';print_r($data);die;
        $temp_view = $this->load->view('farmasi/Produksi_obat/detail_table_view', $data, true);
        echo json_encode( array('html' => $temp_view) );
    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->Produksi_obat->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center">
                        <label class="pos-rel">
                            <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->id_tc_prod_obat.'"/>
                            <span class="lbl"></span>
                        </label>
                      </div>';
            $row[] = '';
            $row[] = $row_list->id_tc_prod_obat;
            $row[] = '<div class="center"><div class="btn-group">
                        <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">
                            <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-inverse">
                            <li>'.$this->authuser->show_button('farmasi/Produksi_obat','R',$row_list->id_tc_prod_obat,67).'</li>
                            <li>'.$this->authuser->show_button('farmasi/Produksi_obat','U',$row_list->id_tc_prod_obat,67).'</li>
                            <li>'.$this->authuser->show_button('farmasi/Produksi_obat','D',$row_list->id_tc_prod_obat,6).'</li>
                        </ul>
                      </div></div>';
            $row[] = strtoupper($row_list->nama_brg);
            $row[] = strtoupper($row_list->satuan_prod);
            $row[] = '<div class="center">'.$row_list->rasio.'</div>';
            $row[] = $this->tanggal->formatDateDmy($row_list->tgl_prod);
            $row[] = $this->tanggal->formatDateDmy($row_list->tgl_expired);
            $row[] = '<div class="center">'.$row_list->jumlah_prod.'</div>';
            $row[] = '<div style="text-align: right">'.number_format($row_list->harga_prod).'</div>';
            $row[] = '<div style="text-align: right">'.number_format($row_list->harga_satuan).'</div>';

            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Produksi_obat->count_all(),
                        "recordsFiltered" => $this->Produksi_obat->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function get_komposisi_obat()
    {
        /*get data from model*/
        $data = array();
        $arr_subtotal = array();
        if (isset($_GET['id_tc_prod_obat']) and $_GET['id_tc_prod_obat'] != 0) {
            $list = $this->Produksi_obat->get_datatables_komposisi_obat();
            $no = $_POST['start'];
            foreach ($list as $row_list) {
                $no++;
                $row = array();
                $row[] = '<div class="center">'.$no.'</div>';
                $row[] = strtoupper($row_list->nama_brg);
                $row[] = '<div class="center">'.strtoupper($row_list->satuan).'</div>';
                $row[] = '<div class="center">'.number_format($row_list->jumlah_obat).'</div>';
                $row[] = '<div style="text-align: right">'.number_format($row_list->harga_beli).'</div>';
                $subtotal = $row_list->jumlah_obat * $row_list->harga_beli;
                $arr_subtotal[] = $row_list->jumlah_obat * $row_list->harga_beli;
                $row[] = '<div style="text-align: right">'.number_format($subtotal).'</div>';
                $row[] = '<div class="center">
                            <a href="#"><i class="fa fa-trash red bigger-120"></i></a> 
                          </div>';

                $data[] = $row;
            }
        }
        
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Produksi_obat->count_all_komposisi_obat(),
                        "recordsFiltered" => $this->Produksi_obat->count_filtered_komposisi_obat(),
                        "data" => $data,
                        "subtotal" => array_sum($arr_subtotal),
                );
        //output to json format
        echo json_encode($output);
    }

    public function process()
    {
        echo '<pre>'; print_r($_POST);die;
        $this->load->library('form_validation');
        $val = $this->form_validation;
        $val->set_rules('kode_brg_hidden_detail', 'Kode Barang', 'trim|required', array('required' => 'Silahkan pilih obat'));
        $val->set_rules('name', 'Function Name', 'trim|required');
        $val->set_rules('description', 'Description', 'trim|required');

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
                'code' => $this->regex->_genRegex($val->set_value('code'), 'RGXAZ'),
                'name' => $this->regex->_genRegex($val->set_value('name'), 'RGXQSL'),
                'description' => $this->regex->_genRegex($val->set_value('description'), 'RGXQSL'),
                'is_active' => $this->input->post('is_active'),
            );
            //print_r($dataexc);die;
            if($id==0){
                $dataexc['created_date'] = date('Y-m-d H:i:s');
                $dataexc['created_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                /*save post data*/
                $newId = $this->Produksi_obat->save($dataexc);
                /*save logs*/
                $this->logs->save('tmp_mst_function', $newId, 'insert new record on '.$this->title.' module', json_encode($dataexc),'id_tc_prod_obat');
            }else{
                $dataexc['updated_date'] = date('Y-m-d H:i:s');
                $dataexc['updated_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                /*update record*/
                $this->Produksi_obat->update(array('id_tc_prod_obat' => $id), $dataexc);
                $newId = $id;
                /*save logs*/
                $this->logs->save('tmp_mst_function', $newId, 'update record on '.$this->title.' module', json_encode($dataexc),'id_tc_prod_obat');
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
            if($this->Produksi_obat->delete_by_id($toArray)){
                $this->logs->save('tmp_mst_function', $id, 'delete record', '', 'id_tc_prod_obat');
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
