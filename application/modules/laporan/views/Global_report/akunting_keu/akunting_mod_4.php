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
        <h4>Laporan IF (Unit farmasi) Perbulan</h4>
        <form class="form-horizontal" method="post" id="form-default" action="<?php echo base_url()?>laporan/Global_report/show_data_if" target="blank">
        <!-- hidden form -->
          <input type="hidden" name="flag" value="<?php echo $flag?>">
          <input type="hidden" name="title" value="Laporan IF (Unit farmasi)">

        
         
          <div class="form-group">
              <label class="control-label col-md-2">Bulan </label>
              
              <div class="col-md-1">
                <?php echo $this->master->get_bulan('','from_month','from_month','form-control','','');?>
              </div>
              <label class="control-label col-md-1">Tahun</label>
              <div class="col-md-1">
                <?php echo $this->master->get_tahun('','year','year','form-control','','');?>
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


    <div class="col-xs-12">

      <div class="page-header">
       
      </div><!-- /.page-header -->

      <div class="col-md-12">
        <br>
        <h4>Laporan IF (Unit farmasi) Pertahun</h4>
        <form class="form-horizontal" method="post" id="form-default" action="<?php echo base_url()?>laporan/Global_report/show_data_if_b" target="blank">
        <!-- hidden form -->
          <input type="hidden" name="flag" value="<?php echo $flag?>">
          <input type="hidden" name="title" value="Laporan IF (Unit farmasi)">

        
         
          <div class="form-group">
              <label class="control-label col-md-2">Tahun</label>
              <div class="col-md-2">
                <?php echo $this->master->get_tahun('','year','year','form-control','','');?>
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

     <div class="col-xs-12">

      <div class="page-header">
       
      </div><!-- /.page-header -->

      <div class="col-md-12">
        <br>
        <h4>Laporan Rekap IF (Unit farmasi) Per-unit</h4>
        <form class="form-horizontal" method="post" id="form-default" action="<?php echo base_url()?>laporan/Global_report/show_data_if_bagian" target="blank">
        <!-- hidden form -->
          <input type="akunting_mod_12" name="flag" value="<?php echo $flag?>">
          <input type="hidden" name="title" value="Laporan Rekap Per-Unit">

           

            <div class="form-group">
            <label class="control-label col-md-2">Bagian</label>
              <div class="col-md-4">
                <?php echo $this->master->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array('pelayanan' => 1)), '' , 'bagian', 'bagian', 'form-control', '', '') ?>
              </div>
          </div>
         
           <div class="form-group">
              <label class="control-label col-md-2">Bulan </label>
              
              <div class="col-md-1">
                <?php echo $this->master->get_bulan('','from_month','from_month','form-control','','');?>
              </div>
              <label class="control-label col-md-1">Tahun</label>
              <div class="col-md-1">
                <?php echo $this->master->get_tahun('','year','year','form-control','','');?>
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






