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
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/AdminLTE.css" class="ace-main-stylesheet" id="main-ace-style" />
    <script src="<?php echo base_url()?>assets/js/ace-extra.js"></script>
    <!-- css default for blank page -->
    <!-- Favicon -->

    <!-- page specific plugin styles -->
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />

    <link rel="shortcut icon" href="<?php echo base_url().COMP_ICON; ?>">
  </head>

  <body class="no-skin">
    <!-- #section:basics/navbar.layout -->
    <div id="navbar" class="navbar navbar-default navbar-collapse h-navbar" style="background: white;">
      <script type="text/javascript">
        try{ace.settings.check('navbar' , 'fixed')}catch(e){}
      </script>

      <div class="navbar-container" id="navbar-container">
        <div class="navbar-header pull-left">
          <!-- #section:basics/navbar.layout.brand -->
          <a href="#" class="navbar-brand">
            <small>
              <img src="<?php echo base_url().PATH_IMG_DEFAULT.$app->app_logo?>" width="150px" style="margin: -16px -7px -14px">&nbsp;
              <?php echo $app->app_name?>
            </small>
          </a>

          <!-- #section:basics/navbar.toggle -->
          <button class="pull-right navbar-toggle navbar-toggle-img collapsed" type="button" data-toggle="collapse" data-target=".navbar-buttons,.navbar-menu">
            <span class="sr-only">Toggle user menu</span>
            <img src="<?php echo isset($this->session->userdata('user')->path_foto) ? base_url().PATH_PHOTO_PROFILE_DEFAULT.$this->session->userdata('user')->path_foto:base_url().'assets/avatars/user.jpg'?>" alt="<?php echo $this->session->userdata('user')->fullname?>'s Photo"/>
          </button>

          <!-- /section:basics/navbar.toggle -->
        </div>

        <!-- #section:basics/navbar.dropdown -->
        <div class="navbar-buttons navbar-header pull-right  collapse navbar-collapse" role="navigation">
          <ul class="nav ace-nav">
            <!-- #section:basics/navbar.user_menu -->
            <li>
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
            </li>
            <li class="light-blue user-min">
              <a data-toggle="dropdown" href="#" class="dropdown-toggle">
              <img class="nav-user-photo" src="<?php echo isset($this->session->userdata('user')->path_foto) ? base_url().PATH_PHOTO_PROFILE_DEFAULT.$this->session->userdata('user')->path_foto:base_url().'assets/avatars/user.jpg'?>" alt="<?php echo $this->session->userdata('user')->fullname?>'s Photo" height="95%"/>
                <span class="user-info">
                  <small>Welcome,</small>
                  <i><?php echo $this->session->userdata('user')->username?></i>
                </span>

                <i class="ace-icon fa fa-caret-down"></i>
              </a>

              <ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
              
                <li>
                  <a href="<?php echo base_url().'login/logout'?>">
                    <i class="ace-icon fa fa-power-off"></i>
                    Logout
                  </a>
                </li>
              </ul>
            </li>
            <!-- /section:basics/navbar.user_menu -->
          </ul>
        </div>

      </div><!-- /.navbar-container -->
    </div>

    <!-- /section:basics/navbar.layout -->
    <div class="main-container" id="main-container">
      <script type="text/javascript">
        try{ace.settings.check('main-container' , 'fixed')}catch(e){}
      </script>

      <!-- main content -->
      <div class="main-content">
        <div class="main-content-inner">
          <!-- #section:basics/content.breadcrumbs -->
          <?php
            $arr_color_breadcrumbs = array('#076960');
            shuffle($arr_color_breadcrumbs);
          ?>
          <div class="breadcrumbs" id="breadcrumbs" style="background-color:<?php echo array_shift($arr_color_breadcrumbs)?>">
            <script type="text/javascript">
              try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
            </script>
          </div>
          <div class="row">
              <div class="col-md-12">
                
                  <h3 class="header smaller lighter blue">
                    Dashboard Kunjungan Pasien  <span style="font-size: 14px !important"> <i class="fa fa-angle-double-right"></i> s.d Bulan November Tahun 2020</span>
                  </h3>

                  <div class="row">
                    
                    <div class="col-sm-12 infobox-container">

  										<div class="infobox infobox-green">
  											<div class="infobox-icon">
  												<i class="ace-icon fa fa-comments"></i>
  											</div>

  											<div class="infobox-data">
  												<span class="infobox-data-number">32</span>
  												<div class="infobox-content">Pendaftaran Pasien</div>
  											</div>

  											<div class="stat stat-success">8%</div>
  										</div>

  										<div class="infobox infobox-blue">
  											<div class="infobox-icon">
  												<i class="ace-icon fa fa-twitter"></i>
  											</div>

  											<div class="infobox-data">
  												<span class="infobox-data-number">11</span>
  												<div class="infobox-content">Kunjungan Poli/Klinik</div>
  											</div>
  											<div class="badge badge-success">
  												+32%
  												<i class="ace-icon fa fa-arrow-up"></i>
  											</div>
  										</div>

  										<div class="infobox infobox-pink">
  											<div class="infobox-icon">
  												<i class="ace-icon fa fa-shopping-cart"></i>
  											</div>

  											<div class="infobox-data">
  												<span class="infobox-data-number">8</span>
  												<div class="infobox-content">Laboratorium</div>
  											</div>
  											<div class="stat stat-important">4%</div>
  										</div>

  										<div class="infobox infobox-red">
  											<div class="infobox-icon">
  												<i class="ace-icon fa fa-flask"></i>
  											</div>

  											<div class="infobox-data">
  												<span class="infobox-data-number">7</span>
  												<div class="infobox-content">Radiologi</div>
  											</div>
                      </div>
                      
                      <div class="infobox infobox-red">
  											<div class="infobox-icon">
  												<i class="ace-icon fa fa-flask"></i>
  											</div>

  											<div class="infobox-data">
  												<span class="infobox-data-number">7</span>
  												<div class="infobox-content">Fisioterapi</div>
  											</div>
  										</div>

  										

  										<div class="space-6"></div>

  									</div>

                  </div>
                    
              </div><!-- /.col -->
            </div>

        </div>
      </div>

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

    <!-- <![endif]-->

    <!--[if IE]>
<script type="text/javascript">
 window.jQuery || document.write("<script src='../assets/js/jquery1x.js'>"+"<"+"/script>");
</script>
<![endif]-->
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
  

      })

    </script>

    
  </body>
</html>
