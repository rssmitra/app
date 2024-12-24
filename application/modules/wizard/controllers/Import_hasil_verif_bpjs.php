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
                $data[] = array(
                    'no_sep' => $row['F'],
                    'tarif_inacbgs'=> str_replace(',','',$row['I']),
                    'tipe_klaim_ncc' => $row['K'],
                   );
                $getTotalTipe[$row['K']][] = $row;

                // $data[] = array(
                //     'no_sep' => $row['A'],
                //     'tarif_inacbgs'=> str_replace(',','',$row['B']),
                //     'tarif_rs_klaim_ncc' => str_replace(',','',$row['C']),
                //     'tipe_klaim_ncc' => $row['D'],
                //    );
                // $getTotalTipe[$row['D']][] = $row;

            }

            $numrow++; // Tambah 1 setiap kali looping
        }
        // echo '<pre>'; print_r($data);die;
        $this->db->update_batch('tc_registrasi', $data, 'no_sep');

        // $this->db->insert_batch('csm_upload_hasil_verif_detail', $data);
        // $fp = fopen(PATH_HASIL_VERIF_BPJS.$file_name.'.json', 'w');
        // fwrite($fp, json_encode($data));
        // fclose($fp);
        return array('totalData' => count($data), 'keterangan' => 'Hasil Klaim Coding NCC', 'totalTipe' => $getTotalTipe);
        // echo '<pre>'; print_r($data);die;
    }

}
/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
