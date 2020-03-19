<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pl_pelayanan_bedah extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'pelayanan/Pl_pelayanan_bedah');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('Pl_pelayanan_bedah_model', 'Pl_pelayanan_bedah');
        $this->load->model('Pl_pelayanan_ri_model', 'Pl_pelayanan_ri');
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
            'breadcrumbs' => $this->breadcrumbs->show()
        );

        $this->load->view('Pl_pelayanan_bedah/index', $data);

    }

    public function form($id='', $no_kunjungan)
    {
         /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Pl_pelayanan_bedah/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*get value by id*/
        $data['value'] = $this->Pl_pelayanan_bedah->get_by_id($id);
        $data['val_ri'] = $this->Pl_pelayanan_ri->get_by_id($data['value']->kode_ri);
        $data['no_mr'] = $data['value']->no_mr;
        $data['id'] = $id;
        $data['kode_klas'] = $data['value']->kode_klas;
        $data['kode_profit'] = 2000;
        $data['no_kunjungan'] = $no_kunjungan;
        $data['kode_bagian'] = '030901';
        // echo '<pre>';print_r($data);die;
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Pl_pelayanan_bedah/form', $data);
    }

    public function tindakan($id='', $no_kunjungan='')
    {
         /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Pl_pelayanan_bedah/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*get value by id*/
        $data['value'] = $this->Pl_pelayanan_bedah->get_by_id($id);
        /*mr*/
        $data['no_mr'] = $data['value']->no_mr;
        $data['no_kunjungan'] = $no_kunjungan;
        $data['id_pesan_bedah'] = $id;
        $data['sess_kode_bag'] = '030901';
        $data['type']='Ranap';
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        // echo '<pre>'; print_r($data);die;
        $this->load->view('Pl_pelayanan_bedah/form_tindakan_bedah', $data);
    }

    public function obat_alkes($id='', $no_kunjungan='')
    {
        /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Pl_pelayanan_bedah/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*get value by id*/
        $data['value'] = $this->Pl_pelayanan_bedah->get_by_id($id);
        /*mr*/
        $data['no_mr'] = $data['value']->no_mr;
        $data['no_kunjungan'] = $no_kunjungan;
        $data['id_pesan_bedah'] = $id;
        $data['sess_kode_bag'] = '030901';
        $data['type']='Ranap';
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Pl_pelayanan_bedah/form_obat_alkes', $data);
    }

    public function riwayat_medis($no_kunjungan='', $id='')
    {
        /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Pl_pelayanan_bedah/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*get value by id*/
        $data['riwayat'] = $this->Pl_pelayanan_ri->get_riwayat_pasien_by_id($no_kunjungan);
        $data['value'] = $this->Pl_pelayanan_bedah->get_by_id($id);
        //echo '<pre>';print_r($data);die;
        /*mr*/
        $data['no_mr'] = $data['value']->no_mr;
        $data['no_kunjungan'] = $no_kunjungan;
        $data['id_pesan_bedah'] = $id;
        $data['sess_kode_bag'] = '030901';
        $data['type']='Ranap';
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Pl_pelayanan_bedah/form_riwayat_medis', $data);
    }

    public function form_kelahiran($no_kunjungan='', $id='')
    {
        /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Pl_pelayanan_bedah/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*get value by id*/
        $data['riwayat'] = $this->Pl_pelayanan_ri->get_riwayat_pasien_by_id($no_kunjungan);
        $data['value'] = $this->Pl_pelayanan_bedah->get_by_id($id);
        //echo '<pre>';print_r($data);die;
        /*mr*/
        $data['no_mr'] = $data['value']->no_mr;
        $data['no_kunjungan'] = $no_kunjungan;
        $data['id_pesan_bedah'] = $id;
        $data['sess_kode_bag'] = '030901';
        $data['type']='Ranap';
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Pl_pelayanan_bedah/form_kelahiran', $data);
    }

    public function form_end_visit()
    {
        $no_kunjungan = isset($_GET['no_kunjungan'])?$_GET['no_kunjungan']:'';
        $data = array(
            'no_mr' => isset($_GET['no_mr'])?$_GET['no_mr']:'',
            'id' => isset($_GET['id'])?$_GET['id']:'',
            'no_kunjungan' => $no_kunjungan,
            );

        /*load form view*/
        $this->load->view('Pl_pelayanan_bedah/form_end_visit', $data);
    }

    public function get_data()
    {
        /*akan di filter berdasarkan pasien pada klinik masing2*/
        /*get data from model*/
        $list = $this->Pl_pelayanan_bedah->get_datatables();
        //echo '<pre>'; print_r($list);die;
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center">
                        <label class="pos-rel">
                            <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->no_kunjungan.'"/>
                            <span class="lbl"></span>
                        </label>
                    </div>';


            $cancel_btn = ($row_list->tgl_jadwal==NULL) ? '<li><a href="#" onclick="cancel_visit('.$row_list->no_registrasi.','.$row_list->no_kunjungan.')">Batalkan Kunjungan</a></li>' : '' ;

            $row[] = '<div class="center"><div class="btn-group">
                        <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">
                            <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-inverse">
                            <li><a href="#" onclick="getMenu('."'kamar_bedah/Ok_acc_jadwal_bedah/form/".$row_list->id_pesan_bedah."/".$row_list->no_kunjungan."?act=edit'".')">Ubah Jadwal Operasi</a></li>
                            '.$cancel_btn.'                            
                            <li><a href="#">Selengkapnya</a></li>
                        </ul>
                    </div></div>';

            $row[] = '<div class="center"><a href="#" onclick="getMenu('."'pelayanan/Pl_pelayanan_bedah/form/".$row_list->id_pesan_bedah."/".$row_list->no_kunjungan."'".')">'.$row_list->no_kunjungan.'</a></div>';
            $row[] = $row_list->no_mr.' - '.strtoupper($row_list->nama_pasien).' ('.$row_list->jen_kelamin.')';
            $row[] = $row_list->nama_kelompok.' '.$row_list->nama_perusahaan;
            $row[] = $this->tanggal->formatDate($row_list->tgl_jadwal).' '.$row_list->jam_bedah;
            $row[] = $row_list->nama_pegawai;
            $row[] = $row_list->nama_tarif;
            $row[] = '<div class="center">'.$row_list->no_kamar.'</div>';
            $row[] = '<div align="right">Rp. '.number_format($row_list->total).',-</div>';

            if($row_list->tgl_keluar==NULL){
                $status_periksa = '<label class="label label-warning"><i class="fa fa-info-circle"></i> Belum dilayani</label>';
            }else{
                $status_periksa = '<label class="label label-success"><i class="fa fa-check-circle"></i> Selesai</label>';
            }

            $row[] = '<div class="center">'.$status_periksa.'</div>';
           
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Pl_pelayanan_bedah->count_all(),
                        "recordsFiltered" => $this->Pl_pelayanan_bedah->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function processPelayananSelesai(){

        //print_r($_POST);die;
        // form validation
        $this->form_validation->set_rules('noMrHidden', 'Pasien', 'trim|required');   
        $this->form_validation->set_rules('pasca_bedah', 'Pasca Bedah', 'trim');       
        $this->form_validation->set_rules('tindakan_pasca_bedah', 'Tindakan Pasca Bedah', 'trim');       
        $this->form_validation->set_rules('tgl_keluar', 'Tanggal Keluar', 'trim|required');       

        // set message error
        $this->form_validation->set_message('required', "Silahkan isi field \"%s\"");        

        if ($this->form_validation->run() == FALSE)
        {
            $this->form_validation->set_error_delimiters('<div style="color:white"><i>', '</i></div>');
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            /*execution*/
            $this->db->trans_begin();           

            $updatePesan["flag_pesan"] = 1;
            $updatePesan["tind_pasca_bedah"] = $this->form_validation->set_value('tindakan_pasca_bedah');
            $updatePesan["tgl_keluar"] = $this->form_validation->set_value('tgl_keluar');
            $this->db->update('ri_pesan_bedah', $updatePesan, array('kode_ri' => $_POST['kode_ri']) );

            /*save logs tc_kunjungan*/
            $this->logs->save('ri_pesan_bedah', $_POST['kode_ri'], 'update ri_pesan_bedah modul pelayanan', json_encode($_POST['kode_ri']),'kode_ri');
            
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'type_pelayanan' => 'Pasien Selesai'));
            }

        
        }

    }

    public function rollback()
    {   
        $this->db->trans_begin();  

        /*update tc_registrasi*/
        $pesan_bedah = array('flag_jadwal' => 1, 'flag_pesan' => 0, 'tgl_keluar' => NULL );
        $this->db->update('ri_pesan_bedah', $pesan_bedah, array('id_pesan_bedah' => $_POST['ID'] ) );
        $this->logs->save('ri_pesan_bedah', $_POST['ID'], 'update ri_pesan_bedah Modul Pelayanan', json_encode($pesan_bedah),'id_pesan_bedah');


        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
        }
        else
        {
            $this->db->trans_commit();
            echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan' ) );
        }
        
    }

    public function find_data()
    {   
        $output = array( "data" => http_build_query($_POST) . "\n" );
        echo json_encode($output);
    }

}

/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
