<?php
/*
 * To change this template, choose Tools | templates
 * and open the template in the editor.
 */

final Class Inventory{

    public function create_kode_brg( $params ) {
        $CI =&get_instance();
        $db = $CI->load->database('default', TRUE);
        $CI->load->library('master');
        // define table
        $table = ($params['flag'] == 'medis' ) ? 'mt_barang' : 'mt_barang_nm' ;
        // get last brg by kode_generik
        $db->where('kode_generik', $params['kode_generik']);
        $db->get( $table );
    }
}

?>