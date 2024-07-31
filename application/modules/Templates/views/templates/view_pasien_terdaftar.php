<p style="text-align: center">
  <b>DATA KUNJUNGAN PASIEN TANGGAL <?php echo $this->tanggal->formatDateDmy($_GET['tgl_registrasi'])?></b>
  <span style="font-weight: bold">
    <br><?php echo strtoupper($result[0]->nama_dr)?><br>
    <?php echo strtoupper($result[0]->nama_bagian)?>
  </span>
</p>
<table class="table table-bordered">
  <thead>
    <tr>
      <th class="center" width="30px">No</th>
      <th>No MR</th>
      <th>Nama Pasien</th>
      <th width="80px" class="center">Umur</th>
      <th width="150px">Tanggal</th>
      <th width="150px">Penjamin</th>
      <th class="center" width="80px">No Antrian</th>
      <th width="80px">Status</th>
    </tr>
  </thead>  
  <tbody>
    <?php $no = 0; foreach( $result as $row_dt) : $no++; ?>
    <tr>
      <td align="center"><?php echo $no; ?></td>
      <td width="80px" align="center"><?php echo $row_dt->no_mr?></td>
      <td><?php echo $row_dt->nama_pasien?></td>
      <td align="center"><?php echo $row_dt->umur?></td>
      <td><?php echo $this->tanggal->formatDateTime($row_dt->tgl_jam_poli)?></td>
      <td><?php echo $row_dt->nama_perusahaan?></td>
      <td align="center"><?php echo $row_dt->no_antrian?></td>
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
      <td align="center"><?php echo $status_periksa?></td>
    </tr>
    <?php endforeach;?>
  </tbody>

</table>