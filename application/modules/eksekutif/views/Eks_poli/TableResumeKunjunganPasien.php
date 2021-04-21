<?php 
  $count_periode = count($value['prd_dt']);
  $getUnitTgl=[];
  $getSumArray=[];
  $getCountArray=[];
  foreach ($value['prd_dt'] as $k1 => $v1) {
  $getSumArray[$k1] = count($v1);
    foreach ($v1 as $k2 => $v2) {
      $getSumArrayRp[$k1][] = $v2->total;
    }
  }

  // total
  $arr_ttl = []; 
  foreach ($getSumArray as $ks => $vs) {
    $arr_ttl[] = $vs;
  }

  // echo '<pre>'; print_r($arr_ttl);die;
  // echo '<pre>'; print_r($getSumArrayRp);die;
  // echo '<pre>'; print_r($value['prd_dt']);die;
?>

<div class="row">
  Jumlah Pasien <?php echo array_sum($arr_ttl)?>
  <div class="col-md-12" style="overflow-x:auto;">
    <table class="table table-bordered">
      <tr>
        <th class="center" width="30px">No</th>
        <th width="250px">Unit/Bagian/Nama Pasien</th>
        <th class="center" width="100px">Tanggal Kunjungan</th>
        <th class="right" width="100px">Pendapatan (Rp)</th>
      </tr>
    <tbody>
      <?php 
        foreach($value['prd_dt'] as $k_dt=>$v_dt) :
      ?>
      <tr>
        <td align="center"></td>
        <td colspan="3"><b><?php echo strtoupper($k_dt) ?></b></td>
      </tr>
      <?php 
        $no=0; foreach($v_dt as $r_vdt) : $no++; 
        $getSumArray[$k_dt] = count($r_vdt);

      ?>
      <tr>
        <td align="center"><?php echo $no?></td>
        <td><?php echo $r_vdt->no_mr.' - '.$r_vdt->nama_pasien?></td>
        <td class="center"><?php echo $this->tanggal->formatDateTime($r_vdt->tgl_masuk)?></td>
        <td align="right"><?php echo number_format($r_vdt->total)?></td>
      </tr>
      <?php endforeach;?>
      <?php endforeach;?>
    </tbody>
      
    </table>
  </div>

