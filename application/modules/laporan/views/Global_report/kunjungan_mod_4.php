<html>
<head>
<title>Laporan Umum</title>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.css" />
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />

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
        <a href="<?php echo base_url().'laporan/Global_report'?>" class="btn btn-xs btn-primary"> Kembali ke Menu Utama</a>
        <br>
        <h4>Pencarian Data MCU Pasien</h4>
        <form class="form-horizontal" method="post" id="form_search" action="<?php echo base_url()?>laporan/Global_report/v_mcu" target="blank">
        <!-- hidden form -->
          <input type="hidden" name="flag" value="kunjungan_mod_4">
          <input type="hidden" name="title" value="Daftar Registrasi Pasien Per-hari">
          
          <div class="form-group">
        <label class="control-label col-md-2">Tanggal Kunjungan</label>
          <div class="col-md-2">
            <div class="input-group">
              <input class="form-control date-picker" name="from_tgl" id="from_tgl" type="text" placeholder="Format : yyyy-mm-dd" value=""/>
              
            </div>
          </div>

          <label class="control-label col-md-1">s/d Tanggal</label>
          <div class="col-md-2">
            <div class="input-group">
              <input class="form-control date-picker" name="to_tgl" id="to_tgl" type="text" placeholder="Format : yyyy-mm-dd" value=""/>
             
            </div>
          </div>
      </div>
         <div class="form-group">
            <label class="control-label col-md-2 ">&nbsp;</label>
            <div class="col-md-10" style="margin-left: 5px">
              <button type="submit" name="submit" value="data" class="btn btn-xs btn-default">
                Proses Pencarian
              </button>
              <button type="submit" name="submit" value="excel" class="btn btn-xs btn-success">
                Export Excel
              </button>
             
            </div>
          </div>

        </form>
        <!-- end content -->
        
     </div>

    </div><!-- /.col -->
  </div><!-- /.row -->
</body>
</html>






