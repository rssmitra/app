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
        font-size: 12px;
        margin-top:20px;
    };

</style>
<body style="background-color:white" >
<!-- <table border="0" width="550px">
<tr>
  <td width="80px">
    <img src="<?php echo base_url().COMP_ICON; ?>" style="width:70px">
  </td>
  <td style="padding-left:10px">
    <b style="font-size:16px"><?php echo strtoupper(COMP_LONG); ?></b><br>
    <?php echo COMP_ADDRESS_SORT?><br>
    Telp: <?php echo COMP_TELP?>  (HUNTING)  FAX.<?php echo COMP_FAX?>
  </td>
</tr>
</table> -->
<div style="max-width:550px;margin-top:250px">
  <!-- <hr> -->
  <center><b><h2>RINGKASAN PASIEN MASUK DAN KELUAR</h2></b></center>
</div>
<table border="0" width="600px">
<tr>
  <td colspan="2"><b>IDENTITAS PASIEN</b></td>
</tr>
<tr>
  <td width="150px">No. Rekam Medik</td>
  <td>: <?php echo $pasien->no_mr?> </td>
</tr>
<tr>
  <td>Nama</td>
  <td>: <?php echo $pasien->nama_pasien?></td>
  <td>Jenis Kelamin</td>
  <td>: <?php echo $pasien->jen_kelamin?> </td>
</tr>
<tr>
  <td>Tempat & Tanggal Lahir</td>
  <td>: <?php echo $pasien->tempat_lahir.', '?> <?php echo $this->tanggal->formatDateShort($pasien->tgl_lhr)?> </td>
  <td>Agama</td>
  <td>: <?php echo $pasien->religion_name?> </td>
</tr>
<tr>
  <td>Status Perkawinan</td>
  <td>: <?php echo $pasien->ms_name?> </td>  
  <td>Umur</td>
  <td>: <?php echo $this->tanggal->AgeWithYearMonthDay($pasien->tgl_lhr)?> </td>
</tr>
<tr>
  <td>Alamat</td>
  <td colspan="3">: <?php echo $pasien->almt_ttp_pasien?> </td>
</tr>
<tr>
  <td>No Telp/Hp</td>
  <td colspan="3">: <?php echo $pasien->tlp_almt_ttp?> / <?php echo $pasien->no_hp?> </td>
</tr>

<tr>
  <td colspan="2"><br><b>RINGKASAN KUNJUNGAN</b></td>
</tr>

<tr style="line-height:1.5em;">
  <td>Tanggal Kunjungan/Jam</td>
  <td>: <?php echo date('D, d/m/Y H:i:s')?></td>
  <td>Penjamin</td>
  <td>: <?php echo $pasien->nama_perusahaan?> </td>
</tr>
<tr style="line-height:1.5em;">
  <td>Tanggal Masuk/Jam</td>
  <td>: <?php echo date('D, d/m/Y H:i:s')?></td>
  <td>Tanggal Keluar/Jam</td>
  <td>: </td>
</tr>
<tr style="line-height:1.5em;">
  <td>Nama Dokter Penanggung Jawab</td>
  <td>: </td>
  <td>No Telp PJ</td>
  <td>: -</td>
</tr>
<tr style="line-height:1.5em;">
  <td>Spesialisasi</td>
  <td>: </td>
</tr>
<tr style="line-height:1.5em;">
  <td>Ruang Rawat</td>
  <td>: </td>
  <td>Kelas</td>
  <td>: </td>
</tr>

<!-- <tr>
  <td>Diagnosa Masuk</td>
  <td>: </td>
</tr> -->

<!-- <tr>
  <td align="right" colspan="4"><br><div id="barcodeTarget" class="barcodeTarget"></div></td>
</tr>


<tr>
  <td align="left">
  <br>
    <div id="options">
      <input id="printpagebutton" type="button" class="btn btn-xs btn-inverse" value="~ PRINT ~" onclick="printpage()"/>
    </div>
  </td>
</tr> -->

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

    /*@page {
        size: A5 portrait;
        margin: 0mm;
        width: 250px;
        height: 250px;
    }*/
    body { 
        background-color: white; 
        /*margin: 1in;*/
    }
    p {
        font-family: sans-serif;
        font-size: 20px;
        color: black;
    }
}

</style>

</body>
</body>