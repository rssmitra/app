<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <style type="text/css">
      body{
        margin:20px 50px;
      }
      .center{
        text-align:center;
      }
      .data{
        margin-left:25%;
      }
      footer{
        text-align:right;
      }
  </style>
</head>
<body>
  <div class="center">
      <h3>SURAT PERNYATAAN PASIEN MENINGGAL</h3><br>
  </div>
  <div class="center">
    <p> Pada hari <?php echo $value->meninggal_hari ?>, tanggal <?php echo $this->tanggal->formatDate($value->tgl_keluar).', pukul '. $jam ?>, kami :</p>
  </div>
  <div class="data">
    
      <table border="0">
        <tr>
          <td>Nama Dokter</td>
          <td>&nbsp;:&nbsp; <?php echo $value->nama_pegawai ?></td>
        </tr>
        <tr>
          <td>Bagian</td>
          <td>&nbsp;:&nbsp; <?php echo $value->nama_bagian ?></td>
        </tr>
        <tr>
          <td><?php echo COMP_FLAG; ?></td>
          <td>&nbsp;:&nbsp; <?php echo COMP_FULL; ?> </td>
        </tr>
        <tr>
          <td>Alamat</td>
          <td>&nbsp; &nbsp; <?php echo COMP_ADDRESS_SORT; ?></td>
        </tr>
        <tr>
          <td>Nama Pasien</td>
          <td>&nbsp;:&nbsp; <?php echo $value->nama_pasien ?></td>
        </tr>
        <tr>
          <td>No. Rekam Medis</td>
          <td>&nbsp;:&nbsp; <?php echo $value->no_mr ?></td>
        </tr>
        <tr>
          <td>Umur</td>
          <td>&nbsp;:&nbsp; <?php echo $umur ?></td>
        </tr>
        <tr>
          <td>Alamat Pasien</td>
          <td>&nbsp;:&nbsp; <?php echo $value->almt_ttp_pasien ?></td>
        </tr>
      </table>
    
  </div>
  <div class="center">
    <p> Menyatakan bahwa pasien tersebut diatas <b>telah meninggal</b> pada : </p>
  </div>
  <div class="data">
    
      <table border="0">
        <tr>
          <td>Hari</td>
          <td>&nbsp;:&nbsp;<?php echo $value->meninggal_hari ?></td>
        </tr>
        <tr>
          <td>Tanggal</td>
          <td>&nbsp;:&nbsp;<?php echo $tgl ?></td>
        </tr>
        <tr>
          <td>Jam</td>
          <td>&nbsp;:&nbsp;<?php echo $jam ?></td>
        </tr>
        <tr>
          <td>Instruksi</td>
          <td>&nbsp;:&nbsp;<?php echo $value->meninggal_instruksi ?></td>
        </tr>
      </table>
    
  </div>
  <div class="center">
    <p> Demikian surat keterangan ini dibuat untuk dipergunakan sebaiknya </p>
  </div>
</body>
<footer>
  <div>
    <p style="margin-right: 18px;"> Jakarta, <?php echo $this->tanggal->formatDate(date('Y-m-d')) ?> </p>
  </div>
  <div>
    <p style="margin-right: 30px;"> Yang menyatakan, </p>
  </div><br><br><br>
  <p>(------------------------)</p>
</footer>
</html>








