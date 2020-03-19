<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Ok_acc_jadwal_bedah extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'kamar_bedah/Ok_acc_jadwal_bedah');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('Ok_acc_jadwal_bedah_model', 'Ok_acc_jadwal_bedah');
        $this->load->model('pelayanan/Pl_pelayanan_ri_model', 'Pl_pelayanan_ri');
        /*load library*/
        $this->load->library('Form_validation');
        $this->load->library('stok_barang');
        $this->load->library('tarif');
        $this->load->library('daftar_pasien');
        /*enable profiler*/
        $this->output->enable_profiler(false);
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'Title';

    }

    public function index() { 
        /*define variable data*/
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show(),
            'result' => $this->Ok_acc_jadwal_bedah->get_data(),
        );

        $this->load->view('Ok_acc_jadwal_bedah/index', $data);
    }

    public function form($id_pesan_bedah='', $no_kunjungan)
    {
         /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Ok_acc_jadwal_bedah/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id_pesan_bedah);
        /*get value by id*/
        $data['value'] = $this->Ok_acc_jadwal_bedah->get_by_id($id_pesan_bedah);

        $data['riwayat'] = $this->Pl_pelayanan_ri->get_riwayat_pasien_by_id($no_kunjungan);
        //echo '<pre>';print_r($data);die;
        $data['transaksi'] = $this->Pl_pelayanan_ri->get_transaksi_pasien_by_id($no_kunjungan);
        /*variable*/
        $data['no_mr'] = $data['value']->no_mr;
        $data['id'] = $id_pesan_bedah;
        $data['kode_klas'] = $data['value']->kode_klas;
        $data['no_kunjungan'] = $no_kunjungan;
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Ok_acc_jadwal_bedah/form', $data);
    }

    public function process_acc(){

        // form validation
        $this->form_validation->set_rules('noMrHidden', 'No MR', 'trim|required', array('required' => 'Pasien tidak ditemukan, silahkan Tampilkan Pasien !') );
        $this->form_validation->set_rules('tgl_jadwal_bedah', 'Tanggal', 'trim|required', array('required' => 'Tanggal Persetuajuan Bedah wajib diisi ') );
        $this->form_validation->set_rules('jam_bedah', 'Jam Bedah', 'trim|required');
        $this->form_validation->set_rules('kode_ruangan', 'No Kamar', 'trim|required');
        $this->form_validation->set_rules('no_kamar_bedah', 'No Kamar', 'trim|required');
        $this->form_validation->set_rules('jenis_layanan_pesan_ok', 'Jenis Layanan', 'trim|required');
        $this->form_validation->set_rules('pl_tindakan_pesan_ok', 'Nama Tindakan', 'trim|required');
        $this->form_validation->set_rules('pl_dokter_ok', 'Dokter', 'trim|required');
        $this->form_validation->set_rules('kode_tarif', 'Nama Tindakan', 'trim|required');
        $this->form_validation->set_rules('kode_master_tarif_detail', 'Tarif Detail', 'trim|required', array('required' => 'Tindakan yang dipilih tidak ada tarif nya, silahkan hubungi Bagian Administrasi') );
        $this->form_validation->set_rules('kode_klas', 'Kelas Pasien', 'trim|required', array('required' => 'Ambigous Klas') );
        
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
            //print_r($_POST);die;   
            /*execution*/
            $this->db->trans_begin();   

            /*ri_pesan_bedah*/
            $kunj_data = array(
                'flag_pesan' => 0, 
                'flag_jadwal' => 1, 
                'jenis_layanan' => $_POST['jenis_layanan_pesan_ok'],
                'kode_master_tarif_detail' => $_POST['kode_master_tarif_detail'],
                'dokter1' => $_POST['pl_dokter_ok'],
                'tgl_jadwal' => $_POST['tgl_jadwal_bedah'], 
                'kode_ruangan' => $_POST['kode_ruangan'], 
                'no_kamar' => $_POST['no_kamar_bedah'], 
                'jam_bedah' => $_POST['jam_bedah'], 
            );
            
            $this->db->update('ri_pesan_bedah', $kunj_data, array('id_pesan_bedah' => $_POST['id_pesan_bedah'], 'no_kunjungan' => $_POST['no_kunjungan'] ) );

            $this->logs->save('ri_pesan_bedah', $_POST['no_kunjungan'], 'update ri_pesan_bedah Modul Pelayanan', json_encode($kunj_data),'no_kunjungan');

            
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
