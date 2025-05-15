<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Import_tarif_naker_2025 extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        $this->load->model('Import_tarif_2025_model');
    }

    public function import($file_name){

        /*first description*/
        echo nl2br("").PHP_EOL;
        echo nl2br("Import Data Tarif NAKER 2025").PHP_EOL;
        echo nl2br($file_name). PHP_EOL;
        echo nl2br("Waiting for execution..."). PHP_EOL;

        // Load plugin PHPExcel nya
        include APPPATH.'third_party/PHPExcel/PHPExcel.php';

        /*declare class*/
        $excelreader = new PHPExcel_Reader_Excel2007();
        /*load file excel to execute*/
        $loadexcel = $excelreader->load('tarif_import/NAKER/'.$file_name.'.xlsx'); // Load file yang telah /*load exce and get data from sheet*/
        $sheet = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);
        // echo '<pre>'; print_r($sheet[3]);die;
        /*get klas by remove empty for sheet klas*/
        $klas = array_filter($sheet[3]);
        // echo '<pre>'; print_r($klas);die;
        /* define variabel array*/
        $data = [];
        $detail_tarif = [];

        /*define num row*/
        $numrow = 1;
        /*loop data from sheet*/
        foreach($sheet as $key=>$row){
            
            /*start data from sheet 4*/
            if($numrow > 4){
                /*save log book*/
                // echo '<pre>';print_r($row);die;
                //$this->Import_tarif_2025_model->save_log_book($row, $klas);
                /*pull data to variabel data array*/
                $data[] = [
                    'kode_tarif'=>$row['A'], 
                    'kode_tindakan'=>$row['C'], 
                    'nama_tarif'=>$row['B'], 
                    'referensi'=>$row['E'], 
                    'jenis_tindakan'=>$row['D'], 
                    'tingkatan'=>5, 
                    'ket'=>'Tarif Baru Naker 2025', 
                    'revisi_ke'=>0, 
                    'is_active'=>'Y', 
                    'is_old'=>'N', 
                    'created_date'=>date('Y-m-d H:i:s'), 
                    'created_by'=>'Administrator', 
                    'updated_date'=>date('Y-m-d H:i:s'), 
                    'updated_by'=>'Administrator', 
                    'new_tarif_2025'=> $_GET['unique_code'], 
                ];

                // echo '<pre>'; print_r($data);die;
                // $get_kode_tarif = $this->Import_tarif_2025_model->insert_tarif('mt_master_tarif', $data);
                
                /*loop data klas for detail tarif*/
                $this->execute_new_tarif_detail($klas, $row, $sheet, $row['A']);

            }

            $numrow++; // Tambah 1 setiap kali looping
        }

        $this->db->insert_batch('mt_master_tarif', $data);

        // echo '<pre>'; print_r($data);die;


    }

    function execute_new_tarif_detail($klas, $row, $sheet, $kode_tarif){

        // echo '<pre>'; print_r($klas);
        // echo '<pre>'; print_r($row);
        // echo '<pre>'; print_r($sheet[4]);die;

        /*tgl_tarif aktif*/
        $tgl_tarif = $this->db->get_where('mt_tgl_tarif', array('status' => 1) )->row();
        $detail_tarif = [];
        foreach ($klas as $key_klas => $val_klas) {
            $is_array = explode("/", $val_klas);
            foreach($is_array as $row_klas) :
                // echo '<pre>'; print_r($row_klas);die;

                /*from sheet*/
                $kamar_tindakan = isset($row[$this->find_column($sheet[4], $row_klas.'/KAMAR_TINDAKAN')])?$row[$this->find_column($sheet[4], $row_klas.'/KAMAR_TINDAKAN')]:0;
                $bill_dr1 = isset($row[$this->find_column($sheet[4], $row_klas.'/BILL_DR1')])?$row[$this->find_column($sheet[4], $row_klas.'/BILL_DR1')]:0;
                $bill_dr2 = isset($row[$this->find_column($sheet[4], $row_klas.'/BILL_DR2')])?$row[$this->find_column($sheet[4], $row_klas.'/BILL_DR2')]:0;
                $pendapatan_rs = isset($row[$this->find_column($sheet[4], $row_klas.'/PENDAPATAN_RS')])?$row[$this->find_column($sheet[4], $row_klas.'/PENDAPATAN_RS')]:0;
                // echo '<pre>'; print_r((int)$pendapatan_rs);die;
                /*total*/
                $bill_rs = (double)$pendapatan_rs + (double)$kamar_tindakan;
                $total = (double)$bill_rs + (double)$bill_dr1 + (double)$bill_dr2;
                
                /*push data*/
                $detail_tarif[] = [
                    'kode_tarif'=> (string)$kode_tarif, 
                    'kode_klas'=> (int)$row_klas, 
                    'bill_rs'=> (double)$bill_rs, 
                    'bill_dr1'=> (double)$bill_dr1, 
                    'bill_dr2'=> (double)$bill_dr2, 
                    'kamar_tindakan'=> (double)$kamar_tindakan, 
                    'total'=> (double)$total, 
                    'pendapatan_rs'=> (double)$pendapatan_rs, 
                    'kode_tgl_tarif'=> $tgl_tarif->kode_tgl_tarif, 
                    'revisi_ke' => 0, 
                    'is_active' => 'Y', 
                    'updated_date' => date('Y-m-d H:i:s'), 
                    'updated_by' => 'Import New Tarif Naker', 
                    'new_tarif_2025' => $_GET['unique_code'], 
                ];

            endforeach;
        }

        // echo '<pre>'; print_r($detail_tarif);die;
        if($this->db->insert_batch('mt_master_tarif_detail', $detail_tarif)){
            echo "Tarif ".$kode_tarif." berhasil";
            echo nl2br("").PHP_EOL;
            echo nl2br("Tarif ".$kode_tarif." berhasil").PHP_EOL;
        }else{
            echo nl2br("").PHP_EOL;
            echo nl2br("Tarif ".$kode_tarif." gagal").PHP_EOL;
        }

        // exit;
        // echo '<pre>'; print_r($detail_tarif);die;
        // return $this->Import_tarif_2025_model->update_detail_tarif('mt_master_tarif_detail', $detail_tarif);
            
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
