<script src="<?php echo base_url()?>assets/js/sweetalert2.all.min.js"></script>

<style>
  /* report styles di dalam modal */
  #modal-laporan-mod .mod-report { font-family:'Segoe UI',Arial,sans-serif; font-size:13px; color:#1a1a1a; }
  #modal-laporan-mod .mod-report-header { text-align:center; margin-bottom:18px; }
  #modal-laporan-mod .mod-report-header h2 { font-size:15px; font-weight:700; margin:0 0 4px; }
  #modal-laporan-mod .mod-report-header p  { margin:2px 0; font-size:12.5px; }
  #modal-laporan-mod .mod-section-title {
    background: linear-gradient(135deg,#0369a1,#0ea5e9);
    color:#fff; padding:5px 12px; font-size:12.5px; font-weight:700;
    border-radius:4px; margin:14px 0 7px;
  }
  #modal-laporan-mod .mod-kv { display:flex; flex-wrap:wrap; gap:3px 0; }
  #modal-laporan-mod .mod-kv-item { width:50%; display:flex; padding:3px 0; }
  #modal-laporan-mod .mod-kv-item.full { width:100%; }
  #modal-laporan-mod .mod-kv-label { color:#555; min-width:200px; font-size:12px; }
  #modal-laporan-mod .mod-kv-val   { font-weight:600; font-size:12px; }
  #modal-laporan-mod .mod-badge { display:inline-block; border-radius:10px; padding:1px 8px; font-size:11px; font-weight:700; }
  #modal-laporan-mod .mod-badge-bpjs     { background:#dbeafe; color:#1d4ed8; }
  #modal-laporan-mod .mod-badge-umum     { background:#fef9c3; color:#854d0e; }
  #modal-laporan-mod .mod-badge-asuransi { background:#d1fae5; color:#065f46; }
  #modal-laporan-mod .mod-badge-naker    { background:#ede9fe; color:#4c1d95; }
  #modal-laporan-mod .mod-badge-rssm     { background:#fee2e2; color:#991b1b; }
  #modal-laporan-mod .mod-table { width:100%; border-collapse:collapse; font-size:12px; margin-top:5px; }
  #modal-laporan-mod .mod-table th { background:#f1f5f9; border:1px solid #d1d5db; padding:4px 7px; font-size:11.5px; }
  #modal-laporan-mod .mod-table td { border:1px solid #e5e7eb; padding:4px 7px; vertical-align:top; }
  #modal-laporan-mod .mod-table tr:nth-child(even) td { background:#f8fafc; }
  #modal-laporan-mod .tt-grid  { display:flex; flex-wrap:wrap; gap:6px; margin:5px 0; }
  #modal-laporan-mod .tt-card  { background:#f1f5f9; border:1px solid #d1d5db; border-radius:5px; padding:6px 12px; text-align:center; min-width:80px; }
  #modal-laporan-mod .tt-card .tt-num   { font-size:20px; font-weight:700; color:#0369a1; }
  #modal-laporan-mod .tt-card .tt-label { font-size:10.5px; color:#64748b; }
  #modal-laporan-mod .tt-total          { background:#0369a1; color:#fff; }
  #modal-laporan-mod .tt-total .tt-num  { color:#fff; }
  #modal-laporan-mod .tt-total .tt-label{ color:#bae6fd; }
  #modal-laporan-mod .mod-closing { text-align:center; margin-top:18px; font-style:italic; color:#555; font-size:12.5px; padding:10px; border-top:1px solid #e2e8f0; }
</style>

<div class="page-header">
  <h1>
    <?php echo $title ?>
    <small>
      <i class="ace-icon fa fa-angle-double-right"></i>
      <?php echo $breadcrumbs ?>
    </small>
  </h1>
</div>

<div class="row">
  <div class="col-xs-12">

    <div class="clearfix" style="margin-bottom:-5px">
      <?php echo $this->authuser->show_button('eksekutif/Eks_laporan_mod', 'C', '', 1) ?>
      <?php echo $this->authuser->show_button('eksekutif/Eks_laporan_mod', 'D', '', 5) ?>
    </div>
    <hr class="separator">

    <div style="margin-top:-27px">
      <table id="dynamic-table" base-url="eksekutif/Eks_laporan_mod" class="table table-bordered table-hover">
        <thead>
          <tr>
            <th width="30px" class="center"></th>
            <th width="150px">&nbsp;</th>
            <th width="40px" class="center">No</th>
            <th>Tanggal</th>
            <th>Nama MOD</th>
            <th width="80px" class="center">Shift</th>
            <th width="80px" class="center">Status</th>
            <th width="110px">Dibuat</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>
    </div>

  </div>
</div>

<!-- ===================== MODAL LAPORAN MOD ===================== -->
<div class="modal fade" id="modal-laporan-mod" tabindex="-1" role="dialog">
  <div class="modal-dialog modal-lg" role="document" style="width:90%;max-width:900px">
    <div class="modal-content">
      <div class="modal-header" style="background:linear-gradient(135deg,#0369a1,#0ea5e9);color:#fff;border-radius:5px 5px 0 0">
        <button type="button" class="close" data-dismiss="modal" style="color:#fff;opacity:.8"><span>&times;</span></button>
        <h4 class="modal-title" style="font-weight:700">
          <i class="fa fa-file-text-o" style="margin-right:6px"></i> Laporan MOD
        </h4>
      </div>
      <div class="modal-body" id="modal-report-body" style="max-height:75vh;overflow-y:auto;padding:20px 24px">
      </div>
      <div class="modal-footer" style="border-top:1px solid #e2e8f0;padding:10px 16px">
        <button type="button" onclick="printModReport()" class="btn btn-info btn-sm">
          <i class="fa fa-print"></i> Cetak Laporan
        </button>
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">
          <i class="fa fa-times"></i> Tutup
        </button>
      </div>
    </div>
  </div>
</div>

<script src="<?php echo base_url().'assets/js/custom/als_datatable.js' ?>"></script>

<script>
var Swal = window.Swal || window.Sweetalert2;

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
    })
    .fail(function() {
      $('#modal-report-body').html(
        '<div class="alert alert-danger"><i class="fa fa-exclamation-circle"></i> Gagal memuat laporan.</div>'
      );
    });
};

function printModReport() {
  var printWin = window.open('', '_blank', 'width=900,height=700');
  var styles = [
    'body{font-family:"Segoe UI",Arial,sans-serif;font-size:12px;color:#000;margin:20px}',
    '.mod-report-header{text-align:center;margin-bottom:18px}',
    '.mod-report-header h2{font-size:15px;font-weight:700;margin:0 0 4px}',
    '.mod-report-header p{margin:2px 0;font-size:12.5px}',
    '.mod-section-title{background:linear-gradient(135deg,#0369a1,#0ea5e9);color:#fff;padding:5px 12px;font-size:12.5px;font-weight:700;border-radius:4px;margin:14px 0 7px;-webkit-print-color-adjust:exact;print-color-adjust:exact}',
    '.mod-kv{display:flex;flex-wrap:wrap}',
    '.mod-kv-item{width:50%;display:flex;padding:3px 0}',
    '.mod-kv-item.full{width:100%}',
    '.mod-kv-label{color:#555;min-width:200px;font-size:11.5px}',
    '.mod-kv-val{font-weight:600;font-size:11.5px}',
    '.mod-badge{display:inline-block;border-radius:10px;padding:1px 7px;font-size:10.5px;font-weight:700;-webkit-print-color-adjust:exact;print-color-adjust:exact}',
    '.mod-badge-bpjs{background:#dbeafe;color:#1d4ed8}',
    '.mod-badge-umum{background:#fef9c3;color:#854d0e}',
    '.mod-badge-asuransi{background:#d1fae5;color:#065f46}',
    '.mod-badge-naker{background:#ede9fe;color:#4c1d95}',
    '.mod-badge-rssm{background:#fee2e2;color:#991b1b}',
    '.mod-table{width:100%;border-collapse:collapse;font-size:11.5px;margin-top:5px}',
    '.mod-table th{background:#f1f5f9;border:1px solid #ccc;padding:4px 7px;-webkit-print-color-adjust:exact;print-color-adjust:exact}',
    '.mod-table td{border:1px solid #ddd;padding:4px 7px;vertical-align:top}',
    '.mod-table tr:nth-child(even) td{background:#f8fafc;-webkit-print-color-adjust:exact;print-color-adjust:exact}',
    '.tt-grid{display:flex;flex-wrap:wrap;gap:6px;margin:5px 0}',
    '.tt-card{background:#f1f5f9;border:1px solid #ccc;border-radius:4px;padding:5px 10px;text-align:center;min-width:75px;-webkit-print-color-adjust:exact;print-color-adjust:exact}',
    '.tt-card .tt-num{font-size:18px;font-weight:700;color:#0369a1}',
    '.tt-card .tt-label{font-size:10px;color:#64748b}',
    '.tt-total{background:#0369a1;color:#fff;-webkit-print-color-adjust:exact;print-color-adjust:exact}',
    '.tt-total .tt-num{color:#fff}.tt-total .tt-label{color:#bae6fd}',
    '.mod-closing{text-align:center;margin-top:18px;font-style:italic;color:#555;font-size:12px;padding:10px;border-top:1px solid #e2e8f0}'
  ].join('');

  printWin.document.write(
    '<!DOCTYPE html><html><head>'
    + '<meta charset="UTF-8"><title>Laporan MOD</title>'
    + '<style>' + styles + '</style>'
    + '</head><body>'
    + document.getElementById('modal-report-body').innerHTML
    + '</body></html>'
  );
  printWin.document.close();
  printWin.focus();
  setTimeout(function() { printWin.print(); }, 600);
}
</script>
