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
      "drawCallback": function (response) { 
        // Here the response
          var objData = response.json;
          $('#total_pasien').text(formatMoney(objData.recordsTotal));
          $('#label_tunai').text(formatMoney(objData.tunai));
          $('#label_nontunai').text(formatMoney(objData.nontunai));
          $('#label_potongan').text(formatMoney(objData.potongan));
          $('#label_nk_perusahaan').text(formatMoney(objData.piutang));
          $('#label_nk_karyawan').text(formatMoney(objData.nk_karyawan));
          $('#label_total_billing').text(formatMoney(objData.billing));
          $('#div2').html(objData.html_trans);
          $('#div3').html(objData.html_costing);
          $('.tgl_filter').html(objData.from_tgl+' s.d '+objData.to_tgl);

      },

    });

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

  function export_excel(type){

    var json_data = {
      "from_tgl" : $('#from_tgl').val(),
      "to_tgl" : $('#to_tgl').val(),
      "penjamin" : $('#penjamin').val(),
      "flag" : $('#flag').val(),
      "type" : type,
      "title" : $('#title_'+type+'').text(),
    };
    preventDefault();
      $.ajax({
      url: $('#form_search').attr('action'),
      type: "post",
      data: json_data,
      dataType: "json",
      beforeSend: function() {
      },
      success: function(response) {
        window.open('eksekutif/Eks_pendapatan/export_excel?'+response.data+'','_blank'); 
      }
    })

  }

  function find_data_reload(result){
      oTable.ajax.url($('#dt_harian_kasir').attr('base-url')+'?'+result.data).load();
      // $("html, body").animate({ scrollTop: "400px" });

  }

  function reload_table(){
    oTable.ajax.reload();
  }

  $('#btn_reset_data').click(function (e) {
      e.preventDefault();
      oTable.ajax.url($('#dt_harian_kasir').attr('base-url')+'?flag='+$('#flag').val()).load();
      // $("html, body").animate({ scrollDown: "400px" });
      $('#form_search')[0].reset();
  });


</script>

<style> 
 .table_wrapper{
    display: block;
    overflow-x: auto;
    white-space: nowrap;
}
</style>

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

    <form class="form-horizontal" method="post" id="form_search" action="templates/References/find_data">
        <!-- hidden form -->
        <!-- <input type="hidden" name="flag" id="flag" value="<?php echo $flag?>"> -->
        <span style="font-weight: bold">PENCARIAN DATA TRANSAKSI</span>
          <div class="form-group">
            <label class="control-label col-md-1">Seri Kuitansi</label>
            <div class="col-md-1">
              <select class="form-control" name="flag" id="flag">
                <option value="all">Pilih Semua</option>
                <option value="RJ" selected>RJ</option>
                <option value="RI">RI</option>
                <option value="PB">PB</option>
              </select>
            </div>
            <label class="control-label col-md-1">Tgl Transaksi</label>
            <div class="col-md-2">
              <div class="input-daterange input-group">
                <input type="text" class="input-xs date-picker" style="max-width: 100px" name="from_tgl" id="from_tgl" value="<?php echo date('Y-m-d')?>" data-date-format="yyyy-mm-dd">
                <span class="input-group-addon">
                  s.d
                </span>
                <input type="text" class="input-xs date-picker" style="max-width: 100px; margin-left:0px !important" name="to_tgl" id="to_tgl" value="<?php echo date('Y-m-d')?>" data-date-format="yyyy-mm-dd">
              </div>
            </div>
            <div class="col-md-2" style="margin-left: 22px;">
              <select class="form-control" name="penjamin" id="penjamin">
                <option value="#">-Pilih Penjamin-</option>
                <option value="120">BPJS Kesehatan</option>
                <option value="um">Umum</option>
                <option value="asuransi">Asuransi Lainnya</option>
                <option value="all">Semua</option>
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
              
            </div>

        </div>
        <hr>

        <div class="tabbable">
          <ul class="nav nav-tabs" id="myTab">
            <li class="active">
              <a data-toggle="tab" href="#data-transaksi">
              Pendapatan Kasir Berdasarkan Submit Kasir
              </a>
            </li>

            <li>
              <a data-toggle="tab" href="#data-transaksi-2">
              Pendapatan Kasir Berdasarkan Jenis Tindakan
              </a>
            </li>

            <li>
              <a data-toggle="tab" href="#data-transaksi-3">
              Pendapatan Kasir Berdasarkan Costing Tariif
              </a>
            </li>

          </ul>

          <div class="tab-content">

            <div id="data-transaksi" class="tab-pane fade in active">

              <div class="row">

                
                  <center><span style="font-weight: bold" id="title_1">REKAPITULASI PENDAPATAN BERDASARKAN DATA YANG DISUBMIT OLEH KASIR <br>PERIODE TANGGAL <span class="tgl_filter"></span></span></center>
                  <br>
                  
                  
                  <div class="col-md-12">
                    <button type="button" name="btn-export" value="1" onclick="export_excel(1)" class="btn btn-xs btn-success">
                      <i class="ace-icon fa fa-file-excel-o icon-on-right bigger-110"></i>
                      Export Excel
                    </button>
                    <table class="table">
                      <tr>
                        <td align="right" style="font-size: 11px; width: 18%">
                          Total Pasien<br>
                          <h3 style="font-weight: bold; margin-top : 0px; font-size: 16px"><span id="total_pasien">0</span></h3>
                        </td>
                        <td align="right" style="font-size: 11px; width: 18%">
                          TUNAI<br>
                          <h3 style="font-weight: bold; margin-top : 0px; font-size: 16px"><span id="label_tunai">0</span>,-</h3>
                        </td>
                        <td align="right" style="font-size: 11px; width: 18%">
                          NON TUNAI (DEBIT/CC)<br>
                          <h3 style="font-weight: bold; margin-top : 0px; font-size: 16px"><span id="label_nontunai">0</span>,-</h3>
                        </td>
                        <td align="right" style="font-size: 11px; width: 18%">
                          PIUTANG PERUSAHAAN<br>
                          <h3 style="font-weight: bold; margin-top : 0px; font-size: 16px"><span id="label_nk_perusahaan">0</span>,-</h3>
                        </td>
                        <td align="right" style="font-size: 11px; width: 18%">
                          PIUTANG KARYAWAN<br>
                          <h3 style="font-weight: bold; margin-top : 0px; font-size: 16px"><span id="label_nk_karyawan">0</span>,-</h3>
                        </td>
                        <td align="right" style="font-size: 11px; width: 18%">
                          TOTAL BILLING<br>
                          <h3 style="font-weight: bold; margin-top : 0px; font-size: 16px"><span id="label_total_billing">0</span>,-</h3>
                        </td>
                      </tr>
                    </table>
                  </div>

                  <div class="col-md-12">
                    <table id="dt_harian_kasir" base-url="eksekutif/Eks_pendapatan/get_data" class="table table-bordered table-hover">
                      <thead>
                        <tr style="background-color:#428bca">
                          <th class="center">No</th>
                          <th width="90px">No. Kuitansi</th>
                          <th width="120px">Tgl Submit</th>
                          <th>Pasien</th>
                          <th>Penjamin</th>
                          <th>Bagian Masuk</th>
                          <th>Tunai</th>
                          <th width="100px">Non-Tunai</th>
                          <th>Potongan</th>
                          <th>Perusahaan</th>
                          <th>Karyawan</th>
                          <th>Total</th>
                          <!-- <th>Petugas</th> -->
                        </tr>
                      </thead>
                    </table>
                  </div>

              </div>

            </div>

            <div id="data-transaksi-2" class="tab-pane fade">
              
              <div class="row">
              <center><span style="font-weight: bold" id="title_2">REKAPITULASI PENDAPATAN BERDASARKAN DATA YANG DISUBMIT OLEH KASIR <br>GROUPING BERDASARKAN JENIS TINDAKAN<br>PERIODE TANGGAL <span class="tgl_filter"></span></span></center>
                <br>
                <div class="col-md-12">
                  <div id="div2"></div>
                </div>
              </div>
            
            </div>

            <div id="data-transaksi-3" class="tab-pane fade">
              
              <div class="row">
              <center><span style="font-weight: bold" id="title_2">REKAPITULASI PENDAPATAN BERDASARKAN DATA YANG DISUBMIT OLEH KASIR <br>GROUPING BERDASARKAN COSTING TARIF<br>PERIODE TANGGAL <span class="tgl_filter"></span></span></center>
                <br>
                <div class="col-md-12">
                  <div id="div3"></div>
                </div>
              </div>
            
            </div>

          </div>
        </div>
        
        

    </form>
  </div><!-- /.col -->
</div><!-- /.row -->




