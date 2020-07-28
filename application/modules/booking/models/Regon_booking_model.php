<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Regon_booking_model extends CI_Model {

    var $table = 'regon_booking';
    var $column = array('regon_booking.regon_booking_kode','regon_booking.regon_booking_tanggal_perjanjian','regon_booking.regon_booking_no_mr','regon_booking.regon_booking_hari','regon_booking.regon_booking_jam','regon_booking.regon_booking_keterangan');
    var $select = 'regon_booking.regon_booking_id,regon_booking.regon_booking_kode,regon_booking.regon_booking_tanggal_perjanjian,regon_booking.regon_booking_no_mr,regon_booking.regon_booking_hari,regon_booking.regon_booking_jam,regon_booking.regon_booking_keterangan,regon_booking.regon_booking_instalasi,regon_booking.regon_booking_klinik,regon_booking.regon_booking_kode_dokter,regon_booking.regon_booking_jenis_penjamin,regon_booking.regon_booking_penjamin,regon_booking.regon_booking_status,regon_booking.regon_booking_urutan,regon_booking.created_date,regon_booking.created_by,regon_booking.regon_booking_waktu_datang, regon_booking.log_detail_pasien, regon_booking.log_transaksi, regon_booking.regon_booking_tgl_registrasi_ulang';

    var $order = array('regon_booking.regon_booking_id' => 'DESC', 'regon_booking.updated_date' => 'DESC');

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    private function _main_query($kode_booking=''){
        $this->db->select($this->select);
        $this->db->from($this->table);
        $this->db->where('regon_booking_kode', $kode_booking);

    }

    private function _get_datatables_query($kode_booking='')
    {
        
        $this->_main_query($kode_booking);

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
    
    function get_datatables($kode_booking)
    {
        $this->_get_datatables_query($kode_booking);
        if($_POST['length'] != -1)
        $this->db->limit($_POST['length'], $_POST['start']);
        $query = $this->db->get();
        return $query->result();
    }

    function count_filtered($kode_booking)
    {
        $this->_get_datatables_query($kode_booking);
        $query = $this->db->get();
        return $query->num_rows();
    }

    public function count_all($kode_booking)
    {
        $this->_main_query($kode_booking);
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
        $this->_main_query($code);
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

    public function get_relasi_pasien()
    {
        /*get relasi pasien*/
        $this->db->where('regon_rp_no_mr', $this->session->userdata('user_profile')->no_mr );
        $this->db->or_where('regon_rp_ref_no_mr', $this->session->userdata('user_profile')->no_mr );
        $this->db->order_by('regon_rp_id', 'ASC');
        $data = $this->db->get('regon_relasi_pasien')->result();
        //print_r($this->db->last_query());die;
        return $data;
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

    // public function booking_view($data)
    // {
    //     $qr_code = $this->master->get_qr_code($data);
    //     $pasien = json_decode($data->log_detail_pasien);
    //     $transaksi = json_decode($data->log_transaksi);
    //     $html = '';

    //     $penjamin = isset($transaksi->penjamin->nama_perusahaan)?$transaksi->penjamin->nama_perusahaan:'';

    //     $current_date = date('Y-m-d');
    //     if($current_date > $data->regon_booking_tanggal_perjanjian){
    //         $message = '<span style="" class="stamp is-nope-2">Expired Date</span>';
    //         $txt_message = '<span class="red">Expired Date</span>';
    //         $tgl_booking = '<span style="color:red">'.$this->tanggal->formatDate($data->regon_booking_tanggal_perjanjian).' '.$data->regon_booking_jam.'</span>';

    //     }elseif ($current_date == $data->regon_booking_tanggal_perjanjian) {
    //         $message = '<span style="" class="stamp is-approved">Available Today</span>';
    //         $txt_message = '<span class="green">Available Today</span>';
    //         $tgl_booking = '<span style="color:green">'.$this->tanggal->formatDate($data->regon_booking_tanggal_perjanjian).' '.$data->regon_booking_jam.'</span>';

    //     }else{
    //         $message = '<span style="" class="stamp is-nope-2">Not This Time</span>';
    //         $txt_message = '<span class="yellow">Not this time!</span>';
    //         $tgl_booking = '<span style="color:red">'.$this->tanggal->formatDate($data->regon_booking_tanggal_perjanjian).' '.$data->regon_booking_jam.'</span>';
    //     }

    //     /*hidden form*/
    //     $html .= '<input type="hidden" name="regon_booking_id" id="regon_booking_id" value="'.$data->regon_booking_id.'">';
    //     $html .= '<table border="0" width="100%" class="table" style="background-color: #f0f0f09e;
    //     ">
    //                 <tr>
    //                     <td rowspan="8" align="center" valign="top" width="100px"><img class="center" src="'.base_url().'assets/barcode.php?s=qrh&d='.$qr_code.'"><br>
    //                     <button type="button" class="btn btn-xs btn-danger" onclick="registerNow('."'".$data->regon_booking_no_mr."'".')"> <i class="ace-icon fa fa-angle-double-down"></i> Daftarkan Sekarang </button> <br>
    //                     <button type="button" class="btn btn-xs btn-primary" onclick="showModalDaftarReschedule('.$data->regon_booking_id.')"> <i class="fa fa-calendar"></i> Reschedule </button><br>
    //                     </td>
    //                 </tr>
    //                 <tr>
    //                   <td width="100px">Kode Booking</td>
    //                   <td> <b>'.$data->regon_booking_kode.'</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Tanggal, '.$this->tanggal->formatDateTime($data->created_date).'</td>
    //                 </tr>

    //                 <tr>
    //                   <td>No. MR</td><td> '.$data->regon_booking_no_mr.'</td>
    //                 </tr>

    //                 <tr>
    //                   <td>Nama Pasien</td><td> '.$pasien->nama_pasien.'</td>
    //                 </tr>

    //                 <tr>
    //                   <td>Poli Tujuan</td>
    //                   <td> '.ucwords($transaksi->klinik->nama_bagian).'</td>
    //                 </tr>

    //                 <tr>
    //                   <td>Nama Dokter</td><td> '.$transaksi->dokter->nama_pegawai.'</td>
    //                 </tr>

    //                 <tr>
    //                   <td>Jam Praktek</td><td> '.$tgl_booking.'</td>
    //                 </tr>

    //                 <tr>
    //                   <td>Status</td><td><b> '.$txt_message.'</b></td>
    //                 </tr>

    //                 </table><br>';

    //     return $html;
    // }

    public function booking_view($data)
    {
        $qr_code = $this->master->get_qr_code($data);
        $pasien = json_decode($data->log_detail_pasien);
        $transaksi = json_decode($data->log_transaksi);
        $html = '';

        $penjamin = isset($transaksi->penjamin->nama_perusahaan)?$transaksi->penjamin->nama_perusahaan:'';

        $current_date = date('Y-m-d');
        if($current_date > $data->regon_booking_tanggal_perjanjian){
            $message = '<span style="" class="stamp is-nope-2">Expired Date</span>';
            $txt_message = '<span class="red">Expired Date</span>';
            $tgl_booking = '<span style="color:red">'.$this->tanggal->formatDate($data->regon_booking_tanggal_perjanjian).' '.$data->regon_booking_jam.'</span>';

        }elseif ($current_date == $data->regon_booking_tanggal_perjanjian) {
            $message = '<span style="" class="stamp is-approved">Available Today</span>';
            $txt_message = '<span class="green">Available Today</span>';
            $tgl_booking = '<span style="color:green">'.$this->tanggal->formatDate($data->regon_booking_tanggal_perjanjian).' '.$data->regon_booking_jam.'</span>';

        }else{
            $message = '<span style="" class="stamp is-nope-2">Not This Time</span>';
            $txt_message = '<span class="yellow">Not this time!</span>';
            $tgl_booking = '<span style="color:red">'.$this->tanggal->formatDate($data->regon_booking_tanggal_perjanjian).' '.$data->regon_booking_jam.'</span>';
        }

        $status_terlayani = ($data->regon_booking_status==1)?'<span style="color: green; font-weight: bold">Sudah dilayani, tanggal '.$this->tanggal->formatDate($data->regon_booking_tgl_registrasi_ulang).'</span>':'Belum dilyani';
        /*hidden form*/
        $html .= '<input type="hidden" name="regon_booking_id" id="regon_booking_id" value="'.$data->regon_booking_id.'">';
        // untuk reschedule
        // <button type="button" class="btn btn-xs btn-primary" onclick="showModalDaftarReschedule('.$data->regon_booking_id.')"> <i class="fa fa-calendar"></i> Reschedule </button><br>

        
            $html_btn_action = ($data->regon_booking_status != 1) ?'<button type="button" class="btn btn-xs btn-danger" onclick="registerNow('."'".$data->regon_booking_no_mr."'".')"> <i class="ace-icon fa fa-angle-double-down"></i> Daftarkan Sekarang </button> <br>
            <button type="button" class="btn btn-xs btn-primary" onclick="showModalDaftarReschedule('.$data->regon_booking_id.')"> <i class="fa fa-calendar"></i> Reschedule </button><br>':'';
        
        $html .= '<table border="0" width="100%" class="table" style="background-color: #f0f0f09e;
        ">
                    <tr>
                        <td rowspan="9" align="center" valign="top" width="100px"><img class="center" src="'.base_url().'assets/barcode.php?s=qrh&d='.$qr_code.'"><br>
                        '.$html_btn_action.'
                        </td>
                    </tr>
                    <tr>
                      <td width="100px">Kode Booking</td>
                      <td> <b>'.$data->regon_booking_kode.'</b> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Tanggal, '.$this->tanggal->formatDateTime($data->created_date).'</td>
                    </tr>

                    <tr>
                      <td>No. MR</td><td> '.$data->regon_booking_no_mr.'</td>
                    </tr>

                    <tr>
                      <td>Nama Pasien</td><td> '.$pasien->nama_pasien.'</td>
                    </tr>

                    <tr>
                      <td>Poli Tujuan</td>
                      <td> '.ucwords($transaksi->klinik->nama_bagian).'</td>
                    </tr>

                    <tr>
                      <td>Nama Dokter</td><td> '.$transaksi->dokter->nama_pegawai.'</td>
                    </tr>

                    <tr>
                      <td>Jam Praktek</td><td> '.$tgl_booking.'</td>
                    </tr>

                    <tr>
                      <td>Status</td><td><b> '.$txt_message.'</b></td>
                    </tr>

                    <tr>
                      <td>Status Dilayani</td><td><b> '.$status_terlayani.'</b></td>
                    </tr>

                    </table><br>';

        return $html;
    }


}
