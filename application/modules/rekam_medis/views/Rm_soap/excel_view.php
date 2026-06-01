<?php
header("Content-Type: application/vnd.ms-excel; charset=utf-8");
header("Content-Disposition: attachment; filename=data_soap_pasien_" . date('Ymd_His') . ".xls");
header("Expires: 0");
header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
header("Cache-Control: private", false);
?>
<html>
<head>
  <meta charset="UTF-8">
  <title>Data SOAP Pasien</title>
</head>
<body>
  <h3>Data SOAP Pasien</h3>
  <?php
    $filter_parts = array();
    if (!empty($filters['no_mr']))       $filter_parts[] = 'No. MR: ' . htmlspecialchars($filters['no_mr']);
    if (!empty($filters['nama_pasien'])) $filter_parts[] = 'Nama Pasien: ' . htmlspecialchars($filters['nama_pasien']);
    if (!empty($filters['from_tgl']))    $filter_parts[] = 'Dari: ' . htmlspecialchars($filters['from_tgl']);
    if (!empty($filters['to_tgl']))      $filter_parts[] = 'Sampai: ' . htmlspecialchars($filters['to_tgl']);
    if (!empty($filters['tipe']))        $filter_parts[] = 'Tipe: ' . htmlspecialchars($filters['tipe']);
    if ($filter_parts) {
        echo '<p><b>Filter:</b> ' . implode(' | ', $filter_parts) . '</p>';
    }
  ?>
  <p>Dicetak: <?php echo date('d-m-Y H:i:s') ?></p>

  <table border="1" cellpadding="4" cellspacing="0">
    <thead>
      <tr style="background:#dbeafe;font-weight:bold;">
        <th>No</th>
        <?php foreach ($fields as $field): ?>
          <th><?php echo isset($labels[$field]) ? $labels[$field] : strtoupper($field) ?></th>
        <?php endforeach; ?>
      </tr>
    </thead>
    <tbody>
      <?php $no = 0; foreach ($getData as $row): $no++; ?>
        <tr>
          <td align="center"><?php echo $no ?></td>
          <?php foreach ($fields as $field): ?>
            <td><?php echo htmlspecialchars((string)($row[$field] ?? '')) ?></td>
          <?php endforeach; ?>
        </tr>
      <?php endforeach; ?>
      <?php if (empty($getData)): ?>
        <tr>
          <td colspan="<?php echo count($fields) + 1 ?>" align="center">Tidak ada data</td>
        </tr>
      <?php endif; ?>
    </tbody>
  </table>
</body>
</html>
