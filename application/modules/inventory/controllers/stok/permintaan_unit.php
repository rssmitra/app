<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Permintaan_unit extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'inventory/distribusi/permintaan_unit');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('distribusi/permintaan_unit_model', 'permintaan_unit');
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
        $this->load->view('distribusi/permintaan_unit/index', $data);
    }

    public function form($id='')
    {
        /*if id is not null then will show form edit*/
        if( $id != '' ){
            /*breadcrumbs for edit*/
            $this->breadcrumbs->push('Edit '.strtolower($this->title).'', 'inventory/distribusi/permintaan_unit/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
            /*get value by id*/
            $data['value'] = $this->permintaan_unit->get_by_id($id);
            $data['value_detail'] = $this->permintaan_unit->get_detail_by_id($id);
            $data['value_numrow'] = $this->permintaan_unit->get_numrow_detail_by_id($id);
            /*initialize flag for form*/
            $data['flag'] = "update";
        }else{
            /*breadcrumbs for create or add row*/
            $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'inventory/distribusi/permintaan_unit/'.strtolower(get_class($this)).'/form');

            /*get no permintaan */
            $no_id = $this->master->get_max_number('tc_permintaan_inst_nm', 'id_tc_permintaan_inst');
            $bagian = $this->db->get_where('mt_bagian', array('kode_bagian' => '070101'))->row();
            $bulan = $this->tanggal->getBulanRomawi(date('m'));
            $data['no_permintaan'] = " ".$no_id."/".$bagian->nama_bagian."/".$bulan."/".date("Y")." ";

            /*initialize flag for form add*/
            $data['flag'] = "create";
        }
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        //print_r($this->session->userdata());die;
        /*load form view*/
        $this->load->view('distribusi/permintaan_unit/form', $data);
    }
    /*function for view data only*/
    public function show($id)
    {
        /*breadcrumbs for view*/
        $this->breadcrumbs->push('View '.strtolower($this->title).'', 'inventory/distribusi/permintaan_unit/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*define data variabel*/
        $data['value'] = $this->permintaan_unit->get_by_id($id);
        $data['value_detail'] = $this->permintaan_unit->get_detail_by_id($id);
        $data['title'] = $this->title;
        $data['flag'] = "read";
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('distribusi/permintaan_unit/form', $data);
    }


    public function get_data()
    {
        /*get data from model*/
        $list = $this->permintaan_unit->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '';
            $row[] = '<div class="center"><div class="hidden-sm hidden-xs action-buttons">
                        '.$this->authuser->show_button('inventory/distribusi/permintaan_unit','R',$row_list->id_tc_permintaan_inst,2).'
                        '.$this->authuser->show_button('inventory/distribusi/permintaan_unit','U',$row_list->id_tc_permintaan_inst,2).'
                      </div>
                      <div class="hidden-md hidden-lg">
                        <div class="inline pos-rel">
                            <button class="btn btn-minier btn-yellow dropdown-toggle" data-toggle="dropdown" data-position="auto"><i class="ace-icon fa fa-caret-down icon-only bigger-120"></i>
                            </button>
                            <ul class="dropdown-permintaan_unit dropdown-only-icon dropdown-yellow dropdown-permintaan_unit-right dropdown-caret dropdown-close">
                                <li>'.$this->authuser->show_button('inventory/distribusi/permintaan_unit','R','',4).'</li>
                                <li>'.$this->authuser->show_button('inventory/distribusi/permintaan_unit','U','',4).'</li>
                            </ul>
                        </div>
                    </div></div>';
            $row[] = $row_list->nomor_permintaan;
            $row[] = $this->tanggal->formatDateTime($row_list->tgl_permintaan);
            $row[] = $row_list->nama_bagian;

            if($row_list->status_selesai==0){
                $status = "Input blm selesai";
            }else if($row_list->status_selesai==1){
                $status = "Menunggu ACC";
            }else if($row_list->status_selesai==2){
                $status = "ACC";
            }else{
                $status = "Tidak ACC";
            }

            $row[] = $status;
            $row[] = $row_list->username;
                    
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->permintaan_unit->count_all(),
                        "recordsFiltered" => $this->permintaan_unit->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function process()
    {
        $this->load->library('form_validation');
        $val = $this->form_validation;
        $val->set_rules('permintaan_nm_bagian_minta', 'Bagian / Depo', 'trim|required');

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

            //print_r($_POST['jmlcell']);die;

            $dataexc = array(
                'nomor_permintaan' => $this->input->post('permintaan_nm_no'),
                'tgl_permintaan' => $this->tanggal->sqlDateFormtoDateTime($this->input->post('permintaan_nm_tanggal')),
                'kode_bagian_minta' => $this->regex->_genRegex($this->form_validation->set_value('permintaan_nm_bagian_minta'),'RGXQSL'),
                'kode_bagian_kirim' => '070101',
                'tgl_input' => date('Y-m-d H:i:s'),
                'status_selesai' => 1,
            );
            if($id==0){

                $id_tc_permintaan_inst = $this->permintaan_unit->save('tc_permintaan_inst_nm', $dataexc);

                for($i=0;$i<=$_POST['jmlcell'];$i++){
                
                    $index1="permintaan_nm_kode_brg".$i;
                    if(isset($_POST[$index1])){
                        $kode_brg_ = explode(":",$_POST[$index1]);	
                        $kode_brg[$i] = $kode_brg_[0];		
                        $check[$i]=!empty($kode_brg[$i]);
                    }
                                
                    $index2="permintaan_nm_satuan".$i;
                    if(isset($_POST[$index2]))$satuan[$i]=$_POST[$index2];
                    
                    $index3="permintaan_nm_jumlah".$i;
                    if(isset($_POST[$index3]))$jumlah[$i]=$_POST[$index3];

                    if(isset($kode_brg[$i])){    
                        $datadetail = array(
                            'id_tc_permintaan_inst' => $id_tc_permintaan_inst,
                            'jumlah_permintaan' => $jumlah[$i],
                            'kode_brg' => $kode_brg[$i],
                            'satuan' => $satuan[$i],
                            'tgl_input' => date('Y-m-d H:i:s'),
                        );
                        /*save transaksi item detail*/
                        $this->permintaan_unit->save('tc_permintaan_inst_nm_det',$datadetail);
                    }
        
                    
                }
                               
            }else{

                $this->db->delete('tc_permintaan_inst_nm_det', array('id_tc_permintaan_inst' => $id));

                $id_tc_permintaan_inst = $this->input->post('id');

                for($i=0;$i<=$_POST['jmlcell'];$i++)
                {
                    $index1="permintaan_nm_kode_brg".$i;
                    if(isset($_POST[$index1])){
                        $kode_brg_ = explode(":",$_POST[$index1]);	
                        $kode_brg[$i] = $kode_brg_[0];		
                        $check[$i]=!empty($kode_brg[$i]);
                    }
                                
                    $index2="permintaan_nm_satuan".$i;
                    if(isset($_POST[$index2]))$satuan[$i]=$_POST[$index2];
                    
                    $index3="permintaan_nm_jumlah".$i;
                    if(isset($_POST[$index3]))$jumlah[$i]=$_POST[$index3];
                    
                    if(isset($kode_brg[$i])){    
                        $datadetail = array(
                            'id_tc_permintaan_inst' => $id_tc_permintaan_inst,
                            'jumlah_permintaan' => $jumlah[$i],
                            'kode_brg' => $kode_brg[$i],
                            'satuan' => $satuan[$i],
                            'tgl_input' => date('Y-m-d H:i:s'),
                        );
                        /*save transaksi item detail*/
                        $this->permintaan_unit->save('tc_permintaan_inst_nm_det',$datadetail);
                    }

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
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan'));
            }
        }
    }

    public function delete()
    {
        $id=$this->input->post('ID')?$this->input->post('ID',TRUE):null;
        $toArray = explode(',',$id);
        if($id!=null){
            if($this->permintaan_unit->delete_by_id($toArray)){
                $this->logs->save('permintaan_unit', $id, 'delete record', '', 'id_tc_permintaan_inst');
                echo json_encode(array('status' => 200, 'message' => 'Proses Hapus Data Berhasil Dilakukan'));
            }else{
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Hapus Data Gagal Dilakukan'));
            }
        }else{
            echo json_encode(array('status' => 301, 'message' => 'Tidak ada item yang dipilih'));
        }
        
    }

    public function getDetailTransaksi($id){
        
        $data = $this->permintaan_unit->get_detail_by_id($id);

        //print_r($data);die;
        
        $html = '';

        $html .= '<b><h3>List Barang</h3></b>';
        $html .= '<table class="table table-striped">';
        $html .= '<tr>';
            $html .= '<th width="50%" class="center">Nama Barang</th>';
            $html .= '<th width="20%">Satuan*</th>';
            $html .= '<th width="10%" class="center">Jumlah</th>';
        $html .= '</tr>'; 
        $no=1;
        foreach ($data as $value_data) {
            $html .= '<tr>';
                $html .= '<td width="50%">'.$value_data->kode_brg.' : '.$value_data->nama_brg.'</td>';
                $html .= '<td width="20%">'.$value_data->satuan.'</td>';
                $html .= '<td width="10%" class="center">'.$value_data->jumlah_permintaan.'</td>';
            $html .= '</tr>';
        }

        echo json_encode(array('html' => $html));
    }


}


/* End of file Tipe Identitas.php */
/* Location: ./application/modules/permintaan_unit/controllers/permintaan_unit.php */
