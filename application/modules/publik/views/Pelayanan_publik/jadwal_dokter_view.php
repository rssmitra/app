<table class="table">
  <?php 
    foreach($jadwal_dokter as $key=>$row) :
      $path_img = 'uploaded/images/photo_karyawan/'.$row[0]['jd_kode_dokter'].'.png';
      if(file_exists($path_img)){
        $path_img = base_url().$path_img;
      }else{
        $path_img = base_url().'assets/img/avatar.png';
      }
  ?>
  <tr style="background: white">
      <td class="center"><img src="<?php echo $path_img; ?>" style="max-width: 150px"><br><span style="font-weight: bold;"><?php echo $key?></span></td>
  </tr>
  <tr style="background: white">
    <td>
      <small>Jadwal praktek :</small><br>
      <table class="table table-bordered">
      <tr style="background: white">
      <?php foreach($row as $val) : ?>
        <td><a href="#" onclick="getMenu('publik/Pelayanan_publik/registrasi_rj')"><?php echo $val['jd_hari'].'<br>'.$this->tanggal->formatTime($val['jd_jam_mulai']).' s.d '.$this->tanggal->formatTime($val['jd_jam_selesai']).''?></a></td>
      <?php endforeach; ?>
      </tr>
      </table>
    </td>
  </tr>
  <tr>
      <td>&nbsp;</td>
  </tr>
  <?php endforeach; ?>
</table>