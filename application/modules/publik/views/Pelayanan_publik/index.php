<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="description" content="<?php echo $app->header_title?>" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
	<!-- bootstrap & fontawesome -->
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.css" />
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/font-awesome.css" />

    <!-- page specific plugin styles -->
    <!-- <link rel="stylesheet" href="<?php echo base_url()?>assets/css/AdminLTE.css" class="ace-main-stylesheet" id="main-ace-style" /> -->
    <!-- text fonts -->
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace-fonts.css" />
    <!-- css date-time -->
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap-timepicker.css" />
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
    <!-- end css date-time -->
    <!-- ace styles -->
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/css_custom.css" />
    <link rel="shortcut icon" href="<?php echo base_url().COMP_ICON; ?>">
    <title><?php echo $app->header_title?></title>
	
  </head>
  <style>
    @media (max-width: 479px){
      .navbar-fixed-top + .main-container {
          padding-top: 50px !important;
      }
    }
    .main-container {
        /* background-color: #ffffff; */
        background: url('<?php echo base_url()?>assets/images/download.png') fixed;
        position: relative;
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
        min-height: 900px;
    }

    .responsive {
      width: 100%;
      height: auto;
      /* max-height: 250px;  */
    }
    
  </style>


  <body class="no-skin" style="background: url('assets/images/unit-pendaftaran.jpg');">
	<div class="navbar navbar-inverse navbar-fixed-top" style="background: #024a19">
		<div class="container">
			<div class="navbar-header pull-left">
				<a class="navbar-brand" href="#">
            <span style="font-size: 14px; font-weight: bold">
              <i class="glyphicon glyphicon-leaf"></i>&nbsp;
              Pelayanan Pasien RS Setia Mitra
            </span>
				</a>
			</div>
		</div>
  </div>
	
    <div class="container main-container">

      <div id="page-area-content">
      
        <div class="center">
          <?php 
            $banner_active = isset($banner->value) ? $banner->value : '1-default.jpeg';
          ?>
          <img class="center responsive" src="<?php echo 'http://10.10.11.5:88/sirs/app/uploaded/images/'.$banner_active.''; ?>">
        </div>
        
        <div class="row" style="padding: 15px !important;">
          <div class="col-xs-12">
            <h3 class="header smaller lighter green">MENU UTAMA</h3>
            <div class="list-group">
              <a href="#" onclick="getMenu('publik/Pelayanan_publik/registrasi_rj')" class="list-group-item" style="color: white !important;background: green;">
                <b>REGISTRASI KUNJUNGAN RAWAT JALAN</b>
              </a>
              <br />
              <a href="#" onclick="getMenu('publik/Pelayanan_publik/jadwal_dokter')" class="list-group-item" style="color: white !important;background: green;">
                <b>INFORMASI JADWAL DOKTER</b>
              </a>
              <br />
              <a href="#" onclick="getMenu('publik/Pelayanan_publik/antrian_poli')" class="list-group-item" style="color: white !important;background: green;">
                <b>CEK ANTRIAN POLIKLINIK</b>
              </a>
              <br />
              <a href="#" onclick="getMenu('publik/Pelayanan_publik/riwayat_kunjungan')" class="list-group-item" style="color: white !important;background: green;">
                <b>RIWAYAT KUNJUNGAN PASIEN</b>
              </a>
            </div>
            <address style="background: #8080806b; padding: 10px;">
              <b>Keterangan : </b>
              <ol>
                <li>Pendaftaran online hanya untuk satu kali pendaftaran pada hari yang sama.</li>
                <li>Pendaftaran online dapat dilakukan minimal H-1 sebelum kunjungan.</li>
              </ol>
            </address>
          </div>
        </div>
      </div>

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

    <script src="<?php echo base_url()?>assets/js/bootstrap-multiselect.js"></script>

    <!-- page specific plugin scripts -->


    <script src="<?php echo base_url()?>/assets/js/dataTables/jquery.dataTables.js"></script>
    <script src="<?php echo base_url()?>/assets/js/dataTables/jquery.dataTables.bootstrap.js"></script>
    <script src="<?php echo base_url()?>/assets/js/dataTables/extensions/TableTools/js/dataTables.tableTools.js"></script>
    <script src="<?php echo base_url()?>/assets/js/dataTables/extensions/ColVis/js/dataTables.colVis.js"></script>
    
    <!-- ace scripts -->
    <script src="<?php echo base_url()?>assets/js/ace/elements.scroller.js"></script>
    <script src="<?php echo base_url()?>assets/js/ace/elements.colorpicker.js"></script>
    <script src="<?php echo base_url()?>assets/js/ace/elements.fileinput.js"></script>
    <script src="<?php echo base_url()?>assets/js/ace/elements.typeahead.js"></script>
    <script src="<?php echo base_url()?>assets/js/ace/elements.wysiwyg.js"></script>
    <script src="<?php echo base_url()?>assets/js/ace/elements.spinner.js"></script>
    <script src="<?php echo base_url()?>assets/js/ace/elements.treeview.js"></script>
    <script src="<?php echo base_url()?>assets/js/ace/elements.wizard.js"></script>
    <script src="<?php echo base_url()?>assets/js/ace/elements.aside.js"></script>
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

    <!-- achtung loader -->
    <link href="<?php echo base_url()?>assets/achtung/ui.achtung-mins.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="<?php echo base_url()?>assets/achtung/ui.achtung-min.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>assets/achtung/achtung.js"></script> 

    <script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery.form.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>assets/js/jquery-validation/dist/jquery.validate.js"></script>
    
    <script type="text/javascript"> ace.vars['base'] = '..'; </script>
    <script src="<?php echo base_url()?>assets/js/ace/elements.onpage-help.js"></script>
    <script src="<?php echo base_url()?>assets/js/ace/ace.onpage-help.js"></script>

    <!-- highchat modules -->
    <script src="<?php echo base_url()?>assets/chart/highcharts.js"></script>
    <script src="<?php echo base_url()?>assets/chart/modules/exporting.js"></script>
    <script src="<?php echo base_url()?>assets/chart/modules/script.js"></script>
    <!-- end highchat modules -->
    
    <script src="<?php echo base_url()?>assets/js/custom/menu_load_page.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>assets/jquery_number/jquery.number.js"></script>
    <script src="<?php echo base_url()?>assets/js/custom/function.js"></script>
    

  </body>
</html>