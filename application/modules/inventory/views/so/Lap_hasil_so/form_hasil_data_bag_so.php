<style>
/* ── Summary cards (reuse lhs-summary pattern) ── */
.lhsb-summary-row {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 16px;
}
.lhsb-summary-card {
    flex: 1;
    min-width: 155px;
    border-radius: 6px;
    padding: 12px 16px;
    color: #fff;
}
.lhsb-summary-card .lhsb-sc-label {
    font-size: 11px;
    font-weight: 600;
    letter-spacing: .4px;
    text-transform: uppercase;
    opacity: .85;
    margin-bottom: 4px;
}
.lhsb-summary-card .lhsb-sc-value {
    font-size: 17px;
    font-weight: 700;
    line-height: 1.2;
}
.lhsb-summary-card .lhsb-sc-sub {
    font-size: 10px;
    opacity: .7;
    margin-top: 2px;
}
.lhsb-sc-aktif    { background: linear-gradient(135deg, #0891b2, #0e7490); }
.lhsb-sc-nonaktif { background: linear-gradient(135deg, #64748b, #475569); }
.lhsb-sc-exp      { background: linear-gradient(135deg, #dc2626, #b91c1c); }
.lhsb-sc-will_exp { background: linear-gradient(135deg, #d97706, #b45309); }
.lhsb-sc-selisih  { background: linear-gradient(135deg, #e65100, #bf360c); }
.lhsb-sc-lebih    { background: linear-gradient(135deg, #1565c0, #0d47a1); }

/* ── Filter bar ── */
.lhsb-filter-bar {
    display: flex;
    align-items: center;
    flex-wrap: wrap;
    gap: 8px;
    margin-bottom: 12px;
    padding: 8px 12px;
    background: #f8fafc;
    border: 1px solid #e2e8f0;
    border-radius: 6px;
}
.lhsb-filter-bar label {
    font-size: 11px;
    font-weight: 600;
    color: #475569;
    text-transform: uppercase;
    letter-spacing: .4px;
    margin: 0;
    white-space: nowrap;
}
.lhsb-filter-bar select {
    height: 28px;
    font-size: 12px;
    border: 1px solid #cbd5e1;
    border-radius: 4px;
    padding: 0 6px;
    background: #fff;
    color: #1e293b;
    min-width: 130px;
}

/* ── Table header ── */
.lhsb-thead th {
    background: linear-gradient(135deg, #0369a1, #0891b2);
    color: #fff;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: .5px;
    text-transform: uppercase;
    border-color: #0284c7 !important;
    white-space: nowrap;
}

/* ── Export button area ── */
.lhsb-toolbar {
    display: flex;
    align-items: center;
    justify-content: space-between;
    margin-bottom: 12px;
    flex-wrap: wrap;
    gap: 6px;
}
</style>

<script>
$(document).ready(function() {

    oTable = $('#dt-bag-so').DataTable({
        processing: true,
        serverSide: true,
        ordering:   false,
        paging:     false,
        bInfo:      false,
        ajax: {
            url:  $('#dt-bag-so').attr('base-url'),
            type: 'POST',
            data: function(d) {
                d.filter_status_so  = $('#filter_status_so').val()  || '';
                d.filter_status_brg = $('#filter_status_brg').val() || '';
                return d;
            }
        },
        drawCallback: function(response) {
            var d = response.json;
            $('#total_hasil_so_aktif').text(formatMoney(d.total_rp_aktif));
            $('#total_hasil_so_not_aktif').text(formatMoney(d.total_rp_not_aktif));
            $('#total_hasil_so_exp').text(formatMoney(d.total_rp_exp));
            $('#total_hasil_so_will_exp').text(formatMoney(d.total_rp_will_exp));
            $('#total_rp_selisih').text(formatMoney(d.total_rp_selisih));
            $('#total_rp_lebih').text(formatMoney(d.total_rp_lebih));
        },
        columnDefs: [
            { targets: [-1], orderable: false },
            { targets: [9], visible: false },   // Expired -3 Bln (disembunyikan)
            { aTargets: [1], sClass: 'hidden-480' },
            { aTargets: [3], sClass: 'hidden-480' }
        ],
        language: {
            processing:  '<i class="fa fa-spinner fa-spin"></i> Memuat data...',
            zeroRecords: 'Tidak ada data ditemukan',
            emptyTable:  'Tidak ada data tersedia',
            search:      'Cari:',
            paginate:    { first: 'Pertama', last: 'Terakhir', next: '&raquo;', previous: '&laquo;' }
        }
    });

    // Filter change → reload table (use element selector, NOT global oTable —
    // als_datatable.js overwrites oTable after this ready callback)
    $('#filter_status_so, #filter_status_brg').on('change', function() {
        $('#dt-bag-so').DataTable().ajax.reload();
    });

    $('#btn_reset_filter').on('click', function() {
        $('#filter_status_so').val('');
        $('#filter_status_brg').val('');
        $('#dt-bag-so').DataTable().ajax.reload();
    });

});
</script>

<div class="row">
  <div class="col-xs-12">

    <div class="page-header">
      <h1>
        <?php echo $title?>
        <small>
          <i class="ace-icon fa fa-angle-double-right"></i>
          <?php echo isset($breadcrumbs) ? $breadcrumbs : ''?>
        </small>
      </h1>
    </div>

    <!-- Toolbar -->
    <div class="lhsb-toolbar">
      <a href="<?php echo base_url().'inventory/so/Lap_hasil_so/excel?agenda_so_id='.$agenda_so_id.'&kode_bagian='.$kode_bagian.'&flag='.$flag?>"
         class="btn btn-sm btn-success" target="_blank">
        <i class="fa fa-file-excel-o"></i> Export Excel
      </a>
    </div>

    <!-- Summary Cards -->
    <div class="lhsb-summary-row">
      <div class="lhsb-summary-card lhsb-sc-aktif">
        <div class="lhsb-sc-label"><i class="fa fa-check-circle"></i> Barang Aktif</div>
        <div class="lhsb-sc-value">Rp. <span id="total_hasil_so_aktif">—</span></div>
        <div class="lhsb-sc-sub">Total nilai barang aktif</div>
      </div>
      <div class="lhsb-summary-card lhsb-sc-nonaktif">
        <div class="lhsb-sc-label"><i class="fa fa-times-circle"></i> Barang Tidak Aktif</div>
        <div class="lhsb-sc-value">Rp. <span id="total_hasil_so_not_aktif">—</span></div>
        <div class="lhsb-sc-sub">Total nilai barang tidak aktif</div>
      </div>
      <div class="lhsb-summary-card lhsb-sc-exp">
        <div class="lhsb-sc-label"><i class="fa fa-exclamation-circle"></i> Barang Expired</div>
        <div class="lhsb-sc-value">Rp. <span id="total_hasil_so_exp">—</span></div>
        <div class="lhsb-sc-sub">Total nilai barang kadaluarsa</div>
      </div>
      <div class="lhsb-summary-card lhsb-sc-selisih">
        <div class="lhsb-sc-label"><i class="fa fa-arrow-down"></i> Selisih (Kurang)</div>
        <div class="lhsb-sc-value">Rp. <span id="total_rp_selisih">—</span></div>
        <div class="lhsb-sc-sub">Nilai Rp stok kurang dari sistem</div>
      </div>
      <div class="lhsb-summary-card lhsb-sc-lebih">
        <div class="lhsb-sc-label"><i class="fa fa-arrow-up"></i> Selisih (Lebih)</div>
        <div class="lhsb-sc-value">Rp. <span id="total_rp_lebih">—</span></div>
        <div class="lhsb-sc-sub">Nilai Rp stok lebih dari sistem</div>
      </div>
    </div>

    <!-- Filter Bar -->
    <div class="lhsb-filter-bar">
      <label><i class="fa fa-filter"></i> Filter:</label>
      <label>Status SO</label>
      <select id="filter_status_so">
        <option value="">— Semua —</option>
        <option value="sesuai">Sesuai</option>
        <option value="kurang">Kurang</option>
        <option value="lebih">Lebih</option>
      </select>
      <label>Status Barang</label>
      <select id="filter_status_brg">
        <option value="">— Semua —</option>
        <option value="1">Aktif</option>
        <option value="0">Tidak Aktif</option>
      </select>
      <button id="btn_reset_filter" class="btn btn-xs btn-default" type="button">
        <i class="fa fa-times"></i> Reset
      </button>
    </div>

    <!-- DataTable -->
    <table id="dt-bag-so"
           base-url="inventory/so/Lap_hasil_so/get_data_hasil_bag_so?agenda_so_id=<?php echo $agenda_so_id?>&kode_bagian=<?php echo $kode_bagian?>&flag=<?php echo $flag?>"
           class="table table-bordered table-hover table-condensed" style="font-size:12px">
      <thead>
        <tr class="lhsb-thead">
          <th width="30px"  class="center"></th>
          <th width="100px">Kode</th>
          <th>Nama Barang</th>
          <th width="110px" class="text-right">Harga Satuan</th>
          <th width="90px"  class="center">Satuan Kecil</th>
          <th width="100px" class="text-right">Stok Sebelum</th>
          <th width="90px"  class="text-right">Hasil SO</th>
          <th width="80px"  class="text-right">Selisih</th>
          <th width="85px"  class="center">Status SO</th>
          <th width="110px" class="text-right" style="display:none">Expired (-3 Bln)</th>
          <th width="90px"  class="text-right">Expired</th>
          <th width="100px" class="text-right">Total Hasil</th>
          <th width="100px" class="center">Status Barang</th>
          <th width="220px">Klarifikasi SO</th>
          <th width="">Petugas</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>

  </div>
</div>

<script src="<?php echo base_url().'assets/js/custom/als_datatable.js'?>"></script>
