<link href="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/themes/south-street/jquery-ui.css" rel="stylesheet">
<link href="<?php echo base_url()?>assets/jSignature/css/jquery.signature.css" rel="stylesheet">
<style>
.kbw-signature { width: 400px; height: 200px; }
</style>
<!--[if IE]>
<script src="excanvas.js"></script>
<![endif]-->
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.12.4/jquery.min.js"></script>
<script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="<?php echo base_url()?>assets/jSignature/js/jquery.signature.js"></script>
<script>
$(function() {
	var sig = $('#sig').signature();
	$('#disable').click(function() {
		var disable = $(this).text() === 'Disable';
		$(this).text(disable ? 'Enable' : 'Disable');
		sig.signature(disable ? 'disable' : 'enable');
	});
	$('#clear').click(function() {
		sig.signature('clear');
	});
	$('#json').click(function() {
		alert(sig.signature('toJSON'));
	});
	$('#svg').click(function() {
		alert(sig.signature('toSVG'));
	});
});
</script>
<style type="text/css">
    table {
        font-family: arial;
        font-size: 13px
    };
    #sig canvas{
      border: 1px solid grey !important;
    }
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
<div style="background-color:white !important; padding: 25px">

  <table border="0" width="100%">
    <tr>

      <!-- Nomor antrian poli/klinik -->
      <td valign="top" style="text-align: center; border: 1px solid grey; width: 15%">
        Nomor Antrian Poli<br>
        <span style="font-size: 150px">1</span><br>
        <span style="font-size: 11px">
          Poli/Klinik Spesialis Penyakit Dalam<br>
          dr. Rima Sagita. Sp.PD<br>
          Jam Praktek 10.00 WIB
        </span>
      </td>

      <!-- SEP -->
      <td style="padding-left: 15px; border: 1px solid black">

        <table border="0">
          <tr>
            <td style="padding-left:30px">
              <b>SURAT ELEGIBILITAS PESERTA<br><?php echo strtoupper(COMP_LONG); ?></b>
            </td>
          </tr>
        </table>
        </br>

        <table border="0">
          <tr>
            <td width="150px">No SEP</td><td colspan="3">: <?php echo $sep->noSep?></td>
          </tr>
          <tr>
            <td>Tgl SEP</td><td>: <?php echo $sep->tglSep?></td>
            <td style="padding-left:80px">Peserta</td><td>: <?php echo $sep->peserta->jnsPeserta?></td>
            <td style="padding-left:80px">Faskes Perujuk</td><td>: </td>
          </tr>
          <tr>
            <td>No Kartu</td><td>: <?php echo $sep->peserta->noKartu?> (MR. <?php echo $sep->peserta->noMr?>)</td>
            <td style="padding-left:80px">COB</td><td>: -</td>
            <td style="padding-left:80px">Diagnosa Awal</td><td colspan="2">: <?php echo $sep->diagnosa?></td>
          </tr>
          <tr>
            <td>Nama Peserta</td><td>: <?php echo $sep->peserta->nama?></td>
            <td style="padding-left:80px">Jns. Rawat</td><td>: <?php echo $sep->jnsPelayanan?></td>
            <td style="padding-left:80px">Catatan</td><td colspan="2">: <?php echo $sep->catatan?></td>
          </tr>
          <tr>
            <td>Tgl Lahir</td><td>: <?php echo $sep->peserta->tglLahir?> &nbsp;&nbsp;&nbsp;&nbsp; Kelamin : <?php echo $sep->peserta->kelamin?></td>
            <td style="padding-left:80px">Kls. Rawat</td><td>: -</td>
            <td style="padding-left:80px" rowspan="4" valign="top" colspan="3">Pasien/Keluarga Pasien<br></br><br></br>______________________</td>
          </tr>
          <tr>
            <td>No Telepon</td><td>: <?php echo isset($sep->peserta->noTelp)?$sep->peserta->noTelp:''?></td>
            <td style="padding-left:80px">Penjamin</td><td>: <?php echo $sep->penjamin?></td>
          </tr>
          <tr>
            <td>Poli Tujuan</td><td>: <?php echo $sep->poli?></td>
            <td align="right">DPJP Yg Melayani </td><td>: </td>
          </tr>

          <tr>
            <td valign="top" colspan="6">
              <p style="font-size:12px">*Saya menyetujui BPJS Kesehatan menggunakan informasi medis pasien jika diperlukan<br>
              SEP Bukan sebagai bukti penjaminan peserta<br></p>
              <span style="font-size:11px">Cetakan ke <?php echo $cetakan_ke?> <?php echo date('d-m-Y H:i:s')?> wib</span>
            </td>
          </tr>
        </table>

      </td>
    </tr>
  </table>
  
  <!-- <div id="sig"></div>
  <p style="clear: both;">
    <button id="disable">Disable</button> 
    <button style="height: 20px; font-size: 14px" id="clear">Reset Signature</button> 
    <button style="height: 20px; font-size: 14px" id="print_sep">Cetak SEP</button> 
    <button id="json">To JSON</button>
    <button id="svg">To SVG</button>
  </p> -->

  <a href="">Print</a>
  

</div>




     