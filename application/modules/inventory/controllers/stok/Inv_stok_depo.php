<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Inv_stok_depo extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'inventory/stok/Inv_stok_depo');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('stok/Inv_stok_depo_model', 'Inv_stok_depo');
        $this->load->model('stok/Inv_mutasi_model', 'Inv_mutasi');
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
        $this->load->view('stok/Inv_stok_depo/index', $data);
    }

    public function form($id='')
    {
        /*if id is not null then will show form edit*/
        if( $id != '' ){
            /*breadcrumbs for edit*/
            $this->breadcrumbs->push('Edit '.strtolower($this->title).'', 'inventory/stok/Inv_stok_depo/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
            /*get value by id*/
            $data['value'] = $this->Inv_stok_depo->get_by_id($id);
            /*initialize flag for form*/
            $data['flag'] = "update";
        }else{
            /*breadcrumbs for create or add row*/
            $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'inventory/stok/Inv_stok_depo/'.strtolower(get_class($this)).'/form');
            /*initialize flag for form add*/
            $data['flag'] = "create";
        }
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('stok/Inv_stok_depo/form', $data);
    }
    /*function for view data only*/
    public function show($id)
    {
        /*breadcrumbs for view*/
        $this->breadcrumbs->push('View '.strtolower($this->title).'', 'inventory/stok/Inv_stok_depo/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*define data variabel*/
        $data['value'] = $this->Inv_stok_depo->get_by_id($id);
        $data['title'] = $this->title;
        $data['flag'] = "read";
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('stok/Inv_stok_depo/form', $data);
    }

    public function detail($kode_brg, $kode_bagian)
    {
        /*breadcrumbs for view*/
        $this->breadcrumbs->push('View '.strtolower($this->title).'', 'inventory/stok/Inv_stok_depo/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$kode_brg);
        /*define data variabel*/
        $data['unit'] = $this->db->get_where('mt_bagian', array('kode_bagian' => $kode_bagian) )->row();
        $data['value'] = $this->Inv_stok_depo->get_mutasi_stok($kode_brg, $kode_bagian);
        // echo '<pre>'; print_r($data);die;
        $data['title'] = $this->title;
        $data['flag'] = "read";
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('stok/Inv_stok_depo/form_mutasi', $data);
    }


    public function get_data()
    {
        /*get data from model*/
        $list = $this->Inv_stok_depo->get_datatables();
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
            $is_prb = ($row_list->is_prb == 'Y') ? '<span style="background: gold; color: black; font-weight: bold; font-size: 10px">PRB</span>' : '' ;
            $is_kronis = ($row_list->is_kronis == 'Y') ? '<span style="background: green; color: white; font-weight: bold; font-size: 10px">Kronis</span>' : '' ;

            $row[] = '<a href="#" onclick="click_detail('."'".$row_list->kode_brg."'".')">'.$row_list->kode_brg.'<br>'.strtoupper($row_list->nama_brg).'</a><br>Harga beli @ '.number_format($row_list->harga_beli).',-<br>'.$is_prb.' '.$is_kronis;;
            $row[] = '<div class="center">'.strtoupper($row_list->content).'</div>';
            // labeling stok minimum
            $label_color = ( $row_list->stok_minimum > $row_list->stok_akhir || $row_list->stok_akhir == 0 ) ? 'style="background-color: #d15b476b; height: 25px"' : '' ;
            $row[] = '<div class="center">'.$row_list->stok_minimum.'</div>';
            $row[] = '<div class="center" '.$label_color.'><span style="font-size: 17px">'.number_format($row_list->stok_akhir).'</span></div>';
            $row[] = '<div class="left">'.strtoupper($row_list->satuan_kecil).'/'.strtoupper($row_list->satuan_besar).'</div>';
            // $row[] = '<div style="text-align: right">'.number_format($row_list->harga_beli).'</div>';
            $row[] = $this->tanggal->formatDateTime($row_list->tgl_input);
            $status_aktif = ($row_list->is_active == 1) ? '<span class="label label-sm label-success">Active</span>' : '<span class="label label-sm label-danger">Not active</span>';
            $row[] = '<div class="center">'.$status_aktif.'</div>';
            $status_brg_aktif = ($row_list->is_active == 0 ) ? '' : 'checked';
            $params_kode_bagian = isset($_GET['kode_bagian']) ? $_GET['kode_bagian'] : '060101' ;
            $row[] = '<div class="center">
                        <label>
                            <input name="status_brg_aktif" id="stat_on_off_'.$row_list->kode_brg.'_'.$row_list->kode_brg.'" onclick="setStatusAktifBrg('."'".$row_list->kode_brg."'".', '."'".$params_kode_bagian."'".')" class="ace ace-switch ace-switch-3" type="checkbox" '.$status_brg_aktif.' value="'.$row_list->is_active.'">
                            <span class="lbl"></span>
                            </label>
                    </div>';
                   
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Inv_stok_depo->count_all(),
                        "recordsFiltered" => $this->Inv_stok_depo->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }
    public function get_data_mutasi()
    {
        /*get data from model*/
        $list = $this->Inv_mutasi->get_datatables();
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
            $row[] = $row_list->fullname;
                   
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Inv_mutasi->count_all(),
                        "recordsFiltered" => $this->Inv_mutasi->count_filtered(),
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
                $this->Inv_stok_depo->save($dataexc);
                $newId = $this->db->insert_id();
                /*save logs*/
                $this->logs->save('Inv_stok_depo', $newId, 'insert new record on '.$this->title.' module', json_encode($dataexc),'kode_brg');
            }else{
                $dataexc['updated_date'] = date('Y-m-d H:i:s');
                $dataexc['updated_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                /*update record*/
                $this->Inv_stok_depo->update(array('kode_brg' => $id), $dataexc);
                $newId = $id;
                /*save logs*/
                $this->logs->save('Inv_stok_depo', $newId, 'update record on '.$this->title.' module', json_encode($dataexc),'kode_brg');
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
            if($this->Inv_stok_depo->delete_by_id($toArray)){
                $this->logs->save('Inv_stok_depo', $id, 'delete record', '', 'kode_brg');
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

    public function export_excel()
    {   
        $data = array();
        $list = $this->Inv_stok_depo->get_data($_GET['tgl'], $_GET['kode_bagian']);
        $data['data'] = $list;
        $this->load->view('inventory/stok/Inv_stok_depo/export_excel', $data);
    }

    public function print_label(){

        $result = $this->db->get_where('mt_barang', array('kode_brg' => $_GET['kode_brg']) )->result();
        $data = array(
            'barang' => $result,
            'count' => count($result),
            );
        $this->load->view('inventory/stok/Inv_stok_depo/print_label', $data);
    }

    public function cetak_kartu_stok(){

        $result = $this->Inv_mutasi->get_by_params(); 
        // print_r($this->db->last_query());die;
        $data = array(
            'unit' => $this->db->get_where('mt_bagian', array('kode_bagian' => $_GET['kode_bagian']) )->row(),
            'header' => $this->Inv_stok_depo->get_mutasi_stok($_GET['kode_brg'], $_GET['kode_bagian']),
            'value' => $result,
            'count' => count($result),
            );
            // echo '<pre>';print_r($data);die;
        $this->load->view('inventory/stok/Inv_stok_depo/kartu_stok', $data);
    }

    public function reset_stok_depo($kode_bagian, $agenda_so_id){
        // select kartu stok
        $kartu_stok = $this->db->where('id_kartu IN (select MAX(id_kartu) as id_kartu from tc_kartu_stok where kode_bagian='."'".$kode_bagian."'".' group by kode_brg) ')->from('tc_kartu_stok')->get()->result();
        
        foreach($kartu_stok as $row){
            $mutasi = array(
                'id_kartu' => $this->master->get_max_number('tc_kartu_stok','id_kartu'),
                'tgl_input' => date('Y-m-d H:i:s'),
                'kode_brg' => $row->kode_brg,
                'stok_awal' => $row->stok_awal,
                'pemasukan' => 0,
                'pengeluaran' => $row->stok_awal,
                'stok_akhir' => 0,
                'jenis_transaksi' => 10,
                'kode_bagian' => $kode_bagian,
                'keterangan' => 'Reset stok depo untuk stok opname',
                'petugas' => 0,
                'nama_petugas' => 'Administrator Sistem',
                'agenda_so_id' => $agenda_so_id,
            );
            // reset 0 kartu stok
        // $this->db->insert('tc_kartu_stok', $mutasi);
        }

        // echo '<pre>';print_r($mutasi);die;

        

        // update mt_rekap stok
        $this->db->update('mt_depo_stok', array('jml_sat_kcl' => 0), array('kode_bagian' => $kode_bagian) );

        echo 'Sukses';

    }

    public function restore_stok_depo_from_gdg($from_bagian,$to_bagian, $agenda_so_id){
        // select kartu stok
        $kartu_stok = $this->db->where('id_kartu IN (select MAX(id_kartu) as id_kartu from tc_kartu_stok where kode_bagian='."'".$from_bagian."'".' group by kode_brg) ')
                                ->where('kode_brg in (
                                    select kode_brg from mt_depo_stok where kode_bagian='."'".$to_bagian."'".' and jml_sat_kcl < 0
                                )')
                                ->where('stok_akhir > 0')
                                ->from('tc_kartu_stok')->order_by('tgl_input DESC')->get()->result();
        
        // echo '<pre>';print_r($kartu_stok);die;
        
        // mutasi gudang
        $no = 0; 
        foreach($kartu_stok as $row){

            $mutasi_gdg = array(
                'id_kartu' => $this->master->get_max_number('tc_kartu_stok','id_kartu'),
                'tgl_input' => date('Y-m-d H:i:s'),
                'kode_brg' => $row->kode_brg,
                'stok_awal' => $row->stok_akhir,
                'pemasukan' => 0,
                'pengeluaran' => $row->stok_akhir,
                'stok_akhir' => 0,
                'jenis_transaksi' => 3,
                'kode_bagian' => $from_bagian,
                'keterangan' => '(Restore Stok Depo Farmasi)',
                'petugas' => 0,
                'nama_petugas' => 'Administrator Sistem',
                'agenda_so_id' => $agenda_so_id,
            );
            // restore  kartu stok gudang farmasi
            if( $this->db->insert('tc_kartu_stok', $mutasi_gdg) ){
                 
                $this->db->update('mt_depo_stok', array('jml_sat_kcl' => 0, 'stok_akhir' => 0), array('kode_bagian' => $from_bagian, 'kode_brg' => $row->kode_brg) );
                $this->db->update('mt_rekap_stok', array('jml_sat_kcl' => 0), array('kode_bagian_gudang' => $from_bagian, 'kode_brg' => $row->kode_brg) );

                $mutasi_depo = array(
                    'id_kartu' => $this->master->get_max_number('tc_kartu_stok','id_kartu'),
                    'tgl_input' => date('Y-m-d H:i:s'),
                    'kode_brg' => $row->kode_brg,
                    'stok_awal' => 0,
                    'pemasukan' => $row->stok_akhir,
                    'pengeluaran' => 0,
                    'stok_akhir' => $row->stok_akhir,
                    'jenis_transaksi' => 3,
                    'kode_bagian' => $to_bagian,
                    'keterangan' => '(Restore Stok Depo Farmasi)',
                    'petugas' => 0,
                    'nama_petugas' => 'Administrator Sistem',
                    'agenda_so_id' => $agenda_so_id,
                );

                $this->db->insert('tc_kartu_stok', $mutasi_depo);
                $this->db->update('mt_depo_stok', array('jml_sat_kcl' => $row->stok_akhir, 'id_kartu' => $mutasi_depo['id_kartu'], 'stok_akhir' => $row->stok_akhir), array('kode_bagian' => $to_bagian, 'kode_brg' => $row->kode_brg) );

            }
            $no++;
            
        }

        // echo '<pre>';print_r($mutasi_depo);die;

        // update mt_rekap stok
        

        echo 'Sukses Total '.$no;

    }

    public function set_status_brg()
    {
        // print_r($_POST);die;
        /*proses input so*/
        $value = ($_POST['value'] == 0) ? 1 : 0;
        // $value = $_POST['value'];
        if($_POST['kode_bagian']=='070101'){
            $this->db->update('mt_barang_nm', array('is_active' => $value), array('kode_brg' => $_POST['kode_brg']) );
            $this->db->update('mt_depo_stok_nm', array('is_active' => $value), array('kode_brg' => $_POST['kode_brg'], 'kode_bagian' => $_POST['kode_bagian']) );
        }else{
            if($_POST['kode_bagian']=='060201'){
                $this->db->update('mt_barang', array('is_active' => $value), array('kode_brg' => $_POST['kode_brg']) );
            }
            $this->db->update('mt_depo_stok', array('is_active' => $value), array('kode_brg' => $_POST['kode_brg'], 'kode_bagian' => $_POST['kode_bagian']) );
            
        }

        echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan'));

    }

}


/* End of file Pendidikan.php */
/* Location: ./application/modules/Inv_stok_depo/controllers/Inv_stok_depo.php */
