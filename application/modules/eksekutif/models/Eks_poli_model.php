<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Eks_poli_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _main_query(){
        $this->db->select('CAST((SUM(bill_rs) + SUM(bill_dr1) + SUM(bill_dr2) + SUM(bill_dr3)) as INT) as total');
        $this->db->from('tc_trans_pelayanan b');
        $this->db->join('tc_kunjungan c', 'c.no_kunjungan=b.no_kunjungan','left');
        $this->db->join('mt_bagian d', 'd.kode_bagian=b.kode_bagian','left');

        if (isset($_GET['penjamin']) AND $_GET['penjamin'] != 'all') {  
            if (isset($_GET['penjamin']) AND $_GET['penjamin'] == 'bpjs') { 
                $this->db->where('b.kode_perusahaan', 120);
            }

            if (isset($_GET['penjamin']) AND $_GET['penjamin'] == 'asuransi') { 
                $this->db->where('b.kode_perusahaan NOT IN(120,0) ');
            }

            if (isset($_GET['penjamin']) AND $_GET['penjamin'] == 'umum') { 
                $this->db->where('b.kode_perusahaan', 0);
            }
        }
 
    }

    function get_content_data($params) {

        /*total klaim berdasarkan nomor sep per tahun existing*/
        /*based query*/
        if($params['prefix']==1){
            $data = array();
            // periode
            $this->db->select('b.no_kunjungan, d.nama_bagian');
            $this->_main_query();
            if(isset($_GET['jenis_kunjungan']) AND $_GET['jenis_kunjungan'] != 'all') {
                if (isset($_GET['jenis_kunjungan']) AND $_GET['jenis_kunjungan'] == 'rj') {
                    $this->db->where('SUBSTRING(b.kode_bagian, 0, 3) != '."'06'".'');
                    $this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
                    FROM tc_kunjungan a
                    where SUBSTRING(a.kode_bagian_tujuan, 0, 3) != '."'03'".' AND CAST(tgl_masuk as DATE) BETWEEN '."'".$_GET['from_tgl']."'".' AND '."'".$_GET['to_tgl']."'".' AND a.status_batal is null   )');
                }

                if (isset($_GET['jenis_kunjungan']) AND $_GET['jenis_kunjungan'] == 'ri') {
                    $this->db->where('SUBSTRING(b.kode_bagian, 0, 3) = '."'03'".'');
                    $this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
                    FROM ri_tc_rawatinap a
                    where CAST(tgl_masuk as DATE) BETWEEN '."'".$_GET['from_tgl']."'".' AND '."'".$_GET['to_tgl']."'".'  )');
                }
            }else{
                $this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
                    FROM tc_kunjungan a
                    where CAST(tgl_masuk as DATE) BETWEEN '."'".$_GET['from_tgl']."'".' AND '."'".$_GET['to_tgl']."'".' AND a.status_batal is null   )');
            }
            $this->db->group_by('b.no_kunjungan, d.nama_bagian');
            $prd_dt = $this->db->get();
            
            
            // day
            $this->_main_query();
            $this->db->select('b.no_kunjungan, d.nama_bagian');
            if(isset($_GET['jenis_kunjungan']) AND $_GET['jenis_kunjungan'] != 'all') {
                if (isset($_GET['jenis_kunjungan']) AND $_GET['jenis_kunjungan'] == 'rj') { 
                    $this->db->where('SUBSTRING(b.kode_bagian, 0, 3) != '."'06'".'');   
                    $this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
                    FROM tc_kunjungan a
                    where SUBSTRING(a.kode_bagian_tujuan, 0, 3) != '."'03'".' AND CAST(tgl_masuk as DATE) = '."'".date('Y-m-d')."'".' AND a.status_batal is null )');           
                }

                if (isset($_GET['jenis_kunjungan']) AND $_GET['jenis_kunjungan'] == 'ri') { 
                    $this->db->where('SUBSTRING(b.kode_bagian, 0, 3) = '."'03'".'');    
                    $this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
                    FROM ri_tc_rawatinap a
                    where CAST(tgl_masuk as DATE) = '."'".date('Y-m-d')."'".' )');          
                }
            }else{
                $this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
                    FROM tc_kunjungan a
                    where CAST(tgl_masuk as DATE) = '."'".date('Y-m-d')."'".' AND a.status_batal is null )');   
            }
            $this->db->group_by('b.no_kunjungan, d.nama_bagian');
            $dy_dt = $this->db->get();

            // month
            $this->_main_query();
            $this->db->select('b.no_kunjungan, d.nama_bagian');
            if(isset($_GET['jenis_kunjungan']) AND $_GET['jenis_kunjungan'] != 'all') {
                if (isset($_GET['jenis_kunjungan']) AND $_GET['jenis_kunjungan'] == 'rj') { 
                    $this->db->where('SUBSTRING(b.kode_bagian, 0, 3) != '."'06'".'');   
                    $this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
                    FROM tc_kunjungan a
                    where SUBSTRING(a.kode_bagian_tujuan, 0, 3) != '."'03'".' AND MONTH(tgl_masuk) = '.date('m').' AND YEAR(tgl_masuk) = '.date('Y').' AND a.status_batal is null)');           
                }

                if (isset($_GET['jenis_kunjungan']) AND $_GET['jenis_kunjungan'] == 'ri') { 
                    $this->db->where('SUBSTRING(b.kode_bagian, 0, 3) = '."'03'".'');    
                    $this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
                    FROM ri_tc_rawatinap a
                    where MONTH(tgl_masuk) = '.date('m').' AND YEAR(tgl_masuk) = '.date('Y').' )');         
                }
            }else{
                $this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
                    FROM tc_kunjungan a
                    where MONTH(tgl_masuk) = '.date('m').' AND YEAR(tgl_masuk) = '.date('Y').' AND a.status_batal is null)');       
            }
            $this->db->group_by('b.no_kunjungan, d.nama_bagian');
            $mth_dt = $this->db->get();

            

            // year
            $this->_main_query();
            $this->db->select('b.no_kunjungan, d.nama_bagian');
            if(isset($_GET['jenis_kunjungan']) AND $_GET['jenis_kunjungan'] != 'all') {
                if (isset($_GET['jenis_kunjungan']) AND $_GET['jenis_kunjungan'] == 'rj') { 
                    $this->db->where('SUBSTRING(b.kode_bagian, 0, 3) != '."'06'".'');   
                    $this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
                    FROM tc_kunjungan a
                    where SUBSTRING(a.kode_bagian_tujuan, 0, 3) != '."'03'".' AND YEAR(tgl_masuk) = '.date('Y').' AND a.status_batal is null)');            
                }

                if (isset($_GET['jenis_kunjungan']) AND $_GET['jenis_kunjungan'] == 'ri') { 
                    $this->db->where('SUBSTRING(b.kode_bagian, 0, 3) = '."'03'".'');    
                    $this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
                    FROM ri_tc_rawatinap a
                    where YEAR(tgl_masuk) = '.date('Y').' )');          
                }
            }else{
                $this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
                    FROM tc_kunjungan a
                    where YEAR(tgl_masuk) = '.date('Y').' AND a.status_batal is null)');
            }
            $this->db->group_by('b.no_kunjungan, d.nama_bagian');
            $yr_dt = $this->db->get();
            // print_r($this->db->last_query());die;

            // text title
            if(isset($_GET['jenis_kunjungan']) AND $_GET['jenis_kunjungan'] != 'all') {
                if (isset($_GET['jenis_kunjungan']) AND $_GET['jenis_kunjungan'] == 'rj') {     
                    $ttl_jk = 'Rawat Jalan';            
                }

                if (isset($_GET['jenis_kunjungan']) AND $_GET['jenis_kunjungan'] == 'ri') {     
                    $ttl_jk = 'Rawat Inap';         
                }
            }else{
                $ttl_jk = '';
            }

            if (isset($_GET['penjamin']) AND $_GET['penjamin'] != 'all') {  
                if (isset($_GET['penjamin']) AND $_GET['penjamin'] == 'bpjs') { 
                    $ttl_pj = 'BPJS Kesehatan';
                }
    
                if (isset($_GET['penjamin']) AND $_GET['penjamin'] == 'asuransi') { 
                    $ttl_pj = 'Asuransi Umum';
                }
    
                if (isset($_GET['penjamin']) AND $_GET['penjamin'] == 'umum') { 
                    $ttl_pj = 'Umum';
                }
            }else{
                $ttl_pj = '';
            }


            $data = array(
                'prd_dt' => $prd_dt->result(),
                'dy_dt' => $dy_dt->result(),
                'mth_dt' => $mth_dt->result(),
                'yr_dt' => $yr_dt->result(),
            );

            $fields = array();
            $title = isset($_GET['jenis_kunjungan']) ? '' : '' ;
            $title = '<span style="font-size: 14px">LAPORAN KUNJUNGAN PASIEN <br><b>'.strtoupper($ttl_jk).' - '.strtoupper($ttl_pj).'</b></span>';
            $subtitle = 'Source: RSSM - SIRS';
        }

        if($params['prefix']==2){
            $year = isset($_GET['tahun_graph_line_1'])?$_GET['tahun_graph_line_1']: date('Y');
            $query = "SELECT MONTH(tgl_masuk) AS bulan, COUNT(no_mr) AS total FROM tc_kunjungan WHERE YEAR(tgl_masuk)=".$year." GROUP BY MONTH(tgl_masuk)";   
            $fields = array('Jumlah_Pasien'=>'total');
            $title = '<span style="font-size:13.5px">Grafik Kunjungan Pasien Tahun '.$year.' '.COMP_LONG.'</span>';
            $subtitle = 'Source: RSSM - SIRS';
            /*excecute query*/
            $data = $this->db->query($query)->result_array();
        }

        if($params['prefix']==3){
            $query = "SELECT TOP 10 YEAR(tc_registrasi.tgl_jam_masuk) AS tahun, mt_perusahaan.nama_perusahaan as name, COUNT(no_registrasi) as total FROM tc_registrasi LEFT JOIN mt_perusahaan ON mt_perusahaan.kode_perusahaan=tc_registrasi.kode_perusahaan WHERE YEAR(tc_registrasi.tgl_jam_masuk)=".date('Y')." GROUP BY tc_registrasi.kode_perusahaan, YEAR(tc_registrasi.tgl_jam_masuk), mt_perusahaan.nama_perusahaan ORDER BY COUNT(no_registrasi) DESC";  
            $fields = array('name' => 'total');
            $title = '<span style="font-size:13.5px">Persentase Perusahaan Asuransi Aktif</span>';
            $subtitle = 'Source: RSSM - SIRS';
            /*excecute query*/
            $data = $this->db->query($query)->result_array();
        }

        if($params['prefix']==4){
            $data = array();
            // periode
            $this->_main_query();   
            $this->db->select('b.no_kunjungan, d.nama_bagian');
            $this->db->select('DAY(tgl_masuk) as tgl'); 
            if (isset($_GET['jenis_kunjungan']) AND $_GET['jenis_kunjungan'] != 'all') {
                if (isset($_GET['jenis_kunjungan']) AND $_GET['jenis_kunjungan'] == 'rj') {
                    $this->db->where('SUBSTRING(b.kode_bagian, 0, 3) != '."'06'".'');
                    $this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
                    FROM tc_kunjungan a
                    where SUBSTRING(a.kode_bagian_tujuan, 0, 3) != '."'03'".' AND MONTH(tgl_masuk) = '.$_GET['bulan'].' AND YEAR(tgl_masuk) = '.$_GET['tahun'].' AND a.status_batal is null   )');
                    
                }

                if (isset($_GET['jenis_kunjungan']) AND $_GET['jenis_kunjungan'] == 'ri') {
                    $this->db->where('SUBSTRING(b.kode_bagian, 0, 3) = '."'03'".'');
                    $this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
                    FROM ri_tc_rawatinap a
                    where MONTH(tgl_masuk) = '.$_GET['bulan'].' AND YEAR(tgl_masuk) = '.$_GET['tahun'].' )');
                }
            }else{
                $this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
                    FROM tc_kunjungan a
                    where MONTH(tgl_masuk) = '.$_GET['bulan'].' AND YEAR(tgl_masuk) = '.$_GET['tahun'].' AND a.status_batal is null )');
            }
            $this->db->group_by('DAY(tgl_masuk)');
            $this->db->order_by('d.nama_bagian ASC');
            $this->db->group_by('b.no_kunjungan, d.nama_bagian');
            $prd_dt = $this->db->get();
            // echo '<pre>';print_r($this->db->last_query());die;
            $getData = [];
            foreach ($prd_dt->result() as $key => $value) {
                $getData[$value->nama_bagian][] = $value;
            }

            
            $data = array(
                'prd_dt' => $getData,
            );
            // echo '<pre>';print_r($data);die;
            $fields = array();
            $title = '<span style="font-size: 14px">Rekapitulasi Kunjungan Detail Harian Berdasarkan Unit/Bagian<br>Bulan <b>'.$this->tanggal->getBulan($_GET['bulan']).'</b> Tahun <b>'.$_GET['tahun'].'</b></span>';
            $subtitle = 'Source: RSSM - SIRS';
        }

        if($params['prefix']==5){
            $data = array();
            // periode
            $this->_main_query(); 
            $this->db->select('b.no_kunjungan, d.nama_bagian');  
            $this->db->join('mt_master_pasien e ', 'e.no_mr=b.no_mr','left');
            $this->db->select('b.no_mr, e.nama_pasien, c.tgl_masuk');   
            if(isset($_GET['jenis_kunjungan']) AND $_GET['jenis_kunjungan'] != 'all') {
                if (isset($_GET['jenis_kunjungan']) AND $_GET['jenis_kunjungan'] == 'rj') {
                    $this->db->where('SUBSTRING(b.kode_bagian, 0, 3) != '."'06'".'');
                    $this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
                    FROM tc_kunjungan a
                    where SUBSTRING(a.kode_bagian_tujuan, 0, 3) != '."'03'".' AND CAST(tgl_masuk as DATE) BETWEEN '."'".$_GET['from_tgl']."'".' AND '."'".$_GET['to_tgl']."'".' AND a.status_batal is null   )');
                }

                if (isset($_GET['jenis_kunjungan']) AND $_GET['jenis_kunjungan'] == 'ri') {
                    $this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
                    FROM ri_tc_rawatinap a
                    where CAST(tgl_masuk as DATE) BETWEEN '."'".$_GET['from_tgl']."'".' AND '."'".$_GET['to_tgl']."'".'  )');
                }
            }else{
                $this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
                    FROM tc_kunjungan a
                    where CAST(tgl_masuk as DATE) BETWEEN '."'".$_GET['from_tgl']."'".' AND '."'".$_GET['to_tgl']."'".' AND a.status_batal is null   )');
            }
            
            $this->db->group_by('e.nama_pasien, b.no_mr, c.tgl_masuk');
            $this->db->order_by('d.nama_bagian ASC');
            $this->db->group_by('b.no_kunjungan, d.nama_bagian');
            $prd_dt = $this->db->get();
            // echo '<pre>';print_r($this->db->last_query());die;
            $getData = [];
            foreach ($prd_dt->result() as $key => $value) {
                $getData[$value->nama_bagian][] = $value;
            }

            
            $data = array(
                'prd_dt' => $getData,
            );
            // echo '<pre>';print_r($data);die;
            $fields = array();
            $title = '<span style="font-size: 16px">Rekapitulasi Kunjungan Berdasarkan Nama Pasien<br>Periode <b>'.$this->tanggal->formatDateDmy($_GET['from_tgl']).'</b> s.d <b>'.$this->tanggal->formatDateDmy($_GET['to_tgl']).'</b></span>';
            $subtitle = 'Source: RSSM - SIRS';
        }

        if($params['prefix']==6){
            $data = array();
            // periode
            $this->_main_query();   
            $this->db->select('b.no_kunjungan, d.nama_bagian');
            $this->db->join('mt_perusahaan e ', 'e.kode_perusahaan=b.kode_perusahaan','left');
            $this->db->select('b.kode_perusahaan, e.nama_perusahaan, c.tgl_masuk'); 
            if(isset($_GET['jenis_kunjungan']) AND $_GET['jenis_kunjungan'] != 'all') {
                if (isset($_GET['jenis_kunjungan']) AND $_GET['jenis_kunjungan'] == 'rj') {
                    $this->db->where('SUBSTRING(b.kode_bagian, 0, 3) != '."'06'".'');
                    $this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
                    FROM tc_kunjungan a
                    where SUBSTRING(a.kode_bagian_tujuan, 0, 3) != '."'03'".' AND CAST(tgl_masuk as DATE) BETWEEN '."'".$_GET['from_tgl']."'".' AND '."'".$_GET['to_tgl']."'".' AND a.status_batal is null   )');
                }

                if (isset($_GET['jenis_kunjungan']) AND $_GET['jenis_kunjungan'] == 'ri') {
                    $this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
                    FROM ri_tc_rawatinap a
                    where CAST(tgl_masuk as DATE) BETWEEN '."'".$_GET['from_tgl']."'".' AND '."'".$_GET['to_tgl']."'".'  )');
                }
            }else{
                $this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
                    FROM tc_kunjungan a
                    where CAST(tgl_masuk as DATE) BETWEEN '."'".$_GET['from_tgl']."'".' AND '."'".$_GET['to_tgl']."'".' AND a.status_batal is null   )');
            }
            
            $this->db->group_by('e.nama_perusahaan, b.kode_perusahaan, c.tgl_masuk');
            $this->db->group_by('b.no_kunjungan, d.nama_bagian');
            $this->db->order_by('e.nama_perusahaan ASC');
            $prd_dt = $this->db->get();
            // echo '<pre>';print_r($this->db->last_query());die;
            $getData = [];
            foreach ($prd_dt->result() as $key => $value) {
                // nama_perusahaan
                $nama_perusahaan = ($value->nama_perusahaan != '')?$value->nama_perusahaan:'UMUM';
                $getData[$nama_perusahaan][] = $value->total;
            }

            // foreach ($prd_dt->result() as $key => $value) {
            //  // nama_perusahaan
            //  $no_kunjungan = ($value->no_kunjungan != '')?$value->no_kunjungan:'UMUM';
            //  $getData[$no_kunjungan][] = $value;
            // }

            // foreach ($getData as $k1 => $v1) {
            //  foreach ($v1 as $k2 => $v2) {
            //      $nama_perusahaan = ($value->nama_perusahaan != '')?$value->nama_perusahaan:'UMUM';
            //      $arr_ttl[$k1][] = $v2->total;
            //  }
            //  $getTtl[$k1] = array_sum($arr_ttl[$k1]);
            //  $getCount[$k1] = ;
            //  $getAsuransi[$k1] = array('nama_perusahaan' => $v1[0]->nama_perusahaan, 'total_kunjungan' => count($arr_ttl[$k1]), 'total_biaya' => array_sum($arr_ttl[$k1]));
            // }

            foreach ($getData as $k => $v) {
                $resData[$k] = array('total_biaya' => array_sum($getData[$k]), 'total_kunjungan' => count($getData[$k]));
            }
            
            $data = array(
                'prd_dt' => $resData,
            );
            // echo '<pre>';print_r($getTtl);die;

            $fields = array();
            $title = '<span style="font-size: 16px">Rekapitulasi Kunjungan Berdasarkan Asuransi<br>Periode <b>'.$this->tanggal->formatDateDmy($_GET['from_tgl']).'</b> s.d <b>'.$this->tanggal->formatDateDmy($_GET['to_tgl']).'</b></span>';
            $subtitle = 'Source: RSSM - SIRS';
        }

        if($params['prefix']==7){
            $data = array();
            // periode
            $this->db->select('SUM(bill_dr1) as total_bill_dr, SUM(bill_dr2) as total_bill_dr2, kode_dokter1, kode_dokter2');
            $this->db->select('b.no_kunjungan, d.nama_bagian');
            $this->_main_query();   
            $this->db->select('c.tgl_masuk, c.kode_dokter');
            $this->db->where('c.kode_dokter != 0');
            $this->db->where('b.jenis_tindakan IN (3,4,12,14)');
            if(isset($_GET['jenis_kunjungan']) AND $_GET['jenis_kunjungan'] != 'all') {
                if (isset($_GET['jenis_kunjungan']) AND $_GET['jenis_kunjungan'] == 'rj') {
                    $this->db->where('SUBSTRING(b.kode_bagian, 0, 3) != '."'06'".'');
                    $this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
                    FROM tc_kunjungan a
                    where SUBSTRING(a.kode_bagian_tujuan, 0, 3) != '."'03'".' AND CAST(tgl_masuk as DATE) BETWEEN '."'".$_GET['from_tgl']."'".' AND '."'".$_GET['to_tgl']."'".' AND a.status_batal is null   )');
                }

                if (isset($_GET['jenis_kunjungan']) AND $_GET['jenis_kunjungan'] == 'ri') {
                    $this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
                    FROM ri_tc_rawatinap a
                    where CAST(tgl_masuk as DATE) BETWEEN '."'".$_GET['from_tgl']."'".' AND '."'".$_GET['to_tgl']."'".'  )');
                }
            }else{
                $this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
                    FROM tc_kunjungan a
                    where CAST(tgl_masuk as DATE) BETWEEN '."'".$_GET['from_tgl']."'".' AND '."'".$_GET['to_tgl']."'".' AND a.status_batal is null   )');
            }
            $this->db->group_by('b.no_kunjungan, d.nama_bagian');
            $this->db->group_by('c.tgl_masuk, kode_dokter1, kode_dokter2, c.kode_dokter');
            $prd_dt = $this->db->get();
            // echo '<pre>';print_r($this->db->last_query());die;
            $getData = [];
            foreach ($prd_dt->result() as $key => $value) {
                $getData[$value->kode_dokter][] = $value;
            }
            
            $data = array(
                'prd_dt' => $getData,
            );
            // echo '<pre>';print_r($getData);die;

            $fields = array();
            $title = '<span style="font-size: 16px">Rekapitulasi Kinerja Dokter Berdasarkan Kunjungan Periode <b>'.$this->tanggal->formatDateDmy($_GET['from_tgl']).'</b> s.d <b>'.$this->tanggal->formatDateDmy($_GET['to_tgl']).'</b></span>';
            $subtitle = 'Source: RSSM - SIRS';
        }

        if($params['prefix']==8){
            $data = array();
            // periode
            $this->_main_query();  
            $this->db->select('b.no_mr'); 
            $this->db->select("CASE 
                                    WHEN SUBSTRING(b.kode_bagian, 1, 2) = '01' THEN 'Poli/Klinik'
                                    WHEN SUBSTRING(b.kode_bagian, 1, 2) = '03' THEN 'Rawat Inap'
                                    WHEN SUBSTRING(b.kode_bagian, 1, 2) = '05' THEN 'Penunjang Medis'
                                    WHEN SUBSTRING(b.kode_bagian, 1, 2) = '02' THEN 'IGD'
                                    WHEN SUBSTRING(b.kode_bagian, 1, 2) = '06' THEN 'Farmasi'
                                END as 'nama_bagian'"); 
            $this->db->select('DAY(tgl_masuk) as tgl'); 
            if (isset($_GET['jenis_kunjungan']) AND $_GET['jenis_kunjungan'] != 'all') {
                if (isset($_GET['jenis_kunjungan']) AND $_GET['jenis_kunjungan'] == 'rj') {
                    $this->db->where('SUBSTRING(b.kode_bagian, 0, 3) != '."'06'".'');
                    $this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
                    FROM tc_kunjungan a
                    where SUBSTRING(a.kode_bagian_tujuan, 0, 3) != '."'03'".' AND MONTH(tgl_masuk) = '.$_GET['bulan'].' AND YEAR(tgl_masuk) = '.$_GET['tahun'].' AND a.status_batal is null   )');
                    
                }

                if (isset($_GET['jenis_kunjungan']) AND $_GET['jenis_kunjungan'] == 'ri') {
                    $this->db->where('SUBSTRING(b.kode_bagian, 0, 3) = '."'03'".'');
                    $this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
                    FROM ri_tc_rawatinap a
                    where MONTH(tgl_masuk) = '.$_GET['bulan'].' AND YEAR(tgl_masuk) = '.$_GET['tahun'].' )');
                }
            }else{
                $this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
                    FROM tc_kunjungan a
                    where MONTH(tgl_masuk) = '.$_GET['bulan'].' AND YEAR(tgl_masuk) = '.$_GET['tahun'].' AND a.status_batal is null )');
            }
            $this->db->group_by('DAY(tgl_masuk), b.kode_bagian, b.no_mr');
            $prd_dt = $this->db->get();
            $getData = [];
            foreach ($prd_dt->result() as $key => $value) {
                $getData[$value->nama_bagian][] = $value;
            }

            foreach ($prd_dt->result() as $key => $value) {
                $getDataRI[$value->no_mr][] = $value;
            }
            $data = array(
                'data_ri' => $getDataRI,
                'prd_dt' => $getData,
                'jenis_kunjungan' => $_GET['jenis_kunjungan'],
            );
            // echo '<pre>';print_r($data);die;
            $fields = array();
            $title = '<span style="font-size: 14px">Rekapitulasi Detail Harian Berdasarkan Jumlah Pasien<br>Bulan <b>'.$this->tanggal->getBulan($_GET['bulan']).'</b> Tahun <b>'.$_GET['tahun'].'</b></span>';
            $subtitle = 'Source: RSSM - SIRS';
        }

        if($params['prefix']==9){
            // periode
            $this->db->select('b.jenis_tindakan, e.jenis_tindakan as nama_jenis_tindakan');
            $this->db->select('CAST ( SUM ( bill_dr1 ) AS INT ) AS bill_dr1,
            CAST ( SUM ( bill_dr2 ) AS INT ) AS bill_dr2,
            CAST ( SUM ( bill_rs ) AS INT ) AS bill_rs,
            CAST ( SUM ( bhp ) AS INT ) AS bhp,
            CAST ( SUM ( kamar_tindakan ) AS INT ) AS kamar_tindakan,
            CAST ( SUM ( pendapatan_rs ) AS INT ) AS pendapatan_rs,
            CAST ( SUM ( biaya_lain ) AS INT ) AS biaya_lain,
            CAST ( SUM ( obat ) AS INT ) AS obat,
            CAST ( SUM ( alkes ) AS INT ) AS alkes,
            CAST ( SUM ( alat_rs ) AS INT ) AS alat_rs,
            CAST ( SUM ( adm ) AS INT ) AS adm,
            CAST ( SUM ( overhead ) AS INT ) AS overhead');
            $this->db->join('mt_jenis_tindakan e ','e.kode_jenis_tindakan=b.jenis_tindakan','left');
            $this->_main_query();  
            if(isset($_GET['jenis_kunjungan']) AND $_GET['jenis_kunjungan'] != 'all') {
                if (isset($_GET['jenis_kunjungan']) AND $_GET['jenis_kunjungan'] == 'rj') {
                    $this->db->where('SUBSTRING(b.kode_bagian, 0, 3) != '."'06'".'');       
                    $this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
                    FROM tc_kunjungan a
                    where SUBSTRING(a.kode_bagian_tujuan, 0, 3) != '."'03'".' AND MONTH(tgl_masuk) = '.date('m').' AND YEAR(tgl_masuk) = '.date('Y').' AND a.status_batal is null)');           
                }

                if (isset($_GET['jenis_kunjungan']) AND $_GET['jenis_kunjungan'] == 'ri') { 
                    $this->db->where('SUBSTRING(b.kode_bagian, 0, 3) = '."'03'".'');    
                    $this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
                    FROM ri_tc_rawatinap a
                    where MONTH(tgl_masuk) = '.date('m').' AND YEAR(tgl_masuk) = '.date('Y').' )');         
                }
            }else{
                $this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
                    FROM tc_kunjungan a
                    where MONTH(tgl_masuk) = '.date('m').' AND YEAR(tgl_masuk) = '.date('Y').' AND a.status_batal is null)');       
            }

            $this->db->where('e.jenis_tindakan IS NOT NULL');
            $this->db->group_by('b.jenis_tindakan, e.jenis_tindakan');
            $this->db->order_by('e.jenis_tindakan', 'ASC');

            $exc_query = $this->db->get();

            // echo '<pre>';print_r($this->db->last_query());die;

            $data = array(
                'result' => $exc_query->result(),
            );
            
            $fields = array();
            $title = '<span style="font-size: 14px">Rekapitulasi Data Transaksi Berdasarkan Jenis Transaksi<br>Bulan <b>'.$this->tanggal->getBulan($_GET['bulan']).'</b> Tahun <b>'.$_GET['tahun'].'</b></span>';
            $subtitle = 'Source: RSSM - SIRS';
        }

        // echo '<pre>';print_r($data);die;
        /*find and set type chart*/
        $chart = $this->graph_master->chartTypeData($params['TypeChart'], $fields, $params, $data);
        $chart_data = array(
            'title'     => $title,
            'subtitle'  => $subtitle,
            'xAxis'     => isset($chart['xAxis'])?$chart['xAxis']:'',
            'series'    => isset($chart['series'])?$chart['series']:'',
            );

        return $chart_data;
        
    }

    function get_detail_data()
    {
        $data = array();

        $this->db->group_by('b.no_kunjungan, b.kode_perusahaan, SUBSTRING(b.kode_bagian, 1, 2)');

        if($_GET['flag'] == 'periode'){

            // periode
            $this->db->select('b.no_kunjungan, b.kode_perusahaan, SUBSTRING(b.kode_bagian, 1, 2) as kode_unit');
            $this->_main_query();
            if(isset($_GET['jenis_kunjungan']) AND $_GET['jenis_kunjungan'] != 'all') {
                if (isset($_GET['jenis_kunjungan']) AND $_GET['jenis_kunjungan'] == 'rj') {
                    $this->db->where('SUBSTRING(b.kode_bagian, 0, 3) != '."'06'".'');
                    $this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
                    FROM tc_kunjungan a
                    where SUBSTRING(a.kode_bagian_tujuan, 0, 3) != '."'03'".' AND CAST(tgl_masuk as DATE) BETWEEN '."'".$_GET['from_tgl']."'".' AND '."'".$_GET['to_tgl']."'".' AND a.status_batal is null   )');
                }

                if (isset($_GET['jenis_kunjungan']) AND $_GET['jenis_kunjungan'] == 'ri') {
                    $this->db->where('SUBSTRING(b.kode_bagian, 0, 3) = '."'03'".'');
                    $this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
                    FROM ri_tc_rawatinap a
                    where CAST(tgl_masuk as DATE) BETWEEN '."'".$_GET['from_tgl']."'".' AND '."'".$_GET['to_tgl']."'".'  )');
                }
            }else{
                $this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
                    FROM tc_kunjungan a
                    where CAST(tgl_masuk as DATE) BETWEEN '."'".$_GET['from_tgl']."'".' AND '."'".$_GET['to_tgl']."'".' AND a.status_batal is null   )');
            }
            
            $prd_dt = $this->db->get();
            
            $title = 'PERIODE, '.$this->tanggal->formatDateDmy($_GET['from_tgl']).' s/d '.$this->tanggal->formatDateDmy($_GET['to_tgl']).' ';
        }

        if($_GET['flag'] == 'day'){
            $this->db->select('b.kode_perusahaan, SUBSTRING(b.kode_bagian, 1, 2) as kode_unit');
            $this->_main_query();
            if(isset($_GET['jenis_kunjungan']) AND $_GET['jenis_kunjungan'] != 'all') {
                if (isset($_GET['jenis_kunjungan']) AND $_GET['jenis_kunjungan'] == 'rj') {
                    $this->db->where('SUBSTRING(b.kode_bagian, 0, 3) != '."'06'".'');       
                    $this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
                    FROM tc_kunjungan a
                    where SUBSTRING(a.kode_bagian_tujuan, 0, 3) != '."'03'".' AND CAST(tgl_masuk as DATE) = '."'".date('Y-m-d')."'".' AND a.status_batal is null )');           
                }

                if (isset($_GET['jenis_kunjungan']) AND $_GET['jenis_kunjungan'] == 'ri') { 
                    $this->db->where('SUBSTRING(b.kode_bagian, 0, 3) = '."'03'".'');    
                    $this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
                    FROM ri_tc_rawatinap a
                    where CAST(tgl_masuk as DATE) = '."'".date('Y-m-d')."'".' )');          
                }
            }else{
                $this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
                    FROM tc_kunjungan a
                    where CAST(tgl_masuk as DATE) = '."'".date('Y-m-d')."'".' AND a.status_batal is null )');   
            }
            $prd_dt = $this->db->get();

            $title = 'HARIAN, '.date('d/m/Y').'';
        }

        if($_GET['flag'] == 'month'){
            $this->db->select('b.kode_perusahaan, SUBSTRING(b.kode_bagian, 1, 2) as kode_unit');
            $this->_main_query();
            if(isset($_GET['jenis_kunjungan']) AND $_GET['jenis_kunjungan'] != 'all') {
                if (isset($_GET['jenis_kunjungan']) AND $_GET['jenis_kunjungan'] == 'rj') {
                    $this->db->where('SUBSTRING(b.kode_bagian, 0, 3) != '."'06'".'');       
                    $this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
                    FROM tc_kunjungan a
                    where SUBSTRING(a.kode_bagian_tujuan, 0, 3) != '."'03'".' AND MONTH(tgl_masuk) = '.date('m').' AND YEAR(tgl_masuk) = '.date('Y').' AND a.status_batal is null)');           
                }

                if (isset($_GET['jenis_kunjungan']) AND $_GET['jenis_kunjungan'] == 'ri') { 
                    $this->db->where('SUBSTRING(b.kode_bagian, 0, 3) = '."'03'".'');    
                    $this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
                    FROM ri_tc_rawatinap a
                    where MONTH(tgl_masuk) = '.date('m').' AND YEAR(tgl_masuk) = '.date('Y').' )');         
                }
            }else{
                $this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
                    FROM tc_kunjungan a
                    where MONTH(tgl_masuk) = '.date('m').' AND YEAR(tgl_masuk) = '.date('Y').' AND a.status_batal is null)');       
            }
            $prd_dt = $this->db->get();

            $title = 'BULANAN, '.strtoupper($this->tanggal->getBulan(date('m'))).'';
        }

        if($_GET['flag'] == 'year'){
            $this->db->select('b.kode_perusahaan, SUBSTRING(b.kode_bagian, 1, 2) as kode_unit');
            $this->_main_query();
            if(isset($_GET['jenis_kunjungan']) AND $_GET['jenis_kunjungan'] != 'all') {
                if (isset($_GET['jenis_kunjungan']) AND $_GET['jenis_kunjungan'] == 'rj') {
                    $this->db->where('SUBSTRING(b.kode_bagian, 0, 3) != '."'06'".'');       
                    $this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
                    FROM tc_kunjungan a
                    where SUBSTRING(a.kode_bagian_tujuan, 0, 3) != '."'03'".' AND YEAR(tgl_masuk) = '.date('Y').' AND a.status_batal is null)');            
                }

                if (isset($_GET['jenis_kunjungan']) AND $_GET['jenis_kunjungan'] == 'ri') { 
                    $this->db->where('SUBSTRING(b.kode_bagian, 0, 3) = '."'03'".'');    
                    $this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
                    FROM ri_tc_rawatinap a
                    where YEAR(tgl_masuk) = '.date('Y').' )');          
                }
            }else{
                $this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
                    FROM tc_kunjungan a
                    where YEAR(tgl_masuk) = '.date('Y').' AND a.status_batal is null)');
            }
            $prd_dt = $this->db->get();

            $title = 'TAHUNAN, '.date('Y').'';
        }
        
        // split by unit
        // print_r($this->db->last_query());die;
        $data['flag'] = $_GET['flag'];
        $data['title'] = $title;
        // kunjungan
        $data['result'] = $prd_dt->result();

        return $data;
    }

    function get_detail_data_unit()
    {
        $data = array();
        $this->db->select('b.no_kunjungan, b.kode_bagian, d.nama_bagian, SUBSTRING(b.kode_bagian, 1, 2) as kode_unit');
        $this->_main_query();   
        $this->db->where('SUBSTRING(b.kode_bagian, 1, 2) = '."'".$_GET['kode']."'".' ');
        $this->db->group_by('b.no_kunjungan, b.kode_bagian, d.nama_bagian, SUBSTRING(b.kode_bagian, 1, 2)');
        if($_GET['flag'] == 'periode'){

            // periode
            if(isset($_GET['jenis_kunjungan']) AND $_GET['jenis_kunjungan'] != 'all') {
                if (isset($_GET['jenis_kunjungan']) AND $_GET['jenis_kunjungan'] == 'rj') {
                    $this->db->where('SUBSTRING(b.kode_bagian, 0, 3) != '."'06'".'');
                    $this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
                    FROM tc_kunjungan a
                    where SUBSTRING(a.kode_bagian_tujuan, 0, 3) != '."'03'".' AND CAST(tgl_masuk as DATE) BETWEEN '."'".$_GET['from_tgl']."'".' AND '."'".$_GET['to_tgl']."'".' AND a.status_batal is null   )');
                }

                if (isset($_GET['jenis_kunjungan']) AND $_GET['jenis_kunjungan'] == 'ri') {
                    $this->db->where('SUBSTRING(b.kode_bagian, 0, 3) = '."'03'".'');
                    $this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
                    FROM ri_tc_rawatinap a
                    where CAST(tgl_masuk as DATE) BETWEEN '."'".$_GET['from_tgl']."'".' AND '."'".$_GET['to_tgl']."'".'  )');
                }
            }else{
                $this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
                    FROM tc_kunjungan a
                    where CAST(tgl_masuk as DATE) BETWEEN '."'".$_GET['from_tgl']."'".' AND '."'".$_GET['to_tgl']."'".' AND a.status_batal is null   )');
            }

            $prd_dt = $this->db->get();

            $title = 'PERIODE, '.$this->tanggal->formatDateDmy($_GET['from_tgl']).' s/d '.$this->tanggal->formatDateDmy($_GET['to_tgl']).' ';
        }

        if($_GET['flag'] == 'day'){         
            if(isset($_GET['jenis_kunjungan']) AND $_GET['jenis_kunjungan'] != 'all') {
                if (isset($_GET['jenis_kunjungan']) AND $_GET['jenis_kunjungan'] == 'rj') {
                    $this->db->where('SUBSTRING(b.kode_bagian, 0, 3) != '."'06'".'');       
                    $this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
                    FROM tc_kunjungan a
                    where SUBSTRING(a.kode_bagian_tujuan, 0, 3) != '."'03'".' AND CAST(tgl_masuk as DATE) = '."'".date('Y-m-d')."'".' AND a.status_batal is null )');           
                }

                if (isset($_GET['jenis_kunjungan']) AND $_GET['jenis_kunjungan'] == 'ri') { 
                    $this->db->where('SUBSTRING(b.kode_bagian, 0, 3) = '."'03'".'');    
                    $this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
                    FROM ri_tc_rawatinap a
                    where CAST(tgl_masuk as DATE) = '."'".date('Y-m-d')."'".' )');          
                }
            }else{
                $this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
                    FROM tc_kunjungan a
                    where CAST(tgl_masuk as DATE) = '."'".date('Y-m-d')."'".' AND a.status_batal is null )');   
            }
            $prd_dt = $this->db->get();

            $title = 'HARIAN, '.date('d/m/Y').'';
        }

        if($_GET['flag'] == 'month'){           
            if(isset($_GET['jenis_kunjungan']) AND $_GET['jenis_kunjungan'] != 'all') {
                if (isset($_GET['jenis_kunjungan']) AND $_GET['jenis_kunjungan'] == 'rj') {
                    $this->db->where('SUBSTRING(b.kode_bagian, 0, 3) != '."'06'".'');       
                    $this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
                    FROM tc_kunjungan a
                    where SUBSTRING(a.kode_bagian_tujuan, 0, 3) != '."'03'".' AND MONTH(tgl_masuk) = '.date('m').' AND YEAR(tgl_masuk) = '.date('Y').' AND a.status_batal is null)');           
                }

                if (isset($_GET['jenis_kunjungan']) AND $_GET['jenis_kunjungan'] == 'ri') { 
                    $this->db->where('SUBSTRING(b.kode_bagian, 0, 3) = '."'03'".'');    
                    $this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
                    FROM ri_tc_rawatinap a
                    where MONTH(tgl_masuk) = '.date('m').' AND YEAR(tgl_masuk) = '.date('Y').' )');         
                }
            }else{
                $this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
                    FROM tc_kunjungan a
                    where MONTH(tgl_masuk) = '.date('m').' AND YEAR(tgl_masuk) = '.date('Y').' AND a.status_batal is null)');       
            }
            
            $prd_dt = $this->db->get();

            $title = 'BULANAN, '.strtoupper($this->tanggal->getBulan(date('m'))).'';
        }

        if($_GET['flag'] == 'year'){        
            if(isset($_GET['jenis_kunjungan']) AND $_GET['jenis_kunjungan'] != 'all') {
                if (isset($_GET['jenis_kunjungan']) AND $_GET['jenis_kunjungan'] == 'rj') {
                    $this->db->where('SUBSTRING(b.kode_bagian, 0, 3) != '."'06'".'');       
                    $this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
                    FROM tc_kunjungan a
                    where SUBSTRING(a.kode_bagian_tujuan, 0, 3) != '."'03'".' AND YEAR(tgl_masuk) = '.date('Y').' AND a.status_batal is null)');            
                }

                if (isset($_GET['jenis_kunjungan']) AND $_GET['jenis_kunjungan'] == 'ri') {     
                    $this->db->where('SUBSTRING(b.kode_bagian, 0, 3) = '."'03'".'');
                    $this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
                    FROM ri_tc_rawatinap a
                    where YEAR(tgl_masuk) = '.date('Y').' )');          
                }
            }else{
                $this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
                    FROM tc_kunjungan a
                    where YEAR(tgl_masuk) = '.date('Y').' AND a.status_batal is null)');
            }
            $prd_dt = $this->db->get();

            $title = 'TAHUNAN, '.date('Y').'';
        }

        // split by unit
        // print_r($this->db->last_query());die;
        $data['flag'] = $_GET['flag'];
        $data['title'] = $title;
        // kunjungan
        $data['result'] = $prd_dt->result();

        return $data;
    }

    function get_detail_data_pasien()
    {
        $data = array();
        $this->db->select('e.nama_pasien, b.no_mr');
        $this->db->join('mt_master_pasien e ','e.no_mr=b.no_mr','left');
        $this->_main_query();   
        $this->db->where('b.kode_bagian = '."'".$_GET['kode']."'".' ');
        $this->db->group_by('e.nama_pasien, b.no_mr');

        if($_GET['flag'] == 'periode'){

            // periode
            $this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
            FROM tc_kunjungan a
            where CAST(tgl_masuk as DATE) BETWEEN '."'".$_GET['from_tgl']."'".' AND '."'".$_GET['to_tgl']."'".' AND status_batal is null   )');
            $prd_dt = $this->db->get();

            $title = 'PERIODE, '.$this->tanggal->formatDateDmy($_GET['from_tgl']).' s/d '.$this->tanggal->formatDateDmy($_GET['to_tgl']).' ';
        }

        if($_GET['flag'] == 'day'){         
            $this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
            FROM tc_kunjungan a
            where CAST(tgl_masuk as DATE) = '."'".date('Y-m-d')."'".' )');
            $prd_dt = $this->db->get();

            $title = 'HARIAN, '.date('d/m/Y').'';
        }

        if($_GET['flag'] == 'month'){           
            $this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
            FROM tc_kunjungan a
            where MONTH(tgl_masuk) = '.date('m').' AND YEAR(tgl_masuk) = '.date('Y').' )');
            $prd_dt = $this->db->get();

            $title = 'BULANAN, '.strtoupper($this->tanggal->getBulan(date('m'))).'';
        }

        if($_GET['flag'] == 'year'){        
            $this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
            FROM tc_kunjungan a
            where YEAR(tgl_masuk) = '.date('Y').' )');
            $prd_dt = $this->db->get();

            $title = 'TAHUNAN, '.date('Y').'';
        }

        // split by unit
        // print_r($this->db->last_query());die;
        $data['flag'] = $_GET['flag'];
        $data['title'] = $title;
        // kunjungan
        $data['result'] = $prd_dt->result();

        return $data;
    }

    function get_detail_data_by_jenis_tindakan()
    {
        $data = array();
        $this->db->select('b.jenis_tindakan, e.jenis_tindakan as nama_jenis_tindakan');
        $this->db->select('CAST ( SUM ( bill_dr1 ) AS INT ) AS bill_dr1,
        CAST ( SUM ( bill_dr2 ) AS INT ) AS bill_dr2, CAST ( SUM ( bill_rs ) AS INT ) AS bill_rs,
        CAST ( SUM ( bhp ) AS INT ) AS bhp,
        CAST ( SUM ( kamar_tindakan ) AS INT ) AS kamar_tindakan,
        CAST ( SUM ( pendapatan_rs ) AS INT ) AS pendapatan_rs,
        CAST ( SUM ( biaya_lain ) AS INT ) AS biaya_lain,
        CAST ( SUM ( obat ) AS INT ) AS obat,
        CAST ( SUM ( alkes ) AS INT ) AS alkes,
        CAST ( SUM ( alat_rs ) AS INT ) AS alat_rs,
        CAST ( SUM ( adm ) AS INT ) AS adm,
        CAST ( SUM ( overhead ) AS INT ) AS overhead');
        $this->db->join('mt_jenis_tindakan e ','e.kode_jenis_tindakan=b.jenis_tindakan','left');
        $this->_main_query();   
        $this->db->where('b.kode_bagian = '."'".$_GET['kode']."'".' ');
        $this->db->group_by('b.jenis_tindakan, e.jenis_tindakan');

        if($_GET['flag'] == 'periode'){

            // periode
            $this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
            FROM tc_kunjungan a
            where CAST(tgl_masuk as DATE) BETWEEN '."'".$_GET['from_tgl']."'".' AND '."'".$_GET['to_tgl']."'".' AND status_batal is null   )');
            $prd_dt = $this->db->get();

            $title = 'PERIODE, '.$this->tanggal->formatDateDmy($_GET['from_tgl']).' s/d '.$this->tanggal->formatDateDmy($_GET['to_tgl']).' ';
        }

        if($_GET['flag'] == 'day'){         
            $this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
            FROM tc_kunjungan a
            where CAST(tgl_masuk as DATE) = '."'".date('Y-m-d')."'".' )');
            $prd_dt = $this->db->get();

            $title = 'HARIAN, '.date('d/m/Y').'';
        }

        if($_GET['flag'] == 'month'){           
            $this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
            FROM tc_kunjungan a
            where MONTH(tgl_masuk) = '.date('m').' AND YEAR(tgl_masuk) = '.date('Y').' )');
            $prd_dt = $this->db->get();

            $title = 'BULANAN, '.strtoupper($this->tanggal->getBulan(date('m'))).'';
        }

        if($_GET['flag'] == 'year'){        
            $this->db->where('b.no_kunjungan IN ( SELECT no_kunjungan
            FROM tc_kunjungan a
            where YEAR(tgl_masuk) = '.date('Y').' )');
            $prd_dt = $this->db->get();

            $title = 'TAHUNAN, '.date('Y').'';
        }

        // split by unit
        // print_r($this->db->last_query());die;
        $data['flag'] = $_GET['flag'];
        $data['title'] = $title;
        // kunjungan
        $data['result'] = $prd_dt->result();

        return $data;
    }


}

