<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');


class Reg_loket extends MX_Controller {

    /*function constructor*/
    
    function __construct() {

        parent::__construct();
        
        /*breadcrumb default*/
        
        $this->breadcrumbs->push('Index', 'registration/Reg_loket');
        
        /*session redirect login if not login*/
        
        if($this->session->userdata('logged')!=TRUE){
            
            echo 'Session Expired !'; exit;
        
        }
        
        /*load model*/
        
        $this->load->model('Reg_loket_model', 'Reg_loket');

        $this->load->library('Form_validation');
        
        /*enable profiler*/
        
        $this->output->enable_profiler(false);
        
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->
        get_menu_by_class(get_class($this))->name : 'Title';

        /*get day*/
        $this->day = $this->tanggal->getHariFromDate(date('D'));
    
    }

    public function index() { 
        
        /*define variable data*/
        $day = $this->day;

        $data = array(
            
            'title' => $this->title,
            
            'breadcrumbs' => $this->breadcrumbs->show(),

            'loket' => $this->Reg_loket->get_open_loket($day),
        
        );
        
        /*load view index*/
        
        $this->load->view('Reg_loket/index', $data);
    
    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->Reg_loket->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();

            $row[] = '<div class="center"><label class="pos-rel">
                        <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->jd_id.'"/>
                        <span class="lbl"></span>
                        </label></div>';

            if($row_list->is_reschedule==NULL){
                $row[] = '<div class="center"><div class="btn-group">
                                    <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">
                                        Action
                                        <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                                    </button>

                                    <ul class="dropdown-menu dropdown-inverse">
                                        <li><a href="#" onclick="showFormModalStatusLoket('.$row_list->jd_id.')">Status Loket</a></li>
                                        <li><a href="#" onclick="showFormModalUbahJadwal('.$row_list->jd_id.')">Ubah Jadwal</a></li>
                                    </ul>
                                </div>
                            </div>';
            }else{
                $row[] = '<div class="center"><div class="btn-group">
                                    <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">
                                        Action
                                        <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                                    </button>

                                    <ul class="dropdown-menu dropdown-inverse">
                                        <li><a href="#" onclick="showFormModalStatusLoket('.$row_list->jd_id.')">Status Loket</a></li>
                                    </ul>
                                </div>
                            </div>';
            }

            /*check status loket*/
            $status_loket = ($row_list->status_loket=='on')?'checked':'';
            $row[] = '<input name="status_loket" class="ace ace-switch ace-switch-7" type="checkbox" '.$status_loket.' onclick="checked_status('.$row_list->jd_id.', this.value)">
                        <span class="lbl"></span>';
            $row[] = ucwords($row_list->nama_bagian);
            $row[] = $row_list->nama_pegawai;
            $row[] = $this->tanggal->formatTime($row_list->jd_jam_mulai).' s/d '.$this->tanggal->formatTime($row_list->jd_jam_selesai);
            $row[] = '<div class="center">'.$row_list->jd_kuota.'</div>';
            /*cek sisa kuota*/
            $sisa_kuota = $row_list->jd_kuota - $row_list->kuota_terpenuhi;
            $row[] = '<div class="center">'.$sisa_kuota.'</div>';
            $status = ($row_list->status_loket=='on')?'<label class="label label-success">Open</lable>':'<label class="label label-danger">Close</lable>';
            $row[] = $status;
            $status_jadwal = '';
            if(!in_array($row_list->status_jadwal, array('Loket dibuka','Loket ditutup') )){
                $status_jadwal = $row_list->status_jadwal.'<br>';
            }
            $row[] = '<p style="font-size:11px">'.$status_jadwal.''.$row_list->jd_keterangan.'<br>'.$row_list->keterangan.'</p>';
            
                   
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Reg_loket->count_all(),
                        "recordsFiltered" => $this->Reg_loket->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function form_status_loket($jd_id)
    
    {
        
        $data = array();
        /*data jadwal dokter*/
        $result = $this->Reg_loket->get_by_id($jd_id);
        /*load form view*/
        $data['jadwal'] = $result;

        $this->load->view('Reg_loket/form_status_modal', $data);
    
    }

    public function form_ubah_jadwal($jd_id)
    
    {
        
        $data = array();
        /*data jadwal dokter*/
        $result = $this->Reg_loket->get_by_id($jd_id);
        /*load form view*/
        $data['jadwal'] = $result;
        //print_r($data['jadwal'] );die;
        $this->load->view('Reg_loket/form_ubah_jadwal', $data);
    
    }

    public function update_status_loket(){
        $jd_id = $this->input->post('ID');
        /*get data*/
        $get_by_id = $this->Reg_loket->get_by_id($jd_id);
        /*proses update status loket*/
        $this->Reg_loket->proses_update_loket($get_by_id);

        echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan'));

    }

    public function process_update_loket(){
        // form validation
        $this->form_validation->set_rules('status', 'Status', 'trim|required');
        $this->form_validation->set_rules('keterangan', 'Keterangan', 'trim');

        // set message error
        $this->form_validation->set_message('required', "Silahkan isi field \"%s\"");

        if ($this->form_validation->run() == FALSE)
        {
            $this->form_validation->set_error_delimiters('<div style="color:white"><i>', '</i></div>');
            //die(validation_errors());
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            /*execution*/
            $this->db->trans_begin();

            $keterangan = ($_POST['status']=='Reschedule')?'Reschedule to '.$_POST['reschedule_to'].'<br>'.$_POST['keterangan'].'':$_POST['keterangan'];
            $dataexc = array(
                'status_loket' => isset($_POST['status_loket'])?'on':'off',
                'status_jadwal' => $this->regex->_genRegex($this->form_validation->set_value('status'),'RGXQSL'),
                'keterangan' => $this->regex->_genRegex($keterangan,'RGXQSL'),
            );
            
            $this->Reg_loket->update(array('jd_id' => $_POST['jd_id']), $dataexc);
            /*end send notification by sms*/

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan'));
            }

        
        }

    }

    public function process_update_jadwal(){
        // form validation
        if($_POST['tipe_reschedule']=='jam_praktek'){
            $this->form_validation->set_rules('start', 'Jam Mulai', 'trim|required');
            $this->form_validation->set_rules('end', 'Jam Selesai', 'trim|required');
        }else{
            $this->form_validation->set_rules('dokter', 'Dokter Pengganti', 'trim|required');
            $this->form_validation->set_rules('start_pengganti', 'Jam Mulai', 'trim|required');
            $this->form_validation->set_rules('end_pengganti', 'Jam Selesai', 'trim|required');
            $this->form_validation->set_rules('kuota', 'Kuota', 'required');
        }

        $this->form_validation->set_rules('keterangan', 'Keterangan', 'trim');

        // set message error
        $this->form_validation->set_message('required', "Silahkan isi field \"%s\"");

        if ($this->form_validation->run() == FALSE)
        {
            $this->form_validation->set_error_delimiters('<div style="color:white"><i>', '</i></div>');
            //die(validation_errors());
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            /*execution*/
            $this->db->trans_begin();

            $data_ref = $this->Reg_loket->get_by_id($_POST['jd_id']);
            $nama_dokter_ref = $this->db->get_where('mt_karyawan', array('kode_dokter' => $data_ref->jd_kode_dokter))->row();
            $nama_dokter = $this->db->get_where('mt_karyawan', array('kode_dokter' => $this->regex->_genRegex($this->form_validation->set_value('dokter'),'RGXQSL')))->row();

            $ket = ($_POST['tipe_reschedule']=='jam_praktek')?'Reschedule ke jam '.$this->tanggal->formatTime($_POST['start']):'Digantikan oleh dokter '.$nama_dokter->nama_pegawai. ' pukul '.$this->tanggal->formatTime($this->regex->_genRegex($this->form_validation->set_value('start_pengganti'),'RGXQSL')).' WIB';

            /*update jadwal referensi */
            $row = $this->Reg_loket->update(array('jd_id'=> $_POST['jd_id']),array('status_jadwal' => 'Loket ditutup','status_loket' => 'off', 'is_reschedule' => 'Y', 'keterangan' => $ket));

            $dataexc = array(
                'jd_kode_spesialis' => $data_ref->jd_kode_spesialis,
                'jd_hari' => $this->tanggal->getHari(date('D')),
                'status_jadwal' => 'Loket dibuka',
                'status_loket' => 'on',
                'keterangan' => $this->regex->_genRegex($this->form_validation->set_value('keterangan'),'RGXQSL'),
                'flag' => 'sementara',
                'ref_jd_id' => $_POST['jd_id']
                
            );

            if($_POST['tipe_reschedule']=='jam_praktek'){

                $dataexc['jd_kode_dokter'] = $data_ref->jd_kode_dokter;
                $dataexc['jd_jam_mulai'] = $this->tanggal->formatTime($this->regex->_genRegex($this->form_validation->set_value('start'),'RGXQSL'));
                $dataexc['jd_jam_selesai'] = $this->tanggal->formatTime($this->regex->_genRegex($this->form_validation->set_value('end'),'RGXQSL'));
                $dataexc['jd_kuota'] = $data_ref->jd_kuota;                

            } else {

                $dataexc['jd_kode_dokter'] = $this->regex->_genRegex($this->form_validation->set_value('dokter'),'RGXQSL');
                $dataexc['jd_jam_mulai'] = $this->tanggal->formatTime($this->regex->_genRegex($this->form_validation->set_value('start_pengganti'),'RGXQSL'));
                $dataexc['jd_jam_selesai'] = $this->tanggal->formatTime($this->regex->_genRegex($this->form_validation->set_value('end_pengganti'),'RGXQSL'));
                $dataexc['jd_kuota'] = $this->regex->_genRegex($this->form_validation->set_value('kuota'),'RGXINT');

            }

            $newId = $this->Reg_loket->save($dataexc);

            /*update kode dokter yang sudah terlanjur disubmit*/
            /*registrasi*/
            $this->Reg_loket->update_registrasi_kode_dokter( $_POST['jd_id'], $dataexc['jd_kode_dokter'], $newId );
            /*kode dokter pada poli*/
            $this->Reg_loket->update_poli_kode_dokter( $data_ref->jd_kode_dokter, $data_ref->jd_kode_spesialis, $dataexc['jd_kode_dokter']);
            /*kode_dokter pada kunjungan*/
            $this->Reg_loket->update_kunjungan_kode_dokter( $data_ref->jd_kode_dokter, $data_ref->jd_kode_spesialis, $dataexc['jd_kode_dokter']);
            
            /*data for send sms*/
            $data_sms_reg       = $this->Reg_loket->get_data_sms_registrasi($_POST['jd_id']);
            $data_sms_regon     = $this->Reg_loket->get_data_sms_regon($data_ref->jd_kode_dokter, $data_ref->jd_kode_spesialis );
            $data_sms_pesanan   = $this->Reg_loket->get_data_sms_perjanjian($data_ref->jd_kode_dokter, $data_ref->jd_kode_spesialis );
            
            /*merge data*/
            $data_sms = array_merge($data_sms_reg,$data_sms_regon,$data_sms_pesanan);

            /*send notification by sms*/
            $config_sms = array(
                'from' => 'RSSM',
                'data' => $data_sms,
                'message' => '(no-reply) RSSM : Mohon maaf, jadwal praktek '.$nama_dokter_ref->nama_pegawai.' hari ini ' .$ket.'',
            );
            //print_r($config_sms);die;
            /*execution sending sms*/
            $this->api->adsmedia_send_sms_blast($config_sms);
            
            /*end send notification by sms*/

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan'));
            }

        
        }

    }

}



/* End of file example.php */

/* Location: ./application/functiones/example/controllers/example.php */
