<?php if(isset($_GET['print'])) : ?>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.css" />
<?php endif; ?>

<style>
  /* ── Scoped sidebar resume ─────────────────────────────── */
  .rms-wrap { font-family: 'Segoe UI', system-ui, Arial, sans-serif; font-size: 12px; }

  /* Patient header */
  .rms-patient {
    background: linear-gradient(135deg, #f0f9ff 0%, #e0f2fe 100%);
    border: 1px solid #bae6fd;
    border-radius: 8px;
    padding: 10px 12px;
    margin-bottom: 10px;
  }
  .rms-patient-name { font-size: 13px; font-weight: 700; color: #0f172a; margin-bottom: 3px; }
  .rms-patient-detail { font-size: 11px; color: #475569; line-height: 1.6; }
  .rms-patient-detail i { color: #0ea5e9; width: 13px; text-align: center; margin-right: 3px; }
  .rms-patient-actions { margin-top: 8px; }
  .rms-btn-print {
    display: inline-flex; align-items: center; gap: 5px;
    padding: 4px 10px; border-radius: 5px;
    background: #0f172a; color: #fff;
    font-size: 10px; font-weight: 600;
    text-decoration: none; border: none;
    transition: opacity .18s;
  }
  .rms-btn-print:hover { opacity: .8; color: #fff; text-decoration: none; }

  /* Section accordion */
  .rms-section { margin-bottom: 8px; border: 1px solid #e2e8f0; border-radius: 8px; overflow: hidden; background: #fff; }
  .rms-section-hdr {
    background: #f8fafc;
    border-bottom: 1px solid #e2e8f0;
    padding: 8px 12px;
    font-size: 11px; font-weight: 700; color: #0f172a;
    display: flex; align-items: center; gap: 6px;
    cursor: pointer;
    transition: background .15s;
    user-select: none;
  }
  .rms-section-hdr:hover { background: #f1f5f9; }
  .rms-section-hdr i.rms-icon { color: #0ea5e9; width: 14px; text-align: center; }
  .rms-section-hdr .rms-chevron {
    margin-left: auto; color: #94a3b8;
    transition: transform .2s;
    font-size: 11px;
  }
  .rms-section.rms-collapsed .rms-section-body { display: none; }
  .rms-section.rms-collapsed .rms-chevron { transform: rotate(-90deg); }
  .rms-section-body { padding: 10px 12px; }

  /* SOAP timeline */
  .rms-soap-item {
    position: relative;
    padding: 0 0 12px 16px;
    border-left: 2px solid #e2e8f0;
    margin-left: 4px;
  }
  .rms-soap-item:last-child { border-left-color: transparent; padding-bottom: 0; }
  .rms-soap-item::before {
    content: '';
    position: absolute; left: -5px; top: 2px;
    width: 8px; height: 8px;
    border-radius: 50%;
    background: #0ea5e9;
    border: 2px solid #fff;
    box-shadow: 0 0 0 1px #bae6fd;
  }
  .rms-soap-date {
    font-size: 10px; font-weight: 700; color: #0ea5e9;
    margin-bottom: 5px;
  }
  .rms-soap-grid { display: flex; flex-direction: column; gap: 5px; }
  .rms-soap-tag {
    display: inline-block;
    font-size: 9px; font-weight: 700;
    padding: 1px 6px; border-radius: 3px;
    letter-spacing: 0.5px;
    margin-right: 4px; vertical-align: middle;
  }
  .rms-tag-s { background: #dbeafe; color: #1d4ed8; }
  .rms-tag-o { background: #dcfce7; color: #15803d; }
  .rms-tag-a { background: #fef9c3; color: #854d0e; }
  .rms-tag-p { background: #fce7f3; color: #9d174d; }
  .rms-soap-val { font-size: 11px; color: #334155; line-height: 1.5; }

  /* TTV mini cards */
  .rms-ttv-row {
    display: flex; flex-wrap: wrap; gap: 4px;
    margin: 4px 0 6px;
  }
  .rms-ttv-chip {
    font-size: 10px; padding: 2px 7px;
    border-radius: 4px; font-weight: 600;
    background: #f1f5f9; color: #475569;
    border: 1px solid #e2e8f0;
    white-space: nowrap;
  }
  .rms-ttv-chip b { color: #0f172a; }

  /* Compact table */
  .rms-table {
    width: 100%; border-collapse: collapse;
    font-size: 11px;
  }
  .rms-table th {
    background: #f1f5f9;
    color: #475569;
    font-weight: 700;
    font-size: 10px;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    padding: 6px 8px;
    border-bottom: 2px solid #e2e8f0;
    text-align: left;
  }
  .rms-table td {
    padding: 5px 8px;
    border-bottom: 1px solid #f1f5f9;
    color: #334155;
    vertical-align: top;
  }
  .rms-table tr:hover td { background: #f8fafc; }
  .rms-table .rms-total td {
    font-weight: 700; color: #0f172a;
    border-top: 2px solid #e2e8f0;
    background: #f8fafc;
  }

  /* Penunjang badges */
  .rms-pm-item {
    display: flex; align-items: center; gap: 6px;
    padding: 6px 0;
    border-bottom: 1px solid #f1f5f9;
  }
  .rms-pm-item:last-child { border-bottom: none; }
  .rms-pm-badge {
    font-size: 9px; font-weight: 700; padding: 2px 7px;
    border-radius: 4px; color: #fff;
    white-space: nowrap; flex-shrink: 0;
  }
  .rms-pm-badge-lab  { background: #ef4444; }
  .rms-pm-badge-rad  { background: #f59e0b; }
  .rms-pm-badge-fisio{ background: #06b6d4; }
  .rms-pm-badge-def  { background: #6b7280; }
  .rms-pm-info { flex: 1; min-width: 0; }
  .rms-pm-name {
    font-size: 11px; font-weight: 600; color: #0369a1;
    cursor: pointer; text-decoration: none;
  }
  .rms-pm-name:hover { color: #0ea5e9; text-decoration: underline; }
  .rms-pm-date { font-size: 10px; color: #94a3b8; }

  /* Footer info */
  .rms-footer-info {
    display: flex; flex-wrap: wrap; gap: 4px 12px;
    padding: 8px 0; font-size: 11px; color: #475569;
  }
  .rms-footer-info b { color: #0f172a; font-weight: 600; }

  /* No data */
  .rms-nodata {
    text-align: center; padding: 12px;
    color: #94a3b8; font-size: 11px; font-style: italic;
  }
</style>

<div class="rms-wrap">

  <!-- Patient Header -->
  <div class="rms-patient">
    <div class="rms-patient-name">
      <?php echo strtoupper($result['registrasi']->nama_pasien)?>
      <span style="font-weight:500;color:#64748b;font-size:11px">(<?php echo $result['registrasi']->no_mr?>)</span>
    </div>
    <div class="rms-patient-detail">
      <div><i class="fa fa-calendar-o"></i> <?php echo $this->tanggal->formatDateTimeFormDmy($result['registrasi']->tgl_jam_masuk)?> &bull; No. <?php echo strtoupper($result['registrasi']->no_registrasi)?></div>
      <div><i class="fa fa-birthday-cake"></i> <?php echo $this->tanggal->formatDate($result['registrasi']->tgl_lhr)?> (<?php echo $umur?> Thn)</div>
      <div><i class="fa fa-map-marker"></i> <?php echo $result['registrasi']->almt_ttp_pasien?></div>
    </div>
    <?php if(!isset($_GET['print'])) :?>
    <div class="rms-patient-actions">
      <a href="<?php echo base_url().'registration/reg_pasien/view_detail_resume_medis/'.$result['registrasi']->no_registrasi.'?print=true'?>" class="rms-btn-print" target="_blank">
        <i class="fa fa-print"></i> Cetak Resume
      </a>
    </div>
    <?php endif;?>
  </div>

  <!-- RESUME MEDIS (SOAP) -->
  <div class="rms-section">
    <div class="rms-section-hdr" onclick="$(this).closest('.rms-section').toggleClass('rms-collapsed')">
      <i class="fa fa-file-text-o rms-icon"></i> Resume Medis (SOAP)
      <i class="fa fa-chevron-down rms-chevron"></i>
    </div>
    <div class="rms-section-body">
      <?php if(count($result['riwayat_medis']) > 0) : ?>
        <?php foreach($result['riwayat_medis'] as $row_rm) :?>
        <div class="rms-soap-item">
          <div class="rms-soap-date">
            <i class="fa fa-clock-o"></i> <?php echo $this->tanggal->formatDateTime($row_rm->tgl_masuk)?>
          </div>
          <div class="rms-soap-grid">
            <!-- TTV -->
            <?php if($row_rm->tinggi_badan || $row_rm->tekanan_darah || $row_rm->nadi || $row_rm->berat_badan || $row_rm->suhu) :?>
            <div class="rms-ttv-row">
              <?php if($row_rm->tinggi_badan):?><span class="rms-ttv-chip"><b>TB</b> <?php echo $row_rm->tinggi_badan?></span><?php endif;?>
              <?php if($row_rm->berat_badan):?><span class="rms-ttv-chip"><b>BB</b> <?php echo $row_rm->berat_badan?></span><?php endif;?>
              <?php if($row_rm->tekanan_darah):?><span class="rms-ttv-chip"><b>TD</b> <?php echo $row_rm->tekanan_darah?></span><?php endif;?>
              <?php if($row_rm->nadi):?><span class="rms-ttv-chip"><b>Nadi</b> <?php echo $row_rm->nadi?></span><?php endif;?>
              <?php if($row_rm->suhu):?><span class="rms-ttv-chip"><b>Suhu</b> <?php echo $row_rm->suhu?></span><?php endif;?>
            </div>
            <?php endif;?>

            <?php if($row_rm->anamnesa):?>
            <div>
              <span class="rms-soap-tag rms-tag-s">S</span>
              <span class="rms-soap-val"><?php echo ucfirst($row_rm->anamnesa)?></span>
            </div>
            <?php endif;?>

            <?php if($row_rm->pemeriksaan):?>
            <div>
              <span class="rms-soap-tag rms-tag-o">O</span>
              <span class="rms-soap-val"><?php echo ucfirst($row_rm->pemeriksaan)?></span>
            </div>
            <?php endif;?>

            <?php if($row_rm->diagnosa_akhir || $row_rm->diagnosa_sekunder || $row_rm->text_icd9):?>
            <div>
              <span class="rms-soap-tag rms-tag-a">A</span>
              <span class="rms-soap-val">
                <?php if($row_rm->diagnosa_akhir):?>
                  <?php echo ucfirst($row_rm->diagnosa_akhir)?>
                <?php endif;?>
                <?php if($row_rm->diagnosa_sekunder):?>
                  <br><span style="color:#94a3b8;font-size:10px">Sek:</span> <?php echo ucfirst(str_replace('|',' ',$row_rm->diagnosa_sekunder))?>
                <?php endif;?>
                <?php if($row_rm->text_icd9):?>
                  <br><span style="color:#94a3b8;font-size:10px">ICD9:</span> <?php echo ucfirst($row_rm->text_icd9)?>
                <?php endif;?>
              </span>
            </div>
            <?php endif;?>

            <?php if($row_rm->pengobatan || $row_rm->tgl_kontrol_kembali):?>
            <div>
              <span class="rms-soap-tag rms-tag-p">P</span>
              <span class="rms-soap-val">
                <?php echo nl2br($row_rm->pengobatan)?>
                <?php if($row_rm->tgl_kontrol_kembali):?>
                  <br><span style="color:#94a3b8;font-size:10px">Kontrol:</span> <?php echo $this->tanggal->formatDate($row_rm->tgl_kontrol_kembali)?>
                <?php endif;?>
              </span>
            </div>
            <?php endif;?>
          </div>
        </div>
        <?php endforeach;?>
      <?php else:?>
        <div class="rms-nodata">Tidak ada data resume medis</div>
      <?php endif;?>

      <div class="rms-footer-info">
        <span><b>Keluar:</b> <?php echo ucfirst($result['registrasi']->cara_keluar_pasien)?></span>
        <span><b>Pasca:</b> <?php echo ucfirst($result['registrasi']->pasca_pulang)?></span>
      </div>
    </div>
  </div>

  <!-- PENUNJANG MEDIS -->
  <?php if(isset($penunjang[$no_registrasi]) AND count($penunjang[$no_registrasi]) > 0) :?>
  <div class="rms-section">
    <div class="rms-section-hdr" onclick="$(this).closest('.rms-section').toggleClass('rms-collapsed')">
      <i class="fa fa-flask rms-icon"></i> Penunjang Medis
      <span style="margin-left:4px;font-size:10px;color:#64748b;font-weight:500">(<?php echo count($penunjang[$no_registrasi])?>)</span>
      <i class="fa fa-chevron-down rms-chevron"></i>
    </div>
    <div class="rms-section-body" style="padding:6px 12px">
      <?php
        $result_pm = $penunjang[$no_registrasi];
        foreach($result_pm as $row_pm) :
          switch ($row_pm->kode_bagian_tujuan) {
            case '050101':
              $type_pm = 'LAB'; $badge_class = 'rms-pm-badge-lab'; break;
            case '050201':
              $type_pm = 'RAD'; $badge_class = 'rms-pm-badge-rad'; break;
            case '050301':
              $type_pm = 'FISIO'; $badge_class = 'rms-pm-badge-fisio'; break;
            default:
              $type_pm = 'PM'; $badge_class = 'rms-pm-badge-def'; break;
          }
      ?>
      <div class="rms-pm-item">
        <span class="rms-pm-badge <?php echo $badge_class?>"><?php echo $type_pm?></span>
        <div class="rms-pm-info">
          <a href="#" class="rms-pm-name" onclick="PopupCenter('<?php echo base_url()?>Templates/Export_data/export?type=pdf&flag=<?php echo $type_pm?>&noreg=<?php echo $row_pm->no_registrasi?>&pm=<?php echo $row_pm->kode_penunjang?>&kode_pm=<?php echo $row_pm->kode_bagian_tujuan?>&no_kunjungan=<?php echo $row_pm->no_kunjungan?>', 'Hasil Penunjang Medis', 850, 650); return false;"><?php echo $row_pm->nama_bagian?></a>
          <div class="rms-pm-date"><?php echo $this->tanggal->formatDateTime($row_pm->tgl_masuk)?></div>
        </div>
      </div>
      <?php endforeach; ?>
    </div>
  </div>
  <?php endif; ?>

  <!-- BILLING -->
  <div class="rms-section rms-collapsed">
    <div class="rms-section-hdr" onclick="$(this).closest('.rms-section').toggleClass('rms-collapsed')">
      <i class="fa fa-money rms-icon"></i> Billing Pasien
      <span style="margin-left:4px;font-size:10px;color:#64748b;font-weight:500">(<?php echo count($result['tindakan'])?>)</span>
      <i class="fa fa-chevron-down rms-chevron"></i>
    </div>
    <div class="rms-section-body" style="padding:0;overflow-x:auto">
      <table class="rms-table">
        <thead>
          <tr>
            <th style="width:20px">#</th>
            <th>Item</th>
            <th style="text-align:right;width:70px">Total</th>
          </tr>
        </thead>
        <tbody>
          <?php
            $arr_total = [];
            $no = 0;
            foreach($result['tindakan'] as $row_t) : $no++;
              $arr_total[] = $row_t->total;
          ?>
          <tr>
            <td style="text-align:center"><?php echo $no?></td>
            <td>
              <div style="font-weight:600;color:#0f172a;font-size:11px"><?php echo $row_t->nama_tindakan?></div>
              <?php
                if($row_t->flag_resep == 'racikan') {
                  $child_racikan = $this->master->get_child_racikan_farmasi($row_t->kode_trans_far);
                  if($child_racikan) echo '<div style="font-size:10px;color:#94a3b8;margin-top:2px">'.$child_racikan.'</div>';
                }
              ?>
              <div style="font-size:10px;color:#94a3b8"><?php echo $row_t->jenis_tindakan?> &bull; <?php echo $row_t->nama_bagian?></div>
            </td>
            <td style="text-align:right;font-weight:600;white-space:nowrap"><?php echo number_format($row_t->total)?></td>
          </tr>
          <?php endforeach;?>
        </tbody>
        <tfoot>
          <tr class="rms-total">
            <td colspan="2" style="text-align:right">Total</td>
            <td style="text-align:right;white-space:nowrap"><?php echo number_format(array_sum($arr_total))?></td>
          </tr>
        </tfoot>
      </table>
    </div>
  </div>

  <!-- FARMASI -->
  <div class="rms-section rms-collapsed">
    <div class="rms-section-hdr" onclick="$(this).closest('.rms-section').toggleClass('rms-collapsed')">
      <i class="fa fa-medkit rms-icon"></i> Instalasi Farmasi
      <span style="margin-left:4px;font-size:10px;color:#64748b;font-weight:500">(<?php echo count($result['farmasi'])?>)</span>
      <i class="fa fa-chevron-down rms-chevron"></i>
    </div>
    <div class="rms-section-body" style="padding:0;overflow-x:auto">
      <?php if(count($result['farmasi']) > 0) :?>
      <table class="rms-table">
        <thead>
          <tr>
            <th style="width:20px">#</th>
            <th>Obat</th>
            <th style="text-align:center;width:40px">Jml</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 0; foreach($result['farmasi'] as $row_t) : $no++; ?>
          <tr>
            <td style="text-align:center"><?php echo $no?></td>
            <td>
              <div style="font-weight:600;color:#0f172a;font-size:11px"><?php echo $row_t->nama_brg?></div>
              <?php
                if($row_t->flag_resep == 'racikan') {
                  $child_racikan = $this->master->get_child_racikan_farmasi($row_t->kode_trans_far);
                  if($child_racikan) echo '<div style="font-size:10px;color:#94a3b8;margin-top:2px">'.$child_racikan.'</div>';
                }
              ?>
              <div style="font-size:10px;color:#94a3b8"><?php echo $row_t->dosis_per_hari?> x <?php echo $row_t->dosis_obat?> <?php echo $row_t->satuan_obat?> <?php echo $row_t->anjuran_pakai?></div>
            </td>
            <td style="text-align:center;font-weight:600">
              <?php
                $jml = ($row_t->jumlah_tebus > 0) ? (int)$row_t->jumlah_tebus : (($row_t->jumlah_obat_23 > 0) ? (int)$row_t->jumlah_obat_23 : '-');
                echo $jml;
              ?>
              <div style="font-size:9px;color:#94a3b8"><?php echo $row_t->satuan_kecil?></div>
            </td>
          </tr>
          <?php endforeach;?>
        </tbody>
      </table>
      <?php else:?>
        <div class="rms-nodata">Tidak ada data farmasi</div>
      <?php endif;?>
    </div>
  </div>

</div>
