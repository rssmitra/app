<style>
.pbr-flag-tab { display:inline-flex; gap:6px; margin-bottom:12px; }
.pbr-flag-tab a {
    padding:6px 18px; border-radius:20px; font-size:12px; font-weight:600;
    border:2px solid #0891b2; color:#0891b2; text-decoration:none; transition:.2s;
}
.pbr-flag-tab a.active, .pbr-flag-tab a:hover { background:#0891b2; color:#fff; }

/* Summary Cards */
.pbr-summary-wrap { display:flex; gap:10px; flex-wrap:wrap; margin-bottom:14px; }
.pbr-card {
    flex:1; min-width:150px; border-radius:8px; padding:12px 16px; text-align:center;
    border:1px solid #e2e8f0;
}
.pbr-card .pbr-label { font-size:10px; font-weight:700; text-transform:uppercase; letter-spacing:.4px; }
.pbr-card .pbr-value { font-size:22px; font-weight:900; line-height:1.2; margin-top:4px; }
.pbr-card .pbr-sub   { font-size:10px; color:#64748b; margin-top:2px; }

/* DataTable row expand */
td.pbr-detail-ctrl { cursor:pointer; text-align:center !important; }
#tbl-pbrekap tbody tr.shown { background:#f0f9ff !important; }
#tbl-pbrekap tbody tr.shown + tr > td {
    border-top:2px solid #0891b2;
    border-bottom:2px solid #0891b2;
}
</style>

<div class="row">
  <div class="col-xs-12">

    <div class="page-header">
      <h1>
        <span id="pbr-page-title"><?php echo $title ?></span>
        <small>
          <i class="ace-icon fa fa-angle-double-right"></i>
          <?php echo isset($breadcrumbs) ? $breadcrumbs : '' ?>
        </small>
      </h1>
    </div>

    <!-- Tab Medis / Non Medis -->
    <div class="pbr-flag-tab">
      <a href="#" data-flag="medis"
         class="pbr-tab-btn <?php echo ($flag_string === 'medis') ? 'active' : '' ?>">
        <i class="fa fa-medkit"></i> Barang Medis
      </a>
      <a href="#" data-flag="non_medis"
         class="pbr-tab-btn <?php echo ($flag_string === 'non_medis') ? 'active' : '' ?>">
        <i class="fa fa-archive"></i> Barang Non Medis
      </a>
    </div>

    <!-- Filter -->
    <div class="well well-sm" style="padding:10px;margin-bottom:10px">
      <form class="form-inline" id="form-filter-pbr">
        <input type="hidden" id="pbr_flag" value="<?php echo $flag_string ?>">
        <div class="form-group" style="margin-right:8px">
          <label style="font-size:12px">Dari Tanggal</label>
          <input type="date" id="pbr-tgl-dari" class="form-control input-sm" style="width:150px">
        </div>
        <div class="form-group" style="margin-right:8px">
          <label style="font-size:12px">s.d.</label>
          <input type="date" id="pbr-tgl-sampai" class="form-control input-sm" style="width:150px">
        </div>
        <button type="button" class="btn btn-xs btn-primary" id="btn-filter-pbr">
          <i class="fa fa-search"></i> Filter
        </button>
        <button type="button" class="btn btn-xs btn-default" id="btn-reset-pbr">
          <i class="fa fa-refresh"></i> Reset
        </button>
        <!-- <a href="#" id="btn-cetak-pbr" target="_blank"
           class="btn btn-xs btn-success" style="margin-left:6px">
          <i class="fa fa-print"></i> Cetak Laporan
        </a> -->
      </form>
    </div>

    <!-- Summary Cards -->
    <div class="pbr-summary-wrap" id="pbr-summary-wrap">
      <div class="pbr-card" style="background:#eff6ff;border-color:#bfdbfe">
        <div class="pbr-label" style="color:#3b82f6">Total Unit</div>
        <div class="pbr-value" style="color:#1d4ed8" id="pbr-total-unit">
          <i class="fa fa-spinner fa-spin text-info"></i>
        </div>
        <div class="pbr-sub">unit / bagian</div>
      </div>
      <div class="pbr-card" style="background:#f0fdf4;border-color:#86efac">
        <div class="pbr-label" style="color:#16a34a">Total Jenis Item</div>
        <div class="pbr-value" style="color:#15803d" id="pbr-total-jenis">
          <i class="fa fa-spinner fa-spin text-success"></i>
        </div>
        <div class="pbr-sub">item barang</div>
      </div>
      <div class="pbr-card" style="flex:2;min-width:220px;background:linear-gradient(135deg,#0891b2,#0e7490)">
        <div class="pbr-label" style="color:#e0f2fe">Total Nilai Persediaan</div>
        <div class="pbr-value" style="color:#fff;font-size:20px" id="pbr-total-nilai">
          <i class="fa fa-spinner fa-spin" style="color:#bae6fd"></i>
        </div>
        <div class="pbr-sub" style="color:#bae6fd" id="pbr-summary-sub">berdasarkan WA harga modal 3 PO terakhir</div>
      </div>
    </div>

    <!-- DataTable -->
    <table id="tbl-pbrekap" class="table table-bordered table-hover table-condensed" style="font-size:12px">
      <thead>
        <tr style="background:#f1f5f9">
          <th class="center" width="20"></th>
          <th class="center" width="40">No</th>
          <th class="center" width="0" style="display:none">Kode</th>
          <th>Nama Unit / Bagian</th>
          <th class="center" width="80">Jumlah Item</th>
          <th class="center" width="130" style="background:#eff6ff">Saldo Awal (Rp)</th>
          <th class="center" width="130" style="background:#f0fdf4">Pembelian (Rp)</th>
          <th class="center" width="130" style="background:#fdf4ff">Penerimaan (Rp)</th>
          <th class="center" width="130" style="background:#fff7ed">Penjualan (Rp)</th>
          <th class="center" width="160" style="background:#fef9c3">Total Persediaan (Rp)</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>

  </div>
</div>

<!-- ═══════════════════════════════════════════════════════════
     Modal Konfirmasi Pengosongan Stok
     ═══════════════════════════════════════════════════════════ -->
<div id="pbrModalKosongkan" class="modal fade" tabindex="-1" role="dialog" style="z-index:99999">
  <div class="modal-dialog modal-md" role="document">
    <div class="modal-content">
      <div class="modal-header" style="background:#991b1b;padding:10px 16px">
        <button type="button" class="close" data-dismiss="modal"
          style="color:#fff;opacity:1;font-size:20px;margin-top:-2px">&times;</button>
        <h4 class="modal-title" style="color:#fff;font-size:14px;font-weight:700">
          <i class="fa fa-exclamation-triangle"></i>&nbsp; Konfirmasi Pengosongan Stok</h4>
      </div>
      <div class="modal-body" style="padding:16px 20px">
        <div style="background:#fef2f2;border:1px solid #fecaca;border-radius:6px;
                    padding:8px 12px;margin-bottom:14px;display:flex;align-items:flex-start;gap:8px">
          <i class="fa fa-warning" style="color:#dc2626;margin-top:2px;font-size:14px;flex-shrink:0"></i>
          <div style="font-size:11px;color:#7f1d1d;line-height:1.5">
            <strong>Tindakan ini tidak dapat dibatalkan.</strong><br>
            Stok barang akan dikosongkan menjadi <strong>0</strong> dan mutasi akan dicatat
            pada kartu stok (jenis transaksi: pengosongan stok unit).
          </div>
        </div>
        <div id="pbrKosongkanLoading" style="text-align:center;padding:20px 0">
          <i class="fa fa-spinner fa-spin text-info fa-2x"></i>
          <p style="color:#64748b;font-size:12px;margin-top:8px">Memuat data stok terkini...</p>
        </div>
        <div id="pbrKosongkanInfo" style="display:none">
          <table style="width:100%;font-size:12px;border-collapse:collapse;margin-bottom:12px">
            <tr>
              <td style="padding:4px 8px;width:130px;color:#64748b;border:1px solid #e2e8f0">Kode Barang</td>
              <td style="padding:4px 8px;border:1px solid #e2e8f0;font-family:monospace" id="pbrK-kode"></td>
            </tr>
            <tr>
              <td style="padding:4px 8px;color:#64748b;border:1px solid #e2e8f0">Nama Barang</td>
              <td style="padding:4px 8px;border:1px solid #e2e8f0;font-weight:700" id="pbrK-nama"></td>
            </tr>
            <tr>
              <td style="padding:4px 8px;color:#64748b;border:1px solid #e2e8f0">Unit / Bagian</td>
              <td style="padding:4px 8px;border:1px solid #e2e8f0" id="pbrK-bagian"></td>
            </tr>
            <tr>
              <td style="padding:4px 8px;color:#64748b;border:1px solid #e2e8f0">Satuan</td>
              <td style="padding:4px 8px;border:1px solid #e2e8f0" id="pbrK-satuan"></td>
            </tr>
            <tr style="background:#eff6ff">
              <td style="padding:4px 8px;color:#1d4ed8;font-weight:700;border:1px solid #bfdbfe">Stok Terakhir</td>
              <td style="padding:4px 8px;border:1px solid #bfdbfe;font-weight:700;color:#1d4ed8" id="pbrK-stok"></td>
            </tr>
            <tr style="background:#fffbeb">
              <td style="padding:4px 8px;color:#92400e;border:1px solid #fde68a">Harga WA / Sat</td>
              <td style="padding:4px 8px;border:1px solid #fde68a;color:#92400e" id="pbrK-harga"></td>
            </tr>
            <tr style="background:#fef9c3">
              <td style="padding:4px 8px;color:#713f12;font-weight:700;border:1px solid #fde68a">Total Nilai</td>
              <td style="padding:4px 8px;border:1px solid #fde68a;font-weight:700;color:#713f12" id="pbrK-nilai"></td>
            </tr>
            <tr>
              <td style="padding:4px 8px;color:#64748b;border:1px solid #e2e8f0;font-size:10px">ID Kartu Terakhir</td>
              <td style="padding:4px 8px;border:1px solid #e2e8f0;font-family:monospace;font-size:10px;color:#94a3b8" id="pbrK-idkartu"></td>
            </tr>
          </table>
          <div style="margin-bottom:4px">
            <label style="font-size:11px;font-weight:600;color:#374151">Keterangan
              <span style="font-weight:400;color:#94a3b8">(opsional)</span></label>
            <textarea id="pbrKosongkanKet" rows="2" placeholder="Alasan pengosongan stok..."
              style="width:100%;font-size:11px;padding:6px 8px;border:1px solid #cbd5e1;
                     border-radius:4px;resize:vertical;margin-top:4px;box-sizing:border-box"></textarea>
          </div>
        </div>
        <div id="pbrKosongkanError" style="display:none;background:#fef2f2;border:1px solid #fecaca;
             border-radius:6px;padding:8px 12px;font-size:11px;color:#991b1b">
          <i class="fa fa-exclamation-circle"></i> <span id="pbrKosongkanErrMsg"></span>
        </div>
      </div>
      <div class="modal-footer" style="padding:10px 16px">
        <button type="button" class="btn btn-default btn-sm" data-dismiss="modal">
          <i class="fa fa-times"></i> Batal</button>
        <button type="button" id="pbrBtnKonfirmasiKosongkan" class="btn btn-danger btn-sm" disabled>
          <i class="fa fa-check"></i> Ya, Kosongkan Stok</button>
      </div>
    </div>
  </div>
</div>

<script type="text/javascript">
var _pbrFlag    = '<?php echo $flag_string ?>';
var _pbrBaseUrl = '<?php echo site_url('inventory/master/Persediaan_barang_rekap_per_unit') ?>';
var oTablePbr;
var _curKodeBrg = '', _curKodeBagian = '', _curFlag = '', _curStok = 0;

// Polyfill: $.unique dihapus di jQuery 3.7+
if (typeof $.unique === 'undefined' && typeof $.uniqueSort !== 'undefined') {
    $.unique = $.uniqueSort;
}

/* ── Format angka ribuan (dot-separator) ── */
function pbrFmtNum(n) {
    return Math.round(n).toString().replace(/\B(?=(\d{3})+(?!$))/g, '.');
}

/* ── Buka modal konfirmasi kosongkan stok ── */
window.pbrOpenKosongkan = function (kodeBrg, namaBrg, kodeBagian, namaBagian) {
    _curKodeBrg    = kodeBrg;
    _curKodeBagian = kodeBagian;
    _curFlag       = _pbrFlag;

    $('#pbrKosongkanLoading').show();
    $('#pbrKosongkanInfo').hide();
    $('#pbrKosongkanError').hide();
    $('#pbrBtnKonfirmasiKosongkan').prop('disabled', true);
    $('#pbrKosongkanKet').val('');

    $('#pbrModalKosongkan').modal('show');

    $.get(_pbrBaseUrl + '/get_stok_item', {
        kode_brg:    kodeBrg,
        kode_bagian: kodeBagian,
        flag:        _curFlag
    }, function (res) {
        $('#pbrKosongkanLoading').hide();

        if (!res || res.status !== 200) {
            $('#pbrKosongkanErrMsg').text(res && res.message ? res.message : 'Gagal memuat data stok.');
            $('#pbrKosongkanError').show();
            return;
        }

        var d = res.data;
        _curStok = d.stok_akhir;

        $('#pbrK-kode').text(d.kode_brg);
        $('#pbrK-nama').text(d.nama_brg);
        $('#pbrK-bagian').html('<strong>' + namaBagian + '</strong>'
            + ' <span style="color:#94a3b8;font-size:10px">(' + kodeBagian + ')</span>');
        $('#pbrK-satuan').text(d.satuan_kecil ? d.satuan_kecil.toUpperCase() : '-');
        $('#pbrK-stok').text(pbrFmtNum(d.stok_akhir) + ' ' + (d.satuan_kecil || ''));
        $('#pbrK-harga').text(d.harga_kecil > 0 ? 'Rp ' + pbrFmtNum(d.harga_kecil) : '-');
        $('#pbrK-nilai').text(d.total_nilai > 0 ? 'Rp ' + pbrFmtNum(d.total_nilai) : 'Rp 0');
        $('#pbrK-idkartu').text('#' + d.id_kartu + ' \u2014 ' + (d.tgl_input || ''));

        if (d.stok_akhir <= 0) {
            $('#pbrKosongkanErrMsg').text('Stok barang ini sudah 0, tidak perlu dikosongkan.');
            $('#pbrKosongkanError').show();
        } else {
            $('#pbrKosongkanInfo').show();
            $('#pbrBtnKonfirmasiKosongkan').prop('disabled', false);
        }
    }, 'json').fail(function () {
        $('#pbrKosongkanLoading').hide();
        $('#pbrKosongkanErrMsg').text('Koneksi gagal. Silahkan coba lagi.');
        $('#pbrKosongkanError').show();
    });
};

/* ── Reload child-row untuk kode_bagian tertentu (tanpa reload seluruh tabel) ── */
function pbrReloadChildRow(kodeBagian) {
    $('#tbl-pbrekap tbody tr.shown').each(function () {
        var tr   = $(this);
        var icon = tr.find('.pbr-dc-icon');
        if (!icon.length) return;
        if (icon.attr('data-kode-bagian') !== String(kodeBagian)) return;

        // Child row yang diinjeksi DataTables selalu menjadi sibling <tr> berikutnya
        var childTd = tr.next('tr').find('> td').first();
        if (!childTd.length) return;

        childTd.html(
            '<div style="padding:20px;text-align:center;background:#f8fafc">' +
            '<i class="fa fa-spinner fa-spin text-info"></i>' +
            ' <span style="color:#64748b;font-size:12px">Memperbarui data...</span></div>'
        );

        $.ajax({
            url:      _pbrBaseUrl + '/get_detail',
            type:     'GET',
            dataType: 'json',
            data:     { kode_bagian: kodeBagian, flag: _pbrFlag, tgl_filter: $('#pbr-tgl-sampai').val() },
            success: function (res) {
                if (res.status === 200) {
                    childTd.html(
                        '<div style="padding:16px 24px;background:#f8fafc">' + res.html + '</div>'
                    );
                }
            }
        });
    });
}

// ── Helpers tanggal default ──
function pbrDefaultTglDari() {
    var d = new Date();
    return d.getFullYear() + '-'
        + String(d.getMonth() + 1).padStart(2, '0') + '-01';
}
function pbrDefaultTglSampai() {
    var d = new Date();
    return d.getFullYear() + '-'
        + String(d.getMonth() + 1).padStart(2, '0') + '-'
        + String(d.getDate()).padStart(2, '0');
}

// ── Buka laporan detail per unit (scope global agar bisa dipanggil dari onclick) ──
function show_detail(kodeBagian) {
    var tglDari   = $('#pbr-tgl-dari').val();
    var tglSampai = $('#pbr-tgl-sampai').val();
    var url = _pbrBaseUrl + '/laporan_detail'
            + '?kode_bagian=' + encodeURIComponent(kodeBagian)
            + '&flag='        + _pbrFlag;
    if (tglDari)   url += '&tgl_dari='   + encodeURIComponent(tglDari);
    if (tglSampai) url += '&tgl_sampai=' + encodeURIComponent(tglSampai);
    window.open(url, '_blank');
}

// ── Muat ringkasan summary ──
function pbrLoadSummary() {
    $('#pbr-total-unit').html('<i class="fa fa-spinner fa-spin text-info"></i>');
    $('#pbr-total-jenis').html('<i class="fa fa-spinner fa-spin text-success"></i>');
    $('#pbr-total-nilai').html('<i class="fa fa-spinner fa-spin" style="color:#bae6fd"></i>');

    var tgl = $('#pbr-tgl-sampai').val();
    var subLabel = tgl
        ? 'WA 3 PO terakhir s.d. ' + pbrFmtTgl(tgl)
        : 'berdasarkan WA harga modal 3 PO terakhir';
    $('#pbr-summary-sub').text(subLabel);

    $.get(_pbrBaseUrl + '/get_summary', {
        flag:       _pbrFlag,
        tgl_filter: tgl
    }, function (res) {
        if (res.status === 200) {
            $('#pbr-total-unit').text(pbrFmt(res.total_unit));
            $('#pbr-total-jenis').text(pbrFmt(res.total_jenis));
            $('#pbr-total-nilai').html('Rp&nbsp;' + pbrFmt(Math.round(res.total_nilai)));
        }
    }, 'json').fail(function () {
        $('#pbr-total-unit, #pbr-total-jenis, #pbr-total-nilai').text('-');
    });
}

function pbrFmtTgl(s) {
    if (!s) return '';
    var p = s.split('-');
    return p.length === 3 ? p[2] + '/' + p[1] + '/' + p[0] : s;
}

function pbrFmt(n) {
    return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
}

$(function () {

    // ── Set nilai default tanggal ──
    $('#pbr-tgl-dari').val(pbrDefaultTglDari());
    $('#pbr-tgl-sampai').val(pbrDefaultTglSampai());

    // ── Init DataTable ──
    oTablePbr = $('#tbl-pbrekap').DataTable({
        destroy:    true,
        processing: true,
        serverSide: true,
        ordering:   false,
        paging:     true,
        ajax: {
            url:  _pbrBaseUrl + '/get_data',
            type: 'POST',
            data: function (d) {
                d.flag       = _pbrFlag;
                d.tgl_filter = $('#pbr-tgl-sampai').val(); // untuk stock snapshot
                d.tgl_dari   = $('#pbr-tgl-dari').val();
                d.tgl_sampai = $('#pbr-tgl-sampai').val();
            }
        },
        columnDefs: [
            { targets: 0, data: 0, className: 'pbr-detail-ctrl center', orderable: false, width: '20px',  defaultContent: '' },
            { targets: 1, data: 1, className: 'center',                  width: '40px',   defaultContent: '' },
            { targets: 2, data: 2, visible: false,                                         defaultContent: '' },
            { targets: 3, data: 3,                                                          defaultContent: '' },
            { targets: 4, data: 4, className: 'center',                                    defaultContent: '' },
            { targets: 5, data: 5, className: 'text-right',                                defaultContent: '' },
            { targets: 6, data: 6, className: 'text-right',                                defaultContent: '' },
            { targets: 7, data: 7, className: 'text-right',                                defaultContent: '' },
            { targets: 8, data: 8, className: 'text-right',                                defaultContent: '' },
            { targets: 9, data: 9, className: 'text-right',                                defaultContent: '' },
        ],
        language: {
            processing:  '<i class="fa fa-spinner fa-spin"></i> Memuat data...',
            zeroRecords: 'Tidak ada data ditemukan',
            emptyTable:  'Tidak ada data tersedia',
            info:        'Menampilkan _START_ s/d _END_ dari _TOTAL_ data',
            infoEmpty:   'Menampilkan 0 data',
            search:      'Cari Unit:',
            lengthMenu:  'Tampilkan _MENU_ data',
            paginate:    { first: 'Pertama', last: 'Terakhir', next: '&raquo;', previous: '&laquo;' }
        },
        pageLength: 25,
    });

    // ── Tab Medis / Non Medis ──
    $('.pbr-tab-btn').on('click', function (e) {
        e.preventDefault();
        var flag = $(this).data('flag');
        if (flag === _pbrFlag) return;

        _pbrFlag = flag;
        $('.pbr-tab-btn').removeClass('active');
        $(this).addClass('active');
        $('#pbr-page-title').text(
            flag === 'non_medis'
                ? 'Rekap Persediaan Per Unit \u2013 Barang Non Medis'
                : 'Rekap Persediaan Per Unit \u2013 Barang Medis'
        );

        // Tutup semua child row
        $('#tbl-pbrekap tbody tr.shown').each(function () {
            oTablePbr.row($(this)).child.hide();
            $(this).removeClass('shown')
                   .find('.pbr-dc-icon')
                   .removeClass('fa-minus-circle').addClass('fa-plus-circle')
                   .css('color', '#0891b2');
        });

        pbrLoadSummary();
        oTablePbr.ajax.reload();
        setTimeout(pbrUpdateCetakUrl, 0);
    });

    // ── Filter & Reset ──
    $('#btn-filter-pbr').on('click', function () {
        pbrLoadSummary();
        oTablePbr.ajax.reload();
        pbrUpdateCetakUrl();
    });
    $('#btn-reset-pbr').on('click', function () {
        $('#pbr-tgl-dari').val(pbrDefaultTglDari());
        $('#pbr-tgl-sampai').val(pbrDefaultTglSampai());
        pbrLoadSummary();
        oTablePbr.ajax.reload();
        pbrUpdateCetakUrl();
    });

    // ── Row expand / collapse ──
    $('#tbl-pbrekap tbody').on('click', 'td.pbr-detail-ctrl', function () {
        var tr          = $(this).closest('tr');
        var row         = oTablePbr.row(tr);
        var icon        = tr.find('.pbr-dc-icon');
        var kodeBagian  = icon.data('kode-bagian');

        if (row.child.isShown()) {
            row.child.hide();
            tr.removeClass('shown');
            icon.removeClass('fa-minus-circle').addClass('fa-plus-circle').css('color', '#0891b2');
        } else {
            icon.removeClass('fa-plus-circle').addClass('fa-minus-circle').css('color', '#dc2626');
            row.child(
                '<td colspan="10" style="padding:20px;text-align:center;background:#f8fafc">' +
                '<i class="fa fa-spinner fa-spin text-info"></i>' +
                ' <span style="color:#64748b;font-size:12px">Memuat data...</span></td>'
            ).show();
            tr.addClass('shown');

            $.ajax({
                url:      _pbrBaseUrl + '/get_detail',
                type:     'GET',
                dataType: 'json',
                data:     { kode_bagian: kodeBagian, flag: _pbrFlag, tgl_filter: $('#pbr-tgl-sampai').val() },
                success: function (res) {
                    if (res.status === 200) {
                        row.child(
                            '<td colspan="10" style="padding:16px 24px;background:#f8fafc">' +
                            res.html + '</td>'
                        ).show();
                    } else {
                        row.child(
                            '<td colspan="10"><div class="alert alert-danger" style="margin:10px">' +
                            '<i class="fa fa-exclamation-triangle"></i> Gagal memuat data detail.</div></td>'
                        ).show();
                    }
                },
                error: function () {
                    row.child(
                        '<td colspan="10"><div class="alert alert-danger" style="margin:10px">' +
                        '<i class="fa fa-exclamation-triangle"></i> Koneksi gagal. Silahkan coba lagi.</div></td>'
                    ).show();
                }
            });
        }
    });

    // ── Update href tombol cetak ──
    function pbrUpdateCetakUrl() {
        var tglSampai = $('#pbr-tgl-sampai').val();
        var url = _pbrBaseUrl + '/laporan?flag=' + _pbrFlag;
        if (tglSampai) url += '&tgl_filter=' + encodeURIComponent(tglSampai);
        $('#btn-cetak-pbr').attr('href', url);
    }

    pbrUpdateCetakUrl();

    // ── Load summary awal ──
    pbrLoadSummary();

    // ── Delegasi klik tombol Kosongkan di child-row ──
    $(document).on('click', '.pbr-btn-kosongkan', function () {
        var $b = $(this);
        pbrOpenKosongkan(
            $b.data('kode-brg'),
            $b.data('nama-brg'),
            $b.data('kode-bagian'),
            $b.data('nama-bagian')
        );
    });

    // ── Klik tombol konfirmasi kosongkan ──
    $(document).on('click', '#pbrBtnKonfirmasiKosongkan', function () {
        var $btn = $(this);
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Memproses...');
        $('#pbrKosongkanError').hide();

        $.post(_pbrBaseUrl + '/kosongkan_stok', {
            kode_brg:    _curKodeBrg,
            kode_bagian: _curKodeBagian,
            flag:        _curFlag,
            keterangan:  $('#pbrKosongkanKet').val()
        }, function (res) {
            if (res && res.status === 200) {
                $('#pbrModalKosongkan').modal('hide');
                // Perbarui child-row unit terkait saja
                pbrReloadChildRow(_curKodeBagian);
                var toast = $('<div>')
                    .css({
                        position: 'fixed', bottom: '24px', right: '24px',
                        background: '#15803d', color: '#fff', padding: '10px 18px',
                        borderRadius: '6px', fontSize: '12px', zIndex: 99999,
                        boxShadow: '0 4px 12px rgba(0,0,0,.2)'
                    })
                    .html('<i class="fa fa-check-circle"></i>&nbsp; Stok <strong>'
                        + _curKodeBrg + '</strong> berhasil dikosongkan '
                        + '(stok sebelum: ' + pbrFmtNum(res.stok_sebelum) + ')')
                    .appendTo('body');
                setTimeout(function () { toast.fadeOut(400, function () { toast.remove(); }); }, 4000);
                $btn.prop('disabled', false).html('<i class="fa fa-check"></i> Ya, Kosongkan Stok');
                
            } else {
                var msg = (res && res.message) ? res.message : 'Terjadi kesalahan. Silahkan coba lagi.';
                $('#pbrKosongkanErrMsg').text(msg);
                $('#pbrKosongkanError').show();
                $btn.prop('disabled', false).html('<i class="fa fa-check"></i> Ya, Kosongkan Stok');
            }
        }, 'json').fail(function () {
            $('#pbrKosongkanErrMsg').text('Koneksi gagal. Silahkan coba lagi.');
            $('#pbrKosongkanError').show();
            $btn.prop('disabled', false).html('<i class="fa fa-check"></i> Ya, Kosongkan Stok');
        });
    });

});
</script>
