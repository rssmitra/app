<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pengiriman_unit extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'purchasing/pendistribusian/Pengiriman_unit');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('purchasing/penerimaan/Penerimaan_brg_model', 'Penerimaan_brg');
        $this->load->model('purchasing/pendistribusian/Pengiriman_unit_model', 'Pengiriman_unit');
        $this->load->model('purchasing/pendistribusian/Permintaan_stok_unit_model', 'Permintaan_stok_unit');
        // load libraries
        $this->load->library('stok_barang');
        /*enable profiler*/
        $this->output->enable_profiler(false);
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'Title';

    }

    public function form($id='')
    {
        $flag = isset($_GET['flag'])?$_GET['flag']:'medis';
        $data = [];
        /*if id is not null then will show form edit*/
        if( $id != '' ){
            /*breadcrumbs for edit*/
            $this->breadcrumbs->push('Edit '.strtolower($this->title).'', 'Permintaan_stok_unit/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
            /*get value by id*/
            $data['id'] = $id; 
            
            $data['value'] = $this->Permintaan_stok_unit->get_by_id($id); 
            /*initialize flag for form*/
            $data['flag'] = "update";
        }else{
            /*breadcrumbs for create or add row*/
            $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Permintaan_stok_unit/'.strtolower(get_class($this)).'/form');
            /*initialize flag for form add*/
            $data['flag'] = "create";
        }
        $data['type'] = $flag; 
        // echo "<pre>";print_r($data);die;
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('pendistribusian/Pengiriman_unit/form', $data);
    }

    public function form_edit_brg($id_tc_permintaan_inst_det='')
    {
        $data = [];
        $data['flag'] = isset($_GET['flag'])?$_GET['flag']:'non_medis';
        $kode_gudang = ($_GET['flag']=='non_medis')?'070101':'060201';
        $data['kode_gudang'] = $kode_gudang;
        $data['value'] = $this->Pengiriman_unit->get_permintaan_inst_det_by_id($id_tc_permintaan_inst_det, $_GET['flag']);
        // echo '<pre>';print_r($data);die;
        
        /*load form view*/
        $this->load->view('pendistribusian/Pengiriman_unit/form_edit_brg', $data);
    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->Permintaan_stok_unit->get_datatables();
        
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center">
                        <label class="pos-rel">
                            <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->id_tc_permintaan_inst.'"/>
                            <span class="lbl"></span>
                        </label>
                      </div>';
            $row[] = '';
            $row[] = $row_list->id_tc_permintaan_inst;
            if( $row_list->status_acc != 1 ){
                $row[] = '<div class="center">
                            <div class="btn-group">
                                <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">
                                    <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                                </button>
                                <ul class="dropdown-menu dropdown-inverse">
                                <li>'.$this->authuser->show_button('purchasing/pendistribusian/Permintaan_stok_unit','R',$row_list->id_tc_permintaan_inst,67).'</li>
                                <li>'.$this->authuser->show_button('purchasing/pendistribusian/Permintaan_stok_unit','U',$row_list->id_tc_permintaan_inst,67).'</li>
                                <li>'.$this->authuser->show_button('purchasing/pendistribusian/Permintaan_stok_unit','D',$row_list->id_tc_permintaan_inst,6).'</li>
                                </ul>
                            </div>
                        </div>';
            }else{
                $row[] = '<div class="center"><a href="#" onclick="PopupCenter('."'".base_url().'purchasing/pendistribusian/Permintaan_stok_unit/print_preview/'.$row_list->id_tc_permintaan_inst.'?flag='.$_GET['flag']."'".', '."'PERMINTAAN PEMBELIAN'".', 1000, 550)" ><i class="fa fa-print bigger-150 inverse"></a></div>';
            }
            $row[] = '<div class="center">'.$row_list->id_tc_permintaan_inst.'</div>';
            // $row[] = $row_list->nomor_permintaan;
            
            // Determine label based on flag parameter
            $flag = isset($_GET['flag']) ? $_GET['flag'] : '';
            if ($flag == 'medis') {
                $label_flag = '<span style="color: green; font-weight: bold">Medis</span>';
            } elseif ($flag == 'non_medis') {
                $label_flag = '<span style="color: blue; font-weight: bold">Non Medis</span>';
            } else {
                $label_flag = '';
            }

            $jenis_permintaan = ($row_list->jenis_permintaan==0)?'Rutin':'Cito';
            $row[] = $this->tanggal->formatDateDmy($row_list->tgl_permintaan).'<br>'.$label_flag.' - '.ucfirst($jenis_permintaan).'';
            $row[] = '<div class="left">'.ucwords($row_list->bagian_minta).'</div>';
            $row[] = '<div class="left">'.ucfirst($row_list->nama_user_input).'</div>';
            $row[] = '<div class="left">'.$row_list->catatan.'</div>';
            $tgl_acc = ($row_list->tgl_acc ==null) ? '<i class="fa fa-exclamation-triangle bigger-150 orange"></i>' : $this->tanggal->formatDateDmy($row_list->tgl_acc);
            $acc_by = ($row_list->acc_by ==null) ? '<i class="fa fa-exclamation-triangle bigger-150 orange"></i>' : $row_list->acc_by;
            $row[] = '<div class="center">'.$tgl_acc.'</div>';
            $row[] = '<div class="center">'.$acc_by.'</div>';
            if($row_list->tgl_acc == null)
            {
                $style_status = '<span style="width: 100% !important" class="label label-warning"><i class="fa fa-exclamation-triangle"></i> Belum diverifikasi</span>';
            }else{
                $style_status = ($row_list->status_acc == 1) ? '<span class="label label-success" style="width: 100% !important"><i class="fa fa-check"></i> Disetujui</span>' :'<span style="width: 100% !important" class="label label-danger"><i class="fa fa-times"></i> Tidak disetujui</span>';
            }
            
            $row[] = '<div class="center">'.$style_status.'</div>';
            $row[] = '<div class="center">'.$this->tanggal->formatDateTime($row_list->tgl_pengiriman).'</div>';
            $row[] = '<div class="center">'.$this->tanggal->formatDateTime($row_list->tgl_input_terima).'</div>';
            $row[] = '<div class="center">'.ucfirst($row_list->yg_terima).'</div>';
            $btn_kirim_permintaan = ($row_list->status_acc == null) ? '<a href="#" onclick="kirim_permintaan('."'".$row_list->id_tc_permintaan_inst."'".')" title="Kirim Permintaan" class="label label-xs label-primary"><i class="fa fa-paper-plane"></i> Kirim</a>' : '';
            if($row_list->send_to_verify == 1){
                $btn_kirim_permintaan = '<i class="fa fa-check bigger-150 green" title="Terkirim"></i>';
            }
            $row[] = '<div class="center">'.$btn_kirim_permintaan.'</div>';
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Permintaan_stok_unit->count_all(),
                        "recordsFiltered" => $this->Permintaan_stok_unit->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function form_pengiriman_unit()
    {
        /*breadcrumbs for view*/
        $this->breadcrumbs->push('Pengimriman Barang ', 'Pengiriman_unit/'.strtolower(get_class($this)).'/'.__FUNCTION__.'?ID='.$_GET['ID'].'&flag='.$_GET['flag']);

        /*define data variabel*/
        $result = $this->Penerimaan_brg->get_penerimaan_brg($_GET['flag'], $_GET['ID']);
        $data['id'] = $_GET['ID'];
        $data['nomor_permintaan'] = $this->master->format_nomor_permintaan($_GET['flag']);
        $data['flag'] = isset($_GET['flag'])?$_GET['flag']:'';
        $data['penerimaan'] = $result;
        $data['value'] = $this->Pengiriman_unit->get_by_id($_GET['ID']);
        $data['title'] = 'Pengiriman Barang ke Unit/Depo';
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        // echo '<pre>'; print_r($data);die;
        /*load form view*/
        $this->load->view('pendistribusian/Pengiriman_unit/form_pengiriman_unit', $data);
    }

    public function process_edit_brg()
    {
        // print_r($_POST);die;
        $this->load->library('form_validation');
        $val = $this->form_validation;
        
        $val->set_rules('kode_brg_revisi', 'Kode Barang', 'trim|required');
        $val->set_rules('qtyBrg', 'Jumlah Revisi Barang', 'trim|required');
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

            $table = ($_POST['flag']=='medis')?'tc_permintaan_inst_det':'tc_permintaan_inst_nm_det';
            $is_bhp = isset($_POST['is_bhp']) ? 1 : 0 ;
            $dataexc = array(
                'rev_kode_brg' => $this->regex->_genRegex($val->set_value('kode_brg_revisi'),'RGXQSL'),
                'rev_qty' => $this->regex->_genRegex($_POST['qtyBrg'],'RGXINT'),
                'is_bhp' => $this->regex->_genRegex($is_bhp,'RGXINT'),
            );
            $this->Pengiriman_unit->update($table, ['id_tc_permintaan_inst_det' => $_POST['id_det']], $dataexc);
            
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

    public function process_pengiriman_brg_unit()
    {
        // print_r($_POST);die;
        $this->load->library('form_validation');
        $val = $this->form_validation;
        
        $val->set_rules('tgl_distribusi', 'Tanggal Distribusi', 'trim');
        $val->set_rules('catatan', 'Catatan', 'trim');
        $val->set_rules('yang_menyerahkan', 'Yang Menyerahkan', 'trim|required');
        
        $val->set_message('required', "Silahkan isi field \"%s\"");

        if ($val->run() == FALSE)
        {
            $val->set_error_delimiters('<div style="color:white">', '</div>');
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            $this->db->trans_begin();

            
            $table = ($_POST['flag_cart']=='medis')?'tc_permintaan_inst':'tc_permintaan_inst_nm';
            $mt_depo_stok = ($_POST['flag_cart']=='medis')?'mt_depo_stok':'mt_depo_stok_nm';
            $kode_gudang = ($_POST['flag_cart']=='medis')?'060201':'070101';
            $nama_bagian = $this->master->get_string_data('nama_bagian', 'mt_bagian', array('kode_bagian' => $_POST['kode_bagian_minta']));

            $dataexc = array(
                'tgl_pengiriman' => $val->set_value('tgl_distribusi').' '.date(' H:i:s '),
                'keterangan_kirim' => $this->regex->_genRegex($val->set_value('catatan'),'RGXQSL'),
                'yg_serah' => $this->regex->_genRegex($val->set_value('yang_menyerahkan'),'RGXQSL'),
                'updated_date' => date('Y-m-d H:i:s'),
                'updated_by' => json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL'))),
            );
            // echo "<pre>";print_r($dataexc);die;
            $newId = $this->Pengiriman_unit->update($table, ['id_tc_permintaan_inst' => $_POST['id']], $dataexc);

            $cart_data = $this->Pengiriman_unit->get_cart_data_by_id($_POST['flag_cart'], $_POST['selected_id']);
            // echo "<pre>";print_r($cart_data);die;
            // update stok gudang
            foreach( $cart_data as $row_brg ){
                
                // kurang stok gudang
                $kode_brg = ($row_brg->rev_kode_brg != NULL || !empty($row_brg->rev_kode_brg) ) ? $row_brg->rev_kode_brg : $row_brg->kode_brg ;
                $qty_brg = ($row_brg->rev_qty != NULL || !empty($row_brg->rev_qty) ) ? $row_brg->rev_qty : $row_brg->qty ;

                // update status verif di detail permintaan
                $update_detail = array(
                    'tgl_kirim' => date('Y-m-d H:i:s'),
                    'jumlah_kirim' => $qty_brg,
                    'petugas_kirim' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL'),
                );
                // update table permintaan detail
                $this->Pengiriman_unit->update($table.'_det', ['id_tc_permintaan_inst_det' => $row_brg->id_tc_permintaan_inst_det], $update_detail);
                // proses stok
                $this->stok_barang->stock_process($kode_brg, $qty_brg, $kode_gudang, 3 ," ".$nama_bagian." &nbsp; [".$_POST['id']."] ", 'reduce');
                $this->db->trans_commit();

            }

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'flag' => $_POST['flag_cart'], 'id' => $newId));
            }
        }
    }

    public function get_detail_cart()
    {
        /*get data from model*/
        $list = $this->Pengiriman_unit->get_cart_data();
        // echo '<pre>';print_r($list);die;
        
        $data = array();
        $arr_count = array();
        $no=0;
        foreach ($list as $row_list) {
            $no++;
            $row = array();

            if($row_list->status_verif == 1){
                if($row_list->jumlah_kirim == $row_list->jml_acc_atasan){
                    $row[] = '<div class="center"><i class="fa fa-check green"></i></div>';
                }else{
                    $row[] = '<div class="center">
                        <label class="pos-rel">
                            <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->id_tc_permintaan_inst_det.'"/>
                            <span class="lbl"></span>
                        </label>
                      </div>';   
                      $arr_count[] = 1; 
                }
            }else{
                $row[] = '<div class="center"><i class="fa fa-times red"></i></div>';
            }
            
            $row[] = '<div class="center">'.$no.'</div>';
            $row[] = '<div class="left">'.$row_list->kode_brg.'</div>';
            $nama_brg = ($row_list->brg_revisi == null) ? $row_list->nama_brg : '<s style="color: red">'.$row_list->nama_brg.'</s> <i class="fa fa-arrow-right"></i> '.$row_list->brg_revisi;
            $qty = ($row_list->qty_revisi == null) ? $row_list->qty : '<s style="color: red">'.$row_list->qty.'</s> <i class="fa fa-arrow-right"></i> '.$row_list->qty_revisi;

            $stok_gudang = ($row_list->stok_gdg_revisi == null) ? $row_list->jumlah_stok_gudang : '<s style="color: red">'.$row_list->jumlah_stok_gudang.'</s> <i class="fa fa-arrow-right"></i> '.$row_list->stok_gdg_revisi;
            $row[] = '<div class="left">'.$nama_brg.'</div>';
            $row[] = '<div class="center">'.$row_list->satuan.'</div>';
            $row[] = '<div class="center">'.$row_list->jumlah_stok_sebelumnya.'</div>';
            $row[] = '<div class="center">'.$qty.'</div>';
            $row[] = '<div class="center">'.$row_list->jml_acc_atasan.'</div>';
            $row[] = '<div class="center">'.$row_list->jumlah_kirim.'</div>';
            $row[] = '<div class="center">'.$stok_gudang.'</div>';
            $row[] = '<div class="center">'.$row_list->keterangan_verif.'</div>';
            $row[] = '<div style="text-align: right">'.number_format($row_list->harga).'</div>';

            if($row_list->status_verif == 1){
                if($row_list->jumlah_kirim > 0){
                    $row[] = '<div class="center"><i class="fa fa-check green"></i></div>';
                }else{
                    $row[] = '<div style="text-align: center"><a class="label label-xs label-success" onclick="edit_brg('.$row_list->id_tc_permintaan_inst_det.', '."'Penyesuaian Permintaan Barang x Distribusi'".')" href="#"><span><i class="fa fa-pencil"></i></span></a></div>';
                }
            }else{
                $row[] = '<div class="center">-</div>';
            }
   
            $data[] = $row;
        }

        $output = array(
            "total_belum_didistribusi" => array_sum($arr_count),
            "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function delete_cart()
    {
        $id=$this->input->post('ID')?$this->regex->_genRegex($this->input->post('ID',TRUE),'RGXQSL'):null;
        if($id!=null){
            if($this->db->where(array('kode_brg' => $id, 'user_id_session' => $this->session->userdata('user')->user_id))->delete('tc_permintaan_inst_cart_log')){
                echo json_encode(array('status' => 200, 'message' => 'Proses Hapus Data Berhasil Dilakukan' ));
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

