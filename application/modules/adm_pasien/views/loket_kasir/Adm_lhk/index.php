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
   
    oTable = $('#dt_harian_kasir').DataTable({ 
          
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
          "url": $('#dt_harian_kasir').attr('base-url')+'?flag='+$('#flag').val()+'',
          "type": "POST"
      },
      "columnDefs": [
        { 
          "targets": [ 0 ], 
          "orderable": false,
        },
        {"aTargets" : [0], "mData" : 0, "sClass":  "details-control"}, 
        { "visible": false, "targets": [1] },
        { "visible": false, "targets": [2] },
      ],

    });

    $('#dt_harian_kasir tbody').on('click', 'td.details-control', function () {
            var tr = $(this).closest('tr');
            var row = oTable.row( tr );
            var data = oTable.row( $(this).parents('tr') ).data();
            var kode_tc_trans_kasir = data[ 1 ];
            var no_registrasi = data[ 2 ];
            

            if ( row.child.isShown() ) {
                // This row is already open - close it
                row.child.hide();
                tr.removeClass('shown');
            }
            else {
                /*data*/
               
                $.getJSON("adm_pasien/loket_kasir/Adm_lhk/getDetailTransaksi/" + kode_tc_trans_kasir + "/" + no_registrasi, '', function (data) {
                    response_data = data;
                     // Open this row
                    row.child( format( response_data ) ).show();
                    tr.addClass('shown');
                });
               
            }
    } );

    $('#dt_harian_kasir tbody').on( 'click', 'tr', function () {
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

    $("#merge_registrasi").click(function(event){
          event.preventDefault();
          var searchIDs = $("#dt_harian_kasir input:checkbox:checked").map(function(){
            return $(this).val();
          }).toArray();
          merge_registrasi(''+searchIDs+'')
          console.log(searchIDs);
    });

    function merge_registrasi( arr ){
      $.ajax({
          url: 'adm_pasien/loket_kasir/Adm_kasir/merge_data_registrasi',
          type: "post",
          data: { value : arr },
          dataType: "json",
          beforeSend: function() {
          },
          success: function(data) {
            
          }
      });
    }

    function format ( data ) {
        return data.html;
    }

  })

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


  $('#add_search_by_date').click(function() {
    if (!$(this).is(':checked')) {
      $('#form_tanggal').hide();
    }else{
      $('#form_tanggal').show();
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
          $('#tgl_filter').text(getFormattedDate($('#from_tgl').val()));
        }
      });
  });

  $('#btn_export_excel').click(function (e) {
      e.preventDefault();
      $.ajax({
      url: $('#form_search').attr('action'),
      type: "post",
      data: $('#form_search').serialize(),
      dataType: "json",
      beforeSend: function() {
        achtungShowLoader();  
      },
      success: function(data) {
        achtungHideLoader();
        export_excel(data);
      }
    })
  });

  function export_excel(result){

    window.open('adm_pasien/loket_kasir/Adm_lhk/export_excel?'+result.data+'','_blank'); 

  }

  function find_data_reload(result){
      get_total_billing();
      oTable.ajax.url($('#dt_harian_kasir').attr('base-url')+'?'+result.data).load();
      // $("html, body").animate({ scrollTop: "400px" });

  }

  function reload_table(){
    get_total_billing();
    oTable.ajax.reload();
  }

  $('#btn_reset_data').click(function (e) {
      e.preventDefault();
      oTable.ajax.url($('#dt_harian_kasir').attr('base-url')+'?flag='+$('#flag').val()).load();
      $("html, body").animate({ scrollDown: "400px" });
      $('#form_search')[0].reset();
  });

  function get_total_billing(){
      var url_search = $('#form_search').attr('action');
      $.ajax({
        url: url_search,
        type: "post",
        data: $('#form_search').serialize(),
        dataType: "json",
        success: function(response) {
          console.log(response.data);
          $.getJSON("adm_pasien/loket_kasir/Adm_lhk/get_resume_kasir?"+response.data, '', function (data) {
             // code here
              $('#label_tunai').text( formatMoney(parseInt(data.tunai)) );
              $('#label_debet').text( formatMoney(parseInt(data.debet)) );
              $('#label_kredit').text( formatMoney(parseInt(data.kredit)) );
              $('#label_nk_perusahaan').text( formatMoney(parseInt(data.nk_perusahaan)) );
              $('#label_nk_karyawan').text( formatMoney(parseInt(data.nk_karyawan)) );
              $('#label_total_billing').text( formatMoney(parseInt(data.bill)) );
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

    <form class="form-horizontal" method="post" id="form_search" action="adm_pasien/loket_kasir/Adm_kasir/find_data">
        <!-- hidden form -->
        <input type="hidden" name="flag" id="flag" value="<?php echo $flag?>">
        <span style="font-weight: bold">PENCARIAN DATA TRANSAKSI</span>
          <div class="form-group">
            <label class="control-label col-md-2">Tanggal Transaksi</label>
            <div class="col-md-2">
              <div class="input-group">
                <input class="form-control date-picker" name="from_tgl" id="from_tgl" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>"/>
                <span class="input-group-addon">
                  <i class="fa fa-calendar bigger-110"></i>
                </span>
              </div>
            </div>

            <label class="control-label col-md-2">Perusahaan Penjamin</label>
            <div class="col-md-3">
              <select class="form-control" name="penjamin" id="penjamin">
                <option value="#">-Pilih-</option>
                <option value="120">BPJS Kesehatan</option>
                <option value="um">Umum</option>
                <option value="asuransi">Asuransi Lainnya</option>
              </select>
            </div>
            <div class="col-md-3">
              <a href="#" id="btn_search_data" class="btn btn-xs btn-primary">
                <i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
                Tampilkan
              </a>
              <a href="#" id="btn_reset_data" class="btn btn-xs btn-danger">
                <i class="ace-icon fa fa-refresh icon-on-right bigger-110"></i>
                Reload
              </a>

              <a href="#" id="btn_export_excel" class="btn btn-xs btn-success">
                <i class="ace-icon fa fa-file-excel-o icon-on-right bigger-110"></i>
                Export Excel
              </a>
            </div>

        </div>
        <hr>
        <span style="font-weight: bold">REKAPITULASI PENDAPATAN RS TANGGAL <span id="tgl_filter"><?php echo date('d/M/Y')?></span></span>
        <div class="col-md-12 no-padding">
          <div class="col-md-2 no-padding">
            <table class="table">
              <tr>
                <td align="right" style="font-size: 11px">
                  TUNAI<br>
                  <h3 style="font-weight: bold; margin-top : 0px; font-size: 16px"><span id="label_tunai">0</span>,-</h3>
                </td>
              </tr>
            </table>
          </div>

          <div class="col-md-2 no-padding">
            <table class="table">
              <tr>
                <td align="right" style="font-size: 11px">
                  DEBET<br>
                  <h3 style="font-weight: bold; margin-top : 0px; font-size: 16px"><span id="label_debet">0</span>,-</h3>
                </td>
              </tr>
            </table>
          </div>

          <div class="col-md-2 no-padding">
            <table class="table">
              <tr>
                <td align="right" style="font-size: 11px">
                  KREDIT<br>
                  <h3 style="font-weight: bold; margin-top : 0px; font-size: 16px"><span id="label_kredit">0</span>,-</h3>
                </td>
              </tr>
            </table>
          </div>

          <div class="col-md-2 no-padding">
            <table class="table">
              <tr>
                <td align="right" style="font-size: 11px">
                  PIUTANG PERUSAHAAN<br>
                  <h3 style="font-weight: bold; margin-top : 0px; font-size: 16px"><span id="label_nk_perusahaan">0</span>,-</h3>
                </td>
              </tr>
            </table>
          </div>

          <div class="col-md-2 no-padding">
            <table class="table">
              <tr>
                <td align="right" style="font-size: 11px">
                  PIUTANG KARYAWAN<br>
                  <h3 style="font-weight: bold; margin-top : 0px; font-size: 16px"><span id="label_nk_karyawan">0</span>,-</h3>
                </td>
              </tr>
            </table>
          </div>

          <div class="col-md-2 no-padding">
            <table class="table">
              <tr>
                <td align="right" style="font-size: 11px">
                  TOTAL BILLING<br>
                  <h3 style="font-weight: bold; margin-top : 0px; font-size: 16px"><span id="label_total_billing">0</span>,-</h3>
                </td>
              </tr>
            </table>
          </div>
        </div>
        

        <table id="dt_harian_kasir" base-url="adm_pasien/loket_kasir/Adm_lhk/get_data" class="table table-bordered table-hover">
          <thead>
            <tr style="background-color:#428bca">
              <th width="50px"></th>
              <th width="50px"></th>
              <th class="center"></th>
              <th>No</th>
              <th width="100px">No. Kuitansi</th>
              <th>Tanggal</th>
              <th>Pasien</th>
              <th>Tunai</th>
              <th>Debet</th>
              <th>Kredit</th>
              <th>Potongan</th>
              <th>Piutang Perusahaan</th>
              <th>Piutang Karyawan</th>
              <th>Total</th>
              <th>Petugas</th>
            </tr>
          </thead>
        </table>

    </form>
  </div><!-- /.col -->
</div><!-- /.row -->




