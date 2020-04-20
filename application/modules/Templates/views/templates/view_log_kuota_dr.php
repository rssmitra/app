
<span style="color: blue"><b>Pemberitahuan !</b> mohon diperhatikan kuota dokter.</span>
<table class="table table-bordered table-hover" style="background-color: #a6d3f966">
  <tr>
    <td>
      Kuota Dokter <br>
      <span style="font-size: 14px; font-weight: bold; text-align: right"><?php echo $kuota?></span>
    </td>
    <td>
      Terdaftar <br>
      <span style="font-size: 14px; font-weight: bold; text-align: right"><a href="#" onclick="show_modal('Templates/References/view_pasien_terdaftar_current?kode_dokter=<?php echo $kode_dokter; ?>&kode_spesialis=<?php echo $kode_bagian; ?>', 'PASIEN TERDAFTAR HARI INI')"><?php echo $terdaftar?></a></span>
    </td>
    <td>
      Perjanjian <br>
      <span style="font-size: 14px; font-weight: bold; text-align: right"><a href="#" onclick="show_modal('Templates/References/view_pasien_perjanjian?kode_dokter=<?php echo $kode_dokter; ?>&kode_spesialis=<?php echo $kode_bagian; ?>', 'PASIEN PERJANJIAN HARI INI')"><?php echo $perjanjian_rj?></a></span>
    </td>
    <td>
      Mobile JKN <br>
      <span style="font-size: 14px; font-weight: bold; text-align: right"><?php echo $perjanjian_mjkn?></span>
    </td>
    <td>
      Mesin Antrian <br>
      <span style="font-size: 14px; font-weight: bold; text-align: right"><?php echo $antrian?></span>
    </td>
    <td>
      Sisa Kuota <br>
      <?php
        $color = ($sisa_kuota <= 0)?'red':'green';
      ?>
      <span style="font-size: 14px; font-weight: bold; text-align: right; color: <?php echo $color; ?>"><?php echo $sisa_kuota?></span>
    </td>
  </tr>

</table>