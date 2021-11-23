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
        <h4>Laporan Pengeluaran Obat per Periode Berdasarkan Mutasi Barang
        </h4>
        <form class="form-horizontal" method="post" id="form_search" action="<?php echo base_url()?>laporan/Global_report/show_data">
        <!-- hidden form -->
          <input type="hidden" name="flag" value="<?php echo $flag?>">
          <input type="hidden" name="title" value="Laporan Pengeluaran Obat per Periode Berdasarkan Mutasi Barang">

          <div class="form-group">
            <label class="control-label col-md-1">Bagian</label>
              <div class="col-md-5">
                <?php echo $this->master->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array('status_aktif' => 1)), '' , 'bagian', 'bagian', 'form-control', '', '') ?>
              </div>
          </div>

          <div class="form-group">
              <label class="control-label col-md-1">Bulan </label>
              
              <div class="col-md-2">
                <?php echo $this->master->get_bulan('','from_month','from_month','form-control','','');?>
              </div>
              <label class="control-label col-md-1">Tahun </label>
              <div class="col-md-2">
                <?php echo $this->master->get_tahun('','year','year','form-control','','');?>
              </div>

          </div>
        
          <div class="form-group">
            <label class="control-label col-md-1 ">&nbsp;</label>
            <div class="col-md-10" style="margin-left: 5px">
              <button type="submit" name="submit" value="pengadaan_mod_12" class="btn btn-xs btn-default">
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






