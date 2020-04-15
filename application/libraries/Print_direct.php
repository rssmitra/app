<?php 


final Class Print_direct {

    //$pop = POP3::popBeforeSmtp('pop3.example.com', 110, 30, 'username', 'password', 1);

	function print_tracer($params){

        $CI =& get_instance();

        $tmpdir = sys_get_temp_dir();
        $file = tempnam($tmpdir, 'ctk');
        $handle = fopen($file, 'w');

        /*$condensed = Chr(27) . Chr(33) . Chr(4);
        $bold1 = Chr(27) . Chr(69);
        $bold0 = Chr(27) . Chr(70);
        $initialized = chr(27).chr(64);
        $condensed1 = chr(15);
        $condensed0 = chr(18);
        $Data = $initialized;
        $Data .= $condensed;*/

        print "<br/>";
        print "<img src=\"$image2\" width=\"16px\" height=\"15px\"\ align=\"absmiddle\"/>";
        print " ";
        echo  'Sorry! Could Not Connect To The Selected Printer';
        print "<br/>";
        

        fwrite($handle, $Data);
        fclose($handle);
        copy($file, "//10.10.10.12/EPSON L120 Series"); # Lakukan cetak
        unlink($file);

	}

    function getHtmlView($params){
        
        $CI =& get_instance();
        $html = '';
        $html .= $CI->load->view('tracer_view', $params);

        if ( ! write_file('./path/to/file.php', $params))
        {
                $html .= 'Unable to write the file';
        }
        else
        {
                echo 'File written!';
        }


        return $html;

    }

    public function printer_php($params)
    {
        # code...
        $CI =& get_instance();
        //print_r($params);die;
        $registrasi = $params['result']['registrasi'];
        $nama_pasien = $registrasi->nama_pasien;
        $nama_perusahaan = ($registrasi->nama_perusahaan)?$registrasi->nama_perusahaan:"UMUM";
        $klinik  = $registrasi->nama_bagian;
        $dokter  = $registrasi->nama_pegawai;
        $tanggal  = $CI->tanggal->formatDateTime($registrasi->tgl_jam_masuk);
        $no_reg = $registrasi->no_registrasi;
        $currentdate = $CI->tanggal->formatDateTime(date("Y-m-d h:i:s"));

        $nomor = $params['result']['no_antrian'];
        $no = isset($nomor->no_antrian)?$nomor->no_antrian:'-';

        $petugas_ = $params['result']['petugas'];
        $petugas = ($petugas_->fullname)?$petugas_->fullname:'-';

        //print_r($no);die;
       
        $p = printer_open("\\\\10.10.10.62\EPSON TM-U220 Receipt");  

        if( $p ){

            //echo 'Connected';

            $var_magin_left = 30;
                    
            $this->print_doc($p, $var_magin_left, $params['no_mr'], $nama_pasien,$nama_perusahaan,$klinik,$dokter,$tanggal,$no_reg,$currentdate, $no, $petugas);

            //$this->print_doc($p, $var_magin_left, $params['no_mr'], $nama_pasien,$nama_perusahaan,$klinik,$dokter,$tanggal,$no_reg,$currentdate, $no, $petugas);
            
            printer_close($p);

            return true;

        }else{

            return false;

        }
       
       
    }

    public function print_doc($p, $var_magin_left, $mr, $nama_pasien,$nama_perusahaan,$klinik,$dokter,$tanggal,$no_reg,$currentdate, $no, $petugas)
    {
        # code...
        $text_h = 14;
        $text_w = 8;

        printer_start_doc($p);
        printer_start_page($p);

        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_BOLD, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, COMP_LONG ,120,0);
        
        $font = printer_create_font("Arial", $text_h, $text_w, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, COMP_ADDRESS_SORT, 50, 26);
        $font = printer_create_font("Arial", 35, 15, PRINTER_FW_BOLD, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "TRACER",130,40);
        $pen = printer_create_pen(PRINTER_PEN_SOLID, 1, "000000");
        printer_select_pen($p, $pen);
        printer_draw_line($p, 0, 75, 550, 75);

        /*Antrian*/
        $font = printer_create_font("Arial", $text_h, $text_w, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "No", 320, 60);
        $font = printer_create_font("Arial", 20, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, ": ".$no."",350, 56);
        
        /*Mr*/
        $font = printer_create_font("Arial", 30, 14, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "No MR", $var_magin_left, 80);
        $font = printer_create_font("Arial", 30, 14, PRINTER_FW_BOLD, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, ": ".$mr."",120, 80);

        /*No Registrasi*/
        $font = printer_create_font("Arial", $text_h, $text_w, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "No Reg", $var_magin_left, 110);
        $font = printer_create_font("Arial", $text_h, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, ": ".$no_reg."",120, 110);

        /*Nama Pasien*/
        $font = printer_create_font("Arial", $text_h, $text_w, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Nama", $var_magin_left, 125);
        $font = printer_create_font("Arial", $text_h, $text_w, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, ": ".$nama_pasien."",120, 125);

        /*Nasabah*/
        $font = printer_create_font("Arial", $text_h, $text_w, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Nasabah", $var_magin_left, 140);
        $font = printer_create_font("Arial", $text_h, $text_w, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, ": ".$nama_perusahaan."",120, 140);

        /*Klinik*/
        $font = printer_create_font("Arial", $text_h, $text_w, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Bagian", $var_magin_left, 155);
        $font = printer_create_font("Arial", $text_h, $text_w, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, ": ".$klinik."",120, 155);

        /*Dokter*/
        $font = printer_create_font("Arial", $text_h, $text_w, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Dokter", $var_magin_left, 170);
        $font = printer_create_font("Arial", $text_h, $text_w, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, ": ".$dokter."",120, 170);

        /*Tanggal*/
        $font = printer_create_font("Arial", $text_h, $text_w, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Tanggal", $var_magin_left, 185);
        $font = printer_create_font("Arial", $text_h, $text_w, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, ": ".$tanggal."",120, 185);

        /*Petugas*/
        $font = printer_create_font("Arial", $text_h, $text_w, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Petugas", $var_magin_left, 200);
        $font = printer_create_font("Arial", $text_h, $text_w, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, ": ".$petugas."",120, 200);

        // $pen = printer_create_pen(PRINTER_PEN_SOLID, 2, "000000");
        // printer_select_pen($p, $pen);
        // printer_draw_line($p, 230, 220, 340, 220);
        // printer_draw_line($p, 230, 220, 230, 275);
        // printer_draw_line($p, 230, 275, 340, 275);
        // printer_draw_line($p, 340, 220, 340, 275);

        // $font = printer_create_font("Arial", $text_h, 5, PRINTER_FW_MEDIUM, false, false, false, 0);
        // printer_select_font($p, $font);
        // printer_draw_text($p, "Antrian", 260, 240);
        
        // $font = printer_create_font("Arial", 40, 15, PRINTER_FW_BOLD, false, false, false, 0);
        // printer_select_font($p, $font);
        // if(strlen($no)>1){
        //     printer_draw_text($p, $no,260, 260);
        // }else{
        //     printer_draw_text($p, $no,275, 260);
        // }

                
        printer_delete_font($font);
        printer_delete_pen($pen);
        printer_end_page($p);
        printer_end_doc($p);
    }

    public function printer_antrian_php($params)
    {
        # code...
        $CI =& get_instance();
        $CI->load->model('counter/Counter_model');
        $log = json_decode($params['log']);
        //print_r($params);die;
        $type = ($params['ant_type']=='umum')?'B':'A';
        $dokter = $log->dokter;
        $klinik  = $log->klinik;
        $jam_praktek  = $log->jam_praktek;
        $tanggal  = $CI->tanggal->formatDate(date('Y-m-d'))."-".$jam_praktek;
        $currentdate = $CI->tanggal->formatDateTime(date("Y-m-d h:i:s"));

        $no = $CI->Counter_model->format_counter_number($params['ant_type'],$params['ant_no']);
              
        $p = printer_open("\\\\10.10.10.38\EPSON TM-T82 ReceiptSA4");
       
        $var_magin_left = 30;
        printer_set_option($p, PRINTER_MODE, "RAW");
        
    
        printer_start_doc($p);
        printer_start_page($p);

        $font = printer_create_font("Arial", 50, 20, PRINTER_FW_BOLD, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, COMP_LONG ,170,0);

        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, COMP_ADDRESS_SORT, 110, 40);
        $pen = printer_create_pen(PRINTER_PEN_SOLID, 3, "000000");
        printer_select_pen($p, $pen);
        printer_draw_line($p, 30, 70, 610, 70);

        $font = printer_create_font("Arial", 45, 20, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, strtoupper($params['ant_type']), 260, 80);

        $font = printer_create_font("Arial", 80, 40, PRINTER_FW_BOLD, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, $no , 220, 120);

        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Klinik", $var_magin_left, 200);
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, " : ".ucwords($klinik), 110, 200);

        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Dokter", $var_magin_left, 230);
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, " : ".$dokter, 110, 230);

        /*Tanggal*/
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Tanggal", $var_magin_left, 260);
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, " : ".$tanggal, 110, 260);

        /*catatan*/
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Catatan ",$var_magin_left, 300);
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, " *Jika terlewat 5 nomor antrian, harap ambil antrian baru ! ", 110, 330);
        
        /*printer_draw_text($p, "Catatan ",$var_magin_left, 300);*/


        printer_draw_text($p,  "Tanggal cetak: ".$currentdate,$var_magin_left, 400);

                
        printer_delete_font($font);
        printer_delete_pen($pen);
        printer_end_page($p);
        printer_end_doc($p);

        printer_close($p);
       
    }
    
}


?>