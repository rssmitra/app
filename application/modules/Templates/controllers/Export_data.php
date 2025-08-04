<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Export_data extends MX_Controller {

    public function __construct()
    {
        parent::__construct();
        /*load model*/
        $this->load->model('Export_data_model', 'export_data_model');
        /*load module*/
        $this->load->module('casemix/Csm_billing_pasien');
        $this->load->module('casemix/Csm_resume_billing');
        $this->load->module('casemix/Csm_resume_billing_ri');
        $this->load->module('casemix/Csm_dokumen_klaim');
        $this->load->module('pelayanan/Pl_pelayanan_ri');
        $this->load->model('registration/Reg_pm_model');
    }

    public function export()
    {
    $type               = $this->input->get('type');
    $flag               = $this->input->get('flag');
    $no_registrasi      = $this->input->get('noreg');
    $pm                 = $this->input->get('pm');
    $act_code           = $this->input->get('act');
    $rincian            = $this->input->get('rb');
    $bagian             = $this->input->get('kode_pm');
    $no_kunjungan       = (isset($_GET['no_kunjungan']))?$this->input->get('no_kunjungan'):'';
    $flag_mcu           = (isset($_GET['flag_mcu']))?$this->input->get('flag_mcu'):'';
    // print_r($no_registrasi);die;

        switch ($type) {
            case 'pdf':
                $this->getContentPDF($no_registrasi, $flag, $pm, $act_code,$bagian,$no_kunjungan,$flag_mcu );
                break;

            case 'viewer':
                $this->getContentHtml($no_registrasi, $flag, $pm, $act_code,$bagian,$no_kunjungan,$flag_mcu );
                break;

            case 'word':
                $this->exportWord();
                break;
            
            default:
                # code...
                break;
        }
    }

    public function getContentPDF($no_registrasi, $flag, $pm, $act_code='',$bagian,$no_kunjungan,$flag_mcu ){

        
        /*load class*/
        $csm_bp = new Csm_billing_pasien;
        $reg_pm = new Reg_pm_model;
        /*get content data*/
        //$data = $csm_bp->getBillingLocal($no_registrasi, $flag); 
        //   $data = $reg_pm->get_hasil_pm($no_registrasi, $no_kunjungan, $bagian, $flag_mcu);
        $data = '' ;
        /*get content html*/
        
        $html = json_decode($csm_bp->getHtmlData($data, $no_registrasi, $flag, $pm, '', $no_kunjungan, $flag_mcu));
        // echo '<pre>';print_r($html);die;

        /*generate pdf*/
        $this->exportPdf($html, $flag, $pm, $act_code); 
      
    }

    public function getContentHtml($no_registrasi, $flag, $pm, $act_code='',$bagian,$no_kunjungan,$flag_mcu ){

        
        /*load class*/
        $csm_bp = new Csm_billing_pasien;
        $reg_pm = new Reg_pm_model;
        $data = [];
        /*get content data*/
        //$data = $csm_bp->getBillingLocal($no_registrasi, $flag); 
        // $data = $reg_pm->get_hasil_pm($no_registrasi, $no_kunjungan, $bagian, $flag_mcu);
        // echo '<pre>';print_r($data);die;
        /*get content html*/
        $html = $csm_bp->getHtmlData($data, $no_registrasi, $flag, $pm, '', $no_kunjungan, $flag_mcu);
        // echo '<pre>';print_r($html);die;
        echo $html;

    }


    public function exportPdf($data, $flag, $pm, $act_code='') { 
        
        $this->load->library('pdf');
        
        $reg_data = $data->data->reg_data;
        // echo '<pre>';print_r($reg_data);die;
        /*default*/
        $action = ($act_code=='')?'I':$act_code;
        /*filename and title*/
        $filename = $reg_data->no_registrasi.'-'.$flag.'-'.$pm;
        $tanggal = new Tanggal();
        $pdf = new TCPDF('P', PDF_UNIT, array(470,280), true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        
        $pdf->SetAuthor(COMP_FULL);
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
        $pdf->AddPage('P', 'A4');
        //$pdf->setY(10);
        $pdf->setXY(10,20,5,5);
        $pdf->SetMargins(10, 10, 10, 10); 
        /* $pdf->Cell(150,42,'',1);*/
//         $html = <<<EOD
//         <link rel="stylesheet" href="'.file_get_contents(_BASE_PATH_.'/assets/css/bootstrap.css)'" />
// EOD;
        $html = '';
        $html .= $data->html;
        $result = $html;

        // output the HTML content
        $pdf->writeHTML($result, true, false, true, false, '');
        ob_end_clean();
        /*save to folder*/
        $pdf->Output('uploaded/casemix/'.$filename.'.pdf', ''.$action.''); 

        /*show pdf*/
        //$pdf->Output(''.$reg_data->no_registrasi.'.pdf', 'I'); 
        /*download*/
        //$pdf->Output(''.$reg_data->no_registrasi.'.pdf', 'D'); 
        
    }

    public function exportContent()
    {
        $this->getHtmlDataFromClass($_GET['mod'], $_GET['type']);
    }

    public function getHtmlDataFromClass($class, $type_doc){

        $obj = new $class;
        $content = $obj->get_content_data();
        $html_content = $content->catatan_pengkajian;

        $data = [
            'cppt_id' => $content->cppt_id,
            'html_content' => $html_content,
        ];
        // echo '<pre>'; print_r($data);die;
        $this->load->view('templates/view_html_content', $data);



        
        $paper_type = isset($_GET['paper']) ? $_GET['paper'] : 'P';
        
        // switch ($type_doc) {
        //     case 'pdf':
        //         # code...
        //         $this->exportPdfContent($html_content,$paper_type);
        //         break;
        //     case 'excel':
        //         # code...
        //         $this->exportExcelContent($html_content);
        //         break;
            
        //     default:
        //         # code...
        //         break;
        // }
        

    }


    public function exportPdfContent($html_content, $paper_type) { 
        
        // echo ''; print_r($html_content);die;
        $this->load->library('pdf');
        $pdf = new TCPDF($paper_type, PDF_UNIT, array(470,280), true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        
        $pdf->SetAuthor(COMP_FULL);
        $pdf->SetTitle('Print PDF Document');

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
        $pdf->AddPage($paper_type, 'A4');
        //$pdf->setY(10);
        $pdf->setXY(5,20,5,5);
        $pdf->SetMargins(10, 10, 10, 10); 
        /* $pdf->Cell(150,42,'',1);*/
        $html = <<<EOD
        <link rel="stylesheet" href="'.file_get_contents(_BASE_PATH_.'/assets/css/bootstrap.css)'" />
EOD;
        $html .= $html_content;
        $result = $html;

        // output the HTML content
        $pdf->writeHTML($result, true, false, true, false, '');
        ob_end_clean();

        /*show pdf*/
        $pdf->Output('test.pdf', 'I'); 
        /*download*/
        //$pdf->Output(''.$reg_data->no_registrasi.'.pdf', 'D'); 
        
    }


    public function exportExcelContent($html_content){

        $random = rand(1,9999);
        header("Content-type: application/vnd.ms-excel");
        header("Content-Disposition: attachment;Filename=Export-".date('dMY').'-'.$random.".xls");

        echo "<html>";
        echo "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=Windows-1252\">";
        echo "<link rel='stylesheet' href='".base_url()."assets/css/bootstrap.css' />";
        echo "<body>";
        echo '<p style="font-size:11px"><b>Exported by system '.date('d/m/Y').'</b></p>';
        echo '<br>';
        echo $html_content;
        
        echo "</body>";
        echo "</html>";
    }

    public function convertGelangPasienToPDF($data='') { 
        
        $this->load->library('pdf');
        
        $tanggal = new Tanggal();
        $pdf = new TCPDF('L','mm',array(30,220));
        $pdf->SetAuthor(COMP_FULL);
        $pdf->SetTitle('Gelang Pasien');

    // remove default header/footer
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);

        $pdf->AddPage();

        $barcode = '<img class="img-responsive" src="'.base_url().'assets/barcode.php?s=code128&d=00211762" style="min-width:500px">';
        
        $pdf->Image($barcode,180,12,40,16);


        $result = $html;

        // output the HTML content
        $pdf->writeHTML($result, true, false, true, false, '');

        ob_end_clean();

        /*save to folder*/
        $pdf->Output('uploaded/casemix/GelangPasien.pdf', 'I'); 

        /*show pdf*/
        //$pdf->Output(''.$reg_data->no_registrasi.'.pdf', 'I'); 
        /*download*/
        //$pdf->Output(''.$reg_data->no_registrasi.'.pdf', 'D'); 
        
    }

}
