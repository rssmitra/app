
<button type="button" name="btn-export" value="3" onclick="export_excel(3)" class="btn btn-xs btn-success">
  <i class="ace-icon fa fa-file-excel-o icon-on-right bigger-110"></i>
  Export Excel
</button>

<?php 
    $no=0; 
    foreach($data_pasien as $key=>$row) : 
      $no++;
      $bill_rs = isset($data_trans[$row['kode_tc_trans_kasir']]['bill_rs'])?$data_trans[$row['kode_tc_trans_kasir']]['bill_rs']:0;
      $arr_bill_rs[] = $bill_rs;

      $bill_dr1 = isset($data_trans[$row['kode_tc_trans_kasir']]['bill_dr1'])?$data_trans[$row['kode_tc_trans_kasir']]['bill_dr1']:0;
      $arr_bill_dr1[] = $bill_dr1;

      $bill_dr2 = isset($data_trans[$row['kode_tc_trans_kasir']]['bill_dr2'])?$data_trans[$row['kode_tc_trans_kasir']]['bill_dr2']:0;
      $arr_bill_dr2[] = $bill_dr2;

      $bill_bhp = isset($data_trans[$row['kode_tc_trans_kasir']]['bhp'])?$data_trans[$row['kode_tc_trans_kasir']]['bhp']:0;
      $arr_bill_bhp[] = $bill_bhp;

      $bill_kamar_op = isset($data_trans[$row['kode_tc_trans_kasir']]['kamar_tindakan'])?$data_trans[$row['kode_tc_trans_kasir']]['kamar_tindakan']:0;
      $arr_bill_kamar_op[] = $bill_kamar_op;

      $bill_adm = isset($data_trans[$row['kode_tc_trans_kasir']]['adm'])?$data_trans[$row['kode_tc_trans_kasir']]['adm']:0;
      $arr_bill_adm[] = $bill_adm;

      $bill_alkes = isset($data_trans[$row['kode_tc_trans_kasir']]['alkes'])?$data_trans[$row['kode_tc_trans_kasir']]['alkes']:0;
      $bill_alat_rs = isset($data_trans[$row['kode_tc_trans_kasir']]['alat_rs'])?$data_trans[$row['kode_tc_trans_kasir']]['alat_rs']:0;
      $sum_alkes = $bill_alkes + $bill_alat_rs;
      $arr_sum_alkes[] = $sum_alkes;

      $bill_kamar = isset($kamar[$row['kode_tc_trans_kasir']])?$kamar[$row['kode_tc_trans_kasir']]:0;
      $arr_bill_kamar[] = $bill_kamar;

      $bill_apotik = isset($apotik[$row['kode_tc_trans_kasir']])?$apotik[$row['kode_tc_trans_kasir']]:0;
      $arr_bill_apotik[] = $bill_apotik;

      $bill_bpako = isset($bpako[$row['kode_tc_trans_kasir']])?$bpako[$row['kode_tc_trans_kasir']]:0;
      $arr_bill_bpako[] = $bill_bpako;

      $bill_lab = isset($lab[$row['kode_tc_trans_kasir']])?$lab[$row['kode_tc_trans_kasir']]:0;
      $arr_bill_lab[] = $bill_lab;

      $bill_rad = isset($rad[$row['kode_tc_trans_kasir']])?$rad[$row['kode_tc_trans_kasir']]:0;
      $arr_bill_rad[] = $bill_rad;

      $total_profit = $bill_rs - ($bill_kamar + $bill_kamar_op + $bill_bhp + $bill_bpako + $bill_apotik + $sum_alkes + $bill_lab + $bill_rad + $bill_adm);
      $arr_total_profit[] = $total_profit;

      $total_bill = $bill_rs + $bill_dr1 + $bill_dr2;
      $arr_total_bill[] = $total_bill;

    endforeach; 
  ?>
<br>
<p style="font-weight: bold; font-style: italic; padding-top: 10px">Rekap Costing Transaksi</p>
<table class="table table-bordered table-hover">
  <tbody>
    <tr>
      <td align="right"><span style="font-size: 11px;">Jasa Dr1</span><br><h3 style="font-weight: bold; margin-top : 0px; font-size: 14px"><?php echo number_format(array_sum($arr_bill_dr1))?></h3></td>
      <td align="right"><span style="font-size: 11px;">Jasa Dr2</span><br><h3 style="font-weight: bold; margin-top : 0px; font-size: 14px"><?php echo number_format(array_sum($arr_bill_dr2))?></h3></td>
      <td align="right"><span style="font-size: 11px;">Kamar RI</span><br><h3 style="font-weight: bold; margin-top : 0px; font-size: 14px"><?php echo number_format(array_sum($arr_bill_kamar))?></h3></td>
      <td align="right"><span style="font-size: 11px;">Kamar OP</span><br><h3 style="font-weight: bold; margin-top : 0px; font-size: 14px"><?php echo number_format(array_sum($arr_bill_kamar_op))?></h3></td>
      <td align="right"><span style="font-size: 11px;">BHP</span><br><h3 style="font-weight: bold; margin-top : 0px; font-size: 14px"><?php echo number_format(array_sum($arr_bill_bhp))?></h3></td>
      <td align="right"><span style="font-size: 11px;">BPAKO</span><br><h3 style="font-weight: bold; margin-top : 0px; font-size: 14px"><?php echo number_format(array_sum($arr_bill_bpako))?></h3></td>
      <td align="right"><span style="font-size: 11px;">Farmasi</span><br><h3 style="font-weight: bold; margin-top : 0px; font-size: 14px"><?php echo number_format(array_sum($arr_bill_apotik))?></h3></td>
      <td align="right"><span style="font-size: 11px;">Alkes</span><br><h3 style="font-weight: bold; margin-top : 0px; font-size: 14px"><?php echo number_format(array_sum($arr_sum_alkes))?></h3></td>
      <td align="right"><span style="font-size: 11px;">Lab</span><br><h3 style="font-weight: bold; margin-top : 0px; font-size: 14px"><?php echo number_format(array_sum($arr_bill_lab))?></h3></td>
      <td align="right"><span style="font-size: 11px;">Rad</span><br><h3 style="font-weight: bold; margin-top : 0px; font-size: 14px"><?php echo number_format(array_sum($arr_bill_rad))?></h3></td>
      <td align="right"><span style="font-size: 11px;">Adm</span><br><h3 style="font-weight: bold; margin-top : 0px; font-size: 14px"><?php echo number_format(array_sum($arr_bill_adm))?></h3></td>
      <td align="right"><span style="font-size: 11px;">Profit RS</span><br><h3 style="font-weight: bold; margin-top : 0px; font-size: 14px"><?php echo number_format(array_sum($arr_total_profit))?></h3></td>
      <td align="right"><span style="font-size: 11px;">Total</span><br><h3 style="font-weight: bold; margin-top : 0px; font-size: 14px"><?php echo number_format(array_sum($arr_total_bill))?></h3></td>
    </tr>
  </tbody>
</table>

<p style="font-weight: bold; font-style: italic; padding-top: 10px">Rincian Detail Transaksi</p>
<table class="table table-bordered table-hover">
  <thead>
    <tr style="font-weight: bold">
      <th class="center">No</th>
      <th>Kuitansi</th>
      <th>No Registrasi</th>
      <th>Tanggal</th>
      <th width="100px">Pasien</th>
      <th width="100px">Penjamin</th>
      <th width="100px">Bagian Masuk</th>
      <th width="80px">Jasa Dr1</th>
      <th width="80px">Jasa Dr2</th>
      <th width="80px">Kamar RI</th>
      <th width="80px">Kamar OP</th>
      <th width="80px">BHP</th>
      <th width="80px">BPAKO</th>
      <th width="80px">Farmasi</th>
      <th width="80px">Alkes</th>
      <th width="80px">Lab</th>
      <th width="80px">Rad</th>
      <th width="80px">Adm</th>
      <th width="80px">Profit RS</th>
      <th width="80px">Total</th>
    </tr>
  </thead>
  <?php 
    $no=0; 
    foreach($data_pasien as $key=>$row) : 
    $no++;
    $bill_rs = isset($data_trans[$row['kode_tc_trans_kasir']]['bill_rs'])?$data_trans[$row['kode_tc_trans_kasir']]['bill_rs']:0;
    $bill_dr1 = isset($data_trans[$row['kode_tc_trans_kasir']]['bill_dr1'])?$data_trans[$row['kode_tc_trans_kasir']]['bill_dr1']:0;
    $bill_dr2 = isset($data_trans[$row['kode_tc_trans_kasir']]['bill_dr2'])?$data_trans[$row['kode_tc_trans_kasir']]['bill_dr2']:0;
    $bill_bhp = isset($data_trans[$row['kode_tc_trans_kasir']]['bhp'])?$data_trans[$row['kode_tc_trans_kasir']]['bhp']:0;
    $bill_kamar_op = isset($data_trans[$row['kode_tc_trans_kasir']]['kamar_tindakan'])?$data_trans[$row['kode_tc_trans_kasir']]['kamar_tindakan']:0;
    $bill_adm = isset($data_trans[$row['kode_tc_trans_kasir']]['adm'])?$data_trans[$row['kode_tc_trans_kasir']]['adm']:0;
    $bill_alkes = isset($data_trans[$row['kode_tc_trans_kasir']]['alkes'])?$data_trans[$row['kode_tc_trans_kasir']]['alkes']:0;
    $bill_alat_rs = isset($data_trans[$row['kode_tc_trans_kasir']]['alat_rs'])?$data_trans[$row['kode_tc_trans_kasir']]['alat_rs']:0;
    $sum_alkes = $bill_alkes + $bill_alat_rs;
    $bill_pendapatan_rs = isset($data_trans[$row['kode_tc_trans_kasir']]['pendapatan_rs'])?$data_trans[$row['kode_tc_trans_kasir']]['pendapatan_rs']:0;
    $bill_kamar = isset($kamar[$row['kode_tc_trans_kasir']])?$kamar[$row['kode_tc_trans_kasir']]:0;
    $bill_apotik = isset($apotik[$row['kode_tc_trans_kasir']])?$apotik[$row['kode_tc_trans_kasir']]:0;
    $bill_bpako = isset($bpako[$row['kode_tc_trans_kasir']])?$bpako[$row['kode_tc_trans_kasir']]:0;
    $bill_lab = isset($lab[$row['kode_tc_trans_kasir']])?$lab[$row['kode_tc_trans_kasir']]:0;
    $bill_rad = isset($rad[$row['kode_tc_trans_kasir']])?$rad[$row['kode_tc_trans_kasir']]:0;
    $total_profit = $bill_rs - ($bill_kamar + $bill_kamar_op + $bill_bhp + $bill_bpako + $bill_apotik + $sum_alkes + $bill_lab + $bill_rad + $bill_adm);
    $total_bill = $bill_rs + $bill_dr1 + $bill_dr2;
  ?>
    <tr>
      <td align="center"><?php echo $no;?></td>
      <td><?php echo $row['seri_kuitansi'].'-'.$row['no_kuitansi'];?></td>
      <td><?php echo $row['no_registrasi'];?></td>
      <td><?php echo $row['tgl_jam'];?></td>
      <td><?php echo '['.$row['no_mr'].']<br>'.$row['nama_pasien'];?></td>
      <td><?php echo $row['nama_perusahaan']; echo ($row['kode_perusahaan'] == 120) ? "<br>(".$row['no_sep'].")" : ""?></td>
      <td><?php echo $row['nama_bagian'];?></td>
      <td align="right"><?php echo number_format($bill_dr1)?></td>
      <td align="right"><?php echo number_format($bill_dr2)?></td>
      <td align="right"><?php echo number_format($bill_kamar)?></td>
      <td align="right"><?php echo number_format($bill_kamar_op)?></td>
      <td align="right"><?php echo number_format($bill_bhp)?></td>
      <td align="right"><?php echo number_format($bill_bpako)?></td>
      <td align="right"><?php echo number_format($bill_apotik)?></td>
      <td align="right"><?php echo number_format($sum_alkes)?></td>
      <td align="right"><?php echo number_format($bill_lab)?></td>
      <td align="right"><?php echo number_format($bill_rad)?></td>
      <td align="right"><?php echo number_format($bill_adm)?></td>
      <td align="right"><?php echo number_format($total_profit)?></td>
      <td align="right"><?php echo number_format($total_bill)?></td>

    </tr>
  <?php endforeach; ?>
</table>