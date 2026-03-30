<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />

<style>
  .page-header-idx { border-bottom: 3px solid #2c6fad; padding-bottom: 8px; margin-bottom: 14px; }
  .page-header-idx h1 { font-size: 20px; color: #1a4f8a; font-weight: 700; margin: 0; }
  .srch-card { border: 1px solid #d0dce8; border-radius: 5px; background: #fff; box-shadow: 0 1px 4px rgba(44,111,173,.07); margin-bottom: 14px; overflow: hidden; }
  .srch-card-hdr { background: #2c6fad; color: #fff; padding: 9px 16px; font-weight: 700; font-size: 13px; display: flex; align-items: center; gap: 8px; }
  .srch-card-body { padding: 14px 20px 8px; }
  .srch-actions { display: flex; gap: 6px; padding: 8px 20px; background: #f0f5fb; border-top: 1px solid #d8e6f3; flex-wrap: wrap; align-items: center; }
  .tbl-wrap { border: 1px solid #d0dce8; border-radius: 5px; overflow: hidden; margin-bottom: 14px; }
  .tbl-hdr { background: #1a4f8a; color: #fff; padding: 9px 14px; font-size: 13px; font-weight: 700; display: flex; align-items: center; gap: 8px; }
  #dynamic-table thead tr { background: #2c6fad !important; }
  #dynamic-table thead th { color: #fff !important; font-size: 12px; font-weight: 600; border-color: #1e5590 !important; }
  .total-box { background: #f0f5fb; border: 1px solid #d0dce8; border-radius: 4px; padding: 8px 16px; display: inline-block; }
  .total-box .label-txt { font-size: 11px; color: #666; }
  .total-box .amount { font-size: 18px; font-weight: 700; color: #1a4f8a; }
</style>

<script type="text/javascript">
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
      "bLengthChange": true,
      "pageLength": 25,
      "bInfo": false,
      "paging": false,
      "ajax": {
          "url": $('#dynamic-table').attr('base-url'),
          "type": "POST"
      },
      "drawCallback": function (settings) {
          var response = settings.json;
          console.log(response.total_billing);
          $('#txt_total_tagihan').text(formatMoney(response.total_billing));
      },
      "columnDefs": [
          { "targets": [ 0 ], "orderable": false },
          {"aTargets" : [0], "mData" : 0, "sClass": "details-control"},
          { "visible": false, "targets": [1] },
        ],
    });

  })

  $('#dynamic-table tbody').on('click', 'td.details-control', function () {
      var tr = $(this).closest('tr');
      var row = oTable.row( tr );
      var data = oTable.row( $(this).parents('tr') ).data();
      var kode_perusahaan = data[ 1 ];

      if ( row.child.isShown() ) {
          row.child.hide();
          tr.removeClass('shown');
      }
      else {
          $.getJSON($('#dynamic-table').attr('url-detail')+ "/" + kode_perusahaan, '', function (data) {
              response_data = data;
              row.child( response_data.html ).show();
              tr.addClass('shown');
          });
      }
  });

  $('#dynamic-table tbody').on( 'click', 'tr', function () {
      if ( $(this).hasClass('selected') ) {
          $(this).removeClass('selected');
      }
      else {
          oTable.$('tr.selected').removeClass('selected');
          $(this).addClass('selected');
      }
  });

  $( ".form-control" ).keypress(function(event) {
      var keycode =(event.keyCode?event.keyCode:event.which);
      if(keycode ==13){
        event.preventDefault();
        if($(this).valid()){
          $('#btn_search_data').click();
        }
        return false;
      }
  });

  $('#btn_search_data').click(function (e) {
      var url_search = $('#form_search').attr('action');
      e.preventDefault();
      $.ajax({
        url: url_search,
        type: "post",
        data: $('#form_search').serialize(),
        dataType: "json",
        success: function(data) {
          console.log(data.data);
          find_data_reload(data);
        }
      });
  });

  $( "#nama_perusahaan" ).keypress(function(event) {
      var keycode =(event.keyCode?event.keyCode:event.which);
      if(keycode ==13){
          event.preventDefault();
          if($(this).valid()){
          $('#btn_search_data').click();
          }
          return false;
      }
  });

  $('#nama_perusahaan').typeahead({
      source: function (query, result) {
          $.ajax({
              url: "templates/references/getSupplier",
              data: 'keyword=' + query,
              dataType: "json",
              type: "POST",
              success: function (response) {
              result($.map(response, function (item) {
                  return item;
              }));
              }
          });
      },
      afterSelect: function (item) {
          var val_item=item.split(':')[0];
          var label_item=item.split(':')[1];
          console.log(label_item);
          $('#nama_perusahaan').val(label_item);
      }
  });

  function find_data_reload(result){
      oTable.ajax.url($('#dynamic-table').attr('base-url')+''+result.data).load();
  }

  function reload_table(){
    oTable.ajax.reload();
  }

  $('#btn_reset_data').click(function (e) {
      e.preventDefault();
      oTable.ajax.url($('#dynamic-table').attr('base-url')+'?keyword='+$('#keyword').val()+'&from_tgl='+$('#from_tgl').val()+'&to_tgl='+$('#to_tgl').val()+'').load();
      $('#form_search')[0].reset();
  });

  function show_detail_penerimaan(id_tc_hutang_supplier_inv, id_penerimaan, flag){
    preventDefault();
    $.getJSON("purchasing/tukar_faktur/Tf_riwayat_tukar_faktur/get_penerimaan_detail/" + id_penerimaan+'?flag='+flag+'', '', function (response) {
      $('#dt_detail_penerimaan_'+id_tc_hutang_supplier_inv+' tbody').remove();
      $('#txt_no_penerimaan_'+id_tc_hutang_supplier_inv+'').text(response.kode_penerimaan);
      $('#txt_tgl_penerimaan_'+id_tc_hutang_supplier_inv+'').text(response.tgl_penerimaan);
      $.each(response.data, function (i, o) {
          if(o.subtotal > 0){
            $('<tr><td align="center">'+o.count_num+'</td><td>'+o.nama_brg+'</td><td>'+o.jml_kirim+' '+o.satuan+'</td><td>'+o.discount+'</td><td align="right">'+formatMoney(o.harga_satuan)+'</td><td align="right">'+formatMoney(o.subtotal)+'</td></tr>').appendTo($('#dt_detail_penerimaan_'+id_tc_hutang_supplier_inv+''));
          }
      });
      $('<tr><td align="right" colspan="5">Subtotal</td><td align="right">'+formatMoney(response.total)+'</td></tr>').appendTo($('#dt_detail_penerimaan_'+id_tc_hutang_supplier_inv+''));
    });
  }
</script>

<div class="page-header-idx">
  <h1>
    <?php echo $title?>
    <small style="font-size:13px;color:#888;font-weight:400">
      <i class="ace-icon fa fa-angle-double-right"></i>
      <?php echo $breadcrumbs?>
    </small>
  </h1>
</div>

<div class="row">
  <div class="col-xs-12">

    <form class="form-horizontal" method="post" id="form_search" action="purchasing/tukar_faktur/Tf_riwayat_tukar_faktur/find_data" autocomplete="off">

      <div class="srch-card">
        <div class="srch-card-hdr"><i class="fa fa-search"></i> Filter &amp; Pencarian</div>
        <div class="srch-card-body">

          <div class="form-group" style="margin-bottom:8px">
            <div class="control-label col-md-2">
              <div class="checkbox" style="margin-top:-5px">
                <label>
                  <input name="checked_nama_perusahaan" id="checked_nama_perusahaan" type="checkbox" class="ace" value="1" <?php echo isset($_GET['checked_nama_perusahaan']) ? ($_GET['checked_nama_perusahaan'] == 1) ? 'checked' : '' : '' ?>>
                  <span class="lbl" style="font-size:12px"> Nama Perusahaan</span>
                </label>
              </div>
            </div>
            <div class="col-md-2">
              <input type="text" value="<?php echo isset($_GET['nama_perusahaan']) ? $_GET['nama_perusahaan'] : '' ?>" name="nama_perusahaan" id="nama_perusahaan" class="form-control input-sm">
            </div>

            <div class="control-label col-md-2">
              <div class="checkbox" style="margin-top:-5px">
                <label>
                  <input name="checked_no_ttf" id="checked_no_ttf" value="1" type="checkbox" class="ace" <?php echo isset($_GET['checked_no_ttf']) ? ($_GET['checked_no_ttf'] == 1) ? 'checked' : '' : '' ?>>
                  <span class="lbl" style="font-size:12px"> No Terima Faktur</span>
                </label>
              </div>
            </div>
            <div class="col-md-2">
              <input type="text" value="<?php echo isset($_GET['no_ttf']) ? $_GET['no_ttf'] : '' ?>" name="no_ttf" id="no_ttf" class="form-control input-sm">
            </div>
          </div>

          <div class="form-group" style="margin-bottom:8px">
            <div class="control-label col-md-2">
              <div class="checkbox" style="margin-top:-5px">
                <label>
                  <input name="checked_from_tgl" id="checked_from_tgl" type="checkbox" class="ace" value="1" <?php echo isset($_GET['checked_from_tgl']) ? ($_GET['checked_from_tgl'] == 1) ? 'checked' : '' : '' ?>>
                  <span class="lbl" style="font-size:12px"> Tgl Tukar Faktur</span>
                </label>
              </div>
            </div>
            <div class="col-md-2">
              <div class="input-group">
                <input class="form-control input-sm date-picker" name="from_tgl" id="from_tgl" type="text" data-date-format="yyyy-mm-dd" value="<?php echo isset($_GET['from_tgl'])?$_GET['from_tgl']:''; ?>" placeholder="Dari..."/>
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
              </div>
            </div>
            <label class="control-label col-md-1" style="font-size:12px">s/d</label>
            <div class="col-md-2">
              <div class="input-group">
                <input class="form-control input-sm date-picker" name="to_tgl" id="to_tgl" type="text" data-date-format="yyyy-mm-dd" value="<?php echo isset($_GET['to_tgl'])?$_GET['to_tgl']:''; ?>" placeholder="Sampai..."/>
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
              </div>
            </div>
          </div>

        </div>
        <div class="srch-actions">
          <a href="#" id="btn_search_data" class="btn btn-sm btn-primary"><i class="fa fa-search"></i> Tampilkan</a>
          <a href="#" id="btn_reset_data" class="btn btn-sm btn-warning"><i class="fa fa-refresh"></i> Reload</a>
          <span style="margin-left:auto">
            <div class="total-box">
              <div class="label-txt">Total Tagihan</div>
              <div class="amount"><span id="txt_total_tagihan">0</span></div>
            </div>
          </span>
        </div>
      </div>

      <div class="tbl-wrap">
        <div class="tbl-hdr">
          <i class="fa fa-list"></i> Data Pengajuan Pembayaran Faktur
          <span style="margin-left:auto;font-weight:400;font-size:11px;opacity:.85">
            Bulan <b><?php echo $this->tanggal->getBulan(date('m'))?></b> Tahun <b><?php echo date('Y')?></b> (Default)
          </span>
        </div>
        <table id="dynamic-table" base-url="purchasing/tukar_faktur/Tf_riwayat_tukar_faktur/get_data?<?php $qry_url = isset($_GET) ? http_build_query($_GET) . "\n" : ''; echo $qry_url?>" url-detail="purchasing/tukar_faktur/Tf_riwayat_tukar_faktur/get_log_data" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th width="50px"></th>
              <th class="center"></th>
              <th width="50px" class="center">No</th>
              <th>No Tanda Terima Faktur</th>
              <th>Tanggal</th>
              <th>Jatuh Tempo</th>
              <th>Nama Supplier</th>
              <th width="150px">Jumlah</th>
              <th>Status</th>
              <th>Petugas</th>
              <th class="center">TTF</th>
            </tr>
          </thead>
        </table>
      </div>

    </form>
  </div>
</div>
