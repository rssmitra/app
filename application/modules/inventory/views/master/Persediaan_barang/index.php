<style>
.pb-flag-tab { display:inline-flex; gap:6px; margin-bottom:12px; }
.pb-flag-tab a {
    padding:6px 18px; border-radius:20px; font-size:12px; font-weight:600;
    border:2px solid #0891b2; color:#0891b2; text-decoration:none; transition:.2s;
}
.pb-flag-tab a.active, .pb-flag-tab a:hover { background:#0891b2; color:#fff; }

/* Summary Cards */
.pb-summary-wrap { display:flex; gap:10px; flex-wrap:wrap; margin-bottom:14px; }
.pb-card {
    flex:1; min-width:150px; border-radius:8px; padding:12px 16px; text-align:center;
    border:1px solid #e2e8f0;
}
.pb-card .pb-label { font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.4px; }
.pb-card .pb-value { font-size:22px; font-weight:900; line-height:1.2; margin-top:4px; }
.pb-card .pb-sub   { font-size:10px; color:#64748b; margin-top:2px; }

/* DataTable row expand */
td.pb-detail-ctrl { cursor:pointer; text-align:center !important; }
#tbl-persediaan tbody tr.shown { background:#f0f9ff !important; }
#tbl-persediaan tbody tr.shown + tr > td {
    border-top:2px solid #0891b2;
    border-bottom:2px solid #0891b2;
}
</style>

<div class="row">
  <div class="col-xs-12">

    <div class="page-header">
      <h1>
        <span id="pb-page-title"><?php echo $title ?></span>
        <small>
          <i class="ace-icon fa fa-angle-double-right"></i>
          <?php echo isset($breadcrumbs) ? $breadcrumbs : '' ?>
        </small>
      </h1>
    </div>

    <!-- Tab Medis / Non Medis -->
    <div class="pb-flag-tab">
      <a href="#" data-flag="medis"
         class="pb-tab-btn <?php echo ($flag_string === 'medis') ? 'active' : '' ?>">
        <i class="fa fa-medkit"></i> Barang Medis
      </a>
      <a href="#" data-flag="non_medis"
         class="pb-tab-btn <?php echo ($flag_string === 'non_medis') ? 'active' : '' ?>">
        <i class="fa fa-archive"></i> Barang Non Medis
      </a>
    </div>

    <!-- Filter -->
    <div class="well well-sm" style="padding:10px;margin-bottom:10px">
      <form class="form-inline" id="form-filter-pb">
        <input type="hidden" id="pb_flag" value="<?php echo $flag_string ?>">
        <div class="form-group" style="margin-right:8px">
          <label style="font-size:12px">Unit / Bagian</label>
          <select name="kode_bagian" id="sel-bagian" class="form-control input-sm" style="min-width:200px">
            <option value="">-- Semua Unit --</option>
            <?php foreach ($bagian_list as $b): ?>
            <option value="<?php echo htmlspecialchars($b->kode_bagian) ?>">
              <?php echo htmlspecialchars($b->nama_bagian) ?>
            </option>
            <?php endforeach ?>
          </select>
        </div>
        <div class="form-group" style="margin-right:8px">
          <label style="font-size:12px">Per Tanggal</label>
          <input type="date" id="tgl-filter-pb" class="form-control input-sm" style="width:150px"
                 title="Kosongkan untuk menampilkan data terkini">
        </div>
        <button type="button" class="btn btn-xs btn-primary" id="btn-filter-pb">
          <i class="fa fa-search"></i> Filter
        </button>
        <button type="button" class="btn btn-xs btn-default" id="btn-reset-pb">
          <i class="fa fa-refresh"></i> Reset
        </button>
      </form>
    </div>

    <!-- Summary Cards -->
    <div class="pb-summary-wrap" id="pb-summary-wrap">
      <div class="pb-card" style="background:#eff6ff;border-color:#bfdbfe">
        <div class="pb-label" style="color:#3b82f6">Total Jenis Barang</div>
        <div class="pb-value" style="color:#1d4ed8" id="pb-total-jenis">
          <i class="fa fa-spinner fa-spin text-info"></i>
        </div>
        <div class="pb-sub">item</div>
      </div>
      <!-- <div class="pb-card" style="background:#f0fdf4;border-color:#86efac">
        <div class="pb-label" style="color:#16a34a">Total Stok</div>
        <div class="pb-value" style="color:#15803d" id="pb-total-stok">
          <i class="fa fa-spinner fa-spin text-success"></i>
        </div>
        <div class="pb-sub">unit (satuan kecil)</div>
      </div> -->
      <div class="pb-card" style="flex:2;min-width:220px;background:linear-gradient(135deg,#0891b2,#0e7490)">
        <div class="pb-label" style="color:#e0f2fe">Total Nilai Persediaan</div>
        <div class="pb-value" style="color:#fff;font-size:20px" id="pb-total-nilai">
          <i class="fa fa-spinner fa-spin" style="color:#bae6fd"></i>
        </div>
        <div class="pb-sub" style="color:#bae6fd" id="pb-summary-sub">berdasarkan WA harga modal 3 PO terakhir</div>
      </div>
    </div>

    <!-- DataTable -->
    <table id="tbl-persediaan" class="table table-bordered table-hover table-condensed" style="font-size:12px">
      <thead>
        <tr style="background:#f1f5f9">
          <th class="center" width="20"></th>
          <th class="center" width="40">No</th>
          <th width="110">Kode Barang</th>
          <th>Nama Barang / Satuan</th>
          <th class="center" width="80">Satuan</th>
          <th class="center" width="100">Jumlah Stok</th>
          <th class="center" width="140" style="background:#e0f2fe">Harga WA / Sat (Rp)</th>
          <th class="center" width="160" style="background:#fef9c3">Total Nilai Persediaan (Rp)</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>

  </div>
</div>

<script type="text/javascript">
var _pbFlag    = '<?php echo $flag_string ?>';
var _pbBaseUrl = '<?php echo site_url('inventory/master/Persediaan_barang') ?>';
var oTablePb;

// Polyfill: $.unique dihapus di jQuery 3.7+
if (typeof $.unique === 'undefined' && typeof $.uniqueSort !== 'undefined') {
    $.unique = $.uniqueSort;
}

// ── Muat ringkasan summary ──
function pbLoadSummary() {
    // Tampilkan spinner
    $('#pb-total-jenis').html('<i class="fa fa-spinner fa-spin text-info"></i>');
    $('#pb-total-stok').html('<i class="fa fa-spinner fa-spin text-success"></i>');
    $('#pb-total-nilai').html('<i class="fa fa-spinner fa-spin" style="color:#bae6fd"></i>');

    var tgl = $('#tgl-filter-pb').val();
    var subLabel = tgl
        ? 'WA 3 PO terakhir s.d. ' + pbFmtTgl(tgl)
        : 'berdasarkan WA harga modal 3 PO terakhir';
    $('#pb-summary-sub').text(subLabel);

    $.get(_pbBaseUrl + '/get_summary', {
        flag:        _pbFlag,
        kode_bagian: $('#sel-bagian').val(),
        tgl_filter:  tgl
    }, function (res) {
        if (res.status === 200) {
            $('#pb-total-jenis').text(pbFmt(res.total_jenis));
            $('#pb-total-stok').text(pbFmt(Math.round(res.total_stok)));
            $('#pb-total-nilai').html('Rp&nbsp;' + pbFmt(Math.round(res.total_nilai)));
        }
    }, 'json').fail(function () {
        $('#pb-total-jenis, #pb-total-stok, #pb-total-nilai').text('-');
    });
}

// Format tanggal YYYY-MM-DD → DD/MM/YYYY
function pbFmtTgl(s) {
    if (!s) return '';
    var p = s.split('-');
    return p.length === 3 ? p[2] + '/' + p[1] + '/' + p[0] : s;
}

function pbFmt(n) {
    return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

$(function () {

    // ── Init DataTable ──
    oTablePb = $('#tbl-persediaan').DataTable({
        destroy:    true,
        processing: true,
        serverSide: true,
        ordering:   false,
        ajax: {
            url:  _pbBaseUrl + '/get_data',
            type: 'POST',
            data: function (d) {
                d.flag         = _pbFlag;
                d.kode_bagian  = $('#sel-bagian').val();
                d.tgl_filter   = $('#tgl-filter-pb').val();
            }
        },
        columnDefs: [
            { targets: 0, data: 0, className: 'pb-detail-ctrl center', orderable: false, width: '20px',  defaultContent: '' },
            { targets: 1, data: 1, className: 'center',                 width: '40px',   defaultContent: '' },
            { targets: 2, data: 2,                                       defaultContent: '' },
            { targets: 3, data: 3,                                       defaultContent: '' },
            { targets: 4, data: 4, className: 'center',                  defaultContent: '' },
            { targets: 5, data: 5, className: 'text-right',              defaultContent: '' },
            { targets: 6, data: 6, className: 'text-right',              defaultContent: '' },
            { targets: 7, data: 7, className: 'text-right',              defaultContent: '' },
        ],
        language: {
            processing:  '<i class="fa fa-spinner fa-spin"></i> Memuat data...',
            zeroRecords: 'Tidak ada data ditemukan',
            emptyTable:  'Tidak ada data tersedia',
            info:        'Menampilkan _START_ s/d _END_ dari _TOTAL_ data',
            infoEmpty:   'Menampilkan 0 data',
            search:      'Cari:',
            lengthMenu:  'Tampilkan _MENU_ data',
            paginate:    { first: 'Pertama', last: 'Terakhir', next: '&raquo;', previous: '&laquo;' }
        },
        pageLength: 25,
    });

    // ── Tab Medis / Non Medis ──
    $('.pb-tab-btn').on('click', function (e) {
        e.preventDefault();
        var flag = $(this).data('flag');
        if (flag === _pbFlag) return;

        _pbFlag = flag;
        $('.pb-tab-btn').removeClass('active');
        $(this).addClass('active');
        $('#pb-page-title').text(
            flag === 'non_medis' ? 'Persediaan Barang Non Medis' : 'Persediaan Barang Medis'
        );

        // Reset filter bagian (pertahankan tgl_filter) + tutup semua child row
        $('#sel-bagian').val('');
        $('#tbl-persediaan tbody tr.shown').each(function () {
            oTablePb.row($(this)).child.hide();
            $(this).removeClass('shown')
                   .find('.pb-dc-icon')
                   .removeClass('fa-minus-circle').addClass('fa-plus-circle')
                   .css('color', '#0891b2');
        });

        pbLoadSummary();
        oTablePb.ajax.reload();
    });

    // ── Filter & Reset ──
    $('#btn-filter-pb').on('click', function () {
        pbLoadSummary();
        oTablePb.ajax.reload();
    });
    $('#btn-reset-pb').on('click', function () {
        $('#sel-bagian').val('');
        $('#tgl-filter-pb').val('');
        pbLoadSummary();
        oTablePb.ajax.reload();
    });

    // ── Row expand / collapse ──
    $('#tbl-persediaan tbody').on('click', 'td.pb-detail-ctrl', function () {
        var tr      = $(this).closest('tr');
        var row     = oTablePb.row(tr);
        var rowData = row.data();
        var kode    = rowData[2];
        var icon    = tr.find('.pb-dc-icon');

        if (row.child.isShown()) {
            row.child.hide();
            tr.removeClass('shown');
            icon.removeClass('fa-minus-circle').addClass('fa-plus-circle').css('color', '#0891b2');
        } else {
            icon.removeClass('fa-plus-circle').addClass('fa-minus-circle').css('color', '#dc2626');
            row.child(
                '<td colspan="8" style="padding:20px;text-align:center;background:#f8fafc">' +
                '<i class="fa fa-spinner fa-spin text-info"></i>' +
                ' <span style="color:#64748b;font-size:12px">Memuat data...</span></td>'
            ).show();
            tr.addClass('shown');

            $.ajax({
                url:      _pbBaseUrl + '/get_detail',
                type:     'GET',
                dataType: 'json',
                data:     { kode_brg: kode, flag: _pbFlag, tgl_filter: $('#tgl-filter-pb').val() },
                success: function (res) {
                    if (res.status === 200) {
                        row.child(
                            '<td colspan="8" style="padding:16px 24px;background:#f8fafc">' +
                            res.html + '</td>'
                        ).show();
                    } else {
                        row.child(
                            '<td colspan="8"><div class="alert alert-danger" style="margin:10px">' +
                            '<i class="fa fa-exclamation-triangle"></i> Gagal memuat data detail.</div></td>'
                        ).show();
                    }
                },
                error: function () {
                    row.child(
                        '<td colspan="8"><div class="alert alert-danger" style="margin:10px">' +
                        '<i class="fa fa-exclamation-triangle"></i> Koneksi gagal. Silahkan coba lagi.</div></td>'
                    ).show();
                }
            });
        }
    });

    // ── Filter mutasi per unit (delegasi ke child row yang dimuat dinamis) ──

    // Klik nama unit di tabel stok → filter baris mutasi sesuai kode_bagian
    $(document).on('click', '.pb-bagian-filter-btn', function (e) {
        e.preventDefault();
        var kodeBagian  = $(this).data('kode-bagian');
        var namaBagian  = $(this).data('nama-bagian');
        var container   = $(this).closest('.pb-detail-container');

        // Highlight baris yang aktif di tabel stok
        container.find('.pb-bagian-row').css('background', '');
        $(this).closest('.pb-bagian-row').css('background', '#dbeafe');

        // Filter baris mutasi
        var $rows    = container.find('.pb-mutasi-row');
        var $matched = $rows.filter('[data-kode-bagian="' + kodeBagian + '"]');
        var $others  = $rows.not('[data-kode-bagian="' + kodeBagian + '"]');

        $others.hide();
        $matched.show();

        // Tampilkan pesan kosong jika tidak ada mutasi untuk unit ini
        var noResult = container.find('.pb-mutasi-no-result');
        if ($matched.length === 0) {
            noResult.show();
        } else {
            noResult.hide();
        }

        // Update counter
        container.find('.pb-mutasi-count').text($matched.length);

        // Tampilkan badge filter aktif
        container.find('.pb-filter-label').text(namaBagian);
        container.find('.pb-filter-badge').css('display', 'inline-flex');
    });

    // Klik "Semua Unit" → reset filter, tampilkan semua baris mutasi
    $(document).on('click', '.pb-filter-reset', function (e) {
        e.preventDefault();
        var container = $(this).closest('.pb-detail-container');

        // Hapus highlight bagian
        container.find('.pb-bagian-row').css('background', '');

        // Tampilkan semua baris mutasi
        container.find('.pb-mutasi-row').show();
        container.find('.pb-mutasi-no-result').hide();

        // Reset counter ke total semua baris
        container.find('.pb-mutasi-count').text(
            container.find('.pb-mutasi-row').length
        );

        // Sembunyikan badge filter
        container.find('.pb-filter-badge').hide();
    });

    // ── Load summary awal ──
    pbLoadSummary();

});
</script>
