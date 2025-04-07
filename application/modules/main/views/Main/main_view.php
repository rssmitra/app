<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="utf-8" />
    <title><?php echo $app->app_name?></title>

    <meta name="description" content="top menu &amp; navigation" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

    <!-- css default for blank page -->
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.css" />
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/font-awesome.css" />
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace-fonts.css" />
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/css_custom.css" class="ace-main-stylesheet" id="main-ace-style" />
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/AdminLTE.css" class="ace-main-stylesheet" id="main-ace-style" />
    <script src="<?php echo base_url()?>assets/js/ace-extra.js"></script>
    <!-- css default for blank page -->
    <!-- Favicon -->

    <!-- page specific plugin styles -->
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/jquery-ui.custom.css" />
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/jquery.gritter.css" />
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/select2.css" />
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap-editable.css" />

    <link rel="shortcut icon" href="<?php echo base_url().COMP_ICON; ?>">
  </head>

  <body class="no-skin">
    <style>
      
      
    </style>
    <!-- #section:basics/navbar.layout -->
    <!-- <div id="navbar" class="navbar navbar-default navbar-collapse h-navbar" style="background: url('assets/images/<?php echo $app->style_header_color?>.png');"> -->
    <div id="navbar" class="navbar navbar-default navbar-collapse h-navbar" style="background: #0d5280;">
      <script type="text/javascript">
        try{ace.settings.check('navbar' , 'fixed')}catch(e){}
      </script>

      <div class="navbar-container" id="navbar-container">
        <div class="navbar-header pull-left">
          <!-- #section:basics/navbar.layout.brand -->
          <a href="#" class="navbar-brand">
            <!-- <small>
              <img src="<?php echo PATH_IMG_DEFAULT.$app->app_logo?>" width="150px" style="margin: -16px -7px -14px">&nbsp;
              <?php echo $app->app_name?>
            </small> -->
            <small style="color: black; font-weight: bold; font-size: 18px">
            <img src="<?php echo base_url().HEADER_LOGO?>" width="150px" style="margin: -10px -7px -14px">
              <!-- Smart Hospital System 4.0  -->
            </small>
          </a>

          <!-- /section:basics/navbar.layout.brand -->

          <!-- #section:basics/navbar.toggle -->
          <button class="pull-right navbar-toggle navbar-toggle-img collapsed" type="button" data-toggle="collapse" data-target=".navbar-buttons,.navbar-menu">
            <span class="sr-only">Toggle user menu</span>
            <img src="<?php echo (file_exists(base_url().PATH_PHOTO_PROFILE_DEFAULT.$this->session->userdata('user')->path_foto)) ? base_url().PATH_PHOTO_PROFILE_DEFAULT.$this->session->userdata('user')->path_foto:base_url().'assets/avatars/user.jpg'?>" alt="<?php echo $this->session->userdata('user')->fullname?>'s Photo"/>
          <!-- /section:basics/navbar.toggle -->
        </div>

        <!-- #section:basics/navbar.dropdown -->
        <div class="navbar-buttons navbar-header pull-right  collapse navbar-collapse" role="navigation">
          <ul class="nav ace-nav">
            <!-- #section:basics/navbar.user_menu -->
            <li>
              <a href="#">
                <i class="ace-icon fa fa-user"></i>
                <?php echo $this->session->userdata('user')->fullname; ?>
              </a>
            </li>

            <li>
              <a href="#">
                <i class="ace-icon fa fa-calendar"></i>
                <?php echo date('l, d F Y'); ?> 
              </a>
            </li>

            <li>
              <a href="<?php echo base_url().'login/logout'?>">
                <i class="ace-icon fa fa-power-off"></i>
                Logout
              </a>
            </li>


            <!-- /section:basics/navbar.user_menu -->
          </ul>
        </div>

        <!-- /section:basics/navbar.dropdown -->
        <nav role="navigation" class="navbar-menu pull-left collapse navbar-collapse">
          <!-- #section:basics/navbar.nav -->
          <ul class="nav navbar-nav">


            <!-- <li>
              <a href="#">
                <i class="ace-icon fa fa-user"></i>
                <?php echo $this->session->userdata('user')->username; ?>
              </a>
            </li>

            <li>
              <a href="#">
                <i class="ace-icon fa fa-calendar"></i>
                <?php echo date('l, d F Y'); ?> 
              </a>
            </li> -->


          </ul>

        </nav>
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
          <!-- #section:basics/content.breadcrumbs -->
          <?php
            $arr_color_breadcrumbs = array('#ef8122');
            shuffle($arr_color_breadcrumbs);
          ?>
          <div class="breadcrumbs" id="breadcrumbs" style="background-color:<?php echo array_shift($arr_color_breadcrumbs)?>">
            <script type="text/javascript">
              try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
            </script>
          </div>
          <div class="page-content-main">
            
            <div class="row">
              <div class="col-xs-12">
                <!-- PAGE CONTENT BEGINS -->
                <!-- MODULE MENU -->
                
                  <?php 
                    foreach($modul as $key_row=>$rows_m) :
                      $arr_color[$key_row] = array('#00679f','#f77f0a','#b279c0','#e51494', '#00679f','#f77f0a','#b279c0','#c2e761','#e51494'); 
                      $arr_color_title = array('#00679f'); 
                      shuffle($arr_color[$key_row]);
                      shuffle($arr_color_title);

                  ?>
                    <div class="row">
                    <h3 class="header smaller lighter " style="font-weight: bold; color: <?php echo array_shift($arr_color_title)?>">
                        <?php echo $rows_m['group_modul_name']?> 
                      </h3>
                      <?php foreach($rows_m['modul'] as $row_modul) : ?>

                        <?php 
                          if($row_modul->is_new_tab=='N'){
                            $href = 'href="'.base_url().'dashboard?mod='.$row_modul->modul_id.'"';
                          }else{
                            $href = 'href="'.$row_modul->link_on_new_tab.'" target="_blank"';
                          }
                        ?>

                        <div class="col-sm-2 widget-container-col ui-sortable" id="widget-container-col-11">
                          <div class="widget-box widget-color-dark ui-sortable-handle" id="widget-box-11">
                            <div class="widget-body" style="background: #00679f; border-top-right-radius: 25px; border-bottom-right-radius: 25px; border-bottom-left-radius: 25px">
                              <div class="widget-main" data-size="125" style="position: relative;">
                                <div  style="cursor:pointer">
                                  <a <?php echo $href?> style="text-decoration: none" class="small-box-footer">
                                    <div class="content">
                                      <div class="center">
                                        <span style="color: white; font-weight: bold"><i class="<?php echo $row_modul->icon?> bigger-300"></i><br><?php echo strtoupper($row_modul->name)?></span>
                                      </div>
                                    </div>
                                  </a>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                        
                      <?php endforeach; ?>
                    </div>
                  <?php endforeach; ?>
                <!-- END MODULE MENU -->

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
              <?php echo $app->footer?>
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
      window.jQuery || document.write("<script src='<?php echo base_url()?>assets/js/jquery.js'>"+"<"+"/script>");
    </script>

    <script type="text/javascript">
      if('ontouchstart' in document.documentElement) document.write("<script src='<?php echo base_url()?>assets/js/jquery.mobile.custom.js'>"+"<"+"/script>");
    </script>
    <script src="<?php echo base_url()?>assets/js/bootstrap.js"></script>

    <!-- page specific plugin scripts -->

    <!--[if lte IE 8]>
      <script src="<?php echo base_url()?>assets/js/excanvas.js"></script>
    <![endif]-->
    <script src="<?php echo base_url()?>assets/js/jquery-ui.custom.js"></script>
    <script src="<?php echo base_url()?>assets/js/jquery.ui.touch-punch.js"></script>
    <script src="<?php echo base_url()?>assets/js/jquery.gritter.js"></script>
    
    <!-- achtung loader -->
    <link href="<?php echo base_url()?>assets/achtung/ui.achtung-mins.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="<?php echo base_url()?>assets/achtung/ui.achtung-min.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>assets/achtung/achtung.js"></script> 

    <script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery.form.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery-validation/dist/jquery.validate.js"></script>

    <!-- the following scripts are used in demo only for onpage help and you don't need them -->
    <!-- <link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace.onpage-help.css" />

    <script type="text/javascript"> ace.vars['base'] = '..'; </script>
    <script src="<?php echo base_url()?>assets/js/ace/elements.onpage-help.js"></script>
    <script src="<?php echo base_url()?>assets/js/ace/ace.onpage-help.js"></script> -->
    <script src="<?php echo base_url()?>assets/js/custom/menu_load_page.js"></script>
    <script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
    <script>
      jQuery(function($) {

        $('.date-picker').datepicker({
          autoclose: true,
          todayHighlight: true
        })
        //show datepicker when clicking on the icon
        .next().on(ace.click_event, function(){
          $(this).prev().focus();
        });

      });

      $(document).ready(function(){
        
        // <?php if( !isset($profile_user->up_id) ) :?>
        //   $('.page-content-main').load('main/Main/form_profile');
        // <?php endif; ?>

        $('#form_tmp_user').ajaxForm({
          beforeSend: function() {
            achtungShowLoader();  
          },
          uploadProgress: function(event, position, total, percentComplete) {
          },
          complete: function(xhr) {     
            var data=xhr.responseText;
            var jsonResponse = JSON.parse(data);

            if(jsonResponse.status === 200){
              $.achtung({message: jsonResponse.message, timeout:3});
              $('#message_success').show({
                  speed: 'slow',
                  timeout: 5000,
              });
            }else{
              $.achtung({message: jsonResponse.message, timeout:5});
            }
            achtungHideLoader();
          }
        });

        $('#form_update_profile').ajaxForm({
          beforeSend: function() {
            achtungShowLoader();  
          },
          uploadProgress: function(event, position, total, percentComplete) {
          },
          complete: function(xhr) {     
            var data=xhr.responseText;
            var jsonResponse = JSON.parse(data);

            if(jsonResponse.status === 200){
              $.achtung({message: jsonResponse.message, timeout:3});
              $('#message_success').show({
                  speed: 'slow',
                  timeout: 1000,
              });
            }else{
              $.achtung({message: jsonResponse.message, timeout:5});
            }
            achtungHideLoader();
          }
        });


      })

      function exc_my_account() {
        $('#form_tmp_user').submit();
        return false;
      }

      function exc_update_profile() {
        $('#form_update_profile').submit();
        return false;
      }

      
    </script>

    
  </body>
</html>
