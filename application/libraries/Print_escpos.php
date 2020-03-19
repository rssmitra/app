<?php 
require_once('PHPPrint_escpos/autoload.php');
use Mike42\Escpos\Printer;
//use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\EscposImage;

class Print_escpos{

    //$pop = POP3::popBeforeSmtp('pop3.example.com', 110, 30, 'username', 'password', 1);

	function print_direct($params){

        /*$connector = new FilePrintConnector("php://stdout");*/
        $CI =& get_instance();
       
        /* Text */
       
        $connector = new WindowsPrintConnector("smb://10.10.10.62/EPSON TM-U220 Receipt");
        $printer = new Printer($connector);

        /* Initialize */
        //$printer -> initialize();

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

        $printer -> setJustification(Printer::JUSTIFY_CENTER);

        $printer -> setTextSize(2, 2);
        $printer -> text("RS. Setia Mitra\n");

        $printer -> setTextSize(1, 1);
        $printer -> text("Jl. RS. Fatmawati No. 80-82\n");
        $printer -> text("Jakarta Selatan\n");

        //$printer -> setTextSize(3, 3);
        $printer -> setEmphasis(true);
        //$printer -> selectPrintMode(Printer::MODE_DOUBLE_HEIGHT);
        $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
        $printer -> text("TRACER\n");      
        $printer -> selectPrintMode();
        //$printer -> selectPrintMode();
        $printer -> setEmphasis(false);

        $printer -> setTextSize(1, 1);
        $printer -> text("---------------------------------\n");
        $printer -> setTextSize(2, 2);
        $printer -> text("No Antrian: ".$no."\n");  
        $printer -> setEmphasis(false);
        
        $printer -> setJustification(Printer::JUSTIFY_LEFT);

        $printer -> setTextSize(2,2);
        $printer -> text("No MR ");

        $printer -> setJustification(Printer::JUSTIFY_CENTER);

        $printer -> setEmphasis(true);
        //$printer -> selectPrintMode(Printer::MODE_DOUBLE_HEIGHT);
        $printer -> selectPrintMode(Printer::MODE_DOUBLE_WIDTH);
        $printer -> text(": ".$params['no_mr']."\n");
        //$printer -> selectPrintMode();
        $printer -> selectPrintMode();
        $printer -> setEmphasis(false);

        $printer -> setTextSize(1,1);
        $printer -> text("No Reg  : ".$no_reg."\n");

        $printer -> setTextSize(1,1);
        $printer -> text("Nama    : ".$nama_pasien."\n");

        $printer -> setTextSize(1,1);
        $printer -> text("Nasabah : ".$nama_perusahaan."\n");

        $printer -> setTextSize(1,1);
        $printer -> text("Bagian  : ".$klinik."\n");

        $printer -> setTextSize(1,1);
        $printer -> text("Dokter  : ".$dokter."\n");

        $printer -> setTextSize(1,1);
        $printer -> text("Tanggal : ".$tanggal."\n");

        $printer -> setTextSize(1,1);
        $printer -> text("Petugas : ".$petugas."\n");

        $printer -> setEmphasis(true);
        $printer -> setTextSize(2, 2);
        $printer -> text("No Antrian: ".$no."\n");  
        $printer -> setEmphasis(false);


        // $printer -> setJustification(Printer::JUSTIFY_RIGHT);
        // $printer -> setUnderline(Printer::UNDERLINE_DOUBLE );

        // $printer -> feed(1);

        // $printer -> setTextSize(2,2);
        // $printer -> text("No: ".$no."\n");

        $printer -> cut(Printer::CUT_FULL, 1);

        /* Pulse */
        $printer -> pulse();

        /* Always close the printer! On some PrintConnectors, no actual
        * data is sent until the printer is closed. */

        $printer -> close();
    

        return true;
       
    }

    public function print_testing()
    {
        # code...
        $CI =& get_instance();
                     
        $p = printer_open("\\\\10.10.10.3\EPSON TM-T88V Receipt");
       
        $var_magin_left = 30;
        printer_set_option($p, PRINTER_MODE, "RAW");
        
    
        printer_start_doc($p);
        printer_start_page($p);

        // header
        $font = printer_create_font("Arial", 50, 20, PRINTER_FW_BOLD, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "RS. Setia Mitra",170,0);

        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Jl. RS. Fatmawati No. 80-8, Jakarta Selatan", 110, 40);
        $pen = printer_create_pen(PRINTER_PEN_SOLID, 3, "000000");
        printer_select_pen($p, $pen);
        printer_draw_line($p, 30, 70, 610, 70);

        // end header

        $font = printer_create_font("Arial", 45, 20, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, '' , 260, 80);

        $font = printer_create_font("Arial", 80, 40, PRINTER_FW_BOLD, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, '1' , 220, 120);

        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Klinik", $var_magin_left, 200);
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, " : " , 110, 200);

        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Dokter", $var_magin_left, 230);
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, " : " , 110, 230);

        /*Tanggal*/
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Tanggal", $var_magin_left, 260);
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, " : " , 110, 260);

        /*catatan*/
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Catatan ",$var_magin_left, 300);
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, " *Jika terlewat 5 nomor antrian, harap ambil antrian baru ! ", 110, 330);
        
        /*printer_draw_text($p, "Catatan ",$var_magin_left, 300);*/


        printer_draw_text($p,  "Tanggal cetak: " ,$var_magin_left, 400);
                        
        printer_delete_font($font);
        printer_delete_pen($pen);
        printer_end_page($p);
        printer_end_doc($p);

        printer_close($p);
       
    }

    public function print_resep_gudang($params)
    {
        # code...
        $CI =& get_instance();
                     
        $p = printer_open("\\\\10.10.10.206\EPSON TM-T88V Receipt");
       
        $var_magin_left = 30;
        printer_set_option($p, PRINTER_MODE, "RAW");
        
    
        printer_start_doc($p);
        printer_start_page($p);

        // header
        $font = printer_create_font("Arial", 35, 13, PRINTER_FW_BOLD, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "INSTALASI FARMASI",140,0);

        $font = printer_create_font("Arial", 35, 13, PRINTER_FW_BOLD, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "RS. SETIA MITRA", 165, 30);

        // line
        $pen = printer_create_pen(PRINTER_PEN_SOLID, 3, "000000");
        printer_select_pen($p, $pen);
        printer_draw_line($p, 30, 70, 610, 70);

        // end header

        // kode trans
        $font = printer_create_font("Arial", 45, 15, PRINTER_FW_BOLD, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "No. ".$params[0]->kode_trans_far , 170, 80);

        // no mr
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "No. Mr", $var_magin_left, 135);
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, " : ". $params[0]->no_mr, 160, 135);

        // nama pasien
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Nama Pasien", $var_magin_left, 165);
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, " : ". $params[0]->nama_pasien , 160, 165);

        // dokter pengirim
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Dokter", $var_magin_left, 195);
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, " : ". $params[0]->dokter_pengirim , 160, 195);

        // petugas
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Petugas", $var_magin_left, 225);
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, " : " , 160, 225);

        // title
        $font = printer_create_font("Arial", 30, 10, PRINTER_FW_BOLD, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Pemesanan Obat : " , $var_magin_left, 255);

        $linespace = 300;
        foreach($params as $row_dt){
            // nama obat
            $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
            printer_select_font($p, $font);
            printer_draw_text($p, $row_dt->nama_brg, $var_magin_left , $linespace);
            $linespace += 30 ;
        }

        // keterangan
        $ln_keterangan = $linespace + 5;
        $font = printer_create_font("Arial", 30, 10, PRINTER_FW_BOLD, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Keterangan : " , $var_magin_left, $ln_keterangan);
        
        // tanggal cetak
        $line_tgl = $ln_keterangan + 250;
        $font = printer_create_font("Arial", 25, 8, PRINTER_FW_BOLD, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Tanggal transaksi : ".$CI->tanggal->formatDateTime($params[0]->tgl_trans) , $var_magin_left, $line_tgl);

                        
        printer_delete_font($font);
        printer_delete_pen($pen);
        printer_end_page($p);
        printer_end_doc($p);

        printer_close($p);
       
    }

    function title(Printer $printer, $text)
    {
        $printer -> selectPrintMode(Printer::MODE_EMPHASIZED);
        $printer -> text("\n" . $text);
        $printer -> selectPrintMode(); // Reset
    }
}

?>