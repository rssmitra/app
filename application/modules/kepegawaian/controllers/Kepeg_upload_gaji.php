<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Kepeg_upload_gaji extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'kepegawaian/Kepeg_upload_gaji');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('Kepeg_upload_gaji_model', 'Kepeg_upload_gaji');
        /*enable profiler*/
        $this->output->enable_profiler(false);
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'Title';

    }

    public function index() { 
        //echo '<pre>';print_r($this->session->all_userdata());
        /*define variable data*/
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show()
        );
        /*load view index*/
        $this->load->view('Kepeg_upload_gaji/index', $data);
    }

    public function form($id='')
    {
        /*if id is not null then will show form edit*/
        /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Edit '.strtolower($this->title).'', 'Kepeg_upload_gaji/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*get value by id*/
        $data['value'] = $this->Kepeg_upload_gaji->get_by_id($id);
        /*initialize flag for form*/
        $data['flag'] = "update";
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        // echo '<pre>'; print_r($data);die;
        /*load form view*/
        $this->load->view('Kepeg_upload_gaji/form', $data);
    }
    
    /*function for view data only*/
    public function show($id)
    {
        /*breadcrumbs for view*/
        $this->breadcrumbs->push('View '.strtolower($this->title).'', 'Kepeg_upload_gaji/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*define data variabel*/
        $data['value'] = $this->Kepeg_upload_gaji->get_by_id($id);
        $data['title'] = $this->title;
        $data['flag'] = "read";
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Kepeg_upload_gaji/form', $data);
    }

    public function show_detail( $id )
    {   

        $data = $this->db->join('kepeg_gaji', 'kepeg_gaji.kg_id=kepeg_rincian_gaji.kg_id','left')->get_where('kepeg_rincian_gaji', array('kepeg_rincian_gaji.kg_id' => $id))->result();
        $response = [];
        $response['value'] = $data;
        // echo '<pre>'; print_r($response);die;
        $html = $this->load->view('Kepeg_upload_gaji/rincian_gaji_view', $response, true);   

        echo json_encode( array('html' => $html) );
    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->Kepeg_upload_gaji->get_datatables();
        
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center">
                        <label class="pos-rel">
                            <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->kg_id.'"/>
                            <span class="lbl"></span>
                        </label>
                      </div>';
            $row[] = '';
            $row[] = $row_list->kg_id;
            $row[] = 'GAJI PERIODE '.strtoupper($this->tanggal->getBulan($row_list->kg_periode_bln)).' / '.$row_list->kg_periode_thn;
            $row[] = $this->tanggal->formatDate($row_list->created_date);
            $row[] = $row_list->created_by;
            $row[] = $row_list->kg_deskripsi;
            $row[] = '<div class="center">'.$row_list->kg_total_pegawai.' (Pegawai)</div>';
            $row[] = '<div style="text-align: right">'.number_format($row_list->kg_total_gaji).'</div>';
            $row[] = '<div class="center">
                        <a href="#" style="width: 100% !important" class="label label-xs label-success" onclick="getMenu('."'kepegawaian/Kepeg_upload_gaji/form/".$row_list->kg_id."'".')"><i class="fa fa-pencil"></i> Update Data </a>
                    </div>';
                   
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Kepeg_upload_gaji->count_all(),
                        "recordsFiltered" => $this->Kepeg_upload_gaji->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function process()
    {
        // echo '<pre>';print_r($_FILES);die;
        $this->load->library('form_validation');
        $val = $this->form_validation;
        $val->set_rules('nama_petugas', 'Nama Petugas', 'trim|required');
        $val->set_rules('tgl_upload', 'Tgl Upload', 'trim|required');
        $val->set_rules('kg_periode_bln', 'Bulan', 'trim|required');
        $val->set_rules('kg_periode_thn', 'Tahun', 'trim|required');
        $val->set_rules('kg_deskripsi', 'Keterangan', 'trim|required');
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
                'kg_periode_bln' => $this->regex->_genRegex($this->input->post('kg_periode_bln',TRUE),'RGXQSL'),
                'kg_periode_thn' => $this->regex->_genRegex($this->input->post('kg_periode_thn',TRUE),'RGXQSL'),
                'kg_deskripsi' => $this->regex->_genRegex($this->input->post('kg_deskripsi',TRUE),'RGXQSL'),
            );

            if($id==0){
                $dataexc['created_date'] = date('Y-m-d H:i:s');
                $dataexc['created_by'] = $this->regex->_genRegex($this->input->post('nama_petugas'),'RGXQSL');
                $newId = $this->Kepeg_upload_gaji->save('kepeg_gaji', $dataexc);
            }else{
                $dataexc['updated_date'] = date('Y-m-d H:i:s');
                $dataexc['updated_by'] = $this->regex->_genRegex($this->input->post('nama_petugas'),'RGXQSL');
                $newId = $this->Kepeg_upload_gaji->update('kepeg_gaji', array('kg_id' => $id), $dataexc);
                $newId = $id;
            }
            
            if(isset($_FILES['file']['name'])){
                $filename = $this->upload_file->doUpload('file', PATH_TMP_FILE);
                if($filename != false){
                    // delete old data
                    $this->db->delete('kepeg_rincian_gaji', array('kg_id' => $newId));
                    // insert data
                    $result = $this->import_excel($newId, $filename);
                    if($result == false){
                        echo json_encode(array('status' => 301, 'message' => 'Format file tidak sesuai!'));        
                    }
                }else{
                    echo json_encode(array('status' => 301, 'message' => 'File tidak tersimpan'));    
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
        $id=$this->input->post('ID')?$this->regex->_genRegex($this->input->post('ID',TRUE),'RGXQSL'):null;
        $toArray = explode(',',$id);
        if($id!=null){
            if($this->Kepeg_upload_gaji->delete_by_id($toArray)){
                $this->db->where_in('kg_id', $toArray)->delete('kepeg_rincian_gaji');
                echo json_encode(array('status' => 200, 'message' => 'Proses Hapus Data Berhasil Dilakukan'));

            }else{
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Hapus Data Gagal Dilakukan'));
            }
        }else{
            echo json_encode(array('status' => 301, 'message' => 'Tidak ada item yang dipilih'));
        }
        
    }

    public function import_excel($id, $filename){
        // Load plugin PHPExcel nya
        include APPPATH.'third_party/PHPExcel/PHPExcel.php';
        $excelreader = new PHPExcel_Reader_Excel2007();
        $loadexcel = $excelreader->load('uploaded/temp/'.$filename.'');
        $sheet = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);
        
        // Buat sebuah variabel array untuk menampung array data yg akan kita insert ke database
        $data = [];

        $numrow = 1;
        foreach($sheet as $row){
            // Cek $numrow apakah lebih dari 1
            // Artinya karena baris pertama adalah nama-nama kolom
            // Jadi dilewat saja, tidak usah diimport
            if($numrow > 6){
                // Kita push (add) array data ke variabel data
                if(!empty($row['B'])){
                    if(!empty($row['D'])){
                        array_push($data, [
                                    'kg_id'                 => $id, 
                                    'nip'                   => $row['B'],
                                    'nama_pegawai'          => $row['C'],
                                    'gaji_dasar'            => $row['D'],
                                    't_keluarga'            => $row['E'],
                                    't_kerja'               => $row['F'],
                                    't_jabatan'             => $row['G'],
                                    't_shift'               => $row['H'],
                                    't_khusus'              => $row['I'],
                                    't_fungsional'          => $row['J'],
                                    'jml_gaji'              => $row['K'],
                                    'lain_lain'             => $row['L'],
                                    'lembur'                => $row['M'],
                                    'insentif'              => $row['N'],
                                    'dkk'                   => $row['P'],
                                    'cito'                  => $row['Q'],
                                    'case_manager'          => $row['R'],
                                    'oncall'                => $row['S'],
                                    'transport'             => $row['T'],
                                    'pjgk_prwt'             => $row['U'],
                                    'home_care'             => $row['V'],
                                    'fee_agent'             => $row['W'],
                                    'ttl_pendapatan'        => $row['X'],
                                    'p_absensi'             => $row['Y'],
                                    'p_ppni'                => $row['Z'],
                                    'p_biaya_perawatan'     => $row['AA'],
                                    'p_apotik'              => $row['AB'],
                                    'p_koperasi'            => $row['AC'],
                                    'p_jamsostek'           => $row['AD'],
                                    'p_pph21'               => $row['AE'],
                                    'p_bpjs'                => $row['AF'],
                                    'total_potongan'        => $row['AG'],
                                    'gaji_diterima'         => $row['AH'],
                                    ]);
                        }
                        // arr gaji
                        $arrTotalGaji[] = $row['AF'];
                    }
                }
                

            $numrow++; // Tambah 1 setiap kali looping
        }

        if( $this->db->insert_batch('kepeg_rincian_gaji', $data) ){
            $result = array(
                'kg_total_pegawai' => count($data),
                'kg_total_gaji' => array_sum($arrTotalGaji)
            );

            // update data header
            $this->Kepeg_upload_gaji->update('kepeg_gaji', array('kg_id' => $id), $result);
            $this->db->trans_commit();
            return true;
            
        }else{
            return false;
        }
    }

    public function show_detail_row()
    {
        /*define data variabel*/
        $data['value'] = $this->Kepeg_upload_gaji->get_datail_row();
        $data['title'] = $this->title;
        /*load form view*/
        $this->load->view('Kepeg_upload_gaji/view_rincian_gaji', $data);
    }


}


/* End of file example.php */
/* Location: ./application/modules/example/controllers/example.php */
