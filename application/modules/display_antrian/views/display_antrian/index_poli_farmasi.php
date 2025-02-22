
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
    <!-- <link rel="stylesheet" href="<?php echo base_url()?>assets/css/font-awesome.css" /> -->
    <!-- css date-time -->
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap-timepicker.css" />
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
    <!-- end css date-time -->
    <!-- ace styles -->
    <!-- <link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" /> -->
    <!-- <link rel="stylesheet" href="<?php echo base_url()?>assets/css/css_custom.css" /> -->
    <link rel="shortcut icon" href="<?php echo base_url().'assets/insani/favicon_rssm.png'; ?>">

	</head>
  <style>
    @font-face { 
      font-family: 'MyriadPro'; 
      src: url('<?php echo base_url()?>assets/fonts/MyriadPro-Bold.otf'); 
    } 

    .page-content {
        background-color: #E6E7E8;
        /* background: url('<?php echo base_url()?>assets/images/unit-pendaftaran.jpg') ; */
        position: fixed;
        margin: 0;
        padding: 0px 20px 24px;
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
        height: 100% !important;
        min-height: 1050px;
        font-family: 'MyriadPro' !important
    }

    .page-header {
      padding-bottom: 15px;
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
        margin-top: 5px;
    }

    #section_antrian_poli{
        /* background: #b179b5; */
        border-radius: 30px;
        /* padding: 18px; */
        color: white;
        margin-top: 5px;
    }

    .table {
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
	<body class="no-skin" style="background: #E6E7E8; min-height: 1920px">
	
		<div class="main-container ace-save-state" id="main-container">
			<script type="text/javascript">
				try{ace.settings.loadState('main-container')}catch(e){}
			</script>

			<div class="main-content" style="background: #E6E7E8">
        <div class="page-header center no-padding">
          <a href="<?php echo base_url().'Display_antrian/poli'?>"><img src="<?php echo base_url().'assets/insani/Logo-Rssm.png'?>" style="width: 350px; text-align: center; padding: 10px; margin-left: 10px"></a>

          <a href="<?php echo base_url().'Display_antrian/poli'?>"><img src="<?php echo base_url().'assets/insani/by_insanicare.png'?>" style="width: 225px; float: right;padding: 36px "></a>
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

                .title_antrian_pendaftaran{
                  background: #00669F;
                  color: white;
                  height: 70px;
                  width: 400px;
                  font-size: 27px;
                  padding: 15px;
                  margin-left: 15px;
                  border-top-left-radius: 40px;
                  border-top-right-radius: 40px;
                  font-weight: bold;
                  position: absolute
                }

                .title_antrian_poliklinik{
                  background: #00669F;
                  color: white;
                  height: 70px;
                  width: 400px;
                  font-size: 27px;
                  padding: 15px;
                  margin-left: 15px;
                  border-top-left-radius: 40px;
                  border-top-right-radius: 40px;
                  font-weight: bold;
                  position: absolute;
                 
                }

                .row_section{
                  background: #00000029;
                  padding: 6px;
                  padding-bottom: 23px;
                  border-bottom-left-radius: 39px;
                  border-bottom-right-radius: 39px;
                  margin-top: 38px;
                  padding-top: 40px;
                }

                .small-box{
                  height: auto;
                  margin: 10px;
                  background: #00669F;
                  color: white;
                  border-bottom-right-radius: 38px;
                  border-bottom-left-radius: 38px;
                  border-top-right-radius: 38px;
                  padding-bottom: 3px
                }

                .box-poliklinik {
                    height: auto;
                    margin: 10px;
                    background: #00669F;
                    color: white;
                    border-bottom-right-radius: 60px;
                    border-bottom-left-radius: 38px;
                    border-top-right-radius: 60px;
                    padding-bottom: 3px
                }

              </style>

              <!-- section antrian pendaftaran -->
              <!-- title antrian pendaftaran -->
              <div class="title_antrian_pendaftaran">Antrian Pendaftaran</div>
              <div class="row row_section">
                  <?php for($i=1; $i<5; $i++) :?>
                  <div class="col-md-6 no-padding">
                    <div class="small-box">
                      <div class="header" style="height:30px;background-color: #5882B0;border-top-right-radius: 156px;">
                        <p style="font-size:20px;margin-left:12%; font-weight: bold;"><b>Loket</b><span style="margin-left: 39%">Nomor Antrian</span></p>
                      </div>
                      <div class="inner">

                        <div style="width:30%;float:left;border-right:2px solid white">
                          <h1 style="margin-top:0px;font-size:55px;text-align:center;text-shadow: 5px 3px 6px black;">0<?php echo $i?></h1>
                        </div>

                        <div id="auto<?php $i?>" style="margin-top:15px;margin-bottom:-17px;width:70%float:left;height:80px;"></div>
                                
                      </div>
                    </div>
                  </div>
                  <?php endfor; ?>

              </div>

              <!-- section antrian poliklinik -->
              <!-- title antrian pendaftaran -->
              <div class="title_antrian_poliklinik" style="margin: 16px">Antrian Poliklinik</div>
              <div class="row row_section" style="margin-top: 55px !important">
                  <?php for($i=1; $i<4; $i++) :?>
                  <div class="col-md-12 no-padding">
                    <div class="small-box box-poliklinik">
                      <div class="header" style="height:80px;background-color: #5882B0;border-top-right-radius: 60px;">
                        <p style="font-size:20px;margin-left:2%; font-weight: bold; padding-top: 10px">
                          <span style="font-size: 22px">Spesialis Jantung</span><br>
                          <span style="font-size: 30px">dr. Adelin Dhivi Kemalsari, Sp.JP</span>
                        </p>
                      </div>
                      <div class="inner">
                          <div style="width:25%;float:left;border-right:2px solid white">
                            <h1 style="margin-top:0px;font-size:55px;text-align:center;text-shadow: 5px 3px 6px black;">
                              <span style="margin-right: 10px">A</span> 0<?php echo $i?></h1>
                          </div>

                          <div style="margin-left: 10px;margin-top:15px;margin-bottom:-17px;width:75% float:left;height:80px; font-size: 55px">
                            <h1 style="margin-top:0px;font-size:55px;text-align:left;text-shadow: 5px 3px 6px black;">
                              <span style="padding-left: 10px;">Syahir</h1>
                          </div>    
                      </div>

                      <div class="inner">
                          <div style="width:25%;float:left;border-right:2px solid white; border-top: 2px solid white">
                            <h1 style="margin-top:13px;font-size:55px;text-align:center;text-shadow: 5px 3px 6px black;">
                              <span style="margin-right: 10px">Next</h1>
                          </div>

                          <div style="margin-left: 10px;margin-top:15px;margin-bottom:-17px;width:75% float:left;height:110px; font-size: 55px; border-top: 2px solid white">
                            <h1 style="margin-top:13px;font-size:55px;text-align:left;text-shadow: 5px 3px 6px black;">
                              <span style="padding-left: 10px;">Oki</h1>
                          </div>    
                      </div>

                    </div>
                  </div>
                  <?php endfor; ?>
              </div>

              <!-- title antrian farmasi -->
              <div class="title_antrian_pendaftaran" style="margin: 16px">Antrian Farmasi</div>
              <div class="row row_section" style="margin-top: 55px">

                <div class="col-md-4 no-padding">
                  <div class="small-box">
                    <div class="header" style="height:45px;background-color: #5882B0;border-top-right-radius: 156px;">
                      <p style="font-size:24px; text-align: center; font-weight: bold; padding-top: 8px"><b>Resep Masuk</b></p>
                    </div>
                    <div class="inner">
                      <div style="padding: 0px; overflow-y: auto; height: 300px ">
                        <table id="data_resep_masuk" class="table resep_masuk">
                            <tbody>
                              <tr>
                                <td align="center">-</td>
                                <td>-Tidak ada resep-</td>
                              </tr>
                              <?php for($i=1;$i<6; $i++) : ?>
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

                <div class="col-md-4 no-padding">
                  <div class="small-box">
                    <div class="header" style="height:45px;background-color: #5882B0;border-top-right-radius: 156px;">
                      <p style="font-size:24px; text-align: center; font-weight: bold; padding-top: 8px"><b>Dalam Proses</b></p>
                    </div>
                    <div class="inner">
                      <div style="padding: 0px; overflow-y: auto; height: 300px ">
                        <table id="data_resep_masuk" class="table resep_masuk">
                            <tbody>
                              <tr>
                                <td align="center">-</td>
                                <td>-Tidak ada resep-</td>
                              </tr>
                              <?php for($i=1;$i<6; $i++) : ?>
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

                <div class="col-md-4 no-padding">
                  <div class="small-box">
                    <div class="header" style="height:45px;background-color: #5882B0;border-top-right-radius: 156px;">
                      <p style="font-size:24px; text-align: center; font-weight: bold; padding-top: 8px"><b>Penyerahan Obat</b></p>
                    </div>
                    <div class="inner">
                      <div style="padding: 0px; overflow-y: auto; height: 300px ">
                        <table id="data_resep_masuk" class="table resep_masuk">
                            <tbody>
                              <tr>
                                <td align="center">-</td>
                                <td>-Tidak ada resep-</td>
                              </tr>
                              <?php for($i=1;$i<6; $i++) : ?>
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

              </div>
              <hr>


            </div>

            

					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->

			<div class="footer">
        
				<!-- <div class="footer-inner">
					<div class="footer-content" style="background: #b279b5;color: white;">
          <span style="font-size: 2em; text-align: center; font-weight: bold;">
            <i class="fa fa-check-circle bigger-120"></i> Pasien Sedang Dilayani
            <i class="fa fa-clock-o bigger-120"></i> Antrian Pasien Berikutnya
          </span>
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
                if(i < 6){
                  var blink_me = (i == 0) ? 'class="blink_me"' : '';
                  var icon = (i == 0) ? '<i class="fa fa-check-circle white bigger-120"></i>' : i;  
                  $('<tr><td align="center">'+icon+'</td><td><span '+blink_me+'>'+o.nama_pasien.substr(0,15)+'</span></td></tr>').appendTo($('#data_resep_masuk'));
                }
              })
            }

            if(obj.total_dalam_proses > 0){
              $('#data_resep_proses tbody').remove();
              $.each(obj.dalam_proses, function (i, o) {  
                if(i < 6){
                  var blink_me = (i == 0) ? 'class="blink_me"' : '';
                  var icon = (i == 0) ? '<i class="fa fa-check-circle white bigger-120"></i>' : i;
                  $('<tr><td align="center">'+icon+'</td><td><span '+blink_me+'>'+o.nama_pasien.substr(0,15)+'</span></td></tr>').appendTo($('#data_resep_proses'));
                  }
              })
            }

            if(obj.total_pengambilan > 0){
              $('#data_resep_pengambilan tbody').remove();
              $.each(obj.pengambilan, function (i, o) {  
                if(i < 6){
                  var blink_me = (i == 0) ? 'class="blink_me"' : '';
                  var icon = (i == 0) ? '<i class="fa fa-check-circle white bigger-120"></i>' : i;
                  $('<tr><td align="center">'+icon+'</td><td><span '+blink_me+'>'+o.nama_pasien.substr(0,15)+'</span></td></tr>').appendTo($('#data_resep_pengambilan'));
                }
              })
            }
            

          });

          // antrian poli
          $.getJSON("<?php echo site_url('display_antrian/reload_antrian_poli') ?>", '', function (data) {   
            
            // console.log(data.result);
            $.each(data.result, function (key, val) { 
              // console.log(val);
              $.each(val, function (keys, vals) {  
                console.log(key);
                $('#table_'+key+'_'+keys+' tbody').remove();
                $.each(vals, function (k, v) {  
                  // console.log(k);
                  // console.log(v);
                  if(k < 2){
                    var icon = (k == 0) ? '<i class="fa fa-check-circle bigger-120"></i>' : '<i class="fa fa-clock-o bigger-120"></i>' ;
                    $('<tr style="background:rgb(15, 53, 78)"><td align="center">'+icon+'</td><td><span>'+v.nama_pasien.substr(0,15)+'</span></td></tr>').appendTo($('#table_'+v.kode_poli_bpjs+'_'+v.kode_dokter+''));
                  }
                  
                })

              })
              
            })

            
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
