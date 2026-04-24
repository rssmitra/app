<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title><?php echo APPS_NAME_SORT; ?> - Laporan</title>

		<meta name="description" content="Modul Laporan Global" />
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
			/* ===== Base ===== */
			body { background: #f0f4f8 !important; }

			/* ===== Navbar ===== */
			.h-navbar { background: -webkit-linear-gradient(135deg, #0369a1 0%, #0ea5e9 100%) !important; background: linear-gradient(135deg, #0369a1 0%, #0ea5e9 100%) !important; }
			.navbar-brand small { color: #fff; font-size: 15px; font-weight: 600; letter-spacing: .4px; }
			.navbar-brand small i { margin-right: 6px; }

			/* ===== Hero Header ===== */
			.glr-hero {
				background: -webkit-linear-gradient(135deg, #0369a1 0%, #0ea5e9 100%);
				background: linear-gradient(135deg, #0369a1 0%, #0ea5e9 100%);
				padding: 26px 24px 22px;
				color: #fff;
				position: relative;
				overflow: hidden;
			}
			.glr-hero::before {
				content: '';
				position: absolute;
				top: -50px; right: -50px;
				width: 200px; height: 200px;
				border-radius: 50%;
				background: rgba(255,255,255,.07);
				pointer-events: none;
			}
			.glr-hero::after {
				content: '';
				position: absolute;
				bottom: -60px; left: 35%;
				width: 240px; height: 240px;
				border-radius: 50%;
				background: rgba(255,255,255,.04);
				pointer-events: none;
			}
			.glr-hero-title {
				font-size: 20px;
				font-weight: 800;
				margin: 0 0 2px;
				letter-spacing: .3px;
			}
			.glr-hero-sub {
				font-size: 12.5px;
				opacity: .85;
				margin: 0 0 16px;
			}
			.glr-stats {
				display: -webkit-flex;
				display: flex;
				gap: 12px;
				-webkit-flex-wrap: wrap;
				flex-wrap: wrap;
			}
			.glr-stat-card {
				background: rgba(255,255,255,.15);
				border: 1px solid rgba(255,255,255,.22);
				border-radius: 8px;
				padding: 8px 16px;
				font-size: 11.5px;
				text-align: center;
				min-width: 90px;
			}
			.glr-stat-card strong {
				display: block;
				font-size: 22px;
				font-weight: 900;
				line-height: 1.1;
			}

			/* ===== Search Bar ===== */
			.glr-search-wrap {
				background: #fff;
				border-bottom: 1px solid #dde3e8;
				padding: 12px 20px;
			}
			.glr-search-inner {
				position: relative;
				width: 100%;
			}
			.glr-search-input {
				width: 100%;
				padding: 9px 36px 9px 34px;
				border: 1.5px solid #bae6fd;
				border-radius: 8px;
				font-size: 13px;
				background: #f0f9ff;
				color: #0c4a6e;
				outline: none;
				-webkit-box-sizing: border-box;
				box-sizing: border-box;
				-webkit-transition: border-color .2s, box-shadow .2s;
				transition: border-color .2s, box-shadow .2s;
			}
			.glr-search-input:focus {
				border-color: #0369a1;
				background: #fff;
				-webkit-box-shadow: 0 0 0 3px rgba(3,105,161,.1);
				box-shadow: 0 0 0 3px rgba(3,105,161,.1);
			}
			.glr-search-icon {
				position: absolute;
				left: 10px;
				top: 50%;
				-webkit-transform: translateY(-50%);
				transform: translateY(-50%);
				color: #0ea5e9;
				font-size: 13px;
				pointer-events: none;
			}
			.glr-search-clear {
				position: absolute;
				right: 10px;
				top: 50%;
				-webkit-transform: translateY(-50%);
				transform: translateY(-50%);
				color: #94a3b8;
				font-size: 13px;
				cursor: pointer;
				display: none;
				background: none;
				border: none;
				padding: 0;
				line-height: 1;
			}
			.glr-search-hint {
				font-size: 11px;
				color: #94a3b8;
				margin-top: 5px;
			}

			/* ===== Content Area ===== */
			.glr-content { padding: 18px 20px 24px; }

			/* ===== Notice Banner ===== */
			.notice-banner {
				display: -webkit-flex;
				display: flex;
				-webkit-align-items: flex-start;
				align-items: flex-start;
				gap: 10px;
				background: #fff8e1;
				border-left: 4px solid #f0ad4e;
				border-radius: 6px;
				padding: 10px 14px;
				margin-bottom: 18px;
				font-size: 12.5px;
				color: #856404;
			}
			.notice-banner i { font-size: 15px; color: #f0ad4e; -webkit-flex-shrink: 0; flex-shrink: 0; margin-top: 1px; }

			/* ===== Accordion Panel ===== */
			.accordion-style1.panel-group { margin-bottom: 0; }
			.accordion-style1.panel-group .panel {
				border: none;
				border-radius: 8px;
				margin-bottom: 8px;
				-webkit-box-shadow: 0 1px 4px rgba(0,0,0,.07);
				box-shadow: 0 1px 4px rgba(0,0,0,.07);
				overflow: hidden;
			}
			.accordion-style1.panel-group .panel-heading {
				padding: 0;
				background: #fff;
				border: 1.5px solid #e2e8f0;
				border-radius: 8px;
				-webkit-transition: border-color .2s;
				transition: border-color .2s;
			}
			.accordion-style1.panel-group .panel-heading:hover {
				border-color: #0ea5e9;
			}
			.accordion-style1.panel-group .panel-heading .accordion-toggle {
				display: -webkit-flex;
				display: flex;
				-webkit-align-items: center;
				align-items: center;
				gap: 10px;
				padding: 11px 16px;
				color: #1e3a5f !important;
				font-size: 13px;
				font-weight: 700;
				text-decoration: none;
				letter-spacing: .2px;
				-webkit-transition: background .18s;
				transition: background .18s;
			}
			.accordion-style1.panel-group .panel-heading .accordion-toggle:hover {
				background: #f0f9ff;
				text-decoration: none;
			}
			/* Open state */
			.accordion-style1.panel-group .panel-heading.glr-open {
				border-color: #0369a1;
				border-bottom-left-radius: 0;
				border-bottom-right-radius: 0;
			}
			.accordion-style1.panel-group .panel-heading.glr-open .accordion-toggle {
				background: -webkit-linear-gradient(135deg, #0369a1 0%, #0ea5e9 100%);
				background: linear-gradient(135deg, #0369a1 0%, #0ea5e9 100%);
				color: #fff !important;
				border-radius: 6px 6px 0 0;
			}
			.accordion-style1.panel-group .panel-heading.glr-open .cat-icon {
				background: rgba(255,255,255,.2) !important;
				color: #fff !important;
			}
			.accordion-style1.panel-group .panel-heading.glr-open .arrow-icon {
				color: rgba(255,255,255,.9) !important;
				-webkit-transform: rotate(180deg);
				transform: rotate(180deg);
			}
			.accordion-style1.panel-group .panel-heading.glr-open .cat-count {
				background: rgba(255,255,255,.2);
				color: #fff;
				border-color: rgba(255,255,255,.3);
			}

			/* ===== Category Icon ===== */
			.cat-icon {
				display: -webkit-inline-flex;
				display: inline-flex;
				-webkit-align-items: center;
				align-items: center;
				-webkit-justify-content: center;
				justify-content: center;
				width: 30px;
				height: 30px;
				border-radius: 8px;
				font-size: 12px;
				-webkit-flex-shrink: 0;
				flex-shrink: 0;
				-webkit-transition: background .18s, color .18s;
				transition: background .18s, color .18s;
			}
			/* Per-category icon colors */
			.cat-akunting   { background: #dbeafe; color: #1d4ed8; }
			.cat-pengadaan  { background: #d1fae5; color: #047857; }
			.cat-farmasi    { background: #ede9fe; color: #7c3aed; }
			.cat-so         { background: #ffedd5; color: #c2410c; }
			.cat-kunjungan  { background: #e0f2fe; color: #0369a1; }
			.cat-penunjang  { background: #cffafe; color: #0e7490; }
			.cat-rekammedis { background: #ffe4e6; color: #9f1239; }

			.cat-label { -webkit-flex: 1; flex: 1; }

			/* Count badge */
			.cat-count {
				display: -webkit-inline-flex;
				display: inline-flex;
				-webkit-align-items: center;
				align-items: center;
				-webkit-justify-content: center;
				justify-content: center;
				min-width: 22px;
				height: 22px;
				padding: 0 6px;
				border-radius: 11px;
				background: #f1f5f9;
				color: #475569;
				font-size: 10.5px;
				font-weight: 700;
				border: 1px solid #e2e8f0;
				-webkit-flex-shrink: 0;
				flex-shrink: 0;
				-webkit-transition: background .18s, color .18s, border-color .18s;
				transition: background .18s, color .18s, border-color .18s;
			}

			.arrow-icon {
				color: #94a3b8;
				font-size: 12px;
				-webkit-flex-shrink: 0;
				flex-shrink: 0;
				-webkit-transition: -webkit-transform .25s, color .18s;
				transition: transform .25s, color .18s;
			}

			/* ===== Report List ===== */
			.panel-body {
				padding: 6px 12px 10px;
				background: #fff;
				border: 1.5px solid #0369a1;
				border-top: none;
				border-radius: 0 0 8px 8px;
			}
			.report-list { list-style: none; padding: 0; margin: 0; }
			.report-list > li { border-bottom: 1px solid #f1f5f9; }
			.report-list > li:last-child { border-bottom: none; }
			.report-list > li.glr-hidden { display: none; }
			.report-list > li > a {
				display: -webkit-flex;
				display: flex;
				-webkit-align-items: center;
				align-items: center;
				gap: 8px;
				padding: 7px 8px;
				color: #334155;
				font-size: 12.5px;
				text-decoration: none;
				border-radius: 5px;
				-webkit-transition: background .14s, color .14s, padding-left .14s;
				transition: background .14s, color .14s, padding-left .14s;
			}
			.report-list > li > a:hover {
				background: #f0f9ff;
				color: #0369a1;
				padding-left: 12px;
				text-decoration: none;
			}
			.report-list > li > a .report-arrow {
				color: #0ea5e9;
				font-size: 10px;
				-webkit-flex-shrink: 0;
				flex-shrink: 0;
			}
			.report-num {
				display: -webkit-inline-flex;
				display: inline-flex;
				-webkit-align-items: center;
				align-items: center;
				-webkit-justify-content: center;
				justify-content: center;
				min-width: 20px;
				height: 20px;
				border-radius: 5px;
				background: #f1f5f9;
				color: #64748b;
				font-size: 10px;
				font-weight: 700;
				-webkit-flex-shrink: 0;
				flex-shrink: 0;
			}
			.report-name { -webkit-flex: 1; flex: 1; line-height: 1.4; }
			.badge-star   { color: #f59e0b; font-size: 10px; margin-left: 3px; }
			.badge-book   { color: #10b981; font-size: 10px; margin-left: 3px; }
			/* Highlight on search match */
			mark.glr-hl { background: #fef08a; color: #713f12; border-radius: 2px; padding: 0 1px; }

			/* ===== Empty Search State ===== */
			.glr-no-result {
				text-align: center;
				padding: 28px 16px;
				color: #94a3b8;
				font-size: 13px;
				display: none;
			}
			.glr-no-result i { font-size: 28px; display: block; margin-bottom: 8px; }

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

			<div class="main-content" style="margin-left:0!important">
				<div class="main-content-inner">

					<!-- Hero Header -->
					<div class="glr-hero">
						<div class="glr-hero-title"><i class="fa fa-bar-chart" style="margin-right:8px;opacity:.9"></i>Modul Laporan &mdash; <?php echo APPS_NAME_SORT?></div>
						<div class="glr-hero-sub"><i class="fa fa-angle-right" style="margin-right:4px"></i>Pencarian laporan umum seluruh unit &mdash; <?php echo COMP_LONG; ?></div>
						<div class="glr-stats">
							<div class="glr-stat-card">
								<strong>7</strong>
								Kategori
							</div>
							<div class="glr-stat-card">
								<strong>48</strong>
								Laporan
							</div>
							<div class="glr-stat-card">
								<strong><i class="fa fa-star" style="font-size:14px"></i></strong>
								Unggulan
							</div>
						</div>
					</div>

					<!-- Search Bar -->
					<div class="glr-search-wrap">
						<div class="glr-search-inner">
							<i class="fa fa-search glr-search-icon"></i>
							<input type="text" id="glrSearch" class="glr-search-input" placeholder="Cari laporan... (contoh: kunjungan, stok, kasir)" autocomplete="off" />
							<button type="button" class="glr-search-clear" id="glrSearchClear" title="Hapus pencarian"><i class="fa fa-times"></i></button>
						</div>
						<div class="glr-search-hint" id="glrSearchHint">Ketik untuk menyaring laporan dari semua kategori</div>
					</div>

					<div class="glr-content">

						<!-- Notice Banner -->
						<div class="notice-banner">
							<i class="fa fa-info-circle"></i>
							<span><strong>Pemberitahuan:</strong> Bagi unit/bagian yang membutuhkan laporan, harap segera memberikan format laporan ke bagian IT.</span>
						</div>

						<div id="accordion" class="accordion-style1 panel-group">

							<!-- AKUNTING & KEUANGAN -->
							<div class="panel panel-default glr-panel">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseAkunting">
											<span class="cat-icon cat-akunting"><i class="fa fa-money"></i></span>
											<span class="cat-label">AKUNTING &amp; KEUANGAN</span>
											<span class="cat-count">11</span>
											<i class="fa fa-chevron-down arrow-icon"></i>
										</a>
									</h4>
								</div>
								<div class="panel-collapse collapse" id="collapseAkunting">
									<div class="panel-body">
										<ul class="report-list">
											<li><a href="<?php echo base_url().'laporan/Global_report/akunting?mod=1'?>"><i class="fa fa-caret-right report-arrow"></i><span class="report-num">1</span><span class="report-name">Setoran Harian Kasir</span></a></li>
											<li><a href="<?php echo base_url().'laporan/Global_report/akunting?mod=2'?>"><i class="fa fa-caret-right report-arrow"></i><span class="report-num">2</span><span class="report-name">Transaksi Pasien BPJS <i class="fa fa-star badge-star"></i></span></a></li>
											<li><a href="<?php echo base_url().'laporan/Global_report/akunting?mod=7'?>"><i class="fa fa-caret-right report-arrow"></i><span class="report-num">3</span><span class="report-name">Transaksi Pasien Asuransi <i class="fa fa-star badge-star"></i></span></a></li>
											<li><a href="<?php echo base_url().'laporan/Global_report/akunting?mod=5'?>"><i class="fa fa-caret-right report-arrow"></i><span class="report-num">4</span><span class="report-name">Resume Laporan Kasir</span></a></li>
											<li><a href="<?php echo base_url().'laporan/Global_report/akunting?mod=3'?>"><i class="fa fa-caret-right report-arrow"></i><span class="report-num">5</span><span class="report-name">Rekapitulasi Stok Awal Bulan, Penerimaan/Pembelian, Penjualan, BMHP dan Saldo Akhir <i class="fa fa-star badge-star"></i></span></a></li>
											<li><a href="<?php echo base_url().'laporan/Global_report/akunting?mod=8'?>"><i class="fa fa-caret-right report-arrow"></i><span class="report-num">6</span><span class="report-name">Rekapitulasi Stok Awal Bulan, Penerimaan, Distribusi dan Saldo Akhir Gudang Non Medis <i class="fa fa-star badge-star"></i></span></a></li>
											<li><a href="<?php echo base_url().'laporan/Global_report/akunting?mod=4'?>"><i class="fa fa-caret-right report-arrow"></i><span class="report-num">7</span><span class="report-name">Laporan IF (Unit Farmasi)</span></a></li>
											<li><a href="<?php echo base_url().'laporan/Global_report/lainnyabillingdokter?mod=1'?>"><i class="fa fa-caret-right report-arrow"></i><span class="report-num">8</span><span class="report-name">Daftar Billing Dokter yang Belum Dibayarkan Per-periode</span></a></li>
											<li><a href="<?php echo base_url().'laporan/Global_report/akunting?mod=5'?>"><i class="fa fa-caret-right report-arrow"></i><span class="report-num">9</span><span class="report-name">Stok Barang Medis Per-periode</span></a></li>
											<li><a href="<?php echo base_url().'laporan/Global_report/akunting?mod=6'?>"><i class="fa fa-caret-right report-arrow"></i><span class="report-num">10</span><span class="report-name">Stok Barang Non Medis Per-periode</span></a></li>
											<li><a href="<?php echo base_url().'laporan/Global_report/master_tarif'?>"><i class="fa fa-caret-right report-arrow"></i><span class="report-num">11</span><span class="report-name">Master Tarif</span></a></li>
										</ul>
									</div>
								</div>
							</div>

							<!-- PENGADAAN DAN GUDANG -->
							<div class="panel panel-default glr-panel">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapsePurchasing">
											<span class="cat-icon cat-pengadaan"><i class="fa fa-truck"></i></span>
											<span class="cat-label">PENGADAAN DAN GUDANG</span>
											<span class="cat-count">13</span>
											<i class="fa fa-chevron-down arrow-icon"></i>
										</a>
									</h4>
								</div>
								<div class="panel-collapse collapse" id="collapsePurchasing">
									<div class="panel-body">
										<ul class="report-list">
											<li><a href="<?php echo base_url().'laporan/Global_report/pengadaan?mod=1'?>"><i class="fa fa-caret-right report-arrow"></i><span class="report-num">1</span><span class="report-name">Laporan Stok Akhir Barang Non Medis Berdasarkan Tanggal Terakhir Stok <i class="fa fa-star badge-star"></i></span></a></li>
											<li><a href="<?php echo base_url().'laporan/Global_report/pengadaan?mod=8'?>"><i class="fa fa-caret-right report-arrow"></i><span class="report-num">2</span><span class="report-name">Laporan Stok Akhir Barang Non Medis Berdasarkan Master Barang</span></a></li>
											<li><a href="<?php echo base_url().'laporan/Global_report/farmasi?mod=3'?>"><i class="fa fa-caret-right report-arrow"></i><span class="report-num">3</span><span class="report-name">Laporan Penerimaan Barang</span></a></li>
											<li><a href="<?php echo base_url().'laporan/Global_report/pengadaan?mod=10'?>"><i class="fa fa-caret-right report-arrow"></i><span class="report-num">4</span><span class="report-name">Laporan Penerimaan Barang Detail</span></a></li>
											<li><a href="<?php echo base_url().'laporan/Global_report/pengadaan?mod=2'?>"><i class="fa fa-caret-right report-arrow"></i><span class="report-num">5</span><span class="report-name">Laporan Distribusi Barang Unit <i class="fa fa-bookmark badge-book"></i></span></a></li>
											<li><a href="<?php echo base_url().'laporan/Global_report/pengadaan?mod=3'?>"><i class="fa fa-caret-right report-arrow"></i><span class="report-num">6</span><span class="report-name">Rekap Biaya Distribusi Barang Per Unit <i class="fa fa-bookmark badge-book"></i></span></a></li>
											<li><a href="<?php echo base_url().'laporan/Global_report/pengadaan?mod=7'?>"><i class="fa fa-caret-right report-arrow"></i><span class="report-num">7</span><span class="report-name">Rekap Barang Keluar Berdasarkan Item Barang <i class="fa fa-bookmark badge-book"></i></span></a></li>
											<li><a href="<?php echo base_url().'laporan/Global_report/pengadaan?mod=4'?>" target="_blank"><i class="fa fa-caret-right report-arrow"></i><span class="report-num">8</span><span class="report-name">Laporan Permintaan Pembelian <i class="fa fa-star badge-star"></i></span></a></li>
											<li><a href="<?php echo base_url().'laporan/Global_report/pengadaan?mod=5'?>"><i class="fa fa-caret-right report-arrow"></i><span class="report-num">9</span><span class="report-name">Laporan Monitoring Usulan Permintaan <i class="fa fa-star badge-star"></i></span></a></li>
											<li><a href="<?php echo base_url().'laporan/Global_report/pengadaan?mod=6'?>"><i class="fa fa-caret-right report-arrow"></i><span class="report-num">10</span><span class="report-name">Rekap Pembelian Barang Berdasarkan Supplier <i class="fa fa-star badge-star"></i></span></a></li>
											<li><a href="<?php echo base_url().'laporan/Global_report/pengadaan?mod=9'?>"><i class="fa fa-caret-right report-arrow"></i><span class="report-num">11</span><span class="report-name">Laporan Mutasi Per-periode</span></a></li>
											<li><a href="<?php echo base_url().'laporan/Global_report/pengadaan?mod=11'?>"><i class="fa fa-caret-right report-arrow"></i><span class="report-num">12</span><span class="report-name">Laporan PO Donasi</span></a></li>
											<li><a href="<?php echo base_url().'laporan/Global_report/pengadaan?mod=12'?>"><i class="fa fa-caret-right report-arrow"></i><span class="report-num">13</span><span class="report-name">Laporan Pengeluaran Obat per Periode Berdasarkan Mutasi Barang <i class="fa fa-star badge-star"></i></span></a></li>
										</ul>
									</div>
								</div>
							</div>

							<!-- FARMASI -->
							<div class="panel panel-default glr-panel">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseFarmasi">
											<span class="cat-icon cat-farmasi"><i class="fa fa-medkit"></i></span>
											<span class="cat-label">FARMASI</span>
											<span class="cat-count">13</span>
											<i class="fa fa-chevron-down arrow-icon"></i>
										</a>
									</h4>
								</div>
								<div class="panel-collapse collapse" id="collapseFarmasi">
									<div class="panel-body">
										<ul class="report-list">
											<li><a href="<?php echo base_url().'laporan/Global_report/farmasi?mod=1'?>"><i class="fa fa-caret-right report-arrow"></i><span class="report-num">1</span><span class="report-name">Laporan Keluar/Masuk Obat &amp; Alkes Berdasarkan Tahun</span></a></li>
											<li><a href="<?php echo base_url().'laporan/Global_report/farmasi?mod=2'?>"><i class="fa fa-caret-right report-arrow"></i><span class="report-num">2</span><span class="report-name">Laporan Stok Akhir Barang Medis Berdasarkan Mutasi Tanggal Terakhir</span></a></li>
											<li><a href="<?php echo base_url().'laporan/Global_report/farmasi?mod=4'?>"><i class="fa fa-caret-right report-arrow"></i><span class="report-num">3</span><span class="report-name">Laporan Bon Obat</span></a></li>
											<li><a href="<?php echo base_url().'laporan/Global_report/farmasi?mod=5'?>"><i class="fa fa-caret-right report-arrow"></i><span class="report-num">4</span><span class="report-name">Laporan Mutasi Obat &amp; Alkes Per-periode</span></a></li>
											<li><a href="<?php echo base_url().'laporan/Global_report/farmasi?mod=6'?>"><i class="fa fa-caret-right report-arrow"></i><span class="report-num">5</span><span class="report-name">Laporan Penjualan Jenis Obat Racikan/Non Racikan</span></a></li>
											<li><a href="<?php echo base_url().'laporan/Global_report/farmasi?mod=7'?>"><i class="fa fa-caret-right report-arrow"></i><span class="report-num">6</span><span class="report-name">Laporan Pembelian Obat Cito</span></a></li>
											<li><a href="<?php echo base_url().'laporan/Global_report/farmasi?mod=8'?>"><i class="fa fa-caret-right report-arrow"></i><span class="report-num">7</span><span class="report-name">Laporan Pemesanan Resep</span></a></li>
											<li><a href="<?php echo base_url().'laporan/Global_report/farmasi?mod=9'?>"><i class="fa fa-caret-right report-arrow"></i><span class="report-num">8</span><span class="report-name">Laporan Penjualan Obat Per Kategori</span></a></li>
											<li><a href="<?php echo base_url().'laporan/Global_report/lappembelian'?>"><i class="fa fa-caret-right report-arrow"></i><span class="report-num">9</span><span class="report-name">Laporan Pembelian Obat (Operational Level)</span></a></li>
											<li><a href="<?php echo base_url().'laporan/Global_report/farmasi?mod=10'?>"><i class="fa fa-caret-right report-arrow"></i><span class="report-num">10</span><span class="report-name">Laporan Mutasi Distribusi Barang Unit <i class="fa fa-bookmark badge-book"></i></span></a></li>
											<li><a href="<?php echo base_url().'laporan/Global_report/farmasi?mod=11'?>"><i class="fa fa-caret-right report-arrow"></i><span class="report-num">11</span><span class="report-name">Laporan Pemakaian Unit</span></a></li>
											<li><a href="<?php echo base_url().'laporan/Global_report/farmasi?mod=12'?>"><i class="fa fa-caret-right report-arrow"></i><span class="report-num">12</span><span class="report-name">Rekapitulasi Jumlah Resep <i class="fa fa-star badge-star"></i></span></a></li>
											<li><a href="<?php echo base_url().'laporan/Global_report/farmasi?mod=13'?>"><i class="fa fa-caret-right report-arrow"></i><span class="report-num">13</span><span class="report-name">Rekapitulasi Hutang Obat ke Pasien <i class="fa fa-star badge-star"></i></span></a></li>
										</ul>
									</div>
								</div>
							</div>

							<!-- STOK OPNAME -->
							<div class="panel panel-default glr-panel">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseSo">
											<span class="cat-icon cat-so"><i class="fa fa-clipboard"></i></span>
											<span class="cat-label">STOK OPNAME</span>
											<span class="cat-count">3</span>
											<i class="fa fa-chevron-down arrow-icon"></i>
										</a>
									</h4>
								</div>
								<div class="panel-collapse collapse" id="collapseSo">
									<div class="panel-body">
										<ul class="report-list">
											<li><a href="<?php echo base_url().'laporan/Global_report/so?mod=1'?>"><i class="fa fa-caret-right report-arrow"></i><span class="report-num">1</span><span class="report-name">Daftar Barang Yang Akan di Stok Opname</span></a></li>
											<li><a href="<?php echo base_url().'laporan/Global_report/so?mod=2'?>"><i class="fa fa-caret-right report-arrow"></i><span class="report-num">2</span><span class="report-name">Laporan Hasil SO</span></a></li>
											<li><a href="<?php echo base_url().'laporan/Global_report/so?mod=3'?>"><i class="fa fa-caret-right report-arrow"></i><span class="report-num">3</span><span class="report-name">Laporan Sebelum SO</span></a></li>
										</ul>
									</div>
								</div>
							</div>

							<!-- KUNJUNGAN PASIEN RAWAT JALAN -->
							<div class="panel panel-default glr-panel">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseKunjunganRj">
											<span class="cat-icon cat-kunjungan"><i class="fa fa-users"></i></span>
											<span class="cat-label">KUNJUNGAN PASIEN RAWAT JALAN</span>
											<span class="cat-count">5</span>
											<i class="fa fa-chevron-down arrow-icon"></i>
										</a>
									</h4>
								</div>
								<div class="panel-collapse collapse" id="collapseKunjunganRj">
									<div class="panel-body">
										<ul class="report-list">
											<li><a href="<?php echo base_url().'laporan/Global_report/kunjungan?mod=1'?>"><i class="fa fa-caret-right report-arrow"></i><span class="report-num">1</span><span class="report-name">Daftar Kunjungan Pasien Berdasarkan Usia dan Tahun Kunjungan</span></a></li>
											<li><a href="<?php echo base_url().'laporan/Global_report/kunjungan?mod=2'?>"><i class="fa fa-caret-right report-arrow"></i><span class="report-num">2</span><span class="report-name">Daftar Kunjungan Pasien Per-hari</span></a></li>
											<li><a href="<?php echo base_url().'laporan/Global_report/kunjungan?mod=3'?>"><i class="fa fa-caret-right report-arrow"></i><span class="report-num">3</span><span class="report-name">Daftar Registrasi Pasien Per-hari</span></a></li>
											<li><a href="<?php echo base_url().'laporan/Global_report/kunjungan?mod=4'?>"><i class="fa fa-caret-right report-arrow"></i><span class="report-num">4</span><span class="report-name">Daftar Pasien MCU</span></a></li>
											<li><a href="<?php echo base_url().'laporan/Global_report/kunjungan?mod=5'?>"><i class="fa fa-caret-right report-arrow"></i><span class="report-num">5</span><span class="report-name">Data Keterlambatan Kunjungan Praktek Dokter <i class="fa fa-star badge-star"></i></span></a></li>
										</ul>
									</div>
								</div>
							</div>

							<!-- PENUNJANG MEDIS -->
							<div class="panel panel-default glr-panel">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapsePenunjangMedis">
											<span class="cat-icon cat-penunjang"><i class="fa fa-stethoscope"></i></span>
											<span class="cat-label">PENUNJANG MEDIS</span>
											<span class="cat-count">2</span>
											<i class="fa fa-chevron-down arrow-icon"></i>
										</a>
									</h4>
								</div>
								<div class="panel-collapse collapse" id="collapsePenunjangMedis">
									<div class="panel-body">
										<ul class="report-list">
											<li><a href="<?php echo base_url().'laporan/Global_report/lapkinerja'?>"><i class="fa fa-caret-right report-arrow"></i><span class="report-num">1</span><span class="report-name">Laporan Kinerja</span></a></li>
											<li><a href="<?php echo base_url().'laporan/Global_report/lapkunjungan'?>"><i class="fa fa-caret-right report-arrow"></i><span class="report-num">2</span><span class="report-name">Laporan Kunjungan</span></a></li>
										</ul>
									</div>
								</div>
							</div>

							<!-- REKAM MEDIS PASIEN -->
							<div class="panel panel-default glr-panel">
								<div class="panel-heading">
									<h4 class="panel-title">
										<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseRekamMedis">
											<span class="cat-icon cat-rekammedis"><i class="fa fa-file-text-o"></i></span>
											<span class="cat-label">REKAM MEDIS PASIEN</span>
											<span class="cat-count">1</span>
											<i class="fa fa-chevron-down arrow-icon"></i>
										</a>
									</h4>
								</div>
								<div class="panel-collapse collapse" id="collapseRekamMedis">
									<div class="panel-body">
										<ul class="report-list">
											<li><a href="<?php echo base_url().'laporan/Global_report/laporanrl'?>"><i class="fa fa-caret-right report-arrow"></i><span class="report-num">1</span><span class="report-name">Laporan RL</span></a></li>
										</ul>
									</div>
								</div>
							</div>

						</div><!-- /#accordion -->

						<!-- No result state -->
						<div class="glr-no-result" id="glrNoResult">
							<i class="fa fa-search"></i>
							Tidak ada laporan yang cocok dengan pencarian Anda.
						</div>

					</div><!-- /.glr-content -->

				</div>
			</div><!-- /.main-content -->

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
		</div><!-- /.main-container -->

		<!--[if !IE]> -->
		<script type="text/javascript">
			window.jQuery || document.write("<script src='<?php echo base_url()?>assets/js/jquery.js'>"+"<"+"/script>");
		</script>
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

		<script>
			/* ---- Accordion chevron + open state ---- */
			$(document).on('show.bs.collapse', '.panel-collapse', function () {
				$(this).closest('.panel').find('.panel-heading').addClass('glr-open');
			});
			$(document).on('hide.bs.collapse', '.panel-collapse', function () {
				$(this).closest('.panel').find('.panel-heading').removeClass('glr-open');
			});

			/* ---- Report Search / Filter ---- */
			(function () {
				var $input    = $('#glrSearch');
				var $clear    = $('#glrSearchClear');
				var $hint     = $('#glrSearchHint');
				var $noResult = $('#glrNoResult');

				function escapeRe(s) {
					return s.replace(/[.*+?^${}()|[\]\\]/g, '\\$&');
				}

				function highlight(text, q) {
					if (!q) return text;
					return text.replace(new RegExp('(' + escapeRe(q) + ')', 'gi'), '<mark class="glr-hl">$1</mark>');
				}

				function doSearch(q) {
					var query   = $.trim(q).toLowerCase();
					var visible = 0;

					// Restore all items to original text first
					$('.report-list .report-name').each(function () {
						var $n = $(this);
						if ($n.data('orig')) {
							$n.html($n.data('orig'));
						}
					});

					if (!query) {
						// Reset — show all panels and items
						$('.glr-panel').show();
						$('.report-list > li').removeClass('glr-hidden').show();
						$noResult.hide();
						$hint.text('Ketik untuk menyaring laporan dari semua kategori');
						return;
					}

					var totalMatch = 0;

					$('.glr-panel').each(function () {
						var $panel      = $(this);
						var $items      = $panel.find('.report-list > li');
						var panelMatch  = 0;

						$items.each(function () {
							var $li   = $(this);
							var $name = $li.find('.report-name');

							// Save original HTML once
							if (!$name.data('orig')) {
								$name.data('orig', $name.html());
							}

							var text = $name.data('orig').replace(/<[^>]*>/g, '').toLowerCase();
							if (text.indexOf(query) !== -1) {
								$li.removeClass('glr-hidden').show();
								$name.html(highlight($name.data('orig'), q));
								panelMatch++;
							} else {
								$li.addClass('glr-hidden').hide();
							}
						});

						if (panelMatch > 0) {
							$panel.show();
							// Auto-expand panels with matches
							var $collapse = $panel.find('.panel-collapse');
							if (!$collapse.hasClass('in')) {
								$collapse.collapse('show');
							}
							totalMatch += panelMatch;
						} else {
							$panel.hide();
						}
					});

					if (totalMatch === 0) {
						$noResult.show();
						$hint.text('Tidak ada hasil ditemukan');
					} else {
						$noResult.hide();
						$hint.text('Ditemukan ' + totalMatch + ' laporan');
					}
				}

				$input.on('input', function () {
					var q = $(this).val();
					doSearch(q);
					$clear.toggle(q.length > 0);
				});

				$clear.on('click', function () {
					$input.val('').trigger('input').focus();
				});
			})();
		</script>
	</body>
</html>
