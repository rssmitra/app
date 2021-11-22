<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>

<script src="<?php echo base_url().'assets/barcode-master/prototype/sample/prototype.js'?>" type="text/javascript"></script>
<script src="<?php echo base_url().'assets/barcode-master/prototype/prototype-barcode.js'?>" type="text/javascript"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />

<script type="text/javascript">

window.onload = generateBarcode;

  function generateBarcode(){

    $("barcodeTarget").update();
    $("barcodeTarget2").update();
    var value = "<?php echo 'ID-'.$result[0]->id_tc_tagih; ?>";
    var btype = "code128";
    
    var settings = {
      output:"css",
      bgColor: "#FFFFFF",
      color: "#000000",
      barWidth: 2,
      barHeight: 35,
      moduleSize: 20,
      fontSize: 12,
      posX: 20,
      posY: 20,
      addQuietZone: false
    };
    $("barcodeTarget").update().show().barcode(value, btype, settings);
    $("barcodeTarget2").update().show().barcode(value, btype, settings);

  }
    
</script> 

<style>

.barcodeTarget{
  font-weight: bold;margin-top: 5px;letter-spacing: 11px; float: right;
}
.barcodeTarget2{
  font-weight: bold;margin-top: 5px;letter-spacing: 11px; float: right;
}

body, table, p{
  font-family: calibri;
  font-size: 14px;
  background-color: white;
}
.table-utama{
  border: 1px solid black;
  border-collapse: collapse;
}
th, td {
  padding: 2px;
  text-align: left;
}
@media print{ 
  #barPrint{
    display:none;
	}
  div.page{
    page-break-after: always;
    page-break-inside: avoid;
  }
}
</style>

</head>

<body>
  <div id="barPrint" style="float: right">
    <button class="tular" onClick="window.close()">Tutup</button>
    <button class="tular" onClick="print()">Cetak</button>
  </div>

  <div class="page" id="tagihanPerusahaanUtuh">

    <table width="100%" border="0">
      <tr>
        <td width="70px"><img src="<?php echo base_url().COMP_ICON?>" alt="" width="60px"></td>
        <td valign="bottom" width="320px"><b><span style="font-size: 18px"><?php echo COMP_FULL; ?></span></b><br><?php echo COMP_ADDRESS; ?></td>
        <td align="right"><div id="barcodeTarget" class="barcodeTarget"></div></td>
      </tr>
    </table>
    <hr>
    <table id="no-border" style="width: 100% !important;">
      <tr>
        <td width="50%">
          <span>No. Invoice :</span><br>
          <span style="font-size: 18px"><b><?php echo $result[0]->no_invoice_tagih?></b></span><br>
          <span>Tanggal invoice <?php echo $this->tanggal->formatDatedmY($result[0]->tgl_tagih); ?></span><br>
          Tanggal jatuh tempo pembayaran <b><?php echo $this->tanggal->formatDatedmY($result[0]->tgl_jt_tempo); ?></b>
        </td>
        <td width="50%" valign="top">
          Perusahaan :<br>
          <b><?php echo $result[0]->nama_tertagih?></b><br>
          <?php echo $result[0]->alamat?><br>
          <?php echo $result[0]->telpon1?><br>
        </td>
      </tr>
    </table>

    <br>
    <center><span style="font-size: 16px"><strong>TAGIHAN PERUSAHAAN ASURANSI</strong></span></center> 
    <table class="table-utama" style="width: 100% !important;margin-top: 10px; margin-bottom: 10px">
      <thead>
        <tr style="background-color: #e4e7e8;color: #0a0a0a;font-weight: bold; border: 1px solid black; border-collapse: collapse">
          <td style="border: 1px solid black; border-collapse: collapse">Nama Perusahaan</td>
          <td style="text-align:center; width: 80px; border: 1px solid black; border-collapse: collapse">Jumlah (Rp)</td>
        </tr>
      </thead>
      <tbody>
        <tr>
          <td style="font-weight: bolder; font-size: 16px; border: 1px solid black; border-collapse: collapse"><?php echo $perusahaan; ?></td>
          <td style="text-align:right; border: 1px solid black; border-collapse: collapse">
            <?php
              $no=0; 
              foreach($result as $key_dt=>$row_dt) : $no++; 
                $arr_tagihan_perusahaan[] = $row_dt->jumlah_tagih_int;
              endforeach;
              // $total = $subtotal + $biaya_materai - $result[0]->rp_diskon;
              $total_tagih_perusahaan = array_sum($arr_tagihan_perusahaan);
              echo number_format($total_tagih_perusahaan);
            ?>
          </td>
        </tr>

        <tr>
          <td style="text-align:right; font-weight: bolder; padding-right: 20px; border: 0px solid black; border-collapse: collapse">Biaya Materai </td>
          <td style="text-align:right; border: 1px solid black; border-collapse: collapse">
            <?php
              $biaya_materai = ($total_tagih_perusahaan > 5000000) ? 10000 : 0; echo number_format($biaya_materai);
            ?>
          </td>
        </tr>
        <tr>
        <tr>
          <td style="text-align:right; font-weight: bolder; padding-right: 20px; border: 0px solid black; border-collapse: collapse">Total </td>
          <td style="font-weight: bolder; font-size: 16px; text-align:right; border: 1px solid black; border-collapse: collapse" nowrap>Rp 
            <?php echo number_format($biaya_materai+$total_tagih_perusahaan); ?>,-
          </td>
        </tr>
        <tr>
          <td colspan="3" style="text-align:center; border: 1px solid black; border-collapse: collapse; padding: 10px 0 10px 0; "><span style="font-size: 16px !important;"> Terbilang : 
          <b><i>"
            <?php 
              $terbilang = new Kuitansi(); echo ucwords($terbilang->terbilang($biaya_materai+$total_tagih_perusahaan))
            ?> 
            Rupiah "</i></b></span>
          </td>
        </tr>
      </tbody>
    </table>
    <br>
    <table style="width: 100% !important; text-align: center">
      <tr>
        <td style="text-align: left; width: 30%">
          &nbsp;
        </td>
        <td style="text-align: center; width: 40%">&nbsp;</td>
        <td style="text-align: center; width: 30%">
          <span>Jakarta, <?php echo $this->tanggal->formatDatedmY($result[0]->tgl_tagih); ?></span><br>
          <span style="font-size: 14px"><b><?php echo COMP_FULL; ?></b></span>
          <br>
          <br>
          <br>
          <br>
          <?php echo $this->master->get_ttd('ttd_kabag_keu');?>
        </td>
      </tr>
    </table>

  </div>

  <div id="listDataPasienAsuransi" style="margin-top: 20px;">
    <table width="100%" border="0">
      <tr>
        <td width="70px"><img src="<?php echo base_url().COMP_ICON?>" alt="" width="60px"></td>
        <td valign="bottom" width="320px"><b><span style="font-size: 18px"><?php echo COMP_FULL; ?></span></b><br><?php echo COMP_ADDRESS; ?></td>
        <td align="right"><div id="barcodeTarget2" class="barcodeTarget2"></div></td>
      </tr>
    </table>
    <hr>
  
    <center>
      <span style="font-size: 18px">
        <strong>
          <small style="font-weight: bolder;">Lampiran Invoice :</small>
          <br>
          <?php echo $result[0]->no_invoice_tagih?>
          <br>
          <small style="font-weight: bolder;">Tanggal Tagih : <?php echo $this->tanggal->formatDatedmY($result[0]->tgl_tagih); ?></small>
        </strong>
      </span>
    </center> 
    <br>
    <center><span style="font-size: 16px"><strong>LIST DATA PASIEN PERUSAHAAN ASURANSI</strong></span></center> 
    <table class="table-utama" style="width: 100% !important;margin-top: 10px; margin-bottom: 10px">
      <thead>
          <tr style="background-color: #e4e7e8;color: #0a0a0a;font-weight: bold; border: 1px solid black; border-collapse: collapse">
            <td style="text-align:center; width: 30px; border: 1px solid black; border-collapse: collapse">No</td>
            <td style="text-align:center; width:100px; border: 1px solid black; border-collapse: collapse">No. Registrasi</td>
            <td style="text-align:center; width:70px; border: 1px solid black; border-collapse: collapse">No. MR</td>
            <td style="text-align:center; width: 100px; border: 1px solid black; border-collapse: collapse">Tanggal Visit</td>
            <td style="border: 1px solid black; border-collapse: collapse">Nama Pasien</td>
            <td style="text-align:center; width: 80px; border: 1px solid black; border-collapse: collapse">Jumlah (Rp)</td>
          </tr>
      </thead>
      <tbody>
          <?php 
            $no=0; 
            foreach($result as $key_dt=>$row_dt) : $no++; 
            $arr_tagihan[] = $row_dt->jumlah_tagih_int;
          ?>
            <tr>
              <td style="text-align:center; border: 1px solid black; border-collapse: collapse"><?php echo $no?></td>
              <td style="text-align:center; border: 1px solid black; border-collapse: collapse"><?php echo $row_dt->no_registrasi; ?></td>
              <td style="text-align:center; border: 1px solid black; border-collapse: collapse"><?php echo $row_dt->no_mr; ?></td>
              <td style="text-align:center; border: 1px solid black; border-collapse: collapse"><?php echo $this->tanggal->formatDateDmy($row_dt->tgl_jam_masuk); ?></td>
              <td style="border: 1px solid black; border-collapse: collapse"><?php echo $row_dt->nama_pasien; ?></td>
              <td style="text-align:right; border: 1px solid black; border-collapse: collapse"><?php echo number_format($row_dt->jumlah_tagih_int)?></td>
            </tr>
          <?php endforeach;?>

          <?php
            if ($result[0]->rp_diskon > 0) : 
              echo '
                <tr>
                  <td colspan="4" style="text-align:right; padding-right: 20px; border: 0px solid black; border-collapse: collapse">Diskon </td>
                  <td style="text-align:right; border: 1px solid black; border-collapse: collapse">
                      '.number_format($result[0]->rp_diskon).'
                  </td>
                </tr>';
            endif;
          ?>
            
            <tr>
              <td colspan="5" style="text-align:right; font-weight: bolder; padding-right: 20px; border: 0px solid black; border-collapse: collapse">Total </td>
              <td style="text-align:right; font-weight: bolder; border: 1px solid black; border-collapse: collapse">
                <?php
                  $total = array_sum($arr_tagihan);
                  echo number_format($total);
                ?>
              </td>
            </tr>
              
      </tbody>
    </table>
    <br>

  </div>
  
</body>
</html>