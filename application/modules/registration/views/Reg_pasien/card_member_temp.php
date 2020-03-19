<script src="<?php echo base_url().'assets/barcode-master/prototype/sample/prototype.js'?>" type="text/javascript"></script>
<script src="<?php echo base_url().'assets/barcode-master/prototype/prototype-barcode.js'?>" type="text/javascript"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />

<script type="text/javascript">

window.onload = generateBarcode;

  function generateBarcode(){
    $("barcodeTarget").update();
    var value = "<?php echo $no_mr; ?>";
    var btype = "code128";
    
    var settings = {
      output:"css",
      bgColor: "#FFFFFF",
      color: "#000000",
      barWidth: 2,
      barHeight: 55,
      moduleSize: 30,
      posX: 20,
      posY: 20,
      addQuietZone: false
    };

    $("barcodeTarget").update().show().barcode(value, btype, settings);

  }
    
</script> 
<style type="text/css">
    table {
        font-family: arial;
        font-size: 18px;
        margin-top:20px;
    };
</style>
<body style="background-color:white" >

<table border="0" width="400px" style="float:left;">
<tr>
  <td>
    <br>
    <b><?php echo $pasien->no_mr?></b><br>
    <?php echo $pasien->nama_pasien?> (<?php echo $pasien->jen_kelamin?>) <br>
    <?php echo $this->tanggal->formatDateShort($pasien->tgl_lhr)?>
  </td>
</tr>
<tr>
  <td align="right">
    <br>
    <div id="barcodeTarget" class="barcodeTarget"></div>
  </td>
</tr>
</table>

<input id="printpagebutton" type="button" class="btn btn-xs btn-inverse" value="~ PRINT ~" onclick="printpage()"/>

<script type="text/javascript">
  
  function printpage() {
        //Get the print button and put it into a variable
        var printButton = document.getElementById("printpagebutton");
        //Set the print button visibility to 'hidden' 
        printButton.style.visibility = 'hidden';
        //Print the page content
        window.print()
        printButton.style.visibility = 'visible';
    }

</script>


</body>