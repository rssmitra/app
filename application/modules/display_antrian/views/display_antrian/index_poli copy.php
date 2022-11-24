<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="utf-8" />
    <title>Display Antrian Poli/Klinik</title>

    <meta name="description" content="top menu &amp; navigation" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

    <!-- css default for blank page -->
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.css" />
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/font-awesome.css" />
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace-fonts.css" />
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/AdminLTE.css" class="ace-main-stylesheet" id="main-ace-style" />
    <!-- js -->
    <script src='<?php echo base_url()?>/assets/js/jquery.js'></script>
    <script src="<?php echo base_url()?>assets/js/bootstrap.js"></script>
    <script src="<?php echo base_url()?>assets/js/ace-extra.js"></script>
    <script src="<?php echo base_url('assets/jquery/jquery-2.2.3.min.js')?>"></script>

    <script>
      $(document).ready( function(){

        setInterval( function () {
        
          $.getJSON("<?php echo site_url('display_antrian/reload_antrian_farmasi') ?>", '', function (data) {              
            console.log(data)
            $('.nama-pasien-antrian span').remove();
            $('.nama-pasien-antrian-small span').remove();

            $.each(data, function (i, o) {    
               console.log(data);
               if (i < 6) {
                $('<span>'+o.nama_pasien.substr(0,20)+'</span>').appendTo($('#antrian-ke-'+i+''));
               }

               if (i > 5) {
                $('<span>'+o.nama_pasien.substr(0,25)+'</span>').appendTo($('#antrian-ke-'+i+''));
               }

            });

          });

        }, 2000 );
      
      });
      
      
      setInterval("my_function();",3000); 

      function my_function(){
        $('#refresh').load(location.href + ' #time');
      }
    </script>
    <style type="text/css">
      
      @font-face { font-family: MyriadPro; src: url('assets/fonts/MyriadPro-Bold.otf'); } 

      .custom-box-utama{
        height:auto;margin:10px;background-image: linear-gradient(#00cc00, #004d00);color:white;border-radius:5px;
      }

      .nama-pasien-antrian-small{ font-size: 2em !important}

     /* @media screen and (min-width: 320px) {
      .nama-pasien-antrian, .text-no{ font-size: 14px !important }
      }*/

      @media screen and (min-width: 220px) {
      .nama-pasien-antrian, .text-no{ font-size: 4em !important; font-weight: bold }
      }

      .footer {
        width: 100%;
        height:55px;
        background-color: #ea0505;
        color: white;
        text-align: center;
      }
    </style>
  </head>

  <body class="no-skin" >
    <!-- #section:basics/navbar.layout -->
    <div id="navbar" class="navbar navbar-default navbar-collapse h-navbar" style="background-image: linear-gradient(to left, #005a00, #f9f9f9);">
      <script type="text/javascript">
        try{ace.settings.check('navbar' , 'fixed')}catch(e){}
      </script>

      <div class="navbar-container" id="navbar-container" style="padding-top: 20px">
        <div class="navbar-header pull-left">
          <!-- #section:basics/navbar.layout.brand -->
          <a href="#" class="navbar-brand" style="">
            <small style="font-size: 1.5em; text-shadow: 1px 2px #a5af98bf; color:#195005; font-weight: bold">
              <i class="fa fa-leaf"></i>
              ANTRIAN PELAYANAN POLI/KLINIK 
            </small>
          </a>
          <!-- <div style="margin-left: -20px; width: auto">
            <span style="font-size: 3em; text-shadow: 1px 2px #a5af98bf; color:#195005">Antrian Instalasi Farmasi </span>
            <span style="font-size: 2em"> <?php echo COMP_LONG?> </span><br> 
            <span style="font-size: 1em"> <?php echo COMP_ADDRESS?> </span>
          </div> -->
          <!-- /section:basics/navbar.toggle -->
        </div>


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
          <div class="breadcrumbs" id="breadcrumbs" style="">
            <script type="text/javascript">
              try{ace.settings.check('breadcrumbs' , 'fixed')}catch(e){}
            </script>
          </div>
          <div class="page-content-main" style="background-color: black !important">
            <div class="no-padding" style="width:90%;float:left;">
              <marquee behavior="scroll" direction="left" style="color: white;font-size:28px;margin-top:3px;"> Bagi pasien yang belum terdaftar pada Display Antrian Poli/Klinik diharapkan untuk menunggu antrian diluar agar tidak terjadi kerumunuan di ruang tunggu poli/klinik. | <?php echo COMP_MOTTO?> </marquee>
            </div>
            <div style="width:10%;float:left;margin-top: 5px;color: white; text-align: center;">
              <div id="refresh"><h3 style="margin:0;font-size:22px;" id="time"><?php date_default_timezone_set("Asia/Jakarta"); echo date('H:i') ?></h3></div>
              <p style="margin:0;font-size:16px;"><?php date_default_timezone_set("Asia/Jakarta"); echo date('d/m/Y') ?></p>
            </div>
            <div class="row no-padding">
              <div class="col-md-12 no-padding" style="padding-right: 5px !important">
                  <?php for($box=1;$box<6;$box++) :?>
                    <div class="col-sm-6 no-padding" style="color: white; padding: 5px !important">
                        <div class="widget-box">
                          <div class="widget-header widget-header-flat widget-header-large">
                            <h3 class="widget-title" style="font-size: 2em; font-weight: bold; padding: 5px !important">
                              KLINIK SPESIALIS JANTUNG DAN PEMBULUH DARAH
                            </h3>

                          </div>

                          <div class="widget-body" style=" background: linear-gradient(45deg, #128812, transparent); font-weight: bold">
                            <div class="widget-main">
                              <div class="center">
                                <span style="text-align: left !important">Sedang berlangsung..</span><br>
                                <span style="text-align: center; font-size: 3em">MUHAMMAD AMIN LUBIS</span>
                              </div>
                              <div class="hr hr8 hr-double"></div>

                              <div class="clearfix">
                                <div class="grid2">
                                  <span class="grey">
                                    <i class="ace-icon fa fa-angle-double-left fa-2x blue"></i>
                                    <span style="font-size: 1em">&nbsp; Selanjutnya..</span>
                                  </span>
                                  <h4 class="bigger center">Muhammad Zaid Hawari Lubis</h4>
                                </div>

                                <div class="grid2">
                                  <span class="grey">
                                    <i class="ace-icon fa fa-angle-double-left fa-2x blue"></i>
                                    <span style="font-size: 1em">&nbsp; Bersiap..</span>
                                  </span>
                                  <h4 class="bigger center">Hengky Zulkarnaen</h4>
                                </div>
                              </div>
                            </div><!-- /.widget-main -->
                          </div><!-- /.widget-body -->
                        </div>
                    </div>
                    

                    <!-- <div class="col-xs-4 widget-container-col ui-sortable no-padding" style="padding-right: 20px" id="widget-container-col-1">
                        <div class="alert alert-success" style="background-image: linear-gradient(#00cc00, #004d00);color:white;border-radius:5px;">
                          <div class="text-no" style="width:20%;float:left;border-right:2px solid white; margin-right: 20px; text-align: center">
                            <span><?php echo $box?></span>
                          </div>
                          <div class="nama-pasien-antrian" id="antrian-ke-<?php echo $box;?>"></div> 
                        </div>
                    </div> -->
                  <?php endfor; ?>
                
              </div>
            </div><!-- /.row -->
          </div><!-- /.page-content -->
        </div>
      </div><!-- /.main-content -->

      <div class="footerx">
        <div class="footer-inner">
          <!-- #section:basics/footer -->
          <!-- <div class="footer-content">
            <div style="width:90%;float:left;">
              <marquee behavior="scroll" direction="left" style="color: white;font-size:28px;margin-top:3px;"> Bagi pasien yang belum terdaftar pada Display Antrian Instalasi Farmasi diharapkan untuk menunggu antrian diluar agar tidak terjadi kerumunuan di ruang tunggu apotik. | <?php echo COMP_MOTTO?> </marquee>
            </div>
            <div style="width:10%;float:left;margin-top: 5px">
              <div id="refresh"><h3 style="margin:0;font-size:22px;" id="time"><?php date_default_timezone_set("Asia/Jakarta"); echo date('H:i') ?></h3></div>
              <p style="margin:0;font-size:16px;"><?php date_default_timezone_set("Asia/Jakarta"); echo date('d/m/Y') ?></p>
            </div>
          </div> -->

          <!-- /section:basics/footer -->
        </div>
      </div>

      <a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
        <i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
      </a>
    </div><!-- /.main-container -->

 
  </body>
</html>
