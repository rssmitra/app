
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
	<body class="no-skin" style="background: url('assets/images/unit-pendaftaran.jpg'); min-height: 1080px">
	
		<div class="main-container ace-save-state" id="main-container">
			<script type="text/javascript">
				try{ace.settings.loadState('main-container')}catch(e){}
			</script>

			<div class="main-content" style="background: white">
        <div class="page-header" style="background: #f3f3f3; border-bottom-left-radius: 50px; border-bottom: 8px solid #137cc1">
          <div class="col-md-3 no-padding" >
            <a href="<?php echo base_url().'Display_antrian/poli'?>"><img src="<?php echo base_url().'assets/insani/logo_rssm_insani_care.png'?>" style="width: 300px; text-align: left; padding-left: 30px "></a>
          </div>
          <p style="text-align: right; padding: 20px; font-size: 3.5em; font-weight: bold; color: #0f354e; text-shadow: 2px 4px rgb(63 65 67 / 31%); font-familiy: system-ui">ANTRIAN POLIKLINIK</p>

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

              <!-- section antrian poli -->
              <div id="section_antrian_poli" class="row" >
                

                <div class="row" style="font-size: 2em; text-align: center; color: #126399; font-weight: bold">
                  <i class="fa fa-check-circle bigger-120"></i> Pasien Sedang Dilayani
                  <i class="fa fa-clock-o bigger-120"></i> Antrian Pasien Berikutnya
                </div>
                <hr>

                <div class="col-md-12 no-padding">

                  <?php 
                    $arr_color = array('#137CC1','#748b33','#ED8222','#DF1E8E','#B179B5','#137CC1','#748b33','#ED8222','#DF1E8E','#B179B5','#137CC1','#748b33','#ED8222','#DF1E8E','#B179B5'); 
                    shuffle($arr_color);
                    foreach($data_loket as $key=>$row) : if(!in_array($row->jd_kode_spesialis, ['013101','012101'])) : ?>
                    <div class="col-md-4" style="padding-bottom:10px">
                      <div style="background: <?php echo array_shift($arr_color)?> !important; padding: 5px; color: white">
                        <span style="text-align: center; font-size: 1.8em; font-weight: bold; padding-bottom: 5px"><?php echo trim(strtoupper($row->short_name))?></span><br><span style="text-align: center; font-size: 1.5em; font-weight: bold; padding-bottom: 5px"><?php echo substr($row->nama_pegawai,0,35)?></span>
                      </div>
                      <div style="height: 120px">
                        <table class="table sedang_dilayani_poli" id="table_<?php echo $row->kode_poli_bpjs?>_<?php echo $row->jd_kode_dokter?>">
                          <tbody style="background:rgb(15, 53, 78)">
                            <tr>
                              <td align="center"><i class="fa fa-check-circle bigger-120"></i></td>
                              <td>-Tidak ada data-</td>
                            </tr>
                            <?php for($i=2; $i<3; $i++) : ?>
                            <tr>
                              <td align="center"><i class="fa fa-clock-o bigger-120"></i></td>
                              <td>-</td>
                            </tr>
                            <?php endfor; ?>
                          </tbody>
                        </table>
                      </div>
                    </div>
                  <?php endif; endforeach;?>

                </div>
              </div>
              

            </div>

            

					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->
      
      <hr>
			<div class="footer">
        
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
			</div>

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
