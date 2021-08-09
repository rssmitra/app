<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>


  <?php
  $tgl = date("d");
  $bln = date("m");
  $thn = date("Y");
  $tglsekarang = date("d F Y");
  

  ?>
  <script src="<?php echo base_url().'assets/barcode-master/prototype/sample/prototype.js'?>" type="text/javascript"></script>
  <script src="<?php echo base_url().'assets/barcode-master/prototype/prototype-barcode.js'?>" type="text/javascript"></script>
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/print.css" class="ace-main-stylesheet" id="main-ace-style" />

  <style>
    .stamp {
          margin-top: -96px;
          margin-left: 600px;
          position: absolute;
          display: inline-block;
          color: black;
          padding: 1px;
          padding-left: 10px;
          padding-right: 10px;
          background-color: white;
          box-shadow:inset 0px 0px 0px 0px;
          /*opacity: 0.5;*/
          -webkit-transform: rotate(25deg);
          -moz-transform: rotate(25deg);
          -ms-transform: rotate(25deg);
          -o-transform: rotate(25deg);
          transform: rotate(0deg);
        
    }
  </style>
</head>

  <body>
    <?php 
      $no_key = 1;
      foreach($kunjungan as $key=>$row_dt_kunj) :  $no_key++;
        foreach($row_dt_kunj as $key_s=>$row_s) : 
          
            $sum_array[$no_key][$key_s] = array();
            foreach( $row_s as $value_data ) : 
              if(isset($_GET['flag_bill']) AND $_GET['flag_bill'] == true) :
                if($value_data->kode_tc_trans_kasir != NULL) : 
                  $subtotal = $this->Billing->get_total_tagihan($value_data);
                  $sum_array[$no_key][$key_s][] = $subtotal;
                endif;
              // Select bill covered by insurance || flag_bayar_nk == 1 is not covered
              elseif($value_data->flag_bayar_nk != true) : 
                $subtotal = $this->Billing->get_total_tagihan($value_data);
                $sum_array[$no_key][$key_s][] = $subtotal;
              endif; 
            endforeach; 
            $arr_sum_total[] = array_sum($sum_array[$no_key][$key_s]);
        endforeach;
      endforeach;
    ?>
      
    <!-- <div style="float: right">
      <button class="tular" onClick="window.close()">Tutup</button>
      <button class="tular" onClick="print()">Cetak</button>
    </div> -->
    
    <div class="row"> 
      <div class="col-xs-8">
        <table style="font-size:13px; padding: 45mm 10px 1px 9mm;" border="0">
          <tr>
            <td style="font-size: 13px; font-weight: bold;">No. Bukti Pembayaran</td>
            <td style="font-size: 13px; font-weight: bold;">: 
              <?php
                // seri kuitansi (jenis layanan)
                echo $tipe;
                // kondisi untuk menentukan tipe kuitansi T = Tunai, D = Kartu Debit, K = Kartu Kredit, NK = BPJS / Asuransi Umum / Internal / PT
                $tunai = $data->kasir_data[0]->tunai;
                echo ($tunai != 0 ? 'T' : '');
                $debet = $data->kasir_data[0]->debet;
                echo ($debet != 0 ? 'D' : '');
                $kredit = $data->kasir_data[0]->kredit;
                echo ($kredit != 0 ? 'K' : '');
                $nk_perusahaan = $data->kasir_data[0]->nk_perusahaan;
                echo ($nk_perusahaan != 0 ? 'NK' : '');
                $nk_karyawan = $data->kasir_data[0]->nk_karyawan;
                echo ($nk_karyawan != 0 ? 'NK' : '');
                // no_kuitansi
                echo $data->kasir_data[0]->no_kuitansi;
                ?>
            </td>
          </tr>
          <tr>
            <td>&nbsp;</td><td></td>
          </tr>
          <tr> 
            <td width="20%" style="font-size:13px">Telah Terima dari </td> 
            <td width="67%"><font size="2">: <?php echo $data->reg_data->nama_perusahaan?>&nbsp;</font></td>
          </tr>
          <tr>
            <td width="20%" valign="top" style="font-size:13px">Terbilang </td> 
            <td width="67%" bgcolor="#EBEBEB" nowrap style="font-size:14px;">: 
            <span style="font-weight: bold; font-style: italic;"><?php $terbilang = new Kuitansi(); echo ucwords($terbilang->terbilang(array_sum($arr_sum_total)))?> Rupiah</span>
            </td>
          </tr> 
          <tr>
            <td width="20%">&nbsp;</td> 
            <td width="67%" bgcolor="#EBEBEB">&nbsp;</td> 
          </tr>
          <tr> 
            <td width="20%" style="font-size:13px">Untuk Pembayaran</td>  
            <!--<td width="67%" bgcolor="#EBEBEB"><?//=trim($total_nd)=="0" || $bill!="0" ? $pembayaran : "Selisih Perawatan "?></td> -->
            <!-- Update Pengembalian Uang Muka 131011-->
            <td style="font-size: 14px;">: <i>Pemeriksaan / Pengobatan</i></td> 
          </tr>
          <tr>
            <td width="20%" style="font-size:13px">Atas Nama</td> 
            <td width="67%" bgcolor="#EBEBEB" style="font-size:13px">: <?php echo $data->reg_data->nama_pasien?></td> 
          </tr>
        </table>
        <br>
        <table width="100%" border="0" cellspacing="0" cellpadding="2" align="center" style="padding: 0 0 0 9mm;"> 
          <tr> 			
            <td width="65%" style="vertical-align: middle;">
            <span style="font-size: 20px; font-weight: bolder; padding: 10px; border-style: solid; border-width: 5px;">Rp <?php echo number_format(array_sum($arr_sum_total)); ?>,-</span> 

            </td>
            <td valign="top" align="center" style="font-size:13px">
              <!--Jakarta,<?//=$tgl_now_full?><br>Petugas Kasir<?//=trim($total_nd)=="0" || $bill!="0" ? $nm_perusahaan : ""?>-->
              <!-- Update Kwitansi Pengembalian Uang Muka 131011-->
              Jakarta, <?php setlocale(LC_TIME, 'id_ID'); echo strftime( "%d %B %Y", time()); ?><br>
              <br/><br/><br/><br/> <br/> 
              ( <?php echo $this->session->userdata('user')->fullname?> )<br/><br/>
            </td>
          </tr>
          <tr>
            <td colspan="2">
              Kuitansi ini SAH bila ada cap & tanda tangan petugas
            </td>
          </tr>
        </table>
      </div>
    </div>
  </body>
</html>