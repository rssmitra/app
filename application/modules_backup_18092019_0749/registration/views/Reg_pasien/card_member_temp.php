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
      barHeight: 35,
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
        font-size: 11px;
        margin-top:20px;
    };
</style>
<body style="background-color:white" >
<table border="0" width="350px">
<tr>
  <td>
    <img src="<?php echo base_url()?>assets/images/logo.png" style="width:70px">
  </td>
  <td style="padding-left:10px">
    <b>RS SETIA MITRA</b><br>
    Jl. RS. Fatmawati No. 80 - 82<br>
    Telp: (021) 7656000  (HUNTING)  FAX.(021) 7656875
  </td>
</tr>
</table>
<div style="max-width:350px">
  <hr>
  <center><b>KARTU PASIEN SEMENTARA</b></center>
</div>
<table border="0" width="350px">
<tr>
  <td width="130px">No. Rekam Medik</td>
  <td>: <?php echo $pasien->no_mr?> </td>
</tr>
<tr>
  <td>Nama</td>
  <td>: <?php echo $pasien->nama_pasien?> (<?php echo $pasien->jen_kelamin?>) </td>
</tr>
<tr>
  <td>Tanggal Lahir</td>
  <td>: <?php echo $this->tanggal->formatDateShort($pasien->tgl_lhr)?> </td>
</tr>
<tr>
  <td>&nbsp;</td>
  <td align="right"><br><div id="barcodeTarget" class="barcodeTarget"></div></td>
</tr>

<tr>
  <td align="left">
    
  </td>
</tr>
<tr>
  <td align="left">
  <br>
    <div id="options">
      <input id="printpagebutton" type="button" class="btn btn-xs btn-inverse" value="~ PRINT ~" onclick="printpage()"/>
    </div>
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