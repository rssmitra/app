<script src="<?php echo base_url().'assets/barcode-master/prototype/sample/prototype.js'?>" type="text/javascript"></script>
<script src="<?php echo base_url().'assets/barcode-master/prototype/prototype-barcode.js'?>" type="text/javascript"></script>

<script type="text/javascript">

window.onload = generateBarcode;

  function generateBarcode(){

    $("barcodeTarget<?php echo $result[0]->kode_trans_far?>").update();
    var value = "<?php echo $result[0]->kode_trans_far; ?>";
    var btype = "code128";
    
    var settings = {
      output:"css",
      bgColor: "#FFFFFF",
      color: "#000000",
      barWidth: 2,
      barHeight: 40,
      moduleSize: 10,
      fontSize: 11,
      posX: 15,
      posY: 15,
      addQuietZone: false
    };
    $("barcodeTarget<?php echo $result[0]->kode_trans_far?>").update().show().barcode(value, btype, settings);


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
<!-- <div id="barPrint" style="float: right">
  <button class="tular" onClick="window.close()">Tutup</button>
  <button class="tular" onClick="print()">Cetak</button>
</div> -->
<div class="body">
<table border=0 width="100%" style="border-bottom: 1px solid">
  <tr>
    <td><img src="<?php echo base_url().'assets/images/logo-black.png'?>" alt="" style="width: 50px"></td>
    <td align="center">INSTALASI FARMASI<br>
        <?php echo strtoupper(COMP_LONG); ?></td>
    <td><img src="<?php echo base_url().'assets/images/qrcode.png'?>" alt="" style="width: 40px"></td>
  </tr>
</table> 
<center style="font-size: 18px" >No. <?php echo $result[0]->kode_trans_far; ?></center>
<p style="margin-top: 1px">
  No. Mr : <?php echo $result[0]->no_mr; ?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
  Tgl : <?php echo $this->tanggal->tgl_indo($result[0]->tgl_trans); ?> <br>
  Nama Pasien : <?php echo $result[0]->nama_pasien; ?><br>
  Dokter : <?php echo $result[0]->dokter_pengirim?>
  <hr style="margin-top: -10px">
</p>

  <?php 
    if(count($result) == 0 ) : echo '<h2>Pemberitahuan</h2>- Tidak ada resep ditemukan - '; exit; endif; 
    foreach( $result as $rows ) : 
  ?>

      <?php echo $rows->nama_brg;?> / <?php echo $rows->jumlah_pesan;?> (<?php echo $rows->satuan_kecil;?>) <br>

  <?php endforeach;?>
  <br>
  <center>
    <div id="barcodeTarget<?php echo $result[0]->kode_trans_far?>" class="barcodeTarget" ></div>
  </center>
  
</div>
