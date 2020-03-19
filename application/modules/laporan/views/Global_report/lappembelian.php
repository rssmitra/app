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

        <center><h4>LAPORAN PEMBELIAN (OPERATIONAL LEVEL)</h4></center>

       
        <ol>
          <li><a href="<?php echo base_url().'laporan/Global_report/lappembelian_1?mod=1'?>" target="_blank">BULANAN</a></li>
		    <li><a href="<?php echo base_url().'laporan/Global_report/lappembelian_1?mod=2'?>" target="_blank">TAHUNAN</a></li>
        </ol>
		</div>

    </div><!-- /.col -->
  </div><!-- /.row -->
</body>
</html>






