<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Eks_kinerja_dokter_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _main_query(){
        $this->db->select('(SUM(bill_rs) + SUM(bill_dr1) + SUM(bill_dr2) + SUM(bill_dr3)) as total');
        $this->db->from('tc_trans_pelayanan b');
        $this->db->join('tc_kunjungan c', 'c.no_kunjungan=b.no_kunjungan','left');
        $this->db->join('mt_bagian d', 'd.kode_bagian=b.kode_bagian','left');
        
    }

    private function _filter(){
         // default
         if(isset($_GET['tahun']) AND $_GET['tahun'] != ''){
            $this->db->where("YEAR (a.tgl) = ".$_GET['tahun']."");
        }else{
            $this->db->where("YEAR (a.tgl) = ".date('Y')."");
        }

        if(isset($_GET['bulan']) AND $_GET['bulan'] != '0'){
            $this->db->where("MONTH (a.tgl) = ".$_GET['bulan']." ");
        }

        if(isset($_GET['poliklinik']) AND $_GET['poliklinik'] != 0){
            $this->db->where("a.kode_bagian = '".$_GET['poliklinik']."' ");
        }

        if(isset($_GET['select_dokter']) AND $_GET['select_dokter'] != 0){
            $this->db->where("a.kode = '".$_GET['select_dokter']."' ");
        }

        if (isset($_GET['jenis_kunjungan']) AND $_GET['jenis_kunjungan'] != 'all') {
            if (isset($_GET['jenis_kunjungan']) AND $_GET['jenis_kunjungan'] == 'rj') {
                $this->db->where("a.subskode NOT IN ('03') ");
            }

            if (isset($_GET['jenis_kunjungan']) AND $_GET['jenis_kunjungan'] == 'ri') {
                $this->db->where("subskode IN ('03') ");
            }
        }

        if (isset($_GET['penjamin']) AND $_GET['penjamin'] != 'all') {  
            if (isset($_GET['penjamin']) AND $_GET['penjamin'] == 'bpjs') { 
                $this->db->where('kode_perusahaan = 120');
            }

            if (isset($_GET['penjamin']) AND $_GET['penjamin'] == 'asuransi') { 
                $this->db->where('kode_perusahaan NOT IN(120,0)');
            }

            if (isset($_GET['penjamin']) AND $_GET['penjamin'] == 'umum') { 
                $this->db->where('kode_perusahaan = 0');
            }
        }
    }

    function get_content_data($params) {

        if($params['prefix']==1){
            $data = array();
            // subquery select
            $this->db->select('SUM(total) AS total');
            $this->db->from('view_rekap_bill_dr_date');
            $this->db->where('MONTH(tgl)', $_GET['bulan']-1);
            $this->db->where('YEAR(tgl)', $_GET['tahun']);
            $this->db->where('kode = a.kode');
            $this->db->where('kode_bagian = a.kode_bagian');
            $this->db->where('subskode = a.subskode');
            $this->_filter();
            $sub_query_total = $this->db->get_compiled_select();

            // subquery select
            $this->db->select('SUM(total_rupiah) AS total');
            $this->db->from('view_rekap_bill_dr_date');
            $this->db->where('MONTH(tgl)', $_GET['bulan']-1);
            $this->db->where('YEAR(tgl)', $_GET['tahun']);
            $this->db->where('kode = a.kode');
            $this->db->where('kode_bagian = a.kode_bagian');
            $this->db->where('subskode = a.subskode');
            $this->_filter();
            $sub_query_total_rp = $this->db->get_compiled_select();


            $this->db->select('a.*, b.nama_bagian, c.nama_pegawai as nama_dokter');
            $this->db->select('('.$sub_query_total.') as total_last_month');
            $this->db->select('('.$sub_query_total_rp.') as total_rp_last_month');
            $this->db->from('view_rekap_bill_dr_date a');
            $this->_filter();
            $this->db->join('mt_bagian b', 'b.kode_bagian=a.kode_bagian','left');
            $this->db->join('mt_dokter_v c', 'c.kode_dokter=a.kode','left');
            $this->db->order_by('a.kode ASC');

            $result = $this->db->get()->result();
            // echo '<pre>';print_r($this->db->last_query());die;
            
            $getData = [];
            if(empty($_GET['poliklinik']) AND !empty($_GET['select_dokter'])){
                foreach ($result as $key => $value) {
                    $tgl = explode('-', $value->tgl);
                    $getData[$value->nama_dokter][(int)$tgl[2]] = array(
                        'tgl' => $value->tgl,
                        'total' => $value->total,
                        'total_rp' => $value->total_rupiah,
                        'total_last_month' => $value->total_last_month,
                        'total_rp_last_month' => $value->total_rp_last_month,
                    );
                }
            }elseif(!empty($_GET['poliklinik']) AND empty($_GET['select_dokter'])){
                foreach ($result as $key => $value) {
                    $tgl = explode('-', $value->tgl);
                    $getData[$value->nama_bagian][(int)$tgl[2]] = array(
                        'tgl' => $value->tgl,
                        'total' => $value->total,
                        'total_rp' => $value->total_rupiah,
                        'total_last_month' => $value->total_last_month,
                        'total_rp_last_month' => $value->total_rp_last_month,
                    );
                }
            }else{
                foreach ($result as $key => $value) {
                    $tgl = explode('-', $value->tgl);
                    $getData[$value->nama_dokter][$value->nama_bagian][(int)$tgl[2]] = array(
                        'tgl' => $value->tgl,
                        'total' => $value->total,
                        'total_rp' => $value->total_rupiah,
                        'total_last_month' => $value->total_last_month,
                        'total_rp_last_month' => $value->total_rp_last_month,
                    );
                }
            }
            
            // echo '<pre>'; print_r($getData);die;
            // parameter
            unset($_GET['prefix']);
            unset($_GET['TypeChart']);
            unset($_GET['style']);
            unset($_GET['mod']);

            $data = array(
                'result' => $getData,
                'parameter' => http_build_query($_GET) . "\n" ,
            );

            $fields = array();
            $title = '<div class="pull-left"><span style="font-size: 14px !important; font-style: italic"><b>Parameter Query :</b> '.$data['parameter'].'</span></div>';
            $subtitle = 'Source: RSSM - SHS.4.0';

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

}

