<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <meta charset="utf-8" />
    <title>Display Antrian Instalasi Farmasi</title>

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
            console.log(data.result)
            $('.nama-pasien-antrian span').remove();
            $('.nama-pasien-antrian-small span').remove();

            $.each(data.result, function (i, o) {    
               console.log(o);
               if (i < 6) {
                var blink_me = (i == 1) ? 'class="blink_me"' : '';
                $('<span '+blink_me+'>'+o.nama_pasien.substr(0,15)+'</span>').appendTo($('#antrian-ke-'+i+''));
               }

               if (i > 5) {
                $('<span>'+o.nama_pasien.substr(0,25)+'</span>').appendTo($('#antrian-ke-'+i+''));
               }

            });

            $('#total-antrian').text(data.total);

            console.log(data.total);
          });

        }, 2000 );
      
      });
      
      
      setInterval("my_function();",3000); 

      function my_function(){
        $('#refresh').load(location.href + ' #time');
      }
    </script>
    <style type="text/css">
      
      .blink_me {
        animation: blinker 3s linear infinite;
      }

      @keyframes blinker {
        50% {
          opacity: 0;
        }
      }

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

      .stamp {
        position:absolute;
        top:40%;
        left: 24%;
        transform: rotate(12deg);
        color: red;
        font-size: 7rem;
        font-weight: 900;
        border: 1rem solid red;
        display: inline-block;
        padding: 0.25rem 1rem;
        text-transform: uppercase;
        border-radius: 1rem;
        /*font-family: 'Courier';*/
        -webkit-mask-image: url('https://s3-us-west-2.amazonaws.com/s.cdpn.io/8399/grunge.png');
        -webkit-mask-size: 944px 604px;
        mix-blend-mode: hard-light;
        vertical-align: center;
      }
    </style>
  </head>

  <body class="no-skin" >
    <!-- #section:basics/navbar.layout -->
    <div id="navbar" class="navbar navbar-default navbar-collapse h-navbar" style="background-image: linear-gradient(to left, #005a00, #f9f9f9);">
      <script type="text/javascript">
        try{ace.settings.check('navbar' , 'fixed')}catch(e){}
      </script>

      <div class="navbar-container" id="navbar-container">
        <div class="navbar-header pull-left">
          <!-- #section:basics/navbar.layout.brand -->
          <a href="#" class="navbar-brand" style="">
            <small style="font-size: 1.5em; text-shadow: 1px 2px #a5af98bf; color:#195005; font-weight: bold">
              <i class="fa fa-leaf"></i>
              ANTRIAN INSTALASI FARMASI
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
              <marquee behavior="scroll" direction="left" style="color: white;font-size:28px;margin-top:3px;"> Bagi pasien yang belum terdaftar pada Display Antrian Instalasi Farmasi diharapkan untuk menunggu antrian diluar agar tidak terjadi kerumunuan di ruang tunggu apotik. | <?php echo COMP_MOTTO?> </marquee>
            </div>
            <div style="width:10%;float:left;margin-top: 5px;color: white; text-align: center;">
              <div id="refresh"><h3 style="margin:0;font-size:22px;" id="time"><?php date_default_timezone_set("Asia/Jakarta"); echo date('H:i') ?></h3></div>
              <p style="margin:0;font-size:16px;"><?php date_default_timezone_set("Asia/Jakarta"); echo date('d/m/Y') ?></p>
            </div>
            <div class="row no-padding">
              <div class="col-md-8 no-padding" style="padding-right: 5px !important">
                <div class="col-xs-12 widget-container-col ui-sortable no-padding" style="padding-right: 20px" id="widget-container-col-1">
                  <!-- <span style="" class="stamp is-nope-2">Uji Coba</span> -->
                  <?php for($box=1;$box<6;$box++) :?>
                    <div class="alert alert-success" style="background-image: linear-gradient(#00cc00, #004d00);color:white;border-radius:5px;">
                      <div class="text-no" style="width:15%;float:left;border-right:2px solid white; margin-right: 20px; text-align: center">
                        <span><?php echo $box?></span>
                      </div>
                      <div class="nama-pasien-antrian" id="antrian-ke-<?php echo $box;?>" style="width: 85%">&nbsp;</div> 
                    </div>
                  <?php endfor; ?>
                </div>
                
              </div>
              <div class="col-md-4 no-padding">
                <div class="col-xs-12 widget-container-col ui-sortable no-padding" id="widget-container-col-1">
                  <?php for($i=6;$i<9;$i++) :?>
                    <div class="alert alert-success" style="background-image: linear-gradient(#bbf75a, #9be820d9); color: black !important; font-weight: bold">
                      <div class="nama-pasien-antrian-small" id="antrian-ke-<?php echo $i;?>" style="text-align: left"></div>
                    </div>
                  <?php endfor; ?>
                </div>
                <div class="col-md-12 no-padding" style="padding-left: 5px">
                  <div class="alert alert-success center" style="background-image: linear-gradient(#bbf75a, #e86120d9); color: black !important; font-weight: bold">
                      <span style="font-size: 1.5em; font-weight: bold">Total antrian dalam proses</span><br>
                      <span style="font-size: 4em; font-weight: bold" id="total-antrian">0</span><br><span style="font-size: 2em; font-weight: bold" id="txt-pasien"> (Resep Obat) </span>
                    </div>
                </div>
                <div class="col-md-12 no-padding" style="padding-left: 5px">
                  <div class="alert alert-success center" style="background-image: linear-gradient(#bbf75a, #2082e8d9); color: black !important; font-weight: bold">
                      <span style="font-size: 3em; font-weight: bold" class="blink_me">LAYANAN ANTAR OBAT KE RUMAH</span><br>
                      <span style="font-size: 1.5em; font-weight: bold">"Jika Bosan Menunggu Lama, Kami Yang Antar Kerumah Anda"</span>
                    </div>
                </div>
                <!-- <div class="col-md-6 no-padding" style="padding-left: 5px">
                  <div class="alert alert-success" style="background-image: linear-gradient(#bbf75a, #20d6e8d9); color: black !important; font-weight: bold">
                      <span style="font-size: 1.5em; font-weight: bold">Sudah dilayani</span><br>
                      <span style="font-size: 3em; font-weight: bold">153</span>
                    </div>
                </div> -->
              </div><!-- /.col -->
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
