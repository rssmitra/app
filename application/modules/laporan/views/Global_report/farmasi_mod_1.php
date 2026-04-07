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
        <h4 class="glr-form-title">Laporan Keluar/Masuk Obat & Alkes Berdasarkan Tahun</h4>
        <form class="form-horizontal" method="post" id="form_search" action="<?php echo base_url()?>laporan/Global_report/show_data" target="blank">
        <!-- hidden form -->
          <input type="hidden" name="flag" value="<?php echo $flag?>">
          <input type="hidden" name="title" value="Laporan Keluar/Masuk Obat & Alkes Berdasarkan Tahun">

          <div class="form-group">
            <label class="control-label col-md-2">Tahun</label>
              <div class="col-md-2">
                <input class="form-control date-picker" name="tahun" id="tahun" type="text" placeholder="Format : 2019" value=""/>
              </div>
          </div>
          <div class="form-group">
            <label class="control-label col-md-2">Kode Bagian</label>
              <div class="col-md-2">
                <input class="form-control date-picker" name="kode_bagian" id="kode_bagian" type="text" value=""/>
              </div>
          </div>
          <p style="margin-left: 2%; font-size: 11px">
            Keterangan :<br>
            1. Gudang Farmasi (060201)<br>
            2. Farmasi (060101)
          </p>
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






