<b>DETAIL KUNJUNGAN</b>
<table class="table table-bordered" style="width:100% !important">
  <thead>
    <tr>
      <th class="center">No</th>
      <th>No Kunjungan</th>
      <th>Tgl Kunjungan</th>
      <th width="200px">Nama Tindakan</th>
      <th>Unit/Bagian</th>
      <th>Penjamin</th>
      <th>Dokter 1</th>
      <th>Dokter 2</th>
      <th>Status</th>
      <th>Total Billing</th>
    </tr>
  </thead>
  <tbody>
    <?php 
      $arr_total = array(); 
      $no_order = 0;
      foreach($detail as $row_detail_dt) :
        $no_order++;
    ?>
      <tr>
        <td align="center"><?php echo $no_order?></td>
        <td><?php echo $row_detail_dt->no_kunjungan?></td>
        <td><?php echo $this->tanggal->formatDateTime($row_detail_dt->tgl_jam)?></td>
        <td><?php echo $row_detail_dt->nama_tindakan?></td>
        <td><?php echo $row_detail_dt->nama_bagian?></td>
        <td><?php echo $row_detail_dt->nama_perusahaan?></td>
        <td><?php echo '<b>'.number_format($row_detail_dt->bill_dr1).'</b><br><small>'.$row_detail_dt->dokter1.'</small>'?></td>
        <td ><?php echo ($row_detail_dt->dokter2 != 0)?$row_detail_dt->dokter2.'<br>'.number_format($row_detail_dt->bill_dr2) : '-'?></td>
        <td><?php echo $row_detail_dt->status_paid?></td>
        <td align="right"><?php $total = $row_detail_dt->bill_dr1 + $row_detail_dt->bill_dr2;  echo number_format($total); ?></td>
      </tr>
    <?php 
      $arr_total[] = $total; 
      endforeach;
    ?>
    <tr>
      <td colspan="9" align="right"><b>TOTAL</b></td>
      <td align="right"><b><?php echo number_format(array_sum($arr_total))?>,-</b></td>
    </tr>
  </tbody>
  
</table>

<br>
<b>TRANSAKSI KASIR</b>
<table class="table table-bordered" style="width:80% !important">
  <thead>
    <tr>
      <th class="center">No</th>
      <th>Kode</th>
      <th>Nomor Kuitansi</th>
      <th>Tanggal</th>
      <th>Tunai</th>
      <th>Debit</th>
      <th>Kredit</th>
      <th>NK Perusahaan</th>
      <th>Total Billing</th>
    </tr>
  </thead>
  <tbody>
    <?php 
      $arr_total_bill = array(); 
      $no_kasir = 0;
      foreach($result->kasir_data as $row_kasir_dt) :
        $no_kasir++;
    ?>
      <tr>
        <td align="center"><?php echo $no_kasir?></td>
        <td><?php echo $row_kasir_dt->kode_tc_trans_kasir?></td>
        <td><?php echo $row_kasir_dt->seri_kuitansi.'-'.$row_kasir_dt->no_kuitansi?></td>
        <td><?php echo $row_kasir_dt->tgl_jam?></td>
        <td align="right"><?php echo number_format($row_kasir_dt->tunai)?>,-</td>
        <td align="right"><?php echo number_format($row_kasir_dt->debet)?>,-</td>
        <td align="right"><?php echo number_format($row_kasir_dt->kredit)?>,-</td>
        <td align="right"><?php echo number_format($row_kasir_dt->nk_perusahaan)?>,-</td>
        <td align="right"><?php echo number_format($row_kasir_dt->bill)?>,-</td>
      </tr>
    <?php 
      $arr_total_bill[] = $row_kasir_dt->bill; 
      endforeach;
    ?>
    <tr>
      <td colspan="8" align="right"><b>TOTAL</b></td>
      <td align="right"><b><?php echo number_format(array_sum($arr_total_bill))?>,-</b></td>
    </tr>
  </tbody>
  
</table>
<br>
<b>PENCATATAN JURNAL AKUNTING</b>

<table class="table" style="width: 60% !important">
  <thead>
    <tr>
      <th></th>
      <th>Nama Akun</th>
      <th>Debit</th>
      <th>Kredit</th>
    </tr>
  </thead>
  <tbody>
    <?php 
      $arr_debet = array();
      $arr_kredit = array();
      foreach($jurnal as $key_jurnal=>$row_jurnal) :
    ?>
    <tr>
      <td colspan="4"><b><?php echo $jurnal[$key_jurnal][0]->acc_no_ref.'. '.$key_jurnal?></b></td>
    </tr>
    <?php foreach($row_jurnal as $row_dt_jurnal) :?>
      <tr>
        <td></td>
        <td><?php echo $row_dt_jurnal->acc_no.'. '.$row_dt_jurnal->acc_nama?></td>
        <td align="right">
          <?php echo ($row_dt_jurnal->tipe_tx == 'D') ? number_format($row_dt_jurnal->nominal) : 0; ?>
        </td>
        <td align="right">
          <?php echo ($row_dt_jurnal->tipe_tx == 'K') ? number_format($row_dt_jurnal->nominal) : 0; ?>
        </td>
      </tr>
    <?php
      $arr_debet[] = ($row_dt_jurnal->tipe_tx == 'D') ? $row_dt_jurnal->nominal : 0;
      $arr_kredit[] = ($row_dt_jurnal->tipe_tx == 'K') ? $row_dt_jurnal->nominal : 0;
      endforeach;
    ?>
  <?php endforeach; ?>
  <tr style="font-weight: bold">
    <td align="right" colspan="2">TOTAL</td>
    <td align="right"><?php echo number_format(array_sum($arr_debet))?></td>
    <td align="right"><?php echo number_format(array_sum($arr_kredit))?></td>
  </tr>
  </tbody>
</table>


