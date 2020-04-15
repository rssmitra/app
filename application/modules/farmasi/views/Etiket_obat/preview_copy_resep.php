<script src="<?php echo base_url().'assets/barcode-master/prototype/sample/prototype.js'?>" type="text/javascript"></script>
<script src="<?php echo base_url().'assets/barcode-master/prototype/prototype-barcode.js'?>" type="text/javascript"></script>

<script type="text/javascript">

window.onload = generateBarcode;

  function generateBarcode(){

    $("barcodeTarget<?php echo $result->kode_trans_far?>").update();
    var value = "<?php echo $result->kode_trans_far; ?>";
    var btype = "code128";
    
    var settings = {
      output:"css",
      bgColor: "#FFFFFF",
      color: "#000000",
      barWidth: 2,
      barHeight: 40,
      moduleSize: 20,
      fontSize: 11,
      posX: 15,
      posY: 15,
      addQuietZone: false
    };
    $("barcodeTarget<?php echo $result->kode_trans_far?>").update().show().barcode(value, btype, settings);
 


  }
    
</script> 

<style>
.body{
  width: 265px;
  height: 210px;
  /* border: 1px solid grey; */
  font-weight: bold;
  font-size: 14px;
  font-family: Arial Narrow;
  color: black;
  text-align: left;
  background-color: white; 
}

table{
  font-weight: bold;
}

@media print{ #barPrint{
		display:none;
	}
}
</style>
<center>
<div class="body" style="page-break-after: always;">
  <table border=0 width="100%" style="border-bottom: 1px solid">
    <tr>
      <td><img src="<?php echo base_url().'assets/images/logo-black.png'?>" alt="" style="width: 50px"></td>
      <td align="center">INSTALASI FARMASI<br>
          <?php echo strtoupper(COMP_LONG); ?></td>
      <td><img src="<?php echo base_url().'assets/images/qrcode.png'?>" alt="" style="width: 40px"></td>
    </tr>
  </table> 
  <p style="margin-top: 1px">
    No. Transaksi : <?php echo $result->kode_trans_far; ?> - <?php echo $this->tanggal->formatDatedmY($result->tgl_trans); ?><br>
    No. Mr : <?php echo $result->no_mr; ?><br>
    Nama Pasien : <?php echo $result->nama_pasien; ?><br>
    Tgl Lahir : <?php echo $this->tanggal->formatDate($result->tgl_lhr)?> <br>
    Umur : <?php echo $this->tanggal->AgeWithYearMonthDay($result->tgl_lhr)?><br>
    <hr style="margin-top: -10px">
  </p>
  <center><h3>COPY RESEP</h3></center>
  <p style="margin-top: 1px; margin-bottom: 100px">
    
    <?php echo $result->copy_resep_text; ?>
  </p>
  <p style="margin-top: -12px">
    <center style="margin-top:-12px">
      <div id="barcodeTarget<?php echo $result->kode_trans_far?>" class="barcodeTarget" ></div>
    </center>
  </p>
  <hr>
  <p style="font-weight: normal; font-size: 11px; margin-top: -5px">
    * Semoga lekas sembuh
  </p>
</div>
</center>

