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
      barHeight: 50,
      moduleSize: 25,
      posX: 20,
      posY: 20,
      fontSize:12,
      addQuietZone: false
    };

    $("barcodeTarget").update().show().barcode(value, btype, settings);

  }
    
</script> 
<style type="text/css">
    table {
        font-family: arial;
        font-size: 11px;
        margin-left: 30%;
        margin-top: 2%;
    }
    .barcodeTarget{
      font-weight: bold;letter-spacing: 11px;
    }
</style>
<body style="background-color:white" >

<table border="0" align="center" class="rotate-X">
  <tr>

    <td align="left" width="55%" style="font-size: 18px; padding-top: 1px;">
      <b><?php echo (string)$pasien->nama_pasien?></b> <br>
      <?php echo $this->tanggal->formatDateShort($pasien->tgl_lhr)?> (<?php echo $pasien->jen_kelamin?>)
    </td>

    <td align="left">
      <div id="barcodeTarget" class="barcodeTarget"></div>
    </td>

  </tr>
</table>

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

<style type="text/css">

  .rotate {

    /* Safari */
    -webkit-transform: rotate(-90deg);

    /* Firefox */
    -moz-transform: rotate(-90deg);

    /* IE */
    -ms-transform: rotate(-90deg);

    /* Opera */
    -o-transform: rotate(-90deg);

    /* Internet Explorer */
    filter: progid:DXImageTransform.Microsoft.BasicImage(rotation=3);

    }

  /*#options {
    align-content:left;
    align-items:left;
    text-align: left;
    cursor: pointer;
  }*/


</style>

</body>