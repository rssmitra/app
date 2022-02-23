
<div style="margin-left: 80px; width: 70% !important">
  <a href="#" class="btn btn-xs btn-primary" onclick="show_modal_medium('akunting/Jurnal_umum/form_penyesuaian/<?php echo $id_ak_tc_transaksi?>', 'FORM PENYESUAIAN JURNAL')"><i class="fa fa-pencil"></i> Sesuaikan </a>
  <label class="pull-right">
    <input name="switch-field-1" class="ace ace-switch ace-switch-5" type="checkbox" value="1" onchange="processVerif(<?php echo $id_ak_tc_transaksi; ?>)">
    <span class="lbl"></span>
  </label>

  <!-- <a href="#" class="btn btn-xs btn-success"><i class="fa fa-check"></i> Verifikasi </a> -->
  <table class="table">
    <thead>
      <tr>
        <th style="width: 100px">Kode Akun</th>
        <th>Nama Akun</th>
        <th style="width: 100px">Debit</th>
        <th style="width: 100px">Kredit</th>
      </tr>
    </thead>
    <tbody>
      <?php foreach($jurnal as $row_dt_jurnal) :?>
        <tr>
          <td><?php echo $row_dt_jurnal->acc_no?></td>
          <td><?php echo $row_dt_jurnal->acc_nama?></td>
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
    <tr style="font-weight: bold">
      <td align="right" colspan="2">TOTAL</td>
      <td align="right"><?php echo number_format(array_sum($arr_debet))?></td>
      <td align="right"><?php echo number_format(array_sum($arr_kredit))?></td>
    </tr>
    <tr>
      <td colspan="2"></td>
      <?php if(array_sum($arr_debet) == array_sum($arr_kredit)) :?>
        <td colspan="2" align="center" style="background: green; color: white">
              <i class="ace-icon fa fa-check bigger-120"></i>
              Balance
        </td>
      <?php endif; ?>

      <?php if(array_sum($arr_debet) != array_sum($arr_kredit)) :?>
        <td colspan="2" align="center" style="background: red; color: white">
              <i class="ace-icon fa fa-times-circle bigger-120"></i>
              Not Balance
        </td>
      <?php endif; ?>

    </tr>
    </tbody>
  </table>
</div>


