<style>
  #pm-wrap {
    font-family: 'Segoe UI', system-ui, Arial, sans-serif;
    font-size: 13px;
  }

  /* ── Page title ── */
  .pm-page-title {
    background: #fff;
    border-top: 4px solid #0ea5e9;
    border: 1px solid #e2e8f0;
    border-top-width: 4px;
    border-radius: 10px 10px 0 0;
    padding: 10px 14px;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 12.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .5px;
    color: #0f172a;
    margin-bottom: 0;
    box-shadow: 0 2px 6px rgba(0,0,0,.05);
  }
  .pm-page-title i { color: #0ea5e9; }

  /* ── Body wrapper ── */
  .pm-body {
    background: #f1f5f9;
    border: 1px solid #dde3ec;
    border-top: none;
    border-radius: 0 0 10px 10px;
    padding: 12px;
  }

  /* ── Section panel ── */
  .pm-panel {
    background: #fff;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 1px 6px rgba(0,0,0,.05);
    overflow: hidden;
    margin-bottom: 12px;
  }
  .pm-panel-hdr {
    padding: 9px 14px;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 11.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .5px;
    border-bottom: 1px solid #e2e8f0;
  }
  .pm-panel-hdr.lab {
    background: #f0f9ff;
    border-left: 3px solid #0ea5e9;
    color: #0369a1;
  }
  .pm-panel-hdr.lab i { color: #0ea5e9; }
  .pm-panel-hdr.rad {
    background: #fdf4ff;
    border-left: 3px solid #9333ea;
    color: #6b21a8;
  }
  .pm-panel-hdr.rad i { color: #9333ea; }

  /* ── Table ── */
  .pm-table {
    width: 100%;
    border-collapse: collapse;
  }
  .pm-table thead th {
    background: #f8fafc;
    color: #475569;
    font-size: 10.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .5px;
    padding: 8px 12px;
    border-bottom: 2px solid #e2e8f0;
    border-top: none;
    white-space: nowrap;
  }
  .pm-table tbody td {
    padding: 9px 12px;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: top;
    font-size: 12.5px;
    color: #1e293b;
  }
  .pm-table tbody tr:last-child td { border-bottom: none; }
  .pm-table tbody tr:hover td { background: #f8fafc; }

  .pm-no-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 24px; height: 24px;
    border-radius: 6px;
    background: #e0f2fe;
    color: #0369a1;
    font-size: 11px;
    font-weight: 700;
  }
  .pm-no-badge.rad {
    background: #f3e8ff;
    color: #7e22ce;
  }

  .pm-tgl {
    font-size: 11px;
    font-weight: 700;
    color: #0369a1;
    margin-bottom: 4px;
    display: flex;
    align-items: center;
    gap: 5px;
  }
  .pm-tgl.rad { color: #7e22ce; }

  .pm-item-list {
    margin: 4px 0 0;
    padding-left: 16px;
    color: #334155;
    font-size: 12px;
    line-height: 1.6;
  }

  .pm-lampiran-label {
    font-size: 10.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .4px;
    color: #64748b;
    margin-top: 6px;
    display: block;
  }
  .pm-lampiran-link {
    display: inline-flex;
    align-items: center;
    gap: 5px;
    font-size: 11.5px;
    color: #0369a1;
    text-decoration: none;
    margin-top: 2px;
  }
  .pm-lampiran-link:hover { color: #0ea5e9; text-decoration: underline; }

  /* ── View button ── */
  .pm-btn-view {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 30px; height: 30px;
    border-radius: 7px;
    background: linear-gradient(135deg, #b45309, #f59e0b);
    color: #fff;
    border: none;
    cursor: pointer;
    text-decoration: none;
    font-size: 13px;
    transition: opacity .15s, transform .15s;
  }
  .pm-btn-view:hover { opacity: .85; transform: translateY(-1px); color: #fff; text-decoration: none; }

  /* ── Footer button ── */
  .pm-btn-more {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    width: 100%;
    padding: 9px 16px;
    border-radius: 8px;
    background: linear-gradient(135deg, #15803d, #22c55e);
    color: #fff;
    font-size: 13px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    text-decoration: none;
    transition: opacity .15s, transform .15s;
    margin-top: 4px;
  }
  .pm-btn-more:hover { opacity: .88; transform: translateY(-1px); color: #fff; text-decoration: none; }

  /* ── Empty state ── */
  .pm-empty {
    padding: 14px;
    text-align: center;
    color: #94a3b8;
    font-size: 12px;
  }
  .pm-empty i { font-size: 24px; display: block; margin-bottom: 6px; color: #cbd5e0; }
</style>

<div id="pm-wrap">

  <!-- ── Page Title ── -->
  <div class="pm-page-title">
    <i class="fa fa-flask"></i>
    Riwayat Pemeriksaan Penunjang Medis
    <small style="font-size:10px; color:#64748b; font-weight:400; text-transform:none; letter-spacing:0; margin-left:4px;">(Laboratorium &amp; Radiologi)</small>
  </div>

  <div class="pm-body">

    <!-- ── Laboratorium ── -->
    <div class="pm-panel">
      <div class="pm-panel-hdr lab">
        <i class="fa fa-flask"></i> Laboratorium
      </div>
      <?php
        $no = 0;
        $data_lab = isset($penunjang['laboratorium']) ? $penunjang['laboratorium'] : [];
      ?>
      <?php if(count($data_lab) > 0): ?>
      <table class="pm-table">
        <thead>
          <tr>
            <th width="36px" style="text-align:center;">No</th>
            <th>Pemeriksaan</th>
            <th width="42px" style="text-align:center;">Lihat</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($data_lab as $key_p => $row_p): $no++; ?>
          <tr>
            <td style="text-align:center; vertical-align:middle;">
              <span class="pm-no-badge"><?php echo $no; ?></span>
            </td>
            <td>
              <div class="pm-tgl">
                <i class="fa fa-calendar-o"></i>
                <?php echo $this->tanggal->formatDateTime($row_p->tgl_daftar); ?>
              </div>
              <?php
                $arr_str = explode('|', $row_p->nama_tarif);
                echo '<ul class="pm-item-list">';
                if($row_p->flag_mcu == 1){
                  echo '<li>Medical Check Up</li>';
                }else{
                  foreach($arr_str as $v){ if(!empty($v)) echo '<li>'.$v.'</li>'; }
                }
                echo '</ul>';

                $lampiran_file = isset($file[$row_p->kode_penunjang]) ? $file[$row_p->kode_penunjang] : [];
                if(count($lampiran_file) > 0){
                  echo '<span class="pm-lampiran-label"><i class="fa fa-paperclip"></i> Lampiran</span>';
                  foreach($lampiran_file as $row_lf){
                    echo '<a href="#" class="pm-lampiran-link" onclick="PopupCenter(\''.$row_lf->base_url_dok.'/'.$row_lf->csm_dex_fullpath.'\', \'LAMPIRAN HASIL PEMERIKSAAN LABORATORIUM\', 1000, 850)"><i class="fa fa-file-o"></i> '.$row_lf->csm_dex_nama_dok.'</a><br>';
                  }
                }
              ?>
            </td>
            <td style="text-align:center; vertical-align:middle;">
              <a href="#" class="pm-btn-view" onclick="show_modal_medium_return_json('registration/reg_pasien/form_modal_view_hasil_pm/<?php echo $row_p->no_registrasi?>/<?php echo $row_p->no_kunjungan?>/<?php echo $row_p->kode_penunjang?>/<?php echo $row_p->kode_bagian_tujuan?>?format=html&flag_mcu=<?php echo $row_p->flag_mcu?>', 'Hasil Penunjang Medis')">
                <i class="fa fa-eye"></i>
              </a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <?php else: ?>
        <div class="pm-empty">
          <i class="fa fa-inbox"></i>
          Belum ada data laboratorium
        </div>
      <?php endif; ?>
    </div>

    <!-- ── Radiologi ── -->
    <div class="pm-panel">
      <div class="pm-panel-hdr rad">
        <i class="fa fa-xray" style="display:none"></i><i class="fa fa-picture-o"></i> Radiologi
      </div>
      <?php
        $no = 0;
        $data_fisio = isset($penunjang['radiologi']) ? $penunjang['radiologi'] : [];
      ?>
      <?php if(count($data_fisio) > 0): ?>
      <table class="pm-table">
        <thead>
          <tr>
            <th width="36px" style="text-align:center;">No</th>
            <th>Pemeriksaan</th>
            <th width="42px" style="text-align:center;">Lihat</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach($data_fisio as $key_f => $row_f): if($key_f > 9) break; $no++; ?>
          <tr>
            <td style="text-align:center; vertical-align:middle;">
              <span class="pm-no-badge rad"><?php echo $no; ?></span>
            </td>
            <td>
              <div class="pm-tgl rad">
                <i class="fa fa-calendar-o"></i>
                <?php echo $this->tanggal->formatDateTime($row_f->tgl_daftar); ?>
              </div>
              <?php
                $arr_str = explode('|', $row_f->nama_tarif);
                echo '<ul class="pm-item-list">';
                if($row_f->flag_mcu == 1){
                  echo '<li>Medical Check Up</li>';
                }else{
                  foreach($arr_str as $v){ if(!empty($v)) echo '<li>'.$v.'</li>'; }
                }
                echo '</ul>';

                $lampiran_file_radiologi = isset($file[$row_f->kode_penunjang]) ? $file[$row_f->kode_penunjang] : [];
                if(count($lampiran_file_radiologi) > 0){
                  echo '<span class="pm-lampiran-label"><i class="fa fa-paperclip"></i> Lampiran</span>';
                  foreach($lampiran_file_radiologi as $row_lfr){
                    echo '<a href="#" class="pm-lampiran-link" onclick="PopupCenter(\''.$row_lfr->base_url_dok.'/'.$row_lfr->csm_dex_fullpath.'\', \'LAMPIRAN HASIL PEMERIKSAAN RADIOLOGI\', 1000, 850)"><i class="fa fa-file-o"></i> '.$row_lfr->csm_dex_nama_dok.'</a><br>';
                  }
                }
              ?>
            </td>
            <td style="text-align:center; vertical-align:middle;">
              <a href="#" class="pm-btn-view" onclick="show_modal_medium_return_json('registration/reg_pasien/form_modal_view_hasil_pm/<?php echo $row_f->no_registrasi?>/<?php echo $row_f->no_kunjungan?>/<?php echo $row_f->kode_penunjang?>/<?php echo $row_f->kode_bagian_tujuan?>?format=html&flag_mcu=<?php echo $row_f->flag_mcu?>', 'Hasil Penunjang Medis')">
                <i class="fa fa-eye"></i>
              </a>
            </td>
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <?php else: ?>
        <div class="pm-empty">
          <i class="fa fa-inbox"></i>
          Belum ada data radiologi
        </div>
      <?php endif; ?>
    </div>

    <!-- ── Footer ── -->
    <a href="#" class="pm-btn-more" onclick="show_modal('registration/riwayat_kunjungan_pm/riwayat_kunjungan_pm_by_mr?type=PM&no_mr=<?php echo $no_mr?>', 'Riwayat Penunjang Medis')">
      <i class="fa fa-th-list"></i> Lihat Selengkapnya
    </a>

  </div><!-- /.pm-body -->

</div><!-- /#pm-wrap -->