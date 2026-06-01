<style>
  #prj-wrap {
    font-family: 'Segoe UI', system-ui, Arial, sans-serif;
    font-size: 13px;
  }

  /* ── Page title ── */
  .prj-page-title {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-top: 4px solid #7c3aed;
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
  .prj-page-title i { color: #7c3aed; }
  .prj-page-title small {
    font-size: 10px;
    color: #64748b;
    font-weight: 400;
    text-transform: none;
    letter-spacing: 0;
    margin-left: 4px;
  }

  /* ── Body wrapper ── */
  .prj-body {
    background: #f5f3ff;
    border: 1px solid #dde3ec;
    border-top: none;
    border-radius: 0 0 10px 10px;
    padding: 12px;
  }

  /* ── Panel ── */
  .prj-panel {
    background: #fff;
    border-radius: 10px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 1px 6px rgba(0,0,0,.05);
    overflow: hidden;
    margin-bottom: 12px;
  }
  .prj-panel-hdr {
    padding: 9px 14px;
    display: flex;
    align-items: center;
    gap: 8px;
    font-size: 11.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .5px;
    border-bottom: 1px solid #e2e8f0;
    background: #f5f3ff;
    border-left: 3px solid #7c3aed;
    color: #4c1d95;
  }
  .prj-panel-hdr i { color: #7c3aed; }

  /* ── Table ── */
  .prj-table {
    width: 100%;
    border-collapse: collapse;
  }
  .prj-table thead th {
    background: #f8fafc;
    color: #475569;
    font-size: 10.5px;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: .5px;
    padding: 8px 10px;
    border-bottom: 2px solid #e2e8f0;
    border-top: none;
    white-space: nowrap;
  }
  .prj-table tbody td {
    padding: 9px 10px;
    border-bottom: 1px solid #f1f5f9;
    vertical-align: middle;
    font-size: 12px;
    color: #1e293b;
  }
  .prj-table tbody tr:last-child td { border-bottom: none; }
  .prj-table tbody tr:hover td { background: #faf5ff; }

  /* ── No badge ── */
  .prj-no-badge {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 24px; height: 24px;
    border-radius: 6px;
    background: #ede9fe;
    color: #6d28d9;
    font-size: 11px;
    font-weight: 700;
  }

  /* ── Date block ── */
  .prj-date-main {
    font-size: 12.5px;
    font-weight: 700;
    color: #4c1d95;
    display: flex;
    align-items: center;
    gap: 5px;
    margin-bottom: 2px;
  }
  .prj-date-main i { color: #7c3aed; font-size: 11px; }
  .prj-poli {
    font-size: 12px;
    font-weight: 600;
    color: #1e293b;
    display: flex;
    align-items: center;
    gap: 4px;
    margin-bottom: 1px;
  }
  .prj-poli i { color: #94a3b8; font-size: 11px; }
  .prj-dokter {
    font-size: 11.5px;
    color: #475569;
    display: flex;
    align-items: center;
    gap: 4px;
  }
  .prj-dokter i { color: #94a3b8; font-size: 11px; }
  .prj-penjamin {
    display: inline-block;
    font-size: 10.5px;
    color: #64748b;
    background: #f1f5f9;
    border-radius: 4px;
    padding: 1px 6px;
    margin-top: 4px;
  }

  /* ── Status badge ── */
  .prj-badge {
    display: inline-flex;
    align-items: center;
    gap: 4px;
    font-size: 10.5px;
    font-weight: 700;
    padding: 3px 8px;
    border-radius: 20px;
    white-space: nowrap;
  }
  .prj-badge.terdaftar {
    background: #dcfce7;
    color: #15803d;
    border: 1px solid #bbf7d0;
  }
  .prj-badge.belum {
    background: #fff7ed;
    color: #c2410c;
    border: 1px solid #fed7aa;
  }
  .prj-badge.cancelled {
    background: #fef2f2;
    color: #991b1b;
    border: 1px solid #fecaca;
  }

  /* ── Flag badge ── */
  .prj-flag {
    display: inline-flex;
    align-items: center;
    gap: 3px;
    font-size: 9.5px;
    font-weight: 700;
    padding: 2px 6px;
    border-radius: 4px;
    margin-top: 3px;
    text-transform: uppercase;
    letter-spacing: .4px;
  }
  .prj-flag.rj   { background: #e0f2fe; color: #0369a1; }
  .prj-flag.bdh  { background: #fef9c3; color: #854d0e; }
  .prj-flag.hd   { background: #fce7f3; color: #9d174d; }

  /* ── Code ── */
  .prj-kode {
    font-size: 12px;
    font-weight: 700;
    color: #7c3aed;
    font-family: monospace;
    letter-spacing: .3px;
  }

  /* ── Footer button ── */
  .prj-btn-more {
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 7px;
    width: 100%;
    padding: 9px 16px;
    border-radius: 8px;
    background: linear-gradient(135deg, #5b21b6, #7c3aed);
    color: #fff;
    font-size: 13px;
    font-weight: 600;
    border: none;
    cursor: pointer;
    text-decoration: none;
    transition: opacity .15s, transform .15s;
    margin-top: 4px;
  }
  .prj-btn-more:hover { opacity: .88; transform: translateY(-1px); color: #fff; text-decoration: none; }

  /* ── Empty state ── */
  .prj-empty {
    padding: 20px 14px;
    text-align: center;
    color: #94a3b8;
    font-size: 12px;
  }
  .prj-empty i { font-size: 28px; display: block; margin-bottom: 8px; color: #c4b5fd; }
  .prj-empty span { display: block; font-size: 11.5px; }
</style>

<div id="prj-wrap">

  <!-- ── Page Title ── -->
  <div class="prj-page-title">
    <i class="fa fa-calendar-check-o"></i>
    Riwayat Perjanjian Pasien
    <small>(2 tahun terakhir, maks. 15 data)</small>
  </div>

  <div class="prj-body">

    <div class="prj-panel">
      <div class="prj-panel-hdr">
        <i class="fa fa-list-alt"></i> Daftar Perjanjian
      </div>

      <?php
        $result = isset($result) ? $result : [];
        $no = 0;
      ?>

      <?php if(count($result) > 0): ?>
      <table class="prj-table">
        <thead>
          <tr>
            <th width="34px" style="text-align:center;">No</th>
            <th>Tanggal &amp; Klinik</th>
            <!-- <th>Kode Perjanjian</th>
            <th style="text-align:center;">Status</th> -->
          </tr>
        </thead>
        <tbody>
          <?php foreach($result as $row): $no++; ?>
          <?php
            // Determine status
            if(!empty($row->status_batal)):
              $status_html = '<span class="prj-badge cancelled"><i class="fa fa-times-circle"></i> Batal</span>';
            elseif(!empty($row->tgl_masuk)):
              $status_html = '<span class="prj-badge terdaftar"><i class="fa fa-check-circle"></i> Terdaftar</span>';
            else:
              $status_html = '<span class="prj-badge belum"><i class="fa fa-clock-o"></i> Belum Daftar</span>';
            endif;

            // Determine flag label
            if(!empty($row->flag) && strtoupper($row->flag) === 'BEDAH'):
              $flag_html = '<span class="prj-flag bdh"><i class="fa fa-cut"></i> Bedah</span>';
            elseif(!empty($row->flag) && strtoupper($row->flag) === 'HD'):
              $flag_html = '<span class="prj-flag hd"><i class="fa fa-tint"></i> Hemodialisa</span>';
            else:
              $flag_html = '<span class="prj-flag rj"><i class="fa fa-stethoscope"></i> Rawat Jalan</span>';
            endif;

            // Format date
            $tgl_display = !empty($row->tgl_pesanan)
              ? date('d/m/Y', strtotime($row->tgl_pesanan))
              : '-';

            $penjamin = (!empty($row->nama_perusahaan)) ? strtoupper($row->nama_perusahaan) : 'PRIBADI / UMUM';
            $nama_poli = (!empty($row->nama_bagian)) ? strtoupper($row->nama_bagian) : $row->no_poli;
            $nama_dr = (!empty($row->nama_dr)) ? strtoupper($row->nama_dr) : '-';
          ?>
          <tr>
            <td style="text-align:center;">
              <span class="prj-no-badge"><?php echo $no; ?></span>
            </td>
            <td>
              <div class="prj-date-main">
                <i class="fa fa-calendar"></i>
                <?php echo $tgl_display; ?>
              </div>
              <div class="prj-poli">
                <i class="fa fa-hospital-o"></i>
                <?php echo $nama_poli; ?>
              </div>
              <div class="prj-dokter">
                <i class="fa fa-user-md"></i>
                <?php echo $nama_dr; ?>
              </div>
              <span class="prj-penjamin"><?php echo $penjamin; ?></span>
              <?php echo $flag_html; ?>
            </td>
            <!-- <td>
              <span class="prj-kode"><?php echo !empty($row->unique_code_counter) ? $row->unique_code_counter : ($row->kode_perjanjian ?: '-'); ?></span>
              <?php if(!empty($row->kode_perjanjian) && $row->kode_perjanjian !== $row->unique_code_counter): ?>
              <br><small style="color:#94a3b8; font-size:10.5px;"><?php echo $row->kode_perjanjian; ?></small>
              <?php endif; ?>
            </td> -->
            <!-- <td style="text-align:center; white-space:nowrap;">
              <?php echo $status_html; ?>
            </td> -->
          </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
      <?php else: ?>
        <div class="prj-empty">
          <i class="fa fa-calendar-times-o"></i>
          <span>Tidak ada data perjanjian</span>
          <span style="margin-top:4px; color:#cbd5e0;">dalam 2 tahun terakhir</span>
        </div>
      <?php endif; ?>
    </div>

    <!-- ── Footer ── -->
    <a href="#" class="prj-btn-more"
       onclick="show_modal('registration/Reg_pasien/riwayat_pasien?tab=perjanjian&no_mr=<?php echo $no_mr?>', 'Riwayat Perjanjian Pasien')">
      <i class="fa fa-th-list"></i> Lihat Selengkapnya
    </a>

  </div><!-- /.prj-body -->

</div><!-- /#prj-wrap -->
