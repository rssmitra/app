<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pl_input_vital_sign extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'pelayanan/Pl_input_vital_sign');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('Pl_input_vital_sign_model', 'Pl_input_vital_sign');
        $this->load->model('Pl_pelayanan_model', 'Pl_pelayanan');
        $this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
        /*load library*/
        $this->load->library('Form_validation');
        /*enable profiler*/
        $this->output->enable_profiler(false);

        $this->load->module('casemix/Csm_billing_pasien');
        $this->cbpModule = new Csm_billing_pasien;
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'Title';
        
    }

    public function index() { 
        /*define variable data*/
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show()
        );
        $this->load->view('Pl_input_vital_sign/index', $data);
    }

    public function assesmen_rj($id='', $no_kunjungan)
    {
        $data = array(
            'title' => 'Assesmen Awal Pasien Rawat Jalan',
            'breadcrumbs' => $this->breadcrumbs->show()
        );
        /*get value by id*/
        $data['value'] = $this->Pl_pelayanan->get_by_id($id);


        $this->load->module('Templates/Templates.php');
        $temp = new Templates;
        $result = json_decode($this->Csm_billing_pasien->getDetailData($data['value']->no_registrasi));
        $result->nama_ppa = $result->reg_data->nama_pegawai;
        $result->kode_dr = $result->reg_data->kode_dokter;
        // echo '<pre>';print_r($result);die;
        // header cppt
        $header = $temp->setGlobalProfileCppt($result);
        $footer = $temp->setGlobalFooterCppt($result);
        $data['header'] = $header;
        $data['footer'] = $footer;

        $data['kode_bagian'] = $data['value']->kode_bagian_asal;
        $data['nama_dokter'] = $data['value']->nama_pegawai;

        $data['no_mr'] = $_GET['no_mr'];
        $data['id'] = $id;
        $data['no_kunjungan'] = $no_kunjungan;
        $data['jenis_form'] = 'form_25';
        $data['riwayat'] = $this->Pl_pelayanan->get_riwayat_pasien_by_id($no_kunjungan);
        $template = $this->load->view('Pl_pelayanan/clinical_pathway/form_25', $data, true);
        $data['template'] = $template;
        
        /*load form view*/
        // echo '<pre>';print_r($data['value']);die;
        $this->load->view('Pl_input_vital_sign/form_assesmen_rj', $data);

    }

    public function get_data()
    {
        /*akan di filter berdasarkan pasien pada klinik masing2*/
        /*get data from model*/
        $list = $this->Pl_input_vital_sign->get_datatables();
        $form_type = isset($_GET['form'])?$_GET['form']:'form_rj';
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '';
            $row[] = '';
            $row[] = $row_list->no_kunjungan;
            $row[] = $row_list->no_registrasi;
            $row[] = '<div class="center">'.$no.'</div>';
            $row[] = '<div class="center">'.$row_list->no_mr.'</div>';
            $row[] = strtoupper($row_list->nama_pasien);
            $row[] = ($row_list->nama_perusahaan)?$row_list->nama_perusahaan:$row_list->nama_kelompok;
            $row[] = ucwords($row_list->nama_bagian);
            $row[] = '<div class="center">'.$this->tanggal->formatDateTimeFormDmy($row_list->tgl_jam_poli).'</div>';
            $row[] = '<div class="center"><input type="text" style="width: 80px; text-align: center" class="form-control" onchange="save_vital_sign('."'tinggi_badan'".', '.$row_list->no_kunjungan.', '.$row_list->no_registrasi.')" value="'.$row_list->tinggi_badan.'" id="tinggi_badan_'.$row_list->no_kunjungan.'"></div>';
            $row[] = '<div class="center"><input type="text" style="width: 80px; text-align: center" class="form-control" onchange="save_vital_sign('."'berat_badan'".', '.$row_list->no_kunjungan.', '.$row_list->no_registrasi.')" value="'.$row_list->berat_badan.'" id="berat_badan_'.$row_list->no_kunjungan.'"></div>';
            $row[] = '<div class="center"><input type="text" style="width: 80px; text-align: center" class="form-control" onchange="save_vital_sign('."'tekanan_darah'".', '.$row_list->no_kunjungan.', '.$row_list->no_registrasi.')" value="'.$row_list->tekanan_darah.'" id="tekanan_darah_'.$row_list->no_kunjungan.'"></div>';
            $row[] = '<div class="center"><input type="text" style="width: 80px; text-align: center" class="form-control" onchange="save_vital_sign('."'nadi'".', '.$row_list->no_kunjungan.', '.$row_list->no_registrasi.')" value="'.$row_list->nadi.'" id="nadi_'.$row_list->no_kunjungan.'"></div>';
            $row[] = '<div class="center"><input type="text" style="width: 80px; text-align: center" class="form-control" onchange="save_vital_sign('."'suhu'".', '.$row_list->no_kunjungan.', '.$row_list->no_registrasi.')" value="'.$row_list->suhu.'" id="suhu_'.$row_list->no_kunjungan.'"></div>';
            $row[] = '<div class="center"><a href="#" class="label label-xs label-primary" onclick="getMenu('."'pelayanan/Pl_input_vital_sign/assesmen_rj/".$row_list->id_pl_tc_poli."/".$row_list->no_kunjungan."?type=Rajal&no_mr=".$row_list->no_mr."'".')">Assesmen</a></div>';

            if($row_list->status_batal==1){
                $status_periksa = '<label class="label label-danger"><i class="fa fa-times-circle"></i> Batal Berobat</label>';
            }else{
                if($row_list->tgl_keluar_poli==NULL || empty($row_list->tgl_keluar_poli)){
                    $status_periksa = '<label class="label label-warning"><i class="fa fa-info-circle"></i> Belum diperiksa</label>';
                }else {
                    $status_periksa = '<label class="label label-success"><i class="fa fa-check-circle"></i> Selesai</label>';
                }
            }

            // $row[] = '<div class="center">'.$status_periksa.'</div>';
           
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function process(){

        // echo '<pre>';print_r($_POST);die;
        // form validation
        $this->form_validation->set_rules('no_registrasi', 'No Registrasi', 'trim|required');
        $this->form_validation->set_rules('no_kunjungan', 'No Kunjungan', 'trim|required');
        $this->form_validation->set_rules('type', 'Type', 'trim|required');
        $this->form_validation->set_rules('value', 'Value', 'trim|required');

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

            // cek exist
            $riwayat = $this->db->get_where('th_riwayat_pasien', ['no_kunjungan' => $_POST['no_kunjungan'], 'no_registrasi' => $_POST['no_registrasi']])->row();

            $vital_sign = array(
                'no_registrasi' => $_POST['no_registrasi'],
                'no_kunjungan' => $_POST['no_kunjungan'],
                $_POST['type'] => $this->input->post('value'),
            );
            // echo '<pre>';print_r($riwayat_diagnosa);die;
            
            if( isset($riwayat->kode_riwayat) ){
                $this->db->where('kode_riwayat', $riwayat->kode_riwayat)->update('th_riwayat_pasien', $vital_sign);
            }else{
                $this->db->insert('th_riwayat_pasien', $vital_sign);
            }
                        
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
