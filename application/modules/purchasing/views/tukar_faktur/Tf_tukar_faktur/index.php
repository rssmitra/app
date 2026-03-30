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

var base_url = $('#dynamic-table').attr('base-url');
var params = $('#dynamic-table').attr('data-id');

$(document).ready(function() {

    oTable = $('#dynamic-table').DataTable({
        "processing": true,
        "serverSide": true,
        "ordering": false,
        "searching": false,
        "bLengthChange": true,
        "bInfo": false,
        "paging": false,
        "ajax": {
            "url": base_url+'/get_data?'+params+'&checked_nama_perusahaan='+$('#checked_nama_perusahaan').val()+'&nama_perusahaan='+$('#nama_perusahaan').val()+'&checked_no_po='+$('#checked_no_po').val()+'&no_po='+$('#no_po').val()+'&tahun='+$('#tahun').val()+'',
            "type": "POST"
        },
        "drawCallback": function (settings) {
            var response = settings.json;
            console.log(response.total);
            $('#txt_total_tagihan').text(formatMoney(response.total));
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
            $.getJSON( url_detail + "/" + kode_primary + "?" +params, '' , function (data) {
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

    $('#btn_reset_data').click(function (e) {
        e.preventDefault();
        reset_table();
        $('#form_search')[0].reset();
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

    $("#btn_tukar_faktur").click(function(event){
            event.preventDefault();
            var searchIDs = $("#dynamic-table input:checkbox:checked").map(function(){
            return $(this).val();
            }).toArray();
            if(searchIDs.length < 0){
                alert('Tidak ada item yang dipilih!'); return false;
            }
            tukar_faktur(searchIDs);
            console.log(searchIDs);
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

})


function format_html ( data ) {
  return data.html;
}

function find_data_reload(result){
    oTable.ajax.url(base_url+'/get_data?'+result.data).load();
}

function reset_table(){
    oTable.ajax.url(base_url+'/get_data?'+params).load();
}

function checkAll(elm) {
  if($(elm).prop("checked") == true){
      $('table .ace').each(function(){
          $(this).prop("checked", true);
      });
  }else{
      $('table .ace').prop("checked", false);
  }
}

function get_detail_brg_po(myid){
    if(confirm('Are you sure?')){
        $.ajax({
            url: 'purchasing/tukar_faktur/Tf_tukar_faktur/get_detail_brg_po',
            type: "post",
            data: {ID:myid},
            dataType: "json",
            beforeSend: function() { achtungShowLoader(); },
            uploadProgress: function(event, position, total, percentComplete) {},
            complete: function(xhr) {
            var data=xhr.responseText;
            var jsonResponse = JSON.parse(data);
            getMenuTabs('purchasing/tukar_faktur/Tf_tukar_faktur/create_po/'+$('#flag').val()+'?'+jsonResponse.params+'', 'tabs_form_po');
            achtungHideLoader();
            }
        });
    }else{
        return false;
    }
}

function tukar_faktur(data){
    $.ajax({
        url: $('#form_search').attr('action'),
        type: "post",
        data: {IDS : data, flag : $('#flag').val(), checked_nama_perusahaan : $('#checked_nama_perusahaan').val(), checked_no_po : $('#checked_no_po').val(), nama_perusahaan : $('#nama_perusahaan').val(), no_po : $('#no_po').val(), tahun: $('#tahun').val() },
        dataType: "json",
        success: function(response) {
        console.log(response.data);
        getMenu('purchasing/tukar_faktur/Tf_tukar_faktur/form?'+response.data+'');
        }
    });
}
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

    <form class="form-horizontal" method="post" id="form_search" action="purchasing/tukar_faktur/Tf_tukar_faktur/find_data?flag=<?php echo $flag?>" autocomplete="off">

      <input type="hidden" name="flag" id="flag" value="<?php echo $flag?>">

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

            <div class="control-label col-md-1">
              <div class="checkbox" style="margin-top:-5px">
                <label>
                  <input name="checked_no_po" id="checked_no_po" value="1" type="checkbox" class="ace" <?php echo isset($_GET['checked_no_po']) ? ($_GET['checked_no_po'] == 1) ? 'checked' : '' : '' ?>>
                  <span class="lbl" style="font-size:12px"> No. PO</span>
                </label>
              </div>
            </div>
            <div class="col-md-2">
              <input type="text" value="<?php echo isset($_GET['no_po']) ? $_GET['no_po'] : '' ?>" name="no_po" id="no_po" class="form-control input-sm">
            </div>
            <label class="control-label col-md-1" style="font-size:12px">Tahun</label>
            <div class="col-sm-1">
              <?php echo $this->master->get_tahun(isset($_GET['tahun']) ? $_GET['tahun'] : date('Y'),'tahun','tahun','form-control input-sm','','')?>
            </div>
          </div>

        </div>
        <div class="srch-actions">
          <a href="#" id="btn_search_data" class="btn btn-sm btn-primary"><i class="fa fa-search"></i> Tampilkan Data</a>
          <a href="#" id="btn_reset_data" class="btn btn-sm btn-warning"><i class="fa fa-refresh"></i> Reload</a>
          <a href="#" id="btn_tukar_faktur" class="btn btn-sm btn-warning" style="margin-left:auto"><i class="fa fa-exchange"></i> Tukar Faktur</a>
          <div class="total-box">
            <div class="label-txt">Total Hutang</div>
            <div class="amount"><span id="txt_total_tagihan">0</span></div>
          </div>
        </div>
      </div>

      <div class="tbl-wrap">
        <div class="tbl-hdr">
          <i class="fa fa-table"></i> Data Penerimaan Barang Supplier
        </div>
        <table id="dynamic-table" base-url="purchasing/tukar_faktur/Tf_tukar_faktur" data-id="flag=<?php echo $flag?>" url-detail="purchasing/tukar_faktur/Tf_tukar_faktur/get_detail" class="table table-bordered table-hover">
          <thead>
            <tr>
              <th width="30px" class="center">
                <div class="center">
                  <label class="pos-rel">
                    <input type="checkbox" class="ace" name="" onClick="checkAll(this);" value=""/>
                    <span class="lbl"></span>
                  </label>
                </div>
              </th>
              <th width="40px" class="center"></th>
              <th width="40px"></th>
              <th width="50px">ID</th>
              <th width="140px">Kode Penerimaan</th>
              <th width="120px">Nomor PO</th>
              <th width="120px">Tgl Diterima</th>
              <th>Nama Supplier</th>
              <th width="120px">No Faktur</th>
              <th width="120px">Penerima</th>
              <th width="120px">Keterangan</th>
              <th width="100px">Total</th>
              <th width="50px" class="center">TF</th>
              <th width="50px" class="center">BAST</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>

    </form>
  </div>
</div>
