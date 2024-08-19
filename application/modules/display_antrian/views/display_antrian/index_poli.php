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
        
          $.getJSON("<?php echo site_url('display_antrian/reload_antrian_poli') ?>", '', function (response) {              
            console.log(response)
            var obj = response.result;
            var nama_pasien = response.nama_pasien;
            
            $('#no_antrian').text(obj.no_antrian);
            $('#nama_pasien_antrian').text(nama_pasien.toUpperCase());
            $('#nama_poli').text(obj.poli.toUpperCase());
            $('#nama_dokter').text(obj.dokter.toUpperCase());
            if(obj.kode_dokter == '0'){
              $('#photo_dokter').attr('src', '<?php echo base_url().PATH_IMG_DEFAULT.'72logo.png'?>');
            }else{
              $('#photo_dokter').attr('src', '<?php echo base_url().PATH_PHOTO_PEGAWAI?>'+obj.kode_dokter+'.png');
            }
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
      .grid2 {
        margin : 0px !important;
        padding: 0px !important;
      }
    </style>
  </head>

  <body class="no-skin" >
    <!-- /section:basics/navbar.layout -->
    <div class="main-container" id="main-container">
      <!-- /section:basics/sidebar.horizontal -->
      <div class="main-content">
        <div class="main-content-inner">
          <div class="page-content-main" style="background-color: black !important">
            
            <div class="row no-padding">
              <div class="col-md-12 no-padding" style="padding-right: 5px !important">
                <div class="col-sm-12 no-padding" style="color: white; padding: 5px !important">
                    <div class="widget-box" style="border-radius: 24px;">
                      
                      <div class="widget-body" style=" background: linear-gradient(180deg, #128812, transparent); font-weight: bold; border-radius: 23px;">
                        <div class="widget-main">
                          <div class="center" style="min-height: 572px !important; text-align: center; vertical-align: middle; margin-top: 0.8% !important">
                            <span style="text-align: center; font-size: 13em" id="no_antrian">0</span><br>
                            <span style="text-align: center; font-size: 10em; line-height: 1em" id="nama_pasien_antrian">-</span>
                          </div>
                          <div class="hr hr8 hr-double"></div>

                          <div class="clearfix" style="background: linear-gradient(45deg, black, transparent);padding: 10px;border-radius: 10px 10px 10px 10px;">
                            <div class="grid2" style="width: 15% !important; text-align: center">
                              <img id="photo_dokter" src="<?php echo base_url().PATH_PHOTO_PEGAWAI.$this->session->userdata('sess_kode_dokter').'.png'?>" width="150px" style="border-radius: 10px">
                            </div>

                            <div class="grid2" style="width: 85% !important; padding-left: 12px !important; text-align: left">
                              <span style="font-weight: bold; font-size: 3.5em !important" id="nama_dokter">-</span>
                              <div style="border-top: 1px solid white"></div>
                              <span style="font-weight: bold; font-size: 2.8em !important" id="nama_poli">-</span>
                            </div>
                          </div>
                        </div><!-- /.widget-main -->
                      </div><!-- /.widget-body -->
                    </div>
                </div>
              </div>
            </div><!-- /.row -->
          </div><!-- /.page-content -->
        </div>
      </div><!-- /.main-content -->
    </div><!-- /.main-container -->
  </body>
</html>
