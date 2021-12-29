<!DOCTYPE html>
<html lang="en">
	<head>

	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="utf-8" />
    <title><?php echo COMP_SORT; ?> - <?php echo COMP_LONG; ?></title>
		

		<!-- Bootstrap -->
		<link href="<?php echo base_url()?>assets/medicaid/css/bootstrap.min.css" rel="stylesheet">
		<!-- Font Awesome -->
		<link href="<?php echo base_url()?>assets/medicaid/fonts/font-awesome/css/font-awesome.min.css" rel="stylesheet">
		<!-- Simple-Line-Icons-Webfont -->
		<link href="<?php echo base_url()?>assets/medicaid/fonts/Simple-Line-Icons-Webfont/simple-line-icons.css" rel="stylesheet">
		<!-- FlexSlider -->
		<link href="<?php echo base_url()?>assets/medicaid/scripts/FlexSlider/flexslider.css" rel="stylesheet">
		<!-- Owl Carousel -->
		<link href="<?php echo base_url()?>assets/medicaid/css/owl.carousel.css" rel="stylesheet">
		<link href="<?php echo base_url()?>assets/medicaid/css/owl.theme.default.css" rel="stylesheet">
		<!-- noUiSlider -->
		<link href="<?php echo base_url()?>assets/medicaid/css/jquery.nouislider.min.css" rel="stylesheet">
		<!-- Style.css -->
		<link href="<?php echo base_url()?>assets/medicaid/css/style.css" rel="stylesheet">
		<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>assets/medicaid/css/color.css" id="color-switcher">

		<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
		<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
		<!--[if lt IE 9]>
			<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
			<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->
        
        <style>
             @font-face { font-family: MyriadPro; src: url('assets/fonts/MyriadPro-Bold.otf'); } 

            .row h1 {
                font-family: MyriadPro;
            }
        </style>

	</head>
	<body>
		<div class="responsive-menu">
			<a href="" class="responsive-menu-close"><i class="fa fa-times"></i></a>
			<nav class="responsive-nav"></nav> <!-- end .responsive-nav -->
		</div> <!-- end .responsive-menu -->
		<header class="header header-transparent">
			<div class="container">
				<nav class="top-contacts">
					<ul class="list-unstyled" >
						<li style="color:#333"><i style="color:#333" class="fa fa-clock-o"></i>Buka setiap hari 24 Jam </li>
						<li><a href="" style="color:#333"><i style="color:#333" class="fa fa-phone"></i>Hotline : <?php echo COMP_TELP?></a></li>
					</ul>
				</nav>
				<div class="navigation-wrapper clearfix">
                    <div class="row" style="height:105px;">
                        <img src="<?php echo base_url().COMP_ICON?>" alt="<?php echo COMP_SORT?>" style="margin:15px 10px 0 0;float:left" class="img-responsive" width="100">
                        <h1 style="margin:20px 0 0 20px;color:#333;text-align:left;"><?php echo COMP_LONG?></h1>
                        <p style="font-family: Helvetica;margin-top:-10px"><b><?php echo COMP_ADDRESS?></b></p>
                    </div> <!-- end .logo -->
				</div> <!-- end .navigation-wrapper -->
			</div> <!-- end .container -->
		</header> <!-- end .header -->

		<div class="section transparent" style="background-image: url('<?php echo base_url()?>assets/medicaid/images/12.jpg');">
			<div class="inner">
				<div class="container">
					<div class="row aligned-cols">
						<div class="col-sm-7 aligned-middle">
							<h1 class="main-heading white">Patient Care is Our First Priority!</h1>
							<ul class="fa-ul list-unstyled main-list">
								<li><i class="fa-li fa fa-check"></i>Administer exercise stress tests in healthy populations</li>
								<li><i class="fa-li fa fa-check"></i>Evaluate a person’s overall health</li>
								<li><i class="fa-li fa fa-check"></i>Develop individualized exercise prescriptions </li>
							</ul>
						</div> <!-- end .col-sm-7 -->
						<div class="col-sm-5">
							<form method="post" action="<?php echo base_url().'index.php/login/process'?>" id="form-login">
								<fieldset class="white" style="height:450px;">
                                    <h1 style="color:#333">Login</h1>
									<div class="form-group">
										<label>Email</label>
										<input type="text" class="form-control" name="username" id="username" value="<?php echo set_value('username')?>" style="border:1px solid #333;border-radius:10px;">
										<?php echo form_error('username'); ?>
									</div> <!-- end .form-group -->
									<div class="form-group">
										<label>Password</label>
										<input type="password" class="form-control" name="password" id="password" value="<?php echo set_value('password')?>" style="border:1px solid #333;border-radius:10px;">
										<?php echo form_error('password'); ?>
									</div> <!-- end .form-group -->
									
									<button type="button" id="button-login" class="width-35 pull-right btn btn-sm btn-primary">
										<i class="ace-icon fa fa-key"></i>
										<span class="bigger-110">Login</span>
									</button>
									
									<div style="display:block;margin-top:30px;">
										<a href="#" onclick="reg()">Register |</a>&nbsp;
										<a href="#" onclick="forgot()">Forgot Password??</a>
									</div>
                                </fieldset>  
                            </form>
                            
                            <form method="post" action="<?php echo base_url().'index.php/registration/Reg_online/process_register'?>" id="form-register" style="display:none;">
                                <fieldset class="white">
                                    <h1 style="color:#333">Register</h1>
									<div class="form-group">
										<label>Nama Lengkap</label>
										<input type="text" name="fullname" id="fullname" style="border:1px solid #333;border-radius:10px;">
                                    </div> <!-- end .form-group -->
                                    <div class="form-group">
										<label>Email</label>
										<input type="text" name="email" id="email" style="border:1px solid #333;border-radius:10px;">
                                    </div> <!-- end .form-group -->
                                    <div class="form-group">
										<label>No Handphone</label>
										<input type="text" name="phone_number" id="phone_number" style="border:1px solid #333;border-radius:10px;">
									</div> <!-- end .form-group -->
									<div class="form-group">
										<label>Password</label>
										<input type="password" name="security_code" id="security_code" style="border:1px solid #333;border-radius:10px;">
                                    </div> <!-- end .form-group -->
                                    <div class="form-group">
										<label>Konfirm Password</label>
										<input type="password" name="confirm_security_code" id="confirm_security_code" style="border:1px solid #333;border-radius:10px;">
									</div> <!-- end .form-group -->
									
                                    <button type="submit" class="button" id="button-register">Submit</button>
                                    
                                    <a href="#" onclick="login()" style="display:block;margin-top:30px;">Back To Login</a>
                                </fieldset>
							</form>
							
							<form method="post" action="" id="forgot-box" style="display:none;">
								<fieldset class="white" style="height:450px;">
                                    <h1 style="color:#333">Reset Password</h1>
									<div class="form-group">
										<label>Masukkan Email Anda</label>
										<input type="text" name="email_fgt" id="email_fgt" style="border:1px solid #333;border-radius:10px;">
									</div> <!-- end .form-group -->					
									
                                    <button type="submit" class="button" id="button-fgt">Submit</button>
                                    
                                    <a href="#" onclick="forgot_pwd()" style="display:block;margin-top:30px;">Back To Login</a>
                                </fieldset>  
                            </form>
						</div> <!-- end .col-sm-5 -->
					</div> <!-- end .row -->
				</div> <!-- end .container -->
			</div> <!-- end .inner -->
		</div> <!-- end .section -->

		
	
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
       $('#btn-login-dark').on('click', function(e) {
        $('body').attr('class', 'login-layout');
        $('#id-text2').attr('class', 'white');
        $('#id-company-text').attr('class', 'blue');
        
        e.preventDefault();
       });
       $('#btn-login-light').on('click', function(e) {
        $('body').attr('class', 'login-layout light-login');
        $('#id-text2').attr('class', 'grey');
        $('#id-company-text').attr('class', 'blue');
        
        e.preventDefault();
       });
       $('#btn-login-blur').on('click', function(e) {
        $('body').attr('class', 'login-layout blur-login');
        $('#id-text2').attr('class', 'white');
        $('#id-company-text').attr('class', 'light-blue');
        
        e.preventDefault();
       });
       
      });
    </script>
    <link href="<?php echo base_url()?>assets/achtung/ui.achtung-mins.css" rel="stylesheet" type="text/css" />
      <script type="text/javascript" src="<?php echo base_url()?>assets/achtung/ui.achtung-min.js"></script>
      <script type="text/javascript" src="<?php echo base_url()?>assets/achtung/achtung.js"></script> 
      <script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery.form.js"></script>
      <script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery-validation/dist/jquery.validate.js"></script> 
      <script>
      $('document').ready(function() {  

        /*========== PROCESS LOGIN ================*/
        $("#form-login").validate({focusInvalid:true});     
        $( "#username" )
          .keypress(function(event) {
            var keycode =(event.keyCode?event.keyCode:event.which); 
            if(keycode ==13){
              event.preventDefault();
              if($(this).valid()){
                $('#password').focus();
              }
              return false;       
            }
        });
        
        $( "#password" )
          .keypress(function(event) {
            var keycode =(event.keyCode?event.keyCode:event.which); 
            if(keycode ==13){
              if($("#form-login").valid()) {  
                $('#form-login').ajaxForm({
                  beforeSend: function() {
                    achtungShowLoader();
                  },
                  uploadProgress: function(event, position, total, percentComplete) {
                  },
                  complete: function(xhr) {     
                    var data=xhr.responseText;
                    var jsonResponse = JSON.parse(data);

                    if(jsonResponse.status === 200){
                      window.location = '<?php echo base_url().'main'?>';
                    }else{
                      $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
                    }
                    achtungHideLoader();
                  }

                });
              }
              $("#form-login").submit();
            }
        });
        
        $( "#button-login" )
          .on("click",function(event) {
            var keycode =(event.keyCode?event.keyCode:event.which); 
              if($("#form-login").valid()) {  
                $('#form-login').ajaxForm({
                  beforeSend: function() {
                    achtungShowLoader();
                  },
                  complete: function(xhr) {  
                    //alert(xhr.responseText); return false;
                    var data=xhr.responseText;
                    var jsonResponse = JSON.parse(data);

                    if(jsonResponse.status === 200){
                      window.location = '<?php echo base_url().'main'?>';
                    }else{
                      $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
                    }
                    achtungHideLoader();
                  }
                });
              }
              $("#form-login").submit();
            
        });

        $( "#button-register" )
          .on("click",function(event) {
            var keycode =(event.keyCode?event.keyCode:event.which); 
              if($("#form-register").valid()) {  
                $('#form-register').ajaxForm({
                  beforeSend: function() {
                    achtungShowLoader();
                  },
                  complete: function(xhr) {  
                    //alert(xhr.responseText); return false;
                    var data=xhr.responseText;
                    var jsonResponse = JSON.parse(data);

                    if(jsonResponse.status === 200){
                      window.location = jsonResponse.redirect;
                    }else{
                      $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
                    }
                    achtungHideLoader();
                  }
                });
              }
              $("#form-register").submit();
            
        });

        
        $("#form-login input:text").first().focus();

        /*========== END PROCESS LOGIN ================*/



        
	  });
	  
	 

	function reg() {
		$('#form-login').hide('fast');
		$('#form-register').show('fast');
	}
	
	function forgot() {
		$('#form-login').hide('fast');
		$('#forgot-box').show('fast');
	}

	function login() {
		$('#form-login').show('fast');
		$('#form-register').hide('fast');
	}
	
	function forgot_pwd() {
		$('#form-login').show('fast');
		$('#forgot-box').hide('fast');
	}


      </script>

	  

	</body>
</html>