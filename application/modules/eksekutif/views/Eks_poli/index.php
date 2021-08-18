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
      achtungShowLoader();  
      

      $.getJSON('eksekutif/Eks_poli/get_content_page', $('#form_search').serialize(), function(response_data) {
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
      achtungHideLoader();
  });

  function show_detail(flag){
    preventDefault();
    $('#show_detail_by_click').load('eksekutif/Eks_poli/show_detail?flag='+flag+'&'+$('#form_search').serialize()+'');
  }

  function show_detail_unit(kode, flag){
    preventDefault();
    $('#show_detail_level_1').load('eksekutif/Eks_poli/show_detail_unit?kode='+kode+'&flag='+flag+'&'+$('#form_search').serialize()+'');
  }
  function show_detail_pasien(kode, flag){
    preventDefault();
    $('#show_detail_level_2').load('eksekutif/Eks_poli/show_detail_pasien?kode='+kode+'&flag='+flag+'&'+$('#form_search').serialize()+'');
  }

  function hide_detail(flag){
    preventDefault();
    $('#show_detail_by_click').html('');
  }

  function checked_checkbox(nameid){
    if (nameid == 'tbl-resume-kunjungan-harian') {
      if($('input[name='+nameid+']').is(':checked')){
          $('#div_bulan').show('fast');
      } else {
        $('#div_bulan').hide('fast');
      }
    }

    if (nameid == 'graph-line-1') {
      if($('input[name='+nameid+']').is(':checked')){
          $('#div_tahun_graph-line-1').show('fast');
      } else {
        $('#div_tahun_graph-line-1').hide('fast');
      }
    }

    // if (nameid == 'tbl-resume-kunjungan') {
    //   if($('input[name='+nameid+']').is(':checked')){
    //       $('#div_form_tanggal').show('fast');
    //   } else {
    //     $('#div_form_tanggal').hide('fast');
    //   }
    // }
    
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
  
    <form class="form-horizontal" method="post" id="form_search" action="eksekutif/Eks_poli/find_data">
      <!-- hidden form -->
      <input type="hidden" name="mod" value="poli">
      <div class="row">
          <div class="col-xs-10">
            <span style="font-size: 16px; font-weight: bold">DASHBOARD EKSEKUTIF</span><br>
            Laporan Kunjungan Pasien dan Pendapatan RS berdasarkan Kunjungan Pasien
            <div class="clearfix"></div>
            <br>

            <p><b>PARAMETER QUERY</b></p>

            <div class="form-group" id="jenis_asuransi" >
              <label class="control-label col-md-2">Jenis Kunjungan</label>
                <div class="col-md-2">
                  <select class="form-control" name="jenis_kunjungan">
                  <option value="all">Pilih Semua</option>
                    <option value="rj">Rawat Jalan</option>
                    <option value="ri">Rawat Inap</option>
                  </select>
                </div>
            </div>

            <div class="form-group" id="jenis_asuransi" >
              <label class="control-label col-md-2">Penjamin Pasien</label>
                <div class="col-md-2">
                  <select class="form-control" name="penjamin">
                    <option value="all">Pilih Semua</option>
                    <option value="bpjs">BPJS Kesehatan</option>
                    <option value="asuransi">Asuransi Lainnya</option>
                    <option value="umum">Umum</option>
                  </select>
                </div>
            </div>

            <div class="form-group" id="div_form_tanggal">
              <label class="control-label col-md-2">Periode Tanggal</label>
                <div class="col-md-2">
                  <div class="input-group">
                    <input class="form-control date-picker" name="from_tgl" id="from_tgl" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>"/>
                    <span class="input-group-addon">
                      <i class="fa fa-calendar bigger-110"></i>
                    </span>
                  </div>
                </div>

                <label class="control-label col-md-1" style="margin-left: 1%">s/d Tgl</label>
                <div class="col-md-2">
                  <div class="input-group">
                    <input class="form-control date-picker" name="to_tgl" id="to_tgl" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>"/>
                    <span class="input-group-addon">
                      <i class="fa fa-calendar bigger-110"></i>
                    </span>
                  </div>
                </div>
            </div>

            <br>
            <div class="col-md-12 no-padding">
              <p><b>JENIS LAPORAN</b></p>
              <label>
                <input name="tbl-resume-kunjungan" type="checkbox" onclick="checked_checkbox(this.name)" class="ace" value="1" checked readonly="true">
                <span class="lbl"> Rekap Total dan Rupiah Kunjungan Pasien per Periode dan Harian, Bulanan dan Tahun Berjalan</span>
              </label>
              <br>
              <label>
                <input name="graph-line-1" type="checkbox" onclick="checked_checkbox(this.name)" class="ace" value="1">
                <span class="lbl"> Grafik Line Kunjungan Pasien per Tahun</span>
              </label>
              <div class="form-group" id="div_tahun_graph-line-1" style="display: none">
                  <label class="control-label col-md-1">Tahun</label>
                  <div class="col-md-2">
                    <?php echo $this->master->get_tahun(date('Y'),'tahun_graph_line_1','tahun_graph_line_1','form-control','','')?>
                  </div>
              </div>
              <br>
              <!-- <label>
                <input name="graph-pie-1" type="checkbox" onclick="checked_checkbox(this.name)" class="ace" value="1">
                <span class="lbl"> Persentase Jumlah Pengunjung Berdasarkan Instalasi</span>
              </label>
              <br>
              <label>
                <input name="graph-table-1" type="checkbox" onclick="checked_checkbox(this.name)" class="ace" value="1">
                <span class="lbl"> 10 Klinik Terbanyak Dikunjungi Pasien Hari Ini</span>
              </label>
              <br> -->
              <label>
                <input name="tbl-resume-kunjungan-harian" type="checkbox" onclick="checked_checkbox(this.name)" class="ace" value="1">
                <span class="lbl"> Rekapitulasi Kunjungan Detail Harian Berdasarkan Unit/Bagian per Bulan</span>
              </label>
              <div class="form-group" id="div_bulan" style="display: none">
                  <label class="control-label col-md-1">Bulan</label>
                  <div class="col-md-2">
                    <?php echo $this->master->get_bulan(date('m'),'bulan','bulan','form-control','','')?>
                  </div>
                  <label class="control-label col-md-1">Tahun</label>
                  <div class="col-md-2">
                    <?php echo $this->master->get_tahun(date('Y'),'tahun','tahun','form-control','','')?>
                  </div>
              </div>
              <br>
              <label>
                <input name="tbl-resume-kunjungan-pasien" type="checkbox" onclick="checked_checkbox(this.name)" class="ace" value="1">
                <span class="lbl"> Rekapitulasi Kunjungan Berdasarkan Nama Pasien per Periode</span>
              </label>
              <br>
              <label>
                <input name="tbl-resume-kunjungan-pasien-asuransi" type="checkbox" onclick="checked_checkbox(this.name)" class="ace" value="1">
                <span class="lbl"> Rekapitulasi Kunjungan Berdasarkan Asuransi per Periode</span>
              </label>

            </div>
            <br>
            <hr class="separator">
            <div class="form-group" style="padding-top: 10px">
                <div class="col-md-10 no-padding">
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




