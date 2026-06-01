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

  $("#btn_create_po").click(function(event){
        event.preventDefault();
        var searchIDs = $("#dynamic-table input:checkbox:checked").map(function(){
          return $(this).val();
        }).toArray();
        get_detail_brg_po(''+searchIDs+'')
        console.log(searchIDs);
  });

  function checkAll(elm) {
    if($(elm).prop("checked") == true){
      $('.ace').each(function(){
          $(this).prop("checked", true);
      });
    }else{
      $('.ace').prop("checked", false);
    }
  }

  function get_detail_brg_po(myid){
    if(confirm('Are you sure?')){
      $.ajax({
          url: 'purchasing/pendistribusian/Penerimaan_stok/get_detail_brg_po',
          type: "post",
          data: {ID:myid},
          dataType: "json",
          beforeSend: function() { achtungShowLoader(); },
          uploadProgress: function(event, position, total, percentComplete) {},
          complete: function(xhr) {
            var data=xhr.responseText;
            var jsonResponse = JSON.parse(data);
            getMenuTabs('purchasing/pendistribusian/Penerimaan_stok/create_po/'+$('#flag_string').val()+'?'+jsonResponse.params+'', 'tabs_form_po');
            achtungHideLoader();
          }
      });
    }else{
      return false;
    }
  }

  $('select[name="search_by"]').change(function () {
    if( $(this).val() == 'month'){
      $('#div_month').show();
      $('#div_keyword').hide();
    }
    if( $(this).val() == 'kode_permintaan'){
      $('#div_month').hide();
      $('#div_keyword').show();
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

    <form class="form-horizontal" method="post" id="form_search" action="purchasing/pendistribusian/Penerimaan_stok/find_data?flag=<?php echo $flag?>">

      <input type="hidden" name="flag" id="flag" value="<?php echo $flag?>">

      <div class="srch-card">
        <div class="srch-card-hdr"><i class="fa fa-search"></i> Filter &amp; Pencarian</div>
        <div class="srch-card-body">

          <div class="form-group" style="margin-bottom:0px">
            <label class="control-label col-md-2" style="font-size:12px">Pilih Bagian/Unit</label>
            <div class="col-md-5">
              <?php echo $this->master->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array()), '' , 'kode_bagian', 'kode_bagian', 'form-control input-sm', '', '') ?>
            </div>
          </div>

          <div class="form-group" style="margin-bottom:0px">
            <label class="control-label col-md-2" style="font-size:12px">Tanggal Permintaan</label>
            <div class="col-md-2">
              <div class="input-group">
                <input class="form-control input-sm date-picker" name="from_tgl" id="from_tgl" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-01')?>" placeholder="Dari..."/>
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
              </div>
            </div>
            <label class="control-label col-md-1" style="font-size:12px">s/d</label>
            <div class="col-md-2">
              <div class="input-group">
                <input class="form-control input-sm date-picker" name="to_tgl" id="to_tgl" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-t')?>" placeholder="Sampai..."/>
                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
              </div>
            </div>
          </div>

          <div class="form-group" style="margin-bottom:0px">
            <label class="control-label col-md-2" style="font-size:12px">Jenis Barang</label>
            <div class="col-md-4">
              <div class="radio" style="margin-top:4px">
                <label style="margin-right:12px">
                  <input name="flag" type="radio" class="ace" value="medis" onclick="$('#btn_search_data').click();" <?php echo isset($flag) && $flag == 'medis' ? 'checked' : 'checked' ?>/>
                  <span class="lbl" style="font-size:12px"> Medis</span>
                </label>
                <label>
                  <input name="flag" type="radio" class="ace" value="non_medis" onclick="$('#btn_search_data').click();" <?php echo isset($flag) && $flag == 'non_medis' ? 'checked' : '' ?>/>
                  <span class="lbl" style="font-size:12px"> Non Medis</span>
                </label>
              </div>
            </div>
          </div>

          <div class="form-group" style="margin-bottom:0px">
            <label class="control-label col-md-2" style="font-size:12px">Status</label>
            <div class="col-md-2">
              <select name="status_penerimaan" id="status_penerimaan" class="form-control input-sm">
                <option value="">-- Semua Status --</option>
                <option value="selesai">Selesai</option>
                <option value="belum_diterima">Belum diterima user</option>
                <option value="belum_dikirim">Belum dikirim</option>
              </select>
            </div>
          </div>

        </div>
        <div class="srch-actions">
          <a href="#" id="btn_search_data" class="btn btn-sm btn-primary"><i class="fa fa-search"></i> Cari</a>
          <a href="#" id="btn_reset_data" class="btn btn-sm btn-warning"><i class="fa fa-refresh"></i> Reset</a>
          <span style="margin-left:auto">
            <a href="" class="btn btn-sm btn-inverse" id="button_print_multiple"><i class="fa fa-print"></i> Print Selected</a>
          </span>
        </div>
      </div>

      <div class="tbl-wrap">
        <div class="tbl-hdr">
          <i class="fa fa-table"></i> Data Penerimaan Stok
          <span style="margin-left:auto;font-weight:400;font-size:11px;opacity:.85">Gudang <?php echo($flag=='non_medis')?'Umum':'Medis'?></span>
        </div>
        <table id="dynamic-table" base-url="purchasing/pendistribusian/Penerimaan_stok" data-id="flag=<?php echo $flag?>" url-detail="purchasing/pendistribusian/Permintaan_stok_unit/get_detail" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th width="30px" class="center">
                <div class="center">
                  <label class="pos-rel">
                    <input type="checkbox" class="ace" name="" onClick="checkAll(this);" value="0"/>
                    <span class="lbl"></span>
                  </label>
                </div>
              </th>
              <th width="40px" class="center"></th>
              <th width="40px"></th>
              <th width="40px"></th>
              <th width="50px">ID</th>
              <th>Tgl Permintaan</th>
              <th>Bagian/Unit</th>
              <th>Petugas</th>
              <th>Keterangan</th>
              <th>Tgl ACC</th>
              <th width="100px">Disetujui Oleh</th>
              <th width="100px">Status Verif</th>
              <th>Tgl Dikirim</th>
              <th>Tgl Diterima</th>
              <th width="120px">Diterima Oleh</th>
              <th width="120px">Status</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>

    </form>
  </div>
</div>

<script>
var oTable;
var base_url = $('#dynamic-table').attr('base-url');
var params = $('#dynamic-table').attr('data-id');

$(document).ready(function() {

    oTable = $('#dynamic-table').DataTable({
      "processing": true,
      "serverSide": true,
      "ordering": false,
      "pageLength": 25,
      "scrollY": "600px",
      "lengthMenu": [ [10, 25, 50, -1], [10, 25, 50, "All"] ],
      "ajax": {
          "url": base_url+'/get_data?'+params,
          "type": "POST"
      },
      "columnDefs": [
          { "targets": [ 0 ], "orderable": false },
          { "aTargets" : [ 1 ], "mData" : 1, "sClass": "details-control"},
          { "visible": true, "targets": [ 1 ] },
          { "targets": [ 2 ], "visible": false },
      ],
    });

    $('#dynamic-table tbody').on('click', 'td.details-control', function () {
        var url_detail = $('#dynamic-table').attr('url-detail');
        preventDefault();
        var tr = $(this).closest('tr');
        var row = oTable.row( tr );
        var data = oTable.row( $(this).parents('tr') ).data();
        var kode_primary = data[ 2 ];
        console.log(data);
        if ( row.child.isShown() ) {
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            $.getJSON( url_detail + "/" + kode_primary + "?flag=" +$( 'input[name=flag]:checked' ).val(), '' , function (data) {
                response_data = data;
                row.child( format_html( response_data ) ).show();
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

    $("#button_delete").click(function(event){
          event.preventDefault();
          var searchIDs = $("#dynamic-table input:checkbox:checked").map(function(){
            return $(this).val();
          }).toArray();
          delete_data(''+searchIDs+'')
          console.log(searchIDs);
    });

    $("#button_print_multiple").click(function(event){
          event.preventDefault();
          var searchIDs = $("#dynamic-table input:checkbox:checked").map(function(){
            return $(this).val();
          }).toArray();
          print_data(''+searchIDs+'');
          console.log(searchIDs);
    });

    $('#btn_reset_data').click(function (e) {
        e.preventDefault();
        reset_table();
        $('#form_search')[0].reset();
    });

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
  window.open(base_url+'/export_excel?'+result.data+'','_blank');
}

function format_html ( data ) {
  return data.html;
}

function find_data_reload(result){
    oTable.ajax.url(base_url+'/get_data?'+result.data).load();
}

function reset_table(){
    oTable.ajax.url(base_url+'/get_data?'+params).load();
}

function reload_table(){
   oTable.ajax.reload();
}

function delete_data(myid){
  if(confirm('Are you sure?')){
    $.ajax({
        url: base_url+'/delete?'+params,
        type: "post",
        data: {ID:myid},
        dataType: "json",
        beforeSend: function() { achtungShowLoader(); },
        uploadProgress: function(event, position, total, percentComplete) {},
        complete: function(xhr) {
          var data=xhr.responseText;
          var jsonResponse = JSON.parse(data);
          if(jsonResponse.status === 200){
            $.achtung({message: jsonResponse.message, timeout:5});
            reload_table();
          }else{
            $.achtung({message: jsonResponse.message, timeout:5});
          }
          achtungHideLoader();
        }
      });
  }else{
    return false;
  }
}

function print_data(myid){
  $.ajax({
    url: base_url+'/print_multiple?'+params,
    type: "post",
    data: {ID:myid, flag: params},
    dataType: "json",
    beforeSend: function() {},
    uploadProgress: function(event, position, total, percentComplete) {},
    complete: function(xhr) {
      var data=xhr.responseText;
      var jsonResponse = JSON.parse(data);
      PopupCenter(''+base_url+'/print_multiple_preview?'+jsonResponse.queryString+'', 'PRINT PREVIEW', 1000, 550);
    }
  });
}

function kirim_permintaan(myid){
  if(confirm('Apakah Anda yakin akan mengirim permintaan ini?')){
    $.ajax({
        url: base_url+'/kirim_permintaan',
        type: "post",
        data: {
          ID: myid,
          flag: $('input[name=flag]:checked').val()
        },
        dataType: "json",
        beforeSend: function() { achtungShowLoader(); },
        success: function(response) {
          if(response.status === 200){
            $.achtung({message: response.message, timeout:5});
            reload_table();
          } else {
            $.achtung({message: response.message, timeout:5, className: 'achtungFail'});
          }
        },
        error: function(xhr, status, error) {
          $.achtung({message: 'Terjadi kesalahan saat mengirim permintaan', timeout:5, className: 'achtungFail'});
        },
        complete: function() { achtungHideLoader(); }
    });
  } else {
    return false;
  }
}
</script>
