<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pl_pelayanan extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'pelayanan/Pl_pelayanan');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('Pl_pelayanan_model', 'Pl_pelayanan');
        /*load library*/
        $this->load->library('Form_validation');
        $this->load->library('stok_barang');
        $this->load->library('tarif');
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

        if( $this->session->userdata('kode_bagian') ){
            /*load view index*/
            $this->load->view('Pl_pelayanan/index', $data);
        }else{
            $this->load->view('Pl_pelayanan/index_no_session_yet', $data);
        }
    }

    public function form($id='', $no_kunjungan)
    {
         /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Pl_pelayanan/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*get value by id*/
        $data['value'] = $this->Pl_pelayanan->get_by_id($id);
        /*echo '<pre>';print_r($data['value']);die;*/
        $data['riwayat'] = $this->Pl_pelayanan->get_riwayat_pasien_by_id($no_kunjungan);
        //$data['transaksi'] = $this->Pl_pelayanan->get_transaksi_pasien_by_id($no_kunjungan);
        /*variable*/
        $data['no_mr'] = $data['value']->no_mr;
        $data['id'] = $id;
        $data['kode_klas'] = 16;
        $data['kode_profit'] = 2000;
        $data['no_kunjungan'] = $no_kunjungan;
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Pl_pelayanan/form', $data);
    }

    public function tindakan($id='', $no_kunjungan='')
    {
         /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Pl_pelayanan/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*get value by id*/
        $data['value'] = $this->Pl_pelayanan->get_by_id($id);
        /*mr*/
        $data['type'] = 'Rajal';
        $data['no_mr'] = $data['value']->no_mr;
        $data['no_kunjungan'] = $no_kunjungan;
        $data['id_pl_tc_poli'] = $id;
        $data['kode_klas'] = 16;
        $data['sess_kode_bagian'] = ($this->session->userdata('kode_bagian'))?$this->session->userdata('kode_bagian'):0;
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Pl_pelayanan/form_tindakan', $data);
    }

    public function form_end_visit()
    {
        $data = array(
            'no_mr' => isset($_GET['no_mr'])?$_GET['no_mr']:'',
            'id_pl_tc_poli' => isset($_GET['id'])?$_GET['id']:'',
            'no_kunjungan' => isset($_GET['no_kunjungan'])?$_GET['no_kunjungan']:'',
            );
        /*load form view*/
        $this->load->view('Pl_pelayanan/form_end_visit', $data);
    }


    public function get_data()
    {
        /*akan di filter berdasarkan pasien pada klinik masing2*/
        /*get data from model*/
        $list = $this->Pl_pelayanan->get_datatables();
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
            $row[] = '<div class="center"><div class="btn-group">
                        <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">
                            <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-inverse">
                            <li><a href="#">Batalkan Kunjungan</a></li>
                            <li><a href="#">Selengkapnya</a></li>
                        </ul>
                    </div></div>';
            $row[] = '<div class="center"><a href="#" onclick="getMenu('."'pelayanan/Pl_pelayanan/form/".$row_list->id_pl_tc_poli."/".$row_list->no_kunjungan."'".')">'.$row_list->no_kunjungan.'</a></div>';
            $row[] = '<div class="center">'.$row_list->no_mr.'</div>';
            $row[] = strtoupper($row_list->nama_pasien);
            $row[] = ($row_list->nama_perusahaan)?$row_list->nama_perusahaan:$row_list->nama_kelompok;
            $row[] = $this->tanggal->formatDateTime($row_list->tgl_jam_poli);
            $row[] = $row_list->nama_pegawai;
            $row[] = '<div class="center">'.$row_list->no_antrian.'</div>';
            $status_periksa = ($row_list->status_periksa==NULL)?'<label class="label label-danger">Belum diperiksa</label>':'<label class="label label-success">Selesai</label>';
            $row[] = '<div class="center">'.$status_periksa.'</div>';
           
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Pl_pelayanan->count_all(),
                        "recordsFiltered" => $this->Pl_pelayanan->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function get_data_tindakan()
    {
        /*get data from model*/
        $list = $this->Pl_pelayanan->get_datatables_tindakan();
        //print_r($this->db->last_query());die;
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            if($row_list->kode_tc_trans_kasir==NULL){
                $row[] = '<div class="center"><a href="#" class="btn btn-xs btn-danger" onclick="delete_transaksi('.$row_list->kode_trans_pelayanan.')"><i class="fa fa-times-circle"></i></a></div>';
            }else{
                $row[] = '<div class="center"><i class="fa fa-check-circle green"></i></div>';
            }

            $row[] = $row_list->kode_trans_pelayanan;
            $row[] = strtoupper($row_list->nama_tindakan);

            $html_dr = '';
            if($row_list->bill_dr1 > 0){
                $html_dr .= '1. '.$row_list->nama_pegawai.'<br><span class="pull-right">'.number_format($row_list->bill_dr1).',-</span><br>';
            }

            if($row_list->bill_dr2 > 0){
                $html_dr .= '2. '.$row_list->dokter_2.'<br><span class="pull-right">'.number_format($row_list->bill_dr2).',-</span><br>';
            }

            if($row_list->bill_dr3 > 0){
                $html_dr .= '3. '.$row_list->dokter_3.'<br><span class="pull-right">'.number_format($row_list->bill_dr3).',-</span><br>';
            }
            $row[] = '<div align="left">'.$html_dr.'</div>';
            $row[] = '<div align="right">'.number_format($row_list->bhp).',-</div>';
            $row[] = '<div align="right">'.number_format($row_list->alat_rs).',-</div>';
            $row[] = '<div align="right">'.number_format($row_list->pendapatan_rs).',-</div>';

            $bill_total = $row_list->bill_rs + $row_list->bill_dr1 + $row_list->bill_dr2 + $row_list->bill_dr3;
            $row[] = '<div align="right">'.number_format($bill_total).',-</div>';
           
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Pl_pelayanan->count_all_tindakan(),
                        "recordsFiltered" => $this->Pl_pelayanan->count_filtered_tindakan(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function get_data_obat()
    {
        /*get data from model*/
        $list = $this->Pl_pelayanan->get_datatables_tindakan();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center">
                        <label class="pos-rel">
                            <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->kode_trans_pelayanan.'"/>
                            <span class="lbl"></span>
                        </label>
                    </div>';
            $row[] = '<div class="center"><a href="#" class="btn btn-xs btn-danger" onclick="delete_transaksi('.$row_list->kode_trans_pelayanan.')"><i class="fa fa-times-circle"></i></a></div>';
            $row[] = $row_list->kode_trans_pelayanan;
            $row[] = strtoupper($row_list->nama_tindakan);
            $row[] = '<div class="center">'.(int)$row_list->jumlah.'</div>';
            $row[] = '<div align="right">'.number_format($row_list->harga_satuan).',-</div>';
            $bill_total = $row_list->bill_rs + $row_list->bill_dr1 + $row_list->bill_dr2;
            $row[] = '<div align="right">'.number_format($bill_total).',-</div>';
           
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Pl_pelayanan->count_all_tindakan(),
                        "recordsFiltered" => $this->Pl_pelayanan->count_filtered_tindakan(),
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

    public function process_add_tindakan(){

        //print_r($_POST);die;
        // form validation
        if( isset($_GET['type']) AND $_GET['type']=='konsultasi'){
            $this->form_validation->set_rules('pl_kode_tindakan_hidden', 'Tindakan', 'trim');
        }else{
            $this->form_validation->set_rules('pl_kode_tindakan_hidden', 'Tindakan', 'trim|required');
        }
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

            $kode_trans_pelayanan = $this->master->get_max_number('tc_trans_pelayanan', 'kode_trans_pelayanan');
            
            $dataexc = array(
                /*form hidden input default*/
                'no_kunjungan' => $this->regex->_genRegex($this->input->post('no_kunjungan'),'RGXINT'),
                'no_registrasi' => $this->regex->_genRegex($this->input->post('no_registrasi'),'RGXINT'),
                'kode_kelompok' => $this->regex->_genRegex($this->input->post('kode_kelompok'),'RGXINT'),
                'kode_perusahaan' => $this->regex->_genRegex($this->input->post('kode_perusahaan'),'RGXINT'),
                'no_mr' => $this->regex->_genRegex($this->input->post('noMrHidden'),'RGXQSL'),
                'nama_pasien_layan' => $this->regex->_genRegex($this->input->post('nama_pasien_hidden'),'RGXQSL'),
                'kode_bagian_asal' => $this->regex->_genRegex($this->input->post('kode_bagian_asal'),'RGXQSL'),
                /*end form hidden input default*/
                'kode_bagian' => $this->regex->_genRegex($this->input->post('kode_bagian'),'RGXQSL'),
                'kode_klas' => $this->regex->_genRegex($this->input->post('kode_klas'),'RGXINT'),
                'kode_bagian_asal' => $this->regex->_genRegex($this->input->post('kode_bagian_asal'),'RGXQSL'),
                'tgl_transaksi' => date('Y-m-d'),                
                'jumlah' => 1,  
            );

            /*jika pasien hanya untuk konsultasi saja*/
            if( isset($_GET['type']) AND $_GET['type']=='konsultasi'){
                $dataexc['kode_dokter1'] = $this->input->post('kode_dokter_poli');
                /*tarif konsultasi*/
                $tarif_konsultasi = $this->tarif->insert_tarif_by_jenis_tindakan($dataexc, 12);
                /*tarif sarana rs*/
                $tarif_sarana = $this->tarif->insert_tarif_by_jenis_tindakan($dataexc, 13);

            /*jika tidak atau ada tambahan tindakan lainnya*/
            }else{

                $dataexc['kode_trans_pelayanan'] = $kode_trans_pelayanan;
                /*form hidden after select tindakan*/
                $dataexc['kode_tarif'] = $this->regex->_genRegex($this->input->post('kode_tarif'),'RGXINT');
                $dataexc['jenis_tindakan'] = ($this->regex->_genRegex($this->input->post('jenis_tindakan'),'RGXINT')!=0)?$this->regex->_genRegex($this->input->post('jenis_tindakan'),'RGXINT'):3;
                $dataexc['nama_tindakan'] = $this->regex->_genRegex($this->input->post('nama_tindakan'),'RGXQSL');
                
                $dataexc['kode_master_tarif_detail'] = $this->regex->_genRegex($this->input->post('kode_master_tarif_detail'),'RGXQSL');

                /*get tarif mulitiple kode dokter*/
                $tarifDokter = $this->tarif->getTarifMultipleDokter( $this->input->post('pl_kode_dokter_hidden') );

                $tarifInsert = $this->tarif->getTarifForinsert();

                $mergeData = array_merge($dataexc, $tarifDokter, $tarifInsert);

                /*save tc_trans_pelayanan*/
                $this->Pl_pelayanan->save('tc_trans_pelayanan', $mergeData);

            }

            /*save logs*/
            $this->logs->save('tc_trans_pelayanan', $kode_trans_pelayanan, 'insert new record on '.$this->title.' module', json_encode($dataexc),'kode_trans_pelayanan');

            
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

    public function process_add_obat(){

        
        // form validation
        $this->form_validation->set_rules('pl_kode_brg_hidden', 'Obat', 'trim|required');        

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

            $kode_trans_pelayanan = $this->master->get_max_number('tc_trans_pelayanan', 'kode_trans_pelayanan');

            /*get bill_rs*/
            $bill_rs = $this->input->post('pl_harga_satuan') * $this->input->post('pl_jumlah_obat');

            $dataexc = array(
                'kode_trans_pelayanan' => $kode_trans_pelayanan,
                /*form hidden input default*/
                'no_kunjungan' => $this->regex->_genRegex($this->input->post('no_kunjungan'),'RGXINT'),
                'no_registrasi' => $this->regex->_genRegex($this->input->post('no_registrasi'),'RGXINT'),
                'kode_kelompok' => $this->regex->_genRegex($this->input->post('kode_kelompok'),'RGXINT'),
                'kode_perusahaan' => $this->regex->_genRegex($this->input->post('kode_perusahaan'),'RGXINT'),
                'no_mr' => $this->regex->_genRegex($this->input->post('no_mr'),'RGXQSL'),
                'nama_pasien_layan' => $this->regex->_genRegex($this->input->post('nama_pasien_layan'),'RGXQSL'),
                'kode_bagian_asal' => $this->regex->_genRegex($this->input->post('kode_bagian_asal'),'RGXQSL'),
                /*end form hidden input default*/

                /*form hidden after select tindakan*/
                'kode_barang' => $this->regex->_genRegex($this->input->post('pl_kode_brg_hidden'),'RGXQSL'),
                'jenis_tindakan' => 9,
                'nama_tindakan' => $this->regex->_genRegex($this->input->post('nama_tindakan'),'RGXQSL'),
                'kode_bagian' => $this->regex->_genRegex($this->input->post('kode_bagian'),'RGXQSL'),
                'kode_klas' => $this->regex->_genRegex(16,'RGXINT'),
                'bill_rs' => $this->regex->_genRegex($bill_rs,'RGXINT'),
                'kode_profit' => $this->regex->_genRegex(2000,'RGXINT'),
                /*end form hidden after select obat*/
                'kode_bagian_asal' => $this->regex->_genRegex($this->input->post('kode_bagian_asal'),'RGXQSL'),
                'tgl_transaksi' => date('Y-m-d'),                
                'jumlah' => $this->regex->_genRegex($this->input->post('pl_jumlah_obat'),'RGXINT'),
                'harga_satuan' => $this->regex->_genRegex($this->input->post('pl_harga_satuan'),'RGXINT'),
                
            );
            

            /*save tc_trans_pelayanan*/
            $this->Pl_pelayanan->save('tc_trans_pelayanan', $dataexc);

            $bagian = ($this->input->post('kode_bagian_depo'))?$this->input->post('kode_bagian_depo'):$dataexc['kode_bagian'];

            $this->stok_barang->stock_process($dataexc['kode_barang'], $dataexc['jumlah'], $bagian,6, '', 'reduce');


            /*save logs*/
            $this->logs->save('tc_trans_pelayanan', $kode_trans_pelayanan, 'insert new record on '.$this->title.' module', json_encode($dataexc),'kode_trans_pelayanan');

            //print_r($dataexc);die;

            
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

    public function delete()
    {
        $id=$this->input->post('ID')?$this->input->post('ID',TRUE):null;
        if($id!=null){
            if($this->Pl_pelayanan->delete_trans_pelayanan($id)){
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

        //print_r($_POST);die;
        // form validation
        $this->form_validation->set_rules('noMrHidden', 'Pasien', 'trim|required');        
        $this->form_validation->set_rules('pl_anamnesa', 'Anamnesa', 'trim');        
        $this->form_validation->set_rules('pl_diagnosa', 'Diagnosa', 'trim|required');        
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

            /*cek transaksi minimal apakah sudah ada tindakan*/
            $cek_transaksi = $this->Pl_pelayanan->cek_transaksi_minimal($no_kunjungan);

            /*jika sudah ada minimal 1 transaksi atau tindakan, maka lanjutkan proses*/
            if($cek_transaksi){

                /*proses utama pasien selesai*/
                /*update pl_tc_poli*/
                $arrPlTcPoli = array('status_periksa' => 3, 'tgl_keluar_poli' => date('Y-m-d H:i:s'), 'no_induk' => $this->session->userdata('user')->user_id );
                $this->Pl_pelayanan->update('pl_tc_poli', $arrPlTcPoli, array('no_kunjungan' => $no_kunjungan ) );
                /*save logs pl_tc_poli*/
                $this->logs->save('pl_tc_poli', $no_kunjungan, 'update pl_tc_poli Modul Pelayanan', json_encode($arrPlTcPoli),'no_kunjungan');

                /*update tc_kunjungan*/
                $arrKunjungan = array('status_keluar' => 3, 'tgl_keluar' => date('Y-m-d H:i:s') );
                $this->Pl_pelayanan->update('tc_kunjungan', $arrKunjungan, array('no_kunjungan' => $no_kunjungan) );
                /*save logs tc_kunjungan*/
                $this->logs->save('tc_kunjungan', $no_kunjungan, 'update tc_kunjungan modul pelayanan', json_encode($arrKunjungan),'no_kunjungan');

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

                if($this->input->post('kode_riwayat')==0){
                    $this->Pl_pelayanan->save('th_riwayat_pasien', $riwayat_diagnosa);
                }else{
                    $this->Pl_pelayanan->update('th_riwayat_pasien', $riwayat_diagnosa, array('kode_riwayat' => $this->input->post('kode_riwayat') ) );
                }

                /*kondisi jika pasien dirujuk RI/RJ*/
                $status = $this->input->post('cara_keluar');
                $txt_rujuk_poli = 'Rujuk ke Poli Lain';
                $txt_rujuk_ri = 'Rujuk ke Rawat Inap';
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
                    $this->Pl_pelayanan->save('rg_tc_rujukan', $rujukan_data );
                   
                }

                /*update tc_trans_pelayanan with status=rujuk*/
                    $this->Pl_pelayanan->update('tc_trans_pelayanan', array('status_selesai' => 2), array('no_kunjungan' => $no_kunjungan ) );

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
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'type_pelayanan' => 'Pasien Selesai'));
            }

        
        }

    }

    public function saveSessionPoli(){

        //print_r($_POST);die;
        // form validation
        $this->form_validation->set_rules('poliklinik', 'Poli/Klinik', 'trim|required');      

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
            $bagian = $this->db->get_where('mt_bagian', array('kode_bagian' => $this->form_validation->set_value('poliklinik')) )->row();
            $this->session->set_userdata('kode_bagian', $this->form_validation->set_value('poliklinik'));
            $this->session->set_userdata('nama_bagian', $bagian->nama_bagian );

            echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'type_pelayanan' => 'Pasien Selesai'));
        
        }

    }

    public function destroy_session_kode_bagian()
    {
        $this->session->unset_userdata('kode_bagian');
        echo json_encode(array('status' => 200, 'message' => 'Silahkan pilih Poli/Klinik kembali'));

        
    }


}

/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
