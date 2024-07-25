<html>
  <head>
    <title>PRINT BUKTI PENDAFTARAN</title>
  </head>
  <body>
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
    body table {
        font-family: arial;
        font-size: 12px
    }

    .table-content th {
      height: 30px;
      border-bottom: 1px solid #ddd;
      border-top: 1px solid #ddd;
      text-align: left;
      padding: 8px
    }

    /*.table-content td {
      border: 1px solid #ddd;
    }*/
    @media print {
        .pagebreak { page-break-before: always; } /* page-break-after works, as well */
    }
    

</style>
<body>
<table border="0">
<tr>
  <!-- <td width="50px">
  <img src="<?php echo base_url().COMP_ICON; ?>" style="width:50px">
  </td> -->
  <td style="padding-left:10px;font-size: 14px" align="center">
  <b>BUKTI PENDAFTARAN PASIEN<br><?php echo strtoupper(COMP_LONG); ?></b><br>
  <small style="font-size:9px"><?php echo COMP_ADDRESS?></small>
  </td>
  <!-- <td align="right"><div class="stamp"><h1> Advanced Type 1 </h1></div></td> -->
</tr>
</table>

<hr>
<!-- DATA PASIEN -->
<table border="0">
    <tr>
      <td width="90px" valign="top" colspan="2"><span style="font-family: arial; font-size: 14px; text-align: center"><b>DATA PASIEN</b></span></td>
    </tr>
    <tr>
      <td width="90px" valign="top">No. MR</td><td> <?php echo $_GET['no_mr']?></td>
    </tr>
    <tr>
      <td width="90px" valign="top">Nama Pasien</td><td> <?php echo $_GET['nama']?> (<?php echo $result['registrasi']->jen_kelamin?>)</td>
    </tr>
    <tr>
      <td width="90px" valign="top">Tgl Lahir</td><td> <?php echo $this->tanggal->formatDateDmy($result['registrasi']->tgl_lhr)?> (<?php echo $umur?> Thn)</td>
    </tr>
    <tr>
      <td width="90px" valign="top" valign="top">Nasabah</td><td> <?php echo ($_GET['nasabah'] == 'null')?'Umum':$_GET['nasabah'];?></td>
    </tr>
    <!-- <tr>
      <td width="90px" valign="top">No.Telp</td><td> <?php echo $result['registrasi']->tlp_almt_ttp?></td>
    </tr> -->
</table>

<hr>
<!-- DATA KUNJUNGAN -->
<table border="0">
    <tr>
      <td width="90px" colspan="2"><b><span style="font-family: arial; font-size: 14px; text-align: center"><b>KUNJUNGAN RAWAT JALAN </b></span></td>
    </tr>
    <tr>
      <td width="90px">Tanggal</td><td> <?php echo $this->tanggal->formatDateDmy($registrasi->tgl_jam_masuk)?></td>
    </tr>
    <tr>
      <td width="90px">Poli Tujuan</td><td> <?php echo ucwords($_GET['poli'])?></td>
    </tr>
    <tr>
      <td width="90px">Dokter</td><td> <?php echo $_GET['dokter']?></td>
    </tr>
    <tr>
      <td width="90px">No. Antrian</td><td> <?php echo $result['registrasi']->kode_perusahaan == 120?'A ':'B '; echo $result['no_antrian']->no_antrian?></td>
    </tr>
</table>

<!-- assesmen pasien rawat jalan -->
<!-- <hr>
<table border="0" width="100%" class="table-content">
  <tr>
    <td colspan="4" align="left"><span style="font-family: arial; font-size: 14px; text-align: center"><b>ASSESMEN PASIEN RAWAT JALAN</b></span></td>
  </tr>
  <tr>
    <td width="30px" style="font-size: 13px; padding: 5px">TD </td><td width="100px">: </td>
    <td width="30px" style="font-size: 13px; padding: 5px">Nadi</td><td width="100px">: </td>
  </tr>
  <tr>
    <td width="30px" style="font-size: 13px; padding: 5px">P </td><td width="100px">: </td>
    <td width="30px" style="font-size: 13px; padding: 5px">Suhu</td><td width="100px">: </td>
  </tr>
  <tr>
    <td width="30px" style="font-size: 13px; padding: 5px">BB </td><td width="100px">: </td>
    <td width="30px" style="font-size: 13px; padding: 5px">TB</td><td width="100px">: </td>
  </tr>
</table> -->

<!-- order penunjang medis -->
<hr>
<table border="0" width="100%" class="table-content">
  <tr>
    <td align="left" colspan="2"><span style="font-family: arial; font-size: 14px;"><b>ORDER PENUNJANG MEDIS</b></span></td>
  </tr>
  <tr>
    <td style="font-size: 13px; padding: 5px" colspan="2">
      <img src="<?php echo base_url().ICON_UNCHECKBOX; ?>" style="width: 18px; float: left"> 
      <span style="margin-top: -0px; padding-left: 10px"> Laboratorium</span>
    </td>
  </tr>
  <tr>
    <td width="100px" style="padding-left: 33px">Tgl tindakan</td><td>: </td>
  </tr>
  <tr>
    <td width="100px" style="padding-left: 33px">Tgl ambil hasil</td><td>: </td>
  </tr>
  <tr>
    <td width="100px" style="padding-left: 33px">Catatan</td><td>: </td>
  </tr>
  <tr>
    <td width="100px" style="padding-left: 33px" colspan="2"><br><br></td>
  </tr>
  <tr>
    <td style="font-size: 13px; padding: 5px">
      <img src="<?php echo base_url().ICON_UNCHECKBOX; ?>" style="width: 18px; float: left"> 
      <span style="margin-top: -0px; padding-left: 10px"> Radiologi</span>
    </td>
  </tr>
  <tr>
    <td width="100px" style="padding-left: 33px">Tgl periksa</td><td>: </td>
  </tr>
  <tr>
    <td width="100px" style="padding-left: 33px">Catatan</td><td>: </td>
  </tr>
  <tr>
    <td width="100px" style="padding-left: 33px" colspan="2"><br><br></td>
  </tr>
  <tr>
    <td style="font-size: 13px; padding: 5px">
      <img src="<?php echo base_url().ICON_UNCHECKBOX; ?>" style="width: 18px; float: left"> 
      <span style="margin-top: -0px; padding-left: 10px"> Fisioterapi</span>
    </td>
  </tr>
  <tr>
    <td width="100px" style="padding-left: 33px">Catatan</td><td>: </td>
  </tr>
  <tr>
    <td width="100px" style="padding-left: 33px" colspan="2"><br><br></td>
  </tr>
</table>

<!-- jadwal kontrol -->
<hr>
<table border="0" width="100%" class="table-content">
  <tr>
    <td align="left" colspan="2"><span style="font-family: arial; font-size: 14px;"><b>JADWAL KONTROL</b></span></td>
  </tr>
  <tr>
    <td width="100px" style="font-size: 13px; padding: 5px">Tgl kontrol </td><td>:</td>
  </tr>
  <tr>
    <td width="100px" style="font-size: 13px; padding: 5px">Catatan</td><td>:</td>
  </tr>
  <tr>
    <td width="100px" colspan="2"><br><br></td>
  </tr>
  
</table>

<!-- eresep -->
<!-- <div class="pagebreak"></div> -->
<hr>
<span style="font-size: 11px"><i>Silahkan potong kertas bagian ini dan berikan ke petugas farmasi</i></span>
<br>
<br>
<table border="0" width="100%" class="table-content">
  <tr>
    <td align="left" colspan="2"><span style="font-family: arial; font-size: 14px;"><b>RESEP OBAT FARMASI</b></span></td>
  </tr>
  <tr>
    <td width="100px">No. MR</td><td> <?php echo $_GET['no_mr']?></td>
  </tr>
  <tr>
    <td width="100px">Nama Pasien</td><td> <?php echo $_GET['nama']?> (<?php echo $result['registrasi']->jen_kelamin?>)</td>
  </tr>
  <tr>
    <td width="100px">&nbsp;</td>
  </tr>
  <tr>
    <td width="100px" colspan="2">Jenis Pengambilan Obat</td>
  </tr>
  <tr>
    <td>
      <img src="<?php echo base_url().ICON_UNCHECKBOX; ?>" style="width: 18px; float: left"> 
      <span style="margin-top: -0px; padding-left: 10px"> Ditunggu</span>
    </td>
    <td>
      <img src="<?php echo base_url().ICON_UNCHECKBOX; ?>" style="width: 18px; float: left"> 
      <span style="margin-top: -0px; padding-left: 10px"> Diantar</span>
    </td>
  </tr>
  <tr>
    <td width="100px" colspan="2">Alamat Pengiriman & No.Telp : </td>
  </tr>
  <tr>
    <td width="100px" colspan="2">
      <div style="border: 1px solid grey; width: 100%; height: 100px"></div><BR>
      <span style="font-size: 11px"><i>Silahkan isi alamat pengiriman jika obat akan diantar</i></span>
    </td>
  </tr>
  
</table>


<br>
<table border="0" width="100%">
<tr>
  <td valign="top">
      <i>Berikan form cheklist ini ke petugas/dokter setiap kali akan dilakukan pemeriksaan </i>
  </td>
  <!-- <td valign="bottom" style="padding-top:25px" align="right">
    Jakarta, .......................... <br><br><br><br>_________________________
  </td> -->
</tr>
</table>
</body>
</html>

