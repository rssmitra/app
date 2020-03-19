<div class="row">
  <div class="col-xs-12">
    <!-- PAGE CONTENT BEGINS -->
      <h4>Rincian Permohonan Barang</h4>
      <table class="table table-bordered table-hovered" style="font-size:11px">
        <tr>
          <th class="center">No</th>
          <th>Kode Barang</th>
          <th>Nama Barang</th>
          <th class="center">Jumlah<br>Permohonan</th>
          <th class="center">Jumlah Brg<br>yang di ACC</th>
          <th class="center">Satuan Besar</th>
          <th class="center">Rasio</th>
        </tr>
        <?php $no=0; foreach($dt_detail_brg as $row_dt) : $no++?>
          <tr>
            <td class="center"><?php echo $no?></td>
            <td><?php echo $row_dt->kode_brg?></td>
            <td><?php echo $row_dt->nama_brg?></td>
            <td class="center"><?php echo $row_dt->jumlah_besar?></td>
            <td class="center"><?php echo $row_dt->jumlah_besar_acc?></td>
            <td class="center"><?php echo $row_dt->satuan_besar?></td>
            <td class="center"><?php echo $row_dt->rasio?></td>
          </tr>
        <?php endforeach;?>
      </table>
    <!-- PAGE CONTENT ENDS -->
  </div><!-- /.col -->
</div><!-- /.row -->


