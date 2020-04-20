<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>

<script>
jQuery(function($) {

  $('.date-picker').datepicker({
    autoclose: true,
    todayHighlight: true
  })
  //show datepicker when clicking on the icon
  .next().on(ace.click_event, function(){
    $(this).prev().focus();
  });

  $('input[name=search_by_field]').click(function(e){
    var field = $('input[name=search_by_field]:checked').val();
    if ( field == 'month_year' ) {
      $('#month_year_field').show('fast');
      $('#tanggal_field').hide('fast');
    }else{
      if (field=='created_date') {
        $('#text_label').html('Tanggal Input/Costing');
      }else {
        $('#text_label').html('Tanggal Transaksi');
      }
      $('#month_year_field').hide('fast');
      $('#tanggal_field').show('fast');
    }
  });


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

    <form class="form-horizontal" method="post" id="form_search">

    <div class="form-group">
        <label class="control-label col-md-1">Bulan</label>
        <div class="col-md-2">
          <select name="month" id="month" class="form-control">
            <option value="">-Silahkan Pilih-</option>
            <?php
              for($month=1;$month<13;$month++){
                echo '<option value="'.$month.'">'.$this->tanggal->getBulan($month).'</option>';    
              }
            ?>
            
          </select>
        </div>
        <div class="col-md-2" style="margin-left: -1.8%">
          <select name="year" id="year" class="form-control">
            <option value="">-Silahkan Pilih-</option>
             <?php
                for($year=2017;$year<=date('Y');$year++){
                  echo '<option value="'.$year.'">'.$year.'</option>';    
                }
              ?>
          </select>
        </div>
        <label class="control-label col-md-1">Tipe (RI/RJ)</label>
        <div class="col-md-2">
          <select name="tipe" id="tipe" class="form-control">
            <option value="all">-Semua-</option>
            <option value="RJ">Rawat Jalan</option>
            <option value="RI">Rawat Inap</option>
          </select>
        </div>
        <div class="col-md-3" style="margin-left: -1%">
          <a href="#" id="btn_search_data" class="btn btn-xs btn-default">
            <i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
            Search
          </a>
          <a href="#" id="btn_reset_data" class="btn btn-xs btn-warning">
            <i class="ace-icon fa fa-refresh icon-on-right bigger-110"></i>
            Reset
          </a>
          <a href="#" id="btn_export_excel" class="btn btn-xs btn-success">
            <i class="fa fa-file-word-o bigger-110"></i>
            Export Excel
          </a>
        </div>
        <div class="col-md-1 pull-right" style="margin-right: 11px">
            <a href="#" id="btn_upload_file" onclick="getMenu('casemix/Csm_upload_hasil_verif/form')" class="btn btn-xs btn-primary">
              <i class="fa fa-upload bigger-110"></i>
              Upload File
            </a>
        </div>
    </div>

    <hr class="separator">

    <div style="margin-top:-27px">
      <table id="dynamic-table" base-url="casemix/Csm_upload_hasil_verif/get_data" class="table table-bordered table-hover">
        <thead>
          <tr>  
            <th width="80px">ID</th>
            <th width="80px">Bulan</th>
            <th width="80px">Tahun</th>
            <th>Tipe (RI/RJ)</th>
            <th>Total Data</th>
            <th>Nama File</th>
            <th>Last Update</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
      <script src="<?php echo base_url().'assets/js/custom/als_datatable_custom_url.js'?>"></script>

    </div>
    </form>
  </div><!-- /.col -->
</div><!-- /.row -->





