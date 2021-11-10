<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Auto_hitung_ruangan extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        $this->load->library("input"); 
        $this->load->model('Auto_hitung_ruangan_model');
    }

    public function index(){

        if(!$this->input->is_cli_request())
        {
            echo "This script can only be accessed via the command line" . PHP_EOL;
            return;
        }

        /*execution*/
        $this->db->trans_begin();    

        /*first description*/
        $data_pasien =  $this->Auto_hitung_ruangan_model->get_data();

        //echo '<pre>'; print_r($data_pasien);die;

        /*define var*/
        $jum_record = 0;
        $jum_record_eksekusi = 0;
        $jum_record_no_eksekusi = 0;
        $log='';

        /*loop data*/
        foreach($data_pasien as $key=>$value){
                        
            if($value->bag_pas!='030001' AND $value->bag_pas!='030501' AND $value->bag_pas!='030901'){
                 /*define first*/
                $kode_bagian = $value->bag_pas;	
                $bagian = $value->nama_bagian;		
                $kode_klas = $value->kelas_pas;
                $no_registrasi	= $value->no_registrasi;
                $no_mr = $value->no_mr;
                $kode_kelompok	= $value->kode_kelompok;
                $kode_perusahaan = $value->kode_perusahaan;
                $kode_ri = $value->kode_ri;
                $kode_ruangan = $value->kode_ruangan;
                $no_kunjungan = $value->no_kunjungan;
                $flag_paket = $value->flag_paket;
        

                if($flag_paket != 1 ) {
                    if($value->pasien_titipan == 1 ){

                        $kode_klas_titipan = $value->kelas_titipan;

                        /*data tarif */
                        $data_tarif = $this->Auto_hitung_ruangan_model->get_tarif_by_klas($kode_klas_titipan);

                        if ($kode_kelompok == 3 && $kode_perusahaan == 120) {
                            $tarif =  $data_tarif->harga_bpjs;
                            if ($tarif < 1) {
                                $tarif = $data_tarif->harga_r;
                            }
                        }else{
                            $tarif = $data_tarif->harga_r;
                        }

                    }else{

                        /*data tarif */
                        $data_tarif = $this->Auto_hitung_ruangan_model->get_tarif_by_kode_bag($kode_bagian,$kode_klas);

                        if ($kode_kelompok == 3 && $kode_perusahaan == 120) {
                            $tarif =  $data_tarif->harga_bpjs;	
                            if ($tarif < 1) {
                                $tarif = $data_tarif->harga_r;
                            }
                        }else{
                            $tarif = $data_tarif->harga_r;
                        }

                    }
                    
                    $trans =  $this->Auto_hitung_ruangan_model->cek_trans($no_kunjungan);

                    $data_ruangan = $this->Auto_hitung_ruangan_model->get_ruangan($value->kode_ruangan);

                    $dataexc = array(
                        'no_kunjungan' => $no_kunjungan,
                        'no_registrasi' => $no_registrasi,
                        'no_mr' => $no_mr,
                        'nama_pasien_layan' => $value->nama_pasien,
                        'kode_kelompok' => $kode_kelompok,
                        'kode_perusahaan' => $kode_perusahaan,
                        'tgl_transaksi' => date('Y-m-d H:i:s'),
                        'jenis_tindakan' => 1,
                        'nama_tindakan' => 'Ruangan '.$bagian,
                        'bill_rs' => $tarif,
                        'jumlah' => 1,
                        'kode_bagian' => $kode_bagian,
                        'kode_bagian_asal' => $kode_bagian,
                        'kode_klas' => $kode_klas,
                        'no_kamar' => $data_ruangan->no_kamar,
                        'no_bed' => $data_ruangan->no_bed,
                        'status_selesai' => 1,
                        'id_dd_user' => 1
                    );

                    // echo '<pre>'; print_r($dataexc);

                    if(!isset($trans)){

                        $kode_trans_pelayanan = $this->master->get_max_number('tc_trans_pelayanan', 'kode_trans_pelayanan');

                        $dataexc['kode_trans_pelayanan'] = $kode_trans_pelayanan;

                        $this->Auto_hitung_ruangan_model->save('tc_trans_pelayanan', $dataexc );

                        //echo '<pre>'; print_r($dataexc);
                        $this->db->trans_commit();

                        $dataexc['executed'] = 'executed';

                        $log[] = $dataexc;

                        $jum_record_eksekusi++;

                    }else{

                        $dataexc['kode_trans_pelayanan'] = $trans->kode_trans_pelayanan;
                        $dataexc['executed'] = 'not_executed';

                        $log[] = $dataexc;

                        $jum_record_no_eksekusi++;

                    }

                    $jum_record++;

                }
            }
        
        }

        $file = "application/logs/".date('Y_m_d_H_i_s').".log";
        $fp = fopen ($file,'w');

        $data_general = 'Jumlah Record = '.$jum_record.', Eksekusi = '.$jum_record_eksekusi.', No Eksekusi = '.$jum_record_no_eksekusi.' ';
        $data_log = var_export($log, true);

        fwrite($fp,  $data_general."\n".$data_log);
        fclose($fp);
        

    }


}
/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
