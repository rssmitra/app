<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pembelian_cito extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'farmasi/Pembelian_cito');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('farmasi/Pembelian_cito_model', 'Pembelian_cito');
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
        $this->load->view('Pembelian_cito/index', $data);
    }

    public function form($id='')
    {
        /*if id is not null then will show form edit*/
        if( $id != '' ){
            /*breadcrumbs for edit*/
            $this->breadcrumbs->push('Edit '.strtolower($this->title).'', 'Pembelian_cito/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
            /*get value by id*/
            $data['value'] = $this->Pembelian_cito->get_by_id($id);
            /*initialize flag for form*/
            $data['flag'] = "update";
        }else{
            /*breadcrumbs for create or add row*/
            $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Pembelian_cito/'.strtolower(get_class($this)).'/form');
            /*initialize flag for form add*/
            $data['flag'] = "create";
        }
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Pembelian_cito/form', $data);
    }
    /*function for view data only*/
    public function show($id)
    {
        /*breadcrumbs for view*/
        $this->breadcrumbs->push('View '.strtolower($this->title).'', 'Pembelian_cito/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*define data variabel*/
        $data['value'] = $this->Pembelian_cito->get_by_id($id);
        $data['title'] = $this->title;
        $data['flag'] = "read";
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Pembelian_cito/form', $data);
    }

    function preview_transaksi(){
        $data = array(
            'title' => 'Preview Transaksi' ,
            'breadcrumbs' => $this->breadcrumbs->show(),
        );
        $data['value'] = $this->Pembelian_cito->get_data_list_cito();
        // echo '<pre>'; print_r($data);die;
        $this->load->view('farmasi/Pembelian_cito/preview_transaksi', $data);

    }

    function nota_pembelian(){
        $data = array(
            'title' => 'Preview Transaksi' ,
            'breadcrumbs' => $this->breadcrumbs->show(),
        );
        $data['value'] = $this->Pembelian_cito->get_data_list_cito();
        // echo '<pre>'; print_r($data);die;
        $this->load->view('farmasi/Pembelian_cito/nota_pembelian', $data);

    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->Pembelian_cito->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center">
                        <label class="pos-rel">
                            <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->id_fr_pengadaan_cito.'"/>
                            <span class="lbl"></span>
                        </label>
                      </div>';
            $row[] = '<div class="center">
                        '.$this->authuser->show_button('farmasi/Pembelian_cito','R',$row_list->id_fr_pengadaan_cito,2).'
                        '.$this->authuser->show_button('farmasi/Pembelian_cito','U',$row_list->id_fr_pengadaan_cito,2).'
                        '.$this->authuser->show_button('farmasi/Pembelian_cito','D',$row_list->id_fr_pengadaan_cito,2).'
                      </div>'; 
            $row[] = '<div class="center">'.$row_list->id_fr_pengadaan_cito.'</div>';
            // $row[] = '<div class="left">'.$row_list->kode_pengadaan.'</div>';
            $row[] = strtoupper($row_list->nama_brg);
            $row[] = $this->tanggal->formatDateDmy($row_list->tgl_pembelian);
            $row[] = '<div class="center">'.$row_list->jumlah_kcl.'</div>';
            $row[] = '<div style="text-align: right">'.number_format($row_list->harga_beli).'</div>';
            $row[] = '<div style="text-align: right">'.number_format($row_list->harga_jual).'</div>';
            $row[] = '<div style="text-align: right">'.number_format($row_list->total_harga).'</div>';
            $row[] = ucwords(strtolower($row_list->tempat_pembelian));

            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Pembelian_cito->count_all(),
                        "recordsFiltered" => $this->Pembelian_cito->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function get_list_cito()
    {
        /*get data from model*/
        $list = $this->Pembelian_cito->get_datatables_list_cito();
        $data = array();
        if(isset($_GET['induk']) AND $_GET['induk'] != 0){
            $no = $_POST['start'];
            foreach ($list as $row_list) {
                $no++;
                $row = array();
                $row[] = '<div class="center">
                            <label class="pos-rel">
                                <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->id_fr_pengadaan_cito.'"/>
                                <span class="lbl"></span>
                            </label>
                          </div>';
                $row[] = '<div class="center">
                            '.$this->authuser->show_button('farmasi/Pembelian_cito','R',$row_list->id_fr_pengadaan_cito,2).'
                            '.$this->authuser->show_button('farmasi/Pembelian_cito','U',$row_list->id_fr_pengadaan_cito,2).'
                            '.$this->authuser->show_button('farmasi/Pembelian_cito','D',$row_list->id_fr_pengadaan_cito,2).'
                          </div>'; 
                $row[] = '<div class="center">'.$row_list->id_fr_pengadaan_cito.'</div>';
                // $row[] = '<div class="left">'.$row_list->kode_pengadaan.'</div>';
                $row[] = strtoupper($row_list->nama_brg);
                $row[] = $this->tanggal->formatDateTime($row_list->tgl_pembelian);
                $row[] = '<div class="center">'.$row_list->jumlah_kcl.'</div>';
                $row[] = '<div style="text-align: right">'.number_format($row_list->harga_beli).'</div>';
                $row[] = '<div style="text-align: right">'.number_format($row_list->harga_jual).'</div>';
                $row[] = '<div style="text-align: right">'.number_format($row_list->total_harga).'</div>';
                $row[] = ucwords(strtolower($row_list->tempat_pembelian));

                $data[] = $row;
            }
        }
        
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Pembelian_cito->count_all_list_cito(),
                        "recordsFiltered" => $this->Pembelian_cito->count_filtered_list_cito(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function process()
    {
        $this->load->library('form_validation');
        $val = $this->form_validation;
        $val->set_rules('induk_cito', 'Code', 'trim|required');
        $val->set_rules('kode_brg_hidden', 'Nama Obat', 'trim|required', array('required' => 'Silahkan masukan keyword lalu pilih nama obat !') );
        $val->set_rules('jumlah_kcl', 'Jumlah', 'trim|required');
        $val->set_rules('harga_beli', 'Harga Beli', 'trim|required');
        $val->set_rules('harga_jual', 'Harga Jual', 'trim|required');
        $val->set_rules('tempat_pembelian', 'Tempat Pembelian', 'trim|required');

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
            $total_harga = $val->set_value('jumlah_kcl') * $val->set_value('harga_beli');
            $dataexc = array(
                'tgl_pembelian' => date('Y-m-d H:i:s'),
                'kode_brg' => $this->regex->_genRegex($val->set_value('kode_brg_hidden'), 'RGXQSL'),
                'jumlah_kcl' => $this->regex->_genRegex($val->set_value('jumlah_kcl'), 'RGXINT'),
                'harga_beli' => $this->regex->_genRegex($val->set_value('harga_beli'), 'RGXINT'),
                'total_harga' => $this->regex->_genRegex($total_harga, 'RGXINT'),
                'harga_jual' => $this->regex->_genRegex($val->set_value('harga_jual'), 'RGXINT'),
                'tempat_pembelian' => $this->regex->_genRegex($val->set_value('tempat_pembelian'), 'RGXQSL'),
            );

            
            // print_r($dataexc);die;
            if($id==0){
                $dataexc['id_fr_pengadaan_cito'] = $this->master->get_max_number('fr_pengadaan_cito', 'id_fr_pengadaan_cito');
                $dataexc['induk_cito'] = ($_POST['induk_cito'] == 0) ? $dataexc['id_fr_pengadaan_cito'] : $_POST['induk_cito'];
                $dataexc['created_date'] = date('Y-m-d H:i:s');
                $dataexc['created_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                /*save post data*/
                $this->Pembelian_cito->save($dataexc);
                $newId = $dataexc['id_fr_pengadaan_cito'];
                /*save logs*/
                $this->logs->save('fr_pengadaan_cito', $newId, 'insert new record on '.$this->title.' module', json_encode($dataexc),'id_fr_pengadaan_cito');
            }else{
                $dataexc['induk_cito'] = $_POST['induk_cito'];
                $dataexc['updated_date'] = date('Y-m-d H:i:s');
                $dataexc['updated_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                /*update record*/
                $this->Pembelian_cito->update(array('id_fr_pengadaan_cito' => $id), $dataexc);
                $newId = $id;
                /*save logs*/
                $this->logs->save('fr_pengadaan_cito', $newId, 'update record on '.$this->title.' module', json_encode($dataexc),'id_fr_pengadaan_cito');
            }

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'induk_cito' => $dataexc['induk_cito']));
            }
        }
    }

    public function proses_selesai()
    {
        $this->load->library('form_validation');
        $val = $this->form_validation;
        $val->set_rules('ID', 'Nomor Induk', 'trim|required');

        $val->set_message('required', "Silahkan isi field \"%s\"");

        if ($val->run() == FALSE)
        {
            $val->set_error_delimiters('<div style="color:white">', '</div>');
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            $this->db->trans_begin();
            $induk_cito = ($this->input->post('ID'))?$this->regex->_genRegex($this->input->post('ID'),'RGXINT'):0;
            // get data from parent
            $dt = $this->db->get_where('fr_pengadaan_cito', array('induk_cito' => $induk_cito) )->result();
            // print_r($dt);die;
            foreach ($dt as $key => $val) {
                // add kartu stok cito
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
            $get_data = $this->Pembelian_cito->get_by_id($id);
            if($this->Pembelian_cito->delete_by_id($toArray)){
                // count jumlah data sisa
                $count = $this->db->get_where('fr_pengadaan_cito', array('induk_cito' => $get_data->induk_cito) )->num_rows();
                $this->logs->save('fr_pengadaan_cito', $id, 'delete record', '', 'id_fr_pengadaan_cito');
                echo json_encode(array('status' => 200, 'message' => 'Proses Hapus Data Berhasil Dilakukan', 'count_last_dt' => $count));

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
