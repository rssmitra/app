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
        <a href="<?php echo base_url().'laporan/Global_report/laporanrl'?>" class="btn btn-xs btn-primary"> Kembali ke Menu Utama</a>
        <br>
        <h4>01 - Rl1 ( Data Dasar Rumah Sakit )</h4>
        <form class="form-horizontal" method="post" id="form_search" action="<?php echo base_url()?>laporan/Global_report/show_data_rl1" target="_blank">
        <!-- hidden form -->
          <input type="hidden" name="flag" value="rl_mod_1">
          <input type="hidden" name="title" value="01 - Rl1 ( Data Dasar Rumah Sakit )">
          <div class="col-md-10" style="margin-left: 5px">
              <button type="submit" name="submit" value="data" class="btn btn-xs btn-default">
                Cetak
              </button>
              
            </div>
            
        </form>
          </div>

       <div class="col-md-12">
        <br>
        <br>
        <h4>02 - Rl1.2 ( Indikator Pelayan Rumah Sakit )</h4>
        <form class="form-horizontal" method="post" id="form_search" action="<?php echo base_url()?>laporan/Global_report/show_data_rl12" target="sakit">
        <!-- hidden form -->
          <input type="hidden" name="flag" value="rl_mod_12">
          <input type="hidden" name="title" value="02 - Rl1.2 ( Indikator Pelayan Rumah Sakit )">

           <div class="form-group">
             <label class="control-label col-md-1">Tahun</label>
              <div class="col-md-1">
                <?php echo $this->master->get_tahun('','year','year','form-control','','');?>
              </div>

          </div>
        
            <div class="col-md-10" style="margin-left: 5px">
              <button type="submit" name="submit" value="data" class="btn btn-xs btn-default" onclick="javascript:openPopLaporan('sakit');">
                Proses Pencarian
              </button>
              <button type="submit" name="submit" value="excel" class="btn btn-xs btn-success">
                Export Excel
              </button>
               </form>
        
            </div>
          </div>

       
        <div class="col-md-12">
        <br>
        <br>
        <h4>03 - Rl1.3 ( Fasilitas Tempat Tidur Rawat Inap ) </h4>
        <form class="form-horizontal" method="post" id="form_search" action="<?php echo base_url()?>laporan/Global_report/show_data_rl13" target="blank">
        <!-- hidden form -->
          <input type="hidden" name="flag" value="rl_mod_13">
          <input type="hidden" name="title" value="03 - Rl1.3 ( Fasilitas Tempat Tidur Rawat Inap ) ">

           <div class="form-group">
             <label class="control-label col-md-1">Tahun</label>
              <div class="col-md-1">
                <?php echo $this->master->get_tahun('','year','year','form-control','','');?>
              </div>

          </div>
        
            <div class="col-md-10" style="margin-left: 5px">
              <button type="submit" name="submit" value="data" class="btn btn-xs btn-default">
                Proses Pencarian
              </button>
              <button type="submit" name="submit" value="excel" class="btn btn-xs btn-success">
                Export Excel
              </button>
            </div>
            </form>
          </div>

        
     </div>

    </div><!-- /.col -->
  </div><!-- /.row -->
</body>
</html>






