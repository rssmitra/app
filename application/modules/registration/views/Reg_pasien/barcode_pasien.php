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
      moduleSize: 10,
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
    .barcodeTarget{
      font-weight: bold;
      letter-spacing: 5px;
    }
    @media print
    {
    body div#print_area, body div#print_area {display: block;}
    }
</style>
<body>
<center>
<?php if($count != '') : for($i=0;$i<$count;$i++) : ?>
<div style="width: 265px; height: 90px; padding: 5px; border: 0px solid;" id="print_area">
  <div style="align: left; margin-top: -5px; float: left">
    <!-- <b><span style="float: left"><?php $str = array('TN.', 'Tn.', 'NY.', 'Ny.', 'AN.', 'An.','NN.','Nn.','By.'); echo str_replace($str, '' ,$pasien->nama_pasien) ?> <?php echo $pasien->title?> (<?php echo $pasien->jen_kelamin?>) </b></span> -->
    <b><span style="float: left"><?php $str = array('By.'); echo str_replace($str, '' ,$pasien->nama_pasien) ?> <?php echo $pasien->title?> (<?php echo $pasien->jen_kelamin?>) </b></span>
    <br>
    <span style="float: left; margin-top:-5px">BOD. <?php echo $this->tanggal->formatDateShort($pasien->tgl_lhr)?>   
    (<?php echo $this->tanggal->AgeWithYearMonth($pasien->tgl_lhr)?>)</span>
  </div>
  <div id="barcodeTarget<?php echo $i?>" class="barcodeTarget"></div>
</div>
<?php endfor; endif; ?>
<input id="printpagebutton" type="button" class="btn btn-xs btn-inverse" value="~ PRINT ~" onclick="printpage()"/>
</center>
</body>

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

