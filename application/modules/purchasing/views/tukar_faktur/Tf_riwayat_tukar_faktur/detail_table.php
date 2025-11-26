
<br>
<div class="col-md-6">
<center><b>RINCIAN FAKTUR</b><br>No Tanda Terima Faktur. <?php echo $result[0]->no_terima_faktur?></center><br>
<table id="dt_search_result_pasien" class="table" width="50%">
    <thead>
      <tr>
        <th class="center" width="50px">No</th>
        <th>Kode Penerimaan</th>
        <th>No Faktur</th>
        <th>Total (Rp)</th>
      </tr>
    </thead>
    <tbody>
      <?php 
        $no=0; 
        foreach($result as $r=>$v) : $no++; 
          $arr_total[] = $v->total_hutang;
      ?>
        <tr>
          <td class="center"><?php echo $no; ?></td>
          <td><?php echo $v->kode_penerimaan?></td>
          <td><?php echo $v->no_faktur?></td>
          <td align="right"><a href="#" onclick="show_detail_penerimaan(<?php echo $id_tc_hutang_supplier_inv?>,<?php echo $v->id_penerimaan?>, '<?php echo $result[0]->flag?>')"><?php echo number_format($v->total_hutang)?></a></td>
        </tr>
      <?php endforeach; ?>
      <tr>
        <td colspan="3" align="right">Subotal</td>
        <td align="right"><?php echo number_format(array_sum($arr_total))?></td>
      </tr>
      <tr>
        <td colspan="3" align="right">PPN</td>
        <td align="right"><?php echo number_format($result[0]->total_ppn)?></td>
      </tr>
      <tr>
        <td colspan="3" align="right">Materai</td>
        <td align="right"><?php echo number_format($result[0]->biaya_materai)?></td>
      </tr>
      <tr>
        <td colspan="3" align="right">Total</td>
        <td align="right"><b><?php $total = array_sum($arr_total) + $result[0]->total_ppn + $result[0]->biaya_materai; echo number_format($total)?></b></td>
      </tr>
    </tbody>
</table>
</div>

<div class="col-md-6">
<center><b>RINCIAN PENERIMAAN BARANG </b><br>Kode Penerimaan <span id="txt_no_penerimaan_<?php echo $id_tc_hutang_supplier_inv; ?>">-</span> Tgl. <span id="txt_tgl_penerimaan_<?php echo $id_tc_hutang_supplier_inv; ?>">-</span></center><br>
  <table id="dt_detail_penerimaan_<?php echo $id_tc_hutang_supplier_inv?>" class="table">
      <thead>
        <tr>
          <th width="30px">No</th>
          <th>Nama Barang</th>
          <th>Qty</th>
          <th>Disc</th>
          <th>Harga</th>
          <th width="100px">Subtotal (Rp.)</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
  </table>
  <!-- <a href="#" class="btn btn-xs btn-warning" onclick="preview_billing(<?php echo $id_tc_hutang_supplier_inv; ?>)"><i class="fa fa-print dark"></i><span class="dark"> Cetak Billing</span></a> -->
</div>