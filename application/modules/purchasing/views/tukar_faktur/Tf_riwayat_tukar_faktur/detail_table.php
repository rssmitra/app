<style>
  .det-wrap { border: 1px solid #c0d4e8; border-radius: 5px; overflow: hidden; margin: 6px 0 10px; }
  .det-hdr { background: #1a4f8a; color: #fff; padding: 8px 14px; font-size: 12px; font-weight: 700; display: flex; align-items: center; gap: 8px; flex-wrap: wrap; }
  .det-hdr small { font-weight: 400; opacity: .85; }
  .det-tbl { width: 100%; border-collapse: collapse; font-size: 12px; }
  .det-tbl thead tr { background: #2c6fad; color: #fff; }
  .det-tbl thead th { padding: 8px 10px; text-align: center; font-weight: 600; border: 1px solid #1e5590; }
  .det-tbl tbody tr:nth-child(even) { background: #f5f9fd; }
  .det-tbl tbody tr:hover { background: #e8f0f9; }
  .det-tbl tbody td { padding: 7px 10px; border: 1px solid #d0dce8; vertical-align: middle; }
  .det-tbl .footer-row td { background: #eef4fb; font-weight: 600; border: 1px solid #d0dce8; padding: 7px 10px; }
</style>

<div class="row">
  <div class="col-md-6">
    <div class="det-wrap">
      <div class="det-hdr">
        <i class="fa fa-file-text-o"></i> Rincian Faktur
        <small>No. TTF: <?php echo $result[0]->no_terima_faktur?></small>
      </div>
      <table class="det-tbl">
        <thead>
          <tr>
            <th width="40px">No</th>
            <th>Kode Penerimaan</th>
            <th>No Faktur</th>
            <th width="120px">Total (Rp)</th>
          </tr>
        </thead>
        <tbody>
          <?php $no=0; foreach($result as $r=>$v) : $no++; $arr_total[] = $v->total_hutang; ?>
          <tr>
            <td class="center"><?php echo $no; ?></td>
            <td><?php echo $v->kode_penerimaan?></td>
            <td><?php echo $v->no_faktur?></td>
            <td align="right">
              <a href="#" onclick="show_detail_penerimaan(<?php echo $id_tc_hutang_supplier_inv?>,<?php echo $v->id_penerimaan?>, '<?php echo $result[0]->flag?>')">
                <?php echo number_format($v->total_hutang)?>
              </a>
            </td>
          </tr>
          <?php endforeach; ?>
          <tr class="footer-row">
            <td colspan="3" align="right">Subtotal</td>
            <td align="right"><?php echo number_format(array_sum($arr_total))?></td>
          </tr>
          <tr class="footer-row">
            <td colspan="3" align="right">PPN</td>
            <td align="right"><?php echo number_format($result[0]->total_ppn)?></td>
          </tr>
          <tr class="footer-row">
            <td colspan="3" align="right">Materai</td>
            <td align="right"><?php echo number_format($result[0]->biaya_materai)?></td>
          </tr>
          <tr class="footer-row">
            <td colspan="3" align="right"><strong>Total</strong></td>
            <td align="right">
              <strong><?php $total = array_sum($arr_total) + $result[0]->total_ppn + $result[0]->biaya_materai; echo number_format($total)?></strong>
            </td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>

  <div class="col-md-6">
    <div class="det-wrap">
      <div class="det-hdr">
        <i class="fa fa-cubes"></i> Rincian Penerimaan Barang
        <small>
          Kode: <span id="txt_no_penerimaan_<?php echo $id_tc_hutang_supplier_inv; ?>">-</span>
          &nbsp;|&nbsp; Tgl: <span id="txt_tgl_penerimaan_<?php echo $id_tc_hutang_supplier_inv; ?>">-</span>
        </small>
      </div>
      <table id="dt_detail_penerimaan_<?php echo $id_tc_hutang_supplier_inv?>" class="det-tbl">
        <thead>
          <tr>
            <th width="35px">No</th>
            <th>Nama Barang</th>
            <th width="60px">Qty</th>
            <th width="60px">Disc</th>
            <th width="100px">Harga</th>
            <th width="110px">Subtotal (Rp.)</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>
</div>
