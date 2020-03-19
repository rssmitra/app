<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Update_applicare extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        $this->load->library("input"); 
        /*load model*/
        $this->load->model('ws_bpjs/Ws_index_model', 'Ws_index');
        $this->kodeppk = '0112R034';
    }

    public function index() { 
        clearstatcache();
        if(!$this->input->is_cli_request())
        {
            echo "This script can only be accessed via the command line" . PHP_EOL;
            return;
        }

        $list = $this->Ws_index->get_data_ruangan();
        $getData = [];
        foreach ($list->result() as $key => $value) {
            $count = $this->countRuangan($value->kode_bagian, $value->kode_klas);
            $getData[$value->kode_bagian][$value->nama_klas_bpjs] = array(
                'kodekelas' => $value->nama_klas_bpjs,
                'koderuang' => $value->kode_bagian,
                'namaruang' => $value->nama_bagian,
                'kapasitas' => $count['kapasitas'],
                'tersedia' => $count['tersedia'],
                'tersediapria' => $count['pria'],
                'tersediapriawanita' => $count['priawanita'],
                'tersediawanita' => $count['wanita'],
                // 'kodekelas' => $value->nama_klas_bpjs,
                );
        }
        
        foreach ($getData as $k => $v) {
            foreach ($v as $vv) {
                $data[] = $vv;
            }
        }
        $response = array();
        
        foreach($data as $row_dt){
            $post_data = array(
                'kodekelas' => $row_dt['kodekelas'],
                'koderuang' => $row_dt['koderuang'],
                'namaruang' => $row_dt['namaruang'],
                'kapasitas' => $row_dt['kapasitas'],
                'tersedia' => $row_dt['tersedia'],
                'tersediapria' => $row_dt['tersediapria'],
                'tersediawanita' => $row_dt['tersediawanita'],
                'tersediapriawanita' => $row_dt['tersediapriawanita'],
                // 'kodekelas' => $row_dt['nama_klas_bpjs'],
                );
                // print_r($post_data);die;
                $response[] = $this->Ws_index->updateRuangan($post_data, $this->kodeppk);
        }
        // echo '<pre>';print_r($post_data);die;
        echo json_encode(array('response' => $response));

        $file = "application/logs/".date('Y_m_d_H_i_s').".log";
        $fp = fopen ($file,'w');

        $data_general = 'Jumlah Record = '.count($data).', Eksekusi = '.count($response).'';
        $data_log = var_export($log, true);

        fwrite($fp,  $data_general."\n".$data_log);
        fclose($fp);

    }

    function countRuangan($kode_bagian, $kode_klas){
        return $count = $this->Ws_index->countRuangan($kode_bagian, $kode_klas);
    }

}


/* End of file example.php */
/* Location: ./application/modules/example/controllers/example.php */
