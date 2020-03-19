<script src="<?php echo base_url().'assets/barcode-master/prototype/sample/prototype.js'?>" type="text/javascript"></script>
<script src="<?php echo base_url().'assets/barcode-master/prototype/prototype-barcode.js'?>" type="text/javascript"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />

<script type="text/javascript">

window.onload = generateBarcode;

  function generateBarcode(){
    $("barcodeTarget").update();
    var value = "<?php echo $sep->noSep?>";
    var btype = "code128";
    
    var settings = {
      output:"css",
      bgColor: "#FFFFFF",
      color: "#000000",
      barWidth: 1,
      barHeight: 50,
      moduleSize: 5,
      posX: 10,
      posY: 20,
      addQuietZone: false
    };

    $("barcodeTarget").update().show().barcode(value, btype, settings);

  }
    
</script> 
<style type="text/css">
    table {
        font-family: arial;
        font-size: 13px
    };
</style>

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
</style>
<body style="background-color:white">
  <div id="options">
    <br>
    <input id="printpagebutton" type="button" class="btn btn-xs btn-inverse" value="~ PRINT SEP ~" onclick="printpage()"/>
  </div>

  <table border="0">
  <tr>
  <td>
  <img src="<?php echo base_url()?>assets/images/logo-bpjs.png" style="width:200px">
  </td>
  <td style="padding-left:30px">
  <b>SURAT ELEGIBILITAS PESERTA<br>RS SETIA MITRA</b>
  </td>
  </tr>
  </table>
  </br>

  <table border="0">
  <tr>
  <td width="150px">No SEP</td><td colspan="3">: <?php echo $sep->noSep?></td>
  </tr>
  <td>Tgl SEP</td><td>: <?php echo $sep->tglSep?></td><td style="padding-left:150px">Peserta</td><td>: <?php echo $sep->jnsPeserta?></td>
  </tr>
  <td>No Kartu</td><td>: <?php echo $sep->noKartu?> (MR. <?php echo $sep->noMr?>)</td><td style="padding-left:150px">COB</td><td>: -</td>
  </tr>
  <td>Nama Peserta</td><td>: <?php echo $sep->nama?></td><td style="padding-left:150px">Jns. Rawat</td><td>: <?php echo $sep->jnsPelayanan?></td>
  </tr>
  <td>Tgl Lahir</td><td>: <?php echo $sep->tglLahir?> &nbsp;&nbsp;&nbsp;&nbsp; Kelamin : <?php echo $sep->kelamin?></td><td style="padding-left:150px">Kls. Rawat</td><td>: -</td>
  </tr>
  <td>No Telepon</td><td>: 0858195529</td><td style="padding-left:150px">Penjamin</td><td>: <?php echo $sep->penjamin?></td>
  </tr>
  <td>Poli Tujuan</td><td>: <?php echo $sep->poli?></td>
  </tr>
  <td>Faskes Perujuk</td><td>: <?php echo $sep->PPKPerujuk?></td>
  </tr>
  <td>Diagnosa Awal</td><td colspan="2">: <?php echo $sep->diagnosa?></td>
  </tr>
  <td>Catatan</td><td colspan="2">: <?php echo $sep->catatan?></td>
  </tr>
  </table>

  <table border="0">
  <tr>
  <td>
  <p style="font-size:12px">*Saya menyetujui BPJS Kesehatan menggunakan informasi medis pasien jika diperlukan<br>
  SEP Bukan sebagai bukti penjaminan peserta<br></p>
  <span style="font-size:11px">Cetakan ke <?php echo $cetakan_ke?> <?php echo date('d-m-Y H:i:s')?> wib</span>
  </td>
  <td valign="top" style="padding-left:120px">
  Pasien/Keluarga Pasien<br><br><br><br>____________________
  </td>
  </tr>
  <tr>
  <td>
  <!-- barcode here -->
  <!-- <div style="margin-top:-10px">
  <div id="barcodeTarget" class="barcodeTarget"></div>
  </div> -->
  </td>
  </tr>
  </table>
</body>




     