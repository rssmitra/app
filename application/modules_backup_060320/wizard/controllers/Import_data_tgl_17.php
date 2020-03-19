<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Import_data_tgl_17 extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        $this->load->model('Import_data_tgl_17_model');
    }

    public function import_registrasi(){
        // Load plugin PHPExcel nya
        include APPPATH.'third_party/PHPExcel/PHPExcel.php';

        $excelreader = new PHPExcel_Reader_Excel2007();
        $loadexcel = $excelreader->load('file_import/tc_registrasi.xlsx'); // Load file yang telah diupload ke folder excel
        $sheet = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);



        // Buat sebuah variabel array untuk menampung array data yg akan kita insert ke database
        $data = [];

        $numrow = 1;
        foreach($sheet as $row){
            // Cek $numrow apakah lebih dari 1
            // Artinya karena baris pertama adalah nama-nama kolom
            // Jadi dilewat saja, tidak usah diimport
            if($numrow > 1){
                // Kita push (add) array data ke variabel data
                $no_reg = $this->Import_data_tgl_17_model->get_no_reg($numrow-1);
                array_push($data, [
                    'no_registrasi'=>$no_reg, // Ambil data no
                    'no_mr'=>$row['A'], // Ambil data no
                    'kode_perusahaan'=>$row['B'], // Insert data id dari kolom A di excel
                    'kode_kelompok'=>$row['C'], // Ambil data nik
                    'kode_dokter'=>$row['D'], // Ambil data jenis kelamin
                    'no_induk'=>$row['E'], // Ambil data alamat
                    'tgl_jam_masuk'=>$row['F'], // Ambil data alamat
                    'tgl_jam_keluar'=>$row['G'], // Ambil data alamat
                    'kode_bagian_masuk'=>$row['H'], // Ambil data alamat
                    'kode_bagian_keluar'=>$row['I'], // Ambil data alamat
                    'stat_pasien'=>$row['J'], // Ambil data alamat
                    'status_registrasi'=>$row['K'], // Ambil data alamat
                    'umur'=>$row['L'], // Ambil data alamat
                    'lama_baru'=>$row['M'], // Ambil data alamat
                    'no_sep'=>$row['N'], // Ambil data alamat
                    'is_17'=>1, // Ambil data alamat
                ]);
            }

            $numrow++; // Tambah 1 setiap kali looping
        }
        /*echo '<pre>';print_r($data);die;*/
        // Panggil fungsi insert_multiple yg telah kita buat sebelumnya di model, disini adalah event handler untuk import kedalam
        // database mapp data dari bkn
        $this->Import_data_tgl_17_model->insert_multiple('tc_registrasi',$data);

        //redirect("Uploadscnbkn"); // Redirect ke halaman awal (ke controller exportdatascnbkn fungsi index)
        //redirect("/admin/Uploadscnbkn");
        echo 'OK';
    }

    public function import_kunjungan(){
        // Load plugin PHPExcel nya
        include APPPATH.'third_party/PHPExcel/PHPExcel.php';

        $excelreader = new PHPExcel_Reader_Excel2007();
        $loadexcel = $excelreader->load('file_import/tc_kunjungan.xlsx'); // Load file yang telah diupload ke folder excel
        $sheet = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);



        // Buat sebuah variabel array untuk menampung array data yg akan kita insert ke database
        $data = [];

        $numrow = 1;
        foreach($sheet as $row){
            // Cek $numrow apakah lebih dari 1
            // Artinya karena baris pertama adalah nama-nama kolom
            // Jadi dilewat saja, tidak usah diimport
            if($numrow > 1){
                // Kita push (add) array data ke variabel data
                $no_kunjungan = $this->Import_data_tgl_17_model->get_no_kunjungan($numrow-1);
                array_push($data, [
                    'no_kunjungan'=>$no_kunjungan, 
                    'no_registrasi'=>$row['A'], 
                    'no_mr'=>$row['B'], 
                    'kode_bagian_tujuan'=>$row['C'], 
                    'kode_bagian_asal'=>$row['D'], 
                    /*'tgl_masuk'=>$row['E'], 
                    'tgl_keluar'=>$row['F'], */
                    'status_masuk'=>$row['E'], 
                    'status_keluar'=>$row['F'], 
                    'is_17'=>$row['G'], 
                ]);
            }

            $numrow++; // Tambah 1 setiap kali looping
        }
        /*echo '<pre>';print_r($data);die;*/
        // Panggil fungsi insert_multiple yg telah kita buat sebelumnya di model, disini adalah event handler untuk import kedalam
        // database mapp data dari bkn
        $this->Import_data_tgl_17_model->insert_multiple('tc_kunjungan',$data);
        $this->Import_data_tgl_17_model->update_tgl_kunjungan('2018-09-17 09:53:00.0000000');


        //redirect("Uploadscnbkn"); // Redirect ke halaman awal (ke controller exportdatascnbkn fungsi index)
        //redirect("/admin/Uploadscnbkn");
        echo 'OK';
    }

    public function import_trans_pelayanan(){
        // Load plugin PHPExcel nya
        include APPPATH.'third_party/PHPExcel/PHPExcel.php';

        $excelreader = new PHPExcel_Reader_Excel2007();
        $loadexcel = $excelreader->load('file_import/tc_trans_pelayanan.xlsx'); // Load file yang telah diupload ke folder excel
        $sheet = $loadexcel->getActiveSheet()->toArray(null, true, true ,true);



        // Buat sebuah variabel array untuk menampung array data yg akan kita insert ke database
        $data = [];

        $numrow = 1;
        foreach($sheet as $row){
            // Cek $numrow apakah lebih dari 1
            // Artinya karena baris pertama adalah nama-nama kolom
            // Jadi dilewat saja, tidak usah diimport
            if($numrow > 1){
                // Kita push (add) array data ke variabel data
                $kode = $this->Import_data_tgl_17_model->get_kode_trans_pelayanan($numrow-1);
                array_push($data, [
                    'kode_trans_pelayanan'=>$kode, 
                    'no_kunjungan'=> $row['A'], 
                    'no_registrasi'=> $row['B'], 
                    'no_mr'=>$row['C'], 
                    'nama_pasien_layan'=> (string)$row['D'], 
                    'kode_kelompok'=>$row['E'], 
                    'kode_perusahaan'=>$row['F'], 
                    'jenis_tindakan'=>$row['G'], 
                    'nama_tindakan'=>$row['H'], 
                    'bill_rs'=> floatval((int)$row['I']/10), 
                    'bill_dr1'=> floatval((int)$row['J']/10), 
                    'bill_dr2'=> floatval((int)$row['K']/10), 
                    'lain_lain'=> floatval((int)$row['L']/10), 
                    'kode_dokter1'=>$row['M'], 
                    'jumlah'=> (int)$row['N'], 
                    'kode_barang'=> (string)$row['O'], 
                    'kode_master_tarif_detail'=>$row['P'], 
                    'kd_tr_resep'=>$row['Q'], 
                    'kode_trans_far'=>$row['R'], 
                    'kode_tarif'=>$row['S'], 
                    'kode_bagian'=>$row['T'], 
                    'kode_bagian_asal'=>$row['U'], 
                    'kode_klas'=>$row['V'], 
                    'no_kamar'=>$row['W'], 
                    'no_bed'=>$row['X'], 
                    'kode_penunjang'=>$row['Y'], 
                    'kode_profit'=>$row['Z'], 
                    'status_selesai'=>$row['AA'], 
                    'status_nk'=>$row['AB'], 
                    'kamar_tindakan'=>$row['AC'], 
                    'biaya_lain'=> (int)$row['AD'], 
                    'id_dd_user'=>$row['AE'], 
                    'obat'=> (int)$row['AF'], 
                    'alkes'=> (int)$row['AG'], 
                    'alat_rs'=> (int)$row['AH'], 
                    'adm'=> floatval((int)$row['AI']/10), 
                    'bhp'=> floatval((int)$row['AJ']/10), 
                    'pendapatan_rs'=> floatval((int)$row['AK']/10), 
                    'flag_perawat'=>$row['AL'], 
                    'bill_kjs'=> floatval((int)$row['AM']/10), 
                    'bill_bs_rs'=> floatval((int)$row['AN']/10), 
                    'tgl_transaksi'=>'2018-09-17 09:53:00', 
                    'is_17'=>1, 
                ]);
            }

            $numrow++; // Tambah 1 setiap kali looping
        }
        /*echo '<pre>';print_r($data);die;*/
        // Panggil fungsi insert_multiple yg telah kita buat sebelumnya di model, disini adalah event handler untuk import kedalam
        // database mapp data dari bkn
        $this->Import_data_tgl_17_model->insert_multiple('tc_trans_pelayanan',$data);


        //redirect("Uploadscnbkn"); // Redirect ke halaman awal (ke controller exportdatascnbkn fungsi index)
        //redirect("/admin/Uploadscnbkn");
        echo 'OK';
    }


}
/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
