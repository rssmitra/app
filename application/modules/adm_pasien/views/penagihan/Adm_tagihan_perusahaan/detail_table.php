
<br>
<div class="col-md-3">
<center><b>RIWAYAT INVOICE </b><br>S/D BULAN <?php echo strtoupper($this->tanggal->getBulan(date('m')))?> TAHUN <?php echo date('Y')?></center><br>
<table id="dt_search_result_pasien" class="table">
    <thead>
      <tr>
        <th class="center" width="50px">No</th>
        <th width="50px">Tanggal</th>
        <th>No. Invoice</th>
        <th></th>
      </tr>
    </thead>
    <tbody>
      <?php $no=0; foreach($result as $r=>$v) : $no++; ?>
        <tr>
          <td class="center"><?php echo $no; ?></td>
          <td width="50px"><?php echo $this->tanggal->formatDateDmy($v->tgl_tagih); ?></td>
          <td><?php echo $v->no_invoice_tagih?></td>
          <td align="center"><a href="#" onclick="show_detail_inv(<?php echo $v->id_tc_tagih; ?>, <?php echo $kode_perusahaan; ?>)" ><i class="fa fa-arrow-right dark"></i></a></td>
        </tr>
      <?php endforeach; ?>
    </tbody>
</table>
</div>

<div class="col-md-9">
<center><b>DATA TRANSAKSI PASIEN </b><br>NO. INVOICE <span id="txt_no_invoice_<?php echo $kode_perusahaan; ?>">-</span></center><br>
  <table id="dt_detail_invoice_<?php echo $kode_perusahaan?>" class="table">
      <thead>
        <tr>
          <th>Kode Transaksi</th>
          <th>Tanggal</th>
          <!-- <th>No Registrasi</th> -->
          <th>No MR</th>
          <th>Nama Pasien</th>
          <th>Jumlah Billing</th>
          <th>Beban Pasien</th>
          <th>Jumlah Ditagih</th>
        </tr>
      </thead>
      <tbody>
      </tbody>
  </table>
  <a href="#" class="btn btn-xs btn-warning"><i class="fa fa-print dark"></i><span class="dark"> Cetak Invoice</span></a>
</div>