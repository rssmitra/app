<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Global_report extends MX_Controller {

    /*function constructor*/
    function __construct() {

        parent::__construct();
        /*breadcrumb default*/
        $this->breadcrumbs->push('Index', 'laporan/Global_report');

        /*load model*/
        $this->load->model('Global_report_model', 'Global_report');
        $this->load->model('Master_model', 'Master_model');
        /*enable profiler*/
        $this->output->enable_profiler(false);
        /*profile class*/
        $this->title = 'Laporan Umum RS Setia Mitra';

    }

    public function index() { 
        /*define variable data*/
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show()
        );

        /*load view index*/
        $this->load->view('Global_report/index', $data);
    }

    public function akunting() { 
        /*define variable data*/
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show(),
            'flag' => 'akunting_mod_'.$_GET['mod'].''
        );

        /*load view index*/
        $data['html'] = $this->load->view('Global_report/akunting_keu/akunting_mod_'.$_GET['mod'].'', $data, true);
        $this->load->view('Global_report/form', $data);
    }

    public function master_tarif() { 
        /*define variable data*/
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show(),
            'flag' => 'master_tarif'
        );

        /*load view index*/
        $data['html'] = $this->load->view('Global_report/index_master_tarif', $data, true);
        $this->load->view('Global_report/form', $data);
    }

    public function show_data(){

        // print_r($_POST);die;
        $query_data = $this->Global_report->get_data();

        $data = array(
            'flag' => $_POST['flag'],
            'title' => $_POST['title'],
            'result' => $query_data,
        );
        
        if($_POST['submit']=='format_so'){
            $this->load->view('Global_report/'.$_POST['submit'].'', $data);
        }elseif($_POST['submit']=='format_so_2'){
            $this->load->view('Global_report/'.$_POST['submit'].'', $data);
        }elseif($_POST['submit']=='input_so'){
            $this->load->view('Global_report/'.$_POST['submit'].'', $data);
        }elseif($_POST['flag'] == 'farmasi_mod_13'){
            // echo '<pre>';print_r($data);die;
            $this->load->view('Global_report/farmasi_mod_13_view_data', $data);
        }else{
            $this->load->view('Global_report/view_data', $data);
        }

    }
    
    public function show_data_gdg_nm(){

        $query_data = $this->Global_report->get_data();
        // echo '<pre>';print_r($query_data);die;
        $g_saldo = $this->Global_report->get_saldo_awal_nm();

        // get saldo
        foreach ($g_saldo as $k_g_saldo => $v_g_saldo) {
            $get_dt_g_saldo[trim($v_g_saldo['kode_brg'])] = (int)$v_g_saldo['stok_akhir'];
        }

        $distribusi = $this->Global_report->distribusi_barang_unit_nm();
         // distribusi
         foreach ($distribusi as $k_distribusi => $v_distribusi) {
            $dt_distribusi[trim($v_distribusi['kode_brg'])] = (int)$v_distribusi['jumlah'];
        }

        $penerimaan_brg_gudang = $this->Global_report->penerimaan_brg_gudang_nm();
        
        // penerimaan barang gudang
        foreach ($penerimaan_brg_gudang as $k_penerimaan_brg => $v_penerimaan_brg_gdg) {
            $qty = isset($v_penerimaan_brg_gdg['jumlah_penerimaan'])?(int)$v_penerimaan_brg_gdg['jumlah_penerimaan']:0;
            $dt_penerimaan_brg_gdg[trim($v_penerimaan_brg_gdg['kode_brg'])] = array('qty' => $qty, 'harga_beli' => (int)$v_penerimaan_brg_gdg['harga_beli'], 'biaya' => (int)$v_penerimaan_brg_gdg['biaya']);
        }
        
        $data = array(
            'flag' => isset($_POST['flag'])?$_POST['flag']:$_GET['flag'],
            'title' => isset($_POST['title'])?$_POST['title']:'',
            'bagian' => '070101',
            'result' => $query_data,
            'year' => isset($_POST['year'])?$_POST['year']:$_GET['year'],
            'month' => isset($_POST['from_month'])?$_POST['from_month']:$_GET['month'],
            'v_saldo' => isset($get_dt_g_saldo)?$get_dt_g_saldo:[],
            'v_penerimaan' => isset($dt_penerimaan_brg)?$dt_penerimaan_brg:[],
            'v_distribusi' => isset($dt_distribusi)?$dt_distribusi:[],
            'v_penerimaan_gdg' => isset($dt_penerimaan_brg_gdg)?$dt_penerimaan_brg_gdg:[],
        );
        // echo '<pre>';print_r($data['result']);die;

        $this->load->view('Global_report/akunting_keu/v_bmhp_gudang_nm', $data);
    }

    public function show_data_bmhp(){

        $query_data = $this->Global_report->get_data();
        $g_saldo = $this->Global_report->get_saldo_awal();

        // get saldo
        foreach ($g_saldo as $k_g_saldo => $v_g_saldo) {
            $get_dt_g_saldo[trim($v_g_saldo['kode_brg'])] = (int)$v_g_saldo['stok_akhir'];
            
        }

        if($_POST['bagian'] == '060201'){
            $distribusi = $this->Global_report->distribusi_barang_unit();
            $penerimaan_brg_gudang = $this->Global_report->penerimaan_brg_gudang();

            // distribusi
            foreach ($distribusi as $k_distribusi => $v_distribusi) {
                $dt_distribusi[trim($v_distribusi['kode_brg'])] = (int)$v_distribusi['jumlah'];
            }

            // penerimaan barang gudang
            foreach ($penerimaan_brg_gudang as $k_penerimaan_brg => $v_penerimaan_brg_gdg) {
                $dt_penerimaan_brg_gdg[trim($v_penerimaan_brg_gdg['kode_brg'])] = array('qty' => (int)$v_penerimaan_brg_gdg['jumlah_penerimaan'], 'harga_beli' => (int)$v_penerimaan_brg_gdg['harga_beli']);
            }

        }else{
            $penerimaan_brg_unit = $this->Global_report->permintaan_brg_medis_unit();
            $penjualan = $this->Global_report->penjualan_obat();
            
            $bmhp = $this->Global_report->penjualan_obat_internal_bmhp();

            // penerimaan barang unit
            foreach ($penerimaan_brg_unit as $k_penerimaan_brg => $v_penerimaan_brg) {
                $dt_penerimaan_brg[trim($v_penerimaan_brg['kode_brg'])] = (int)$v_penerimaan_brg['jumlah_penerimaan'];
            }
            
            // get data penjualan bpjs
            foreach ($penjualan as $k_pjl_bpjs => $v_pjl_bpjs) {
                $jumlah = $v_pjl_bpjs['jml_terjual'];
                $total = $jumlah * ($v_pjl_bpjs['harga_rata_satuan'] + ($v_pjl_bpjs['harga_rata_satuan'] * 0.44));
                $dt_penjualan_bpjs[trim($v_pjl_bpjs['kode_brg'])] = array('jumlah' => $jumlah, 'total' => $total);
            }
            // echo '<pre>';print_r($dt_penjualan_bpjs);die;

            
            // data penjualan umum
            // foreach ($penjualan as $k_pjl_umum => $v_pjl_umum) {
            //     if($v_pjl_umum['kode_perusahaan'] != 120){
            //         $dt_penjualan_umum[trim($v_pjl_umum['kode_brg'])] = array('jumlah' => (int)$v_pjl_umum['jumlah'], 'total' => (int)$v_pjl_umum['jumlah_total']);
            //     }
            // }

            // bmhp
            foreach ($bmhp as $k_bmhp => $v_bmhp) {
                    $dt_bmhp[trim($v_bmhp['kode_brg'])] = (int)$v_bmhp['jumlah'];
            }
            
        }
        
        $data = array(
            'flag' => isset($_POST['flag'])?$_POST['flag']:$_GET['flag'],
            'title' => isset($_POST['title'])?$_POST['title']:'',
            'bagian' => isset($_POST['bagian'])?$_POST['bagian']:$_GET['kode_bagian'],
            'result' => $query_data,
            'from_tgl' => isset($_POST['from_tgl'])?$_POST['from_tgl']:date('Y-m-d'),
            'to_tgl' => isset($_POST['to_tgl'])?$_POST['to_tgl']:date('Y-m-d'),
            'v_saldo' => isset($get_dt_g_saldo)?$get_dt_g_saldo:[],
            'v_penerimaan' => isset($dt_penerimaan_brg)?$dt_penerimaan_brg:[],
            'v_penjualan_bpjs' => isset($dt_penjualan_bpjs)?$dt_penjualan_bpjs:[],
            'v_penjualan_umum' => isset($dt_penjualan_umum)?$dt_penjualan_umum:[],
            'v_bmhp' => isset($dt_bmhp)?$dt_bmhp:[],
            'v_distribusi' => isset($dt_distribusi)?$dt_distribusi:[],
            'v_penerimaan_gdg' => isset($dt_penerimaan_brg_gdg)?$dt_penerimaan_brg_gdg:[],
        );

        // echo '<pre>';print_r($data['result']);die;
        if($_POST['bagian'] == '060201'){
            $this->load->view('Global_report/akunting_keu/v_bmhp_gudang', $data);
        }else{
            $this->load->view('Global_report/akunting_keu/v_bmhp', $data);
        }
                
    }

    public function show_data_bmhp_rekap(){

        $query_data = $this->Global_report->get_data();
        $g_saldo = $this->Global_report->get_saldo_akhir();
        // print_r($this->db->last_query());die;
        // get harga beli
        foreach ($query_data['data'] as $k_result => $v_result) {
            $get_dt_result[trim($v_result->kode_brg)] = $v_result->harga_beli;
        }
        // echo '<pre>';print_r($get_dt_result);die;

        foreach ($g_saldo as $k_g_saldo => $v_g_saldo) {
            $get_dt_g_saldo[trim($v_g_saldo['nama_bagian'])][] = array('kode_brg' => trim($v_g_saldo['kode_brg']), 'nama_brg' => $v_g_saldo['nama_brg'], 'stok_akhir' => $v_g_saldo['stok_akhir'], 'kode_bagian' => $v_g_saldo['kode_bagian']);
        }

        $data = array(
            'flag' => $_POST['flag'],
            'title' => $_POST['title'],
            'month' => $_POST['from_month'],
            'year' => $_POST['year'],
            'harga_beli' => $get_dt_result,
            'v_saldo' => $get_dt_g_saldo,
        );

        
        // echo '<pre>';print_r($data);die;
        
        $this->load->view('Global_report/akunting_keu/v_bagian', $data);
                
    }

    public function show_rekap_supplier(){

        $query_data = $this->Global_report->get_data();
        $data = array(
            'flag' => $_POST['flag'],
            'title' => $_POST['title'],
            'month' => $_POST['from_month'],
            'year' => $_POST['year'],
            'jenis' => $_POST['jenis'],
            'result' => $query_data,
        );

        $this->load->view('Global_report/pengadaan_mod_6_rekap_supplier', $data);
                
    }

    public function show_rekap_supplier_detail_transaksi(){

        $query_data = $this->Global_report->get_data();
        $data = array(
            'flag' => $_GET['flag'],
            'month' => $_GET['month'],
            'year' => $_GET['year'],
            'result' => $query_data,
        );
        // echo '<pre>';print_r($data);die;
        $this->load->view('Global_report/pengadaan_mod_6_rekap_supplier_detail', $data);
                
    }

    public function pengadaan_mod_6_shw_w_det_trx(){

        $query_data = $this->Global_report->get_data();
        $getData = [];
        foreach ($query_data['data'] as $key => $value) {
            $getData[$value->supplier][] = $value; 
        }
        $data = array(
            'flag' => $_GET['flag'],
            'month' => $_GET['month'],
            'year' => $_GET['year'],
            'result' => $getData,
        );
        // echo '<pre>';print_r($data);die;
        $this->load->view('Global_report/pengadaan_mod_6_shw_w_det_trx', $data);
                
    }

    public function show_data_if(){

        $query_data = $this->Global_report->get_data();
        $g_saldo = $this->Global_report->get_saldo_awal();
        $p_penerimaan = $this->Global_report->penerimaan_penjualan();
        $pjl_bpjs = $this->Global_report->penjualan_obat_bpjs();
        $pjl_umum = $this->Global_report->penjualan_obat_umum();
        $pjl_internal = $this->Global_report->penjualan_obat_internal();
        $distribusiU = $this->Global_report->distribusi_unit();


        // get saldo
        foreach ($g_saldo as $k_g_saldo => $v_g_saldo) {
                $get_dt_g_saldo[] = $v_g_saldo;
            
        }
        // get data penjualan bpjs
        foreach ($pjl_bpjs as $k_pjl_bpjs => $v_pjl_bpjs) {
            if($v_pjl_bpjs['kode_perusahaan']==120){
                $get_dt_pjl_bpjs[] = $v_pjl_bpjs;
            }
        }
        // get data penjualan umum
        foreach ($pjl_umum as $k_pjl_umum => $v_pjl_umum) {
            if($v_pjl_umum['kode_perusahaan'] ==0){
                $get_dt_pjl_umum[] = $v_pjl_umum;
            }
        }
        foreach ($pjl_internal as $k_pjl_internal => $v_pjl_internal) {
            if($v_pjl_internal['kode_perusahaan'] == 'NULL'){
                $get_dt_pjl_internal[] = $v_pjl_internal;
            }
        }

        foreach ($distribusiU as $k_distribusiU => $v_distribusiU) {
            // if($v_distribusiU['kode_perusahaan'] == 'NULL'){
                $get_dt_distribusiU[] = $v_distribusiU;
            // }
        }
       
        // echo '<pre>'; print_r($p_penerimaan);die;

        $data = array(
            'flag' => $_POST['flag'],
            'title' => $_POST['title'],
            'result' => $query_data,
            'v_saldo' => $g_saldo,
            'v_penerimaan' => $p_penerimaan,
            'dt_pjl_bpjs' => $get_dt_pjl_bpjs,
            'dt_pjl_umum' => $get_dt_pjl_umum,
            'dt_pjl_internal' => $get_dt_pjl_internal,
            'dt_distribusiU' => $get_dt_distribusiU,
        );

        // echo '<pre>';print_r($viewpjl);
         // echo '<pre>'; print_r($query_data);

            $this->load->view('Global_report/akunting_keu/v_if', $data);
                
    }

    public function show_data_if_b(){

        $query_data = $this->Global_report->get_data();
        $g_saldo = $this->Global_report->get_saldo_b();
        $p_penerimaan = $this->Global_report->penerimaan_penjualan_b();
        $pjl_bpjs = $this->Global_report->penjualan_obat_bpjs_b();
        $pjl_umum = $this->Global_report->penjualan_obat_umum_b();
        $pjl_internal = $this->Global_report->penjualan_obat_internal_b();
        $distribusiU = $this->Global_report->distribusi_unit_b();
        // get saldo
        foreach ($g_saldo as $k_g_saldo => $v_g_saldo) {
                $get_dt_g_saldo[] = $v_g_saldo;
            
        }
        // get data penjualan bpjs
        foreach ($pjl_bpjs as $k_pjl_bpjs => $v_pjl_bpjs) {
            if($v_pjl_bpjs['kode_perusahaan']==120){
                $get_dt_pjl_bpjs[] = $v_pjl_bpjs;
            }
        }
        // get data penjualan umum
        foreach ($pjl_umum as $k_pjl_umum => $v_pjl_umum) {
            if($v_pjl_umum['kode_perusahaan'] ==0){
                $get_dt_pjl_umum[] = $v_pjl_umum;
            }
        }
        foreach ($pjl_internal as $k_pjl_internal => $v_pjl_internal) {
            if($v_pjl_internal['kode_perusahaan'] == 'NULL'){
                $get_dt_pjl_internal[] = $v_pjl_internal;
            }
        }

        foreach ($distribusiU as $k_distribusiU => $v_distribusiU) {
            // if($v_distribusiU['kode_perusahaan'] == 'NULL'){
                $get_dt_distribusiU[] = $v_distribusiU;
            // }
        }
       
        // echo '<pre>'; print_r($query_data);die;

        $data = array(
            'flag' => $_POST['flag'],
            'title' => $_POST['title'],
            'result' => $query_data,
            'v_saldo' => $g_saldo,
            'v_penerimaan' => $p_penerimaan,
            'dt_pjl_bpjs' => $get_dt_pjl_bpjs,
            'dt_pjl_umum' => $get_dt_pjl_umum,
            'dt_pjl_internal' => $get_dt_pjl_internal,
            'dt_distribusiU' => $get_dt_distribusiU,
        );

        // echo '<pre>';print_r($viewpjl);
         // echo '<pre>'; print_r($query_data);

            $this->load->view('Global_report/akunting_keu/v_if_b', $data);
                
    }

    public function show_data_if_bagian(){

        $query_data = $this->Global_report->get_data();
        $g_saldo = $this->Global_report->get_saldo_awal_bagian();
        $p_penerimaan = $this->Global_report->penerimaan_penjualan_bagian();
        $pjl_bpjs = $this->Global_report->penjualan_obat_bpjs_bagian();
        $pjl_umum = $this->Global_report->penjualan_obat_umum_bagian();
        $pjl_internal = $this->Global_report->penjualan_obat_internal_bagian();
        $distribusiU = $this->Global_report->distribusi_unit_bagian();


        // get saldo
        foreach ($g_saldo as $k_g_saldo => $v_g_saldo) {
                $get_dt_g_saldo[] = $v_g_saldo;
            
        }
        // get data penjualan bpjs
        foreach ($pjl_bpjs as $k_pjl_bpjs => $v_pjl_bpjs) {
            if($v_pjl_bpjs['kode_perusahaan']==120){
                $get_dt_pjl_bpjs[] = $v_pjl_bpjs;
            }
        }
        // get data penjualan umum
        foreach ($pjl_umum as $k_pjl_umum => $v_pjl_umum) {
            if($v_pjl_umum['kode_perusahaan'] ==0){
                $get_dt_pjl_umum[] = $v_pjl_umum;
            }
        }
        foreach ($pjl_internal as $k_pjl_internal => $v_pjl_internal) {
            if($v_pjl_internal['kode_perusahaan'] == 'NULL'){
                $get_dt_pjl_internal[] = $v_pjl_internal;
            }
        }

        foreach ($distribusiU as $k_distribusiU => $v_distribusiU) {
            // if($v_distribusiU['kode_perusahaan'] == 'NULL'){
                $get_dt_distribusiU[] = $v_distribusiU;
            // }
        }
       
        // echo '<pre>'; print_r($p_penerimaan);die;
        //echo '<pre>'; print_r($distribusiU);die;
        $data = array(
            'flag' => $_POST['flag'],
            'title' => $_POST['title'],
            'result' => $query_data,
            'v_saldo' => $g_saldo,
            'v_penerimaan' => $p_penerimaan,
            'dt_pjl_bpjs' => $get_dt_pjl_bpjs,
            'dt_pjl_umum' => $get_dt_pjl_umum,
            'dt_pjl_internal' => $get_dt_pjl_internal,
            'dt_distribusiU' => $get_dt_distribusiU,
        );

        // echo '<pre>';print_r($viewpjl);
         // echo '<pre>'; print_r($query_data);

            $this->load->view('Global_report/akunting_keu/v_if_bagian', $data);
                
    }
    
    public function show_data_stok_m(){

        $query_data = $this->Global_report->get_data();

        $data = array(
            'flag' => $_POST['flag'],
            'title' => $_POST['title'],
            'tgl' => $_POST['tgl'],
            'result' => $query_data,
        );

        // echo '<pre>';print_r($query_data);
        
           $this->load->view('Global_report/akunting_keu/v_stok_m', $data);
                
    }

    public function show_data_stok_nm(){

        $query_data = $this->Global_report->get_data();

        $data = array(
            'flag' => $_POST['flag'],
            'title' => $_POST['title'],
            'tgl' => $_POST['tgl'],
            'result' => $query_data,
        );

        // echo '<pre>';print_r($query_data);
        
           $this->load->view('Global_report/akunting_keu/v_stok_nm', $data);
                
    }

    public function show_data_keterlambatan(){

        $query_data = $this->Global_report->get_data();

        foreach($query_data['data'] as $key=>$row){
            // resume keterlambatan dokter
            $config = array(
                'kode_dokter' => $_POST['kode_dokter'],
                'kode_bagian' => $_POST['kode_bagian'],
                'tgl_mulai' => $row->tgl_keluar_poli,
            );
            $resume = $this->master->getDataKeterlambatanDokter($config);
            $jam_keterlambatan = ($resume['jam'] < 0) ? 0 : $resume['jam'].' jam '.$resume['menit'].' menit ';
            $getData[] = array(
                'dokter' => $row->nama_pegawai,
                'bagian' => $row->nama_bagian,
                'tgl_kunjungan_ori' => $row->tgl_keluar_poli,
                'tgl_kunjungan' => $this->tanggal->formatDate($row->tgl_keluar_poli),
                'jam_mulai' => $resume['jam_mulai'],
                'jam_praktek' => $resume['jam_praktek'],
                'waktu_keterlambatan' => $jam_keterlambatan,
                'jam' => $resume['jam'],
            );
        }

        $data = array(
            'flag' => $_POST['flag'],
            'title' => $_POST['title'],
            'result' => $getData,
        );

        
        $this->load->view('Global_report/v_keterlambatan_dokter', $data);
                
    }

    public function show_detail_keterlambatan(){

        $query = $this->db->order_by('no_antrian', 'ASC')->join('tc_kunjungan c','c.no_kunjungan=a.no_kunjungan','left')->join('mt_master_pasien b','b.no_mr=c.no_mr','left')
                            ->get_where('pl_tc_poli a', array('a.kode_bagian' => $_GET['kode_bagian'], 'a.kode_dokter' => $_GET['kode_dokter'], 'CAST(a.tgl_keluar_poli as DATE) = ' => $this->tanggal->formatDateTimeToSqlDate($_GET['tgl']) ))
                            ->result();

        $data = array(
            'flag' => '',
            'title' => 'DATA PASIEN DOKTER <br>'.strtoupper($_GET['dokter']).'<br>POLI/KLINIK '.strtoupper($_GET['bagian']).'<br>',
            'result' => $query,
        );

        $this->load->view('Global_report/v_detail_keterlambatan_dokter', $data);

    }

    public function rekap_resep(){

        $year = ($_POST['tahun']) ? $_POST['tahun'] : date('Y');
        $query = "select
        CASE 
        WHEN kode_perusahaan = 120 THEN 'BPJS Kesehatan'
        ELSE 'Umum'
        END AS perusahaan,
        MONTH(tgl_trans) as mth, COUNT(*) as total, SUBSTRING(kode_bagian_masuk, 1, 2) as substrt from fr_tc_far 
        left join tc_registrasi on tc_registrasi.no_registrasi = fr_tc_far.no_registrasi
        where fr_tc_far.no_registrasi > 0 AND YEAR(tgl_trans) = ".$year." and SUBSTRING(kode_bagian_masuk, 1, 2) is not null
        group by MONTH(tgl_trans), SUBSTRING(kode_bagian_masuk, 1, 2), CASE 
        WHEN kode_perusahaan = 120 THEN 'BPJS Kesehatan'
        ELSE 'Umum'
        END 
        ORDER BY MONTH(tgl_trans) ASC";
        $result = $this->db->query($query)->result();

        foreach($result as $row){
            $getData[$row->mth][$row->substrt][$row->perusahaan] = $row;
        }

        // echo '<pre>'; print_r($_POST);die;

        $data = array(
            'flag' => '',
            'title' => 'REKAPITULASI JUMLAH RESEP',
            'result' => $getData,
        );


        $this->load->view('Global_report/v_rekap_resep', $data);

    }

    public function show_data_master_tarif(){

        $this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));

        // $implode = (isset($_POST['klas'])) ? implode("_", $_POST['klas']) : '';
        // if( ! $list = $this->cache->get('master_tarif_'.date('Y-m-d').'_'.$_POST['bagian'].'_'.$implode.'') )
		// {
        //     $list = $this->Global_report->get_data();
        //     $this->cache->save('master_tarif_'.date('Y-m-d').'_'.$_POST['bagian'].'_'.$implode.'', $list, 3600);
        // }
        $list = $this->Global_report->get_data();
        // get tarif
        foreach ($list['data'] as $key => $row) {
            $tarif[$row->nama_tarif][$row->kode_klas][$row->revisi_ke] = $row;
        }
        // echo '<pre>';print_r($tarif);die;
        $klas = $this->db->order_by('no_urut', 'ASC')->get_where('mt_klas', ['is_active' => 1])->result();

        $data = array(
            'flag' => isset($_POST['flag'])?$_POST['flag']:$_GET['flag'],
            'title' => isset($_POST['title'])?$_POST['title']:'',
            'bagian' => isset($_POST['bagian'])?$_POST['bagian']:$_GET['kode_bagian'],
            'result' => $tarif,
            'klas' => $klas,
        );

        $this->load->view('Global_report/v_master_tarif', $data);
                
    }






























    public function lappembelian() { 
        /*define variable data*/
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show()
        );

        /*load view index*/
        $this->load->view('Global_report/lappembelian', $data);
    }
    public function lappembelian_1() { 
        /*define variable data*/
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show(),
            'flag' => 'lappembelianlappembelian_1_mod_'.$_GET['mod'].''
        );

        /*load view index*/
        $this->load->view('Global_report/lappembelian_1_mod_'.$_GET['mod'].'', $data);
    }
    public function lapkinerja() { 
        /*define variable data*/
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show()
        );

        /*load view index*/
        $data['html'] = $this->load->view('Global_report/lapkinerja', $data, true);
        $this->load->view('Global_report/form', $data);
        // $this->load->view('Global_report/lapkinerja', $data);
    }

    public function showdatakinerja(){

        $query_data = $this->Global_report->lapkinerja();

        $data = array(
            'flag' => $_POST['flag'],
            'title' => $_POST['title'],
            'penunjang' => $_POST['penunjang'],
            'result' => $query_data,
        );
        // echo '<pre>';print_r($query_data);
         $this->load->view('Global_report/v_kinerja_pm', $data);
        
        
    }
     public function showdatakinerjathn(){

        $query_data = $this->Global_report->lapkinerjathn();

        $data = array(
            'flag' => $_POST['flag'],
            'title' => $_POST['title'],
            'tahun' => $_POST['year'],
            'penunjang' => $_POST['penunjang'],
            'result' => $query_data,
        );
        // echo '<pre>';print_r($query_data);
         $this->load->view('Global_report/v_kinerja_pm_thn', $data);
        
        
    }
    public function lapkunjungan() { 
        /*define variable data*/
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show()
        );

        /*load view index*/
        $data['html'] = $this->load->view('Global_report/lapkunjungan', $data, true);
        $this->load->view('Global_report/form', $data);
    }

    public function showdatakunjungan(){

        // $query_data = $this->Global_report->get_data();
        $sql_ugd = $this->Global_report->vsql_ugd();
        echo $this->db->last_query();die;
        $sql_spesialis = $this->Global_report->vsql_spesialis();
        $sql_luar = $this->Global_report->vsql_luar();
        $sql_inap = $this->Global_report->vsql_inap();

         // get saldo
        // foreach ($sql_ugd as $k_sql_ugd => $v_sql_ugd) {
        //         $get_v_sql_ugd[] = $v_sql_ugd;
            
        // // }
        // //   // get saldo
        // foreach ($sql_spesialis as $k_sql_spesialis => $v_sql_spesialis) {
        //         $get_v_sql_spesialis[] = $v_sql_spesialis;
            
        // }
        // //   // get saldo
        // foreach ($sql_luar as $k_sql_luar => $v_sql_luar) {
        //         $get_v_sql_luar[] = $v_sql_luar;
            
        // }
        // //   // get saldo
        // foreach ($sql_inap as $k_sql_inap => $v_sql_inap) {
        //         $get_v_sql_inap[] = $v_sql_inap;
            
        // }

        $data = array(
            'flag' => $_POST['flag'],
            'title' => $_POST['title'],
            'penunjang' => $_POST['penunjang'],
            // 'result' => $query_data,
            'dt_sql_ugd' => $sql_ugd[0],
            'dt_sql_spesialis' => $sql_spesialis[0],
            'dt_sql_luar' => count($sql_luar),
            'dt_sql_inap' => $sql_inap[0],
        );
         // echo '<pre>';print_r($sql_ugd);die;;
         $this->load->view('Global_report/v_kunjungan', $data);
        
        
    }

    public function showdatakunjunganthn(){

        // $query_data = $this->Global_report->get_data();
        $sql_ugd = $this->Global_report->vsql_ugdthn();
        $sql_spesialis = $this->Global_report->vsql_spesialisthn();
        $sql_luar = $this->Global_report->vsql_luarthn();
        $sql_inap = $this->Global_report->vsql_inapthn();
      

        $data = array(
            'flag' => $_POST['flag'],
            'title' => $_POST['title'],
            'penunjang' => $_POST['penunjang'],
            'tahun' => $_POST['year'],
            // 'result' => $query_data,
            'dt_sql_ugd' => $sql_ugd[0],
            'dt_sql_spesialis' => $sql_spesialis[0],
            'dt_sql_luar' => $sql_luar[0],
            'dt_sql_inap' => $sql_inap[0],
        );
         // echo '<pre>';print_r($sql_ugd);die;;
         $this->load->view('Global_report/v_kunjungan_thn', $data);
        
        
    }
    public function laporanrl() { 
        /*define variable data*/
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show()
        );

        /*load view index*/
        $this->load->view('Global_report/laporanrl', $data);
    }

    public function pengadaan() { 
        /*define variable data*/
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show(),
            'flag' => 'pengadaan_mod_'.$_GET['mod'].''
        );

        /*load view index*/
        $this->load->view('Global_report/pengadaan_mod_'.$_GET['mod'].'', $data);
    }

    public function farmasi() { 
        /*define variable data*/
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show(),
            'flag' => 'farmasi_mod_'.$_GET['mod'].''
        );

        /*load view index*/
        $this->load->view('Global_report/farmasi_mod_'.$_GET['mod'].'', $data);
    }

    public function so() { 
        /*define variable data*/
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show(),
            'flag' => 'so_mod_'.$_GET['mod'].''
        );

        /*load view index*/
        $this->load->view('Global_report/so_mod_'.$_GET['mod'].'', $data);
    }

    public function kunjungan() { 
        /*define variable data*/
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show(),
            'flag' => 'so_mod_'.$_GET['mod'].''
        );

        /*load view index*/
        $this->load->view('Global_report/kunjungan_mod_'.$_GET['mod'].'', $data);
    }
    public function lainnyabillingdokter() { 
        /*define variable data*/
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show(),
            'flag' => 'lainnyabillingdokter_mod_'.$_GET['mod'].''
        );

        /*load view index*/
        $this->load->view('Global_report/lainnyabillingdokter_mod_'.$_GET['mod'].'', $data);
    }
    public function rl() { 
        /*define variable data*/
        $data = array(
            'title' => $this->title,
            'breadcrumbs' => $this->breadcrumbs->show(),
            'flag' => 'so_mod_'.$_GET['mod'].''
        );

        /*load view index*/
        $this->load->view('Global_report/rl_mod_'.$_GET['mod'].'', $data);
    }
    

    public function show_data_penerimaan(){

        $query_data = $this->Global_report->get_data();

        $data = array(
            'flag' => $_POST['flag'],
            'title' => $_POST['title'],
            'jenis' => $_POST['jenis'],
            'result' => $query_data,
        );
        // echo '<pre>';print_r($query_data);
         $this->load->view('Global_report/v_penerimaan_brg', $data);
        
        
    }
     public function show_data_penerimaan_detail(){

        $query_data = $this->Global_report->get_data();

        $data = array(
            'flag' => $_POST['flag'],
            'title' => $_POST['title'],
            'jenis' => $_POST['jenis'],
            'result' => $query_data,
        );
        // echo '<pre>';print_r($query_data);
         $this->load->view('Global_report/v_penerimaan_brg_dtl', $data);
        
        
    }
    public function show_data_r(){

        $query_data = $this->Global_report->get_data();

        $data = array(
            'flag' => $_POST['flag'],
            'title' => $_POST['title'],
            'result' => $query_data,
            'tgl1' => $_POST['tgl1'],
            'tgl2' => $_POST['tgl2'],
        );
        // echo '<pre>';print_r($query_data);
        $this->load->view('Global_report/v_racikan', $data);
                
    }
    public function show_data_farmasi_mod_10(){

        $query_data = $this->Global_report->get_data();

        $data = array(
            'flag' => $_POST['flag'],
            'title' => $_POST['title'],
            'result' => $query_data,
            'tgl1' => $_POST['tgl1'],
            'tgl2' => $_POST['tgl2'],
        );
        // echo '<pre>';print_r($query_data);
        $this->load->view('Global_report/v_distribusi_unit', $data);
                
    }

    public function show_data_farmasi_mod_11(){

        $query_data = $this->Global_report->get_data();

        $data = array(
            'flag' => $_POST['flag'],
            'title' => $_POST['title'],
            'result' => $query_data,
            'tgl1' => $_POST['tgl1'],
            'tgl2' => $_POST['tgl2'],
        );
        // echo '<pre>';print_r($query_data);
        $this->load->view('Global_report/v_laporan_if', $data);
                
    }
    public function show_data_cito(){

        $query_data = $this->Global_report->get_data();

        $data = array(
            'flag' => $_POST['flag'],
            'title' => $_POST['title'],
            'result' => $query_data,
            'tgl1' => $_POST['tgl1'],
            'tgl2' => $_POST['tgl2'],
        );
        // echo '<pre>';print_r($query_data);
        $this->load->view('Global_report/v_cito', $data);
                
    }
    public function show_data_p_resep(){

        $query_data = $this->Global_report->get_data();
        $namabagian = $this->db->where("kode_bagian = '".$_POST['bagian']."' ")
                          ->order_by('nama_bagian', 'ASC')
                          ->get('mt_bagian')->result();
        $data = array(
            'flag' => $_POST['flag'],
            'title' => $_POST['title'],
            'result' => $query_data,
            'resultt' => $namabagian[0],
            'bagian' => $_POST['bagian'],
            'lokasi' => $_POST['lokasi'],
            'tgl1' => $_POST['tgl1'],
            'tgl2' => $_POST['tgl2'],
        );
         // echo '<pre>';print_r($namabagian);
        $this->load->view('Global_report/v_pesan_resep', $data);
                
    }

   public function show_data_obat_kategori(){

        $query_data = $this->Global_report->get_data();
        
        $data = array(
            'flag' => $_POST['flag'],
            'title' => $_POST['title'],
            'result' => $query_data,
            // 'obat_alkes' => $_POST['obat_alkes'],
            // 'kode_profit' => $_POST['kode_profit'],
            'tgl1' => $_POST['tgl1'],
            'tgl2' => $_POST['tgl2'],
        );
         // echo '<pre>';print_r($query_data);
        $this->load->view('Global_report/v_obat_kategori', $data);
                
    }

    function getNamaKaryawan()
    {
        
        $result = $this->db->where("kode_dokter IS NULL AND nama_pegawai LIKE '%".$_POST['keyword']."%' ")
                          ->order_by('nama_pegawai', 'ASC')
                          ->get('mt_karyawan')->result();
        $arrResult = [];
        foreach ($result as $key => $value) {
            $arrResult[] = $value->no_induk.' : '.$value->nama_pegawai;
        }
        echo json_encode($arrResult);
        
        
    }

    

    
    public function show_data_stok(){

        $query_data = $this->Global_report->get_data();

        $data = array(
            'flag' => $_POST['flag'],
            'title' => $_POST['title'],
            'result' => $query_data,
        );

        
            $this->load->view('Global_report/v_stok', $data);
                
    }
    
    public function show_data_stokb(){

        $query_data = $this->Global_report->get_data();

        $data = array(
            'flag' => $_POST['flag'],
            'title' => $_POST['title'],
            'status' => $_POST['status'],
            'result' => $query_data,
        );

        
            $this->load->view('Global_report/v_stokbarang', $data);
                
    }
    public function show_data_bon(){

        $query_data = $this->Global_report->get_data();

        $data = array(
            'flag' => $_POST['flag'],
            'title' => $_POST['title'],
            'result' => $query_data,
        );

        
            $this->load->view('Global_report/v_bon', $data);
                
    }
    public function show_data_k_unit(){

        $query_data = $this->Global_report->get_data();
        foreach ($query_data['data'] as $key => $value) {
            $getData[$value->nama_bagian_minta][] = $value;
        }

        $data = array(
            'flag' => $_POST['flag'],
            'title' => $_POST['title'],
            'bagian' => $this->master->get_string_data('nama_bagian', 'mt_bagian', array('kode_bagian' => $_POST['bagian'])),
            'result' => $getData,
        );

        // echo '<pre>';print_r($query_data);
        
        $this->load->view('Global_report/v_keluar_unit', $data);
                
    }
    public function show_data_rekap_unit(){

        $query_data = $this->Global_report->get_data();
        foreach ($query_data['data'] as $key => $value) {
            $getData[$value->nama_bagian_minta][] = $value;
        }
        
        // echo '<pre>';print_r($getData);die;
        $data = array(
            'flag' => $_POST['flag'],
            'title' => $_POST['title'],
            'status' => $_POST['status'],
            'result' => $getData,
        );

        
        $this->load->view('Global_report/rekap_keluar_unit', $data);
                
    }
    public function show_data_rekap_unit_barang(){
        
        $query_data = $this->Global_report->get_data();
        $getData = [];
        foreach ($query_data['data'] as $key => $value) {
            $getData[$value->nama_bagian_minta][] = $value;
        }
        $data = array(
            'flag' => $_POST['flag'],
            'title' => $_POST['title'],
            'result' => $getData,
        );
        // echo '<pre>';print_r($getData);die;
        $this->load->view('Global_report/rekap_keluar_unit_barang', $data);
                
    }

    public function show_data_pp(){

        $query_data = $this->Global_report->get_data();
        // echo '<pre>';print_r($query_data);die;
        $data = array(
            'flag' => $_POST['flag'],
            'title' => $_POST['title'],
            'keterangan' => $_POST['keterangan'],
            'status' => $_POST['status'],
            'result' => $query_data,
        );

        
            $this->load->view('Global_report/v_permintaanpembelian', $data);
                
    }

    public function v_kunjungan_hari(){

        $query_data = $this->Global_report->get_data();

        $data = array(
            'flag' => $_POST['flag'],
            'title' => $_POST['title'],
            'tanggal' => $_POST['tgl'],
            'result' => $query_data,
        );

        
            $this->load->view('Global_report/v_kunjungan_hari', $data);
       
        
    }
     public function v_registrasi_hari(){

        $query_data = $this->Global_report->get_data();

        $data = array(
            'flag' => $_POST['flag'],
            'title' => $_POST['title'],
            'from_tanggal' => $_POST['from_tgl'],
            'to_tanggal' => $_POST['to_tgl'],
            'result' => $query_data,
        );

        $this->load->view('Global_report/v_registrasi_hari', $data);
       
        
    }

    public function show_data_po(){

        $query_data = $this->Global_report->get_data();
        $data = array(
            'flag' => $_POST['flag'],
            'title' => $_POST['title'],
            'keterangan' => $_POST['keterangan'],
            'bulan' => $_POST['from_month'],
            'tahun' => $_POST['year'],
            'result' => $query_data,
        );
        // echo '<pre>'; print_r($query_data);die;

        
            $this->load->view('Global_report/v_purchaseorder', $data);
                
    }

    public function show_data_po_donasi(){

        $query_data = $this->Global_report->get_data();

        $data = array(
            'flag' => $_POST['flag'],
            'title' => $_POST['title'],
            'keterangan' => $_POST['keterangan'],
            'bulan' => $_POST['from_month'],
            'bulan2' => $_POST['to_month'],
            'tahun' => $_POST['year'],
            'result' => $query_data,
        );

        
        // echo '<pre>';print_r($query_data);
            $this->load->view('Global_report/v_purchaseorder_donasi', $data);
                
    }

     public function show_data_so(){

        $query_data = $this->Global_report->get_data();

        $data = array(
            'flag' => $_POST['flag'],
            'title' => $_POST['title'],
            'agenda_so' => $_POST['agenda_so'],
            'status' => $_POST['status'],
            'bagian' => $_POST['bagian'],
            'result' => $query_data,
        );

        
            $this->load->view('Global_report/v_so', $data);
                
    }
     public function show_data_sblm_so(){

        $query_data = $this->Global_report->get_data();

        $data = array(
            'flag' => $_POST['flag'],
            'title' => $_POST['title'],
            'agenda_so' => $_POST['agenda_so'],
            'status' => $_POST['status'],
            'bagian' => $_POST['bagian'],
            'result' => $query_data,
        );

        
            $this->load->view('Global_report/v_sblm_so', $data);
                
    }
    public function show_data_rl1(){

        $query_data = $this->Global_report->get_data();
        $jumlah_spesialis = $this->Global_report->jumlah_spesialis();
        $jumlah_spesialispwt = $this->Global_report->jumlah_spesialispwt();
        $jumlah_spesialisbdn = $this->Global_report->jumlah_spesialisbdn();
        $jumlah_spesialisfrm = $this->Global_report->jumlah_spesialisfrm();
        $jumlah_spesialistk = $this->Global_report->jumlah_spesialistk();
        $jumlah_spesialisntk = $this->Global_report->jumlah_spesialisntk();

        $data = array(
            'flag' => $_POST['flag'],
            'title' => $_POST['title'],
            'result' => $query_data['data'][0],
            'jumlah_spesialis'    => $jumlah_spesialis,
            'jumlah_spesialispwt' => $jumlah_spesialispwt[0],
            'jumlah_spesialisbdn' => $jumlah_spesialisbdn[0],
            'jumlah_spesialisfrm' => $jumlah_spesialisfrm[0],
            'jumlah_spesialistk'  => $jumlah_spesialistk[0],
            'jumlah_spesialisntk'  => $jumlah_spesialisntk[0],
        );
       
            $this->load->view('Global_report/v_rl1', $data);
                
    }

     public function show_data_rl12(){

        $query_data = $this->Global_report->rl_mod_12_1();
        // echo '<pre>';print_r($query_data);
        $ddkonf = $this->Global_report->v_rl_mod_1();
        // $query_data2 = $this->Global_report->rl_mod_12_2();
        // $query_data3 = $this->Global_report->rl_mod_12_3();

        $data = array(
            'flag'    => $_POST['flag'],
            'title'   => $_POST['title'],
            'tahun'   => $_POST['year'],
            'konf'    => $ddkonf[0],
            'result'  => $query_data,
            // 'result2' => isset($query_data2[0])?$query_data2[0]:0,
            // 'result3' => isset($query_data3[0])?$query_data3[0]:0,
        );
       //echo '<pre>';print_r($ddkonf);
           // $this->load->view('Global_report/tpl_incKopLap_rl', $data);
            $this->load->view('Global_report/v_rl12', $data);
                
    }

    public function show_data_rl13(){

        $query_data = $this->Global_report->rl_mod_13();
        $data = array(
            'flag'    => $_POST['flag'],
            'title'   => $_POST['title'],
            'tahun'   => $_POST['year'],
            'result'  => $query_data,
        );
       //echo '<pre>';print_r($query_data3);
            $this->load->view('Global_report/v_rl13', $data);
                
    }


     public function show_data_rl2(){

        $query_data = $this->Global_report->rl_mod_2_1();
        // $query_data2 = $this->Global_report->rl_mod_2();
        $ddkonf = $this->Global_report->v_rl_mod_1();
        $data = array(
            'flag' => $_POST['flag'],
            'title' => $_POST['title'],
            'result'  => $query_data,
            // 'resultt'  => $query_data2,
            'konf'    => $ddkonf[0],
        );
        // echo '<pre>';print_r($query_data);
        $this->load->view('Global_report/v_rl2', $data);
                
    }

    public function show_data_rl31(){

        $query_data = $this->Global_report->rl_mod_3_1();
        // $query_data2 = $this->Global_report->rl_mod_3_1_1();
        // $query_data3 = $this->Global_report->rl_mod_3_1_2();
        // $query_data2 = $this->Global_report->rl_mod_2();
        $ddkonf = $this->Global_report->v_rl_mod_1();
        $data = array(
            'flag' => $_POST['flag'],
            'title' => $_POST['title'],
            'result'  => $query_data,
            'bagian'  => $this->db->order_by('nama_bagian','ASC')->get_where('mt_bagian',array('pelayanan' => 1))->result(),
            // 'result3'  => isset($query_data3[0])?$query_data3[0]:0,
            'konf'    => $ddkonf[0],
        );
         // echo '<pre>';print_r($query_data);
        $this->load->view('Global_report/v_rl31', $data);
                
    }

     public function get_total_pengadaan()
    {
        /*get data from model*/
        $list = $this->Global_report->totalpengadaan_mod_3();
        
        $arr_submit = array();
        $arr_non_submit = array();
        
        $result = array(
            'total' => array_sum($arr_submit),
        );

        echo json_encode($result);
    }

    public function show_data_olbulanan(){

        $query_data = $this->Global_report->lappembelian_1_mod_1();
        
        $data = array(
            'flag' => $_POST['flag'],
            'title' => $_POST['title'],
            'jenis' => $_POST['jenis'],
            'bulan' => $_POST['to_month'],
            'tahun' => $_POST['year'],
            'result' => $query_data,
        );
        //  echo '<pre>';print_r($query_data);
        $this->load->view('Global_report/v_ol_bulanan', $data);
                
    }

    public function show_data_oltahunan(){

        $query_data = $this->Global_report->lappembelian_1_mod_2();
        
        $data = array(
            'flag' => $_POST['flag'],
            'title' => $_POST['title'],
            'jenis' => $_POST['jenis'],
            'tahun' => $_POST['year'],
            'result' => $query_data,
        );
         // echo '<pre>';print_r($query_data);
        $this->load->view('Global_report/v_ol_tahunan', $data);
                
    }

    
 public function v_mcu(){

        $query_data = $this->Global_report->get_data();

        $data = array(
            'flag' => $_POST['flag'],
            'title' => $_POST['title'],
            'from_tanggal' => $_POST['from_tgl'],
            'to_tanggal' => $_POST['to_tgl'],
            'result' => $query_data,
        );

        
            $this->load->view('Global_report/v_mcu', $data);
       
        
    }

    public function show_data_pemakaian_unit(){

        $query_data = $this->Global_report->get_data();

        $data = array(
            'flag' => $_POST['flag'],
            'title' => $_POST['title'],
            'status' => $_POST['status'],
            'bagian' => $_POST['bagian'],
            'bulan' => $_POST['to_month'],
            'tahun' => $_POST['year'],
            'result' => $query_data,
        );

        
            $this->load->view('Global_report/v_pemakaian_unit', $data);
                
    }
}

/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
