<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

 Final Class Inventory_lib {

    
    function save_mutasi_stok($data='') {
        
        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        
        /*cek selisih stok*/
        $selisih_stok = abs($data['new_stok']-$data['last_stok']);

        if($data['last_stok'] > $data['new_stok']){
            /*maka tercatat sebagai pengeluaran*/
            $new_id_kartu = $this->write_kartu_stok($data, $selisih_stok, 'pengeluaran', 9);
            $this->update_depo_stok($data, $selisih_stok, '-', $new_id_kartu);
        }else{
            $new_id_kartu = $this->write_kartu_stok($data, $selisih_stok, 'pemasukan', 9);
            $this->update_depo_stok($data, $selisih_stok, '+', $new_id_kartu);
        }

        return true;
        
    }

    function write_kartu_stok($data, $value, $tipe, $jenisKartuStok) {
        
        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        $CI->load->library('master');

        $id_kartu = $CI->master->get_max_number($data['table_kartu_flag'], 'id_kartu');
        $jenis_transaksi = $db->get_where('mt_jenis_kartu_stok', array('jenis_transaksi' => $jenisKartuStok))->row();

        $field=array();
        $field["id_kartu"] = $id_kartu;
        $field["kode_brg"] = $data['kode_brg'];
        $field["stok_awal"] = $data['last_stok'];
        $field["pemasukan"] = ($tipe=='pemasukan')?$value:0;
        $field["pengeluaran"] = ($tipe=='pengeluaran')?$value:0;
        $field["stok_akhir"] = $data['new_stok'];
        $field["jenis_transaksi"] = $jenisKartuStok;
        $field["kode_bagian"] = $data['kode_bagian'];
        $field["keterangan"] = $jenis_transaksi->nama_jenis;
        $field["nama_petugas"] = $data['petugas'];
        $field["tgl_input"]= date('Y-m-d H:i:s');
        $field["agenda_so_id"]= $data['agenda_so_id'];

        $db->insert($data['table_kartu_flag'], $field);

        return $id_kartu;
        
    }

    function update_depo_stok($data, $value, $sign, $new_id_kartu) {
        
        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        
        /*cek selisih stok*/

        $last = ($sign=='-') ? $data['last_stok'] - $value : $data['last_stok'] + $value;
        $db->update($data['table_depo_flag'], array('jml_sat_kcl' => $last, 'last_id_tc_stok_opname' => $data['id_tc_stok_opname'], 'id_kartu' => $new_id_kartu), array('kode_brg' => $data['kode_brg'], 'kode_bagian' => $data['kode_bagian']) );

        if( $data['kode_bagian'] == '070101' ){
            $db->update('mt_rekap_stok_nm', array('jml_sat_kcl' => $last), array('kode_brg' => $data['kode_brg'])  );
        }

        if( $data['kode_bagian'] == '060201' ){
            $db->update('mt_rekap_stok', array('jml_sat_kcl' => $last), array('kode_brg' => $data['kode_brg'])  );
        }

        return true;
        
    }


}

?> 