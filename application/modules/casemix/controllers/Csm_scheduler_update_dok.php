<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Csm_scheduler_update_dok extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'casemix/Csm_scheduler_update_dok');
        /*session redirect login if not login*/
        $this->load->library("input"); 
        /*load model*/
        $this->load->model('Csm_billing_pasien_model', 'Csm_billing_pasien');
        $this->load->model('Csm_scheduler_update_dok_model', 'Csm_scheduler_update_dok');
        $this->load->model('ws_bpjs/Ws_index_model', 'Ws_index');
        /*load module*/
        $this->load->module('Templates/Templates.php');
        $this->load->module('Templates/Export_data.php');
        Modules::run('Templates/Export_data');
        /*enable profiler*/
        $this->output->enable_profiler(false);
        $this->load->module('casemix/Csm_billing_pasien');
        $this->cbpModule = new Csm_billing_pasien;

    }

    public function index(){

        // if(!$this->input->is_cli_request())
        // {
        //     echo "This script can only be accessed via the command line" . PHP_EOL;
        //     return;
        // }

        /*execution*/
        $this->db->trans_begin();    

        /*first description*/
        $data_pasien =  $this->Csm_scheduler_update_dok->get_data();
        $data = array();
        if($data_pasien->no_registrasi > 0): 
            /*define var*/
            $jum_record = 0;
            $jum_record_eksekusi = 0;
            $jum_record_no_eksekusi = 0;
            $log='';
            $no_registrasi = $data_pasien->no_registrasi;
            $type = $data_pasien->csm_rp_tipe;
            
            /*loop data*/
            $createDocument = $this->Csm_billing_pasien->createDocument($no_registrasi, $type);
            // echo '<pre>'; print_r($createDocument);die;

            foreach ($createDocument as $k_cd => $v_cd) {
                # code...
                $explode = explode('-', $v_cd);
                /*explode result*/
                $named = str_replace('BILL','',$explode[0]);
                $no_mr = $explode[1];
                $exp_no_registrasi = $explode[2];
                $unique_code = $explode[3];

                /*create and save download file pdf*/
                if( $this->cbpModule->getContentPDF($exp_no_registrasi, $named, $unique_code, 'F') ) :
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
                        $this->db->trans_commit();
                    }

                    $this->db->update('csm_reg_pasien', array('is_scheduler' => 0), array('no_registrasi' => $no_registrasi));
                    $this->db->trans_commit();
                endif;
                /*insert database*/
            }

            // echo '<pre>'; print_r($data_pasien);die;
            $data['result'] = $data_pasien;
            $data['redirect'] = base_url().'casemix/Csm_billing_pasien/mergePDFFiles/'.$no_registrasi.'/'.$type.'';
        endif;
        $this->load->view('casemix/index_scheduler', $data);
        

        // $file = "application/logs/csm_".date('Y_m_d_H_i_s').".log";
        // $fp = fopen ($file,'w');

        // $data_general = 'Jumlah Record = '.$jum_record.', Eksekusi = '.$jum_record_eksekusi.', No Eksekusi = '.$jum_record_no_eksekusi.' ';
        // $data_log = var_export($log, true);

        // fwrite($fp,  $data_general."\n".$data_log);
        // fclose($fp);
        
    }



}
/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
