<?php
    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=harga-pokok-" . $flag_string . "-" . date('Ymd') . ".xls");
    header("Expires: 0");
    header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
    header("Cache-Control: private", false);
?>
<table border="1" cellpadding="4" cellspacing="0">
  <thead>
    <tr>
      <th colspan="11" style="text-align:center;font-size:14px"><b><?php echo strtoupper($title); ?></b></th>
    </tr>
    <tr>
      <th colspan="11" style="font-size:11px;color:#555">
        Diekspor: <?php echo date('d/m/Y H:i:s'); ?>
        <?php
          $filters = array();
          if (isset($_GET['kode_golongan']) && $_GET['kode_golongan'] !== '') $filters[] = 'Golongan: ' . $_GET['kode_golongan'];
          if (isset($_GET['kode_sub_gol'])  && $_GET['kode_sub_gol']  !== '') $filters[] = 'Sub Gol: ' . $_GET['kode_sub_gol'];
          echo $filters ? ' | Filter: ' . implode(', ', $filters) : '';
        ?>
      </th>
    </tr>
    <tr style="background:#dce6f1;font-weight:bold;text-align:center">
      <th>No</th>
      <th>Kode Barang</th>
      <th>Nama Barang</th>
      <th>Pabrik</th>
      <th>Satuan</th>
      <th>HPP per Hari ini (Rp)</th>
      <th>Harga Modal Sblm Diskon (Rp)</th>
      <th>Harga Modal Stlh Diskon/WA (Rp)</th>
      <th>HPP Kalkulasi (Rp)</th>
      <th>Est. Harga Jual (Rp)</th>
      <th>Tgl Update</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($rows as $r): ?>
    <tr>
      <td align="center"><?php echo $r['no']; ?></td>
      <td><?php echo $r['kode_brg']; ?></td>
      <td><?php echo $r['nama_brg']; ?></td>
      <td><?php echo $r['nama_pabrik']; ?></td>
      <td align="center"><?php echo $r['satuan']; ?></td>
      <td align="right"><?php echo $r['hpp_hari_ini']   > 0 ? number_format($r['hpp_hari_ini'],   0, ',', '.') : '-'; ?></td>
      <td align="right"><?php echo $r['hm_sblm_diskon'] > 0 ? number_format($r['hm_sblm_diskon'], 0, ',', '.') : '-'; ?></td>
      <td align="right"><?php echo $r['hm_stlh_diskon'] > 0 ? number_format($r['hm_stlh_diskon'], 0, ',', '.') : '-'; ?></td>
      <td align="right"><?php echo $r['hpp_calc']        > 0 ? number_format($r['hpp_calc'],       0, ',', '.') : '-'; ?></td>
      <td align="right"><?php echo $r['harga_jual_est']  > 0 ? number_format($r['harga_jual_est'], 0, ',', '.') : '-'; ?></td>
      <td align="center"><?php echo $r['updated_date']; ?></td>
    </tr>
    <?php endforeach; ?>
    <tr>
      <td colspan="11" style="font-size:10px;color:#888">Total: <?php echo count($rows); ?> barang</td>
    </tr>
  </tbody>
</table>
