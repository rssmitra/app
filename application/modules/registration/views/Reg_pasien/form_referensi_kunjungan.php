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
<div style="padding: 20px">
  <table border="0">
  <tr>
    <td>
      <img src="<?php echo base_url().COMP_ICON; ?>" style="width:70px">
    </td>
    <td style="padding-left:30px;">
  <b>REFERENSI KUNJUNGAN PASIEN<br><?php echo strtoupper(COMP_LONG); ?></b>
  </td>
  </tr>
  </table>
  </br>
  <?php 
    if(empty($value)){
      echo "<br><br><div class='alert alert-danger'>DATA REFERENSI KUNJUNGAN TIDAK DITEMUKAN</div>";
      return;
    }
  
  ?>
  <table border="0">

  <tr>
  <td>Tgl Kunjungan</td><td>: <?php echo $this->tanggal->formatDate($value->tgl_jam_poli)?></td>
  </tr>

  <tr>
  <td>Poliklinik Spesialis</td><td>: <?php echo ucwords($value->nama_bagian)?></td>
  </tr>

  <tr>
  <td>Dokter</td><td>: <?php echo $value->nama_pegawai?></td>
  </tr>

  <tr>
  <td >Penjamin</td><td>: <?php echo $value->nama_perusahaan?></td>
  </tr>

  </table>
  
</div>




