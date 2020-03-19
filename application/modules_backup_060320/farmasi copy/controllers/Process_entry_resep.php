<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Process_entry_resep extends MX_Controller {

    /*function constructor*/
    function __construct() 
    {

        parent::__construct();

        /*session redirect login if not login*/
        if($this->session->userdata('logged')!=TRUE){
            redirect(base_url().'login');exit;
        }
        /*load model*/
        $this->load->model('Process_entry_resep_model', 'Process_entry_resep');
        $this->load->helper('security');
        /*enable profiler*/
        $this->output->enable_profiler(false);

    }

    public function process()
    {
        // print_r($_POST);die;
        $this->load->library('form_validation');
        // form validation
        $this->form_validation->set_rules('kode_brg', 'Cari Obat', 'trim|required');
        $this->form_validation->set_rules('jumlah_pesan', 'Jumlah Pesan', 'trim|required');
        /*$this->form_validation->set_rules('jumlah_tebus', 'Jumlah Tebus', 'trim|required');*/
        $this->form_validation->set_rules('aturan_pakai', 'Aturan Pakai', 'trim|required');


        // set message error
        $this->form_validation->set_message('required', "Silahkan isi field \"%s\"");        

        if ($this->form_validation->run() == FALSE)
        {
            $this->form_validation->set_error_delimiters('<div style="color:white"><i>', '</i></div>');
            //die(validation_errors());
            echo json_encode(array('status' => 301, 'message' => validation_errors()));
        }
        else
        {                       
            /*execution*/
            $this->db->trans_begin();

            $data_farmasi = array(
                'kode_pesan_resep' => $this->regex->_genRegex($_POST['no_resep'], 'RGXINT'),
                'no_resep' => $this->Process_entry_resep->format_no_resep($_POST['flag_trans'], $_POST['kode_profit']),
                'kode_profit' => $this->regex->_genRegex($_POST['kode_profit'], 'RGXINT'),
                'kode_bagian' => $this->regex->_genRegex($_POST['kode_bagian'], 'RGXQSL'),
                'tgl_trans' => date('Y-m-d H:i:s'),
                'kode_bagian_asal' => $this->regex->_genRegex($_POST['kode_bagian_asal'], 'RGXQSL'),
                'no_mr' => $this->regex->_genRegex($_POST['no_mr'], 'RGXQSL'),
                'no_registrasi' => $this->regex->_genRegex($_POST['no_registrasi'], 'RGXINT'),
                'no_kunjungan' => $this->regex->_genRegex($_POST['no_kunjungan'], 'RGXINT'),
                'kode_dokter' => $this->regex->_genRegex($_POST['kode_dokter'], 'RGXINT'),
                'dokter_pengirim' => $this->regex->_genRegex($_POST['dokter_pengirim'], 'RGXQSL'),
                'nama_pasien' => $this->regex->_genRegex(substr($_POST['nama_pasien'], 1,20), 'RGXQSL'),
                'flag_trans' => $this->regex->_genRegex($_POST['flag_trans'], 'RGXAZ'),
            );
            // print_r($data_farmasi);die;
            /*data detail farmasi*/
            $biaya_tebus = $_POST['pl_harga_satuan'] * $_POST['jumlah_pesan'];
            /*format aturan pakai*/
            $format_aturan_pakai = $this->Process_entry_resep->format_aturan_pakai($_POST['aturan_pakai'], $_POST['bentuk_resep'], $_POST['anjuran_pakai']);

            $data_farmasi_detail = array(
                'jumlah_pesan' => $this->regex->_genRegex($_POST['jumlah_pesan'], 'RGXINT'),
                'jumlah_tebus' => $this->regex->_genRegex($_POST['jumlah_pesan'], 'RGXINT'),
                'sisa' => $this->regex->_genRegex(0, 'RGXINT'),
                'kode_brg' => $this->regex->_genRegex($_POST['kode_brg'], 'RGXQSL'),
                'harga_beli' => $this->regex->_genRegex($_POST['pl_harga_beli'], 'RGXINT'),
                'harga_jual' => $this->regex->_genRegex($_POST['pl_harga_satuan'], 'RGXINT'),
                'harga_r' => $this->regex->_genRegex($_POST['harga_r'], 'RGXINT'),
                'biaya_tebus' => $this->regex->_genRegex($biaya_tebus, 'RGXINT'),
                'tgl_input' => date('Y-m-d H:i:s'),
                'aturan_pakai' => $format_aturan_pakai,
                'catatan_aturan_pakai' => $this->regex->_genRegex($_POST['catatan'], 'RGXQSL'),
                'aturan_pakai_format' => $this->regex->_genRegex($_POST['aturan_pakai'], 'RGXQSL'),
                'urgensi' => $this->regex->_genRegex($_POST['urgensi'], 'RGXQSL'),
            );

            //print_r($data_farmasi_detail);die;
            /*cek terlebih dahulu data fr_tc_far*/
            /*jika sudah ada data sebelumnya maka langsung insert ke detail*/
            $cek_existing = $this->Process_entry_resep->cek_existing_data('fr_tc_far', array('kode_pesan_resep' => $_POST['no_resep']) );

            if( $cek_existing != false ){
                /*update existing*/
                $kode_trans_far = $cek_existing->kode_trans_far;
                $data_farmasi['kode_trans_far'] = $kode_trans_far;
                $data_farmasi['updated_date'] = date('Y-m-d H:i:s');
                $data_farmasi['updated_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                $this->db->update('fr_tc_far', $data_farmasi, array('kode_pesan_resep' => $_POST['no_resep']) );
                /*save log*/
                $this->logs->save('fr_tc_far', $_POST['no_resep'], 'update record on entry resep module', json_encode($data_farmasi),'kode_pesan_resep');
            
            }else{
                $kode_trans_far = $this->master->get_max_number('fr_tc_far', 'kode_trans_far', array('kode_bagian' => $_POST['kode_bagian'] ));
                /*update existing*/
                $data_farmasi['kode_trans_far'] = $kode_trans_far;
                $data_farmasi['created_date'] = date('Y-m-d H:i:s');
                $data_farmasi['created_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                $this->db->insert( 'fr_tc_far', $data_farmasi );
                /*save log*/
                $this->logs->save('fr_tc_far', $_POST['no_resep'], 'insert new record on entry resep module', json_encode($data_farmasi),'kode_pesan_resep');

            }

            if( $_POST['kd_tr_resep'] == 0 ){
                $data_farmasi_detail['kd_tr_resep'] = $this->master->get_max_number('fr_tc_far_detail', 'kd_tr_resep');
                $data_farmasi_detail['kode_trans_far'] = $kode_trans_far;
                $data_farmasi_detail['created_date'] = date('Y-m-d H:i:s');
                $data_farmasi_detail['created_by'] = json_encode(array('user_id' => $this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                /*insert new data detail*/
                $this->db->insert('fr_tc_far_detail', $data_farmasi_detail);
            }else{
                $data_farmasi_detail['kd_tr_resep'] = $_POST['kd_tr_resep'];
                $data_farmasi_detail['kode_trans_far'] = $kode_trans_far;
                $data_farmasi_detail['updated_date'] = date('Y-m-d H:i:s');
                $data_farmasi_detail['updated_by'] = json_encode(array('user_id' => $this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                /*insert new data detail*/
                $this->db->update('fr_tc_far_detail', $data_farmasi_detail, array('kd_tr_resep' => $_POST['kd_tr_resep']) );
            }

            /*params log*/
            $data_farmasi_detail['kode_pesan_resep'] = $_POST['no_resep'];
            $data_farmasi_detail['bentuk_resep'] = $_POST['bentuk_resep'];
            $data_farmasi_detail['anjuran_pakai'] = $_POST['anjuran_pakai'];
            $data_farmasi_detail['nama_brg'] = $_POST['nama_tindakan'];
            $data_farmasi_detail['satuan_kecil'] = $_POST['pl_satuan_kecil'];
            $data_farmasi_detail['catatan'] = $_POST['catatan'];
            $data_farmasi_detail['flag_resep'] = $_POST['flag_resep'];

            $this->Process_entry_resep->save_log_detail($data_farmasi_detail, $data_farmasi_detail['kd_tr_resep']);
            /*save log*/

            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan' ));
            }
        
        }

    }

    

    public function delete()
    {
        $id=$this->input->post('ID')?$this->input->post('ID',TRUE):null;
        $flag=$this->input->post('flag')?$this->input->post('flag',TRUE):null;
        $toArray = explode(',',$id);
        if($id!=null){
            if($this->Process_entry_resep->delete_by_id($toArray, $flag)){
                echo json_encode(array('status' => 200, 'message' => 'Proses Hapus Data Berhasil Dilakukan'));
            }else{
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Hapus Data Gagal Dilakukan'));
            }
        }else{
            echo json_encode(array('status' => 301, 'message' => 'Tidak ada item yang dipilih'));
        }
        
    }

    public function process_selesai_resep()
    {
        $ID = ($this->input->post('ID'))?$this->input->post('ID'):0;

        $this->db->where('kode_trans_far IN (SELECT kode_trans_far FROM fr_tc_far WHERE kode_pesan_resep='.$ID.')');
        $this->db->update('fr_tc_far_detail', array('status_kirim' => 1, 'sisa' => 0));

        /*inventory*/
        
        /*save log mutasi kartu stok*/

        /*kurangi depo stok farmasi*/

        /*log transaksi*/

        $this->db->update('fr_tc_pesan_resep', array('status_tebus' => 1), array('kode_pesan_resep' => $ID) );
        
        $this->db->update('fr_tc_far', array('status_transaksi' => 1), array('kode_pesan_resep' => $ID) );

        $this->db->update('tc_far_racikan', array('status_input' => 1), array('kode_pesan_resep' => $ID) );
        
        echo json_encode(array('status' => 200, 'message' => 'Proses Hapus Data Berhasil Dilakukan'));
                
    }

    function get_total_biaya_farmasi($no_resep){

        /*resep biasa*/
        $total_resep_biasa = $this->Process_entry_resep->sum_total_biaya_far($no_resep);
        echo json_encode(array('total' => $total_resep_biasa));
    }




}

/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
