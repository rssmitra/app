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
			/* ===== CSS Variables ===== */
			.glr-wrap { --clr: #00b8a8; --clr-dk: #009e90; --clr-lt: #e0f7f5; }

			/* ===== Navbar ===== */
			.h-navbar { background-color: #00b8a8 !important; }
			.navbar-brand small { color: #fff; font-size: 15px; font-weight: 600; letter-spacing: .4px; }
			.navbar-brand small i { margin-right: 6px; }

			/* ===== Page Header ===== */
			.page-header { border-bottom: 2px solid #00b8a8; margin-bottom: 20px; padding-bottom: 10px; }
			.page-header h1 { font-size: 20px; font-weight: 700; color: #2c3e50; }
			.page-header h1 small { font-size: 13px; color: #7f8c8d; }

			/* ===== Notice Banner ===== */
			.notice-banner {
				display: -webkit-flex;
				display: flex;
				-webkit-align-items: center;
				align-items: center;
				gap: 10px;
				background: #fff8e1;
				border-left: 4px solid #f0ad4e;
				border-radius: 4px;
				padding: 12px 16px;
				margin-bottom: 20px;
				font-size: 13px;
				color: #856404;
			}
			.notice-banner i { font-size: 17px; color: #f0ad4e; -webkit-flex-shrink: 0; flex-shrink: 0; }

			/* ===== Accordion Panel ===== */
			.accordion-style1.panel-group .panel {
				border: none;
				border-radius: 6px;
				margin-bottom: 8px;
				-webkit-box-shadow: 0 1px 4px rgba(0,0,0,.08);
				box-shadow: 0 1px 4px rgba(0,0,0,.08);
				overflow: hidden;
			}
			.accordion-style1.panel-group .panel-heading {
				padding: 0;
				background: #fff;
				border: 1px solid #dde3e8;
				border-radius: 6px;
				-webkit-transition: border-color .2s;
				transition: border-color .2s;
			}
			.accordion-style1.panel-group .panel-heading:hover {
				border-color: #00b8a8;
			}
			.accordion-style1.panel-group .panel-heading .accordion-toggle {
				display: -webkit-flex;
				display: flex;
				-webkit-align-items: center;
				align-items: center;
				gap: 10px;
				padding: 12px 16px;
				color: #2c3e50 !important;
				font-size: 13px;
				font-weight: 600;
				text-decoration: none;
				background-color: #fff;
				border-radius: 6px;
				-webkit-transition: background .18s, color .18s;
				transition: background .18s, color .18s;
			}
			.accordion-style1.panel-group .panel-heading .accordion-toggle:hover {
				background-color: #e0f7f5;
				color: #009e90 !important;
			}
			/* Expanded state — driven by JS class toggling */
			.accordion-style1.panel-group .panel-heading.glr-open .accordion-toggle {
				background-color: #00b8a8;
				color: #fff !important;
				border-radius: 6px 6px 0 0;
			}
			.accordion-style1.panel-group .panel-heading.glr-open .cat-icon {
				background: rgba(255,255,255,.2);
				color: #fff;
			}
			.accordion-style1.panel-group .panel-heading.glr-open .arrow-icon {
				color: #fff;
				-webkit-transform: rotate(180deg);
				transform: rotate(180deg);
			}
			.accordion-style1.panel-group .panel-heading.glr-open {
				border-color: #00b8a8;
			}

			/* ===== Category Icon Badge ===== */
			.cat-icon {
				display: -webkit-inline-flex;
				display: inline-flex;
				-webkit-align-items: center;
				align-items: center;
				-webkit-justify-content: center;
				justify-content: center;
				width: 28px;
				height: 28px;
				border-radius: 50%;
				background: #e0f7f5;
				color: #00b8a8;
				font-size: 12px;
				-webkit-flex-shrink: 0;
				flex-shrink: 0;
				-webkit-transition: background .18s, color .18s;
				transition: background .18s, color .18s;
			}
			.cat-label { -webkit-flex: 1; flex: 1; }
			.arrow-icon {
				color: #b0bec5;
				font-size: 12px;
				-webkit-transition: transform .25s, color .18s;
				transition: transform .25s, color .18s;
			}

			/* ===== Report List ===== */
			.panel-body {
				padding: 8px 14px 12px;
				background: #f9fafb;
				border-top: 1px solid #e8edf1;
				border-radius: 0 0 6px 6px;
			}
			.report-list { list-style: none; padding: 0; margin: 0; }
			.report-list > li { border-bottom: 1px solid #eff2f5; }
			.report-list > li:last-child { border-bottom: none; }
			.report-list > li > a {
				display: -webkit-flex;
				display: flex;
				-webkit-align-items: center;
				align-items: center;
				gap: 8px;
				padding: 8px 8px;
				color: #34495e;
				font-size: 12.5px;
				text-decoration: none;
				border-radius: 4px;
				-webkit-transition: background .15s, color .15s;
				transition: background .15s, color .15s;
			}
			.report-list > li > a:hover {
				background: #e0f7f5;
				color: #009e90;
				text-decoration: none;
			}
			.report-list > li > a .report-arrow {
				color: #00b8a8;
				font-size: 10px;
				-webkit-flex-shrink: 0;
				flex-shrink: 0;
			}
			.report-num {
				display: inline-block;
				min-width: 20px;
				height: 20px;
				line-height: 20px;
				text-align: center;
				border-radius: 50%;
				background: #ecf0f1;
				color: #95a5a6;
				font-size: 10px;
				font-weight: 700;
				-webkit-flex-shrink: 0;
				flex-shrink: 0;
			}
			.report-name { -webkit-flex: 1; flex: 1; }
			.badge-star { color: #e67e22; font-size: 10px; margin-left: 2px; }
			.badge-book { color: #27ae60; font-size: 10px; margin-left: 2px; }

			/* ===== Footer ===== */
			.footer { background: #fff; border-top: 1px solid #e8edf1; }
			.footer-content { color: #7f8c8d; font-size: 13px; }
			.footer-content .brand-color { color: #00b8a8; font-weight: 700; }
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

		<div class="main-container glr-wrap" id="main-container">
			<script type="text/javascript">
				try{ace.settings.check('main-container' , 'fixed')}catch(e){}
			</script>

			<div class="main-content">
				<div class="main-content-inner">
					<div class="page-content">

						<div class="page-header">
							<h1>
								Modul Laporan &mdash; <?php echo APPS_NAME_SORT?>
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									Pencarian laporan umum seluruh unit
								</small>
							</h1>
						</div>

						<div class="row">
							<div class="col-xs-12">

								<!-- Notice Banner -->
								<div class="notice-banner">
									<i class="fa fa-info-circle"></i>
									<span><strong>Pemberitahuan:</strong> Bagi unit/bagian yang membutuhkan laporan, harap segera memberikan format laporan ke bagian IT.</span>
								</div>

								<div id="accordion" class="accordion-style1 panel-group">

									<!-- AKUNTING & KEUANGAN -->
									<div class="panel panel-default">
										<div class="panel-heading">
											<h4 class="panel-title">
												<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseAkunting">
													<span class="cat-icon"><i class="fa fa-money"></i></span>
													<span class="cat-label">AKUNTING &amp; KEUANGAN</span>
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
									<div class="panel panel-default">
										<div class="panel-heading">
											<h4 class="panel-title">
												<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapsePurchasing">
													<span class="cat-icon"><i class="fa fa-truck"></i></span>
													<span class="cat-label">PENGADAAN DAN GUDANG</span>
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
									<div class="panel panel-default">
										<div class="panel-heading">
											<h4 class="panel-title">
												<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseFarmasi">
													<span class="cat-icon"><i class="fa fa-medkit"></i></span>
													<span class="cat-label">FARMASI</span>
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
									<div class="panel panel-default">
										<div class="panel-heading">
											<h4 class="panel-title">
												<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseSo">
													<span class="cat-icon"><i class="fa fa-clipboard"></i></span>
													<span class="cat-label">STOK OPNAME</span>
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
									<div class="panel panel-default">
										<div class="panel-heading">
											<h4 class="panel-title">
												<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseKunjunganRj">
													<span class="cat-icon"><i class="fa fa-users"></i></span>
													<span class="cat-label">KUNJUNGAN PASIEN RAWAT JALAN</span>
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
									<div class="panel panel-default">
										<div class="panel-heading">
											<h4 class="panel-title">
												<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapsePenunjangMedis">
													<span class="cat-icon"><i class="fa fa-stethoscope"></i></span>
													<span class="cat-label">PENUNJANG MEDIS</span>
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
									<div class="panel panel-default">
										<div class="panel-heading">
											<h4 class="panel-title">
												<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseRekamMedis">
													<span class="cat-icon"><i class="fa fa-file-text-o"></i></span>
													<span class="cat-label">REKAM MEDIS PASIEN</span>
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

							</div><!-- /.col -->
						</div><!-- /.row -->
					</div><!-- /.page-content -->
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
			/* Rotate chevron & highlight header when accordion opens */
			$(document).on('show.bs.collapse', '.panel-collapse', function () {
				$(this).closest('.panel').find('.panel-heading').addClass('glr-open');
			});
			$(document).on('hide.bs.collapse', '.panel-collapse', function () {
				$(this).closest('.panel').find('.panel-heading').removeClass('glr-open');
			});
		</script>
	</body>
</html>
