<html>
<head>
  <title>Laporan Umum</title>
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.css" />
  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />
  <script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
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
        <a href="<?php echo base_url().'laporan/Global_report'?>" class="btn btn-xs btn-primary"> Kembali ke Menu Utama</a>
        <br>
        <h4>Rekapitulasi Stok Awal Bulan, Penerimaan, Distribusi dan Saldo Akhir</h4>
        <form class="form-horizontal" method="post" id="form-default" action="<?php echo base_url()?>laporan/Global_report/show_data_gdg_nm" target="_blank">
        <!-- hidden form -->
          <input type="hidden" name="flag" value="<?php echo $flag?>">
          <input type="hidden" name="title" value="Rekapitulasi Stok Awal Bulan, Penerimaan, Distribusi dan Saldo Akhir">
          
          <div class="form-group">
              <label class="control-label col-md-1">Bulan </label>
              <div class="col-md-1">
                <?php echo $this->master->get_bulan(date('m'),'from_month','from_month','form-control','','');?>
              </div>
              <label class="control-label col-md-1">Tahun</label>
              <div class="col-md-1">
                <?php echo $this->master->get_tahun(date('Y'),'year','year','form-control','','');?>
              </div>
              <div class="col-md-8 no-padding">
                <button type="submit" name="submit" value="data" class="btn btn-xs btn-default">
                  Proses Pencarian
                </button>
                <button type="submit" name="submit" value="excel" class="btn btn-xs btn-success">
                  Export Excel
                </button>
              </div>
          </div>

        </form>

     </div>

    </div><!-- /.col -->
  </div><!-- /.row -->
</body>
</html>






