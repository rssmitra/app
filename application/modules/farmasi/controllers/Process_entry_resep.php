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
        $this->load->model('Entry_resep_racikan_model', 'Entry_resep_racikan');
        $this->load->model('Retur_obat_model', 'Retur_obat');
        $this->load->library('Stok_barang');
        $this->load->library('Print_escpos'); 
        $this->load->helper('security');
        /*enable profiler*/
        $this->output->enable_profiler(false);
         /*profile class*/
        $this->title = ($this->lib_menus->get_menu_by_class(get_class($this)))?$this->lib_menus->get_menu_by_class(get_class($this))->name : 'Title';

    }

    public function process()
    {
        // print_r($_POST);die;
        $this->load->library('form_validation');
        // form validation
        if( $_POST['submit'] != 'header' ){
            $this->form_validation->set_rules('kode_brg', 'Cari Obat', 'trim|required');
            $this->form_validation->set_rules('jumlah_pesan', 'Jumlah Pesan', 'trim');
        }

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
                'tgl_trans' => isset($_POST['tgl_trans'])?$_POST['tgl_trans']: date('Y-m-d H:i:s'),
                'kode_bagian_asal' => $this->regex->_genRegex($_POST['kode_bagian_asal'], 'RGXQSL'),
                'no_mr' => $this->regex->_genRegex($_POST['no_mr'], 'RGXQSL'),
                'no_registrasi' => $this->regex->_genRegex($_POST['no_registrasi'], 'RGXINT'),
                'no_kunjungan' => $this->regex->_genRegex($_POST['no_kunjungan'], 'RGXINT'),
                'kode_dokter' => $this->regex->_genRegex($_POST['kode_dokter'], 'RGXINT'),
                'dokter_pengirim' => $this->regex->_genRegex($_POST['dokter_pengirim'], 'RGXQSL'),
                'nama_pasien' => $this->regex->_genRegex($_POST['nama_pasien'], 'RGXQSL'),
                'alamat_pasien' => isset($_POST['alamat_pasien'])?$this->regex->_genRegex($_POST['alamat_pasien'], 'RGXQSL'):'',
                'telpon_pasien' => isset($_POST['no_telp'])?$this->regex->_genRegex($_POST['no_telp'], 'RGXQSL'):'',
                'flag_trans' => $this->regex->_genRegex($_POST['flag_trans'], 'RGXAZ'),
            );

            /*cek terlebih dahulu data fr_tc_far*/
            /*jika sudah ada data sebelumnya maka langsung insert ke detail*/
            $cek_existing = ( $_POST['kode_trans_far'] == 0 ) ? false : $this->Process_entry_resep->cek_existing_data('fr_tc_far', array('kode_trans_far' => $_POST['kode_trans_far']) );

            // print_r($data_farmasi);die;
            if( $cek_existing != false ){
                /*update existing*/
                $kode_trans_far = $cek_existing->kode_trans_far;
                $data_farmasi['iter'] = $_POST['jenis_iter'];
                $data_farmasi['kode_trans_far'] = $kode_trans_far;
                $data_farmasi['updated_date'] = date('Y-m-d H:i:s');
                $data_farmasi['updated_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                $this->db->update('fr_tc_far', $data_farmasi, array('kode_trans_far' => $_POST['kode_trans_far']) );
                /*save log*/
                $this->logs->save('fr_tc_far', $_POST['kode_trans_far'], 'update record on entry resep module', json_encode($data_farmasi),'kode_pesan_resep');
            
            }else{
                $kode_trans_far = $this->master->get_max_number('fr_tc_far', 'kode_trans_far', array());
                /*update existing*/
                $data_farmasi['iter'] = $_POST['jenis_iter'];
                $data_farmasi['kode_trans_far'] = $kode_trans_far;
                $data_farmasi['created_date'] = date('Y-m-d H:i:s');
                $data_farmasi['created_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                // echo '<pre>';print_r($data_farmasi);die;
                $this->db->insert( 'fr_tc_far', $data_farmasi );
                /*save log*/
                $this->logs->save('fr_tc_far', $_POST['kode_trans_far'], 'insert new record on entry resep module', json_encode($data_farmasi),'kode_pesan_resep');

            }

            // jika submit detail
            if( $_POST['submit'] != 'header' ) :

                $isset_jml_tebus = isset($_POST['jumlah_tebus'])?$_POST['jumlah_tebus']:$_POST['jumlah_pesan'];
                /*data detail farmasi*/
                if( $_POST['urgensi'] == 'biasa' ){
                    $biaya_tebus = ($_POST['kode_perusahaan'] == 120) ? $_POST['pl_harga_satuan'] * $isset_jml_tebus : $_POST['pl_harga_satuan'] * $isset_jml_tebus;
                    $harga_jual = ($_POST['kode_perusahaan'] == 120) ? $_POST['pl_harga_satuan'] : $_POST['pl_harga_satuan'];
                }else{
                    $biaya_tebus = $_POST['pl_harga_cito'] * $isset_jml_tebus;
                    $harga_jual = $_POST['pl_harga_cito'];
                }
                
                $sisa = $_POST['jumlah_pesan'] - $isset_jml_tebus;
                $data_farmasi_detail = array(
                    'jumlah_pesan' => ($_POST['jumlah_pesan']) ? $_POST['jumlah_pesan'] : 0,
                    'jumlah_tebus' => ($isset_jml_tebus) ? $isset_jml_tebus : 0,
                    'sisa' => $sisa,
                    'kode_brg' => $this->regex->_genRegex($_POST['kode_brg'], 'RGXQSL'),
                    'harga_beli' => $_POST['pl_harga_beli'],
                    'harga_jual' => $harga_jual,
                    'harga_r' => 500,
                    'jumlah_retur' => 0,
                    'harga_r_retur' => 0,
                    'status_retur' => null,
                    'biaya_tebus' => $biaya_tebus,
                    'tgl_input' => date('Y-m-d H:i:s'),
                    'urgensi' => $this->regex->_genRegex($_POST['urgensi'], 'RGXQSL'),
                    'jumlah_obat_23' => isset($_POST['jml_23'])?$_POST['jml_23']:0,
                    'prb_ditangguhkan' => isset($_POST['prb_ditangguhkan'])?$_POST['prb_ditangguhkan']:0,
                    'resep_ditangguhkan' => isset($_POST['resep_ditangguhkan'])?$_POST['resep_ditangguhkan']:0,
                );
                
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
                
                // check existing data
                $dt_existing = $this->db->get_where('fr_tc_far_detail_log', array('relation_id' => $data_farmasi_detail['kd_tr_resep']) );
               
                // insert etiket obat
                $data_etiket = array(
                    'dosis_per_hari' => isset($_POST['dosis_start'])?$this->regex->_genRegex($_POST['dosis_start'], 'RGXQSL'):0,
                    'dosis_obat' => isset($_POST['dosis_end'])?$this->regex->_genRegex($_POST['dosis_end'], 'RGXQSL'):0,
                    // 'aturan_pakai' => isset($_POST['satuan_obat'])?$this->regex->_genRegex($_POST['satuan_obat'], 'RGXQSL'):0,
                    'catatan_lainnya' => isset($_POST['catatan'])?$this->regex->_genRegex($_POST['catatan'], 'RGXQSL'):0,
                    'relation_id' => isset($data_farmasi_detail['kd_tr_resep'])?$this->regex->_genRegex($data_farmasi_detail['kd_tr_resep'], 'RGXINT'):0,
                    'satuan_obat' => isset($_POST['satuan_obat'])?$this->regex->_genRegex($_POST['satuan_obat'], 'RGXQSL'):0,
                    'anjuran_pakai' => isset($_POST['anjuran_pakai'])?$this->regex->_genRegex($_POST['anjuran_pakai'], 'RGXQSL'):0,
                    // 'jumlah_obat' => isset($_POST['jumlah_obat'])?$this->regex->_genRegex($_POST['jumlah_obat'], 'RGXQSL'):0,
                    'jumlah_obat_23' => isset($_POST['jml_23'])?$this->regex->_genRegex($_POST['jml_23'], 'RGXQSL'):0,
                    'urgensi' => $this->regex->_genRegex($_POST['urgensi'], 'RGXQSL'),
                    'jumlah_retur' => 0,
                );
                
                if( $dt_existing->num_rows() > 0 ){
                    /*update existing*/
                    $data_etiket['updated_date'] = date('Y-m-d H:i:s');
                    $data_etiket['updated_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                    $this->db->update('fr_tc_far_detail_log', $data_etiket, array('relation_id' => $data_farmasi_detail['kd_tr_resep'], 'kode_trans_far' => $kode_trans_far) );
                    // print_r($this->db->last_query());die;
                    /*save log*/
                    $this->logs->save('fr_tc_far_detail_log', $data_farmasi_detail['kd_tr_resep'], 'update record on entry resep module', json_encode($data_etiket),'relation_id');
                
                }else{
                    $dt_existing_obat = $this->db->get_where('fr_hisbebasluar_v', array('kd_tr_resep' => $_POST['kd_tr_resep']) )->row();
                    // print_r($dt_existing_obat);die;
                    /*sub total*/
                    $sub_total = ceil($dt_existing_obat->jumlah_tebus * $dt_existing_obat->harga_jual);
                    /*total biaya*/
                    $total_biaya = ($sub_total + $dt_existing_obat->jumlah_pesan);
                    
                    $data_etiket['relation_id'] = $dt_existing_obat->kd_tr_resep;
                    $data_etiket['kode_trans_far'] = $dt_existing_obat->kode_trans_far;
                    $data_etiket['kode_pesan_resep'] = $dt_existing_obat->kode_pesan_resep;
                    $data_etiket['tgl_input'] = ($dt_existing_obat->tgl_input)?$dt_existing_obat->tgl_input:$dt_existing_obat->tgl_trans;
                    $data_etiket['kode_brg'] = $dt_existing_obat->kode_brg;
                    $data_etiket['nama_brg'] = $dt_existing_obat->nama_brg;
                    $data_etiket['satuan_kecil'] = $dt_existing_obat->satuan_kecil;
                    $data_etiket['jumlah_pesan'] = $dt_existing_obat->jumlah_pesan;
                    $data_etiket['jumlah_tebus'] = $dt_existing_obat->jumlah_tebus;
                    $data_etiket['harga_jual_satuan'] = $dt_existing_obat->harga_jual;
                    $data_etiket['sub_total'] = $sub_total;
                    $data_etiket['total'] = $total_biaya;
                    $data_etiket['jasa_r'] = $dt_existing_obat->harga_r;
                    $data_etiket['total'] = $dt_existing_obat->harga_beli;
                    $data_etiket['urgensi'] = $this->regex->_genRegex($_POST['urgensi'], 'RGXQSL');
                    $data_etiket['flag_resep'] = ( $dt_existing_obat->id_tc_far_racikan == 0 )?'biasa':'racikan';

                    $data_etiket['created_date'] = date('Y-m-d H:i:s');
                    $data_etiket['created_by'] = json_encode(array('user_id' =>$this->regex->_genRegex($this->session->userdata('user')->user_id,'RGXINT'), 'fullname' => $this->regex->_genRegex($this->session->userdata('user')->fullname,'RGXQSL')));
                    // print_r($data_etiket);die;

                    $this->db->insert( 'fr_tc_far_detail_log', $data_etiket );
                    /*save log*/
                    $this->logs->save('fr_tc_far_detail_log', $data_farmasi_detail['kd_tr_resep'], 'insert new record on entry resep module', json_encode($data_etiket),'relation_id');

                }

            endif;

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

    public function rollback()
    {
        $id=$this->input->post('ID')?$this->input->post('ID',TRUE):null;
        
        if($this->Process_entry_resep->rollback($id)){
            echo json_encode(array('status' => 200, 'message' => 'Proses Rollback Berhasil Dilakukan'));
        }else{
            echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Rollback Gagal Dilakukan'));
        }
        
    }

    public function rollback_by_kode_trans_far()
    {
        $id=$this->input->post('ID')?$this->input->post('ID',TRUE):null;
        
        $dt_trans = $this->db->get_where('fr_tc_far', array('kode_trans_far' => $id) )->row();

        if($this->Process_entry_resep->rollback_by_kode_trans_far($id)){
            echo json_encode(array('status' => 200, 'message' => 'Proses Rollback Berhasil Dilakukan', 'iter' => $dt_trans->referensi));
        }else{
            echo json_encode(array('status' => 301, 'message' => 'Maaf Proses Rollback Gagal Dilakukan'));
        }
        
    }

    public function process_selesai_resep()
    {
        // print_r($_POST);die;
        $ID = ($this->input->post('ID'))?$this->input->post('ID'):0;
        $kode_bagian = '060101'; // kode bagian farmasi
        
        $trans_dt = $this->Retur_obat->get_detail_resep_data($ID)->result();
        // echo '<pre>';print_r($trans_dt);die;
        /*execution begin*/
        $this->db->trans_begin();

        if( count($trans_dt) > 0 ){
            
            foreach($trans_dt as $row_dt){

                // update sisa tebus fr_tc_far_detail
                $sisa_tebus = $row_dt->jumlah_pesan - $row_dt->jumlah_tebus;
                // print_r($row_dt);die;
                $this->db->update('fr_tc_far_detail', array('sisa' => $sisa_tebus, 'status_kirim' => 1, 'status_input' => 1, 'jumlah_retur' => 0), array('kd_tr_resep' => $row_dt->kd_tr_resep) );
                    
                // create mutasi untuk obat racikan
                if( $row_dt->id_tc_far_racikan != 0 ){
                    // cari data obat racikan 
                    $dt_racikan = $this->Entry_resep_racikan->get_detail_by_id($row_dt->relation_id);
                    // print_r($this->db->last_query());die;
                    // lopping data racikan 
                    foreach ( $dt_racikan as $val_dt ) {
                        $total_prb = ( $val_dt->prb_ditangguhkan == 0) ? $val_dt->jumlah + $val_dt->jumlah_obat_23 : $val_dt->jumlah;
                        $total_mutasi = $total_prb - $val_dt->jumlah_retur;
                        $this->stok_barang->stock_process($val_dt->kode_brg, (int)$total_mutasi, $kode_bagian, 14, " Nama Racikan : ".$val_dt->nama_racikan." - No. Racikan : ".$row_dt->kode_brg."", 'reduce');
                    }
                    // update status input fr_tc_far_racikan
                    $this->db->update('tc_far_racikan', array('status_input' => 1), array('id_tc_far_racikan' => $row_dt->id_tc_far_racikan ) );
    
                    // define untuk obat racikan
                    $kode_brg = $row_dt->kode_brg;
                    $nama_barang_tindakan = $row_dt->nama_brg;
                    $total_harga = $row_dt->total;
    
                }
                
                // create mutasi obat biasa atau non racikan
                else{

                    // jika ditangguhkan maka yang dipotong stok hanya jumlah tebus
                    $jml_kronis = ( $row_dt->prb_ditangguhkan != 1 ) ? (int)$row_dt->jumlah_obat_23 : 0 ;
                    $jml_tebus = ( $row_dt->resep_ditangguhkan != 1 ) ? (int)$row_dt->jumlah_tebus : 0 ;
                    $jml_mutasi_brg = ($jml_kronis + $jml_tebus);
                    // kurangi stok depo, update kartu stok dan rekap stok
                    if( $jml_mutasi_brg > 0 ){
                        if($row_dt->urgensi=='cito'){
                            // potong stok cito
                            $this->stok_barang->stock_process_cito($row_dt->kode_brg, $jml_mutasi_brg, $kode_bagian, 16, " No Transaksi : ".$row_dt->kode_trans_far."", 'reduce');
                        }else{
                            // potong stok biasa
                            $this->stok_barang->stock_process($row_dt->kode_brg, $jml_mutasi_brg, $kode_bagian, 14, " No Transaksi : ".$row_dt->kode_trans_far."", 'reduce');
                        }
                    }

                    // define untuk obat biasa atau non racikan
                    $kode_brg = $row_dt->kode_brg;
                    $nama_barang_tindakan = $row_dt->nama_brg;
                    $total_harga = $row_dt->total + $row_dt->jasa_r;
                }
    
                // insert table tc_trans_pelayanan
                $kode_trans_pelayanan = $this->master->get_max_number("tc_trans_pelayanan","kode_trans_pelayanan");
                
                $dataexc = array(
                    "kode_trans_pelayanan" => $kode_trans_pelayanan,
                    "no_registrasi" => $row_dt->no_registrasi,
                    "no_mr" => $row_dt->no_mr,
                    "nama_pasien_layan" => $_POST['nama_pasien'],
                    "kode_kelompok" => $_POST['kode_kelompok'],
                    "kode_perusahaan" => $_POST['kode_perusahaan'],
                    "tgl_transaksi" => date('Y-m-d H:i:s'),
                    "jenis_tindakan" => 11,
                    "nama_tindakan" => $nama_barang_tindakan ,
                    "bill_rs" => $total_harga,
                    // "kode_ri" => isset($_POST['kode_ri'])?$_POST['kode_ri']:0,
                    // "kode_poli" => isset($_POST['kode_poli'])?$_POST['kode_poli']:0,
                    "jumlah" => $row_dt->jumlah_tebus,
                    "kode_barang" => (string)$kode_brg,
                    "kode_trans_far" => $row_dt->kode_trans_far,
                    "kode_bagian" => $row_dt->kode_bagian,
                    "kode_bagian_asal" => $row_dt->kode_bagian_asal,
                    "kode_profit" => $_POST['kode_profit'],
                    "kd_tr_resep" => $row_dt->relation_id,
                    "no_kunjungan" => (in_array($_POST['kode_profit'], array(1000 , 2000) )) ?$row_dt->no_kunjungan : 0,
                    "status_selesai" => 2,
                    "status_nk" => (in_array($_POST['kode_kelompok'], array(10 , 3) )) ? 1 : 0,
                    "status_karyawan" => ( $row_dt->flag_trans == 'RK' ) ? 1 : 0,
                );
    
                // print_r($dataexc);die;
                $this->db->insert('tc_trans_pelayanan', $dataexc);
    
                $this->db->trans_commit();
    
            }
            
            $status_diantar = ($_POST['submit'] == 'ditunggu') ? 'N' : 'Y';

            // update status transaksi
            $this->db->where('kode_trans_far', $ID);
            $this->db->update('fr_tc_far', array('status_transaksi' => 1, 'kode_profit' => $_POST['kode_profit'], 'nama_pasien' => $_POST['nama_pasien'], 'no_mr' => $_POST['no_mr'], 'resep_diantar' => $status_diantar) );

            // update fr_tc_far_detail_log
            $this->db->where('kode_trans_far', $ID);
            $this->db->update('fr_tc_far_detail_log', array('status_tebus' => 1, 'status_input' => 1, 'jumlah_retur' => 0) );
    
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
            // if($_POST['is_rollback'] == 0){
            //     $this->print_tracer_gudang($ID);
            // }
            echo json_encode(array('status' => 200, 'message' => 'Proses Berhasil Dilakukan', 'kode_trans_far' => $ID ));
        }
        
                
    }

    function get_total_biaya_farmasi($no_resep){

        /*resep biasa*/
        $total_resep_biasa = ($no_resep != 0) ? $this->Process_entry_resep->sum_total_biaya_far($no_resep) : 0;
        echo json_encode(array('total' => $total_resep_biasa));
    }

    function nota_farmasi($kode_trans_far){
        $resep_log = $this->Etiket_obat->get_detail_resep_data($kode_trans_far)->result_array();
        foreach($resep_log as $row){
            $racikan = ($row['flag_resep']=='racikan') ? $this->Entry_resep_racikan->get_detail_by_id($row['relation_id']) : [] ;
            $row['racikan'][] = $racikan;
            $getData[] = $row;
        }
        $tipe = isset($_GET['tipe'])?$_GET['tipe']:'biasa';
        $data = array(
            'resep' => $getData,
            'tipe_resep' => $tipe,
        );
        // echo '<pre>'; print_r($data);die;
        $view_name = ($tipe == 'resep_kronis')?'preview_nota_farmasi_rk':'preview_nota_farmasi'; 
        $this->load->view('farmasi/'.$view_name.'', $data);

    }

    function preview_entry($kode_trans_far){

        /*breadcrumbs for edit*/
        $this->breadcrumbs->push('Preview Transaksi Farmasi', 'Entry_resep_ri_rj/'.strtolower(get_class($this)).'/'.__FUNCTION__.'/'.$kode_trans_far);

        $data = array(
            'title' => 'Preview Transaksi' ,
            'breadcrumbs' => $this->breadcrumbs->show(),
            'flag' => $_GET['flag'],
            'status_lunas' => isset($_GET['status_lunas']) ? $_GET['status_lunas'] : 0,
        );
        $resep_log = $this->Etiket_obat->get_detail_resep_data($kode_trans_far)->result_array();
        // echo '<pre>'; print_r($resep_log);die;
        $getData = array();
        $getDataResepKronis = array();
        foreach($resep_log as $row){
            $racikan = ($row['flag_resep']=='racikan') ? $this->Entry_resep_racikan->get_detail_by_id($row['relation_id']) : [] ;
            $row['racikan'][] = $racikan;
            $getData[] = $row;
            if($row['jumlah_obat_23'] > 0){
                $getDataResepKronis[] = $row;
            }
        }
        $data['resep'] = $getData;
        $data['resep_kronis'] = $getDataResepKronis;
        $data['no_mr'] = isset($getData[0]['no_mr'])?$getData[0]['no_mr']:0;
        $data['kode_trans_far'] = $kode_trans_far;
        
        
        $this->load->view('farmasi/preview_entry', $data);

    }

    public function print_tracer_gudang($kode_trans_far)
    {   
        // $data = array();
        $resep_log = $this->Etiket_obat->get_detail_resep_data($kode_trans_far)->result_array();
        $getData = array();
        $getDataResepKronis = array();
        foreach($resep_log as $row){
            $racikan = ($row['flag_resep']=='racikan') ? $this->Entry_resep_racikan->get_detail_by_id($row['relation_id']) : [] ;
            $row['racikan'][] = $racikan;
            $getData[] = $row;
            if($row['jumlah_obat_23'] > 0){
                $getDataResepKronis[] = $row;
            }
        }
        $data['resep'] = $getData;
        $data['resep_kronis'] = $getDataResepKronis;

        // foreach($resep_log as $row){
        //     $racikan = ($row['flag_resep']=='racikan') ? $this->Entry_resep_racikan->get_detail_by_id($row['relation_id']) : [] ;
        //     $row['racikan'][] = $racikan;
        //     $getData[] = $row;
        //     if($row['jumlah_obat_23'] > 0){
        //         $getDataResepKronis[] = $row;
        //     }
        // }
        // $data['resepAll'] = array_merge($getData, $getDataResepKronis);
        $data['no_mr'] = isset($getData[0]['no_mr'])?$getData[0]['no_mr']:0;
        return $this->print_escpos->print_resep_gudang($data);
    }

    public function print_tracer_gudang_view($kode_trans_far)
    {   
        // $data = array();
        $resep_log = $this->Etiket_obat->get_detail_resep_data($kode_trans_far)->result_array();
        $getData = array();
        $getDataResepKronis = array();
        foreach($resep_log as $row){
            $racikan = ($row['flag_resep']=='racikan') ? $this->Entry_resep_racikan->get_detail_by_id($row['relation_id']) : [] ;
            $row['racikan'][] = $racikan;
            $getData[] = $row;
            if($row['jumlah_obat_23'] > 0){
                $getDataResepKronis[] = $row;
            }
        }
        $data['resep'] = $getData;
        $data['resep_kronis'] = $getDataResepKronis;
        
        $data['no_mr'] = isset($getData[0]['no_mr'])?$getData[0]['no_mr']:0;
        $this->print_escpos->print_resep_gudang($data);
        $this->load->view('farmasi/preview_tracer', $data);
    }




}

/* End of file example.php */
/* Location: ./application/functiones/example/controllers/example.php */
