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
						<!-- /section:settings.box -->
						<div class="page-header">
							<h1>
								Modul Laporan SIRS
								<small>
									<i class="ace-icon fa fa-angle-double-right"></i>
									pencarian laporan umum seluruh unit
								</small>
							</h1>
						</div><!-- /.page-header -->

						<div class="row">
							<div class="col-xs-12">
								<!-- PAGE CONTENT BEGINS -->
								<marquee behavior="left" direction="left" style="background-color: #f7c9c9; padding: 5px">
									<strong>Pemberitahuan !</strong>
									Bagi unit/bagian yang membutuhkan laporan, harap segera memberikan format laporan ke bagian IT.
								</marquee>
								<div id="accordion" class="accordion-style1 panel-group">

									<!-- MODUL LAPORAN AKUNTING -->
									<div class="panel panel-default">
									<div class="panel-heading">
										<h4 class="panel-title">
										<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseAkunting" aria-expanded="false">
											<i class="bigger-110 ace-icon fa fa-angle-right" data-icon-hide="ace-icon fa fa-angle-down" data-icon-show="ace-icon fa fa-angle-right"></i>
											&nbsp;AKUNTING & KEUANGAN
										</a>
										</h4>
									</div>
									<div class="panel-collapse collapse" id="collapseAkunting" aria-expanded="false" style="height: 0px;">
										<div class="panel-body">
											<ol>
												<li><a href="<?php echo base_url().'laporan/Global_report/akunting?mod=1'?>">Setoran Harian Kasir</a></li>
												<li><a href="<?php echo base_url().'laporan/Global_report/akunting?mod=2'?>">Transaksi Pasien BPJS</a></li>
												<li><a href="<?php echo base_url().'laporan/Global_report/akunting?mod=5'?>">Resume Laporan Kasir</a></li>
												<li><a href="<?php echo base_url().'laporan/Global_report/akunting?mod=3'?>">Laporan BMHP (Barang Medis Habis Pakai)</a></li>
												<li><a href="<?php echo base_url().'laporan/Global_report/akunting?mod=4'?>">Laporan IF (Unit Farmasi)</a></li>
												<li><a href="<?php echo base_url().'laporan/Global_report/lainnyabillingdokter?mod=1'?>">Daftar Billing Dokter yang belum dibayarkan Per-periode</a></li>
											</ol>
										</div>
									</div>
									</div>
									
									<!-- MODUL PENGADAAN DAN GUDANG -->
									<div class="panel panel-default">
									<div class="panel-heading">
										<h4 class="panel-title">
										<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapsePurchasing" aria-expanded="false">
											<i class="bigger-110 ace-icon fa fa-angle-right" data-icon-hide="ace-icon fa fa-angle-down" data-icon-show="ace-icon fa fa-angle-right"></i>
											&nbsp;PENGADAAN DAN GUDANG
										</a>
										</h4>
									</div>

									<div class="panel-collapse collapse" id="collapsePurchasing" aria-expanded="false" style="height: 0px;">
										<div class="panel-body">
										<ol>
											<li><a href="<?php echo base_url().'laporan/Global_report/pengadaan?mod=1'?>">Laporan Stok Akhir Barang Non Medis Berdasarkan Tanggal Terakhir Stok</a></li>
											<li><a href="<?php echo base_url().'laporan/Global_report/pengadaan?mod=8'?>">Laporan Stok Akhir Barang Non Medis Berdasarkan Master Barang</a></li>
											<li><a href="<?php echo base_url().'laporan/Global_report/farmasi?mod=3'?>">Laporan Penerimaan Barang</a></li>
											<li><a href="<?php echo base_url().'laporan/Global_report/pengadaan?mod=10'?>">Laporan Penerimaan Barang Detail</a></li>
											<li><a href="<?php echo base_url().'laporan/Global_report/pengadaan?mod=2'?>">Laporan Keluar Barang ke Unit Per-periode</a></li>
											<li><a href="<?php echo base_url().'laporan/Global_report/pengadaan?mod=3'?>">Laporan Rekap Keluar Barang ke Unit Per-periode</a></li>
											<li><a href="<?php echo base_url().'laporan/Global_report/pengadaan?mod=7'?>">Laporan Rekap Keluar Barang ke Unit Per-Barang</a></li>
											<li><a href="<?php echo base_url().'laporan/Global_report/pengadaan?mod=4'?>" target=_blank>Laporan Permintaan Pembelian </a></li>
											<li><a href="<?php echo base_url().'laporan/Global_report/pengadaan?mod=5'?>">Laporan PO </a></li>
											<li><a href="<?php echo base_url().'laporan/Global_report/pengadaan?mod=6'?>">Laporan Pembelian </a></li>
											<li><a href="<?php echo base_url().'laporan/Global_report/pengadaan?mod=9'?>">Laporan Mutasi Per-periode</a></li>
											<li><a href="<?php echo base_url().'laporan/Global_report/pengadaan?mod=11'?>">Laporan PO Donasi </a></li>
										</ol>
										</div>
									</div>
									</div>
									
									<!-- MODUL FARMASI -->
									<div class="panel panel-default">
									<div class="panel-heading">
										<h4 class="panel-title">
										<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseFarmasi" aria-expanded="true">
											<i class="bigger-110 ace-icon fa fa-angle-down" data-icon-hide="ace-icon fa fa-angle-down" data-icon-show="ace-icon fa fa-angle-right"></i>
											&nbsp;FARMASI
										</a>
										</h4>
									</div>

									<div class="panel-collapse collapse" id="collapseFarmasi" aria-expanded="true" style="">
										<div class="panel-body">
										<ol>
											<li><a href="<?php echo base_url().'laporan/Global_report/farmasi?mod=1'?>">Laporan Keluar/Masuk Obat & Alkes Berdasarkan Tahun</a></li>
											<li><a href="<?php echo base_url().'laporan/Global_report/farmasi?mod=2'?>">Laporan Stok Akhir Barang Medis Berdasarkan Mutasi Tanggal Terakhir</a></li>
											<li><a href="<?php echo base_url().'laporan/Global_report/farmasi?mod=4'?>">Laporan Bon Obat</a></li>
											<li><a href="<?php echo base_url().'laporan/Global_report/farmasi?mod=5'?>">Laporan Mutasi Obat & Alkes Per-periode</a></li>
											<li><a href="<?php echo base_url().'laporan/Global_report/farmasi?mod=6'?>">Laporan Penjualan Jenis Obat Racikan/Non Racikan</a></li>
											<li><a href="<?php echo base_url().'laporan/Global_report/farmasi?mod=7'?>">Laporan Pembelian Obat Cito</a></li>
											<li><a href="<?php echo base_url().'laporan/Global_report/farmasi?mod=8'?>">Laporan Pemesanan Resep</a></li>
											<li><a href="<?php echo base_url().'laporan/Global_report/farmasi?mod=9'?>">Laporan Penjualan Obat Per Kategori</a></li>
											<li><a href="<?php echo base_url().'laporan/Global_report/lappembelian'?>">Laporan Pembelian Obat (Operational Level)</a></li>
										</ol>
										</div>
									</div>
									</div>

									<!-- MODUL STOK OPNAME -->
									<div class="panel panel-default">
									<div class="panel-heading">
										<h4 class="panel-title">
										<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseSo" aria-expanded="true">
											<i class="bigger-110 ace-icon fa fa-angle-down" data-icon-hide="ace-icon fa fa-angle-down" data-icon-show="ace-icon fa fa-angle-right"></i>
											&nbsp;STOK OPNAME
										</a>
										</h4>
									</div>

									<div class="panel-collapse collapse" id="collapseSo" aria-expanded="true" style="">
										<div class="panel-body">
										<ol>
											<li><a href="<?php echo base_url().'laporan/Global_report/so?mod=1'?>">Daftar Barang Yang Akan di Stok Opname</a></li>
											<li><a href="<?php echo base_url().'laporan/Global_report/so?mod=2'?>">Laporan Hasil SO</a></li>
											<li><a href="<?php echo base_url().'laporan/Global_report/so?mod=3'?>">Laporan Sebelum SO</a></li>
										</ol>
										</div>
									</div>
									</div>

									<!-- MODUL STOK OPNAME -->
									<div class="panel panel-default">
									<div class="panel-heading">
										<h4 class="panel-title">
										<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseKunjunganRj" aria-expanded="true">
											<i class="bigger-110 ace-icon fa fa-angle-down" data-icon-hide="ace-icon fa fa-angle-down" data-icon-show="ace-icon fa fa-angle-right"></i>
											&nbsp;KUNJUNGAN PASIEN RAWAT JALAN
										</a>
										</h4>
									</div>

									<div class="panel-collapse collapse" id="collapseKunjunganRj" aria-expanded="true" style="">
										<div class="panel-body">
										<ol>
											<li><a href="<?php echo base_url().'laporan/Global_report/kunjungan?mod=1'?>">Daftar Kunjungan Pasien Berdasarkan Usia dan Tahun Kunjungan</a></li>
											<li><a href="<?php echo base_url().'laporan/Global_report/kunjungan?mod=2'?>">Daftar Kunjungan Pasien Per-hari</a></li>
											<li><a href="<?php echo base_url().'laporan/Global_report/kunjungan?mod=3'?>">Daftar Registrasi Pasien Per-hari</a></li>
										</ol>
										</div>
									</div>
									</div>

									<!-- MODUL STOK OPNAME -->
									<div class="panel panel-default">
									<div class="panel-heading">
										<h4 class="panel-title">
										<a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion" href="#collapseRekamMedis" aria-expanded="true">
											<i class="bigger-110 ace-icon fa fa-angle-down" data-icon-hide="ace-icon fa fa-angle-down" data-icon-show="ace-icon fa fa-angle-right"></i>
											&nbsp;REKAM MEDIS PASIEN
										</a>
										</h4>
									</div>

									<div class="panel-collapse collapse" id="collapseRekamMedis" aria-expanded="true" style="">
										<div class="panel-body">
										<ol>
											<li><a href="<?php echo base_url().'laporan/Global_report/laporanrl'?>">Laporan RL</a></li>
										</ol>
										</div>
									</div>
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
			window.jQuery || document.write("<script src='../assets/js/jquery.js'>"+"<"+"/script>");
		</script>

		<script type="text/javascript">
			if('ontouchstart' in document.documentElement) document.write("<script src='../assets/js/jquery.mobile.custom.js'>"+"<"+"/script>");
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

	</body>
</html>
