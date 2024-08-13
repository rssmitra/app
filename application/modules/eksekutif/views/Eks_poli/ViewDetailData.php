<?php 
  foreach ($value['result'] as $ky => $val) {
    $group_unit[$val->kode_unit][] = $val;
    // $group_poli[$val->nama_bagian][] = $val;
  }
  
?>

<div class="pull-right"><a href="#" alt="close detail" onclick="hide_detail()"><i class="fa fa-times bigger-150 red"></i> Close</a></div>
<br>
<div class="row">
  <div class="col-md-6">
    <center>KUNJUNGAN <?php echo $value['title']; ?> <br> Berdasarkan Instalasi Unit</center>
    
    <div style="height:300px; overflow:auto;">
      <table class="table">
        <tr style="background: chartreuse">
          <th>Unit/Bagian</th>
          <th class="center">Jml Pasien</th>
          <th>Total (Rp.)</th>
        </tr>
        <?php 

          foreach($group_unit as $ky1=>$row_k) {
            foreach ($row_k as $ky2 => $vl2) {
              $arr_unit[$ky1][] = isset($vl2->total)?$vl2->total:0;
            }
          }
          foreach($arr_unit as $ky3=>$vl3) : 
            if(!empty($ky3)) : 
            $arr_ttl_unit[] = array_sum($arr_unit[$ky3]);
            $arr_ttl_pasien[] = count($arr_unit[$ky3]);
            $arr_ky3[] = $ky3;
            switch ($ky3) {
              case '01':
                $nama_unit = 'Poli/Klinik Rawat Jalan';
                break;
              case '02':
                $nama_unit = 'IGD';
                break;
              case '03':
                $nama_unit = 'Rawat Inap';
                break;
              case '05':
                $nama_unit = 'Penunjang Medis';
                break;
              case '06':
                $nama_unit = 'Apotik';
                break;
            }

        ?>
        <tr>
          <td><b><?php echo $nama_unit?></b></td>
          <td align="right"><a href="#" style="font-weight: bold; color: blue" onclick="show_detail_unit('<?php echo $ky3; ?>', '<?php echo $value['flag']; ?>')"><?php echo number_format(count($arr_unit[$ky3]))?></a></td>
          <td align="right"><a href="#" style="font-weight: bold; color: blue" onclick="show_detail_unit('<?php echo $ky3; ?>', '<?php echo $value['flag']; ?>')"><?php echo number_format(array_sum($arr_unit[$ky3]))?></a></td>
        </tr>
        <?php endif; endforeach;  ?>
        <tr>
          <td align="right"><b>Total</b></td>
          <td align="right"><b><?php echo number_format(array_sum($arr_ttl_pasien))?></b></td>
          <td align="right"><b><?php echo number_format(array_sum($arr_ttl_unit))?></b></td>
        </tr>
      </table>
    </div>
      
  </div>


  <div class="col-md-6">
    <div id="show_detail_level_1"></div>
  </div>



</div>