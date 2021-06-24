<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="utf-8" />
    <title>KIOSK - Pelayanan Mandiri Pasien</title>

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
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/jquery-ui.custom.css" />
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/jquery.gritter.css" />
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/select2.css" />
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap-editable.css" />

    <link rel="shortcut icon" href="<?php echo base_url().COMP_ICON; ?>">
  </head>
  <style>
    .page-content-main{
      min-height: 950px !important;
    }

    #footer {
      position:absolute;
      bottom:0;
      width:100%;
      height:60px;   /* Height of the footer */
      background:#6cf;
    }
  </style>
  <body class="no-skin">
    <!-- #section:basics/navbar.layout -->
    <div id="navbar" class="navbar navbar-default navbar-collapse h-navbar" style="background: white;">
      <script type="text/javascript">
        try{ace.settings.check('navbar' , 'fixed')}catch(e){}
      </script>

      <div class="navbar-container" id="navbar-container">
        <div class="pull-right">
          <a href="<?php echo base_url().'registration/Self_service'?>" class="btn btn-lg btn-primary">
            MENU UTAMA</a>
        </div>
        <div class="navbar-header pull-left">
          
          <!-- #section:basics/navbar.layout.brand -->
          <a href="#" class="navbar-brand">
            <small style="color: black; font-weight: bold; font-size: 18px">
              KIOSK PELAYANAN MANDIRI PASIEN - Smart Hospital System 4.0 
            </small>
          </a>

          <!-- /section:basics/navbar.layout.brand -->

          <!-- #section:basics/navbar.toggle -->
          <button class="pull-right navbar-toggle navbar-toggle-img collapsed" type="button" data-toggle="collapse" data-target=".navbar-buttons,.navbar-menu">
            <span class="sr-only">Toggle user menu</span>
          </button>

          <!-- <button class="pull-right navbar-toggle collapsed" type="button" data-toggle="collapse" data-target="#sidebar">
            <span class="sr-only">Toggle sidebar</span>

            <span class="icon-bar"></span>

            <span class="icon-bar"></span>

            <span class="icon-bar"></span>
          </button> -->

          <!-- /section:basics/navbar.toggle -->
        </div>

        <!-- #section:basics/navbar.dropdown -->
        
      </div><!-- /.navbar-container -->
    </div>

    <!-- /section:basics/navbar.layout -->
    <div class="main-container" id="main-container">
      <script type="text/javascript">
        try{ace.settings.check('main-container' , 'fixed')}catch(e){}
      </script>

      <!-- /section:basics/sidebar.horizontal -->
      <div class="main-content">
          <div class="page-content-main">
            
            <div class="row" id="page-area-content">
              <div class="col-xs-12">
                <!-- PAGE CONTENT BEGINS -->
                
                <div class="main-nav-modules" style="margin-top:20px">

                  <div class="col-lg-3 col-xs-6" style="margin-top:-10px">
                    <div class="small-box bg-red" style="min-height: 133px; cursor: pointer !important" onclick="getMenu('Self_service/pmp_bpjs')">
                      <div class="inner">
                        <h3 style="font-size:16px">PENDAFTARAN MANDIRI <br>PASIEN BPJS</h3>
                        <p style="font-size:12px; line-height: 15px; color: black; font-weight: bold">Wajib membawa Surat Rujukan dari Puskesmas atau Nomor Kartu BPJS anda.</p>
                      </div>
                      <div class="icon" style="margin-top:-10px">
                        <i class="fa fa-globe"></i>
                      </div>
                    </div>
                  </div>

                  <div class="col-lg-3 col-xs-6" style="margin-top:-10px">
                    <div class="small-box bg-blue" style="min-height: 133px; cursor: pointer !important" onclick="return confirm('OK')">
                      <div class="inner">
                        <h3 style="font-size:16px">PENDAFTARAN MANDIRI <br>PASIEN UMUM & ASURANSI</h3>
                        <p style="font-size:12px; line-height: 15px; color: black; font-weight: bold">Mohon siapkan KTP jika anda Pasien Baru atau Nomor Rekam Medis jika anda sudah pernah berobat sebelumnya.</p>
                      </div>
                      <div class="icon" style="margin-top:-10px">
                        <i class="fa fa-globe"></i>
                      </div>
                    </div>
                  </div>

                  <div class="col-lg-3 col-xs-6" style="margin-top:-10px">
                    <div class="small-box bg-green" style="min-height: 133px; cursor: pointer !important" onclick="return confirm('OK')">
                      <div class="inner">
                        <h3 style="font-size:16px">ANTRIAN PENDAFTARAN <br> POLI/KLINIK SPESIALIS</h3>
                        <p style="font-size:12px; line-height: 15px; color: black; font-weight: bold">Pengambilan Nomor untuk Antrian Pendaftaran ke Poli/Klinik Spesialis</p>
                      </div>
                      <div class="icon" style="margin-top:-10px">
                        <i class="fa fa-globe"></i>
                      </div>
                    </div>
                  </div>

                  <div class="col-lg-3 col-xs-6" style="margin-top:-10px">
                    <div class="small-box bg-yellow" style="min-height: 133px; cursor: pointer !important" onclick="return confirm('OK')">
                      <div class="inner">
                        <h3 style="font-size:16px">ANTRIAN PENDAFTARAN <br> IGD, PENUNJANG MEDIS & LAINNYA</h3>
                        <p style="font-size:12px; line-height: 15px; color: black; font-weight: bold">Pengambilan Nomor Antrian Pendaftaran untuk IGD, Penunjang Medis, Pasien Baru, dsb.</p>
                      </div>
                      <div class="icon" style="margin-top:-10px">
                        <i class="fa fa-globe"></i>
                      </div>
                    </div>
                  </div>

                </div>

                <!-- PAGE CONTENT ENDS -->
              </div><!-- /.col -->
            </div><!-- /.row -->
          </div><!-- /.page-content -->
        </div>
      </div><!-- /.main-content -->

      <div id="footer">
        <span>RS SETIA MITRA - JAKARTA</span>
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
