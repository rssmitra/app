<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Riwayat_reg_pasien extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'registration/Riwayat_reg_pasien');
        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            echo 'Session Expired !'; exit;
        }
        /*load model*/
        $this->load->model('Riwayat_reg_pasien_model', 'Riwayat_reg_pasien');
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
        /*load view index*/
        $this->load->view('Riwayat_reg_pasien/index', $data);
    }

    public function form($id='')
    {
        /*if id is not null then will show form edit*/
        if( $id != '' ){
            /*breadcrumbs for edit*/
            $this->breadcrumbs->push('Edit '.strtolower($this->title).'', 'Reg_on_dashboard/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
            /*get value by id*/
            $data['value'] = $this->Reg_on_dashboard->get_by_id($id);
            /*initialize flag for form*/
            $data['flag'] = "update";
        }else{
            /*breadcrumbs for create or add row*/
            $this->breadcrumbs->push('Add '.strtolower($this->title).'', 'Reg_on_dashboard/'.strtolower(get_class($this)).'/form');
            /*initialize flag for form add*/
            $data['flag'] = "create";
        }
        /*title header*/
        $data['title'] = $this->title;
        /*show breadcrumbs*/
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Reg_on_dashboard/form', $data);
    }
    /*function for view data only*/
    public function show($id)
    {
        /*breadcrumbs for view*/
        $this->breadcrumbs->push('View '.strtolower($this->title).'', 'Reg_on_dashboard/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$id);
        /*define data variabel*/
        $data['value'] = $this->Reg_on_dashboard->get_by_id($id);
        $data['title'] = $this->title;
        $data['flag'] = "read";
        $data['breadcrumbs'] = $this->breadcrumbs->show();
        /*load form view*/
        $this->load->view('Reg_on_dashboard/form', $data);
    }

    public function get_data()
    {
        /*get data from model*/
        $list = $this->Riwayat_reg_pasien->get_datatables();
        

        $data = array();
        $resume = array();
        $rekap_batal = array();
        $rekap_stat_pasien = array();
        $rekap_asuransi = array();
        $rekap_dr = array();
        $substr = array();
        $total_unit = array();
        $total_kunjungan = array();
        $total_dr = array();
        $total_asuransi = array();
        $total_stat_pasien = array();

        $no = $_POST['start'];
        foreach ($list as $row_list) {
            $no++;
            $row = array();
            $row[] = '<div class="center">'.$no.'</div>';
            $row[] = '<div class="center"><a href="#" onclick="show_modal('."'registration/reg_pasien/view_detail_resume_medis/".$row_list->no_registrasi."'".', '."'RESUME MEDIS PASIEN'".')" style="font-weight: bold; color: blue">'.$row_list->no_registrasi.'</a></div>';
            $row[] = '<div class="center">'.$row_list->no_mr.'</div>';
            $row[] = strtoupper($row_list->nama_pasien);
            $row[] = ($row_list->nama_perusahaan)?$row_list->nama_perusahaan:'UMUM';
            $row[] = $this->tanggal->formatDateTime($row_list->tgl_jam_masuk);
            $row[] = ucwords($row_list->nama_bagian);
            $row[] = $row_list->nama_pegawai;
            $row[] = '<div class="center">'.$row_list->no_sep.'</div>';
            $data[] = $row;
            $resume[$row_list->nama_bagian][] = $row;
            $rekap_dr[$row_list->nama_pegawai][] = $row;
            $rekap_asuransi[$row_list->kode_perusahaan][] = $row_list->nama_perusahaan;
            $rekap_stat_pasien[$row_list->stat_pasien][] = $row_list->stat_pasien;
            // substring
            $substring = substr($row_list->kode_bagian_masuk, 0,2);
            $substr[$substring][] = $row;
            if($row_list->status_batal == 1){
                $rekap_batal[] = $row;
            }
        }
        
        // echo "<pre>"; print_r($resume); die;
        // rekap berdasarkan unit
        foreach($resume as $key=>$val){
            $total_unit[] = ['unit' => $key, 'total' => count($resume[$key])];
        }

        // rekap berdasarkan stat_pasien
        foreach($rekap_stat_pasien as $key=>$val){
            $total_stat_pasien[] = ['status' => $key, 'total' => count($rekap_stat_pasien[$key])];
        }
        

        // rekap berdasarkan asuransi
        foreach($rekap_asuransi as $key=>$val){
            $total_asuransi[$key] = ['penjamin' => ($val[0] == '')?'UMUM':$val[0], 'total' => count($rekap_asuransi[$key])];
        }
        // echo "<pre>"; print_r($total_asuransi);die;


        // rekap berdasarkan dokter
        foreach($rekap_dr as $key=>$val){
            $nama_dr = (!empty($key))?$key:'<span class="red bold">-tidak ada dokter-</spam>';
            $total_dr[] = ['nama_dr' => $nama_dr, 'total' => count($rekap_dr[$key])];
        }
        // rekap berdasarkan instalasi
        foreach($substr as $key=>$val){
            if($key == '01'){
                $total_kunjungan[] = ['unit' => 'POLIKLINIK', 'total' => count($substr[$key])];
            }
            if($key == '05'){
                $total_kunjungan[] = ['unit' => 'PENUNJANG MEDIS', 'total' => count($substr[$key])];
            }
            if($key == '02'){
                $total_kunjungan[] = ['unit' => 'IGD', 'total' => count($substr[$key])];
            }
            if($key == '03'){
                $total_kunjungan[] = ['unit' => 'RAWAT INAP', 'total' => count($substr[$key])];
            }
        }
        // echo "<pre>"; print_r($total_dr);die;

        $output = array(
                        "draw" => $_POST['draw'],
                        "recordsTotal" => $this->Riwayat_reg_pasien->count_all(),
                        "recordsFiltered" => $this->Riwayat_reg_pasien->count_filtered(),
                        "data" => $data,
                        "resume" => $total_unit,
                        "rekap" => $total_kunjungan,
                        "rekap_dr" => $total_dr,
                        "rekap_asuransi" => $total_asuransi,
                        "rekap_stat_pasien" => $total_stat_pasien,
                        "rekap_batal" => count($rekap_batal),
                );
        //output to json format
        echo json_encode($output);
    }


    public function find_data()
    {   
        $output = array( "data" => http_build_query($_POST) . "\n" );
        echo json_encode($output);
    }

    /**
     * Modal: detail paket MCU pasien
     * Accepts GET params: from (yyyy-mm-dd), to (yyyy-mm-dd)
     * Returns JSON { html: '<html table ...>' }
     */
    public function modal_mcu_detail()
    {
        $from = $this->input->get('from') ? $this->input->get('from') : date('Y-m-d');
        $to = $this->input->get('to') ? $this->input->get('to') : date('Y-m-d');


        $this->db->select('tc_trans_pelayanan.*, tc_registrasi.no_registrasi, tc_registrasi.no_mr');
        $this->db->from('tc_trans_pelayanan');
        $this->db->join('tc_registrasi','tc_registrasi.no_registrasi=tc_trans_pelayanan.no_registrasi','left');
        // filter by date range (support either tgl_jam_masuk OR tgl_jam_keluar in range)
        $this->db->group_start()
                 ->where("CAST(tc_registrasi.tgl_jam_masuk AS DATE) >=", $from)
                 ->where("CAST(tc_registrasi.tgl_jam_masuk AS DATE) <=", $to)
                 ->where("kode_bagian_masuk", '010901')
                 ->group_end()
                 ->or_group_start()
                 ->where("CAST(tc_registrasi.tgl_jam_keluar AS DATE) >=", $from)
                 ->where("CAST(tc_registrasi.tgl_jam_keluar AS DATE) <=", $to)
                 ->where("kode_bagian_masuk", '010901')
                 ->group_end();

        $this->db->order_by('tc_registrasi.tgl_jam_masuk', 'DESC');

        $result = $this->db->get()->result();

        // bangun mapping nama pasien dari no_mr (efisien: 1 query)
        $mrs = array();
        $kode_tarifs = array();
        foreach($result as $r){
            if(!empty($r->no_mr)) $mrs[] = $r->no_mr;
            if(!empty($r->kode_tarif)) $kode_tarifs[] = $r->kode_tarif;
            if(!empty($r->kode_tindakan)) $kode_tarifs[] = $r->kode_tindakan;
        }
        $mrs = array_values(array_unique($mrs));
        $kode_tarifs = array_values(array_unique($kode_tarifs));

        $pasien_map = array();
        if(!empty($mrs)){
            $pasien_rows = $this->db->select('no_mr, nama_pasien')->from('mt_master_pasien')->where_in('no_mr', $mrs)->get()->result();
            foreach($pasien_rows as $p){ $pasien_map[$p->no_mr] = $p->nama_pasien; }
        }

        $tarif_map = array();
        if(!empty($kode_tarifs)){
            $tarif_rows = $this->db->select('kode_tarif, nama_tarif')->from('mt_master_tarif')->where_in('kode_tarif', $kode_tarifs)->get()->result();
            foreach($tarif_rows as $t){ $tarif_map[$t->kode_tarif] = $t->nama_tarif; }
        }

        // susun details sesuai view: no_registrasi, no_mr, nama_pasien, nama_tarif, tgl_daftar
        $details = array();
        foreach($result as $r){
            $nama_pasien = isset($pasien_map[$r->no_mr]) ? $pasien_map[$r->no_mr] : (isset($r->nama_pasien)?$r->nama_pasien:'-');

            // tentukan nama paket: prioritas field yang ada
            $nama_paket = '-';
            if(!empty($r->nama_tindakan)){
                $nama_paket = $r->nama_tindakan;
            }elseif(!empty($r->nama_tarif)){
                $nama_paket = $r->nama_tarif;
            }elseif(!empty($r->kode_tarif) && isset($tarif_map[$r->kode_tarif])){
                $nama_paket = $tarif_map[$r->kode_tarif];
            }elseif(!empty($r->kode_tindakan) && isset($tarif_map[$r->kode_tindakan])){
                $nama_paket = $tarif_map[$r->kode_tindakan];
            }

            $tgl = '';
            if(!empty($r->tgl_transaksi)) $tgl = $r->tgl_transaksi;
            elseif(!empty($r->tgl_daftar)) $tgl = $r->tgl_daftar;
            elseif(!empty($r->tgl_jam_masuk)) $tgl = $r->tgl_jam_masuk;

            $details[] = (object)array(
                'no_registrasi' => isset($r->no_registrasi)?$r->no_registrasi : '',
                'no_mr' => isset($r->no_mr)?$r->no_mr : '',
                'nama_pasien' => $nama_pasien,
                'nama_tarif' => $nama_paket,
                'tgl_daftar' => $tgl
            );
        }

        // bangun rekap per paket dari details
        $rekap_map = array();
        foreach($details as $d){
            $k = $d->nama_tarif ?: '-';
            if(!isset($rekap_map[$k])){
                $rekap_map[$k] = (object)array('nama_tarif' => $k, 'total' => 0);
            }
            $rekap_map[$k]->total++;
        }
        $rekap_paket = array_values($rekap_map);
        usort($rekap_paket, function($a, $b){ return $b->total - $a->total; });

        $data = array(
            'details' => $details,
            'rekap_paket' => $rekap_paket,
            'from' => $from,
            'to' => $to
        );

        // langsung render view (show_modal akan melakukan load URL ke modal)
        $this->load->view('Riwayat_reg_pasien/modal_mcu_detail', $data);
    }

    public function updateNoSEP(){
        $this->db->where('no_registrasi', $_POST['ID'])->update('tc_registrasi', array('no_sep' => $_POST['no_sep']));
        echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan'));
    }


}

/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
