<style>
/* ── Summary cards ── */
.lhs-summary-row {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-bottom: 16px;
}
.lhs-summary-card {
    flex: 1;
    min-width: 160px;
    border-radius: 6px;
    padding: 12px 16px;
    color: #fff;
    position: relative;
    overflow: hidden;
}
.lhs-summary-card .lhs-sc-label {
    font-size: 11px;
    font-weight: 600;
    letter-spacing: .4px;
    text-transform: uppercase;
    opacity: .85;
    margin-bottom: 4px;
}
.lhs-summary-card .lhs-sc-value {
    font-size: 17px;
    font-weight: 700;
    line-height: 1.2;
}
.lhs-summary-card .lhs-sc-sub {
    font-size: 10px;
    opacity: .7;
    margin-top: 2px;
}
.lhs-sc-aktif    { background: linear-gradient(135deg, #0891b2, #0e7490); }
.lhs-sc-nonaktif { background: linear-gradient(135deg, #64748b, #475569); }
.lhs-sc-exp      { background: linear-gradient(135deg, #dc2626, #b91c1c); }
.lhs-sc-will_exp { background: linear-gradient(135deg, #d97706, #b45309); }

/* ── Table header ── */
.lhs-rs-thead th {
    background: linear-gradient(135deg, #0369a1, #0891b2);
    color: #fff;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: .5px;
    text-transform: uppercase;
    border-color: #0284c7 !important;
    white-space: nowrap;
}
</style>

<script>
$(document).ready(function() {

    oTable = $('#dt-bag-so').DataTable({
        processing: true,
        serverSide: true,
        ordering:   false,
        // pageLength: 50,
        paginate: false,
        ajax: {
            url:  $('#dt-bag-so').attr('base-url'),
            type: 'POST'
        },
        drawCallback: function(response) {
            var d = response.json;
            $('#total_hasil_so_aktif').text(formatMoney(d.total_rp_aktif));
            $('#total_hasil_so_not_aktif').text(formatMoney(d.total_rp_not_aktif));
            $('#total_hasil_so_exp').text(formatMoney(d.total_rp_exp));
            $('#total_hasil_so_will_exp').text(formatMoney(d.total_rp_will_exp));
        },
        columnDefs: [
            { targets: [-1], orderable: false },
            { targets: [7], visible: false },   // Expired -3 Bln (disembunyikan)
            { aTargets: [1], sClass: 'hidden-480' },
            { aTargets: [3], sClass: 'hidden-480' }
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
        }
    });

});

function get_rincian_log(kode_brg) {
    show_modal('inventory/so/Lap_hasil_so/log_barang?agenda_so_id=<?php echo $agenda_so_id?>&kode_brg=' + kode_brg + '&flag=<?php echo $flag?>');
}
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

    <!-- Summary Cards -->
    <div class="lhs-summary-row">
      <div class="lhs-summary-card lhs-sc-aktif">
        <div class="lhs-sc-label"><i class="fa fa-check-circle"></i> Barang Aktif</div>
        <div class="lhs-sc-value">Rp. <span id="total_hasil_so_aktif">—</span></div>
        <div class="lhs-sc-sub">Total nilai barang aktif</div>
      </div>
      <div class="lhs-summary-card lhs-sc-nonaktif">
        <div class="lhs-sc-label"><i class="fa fa-times-circle"></i> Barang Tidak Aktif</div>
        <div class="lhs-sc-value">Rp. <span id="total_hasil_so_not_aktif">—</span></div>
        <div class="lhs-sc-sub">Total nilai barang tidak aktif</div>
      </div>
      <div class="lhs-summary-card lhs-sc-exp">
        <div class="lhs-sc-label"><i class="fa fa-exclamation-circle"></i> Barang Expired</div>
        <div class="lhs-sc-value">Rp. <span id="total_hasil_so_exp">—</span></div>
        <div class="lhs-sc-sub">Total nilai barang kadaluarsa</div>
      </div>
      <!-- <div class="lhs-summary-card lhs-sc-will_exp">
        <div class="lhs-sc-label"><i class="fa fa-clock-o"></i> Mendekati Expired (-3 Bln)</div>
        <div class="lhs-sc-value">Rp. <span id="total_hasil_so_will_exp">—</span></div>
        <div class="lhs-sc-sub">Total nilai barang hampir kadaluarsa</div>
      </div> -->
    </div>

    <!-- DataTable -->
    <table id="dt-bag-so"
           base-url="inventory/so/Lap_hasil_so/get_data_bag_so_rs?agenda_so_id=<?php echo $agenda_so_id?>&flag=<?php echo $flag?>"
           class="table table-bordered table-hover table-condensed" style="font-size:12px">
      <thead>
        <tr class="lhs-rs-thead">
          <th width="30px"  class="center"></th>
          <th width="100px">Kode Barang</th>
          <th>Nama Barang</th>
          <th width="90px" class="center">Satuan Kecil</th>
          <th width="100px" class="text-right">Stok Sebelum</th>
          <th width="90px"  class="text-right">Hasil SO</th>
          <th width="90px"  class="text-right">Expired</th>
          <th width="110px" class="text-right" style="display:none">Expired (-3 Bln)</th>
          <th width="130px" class="text-right">Harga Satuan Pembelian</th>
          <th width="110px" class="text-right">Total (Rp)</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>

  </div>
</div>

<script src="<?php echo base_url().'assets/js/custom/als_datatable.js'?>"></script>
