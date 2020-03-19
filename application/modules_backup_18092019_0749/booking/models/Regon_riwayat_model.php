<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Regon_riwayat_model extends CI_Model {

    var $table = 'regon_booking';
    var $column = array('regon_booking.regon_booking_kode','regon_booking.regon_booking_tanggal_perjanjian','regon_booking.regon_booking_no_mr','regon_booking.regon_booking_hari','regon_booking.regon_booking_jam','regon_booking.regon_booking_keterangan');
    var $select = 'regon_booking.regon_booking_kode,regon_booking.regon_booking_tanggal_perjanjian,regon_booking.regon_booking_no_mr,regon_booking.regon_booking_hari,regon_booking.regon_booking_jam,regon_booking.regon_booking_keterangan,regon_booking.regon_booking_instalasi,regon_booking.regon_booking_klinik,regon_booking.regon_booking_kode_dokter,regon_booking.regon_booking_jenis_penjamin,regon_booking.regon_booking_penjamin,regon_booking.regon_booking_status,regon_booking.regon_booking_urutan,regon_booking.created_date,regon_booking.created_by,regon_booking.log_detail_pasien, regon_booking.log_transaksi, regon_booking.regon_booking_waktu_datang';

    var $order = array('regon_booking.regon_booking_id' => 'DESC', 'regon_booking.updated_date' => 'DESC');

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_riwayat_pasien()
    {
        /*get relasi pasien*/
        $this->db->where('regon_booking_no_mr', $this->session->userdata('user_profile')->no_mr );
        $data = $this->db->get('regon_booking')->result();
        return $data;
    }


    private function _main_query(){
        $this->db->select($this->select);
        $this->db->from($this->table);
        /*check level user*/
        //$this->authuser->filtering_data_by_level_user($this->table, $this->session->userdata('user')->user_id);

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
            $this->db->where_in(''.$this->table.'.regon_booking_id',$id);
            $query = $this->db->get();
            return $query->result();
        }else{
            $this->db->where(''.$this->table.'.regon_booking_id',$id);
            $query = $this->db->get();
            return $query->row();
        }
        
    }

    public function get_by_kode_booking($code)
    {
        $this->_main_query();
        $this->db->where(''.$this->table.'.regon_booking_kode',$code);
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
        $this->db->where_in(''.$this->table.'.regon_booking_id', $id);
        return $this->db->update($this->table, array('is_deleted' => 'Y', 'is_active' => 'N'));
    }

    public function get_pasien_by_relasi($regon_rp_id)
    {
        /*get data pasien*/
        $result = $this->db->get_where('regon_relasi_pasien', array('regon_rp_id' => $regon_rp_id) )->row();
        $data = json_decode($result->log_det_no_mr);

        return $data;
    }

    public function get_log_mr($no_mr)
    {
        /*get data pasien*/
        $result = $this->db->get_where('regon_relasi_pasien', array('regon_rp_no_mr' => $no_mr, 'regon_rp_status_relasi' => 'Owner') )->row();

        return $result->log_det_no_mr;
    }

    public function save_new_pasien($data)
    {
        $this->db->insert('regon_relasi_pasien', $data);
        return $this->db->insert_id();
    }


}
