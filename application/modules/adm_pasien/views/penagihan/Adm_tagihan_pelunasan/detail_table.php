
<br>
<div class="col-md-7">
<center><b>DATA TAGIHAN PASIEN ASURANSI</b><br>No Invoice. <?php echo $result[0]->no_invoice_tagih?></center><br>
<table id="dt_search_result_pasien" class="table">
    <thead>
      <tr>
        <th class="center" width="50px">No</th>
        <th width="50px">Kode</th>
        <th width="50px">No MR</th>
        <th>Nama Pasien</th>
        <th width="80px">Tanggal</th>
        <th width="130px">Jumlah Tagihan</th>
      </tr>
    </thead>
    <tbody>
      <?php $no=0; foreach($result as $r=>$v) : $no++; ?>
        <!-- hidden -->
        <span id="beban_pasien_<?php echo $v->kode_tc_trans_kasir; ?>" style="display: none"><?php echo $v->beban_pasien_int; ?></span>
        <tr>
          <td class="center"><?php echo $no; ?></td>
          <td align="center"><?php echo $v->kode_tc_trans_kasir?></td>
          <td><?php echo $v->no_mr?></td>
          <td><?php echo $v->nama_pasien?></td>
          <td width="50px"><?php echo $this->tanggal->formatDateDmy($v->tgl_jam); ?></td>
          <td align="right"><a href="#" onclick="show_detail_inv(<?php echo $v->kode_tc_trans_kasir; ?>, <?php echo $id_tc_tagih; ?>)" ><?php echo number_format($v->jumlah_dijamin)?></a></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
</table>
</div>

<div class="col-md-5">
<center><b>RINCIAN BILLING PASIEN </b><br>No. Registrasi <span id="txt_no_invoice_<?php echo $id_tc_tagih; ?>">-</span></center><br>
  <table id="dt_detail_invoice_<?php echo $id_tc_tagih?>" class="table">
      <thead>
        <tr>
          <th width="30px">No</th>
          <th>Uraian</th>
          <th width="100px">Subtotal (Rp.)</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
  </table>
  <!-- <a href="#" class="btn btn-xs btn-warning" onclick="preview_billing(<?php echo $id_tc_tagih; ?>)"><i class="fa fa-print dark"></i><span class="dark"> Cetak Billing</span></a> -->
</div>