<div class="row">
  <div class="col-md-12" style="overflow-x:auto;">
    <table class="table table-bordered">
      <tr>
        <th rowspan="3" class="center" width="30px" style="vertical-align: middle">No</th>
        <th rowspan="3" style="vertical-align: middle">Nama Dokter</th>
        <th rowspan="3" style="vertical-align: middle; width: 200px !important;">Poli/Klinik</th>
        <th colspan="2" style="vertical-align: middle" class="center">Total Bulan Lalu</th>
        <th colspan="62" style="vertical-align: middle; text-align: center !important">Total Bulan Ini</th>
        <th colspan="4" style="vertical-align: middle; text-align: center !important">Resume</th>
      </tr>
      <tr>
        <th rowspan="2" class="center">Jml Pasien</th>
        <th rowspan="2" class="center">Total Rupiah</th>
        <?php for($i=1; $i<=31; $i++) :?>
          <th colspan="2" class="center"><?php echo $i; ?></th>
        <?php endfor;?>
        <th rowspan="2" class="center">Total Pasien</th>
        <th rowspan="2" class="center">Total Rupiah</th>
      </tr>
      <tr>
      <?php for($i=1; $i<=31; $i++) :?>
          <th class="center">JP</th>
          <th class="center">RP</th>
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
            <td><?php echo number_format($total_last_month)?></td>
            <td><?php echo number_format($total_rp_last_month)?></td>
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
                <td><?php echo number_format($jp)?></td>
                <td><?php echo number_format($rp)?></td>
              <?php endfor;?>
              <td class="center"><?php $var_arr_jp_ttl = isset($arr_jp_ttl[$key2]) ? array_sum($arr_jp_ttl[$key2]) : 0; echo number_format($var_arr_jp_ttl); $arr_var_arr_jp_ttl[] = $var_arr_jp_ttl; ?></td>
              <td class="center"><?php $var_arr_rp_ttl = isset($arr_rp_ttl[$key2]) ? array_sum($arr_rp_ttl[$key2]) : 0; echo number_format($var_arr_rp_ttl); $arr_var_arr_rp_ttl[] = $var_arr_rp_ttl; ?></td>
          <?php endforeach; ?>
        </tr>
      <?php endforeach; ?>
      <tr>
        <td colspan="3" class="center"><b>TOTAL</b></td>
        <td><?php echo number_format(array_sum($arr_total_last_month))?></td>
        <td><?php echo number_format(array_sum($arr_total_rp_last_month))?></td>
        <?php for($i=1; $i<=31; $i++) :?>
          <td><?php echo isset($arr_jp[$i]) ? number_format(array_sum($arr_jp[$i])) : 0?></td>
          <td><?php echo isset($arr_rp[$i]) ? number_format(array_sum($arr_rp[$i])) : 0?></td>
        <?php endfor; ?>
        <td class="center"><?php echo number_format(array_sum($arr_var_arr_jp_ttl))?></td>
        <td><?php echo number_format(array_sum($arr_var_arr_rp_ttl))?></td>
      </tr>
    </tbody>
      
    </table>
  </div>

