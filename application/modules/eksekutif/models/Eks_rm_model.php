<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Eks_rm_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _main_query($type='rj'){

        $table = ($type == 'rj') ? 'sensus_rawat_jalan_v' : 'sensus_rawat_inap_v';
        $this->db->from(''.$table.' a');

        if (isset($_GET['penjamin']) AND $_GET['penjamin'] != 'all') {  
            if (isset($_GET['penjamin']) AND $_GET['penjamin'] == 'bpjs') { 
                $this->db->where('a.kode_perusahaan', 120);
            }

            if (isset($_GET['penjamin']) AND $_GET['penjamin'] == 'asuransi') { 
                $this->db->where('a.kode_perusahaan NOT IN(120,0) ');
            }

            if (isset($_GET['penjamin']) AND $_GET['penjamin'] == 'umum') { 
                $this->db->where('a.kode_perusahaan', 0);
            }
        }
 
    }

    function get_content_data($params) {

        /*total klaim berdasarkan nomor sep per tahun existing*/
        /*based query*/
        if($params['prefix']==1){
            $data = array();
            // periode
            $this->_main_query('rj');
            // $this->db->join('mt_perusahaan c', 'c.kode_perusahaan =  a.kode_perusahaan', 'left');
            $this->db->where('MONTH(tgl_masuk)', $_GET['bulan']);
            $this->db->where('YEAR(tgl_masuk)', $_GET['tahun']);
            $this->db->where('a.status_batal is null');
            $sensus_rj = $this->db->get();

            // query diagnosa
            $this->db->select('CAST(diagnosa_akhir AS NVARCHAR(255)) as diagnosa, COUNT(b.no_kunjungan) as total');
            $this->_main_query('rj');
            $this->db->join('pl_th_riwayat_pasien_v b', 'b.no_kunjungan=a.no_kunjungan','left');
            $this->db->where('MONTH(tgl_masuk)', $_GET['bulan']);
            $this->db->where('YEAR(tgl_masuk)', $_GET['tahun']);
            $this->db->where('a.status_batal is null');
            $this->db->group_by('CAST(diagnosa_akhir AS NVARCHAR(255))');
            $this->db->order_by('COUNT(b.no_kunjungan)', 'DESC');
            $diagnosa_rj = $this->db->get();

            $data = array(
                'result' => $sensus_rj->result(),
                'diagnosa' => $diagnosa_rj->result(),
            );

            $fields = array();
            $title = isset($_GET['jenis_kunjungan']) ? '' : '' ;
            $title = '<span style="font-size: 18px; font-weight: bold">SENSUS KUNJUNGAN PASIEN RAWAT JALAN<br>BULAN '.strtoupper($this->tanggal->getBulan($_GET['bulan'])).' TAHUN '.$_GET['tahun'].'</b></span>';
            $subtitle = 'Source: RSSM - SIRS';
        }

        if($params['prefix']==2){
            $data = array();
            // periode
            $this->_main_query('ri');
            $this->db->where('MONTH(tgl_masuk)', $_GET['bulan_ri']);
            $this->db->where('YEAR(tgl_masuk)', $_GET['tahun_ri']);
            $this->db->where('a.status_batal is null');
            $sensus_rj = $this->db->get();
            // echo '<pre>';print_r($this->db->last_query());die;

            // query diagnosa
            $this->db->select('CAST(diagnosa_akhir AS NVARCHAR(255)) as diagnosa, COUNT(b.no_kunjungan) as total');
            $this->_main_query('ri');
            $this->db->join('th_riwayat_pasien b', 'b.no_kunjungan=a.no_kunjungan','left');
            $this->db->where('MONTH(tgl_masuk)', $_GET['bulan_ri']);
            $this->db->where('YEAR(tgl_masuk)', $_GET['tahun_ri']);
            $this->db->where('a.status_batal is null');
            // $this->db->where('CAST(diagnosa_akhir AS NVARCHAR(255)) is not null');
            $this->db->group_by('CAST(diagnosa_akhir AS NVARCHAR(255))');
            $this->db->order_by('COUNT(b.no_kunjungan)', 'DESC');
            $diagnosa_ri = $this->db->get();
            // echo '<pre>';print_r($this->db->last_query());die;

            $data = array(
                'result' => $sensus_rj->result(),
                'diagnosa' => $diagnosa_ri->result(),
            );

            $fields = array();
            $title = isset($_GET['jenis_kunjungan']) ? '' : '' ;
            $title = '<span style="font-size: 18px; font-weight: bold">SENSUS KUNJUNGAN PASIEN RAWAT INAP<br>BULAN '.strtoupper($this->tanggal->getBulan($_GET['bulan'])).' TAHUN '.$_GET['tahun'].'</b></span>';
            $subtitle = 'Source: RSSM - SIRS';
        }

       
        // echo '<pre>';print_r($this->db->last_query());die;
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
            $this->_main_query('rj');
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
            $this->_main_query('rj');
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
            $this->_main_query('rj');
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
            $this->_main_query('rj');
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
        $this->_main_query('rj');   
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
        $this->_main_query('rj');   
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
        $this->_main_query('rj');   
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

