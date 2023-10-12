<?php 
  foreach ($value['result'] as $ky => $val) {
    $group_poli[$val->nama_bagian][] = $val;
  }
  
?>

<div class="row">
  <div class="col-md-12">
    <center>KUNJUNGAN <?php echo $value['title']; ?> <br> Berdasarkan Unit/Bagian</center>
    <div>
      <table class="table">
        <tr style="background: chartreuse">
          <th>Unit/Bagian</th>
          <th class="center">Jml Pasien</th>
          <th>Total (Rp.)</th>
        </tr>
        <?php 
          foreach($group_poli as $ky3=>$row_k3) :
            foreach ($row_k3 as $ky7 => $val7) :
              $arr_ttl_poli[$ky3][] = $val7->total; 
              $kode_bagian[$ky3] = $val7->kode_bagian;
        ?>
        <?php 
          endforeach; 
          $ttl_pasien_unit[] = count($arr_ttl_poli[$ky3]);
          $arr_ttl_poli_rp[] = array_sum($arr_ttl_poli[$ky3]);
        ?>
        <tr>
          <td><?php echo ucwords($ky3)?></td>
          <td align="right">
            <a href="#" onclick="show_detail_jenis_tindakan('<?php echo $kode_bagian[$ky3]; ?>', '<?php echo $value['flag']; ?>')"><?php echo number_format(count($arr_ttl_poli[$ky3]))?></a>
          </td>
          <td align="right">
            <a href="#" onclick="show_detail_jenis_tindakan('<?php echo $kode_bagian[$ky3]; ?>', '<?php echo $value['flag']; ?>')"><?php echo number_format(array_sum($arr_ttl_poli[$ky3]))?></a>
          </td>
        </tr>
        <?php endforeach; ?>
        <tr>
          <td align="right"><b>Total</b></td>
          <td align="right"><b><?php echo number_format(array_sum($ttl_pasien_unit))?></b></td>
          <td align="right"><b><?php echo number_format(array_sum($arr_ttl_poli_rp))?></b></td>
        </tr>
      </table>
    </div>
  </div>
</div>