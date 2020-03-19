<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Pl_pelayanan_pm extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'pelayanan/Pl_pelayanan_pm');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('Pl_pelayanan_pm_model', 'Pl_pelayanan_pm');
        $this->load->model('Pl_pelayanan_model', 'Pl_pelayanan');
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
        $type = $_GET['type_tujuan'];
        $step = (isset($_GET['step']))?$_GET['step']:'';
        $nama_bagian = $this->db->get_where('mt_bagian',array('kode_bagian' => $type))->row();
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show(),
            'step' => $step,
            'bag_tujuan' => $type,
            'nama_bag' => $nama_bagian->nama_bagian
        );

        if($type=='050301'){
            $view = 'index_without_wizard';
        }else{
            $view = 'index';
        }

        $this->load->view('Pl_pelayanan_pm/'.$view.'', $data);
    }

    public function form($no_kunjungan, $kode_penunjang='',$status)
    {
         /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Pl_pelayanan_pm/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$kode_penunjang);

        /*get value by id*/
        $data['value'] = $this->Pl_pelayanan_pm->get_by_id($kode_penunjang);
        $data['riwayat'] = $this->Pl_pelayanan_pm->get_riwayat_pasien_by_id($no_kunjungan);
        $data['transaksi'] = $this->Pl_pelayanan_pm->get_transaksi_pasien_by_id($no_kunjungan);
        /*variable*/
        $bag = substr($data['value']->kode_bagian_asal, 1, 1);
        $data['type_asal'] = ($bag==3)?'RI':'RJ';
        $data['no_mr'] = $data['value']->no_mr;
        $data['id'] = $kode_penunjang;
        $data['kode_klas'] = $data['value']->kode_klas;
        $data['kode_profit'] = 2000;
        $data['no_kunjungan'] = $no_kunjungan;
        $data['status'] = $status;
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Pl_pelayanan_pm/form', $data);
    }

    public function tindakan($id='', $no_kunjungan='')
    {
         /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Pl_pelayanan_pm/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*get value by id*/
        $data['value'] = $this->Pl_pelayanan_pm->get_by_id($id);
        /*mr*/
        $data['no_mr'] = $data['value']->no_mr;
        $data['no_kunjungan'] = $no_kunjungan;
        $data['kode_penunjang'] = $id;
        $data['sess_kode_bag'] = ( $data['value']->kode_bagian_tujuan)? $data['value']->kode_bagian_tujuan:0;
        $data['type']='PM';
        $data['status_pulang'] = ($data['value']->status_daftar>=1)?1:0;
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Pl_pelayanan/form_tindakan', $data);
    }

    public function form_isi_hasil($no_kunjungan, $kode_bag_tujuan, $kode_penunjang='')
    {
         /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Pl_pelayanan_pm/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$kode_penunjang);

        $no_mr = $_GET['mr'];

        $data['pasien'] = $this->Reg_pasien->get_by_mr($no_mr);

        $userDob = $data['pasien']->tgl_lhr;
 
        //Create a DateTime object using the user's date of birth.
        $dob = new DateTime($userDob);
     
        //We need to compare the user's date of birth with today's date.
        $now = new DateTime();

        //Calculate the time difference between the two dates.
        $difference = $now->diff($dob);

        //Get the difference in years, as we are looking for the user's age.
        $umur_tahun = $difference->format('%y');
        $umur_bulan = $difference->format('%m') + 12 * $difference->format('%y');
        $umur_hari = $difference->d;
        //echo $umur_tahun.'-'.$umur_bulan.'-'.$umur_hari.'-'.$umur_jam;die;

        $mktime_tahun=31622400 * $umur_tahun;
        $mktime_bulan=2678400 * $umur_bulan;
        $mktime_hari=86400 * $umur_hari;
        $mktime_jam=0;

        $mktimenya=$mktime_tahun + $mktime_bulan + $mktime_hari + $mktime_jam;
        
        /*get value by id*/
        $data['mktime'] = $mktimenya;
        // $list =  (isset($_GET['is_edit']) AND $_GET['is_edit']!='')?$this->Pl_pelayanan_pm->get_data_hasil_pasien_pm($kode_penunjang,$kode_bag_tujuan):$this->Pl_pelayanan_pm->get_datatables_hasil_pm($kode_penunjang,$kode_bag_tujuan,$mktimenya);
        if((!isset($_GET['is_mcu'])) AND (isset($_GET['is_edit']) AND $_GET['is_edit']!='')){
            $list = $this->Pl_pelayanan_pm->get_data_hasil_pasien_pm($kode_penunjang,$kode_bag_tujuan);
            //echo 'hello i m here'; die;
        }else if((isset($_GET['is_mcu']) AND $_GET['is_mcu']==1)){
            $list = $this->Pl_pelayanan_pm->get_data_hasil_pasien_pm_mcu($kode_penunjang,$kode_bag_tujuan);
        }else if((isset($_GET['is_mcu']) AND $_GET['is_mcu']==2)){
            $list = $this->Pl_pelayanan_pm->get_hasil_pm_mcu($kode_penunjang,$kode_bag_tujuan,$mktimenya);
        }else{
            $list = $this->Pl_pelayanan_pm->get_datatables_hasil_pm($kode_penunjang,$kode_bag_tujuan,$mktimenya);
        }

        $data['list'] = $list;
        // echo '<pre>';print_r($this->db->last_query());die;
        if($kode_bag_tujuan=='050201'){
            $data['bpako'] = $this->Pl_pelayanan_pm->get_bpako($kode_penunjang);
            $view = 'form_isi_hasil_rad';
        }else if($kode_bag_tujuan=='050101'){
            $view = 'form_isi_hasil_lab';
        }

        /*variable*/
        $data['id'] = $kode_penunjang;
        $data['kode_profit'] = 2000;
        $data['no_kunjungan'] = $no_kunjungan;
        $data['no_mr'] = $no_mr;
        $penunjang = $this->Pl_pelayanan_pm->get_by_id($kode_penunjang);
        $data['no_registrasi'] = $penunjang->no_registrasi;
        $data['catatan_hasil'] = $penunjang->catatan_hasil;
        if(isset($_GET['is_edit']) AND $_GET['is_edit']!=''){
            $data['is_edit'] = $_GET['is_edit'];
        }
        if(isset($_GET['is_mcu']) AND $_GET['is_mcu']!=''){
            $data['is_mcu'] = $_GET['is_mcu'];
        }
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/

        
        $this->load->view('Pl_pelayanan_pm/'.$view.'', $data);
        
    }

    public function periksa_pm()
    {
         /*update status daftar */

        $kode_penunjang = $this->input->post('kode_penunjang');

        $this->Pl_pelayanan_pm->update('pm_tc_penunjang',array('status_daftar' => 2,'tgl_periksa' => date('Y-m-d H:i:s')), array('kode_penunjang' => $kode_penunjang));

        echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan'));
        
    }

    public function get_data()
    {
        /*akan di filter berdasarkan pasien pada klinik masing2*/
        /*get data from model*/
        $list = $this->Pl_pelayanan_pm->get_datatables();
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $link = 'billing/Billing';
            $str_type = 'RJ';
            
            $bag = substr($row_list->kode_bagian_asal, 1, 1);
            
            if($row_list->status_daftar==0 || $row_list->status_daftar==NULL){
                $status_pasien = 'belum_ditindak';
                $rollback_btn ='';
                $charge_slip = '';
            }else if($row_list->status_daftar==1){
                
                if($bag==5){
                    $status_pasien = ($row_list->status_selesai!=3)?'belum_bayar':'belum_diperiksa';
                    $rollback_btn = ($row_list->status_selesai!=3)?'<li><a href="#" onclick="rollback('.$row_list->kode_penunjang.')">Rollback</a></li>':'';
                }else if($bag==1 && $row_list->kode_bagian_asal !='010901' && $row_list->kode_bagian_asal != '012701' && $row_list->kode_kelompok != 3){
                    $status_pasien = ($row_list->status_selesai!=3)?'belum_bayar':'belum_diperiksa';
                    $rollback_btn = ($row_list->status_selesai!=3)?'<li><a href="#" onclick="rollback('.$row_list->kode_penunjang.')">Rollback</a></li>':'';
                }else{
                    $status_pasien = 'belum_diperiksa';
                    $rollback_btn = '<li><a href="#" onclick="rollback('.$row_list->kode_penunjang.')">Rollback</a></li>';
                }

                $charge_slip = '<li><a href="#" onclick="cetak_slip('.$row_list->kode_penunjang.')">Cetak Slip</a></li>';

            }else  if($row_list->status_daftar==2){
                $status_pasien = 'belum_isi_hasil';

                $transaksi = $this->Pl_pelayanan_pm->get_transaksi_pasien_by_id($row_list->no_kunjungan);

                $rollback_btn = ($transaksi!=0)?'<li><a href="#" onclick="rollback('.$row_list->kode_penunjang.')">Rollback</a></li>':'';

                $charge_slip = '<li><a href="#" onclick="cetak_slip('.$row_list->kode_penunjang.')">Cetak Slip</a></li>';
            }
            
            $row[] = $row_list->no_registrasi;
            $row[] = $str_type;
            $row[] = '';
            $row[] = '<div class="center"><div class="btn-group">
                        <button data-toggle="dropdown" class="btn btn-primary btn-xs dropdown-toggle">
                            <span class="ace-icon fa fa-caret-down icon-on-right"></span>
                        </button>
                        <ul class="dropdown-menu dropdown-inverse">
                            <li><a href="#" onclick="show_modal('."'registration/reg_pasien/view_detail_resume_medis/".$row_list->no_registrasi."'".', '."'RESUME MEDIS'".')">Selengkapnya</a></li>
                            '.$charge_slip.'
                            '.$rollback_btn.' 
                        </ul>
                    </div></div>';


            $form  = '<div class="center"><a href="#" onclick="getMenu('."'pelayanan/Pl_pelayanan_pm/form/".$row_list->no_kunjungan."/".$row_list->kode_penunjang."/".$status_pasien."'".')">'.$row_list->no_kunjungan.'</a></div>';
            $row[] = '<div class="center">'.$form.'</div>';
            $row[] = '<div class="center">'.$row_list->no_mr.'</div>';
            $row[] = strtoupper($row_list->nama_pasien);
            $row[] = '<div class="center">'.$row_list->no_antrian.'</div>';
            $row[] = ($row_list->nama_perusahaan)?$row_list->nama_perusahaan:$row_list->nama_kelompok;
            $row[] = $this->tanggal->formatDateTime($row_list->tgl_masuk);
			$row[] = ($row_list->status_cito==1)?'Cito':'Biasa';
            $row[] = $row_list->nama_bagian;

            $bag = substr($row_list->kode_bagian_asal, 1, 1);

            if($status_pasien=='belum_ditindak'){

                $status = '<label class="label label-warning"><i class="fa fa-info-circle"></i> Belum ditindak</label>';

            }else if($status_pasien=='belum_bayar'){

                $status = '<label class="label label-danger">Belum bayar</label>';
               
            }else if($status_pasien=='belum_isi_hasil'){

                $status = '<label class="label label-success">Belum isi hasil</label>';

            }else{

                if($_GET['sess_kode_bagian']!='050301'){
                    $status = '<a href="#" class="btn btn-xs btn-primary" onclick="periksa('.$row_list->kode_penunjang.')">Periksa</a>';
                }else{
                    
                    $status = ($transaksi==0)?'<label class="label label-info">Lunas</label>':'<label class="label label-success">Selesai</label>';

                }
                
            }

            $row[] = '<div class="center">'.$status.'</div>';
           
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Pl_pelayanan_pm->count_all(),
                        "recordsFiltered" => $this->Pl_pelayanan_pm->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function get_hasil_pm()
    {
        /*get data from model*/
        $list_group = $this->Pl_pelayanan_pm->get_group_hasil_pm();
        $list =  $this->Pl_pelayanan_pm->get_datatables_hasil_pm();
        $data = array();
        $no = $_POST['start'];
        foreach ($list_group as $row_list_group) {
            $no++;
            $row = array();
           

            foreach ($list as $row_list) {
                # code...
                if($_GET['jk']=='L'){
                    $nilai_std = $row_list->standar_hasil_pria;
                }else{
                    $nilai_std = $row_list->standar_hasil_wanita;
                }

                if($row_list_group->nama_tindakan==$row_list->nama_tindakan){
                    $row[] = $row_list->nama_pemeriksaan;
                    $row[] = $row_list->detail_item_1;
                    $row[] = $row_list->detail_item_2;
                    $row[] = $nilai_std.' '.$row_list->satuan;
                    $row[] = '';
                    $row[] = '';
                }
            }
                       
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Pl_pelayanan_pm->count_all_tindakan_riwayat_diagnosa(),
                        "recordsFiltered" => $this->Pl_pelayanan_pm->count_filtered_tindakan_riwayat_diagnosa(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function get_bpako()
    {
        /*get data from model*/
        $kode_tarif = (isset($_GET['kode_tarif']))?$_GET['kode_tarif']:'';
        $list = $this->Pl_pelayanan_pm->get_bpako($_GET['id'],$kode_tarif);
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
                                
            $row[] = '<a href="#" class="btn btn-xs btn-danger" onclick="delete_obalkes('.$row_list->id_pm_tc_obalkes.')"><i class="fa fa-times-circle"></i></a>';
            $row[] = strtoupper($row_list->nama_brg);
            $row[] = '<div class="center">'.$row_list->volume.'</div>';
                     
            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => count($data),
                        "recordsFiltered" => count($data),
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
        $this->load->view('Pl_pelayanan_pm/form_end_visit', $data);
    }

    public function find_data()
    {   
        $output = array( "data" => http_build_query($_POST) . "\n" );
        echo json_encode($output);
    }

    public function process_isi_hasil(){

        // echo '<pre>';print_r($_POST);die;
        // form validation
        $this->form_validation->set_rules('kode_penunjang', 'No MR', 'trim');
               
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

            /*Update pm_tc_penunjang */
            $pm_tc_penunjang = array(
                'tgl_isihasil' => date('Y-m-d H:i:s'),
                'petugas_isihasil' => $this->session->userdata('user')->user_id,
                'status_isihasil' => 1,
                'catatan_hasil' => isset($_POST['catatan_hasil'])?$this->input->post('catatan_hasil'):''
            );

            $this->Pl_pelayanan_pm->update('pm_tc_penunjang',$pm_tc_penunjang, array('kode_penunjang' => $this->input->post('kode_penunjang')));

            /*insert pm_tc_hasilpenunjang*/
            foreach($_POST['kode_mt_hasilpm'] as $key=>$row_dt){

                $kode_mt_hasilpm = $row_dt;
                $kode_tc_hasilpenunjang = $this->master->get_max_number('pm_tc_hasilpenunjang', 'kode_tc_hasilpenunjang');
                $kode_trans_pelayanan = $_POST['kode_trans_pelayanan'][$kode_mt_hasilpm];
                $hasil = $_POST['hasil_pm'][$kode_mt_hasilpm];
                $keterangan = $_POST['keterangan_pm'][$kode_mt_hasilpm];

                $dataexc = array(
                    'kode_mt_hasilpm' =>  $kode_mt_hasilpm,
                    'hasil' => $hasil,
                    'keterangan' => $keterangan,
                );

                //print_r($_POST['kode_tc_hasilpenunjang'][$kode_mt_hasilpm]);die;

                if($kode_trans_pelayanan!=''){
                    $cek_mcu = $this->db->get_where('tc_trans_pelayanan_paket_mcu',array('kode_trans_pelayanan_paket_mcu' => $kode_trans_pelayanan))->row();
                    if(isset($cek_mcu) AND $cek_mcu->kode_bagian_asal=='010901'){
                        $dataexc["flag_mcu"] = 1; 
                    }
                }

                if(isset($_POST['kode_tc_hasilpenunjang'][$kode_mt_hasilpm]) AND $_POST['kode_tc_hasilpenunjang'][$kode_mt_hasilpm]!=0){
                    $this->Pl_pelayanan_pm->update('pm_tc_hasilpenunjang', $dataexc, array('kode_tc_hasilpenunjang' => $_POST['kode_tc_hasilpenunjang'][$kode_mt_hasilpm] ) );
                    $this->db->trans_commit();

                }else{
                    $dataexc["kode_tc_hasilpenunjang"] = $kode_tc_hasilpenunjang; 
                    $dataexc["kode_trans_pelayanan"] = $kode_trans_pelayanan; 
                    // echo '<pre>';print_r($dataexc);die;
                    $this->Pl_pelayanan_pm->save('pm_tc_hasilpenunjang', $dataexc);
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

    public function process_add_obalkes()
    {
    
        $this->db->trans_begin();        

        $id_pm_tc_obalkes = $this->master->get_max_number('pm_tc_obalkes', 'id_pm_tc_obalkes');

        $pm_tc_obatalkes = array(
            'id_pm_tc_obalkes' => $id_pm_tc_obalkes,
            'kode_penunjang' => $this->input->post('kode_penunjang'),
            'kode_brg' => $this->input->post('kode_brg'),
            'kode_tarif' => $this->input->post('kode_tarif'),
            'volume' => $this->input->post('jml'),
            'kode_bagian' => $this->input->post('kode_bagian'),
            'petugas' => $this->session->userdata('user')->user_id,
            // 'harga_jual' => $harga_jual
        );

        $this->Pl_pelayanan_pm->save('pm_tc_obalkes', $pm_tc_obatalkes);

        $this->stok_barang->stock_process($this->input->post('kode_brg'),$this->input->post('jml'),$this->input->post('kode_bagian'),6,'','reduce');
        

        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
        }
        else
        {
            $this->db->trans_commit();
            echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'type_pelayanan' => 'Pasien Selesai', 'data' => $pm_tc_obatalkes));
        }

    }

    public function processPelayananSelesai(){

        // form validation
        $this->form_validation->set_rules('noMrHidden', 'Pasien', 'trim|required');        
        $this->form_validation->set_rules('kode_penunjang', 'Kode Penunjang', 'trim');        
            
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

            $kode_penunjang = $this->regex->_genRegex($this->input->post('kode_penunjang'),'RGXINT');
            $no_mr = $this->form_validation->set_value('noMrHidden');
            
            /*update pm_tc_penunjang*/
            $pm_tc_penunjang = array('status_daftar' => 1);
            $this->Pl_pelayanan_pm->update('pm_tc_penunjang', $pm_tc_penunjang, array('kode_penunjang' => $kode_penunjang ) );

            
            if( ($this->input->post('kode_bagian_asal')!=$this->input->post('kode_bagian')) OR ($this->input->post('kode_bagian')=='050301') ){
                
                $status_keluar = ($this->input->post('kode_bagian')=='050301')?3:5;
                $this->daftar_pasien->pulangkan_pasien($this->input->post('no_kunjungan'),$status_keluar,'PM');

            }else{
                $tc_trans_pelayanan["status_selesai"] = 2;

                /*update tc_trans_pelayanan*/
                $this->Pl_pelayanan_pm->update('tc_trans_pelayanan', $tc_trans_pelayanan, array('kode_penunjang' => $kode_penunjang ) );
            }
        
            /*input bpako radiologi */

            $list_tindakan1 = $this->Pl_pelayanan_pm->get_tindakan_by_tc_trans_pelayanan($kode_penunjang,3);
                
            foreach ($list_tindakan1 as $value) {
                $kode_tarif = $value->kode_tarif;
                $harga_jual = $value->bill_rs;
                
                $list_pm_mt_bpako = $this->Pl_pelayanan_pm->get_pm_mt_bpako($kode_tarif);
                //print_r($list_pm_mt_bpako);die;

                if(!empty($list_pm_mt_bpako)){

                    foreach ($list_pm_mt_bpako as $vals) {
                        
                        $kode_brg = $vals->kode_brg;
                        $volume = (int)$vals->volume;                      
                        
                        $id_pm_tc_obalkes = $this->master->get_max_number('pm_tc_obalkes', 'id_pm_tc_obalkes');

                        $pm_tc_obatalkes = array(
                            'id_pm_tc_obalkes' => $id_pm_tc_obalkes,
                            'kode_penunjang' => $kode_penunjang,
                            'kode_brg' => $kode_brg,
                            'kode_tarif' => $kode_tarif,
                            'volume' => $volume,
                            'kode_bagian' => $this->input->post('kode_bagian'),
                            'petugas' => $this->session->userdata('user')->user_id,
                            // 'harga_jual' => $harga_jual
                        );

                        $this->Pl_pelayanan_pm->save('pm_tc_obalkes', $pm_tc_obatalkes);

                        $this->stok_barang->stock_process($kode_brg,$volume,$this->input->post('kode_bagian'),6,'','reduce');
                        
                        
                        $this->db->trans_commit();
                    }

                }

            }
            
            $list_tindakan2 = $this->Pl_pelayanan_pm->get_tindakan_by_tc_trans_pelayanan($kode_penunjang,9);

            if(!empty($list_tindakan2)){
                foreach ($list_tindakan2 as $value) {
                
                    $kode_brg = $value->kode_barang;
                    $jumlah = (int)$value->jumlah;
                    $harga_jual = $value->bill_rs;
    
                    if(isset($kode_brg) OR $kode_brg!=NULL){
        
                        $id_pm_tc_obalkes = $this->master->get_max_number('pm_tc_obalkes', 'id_pm_tc_obalkes');
    
                        $pm_tc_obatalkes = array(
                            'id_pm_tc_obalkes' => $id_pm_tc_obalkes,
                            'kode_penunjang' => $kode_penunjang,
                            'kode_brg' => $kode_brg,
                            'volume' => $jumlah,
                            'kode_bagian' => $this->input->post('kode_bagian'),
                            'petugas' => $this->session->userdata('user')->user_id,
                            // 'harga_jual' => $harga_jual
                        );
    
                        $this->Pl_pelayanan_pm->save('pm_tc_obalkes', $pm_tc_obatalkes);
    
                        $this->stok_barang->stock_process($kode_brg,$jumlah,$this->input->post('kode_bagian'),6,'','reduce');
    
                        $this->db->trans_commit();
                    }
    
                }
            } 

            /*proses tanpa input hasil fisioterapi */

            if($this->input->post('kode_bagian')=='050301'){

                $tindakan = $this->Pl_pelayanan_pm->get_tindakan_by_tc_trans_pelayanan($kode_penunjang);

                foreach ($tindakan as $value) {

                    $kode_tc_hasilpenunjang = $this->master->get_max_number('pm_tc_hasilpenunjang', 'kode_tc_hasilpenunjang');

                    $tc_hasilpenunjang = array(
                        'kode_tc_hasilpenunjang' => $kode_tc_hasilpenunjang,
                        'kode_trans_pelayanan' => $value->kode_trans_pelayanan,
                        'kode_mt_hasilpm' => '5030101011',
                        'hasil' => '0',
                        'keterangan' => 'Tanpa Input Hasil',
                    );
				
                    $this->Pl_pelayanan_pm->save('pm_tc_hasilpenunjang', $tc_hasilpenunjang);

                    $this->db->trans_commit();
                
                }

                $pm_tc_penunjang_fisio = array(
                    'tgl_isihasil' => date('Y-m-d H:i:s'),
                    'petugas_isihasil' => $this->session->userdata('user')->user_id,
                    'catatan_hasil' => 'Tanpa Input Hasil',
                    'status_isihasil' => 0,
                    'status_daftar' => 1
                );
               
                $this->Pl_pelayanan_pm->update('pm_tc_penunjang', $pm_tc_penunjang_fisio, array('kode_penunjang' => $kode_penunjang ) );
                
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

    public function delete()
    {
        $id=$this->input->post('ID')?$this->input->post('ID',TRUE):null;
        $brg=$this->db->get_where('pm_tc_obalkes',array('id_pm_tc_obalkes' => $id))->row();
        $kode_brg = $brg->kode_brg;
        $volume = $brg->volume;
        if($id!=null){
            if($this->Pl_pelayanan_pm->delete_by_id('pm_tc_obalkes','id_pm_tc_obalkes',$id)){

                $this->stok_barang->stock_process($kode_brg,$volume,$this->input->post('kode_bagian'),8,'','restore');

                echo json_encode(array('status' => 200, 'message' => 'Proses Hapus Data Berhasil Dilakukan', 'data' => $brg));
            }else{
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Hapus Data Gagal Dilakukan'));
            }
        }else{
            echo json_encode(array('status' => 301, 'message' => 'Tidak ada item yang dipilih'));
        }
        
    }

    public function rollback()
    {   
        //print_r($_POST);die;
        $this->db->trans_begin();  

        $kode_penunjang = $this->regex->_genRegex($this->input->post('kode_penunjang'),'RGXINT');
             
        /*update pm_tc_penunjang*/
        $pm_tc_penunjang = array('status_daftar' => 0);
        $this->Pl_pelayanan_pm->update('pm_tc_penunjang', $pm_tc_penunjang, array('kode_penunjang' => $kode_penunjang ) );
        /*save logs*/
        //$this->logs->save('pl_tc_poli', $no_kunjungan, 'update pl_tc_poli Modul Pelayanan', json_encode($arrPlTcPoli),'no_kunjungan');

         /*update tc_trans_pelayanan*/
         $tc_trans_pelayanan = array('status_selesai' => 0);
         $this->Pl_pelayanan_pm->update('tc_trans_pelayanan', $tc_trans_pelayanan, array('kode_penunjang' => $kode_penunjang ) );
         /*save logs*/
         //$this->logs->save('pl_tc_poli', $no_kunjungan, 'update pl_tc_poli Modul Pelayanan', json_encode($arrPlTcPoli),'no_kunjungan');

         if(isset($_POST['kode_bagian']) AND $_POST['kode_bagian']=='050301'){
             
            $kunjungan = $this->Pl_pelayanan_pm->get_by_id($kode_penunjang);
            $kunj_data = array('tgl_keluar' => NULL, 'status_keluar' => NULL, 'status_batal' => NULL );
            $this->db->update('tc_kunjungan', $kunj_data, array('no_registrasi' => $kunjungan->no_registrasi, 'no_kunjungan' => $kunjungan->no_kunjungan ) );
            $this->logs->save('tc_kunjungan', $kunjungan->no_kunjungan, 'update tc_kunjungan Modul Pelayanan', json_encode($kunj_data),'no_kunjungan');

            if($_POST['flag']=='submited'){

                /*delete ak_tc_transaksi_det*/
                $this->Pl_pelayanan->delete_ak_tc_transaksi_det($kunjungan->no_kunjungan);
                /*delete ak_tc_transaksi*/
                $this->Pl_pelayanan->delete_ak_tc_transaksi($kunjungan->no_kunjungan);
                /*delete transaksi_kasir*/
                $this->Pl_pelayanan->delete_transaksi_kasir($kunjungan->no_kunjungan);

            }

            /*tc_trans_pelayanan*/
            $trans_data = array('status_selesai' => 2, 'status_nk' => NULL, 'kode_tc_trans_kasir' => NULL );
            $this->db->update('tc_trans_pelayanan', $trans_data, array('no_kunjungan' => $kunjungan->no_kunjungan, 'no_registrasi' => $kunjungan->no_registrasi ) );
 
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

    public function slip(){
        
        $kode_penunjang = $_GET['kode_penunjang'];
        $flag = isset($_GET['flag'])?$_GET['flag']:'';
        
        $data['value'] = $this->Pl_pelayanan_pm->get_pemeriksaan($kode_penunjang,$flag);

        foreach ($data['value'] as $value) {
            if(isset($value->nama_pasien))$data['nama'] = $value->nama_pasien;
            if(isset($value->no_registrasi))$data['no_registrasi'] = $value->no_registrasi;
            if(isset($value->no_mr))$data['no_mr'] = $value->no_mr;
            if(isset($value->dokter_1))$data['dokter_1'] = $value->dokter_1;
            if(isset($value->dokter_2))$data['dokter_2'] = $value->dokter_2;
            if(isset($value->bagian_asal))$data['bagian_asal'] = $value->bagian_asal;
        }
 
        $this->load->view('Pl_pelayanan_pm/charge_slip', $data);

    }

    public function process_edit_dokter()
    {
        // print_r($_POST);
        $this->db->trans_begin();  
      
        $kode_penunjang = $this->regex->_genRegex($this->input->post('kode_penunjang_dr'),'RGXINT');
        $kode_trans_pelayanan = $this->regex->_genRegex($this->input->post('kode_trans_pelayanan'),'RGXINT');     
      
        $dataexec = array(
            'kode_dokter1' => $this->regex->_genRegex($this->input->post('kode_dokter1'),'RGXINT'),
            'kode_dokter2' => (isset($_POST['kode_dokter2']) AND $_POST['kode_dokter2']!='')?$_POST['kode_dokter2']:0,
        );
        
        /*update trans pelayanan */
        $this->Pl_pelayanan_pm->update('tc_trans_pelayanan', $dataexec, array('kode_penunjang' => $kode_penunjang, 'kode_trans_pelayanan' =>  $kode_trans_pelayanan) );

        $data_pm = $this->Pl_pelayanan_pm->get_by_id($kode_penunjang);

        //print_r($data_pm);die;

        /*update tc_kunjungan */
        $this->Pl_pelayanan_pm->update('tc_kunjungan', array('kode_dokter' =>  $this->regex->_genRegex($this->input->post('kode_dokter1'),'RGXINT')), array('no_kunjungan' => $data_pm->no_kunjungan) );

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
