<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Riwayat_kunjungan_pm extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'registration/Riwayat_kunjungan_pm');
        $this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            redirect(base_url().'login');exit;
        }
        /*load model*/
        $this->load->model('Riwayat_kunjungan_pm_model', 'Riwayat_kunjungan_pm');
        $this->load->model('pelayanan/Pl_pelayanan_model', 'Pl_pelayanan');
        $this->load->model('pelayanan/Pl_pelayanan_pm_model', 'Pl_pelayanan_pm');
        //$this->load->model('pelayanan/Pl_pelayanan_pm', 'Pl_pelayanan_pm');
        $this->load->model('registration/Reg_pasien_model', 'Reg_pasien');
        /*enable profiler*/
        $this->output->enable_profiler(false);
        /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'Title';

    }

    public function index() { 

        $kode_bagian = (isset($_GET['kode_bagian']))?$_GET['kode_bagian']:0;
        $type = (isset($_GET['type']))?$_GET['type']:'';
        /*define variable data*/
        $data = array(
            'title' => 'Riwayat Kunjungan',
            'breadcrumbs' => $this->breadcrumbs->show(),
            'kode_bagian' => $kode_bagian ,
            'type' => $type
        );
        /*load view index*/
        $this->load->view('Riwayat_kunjungan_pm/index', $data);
    }

    public function riwayat_kunjungan_pm_by_mr() { 

        $kode_bagian = (isset($_GET['kode_bagian']))?$_GET['kode_bagian']:0;
        $type = (isset($_GET['type']))?$_GET['type']:'';
        // ex 
        $pm = $this->db->join('tc_kunjungan', 'tc_kunjungan.no_kunjungan=pm_tc_penunjang.no_kunjungan','left')->get_where('pm_tc_penunjang', ['tc_kunjungan.no_mr' => $_GET['no_mr']])->result();
        /*define variable data*/
        $data = array(
            'title' => 'Riwayat Kunjungan',
            'breadcrumbs' => $this->breadcrumbs->show(),
            'kode_bagian' => $kode_bagian ,
            'type' => $type,
            'no_mr' => $_GET['no_mr'],
            'pm' => $pm
        );
        // echo "<pre>";print_r($data);die;
        /*load view index*/
        $this->load->view('Riwayat_kunjungan_pm/view_riwayat_kunjungan_pm_by_mr', $data);
    }
    
    public function get_data()
    {
        /*get data from model*/
        $list = $this->Riwayat_kunjungan_pm->get_datatables();
        // echo "<pre>";print_r($list);die;
        //print_r($_GET);die;
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $flag_mcu = ($row_list->flag_mcu==1)?'&flag_mcu=1':'';
            $flag_mcu_edit = ($row_list->flag_mcu==1)?'&is_mcu=1':'';
            

            $trans_kasir = $this->Riwayat_kunjungan_pm->cek_transaksi_kasir($row_list->no_registrasi, $row_list->no_kunjungan);
            if( isset($_GET['bagian_tujuan']) && $_GET['bagian_tujuan']=='050301'){
                $flag_rollback = ($trans_kasir!=true)?'submited':'unsubmit';
                $rollback_btn = '<li><a href="#" onclick="rollback('.$row_list->kode_penunjang.','."'".$flag_rollback."'".')">Rollback</a></li>';
            }else{
                $rollback_btn = ($trans_kasir==true)?'<li><a href="#" onclick="rollback('.$row_list->kode_penunjang.')">Rollback</a></li>':'';
            }
            
            if($row_list->flag_mcu == 1){
                if($row_list->status_isihasil == 1){
                    $status_pasien = 'selesai';
                }else{
                    $status_pasien = 'belum_isi_hasil';
                }
            }else{
                if($row_list->status_daftar==0 || $row_list->status_daftar==NULL){
                    $status_pasien = 'belum_ditindak';
                }else if($row_list->status_daftar==1){
                    $status_pasien = 'belum_diperiksa';
    
                }else  if($row_list->status_daftar==2){
                    $status_pasien = 'belum_isi_hasil';
                }
            }
            

            if(isset($_GET['bagian_tujuan']) and $_GET['bagian_tujuan']=='050101'){
                $cetak = ($row_list->status_isihasil==1)?'<li><a href="'.base_url().'Templates/Export_data/export?type=pdf&flag=LAB&noreg='.$row_list->no_registrasi.'&pm='.$row_list->kode_penunjang.'&kode_pm=050101&no_kunjungan='.$row_list->no_kunjungan.' '.$flag_mcu.'" target="blank"  >Cetak Hasil</a></li>':'';
                $edit_hasil = ($row_list->status_isihasil==1)?'<li><a href="#" onclick="getMenu('."'pelayanan/Pl_pelayanan_pm/form/".$row_list->no_kunjungan."/".$row_list->kode_penunjang."/".$status_pasien."'".')">Edit Hasil</a></li>':'';
            }else if(isset($_GET['bagian_tujuan']) and $_GET['bagian_tujuan']=='050201'){
                $cetak = ($row_list->status_isihasil==1)?'<li><a href="'.base_url().'Templates/Export_data/export?type=pdf&flag=RAD&noreg='.$row_list->no_registrasi.'&pm='.$row_list->kode_penunjang.'&kode_pm=050201&no_kunjungan='.$row_list->no_kunjungan.' '.$flag_mcu.'" target="blank"  >Cetak Hasil</a></li>':'';
                $edit_hasil = ($row_list->status_isihasil==1)?'<li><a href="#" onclick="getMenu('."'pelayanan/Pl_pelayanan_pm/form/".$row_list->no_kunjungan."/".$row_list->kode_penunjang."/".$status_pasien."'".')">Edit Hasil</a></li>':'';
            }else{
                $cetak = '';
                $edit_hasil = '';
            }
            
            $charge_slip = '<li><a href="#" onclick="cetak_slip('.$row_list->kode_penunjang.')">Cetak Slip</a></li>';
            
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
                            <li><a href="#" onclick="show_modal('."'registration/reg_pasien/view_detail_resume_medis/".$row_list->no_registrasi."'".', '."'RESUME MEDIS'".')">Selengkapnya</a></li>
                            '.$charge_slip.'
                            '.$cetak.'
                            '.$edit_hasil.'
                            '.$rollback_btn.'
                        </ul>
                    </div></div>';
            $row[] = '<div class="center"><a href="#" onclick="getMenu('."'pelayanan/Pl_pelayanan_pm/form/".$row_list->no_kunjungan."/".$row_list->kode_penunjang."/".$status_pasien."'".')" style="font-weight: bold; color: blue">'.$row_list->no_kunjungan.'</a></div>';
            $row[] = $row_list->no_mr.' - '.$row_list->nama_pasien;
            $row[] = $row_list->asal_bagian;
            $row[] = $row_list->tujuan_bagian;
            $row[] = $row_list->dokter;
            $row[] = $this->tanggal->formatDateTime($row_list->tgl_masuk);
            $row[] = $this->tanggal->formatDateTime($row_list->tgl_keluar);

            //$trans_kasir = $this->Pl_pelayanan->cek_transaksi_kasir($row_list->no_registrasi, $row_list->no_kunjungan);

            if($trans_kasir==false){
                $status_periksa = '<label class="label label-primary"><i class="fa fa-money"></i> Lunas </label>';
            }else{
                if($row_list->flag_mcu == 1){
                    if($row_list->status_isihasil == 1){
                        $status_periksa = '<label class="label label-success"><i class="fa fa-check-circle"></i> Selesai</label>';
                    }else{
                        $status_periksa = '<label class="label label-warning"><i class="fa fa-info-circle"></i> Belum isi hasil</label>';
                    }
                }else{
                    $status_periksa = ($row_list->tgl_keluar==NULL)?'<label class="label label-warning"><i class="fa fa-info-circle"></i> Belum diperiksa</label>':'<label class="label label-success"><i class="fa fa-check-circle"></i> Selesai</label>';
                }

            }

            $row[] = '<div class="center">'.$status_periksa.'</div>';

            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Riwayat_kunjungan_pm->count_all(),
                        "recordsFiltered" => $this->Riwayat_kunjungan_pm->count_filtered(),
                        "data" => $data,
                );
        //output to json format
        echo json_encode($output);
    }

    public function get_data_by_mr()
    {
        /*get data from model*/
        $no_mr = isset($_GET['keyword'])?$_GET['keyword']:[];
        if( ! $list = $this->cache->get('get_data_rm_penunjang_medis_'.$no_mr.'_'.date('Y-m-d').'') )
		{
            $list = $this->Riwayat_kunjungan_pm->get_datatables_by_mr();
            $this->cache->save('get_data_rm_penunjang_medis_'.$no_mr.'_'.date('Y-m-d').'', $list, 3600);
        }


        // echo "<pre>";print_r($list);die;
        //print_r($_GET);die;
        $data = array();
        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $subs_kode_bag = substr($row_list->kode_bagian_tujuan, 0,2);

            $btn_view_hasil_pm = ($subs_kode_bag=='05')?'<li><a href="#" onclick="show_modal('."'registration/reg_pasien/form_modal_view_hasil_pm/".$row_list->no_registrasi."/".$row_list->no_kunjungan."/".$row_list->kode_penunjang."/".$row_list->kode_bagian_tujuan."'".', '."'HASIL PENUNJANG MEDIS (".$row_list->tujuan_bagian.")'".')">Lihat Hasil '.$row_list->tujuan_bagian.'</a></li>':'';
            
            $row[] = '';
            $row[] = $row_list->no_registrasi;
            $row[] = $row_list->no_kunjungan;
            $row[] = $row_list->kode_penunjang;
            $row[] = $row_list->kode_bagian_tujuan;
            $row[] = $this->tanggal->formatDateTime($row_list->tgl_daftar);
            $row[] = $this->tanggal->formatDateTime($row_list->tgl_periksa);
            $row[] = $row_list->asal_bagian;
            // color parameter by penunjang
            $txt_label_pm = $this->master->get_color_pm($row_list->kode_bagian_tujuan, $row_list->tujuan_bagian);
            $row[] = '<div class="center">'.$txt_label_pm.'</div>';
            $row[] = $row_list->dokter;
            $arr_str = explode("|",$row_list->nama_tarif);
            // print_r($arr_str);die;
            $html = '<ul class="no-padding">';
            foreach ($arr_str as $key => $value) {
                if(!empty($value)){
                    $html .= '<li>'.$value.'</li>';
                }
            }
            $html .= '</ul>';

            $row[] = $html;

            $row[] = $this->tanggal->formatDateTime($row_list->tgl_isihasil);


            $data[] = $row;
        }

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Riwayat_kunjungan_pm->count_all_by_mr(),
                        "recordsFiltered" => $this->Riwayat_kunjungan_pm->count_filtered_by_mr(),
                        "no_mr" => isset($list[0]->no_mr)?$list[0]->no_mr:'',
                        "nama_pasien" => isset($list[0]->nama_pasien)?$list[0]->nama_pasien:'',
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

    public function export_excel(){
      /*get data from model*/
      $list = $this->Riwayat_kunjungan_pm->get_data();
      $data =array();
      $data['list'] = $list;
      $data['nama_bagian'] = $this->db->get_where('mt_bagian', ['kode_bagian' => $_GET['bagian_tujuan']])->row()->nama_bagian;

    //   echo "<pre>";print_r($data); die;

      $this->load->view('Riwayat_kunjungan_pm/export_excel', $data);
    }


}

/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
