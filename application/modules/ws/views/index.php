<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="utf-8" />
    <title><?php echo COMP_SORT?> - <?php echo COMP_LONG?></title>

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

 </head>

  <body class="login-layout light-login">
    <div class="main-container">
      <div class="main-content">
        <div class="row">
          <div class="col-sm-10 col-sm-offset-1">
            <div class="login-container">
              <div class="center" style="padding-top:1%">
                <img src="<?php echo base_url().COMP_ICON; ?>" style="width:120px">
                <h1>
                  <i class="ace-icon fa fa-leaf green"></i>
                  <span class="red">Registrasi</span>
                  <span class="dark" id="id-text2">Online</span>
                </h1>
                <h4 class="blue" id="id-company-text">&copy; <?php echo COMP_FULL; ?></h4>
              </div>

              <div class="space-6"></div>

              <div class="position-relative">
                <div id="login-box" class="login-box visible widget-box no-border">
                  <div class="widget-body">
                    <div class="widget-main">
                      <h4 class="header blue lighter bigger">
                        <i class="ace-icon fa fa-coffee green"></i>
                        Reset Password
                      </h4>

                      <div class="space-6"></div>

                      <form method="post" action="<?php echo base_url().'index.php/global_ws/process_reset_pwd'?>" id="form-login">
                        <fieldset>
                          <label class="block clearfix">
                            <span class="block input-icon input-icon-right">
                                <input type="password" class="form-control" placeholder="Kata Sandi" name="security_code"/>
                                <i class="ace-icon fa fa-lock"></i>
                                <small>* Kata sandi minimal 6 karakter</small>
                            </span>
                          </label>

                          <label class="block clearfix">
                            <span class="block input-icon input-icon-right">
                                <input type="password" class="form-control" placeholder="Konfirmasi Kata Sandi" name="confirm_security_code" />
                                <i class="ace-icon fa fa-retweet"></i>
                            </span>
                          </label>    

                          <input type="hidden" name="email" value="<?php echo $email?>">

                          <div class="space"></div>

                          <div class="clearfix">
                           
                            <button type="button" id="button-login" class="width-35 pull-right btn btn-sm btn-primary">
                              <i class="ace-icon fa fa-key"></i>
                              <span class="bigger-110">Submit</span>
                            </button>
                          </div>

                          <div class="space-4"></div>
                        </fieldset>
                      </form>

                    </div><!-- /.widget-main -->

                   

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

      </script>

  </body>
</html>
