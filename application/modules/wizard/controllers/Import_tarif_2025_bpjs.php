<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Import_tarif_2025_bpjs extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        $this->load->model('Import_tarif_2025_model');
    }

    public function import($file_name){

        /*first description*/
        echo nl2br("").PHP_EOL;
        echo nl2br("Import Data Tarif 2025").PHP_EOL;
        echo nl2br($file_name). PHP_EOL;
        echo nl2br("Waiting for execution..."). PHP_EOL;

        // Load plugin PHPExcel nya
        include APPPATH.'third_party/PHPExcel/PHPExcel.php';

        /*declare class*/
        $excelreader = new PHPExcel_Reader_Excel2007();
        /*load file excel to execute*/
        $loadexcel = $excelreader->load('tarif_import/new_fix/'.$file_name.'.xlsx'); // Load file yang telah /*load exce and get data from sheet*/
        $sheet = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);
        // echo '<pre>'; print_r($sheet);die;
        // echo '<pre>'; print_r($klas);die;
        /* define variabel array*/
        $data = [];
        $detail_tarif = [];

        /*define num row*/
        $numrow = 1;
        /*loop data from sheet*/
        foreach($sheet as $key=>$row){
            
            /*start data from sheet 4*/
            if($numrow > 1){
                /*save log book*/
                // echo '<pre>';print_r($row);die;
                //$this->Import_tarif_2025_model->save_log_book($row, $klas);
                /*pull data to variabel data array*/
                $data = [
                    'kode_tarif'=>$row['A'], 
                    'nama_tarif'=>$row['B'], 
                    'is_active'=>$row['D'], 
                    'updated_date'=>date('Y-m-d H:i:s'), 
                    'updated_by'=>'Import New Tarif', 
                    'new_tarif_2025'=> $_GET['unique_code'], 
                ];

                $detail = [
                    'is_active'=>$row['D'], 
                    'updated_date'=>date('Y-m-d H:i:s'), 
                    'updated_by'=>'Import New Tarif', 
                    'new_tarif_2025'=> $_GET['unique_code'], 
                ];

                // echo '<pre>'; print_r($data);die;
                $this->db->where('kode_tarif', $row['A'])->update('mt_master_tarif_dev', $data);
                $this->db->where('kode_tarif', $row['A'])->update('mt_master_tarif_detail_dev', $detail);

            }

            $numrow++; // Tambah 1 setiap kali looping
        }


    }

    function execute_tarif_klas($klas, $row, $sheet, $kode_tarif){

        // echo '<pre>'; print_r($klas);die;
        // echo '<pre>'; print_r($row);
        // echo '<pre>'; print_r($sheet);die;

        /*tgl_tarif aktif*/
        $tgl_tarif = $this->db->get_where('mt_tgl_tarif', array('status' => 1) )->row();
        $detail_tarif = [];
        foreach ($klas as $key_klas => $val_klas) {
            $is_array = explode("/", $val_klas);
            foreach($is_array as $row_klas) :
                // echo '<pre>'; print_r($row_klas);die;
                /*get row data existing by kode tarif and klas*/
                $dt_ex = $this->Import_tarif_2025_model->get_detail_tarif($kode_tarif, $row_klas);

                /*from sheet*/
                $alat_rs = isset($row[$this->find_column($sheet[3], $val_klas.'/ALAT_RS')])?$row[$this->find_column($sheet[3], $val_klas.'/ALAT_RS')]:0;
                $bhp = isset($row[$this->find_column($sheet[3], $val_klas.'/BHP')])?$row[$this->find_column($sheet[3], $val_klas.'/BHP')]:0;
                $kamar_tindakan = isset($row[$this->find_column($sheet[3], $val_klas.'/KAMAR_TINDAKAN')])?$row[$this->find_column($sheet[3], $val_klas.'/KAMAR_TINDAKAN')]:0;
                $bill_dr1 = isset($row[$this->find_column($sheet[3], $val_klas.'/BILL_DR1')])?$row[$this->find_column($sheet[3], $val_klas.'/BILL_DR1')]:0;
                $bill_dr2 = isset($row[$this->find_column($sheet[3], $val_klas.'/BILL_DR2')])?$row[$this->find_column($sheet[3], $val_klas.'/BILL_DR2')]:0;
                $pendapatan_rs = isset($row[$this->find_column($sheet[3], $val_klas.'/PENDAPATAN_RS')])?$row[$this->find_column($sheet[3], $val_klas.'/PENDAPATAN_RS')]:0;
                // echo '<pre>'; print_r((int)$pendapatan_rs);die;
                /*total*/
                $bill_rs = (double)$alat_rs + (double)$bhp + (double)$pendapatan_rs + (double)$kamar_tindakan;
                $total = (double)$bill_rs + (double)$bill_dr1 + (double)$bill_dr2;
                
                /*push data*/

                $detail_tarif[] = [
                    'kode_tarif'=> (string)$kode_tarif, 
                    'kode_klas'=> (int)$row_klas, 
                    'bill_rs'=> (double)$bill_rs, 
                    'bill_dr1'=> (double)$bill_dr1, 
                    'bill_dr2'=> (double)$bill_dr2, 
                    'bill_dr3'=> 0, 
                    'kamar_tindakan'=> (double)$kamar_tindakan, 
                    'total'=> (double)$total, 
                    'bhp'=> (double)$bhp, 
                    'pendapatan_rs'=> (double)$pendapatan_rs, 
                    'alat_rs'=> (double)$alat_rs, 
                    'obat'=> 0, 
                    'alkes'=> 0, 
                    'adm'=> 0, 
                    'kode_tgl_tarif'=> $tgl_tarif->kode_tgl_tarif, 
                    'is_active' => 'Y', 
                    'updated_date' => date('Y-m-d H:i:s'), 
                    'updated_by' => 'Import New Tarif', 
                    'new_tarif_2025' => $_GET['unique_code'], 
                ];

            endforeach;
        }
        // echo '<pre>'; print_r($detail_tarif);die;
        return $this->Import_tarif_2025_model->update_detail_tarif('mt_master_tarif_detail', $detail_tarif);
            
    }

    function find_column($array, $string) {
        $key = array_search((string)$string, $array);
        return (string)$key;
    }

    public function export($kode_bagian, $reff){
        /*get data*/
        $data = $this->Import_tarif_2025_model->get_tarif_by_kode_bagian($kode_bagian, $reff);
    }

}
/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
