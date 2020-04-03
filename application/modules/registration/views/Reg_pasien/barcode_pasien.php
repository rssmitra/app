<script src="<?php echo base_url().'assets/barcode-master/prototype/sample/prototype.js'?>" type="text/javascript"></script>
<script src="<?php echo base_url().'assets/barcode-master/prototype/prototype-barcode.js'?>" type="text/javascript"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />

<script type="text/javascript">

window.onload = generateBarcode;

  function generateBarcode(){

    <?php if($count != '') : for($i=0;$i<$count;$i++) :?>
    $("barcodeTarget<?php echo $i?>").update();
    var value = "<?php echo $no_mr; ?>";
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
<center>
<table border="0" style="margin-left: -65px">

<?php if($count != '') : for($i=0;$i<$count;$i++) : ?>
<tr>
  <td align="left" width="250px">
    <b><?php $str = array('TN.', 'Tn.', 'NY.', 'Ny.', 'AN.', 'An.','NN.','Nn.','By.'); echo str_replace($str, '' ,$pasien->nama_pasien) ?> <?php echo $pasien->title?> (<?php echo $pasien->jen_kelamin?>) </b> <br>
    BOD. <?php echo $this->tanggal->formatDateShort($pasien->tgl_lhr)?>   
    (<?php echo $this->tanggal->AgeWithYearMonth($pasien->tgl_lhr)?>)
  </td>
</tr>
<tr>
  <td align="left">
    <div id="barcodeTarget<?php echo $i?>" class="barcodeTarget"></div>
  </td>
</tr>
<?php endfor; endif; ?>

<tr>
  <td align="left">
  <br>
    <div id="options">
      <input id="printpagebutton" type="button" class="btn btn-xs btn-inverse" value="~ PRINT ~" onclick="printpage()"/>
    </div>
  </td>
</tr>

</table>
</center>
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
        width: 250px;
        height: 250px;
    }
    body { 
        background-color: white; 
        margin: 1in;
    }
    p {
        font-family: sans-serif;
        font-size: 20px;
        color: black;
    }
}

</style>

</body>