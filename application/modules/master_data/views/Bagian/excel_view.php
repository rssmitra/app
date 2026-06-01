<?php
    header("Content-Type: application/vnd.ms-excel; charset=utf-8");
    header("Content-Disposition: attachment; filename=data-bagian-" . date('Ymd') . ".xls");
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
      <th style="background:#f0f0f0">No</th>
      <th style="background:#f0f0f0">Kode Bagian</th>
      <th style="background:#f0f0f0">Nama Bagian</th>
      <th style="background:#f0f0f0">Nama Singkat</th>
      <th style="background:#f0f0f0">Group Bag</th>
      <th style="background:#f0f0f0">Nama Group (Parent)</th>
      <th style="background:#f0f0f0">Depo?</th>
      <th style="background:#f0f0f0">Depo Group</th>
      <th style="background:#f0f0f0">Publik?</th>
      <th style="background:#f0f0f0">Pelayanan?</th>
      <th style="background:#f0f0f0">Status Aktif</th>
    </tr>
  </thead>
  <tbody>
    <?php $no = 0; foreach ($list as $r): $no++; ?>
    <tr>
      <td align="center"><?php echo $no; ?></td>
      <td><?php echo $r->kode_bagian; ?></td>
      <td><?php echo $r->nama_bagian; ?></td>
      <td><?php echo isset($r->short_name) ? $r->short_name : ''; ?></td>
      <td><?php echo $r->group_bag; ?></td>
      <td><?php echo (isset($r->nama_depo_group) && $r->nama_depo_group) ? $r->nama_depo_group : '-'; ?></td>
      <td align="center"><?php echo (isset($r->is_depo) && $r->is_depo == 'Y') ? 'Ya' : 'Tidak'; ?></td>
      <td><?php echo (isset($r->depo_group) && $r->depo_group) ? $r->depo_group : '-'; ?></td>
      <td align="center"><?php echo (isset($r->is_public) && $r->is_public == '1') ? 'Ya' : 'Tidak'; ?></td>
      <td><?php
        $pel = isset($r->pelayanan) ? (string)$r->pelayanan : '';
        echo ($pel === '1') ? 'Pelayanan' : (($pel === '0') ? 'Backoffice' : '-');
      ?></td>
      <td align="center"><?php echo ((string)$r->is_active === 'Y') ? 'Aktif' : 'Tidak Aktif'; ?></td>
    </tr>
    <?php endforeach; ?>
    <tr>
      <td colspan="11" style="color:#888;font-size:10px">
        Diekspor: <?php echo date('d/m/Y H:i:s'); ?>
        <?php
          $filters = array();
          if (isset($_GET['filter_is_active'])  && $_GET['filter_is_active']  !== '') $filters[] = 'Status: ' . $_GET['filter_is_active'];
          if (isset($_GET['filter_depo_group']) && $_GET['filter_depo_group'] !== '') $filters[] = 'Group: ' . $_GET['filter_depo_group'];
          echo $filters ? ' | Filter: ' . implode(', ', $filters) : '';
        ?>
      </td>
    </tr>
  </tbody>
</table>
