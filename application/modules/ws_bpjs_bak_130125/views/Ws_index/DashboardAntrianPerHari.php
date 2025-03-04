<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<script src="<?php echo base_url()?>assets/js/custom/chart.js"></script>

<script>
jQuery(function($) {

  $('.date-picker').datepicker({
    autoclose: true,
    todayHighlight: true,
  })
  //show datepicker when clicking on the icon
  .next().on(ace.click_event, function(){
    $(this).prev().focus();
  });

  $('#btn_search_data').click(function (e) {
      e.preventDefault();
      // achtungShowLoader();  
      
      $('#contentPage').html('Loading...');
      $.getJSON('ws_bpjs/Ws_index/getDashboardAntrian', $('#form_search').serialize(), function(response_data) {
        html = '';
        $.each(response_data, function (i, o) {
          html += '<div class="col-sm-'+o.col_size+'"><div id="'+o.nameid+'"></div></div>';
          if(o.style=='custom-dashboard-antrol'){
            GraphTableStyle(o.mod, o.nameid, o.url);
          }
        });
        $('#contentPage').html(html);
      });
      // achtungHideLoader();
  });

  $('select[name="jenis_laporan"]').change(function () {
    if($(this).val() == 2){
      $('#divBln').show();
      $('#divTgl').hide();
    }else{
      $('#divBln').hide();
      $('#divTgl').show();
    }
  })


});  

</script>

<div class="row">
  <div class="col-xs-12">

    <div class="page-header">
      <h1>
        <?php echo $title?>
        <small>
          <i class="ace-icon fa fa-angle-double-right"></i>
          <?php echo isset($breadcrumbs)?$breadcrumbs:''?>
        </small>
      </h1>
    </div><!-- /.page-header -->

    <form class="form-horizontal" method="post" id="form_search" action="ws_bpjs/Ws_index/find_data">

    <div class="col-md-12">

      <div class="form-group">
        <label class="control-label col-md-2">Jenis Laporan</label>
        <div class="col-md-4">
          <?php 
            $select_data = [];
            // $select_data[0] = ['value' => 1, 'label' => 'Dashboard waktu per tanggal'];
            // $select_data[1] = ['value' => 2, 'label' => 'Dashboard waktu per bulan'];
            $select_data[2] = ['value' => 3, 'label' => 'Melihat pendaftaran antrean per tanggal'];
            echo $this->master->custom_selection_with_data(array('data' => $select_data, 'value' => 'value', 'label' => 'label'), 3 , 'jenis_laporan', 'jenis_laporan', 'form-control', '', '') ?>
        </div>
      </div>
      
      <div class="form-group">
        <label class="control-label col-md-2">Tipe Waktu</label>
        <div class="col-md-6">
          <div class="radio">
              <label>
                <input name="tipe_waktu" type="radio" class="ace" value="rs" checked/>
                <span class="lbl"> Waktu RS</span>
              </label>
              <label>
                <input name="tipe_waktu" type="radio" class="ace" value="server"/>
                <span class="lbl">Waktu Server</span>
              </label>
          </div>
        </div>
      </div>
      <div id="divBln" style="display: none">
        <div class="form-group">
          <label class="control-label col-md-2">Bulan</label>
          <div class="col-md-2">
            <?php echo $this->master->get_bulan(date('m') , 'bulan', 'bulan', 'form-control', '', '') ?>
          </div>
          <label class="control-label col-md-1">Tahun</label>
          <div class="col-md-2">
            <?php echo $this->master->get_tahun(date('Y') , 'tahun', 'tahun', 'form-control', '', '') ?>
          </div>
        </div>
      </div>
      
      <div id="divTgl" style="display: block">
        <div class="form-group">
          <label class="control-label col-md-2">Tanggal</label>
          <div class="col-md-2">
            <div class="input-group">
                <input name="tgl" id="tgl" value="<?php echo date('Y-m-d')?>" placeholder="ex : yyyy-MM-dd" class="form-control date-picker" type="text" data-date-format="yyyy-mm-dd">
                <span class="input-group-addon">
                  <i class="ace-icon fa fa-calendar"></i>
                </span>
              </div>
          </div>
        </div>
      </div>

        <div class="col-md-3 no-padding">
          <a href="#" id="btn_search_data" class="btn btn-xs btn-default">
            <i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
            Search
          </a>
          <a href="#" id="btn_reset_data" class="btn btn-xs btn-warning">
            <i class="ace-icon fa fa-refresh icon-on-right bigger-110"></i>
            Reset
          </a>
        </div>

      

    </div>
    
    <hr class="separator">
    <div style="margin-top:-30px" id="contentPage"></div>

    </form>
  </div><!-- /.col -->
</div><!-- /.row -->



