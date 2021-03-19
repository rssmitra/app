
<div class="row">
  <div class="col-md-3">
    <span><b>PERIODE</b>, <?php echo $this->tanggal->formatDateDmy($_GET['from_tgl'])?> s/d <?php echo $this->tanggal->formatDateDmy($_GET['to_tgl'])?></span>
    <table class="table">
      <tr>
        <td align="left">
          Total Kunjungan<br>
          <a href="#" onclick="show_detail('periode')" style="font-size: 18px; font-weight: bold"><?php echo number_format($value['kunjungan']['periode'])?></a>
        </td>
      </tr>
      <tr>
        <td align="right">
          Total Pendapatan<br>
          <a href="#" onclick="show_detail('periode')" style="font-size: 18px; font-weight: bold"><?php echo number_format($value['pendapatan']['periode'])?></a>
        </td>
      </tr>
    </table>
  </div>

  <div class="col-md-3">
    <span><b>HARIAN</b>,  <?php echo date('d/M/Y')?></span>
    <table class="table">
      <tbody style="background-color: #ece6e6">
    <tr>
        <td align="left">
          Total Kunjungan<br>
          <a href="#" onclick="show_detail('day')" style="font-size: 18px; font-weight: bold"><?php echo number_format($value['kunjungan']['day'])?></a>
        </td>
      </tr>
      <tr>
        <td align="right">
          Total Pendapatan<br>
          <a href="#" onclick="show_detail('day')" style="font-size: 18px; font-weight: bold"><?php echo number_format($value['pendapatan']['day'])?></a>
        </td>
      </tr>
      </tbody>
    </table>
  </div>

  <div class="col-md-3">
    <span><b>BULANAN, </b> <?php echo $this->tanggal->getBulan(date('m'))?></span>
    <table class="table">
      <tbody style="background-color: #ece6e6">
    <tr>
        <td align="left">
          Total Kunjungan<br>
          <a href="#" onclick="show_detail('month')" style="font-size: 18px; font-weight: bold"><?php echo number_format($value['kunjungan']['month'])?></a>
        </td>
      </tr>
      <tr>
        <td align="right">
          Total Pendapatan<br>
          <a href="#" onclick="show_detail('month')" style="font-size: 18px; font-weight: bold"><?php echo number_format($value['pendapatan']['month'])?></a>
        </td>
      </tr>
      </tbody>
    </table>
  </div>

  <div class="col-md-3">
    <span><b>TAHUNAN</b>, <?php echo date('Y')?></span>
    <table class="table">
      <tbody style="background-color: #ece6e6">
      <tr>
        <td align="left">
          Total Kunjungan<br>
          <a href="#" onclick="show_detail('year')" style="font-size: 18px; font-weight: bold"><?php echo number_format($value['kunjungan']['year'])?></a>
        </td>
      </tr>
      <tr>
        <td align="right">
          Total Pendapatan<br>
          <a href="#" onclick="show_detail('year')" style="font-size: 18px; font-weight: bold"><?php echo number_format($value['pendapatan']['year'])?></a>
        </td>
      </tr>
      </tbody>
    </table>
  </div>
</div>


<div class="clearfix" style="margin-top: 20px"></div>

<div id="show_detail_by_click"></div>

