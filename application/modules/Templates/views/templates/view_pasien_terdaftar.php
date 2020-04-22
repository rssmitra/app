<table class="table table-bordered table-hover" style="background-color: #a6d3f966">
  <thead>
    <tr>
      <th>No</th>
      <th>No MR</th>
      <th>Nama Pasien</th>
      <th>Tanggal</th>
      <th>Penjamin</th>
      <th class="center">No Antrian</th>
      <th>Status</th>
    </tr>
  </thead>  
  <tbody>
    <?php $no = 0; foreach( $result as $row_dt) : $no++; ?>
    <tr>
      <td><?php echo $no; ?></td>
      <td><?php echo $row_dt->no_mr?></td>
      <td><?php echo $row_dt->nama_pasien?></td>
      <td><?php echo $this->tanggal->formatDateTime($row_dt->tgl_jam_poli)?></td>
      <td><?php echo $row_dt->nama_perusahaan?></td>
      <td align="center"><?php echo $no?></td>
      <?php 
        if($row_dt->status_batal==1){
          $status_periksa = '<label class="label label-danger"><i class="fa fa-times-circle"></i> Batal Berobat</label>';
        }else{
            if($row_dt->tgl_keluar_poli==NULL || empty($row_dt->tgl_keluar_poli)){
                $status_periksa = '<label class="label label-warning"><i class="fa fa-info-circle"></i> Belum diperiksa</label>';
            }else {
                $status_periksa = '<label class="label label-success"><i class="fa fa-check-circle"></i> Selesai</label>';
            }
        }
        
      ?>
      <td><?php echo $status_periksa?></td>
    </tr>
    <?php endforeach;?>
  </tbody>

</table>