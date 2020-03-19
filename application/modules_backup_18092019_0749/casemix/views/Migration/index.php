<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />

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

    <div class="col-md-12">
      <center><h4>FORM PENCARIAN DATA REGISTRASI PASIEN<br><small style="font-size:12px">(Silahkan lakukan pencarian data berdasarkan parameter dibawah ini)</small></h4></center>
      <br>
      <div class="form-group">
        <label class="control-label col-md-2">No.SEP / No.MR  </label>
        <div class="col-md-2">
          <input type="text" class="form-control" name="no_sep_mr" id="no_sep_mr">
        </div>
        <label class="control-label col-md-1">Tanggal</label>
          <div class="col-md-2">
            <div class="input-group">
              <input class="form-control date-picker" name="from_tgl_reg" id="from_tgl_reg" type="text" data-date-format="yyyy-mm-dd" value=""/>
              <span class="input-group-addon">
                <i class="fa fa-calendar bigger-110"></i>
              </span>
            </div>
          </div>

          <label class="control-label col-md-1">s/d tanggal</label>
          <div class="col-md-2">
            <div class="input-group">
              <input class="form-control date-picker" name="to_tgl_reg" id="to_tgl_reg" type="text" data-date-format="yyyy-mm-dd" value=""/>
              <span class="input-group-addon">
                <i class="fa fa-calendar bigger-110"></i>
              </span>
            </div>
          </div>
          <div class="col-md-2">
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
    </div>
    <p>&nbsp;</p>
    <hr class="separator">
    <div style="margin-top:-20px" id="table-data">
      <table id="dynamic-table" class="table table-bordered table-hover">
        <thead>
          <tr>  
            <th width="30px" class="center"></th>
            <th width="50px">&nbsp;</th>
            <th width="50px">&nbsp;</th>
            <th></th>
            <th width="80px">No. Reg</th>
            <th>No. SEP</th>
            <th>No. MR</th>
            <th width="150px">Nama Pasien</th>
            <th width="120px">Tanggal (In/Out)</th>
            <th width="150px">Dokter</th>
            <th width="80px" class="center">Tipe<br>(RI/RJ)</th>
            <th class="center">Status</th>
            <th class="center">Submit</th>
            <!-- <th class="center">Merge</th> -->
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>

    </form>
  </div><!-- /.col -->
</div><!-- /.row -->

<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<script src="<?php echo base_url().'assets/js/custom/casemix/Migration.js'?>"></script>

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

});  
</script>



