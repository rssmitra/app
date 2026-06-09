<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>

<style>
/* ── Filter card ── */
.eb-filter-card {
    background: #fff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 16px 20px 12px;
    margin-bottom: 16px;
    box-shadow: 0 1px 4px rgba(0,0,0,.06);
}
.eb-filter-title {
    font-size: 11px;
    font-weight: 700;
    color: #0891b2;
    text-transform: uppercase;
    letter-spacing: .6px;
    margin: 0 0 12px;
    padding-bottom: 8px;
    border-bottom: 2px solid #e0f2fe;
}
.eb-filter-row {
    display: flex;
    flex-wrap: wrap;
    gap: 10px 14px;
    margin-bottom: 10px;
    align-items: flex-end;
}
.eb-filter-group {
    display: flex;
    flex-direction: column;
    min-width: 140px;
}
.eb-filter-group.fg-sm  { min-width: 120px; max-width: 160px; }
.eb-filter-group.fg-md  { min-width: 180px; max-width: 260px; }
.eb-filter-group.fg-lg  { flex: 1; min-width: 200px; }
.eb-filter-group label {
    font-size: 11px;
    font-weight: 600;
    color: #475569;
    text-transform: uppercase;
    letter-spacing: .4px;
    margin-bottom: 4px;
    white-space: nowrap;
}
/* Direct-child selectors avoid conflicting with Bootstrap's input-group table-cell layout */
.eb-filter-group > select,
.eb-filter-group > input[type="text"] {
    height: 30px;
    font-size: 12px;
    border: 1px solid #cbd5e1;
    border-radius: 4px;
    padding: 0 8px;
    color: #1e293b;
    background: #fff;
    width: 100%;
    box-sizing: border-box;
}
/* Bootstrap 3 uses display:table-cell for input-group — do NOT override display.
   Only set height/font; Bootstrap handles border-radius, border joins, and width. */
.eb-filter-group .input-group > .form-control {
    height: 30px;
    font-size: 12px;
    border-color: #cbd5e1;
}
.eb-filter-group .input-group-addon {
    height: 30px;
    padding: 4px 9px;
    font-size: 12px;
    background-color: #f1f5f9;
    border-color: #cbd5e1;
    cursor: pointer;
}

/* Penjamin radios */
.eb-penjamin-wrap {
    display: flex;
    align-items: center;
    gap: 14px;
    flex-wrap: wrap;
    padding: 5px 0 2px;
}
.eb-penjamin-wrap .eb-radio-item {
    display: flex;
    align-items: center;
    gap: 5px;
    cursor: pointer;
    margin: 0;
    font-size: 12px;
    font-weight: 500;
    color: #334155;
    text-transform: none;
    letter-spacing: 0;
    white-space: nowrap;
}

/* Date range separator */
.eb-date-sep {
    font-size: 12px;
    font-weight: 600;
    color: #64748b;
    padding-bottom: 6px;
    align-self: flex-end;
}

/* Action buttons row */
.eb-btn-row {
    display: flex;
    align-items: center;
    gap: 6px;
    padding-bottom: 2px;
}

/* ── Table header ── */
#dynamic-table thead th {
    background: linear-gradient(135deg, #0369a1, #0891b2);
    color: #fff;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: .5px;
    text-transform: uppercase;
    border-color: #0284c7 !important;
    white-space: nowrap;
    vertical-align: middle !important;
}
#dynamic-table tbody tr:hover {
    background-color: #f0f9ff !important;
}
</style>

<script>

  jQuery(function($) {

    $('.date-picker').datepicker({
      autoclose: true,
      todayHighlight: true
    })
    .next().on(ace.click_event, function(){
      $(this).prev().focus();
    });
  });

  $(document).ready(function(){

    oTable = $('#dynamic-table').DataTable({

        "processing": true,
        "serverSide": true,
        "ordering": false,
        "searching": false,
        "bPaginate": true,
        "bInfo": true,
        "pageLength": 25,
        "ajax": {
            "url": "pelayanan/Pl_pelayanan/get_data_entry_billing?bag=0&form=billing_entry",
            "type": "POST"
        },
        "columnDefs": [
            {
                "targets": [ -1 ],
                "orderable": false,
            },
            {"aTargets" : [0], "mData" : 1, "sClass":  "details-control"},
            { "visible": false, "targets": [1,2,3] },
        ],
        "language": {
            "processing":  '<i class="fa fa-spinner fa-spin"></i> Memuat data...',
            "zeroRecords": 'Tidak ada data ditemukan',
            "emptyTable":  'Tidak ada data tersedia',
            "search":      'Cari:',
            "paginate": { "first": "Pertama", "last": "Terakhir", "next": "&raquo;", "previous": "&laquo;" }
        }

    });

    $('#dynamic-table tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = oTable.row( tr );
        var data = oTable.row( $(this).parents('tr') ).data();
        var no_kunjungan = data[ 2 ];
        var no_registrasi = data[ 3 ];

        if ( row.child.isShown() ) {
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            $.getJSON("pelayanan/Pl_pelayanan/view_detail_resume_medis/" + no_registrasi+"/"+no_kunjungan , '', function (data) {
                response_data = data;
                row.child( format( response_data ) ).show();
                tr.addClass('shown');
            });
        }
    });

    $('#btn_search_data').click(function (e) {
        e.preventDefault();
        $.ajax({
            url: 'tarif/Mst_tarif/find_data',
            type: "post",
            data: $('#form_search').serialize(),
            dataType: "json",
            beforeSend: function() {
                achtungShowLoader();
            },
            success: function(data) {
                achtungHideLoader();
                find_data_reload(data,'tarif/Mst_tarif');
            }
        });
    });

    $('#btn_reset_data').click(function (e) {
        e.preventDefault();
        find_data_reload();
    });

    $( ".form-control" )
      .keypress(function(event) {
        var keycode =(event.keyCode?event.keyCode:event.which);
        if(keycode ==13){
          event.preventDefault();
          $('#btn_search_data').click();
          return false;
        }
    });

    $('#btn_update_session_poli').click(function (e) {
        achtungShowLoader();
        $.ajax({
            url: "pelayanan/Pl_pelayanan/destroy_session_kode_bagian",
            data: { kode: $('#sess_kode_bagian').val()},
            dataType: "json",
            type: "POST",
            complete: function (xhr) {
                var data=xhr.responseText;
                var jsonResponse = JSON.parse(data);
                if(jsonResponse.status === 200){
                    $.achtung({message: jsonResponse.message, timeout:5});
                    getMenu('pelayanan/Pl_pelayanan');
                }else{
                    $.achtung({message: jsonResponse.message, timeout:5});
                }
                achtungHideLoader();
            }
        });
    });

    $('select[name="poliklinik"]').change(function () {
        $.getJSON("<?php echo site_url('Templates/References/getDokterBySpesialis') ?>/" + $(this).val(), '', function (data) {
            $('#select_dokter option').remove();
            $('<option value="">-Pilih Dokter-</option>').appendTo($('#select_dokter'));
            $.each(data, function (i, o) {
                $('<option value="' + o.kode_dokter + '">' + o.nama_pegawai + '</option>').appendTo($('#select_dokter'));
            });
        });
    });

  })

function format ( data ) {
  return data.html;
}

function find_data_reload(result=''){
    oTable.ajax.url('pelayanan/Pl_pelayanan/get_data_entry_billing?bag=0&form=billing_entry&'+result.data).load();
}

function reload_data(){
    oTable.ajax.url('pelayanan/Pl_pelayanan/get_data_entry_billing?bag=0&form=billing_entry').load();
}

function cancel_visit(no_registrasi, no_kunjungan){
    preventDefault();
    achtungShowLoader();
    $.ajax({
        url: "pelayanan/Pl_pelayanan/cancel_visit",
        data: { no_registrasi: no_registrasi, no_kunjungan: no_kunjungan, kode_bag: $('#sess_kode_bagian').val() },
        dataType: "json",
        type: "POST",
        complete: function (xhr) {
            var data=xhr.responseText;
            var jsonResponse = JSON.parse(data);
            if(jsonResponse.status === 200){
                $.achtung({message: jsonResponse.message, timeout:5});
                getMenu('pelayanan/Pl_pelayanan');
            }else{
                $.achtung({message: jsonResponse.message, timeout:5});
            }
            achtungHideLoader();
        }
    });
}

function rollback(no_registrasi, no_kunjungan, flag){
    preventDefault();
    achtungShowLoader();
    $.ajax({
        url: "pelayanan/Pl_pelayanan/rollback",
        data: { no_registrasi: no_registrasi, no_kunjungan: no_kunjungan, kode_bag: $('#kode_bagian_val').val(), flag: flag },
        dataType: "json",
        type: "POST",
        complete: function (xhr) {
            var data=xhr.responseText;
            var jsonResponse = JSON.parse(data);
            if(jsonResponse.status === 200){
                $.achtung({message: jsonResponse.message, timeout:5});
                reload_table();
            }else{
                $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
            }
            achtungHideLoader();
        }
    });
}

function selesaikanKunjungan(no_registrasi, no_kunjungan){
    preventDefault();
    achtungShowLoader();
    $.ajax({
        url: "pelayanan/Pl_pelayanan/processSelesaikanKunjungan",
        data: { no_registrasi: no_registrasi, no_kunjungan: no_kunjungan},
        dataType: "json",
        type: "POST",
        complete: function (xhr) {
            var data=xhr.responseText;
            var jsonResponse = JSON.parse(data);
            if(jsonResponse.status === 200){
                $.achtung({message: jsonResponse.message, timeout:5});
                reload_data();
            }else{
                $.achtung({message: jsonResponse.message, timeout:5});
            }
            achtungHideLoader();
        }
    });
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

    <form method="post" id="form_search" action="pelayanan/Pl_pelayanan/find_data">

      <!-- Filter Card -->
      <div class="eb-filter-card">
        <div class="eb-filter-title"><i class="fa fa-filter"></i> Filter Pencarian</div>

        <!-- Row 1: Pencarian + Poli + Dokter -->
        <div class="eb-filter-row">
          <div class="eb-filter-group fg-sm">
            <label>Cari Berdasarkan</label>
            <select name="search_by" class="form-control">
              <option value="">— Pilih —</option>
              <option value="tc_kunjungan.no_mr" selected>No MR</option>
              <option value="pl_tc_poli.nama_pasien">Nama Pasien</option>
            </select>
          </div>

          <div class="eb-filter-group fg-md">
            <label>Keyword</label>
            <input type="text" class="form-control" name="keyword" id="keyword_form" placeholder="Ketik kata kunci...">
          </div>

          <div class="eb-filter-group fg-lg">
            <label>Poli / Klinik</label>
            <?php echo $this->master->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array('validasi' => 100, 'status_aktif' => 1)), '' , 'poliklinik', 'poliklinik', 'form-control', '', '') ?>
          </div>

          <div class="eb-filter-group fg-lg">
            <label>Dokter</label>
            <?php echo $this->master->get_change($params = array('table' => 'mt_dokter', 'id' => 'kode_dokter', 'name' => 'nama_pegawai', 'where' => array()), '' , 'select_dokter', 'select_dokter', 'form-control', '', '') ?>
          </div>
        </div>

        <!-- Row 2: Penjamin + Tanggal + Buttons -->
        <div class="eb-filter-row">
          <div class="eb-filter-group" style="min-width:300px;">
            <label>Penjamin</label>
            <div class="eb-penjamin-wrap">
              <label class="eb-radio-item">
                <input name="penjamin" type="radio" class="ace" value="0">
                <span>Umum</span>
              </label>
              <label class="eb-radio-item">
                <input name="penjamin" type="radio" class="ace" value="1">
                <span>Asuransi</span>
              </label>
              <label class="eb-radio-item">
                <input name="penjamin" type="radio" class="ace" value="120">
                <span>BPJS Kesehatan</span>
              </label>
            </div>
          </div>

          <div class="eb-filter-group fg-sm">
            <label>Tanggal Dari</label>
            <div class="input-group">
              <input class="form-control date-picker" name="from_tgl" id="from_tgl" type="text" data-date-format="yyyy-mm-dd" value="" placeholder="yyyy-mm-dd">
              <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
            </div>
          </div>

          <div class="eb-date-sep">s/d</div>

          <div class="eb-filter-group fg-sm">
            <label>Tanggal Sampai</label>
            <div class="input-group">
              <input class="form-control date-picker" name="to_tgl" id="to_tgl" type="text" data-date-format="yyyy-mm-dd" value="" placeholder="yyyy-mm-dd">
              <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
            </div>
          </div>

          <div class="eb-filter-group" style="min-width:auto;">
            <label>&nbsp;</label>
            <div class="eb-btn-row">
              <a href="#" id="btn_search_data" class="btn btn-sm btn-primary" action="pelayanan/Pl_pelayanan/find_data">
                <i class="fa fa-search"></i> Cari
              </a>
              <a href="#" id="btn_reset_data" class="btn btn-sm btn-default">
                <i class="fa fa-refresh"></i> Reset
              </a>
            </div>
          </div>
        </div>
      </div>

      <!-- DataTable -->
      <table id="dynamic-table"
             base-url="pelayanan/Pl_pelayanan/get_data_entry_billing?bag=0&form=billing_entry"
             class="table table-bordered table-hover table-condensed"
             style="font-size:12px; width:100%">
        <thead>
          <tr>
            <th width="30px"  class="center"></th>
            <th></th>
            <th></th>
            <th></th>
            <th width="60px"  class="center">Aksi</th>
            <th width="100px" class="center">No MR</th>
            <th>Nama Pasien</th>
            <th width="140px">Penjamin</th>
            <th width="190px">Tanggal Kunjungan</th>
            <th width="160px">Dokter</th>
            <th width="140px">Petugas Input</th>
            <th width="120px" class="center">Status</th>
          </tr>
        </thead>
        <tbody></tbody>
      </table>

    </form>

  </div>
</div>
