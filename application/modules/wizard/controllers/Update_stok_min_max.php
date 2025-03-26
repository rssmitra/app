<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Update_stok_min_max extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
    }

    public function import($file_name, $kode_bagian){

        /*first description*/

        // Load plugin PHPExcel nya
        include APPPATH.'third_party/PHPExcel/PHPExcel.php';

        /*declare class*/
        $excelreader = new PHPExcel_Reader_Excel2007();
        /*load file excel to execute*/
        $loadexcel = $excelreader->load('import/farmasi/'.$file_name.'.xlsx'); // Load file yang telah /*load exce and get data from sheet*/
        $sheet = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);
        // echo '<pre>'; print_r($sheet[2]);die;
        /* define variabel array*/
        $data = [];

        /*define num row*/
        $numrow = 1;
        /*loop data from sheet*/
        foreach($sheet as $key=>$row){
            
            /*start data from sheet 4*/
            if($numrow > 1){
                /*save log book*/
                /*pull data to variabel data array*/
                $data[] = [
                    'kode_brg'=>$row['A'], 
                    'stok_minimum'=>$row['B'], 
                    'stok_maksimum'=>$row['C']
                ];
            }
            
            $numrow++; // Tambah 1 setiap kali looping
        }
        // echo '<pre>'; print_r($data);die;

        if($kode_bagian == '060201'){
            $this->db->where('kode_bagian_gudang', $kode_bagian );
            $this->db->update_batch('mt_rekap_stok', $data, 'kode_brg');     
        }
        $this->db->where('kode_bagian', $kode_bagian );
        $this->db->update_batch('mt_depo_stok', $data, 'kode_brg'); 

        

    }


}
/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
