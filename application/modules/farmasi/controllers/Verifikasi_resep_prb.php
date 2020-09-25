<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Verifikasi_resep_prb extends MX_Controller {

    /*function constructor*/
    function __construct() 
    {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'farmasi/Verifikasi_resep_prb');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('Etiket_obat_model', 'Etiket_obat');
        $this->load->model('Entry_resep_racikan_model', 'Entry_resep_racikan');
        $this->load->model('Verifikasi_resep_prb_model', 'Verifikasi_resep_prb');
        $this->load->model('ws_bpjs/Ws_index_model', 'Ws_index');
        $this->load->model('Retur_obat_model', 'Retur_obat');
        // load library
        $this->load->library('Print_direct');
        $this->load->library('Print_escpos'); 
        // load module
        $this->load->module('Templates/Templates.php');

        /*enable profiler*/
        $this->output->enable_profiler(false);
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'Title';

    }

    public function index() 
    { 
        /*define variable data*/
        $data = array(
            'title' => $this->title ,
            'breadcrumbs' => $this->breadcrumbs->show(),
        );
        /*load view index*/
        $this->load->view('Verifikasi_resep_prb/index', $data);
    }

    public function form($id='')
    {
        /*if id is not null then will show form edit*/
        /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Entry Resep '.strtolower($this->title).'', 'Verifikasi_resep_prb/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*get value by id*/
        $data['value'] = $this->Etiket_obat->get_by_id($id);
        $resep_log = $this->Etiket_obat->get_detail_resep_data($id)->result_array();
        foreach($resep_log as $row){
            $racikan = ($row['flag_resep']=='racikan') ? $this->Entry_resep_racikan->get_detail_by_id($row['relation_id']) : [] ;
            $row['racikan'][] = $racikan;
            $getData[] = $row;
        }
        $data['detail_obat'] = $getData;
        
        // echo '<pre>';print_r($data);die;
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Verifikasi_resep_prb/form', $data);
    }

    public function get_detail($id){
        $flag = $_GET['flag'];
        
        $data = array(
            'title' => 'Preview Transaksi' ,
            'breadcrumbs' => $this->breadcrumbs->show(),
            'flag' => $_GET['flag']
        );
        $data['value'] = $this->Etiket_obat->get_by_id($id);
        $detail_log = $this->Verifikasi_resep_prb->get_detail($id);
        $data['resep'] = $detail_log;
        
        // echo '<pre>';print_r($data);die;
        $temp_view = $this->load->view('farmasi/Verifikasi_resep_prb/detail_table_view', $data, true);
        echo json_encode( array('html' => $temp_view) );
    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->Verifikasi_resep_prb->get_datatables();
        if(isset($_GET['search']) AND $_GET['search']==TRUE){
            $this->find_data(); exit;
        }
        $data = array();
        $no = $_POST['start'];
        $atts = array('class' => 'btn btn-xs btn-warning','width'       => 900,'height'      => 500,'scrollbars'  => 'no','status'      => 'no','resizable'   => 'no','screenx'     => 1000,'screeny'     => 80,'window_name' => '_blank'
            );

        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center">'.$no.'</div>';
            $row[] = '';
            $row[] = $row_list->kode_trans_far;
            $row[] = '<div class="center"><a href="#" onclick="getMenu('."'farmasi/Verifikasi_resep_prb/form/".$row_list->kode_trans_far."'".')">'.$row_list->kode_trans_far.'</a></div>';
            $row[] = '<div class="left">'.$row_list->no_sep.'</div>';
            $row[] = $this->tanggal->formatDateTime($row_list->tgl_trans);
            $row[] = '<div class="center">'.$row_list->no_mr.'</div>';
            $row[] = strtoupper($row_list->nama_pasien);
            $row[] = $row_list->dokter_pengirim;
            $row[] = $row_list->nama_pelayanan;
            $status = ($row_list->verifikasi_prb==NULL)?'<label class="label label-warning">Dalam Proses</label>':'<a href="#" onclick="getMenu('."'farmasi/Verifikasi_resep_prb/preview_verifikasi/".$row_list->kode_trans_far."?flag=RJ'".')"><label class="label label-success" style="cursor: pointer"> <i class="fa fa-check-circle"></i> Verify</label></a>';
            $row[] = '<div class="center">'.$status.'</div>';
            // $row[] = '<div class="center">
            //             <a href="#" onclick="getMenu('."'farmasi/Verifikasi_resep_prb/form/".$row_list->kode_trans_far."'".')" class="btn btn-xs btn-primary" title="etiket">
            //               <i class="fa fa-check-square-o"></i> Verifikasi
            //             </a>
            //           </div>';
            
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Verifikasi_resep_prb->count_all(),
                        "recordsFiltered" => $this->Verifikasi_resep_prb->count_filtered(),
                        "data" => $data,
        );
        //output to json format
        echo json_encode($output);
    }

    public function process()
    {
        // print_r($_POST);die;
        $this->load->library('form_validation');
        // form validation

        $this->form_validation->set_rules('kode_trans_far', 'Kode Transaksi', 'trim|required');

        // set message error
        $this->form_validation->set_message('required', "Silahkan isi field \"%s\"");        

        if ($this->form_validation->run() == FALSE)
        {
            $this->form_validation->set_error_delimiters('<div style="color:white"><i>', '</i></div>');
            //die(validation_errors());
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            /*execution*/
            $this->db->trans_begin();
            
            // selected id
            foreach( $_POST['kode_brg'] as $key => $row ){
                // data master barang
                $dt_brg = $this->db->get_where('mt_barang', array('kode_brg' => $row) )->row();
                // data existing
                $dt_existing = $this->db->get_where('fr_tc_far_detail_log_prb', array('kode_brg' => $row, 'kode_trans_far' => $_POST['kode_trans_far']) )->row();

                $data_farmasi = array(
                    'no_sep' => isset($_POST['no_sep'])?$this->regex->_genRegex($_POST['no_sep'], 'RQXINT'):0,
                    'kode_trans_far' => isset($_POST['kode_trans_far'])?$this->regex->_genRegex($_POST['kode_trans_far'], 'RQXINT'):0,
                    'tgl_input' => date('Y-m-d H:i:s'),
                    'kode_brg' => isset($row)?$this->regex->_genRegex($row, 'RGXQSL'):0,
                    'nama_brg' => $dt_brg->nama_brg,
                    'satuan_kecil' => $dt_brg->satuan_kecil,
                    'jumlah' =>  isset($_POST['jumlah_'.$row.''])?$this->regex->_genRegex($_POST['jumlah_'.$row.''], 'RQXINT'):0,
                    'harga_satuan' =>  isset($_POST['harga_jual_'.$row.''])?$this->regex->_genRegex($_POST['harga_jual_'.$row.''], 'RQXINT'):0,
                    'sub_total' =>  isset($_POST['sub_total_'.$row.''])?$this->regex->_genRegex($_POST['sub_total_'.$row.''], 'RQXINT'):0,
                );

                if( count($dt_existing) > 0 ){
                    /*update existing*/
                    $data_farmasi['updated_date'] = date('Y-m-d H:i:s');
                    $data_farmasi['updated_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                    $this->db->update('fr_tc_far_detail_log_prb', $data_farmasi, array('id_fr_tc_far_detail_log_prb' => $dt_existing->id_fr_tc_far_detail_log_prb) );
                    /*save log*/
                    $this->logs->save('fr_tc_far_detail_log_prb', $dt_existing->id_fr_tc_far_detail_log_prb, 'update record on verifikasi obat prb module', json_encode($data_farmasi),'id_fr_tc_far_detail_log_prb');
                
                }else{    
                    $data_farmasi['created_date'] = date('Y-m-d H:i:s');
                    $data_farmasi['created_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                    // print_r($data_farmasi);die;
    
                    $this->db->insert( 'fr_tc_far_detail_log_prb', $data_farmasi );
                    $newId = $this->db->insert_id();
                    /*save log*/
                    $this->logs->save('fr_tc_far_detail_log_prb', $newId, 'insert new record on verifikasi obat prb module', json_encode($data_farmasi),'id_fr_tc_far_detail_log_prb');
    
                }

                $this->db->trans_commit();
                
            }
            
            // update fr_tc_far
            $this->db->update('fr_tc_far', array('verifikasi_prb' => 1), array('kode_trans_far' => $_POST['kode_trans_far']) );
            // set dokumen
            $this->merge_dokumen_klaim($_POST['kode_trans_far']);

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

    public function find_data()
    {   
        $output = array( "data" => http_build_query($_POST) . "\n" );
        echo json_encode($output);
    }

    function preview_verifikasi($kode_trans_far){

        /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Preview Transaksi Farmasi', 'Entry_resep_ri_rj/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$kode_trans_far);

        $data = array(
            'title' => 'Preview Transaksi' ,
            'breadcrumbs' => $this->breadcrumbs->show(),
            'flag' => $_GET['flag']
        );
        $data['value'] = $this->Etiket_obat->get_by_id($kode_trans_far);
        $detail_log = $this->Verifikasi_resep_prb->get_detail($kode_trans_far);
        $data['resep'] = $detail_log;

        // echo '<pre>'; print_r($data);die;
        $this->load->view('farmasi/Verifikasi_resep_prb/preview_verifikasi', $data);

    }

    function nota_farmasi($kode_trans_far){
        $data = array(
            'title' => 'Preview Transaksi' ,
            'breadcrumbs' => $this->breadcrumbs->show(),
            'flag' => $_GET['flag']
        );
        $data['value'] = $this->Etiket_obat->get_by_id($kode_trans_far);
        $detail_log = $this->Verifikasi_resep_prb->get_detail($kode_trans_far);
        $data['resep'] = $detail_log;
        // echo '<pre>'; print_r($data);die;
        $this->load->view('farmasi/Verifikasi_resep_prb/preview_nota_farmasi', $data);

    }

    public function merge_dokumen_klaim($kode_trans_far){
        // create dokumen
        $createDocument = $this->createDocument($kode_trans_far);
        // echo '<pre>';print_r($createDocument);die;
        foreach ($createDocument as $k_cd => $v_cd) {
            # code...
            $explode = explode('-', $v_cd);
            /*explode result*/
            $named = $explode[0];
            $no_sep = $explode[1];
            $kode_trans_far = $explode[2];

            /*create and save download file pdf*/
            if( $this->getContentPDF($named, $no_sep, $kode_trans_far, 'F') ) :
                /*save document to database*/
                /*csm_reg_pasien*/
                $filename = $named.'-'.$no_sep.'-'.$kode_trans_far.'.pdf';
                
                $doc_save = array(
                    'kode_trans_far' => $this->regex->_genRegex($kode_trans_far, 'RGXINT'),
                    'dok_prb_file_name' => $this->regex->_genRegex($filename, 'RGXQSL'),
                    'dok_prb_file_type' => $this->regex->_genRegex($named, 'RGXQSL'),
                    'dok_prb_fullpath' => $this->regex->_genRegex('uploaded/farmasi/log/'.$filename.'', 'RGXQSL'),
                );
                $doc_save['created_date'] = date('Y-m-d H:i:s');
                $doc_save['created_by'] = $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL');
                /*check if exist*/
                if ( $this->Verifikasi_resep_prb->checkIfDokExist($kode_trans_far, $filename) == FALSE ) {
                    $this->db->insert('fr_tc_far_dokumen_klaim_prb', $doc_save);
                }
            endif;
            /*insert database*/
        }

        return array('redirect' => 'farmasi/Verifikasi_resep_prb/mergePDFFiles/'.$kode_trans_far.'/'.$no_sep.'', 'created_by' => $doc_save['created_by'], 'created_date' => $this->tanggal->formatDateTime($doc_save['created_date']));

    }

    public function mergePDFFiles($kode_trans_far, $no_sep){
        /*get doc*/

        $doc_pdf = $this->Verifikasi_resep_prb->getDocumentPDF($kode_trans_far);
        /*save merged file*/
        $fields_string = "";
        foreach($doc_pdf as $key=>$value) {
            // update status mergeing
            $this->db->update('fr_tc_far_dokumen_klaim_prb', array('dok_prb_status_merge' => 1), array('dok_prb_id' => $value->dok_prb_id) );
            // define
            $month = date("M",strtotime($value->tgl_trans));
            $year = date("Y",strtotime($value->tgl_trans));
            $fields_string .= $value->dok_prb_id.'='.$value->dok_prb_file_name.'&sep='.$no_sep.'&tipe='.$value->dok_prb_file_type.'&month='.$month.'&year='.$year.'&';
        }

        rtrim($fields_string,'&');
        $url = base_url().'ApiMerge/index_farmasi.php?action=download&&kode='.$kode_trans_far.'&'.$fields_string;
        header("Location:".$url);
    }

    public function getHtmlData($data, $named, $no_sep, $kode_trans_far){

        $temp = new Templates;
        /*header html*/
        $header = $this->Verifikasi_resep_prb->get_header_data($kode_trans_far);
        $html = '';

        switch ($named) {

            case 'NOTA':
                $result = array();
                $result['value'] = $header;
                $result['resep'] = $data;
                $html .= $this->load->view('farmasi/Verifikasi_resep_prb/preview_nota_farmasi', $result, true);
            break;

            case 'COPY_RESEP':
                $resep_log = $this->db->join('mt_master_pasien','mt_master_pasien.no_mr=fr_tc_far.no_mr','left')->join('mt_bagian','mt_bagian.kode_bagian=fr_tc_far.kode_bagian_asal','left')->get_where('fr_tc_far', array('kode_trans_far' => $kode_trans_far) )->row();
                $result = array();
                $result['result'] = $resep_log;
                $html .= $this->load->view('farmasi/Verifikasi_resep_prb/preview_copy_resep_w_header_n_footer', $result, true);
            break;

            case 'SEP':
                /*data sep*/
                $row_sep = $this->Ws_index->findSep($no_sep);
                // echo '<pre>'; print_r($row_sep);die;
                $cetakan_ke = $this->Ws_index->count_sep_by_day();
                $result = array('sep'=>$row_sep->response, 'cetakan_ke' => $cetakan_ke, 'header' => $header);
                $html .= $this->load->view('farmasi/Verifikasi_resep_prb/preview_sep', $result, true);
            break;
            
            
            default:
                # code...
                break;
        }
        // echo '<pre>'; echo($html); die;
        return json_encode( array('html' => $html) );
    }

    public function getContentPDF($named, $no_sep, $kode_trans_far, $act_code=''){

        /*get content data*/
        $data = $this->Verifikasi_resep_prb->get_detail($kode_trans_far);
        /*get content html*/
        $html = json_decode( $this->getHtmlData($data, $named, $no_sep, $kode_trans_far) );
        /*generate pdf*/
        $this->exportPdf($html, $named, $no_sep, $kode_trans_far, $act_code); 
        
        return true;

    }

    public function createDocument($kode_trans_far){

        /*get data*/
        $data = $this->Etiket_obat->get_by_id($kode_trans_far);
        $filename[] ='NOTA-'.$data->no_sep.'-'.$kode_trans_far;
        $filename[] ='COPY_RESEP-'.$data->no_sep.'-'.$kode_trans_far;
        $filename[] ='SEP-'.$data->no_sep.'-'.$kode_trans_far;

        return $filename;

    }
   

    public function exportPdf($data, $named, $no_sep, $kode_trans_far, $act_code) { 
        
        $this->load->library('pdf');
        /*default*/
        $action = ($act_code=='')?'I':$act_code;
        /*filename and title*/
        $filename = $named.'-'.$no_sep.'-'.$kode_trans_far;
        
        $tanggal = new Tanggal();
        $pdf = new TCPDF('P', PDF_UNIT, array(470,280), true, 'UTF-8');
        // print_r($flag);die;
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
        if(in_array($named, array('NOTA', 'COPY_RESEP') )){
            $pdf->AddPage('P', 'A5');
        }else{
            $pdf->AddPage('L', 'A5');
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
        $pdf->Output('uploaded/farmasi/log/'.$filename.'.pdf', ''.$action.''); 

        /*show pdf*/
        //$pdf->Output(''.$reg_data->no_registrasi.'.pdf', 'I'); 
        /*download*/
        //$pdf->Output(''.$reg_data->no_registrasi.'.pdf', 'D'); 
        
    }
    
}


/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
