
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>RS SETIA MITRA</title>

	<!-- Enable zoom on mobile device
	====================================	 -->
	<meta name="viewport" content="width=device-width, initial-scale=1">

	<!-- Install fonts to your website
	====================================	 -->
	<link href="https://fonts.googleapis.com/css?family=Open+Sans:400,700|Poppins:300|Source+Sans+Pro:300,400,700" rel="stylesheet"> 

	<!-- Animate css
	====================================	 -->
	<link rel="stylesheet" href="<?php echo base_url()?>assets/kiosk/animate.min.css">

	<!-- Icofont
	====================================	 -->
	<link rel="stylesheet" href="<?php echo base_url()?>assets/kiosk/icofont.css">

	<!-- Mean menu
	====================================	 -->
	<link rel="stylesheet" href="<?php echo base_url()?>assets/kiosk/meanmenu.min.css">

	<!-- venobox
	====================================	 -->
	<link rel="stylesheet" href="<?php echo base_url()?>assets/kiosk/venobox.css">

	<!-- Default Owl theme
	====================================	 -->
	<link rel="stylesheet" href="<?php echo base_url()?>assets/kiosk/owl.theme.default.min.css">

	<!-- Owl carousel
	====================================	 -->
	<link rel="stylesheet" href="<?php echo base_url()?>assets/kiosk/owl.carousel.min.css">

	<!-- Bootstrap theme
	====================================	 -->
	<link rel="stylesheet" href="<?php echo base_url()?>assets/kiosk/bootstrap.min.css">

	<!-- style css
	====================================	 -->
	<link rel="stylesheet" href="<?php echo base_url()?>assets/kiosk/style.css">

	<!-- faveicon
	================================ -->
	<link rel="shortcut icon" href="img/icon/favicon.png" type="image/x-icon">

	<script>
		function scrollSmooth(link){
			$('#banner-home').hide('fast');
			$('#load-content-page').load(link);
			$('html,body').animate({
					scrollTop: $("#load-content-page").offset().top},
					'slow');
		}
	</script>
</head>
<body class="home2 inner-page">

<style>
	.main-navigation ul li a {
		font-size: 22px;
	}
	.subscribe-content-area ul li:first-child {
		width: 80%;
	}
	.subscribe-content-area ul li:nth-child(2) {
		width: 20%;
	}
	.green-btn {
		background: #27ae61 none repeat scroll 0 0;
		border-radius: 2px;
		box-shadow: 0 5px 0 #239b56;
		color: #fff;
		display: inline-block;
		font-family: "Source Sans Pro",sans-serif;
		font-size: 14px;
		font-weight: 700;
		line-height: 50px;
		padding-left: 27px;
		padding-right: 27px;
		position: relative;
		text-transform: uppercase;
		transition: all .10s linear;
	}
</style>

<div class="preloader" style="display: none;">
    <div class="preloader-inner-area">
        <div class="loader-overlay">
            <div class="l-preloader">
                <div class="c-preloader"></div>
            </div>
        </div>
    </div>
</div>

<!-- =====================================================
				==Start Banner==
===================================================== -->
<section class="banner-type2">
	<div class="navigation-type2">
		<div class="container">
			<div class="navigation-type2-inner-area clearfix">
				<!-- <div class="col-md-2 col-sm-2 col-xs-12">
					<div class="brand-logo" style="width: 250px; padding-top: 5px !important">
						<a href="index.html">
							<img src="<?php echo base_url()?>assets/kiosk/logo_rssm.png" alt="main-logo">
						</a>
					</div>
				</div> -->
				<div class="col-md-12 col-sm-12">
					<div class="navigation-inner-container type2">
						<div class="cart-search-bar navbar-right">
							
						</div>
						<div class="main-navigation">
							<ul>
								<li>
									<a href="#" onclick="scrollSmooth('Self_service/mandiri_bpjs')">
										MANDIRI BPJS 
									</a>
								</li>
								<li>
									<a href="#" onclick="scrollSmooth('Self_service/mandiri_umum')">
										MANDIRI UMUM & ASURANSI 
									</a>
								</li>
								<li>
									<a href="#" onclick="scrollSmooth('antrian_poli')">
										ANTRIAN POLI/KLINIK SPESIALIS 
									</a>
								</li>
								<li>
									<a href="#">
										IGD & PENUNJANG MEDIS   
									</a>
								</li>
							</ul>
						</div>
						
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="container" id="load-content-page">
		<div class="row">
			<div class="banner-type2-inner-area">
				<div class="banner-type2-content">
					<div class="banner-type2-inner-content">
						<div class="banner-icon1">
							<img src="<?php echo base_url()?>assets/kiosk/banner-icon7.png" alt="banner-icon">
						</div>
						<div class="banner-icon2">
							<img src="<?php echo base_url()?>assets/kiosk/banner-icon6.png" alt="banner-icon">
						</div>
						<div class="banner-icon3">
							<img src="<?php echo base_url()?>assets/kiosk/banner-icon5.png" alt="banner-icon">
						</div>
						<div class="banner-icon4">
							<img src="<?php echo base_url()?>assets/kiosk/banner-icon4.png" alt="banner-icon">
						</div>
						<div class="banner-icon5">
							<img src="<?php echo base_url()?>assets/kiosk/banner-icon1.png" alt="banner-icon">
						</div>
						<div class="banner-icon6">
							<img src="<?php echo base_url()?>assets/kiosk/banner-icon3.png" alt="banner-icon">
						</div>
						<div class="banner-icon7">
							<img src="<?php echo base_url()?>assets/kiosk/banner-icon2.png" alt="banner-icon">
						</div>
						<div class="col-md-8 col-md-offset-2">
							<h2>
							KIOSK PENDAFTARAN & ANTRIAN PASIEN
							</h2>
							<p>
								Kiosk Pendaftaran Mandiri Pasien BPJS, Umum dan Asuransi. <br>
								Persiapkan Dokumen anda sebelum melakukan Pendaftaran Mandiri seperti Surat Rujukan Puskesmas, KTP, Nomor Rekam Medis, dsb.
							</p>
							<!-- <a href="#" class="seo-btn">
								free seo analysis
								<img src="<?php echo base_url()?>assets/kiosk/symbols1.png" alt="icon-symbols">
							</a> -->
						</div>
					</div>
				</div>
				
			</div>
		</div>

		

	</div>
</section>
<!-- =====================================================
				==End Banner==
===================================================== -->


<!-- ======================================================
			==Start content page area==
====================================================== -->

<!-- <div id="load-content-page"></div> -->

<!-- ======================================================
			==End content page area==
====================================================== -->


<!-- =====================================================
				==Start facts==
===================================================== -->
<div class="facts-area">
	<div class="container">
		<div class="row">
			<div class="col-md-3 col-sm-3">
				<div class="single-facts-container">
					<span class='counter'>15</span>
					<p>
						Pendaftaran Mandiri Pasien BPJS
					</p>
				</div>
			</div>
			<div class="col-md-3 col-sm-3">
				<div class="single-facts-container">
					<span class='counter'>300</span>
					<p>
					Pendaftaran Mandiri Pasien Umum
					</p>
				</div>
			</div>
			<div class="col-md-3 col-sm-3">
				<div class="single-facts-container">
					<span class='counter'>455</span>
					<p>
					Antrian Poli/Klinik Spesialis
					</p>
				</div>
			</div>
			<div class="col-md-3 col-sm-3">
				<div class="single-facts-container">
					<span class='counter'>200</span>
					<p>
						Gawat Darurat (IGD) & Penunjang Medis
					</p>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- =====================================================
				==End facts==
===================================================== -->



<!-- ======================================================
			==Start call to action==
====================================================== -->
<section class="call-to-action-area">
	<div class="container">
		<div class="row">
			<div class="col-sm-8 col-md-6">
				<div class="call-to-action-left">
					<h4>Kami Peduli Kesehatan Anda.</h4>
				</div>
			</div>
			<!-- <div class="col-sm-4 col-md-6">
				<div class="call-to-actioin-right">
					<a href="#" class="seo-btn2">
						get started 
						<span>
							<i class="icofont get started   icofont-paper-plane"></i>
						</span>   
					</a>
				</div>
			</div> -->
		</div>
	</div>
</section>
<!-- ======================================================
			==End call to action==
====================================================== -->

<!-- ======================================================
			==Start footer==
====================================================== -->
<footer>
	<div class="footer-bottom-area">
		<div class="container">
			<div class="col-xs-12">
				<div class="footer-bottom-content">
					<ul>
						<li>
							© Copyright <?php echo date('Y')?> Smart Hospital System, All Rights Reserved     
						</li>
						<li>
							  Developed by <a href="#">IT Department RS Setia Mitra</a>
						</li>
					</ul>
				</div>
			</div>
		</div>
	</div>
</footer>
<!-- ======================================================
			==End footer==
====================================================== -->



	<!-- vendor js
	====================================	 -->
	<script src="<?php echo base_url()?>assets/kiosk/jquery-1.12.1.min.js"></script>

	<!-- Bootstrap
	====================================	 -->
	<script src="<?php echo base_url()?>assets/kiosk/bootstrap.min.js"></script>

	<!-- 
	====================================	 -->
	<script src="<?php echo base_url()?>assets/kiosk/jquery.mixitup.min.js"></script>

	<!-- 
	====================================	 -->
	<script src="<?php echo base_url()?>assets/kiosk/owl.carousel.min.js"></script>

	<!-- Venobox
	====================================	 -->
	<script src="<?php echo base_url()?>assets/kiosk/venobox.min.js"></script>

	<!-- counter up
	====================================	 -->
	<script src="<?php echo base_url()?>assets/kiosk/jquery.counterup.js"></script>

	<!-- Mean Menu
	====================================	 -->
	<script src="<?php echo base_url()?>assets/kiosk/jquery.meanmenu.min.js"></script>

	<!-- waypoint
	====================================	 -->
	<script src="<?php echo base_url()?>assets/kiosk/waypoints.min.js"></script>

	<!-- main js
	====================================	 -->
	<script src="<?php echo base_url()?>assets/kiosk/main.js"></script>
</body>
</html>