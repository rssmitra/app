<!-- 
<script src="../assets_als/barcode-master/prototype/sample/prototype.js" type="text/javascript"></script>
<script src="../assets_als/barcode-master/prototype/prototype-barcode.js" type="text/javascript"></script>
<script type="text/javascript">

window.onload = generateBarcode;

  function generateBarcode(){
    $("barcodeTarget").update();
    var value = "0112R034000001";
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
    
</script>  -->
<style type="text/css">
    .stamp {
      margin-top: -30px;
      margin-left: 175px;
      position: absolute;
      display: inline-block;
      color: black;
      padding: 1px;
      padding-left: 10px;
      padding-right: 10px;
      background-color: white;
      box-shadow:inset 0px 0px 0px 7px black;
      opacity: 0.5;
      -webkit-transform: rotate(25deg);
      -moz-transform: rotate(25deg);
      -ms-transform: rotate(25deg);
      -o-transform: rotate(25deg);
      transform: rotate(0deg);
    }
    table {
        font-family: arial;
        font-size: 13px
    };
    
</style>
<table border="0">
<tr>
<td>
<img src="../../assets/images/logo_rssm_default.png" style="width:70px">
</td>
<td style="padding-left:30px;">
<b>SURAT KONTROL PASIEN<br>RS SETIA MITRA</b>
</td>
<td align="right"><div class="stamp"><h1> WAJIB DIBAWA </h1></div></td>
</tr>
</table>
</br>

<table border="0">
<tr>
<td width="150px">Nomor Surat Kontrol</td><td colspan="3">: <?php echo ($value->kode_perjanjian!=NULL)?$value->kode_perjanjian:$value->no_registrasi?></td>
</tr>

<tr>
<td>Tanggal Kembali</td><td>: <?php echo $this->tanggal->formatDate($value->tgl_kembali)?></td><td style="padding-left:80px">Penjamin</td><td>: <?php echo $value->nama_perusahaan?></td>
</tr>

<tr>
<td>Nama Pasien</td><td>: <?php echo $value->nama?></td><td style="padding-left:80px">No RM</td><td>: <?php echo $value->no_mr?></td>
</tr>

<tr>
<td>Poli Tujuan</td><td>: <?php echo $value->nama_bagian?></td>
</tr>

<tr>
<td>Dokter</td><td>: <?php echo $value->dokter?></td>
</tr>

<tr>
<td>Diagnosa Terakhir</td><td colspan="2">: <?php echo $value->diagnosa_akhir?></td>
</tr>

<tr>
<td>Catatan</td><td colspan="2">: <?php echo $value->keterangan?> </td>
</tr>

</table>

<table border="0">
<tr>
<td>
<p>
  Belum dapat dikembalikanke Faskes Perujuk dengan alasan :
  <ol>
    <li>Kondisi pasien masih belum stabil</li>
    <li>Masih dalam pengawasan khusus</li>
    <li>Pemantauan penggunaan obat-obatan</li>
    <li>Lain-lain</li>
  </ol>
</p>
<span style="font-size:11px">Cetakan ke <?php echo $value->counter?> <?php echo date('d-m-Y H:i:s')?> wib</span>
</td>
<td valign="top" style="padding-left:75px">
<br><br>
Jakarta, .......................... <br><br><br><br>_________________________
</tr>
<tr>
<td>
<!-- barcode here -->
<!-- <div style="margin-top:5px">
<div id="barcodeTarget" class="barcodeTarget"></div>
</div> -->
</td>
</tr>
</table>


