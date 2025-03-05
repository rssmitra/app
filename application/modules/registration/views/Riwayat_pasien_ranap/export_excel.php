<?php 
  $filename = 'Export_Data_Riwayat_Kunjungan_Pasien_ICU';
    header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=".$filename.'_'.date('Ymd').".xls");  //File name extension was wrong
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private",false);

?>
<h3>Data Riwayat Kunjungan Pasien ICU</h3>
<table id="dynamic-table" base-url="" class="table table-striped table-bordered table-hover">
    <thead>
      <tr>  
          <th>No</th>
          <th>No Registrasi</th>
          <th>No SEP</th>
          <th>Nama Pasien</th>
          <th>Asal Unit</th>
          <th>Kelas</th>
          <th>Penjamin</th>
          <th>Dokter Merawat</th>
          <th>Tanggal Masuk</th>
          <th>Tanggal Keluar</th>
          <th>Status</th>
      </tr>
    </thead>
    <tbody>
    <?php $no=0; foreach ($list as $key => $value) : $no++; 
     if($value->status_batal==1){
          $status_periksa = 'Batal Berobat';
      }else{
          $status_periksa = ($value->tgl_keluar==NULL)?'Belum diperiksa':'Selesai';
      }
    ?>
      <tr>
        <td><?php echo $no?></td>
        <td><?php echo $value->no_registrasi?></td>
        <td><?php echo $value->no_sep?></td>
        <td><?php echo $value->no_mr?></td>
        <td><?php echo $value->nama_pasien?></td>
        <td><?php echo $value->asal_bagian?></td>
        <td><?php echo $value->nama_klas?></td>
        <td><?php echo $value->nama_perusahaan?></td>
        <td><?php echo $value->dokter_merawat?></td>
        <td><?php echo $value->tgl_masuk;?></td>
        <td><?php echo $value->tgl_keluar;?></td>
        <td><?php echo $status_periksa;?></td>
      </tr>
    <?php endforeach; ?>
    </tbody>
</table>