<?php 
  header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
  header("Content-Disposition: attachment; filename=".'lhk_exp_date_type_3_'.date('Ymd').".xls");  //File name extension was wrong
  header("Expires: 0");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("Cache-Control: private",false);
?>

<p style="font-weight: bold; font-style: italic; padding-top: 10px">Rincian Detail Transaksi</p>
<table class="table table-bordered table-hover">
  <thead>
    <tr style="font-weight: bold">
      <th class="center">No</th>
      <th>Tipe</th>
      <th>Kuitansi</th>
      <th>No Registrasi</th>
      <th>Tgl Submit</th>
      <th>Tgl Masuk</th>
      <th>Tgl Keluar</th>
      <th width="100px">No MR</th>
      <th width="100px">Pasien</th>
      <th width="100px">Penjamin</th>
      <th width="100px">No SEP</th>
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
      <td><?php echo $row['seri_kuitansi'];?></td>
      <td><?php echo $row['no_kuitansi'];?></td>
      <td><?php echo $row['no_registrasi'];?></td>
      <td><?php echo $row['tgl_jam'];?></td>
      <td><?php echo $row['tgl_masuk'];?></td>
      <td><?php echo $row['tgl_keluar'];?></td>
      <td><?php echo $row['no_mr'];?></td>
      <td><?php echo $row['nama_pasien'];?></td>
      <td><?php echo $row['nama_perusahaan'];?></td>
      <td><?php echo $row['no_sep']; ?></td>
      <td><?php echo $row['nama_bagian'];?></td>
      <td align="right"><?php echo $bill_dr1?></td>
      <td align="right"><?php echo $bill_dr2?></td>
      <td align="right"><?php echo $bill_kamar?></td>
      <td align="right"><?php echo $bill_kamar_op?></td>
      <td align="right"><?php echo $bill_bhp?></td>
      <td align="right"><?php echo $bill_bpako?></td>
      <td align="right"><?php echo $bill_apotik?></td>
      <td align="right"><?php echo $sum_alkes?></td>
      <td align="right"><?php echo $bill_lab?></td>
      <td align="right"><?php echo $bill_rad?></td>
      <td align="right"><?php echo $bill_adm?></td>
      <td align="right"><?php echo $total_profit?></td>
      <td align="right"><?php echo $total_bill?></td>

    </tr>
  <?php endforeach; ?>
</table>