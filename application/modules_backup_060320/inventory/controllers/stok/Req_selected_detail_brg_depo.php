<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Req_selected_detail_brg_depo extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'inventory/stok/Req_selected_detail_brg_depo');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('inventory/stok/Req_selected_detail_brg_depo_model', 'Req_selected_detail_brg_depo');
        /*enable profiler*/
        $this->output->enable_profiler(false);
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'Title';

    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->Req_selected_detail_brg_depo->get_datatables();
        
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $img = ( $row_list->path_image != NULL ) ? PATH_IMG_MST_BRG.$row_list->path_image : PATH_IMG_MST_BRG.'no-image.jpg' ;
            $tr_color = ( $row_list->is_active == 0 ) ? '<span style="color: red; font-weight: bold">Tidak Aktif</span>' : '<span style="color: green; font-weight: bold">Aktif</span>' ;
            $row[] = $row_list->kode_brg;
            $row[] = '<div class="center">'.$no.'</div>';
            $row[] = '<img src="'.base_url().''.$img.'" width="100px">';
            $row[] = '<div style="font-size:14px"><b>'.$row_list->kode_brg.'</b><br>'.$row_list->nama_brg.'</div>';
            $row[] = '<div align="right">Rp. '.number_format((int)$row_list->harga_beli_terakhir).',- / '.$row_list->satuan_kecil.'</div>';
            /*coloring style*/
            $color = ($row_list->jml_sat_kcl <= $row_list->stok_minimum)?'red':($row_list->jml_sat_kcl > $row_list->stok_minimum) ? 'green' : 'blue' ;
            $row[] = '<div class="center">
                        <input type="hidden" id="stok_akhir_'.$row_list->kode_brg.'" value="'.$row_list->stok_akhir.'">
                        <input type="number" style="width:70px; height:40px !important; text-align:center;font-size:14px;font-weight:bold" id="input_'.$row_list->kode_brg.'" value="'.$row_list->stok_minimum.'">
                        <select name="satuan" id="select_satuan_'.$row_list->kode_brg.'" style="height: 40px !important; width: 100px">
                            <option value="'.$row_list->satuan_kecil.'" selected>'.$row_list->satuan_kecil.'</option>
                        </select>
                      </div>';
            $row[] = '<div class="center">'.$row_list->content.' '.$row_list->satuan_kecil.'/'.$row_list->satuan_besar.'</div>';
            $row[] = '<div class="center">'.$tr_color.'</div>';
            if( is_null($row_list->kode_depo_stok) || $row_list->kode_depo_stok == '' ){
                $row[] = '<div class="center"><a href="#" class="btn btn-sm btn-primary" title="Masukan ke Depo" > <i class="fa fa-angle-double-down bigger-100"></i></a></div>';
            }else{
                $row[] = '<div class="center"><a href="#" class="btn btn-sm btn-danger" title="Hapus dari Depo"> <i class="fa fa-times-circle bigger-100"></i></a></div>';
            }
            
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Req_selected_detail_brg_depo->count_all(),
                        "recordsFiltered" => $this->Req_selected_detail_brg_depo->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function process()
    {
        // print_r($_POST);die;
        $this->load->library('form_validation');
        $val = $this->form_validation;
        
        $val->set_rules('kode_brg', 'Kode Barang', 'trim|required');
        $val->set_rules('input_value', 'Jumlah Permintaan', 'trim|required');
        $val->set_rules('kode_bagian', 'Satuan Besar', 'trim|required');
        
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

            $table = ($_POST['flag']=='medis')?'mt_depo_stok':'mt_depo_stok_nm';

            $dataexc = array(
                'kode_brg' => $this->regex->_genRegex($val->set_value('kode_brg'),'RGXQSL'),
                'stok_minimum' => $this->regex->_genRegex($val->set_value('stok_minimum'),'RGXQSL'),
                'kode_bagian' => $this->regex->_genRegex($val->set_value('kode_bagian'),'RGXQSL'),
            );
            
            if($id==0){
                $dataexc['created_date'] = date('Y-m-d H:i:s');
                $dataexc['created_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                $newId = $this->Req_selected_detail_brg_depo->save($table, $dataexc);
                /*save logs*/
                $this->logs->save($table, $newId, 'insert new record on '.$this->title.' module', json_encode($dataexc),'kode_depo_stok');
            }else{
                $dataexc['updated_date'] = date('Y-m-d H:i:s');
                $dataexc['updated_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                /*print_r($dataexc);die;*/
                /*update record*/
                $this->Req_selected_detail_brg_depo->update($table, array('kode_depo_stok' => $id), $dataexc);
                $newId = $id;
                $this->logs->save($table, $newId, 'update record'.$this->title.' module', json_encode($dataexc), 'kode_depo_stok');
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
        $table = ($_POST['flag']=='medis')?'tc_permohonan_det':'tc_permohonan_nm_det';
        if($id!=null){
            if($this->Req_selected_detail_brg_depo->delete_by_id($table, $id)){
                $this->logs->save($table, $id, 'delete record', '', 'id_tc_permohonan_det');
                $ttl = $this->Req_pembelian->get_detail_brg_permintaan($_POST['flag'], $_POST['id_tc_permohonan']);
                echo json_encode(array('status' => 200, 'message' => 'Proses Hapus Data Berhasil Dilakukan', 'total_brg' => count($ttl) ));

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
