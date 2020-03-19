<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Import_tarif_OK_2019 extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        $this->load->model('Import_tarif_OK_2019_model');
    }

    public function import($file_name){

        /*first description*/
        echo nl2br("").PHP_EOL;
        echo nl2br("Import Data Tarif 2018").PHP_EOL;
        echo nl2br($file_name). PHP_EOL;
        echo nl2br("Waiting for execution..."). PHP_EOL;

        // Load plugin PHPExcel nya
        include APPPATH.'third_party/PHPExcel/PHPExcel.php';

        /*declare class*/
        $excelreader = new PHPExcel_Reader_Excel2007();
        /*load file excel to execute*/
        $loadexcel = $excelreader->load('tarif_import/'.$file_name.'.xlsx'); // Load file yang telah /*load exce and get data from sheet*/
        $sheet = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);
        //echo '<pre>'; print_r($sheet[3]);die;
        /*get klas by remove empty for sheet klas*/
        $klas = array_filter($sheet[2]);
        //echo '<pre>'; print_r($klas);die;
        /* define variabel array*/
        $data = [];
        $detail_tarif = [];

        /*define num row*/
        $numrow = 1;
        /*loop data from sheet*/
        foreach($sheet as $key=>$row){
            
            /*start data from sheet 4*/
            if($numrow > 3){
                /*save log book*/
                //echo '<pre>';print_r($row);die;
                //$this->Import_tarif_OK_2019_model->save_log_book($row, $klas);
                /*pull data to variabel data array*/
                $data = [
                    'nama_tarif'=>$row['B'], 
                    'kode_bagian'=> (string)$row['C'], 
                    'referensi'=> (string)$row['BO'], 
                    'is_active'=>'Y', 
                    'is_old'=>'N', 
                ];

                //echo '<pre>'; print_r($data);die;
                $get_kode_tarif = $this->Import_tarif_OK_2019_model->update_tarif('mt_master_tarif',$data);
                
                /*loop data klas for detail tarif*/
                $this->execute_tarif_klas($klas, $row, $sheet, $get_kode_tarif);

            }

            $numrow++; // Tambah 1 setiap kali looping
        }


    }

    function execute_tarif_klas($klas, $row, $sheet, $kode_tarif){

        /*echo '<pre>'; print_r($klas);
        echo '<pre>'; print_r($row);
        echo '<pre>'; print_r($sheet);die;*/

        /*tgl_tarif aktif*/
        $tgl_tarif = $this->db->get_where('mt_tgl_tarif', array('status' => 1) )->row();
        $detail_tarif = [];
        foreach ($klas as $key_klas => $val_klas) {
            
            /*get row data existing by kode tarif and klas*/
            $dt_ex = $this->Import_tarif_OK_2019_model->get_detail_tarif($kode_tarif, $val_klas);
            //echo '<pre>'; print_r($dt_ex);die;

            /*from existing*/
            $room = isset($dt_ex->kamar_tindakan)?$dt_ex->kamar_tindakan:0;
            $obat = isset($dt_ex->obat)?$dt_ex->obat:0;
            $alkes = isset($dt_ex->alkes)?$dt_ex->alkes:0;

            /*from sheet*/
            $alat_rs = isset($row[$this->find_column($sheet[3], $val_klas.'/alat_rs')])?$row[$this->find_column($sheet[3], $val_klas.'/alat_rs')]:0;

            $bhp = isset($row[$this->find_column($sheet[3], $val_klas.'/bhp')])?$row[$this->find_column($sheet[3], $val_klas.'/bhp')]:0;
            $pendapatan_rs = isset($row[$this->find_column($sheet[3], $val_klas.'/pendapatan_rs')])?$row[$this->find_column($sheet[3], $val_klas.'/pendapatan_rs')]:0;
            $bill_dr1 = isset($row[$this->find_column($sheet[3], $val_klas.'/bill_dr1')])?$row[$this->find_column($sheet[3], $val_klas.'/bill_dr1')]:0;
            $bill_dr2 = isset($row[$this->find_column($sheet[3], $val_klas.'/bill_dr2')])?$row[$this->find_column($sheet[3], $val_klas.'/bill_dr2')]:0;
            $bill_dr3 = isset($row[$this->find_column($sheet[3], $val_klas.'/bill_dr3')])?$row[$this->find_column($sheet[3], $val_klas.'/bill_dr3')]:0;
            $kamar_tindakan = isset($row[$this->find_column($sheet[3], $val_klas.'/kamar_tindakan')])?$row[$this->find_column($sheet[3], $val_klas.'/kamar_tindakan')]:0;

            /*total*/
            $bill_rs = (double)$room + (double)$obat + (double)$alkes + (double)$alat_rs + (double)$bhp + (double)$pendapatan_rs + (double)$kamar_tindakan;
            $total = (double)$bill_rs + (double)$bill_dr1 + (double)$bill_dr2 + (double)$bill_dr3;
            
            /*push data*/

            $detail_tarif[] = [
                'kode_tarif'=> (string)$kode_tarif, 
                'kode_klas'=> (int)$val_klas, 
                //'bill_rs_before'=> (double)$bill_rs, 
                'bill_rs'=> (double)$bill_rs, 
                'bill_dr1'=> (double)$bill_dr1, 
                'bill_dr2'=> (double)$bill_dr2, 
                'bill_dr3'=> (double)$bill_dr3, 
                'kamar_tindakan'=> (double)$kamar_tindakan, 
                //'total_before'=> (double)$total, 
                'total'=> (double)$total, 
                'bhp'=> (double)$bhp, 
                //'pendapatan_rs_before'=> (double)$pendapatan_rs_dibulatkan, 
                'pendapatan_rs'=> (double)$pendapatan_rs, 
                //'modulus'=> (double)$mod, 
                'alat_rs'=> (double)$alat_rs, 
                'obat'=> 0, 
                'alkes'=> 0, 
                'adm'=> 0, 
                'kode_tgl_tarif'=> $tgl_tarif->kode_tgl_tarif, 
                //'is_old' => 'N', 
                'is_active' => 'Y', 
            ];

        }
        //echo '<pre>';print_r($detail_tarif);die;
        return $this->Import_tarif_OK_2019_model->update_detail_tarif('mt_master_tarif_detail',$detail_tarif);
            
    }

    function find_column($array, $string) {
        $key = array_search((string)$string, $array);
        return (string)$key;
    }

    public function export($kode_bagian, $reff){
        /*get data*/
        $data = $this->Import_tarif_OK_2019_model->get_tarif_by_kode_bagian($kode_bagian, $reff);
    }

}
/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
