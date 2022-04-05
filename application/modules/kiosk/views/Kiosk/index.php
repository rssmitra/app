
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
		<meta charset="utf-8" />
		<title><?php echo $app->header_title?></title>

		<meta name="description" content="top menu &amp; navigation" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0" />

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

	</head>
  <style>
    .page-header h1 {
        color: #3a8e3e !important;
    }
    .profile-user-info-striped .profile-info-name {
      color: #078001;
      background-color: #14b60b33;
      font-weight: bold;
      border-top: 1px solid #F7FBFF;
    }
    .page-content {
        /* background-color: #ffffff; */
        background: url('assets/images/unit-pendaftaran.jpg') fixed;
        position: relative;
        margin: 0;
        padding: 0px 20px 24px;
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
        min-height: 750px;
    }

  </style>
	<body class="no-skin" style="background: url('assets/images/unit-pendaftaran.jpg');">
		<div id="navbar" class="navbar navbar-default    navbar-collapse       h-navbar ace-save-state" style="background: green">
			<div class="navbar-container ace-save-state" id="navbar-container" >
				<div class="navbar-header pull-left">
					<a href="" class="navbar-brand">
						<small>
							<i class="fa fa-leaf"></i>
							KIOSK PELAYANAN PASIEN
						</small>
					</a>
				</div>


			</div><!-- /.navbar-container -->
		</div>

		<div class="main-container ace-save-state" id="main-container">
			<script type="text/javascript">
				try{ace.settings.loadState('main-container')}catch(e){}
			</script>

			<div class="main-content">
				<div class="main-content-inner">
					<div class="page-content">

            <!--  hidden form -->
            <input type="hidden" class="form-control" value="" id="noKartuBpjs" name="noKartuBpjs" readonly>
            <input type="hidden" class="form-control" value="" id="no_mr_val" name="no_mr" readonly>
            <input type="hidden" class="form-control" value="" id="nama_pasien" name="nama_pasien" readonly>
            <input type="hidden" class="form-control" value="" id="umur_saat_pelayanan_hidden" name="umur_saat_pelayanan_hidden">
            <input type="hidden" class="form-control" id="jenis_pendaftaran" name="jenis_pendaftaran" value="1">
            
            <div class="page-header">
              <h1>
                <span id="breadcrumb_nama_pasien">Beranda</span>
                <small>
                  <i class="ace-icon fa fa-angle-double-right"></i>
                  <span id="breadcrumb_description">Selamat datang di KIOSK RS Setia Mitra</span>
                </small>
              </h1>
            </div>
            
            <div id="page-area-content">
              <div class="col-md-12 center" style="padding-top: 15%">
                <a href="#" onclick="getMenu('kiosk/Kiosk/pasien_baru')" class="btn btn-lg" style="background: green !important; border-color: green; height: 70px; font-size: 2.8em; border-radius: 10px 10px 10px 10px"><i class="fa fa-user"></i> Pasien Baru</a>
                &nbsp;&nbsp;
                <a href="#" onclick="getMenu('kiosk/Kiosk/pasien_lama')" class="btn btn-lg" style="background: green !important; border-color: green; height: 70px; font-size: 2.8em; border-radius: 10px 10px 10px 10px"><i class="fa fa-users"></i> Pasien Lama</a>
                &nbsp;&nbsp;
                <a href="#" onclick="getMenu('Kiosk/antrian_front')" class="btn btn-lg" style="background: green !important; border-color: green; height: 70px; font-size: 2.8em; border-radius: 10px 10px 10px 10px"><i class="fa fa-list"></i> No Antrian</a>
                &nbsp;&nbsp;
                <a href="#" onclick="getMenu('Kiosk/spesialis_front')" class="btn btn-lg" style="background: green !important; border-color: green; height: 70px; font-size: 2.8em; border-radius: 10px 10px 10px 10px"><i class="fa fa-calendar"></i> Jadwal Dokter</a>
              </div>
            </div>

					</div><!-- /.page-content -->
				</div>
			</div><!-- /.main-content -->

			<div class="footer">
				<div class="footer-inner">
					<div class="footer-content">
						<span class="bigger-120">
							<span class="blue bolder">RSSM</span>
							SHS 4.0 &copy; 2018-<?php echo date('Y')?>
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
    <script>
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
        function getDateToday(){
          var today = new Date();
          var dd = String(today.getDate()).padStart(2, '0');
          var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
          var yyyy = today.getFullYear();

          today = dd + '/' + mm + '/' + yyyy;
          return today;
        }

        function getLiburNasional(year){

          if(year == 2022){
              var dataLiburNasional = ["1-1-2022","1-2-2022", "28-2-2022","3-3-2022","15-4-2022","1-5-2022","2-5-2022","3-5-2022","16-5-2022","26-5-2022","1-6-2022","9-7-2022","30-7-2022","17-8-2022","8-10-2022","25-12-2022"];
          }

          return dataLiburNasional;

        }

        function preventDefault(e) {
          e = e || window.event;
          if (e.preventDefault)
              e.preventDefault();
          e.returnValue = false;  
        }

        function reprint(link){
        preventDefault();
        $.ajax({
            url: link,
            dataType: "json",
            data: {},
            type: "POST",
            success: function (response) {
              // no action
              console.log(response);
            }
        });
      }

        
    </script>
</body>
</html>
