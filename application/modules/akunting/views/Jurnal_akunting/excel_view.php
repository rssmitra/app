<?php 

    header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=".'export-jurnal-'.date('Ymd').".xlsx");  //File name extension was wrong
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private",false);

?>

<center>
<b>PENCATATAN JURNAL AKUNTING</b><br>
Tanggal <?php echo $this->tanggal->formatDatedmY($_GET['from_tgl'])?> s/d <?php echo $this->tanggal->formatDatedmY($_GET['to_tgl']); ?>
</center>
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
    <tr>
      <td colspan="4"><b><?php echo $jurnal[$key_jurnal][0]->acc_no_ref.'. '.$key_jurnal?></b></td>
    </tr>
    <?php foreach($row_jurnal as $row_dt_jurnal) :?>
      <tr>
        <td></td>
        <td><?php echo $row_dt_jurnal->acc_no.'. '.$row_dt_jurnal->acc_nama?></td>
        <td align="right">
          <?php echo ($row_dt_jurnal->tipe_tx == 'D') ? (int)$row_dt_jurnal->nominal : 0; ?>
        </td>
        <td align="right">
          <?php echo ($row_dt_jurnal->tipe_tx == 'K') ? (int)$row_dt_jurnal->nominal : 0; ?>
        </td>
      </tr>
    <?php
      $arr_debet[] = ($row_dt_jurnal->tipe_tx == 'D') ? (int)$row_dt_jurnal->nominal : 0;
      $arr_kredit[] = ($row_dt_jurnal->tipe_tx == 'K') ? (int)$row_dt_jurnal->nominal : 0;
      endforeach;
    ?>
  <?php endforeach; ?>
  <tr style="font-weight: bold">
    <td align="right" colspan="2">TOTAL</td>
    <td align="right"><?php echo array_sum($arr_debet)?></td>
    <td align="right"><?php echo array_sum($arr_kredit)?></td>
  </tr>
  </tbody>
</table>


