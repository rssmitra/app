<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Mst_tarif extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'tarif/Mst_tarif');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('Mst_tarif_model', 'Mst_tarif');
        $this->load->library('tarif');
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
        $this->load->view('Mst_tarif/index', $data);
    }

    public function form($id='')
    {
        /*if id is not null then will show form edit*/
        if( $id != '' ){
            /*breadcrumbs for edit*/
            $this->breadcrumbs->push('Edit '.strtolower($this->title).'', 'Mst_tarif/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
            /*get value by id*/
            $data['value'] = $this->Mst_tarif->get_by_id($id);
            /*initialize flag for form*/
            $data['flag'] = "update";
        }else{
            /*breadcrumbs for create or add row*/
            $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Mst_tarif/'.strtolower(get_class($this)).'/form');
            /*initialize flag for form add*/
            $data['flag'] = "create";
        }
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Mst_tarif/form', $data);
    }
    /*function for view data only*/
    public function edit_klas_tarif($kode_master_tarif_detail)
    {
        // define
        $tarif = $this->Mst_tarif->get_detail_by_kode_tarif_detail($kode_master_tarif_detail);
        /*breadcrumbs for view*/
        /*define data variabel*/
        $data['value'] = $tarif;
        $data['title'] = $tarif->nama_tarif;
        $data['flag'] = "read";
        /*load form view*/
        $this->load->view('Mst_tarif/form_edit_klas_tarif', $data);
    }

    public function getDetail($kode_tarif){
        
        $data = $this->Mst_tarif->get_detail_by_kode_tarif($kode_tarif);
        // echo'<pre>';print_r($data);
        
        $html = '';
        if(count($data) > 0){
            $html .= '<div style="border-bottom: 1px #333 solid"><b><h4>Tarif klas pasien : </h4></b></div><br>';
            $html .= '<table class="table table-striped" style="width: 90%; margin-left: 38px !important">';
            $html .= '<tr>';
                $html .= '<th>Nama Klas</th>';
                $html .= '<th>Bill Dr1</th>';
                $html .= '<th>Bill Dr2</th>';
                $html .= '<th>Bill Dr3</th>';
                $html .= '<th>Kamar Tindakan</th>';
                $html .= '<th>Obat</th>';
                $html .= '<th>Alkes</th>';
                $html .= '<th>Alat RS</th>';
                $html .= '<th>BHP</th>';
                $html .= '<th>Adm</th>';
                $html .= '<th>Pendapatan RS</th>';
                $html .= '<th>Biaya Lainnya</th>';
                $html .= '<th>Total</th>';
                $html .= '<th>Revisi Ke-</th>';
                $html .= '<th>Action</th>';
            $html .= '</tr>'; 
            foreach ($data as $key => $value) {
                $nama_klas = ($key != '0')?$key:'Tarif Global';
                foreach ($value as $key2 => $val) {
                    # code...
                    $html .= '<tr>';
                        $html .= '<td>'.$nama_klas.'</td>';
                        $html .= '<td align="right">'.number_format($val->bill_dr1).'</td>';
                        $html .= '<td align="right">'.number_format($val->bill_dr2).'</td>';
                        $html .= '<td align="right">'.number_format($val->bill_dr3).'</td>';
                        $html .= '<td align="right">'.number_format($val->kamar_tindakan).'</td>';
                        $html .= '<td align="right">'.number_format($val->obat).'</td>';
                        $html .= '<td align="right">'.number_format($val->alkes).'</td>';
                        $html .= '<td align="right">'.number_format($val->alat_rs).'</td>';
                        $html .= '<td align="right">'.number_format($val->bhp).'</td>';
                        $html .= '<td align="right">'.number_format($val->adm).'</td>';
                        $html .= '<td align="right">'.number_format($val->pendapatan_rs).'</td>';
                        $html .= '<td align="right">'.number_format($val->biaya_lain).'</td>';
                        $html .= '<td align="right">'.number_format($val->total).'</td>';
                        $html .= '<td align="center">'.$val->revisi_ke.'</td>';
                        $html .= '<td align="center">
                                <a href="#" onclick="show_modal_medium('."'tarif/Mst_tarif/edit_klas_tarif/".$val->kode_master_tarif_detail."'".', '."'EDIT KLAS TARIF'".')"><span class="badge badge-success"><i class="fa fa-pencil"></i></span></a>
                                <span class="badge badge-danger"><i class="fa fa-trash"></i></span>
                        </td>';
                    $html .= '</tr>';
                }
                
            }
                

            $html .= '</table>'; 
        }else{
            $html .= '<div style="border-bottom:1px solid #333;"><b>Tidak ada barang ditemukan</b></div><br>';
        }

        echo json_encode(array('html' => $html, 'data' => $data));

    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->Mst_tarif->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '';
            $row[] = '';
            $row[] = $row_list->kode_tarif;
            $row[] = $row_list->kode_tarif.' - '.ucwords($row_list->nama_tarif);
            $row[] = ucwords($row_list->jenis_tindakan);
            $row[] = ucwords($row_list->nama_bagian);
            $row[] = '<div class="center">'.$row_list->revisi_ke.'</div>';
            // $row[] = ($row_list->is_active == 'Y') ? '<div class="center"><span class="label label-sm label-success">Active</span></div>' : '<div class="center"><span class="label label-sm label-danger">Not active</span></div>';

            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Mst_tarif->count_all(),
                        "recordsFiltered" => $this->Mst_tarif->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function process()
    {
       
        $this->load->library('form_validation');
        $val = $this->form_validation;
        $val->set_rules('kode_bagian', 'Kode Bagian', 'trim|required');
        $val->set_rules('kode_klas', 'Klas Tarif', 'trim|required');
        $val->set_rules('kode_jenis_tindakan', 'Jenis Tindakan', 'trim|required');
        $val->set_rules('bill_dr1', 'Bill Dr1', 'trim|required');
        $val->set_rules('bill_dr2', 'Bill Dr2', 'trim|required');
        $val->set_rules('bill_dr3', 'Bill Dr3', 'trim|required');
        $val->set_rules('kamar_tindakan', 'Kamar Tindakan', 'trim|required');
        $val->set_rules('bhp', 'BHP', 'trim|required');
        $val->set_rules('obat', 'Obat', 'trim|required');
        $val->set_rules('alat_rs', 'Alat RS', 'trim|required');
        $val->set_rules('alkes', 'Alkes', 'trim|required');
        $val->set_rules('adm', 'Administrasi', 'trim|required');
        $val->set_rules('pendapatan_rs', 'Pendapatan RS', 'trim|required');
        $val->set_rules('total', 'Total', 'trim|required');
        
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
                'kode_bagian' => $this->regex->_genRegex($val->set_value('kode_bagian'), 'RGXQSL'),
                'kode_klas' => $this->regex->_genRegex($val->set_value('kode_klas'), 'RGXQSL'),
                'kode_jenis_tindakan' => $this->regex->_genRegex($val->set_value('kode_jenis_tindakan'), 'RGXQSL'),
                'bill_dr1' => $this->regex->_genRegex($val->set_value('bill_dr1'), 'RGXQSL'),
                'bill_dr2' => $this->regex->_genRegex($val->set_value('bill_dr2'), 'RGXQSL'),
                'bill_dr3' => $this->regex->_genRegex($val->set_value('bill_dr3'), 'RGXQSL'),
                'kamar_tindakan' => $this->regex->_genRegex($val->set_value('kamar_tindakan'), 'RGXQSL'),
                'bhp' => $this->regex->_genRegex($val->set_value('bhp'), 'RGXQSL'),
                'obat' => $this->regex->_genRegex($val->set_value('obat'), 'RGXQSL'),
                'alat_rs' => $this->regex->_genRegex($val->set_value('alat_rs'), 'RGXQSL'),
                'alkes' => $this->regex->_genRegex($val->set_value('alkes'), 'RGXQSL'),
                'adm' => $this->regex->_genRegex($val->set_value('adm'), 'RGXQSL'),
                'pendapatan_rs' => $this->regex->_genRegex($val->set_value('pendapatan_rs'), 'RGXQSL'),
                'total' => $this->regex->_genRegex($val->set_value('total'), 'RGXQSL'),
                'is_active' => $this->input->post('is_active'),
                'revisi_ke' => 1,
            );
            //print_r($dataexc);die;
            if($id==0){
                $dataexc['created_date'] = date('Y-m-d H:i:s');
                $dataexc['created_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                /*save post data*/
                $newId = $this->db->insert('mt_master_tarif');
            }else{
                $dataexc['updated_date'] = date('Y-m-d H:i:s');
                $dataexc['updated_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                /*update record*/
                $this->Mst_tarif->update(array('kode_tarif' => $id), $dataexc);
                $newId = $id;
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
            if($this->Mst_tarif->delete_by_id($toArray)){
                $this->logs->save('tmp_mst_function', $id, 'delete record', '', 'kode_tarif');
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

}

/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
