<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="utf-8" />
    <title>KIOSK</title>

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
    body{
      overflow-y: scroll;
    }
    
    .ui-keyboard{
      font-size: 20px !important;
    }

    button { margin-top: auto; }
    .alert-success {
        background-color: #dff0d8 !important;
        border-color: #d6e9c6 !important;
        color: #3c763d !important;
    }

    .alert-danger {
        background-color: #f2dede !important;
        border-color: #ebccd1 !important;
        color: #a94442 !important;
    }

    .footer .footer-inner .footer-content {
        background: linear-gradient(2deg, #8bc32ea3, transparent);
    }

    .smaller{
      font-weight: bold !important;
      color: #024a19 !important;
    }

    .btn-primary, .btn-primary:focus {
      background-color: #8bc22d !important;
      border-color: #7fb720;
    }

	</style>

  <body class="no-skin" style="background: url('../assets/images/bg2.jpg') !important;">
    <!-- #section:basics/navbar.layout -->
    <!-- <div id="navbar" class="navbar navbar-default navbar-collapse h-navbar" style="background: url('assets/images/KIOSK.png');"> -->
    

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
            $arr_color_breadcrumbs = array('#076960');
            shuffle($arr_color_breadcrumbs);
          ?>
          <div class="breadcrumbs" id="breadcrumbs" style="background-color:<?php echo array_shift($arr_color_breadcrumbs)?>">
            <script type="text/javascript">
              try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
            </script>
          </div>
          <div class="page-content-main" style="background: white !important">
          <br>
          <div class="center">
            <img src="<?php echo base_url().'assets/images/logo.png'?>" style="width: 100px">
            <h2 style="font-family: sans-serif; font-weight: bold; font-size:25px; color: #024a19; ">KIOSK LAYANAN MANDIRI PASIEN<br>RS SETIA MITRA</h2><br>
          </div>
            

            <div class="center">
              <a style="height: 40px; font-size: 18px" class="btn btn-sm btn-success" type="button" href="<?php echo base_url().'registration/Self_service'?>">
                  <i class="ace-icon fa fa-home bigger-110"></i>
                  Kembali ke Halaman Utama
              </a>
            </div>
            <br>
            <div class="row centered" style="width: 100% !important">
              <div class="col-xs-12">
                <!-- PAGE CONTENT BEGINS -->
                <!-- MODULE MENU -->
                
                <div id="load-content-page">
                  <div class="row ">

                    <div class="col-sm-3" style="text-align: center">
                      <div class="widget-box">
                        <div class="widget-header">
                          <h4 class="smaller">
                          BPJS KESEHATAN
                          </h4>
                        </div>

                        <div class="widget-body">
                          <div class="widget-main">
                            <p class="muted">
                              <img src="<?php echo base_url()?>assets/kiosk/bpjs.jpeg" height="100" alt=""><br>
                              Layanan Pendaftaran Mandiri <br>Pasien BPJS Kesehatan
                            </p>

                            <hr>

                            <p>
                              <span class="btn btn-block btn-primary" data-rel="tooltip" title="" data-original-title="Default" onclick="scrollSmooth('Self_service/mandiri_bpjs')">Selengkapnya</span>
                            </p>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="col-sm-3" style="text-align: center">
                      <div class="widget-box">
                        <div class="widget-header">
                          <h4 class="smaller">
                          UMUM & ASURANSI
                          </h4>
                        </div>

                        <div class="widget-body">
                          <div class="widget-main">
                            <p class="muted">
                              <img src="<?php echo base_url()?>assets/kiosk/insurance.png" height="100" alt=""><br>
                              Layanan Pendaftaran Mandiri <br>Pasien Umum dan Asuransi Lainnya
                            </p>

                            <hr>

                            <p>
                              <span class="btn btn-block btn-primary" data-rel="tooltip" title="" data-original-title="Default" onclick="scrollSmooth('Self_service/mandiri_umum')">Selengkapnya</span>
                            </p>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="col-sm-3" style="text-align: center">
                      <div class="widget-box">
                        <div class="widget-header">
                          <h4 class="smaller">
                          ANTRIAN PENDAFTARAN
                          </h4>
                        </div>

                        <div class="widget-body">
                          <div class="widget-main">
                            <p class="muted">
                            <img src="<?php echo base_url()?>assets/kiosk/antrian.png" height="100" alt=""><br>
                              Pengambilan Nomor Antrian <br>Pendaftaran Pasien Poli/Klinik
                            </p>

                            <hr>

                            <p>
                              <span class="btn btn-block btn-primary" data-rel="tooltip" title="" data-original-title="Default" onclick="scrollSmooth('Self_service/antrian_poli')">Selengkapnya</span>
                            </p>
                          </div>
                        </div>
                      </div>
                    </div>

                    <div class="col-sm-3" style="text-align: center">
                      <div class="widget-box">
                        <div class="widget-header">
                          <h4 class="smaller">
                            INFORMASI & PERJANJIAN
                          </h4>
                        </div>

                        <div class="widget-body">
                          <div class="widget-main">
                            <p class="muted">
                            <img src="<?php echo base_url()?>assets/kiosk/appointment.png" height="100" alt=""><br>
                              Informasi Jadwal Praktek Dokter <br>dan Perjanjian Pasien
                            </p>

                            <hr>

                            <p>
                              <span class="btn btn-block btn-primary" data-rel="tooltip" title="" data-original-title="Default" onclick="scrollSmooth('Self_service/jadwal_dokter')">Selengkapnya</span>
                            </p>
                          </div>
                        </div>
                      </div>
                    </div>
                    
                    <!-- <div class="wrapper">
                      <div class="box">
                        <img src="http://i.imgur.com/60PVLis.png" width="50" height="50" alt="">
                        <h2>PENDAFTARAN PASIEN BPJS</h2>
                        <p>Layanan Pendaftaran Mandiri <br>Pasien BPJS Kesehatan</p>
                        <button onclick="scrollSmooth('Self_service/mandiri_bpjs')" class="btn btn-danger btn-block" style="height: 50px !important;">Selengkapnya</button>
                      </div>

                      <div class="box">
                        <img src="http://i.imgur.com/60PVLis.png" width="50" height="50" alt="">
                        <h2>PENDAFTARAN <br> UMUM & ASURANSI</h2>
                        <p>Layanan Pendaftaran Mandiri Pasien Umum dan Asuransi Lainnya</p>
                        <button onclick="scrollSmooth('Self_service/mandiri_umum')" class="btn btn-danger btn-block" style="height: 50px !important;">Selengkapnya</button>
                      </div>

                      <div class="box">
                        <img src="http://i.imgur.com/60PVLis.png" width="50" height="50" alt="">
                        <h2>ANTRIAN PENDAFTARAN</h2>
                        <p>Pengambilan Nomor Antrian Pendaftaran Pasien Poli/Klinik</p>
                        <button onclick="scrollSmooth('Self_service/antrian_poli')" class="btn btn-danger btn-block" style="height: 50px !important;">Selengkapnya</button>
                      </div>
                      <div class="box">
                        <img src="http://i.imgur.com/60PVLis.png" width="50" height="50" alt="">
                        <h2>INFORMASI & PERJANJIAN</h2>
                        <p>Informasi Jadwal Praktek Dokter dan Perjanjian Pasien</p>
                        <button onclick="scrollSmooth('Self_service/')" class="btn btn-danger btn-block" style="height: 50px !important;">Selengkapnya</button>
                      </div>
                    </div> -->

                  </div>
                </div>
                <!-- END MODULE MENU -->

                <!-- PAGE CONTENT ENDS -->
              </div><!-- /.col -->
            </div><!-- /.row -->
          </div><!-- /.page-content -->
        </div>
      </div><!-- /.main-content -->

      <div class="footer">
				<div class="footer-inner">
					<div class="footer-content">
						<span class="bigger-120">
							<span class="blue bolder">KIOSK - </span>
							RS. Setia Mitra <?php echo date('Y')?>
						</span>
					</div>
				</div>
			</div>

      <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
        <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
      </a>
    </div><!-- /.main-container -->



     <div id="globalModalView" class="modal fade" tabindex="-1">

      <div class="modal-dialog" style="overflow-y: scroll; max-height:100%;  margin-top: 50px; margin-bottom:50px;width:40%">

        <div class="modal-content">

          <div class="modal-header">

            <!-- <div class="table-header"> -->

              <button type="button" class="close" data-dismiss="modal" aria-hidden="true">

                <span class="white">&times;</span>

              </button>

              <span id="text_title" style="font-size: 14px; color: white">JADWAL DOKTER DAN PERJANJIAN PASIEN</span>

            <!-- </div> -->

          </div>

          <div class="modal-body" style="min-height: 400px !important">

            <div id="global_modal_content_detail"></div>

          </div>

        </div><!-- /.modal-content -->

      </div><!-- /.modal-dialog -->

    </div>

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

    <script src="<?php echo base_url()?>/assets/js/dataTables/jquery.dataTables.js"></script>
    <script src="<?php echo base_url()?>/assets/js/dataTables/jquery.dataTables.bootstrap.js"></script>
    <script src="<?php echo base_url()?>/assets/js/dataTables/extensions/TableTools/js/dataTables.tableTools.js"></script>
    <script src="<?php echo base_url()?>/assets/js/dataTables/extensions/ColVis/js/dataTables.colVis.js"></script>

    <!-- the following scripts are used in demo only for onpage help and you don't need them -->
    <!-- <link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace.onpage-help.css" />

    <script type="text/javascript"> ace.vars['base'] = '..'; </script>
    <script src="<?php echo base_url()?>assets/js/ace/elements.onpage-help.js"></script>
    <script src="<?php echo base_url()?>assets/js/ace/ace.onpage-help.js"></script> -->
    <script src="<?php echo base_url()?>assets/js/custom/menu_load_page.js"></script>
    <script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />

    <!-- keyboar on screen -->
    <!-- keyboard widget css & script -->
    <link href="<?php echo base_url()?>assets/Keyboard-master/css/keyboard-dark.css" rel="stylesheet">
    <script src="<?php echo base_url()?>assets/Keyboard-master/js/jquery.keyboard.js"></script>

    <!-- css for the preview keyset extension -->
    <link href="<?php echo base_url()?>assets/Keyboard-master/css/keyboard-previewkeyset.css" rel="stylesheet">

    <!-- keyboard optional extensions - include ALL (includes mousewheel) -->
    <script src="<?php echo base_url()?>assets/Keyboard-master/js/jquery.keyboard.extension-all.js"></script>
    <style>
      .form-control .ui-widget-content .ui-corner-all .ui-keyboard-preview{
        text-align: center !important;
      }
    </style>

    <script>
		function scrollSmooth(link){
			$('#banner-home').hide('fast');
			$('#load-content-page').load(link);
			// $('html,body').animate({
			// 		scrollTop: $("#load-content-page").offset().top},
			// 		'slow');
		}

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
	    })

    function getDateToday(){
      var today = new Date();
      var dd = String(today.getDate()).padStart(2, '0');
      var mm = String(today.getMonth() + 1); //January is 0!
      var yyyy = today.getFullYear();

      current_date = dd + '/' + mm + '/' + yyyy;
      return current_date;
    }

    function show_modal(url, title){  

          preventDefault();
          
          $('#text_title').text(title);

          $('#global_modal_content_detail').load(url); 

          $("#globalModalView").modal();
          
    }

    function preventDefault(e) {
        e = e || window.event;
        if (e.preventDefault)
            e.preventDefault();
        e.returnValue = false;  
    }


      
    </script>

    
  </body>
</html>
