<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Global_ws_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
    }

    public function get_data_pasien_by_mr($no_mr){
        return $this->db->get_where('mt_master_pasien', array('no_mr' => $no_mr))->row();
    }

    public function get_data_pasien_relasi($no_mr){
       /* $qry = "SELECT p1.no_mr, p1.nama_pasien, p1.tgl_lhr, p1.almt_ttp_pasien FROM regon_relasi_pasien
                LEFT JOIN mt_master_pasien p1 ON p1.no_mr=regon_relasi_pasien.regon_relasi_pasien_no_mr
                LEFT JOIN mt_master_pasien p2 ON p2.no_mr=regon_relasi_pasien.regon_relasi_pasien_ref_no_mr
                WHERE regon_rp_ref_no_mr='$no_mr'";
        return $this->db->query($qry)->result(); */
        $this->db->select('log_det_no_mr');
        return $this->db->get_where('regon_relasi_pasien', array('regon_rp_ref_no_mr' => $no_mr))->result();
        // $this->db->get_where('regon_relasi_pasien', array('regon_relasi_pasien_ref_no_mr' => $no_mr))->result();
    }

    public function save_reg_relasi($dataexc){
        $this->db->insert('regon_relasi_pasien', $dataexc);
        return $this->db->insert_id();
    }

    public function check_mr($regon_relasi_pasien_no_mr,$regon_relasi_pasien_ref_no_mr){
        $qry = $this->db->get_where('regon_relasi_pasien', array('regon_relasi_pasien_no_mr' => $regon_relasi_pasien_no_mr, 'regon_relasi_pasien_ref_no_mr' => $regon_relasi_pasien_ref_no_mr));
        return $qry->num_rows();
    }

    public function get_klinik()
    {
        # code...
        $this->db->select('kode_bagian, nama_bagian');
        $qry = $this->db->get_where('mt_bagian', array('validasi' => '0100', 'status_aktif' => 1));
        return $qry->result();
    }

    public function get_dokter()
    {
        # code...
        $qry = "SELECT mt_karyawan.id_mt_karyawan, mt_karyawan.nama_pegawai, mt_karyawan.kode_dokter, mt_karyawan.kode_spesialisasi, 
                mt_dokter_bagian.kd_bagian,mt_bagian.nama_bagian FROM mt_karyawan 
                LEFT JOIN mt_dokter_bagian on mt_dokter_bagian.kode_dokter=mt_karyawan.kode_dokter
                LEFT JOIN mt_bagian on mt_bagian.kode_bagian=mt_dokter_bagian.kd_bagian
                where mt_karyawan.kode_dokter IS NOT NULL and mt_karyawan.status_dr=1";
        return $this->db->query($qry)->result();
    }

    public function get_dokter_by_bagian($kode_bag)
    {
        # code...
        $qry = "SELECT mt_karyawan.nama_pegawai, mt_karyawan.kode_dokter FROM mt_karyawan 
                LEFT JOIN mt_dokter_bagian on mt_dokter_bagian.kode_dokter=mt_karyawan.kode_dokter
                LEFT JOIN mt_bagian on mt_bagian.kode_bagian=mt_dokter_bagian.kd_bagian
                where mt_karyawan.kode_dokter IS NOT NULL and mt_karyawan.status_dr=1 and mt_dokter_bagian.kd_bagian='$kode_bag'";
        return $this->db->query($qry)->result();
    }

    public function get_jd_dokter()
    {
        # code...
        $qry = "SELECT tr_jadwal_dokter.jd_kode_dokter,tr_jadwal_dokter.jd_kode_spesialis,tr_jadwal_dokter.jd_hari,tr_jadwal_dokter.jd_jam_mulai,
                tr_jadwal_dokter.jd_jam_selesai,tr_jadwal_dokter.jd_kuota FROM tr_jadwal_dokter
                LEFT JOIN mt_karyawan on mt_karyawan.kode_dokter=tr_jadwal_dokter.jd_kode_dokter
                where mt_karyawan.kode_dokter IS NOT NULL and mt_karyawan.status_dr=1";
        return $this->db->query($qry)->result();
    }

    public function get_jd_dokter_by_kode($kode)
    {
        # code...
        $qry = "SELECT tr_jadwal_dokter.jd_kode_dokter,tr_jadwal_dokter.jd_kode_spesialis,tr_jadwal_dokter.jd_hari,tr_jadwal_dokter.jd_jam_mulai,
                tr_jadwal_dokter.jd_jam_selesai,tr_jadwal_dokter.jd_kuota FROM tr_jadwal_dokter
                LEFT JOIN mt_karyawan on mt_karyawan.kode_dokter=tr_jadwal_dokter.jd_kode_dokter
                where mt_karyawan.kode_dokter IS NOT NULL and mt_karyawan.status_dr=1 and tr_jadwal_dokter.jd_kode_dokter='$kode'";
        return $this->db->query($qry)->result();
    }
    

    public function get_penjamin()
    {
        # code...
        $this->db->select('kode_perusahaan, nama_perusahaan');
        $qry = $this->db->get_where('mt_perusahaan', array('flag_status' => 0));
        return $qry->result();
    }

    public function get_booked($no_mr)
    {
        # code...
       
        $qry = "SELECT regon_booking.regon_booking_kode,regon_booking.regon_booking_tanggal_perjanjian, regon_booking.regon_booking_no_mr, regon_booking.regon_booking_klinik, regon_booking.regon_booking_kode_dokter, regon_booking.regon_booking_hari, regon_booking.regon_booking_jam, regon_booking.regon_booking_penjamin, regon_booking.regon_booking_status, mt_bagian.nama_bagian,p1.nama_pasien,p2.nama_pegawai FROM regon_booking
        LEFT JOIN mt_master_pasien p1 ON p1.no_mr=regon_booking.regon_booking_no_mr
        LEFT JOIN mt_karyawan p2 ON p2.kode_dokter=regon_booking.regon_booking_kode_dokter
        LEFT JOIN mt_bagian on mt_bagian.kode_bagian=regon_booking.regon_booking_klinik
        where regon_booking.regon_booking_no_mr_ref='$no_mr' order by created_date DESC";
        return $this->db->query($qry)->result();
        //$qry = $this->db->get_where('regon_booking', array('regon_booking_keterangan' => $no_mr));
        //return $qry->result();
    }

    public function get_booked_by_date($date)
    {
        # code...
       
        $qry = "SELECT regon_booking.regon_booking_kode FROM regon_booking
        LEFT JOIN mt_master_pasien p1 ON p1.no_mr=regon_booking.regon_booking_no_mr
        LEFT JOIN mt_bagian on mt_bagian.kode_bagian=regon_booking.regon_booking_klinik
        where regon_booking.regon_booking_tanggal_perjanjian='$date'";
        return $this->db->query($qry)->num_rows();
        //$qry = $this->db->get_where('regon_booking', array('regon_booking_keterangan' => $no_mr));
        //return $qry->result();
    }

    public function check_account_by_email($email) {
        $qry = $this->db->get_where('regon_acc_register', array('regon_accreg_email' => $email));
        return $qry->num_rows();
    }

    public function get_global_param($flag)
    {
        # code...
        $qry = $this->db->get_where('global_parameter', array('flag' => $flag));
        return $qry->result();
    }

}
