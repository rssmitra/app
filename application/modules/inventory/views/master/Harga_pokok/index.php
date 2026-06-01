<style>
.hp-card-harga {
    background: linear-gradient(135deg, #0891b2, #0e7490);
    color: #fff;
    border-radius: 6px;
    padding: 10px 16px;
    display: inline-block;
    font-size: 15px;
    font-weight: 700;
    letter-spacing: .3px;
}
.hp-flag-tab {
    display: inline-flex;
    gap: 6px;
    margin-bottom: 12px;
}
.hp-flag-tab a {
    padding: 6px 18px;
    border-radius: 20px;
    font-size: 12px;
    font-weight: 600;
    border: 2px solid #0891b2;
    color: #0891b2;
    text-decoration: none;
    transition: .2s;
}
.hp-flag-tab a.active, .hp-flag-tab a:hover {
    background: #0891b2;
    color: #fff;
}
td.details-control {
    cursor: pointer;
    text-align: center !important;
}
#tbl-harga-pokok tbody tr.shown {
    background: #f0f9ff !important;
}
#tbl-harga-pokok tbody tr.shown + tr > td {
    border-top: 2px solid #0891b2;
    border-bottom: 2px solid #0891b2;
}
</style>

<div class="row">
  <div class="col-xs-12">

    <div class="page-header">
      <h1>
        <span id="hp-page-title"><?php echo $title ?></span>
        <small>
          <i class="ace-icon fa fa-angle-double-right"></i>
          <?php echo isset($breadcrumbs) ? $breadcrumbs : '' ?>
        </small>
      </h1>
    </div>

    <div class="hp-flag-tab">
      <a href="#" data-flag="medis"
         class="hp-tab-btn <?php echo ($flag_string === 'medis') ? 'active' : '' ?>">
        <i class="fa fa-medkit"></i> Barang Medis
      </a>
      <a href="#" data-flag="non_medis"
         class="hp-tab-btn <?php echo ($flag_string === 'non_medis') ? 'active' : '' ?>">
        <i class="fa fa-archive"></i> Barang Non Medis
      </a>
    </div>

    <!-- Filter bar -->
    <div class="well well-sm" style="padding:10px;margin-bottom:10px">
      <form class="form-inline" id="form-filter-hp">
        <input type="hidden" id="hp_flag" value="<?php echo $flag_string ?>">
        <div class="form-group" style="margin-right:8px">
          <label style="font-size:12px">Golongan</label>
          <?php
            $table_gol = ($flag_string === 'medis') ? 'mt_golongan' : 'mt_golongan_nm';
            echo $this->master->custom_selection(
                array('table' => $table_gol, 'id' => 'kode_golongan', 'name' => 'nama_golongan', 'where' => array()),
                '', 'kode_golongan', 'kode_golongan', 'form-control input-sm', '', ''
            );
          ?>
        </div>
        <div class="form-group" style="margin-right:8px">
          <label style="font-size:12px">Sub Golongan</label>
          <?php
            $table_sub = ($flag_string === 'medis') ? 'mt_sub_golongan' : 'mt_sub_golongan_nm';
            echo $this->master->custom_selection(
                array('table' => $table_sub, 'id' => 'kode_sub_gol', 'name' => 'nama_sub_golongan', 'where' => array()),
                '', 'kode_sub_gol', 'kode_sub_gol', 'form-control input-sm', '', ''
            );
          ?>
        </div>
        <button type="button" class="btn btn-xs btn-primary" id="btn-filter-hp">
          <i class="fa fa-search"></i> Filter
        </button>
        <button type="button" class="btn btn-xs btn-default" id="btn-reset-hp">
          <i class="fa fa-refresh"></i> Reset
        </button>
      </form>
    </div>

    <!-- DataTable -->
    <table id="tbl-harga-pokok" class="table table-bordered table-hover table-condensed" style="font-size:12px">
      <thead>
        <tr style="background:#f1f5f9">
          <th class="center" rowspan="2" width="20"></th>
          <th class="center" rowspan="2" width="40">No</th>
          <th rowspan="2" width="110">Kode Barang</th>
          <th rowspan="2">Nama Barang / Pabrik</th>
          <th class="center" rowspan="2" width="100">Satuan</th>
          <th class="center" rowspan="2" width="130">HPP per Hari ini (Rp)</th>
          <th class="center" colspan="2" style="background:#e0f2fe">Harga Modal</th>
          <th class="center" colspan="2" style="background:#fef9c3">Harga Jual</th>
          <th class="center" rowspan="2" width="90">Tgl Update</th>
        </tr>
        <tr style="background:#f1f5f9">
          <th class="center" width="130" style="background:#e0f2fe">Sebelum Diskon</th>
          <th class="center" width="130" style="background:#e0f2fe">Setelah Diskon (WA)</th>
          <th class="center" width="145" style="background:#fef9c3">HPP</th>
          <th class="center" width="130" style="background:#fef9c3">Est. Harga Jual</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>

  </div>
</div>

<script type="text/javascript">
var _hpFlag    = '<?php echo $flag_string ?>';
var _hpBaseUrl = '<?php echo site_url('inventory/master/Harga_pokok') ?>';
var oTableHp;

// Polyfill: $.unique dihapus di jQuery 3.7+, dipakai DataTables secara internal
if (typeof $.unique === 'undefined' && typeof $.uniqueSort !== 'undefined') {
    $.unique = $.uniqueSort;
}

$(function () {

    // ── Init DataTable ──
    oTableHp = $('#tbl-harga-pokok').DataTable({
        destroy:    true,
        processing: true,
        serverSide: true,
        ordering:   false,
        ajax: {
            url:  _hpBaseUrl + '/get_data',
            type: 'POST',
            data: function (d) {
                d.flag          = _hpFlag;
                d.kode_golongan = $('select[name="kode_golongan"]').val();
                d.kode_sub_gol  = $('select[name="kode_sub_gol"]').val();
            }
        },
        columnDefs: [
            { targets: 0, data: 0, className: 'details-control center', orderable: false, width: '20px', defaultContent: '' },
            { targets: 1, data: 1, className: 'center', width: '40px',  defaultContent: '' },
            { targets: 2, data: 2, defaultContent: '' },
            { targets: 3, data: 3, defaultContent: '' },
            { targets: 4, data: 4, className: 'center',     defaultContent: '' },
            { targets: 5, data: 5, className: 'text-right', defaultContent: '' },
            { targets: 6, data: 6, className: 'text-right', defaultContent: '' },
            { targets: 7, data: 7, className: 'text-right', defaultContent: '' },
            { targets: 8, data: 8, className: 'text-right', defaultContent: '' },
            { targets: 9, data: 9, className: 'text-right', defaultContent: '' },
            { targets: 10, data: 10, className: 'center',   defaultContent: '' },
        ],
        language: {
            processing:  '<i class="fa fa-spinner fa-spin"></i> Memuat data...',
            zeroRecords: 'Tidak ada data ditemukan',
            emptyTable:  'Tidak ada data tersedia',
            info:        'Menampilkan _START_ s/d _END_ dari _TOTAL_ data',
            infoEmpty:   'Menampilkan 0 data',
            search:      'Cari:',
            lengthMenu:  'Tampilkan _MENU_ data',
            paginate: { first: 'Pertama', last: 'Terakhir', next: '&raquo;', previous: '&laquo;' }
        },
        pageLength: 25,
    });

    // ── Tab Medis / Non Medis ──
    $('.hp-tab-btn').on('click', function (e) {
        e.preventDefault();
        var flag = $(this).data('flag');
        if (flag === _hpFlag) return;

        _hpFlag = flag;

        // Update active tab visual
        $('.hp-tab-btn').removeClass('active');
        $(this).addClass('active');

        // Update judul halaman
        var title = (flag === 'non_medis')
            ? 'Harga Pokok Barang Non Medis'
            : 'Harga Pokok Barang Medis';
        $('#hp-page-title').text(title);

        // Reset filter golongan (berbeda tabel antar flag)
        $('select[name="kode_golongan"]').val('');
        $('select[name="kode_sub_gol"]').val('');

        // Tutup semua child row yang sedang terbuka
        $('#tbl-harga-pokok tbody tr.shown').each(function () {
            oTableHp.row($(this)).child.hide();
            $(this).removeClass('shown')
                   .find('.dc-icon')
                   .removeClass('fa-minus-circle').addClass('fa-plus-circle')
                   .css('color', '#0891b2');
        });

        // Reload DataTable — flag sudah di-update di _hpFlag, dikirim via data()
        oTableHp.ajax.reload();
    });

    // ── Filter ──
    $('#btn-filter-hp').on('click', function () { oTableHp.ajax.reload(); });
    $('#btn-reset-hp').on('click', function () {
        $('select[name="kode_golongan"]').val('');
        $('select[name="kode_sub_gol"]').val('');
        oTableHp.ajax.reload();
    });

    // ── Row expand / collapse (details-control) ──
    $('#tbl-harga-pokok tbody').on('click', 'td.details-control', function () {
        var tr      = $(this).closest('tr');
        var row     = oTableHp.row(tr);
        var rowData = row.data();

        // kode_brg ada di indeks 2, nama di indeks 3 (berisi HTML)
        var kode = rowData[2];
        var nama = $('<div>').html(rowData[3]).find('strong').first().text() || kode;
        var icon = tr.find('.dc-icon');

        if (row.child.isShown()) {
            // Tutup child row
            row.child.hide();
            tr.removeClass('shown');
            icon.removeClass('fa-minus-circle').addClass('fa-plus-circle').css('color', '#0891b2');
        } else {
            // Tampilkan loading dulu
            icon.removeClass('fa-plus-circle').addClass('fa-minus-circle').css('color', '#dc2626');
            row.child(
                '<td colspan="11" style="padding:20px;text-align:center;background:#f8fafc">' +
                '<i class="fa fa-spinner fa-spin text-info"></i>' +
                ' <span style="color:#64748b;font-size:12px">Memuat data...</span></td>'
            ).show();
            tr.addClass('shown');

            $.ajax({
                url:      _hpBaseUrl + '/get_detail',
                type:     'GET',
                dataType: 'json',
                data:     { kode_brg: kode, nama_brg: nama, flag: _hpFlag },
                success: function (res) {
                    if (res.status === 200) {
                        row.child(
                            '<td colspan="11" style="padding:16px 24px;background:#f8fafc">' +
                            res.html + '</td>'
                        ).show();
                    } else {
                        row.child(
                            '<td colspan="11"><div class="alert alert-danger" style="margin:10px">' +
                            '<i class="fa fa-exclamation-triangle"></i> Gagal memuat data detail.</div></td>'
                        ).show();
                    }
                },
                error: function () {
                    row.child(
                        '<td colspan="11"><div class="alert alert-danger" style="margin:10px">' +
                        '<i class="fa fa-exclamation-triangle"></i> Koneksi gagal. Silahkan coba lagi.</div></td>'
                    ).show();
                }
            });
        }
    });

});
</script>
