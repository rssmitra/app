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
      <center><h4>FORM PENCARIAN DOKUMEN KLAIM PASIEN<br><small style="font-size:12px">(Silahkan lakukan pencarian data berdasarkan parameter dibawah ini)</small></h4></center>
      <br>
      <div class="form-group">
        <label class="control-label col-md-2">Pencarian Data</label>
          <div class="col-md-10">
            <div class="radio">
              <label>
                <input name="search_data" type="radio" class="ace" value="dokumen_klaim" checked>
                <span class="lbl"> Dokumen Klaim </span>
              </label>

              <label>
                <input name="search_data" type="radio" class="ace" value="reg_not_merge">
                <span class="lbl"> Registrasi Pasien Yang Belum Di Merge </span>
              </label>

            </div>

          </div>
      </div>
      <div class="form-group">
        <label class="control-label col-md-2">Pencarian Berdasarkan</label>
          <div class="col-md-6">
            <div class="radio">
              <label>
                <input name="search_by_field" type="radio" class="ace" value="created_date" checked>
                <span class="lbl"> Waktu Input/Costing</span>
              </label>

              <label>
                <input name="search_by_field" type="radio" class="ace" value="tgl_transaksi_kasir">
                <span class="lbl"> Tanggal Transaksi</span>
              </label>

              <label>
                <input name="search_by_field" type="radio" class="ace" value="month_year">
                <span class="lbl"> Bulan dan Tahun Transaksi</span>
              </label>
            </div>

          </div>
      </div>
      <div class="form-group" id="tanggal_field">
        <label class="control-label col-md-2" id="text_label">Tanggal Input/Costing</label>
          <div class="col-md-2">
            <div class="input-group">
              <input class="form-control date-picker" name="from_tgl_reg" id="from_tgl_reg" type="text" data-date-format="yyyy-mm-dd" value=""/>
              <span class="input-group-addon">
                <i class="fa fa-calendar bigger-110"></i>
              </span>
            </div>
          </div>

          <label class="control-label col-md-1">s/d Tanggal</label>
          <div class="col-md-2">
            <div class="input-group">
              <input class="form-control date-picker" name="to_tgl_reg" id="to_tgl_reg" type="text" data-date-format="yyyy-mm-dd" value=""/>
              <span class="input-group-addon">
                <i class="fa fa-calendar bigger-110"></i>
              </span>
            </div>
          </div>
      </div>
      <div class="form-group" id="month_year_field" style="display:none">
        <label class="control-label col-md-2">Bulan</label>
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

          <label class="control-label col-md-1">Tahun</label>
          <div class="col-md-2">
            <select name="year" id="year" class="form-control">
              <option value="">-Silahkan Pilih-</option>
               <?php
                  for($year=2017;$year<=date('Y');$year++){
                    echo '<option value="'.$year.'">'.$year.'</option>';    
                  }
                ?>
            </select>
          </div>
      </div>
      <div class="form-group">
        <label class="control-label col-md-2">Tipe (RI/RJ)</label>
          <div class="col-md-2">
            <select name="tipe" id="tipe" class="form-control">
              <option value="all">-Semua-</option>
              <option value="RJ">Rawat Jalan</option>
              <option value="RI">Rawat Inap</option>
            </select>
          </div>
          <div class="col-md-6">
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
      </div>

      <br>

    </div>

    <hr class="separator">

    <div style="margin-top:-27px">
      <table id="dynamic-table" class="table table-bordered table-hover">
        <thead>
          <tr>  
            <th width="30px" class="center"></th>
            <th width="80px">No. SEP</th>
            <th width="80px">No. Reg</th>
            <th>No. MR</th>
            <th>Nama Pasien</th>
            <th width="130px">Tanggal Masuk</th>
            <th width="130px">Tanggal Keluar</th>
            <th width="80px" class="center">Tipe (RI/RJ)</th>
            <th width="100px" class="center">Total Klaim</th>
            <th width="120px" class="center">Tanggal Costing</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
      <script src="<?php echo base_url().'assets/js/custom/casemix/Csm_dokumen_klaim.js'?>"></script>

    </div>
    </form>
  </div><!-- /.col -->
</div><!-- /.row -->

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

});  
</script>



