<style>
.lhs-unit-thead th {
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
        pageLength: 50,
        ajax: {
            url:  $('#dt-bag-so').attr('base-url'),
            type: 'POST'
        },
        columnDefs: [
            { targets: [-1], orderable: false },
            { targets: [5], visible: false },   // Barang Exp -3 Bln (disembunyikan)
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

    <table id="dt-bag-so"
           base-url="inventory/so/Lap_hasil_so/get_data_bag_so?agenda_so_id=<?php echo $agenda_so_id?>&flag=<?php echo $flag?>"
           class="table table-bordered table-hover table-condensed" style="font-size:12px">
      <thead>
        <tr class="lhs-unit-thead">
          <th width="30px"  class="center"></th>
          <th width="100px">Kode Bagian</th>
          <th>Nama Bagian</th>
          <th class="center" width="100px">Barang Aktif</th>
          <th class="center" width="110px">Barang Tidak Aktif</th>
          <th class="center" width="130px" style="display:none">Barang Exp (-3 Bln)</th>
          <th class="center" width="110px">Barang Expired</th>
          <th class="center" width="130px">Total Barang<br>(Aktif + Tidak Aktif)</th>
          <th class="center" width="130px">Total Persediaan Aktif</th>
        </tr>
      </thead>
      <tbody></tbody>
    </table>

  </div>
</div>

<script src="<?php echo base_url().'assets/js/custom/als_datatable.js'?>"></script>
