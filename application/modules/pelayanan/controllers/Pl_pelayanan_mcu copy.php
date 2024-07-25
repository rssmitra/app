<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pl_pelayanan_mcu extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'pelayanan/Pl_pelayanan_mcu');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('Pl_pelayanan_mcu_model', 'Pl_pelayanan_mcu');
        $this->load->model('Pl_pelayanan_ri_model', 'Pl_pelayanan_ri');
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
            'title' => 'Medical Check Up',
            'breadcrumbs' => $this->breadcrumbs->show()
        );

        $this->load->view('Pl_pelayanan_mcu/index', $data);
    }

    public function form($id, $no_kunjungan)
    {
         /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Pl_pelayanan_mcu/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*get value by id*/
        $data['value'] = $this->Pl_pelayanan_mcu->get_by_id($id);
        //echo '<pre>';print_r($data['value']);die;
        $data['riwayat'] = $this->Pl_pelayanan_mcu->get_riwayat_pasien_by_id($no_kunjungan);

        $pemeriksaan = $this->Pl_pelayanan_mcu->get_pemeriksaan($data['value']->kode_gcu);
        if($pemeriksaan){
            $data['id_tc_pemeriksaan_fisik_mcu'] = $pemeriksaan->id_tc_pemeriksaan_fisik_mcu;
            $hasil = $this->Pl_pelayanan_mcu->get_hasil_by_kode_gcu($data['value']->kode_gcu);
            if($hasil){
                $data['hasil_kesimpulan'] = 1;
            }
        }
                
        /*variable*/
         /*type*/
       
        $kode_klas = 16;
        
        $data['no_mr'] = $data['value']->no_mr;
        $data['id'] = $id;
        $data['kode_klas'] = $kode_klas;
        $data['kode_profit'] = 2000;
        $data['no_kunjungan'] = $no_kunjungan;
        //echo '<pre>';print_r($data);die;
        /*title header*/
        $data['title'] = 'Medical Check Up';
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Pl_pelayanan_mcu/form', $data);
    }

    public function periksa_bagian($id_pl_tc_poli,$kode_tarif, $kode_dokter) { 
        
        $data['list'] = $this->Pl_pelayanan_mcu->get_mcu_tarif_detail($kode_tarif);

        $data_poli = $this->Pl_pelayanan_mcu->get_by_id($id_pl_tc_poli);
        // echo '<pre>';print_r($data_poli);die;
        $data['no_registrasi'] = $data_poli->no_registrasi;
        $data['kode_dokter_asal'] = $data_poli->kode_dokter;
        $data['dokter_asal'] = $data_poli->nama_pegawai;
        $data['no_kunjungan'] = $data_poli->no_kunjungan;
        $data['no_mr'] = $data_poli->no_mr;
        $data['kode_gcu'] = $data_poli->kode_gcu;
        $data['kode_tarif'] = $data_poli->kode_tarif;
        $data['nama_pasien_hidden'] = $data_poli->nama_pasien;
        $data['id_pl_tc_poli'] = $data_poli->id_pl_tc_poli;

        $kode_bag = '';
        $groupBag = array();
        foreach ($data['list'] as $val) {

            $nama_tindakan = $this->Pl_pelayanan_mcu->get_tarif($val->kode_referensi);
            $val->nama_tindakan = $nama_tindakan->nama_tarif;

            if($val->kode_bagian != $kode_bag){
                $val->dokter = $this->Pl_pelayanan_mcu->get_dokter_by_bagian($val->kode_bagian);
                //echo '<pre>';print_r($this->db->last_query());die;

                $kode_bag = $val->kode_bagian;
            }
            $groupBag[$val->nama_bagian][] = $val;
        }
        $data['group_bag'] = $groupBag;

        // echo '<pre>';print_r($data['group_bag']);die;

        $this->load->view('Pl_pelayanan_mcu/form_periksa_bagian', $data);
    
    }

    public function anamnesa($kode_gcu,$id='') { 
        
        $data['kode_gcu'] = $kode_gcu;
        $pemeriksaan = $this->Pl_pelayanan_mcu->get_pemeriksaan_by_id($id);
        if($pemeriksaan){
            $data['anamnesa'] = json_decode($pemeriksaan->anamnesa_mcu);
        }
        //echo"<pre>";print_r($data);die;
        $this->load->view('Pl_pelayanan_mcu/form_anamnesa', $data);
            
    }

    public function pemeriksaan_fisik($kode_gcu,$id='') { 
        
        $data['kode_gcu'] = $kode_gcu;
        $pemeriksaan = $this->Pl_pelayanan_mcu->get_pemeriksaan_by_id($id);
        if($pemeriksaan){
            $data['pemeriksaan_fisik'] = json_decode($pemeriksaan->pemeriksaan_fisik);
            $data['tinggi_badan'] =$pemeriksaan->tinggi_badan;
            $data['berat_badan'] =$pemeriksaan->berat_badan;
            $data['tekanan_darah'] =$pemeriksaan->tekanan_darah;
            $data['pernafasan'] =$pemeriksaan->pernafasan;
            $data['keadaan_umum'] =$pemeriksaan->keadaan_umum;
            $data['status_gizi'] =$pemeriksaan->status_gizi;
            $data['kesadaran'] =$pemeriksaan->kesadaran;
            $data['nadi'] =$pemeriksaan->nadi;
        }
        $data['gigi'] = $this->db->where(array('flag' => 'pemeriksaan_fisik_gigi'))->get('global_parameter')->result();

        $this->load->view('Pl_pelayanan_mcu/form_pemeriksaan_fisik', $data);
            
    }

    public function radiologi($kode_gcu,$no_registrasi,$id='') { 
        
        $data['kode_gcu'] = $kode_gcu;
        $data_kunjungan = $this->Pl_pelayanan_mcu->get_kunjungan_pm_by_no_reg($no_registrasi,'050201');
        $data['no_kunjungan'] = $data_kunjungan->no_kunjungan;
        $data['kode_penunjang'] = $data_kunjungan->kode_penunjang;
        $data['kode_bagian'] = $data_kunjungan->kode_bagian_tujuan;
        $data['no_mr'] = $data_kunjungan->no_mr;
        $data['status_isihasil'] = $data_kunjungan->status_isihasil;
        $pemeriksaan = $this->Pl_pelayanan_mcu->get_pemeriksaan_by_id($id);
        if($pemeriksaan){
            $data['pemeriksaan_radiologi'] = json_decode($pemeriksaan->pemeriksaan_radiologi);
            $data['pemeriksaan_ekg'] = json_decode($pemeriksaan->pemeriksaan_ekg);
        }
        //echo"<pre>";print_r($data);die;

        $this->load->view('Pl_pelayanan_mcu/form_radiologi', $data);
            
    }

    public function kesimpulan_saran($kode_gcu,$no_registrasi,$id='') { 
        
        $data['kode_gcu'] = $kode_gcu;
        $data_kunjungan = $this->Pl_pelayanan_mcu->get_kunjungan_pm_by_no_reg($no_registrasi,'050101');
        $data['no_kunjungan'] = $data_kunjungan->no_kunjungan;
        $data['kode_penunjang'] = $data_kunjungan->kode_penunjang;
        $data['kode_bagian'] = $data_kunjungan->kode_bagian_tujuan;
        $data['no_mr'] = $data_kunjungan->no_mr;
        $data['status_isihasil'] = $data_kunjungan->status_isihasil;
        $pemeriksaan = $this->Pl_pelayanan_mcu->get_pemeriksaan_by_id($id);
        if($pemeriksaan){
            $data['anamnesa'] = json_decode($pemeriksaan->anamnesa_mcu);
            $data['pemeriksaan_fisik'] = json_decode($pemeriksaan->pemeriksaan_fisik);
            $data['status_gizi'] =$pemeriksaan->status_gizi;
            $data['pemeriksaan_radiologi'] = json_decode($pemeriksaan->pemeriksaan_radiologi);
            $data['pemeriksaan_ekg'] = json_decode($pemeriksaan->pemeriksaan_ekg);
            $data['pemeriksaan_lab'] = $pemeriksaan->pemeriksaan_lab;
        }
        $hasil = $this->Pl_pelayanan_mcu->get_hasil_by_kode_gcu($kode_gcu);
        if($hasil){
            $data['hasil'] = json_decode($hasil->hasil);
            $data['kesimpulan'] = $hasil->kesimpulan;
            $data['kesan'] = $hasil->kesan;
            $data['kode_tc_hasilMcu'] = $hasil->kode_tc_hasilMcu;
        }
        //echo"<pre>";print_r($data);die;

        $this->load->view('Pl_pelayanan_mcu/form_kesimpulan_saran', $data);
            
    }

    public function form_end_visit()
    {
        $no_kunjungan = isset($_GET['no_kunjungan'])?$_GET['no_kunjungan']:'';
        $no_registrasi = isset($_GET['no_registrasi'])?$_GET['no_registrasi']:'';
        $riwayat = $this->Reg_pasien->get_detail_kunjungan_by_no_kunjungan($no_kunjungan);
        $kunjungan = $this->Pl_pelayanan_mcu->get_kunjungan_by_no_registrasi($no_registrasi);
        $data = array(
            'no_mr' => isset($_GET['no_mr'])?$_GET['no_mr']:'',
            'id' => isset($_GET['id'])?$_GET['id']:'',
            'no_kunjungan' => $no_kunjungan,
            'riwayat' => $riwayat,
            'kunjungan' => $kunjungan
            );

        //echo"<pre>";print_r($kunjungan);die;
        /*load form view*/
        $this->load->view('Pl_pelayanan_mcu/form_end_visit', $data);
    }


    public function get_data()
    {
        /*akan di filter berdasarkan pasien pada klinik masing2*/
        /*get data from model*/
        $list = $this->Pl_pelayanan_mcu->get_datatables();
        //print_r($this->db->last_query());die;
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
            /*fungsi rollback pasien, jika belum disubmit kasir maka poli masih bisa melakukan rollback*/
            /*cek transaksi*/
            $trans_kasir = ($row_list->status_periksa!=NULL)?TRUE:FALSE;
            $rollback_btn = ($trans_kasir==true)?'<li><a href="#" onclick="rollback('.$row_list->no_registrasi.','.$row_list->no_kunjungan.')">Rollback</a></li>':'';
            //$rollback_btn = '<li><a href="#" onclick="rollback('.$row_list->no_registrasi.','.$row_list->no_kunjungan.')">Rollback</a></li>';

            $cancel_btn = ($row_list->status_periksa==NULL) ? '<li><a href="#" onclick="cancel_visit('.$row_list->no_registrasi.','.$row_list->no_kunjungan.')">Batalkan Kunjungan</a></li>' : '' ;
            $cetak_hasil = ($row_list->status_periksa!=NULL) ? '<li><a href="#" onclick="cetak_hasil('.$row_list->kode_gcu.','.$row_list->id_pl_tc_poli.')">Cetak Hasil</a></li>' : '' ;

            $row[] = '<div class="center"><div class="btn-group">
                        <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">
                            <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-inverse">
                            '.$rollback_btn.' '.$cancel_btn.' '.$cetak_hasil.'                            
                            <li><a href="#">Selengkapnya</a></li>
                        </ul>
                    </div></div>';

            $row[] = '<div class="center">'.$row_list->no_kunjungan.'</div>';
            $row[] = '<div class="center">'.$row_list->no_mr.'</div>';
            $row[] = strtoupper($row_list->nama_pasien);
            $row[] = ($row_list->nama_perusahaan)?$row_list->nama_perusahaan:$row_list->nama_kelompok;
            $row[] = $this->tanggal->formatDateTime($row_list->tgl_jam_poli);
            $row[] = $row_list->nama_pegawai;
            $row[] = '<div class="center">'.$row_list->no_antrian.'</div>';
            $row[] = '<div class="center">'.$row_list->nama_tarif.'</div>';

            if($row_list->status_batal==1){
                $status_periksa = '<label class="label label-danger"><i class="fa fa-times-circle"></i> Batal Berobat</label>';
            }else{
                if($row_list->status_selesai==3){
                    
                    if($row_list->status_daftar!=2){
                        $status_periksa = ($row_list->status_periksa!=NULL)?'<label class="label label-success"><i class="fa fa-check-circle"></i> Selesai</label>':' <a href="#" class="btn btn-xs btn-info" onclick="show_modal('."'pelayanan/Pl_pelayanan_mcu/periksa_bagian/".$row_list->id_pl_tc_poli."/".$row_list->kode_tarif."/".$row_list->kode_dokter."  '".', '."'".strtoupper($row_list->nama_pasien)."'".')"> Periksa Bagian</a>';
                    }else{
                        $status_periksa = '<a href="#" class="btn btn-xs btn-primary" onclick="getMenu('."'pelayanan/Pl_pelayanan_mcu/form/".$row_list->id_pl_tc_poli."/".$row_list->no_kunjungan."'".')">Periksa</a>';
                    }
                }else {
                    $status_periksa = '<label class="label label-warning"><i class="fa fa-times-circle"></i> Belum Bayar</label>';
                }
            }

            $row[] = '<div class="center">'.$status_periksa.'</div>';
           
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Pl_pelayanan_mcu->count_all(),
                        "recordsFiltered" => $this->Pl_pelayanan_mcu->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function process_periksa_bagian()
    {
        
        // print_r($_POST);die;
      
        /*execution*/
        $this->db->trans_begin();           

        $no_kunjungan = $this->input->post('no_kunjungan');
        $no_registrasi = $this->input->post('no_registrasi');
        $no_mr = $this->input->post('no_mr');
      
        /*proses utama periksa bagian*/
        /*update pl_tc_poli*/
        $arrPlTcPoli = array('status_daftar' => 2 );
        // $this->Pl_pelayanan_mcu->update('pl_tc_poli', $arrPlTcPoli, array('kode_gcu' => $this->input->post('kode_gcu') ) );
        /*save logs pl_tc_poli*/
        // $this->logs->save('pl_tc_poli', $no_kunjungan, 'update pl_tc_poli Modul Pelayanan MCU', json_encode($arrPlTcPoli),'no_kunjungan');
         
        $this->db->trans_commit();

        /*daftar ke bagian*/
        if( is_array($_POST['id_mt_mcu_detail']) ){

            foreach ($_POST['id_mt_mcu_detail'] as $key => $value) {
                
                $data_paket = $this->Pl_pelayanan_mcu->get_paket_detail($value);
                // print_r($data_paket);die;
                $bagian_tujuan = isset($_POST['bagian_tujuan'][$value])?$_POST['bagian_tujuan'][$value]:0;

                if($bagian_tujuan != 0 and $bagian_tujuan != '010901'){
                    $no_kunjungan_exist = $this->db->get_where('tc_kunjungan', array('kode_bagian_tujuan' => $bagian_tujuan, 'kode_bagian_asal' => '010901') );
                    if( $no_kunjungan_exist->num_rows() == 0 ){
                        $no_kunjungan_tujuan = $this->daftar_pasien->daftar_kunjungan($title,$no_registrasi,$no_mr,$_POST['dokter'][$data_paket->kode_bagian],$data_paket->kode_bagian,'010901',1);
                    }else{
                        $no_kunjungan_tujuan = $no_kunjungan_exist->row()->no_kunjungan;
                    }
                }else{
                    $no_kunjungan_tujuan = $no_kunjungan;
                }
                
                if($data_paket->kode_bagian!='010901'){
                     /*daftar kunjungan */
                    $title = 'Dari MCU';
                    
                    $tujuan = substr($data_paket->kode_bagian, 1, 1);

                    if(isset($kode_poli))$kode_poli=0;
                    if(isset($kode_penunjang))$kode_penunjang=0;

                    // if($tujuan==1){
                    //     /*daftar pl_tc_poli */
                    //     $kode_poli = $this->master->get_max_number('pl_tc_poli', 'kode_poli');
                    //     $no_antrian = $this->master->get_no_antrian_poli($data_paket->kode_bagian,$_POST['dokter'][$data_paket->kode_bagian]);
                        
                    //     $datapoli['kode_poli'] = $kode_poli;
                    //     $datapoli['no_kunjungan'] = $no_kunjungan_tujuan;
                    //     $datapoli['kode_bagian'] = $data_paket->kode_bagian;
                    //     $datapoli['tgl_jam_poli'] = date('Y-m-d H:i:s');
                    //     $datapoli['kode_dokter'] = $_POST['dokter'][$data_paket->kode_bagian];
                    //     $datapoli['no_antrian'] = $no_antrian;
                    //     $datapoli['nama_pasien'] = $_POST['nama_pasien_hidden'];
                    //     $datapoli["flag_mcu"] = 1;
                    //     $datapoli["flag_bayar_konsul"] = 2;
              
                    //     /*save poli*/
                    //     $this->Pl_pelayanan_mcu->save('pl_tc_poli', $datapoli);

                    //     /*save logs*/
                    //     $this->logs->save('pl_tc_poli', $datapoli['kode_poli'], 'insert new record on Dari MCU module', json_encode($datapoli),'kode_poli');

                    //     // $this->db->trans_commit();
            
                    // }

                    // if($tujuan=='5'){

                    //     /*daftar penunjang */
                    //     $kode_penunjang = $this->master->get_max_number('pm_tc_penunjang', 'kode_penunjang');
                    //     $no_antrian_pm = $this->master->get_no_antrian_pm($data_paket->kode_bagian);
                    //     $klas = 16;

                    //     $data_pm_tc_penunjang = array(
                    //         'kode_penunjang' => $kode_penunjang,
                    //         'dr_pengirim' => $this->input->post('dokter_asal'),
                    //         'asal_daftar' => '010901',
                    //         'tgl_daftar' => date('Y-m-d H:i:s'),
                    //         'kode_bagian' => $data_paket->kode_bagian,
                    //         'no_kunjungan' => $no_kunjungan_tujuan,
                    //         'no_kunjungan_asal' => $no_kunjungan,
                    //         'no_antrian' => $no_antrian_pm,
                    //         'kode_klas' => $klas,
                    //         'petugas_input' => $this->session->userdata('user')->user_id,
                    //         'status_daftar' => 1,
                    //         'flag_mcu' => 1
                    //     );
            
                    //     /*save penunjang medis*/
                    //     $this->Pl_pelayanan_mcu->save('pm_tc_penunjang', $data_pm_tc_penunjang);
            
                    //     /*save logs*/
                    //     $this->logs->save('pm_tc_penunjang', $data_pm_tc_penunjang['kode_penunjang'], 'insert new record Dari MCU', json_encode($data_pm_tc_penunjang),'kode_penunjang');

                    //     $this->db->trans_commit();
            

                    // }

                }

                /*insert tc_trans_pelayanan_paket */
                
                $dataexec[] = array(
                    
                    'no_kunjungan' => $no_kunjungan_tujuan,
                    'no_registrasi' => $no_registrasi,
                    'kode_tarif_mcu' => $this->input->post('kode_tarif'),
                    'no_mr' => $no_mr,
                    'tgl_transaksi' => date('Y-m-d H:i:s'),
                    'kode_mt_mcu' => $data_paket->kode_mt_mcu,
                    'nama_tindakan' => $data_paket->tindakan_det,
                    'nama_tindakan_paket' => $data_paket->tindakan_mcu,
                    'kode_bagian' => $data_paket->kode_bagian,
                    'kode_bagian_asal' => $this->input->post('kode_bagian_asal'),
                    'bill_rs' => $data_paket->bill_rs,
                    'bill_dr' => $data_paket->bill_dr,
                    'bill_total' => $data_paket->total,
                    'jumlah' => 1,
                    'status_selesai' => 3,
                    'kode_tarif_detail' => $data_paket->kode_referensi,
                    'kode_penunjang' => isset($kode_penunjang)?$kode_penunjang:0,
                    'kode_mcu' => $this->input->post('kode_gcu'),
                    'kode_dokter_mcu' => $this->input->post('kode_dokter_asal')

                );

                /*save tc_trans_pelayanan_paket*/
                // $this->Pl_pelayanan_mcu->save('tc_trans_pelayanan_paket_mcu', $dataexec);

                // $this->db->trans_commit();

               
            }

            print_r($dataexec);die;
        }

                
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
        }
        else
        {
            $this->db->trans_commit();
            echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'id_pl_tc_poli' => $this->input->post('id_pl_tc_poli'), 'no_kunjungan' => $no_kunjungan));
        }


    }

    public function process_add_anamnesa()
    {
        //print_r($_POST);die;

        $this->form_validation->set_rules('noMrHidden', 'Pasien', 'trim|required', array('required' => 'No MR Pasien Tidak ditemukan!') ); 
        $this->form_validation->set_rules('keluhan_utama', 'Keluhan Utama', 'trim|required' );
        
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

            $dataexec = array(
                'keluhan_utama' => $this->form_validation->set_value('keluhan_utama'),
                'riwayat_penyakit_masa_lampau' => array(
                                        'sakit_kuning' => $this->input->post('riwayat_sakit_kuning'),
                                        'kencing_manis' => $this->input->post('riwayat_kencing_manis'),
                                        'hipertensi' => $this->input->post('riwayat_hipertensi'),
                                        'kencing_batu' => $this->input->post('riwayat_kencing_batu'),
                                        'asma' => $this->input->post('riwayat_asma'),
                                        'operasi' => $this->input->post('riwayat_operasi'),
                                        'penyakit_karena_kecelakaan' => $this->input->post('riwayat_krn_kecelakaan'),
                                        'lainnya' => $this->input->post('riwayat_lainnya')
                                    ),   
                'riwayat_penyakit_keluarga' => array(
                                        'alergi' => $this->input->post('riwayat_keluarga_alergi'),
                                        'kencing_manis' => $this->input->post('riwayat_keluarga_kencing_manis'),
                                        'penyakit_darah' => $this->input->post('riwayat_keluarga_penyakit_darah'),
                                        'penyakit_jiwa' => $this->input->post('riwayat_keluarga_penyakit_jiwa'),
                                        'hipertensi' => $this->input->post('riwayat_keluarga_hipertensi'),
                                        'kencing_batu' => $this->input->post('riwayat_keluarga_kencing_batu'),
                                        'lainnya' => $this->input->post('riwayat_keluarga_lainnya')
                                    ),
                'alergi' => array(
                                    'alergi_makanan' => $this->input->post('alergi_makanan'),
                                    'alergi_udara' => $this->input->post('alergi_udara'),
                                    'alergi_obat' => $this->input->post('alergi_obat'),
                                    'alergi_lainnya' => $this->input->post('alergi_lainnya')
                                )
               
                
            );

            if($this->input->post('id_tc_pemeriksaan_fisik_mcu')==0){
                $id_tc_pemeriksaan_fisik_mcu = $this->Pl_pelayanan_mcu->save('tc_pemeriksaan_fisik_mcu', array('anamnesa_mcu' => json_encode($dataexec),'kode_mcu' => $this->input->post('kode_gcu')));
            }else{
                $id_tc_pemeriksaan_fisik_mcu = $this->input->post('id_tc_pemeriksaan_fisik_mcu');
                $this->Pl_pelayanan_mcu->update('tc_pemeriksaan_fisik_mcu', array('anamnesa_mcu' => json_encode($dataexec)), array('id_tc_pemeriksaan_fisik_mcu' => $id_tc_pemeriksaan_fisik_mcu ) );
            }
                    
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'id_pl_tc_poli' => $this->input->post('id_pl_tc_poli'), 'no_kunjungan' =>  $this->input->post('no_kunjungan') ,'kode_gcu' => $this->input->post('kode_gcu'), 'id_tc_pemeriksaan_fisik_mcu' => $id_tc_pemeriksaan_fisik_mcu));
            }

         }



    }

    public function process_add_pemeriksaan_fisik()
    {
        //print_r($_POST);die;

        $this->form_validation->set_rules('noMrHidden', 'Pasien', 'trim|required', array('required' => 'No MR Pasien Tidak ditemukan!') ); 
        $this->form_validation->set_rules('fisik_tinggi_badan', 'Tinggi Badan', 'trim|required' );
        $this->form_validation->set_rules('fisik_tekanan_darah', 'Tekanan Darah', 'trim|required' );
        $this->form_validation->set_rules('fisik_berat_badan', 'Berat Badan', 'trim|required' );
        $this->form_validation->set_rules('fisik_nadi', 'Nadi', 'trim|required' );
        $this->form_validation->set_rules('fisik_pernafasan', 'Pernafasan', 'trim|required' );
        $this->form_validation->set_rules('fisik_keadaan_umum', 'Keadaan Umum', 'trim|required' );
        $this->form_validation->set_rules('fisik_kesadaran', 'Kesadaran', 'trim|required' );
        $this->form_validation->set_rules('fisik_status_gizi', 'Status Gizi', 'trim|required' );
        
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

            $data_fisik = array(
                'buta_warna' => $this->input->post('fisik_buta_warna'),
                'mata' => array(
                    'konjungtiva' => $this->input->post('fisik_mata_konjungtiva'),
                    'sclera' => $this->input->post('fisik_mata_sclera'),
                    'reflek_cahaya' => $this->input->post('fisik_mata_reflek_cahaya'),
                    'penglihatan_atau_visus' => $this->input->post('fisik_mata_visus'),
                    'kacamata' => $this->input->post('fisik_mata_kacamata'),
                ),
                'tht' => array(
                    'telinga' => $this->input->post('fisik_tht_telinga'),
                    'hidung' => $this->input->post('fisik_tht_hidung'),
                    'tenggorokan' => $this->input->post('fisik_tht_tenggorokan'),
                ),
                'mulut_gigi' => array(
                    'gigi' => json_encode($this->input->post('fisik_gigi')),
                    'lidah' => $this->input->post('fisik_lidah'),
                ),
                'leher' => array(
                    'jvp' => $this->input->post('fisik_leher_JVP'),
                    'tiroid' => $this->input->post('fisik_leher_tiroid'),
                    'kel_getah_bening' => $this->input->post('fisik_leher_getah_bening'),
                ),
                'thorax' => array(
                    'paru_kanan' => $this->input->post('fisik_thorax_paru_kanan'),
                    'paru_kiri' => $this->input->post('fisik_thorax_paru_kiri'),
                ),
                'jantung' => array(
                    'besar' => $this->input->post('fisik_jantung_besar'),
                    'bunyi_S1_strip_S2' => $this->input->post('fisik_jantung_bunyi'),
                    'bising' => $this->input->post('fisik_jantung_bising'),
                ),
                'abdomen' => array(
                    'hati_atau_limpa' => $this->input->post('fisik_abdomen_limpa'),
                    'nyeri_tekan' => $this->input->post('fisik_abdomen_nyeri_tekan'),
                    'tumor' => $this->input->post('fisik_abdomen_tumor'),
                    'extremitas' => $this->input->post('fisik_abdomen_extremitas'),
                    'neurologis' => $this->input->post('fisik_abdomen_neurologis'),
                    'kulit_atau_turgor' => $this->input->post('fisik_abdomen_kulit'),
                    'kel_getah_bening' => $this->input->post('fisik_abdomen_getah_bening'),
                    'lainnya' => $this->input->post('fisik_abdomen_lainnya')
                ),
                                
            );

            $dataexc = array(
               'tinggi_badan'	    => (int)$this->input->post('fisik_tinggi_badan'),
               'tekanan_darah'	    => $this->input->post('fisik_tekanan_darah'),
               'pernafasan'		    => (int)$this->input->post('fisik_pernafasan'),
               'keadaan_umum'		=> $this->input->post('fisik_keadaan_umum'),
               'status_gizi'		=> $this->input->post('fisik_status_gizi'),
               'kesadaran'		    => $this->input->post('fisik_kesadaran'),
               'berat_badan'		=> (int)$this->input->post('fisik_berat_badan'),
               'nadi'				=> (int)$this->input->post('fisik_nadi'),
               'pemeriksaan_fisik ' => json_encode($data_fisik)
            );
            
            if($this->input->post('id_tc_pemeriksaan_fisik_mcu')==0){
                $dataexc['kode_mcu'] = $this->input->post('kode_gcu');
                $id_tc_pemeriksaan_fisik_mcu = $this->Pl_pelayanan_mcu->save('tc_pemeriksaan_fisik_mcu',$dataexc);
            }else{
                $id_tc_pemeriksaan_fisik_mcu = $this->input->post('id_tc_pemeriksaan_fisik_mcu');
                $this->Pl_pelayanan_mcu->update('tc_pemeriksaan_fisik_mcu', $dataexc, array('id_tc_pemeriksaan_fisik_mcu' => $id_tc_pemeriksaan_fisik_mcu ) );
            }
                    
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'id_pl_tc_poli' => $this->input->post('id_pl_tc_poli'), 'no_kunjungan' =>  $this->input->post('no_kunjungan') , 'kode_gcu' => $this->input->post('kode_gcu'), 'id_tc_pemeriksaan_fisik_mcu' => $id_tc_pemeriksaan_fisik_mcu));
            }

         }

    }

    public function process_add_radiologi()
    {
        //print_r($_POST);die;

        $this->form_validation->set_rules('noMrHidden', 'Pasien', 'trim|required', array('required' => 'No MR Pasien Tidak ditemukan!') ); 
        $this->form_validation->set_rules('hasil_rad', 'Thorax Foto', 'trim|required' );
        $this->form_validation->set_rules('kesan_rad', 'Kesan (Radiologi)', 'trim|required' );
        $this->form_validation->set_rules('ekg_irama', 'Irama', 'trim|required' );
        $this->form_validation->set_rules('ekg_HR', 'HR', 'trim|required' );
        $this->form_validation->set_rules('kesan_ekg', 'Kesan (EKG)', 'trim|required' );
        
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

            $data_pemeriksaan_rad = array(
                'hasil' => $this->form_validation->set_value('hasil_rad'),
                'kesan' => $this->form_validation->set_value('kesan_rad')
            );

            $data_pemeriksaan_ekg = array(
                'irama' => $this->form_validation->set_value('ekg_irama'),
                'hr' => $this->form_validation->set_value('ekg_HR'),
                'kesan' => $this->form_validation->set_value('kesan_ekg')
            );

            $dataexc = array(
               'pemeriksaan_radiologi' => json_encode($data_pemeriksaan_rad),
               'pemeriksaan_ekg' => json_encode($data_pemeriksaan_ekg)
            );
            
            if($this->input->post('id_tc_pemeriksaan_fisik_mcu')==0){
                $dataexc['kode_mcu'] = $this->input->post('kode_gcu');
                $id_tc_pemeriksaan_fisik_mcu = $this->Pl_pelayanan_mcu->save('tc_pemeriksaan_fisik_mcu',$dataexc);
            }else{
                $id_tc_pemeriksaan_fisik_mcu = $this->input->post('id_tc_pemeriksaan_fisik_mcu');
                $this->Pl_pelayanan_mcu->update('tc_pemeriksaan_fisik_mcu', $dataexc, array('id_tc_pemeriksaan_fisik_mcu' => $id_tc_pemeriksaan_fisik_mcu ) );
            }
                    
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'id_pl_tc_poli' => $this->input->post('id_pl_tc_poli'), 'no_kunjungan' =>  $this->input->post('no_kunjungan') , 'kode_gcu' => $this->input->post('kode_gcu') , 'no_registrasi' => $this->input->post('no_registrasi') ,'id_tc_pemeriksaan_fisik_mcu' => $id_tc_pemeriksaan_fisik_mcu));
            }

         }

    }

    public function process_add_kesimpulan()
    {
        // print_r($_POST);die;

        $this->form_validation->set_rules('noMrHidden', 'Pasien', 'trim|required', array('required' => 'No MR Pasien Tidak ditemukan!') ); 
        $this->form_validation->set_rules('kesimpulan_laboratorium', 'Laboratorium', 'trim|required' );
        $this->form_validation->set_rules('kesimpulan_audiometri', 'Audiometri', 'trim|required' );
        $this->form_validation->set_rules('kesimpulan_treadmill', 'Treadmill', 'trim|required' );
        $this->form_validation->set_rules('kesimpulan_kesan', 'Kesan', 'trim|required' );
                
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
            
            /*update tc_pemeriksaan_fisik_mcu hasil lab first */
            $data_pemeriksaan_lab = array(
                'pemeriksaan_lab' => $this->form_validation->set_value('kesimpulan_laboratorium')
            );

            if($this->input->post('id_tc_pemeriksaan_fisik_mcu')==0){
                $data_pemeriksaan_lab['kode_mcu'] = $this->input->post('kode_gcu');
                $id_tc_pemeriksaan_fisik_mcu = $this->Pl_pelayanan_mcu->save('tc_pemeriksaan_fisik_mcu',$data_pemeriksaan_lab);
            }else{
                $id_tc_pemeriksaan_fisik_mcu = $this->input->post('id_tc_pemeriksaan_fisik_mcu');
                $this->Pl_pelayanan_mcu->update('tc_pemeriksaan_fisik_mcu', $data_pemeriksaan_lab, array('id_tc_pemeriksaan_fisik_mcu' => $id_tc_pemeriksaan_fisik_mcu ) );
            }

            /*insert mcu_tc_hasil */

            $data_hasil = array(
                'keluhan_saat_ini' => $this->input->post('kesimpulan_keluhan'),
                'riwayat_penyakit' => $this->input->post('kesimpulan_riwayat_penyakit'),
                'riwayat_penyakit_keluarga' => $this->input->post('kesimpulan_riwayat_penyakit_keluarga'),
                'alergi' => $this->input->post('kesimpulan_alergi'),
                'pemeriksaan_fisik' => array(
                    'status_gizi' => $this->input->post('kesimpulan_status_gizi'),
                    'gigi' => $this->input->post('kesimpulan_gigi'),
                ),
                'radiologi' => $this->input->post('kesimpulan_radiologi'),
                'ekg' => $this->input->post('kesimpulan_ekg'),
                'laboratorium' => $this->form_validation->set_value('kesimpulan_laboratorium'),
                'buta_warna' => $this->input->post('kesimpulan_buta_warna'),
                'audiometri' => $this->input->post('kesimpulan_audiometri'),
                'treadmill' => $this->input->post('kesimpulan_treadmill'),
            );

            $dataexc = array(
                'kode_mcu' => $this->input->post('kode_gcu'),
                'kode_tarif' => $this->input->post('kode_tarif'),
                'kode_trans_pelayanan' => $this->input->post('kode_trans_pelayanan'),
                'hasil' => json_encode($data_hasil),
                'kesimpulan' => $this->form_validation->set_value('kesimpulan_kesan'),
                'kesan' =>  $this->input->post('kesimpulan_saran'),
             );

             //print_r($dataexc);die;
            
            if($this->input->post('kode_tc_hasilMcu')==0){
                $kode_tc_hasilMcu = $this->Pl_pelayanan_mcu->save('mcu_tc_hasil',$dataexc);
            }else{
                $kode_tc_hasilMcu = $this->input->post('kode_tc_hasilMcu');
                $this->Pl_pelayanan_mcu->update('mcu_tc_hasil', $dataexc, array('kode_tc_hasilMcu' => $kode_tc_hasilMcu ) );
            }
                    
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'kode_tc_hasilMcu' => $kode_tc_hasilMcu, 'no_kunjungan' =>  $this->input->post('no_kunjungan') , 'id_pl_tc_poli' => $this->input->post('id_pl_tc_poli'), 'id_tc_pemeriksaan_fisik_mcu' => $id_tc_pemeriksaan_fisik_mcu));
            }

         }

    }

    public function delete()
    {
        $id=$this->input->post('ID')?$this->input->post('ID',TRUE):null;
        if($id!=null){
            if($this->Pl_pelayanan_mcu->delete_trans_pelayanan($id)){
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
        $this->form_validation->set_rules('noMrHidden', 'Pasien', 'trim|required', array('required' => 'No MR Pasien Tidak ditemukan!') );        
         

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

            $no_kunjungan = $this->input->post('no_kunjungan');
            $no_registrasi = $this->input->post('no_registrasi');

            /*cek kunjungan poli */
            $cek_poli = $this->Pl_pelayanan_mcu->cek_poli_pulang($no_registrasi);
            if($cek_poli){
                
                /*batalkan kunjungan poli */
                foreach ($cek_poli as $value) {
                     /*tc_kunjungan*/
                    $kunj_data = array('tgl_keluar' => date('Y-m-d H:i:s'), 'status_keluar' => 1, 'status_batal' => 1 );
                    $this->db->update('tc_kunjungan', $kunj_data, array('no_registrasi' => $value->no_registrasi, 'no_kunjungan' => $value->no_kunjungan ) );
                    $this->logs->save('tc_kunjungan', $value->no_kunjungan, 'update tc_kunjungan Modul Pelayanan', json_encode($kunj_data),'no_kunjungan');

                    /*pl_tc_poli*/
                    $poli_data = array('status_batal' => 1, 'no_induk' => $this->session->userdata('user')->user_id, 'created_by' => $this->session->userdata('user')->fullname );
                    $this->db->update('pl_tc_poli', $poli_data, array('no_kunjungan' => $value->no_kunjungan) );
                    $this->logs->save('pl_tc_poli', $value->no_kunjungan, 'update pl_tc_poli Modul Pelayanan', json_encode($poli_data),'no_kunjungan');

                }

            }

            /*cek penunjang */
            // $cek_pm = $this->Pl_pelayanan_mcu->cek_pm_pulang($no_kunjungan);
            // if($cek_pm){
            //     echo json_encode(array('status' => 301, 'message' => 'Maaf pasien masih dalam antrian Penunjang !'));
            //     exit;
            // }

            /*cek hasil minimal apakah sudah ada hasil*/
            $cek_hasil = $this->Pl_pelayanan_mcu->cek_hasil($this->input->post('kode_gcu'));

            if($cek_hasil){
                echo json_encode(array('status' => 301, 'message' => 'Tidak ada data hasil pemeriksaan'));
                exit;
            }

            /*cek kesimpulan minimal apakah sudah ada hasil*/
            $cek_kesimpulan = $this->Pl_pelayanan_mcu->cek_kesimpulan($this->input->post('kode_gcu'));

            if($cek_kesimpulan){
                echo json_encode(array('status' => 301, 'message' => 'Tidak ada data kesimpulan/saran'));
                exit;
            }

            /*proses utama pasien selesai*/
            /*update pl_tc_poli*/
            $arrPlTcPoli = array('status_periksa' => 1, 'status_daftar' => 3, 'tgl_keluar_poli' => date('Y-m-d H:i:s'), 'no_induk' => $this->session->userdata('user')->user_id, 'created_by' => $this->session->userdata('user')->fullname );
            $this->Pl_pelayanan_mcu->update('pl_tc_poli', $arrPlTcPoli, array('no_kunjungan' => $no_kunjungan ) );
            //print_r($this->db->last_query());die;
            /*save logs pl_tc_poli*/
            $this->logs->save('pl_tc_poli', $no_kunjungan, 'update pl_tc_poli Modul Pelayanan', json_encode($arrPlTcPoli),'no_kunjungan');
            

            /*kondisi jika pasien dirujuk RI/RJ*/
            $status = $this->input->post('cara_keluar');
            $txt_rujuk_poli = 'Rujuk ke Poli Lain';
            $txt_rujuk_ri = 'Rujuk ke Rawat Inap';
            if( in_array($status, array($txt_rujuk_ri,$txt_rujuk_poli) ) ){
                $max_kode_rujukan = $this->master->get_max_number('rg_tc_rujukan', 'kode_rujukan');
                $tujuan = ($status==$txt_rujuk_poli)?$this->input->post('rujukan_tujuan'):'030001';
                $rujukan_data = array(
                    'kode_rujukan' => $max_kode_rujukan,
                    'rujukan_dari' => $this->input->post('kode_bagian_asal'),
                    'no_mr' => $this->form_validation->set_value('noMrHidden'),
                    'no_kunjungan_lama' => $no_kunjungan,
                    'no_registrasi' => $this->input->post('no_registrasi'),
                    'rujukan_tujuan' => $tujuan,
                    'status' => 0,
                    'tgl_input' => date('Y-m-d H:i:s'),
                );
                /*insert rg_tc_rujukan*/
                $this->Pl_pelayanan_mcu->save('rg_tc_rujukan', $rujukan_data );
                
            }

            /*update kode_bagian_keluar*/
            $arrRegistrasi = array('kode_bagian_keluar' => $_POST['kode_bagian_asal']);
            $this->Pl_pelayanan_mcu->update('tc_registrasi', $arrRegistrasi, array('no_registrasi' => $_POST['no_registrasi']) );
            /*save logs tc_registrasi*/
            $this->logs->save('tc_registrasi', $_POST['no_registrasi'], 'update tc_registrasi modul pelayanan', json_encode($arrRegistrasi),'no_registrasi');

            /*update tc_kunjungan*/
            $arrKunjungan = array(
                'status_keluar' => 3, 
                'tgl_keluar' => date('Y-m-d H:i:s'), 
                'cara_keluar_pasien' => isset($_POST['cara_keluar'])?$_POST['cara_keluar']:'', 
                'pasca_pulang' => isset($_POST['pasca_pulang'])?$_POST['pasca_pulang']:'' 
            );
            $this->Pl_pelayanan_mcu->update('tc_kunjungan', $arrKunjungan, array('no_registrasi' => $no_registrasi) );
            /*save logs tc_kunjungan*/
            $this->logs->save('tc_kunjungan', $no_registrasi, 'update tc_kunjungan modul pelayanan', json_encode($arrKunjungan),'no_registrasi');       

            
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'type_pelayanan' => 'pasien_selesai'));
            }

        
        }

    }

    public function cetak_hasil(){

        $this->load->library('pdf','get_pdf');
        
        $kode_mcu = $_GET['kode_mcu'];
        $id_pl_tc_poli = $_GET['id_pl_tc_poli'];
        
        $pemeriksaan = $this->Pl_pelayanan_mcu->get_pemeriksaan($kode_mcu);
        if($pemeriksaan){
            $data['anamnesa'] = json_decode($pemeriksaan->anamnesa_mcu);
            $data['pemeriksaan_fisik'] = json_decode($pemeriksaan->pemeriksaan_fisik);
            $data['status_gizi'] =$pemeriksaan->status_gizi;
            $data['pemeriksaan_radiologi'] = json_decode($pemeriksaan->pemeriksaan_radiologi);
            $data['pemeriksaan_ekg'] = json_decode($pemeriksaan->pemeriksaan_ekg);
            $data['pemeriksaan_lab'] = $pemeriksaan->pemeriksaan_lab;
            $data['fisik'] = $pemeriksaan;
        }
        $hasil = $this->Pl_pelayanan_mcu->get_hasil_by_kode_gcu($kode_mcu);
        if($hasil){
            $data['hasil'] = json_decode($hasil->hasil);
            $data['kesimpulan'] = $hasil->kesimpulan;
            $data['kesan'] = $hasil->kesan;
            $data['kode_tc_hasilMcu'] = $hasil->kode_tc_hasilMcu;
        }

        $data['kunjungan'] = $this->Pl_pelayanan_mcu->get_by_id($id_pl_tc_poli);

        $data['pasien'] = $this->Reg_pasien->get_by_mr($data['kunjungan']->no_mr);

        $userDob = $data['pasien']->tgl_lhr;
 
        //Create a DateTime object using the user's date of birth.
        $dob = new DateTime($userDob);
     
        //We need to compare the user's date of birth with today's date.
        $now = new DateTime();

        //Calculate the time difference between the two dates.
        $difference = $now->diff($dob);

        //Get the difference in years, as we are looking for the user's age.
        $umur = $difference->format('%y');

        $data['pasien']->umur = $umur;


        /*parameter */
        $data['param_agama'] = $this->Pl_pelayanan_mcu->get_param('mst_religion','religion_name as label,religion_id as value',array('is_active' => 'Y'));
        $data['param_perkawinan'] = $this->Pl_pelayanan_mcu->get_param('mst_marital_status','ms_name as label,ms_id as value',array('is_active' => 'Y'));
        $data['param_status_gizi'] = $this->Pl_pelayanan_mcu->get_param('global_parameter','label,value',array('flag' => 'status_gizi'));
        $data['param_kesadaran'] = $this->Pl_pelayanan_mcu->get_param('global_parameter','label,value',array('flag' => 'kesadaran_pasien'));
        $data['param_buta_warna'] = $this->Pl_pelayanan_mcu->get_param('global_parameter','label,value',array('flag' => 'buta_warna'));
        $data['param_hidung'] = $this->Pl_pelayanan_mcu->get_param('global_parameter','label,value',array('flag' => 'pemeriksaan_fisik_hidung'));
        $data['param_gigi'] = $this->Pl_pelayanan_mcu->get_param('global_parameter','label,value',array('flag' => 'pemeriksaan_fisik_gigi'));
        $data['param_lidah'] = $this->Pl_pelayanan_mcu->get_param('global_parameter','label,value',array('flag' => 'pemeriksaan_fisik_lidah'));
        $data['param_jvp'] = $this->Pl_pelayanan_mcu->get_param('global_parameter','label,value',array('flag' => 'pemeriksaan_JVP'));
        $data['param_fisik'] = $this->Pl_pelayanan_mcu->get_param('global_parameter','label,value',array('flag' => 'pemeriksaan_fisik'));
        $data['param_jantung_besar'] = $this->Pl_pelayanan_mcu->get_param('global_parameter','label,value',array('flag' => 'pemeriksaan_jantung_besar'));
        $data['param_jantung_S1_S2'] = $this->Pl_pelayanan_mcu->get_param('global_parameter','label,value',array('flag' => 'pemeriksaan_jantung_bunyi_S1_S2'));
        $data['param_jantung_bising'] = $this->Pl_pelayanan_mcu->get_param('global_parameter','label,value',array('flag' => 'pemeriksaan_jantung_bising'));
        $data['param_abdomen_lainnya'] = $this->Pl_pelayanan_mcu->get_param('global_parameter','label,value',array('flag' => 'pemeriksaan_abdomen_lainnya'));
        $data['param_ekg'] = $this->Pl_pelayanan_mcu->get_param('global_parameter','label,value',array('flag' => 'pemeriksaan_irama_ekg'));
        $data['param_kesan_mcu'] = $this->Pl_pelayanan_mcu->get_param('global_parameter','label,value',array('flag' => 'kesan_mcu'));

        //echo"<pre>";print_r($data);die;

        //$this->load->view('Pl_pelayanan_mcu/hasil_mcu', $data);
        
        $html_content =  $this->load->view('Pl_pelayanan_mcu/hasil_mcu', $data, TRUE);   

        $this->exportHasilMCU($html_content,$data['kunjungan']->no_registrasi,$data['pasien']->nama_pasien);
        
    
    }

    public function exportHasilMCU($html,$no_reg,$nama)
    {
        /*Print PDF */
        /*default*/
        /*filename and title*/
        
        $filename = 'MCU_'.$no_reg.'_'.$nama.'';
        $tanggal = new Tanggal();
        $pdf = new TCPDF('P', PDF_UNIT, array(470,280), true, 'UTF-8', false);
        $pdf->SetCreator(PDF_CREATOR);
        
        $pdf->SetAuthor(COMP_FULL);
        $pdf->SetTitle(''.$filename.'');

        $PDF_HEADER_LOGO = "logo_rssm_default.png";//any image file. check correct path.
        $PDF_HEADER_LOGO_WIDTH = "20";
        $PDF_HEADER_TITLE = COMP_LONG;
        $PDF_HEADER_STRING = COMP_ADDRESS;
        $PDF_FONT_NAME_MAIN = "helvetica";
        $PDF_FONT_SIZE_MAIN="8";

        $pdf->setHeaderFont(Array($PDF_FONT_NAME_MAIN, '', $PDF_FONT_SIZE_MAIN));
        $pdf->SetHeaderData($PDF_HEADER_LOGO, $PDF_HEADER_LOGO_WIDTH, $PDF_HEADER_TITLE, $PDF_HEADER_STRING);

        $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
        
    // set default monospaced font
        $pdf->SetDefaultMonospacedFont(PDF_FONT_MONOSPACED);

    // set margins
        //$pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT,PDF_MARGIN_BOTTOM);
        $pdf->SetMargins(20, 25, 25, true); 
        $pdf->SetFooterMargin(20);

    // set auto page breaks
        $pdf->SetAutoPageBreak(TRUE, PDF_MARGIN_BOTTOM);

    // set image scale factor
        $pdf->setImageScale(PDF_IMAGE_SCALE_RATIO);
    
    // auto page break //
        //$pdf->SetAutoPageBreak(TRUE, 30);

        //set page orientation
        
    // set some language-dependent strings (optional)
        if (@file_exists(dirname(__FILE__).'/lang/eng.php')) {
            require_once(dirname(__FILE__).'/lang/eng.php');
            $pdf->setLanguageArray($l);
        }
        
        $pdf->SetFont('helvetica', '', 9);
        $pdf->ln();

        //kotak form

        $pdf->AddPage('P', 'A4');
        //$pdf->setY(10);
        $pdf->setXY(5,10,5,5);
        // $pdf->SetMargins(0, 25, 25, true); 
        // /* $pdf->Cell(150,42,'',1);*/
        
        $result = $html;

        // output the HTML content
        $pdf->writeHTML($result, true, false, true, false, '');
        ob_end_clean();
        /*save to folder*/
        $pdf->Output('uploaded/'.$filename.'.pdf', 'I'); 

        /*show pdf*/
        //$pdf->Output(''.$reg_data->no_registrasi.'.pdf', 'I'); 
        /*download*/
        //$pdf->Output(''.$reg_data->no_registrasi.'.pdf', 'D'); 
    }


    public function cancel_visit()
    {   
        $this->db->trans_begin();   
        /*update tc_registrasi*/
        $reg_data = array('tgl_jam_keluar' => date('Y-m-d H:i:s'), 'kode_bagian_keluar' => $_POST['kode_bag'], 'status_batal' => 1 );
        $this->db->update('tc_registrasi', $reg_data, array('no_registrasi' => $_POST['no_registrasi'] ) );
        $this->logs->save('tc_registrasi', $_POST['no_registrasi'], 'update tc_registrasi Modul Pelayanan', json_encode($reg_data),'no_registrasi');


        /*tc_kunjungan*/
        $kunj_data = array('tgl_keluar' => date('Y-m-d H:i:s'), 'status_keluar' => 1, 'status_batal' => 1 );
        $this->db->update('tc_kunjungan', $kunj_data, array('no_registrasi' => $_POST['no_registrasi'], 'no_kunjungan' => $_POST['no_kunjungan'] ) );
        $this->logs->save('tc_kunjungan', $_POST['no_kunjungan'], 'update tc_kunjungan Modul Pelayanan', json_encode($kunj_data),'no_kunjungan');

        /*pl_tc_poli*/
        $poli_data = array('status_batal' => 1, 'no_induk' => $this->session->userdata('user')->user_id, 'created_by' => $this->session->userdata('user')->fullname );
        $this->db->update('pl_tc_poli', $poli_data, array('no_kunjungan' => $_POST['no_kunjungan']) );
        $this->logs->save('pl_tc_poli', $_POST['no_kunjungan'], 'update pl_tc_poli Modul Pelayanan', json_encode($poli_data),'no_kunjungan');

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

    public function rollback()
    {   
        $this->db->trans_begin();  

        /*update tc_registrasi*/
        $reg_data = array('tgl_jam_keluar' => NULL, 'kode_bagian_keluar' => NULL, 'status_batal' => NULL );
        $this->db->update('tc_registrasi', $reg_data, array('no_registrasi' => $_POST['no_registrasi'] ) );
        $this->logs->save('tc_registrasi', $_POST['no_registrasi'], 'update tc_registrasi Modul Pelayanan', json_encode($reg_data),'no_registrasi');


        /*tc_kunjungan*/
        $kunj_data = array('tgl_keluar' => NULL, 'status_keluar' => NULL, 'status_batal' => NULL );
        $this->db->update('tc_kunjungan', $kunj_data, array('no_registrasi' => $_POST['no_registrasi'], 'no_kunjungan' => $_POST['no_kunjungan'] ) );
        $this->logs->save('tc_kunjungan', $_POST['no_kunjungan'], 'update tc_kunjungan Modul Pelayanan', json_encode($kunj_data),'no_kunjungan');

        /*pl_tc_poli*/
        $poli_data = array('tgl_keluar_poli' => NULL, 'status_periksa' => NULL, 'status_batal' => NULL );
        $this->db->update('pl_tc_poli', $poli_data, array('no_kunjungan' => $_POST['no_kunjungan']) );
        $this->logs->save('pl_tc_poli', $_POST['no_kunjungan'], 'update pl_tc_poli Modul Pelayanan', json_encode($poli_data),'no_kunjungan');

        /*tc_trans_pelayanan*/
        $trans_data = array('status_selesai' => 2, 'status_nk' => NULL, 'kode_tc_trans_kasir' => NULL );
        $this->db->update('tc_trans_pelayanan', $trans_data, array('no_kunjungan' => $_POST['no_kunjungan'], 'no_registrasi' => $_POST['no_registrasi'] ) );


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


}

/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
