<script src="<?php echo base_url().'assets/barcode-master/prototype/sample/prototype.js'?>" type="text/javascript"></script>
<script src="<?php echo base_url().'assets/barcode-master/prototype/prototype-barcode.js'?>" type="text/javascript"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />

<script type="text/javascript">

window.onload = generateBarcode;

  function generateBarcode(){

    $("barcodeTarget").update();
    var value = "<?php echo 'NOTA LUNAS-'.$result[0]->id_tc_tagih; ?>";
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


  }
    
</script> 

<style>

.barcodeTarget{
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
@media print{ #barPrint{
		display:none;
	}
}
</style>

<body>
  <div id="barPrint" style="float: right">
    <button class="tular" onClick="window.close()">Tutup</button>
    <button class="tular" onClick="print()">Cetak</button>
  </div>

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
        <span>Tanggal penagihan. <?php echo $this->tanggal->formatDatedmY($result[0]->tgl_tagih); ?></span><br>
        <!-- <span>Tanggal jatuh tempo pembayaran. <?php //echo $this->tanggal->formatDatedmY($result[0]->tgl_jt_tempo); ?></span> -->
        <span>No. Kuitansi :</span><br>
        <span style="font-size: 18px"><b><?php echo $result[0]->no_kuitansi_bayar?></b></span><br>
        <span>Tanggal pembayaran. <?php echo $this->tanggal->formatDatedmY($result[0]->tgl_bayar); ?></span><br>
      </td>
      <td width="50%" valign="top">
        <span>Perusahaan :</span><br>
        <span><b><?php echo $result[0]->nama_perusahaan?></b><br></span>
        <span><?php echo $result[0]->alamat?><br></span>
        <span><?php echo ($result[0]->telpon1) ? NULL : '~' ;?><br></span>
      </td>
    </tr>
  </table>

  <br>
  <center><span style="font-size: 16px"><strong>DATA PASIEN ASURANSI</strong></span></center> 
  <table class="table-utama" style="width: 100% !important;margin-top: 10px; margin-bottom: 10px">
    <thead>
        <tr style="background-color: #e4e7e8;color: #0a0a0a;font-weight: bold; border: 1px solid black; border-collapse: collapse">
          <th style="text-align:center; width: 30px; border: 1px solid black; border-collapse: collapse">No</th>
          <th style="text-align:center; width:70px; border: 1px solid black; border-collapse: collapse">No. MR</th>
          <th style="padding-left:5px; border: 1px solid black; border-collapse: collapse">Nama Pasien</th>
          <th style="text-align:center; width: 120px; border: 1px solid black; border-collapse: collapse">Jumlah (Rp)</th>
        </tr>
    </thead>
    <tbody>
        <?php 
          $no=0; 
          foreach($result as $key_dt=>$row_dt) : $no++; 
          $arr_tagihan[] = $row_dt->jumlah_bayar;
        ?>
            <tr>
              <td style="text-align:center; border: 1px solid black; border-collapse: collapse"><?php echo $no?></td>
              <td style="border: 1px solid black; border-collapse: collapse"><?php echo $row_dt->no_mr; ?></td>
              <td style="padding-left:5px; border: 1px solid black; border-collapse: collapse"><?php echo $row_dt->nama_pasien; ?></td>
              <td style="padding-right:5px; text-align:right; border: 1px solid black; border-collapse: collapse"><?php echo number_format($row_dt->jumlah_bayar)?></td>
            </tr>
            <?php endforeach;?>

            <tr>
              <td colspan="3" style="text-align:right; padding-right: 20px; border: 0px solid black; border-collapse: collapse">Subtotal </td>
              <td style="padding-right:5px; text-align:right; border: 1px solid black; border-collapse: collapse">
                <?php 
                  $subtotal = array_sum($arr_tagihan);
                  echo number_format($subtotal);
                ?>
              </td>
            </tr>
            <?php
              $diskon = $result[0]->tr_yg_diskon;
              if ($diskon != 0){
                echo '<tr>';
                echo    '<td colspan="3" style="text-align:right; padding-right: 20px; border: 0px solid black; border-collapse: collapse">Diskon </td>';
                echo    '<td style="padding-right:5px; text-align:right; border: 1px solid black; border-collapse: collapse">';
                echo      number_format($result[0]->tr_yg_diskon);
                echo    '</td>';
                echo  '</tr>';
              }
            ?>
            <tr>
              <td colspan="3" style="text-align:right; padding-right: 20px; border: 0px solid black; border-collapse: collapse">Materai </td>
              <td style="padding-right:5px; text-align:right; border: 1px solid black; border-collapse: collapse">
                <?php
                  $biaya_materai = ($subtotal > 5000000) ? 10000 : 0; echo number_format($biaya_materai);
                ?>
              </td>
            </tr>

            <tr>
              <td colspan="3" style="text-align:right; padding-right: 20px; border: 0px solid black; border-collapse: collapse">Total </td>
              <td style="padding-right:5px; text-align:right; border: 1px solid black; border-collapse: collapse">
                <?php
                  $total = $subtotal + $biaya_materai - $diskon;
                  echo number_format($total);
                ?>
              </td>
            </tr>
            <tr>
            <td colspan="9" style="padding-right:5px; text-align:right; border: 1px solid black; border-collapse: collapse">Terbilang : 
            <b><i>"<?php $terbilang = new Kuitansi(); echo ucwords($terbilang->terbilang($total))?> Rupiah"</i></b>
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
        <span style="font-size: 14px"><b><?php echo COMP_LONG; ?></b></span>
        <br>
        <br>
        <br>
        <br>
        <?php echo $this->master->get_ttd('ttd_ka_rs');?>
      </td>
    </tr>
    
  </table>
</body>