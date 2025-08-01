<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="UTF-8">
    <meta name="description" content="Smart Hospital System 4.0 adalah sistem rumah sakit yang sudah terintegrasi antar modulnya dan juga terintegrasi dengan BPJS seperti VClaim, ICare JKN, Antrian Online, MJKN, Pcare, Satu Sehat Kemenkes, dsb." />
    <meta name="keywords" content="SIMRS, Smart Hospital, Bridging, BPJS">
    <meta name="author" content="<?php echo COMP_LONG; ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

    <title><?php echo $app->header_title?></title>
    <!-- bootstrap & fontawesome -->

    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.css" />
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/font-awesome.css" />

    <!-- page specific plugin styles -->
    <!-- <link rel="stylesheet" href="<?php echo base_url()?>assets/css/AdminLTE.css" class="ace-main-stylesheet" id="main-ace-style" /> -->
    <!-- text fonts -->
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace-fonts.css" />
    <!-- css date-time -->
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap-timepicker.css" />
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
    <!-- end css date-time -->
    <!-- ace styles -->
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/css_custom.css" />
    <link rel="shortcut icon" href="<?php echo base_url().COMP_ICON; ?>">
    <style type="text/css">
      .highcharts-data-labels span {
          width: 100px !important; 
          word-break: break-word !important;
          white-space: normal !important;
      }
      

    </style>
    <!--[if lte IE 9]>
      <link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace-part2.css" class="ace-main-stylesheet" />
    <![endif]-->

    <!--[if lte IE 9]>
      <link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace-ie.css" />
    <![endif]-->

    <!-- inline styles related to this page -->

    <!-- ace settings handler -->
    <script src="<?php echo base_url()?>assets/js/ace-extra.js"></script>

    <!-- HTML5shiv and Respond.js for IE8 to support HTML5 elements and media queries -->

    <!--[if lte IE 8]>
    <script src="<?php echo base_url()?>assets/js/html5shiv.js"></script>
    <script src="<?php echo base_url()?>assets/js/respond.js"></script>
    <![endif]-->
  </head>
  
  <body class="no-skin">
    <!-- #section:basics/navbar.layout -->
    <div id="navbar" class="navbar navbar-default navbar-fixed-top" style="background-color: #0d5280; border-bottom: 7px solid #ef8122; border-bottom-left-radius: 20px;; border-bottom-right-radius: 20px;">
      <script type="text/javascript">
        try{ace.settings.check('navbar' , 'fixed')}catch(e){}
      </script>

      <div class="navbar-container" id="navbar-container">
        <!-- #section:basics/sidebar.mobile.toggle -->
        <button type="button" class="navbar-toggle menu-toggler pull-left" id="menu-toggler" data-target="#sidebar">
          <span class="sr-only">Toggle sidebar</span>

          <span class="icon-bar"></span>

          <span class="icon-bar"></span>

          <span class="icon-bar"></span>
        </button>

        <!-- /section:basics/sidebar.mobile.toggle -->
        <div class="navbar-header pull-left">
          <!-- #section:basics/navbar.layout.brand -->
          <a href="#" class="navbar-brand">
            <small>
              <!-- <i class="<?php echo $app->icon?>"></i> -->
              <a href="<?php echo base_url().'main'?>">
                <img src="<?php echo base_url().HEADER_LOGO?>" width="120px" style="margin: 4px -7px -14px"> &nbsp;
              <!-- <?php echo $app->app_name?> -->
              </a>
            </small>
          </a>

          <!-- /section:basics/navbar.layout.brand -->

          <!-- #section:basics/navbar.toggle -->

          <!-- /section:basics/navbar.toggle -->
        </div>

        <!-- #section:basics/navbar.dropdown -->
        <div class="navbar-buttons navbar-header pull-right hidden-480" role="navigation">
          <ul class="nav ace-nav">

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
            <!-- #section:basics/navbar.user_menu -->
            <li class="light-blue">
              <a data-toggle="dropdown" href="#" class="dropdown-toggle">
                <img class="nav-user-photo" src="<?php echo (file_exists(base_url().PATH_PHOTO_PROFILE_DEFAULT.$this->session->userdata('user')->path_foto)) ? base_url().PATH_PHOTO_PROFILE_DEFAULT.$this->session->userdata('user')->path_foto:base_url().'assets/avatars/user.jpg'?>" alt="<?php echo $this->session->userdata('user')->fullname?>'s Photo" height="95%"/>
                <span class="user-info">
                  <small>Welcome,</small>
                  <?php echo substr($this->session->userdata('user')->username, 0, 8)?>
                </span>

                <i class="ace-icon fa fa-caret-down"></i>
              </a>

              <ul class="user-menu dropdown-menu-right dropdown-menu dropdown-yellow dropdown-caret dropdown-close">
                <li>
                  <a href="#" onclick="getMenu('setting/Tmp_user/account_setting')">
                    <i class="ace-icon fa fa-key"></i>
                    Account
                  </a>
                </li>

                <li>
                  <a href="#" onclick="getMenu('setting/Tmp_user/form_update_profile')">
                    <i class="ace-icon fa fa-user"></i>
                    Profile
                  </a>
                </li>

                <li class="divider"></li>

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

        <!-- /section:basics/navbar.dropdown -->
      </div><!-- /.navbar-container -->
        <nav role="navigation" class="navbar-menu pull-left collapse navbar-collapse">
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
    </div>

    <!-- /section:basics/navbar.layout -->
    <div class="main-container" id="main-container">
      <script type="text/javascript">
        try{ace.settings.check('main-container' , 'fixed')}catch(e){}
      </script>
      <?php
            $arr_color_breadcrumbs = array('#0d528052');
            shuffle($arr_color_breadcrumbs);
          ?>
          <div class="breadcrumbs" id="breadcrumbs" style="">
            <script type="text/javascript">
              try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
            </script>
          </div>

      <!-- #section:basics/sidebar -->
      <div id="sidebar" class="sidebar responsive sidebar-fixed">
        <script type="text/javascript">
          try{ace.settings.check('sidebar' , 'fixed')}catch(e){}
        </script>

        <div class="sidebar-shortcuts" id="sidebar-shortcuts">
          <div class="sidebar-shortcuts-large" id="sidebar-shortcuts-large">
            <?php
              $arr_color = array('btn btn-success','btn btn-danger','btn btn-warning','btn btn-primary'); 
              shuffle($arr_color);
              //echo '<pre>';print_r($shortcut);die;
              if(count($shortcut) > 0){
                foreach ($shortcut as $key_shortcut => $value_shortcut) {
                    if($value_shortcut['set_shortcut']=='Y'){
                      echo '<a class="'.array_shift($arr_color).'" onclick="getMenu('."'".$value_shortcut['link']."'".')"><i class="ace-icon '.$value_shortcut['icon'].'"></i></a>&nbsp;';
                    }
                }
              }else{
                echo '-No shortcut menu available-';
              }
            ?>


            <!-- /section:basics/sidebar.layout.shortcuts -->
          </div>

          <div class="sidebar-shortcuts-mini" id="sidebar-shortcuts-mini">

            <span class="btn btn-success"></span>

            <span class="btn btn-info"></span>

            <span class="btn btn-warning"></span>

            <span class="btn btn-danger"></span>

          </div>
        </div><!-- /.sidebar-shortcuts -->

        <!-- /.nav-list -->

        <ul class="nav nav-list">

          <li class="hover">
              <a href="#" onclick="getMenu('main/Main/modul_view')">
                  <i class="menu-icon fa fa-arrow-left"></i>
                  <span class="menu-text"> Menu Utama </span>
              </a>
              <b class="arrow"></b>
          </li>

          <li class="hover">
              <a href="<?php echo base_url().'dashboard?mod='.$_GET['mod'].''?>">
                  <i class="menu-icon fa fa-dashboard"></i>
                  <span class="menu-text"> Dashboard </span>
              </a>
              <b class="arrow"></b>
          </li>

          <?php
              if(count($menu) > 0){
              foreach ($menu as $key => $value) {
                  $string_link = ''.$value['link'].'';
          ?>

          <li class="">
              <a href="#" <?php echo ( $value['link'] == '#' ) ? 'class="dropdown-toggle"' : '' ;?>  <?php if( $value['link'] != '#' ){?> onclick="getMenu('<?php echo $value['link']?>')" <?php }?>>
                  <i class="menu-icon <?php echo $value['icon']?>"></i>
                  <span class="menu-text"> <?php echo $value['name']?> </span>
                  <?php echo ( $value['link'] == '#' ) ? '<b class="arrow fa fa-angle-down"></b>' : '' ;?>
              </a>

              <b class="arrow"></b>
              <?php if( count($value['submenu']) != 0 ){?>
              <ul class="submenu">
                  <?php foreach($value['submenu'] as $row_sub_menu){ ?>
                  <li class="">
                      <a href="#" onclick="getMenu('<?php echo $row_sub_menu['link']?>')">
                          <i class="menu-icon fa fa-caret-right"></i>
                          <?php echo $row_sub_menu['name']?>
                      </a>

                      <b class="arrow"></b>
                  </li>
                  <?php }?>
              </ul>
              <?php }?>

          </li>

          <?php } }?>
          <li>
              <a href="<?php echo base_url().'laporan/Global_report'?>" target="_blank">
                  <i class="menu-icon fa fa-archive"></i>
                  <span class="menu-text"> Laporan Umum </span>
              </a>
              <b class="arrow"></b>
          </li>

          <li>
              <a href="<?php echo base_url().'login/logout'?>">
                  <i class="menu-icon fa fa-power-off"></i>
                  <span class="menu-text"> Logout </span>
              </a>
              <b class="arrow"></b>
          </li>

        </ul>

        <!-- #section:basics/sidebar.layout.minimize -->
        <div class="sidebar-toggle sidebar-collapse" id="sidebar-collapse">
          <i class="ace-icon fa fa-angle-double-left" data-icon1="ace-icon fa fa-angle-double-left" data-icon2="ace-icon fa fa-angle-double-right"></i>
        </div>

        <!-- /section:basics/sidebar.layout.minimize -->
        <script type="text/javascript">
          try{ace.settings.check('sidebar' , 'collapsed')}catch(e){}
        </script>
      </div>

      <!-- /section:basics/sidebar -->
      <div class="main-content">
        <div class="main-content-inner">
          <!-- /section:basics/content.breadcrumbs -->
          <div class="page-content" style="min-height: 700px !important">
            <!-- #section:settings.box -->

            <!-- <div class="ace-settings-container" id="ace-settings-container" style="position: fixed">
              <div class="btn btn-app btn-xs btn-warning ace-settings-btn" id="ace-settings-btn">
                <i class="ace-icon fa fa-cog bigger-130"></i>
              </div>

              <div class="ace-settings-box clearfix" id="ace-settings-box">

                <div class="pull-left width-200">

                  <div class="ace-settings-item">
                    <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-navbar" />
                    <label class="lbl" for="ace-settings-navbar"> Fixed Navbar</label>
                  </div>

                  <div class="ace-settings-item">
                    <input type="checkbox" class="ace ace-checkbox-2" id="ace-settings-sidebar" />
                    <label class="lbl" for="ace-settings-sidebar"> Fixed Sidebar</label>
                  </div>

                </div>
              </div>
            </div> -->
            
            <!-- /section:settings.box -->
                <!-- PAGE CONTENT BEGINS -->
                <div id="page-area-content">

                  <div class="row">
                    <!-- content here -->
                      <div class="page-header">
                          <h1>
                            <?php echo $title?>
                            <small>
                              <i class="ace-icon fa fa-angle-double-right"></i>
                              <?php echo isset($breadcrumbs)?$breadcrumbs:''?>
                            </small>
                          </h1>
                        </div>
                        <div class="col-sm-12">
                        <h4><b>Modul <?php echo $module->name?></b></h4>
                        <?php echo $module->description?>
                      </div>
                      
                      <div id="content_graph"></div>
                      
                    <!-- end content here -->
                  </div>
                </div>
                <!-- PAGE CONTENT ENDS -->
          </div><!-- /.page-content -->
        </div>
      </div><!-- /.main-content -->

      <div class="footer">
        <div class="footer-inner">
          <div class="footer-content">
            <span class="bigger-120">
              <?php echo $app->footer?> - <?php echo date('Y')?>
            </span>
          </div>
        </div>
      </div>

      <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
        <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
      </a>

    </div><!-- /.main-container -->

    <div id="proses-loading">
        <div class="loading-content">
            <img width="125px" src="<?php echo base_url('assets/images/logo.png') ?>" alt="Logo <?php echo COMP_SORT; ?>">
            <br>
            <i class="fa fa-cog fa-spin fa-3x fa-fw"></i>
            <br>
            <span class="">Transaksi sedang di proses harap menunggu</span>
        </div>
    </div>

    <div id="ModalSuccess" class="modal fade" tabindex="-1">

      <div class="modal-dialog" style="overflow-y: scroll; max-height:50%;  margin-top: 50px; margin-bottom:50px;width:50%;">

        <div class="modal-content">

          <div class="modal-header">

            <!-- <div class="table-header"> -->

              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">

                <span class="white">&times;</span>

              </button>

              <span id="result_text_create_sep">SUKSES</span>

            <!-- </div> -->

          </div>

          <div class="modal-body" style="min-height: 400px !important">

            <div class="alert alert-succcess">
              BERHASIL
            </div>

          </div>

        </div><!-- /.modal-content -->

      </div><!-- /.modal-dialog -->

    </div>

    <!-- GLOBAL MODAL -->

    <div id="globalModalView" class="modal fade" tabindex="-1">

      <div class="modal-dialog" style="overflow-y: scroll; max-height:90%;  margin-top: 50px; margin-bottom:50px;width:90%">

        <div class="modal-content">

          <div class="modal-header">

            <!-- <div class="table-header"> -->

              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">

                <span class="white">&times;</span>

              </button>

              <span id="text_title" style="font-size: 14px; color: white">TITLE</span>

            <!-- </div> -->

          </div>

          <div class="modal-body" style="min-height: 400px !important">

            <div id="global_modal_content_detail"></div>

          </div>

        </div><!-- /.modal-content -->

      </div><!-- /.modal-dialog -->

    </div>

    <div id="globalModalViewWithiFrame" class="modal fade" tabindex="-1">

      <div class="modal-dialog" style="overflow-y: scroll; max-height:90%;  margin-top: 50px; margin-bottom:50px;width:90%">

        <div class="modal-content">

          <div class="modal-header">

            <!-- <div class="table-header"> -->

              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">

                <span class="white">&times;</span>

              </button>

              <span id="text_title_iframe" style="font-size: 14px; color: white">TITLE</span>

            <!-- </div> -->

          </div>

          <div class="modal-body">

            <iframe id="content_iframe" style="width: 100%; height: 750px !important"></iframe>

          </div>

        </div><!-- /.modal-content -->

      </div><!-- /.modal-dialog -->

    </div>

    <div id="globalModalViewMedium" class="modal fade" tabindex="-1">

      <div class="modal-dialog" style="overflow-y: scroll; max-height:85%;  margin-top: 50px; margin-bottom:50px;width:70%; background-color: white">

        <div class="modal-content">

          <div class="modal-header">

            <!-- <div class="table-header"> -->

              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">

                <span class="white">&times;</span>

              </button>

              <span id="text_title_medium" style="color: white; font-weight: bold">TITLE</span>

            <!-- </div> -->

          </div>

          <div class="modal-body" style="min-height: 400px !important">

            <div id="global_modal_content_detail_medium" style="background-color: white"></div>

          </div>

        </div><!-- /.modal-content -->

      </div><!-- /.modal-dialog -->

    </div>

    <div id="globalModalViewSmall" class="modal fade" tabindex="-1">

      <div class="modal-dialog" style="overflow-y: scroll; max-height:35%;  margin-top: 50px; margin-bottom:50px;width:45%; background-color: white">

        <div class="modal-content">

          <div class="modal-header">

            <!-- <div class="table-header"> -->

              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">

                <span class="white">&times;</span>

              </button>

              <span id="text_title_small" style="color: white; font-weight: bold">TITLE</span>

            <!-- </div> -->

          </div>

          <div class="modal-body">

            <div id="global_modal_content_detail_small" style="background-color: white"></div>

          </div>

        </div><!-- /.modal-content -->

      </div><!-- /.modal-dialog -->

    </div>
    
    
    <!-- <script> 
      document.addEventListener('contextmenu', event=> event.preventDefault()); 
      document.onkeydown = function(e) { 
      if(event.keyCode == 123) { 
      return false; 
      } 
      if(e.ctrlKey && e.shiftKey && e.keyCode == 'I'.charCodeAt(0)){ 
      return false; 
      } 
      if(e.ctrlKey && e.shiftKey && e.keyCode == 'J'.charCodeAt(0)){ 
      return false; 
      } 
      if(e.ctrlKey && e.keyCode == 'U'.charCodeAt(0)){ 
      return false; 
      } 
      } 
      </script>  -->
    <!--[if !IE]> -->
    <script type="text/javascript">
      window.jQuery || document.write("<script src='<?php echo base_url()?>/assets/js/jquery.js'>"+"<"+"/script>");
    </script>

    <script type="text/javascript" src="<?php echo base_url()?>assets/jSignature/jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>assets/jSignature/jquery-ui.min.js"></script>



    <script type="text/javascript">
      if('ontouchstart' in document.documentElement) document.write("<script src='<?php echo base_url()?>/assets/js/jquery.mobile.custom.js'>"+"<"+"/script>");
    </script>
    <script src="<?php echo base_url()?>assets/js/bootstrap.js"></script>

    <script src="<?php echo base_url()?>assets/js/bootstrap-multiselect.js"></script>

    <!-- page specific plugin scripts -->


    <script src="<?php echo base_url()?>/assets/js/dataTables/jquery.dataTables.js"></script>
    <script src="<?php echo base_url()?>/assets/js/dataTables/jquery.dataTables.bootstrap.js"></script>
    <script src="<?php echo base_url()?>/assets/js/dataTables/extensions/TableTools/js/dataTables.tableTools.js"></script>
    <script src="<?php echo base_url()?>/assets/js/dataTables/extensions/ColVis/js/dataTables.colVis.js"></script>
    
    <!-- ace scripts -->
    <script src="<?php echo base_url()?>assets/js/ace/elements.scroller.js"></script>
    <script src="<?php echo base_url()?>assets/js/ace/elements.colorpicker.js"></script>
    <script src="<?php echo base_url()?>assets/js/ace/elements.fileinput.js"></script>
    <script src="<?php echo base_url()?>assets/js/ace/elements.typeahead.js"></script>
    <script src="<?php echo base_url()?>assets/js/ace/elements.wysiwyg.js"></script>
    <script src="<?php echo base_url()?>assets/js/ace/elements.spinner.js"></script>
    <script src="<?php echo base_url()?>assets/js/ace/elements.treeview.js"></script>
    <script src="<?php echo base_url()?>assets/js/ace/elements.wizard.js"></script>
    <script src="<?php echo base_url()?>assets/js/ace/elements.aside.js"></script>
    <script src="<?php echo base_url()?>assets/js/ace/ace.js"></script>
    <script src="<?php echo base_url()?>assets/js/ace/ace.ajax-content.js"></script>
    <script src="<?php echo base_url()?>assets/js/ace/ace.touch-drag.js"></script>
    <script src="<?php echo base_url()?>assets/js/ace/ace.sidebar.js"></script>
    <script src="<?php echo base_url()?>assets/js/ace/ace.sidebar-scroll-1.js"></script>
    <script src="<?php echo base_url()?>assets/js/ace/ace.submenu-hover.js"></script>
    <script src="<?php echo base_url()?>assets/js/ace/ace.widget-box.js"></script>
    <script src="<?php echo base_url()?>assets/js/ace/ace.settings.js"></script>
    <script src="<?php echo base_url()?>assets/js/ace/ace.settings-rtl.js"></script>
    <script src="<?php echo base_url()?>assets/js/ace/ace.settings-skin.js"></script>
    <script src="<?php echo base_url()?>assets/js/ace/ace.widget-on-reload.js"></script>
    <script src="<?php echo base_url()?>assets/js/ace/ace.searchbox-autocomplete.js"></script>

    <!-- achtung loader -->
    <link href="<?php echo base_url()?>assets/achtung/ui.achtung-mins.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="<?php echo base_url()?>assets/achtung/ui.achtung-min.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>assets/achtung/achtung.js"></script> 

    <script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery.form.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery-validation/dist/jquery.validate.js"></script>
    
    <script type="text/javascript"> ace.vars['base'] = '..'; </script>
    <script src="<?php echo base_url()?>assets/js/ace/elements.onpage-help.js"></script>
    <script src="<?php echo base_url()?>assets/js/ace/ace.onpage-help.js"></script>

    <!-- highchat modules -->
    <script src="<?php echo base_url()?>assets/chart/highcharts.js"></script>
    <script src="<?php echo base_url()?>assets/chart/modules/exporting.js"></script>
    <script src="<?php echo base_url()?>assets/chart/modules/script.js"></script>
    <!-- end highchat modules -->
    
    <script src="<?php echo base_url()?>assets/js/custom/menu_load_page.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>assets/jquery_number/jquery.number.js"></script>
    
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/chosen.css" />
    <script src="<?php echo base_url()?>assets/js/chosen.jquery.js"></script>

    <script type="text/javascript">
      
      $('.format_number').number( true, 2 );

      function changeTotalBiaya(field, id){
        //alert(field); return false;
        /*harga * diskon*/
        var harga_awal = $('#'+field+'_'+id).val();
        var input_persen = $('#diskon_'+field+'_'+id).val();
        var hidden_diskon = $('#hidden_diskon_'+field+'_'+id).val();

        // if bill dr 
        if( field == 'bill_dr1' || field == 'bill_dr2' || field == 'bill_dr3'){
          var result_bill_dr = (parseInt(harga_awal) * input_persen/100) * (70/100);
          var result_pendapatan_rs = (input_persen == 0) ? parseInt(hidden_diskon) :(parseInt(harga_awal) * input_persen/100) * (30/100);

          // last price dookter
          var last_price = parseInt(result_bill_dr) + parseInt(harga_awal);
          // last price pendapatan rs
          var harga_awal_pendapatan = $('#pendapatan_rs_'+id).val();
          
          if(result_bill_dr >= 0){
            var pendapatan_rs = parseInt(harga_awal_pendapatan) + parseInt(result_pendapatan_rs);
          }else if(result_bill_dr < 0){
            var pendapatan_rs = parseInt(harga_awal_pendapatan) - parseInt(result_pendapatan_rs);
          }
          // var pendapatan_rs = (result_bill_dr < 1) ? parseInt(harga_awal_pendapatan) + parseInt(result_pendapatan_rs) : parseInt(harga_awal_pendapatan) - parseInt(result_pendapatan_rs);
          
          $('#total_diskon_pendapatan_rs_'+id+'').val( pendapatan_rs );
          $('#pendapatan_rs_'+id+'').val( pendapatan_rs );
          format_pendapatan_rs = formatMoney(pendapatan_rs);
          $('#text_total_diskon_pendapatan_rs_'+id).text( format_pendapatan_rs );

        }else{
            kenaikan_tarif = harga_awal * input_persen/100;
            var last_price = parseInt(harga_awal) + parseInt(kenaikan_tarif);
        }
      
        $('#total_diskon_'+field+'_'+id).val( last_price );
        var formatTxt = formatMoney(last_price);
        $('#text_total_diskon_'+field+'_'+id).text( formatTxt );

        /*sum class name*/
        sum = sumClass('total_diskon_'+id+'');
        sumFormat = formatMoney(sum);
        $('#total_biaya_'+id+'').text( sumFormat );
        $('#hidden_diskon_'+field+'_'+id).val(result_pendapatan_rs);

        console.log(field+'|'+id+'|'+harga_awal+'|'+result_bill_dr+'|'+result_pendapatan_rs);
        console.log(pendapatan_rs+'|'+harga_awal_pendapatan+'|'+hidden_diskon);

      }
      
      function show_modal(url, title){  
          preventDefault();
          $('#text_title').text(title);
          $('#global_modal_content_detail').html('Loading...'); 
          $('#global_modal_content_detail').load(url); 
          $("#globalModalView").modal();
      }

      function show_modal_with_iframe(url, title){  

        preventDefault();

        $('#text_title_iframe').text(title);

        $('#content_iframe').attr('src', url); 

        $("#globalModalViewWithiFrame").modal();

      }

      function show_modal_medium(url, title){  

        preventDefault();

        $('#text_title_medium').text(title);

        $('#global_modal_content_detail_medium').load(url); 

        $("#globalModalViewMedium").modal();

      }

      function show_modal_small(url, title){  

        preventDefault();

        $('#text_title_small').text(title);

        $('#global_modal_content_detail_small').load(url); 

        $("#globalModalViewSmall").modal();

      }

      function show_modal_return_json(url, title){  
          preventDefault();
          $('#global_modal_content_detail').html('Loading...'); 
          $.getJSON(url, '' , function (data) {
            $('#global_modal_content_detail').html(data.html);
          })
          $('#text_title').text(title);
          $("#globalModalView").modal();
      }

      function show_modal_medium_return_json(url, title){  

        preventDefault();

        $.getJSON(url, '' , function (data) {
          $('#global_modal_content_detail_medium').html(data.html);
        })
        
        $('#text_title_medium').text(title);

        $("#globalModalViewMedium").modal();

      }

      function show_modal_with_html(html, value_obj){  

        preventDefault();
        
        // set value input
        console.log(value_obj);
        $.each(value_obj, function(i, item) {
          var text = item;
          text = text.replace(/\+/g, ' ');
          console.log(i+' = '+text);
          $('#'+i).val(item);
        });
        $('#global_modal_content_detail_medium').html(html);
        // $('#text_title_medium').text(title);
        $("#globalModalViewMedium").modal();

      }

      function PopupCenter(url, title, w, h) {
          // Fixes dual-screen position                         Most browsers      Firefox
          var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : window.screenX;
          var dualScreenTop = window.screenTop != undefined ? window.screenTop : window.screenY;

          var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
          var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

          var left = ((width / 2) - (w / 2)) + dualScreenLeft;
          var top = ((height / 2) - (h / 2)) + dualScreenTop;
          var newWindow = window.open(url, title, 'scrollbars=yes, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

          // Puts focus on the newWindow
          if (window.focus) {
              newWindow.focus();
          }

          /*custom hide after show popup*/
          $('#modalCetakTracer').modal('hide');
      }

      function copyToClipboard(text) {
        var selected = false;
        var el = document.createElement('textarea');
        el.value = text;
        el.setAttribute('readonly', '');
        el.style.position = 'absolute';
        el.style.left = '-9999px';
        document.body.appendChild(el);
        if (document.getSelection().rangeCount > 0) {
            selected = document.getSelection().getRangeAt(0)
        }
        el.select();
        document.execCommand('copy');
        document.body.removeChild(el);
        if (selected) {
            document.getSelection().removeAllRanges();
            document.getSelection().addRange(selected);
        }
      }

      function preventDefault(e) {
        e = e || window.event;
        if (e.preventDefault)
            e.preventDefault();
        e.returnValue = false;  
      }

      function hitung_usia(DOB){

        var today = new Date(); 
          var d = DOB;
          if (!/\d{4}\-\d{2}\-\d{2}/.test(d)) {   // check valid format
          return false;
          }
          d = d.split("-");

          var byr = parseInt(d[0]); 
          var nowyear = today.getFullYear();
          if (byr >= nowyear || byr < 1900) {  // check valid year
          return false;
          }

          var bmth = parseInt(d[1],10)-1;  
          if (bmth<0 || bmth>11) {  // check valid month 0-11
          return false;
          }

          var bdy = parseInt(d[2],10); 
          if (bdy<1 || bdy>31) {  // check valid date according to month
          return false;
          }

          var age = nowyear - byr;
          var nowmonth = today.getMonth();
          var nowday = today.getDate();
          if (bmth > nowmonth) {age = age - 1}  // next birthday not yet reached
          else if (bmth == nowmonth && nowday < bdy) {age = age - 1}

          return age;
          //alert('You are ' + age + ' years old'); 
      }

      function getAge(paramsDate, style) {

        var dateString = getFormattedDate(paramsDate);

        var now = new Date();
        var today = new Date(now.getYear(),now.getMonth(),now.getDate());

        var yearNow = now.getYear();
        var monthNow = now.getMonth();
        var dateNow = now.getDate();

        var dob = new Date(dateString.substring(6,10),
                           dateString.substring(0,2)-1,                   
                           dateString.substring(3,5)                  
                           );

        var yearDob = dob.getYear();
        var monthDob = dob.getMonth();
        var dateDob = dob.getDate();
        var age = {};
        var ageString = "";
        var yearString = "";
        var monthString = "";
        var dayString = "";


        yearAge = yearNow - yearDob;

        if (monthNow >= monthDob)
          var monthAge = monthNow - monthDob;
        else {
          yearAge--;
          var monthAge = 12 + monthNow -monthDob;
        }

        if (dateNow >= dateDob)
          var dateAge = dateNow - dateDob;
        else {
          monthAge--;
          var dateAge = 31 + dateNow - dateDob;

          if (monthAge < 0) {
            monthAge = 11;
            yearAge--;
          }
        }

        age = {
            years: yearAge,
            months: monthAge,
            days: dateAge
            };

        if ( age.years > 1 ) yearString = " thn";
        else yearString = " thn";
        if ( age.months> 1 ) monthString = " bln";
        else monthString = " bln";
        if ( age.days > 1 ) dayString = " hr";
        else dayString = " hr";


        if ( (age.years > 0) && (age.months > 0) && (age.days > 0) )
          ageString = age.years + yearString + ", " + age.months + monthString + ", " + age.days + dayString + " ";
        else if ( (age.years == 0) && (age.months == 0) && (age.days > 0) )
          ageString = "" + age.days + dayString + " ";
        else if ( (age.years > 0) && (age.months == 0) && (age.days == 0) )
          ageString = age.years + yearString + " Happy Birthday!!";
        else if ( (age.years > 0) && (age.months > 0) && (age.days == 0) )
          ageString = age.years + yearString + ",  " + age.months + monthString + " ";
        else if ( (age.years == 0) && (age.months > 0) && (age.days > 0) )
          ageString = age.months + monthString + ", " + age.days + dayString + " ";
        else if ( (age.years > 0) && (age.months == 0) && (age.days > 0) )
          ageString = age.years + yearString + ", " + age.days + dayString + " ";
        else if ( (age.years == 0) && (age.months > 0) && (age.days == 0) )
          ageString = age.months + monthString + " ";
        else ageString = "Oops! Could not calculate age!";

        if(style==1){
          return ageString;
        }else{
          return age.years;
        }

      }

      function getFormattedDate(paramsDate) {
          var date = new Date(paramsDate);
          let year = date.getFullYear();
          let month = (1 + date.getMonth()).toString().padStart(2, '0');
          let day = date.getDate().toString().padStart(2, '0');        
          return day + '/' + month + '/' + year;
      }

      function getDateToday(){
        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
        var yyyy = today.getFullYear();

        today = dd + '/' + mm + '/' + yyyy;
        return today;
      }

      function getFormatSqlDate(paramsDate) {
          var date = new Date(paramsDate);
          let year = date.getFullYear();
          let month = (1 + date.getMonth()).toString().padStart(2, '0');
          let day = date.getDate().toString().padStart(2, '0');        
          return year + '-' + month + '-' + date;
      }

      function changeDiscount(field, id){
        //alert(field); return false;
        /*harga * diskon*/
        var harga_awal = $('#'+field+'_'+id).val();
        var discount = $('#diskon_'+field+'_'+id).val();
        /*modulus*/
        
        if(discount > 100){

          var modulus = discount % 100;
          disc = harga_awal * modulus/100;
          console.log(disc);
          var last_price = parseInt(harga_awal) + parseInt(disc);

        }else{
          disc = harga_awal * discount/100;
          var last_price = harga_awal - disc;
        }

        $('#total_diskon_'+field+'_'+id).val( last_price );
        format = formatMoney(last_price);
        $('#text_total_diskon_'+field+'_'+id).text( format );
        /*sum class name*/
        sum = sumClass('total_diskon_'+id+'');
        sumFormat = formatMoney(sum);
        $('#total_biaya_'+id+'').text( sumFormat );
      }

      function formatMoney(number){
        money = new Intl.NumberFormat().format(number, 0);
        format = '' +money+ '';
        return format;
      }

      function sumClass(classname){

        var sum = 0;

        $("."+classname).each(function() {
            var val = $.trim( $(this).val() );
            
            if ( val ) {
                val = parseFloat( val.replace( /^\$/, "" ) );
            
                sum += !isNaN( val ) ? val : 0;
            }
        });


        return sum;
      }

      

      function checkAll(elm) {

        if($(elm).prop("checked") == true){
          $('table .ace').each(function(){
              $('table .ace').prop("checked", true);
          });
        }else{
          $('table .ace').prop("checked", false);
        }

      }

      function submitUpdateTransaksi(kode_trans_pelayanan){

        preventDefault();
        achtungShowLoader();
        $.ajax({
            url: "pelayanan/Pl_pelayanan/updateBilling?kode="+kode_trans_pelayanan+"",
            data: $('#form_update_billing_'+kode_trans_pelayanan+'').serialize(),            
            dataType: "json",
            type: "POST",
            complete: function (xhr) {
              var data=xhr.responseText;  
              var jsonResponse = JSON.parse(data);  
              if(jsonResponse.status === 200){  
                $.achtung({message: jsonResponse.message, timeout:5});
                reset_table();
              }else{          
                $.achtung({message: jsonResponse.message, timeout:5});  
              } 
              achtungHideLoader();
            }
        });

      }

      function format_currency(div_id){
        $('#'+div_id+'').number(true, 0);
      }

      function formatNumberFromCurrency(yourNumber) {
          //Seperates the components of the number
          var components = yourNumber.toString().split(/[ .:;?!~,`"&|()<>{}\[\]\r\n/\\]+/);
          //Comma-fies the first part
          components [0] = components [0].replace(/\B(?=(\d{3})+(?!\d))/g, "");
          //Combines the two sections
          return components.join("");
      }

      function getLiburNasional(year){

        if(year == 2023){
            var dataLiburNasional = ["1-1-2023", "22-1-2023", "18-2-2023", "22-3-2023", "7-4-2023", "22-4-2023", "23-4-2023", "1-5-2023", "18-5-2023", "1-6-2023", "4-6-2023", "29-6-2023","19-7-2023","17-8-2023","28-9-2023", "25-12-2023"];
        }

        if(year == 2024){
            var dataLiburNasional = ["1-1-2024", "8-2-2024", "10-2-2024", "14-2-2024" , "11-3-2024", "29-3-2024", "31-3-2024", "10-4-2024", "11-4-2024", "1-5-2024", "9-5-2024", "23-5-2024", "1-6-2024", "17-6-2024", "7-7-2024", "17-8-2024", "16-9-2024", "25-12-2024", "1-1-2025", "27-1-2025", "29-1-2025", "29-3-2025", "31-3-2025", "1-4-2025", "18-4-2025", "1-5-2025", "12-5-2025", "29-5-2025", "6-6-2025", "27-6-2025", "5-9-2025", "25-12-2025"];
        }

        if(year == 2025){
            var dataLiburNasional = ["1-1-2025", "27-1-2025", "29-1-2025", "29-3-2025", "31-3-2025", "1-4-2025", "18-4-2025", "1-5-2025", "12-5-2025", "29-5-2025", "6-6-2025", "27-6-2025", "5-9-2025", "25-12-2025"];
        }

        return dataLiburNasional;

      }

      function syntaxHighlight(json) {
        json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (match) {
            var cls = 'number';
            if (/^"/.test(match)) {
                if (/:$/.test(match)) {
                    cls = 'key';
                } else {
                    cls = 'string';
                }
            } else if (/true|false/.test(match)) {
                cls = 'boolean';
            } else if (/null/.test(match)) {
                cls = 'null';
            }
            return '<span class="' + cls + '">' + match + '</span>';
        });
      }

      function reprint(jd_id, id_tc_pesanan, printerName){
        preventDefault();
        $.ajax({
            url: "registration/Reg_pasien/print_booking/"+jd_id+"/"+id_tc_pesanan+"",
            dataType: "json",
            data: {printer : printerName},
            type: "POST",
            success: function (response) {
              // no action
              console.log(response);
            }
        });
      }

      function reprintSEP(no_sep, no_antrian, printerName){
        preventDefault();
        $.ajax({
            url: "ws_bpjs/Ws_index/print_sep/"+no_sep+"",
            dataType: "json",
            data: {printer : printerName, no_antrian : no_antrian},
            type: "POST",
            success: function (response) {
              // no action
              console.log(response);
            }
        });
      }

      function reprintBuktiPendaftaran(no_registrasi, no_antrian, printerName){
        preventDefault();
        $.ajax({
            url: "registration/Reg_klinik/print_bukti_pendaftaran/"+no_registrasi+"",
            dataType: "json",
            data: {printer : printerName, no_antrian : no_antrian},
            type: "POST",
            success: function (response) {
              // no action
              console.log(response);
            }
        });
      }
      
      function duplicateFieldValue(fieldId, valueId){
        $('#'+valueId+'').val($('#'+fieldId+'').val());
      }

      function startStopWatch(){
        // $('#startCount').attr({'disabled':'disbaled'});
        // $('#pauseCount').removeAttr("disabled");
        // $('#resetCount').removeAttr("disabled");
        
        // $.post('ws/AntrianOnline/updateTask', {kodebooking : $('#kode_perjanjian').val(), taskId : $('#taskId').val() },
        //   function(response){
        //     console.log(response);
        //   }
        // );

        // minutessetInterval = setInterval(function () {
        //     minutesCount += 1
        //     minutes.innerHTML = minutesCount
        // }, 60000)

        // secondsetInterval = setInterval(function () {
        //     secondCount += 1
        //     if(secondCount > 59){
        //         secondCount = 1
        //     }
        //     second.innerHTML = secondCount
        // }, 1000)

        // centiSecondsetInterval = setInterval(function () {
        //     centiSecondCount += 1
        //     if(centiSecondCount > 99){
        //         centiSecondCount = 1
        //     }
        //     centiSecond.innerHTML = centiSecondCount
        // }, 10)
    }

    function pauseStopWatch(){
        // $('#pauseCount').attr({'disabled':'disbaled'});
        // $('#startCount').removeAttr("disabled");
        clearInterval(minutessetInterval)
        clearInterval(secondsetInterval)
        clearInterval(centiSecondsetInterval)
    }

    function resetStopWatch(){
        // $('#pauseCount').attr({'disabled':'disbaled'});
        // $('#resetCount').attr({'disabled':'disbaled'});
        // $('#startCount').removeAttr("disabled");

        clearInterval(minutessetInterval)
        clearInterval(secondsetInterval)
        clearInterval(centiSecondsetInterval)

        minutesCount = 0; secondCount = 0; centiSecondCount = 0;
        minutes.innerHTML = minutesCount;
        second.innerHTML = secondCount;
        centiSecond .innerHTML = centiSecondCount;

    }

    function cetak_surat_kontrol_popup(ID, jd_id) {   
      url = 'registration/Reg_pasien/surat_kontrol_popup?id_tc_pesanan='+ID+'&jd_id='+jd_id+'';
      PopupCenter(url, 'CETAK SURAT KONTROL', 800, 650);
    }
    


    </script>


  </body>
</html>
