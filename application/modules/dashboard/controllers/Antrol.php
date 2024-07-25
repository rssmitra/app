<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Antrol extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'setting/Antrol');
        /*session redirect login if not login*/
        // if($this->session->userdata('logged')!=TRUE){
        //     echo 'Session Expired !'; exit;
        // }
        /*load model*/
        $this->load->model('Antrol_model', 'Antrol');
        $this->load->model('registration/Reg_pasien_model', 'Reg_pasien');
        $this->load->model('ws/AntrianOnlineModel', 'AntrianOnline');
        /*enable profiler*/
        $this->output->enable_profiler(false);
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'Title';

    }

    public function index() { 
        //echo '<pre>';print_r($this->session->all_userdata());
        /*define variable data*/
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show()
        );
        /*load view index*/
        $this->load->view('Antrol/index', $data);
    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->Antrol->get_datatables();
        $dt_reg = $this->Antrol->get_data_registrasi();
        // echo "<pre>"; print_r($list);die;

        $data = array();
        $getList = array();
        $data = array();
        $data2 = array();
        $getRespMsg = array();
        $getTotalData = array();

        $arrtask_success[1] = [];
        $arrtask_success[2] = [];
        $arrtask_success[3] = [];
        $arrtask_success[4] = [];
        $arrtask_success[5] = [];
        $arrtask_success[6] = [];
        $arrtask_success[7] = [];

        // task failed
        $arrtask_failed[1] = [];
        $arrtask_failed[2] = [];
        $arrtask_failed[3] = [];
        $arrtask_failed[4] = [];
        $arrtask_failed[5] = [];
        $arrtask_failed[6] = [];
        $arrtask_failed[7] = [];

        $no = $_POST['start'];
        
        foreach($list as $key=>$row){
            $rowdt = (array)$row;
            // $getList[$rowdt['kodebooking']] = $rowdt;
            // $getList[$rowdt['kodebooking']]['arr_taskid'] = $this->getListTask($list, $rowdt['kodebooking']);
            $getList[$rowdt['kodebooking']][$rowdt['taskid']] = $rowdt;
            $getRespMsg[$rowdt['response_msg']][] = $rowdt;
            $getTotalData[] = $rowdt;
        }

        $countResp = [];
        $other = [];
        foreach ($getRespMsg as $kr => $vr) {
            # code...
            if(count($getRespMsg[$kr]) > 1){
                $countResp[$kr] = count($getRespMsg[$kr]);
            }else{
                $other[] = count($getRespMsg[$kr]);
            }
        }

        $countResp['Error Lainnya'] = count($other);
        
        
        foreach ($getList as $key_list=>$row_list) {
            $no++;
            $row = array();
            $no_registrasi = isset($row_list[1]['no_registrasi'])?$row_list[1]['no_registrasi']:'';
            
            $row[] = '<div class="center">'.$no.'</div>';
            $row[] = '<div class="center"><a href="#" style="font-weight: bold; color: blue" onclick="show_modal('."'registration/reg_pasien/view_detail_resume_medis/".$no_registrasi."'".')">'.$key_list.'</a></div>';
            for($i=1; $i<8; $i++) {
                $idt = isset($row_list[$i]) ? $row_list[$i] : [];
                $convert_milisecod = isset($idt['timestamp']) ? $this->tanggal->formatDateTime(date('Y-m-d H:i:s', $idt['timestamp']/1000)) : '';
                if(!empty($idt)){
                    $is_success = ($idt['response_code'] == 200) ? '<i class="fa fa-check-circle green bigger-150"></i>' : '<i class="fa fa-times-circle red bigger-150"></i>' ;
                    $is_success_msg = ($idt['response_code'] == 200) ? '<br>'.$convert_milisecod : '<br>'.$idt['response_msg'] ;
                    $btn_reload = ($idt['response_code'] == 200) ? '': '<br><a href="#" class="label label-xs label-default" onclick="resend_antrol('."'".$key_list."'".', '.$i.')"><i class="fa fa-refresh"></i> Kirim ulang</a>';

                    $row[] = '<div class="center" id="span_'.$key_list.'_'.$i.'">'.$is_success.$is_success_msg.$btn_reload.'</div>';
                    $task[$key_list][$i] = ($idt['response_code'] == 200) ? 1 : 0 ;
                    $task_fail[$key_list][$i] = ($idt['response_code'] != 200) ? 1 : 0 ;
                    $task_fail_msg[$key_list][$i] = ($idt['response_code'] != 200) ? $idt['response_msg'] : 0 ;
                }else{
                    $row[] = '<div class="center" id="span_'.$key_list.'_'.$i.'" ><a href="#" class="label label-xs label-default" onclick="resend_antrol('."'".$key_list."'".', '.$i.')"><i class="fa fa-refresh"></i> Kirim ulang</a></div>';
                    $task[$key_list][$i] = 0 ;
                    $task_fail[$key_list][$i] = 1 ;
                    $task_fail_msg[$key_list][$i] = 'NULL/Belum terkirim';
                }
                
            }
            $data[] = $row;
            
            // task success
            $arrtask_success[1][] = $task[$key_list][1];
            $arrtask_success[2][] = $task[$key_list][2];
            $arrtask_success[3][] = $task[$key_list][3];
            $arrtask_success[4][] = $task[$key_list][4];
            $arrtask_success[5][] = $task[$key_list][5];
            $arrtask_success[6][] = $task[$key_list][6];
            $arrtask_success[7][] = $task[$key_list][7];

            // task failed
            $arrtask_failed[1][] = $task_fail[$key_list][1];
            $arrtask_failed[2][] = $task_fail[$key_list][2];
            $arrtask_failed[3][] = $task_fail[$key_list][3];
            $arrtask_failed[4][] = $task_fail[$key_list][4];
            $arrtask_failed[5][] = $task_fail[$key_list][5];
            $arrtask_failed[6][] = $task_fail[$key_list][6];
            $arrtask_failed[7][] = $task_fail[$key_list][7];
        }

        // get data registrasi
        foreach ($dt_reg as $key_list=>$row_dt) {
            $no++;
            $row = array();
            $row[] = '<div class="center">'.$no.'</div>';
            $row[] = '<div class="center"><a href="" style="font-weight: bold; color: blue">'.$row_dt->kodebookingantrol.'</a></div>';
            for($i=1; $i<8; $i++) {
                $row[] = '<div class="center" id="span_'.$row_dt->kodebookingantrol.'_'.$i.'" ><a href="#" class="label label-xs label-default" onclick="resend_antrol('."'".$row_dt->kodebookingantrol."'".', '.$i.', '."'no_registrasi'".')"><i class="fa fa-refresh"></i> Kirim ulang</a></div>';
                $task[$key_list][$i] = 0 ;
                $task_fail2[$key_list][$i] = 1 ;
            }
            $data2[] = $row;

            // task failed
            $arrtask_failed[1][] = $task_fail2[$key_list][1];
            $arrtask_failed[2][] = $task_fail2[$key_list][2];
            $arrtask_failed[3][] = $task_fail2[$key_list][3];
            $arrtask_failed[4][] = $task_fail2[$key_list][4];
            $arrtask_failed[5][] = $task_fail2[$key_list][5];
            $arrtask_failed[6][] = $task_fail2[$key_list][6];
            $arrtask_failed[7][] = $task_fail2[$key_list][7];

        }

        $merge_data = array_merge($data, $data2);
        
        // echo "<pre>"; print_r($merge_data);die;

        $output = array(
                        "draw" => $_POST['draw'],
                        "data" => $merge_data,
                        // task success
                        "task_1" => array_sum($arrtask_success[1]),
                        "task_2" => array_sum($arrtask_success[2]),
                        "task_3" => array_sum($arrtask_success[3]),
                        "task_4" => array_sum($arrtask_success[4]),
                        "task_5" => array_sum($arrtask_success[5]),
                        "task_6" => array_sum($arrtask_success[6]),
                        "task_7" => array_sum($arrtask_success[7]),
                        // task fail
                        "task_fail_1" => array_sum($arrtask_failed[1]),
                        "task_fail_2" => array_sum($arrtask_failed[2]),
                        "task_fail_3" => array_sum($arrtask_failed[3]),
                        "task_fail_4" => array_sum($arrtask_failed[4]),
                        "task_fail_5" => array_sum($arrtask_failed[5]),
                        "task_fail_6" => array_sum($arrtask_failed[6]),
                        "task_fail_7" => array_sum($arrtask_failed[7]),
                        // rekap response msg
                        "rekap_msg" => $countResp,
                        "total_data_all_task" => array_sum($getTotalData),
                );
        //output to json format
        echo json_encode($output);
    }

    public function getListTask($list, $kodebooking){
        $getList = [];
        foreach($list as $key=>$row){
            $rowdt = (array)$row;
            $getList[$rowdt['kodebooking']][$rowdt['taskid']] = $rowdt;
        }

        return $getList[$kodebooking];
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

        $detail_data = $this->Reg_pasien->get_detail_resume_medis($rowdt->no_registrasi);
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
            echo json_encode($response);
        }else{
            // update task antrol
            $updateTask = $this->AntrianOnline->update_task_antrol($kodebooking, $taskid, $dt_reg->tgl_jam_masuk);
            $convert_milisecod = date('Y-m-d H:i:s', $updateTask['waktu']/1000);
            $response = ['code' => $updateTask['response_code'], 'msg' => $updateTask['response_msg'], 'time' => $convert_milisecod];
            // echo '<pre>';print_r($convert_milisecod);die;
            echo json_encode($response);
        }
    }

    public function decrypt(){
        $string = 'eyJub2thcHN0IjoiMDAwMjQ0MTM0Njk3NCIsImtvZGVCb29raW5nIjoiMDExMlIwMzQwNzI0SUpTSTdYIiwibm9SdWp1a2FuIjoiMDkwMjA3MDAwNzI0UDAwODMwMCIsIm5vcm0iOm51bGwsImtldEt1bmp1bmdhbiI6IlJ1anVrYW4gRktUUCIsIm5hbWFGYXNrZXNBc2FsUnVqdWsiOiJSUyBTRVRJQSBNSVRSQSIsIm5hbWFQb2xpIjoiTUFUQSIsIm5hbWFEb2t0ZXIiOiJkci4gRGlhbmkgRHlhaCBTYXJhc3dhdGksIFNwIE0iLCJub21vckFudHJlYW4iOiJNQVQtMTEifQ==';
        $strdecrpt = $this->AntrianOnline->stringDecryptString($string);
        echo "<pre>"; echo json_decode($strdecrpt);die;
        $decompress = $this->AntrianOnline->decompress($string);

    }


}


/* End of file example.php */
/* Location: ./application/modules/example/controllers/example.php */
