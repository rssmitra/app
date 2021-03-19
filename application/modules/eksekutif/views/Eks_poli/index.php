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
      $.getJSON('eksekutif/Eks_poli/get_content_page', {mod: 'poli', from_tgl: $('#from_tgl').val(), to_tgl : $('#to_tgl').val() }, function(response_data) {
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
  });

  function show_detail(flag){
    preventDefault();
    $('#show_detail_by_click').load('eksekutif/Eks_poli/show_detail?flag='+flag+'&from_tgl='+$('#from_tgl').val()+'&to_tgl='+$('#to_tgl').val()+'');
  }

  function hide_detail(flag){
    preventDefault();
    $('#show_detail_by_click').html('');
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
      <div class="row">
          <div class="col-xs-10">
            <span style="font-size: 16px; font-weight: bold">DASHBOARD EKSEKUTIF</span><br>
            Laporan Kunjungan Pasien dan Pendapatan Global
            <div class="clearfix"></div>
            <br>

            <div class="form-group" id="form_tanggal" >
              <label class="control-label col-md-1">Periode</label>
                <div class="col-md-2">
                  <div class="input-group">
                    <input class="form-control date-picker" name="from_tgl" id="from_tgl" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>"/>
                    <span class="input-group-addon">
                      <i class="fa fa-calendar bigger-110"></i>
                    </span>
                  </div>
                </div>

                <label class="control-label col-md-1" style="margin-left: 2%">s/d Tgl</label>
                <div class="col-md-2">
                  <div class="input-group">
                    <input class="form-control date-picker" name="to_tgl" id="to_tgl" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>"/>
                    <span class="input-group-addon">
                      <i class="fa fa-calendar bigger-110"></i>
                    </span>
                  </div>
                </div>
                <div class="col-md-2" style="margin-left: 0.8%">
                  <a href="#" id="btn_search_data" onclick="pageLoadEksekutif()" class="btn btn-xs btn-primary">
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




