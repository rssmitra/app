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

   
    oTable = $('#datatable_rows').DataTable({ 
          
      "processing": true, //Feature control the processing indicator.
      "serverSide": true, //Feature control DataTables' server-side processing mode.
      "ordering": false,
      "searching": false,
      "bLengthChange": true,
      "pageLength": 25,
      "bInfo": false,
      "paging": false,
      "scrollX": true,
      // Load data for the table's content from an Ajax source
      "ajax": {
          "url": $('#datatable_rows').attr('base-url')+'?start_date='+$('#start_date').val()+'&end_date='+$('#end_date').val()+'',
          "type": "POST"
      },
      "drawCallback": function (response) { 
        // Here the response
          var objData = response.json;
          $('#total_pasien').text(formatMoney(objData.recordsTotal));

          $("#ttl_bill_dr1").text(formatMoney(objData.ttl_bill_dr1));
          $("#ttl_bill_dr2").text(formatMoney(objData.ttl_bill_dr2));
          $("#ttl_bhp").text(formatMoney(objData.ttl_bhp));
          $("#ttl_bhp_apotik").text(formatMoney(objData.ttl_bhp_apotik));
          $("#ttl_bill_kamar").text(formatMoney(objData.ttl_bill_kamar));
          $("#ttl_kamar_tindakan").text(formatMoney(objData.ttl_kamar_tindakan));
          $("#ttl_alat_rs").text(formatMoney(objData.ttl_alat_rs));
          $("#ttl_profit").text(formatMoney(objData.ttl_profit));
          $("#ttl_total_bill").text(formatMoney(objData.ttl_total_bill));
          // rekap rawat jalan by kategori
          $("#um_ttl_pasien").text(formatMoney(objData.um_ttl_pasien));
          $("#asuransi_ttl_pasien").text(formatMoney(objData.asuransi_ttl_pasien));
          $("#bpjs_ttl_pasien").text(formatMoney(objData.bpjs_ttl_pasien));
          $("#um_revenue").text(formatMoney(objData.um_ttl_revenue));
          $("#asuransi_revenue").text(formatMoney(objData.asuransi_ttl_revenue));
          $("#bpjs_revenue").text(formatMoney(objData.bpjs_ttl_revenue));
          $("#um_cost").text(formatMoney(objData.um_ttl_cost));
          $("#asuransi_cost").text(formatMoney(objData.asuransi_ttl_cost));
          $("#bpjs_cost").text(formatMoney(objData.bpjs_ttl_cost));
          $("#um_profit").text(formatMoney(objData.um_ttl_profit));
          $("#asuransi_profit").text(formatMoney(objData.asuransi_ttl_profit));
          $("#bpjs_profit").text(formatMoney(objData.bpjs_ttl_profit));
          // rekap rawat inap by kategori
          $("#ri_um_ttl_pasien").text(formatMoney(objData.ri_um_ttl_pasien));
          $("#ri_asuransi_ttl_pasien").text(formatMoney(objData.ri_asuransi_ttl_pasien));
          $("#ri_bpjs_ttl_pasien").text(formatMoney(objData.ri_bpjs_ttl_pasien));
          $("#ri_um_revenue").text(formatMoney(objData.ri_um_ttl_revenue));
          $("#ri_asuransi_revenue").text(formatMoney(objData.ri_asuransi_ttl_revenue));
          $("#ri_bpjs_revenue").text(formatMoney(objData.ri_bpjs_ttl_revenue));
          $("#ri_um_cost").text(formatMoney(objData.ri_um_ttl_cost));
          $("#ri_asuransi_cost").text(formatMoney(objData.ri_asuransi_ttl_cost));
          $("#ri_bpjs_cost").text(formatMoney(objData.ri_bpjs_ttl_cost));
          $("#ri_um_profit").text(formatMoney(objData.ri_um_ttl_profit));
          $("#ri_asuransi_profit").text(formatMoney(objData.ri_asuransi_ttl_profit));
          $("#ri_bpjs_profit").text(formatMoney(objData.ri_bpjs_ttl_profit));

          // rekap total
          
          $("#all_ttl_pasien").text(formatMoney(objData.all_ttl_pasien));
          $("#all_ttl_revenue").text(formatMoney(objData.all_ttl_revenue));
          $("#all_ttl_cost").text(formatMoney(objData.all_ttl_cost));
          $("#all_ttl_profit").text(formatMoney(objData.all_ttl_profit));

          $("#all_ri_ttl_pasien").text(formatMoney(objData.all_ri_ttl_pasien));
          $("#all_ri_ttl_revenue").text(formatMoney(objData.all_ri_ttl_revenue));
          $("#all_ri_ttl_cost").text(formatMoney(objData.all_ri_ttl_cost));
          $("#all_ri_ttl_profit").text(formatMoney(objData.all_ri_ttl_profit));

          // total rs
          var ttl_rs = parseInt(objData.all_ttl_pasien) + parseInt(objData.all_ri_ttl_pasien);
          $("#total_pasien_rs").text(formatMoney(ttl_rs));
          var ttl_revenue = parseInt(objData.all_ttl_revenue) + parseInt(objData.all_ri_ttl_revenue);
          $("#total_revenue_rs").text(formatMoney(ttl_revenue));
          var ttl_cost = parseInt(objData.all_ttl_cost) + parseInt(objData.all_ri_ttl_cost);
          $("#total_cost_rs").text(formatMoney(ttl_cost));
          var ttl_profit = parseInt(objData.all_ttl_profit) + parseInt(objData.all_ri_ttl_profit);
          $("#total_profit_rs").text(formatMoney(ttl_profit));

          // rekap BPJS
          $("#totalPasienKlaimRJ").text(formatMoney(objData.totalPasienKlaimRJ));
          $("#totalRpKlaimInacbgsRJ").text(formatMoney(objData.totalRpKlaimInacbgsRJ));
          $("#totalRpKlaimRsRJ").text(formatMoney(objData.totalRpKlaimRsRJ));
          $("#totalRpBillRsKlaimRJ").text(formatMoney(objData.totalRpBillRsKlaimRJ));

          $("#totalPasienKlaimRI").text(formatMoney(objData.totalPasienKlaimRI));
          $("#totalRpKlaimInacbgsRI").text(formatMoney(objData.totalRpKlaimInacbgsRI));
          $("#totalRpKlaimRsRI").text(formatMoney(objData.totalRpKlaimRsRI));
          $("#totalRpBillRsKlaimRI").text(formatMoney(objData.totalRpBillRsKlaimRI));

          $("#totalPasienNoKlaimRJ").text(formatMoney(objData.totalPasienNoKlaimRJ));
          $("#totalRpNoKlaimInacbgsRJ").text(formatMoney(objData.totalRpNoKlaimInacbgsRJ));
          $("#totalRpNoKlaimRsRJ").text(formatMoney(objData.totalRpNoKlaimRsRJ));
          $("#totalRpBillRsNKlaimRJ").text(formatMoney(objData.totalRpBillRsNKlaimRJ));
          $("#totalPasienNoKlaimRI").text(formatMoney(objData.totalPasienNoKlaimRI));
          $("#totalRpNoKlaimInacbgsRI").text(formatMoney(objData.totalRpNoKlaimInacbgsRI));
          $("#totalRpNoKlaimRsRI").text(formatMoney(objData.totalRpNoKlaimRsRI));
          $("#totalRpBillRsNKlaimRI").text(formatMoney(objData.totalRpBillRsNKlaimRI));

          if(objData.start_date == objData.end_date){
            $('.tgl_filter').html(objData.start_date);
          }else{
            $('.tgl_filter').html(objData.start_date+' s.d '+objData.end_date);
          }

      },

    });

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
      "start_date" : $('#start_date').val(),
      "end_date" : $('#end_date').val(),
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
        window.open('eksekutif/Eks_profit_by_close_bill/export_excel?'+response.data+'','_blank'); 
      }
    })

  }

  function find_data_reload(result){
      oTable.ajax.url($('#datatable_rows').attr('base-url')+'?'+result.data).load();
      // $("html, body").animate({ scrollTop: "400px" });

  }

  function reload_table(){
    oTable.ajax.reload();
  }

  $('#btn_reset_data').click(function (e) {
      e.preventDefault();
      oTable.ajax.url($('#datatable_rows').attr('base-url')+'?flag='+$('#flag').val()).load();
      // $("html, body").animate({ scrollDown: "400px" });
      $('#form_search')[0].reset();
  });


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

    <form class="form-horizontal" method="post" id="form_search" action="templates/References/find_data">
        <!-- hidden form -->
        <span style="font-weight: bold">PENCARIAN DATA TRANSAKSI</span>
        <div class="form-group">
          <label class="control-label col-md-1">Tgl Transaksi</label>
          <div class="col-md-3">
            <div class="input-daterange input-group">
              <input type="text" class="input-xs date-picker" style="max-width: 100px" name="start_date" id="start_date" value="<?php echo date('Y-m-d')?>" data-date-format="yyyy-mm-dd">
              <span class="input-group-addon">
                s.d
              </span>
              <input type="text" class="input-xs date-picker" style="max-width: 100px; margin-left:0px !important" name="end_date" id="end_date" value="<?php echo date('Y-m-d')?>" data-date-format="yyyy-mm-dd">
            </div>
          </div>
          <div class="col-md-2" style="margin-left: -6%">
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
        <br>
        <p>
          <b>Keterangan :</b>
          <ul>
            <li>Data yang ditampilkan adalah data berdasarkan tanggal kunjungan (masuk/keluar) pasien RJ/RI</li>
            <li>Kolom <b>"Tipe"</b> merupakan seri kuitansi pasien ketika <i>closing billing</i> oleh petugas kasir</li>
            <li>Tipe <b>"ON GOING"</b> berarti pasien masih dalam proses pelayanan dan belum dilakukan <i>closing billing</i></li>
            <li>Tipe <b>"UNBILL"</b> berarti pasien belum dilakukan <i>closing billing</i> oleh petugas kasir atau ada rincian billing yang belum di<i>closing</i></li>
            <li>Tipe <b>"PB"</b> (Pembelian Bebas) yaitu pasien rawat jalan yang melakukan pembelian obat di apotik dengan resep luar</li>
            <li>Billing Apotik diluar dari Billing Resep PRB dan sudah dikurangi margin 33% dari total billing apotik dan margin 33% dimasukan kedalam Profit RS</li>
          </ul>
        </p>
        <p>
        <hr>

        <center><span style="font-weight: bold" id="title_1">REKAPITULASI PENDAPATAN BERDASARKAN DATA YANG DISUBMIT OLEH KASIR <br>PERIODE TANGGAL <span class="tgl_filter"></span></span></center>
        <br>

        <div class="col-md-12" style="padding-bottom: 20px">
          <table class="table">
            <tr>
              <th style="width: 25%; color: white; background: #d15b47">TOTAL PASIEN</th>
              <th style="width: 25%; color: white; background: #428bca">REVENUE</th>
              <th style="width: 25%; color: white; background: #ffb752">COST</th>
              <th style="width: 25%; color: white; background: #87b87f">PROFIT</th>
            </tr>
            <tr style="font-size: 20px; font-weight: bold;">
              <td align="right" style="background: #d15b4714"><span id="total_pasien_rs"></span></td>
              <td align="right" style="background: #428bca1f"><span id="total_revenue_rs"></span></td>
              <td align="right" style="background: #ffb7521c"><span id="total_cost_rs"></span></td>
              <td align="right" style="background: #87b87f17"><span id="total_profit_rs"></span></td>
            </tr>
          </table>
          <table class="table">
            <tr style="font-weight: bold; background: #c7cccb">
              <td rowspan="2" style="vertical-align: middle" width="30px" align="center">NO</td>
              <td rowspan="2" style="vertical-align: middle">KATEGORI</td>
              <td colspan="4" align="center">RAWAT JALAN</td>
              <td colspan="4" align="center">RAWAT INAP</td>
            </tr>
            <tr style="font-weight: bold; background: #c7cccb">
              <td width="100px" align="center">PASIEN</td>
              <td width="100px" align="right">REVENUE</td>
              <td width="100px" align="right">COST</td>
              <td width="100px" align="right">PROFIT</td>
              <td width="100px" align="center">PASIEN</td>
              <td width="100px" align="right">REVENUE</td>
              <td width="100px" align="right">COST</td>
              <td width="100px" align="right">PROFIT</td>
            </tr>
            <tr>
              <td>1.</td>
              <td>UMUM</td>
              <td align="center"><span id="um_ttl_pasien"></span></td>
              <td align="right"><span id="um_revenue"></span></td>
              <td align="right"><span id="um_cost"></span></td>
              <td align="right"><span id="um_profit"></span></td>
              <td align="center"><span id="ri_um_ttl_pasien"></span></td>
              <td align="right"><span id="ri_um_revenue"></span></td>
              <td align="right"><span id="ri_um_cost"></span></td>
              <td align="right"><span id="ri_um_profit"></span></td>
            </tr>
            <tr>
              <td>2.</td>
              <td>ASURANSI</td>
              <td align="center"><span id="asuransi_ttl_pasien"></span></td>
              <td align="right"><span id="asuransi_revenue"></span></td>
              <td align="right"><span id="asuransi_cost"></span></td>
              <td align="right"><span id="asuransi_profit"></span></td>
              <td align="center"><span id="ri_asuransi_ttl_pasien"></span></td>
              <td align="right"><span id="ri_asuransi_revenue"></span></td>
              <td align="right"><span id="ri_asuransi_cost"></span></td>
              <td align="right"><span id="ri_asuransi_profit"></span></td>
            </tr>
            <tr>
              <td>3.</td>
              <td>BPJS KESEHATAN</td>
              <td align="center"><span id="bpjs_ttl_pasien"></span></td>
              <td align="right"><span id="bpjs_revenue"></span></td>
              <td align="right"><span id="bpjs_cost"></span></td>
              <td align="right"><span id="bpjs_profit"></span></td>
              <td align="center"><span id="ri_bpjs_ttl_pasien"></span></td>
              <td align="right"><span id="ri_bpjs_revenue"></span></td>
              <td align="right"><span id="ri_bpjs_cost"></span></td>
              <td align="right"><span id="ri_bpjs_profit"></span></td>
            </tr>

            <tr>
              <td colspan="2" align="right"><b>GRAND TOTAL</b></td>
              <td align="center"><span id="all_ttl_pasien"></span></td>
              <td align="right"><span id="all_ttl_revenue"></span></td>
              <td align="right"><span id="all_ttl_cost"></span></td>
              <td align="right"><span id="all_ttl_profit"></span></td>
              <td align="center"><span id="all_ri_ttl_pasien"></span></td>
              <td align="right"><span id="all_ri_ttl_revenue"></span></td>
              <td align="right"><span id="all_ri_ttl_cost"></span></td>
              <td align="right"><span id="all_ri_ttl_profit"></span></td>
            </tr>
          </table>
          <span><b>Note :</b> <i>Rekap data yang ditampilkan diatas tidak termasuk pasien yang sedang <b>ON GOING/UNBILL/PB</b></i></span>
        </div>
        <br>
        <hr>
        <!-- rekap BPJS -->
         <div class="col-md-12" style="padding-bottom: 20px">
          <b>REKAP KLAIM BPJS PERIODE TANGGAL <span class="tgl_filter"></span></b>
          <table class="table">
            <tr style="font-weight: bold;">
              <td colspan="8" class="center" style="background: #019833; color: white">JUMLAH NAIK KLAIM NCC</td>
              <td colspan="4" class="center" style="background: #29428c; color: white">JUMLAH BELUM NAIK KLAIM</td>
            </tr>
            <tr style="font-weight: bold">
              <td colspan="4" class="center" style="background: #01983330">RAWAT JALAN</td>
              <td colspan="4" class="center" style="background: #01983330">RAWAT INAP</td>
              <td colspan="2" class="center" style="background: #29428c30">RAWAT JALAN</td>
              <td colspan="2" class="center" style="background: #29428c30">RAWAT INAP</td>
            </tr>
            <tr style="font-size: 11px">
              <td style="text-align: right">Pasien</td>
              <td style="text-align: right">Inacbgs</td>
              <td style="text-align: right">Klaim RS</td>
              <td style="text-align: right">Bill RS</td>
              <td style="text-align: right">Pasien</td>
              <td style="text-align: right">Inacbgs</td>
              <td style="text-align: right">Klaim RS</td>
              <td style="text-align: right">Bill RS</td>

              <td style="text-align: right">Pasien</td>
              <td style="text-align: right">Bill RS</td>
              <td style="text-align: right">Pasien</td>
              <td style="text-align: right">Bill RS</td>
            </tr>
            <tr style="font-weight: bold; font-size: 14px">
              <td align="right"><span id="totalPasienKlaimRJ"></span></td>
              <td align="right"><span id="totalRpKlaimInacbgsRJ"></span></td>
              <td align="right"><span id="totalRpKlaimRsRJ"></span></td>
              <td align="right"><span id="totalRpBillRsKlaimRJ"></span></td>
              <td align="right"><span id="totalPasienKlaimRI"></span></td>
              <td align="right"><span id="totalRpKlaimInacbgsRI"></span></td>
              <td align="right"><span id="totalRpKlaimRsRI"></span></td>
              <td align="right"><span id="totalRpBillRsKlaimRI"></span></td>

              <td align="right"><span id="totalPasienNoKlaimRJ"></span></td>
              <td align="right"><span id="totalRpBillRsNKlaimRJ"></span></td>
              <td align="right"><span id="totalPasienNoKlaimRI"></span></td>
              <td align="right"><span id="totalRpBillRsNKlaimRI"></span></td>
            </tr>
          </table>
        </div>

        <div class="col-md-12">
          <button type="button" name="btn-export" value="1" onclick="export_excel(1)" class="btn btn-xs btn-success">
            <i class="ace-icon fa fa-file-excel-o icon-on-right bigger-110"></i>
            Export Excel
          </button>
          <table class="table">
            <tr>
              <td align="right" style="font-size: 11px; width: 10%">
                Total Pasien<br>
                <h3 style="font-weight: bold; margin-top : 0px; font-size: 14px"><span id="total_pasien">0</span></h3>
              </td>
              <td align="right" style="font-size: 11px; width: 10%">
                Jasa Dr1<br>
                <h3 style="font-weight: bold; margin-top : 0px; font-size: 14px"><span id="ttl_bill_dr1">0</span>,-</h3>
              </td>
              <td align="right" style="font-size: 11px; width: 10%">
                Jasa Dr2<br>
                <h3 style="font-weight: bold; margin-top : 0px; font-size: 14px"><span id="ttl_bill_dr2">0</span>,-</h3>
              </td>
              <td align="right" style="font-size: 11px; width: 10%">
                BHP<br>
                <h3 style="font-weight: bold; margin-top : 0px; font-size: 14px"><span id="ttl_bhp">0</span>,-</h3>
              </td>
              <td align="right" style="font-size: 11px; width: 10%">
                Apotik<br>
                <h3 style="font-weight: bold; margin-top : 0px; font-size: 14px"><span id="ttl_bhp_apotik">0</span>,-</h3>
              </td>
              <td align="right" style="font-size: 11px; width: 10%">
                Kamar Rawat<br>
                <h3 style="font-weight: bold; margin-top : 0px; font-size: 14px"><span id="ttl_bill_kamar">0</span>,-</h3>
              </td>
              <td align="right" style="font-size: 11px; width: 10%">
                Kamar Operasi<br>
                <h3 style="font-weight: bold; margin-top : 0px; font-size: 14px"><span id="ttl_kamar_tindakan">0</span>,-</h3>
              </td>
              <td align="right" style="font-size: 11px; width: 10%">
                Alkes<br>
                <h3 style="font-weight: bold; margin-top : 0px; font-size: 14px"><span id="ttl_alat_rs">0</span>,-</h3>
              </td>
              <td align="right" style="font-size: 11px; width: 10%">
                Profit RS<br>
                <h3 style="font-weight: bold; margin-top : 0px; font-size: 14px"><span id="ttl_profit">0</span>,-</h3>
              </td>
              <td align="right" style="font-size: 11px; width: 10%">
                Total Billing<br>
                <h3 style="font-weight: bold; margin-top : 0px; font-size: 14px"><span id="ttl_total_bill">0</span>,-</h3>
              </td>
            </tr>
          </table>
        </div>

        <div class="col-md-12">
          <table id="datatable_rows" base-url="eksekutif/Eks_profit_by_close_bill/get_data" class="table table-bordered table-hover">
            <thead>
              <tr style="background-color:#428bca">
                <th class="center">No</th>
                <th width="90px" class="center">Tipe</th>
                <th width="120px">Tgl Masuk</th>
                <th width="120px">Tgl Keluar</th>
                <th width="100px">No MR</th>
                <th>Nama Pasien</th>
                <th>Dokter</th>
                <th>Unit/Bagian/Spesialis</th>
                <th>Kategori</th>
                <th>Penjamin</th>
                <th>No SEP</th>
                <th width="100px">Jasa Dr1</th>
                <th width="100px">Jasa Dr2</th>
                <th width="100px">BHP</th>
                <th width="100px">Apotik</th>
                <th width="130px">Kamar Rawat</th>
                <th width="130px">Kamar Operasi</th>
                <th width="100px">Alkes</th>
                <th width="100px">Profit</th>
                <th width="100px">Billing RS</th>
                <th width="100px">Tarif Inacbgs</th>
                <th width="100px">Tarif RS NCC</th>
              </tr>
            </thead>
          </table>
        </div>

    </form>
  </div><!-- /.col -->
</div><!-- /.row -->




