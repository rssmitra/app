
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
	<div class="container">
	
		<div class="row">
			<div class="col-md-7">
				<div class="checkout-container-left ">
					<div class="section-title inner">
						<h2>Billing Details</h2>
						<span></span>
					</div>
				</div>
				<form action="#">
					<div class="row">
						<div class="col-sm-6">
							<div class="single-form-field">
								<div class="form-group">
									<label for="field1">
										First Name <span>*</span>
									</label>
									<input id="field1" type="text" placeholder="First Name">
								</div>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="single-form-field">
								<div class="form-group">
									<label for="field2">
										Last Name <span>*</span>
									</label>
									<input id="field2" type="text" placeholder="First Name">
								</div>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="single-form-field">
								<div class="form-group">
									<label for="field3">
										Email Address  <span>*</span>
									</label>
									<input id="field3" type="text" placeholder="First Name">
								</div>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="single-form-field">
								<div class="form-group">
									<label for="field13">
										Phone <span>*</span>
									</label>
									<input id="field13" type="text" placeholder="First Name">
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12">
							<div class="single-form-field">
								<div class="form-group">
									<label for="select1">
										Country <span>*</span>
									</label>
									<select id="select1">
										<option value="Bangladesh">Bangladesh</option>
										<option value="India">India</option>
										<option value="Usa">Usa</option>
										<option value="Singapore">Singapore</option>
									</select>								
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12">
							<div class="single-form-field">
								<div class="form-group">
									<label for="field9">
										Address  <span>*</span>
									</label>
									<input id="field9" type="text" placeholder="First Name">
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<div class="single-form-field">
								<div class="form-group">
									<label for="field7">
										Town / City  <span>*</span>
									</label>
									<input id="field7" type="text" placeholder="First Name">
								</div>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="single-form-field">
								<div class="form-group">
									<label for="select3">
										State  <span>*</span>
									</label>
									<select id="select3">
										<option value="volvo">Wes Indies</option>
										<option value="saab">USA</option>
										<option value="mercedes">India</option>
										<option value="audi">Singapore</option>
									</select>								
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-xs-12">
							<div class="single-form-field">
								<div class="form-group">
									<label for="field11">
										Postcode / Zip  <span>*</span>
									</label>
									<input id="field11" type="text" placeholder="First Name">
								</div>
							</div>
						</div>
					</div>
				</form>
			</div>
			<div class="col-md-3 col-md-offset-1">
				<div class="checkout-container-right ">
					<div class="section-title inner">
						<h2>CART TOTALS</h2>
						<span></span>
					</div>
					<div class="total-cart-container">
						<ul>
							<li>
								<h6>Product <span>total</span></h6>
							</li>
							<li>
								<p>
									Search Marketing x 1
									<span>
										$60
									</span>
								</p>
							</li>
							<li>
								<p>
									Search Marketing x 1
									<span>
										$60
									</span>
								</p>
							</li>
							<li>
								<p>
									Search Marketing x 1
									<span>
										$60
									</span>
								</p>
							</li>
							<li>
								<p>
									Search Marketing x 1
									<span>
										$60
									</span>
								</p>
							</li>
							<li>
								<h5>Sub Total <span>Sub Total</span></h5>
							</li>
							<li class="cart-calculation">
								<h6>Total Order <span>$115</span></h6>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12">
				<div class="bank-draft">
					<form action="#">
						<ul>
							<li>
								<div class="draft-radio">
									<input id="radio1" type="radio" name="payment-type" value="male" checked> 
								</div>
								<div class="draft-content">
									<label  for="radio1">Direct Bank Transfer</label>
									<p>
										Make your payment directly into our bank account.Please use your Order ID as the payment reference. Your order won’t be shipped until the funds 
										have cleared in our account
									</p>
								</div>
							</li>
							<li>
								<div class="draft-radio">
									<input id="radio2" type="radio" name="payment-type" value="male" checked> 
								</div>
								<div class="draft-content">
									<label  for="radio2">Cheque Payment</label>
								</div>
							</li>
							<li>
								<div class="draft-radio">
									<input id="radio3" type="radio" name="payment-type" value="male" checked> 
								</div>
								<div class="draft-content">
									<label  for="radio3">PayPal</label>
								</div>
							</li>
						</ul>
						<a href="#" class="seo-btn">
							PLACE ORDER 
							<span>
								<i class="icofont icofont-cur-dollar"></i>
							</span>  
						</a>
					</form>
				</div>
			</div>
		</div>
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