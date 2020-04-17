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

    get_total_billing();
    oTable = $('#dt_pasien_kasir').DataTable({ 
          
        "processing": true, //Feature control the processing indicator.
        "serverSide": true, //Feature control DataTables' server-side processing mode.
        "ordering": false,
        "searching": false,
        "bLengthChange": true,
        "pageLength": 25,
        "bInfo": true,
        "paging": true,
        // Load data for the table's content from an Ajax source
        "ajax": {
            "url": $('#dt_pasien_kasir').attr('base-url'),
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

    $('#dt_pasien_kasir tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = oTable.row( tr );
            var data = oTable.row( $(this).parents('tr') ).data();
            var no_registrasi = data[ 1 ];
            

            if ( row.child.isShown() ) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                /*data*/
               
                $.getJSON("adm_pasien/loket_kasir/Adm_kasir/getDetailTransaksi/" + no_registrasi, '', function (data) {
                    response_data = data;
                     // Open this row
                    row.child( format( response_data ) ).show();
                    tr.addClass('shown');
                });
               
            }
    } );

    function format ( data ) {
        return data.html;
    }

  })

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

  $('#btn_reset_data').click(function (e) {
      e.preventDefault();
      oTable.ajax.url($('#dt_pasien_kasir').attr('base-url')+'?flag='+$('#flag').val()+'&pelayanan='+$('#pelayanan').val()).load();
      $("html, body").animate({ scrollDown: "400px" });
      $('#form_search')[0].reset();
  });
  

  function find_data_reload(result){
      get_total_billing();
      oTable.ajax.url($('#dt_pasien_kasir').attr('base-url')+'?'+result.data).load();
      $("html, body").animate({ scrollTop: "400px" });

  }

  function reload_table(){
    get_total_billing();
    oTable.ajax.reload();
  }
  

  function get_total_billing(){
      var url_search = $('#form_search').attr('action');
      $.ajax({
        url: url_search,
        type: "post",
        data: $('#form_search').serialize(),
        dataType: "json",
        success: function(response) {
          console.log(response.data);
          $.getJSON("adm_pasien/pembayaran_dr/Pembentukan_saldo_dr/get_total_billing?"+response.data, '', function (data) {
             // code here
              $('#label_total_billing_dr').text( formatMoney(parseInt(data.total_billing)) );
          });
        }
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
  
    <form class="form-horizontal" method="post" id="form_search" action="adm_pasien/pembayaran_dr/Pembentukan_saldo_dr/find_data">
      <!-- hidden form -->
      <div class="row">
          <div class="col-xs-10">
            <div class="form-group">
              <label class="control-label col-md-2">Pilih Dokter</label>
              <div class="col-md-6">
                <?php echo $this->master->custom_selection($params = array('table' => 'mt_dokter_v', 'id' => 'kode_dokter', 'name' => 'nama_pegawai', 'where' => array()), '' , 'kode_dokter', 'kode_dokter', 'form-control', '', '') ?>
              </div>
            </div>

            <div class="form-group" id="form_tanggal" >
              <label class="control-label col-md-2">Tgl Transaksi Kasir</label>
                <div class="col-md-3">
                  <div class="input-group">
                    <input class="form-control date-picker" name="from_tgl" id="from_tgl" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>"/>
                    <span class="input-group-addon">
                      <i class="fa fa-calendar bigger-110"></i>
                    </span>
                  </div>
                </div>

                <label class="control-label col-md-1">s/d Tgl</label>
                <div class="col-md-3">
                  <div class="input-group">
                    <input class="form-control date-picker" name="to_tgl" id="to_tgl" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>"/>
                    <span class="input-group-addon">
                      <i class="fa fa-calendar bigger-110"></i>
                    </span>
                  </div>
                </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-2">&nbsp;</label>
              <div class="col-md-6" style="margin-left:6px">
                <a href="#" id="btn_search_data" class="btn btn-xs btn-primary">
                  <i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
                  Cari
                </a>
                <a href="#" id="btn_reset_data" class="btn btn-xs btn-danger">
                  <i class="ace-icon fa fa-refresh icon-on-right bigger-110"></i>
                  Reload
                </a>
              </div>
            </div>   
          </div>

          <div class="col-xs-2">
            <div class="pull-right" style="border-left: 1px solid #b2b3b5; padding-left: 10px; padding-right: 10px; background: #89f56ed1; width:100% !important">
              <span style="font-size: 12px">Total Saldo</span>
              <h3 style="font-weight: bold; margin-top : 0px; font-size: 14px"><span id="label_total_billing_dr">0</span>,-</h3>
            </div>
          </div>
      </div>
      

      <div id="showDataTables">
        <table id="dt_pasien_kasir" base-url="adm_pasien/pembayaran_dr/Pembentukan_saldo_dr/get_data" url-detail="billing/Billing/getDetailBillingKasir" class="table table-bordered table-hover">
          <thead>
            <tr style="background-color:#428bca">
            <th width="50px"></th>
            <th class="center"></th>
            <th width="50px" class="center">No</th>
            <th>Kode</th>
            <th>Tanggal</th>
            <th>No. MR</th>
            <th>Nama Pasien</th>
            <th width="250px">Tindakan</th>
            <th width="100px">Total Billing</th>
          </tr>
          </thead>
        </table>
      </div>   

    </form>
  </div><!-- /.col -->
</div><!-- /.row -->




