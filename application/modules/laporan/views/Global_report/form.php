<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title><?php echo APPS_NAME_SORT; ?> - Laporan</title>

		<meta name="description" content="top menu &amp; navigation" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.css" />
		<link rel="stylesheet" href="<?php echo base_url()?>assets/css/font-awesome.css" />
		<link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace-fonts.css" />
		<link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />

		<!--[if lte IE 9]>
			<link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace-part2.css" class="ace-main-stylesheet" />
		<![endif]-->
		<!--[if lte IE 9]>
		  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace-ie.css" />
		<![endif]-->

		<script src="<?php echo base_url()?>assets/js/ace-extra.js"></script>

		<!--[if lte IE 8]>
		<script src="<?php echo base_url()?>assets/js/html5shiv.js"></script>
		<script src="<?php echo base_url()?>assets/js/respond.js"></script>
		<![endif]-->

		<style>
			/* ===== Navbar ===== */
			.h-navbar {
				background: -webkit-linear-gradient(135deg, #0369a1 0%, #0ea5e9 100%) !important;
				background: linear-gradient(135deg, #0369a1 0%, #0ea5e9 100%) !important;
			}
			.navbar-brand small { color: #fff; font-size: 15px; font-weight: 600; letter-spacing: .4px; }
			.navbar-brand small i { margin-right: 6px; }

			/* ===== Page background ===== */
			body { background: #f0f4f8 !important; }

			/* ===== Page-header (rendered from child views) ===== */
			.page-content .page-header {
				border-bottom: 2px solid #0ea5e9;
				margin-bottom: 16px;
				padding-bottom: 10px;
			}
			.page-content .page-header h1 {
				font-size: 20px;
				font-weight: 700;
				color: #1e3a5f;
			}
			.page-content .page-header h1 small {
				font-size: 13px;
				color: #64748b;
			}

			/* ===== Form container card ===== */
			.page-content .col-md-12 {
				background: #fff;
				border: 1px solid #e2e8f0;
				border-radius: 8px;
				padding: 18px 22px 14px;
				-webkit-box-shadow: 0 1px 6px rgba(0,0,0,.07);
				box-shadow: 0 1px 6px rgba(0,0,0,.07);
				margin-bottom: 10px;
			}

			/* ===== Back button ===== */
			.page-content .glr-btn-back {
				display: -webkit-inline-flex;
				display: inline-flex;
				-webkit-align-items: center;
				align-items: center;
				gap: 5px;
				background: #f1f5f9;
				border: 1px solid #e2e8f0;
				color: #475569 !important;
				border-radius: 6px;
				padding: 5px 12px;
				font-size: 12px;
				font-weight: 600;
				margin-bottom: 12px;
				text-decoration: none;
				-webkit-transition: background .15s;
				transition: background .15s;
			}
			.page-content .glr-btn-back:hover {
				background: #e0f2fe;
				border-color: #0ea5e9;
				color: #0369a1 !important;
				text-decoration: none;
			}

			/* ===== Report title h4 ===== */
			.page-content .glr-form-title {
				font-size: 13.5px;
				font-weight: 700;
				color: #0369a1;
				border-left: 3px solid #0ea5e9;
				padding-left: 10px;
				margin: 0 0 16px;
				line-height: 1.4;
			}

			/* ===== RL sub-menu list ===== */
			.page-content .glr-rl-title {
				font-size: 14px;
				font-weight: 700;
				color: #1e3a5f;
				margin-bottom: 14px;
				padding-bottom: 8px;
				border-bottom: 1px solid #e2e8f0;
			}
			.glr-rl-list {
				list-style: none;
				padding: 0;
				margin: 0;
			}
			.glr-rl-list li {
				border-bottom: 1px solid #f1f5f9;
			}
			.glr-rl-list li:last-child { border-bottom: none; }
			.glr-rl-list li a {
				display: -webkit-flex;
				display: flex;
				-webkit-align-items: center;
				align-items: center;
				gap: 8px;
				padding: 9px 8px;
				color: #334155;
				font-size: 13px;
				text-decoration: none;
				border-radius: 5px;
				-webkit-transition: background .15s, color .15s, padding-left .14s;
				transition: background .15s, color .15s, padding-left .14s;
			}
			.glr-rl-list li a:hover {
				background: #e0f2fe;
				color: #0369a1;
				padding-left: 12px;
				text-decoration: none;
			}
			.glr-rl-list li a::before {
				content: "\f0da";
				font-family: FontAwesome;
				font-size: 11px;
				color: #0ea5e9;
				-webkit-flex-shrink: 0;
				flex-shrink: 0;
			}

			/* ===== Form labels ===== */
			.page-content .form-horizontal .control-label {
				font-size: 12px;
				font-weight: 600;
				color: #374151;
				padding-top: 7px;
			}

			/* ===== Inputs ===== */
			.page-content input.form-control,
			.page-content select.form-control,
			.page-content textarea.form-control {
				font-size: 12.5px;
				border-color: #d1d5db;
				border-radius: 6px;
			}
			.page-content input.form-control:focus,
			.page-content select.form-control:focus {
				border-color: #0ea5e9;
				-webkit-box-shadow: 0 0 0 3px rgba(14,165,233,.12);
				box-shadow: 0 0 0 3px rgba(14,165,233,.12);
			}

			/* ===== Bare <select> without form-control ===== */
			.page-content form select:not(.form-control) {
				display: inline-block;
				height: 32px;
				padding: 4px 8px;
				font-size: 12.5px;
				border: 1px solid #d1d5db;
				border-radius: 6px;
				color: #374151;
				background: #fff;
			}

			/* ===== Input-group calendar addon ===== */
			.page-content .input-group-addon {
				background: #f1f5f9;
				border-color: #d1d5db;
				color: #64748b;
			}

			/* ===== Action row (button row) ===== */
			.page-content .glr-action-row {
				padding-top: 12px;
				margin-top: 4px;
				border-top: 1px solid #f1f5f9;
				display: -webkit-flex;
				display: flex;
				-webkit-flex-wrap: wrap;
				flex-wrap: wrap;
				gap: 6px;
			}

			/* Search / view data button */
			.page-content .glr-btn-search {
				background: -webkit-linear-gradient(135deg, #0369a1, #0ea5e9);
				background: linear-gradient(135deg, #0369a1, #0ea5e9);
				border: none;
				color: #fff !important;
				font-size: 12.5px;
				font-weight: 600;
				border-radius: 6px;
				padding: 6px 16px;
				-webkit-transition: opacity .18s;
				transition: opacity .18s;
			}
			.page-content .glr-btn-search:hover { opacity: .88; color: #fff !important; }

			/* Excel button */
			.page-content .glr-btn-excel {
				background: -webkit-linear-gradient(135deg, #16a34a, #22c55e);
				background: linear-gradient(135deg, #16a34a, #22c55e);
				border: none;
				color: #fff !important;
				font-size: 12.5px;
				font-weight: 600;
				border-radius: 6px;
				padding: 6px 16px;
				-webkit-transition: opacity .18s;
				transition: opacity .18s;
			}
			.page-content .glr-btn-excel:hover { opacity: .88; color: #fff !important; }

			/* Extra action buttons */
			.page-content .glr-btn-extra {
				font-size: 12px;
				border-radius: 6px;
				padding: 6px 12px;
			}

			/* ===== HR divider ===== */
			.page-content hr { border-color: #e2e8f0; }

			/* ===== Footer ===== */
			.footer { background: #fff; border-top: 1px solid #e2e8f0; }
			.footer-content { color: #64748b; font-size: 13px; }
			.footer-content .brand-color { color: #0369a1; font-weight: 700; }
		</style>
	</head>
	<body class="no-skin">
		<div id="navbar" class="navbar navbar-default navbar-collapse h-navbar">
			<script type="text/javascript">
				try{ace.settings.check('navbar' , 'fixed')}catch(e){}
			</script>
			<div class="navbar-container" id="navbar-container">
				<div class="navbar-header pull-left">
					<a href="#" class="navbar-brand">
						<small>
							<i class="fa fa-hospital-o"></i>
							<?php echo strtoupper(COMP_LONG); ?>
						</small>
					</a>
					<button class="pull-right navbar-toggle navbar-toggle-img collapsed" type="button" data-toggle="collapse" data-target=".navbar-buttons,.navbar-menu">
						<span class="sr-only">Toggle user menu</span>
						<img src="<?php echo base_url()?>assets/avatars/user.jpg" alt="User Photo" />
					</button>
					<button class="pull-right navbar-toggle collapsed" type="button" data-toggle="collapse" data-target="#sidebar">
						<span class="sr-only">Toggle sidebar</span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
						<span class="icon-bar"></span>
					</button>
				</div>
			</div>
		</div>

		<div class="main-container" id="main-container">
			<script type="text/javascript">
				try{ace.settings.check('main-container' , 'fixed')}catch(e){}
			</script>

			<div class="main-content">
				<div class="main-content-inner">
					<div class="page-content">
						<br>
						<?php echo $html; ?>
					</div>
				</div>
			</div>

			<div class="footer">
				<div class="footer-inner">
					<div class="footer-content">
						<span class="bigger-120">
							<span class="brand-color"><?php echo APPS_NAME_SORT; ?></span>
							&mdash; <?php echo COMP_LONG; ?> &copy; <?php echo date('Y'); ?>
						</span>
					</div>
				</div>
			</div>

			<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
				<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
			</a>
		</div>

		<!--[if !IE]> -->
		<script type="text/javascript">
			window.jQuery || document.write("<script src='<?php echo base_url()?>assets/js/jquery.js'>"+"<"+"/script>");
		</script>
		<!--[if IE]>
		<script type="text/javascript">
			window.jQuery || document.write("<script src='<?php echo base_url()?>assets/js/jquery1x.js'>"+"<"+"/script>");
		</script>
		<![endif]-->
		<script type="text/javascript">
			if('ontouchstart' in document.documentElement) document.write("<script src='<?php echo base_url()?>assets/js/jquery.mobile.custom.js'>"+"<"+"/script>");
		</script>
		<script src="<?php echo base_url()?>assets/js/bootstrap.js"></script>
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
				.next().on(ace.click_event, function(){
					$(this).prev().focus();
				});
			});
		</script>
	</body>
</html>
