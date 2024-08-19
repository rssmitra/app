<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/custom/chart.js"></script>

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

  $('#btn_search_data').click(function (e) {
      e.preventDefault();
      // achtungShowLoader();  
      
      $('#contentPage').html('Loading...');
      $.getJSON('eksekutif/Eks_rm/get_content_page', $('#form_search').serialize(), function(response_data) {
        html = '';
        $.each(response_data, function (i, o) {
          html += '<div class="col-sm-'+o.col_size+'"><div id="'+o.nameid+'"></div></div>';
          if(o.style=='column'){
            GraphColumnStyle(o.mod, o.nameid, o.url);
          }
          if(o.style=='pie'){
            GraphPieStyle(o.mod, o.nameid, o.url);
          }
          if(o.style=='line'){
            GraphLineStyle(o.mod, o.nameid, o.url);
          }
          if(o.style=='table'){
            GraphTableStyle(o.mod, o.nameid, o.url);
          }

          });
          $('#contentPage').html(html);
      });
      // achtungHideLoader();
  });

  function show_detail(flag){
    preventDefault();
    $('#show_detail_by_click').load('eksekutif/Eks_rm/show_detail?flag='+flag+'&'+$('#form_search').serialize()+'');
  }

  function show_detail_unit(kode, flag){
    preventDefault();
    $('#show_detail_level_1').load('eksekutif/Eks_rm/show_detail_unit?kode='+kode+'&flag='+flag+'&'+$('#form_search').serialize()+'');
  }
  function show_detail_pasien(kode, flag){
    preventDefault();
    $('#show_detail_level_3').load('eksekutif/Eks_rm/show_detail_pasien?kode='+kode+'&flag='+flag+'&'+$('#form_search').serialize()+'');
  }

  function show_detail_jenis_tindakan(kode, flag){
    preventDefault();
    show_modal('eksekutif/Eks_rm/show_detail_jenis_tindakan?kode='+kode+'&flag='+flag+'&'+$('#form_search').serialize()+'', 'REKAPITULASI BERDASARKAN JENIS TINDAKAN TRANSAKSI');
    // $('#show_detail_level_3').load('eksekutif/Eks_rm/show_detail_jenis_tindakan?kode='+kode+'&flag='+flag+'&'+$('#form_search').serialize()+'');
  }

  function hide_detail(flag){
    preventDefault();
    $('#show_detail_by_click').html('');
  }

  function checked_checkbox(nameid){
    if (nameid == 'tbl-sensus-rawat-jalan') {
      if($('input[name='+nameid+']').is(':checked')){
          $('#div_bulan').show('fast');
      } else {
        $('#div_bulan').hide('fast');
      }
    }

    if (nameid == 'tbl-sensus-rawat-inap') {
      if($('input[name='+nameid+']').is(':checked')){
          $('#div_bulan_ri').show('fast');
      } else {
        $('#div_bulan_ri').hide('fast');
      }
    }
    return false;
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
  
    <form class="form-horizontal" method="post" id="form_search" action="eksekutif/Eks_rm/find_data">
      <!-- hidden form -->
      <input type="hidden" name="mod" value="poli">
      <div class="row">
      <div class="col-xs-12">
            <!-- <span style="font-size: 16px; font-weight: bold">DASHBOARD EKSEKUTIF</span><br>
            <p style="text-align:justify;">
                Fitur Laporan Kunjungan Pasien adalah rekapitulasi kunjungan pasien berdasarkan Tanggal Masuk pasien yang tercatat pada sistem sesuai dengan periode yang dipilih, dan juga dapat di filter berdasarkan jenis pelayanan atau jenis kunjungan pasien, dan jenis penjamin nya ataupun umum.<br>
                Jenis Laporan dapat dipilih sesuai dengan kebutuhannya dan visualisasi nya, ada yang berupa grafik dan juga data tabulasi. Perlu dicatat bahwa laporan kunjungan pasien bukan menunjukan jumlah pasien, karena setiap pasien dalam satu hari terdaftar dapat berkunjung ke poli spesialis, laboratorium, radiologi, dsb. sehingga tiap kunjungan ke unit dihitung masing-masing 1 kunjungan sehingga memungkinkan 1 pasien 3 kunjungan.
            </p>
            <div class="clearfix"></div>
            <br> -->

            <p><b>PARAMETER QUERY</b></p>

            <div class="form-group"  >
              <label class="control-label col-md-2">Jenis Pelayanan</label>
                <div class="col-md-2">
                  <select class="form-control" name="jenis_kunjungan">
                  <option value="all">Pilih Semua</option>
                    <option value="rj">Poliklinik Spesialis</option>
                    <option value="igd">IGD</option>
                    <option value="pm">Penunjang Medis</option>
                    <option value="ri">Rawat Inap</option>
                    <option value="fr">Resep Farmasi</option>
                  </select>
                </div>
                <label class="control-label col-md-1">Periode</label>
                <div class="col-md-2">
                  <div class="input-group">
                    <input class="form-control date-picker" name="from_tgl" id="from_tgl" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>"/>
                    <span class="input-group-addon">
                      <i class="fa fa-calendar bigger-110"></i>
                    </span>
                  </div>
                </div>

                <label class="control-label col-md-1">s/d Tgl</label>
                <div class="col-md-2">
                  <div class="input-group">
                    <input class="form-control date-picker" name="to_tgl" id="to_tgl" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>"/>
                    <span class="input-group-addon">
                      <i class="fa fa-calendar bigger-110"></i>
                    </span>
                  </div>
                </div>
                <div class="col-md-2 no-padding">
                  <a href="#" id="btn_search_data" class="btn btn-xs btn-primary">
                    <i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
                    Tampilkan Data
                  </a>
                </div>
            </div>
          </div>

      </div>
      <hr>
      <div id="contentPage"></div>   

    </form>

  </div><!-- /.col -->
</div><!-- /.row -->




