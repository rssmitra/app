<!DOCTYPE html>
<html lang="en">
<head>
	<title><?php echo COMP_LONG?></title>
	
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/bootstrap.css" />
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/font-awesome.css" />
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace-fonts.css" />
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/ace.css" class="ace-main-stylesheet" id="main-ace-style" />
    <link rel="stylesheet" href="<?php echo base_url()?>assets/css/AdminLTE.css" class="ace-main-stylesheet" id="main-ace-style" />
	<!-- javscript js -->
	<script src="<?php echo base_url()?>assets/js/js_/jquery.js"></script> 
	<script src="<?php echo base_url()?>assets/js/js_/bootstrap.min.js"></script>

	<script src="<?php echo base_url()?>assets/js/js_/vegas.min.js"></script>

	<script src="<?php echo base_url()?>assets/js/js_/wow.min.js"></script>
	<script src="<?php echo base_url()?>assets/js/js_/smoothscroll.js"></script>
	<script src="<?php echo base_url()?>assets/js/js_/custom.js"></script>



	<!-- stylesheets css -->
	<link rel="stylesheet" href="<?php echo base_url()?>assets/css/css_/animate.min.css" />


  	<link rel="stylesheet" href="<?php echo base_url()?>assets/css/css_/vegas.min.css" />

	<!-- achtung loader -->
	<link href="<?php echo base_url()?>assets/achtung/ui.achtung-mins.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="<?php echo base_url()?>assets/achtung/ui.achtung-min.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>assets/achtung/achtung.js"></script> 

	<style>
      
		body{
			padding: 0px 10px 0px 10px !important
		}

		.blink_me {
			animation: blinker 1s linear infinite;
		}
		

	  @font-face { font-family: MyriadPro; src: url('<?php echo base_url()?>assets/fonts/MyriadPro-Bold.otf'); } 

		#id_header {
		font-family: MyriadPro;
		}

		input{
			-webkit-appearance: none;
			width: 50%;
			border:0;
			font-family: inherit;
			padding: 10px 0;
			margin:5px 0;
			height: 48px;
			font-size: 16px;
			font-weight: 500;
			border-bottom: 2px solid #C8CCD4;
			background: none;
			border-radius: 0;
			text-align:center;
		}

		.ui-keyboard input{
			color:black;
		}

		body {
          /* background-color: #ffffff; */
          background: url('<?php echo base_url()?>assets/images/download.png') fixed;
          position: relative;
          margin: 0;
          padding: 0px 20px 24px;
          background-position: center;
          background-repeat: no-repeat;
          background-size: cover;
          min-height: 510px;
      }

		
  </style>

	<style type="text/css">
		.active, .btn:hover {
		background-color: #666 !important;
		color: white;
		}
	</style>
  	<script>
		setInterval("update_antrian();",15000); 
		function update_antrian() {

			var loket = $('#select_loket').val();

			var type = $('#select_tipe').val();

			$.post("<?php echo base_url()?>antrian/Loket/reload_page", { loket:loket, tipe:type } ).done( function(data) {
			
			var obj = JSON.parse(data);
			console.log(obj)
			if(obj.success==1){
				$('#message_loket').hide('fast');
				$('#loket_hidden').val( obj.loket);
				$('#label_loket').text( obj.loket);

				$('#tipe_hidden').val( obj.tipe);
				$('#label_tipe').text( obj.tipe_loket.toUpperCase());
				
				format_no = pad(obj.counter, 3);

				console.log(format_no)

				$('#counter_number').text( obj.tipe+''+format_no );
				$('#counter_number_value').val( obj.counter );
				/*info antrian*/
				$('#from_num').text(obj.counter);

				if(type=='bpjs'){
				$('#to_num').text(obj.total_bpjs);
				}else{
				$('#to_num').text(obj.total_non_bpjs);
				}

				$('#total_bpjs').text(obj.total_bpjs);
				$('#sisa_antrian_bpjs').text(obj.sisa_bpjs);

				$('#total_non_bpjs').text(obj.total_non_bpjs);
				$('#sisa_antrian_non_bpjs').text(obj.sisa_non_bpjs);

			}else{
				$('#message_loket').show('fast');
				$('#message_loket').html('<span style="color:red"><i>'+obj.message+'</i></span>');          
			}
			
			});

		}
		// Add active class to the current button (highlight it)
		var header = document.getElementById("myButtonType");
		var btns = header.getElementsByClassName("btn");
		for (var i = 0; i < btns.length; i++) {
		btns[i].addEventListener("click", function() {
			var current = document.getElementsByClassName("active");
			if (current.length > 0) { 
				current[0].className = current[0].className.replace(" active", "");
			}
			this.className += " active";
		});
		}
	</script>

</head>

<body id="body-poli">

	<div class="row">
		<div class="col-xs-12">
			<center><h2><b>ANTRIAN POLI/KLINIK RAWAT JALAN</b><br>TANGGAL <?php echo $this->tanggal->formatDateDmy(date('Y-m-d'))?> <span id="time"><?php date_default_timezone_set("Asia/Jakarta"); echo date('H') ?><span class="blink_me">:</span><?php echo date('i') ?></span></h2></center>
		</div>
	</div>
	<br>
	<div class="row">
		<div class="col-xs-12">
			<?php for($i=1; $i<7; $i++) :?>
			<div class="col-xs-4 center" style="padding-bottom: 20px">
				<table class="table">
					<tr style="background: lightblue">
						<td colspan="2" style="height: 50%"><span style="font-weight: bold; font-size: 120%">KLINIK SPESIALIS JANTUNG DAN PEMBULUH DARAH</span></td>
					</tr>
					<tr>
						<td style="height: 150px; width: 50%;background-color: darkseagreen">
							<b><span style="font-size: 250%">Muhammad Amin Lubis</span></b><br>
							<span>Sedang berlangsung</span>
						</td>
						<td style="height: 150px; width: 50%; background-color: gold ">
							<b><span style="font-size: 150%">Hengky Zulkarnain</span></b><br>
							<span>Antrian berikutnya</span>
						</td>
					</tr>
					<tr style="width: 100%; background-color: darksalmon ">
						<td colspan="2">
							<span>Bersiap diruang tunggu</span><br>
							<span style="font-weight: bold; font-size: 100%">Sinta - Uci - Zaenal - Ocin</span>
						</td>
					</tr>
				</table>
			</div>
			<?php endfor;?>
		</div>
	</div>

</body>

<script>
	
	setInterval("my_function();",3000); 

	function my_function(){
		$('#refresh').load(location.href + ' #time');
		$('#refresh2').load(location.href + ' #loket_refresh');
	}

</script>

</html>


