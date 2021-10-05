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

    public function print_bukti_registrasi($obj, $no_antrian, $tipe_pasien)
    {
        // echo '<pre>';print_r($obj);die;
        # code...
        $CI =& get_instance();
        sscanf($_SERVER['REMOTE_ADDR'], '%d.%d.%d.%d', $a, $b, $c, $d);

        // $p = printer_open("\\\\".$_SERVER['REMOTE_ADDR']."\EPSON TM-T82X KIOSK".$d."");
        $p = printer_open("\\\\10.10.10.238\EPSON TM-T82X KIOSK238");
        
            // define
            $font_familiy = "Calibri";
            $var_margin_left = 5;
            $var_margin_lists = 29;
            $var_margin_draw_text = 125;
            $font_medium_width = 26;
            $font_medium_height = 12;
            $line_space = 20;
            
            printer_start_doc($p);
            printer_start_page($p);


            printer_set_option($p, PRINTER_MODE, "raw");
            printer_set_option($p, PRINTER_TEXT_ALIGN, PRINTER_TA_CENTER);

            /*Title*/
            $font = printer_create_font("Arial", 35, 15, PRINTER_FW_BOLD, false, false, false, 0);
            printer_select_font($p, $font);
            printer_draw_text($p, "BUKTI REGISTRASI PASIEN", 70, 30);

            $font = printer_create_font("Arial", 30, 12, PRINTER_FW_MEDIUM, false, false, false, 0);
            printer_select_font($p, $font);
            printer_draw_text($p, "RS SETIA MITRA", 175, 60);

            // no mr
            $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
            printer_select_font($p, $font);
            printer_draw_text($p, "No MR", $var_margin_left, 100);
            $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
            printer_select_font($p, $font);
            printer_draw_text($p, "  ".$obj->no_mr." ", 175, 100);

            // nama pasien
            $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
            printer_select_font($p, $font);
            printer_draw_text($p, "Nama Pasien", $var_margin_left, 130);
            $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
            printer_select_font($p, $font);
            printer_draw_text($p, "  ".$obj->nama_pasien." ", 175, 130);

            // tgl jam kunjungan
            $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
            printer_select_font($p, $font);
            printer_draw_text($p, "Tanggal", $var_margin_left, 160);
            $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
            printer_select_font($p, $font);
            printer_draw_text($p, "  ".$CI->tanggal->formatDateTime($obj->tgl_jam_masuk)." ", 175, 160);

            if($no_antrian > 0) :
                // Penjamin
                $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
                printer_select_font($p, $font);
                printer_draw_text($p, "Penjamin", $var_margin_left, 250);
                $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
                printer_select_font($p, $font);
                printer_draw_text($p, "  ".$obj->nama_perusahaan." ", 175, 250);
                 // Poli Tujuan
                $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
                printer_select_font($p, $font);
                printer_draw_text($p, "Tujuan", $var_margin_left, 190);
                $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
                printer_select_font($p, $font);
                printer_draw_text($p, "  ".ucwords($obj->nama_bagian)." ", 175, 190);

                // Dokter
                $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
                printer_select_font($p, $font);
                printer_draw_text($p, "Dokter", $var_margin_left, 220);
                $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
                printer_select_font($p, $font);
                printer_draw_text($p, "  ".$obj->nama_pegawai." ", 175, 220);
                
                // 1. Assesmen
                $font = printer_create_font("Arial", 25, 10, PRINTER_FW_BOLD, false, false, false, 0);
                printer_select_font($p, $font);
                printer_draw_text($p, "Assesmen Rawat Jalan ", $var_margin_left, 300);
                // $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
                // printer_select_font($p, $font);
                // printer_draw_text($p, "  ", 300, 300);

                // Berat Badan
                $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
                printer_select_font($p, $font);
                printer_draw_text($p, "Berat Badan", $var_margin_lists, 330);
                $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
                printer_select_font($p, $font);
                printer_draw_text($p, " [      ] ", 280, 330);

                // Tekanan Darah
                $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
                printer_select_font($p, $font);
                printer_draw_text($p, "Tekanan Darah (TD)", $var_margin_lists, 360);
                $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
                printer_select_font($p, $font);
                printer_draw_text($p, " [      /      ] ", 280, 360);

                // EKG
                $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
                printer_select_font($p, $font);
                printer_draw_text($p, "EKG", $var_margin_lists, 390);
                $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
                printer_select_font($p, $font);
                printer_draw_text($p, " [      ] ", 280, 390);

                // Suhu
                $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
                printer_select_font($p, $font);
                printer_draw_text($p, "Suhu", $var_margin_lists, 420);
                $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
                printer_select_font($p, $font);
                printer_draw_text($p, " [      ] ", 280, 420);

                // Nadi
                $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
                printer_select_font($p, $font);
                printer_draw_text($p, "Nadi", $var_margin_lists, 450);
                $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
                printer_select_font($p, $font);
                printer_draw_text($p, " [      ] ", 280, 450);

                // 2. Poli Klinik
                $font = printer_create_font("Arial", 25, 10, PRINTER_FW_BOLD, false, false, false, 0);
                printer_select_font($p, $font);
                printer_draw_text($p, "Polik / Klinik ", $var_margin_left, 490);
                // $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
                // printer_select_font($p, $font);
                // printer_draw_text($p, "  ", 175, 440);

                // Konsultasi Dokter
                $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
                printer_select_font($p, $font);
                printer_draw_text($p, "Konsultasi Dokter", $var_margin_lists, 520);
                $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
                printer_select_font($p, $font);
                printer_draw_text($p, " [     ] ", 230, 520);

                // 3. IGD
                $font = printer_create_font("Arial", 25, 10, PRINTER_FW_BOLD, false, false, false, 0);
                printer_select_font($p, $font);
                printer_draw_text($p, "Instalasi Gawat Darurat (IGD) ", $var_margin_left, 570);
                // $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
                // printer_select_font($p, $font);
                // printer_draw_text($p, "  ", 175, 480);

                // konsultasi dokter
                $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
                printer_select_font($p, $font);
                printer_draw_text($p, "Konsultasi Dokter", $var_margin_lists, 600);
                $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
                printer_select_font($p, $font);
                printer_draw_text($p, " [     ] ", 230, 600);

                // 4. Penunjang Medis
                $font = printer_create_font("Arial", 25, 10, PRINTER_FW_BOLD, false, false, false, 0);
                printer_select_font($p, $font);
                printer_draw_text($p, "Penunjang Medis ", $var_margin_left, 650);
                // $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
                // printer_select_font($p, $font);
                // printer_draw_text($p, "  ", 175, 540);

                // DPJP yang melayani
                // $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
                // printer_select_font($p, $font);
                // printer_draw_text($p, "DPJP Yg Melayani", $var_margin_left, 790);
                // $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
                // printer_select_font($p, $font);
                // printer_draw_text($p, " dr. Adelin Dhivi Kemalasari", 30, 520);

                // laboratorium
                $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
                printer_select_font($p, $font);
                printer_draw_text($p, "Laboratorium ", $var_margin_lists, 680);
                $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
                printer_select_font($p, $font);
                printer_draw_text($p, " [     ] ", 230, 680);

                // Radiologi
                $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
                printer_select_font($p, $font);
                printer_draw_text($p, "Radiologi ", $var_margin_lists, 710);
                $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
                printer_select_font($p, $font);
                printer_draw_text($p, " [     ] ", 230, 710);

                // Fisioterapi
                $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
                printer_select_font($p, $font);
                printer_draw_text($p, "Fisioterapi ", $var_margin_lists, 740);
                $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
                printer_select_font($p, $font);
                printer_draw_text($p, " [     ] ", 230, 740);

                // 5. Farmasi
                $font = printer_create_font("Arial", 25, 10, PRINTER_FW_BOLD, false, false, false, 0);
                printer_select_font($p, $font);
                printer_draw_text($p, "Farmasi ", $var_margin_left, 790);
                // $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
                // printer_select_font($p, $font);
                // printer_draw_text($p, "  ", 175, 660);

                // Resep
                $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
                printer_select_font($p, $font);
                printer_draw_text($p, "Resep Dokter", $var_margin_lists, 820);
                $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
                printer_select_font($p, $font);
                printer_draw_text($p, " [     ] ", 230, 820);

                // Keterangan
                $font = printer_create_font("Arial", 23, 9, PRINTER_FW_MEDIUM, false, false, false, 0);
                printer_select_font($p, $font);
                printer_draw_text($p, "* Berikan form checklist ini ke petugas ", $var_margin_left, 870);

                $font = printer_create_font("Arial", 23, 9, PRINTER_FW_MEDIUM, false, false, false, 0);
                printer_select_font($p, $font);
                printer_draw_text($p, "  setiap kali akan dilakukan pemeriksaan", $var_margin_left, 900);

                // $font = printer_create_font("Arial", 23, 9, PRINTER_FW_MEDIUM, false, false, false, 0);
                // printer_select_font($p, $font);
                // printer_draw_text($p, " penjaminan peserta", $var_margin_left, 760);

                // ttd Pasien
                $font = printer_create_font("Arial", 23, 9, PRINTER_FW_MEDIUM, false, false, false, 0);
                printer_select_font($p, $font);
                printer_draw_text($p, " Ttd. Pasien/Keluarga Pasien ", 300, 1050);

                // $pen = printer_create_pen(PRINTER_PEN_SOLID, 3, "000000");
                // printer_select_pen($p, $pen);
                // printer_draw_line($p, 270, 900, 550, 900);

                $pen = printer_create_pen(PRINTER_PEN_SOLID, 3, "000000");
                printer_select_pen($p, $pen);
                printer_draw_line($p, 10, 1080, 550, 1080);

                // text nomor antrian
                $font = printer_create_font("Arial", 25, 10, PRINTER_FW_BOLD, false, false, false, 0);
                printer_select_font($p, $font);
                printer_draw_text($p, "NOMOR ANTRIAN POLI/KLINIK", 130, 1115);

                /*nomor antrian poli + 160*/
                $font = printer_create_font("Arial", 100, 35, PRINTER_FW_BOLD, false, false, false, 0);
                printer_select_font($p, $font);
                printer_draw_text($p, $no_antrian, 10, 1160);

                // Nomor antrian poli +25
                $font = printer_create_font("Arial", 25, 10, PRINTER_FW_BOLD, false, false, false, 0);
                printer_select_font($p, $font);
                printer_draw_text($p, '('.$tipe_pasien.')', 130, 1145);

                // Nama Poli +40
                $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
                printer_select_font($p, $font);
                printer_draw_text($p, "".ucwords($obj->nama_bagian)."", 130, 1175);

                // Nama Dokter +30
                $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
                printer_select_font($p, $font);
                printer_draw_text($p, "".$obj->nama_pegawai."", 130, 1205);

                // Jam Praktek +30
                $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
                printer_select_font($p, $font);
                printer_draw_text($p, "(".$CI->tanggal->formatTime($obj->jd_jam_mulai)." - ".$CI->tanggal->formatTime($obj->jd_jam_selesai).")", 130, 1235);
                
            else: 
                $pen = printer_create_pen(PRINTER_PEN_SOLID, 3, "000000");
                printer_select_pen($p, $pen);
                printer_draw_line($p, 10, 200, 550, 200);
                // kunjungan pm
                $font = printer_create_font("Arial", 23, 9, PRINTER_FW_MEDIUM, false, false, false, 0);
                printer_select_font($p, $font);
                printer_draw_text($p, "Tujuan kunjungan ", $var_margin_left, 250);

                $font = printer_create_font("Arial", 35, 15, PRINTER_FW_BOLD, false, false, false, 0);
                printer_select_font($p, $font);
                printer_draw_text($p, "".strtoupper($obj->nama_bagian)."", 50, 285);

                
            endif;
            $length = ($no_antrian > 0) ? 1300 : 350;
            // tgl cetak +80
            $font = printer_create_font("Arial", 23, 9, PRINTER_FW_MEDIUM, false, false, false, 0);
            printer_select_font($p, $font);
            printer_draw_text($p, "Tanggal cetak  ".date('d/M/Y H:i:s')." WIB ", 100, $length);

            
            printer_delete_font($font);
            printer_delete_pen($pen);
            printer_end_page($p);
            printer_end_doc($p);
            printer_close($p);
       
    }

    public function print_sep($data)
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

        // sep data
        $sep = $data['sep'];


        printer_set_option($p, PRINTER_MODE, "raw");
        printer_set_option($p, PRINTER_TEXT_ALIGN, PRINTER_TA_CENTER);

        /*sep*/
        $font = printer_create_font("Arial", 35, 15, PRINTER_FW_BOLD, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "SURAT ELIGIBILITAS PASIEN", 70, 30);

        $font = printer_create_font("Arial", 30, 12, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "BPJS KESEHATAN", 175, 60);

        // no mr
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "No SEP", $var_margin_left, 100);
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "  ".$sep->noSep." ", 175, 100);

        // tgl sep
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Tgl SEP", $var_margin_left, 130);
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "  ".$sep->tglSep." ", 175, 130);

        // no kartu
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "No. Kartu", $var_margin_left, 160);
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "  ".$sep->peserta->noKartu." ", 175, 160);

        // no mr
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "No. MR", $var_margin_left, 190);
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "  ".$sep->peserta->noMr." ", 175, 190);

        // Nama Peserta
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Nama Peserta", $var_margin_left, 220);
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "  ".$sep->peserta->nama." ", 175, 220);

        // Tgl Lahir
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Tgl Lahir", $var_margin_left, 250);
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "  ".$sep->peserta->tglLahir." ", 175, 250);

        // Tgl Lahir
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Kelamin", $var_margin_left, 280);
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "  ".$sep->peserta->kelamin." ", 175, 280);

        // No Telp
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "No Telp", $var_margin_left, 310);
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "  ".$sep->peserta->noKartu." ", 175, 310);

        // Poli
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Sub/Spesialis", $var_margin_left, 340);
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "  ".$sep->poli." ", 175, 340);

        // Faskes Perujuk
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Faskes Perujuk", $var_margin_left, 370);
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "  ".$sep->peserta->noKartu." ", 175, 370);

        // Jns Rawat
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Jns. Rawat", $var_margin_left, 400);
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "  ".$sep->jnsPelayanan." ", 175, 400);

        // Kls Rawat
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Kls. Rawat", $var_margin_left, 430);
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "  ".$sep->peserta->hakKelas." ", 175, 430);

        // Peserta
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Peserta", $var_margin_left, 460);
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "  ".$sep->peserta->jnsPeserta." ", 175, 460);

        // COB
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "COB", $var_margin_left, 490);
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "  ", 175, 490);

        // Penjamin
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Penjamin", $var_margin_left, 520);
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, " BPJS Kesehatan ", 175, 520);

        // DPJP yang melayani
        // $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        // printer_select_font($p, $font);
        // printer_draw_text($p, "DPJP Yg Melayani", $var_margin_left, 790);
        // $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        // printer_select_font($p, $font);
        // printer_draw_text($p, " dr. Adelin Dhivi Kemalasari", 30, 520);

        // Diagnosa Awal
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Diagnosa Awal", $var_margin_left, 550);
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, " ".$sep->diagnosa."", 30, 580);

        // Catatan
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Catatan ", $var_margin_left, 610);
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, " ".$sep->catatan." ", 30, 640);

        // Keterangan
        $font = printer_create_font("Arial", 23, 9, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "*Saya menyetujui BPJS Kesehatan menggunakan informasi", $var_margin_left, 700);

        $font = printer_create_font("Arial", 23, 9, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "  medis pasien jika diperlukan SEP, Bukan sebagai bukti", $var_margin_left, 730);

        $font = printer_create_font("Arial", 23, 9, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, " penjaminan peserta", $var_margin_left, 760);

        $font = printer_create_font("Arial", 23, 9, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Pasien/Keluarga Pasien", 300, 790);

        // $pen = printer_create_pen(PRINTER_PEN_SOLID, 3, "000000");
        // printer_select_pen($p, $pen);
        // printer_draw_line($p, 270, 900, 550, 900);

        $pen = printer_create_pen(PRINTER_PEN_SOLID, 3, "000000");
        printer_select_pen($p, $pen);
        printer_draw_line($p, 10, 900, 550, 900);

        /*nomor antrian poli*/
        $font = printer_create_font("Arial", 150, 50, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "22", 230, 1060);

        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_BOLD, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "(NOMOR ANTRIAN POLI)", 170, 1085);

        // Nama Poli
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Spesialis Penyakit Jantung dan Pembuluh Darah", 5, 1130);

        // Nama Dokter
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "dr. Adelin Dhivi Kemalasari", 5, 1160);

        // Jam Praktek
        $font = printer_create_font("Arial", 25, 10, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "(10.00 - 11.00)", 5, 1190);

        $font = printer_create_font("Arial", 23, 9, PRINTER_FW_MEDIUM, false, false, false, 0);
        printer_select_font($p, $font);
        printer_draw_text($p, "Tanggal cetak  ".date('d/M/Y H:i:s')." WIB ", 100, 1290);
        
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

    function _convertToImg($base64str){ //$base64str is the data url
        $image = $this->base64_to_jpeg( $base64str, 'tmp.jpg' );
        // get image height, width
        // list($width, $height, $type, $attr) = getimagesize($image);
        // jpeg to bmp? Something goes wrong here
        // jpeg2wbmp("uploaded/ttd/tmp.jpeg","uploaded/ttd/tmp.bmp",30,50,0);
        $handle = printer_open("\\\\10.10.10.3\EPSON TM-T82X KIOSK01");
        printer_start_doc($handle, "My Document");
        printer_start_page($handle);

        $font = printer_create_font("Barcode39dc.ttf",70,60,PRINTER_FW_THIN,false,false,false,0);
        printer_select_font($handle, $font);
        printer_draw_bmp($handle,$base64str,0,0,50,30);
        printer_draw_text($handle, "000100020045", 40, 40);
        printer_delete_font($font);

        printer_end_page($handle);
        printer_end_doc($handle);
        printer_close($handle);

        // print bmp...

    }

    function base64_to_jpeg( $base64_string, $output_file ) {
        $ifp = fopen( $output_file, "wb" ); 
        fwrite( $ifp, base64_decode( $base64_string) ); 
        fclose( $ifp ); 
        return( $output_file ); 
    }
    
    




}


?>



