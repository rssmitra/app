<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Csm_billing_pasien extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'casemix/Csm_billing_pasien');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            redirect(base_url().'login');exit;
        }
        /*load model*/
        $this->load->model('Csm_billing_pasien_model', 'Csm_billing_pasien');
        $this->load->model('ws_bpjs/Ws_index_model', 'Ws_index');
        /*load module*/
        $this->load->module('Templates/Templates.php');
        $this->load->module('Templates/Export_data.php');
        Modules::run('Templates/Export_data');
        /*enable profiler*/
        $this->output->enable_profiler(false);

    }

    public function index() { 
        /*define variable data*/
        $data = array(
            'title' => 'Billing Pasien',
            'breadcrumbs' => $this->breadcrumbs->show()
        );
        /*load view index*/
        $this->load->view('Csm_billing_pasien/index', $data);
    }

    
    /*function for view data only*/
    public function editBilling($no_registrasi, $tipe)
    {
        /*breadcrumbs for view*/
        $this->breadcrumbs->push('Edit function', 'Csm_billing_pasien/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$no_registrasi);
        /*define data variabel*/
        /*load form view*/
        $view_name = ($tipe=='RJ')?'form_edit':'form_edit_ri';
        $title_name = ($tipe=='RJ')?'Rawat Jalan':'Rawat Inap';
        $data['form_type'] = $tipe;
        $data['value'] = $this->Csm_billing_pasien->get_by_id($no_registrasi); 
        $data['title'] = 'Billing Pasien '.$title_name.'';
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        
        /*get data trans pelayanan by no registrasi from sirs*/
        $sirs_data = json_decode($this->Csm_billing_pasien->getDetailData($no_registrasi));
        //print_r($sirs_data);die;
        /*cek apakah data sudah pernah diinsert ke database atau blm*/
        if( $this->Csm_billing_pasien->checkExistingData($no_registrasi) ){
            /*no action if data exist, continue to view data*/
        }else{
        /*jika data belum ada atau belum pernah diinsert, maka insert ke table*/
            /*insert data untuk pertama kali*/
            if( $sirs_data->group && $sirs_data->kasir_data && $sirs_data->trans_data )
            $this->Csm_billing_pasien->insertDataFirstTime($sirs_data, $no_registrasi);
        }

        $dataBilling = $this->getBillingLocal($no_registrasi, $tipe);
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
        $this->load->view('Csm_billing_pasien/'.$view_name.'', $data);
    }

    public function getBillingLocal($no_registrasi, $tipe){
        return $this->Csm_billing_pasien->getBillingDataLocal($no_registrasi, $tipe);
    }

    
    public function process()
    {
        // echo '<pre>';print_r($_POST);die;

        $this->load->library('form_validation');
        $val = $this->form_validation;
        $val->set_rules('csm_rp_no_sep', 'No.SEP', 'trim|required');
        $val->set_rules('csm_rp_tgl_masuk', 'Tanggal Masuk', 'trim|required');
        $val->set_rules('csm_rp_tgl_keluar', 'Tanggal Keluar', 'trim|required');
        $val->set_message('required', "Silahkan isi field \"%s\"");

        if ($val->run() == FALSE)
        {
            $val->set_error_delimiters('<div style="color:white">', '</div>');
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            $this->db->trans_begin();
            $no_registrasi = ($this->input->post('no_registrasi_hidden'))?$this->regex->_genRegex($this->input->post('no_registrasi_hidden'),'RGXINT'):0;

            $type = $this->input->post('form_type');
            if($_POST['submit'] == 'update_dok_klaim'){
                /*created document name*/
                /*clean first data*/
                //$this->db->delete('csm_dokumen_export', array('no_registrasi' => $no_registrasi));
                
                $createDocument = $this->Csm_billing_pasien->createDocument($no_registrasi, $type);
                //echo '<pre>';print_r($createDocument);die;
                
                foreach ($createDocument as $k_cd => $v_cd) {
                    # code...
                    $explode = explode('-', $v_cd);
                    /*explode result*/
                    $named = str_replace('BILL','',$explode[0]);
                    $no_mr = $explode[1];
                    $exp_no_registrasi = $explode[2];
                    $unique_code = $explode[3];

                    /*create and save download file pdf*/
                    if( $this->getContentPDF($exp_no_registrasi, $named, $unique_code, 'F') ) :
                    /*save document to database*/
                    /*csm_reg_pasien*/
                    $filename = $named.'-'.$no_mr.$exp_no_registrasi.$unique_code.'.pdf';
                    
                    $doc_save = array(
                        'no_registrasi' => $this->regex->_genRegex($exp_no_registrasi, 'RGXQSL'),
                        'csm_dex_nama_dok' => $this->regex->_genRegex($filename, 'RGXQSL'),
                        'csm_dex_jenis_dok' => $this->regex->_genRegex($v_cd, 'RGXQSL'),
                        'csm_dex_fullpath' => $this->regex->_genRegex('uploaded/casemix/log/'.$filename.'', 'RGXQSL'),
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
            }else{
                /*csm_reg_pasien*/
                $dataexc = array(
                    'csm_rp_no_sep' => $this->regex->_genRegex($val->set_value('csm_rp_no_sep'), 'RGXQSL'),
                    'csm_rp_tgl_masuk' => $this->regex->_genRegex($val->set_value('csm_rp_tgl_masuk'), 'RGXQSL'),
                    'csm_rp_tgl_keluar' => $this->regex->_genRegex($val->set_value('csm_rp_tgl_keluar'), 'RGXQSL'),
                );

                if($no_registrasi==0){
                    $dataexc['created_date'] = date('Y-m-d H:i:s');
                    $dataexc['created_by'] = $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL');
                    $exc_qry = $this->db->insert('csm_reg_pasien', $dataexc);
                    $newId = $this->db->insert_id();
                    $this->logs->save('csm_reg_pasien', $newId, 'insert new record', json_encode($dataexc), 'no_registrasi');
                }else{
                    $dataexc['updated_date'] = date('Y-m-d H:i:s');
                    $dataexc['updated_by'] = $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL');
                    $exc_qry = $this->db->update('csm_reg_pasien', $dataexc, array('no_registrasi' => $no_registrasi));
                    $newId = $no_registrasi;
                    $this->logs->save('csm_reg_pasien', $newId, 'update record', json_encode($dataexc), 'no_registrasi');
                }

                $this->db->update('csm_reg_pasien', array('is_submitted' => 'Y') , array('no_registrasi' => $no_registrasi));
                
                // update diagnosa
                $riwayat_diagnosa = array();
                $riwayat_diagnosa['kode_icd_diagnosa'] = $_POST['diagnosa_akhir_hidden'];
                $riwayat_diagnosa['diagnosa_akhir'] = $_POST['diagnosa_akhir'];

                if(isset($_POST['kode_riwayat_hidden']) AND !empty($_POST['kode_riwayat_hidden']) ){
                    $this->db->update('th_riwayat_pasien', $riwayat_diagnosa, array('kode_riwayat' => $_POST['kode_riwayat_hidden']) );
                }else{
                    $riwayat_diagnosa['no_registrasi'] = $no_registrasi;
                    $riwayat_diagnosa['no_mr'] = $_POST['csm_rp_no_mr'];
                    $riwayat_diagnosa['nama_pasien'] = $_POST['csm_rp_nama_pasien'];
                    $riwayat_diagnosa['diagnosa_awal'] = $_POST['diagnosa_akhir'];
                    $riwayat_diagnosa['dokter_pemeriksa'] = $_POST['csm_rp_nama_dokter'];
                    $riwayat_diagnosa['tgl_periksa'] = $_POST['csm_rp_tgl_keluar'];
                    $riwayat_diagnosa['kode_bagian'] = $_POST['poliklinik'];
                    $riwayat_diagnosa['kategori_tindakan'] = 3;
                    $this->db->insert('th_riwayat_pasien', $riwayat_diagnosa );
                }

                /*update to sirs*/
                $this->Csm_billing_pasien->updateSirs($no_registrasi, $dataexc);
                
                /*insert dokumen adjusment*/
                if(isset($_FILES['pf_file'])){
                    $this->upload_file->CsmdoUploadMultiple(array(
                        'name' => 'pf_file',
                        'path' => 'uploaded/casemix/log/',
                        'ref_id' => $no_registrasi,
                        'ref_table' => 'csm_dokumen_export',
                        'flag' => 'dokumen_export',
                    ));
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
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'redirect' => base_url().'casemix/Csm_billing_pasien/mergePDFFiles/'.$no_registrasi.'/'.$type.''));
            }
            
        }
    }

    public function get_data()
    {
        /*get data from model*/
        if(isset($_GET['num'])){
            $list = $this->Csm_billing_pasien->get_datatables();
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $row_list) {
                $no++;
                $row = array();
                $link = 'casemix/Csm_billing_pasien';
                $kode_bag = ($row_list->kode_bagian_keluar!=null)?$row_list->kode_bagian_keluar:$row_list->kode_bagian_masuk;
                $str_kode_bag = substr((string)$kode_bag, 0,2);
                $str_type = ($str_kode_bag=='01' || $str_kode_bag=='02')?'RJ':'RI';
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
                $row[] = '<div class="center">'.$row_list->no_sep.'</div>';
                $row[] = $row_list->no_mr;
                $row[] = strtoupper($row_list->nama_pasien);
                $row[] = '<i class="fa fa-angle-double-right green"></i> '.$this->tanggal->formatDate($row_list->tgl_jam_masuk);
                $row[] = '<i class="fa fa-angle-double-left red"></i> '.$this->tanggal->formatDate($row_list->tgl_jam_keluar);
                $row[] = $row_list->nama_pegawai;
                $row[] = $row_list->nama_bagian;
                
                $row[] = '<div class="center">'.$str_type.'</div>';
                $status_reg = $this->Csm_billing_pasien->cekIfExist($row_list->no_registrasi);
                $row[] = ($status_reg->num_rows() > 0)?'<div class="center"><i class="fa fa-check bigger-200 green"></i></div>':'';
                       
                $data[] = $row;
            }
        }else{
            $data = array();
        }
        

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Csm_billing_pasien->count_all(),
                        //"recordsFiltered" => $this->Csm_billing_pasien->count_filtered(),
                        "data" => $data,
                );
        //print_r($output);die;
        //output to json format
        echo json_encode($output);
    }

    public function getDetail($no_registrasi, $tipe){
        
        /*get detail data billing*/
        $data = json_decode($this->Csm_billing_pasien->getDetailData($no_registrasi));
        
        /*cek apakah data sudah pernah diinsert ke database atau blm*/
        if( $this->Csm_billing_pasien->checkExistingData($no_registrasi) ){
            /*no action if data exist, continue to view data*/
        }else{
        /*jika data belum ada atau belum pernah diinsert, maka insert ke table*/
            /*insert data untuk pertama kali*/
            if( $data->group && $data->kasir_data && $data->trans_data )
            $this->Csm_billing_pasien->insertDataFirstTime($data, $no_registrasi);
        }
        //print_r($data);die;
        if($tipe=='RJ'){
            $html = $this->Csm_billing_pasien->getDetailBillingRJ($no_registrasi, $tipe, $data);
        }else{
            $html = $this->Csm_billing_pasien->getDetailBillingRI($no_registrasi, $tipe, $data);
        }

        echo json_encode(array('html' => $html));
    }

    public function find_data()
    {   
        $output = array(
                        "recordsTotal" => $this->Csm_billing_pasien->count_all(),
                        "data" => $_POST,
                );
        echo json_encode($output);
    }

    public function getHtmlData($params, $no_registrasi, $flag, $pm, $rb='',$no_kunjungan='',$flag_mcu=''){

        $temp = new Templates;
        /*header html*/
        /*get detail data billing*/
        $data = json_decode($this->Csm_billing_pasien->getDetailData($no_registrasi));
        // echo '<pre>'; print_r($data);die;
        $html = '';

       switch ($flag) {
            case 'RESUME':
                $html .= $temp->setGlobalHeaderTemplate();
                $html .= $temp->setGlobalProfileRekamMedis($data);
                $html .= $temp->setGlobalContentBilling($temp->TemplateResumeMedis($no_registrasi, $flag, $data));
                $html .= $temp->setGlobalFooterRm($data);
                break;
            case 'RJ':
                $html .= $temp->setGlobalHeaderTemplate();
                $html .= $temp->setGlobalProfilePasienTemplate($data);
                $html .= $temp->setGlobalContentBilling($temp->TemplateBillingRJ($no_registrasi, $flag, $data));
                $html .= $temp->setGlobalFooterBilling($data);
                break;
            case 'RI':
                $html .= $temp->setGlobalHeaderTemplate();
                $html .= $temp->setGlobalProfilePasienTemplateRI($data);
                $html .= $temp->setGlobalContentBilling($temp->TemplateBillingRI($no_registrasi, $flag, $data, $rb));
                $html .= $temp->setGlobalFooterBillingRI($data);
                break;
            case 'RAD':
                $data_pm = $this->Pl_pelayanan_pm->get_by_no_kunjungan($no_kunjungan,$flag_mcu);
                // echo '<pre>'; print_r($this->db->last_query());die;
                // echo '<pre>'; print_r($data_pm);die;
                $html .= $temp->setGlobalHeaderTemplate();
                $html .= $temp->setGlobalProfilePasienTemplatePM($data, $flag, $pm, $data_pm);
                $html .= $temp->setGlobalContentBilling($temp->TemplateHasilPM($no_registrasi, $flag, $data, $pm, $flag_mcu, $data_pm));
                $html .= $temp->setGlobalFooterBillingPM($data->reg_data->nama_pegawai, $flag, $pm);
                break;
                
            case 'LAB':
                $data_pm = $this->Pl_pelayanan_pm->get_by_no_kunjungan($no_kunjungan,$flag_mcu);
                
                $template_html = $temp->TemplateHasilPM($no_registrasi, $flag, $data, $pm, $flag_mcu, $data_pm);
                $html .= $temp->setGlobalHeaderTemplate();
                $html .= $temp->setGlobalProfilePasienTemplatePM($data, $flag, $pm, $data_pm);
                $html .= $temp->setGlobalContentBilling($template_html);
                $html .= $temp->setGlobalFooterBillingPM($data->reg_data->nama_pegawai, $flag, $pm);
                break;

            case 'SEP':
                $row_sep = $this->Ws_index->findSep($data->reg_data->no_sep);
                $header = $this->Csm_billing_pasien->get_header_data($no_registrasi);
                // echo '<pre>'; print_r($header);die;
                $cetakan_ke = $this->Ws_index->count_sep_by_day();
                $result = array('sep'=>$row_sep->response, 'cetakan_ke' => $cetakan_ke, 'header' => $header);
                $html .= $this->load->view('casemix/Csm_billing_pasien/preview_sep', $result, true);
                break;
            
            default:
                # code...
                break;
        }
        
        return json_encode( array('html' => $html, 'data' => $data) );
    }

    public function getRincianBilling($noreg, $tipe, $field){
        $temp = new Templates;
        /*header html*/
        $html = '';
        $html .= $temp->TemplateRincianRI($noreg, $tipe, $field);
        
        echo json_encode(array('html' => $html));
    }

    public function getRincianBillingData($noreg, $tipe, $field){
        $temp = new Templates;
        /*header html*/
        $html = '';
        $html .= $temp->TemplateRincianRI($noreg, $tipe, $field);
         
        return json_encode(array('html' => $html));
    }

    public function getContentPDF($no_registrasi, $flag, $pm, $act_code=''){

      /*get content data*/
      $data = $this->getBillingLocal($no_registrasi, $flag); 

      /*get content html*/
      $html = json_decode( $this->getHtmlData($data, $no_registrasi, $flag, $pm) );
    //   if($flag=='RESUME') {
    //     print_r($html);die;
    //   }
      /*generate pdf*/
      $this->exportPdf($html, $flag, $pm, $act_code); 
      
      return true;

    }

    public function exportPdf($data, $flag, $pm, $act_code='') { 
        
        $this->load->library('pdf');
        $reg_data = $data->data->reg_data;
        /*default*/
        $action = ($act_code=='')?'I':$act_code;
        /*filename and title*/
        $filename = $flag.'-'.$reg_data->no_mr.$reg_data->no_registrasi.$pm;
        // print_r($reg_data);die;
        
        $tanggal = new Tanggal();
        $pdf = new TCPDF('P', PDF_UNIT, array(470,280), true, 'UTF-8');
        $pdf->SetCreator(PDF_CREATOR);
        
        $pdf->SetAuthor(COMP_LONG);
        $pdf->SetTitle(''.$filename.'');

        // remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

        // set margins
        $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT,PDF_MARGIN_BOTTOM);

        // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

        // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    
        // auto page break //
        $pdf->SetAutoPageBreak(TRUE, 30);

            //set page orientation
        
        // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once(dirname(__FILE__).'/lang/eng.php');
            $pdf->setLanguageArray($l);
        }
        
        $pdf->SetFont('helvetica', '', 9);
        $pdf->ln();


        //kotak form
        if(in_array($flag, array('SEP') )){
            $pdf->AddPage('L', 'A5');
        }else{
            $pdf->AddPage('P', 'A4');
        }

        //$pdf->setY(10);
        $pdf->setXY(5,20,5,5);
        $pdf->SetMargins(10, 10, 10, 10); 
        /* $pdf->Cell(150,42,'',1);*/
        $html = <<<EOD
        <link rel="stylesheet" href="'.file_get_contents(_BASE_PATH_.'/assets/css/bootstrap.css)'" />
EOD;
        $html .= $data->html;
        
        $result = $html;

        // output the HTML content
        $pdf->writeHTML($result, true, false, true, false, '');

        /*save to folder*/
        $pdf->Output('uploaded/casemix/log/'.$filename.'.pdf', ''.$action.''); 

        if( in_array($flag, array('RESUME','LAB','RAD', 'SEP') )){
            //kotak form
            
            // update file emr pasien
            $file_name_merge_emr = 'EMR-'.$reg_data->no_registrasi.'-'.date('Ymd');
            $this->Csm_billing_pasien->saveEmr($file_name_merge_emr, $reg_data);
            
            // create directory no_mr
            $pdf->Output('uploaded/rekam_medis/log/'.$filename.'.pdf', ''.$action.''); 
            
        }

        

        /*show pdf*/
        //$pdf->Output(''.$reg_data->no_registrasi.'.pdf', 'I'); 
        /*download*/
        //$pdf->Output(''.$reg_data->no_registrasi.'.pdf', 'D'); 
        
    }

    public function mergePDFFiles($no_registrasi, $tipe){
        /*get doc*/

        $reg_data = $this->Csm_billing_pasien->getRegDataLocal($no_registrasi);
        // echo '<pre>';print_r($reg_data);die;
        $doc_pdf = $this->Csm_billing_pasien->getDocumentPDF($no_registrasi);
        // echo '<pre>';print_r($doc_pdf);die;
        /*save merged file*/
        $month_saved = date("M",strtotime($reg_data->csm_rp_tgl_masuk));
        $year_saved = date("Y",strtotime($reg_data->csm_rp_tgl_masuk));
        $datasaved = array(
            'no_registrasi' => $no_registrasi,
            'tgl_transaksi_kasir' => $reg_data->csm_rp_tgl_keluar,
            'no_sep' => $reg_data->csm_rp_no_sep,
            'csm_dk_filename' => $reg_data->csm_rp_no_sep.'.pdf',
            'csm_dk_fullpath' => 'uploaded/casemix/merge-'.$month_saved.'-'.$year_saved.'/'.$tipe.'/'.$reg_data->csm_rp_no_sep.'.pdf',
            'csm_dk_total_klaim' => (int)$this->Csm_billing_pasien->getTotalBilling($no_registrasi, $tipe),
            'csm_dk_tipe' => $tipe,
            'created_date' => date('Y-m-d H:i:s'),
            'created_by' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')
            );
            /*check if exist*/
            $dt = $this->db->get_where('csm_dokumen_klaim', array('no_sep' => $reg_data->csm_rp_no_sep, 'no_registrasi' => $no_registrasi))->row();
            // echo '<pre>';print_r($this->db->last_query());die;

            if( !empty($dt) ){
                $this->db->update('csm_dokumen_klaim', $datasaved, array('no_sep' => $reg_data->csm_rp_no_sep));
            }else{
                $this->db->insert('csm_dokumen_klaim', $datasaved);
            }

        $fields_string = "";
        foreach($doc_pdf as $key=>$value) {
            $month = date("M",strtotime($value->csm_rp_tgl_masuk));
            $year = date("Y",strtotime($value->csm_rp_tgl_masuk));
            $fields_string .= $value->csm_dex_id.'='.$value->csm_dex_nama_dok.'&sep='.$value->csm_rp_no_sep.'&tipe='.$tipe.'&month='.$month.'&year='.$year.'&';
        }

        rtrim($fields_string,'&');
        $url = base_url().'ApiMerge/index.php?action=download&no_mr='.$reg_data->csm_rp_no_mr.'&noreg='.$no_registrasi.'&'.$fields_string;
        header("Location:".$url);
    }

    public function rollback_kasir(){
        $exc = $this->Csm_billing_pasien->rollback_kasir( $_POST['no_reg'] );
        
        echo json_encode( array('status' => 200) );
    }

}
/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
