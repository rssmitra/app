
<?php 
  $count_periode = count($value['prd_dt']);
  $getUnitTgl=[];
  $getSumArray=[];
  $getCountArray=[];
  foreach ($value['prd_dt'] as $k1 => $v1) {
  $getSumArray[$k1] = [];
    foreach ($v1 as $k2 => $v2) {
      $getUnitTgl[$k1][$v2->tgl][] = $v2;
      $getSumArray[$k1][$v2->tgl][] = $v2->total;
      $getCountArray[$k1][$v2->tgl][] = $v2;
    }
  }
  // echo '<pre>'; print_r($getCountArray);die;
  // echo '<pre>'; print_r($getSumArray);die;
  // echo '<pre>'; print_r($value['prd_dt']);die;
?>
<div class="row">

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
      <?php 
        $no=0; foreach($getUnitTgl as $k_dt=>$v_dt) : $no++;

      ?>
      <tr>
        <td align="center"><?php echo $no?></td>
        <td width="250px"><?php echo ucwords($k_dt) ?></td>
        <?php 
          for($i=1; $i<32;$i++) :
            $jml_tgl = isset($getCountArray[$k_dt][$i]) ? count($getCountArray[$k_dt][$i]) : 0 ;
            $rp_tgl = isset($getSumArray[$k_dt][$i]) ? array_sum($getSumArray[$k_dt][$i]) : 0 ;
            
            $arr_jml_unit[$k_dt][] = $jml_tgl;
            $arr_rp_unit[$k_dt][] = $rp_tgl;

            $arr_jml_unit_tgl[$i][] = $jml_tgl;
            $arr_rp_unit_tgl[$i][] = $rp_tgl;
        ?>
        <td align="center"><?php echo $jml_tgl?></td>
        <td align="right"><?php echo number_format($rp_tgl)?>,-</td>
      <?php endfor; ?>
        <td align="center">
          <?php 
            $arr_ttl_jml[] = array_sum($arr_jml_unit[$k_dt]);
            echo array_sum($arr_jml_unit[$k_dt])?>
          </td>
        <td align="right">
          <?php 
            $arr_ttl_rp[] = array_sum($arr_rp_unit[$k_dt]);
            echo number_format(array_sum($arr_rp_unit[$k_dt]))?>,-
        </td>
      </tr>
      <?php endforeach;?>
      <tr>
        <td>&nbsp;</td>
        <td>&nbsp;</td>
        <?php 
          for($i=1; $i<32;$i++) :
        ?>
        <td align="center"><b><?php echo isset($arr_jml_unit_tgl[$i]) ? array_sum($arr_jml_unit_tgl[$i]) : 0?></b></td>
        <td align="right"><b><?php echo isset($arr_rp_unit_tgl[$i])?number_format(array_sum($arr_rp_unit_tgl[$i])) : 0;?>,-</b></td>
      <?php endfor;?>
      <td align="center"><b><?php echo isset($arr_ttl_jml) ? array_sum($arr_ttl_jml) : 0?></b></td>
        <td align="right"><b><?php echo isset($arr_ttl_rp) ? number_format(array_sum($arr_ttl_rp)) : 0?>,-</b></td>
      </tr>
    </tbody>
      
    </table>
  </div>

