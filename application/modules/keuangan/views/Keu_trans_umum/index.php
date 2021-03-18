<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />

<script type="text/javascript">
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

  // setInterval("reload_table();",7000);

  $(document).ready(function(){

    oTable = $('#dt_table_trx_keu').DataTable({ 
          
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
          "url": $('#dt_table_trx_keu').attr('base-url')+'?from_tgl='+$('#from_tgl').val()+'&to_tgl='+$('#to_tgl').val()+'',
          "type": "POST"
      },
      "drawCallback": function (settings) { 
          // Here the response
          var response = settings.json;
          console.log(response.total_billing);
          $('#total_pemasukan').text(formatMoney(response.total_pemasukan));
          $('#total_pengeluaran').text(formatMoney(response.total_pengeluaran));
      },
      "columnDefs": [
          { 
            "targets": [ 0 ], 
            "orderable": false,
          },
          {"aTargets" : [0], "mData" : 0, "sClass":  "details-control"}, 
          { "visible": false, "targets": [1] },
        ],

    });

  })

  $('#dt_table_trx_keu tbody').on('click', 'td.details-control', function () {
      var tr = $(this).closest('tr');
      var row = oTable.row( tr );
      var data = oTable.row( $(this).parents('tr') ).data();
      var kode_perusahaan = data[ 1 ];
      

      if ( row.child.isShown() ) {
          // This row is already open - close it
          row.child.hide();
          tr.removeClass('shown');
      }
      else {
          /*data*/
          
          $.getJSON($('#dt_table_trx_keu').attr('url-detail')+ "/" + kode_perusahaan, '', function (data) {
              response_data = data;
                // Open this row
              row.child( response_data.html ).show();
              tr.addClass('shown');
          });
          
      }
    } );

    $('#dt_table_trx_keu tbody').on( 'click', 'tr', function () {
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

  function find_data_reload(result){
      oTable.ajax.url($('#dt_table_trx_keu').attr('base-url')+''+result.data).load();
      // $("html, body").animate({ scrollTop: "400px" });

  }

  function reload_table(){
    oTable.ajax.reload();
  }

  $('#btn_reset_data').click(function (e) {
      e.preventDefault();
      oTable.ajax.url($('#dt_table_trx_keu').attr('base-url')+'?from_tgl='+$('#from_tgl').val()+'&to_tgl='+$('#to_tgl').val()+'').load();
      // $("html, body").animate({ scrollDown: "400px" });
      $('#form_search')[0].reset();
  });

  function delete_data(myid){
  if(confirm('Are you sure?')){
    $.ajax({
        url: 'keuangan/Keu_trans_umum/delete',
        type: "post",
        data: {ID:myid},
        dataType: "json",
        beforeSend: function() {
          achtungShowLoader();  
        },
        uploadProgress: function(event, position, total, percentComplete) {
        },
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


</script>

<div class="page-header">
  <h1>
    <?php echo $title?>
    <small>
      <i class="ace-icon fa fa-angle-double-right"></i>
      <?php echo $breadcrumbs?>
    </small>
  </h1>
</div><!-- /.page-header -->


<div class="row">
  <div class="col-xs-12">

    <form class="form-horizontal" method="post" id="form_search" action="adm_pasien/penagihan/Adm_tagihan_list/find_data">
      <p><b>FORM PENCARIAN</b></p>
      
      <div class="form-group">
        <label class="control-label col-md-2">Tanggal Transaksi</label>
        <div class="col-md-1">
          <div class="input-group">
            <input class="form-control date-picker" name="from_tgl" id="from_tgl" type="text" data-date-format="yyyy-mm-dd" value="<?php echo isset($_GET['from_tgl'])?$_GET['from_tgl']:''; ?>"/>
            <span class="input-group-addon">
              <i class="fa fa-calendar bigger-110"></i>
            </span>
          </div>
        </div>
        <label class="control-label col-md-1" style="margin-left: 5.8%">s/d Tanggal</label>
        <div class="col-md-1">
          <div class="input-group">
            <input class="form-control date-picker" name="to_tgl" id="to_tgl" type="text" data-date-format="yyyy-mm-dd" value="<?php echo isset($_GET['to_tgl'])?$_GET['to_tgl']:''?>"/>
            <span class="input-group-addon">
              <i class="fa fa-calendar bigger-110"></i>
            </span>
          </div>
        </div>
      </div>

      <div class="form-group">
          
      </div>
  
      <div class="form-group">
        <label class="col-md-2">&nbsp;</label>
        <div class="col-md-6" style="margin-left:0.5%">
          <a href="#" id="btn_search_data" class="btn btn-xs btn-primary">
            <i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
            Tampilkan
          </a>
          <a href="#" id="btn_reset_data" class="btn btn-xs btn-danger">
            <i class="ace-icon fa fa-refresh icon-on-right bigger-110"></i>
            Reload
          </a>
        </div>
      </div>
      <hr class="separator">
      <div class="pull-left">
      <b>DATA TRANSAKSI UMUM KEUANGAN</b><br>
      Data yang ditampilkan dibawah ini adalah Data Transaksi Keuangan Tahun <b><?php echo date('Y')?></b> (Default)<br><br>
      </div>
      <div class="pull-right"><span class="green">Total Pemasukan</span><br><b><span id="total_pemasukan" style="font-size: 18px"></span></b></div>
      <div class="pull-right" style="padding-right: 20px"><span class="red">Total Pengeluaran</span><br><b><span id="total_pengeluaran" style="font-size: 18px"></span></b></div>
      <div class="clearfix"></div>
      <div class="pull-left"><a href="#" onclick="getMenu('keuangan/Keu_trans_umum/form')" class="btn btn-xs btn-primary pull-left">Buat Transaksi</a></div>
      <div style="margin-top:-27px">
        <table id="dt_table_trx_keu" base-url="keuangan/Keu_trans_umum/get_data?<?php $qry_url = isset($_GET) ? http_build_query($_GET) . "\n" : ''; echo $qry_url?>" url-detail="keuangan/Keu_trans_umum/get_detail" class="table table-bordered table-hover">
          <thead>
            <tr style="background-color:#428bca">
              <th width="50px"></th>
              <th class="center"></th>
              <th width="50px" class="center">No</th> 
              <th>No. Transaksi</th>
              <th>Tanggal</th>
              <th>Uraian</th>
              <th>Jenis</th>
              <th>Jumlah(Rp.)</th>
              <th>Sumber Dana</th>
              <th>Penerima</th>
              <th class="center">KWI</th>
              <th class="center"></th>
            </tr>
          </thead>
        </table>
      </div>   

    </form>
  </div><!-- /.col -->
</div><!-- /.row -->




