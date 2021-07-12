
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
		function scrollSmooth(div_id, link){
			if(div_id == 'content-green'){
				$('.content-green').show('slow');
				$('.content-white').hide('slow');
			}else{
				$('.content-green').hide('slow');
				$('.content-white').show('slow');
			}
			$('#'+div_id+'').load(link);
			$('html,body').animate({
					scrollTop: $("."+div_id+"").offset().top},
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
									<a href="#" onclick="scrollSmooth('content-green','mandiri_bpjs')">
										MANDIRI BPJS 
									</a>
								</li>
								<li>
									<a href="#" onclick="scrollSmooth('content-white','mandiri_umum')">
										MANDIRI UMUM & ASURANSI 
									</a>
								</li>
								<li>
									<a href="#" onclick="scrollSmooth('content-white','antrian_poli')">
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
	<div class="container">
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
			==Start subscription area==
====================================================== -->
<section class="content-green subscribe-area">
	<div class="container">
		<div class="row" id="content-green"></div>
	</div>
</section>
<!-- ======================================================
			==End subscription area==
====================================================== -->


<!-- =====================================================
			==Start testimonial==
===================================================== -->
<section class="content-white testimonial" style="padding-bottom: 20px">
	<div class="container">
		<div class="row" id="content-white"></div>
	</div>
</section>
<!-- =====================================================
			==End testimonial==
===================================================== -->


<div class="facts-area">
	<div class="container">
		<style>
			
		</style>
		<div class="row">
			<div class="col-md-8 col-md-offset-2">
				<div class="section-title" style="padding-bottom: 25px !important">
					<h2>Masukan Nomor Rujukan Puskesmas</h2>
					<span></span>
					<p>
						Masukan Nomor Rujukan dari Puskesmas untuk mencetak Surat Eligibiltas Pasien (SEP)
					</p>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-xs-12  col-md-10 col-md-offset-1 col-lg-10 col-lg-offset-1">
				<div class="subscribe-content-area">
					<form action="#">
						<ul>
							<li>
								<div class="subscribe-field">
									<div class="form-group">
										<input type="text" placeholder="Masukan Nomor Rujukan Puskesmas" class="input-field" style="font-size:18px;">
									</div>
								</div>
							</li>
							<li>
								<div class="subscribe-btn" style="padding-left: 15px">
									<a href="#!" class="green-btn">
										Cari Data
										<img src="<?php echo base_url()?>assets/kiosk/symbols2.png" alt="symbol2">
									</a>
								</div>
							</li>
						</ul>
					</form>
				</div>
			</div>
		</div>

		<div class="row" id="result-dt-rujukan" style="padding-top: 20px">
			
			<div class="col-md-3">
				<div class="box box-primary">

					<ul class="list-group list-group-unbordered">

						<li class="list-group-item">
						<small style="color: blue; font-weight: bold; font-size:11px">No Kartu BPJS : </small> <div id="noKartuFromNik">-</div>
						</li>

						<li class="list-group-item">
						<small style="color: blue; font-weight: bold; font-size:11px">Nama Peserta : </small> <div id="nama">-</div>
						</li>

						<li class="list-group-item">
						<small style="color: blue; font-weight: bold; font-size:11px">NIK : </small> <div id="nik">-</div>
						</li>
						<li class="list-group-item">
						<small style="color: blue; font-weight: bold; font-size:11px">Tanggal Lahir : </small> <div id="tglLahir">-</div>
						</li>
						<li class="list-group-item">
						<small style="color: blue; font-weight: bold; font-size:11px">Umur : </small> <div id="umur_p_bpjs">-</div>
						</li>
						<li class="list-group-item">
						<small style="color: blue; font-weight: bold; font-size:11px">Jenis Peserta : </small> <div id="jenisPeserta">-</div>
						</li>
						<li class="list-group-item">
						<small style="color: blue; font-weight: bold; font-size:11px">Hak Kelas : </small> <div id="hakKelas">-</div>
						</li>
						<li class="list-group-item">
						<small style="color: blue; font-weight: bold; font-size:11px">Status Kepesertaan : </small> <div id="statusPeserta">-</div>
						</li>
					</ul>

				</div>
			</div>

			<div class="col-sm-9 col-md-9 col-lg-9">
				<div class="contact-info-right" style="padding-top: 0px !important">
					
					<div class="contact-area-contact-field" style="padding-top: 0px !important;">
						<form action="#">
							<!-- form hidden -->
							<input name="tglSEP" id="tglSEP" value="<?php echo date('m/d/Y')?>" placeholder="mm/dd/YYYY" class="form-control date-picker" type="hidden">
							<input name="jenis_faskes" type="hidden" class="ace" value="1" checked/>
							<input type="hidden" class="form-control" id="noKartuHidden" name="noKartuHidden" readonly>
							<input name="jnsPelayanan" type="hidden" class="ace" value="2" checked/>
							<input name="lakalantas" type="hidden" class="ace" value="0" checked/>
							<input name="penjaminKLL" type="hidden" class="ace" value="0" checked/>
							<input type="hidden" class="form-control" name="catatan" id="catatan" value="">
							<input type="hidden" class="form-control" id="noSuratSKDP" name="noSuratSKDP" value="">
							<input type="hidden" class="form-control" id="user" name="user" value="" readonly>
							<input id="InputKeydokterDPJP" class="form-control" name="dokterDPJP" type="hidden" placeholder="Masukan keyword minimal 3 karakter" />
							<input type="hidden" name="KodedokterDPJP" value="" id="KodedokterDPJP">
							<input type="hidden" class="form-control" id="noRujukan" name="noRujukan" readonly>
							<input name="eksekutif" type="hidden" class="ace" value="0">
							<input name="tglRujukan" id="tglKunjungan" value="" placeholder="dd/mm/YYYY" class="form-control date-picker" type="hidden" readonly>
							
							<div class="row">

								<div class="col-sm-6">
									<div class="single-form-field">
										<label>PPK Asal Rujukan</label>
										<div class="form-group">
											<input id="inputKeyFaskes" class="form-control" name="ppkRujukan" type="text" placeholder="Masukan keyword minimal 3 karakter" value="" readonly/>
											<input type="hidden" name="kodeFaskesHidden" value="" id="kodeFaskesHidden">
										</div>
									</div>
								</div>

								<div class="col-sm-6">
									<div class="single-form-field">
										<label>Spesialis/SubSpesialis</label>
										<div class="form-group">
											<input id="inputKeyPoli" class="form-control" name="tujuan" type="text" placeholder="Masukan keyword minimal 3 karakter" readonly/>
                                            <input type="hidden" name="kodePoliHidden" value="" id="kodePoliHidden">	
										</div>
									</div>
								</div>

								<div class="col-sm-6">
									<div class="single-form-field">
										<label>Dokter DPJP</label>
										<div class="form-group">
											<input id="show_dpjp" class="form-control" name="show_dpjp" type="text" readonly/>	
										</div>
									</div>
								</div>

								<div class="col-sm-6">
									<div class="single-form-field">
										<label>No. Telp/Hp</label>
										<div class="form-group">
											<input type="text" class="form-control" id="noTelp" name="noTelp">	
										</div>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-xs-12">
									<div class="single-form-field">
										<label>Diagnosa Awal</label>
										<div class="form-group">
											<input type="hidden" name="kodeDiagnosaHidden" value="" id="kodeDiagnosaHidden">
													
											<textarea id="inputKeyDiagnosa" class="form-control" name="diagAwal" type="text" placeholder="Masukan keyword minimal 3 karakter" style="text-transform: uppercase" readonly></textarea>
										</div>
										<a href="#!" class="seo-btn">
											PROSES PENDAFTARAN 
										</a>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>

		</div>
	</div>
</div>

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