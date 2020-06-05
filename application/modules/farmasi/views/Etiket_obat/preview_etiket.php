<script src="<?php echo base_url().'assets/barcode-master/prototype/sample/prototype.js'?>" type="text/javascript"></script>
<script src="<?php echo base_url().'assets/barcode-master/prototype/prototype-barcode.js'?>" type="text/javascript"></script>

<script type="text/javascript">

window.onload = generateBarcode;

  function generateBarcode(){

    <?php foreach( $result as $rows_dt ) :?>
    $("barcodeTarget<?php echo $rows_dt->kode_brg?>").update();
    var value = "<?php echo $rows_dt->kode_brg; ?>-<?php echo $rows_dt->relation_id; ?>";
    var btype = "code128";
    
    var settings = {
      output:"css",
      bgColor: "#FFFFFF",
      color: "#000000",
      barWidth: 1.25,
      barHeight: 40,
      moduleSize: 10,
      fontSize: 11,
      posX: 15,
      posY: 15,
      addQuietZone: false
    };
    $("barcodeTarget<?php echo $rows_dt->kode_brg?>").update().show().barcode(value, btype, settings);
  <?php endforeach;  ?>


  }
    
</script> 

<style>
.body{
  width: 265px;
  height: 210px;
  /* border: 1px solid grey; */
  /*font-weight: bold;*/
  font-size: 14px;
  font-family: Arial Narrow;
  color: black;
  text-align: left;
  background-color: white; 
}

table{
  /*font-weight: bold;*/
}

@media print{ #barPrint{
		display:none;
	}
}
.monotype_style{
    font-family : Monotype Corsiva, Times, Serif !important;
    font-size: 14px; 
  }
</style>
<!-- <div id="barPrint" style="float: right">
  <button class="tular" onClick="window.close()">Tutup</button>
  <button class="tular" onClick="print()">Cetak</button>
</div> -->
<?php 
  if(count($result) == 0 )
  { 
    echo '<h2>Pemberitahuan</h2>- Tidak ada etiket ditemukan - '; exit; 
  } 

  foreach( $result as $rows ) : 
?>
<center style="margin-bottom: 5px">
<div class="body" style="page-break-after: always; border: 1px solid grey; padding: 5px">
  <table border=0 width="100%" style="border-bottom: 1px solid">
    <tr>
      <td><img src="<?php echo base_url().'assets/images/logo-black.png'?>" alt="" style="width: 50px"></td>
      <td align="left">
        <span style="font-size: 10px">INSTALASI FARMASI</span><br>
        <span style="font-size: 12px"><?php echo strtoupper(COMP_LONG); ?></span><br>
        <span style="font-size: 9px"><?php echo COMP_ADDRESS_SORT; ?></span><br>
      </td>
    </tr>
    <tr>
      <td colspan="2" align="center" style="margin-top: -5px">
        
      </td>
    </tr>
  </table> 
  <center>
    <span style="font-size: 11px">Apoteker : Zora Almira, S.Farm., Apt</span><br>
        <span style="font-size: 10px">4/B.19/31.74.06.1001.025.S.2.b.d/3/-1.779.3/e/1019
  </center>
  <div style="width: 100%; font-size: 12px"> 
    <div style="width: 40%; float:left">
      No. <?php echo $rows->kode_trans_far; ?>
    </div>
    <div style="width: 60%; float: right; text-align: right">
    Tgl. <?php echo $this->tanggal->formatDatedmY($rows->tgl_trans); ?>
    </div>
  </div>
  
  <p style="padding-top: 0px">
    <center>
    <?php echo ucwords(strtolower($rows->nama_brg));?><br>
    <span style="font-weight: bold; font-size: 16px">Sehari <?php echo $rows->dosis_per_hari; ?> x <?php echo $rows->dosis_obat; ?> <?php echo $rows->satuan_obat; ?></span>
      <br><?php echo $rows->anjuran_pakai; ?> <br>
    </center>
    <span style="font-size: 10px">* <?php echo $rows->catatan_lainnya; ?></span>
    <hr>
    <div style="width: 100%; font-size: 12px !important"> 
      <span>Nama</span> : <?php echo ucwords(strtolower($rows->nama_pasien)); ?><br>
      <span style="padding-right: 12px">ED</span> :  
      <!-- <span >TL/ Umur</span> :  <?php echo $this->tanggal->formatDatedmY($rows->tgl_lhr); ?>  (<?php echo $this->tanggal->AgeWithYearMonthDay($rows->tgl_lhr)?>) -->
      <center class="monotype_style">Semoga lekas sembuh</center>
    </div>
  </p>
</div>
</center>
<?php endforeach;?>

