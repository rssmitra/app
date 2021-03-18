<script src="<?php echo base_url().'assets/barcode-master/prototype/sample/prototype.js'?>" type="text/javascript"></script>
<script src="<?php echo base_url().'assets/barcode-master/prototype/prototype-barcode.js'?>" type="text/javascript"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />

<script type="text/javascript">

window.onload = generateBarcode;

  function generateBarcode(){

    $("barcodeTarget").update();
    var value = "<?php echo $value->no_kuitansi_pembayaran; ?>";
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

  <center><span style="font-size: 16px"><strong>BUKTI PEMBAYARAN FAKTUR</strong></span></center> 

  <table id="no-border" style="width: 100% !important;">
    <tr>
      <!-- <td width="50%">
        <span>No. Tanda Terima Faktur :</span><br>
        <span style="font-size: 18px"><b><?php echo $value->no_terima_faktur?></b></span><br>
        <span>Tanggal. <?php echo $this->tanggal->formatDatedmY($value->tgl_faktur); ?></span><br>
      </td> -->
      <td width="50%" valign="top">
        <span style="font-size: 16px"><b><?php echo $value->namasupplier?></b></span><br>
        <?php echo $value->alamat?><br>
        <?php echo $value->telpon1?><br>
      </td>
    </tr>
  </table>

  <br>
  
  <table class="table-utama" style="width: 100% !important;margin-top: 10px; margin-bottom: 10px">
    <thead>
        <tr style="background-color: #e4e7e8;color: #0a0a0a;font-weight: bold; border: 1px solid black; border-collapse: collapse">
          <td style="text-align:center; width: 5%; border: 1px solid black; border-collapse: collapse">No</td>
          <td style="width:20%; border: 1px solid black; border-collapse: collapse">No. Tanda Terima Faktur</td>
          <td style="width:20%; border: 1px solid black; border-collapse: collapse">No. Kuitansi</td>
          <td style="width:20%; border: 1px solid black; border-collapse: collapse">Tanggal</td>
          <td style="text-align:center; width: 20%; border: 1px solid black; border-collapse: collapse">Penerima</td>
          <td style="text-align:center; width: 20%; border: 1px solid black; border-collapse: collapse">Jumlah (Rp)</td>
        </tr>
    </thead>
    <tbody>
        <tr style="height: 150px; vertical-align: middle">
          <td style="text-align:center; border: 1px solid black; border-collapse: collapse">1</td>
          <td style="border: 1px solid black; border-collapse: collapse"><?php echo $value->no_terima_faktur; ?></td>
          <td style="border: 1px solid black; border-collapse: collapse"><?php echo $value->no_kuitansi_pembayaran; ?></td>
          <td style="text-align:center; border: 1px solid black; border-collapse: collapse"><?php echo $this->tanggal->formatDateDmy($value->tgl_pembayaran); ?></td>
          <td style="border: 1px solid black; border-collapse: collapse"><?php echo $value->penerima_pembayaran; ?></td>
          <td style="text-align:right; border: 1px solid black; border-collapse: collapse"><?php echo number_format($value->total_harga)?></td>
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
        <?php echo $this->session->userdata('user')->fullname;?><br>
        (Petugas)
      </td>
    </tr>
    
  </table>
</body>