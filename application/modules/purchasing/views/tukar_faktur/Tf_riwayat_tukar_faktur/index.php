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

    oTable = $('#dynamic-table').DataTable({ 
          
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
          "url": $('#dynamic-table').attr('base-url'),
          "type": "POST"
      },
      "drawCallback": function (settings) { 
          // Here the response
          var response = settings.json;
          console.log(response.total_billing);
          $('#txt_total_tagihan').text(formatMoney(response.total_billing));
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

  $('#dynamic-table tbody').on('click', 'td.details-control', function () {
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
          
          $.getJSON($('#dynamic-table').attr('url-detail')+ "/" + kode_perusahaan, '', function (data) {
              response_data = data;
                // Open this row
              row.child( response_data.html ).show();
              tr.addClass('shown');
          });
          
      }
    } );

    $('#dynamic-table tbody').on( 'click', 'tr', function () {
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
          // do what is needed with item
          var val_item=item.split(':')[0];
          var label_item=item.split(':')[1];
          console.log(label_item);
          $('#nama_perusahaan').val(label_item);
          
      }
  }); 
  
  function find_data_reload(result){
      oTable.ajax.url($('#dynamic-table').attr('base-url')+''+result.data).load();
      // $("html, body").animate({ scrollTop: "400px" });

  }

  function reload_table(){
    oTable.ajax.reload();
  }
  $('#btn_reset_data').click(function (e) {
      e.preventDefault();
      oTable.ajax.url($('#dynamic-table').attr('base-url')+'?keyword='+$('#keyword').val()+'&from_tgl='+$('#from_tgl').val()+'&to_tgl='+$('#to_tgl').val()+'').load();
      // $("html, body").animate({ scrollDown: "400px" });
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
            $('<tr><td align="center">'+o.count_num+'</td><td>'+o.nama_brg+'</td><td>'+o.jml_kirim+' '+o.satuan+'</td><td align="right">'+formatMoney(o.harga_satuan)+'</td><td align="right">'+formatMoney(o.subtotal)+'</td></tr>').appendTo($('#dt_detail_penerimaan_'+id_tc_hutang_supplier_inv+''));            
          }
      });
      $('<tr><td align="right" colspan="4">Subtotal</td><td align="right">'+formatMoney(response.total)+'</td></tr>').appendTo($('#dt_detail_penerimaan_'+id_tc_hutang_supplier_inv+'')); 
      
    });
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

    <form class="form-horizontal" method="post" id="form_search" action="purchasing/tukar_faktur/Tf_riwayat_tukar_faktur/find_data" autocomplete="off">
      <p><b>FORM PENCARIAN</b></p>
      <div class="form-group" style="padding-bottom: 3px">
        <div class="control-label col-md-2">
          <div class="checkbox" style="margin-top: -5px">
            <label>
              <input name="checked_nama_perusahaan" id="checked_nama_perusahaan" type="checkbox" class="ace" value="1" <?php echo isset($_GET['checked_nama_perusahaan']) ? ($_GET['checked_nama_perusahaan'] == 1) ? 'checked' : '' : '' ?>>
              <span class="lbl"> Nama Perusahaan</span>
            </label>
          </div>
        </div>
        <div class="col-md-2" style="margin-left: -15px">
            <input type="text" value="<?php echo isset($_GET['nama_perusahaan']) ? $_GET['nama_perusahaan'] : '' ?>" name="nama_perusahaan" id="nama_perusahaan" class="form-control">
        </div>

        <div class="control-label col-md-2">
          <div class="checkbox" style="margin-top: -5px">
            <label>
              <input name="checked_no_ttf" id="checked_no_ttf" value="1" type="checkbox" class="ace" <?php echo isset($_GET['checked_no_ttf']) ? ($_GET['checked_no_ttf'] == 1) ? 'checked' : '' : '' ?>>
              <span class="lbl"> No Terima Faktur</span>
            </label>
          </div>
        </div>
        <div class="col-md-2" style="margin-left: -15px">
            <input type="text" value="<?php echo isset($_GET['no_ttf']) ? $_GET['no_ttf'] : '' ?>" name="no_ttf" id="no_ttf" class="form-control">
        </div>
      </div>

      <div class="form-group" style="padding-bottom: 3px">
        <div class="control-label col-md-2">
          <div class="checkbox" style="margin-top: -5px">
            <label>
              <input name="checked_from_tgl" id="checked_from_tgl" type="checkbox" class="ace" value="1" <?php echo isset($_GET['checked_from_tgl']) ? ($_GET['checked_from_tgl'] == 1) ? 'checked' : '' : '' ?>>
              <span class="lbl"> Tgl Tukar Faktur</span>
            </label>
          </div>
        </div>
        <div class="col-md-2" style="margin-left: -15px">
          <div class="input-group">
            <input class="form-control date-picker" name="from_tgl" id="from_tgl" type="text" data-date-format="yyyy-mm-dd" value="<?php echo isset($_GET['from_tgl'])?$_GET['from_tgl']:''; ?>"/>
            <span class="input-group-addon">
              <i class="fa fa-calendar bigger-110"></i>
            </span>
          </div>
        </div>

        <label class="control-label col-md-1">s/d Tgl</label>
        <div class="col-md-2">
          <div class="input-group">
            <input class="form-control date-picker" name="to_tgl" id="to_tgl" type="text" data-date-format="yyyy-mm-dd" value="<?php echo isset($_GET['to_tgl'])?$_GET['to_tgl']:''; ?>"/>
            <span class="input-group-addon">
              <i class="fa fa-calendar bigger-110"></i>
            </span>
          </div>
        </div>
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
      <b>DATA PENGAJUAN PEMBAYARAN FAKTUR</b><br>
      Data yang ditampilkan dibawah ini adalah Data Pengajuan Pembayaran Bulan <b><?php echo $this->tanggal->getBulan(date('m'))?></b> Tahun <b><?php echo date('Y')?></b> (Default)<br><br>
      </div>
      <div class="pull-right">Total tagihan<br><b><span id="txt_total_tagihan" style="font-size: 18px"></span></b></div>
      <div class="clearfix"></div>
      <br>
      <div style="margin-top:-27px">
        <table id="dynamic-table" base-url="purchasing/tukar_faktur/Tf_riwayat_tukar_faktur/get_data?<?php $qry_url = isset($_GET) ? http_build_query($_GET) . "\n" : ''; echo $qry_url?>" url-detail="purchasing/tukar_faktur/Tf_riwayat_tukar_faktur/get_log_data" class="table table-bordered table-hover">
          <thead>
            <tr style="background-color:#428bca">
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
  </div><!-- /.col -->
</div><!-- /.row -->




