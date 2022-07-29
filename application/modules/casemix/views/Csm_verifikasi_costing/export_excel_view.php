
<?php 
  header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
  header("Content-Disposition: attachment; filename=".'DATA_VERIFIKASI_EXPORTED_AT_'.date('Ymd').".xls");  //File name extension was wrong
  header("Expires: 0");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("Cache-Control: private",false);
?>
<table id="dynamic-table" base-url="casemix/Csm_verifikasi_costing/get_data?flag=" class="table table-bordered table-hover">
  <thead>
    <tr>  
      <th width="50px" class="center">No</th>
      <th width="70px">No. Reg</th>
      <th width="80px">No. SEP</th>
      <th width="70px">No. MR</th>
      <th>Nama Pasien</th>
      <th>Poli/Klinik</th>
      <th width="130px">Tanggal Masuk</th>
      <th width="130px">Tanggal Keluar</th>
      <th width="80px" class="center">Tipe (RI/RJ)</th>
      <th width="100px" class="center">Total Klaim</th>
      <th width="120px" class="center">Tanggal Costing</th>
    </tr>
  </thead>
  <tbody>
    <?php $no=0; foreach($result as $row) : $no++; ?>
      <tr>
        <td width="50px" class="center"><?php echo $no?></td>
        <td width="70px"><?php echo $row->no_registrasi?></td>
        <td width="80px"><?php echo $row->csm_rp_no_sep?></td>
        <td width="70px"><?php echo $row->csm_rp_no_mr?></td>
        <td><?php echo $row->csm_rp_nama_pasien?></td>
        <td><?php echo $row->csm_rp_bagian?></td>
        <td width="130px"><?php echo $this->tanggal->formatDate($row->csm_rp_tgl_masuk)?></td>
        <td width="130px"><?php echo $this->tanggal->formatDate($row->csm_rp_tgl_keluar)?></td>
        <td><?php echo $row->csm_rp_tipe?></td>
        <td><?php echo number_format($row->csm_dk_total_klaim)?></td>
        <td><?php echo  $this->tanggal->formatDate($row->created_date).'<br>by : '.$row->created_by?></td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>





