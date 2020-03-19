<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Counter extends MX_Controller {

    function __construct() {
        parent::__construct();
 
        $this->load->model('counter_model','counter'); 

    }

    public function index() {
   
       // print_r($data['klinik']);die;
        
        $this->load->view('Counter/index.php');
    }

    public function process_save_session_counter(){
        
        $data = $_POST['data'];
        $session = array(
            'loket' => $data[0],
            'tipe' => $data[1],
            );
        /*save data on session*/
        $this->session->set_userdata(array('counter' => (object)$session));

        /*tipe antrian*/
        $tipe_antrian = $this->counter->txt_tipe_loket($data[1]);

        /*current*/
        $no_current = $this->counter->get_antrian_current($data);

        $cek = count($no_current);

        if($cek==0){
            /*get antrian */
            $no_ = $this->counter->get_antrian($data);
            if( count($no_) == 0 ){
                $no = 1;
            }else{
                $no = $no_->ant_no;
            }
        }else{
            $no = $no_current->ant_no;
        }

        echo json_encode(array('success' => 1, 'sess_counter_data' => $this->session->userdata('counter'), 'counter' => $no, 'tipe' => $tipe_antrian));

    }

    public function process_play()
    {
        /*get antrian */
        
        $this->db->trans_begin();
        
        /*update antrian */
        $this->counter->update_last_status_aktif_loket($_POST, $_POST['curr_num']);

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
        }
        else
        {
            $this->db->trans_commit();
            echo json_encode(array('no' => $_POST['curr_num'], 'type' => $_POST['tipe'], 'loket' => $_POST['loket'] ));
        }

    }

    public function process_selesai()
    {
        /*get antrian */
    
        $this->db->trans_begin();
               
        /*update antrian */
        $this->counter->update('tr_antrian',array('ant_status' => 1, 'ant_aktif' => 2, 'ant_loket' => $_POST['loket']), array('ant_no' => $_POST['curr_num'], 'ant_type' => $_POST['tipe']));
        //print_r($this->db->last_query());die;

        /*get next*/
        $next_num = $this->counter->get_next_counter_number( $_POST );

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
        }
        else
        {
            $this->db->trans_commit();
            echo json_encode(array('no' => $_POST['curr_num'],'type' => $_POST['tipe'],'loket' => $_POST['loket'], 'next_num' => $next_num, 'next_num_int' => $this->counter->next_counter_number($_POST), 'info' => $this->counter->get_counter_info( $_POST ) ));
        }

    }

    public function process_skip()
    {

        /*get antrian */
    
        $this->db->trans_begin();
               
        /*update antrian */
        /*$this->counter->update('tr_antrian',array('ant_status' => 2, 'ant_loket' => $data[0] ), array('ant_no' => $data[2], 'ant_type' => $data[1] ));*/

        /*get next*/
        $next_num = $this->counter->get_next_counter_number( $_POST );

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
        }
        else
        {
            $this->db->trans_commit();
            echo json_encode(array('no' => $_POST['curr_num'],'type' => $_POST['tipe'],'loket' => $_POST['loket'], 'next_num' => $next_num, 'next_num_int' => $this->counter->next_counter_number($_POST), 'info' => $this->counter->get_counter_info($_POST) ));
        }

    }

    public function process_previous()
    {
        # code...
        $data = $_POST['data'];

        /*get antrian */
    
        $this->db->trans_begin();
        
         /*update antrian */
        $this->counter->update('tr_antrian',array('ant_loket' => $data[0]),array('ant_no' => $data[2], 'ant_type' => $data[1], 'ant_loket' => $data[0] ));

        /*get previous*/
        $prev_num = $this->counter->get_previous_counter_number($data);

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
        }
        else
        {
            $this->db->trans_commit();
            echo json_encode(array('no' => $data[2], 'type' => $data[1],'loket' => $data[0], 'prev_num' => $prev_num, 'prev_num_int' => $this->counter->prev_counter_number($data), 'info' => $this->counter->get_counter_info() ));
        }

    }

}

/* End of file empty_module.php */
/* Location: ./application/modules/empty_module/controllers/empty_module.php */

