<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Req_selected_detail_brg_unit extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'purchasing/permintaan/Req_selected_detail_brg');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('purchasing/permintaan/Req_selected_detail_brg_model', 'Req_selected_detail_brg');
        $this->load->model('purchasing/permintaan/Req_pembelian_model', 'Req_pembelian');
        /*enable profiler*/
        $this->output->enable_profiler(false);
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'Title';

    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->Req_selected_detail_brg->get_datatables();
        
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $img = ( $row_list->path_image != NULL ) ? PATH_IMG_MST_BRG.$row_list->path_image : 'assets/images/no_pict.jpg' ;
            $tr_color = ( $row_list->is_active == 0 ) ? '<span style="color: red; font-weight: bold">Tidak Aktif</span>' : '<span style="color: green; font-weight: bold">Aktif</span>' ;
            $row[] = $row_list->kode_brg;
            $row[] = '<div class="center">'.$no.'</div>';
            $row[] = '<img src="'.base_url().''.$img.'" width="100px">';
            $row[] = '<div style="font-size:14px"><b>'.$row_list->kode_brg.'</b><br>'.$row_list->nama_brg.'<br>'.$tr_color.'</div>';
            $row[] = '<div align="right">Rp. '.number_format((int)$row_list->harga_beli_terakhir).',- / '.$row_list->satuan_kecil.'</div>';
            /*coloring style*/
            $color = ($row_list->jml_sat_kcl <= $row_list->stok_minimum)?'red':($row_list->jml_sat_kcl > $row_list->stok_minimum) ? 'green' : 'blue' ;
            $row[] = '<div class="center" style="color:'.$color.'; font-weight: bold">'.$row_list->stok_akhir.' '.$row_list->satuan_kecil.'</div>';
            $row[] = '<div class="center">
                        <input type="hidden" id="stok_akhir_'.$row_list->kode_brg.'" value="'.$row_list->stok_akhir.'">
                        <input type="number" style="width:70px; height:40px !important; text-align:center;font-size:14px;font-weight:bold" id="input_'.$row_list->kode_brg.'" onkeyup="sum_ttl_permintaan('."'".$row_list->kode_brg."'".', '."'".$row_list->satuan_besar."'".')">
                        <select name="satuan" id="select_satuan_'.$row_list->kode_brg.'" style="height: 40px !important">
                            <option value="'.$row_list->satuan_kecil.'" selected>'.$row_list->satuan_kecil.'</option>
                        </select>
                      </div>';
            $row[] = '<div class="center"><input type="number" style="width:70px; height:40px !important; text-align:center;font-size:14px;font-weight:bold" id="input_rasio_'.$row_list->kode_brg.'" value="'.$row_list->content.'" onkeyup="sum_ttl_permintaan('."'".$row_list->kode_brg."'".', '."'".$row_list->satuan_besar."'".')" readonly></div>';
            
            $row[] = '<div class="center" id="konversi_'.$row_list->kode_brg.'"></div>';
            $row[] = '<a href="#" class="btn btn-sm btn-primary" > <i class="fa fa-shopping-cart bigger-150"></i> </a>';
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Req_selected_detail_brg->count_all(),
                        "recordsFiltered" => $this->Req_selected_detail_brg->count_filtered(),
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
        
        $val->set_rules('id_tc_permohonan', 'ID', 'trim|required');
        $val->set_rules('kode_brg', 'Kode Barang', 'trim|required');
        $val->set_rules('jumlah_besar', 'Jumlah Permintaan', 'trim|required');
        $val->set_rules('satuan_besar', 'Satuan Besar', 'trim|required');
        $val->set_rules('rasio', 'Rasio', 'trim|required');
        $val->set_rules('stok_akhir', 'Jumlah Stok Sebelumnya', 'trim|required');
        
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

            $table = ($_POST['flag']=='medis')?'tc_permohonan':'tc_permohonan_nm';

            $dataexc = array(
                'id_tc_permohonan' => $this->regex->_genRegex($val->set_value('id_tc_permohonan'),'RGXINT'),
                'kode_brg' => $this->regex->_genRegex($val->set_value('kode_brg'),'RGXQSL'),
                'jumlah_besar' => $this->regex->_genRegex($val->set_value('jumlah_besar'),'RGXINT'),
                'satuan_besar' => $this->regex->_genRegex($val->set_value('satuan_besar'),'RGXQSL'),
                'rasio' => $this->regex->_genRegex($val->set_value('rasio'),'RGXINT'),
                'jumlah_stok_sebelumnya' => $this->regex->_genRegex($val->set_value('stok_akhir'),'RGXINT'),
            );
            
            if($id==0){
                $dataexc['created_date'] = date('Y-m-d H:i:s');
                $dataexc['created_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                $newId = $this->Req_selected_detail_brg->save($table.'_det', $dataexc);
                /*save logs*/
                // $this->logs->save($table.'_det', $newId, 'insert new record on '.$this->title.' module', json_encode($dataexc),'id_tc_permohonan');
            }else{
                $dataexc['updated_date'] = date('Y-m-d H:i:s');
                $dataexc['updated_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                /*print_r($dataexc);die;*/
                /*update record*/
                $this->Req_selected_detail_brg->update($table.'_det', array('id_tc_permohonan_det' => $id), $dataexc);
                $newId = $id;
                // $this->logs->save($table.'_det', $newId, 'update record'.$this->title.' module', json_encode($dataexc), 'id_tc_permohonan');
            }
            
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                // get total barang
                $total_brg = $this->db->get_where($table.'_det', array('id_tc_permohonan' => $_POST['id_tc_permohonan']) );
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'flag' => $_POST['flag'], 'id_tc_permohonan' => $_POST['id_tc_permohonan'], 'total_brg' => $total_brg->num_rows() ));
            }
        }
    }

    public function delete()
    {
        $id=$this->input->post('ID')?$this->regex->_genRegex($this->input->post('ID',TRUE),'RGXQSL'):null;
        $table = ($_POST['flag']=='medis')?'tc_permohonan_det':'tc_permohonan_nm_det';
        if($id!=null){
            if($this->Req_selected_detail_brg->delete_by_id($table, $id)){
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
