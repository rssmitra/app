<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>

<style>
  /* ── Filter Card ─────────────────────────────────────── */
  .filter-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    margin-bottom: 18px;
    box-shadow: 0 1px 4px rgba(0,0,0,.06);
    overflow: hidden;
  }
  .filter-header {
    background: #f1f5f9;
    border-bottom: 1px solid #e2e8f0;
    padding: 9px 16px;
    font-size: 12px;
    font-weight: 700;
    color: #334155;
    letter-spacing: .2px;
  }
  .filter-header i { color: #64748b; margin-right: 5px; }
  .filter-body   { padding: 14px 16px 6px; }
  .filter-footer {
    background: #f8fafc;
    border-top: 1px solid #e2e8f0;
    padding: 10px 16px;
  }

  /* Field label (above each input) */
  .filter-label {
    display: block;
    font-size: 10.5px;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: .5px;
    margin-bottom: 5px;
  }
  .filter-label i { color: #94a3b8; margin-right: 3px; }

  /* Search-by select inside input-group-addon */
  .search-addon {
    padding: 0;
    background: #f1f5f9;
    border-right: 1px solid #cdd5e0;
  }
  .search-addon select {
    border: 0;
    background: transparent;
    height: 28px;
    padding: 3px 8px;
    min-width: 115px;
    font-size: 12px;
    font-weight: 600;
    color: #334155;
    cursor: pointer;
    border-radius: 3px 0 0 3px;
    -webkit-appearance: auto;
  }
  .search-addon select:focus { outline: none; }

  /* DataTable cells */
  #dynamic-table td { vertical-align: top; font-size: 12px; }
  #dynamic-table td:nth-child(4),
  #dynamic-table td:nth-child(5),
  #dynamic-table td:nth-child(6),
  #dynamic-table td:nth-child(7),
  #dynamic-table td:nth-child(8),
  #dynamic-table td:nth-child(9),
  #dynamic-table td:nth-child(10) { min-width: 180px; }
</style>

<script>
jQuery(function($) {

  $('.date-picker').datepicker({
    autoclose: true,
    todayHighlight: true
  }).next().on(ace.click_event, function(){
    $(this).prev().focus();
  });

});

var oTable;
var base_url = $('#dynamic-table').attr('base-url');

$(document).ready(function() {

  // Dynamic placeholder based on search_by
  var placeholders = {
    'no_mr'       : 'Contoh: 00313889',
    'nama_pasien' : 'Cari nama pasien...'
  };
  $('#search_by').on('change', function() {
    $('#keyword').attr('placeholder', placeholders[$(this).val()] || 'Kata kunci...');
  });

  $(".form-control").keypress(function(event) {
    var keycode = (event.keyCode ? event.keyCode : event.which);
    if (keycode == 13) {
      event.preventDefault();
      $('#btn_search_data').click();
      return false;
    }
  });

  oTable = $('#dynamic-table').DataTable({
    "processing": true,
    "serverSide": true,
    "ordering": false,
    "searching": false,
    "pageLength": 25,
    "ajax": {
      "url": base_url,
      "type": "POST"
    },
    "columns": [
      { "title": "No",           "width": "40px",  "className": "center" },
      { "title": "Pasien",       "width": "200px" },
      { "title": "PPA",          "width": "140px" },
      { "title": "Subjective",   "orderable": false },
      { "title": "Objective",    "orderable": false },
      { "title": "Assesment",    "orderable": false },
      { "title": "Planning",     "orderable": false },
      { "title": "Eresep",       "orderable": false },
      { "title": "Penunjang",    "orderable": false },
      { "title": "File EMR",     "orderable": false },
    ],
  });

  $('#btn_search_data').click(function(e) {
    e.preventDefault();
    $.ajax({
      url: $('#form_filter').attr('action'),
      type: 'post',
      data: $('#form_filter').serialize(),
      dataType: 'json',
      beforeSend: function() { achtungShowLoader(); },
      success: function(data) {
        achtungHideLoader();
        var params = data.data;
        oTable.ajax.url(base_url + '?' + params).load();
      }
    });
  });

  $('#btn_reset_data').click(function(e) {
    e.preventDefault();
    $('#form_filter')[0].reset();
    oTable.ajax.url(base_url).load();
  });

  $('#btn_export_excel').click(function(e) {
    e.preventDefault();
    var params = $('#form_filter').serialize();
    window.open('<?php echo site_url('rekam_medis/Rm_soap/export_excel')?>?' + params, '_blank');
  });

});
</script>

<div class="row">
  <div class="col-xs-12">

    <div class="page-header">
      <h1>
        <?php echo $title ?>
        <small>
          <i class="ace-icon fa fa-angle-double-right"></i>
          <?php echo isset($breadcrumbs) ? $breadcrumbs : '' ?>
        </small>
      </h1>
    </div>

    <form class="form-horizontal" method="post" id="form_filter"
          action="<?php echo site_url('rekam_medis/Rm_soap/find_data') ?>" autocomplete="off">

      <div class="filter-card">

        <!-- ── Header ── -->
        <div class="filter-header">
          <i class="fa fa-filter"></i> Filter &amp; Pencarian Data SOAP
        </div>

        <!-- ── Body: Filter Controls ── -->
        <div class="filter-body">
          <div class="row">

            <!-- Pencarian Pasien -->
            <div class="col-md-5 col-sm-12" style="margin-bottom:10px;">
              <label class="filter-label">
                <i class="fa fa-search"></i> Pencarian Pasien
              </label>
              <div class="input-group">
                <span class="input-group-addon search-addon">
                  <select name="search_by" id="search_by">
                    <option value="no_mr">No. MR</option>
                    <option value="nama_pasien">Nama Pasien</option>
                  </select>
                </span>
                <input type="text" class="form-control input-sm" name="keyword" id="keyword"
                       placeholder="Contoh: 00313889">
              </div>
            </div>

            <!-- Tanggal Kunjungan -->
            <div class="col-md-5 col-sm-8" style="margin-bottom:10px;">
              <label class="filter-label">
                <i class="fa fa-calendar-o"></i> Tanggal Kunjungan
                <span style="text-transform:none;letter-spacing:0;font-weight:400;color:#94a3b8;">&nbsp;(default: bulan ini)</span>
              </label>
              <div class="row" style="margin:0 -4px;">
                <div class="col-xs-6" style="padding:0 4px;">
                  <div class="input-group">
                    <input class="form-control input-sm date-picker" name="from_tgl" id="from_tgl"
                           type="text" data-date-format="yyyy-mm-dd" placeholder="Dari">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                  </div>
                </div>
                <div class="col-xs-6" style="padding:0 4px;">
                  <div class="input-group">
                    <input class="form-control input-sm date-picker" name="to_tgl" id="to_tgl"
                           type="text" data-date-format="yyyy-mm-dd" placeholder="Sampai">
                    <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Tipe Kunjungan -->
            <div class="col-md-2 col-sm-4" style="margin-bottom:10px;">
              <label class="filter-label">
                <i class="fa fa-tag"></i> Tipe
              </label>
              <select name="tipe" class="form-control input-sm">
                <option value="">Semua Tipe</option>
                <option value="RJ">Rawat Jalan</option>
                <option value="RI">Rawat Inap</option>
              </select>
            </div>

          </div><!-- /.row -->
        </div><!-- /.filter-body -->

        <!-- ── Footer: Action Buttons ── -->
        <div class="filter-footer">
          <button type="button" id="btn_search_data" class="btn btn-sm btn-primary">
            <i class="fa fa-search"></i> Tampilkan Data
          </button>
          <button type="button" id="btn_reset_data" class="btn btn-sm btn-default" style="margin-left:5px;">
            <i class="fa fa-times-circle-o"></i> Reset Filter
          </button>
          <button type="button" id="btn_export_excel" class="btn btn-sm btn-success" style="margin-left:5px;">
            <i class="fa fa-file-excel-o"></i> Export Excel
          </button>
        </div><!-- /.filter-footer -->

      </div><!-- /.filter-card -->

      <table id="dynamic-table"
             base-url="<?php echo site_url('rekam_medis/Rm_soap/get_data') ?>"
             class="table table-bordered table-hover table-condensed">
        <thead>
          <tr>
            <th class="center" width="40">No</th>
            <th width="200">Pasien</th>
            <th width="140">PPA</th>
            <th width="180">Subjective</th>
            <th width="180">Objective</th>
            <th width="180">Assesment</th>
            <th width="180">Planning</th>
            <th width="180">Eresep</th>
            <th width="160">Penunjang</th>
            <th width="160">File EMR</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>

    </form>

  </div><!-- /.col -->
</div><!-- /.row -->
