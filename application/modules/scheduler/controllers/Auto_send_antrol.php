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
        // load modul antrol
        $this->load->module('dashboard/Antrol');
        
    }

    public function execute(){

        if(!$this->input->is_cli_request())
        {
            echo "This script can only be accessed via the command line" . PHP_EOL;
            return;
        }

        $execute = $this->Auto_send_antrol_model->execute_all_task();

        /*execution*/
        echo " UPDATE ALL TASK ANTROL " . PHP_EOL;
        echo " Sedang dalam proses pengiriman data" . PHP_EOL;
        echo " Mohon ditunggu  ..." . PHP_EOL;
        echo " ================================================ " . PHP_EOL;

        $this->db->trans_begin(); 
        /*first description*/
        if(!empty($execute)){
            $no=0;
			foreach ($execute as $key => $value) {
                $no++;
				# code...
				$kodebooking = (!empty($value->kodebookingantrol))?$value->kodebookingantrol:$value->no_registrasi;
                echo " Kode Booking. ".$kodebooking." " . PHP_EOL;
				$flag = (!empty($value->kodebookingantrol))?'':'no_registrasi';
				$last_task = $this->AntrianOnline->cekAntrolKodeBooking($kodebooking);
				// get max keys
				$max_key = (empty($last_task['data'])) ? 1 : max(array_keys($last_task['data']));
				if($max_key >= 5){
                    echo " Selesai sampai dengan task  ".$max_key." " . PHP_EOL;
					// update autosendantrol
					$this->db->where('kodebookingantrol', (string)$kodebooking )->update('tc_registrasi', array('autosendantrol' => 1));
					$this->db->trans_commit();
					// echo "<pre>"; print_r($this->db->last_query());die;
				}
				// loop till task 5
				$response = [];
				for($i=$max_key+1; $i<8; $i++){
					// update task
					$execute_task = $this->resend_antrol($kodebooking, $i, $flag);
                    $implode = implode(" | ", $execute_task);
					// $response[$kodebooking][$i] = $execute_task;
                    echo " Proses update task ".$i."" . PHP_EOL;
                    echo " Status. ".$implode."" . PHP_EOL;
                    echo " ============================================ " . PHP_EOL;
                    // echo "<pre>"; print_r($execute_task);die;
				}
				// $arr_last_task[$kodebooking] = $last_task['data'];
			}

		}else{
			echo " Tidak ada data untuk diproses " . PHP_EOL;
		}
    }

    public function execute_first_task(){

        if(!$this->input->is_cli_request())
        {
            echo "This script can only be accessed via the command line" . PHP_EOL;
            return;
        }

        /*execution*/
        echo " UPDATE FIRST TASK ANTROL " . PHP_EOL;
        echo " Sedang dalam proses pengiriman data" . PHP_EOL;
        echo " Mohon ditunggu  ..." . PHP_EOL;
        echo " ================================================ " . PHP_EOL;

        $execute = $this->Auto_send_antrol_model->execute_first_task();
        
        /*execution*/
        $this->db->trans_begin(); 
        if(!empty($execute)){
            foreach ($execute as $key => $value) {
                # code...
				$kodebooking = (!empty($value->kodebookingantrol))?$value->kodebookingantrol:$value->no_registrasi;
                // echo "<pre>"; print_r($kodebooking);die;
                echo " Kode Booking. ".$kodebooking." " . PHP_EOL;
				$flag = (!empty($value->kodebookingantrol))?'':'no_registrasi';
                for($i=0; $i<=3; $i++){
                    $last_task = $this->AntrianOnline->cekAntrolKodeBooking($kodebooking);
                    // resend for the first task
                    $execute_task = $this->resend_antrol($kodebooking, $i, $flag);
                    $implode = implode(" | ", $execute_task);
                    // $response[$kodebooking][$i] = $execute_task;
                    echo " Proses update task ".$i." " . PHP_EOL;
                    echo " Status. ".$implode." " . PHP_EOL;
                    echo " ============================================ " . PHP_EOL;
                }
				
			}
		}else{
			echo " Tidak ada data untuk diproses " . PHP_EOL;
		}
        echo " Proses selesai " . PHP_EOL;
    }

    function resend_antrol($kodebooking, $taskid, $flag=''){

        // get data registrasi
        if($flag == 'no_registrasi'){
            $rowdt = $this->db->get_where('tc_registrasi', ['no_registrasi' => $kodebooking])->row();
            // update kodebooking antrol
            $this->db->where(['no_registrasi' => $kodebooking])->update('tc_registrasi', ['kodebookingantrol' => $kodebooking]);
        }else{
            $rowdt = $this->db->get_where('tc_registrasi', ['kodebookingantrol' => $kodebooking])->row();
        }

        $detail_data = $this->Auto_send_antrol_model->get_detail_resume_medis($rowdt->no_registrasi);
        // echo "<pre>"; print_r($detail_data);die;
        $dt_reg = $detail_data['registrasi'];
        $dt_antrian = $detail_data['no_antrian'];
        $dt_jadwal = $detail_data['jadwal'];

        // jadwal praktek
        $jam_praktek_mulai = ($dt_jadwal->jd_jam_mulai) ? $this->tanggal->formatTime($dt_jadwal->jd_jam_mulai) : '08:00';
        $jam_praktek_selesai = ($dt_jadwal->jd_jam_selesai) ? $this->tanggal->formatTime($dt_jadwal->jd_jam_selesai) : '10:00';
        $kuota_dr = ($dt_jadwal->jd_kuota) ? $dt_jadwal->jd_kuota : 10;

        // jenis kunjungan
        $jeniskunjungan = ($dt_reg->jeniskunjunganbpjs > 0) ? $dt_reg->jeniskunjunganbpjs : 3;
        $tanggalperiksa = $this->tanggal->formatDateBPJS($this->tanggal->formatDateTimeToSqlDate($dt_reg->tgl_jam_masuk));
        $jam_mulai_praktek = $this->tanggal->formatFullTime($jam_praktek_mulai);
        $jam_selesai_praktek = $this->tanggal->formatFullTime($jam_praktek_selesai);
        $date = date_create($this->tanggal->formatDateTimeToSqlDate($tanggalperiksa).' '.$jam_mulai_praktek );
        $nomorantrean = $dt_antrian->no_antrian;
        $angkaantrean = $dt_antrian->no_antrian;
        
        $est_hour = ceil($nomorantrean / 12);
        $estimasi = ($nomorantrean <= 12) ? 1 : $est_hour; 
        
        // estimasi dilayani
        date_add($date, date_interval_create_from_date_string('+'.$estimasi.' hours'));
        $estimasidilayani = date_format($date, 'Y-m-d H:i:s');
        $milisecond = strtotime($estimasidilayani) * 1000;

		$kuota = round($kuota_dr/2);
        $sisa_kuota = $kuota - $angkaantrean;

        if ($taskid == 1) {
            # add antrol for the first
            $config_antrol = array(
                "no_registrasi" => $dt_reg->no_registrasi,
                'jam_praktek_mulai' => $jam_praktek_mulai,
                'jam_praktek_selesai' => $jam_praktek_selesai,
                'kuota_dr' => $kuota_dr,
                "kodebooking" => ($flag == 'no_registrasi') ? $dt_reg->no_registrasi :$dt_reg->kodebookingantrol,
                "jenispasien" => "JKN",
                "nomorkartu" => $dt_reg->no_kartu_bpjs,
                "nik" => $dt_reg->no_ktp,
                "nohp" => (int)$dt_reg->no_hp,
                "kodepoli" => $dt_reg->kode_poli_bpjs,
                "namapoli" => $dt_reg->nama_bagian,
                "pasienbaru" => 0,
                "norm" => $dt_reg->no_mr,
                "tanggalperiksa" => $this->tanggal->formatDateBPJS($this->tanggal->formatDateTimeToSqlDate($dt_reg->tgl_jam_masuk)),
                "kodedokter" => $dt_reg->kode_dokter_bpjs,
                "namadokter" => $dt_reg->nama_pegawai,
                "jampraktek" => $jam_praktek_mulai.'-'.$jam_praktek_selesai,
                "jeniskunjungan" => $jeniskunjungan,
                "nomorreferensi" => $dt_reg->norujukan,
                "nomorantrean" => $dt_reg->kode_poli_bpjs.'-'.$dt_antrian->no_antrian,
                "angkaantrean" => $dt_antrian->no_antrian,
                "estimasidilayani" => $milisecond,
                "sisakuotajkn" => ($sisa_kuota > 0) ? $sisa_kuota : 1,
                "kuotajkn" => $kuota,
                "sisakuotanonjkn" => ($sisa_kuota > 0) ? $sisa_kuota : 1,
                "kuotanonjkn" => $kuota,
                "keterangan" => "Silahkan tensi dengan perawat"
            );
            
            $addAntrian = $this->AntrianOnline->addAntrianOnsite($config_antrol, $dt_reg->tgl_jam_masuk);
            $milisecond = strtotime($dt_reg->tgl_jam_masuk) * 1000;
            $convert_milisecod = date('Y-m-d H:i:s', $milisecond/1000);
            $response = ['code' => $addAntrian['response_code'], 'msg' => $addAntrian['response_msg'], 'time' => $convert_milisecod];
            // echo '<pre>';print_r($config_antrol);die;
            return $response;
        }else{
            // update task antrol
            $updateTask = $this->AntrianOnline->update_task_antrol($kodebooking, $taskid, $dt_reg->tgl_jam_masuk);
            $convert_milisecod = date('Y-m-d H:i:s', $updateTask['waktu']/1000);
            $response = ['code' => $updateTask['response_code'], 'msg' => $updateTask['response_msg'], 'time' => $convert_milisecod];
            // echo '<pre>';print_r($convert_milisecod);die;
            return $response;
        }
    }

}
/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
