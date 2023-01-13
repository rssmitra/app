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
      <center><h4>FORM PENCARIAN DATA RESUME BILLING RAWAT JALAN<br><small style="font-size:12px">(Silahkan lakukan pencarian data berdasarkan parameter dibawah ini)</small></h4></center>
      <br>
      <div class="form-group">
        <label class="control-label col-md-2">Pencarian Berdasarkan</label>
          <div class="col-md-6">
            <div class="radio">
              <label>
                <input name="search_by_field" type="radio" class="ace" value="created_date" checked>
                <span class="lbl"> Waktu Input/Costing</span>
              </label>

              <label>
                <input name="search_by_field" type="radio" class="ace" value="csm_rp_tgl_keluar">
                <span class="lbl"> Tanggal Kunjungan</span>
              </label>
            </div>

          </div>
      </div>
      <div class="form-group">
        <label class="control-label col-md-2">Masukan Tanggal</label>
          <div class="col-md-2">
            <div class="input-group">
              <input class="form-control date-picker" name="from_tgl_reg" id="from_tgl_reg" type="text" data-date-format="yyyy-mm-dd" value=""/>
              <span class="input-group-addon">
                <i class="fa fa-calendar bigger-110"></i>
              </span>
            </div>
          </div>

          <label class="control-label col-md-1">s/d Tgl</label>
          <div class="col-md-2">
            <div class="input-group">
              <input class="form-control date-picker" name="to_tgl_reg" id="to_tgl_reg" type="text" data-date-format="yyyy-mm-dd" value=""/>
              <span class="input-group-addon">
                <i class="fa fa-calendar bigger-110"></i>
              </span>
            </div>
          </div>
          <div class="col-md-5">
          <a href="#" id="btn_search_data" class="btn btn-xs btn-default">
            <i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
            Search
          </a>
          <a href="#" id="btn_reset_data" class="btn btn-xs btn-warning">
            <i class="ace-icon fa fa-refresh icon-on-right bigger-110"></i>
            Reset
          </a>
          <!-- <a href="#" id="btn_export_pdf" class="btn btn-xs btn-danger">
            <i class="fa fa-file-word-o bigger-110"></i>
            Export PDF
          </a> -->
          <a href="#" id="btn_export_excel" class="btn btn-xs btn-success">
            <i class="fa fa-file-word-o bigger-110"></i>
            Export Excel
          </a>
        </div>
      </div>
      <br>

    </div>

    <hr class="separator">

    <div style="margin-top:-27px">
      <table id="dynamic-table" class="table table-bordered table-hover">
        <thead>
          <tr>  
            <th width="30px" class="center"></th>
            <th width="30px">No. SEP</th>
            <th width="40px">No. MR</th>
            <th width="150px">Nama Pasien</th>
            <th width="60px" class="center">Tanggal</th>
            <th width="180px">Nama Dokter</th>
            <th width="180px">Diagnosa</th>
            <th width="60px" class="center">Dokter</th>
            <th width="60px" class="center">Adm</th>
            <th width="60px" class="center">Farmasi</th>
            <th width="60px" class="center">Penunjang</th>
            <th width="60px" class="center">Tindakan</th>
            <th width="60px" class="center">Total</th>
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
<script src="<?php echo base_url().'assets/js/custom/casemix/Csm_resume_billing.js'?>"></script>

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



