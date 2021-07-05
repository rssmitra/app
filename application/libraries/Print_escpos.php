<?php 
require_once('PHPPrint_escpos/autoload.php');
use Mike42\Escpos\Printer;
// use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\PrintConnectors\FilePrintConnector;
use Mike42\Escpos\PrintConnectors\WindowsPrintConnector;
use Mike42\Escpos\EscposImage;
use Mike42\Escpos\PrintConnectors\RawbtPrintConnector;
use Mike42\Escpos\CapabilityProfile;

class Print_escpos{

    //$pop = POP3::popBeforeSmtp('pop3.example.com', 110, 30, 'username', 'password', 1);

    public function print_direct_ori($params){

        /*$connector = new FilePrintConnector("php://stdout");*/
        $CI =& get_instance();
       
        /* Text */
        // $connector = new WindowsPrintConnector("smb://10.10.10.62/EPSON TM-U220 Tracer RM");
        // $connector = new NetworkPrintConnector("10.10.10.62", 9100);
        $connector = new FilePrintConnector("php://stdout");
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
        $printer -> text(COMP_LONG);

        $printer -> setTextSize(1, 1);
        $printer -> text(COMP_ADDRESS_SORT);
        // $printer -> text("Jakarta Selatan\n");

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

    public function print_direct_new_bug($params)
    {
        # code...
        $CI =& get_instance();
                     
        $p = printer_open("\\\\10.10.10.62\EPSON TM-U220 ReceiptE4");
       
        $var_margin_left = 30;
        printer_set_option($p, PRINTER_MODE, "RAW");
        
        printer_start_doc($p);
        printer_start_page($p);

        // define variable
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


        // header
        $font = printer_create_font("Arial", 50, 20, PRINTER_FW_BOLD, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, COMP_LONG ,170,0);

        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, COMP_ADDRESS_SORT, 110, 40);
        $pen = printer_create_pen(PRINTER_PEN_SOLID, 3, "000000");
        printer_select_pen($p, $pen);
        printer_draw_line($p, 30, 70, 610, 70);

        // end header

        $font = printer_create_font("Arial", 45, 20, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, '' , 260, 80);

        $font = printer_create_font("Arial", 80, 40, PRINTER_FW_BOLD, false, false, false, 0);
        printer_select_font($p, $font);

        printer_draw_text($p, "No Antrian: ".$no."\n" , 220, 120);

        printer_draw_text($p, "No MR", $var_margin_left, 200);

        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, ": ".$params['no_mr']."\n" , 110, 200);

        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "No Reg  : ".$no_reg."\n", $var_margin_left, 230);
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);

        printer_select_font($p, $font);
        printer_draw_text($p, " : " , 110, 230);

        /*Tanggal*/
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Tanggal", $var_margin_left, 260);
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, " : " , 110, 260);

        /*catatan*/
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Catatan ",$var_margin_left, 300);
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, " *Jika terlewat 5 nomor antrian, harap ambil antrian baru ! ", 110, 330);
        
        /*printer_draw_text($p, "Catatan ",$var_margin_left, 300);*/


        printer_draw_text($p,  "Tanggal cetak: " ,$var_margin_left, 400);
                        
        printer_delete_font($font);
        printer_delete_pen($pen);
        printer_end_page($p);
        printer_end_doc($p);

        printer_close($p);
       
    }

    public function print_testing()
    {
        # code...
        $CI =& get_instance();
                     
        $p = printer_open("\\\\10.10.10.3\EPSON TM-T88V(tracer obat)");
       
        $var_margin_left = 30;
        printer_set_option($p, PRINTER_MODE, "RAW");
        
    
        printer_start_doc($p);
        printer_start_page($p);

        // header
        $font = printer_create_font("Arial", 50, 20, PRINTER_FW_BOLD, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, COMP_LONG ,170,0);

        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, COMP_ADDRESS_SORT, 110, 40);
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
        printer_draw_text($p, "Klinik", $var_margin_left, 200);
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, " : " , 110, 200);

        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Dokter", $var_margin_left, 230);
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, " : " , 110, 230);

        /*Tanggal*/
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Tanggal", $var_margin_left, 260);
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, " : " , 110, 260);

        /*catatan*/
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Catatan ",$var_margin_left, 300);
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, " *Jika terlewat 5 nomor antrian, harap ambil antrian baru ! ", 110, 330);
        
        /*printer_draw_text($p, "Catatan ",$var_margin_left, 300);*/


        printer_draw_text($p,  "Tanggal cetak: " ,$var_margin_left, 400);
                        
        printer_delete_font($font);
        printer_delete_pen($pen);
        printer_end_page($p);
        printer_end_doc($p);

        printer_close($p);
       
    }

    public function print_resep_gudang($params)
    {
        // echo '<pre>';print_r($params); 
        // die;
        # code...
        $CI =& get_instance();
        $dt_index = $params['resep'][0];
        $petugas = json_decode($dt_index['created_by']);
        $p = printer_open("\\\\10.10.10.206\EPSON TM-T88V(tracer obat)");
       
        $var_margin_left = 10;
        printer_set_option($p, PRINTER_MODE, "RAW");
            
        printer_start_doc($p);
        printer_start_page($p);

        // header
        // $font = printer_create_font("Arial", 35, 13, PRINTER_FW_BOLD, false, false, false, 0);
        // printer_select_font($p, $font);
        // printer_draw_text($p, "INSTALASI FARMASI",140,0);

        // $font = printer_create_font("Arial", 35, 13, PRINTER_FW_BOLD, false, false, false, 0);
        // printer_select_font($p, $font);
        // printer_draw_text($p, strtoupper(COMP_LONG), 165, 30);

        // line
        // $pen = printer_create_pen(PRINTER_PEN_SOLID, 3, "000000");
        // printer_select_pen($p, $pen);
        // printer_draw_line($p, 30, 70, 610, 70);

        // end header

        // kode trans
        $flag_resep_diantar = ($dt_index['resep_diantar'] == 'Y') ? '(DIANTAR / DITINGGAL)' : '(DITUNGGU)'; 

        $font = printer_create_font("Arial", 40, 15, PRINTER_FW_BOLD, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, $dt_index['kode_trans_far']." - ".$dt_index['no_resep'] , 10, 0);

        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_BOLD, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, $flag_resep_diantar , 270, 10);

        // no mr
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "No. Mr", $var_margin_left, 65);
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_BOLD, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, " : ". $dt_index['no_mr'], 160, 65);

        // nama pasien
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Nama Pasien", $var_margin_left, 95);
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_BOLD, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, " : ". $dt_index['nama_pasien'] , 160, 95);

        // dokter pengirim
        // $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        // printer_select_font($p, $font);
        // printer_draw_text($p, "Dokter", $var_margin_left, 195);
        // $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        // printer_select_font($p, $font);
        // printer_draw_text($p, " : ". $params['resep'][0]->dokter_pengirim , 160, 195);

        // petugas
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Petugas", $var_margin_left, 125);
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_BOLD, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, " : ". $petugas->fullname, 160, 125);
        // line
        $pen = printer_create_pen(PRINTER_PEN_SOLID, 3, "000000");
        printer_select_pen($p, $pen);
        printer_draw_line($p, 10, 155, 610, 155);

        // title
        // $font = printer_create_font("Arial", 30, 10, PRINTER_FW_BOLD, false, false, false, 0);
        // printer_select_font($p, $font);
        // printer_draw_text($p, "Pemesanan Obat : " , $var_margin_left, 155);

        $linespace = 185;
        $no = 0;
        foreach($params['resep'] as $row_dt){
            $no++;
            $desc = ($row_dt['flag_resep'] == 'racikan') ? 'Racikan Obat' : $row_dt['nama_brg'];

            // no
            $font = printer_create_font("Arial", 25, 10, PRINTER_FW_BOLD, false, false, false, 0);
            printer_select_font($p, $font);
            printer_draw_text($p, $no.".", 10, $linespace);
            // nama obat
            $font = printer_create_font("Arial", 25, 10, PRINTER_FW_BOLD, false, false, false, 0);
            printer_select_font($p, $font);
            $txt_length = (strlen($desc) > 30) ? 'xxx' : '';
            printer_draw_text($p, substr($desc, 0, 30)."".$txt_length, 30 , $linespace);
            // jumlah
            $font = printer_create_font("Arial", 25, 10, PRINTER_FW_BOLD, false, false, false, 0);
            printer_select_font($p, $font);
            $racikan = $row_dt['racikan'][0];
            $satuan = ($row_dt['flag_resep'] == 'racikan') ? $racikan[0]->satuan_racikan : $row_dt['satuan_kecil'];
            // printer_set_option($p, PRINTER_TEXT_ALIGN, PRINTER_TA_RIGHT);
            printer_draw_text($p, "".$row_dt['jumlah_tebus']." ".strtolower($satuan)."", 430 , $linespace);
            $linespace += 30 ;

            if($row_dt['flag_resep'] == 'racikan') 
                {
                  foreach ($row_dt['racikan'][0] as $key => $value) {
                    $arr_total[] = ($value->harga_jual * $value->jumlah);
                    $subtotal_racikan = ($value->harga_jual * $value->jumlah);

                    $font = printer_create_font("Arial", 25, 10, PRINTER_FW_BOLD, false, false, false, 0);
                    printer_select_font($p, $font);
                    $txt_length = (strlen($value->nama_brg) > 30) ? 'xxx' : '';
                    printer_draw_text($p, '- '.substr($value->nama_brg, 0, 30)."".$txt_length, 30 , $linespace);
                    // jumlah
                    $font = printer_create_font("Arial", 25, 10, PRINTER_FW_BOLD, false, false, false, 0);
                    printer_select_font($p, $font);
                    // printer_set_option($p, PRINTER_TEXT_ALIGN, PRINTER_TA_RIGHT);
                    printer_draw_text($p, "".$value->jumlah." ".strtolower($value->satuan)."", 430 , $linespace);
                    $linespace += 30 ;
                }
            }
     
        }

        $count_dt_above = count($params['resep']);
        $linespace2 = $linespace + ($count_dt_above * 30);

        // line
        $pen = printer_create_pen(PRINTER_PEN_SOLID, 3, "000000");
        printer_select_pen($p, $pen);
        printer_draw_line($p, 10, $linespace2, 610, $linespace2);
        
        $linespace3 = $linespace2 + 30;
        
        if(count($params['resep_kronis']) > 0) :
        

        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_BOLD, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "RESEP KRONIS", $var_margin_left, $linespace3);

        $linespace4 = $linespace3 + 30;

        $num = 0;
        foreach($params['resep_kronis'] as $row_dtk){
            $num++;
            // no
            $desc_k = ($row_dtk['flag_resep'] == 'racikan') ? 'Racikan Obat' : $row_dtk['nama_brg'];
            $font = printer_create_font("Arial", 25, 10, PRINTER_FW_BOLD, false, false, false, 0);
            printer_select_font($p, $font);
            printer_draw_text($p, $num.".", 10, $linespace4);
            // nama obat
            $font = printer_create_font("Arial", 25, 10, PRINTER_FW_BOLD, false, false, false, 0);
            printer_select_font($p, $font);
            $txt_length = (strlen($row_dtk['nama_brg']) > 30) ? 'xxx' : '';
            printer_draw_text($p, substr($desc_k, 0, 30)."".$txt_length, 30 , $linespace4);
            // jumlah
            $font = printer_create_font("Arial", 25, 10, PRINTER_FW_BOLD, false, false, false, 0);
            printer_select_font($p, $font);
            $racikan_k = $row_dtk['racikan'][0];
            $satuan_k = ($row_dtk['flag_resep'] == 'racikan') ? $racikan[0]->satuan_racikan : $row_dtk['satuan_kecil'];

            // printer_set_option($p, PRINTER_TEXT_ALIGN, PRINTER_TA_RIGHT);
            printer_draw_text($p, "".$row_dtk['jumlah_obat_23']." ".strtolower($satuan_k)."", 430 , $linespace4);

            $linespace4 += 30 ;

            if($row_dtk['flag_resep'] == 'racikan') :
              foreach ($row_dtk['racikan'][0] as $keyk => $valuekr) {
                 // nama obat
                $font = printer_create_font("Arial", 25, 10, PRINTER_FW_BOLD, false, false, false, 0);
                printer_select_font($p, $font);
                $txt_length = (strlen($valuekr->nama_brg) > 30) ? 'xxx' : '';
                printer_draw_text($p, substr($valuekr->nama_brg, 0, 30)."".$txt_length, 30 , $linespace4);
                // jumlah
                $font = printer_create_font("Arial", 25, 10, PRINTER_FW_BOLD, false, false, false, 0);
                printer_select_font($p, $font);
                // printer_set_option($p, PRINTER_TEXT_ALIGN, PRINTER_TA_RIGHT);
                printer_draw_text($p, "".$valuekr->jumlah." ".strtolower($valuekr->satuan)."", 430 , $linespace4);
                $linespace4 += 30 ;
              }
            endif; 
     
        }

        endif; 
        $line_ex = isset($linespace4)?$linespace4:$linespace3;
        // keterangan
        $ln_keterangan = $line_ex + 5;
        // $font = printer_create_font("Arial", 30, 10, PRINTER_FW_BOLD, false, false, false, 0);
        // printer_select_font($p, $font);
        // printer_draw_text($p, "Keterangan : " , $var_margin_left, $ln_keterangan);
        
        // Form Penyerahan
        $line_form = $ln_keterangan + 30;
        $font = printer_create_font("Arial", 25, 8, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Penerima" , 280, $line_form);

        // TTD Pasien
        $line_ttd = $line_form + 100;
        $font = printer_create_font("Arial", 25, 8, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, $dt_index['nama_pasien'], 280, $line_ttd);




        // tanggal cetak
        $line_tgl = $line_ttd + 30;
        $font = printer_create_font("Arial", 25, 8, PRINTER_FW_BOLD, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Tanggal cetak : ".$CI->tanggal->formatDateTime(date('Y-m-d H:i:s')) , 100, $line_tgl);

                        
        printer_delete_font($font);
        printer_delete_pen($pen);
        printer_end_page($p);
        printer_end_doc($p);

        printer_close($p);
       
    }

    public function print_direct($params)
    {
        # code...
        $CI =& get_instance();
                     
        $p = printer_open("\\\\10.10.10.62\EPSON TM-U220 ReceiptE4");
        
        // define
        $font_familiy = "Calibri";
        $var_margin_left = 5;
        $var_margin_draw_text = 125;
        $font_medium_width = 26;
        $font_medium_height = 12;
        $line_space = 20;

        printer_set_option($p, PRINTER_MODE, "RAW");
            
        printer_start_doc($p);
        printer_start_page($p);

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

        // header
        $font = printer_create_font($font_familiy, 65, 25, PRINTER_FW_BOLD, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, $no, 180, -10);

        $font = printer_create_font($font_familiy, 30, 12, PRINTER_FW_BOLD, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, strtoupper(COMP_LONG), 125, 60);

        // address
        $font = printer_create_font($font_familiy, 25, 9, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, COMP_ADDRESS_SORT, 30,90);

        // title tracer
        $font = printer_create_font($font_familiy, 32, 12, PRINTER_FW_BOLD, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Tracer Pasien", 140, 110);

        // line
        $pen = printer_create_pen(PRINTER_PEN_SOLID, 3, "000000");
        printer_select_pen($p, $pen);
        // printer_draw_line($p, padding-left, margin-bottom-ip, padding-right, margin-bottm-up);
        printer_draw_line($p, 0, 155, 900, 155);

        // end header

        // no mr
        $font = printer_create_font($font_familiy, $font_medium_width, $font_medium_height, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "No. MR ", 100, 165);

        $font = printer_create_font($font_familiy, 45, 17, PRINTER_FW_BOLD, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, $params['no_mr'], 190, 160);

        // no registrasi
        $font = printer_create_font($font_familiy, $font_medium_width, $font_medium_height, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "No. Reg", $var_margin_left, 210);
        $font = printer_create_font($font_familiy, $font_medium_width, $font_medium_height, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, " : ". $no_reg , $var_margin_draw_text, 210);

        // nama pasien
        $font = printer_create_font($font_familiy, $font_medium_width, $font_medium_height, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Nama", $var_margin_left, 240);
        $font = printer_create_font($font_familiy, $font_medium_width, $font_medium_height, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, " : ". $nama_pasien , $var_margin_draw_text, 240);

        // Poli/Klinik
        // $font = printer_create_font($font_familiy, $font_medium_width, $font_medium_height, PRINTER_FW_MEDIUM, false, false, false, 0);
        // printer_select_font($p, $font);
        // printer_draw_text($p, "Poli/Klinik", $var_margin_left, 270);
        $font = printer_create_font($font_familiy, 28, 12, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, ucwords($klinik) , 5, 275);

        // dokter pengirim
        $font = printer_create_font($font_familiy, $font_medium_width, $font_medium_height, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Dokter", $var_margin_left, 305);
        $font = printer_create_font($font_familiy, $font_medium_width, $font_medium_height, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, " : ". $dokter , $var_margin_draw_text, 305);

        // Penjamin
        $font = printer_create_font($font_familiy, $font_medium_width, $font_medium_height, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Penjamin", $var_margin_left, 335);
        $font = printer_create_font($font_familiy, $font_medium_width, $font_medium_height, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, " : ". $nama_perusahaan , $var_margin_draw_text, 335);

        // petugas
        $font = printer_create_font($font_familiy, $font_medium_width, $font_medium_height, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Petugas", $var_margin_left, 365);
        $font = printer_create_font($font_familiy, $font_medium_width, $font_medium_height, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, " : ". $petugas , $var_margin_draw_text, 365);

        // Tanggal
        $font = printer_create_font($font_familiy, $font_medium_width, $font_medium_height, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Tanggal", $var_margin_left, 395);
        $font = printer_create_font($font_familiy, $font_medium_width, $font_medium_height, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, " : ". $tanggal , $var_margin_draw_text, 395);

        // footer
        $font = printer_create_font($font_familiy, 20, 8, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Generated by SIRS Setia Mitra - ".date('D, d/m/Y')."" , $var_margin_left, 430);
                        
        printer_delete_font($font);
        printer_delete_pen($pen);
        printer_end_page($p);
        printer_end_doc($p);

        printer_close($p);

        return true;
       
    }

    function title(Printer $printer, $text)
    {
        $printer -> selectPrintMode(Printer::MODE_EMPHASIZED);
        $printer -> text("\n" . $text);
        $printer -> selectPrintMode(); // Reset
    }

    public function print_sep()
    {
        # code...
        $CI =& get_instance();
                     
        $p = printer_open("\\\\10.10.10.3\EPSON TM-T82X KIOSK01");
       
        // define
        $font_familiy = "Calibri";
        $var_margin_left = 5;
        $var_margin_draw_text = 125;
        $font_medium_width = 26;
        $font_medium_height = 12;
        $line_space = 20;
        
        printer_start_doc($p);
        printer_start_page($p);

        

        // $pen = printer_create_pen(PRINTER_PEN_SOLID, 3, "000000");
        // printer_select_pen($p, $pen);
        // printer_draw_line($p, 10, 1, 200, 1);

        // $pen = printer_create_pen(PRINTER_PEN_SOLID, 3, "000000");
        // printer_select_pen($p, $pen);
        // printer_draw_line($p, 10, 1, 200, 180);

        printer_set_option($p, PRINTER_MODE, "raw");
        printer_set_option($p, PRINTER_TEXT_ALIGN, PRINTER_TA_CENTER);
        
        /*nomor antrian poli*/
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_BOLD, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "NOMOR ANTRIAN POLI", 160, 20);

        $font = printer_create_font("Arial", 150, 50, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "22", 230, 150);

        
        // Nama Poli
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Spesialis Penyakit Jantung dan Pembuluh Darah", 5, 210);

        // Nama Dokter
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "dr. Adelin Dhivi Kemalasari", 5, 235);

        // Jam Praktek
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Jam Praktek (10.00 - 11.00)", 5, 260);

        $pen = printer_create_pen(PRINTER_PEN_SOLID, 3, "000000");
        printer_select_pen($p, $pen);
        printer_draw_line($p, 5, 270, 550, 270);

        /*sep*/
        $font = printer_create_font("Arial", 35, 15, PRINTER_FW_BOLD, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "SURAT ELIGIBILITAS PASIEN", 70, 320);

        $font = printer_create_font("Arial", 30, 12, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "BPJS KESEHATAN", 175, 350);

        // no mr
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "No SEP", $var_margin_left, 400);
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, " : 0112R0937356183001", 175, 400);

        // tgl sep
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Tgl SEP", $var_margin_left, 430);
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, " : 27 Juni 2021", 175, 430);

        // no kartu
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "No. Kartu", $var_margin_left, 460);
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, " : 0000042534786 (MR. 00211762)", 175, 460);

        // Nama Peserta
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Nama Peserta", $var_margin_left, 490);
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, " : Muhammad Amin Lubis ", 175, 490);

        // Tgl Lahir
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Tgl Lahir", $var_margin_left, 520);
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, " : 23 Nov 1990 (JK. P)", 175, 520);

        // No Telp
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "No Telp", $var_margin_left, 550);
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, " : 085819655296", 175, 550);

        // Poli
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Poli Tujuan", $var_margin_left, 580);
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, " : Spesialis Jantung dan Pembuluh Darah", 175, 580);

        // Faskes Perujuk
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Faskes Perujuk", $var_margin_left, 610);
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, " : KLINIK PAMULANG", 175, 610);

        // Jns Rawat
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Jns. Rawat", $var_margin_left, 640);
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, " : Rawat Jalan", 175, 640);

        // Kls Rawat
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Kls. Rawat", $var_margin_left, 670);
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, " : Kelas I", 175, 670);

        // Penjamin
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Penjamin", $var_margin_left, 700);
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, " : BPJS Kesehatan", 175, 700);

        // Peserta
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Peserta", $var_margin_left, 730);
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, " : PENERIMA PENSIUN TNI	", 175, 730);

        // COB
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "COB", $var_margin_left, 760);
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, " : ", 175, 760);

        // DPJP yang melayani
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "DPJP Yg Melayani", $var_margin_left, 790);
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, " dr. Adelin Dhivi Kemalasari", 30, 820);

        // Diagnosa Awal
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Diagnosa Awal", $var_margin_left, 850);
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, " Cervical disc disorder with radiculopathy", 30, 880);

        // Catatan
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Catatan ", $var_margin_left, 910);
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, " ", 30, 940);

        // Keterangan
        $font = printer_create_font("Arial", 23, 9, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "*Saya menyetujui BPJS Kesehatan menggunakan informasi", $var_margin_left, 1000);

        $font = printer_create_font("Arial", 23, 9, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "  medis pasien jika diperlukan SEP, Bukan sebagai bukti", $var_margin_left, 1030);

        $font = printer_create_font("Arial", 23, 9, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, " penjaminan peserta", $var_margin_left, 1060);

        $font = printer_create_font("Arial", 23, 9, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Pasien/Keluarga Pasien", 300, 1090);

        



        $pen = printer_create_pen(PRINTER_PEN_SOLID, 3, "000000");
        printer_select_pen($p, $pen);
        printer_draw_line($p, 270, 1250, 550, 1250);

        $font = printer_create_font("Arial", 23, 9, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Cetakan ke 0 29-06-2021 14:37:32 wib", 10, 1290);



        
        // $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        // printer_select_font($p, $font);
        // printer_draw_text($p,  "Tanggal cetak: " ,$var_margin_left, 200);
                        
        printer_delete_font($font);
        printer_end_page($p);
        printer_end_doc($p);

        printer_close($p);
       
    }

    public function print_booking($params){

        $CI =& get_instance();
        /* Text */
        $connector = new WindowsPrintConnector("smb://10.10.10.3/EPSON TM-T82X KIOSK01");
        // $connector = new NetworkPrintConnector("10.10.10.62", 9100);
        // $connector = new FilePrintConnector("php://stdout");
        $printer = new Printer($connector);

        /* Initialize */
        $printer -> initialize();

        $printer->setFont(Printer::FONT_A);

        $printer -> setJustification(Printer::JUSTIFY_CENTER);
        $printer -> setEmphasis(true);
        $printer -> setTextSize(2, 2);
        $printer -> text(strtoupper(COMP_LONG)."\n");
        $printer -> setEmphasis(false);

        $printer -> setTextSize(1, 1);
        $printer -> text(COMP_ADDRESS_SORT."\n");
        $printer -> text("-----------------------------------------------\n");

        $printer -> setTextSize(1, 1);
        $printer -> setEmphasis(true);
        $printer -> setJustification(Printer::JUSTIFY_CENTER);
        $printer -> text("BUKTI PERJANJIAN PASIEN\n");  
        $printer -> text("\n");  
        $printer -> setEmphasis(false);
        
        $printer -> setLineSpacing(64);
        $printer -> setTextSize(1,1);
        $printer -> setJustification(Printer::JUSTIFY_LEFT);
        $printer->text($this->addSpaces('No MR', 15) . $this->addSpaces($params['value']->no_mr, 20) . "\n");
        $printer->text($this->addSpaces('Nama Pasien', 15) . $this->addSpaces($params['value']->nama, 20) . "\n");
        $printer->text($this->addSpaces('Penjamin', 15) . $this->addSpaces($params['value']->nama_perusahaan, 20) . "\n");
        
        $printer->text("\n");
        

        // qrcode
        $printer -> setJustification(Printer::JUSTIFY_CENTER);
        
        $printer -> setTextSize(1, 1);
        $printer -> setEmphasis(true);
        $printer->text(ucwords($params['value']->nama_bagian)."\n");
        $printer->text($params['value']->dokter."\n");
        $printer->text('Tgl. Perjanjian '.$CI->tanggal->formatDatedmy($params['value']->tgl_kembali).' '.$CI->tanggal->formatTime($params['jadwal']->jd_jam_mulai).' - '.$CI->tanggal->formatTime($params['jadwal']->jd_jam_selesai).''."\n");
        
        $printer->text("\n");
        
        $printer -> qrCode($params['value']->kode_perjanjian, Printer::QR_ECLEVEL_L, 13);
        $printer -> setTextSize(1, 1);
        $printer -> text($params['value']->kode_perjanjian."\n");
        $printer -> setEmphasis(false);
        $printer->setFont();

        $printer->text("\n");
        $printer -> setTextSize(1, 1);
        $printer->setFont(Printer::FONT_B);
        $printer -> setJustification(Printer::JUSTIFY_LEFT);
        $printer -> text("1. Simpan Bukti Perjanjian ini untuk dapat melakukan \n   registrasi ulang\n");
        $printer -> text("2. Silahkan datang 2 jam sebelum praktek dokter dimulai \n   untuk melakukan registrasi ulang.\n");
        $printer -> setJustification();
        $printer -> feed();


        $printer -> cut(Printer::CUT_FULL, 1);

        /* Pulse */
        $printer -> pulse();

        /* Always close the printer! On some PrintConnectors, no actual
        * data is sent until the printer is closed. */

        $printer -> close();
    

        return true;
       
    }

    function addSpaces($string = '', $valid_string_length = 0) {
        if (strlen($string) < $valid_string_length) {
            $spaces = $valid_string_length - strlen($string);
            for ($index1 = 1; $index1 <= $spaces; $index1++) {
                $string = $string . ' ';
            }
        }
    
        return $string;
    }


}


?>


