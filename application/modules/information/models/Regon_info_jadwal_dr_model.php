<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Regon_info_jadwal_dr_model extends CI_Model {

    var $table = 'tr_jadwal_dokter';
    var $column = array('tr_jadwal_dokter.jd_hari','tr_jadwal_dokter.jd_jam_mulai','tr_jadwal_dokter.jd_jam_selesai','tr_jadwal_dokter.jd_keterangan','tr_jadwal_dokter.jd_kuota','mt_karyawan.nama_pegawai','mt_bagian.nama_bagian');
    var $select = 'tr_jadwal_dokter.jd_id, tr_jadwal_dokter.jd_kode_dokter,tr_jadwal_dokter.jd_kode_spesialis,tr_jadwal_dokter.jd_hari,tr_jadwal_dokter.jd_jam_mulai,tr_jadwal_dokter.jd_jam_selesai,tr_jadwal_dokter.jd_keterangan,tr_jadwal_dokter.jd_kuota,mt_karyawan.nama_pegawai,mt_bagian.nama_bagian';

    var $order = array('mt_karyawan.nama_pegawai' => 'ASC');

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _main_query(){
        $this->db->select($this->select);
        $this->db->from($this->table);
        $this->db->join('mt_bagian',''.$this->table.'.jd_kode_spesialis=mt_bagian.kode_bagian','left');
		$this->db->join('mt_karyawan',''.$this->table.'.jd_kode_dokter=mt_karyawan.kode_dokter','left');

    }

    private function _get_datatables_query()
    {
        
        $this->_main_query();

        if(isset($_GET['kode'])){
            $this->db->where('jd_kode_spesialis', $_GET['kode']);
        }

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
        return $query->result_array();
    }

    function count_filtered()
    {
        $this->_get_datatables_query();
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all()
    {
        $this->_main_query();
        return $this->db->count_all_results();
    }

    public function get_by_id($id)
    {
        $this->_main_query();
        if(is_array($id)){
            $this->db->where_in(''.$this->table.'.jd_id',$id);
            $query = $this->db->get();
            return $query->result();
        }else{
            $this->db->where(''.$this->table.'.jd_id',$id);
            $query = $this->db->get();
            return $query->row();
        }
        
    }

    public function get_by_kode_booking($code)
    {
        $this->_main_query();
        $this->db->where(''.$this->table.'.tr_jadwal_dokter_kode',$code);
        $query = $this->db->get();
        return $query->row();
    }

    public function save($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    public function update($where, $data)
    {
        $this->db->update($this->table, $data, $where);
        return $this->db->affected_rows();
    }

    public function delete_by_id($id)
    {
        $get_data = $this->get_by_id($id);
        foreach ($get_data as $key => $value) {
            $this->db->where(''.$this->table.'.jd_kode_dokter', $value->jd_kode_dokter);
            $this->db->where(''.$this->table.'.jd_kode_spesialis', $value->jd_kode_spesialis);
            $this->db->delete($this->table);
        }

        return true;
    }

    public function get_jadwal_by_dr_spesialis($data)
    {
        $result = $this->db->order_by('jd_id', 'ASC')->get_where($this->table, array('jd_kode_dokter' => $data->jd_kode_dokter, 'jd_kode_spesialis' => $data->jd_kode_spesialis) );
        return $result->result_array();
    }

    public function is_exist($where){
        $this->_main_query();
        $this->db->where($where);
        return $this->db->get();
    }

    function get_no_booking($dokter,$klinik,$hari){
		
        $this->db->select('mt_master_pasien.no_hp,mt_master_pasien.tlp_almt_ttp');
        $this->db->from('regon_booking');
        $this->db->join('mt_master_pasien','mt_master_pasien.no_mr=regon_booking.regon_booking_no_mr_ref OR mt_master_pasien.no_mr=regon_booking.regon_booking_no_mr');
        $this->db->where('regon_booking.regon_booking_kode_dokter', $dokter);
        $this->db->where('regon_booking.regon_booking_klinik', $klinik);
        $this->db->where('regon_booking.regon_booking_hari', $hari);
        $this->db->where("regon_booking.regon_booking_tanggal_perjanjian >= '".date('Y-m-d')."'");
		
		$data = $this->db->get()->result();

		return $data;
	}

    function get_no_pesanan($dokter,$klinik,$hari){
        
        $this->db->select('mt_master_pasien.no_hp,mt_master_pasien.tlp_almt_ttp');
        $this->db->from('tc_pesanan');
        $this->db->join('mt_master_pasien','mt_master_pasien.no_mr=tc_pesanan.no_mr');
        $this->db->where("DATENAME(weekday, tgl_pesanan) = '".$hari."' ");
        $this->db->where('tc_pesanan.kode_dokter', $dokter);
        $this->db->where('tc_pesanan.no_poli', $klinik);
        $this->db->where("tc_pesanan.tgl_pesanan  >= '".date('Y-m-d')."'");
        		
		$data = $this->db->get()->result();

		return $data;
	}

}
