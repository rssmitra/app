
<?php 
  $count_periode = count($value['data_ri']);
  $getUnitTgl=[];
  $getSumArray=[];
  $getCountArray=[];
  foreach ($value['data_ri'] as $k1 => $v1) {
  $getSumArray[$k1] = [];
    foreach ($v1 as $k2 => $v2) {
      $getUnitTgl[$k1][$v2->tgl][] = $v2;
      $getSumArray[$k1][$v2->tgl][] = $v2->total;
      $getCountArray[$k1][$v2->tgl][] = $v2;
    }
  }

  foreach ($getUnitTgl as $k3 => $v3) {
    foreach ($v3 as $k4 => $v4) {
      $getResume[$k4][] = $v4;
    }
  }

  foreach ($getResume as $k5 => $v5) {
    foreach ($v5 as $k6 => $v6) {
      foreach ($v6 as $k7 => $v7) {
        $getTotalRp[$k5][] = $v7->total;
      }
    }
  }

  // echo '<pre>'; print_r($getTotalRp);die;
  // echo '<pre>'; print_r($getResume);die;
  // echo '<pre>'; print_r($value['data_ri']);die;
?>
<div class="row" style="padding-bottom: 100px">

  <!-- <span><b>PERIODE</b>, <?php echo $this->tanggal->formatDateDmy($_GET['from_tgl'])?> s/d <?php echo $this->tanggal->formatDateDmy($_GET['to_tgl'])?></span> -->
  <div class="col-md-12" style="overflow-x:auto;">
    <table class="table table-bordered">
      <tr>
        <th rowspan="3" class="center">No</th>
        <th rowspan="3" width="250px">Unit/Bagian</th>
        <th colspan="62" class="center">Tanggal Kunjungan Pasien</th>
        <th rowspan="3" class="center">Ttl Sensus</th>
        <th rowspan="3" class="right">Ttl Pendapatan (Rp)</th>
      </tr>
      <tr>
        <?php for($i=1; $i<32;$i++) :?>
        <th class="center" colspan="2"><?php echo $i?></th>
        <?php endfor;?>
      </tr>
      <tr>
      <?php for($i=1; $i<32;$i++) :?>
        <th class="center">Ss</th>
        <th class="center">Rp</th>
      <?php endfor;?>
      
      </tr>
    <tbody>
      <tr>
        <td align="center">1</td>
        <td width="250px">Rawat Inap</td>
        <?php 
          for($i=1; $i<32;$i++) :
            $jml_tgl = isset($getResume[$i]) ? count($getResume[$i]) : 0 ;
            $rp_tgl = isset($getTotalRp[$i]) ? array_sum($getTotalRp[$i]) : 0 ;
            
            $arr_jml_unit[] = $jml_tgl;
            $arr_rp_unit[] = $rp_tgl;

            $arr_jml_unit_tgl[$i][] = $jml_tgl;
            $arr_rp_unit_tgl[$i][] = $rp_tgl;
        ?>
        <td align="center" <?php echo ($jml_tgl > 0) ? '' : 'style="background: #f5c7c7"' ; ?> ><?php echo $jml_tgl?></td>
        <td align="right" <?php echo ($rp_tgl > 0) ? '' : 'style="background: #f5c7c7"' ; ?>><?php echo number_format($rp_tgl)?>,-</td>
        <?php endfor; ?>

        <td align="center">
          <?php 
            $arr_ttl_jml = array_sum($arr_jml_unit);
            echo number_format($arr_ttl_jml); ?>
          </td>
        <td align="right">
          <?php 
            $arr_ttl_rp = array_sum($arr_rp_unit);
            echo number_format($arr_ttl_rp)?>,-
        </td>
      </tr>
      <!-- <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <?php 
          for($i=1; $i<32;$i++) :
        ?>
        <td align="center"><b><?php echo isset($arr_jml_unit_tgl[$i]) ? number_format(array_sum($arr_jml_unit_tgl[$i])) : 0?></b></td>
        <td align="right"><b><?php echo isset($arr_rp_unit_tgl[$i])?number_format(array_sum($arr_rp_unit_tgl[$i])) : 0;?>,-</b></td>
      <?php endfor;?>
      <td align="center"><b><?php echo isset($arr_ttl_jml) ? number_format(array_sum($arr_ttl_jml)) : 0?></b></td>
        <td align="right"><b><?php echo isset($arr_ttl_rp) ? number_format(array_sum($arr_ttl_rp)) : 0?>,-</b></td>
      </tr> -->
    </tbody>
      
    </table>
  </div>

