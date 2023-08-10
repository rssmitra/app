<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Auto_merge_farmasi extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        $this->load->library("input"); 
        $this->load->model('farmasi/Etiket_obat_model', 'Etiket_obat');
        $this->load->model('farmasi/Entry_resep_racikan_model', 'Entry_resep_racikan');
        $this->load->model('farmasi/Verifikasi_resep_prb_model', 'Verifikasi_resep_prb');
        $this->load->model('ws_bpjs/Ws_index_model', 'Ws_index');
        $this->load->model('farmasi/Retur_obat_model', 'Retur_obat');
        // load module
        $this->load->module('Templates/Templates.php');
        // harus pake tanggal
        $this->date = $this->uri->segment(4);
    }

    public function index(){

        if(!$this->input->is_cli_request())
        {
            echo "This script can only be accessed via the command line" . PHP_EOL;
            return;
        }
        
        $last_date = ( $this->uri->segment(4) ) ? $this->date : date('Y-m-d', strtotime(date('Y-m-d'), '-1 day'));

        // get data verifikasi
        $data = $this->Verifikasi_resep_prb->get_result_data($last_date);
        // echo '<pre>';
        // print_r($data);
        // exit;
        $txt_success = '';
        $txt_failed = '';

        foreach ($data as $key => $list) {
        
            // kode sep untuk file scan resep
            $substr_no_sep = substr($list->no_sep, -4);
            //get month and year
            sscanf($this->date, '%d-%d-%d', $y, $m, $d);
            $filename = 'uploaded/farmasi/scan_'.$m.$y.'/'.$this->date.'/'.$substr_no_sep.'.pdf';
            // echo $filename; exit;

            // jika file hasil scan ada maka lanjutkan
            if (file_exists($filename)) {
                
                echo "File ".$substr_no_sep.".pdf exist, please wait to execute this file" . PHP_EOL;

                if(isset($list->kode_trans_far)){

                    // get list item obat
                    $items = $this->Etiket_obat->get_detail_resep_data($list->kode_trans_far)->result();
        
                    // proses verifikasi dan merge dokumen klaim
                    foreach( $items as $key => $row ){
        
                        // define kode barang
                        $kode_brg = $row->kode_brg;
                        echo "verifikasi item [".$kode_brg."] ".$row->nama_brg." success. " . PHP_EOL;
        
                        // cek data existing
                        $dt_existing = $this->db->get_where('fr_tc_far_detail_log_prb', array('kode_brg' => $kode_brg, 'kode_trans_far' => $list->kode_trans_far, 'kd_tr_resep' => $row->kd_tr_resep) )->row();
        
                        // define id tc far racikan
                        $id_tc_far_racikan = isset($row->id_tc_far_racikan)?$this->regex->_genRegex($row->id_tc_far_racikan, 'RQXINT'):0;
        
                        // data to execute
                        $data_farmasi = array(
                            'id_tc_far_racikan' => $id_tc_far_racikan,
                            'kd_tr_resep' => isset($row->kd_tr_resep)?$this->regex->_genRegex($row->kd_tr_resep, 'RQXINT'):0,
                            'no_sep' => isset($row->no_sep)?$this->regex->_genRegex($row->no_sep, 'RQXINT'):0,
                            'kode_trans_far' => isset($list->kode_trans_far)?$this->regex->_genRegex($list->kode_trans_far, 'RQXINT'):0,
                            'tgl_input' => date('Y-m-d H:i:s'),
                            'kode_brg' => isset($kode_brg)?$this->regex->_genRegex($kode_brg, 'RGXQSL'):0,
                            'nama_brg' => $row->nama_brg,
                            'satuan_kecil' => $row->satuan_kecil,
                            'jumlah' =>  isset($row->jumlah_tebus)?$this->regex->_genRegex($row->jumlah_tebus, 'RQXINT'):0,
                            'harga_satuan' =>  isset($row->harga_jual)?$this->regex->_genRegex($row->harga_jual, 'RQXINT'):0,
                            'sub_total' =>  isset($row->sub_total)?$this->regex->_genRegex($row->sub_total, 'RQXINT'):0,
                        );
        
                        // kondisi untuk diporses update/insert
                        if( count($dt_existing) > 0 ){
                            /*update existing*/
                            $data_farmasi['updated_date'] = date('Y-m-d H:i:s');
                            $data_farmasi['updated_by'] = "AUTO RUN BY SCHEDULER";
        
                            // proses udpate data
                            $this->db->update('fr_tc_far_detail_log_prb', $data_farmasi, array('id_fr_tc_far_detail_log_prb' => $dt_existing->id_fr_tc_far_detail_log_prb) );
        
                            /*save log*/
                            $this->logs->save('fr_tc_far_detail_log_prb', $dt_existing->id_fr_tc_far_detail_log_prb, 'update record on verifikasi obat prb module', json_encode($data_farmasi),'id_fr_tc_far_detail_log_prb');
                        
                        }else{    
                            $data_farmasi['created_date'] = date('Y-m-d H:i:s');
                            $data_farmasi['created_by'] = "AUTO RUN BY SCHEDULER";
                            $this->db->insert( 'fr_tc_far_detail_log_prb', $data_farmasi );
                            
                            // proses insert data
                            $newId = $this->db->insert_id();
        
                            /*save log*/
                            $this->logs->save('fr_tc_far_detail_log_prb', $newId, 'insert new record on verifikasi obat prb module', json_encode($data_farmasi),'id_fr_tc_far_detail_log_prb');
        
                        }
        
                        $this->db->trans_commit();
        
                        $data_log = array(
                            'jumlah_obat_23' => isset($row->jumlah_tebus)?$this->regex->_genRegex($row->jumlah_tebus, 'RQXINT'):0,
                        );
                        // update log
                        $this->db->update('fr_tc_far_detail_log', $data_log, array('kode_trans_far' => $list->kode_trans_far, 'relation_id' => $data_farmasi['kd_tr_resep']) );
                        $this->db->trans_commit();
        
                        $this->db->update('fr_tc_far_detail', $data_log, array('kode_trans_far' => $list->kode_trans_far, 'kd_tr_resep' => $data_farmasi['kd_tr_resep']) );
                        $this->db->trans_commit();
                        
                    }
                
                    // set dokumen
                    $url_merge = $this->merge_dokumen_klaim($list->kode_trans_far);

                    // update fr_tc_far
                    $this->db->update('fr_tc_far', array('verifikasi_prb' => 1, 'scheduler_running_time' => date('Y-m-d H:i:s')), array('kode_trans_far' => $list->kode_trans_far) );

                    echo "url merge ".$url_merge."  " . PHP_EOL;
                    $script_cmd = 'start chrome "'.$url_merge.'" ';
                    exec( $script_cmd );

                    $script_close_chrome = "taskkill /F /IM chrome.exe /T > nul";
                    exec( $script_close_chrome );

                    echo "success  " . PHP_EOL;
                    echo "====================================================================". PHP_EOL;
                    
        
                }else{
                    echo 'No data available'. PHP_EOL;
                }
                $count_result[] = 1;
                $txt_success .= $list->kode_trans_far." (".$data_farmasi['no_sep'].")". PHP_EOL;

            } else {
                $txt_failed .= $list->kode_trans_far." (".$data_farmasi['no_sep'].")". PHP_EOL;
                echo "The file ".$substr_no_sep.".pdf does not exist". PHP_EOL;
            }
            
            
        
        }

        

        $file = "uploaded/farmasi/log_scheduler/".date('Y_m_d_H_i_s').".log";
        $fp = fopen ($file,'w');
        $data_general = "Total Eksekusi : ".count($count_result)." \nList transaksi sukses (kode_trans_far):\n".$txt_success."\nList transaksi gagal :\n".$txt_failed."";
        $data_log = var_export($log, true);
        fwrite($fp,  $data_general."\n".$data_log);
        fclose($fp);


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

        return $this->mergePDFFiles($kode_trans_far, $no_sep);

    }

    public function createDocument($kode_trans_far){

        /*get data*/
        $data = $this->Etiket_obat->get_by_id($kode_trans_far);
        $substr_sep = substr($data->no_sep, -4);

        $filename[] ='SEP-'.$data->no_sep.'-'.$kode_trans_far;
        $filename[] ='NOTA-'.$data->no_sep.'-'.$kode_trans_far;
        $filename[] ='FRM_BAST-'.$data->no_sep.'-'.$kode_trans_far;
        $filename[] ='COPY_RESEP-'.$data->no_sep.'-'.$kode_trans_far;

        return $filename;

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

    public function getHtmlData($data, $named, $no_sep, $kode_trans_far){

        $temp = new Templates;
        /*header html*/
        $header = $this->Verifikasi_resep_prb->get_header_data($kode_trans_far);
        // echo '<pre>'; print_r($header);die;
        $html = '';
        switch ($named) {

            case 'FRM_BAST':
                $result = array();
                $result['value'] = $header;
                $result['resep'] = $data;
                $html .= $this->load->view('preview_form_bast', $result, true);
            break;

            case 'NOTA':
                $result = array();
                $result['value'] = $header;
                $result['resep'] = $data;
                $html .= $this->load->view('preview_nota_farmasi_klaim', $result, true);
            break;

            case 'COPY_RESEP':
                $resep_log = $this->db->join('mt_master_pasien','mt_master_pasien.no_mr=fr_tc_far.no_mr','left')->join('mt_bagian','mt_bagian.kode_bagian=fr_tc_far.kode_bagian_asal','left')->get_where('fr_tc_far', array('kode_trans_far' => $kode_trans_far) )->row();
                $result = array();
                $result['result'] = $resep_log;
                $resep_log = $this->Etiket_obat->get_detail_resep_data($kode_trans_far)->result_array();
                foreach($resep_log as $row){
                    $racikan = ($row['flag_resep']=='racikan') ? $this->Entry_resep_racikan->get_detail_by_id($row['relation_id']) : [] ;
                    $row['racikan'][] = $racikan;
                    $getData[] = $row;
                }

                $result['detail_obat'] = $getData;
                $html .= $this->load->view('preview_copy_resep_w_header_n_footer', $result, true);
            break;

            case 'SEP':
                /*data sep*/
                $row_sep = $this->Ws_index->findSep($no_sep);
                $sep = isset($row_sep -> data) ? $row_sep -> data : [];
                $cetakan_ke = $this->Ws_index->count_sep_by_day();
                $cetakan = isset($cetakan_ke) ? $cetakan_ke : [];
                $header_ = isset($header) ? $header : [];

                $result = array('sep' => $sep, 'cetakan_ke' => $cetakan, 'header' => $header_);
                $html .= $this->load->view('preview_sep', $result, true);
            break;

            default:
                # code...
                break;
        }
        // echo '<pre>'; echo($html); die;
        return json_encode( array('html' => $html) );
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
        if(in_array($named, array('NOTA', 'COPY_RESEP', 'FRM_BAST', 'SCAN_RESEP') )){
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
        
    }


    public function mergePDFFiles($kode_trans_far, $no_sep){
        /*get doc*/
        $date = $this->uri->segment();
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

        if( ! ini_get('date.timezone') )
        {
            date_default_timezone_set('Asia/Bangkok');
        }

        rtrim($fields_string,'&');

        $url = 'http://10.10.11.5:88/sirs/app/ApiMerge/index_farmasi.php?action=download&"kode"='.$kode_trans_far.'&'.$fields_string.'&addfilesscan='.$this->date.'';
        // redirect($url, 'location');
        return $url;
    }



    public function findSep($no_sep){
        $row_sep = $this->Ws_index->findSep($no_sep);
    }


}
/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
