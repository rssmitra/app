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
        <a href="<?php echo base_url().'laporan/Global_report'?>" class="btn btn-sm glr-btn-back"><i class="fa fa-arrow-left"></i> Menu Laporan</a>
        <br>
        <h4 class="glr-form-title">Laporan Billing Dokter yang belum dibayarkan  Per-periode</h4>
        <form class="form-horizontal" method="post" id="form_search" action="<?php echo base_url()?>laporan/Global_report/show_data">
        <!-- hidden form -->
          <input type="hidden" name="flag" value="<?php echo $flag?>">
          <input type="hidden" name="title" value="Laporan Billing Dokter yang belum dibayar Per-periode">

         <div class="form-group">
              <label class="control-label col-md-1">Dari Bulan</label>
              <div class="col-md-1">
                <?php echo $this->master->get_bulan('','from_month','from_month','form-control','','');?>
              </div>
              <label class="control-label col-md-1">s/d Bulan</label>
              <div class="col-md-1">
                <?php echo $this->master->get_bulan('','to_month','to_month','form-control','','');?>
              </div>
              <label class="control-label col-md-1">Tahun</label>
              <div class="col-md-1">
                <?php echo $this->master->get_tahun('','year','year','form-control','','');?>
              </div>

          </div>
          <div class="form-group">
            <label class="control-label col-md-2">Nama Dokter</label>
              <div class="col-md-4">
                <?php echo $this->master->custom_selection($params = array('table' => 'mt_dokter_v', 'id' => 'kode_dokter', 'name' => 'nama_pegawai', 'where' => array('status' => 0)), '' , 'dokter', 'dokter', 'form-control', '', '') ?>
              </div>
          </div>
         
                    <div class="glr-action-row">
            <button type="submit" name="submit" value="data" class="btn btn-sm glr-btn-search">
              <i class="fa fa-search"></i>&nbsp;Tampilkan Data
            </button>
            <button type="submit" name="submit" value="excel" class="btn btn-sm glr-btn-excel">
              <i class="fa fa-file-excel-o"></i>&nbsp;Export Excel
            </button>
          </div>

        </form>
        <!-- end content -->
        
     </div>

    </div><!-- /.col -->
  </div><!-- /.row -->
</body>
</html>






