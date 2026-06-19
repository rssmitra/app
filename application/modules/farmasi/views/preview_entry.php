<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>

<style>
  /* ===== Card / Wrap ===== */
  .pu-wrap { border:1px solid #b8d0e8; border-radius:7px; overflow:hidden; margin-bottom:16px; box-shadow:0 2px 8px rgba(26,79,138,.09); background:#fff; }
  .pu-hdr  { background:linear-gradient(135deg,#1a4f8a 0%,#2c6fad 100%); color:#fff; padding:10px 16px; font-size:13px; font-weight:700; display:flex; align-items:center; gap:9px; justify-content:space-between; }
  .pu-hdr-left  { display:flex; align-items:center; gap:8px; }
  .pu-hdr-right { display:flex; align-items:center; gap:8px; flex-shrink:0; }
  .pu-body { padding:14px 18px; }

  /* ===== Info grid ===== */
  .pu-info-grid  { display:grid; grid-template-columns:repeat(auto-fit, minmax(150px, 1fr)); gap:8px 14px; margin-bottom:14px; }
  .pu-info-item  { display:flex; flex-direction:column; gap:2px; }
  .pu-info-label { font-size:10px; color:#6b8cae; font-weight:600; text-transform:uppercase; letter-spacing:.4px; }
  .pu-info-label i { color:#2c6fad; margin-right:2px; }
  .pu-info-val   { font-size:12px; font-weight:700; color:#1a4f8a; }

  /* ===== Trans reference badge ===== */
  .pv-ref-badge {
    display:inline-flex; align-items:center; gap:5px;
    background:rgba(255,255,255,.18); border:1px solid rgba(255,255,255,.38);
    border-radius:4px; padding:2px 10px; font-size:11px; font-weight:700;
  }

  /* ===== Drug table ===== */
  .pv-tbl { width:100% !important; border-collapse:collapse; font-size:11px; }
  .pv-tbl thead tr { background:#2c6fad; color:#fff; }
  .pv-tbl thead th { padding:6px 8px; text-align:center; font-weight:600; border:1px solid #1e5590; vertical-align:middle; font-size:11px; line-height:1.3; }
  .pv-tbl tbody tr:nth-child(even) { background:#f5f9fd; }
  .pv-tbl tbody tr:hover { background:#e8f0f9; }
  .pv-tbl tbody td { padding:5px 7px; border:1px solid #d0dce8; vertical-align:middle; }
  .pv-racikan td { background:#f0f7ff !important; color:#3b5a80; font-size:10.5px; }

  /* ===== Total band ===== */
  .pv-total-band {
    background:linear-gradient(135deg,#f0f6ff 0%,#e8f0fa 100%);
    border-top:2px solid #1a4f8a;
    padding:10px 16px;
    display:flex; flex-wrap:wrap; justify-content:space-between; align-items:center; gap:8px;
  }
  .pv-total-amt  { font-size:16px; font-weight:800; color:#1a4f8a; }
  .pv-terbilang  { font-size:11px; color:#6b8cae; font-style:italic; margin-top:2px; }

  /* ===== Petugas ===== */
  .pv-staff { display:flex; justify-content:flex-end; padding:10px 0 0; }
  .pv-staff-box { text-align:center; }
  .pv-staff-label { font-size:11px; font-weight:700; color:#2c4a6e; }
  .pv-staff-name  { font-size:12px; font-weight:600; color:#1a3a5c; margin-top:2px; }

  /* ===== Status colors ===== */
  .pv-tagguh { color:#dc2626; font-weight:700; }
  .pv-ok     { color:#15803d; font-weight:700; }
  .pv-retur  { color:#dc2626; font-weight:700; font-size:10.5px; }

  /* ===== Divider ===== */
  .pv-divider { height:1px; background:#e2eaf4; margin:12px 0; }

  /* ===== No data alert ===== */
  .pv-nodata { background:#fef2f2; border:1px solid #fca5a5; border-radius:5px; padding:10px 14px; color:#991b1b; font-size:12px; margin-bottom:16px; }

  /* ===== Action bar ===== */
  .pv-action-bar {
    background:#f8fafc; border:1px solid #e2eaf4; border-radius:7px;
    padding:12px 20px; display:flex; flex-wrap:wrap; gap:8px;
    align-items:center; justify-content:center; margin-bottom:16px;
  }
  .pv-btn {
    display:inline-flex; align-items:center; gap:5px;
    border:none; border-radius:4px; padding:6px 14px;
    font-size:12px; font-weight:600; cursor:pointer;
    text-decoration:none; transition:opacity .15s;
  }
  .pv-btn:hover { opacity:.85; text-decoration:none; }
  .pv-btn-secondary { background:#e2eaf4; color:#2c4a6e !important; }
  .pv-btn-success   { background:#16a34a; color:#fff !important; }
  .pv-btn-primary   { background:#1a4f8a; color:#fff !important; }
  .pv-btn-danger    { background:#dc2626; color:#fff !important; }
  .pv-btn-muted     { background:#64748b; color:#fff !important; }
  .pv-btn-warning   { background:#d97706; color:#fff !important; }

  /* ===== History section ===== */
  .pv-hist-subtitle { font-size:11px; font-weight:400; opacity:.8; display:block; }

  /* ===== Nota btn (in header — small) ===== */
  .pv-nota-btn {
    display:inline-flex; align-items:center; gap:4px;
    background:rgba(255,255,255,.15); color:#fff; border:1px solid rgba(255,255,255,.4);
    border-radius:4px; padding:3px 10px; font-size:11px; font-weight:700; cursor:pointer;
    transition:background .15s;
  }
  .pv-nota-btn:hover { background:rgba(255,255,255,.28); }
</style>

<!-- ── Page Header ── -->
<div class="page-header" style="margin-bottom:14px">
  <h1 style="font-size:18px; color:#1a4f8a; font-weight:700; margin:0; display:flex; align-items:center; gap:8px;">
    <i class="fa fa-file-text" style="font-size:16px"></i>
    <?php echo $title?>
    <small style="font-size:12px; color:#6b8cae; font-weight:400">
      <i class="ace-icon fa fa-angle-double-right"></i>
      <?php echo isset($breadcrumbs) ? $breadcrumbs : ''?>
    </small>
  </h1>
</div>

<div class="row">
<div class="col-xs-12">

  <!-- Hidden inputs -->
  <input type="hidden" name="id_iter"  id="id_iter"  value="<?php echo strtoupper($resep[0]['id_iter'])?>">
  <input type="hidden" name="no_resep" id="no_resep" value="<?php echo isset($resep[0]['kode_pesan_resep']) ? $resep[0]['kode_pesan_resep'] : 0?>">
  <input type="hidden" name="no_mr"    id="no_mr"    value="<?php echo $no_mr?>">

  <div class="row">

  <?php if(count($resep) > 0) : ?>
  <div class="col-xs-<?php echo (count($resep_kronis) > 0) ? 6 : 12 ?>">

    <!-- ===== Card: Transaksi Farmasi (Non-Kronis) ===== -->
    <div class="pu-wrap">
      <div class="pu-hdr">
        <div class="pu-hdr-left">
          <i class="fa fa-list-alt"></i>
          TRANSAKSI FARMASI
        </div>
        <div class="pu-hdr-right">
          <span class="pv-ref-badge">
            <?php echo $kode_trans_far?> &ndash; <?php echo strtoupper($resep[0]['no_resep'])?>.<?php echo strtoupper($resep[0]['id_iter'])?>
          </span>
          <button onclick="PopupCenter('farmasi/Process_entry_resep/nota_farmasi/<?php echo $kode_trans_far; ?>')" class="pv-nota-btn">
            <i class="fa fa-print"></i> Nota
          </button>
        </div>
      </div>
      <div class="pu-body" style="padding-bottom:0">

        <!-- Patient info -->
        <div class="pu-info-grid">
          <div class="pu-info-item">
            <div class="pu-info-label"><i class="fa fa-calendar"></i> Tanggal</div>
            <div class="pu-info-val"><?php echo $this->tanggal->formatDateTime($resep[0]['tgl_trans'])?></div>
          </div>
          <div class="pu-info-item">
            <div class="pu-info-label"><i class="fa fa-user-o"></i> Nama Pasien</div>
            <div class="pu-info-val"><?php echo ucwords($resep[0]['nama_pasien'])?></div>
          </div>
          <div class="pu-info-item">
            <div class="pu-info-label"><i class="fa fa-bookmark-o"></i> No. MR</div>
            <div class="pu-info-val"><?php echo $no_mr?></div>
          </div>
          <div class="pu-info-item">
            <div class="pu-info-label"><i class="fa fa-stethoscope"></i> Dokter</div>
            <div class="pu-info-val"><?php echo ucwords($resep[0]['dokter_pengirim'])?></div>
          </div>
          <div class="pu-info-item">
            <div class="pu-info-label"><i class="fa fa-hospital-o"></i> Unit / Bagian</div>
            <div class="pu-info-val"><?php echo ucwords($resep[0]['nama_bagian'])?></div>
          </div>
        </div>

        <div class="pv-divider"></div>

        <!-- Drug table -->
        <table class="pv-tbl">
          <thead>
            <tr>
              <th width="28">No</th>
              <th>Nama Obat</th>
              <th width="80">Jml Tebus</th>
              <th width="75">Ditangguhkan</th>
              <th width="60">Satuan</th>
              <?php if(count($resep_kronis) == 0) : ?>
              <th width="90">Harga Satuan</th>
              <th width="70">Jasa R</th>
              <?php endif; ?>
              <th width="90">Subtotal</th>
            </tr>
          </thead>
          <tbody>
            <?php
              $no = 0;
              $arr_total = [];
              foreach($resep as $key_dt => $row_dt) :
                if($row_dt['jumlah_tebus'] > 0) {
                  $subtotal = ($row_dt['flag_resep'] == 'racikan')
                    ? $row_dt['jasa_r']
                    : ($row_dt['harga_jual'] * $row_dt['jumlah_tebus']) + $row_dt['jasa_r'];
                } else {
                  $subtotal = 0;
                }
                $arr_total[]              = $subtotal;
                $desc                     = ($row_dt['flag_resep'] == 'racikan') ? 'Jasa Racikan Obat' : $row_dt['nama_brg'];
                $satuan                   = ($row_dt['satuan_kecil'] != null) ? $row_dt['satuan_kecil'] : $row_dt['satuan_brg'];
                $penangguhan_resep        = ($row_dt['resep_ditangguhkan'] == 1) ? 'Ya' : '—';
                $cls_penangguhan          = ($row_dt['resep_ditangguhkan'] == 1) ? 'pv-tagguh' : 'pv-ok';
                $racikan                  = isset($row_dt['racikan'][0]) ? $row_dt['racikan'][0] : [];
                $is_retur                 = ($row_dt['jumlah_retur'] > 0)
                  ? ' <span class="pv-retur">retur ('.$row_dt['jumlah_retur'].')</span>' : '';

                if($row_dt['jumlah_tebus'] > 0) :
                  $no++;
            ?>
            <tr>
              <td style="text-align:center; font-weight:600"><?php echo $no?>.</td>
              <td><?php echo $desc?></td>
              <td style="text-align:center" class="<?php echo $cls_penangguhan?>">
                <?php echo ($row_dt['flag_resep'] == 'racikan') ? $racikan[0]->jml_content : $row_dt['jumlah_tebus']?><?php echo $is_retur?>
              </td>
              <td style="text-align:center" class="<?php echo $cls_penangguhan?>"><?php echo $penangguhan_resep?></td>
              <td style="text-align:center"><?php echo ($row_dt['flag_resep'] == 'racikan') ? $racikan[0]->satuan_racikan : $satuan?></td>
              <?php if(count($resep_kronis) == 0) : ?>
              <td style="text-align:right"><?php echo ($row_dt['flag_resep'] == 'racikan') ? '—' : number_format($row_dt['harga_jual'])?></td>
              <td style="text-align:right"><?php echo number_format($row_dt['jasa_r'])?></td>
              <?php endif; ?>
              <td style="text-align:right; font-weight:700"><?php echo number_format($subtotal)?></td>
            </tr>
            <?php
              if($row_dt['flag_resep'] == 'racikan') :
                foreach ($row_dt['racikan'][0] as $key => $value) {
                  $arr_total[]      = ($value->harga_jual * $value->jumlah);
                  $sub_rac          = ($value->harga_jual * $value->jumlah);
                  $pen_rac          = ($value->resep_ditangguhkan == 1) ? 'Ya' : '—';
                  $is_retur_rac     = ($value->jumlah_retur > 0)
                    ? ' <span class="pv-retur">retur ('.$value->jumlah_retur.')</span>' : '';
                  echo '<tr class="pv-racikan">';
                  echo   '<td style="text-align:center"></td>';
                  echo   '<td style="padding-left:16px">&rsaquo; '.$value->nama_brg.'</td>';
                  echo   '<td style="text-align:center">'.$value->jumlah.$is_retur_rac.'</td>';
                  echo   '<td style="text-align:center">'.$pen_rac.'</td>';
                  echo   '<td style="text-align:center">'.$value->satuan.'</td>';
                  if(count($resep_kronis) == 0) {
                    echo '<td style="text-align:right">'.number_format($value->harga_jual).'</td>';
                    echo '<td style="text-align:right">0</td>';
                  }
                  echo   '<td style="text-align:right">'.number_format($sub_rac).'</td>';
                  echo '</tr>';
                }
              endif;
              endif;
              endforeach;

              if(array_sum($arr_total) == 0) {
                $colspan_nd = (count($resep_kronis) > 0) ? 5 : 7;
                echo "<tr><td colspan='{$colspan_nd}' style='text-align:center; color:#dc2626; font-weight:700; padding:12px 8px;'>Tidak ada Resep Non Kronis</td></tr>";
              }
            ?>
          </tbody>
        </table>

        <!-- Total band -->
        <div class="pv-total-band">
          <div class="pv-terbilang">
            <?php $terbilang = new Kuitansi(); echo '"'.ucwords($terbilang->terbilang(array_sum($arr_total))).' Rupiah"'?>
          </div>
          <div style="text-align:right">
            <div style="font-size:10px; color:#6b8cae; font-weight:600; text-transform:uppercase; letter-spacing:.4px">Total</div>
            <div class="pv-total-amt">Rp <?php echo number_format(array_sum($arr_total))?></div>
          </div>
        </div>

        <!-- Petugas -->
        <div class="pv-staff">
          <div class="pv-staff-box">
            <div class="pv-staff-label"><i class="fa fa-user-circle-o"></i> Petugas</div>
            <div class="pv-staff-name">
              <?php $decode = json_decode($resep[0]['created_by']); echo isset($decode->fullname) ? $decode->fullname : $this->session->userdata('user')->fullname; ?>
            </div>
          </div>
        </div>
        <div style="height:14px"></div>

      </div><!-- /.pu-body -->
    </div><!-- /.pu-wrap -->

  </div><!-- /.col -->

  <?php else: ?>
  <div class="col-xs-12">
    <div class="pv-nodata">
      <i class="fa fa-exclamation-triangle"></i>
      <strong>Perhatian!</strong> Tidak ada data ditampilkan.
      Silahkan lakukan <a href="#" onclick="update_data(<?php echo $kode_trans_far?>, '<?php echo strtolower($flag)?>')">Entry Resep</a>.
    </div>
  </div>
  <?php endif; ?>

  <?php if(count($resep_kronis) > 0) : ?>
  <div class="col-xs-6">

    <!-- ===== Card: Transaksi Farmasi Kronis ===== -->
    <div class="pu-wrap" style="position:relative; overflow:hidden">
      <div class="pu-hdr">
        <div class="pu-hdr-left">
          <i class="fa fa-medkit"></i>
          TRANSAKSI FARMASI KRONIS
        </div>
        <div class="pu-hdr-right">
          <span class="pv-ref-badge">
            RSK-<?php echo $resep_kronis[0]['kode_trans_far']?> &ndash; <?php echo strtoupper($resep_kronis[0]['no_resep'])?>.<?php echo strtoupper($resep[0]['id_iter'])?>
          </span>
          <button onclick="PopupCenter('farmasi/Process_entry_resep/nota_farmasi/<?php echo $kode_trans_far; ?>?tipe=resep_kronis')" class="pv-nota-btn">
            <i class="fa fa-print"></i> Nota
          </button>
        </div>
      </div>
      <div class="pu-body" style="padding-bottom:0">

        <!-- Patient info -->
        <div class="pu-info-grid">
          <div class="pu-info-item">
            <div class="pu-info-label"><i class="fa fa-calendar"></i> Tanggal</div>
            <div class="pu-info-val"><?php echo $this->tanggal->formatDateTime($resep_kronis[0]['tgl_trans'])?></div>
          </div>
          <div class="pu-info-item">
            <div class="pu-info-label"><i class="fa fa-user-o"></i> Nama Pasien</div>
            <div class="pu-info-val"><?php echo ucwords($resep_kronis[0]['nama_pasien'])?> <small style="color:#6b8cae; font-weight:400">(<?php echo $resep_kronis[0]['no_mr']?>)</small></div>
          </div>
          <div class="pu-info-item">
            <div class="pu-info-label"><i class="fa fa-id-card-o"></i> No. SEP</div>
            <div class="pu-info-val"><?php echo $resep_kronis[0]['no_sep']?></div>
          </div>
          <div class="pu-info-item">
            <div class="pu-info-label"><i class="fa fa-stethoscope"></i> Dokter</div>
            <div class="pu-info-val"><?php echo ucwords($resep_kronis[0]['dokter_pengirim'])?></div>
          </div>
          <div class="pu-info-item">
            <div class="pu-info-label"><i class="fa fa-hospital-o"></i> Unit / Bagian</div>
            <div class="pu-info-val"><?php echo ucwords($resep_kronis[0]['nama_bagian'])?></div>
          </div>
          <div class="pu-info-item">
            <div class="pu-info-label"><i class="fa fa-stethoscope"></i> Diagnosa Akhir</div>
            <div class="pu-info-val"><?php echo $resep_kronis[0]['diagnosa_akhir']?></div>
          </div>
        </div>

        <div class="pv-divider"></div>

        <!-- Drug table (kronis) -->
        <table class="pv-tbl">
          <thead>
            <tr>
              <th width="28">No</th>
              <th>Nama Obat</th>
              <th width="80">Jml Tebus</th>
              <th width="75">Ditangguhkan</th>
              <th width="60">Satuan</th>
              <th width="90">Subtotal</th>
            </tr>
          </thead>
          <tbody>
            <?php
              $no = 0;
              $arr_totalkr = [];
              foreach($resep_kronis as $key_dtk => $row_dtkr) :
                $no++;
                if($row_dtkr['jumlah_obat_23'] > 0) :
                  $subtotalkr = ($row_dtkr['flag_resep'] == 'racikan')
                    ? $row_dtkr['jasa_r']
                    : ($row_dtkr['harga_jual'] * $row_dtkr['jumlah_obat_23']) + $row_dtkr['jasa_r'];
                  $arr_totalkr[]       = $subtotalkr;
                  $desc                = ($row_dtkr['flag_resep'] == 'racikan') ? 'Jasa Racikan Obat' : $row_dtkr['nama_brg'];
                  $satuan              = ($row_dtkr['satuan_kecil'] != null) ? $row_dtkr['satuan_kecil'] : $row_dtkr['satuan_brg'];
                  $penangguhan_kronis  = ($row_dtkr['prb_ditangguhkan'] == 1) ? 'Ya' : '—';
                  $cls_penangguhan_kr  = ($row_dtkr['prb_ditangguhkan'] == 1) ? 'pv-tagguh' : 'pv-ok';
            ?>
            <tr>
              <td style="text-align:center; font-weight:600"><?php echo $no?>.</td>
              <td><?php echo $desc?></td>
              <td style="text-align:center" class="<?php echo $cls_penangguhan_kr?>">
                <?php echo ($row_dtkr['flag_resep'] == 'racikan') ? '—' : $row_dtkr['jumlah_obat_23']?>
              </td>
              <td style="text-align:center" class="<?php echo $cls_penangguhan_kr?>"><?php echo $penangguhan_kronis?></td>
              <td style="text-align:center"><?php echo $satuan?></td>
              <td style="text-align:right; font-weight:700"><?php echo number_format($subtotalkr)?></td>
            </tr>
            <?php
              if($row_dtkr['flag_resep'] == 'racikan') :
                foreach ($row_dtkr['racikan'][0] as $key => $valuekr) {
                  $arr_totalkr[] = ($valuekr->harga_jual * $valuekr->jumlah);
                  $sub_rackr     = ($valuekr->harga_jual * $valuekr->jumlah);
                  echo '<tr class="pv-racikan">';
                  echo   '<td style="text-align:center"></td>';
                  echo   '<td style="padding-left:16px">&rsaquo; '.$valuekr->nama_brg.'</td>';
                  echo   '<td style="text-align:center">'.$valuekr->jumlah.'</td>';
                  echo   '<td style="text-align:center">—</td>';
                  echo   '<td style="text-align:center">'.$valuekr->satuan.'</td>';
                  echo   '<td style="text-align:right">'.number_format($sub_rackr).'</td>';
                  echo '</tr>';
                }
              endif;
              endif;
              endforeach;
            ?>
          </tbody>
        </table>

        <!-- Total band (kronis) -->
        <div class="pv-total-band">
          <div class="pv-terbilang">
            <?php $terbilangkr = new Kuitansi(); echo '"'.ucwords($terbilangkr->terbilang(array_sum($arr_totalkr))).' Rupiah"'?>
          </div>
          <div style="text-align:right">
            <div style="font-size:10px; color:#6b8cae; font-weight:600; text-transform:uppercase; letter-spacing:.4px">Total</div>
            <div class="pv-total-amt">Rp <?php echo number_format(array_sum($arr_totalkr))?></div>
          </div>
        </div>

        <!-- Petugas -->
        <div class="pv-staff">
          <div class="pv-staff-box">
            <div class="pv-staff-label"><i class="fa fa-user-circle-o"></i> Petugas</div>
            <div class="pv-staff-name">
              <?php $decode = json_decode($resep[0]['created_by']); echo isset($decode->fullname) ? $decode->fullname : $this->session->userdata('user')->fullname; ?>
            </div>
          </div>
        </div>
        <div style="height:14px"></div>

      </div><!-- /.pu-body -->

      <!-- Watermark stamp -->
      <div style="position:absolute; top:50%; left:50%; transform:translate(-50%,-50%) rotate(-25deg); pointer-events:none; opacity:.06; font-size:36px; font-weight:900; color:#16a34a; text-transform:uppercase; letter-spacing:4px; white-space:nowrap">
        RESEP KRONIS
      </div>
    </div><!-- /.pu-wrap -->

  </div><!-- /.col -->
  <?php endif; ?>

  </div><!-- /.inner row -->

  <!-- ===== Action Bar ===== -->
  <div class="pv-action-bar">
    <button onclick="getMenu('farmasi/Retur_obat');" class="pv-btn pv-btn-secondary">
      <i class="fa fa-history"></i> Kembali ke Riwayat Resep
    </button>
    <button onclick="getMenu('farmasi/Etiket_obat/form_copy_resep/<?php echo $kode_trans_far; ?>?flag=<?php echo $flag; ?>')" class="pv-btn pv-btn-success">
      <i class="fa fa-copy"></i> Cetak Copy Resep
    </button>
    <button onclick="getMenu('farmasi/Etiket_obat/form/<?php echo $kode_trans_far; ?>?flag=<?php echo $flag; ?>')" class="pv-btn pv-btn-primary">
      <i class="fa fa-ticket"></i> Cetak Etiket Obat
    </button>
    <?php if($status_lunas == 0) : ?>
    <button onclick="rollback_by_kode_trans_far(<?php echo $kode_trans_far; ?>, '<?php echo strtolower($flag); ?>')" class="pv-btn pv-btn-danger">
      <i class="fa fa-undo"></i> Revisi Data Resep
    </button>
    <?php endif; ?>
    <button onclick="print_tracer(<?php echo $kode_trans_far; ?>)" class="pv-btn pv-btn-muted">
      <i class="fa fa-print"></i> Kirim Tracer ke Gudang
    </button>
  </div>

</div><!-- /.col-xs-12 -->
</div><!-- /.row -->

<?php if($no_mr != 0) : ?>
<div class="row" style="margin-top:4px">
<div class="col-xs-12">
  <div class="pu-wrap">
    <div class="pu-hdr" style="flex-direction:column; align-items:flex-start; gap:2px;">
      <div style="display:flex; align-items:center; gap:8px; font-size:13px; font-weight:700;">
        <i class="fa fa-history"></i> RIWAYAT PESAN RESEP FARMASI
      </div>
      <span class="pv-hist-subtitle">Data pemesanan resep 3 bulan terakhir pasien ini</span>
    </div>
    <div class="pu-body" style="padding:12px 14px">
      <table id="riwayat_pesan_resep_pasien" base-url="farmasi/Retur_obat/get_data?flag=All&no_mr=<?php echo $no_mr; ?>" class="table table-bordered table-hover">
        <thead>
          <tr>
            <th class="center">No</th>
            <th></th>
            <th>Kode</th>
            <th>Kode</th>
            <th>No Resep</th>
            <th>Tgl Pesan</th>
            <th>No Mr</th>
            <th>Nama Pasien</th>
            <th>Nama Dokter</th>
            <th>Penjamin</th>
            <th>Status</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>
  </div>
</div>
</div>
<?php endif; ?>

<script type="text/javascript">
$(document).ready(function(){
  table = $('#riwayat_pesan_resep_pasien').DataTable({
    "processing": true,
    "serverSide": true,
    "bInfo": false,
    "bPaginate": false,
    "searching": false,
    "bSort": false,
    "ajax": {
      "url": $('#riwayat_pesan_resep_pasien').attr('base-url'),
      "type": "POST"
    },
    "columnDefs": [
      { "visible": true,  "targets": [0] },
      { "visible": true,  "targets": [1] },
      { "visible": false, "targets": [2] }
    ]
  });

  $('#riwayat_pesan_resep_pasien tbody').on('click', 'tr', function(){
    if($(this).hasClass('selected')) {
      $(this).removeClass('selected');
    } else {
      table.$('tr.selected').removeClass('selected');
      $(this).addClass('selected');
    }
  });
});

function rollback_by_kode_trans_far(id, flag){
  Swal.fire({
    icon: 'warning',
    title: 'Konfirmasi Revisi',
    html: 'Data resep akan dikembalikan ke status <b>sebelum</b> diproses.<br>Lanjutkan revisi data resep?',
    showCancelButton: true,
    confirmButtonText: '<i class="fa fa-undo"></i>&nbsp; Ya, Revisi',
    cancelButtonText: 'Batal',
    confirmButtonColor: '#dc2626',
    cancelButtonColor: '#6b8cae'
  }).then(function(result){
    if(result.isConfirmed){
      $.ajax({
        url: 'farmasi/process_entry_resep/rollback_by_kode_trans_far',
        type: 'post',
        data: { ID: id, id_iter: $('#id_iter').val() },
        dataType: 'json',
        beforeSend: function(){ achtungShowLoader(); },
        complete: function(xhr){
          var jsonResponse = JSON.parse(xhr.responseText);
          if(jsonResponse.status === 200){
            $.achtung({ message: jsonResponse.message, timeout: 5 });
            if(flag == 'rj' || flag == 'ri'){
              $('#page-area-content').load('farmasi/Entry_resep_ri_rj/form/'+$('#no_resep').val()+'?mr='+$('#no_mr').val()+'&tipe_layanan='+flag+'&rollback=true');
            }
            if(flag == 'rl' || flag == 'pb' || flag == 'rk'){
              $('#page-area-content').load('farmasi/Entry_resep_ri_rj/form_create?jenis_resep='+flag+'&rollback=true&kode_trans_far='+id+'&mr='+$('#no_mr').val());
            }
            if(flag == 'itr'){
              $('#page-area-content').load('farmasi/Pengambilan_resep_iter/form/'+jsonResponse.iter+'?id_iter='+$('#id_iter').val()+'&flag=RJ');
            }
          } else {
            $.achtung({ message: jsonResponse.message, timeout: 5 });
          }
          achtungHideLoader();
        }
      });
    }
  });
}

function update_data(kode_trans_far, jenis_resep){
  $('#page-area-content').load('farmasi/Entry_resep_ri_rj/form_create?jenis_resep='+jenis_resep);
}

function print_tracer(kode_trans_far){
  PopupCenter('farmasi/Process_entry_resep/print_tracer_gudang_view/'+kode_trans_far, 'TRACER GUDANG FARMASI', 500, 600);
}
</script>
