
<div class="row">
  <div class="col-md-3">
    <span><b>PERIODE</b>, <?php echo $this->tanggal->formatDateDmy($_GET['from_tgl'])?> s/d <?php echo $this->tanggal->formatDateDmy($_GET['to_tgl'])?></span>
    <table class="table">

        <tr>
          <td align="right">
            Medis<br>
            <span style="font-size: 14px;font-weight: bold"><?php echo number_format($value['pendapatan']['periode_medis'])?></span>
          </td>
          <td align="right">
            Non Medis<br>
            <span style="font-size: 14px;font-weight: bold"><?php echo number_format($value['pendapatan']['periode_nm'])?></span>
          </td>
        </tr>
        <tr>
          <td colspan="2" align="right">
            Total Hutang Usaha <br>
            <span style="font-size: 18px; font-weight: bold">
              <?php 
                $ttl_periode = $value['pendapatan']['periode_medis'] + $value['pendapatan']['periode_nm'];
                echo number_format($ttl_periode);
              ?>
            </span>
          </td>
        </tr>
    </table>
  </div>

  <div class="col-md-3">
    <span><b>HARIAN</b>,  <?php echo date('d/M/Y')?></span>
    <table class="table">
      <tr>
        <td align="right">
            Medis<br>
            <span style="font-size: 14px;font-weight: bold"><?php echo number_format($value['pendapatan']['day_medis'])?></span>
          </td>
          <td align="right">
            Non Medis<br>
            <span style="font-size: 14px;font-weight: bold"><?php echo number_format($value['pendapatan']['day_nm'])?></span>
          </td>
        </tr>
        <tr>
          <td colspan="2" align="right">
            Total Hutang Usaha <br>
            <span style="font-size: 20px; font-weight: bold">
              <?php 
                $ttl_day = $value['pendapatan']['day_medis'] + $value['pendapatan']['day_nm'];
                echo number_format($ttl_day);
              ?>
            </span>
          </td>
        </tr>
    </table>
  </div>

  <div class="col-md-3">
    <span><b>BULANAN, </b> <?php echo $this->tanggal->getBulan(date('m'))?></span>
    <table class="table">
      <tr>
        <td align="right">
            Medis<br>
            <span style="font-size: 14px;font-weight: bold"><?php echo number_format($value['pendapatan']['month_medis'])?></span>
          </td>
          <td align="right">
            Non Medis<br>
            <span style="font-size: 14px;font-weight: bold"><?php echo number_format($value['pendapatan']['month_nm'])?></span>
          </td>
        </tr>
        <tr>
          <td colspan="2" align="right">
            Total Hutang Usaha <br>
            <span style="font-size: 20px; font-weight: bold">
              <?php 
                $ttl_month = $value['pendapatan']['month_medis'] + $value['pendapatan']['month_nm'];
                echo number_format($ttl_month);
              ?>
            </span>
          </td>
        </tr>
    </table>
  </div>

  <div class="col-md-3">
    <span><b>TAHUNAN</b>, <?php echo date('Y')?></span>
    <table class="table">
      <tr>
        <td align="right">
            Medis<br>
            <span style="font-size: 14px;font-weight: bold"><?php echo number_format($value['pendapatan']['year_medis'])?></span>
          </td>
          <td align="right">
            Non Medis<br>
            <span style="font-size: 14px;font-weight: bold"><?php echo number_format($value['pendapatan']['year_nm'])?></span>
          </td>
        </tr>
        <tr>
          <td colspan="2" align="right">
            Total Hutang Usaha <br>
            <span style="font-size: 20px; font-weight: bold">
              <?php 
                $ttl_year = $value['pendapatan']['year_medis'] + $value['pendapatan']['year_nm'];
                echo number_format($ttl_year);
              ?>
            </span>
          </td>
        </tr>
    </table>
  </div>
</div>


<div class="clearfix" style="margin-top: 20px"></div>

<div id="show_detail_by_click"></div>

