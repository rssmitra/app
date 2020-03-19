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
        <label class="control-label col-md-2 ">No.SEP / No.MR  </label>
        <div class="col-md-2">
          <input type="text" class="form-control" name="no_sep_mr" id="no_sep_mr">
        </div>
      </div>

      <!-- <div class="form-group">
        <label class="control-label col-md-2">Tgl Reg Pasien</label>
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
      </div> -->
      <br>

      <div class="form-group">
        <label class="control-label col-md-2 ">&nbsp;</label>
        <div class="col-md-10">
          <a href="#" id="btn_search_data" class="btn btn-xs btn-default">
            <i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
            Search
          </a>
          <a href="#" id="btn_reset_data" class="btn btn-xs btn-warning">
            <i class="ace-icon fa fa-refresh icon-on-right bigger-110"></i>
            Reset
          </a>
         <!--  <a href="#" id="btn_export_word" class="btn btn-xs btn-primary">
            <i class="fa fa-file-word-o bigger-110"></i>
            Export Word
          </a>
          <a href="#" id="btn_export_pdf" class="btn btn-xs btn-danger">
            <i class="fa fa-file-word-o bigger-110"></i>
            Export PDF
          </a>
          <a href="#" id="btn_export_excel" class="btn btn-xs btn-success">
            <i class="fa fa-file-word-o bigger-110"></i>
            Export Excel
          </a> -->
        </div>
      </div>

    </div>

    <hr class="separator">

    <div style="margin-top:-27px">
      <table id="dynamic-table" class="table table-bordered table-hover">
        <thead>
          <tr>  
            <th width="30px" class="center"></th>
            <th width="50px">&nbsp;</th>
            <th width="50px">&nbsp;</th>
            <th></th>
            <th>No. Reg</th>
            <th>No. SEP</th>
            <th>No. MR</th>
            <th width="150px">Nama Pasien</th>
            <th width="120px">Tanggal Masuk</th>
            <th width="120px">Tanggal Keluar</th>
            <th>Dokter</th>
            <th width="120px">Klinik</th>
            <th width="100px">Tipe (RI/RJ)</th>
            <th>Status</th>
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
<script src="<?php echo base_url().'assets/js/custom/casemix/Csm_billing_pasien.js'?>"></script>

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



