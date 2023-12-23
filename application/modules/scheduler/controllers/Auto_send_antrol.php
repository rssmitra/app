<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Auto_send_antrol extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        $this->load->library("input"); 
        $this->load->model('Auto_send_antrol_model');
        $this->load->model('registration/Reg_pasien_model', 'Reg_pasien');
		$this->load->model('ws/AntrianOnlineModel', 'AntrianOnline');
    }

    public function execute(){

        // if(!$this->input->is_cli_request())
        // {
        //     echo "This script can only be accessed via the command line" . PHP_EOL;
        //     return;
        // }
        /*execution*/
        $this->db->trans_begin(); 

        /*define var*/
        $jum_record = 0;
        $jum_record_eksekusi = 0;
        $jum_record_no_eksekusi = 0;
        $log='';

        /*first description*/
        $value =  $this->Auto_send_antrol_model->get_data_registrasi();
        // echo "<pre>"; print_r($value);die;
        if(!empty($value)){
            // cek last antrol
            $last_antrol = $this->getlisttask($value->kodebooking);
            // echo "<pre>"; print_r($last_antrol);die;
            if($last_antrol != false){
                if($last_antrol < 5){
                    $kodebooking = $value->kodebooking;
                    $jam_masuk_registrasi = date_create($value->created_date);
                    // $jam_masuk_registrasi = date_create(date('Y-m-d H:i:s'));
                    for ($i=$last_antrol; $i < 5; $i++) { 
                        $taskid = $i + 1;
                        $newtimestamp = strtotime(''.date('Y-m-d H:i:s').' + '.$i.'5 minute');
                        // $waktukirim = $newtimestamp * 1000;
                        $waktukirim = strtotime(date('Y-m-d H:i:s')) * 1000;
                        $execute_task = $this->AntrianOnline->postDataWs('antrean/updatewaktu', array('kodebooking' => $kodebooking, 'taskid' => $taskid, 'waktu' => $waktukirim));
                        echo "Kode Booking ".$kodebooking." execute to ".$taskid." <br>" . PHP_EOL;
                    }
                    return $this->execute();
                }else{
                    $this->db->where('kodebookingantrol', $value->kodebooking)->update('tc_registrasi', array('task_id' => $last_antrol));
                    return $this->execute();
                }
            }else{
                // update task
                $this->db->where('kodebookingantrol', $value->kodebooking)->update('tc_registrasi', array('task_id' => 99));
                return $this->execute();
            }
        }

        // $file = "application/logs/antrl_".date('Y_m_d_H_i_s').".log";
        // $fp = fopen ($file,'w');

        // $data_general = 'Jumlah Record = '.$jum_record.', Eksekusi = '.$jum_record_eksekusi.', No Eksekusi = '.$jum_record_no_eksekusi.' ';
        // $data_log = var_export($log, true);

        // fwrite($fp,  $data_general."\n".$data_log);
        // fclose($fp);

    }

    public function getlisttask($kodebooking){
        $result = $this->AntrianOnline->postDataWs('antrean/getlisttask', array('kodebooking' => $kodebooking));
        // echo "<pre>"; print_r($result);die;
        if($result['response']->metadata->code == 200){
            $antrol = $result['data'];
            $max_key = max(array_keys($antrol));
            $getKeyData = $antrol[$max_key];
            $last_antrol = $getKeyData->taskid;
            return $last_antrol;
        }else{
            return false;
        }
        
    }


}
/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
