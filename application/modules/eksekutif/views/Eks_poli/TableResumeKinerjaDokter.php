<div class="row">
  <div class="col-md-12" style="overflow-x:auto;">
    <table class="table table-bordered">
      <tr>
        <th class="center" width="30px">No</th>
        <th width="250px">Nama Dokter</th>
        <th width="50px">Jumlah Pasien</th>
        <th class="right" width="50px">Pendapatan dr (Rp)</th>
      </tr>
    <tbody>
      <?php 
        $no=0;
        $arr_ttl = [];
        $arr_ttl_biaya = [];
        foreach($value as $k_dt=>$v_dt) : $no++;
        // echo '<pre>';print_r($v_dt);die;
        $arr_ttl[] = $v_dt['jml_kunjungan'];
        $arr_ttl_biaya[] = $v_dt['jml_billing'];
      ?>
      <tr>
        <td align="center"><?php echo $no?></td>
        <td><?php echo strtoupper($v_dt['nama_dokter']) ?></td>
        <td align="center"><?php echo number_format($v_dt['jml_kunjungan']) ?></td>
        <td align="right"><?php echo number_format($v_dt['jml_billing']) ?></td>
      </tr>
      <?php endforeach;?>
      <tr>
        <td align="right" colspan="2">TOTAL</td>
        <td align="center"><b><?php echo number_format(array_sum($arr_ttl))?></b></td>
        <td align="right"><b><?php echo number_format(array_sum($arr_ttl_biaya))?></b></td>
      </tr>
    </tbody>
      
    </table>
  </div>

