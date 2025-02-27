
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title>SHS 4.0 - Antrian Poliklinik</title>

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
    @font-face { 
      font-family: 'MyriadPro'; 
      src: url('<?php echo base_url()?>assets/fonts/MyriadPro-Bold.otf'); 
    } 

    body{
      font-family: 'MyriadPro' !important;
      background: url('<?php echo base_url()?>assets/images/unit-pendaftaran.jpg') fixed !important;
      background-color: #E6E7E8;
    }

    .page-content {
        background-color: #E6E7E8;
       
        position: relative;
        margin: 0;
        padding: 0px 20px 24px;
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
        height: 100% !important;
        min-height: 670px;
    }

    .page-header {
      padding-bottom: 9px;
      margin: 0px 0 0px !important;
      border-bottom: 1px solid #eee;
      background-color: #E6E7E8;
    }

    .footer{
      padding: 16px !important;
    }

    .table tr {
      font-size: 2.2em;
    }

    .table {
      /* border-collapse: collapse; */
      width: 100%;
    }

    .table td, .table th {
      border: 0px solid black !important;
      padding: 8px;
      color: white
    }

    .table th {
      padding-top: 12px;
      padding-bottom: 12px;
      text-align: left;
      color: white !important;
    }
    

  </style>

	<body class="no-skin">
	
		<div class="main-container ace-save-state" id="main-container">
			<script type="text/javascript">
				try{ace.settings.loadState('main-container')}catch(e){}
			</script>

			<div class="main-content">

        <div class="col-md-12" style="padding: 10px">
            <div style="float: left; margin-left: 20px; margin-top: 10px">
              <img alt="" src="<?php echo base_url().COMP_ICON_INSANI?>" width="300px">
            </div>
            <div style="float: right; margin-top: 10px; margin-right: 10px">
              <span class="title-text"><img alt="" src="<?php echo base_url().COMP_ICON_BY_INSANI?>" width="150"></span>
            </div>
        </div>

        

        <div class="col-md-12" style="background: #00669F; color: white; padding: 5px; border-top-left-radius: 15px; border-top-right-radius: 15px">
          <div style="font-size: 25px; font-weight: bold; float: left; padding-left: 20px">Antrian Poliklinik</div>
          <div style="text-align: right; font-size: 20px; margin-top: 3px; float: right; margin-right: 20px" >
            <i class="fa fa-calendar"></i> <?php date_default_timezone_set("Asia/Jakarta"); echo date('l, d F Y') ?> &nbsp; <i class="fa fa-clock-o"></i>  
            <span id="refresh">&nbsp;
                <span id="time"><?php date_default_timezone_set("Asia/Jakarta"); echo date('H:i') ?></span> WIB
          </div>
        </div>

				<div class="main-content-inner">
					<div class="page-content">
            <div id="page-area-content" >
              <!-- section antrian poli -->
              <div id="section_antrian_poli" class="row" style="margin-top: 10px">
                
                <div class="col-md-12 no-padding">

                  <?php 
                    $arr_color = array('#137CC1','#748b33','#ED8222','#DF1E8E','#B179B5','#137CC1','#748b33','#ED8222','#DF1E8E','#B179B5','#137CC1','#748b33','#ED8222','#DF1E8E','#B179B5'); 
                    shuffle($arr_color);
                    foreach($data_loket as $key=>$row) : if(!in_array($row->jd_kode_spesialis, ['013101','012101'])) : ?>
                    <div class="col-md-4" style="padding-bottom:10px; ">
                      <div style="background: #5882B0 !important; padding: 5px; color: white; border-top-right-radius: 50px; border-top-left-radius: 10px;">
                        <span style="text-align: center; font-size: 1.5em; font-weight: bold; padding-bottom: 5px"><?php echo trim(strtoupper($row->short_name))?></span><br><span style="text-align: center; font-size: 1.8em; font-weight: bold; padding-bottom: 5px"><?php echo substr($row->nama_pegawai,0,35)?></span>
                      </div>
                      <div style="height: 100px">
                        <table class="table" id="table_<?php echo $row->kode_poli_bpjs?>_<?php echo $row->jd_kode_dokter?>">
                          <tbody style="background: #00669F">
                            <tr>
                              <td align="center">-</td>
                              <td>-Tidak ada data-</td>
                            </tr>
                            <?php for($i=2; $i<3; $i++) : ?>
                            <tr>
                              <td align="center">-</td>
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
      
			<div class="footer">
				<div class="footer-inner" style="background: #0765a1;color: white; ">
					<div class="footer-content" style="background: #0765a1;color:">
            <!-- <div class="center">
              <span style="font-size: 1.5em; font-weight: bold; padding: 20px; font-style: italic;">Partners and Integrated System :</span><br>
              <?php for($i=1; $i<13; $i++) : ?>
              <img style="padding: 10px" height="80px" src="<?php echo base_url().'assets/insani/partner/'.$i.'.png'?>">
              <?php endfor;?>
            </div> -->
						<span class="bigger-120" style="font-size: 18px !important; font-weight: bold; float: left">
							<span class="white bolder">RS Setia Mitra</span>
							| <i>Smart Hospital System 4.0 </i> &copy; 2018-<?php echo date('Y')?>
						</span>
            <span style="font-size: 16px; text-align: center; color: white; font-weight: bold; float: right">
              <i class="fa fa-check-circle bigger-120"></i> Pasien Sedang Dilayani
              <i class="fa fa-clock-o bigger-120"></i> Antrian Pasien Berikutnya
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

        // setInterval( function () {
          

          // antrian poli
          $.getJSON("<?php echo site_url('display_antrian/reload_antrian_poli') ?>", '', function (data) {   
            
            // console.log(data.result);
            $.each(data.result, function (key, val) { 
              // console.log(val);
              $.each(val, function (keys, vals) {  
                console.log(key);
                $('#table_'+key+'_'+keys+' tbody').remove();
                var length = vals.length;
                $.each(vals, function (k, v) {  
                  // console.log(k);
                  // console.log(v);
                  if(k < 2){
                    var prefix = (v.kode_perusahaan == 120)?'B':'A';
                    var lgth_no_antrian = v.no_antrian.toString();
                    // console.log(lgth_no_antrian);
                    var no_antrian = (lgth_no_antrian.length == 1) ? '0'+v.no_antrian : v.no_antrian;
                    var icon = (k == 0) ? '<span style="float: right !important"><i class="fa fa-circle green"></i></span>' : '' ;
                    $('<tr style="background: #00669F"><td align="center"><span style="border-right: 1px solid white !important;">'+prefix+' '+no_antrian+' &nbsp;&nbsp;</span></td><td><span>'+v.nama_pasien.substr(0,15)+'</span>'+icon+'</td></tr>').appendTo($('#table_'+v.kode_poli_bpjs+'_'+v.kode_dokter+''));
                  }

                  if(length == 1){
                    $('<tr style="background: #00669F"><td align="center"><span style="border-right: 1px solid white !important;">X 00 &nbsp;&nbsp;</span></td><td>-</td></tr>').appendTo($('#table_'+v.kode_poli_bpjs+'_'+v.kode_dokter+''));
                  }
                  
                })

                

              })
              
            })

            
          });

        // }, 2000 );
      
        setInterval("reload_page();",3000);

      });
      

      function reload_page(){

        // $('#refresh').load(location.href + ' #time');
        location.reload(location.href);

      }

    </script>

    
</body>
</html>
