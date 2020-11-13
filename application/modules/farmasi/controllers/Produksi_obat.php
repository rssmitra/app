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
        // load libaries
        $this->load->library('stok_barang');
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
            // echo '<pre>';print_r($data);die;
            /*initialize flag for form*/
            $data['flag'] = "update";
        }else{
            /*breadcrumbs for create or add row*/
            $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Produksi_obat/'.strtolower(get_class($this)).'/form');
            /*initialize flag for form add*/
            $data['flag'] = "create";
        }
        // kode bagian gudang
        $data['kode_bagian_gudang'] = '060201';
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
        $komposisi_obat_dt = $this->Produksi_obat->get_komposisi_obat($id);
        $data['komposisi'] = $komposisi_obat_dt;
        $data['title'] = $this->title;
        $data['flag'] = "read";
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Produksi_obat/form_view', $data);
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

            $btn_delete = ($row_list->flag_proses == 0) ? '<li>'.$this->authuser->show_button('farmasi/Produksi_obat','D',$row_list->id_tc_prod_obat,6).'</li>':'';
            $btn_rollback = ($row_list->flag_proses == 1) ? '<li><a href="#" onclick="rollback_produksi('.$row_list->id_tc_prod_obat.')">Rollback Produksi</a></li>':'';
            $row[] = '<div class="center"><div class="btn-group">
                        <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">
                            <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-inverse">
                            '.$btn_delete.'
                            '.$btn_rollback.'
                           
                            
                        </ul>
                      </div></div>';
            if($row_list->flag_proses == 0){
                $row[] = '<a href="#" onclick="getMenu('."'farmasi/Produksi_obat/form/".$row_list->id_tc_prod_obat."?flag=All'".')">'.strtoupper($row_list->nama_brg_prod).'</a>';
            }else{
                $row[] = '<a href="#" onclick="getMenu('."'farmasi/Produksi_obat/show/".$row_list->id_tc_prod_obat."?flag=All'".')">'.strtoupper($row_list->nama_brg_prod).'</a>';
            }
            $row[] = strtoupper($row_list->satuan_prod);
            $row[] = '<div class="center">'.$row_list->rasio.'</div>';
            $row[] = $this->tanggal->formatDateDmy($row_list->tgl_prod);
            $row[] = $this->tanggal->formatDateDmy($row_list->tgl_expired);
            $row[] = '<div class="center">'.$row_list->jumlah_prod.'</div>';
            $row[] = '<div style="text-align: right">'.number_format($row_list->harga_prod).'</div>';
            $row[] = '<div style="text-align: right">'.number_format($row_list->harga_satuan).'</div>';
            $status = ($row_list->flag_proses == 1) ? '<label class="label label-success" style="margin-bottom: 0px !important"><i class="fa fa-check-circle"></i> Selesai</label>':'<label class="label label-yellow" style="margin-bottom: 0px !important"><i class="fa fa-flask"></i> Dalam proses</label>';
            $row[] = '<div class="center">'.$status.'</div>';

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
                            <a href="#" onclick="delete_item_komposisi('.$row_list->id_tc_prod_obat_det.')"><i class="fa fa-trash red bigger-120"></i></a> 
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
        // echo '<pre>'; print_r($_POST);die;
        $this->load->library('form_validation');
        $val = $this->form_validation;
        if($_POST['submit'] == 'detail'){   
            $val->set_rules('kode_brg_hidden_detail', 'Kode Barang', 'trim|required', array('required' => 'Silahkan pilih obat'));
            $val->set_rules('jumlah_kcl', 'Jumlah Obat', 'trim|required');
        }

        if($_POST['submit'] == 'finish'){
            $val->set_rules('kode_brg_prod', 'Kode Barang', 'trim|required', array('required' => 'Silahkan pilih obat'));
            $val->set_rules('jasa_prod', 'Jasa Produksi', 'trim|required');
            $val->set_rules('jumlah_prod', 'Jumlah Produksi', 'trim|required');
            $val->set_rules('tgl_prod', 'Tanggal Produksi', 'trim|required');
            $val->set_rules('tgl_expired', 'Tanggal Expired', 'trim|required');
        }

        $val->set_message('required', "Silahkan isi field \"%s\"");

        if ($val->run() == FALSE)
        {
            $val->set_error_delimiters('<div style="color:white">', '</div>');
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            $this->db->trans_begin();
            $id_tc_prod_obat = ($this->input->post('id_tc_prod_obat'))?$this->regex->_genRegex($this->input->post('id_tc_prod_obat'),'RGXINT'):0;
            $id_tc_prod_obat_det = ($this->input->post('id_tc_prod_obat_det'))?$this->regex->_genRegex($this->input->post('id_tc_prod_obat_det'),'RGXINT'):0;

            $data_komposisi_obat = array(
                'kode_brg' => $this->input->post('kode_brg_hidden_detail'),
                'nama_brg' => $this->input->post('nama_tindakan'),
                'id_obat' => $this->input->post('id_obat'),
                'jumlah_obat' => $this->input->post('jumlah_kcl'),
                'satuan' => $this->input->post('pl_satuan_kecil'),
                'harga_beli' => $this->input->post('pl_harga_umum'),
                'flag_proses' => 1,
                'session_user_id' => $this->session->userdata('user')->user_id,
            );
            // echo '<pre>'; print_r($data_komposisi_obat);die;

            $data_produksi = array(
                'nama_brg_prod' => $this->input->post('nama_brg_prod'),
                'satuan_prod' => $this->input->post('satuan_kecil_prod'),
                'kode_brg_prod' => $this->input->post('kode_brg_prod'),
                'id_obat_prod' => $this->input->post('id_obat_prod'),
                'jasa_prod' => $this->input->post('jasa_prod'),
                'jumlah_prod' => $this->input->post('jumlah_prod'),
                'flag_proses' => 1,
                'harga_prod' => $this->input->post('harga_prod'),
                'tgl_prod' => $this->input->post('tgl_prod'),
                'tgl_expired' => $this->input->post('tgl_expired'),
                'input_id' => $this->session->userdata('user')->user_id,
                'input_tgl' => date('Y-m-d H:i:s'),
                'rasio' => $this->input->post('rasio'),
                'harga_satuan' => $this->input->post('harga_satuan_prod'),
            );

            
            if($_POST['submit'] =='finish'){
                // Produksi Obat
                if($id_tc_prod_obat==0){
                    $data_produksi['created_date'] = date('Y-m-d H:i:s');
                    $data_produksi['created_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                    /*save post data*/
                    $newIdProdObat = $this->Produksi_obat->save($data_produksi);
                    /*save logs*/
                    $this->logs->save('tc_prod_obat', $newIdProdObat, 'insert new record on '.$this->title.' module', json_encode($data_produksi),'id_tc_prod_obat');
                }else{
                    $data_produksi['updated_date'] = date('Y-m-d H:i:s');
                    $data_produksi['updated_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                    /*update record*/
                    $this->Produksi_obat->update(array('id_tc_prod_obat' => $id_tc_prod_obat), $data_produksi);
                    $newIdProdObat = $id_tc_prod_obat;
                    /*save logs*/
                    $this->logs->save('tc_prod_obat', $newIdProdObat, 'update record on '.$this->title.' module', json_encode($data_produksi),'id_tc_prod_obat');
                }

                // udpate harga obat, cari yang paling besar harga satuannya
                $harga_satuan_master = $_POST['pl_harga_satuan'];
                $harga_beli_master = ($harga_satuan_master > $_POST['harga_satuan_prod']) ? $harga_satuan_master : $_POST['harga_satuan_prod'];
                // update master barang
                $data_brg = array(
                    'harga_beli' => $harga_beli_master,
                    'harga_satuan_produksi' => $harga_beli_master,
                    'id_tc_prod_obat' => $newIdProdObat,
                    'updated_date' => date('Y-m-d H:i:s'),
                    'updated_by' => json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL'))),
                );
                $this->db->update('mt_barang', $data_brg, array('kode_brg' => $_POST['kode_brg_prod'] ));

                // update rekap stok
                $rekap_stok = array(
                    'harga_beli' => $harga_beli_master, 
                    'harga_beli_supplier' => $harga_beli_master, 
                    'harga_satuan_produksi' => $harga_beli_master,
                    'id_tc_prod_obat' => $newIdProdObat,
                    'updated_date' => date('Y-m-d H:i:s'),
                    'updated_by' => json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL'))),
                );

                $this->db->where(array('kode_brg' => $_POST['kode_brg_prod'], 'kode_bagian_gudang' => $_POST['kode_bagian_gudang']))->update('mt_rekap_stok', $rekap_stok );
                // tambahkan ke stok gudang
                $exc_mutasi = $this->stok_barang->stock_process_produksi_obat($_POST['kode_brg_prod'], $_POST['jumlah_prod'], $_POST['kode_bagian_gudang'], 12 ,"(Produksi Obat)", 'restore');
                // update rekap stok
                $this->db->update('mt_rekap_stok' ,array('jml_sat_kcl' => $exc_mutasi['stok_akhir']), array('kode_brg' => $_POST['kode_brg_prod'], 'kode_bagian_gudang' => $_POST['kode_bagian_gudang'] ) );

                // komposisi item dipotong stok nya
                $getItemObat = $this->db->get_where('tc_prod_obat_det', array('id_tc_prod_obat' => $newIdProdObat) )->result();
                foreach ($getItemObat as $k => $v) {
                    // kurang stok bahan komposisi
                    $this->stok_barang->stock_process_produksi_obat($v->kode_brg, $v->jumlah_obat, $_POST['kode_bagian_gudang'], 18 ,"(Bahan Produksi)", 'reduce');
                    // update rekap stok
                    $this->db->update('mt_rekap_stok' ,array('jml_sat_kcl' => $v->jumlah_obat), array('kode_brg' => $v->kode_brg, 'kode_bagian_gudang' => $_POST['kode_bagian_gudang'] ) );
                    $this->db->trans_commit();
                }

            }

            if($_POST['submit'] =='detail'){
                if($id_tc_prod_obat==0){
                    $data_temp_produksi['nama_brg_prod'] = 'Temporary - '.date('Y-m-d H:i:s');
                    $data_temp_produksi['input_id'] = $this->session->userdata('user')->user_id;
                    $data_temp_produksi['input_tgl'] = date('Y-m-d H:i:s');
                    $data_temp_produksi['created_date'] = date('Y-m-d H:i:s');
                    $data_temp_produksi['created_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                    /*save post data*/
                    $newIdProdObat = $this->Produksi_obat->save($data_temp_produksi);
                    /*save logs*/
                    $this->logs->save('tc_prod_obat', $newIdProdObat, 'insert new record on '.$this->title.' module', json_encode($data_temp_produksi),'id_tc_prod_obat');
                }else{
                    $newIdProdObat = $id_tc_prod_obat;
                }
                // Komposisi Obat
                if($id_tc_prod_obat_det==0){
                    $data_komposisi_obat['id_tc_prod_obat'] = $newIdProdObat;
                    /*save post data*/
                    $newIdProdObatDet = $this->db->insert('tc_prod_obat_det',$data_komposisi_obat);
                }else{
                    $data_komposisi_obat['id_tc_prod_obat'] = $newIdProdObatDet;
                    /*update record*/
                    $this->db->update('tc_prod_obat_det', $data_komposisi_obat, array('id_tc_prod_obat_det' => $id_tc_prod_obat_det) );
                    $newIdProdObatDet = $id_tc_prod_obat_det;
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
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'id_tc_prod_obat' => $newIdProdObat, 'action' => $_POST['submit']));
            }
        }
    }

    public function delete()
    {
        $id=$this->input->post('ID')?$this->regex->_genRegex($this->input->post('ID',TRUE),'RGXQSL'):null;
        $toArray = explode(',',$id);
        if($id!=null){
            if($this->Produksi_obat->delete_by_id($toArray)){
                $this->logs->save('tc_prod_obat', $id, 'delete record', '', 'id_tc_prod_obat');
                echo json_encode(array('status' => 200, 'message' => 'Proses Hapus Data Berhasil Dilakukan'));

            }else{
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Hapus Data Gagal Dilakukan'));
            }
        }else{
            echo json_encode(array('status' => 301, 'message' => 'Tidak ada item yang dipilih'));
        }
        
    }

    public function rollback_produksi()
    {
        $id=$this->input->post('ID')?$this->regex->_genRegex($this->input->post('ID',TRUE),'RGXQSL'):null;
        
        if($this->Produksi_obat->rollback_produksi($id)){
            $this->logs->save('tc_prod_obat', $id, 'rollback record', '', 'id_tc_prod_obat');
            echo json_encode(array('status' => 200, 'message' => 'Proses Rollback Berhasil Dilakukan'));
        }else{
            echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Rollback Gagal Dilakukan'));
        }
        
    }
    
    public function delete_item_komposisi()
    {
        $id=$this->input->post('ID')?$this->regex->_genRegex($this->input->post('ID',TRUE),'RGXQSL'):null;
        if($this->Produksi_obat->delete_item_komposisi($id)){
            echo json_encode(array('status' => 200, 'message' => 'Proses Hapus Data Berhasil Dilakukan'));

        }else{
            echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Hapus Data Gagal Dilakukan'));
        }
        
    }


}

/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
