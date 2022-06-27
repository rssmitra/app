<?php 
  $filename = 'Export_Data_Riwayat_Kunjungan_Pasien_Fisioterapi';
    header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=".$filename.'_'.date('Ymd').".xls");  //File name extension was wrong
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private",false);

?>
<h3>Data Riwayat Kunjungan Pasien Fisioterapi</h3>
<table id="dynamic-table" base-url="" class="table table-striped table-bordered table-hover">
    <thead>
    <tr>  
      <th>No</th>      
      <th>No MR</th>      
      <th>Nama Pasien</th>      
      <th>Tanggal, Jam Masuk</th>      
      <th>Tanggal, Jam Keluar</th>      
    </tr>
    </thead>
    <tbody>

    <?php $no=0; foreach ($list as $key => $value) : $no++; ?>
      <tr>
        <td><?php echo $no?></td>
        <td><?php echo $value->no_mr?></td>
        <td><?php echo $value->nama_pasien?></td>
        <td><?php echo $value->tgl_masuk?></td>
        <td><?php echo $value->tgl_keluar?></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
</table>