<div class="row">
  <p style="text-align: center; font-size: 16px; font-weight: bold">Dashboard Antrian Pasien Online BPJS<br>tanggal <?php echo $this->tanggal->formatDate($tgl)?></p>
  <div class="col-md-4">
    <span>Data antrian berdasarkan sumber data masuk</span>
    <table class="table">
      <thead>
        <tr>
          <th>SUMBER DATA</th>
          <th>JUMLAH ANTRIAN</th>
        </tr>
      </thead>
      <?php $arr_sumberdata = []; foreach ($sumberdata as $ksd => $vsd) {
        $arr_sumberdata[] = array_sum($sumberdata[$ksd]);
        echo "<tr>";
        echo "<td>".strtoupper($ksd)."</td><td>".array_sum($sumberdata[$ksd])."</td>";
        echo "</tr>";
      }?>
      <tr>
        <td>JUMLAH ANTRIAN</td>
        <td><?php echo array_sum($arr_sumberdata)?></td>
      </tr>
    </table>
  </div>
  <div class="col-md-4">
    <span>Data antrian berdasarkan jenis poli</span>
    <table class="table">
      <thead>
        <tr>
          <th>KODE POLI</th>
          <th>JUMLAH ANTRIAN</th>
        </tr>
      </thead>
      <?php $arr_poli = [];  foreach ($poli as $kp => $v) {
        $arr_poli[] = array_sum($poli[$kp]);
        echo "<tr>";
        echo "<td>".strtoupper($kp)."</td><td>".array_sum($poli[$kp])."</td>";
        echo "</tr>";
      }?>
      <tr>
        <td>JUMLAH ANTRIAN</td>
        <td><?php echo array_sum($arr_poli)?></td>
      </tr>
    </table>
  </div>
  <div class="col-md-4">
    <span>Data antrian berdasarkan status pelayananya</span>
    <table class="table">
      <thead>
        <tr>
          <th>STATUS</th>
          <th>JUMLAH ANTRIAN</th>
        </tr>
      </thead>
      <?php $arr_status = []; foreach ($status as $ks => $vs) {
        $arr_status[] = array_sum($status[$ks]);
        echo "<tr>";
        echo "<td>".strtoupper($ks)."</td><td>".array_sum($status[$ks])."</td>";
        echo "</tr>";
      }?>
      <tr>
        <td>JUMLAH ANTRIAN</td>
        <td><?php echo array_sum($arr_status)?></td>
      </tr>
    </table>
  </div>
</div>