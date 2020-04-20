<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Import_hasil_verif_bpjs extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        $this->load->model('Import_hasil_verif_bpjs_model');
    }

    public function import($file_name, $id){
        // Load plugin PHPExcel nya
        include APPPATH.'third_party/PHPExcel/PHPExcel.php';
        /*declare class*/
        $excelreader = new PHPExcel_Reader_Excel2007();
        /*load file excel to execute*/
        $loadexcel = $excelreader->load(PATH_HASIL_VERIF_BPJS.$file_name); // Load file yang telah /*load exce and get data from sheet*/
        $sheet = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);
        // echo '<pre>'; print_r($sheet);die;
        /*get klas by remove empty for sheet klas*/
        $keterangan = array_filter($sheet[2]);
        // echo '<pre>'; print_r($keterangan);die;
        /* define variabel array*/
        $data = [];
        $detail_tarif = [];

        /*define num row*/
        $numrow = 1;
        /*loop data from sheet*/
        foreach($sheet as $key=>$row){
            
            /*start data from sheet 4*/
            if($numrow >= 5){
                /*save log book*/
                /*pull data to variabel data array*/

                $data[] = [
                    'csm_uhvd_tgl_masuk' => $row['B'],
                    'csm_uhvd_tgl_keluar  ' => $row['C'],
                    'csm_uhvd_no_mr' => $row['D'],
                    'csm_uhvd_nama_pasien' => $row['E'],
                    'csm_uhvd_no_sep' => $row['F'],
                    'csm_uhvd_inacbg' => $row['G'],
                    'csm_uhvd_total_tarif' => (int)$row['I'],
                    'csm_uhvd_tarif_rs' => (int)$row['J'],
                    'csm_uhvd_jenis' => $row['K'],
                    'csm_uhv_id' => $id,
                ];

            }

            $numrow++; // Tambah 1 setiap kali looping
        }
        $this->db->insert_batch('csm_upload_hasil_verif_detail', $data);
        $fp = fopen(PATH_HASIL_VERIF_BPJS.$file_name.'.json', 'w');
        fwrite($fp, json_encode($data));
        fclose($fp);
        return array('totalData' => count($data), 'keterangan' => $keterangan['A']);
        // echo '<pre>'; print_r($data);die;
    }

}
/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
