<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>SIRS - Laporan</title>

		<meta name="description" content="top menu &amp; navigation" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.css" />
		<link rel="stylesheet" href="<?php echo base_url()?>assets/css/font-awesome.css" />

		<!-- page specific plugin styles -->

		<!-- text fonts -->
		<link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace-fonts.css" />

		<!-- ace styles -->
		<link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />

		<!--[if lte IE 9]>
			<link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace-part2.css" class="ace-main-stylesheet" />
		<![endif]-->

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
  <style>
    .accordion-toggle{
      color: black !important;
    }
    .accordion-style1.panel-group .panel-heading .accordion-toggle{
      background-color: #cbe0f1;
    }
  </style>
	<body class="no-skin">
		<!-- #section:basics/navbar.layout -->
		<div id="navbar" class="navbar navbar-default navbar-collapse h-navbar" style="background-color: #00b8a8">
			<script type="text/javascript">
				try{ace.settings.check('navbar' , 'fixed')}catch(e){}
			</script>

			<div class="navbar-container" id="navbar-container">
				<div class="navbar-header pull-left">
					<!-- #section:basics/navbar.layout.brand -->
					<a href="#" class="navbar-brand">
						<small>
							<i class="fa fa-leaf"></i>
							RS SETIA MITRA
						</small>
					</a>

					<!-- /section:basics/navbar.layout.brand -->

					<!-- #section:basics/navbar.toggle -->
					<button class="pull-right navbar-toggle navbar-toggle-img collapsed" type="button" data-toggle="collapse" data-target=".navbar-buttons,.navbar-menu">
						<span class="sr-only">Toggle user menu</span>

						<img src="<?php echo base_url()?>assets/avatars/user.jpg" alt="Jason's Photo" />
					</button>

					<button class="pull-right navbar-toggle collapsed" type="button" data-toggle="collapse" data-target="#sidebar">
						<span class="sr-only">Toggle sidebar</span>

						<span class="icon-bar"></span>

						<span class="icon-bar"></span>

						<span class="icon-bar"></span>
					</button>

					<!-- /section:basics/navbar.toggle -->
				</div>

			</div><!-- /.navbar-container -->
		</div>

		<!-- /section:basics/navbar.layout -->
		<div class="main-container" id="main-container">
			<script type="text/javascript">
				try{ace.settings.check('main-container' , 'fixed')}catch(e){}
			</script>

			<!-- /section:basics/sidebar.horizontal -->
			<div class="main-content">
				<div class="main-content-inner">
					<div class="page-content">
						<?php echo $html; ?>
					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->

			<div class="footer">
				<div class="footer-inner">
					<!-- #section:basics/footer -->
					<div class="footer-content">
						<span class="bigger-120">
							<span class="blue bolder">SIRS</span>
							- RS Setia Mitra &copy; 2019
						</span>
					</div>

					<!-- /section:basics/footer -->
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

		<!-- <![endif]-->

		<!--[if IE]>
<script type="text/javascript">
 window.jQuery || document.write("<script src='<?php echo base_url()?>assets/js/jquery1x.js'>"+"<"+"/script>");
</script>
<![endif]-->
		<script type="text/javascript">
			if('ontouchstart' in document.documentElement) document.write("<script src='<?php echo base_url()?>assets/js/jquery.mobile.custom.js'>"+"<"+"/script>");
		</script>
		<script src="<?php echo base_url()?>assets/js/bootstrap.js"></script>

		<!-- page specific plugin scripts -->

		<!-- ace scripts -->
		<script src="<?php echo base_url()?>assets/js/ace/ace.js"></script>
		<script src="<?php echo base_url()?>assets/js/ace/ace.ajax-content.js"></script>
		<script src="<?php echo base_url()?>assets/js/ace/ace.sidebar.js"></script>
		<script src="<?php echo base_url()?>assets/js/ace/ace.sidebar-scroll-1.js"></script>
		<script src="<?php echo base_url()?>assets/js/ace/ace.submenu-hover.js"></script>
		<script src="<?php echo base_url()?>assets/js/ace/ace.settings.js"></script>
		<script src="<?php echo base_url()?>assets/js/ace/ace.settings-rtl.js"></script>
		<script src="<?php echo base_url()?>assets/js/ace/ace.settings-skin.js"></script>
		
		<!-- datepicker -->
		<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
		<script type="text/javascript">
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
		</script>
		


	</body>
</html>
