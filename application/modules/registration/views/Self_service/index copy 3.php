
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Checkout</title>

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
	<link rel="shortcut icon" href="<?php echo base_url()?>assets/kiosk/img/icon/favicon.png" type="image/x-icon">

	<script>
		function scrollSmooth(link){
			$('#load-content-page').load(link);
			$('html,body').animate({
					scrollTop: $("#load-content-page").offset().top},
					'slow');
		}
	</script>
</head>
<body class="checkout-page inner-page">

<!-- ========================================
            ==Start Preloader==
======================================== -->
<!-- <div class="preloader">
    <div class="preloader-inner-area">
        <div class="loader-overlay">
            <div class="l-preloader">
                <div class="c-preloader"></div>
            </div>
        </div>
    </div>
</div> -->
<!-- ========================================
        ==Start Preloader==
======================================== -->

<section class="bread-crumb-area">
	<div class="navigation-type2">
		<div class="container">
			<div class="navigation-type2-inner-area clearfix">
				<div class="col-md-2 col-sm-2">
					<div class="brand-logo">
						<a href="index.html">
							<img src="<?php echo base_url()?>assets/kiosk/logo.png" alt="main-logo">
						</a>
					</div>
				</div>
				<div class="col-md-10 col-sm-10">
					<div class="navigation-inner-container type2">
						<div class="main-navigation">
							<ul><li>
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
						<nav class="mobile-menu">
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
						</nav>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-xs-12">
				<div class="bread-crumb-container">
					<div class="bread-crumb-inner-area">
						<div class="bread-crumb-content">
							<h2>checkout</h2>
							<ul class="breadcrumb">
								<li><a href="index.html">Home</a></li>
								<li class="active">checkout</li>
							</ul>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</section>

<!-- =======================================================
			==Start checkout ==
======================================================= -->
<section class="checkout-container">
	<div class="row" id="load-content-page">
	</div>
</section>
<!-- =======================================================
			==End checkout ==
======================================================= -->

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
							  Developed by <a href="#">IT Department <?php echo COMP_LONG; ?></a>
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