<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pl_pelayanan_igd extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'pelayanan/Pl_pelayanan_igd');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('Pl_pelayanan_igd_model', 'Pl_pelayanan_igd');
        $this->load->model('Pl_pelayanan_model', 'Pl_pelayanan');
        $this->load->model('registration/Reg_pasien_model', 'Reg_pasien');
        /*load library*/
        $this->load->library('Form_validation');
        $this->load->library('stok_barang');
        $this->load->library('tarif');
        $this->load->library('daftar_pasien');
        /*enable profiler*/
        $this->output->enable_profiler(false);
        // load other module
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

        $this->load->view('Pl_pelayanan_igd/index', $data);
    }

    public function form($id, $no_kunjungan)
    {
         /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Pl_pelayanan_igd/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*get value by id*/
        $data['value'] = $this->Pl_pelayanan_igd->get_by_id($id);
        //print_r($this->db->last_query());
        //echo '<pre>';print_r($id);die;
        $data['riwayat'] = $this->Pl_pelayanan_igd->get_riwayat_pasien_by_id($no_kunjungan);
        $kunjungan = $this->Reg_pasien->get_detail_kunjungan_by_no_kunjungan($no_kunjungan);
        if($kunjungan->status_keluar==4){
            $data['meninggal'] = $this->Pl_pelayanan_igd->get_meninggal($no_kunjungan,$data['value']->no_registrasi);
        }
        $data['keracunan'] = $this->Pl_pelayanan_igd->get_keracunan($no_kunjungan);
        //$data['transaksi'] = $this->Pl_pelayanan_igd->get_transaksi_pasien_by_id($no_kunjungan);
        /*variable*/
         /*type*/
        $kode_klas = 16;

        $data['no_mr'] = $data['value']->no_mr;
        $data['id'] = $id;
        $data['kode_klas'] = $kode_klas;
        $data['kode_profit'] = 2000;
        $data['no_kunjungan'] = $no_kunjungan;
        // echo '<pre>';print_r($data);die;
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Pl_pelayanan_igd/form', $data);
    }

    public function tindakan($id='', $no_kunjungan='')
    {
         /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Pl_pelayanan_igd/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*get value by id*/
        $data['value'] = $this->Pl_pelayanan_igd->get_by_id($id); //echo '<pre>'; print_r($this->db->last_query());die;
        /*mr*/
        /*type*/
        $kode_klas = 16;

        $data['type'] = $_GET['type'];
        if(isset($_GET['cito'])) $data['cito'] = $_GET['cito'];
        $data['no_mr'] = $data['value']->no_mr;
        $data['no_kunjungan'] = $no_kunjungan;
        $data['kode_gd'] = $id;
        $data['status_pulang'] = ($data['value']->status_keluar > 0)?1:0;
        $data['kode_klas'] = $kode_klas;
        $data['sess_kode_bag'] = ($_GET['kode_bag'])?$_GET['kode_bag']:$this->session->userdata('kode_bagian');
        //echo '<pre>'; print_r($data);die;
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Pl_pelayanan/form_tindakan', $data);
    }

    public function diagnosa($id='', $no_kunjungan='')
    {
         /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Pl_pelayanan_igd/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*get value by id*/
        $data['value'] = $this->Pl_pelayanan_igd->get_by_id($id); 
        $data['riwayat'] = $this->Pl_pelayanan->get_riwayat_pasien_by_id($no_kunjungan);
        /*mr*/
        /*type*/
        $kode_klas = 16;

        $data['type'] = $_GET['type'];
        if(isset($_GET['cito'])) $data['cito'] = $_GET['cito'];
        $data['no_mr'] = $data['value']->no_mr;
        $data['no_kunjungan'] = $no_kunjungan;
        $data['kode_gd'] = $id;
        $data['status_pulang'] = ($data['value']->status_keluar > 0)?1:0;
        $data['kode_klas'] = $kode_klas;
        $data['sess_kode_bag'] = ($_GET['kode_bag'])?$_GET['kode_bag']:$this->session->userdata('kode_bagian');
        // get form pengkajian dokter
        $query = $this->db->get_where('view_cppt', array('no_kunjungan' => $no_kunjungan, 'jenis_form' => 28))->row();
        if(isset($query->value_form)){
            $convert_to_array = explode('|', $query->value_form);
            for($i=0; $i < count($convert_to_array ); $i++){
                $key_value = explode('=', $convert_to_array [$i]);
                $end_array[trim($key_value[0])] = isset($key_value [1])?$key_value [1]:'';
            }
        }

        // get form pengkajian keperawatan
        $query = $this->db->get_where('view_cppt', array('no_kunjungan' => $no_kunjungan, 'jenis_form' => 27))->row();
        if(isset($query->value_form)){
            $convert_to_array = explode('|', $query->value_form);
            for($i=0; $i < count($convert_to_array ); $i++){
                $key_value = explode('=', $convert_to_array [$i]);
                $end_array_triase[trim($key_value[0])] = isset($key_value [1])?$key_value [1]:'';
            }
        }
        
        
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        $data['pengkajian_keperawatan'] = isset($end_array)?$end_array:[];
        $data['pengkajian_keperawatan_triase'] = isset($end_array_triase)?$end_array_triase:[];
        // echo "<pre>";print_r($data);die;
        /*load form view*/
        $this->load->view('Pl_pelayanan_igd/form_diagnosa', $data);
    }

    public function laporan_catatan($no_kunjungan='', $id='')
    {
         /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Pl_pelayanan_igd/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*get value by id*/
        $data['value'] = $this->Pl_pelayanan_igd->get_by_id($id);
        /*mr*/
        $data['no_mr'] = $data['value']->no_mr;
        $data['no_registrasi'] = $data['value']->no_registrasi;
        $data['no_kunjungan'] = $no_kunjungan;
        $data['kode_gd'] = $id;
        $vital_sign = $this->Pl_pelayanan_igd->get_vital_sign($data['value']->no_registrasi);
        if(!empty($vital_sign))$data['vital_sign'] = $vital_sign[0];
        $data['laporan_dr'] = $this->Pl_pelayanan_igd->get_laporan_dr($no_kunjungan);
        $data['laporan_perawat'] = $this->Pl_pelayanan_igd->get_laporan_perawat($no_kunjungan);
        $data['keracunan'] = $this->Pl_pelayanan_igd->get_keracunan($no_kunjungan);
        //print_r($data['keracunan']);die;
        $data['type']='IGD';
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Pl_pelayanan_igd/form_laporan_catatan', $data);
    }

    public function cppt($id='', $no_kunjungan)
    {
        /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Pl_pelayanan/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*get value by id*/
        $data['value'] = $this->Pl_pelayanan_igd->get_by_id($id);
        $data['riwayat'] = $this->Pl_pelayanan->get_riwayat_pasien_by_id($no_kunjungan);
        $data['kode_bagian'] = $this->session->userdata('kode_bagian');
        $data['nama_bagian'] = $this->session->userdata('nama_bagian');
        $data['nama_dokter'] = $this->session->userdata('sess_nama_dokter');

        // echo '<pre>';print_r($data['riwayat']);die;
        //$data['transaksi'] = $this->Pl_pelayanan->get_transaksi_pasien_by_id($no_kunjungan);
        /*variable*/
        /*type*/
        $kode_klas = 16;

        $data['no_mr'] = $data['value']->no_mr;
        $data['id'] = $id;
        $data['kode_klas'] = $kode_klas;
        $data['kode_profit'] = 2000;
        $data['no_kunjungan'] = $no_kunjungan;
        $data['form_type'] = $_GET['form'];
        
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        // echo '<pre>';print_r($data);die;
        $this->load->view('Pl_pelayanan/form_cppt', $data);

    }

    public function form_end_visit()
    {
        $data = array(
            'no_mr' => isset($_GET['no_mr'])?$_GET['no_mr']:'',
            'kode_gd' => isset($_GET['id'])?$_GET['id']:'',
            'no_kunjungan' => isset($_GET['no_kunjungan'])?$_GET['no_kunjungan']:'',
            );
        /*load form view*/
        $this->load->view('Pl_pelayanan_igd/form_end_visit', $data);
    }


    public function get_data()
    {
        /*akan di filter berdasarkan pasien pada klinik masing2*/
        /*get data from model*/
        $list = $this->Pl_pelayanan_igd->get_datatables();
        //print_r($this->db->last_query());die;
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center">'.$no.'</div>';
            /*fungsi rollback pasien, jika belum disubmit kasir maka poli masih bisa melakukan rollback*/
            $rollback_btn = '<li><a href="#" onclick="rollback('.$row_list->no_registrasi.','.$row_list->no_kunjungan.')">Rollback</a></li>';

            // $rollback_btn = '<li><a href="#" onclick="rollback('.$row_list->no_registrasi.','.$row_list->no_kunjungan.')">Rollback</a></li>';
            if($row_list->tgl_jam_kel!=NULL){
                $kunjungan = $this->Reg_pasien->get_detail_kunjungan_by_no_kunjungan($row_list->no_kunjungan);
                $bday = new Datetime($kunjungan->tgl_lhr);
                $today = new Datetime(date('Y-m-d H:i:s'));
                $diff = $today->diff($bday);
                $cetak_kematian_btn = ($kunjungan->status_keluar==4)?'<li><a href="#" onclick="cetak_surat_kematian('.$row_list->no_registrasi.','.$row_list->no_kunjungan.','.$diff->y.')">Cetak Surat Kematian</a></li>':'';
            }else{
                $cetak_kematian_btn = '';
            }
            $keracunan = $this->Pl_pelayanan_igd->get_keracunan($row_list->no_kunjungan);
            $cetak_keracunan_btn = !empty($keracunan)?'<li><a href="#" onclick="cetak_surat_keracunan('.$row_list->no_kunjungan.','.$row_list->no_mr.')">Cetak Surat Keracunan</a></li>':'';
                        
            $row[] = '<div class="center"><div class="btn-group">
                        <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">
                            <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-inverse">
                            '.$rollback_btn.'    
                            '.$cetak_kematian_btn.'     
                            '.$cetak_keracunan_btn.'                     
                            <li><a href="#" onclick="show_modal('."'registration/reg_pasien/view_detail_resume_medis/".$row_list->no_registrasi."'".', '."'RESUME MEDIS'".')">Selengkapnya</a></li>
                        </ul>
                    </div></div>';

            $row[] = '<div class="center"><a href="#" onclick="getMenu('."'pelayanan/Pl_pelayanan_igd/form/".$row_list->kode_gd."/".$row_list->no_kunjungan."'".')" style="color: blue"><b>'.$row_list->no_mr.'</b></a></div>';
            $row[] = strtoupper($row_list->nama_pasien_igd);
            $row[] = ($row_list->nama_perusahaan)?$row_list->nama_perusahaan:$row_list->nama_kelompok;
            $row[] = $this->tanggal->formatDateTimeFormDmy($row_list->tanggal_gd);
            $row[] = $this->tanggal->formatDateTimeFormDmy($row_list->tgl_jam_kel);
            $row[] = $row_list->nama_pegawai;
            $row[] = '<div class="left">'.ucwords($row_list->fullname).'</div>';


           
            if($row_list->tgl_jam_kel==NULL || empty($row_list->tgl_jam_kel)){
                $status_periksa = '<label class="label label-warning"><i class="fa fa-info-circle"></i> Belum diperiksa</label>';
            }else {
                /*cek rujuk */
                $cek_rujuk = $this->Pl_pelayanan_igd->cekRujuk($row_list->no_kunjungan);

                if(isset($cek_rujuk) AND $cek_rujuk->status==1){
                    $tujuan = substr($cek_rujuk->rujukan_tujuan, 1, 1);
                    if($tujuan == '3'){
                        $status_periksa = '<label class="label label-info"><i class="fa fa-arrow-circle-right"></i> Rujuk Rawat Jalan</label>';
                    }else{
                        $status_periksa = ($tujuan=='1')?'<label class="label label-purple"><i class="fa fa-arrow-circle-right"></i> Rujuk Rawat Inap</label>':'<label class="label label-blue"><i class="fa fa-arrow-circle-right"></i> Rujuk</label>';
                    }

                }else{
                    if($row_list->status_batal == 1){
                        $status_periksa = '<label class="label label-danger"><i class="fa fa-times"></i> Batal Kunjungan</label>';
                    }else{
                        $status_periksa = '<label class="label label-success"><i class="fa fa-check-circle"></i> Selesai</label>';
                    }
                }
                
            }
            

            $row[] = '<div class="center">'.$status_periksa.'</div>';
           
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Pl_pelayanan_igd->count_all(),
                        "recordsFiltered" => $this->Pl_pelayanan_igd->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function get_vital_sign()
    {
        /*get data from model*/
        $list = $this->Pl_pelayanan_igd->get_vital_sign($_GET['no_registrasi']);
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            
            $row[] = '<div class="center"><a href="#" class="btn btn-xs btn-success" onclick="edit_vital_sign('.$row_list->kode_rujuk_ri.')"><i class="fa fa-edit"></i></a></div>';
            $row[] = $row_list->kode_rujuk_ri;
            $row[] = $row_list->keadaan_umum;
            $row[] = $row_list->kesadaran_pasien;
            $row[] = $row_list->tekanan_darah;
            $row[] = $row_list->nadi;
            $row[] = $row_list->suhu;
            $row[] = $row_list->pernafasan;
            $row[] = $row_list->berat_badan;
           
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => count($list),
                        "recordsFiltered" => count($list),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function find_data()
    {   
        $output = array( "data" => http_build_query($_POST) . "\n" );
        echo json_encode($output);
    }

    public function process_add_vital_sign(){

        // form validation
        $this->form_validation->set_rules('noMrHidden', 'No MR', 'trim|required');
        $this->form_validation->set_rules('vs_keadaan_umum_igd', 'Keadaan Umum', 'trim|required');
        $this->form_validation->set_rules('vs_kesadaran_pasien', 'Kesadaran Pasien', 'trim|required');
        

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

            $dataexc = array(
                'no_mr' => $this->regex->_genRegex($this->input->post('noMrHidden'),'RGXQSL'),
                'no_registrasi' => $this->regex->_genRegex($this->input->post('no_registrasi'),'RGXINT'),
                'keadaan_umum' => $this->regex->_genRegex($this->input->post('vs_keadaan_umum_igd'),'RGXQSL'),           
                'kesadaran_pasien' => $this->regex->_genRegex($this->input->post('vs_kesadaran_pasien'),'RGXQSL'),
                'tekanan_darah' => $this->regex->_genRegex($this->input->post('vs_tekanan_darah'),'RGXINT'),
                'nadi' => $this->regex->_genRegex($this->input->post('vs_nadi'),'RGXINT'),
                'suhu' => $this->regex->_genRegex($this->input->post('vs_suhu'),'RGXINT'),
                'pernafasan' => $this->regex->_genRegex($this->input->post('vs_pernafasan'),'RGXINT'),
                'berat_badan' => $this->regex->_genRegex($this->input->post('vs_berat_badan'),'RGXINT'),
                'dokter_igd' => $this->regex->_genRegex($this->input->post('kode_dokter_igd'),'RGXINT'),
                'tgl_input' => date('Y-m-d H:i:s'),
            );

            //print_r($dataexc);die;

            if(isset($_POST['kode_rujuk_ri']) AND $_POST['kode_rujuk_ri']!=''){
                $this->Pl_pelayanan_igd->update('gd_th_rujuk_ri', $dataexc, array('kode_rujuk_ri' => $_POST['kode_rujuk_ri'] ) );
                $id = $_POST['kode_rujuk_ri'];
            }else{
                $id = $this->Pl_pelayanan_igd->save('gd_th_rujuk_ri', $dataexc);
            }

            //print_r($this->db->last_query());die;
            
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'id' => $id));
            }
        
        }

    }

    public function process_add_laporan_dokter(){

        // form validation
        $this->form_validation->set_rules('noMrHidden', 'No MR', 'trim|required');
        $this->form_validation->set_rules('laporan_dokter_keadaan_umum', 'Keadaan Umum', 'trim|required');
        $this->form_validation->set_rules('laporan_dokter_kesadaran', 'Kesadaran', 'trim|required');
        

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
 
            $dataexc = array(
                'no_mr' => $this->regex->_genRegex($this->input->post('noMrHidden'),'RGXQSL'),
                'no_registrasi' => $this->regex->_genRegex($this->input->post('no_registrasi'),'RGXINT'),
                'no_kunjungan' => $this->regex->_genRegex($this->input->post('no_kunjungan'),'RGXINT'),           
                'keadaan_umum' => $this->regex->_genRegex($this->input->post('laporan_dokter_keadaan_umum'),'RGXQSL'),
                'kesadaran' => $this->regex->_genRegex($this->input->post('laporan_dokter_kesadaran'),'RGXQSL'),
            );

            //print_r($dataexc);die;

            if(isset($_POST['id_th_laporan_dr']) AND $_POST['id_th_laporan_dr']!=''){
                $this->Pl_pelayanan_igd->update('th_laporan_dr', $dataexc, array('id_th_laporan_dr' => $_POST['id_th_laporan_dr'] ) );
                $id = $_POST['id_th_laporan_dr'];
            }else{
                $id = $this->Pl_pelayanan_igd->save('th_laporan_dr', $dataexc);
            }

            //print_r($this->db->last_query());die;
            
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'id' => $id));
            }
        
        }

    }

    public function process_add_laporan_perawat(){

        // form validation
        $this->form_validation->set_rules('noMrHidden', 'No MR', 'trim|required');
        $this->form_validation->set_rules('laporan_perawat', 'Laporan Perawat', 'trim|required');
                

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
 
            $dataexc = array(
                'no_mr' => $this->regex->_genRegex($this->input->post('noMrHidden'),'RGXQSL'),
                'no_registrasi' => $this->regex->_genRegex($this->input->post('no_registrasi'),'RGXINT'),
                'no_kunjungan' => $this->regex->_genRegex($this->input->post('no_kunjungan'),'RGXINT'),           
                'laporan_perawat' => $this->regex->_genRegex($this->input->post('laporan_perawat'),'RGXQSL'),
            );

            //print_r($dataexc);die;

            if(isset($_POST['id_th_laporan_perawat']) AND $_POST['id_th_laporan_perawat']!=''){
                $this->Pl_pelayanan_igd->update('th_laporan_perawat', $dataexc, array('id_th_laporan_perawat' => $_POST['id_th_laporan_perawat'] ) );
                $id = $_POST['id_th_laporan_perawat'];
            }else{
                $id = $this->Pl_pelayanan_igd->save('th_laporan_perawat', $dataexc);
            }
            
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'id' => $id));
            }
        
        }

    }

    public function process_add_keracunan(){

        // form validation
        $this->form_validation->set_rules('noMrHidden', 'No MR', 'trim|required');              

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
            
            $dataexc = array(
                'no_mr' => $this->regex->_genRegex($this->input->post('noMrHidden'),'RGXQSL'),					    
                'no_kunjungan' => $this->regex->_genRegex($this->input->post('no_kunjungan'),'RGXINT'),			
                'no_registrasi' => $this->regex->_genRegex($this->input->post('no_registrasi'),'RGXINT'),		
                'tempat_kejadian' => $this->regex->_genRegex($this->input->post('tempat_kejadian_keracunan'),'RGXQSL'),		
                'keluhan_utama' => $this->regex->_genRegex($this->input->post('keluhan_keracunan'),'RGXQSL'),			
                'rps' => $this->regex->_genRegex($this->input->post('rps_keracunan'),'RGXQSL'),								
                'ket_pas_menyusui' => $this->regex->_genRegex($this->input->post('menyusui_keracunan'),'RGXQSL'),		
                'hamil' => $this->regex->_genRegex($this->input->post('hamil_keracunan'),'RGXQSL'),					
                'tgl_keracunan' => $this->tanggal->sqlDateForm($this->regex->_genRegex($this->input->post('tgl_laporan'),'RGXQSL')),			
                'keluhan' => $this->regex->_genRegex($this->input->post('keluhan_keracunan'),'RGXQSL'),				
                'bahan_napza' => $this->regex->_genRegex($this->input->post('napza_bahan_keracunan'),'RGXQSL'),			
                'jumlah_napza' => $this->regex->_genRegex($this->input->post('napza_jml_bahan_keracunan'),'RGXQSL'),			
                'bahan_obat' => $this->regex->_genRegex($this->input->post('obat_bahan_keracunan'),'RGXQSL'),				
                'jumlah_obat' => $this->regex->_genRegex($this->input->post('obat_jml_bahan_keracunan'),'RGXQSL'),			
                'bahan_obattradisional' => $this->regex->_genRegex($this->input->post('obat_tradisional_bahan_keracunan'),'RGXQSL'),	
                'jumlah_obattradisional' => $this->regex->_genRegex($this->input->post('obat_tradisional_jml_bahan_keracunan'),'RGXQSL'),
                'bahan_makanan' => $this->regex->_genRegex($this->input->post('makanan_bahan_keracunan'),'RGXQSL'),			
                'jumlah_makanan' => $this->regex->_genRegex($this->input->post('makanan_jml_bahan_keracunan'),'RGXQSL'),			
                'bahan_suplemen' => $this->regex->_genRegex($this->input->post('suplemen_bahan_keracunan'),'RGXQSL'),			
                'jumlah_suplemen' => $this->regex->_genRegex($this->input->post('suplemen_jml_bahan_keracunan'),'RGXQSL'),		
                'bahan_kosmetik' => $this->regex->_genRegex($this->input->post('kosmetik_bahan_keracunan'),'RGXQSL'),			
                'jumlah_kosmetik' => $this->regex->_genRegex($this->input->post('kosmetik_jml_bahan_keracunan'),'RGXQSL'),		
                'bahan_kimia' => $this->regex->_genRegex($this->input->post('bahan_kimia_bahan_keracunan'),'RGXQSL'),			
                'jumlah_kimia' => $this->regex->_genRegex($this->input->post('bahan_kimia_jml_bahan_keracunan'),'RGXQSL'),			
                'bahan_pestisida' => $this->regex->_genRegex($this->input->post('pestisida_bahan_keracunan'),'RGXQSL'),		
                'jumlah_pestisida' => $this->regex->_genRegex($this->input->post('pestisida_jml_bahan_keracunan'),'RGXQSL'),		
                'bahan_ular' => $this->regex->_genRegex($this->input->post('gigitan_ular_bahan_keracunan'),'RGXQSL'),				
                'jumlah_ular' => $this->regex->_genRegex($this->input->post('gigitan_ular_jml_bahan_keracunan'),'RGXQSL'),			
                'bahan_bukanular' => $this->regex->_genRegex($this->input->post('binatang_bahan_keracunan'),'RGXQSL'),		
                'jumlah_bukanular' => $this->regex->_genRegex($this->input->post('binatang_jml_bahan_keracunan'),'RGXQSL'),		
                'bahan_tumbuhan' => $this->regex->_genRegex($this->input->post('tumbuhan_bahan_keracunan'),'RGXQSL'),		
                'jumlah_tumbuhan' => $this->regex->_genRegex($this->input->post('tumbuhan_jml_bahan_keracunan'),'RGXQSL'),		
                'bahan_pencemaran' => $this->regex->_genRegex($this->input->post('pencemaran_bahan_keracunan'),'RGXQSL'),		
                'jumlah_pencemaran' => $this->regex->_genRegex($this->input->post('pencemaran_jml_bahan_keracunan'),'RGXQSL'),		
                'bahan_tdkdiketahui' => $this->regex->_genRegex($this->input->post('tdk_diketahui_bahan_keracunan'),'RGXQSL'),		
                'jumlah_tdkdiketahui' => $this->regex->_genRegex($this->input->post('tdk_diketahui_jml_bahan_keracunan'),'RGXQSL'),
                'tipe_pemaparan' => $this->regex->_genRegex($this->input->post('tipe_pemaparan_keracunan'),'RGXQSL'),
                'tipe_kejadian'	=> $this->regex->_genRegex($this->input->post('tipe_kejadian_keracunan'),'RGXQSL'),
                'kesadaran' => $this->regex->_genRegex($this->input->post('kesadaran_pasien_keracunan'),'RGXQSL'),				
                'tekanan_darah' => $this->regex->_genRegex($this->input->post('tekanan_darah_keracunan'),'RGXQSL'),			
                'nadi' => $this->regex->_genRegex($this->input->post('nadi_keracunan'),'RGXQSL'),				
                'suhu' => $this->regex->_genRegex($this->input->post('suhu_keracunan'),'RGXQSL'),					
                'pernafasan' => $this->regex->_genRegex($this->input->post('pernafasan_keracunan'),'RGXQSL'),				
                'urine' => $this->regex->_genRegex($this->input->post('urine_keracunan'),'RGXQSL'),					
                'bau_bahan' => $this->regex->_genRegex($this->input->post('bau_bahan_keracunan'),'RGXQSL'),				
                'keterangan_bau_bahan' => $this->regex->_genRegex($this->input->post('nama_bau_bahan_keracunan'),'RGXQSL'),	
                'pupil' => $this->regex->_genRegex($this->input->post('kondisi_pupil_keracunan'),'RGXQSL'),					
                'kode_icd_x' => $this->regex->_genRegex($this->input->post('diagnosa_keracunan_hidden'),'RGXQSL'),				
                'pemeriksaan_penunjang' => $this->regex->_genRegex($this->input->post('pemeriksaan_penunjang_keracunan'),'RGXQSL'),	
                'penatalaksanaan' => $this->regex->_genRegex($this->input->post('penatalaksanaan_keracunan'),'RGXQSL'),		
                'tindak_lanjut' => $this->regex->_genRegex($this->input->post('tindak_lanjut_keracunan'),'RGXQSL'),			
                'umur_tahun' => $this->regex->_genRegex($this->input->post('umur_saat_pelayanan_hidden'),'RGXQSL'),										
                'pengobatan_sbl_igd' => $this->regex->_genRegex($this->input->post('sebelum_igd_keracunan'),'RGXQSL'),	
            );

            if(isset($_POST['id_cetak_racun']) AND $_POST['id_cetak_racun']!=''){
                $this->Pl_pelayanan_igd->update('gd_tc_cetak_racun', $dataexc, array('id_cetak_racun' => $_POST['id_cetak_racun'] ) );
                $id = $_POST['id_cetak_racun'];
            }else{
                $id = $this->Pl_pelayanan_igd->save('gd_tc_cetak_racun', $dataexc);
            }
            
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'id' => $id));
            }
        
        }

    }

    public function delete()
    {
        $id=$this->input->post('ID')?$this->input->post('ID',TRUE):null;
        if($id!=null){
            if($this->Pl_pelayanan_igd->delete_trans_pelayanan($id)){
                $this->logs->save('tc_trans_pelayanan', $id, 'delete record', '', 'kode_trans_pelayanan');
                echo json_encode(array('status' => 200, 'message' => 'Proses Hapus Data Berhasil Dilakukan'));
            }else{
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Hapus Data Gagal Dilakukan'));
            }
        }else{
            echo json_encode(array('status' => 301, 'message' => 'Tidak ada item yang dipilih'));
        }
        
    }

    public function processPelayananSelesai(){

        // print_r($_POST);die;
        // form validation
        $this->form_validation->set_rules('noMrHidden', 'Pasien', 'trim|required', array('required' => 'No MR Pasien Tidak ditemukan!') );        
        $this->form_validation->set_rules('pl_anamnesa', 'Anamnesa', 'trim');        
        $this->form_validation->set_rules('pl_diagnosa', 'Diagnosa', 'trim');        
        $this->form_validation->set_rules('pl_pemeriksaan', 'Pemeriksaan', 'trim');        
        $this->form_validation->set_rules('pl_pengobatan', 'Pengobatan', 'trim');        
        $this->form_validation->set_rules('no_registrasi', 'No Registrasi', 'trim|required');        
        $this->form_validation->set_rules('no_kunjungan', 'No Kunjungan', 'trim|required');        
        $this->form_validation->set_rules('kode_bagian_asal', 'Kode Bagian Asal', 'trim|required');        
        $this->form_validation->set_rules('cara_keluar', 'Cara Keluar Pasien', 'trim|required');        
        $this->form_validation->set_rules('pasca_pulang', 'Pasca Pulang', 'trim|required');        

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

            $no_kunjungan = $this->form_validation->set_value('no_kunjungan');
            $no_registrasi = $this->form_validation->set_value('no_registrasi');
            $kode_gd = $this->regex->_genRegex($this->input->post('kode_gd'),'RGXINT');

            /*cek transaksi minimal apakah sudah ada tindakan*/
            $cek_transaksi = $this->Pl_pelayanan_igd->cek_transaksi_minimal($no_kunjungan);

            /*jika sudah ada minimal 1 transaksi atau tindakan, maka lanjutkan proses*/
            if($cek_transaksi){

                /*cek pm selesai */
                $cek_pm = $this->Pl_pelayanan_igd->cek_pm_pulang($no_registrasi);

                /*if($cek_pm){
                    echo json_encode(array('status' => 301, 'message' => 'Maaf pasien masih dalam antrian Penunjang !', 'err' => 'antrian_pm'));
                    exit;
                }*/

                /*proses utama pasien selesai*/
                /*update gd_tc_gawat_darurat*/
                $arrGdTc = array('tgl_jam_kel' => date('Y-m-d H:i:s') );
                $this->Pl_pelayanan_igd->update('gd_tc_gawat_darurat', $arrGdTc, array('kode_gd' => $kode_gd ) );
                /*save logs gd_tc_gawat_darurat*/
                $this->logs->save('gd_tc_gawat_darurat', $kode_gd, 'update gd_tc_gawat_darurat Modul Pelayanan', json_encode($arrGdTc),'kode_gd');                               

                /*insert log diagnosa pasien th_riwayat pasien*/
                $riwayat_diagnosa = array(
                    'no_registrasi' => $this->form_validation->set_value('no_registrasi'),
                    'no_kunjungan' => $no_kunjungan,
                    'no_mr' => $this->form_validation->set_value('noMrHidden'),
                    'nama_pasien' => $this->input->post('nama_pasien_layan'),
                    'diagnosa_awal' => $this->form_validation->set_value('pl_diagnosa'),
                    'anamnesa' => $this->form_validation->set_value('pl_anamnesa'),
                    'pengobatan' => $this->form_validation->set_value('pl_pengobatan'),
                    'dokter_pemeriksa' => $this->input->post('dokter_pemeriksa'),
                    'pemeriksaan' => $this->form_validation->set_value('pl_pemeriksaan'),
                    'tgl_periksa' => date('Y-m-d H:i:s'),
                    'kode_bagian' => $this->form_validation->set_value('kode_bagian_asal'),
                    'diagnosa_akhir' => $this->form_validation->set_value('pl_diagnosa'),
                    'kategori_tindakan' => 3,
                    'kode_icd_diagnosa' => $this->input->post('pl_diagnosa_hidden'),
                );

                // if($this->input->post('kode_riwayat')==0){
                //     $this->Pl_pelayanan_igd->save('th_riwayat_pasien', $riwayat_diagnosa);
                // }else{
                //     $this->Pl_pelayanan_igd->update('th_riwayat_pasien', $riwayat_diagnosa, array('kode_riwayat' => $this->input->post('kode_riwayat') ) );
                // }

                $status = $this->input->post('cara_keluar');
                $txt_rujuk_poli = 'Rujuk ke Poli Lain';
                $txt_rujuk_ri = 'Rujuk ke Rawat Inap';

                $cek_rujuk = $this->Pl_pelayanan_igd->cekRujuk($_POST['no_kunjungan']);

                if( empty($cek_rujuk) ){
                    /*kondisi jika pasien dirujuk RI/RJ*/
                    if( in_array($status, array($txt_rujuk_ri,$txt_rujuk_poli) ) ){
                        $max_kode_rujukan = $this->master->get_max_number('rg_tc_rujukan', 'kode_rujukan');
                        $tujuan = ($status==$txt_rujuk_poli)?$this->input->post('rujukan_tujuan'):'030001';
                        $rujukan_data = array(
                            'kode_rujukan' => $max_kode_rujukan,
                            'rujukan_dari' => $this->form_validation->set_value('kode_bagian_asal'),
                            'no_mr' => $this->form_validation->set_value('noMrHidden'),
                            'no_kunjungan_lama' => $no_kunjungan,
                            'no_registrasi' => $this->form_validation->set_value('no_registrasi'),
                            'rujukan_tujuan' => $tujuan,
                            'status' => 0,
                            'tgl_input' => date('Y-m-d H:i:s'),
                        );
                        /*insert rg_tc_rujukan*/
                        $this->Pl_pelayanan_igd->save('rg_tc_rujukan', $rujukan_data );
                                        
                    }
                }

                /*kondisi jika pasien Meninggal*/
                if($status=='Meninggal'){
                    
                    $tgl = $this->tanggal->sqlDateForm($this->input->post('tgl_meninggal'));
                    $jam = $this->input->post('jam_meninggal');
                    $date = date_create($tgl.' '.$jam );
                    $jam_tgl_meninggal = date_format($date, 'Y-m-d H:i:s');

                    $gd_th_kematian = array(
                        'no_mr'	=> $this->form_validation->set_value('noMrHidden'),
                        'no_registrasi'	=> $no_registrasi,
                        'kode_bagian' => $this->input->post('kode_bagian_pasien_meninggal'),
                        'meninggal_hari' => $this->input->post('hari_meninggal'),
                        'tgl_keluar' => $jam_tgl_meninggal,
                        'no_kunjungan' => $no_kunjungan, 
                        'kode_gd' => $kode_gd,
                        'dokter_asal' => $this->input->post('dokter_pasien_meninggal'),
                        'meninggal_instruksi' => $this->input->post('instruksi_meninggal'),
                    );

                    /*insert gd_th_kematian*/
                    $kode_meninggal = $this->Pl_pelayanan_igd->save('gd_th_kematian', $gd_th_kematian );

                    /*update mt_master_pasien */
                    $this->Pl_pelayanan_igd->update('mt_master_pasien', array('status_meninggal' => 1), array('no_mr' => $this->form_validation->set_value('noMrHidden') ) );

                    $status_keluar = 4;
                    $type_pelayanan = 'pasien_meninggal';

                }else{
                    $status_keluar = 3;
                    $type_pelayanan = 'pasien_selesai';
                    $kode_meninggal = 0;
                }

                /*last func to finsih visit*/
                $this->daftar_pasien->pulangkan_pasien($no_kunjungan,$status_keluar);

            }else{
                echo json_encode(array('status' => 301, 'message' => 'Tidak ada data transaksi, Silahkan klik Batal Berobat jika tidak ada tindakan atau minimal konsultasi dokter'));
                exit;
            }

            
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'type_pelayanan' => $type_pelayanan, 'kode_meninggal' => $kode_meninggal));
            }

        
        }

    }

    public function generateSuratPermohonan($no_mr, $no_registrasi){
        // generate file surat permohonan
        $filename = 'Surat_Permohonan_RI-'.$no_mr.'-'.$no_registrasi.'';
        return $this->cbpModule->generateSingleDoc($filename);
    }

    public function processSaveDiagnosa(){

        print_r($_POST);die;
        // form validation
        $this->form_validation->set_rules('noMrHidden', 'Pasien', 'trim|required', array('required' => 'No MR Pasien Tidak ditemukan!') );        
        $this->form_validation->set_rules('pl_anamnesa', 'Anamnesa', 'trim');        
        $this->form_validation->set_rules('pl_diagnosa', 'Diagnosa', 'trim|required');        
        $this->form_validation->set_rules('pl_pemeriksaan', 'Pemeriksaan', 'trim');        
        $this->form_validation->set_rules('pl_pengobatan', 'Pengobatan', 'trim');        
        $this->form_validation->set_rules('no_registrasi', 'No Registrasi', 'trim|required');        
        $this->form_validation->set_rules('no_kunjungan', 'No Kunjungan', 'trim|required');        
        $this->form_validation->set_rules('kode_bagian_asal', 'Kode Bagian Asal', 'trim|required'); 

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

            $no_kunjungan = $this->form_validation->set_value('no_kunjungan');
            $no_registrasi = $this->form_validation->set_value('no_registrasi');
            $kode_gd = $this->regex->_genRegex($this->input->post('kode_gd'),'RGXINT');

            /*insert log diagnosa pasien th_riwayat pasien*/
            $riwayat_diagnosa = array(
                'no_registrasi' => $this->form_validation->set_value('no_registrasi'),
                'no_kunjungan' => $no_kunjungan,
                'no_mr' => $this->form_validation->set_value('noMrHidden'),
                'nama_pasien' => $this->input->post('nama_pasien_layan'),
                'diagnosa_awal' => $this->form_validation->set_value('pl_diagnosa'),
                'anamnesa' => $this->form_validation->set_value('pl_anamnesa'),
                'pengobatan' => $this->form_validation->set_value('pl_pengobatan'),
                'dokter_pemeriksa' => $this->input->post('dokter_pemeriksa'),
                'pemeriksaan' => $this->form_validation->set_value('pl_pemeriksaan'),
                'tgl_periksa' => date('Y-m-d H:i:s'),
                'kode_bagian' => $this->form_validation->set_value('kode_bagian_asal'),
                'diagnosa_akhir' => $this->form_validation->set_value('pl_diagnosa'),
                'kategori_tindakan' => $_POST['kategori_tindakan'],
                'kode_icd_diagnosa' => $this->input->post('pl_diagnosa_hidden'),
            );

            if($this->input->post('kode_riwayat')==0){
                $this->Pl_pelayanan_igd->save('th_riwayat_pasien', $riwayat_diagnosa);
            }else{
                $this->Pl_pelayanan_igd->update('th_riwayat_pasien', $riwayat_diagnosa, array('kode_riwayat' => $this->input->post('kode_riwayat') ) );
            }

            
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'type_pelayanan' => $type_pelayanan, 'kode_meninggal' => $kode_meninggal));
            }

        
        }

    }

    public function processSaveDiagnosaDr(){

        // print_r($_POST);die;
        // form validation
        $this->form_validation->set_rules('noMrHidden', 'Pasien', 'trim|required', array('required' => 'No MR Pasien Tidak ditemukan!') );

        $this->form_validation->set_rules('pl_anamnesa', 'Anamnesa', 'trim|required|min_length[8]');        
        $this->form_validation->set_rules('pl_diagnosa', 'Diagnosa Primer', 'trim|required');        
        $this->form_validation->set_rules('pl_diagnosa_hidden', 'Diagnosa', 'trim|required', array('required' => 'Silahkan pilih kembali Diagnosa Primer'));   
        $this->form_validation->set_rules('pl_pemeriksaan', 'Pemeriksaan', 'trim');        
        $this->form_validation->set_rules('pl_pengobatan', 'Pengobatan', 'trim');        
        $this->form_validation->set_rules('pl_procedure', 'Prosedur/Tindakan', 'trim');        
        $this->form_validation->set_rules('pl_alergi', 'Alergi Obat', 'trim');        
        $this->form_validation->set_rules('pl_diet', 'Diet', 'trim');        
        $this->form_validation->set_rules('pl_tgl_kontrol_kembali', 'Tanggal Kontrol', 'trim');        
        $this->form_validation->set_rules('pl_catatan_kontrol', 'Catatan Kontrol', 'trim');        
        $this->form_validation->set_rules('no_registrasi', 'No Registrasi', 'trim|required');        
        $this->form_validation->set_rules('no_kunjungan', 'No Kunjungan', 'trim|required');        
        $this->form_validation->set_rules('kode_bagian_asal', 'Kode Bagian Asal', 'trim|required');             
        $this->form_validation->set_rules('kode_dokter_poli', 'Dokter Poli', 'trim');  
        // form assesment
        $this->form_validation->set_rules('pl_tb_igd', 'Tinggi Badan', 'trim');        
        $this->form_validation->set_rules('pl_bb_igd', 'Berat Badan', 'trim');        
        $this->form_validation->set_rules('pl_td_igd', 'Tekanan Darah', 'trim');        
        $this->form_validation->set_rules('pl_suhu_igd', 'Suhu', 'trim');        
        $this->form_validation->set_rules('pl_nadi_igd', 'Nadi', 'trim');             

        // set message error
        $this->form_validation->set_message('required', "Silahkan isi field \"%s\"");        
        $this->form_validation->set_message('min_length', "\"%s\" Minimal 8 Karakter");        

        if ($this->form_validation->run() == FALSE)
        {
            $this->form_validation->set_error_delimiters('<div style="color:white"><i>', '</i></div>');
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            /*execution*/
            $this->db->trans_begin();           
            // print_r($_POST);die;
            $no_kunjungan = $this->form_validation->set_value('no_kunjungan');
            $no_registrasi = $this->form_validation->set_value('no_registrasi');

            // diagnosa sekunder
            $diagnosa_sekunder = strip_tags($_POST['konten_diagnosa_sekunder_igd']);

            /*insert log diagnosa pasien th_riwayat pasien*/
            $riwayat_diagnosa = array(
                'no_registrasi' => $this->form_validation->set_value('no_registrasi'),
                'no_kunjungan' => $no_kunjungan,
                'no_mr' => $this->form_validation->set_value('noMrHidden'),
                'nama_pasien' => $this->input->post('nama_pasien_layan'),
                'diagnosa_awal' => $this->master->br2nl($_POST['pl_diagnosa']),
                'anamnesa' => $this->master->br2nl($_POST['pl_anamnesa']),
                'pengobatan' => $this->master->br2nl($_POST['pl_pengobatan']),
                'alergi_obat' => $this->master->br2nl($_POST['pl_alergi']),
                'diet' => $this->master->br2nl($_POST['pl_diet']),
                'dokter_pemeriksa' => $this->input->post('dokter_pemeriksa'),
                'pemeriksaan' => $this->master->br2nl($_POST['pl_pemeriksaan']),
                'tgl_periksa' => $this->input->post('pl_tgl_transaksi').' '.date('H:i:s'),
                'kode_bagian' => $this->form_validation->set_value('kode_bagian_asal'),
                'diagnosa_akhir' => $this->form_validation->set_value('pl_diagnosa'),
                'diagnosa_sekunder' => $diagnosa_sekunder,
                'kategori_tindakan' => $_POST['kategori_tindakan'],
                'kode_icd_diagnosa' => $this->input->post('pl_diagnosa_hidden'),
                'tinggi_badan' => $this->input->post('pl_tb_igd'),
                'tekanan_darah' => $this->input->post('pl_td_igd'),
                'berat_badan' => $this->input->post('pl_bb_igd'),
                'suhu' => $this->input->post('pl_suhu_igd'),
                'nadi' => $this->input->post('pl_nadi_igd'),
                'saturasi' => $this->input->post('pl_saturasi_igd'),
                'kode_icd9' => $this->input->post('pl_procedure_hidden'),
                'text_icd9' => $this->input->post('pl_procedure'),
                'tgl_kontrol_kembali' => $this->input->post('pl_tgl_kontrol_kembali'),
                'catatan_kontrol_kembali' => $this->input->post('pl_catatan_kontrol'),
            );

            if($this->input->post('kode_riwayat')==0){
                $this->Pl_pelayanan->save('th_riwayat_pasien', $riwayat_diagnosa);
            }else{
                $this->Pl_pelayanan->update('th_riwayat_pasien', $riwayat_diagnosa, array('kode_riwayat' => $this->input->post('kode_riwayat') ) );
            }

            /*update gd_tc_gawat_darurat*/
            $arr_gd = array(
                'jenis_kasus'       => $_POST['jenis_kasus_igd'], 
                'no_induk'          => $this->session->userdata('user')->user_id, 
                'kode_penyakit'     => $this->input->post('pl_diagnosa_hidden'), 
                'pengobatan'        => $this->master->br2nl($_POST['pl_pengobatan']), 
                'diagnosa_masuk'    => $_POST['pl_diagnosa'], 
                'tek_darah'         => $_POST['pl_td'], 
            );

            $this->db->where('kode_gd', $_POST['kode_gd'])->update('gd_tc_gawat_darurat', $arr_gd );

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'type_pelayanan' => 'pasien_selesai', 'kode_meninggal' => $_POST['kode_meninggal']));
            }

        }

    }

    public function surat_kematian(){
        
        $kode_meninggal = isset($_GET['kode_meninggal'])?$_GET['kode_meninggal']:'';
        $no_kunjungan = $_GET['no_kunjungan'];
        $no_registrasi = $_GET['no_registrasi'];
        $umur = $_GET['umur'];
        $data['umur'] = $umur;
        $data['value'] = $this->Pl_pelayanan_igd->get_meninggal($no_kunjungan,$no_registrasi);

        $tgl_explode = explode(' ',$data['value']->tgl_keluar);
        $jam = date_create($tgl_explode[1]);
        $date = date_create($tgl_explode[0]);
        $jam_meninggal = date_format($jam, 'H:i:s');
        $date_meninggal = date_format($date, 'Y-m-d');

        $data['jam'] = $jam_meninggal;
        $data['tgl'] = $date_meninggal;

        $this->load->view('Pl_pelayanan_igd/surat_kematian', $data);

    }

    public function surat_keracunan(){
        
        $no_mr = $_GET['no_mr'];
        $no_kunjungan = $_GET['no_kunjungan'];
        
        $data['value'] = $this->Pl_pelayanan_igd->get_keracunan($no_kunjungan);
        $exp = explode(':',$data['value']->kode_icd_x);
        $data['diagnosa'] = isset($exp[1])?$exp[1]:'';
        $data['icd_x'] = isset($exp[0])?$exp[0]:'';

        $this->load->view('Pl_pelayanan_igd/surat_keracunan', $data);

    }
   
    public function rollback()
    {   
        $this->db->trans_begin();  

        $cek_rujuk = $this->Pl_pelayanan_igd->cekRujuk($_POST['no_kunjungan']);

        if(!isset($cek_rujuk) OR (isset($cek_rujuk) AND $cek_rujuk->status!=1)){
             /*update tc_registrasi*/
            $reg_data = array('tgl_jam_keluar' => NULL, 'kode_bagian_keluar' => NULL, 'status_batal' => NULL );
            $this->db->update('tc_registrasi', $reg_data, array('no_registrasi' => $_POST['no_registrasi'] ) );
            // $this->logs->save('tc_registrasi', $_POST['no_registrasi'], 'update tc_registrasi Modul Pelayanan', json_encode($reg_data),'no_registrasi');


            /*tc_kunjungan*/
            $kunj_data = array('tgl_keluar' => NULL, 'status_keluar' => NULL, 'status_batal' => NULL );
            $this->db->update('tc_kunjungan', $kunj_data, array('no_registrasi' => $_POST['no_registrasi'], 'no_kunjungan' => $_POST['no_kunjungan'] ) );
            // $this->logs->save('tc_kunjungan', $_POST['no_kunjungan'], 'update tc_kunjungan Modul Pelayanan', json_encode($kunj_data),'no_kunjungan');

            /*update gd_tc_gawat_darurat*/
            $arrGdTc = array('tgl_jam_kel' => NULL, 'status_batal' => NULL );
            $this->Pl_pelayanan_igd->update('gd_tc_gawat_darurat', $arrGdTc, array('no_kunjungan' => $_POST['no_kunjungan'] ) );
            /*save logs gd_tc_gawat_darurat*/
            // $this->logs->save('gd_tc_gawat_darurat', $_POST['no_kunjungan'], 'update gd_tc_gawat_darurat Modul Pelayanan', json_encode($arrGdTc),'no_kunjungan');

            /*tc_trans_pelayanan*/
            $trans_data = array('status_selesai' => 2, 'status_nk' => NULL, 'kode_tc_trans_kasir' => NULL );
            $this->db->update('tc_trans_pelayanan', $trans_data, array('no_kunjungan' => $_POST['no_kunjungan'], 'no_registrasi' => $_POST['no_registrasi'] ) );

            if( isset($cek_rujuk) AND $cek_rujuk->status!=1 ){
                $this->db->where('rg_tc_rujukan.no_kunjungan_lama', $_POST['no_kunjungan']);
                $this->db->delete('rg_tc_rujukan');
            }

        }else{
            // echo json_encode(array('status' => 301, 'message' => 'Maaf pasien sudah terdaftar di bagian lain'));
            // exit;

            /*tc_kunjungan*/
            $kunj_data = array('tgl_keluar' => NULL, 'status_keluar' => NULL, 'status_batal' => NULL );
            $this->db->update('tc_kunjungan', $kunj_data, array('no_registrasi' => $_POST['no_registrasi'], 'no_kunjungan' => $_POST['no_kunjungan'] ) );
            // $this->logs->save('tc_kunjungan', $_POST['no_kunjungan'], 'update tc_kunjungan Modul Pelayanan', json_encode($kunj_data),'no_kunjungan');

            /*update gd_tc_gawat_darurat*/
            $arrGdTc = array('tgl_jam_kel' => NULL );
            $this->Pl_pelayanan_igd->update('gd_tc_gawat_darurat', $arrGdTc, array('no_kunjungan' => $_POST['no_kunjungan'] ) );
            /*save logs gd_tc_gawat_darurat*/
            // $this->logs->save('gd_tc_gawat_darurat', $_POST['no_kunjungan'], 'update gd_tc_gawat_darurat Modul Pelayanan', json_encode($arrGdTc),'no_kunjungan');

            /*tc_trans_pelayanan*/
            $trans_data = array('status_selesai' => 2, 'status_nk' => NULL, 'kode_tc_trans_kasir' => NULL );
            $this->db->update('tc_trans_pelayanan', $trans_data, array('no_kunjungan' => $_POST['no_kunjungan'], 'no_registrasi' => $_POST['no_registrasi'] ) );
        }

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

    public function form_img_tagging($no_kunjungan='') { 
        /*define variable data*/
        $data = [];
        $img_tag = $this->db->get_where('th_img_tagging', ['no_kunjungan' => $no_kunjungan])->row();
        $data['img_tagging'] = $img_tag;
        // echo "<pre>"; print_r($data);die;
        $data['no_kunjungan'] = $no_kunjungan;
        $data['cppt_id'] = isset($img_tag->cppt_id)?$img_tag->cppt_id:'';

        $this->load->view('Pl_pelayanan_igd/form_img_tag_anatomi', $data);
    }

    public function get_list_pasien()
    {
        /*akan di filter berdasarkan pasien pada klinik masing2*/
        /*get data from model*/
        $list = $this->Pl_pelayanan_igd->get_list_data();
        $data = array();
        $no=0;
        foreach ($list as $row_list) {
            $no++;
            $row = array();

            if($row_list->tgl_jam_kel==NULL || empty($row_list->tgl_jam_kel)){
                $status_periksa = '<label class="label label-warning"><i class="fa fa-info-circle"></i> Belum diperiksa</label>';
                $color = 'black';
            }else {
                /*cek rujuk */
                $cek_rujuk = $this->Pl_pelayanan_igd->cekRujuk($row_list->no_kunjungan);

                if(isset($cek_rujuk) AND $cek_rujuk->status==1){
                    $tujuan = substr($cek_rujuk->rujukan_tujuan, 1, 1);
                    if($tujuan == '3'){
                        $status_periksa = '<label class="label label-info"><i class="fa fa-arrow-circle-right"></i> Rujuk Rawat Jalan</label>';
                    }else{
                        $status_periksa = ($tujuan=='1')?'<label class="label label-purple"><i class="fa fa-arrow-circle-right"></i> Rujuk Rawat Inap</label>':'<label class="label label-blue"><i class="fa fa-arrow-circle-right"></i> Rujuk</label>';
                    }
                    $color = 'blue';

                }else{
                    if($row_list->status_batal == 1){
                        $status_periksa = '<label class="label label-danger"><i class="fa fa-times"></i> Batal Kunjungan</label>';
                        $color = 'red';
                    }else{
                        $status_periksa = '<label class="label label-success"><i class="fa fa-check-circle"></i> Selesai</label>';
                        $color = 'green';
                    }
                }
                
            }
            
            $row[] = array('no_kunjungan' => $row_list->no_kunjungan, 'kode_gd' => $row_list->kode_gd, 'no_mr' => $row_list->no_mr, 'nama_pasien' => strtoupper($row_list->nama_pasien_igd), 'color_txt' => $color);
            $data[] = $row;
        }

        $output = array("data" => $data );
        //output to json format
        echo json_encode($output);
    }

    public function save_img_tagging(){
        // get 
        // echo "<pre>"; print_r($_POST); die;
        $dataexc = [];
        $dataexc['cppt_id'] = $_POST['cppt_id'];
        $dataexc['no_kunjungan'] = $_POST['no_kunjungan'];
        $dataexc['data_points'] = json_encode($_POST['data_points']);
        if($_POST['img_tag_id'] == ''){
            $this->db->insert('th_img_tagging', $dataexc);
            $newId = $this->db->insert_id();
        }else{
            $this->db->where('img_tag_id', $_POST['img_tag_id'])->update('th_img_tagging', $dataexc);
            $newId = $_POST['img_tag_id'];
        }
        
        echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'newId' => $newId));
    }


}

/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
