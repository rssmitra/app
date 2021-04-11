
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>Sistem Antrian RS. Setia Mitra</title>

		<meta name="description" content="top menu &amp; navigation" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.css" />
		<link rel="stylesheet" href="<?php echo base_url()?>assets/font-awesome/4.5.0/css/font-awesome.css" />

		<!-- page specific plugin styles -->

		<!-- text fonts -->
		<link rel="stylesheet" href="<?php echo base_url()?>assets/css/fonts.googleapis.com.css" />

		<!-- ace styles -->
		<link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />

		<!--[if lte IE 9]>
			<link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace-part2.css" class="ace-main-stylesheet" />
		<![endif]-->
		<link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace-skins.css" />
		<link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace-rtl.css" />

		<!--[if lte IE 9]>
		  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace-ie.css" />
		<![endif]-->

		<!-- inline styles related to this page -->

		<!-- ace settings handler -->
		<script src="<?php echo base_url()?>assets/js/ace-extra.js"></script>

		<!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

		<!--[if lte IE 8]>
		<script src="<?php echo base_url()?>assets/js/html5shiv.js"></script>
		<script src="<?php echo base_url()?>assets/js/respond.js"></script>
		<![endif]-->
	</head>
	<body class="no-skin">
		<div id="navbar" class="navbar navbar-default    navbar-collapse       h-navbar ace-save-state" style="background: linear-gradient(45deg, #155604, transparent);">
			<div class="navbar-container ace-save-state" id="navbar-container">
				<div class="navbar-header pull-left">
					<a href="index.html" class="navbar-brand">
						<small>
							<i class="fa fa-leaf"></i>
							ANTRIAN PELAYANAN PASIEN RS SETIA MITRA
						</small>
					</a>

					<button class="pull-right navbar-toggle collapsed" type="button" data-toggle="collapse" data-target=".navbar-buttons,.navbar-menu" style="background-color: #0f3e02 !important;">
						<span class="sr-only">Toggle sidebar</span>

						<span class="icon-bar"></span>

						<span class="icon-bar"></span>

						<span class="icon-bar"></span>
					</button>
				</div>


				<nav role="navigation" class="navbar-menu pull-left collapse navbar-collapse">
					<ul class="nav navbar-nav">
						<li>
							<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								- Pilih Jenis Antrian -
								<i class="ace-icon fa fa-angle-down bigger-110"></i>
							</a>

							<ul class="dropdown-menu dropdown-light-blue dropdown-caret">
								<li>
									<a href="#" onclick="getMenu('antrian_pendaftaran_pasien')">
										<i class="ace-icon fa fa-eye bigger-110 blue"></i>
										Antrian Pendaftaran Pasien
									</a>
								</li>

								<li>
									<a href="#" onclick="getMenu('antrian_instalasi_farmasi')">
										<i class="ace-icon fa fa-user bigger-110 blue"></i>
										Antrian Instalasi Farmasi
									</a>
								</li>

								<!-- <li>
									<a href="#" onclick="getMenu('poli')">
										<i class="ace-icon fa fa-cog bigger-110 blue"></i>
										Antrian Poli/Klinik
									</a>
								</li> -->
							</ul>
						</li>

					</ul>

				</nav>
			</div><!-- /.navbar-container -->
		</div>

		<div class="main-container ace-save-state" id="main-container">
			<script type="text/javascript">
				try{ace.settings.loadState('main-container')}catch(e){}
			</script>

			<div id="sidebar" class="sidebar      h-sidebar                navbar-collapse collapse          ace-save-state">
				<script type="text/javascript">
					try{ace.settings.loadState('sidebar')}catch(e){}
				</script>
			</div>

			<div class="page-content-main main-content" id="page-area-content">
				<div class="main-content-inner">
					<div class="page-content">
						<div class="page-header">
							<h1>
								Selamat Datang,
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									Sistem Antrian RS Setia Mitra
								</small>
							</h1>
						</div><!-- /.page-header -->

						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<div class="page-area-content">
									<div class="alert alert-info">
										<strong>Perhatian..!</strong> silahkan pilih jenis antrian pada menu diatas.
									</div>
								</div>
								<!-- PAGE CONTENT ENDS -->
							</div><!-- /.col -->
						</div><!-- /.row -->
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->

			<div class="footer">
				<div class="footer-inner">
					<div class="footer-content">
						<span class="bigger-120">
							<span class="blue bolder">SHOS 4.0</span>
							Copyright &copy; RS Setia Mitra  2018 - <?php echo date('Y')?>
						</span>

						&nbsp; &nbsp;
						<span class="action-buttons">
							<a href="#">
								<i class="ace-icon fa fa-twitter-square light-blue bigger-150"></i>
							</a>

							<a href="#">
								<i class="ace-icon fa fa-facebook-square text-primary bigger-150"></i>
							</a>

							<a href="#">
								<i class="ace-icon fa fa-rss-square orange bigger-150"></i>
							</a>
						</span>
					</div>
				</div>
			</div>

			<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
				<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
			</a>
		</div><!-- /.main-container -->

    <!-- basic scripts -->

    <!--[if !IE]> -->
    <script type="text/javascript">
      window.jQuery || document.write("<script src='<?php echo base_url()?>assets/js/jquery.js'>"+"<"+"/script>");
    </script>

    <script type="text/javascript">
      if('ontouchstart' in document.documentElement) document.write("<script src='<?php echo base_url()?>assets/js/jquery.mobile.custom.js'>"+"<"+"/script>");
    </script>
    <script src="<?php echo base_url()?>assets/js/bootstrap.js"></script>

    <!-- page specific plugin scripts -->

    <!--[if lte IE 8]>
      <script src="<?php echo base_url()?>assets/js/excanvas.js"></script>
    <![endif]-->
    <script src="<?php echo base_url()?>assets/js/jquery-ui.custom.js"></script>
    <script src="<?php echo base_url()?>assets/js/jquery.ui.touch-punch.js"></script>
    <script src="<?php echo base_url()?>assets/js/jquery.gritter.js"></script>
    
    <!-- achtung loader -->
    <link href="<?php echo base_url()?>assets/achtung/ui.achtung-mins.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="<?php echo base_url()?>assets/achtung/ui.achtung-min.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>assets/achtung/achtung.js"></script> 

    <script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery.form.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery-validation/dist/jquery.validate.js"></script>

    <!-- the following scripts are used in demo only for onpage help and you don't need them -->
    <!-- <link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace.onpage-help.css" />

    <script type="text/javascript"> ace.vars['base'] = '..'; </script>
    <script src="<?php echo base_url()?>assets/js/ace/elements.onpage-help.js"></script>
    <script src="<?php echo base_url()?>assets/js/ace/ace.onpage-help.js"></script> -->
    <script src="<?php echo base_url()?>assets/js/custom/menu_load_page.js"></script>
    <script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
    <script>
      jQuery(function($) {

        $('.date-picker').datepicker({
          autoclose: true,
          todayHighlight: true
        })
        //show datepicker when clicking on the icon
        .next().on(ace.click_event, function(){
          $(this).prev().focus();
        });

      });

      $(document).ready(function(){
  			$('#page-area-content').load('antrian_pendaftaran_pasien');
      })


      
    </script>
</body>
</html>
