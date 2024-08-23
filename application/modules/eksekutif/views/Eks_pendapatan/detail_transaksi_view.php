<?php
  $resume_billing = array();
  if(count($result) > 0 ) :
  foreach ($result->group as $k => $val) {
      foreach ($val as $value_data) {
          $subtotal = (double)$value_data->bill_rs + (double)$value_data->bill_dr1 + (double)$value_data->bill_dr2 + (double)$value_data->lain_lain;
          /*total*/
          $sum_subtotal[] = $subtotal;
          /*resume billing*/
          $resume_billing[] = $this->Csm_billing_pasien->resumeBillingRJ($value_data->jenis_tindakan, $value_data->kode_bagian, $subtotal);
      }        
  }
?>
<b>RESUME BILLING</b>
<table class="table table-striped" style="width:80% !important">
  <thead>
    <tr>
        <th class="right">Dokter</th>
        <th class="right">Administrasi</th>
        <th class="right">Obat/Farmasi</th>
        <th class="right">Penunjang Medis</th>
        <th class="right">Tindakan</th>
        <th class="right">BPAKO</th>
        <th class="right">Total</th>
    </tr>
  </thead>
  <?php 
    $split_billing = $this->Csm_billing_pasien->splitResumeBilling($resume_billing);
    $total_billing = (double)$split_billing['bill_dr'] + (double)$split_billing['bill_adm_rs'] + (double)$split_billing['bill_farm'] + (double)$split_billing['bill_pm'] + (double)$split_billing['bill_tindakan']+ (double)$split_billing['bill_bpako']
  ?>
  <tbody>
    <tr>
        <td align="left">Rp. <?php echo number_format($split_billing['bill_dr'])?>,-</td>
        <td align="left">Rp. <?php echo number_format($split_billing['bill_adm_rs'])?>,-</td>
        <td align="left">Rp. <?php echo number_format($split_billing['bill_farm'])?>,-</td>
        <td align="left">Rp. <?php echo number_format($split_billing['bill_pm'])?>,-</td>
        <td align="left">Rp. <?php echo number_format($split_billing['bill_tindakan'])?>,-</td>
        <td align="left">Rp. <?php echo number_format($split_billing['bill_bpako'])?>,-</td>
        <td align="left"><b>Rp. <?php echo number_format($total_billing)?>,-</b></td>
    </tr> 
  </tbody>

</table> 
<?php endif; ?>
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
    <!-- <tr>
      <td colspan="4"><b><?php echo $jurnal[$key_jurnal][0]->acc_no_ref.'. '.$key_jurnal?></b></td>
    </tr> -->
    <?php foreach($row_jurnal as $row_dt_jurnal) :?>
      <tr>
        <td></td>
        <td><?php echo '<b>'.$row_dt_jurnal->acc_no.'</b>. '.$row_dt_jurnal->acc_nama?></td>
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


