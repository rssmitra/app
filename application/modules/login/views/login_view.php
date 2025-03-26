 <!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="UTF-8">
    <meta name="description" content="Smart Hospital System 4.0 adalah sistem rumah sakit yang sudah terintegrasi antar modulnya dan juga terintegrasi dengan BPJS seperti VClaim, ICare JKN, Antrian Online, MJKN, Pcare, Satu Sehat Kemenkes, dsb." />
    <meta name="keywords" content="SIMRS, Smart Hospital, Bridging, BPJS">
    <meta name="author" content="<?php echo COMP_LONG; ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

    <title><?php echo COMP_SORT; ?> - Form Login</title>

    <!-- bootstrap & fontawesome -->
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.css" />
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/font-awesome.css" />

    <!-- text fonts -->
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace-fonts.css" />

    <!-- ace styles -->
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace.css" />
    <!-- Favicon -->
    <link rel="shortcut icon" href="<?php echo base_url().COMP_ICON; ?>">
    
  </head>
  <style type="text/css">
    #body-style {
      background-image:url(<?php echo PATH_IMG_DEFAULT.$profile_form->cover_login?>);
      /* background: white; */
      background-size: 100%; 
      background-attachment: fixed;
      background-position: center;
      background-size: cover;
      opacity: 1;
      background-repeat: no-repeat;
    }

    .login-layout .widget-box .widget-main {
        padding: 10px 15px 10px;
        background: #FFFFFF !important;
    }

    .login-box .toolbar {
        background: #0d5280;
    }

    #login-box {
        top: 50%;
        box-shadow: 0px 0px 25px 10px grey;
        border-radius: 15px;
    }

  </style>

  <body class="login-layout light-login" id="body-style">
    <div class="main-container">
      <div class="main-content">
        <div class="row">
          <br>
          <br>
          <div class="col-sm-10 col-sm-offset-1">
            <div class="login-container">
              <div class="position-relative">
                <div id="login-box" class="login-box visible widget-box no-border">
                  <div class="widget-body">
                    
                    <div class="widget-main" style="border-radius: 20px">
                      <center>
                        <br><img src="<?php echo base_url().COMP_ICON_INSANI?>" width="300px">
                        <!-- <br><img src="<?php echo base_url().COMP_ICON_BY_INSANI?>" width="70px"> -->
                        <br>
                        <p style="line-height: 16px; padding-top: 10px">
                        <!-- <span style="font-size: 35px;font-family: fantasy; color: #545658"><?php echo strtoupper(COMP_LONG); ?></span><br> -->
                        <!-- <span class="bigger-120"><?php echo strtoupper(APPS_NAME_LONG).'&nbsp;'.APPS_VERSION; ?></span> -->
                        </p>
                      </center>

                      <!-- <center></center> -->
                      <h4 class="header blue lighter bigger">
                        <i class="ace-icon fa fa-lock #0d5280"></i>
                        Form Login
                      </h4>
                      <div class="space-6"></div>
                      <form method="post" action="<?php echo base_url().'login/process'?>" id="form-login">
                        <fieldset>
                          <label class="block clearfix">
                            <label style="font-weight: bold">NIP/Username :</label>
                            <span class="block input-icon input-icon-right">
                              <input type="text" class="form-control" placeholder="Username" name="username" id="username" value="<?php echo set_value('username')?>" style="height: 32px !important;border-radius: 9px !important;"/>
                              <i class="ace-icon fa fa-user"></i>
                              <?php echo form_error('username'); ?>
                            </span>
                          </label>

                          <label class="block clearfix">
                            <label style="font-weight: bold">Password :</label>
                            <span class="block input-icon input-icon-right">
                              <input type="password" class="form-control" placeholder="Password" name="password" id="password" value="<?php echo set_value('password')?>" style="height: 32px !important;border-radius: 9px !important;" />
                              <i class="ace-icon fa fa-lock"></i>
                              <?php echo form_error('password'); ?>
                            </span>
                          </label>

                          <div class="space"></div>

                          <div class="clearfix">
                            <label class="inline">
                              <input type="checkbox" class="ace" />
                              <span class="lbl"> Ingatkan saya</span>
                            </label>

                            <!-- <input type="button" id="button-submit-form" value="Sign In" class="width-35 pull-right btn btn-sm btn-primary" > -->

                            <button id="button-submit-form" name="Submit" type="button" value="submit" class="width-35 pull-right btn btn-sm btn-success" style="background: #0d5280 !important;border-color: #0d5280; line-height: 0.38">
                              <i class="ace-icon fa fa-key"></i>
                              <span class="bigger-110">Masuk</span>
                            </button>

                          </div>
                          
                          <div class="space-4"></div>
                        </fieldset>
                      </form>
                      <div class="space-6"></div>
                      <span style="font-style: italic; padding: 20px">Bridging system :</span>
                      <div class="center" style="width: 100%">
                        <img src="<?php echo base_url().'assets/images/bpjs.jpg'?>" width="60px" style="padding:2px">
                        <img src="<?php echo base_url().'assets/images/satu-sehat.png'?>" width="40px" style="padding:2px">
                        <img src="<?php echo base_url().'assets/images/icare-logo.png'?>" width="90px" style="padding:2px">
                        <img src="<?php echo base_url().'assets/images/kominfo.png'?>" width="110px" style="padding:2px">
                      </div>
                      <br>
                      <div style="width:100% !important; text-align:right; font-size:11px;color:black;padding-top:5px">
                        <span id='ct6' style=" font-size: 12px;" ></span>
                      </div>

                    </div><!-- /.widget-main -->
                    <div class="toolbar clearfix">
                      <div style="width:100% !important; text-align:left;float:left; font-size:11px;color:white;padding-top:5px;padding-left:5px; padding-right:5px">
                        <table border="0" width="100%">
                          <tr>
                            <td style="width: 15%" align="center" valign="top"><img src="<?php echo base_url().'assets/images/pse.png'?>" width="70px"></td>
                            <td valign="top" style="width: 60%">
                              <span style="font-size: 12px; font-weight: bold;"><?php echo strtoupper(APPS_NAME_LONG).'&nbsp;'.APPS_VERSION; ?></span><br>
                              <span style="font-size: 11px">No. PSE. 012514.01/DJAI.PSE/12/2023</span><br>
                              <p style="font-size: 9px;line-height: 12px !important;font-style: italic;padding-top: 8px;">Aplikasi ini telah terdaftar sebagai Penyelenggara Sistem Elektronik pada Direktorat Tata Kelola Aptika KOMINFO</p>
                            </td>
                          </tr>
                        </table>
                      </div>
                    </div>
                  </div><!-- /.widget-body -->
                </div><!-- /.login-box -->

              </div><!-- /.position-relative -->

              <!-- <div class="navbar-fixed-top align-right">
                <br />
                &nbsp;
                <a id="btn-login-dark" href="#">Dark</a>
                &nbsp;
                <span class="blue">/</span>
                &nbsp;
                <a id="btn-login-blur" href="#">Blur</a>
                &nbsp;
                <span class="blue">/</span>
                &nbsp;
                <a id="btn-login-light" href="#">Light</a>
                &nbsp; &nbsp; &nbsp;
              </div> -->
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

    <script>
      function display_ct6() {
        var x = new Date()
        var ampm = x.getHours( ) >= 12 ? ' PM' : ' AM';
        hours = x.getHours( ) % 12;
        hours = hours ? hours : 12;
        var x1=x.getMonth() + 1+ "/" + x.getDate() + "/" + x.getFullYear(); 
        x1 = x1 + " - " +  hours + ":" +  x.getMinutes() + ":" +  x.getSeconds() + ":" + ampm;
        document.getElementById('ct6').innerHTML = x1;
        display_c6();
      }
      function display_c6(){
        var refresh=1000; // Refresh rate in milli seconds
        mytime=setTimeout('display_ct6()',refresh)
      }
      display_c6()
    </script>


    <!-- inline scripts related to this page -->
    <script type="text/javascript">    
      
      
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
        $('#id-text2').attr('class', 'black');
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
              if($(this).valid()) {  
                $('#button-submit-form').click();
              }
              
            }
        });
        
        $( "#button-submit-form" )
          .on("click",function(event) {
            $('#form-login').submit();
        });

        $("#form-login input:text").first().focus();

        /*========== END PROCESS LOGIN ================*/



        
      });

      </script>
  </body>
</html>

