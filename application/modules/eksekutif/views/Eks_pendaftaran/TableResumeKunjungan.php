
<?php 
  $count_periode = count($value['prd_dt']);
  $ttl_periode = [];
  foreach ($value['prd_dt'] as $k1 => $v1) {
    $ttl_periode[] = $v1->total;
  }
?>

<div class="row">

  <div class="col-md-3">
    <span><b>PERIODE</b>, <?php echo $this->tanggal->formatDateDmy($_GET['from_tgl'])?> s/d <?php echo $this->tanggal->formatDateDmy($_GET['to_tgl'])?></span>
    <table class="table">
      <tr>
        <td align="left">
          Total Pendaftaran<br>
          <a href="#" onclick="show_detail('periode')" style="font-size: 18px; font-weight: bold"><?php echo number_format($count_periode)?></a>
        </td>
      </tr>
      <!-- <tr>
        <td align="right">
          Total Pendapatan<br>
          <a href="#" onclick="show_detail('periode')" style="font-size: 18px; font-weight: bold"><?php echo number_format(array_sum($ttl_periode))?></a>
        </td>
      </tr> -->
    </table>
  </div>

  <?php 
    $count_day = count($value['dy_dt']);
    $ttl_day = [];
    foreach ($value['dy_dt'] as $k2 => $v2) {
      $ttl_day[] = $v2->total;
    }
  ?>
  
  <div class="col-md-3">
    <span><b>HARIAN</b>, <?php echo $this->tanggal->formatDateDmy(date('Y-m-d'))?></span>
    <table class="table">
      <tr>
        <td align="left">
          Total Pendaftaran<br>
          <a href="#" onclick="show_detail('day')" style="font-size: 18px; font-weight: bold"><?php echo number_format($count_day)?></a>
        </td>
      </tr>
      <!-- <tr>
        <td align="right">
          Total Pendapatan<br>
          <a href="#" onclick="show_detail('day')" style="font-size: 18px; font-weight: bold"><?php echo number_format(array_sum($ttl_day))?></a>
        </td>
      </tr> -->
    </table>
  </div>

  <?php 
    $count_mth = count($value['mth_dt']);
    foreach ($value['mth_dt'] as $k3 => $v3) {
      $ttl_mth[] = $v3->total;
    }
  ?>

  <div class="col-md-3">
    <span><b>BULANAN</b>, <?php echo $this->tanggal->getBulan(date('m'))?></span>
    <table class="table">
      <tr>
        <td align="left">
          Total Pendaftaran<br>
          <a href="#" onclick="show_detail('month')" style="font-size: 18px; font-weight: bold"><?php echo number_format($count_mth)?></a>
        </td>
      </tr>
      <!-- <tr>
        <td align="right">
          Total Pendapatan<br>
          <a href="#" onclick="show_detail('month')" style="font-size: 18px; font-weight: bold"><?php echo number_format(array_sum($ttl_mth))?></a>
        </td>
      </tr> -->
    </table>
  </div>

  <?php 
    $count_yr = count($value['yr_dt']);
    foreach ($value['yr_dt'] as $k3 => $v3) {
      $ttl_yr[] = $v3->total;
    }
  ?>

  <div class="col-md-3">
    <span><b>TAHUNAN</b>, <?php echo date('Y')?></span>
    <table class="table">
      <tr>
        <td align="left">
          Total Pendaftaran<br>
          <a href="#" onclick="show_detail('year')" style="font-size: 18px; font-weight: bold"><?php echo number_format($count_yr)?></a>
        </td>
      </tr>
      <!-- <tr>
        <td align="right">
          Total Pendapatan<br>
          <a href="#" onclick="show_detail('year')" style="font-size: 18px; font-weight: bold"><?php echo number_format(array_sum($ttl_yr))?></a>
        </td>
      </tr> -->
    </table>
  </div>

</div>


<div class="clearfix" style="margin-top: 20px"></div>

<div id="show_detail_by_click"></div>

