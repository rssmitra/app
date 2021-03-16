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
            "url": $('#dt_table_perusahaan').attr('base-url'),
            "type": "POST"
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

    $( "#keyword" ).keypress(function(event) {  
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

    function show_detail_inv(id_tagih, kode_perusahaan){
      preventDefault();
      $.getJSON("adm_pasien/penagihan/Adm_tagihan_perusahaan/get_invoice_detail/" + id_tagih, '', function (response) {
        $('#dt_detail_invoice_'+kode_perusahaan+' tbody').remove();
        $('#txt_no_invoice_'+kode_perusahaan+'').text(response.no_invoice);
        $.each(response.data, function (i, o) {
            var no = 1;
            $('<tr><td>'+o.kode_tc_trans_kasir+'</td><td>'+o.tgl_jam+'</td><td>'+o.no_mr+'</td><td>'+o.nama_pasien+'</td><td align="right">'+formatMoney(o.jumlah_billing)+'</td><td align="right">'+formatMoney(o.jumlah_dijamin)+'</td><td align="right">'+formatMoney(o.jumlah_tagih)+'</td></tr>').appendTo($('#dt_detail_invoice_'+kode_perusahaan+''));
            no++;
        });
        
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

    <form class="form-horizontal" method="post" id="form_search" action="adm_pasien/penagihan/Adm_tagihan_perusahaan/find_data">
      <p><b>FORM PENCARIAN</b></p>
      <!-- <div class="form-group">
          <label class="control-label col-md-2">Cari Nama Perusahaan</label>
          <div class="col-md-3">
            <input type="text" name="keyword" id="keyword" value="<?php echo isset($_GET['keyword'])?$_GET['keyword']:''?>" class="form-control">
          </div>
      </div> -->

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
        <label class="control-label col-md-2" style="margin-left: 5.8%">Jenis Pelayanan</label>
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
      <center>
      <b>DATA TRANSAKSI PELAYANAN PASIEN ASURANSI</b><br>
      Silahkan lakukan pencarian data terlebih dahulu untuk menampilkan data transaksi.<br><br>
      </center>
      <div class="clearfix"></div>
      <br>
      <div style="margin-top:-27px">
        <table id="dt_table_perusahaan" base-url="adm_pasien/penagihan/adm_tagihan_perusahaan/get_data?<?php $qry_url = isset($_GET) ? http_build_query($_GET) . "\n" : ''; echo $qry_url?>" url-detail="adm_pasien/penagihan/adm_tagihan_perusahaan/get_hist_inv" class="table table-bordered table-hover">
          <thead>
            <tr style="background-color:#428bca">
              <th width="50px"></th>
              <th class="center"></th>
              <th width="50px" class="center">No</th> 
              <th>Nama Perusahaan</th>
              <th width="150px">Jumlah Tagihan</th>
              <th width="100px">Invoice</th>
            </tr>
          </thead>
        </table>
      </div>   

    </form>
  </div><!-- /.col -->
</div><!-- /.row -->




