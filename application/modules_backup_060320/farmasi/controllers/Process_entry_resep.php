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
        $this->load->model('Etiket_obat_model', 'Etiket_obat');
        $this->load->model('Process_entry_resep_model', 'Process_entry_resep');
        $this->load->library('Stok_barang');
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

        if(isset($_POST['jenis_resep']) AND $_POST['jenis_resep'] == 'rk'){
            $this->form_validation->set_rules('no_mr', 'Nama Karyawan', 'trim|required', array('required' => 'Masukan nama karyawan lalu pilih !') );
        }

        if(isset($_POST['jenis_resep']) AND ($_POST['jenis_resep'] == 'rl' || $_POST['jenis_resep'] == 'pb') ){
            $this->form_validation->set_rules('nama_pasien_keyword', 'Nama Pasien', 'trim|required');
            $this->form_validation->set_rules('dokter_pengirim_keyword', 'Dokter Pengirim', 'trim|required');
        }


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
                'kode_pesan_resep' => isset($_POST['no_resep'])?$this->regex->_genRegex($_POST['no_resep'], 'RGXINT'):0,
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
                'nama_pasien' => $this->regex->_genRegex($_POST['nama_pasien'], 'RGXQSL'),
                'flag_trans' => $this->regex->_genRegex($_POST['flag_trans'], 'RGXAZ'),
            );
            // print_r($data_farmasi);die;
            /*data detail farmasi*/
            $biaya_tebus = $_POST['pl_harga_satuan'] * $_POST['jumlah_pesan'];

            $data_farmasi_detail = array(
                'jumlah_pesan' => $this->regex->_genRegex($_POST['jumlah_pesan'], 'RGXINT'),
                'jumlah_tebus' => $this->regex->_genRegex($_POST['jumlah_tebus'], 'RGXINT'),
                'sisa' => $this->regex->_genRegex(0, 'RGXINT'),
                'kode_brg' => $this->regex->_genRegex($_POST['kode_brg'], 'RGXQSL'),
                'harga_beli' => $this->regex->_genRegex($_POST['pl_harga_beli'], 'RGXINT'),
                'harga_jual' => $this->regex->_genRegex($_POST['pl_harga_satuan'], 'RGXINT'),
                'harga_r' => $this->regex->_genRegex($_POST['harga_r'], 'RGXINT'),
                'biaya_tebus' => $this->regex->_genRegex($biaya_tebus, 'RGXINT'),
                'tgl_input' => date('Y-m-d H:i:s'),
                'urgensi' => $this->regex->_genRegex($_POST['urgensi'], 'RGXQSL'),
            );

            
            /*cek terlebih dahulu data fr_tc_far*/
            /*jika sudah ada data sebelumnya maka langsung insert ke detail*/
            $cek_existing = ( $_POST['kode_trans_far'] == 0 ) ? false : $this->Process_entry_resep->cek_existing_data('fr_tc_far', array('kode_trans_far' => $_POST['kode_trans_far']) );

            // print_r($cek_existing);die;

            if( $cek_existing != false ){
                /*update existing*/
                $kode_trans_far = $cek_existing->kode_trans_far;
                $data_farmasi['kode_trans_far'] = $kode_trans_far;
                $data_farmasi['updated_date'] = date('Y-m-d H:i:s');
                $data_farmasi['updated_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                $this->db->update('fr_tc_far', $data_farmasi, array('kode_trans_far' => $_POST['kode_trans_far']) );
                /*save log*/
                $this->logs->save('fr_tc_far', $_POST['kode_trans_far'], 'update record on entry resep module', json_encode($data_farmasi),'kode_pesan_resep');
            
            }else{
                $kode_trans_far = $this->master->get_max_number('fr_tc_far', 'kode_trans_far', array('kode_bagian' => $_POST['kode_bagian'] ));
                /*update existing*/
                $data_farmasi['kode_trans_far'] = $kode_trans_far;
                $data_farmasi['created_date'] = date('Y-m-d H:i:s');
                $data_farmasi['created_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                $this->db->insert( 'fr_tc_far', $data_farmasi );
                /*save log*/
                $this->logs->save('fr_tc_far', $_POST['kode_trans_far'], 'insert new record on entry resep module', json_encode($data_farmasi),'kode_pesan_resep');

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
            $data_farmasi_detail['kode_trans_far'] = $kode_trans_far;
            $data_farmasi_detail['kode_pesan_resep'] = $_POST['no_resep'];
            $data_farmasi_detail['nama_brg'] = $_POST['nama_tindakan'];
            $data_farmasi_detail['satuan_kecil'] = $_POST['pl_satuan_kecil'];
            $data_farmasi_detail['flag_resep'] = $_POST['flag_resep'];

            $this->Process_entry_resep->save_log_detail($data_farmasi_detail, $data_farmasi_detail['kd_tr_resep']);
            
            if ($this->db->trans_status() === FALSE)
            {
                $this->db->trans_rollback();
                echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
            }
            else
            {
                $this->db->trans_commit();
                echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'kode_trans_far' => $kode_trans_far ));
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

        // print_r($_POST);die;
        // select transaksi farmasi
        $this->db->from('fr_tc_far a, fr_tc_far_detail b');
        $this->db->join('mt_barang c', 'c.kode_brg=b.kode_brg','left');
        $this->db->where('a.kode_trans_far =  b.kode_trans_far');
        $this->db->where('a.kode_trans_far', $ID);
        $trans_dt = $this->db->get()->result();
        
        /*execution begin*/
        $this->db->trans_begin();

        if( count($trans_dt) > 0 ){
            foreach($trans_dt as $row_dt){
                // update sisa tebus fr_tc_far_detail
                $sisa_tebus = $row_dt->jumlah_pesan - $row_dt->jumlah_tebus;
                
                // $this->db->update('fr_tc_far_detail', array('sisa' => $sisa_tebus, 'status_kirim' => 1), array('kd_tr_resep' => $row_dt->kd_tr_resep) );
                    
                // create mutasi untuk obat racikan
                if( $row_dt->id_tc_far_racikan != 0 ){
                    // cari data obat racikan 
                    $dt_racikan = $this->db->join('tc_far_racikan a','a.id_tc_far_racikan=b.id_tc_far_racikan','left')->get_where('tc_far_racikan_detail b', array('b.id_tc_far_racikan' => $row_dt->id_tc_far_racikan) )->result();
                    // lopping data racikan 
                    foreach ( $dt_racikan as $val_dt ) {
                        $this->stok_barang->stock_process($val_dt->kode_brg, $val_dt->jumlah, $row_dt->kode_bagian, 14, " Nama Racikan : ".$val_dt->nama_racikan." - Nomor Resep : ".$row_dt->kode_pesan_resep."", 'reduce');
                                            
                    }
                    // update status input fr_tc_far_racikan
                    $this->db->update('tc_far_racikan', array('status_input' => 1), array('id_tc_far_racikan' => $row_dt->id_tc_far_racikan ) );
    
                    // define untuk obat racikan
                    $kode_brg = $dt_racikan[0]->id_tc_far_racikan;
                    $nama_barang_tindakan = $dt_racikan[0]->nama_racikan;
                    $total_harga = $dt_racikan[0]->harga_jual + $dt_racikan[0]->jasa_r;
    
                }
                // create mutasi obat biasa atau non racikan
                else{
                    // kurangi stok depo, update kartu stok dan rekap stok
                    $this->stok_barang->stock_process($row_dt->kode_brg, $row_dt->jumlah_tebus, $row_dt->kode_bagian, 14, " No Resep : ".$row_dt->kode_pesan_resep."", 'reduce');
                    // define untuk obat biasa atau non racikan
                    $kode_brg = $row_dt->kode_brg;
                    $nama_barang_tindakan = $row_dt->nama_brg;
                    $total_harga = $row_dt->biaya_tebus + $row_dt->harga_r;
                    
                }
    
                // insert table tc_trans_pelayanan
                $kode_trans_pelayanan = $this->master->get_max_number("tc_trans_pelayanan","kode_trans_pelayanan");
    
                $dataexc = array(
                    "kode_trans_pelayanan" => $kode_trans_pelayanan,
                    "no_registrasi" => $row_dt->no_registrasi,
                    "no_mr" => $row_dt->no_mr,
                    "nama_pasien_layan" => $row_dt->nama_pasien,
                    "kode_kelompok" => $_POST['kode_kelompok'],
                    "kode_perusahaan" => $_POST['kode_perusahaan'],
                    "tgl_transaksi" => date('Y-m-d H:i:s'),
                    "jenis_tindakan" => '',
                    "nama_tindakan" => $nama_barang_tindakan ,
                    "bill_rs" => $total_harga,
                    // "kode_ri" => isset($_POST['kode_ri'])?$_POST['kode_ri']:0,
                    // "kode_poli" => isset($_POST['kode_poli'])?$_POST['kode_poli']:0,
                    "jumlah" => $row_dt->jumlah_tebus,
                    "kode_barang" => (string)$kode_brg,
                    "kode_trans_far" => $row_dt->kode_trans_far,
                    "kode_bagian" => $row_dt->kode_bagian,
                    "kode_bagian_asal" => $row_dt->kode_bagian_asal,
                    "kode_profit" => $row_dt->kode_profit,
                    "kd_tr_resep" => $row_dt->kd_tr_resep,
                    "no_kunjungan" => (in_array($row_dt->kode_profit, array(1000 , 2000) )) ?$row_dt->no_kunjungan : 0,
                    "status_selesai" => 2,
                    "status_nk" => (in_array($_POST['kode_kelompok'], array(10 , 3) )) ? 1 : 0,
                    "status_karyawan" => ( $row_dt->flag_trans == 'RK' ) ? 1 : 0,
                );
    
                // print_r($dataexc);die;
                $this->db->insert('tc_trans_pelayanan', $dataexc);
    
                $this->db->trans_commit();
    
            }
    
            // update status transaksi
            $this->db->where('kode_trans_far', $ID);
            $this->db->update('fr_tc_far', array('status_transaksi' => 1) );
    
            /*log transaksi*/
            $this->db->update('fr_tc_pesan_resep', array('status_tebus' => 1), array('kode_pesan_resep' => $_POST['kode_pesan_resep']) );   
        }else{
            echo json_encode(array('status' => 301, 'message' => 'Tidak ada obat ditemukan dalam resep'));
            exit;
        }
                    
        
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Gagal Dilakukan'));
        }
        else
        {
            $this->db->trans_commit();
            echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'kode_trans_far' => $ID ));
        }
        
                
    }

    function get_total_biaya_farmasi($no_resep){

        /*resep biasa*/
        $total_resep_biasa = $this->Process_entry_resep->sum_total_biaya_far($no_resep);
        echo json_encode(array('total' => $total_resep_biasa));
    }

    function nota_farmasi($kode_trans_far){
        $resep_log = $this->Etiket_obat->get_detail_resep_data($kode_trans_far);

        $data = array(
            'resep' => $resep_log->result(),
        );
        // echo '<pre>'; print_r($resep_log->result());
        $this->load->view('farmasi/preview_nota_farmasi', $data);

    }




}

/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
