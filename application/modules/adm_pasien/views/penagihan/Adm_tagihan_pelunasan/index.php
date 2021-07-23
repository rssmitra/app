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

    oTable = $('#dt_table_perusahaan').DataTable({ 
          
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
          "url": $('#dt_table_perusahaan').attr('base-url')+'?jenis_pelayanan='+$('input[name="jenis_pelayanan"]:checked').val()+'',
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

  $('#dt_table_perusahaan tbody').on('click', 'td.details-control', function () {
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
          
          $.getJSON($('#dt_table_perusahaan').attr('url-detail')+ "/" + kode_perusahaan, '', function (data) {
              response_data = data;
                // Open this row
              row.child( response_data.html ).show();
              tr.addClass('shown');
          });
          
      }
    } );

    $('#dt_table_perusahaan tbody').on( 'click', 'tr', function () {
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
      oTable.ajax.url($('#dt_table_perusahaan').attr('base-url')+''+result.data).load();
      // $("html, body").animate({ scrollTop: "400px" });

  }

  function reload_table(){
    oTable.ajax.reload();
  }
  $('#btn_reset_data').click(function (e) {
      e.preventDefault();
      oTable.ajax.url($('#dt_table_perusahaan').attr('base-url')+'?keyword='+$('#keyword').val()+'&from_tgl='+$('#from_tgl').val()+'&to_tgl='+$('#to_tgl').val()+'&jenis_pelayanan='+$('#jenis_pelayanan').val()+'').load();
      // $("html, body").animate({ scrollDown: "400px" });
      $('#form_search')[0].reset();
  });

  function show_detail_inv(kode_tc_trans_kasir, id_tc_tagih){
    preventDefault();
    // beban pasien
    var beban_pasien = $('#beban_pasien_'+kode_tc_trans_kasir+'').text();
    $.getJSON("adm_pasien/penagihan/Adm_tagihan_pelunasan/get_billing_detail/" + kode_tc_trans_kasir, '', function (response) {
      $('#dt_detail_invoice_'+id_tc_tagih+' tbody').remove();
      $('#txt_no_invoice_'+id_tc_tagih+'').text(response.no_registrasi);
      $.each(response.data, function (i, o) {
          if(o.subtotal > 0){
            $('<tr><td align="center">'+o.count_num+'</td><td>'+o.title+'</td><td align="right">'+formatMoney(o.subtotal)+'</td></tr>').appendTo($('#dt_detail_invoice_'+id_tc_tagih+''));            
          }
      });
      $('<tr><td align="right" colspan="2">Subtotal</td><td align="right">'+formatMoney(response.total)+'</td></tr>').appendTo($('#dt_detail_invoice_'+id_tc_tagih+'')); 
      $('<tr><td align="right" colspan="2">Beban Pasien</td><td align="right">'+formatMoney(beban_pasien)+'</td></tr>').appendTo($('#dt_detail_invoice_'+id_tc_tagih+'')); 
      var total_tagihan = parseInt(response.total) - parseInt(beban_pasien);
      $('<tr><td align="right" colspan="2"><b>Total Tagihan</b></td><td align="right"><a href="#" onclick="PopupCenter('+"'billing/Billing/print_preview?no_registrasi="+response.no_registrasi+"&status_nk=1&flag_bill=true'"+', '+"'Rincian Billing Pasien'"+', 900, 650)">'+formatMoney(total_tagihan)+'</a></td></tr>').appendTo($('#dt_detail_invoice_'+id_tc_tagih+'')); 
      
    });
  }

  function preview_billing(id_tc_tagih){
    preventDefault();
    var no_registrasi = $('#txt_no_invoice_'+id_tc_tagih+'').text();
    PopupCenter('billing/Billing/print_preview?no_registrasi='+no_registrasi+'&status_nk=1&flag_bill=true', 'Billing Pasien' , 900 , 650);
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

    <form class="form-horizontal" method="post" id="form_search" action="adm_pasien/penagihan/Adm_tagihan_pelunasan/find_data">
      <p><b>FORM PENCARIAN</b></p>
      
      <div class="form-group">
        <label class="control-label col-md-2">Jenis Pelayanan</label>
          <div class="col-md-4">
            <div class="radio">
                  <label>
                    <input name="jenis_pelayanan" type="radio" class="ace" value="RJ" <?php echo isset($_GET['jenis_pelayanan']) ? ($_GET['jenis_pelayanan'] == 'RJ') ? 'checked' : '' : 'checked'?> />
                    <span class="lbl"> Rawat Jalan</span>
                  </label>
                  <label>
                    <input name="jenis_pelayanan" type="radio" class="ace" value="RI" <?php echo isset($_GET['jenis_pelayanan']) ? ($_GET['jenis_pelayanan'] == 'RI') ? 'checked' : '' : ''?>/>
                    <span class="lbl"> Rawat Inap</span>
                  </label>
            </div>
          </div>
      </div>

      <div class="form-group">
          <label class="control-label col-md-2">No Invoice</label>
          <div class="col-md-3">
            <input type="text" name="no_invoice" id="no_invoice" value="<?php echo isset($_GET['no_invoice'])?$_GET['no_invoice']:''?>" class="form-control">
          </div>
      </div>

      <div class="form-group">
        <label class="control-label col-md-2">Tanggal Tagihan</label>
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
        <b>DATA TAGIHAN PERUSAHAAN ASURANSI</b><br>
        Silahkan lakukan pencarian untuk menampilkan data.<br><br>
      </div>
      <div class="pull-right">Total tagihan<br><b><span id="txt_total_tagihan" style="font-size: 18px"></span></b></div>
      <div class="clearfix"></div>
      <br>
      <div style="margin-top:-27px">
        <table id="dt_table_perusahaan" base-url="adm_pasien/penagihan/adm_tagihan_pelunasan/get_data?<?php $qry_url = isset($_GET) ? http_build_query($_GET) . "\n" : ''; echo $qry_url?>" url-detail="adm_pasien/penagihan/adm_tagihan_pelunasan/get_hist_inv" class="table table-bordered table-hover">
          <thead>
            <tr style="background-color:#428bca">
              <th width="38px"></th>
              <th></th>
              <th width="40px" class="center">No</th> 
              <th width="40px" class="center">INV</th>
              <th width="40px" class="center">KWI</th>
              <th width="180px">No. Invoice</th>
              <th class="center">Tanggal</th>
              <th width="80px" class="center">Jth Tempo</th>
              <th width="360px">Nama Perusahaan</th>
              <th class="center">Total Tagihan</th>
              <th class="center">Diskon</th>
              <th class="center">Total Bayar</th>
              <th class="center">Status</th>
              <th width="110px" class="center">Pembayaran</th>
            </tr>
          </thead>
        </table>
      </div>   
    </form>


  </div><!-- /.col -->
</div><!-- /.row -->




