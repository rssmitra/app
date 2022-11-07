<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />

<script type="text/javascript">

$('#txt_title').text('Laporan Penjualan Tanggal '+$('#from_tgl').val()+' s/d '+$('#to_tgl').val()+'');

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

var oTable;
var base_url = $('#dynamic-table').attr('base-url'); 
var params = $('#dynamic-table').attr('data-id'); 

$(document).ready(function() {
    //initiate dataTables plugin
    oTable = $('#dynamic-table').DataTable({ 
          
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "searching": false,
      "pageLength": false,
      "bPaginate": false,
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": base_url+'/get_data?from_tgl='+$('#from_tgl').val()+'&to_tgl='+$('#to_tgl').val()+'',
          "type": "POST"
      },
      "drawCallback": function (settings) { 
        // Here the response
        var response = settings.json;
        console.log(response.total_penjualan);
        $('#total_penjualan').text('Rp. '+formatMoney(response.total_penjualan)+'');
    },

    });
    

    $('#dynamic-table tbody').on( 'click', 'tr', function () {
        if ( $(this).hasClass('selected') ) {
            $(this).removeClass('selected');
        }
        else {
            oTable.$('tr.selected').removeClass('selected');
            $(this).addClass('selected');
        }
    } );
      

    $('#btn_search_data').click(function (e) {
        
          e.preventDefault();
          $.ajax({
          url: $('#form_search').attr('action'),
          type: "post",
          data: $('#form_search').serialize(),
          dataType: "json",
          beforeSend: function() {
            achtungShowLoader();  
            $('#txt_from').text($('#from_tgl').val());
            $('#txt_to').text($('#to_tgl').val());
          },
          success: function(data) {
            achtungHideLoader();
            find_data_reload(data,base_url);
          }
        });
      });

    $('#btn_reset_data').click(function (e) {
            e.preventDefault();
            reset_table();
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


});


function export_excel(result){

  window.open(base_url+'/export_excel?'+result.data+'','_blank'); 

}

function find_data_reload(result, base_url){
  
    var data = result.data;    
    oTable.ajax.url(base_url+'/get_data?'+data).load();
    // $("html, body").animate({ scrollTop: "400px" });

}

function reset_table(){
    oTable.ajax.url(base_url).load();
    // $("html, body").animate({ scrollTop: "400px" });

}

function reload_table(){
   oTable.ajax.reload(); //reload datatable ajax 
}
  
</script>
<div class="row">
  <div class="col-xs-12">

    <div class="page-header">
      <h1>
        <?php echo $title?>
        <small>
          <i class="ace-icon fa fa-angle-double-right"></i>
          <?php echo isset($breadcrumbs)?$breadcrumbs:''?>
        </small>
      </h1>
    </div><!-- /.page-header -->

    <form class="form-horizontal" method="post" id="form_search" action="farmasi/Lap_penjualan_obat/find_data">

        <center>
            <h4><?php echo strtoupper($title)?> <br><small style="font-size:12px">Data yang ditampilkan saat ini adalah Data Penjualan Hari ini.</small></h4>
        </center>
      
        <br>

        <div class="form-group">
          <label class="control-label col-md-2">Tanggal Penjualan/Transaksi</label>
          <div class="col-md-2">
            <div class="input-group">
              <input class="form-control date-picker" name="from_tgl" id="from_tgl" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>"/>
              <span class="input-group-addon">
                <i class="fa fa-calendar bigger-110"></i>
              </span>
            </div>
          </div>

          <label class="control-label col-md-1">s/d Tanggal</label>
          <div class="col-md-2" style="margin-left:-10px">
            <div class="input-group">
              <input class="form-control date-picker" name="to_tgl" id="to_tgl" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>"/>
              <span class="input-group-addon">
                <i class="fa fa-calendar bigger-110"></i>
              </span>
            </div>
          </div>

          <div class="col-md-6" style="margin-left: -1.3%">
            <a href="#" id="btn_search_data" class="btn btn-xs btn-default">
              <i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
              Search
            </a>
            <a href="#" id="btn_reset_data" class="btn btn-xs btn-warning">
              <i class="ace-icon fa fa-refresh icon-on-right bigger-110"></i>
              Reset
            </a>
            <a href="#" id="btn_export_excel" class="btn btn-xs btn-success">
              <i class="ace-icon fa fa-excel icon-on-right bigger-110"></i>
              Export Excel
            </a>
          </div>
        </div>
       

        <hr class="separator">
        <div class="pull-left">
          <span style="font-style: italic">Total Penjualan Periode <span id="txt_from"><?php echo date('Y-m-d')?></span> s/d <span id="txt_to"><?php echo date('Y-m-d')?></span> </span><br>
          <span id="total_penjualan" style="font-size: 20px; font-weight: bold">Rp.,-</span>
        </div>
        <div style="margin-top:-27px">
          <table id="dynamic-table" base-url="farmasi/Lap_penjualan_obat" class="table table-bordered table-hover">
            <thead>
              <tr>  
                <th width="50px" class="center">No</th>
                <th width="100px">Kode Barang</th>
                <th>Nama Barang</th>
                <th width="100px" >Terjual</th>
                <th width="100px">Satuan</th>
                <th width="100px">Harga Satuan</th>
                <!-- <th class="center" width="100px">Stok Akhir Gudang</th> -->
                <!-- <th class="center" width="100px">Stok Akhir Farmasi</th> -->
                <th width="100px" class="center">Total (Rp.)</th>
              </tr>
            </thead>
            <tbody>
            </tbody>
          </table>

        </div>

    </form>
  </div><!-- /.col -->
</div><!-- /.row -->

