<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>

<style>
  .page-header-idx { border-bottom: 3px solid #2c6fad; padding-bottom: 8px; margin-bottom: 14px; }
  .page-header-idx h1 { font-size: 20px; color: #1a4f8a; font-weight: 700; margin: 0; }
  .srch-card { border: 1px solid #d0dce8; border-radius: 5px; background: #fff; box-shadow: 0 1px 4px rgba(44,111,173,.07); margin-bottom: 14px; overflow: hidden; }
  .srch-card-hdr { background: #2c6fad; color: #fff; padding: 9px 16px; font-weight: 700; font-size: 13px; display: flex; align-items: center; gap: 8px; }
  .srch-card-body { padding: 14px 20px 8px; }
  .srch-actions { display: flex; gap: 6px; padding: 8px 20px; background: #f0f5fb; border-top: 1px solid #d8e6f3; flex-wrap: wrap; align-items: center; }
  .tbl-wrap { border: 1px solid #d0dce8; border-radius: 5px; overflow: hidden; margin-bottom: 14px; }
  .tbl-hdr { background: #1a4f8a; color: #fff; padding: 9px 14px; font-size: 13px; font-weight: 700; display: flex; align-items: center; gap: 8px; }
  #table-monitoring-po thead tr { background: #2c6fad !important; }
  #table-monitoring-po thead th { color: #fff !important; font-size: 12px; font-weight: 600; border-color: #1e5590 !important; }
  .stat-row { display: flex; gap: 14px; padding: 10px 0 4px; flex-wrap: wrap; }
  .stat-box { background: #f0f5fb; border: 1px solid #d0dce8; border-radius: 4px; padding: 8px 18px; min-width: 200px; }
  .stat-box .stat-label { font-size: 11px; color: #666; }
  .stat-box .stat-value { font-size: 20px; font-weight: 700; color: #1a4f8a; }
  .stat-box .stat-sub { font-size: 13px; font-weight: 600; color: #333; }
  .stat-note { font-size: 11px; color: #888; font-style: italic; padding: 4px 0 8px; }
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

    var base_url = $('#table-monitoring-po').attr('base-url');

    oTable = $('#table-monitoring-po').DataTable({
      "processing": true,
      "serverSide": true,
      "ordering": false,
      "searching": false,
      "bPaginate": false,
      "bInfo": false,
      "ajax": {
          "url": base_url,
          "type": "POST"
      },
      "drawCallback": function (response) {
        var objData = response.json;
          $('#total_po').text('Rp.'+formatMoney(objData.total_po)+',-');
          $('#nm_brg_max').html(objData.nm_brg_max+"<br>"+objData.ttl_brg_max);
      },
    });

    $('#table-monitoring-po tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            oTable.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    });

    $('#btn_search_data').click(function (e) {
        e.preventDefault();
        $.ajax({
          url: $('#form_search').attr('action'),
          type: "post",
          data: $('#form_search').serialize(),
          dataType: "json",
          beforeSend: function() { achtungShowLoader(); },
          success: function(data) {
            achtungHideLoader();
            find_data_reload(data, base_url);
          }
        });
    });

    $('#btn_reset_data').click(function (e) {
            e.preventDefault();
            oTable.ajax.url(base_url).load();
    });

    $('#btn_export_excel').click(function (e) {
      var url_search = $('#form_search').attr('action');
      e.preventDefault();
      $.ajax({
        url: url_search,
        type: "post",
        data: $('#form_search').serialize(),
        dataType: "json",
        success: function(data) {
          console.log(data.data);
          export_excel(data);
        }
      });
    });

    function export_excel(result){
      window.open('purchasing/Po/Po_monitoring/export_excel?'+result.data+'','_blank');
    }

    function find_data_reload(result, base_url){
      var data = result.data;
      oTable.ajax.url(base_url+'&'+data).load();
    }

  });

  $( "#keyword_form" ).keypress(function(event) {
      var keycode =(event.keyCode?event.keyCode:event.which);
      if(keycode ==13){
        event.preventDefault();
        if($(this).valid()){
          $('#btn_search_data').click();
        }
        return false;
      }
  });

  $('select[name="search_by"]').change(function () {
      if( $(this).val() == 'month'){
        $('#div_month').show();
        $('#div_keyword').hide();
        $('#div_supplier').hide();
        $('#div_tgl_po').hide();
      }
      if( $(this).val() == 'supplier'){
        $('#div_supplier').show();
        $('#div_keyword').hide();
        $('#div_month').hide();
        $('#div_tgl_po').hide();
      }
      if( $(this).val() == 'no_po'){
        $('#div_month').hide();
        $('#div_keyword').show();
        $('#div_supplier').hide();
        $('#div_tgl_po').hide();
      }
      if( $(this).val() == 'tgl_po'){
        $('#div_month').hide();
        $('#div_keyword').hide();
        $('#div_supplier').hide();
        $('#div_tgl_po').show();
      }
  });

  $('#inputSupplier').typeahead({
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
        preventDefault();
        var val_item=item.split(':')[0];
        var label_item=item.split(':')[1];
        console.log(val_item);
        $('#inputSupplier').val(label_item);
        $('#kodesupplier').val(val_item);
    }
  });
</script>

<div class="page-header-idx">
  <h1>
    <?php echo $title?>
    <small style="font-size:13px;color:#888;font-weight:400">
      <i class="ace-icon fa fa-angle-double-right"></i>
      <?php echo isset($breadcrumbs)?$breadcrumbs:''?>
    </small>
  </h1>
</div>

<div class="row">
  <div class="col-xs-12">

    <form class="form-horizontal" method="post" id="form_search" action="purchasing/po/Po_monitoring/find_data">

      <div class="srch-card">
        <div class="srch-card-hdr"><i class="fa fa-search"></i> Filter &amp; Pencarian</div>
        <div class="srch-card-body">

          <div class="form-group" style="margin-bottom:8px">
            <label class="control-label col-md-2" style="font-size:12px">Jenis PO</label>
            <div class="col-md-4">
              <div class="radio" style="margin-top:4px">
                <label style="margin-right:12px">
                  <input name="flag" type="radio" class="ace" value="medis" checked/>
                  <span class="lbl" style="font-size:12px"> Medis</span>
                </label>
                <label>
                  <input name="flag" type="radio" class="ace" value="non_medis"/>
                  <span class="lbl" style="font-size:12px"> Non Medis</span>
                </label>
              </div>
            </div>
          </div>

          <div class="form-group" style="margin-bottom:8px">
            <label class="control-label col-md-2" style="font-size:12px">Pencarian berdasarkan</label>
            <div class="col-md-2">
              <select name="search_by" id="search_by" class="form-control input-sm">
                <option value="">-Silahkan Pilih-</option>
                <option value="no_po">Nomor PO</option>
                <option value="month" selected>Bulan</option>
                <option value="supplier">Nama Supplier</option>
                <option value="tgl_po">Tanggal PO</option>
              </select>
            </div>

            <div id="div_month">
              <label class="control-label col-md-1" style="font-size:12px">Bulan</label>
              <div class="col-md-1" style="margin-left:-15px">
                <?php echo $this->master->get_bulan(date('m'),'month','month','form-control input-sm','','')?>
              </div>
              <div class="col-md-1" style="margin-left:-15px">
                <?php echo $this->master->get_tahun(date('Y'),'year','year','form-control input-sm','','')?>
              </div>
            </div>

            <div id="div_supplier" style="display:none">
              <label class="control-label col-md-1" style="font-size:12px">Cari Supplier</label>
              <div class="col-md-3" style="margin-left:-15px">
                <input id="inputSupplier" class="form-control input-sm" type="text" placeholder="Masukan keyword minimal 3 karakter" />
                <input type="hidden" name="kodesupplier" id="kodesupplier" class="form-control">
              </div>
            </div>

            <div id="div_keyword" style="display:none">
              <label class="control-label col-md-1" style="font-size:12px">No. PO</label>
              <div class="col-md-2" style="margin-left:-15px">
                <input type="text" class="form-control input-sm" name="keyword" id="keyword_form" placeholder="Masukan No. PO">
              </div>
              <label class="control-label col-md-1" style="font-size:12px">Tahun</label>
              <div class="col-md-1" style="margin-left:-15px">
                <?php echo $this->master->get_tahun(date('Y'),'year_nopo','year_nopo','form-control input-sm','','')?>
              </div>
            </div>

            <div id="div_tgl_po" style="display:none">
              <div class="col-md-2" style="margin-left:-1%">
                <div class="input-group">
                  <input class="form-control input-sm date-picker" name="from_tgl" id="from_tgl" type="text" data-date-format="yyyy-mm-dd" value=""/>
                  <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                </div>
              </div>
              <div class="col-md-2" style="margin-left:-1%">
                <div class="input-group">
                  <input class="form-control input-sm date-picker" name="to_tgl" id="to_tgl" type="text" data-date-format="yyyy-mm-dd" value=""/>
                  <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                </div>
              </div>
            </div>

          </div>

        </div>
        <div class="srch-actions">
          <a href="#" id="btn_search_data" class="btn btn-sm btn-primary"><i class="fa fa-search"></i> Tampilkan</a>
          <a href="#" id="btn_export_excel" class="btn btn-sm btn-success"><i class="fa fa-file-excel-o"></i> Export Excel</a>
          <a href="#" id="btn_reset_data" class="btn btn-sm btn-warning"><i class="fa fa-refresh"></i> Reset</a>
        </div>
      </div>

      <div class="stat-row">
        <div class="stat-box">
          <div class="stat-label">Total Pembelian</div>
          <div class="stat-value" id="total_po">Rp.0,-</div>
        </div>
        <div class="stat-box">
          <div class="stat-label">Pembelian Barang Terbanyak</div>
          <div class="stat-sub" id="nm_brg_max"></div>
        </div>
      </div>
      <div class="stat-note">*) Data subtotal yang ditampilkan adalah total harga per item barang pada PO belum termasuk PPN</div>

      <div class="tbl-wrap">
        <div class="tbl-hdr">
          <i class="fa fa-bar-chart"></i> Monitoring Purchase Order (PO)
        </div>
        <table id="table-monitoring-po" base-url="purchasing/po/Po_monitoring/get_data?flag=" data-id="flag=" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th width="30px" class="center">No</th>
              <th width="150px">Nomor PO</th>
              <th>Tanggal</th>
              <th>Jenis</th>
              <th>Nama Supplier</th>
              <th>Kode</th>
              <th>Nama Barang</th>
              <th>Rasio</th>
              <th>Satuan</th>
              <th>Qty</th>
              <th>Harga @</th>
              <th>Disc(%)</th>
              <th>Sub Total</th>
              <th width="150px">Status</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>

    </form>
  </div>
</div>
