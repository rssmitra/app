<?php
  header("Content-Type: application/vnd.ms-excel; charset=utf-8");
  header("Content-Disposition: attachment; filename=export_perjanjian_" . date('Ymd') . ".xls");
  header("Expires: 0");
  header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  header("Cache-Control: private", false);
?>
<html>
<head>
  <title><?php echo $title?></title>
  <style>
    body { font-family: Arial, sans-serif; font-size: 11px; }
    table { border-collapse: collapse; width: 100%; }
    th { background: #0369a1; color: #fff; padding: 6px 8px; text-align: left; font-size: 11px; text-transform: uppercase; }
    td { padding: 5px 8px; border-bottom: 1px solid #e2e8f0; font-size: 11px; }
    tr:nth-child(even) td { background: #f8fafc; }
    h3 { color: #0369a1; margin-bottom: 4px; }
    .param { font-size: 10px; color: #64748b; margin-bottom: 12px; }
  </style>
</head>
<body>
  <h3><?php echo $title?></h3>
  <div class="param">Dicetak pada: <?php echo date('d-m-Y H:i:s')?></div>

  <table>
    <thead>
      <tr>
        <th>NO</th>
        <?php foreach ($fields as $field): ?>
          <th><?php echo strtoupper($field->name)?></th>
        <?php endforeach; ?>
      </tr>
    </thead>
    <tbody>
      <?php $no = 0; foreach ($data as $row_data): $no++; ?>
        <tr>
          <td align="center"><?php echo $no?></td>
          <?php foreach ($fields as $row_field): ?>
            <?php $fn = $row_field->name; ?>
            <td><?php echo strtoupper($row_data->$fn)?></td>
          <?php endforeach; ?>
        </tr>
      <?php endforeach; ?>
    </tbody>
  </table>
</body>
</html>
