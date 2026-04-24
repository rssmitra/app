<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<script src="<?php echo base_url()?>assets/js/sweetalert2.all.min.js"></script>

<style>
  /* ── Report Modal — Professional Layout ──────────────────── */
  #modal-laporan-mod .mod-report {
    font-family: 'Segoe UI', -apple-system, sans-serif;
    font-size: 13px; color: #1e293b; line-height: 1.6;
  }

  /* Header */
  #modal-laporan-mod .rpt-header {
    border-bottom: 3px solid #0369a1;
    padding-bottom: 14px; margin-bottom: 18px; text-align: center;
  }
  #modal-laporan-mod .rpt-header h2 {
    font-size: 17px; font-weight: 800; color: #0c4a6e;
    letter-spacing: 1.5px; margin: 0 0 2px; text-transform: uppercase;
  }
  #modal-laporan-mod .rpt-header .rpt-subtitle {
    font-size: 11.5px; color: #64748b; margin: 0;
  }
  #modal-laporan-mod .rpt-meta {
    display: flex; justify-content: space-between; align-items: center;
    background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 6px;
    padding: 10px 16px; margin-top: 12px; font-size: 12px;
  }
  #modal-laporan-mod .rpt-meta-item { text-align: center; }
  #modal-laporan-mod .rpt-meta-item .rpt-meta-label {
    display: block; font-size: 10px; text-transform: uppercase;
    letter-spacing: 0.5px; color: #94a3b8; font-weight: 600;
  }
  #modal-laporan-mod .rpt-meta-item .rpt-meta-val {
    display: block; font-weight: 700; color: #1e293b; font-size: 12.5px; margin-top: 2px;
  }

  /* Summary cards */
  #modal-laporan-mod .rpt-summary {
    display: grid; grid-template-columns: repeat(auto-fit, minmax(100px, 1fr));
    gap: 8px; margin-bottom: 18px;
  }
  #modal-laporan-mod .rpt-sum-card {
    background: #fff; border: 1px solid #e2e8f0; border-radius: 6px;
    padding: 10px 8px; text-align: center; border-top: 3px solid #0ea5e9;
  }
  #modal-laporan-mod .rpt-sum-card .rpt-sum-num {
    font-size: 22px; font-weight: 800; color: #0369a1; line-height: 1.1;
  }
  #modal-laporan-mod .rpt-sum-card .rpt-sum-label {
    font-size: 9.5px; text-transform: uppercase; letter-spacing: 0.3px;
    color: #64748b; font-weight: 600; margin-top: 3px;
  }

  /* Section */
  #modal-laporan-mod .rpt-section {
    background: #fff; border: 1px solid #e2e8f0; border-radius: 6px;
    margin-bottom: 12px; overflow: hidden;
  }
  #modal-laporan-mod .rpt-section-head {
    background: linear-gradient(135deg, #0c4a6e, #0369a1);
    color: #fff; padding: 7px 14px; font-size: 12.5px; font-weight: 700;
    display: flex; align-items: center; gap: 8px;
  }
  #modal-laporan-mod .rpt-section-head .rpt-sec-icon {
    width: 22px; height: 22px; background: rgba(255,255,255,.2);
    border-radius: 4px; display: flex; align-items: center; justify-content: center;
    font-size: 11px;
  }
  #modal-laporan-mod .rpt-section-body { padding: 12px 14px; }

  /* Stats row */
  #modal-laporan-mod .rpt-stats {
    display: flex; flex-wrap: wrap; gap: 8px; margin-bottom: 10px;
  }
  #modal-laporan-mod .rpt-stat {
    flex: 1; min-width: 70px; background: #f8fafc; border: 1px solid #e2e8f0;
    border-radius: 5px; padding: 8px 10px; text-align: center;
  }
  #modal-laporan-mod .rpt-stat-num {
    font-size: 18px; font-weight: 800; color: #0369a1; line-height: 1;
  }
  #modal-laporan-mod .rpt-stat-label {
    font-size: 9.5px; text-transform: uppercase; color: #64748b;
    font-weight: 600; margin-top: 3px; letter-spacing: 0.2px;
  }
  #modal-laporan-mod .rpt-stat.stat-total {
    background: linear-gradient(135deg, #0369a1, #0ea5e9);
    border-color: #0369a1;
  }
  #modal-laporan-mod .rpt-stat.stat-total .rpt-stat-num { color: #fff; }
  #modal-laporan-mod .rpt-stat.stat-total .rpt-stat-label { color: #bae6fd; }

  /* KV rows */
  #modal-laporan-mod .rpt-kv { margin-top: 8px; }
  #modal-laporan-mod .rpt-kv-row {
    display: flex; padding: 5px 0;
    border-bottom: 1px solid #f1f5f9;
  }
  #modal-laporan-mod .rpt-kv-row:last-child { border-bottom: none; }
  #modal-laporan-mod .rpt-kv-label {
    min-width: 200px; color: #64748b; font-size: 12px; font-weight: 500;
  }
  #modal-laporan-mod .rpt-kv-val {
    font-weight: 600; font-size: 12px; color: #1e293b;
  }

  /* Info box */
  #modal-laporan-mod .rpt-info-box {
    background: #fffbeb; border: 1px solid #fde68a; border-radius: 5px;
    padding: 8px 12px; margin-top: 8px; font-size: 12px; color: #92400e;
  }
  #modal-laporan-mod .rpt-info-box.info-red {
    background: #fef2f2; border-color: #fecaca; color: #991b1b;
  }
  #modal-laporan-mod .rpt-info-box.info-blue {
    background: #eff6ff; border-color: #bfdbfe; color: #1e40af;
  }

  /* Tables */
  #modal-laporan-mod .rpt-table {
    width: 100%; border-collapse: collapse; font-size: 11.5px; margin-top: 10px;
  }
  #modal-laporan-mod .rpt-table th {
    background: #f1f5f9; border: 1px solid #d1d5db;
    padding: 6px 8px; font-weight: 700; color: #475569;
    text-transform: uppercase; font-size: 10px; letter-spacing: 0.3px;
  }
  #modal-laporan-mod .rpt-table td {
    border: 1px solid #e5e7eb; padding: 5px 8px; vertical-align: top;
  }
  #modal-laporan-mod .rpt-table tr:nth-child(even) td { background: #f8fafc; }
  #modal-laporan-mod .rpt-table .rpt-td-num {
    text-align: center; font-weight: 700; color: #0369a1; width: 30px;
  }

  /* TT grid */
  #modal-laporan-mod .rpt-tt-grid {
    display: grid; grid-template-columns: repeat(auto-fit, minmax(80px, 1fr));
    gap: 6px; margin: 8px 0;
  }
  #modal-laporan-mod .rpt-tt-card {
    background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 5px;
    padding: 8px 6px; text-align: center;
  }
  #modal-laporan-mod .rpt-tt-card .rpt-tt-num { font-size: 20px; font-weight: 800; color: #0369a1; }
  #modal-laporan-mod .rpt-tt-card .rpt-tt-label { font-size: 9.5px; color: #64748b; font-weight: 600; }
  #modal-laporan-mod .rpt-tt-card.tt-total {
    background: linear-gradient(135deg, #0369a1, #0ea5e9);
    border-color: #0369a1;
  }
  #modal-laporan-mod .rpt-tt-card.tt-total .rpt-tt-num { color: #fff; }
  #modal-laporan-mod .rpt-tt-card.tt-total .rpt-tt-label { color: #bae6fd; }

  /* Shift badge */
  #modal-laporan-mod .rpt-shift-badge {
    display: inline-block; padding: 2px 10px; border-radius: 10px;
    font-size: 10.5px; font-weight: 700; margin-right: 6px;
  }
  #modal-laporan-mod .rpt-shift-pagi { background: #fef3c7; color: #92400e; }
  #modal-laporan-mod .rpt-shift-sore { background: #dbeafe; color: #1d4ed8; }
  #modal-laporan-mod .rpt-shift-malam { background: #ede9fe; color: #4c1d95; }

  /* Detail sub-head */
  #modal-laporan-mod .rpt-detail-head {
    font-weight: 700; font-size: 11.5px; color: #334155;
    margin: 10px 0 4px; display: flex; align-items: center; gap: 6px;
  }
  #modal-laporan-mod .rpt-count-badge {
    background: #0ea5e9; color: #fff; font-size: 10px; padding: 1px 7px;
    border-radius: 8px; font-weight: 700;
  }

  /* Notes block */
  #modal-laporan-mod .rpt-notes {
    background: #f8fafc; border-left: 3px solid #0ea5e9;
    padding: 8px 12px; margin: 6px 0; font-size: 12px;
    color: #334155; border-radius: 0 4px 4px 0;
  }
  #modal-laporan-mod .rpt-notes.notes-warning {
    border-left-color: #f59e0b; background: #fffbeb;
  }
  #modal-laporan-mod .rpt-notes.notes-danger {
    border-left-color: #ef4444; background: #fef2f2;
  }
  #modal-laporan-mod .rpt-notes-label {
    font-size: 10px; text-transform: uppercase; letter-spacing: 0.3px;
    color: #64748b; font-weight: 700; margin-bottom: 3px;
  }

  /* Footer */
  #modal-laporan-mod .rpt-footer {
    text-align: center; margin-top: 20px; padding-top: 14px;
    border-top: 2px solid #e2e8f0;
  }
  #modal-laporan-mod .rpt-footer p {
    margin: 2px 0; font-size: 11.5px; color: #64748b;
  }
  #modal-laporan-mod .rpt-footer .rpt-sign {
    margin-top: 12px; font-weight: 700; color: #1e293b; font-size: 12.5px;
  }

  /* Empty state */
  #modal-laporan-mod .rpt-empty {
    color: #94a3b8; font-size: 11.5px; font-style: italic; padding: 4px 0;
  }

  /* ── Report Modal — mod-* classes (used by report_modal.php) ── */
  #modal-laporan-mod .mod-report-header {
    text-align: center; border-bottom: 3px solid #0369a1;
    padding-bottom: 12px; margin-bottom: 16px;
  }
  #modal-laporan-mod .mod-report-header h2 {
    font-size: 17px; font-weight: 800; color: #0c4a6e;
    letter-spacing: 1.5px; margin: 0 0 6px; text-transform: uppercase;
  }
  #modal-laporan-mod .mod-section-title {
    background: linear-gradient(135deg, #0c4a6e, #0369a1);
    color: #fff; padding: 7px 14px; font-size: 12.5px; font-weight: 700;
    border-radius: 5px; margin: 14px 0 8px;
  }
  #modal-laporan-mod .mod-kv { margin-bottom: 8px; }
  #modal-laporan-mod .mod-kv-item {
    display: flex; padding: 5px 0; border-bottom: 1px solid #f1f5f9;
    font-size: 12.5px; align-items: flex-start;
  }
  #modal-laporan-mod .mod-kv-item.full { flex-direction: column; }
  #modal-laporan-mod .mod-kv-item:last-child { border-bottom: none; }
  #modal-laporan-mod .mod-kv-label {
    min-width: 200px; color: #64748b; font-weight: 500; font-size: 12px;
  }
  #modal-laporan-mod .mod-kv-val {
    font-weight: 600; color: #1e293b; font-size: 12.5px;
  }
  #modal-laporan-mod .mod-badge {
    display: inline-block; padding: 2px 8px; border-radius: 10px;
    font-size: 11px; font-weight: 600; margin: 2px 3px;
  }
  #modal-laporan-mod .mod-badge-bpjs { background: #dbeafe; color: #1d4ed8; }
  #modal-laporan-mod .mod-badge-umum { background: #dcfce7; color: #166534; }
  #modal-laporan-mod .mod-badge-asuransi { background: #fef3c7; color: #92400e; }
  #modal-laporan-mod .mod-badge-naker { background: #ede9fe; color: #5b21b6; }
  #modal-laporan-mod .mod-badge-rssm { background: #fce7f3; color: #9d174d; }
  #modal-laporan-mod .mod-table {
    width: 100%; border-collapse: collapse; font-size: 11.5px; margin-top: 6px;
  }
  #modal-laporan-mod .mod-table th {
    background: #f1f5f9; border: 1px solid #d1d5db; padding: 6px 8px;
    font-weight: 700; color: #475569; text-transform: uppercase; font-size: 10px;
  }
  #modal-laporan-mod .mod-table td {
    border: 1px solid #e5e7eb; padding: 5px 8px; vertical-align: top;
  }
  #modal-laporan-mod .mod-table tr:nth-child(even) td { background: #f8fafc; }
  #modal-laporan-mod .tt-grid {
    display: grid; grid-template-columns: repeat(auto-fit, minmax(80px, 1fr));
    gap: 6px; margin: 8px 0;
  }
  #modal-laporan-mod .tt-card {
    background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 5px;
    padding: 8px 6px; text-align: center;
  }
  #modal-laporan-mod .tt-card .tt-num { font-size: 20px; font-weight: 800; color: #0369a1; }
  #modal-laporan-mod .tt-card .tt-label { font-size: 9.5px; color: #64748b; font-weight: 600; }
  #modal-laporan-mod .tt-card.tt-total {
    background: linear-gradient(135deg, #0369a1, #0ea5e9); border-color: #0369a1;
  }
  #modal-laporan-mod .tt-card.tt-total .tt-num { color: #fff; }
  #modal-laporan-mod .tt-card.tt-total .tt-label { color: #bae6fd; }
  #modal-laporan-mod .mod-closing {
    text-align: center; margin-top: 20px; padding-top: 14px;
    border-top: 2px solid #e2e8f0; font-size: 12px; color: #64748b;
  }
  /* Patient count grid */
  #modal-laporan-mod .mod-jml-grid {
    display: inline-grid; grid-template-columns: repeat(auto-fit, minmax(70px, auto));
    gap: 4px 8px; margin-top: 4px; vertical-align: middle;
  }
  #modal-laporan-mod .mod-jml-item {
    display: flex; align-items: center; gap: 4px;
    background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 4px;
    padding: 3px 8px; font-size: 11px;
  }
  #modal-laporan-mod .mod-jml-item .mod-jml-lbl {
    color: #64748b; font-weight: 500;
  }
  #modal-laporan-mod .mod-jml-item .mod-jml-val {
    font-weight: 700; color: #1e293b;
  }
  #modal-laporan-mod .mod-jml-item.mod-jml-total {
    background: linear-gradient(135deg, #0369a1, #0ea5e9); border-color: #0369a1;
  }
  #modal-laporan-mod .mod-jml-item.mod-jml-total .mod-jml-lbl { color: #bae6fd; }
  #modal-laporan-mod .mod-jml-item.mod-jml-total .mod-jml-val { color: #fff; }

  /* print */
  @media print {
    body > * { display:none !important; }
    #mod-report-printable-wrap { display:block !important; }
    #mod-report-printable-wrap .mod-report { font-size:12px; color:#000; }
  }
  #mod-report-printable-wrap { display:none; }
</style>

<style>
  /* ── Layout & Section ─────────────────────────────────────── */
  .mod-section {
    background: #fff;
    border: 1px solid #dde3ea;
    border-radius: 8px;
    margin-bottom: 20px;
    box-shadow: 0 1px 4px rgba(0,0,0,.06);
    overflow: hidden;
  }
  .mod-section-head {
    background: linear-gradient(135deg, #0369a1, #0ea5e9);
    color: #fff;
    padding: 10px 18px;
    font-size: 13px;
    font-weight: 700;
    letter-spacing: .3px;
    display: flex;
    align-items: center;
    gap: 8px;
  }
  .mod-section-body { padding: 16px 18px 18px; }

  /* ── Section Badge ────────────────────────────────────────── */
  .section-num {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: rgba(255,255,255,.25);
    border-radius: 50%;
    width: 22px;
    height: 22px;
    font-size: 11px;
    font-weight: 800;
    flex-shrink: 0;
  }

  /* ── Sub-headings ─────────────────────────────────────────── */
  .mod-sub-head {
    font-size: 11.5px;
    font-weight: 700;
    color: #0369a1;
    text-transform: uppercase;
    letter-spacing: .5px;
    margin: 14px 0 8px;
    padding-bottom: 5px;
    border-bottom: 2px solid #e0f2fe;
    display: flex;
    align-items: center;
    gap: 6px;
  }
  .mod-sub-head:first-child { margin-top: 0; }

  /* ── Labels ───────────────────────────────────────────────── */
  label.mod-label,
  .mod-label {
    font-size: 11.5px;
    font-weight: 600;
    color: #374151;
    margin-bottom: 4px;
    display: block;
  }

  /* ── Form Controls ────────────────────────────────────────── */
  .form-control {
    font-size: 12.5px;
    border-color: #d1d5db;
    border-radius: 5px;
    transition: border-color .15s, box-shadow .15s;
    color: #111827;
  }
  .form-control:focus {
    border-color: #0ea5e9;
    box-shadow: 0 0 0 3px rgba(14,165,233,.15);
    outline: none;
  }
  .form-group { margin-bottom: 14px; }

  /* ── Textarea ─────────────────────────────────────────────── */
  textarea.form-control {
    height: 100px !important;
    resize: vertical;
    line-height: 1.5;
  }

  /* ── Number Inputs ────────────────────────────────────────── */
  .mod-number {
    width: 90px !important;
    text-align: center;
    font-weight: 600;
    font-size: 13px !important;
    color: #1e40af;
    padding-left: 8px;
    padding-right: 8px;
  }
  .mod-number.mod-auto-total {
    background: #eff6ff; border-color: #93c5fd;
    color: #1d4ed8; font-weight: 700; font-size: 14px !important;
    cursor: default;
  }
  .mod-number.mod-auto-total:focus { box-shadow: none; border-color: #93c5fd; }
  .num-block.num-block-total label.mod-label {
    color: #1d4ed8; font-weight: 700; font-size: 11px;
  }
  .num-block.num-block-total label.mod-label::after {
    content: ' \f021'; font-family: FontAwesome; font-size: 9px; margin-left: 3px; opacity: .6;
  }
  .num-block { margin-bottom: 10px; }
  .num-block label.mod-label { font-size: 11px; color: #6b7280; margin-bottom: 3px; }

  /* ── Numbers Grid ─────────────────────────────────────────── */
  .num-grid {
    display: flex;
    flex-wrap: wrap;
    gap: 10px 14px;
    margin-bottom: 6px;
  }
  .num-grid .num-block { min-width: 90px; }

  /* ── Detail Rows (Pasien Lists) ───────────────────────────── */
  .mod-row-detail {
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
    padding: 8px 10px;
    margin-bottom: 6px;
    transition: background .15s;
  }
  .mod-row-detail:hover { background: #f0f9ff; border-color: #bae6fd; }
  .detail-header-row {
    display: flex;
    gap: 0;
    font-size: 10.5px;
    font-weight: 700;
    color: #6b7280;
    text-transform: uppercase;
    letter-spacing: .4px;
    padding: 0 10px 4px;
  }

  /* ── Buttons ──────────────────────────────────────────────── */
  .btn-add-row {
    font-size: 11.5px;
    margin-top: 6px;
    border-radius: 5px;
    /* padding: 4px 12px; */
    font-weight: 600;
    color: #0369a1;
    border-color: #bae6fd;
    background: #f0f9ff;
  }
  .btn-add-row:hover { background: #e0f2fe; border-color: #7dd3fc; color: #075985; }
  .btn-del-row {
    font-size: 11px;
    padding: 3px 7px;
    border-radius: 4px;
    margin-top: 1px;
  }

  /* ── Input Group ──────────────────────────────────────────── */
  .input-group .form-control { border-radius: 5px 0 0 5px; }
  .input-group-addon {
    background: #f3f4f6;
    border-color: #d1d5db;
    color: #6b7280;
    border-radius: 0 5px 5px 0;
  }

  /* ── HR Divider ───────────────────────────────────────────── */
  .mod-divider {
    border: none;
    border-top: 1px dashed #e2e8f0;
    margin: 16px 0 14px;
  }

  /* ── Save Bar ─────────────────────────────────────────────── */
  .save-bar {
    position: sticky;
    bottom: 0;
    background: #fff;
    border-top: 2px solid #0ea5e9;
    padding: 12px 0;
    z-index: 100;
    box-shadow: 0 -2px 10px rgba(0,0,0,.08);
  }
  .save-bar .btn-primary {
    padding: 7px 22px;
    font-size: 13px;
    font-weight: 700;
    border-radius: 6px;
    background: linear-gradient(135deg, #0369a1, #0ea5e9);
    border: none;
    letter-spacing: .3px;
  }
  .save-bar .btn-primary:hover { background: linear-gradient(135deg, #075985, #0284c7); }
  .save-bar .btn-default {
    padding: 7px 18px;
    font-size: 13px;
    border-radius: 6px;
    font-weight: 600;
  }

  /* ── Page Header ──────────────────────────────────────────── */
  .page-header { border-bottom: 2px solid #e0f2fe; margin-bottom: 22px; padding-bottom: 12px; }
  .page-header h1 { font-size: 20px; font-weight: 700; color: #0c4a6e; margin: 0; }
  .page-header small { font-size: 12px; color: #6b7280; }

  /* ── Kebersihan Select ────────────────────────────────────── */
  .kebersihan-select { font-size: 12.5px; }

  /* ── Shift Badge ──────────────────────────────────────────── */
  .shift-badge {
    display: inline-block;
    font-size: 11px;
    font-weight: 700;
    padding: 2px 10px;
    border-radius: 20px;
    margin-left: 6px;
  }
  .shift-pagi  { background: #fef9c3; color: #854d0e; }
  .shift-sore  { background: #ffedd5; color: #9a3412; }
  .shift-malam { background: #ede9fe; color: #5b21b6; }

  /* ── Foto Kondisi Lapangan ───────────────────────────────── */
  .foto-grid {
    display: flex; flex-wrap: wrap; gap: 10px; margin-top: 6px;
  }
  .foto-thumb-wrap {
    position: relative; width: 100px;
    border: 1px solid #e2e8f0; border-radius: 6px; overflow: hidden;
    background: #f8fafc; text-align: center;
  }
  .foto-thumb {
    width: 100px; height: 80px; object-fit: cover; cursor: pointer; display: block;
    transition: opacity .15s;
  }
  .foto-thumb:hover { opacity: .85; }
  .foto-ket-label {
    font-size: 10px; color: #374151; padding: 3px 4px; line-height: 1.3;
    word-break: break-word; background: #f8fafc;
  }
  .foto-thumb-sm {
    width: 100%; max-height: 70px; object-fit: cover; border-radius: 4px;
    cursor: pointer; border: 1px solid #e2e8f0;
  }
  .foto-exist-row, .foto-new-row {
    background: #f8fafc; border: 1px solid #e8edf2; border-radius: 5px;
    padding: 6px 8px; margin-bottom: 6px;
  }
  /* Lightbox overlay */
  #foto-lightbox-overlay {
    display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(0,0,0,.82); z-index: 9999;
    align-items: center; justify-content: center; cursor: zoom-out;
  }
  #foto-lightbox-overlay.active { display: flex; }
  #foto-lightbox-img { max-width: 90vw; max-height: 90vh; border-radius: 6px; box-shadow: 0 8px 32px rgba(0,0,0,.5); }
</style>

<div class="page-header">
  <h1>
    <i class="fa fa-clipboard" style="color:#0ea5e9;margin-right:8px"></i>
    <?php echo $title ?>
    <small>
      <i class="ace-icon fa fa-angle-double-right"></i>
      <?php echo $breadcrumbs ?> &raquo; <?php echo $id ? 'Edit Laporan' : 'Input Laporan Baru' ?>
    </small>
  </h1>
</div>

<?php
  $L  = $laporan;
  $D  = $detail;
  $igd   = $D ? $D['igd']   : null;
  $rj    = $D ? $D['rawat_jalan']  : null;
  $hd    = $D ? $D['hemodialisa']  : null;
  $ri    = $D ? $D['rawat_inap']   : null;
  $rd    = $D ? $D['ranap_detail'] : [];
  $ints  = $D ? $D['intensive']    : null;
  $icud  = $D ? $D['icu_detail']   : [];
  $vk    = $D ? $D['vk']           : null;
  $vkd   = $D && isset($D['vk_detail']) ? $D['vk_detail'] : [];
  $pna   = $D ? $D['perina']       : null;
  $pnad  = $D && isset($D['perina_detail']) ? $D['perina_detail'] : [];
  $ko    = $D ? $D['kamar_op']     : [];
  $lab   = $D ? $D['lab']          : null;
  $frm   = $D ? $D['farmasi']      : null;
  $rad   = $D ? $D['radiologi']    : null;
  $lain  = $D ? $D['lainnya']      : null;

  $ok = ['pagi'=>[], 'sore'=>[], 'malam'=>[]];
  foreach ($ko as $row) $ok[$row->shift][] = $row;

  $v = function($obj, $key, $default='') {
    if (!$obj) return $default;
    $val = isset($obj->$key) ? $obj->$key : null;
    return ($val !== null && $val !== '') ? $val : $default;
  };

  $fotos = $D && isset($D['fotos']) ? $D['fotos'] : [];

  function mod_foto_widget($sec, $fotos, $flag) {
    $base    = base_url('uploaded/mod_laporan/');
    $sec_f   = isset($fotos[$sec]) ? $fotos[$sec] : [];
    $is_read = ($flag === 'read');
    ?>
    <hr class="mod-divider" style="margin-top:16px">
    <p class="mod-sub-head"><i class="fa fa-camera"></i> Foto Kondisi Lapangan</p>
    <?php if ($is_read): ?>
      <?php if (!empty($sec_f)): ?>
      <div class="foto-grid">
        <?php foreach ($sec_f as $f): ?>
        <div class="foto-thumb-wrap">
          <img src="<?php echo $base . htmlspecialchars($f->foto_path) ?>"
               class="foto-thumb" onclick="openFotoLb(this.src)"
               title="<?php echo htmlspecialchars($f->keterangan) ?>">
          <?php if ($f->keterangan): ?>
          <div class="foto-ket-label"><?php echo htmlspecialchars($f->keterangan) ?></div>
          <?php endif; ?>
        </div>
        <?php endforeach; ?>
      </div>
      <?php else: ?>
      <p class="rpt-empty">Tidak ada foto.</p>
      <?php endif; ?>
    <?php else: ?>
      <?php foreach ($sec_f as $f): ?>
      <div class="foto-exist-row" id="fer-<?php echo $f->id ?>">
        <div class="row" style="align-items:center;margin:0">
          <div class="col-xs-2" style="padding-left:0">
            <img src="<?php echo $base . htmlspecialchars($f->foto_path) ?>"
                 class="foto-thumb-sm" onclick="openFotoLb(this.src)">
          </div>
          <div class="col-xs-9">
            <input type="text" class="form-control input-sm"
                   name="foto_ket_exist[<?php echo $sec ?>][]"
                   value="<?php echo htmlspecialchars($f->keterangan) ?>"
                   placeholder="Keterangan foto...">
            <input type="hidden" name="foto_keep[<?php echo $sec ?>][]"
                   value="<?php echo $f->id ?>">
          </div>
          <div class="col-xs-1" style="padding-left:4px">
            <button type="button" class="btn btn-xs btn-danger"
                    onclick="delExistFoto(this,'<?php echo $sec ?>',<?php echo $f->id ?>)"
                    title="Hapus foto">
              <i class="fa fa-trash-o"></i>
            </button>
          </div>
        </div>
      </div>
      <?php endforeach; ?>
      <div id="fdel-<?php echo $sec ?>"></div>
      <div id="fnew-<?php echo $sec ?>"></div>
      <button type="button" class="btn btn-xs btn-add-row"
              onclick="addFotoRow('<?php echo $sec ?>')" style="margin-top:4px">
        <i class="fa fa-camera"></i> Tambah Foto
      </button>
    <?php endif; ?>
    <?php
  }

?>

<form method="post" action="<?php echo site_url('eksekutif/Eks_laporan_mod/save') ?>" id="form_mod" enctype="multipart/form-data">
<input type="hidden" name="id" value="<?php echo $id ?: '' ?>">
<?php if ($flag == 'read'): ?><fieldset disabled style="border:none;padding:0;margin:0"><?php endif; ?>

<!-- ===================== HEADER ===================== -->
<div class="mod-section">
  <div class="mod-section-head">
    <i class="fa fa-file-text-o"></i> Informasi Laporan MOD
  </div>
  <div class="mod-section-body">
    <div class="row">
      <div class="col-md-4">
        <div class="form-group">
          <label class="mod-label">Hari / Tanggal <span class="text-danger">*</span></label>
          <div class="input-group">
            <input class="form-control date-picker" name="tanggal" id="tanggal" type="text"
                   data-date-format="yyyy-mm-dd"
                   value="<?php echo $L ? $L->tanggal : date('Y-m-d') ?>" required>
            <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
          </div>
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          <label class="mod-label">Nama MOD <span class="text-danger">*</span></label>
          <input class="form-control" name="nama_mod" type="text" placeholder="Nama Manager On Duty"
                 value="<?php echo htmlspecialchars($v($L,'nama_mod')) ?>" required>
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group">
          <label class="mod-label">Shift MOD <span class="text-danger">*</span></label>
          <select class="form-control" name="shift_mod" required>
            <?php foreach (['Pagi','Sore','Malam'] as $s): ?>
            <option value="<?php echo $s ?>" <?php echo $v($L,'shift_mod')==$s?'selected':'' ?>><?php echo $s ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- ===================== 1. IGD ===================== -->
<div class="mod-section">
  <div class="mod-section-head">
    <span class="section-num">1</span> IGD (Instalasi Gawat Darurat)
  </div>
  <div class="mod-section-body">
    <p class="mod-sub-head"><i class="fa fa-users"></i> Jumlah Pasien</p>
    <div class="num-grid">
      <div class="num-block num-block-total">
        <label class="mod-label">Total</label>
        <input class="form-control mod-number mod-auto-total" name="igd_jml" type="number" min="0" value="<?php echo $v($igd,'jml_pasien',0) ?>" readonly>
      </div>
      <div class="num-block">
        <label class="mod-label">BPJS</label>
        <input class="form-control mod-number" name="igd_bpjs" type="number" min="0" value="<?php echo $v($igd,'bpjs',0) ?>">
      </div>
      <div class="num-block">
        <label class="mod-label">Umum</label>
        <input class="form-control mod-number" name="igd_umum" type="number" min="0" value="<?php echo $v($igd,'umum',0) ?>">
      </div>
      <div class="num-block">
        <label class="mod-label">Asuransi</label>
        <input class="form-control mod-number" name="igd_asuransi" type="number" min="0" value="<?php echo $v($igd,'asuransi',0) ?>">
      </div>
      <div class="num-block">
        <label class="mod-label">Naker</label>
        <input class="form-control mod-number" name="igd_naker" type="number" min="0" value="<?php echo $v($igd,'naker',0) ?>">
      </div>
      <div class="num-block">
        <label class="mod-label">Karyawan</label>
        <input class="form-control mod-number" name="igd_Karyawan" type="number" min="0" value="<?php echo $v($igd,'Karyawan',0) ?>">
      </div>
      <div class="num-block">
        <label class="mod-label">Ranap</label>
        <input class="form-control mod-number" name="igd_ranap" type="number" min="0" value="<?php echo $v($igd,'ranap',0) ?>">
      </div>
      <div class="num-block">
        <label class="mod-label">DOA</label>
        <input class="form-control mod-number" name="igd_doa" type="number" min="0" value="<?php echo $v($igd,'doa',0) ?>">
      </div>
      <div class="num-block">
        <label class="mod-label">DOE</label>
        <input class="form-control mod-number" name="igd_doe" type="number" min="0" value="<?php echo $v($igd,'doe',0) ?>">
      </div>
      <div class="num-block" style="min-width:130px">
        <label class="mod-label">Rujukan Ditolak</label>
        <input class="form-control mod-number" name="igd_jml_rujukan_ditolak" type="number" min="0" value="<?php echo $v($igd,'jml_rujukan_ditolak',0) ?>">
      </div>
      <div class="num-block" style="min-width:150px">
        <label class="mod-label">Menolak Ranap</label>
        <input class="form-control mod-number" name="igd_jml_menolak_ranap" type="number" min="0" value="<?php echo $v($igd,'jml_menolak_ranap',0) ?>">
      </div>
    </div>
    <hr class="mod-divider">
    <div class="row">
      <div class="col-md-6">
        <div class="form-group" style="margin-bottom:0">
          <label class="mod-label">Alasan Rujukan Ditolak</label>
          <textarea class="form-control" name="igd_alasan_ditolak" placeholder="Tuliskan alasan penolakan rujukan..."><?php echo htmlspecialchars($v($igd,'alasan_ditolak')) ?></textarea>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group" style="margin-bottom:0">
          <label class="mod-label">Alasan Menolak Ranap</label>
          <textarea class="form-control" name="igd_alasan_menolak_ranap" placeholder="Tuliskan alasan pasien menolak rawat inap..."><?php echo htmlspecialchars($v($igd,'alasan_menolak_ranap')) ?></textarea>
        </div>
      </div>
    </div>
    <?php mod_foto_widget('igd', $fotos, $flag); ?>
  </div>
</div>

<!-- ===================== 2. RAWAT JALAN ===================== -->
<div class="mod-section">
  <div class="mod-section-head">
    <span class="section-num">2</span> Rawat Jalan
  </div>
  <div class="mod-section-body">
    <p class="mod-sub-head"><i class="fa fa-users"></i> Jumlah Pasien</p>
    <div class="num-grid">
      <div class="num-block num-block-total">
        <label class="mod-label">Total</label>
        <input class="form-control mod-number mod-auto-total" name="rj_jml" type="number" min="0" value="<?php echo $v($rj,'jml_pasien',0) ?>" readonly>
      </div>
      <div class="num-block">
        <label class="mod-label">BPJS</label>
        <input class="form-control mod-number" name="rj_bpjs" type="number" min="0" value="<?php echo $v($rj,'bpjs',0) ?>">
      </div>
      <div class="num-block">
        <label class="mod-label">Umum</label>
        <input class="form-control mod-number" name="rj_umum" type="number" min="0" value="<?php echo $v($rj,'umum',0) ?>">
      </div>
      <div class="num-block">
        <label class="mod-label">Asuransi</label>
        <input class="form-control mod-number" name="rj_asuransi" type="number" min="0" value="<?php echo $v($rj,'asuransi',0) ?>">
      </div>
      <div class="num-block">
        <label class="mod-label">Naker</label>
        <input class="form-control mod-number" name="rj_naker" type="number" min="0" value="<?php echo $v($rj,'naker',0) ?>">
      </div>
      <div class="num-block">
        <label class="mod-label">Karyawan</label>
        <input class="form-control mod-number" name="rj_Karyawan" type="number" min="0" value="<?php echo $v($rj,'Karyawan',0) ?>">
      </div>
      <div class="num-block">
        <label class="mod-label">Ranap</label>
        <input class="form-control mod-number" name="rj_ranap" type="number" min="0" value="<?php echo $v($rj,'ranap',0) ?>">
      </div>
    </div>
    <?php mod_foto_widget('rawat_jalan', $fotos, $flag); ?>
  </div>
</div>

<!-- ===================== 3. HEMODIALISA ===================== -->
<div class="mod-section">
  <div class="mod-section-head">
    <span class="section-num">3</span> Hemodialisa
  </div>
  <div class="mod-section-body">
    <p class="mod-sub-head"><i class="fa fa-users"></i> Jumlah Pasien</p>
    <div class="num-grid">
      <div class="num-block num-block-total">
        <label class="mod-label">Total</label>
        <input class="form-control mod-number mod-auto-total" name="hd_jml" type="number" min="0" value="<?php echo $v($hd,'jml_pasien',0) ?>" readonly>
      </div>
      <div class="num-block">
        <label class="mod-label">BPJS</label>
        <input class="form-control mod-number" name="hd_bpjs" type="number" min="0" value="<?php echo $v($hd,'bpjs',0) ?>">
      </div>
      <div class="num-block">
        <label class="mod-label">Umum</label>
        <input class="form-control mod-number" name="hd_umum" type="number" min="0" value="<?php echo $v($hd,'umum',0) ?>">
      </div>
      <div class="num-block">
        <label class="mod-label">Asuransi</label>
        <input class="form-control mod-number" name="hd_asuransi" type="number" min="0" value="<?php echo $v($hd,'asuransi',0) ?>">
      </div>
      <div class="num-block" style="min-width:120px">
        <label class="mod-label">Pasien HD Ranap</label>
        <input class="form-control mod-number" name="hd_ranap" type="number" min="0" value="<?php echo $v($hd,'hd_ranap',0) ?>">
      </div>
    </div>
    <?php mod_foto_widget('hemodialisa', $fotos, $flag); ?>
  </div>
</div>

<!-- ===================== 4. RAWAT INAP ===================== -->
<div class="mod-section">
  <div class="mod-section-head">
    <span class="section-num">4</span> Rawat Inap
  </div>
  <div class="mod-section-body">
    <p class="mod-sub-head"><i class="fa fa-users"></i> Jumlah Pasien</p>
    <div class="num-grid">
      <div class="num-block num-block-total">
        <label class="mod-label">Total</label>
        <input class="form-control mod-number mod-auto-total" name="ri_jml" type="number" min="0" value="<?php echo $v($ri,'jml_pasien',0) ?>" readonly>
      </div>
      <div class="num-block">
        <label class="mod-label">BPJS</label>
        <input class="form-control mod-number" name="ri_bpjs" type="number" min="0" value="<?php echo $v($ri,'bpjs',0) ?>">
      </div>
      <div class="num-block">
        <label class="mod-label">Umum</label>
        <input class="form-control mod-number" name="ri_umum" type="number" min="0" value="<?php echo $v($ri,'umum',0) ?>">
      </div>
      <div class="num-block">
        <label class="mod-label">Asuransi</label>
        <input class="form-control mod-number" name="ri_asuransi" type="number" min="0" value="<?php echo $v($ri,'asuransi',0) ?>">
      </div>
      <div class="num-block">
        <label class="mod-label">Naker</label>
        <input class="form-control mod-number" name="ri_naker" type="number" min="0" value="<?php echo $v($ri,'naker',0) ?>">
      </div>
      <div class="num-block" style="min-width:120px">
        <label class="mod-label">Rencana Operasi</label>
        <input class="form-control mod-number" name="ri_rencana_op" type="number" min="0" value="<?php echo $v($ri,'rencana_operasi',0) ?>">
      </div>
    </div>

    <hr class="mod-divider">
    <p class="mod-sub-head"><i class="fa fa-bed"></i> Ketersediaan Tempat Tidur (TT)</p>
    <div class="num-grid">
      <div class="num-block" style="min-width:110px">
        <label class="mod-label">VVIP (Deluxe)</label>
        <input class="form-control mod-number" name="tt_vvip" type="number" min="0" value="<?php echo $v($ri,'tt_vvip',0) ?>">
      </div>
      <div class="num-block">
        <label class="mod-label">VIP 1</label>
        <input class="form-control mod-number" name="tt_vip1" type="number" min="0" value="<?php echo $v($ri,'tt_vip1',0) ?>">
      </div>
      <div class="num-block">
        <label class="mod-label">VIP 2</label>
        <input class="form-control mod-number" name="tt_vip2" type="number" min="0" value="<?php echo $v($ri,'tt_vip2',0) ?>">
      </div>
      <div class="num-block">
        <label class="mod-label">Kelas 1</label>
        <input class="form-control mod-number" name="tt_kelas1" type="number" min="0" value="<?php echo $v($ri,'tt_kelas1',0) ?>">
      </div>
      <div class="num-block">
        <label class="mod-label">Kelas 2</label>
        <input class="form-control mod-number" name="tt_kelas2" type="number" min="0" value="<?php echo $v($ri,'tt_kelas2',0) ?>">
      </div>
      <div class="num-block">
        <label class="mod-label">Kelas 3</label>
        <input class="form-control mod-number" name="tt_kelas3" type="number" min="0" value="<?php echo $v($ri,'tt_kelas3',0) ?>">
      </div>
    </div>

    <hr class="mod-divider">
    <p class="mod-sub-head"><i class="fa fa-exclamation-triangle"></i> Data Pasien Pengawasan Khusus</p>
    <div class="num-grid" style="margin-bottom:12px">
      <div class="num-block">
        <label class="mod-label">Jumlah</label>
        <input class="form-control mod-number" name="ri_jml_pengawasan" type="number" min="0" value="<?php echo $v($ri,'jml_pengawasan',0) ?>">
      </div>
    </div>
    <div class="detail-header-row" style="padding-left:0">
      <div style="flex:3;padding-right:4px">Nama / Umur</div>
      <div style="flex:2;padding-right:4px">Jaminan</div>
      <div style="flex:2;padding-right:4px">Hari Rawat</div>
      <div style="flex:3;padding-right:4px">Diagnosa</div>
      <div style="flex:1;padding-right:4px">DPJP</div>
      <div style="flex:0 0 34px"></div>
    </div>
    <div id="ranap-detail-list">
      <?php if (!empty($rd)): foreach($rd as $row): ?>
      <div class="mod-row-detail ranap-detail-row">
        <div class="row">
          <div class="col-md-3"><input class="form-control" name="ranap_nama_umur[]" type="text" placeholder="Nama / Umur" value="<?php echo htmlspecialchars($row->nama_umur) ?>"></div>
          <div class="col-md-2"><input class="form-control" name="ranap_jaminan[]" type="text" placeholder="Jaminan" value="<?php echo htmlspecialchars($row->jaminan) ?>"></div>
          <div class="col-md-2"><input class="form-control" name="ranap_hari_rawat[]" type="text" placeholder="Hari ke-" value="<?php echo htmlspecialchars($row->hari_rawat) ?>"></div>
          <div class="col-md-3"><input class="form-control" name="ranap_diagnosa[]" type="text" placeholder="Diagnosa" value="<?php echo htmlspecialchars($row->diagnosa) ?>"></div>
          <div class="col-md-1"><input class="form-control" name="ranap_dpjp[]" type="text" placeholder="DPJP" value="<?php echo htmlspecialchars($row->dpjp) ?>"></div>
          <div class="col-md-1">
            <button type="button" class="btn btn-xs btn-danger btn-del-row" onclick="removeRow(this)" title="Hapus baris">
              <i class="fa fa-trash-o"></i>
            </button>
          </div>
        </div>
      </div>
      <?php endforeach; else: ?>
      <div class="mod-row-detail ranap-detail-row">
        <div class="row">
          <div class="col-md-3"><input class="form-control" name="ranap_nama_umur[]" type="text" placeholder="Nama / Umur"></div>
          <div class="col-md-2"><input class="form-control" name="ranap_jaminan[]" type="text" placeholder="Jaminan"></div>
          <div class="col-md-2"><input class="form-control" name="ranap_hari_rawat[]" type="text" placeholder="Hari ke-"></div>
          <div class="col-md-3"><input class="form-control" name="ranap_diagnosa[]" type="text" placeholder="Diagnosa"></div>
          <div class="col-md-1"><input class="form-control" name="ranap_dpjp[]" type="text" placeholder="DPJP"></div>
          <div class="col-md-1">
            <button type="button" class="btn btn-xs btn-danger btn-del-row" onclick="removeRow(this)" title="Hapus baris"><i class="fa fa-trash-o"></i></button>
          </div>
        </div>
      </div>
      <?php endif; ?>
    </div>
    <button type="button" class="btn btn-primary btn-xs btn-add-row" onclick="addRanapRow()">
      <i class="fa fa-plus"></i> Tambah Pasien
    </button>
    <?php mod_foto_widget('rawat_inap', $fotos, $flag); ?>
  </div>
</div>

<!-- ===================== 5. INTENSIVE ===================== -->
<div class="mod-section">
  <div class="mod-section-head">
    <span class="section-num">5</span> Intensive Unit (ICU / PICU / NICU)
  </div>
  <div class="mod-section-body">
    <?php foreach (['ICU'=>'icu','PICU'=>'picu','NICU'=>'nicu'] as $label => $pfx): ?>
    <p class="mod-sub-head" style="<?php echo $pfx!='icu'?'margin-top:16px':'' ?>">
      <i class="fa fa-heartbeat"></i> <?php echo $label ?>
    </p>
    <div class="num-grid" style="margin-bottom:4px">
      <div class="num-block num-block-total">
        <label class="mod-label">Total</label>
        <input class="form-control mod-number mod-auto-total" name="<?php echo $pfx ?>_total" type="number" min="0" value="<?php echo $v($ints,$pfx.'_total',0) ?>" readonly>
      </div>
      <div class="num-block">
        <label class="mod-label">BPJS</label>
        <input class="form-control mod-number" name="<?php echo $pfx ?>_bpjs" type="number" min="0" value="<?php echo $v($ints,$pfx.'_bpjs',0) ?>">
      </div>
      <div class="num-block">
        <label class="mod-label">Umum</label>
        <input class="form-control mod-number" name="<?php echo $pfx ?>_umum" type="number" min="0" value="<?php echo $v($ints,$pfx.'_umum',0) ?>">
      </div>
      <div class="num-block">
        <label class="mod-label">Asuransi</label>
        <input class="form-control mod-number" name="<?php echo $pfx ?>_asuransi" type="number" min="0" value="<?php echo $v($ints,$pfx.'_asuransi',0) ?>">
      </div>
    </div>
    <?php endforeach; ?>

    <hr class="mod-divider">
    <p class="mod-sub-head"><i class="fa fa-list"></i> Detail Pasien ICU / PICU / NICU</p>
    <div class="detail-header-row" style="padding-left:0">
      <div style="flex:1;padding-right:4px">Unit</div>
      <div style="flex:3;padding-right:4px">Nama / Umur</div>
      <div style="flex:2;padding-right:4px">Jaminan</div>
      <div style="flex:1;padding-right:4px">Hari ke-</div>
      <div style="flex:3;padding-right:4px">Diagnosa</div>
      <div style="flex:1;padding-right:4px">DPJP</div>
      <div style="flex:0 0 34px"></div>
    </div>
    <div id="icu-detail-list">
      <?php if (!empty($icud)): foreach($icud as $row): ?>
      <div class="mod-row-detail icu-detail-row">
        <div class="row">
          <div class="col-md-1">
            <select class="form-control" name="icu_det_unit[]">
              <?php foreach(['ICU','PICU','NICU'] as $u): ?>
              <option value="<?php echo $u ?>" <?php echo $row->unit==$u?'selected':'' ?>><?php echo $u ?></option>
              <?php endforeach; ?>
            </select>
          </div>
          <div class="col-md-3"><input class="form-control" name="icu_det_nama[]" type="text" placeholder="Nama / Umur" value="<?php echo htmlspecialchars($row->nama_umur) ?>"></div>
          <div class="col-md-2"><input class="form-control" name="icu_det_jaminan[]" type="text" placeholder="Jaminan" value="<?php echo htmlspecialchars($row->jaminan) ?>"></div>
          <div class="col-md-1"><input class="form-control" name="icu_det_hari[]" type="text" placeholder="Hari ke-" value="<?php echo htmlspecialchars($row->hari_rawat) ?>"></div>
          <div class="col-md-3"><input class="form-control" name="icu_det_diagnosa[]" type="text" placeholder="Diagnosa" value="<?php echo htmlspecialchars($row->diagnosa) ?>"></div>
          <div class="col-md-1"><input class="form-control" name="icu_det_dpjp[]" type="text" placeholder="DPJP" value="<?php echo htmlspecialchars($row->dpjp) ?>"></div>
          <div class="col-md-1"><button type="button" class="btn btn-xs btn-danger btn-del-row" onclick="removeRow(this)" title="Hapus baris"><i class="fa fa-trash-o"></i></button></div>
        </div>
      </div>
      <?php endforeach; else: ?>
      <div class="mod-row-detail icu-detail-row">
        <div class="row">
          <div class="col-md-1">
            <select class="form-control" name="icu_det_unit[]">
              <option value="ICU">ICU</option><option value="PICU">PICU</option><option value="NICU">NICU</option>
            </select>
          </div>
          <div class="col-md-3"><input class="form-control" name="icu_det_nama[]" type="text" placeholder="Nama / Umur"></div>
          <div class="col-md-2"><input class="form-control" name="icu_det_jaminan[]" type="text" placeholder="Jaminan"></div>
          <div class="col-md-1"><input class="form-control" name="icu_det_hari[]" type="text" placeholder="Hari ke-"></div>
          <div class="col-md-3"><input class="form-control" name="icu_det_diagnosa[]" type="text" placeholder="Diagnosa"></div>
          <div class="col-md-1"><input class="form-control" name="icu_det_dpjp[]" type="text" placeholder="DPJP"></div>
          <div class="col-md-1"><button type="button" class="btn btn-xs btn-danger btn-del-row" onclick="removeRow(this)" title="Hapus baris"><i class="fa fa-trash-o"></i></button></div>
        </div>
      </div>
      <?php endif; ?>
    </div>
    <button type="button" class="btn btn-primary btn-xs btn-add-row" onclick="addIcuRow()">
      <i class="fa fa-plus"></i> Tambah Pasien ICU/PICU/NICU
    </button>
    <?php mod_foto_widget('intensive', $fotos, $flag); ?>
  </div>
</div>

<!-- ===================== 6. RUANG BERSALIN (VK) ===================== -->
<div class="mod-section">
  <div class="mod-section-head">
    <span class="section-num">6</span> Ruang Bersalin (VK)
  </div>
  <div class="mod-section-body">
    <p class="mod-sub-head"><i class="fa fa-users"></i> Jumlah Pasien</p>
    <div class="num-grid">
      <div class="num-block num-block-total">
        <label class="mod-label">Total</label>
        <input class="form-control mod-number mod-auto-total" name="vk_jml" type="number" min="0" value="<?php echo $v($vk,'jml_pasien',0) ?>" readonly>
      </div>
      <div class="num-block">
        <label class="mod-label">BPJS</label>
        <input class="form-control mod-number" name="vk_bpjs" type="number" min="0" value="<?php echo $v($vk,'bpjs',0) ?>">
      </div>
      <div class="num-block">
        <label class="mod-label">Umum</label>
        <input class="form-control mod-number" name="vk_umum" type="number" min="0" value="<?php echo $v($vk,'umum',0) ?>">
      </div>
      <div class="num-block">
        <label class="mod-label">Asuransi</label>
        <input class="form-control mod-number" name="vk_asuransi" type="number" min="0" value="<?php echo $v($vk,'asuransi',0) ?>">
      </div>
      <div class="num-block">
        <label class="mod-label">Jml Rujukan</label>
        <input class="form-control mod-number" name="vk_jml_rujukan" type="number" min="0" value="<?php echo $v($vk,'jml_rujukan',0) ?>">
      </div>
      <div class="num-block" style="min-width:120px">
        <label class="mod-label">Rujukan Ditolak</label>
        <input class="form-control mod-number" name="vk_jml_ditolak" type="number" min="0" value="<?php echo $v($vk,'jml_rujukan_ditolak',0) ?>">
      </div>
    </div>
    <hr class="mod-divider">
    <p class="mod-sub-head"><i class="fa fa-list"></i> Data Pasien VK</p>
    <div class="detail-header-row" style="padding-left:0">
      <div style="flex:3;padding-right:4px">Nama / Umur</div>
      <div style="flex:2;padding-right:4px">Jaminan</div>
      <div style="flex:3;padding-right:4px">Diagnosa</div>
      <div style="flex:2;padding-right:4px">DPJP</div>
      <div style="flex:0 0 34px"></div>
    </div>
    <div id="vk-detail-list">
      <?php if (!empty($vkd)): foreach ($vkd as $row): ?>
      <div class="mod-row-detail vk-detail-row">
        <div class="row">
          <div class="col-md-3"><input class="form-control" name="vk_det_nama[]" type="text" placeholder="Nama / Umur" value="<?php echo htmlspecialchars($row->nama_umur) ?>"></div>
          <div class="col-md-3"><input class="form-control" name="vk_det_jaminan[]" type="text" placeholder="Jaminan" value="<?php echo htmlspecialchars($row->jaminan) ?>"></div>
          <div class="col-md-3"><input class="form-control" name="vk_det_diagnosa[]" type="text" placeholder="Diagnosa" value="<?php echo htmlspecialchars($row->diagnosa) ?>"></div>
          <div class="col-md-2"><input class="form-control" name="vk_det_dpjp[]" type="text" placeholder="DPJP" value="<?php echo htmlspecialchars($row->dpjp) ?>"></div>
          <div class="col-md-1"><button type="button" class="btn btn-xs btn-danger btn-del-row" onclick="removeRow(this)" title="Hapus baris"><i class="fa fa-trash-o"></i></button></div>
        </div>
      </div>
      <?php endforeach; else: ?>
      <div class="mod-row-detail vk-detail-row">
        <div class="row">
          <div class="col-md-3"><input class="form-control" name="vk_det_nama[]" type="text" placeholder="Nama / Umur"></div>
          <div class="col-md-3"><input class="form-control" name="vk_det_jaminan[]" type="text" placeholder="Jaminan"></div>
          <div class="col-md-3"><input class="form-control" name="vk_det_diagnosa[]" type="text" placeholder="Diagnosa"></div>
          <div class="col-md-2"><input class="form-control" name="vk_det_dpjp[]" type="text" placeholder="DPJP"></div>
          <div class="col-md-1"><button type="button" class="btn btn-xs btn-danger btn-del-row" onclick="removeRow(this)" title="Hapus baris"><i class="fa fa-trash-o"></i></button></div>
        </div>
      </div>
      <?php endif; ?>
    </div>
    <button type="button" class="btn btn-primary btn-xs btn-add-row" onclick="addVkRow()">
      <i class="fa fa-plus"></i> Tambah Pasien VK
    </button>
    <?php mod_foto_widget('vk', $fotos, $flag); ?>
  </div>
</div>

<!-- ===================== 7. PERINA ===================== -->
<div class="mod-section">
  <div class="mod-section-head">
    <span class="section-num">7</span> Perina
  </div>
  <div class="mod-section-body">
    <p class="mod-sub-head"><i class="fa fa-users"></i> Jumlah Pasien</p>
    <div class="num-grid">
      <div class="num-block num-block-total">
        <label class="mod-label">Total</label>
        <input class="form-control mod-number mod-auto-total" name="pna_jml" type="number" min="0" value="<?php echo $v($pna,'jml_pasien',0) ?>" readonly>
      </div>
      <div class="num-block">
        <label class="mod-label">BPJS</label>
        <input class="form-control mod-number" name="pna_bpjs" type="number" min="0" value="<?php echo $v($pna,'bpjs',0) ?>">
      </div>
      <div class="num-block">
        <label class="mod-label">Umum</label>
        <input class="form-control mod-number" name="pna_umum" type="number" min="0" value="<?php echo $v($pna,'umum',0) ?>">
      </div>
      <div class="num-block">
        <label class="mod-label">Asuransi</label>
        <input class="form-control mod-number" name="pna_asuransi" type="number" min="0" value="<?php echo $v($pna,'asuransi',0) ?>">
      </div>
      <div class="num-block" style="min-width:120px">
        <label class="mod-label">Bayi Sakit</label>
        <input class="form-control mod-number" name="pna_jml_bayi_sakit" type="number" min="0" value="<?php echo $v($pna,'jml_bayi_sakit',0) ?>">
      </div>
    </div>
    <hr class="mod-divider">
    <p class="mod-sub-head"><i class="fa fa-list"></i> Data Pasien Perina (Bayi Sakit)</p>
    <div class="detail-header-row" style="padding-left:0">
      <div style="flex:3;padding-right:4px">Nama Bayi / Umur</div>
      <div style="flex:2;padding-right:4px">Jaminan</div>
      <div style="flex:3;padding-right:4px">Diagnosa</div>
      <div style="flex:2;padding-right:4px">DPJP</div>
      <div style="flex:0 0 34px"></div>
    </div>
    <div id="perina-detail-list">
      <?php if (!empty($pnad)): foreach ($pnad as $row): ?>
      <div class="mod-row-detail perina-detail-row">
        <div class="row">
          <div class="col-md-3"><input class="form-control" name="pna_det_nama[]" type="text" placeholder="Nama Bayi / Umur" value="<?php echo htmlspecialchars($row->nama_umur) ?>"></div>
          <div class="col-md-3"><input class="form-control" name="pna_det_jaminan[]" type="text" placeholder="Jaminan" value="<?php echo htmlspecialchars($row->jaminan) ?>"></div>
          <div class="col-md-3"><input class="form-control" name="pna_det_diagnosa[]" type="text" placeholder="Diagnosa" value="<?php echo htmlspecialchars($row->diagnosa) ?>"></div>
          <div class="col-md-2"><input class="form-control" name="pna_det_dpjp[]" type="text" placeholder="DPJP" value="<?php echo htmlspecialchars($row->dpjp) ?>"></div>
          <div class="col-md-1"><button type="button" class="btn btn-xs btn-danger btn-del-row" onclick="removeRow(this)" title="Hapus baris"><i class="fa fa-trash-o"></i></button></div>
        </div>
      </div>
      <?php endforeach; else: ?>
      <div class="mod-row-detail perina-detail-row">
        <div class="row">
          <div class="col-md-3"><input class="form-control" name="pna_det_nama[]" type="text" placeholder="Nama Bayi / Umur"></div>
          <div class="col-md-3"><input class="form-control" name="pna_det_jaminan[]" type="text" placeholder="Jaminan"></div>
          <div class="col-md-3"><input class="form-control" name="pna_det_diagnosa[]" type="text" placeholder="Diagnosa"></div>
          <div class="col-md-2"><input class="form-control" name="pna_det_dpjp[]" type="text" placeholder="DPJP"></div>
          <div class="col-md-1"><button type="button" class="btn btn-xs btn-danger btn-del-row" onclick="removeRow(this)" title="Hapus baris"><i class="fa fa-trash-o"></i></button></div>
        </div>
      </div>
      <?php endif; ?>
    </div>
    <button type="button" class="btn btn-primary btn-xs btn-add-row" onclick="addPerinaRow()">
      <i class="fa fa-plus"></i> Tambah Pasien Perina
    </button>
    <?php mod_foto_widget('perina', $fotos, $flag); ?>
  </div>
</div>

<!-- ===================== 8. KAMAR OPERASI ===================== -->
<div class="mod-section">
  <div class="mod-section-head">
    <span class="section-num">8</span> Kamar Operasi
  </div>
  <div class="mod-section-body">
    <?php
    $shifts_label = ['pagi'=>'Pagi','sore'=>'Sore','malam'=>'Malam'];
    $shift_badge  = ['pagi'=>'shift-pagi','sore'=>'shift-sore','malam'=>'shift-malam'];
    foreach ($shifts_label as $shift => $label):
      $shift_data = $ok[$shift];
    ?>
    <p class="mod-sub-head" style="<?php echo $shift!='pagi'?'margin-top:18px':'' ?>">
      <i class="fa fa-clock-o"></i> Shift <?php echo $label ?>
      <span class="shift-badge <?php echo $shift_badge[$shift] ?>"><?php echo $label ?></span>
    </p>
    <div class="detail-header-row" style="padding-left:0">
      <div style="flex:3;padding-right:4px">Nama / Umur</div>
      <div style="flex:2;padding-right:4px">Jaminan</div>
      <div style="flex:3;padding-right:4px">Diagnosa</div>
      <div style="flex:2;padding-right:4px">DPJP</div>
      <div style="flex:1;padding-right:4px">Jam</div>
      <div style="flex:0 0 34px"></div>
    </div>
    <div id="ok-<?php echo $shift ?>-list">
      <?php if (!empty($shift_data)): foreach ($shift_data as $row): ?>
      <div class="mod-row-detail ok-detail-row">
        <div class="row">
          <div class="col-md-3"><input class="form-control" name="ok_<?php echo $shift ?>_nama[]" type="text" placeholder="Nama / Umur" value="<?php echo htmlspecialchars($row->nama_umur) ?>"></div>
          <div class="col-md-2"><input class="form-control" name="ok_<?php echo $shift ?>_jaminan[]" type="text" placeholder="Jaminan" value="<?php echo htmlspecialchars($row->jaminan) ?>"></div>
          <div class="col-md-3"><input class="form-control" name="ok_<?php echo $shift ?>_diagnosa[]" type="text" placeholder="Diagnosa" value="<?php echo htmlspecialchars($row->diagnosa) ?>"></div>
          <div class="col-md-2"><input class="form-control" name="ok_<?php echo $shift ?>_dpjp[]" type="text" placeholder="DPJP" value="<?php echo htmlspecialchars($row->dpjp) ?>"></div>
          <div class="col-md-1"><input class="form-control" name="ok_<?php echo $shift ?>_jam[]" type="text" placeholder="Jam" value="<?php echo htmlspecialchars($row->jam) ?>"></div>
          <div class="col-md-1"><button type="button" class="btn btn-xs btn-danger btn-del-row" onclick="removeRow(this)" title="Hapus baris"><i class="fa fa-trash-o"></i></button></div>
        </div>
      </div>
      <?php endforeach; else: ?>
      <div class="mod-row-detail ok-detail-row">
        <div class="row">
          <div class="col-md-3"><input class="form-control" name="ok_<?php echo $shift ?>_nama[]" type="text" placeholder="Nama / Umur"></div>
          <div class="col-md-2"><input class="form-control" name="ok_<?php echo $shift ?>_jaminan[]" type="text" placeholder="Jaminan"></div>
          <div class="col-md-3"><input class="form-control" name="ok_<?php echo $shift ?>_diagnosa[]" type="text" placeholder="Diagnosa"></div>
          <div class="col-md-2"><input class="form-control" name="ok_<?php echo $shift ?>_dpjp[]" type="text" placeholder="DPJP"></div>
          <div class="col-md-1"><input class="form-control" name="ok_<?php echo $shift ?>_jam[]" type="text" placeholder="Jam"></div>
          <div class="col-md-1"><button type="button" class="btn btn-xs btn-danger btn-del-row" onclick="removeRow(this)" title="Hapus baris"><i class="fa fa-trash-o"></i></button></div>
        </div>
      </div>
      <?php endif; ?>
    </div>
    <button type="button" class="btn btn-primary btn-xs btn-add-row" onclick="addOkRow('<?php echo $shift ?>')">
      <i class="fa fa-plus"></i> Tambah Pasien Shift <?php echo $label ?>
    </button>
    <?php endforeach; ?>
    <?php mod_foto_widget('kamar_op', $fotos, $flag); ?>
  </div>
</div>

<!-- ===================== 9. LABORATORIUM ===================== -->
<div class="mod-section">
  <div class="mod-section-head">
    <span class="section-num">9</span> Laboratorium
  </div>
  <div class="mod-section-body">
    <p class="mod-sub-head"><i class="fa fa-flask"></i> Jumlah Pasien &amp; Pemeriksaan</p>
    <div class="num-grid">
      <div class="num-block num-block-total">
        <label class="mod-label">Total</label>
        <input class="form-control mod-number mod-auto-total" name="lab_jml" type="number" min="0" value="<?php echo $v($lab,'jml_pasien',0) ?>" readonly>
      </div>
      <div class="num-block">
        <label class="mod-label">BPJS</label>
        <input class="form-control mod-number" name="lab_bpjs" type="number" min="0" value="<?php echo $v($lab,'bpjs',0) ?>">
      </div>
      <div class="num-block">
        <label class="mod-label">Umum</label>
        <input class="form-control mod-number" name="lab_umum" type="number" min="0" value="<?php echo $v($lab,'umum',0) ?>">
      </div>
      <div class="num-block">
        <label class="mod-label">Asuransi</label>
        <input class="form-control mod-number" name="lab_asuransi" type="number" min="0" value="<?php echo $v($lab,'asuransi',0) ?>">
      </div>
      <div class="num-block">
        <label class="mod-label">Naker</label>
        <input class="form-control mod-number" name="lab_naker" type="number" min="0" value="<?php echo $v($lab,'naker',0) ?>">
      </div>
      <div class="num-block">
        <label class="mod-label">Karyawan</label>
        <input class="form-control mod-number" name="lab_Karyawan" type="number" min="0" value="<?php echo $v($lab,'Karyawan',0) ?>">
      </div>
      <div class="num-block" style="min-width:140px">
        <label class="mod-label">Patologi Klinis</label>
        <input class="form-control mod-number" name="lab_pk" type="number" min="0" value="<?php echo $v($lab,'patologi_klinis',0) ?>">
      </div>
      <div class="num-block" style="min-width:150px">
        <label class="mod-label">Patologi Anatomi</label>
        <input class="form-control mod-number" name="lab_pa" type="number" min="0" value="<?php echo $v($lab,'patologi_anatomi',0) ?>">
      </div>
    </div>
    <?php mod_foto_widget('lab', $fotos, $flag); ?>
  </div>
</div>

<!-- ===================== 10. FARMASI ===================== -->
<div class="mod-section">
  <div class="mod-section-head">
    <span class="section-num">10</span> Farmasi
  </div>
  <div class="mod-section-body">
    <p class="mod-sub-head"><i class="fa fa-medkit"></i> Jumlah Resep</p>
    <div class="num-grid">
      <div class="num-block num-block-total">
        <label class="mod-label">Total</label>
        <input class="form-control mod-number mod-auto-total" name="frm_jml" type="number" min="0" value="<?php echo $v($frm,'jml_resep',0) ?>" readonly>
      </div>
      <div class="num-block">
        <label class="mod-label">BPJS</label>
        <input class="form-control mod-number" name="frm_bpjs" type="number" min="0" value="<?php echo $v($frm,'bpjs',0) ?>">
      </div>
      <div class="num-block">
        <label class="mod-label">Umum</label>
        <input class="form-control mod-number" name="frm_umum" type="number" min="0" value="<?php echo $v($frm,'umum',0) ?>">
      </div>
      <div class="num-block">
        <label class="mod-label">Asuransi</label>
        <input class="form-control mod-number" name="frm_asuransi" type="number" min="0" value="<?php echo $v($frm,'asuransi',0) ?>">
      </div>
      <div class="num-block">
        <label class="mod-label">Naker</label>
        <input class="form-control mod-number" name="frm_naker" type="number" min="0" value="<?php echo $v($frm,'naker',0) ?>">
      </div>
      <div class="num-block">
        <label class="mod-label">Karyawan</label>
        <input class="form-control mod-number" name="frm_Karyawan" type="number" min="0" value="<?php echo $v($frm,'Karyawan',0) ?>">
      </div>
      <div class="num-block" style="min-width:110px">
        <label class="mod-label">Obat Bebas</label>
        <input class="form-control mod-number" name="frm_obat_bebas" type="number" min="0" value="<?php echo $v($frm,'obat_bebas',0) ?>">
      </div>
    </div>
    <?php mod_foto_widget('farmasi', $fotos, $flag); ?>
  </div>
</div>

<!-- ===================== 11. RADIOLOGI ===================== -->
<div class="mod-section">
  <div class="mod-section-head">
    <span class="section-num">11</span> Radiologi
  </div>
  <div class="mod-section-body">
    <p class="mod-sub-head"><i class="fa fa-film"></i> Jumlah Pasien &amp; Tindakan</p>
    <div class="num-grid">
      <div class="num-block num-block-total">
        <label class="mod-label">Total</label>
        <input class="form-control mod-number mod-auto-total" name="rad_jml" type="number" min="0" value="<?php echo $v($rad,'jml_pasien',0) ?>" readonly>
      </div>
      <div class="num-block">
        <label class="mod-label">BPJS</label>
        <input class="form-control mod-number" name="rad_bpjs" type="number" min="0" value="<?php echo $v($rad,'bpjs',0) ?>">
      </div>
      <div class="num-block">
        <label class="mod-label">Umum</label>
        <input class="form-control mod-number" name="rad_umum" type="number" min="0" value="<?php echo $v($rad,'umum',0) ?>">
      </div>
      <div class="num-block">
        <label class="mod-label">Asuransi</label>
        <input class="form-control mod-number" name="rad_asuransi" type="number" min="0" value="<?php echo $v($rad,'asuransi',0) ?>">
      </div>
      <div class="num-block">
        <label class="mod-label">Naker</label>
        <input class="form-control mod-number" name="rad_naker" type="number" min="0" value="<?php echo $v($rad,'naker',0) ?>">
      </div>
      <div class="num-block">
        <label class="mod-label">X-Ray</label>
        <input class="form-control mod-number" name="rad_xray" type="number" min="0" value="<?php echo $v($rad,'xray',0) ?>">
      </div>
      <div class="num-block">
        <label class="mod-label">USG</label>
        <input class="form-control mod-number" name="rad_usg" type="number" min="0" value="<?php echo $v($rad,'usg',0) ?>">
      </div>
    </div>
    <?php mod_foto_widget('radiologi', $fotos, $flag); ?>
  </div>
</div>

<!-- ===================== 12. DPJP ===================== -->
<div class="mod-section">
  <div class="mod-section-head">
    <span class="section-num">12</span> DPJP Tidak Visite 24 Jam
  </div>
  <div class="mod-section-body">
    <label class="mod-label">Nama DPJP yang tidak visite</label>
    <textarea class="form-control" name="dpjp_visite"
              placeholder="Tuliskan nama DPJP yang tidak visite atau 'tidak ada'"><?php echo htmlspecialchars($v($lain,'dpjp_visite')) ?></textarea>
    <?php mod_foto_widget('dpjp', $fotos, $flag); ?>
  </div>
</div>

<!-- ===================== 13. AMBULANS ===================== -->
<div class="mod-section">
  <div class="mod-section-head">
    <span class="section-num">13</span> Utilisasi Ambulans
  </div>
  <div class="mod-section-body">
    <div class="row">
      <div class="col-md-4">
        <div class="form-group" style="margin-bottom:0">
          <label class="mod-label"><span class="shift-badge shift-pagi" style="margin-left:0;margin-right:4px">Pagi</span> Shift Pagi</label>
          <textarea class="form-control" name="ambulans_pagi" placeholder="Utilisasi ambulans shift pagi..."><?php echo htmlspecialchars($v($lain,'ambulans_pagi')) ?></textarea>
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group" style="margin-bottom:0">
          <label class="mod-label"><span class="shift-badge shift-sore" style="margin-left:0;margin-right:4px">Sore</span> Shift Sore</label>
          <textarea class="form-control" name="ambulans_sore" placeholder="Utilisasi ambulans shift sore..."><?php echo htmlspecialchars($v($lain,'ambulans_sore')) ?></textarea>
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group" style="margin-bottom:0">
          <label class="mod-label"><span class="shift-badge shift-malam" style="margin-left:0;margin-right:4px">Malam</span> Shift Malam</label>
          <textarea class="form-control" name="ambulans_malam" placeholder="Utilisasi ambulans shift malam..."><?php echo htmlspecialchars($v($lain,'ambulans_malam')) ?></textarea>
        </div>
      </div>
    </div>
    <?php mod_foto_widget('ambulans', $fotos, $flag); ?>
  </div>
</div>

<!-- ===================== 14. KENDALA ===================== -->
<div class="mod-section">
  <div class="mod-section-head">
    <span class="section-num">14</span> Kendala / Insiden / Komplain Pelayanan
  </div>
  <div class="mod-section-body">
    <div class="row">
      <div class="col-md-6">
        <div class="form-group" style="margin-bottom:0">
          <label class="mod-label"><i class="fa fa-exclamation-circle text-danger" style="margin-right:4px"></i> Kendala / Insiden / Komplain</label>
          <textarea class="form-control" name="kendala" placeholder="Tuliskan kendala/insiden/komplain atau 'tidak ada'"><?php echo htmlspecialchars($v($lain,'kendala')) ?></textarea>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group" style="margin-bottom:0">
          <label class="mod-label"><i class="fa fa-check-circle text-success" style="margin-right:4px"></i> Tindak Lanjut yang Dilakukan</label>
          <textarea class="form-control" name="kendala_tindak" placeholder="Tindak lanjut yang sudah dilakukan..."><?php echo htmlspecialchars($v($lain,'kendala_tindak')) ?></textarea>
        </div>
      </div>
    </div>
    <?php mod_foto_widget('kendala', $fotos, $flag); ?>
  </div>
</div>

<!-- ===================== 15. SARPRAS ===================== -->
<div class="mod-section">
  <div class="mod-section-head">
    <span class="section-num">15</span> Kerusakan Sarana &amp; Prasarana
  </div>
  <div class="mod-section-body">
    <div class="row">
      <div class="col-md-6">
        <div class="form-group" style="margin-bottom:0">
          <label class="mod-label"><i class="fa fa-wrench text-warning" style="margin-right:4px"></i> Kerusakan Sarpras</label>
          <textarea class="form-control" name="sarpras" placeholder="Tuliskan kerusakan sarpras atau 'tidak ada'"><?php echo htmlspecialchars($v($lain,'sarpras')) ?></textarea>
        </div>
      </div>
      <div class="col-md-6">
        <div class="form-group" style="margin-bottom:0">
          <label class="mod-label"><i class="fa fa-check-circle text-success" style="margin-right:4px"></i> Tindak Lanjut yang Dilakukan</label>
          <textarea class="form-control" name="sarpras_tindak" placeholder="Tindak lanjut yang sudah dilakukan..."><?php echo htmlspecialchars($v($lain,'sarpras_tindak')) ?></textarea>
        </div>
      </div>
    </div>
    <?php mod_foto_widget('sarpras', $fotos, $flag); ?>
  </div>
</div>

<!-- ===================== 16. KEBERSIHAN ===================== -->
<div class="mod-section">
  <div class="mod-section-head">
    <span class="section-num">16</span> Kebersihan
  </div>
  <div class="mod-section-body">
    <div class="row">
      <div class="col-md-4">
        <div class="form-group" style="margin-bottom:0">
          <label class="mod-label">Area Tunggu</label>
          <select class="form-control kebersihan-select" name="kebersihan_tunggu">
            <option value="">-- Pilih Status --</option>
            <?php foreach (['Bersih','Cukup Bersih','Kotor','Perlu Perhatian'] as $opt):
              $sel = $v($lain,'kebersihan_tunggu')==$opt ? 'selected' : '';
            ?>
            <option value="<?php echo $opt ?>" <?php echo $sel ?>><?php echo $opt ?></option>
            <?php endforeach; ?>
            <?php
              $existing = $v($lain,'kebersihan_tunggu');
              if ($existing && !in_array($existing, ['Bersih','Cukup Bersih','Kotor','Perlu Perhatian'])): ?>
            <option value="<?php echo htmlspecialchars($existing) ?>" selected><?php echo htmlspecialchars($existing) ?></option>
            <?php endif; ?>
          </select>
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group" style="margin-bottom:0">
          <label class="mod-label">Kran / Toilet (Rajal &amp; Ranap)</label>
          <select class="form-control kebersihan-select" name="kebersihan_toilet">
            <option value="">-- Pilih Status --</option>
            <?php foreach (['Bersih','Cukup Bersih','Kotor','Perlu Perhatian'] as $opt):
              $sel = $v($lain,'kebersihan_toilet')==$opt ? 'selected' : '';
            ?>
            <option value="<?php echo $opt ?>" <?php echo $sel ?>><?php echo $opt ?></option>
            <?php endforeach; ?>
            <?php
              $existing = $v($lain,'kebersihan_toilet');
              if ($existing && !in_array($existing, ['Bersih','Cukup Bersih','Kotor','Perlu Perhatian'])): ?>
            <option value="<?php echo htmlspecialchars($existing) ?>" selected><?php echo htmlspecialchars($existing) ?></option>
            <?php endif; ?>
          </select>
        </div>
      </div>
      <div class="col-md-4">
        <div class="form-group" style="margin-bottom:0">
          <label class="mod-label">Area Lobby</label>
          <select class="form-control kebersihan-select" name="kebersihan_lobby">
            <option value="">-- Pilih Status --</option>
            <?php foreach (['Bersih','Cukup Bersih','Kotor','Perlu Perhatian'] as $opt):
              $sel = $v($lain,'kebersihan_lobby')==$opt ? 'selected' : '';
            ?>
            <option value="<?php echo $opt ?>" <?php echo $sel ?>><?php echo $opt ?></option>
            <?php endforeach; ?>
            <?php
              $existing = $v($lain,'kebersihan_lobby');
              if ($existing && !in_array($existing, ['Bersih','Cukup Bersih','Kotor','Perlu Perhatian'])): ?>
            <option value="<?php echo htmlspecialchars($existing) ?>" selected><?php echo htmlspecialchars($existing) ?></option>
            <?php endif; ?>
          </select>
        </div>
      </div>
    </div>
    <?php mod_foto_widget('kebersihan', $fotos, $flag); ?>
  </div>
</div>

<!-- ===================== 17. KETERANGAN LAINNYA ===================== -->
<div class="mod-section">
  <div class="mod-section-head">
    <span class="section-num">17</span> Keterangan Lainnya
  </div>
  <div class="mod-section-body">
    <div class="form-group">
      <label class="mod-label">
        <i class="fa fa-pencil-square-o" style="margin-right:4px"></i>
        Keterangan Lainnya
      </label>
      <textarea class="form-control" name="keterangan_lain"
                placeholder="Tuliskan keterangan lainnya yang perlu dilaporkan..."
                style="height:120px!important"><?php echo htmlspecialchars($v($lain,'keterangan_lain')) ?></textarea>
    </div>
    <?php mod_foto_widget('keterangan_lain', $fotos, $flag); ?>
  </div>
</div>

<?php if ($flag == 'read'): ?></fieldset><?php endif; ?>

<!-- SAVE BAR -->
<div class="save-bar">
  <div class="row">
    <div class="col-xs-12">
      <a onclick="getMenu('eksekutif/Eks_laporan_mod')" href="#" class="btn btn-sm btn-success">
        <i class="ace-icon fa fa-arrow-left icon-on-right bigger-110"></i>
        Kembali ke daftar
      </a>
      <?php if ($flag != 'read'): ?>
      <button type="reset" id="btnReset" class="btn btn-sm btn-danger" style="margin-left:6px">
        <i class="ace-icon fa fa-close icon-on-right bigger-110"></i>
        Reset
      </button>
      <button type="button" id="btnSave" name="btnSave" class="btn btn-sm btn-info" style="margin-left:6px">
        <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>
        Simpan
      </button>
      <?php endif; ?>
    </div>
  </div>
</div>

</form>

<script>
var Swal = window.Swal || window.Sweetalert2;

/* datepicker — di-wrap try/catch agar error di sini tidak
   menghentikan binding handler AJAX di bawah */
try {
  $('.date-picker').datepicker({ autoclose:true, todayHighlight:true, format:'yyyy-mm-dd' })
    .next().on(ace.click_event, function(){ $(this).prev().focus(); });
} catch(e) {}

/* ── handler AJAX — gunakan delegasi dari document agar
   pasti terikat meski ada JS error sebelumnya ──────────── */
$(document).off('click.modform').on('click.modform', '#btnSave', function(e) {
  e.preventDefault();

  var $btn     = $(this);
  var $form    = $('#form_mod');

  if (!$form.length) return;

  var formData = new FormData($form[0]);

  achtungShowLoader();
  $btn.prop('disabled', true)
      .html('<i class="fa fa-spinner fa-spin"></i> Menyimpan...');

  $.ajax({
    url         : $form.attr('action'),
    type        : 'POST',
    data        : formData,
    processData : false,
    contentType : false,
    dataType    : 'text',
    success: function(responseText) {
      achtungHideLoader();
      var res;
      try { res = JSON.parse(responseText); } catch(err) {
        Swal.fire({ icon:'error', title:'Error', text:'Response tidak valid dari server.' });
        $btn.prop('disabled', false)
            .html('<i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i> Simpan');
        return;
      }

      if (res.status === 200) {
        Swal.fire({
          icon             : 'success',
          title            : 'Berhasil Disimpan!',
          text             : res.message,
          confirmButtonText: 'Lihat Laporan',
          confirmButtonColor: '#0ea5e9',
          timer            : 2500,
          timerProgressBar : true,
          allowOutsideClick: false
        }).then(function() {
          loadReportModal(res.url_id);
        });
      } else {
        Swal.fire({
          icon              : 'error',
          title             : 'Gagal!',
          text              : res.message,
          confirmButtonColor: '#ef4444'
        });
        $btn.prop('disabled', false)
            .html('<i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i> Simpan');
      }
    },
    error: function(xhr) {
      achtungHideLoader();
      Swal.fire({ icon:'error', title:'Error', text:'Terjadi kesalahan: HTTP ' + xhr.status });
      $btn.prop('disabled', false)
          .html('<i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i> Simpan');
    }
  });
});

/* ── muat konten laporan ke modal via AJAX ───────────────── */
window.loadReportModal = function(laporan_id) {
    $('#modal-report-body').html(
      '<div class="text-center" style="padding:40px">'
      + '<i class="fa fa-spinner fa-spin fa-2x text-info"></i>'
      + '<p style="margin-top:10px;color:#6b7280">Memuat laporan...</p>'
      + '</div>'
    );
    $('#modal-laporan-mod').modal('show');

    $.get('<?php echo site_url('eksekutif/Eks_laporan_mod/report_modal') ?>/' + laporan_id)
      .done(function(html) {
        $('#modal-report-body').html(html);
        /* simpan konten untuk keperluan cetak */
        $('#mod-report-printable-wrap').html(html);
      })
      .fail(function() {
        $('#modal-report-body').html(
          '<div class="alert alert-danger">Gagal memuat laporan. Silakan coba lagi.</div>'
        );
      });
  };

function removeRow(btn) {
  $(btn).closest('.mod-row-detail').remove();
}

/* ── Cetak laporan dari modal ────────────────────────────── */
function printModReport() {
  var printWin = window.open('', '_blank', 'width=900,height=700');
  var styles = [
    'body{font-family:"Segoe UI",-apple-system,sans-serif;font-size:12px;color:#1e293b;margin:20px;line-height:1.5}',
    '.mod-report{font-size:13px;color:#1e293b;line-height:1.6}',
    '.mod-report-header{text-align:center;border-bottom:3px solid #0369a1;padding-bottom:12px;margin-bottom:16px}',
    '.mod-report-header h2{font-size:16px;font-weight:800;color:#0c4a6e;letter-spacing:1.5px;margin:0 0 6px;text-transform:uppercase}',
    '.mod-section-title{background:linear-gradient(135deg,#0c4a6e,#0369a1);color:#fff;padding:7px 14px;font-size:12px;font-weight:700;border-radius:5px;margin:14px 0 8px}',
    '.mod-kv{margin-bottom:8px}',
    '.mod-kv-item{display:flex;padding:5px 0;border-bottom:1px solid #f1f5f9;font-size:12px;align-items:flex-start}',
    '.mod-kv-item.full{flex-direction:column}',
    '.mod-kv-item:last-child{border-bottom:none}',
    '.mod-kv-label{min-width:180px;color:#64748b;font-weight:500;font-size:11px}',
    '.mod-kv-val{font-weight:600;color:#1e293b;font-size:12px}',
    '.mod-table{width:100%;border-collapse:collapse;font-size:10.5px;margin-top:6px}',
    '.mod-table th{background:#f1f5f9;border:1px solid #d1d5db;padding:5px 6px;font-weight:700;color:#475569;text-transform:uppercase;font-size:9px}',
    '.mod-table td{border:1px solid #e5e7eb;padding:4px 6px;vertical-align:top}',
    '.mod-table tr:nth-child(even) td{background:#f8fafc}',
    '.tt-grid{display:flex;flex-wrap:wrap;gap:5px;margin:6px 0}',
    '.tt-card{background:#f8fafc;border:1px solid #e2e8f0;border-radius:4px;padding:6px 8px;text-align:center;min-width:65px}',
    '.tt-card .tt-num{font-size:16px;font-weight:800;color:#0369a1}',
    '.tt-card .tt-label{font-size:9px;color:#64748b;font-weight:600}',
    '.tt-card.tt-total{background:linear-gradient(135deg,#0369a1,#0ea5e9);border-color:#0369a1}',
    '.tt-card.tt-total .tt-num{color:#fff}',
    '.tt-card.tt-total .tt-label{color:#bae6fd}',
    '.mod-closing{text-align:center;margin-top:18px;padding-top:12px;border-top:2px solid #e2e8f0;font-size:11px;color:#64748b}',
    '.mod-jml-grid{display:inline-grid;grid-template-columns:repeat(auto-fit,minmax(70px,auto));gap:4px 6px;margin-top:3px;vertical-align:middle}',
    '.mod-jml-item{display:flex;align-items:center;gap:4px;background:#f8fafc;border:1px solid #e2e8f0;border-radius:4px;padding:3px 8px;font-size:10.5px}',
    '.mod-jml-item .mod-jml-lbl{color:#64748b;font-weight:500}',
    '.mod-jml-item .mod-jml-val{font-weight:700;color:#1e293b}',
    '.mod-jml-item.mod-jml-total{background:linear-gradient(135deg,#0369a1,#0ea5e9);border-color:#0369a1}',
    '.mod-jml-item.mod-jml-total .mod-jml-lbl{color:#bae6fd}',
    '.mod-jml-item.mod-jml-total .mod-jml-val{color:#fff}'
  ].join('\n');

  printWin.document.write(
    '<!DOCTYPE html><html><head>'
    + '<meta charset="UTF-8">'
    + '<title>Laporan MOD</title>'
    + '<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">'
    + '<style>' + styles + '</style>'
    + '</head><body>'
    + document.getElementById('modal-report-body').innerHTML
    + '</body></html>'
  );
  printWin.document.close();
  printWin.focus();
  setTimeout(function() { printWin.print(); }, 600);
}

var ranapRowTpl = '<div class="mod-row-detail ranap-detail-row"><div class="row">'
  + '<div class="col-md-3"><input class="form-control" name="ranap_nama_umur[]" type="text" placeholder="Nama / Umur"></div>'
  + '<div class="col-md-2"><input class="form-control" name="ranap_jaminan[]" type="text" placeholder="Jaminan"></div>'
  + '<div class="col-md-2"><input class="form-control" name="ranap_hari_rawat[]" type="text" placeholder="Hari ke-"></div>'
  + '<div class="col-md-3"><input class="form-control" name="ranap_diagnosa[]" type="text" placeholder="Diagnosa"></div>'
  + '<div class="col-md-1"><input class="form-control" name="ranap_dpjp[]" type="text" placeholder="DPJP"></div>'
  + '<div class="col-md-1"><button type="button" class="btn btn-xs btn-danger btn-del-row" onclick="removeRow(this)" title="Hapus baris"><i class="fa fa-trash-o"></i></button></div>'
  + '</div></div>';

function addRanapRow() { $('#ranap-detail-list').append(ranapRowTpl); }

var icuRowTpl = '<div class="mod-row-detail icu-detail-row"><div class="row">'
  + '<div class="col-md-1"><select class="form-control" name="icu_det_unit[]"><option value="ICU">ICU</option><option value="PICU">PICU</option><option value="NICU">NICU</option></select></div>'
  + '<div class="col-md-3"><input class="form-control" name="icu_det_nama[]" type="text" placeholder="Nama / Umur"></div>'
  + '<div class="col-md-2"><input class="form-control" name="icu_det_jaminan[]" type="text" placeholder="Jaminan"></div>'
  + '<div class="col-md-1"><input class="form-control" name="icu_det_hari[]" type="text" placeholder="Hari ke-"></div>'
  + '<div class="col-md-3"><input class="form-control" name="icu_det_diagnosa[]" type="text" placeholder="Diagnosa"></div>'
  + '<div class="col-md-1"><input class="form-control" name="icu_det_dpjp[]" type="text" placeholder="DPJP"></div>'
  + '<div class="col-md-1"><button type="button" class="btn btn-xs btn-danger btn-del-row" onclick="removeRow(this)" title="Hapus baris"><i class="fa fa-trash-o"></i></button></div>'
  + '</div></div>';

function addIcuRow() { $('#icu-detail-list').append(icuRowTpl); }

function addOkRow(shift) {
  var tpl = '<div class="mod-row-detail ok-detail-row"><div class="row">'
    + '<div class="col-md-3"><input class="form-control" name="ok_'+shift+'_nama[]" type="text" placeholder="Nama / Umur"></div>'
    + '<div class="col-md-2"><input class="form-control" name="ok_'+shift+'_jaminan[]" type="text" placeholder="Jaminan"></div>'
    + '<div class="col-md-3"><input class="form-control" name="ok_'+shift+'_diagnosa[]" type="text" placeholder="Diagnosa"></div>'
    + '<div class="col-md-2"><input class="form-control" name="ok_'+shift+'_dpjp[]" type="text" placeholder="DPJP"></div>'
    + '<div class="col-md-1"><input class="form-control" name="ok_'+shift+'_jam[]" type="text" placeholder="Jam"></div>'
    + '<div class="col-md-1"><button type="button" class="btn btn-xs btn-danger btn-del-row" onclick="removeRow(this)" title="Hapus baris"><i class="fa fa-trash-o"></i></button></div>'
    + '</div></div>';
  $('#ok-'+shift+'-list').append(tpl);
}

var vkRowTpl = '<div class="mod-row-detail vk-detail-row"><div class="row">'
  + '<div class="col-md-3"><input class="form-control" name="vk_det_nama[]" type="text" placeholder="Nama / Umur"></div>'
  + '<div class="col-md-3"><input class="form-control" name="vk_det_jaminan[]" type="text" placeholder="Jaminan"></div>'
  + '<div class="col-md-3"><input class="form-control" name="vk_det_diagnosa[]" type="text" placeholder="Diagnosa"></div>'
  + '<div class="col-md-2"><input class="form-control" name="vk_det_dpjp[]" type="text" placeholder="DPJP"></div>'
  + '<div class="col-md-1"><button type="button" class="btn btn-xs btn-danger btn-del-row" onclick="removeRow(this)" title="Hapus baris"><i class="fa fa-trash-o"></i></button></div>'
  + '</div></div>';

function addVkRow() { $('#vk-detail-list').append(vkRowTpl); }

var perinaRowTpl = '<div class="mod-row-detail perina-detail-row"><div class="row">'
  + '<div class="col-md-3"><input class="form-control" name="pna_det_nama[]" type="text" placeholder="Nama Bayi / Umur"></div>'
  + '<div class="col-md-3"><input class="form-control" name="pna_det_jaminan[]" type="text" placeholder="Jaminan"></div>'
  + '<div class="col-md-3"><input class="form-control" name="pna_det_diagnosa[]" type="text" placeholder="Diagnosa"></div>'
  + '<div class="col-md-2"><input class="form-control" name="pna_det_dpjp[]" type="text" placeholder="DPJP"></div>'
  + '<div class="col-md-1"><button type="button" class="btn btn-xs btn-danger btn-del-row" onclick="removeRow(this)" title="Hapus baris"><i class="fa fa-trash-o"></i></button></div>'
  + '</div></div>';

function addPerinaRow() { $('#perina-detail-list').append(perinaRowTpl); }

/* ── Foto Kondisi Lapangan ───────────────────────────────────── */
var _fotoCounter = {};

function addFotoRow(sec) {
  if (!_fotoCounter[sec]) _fotoCounter[sec] = 0;
  var idx = _fotoCounter[sec]++;
  var html =
    '<div class="foto-new-row" id="fnr-' + sec + '-' + idx + '">' +
      '<div class="row" style="align-items:center;margin:0">' +
        '<div class="col-xs-4" style="padding-left:0">' +
          '<input type="file" name="foto_file[' + sec + '][]"' +
                 ' accept="image/*" class="foto-file-input"' +
                 ' onchange="previewFoto(this, \'fprev-' + sec + '-' + idx + '\')">' +
          '<img id="fprev-' + sec + '-' + idx + '"' +
               ' src="" style="display:none;width:100%;max-height:70px;object-fit:cover;' +
               'border-radius:4px;margin-top:4px;cursor:pointer"' +
               ' onclick="openFotoLb(this.src)">' +
        '</div>' +
        '<div class="col-xs-7">' +
          '<input type="text" class="form-control input-sm"' +
                 ' name="foto_ket[' + sec + '][]" placeholder="Keterangan foto...">' +
        '</div>' +
        '<div class="col-xs-1" style="padding-left:4px">' +
          '<button type="button" class="btn btn-xs btn-danger"' +
                  ' onclick="$(this).closest(\'.foto-new-row\').remove()"' +
                  ' title="Hapus">' +
            '<i class="fa fa-trash-o"></i>' +
          '</button>' +
        '</div>' +
      '</div>' +
    '</div>';
  $('#fnew-' + sec).append(html);
}

function previewFoto(input, imgId) {
  if (input.files && input.files[0]) {
    var reader = new FileReader();
    reader.onload = function(e) {
      var img = document.getElementById(imgId);
      if (img) { img.src = e.target.result; img.style.display = 'block'; }
    };
    reader.readAsDataURL(input.files[0]);
  }
}

function delExistFoto(btn, sec, fotoId) {
  if (!confirm('Hapus foto ini?')) return;
  var $row = $(btn).closest('.foto-exist-row');
  // hapus input foto_keep agar tidak dikirim, tambah marker delete
  $row.find('input[name^="foto_keep"]').remove();
  $row.find('input[name^="foto_ket_exist"]').remove();
  $('#fdel-' + sec).append(
    '<input type="hidden" name="foto_delete[' + sec + '][]" value="' + fotoId + '">'
  );
  $row.fadeOut(200, function() { $(this).remove(); });
}

function openFotoLb(src) {
  if (!src) return;
  $('#foto-lightbox-img').attr('src', src);
  $('#foto-lightbox-overlay').addClass('active');
}

$(document).on('click', '#foto-lightbox-overlay', function() {
  $(this).removeClass('active');
  $('#foto-lightbox-img').attr('src', '');
});

/* ── Auto-sum: hitung Total otomatis dari sub-field penjamin ── */
(function(){
  var rules = {
    'igd_jml':    ['igd_bpjs','igd_umum','igd_asuransi','igd_naker','igd_Karyawan', 'igd_ranap', 'igd_doa', 'igd_doe', 'igd_jml_rujukan_ditolak', 'igd_jml_menolak_ranap'],
    'rj_jml':     ['rj_bpjs','rj_umum','rj_asuransi','rj_naker','rj_Karyawan','rj_ranap'],
    'hd_jml':     ['hd_bpjs','hd_umum','hd_asuransi','hd_ranap'],
    'ri_jml':     ['ri_bpjs','ri_umum','ri_asuransi','ri_naker','ri_rencana_op'],
    'icu_total':  ['icu_bpjs','icu_umum','icu_asuransi'],
    'picu_total': ['picu_bpjs','picu_umum','picu_asuransi'],
    'nicu_total': ['nicu_bpjs','nicu_umum','nicu_asuransi'],
    'vk_jml':     ['vk_bpjs','vk_umum','vk_asuransi','vk_jml_rujukan','vk_jml_ditolak'],
    'pna_jml':    ['pna_bpjs','pna_umum','pna_asuransi','pna_jml_bayi_sakit'],
    'lab_jml':    ['lab_bpjs','lab_umum','lab_asuransi','lab_naker','lab_Karyawan','lab_pk','lab_pa'],
    'frm_jml':    ['frm_bpjs','frm_umum','frm_asuransi','frm_naker','frm_Karyawan','frm_obat_bebas'],
    'rad_jml':    ['rad_bpjs','rad_umum','rad_asuransi','rad_naker','rad_xray','rad_usg']
  };

  // Build reverse lookup: source field → target total
  var sourceMap = {};
  $.each(rules, function(total, sources){
    $.each(sources, function(i, src){
      sourceMap[src] = total;
    });
  });

  function recalc(totalName){
    var sum = 0;
    $.each(rules[totalName], function(i, src){
      sum += parseInt($('input[name="'+src+'"]').val()) || 0;
    });
    var $total = $('input[name="'+totalName+'"]');
    $total.val(sum);
    // flash animation
    $total.css('background','#dbeafe');
    setTimeout(function(){ $total.css('background','#eff6ff'); }, 300);
  }

  // Bind input events on all source fields
  $(document).on('input change', '.mod-number', function(){
    var name = $(this).attr('name');
    if (sourceMap[name]) {
      recalc(sourceMap[name]);
    }
  });

  // Initial calculation on page load
  $.each(rules, function(total){ recalc(total); });
})();
</script>

<!-- ===================== MODAL LAPORAN MOD ===================== -->
<div class="modal fade" id="modal-laporan-mod" tabindex="-1" role="dialog" aria-labelledby="modalLaporanModLabel">
  <div class="modal-dialog modal-lg" role="document" style="width:90%;max-width:900px">
    <div class="modal-content">

      <div class="modal-header" style="background:linear-gradient(135deg,#0369a1,#0ea5e9);color:#fff;border-radius:5px 5px 0 0">
        <button type="button" class="close" data-dismiss="modal" style="color:#fff;opacity:.8">
          <span>&times;</span>
        </button>
        <h4 class="modal-title" id="modalLaporanModLabel" style="font-weight:700">
          <i class="fa fa-file-text-o" style="margin-right:6px"></i> Laporan MOD
        </h4>
      </div>

      <div class="modal-body" id="modal-report-body"
           style="max-height:75vh;overflow-y:auto;padding:20px 24px">
        <!-- konten laporan dimuat via AJAX -->
      </div>

      <div class="modal-footer" style="border-top:1px solid #e2e8f0;padding:10px 16px">
        <button type="button" onclick="printModReport()" class="btn btn-info btn-sm">
          <i class="fa fa-print"></i> Cetak Laporan
        </button>
        <button type="button" onclick="getMenu('eksekutif/Eks_laporan_mod')" class="btn btn-success btn-sm">
          <i class="fa fa-list"></i> Ke Daftar
        </button>
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">
          <i class="fa fa-times"></i> Tutup
        </button>
      </div>

    </div>
  </div>
</div>

<!-- hidden wrapper untuk cetak via window.print() -->
<div id="mod-report-printable-wrap"></div>

<!-- Lightbox overlay untuk preview foto -->
<div id="foto-lightbox-overlay">
  <img id="foto-lightbox-img" src="" alt="Preview Foto">
</div>
