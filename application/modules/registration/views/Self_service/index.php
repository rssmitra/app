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
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/css_custom.css" />
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
    
    .main-container:before {
        background-color: #024a15 !important;
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

    .widget-header{
      padding-left: 0px !important;
      padding: 0px 5px 0px 5px;
    }
    .widget-body{
      background: white !important;
    }

    .row {
        margin-left: 0px !important;
        margin-right: 0px !important;
    }
    .muted {
      min-height: 154px !important;
    }

    .tab-content{
      background: white !important;
    }

	</style>

  <body class="no-skin" style="background: url('../assets/images/bg2.jpg') !important; background: #024a15 !important">
    <!-- #section:basics/navbar.layout -->
    <div id="navbar" class="navbar navbar-default navbar-collapse h-navbar navbar-fixed-top" style="background: linear-gradient(271deg, #72ad40, #f2f5ee);">
      <script type="text/javascript">
        try{ace.settings.check('navbar' , 'fixed')}catch(e){}
      </script>

      <div class="navbar-container" id="navbar-container">
        <div class="navbar-header pull-left">
          <!-- #section:basics/navbar.layout.brand -->
          <a href="#" class="navbar-brand">
            <small style="color: black; font-weight: bold; font-size: 18px">
            <img src="<?php echo base_url().'assets/images/logo.png'?>" width="80px">
            </small>
          </a>
          <!-- /section:basics/navbar.toggle -->
        </div>
        <div class="pull-left">
        <h2 style="font-family: sans-serif; font-weight: bold; font-size:25px; color: #024a19; color: #024a15;
    text-shadow: 2px 2px #1674264d;">KIOSK LAYANAN MANDIRI PASIEN <br> RS SETIA MITRA</h2>
        </div>
        <div class="pull-right">
            <a href="<?php echo base_url().'registration/Self_service'?>" class="btn btn-success" style="margin-top: 10px;background: linear-gradient(45deg, #0a4b19, #ea4b94) !important;border-color: #9fca6b;">
                <i class="ace-icon fa fa-home bigger-300"></i>
            </a>
        </div>

      </div><!-- /.navbar-container -->
    </div>
    
    <!-- /section:basics/navbar.layout -->
    <div class="main-container" id="main-container" style="background: #024a15;">
      <script type="text/javascript">
        try{ace.settings.check('main-container' , 'fixed')}catch(e){}
      </script>

      <!-- /section:basics/sidebar.horizontal -->
      <div class="main-content" style="background: #024a15;">
        <div class="main-content-inner" style="background: #024a15;">
          <!-- #section:basics/content.breadcrumbs -->
          <?php
            $arr_color_breadcrumbs = array('#013518');
            shuffle($arr_color_breadcrumbs);
          ?>
          <div class="breadcrumbs" id="breadcrumbs" style="background-color:<?php echo array_shift($arr_color_breadcrumbs)?>; line-height: 25px !important; position: fixed !important; width: 100%">
            <script type="text/javascript">
              try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
            </script>
            <div style="background: linear-gradient(45deg, #8cc22f, transparent);margin-top: 3%; width: 100%">
              <div style="float: right; width: 20%; padding: 5px; padding-top: 8px; border-left: 1px solid white; text-align: center; font-size: 14px; color: white">
                <?php echo date('d/M/Y') ?> <span id="clock"><?php echo date('H:i:s')?></span>
              </div>
              <div style="float: left; width: 80%">
                  <marquee style="font-size: 18px;color: white;margin-top: 8px;">Hanya untuk pasien yang sudah pernah terdaftar atau yang pernah berobat ke RS Setia Mitra. Untuk pasien baru silahkan mengambil Nomor Antrian Pendaftaran</marquee>
              </div>
            </div>

          </div>
          <div class="page-content-main" style="background: #024a15 !important; margin-top:8%; min-height: 0px !important">
          <!-- <br>
          <div class="center">
            <img src="<?php echo base_url().'assets/images/logo.png'?>" style="width: 100px">
            <h2 style="font-family: sans-serif; font-weight: bold; font-size:25px; color: #024a19; ">KIOSK LAYANAN MANDIRI PASIEN<br>RS SETIA MITRA</h2><br>
          </div> -->
            

            <!-- <div class="pull-left">
              <a style="height: 40px; font-size: 18px" class="btn btn-sm btn-success" type="button" href="<?php echo base_url().'registration/Self_service'?>">
                  <i class="ace-icon fa fa-home bigger-110"></i>
                  Kembali ke Halaman Utama
              </a>
            </div> -->
            <!-- PAGE CONTENT BEGINS -->
            <div class="center" id="load-content-page" >
              <div class="row" style="padding-top: 50px">
              <span style="color: white"><h2>MODUL KIOSK LAYANAN MANDIRI PASIEN</h2></span>

                <div class="col-sm-3" style="text-align: center">
                  <div class="widget-box">
                    <div class="widget-header">
                      <h4 class="smaller">
                      PENDAFTARAN PASIEN BPJS KESEHATAN
                      </h4>
                    </div>

                    <div class="widget-body">
                      <div class="widget-main" onclick="scrollSmooth('Self_service/mandiri_bpjs')">
                        <!-- <span style="margin-left:-40%;position:absolute;transform: rotate(-25deg) !important; margin-top: 16%" class="stamp is-nope-2">comming soon</span> -->
                        <p class="muted">
                          <img src="<?php echo base_url()?>assets/kiosk/bpjs.jpeg" height="100" alt=""><br>
                          Persiapkan <b>Nomor Rujukan</b> Puskesmas anda untuk melakukan Pendaftaran ke Poli/Klinik
                        </p>

                        <hr>

                        <p>
                          <span class="btn btn-block btn-primary" disable data-rel="tooltip" title="" data-original-title="Default">Selengkapnya</span>
                        </p>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="col-sm-3" style="text-align: center">
                  <div class="widget-box">
                    <div class="widget-header">
                      <h4 class="smaller">
                      PENDAFTARAN PASIEN UMUM & ASURANSI
                      </h4>
                    </div>

                    <div class="widget-body">
                      <div class="widget-main" onclick="scrollSmooth('Self_service/mandiri_umum')">
                        <p class="muted">
                          <img src="<?php echo base_url()?>assets/kiosk/insurance.png" height="100" alt=""><br>
                          Persiapkan <b>Nomor Rekam Medis</b> anda untuk melakukan pendaftaran ke poli/klinik.
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
                      ANTRIAN <br> PENDAFTARAN PASIEN
                      </h4>
                    </div>

                    <div class="widget-body">
                      <div class="widget-main" onclick="scrollSmooth('Self_service/antrian_poli')">
                        <p class="muted">
                        <img src="<?php echo base_url()?>assets/kiosk/antrian.png" height="100" alt=""><br>
                          Pengambilan <b>Nomor Antrian</b> <br>Pendaftaran Poli/Klinik, Pasien Baru, IGD dan Penunjang Medis
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
                        INFORMASI JADWAL & <br>PERJANJIAN PASIEN
                      </h4>
                    </div>

                    <div class="widget-body">
                      <div class="widget-main" onclick="scrollSmooth('Self_service/jadwal_dokter')">
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
            <!-- PAGE CONTENT ENDS -->
          </div><!-- /.page-content -->
        </div>
      </div><!-- /.main-content -->

      <!-- <div class="footer">
				<div class="footer-inner">
					<div class="footer-content">
						<span class="bigger-120">
							<span class="blue bolder">KIOSK - </span>
							RS. Setia Mitra <?php echo date('Y')?>
						</span>
					</div>
				</div>
			</div> -->

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

      <div id="proses-loading">
        <div class="loading-content">
            <img width="125px" src="<?php echo base_url('assets/images/logo.png') ?>" alt="Logo <?php echo COMP_SORT; ?>">
            <br>
            <i class="fa fa-cog fa-spin fa-3x fa-fw"></i>
            <br>
            <span class="">Transaksi sedang di proses harap menunggu</span>
        </div>
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

    setInterval(showTime, 1000);
    function showTime() {
        let time = new Date();
        let hour = time.getHours();
        let min = time.getMinutes();
        let sec = time.getSeconds();
        am_pm = "AM";

        if (hour > 12) {
            hour -= 12;
            am_pm = " PM";
        }
        if (hour == 0) {
            hr = 12;
            am_pm = " AM";
        }

        hour = hour < 10 ? "0" + hour : hour;
        min = min < 10 ? "0" + min : min;
        sec = sec < 10 ? "0" + sec : sec;

        let currentTime = hour + ":" 
            + min + ":" + sec + am_pm;

        document.getElementById("clock")
            .innerHTML = currentTime;
    }

    showTime();

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

    function getFormattedDate(paramsDate) {
          var date = new Date(paramsDate);
          let year = date.getFullYear();
          let month = (1 + date.getMonth()).toString().padStart(2, '0');
          let day = date.getDate().toString().padStart(2, '0');        
          return day + '/' + month + '/' + year;
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



      
    </script>

    
  </body>
</html>
