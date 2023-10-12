<div class="row">
  <div class="col-md-12" style="overflow-x:auto;">
    <table class="table table-bordered">
      <tr>
        <th class="center" width="30px">No</th>
        <th width="250px">Nama Perusahaan</th>
        <th width="50px">Jumlah Kunjungan</th>
        <th class="right" width="50px">Pendapatan (Rp)</th>
      </tr>
    <tbody>
      <?php 
        $no=0;
        $arr_ttl = [];
        foreach($value['prd_dt'] as $k_dt=>$v_dt) : $no++;
        $arr_ttl[] = $v_dt['total_kunjungan'];
        $arr_ttl_biaya[] = $v_dt['total_biaya'];
      ?>
      <tr>
        <td align="center"><?php echo $no?></td>
        <td><?php echo strtoupper($k_dt) ?></td>
        <td align="center"><?php echo number_format($v_dt['total_kunjungan']) ?></td>
        <td align="right"><?php echo number_format($v_dt['total_biaya']) ?></td>
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

