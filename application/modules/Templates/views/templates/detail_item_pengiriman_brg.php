<?php if(count($result) > 1) : ?>
  <div class="alert alert-warning" style="margin-bottom: 11px;">
    <strong>Pemberitahuan !</strong> Terdapat <?php echo count($result)?> barang yang berbeda dengan kode barcode yang sama
  </div>
<?php endif; ?>

<table class="table table-hovered" style="margin-top: -0px;">
  <thead>
    <tr style="background: #edf3f4;">
      <th width="20px" class="center">No</th>
      <th>Description</th>
      <th width="100px">Sisa Stok</th>
      <th width="100px">Harga Satuan </th>
    </tr>
  </thead>
  <tbody>
  <?php 
    $no = 0;
    foreach($result as $key=>$row_dt) :
      $link_image = ( $row_dt->path_image != NULL ) ? PATH_IMG_MST_BRG.$row_dt->path_image : PATH_IMG_MST_BRG.'no-image.jpg' ;
      $no++;
  ?>
  
      <tr>
        <td style="vertical-align: top" class="center" style="background: #edf3f4;"><?php echo $no; ?></td>
        <td><a href="#" onclick="getDetailBarang('<?php echo $row_dt->kode_brg; ?>')"><?php echo $row_dt->kode_brg; ?><br><?php echo $row_dt->nama_brg; ?></td>
        <td style="vertical-align: top"><?php echo $row_dt->jml_sat_kcl; ?></span> <?php echo $row_dt->satuan_kecil?></td>
        <td style="text-align: right; vertical-align: top"><?php echo number_format($row_dt->harga_beli, 2); ?></td>
      </tr>
  <?php endforeach;?>
  </tbody>
</table>