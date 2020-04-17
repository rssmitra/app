<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Save_log_billing_dr extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        $this->load->library("input"); 
    }

    public function index(){

        if(!$this->input->is_cli_request())
        {
            echo "This script can only be accessed via the command line" . PHP_EOL;
            return;
        }

        $qry_dt = "SELECT kode_trans_pelayanan, kode_tc_trans_kasir, tgl_jam, tgl_transaksi, no_registrasi, no_mr, nama_pasien_layan, 
            nama_tindakan, kode_dokter1, kode_dokter2, CAST(bill_dr1 as INT) as bill_dr1, CAST(bill_dr2 as INT) as bill_dr2
            FROM view_log_billing_dokter
            WHERE nama_pasien_layan not like '%percobaan%' AND YEAR(tgl_jam) >= 2019 and send_log_bill_dr IS NULL";
        $exc_qry =  $this->db->query($qry_dt)->result();
        // echo '<pre>'; print_r($exc_qry); die;
        $getData = array();
        $log = array();
        foreach ($exc_qry as $key => $value) {
            $getData[] = array(
                'kode_trans_pelayanan' => $value->kode_trans_pelayanan,
                'kode_tc_trans_kasir' => $value->kode_tc_trans_kasir,
                'tgl_jam' => $value->tgl_jam,
                'tgl_transaksi' => $value->tgl_transaksi,
                'nama_tindakan' => $value->nama_tindakan,
                'no_registrasi' => $value->no_registrasi,
                'no_mr' => $value->no_mr,
                'nama_pasien_layan' => $value->nama_pasien_layan,
                'kode_dokter1' => $value->kode_dokter1,
                'kode_dokter2' => $value->kode_dokter2,
                'bill_dr1' => $value->bill_dr1,
                'bill_dr2' => $value->bill_dr2,
                );
            $this->db->update('tc_trans_pelayanan', array('send_log_bill_dr' => 1), array('kode_trans_pelayanan' => $value->kode_trans_pelayanan) );
            $log[] = $value;
        }
        $this->db->insert_batch('log_billing_dr', $getData);

        $file = "application/logs/".date('Y_m_d_H_i_s').".log";
        $fp = fopen ($file,'w');

        $data_general = 'Execute query : '.$this->db->last_query().'';
        $data_log = var_export($log, true);

        fwrite($fp,  $data_general."\n".$data_log);
        fclose($fp);
        

    }


}
/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
