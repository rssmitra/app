<?php 
  $arr_total_last_month = [];
  $arr_total_rp_last_month = [];
  $arr_jp = [];
  $arr_rp = [];
  $no=0; foreach($value as $key=>$row_dt) : $no++; ?>
    <?php 
    
      foreach ($row_dt as $key2 => $row_dt2) : 
        reset($row_dt2);
        $first_key = key($row_dt2);
        $total_last_month = isset($row_dt2[$first_key]['total_last_month'])?$row_dt2[$first_key]['total_last_month']:0;
        $total_rp_last_month = isset($row_dt2[$first_key]['total_rp_last_month'])?$row_dt2[$first_key]['total_rp_last_month']:0;
        $arr_total_last_month[] = isset($row_dt2[$first_key]['total_last_month'])?$row_dt2[$first_key]['total_last_month']:0;
        $arr_total_rp_last_month[] = isset($row_dt2[$first_key]['total_rp_last_month'])?$row_dt2[$first_key]['total_rp_last_month']:0;
    ?>
      <?php 
      $arr_jp_ttl[$key2] = [];
      $arr_rp_ttl[$key2] = [];
        for($i=1; $i<=31; $i++) :
          $jp = isset($row_dt2[$i])? $row_dt2[$i]['total'] : 0;
          $rp = isset($row_dt2[$i])? $row_dt2[$i]['total_rp'] : 0;
          $arr_jp[$i][] = isset($row_dt2[$i])? $row_dt2[$i]['total'] : 0;
          $arr_rp[$i][] = isset($row_dt2[$i])? $row_dt2[$i]['total_rp'] : 0;

          $arr_jp_ttl[$key2][] = isset($row_dt2[$i])? $row_dt2[$i]['total'] : 0;
          $arr_rp_ttl[$key2][] = isset($row_dt2[$i])? $row_dt2[$i]['total_rp'] : 0;

      ?>
        <?php endfor;?>
    <?php endforeach; ?>
<?php endforeach; ?>

<div class="row">
  <div class="col-md-12" style="overflow-x:auto;">
    <table class="table table-bordered">
      <tr>
        <th rowspan="3" class="center" width="30px" style="vertical-align: middle">No</th>
        <th rowspan="3" style="vertical-align: middle">Nama Dokter</th>
        <th rowspan="3" style="vertical-align: middle; width: 200px !important;">Poli/Klinik</th>
        <th colspan="2" style="vertical-align: middle" class="center"><?php echo $this->tanggal->getBulan($_GET['bulan']-1)?></th>
        <?php 
          if(isset($_GET['jml_pasien']) AND isset($_GET['jml_rp'])){
            $colspan2 = 2;
            $colspan62 = 62;
          }
          ?>
        <th colspan="2" style="vertical-align: middle; text-align: center !important"><?php echo $this->tanggal->getBulan($_GET['bulan'])?></th>
        <th <?php echo isset($colspan62)?'colspan="62"':'colspan="31"'?> style="vertical-align: middle; text-align: center !important">Rekapitulasi Detail Harian Bulan <?php echo $this->tanggal->getBulan($_GET['bulan'])?> Tahun <?php echo $_GET['tahun']?></th>
      </tr>
      <tr>
        <th rowspan="2" class="center">Total Pasien</th>
        <th rowspan="2" class="center">Total Rupiah</th>
        <th rowspan="2" class="center">Total Pasien</th>
        <th rowspan="2" class="center">Total Rupiah</th>
        <?php 
          for($i=1; $i<=31; $i++) :
        ?>
          <th <?php echo isset($colspan2)?'colspan="2"':''?> class="center"><?php echo $i; ?></th>
        <?php endfor;?>
        
      </tr>
      <tr>
      <?php for($i=1; $i<=31; $i++) :?>
          <?php if(isset($_GET['jml_pasien'])) :?>
          <th class="center">TP</th>
          <?php endif; ?>
          <?php if(isset($_GET['jml_rp'])) :?>
          <th class="center">RP</th>
          <?php endif; ?>
        <?php endfor;?>
      </tr>

    <tbody>
      <?php 
        $arr_total_last_month = [];
        $arr_total_rp_last_month = [];
        $arr_jp = [];
        $arr_rp = [];
        $no=0; foreach($value as $key=>$row_dt) : $no++; ?>
        <tr>
          <td><?php echo $no?></td>
          <td><?php echo $key; ?></td>
          <?php 
            foreach ($row_dt as $key2 => $row_dt2) : 
              reset($row_dt2);
              $first_key = key($row_dt2);
              $total_last_month = isset($row_dt2[$first_key]['total_last_month'])?$row_dt2[$first_key]['total_last_month']:0;
              $total_rp_last_month = isset($row_dt2[$first_key]['total_rp_last_month'])?$row_dt2[$first_key]['total_rp_last_month']:0;
              $arr_total_last_month[] = isset($row_dt2[$first_key]['total_last_month'])?$row_dt2[$first_key]['total_last_month']:0;
              $arr_total_rp_last_month[] = isset($row_dt2[$first_key]['total_rp_last_month'])?$row_dt2[$first_key]['total_rp_last_month']:0;
          ?>
            <td><?php echo ucwords($key2); ?></td>
            <td class="center"><?php echo number_format($total_last_month)?></td>
            <td><?php echo number_format($total_rp_last_month)?></td>
            <td class="center">
                <?php 
                  $var_arr_jp_ttl = isset($arr_jp_ttl[$key2]) ? array_sum($arr_jp_ttl[$key2]) : 0; 
                  echo number_format($var_arr_jp_ttl); 
                  echo '<br>'.$this->master->stats_between_value($var_arr_jp_ttl, $total_last_month);
                  $arr_var_arr_jp_ttl[] = $var_arr_jp_ttl; 
                ?>
            </td>
            <td class="center">
                <?php 
                  $var_arr_rp_ttl = isset($arr_rp_ttl[$key2]) ? array_sum($arr_rp_ttl[$key2]) : 0; 
                  echo number_format($var_arr_rp_ttl); 
                  echo '<br>'.$this->master->stats_between_value($var_arr_rp_ttl, $total_rp_last_month);
                  $arr_var_arr_rp_ttl[] = $var_arr_rp_ttl; 
                ?>
            </td>
            <?php 
            $arr_jp_ttl[$key2] = [];
            $arr_rp_ttl[$key2] = [];
              for($i=1; $i<=31; $i++) :
                $jp = isset($row_dt2[$i])? $row_dt2[$i]['total'] : 0;
                $rp = isset($row_dt2[$i])? $row_dt2[$i]['total_rp'] : 0;
                $arr_jp[$i][] = isset($row_dt2[$i])? $row_dt2[$i]['total'] : 0;
                $arr_rp[$i][] = isset($row_dt2[$i])? $row_dt2[$i]['total_rp'] : 0;

                $arr_jp_ttl[$key2][] = isset($row_dt2[$i])? $row_dt2[$i]['total'] : 0;
                $arr_rp_ttl[$key2][] = isset($row_dt2[$i])? $row_dt2[$i]['total_rp'] : 0;

            ?>
                <?php if(isset($_GET['jml_pasien'])) :?>
                <td><?php echo number_format($jp)?></td>
                <?php endif; ?>
                <?php if(isset($_GET['jml_rp'])) :?>
                <td><?php echo number_format($rp)?></td>
                <?php endif; ?>
              <?php endfor;?>
              
          <?php endforeach; ?>
        </tr>
      <?php endforeach; ?>
      <tr>
        <td colspan="3" class="center"><b>TOTAL</b></td>
        <td><?php echo number_format(array_sum($arr_total_last_month))?></td>
        <td><?php echo number_format(array_sum($arr_total_rp_last_month))?></td>
        <?php for($i=1; $i<=31; $i++) :?>
          <?php if(isset($_GET['jml_pasien'])) :?>
          <td><?php echo isset($arr_jp[$i]) ? number_format(array_sum($arr_jp[$i])) : 0?></td>
          <?php endif;?>
          <?php if(isset($_GET['jml_rp'])) :?>
          <td><?php echo isset($arr_rp[$i]) ? number_format(array_sum($arr_rp[$i])) : 0?></td>
          <?php endif;?>
        <?php endfor; ?>
        <td class="center"><?php echo number_format(array_sum($arr_var_arr_jp_ttl))?></td>
        <td><?php echo number_format(array_sum($arr_var_arr_rp_ttl))?></td>
      </tr>
    </tbody>
      
    </table>
  </div>

