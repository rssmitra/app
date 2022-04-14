<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>Validasi Dokumen</title>

		<meta name="description" content="User login page" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<!-- bootstrap & fontawesome -->
		<link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.css" />
		<link rel="stylesheet" href="<?php echo base_url()?>assets/css/font-awesome.css" />

		<!-- text fonts -->
		<link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace-fonts.css" />

		<!-- ace styles -->
		<link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace.css" />

		<!--[if lte IE 9]>
			<link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace-part2.css" />
		<![endif]-->
		<link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace-rtl.css" />

		<!--[if lte IE 9]>
		  <link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace-ie.css" />
		<![endif]-->

		<!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->

		<!--[if lt IE 9]>
		<script src="../assets/js/html5shiv.js"></script>
		<script src="../assets/js/respond.js"></script>
		<![endif]-->
	</head>
  <style>
    .login-layout .widget-box .widget-main {
        background: #F7F7F7 !important;
    }
    .forgot-box .toolbar {
        background: #6aaa46;
        border-top: 2px solid #42821c;
    }
  </style>
	<body class="login-layout">
		<div class="main-container">
			<div class="main-content">
				<div class="row">
					<div class="col-sm-10 col-sm-offset-1">
						<div class="login-container">
							<div class="center">
								<h1>
									<i class="ace-icon fa fa-file green"></i>
									<span class="red">Validasi</span>
									<span class="white" id="id-text2">Dokumen</span>
								</h1>
								<h4 class="blue" id="id-company-text">&copy; RS Setia Mitra</h4>
							</div>

							<div class="space-6"></div>

							<div class="position-relative">
							
								<div id="forgot-box" class="forgot-box visible widget-box no-border">
									<div class="widget-body">
										<div class="widget-main">

                      <div id="form-search-doc" >
											<h4 class="header red lighter bigger">
												<i class="ace-icon fa fa-file"></i>
												Validasi Dokumen
											</h4>

											<div class="space-6"></div>
											<p>
												Masukan kode penunjang
											</p>

											<form>
												<fieldset>
													<label class="block clearfix">
														<span class="block input-icon input-icon-right">
															<input type="text" class="form-control" name="kode" id="kode" placeholder="Kode Penunjang" />
														</span>
													</label>

													<div class="clearfix">
														<button type="button" id="btn-validasi-doc" class="width-35 pull-right btn btn-sm btn-danger">
															<i class="ace-icon fa fa-lightbulb-o"></i>
															<span class="bigger-110">Validasi</span>
														</button>
													</div>
												</fieldset>
											</form>
                      <br>
                      </div>
                      <div id="loadingpage"></div>
                      <div class="center" id="certificate_show" style="display: none">
                        <span style="font-size: 36px"><i class="fa fa-certificate bigger-300 orange2"></i></span><br>
                        <span style="font-family: cursive; font-size: 18px; text-shadow: 1px 0px 1px gold;color: darkgoldenrod;">Verified Document</span><br>
                        <table class="center" style="width: 100%;">
                            <tr><td><span style="font-size: 11px">No. Rekam Medis</span><br><span style="font-size: 14px" id="no_mr_txt">-</span></td></tr>
                            <tr><td><span style="font-size: 11px">Nama Pasien</span><br><span style="font-size: 14px" id="nama_pasien_txt">-</span></td></tr>
                            <tr><td><span style="font-size: 11px">Tanggal Daftar</span><br><span style="font-size: 14px" id="tgl_daftar_txt">-</span></td></tr>


                        </table>
                      </div>
										</div><!-- /.widget-main -->

										<div class="toolbar center">
											<a href="#" data-target="#login-box" class="back-to-login-link">
												Copyright @ RS Setia Mitra
											</a>
										</div>
									</div><!-- /.widget-body -->
								</div><!-- /.forgot-box -->

							</div><!-- /.position-relative -->

						</div>
					</div><!-- /.col -->
				</div><!-- /.row -->
			</div><!-- /.main-content -->
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

		<!-- inline scripts related to this page -->
		<script type="text/javascript">
			jQuery(function($) {
			 $(document).on('click', '.toolbar a[data-target]', function(e) {
				e.preventDefault();
				var target = $(this).data('target');
				$('.widget-box.visible').removeClass('visible');//hide others
				$(target).addClass('visible');//show target
			 });
			});
			
			
			
			//you don't need this, just used for changing background
			jQuery(function($) {
        $('#btn-validasi-doc').on('click', function(e) {
          $('#loadingpage').html('Loading...');
          $.getJSON("<?php echo site_url('Templates/Attachment/prosesValidasiDok/') ?>" + $("#kode").val() + "", '', function (response) {
            if(response.code == 200){
              $('#certificate_show').show('fast');
              $('#form-search-doc').hide('fast');
              var obj = response.data;
              $('#no_mr_txt').text(obj.no_mr);
              $('#nama_pasien_txt').text(obj.nama_pasien);
              $('#tgl_daftar_txt').text(obj.tgl_daftar);
            }else{
              $('#certificate_show').hide('fast');
              $('#form-search-doc').show('fast');
              alert('data tidak ditemukan.');
            }
            $('#loadingpage').html('');
            
          })
				  e.preventDefault();
			  });
       
			 
			});
		</script>
	</body>
</html>
