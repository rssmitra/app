<?php 

  if($export == 'excel'){
    $filename = 'Export_Data_Kinerja_Dokter';
    header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=".$filename.'_'.date('Ymd').".xls");  //File name extension was wrong
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private",false);
  }
  
  $arr_total_last_month = [];
  $arr_total_rp_last_month = [];
  $arr_jp = [];
  $arr_rp = [];
  
  $no=0; 
  foreach($value as $key=>$row_dt) : $no++; 
      $arr_jp_ttl[$key] = [];
      $arr_rp_ttl[$key] = [];
      reset($row_dt);
      $first_key = key($row_dt);
      for($i=1; $i<=31; $i++) :
        $jp = isset($row_dt[$i])? $row_dt[$i]['total'] : 0;
        $rp = isset($row_dt[$i])? $row_dt[$i]['total_rp'] : 0;
        $arr_jp[$i][] = isset($row_dt[$i])? $row_dt[$i]['total'] : 0;
        $arr_rp[$i][] = isset($row_dt[$i])? $row_dt[$i]['total_rp'] : 0;
        $arr_jp_ttl[$key][] = isset($row_dt[$i])? $row_dt[$i]['total'] : 0;
        $arr_rp_ttl[$key][] = isset($row_dt[$i])? $row_dt[$i]['total_rp'] : 0;
      endfor;
      $arr_total_last_month[$key] = isset($row_dt[$first_key])? $row_dt[$first_key]['total_last_month'] : 0; 
      $arr_total_rp_last_month[$key] = isset($row_dt[$first_key])?$row_dt[$first_key]['total_rp_last_month']:0;

        
  endforeach; 
  // echo '<pre>';print_r($first);die;
  // echo '<pre>';print_r($arr_total_last_month);die;
?>

<div class="row">
  <div class="col-md-12" style="overflow-x:auto;">
  <table class="table table-bordered">
    <tr>
        <td colspan="50" align="left">Laporan Kinerja Dokter</td>
      </tr>
  </table>
  
    <table class="table table-bordered" border="1">
      
      <tr>
        <th rowspan="3" class="center" width="30px" style="vertical-align: middle">No</th>
        <th rowspan="3" style="vertical-align: middle; width: 200px !important;">Keterangan</th>
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
        $arr_jp = [];
        $arr_rp = [];
        $no=0; foreach($value as $key=>$row_dt) : $no++; 
        
        $ttl_arr_total_last_month[] = isset($arr_total_last_month[$key]) ? $arr_total_last_month[$key] : 0; 
        $ttl_arr_total_rp_last_month[] = isset($arr_total_rp_last_month[$key])?$arr_total_rp_last_month[$key]:0;

        ?>
        <tr>
          <td><?php echo $no?></td>
          
            <td><?php echo ucwords($key); ?></td>

            <td class="center"><?php echo $arr_total_last_month[$key]?></td>
            <td><?php echo $arr_total_rp_last_month[$key]?></td>
            <td class="center">
                <?php 
                  $var_arr_jp_ttl = isset($arr_jp_ttl[$key]) ? array_sum($arr_jp_ttl[$key]) : 0; 
                  echo $var_arr_jp_ttl; 
                  $arr_var_arr_jp_ttl[] = $var_arr_jp_ttl; 
                ?>
            </td>
            <td class="center">
                <?php 
                  $var_arr_rp_ttl = isset($arr_rp_ttl[$key]) ? array_sum($arr_rp_ttl[$key]) : 0; 
                  echo $var_arr_rp_ttl; 
                  $arr_var_arr_rp_ttl[] = $var_arr_rp_ttl; 
                ?>
            </td>
            <?php 
            $arr_jp_ttl[$key] = [];
            $arr_rp_ttl[$key] = [];
              for($i=1; $i<=31; $i++) :
                $jp = isset($row_dt[$i])? $row_dt[$i]['total'] : 0;
                $rp = isset($row_dt[$i])? $row_dt[$i]['total_rp'] : 0;
                $arr_jp[$i][] = isset($row_dt[$i])? $row_dt[$i]['total'] : 0;
                $arr_rp[$i][] = isset($row_dt[$i])? $row_dt[$i]['total_rp'] : 0;

                $arr_jp_ttl[$key][] = isset($row_dt[$i])? $row_dt[$i]['total'] : 0;
                $arr_rp_ttl[$key][] = isset($row_dt[$i])? $row_dt[$i]['total_rp'] : 0;

            ?>
                <?php if(isset($_GET['jml_pasien'])) :?>
                <td align="center"><?php echo $jp?></td>
                <?php endif; ?>
                <?php if(isset($_GET['jml_rp'])) :?>
                <td><?php echo $rp?></td>
                <?php endif; ?>
              <?php endfor;?>
        </tr>
      <?php endforeach; ?>
      <tr>
        <td colspan="2" class="center"><b>TOTAL</b></td>
        <td><?php echo array_sum($ttl_arr_total_last_month)?></td>
        <td><?php echo array_sum($ttl_arr_total_rp_last_month)?></td>
        <td class="center">
            <?php 
              echo array_sum($arr_var_arr_jp_ttl);
            ?>

        </td>
        <td class="center">
          <?php 
            echo array_sum($arr_var_arr_rp_ttl);
          ?>

        </td>
        <?php for($i=1; $i<=31; $i++) :?>
          <?php if(isset($_GET['jml_pasien'])) :?>
          <td><?php echo isset($arr_jp[$i]) ? array_sum($arr_jp[$i]) : 0?></td>
          <?php endif;?>
          <?php if(isset($_GET['jml_rp'])) :?>
          <td><?php echo isset($arr_rp[$i]) ? array_sum($arr_rp[$i]) : 0?></td>
          <?php endif;?>
        <?php endfor; ?>
        
      </tr>
    </tbody>
      
    </table>
  </div>

