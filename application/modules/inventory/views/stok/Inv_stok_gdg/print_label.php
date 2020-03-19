<script src="<?php echo base_url().'assets/barcode-master/prototype/sample/prototype.js'?>" type="text/javascript"></script>
<script src="<?php echo base_url().'assets/barcode-master/prototype/prototype-barcode.js'?>" type="text/javascript"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />

<script type="text/javascript">

window.onload = generateBarcode;

  function generateBarcode(){

    <?php if( $count != '' ) : for($i=0; $i < $count; $i++ ) :?>
    $("barcodeTarget<?php echo $i?>").update();
    var value = "<?php echo $barang[$i]->kode_brg; ?>";
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
    $("barcodeTarget<?php echo $i?>").update().show().barcode(value, btype, settings);
    <?php endfor; endif; ?>


  }
    
</script> 

<style type="text/css">
    table {
        font-family: arial;
        font-size: 11px;
        margin-top:0px;
    }
    .barcodeTarget{
      font-weight: bold;margin-top: 5px;letter-spacing: 11px;
    }
</style>
<body style="background-color:white; align: center" >


<div id="barPrint" style="float: right">
  <button class="tular" onClick="window.close()">Tutup</button>
  <button class="tular" onClick="printpage()">Cetak</button>
</div>

<center>

<table border="0">
  <?php foreach( $barang as $key=>$rows ) : ?>
    <tr>
      <td align="left" style="width:265px; height:95px">
        <b><span style="font-size: 14px"><?php echo $rows->kode_brg; ?></span></b> <br>
        <span style="font-size: 12px"><?php echo ucwords(strtolower($rows->nama_brg)).' <br>('.$rows->content.' '.$rows->satuan_kecil.'/'.$rows->satuan_besar.')'?></span><br>
        <div style="margin-left: 40px" id="barcodeTarget<?php echo $key?>" class="barcodeTarget"></div>
      </td>
    </tr>
  <?php endforeach; ?>
</table>

</center>

<script type="text/javascript">
  
  function printpage() {
        //Get the print button and put it into a variable
        var printButton = document.getElementById("barPrint");
        //Set the print button visibility to 'hidden' 
        printButton.style.visibility = 'hidden';
        //Print the page content
        window.print()
        printButton.style.visibility = 'visible';
    }

</script>

<style type="text/css">
  #options {
    align-content:left;
    align-items:center;
    text-align: center;
    cursor: pointer;
  }

  @media print {

    @page {
        /*size: A5 portrait;*/
        margin: 0mm;
        width: 265px;
        height: 95px;
    }
    body { 
        background-color: white; 
        margin: 1in;
    }
    
}

</style>
