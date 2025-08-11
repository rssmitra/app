<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Auto_merge_dok_klaim_casemix extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        $this->load->library("input"); 
        $this->load->model('farmasi/Etiket_obat_model', 'Etiket_obat');
        $this->load->model('farmasi/Entry_resep_racikan_model', 'Entry_resep_racikan');
        $this->load->model('farmasi/Verifikasi_resep_prb_model', 'Verifikasi_resep_prb');
        $this->load->model('ws_bpjs/Ws_index_model', 'Ws_index');
        $this->load->model('farmasi/Retur_obat_model', 'Retur_obat');
        $this->load->model('casemix/Csm_billing_pasien_model', 'Csm_billing_pasien');
        $this->load->model('pelayanan/Pl_pelayanan_pm_model', 'Pl_pelayanan_pm');
        // load module
        $this->load->module('Templates/Templates.php');
        // harus pake tanggal
        $this->date = $this->uri->segment(4);

        /*load module*/
        $this->load->module('casemix/Csm_billing_pasien');
        $this->cbpModule = new Csm_billing_pasien;
    }

    public function index(){

        $return = $this->execute();
        $data = [];
        $data['return'] = $return;
        $this->load->view('view_auto_reload_merge', $data);

    }

    public function execute(){

        // if(!$this->input->is_cli_request())
        // {
        //     echo "This script can only be accessed via the command line" . PHP_EOL;
        //     return;
        // }
        
        $last_date = date('Y-m-d', strtotime('-4 day', strtotime(date('Y-m-d'))));
        $month = date("m",strtotime($last_date));
		$this->db->select('csm_reg_pasien.*');
		$this->db->from('csm_reg_pasien');
		$this->db->where("csm_reg_pasien.is_submitted = 'Y' " );
		$this->db->where("(csm_dokumen_klaim.no_sep is null AND LEN(csm_rp_no_sep) > 18)");
        $this->db->where("csm_rp_tipe = 'RI' " );
        $this->db->where("csm_reg_pasien.csm_rp_kode_bagian !=", '031201');
        $this->db->where("csm_reg_pasien.is_scheduler is null" );
        $this->db->where("DATEDIFF(day,csm_reg_pasien.csm_rp_tgl_keluar,GETDATE()) <= 4" );
		$this->db->join('csm_dokumen_klaim', 'csm_dokumen_klaim.no_registrasi=csm_reg_pasien.no_registrasi', 'LEFT');
		$this->db->order_by('no_registrasi ASC');
        $result = $this->db->get();
        $count = $result->num_rows();
        $data = $result->row();

        // echo '<pre>';
        // print_r($last_date);
        // print_r($count);
        // print_r($data);
        // print_r($this->db->last_query());
        // exit;
        
        $txt_success = '';
        $txt_failed = '';
        $count_result = [];
        $data_log = [];

        if(count($data) == 0){
            echo "Tidak ada data untuk diverifikasi" . PHP_EOL;
        }

        // $no_registrasi = 1255803;
        $no_registrasi = $data->no_registrasi;
        $type = trim($data->csm_rp_tipe);
        $this->db->delete('csm_dokumen_export', array('no_registrasi' => $no_registrasi, 'is_adjusment' => NULL));
        /*created document name*/
        $createDocument = $this->Csm_billing_pasien->createDocument($no_registrasi, $type);
        // echo "<pre>"; print_r($createDocument);die;
        $this->db->delete('csm_dokumen_export', array('no_registrasi' => $no_registrasi, 'is_adjusment' => NULL));
        foreach ($createDocument as $k_cd => $v_cd) {
            # code...
            $explode = explode('-', $v_cd);
            /*explode result*/
            $named = str_replace('BILL','',$explode[0]);
            $no_mr = $explode[1];
            $exp_no_registrasi = $explode[2];
            $unique_code = $explode[3];
            $no_kunjungan = isset($explode[4])?$explode[4]:0;
            
            /*create and save download file pdf*/
            if( $this->cbpModule->getContentPDF($exp_no_registrasi, $named, $unique_code, 'F', $no_kunjungan) ) :
                /*save document to database*/
                /*csm_reg_pasien*/
                $filename = $named.'-'.$no_mr.$exp_no_registrasi.$unique_code.'.pdf';
                $doc_save = array(
                    'no_registrasi' => $this->regex->_genRegex($exp_no_registrasi, 'RGXQSL'),
                    'csm_dex_nama_dok' => $this->regex->_genRegex($filename, 'RGXQSL'),
                    'csm_dex_jenis_dok' => $this->regex->_genRegex($v_cd, 'RGXQSL'),
                    'csm_dex_fullpath' => $this->regex->_genRegex('uploaded/casemix/log/'.$filename.'', 'RGXQSL'),
                    'base_url_dok' => $this->regex->_genRegex(base_url(), 'RGXQSL'),
                    'is_scheduler' => $this->regex->_genRegex(1, 'RGXINT'),
                );
                
                $doc_save['created_date'] = date('Y-m-d H:i:s');
                $doc_save['created_by'] = $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL');
                /*check if exist*/
                if ( $this->Csm_billing_pasien->checkIfDokExist($exp_no_registrasi, $filename) == FALSE ) {
                    $this->db->insert('csm_dokumen_export', $doc_save);
                }
            endif;
        }

        $string_url = $this->cbpModule->mergePDFFilesReturnValue($no_registrasi, $type, 1);
        // echo '<pre>';print_r($string_url);exit;
        // redirect(base_url().'casemix/Csm_billing_pasien/mergePDFFiles/'.$no_registrasi.'/'.$type.'');
        file_get_contents(base_url().'casemix/Csm_billing_pasien/mergePDFFiles/'.$no_registrasi.'/'.$type.'');

        // echo "url merge ".$string_url['url']."  " . PHP_EOL;
        // $script_cmd = 'start chrome "'.$string_url['url'].'" ';
        // exec( $script_cmd );

        // $script_cmd = 'taskkill /F /IM chrome.exe /T > nul';
        // exec( $script_cmd );

        $file = "uploaded/casemix/log_scheduler/".date('Y_m_d_H_i_s').".log";
        $fp = fopen ($file,'w');
        $data_general = "\nURL Merge :\n".$string_url['url']."";
        $data_log = var_export($data_log, true);
        fwrite($fp,  $data_general."\n".$data_log);
        fclose($fp);

        return $string_url['url'];

    }


}
/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
