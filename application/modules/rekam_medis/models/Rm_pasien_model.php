<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Rm_pasien_model extends CI_Model {


    var $table = 'tc_registrasi';
    var $column = array('tc_registrasi.no_sep');
    var $select = 'tgl_jam_masuk, tgl_jam_keluar, tc_registrasi.kode_kelompok, tc_registrasi.kode_perusahaan, no_registrasi, status_registrasi, status_batal, umur, mt_bagian.nama_bagian, nama_pegawai, kode_bagian_masuk, tc_registrasi.no_mr, nama_pasien, no_sep, nama_perusahaan, no_kartu_bpjs, tgl_lhr, jen_kelamin, stat_pasien, diagnosa_rujukan';
    var $order = array('tc_registrasi.no_registrasi' => 'DESC');
    

    public function __construct()
    {
        parent::__construct();
        $this->load->database('default', TRUE);
    }

    private function _main_query(){
        
        $this->db->select($this->select);
        $this->db->from($this->table);
        $this->db->join('mt_bagian', 'mt_bagian.kode_bagian=tc_registrasi.kode_bagian_masuk', 'LEFT');
        $this->db->join('mt_dokter_v', 'mt_dokter_v.kode_dokter=tc_registrasi.kode_dokter', 'LEFT');
        $this->db->join('mt_master_pasien', 'mt_master_pasien.no_mr=tc_registrasi.no_mr', 'LEFT');
        $this->db->join('mt_perusahaan', 'mt_perusahaan.kode_perusahaan=tc_registrasi.kode_perusahaan', 'LEFT');
          
    }

    private function _get_datatables_query()
    {
        
        $this->_main_query();
        $this->db->where('SUBSTRING(kode_bagian_masuk, 1,2) = '."'01'".'');
        $this->db->where('tc_registrasi.tgl_jam_keluar IS NOT NULL');
        
        if(isset($_GET['search_by'])) {

            if (isset($_GET['search_by']) AND $_GET['search_by'] != '' || isset($_GET['keyword']) AND $_GET['keyword'] != '' ) {
                if($_GET['search_by'] == 'nama_pasien'){
                    $this->db->like($_GET['search_by'], $_GET['keyword']);  
                }else{
                    $this->db->where("".$_GET['search_by']." = '".$_GET['keyword']."' " );
                }
            }
    
    
            if (isset($_GET['from_tgl']) AND $_GET['from_tgl'] != '' || isset($_GET['to_tgl']) AND $_GET['to_tgl'] != '') {
                $this->db->where("CAST(tgl_jam_masuk as DATE) BETWEEN '".$_GET['from_tgl']."' AND '".$_GET['to_tgl']."' " );
            }
            
            if (isset($_GET['kode_bagian']) AND $_GET['kode_bagian'] != '' ) {
                $this->db->where("kode_bagian_masuk = '".$_GET['kode_bagian']."' " );
            }

        }else{
            $this->db->where(" CAST(tc_registrasi.tgl_jam_masuk AS DATE) = '".date('Y-m-d')."' " );
        }
        $this->db->group_by($this->select);

        $i = 0;
    
        foreach ($this->column as $item) 
        {
            if($_POST['search']['value'])
                ($i===0) ? $this->db->like($item, $_POST['search']['value']) : $this->db->or_like($item, $_POST['search']['value']);
            $column[$i] = $item;
            $i++;
        }
        
        if(isset($_POST['order']))
        {
            $this->db->order_by($column[$_POST['order']['0']['column']], $_POST['order']['0']['dir']);
        } 
        else if(isset($this->order))
        {
            $order = $this->order;
            $this->db->order_by(key($order), $order[key($order)]);
        }
    }
    
    function get_datatables()
    {
        $this->_get_datatables_query();
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);

        $query = $this->db->get();
        // print_r($this->db->last_query());die;
        return $query->result();
    }

    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        //print_r($query);die;
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->_get_datatables_query();
        return $this->db->count_all_results();
    }

    public function get_by_id($no_registrasi)
    {
        $this->_main_query();
        $this->db->where('no_registrasi', $no_registrasi);
        return $this->db->get()->row();
    }

    public function get_riwayat_pasien_by_id($params){
		return $this->db->get_where('th_riwayat_pasien', array('no_registrasi' => $params->no_registrasi, 'kode_bagian' => $params->kode_bagian_masuk) )->row();
	}
    
}

