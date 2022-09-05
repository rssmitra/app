<?php
/*
 * To change this template, choose Tools | templates
 * and open the template in the editor.
 */

final Class Stok_barang{

    function stock_process($kodeBrg, $jumlah, $kodeBagian, $jenisKartuStok, $keterangan="", $flag) {

        // restore => untuk mengembalikan stok ke jumlah sebelumnya
        // reduce => untuk mengurangi stok sesuai dengan jumlah yang dikirim

        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        $CI->load->library('master');
        
        $t_kartu_stok = ($kodeBagian=='070101') ? 'tc_kartu_stok_nm' : 'tc_kartu_stok' ;
        $t_depo_stok = ($kodeBagian=='070101') ? 'mt_depo_stok_nm' : 'mt_depo_stok' ;
        $mt_rekap_stok = ($kodeBagian=='070101') ? 'mt_rekap_stok_nm' : 'mt_rekap_stok' ;

        $dataexc=array();
        
        if( $jumlah > 0 ) {
            /*get last kartu stok*/
            $kartu_stok = $db->order_by('id_kartu', 'DESC')->get_where($t_kartu_stok, array('kode_brg' => $kodeBrg, 'kode_bagian' => $kodeBagian) )->row();
            
            /*jumlah setelah ditambah dengan stok sebelumnya*/
            $stok_akhir_mutasi = isset($kartu_stok->stok_akhir)?$kartu_stok->stok_akhir:0;
            if($flag=='restore'){
                $last_stok = $stok_akhir_mutasi + $jumlah;
            }else{
                $last_stok = $stok_akhir_mutasi - $jumlah;
            }
            
            /*get max id kartu stok*/
            $id_kartu = $CI->master->get_max_number($t_kartu_stok, 'id_kartu');
            $dataexc["id_kartu"] = $id_kartu;
            $dataexc["kode_brg"] = $kodeBrg;
            $dataexc["stok_awal"] = $stok_akhir_mutasi;
            $dataexc["pemasukan"] = ($flag=='restore')?$jumlah:0;
            $dataexc["pengeluaran"] = ($flag=='reduce')?$jumlah:0;
            $dataexc["stok_akhir"] = $last_stok;
            $dataexc["jenis_transaksi"] = $jenisKartuStok;
            
            if ($jenisKartuStok==15){
                /*untuk jenis kartu cito*/
                $depo_cito = $db->get_where('fr_depo_cito', array('kode_brg' => $kodeBrg) )->row();
                $dataexc["harga_beli"] = $depo_cito->harga_beli;
                $dataexc["harga_jual"] = $depo_cito->harga_jual;
            }
            
            $dataexc["kode_bagian"] = $kodeBagian;
            $ket_jenis_kartu = $db->get_where('mt_jenis_kartu_stok', array('jenis_transaksi' => $jenisKartuStok) )->row();
            $dataexc["keterangan"] = $ket_jenis_kartu->nama_jenis. ' ' .$keterangan;
            $dataexc["petugas"] = $CI->session->userdata('user')->user_id;
            $dataexc["tgl_input"]= date('Y-m-d H:i:s');
            // print_r($dataexc);die;
            $db->insert($t_kartu_stok, $dataexc);
            
            /*update mt_depo_stok*/
            $db->update($t_depo_stok ,array('jml_sat_kcl' => $last_stok, 'id_kartu' => $id_kartu, 'is_active' => 1), array('kode_brg' => $kodeBrg, 'kode_bagian' => $kodeBagian ) );

            $db->update($mt_rekap_stok ,array('jml_sat_kcl' => $last_stok), array('kode_brg' => $kodeBrg, 'kode_bagian_gudang' => $kodeBagian ) );
            // print_r($db->last_query());die;
                        
        }

        // print_r($dataexc);die;
        return $dataexc;

    }

    function stock_process_racikan($kodeBrg, $jumlah, $kodeBagian, $jenisKartuStok, $keterangan="", $flag) {

        // restore => untuk mengembalikan stok ke jumlah sebelumnya
        // reduce => untuk mengurangi stok sesuai dengan jumlah yang dikirim

        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        $CI->load->library('master');
        
        $t_kartu_stok = ($kodeBagian=='070101') ? 'tc_kartu_stok_nm' : 'tc_kartu_stok' ;
        $t_depo_stok = ($kodeBagian=='070101') ? 'mt_depo_stok_nm' : 'mt_depo_stok' ;
        $mt_rekap_stok = ($kodeBagian=='070101') ? 'mt_rekap_stok_nm' : 'mt_rekap_stok' ;

        $dataexc=array();
        
        if( $jumlah > 0 ) {
            /*get last kartu stok*/
            $kartu_stok = $db->order_by('id_kartu', 'DESC')->get_where($t_kartu_stok, array('kode_brg' => $kodeBrg, 'kode_bagian' => $kodeBagian) )->row();
            
            /*jumlah setelah ditambah dengan stok sebelumnya*/
            $stok_akhir_mutasi = isset($kartu_stok->stok_akhir)?$kartu_stok->stok_akhir:0;
            if($flag=='restore'){
                $last_stok = $stok_akhir_mutasi + $jumlah;
            }else{
                $last_stok = $stok_akhir_mutasi - $jumlah;
            }
            
            /*get max id kartu stok*/
            $id_kartu = $CI->master->get_max_number($t_kartu_stok, 'id_kartu');
            $dataexc["id_kartu"] = $id_kartu;
            $dataexc["kode_brg"] = $kodeBrg;
            $dataexc["stok_awal"] = $stok_akhir_mutasi;
            $dataexc["pemasukan"] = ($flag=='restore')?$jumlah:0;
            $dataexc["pengeluaran"] = ($flag=='reduce')?$jumlah:0;
            $dataexc["stok_akhir"] = $last_stok;
            $dataexc["jenis_transaksi"] = $jenisKartuStok;
            
            if ($jenisKartuStok==15){
                /*untuk jenis kartu cito*/
                $depo_cito = $db->get_where('fr_depo_cito', array('kode_brg' => $kodeBrg) )->row();
                $dataexc["harga_beli"] = $depo_cito->harga_beli;
                $dataexc["harga_jual"] = $depo_cito->harga_jual;
            }
            
            $dataexc["kode_bagian"] = $kodeBagian;
            $ket_jenis_kartu = $db->get_where('mt_jenis_kartu_stok', array('jenis_transaksi' => $jenisKartuStok) )->row();
            $dataexc["keterangan"] = $ket_jenis_kartu->nama_jenis. ' ' .$keterangan;
            $dataexc["petugas"] = $CI->session->userdata('user')->user_id;
            $dataexc["tgl_input"]= date('Y-m-d H:i:s');
            // print_r($dataexc);die;
            $db->insert($t_kartu_stok, $dataexc);
            
            /*update mt_depo_stok*/
            $db->update($t_depo_stok ,array('jml_sat_kcl' => $last_stok, 'id_kartu' => $id_kartu, 'is_active' => 1), array('kode_brg' => $kodeBrg, 'kode_bagian' => $kodeBagian ) );

            $db->update($mt_rekap_stok ,array('jml_sat_kcl' => $last_stok), array('kode_brg' => $kodeBrg, 'kode_bagian_gudang' => $kodeBagian ) );
            // print_r($db->last_query());die;
                        
        }

        // print_r($dataexc);die;
        return $dataexc;

    }

    function stock_process_cito($kodeBrg, $jumlah, $kodeBagian, $jenisKartuStok, $keterangan="", $flag) {

        // restore => untuk mengembalikan stok ke jumlah sebelumnya
        // reduce => untuk mengurangi stok sesuai dengan jumlah yang dikirim

        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        $CI->load->library('master');
        
        $t_kartu_stok = 'tc_kartu_stokcito' ;
        $t_depo_stok = 'fr_depo_cito' ;

        $dataexc=array();
        
        if( $jumlah > 0 ) {
            /*get last kartu stok*/
            $kartu_stok = $db->order_by('id_kartucito', 'DESC')->get_where($t_kartu_stok, array('kode_brg' => $kodeBrg, 'kode_bagian' => $kodeBagian) )->row();
            $depo_cito = $db->get_where('fr_depo_cito', array('kode_brg' => $kodeBrg) )->row();
            
            /*jumlah setelah ditambah dengan stok sebelumnya*/
            $stok_akhir_mutasi = isset($kartu_stok->stok_akhir)?$kartu_stok->stok_akhir:0;
            if($flag=='restore'){
                $last_stok = $stok_akhir_mutasi + $jumlah;
            }else{
                $last_stok = $stok_akhir_mutasi - $jumlah;
            }
            
            /*get max id kartu stok*/
            $id_kartucito = $CI->master->get_max_number($t_kartu_stok, 'id_kartucito');
            $dataexc["id_kartucito"] = $id_kartucito;
            $dataexc["kode_brg"] = $kodeBrg;
            $dataexc["stok_awal"] = $stok_akhir_mutasi;
            $dataexc["pemasukan"] = ($flag=='restore')?$jumlah:0;
            $dataexc["pengeluaran"] = ($flag=='reduce')?$jumlah:0;
            $dataexc["stok_akhir"] = $last_stok;
            $dataexc["jenis_transaksi"] = $jenisKartuStok;
            $dataexc["kode_bagian"] = $kodeBagian;

            $ket_jenis_kartu = $db->get_where('mt_jenis_kartu_stok', array('jenis_transaksi' => $jenisKartuStok) )->row();

            $dataexc["keterangan"] = $ket_jenis_kartu->nama_jenis. ' ' .$keterangan;
            $dataexc["petugas"] = $CI->session->userdata('user')->user_id;
            $dataexc["tgl_input"]= date('Y-m-d H:i:s');
            // print_r($dataexc);die;
            $db->insert($t_kartu_stok, $dataexc);
            
            /*update mt_depo_stokcito*/
            $db->update($t_depo_stok ,array('jml_sat_kcl' => $last_stok, 'is_active' => 1), array('kode_brg' => $kodeBrg) );

                        
        }

        // print_r($dataexc);die;
        return $dataexc;

    }

    function stock_process_depo($kodeBrg, $jumlah, $kodeBagian, $jenisKartuStok, $keterangan="", $flag, $kode_bagian_minta='') {

        // restore => untuk mengembalikan stok ke jumlah sebelumnya
        // reduce => untuk mengurangi stok sesuai dengan jumlah yang dikirim

        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        $CI->load->library('master');

        $t_kartu_stok = ($kodeBagian=='070101') ? 'tc_kartu_stok_nm' : 'tc_kartu_stok' ;
        $t_depo_stok = ($kodeBagian=='070101') ? 'mt_depo_stok_nm' : 'mt_depo_stok' ;
        $mt_rekap_stok = ($kodeBagian=='070101') ? 'mt_rekap_stok_nm' : 'mt_rekap_stok' ;

        $dataexc=array();
        
        if( $jumlah > 0 ) {

            if( $kode_bagian_minta != '' ){

                /*get last kartu stok*/
                $kartu_stok = $db->order_by('id_kartu', 'DESC')->get_where($t_kartu_stok, array('kode_brg' => $kodeBrg, 'kode_bagian' => $kode_bagian_minta) )->row();
                // print_r($kartu_stok);die;
                /*jumlah setelah ditambah dengan stok sebelumnya*/
                if($flag=='restore'){
                    $last_stok = ( !empty($kartu_stok->stok_akhir) ) ? $kartu_stok->stok_akhir + $jumlah : $jumlah;
                }else{
                    $last_stok = ( !empty($kartu_stok->stok_akhir) ) ? $kartu_stok->stok_akhir - $jumlah : $jumlah;
                }
                /*get max id kartu stok*/
                $id_kartu = $CI->master->get_max_number($t_kartu_stok, 'id_kartu');
                $dataexc["id_kartu"] = $id_kartu;
                $dataexc["kode_brg"] = $kodeBrg;
                $dataexc["stok_awal"] = $kartu_stok->stok_akhir;
                $dataexc["pemasukan"] = ($flag=='restore')?$jumlah:0;
                $dataexc["pengeluaran"] = ($flag=='reduce')?$jumlah:0;
                $dataexc["stok_akhir"] = $last_stok;
                $dataexc["jenis_transaksi"] = $jenisKartuStok;
                $dataexc["kode_bagian"] = $kode_bagian_minta;

                $ket_jenis_kartu = $db->get_where('mt_jenis_kartu_stok', array('jenis_transaksi' => $jenisKartuStok) )->row();
                $dataexc["keterangan"] = $ket_jenis_kartu->nama_jenis. ' ' .$keterangan;
                $dataexc["petugas"] = $CI->session->userdata('user')->user_id;
                $dataexc["tgl_input"]= date('Y-m-d H:i:s');
                $db->insert($t_kartu_stok, $dataexc);

                /*update mt_depo_stok*/
                // cek dt_depo existing
                $dt_depo = $db->get_where($t_depo_stok, array( 'kode_brg' => $kodeBrg, 'kode_bagian' => $kode_bagian_minta ) );
                
                if( $dt_depo->num_rows() > 0 ){
                    $db->update($t_depo_stok ,array('jml_sat_kcl' => $last_stok, 'id_kartu' => $id_kartu, 'is_active' => 1), array('kode_brg' => $kodeBrg, 'kode_bagian' => $kode_bagian_minta ) );
                }else{
                    $dt_depo = array(
                        'kode_depo_stok' => $CI->master->get_max_number($t_depo_stok, 'kode_depo_stok'),
                        'id_kartu' => $id_kartu,
                        'kode_brg' => $kodeBrg,
                        'jml_sat_kcl' => $last_stok,
                        'stok_minimum' => 0,
                        'stok_maksimum' => 0,
                        'kode_bagian' => $kode_bagian_minta,
                        'created_date' => date('Y-m-d H:i:s'),
                        'created_by' => json_encode(array('user_id' => $CI->session->userdata('user')->user_id, 'fullname' => $CI->session->userdata('user')->fullname )),
                    );
                    if( $kodeBagian != '070101' ){
                        $db->query('SET IDENTITY_INSERT '.$t_depo_stok.' ON');
                    }
                    $db->insert($t_depo_stok, $dt_depo);
                }                
                
            }
            
        }

        return $dataexc;

    }

    function kurang_stok_depo($kodeBrg, $jumlah, $kodeBagian, $jenisKartuStok, $keterangan="", $flag, $kode_bagian_minta='') {

        // restore => untuk mengembalikan stok ke jumlah sebelumnya
        // reduce => untuk mengurangi stok sesuai dengan jumlah yang dikirim

        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        $CI->load->library('master');

        $t_kartu_stok = ($kodeBagian=='070101') ? 'tc_kartu_stok_nm' : 'tc_kartu_stok' ;
        $t_depo_stok = ($kodeBagian=='070101') ? 'mt_depo_stok_nm' : 'mt_depo_stok' ;
        $mt_rekap_stok = ($kodeBagian=='070101') ? 'mt_rekap_stok_nm' : 'mt_rekap_stok' ;

        $dataexc=array();
        
        if( $jumlah > 0 ) {

            if( $kode_bagian_minta != '' ){

                /*get last kartu stok*/
                $kartu_stok = $db->order_by('id_kartu', 'DESC')->get_where($t_kartu_stok, array('kode_brg' => $kodeBrg, 'kode_bagian' => $kode_bagian_minta) )->row();
                // print_r($kartu_stok);die;
                /*jumlah setelah ditambah dengan stok sebelumnya*/
                if($flag=='restore'){
                    $last_stok = ( !empty($kartu_stok->stok_akhir) ) ? $kartu_stok->stok_akhir + $jumlah : $jumlah;
                }else{
                    $last_stok = ( !empty($kartu_stok->stok_akhir) ) ? $kartu_stok->stok_akhir - $jumlah : $jumlah;
                }
                /*get max id kartu stok*/
                $id_kartu = $CI->master->get_max_number($t_kartu_stok, 'id_kartu');
                $dataexc["id_kartu"] = $id_kartu;
                $dataexc["kode_brg"] = $kodeBrg;
                $dataexc["stok_awal"] = $kartu_stok->stok_akhir;
                $dataexc["pemasukan"] = 0;
                $dataexc["pengeluaran"] = ($flag=='reduce')?$jumlah:0;
                $dataexc["stok_akhir"] = $last_stok;
                $dataexc["jenis_transaksi"] = $jenisKartuStok;
                $dataexc["kode_bagian"] = $kode_bagian_minta;

                $ket_jenis_kartu = $db->get_where('mt_jenis_kartu_stok', array('jenis_transaksi' => $jenisKartuStok) )->row();
                $dataexc["keterangan"] = $ket_jenis_kartu->nama_jenis. ' ' .$keterangan;
                $dataexc["petugas"] = $CI->session->userdata('user')->user_id;
                $dataexc["tgl_input"]= date('Y-m-d H:i:s');
                $db->insert($t_kartu_stok, $dataexc);

                /*update mt_depo_stok*/
                // cek dt_depo existing
                $dt_depo = $db->get_where($t_depo_stok, array( 'kode_brg' => $kodeBrg, 'kode_bagian' => $kode_bagian_minta ) );
                
                if( $dt_depo->num_rows() > 0 ){
                    $db->update($t_depo_stok ,array('jml_sat_kcl' => $last_stok, 'id_kartu' => $id_kartu, 'is_active' => 1), array('kode_brg' => $kodeBrg, 'kode_bagian' => $kode_bagian_minta ) );
                }else{
                    $dt_depo = array(
                        'kode_depo_stok' => $CI->master->get_max_number($t_depo_stok, 'kode_depo_stok'),
                        'id_kartu' => $id_kartu,
                        'kode_brg' => $kodeBrg,
                        'jml_sat_kcl' => $last_stok,
                        'stok_minimum' => 0,
                        'stok_maksimum' => 0,
                        'kode_bagian' => $kode_bagian_minta,
                        'created_date' => date('Y-m-d H:i:s'),
                        'created_by' => json_encode(array('user_id' => $CI->session->userdata('user')->user_id, 'fullname' => $CI->session->userdata('user')->fullname )),
                    );
                    if( $kodeBagian != '070101' ){
                        $db->query('SET IDENTITY_INSERT '.$t_depo_stok.' ON');
                    }
                    $db->insert($t_depo_stok, $dt_depo);
                }                
                
            }
            
        }

        return $dataexc;

    }

    function stock_process_depo_bhp($kodeBrg, $jumlah, $kodeBagian, $jenisKartuStok, $keterangan="", $flag, $kode_bagian_minta='') {

        // restore => untuk mengembalikan stok ke jumlah sebelumnya
        // reduce => untuk mengurangi stok sesuai dengan jumlah yang dikirim

        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        $CI->load->library('master');

        $t_kartu_stok = ($kodeBagian=='070101') ? 'tc_kartu_stok_nm' : 'tc_kartu_stok' ;
        $t_depo_stok = ($kodeBagian=='070101') ? 'mt_depo_stok_nm' : 'mt_depo_stok' ;
        $mt_rekap_stok = ($kodeBagian=='070101') ? 'mt_rekap_stok_nm' : 'mt_rekap_stok' ;

        $dataexc=array();
        
        if( $jumlah > 0 ) {

            if( $kode_bagian_minta != '' ){

                /*get last kartu stok*/
                $kartu_stok = $db->order_by('id_kartu', 'DESC')->get_where($t_kartu_stok, array('kode_brg' => $kodeBrg, 'kode_bagian' => $kode_bagian_minta) )->row();
                // print_r($kartu_stok);die;

                // jika sudah ada stoknya
                if(!empty($kartu_stok)){
                    // take no antion
                    
                }else{
                    // masukan ke stok depo dan kartu stok dulu
                    $datadepo = array(
                        'kode_brg' => $kodeBrg,
                        'stok_minimum' => 0,
                        'kode_bagian' => $kode_bagian_minta,
                        'created_date' => date('Y-m-d H:i:s'),
                        'created_by' => json_encode(array('user_id' => $CI->session->userdata('user')->user_id, 'fullname' => $CI->session->userdata('user')->fullname)),
                    );
                    $db->insert($t_depo_stok, $datadepo);
                }

                /*jumlah setelah ditambah dengan stok sebelumnya*/
                $last_stok = isset($kartu_stok->stok_akhir) ? $kartu_stok->stok_akhir + 0 : 0;
                /*get max id kartu stok*/
                $id_kartu = $CI->master->get_max_number($t_kartu_stok, 'id_kartu');
                $dataexc["id_kartu"] = $id_kartu;
                $dataexc["kode_brg"] = $kodeBrg;
                $dataexc["stok_awal"] = isset($kartu_stok->stok_akhir)?$kartu_stok->stok_akhir:0;
                $dataexc["pemasukan"] = $jumlah;
                $dataexc["pengeluaran"] = $jumlah;
                $dataexc["stok_akhir"] = $last_stok;
                $dataexc["jenis_transaksi"] = $jenisKartuStok;
                $dataexc["kode_bagian"] = $kode_bagian_minta;

                $ket_jenis_kartu = $db->get_where('mt_jenis_kartu_stok', array('jenis_transaksi' => $jenisKartuStok) )->row();
                $dataexc["keterangan"] = 'Distribusi Barang BHP ke Unit '.$keterangan.'';
                $dataexc["petugas"] = $CI->session->userdata('user')->user_id;
                $dataexc["tgl_input"]= date('Y-m-d H:i:s');
                $db->insert($t_kartu_stok, $dataexc);

                /*update mt_depo_stok*/
                // cek dt_depo existing
                $dt_depo = $db->get_where($t_depo_stok, array( 'kode_brg' => $kodeBrg, 'kode_bagian' => $kode_bagian_minta ) );
                
                if( $dt_depo->num_rows() > 0 ){
                    $db->update($t_depo_stok ,array('jml_sat_kcl' => $last_stok, 'id_kartu' => $id_kartu, 'is_active' => 1), array('kode_brg' => $kodeBrg, 'kode_bagian' => $kode_bagian_minta ) );
                }else{
                    $dt_depo = array(
                        'kode_depo_stok' => $CI->master->get_max_number($t_depo_stok, 'kode_depo_stok'),
                        'id_kartu' => $id_kartu,
                        'kode_brg' => $kodeBrg,
                        'jml_sat_kcl' => $last_stok,
                        'stok_minimum' => 0,
                        'stok_maksimum' => 0,
                        'kode_bagian' => $kode_bagian_minta,
                        'created_date' => date('Y-m-d H:i:s'),
                        'created_by' => json_encode(array('user_id' => $CI->session->userdata('user')->user_id, 'fullname' => $CI->session->userdata('user')->fullname )),
                    );
                    if( $kodeBagian != '070101' ){
                        $db->query('SET IDENTITY_INSERT '.$t_depo_stok.' ON');
                    }
                    $db->insert($t_depo_stok, $dt_depo);
                } 

                  
            }
            
        }

        return $dataexc;

    }

    function stock_process_produksi_obat($kodeBrg, $jumlah, $kodeBagian, $jenisKartuStok, $keterangan="", $flag) {

        // restore => untuk mengembalikan stok ke jumlah sebelumnya
        // reduce => untuk mengurangi stok sesuai dengan jumlah yang dikirim

        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        $CI->load->library('master');
        
        $t_kartu_stok = ($kodeBagian=='070101') ? 'tc_kartu_stok_nm' : 'tc_kartu_stok' ;
        $t_depo_stok = ($kodeBagian=='070101') ? 'mt_depo_stok_nm' : 'mt_depo_stok' ;
        $mt_rekap_stok = ($kodeBagian=='070101') ? 'mt_rekap_stok_nm' : 'mt_rekap_stok' ;

        $dataexc=array();
        
        if( $jumlah > 0 ) {
            /*get last kartu stok*/
            $kartu_stok = $db->order_by('id_kartu', 'DESC')->get_where($t_kartu_stok, array('kode_brg' => $kodeBrg, 'kode_bagian' => $kodeBagian) )->row();
            
            /*jumlah setelah ditambah dengan stok sebelumnya*/
            $stok_akhir_mutasi = isset($kartu_stok->stok_akhir)?$kartu_stok->stok_akhir:0;
            if($flag=='restore'){
                $last_stok = $stok_akhir_mutasi + $jumlah;
            }else{
                $last_stok = $stok_akhir_mutasi - $jumlah;
            }
            
            /*get max id kartu stok*/
            $id_kartu = $CI->master->get_max_number($t_kartu_stok, 'id_kartu');
            $dataexc["id_kartu"] = $id_kartu;
            $dataexc["kode_brg"] = $kodeBrg;
            $dataexc["stok_awal"] = $stok_akhir_mutasi;
            $dataexc["pemasukan"] = ($flag=='restore')?$jumlah:0;
            $dataexc["pengeluaran"] = ($flag=='reduce')?$jumlah:0;
            $dataexc["stok_akhir"] = $last_stok;
            $dataexc["jenis_transaksi"] = $jenisKartuStok;
            
            if ($jenisKartuStok==15){
                /*untuk jenis kartu cito*/
                $depo_cito = $db->get_where('fr_depo_cito', array('kode_brg' => $kodeBrg) )->row();
                $dataexc["harga_beli"] = $depo_cito->harga_beli;
                $dataexc["harga_jual"] = $depo_cito->harga_jual;
            }
            
            $dataexc["kode_bagian"] = $kodeBagian;
            $ket_jenis_kartu = $db->get_where('mt_jenis_kartu_stok', array('jenis_transaksi' => $jenisKartuStok) )->row();
            $dataexc["keterangan"] = $ket_jenis_kartu->nama_jenis. ' ' .$keterangan;
            $dataexc["petugas"] = $CI->session->userdata('user')->user_id;
            $dataexc["tgl_input"]= date('Y-m-d H:i:s');
            // print_r($dataexc);die;
            $db->insert($t_kartu_stok, $dataexc);
            
            /*update mt_depo_stok*/
            $db->update($t_depo_stok ,array('jml_sat_kcl' => $last_stok, 'id_kartu' => $id_kartu, 'is_active' => 1), array('kode_brg' => $kodeBrg, 'kode_bagian' => $kodeBagian ) );
                        
        }

        // print_r($dataexc);die;
        return $dataexc;

    }

}

?>