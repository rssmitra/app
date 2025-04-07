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
        <h4>01 - Laporan Kinerja per Periode</h4>
        <form class="form-horizontal" method="post" id="form_search" action="<?php echo base_url()?>laporan/Global_report/showdatakinerja" target="_blank">
        <!-- hidden form -->
          <input type="hidden" name="flag" value="lapkinerja">
          <input type="hidden" name="title" value="01 - Laporan Kinerja">

            <div class="form-group">
              <label class="control-label col-md-1">Unit Instalasi</label>
               <div class="col-md-2">
                <select name="penunjang" class="form-control">
                  <option value="Lab">Laboratorium </option>
                  <option value="Rad">Radiologi</option>
                  <option value="Fisio">Fisioterapi</option>
                </select>
              </div>
              <label class="control-label col-md-1">Tanggal </label>
              <div class="col-md-1">
                <input class="form-control date-picker" name="from_tgl" id="from_tgl" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>"/>
              </div>
              <label class="control-label col-md-1">s/d Tanggal</label>
              <div class="col-md-1">
                <input class="form-control date-picker" name="to_tgl" id="to_tgl" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>"/>
              </div>
              
            <div class="col-md-4" style="margin-left: -1%">
              <button type="submit" name="submit" value="data" class="btn btn-xs btn-primary">
                Tampilkan Data
              </button>
              <button type="submit" name="submit" value="excel" class="btn btn-xs btn-success">
                Export Excel
              </button>
            </div>
          </div>
        </form>


        <br>
        <h4>02 - Laporan Kinerja Tahunan</h4>
        <form class="form-horizontal" method="post" id="form_search" action="<?php echo base_url()?>laporan/Global_report/showdatakinerjathn" target="_blank">
        <!-- hidden form -->
          <input type="hidden" name="flag" value="lapkinerja">
          <input type="hidden" name="title" value="01 - Laporan Kinerja">

            <div class="form-group">
              <label class="control-label col-md-2">Penunjang Medis</label>
               <div class="col-md-2">
                <select name="penunjang" class="form-control">
                  <option value="Lab">Laboratorium </option>
                  <option value="Rad">Radiologi</option>
                  <option value="Fisio">Fisioterapi</option>
                </select>
              </div>

             <label class="control-label col-md-1">Tahun</label>
             
              <div class="col-md-1" style="margin-left: -20px">
                <?php echo $this->master->get_tahun(date('Y'),'year','year','form-control','','');?>
              </div>
         

            <div class="col-md-6" style="margin-left: -1%">
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






