<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="utf-8" />
    <title>RSSM - RS Setia Mitra</title>

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
    <script src="<?php echo base_url()?>assets/js/html5shiv.js"></script>
    <script src="<?php echo base_url()?>assets/js/respond.js"></script>
    <![endif]-->
    <!-- recaptcha -->
    <script src='https://www.google.com/recaptcha/api.js'></script>
  </head>

  <body class="login-layout light-login">
    <div class="main-container">
      <div class="main-content">
        <div class="row">
          <div class="col-sm-10 col-sm-offset-1">
            <div class="login-container">
              <div class="center" style="padding-top:1%">
                <img src="<?php echo base_url().'assets/images/logo.png'?>" style="width:120px">
                <h1>
                  <i class="ace-icon fa fa-leaf green"></i>
                  <span class="red">Registrasi</span>
                  <span class="dark" id="id-text2">Online</span>
                </h1>
                <h4 class="blue" id="id-company-text">&copy; Rumah Sakit Setia Mitra</h4>
              </div>

              <div class="space-6"></div>

              <div class="position-relative">

                <div id="forgot-box" class="forgot-box visible widget-box no-border">
                  <div class="widget-body">
                    <div class="widget-main">
                      <h4 class="header red lighter bigger">
                        <i class="ace-icon fa fa-key"></i>
                        Verification Security Code
                      </h4>
                      <p>
                        Masukan <b>Security Code</b>
                      </p>
                      <form method="post" action="<?php echo base_url().'index.php/registration/Reg_online/process_verification'?>" id="form-verification">
                        <fieldset>
                          <label class="block clearfix">
                            <span class="block input-icon input-icon-right">
                              <input type="hidden" name="hash_code" value="<?php echo $hash_code?>">
                              <input style="text-align:center; height:40px !important; font-size:25px" type="text" class="form-control" placeholder=" __ __ __ __" name="key" id="key"/>
                              <small>Masukan 4 angka yang telah kami kirim melali SMS</small>
                            </span>
                          </label>

                          <div class="clearfix">
                            <button type="button" id="btn-verification" class="width-35 pull-right btn btn-sm btn-danger">
                              <i class="ace-icon fa fa-lightbulb-o"></i>
                              <span class="bigger-110">Verifikasi !</span>
                            </button>
                          </div>
                        </fieldset>
                      </form>
                    </div><!-- /.widget-main -->

                    <div class="toolbar center">
                      <a href="#" data-target="#forgot-box" class="back-to-login-link">
                        Kembali ke login
                        <i class="ace-icon fa fa-arrow-right"></i>
                      </a>
                    </div>
                  </div><!-- /.widget-body -->
                </div>

              </div><!-- /.position-relative -->

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
        $("#form-verification").validate({focusInvalid:true});     
        $( "#key" )
          .keypress(function(event) {
            var keycode =(event.keyCode?event.keyCode:event.which); 
            if(keycode ==13){
              event.preventDefault();
              if($(this).valid()){
                $('#btn-verification').focus();
              }
              return false;       
            }
        });
        
        $( "#btn-verification" )
          .on("click",function(event) {
            var keycode =(event.keyCode?event.keyCode:event.which); 
              if($("#form-verification").valid()) {  
                $('#form-verification').ajaxForm({
                  beforeSend: function() {
                    achtungShowLoader();
                  },
                  complete: function(xhr) {  
                    var data=xhr.responseText;
                    var jsonResponse = JSON.parse(data);

                    if(jsonResponse.status == 200){
                      //window.location = jsonResponse.redirect;
                      $.achtung({message: jsonResponse.message, timeout:10});
                      setTimeout(function(){
                        window.open(jsonResponse.redirect);
                      },1800);
                    }else{
                      $.achtung({message: jsonResponse.message, timeout:10});
                    }
                    achtungHideLoader();
                  }
                });
              }
              $("#form-verification").submit();
            
        });
        /*========== END PROCESS VERIFICATION ================*/

      });

      </script>

  </body>
</html>
