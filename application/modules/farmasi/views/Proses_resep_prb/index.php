<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>
<script>

jQuery(function($) {

  $('.date-picker').datepicker({
    autoclose: true,
    todayHighlight: true
  })
  //show datepicker when clicking on the icon
  .next().on(ace.click_event, function(){
    $(this).prev().focus();
  });
});

oTable = $('#dt_copy_lunas').DataTable({ 
          
    "processing": true, //Feature control the processing indicator.
    "serverSide": true, //Feature control DataTables' server-side processing mode.
    "ordering": false,
    "searching": false,
    "bLengthChange": true,
    "pageLength": 25,
    "bInfo": false,
    "paging": false,
    // Load data for the table's content from an Ajax source
    "ajax": {
        "url": $('#dt_copy_lunas').attr('base-url'),
        "data": {search_by:$('#search_by').val(),keyword:$('#keyword').val(), from_tgl:$('#from_tgl').val(), to_tgl:$('#to_tgl').val()},
        "type": "POST"
    },
    "columnDefs": [
      { 
        "targets": [ 0 ], 
        "orderable": false,
      },
      {"aTargets" : [0], "mData" : 0, "sClass":  "details-control"}, 
      { "visible": false, "targets": [1,3] },
    ],
});

$('#dt_copy_lunas tbody').on('click', 'td.details-control', function () {
        var tr = $(this).closest('tr');
        var row = oTable.row( tr );
        var data = oTable.row( $(this).parents('tr') ).data();
        var no_registrasi = data[ 3 ];
        

        if ( row.child.isShown() ) {
            // This row is already open - close it
            row.child.hide();
            tr.removeClass('shown');
        }
        else {
            /*data*/
            
            $.getJSON($('#dt_copy_lunas').attr('url-detail')+ "/" + no_registrasi + "?flag=All", '', function (data) {
                response_data = data;
                  // Open this row
                row.child( format( response_data ) ).show();
                tr.addClass('shown');
            });
            
        }
} );

$('#dt_copy_lunas tbody').on( 'click', 'tr', function () {
    if ( $(this).hasClass('selected') ) {
        //achtungShowLoader();
        $(this).removeClass('selected');
        //achtungHideLoader();
    }
    else {
        //achtungShowLoader();
        oTable.$('tr.selected').removeClass('selected');
        $(this).addClass('selected');
        //achtungHideLoader();
    }
} );

$('#btn_reset_data').click(function (e) {
    e.preventDefault();
    oTable.ajax.url($('#dt_copy_lunas').attr('base-url')+'?search_by='+$('#search_by').val()+'&keyword='+$('#keyword').val()+'&from_tgl='+$('#from_tgl').val()+'&to_tgl='+$('#to_tgl').val()+'').load();
    $("html, body").animate({ scrollDown: "400px" });
    $('#form_search')[0].reset();
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

$( "#keyword" )
  .keypress(function(event) {
    var keycode =(event.keyCode?event.keyCode:event.which); 
    if(keycode ==13){
      event.preventDefault();
      return false;       
    }
});

function popUnder(node) {
    var newWindow = window.open("about:blank", node.target, "width=700,height=500"); 
    window.focus();
    newWindow.location.href = node.href;
    return false;
}

function format ( data ) {
    return data.html;
}

function find_data_reload(result){
    oTable.ajax.url($('#dt_copy_lunas').attr('base-url')+'&'+result.data).load();
    $("html, body").animate({ scrollTop: "400px" });

}

  function reload_table(){
    oTable.ajax.reload();
  }

</script>

<div class="page-header">
  <h1>
    <?php echo $title?>
    <small>
      <i class="ace-icon fa fa-angle-double-right"></i>
      <?php echo isset($breadcrumbs)?$breadcrumbs:''?>
    </small>
  </h1>
</div><!-- /.page-header -->

<form class="form-horizontal" method="post" id="form_search" action="Templates/References/find_data" autocomplete="off">

<center>
  <h4>DATA RESEP COPY LUNAS PASIEN<br><small style="font-size:12px">Data yang ditampilkan saat ini adalah Data Resep Copy Lunas Pasien 1 Bulan terakhir </small></h4>
</center>

    <div class="form-group">
        <label class="control-label col-md-2">Pencarian berdasarkan</label>
        <div class="col-md-2">
          <select name="search_by" id="search_by" class="form-control">
            <option value="no_sep" >Nomor SEP</option>
            <option value="kode_trans_far" selected>Kode Transaksi</option>
            <option value="no_mr">No MR</option>
            <option value="nama_pasien">Nama Pasien</option>
          </select>
        </div>

        <label class="control-label col-md-1">Keyword</label>
        <div class="col-md-2">
          <input type="text" class="form-control" name="keyword" id="keyword">
        </div>
    </div>

    <div class="form-group">
      <label class="control-label col-md-2">Tanggal</label>
        <div class="col-md-2">
          <div class="input-group">
            <input class="form-control date-picker" name="from_tgl" id="from_tgl" type="text" data-date-format="yyyy-mm-dd" value=""/>
            <span class="input-group-addon">
              <i class="fa fa-calendar bigger-110"></i>
            </span>
          </div>
        </div>

        <label class="control-label col-md-1">s/d</label>
        <div class="col-md-2" style="margin-lef:-10px">
          <div class="input-group">
            <input class="form-control date-picker" name="to_tgl" id="to_tgl" type="text" data-date-format="yyyy-mm-dd" value=""/>
            <span class="input-group-addon">
              <i class="fa fa-calendar bigger-110"></i>
            </span>
          </div>
        </div>
    </div>
    
    <div class="form-group">
      <label class="control-label col-md-2 ">&nbsp;</label>
      <div class="col-md-10" style="margin-left: 5px">
        <a href="#" id="btn_search_data" class="btn btn-xs btn-default">
          <i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
          Search
        </a>
        <a href="#" id="btn_reset_data" class="btn btn-xs btn-warning">
          <i class="ace-icon fa fa-refresh icon-on-right bigger-110"></i>
          Reset
        </a>
      </div>
    </div>

  <hr class="separator">
  <!-- div.dataTables_borderWrap -->
  <div style="margin-top:-27px">
  <table id="dt_copy_lunas" base-url="farmasi/Proses_resep_prb/get_data?flag=All" data-id="flag=All" url-detail="farmasi/Proses_resep_prb/get_detail" class="table table-bordered table-hover">

      <thead>
        <tr>  
          <th width="30px" class="center"></th>
          <th width="40px" class="center"></th>
          <th width="30px" class="center">No</th>
          <th width="40px"></th>
          <th>Kode</th>
          <!-- <th>No. SEP</th> -->
          <th>Tgl Resep</th>
          <th>No Mr</th>
          <th>Nama Pasien</th>
          <th>Alamat</th>
          <th>Telp/HP</th>
          <!-- <th>Total</th> -->
          <!-- <th>Status</th> -->
        </tr>
      </thead>
      <tbody>
      </tbody>
    </table>
  </div>

</form>





