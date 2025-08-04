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

          var jasa_dokter = parseInt(objData.ttl_bill_dr1) + parseInt(objData.ttl_bill_dr2);

          $("#ttl_bill_dr1").text(formatMoney(jasa_dokter));
          $("#ttl_bill_dr2").text(formatMoney(objData.ttl_bill_dr2));
          $("#ttl_bhp").text(formatMoney(objData.ttl_bhp));
          $("#ttl_bhp_apotik").text(formatMoney(objData.ttl_bhp_apotik));
          $("#ttl_bill_kamar").text(formatMoney(objData.ttl_bill_kamar));
          $("#ttl_bill_lab").text(formatMoney(objData.ttl_bill_lab));
          $("#ttl_bill_rad").text(formatMoney(objData.ttl_bill_rad));
          $("#ttl_kamar_tindakan").text(formatMoney(objData.ttl_kamar_tindakan));
          $("#ttl_alat_rs").text(formatMoney(objData.ttl_alat_rs));
          $("#ttl_profit").text(formatMoney(objData.ttl_profit));

          
          // rekap rawat jalan by kategori
          $("#um_ttl_pasien").text(formatMoney(objData.um_ttl_pasien));
          $("#asuransi_ttl_pasien").text(formatMoney(objData.asuransi_ttl_pasien));
          $("#bpjs_ttl_pasien").text(formatMoney(objData.bpjs_ttl_pasien));
          $("#naker_ttl_pasien").text(formatMoney(objData.naker_ttl_pasien));

          $("#um_revenue").text(formatMoney(objData.um_ttl_revenue));
          $("#asuransi_revenue").text(formatMoney(objData.asuransi_ttl_revenue));
          $("#bpjs_revenue").text(formatMoney(objData.bpjs_ttl_revenue));
          $("#naker_revenue").text(formatMoney(objData.naker_ttl_revenue));

          $("#um_cost").text(formatMoney(objData.um_ttl_cost));
          $("#asuransi_cost").text(formatMoney(objData.asuransi_ttl_cost));
          $("#bpjs_cost").text(formatMoney(objData.bpjs_ttl_cost));
          $("#naker_cost").text(formatMoney(objData.naker_ttl_cost));

          $("#um_profit").text(formatMoney(objData.um_ttl_profit));
          $("#asuransi_profit").text(formatMoney(objData.asuransi_ttl_profit));
          $("#bpjs_profit").text(formatMoney(objData.bpjs_ttl_profit));
          $("#naker_profit").text(formatMoney(objData.naker_ttl_profit));

          // rekap rawat inap by kategori
          $("#ri_um_ttl_pasien").text(formatMoney(objData.ri_um_ttl_pasien));
          $("#ri_asuransi_ttl_pasien").text(formatMoney(objData.ri_asuransi_ttl_pasien));
          $("#ri_bpjs_ttl_pasien").text(formatMoney(objData.ri_bpjs_ttl_pasien));
          $("#ri_naker_ttl_pasien").text(formatMoney(objData.ri_naker_ttl_pasien));

          $("#ri_um_revenue").text(formatMoney(objData.ri_um_ttl_revenue));
          $("#ri_asuransi_revenue").text(formatMoney(objData.ri_asuransi_ttl_revenue));
          $("#ri_bpjs_revenue").text(formatMoney(objData.ri_bpjs_ttl_revenue));
          $("#ri_naker_revenue").text(formatMoney(objData.ri_naker_ttl_revenue));

          $("#ri_um_cost").text(formatMoney(objData.ri_um_ttl_cost));
          $("#ri_asuransi_cost").text(formatMoney(objData.ri_asuransi_ttl_cost));
          $("#ri_bpjs_cost").text(formatMoney(objData.ri_bpjs_ttl_cost));
          $("#ri_naker_cost").text(formatMoney(objData.ri_naker_ttl_cost));

          $("#ri_um_profit").text(formatMoney(objData.ri_um_ttl_profit));
          $("#ri_asuransi_profit").text(formatMoney(objData.ri_asuransi_ttl_profit));
          $("#ri_bpjs_profit").text(formatMoney(objData.ri_bpjs_ttl_profit));
          $("#ri_naker_profit").text(formatMoney(objData.ri_naker_ttl_profit));

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


          // rekap by tipe
          $("#jml_pasien_rj").text(formatMoney(objData.all_ttl_pasien));
          $("#ttl_billing_rj").text(formatMoney(objData.all_ttl_revenue));

          $("#jml_pasien_ri").text(formatMoney(objData.all_ri_ttl_pasien));
          $("#ttl_billing_ri").text(formatMoney(objData.all_ri_ttl_revenue));

          $("#jml_resep_prb").text(formatMoney(objData.jml_resep_prb));
          $("#ttl_billing_prb").text(formatMoney(objData.ttl_billing_prb));
          $("#ttl_prb").text(formatMoney(objData.ttl_billing_prb));
          $("#total_resep_prb_all").text(formatMoney(objData.jml_resep_prb));
          $("#total_revenue_resep_prb_all").text(formatMoney(objData.ttl_billing_prb));

          $("#jml_resep_pb").text(formatMoney(objData.jml_resep_pb));
          $("#ttl_billing_pb").text(formatMoney(objData.ttl_billing_pb));
          $("#ttl_pb").text(formatMoney(objData.ttl_billing_pb));
          $("#total_resep_pb_all").text(formatMoney(objData.jml_resep_pb));
          $("#total_revenue_resep_pb_all").text(formatMoney(objData.ttl_billing_pb));

          $("#on_going_pasien").text(formatMoney(objData.on_going_pasien));
          $("#on_going_revenue").text(formatMoney(objData.on_going_revenue));

          $("#unbill_pasien").text(formatMoney(objData.unbill_pasien));
          $("#unbill_revenue").text(formatMoney(objData.unbill_revenue));

          var total_ttl_bill = objData.ttl_total_bill + objData.ttl_billing_prb + objData.ttl_billing_pb;
          $("#ttl_total_bill").text(formatMoney(total_ttl_bill));


          var total_pasien_all = parseInt(objData.all_ttl_pasien) + parseInt(objData.all_ri_ttl_pasien) + parseInt(objData.on_going_pasien) + parseInt(objData.unbill_pasien);
          $("#total_pasien_all").text(formatMoney(total_pasien_all));

          var total_revenue_pasien_all = parseInt(objData.all_ttl_revenue) + parseInt(objData.all_ri_ttl_revenue) + parseInt(objData.on_going_revenue) + parseInt(objData.unbill_revenue);
          $("#total_revenue_pasien_all").text(formatMoney(total_revenue_pasien_all));

          var total_revenue_pasien_all_type = parseInt(objData.all_ttl_revenue) + parseInt(objData.all_ri_ttl_revenue) + parseInt(objData.ttl_billing_prb) + parseInt(objData.ttl_billing_pb) + parseInt(objData.on_going_revenue) + parseInt(objData.unbill_revenue);
          $("#total_revenue_pasien_all_type").text(formatMoney(total_revenue_pasien_all_type));

          // rekap by instalasi
          $("#instalasi_rj_ttl_pasien").text(formatMoney(objData.instalasi_rj_ttl_pasien));
          $("#instalasi_rj_ttl_revenue").text(formatMoney(objData.instalasi_rj_ttl_revenue));
          $("#hd_ttl_pasien").text(formatMoney(objData.hd_ttl_pasien));
          $("#hd_ttl_revenue").text(formatMoney(objData.hd_ttl_revenue));
          $("#mcu_ttl_pasien").text(formatMoney(objData.mcu_ttl_pasien));
          $("#mcu_ttl_revenue").text(formatMoney(objData.mcu_ttl_revenue));
          $("#ttl_instalasi_ri").text(formatMoney(objData.all_ri_ttl_pasien));
          $("#ttl_bill_instalasi_ri").text(formatMoney(objData.all_ri_ttl_revenue));
          $("#igd_ttl_pasien").text(formatMoney(objData.igd_ttl_pasien));
          $("#igd_ttl_revenue").text(formatMoney(objData.igd_ttl_revenue));
          $("#lab_ttl_pasien").text(formatMoney(objData.lab_ttl_pasien));
          $("#lab_ttl_revenue").text(formatMoney(objData.lab_ttl_revenue));
          $("#rad_ttl_pasien").text(formatMoney(objData.rad_ttl_pasien));
          $("#rad_ttl_revenue").text(formatMoney(objData.rad_ttl_revenue));
          $("#fisio_ttl_pasien").text(formatMoney(objData.fisio_ttl_pasien));
          $("#fisio_ttl_revenue").text(formatMoney(objData.fisio_ttl_revenue));

          var ttl_instalasi_pasien = parseInt(objData.instalasi_rj_ttl_pasien) + parseInt(objData.all_ri_ttl_pasien) + parseInt(objData.igd_ttl_pasien) + parseInt(objData.lab_ttl_pasien) + parseInt(objData.rad_ttl_pasien) + parseInt(objData.fisio_ttl_pasien) + parseInt(objData.mcu_ttl_pasien) + parseInt(objData.hd_ttl_pasien);
          
          var ttl_instalasi_revenue = parseInt(objData.instalasi_rj_ttl_revenue) + parseInt(objData.all_ri_ttl_revenue) + parseInt(objData.igd_ttl_revenue) + parseInt(objData.lab_ttl_revenue) + parseInt(objData.rad_ttl_revenue) + parseInt(objData.fisio_ttl_revenue) + parseInt(objData.mcu_ttl_revenue) + parseInt(objData.hd_ttl_revenue);
          $("#ttl_instalasi_pasien").text(formatMoney(ttl_instalasi_pasien));
          $("#ttl_instalasi_revenue").text(formatMoney(ttl_instalasi_revenue));

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
              <input type="text" class="input-xs date-picker" name="start_date" id="start_date" value="<?php echo date('Y-m-d')?>" data-date-format="yyyy-mm-dd">
              <span class="input-group-addon">
                s.d
              </span>
              <input type="text" class="input-xs date-picker" style="margin-left:0px !important" name="end_date" id="end_date" value="<?php echo date('Y-m-d')?>" data-date-format="yyyy-mm-dd">
            </div>
          </div>
          <div class="col-md-2 no-padding">
            <select name="kategori" id="kategori" class="input-xs form-control">
              <option value="">-- Pilih Tipe --</option>
              <option value="rj">RJ</option>
              <option value="ri">RI</option>
              <option value="og">ON GOING</option>
              <option value="ub">UNBILL</option>
              <option value="pb">PEMBELIAN BEBAS</option>
            </select>    
          </div> 
          <div class="col-md-4" style="margin-left: 0%">
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
            <li>Data yang ditampilkan adalah data berdasarkan Tanggal Kunjungan (masuk/keluar) Pasien RJ/RI dan atau Tanggal Transaksi</li>
            <li>Kolom <b>"Tipe"</b> merupakan seri kuitansi pasien ketika <i>closing billing</i> oleh petugas kasir</li>
            <li>Tipe <b>"ON GOING"</b> berarti pasien masih dalam proses pelayanan dan belum dilakukan <i>closing billing</i></li>
            <li>Tipe <b>"UNBILL"</b> berarti pasien belum dilakukan <i>closing billing</i> oleh petugas kasir atau ada rincian billing yang belum di<i>closing</i></li>
            <li>Tipe <b>"PB"</b> (Pembelian Bebas) yaitu pasien rawat jalan yang melakukan pembelian obat di apotik dengan resep luar</li>
            <li>Tipe <b>"PRB"</b> (Pasien Rujuk Balik) yaitu Resep Pasien Rujuk Balik (PRB) yang diklaim oleh farmasi setiap bulannya</li>
            <li>Billing Apotik diluar dari Billing Resep PRB dan sudah dikurangi margin 33% dari total billing apotik dan margin 33% dimasukan kedalam Profit RS</li>
            <li>Harga Satuan pada Resep PRB masih menggunakan Harga Satuan Penjualan pada Sistem</li>
          </ul>
        </p>
        <p>
        <hr>

        <center><span style="font-weight: bold; font-size: 18px" id="title_1">REKAPITULASI PENDAPATAN RUMAH SAKIT <br>PERIODE TANGGAL <span class="tgl_filter"></span></span></center>
        <br>
        <div class="col-md-12">
          <h3 class="header smaller lighter blue padding-10">
            REKAP BERDASARKAN TIPE PELAYANAN 
          </h3>
          <div class="col-md-6">
            <table class="table" style="width: 100%">
              <tr style="background: #428bca; color: white; font-weight: bold;">
                <td width="30px">No</td>
                <td>Tipe Pelayanan</td>
                <td width="100px" align="center">Qty</td>
                <td width="150px" align="right">Total (Rp.)</td>
              </tr>
              <tr>
                <td align="center">1</td>
                <td>Rawat Jalan (RJ)</td>
                <td align="center"><span id="jml_pasien_rj">0</span></td>
                <td align="right"><span id="ttl_billing_rj">0</span></td>
              </tr>
              <tr>
                <td align="center">2</td>
                <td>Rawat Inap (RI)</td>
                <td align="center"><span id="jml_pasien_ri">0</span></td>
                <td align="right"><span id="ttl_billing_ri">0</span></td>
              </tr>

              <tr>
                <td align="center">3</td>
                <td>Resep PRB (Pasien Rujuk Balik)</td>
                <td align="center"><span id="jml_resep_prb">0</span></td>
                <td align="right"><span id="ttl_billing_prb">0</span></td>
              </tr>

              <tr>
                <td align="center">4</td>
                <td>Pembelian Bebas/Karyawan (PB)</td>
                <td align="center"><span id="jml_resep_pb">0</span></td>
                <td align="right"><span id="ttl_billing_pb">0</span></td>
              </tr>

              <tr>
                <td align="center">5</td>
                <td>Sedang Berlangsung (On Going)</td>
                <td align="center"><span id="on_going_pasien">0</span></td>
                <td align="right"><span id="on_going_revenue">0</span></td>
              </tr>

              <tr>
                <td align="center">6</td>
                <td>Belum disubmit kasir (UNBILL)</td>
                <td align="center"><span id="unbill_pasien">0</span></td>
                <td align="right"><span id="unbill_revenue">0</span></td>
              </tr>
            </table>
          </div>
          <div class="col-md-6">
            <table class="table" style="width: 100%">
              <tr style="background: #428bca; color: white; font-weight: bold;">
                <td width="33%">Total Pasien</td>
                <td width="33%">Total Resep PRB</td>
                <td width="33%">Total Pembelian Bebas</td>
              </tr>
              <tr>
                <td align="right">
                  <span id="total_pasien_all">-</span> Pasien<br>
                  <span id="total_revenue_pasien_all" style="font-weight: bold; font-size: 16px">-</span>
                </td>
                <td align="right">
                  <span id="total_resep_prb_all">-</span> Resep <br>
                  <span id="total_revenue_resep_prb_all" style="font-weight: bold; font-size: 16px">-</span>
                </td>
                <td align="right">
                  <span id="total_resep_pb_all">-</span> Resep <br>
                  <span id="total_revenue_resep_pb_all" style="font-weight: bold; font-size: 16px">-</span>
                </td>
              </tr>
              <tr>
                <td colspan="3" align="center" style="background: #428bca; color: white; font-weight: bold;">TOTAL REVENUE KESELURUHAN</td>
              </tr>
              <tr>
                <td align="center" valign="middle" colspan="3">
                  <span id="total_revenue_pasien_all_type" style="font-weight: bold; font-size: 26px">0</span>
                </td>
              </tr>
            </table>
            
          </div>
        </div>

        <div class="col-md-12" style="padding-bottom: 20px">

          <h3 class="header smaller lighter blue padding-10">
            REKAP PASIEN RJ/RI BERDASARKAN INSTALASI & PENJAMIN
          </h3>
          Rekap data yang ditampilkan dibawah ini hanya pelayanan <b>RJ/RI</b> tidak termasuk pasien yang sedang <b>ONGOING/UNBILL/PB/PRB</b>

          <table class="table" style="width:100% !important">
            <tr>
              <th style="width: 25%; color: white; background: #d15b47">TOTAL PASIEN</th>
              <th style="width: 25%; color: white; background: #428bca">REVENUE</th>
              <!-- <th style="width: 25%; color: white; background: #ffb752">COST</th>
              <th style="width: 25%; color: white; background: #87b87f">PROFIT</th> -->
            </tr>
            <tr style="font-size: 20px; font-weight: bold;">
              <td align="right" style="background: #d15b4714"><span id="total_pasien_rs"></span></td>
              <td align="right" style="background: #428bca1f"><span id="total_revenue_rs"></span></td>
              <!-- <td align="right" style="background: #ffb7521c"><span id="total_cost_rs"></span></td>
              <td align="right" style="background: #87b87f17"><span id="total_profit_rs"></span></td> -->
            </tr>
          </table>
          <div class="col-md-6 no-padding">
            <h4 class="header smaller lighter black padding-10">
              Rekap Pasien Berdasarkan Instalasi
            </h4>
          
            <table class="table">
              <tr style="font-weight: bold; background: #c7cccb">
                <td rowspan="2" style="vertical-align: middle" width="30px" align="center">NO</td>
                <td rowspan="2" style="vertical-align: middle">INSTALASI</td>
                <td align="center" width="150px" colspan="2">REKAPITULASI</td>
              </tr>
              <tr style="font-weight: bold; background: #c7cccb">
                <td align="center" width="150px">JUMLAH PASIEN</td>
                <td align="center" width="150px">TOTAL REVENUE</td>
              </tr>
              <tr>
                <td style="vertical-align: middle" width="30px" align="center">1</td>
                <td style="vertical-align: middle">Poliklinik Rawat Jalan</td>
                <td align="right" width="150px"><span id="instalasi_rj_ttl_pasien">-</td>
                <td align="right" width="150px"><span id="instalasi_rj_ttl_revenue">-</td>
              </tr>
              <tr>
                <td style="vertical-align: middle" width="30px" align="center">2</td>
                <td style="vertical-align: middle">Rawat Inap</td>
                <td align="right" width="150px"><span id="ttl_instalasi_ri">-</td>
                <td align="right" width="150px"><span id="ttl_bill_instalasi_ri">-</td>
              </tr>
              <tr>
                <td style="vertical-align: middle" width="30px" align="center">3</td>
                <td style="vertical-align: middle">Gawat Darurat (IGD)</td>
                <td align="right" width="150px"><span id="igd_ttl_pasien">-</td>
                <td align="right" width="150px"><span id="igd_ttl_revenue">-</td>
              </tr>
              <tr>
                <td style="vertical-align: middle" width="30px" align="center">4</td>
                <td style="vertical-align: middle">Penunjang Medis</td>
                <td align="right" width="150px"><span id="ttl_instalasi_pm">-</td>
                <td align="right" width="150px"><span id="ttl_bill_instalasi_pm">-</td>
              </tr>
               <tr>
                <td style="vertical-align: middle" width="30px" align="center"></td>
                <td style="vertical-align: middle">Laboratorium</td>
                <td align="right" width="150px"><span id="lab_ttl_pasien">-</td>
                <td align="right" width="150px"><span id="lab_ttl_revenue">-</td>
              </tr>
              <tr>
                <td style="vertical-align: middle" width="30px" align="center"></td>
                <td style="vertical-align: middle">Radiologi</td>
                <td align="right" width="150px"><span id="rad_ttl_pasien">-</td>
                <td align="right" width="150px"><span id="rad_ttl_revenue">-</td>
              </tr>
              <tr>
                <td style="vertical-align: middle" width="30px" align="center"></td>
                <td style="vertical-align: middle">Fisioterapi</td>
                <td align="right" width="150px"><span id="fisio_ttl_pasien">-</td>
                <td align="right" width="150px"><span id="fisio_ttl_revenue">-</td>
              </tr>
              <tr>
                <td style="vertical-align: middle" width="30px" align="center">5</td>
                <td style="vertical-align: middle">Hemodialisa</td>
                <td align="right" width="150px"><span id="hd_ttl_pasien">-</td>
                <td align="right" width="150px"><span id="hd_ttl_revenue">-</td>
              </tr>
              <tr>
                <td style="vertical-align: middle" width="30px" align="center">6</td>
                <td style="vertical-align: middle">Medical Checkup</td>
                <td align="right" width="150px"><span id="mcu_ttl_pasien">-</td>
                <td align="right" width="150px"><span id="mcu_ttl_revenue">-</td>
              </tr>

              <tr>
                <td style="vertical-align: middle" colspan="2" align="right">TOTAL</td>
                <td align="right" width="150px"><span id="ttl_instalasi_pasien">-</td>
                <td align="right" width="150px"><span id="ttl_instalasi_revenue">-</td>
              </tr>
              
            </table>
          </div>
          <div class="col-md-6 no-padding" style="padding-left: 5px !important">
            <h4 class="header smaller lighter black padding-10">
              Rekap Berdasarkan Penjamin Pasien
            </h4>
            <table class="table" >
              <tr style="font-weight: bold; background: #c7cccb">
                <td rowspan="2" style="vertical-align: middle" width="30px" align="center">NO</td>
                <td rowspan="2" style="vertical-align: middle">KATEGORI</td>
                <td colspan="2" align="center">RAWAT JALAN</td>
                <td colspan="2" align="center">RAWAT INAP</td>
              </tr>
              <tr style="font-weight: bold; background: #c7cccb">
                <td width="100px" align="center">PASIEN</td>
                <!-- <td width="100px" align="right">COST</td>
                <td width="100px" align="right">PROFIT</td> -->
                <td width="100px" align="right">REVENUE</td>
                <td width="100px" align="center">PASIEN</td>
                <!-- <td width="100px" align="right">COST</td>
                <td width="100px" align="right">PROFIT</td> -->
                <td width="100px" align="right">REVENUE</td>
              </tr>
              <tr>
                <td align="center">1.</td>
                <td>UMUM</td>
                <td align="center"><span id="um_ttl_pasien"></span></td>
                <!-- <td align="right"><span id="um_cost"></span></td>
                <td align="right"><span id="um_profit"></span></td> -->
                <td align="right"><span id="um_revenue"></span></td>
                <td align="center"><span id="ri_um_ttl_pasien"></span></td>
                <!-- <td align="right"><span id="ri_um_cost"></span></td>
                <td align="right"><span id="ri_um_profit"></span></td> -->
                <td align="right"><span id="ri_um_revenue"></span></td>
              </tr>
              <tr>
                <td align="center">2.</td>
                <td>ASURANSI</td>
                <td align="center"><span id="asuransi_ttl_pasien"></span></td>
                <!-- <td align="right"><span id="asuransi_cost"></span></td>
                <td align="right"><span id="asuransi_profit"></span></td> -->
                <td align="right"><span id="asuransi_revenue"></span></td>
                <td align="center"><span id="ri_asuransi_ttl_pasien"></span></td>
                <!-- <td align="right"><span id="ri_asuransi_cost"></span></td>
                <td align="right"><span id="ri_asuransi_profit"></span></td> -->
                <td align="right"><span id="ri_asuransi_revenue"></span></td>
              </tr>
              <tr>
                <td align="center">3.</td>
                <td>BPJS KESEHATAN</td>
                <td align="center"><span id="bpjs_ttl_pasien"></span></td>
                <!-- <td align="right"><span id="bpjs_cost"></span></td>
                <td align="right"><span id="bpjs_profit"></span></td> -->
                <td align="right"><span id="bpjs_revenue"></span></td>
                <td align="center"><span id="ri_bpjs_ttl_pasien"></span></td>
                <!-- <td align="right"><span id="ri_bpjs_cost"></span></td>
                <td align="right"><span id="ri_bpjs_profit"></span></td> -->
                <td align="right"><span id="ri_bpjs_revenue"></span></td>
              </tr>

              <tr>
                <td align="center">4.</td>
                <td>BPJS KETENAGAKERJAAN</td>
                <td align="center"><span id="naker_ttl_pasien"></span></td>
                <!-- <td align="right"><span id="naker_cost"></span></td>
                <td align="right"><span id="naker_profit"></span></td> -->
                <td align="right"><span id="naker_revenue"></span></td>
                <td align="center"><span id="ri_naker_ttl_pasien"></span></td>
                <!-- <td align="right"><span id="ri_naker_cost"></span></td>
                <td align="right"><span id="ri_naker_profit"></span></td> -->
                <td align="right"><span id="ri_naker_revenue"></span></td>
              </tr>

              <tr>
                <td colspan="2" align="right"><b>GRAND TOTAL</b></td>
                <td align="center"><span id="all_ttl_pasien"></span></td>
                <!-- <td align="right"><span id="all_ttl_cost"></span></td>
                <td align="right"><span id="all_ttl_profit"></span></td> -->
                <td align="right"><span id="all_ttl_revenue"></span></td>
                <td align="center"><span id="all_ri_ttl_pasien"></span></td>
                <!-- <td align="right"><span id="all_ri_ttl_cost"></span></td>
                <td align="right"><span id="all_ri_ttl_profit"></span></td> -->
                <td align="right"><span id="all_ri_ttl_revenue"></span></td>
              </tr>
            </table>
          </div>
        </div>
        <br>
        <hr>
        <!-- rekap BPJS -->
         <div class="col-md-12" style="padding-bottom: 20px">
          <h3 class="header smaller lighter blue padding-10">
            REKAP KLAIM BPJS PERIODE TANGGAL <span class="tgl_filter"></span>
          </h3>
          Data yang ditampilkan adalah data klaim BPJS yang sudah dilakukan <i>closing billing</i> oleh petugas kasir, baik klaim yang sudah naik maupun yang belum naik.
          
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
          <h3 class="header smaller lighter blue padding-10">
            LIST DATA TRANSAKSI PERIODE TANGGAL <span class="tgl_filter"></span>
          </h3>
          Rekap data berdasarkan kategori.
          <table class="table">
            <tr>
              <td align="right" style="font-size: 11px; width: 10%">
                Total Pasien<br>
                <h3 style="font-weight: bold; margin-top : 0px; font-size: 14px"><span id="total_pasien">0</span></h3>
              </td>
              <td align="right" style="font-size: 11px; width: 10%">
                Jasa Dokter<br>
                <h3 style="font-weight: bold; margin-top : 0px; font-size: 14px"><span id="ttl_bill_dr1">0</span>,-</h3>
              </td>
              <!-- <td align="right" style="font-size: 11px; width: 10%">
                Jasa Dr2<br>
                <h3 style="font-weight: bold; margin-top : 0px; font-size: 14px"><span id="ttl_bill_dr2">0</span>,-</h3>
              </td> -->
              <td align="right" style="font-size: 11px; width: 10%">
                BHP<br>
                <h3 style="font-weight: bold; margin-top : 0px; font-size: 14px"><span id="ttl_bhp">0</span>,-</h3>
              </td>
              <td align="right" style="font-size: 11px; width: 10%">
                Apotik<br>
                <h3 style="font-weight: bold; margin-top : 0px; font-size: 14px"><span id="ttl_bhp_apotik">0</span>,-</h3>
              </td>
              <td align="right" style="font-size: 11px; width: 10%">
                PRB<br>
                <h3 style="font-weight: bold; margin-top : 0px; font-size: 14px"><span id="ttl_prb">0</span>,-</h3>
              </td>
              <td align="right" style="font-size: 11px; width: 10%">
                PB<br>
                <h3 style="font-weight: bold; margin-top : 0px; font-size: 14px"><span id="ttl_pb">0</span>,-</h3>
              </td>
              <td align="right" style="font-size: 11px; width: 10%">
                Lab<br>
                <h3 style="font-weight: bold; margin-top : 0px; font-size: 14px"><span id="ttl_bill_lab">0</span>,-</h3>
              </td>
              <td align="right" style="font-size: 11px; width: 10%">
                Radiologi<br>
                <h3 style="font-weight: bold; margin-top : 0px; font-size: 14px"><span id="ttl_bill_rad">0</span>,-</h3>
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
          <button type="button" name="btn-export" value="1" onclick="export_excel(1)" class="btn btn-xs btn-success">
            <i class="ace-icon fa fa-file-excel-o icon-on-right bigger-110"></i>
            Export Excel
          </button>
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
                <th width="100px">Jasa Dokter</th>
                <th width="100px">BHP</th>
                <th width="100px">Apotik</th>
                <th width="100px">Lab</th>
                <th width="100px">Radiologi</th>
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




