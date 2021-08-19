<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pl_pelayanan_ruang_pemeriksaan extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'pelayanan/Pl_pelayanan_ruang_pemeriksaan');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('Pl_pelayanan_ruang_pemeriksaan_model', 'Pl_pelayanan_ruang_pemeriksaan');
        $this->load->model('registration/Reg_pasien_model', 'Reg_pasien');
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

        if( $this->session->userdata('kode_bagian') ){
            /*load view index*/
            $data['kode_bagian'] = $this->session->userdata('kode_bagian');
            $data['nama_bagian'] = $this->session->userdata('nama_bagian');
            $data['nama_dokter'] = $this->session->userdata('sess_nama_dokter');
            // get antrian pasien
            // $antrian_pasien = $this->Pl_pelayanan_ruang_pemeriksaan->get_next_antrian_pasien();
            // $next_pasien = isset($antrian_pasien)?$antrian_pasien: ''; 
            // $this->form($next_pasien->id_pl_tc_poli, $next_pasien->no_kunjungan);
            $this->load->view('Pl_pelayanan_ruang_pemeriksaan/index', $data);
        }else{
            if( isset($_GET['bag']) AND $_GET['bag'] != '' ){
                $data['kode_bagian'] = $_GET['bag'];
                $data['nama_bagian'] = $this->master->get_string_data('nama_bagian','mt_bagian', array('kode_bagian' => $_GET['bag']) );
                $this->load->view('Pl_pelayanan_ruang_pemeriksaan/index', $data);
            }else{
                $this->load->view('Pl_pelayanan_ruang_pemeriksaan/index_no_session_yet', $data);
            }
        }
    }


    public function form($id='', $no_kunjungan)
    {
         /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Pl_pelayanan_ruang_pemeriksaan/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*get value by id*/
        
        $data['kode_bagian'] = $this->session->userdata('kode_bagian');
        $data['nama_bagian'] = $this->session->userdata('nama_bagian');
        $data['nama_dokter'] = $this->session->userdata('sess_nama_dokter');
        
        $data['no_mr'] = $_GET['no_mr'];
        $data['id'] = $id;
        $data['value'] = $this->Pl_pelayanan_ruang_pemeriksaan->get_by_id($id);
        
        $data['kunjungan'] = $this->db->get_where('tc_kunjungan', array('no_kunjungan' => $no_kunjungan) )->row();
        // echo '<pre>';print_r($data['pemeriksaan']);die;
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Pl_pelayanan_ruang_pemeriksaan/form', $data);
    }

    public function get_data()
    {
        /*get data from model*/
        if(isset($_GET['search']) AND $_GET['search']==TRUE ){
            $this->find_data();
        }else{
            $list = $this->Pl_pelayanan_ruang_pemeriksaan->get_datatables(); 
            $data = array();
            $no = $_POST['start'];
            foreach ($list as $row_list) {
                $no++;
                $row = array();
                $row[] = '<div class="center">
                            <label class="pos-rel">
                                <input type="checkbox" class="ace" name="selected_id[]" value="'.$row_list->id_tc_pesanan.'"/>
                                <span class="lbl"></span>
                            </label>
                          </div>';
                $row[] = '<div class="left"><a href="#" onclick="getMenu('."'pelayanan/Pl_pelayanan_ruang_pemeriksaan/form/".$row_list->id_tc_pesanan."/".$row_list->referensi_no_kunjungan."?no_mr=".$row_list->no_mr."&bag=".$this->session->userdata('kode_bagian')."'".')">'.$row_list->referensi_no_kunjungan.'</a></div>';

                if( !isset($_GET['no_mr']) ){
                    $no_mr = ($row_list->no_mr == NULL)?'<i class="fa fa-user green bigger-150"></i> - ':$row_list->no_mr;
                }
                $row[] = $no_mr;
                $row[] = strtoupper($row_list->nama);
                $row[] = ($row_list->nama_perusahaan==NULL)?'<div class="left">PRIBADI/UMUM</div>':'<div class="left">'.$row_list->nama_perusahaan.'</div>';
                // $row[] = '<div class="left">'.$row_list->nama_bagian.'</div>';
                $row[] = '<div class="left">'.$row_list->nama_pegawai.'</div>';
                $row[] = '<div class="left">'.$row_list->nama_tarif.'</div>';
                // $row[] = ($row_list->status_konfirmasi_kedatangan == NULL) ? '<div class="center"><i class="fa fa-times-circle bigger-120 red"></i></div>' : $this->tanggal->formatDate($row_list->tgl_pesanan);


                $data[] = $row;
            }

            $output = array(
                            "draw" => $_POST['draw'],
                            "recordsTotal" => $this->Pl_pelayanan_ruang_pemeriksaan->count_all(),
                            "recordsFiltered" => $this->Pl_pelayanan_ruang_pemeriksaan->count_filtered(),
                            "data" => $data,
                    );
            //output to json format
            echo json_encode($output);
        }
        
    }

    public function form_input_hasil($id='', $no_kunjungan='')
    {
         /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Pl_pelayanan_ruang_pemeriksaan/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$_GET['id']);

        $data['no_kunjungan'] = $_GET['no_kunjungan'];
        $data['id_tc_pesanan'] = $_GET['id'];
        $data['sess_kode_bag'] = ($_GET['bag'])?$_GET['bag']:$this->session->userdata('kode_bag');
        $data['value'] = $this->Pl_pelayanan_ruang_pemeriksaan->get_by_id($_GET['id']);
        $data['pemeriksaan'] = $this->Pl_pelayanan_ruang_pemeriksaan->get_pemeriksaan($_GET['no_kunjungan']);
        $data['kunjungan'] = $this->db->get_where('tc_kunjungan', array('no_kunjungan' => $_GET['no_kunjungan']) )->row();
        // echo '<pre>'; print_r($data);die;
        /*title header*/
        $data['jenis_expertise'] = $_GET['bag'];
        $data['kode_bag_expertise'] = $_GET['bag'];
        /*load form view*/
        $this->load->view('Pl_pelayanan_ruang_pemeriksaan/form_input_hasil', $data);
    }

    public function get_data_antrian_pasien(){
        $list = $this->Pl_pelayanan_ruang_pemeriksaan->get_data_antrian_pasien();
        echo json_encode($list);

    }

    public function process(){

        // echo '<pre>';print_r($_POST);die;
        // form validation
        $this->form_validation->set_rules('noMrHidden', 'No MR', 'trim', array('required' => 'Pasien tidak ditemukan'));
               
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
            $txt_nl2br = nl2br($_POST['catatan_hasil'], '<br>');
            /*Update pm_tc_penunjang */
            $pm_tc_penunjang = array(
                'tgl_isihasil' => date('Y-m-d H:i:s'),
                'petugas_isihasil' => $this->session->userdata('user')->user_id,
                'status_isihasil' => 1,
                'catatan_hasil' => $txt_nl2br
            );
            $this->db->update('pm_tc_penunjang', $pm_tc_penunjang, array('no_kunjungan' => $this->input->post('no_kunjungan')));

            /*insert pm_tc_hasilpenunjang*/
            
            foreach($_POST['kode_trans_pelayanan'] as $key=>$row_dt){

                $kode_tc_hasilpenunjang = $this->master->get_max_number('pm_tc_hasilpenunjang', 'kode_tc_hasilpenunjang');
                $kode_trans_pelayanan = $row_dt;
                $hasil = $_POST['hasil_pemeriksaan'][$row_dt];

                $dataexc = array(
                    'kode_mt_hasilpm' => $_POST['kode_mt_hasilpm'][$row_dt],
                    'hasil' => $hasil,
                );

                // cek hasil apakah sudah pernah diinput
                $dt_ex = $this->db->get_where('pm_tc_hasilpenunjang', array('kode_trans_pelayanan' => $row_dt) );

               if($dt_ex->num_rows() > 0){
                    $this->db->update('pm_tc_hasilpenunjang', $dataexc, array('kode_tc_hasilpenunjang' => $dt_ex->row()->kode_tc_hasilpenunjang ) );
                    $this->db->trans_commit();
                }else{
                    $dataexc["kode_tc_hasilpenunjang"] = $kode_tc_hasilpenunjang; 
                    $dataexc["kode_trans_pelayanan"] = $kode_trans_pelayanan; 
                    $this->db->insert('pm_tc_hasilpenunjang', $dataexc);
                    $this->db->trans_commit();
                }

            }
                          
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'type_pelayanan' => 'isi_hasil', 'bagian' => $this->input->post('kode_bagian')));
            }
        
        }

    }

    public function find_data()
    {   
        $output = array( "data" => http_build_query($_POST) . "\n" );
        echo json_encode($output);
    }

    public function saveSessionPoli(){

        //print_r($_POST);die;
        // form validation
        $this->form_validation->set_rules('poliklinik', 'Poli/Klinik', 'trim|required');      
        $this->form_validation->set_rules('select_dokter', 'Dokter', 'trim|required');      

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
            $dokter = $this->db->get_where('mt_karyawan', array('kode_dokter' => $this->form_validation->set_value('select_dokter')) )->row();

            $this->session->set_userdata('kode_bagian', $this->form_validation->set_value('poliklinik'));
            $this->session->set_userdata('nama_bagian', $bagian->nama_bagian );
            $this->session->set_userdata('sess_kode_dokter', $this->form_validation->set_value('select_dokter'));
            $this->session->set_userdata('sess_nama_dokter', $dokter->nama_pegawai );

            echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'type_pelayanan' => 'Pasien Selesai'));
        
        }

    }

    public function destroy_session_kode_bagian()
    {
        $this->session->unset_userdata('kode_bagian');
        echo json_encode(array('status' => 200, 'message' => 'Silahkan pilih Poli/Klinik kembali'));

        
    }

    public function process_input_expertise(){

        
        // form validation
        $this->form_validation->set_rules('noMrHidden', 'Pasien', 'trim|required', array('required' => 'No MR Pasien Tidak ditemukan!') );        
        $this->form_validation->set_rules('konten', 'Input Hasil Expertise', 'trim|required');        
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

            /*insert log diagnosa pasien th_riwayat pasien*/
            $dtexpertise = array(
                'no_registrasi' => $this->form_validation->set_value('no_registrasi'),
                'no_kunjungan' => $no_kunjungan,
                'no_mr' => $this->form_validation->set_value('noMrHidden'),
                'nama_pasien' => $this->input->post('nama_pasien_layan'),
                'hasil_expertise' => $this->input->post('konten'),
                'jenis_expertise' => $this->input->post('jenis_expertise'),
                'kode_bagian_asal' => $this->input->post('kode_bagian_asal'),
                'kode_bagian_tujuan' => $this->input->post('kode_bag_expertise'),
                'nama_dokter' => $this->input->post('dokter_pemeriksa'),
            );
            // print_r($dtexpertise);die;

            if($this->input->post('kode_expertise')==0){
                $dtexpertise['created_date'] = date('Y-m-d H:i:s');
                $dtexpertise['created_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                $newId = $this->Pl_pelayanan_ruang_pemeriksaan->save('th_expertise_pasien', $dtexpertise);
            }else{
                $dtexpertise['updated_date'] = date('Y-m-d H:i:s');
                $dtexpertise['updated_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                $this->Pl_pelayanan_ruang_pemeriksaan->update('th_expertise_pasien', $dtexpertise, array('kode_expertise' => $this->input->post('kode_expertise') ) );
                $newId = $this->input->post('kode_expertise');
            }
            
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'title' => $dtexpertise['jenis_expertise'], 'kode_bagian_asal' => $dtexpertise['kode_bagian_asal'], 'kode_bagian_tujuan' => $dtexpertise['kode_bagian_tujuan'], 'type_pelayanan' => 'Expertise', 'ID' => $newId));
            }

        
        }

    }


}

/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
