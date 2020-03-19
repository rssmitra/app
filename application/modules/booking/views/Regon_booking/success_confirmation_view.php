<script src="<?php echo base_url().'assets/barcode-master/prototype/sample/prototype.js'?>" type="text/javascript"></script>
<script src="<?php echo base_url().'assets/barcode-master/prototype/prototype-barcode.js'?>" type="text/javascript"></script>
<script type="text/javascript">

window.onload = generateBarcode;

  function generateBarcode(){
    $("barcodeTarget").update();
    var value = "211762-010201-000121";
    var btype = "code128";
    
    var settings = {
      output:"css",
      bgColor: "#FFFFFF",
      color: "#000000",
      barWidth: 1,
      barHeight: 30,
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
        font-size: 12px;
        margin-top:20px;
    };
</style>
<table border="0" width="50%">
<tr>
  <td width="70%">
    <img src="<?php echo base_url()?>assets/images/logo.png" style="width:50px;float:left">
    <div style="float:left;margin:10px 10px 10px 10px"><b>BUKTI REGISTRASI ONLINE<br>RS SETIA MITRA</b></div>
  </td>
  <td align="right" width="30%">
    <div id="barcodeTarget" class="barcodeTarget"></div>
  </td>
</tr>
</table>

<table border="0" width="50%">
<tr>
  <td>Kode Booking</td><td colspan="3">: QR032178 &nbsp;&nbsp;&nbsp;&nbsp;&nbsp; Tanggal, 12 Oktober 2018</td>
</tr>

<tr>
  <td>Nama Pasien</td><td>: Muhammad Amin Lubis</td>
  <td style="padding-left:50px">No. MR</td><td>: 00211762</td>
</tr>

<tr>
  <td>Tgl Lahir</td><td>:  Tangerang, 23 September 1990 &nbsp;&nbsp;&nbsp;&nbsp; Kelamin : Laki-Laki</td>
  <td style="padding-left:50px">Penjamin</td><td>: BPJS Kesehatan</td>
</tr>

<tr>
  <td>No Telepon</td><td>: 0858195529</td>
</tr>

<tr>
  <td>Poli Tujuan</td><td>: Klinik Spesialis Penyakit Dalam</td>
</tr>

<tr>
  <td>Nama Dokter</td><td>: Arlis FS Reksoprodjo dr.SPD</td>
</tr>

<tr>
  <td>Tanggal Perjanjian</td><td>: 21 Desember 2018 10:00 s/d 12.00</td>
</tr>

<tr>
  <td>Catatan</td><td>: </td>
</tr>

</table>

<table border="0">
<tr>
<td>
<p style="font-size:11px"><b>Pemberitahuan !</b><br>
  Silahkan lakukan registrasi ulang satu jam sebelum praktek dokter dimulai<br>
  Loket anda akan mulai dibuka jam 09.00 s/d praktek dokter dimulai.<br>
  Kami tidak akan melayani registrasi ulang pasien diluar waktu yang sudah ditentukan<br>
</p>
</td>
<td valign="top" style="padding-left:120px">
Pasien/Keluarga Pasien<br><br><br><br>____________________
</td>
</tr>
<tr>
<td>
</td>
</tr>
</table>


     