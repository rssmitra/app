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
        Transaksi Pasien BPJS
          <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
            <?php echo isset($breadcrumbs)?$breadcrumbs:''?>
          </small>
        </h1>
      </div><!-- /.page-header -->

      <div class="col-md-12">

        <!-- content -->
        <a href="<?php echo base_url().'laporan/Global_report'?>" class="btn btn-xs btn-default"> Kembali ke Menu Utama</a>
        <hr>
        <form class="form-horizontal" method="post" id="form_search" action="<?php echo base_url()?>laporan/Global_report/show_data">
        <!-- hidden form -->
          <input type="hidden" name="flag" value="<?php echo $flag?>">
          <input type="hidden" name="title" value="Transaksi Pasien BPJS">

          <div class="form-group">
              <label class="control-label col-md-1">Jenis Transaksi</label>
               <div class="col-md-2">
                <select name="keterangan" class="form-control">
                  <option value="RJ">Rawat Jalan </option>
                  <option value="RI">Rawat Inap</option>
                </select>
              </div>
              <label class="control-label col-md-1">Bulan</label>
              <div class="col-md-1">
                <?php echo $this->master->get_bulan(1,'from_month','from_month','form-control','','');?>
              </div>
              <label class="control-label col-md-1">s/d Bulan</label>
              <div class="col-md-1">
                <?php echo $this->master->get_bulan(date('m'),'to_month','to_month','form-control','','');?>
              </div>
              <div class="col-md-1" style="margin-left: -20px">
                <?php echo $this->master->get_tahun(date('Y'),'year','year','form-control','','');?>
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
        <!-- end content -->
        
     </div>

    </div><!-- /.col -->
  </div><!-- /.row -->
</body>
</html>






