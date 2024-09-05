<b>DATA TRANSAKSI</b>
<table class="table table-striped" style="width:100% !important">
  <thead>
    <tr>
        <th class="center" width="30px">No</th>
        <th class="right">Tanggal</th>
        <th class="right" width="150px">Keterangan</th>
        <th class="center">Jasa Dokter 1</th>
        <th class="center">Jasa Dokter 2</th>
        <th class="center">BHP</th>
        <th class="center">Alat RS</th>
        <th class="center">Kamar Tindakan</th>
        <th class="center">Administrasi</th>
        <th class="center">Pendapatan RS</th>
        <th class="center">Total (Rp)</th>
    </tr>
  </thead>
  <tbody>
    <?php 
      $nox=0;
      foreach ($trans_data as $k => $v) : $nox++;
        echo "<tr style='background: #f7f7f7; font-weight: bold'><td align='center'>".$nox."</td><td colspan='11'><b>".strtoupper($k)."</b></td></tr>";
          foreach ($v as $key => $value) : 
            if($jenis_tindakan != '') : 
              if($value->jenis_tindakan == $jenis_tindakan) :
                
                $total = $value->bill_rs + $value->bill_dr1 + $value->bill_dr2 + $value->bill_dr3;
                $arr_total[$k][] = $total;
                $arr_bill_dr1[$k][] = $value->bill_dr1;
                $arr_bill_dr2[$k][] = $value->bill_dr2;
                $arr_bhp[$k][] = $value->bhp;
                $arr_alat_rs[$k][] = $value->alat_rs;
                $arr_kamar_tindakan[$k][] = $value->kamar_tindakan;
                $arr_adm[$k][] = $value->adm;
                $arr_pendapatan_rs[$k][] = $value->pendapatan_rs;

                $pendapatan = $value->bill_rs - ($value->bhp + $value->alat_rs + $value->kamar_tindakan + $value->adm);
                
    ?>
    <tr>
      <td align="center">&nbsp;</td>
      <td><?php echo $this->tanggal->formatDateTimeToDmy($value->tgl_transaksi);?></td>
      <td><?php echo $value->nama_tindakan;?></td>
      <td align="right"><?php echo '<span style="font-weight: bold; color: black">'.number_format($value->bill_dr1).'</span><br>'.$value->nama_dokter;?></td>
      <td align="right"><?php echo '<span style="font-weight: bold; color: black">'.number_format($value->bill_dr2).'</span><br>'.$value->nama_dokter_2;?></td>
      <td align="right"><span style="font-weight: bold; color: black"><?php echo number_format($value->bhp);?></span></td>
      <td align="right"><span style="font-weight: bold; color: black"><?php echo number_format($value->alat_rs);?></span></td>
      <td align="right"><span style="font-weight: bold; color: black"><?php echo number_format($value->kamar_tindakan);?></span></td>
      <td align="right"><span style="font-weight: bold; color: black"><?php echo number_format($value->adm);?></span></td>
      <td align="right"><span style="font-weight: bold; color: black"><?php echo number_format($pendapatan);?></span></td>
      <td align="right"><span style="font-weight: bold; color: black"><?php echo number_format($total)?>,-</span></td>
    </tr> 
    <?php 
      endif; endif; endforeach; 
        $total = isset($arr_total[$k]) ? array_sum($arr_total[$k]) : 0;
        $bill_dr1 = isset($arr_bill_dr1[$k]) ? array_sum($arr_bill_dr1[$k]) : 0;
        $bill_dr2 = isset($arr_bill_dr2[$k]) ? array_sum($arr_bill_dr2[$k]) : 0;
        $bhp = isset($arr_bhp[$k]) ? array_sum($arr_bhp[$k]) : 0;
        $alat_rs = isset($arr_alat_rs[$k]) ? array_sum($arr_alat_rs[$k]) : 0;
        $kamar_tindakan = isset($arr_kamar_tindakan[$k]) ? array_sum($arr_kamar_tindakan[$k]) : 0;
        $adm = isset($arr_adm[$k]) ? array_sum($arr_adm[$k]) : 0;
        $pendapatan_rs = isset($arr_pendapatan_rs[$k]) ? array_sum($arr_pendapatan_rs[$k]) : 0;
        
        echo "<tr style='background: #f7f7f7; font-weight: bold'>";
        echo "<td colspan='3' align='right'>Total Billing ".$k."</td>";
        echo "<td align='right'>".number_format($bill_dr1)."</td>";
        echo "<td align='right'>".number_format($bill_dr2)."</td>";
        echo "<td align='right'>".number_format($bhp)."</td>";
        echo "<td align='right'>".number_format($alat_rs)."</td>";
        echo "<td align='right'>".number_format($kamar_tindakan)."</td>";
        echo "<td align='right'>".number_format($adm)."</td>";
        echo "<td align='right'>".number_format($pendapatan_rs)."</td>";
        echo "<td align='right'>".number_format($total).",-</td>";
        echo "</tr>";
        $arr_total_all[] = $total;
        endforeach; ?>
    <tr>
      <td colspan="10" align="right"><b>Total Keseluruhan</b></td>
      <td align="right"><b><?php echo number_format(array_sum($arr_total_all))?>,-</b></td>
    </tr>
  </tbody>

</table> 
<br>
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
<table class="table table-striped" style="width:100% !important">
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

<table class="table" style="width: 100% !important">
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


