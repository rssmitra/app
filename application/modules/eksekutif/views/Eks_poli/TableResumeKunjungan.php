
<?php 
  $count_periode = count($value['prd_dt']);
  $ttl_periode = [];
  foreach ($value['prd_dt'] as $k1 => $v1) {
    $ttl_periode[] = $v1->total;
  }
?>

<div class="row">
  <div class="col-md-12">

    <table class="table">
      <tr>
        <td rowspan="4" align="center" valign="middle">
        <span style="font-size: 18px"><b>PERIODE</b>, <?php echo $this->tanggal->formatDateDmy($_GET['from_tgl'])?> s/d <?php echo $this->tanggal->formatDateDmy($_GET['to_tgl'])?></span><br><br>
          <div class="col-md-6">
            Total Kunjungan<br>
            <a href="#" onclick="show_detail('periode')" style="font-size: 24px; font-weight: bold"><?php echo number_format($count_periode)?></a>
          </div>
          <div class="col-md-6">
            Total Pendapatan<br>
            <a href="#" onclick="show_detail('periode')" style="font-size: 24px; font-weight: bold"><?php echo number_format(array_sum($ttl_periode))?></a>
          </div>
        </td>
      </tr>
      <tr>
        <td align="center">
          <span><b>HARIAN</b>, <?php echo $this->tanggal->formatDateDmy(date('Y-m-d'))?></span><br>
          <?php 
            $count_day = count($value['dy_dt']);
            $ttl_day = [];
            foreach ($value['dy_dt'] as $k2 => $v2) {
              $ttl_day[] = $v2->total;
            }
          ?>
          <div class="col-md-6">
            Total Kunjungan<br>
            <a href="#" onclick="show_detail('day')" style="font-size: 16px; font-weight: bold"><?php echo number_format($count_day)?></a>
          </div>
          <div class="col-md-6">
          Total Pendapatan<br>
            <a href="#" onclick="show_detail('day')" style="font-size: 16px; font-weight: bold"><?php echo number_format(array_sum($ttl_day))?></a>
          </div>
        </td>
      </tr>
      <tr>
        <td align="center">
          <span><b>BULANAN</b>, <?php echo $this->tanggal->getBulan(date('m'))?></span><br>
          <?php 
            $count_mth = count($value['mth_dt']);
            $ttl_mth = [];
            foreach ($value['mth_dt'] as $k3 => $v3) {
              $ttl_mth[] = $v3->total;
            }
          ?>
          <div class="col-md-6">
            Total Kunjungan<br>
            <a href="#" onclick="show_detail('month')" style="font-size: 16px; font-weight: bold"><?php echo number_format($count_mth)?></a>
          </div>
          <div class="col-md-6">
          Total Pendapatan<br>
            <a href="#" onclick="show_detail('month')" style="font-size: 16px; font-weight: bold"><?php echo number_format(array_sum($ttl_mth))?></a>
          </div>
        </td>
      </tr>
      <tr>
        <td align="center">
          <span><b>TAHUNAN</b>, <?php echo date('Y')?></span><br>
          <div class="col-md-6">
            Total Kunjungan<br>
            <a href="#" onclick="show_detail('year')" style="font-size: 16px; font-weight: bold"><?php echo number_format($value['yr_dt']->ttl_pasien)?></a>
          </div>
          <div class="col-md-6">
          Total Pendapatan<br>
            <a href="#" onclick="show_detail('year')" style="font-size: 16px; font-weight: bold"><?php echo number_format($value['yr_dt']->total_rp)?></a>
          </div>
        </td>
      </tr>
    </table>

  </div>
</div>


<div class="clearfix" style="margin-top: 20px"></div>

<div id="show_detail_by_click"></div>

