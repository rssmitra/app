<?php 
  header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
  $prefix_name = 'Export_Purchase_Order_'.$_GET['flag'].'';
  header("Content-Disposition: attachment; filename=".$prefix_name.'_'.date('YmdHis').".xls");  //File name extension was wrong
  header("Expires: 0");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("Cache-Control: private",false);
?>
<table id="table-monitoring-po" base-url="purchasing/po/Po_monitoring/get_data?flag=" data-id="flag=" class="table" >
  <thead>
  <tr>  
    <th width="30px" class="center">No</th>
    <th>Nomor PO</th>
    <th>Tanggal</th>
    <th>Jenis</th>
    <th>Nama Supplier</th>
    <th>Kode</th>
    <th>Nama Barang</th>
    <th>Rasio</th>
    <th>Satuan</th>
    <th>Jml Pesan</th>
    <th>@ Harga</th>
    <th>Diskon</th>
    <th>Total Harga</th>
    <th>Status</th>
  </tr>
  </thead>
  <tbody>
    <?php 
      $no=0; foreach( $data as $row) : 
        if($row->jumlah_kirim > 0){
            $status = ($row->jumlah_kirim == $row->jumlah_besar) ? 'Selesai' : 'Diterima '.$row->jumlah_kirim.' '.$row->satuan_besar.' <br> '.$this->tanggal->formatDateTime($row->tgl_terima).'' ;
        }else{
            $status = ($row->jumlah_kirim == $row->jumlah_besar) ? 'Selesai' : 'Belum dikirim' ;
        }
      $no++;
    ?>
  <tr>  
    <td><?php echo $no?></td>
    <td width="150px"><?php echo $row->no_po; ?></td>
    <td><?php echo $this->tanggal->formatDateDmy($row->tgl_po); ?></td>
    <td><?php echo $row->jenis_po?> </td>
    <td><?php echo $row->namasupplier?> </td>
    <td><?php echo $row->kode_brg?> </td>
    <td><?php echo $row->nama_brg?> </td>
    <td><?php echo $row->content?> </td>
    <td><?php echo $row->satuan_besar?> </td>
    <td><?php echo $row->jumlah_besar?></td>
    <td><?php echo $row->harga_satuan?></td>
    <td><?php echo $row->discount?></td>
    <td><?php echo $row->jumlah_harga?></td>
    <td><?php echo $status?></td>
    <td></td>
  </tr>
  <?php endforeach; ?>

  </tbody>
</table>
