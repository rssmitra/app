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


