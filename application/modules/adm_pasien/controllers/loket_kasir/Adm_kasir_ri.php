<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Adm_kasir_ri extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'adm_pasien/loket_kasir/Adm_kasir_ri');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('loket_kasir/Adm_kasir_ri_model', 'Adm_kasir_ri');
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
        $is_icu = (isset($_GET['is_icu']))?$_GET['is_icu']:'N';
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show(),
            'is_icu' => $is_icu,
        );

        $this->load->view('loket_kasir/Adm_kasir_ri/index', $data);
    }

    public function form($kode_ri='', $no_kunjungan)
    {
         /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Adm_kasir_ri/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$kode_ri);
        /*get value by id*/
        $data['value'] = $this->Adm_kasir_ri->get_by_id($kode_ri);
        //echo '<pre>';print_r($data['value']);die;
        $data['riwayat'] = $this->Adm_kasir_ri->get_riwayat_pasien_by_id($no_kunjungan);
        $data['transaksi'] = $this->Adm_kasir_ri->get_transaksi_pasien_by_id($no_kunjungan);
        $data['ruangan'] = $this->Adm_kasir_ri->get_ruangan_by_id($data['value']->kode_ruangan);
        /*variable*/
        $data['no_mr'] = $data['value']->no_mr;
        $data['id'] = $kode_ri;
        $data['kode_klas'] = $data['value']->kelas_pas;
        $data['klas_titipan'] = ($data['value']->kelas_titipan!=NULL)?$data['value']->kelas_titipan:0;
        $data['kode_profit'] = 2000;
        $data['no_kunjungan'] = $no_kunjungan;
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('loket_kasir/Adm_kasir_ri/form', $data);
    }

    public function tindakan($id='', $no_kunjungan='')
    {
         /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Adm_kasir_ri/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*get value by id*/
        $data['value'] = $this->Adm_kasir_ri->get_by_id($id);
        /*mr*/
        $data['no_mr'] = $data['value']->no_mr;
        $data['no_kunjungan'] = $no_kunjungan;
        $data['kode_ri'] = $id;
        $data['sess_kode_bag'] = ( $data['value']->bag_pas)? $data['value']->bag_pas:0;
        $data['type']='Ranap';
        $data['status_pulang'] = $data['value']->status_pulang;
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Pl_pelayanan/form_tindakan', $data);
    }

    public function get_data()
    {
        /*akan di filter berdasarkan pasien pada klinik masing2*/
        /*get data from model*/
        $list = $this->Adm_kasir_ri->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $link = 'billing/Billing';
            $str_type = 'RI';
            $cek_trans = $this->Adm_kasir_ri->cek_trans_pelayanan($row_list->no_registrasi);

            $rollback_btn = ($cek_trans>0 AND $row_list->status_pulang!= 0 || NULL)?'<li><a href="#" onclick="rollback('.$row_list->no_registrasi.','.$row_list->no_kunjungan.')">Rollback</a></li>':'';

            /*color of type Ruangan RI*/
            /*LB*/
            if ( in_array($row_list->bag_pas, array('030101','031401','031301','030801','030401','031601') ) ) {
                $color = 'red';
            /*LA*/
            }elseif( in_array($row_list->bag_pas,  array('030701','030301','030601','030201') )){
                $color = 'green';
            /*VK Ruang Bersalin*/
            }elseif( in_array($row_list->bag_pas,  array('031201','031701','030501') )){
                $color = 'blue';
            }else{
                $color = 'black';
            }
            $row[] = $row_list->no_registrasi;
            $row[] = $str_type;
            $row[] = '';
            $row[] = '<div class="center"><div class="btn-group">
                        <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">
                            <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-inverse">
                            '.$rollback_btn.' 
                            <li><a href="#" onclick="show_modal('."'registration/reg_pasien/view_detail_resume_medis/".$row_list->no_registrasi."'".', '."'RESUME MEDIS'".')">Selengkapnya</a></li>
                        </ul>
                    </div></div>';
            $row[] = '<div class="center">'.$no.'</div>';
            $row[] = '<div class="center"><a href="#" onclick="getMenu('."'adm_pasien/loket_kasir/Adm_kasir_ri/form/".$row_list->kode_ri."/".$row_list->no_kunjungan."'".')">'.$row_list->no_kunjungan.'</a></div>';
            $row[] = '<div class="center">'.$row_list->no_mr.'</div>';
            $row[] = '<span style="color:'.$color.'"><b>'.strtoupper($row_list->nama_pasien).'</b></span>';
            $row[] = $row_list->nama_bagian;
            $row[] = ($row_list->nama_perusahaan)?$row_list->nama_perusahaan:$row_list->nama_kelompok;
            $row[] = $row_list->klas;
            $row[] = ($row_list->klas_titip)?$row_list->klas_titip:$row_list->klas;
            $row[] = number_format($row_list->tarif_inacbgs);
            $row[] = $this->tanggal->formatDateTime($row_list->tgl_masuk);
            $row[] = $row_list->nama_pegawai;

            if($cek_trans==0){
                $status_pulang = '<label class="label label-primary"><i class="fa fa-money"></i> Lunas </label>';
            }else{
                $status_pulang = ($row_list->status_pulang== 0 || NULL)?($row_list->pasien_titipan== 1)?'<label class="label label-yellow">Pasien Titipan</label>':'<label class="label label-danger">Masih dirawat</label>':'<label class="label label-success">Pulang</label>';
            }

            $row[] = '<div class="center">'.$status_pulang.'</div>';
           
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Adm_kasir_ri->count_all(),
                        "recordsFiltered" => $this->Adm_kasir_ri->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function form_end_visit()
    {
        $data = array(
            'no_mr' => isset($_GET['no_mr'])?$_GET['no_mr']:'',
            'kode_ri' => isset($_GET['id'])?$_GET['id']:'',
            'no_kunjungan' => isset($_GET['no_kunjungan'])?$_GET['no_kunjungan']:'',
            );
        /*load form view*/
        $this->load->view('loket_kasir/Adm_kasir_ri/form_end_visit', $data);
    }

    public function find_data()
    {   
        $output = array( "data" => http_build_query($_POST) . "\n" );
        echo json_encode($output);
    }

    public function processPelayananSelesai(){

        //print_r($_POST);die;
        // form validation
        $this->form_validation->set_rules('noMrHidden', 'Pasien', 'trim|required');       
        $this->form_validation->set_rules('no_registrasi', 'No Registrasi', 'trim|required');        
        $this->form_validation->set_rules('no_kunjungan', 'No Kunjungan', 'trim|required');        
        $this->form_validation->set_rules('kode_bagian_asal', 'Kode Bagian Asal', 'trim|required');        
        //$this->form_validation->set_rules('cara_keluar', 'Cara Keluar Pasien', 'trim|required');        
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
            $kode_ri = $this->regex->_genRegex($this->input->post('kode_ri'),'RGXINT');
            $no_mr = $this->form_validation->set_value('noMrHidden');

            /*cek kunjungan poli */
            $cek_poli = $this->Adm_kasir_ri->cek_poli_pulang($no_registrasi);
            if($cek_poli){
                echo json_encode(array('status' => 301, 'message' => 'Maaf pasien masih dalam antrian Poli !'));
                exit;
            }

            /*cek kunjungan vk */
            $cek_vk = $this->Adm_kasir_ri->cek_vk_pulang($kode_ri);
            if($cek_vk){
                echo json_encode(array('status' => 301, 'message' => 'Maaf pasien masih dalam antrian VK !'));
                exit;
            }

             /*cek kunjungan ok */
             $cek_ok = $this->Adm_kasir_ri->cek_ok_pulang($kode_ri);

             if($cek_ok){
                 echo json_encode(array('status' => 301, 'message' => 'Maaf pasien masih dalam antrian OK !'));
                 exit;
             }

            /*cek pesan resep*/
            $cek_resep = $this->Adm_kasir_ri->cek_resep_progress($no_kunjungan);

            /*jika sudah tidak ada resep yang belum diproses, maka lanjutkan*/
            if($cek_resep==0){

                /*proses utama pasien selesai*/

                /*cek pemeriksaan pm */
                $cek_pm = $this->Adm_kasir_ri->cek_pemeriksaan_pm($no_registrasi);

                //print_r($cek_pm);die;

                if($cek_pm){
                    foreach ($cek_pm as $value) {
                        # code...
                        $this->Adm_kasir_ri->delete_by_id('pm_tc_penunjang','kode_penunjang',$value->kode_penunjang);
                        $this->Adm_kasir_ri->delete_by_id('tc_trans_pelayanan','kode_trans_pelayanan',$value->kode_trans_pelayanan);
                    }
                }
    
                /*update mt_ruangan*/
                $this->Adm_kasir_ri->update('mt_ruangan', array('status' => NULL), array('kode_ruangan' => $this->regex->_genRegex($this->input->post('kode_ruangan'),'RGXINT') ) );
                /*save logs*/
                //$this->logs->save('pl_tc_poli', $no_kunjungan, 'update pl_tc_poli Modul Pelayanan', json_encode($arrPlTcPoli),'no_kunjungan');

                /*cek if meninggal */
                if ( $this->form_validation->set_value('pasca_pulang') == 'Meninggal' ){
                    $ket_keluar = 3;
                    $status_keluar=4;
                    $status_meninggal=1;

                    /*update mt_master_pasien */
                    $mt_master_pasien["status_meninggal"] = $status_meninggal;
                    $this->Adm_kasir_ri->update('mt_master_pasien', $mt_master_pasien, array('no_mr' => $no_mr ) );
                    /*save logs*/
                    $this->logs->save('mt_master_pasien', $no_mr, 'update mt_master_pasien modul pelayanan', json_encode($mt_master_pasien),'no_mr');
                }else{
                    $ket_keluar = 4;
                    $status_keluar=3;
                    $status_meninggal="";
                }

                /*update ri_tc_riwayat_kelas */
                $riwayat_kelas = array(
                    'tgl_pindah' => date('Y-m-d H:i:s'),
                    'ket_keluar' => $ket_keluar,
                );
                $this->Adm_kasir_ri->update('ri_tc_riwayat_kelas', $riwayat_kelas, array('kode_ri' => $kode_ri, 'tgl_pindah' => NULL ) );

                /*update ri_tc_rwatinap */
                $ri_tc_rawatinap = array(
                    'tgl_keluar' => date('Y-m-d H:i:s'),
                    'status_pulang' => 1,
                    'user_plg' => $this->session->userdata('user')->user_id,
                );
                $this->db->update('ri_tc_rawatinap', $ri_tc_rawatinap, array('kode_ri' => $kode_ri) );

                /*insert biaya administrasi */
                /*cek biaya farmasi*/
                $biaya_obat = $this->Adm_kasir_ri->cek_biaya_obat($no_registrasi); 
                if($biaya_obat){
                    $_bi_apoA = $biaya_obat->bi_apo;
		            $_bi_lainA = $biaya_obat->bi_lain;
                }else{
                    $_bi_apoA = 0;
		            $_bi_lainA = 0;
                }
                
                $biaya_obat_kredit = $this->Adm_kasir_ri->cek_biaya_obat_kredit($no_registrasi); 
                if($biaya_obat_kredit){
                    $_bi_apoB = $biaya_obat_kredit->bi_apo;
                    $_bi_lainB = $biaya_obat_kredit->bi_lain;
                }else{
                    $_bi_apoB = 0;
                    $_bi_lainB = 0;
                }
               
                $bi_apo = ( $_bi_apoA + $_bi_lainA ) - ( $_bi_apoB + $_bi_lainB ) ;
		        $billApo = $bi_apo;

                /*cek semua biaya by reg */
                $biaya_by_registrasi = $this->Adm_kasir_ri->cek_biaya_reg($no_registrasi); 
                $biaya_rs = $biaya_by_registrasi->biy_rs + $biaya_by_registrasi->biy_lain;
                $biaya_dr1 = $biaya_by_registrasi->biy_dr1;
                $biaya_dr2 = $biaya_by_registrasi->biy_dr2 + $biaya_by_registrasi->biy_dr3;

                $total_adm = ($biaya_rs + $biaya_dr1 + $biaya_dr2 + $billApo);
                $materai = ($total_adm > 5000000) ? 10000 : 0;
                
                $biy_adm = 0.06 * ($total_adm + $materai);

                // $biy_adm = 0.06 * ($biaya_rs + $biaya_dr1 + $biaya_dr2 + $billApo);

                /*save tc_trans_pelayanan */
                $kode_tc_trans_pelayanan = $this->master->get_max_number('tc_trans_pelayanan', 'kode_trans_pelayanan');
                $data_tc_trans_pelayanan = array(
                    'kode_trans_pelayanan' => $kode_tc_trans_pelayanan,
                    'no_kunjungan' => $no_kunjungan,
                    'no_registrasi' => $no_registrasi,
                    'no_mr' => $no_mr,
                    'nama_pasien_layan' => $this->input->post('nama_pasien_layan'),
                    'kode_perusahaan' => $this->regex->_genRegex($this->input->post('kode_perusahaan'),'RGXINT'),
                    'kode_kelompok' => $this->regex->_genRegex($this->input->post('kode_kelompok'),'RGXINT'),
                    'tgl_transaksi' => date('Y-m-d H:i:s'),
                    'jenis_tindakan' => 2,
                    'nama_tindakan' => 'Biaya Administrasi',
                    'bill_rs' =>  $biy_adm,
                    'jumlah' =>  1,
                    'kode_bagian' => $this->regex->_genRegex($this->input->post('kode_bagian'),'RGXQSL'),
                    'kode_bagian_asal' => $this->regex->_genRegex($this->input->post('kode_bagian'),'RGXQSL'),
                    'status_selesai' => 1,
                    'id_dd_user' => $this->session->userdata('user')->user_id,
                ); 
                $this->Adm_kasir_ri->save('tc_trans_pelayanan', $data_tc_trans_pelayanan);
                /*save logs tc_trans_pelayanan*/
                $this->logs->save('tc_trans_pelayanan', $kode_tc_trans_pelayanan, 'insert tc_trans_pelayanan modul pelayanan', json_encode($data_tc_trans_pelayanan),'kode_trans_pelayanan');


                /*update kunjungan by no_kunjungan */
                $this->daftar_pasien->pulangkan_pasien($no_kunjungan,$status_keluar);

                /*update kunjungan by no_registrasi */
                $kunjungan["tgl_keluar"] = date('Y-m-d H:i:s');
                $kunjungan["status_keluar"] = ($status_keluar=="")?3:$status_keluar;
                $this->db->update('tc_kunjungan', $kunjungan, array('no_registrasi' => $no_registrasi,'no_mr' => $no_mr,'tgl_keluar' => NULL) );
                /*save logs tc_kunjungan*/
                $this->logs->save('tc_kunjungan', $no_kunjungan, 'update tc_kunjungan modul pelayanan', json_encode($kunjungan),'no_kunjungan');

                /*update bagian_keluar */
                $this->Adm_kasir_ri->update('tc_registrasi', array('kode_bagian_keluar' => $this->input->post('kode_bagian') ), array('no_registrasi' => $no_registrasi ) );

               

            }else{
                echo json_encode(array('status' => 301, 'message' => 'Masih ada Resep yang belum selesai'));
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
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'type_pelayanan' => 'Pasien Selesai', 'total_biaya' => $total_adm, 'materai' => $materai));
            }

        
        }

    }

    public function rollback()
    {   
        $this->db->trans_begin();  

        /*tc_kunjungan*/
        $kunj_data = array('tgl_keluar' => NULL, 'status_keluar' => NULL, 'status_batal' => NULL );
        $this->db->update('tc_kunjungan', $kunj_data, array('no_registrasi' => $_POST['no_registrasi'], 'no_kunjungan' => $_POST['no_kunjungan'] ) );
        $this->logs->save('tc_kunjungan', $_POST['no_kunjungan'], 'update tc_kunjungan Modul Pelayanan', json_encode($kunj_data),'no_kunjungan');

        /*pl_tc_poli*/
        $data_ri = array('tgl_keluar' => NULL, 'status_pulang' => 0, 'user_plg' => NULL );
        $this->db->update('ri_tc_rawatinap', $data_ri, array('no_kunjungan' => $_POST['no_kunjungan']) );
        //$this->logs->save('ri_tc_rawatinap', $_POST['no_kunjungan'], 'update ri_tc_rawatinap Modul Pelayanan', json_encode($data_ri),'no_kunjungan');

        /*tc_trans_pelayanan*/
        $trans_data = array('status_selesai' => 2, 'status_nk' => NULL, 'kode_tc_trans_kasir' => NULL );
        $this->db->update('tc_trans_pelayanan', $trans_data, array('no_kunjungan' => $_POST['no_kunjungan'], 'no_registrasi' => $_POST['no_registrasi'] ) );
        $this->db->delete('tc_trans_pelayanan', array('no_kunjungan' => $_POST['no_kunjungan'], 'jenis_tindakan' => 2, 'nama_tindakan' => 'Biaya Administrasi'));


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
