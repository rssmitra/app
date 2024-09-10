<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Regon_info_jadwal_cuti_dr_model extends CI_Model {

    var $table = 'tr_jadwal_cuti_dr';
    var $column = array('mt_karyawan.nama_pegawai','mt_bagian.nama_bagian');
    var $select = 'cuti_id, mt_karyawan.nama_pegawai,mt_bagian.nama_bagian, from_tgl, to_tgl, keterangan_cuti, tr_jadwal_cuti_dr.is_active, kode_bag, kode_dr';

    var $order = array('mt_karyawan.nama_pegawai' => 'ASC');

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _main_query(){
        $this->db->select($this->select);
        $this->db->from($this->table);
        $this->db->join('mt_bagian',''.$this->table.'.kode_bag=mt_bagian.kode_bagian','left');
		$this->db->join('mt_karyawan',''.$this->table.'.kode_dr=mt_karyawan.kode_dokter','left');
    }

    private function _get_datatables_query()
    {
        
        $this->_main_query();

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
        return $query->result();
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
            $this->db->where_in(''.$this->table.'.cuti_id',$id);
            $query = $this->db->get();
            return $query->result();
        }else{
            $this->db->where(''.$this->table.'.cuti_id',$id);
            $query = $this->db->get();
            return $query->row();
        }
        
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
        $this->db->where_in(''.$this->table.'.cuti_id', $id);
        return $this->db->delete($this->table);
    }

}
