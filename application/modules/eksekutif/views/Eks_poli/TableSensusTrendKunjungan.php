<div class="row">
  <div class="col-md-8" style="overflow-x:auto;">
    <table class="table table-bordered">
      <tr>
        <th class="center" width="30px">No</th>
        <th width="250px">Periode <?php echo $this->tanggal->formatDateDmy($from_tgl).' s.d '.$this->tanggal->formatDateDmy($to_tgl)?></th>
        <th width="50px" class="center">Rawat<br>Jalan</th>
        <th width="50px" class="center">Rawat<br>Inap</th>
        <th width="50px" class="center">Gawat<br>Darurat</th>
        <th width="50px" class="center">Penunjang<br>Medis</th>
        <th width="50px" class="center">Total<br>Kunjungan</th>
      </tr>
    <tbody>
      <?php 
        $no=0;
        $arr_total_kunjungan = [];
        
        foreach($range_date as $k_dt=>$v_dt) : 
          $no++;
          $txt_rj = isset($rj[$v_dt])?$rj[$v_dt]:0;
          $arr_rj[] = $txt_rj;

          $txt_ri = isset($ri[$v_dt])?$ri[$v_dt]:0;
          $arr_ri[] = $txt_ri;

          $txt_igd = isset($igd[$v_dt])?$igd[$v_dt]:0;
          $arr_igd[] = $txt_igd;

          $txt_pm = isset($pm[$v_dt])?$pm[$v_dt]:0;
          $arr_pm[] = $txt_pm;

          $total_kunjungan = $txt_rj + $txt_ri + $txt_igd + $txt_pm;
          $arr_total_kunjungan[] = $total_kunjungan;

      ?>
      <tr>
        <td align="center"><?php echo $no?></td>
        <td><?php echo strtoupper($v_dt) ?></td>
        <td align="center"><?php echo number_format($txt_rj) ?></td>
        <td align="center"><?php echo number_format($txt_ri) ?></td>
        <td align="center"><?php echo number_format($txt_igd) ?></td>
        <td align="center"><?php echo number_format($txt_pm) ?></td>
        <td align="center"><?php echo number_format($total_kunjungan) ?></td>
      </tr>
      <?php endforeach;?>
      <tr>
        <td align="right" colspan="2">TOTAL KUNJUNGAN PASIEN</td>
        <td align="center"><b><?php echo number_format(array_sum($arr_rj))?></b></td>
        <td align="center"><b><?php echo number_format(array_sum($arr_ri))?></b></td>
        <td align="center"><b><?php echo number_format(array_sum($arr_igd))?></b></td>
        <td align="center"><b><?php echo number_format(array_sum($arr_pm))?></b></td>
        <td align="center"><b><?php echo number_format(array_sum($arr_total_kunjungan))?></b></td>
      </tr>
    </tbody>
    </table>
  </div>

  <div class="col-md-4" style="overflow-x:auto;">
    <table class="table table-bordered">
      <tr>
        <th class="center" width="30px">No</th>
        <th width="250px">Tipe Nilai</th>
        <th class="center">Rawat<br>Jalan</th>
        <th class="center">Rawat<br>Inap</th>
        <th class="center">Gawat<br>Darurat</th>
        <th class="center">Penunjang<br>Medis</th>
        <th class="center">Total<br>Kunjungan</th>
      </tr>
    <tbody>
      <tr>
        <td align="center">1</td>
        <td>SUM</td>
        <td align="center"><?php echo number_format($txt_rj) ?></td>
        <td align="center"><?php echo number_format($txt_ri) ?></td>
        <td align="center"><?php echo number_format($txt_igd) ?></td>
        <td align="center"><?php echo number_format($txt_pm) ?></td>
        <td align="center"><?php echo number_format($total_kunjungan) ?></td>
      </tr>
      <tr>
        <td align="center">2</td>
        <td>AVG</td>
        <td align="center"><?php echo number_format(array_sum($arr_rj)/count($arr_rj)) ?></td>
        <td align="center"><?php echo number_format(array_sum($arr_ri)/count($arr_ri)) ?></td>
        <td align="center"><?php echo number_format(array_sum($arr_igd)/count($arr_igd)) ?></td>
        <td align="center"><?php echo number_format(array_sum($arr_pm)/count($arr_pm)) ?></td>
        <td align="center"><?php echo number_format(array_sum($arr_total_kunjungan)/count($arr_total_kunjungan)) ?></td>
      </tr>
      <tr>
        <td align="center">3</td>
        <td>MAX</td>
        <td align="center"><?php echo number_format(MAX($arr_rj)) ?></td>
        <td align="center"><?php echo number_format(MAX($arr_ri)) ?></td>
        <td align="center"><?php echo number_format(MAX($arr_igd)) ?></td>
        <td align="center"><?php echo number_format(MAX($arr_pm)) ?></td>
        <td align="center"><?php echo number_format(MAX($arr_total_kunjungan)) ?></td>
      </tr>

      <tr>
        <td align="center">4</td>
        <td>MIN</td>
        <td align="center"><?php echo number_format(MIN($arr_rj)) ?></td>
        <td align="center"><?php echo number_format(MIN($arr_ri)) ?></td>
        <td align="center"><?php echo number_format(MIN($arr_igd)) ?></td>
        <td align="center"><?php echo number_format(MIN($arr_pm)) ?></td>
        <td align="center"><?php echo number_format(MIN($arr_total_kunjungan)) ?></td>
      </tr>

      <tr>
        <td align="center">5</td>
        <td>MEDIAN</td>
        <td align="center"><?php echo number_format($this->master->calculateMedian($arr_rj)) ?></td>
        <td align="center"><?php echo number_format($this->master->calculateMedian($arr_ri)) ?></td>
        <td align="center"><?php echo number_format($this->master->calculateMedian($arr_igd)) ?></td>
        <td align="center"><?php echo number_format($this->master->calculateMedian($arr_pm)) ?></td>
        <td align="center"><?php echo number_format($this->master->calculateMedian($arr_total_kunjungan)) ?></td>
      </tr>
    </tbody>
    </table>
  </div>
</div>

