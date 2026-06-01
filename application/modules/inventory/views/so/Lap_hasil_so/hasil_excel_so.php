<?php
  // header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
  // header("Content-Disposition: attachment; filename=".$title.'_'.date('Ymd').".xls");
  // header("Expires: 0");
  // header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  // header("Cache-Control: private",false);
?>
<html>
<head>
  <title>Laporan Hasil Stok Opname</title>
  <meta charset="UTF-8">
</head>
<body style="background:#fff; font-family:Calibri,Arial,sans-serif; font-size:12px;">

<?php
  /* ── Pre-pass: akumulasi summary sebelum render tabel ── */
  $summary  = array();
  $grand    = array('count'=>0,'total'=>0,'total_exp'=>0,'total_will_exp'=>0);
  $kondisi_summary = array(
    'Sesuai' => array('count'=>0,'total'=>0,'total_exp'=>0,'total_will_exp'=>0),
    'Kurang' => array('count'=>0,'total'=>0,'total_exp'=>0,'total_will_exp'=>0),
    'Lebih'  => array('count'=>0,'total'=>0,'total_exp'=>0,'total_will_exp'=>0),
  );

  foreach ($result_content as $rd) {
    $ps      = isset($po_map[$rd->kode_brg]) ? $po_map[$rd->kode_brg] : null;
    $rasio   = ($rd->content > 0) ? (int)$rd->content : 1;
    $h_netto = ($ps && $ps->wa_harga_modal > 0) ? (int)round($ps->wa_harga_modal / $rasio) : 0;
    $t  = $h_netto * $rd->stok_sekarang;
    $te = $h_netto * $rd->stok_exp;
    $tw = $h_netto * $rd->will_stok_exp;
    $sk = ($rd->set_status_aktif == 0) ? 'Tidak Aktif' : 'Aktif';

    if (!isset($summary[$sk])) {
      $summary[$sk] = array('count'=>0,'total'=>0,'total_exp'=>0,'total_will_exp'=>0);
    }
    $summary[$sk]['count']++;
    $summary[$sk]['total']          += $t;
    $summary[$sk]['total_exp']      += $te;
    $summary[$sk]['total_will_exp'] += $tw;

    $grand['count']++;
    $grand['total']          += $t;
    $grand['total_exp']      += $te;
    $grand['total_will_exp'] += $tw;

    if ($rd->stok_sekarang == $rd->stok_sebelum)      { $kk = 'Sesuai'; }
    elseif ($rd->stok_sekarang < $rd->stok_sebelum)   { $kk = 'Kurang'; }
    else                                               { $kk = 'Lebih';  }
    $kondisi_summary[$kk]['count']++;
    $kondisi_summary[$kk]['total']          += $t;
    $kondisi_summary[$kk]['total_exp']      += $te;
    $kondisi_summary[$kk]['total_will_exp'] += $tw;
  }
  krsort($summary); // Aktif dulu
?>

<!-- ═══════ HEADER LAPORAN ═══════ -->
<table border="0" cellpadding="3" cellspacing="0" style="margin-bottom:6px;">
  <tr>
    <td style="font-size:16px;font-weight:bold;color:#1a4f8a;">
      LAPORAN HASIL STOK OPNAME
    </td>
  </tr>
  <tr>
    <td style="font-size:12px;color:#444;">
      <?php echo isset($title) ? strtoupper($title) : '-'; ?>
    </td>
  </tr>
  <tr>
    <td style="font-size:11px;color:#666;">
      Tanggal Cetak: <?php echo date('d/m/Y H:i'); ?>
      &nbsp;|&nbsp;
      Tgl SO: <?php echo isset($value->agenda_so_date) ? $value->agenda_so_date : '-'; ?>
      &nbsp;|&nbsp;
      PJ: <?php echo isset($value->agenda_so_spv) ? $value->agenda_so_spv : '-'; ?>
      &nbsp;|&nbsp;
      Total Item: <strong><?php echo count($result_content); ?></strong>
    </td>
  </tr>
</table>

<br>

<!-- ═══════ TABEL DETAIL ═══════ -->
<table border="1" cellpadding="4" cellspacing="0"
       style="border-collapse:collapse;width:100%;font-size:11px;border-color:#bbb;">
  <thead>
    <tr bgcolor="#1a4f8a" style="font-weight:bold;text-align:center;">
      <td style="color:#fff;width:35px;">NO</td>
      <td style="color:#fff;width:90px;">KODE</td>
      <td style="color:#fff;width:220px;">NAMA BARANG</td>
      <td style="color:#fff;width:110px;text-align:right;">HARGA SATUAN<br>RATA-RATA (Rp)</td>
      <td style="color:#fff;width:70px;text-align:center;">STOK<br>SEBELUM</td>
      <td style="color:#fff;width:70px;text-align:center;">HASIL SO</td>
      <td style="color:#fff;width:65px;text-align:center;">EXPIRED</td>
      <td style="color:#fff;width:75px;text-align:center;">EXPIRED<br>-3 BLN</td>
      <td style="color:#fff;width:85px;text-align:center;">SATUAN</td>
      <td style="color:#fff;width:130px;text-align:right;">TOTAL PERSEDIAAN<br>(Rp)</td>
      <td style="color:#fff;width:130px;text-align:right;">TOTAL EXPIRED<br>(Rp)</td>
      <!-- <td style="color:#fff;width:130px;text-align:right;">TOTAL EXPIRED<br>-3 BLN (Rp)</td> -->
      <td style="color:#fff;width:70px;text-align:center;">STATUS</td>
      <td style="color:#fff;width:75px;text-align:center;">KONDISI SO</td>
      <td style="color:#fff;width:130px;">PETUGAS</td>
      <td style="color:#fff;width:120px;text-align:center;">WAKTU INPUT</td>
    </tr>
  </thead>
  <tbody>
    <?php
      $no = 0;
      $totalhasil = 0;
      $arr_totalhasil_exp = 0;
      $arr_totalhasil_will_exp = 0;
      foreach ($result_content as $row_data): $no++;
        $satuan = ($row_data->satuan_kecil == $row_data->satuan_besar)
          ? $row_data->satuan_kecil
          : $row_data->satuan_kecil . '/ ' . $row_data->satuan_besar;
        $ps          = isset($po_map[$row_data->kode_brg]) ? $po_map[$row_data->kode_brg] : null;
        $rasio       = ($row_data->content > 0) ? (int)$row_data->content : 1;
        $harga_netto = ($ps && $ps->wa_harga_modal > 0) ? (int)round($ps->wa_harga_modal / $rasio) : 0;
        $total          = $harga_netto * $row_data->stok_sekarang;
        $total_exp      = $harga_netto * $row_data->stok_exp;
        $total_will_exp = $harga_netto * $row_data->will_stok_exp;
        $totalhasil              += $total;
        $arr_totalhasil_exp      += $total_exp;
        $arr_totalhasil_will_exp += $total_will_exp;
        $status = ($row_data->set_status_aktif == 0) ? 'Tidak Aktif' : 'Aktif';
        $status_color = ($row_data->set_status_aktif == 0) ? '#c62828' : '#2e7d32';

        if ($row_data->stok_sekarang == $row_data->stok_sebelum) {
          $kondisi = 'Sesuai'; $kbg = '#e8f5e9'; $kclr = '#1b5e20';
        } elseif ($row_data->stok_sekarang < $row_data->stok_sebelum) {
          $kondisi = 'Kurang'; $kbg = '#fff3e0'; $kclr = '#e65100';
        } else {
          $kondisi = 'Lebih';  $kbg = '#e3f2fd'; $kclr = '#0d47a1';
        }
        $row_bg = ($no % 2 == 0) ? '#f9fafb' : '#ffffff';
    ?>
    <tr bgcolor="<?php echo $row_bg; ?>">
      <td align="center"><?php echo $no; ?></td>
      <td><?php echo htmlspecialchars($row_data->kode_brg); ?></td>
      <td><?php echo htmlspecialchars($row_data->nama_brg); ?></td>
      <td align="right"><?php echo $harga_netto; ?></td>
      <td align="center"><?php echo $row_data->stok_sebelum; ?></td>
      <td align="center"><?php echo $row_data->stok_sekarang; ?></td>
      <td align="center"><?php echo $row_data->stok_exp; ?></td>
      <td align="center"><?php echo $row_data->will_stok_exp; ?></td>
      <td align="center"><?php echo htmlspecialchars($satuan); ?></td>
      <td align="right"><?php echo (int)$total; ?></td>
      <td align="right"><?php echo (int)$total_exp; ?></td>
      <!-- <td align="right"><?php echo (int)$total_will_exp; ?></td> -->
      <td align="center" style="font-weight:bold;color:<?php echo $status_color; ?>;"><?php echo $status; ?></td>
      <td align="center" bgcolor="<?php echo $kbg; ?>" style="font-weight:bold;color:<?php echo $kclr; ?>;"><?php echo $kondisi; ?></td>
      <td><?php echo htmlspecialchars($row_data->nama_petugas); ?></td>
      <td align="center"><?php echo $this->tanggal->formatDateTime($row_data->tgl_stok_opname); ?></td>
    </tr>
    <?php endforeach; ?>

    <!-- Baris total -->
    <tr bgcolor="#e8eaf6" style="font-weight:bold;">
      <td colspan="9" align="right" style="padding-right:8px;">TOTAL KESELURUHAN</td>
      <td align="right"><?php echo (int)$totalhasil; ?></td>
      <td align="right"><?php echo (int)$arr_totalhasil_exp; ?></td>
      <!-- <td align="right"><?php echo (int)$arr_totalhasil_will_exp; ?></td> -->
      <td></td><td></td><td></td><td></td>
    </tr>
  </tbody>
</table>

<br><br>

<!-- ═══════ SUMMARY 1 — STATUS BARANG ═══════ -->
<table border="0" cellpadding="0" cellspacing="0" style="margin-bottom:5px;">
  <tr>
    <td style="font-size:13px;font-weight:bold;color:#1a4f8a;border-bottom:2px solid #1a4f8a;padding-bottom:3px;">
      RINGKASAN BERDASARKAN STATUS BARANG
    </td>
  </tr>
</table>

<table border="1" cellpadding="5" cellspacing="0"
       style="border-collapse:collapse;font-size:11px;border-color:#bbb;min-width:700px;">
  <thead>
    <tr bgcolor="#37474f" style="font-weight:bold;text-align:center;">
      <td style="color:#fff;width:130px;">STATUS</td>
      <td style="color:#fff;width:80px;text-align:center;">JML ITEM</td>
      <td style="color:#fff;width:160px;text-align:right;">TOTAL PERSEDIAAN (Rp)</td>
      <td style="color:#fff;width:160px;text-align:right;">TOTAL EXPIRED (Rp)</td>
      <td style="color:#fff;width:160px;text-align:right;">TOTAL EXPIRED -3 BLN (Rp)</td>
      <td style="color:#fff;width:80px;text-align:center;">% ITEM</td>
      <td style="color:#fff;width:100px;text-align:center;">% NILAI</td>
    </tr>
  </thead>
  <tbody>
    <?php
      $stat_meta = array(
        'Aktif'       => array('bg'=>'#e8f5e9','clr'=>'#1b5e20'),
        'Tidak Aktif' => array('bg'=>'#ffebee','clr'=>'#b71c1c'),
      );
      foreach ($summary as $sk => $sv):
        $pct_item  = ($grand['count'] > 0) ? round($sv['count'] / $grand['count'] * 100, 1) : 0;
        $pct_nilai = ($grand['total'] > 0) ? round($sv['total']  / $grand['total']  * 100, 1) : 0;
        $sm = isset($stat_meta[$sk]) ? $stat_meta[$sk] : array('bg'=>'#fff','clr'=>'#000');
    ?>
    <tr bgcolor="<?php echo $sm['bg']; ?>">
      <td style="font-weight:bold;color:<?php echo $sm['clr']; ?>;"><?php echo $sk; ?></td>
      <td align="center"><?php echo $sv['count']; ?></td>
      <td align="right"><?php echo (int)$sv['total']; ?></td>
      <td align="right"><?php echo (int)$sv['total_exp']; ?></td>
      <td align="right"><?php echo (int)$sv['total_will_exp']; ?></td>
      <td align="center"><?php echo $pct_item; ?>%</td>
      <td align="center"><?php echo $pct_nilai; ?>%</td>
    </tr>
    <?php endforeach; ?>
    <tr bgcolor="#e8eaf6" style="font-weight:bold;">
      <td>TOTAL</td>
      <td align="center"><?php echo $grand['count']; ?></td>
      <td align="right"><?php echo (int)$grand['total']; ?></td>
      <td align="right"><?php echo (int)$grand['total_exp']; ?></td>
      <td align="right"><?php echo (int)$grand['total_will_exp']; ?></td>
      <td align="center">100%</td>
      <td align="center">100%</td>
    </tr>
  </tbody>
</table>

<br><br>

<!-- ═══════ SUMMARY 2 — KONDISI SO ═══════ -->
<table border="0" cellpadding="0" cellspacing="0" style="margin-bottom:5px;">
  <tr>
    <td style="font-size:13px;font-weight:bold;color:#1a4f8a;border-bottom:2px solid #1a4f8a;padding-bottom:3px;">
      RINGKASAN BERDASARKAN KONDISI STOK OPNAME
    </td>
  </tr>
</table>

<table border="1" cellpadding="5" cellspacing="0"
       style="border-collapse:collapse;font-size:11px;border-color:#bbb;min-width:700px;">
  <thead>
    <tr bgcolor="#37474f" style="font-weight:bold;text-align:center;">
      <td style="color:#fff;width:130px;">KONDISI SO</td>
      <td style="color:#fff;width:80px;text-align:center;">JML ITEM</td>
      <td style="color:#fff;width:160px;text-align:right;">TOTAL PERSEDIAAN (Rp)</td>
      <td style="color:#fff;width:160px;text-align:right;">TOTAL EXPIRED (Rp)</td>
      <td style="color:#fff;width:160px;text-align:right;">TOTAL EXPIRED -3 BLN (Rp)</td>
      <td style="color:#fff;width:80px;text-align:center;">% ITEM</td>
      <td style="color:#fff;width:180px;">KETERANGAN</td>
    </tr>
  </thead>
  <tbody>
    <?php
      $km_meta = array(
        'Sesuai' => array('bg'=>'#e8f5e9','clr'=>'#1b5e20','ket'=>'Stok fisik sesuai sistem'),
        'Kurang' => array('bg'=>'#fff3e0','clr'=>'#e65100','ket'=>'Stok fisik di bawah sistem'),
        'Lebih'  => array('bg'=>'#e3f2fd','clr'=>'#0d47a1','ket'=>'Stok fisik di atas sistem'),
      );
      $k_grand = array_sum(array_column($kondisi_summary, 'count'));
      foreach ($kondisi_summary as $kk => $kv):
        $km   = $km_meta[$kk];
        $pct  = ($k_grand > 0) ? round($kv['count'] / $k_grand * 100, 1) : 0;
    ?>
    <tr bgcolor="<?php echo $km['bg']; ?>">
      <td style="font-weight:bold;color:<?php echo $km['clr']; ?>;"><?php echo $kk; ?></td>
      <td align="center"><?php echo $kv['count']; ?></td>
      <td align="right"><?php echo (int)$kv['total']; ?></td>
      <td align="right"><?php echo (int)$kv['total_exp']; ?></td>
      <td align="right"><?php echo (int)$kv['total_will_exp']; ?></td>
      <td align="center"><?php echo $pct; ?>%</td>
      <td><?php echo $km['ket']; ?></td>
    </tr>
    <?php endforeach; ?>
    <tr bgcolor="#e8eaf6" style="font-weight:bold;">
      <td>TOTAL</td>
      <td align="center"><?php echo $k_grand; ?></td>
      <td align="right"><?php echo (int)$grand['total']; ?></td>
      <td align="right"><?php echo (int)$grand['total_exp']; ?></td>
      <td align="right"><?php echo (int)$grand['total_will_exp']; ?></td>
      <td align="center">100%</td>
      <td></td>
    </tr>
  </tbody>
</table>

<br>
<table border="0" cellpadding="2" style="font-size:10px;color:#999;">
  <tr><td>*) Dicetak otomatis oleh sistem pada <?php echo date('d/m/Y H:i:s'); ?></td></tr>
</table>

</body>
</html>
