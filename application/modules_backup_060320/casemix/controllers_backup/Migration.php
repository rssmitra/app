<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
include_once (dirname(__FILE__) . "/Csm_billing_pasien.php");
class Migration extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'casemix/Migration');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            redirect(base_url().'login');exit;
        }
        /*load model*/
        $this->load->model('Migration_model', 'Migration');
        $this->load->model('Csm_billing_pasien_model', 'Csm_billing_pasien');
        /*load module*/
        $this->load->module('Templates/Templates.php');
        $this->load->module('Templates/Export_data.php');
        /*enable profiler*/
        $this->output->enable_profiler(false);
        $this->cbpModule = new Csm_billing_pasien;
    }

    public function index() { 
        /*define variable data*/
        $data = array(
            'title' => 'Costing',
            'breadcrumbs' => $this->breadcrumbs->show()
        );
        /*load view index*/
        $this->load->view('Migration/index', $data);
    }

    public function get_data()
    {
        //print_r($_GET['num']);die;
        /*get data from model*/
        if( isset($_GET['num']) AND $_GET['num'] != "" ){
            $list = $this->Migration->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $row_list) {
                $kode_bag = ($row_list->kode_bagian_keluar!=null)?$row_list->kode_bagian_keluar:$row_list->kode_bagian_masuk;
                /*get tipe RI/RJ*/
                $str_type = $this->Csm_billing_pasien->getTipeRegistrasi($kode_bag);
                $det_data = $this->Csm_billing_pasien->getDetailData($row_list->no_registrasi);
                $decode_data = json_decode($det_data);
                $no++;
                $row = array();
                $link = 'casemix/Migration';

                $status_reg = $this->Migration->cekIfExist($row_list->no_registrasi);
                $reg_data = $status_reg->row();

                $row[] = '<div class="center">
                            <label class="pos-rel">
                                <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->no_registrasi.'"/>
                                <span class="lbl"></span>
                            </label>
                          </div>';
                $row[] = $row_list->no_registrasi;
                $row[] = $str_type;
                $row[] = '';
                $row[] = '<a href="#" onclick="getMenu('."'".$link.'/editBilling/'.$row_list->no_registrasi.''."/".$str_type."'".')">'.$row_list->no_registrasi.'</a>';
                if ( $status_reg->num_rows() > 0 ) {
                    $reg_data = $status_reg->row();
                    $row[] = '<div class="center"><input type="hidden" id="'.$row_list->no_registrasi.'" class="form-control" name="no_sep['.$row_list->no_registrasi.']" value="'.$reg_data->csm_rp_no_sep.'"> '.$reg_data->csm_rp_no_sep.'  </div>';
                }else{
                    if( $row_list->no_sep == NULL || $row_list->no_sep == '' ){
                        $row[] = '<div class="center"><input type="text" id="'.$row_list->no_registrasi.'" class="form-control" name="no_sep['.$row_list->no_registrasi.']" value="0112R034"></div>';
                    }else{
                        $row[] = '<div class="center">'.$row_list->no_sep.' <input type="hidden" id="'.$row_list->no_registrasi.'" class="form-control" name="no_sep['.$row_list->no_registrasi.']" value="'.$row_list->no_sep.'"> </div>';
                    }
                }

                
                $row[] = $row_list->no_mr;
                $row[] = strtoupper($row_list->nama_pasien);
                $row[] = '<i class="fa fa-angle-double-right green"></i> '.$this->tanggal->formatDate($row_list->tgl_jam_masuk).'<br><i class="fa fa-angle-double-left red"></i> '.$this->tanggal->formatDate($row_list->tgl_jam_keluar);
                if($row_list->kode_bagian_keluar!=NULL){
                    $row[] = $row_list->nama_pegawai.'<br><span style="font-size:11px"><b>('.$row_list->bagian_keluar_field.')</b></span>';
                }else{
                    $row[] = $row_list->nama_pegawai.'<br><span style="font-size:11px"><b>('.$row_list->bagian_masuk_field.')</b></span>';
                }
                
                $row[] = '<div class="center"><input type="hidden" id="type_'.$row_list->no_registrasi.'" class="form-control" name="form_type['.$row_list->no_registrasi.']" value="'.$str_type.'">'.$str_type.'</div>';
                
                if( count($decode_data->group) > 0 ){
                    $row[] = ($status_reg->num_rows() > 0)?'<div class="center" ><i class="fa fa-check bigger-200 green"></i><br><span style="font-size:10px">By : '.$reg_data->created_by.'<br>'.$this->tanggal->formatDateTime($reg_data->created_date).'</span></div>':'<div class="center" id="status_'.$row_list->no_registrasi.'""></div>';
                }else{
                    $row[] = '<div class="center" style="color:red;font-size:11px">Pasien Belum Disubmit Kasir</div>';
                }

                if($row_list->kode_perusahaan==120){
                    if( count($decode_data->group) > 0 ){
                        $row[] = '<div class="center"><a href="#" class="btn btn-xs btn-primary" onclick="submit('.$row_list->no_registrasi.')"><i class="fa fa-arrow-to-bottom bigger-50"></i> Submit</a></div>';
                    }else{
                        $row[] = '<div class="center" style="color:red;font-size:11px">Pasien Belum Disubmit Kasir</div>';
                    }
                     
                }else{
                    $row[] = '<div class="center" style="color:red;font-size:11px">Silahkan Ubah Data Penjamin Pasien</div>';
                }

                /*if ( $status_reg->num_rows() > 0 ) {
                    $row[] = '<div class="center" id="merge_'.$row_list->no_registrasi.'""><a href="'.base_url().'casemix/Csm_billing_pasien/mergePDFFiles/'.$row_list->no_registrasi.'/'.$str_type.'" target="_blank" class="btn btn-xs btn-danger"><i class="ace-icon fa fa-pdf-file bigger-50"></i>Merge</a></div>';
                }else{
                    $row[] = '<div class="center" style="color:red" id="merge_'.$row_list->no_registrasi.'"">Waiting..</div>';
                }*/
                       
                $data[] = $row;
            }
            $recordsTotal = $this->Migration->count_all();
            $recordsFiltered = $this->Migration->count_filtered();
        }else{
            $data = array();
            $recordsTotal = 0;
            $recordsFiltered = 0;
        }
            
            
        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $recordsTotal,
                        "recordsFiltered" => $recordsFiltered,
                        "data" => $data,
                );
        //print_r($output);die;
        //output to json format
        echo json_encode($output);
    }

    public function process()
    {

        $this->load->library('form_validation');
        $val = $this->form_validation;
        $val->set_rules('no_sep', 'No.SEP', 'trim|required');
        $val->set_rules('csm_rp_tgl_masuk', 'Tanggal Masuk', 'trim');
        $val->set_rules('csm_rp_tgl_keluar', 'Tanggal Keluar', 'trim');

        $val->set_message('required', "Silahkan isi field \"%s\"");

        if ($val->run() == FALSE)
        {
            $val->set_error_delimiters('<div style="color:white">', '</div>');
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            $this->db->trans_begin();
            $no_registrasi = ($this->input->post('no_registrasi'))?$this->regex->_genRegex($this->input->post('no_registrasi'),'RGXINT'):0;

            /*get data trans pelayanan by no registrasi from sirs*/
            $sirs_data = json_decode($this->Csm_billing_pasien->getDetailData($no_registrasi));
            //echo '<pre>';print_r($sirs_data);die;
            /*cek apakah data sudah pernah diinsert ke database atau blm*/
            if( $this->Csm_billing_pasien->checkExistingData($no_registrasi) ){
                /*no action if data exist, continue to view data*/
            }else{
            /*jika data belum ada atau belum pernah diinsert, maka insert ke table*/
                /*insert data untuk pertama kali*/
                if( $sirs_data->group && $sirs_data->kasir_data && $sirs_data->trans_data )
                $this->Csm_billing_pasien->insertDataFirstTime($sirs_data, $no_registrasi);
            }

            if( $this->input->post('no_sep') ){
                /*csm_reg_pasien*/
                $dataexc = array(
                    'csm_rp_no_sep' => $this->regex->_genRegex(strtoupper($val->set_value('no_sep')), 'RGXQSL'),
                    'is_submitted' => $this->regex->_genRegex('Y', 'RGXAZ'),
                );
                $dataexc['updated_date'] = date('Y-m-d H:i:s');
                $dataexc['updated_by'] = $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL');
                $exc_qry = $this->db->update('csm_reg_pasien', $dataexc, array('no_registrasi' => $no_registrasi));
                $newId = $no_registrasi;
                $this->logs->save('csm_reg_pasien', $newId, 'update record', json_encode($dataexc));
            }
            
            $type = $this->input->post('form_type');
            $this->db->delete('csm_dokumen_export', array('no_registrasi' => $no_registrasi));
            /*created document name*/
            $createDocument = $this->Csm_billing_pasien->createDocument($no_registrasi, $type);
            //print_r($createDocument);die;
            foreach ($createDocument as $k_cd => $v_cd) {
                # code...
                $explode = explode('-', $v_cd);
                /*explode result*/
                $named = str_replace('BILL','',$explode[0]);
                $no_mr = $explode[1];
                $exp_no_registrasi = $explode[2];
                $unique_code = $explode[3];

                /*create and save download file pdf*/
                //$cbpModule = new Csm_billing_pasien;
                if( $this->cbpModule->getContentPDF($exp_no_registrasi, $named, $unique_code, 'F') ) :
                /*save document to database*/
                /*csm_reg_pasien*/
                $filename = $named.'-'.$no_mr.$exp_no_registrasi.$unique_code.'.pdf';
                
                $doc_save = array(
                    'no_registrasi' => $this->regex->_genRegex($exp_no_registrasi, 'RGXQSL'),
                    'csm_dex_nama_dok' => $this->regex->_genRegex($filename, 'RGXQSL'),
                    'csm_dex_jenis_dok' => $this->regex->_genRegex($v_cd, 'RGXQSL'),
                    'csm_dex_fullpath' => $this->regex->_genRegex('uploaded/casemix/'.$filename.'', 'RGXQSL'),
                );
                $doc_save['created_date'] = date('Y-m-d H:i:s');
                $doc_save['created_by'] = $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL');
                /*check if exist*/
                if ( $this->Csm_billing_pasien->checkIfDokExist($exp_no_registrasi, $filename) == FALSE ) {
                    $this->db->insert('csm_dokumen_export', $doc_save);
                }
                endif;
                /*insert database*/
            }
            
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'redirect' => 'casemix/Csm_billing_pasien/mergePDFFiles/'.$no_registrasi.'/'.$type.'', 'created_by' => $doc_save['created_by'], 'created_date' => $this->tanggal->formatDateTime($doc_save['created_date'])));
            }
        }
    }

    /*function for view data only*/
    public function editBilling($no_registrasi, $tipe)
    {
        /*breadcrumbs for view*/
        $this->breadcrumbs->push('Edit function', 'Migration/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$no_registrasi);
        /*define data variabel*/
        /*load form view*/
        $view_name = ($tipe=='RJ')?'form_edit':'form_edit_ri';
        $title_name = ($tipe=='RJ')?'Rawat Jalan':'Rawat Inap';
        $data['no_registrasi'] = $no_registrasi;
        $data['form_type'] = $tipe;
        $data['value'] = $this->Csm_billing_pasien->get_by_id($no_registrasi);
        $data['title'] = 'Costing '.$title_name.'';
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        //echo '<pre>';print_r($data);die;
        /*get data trans pelayanan by no registrasi from sirs*/
        $sirs_data = json_decode($this->Csm_billing_pasien->getDetailData($no_registrasi));
        //echo '<pre>';print_r($sirs_data);die;
        /*cek apakah data sudah pernah diinsert ke database atau blm*/
        if( $this->Csm_billing_pasien->checkExistingData($no_registrasi) ){
            
        }else{
        /*jika data belum ada atau belum pernah diinsert, maka insert ke table*/
            /*insert data untuk pertama kali*/
            if( $sirs_data->group && $sirs_data->kasir_data && $sirs_data->trans_data )
            $this->Csm_billing_pasien->insertDataFirstTime($sirs_data, $no_registrasi);

        }

       //echo '<pre>';print_r($dataBilling);die;
        /*no action if data exist, continue to view data*/
        $dataBilling = $this->Csm_billing_pasien->getBillingDataLocal($no_registrasi, $tipe);
        $data['reg'] = (count($dataBilling['reg_data']) > 0) ? $dataBilling['reg_data'] : [] ;
        if( $tipe=='RJ' ){
            $group = array();
            foreach ($dataBilling['billing'] as $value) {
                /*group berdasarkan nama jenis tindakan*/
                $group[$value->csm_bp_nama_jenis_tindakan][] = $value;
            }
            $data['group'] = $group;
            $data['resume'] = $dataBilling['resume'];
        }else{
            $data['content_view'] = $this->Csm_billing_pasien->getDetailBillingRI($no_registrasi, $tipe, $sirs_data);
        }

        //echo '<pre>';print_r($data);die;
        $this->load->view('Migration/'.$view_name.'', $data);
    }


    public function getDetail($no_registrasi, $tipe){
        
        /*get detail data billing*/
        $data = json_decode($this->Migration->getDetailData($no_registrasi));
        
        /*cek apakah data sudah pernah diinsert ke database atau blm*/
        if( $this->Migration->checkExistingData($no_registrasi) ){
            /*no action if data exist, continue to view data*/
        }else{
        /*jika data belum ada atau belum pernah diinsert, maka insert ke table*/
            /*insert data untuk pertama kali*/
            if( $data->group && $data->kasir_data && $data->trans_data )
            $this->Migration->insertDataFirstTime($data, $no_registrasi);
        }
        //print_r($data);die;
        if($tipe=='RJ'){
            $html = $this->Migration->getDetailBillingRJ($no_registrasi, $tipe, $data);
        }else{
            $html = $this->Migration->getDetailBillingRI($no_registrasi, $tipe, $data);
        }

        echo json_encode(array('html' => $html));
    }

    public function find_data()
    {   
        $output = array(
                        "recordsTotal" => $this->Migration->count_all(),
                        "data" => $_POST,
                );
        echo json_encode($output);
    }

    public function update_status_nk_kode_perusahaan()
    {   
        $no_registrasi = $this->input->post('no_registrasi');
        if( $this->Migration->update_status_nk_kode_perusahaan($no_registrasi) ){
            /*get data trans pelayanan by no registrasi from sirs*/
            $sirs_data = json_decode($this->Csm_billing_pasien->getDetailData($no_registrasi));
            $this->Csm_billing_pasien->insertDataFirstTime($sirs_data, $no_registrasi);

            echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan'));
        }else{
            echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
        }
    }

    public function submit_kasir()
    {   
        $no_registrasi = $this->input->post('no_registrasi');
        if( $this->Migration->submit_kasir($no_registrasi) ){
            
            /*get data trans pelayanan by no registrasi from sirs*/
            $sirs_data = json_decode($this->Csm_billing_pasien->getDetailData($no_registrasi));
            $this->Csm_billing_pasien->insertDataFirstTime($sirs_data, $no_registrasi);

            echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan'));
        }else{
            echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
        }
    }


}
/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
