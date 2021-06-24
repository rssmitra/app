<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Loket extends MX_Controller {

    function __construct() {
        parent::__construct();
 
        $this->load->model('loket_model','loket'); 

        $this->load->model('counter/Counter_model', 'Counter');

        $this->load->model('registration/Reg_pasien_model', 'Reg_pasien');

        $this->load->library('Print_direct');

    }

    public function index() {
   
        $data_loket = $this->loket->get_open_loket();

        foreach ($data_loket as $key => $value) {
            # code...
            $kuota = $this->loket->get_sisa_kuota($value);
            $data_loket[$key]->kuota = $kuota;
        }

        //print_r($_GET['type']);die;
        $data['type'] = $_GET['type'];
        
        $data['klinik'] = $data_loket;

       //echo '<pre>';print_r($data['klinik']);die;
        
        $this->load->view('Loket/index.php',$data);
    }

    public function process()
    {
        # code...
        $data = $_POST['data'];

        $this->db->trans_begin();

        if($data[0]=='umum' or $data[0]=='online'){
            $query="select * from tr_antrian where ant_type = 'umum' or ant_type ='online'";
            $no_ = $this->db->query($query)->num_rows();
            $no = $no_ + 1;
        } else {
            $no_ = $this->db->get_where('tr_antrian',array('ant_type' => 'bpjs'))->num_rows();
            $no = $no_ + 1;
        }

        $dataexc = array(
            'ant_kode_spesialis' => $data[3],
            'ant_kode_dokter' => $data[1],
            'ant_status' => 0,
            'ant_type' => $data[0],
            'ant_date' => date('Y-m-d H:i:s'),
            'ant_no' => $no,
            'ant_panggil' => 0,
            'log' => json_encode(array('dokter' => $data[2],'klinik' => $data[4], 'jam_praktek' => $data[6])),
        );

        $datakuota = array(
            'tanggal' => date('Y-m-d'),
            'kode_dokter' => $dataexc['ant_kode_dokter'],
            'kode_spesialis' => $dataexc['ant_kode_spesialis'], 
            'flag' => 'mesin_antrian', 
        );
        // print_r($datakuota);die;
        /*save antrian */
        $this->loket->save('tr_antrian',$dataexc);

        $this->loket->save('log_kuota_dokter',$datakuota);

        $this->print_direct->printer_antrian_php($dataexc);

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
        }
        else
        {
            $this->db->trans_commit();
            echo json_encode(array('dokter' => $data[2],'klinik' => $data[4], 'jam_praktek' => $data[6],'type' => $data[0],'no' => $no));
        }

    }

    public function process_other()
    {
        # code...
        $this->db->trans_begin();

        $query="select * from tr_antrian where ant_type = '".$_POST['type']."' ";
        $no_ = $this->db->query($query)->num_rows();
        $no = $no_ + 1;

        $dataexc = array(
            'ant_kode_spesialis' => '',
            'ant_kode_dokter' => '',
            'ant_status' => 0,
            'ant_type' => $_POST['type'],
            'ant_date' => date('Y-m-d H:i:s'),
            'ant_no' => $no,
            'ant_panggil' => 0,
            'log' => json_encode(array('dokter' => 'Lainnya','klinik' => $_POST['poli'], 'jam_praktek' => '-' )),
        );

        
        /*save antrian */
        $this->loket->save('tr_antrian',$dataexc);

        $this->print_direct->printer_antrian_php($dataexc);

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
        }
        else
        {
            $this->db->trans_commit();
            echo json_encode(array('dokter' => '', 'klinik' => $_POST['poli'], 'jam_praktek' => '-', 'type' => $_POST['type'] ,'no' => $no));
        }

    }

    public function reload_page() {
   
        /*current counter number*/

        $loket = isset($_POST['loket'])?$_POST['loket']:0;
        $tipe = isset($_POST['tipe'])?$_POST['tipe']:0;

        if($loket!='#'){
            /*tipe antrian*/
            $tipe_antrian = $this->Counter->txt_tipe_loket($tipe);

            /*current*/
            $no_current = $this->Counter->get_antrian_current($_POST);   
            //print_r($this->db->last_query());die;
            $cek = count($no_current);
            //print_r($cek);die;
            /*cek jika tidak ada antrian pada loket*/
            if($cek==0){
                /*get antrian */
                $no_ = $this->Counter->get_antrian($_POST);
                //print_r($this->db->last_query());die;

                if( count($no_) == 0 ){
                    //get antrian pertama untuk loket ini
                    $no = 0;

                }else{
                    $no = $no_->ant_no;
                    $this->Counter->update_current_num_for_first($_POST, $no);
                }

            }else{

                $no = $no_current->ant_no;

            }
           //print_r($cek);die;     

            /*info*/
            $info = $this->Counter->get_counter_total_tipe_loket();


            echo json_encode(array('success' => 1, 'loket' => $_POST['loket'], 'tipe_loket' => $_POST['tipe'] , 'counter' => $no, 'tipe' => $tipe_antrian, 'total_bpjs' => $info['bpjs'], 'sisa_bpjs' => $info['sisa_bpjs'], 'total_non_bpjs' => $info['non_bpjs'], 'sisa_non_bpjs' => $info['sisa_non_bpjs'], 'total_online' => $info['online'], 'sisa_online' => $info['sisa_online']));
        }else{
            echo json_encode(array('success' => 0, 'message' => 'Silahkan pilih loket dan tipe loket'));
        }
        


    }

}

/* End of file empty_module.php */
/* Location: ./application/modules/empty_module/controllers/empty_module.php */

