<table class="table">
  <?php foreach($jadwal_dokter as $key=>$row) :?>
  <tr style="background: white">
      <td class="center"><img src="<?php echo base_url().'assets/img/avatar.png'?>" style="max-width: 150px"><br><span style="font-weight: bold;"><?php echo $key?></span></td>
  </tr>
  <tr style="background: white">
    <td>
      <small>Jadwal praktek :</small><br>
      <table class="table table-bordered">
      <tr style="background: white">
      <?php foreach($row as $val) : ?>
        <td><?php echo $val['jd_hari'].'<br>'.$this->tanggal->formatTime($val['jd_jam_mulai']).' s.d '.$this->tanggal->formatTime($val['jd_jam_selesai']).''?></td>
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