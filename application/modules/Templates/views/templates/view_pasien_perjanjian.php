<table class="table table-bordered table-hover" style="background-color: #a6d3f966">
  <thead>
    <tr>
      <th>No</th>
      <th>No MR</th>
      <th>Nama Pasien</th>
      <th>Tanggal Perjanjian</th>
      <th>Tanggal Kunjungan</th>
      <th>Alamat</th>
      <th>Status</th>
    </tr>
  </thead>  
  <tbody>
    <?php $no = 0; foreach( $result as $row_dt) : $no++; ?>
    <tr>
      <td><?php echo $no; ?></td>
      <td><?php echo $row_dt->no_mr?></td>
      <td><?php echo $row_dt->nama?></td>
      <td><?php echo $this->tanggal->formatDateTime($row_dt->input_tgl)?></td>
      <td><?php echo $this->tanggal->formatDate($row_dt->jam_pesanan)?></td>
      <td><?php echo $row_dt->alamat?></td>
      <?php 
        if($row_dt->tgl_masuk==NULL || empty($row_dt->tgl_masuk)){
          $status_periksa = '<label class="label label-warning"><i class="fa fa-info-circle"></i> Belum didaftarkan</label>';
        }else {
            $status_periksa = '<label class="label label-success"><i class="fa fa-check-circle"></i> Selesai</label>';
        }
      ?>
      <td><?php echo $status_periksa?></td>
    </tr>
    <?php endforeach;?>
  </tbody>

</table>