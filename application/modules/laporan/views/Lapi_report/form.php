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
          <a href="<?php echo base_url().'lapi'?>"><?php echo $title?></a>
          <small>
            <i class="ace-icon fa fa-angle-double-right"></i>
            <?php echo isset($breadcrumbs)?$breadcrumbs:''?>
          </small>
        </h1>
      </div><!-- /.page-header -->

      <div class="col-md-12">

        <!-- content -->
        <a href="<?php echo base_url().'lapi/logout'?>" class="label label-danger"> Logout </a>
        <br>
        <h4>Laporan Penjualan</h4>
        <form name="kinerja_dr" class="form-horizontal" method="post" id="form_search" action="<?php echo base_url()?>lapi/showData" target="blank">
          <!-- hidden form -->
          <input type="hidden" name="token" value="<?php echo ($this->session->userdata('token'))?$this->session->userdata('token'):''?>">
          <div class="form-group" id="bln">
            <label class="control-label col-md-1">Bulan</label>
            <div class="col-md-1">
              <?php echo $this->master->lapi_get_bulan(date('m'),'month','month','form-control','','');?>
            </div>
            <label class="control-label col-md-1">Tahun</label>
            <div class="col-md-1">
              <?php echo $this->master->lapi_get_tahun(date('Y'),'year','year','form-control','','');?>
            </div>
            <div class="col-md-4" style="margin-left: 5px">
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






