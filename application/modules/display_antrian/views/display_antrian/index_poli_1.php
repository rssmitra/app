
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>Smart Hospital System 4.0</title>

		<meta name="description" content="top menu &amp; navigation" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

		<!-- bootstrap & fontawesome -->
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.css" />
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/font-awesome.css" />
    <!-- css date-time -->
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap-timepicker.css" />
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
    <!-- end css date-time -->
    <!-- ace styles -->
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/css_custom.css" />
    <link rel="shortcut icon" href="<?php echo base_url().'assets/insani/favicon_rssm.png'; ?>">

	</head>
  <style>
    @font-face { font-family: MyriadPro; src: url('assets/fonts/MyriadPro-Bold.otf'); } 

    .page-content {
        /* background-color: #ffffff; */
        background: url('assets/images/unit-pendaftaran.jpg') fixed ;
        position: relative;
        margin: 0;
        padding: 0px 20px 24px;
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
        height: 100% !important;
        /* min-height: 750px; */
    }

    .page-header {
      padding-bottom: 9px;
      margin: 0px 0 0px !important;
      border-bottom: 1px solid #eee;
    }

    .footer{
      padding: 16px !important;
    }

    .table tr {
      font-size: 2.2em;
    }

    #section_antrian_farmasi{
        background: #0166a0;
        border-radius: 30px;
        padding: 18px;
        color: white;
    }

    #section_antrian_poli{
        background: #b179b5;
        border-radius: 30px;
        padding: 18px;
        color: white;
    }

    .table {
      font-family: Arial, Helvetica, sans-serif;
      border-collapse: collapse;
      width: 100%;
    }

    .table td, .table th {
      border: 1px solid black;
      padding: 8px;
    }

    .resep_masuk tr:nth-child(even){background-color:rgba(224, 198, 226, 0.3);}
    .resep_sedang_proses tr:nth-child(even){background-color:rgba(224, 198, 226, 0.3);}
    .pengambilan_resep tr:nth-child(even){background-color:rgba(224, 198, 226, 0.3);}
    .sedang_dilayani_poli tr:nth-child(even){background-color:rgba(224, 198, 226, 0.3);}
    .antrian_poli_selanjutnya tr:nth-child(even){background-color:rgba(198, 200, 226, 0.3);}

    #data_antrian_poli_selanjutnya tbody{
      background:rgb(69, 82, 35);
    }

    #data_sedang_dilayani_poli tbody{
      background:rgba(15, 80, 124, 0.83) !important;
    }
    

    .resep_masuk tr:hover {background-color: black;}

    .table th {
      padding-top: 12px;
      padding-bottom: 12px;
      text-align: left;
      color: white;
    }
    

  </style>
	<body class="no-skin" style="background: url('assets/images/unit-pendaftaran.jpg'); min-height: 1920px">
	
		<div class="main-container ace-save-state" id="main-container">
			<script type="text/javascript">
				try{ace.settings.loadState('main-container')}catch(e){}
			</script>

			<div class="main-content" style="background: white">
        <div class="page-header center no-padding" style="background: #f3f3f3; border-bottom-left-radius: 50px; border-bottom: 8px solid #137cc1">
          <a href="<?php echo base_url().'Display_antrian/poli'?>"><img src="<?php echo base_url().'assets/insani/logo_rssm_insani_care.png'?>" style="width: 350px; text-align: center; "></a>
        </div>

				<div class="main-content-inner">

					<div class="page-content">

            <div id="page-area-content" style="height: 100% !important">
              <br>
              <style>
                .widget-title{
                  font-size: medium !important;
                  font-weight: bold;
                }
                .widget-color-dark {
                  border-color: #dfdcdc;
                  border: 0px !important
                }

                .display-video{
                  height:500px;
                  background-color:black;
                }

                @media only screen 
                and (min-width : 1824px) {
                  .display-video{
                    height:auto;
                    background-color:black;
                  }
                }

                .bg {
                  /* Full height */
                  height: 100%;
                  border-radius: 45px;
                  width: 100%;
                  /* Center and scale the image nicely */
                  background-position: center;
                  background-repeat: no-repeat;
                  background-size: cover;
                }
              </style>

              <!-- section advertisement -->
              <div class="row" style="max-height: 30%">
                <div id="myCarousel" class="carousel slide" data-ride="carousel">
                  <!-- Indicators -->
                  <ol class="carousel-indicators">
                    <li data-target="#myCarousel" data-slide-to="0" class="active"></li>
                    <li data-target="#myCarousel" data-slide-to="1"></li>
                    <li data-target="#myCarousel" data-slide-to="2"></li>
                  </ol>

                  <!-- Wrapper for slides -->
                  <div class="carousel-inner">
                    <div class="item active">
                      <img src="<?php echo base_url().'assets/insani/banner/img_reg_online.png'?>" alt="Los Angeles" style="width:100%;">
                    </div>

                    <div class="item">
                      <img src="<?php echo base_url().'assets/insani/banner/paket_mcu_haji.jpeg'?>" alt="Chicago" style="width:100%;">
                    </div>
                  
                    <div class="item">
                      <img src="<?php echo base_url().'assets/insani/banner/img_bpjs_naker.png'?>" alt="New york" style="width:100%;">
                    </div>
                  </div>

                  <!-- Left and right controls -->
                  <a class="left carousel-control" href="#myCarousel" data-slide="prev">
                    <span class="glyphicon glyphicon-chevron-left"></span>
                    <span class="sr-only">Previous</span>
                  </a>
                  <a class="right carousel-control" href="#myCarousel" data-slide="next">
                    <span class="glyphicon glyphicon-chevron-right"></span>
                    <span class="sr-only">Next</span>
                  </a>
                </div>
              </div>
              <hr>
              <!-- section antrian farmasi -->
              <div id="section_antrian_farmasi" class="row" >
                <p style="text-align: center; font-size: 3.5em; font-weight: bold; color: white ">ANTRIAN FARMASI</p>
                <div class="col-md-12">
                  <div class="col-md-4">
                    <span style="background:  #b179b5; padding: 10px; color: white; text-align: center; font-size: 2.2em; font-weight: bold; padding-bottom: 5px">RESEP MASUK</span>
                      <div style="padding: 3px; overflow-y: auto; height: 500px ">
                        <table id="data_resep_masuk" class="table resep_masuk" style="max-height: 730px">
                            <tbody style="background:rgba(214, 161, 218, 0.42)">
                              <tr>
                                <td align="center">1</td>
                                <td>-Tidak ada resep-</td>
                              </tr>
                              <?php for($i=2;$i<11; $i++) : ?>
                                <tr>
                                  <td align="center"><?php echo $i;?></td>
                                  <td>-</td>
                                </tr>
                              <?php endfor;?>
                            </tbody>
                        </table>
                      </div>
                  </div>
                  <div class="col-md-4">
                    <span style="background:  #ed8222; padding: 10px; color: white; text-align: center; font-size: 2.2em; font-weight: bold; padding-bottom: 5px">DALAM PROSES</span>
                    <div style="padding: 3px; overflow-y: auto; height: 500px ">
                      <table id="data_resep_proses" class="table resep_sedang_proses">
                        <tbody style="background:rgba(240, 171, 112, 0.41)">
                          <tr>
                            <td align="center">1</td>
                            <td>-Tidak ada resep-</td>
                          </tr>
                          <?php for($i=2;$i<11; $i++) : ?>
                            <tr>
                              <td align="center"><?php echo $i;?></td>
                              <td>-</td>
                            </tr>
                          <?php endfor;?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <div class="col-md-4">
                  <span style="background:  #df1e8e; padding: 10px; color: white; text-align: center; font-size: 2.2em; font-weight: bold; padding-bottom: 5px">PENGAMBILAN OBAT</span>
                    <div style="padding: 3px; overflow-y: auto; height: 500px ">
                      <table id="data_resep_pengambilan" class="table pengambilan_resep" style="max-height: 550px">
                      
                        <tbody style="background:rgba(240, 112, 186, 0.39)">
                          <tr>
                            <td align="center">1</td>
                            <td>-Tidak ada resep-</td>
                          </tr>
                          <?php for($i=2;$i<11; $i++) : ?>
                            <tr>
                              <td align="center"><?php echo $i;?></td>
                              <td>-</td>
                            </tr>
                          <?php endfor;?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>
              <hr>
              <!-- section antrian poli -->
              <div id="section_antrian_poli" class="row" >
                <p style="text-align: center; font-size: 3.5em; font-weight: bold; color: white ">ANTRIAN POLIKLINIK</p>
                <div class="col-md-12">
                  <div class="col-md-6">
                  <span style="background:rgba(39, 147, 220, 0.47); padding: 10px; color: white; text-align: center; font-size: 2.2em; font-weight: bold; padding-bottom: 5px">SEDANG DILAYANI</span>
                    <div style="padding: 3px; overflow-y: auto; height: 500px ">
                      <table class="table sedang_dilayani_poli" id="data_sedang_dilayani_poli">
                        <thead>
                            <tr style="background: #137cc1 !important;">
                              <th class="center">No</th>
                              <th>Nama Pasien</th>
                              <th>Poli</th>
                            </tr>
                        </thead>
                        <tbody style="background:rgb(15, 53, 78)">
                          <tr>
                            <td align="center">1</td>
                            <td>-Tidak ada data-</td>
                            <td>-</td>
                          </tr>
                          <?php for($i=2; $i<11; $i++) : ?>
                          <tr>
                            <td align="center"><?php echo $i?></td>
                            <td>-</td>
                            <td>-</td>
                          </tr>
                          <?php endfor; ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                  <div class="col-md-6">
                  <span style="background:  #b7d661; padding: 10px; color: white; text-align: center; font-size: 2.2em; font-weight: bold; padding-bottom: 5px">ANTRIAN SELANJUTNYA</span>
                    <div style="padding: 3px; overflow-y: auto; height: 500px ">
                      <table class="table antrian_poli_selanjutnya" id="data_antrian_poli_selanjutnya">
                        <thead>
                            <tr style="background:rgb(121, 155, 27) !important;">
                              <th class="center">No</th>
                              <th>Nama Pasien</th>
                              <th>Poli</th>
                            </tr>
                        </thead>
                        <tbody style="background:rgb(69, 82, 35)">
                          <tr>
                            <td align="center">1</td>
                            <td>-Tidak ada data-</td>
                            <td>-</td>
                          </tr>
                          <?php for($i=2; $i<11; $i++) : ?>
                            <tr>
                            <td align="center"><?php echo $i?></td>
                            <td>-</td>
                            <td>-</td>
                          </tr>
                          <?php endfor; ?>
                        </tbody>
                      </table>
                    </div>
                  </div>
                </div>
              </div>

            </div>

            

					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->

			<!-- <div class="footer">
        
				<div class="footer-inner">
					<div class="footer-content">
            <div class="center">
              <span style="font-size: 1.5em; font-weight: bold; padding: 20px; font-style: italic;">Partners and Integrated System :</span><br>
              <?php for($i=1; $i<13; $i++) : ?>
              <img style="padding: 10px" height="80px" src="<?php echo base_url().'assets/insani/partner/'.$i.'.png'?>">
              <?php endfor;?>
            </div>
						<span class="bigger-120">
							<span class="white bolder">RS Setia Mitra</span>
							| <i>Smart Hospital System 4.0 </i> &copy; 2018-<?php echo date('Y')?>
						</span>
					</div>
				</div>
			</div> -->

			<a href="#" id="btn-scroll-up" class="btn-scroll-up btn btn-sm btn-inverse">
				<i class="ace-icon fa fa-angle-double-up icon-only bigger-110"></i>
			</a>
		</div><!-- /.main-container -->

		<!-- basic scripts -->

		
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

    <!-- ace scripts -->
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

    <script type="text/javascript"> ace.vars['base'] = '..'; </script>
    <script src="<?php echo base_url()?>assets/js/ace/elements.onpage-help.js"></script>
    <script src="<?php echo base_url()?>assets/js/ace/ace.onpage-help.js"></script>
    <script src="<?php echo base_url()?>assets/js/custom/menu_load_page.js"></script>
    
    <!-- farmnasi -->
    <script>
      $(document).ready( function(){

        setInterval( function () {
          
          // antrian farmasi
          $.getJSON("<?php echo site_url('display_antrian/reload_antrian_farmasi') ?>", '', function (data) {   
            
            var obj = data.result;

            if(obj.total_resep_masuk > 0){
              $('#data_resep_masuk tbody').remove();
              $.each(obj.resep_masuk, function (i, o) {  
                if(i < 11){
                  var blink_me = (i == 0) ? 'class="blink_me"' : '';
                  var icon = (i == 0) ? '<i class="fa fa-check-circle white bigger-120"></i>' : i;  
                  $('<tr><td align="center">'+icon+'</td><td><span '+blink_me+'>'+o.nama_pasien.substr(0,15)+'</span></td></tr>').appendTo($('#data_resep_masuk'));
                }
              })
            }

            if(obj.total_dalam_proses > 0){
              $('#data_resep_proses tbody').remove();
              $.each(obj.dalam_proses, function (i, o) {  
                if(i < 11){
                  var blink_me = (i == 0) ? 'class="blink_me"' : '';
                  var icon = (i == 0) ? '<i class="fa fa-check-circle white bigger-120"></i>' : i;
                  $('<tr><td align="center">'+icon+'</td><td><span '+blink_me+'>'+o.nama_pasien.substr(0,15)+'</span></td></tr>').appendTo($('#data_resep_proses'));
                  }
              })
            }

            if(obj.total_pengambilan > 0){
              $('#data_resep_pengambilan tbody').remove();
              $.each(obj.pengambilan, function (i, o) {  
                if(i < 11){
                  var blink_me = (i == 0) ? 'class="blink_me"' : '';
                  var icon = (i == 0) ? '<i class="fa fa-check-circle white bigger-120"></i>' : i;
                  $('<tr><td align="center">'+icon+'</td><td><span '+blink_me+'>'+o.nama_pasien.substr(0,15)+'</span></td></tr>').appendTo($('#data_resep_pengambilan'));
                }
              })
            }
            

          });

          // antrian poli
          $.getJSON("<?php echo site_url('display_antrian/reload_antrian_poli') ?>", '', function (data) {   
            
            var obj = data.result;

            if(obj.total_sedang_dilayani > 0){
              $('#data_sedang_dilayani_poli tbody').remove();
              $.each(obj.sedang_dilayani, function (i, o) {  
                $('<tr><td align="center">'+o.no_antrian+'</td><td><span>'+o.nama_pasien.substr(0,15)+'</span></td><td align="center">'+o.kode_poli_bpjs+'</td></tr>').appendTo($('#data_sedang_dilayani_poli'));
              })
            }

            if(obj.total_next_antrian > 0){
              $('#data_antrian_poli_selanjutnya tbody').remove();
              $.each(obj.next_antrian, function (i, o) {  
                $('<tr><td align="center">'+o.no_antrian+'</td><td><span>'+o.nama_pasien.substr(0,15)+'</span></td><td align="center">'+o.kode_poli_bpjs+'</td></tr>').appendTo($('#data_antrian_poli_selanjutnya'));
              })
            }
            
          });

        }, 2000 );
      
      });
      
      setInterval("my_function();",3000); 

      function my_function(){

        $('#refresh').load(location.href + ' #time');

      }

    </script>

    
</body>
</html>
