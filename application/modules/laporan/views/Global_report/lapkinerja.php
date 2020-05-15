<html>
<head>
  <title>Laporan Umum</title>
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.css" />
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
</head>
<body>
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

      <div class="col-md-12">

        <!-- content -->
        <a href="<?php echo base_url().'laporan/Global_report/lapkinerja'?>" class="btn btn-xs btn-primary"> Kembali ke Menu Utama</a>
        <br>
        <h4>01 - Laporan Kinerja</h4>
        <form class="form-horizontal" method="post" id="form_search" action="<?php echo base_url()?>laporan/Global_report/showdatakinerja" target="_blank">
        <!-- hidden form -->
          <input type="hidden" name="flag" value="lapkinerja">
          <input type="hidden" name="title" value="01 - Laporan Kinerja">

           <div class="form-group">
         
            <label class="control-label col-md-1">Tanggal</label>
            <div class="col-md-2">
              <div class="input-group">
                <input class="form-control date-picker" name="tgl1" id="tgl1" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>"/>
                <span class="input-group-addon">
                  <i class="fa fa-calendar bigger-110"></i>
                </span>
              </div>
            </div>
            <label class="control-label col-md-1">sampai</label>
            <div class="col-md-2">
              <div class="input-group">
                <input class="form-control date-picker" name="tgl2" id="tgl2" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>"/>
                <span class="input-group-addon">
                  <i class="fa fa-calendar bigger-110"></i>
                </span>
              </div>
            </div>
          <label class="control-label col-md-1">Jenis Kelamin</label>
            <div class="col-md-1">
              <select name="jeniskelamin" id="jeniskelamin" class="form-control">
                <option value="">-All-</option>
                <option value="1">Laki-laki</option>
                <option value="2">Perempuan</option>
              </select>
            </div>

            <div class="col-md-6" style="margin-left: -1.5%">
              <button type="submit" name="submit" value="data" class="btn btn-xs btn-primary">
                Tampilkan Data
              </button>
              <button type="submit" name="submit" value="excel" class="btn btn-xs btn-success">
                Export Excel
              </button>
            </div>
          </div>
        </form>

       
</body>
</html>






