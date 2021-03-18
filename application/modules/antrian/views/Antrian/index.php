<!DOCTYPE html>
<html lang="en">
<head>

	<meta charset="utf-8">

	<title><?php echo COMP_LONG?></title>
	<meta http-equiv="X-UA-Compatible" content="IE=Edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="keywords" content="">
	<meta name="description" content="">
	
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

	<!-- keyboar on screen -->
	<!-- keyboard widget css & script -->
	<link href="<?php echo base_url()?>assets/Keyboard-master/css/keyboard-dark.css" rel="stylesheet">
	<script src="<?php echo base_url()?>assets/Keyboard-master/js/jquery.keyboard.js"></script>

	<!-- css for the preview keyset extension -->
	<link href="<?php echo base_url()?>assets/Keyboard-master/css/keyboard-previewkeyset.css" rel="stylesheet">

	<!-- keyboard optional extensions - include ALL (includes mousewheel) -->
	<script src="<?php echo base_url()?>assets/Keyboard-master/js/jquery.keyboard.extension-all.js"></script>

	<!-- achtung loader -->
	<link href="<?php echo base_url()?>assets/achtung/ui.achtung-mins.css" rel="stylesheet" type="text/css" />
    <script type="text/javascript" src="<?php echo base_url()?>assets/achtung/ui.achtung-min.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>assets/achtung/achtung.js"></script> 

	<style>
      

	  .blink_me {
		animation: blinker 1s linear infinite;
		}

		@keyframes blinker {
		50% {
			opacity: 0;
		}
		}

	  @font-face { font-family: MyriadPro; src: url('assets/fonts/MyriadPro-Bold.otf'); } 

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

			$.post("antrian/Loket/reload_page", { loket:loket, tipe:type } ).done( function(data) {
			
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
<body id="body-antrian">



<!-- home section -->
<section id="home">

	<br>

	<div class="col-xs-6">
		<img alt="" src="<?php echo COMP_ICON?>" width="60" style="margin:5px 20px;float:left">
			<h3 id="id_header" style="margin:0;text-align:left;font-size:30px;color:#333"><?php echo COMP_LONG?></h3>
			<p style="font-family: Helvetica;margin:0;text-align:left"><b><?php echo COMP_ADDRESS?></b></p>
	</div>

	<div class="col-xs-6" id="myButtonType" style="left:16%">
		<botton class="btn btn-sm btn-primary" data-wow-delay="0.8s" id="btn_non_bpjs" style="border-radius:10px;text-decoration:none;"><h3 style="font-size: 2rem;margin:20px">NON BPJS</h3></botton>
			<botton class="btn btn-sm btn-primary" data-wow-delay="1.0s" id="btn_bpjs" style="border-radius:10px;text-decoration:none;"><h3 style="font-size: 2rem;margin:20px">&nbsp; BPJS &nbsp; </h3></botton>
			<botton onclick="online()" href="#" class="btn btn-sm btn-primary" data-wow-delay="1.0s" style="border-radius:10px;text-decoration:none;"><h3 style="font-size: 2rem;margin:20px">ONLINE</h3></botton>

			<input type="hidden" name="tipe_antrian" id="tipe_antrian" value="bpjs">
	</div>
	<div class="row" style="margin-top:5% !important" >
	<h2 class="center animate" style="font-size:250%"><b id="title_tipe_antrian">ANTRIAN PASIEN BPJS</b></h2>
		<div id="refresh2">  
			<div class="col-xs-12" id="loket_refresh">
				<?php 
					
					$arr_color = array('yellow','lime','orange','fuchsia','lightgray','lightblue','lightgrey','cyan','aqua','khaki','lightpink','wheat'); 
					/*$arr_color = array('yellow','olive','lime','orange','fuchsia','lightgray','lightblue'); */
					shuffle($arr_color);

				?>
				<div class="row">
				<?php foreach($klinik as $row_modul) : ?>
					<div class="col-lg-2 col-xs-2" style="margin-top:0px;height:170px;">
					<!-- small box -->
					<button onclick="add_antrian_poli(<?php echo $row_modul->jd_kode_dokter ?>,'<?php echo $row_modul->nama_pegawai?>','<?php echo $row_modul->jd_kode_spesialis ?>','<?php echo $row_modul->nama_bagian?>','<?php echo $row_modul->jd_hari ?>','<?php echo $this->tanggal->formatTime($row_modul->jd_jam_mulai) ?>','<?php echo $row_modul->jd_jam_selesai ?>',<?php echo $row_modul->kuota ?>)" class="shadow" style="border:none;text-decoration: none;border-radius:10px;margin-bottom:20px;height:150px !important;width:100%;text-align:left;padding-bottom:20px;background:<?php echo array_shift($arr_color)?>;">
						<div class="inner" style="margin-top:-10px">
						<h3 style="font-size:14px;color:black;"><b><?php echo ucwords($row_modul->nama_bagian)?></b></h3>
						<p style="font-size:12px;color:black;">
							<?php echo $row_modul->nama_pegawai?><br>
							<?php echo $this->tanggal->formatTime($row_modul->jd_jam_mulai).' s/d '.$this->tanggal->formatTime($row_modul->jd_jam_selesai)?><br>
							<?php if($type!='online'){ ?> <b>Sisa Kuota : <?php echo $row_modul->kuota.'</b>'; }?><br>
							<?php echo isset($row_modul->jd_keterangan)?$row_modul->jd_keterangan:''?> <?php echo isset($row_modul->keterangan)?$row_modul->keterangan:''?> 
						</p>
						</div> 
							
						<input type="hidden" id="kode_dokter" val="<?php echo $row_modul->jd_kode_dokter ?>">
						<input type="hidden" id="kode_spesialis" val="<?php echo $row_modul->jd_kode_spesialis ?>">
					</button>
					</div>
				<?php endforeach; ?>
				
				<div class="col-lg-2 col-xs-2" style="margin-top:0px;height:180px;">
					<button onclick="add_other()" class="shadow" style="border:none;text-decoration: none;border-radius:10px;margin-bottom:20px;height:150px !important;width:100%;text-align:left;padding-bottom:20px;background:<?php echo array_shift($arr_color)?>;">
					<div class="inner" style="margin-top:-10px">
						<h3 style="font-size:16px;color:black;"><b>Antrian Lainnya</b></h3>
						<p style="font-size:12px;color:black;">
						Pendaftaran pasien untuk Penunjang Medis, IGD, Rawat Inap, dan lainnya.
						</p>
					</div>                      
					</button>
				</div>

				</div>
			</div><!-- /.col -->
		</div>
    </div>
            

	<div class="row" style="width:100%;top:87%;text-align:right;margin-right:20px;">
		<div id="refresh"><h3 id="time" style="margin:0;color:#333;font-size:26px;"><?php date_default_timezone_set("Asia/Jakarta"); echo date('H') ?><span class="blink_me">:</span><?php echo date('i') ?></h3></div>
		<p style="margin:0;font-size:26px;color:#333;"><?php date_default_timezone_set("Asia/Jakarta"); echo date('d/m/Y') ?></p>
	</div>
			
</section>

<!-- MODAL -->

<div id="modalAntrian" class="modal fade" tabindex="-1">

<div class="modal-dialog" style="max-height:90%;  margin-top: 15%; margin-bottom:50px;width:50%">

  <div class="modal-content">

    <div class="modal-header no-padding">

      <div class="table-header" style="text-align:center">

        <h3 style="margin:0 !important;font-size:28px;">Masukan Kode Booking Anda</h3>
    
      </div>

    </div>
    

    <div class="modal-body" style="text-align:left;">

		<div style="margin-left:32%"><p style="display:inline;">Kode Booking :</p><input type="text" id="kode_booking" name="kode_booking" class="keyboard-init-focus" style="width:30%"/></div>

		<br><a onclick="verifbooking()" href="#" class="btn btn-lg btn-primary" style="margin-left:50%">
			Submit 
		</a>

    </div>

    <!-- <div class="modal-footer no-margin-top">

      <button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">

        <i class="ace-icon fa fa-times"></i>

        Close

      </button>

    </div> -->

  </div><!-- /.modal-content -->

</div><!-- /.modal-dialog -->

</div>

<script type="text/javascript">
	$('#btn_non_bpjs').click(function(){
		$('#title_tipe_antrian').text('ANTRIAN PASIEN NON BPJS ATAU UMUM');
		$('#tipe_antrian').val('umum');
	});
	$('#btn_bpjs').click(function(){
		$('#title_tipe_antrian').text('ANTRIAN PASIEN BPJS');
		$('#tipe_antrian').val('bpjs');
	});

</script>

<script>
	
	setInterval("my_function();",3000); 

	function my_function(){
		$('#refresh').load(location.href + ' #time');
		$('#refresh2').load(location.href + ' #loket_refresh');
	}

	function online() {
		$("#modalAntrian").modal();  
	}

	function verifbooking() {
		
		if($('#kode_booking').val()==''){
			achtungCreate("<h3 style='text-align:center;'>Silahkan Isi form yang tersedia</h3>",false);
			return false;
		} else {
			data = [];
			data[0] = 'online';
			data[1] = $('#kode_booking').val();
			$.ajax({
				url:"<?php echo base_url(); ?>antrian/process",
				data:{data:data}, 
				dataType: "json", 
				type:"POST",       
				success:function (data) {
					console.log(data['status'])
					if(data['status']==200){
						$('#kode_booking').val('');
						$("#modalAntrian").modal('hide');  
						//window.location.href = "<?php echo base_url()?>antrian/loket?type=online";
					}else if(data['status']!=200){
						achtungCreate("<h3 style='text-align:center;'>"+data['message']+"</h3>",false);
						//$('#email').val('');
						$('#kode_booking').val('');
					}
				}
				
			
			});
		}
	}

	$('#email').keyboard({

		// set this to ISO 639-1 language code to override language set by the layout
		// http://en.wikipedia.org/wiki/List_of_ISO_639-1_codes
		// language defaults to "en" if not found
		language     : null,  // string or array
		rtl          : false, // language direction right-to-left

		// *** choose layout ***
		layout       : 'qwerty',
		customLayout : { 'normal': ['{cancel}'] },

		position : {
		// optional - null (attach to input/textarea) or a jQuery object
		// (attach elsewhere)
		of : null,
		my : 'center top',
		at : 'center top',
		// used when "usePreview" is false
		// (centers keyboard at bottom of the input/textarea)
		at2: 'center bottom'
		},

		// allow jQuery position utility to reposition the keyboard on window resize
		reposition : true,

		// preview added above keyboard if true, original input/textarea used if false
		// always disabled for contenteditable elements
		usePreview : true,

		// if true, the keyboard will always be visible
		alwaysOpen : false,

		// give the preview initial focus when the keyboard becomes visible
		initialFocus : false,
		// Avoid focusing the input the keyboard is attached to
		noFocus : false,

		// if true, keyboard will remain open even if the input loses focus.
		stayOpen : false,

		// Prevents the keyboard from closing when the user clicks or
		// presses outside the keyboard. The `autoAccept` option must
		// also be set to true when this option is true or changes are lost
		userClosed : false,

		// if true, keyboard will not close if you press escape.
		ignoreEsc : false,

		// if true, keyboard will only closed on click event instead of mousedown or
		// touchstart. The user can scroll the page without closing the keyboard.
		closeByClickEvent : false,

		// *** change keyboard language & look ***
		display : {
		// \u2714 = check mark - same action as accept
		'a'      : '\u2714:Accept (Shift-Enter)',
		'accept' : 'Accept:Accept (Shift-Enter)',
		'alt'    : 'AltGr:Alternate Graphemes',
		// \u232b = outlined left arrow with x inside
		'b'      : '\u232b:Backspace',
		'bksp'   : 'Bksp:Backspace',
		// \u2716 = big X, close - same action as cancel
		'c'      : '\u2716:Cancel (Esc)',
		'cancel' : 'Cancel:Cancel (Esc)',
		// clear num pad
		'clear'  : 'C:Clear',
		'combo'  : '\u00f6:Toggle Combo Keys',
		// decimal point for num pad (optional);
		// change '.' to ',' for European format
		'dec'    : '.:Decimal',
		// down, then left arrow - enter symbol
		'e'      : '\u21b5:Enter',
		'empty'  : '\u00a0', // &nbsp;
		'enter'  : 'Enter:Enter',
		// \u2190 = left arrow (move caret)
		'left'   : '\u2190',
		// caps lock
		'lock'   : '\u21ea Lock:Caps Lock',
		'next'   : 'Next',
		'prev'   : 'Prev',
		// \u2192 = right arrow (move caret)
		'right'  : '\u2192',
		// \u21e7 = thick hollow up arrow
		's'      : '\u21e7:Shift',
		'shift'  : 'Shift:Shift',
		// \u00b1 = +/- sign for num pad
		'sign'   : '\u00b1:Change Sign',
		'space'  : '&nbsp;:Space',

		// \u21e5 = right arrow to bar; used since this virtual
		// keyboard works with one directional tabs
		't'      : '\u21e5:Tab',
		// \u21b9 is the true tab symbol (left & right arrows)
		'tab'    : '\u21e5 Tab:Tab',
		// replaced by an image
		'toggle' : ' ',

		// added to titles of keys
		// accept key status when acceptValid:true
		'valid': 'valid',
		'invalid': 'invalid',
		// combo key states
		'active': 'active',
		'disabled': 'disabled'
		},

		// Message added to the key title while hovering, if the mousewheel plugin exists
		wheelMessage : 'Use mousewheel to see other keys',

		css : {
		// input & preview
		input          : 'ui-widget-content ui-corner-all',
		// keyboard container
		container      : 'ui-widget-content ui-widget ui-corner-all ui-helper-clearfix',
		// keyboard container extra class (same as container, but separate)
		popup: '',
		// default state
		buttonDefault  : 'ui-state-default ui-corner-all',
		// hovered button
		buttonHover    : 'ui-state-hover',
		// Action keys (e.g. Accept, Cancel, Tab, etc); replaces "actionClass"
		buttonAction   : 'ui-state-active',
		// used when disabling the decimal button {dec}
		buttonDisabled : 'ui-state-disabled',
		// empty button class name {empty}
		buttonEmpty    : 'ui-keyboard-empty'
		},

		// *** Useability ***
		// Auto-accept content when clicking outside the keyboard (popup will close)
		autoAccept : false,
		// Auto-accept content even if the user presses escape
		// (only works if `autoAccept` is `true`)
		autoAcceptOnEsc : false,

		// Prevents direct input in the preview window when true
		lockInput : false,

		// Prevent keys not in the displayed keyboard from being typed in
		restrictInput : false,
		// Additional allowed characters while restrictInput is true
		restrictInclude : '', // e.g. 'a b foo \ud83d\ude38'

		// Check input against validate function, if valid the accept button
		// is clickable; if invalid, the accept button is disabled.
		acceptValid : true,
		// Auto-accept when input is valid; requires `acceptValid`
		// set `true` & validate callback
		autoAcceptOnValid : false,

		// if acceptValid is true & the validate function returns a false, this option
		// will cancel a keyboard close only after the accept button is pressed
		cancelClose : true,

		// Use tab to navigate between input fields
		tabNavigation : false,

		// press enter (shift-enter in textarea) to go to the next input field
		enterNavigation : true,
		// mod key options: 'ctrlKey', 'shiftKey', 'altKey', 'metaKey' (MAC only)
		// alt-enter to go to previous; shift-alt-enter to accept & go to previous
		enterMod : 'altKey',

		// if true, the next button will stop on the last keyboard input/textarea;
		// prev button stops at first
		// if false, the next button will wrap to target the first input/textarea;
		// prev will go to the last
		stopAtEnd : true,

		// Set this to append the keyboard immediately after the input/textarea it
		// is attached to. This option works best when the input container doesn't
		// have a set width and when the "tabNavigation" option is true
		appendLocally : false,

		// Append the keyboard to a desired element. This can be a jQuery selector
		// string or object
		appendTo : 'body',

		// If false, the shift key will remain active until the next key is (mouse)
		// clicked on; if true it will stay active until pressed again
		stickyShift : true,

		// caret placed at the end of any text when keyboard becomes visible
		caretToEnd : false,

		// Prevent pasting content into the area
		preventPaste : false,

		// caret stays this many pixels from the edge of the input
		// while scrolling left/right; use "c" or "center" to center
		// the caret while scrolling
		scrollAdjustment : 10,

		// Set the max number of characters allowed in the input, setting it to
		// false disables this option
		maxLength : false,

		// allow inserting characters @ caret when maxLength is set
		maxInsert : true,

		// Mouse repeat delay - when clicking/touching a virtual keyboard key, after
		// this delay the key will start repeating
		repeatDelay : 500,

		// Mouse repeat rate - after the repeatDelay, this is the rate (characters
		// per second) at which the key is repeated. Added to simulate holding down
		// a real keyboard key and having it repeat. I haven't calculated the upper
		// limit of this rate, but it is limited to how fast the javascript can
		// process the keys. And for me, in Firefox, it's around 20.
		repeatRate : 20,

		// resets the keyboard to the default keyset when visible
		resetDefault : false,

		// Event (namespaced) on the input to reveal the keyboard. To disable it,
		// just set it to an empty string ''.
		openOn : 'focus',

		// When the character is added to the input
		keyBinding : 'mousedown touchstart',

		// enable/disable mousewheel functionality
		// enabling still depends on the mousewheel plugin
		useWheel : true,

		// combos (emulate dead keys)
		// http://en.wikipedia.org/wiki/Keyboard_layout#US-International
		// if user inputs `a the script converts it to à, ^o becomes ô, etc.
		useCombos : true,

		// *** Methods ***
		// Callbacks - add code inside any of these callback functions as desired
		initialized   : function(e, keyboard, el) {},
		beforeVisible : function(e, keyboard, el) {},
		visible       : function(e, keyboard, el) {},
		beforeInsert  : function(e, keyboard, el, textToAdd) { return textToAdd; },
		change        : function(e, keyboard, el) {},
		beforeClose   : function(e, keyboard, el, accepted) {},
		accepted      : function(e, keyboard, el) {},
		canceled      : function(e, keyboard, el) {},
		restricted    : function(e, keyboard, el) {},
		hidden        : function(e, keyboard, el) {},

		// called instead of base.switchInput
		switchInput : function(keyboard, goToNext, isAccepted) {},

		// used if you want to create a custom layout or modify the built-in keyboard
		create : function(keyboard) { return keyboard.buildKeyboard(); },

		// build key callback (individual keys)
		buildKey : function( keyboard, data ) {
		/*
		data = {
			// READ ONLY
			// true if key is an action key
			isAction : [boolean],
			// key class name suffix ( prefix = 'ui-keyboard-' ); may include
			// decimal ascii value of character
			name     : [string],
			// text inserted (non-action keys)
			value    : [string],
			// title attribute of key
			title    : [string],
			// keyaction name
			action   : [string],
			// HTML of the key; it includes a <span> wrapping the text
			html     : [string],
			// jQuery selector of key which is already appended to keyboard
			// use to modify key HTML
			$key     : [object]
		}
		*/
		return data;
		},

		// this callback is called just before the "beforeClose" to check the value
		// if the value is valid, return true and the keyboard will continue as it
		// should (close if not always open, etc)
		// if the value is not value, return false and the clear the keyboard value
		// ( like this "keyboard.$preview.val('');" ), if desired
		// The validate function is called after each input, the "isClosing" value
		// will be false; when the accept button is clicked, "isClosing" is true
		validate : function(keyboard, value, isClosing) {
		return true;
		}

	});

	$('#kode_booking').keyboard({

		// set this to ISO 639-1 language code to override language set by the layout
		// http://en.wikipedia.org/wiki/List_of_ISO_639-1_codes
		// language defaults to "en" if not found
		language     : null,  // string or array
		rtl          : false, // language direction right-to-left

		// *** choose layout ***
		layout       : 'qwerty',
		customLayout : { 'normal': ['{cancel}'] },

		position : {
		// optional - null (attach to input/textarea) or a jQuery object
		// (attach elsewhere)
		of : null,
		my : 'center top',
		at : 'center top',
		// used when "usePreview" is false
		// (centers keyboard at bottom of the input/textarea)
		at2: 'center bottom'
		},

		// allow jQuery position utility to reposition the keyboard on window resize
		reposition : true,

		// preview added above keyboard if true, original input/textarea used if false
		// always disabled for contenteditable elements
		usePreview : true,

		// if true, the keyboard will always be visible
		alwaysOpen : false,

		// give the preview initial focus when the keyboard becomes visible
		initialFocus : false,
		// Avoid focusing the input the keyboard is attached to
		noFocus : false,

		// if true, keyboard will remain open even if the input loses focus.
		stayOpen : false,

		// Prevents the keyboard from closing when the user clicks or
		// presses outside the keyboard. The `autoAccept` option must
		// also be set to true when this option is true or changes are lost
		userClosed : false,

		// if true, keyboard will not close if you press escape.
		ignoreEsc : false,

		// if true, keyboard will only closed on click event instead of mousedown or
		// touchstart. The user can scroll the page without closing the keyboard.
		closeByClickEvent : false,

		// *** change keyboard language & look ***
		display : {
		// \u2714 = check mark - same action as accept
		'a'      : '\u2714:Accept (Shift-Enter)',
		'accept' : 'Accept:Accept (Shift-Enter)',
		'alt'    : 'AltGr:Alternate Graphemes',
		// \u232b = outlined left arrow with x inside
		'b'      : '\u232b:Backspace',
		'bksp'   : 'Bksp:Backspace',
		// \u2716 = big X, close - same action as cancel
		'c'      : '\u2716:Cancel (Esc)',
		'cancel' : 'Cancel:Cancel (Esc)',
		// clear num pad
		'clear'  : 'C:Clear',
		'combo'  : '\u00f6:Toggle Combo Keys',
		// decimal point for num pad (optional);
		// change '.' to ',' for European format
		'dec'    : '.:Decimal',
		// down, then left arrow - enter symbol
		'e'      : '\u21b5:Enter',
		'empty'  : '\u00a0', // &nbsp;
		'enter'  : 'Enter:Enter',
		// \u2190 = left arrow (move caret)
		'left'   : '\u2190',
		// caps lock
		'lock'   : '\u21ea Lock:Caps Lock',
		'next'   : 'Next',
		'prev'   : 'Prev',
		// \u2192 = right arrow (move caret)
		'right'  : '\u2192',
		// \u21e7 = thick hollow up arrow
		's'      : '\u21e7:Shift',
		'shift'  : 'Shift:Shift',
		// \u00b1 = +/- sign for num pad
		'sign'   : '\u00b1:Change Sign',
		'space'  : '&nbsp;:Space',

		// \u21e5 = right arrow to bar; used since this virtual
		// keyboard works with one directional tabs
		't'      : '\u21e5:Tab',
		// \u21b9 is the true tab symbol (left & right arrows)
		'tab'    : '\u21e5 Tab:Tab',
		// replaced by an image
		'toggle' : ' ',

		// added to titles of keys
		// accept key status when acceptValid:true
		'valid': 'valid',
		'invalid': 'invalid',
		// combo key states
		'active': 'active',
		'disabled': 'disabled'
		},

		// Message added to the key title while hovering, if the mousewheel plugin exists
		wheelMessage : 'Use mousewheel to see other keys',

		css : {
		// input & preview
		input          : 'ui-widget-content ui-corner-all',
		// keyboard container
		container      : 'ui-widget-content ui-widget ui-corner-all ui-helper-clearfix',
		// keyboard container extra class (same as container, but separate)
		popup: '',
		// default state
		buttonDefault  : 'ui-state-default ui-corner-all',
		// hovered button
		buttonHover    : 'ui-state-hover',
		// Action keys (e.g. Accept, Cancel, Tab, etc); replaces "actionClass"
		buttonAction   : 'ui-state-active',
		// used when disabling the decimal button {dec}
		buttonDisabled : 'ui-state-disabled',
		// empty button class name {empty}
		buttonEmpty    : 'ui-keyboard-empty'
		},

		// *** Useability ***
		// Auto-accept content when clicking outside the keyboard (popup will close)
		autoAccept : false,
		// Auto-accept content even if the user presses escape
		// (only works if `autoAccept` is `true`)
		autoAcceptOnEsc : false,

		// Prevents direct input in the preview window when true
		lockInput : false,

		// Prevent keys not in the displayed keyboard from being typed in
		restrictInput : false,
		// Additional allowed characters while restrictInput is true
		restrictInclude : '', // e.g. 'a b foo \ud83d\ude38'

		// Check input against validate function, if valid the accept button
		// is clickable; if invalid, the accept button is disabled.
		acceptValid : true,
		// Auto-accept when input is valid; requires `acceptValid`
		// set `true` & validate callback
		autoAcceptOnValid : false,

		// if acceptValid is true & the validate function returns a false, this option
		// will cancel a keyboard close only after the accept button is pressed
		cancelClose : true,

		// Use tab to navigate between input fields
		tabNavigation : false,

		// press enter (shift-enter in textarea) to go to the next input field
		enterNavigation : true,
		// mod key options: 'ctrlKey', 'shiftKey', 'altKey', 'metaKey' (MAC only)
		// alt-enter to go to previous; shift-alt-enter to accept & go to previous
		enterMod : 'altKey',

		// if true, the next button will stop on the last keyboard input/textarea;
		// prev button stops at first
		// if false, the next button will wrap to target the first input/textarea;
		// prev will go to the last
		stopAtEnd : true,

		// Set this to append the keyboard immediately after the input/textarea it
		// is attached to. This option works best when the input container doesn't
		// have a set width and when the "tabNavigation" option is true
		appendLocally : false,

		// Append the keyboard to a desired element. This can be a jQuery selector
		// string or object
		appendTo : 'body',

		// If false, the shift key will remain active until the next key is (mouse)
		// clicked on; if true it will stay active until pressed again
		stickyShift : true,

		// caret placed at the end of any text when keyboard becomes visible
		caretToEnd : false,

		// Prevent pasting content into the area
		preventPaste : false,

		// caret stays this many pixels from the edge of the input
		// while scrolling left/right; use "c" or "center" to center
		// the caret while scrolling
		scrollAdjustment : 10,

		// Set the max number of characters allowed in the input, setting it to
		// false disables this option
		maxLength : false,

		// allow inserting characters @ caret when maxLength is set
		maxInsert : true,

		// Mouse repeat delay - when clicking/touching a virtual keyboard key, after
		// this delay the key will start repeating
		repeatDelay : 500,

		// Mouse repeat rate - after the repeatDelay, this is the rate (characters
		// per second) at which the key is repeated. Added to simulate holding down
		// a real keyboard key and having it repeat. I haven't calculated the upper
		// limit of this rate, but it is limited to how fast the javascript can
		// process the keys. And for me, in Firefox, it's around 20.
		repeatRate : 20,

		// resets the keyboard to the default keyset when visible
		resetDefault : false,

		// Event (namespaced) on the input to reveal the keyboard. To disable it,
		// just set it to an empty string ''.
		openOn : 'focus',

		// When the character is added to the input
		keyBinding : 'mousedown touchstart',

		// enable/disable mousewheel functionality
		// enabling still depends on the mousewheel plugin
		useWheel : true,

		// combos (emulate dead keys)
		// http://en.wikipedia.org/wiki/Keyboard_layout#US-International
		// if user inputs `a the script converts it to à, ^o becomes ô, etc.
		useCombos : true,

		// *** Methods ***
		// Callbacks - add code inside any of these callback functions as desired
		initialized   : function(e, keyboard, el) {},
		beforeVisible : function(e, keyboard, el) {},
		visible       : function(e, keyboard, el) {},
		beforeInsert  : function(e, keyboard, el, textToAdd) { return textToAdd; },
		change        : function(e, keyboard, el) {},
		beforeClose   : function(e, keyboard, el, accepted) {},
		accepted      : function(e, keyboard, el) {},
		canceled      : function(e, keyboard, el) {},
		restricted    : function(e, keyboard, el) {},
		hidden        : function(e, keyboard, el) {},

		// called instead of base.switchInput
		switchInput : function(keyboard, goToNext, isAccepted) {},

		// used if you want to create a custom layout or modify the built-in keyboard
		create : function(keyboard) { return keyboard.buildKeyboard(); },

		// build key callback (individual keys)
		buildKey : function( keyboard, data ) {
		/*
		data = {
			// READ ONLY
			// true if key is an action key
			isAction : [boolean],
			// key class name suffix ( prefix = 'ui-keyboard-' ); may include
			// decimal ascii value of character
			name     : [string],
			// text inserted (non-action keys)
			value    : [string],
			// title attribute of key
			title    : [string],
			// keyaction name
			action   : [string],
			// HTML of the key; it includes a <span> wrapping the text
			html     : [string],
			// jQuery selector of key which is already appended to keyboard
			// use to modify key HTML
			$key     : [object]
		}
		*/
		return data;
		},

		// this callback is called just before the "beforeClose" to check the value
		// if the value is valid, return true and the keyboard will continue as it
		// should (close if not always open, etc)
		// if the value is not value, return false and the clear the keyboard value
		// ( like this "keyboard.$preview.val('');" ), if desired
		// The validate function is called after each input, the "isClosing" value
		// will be false; when the accept button is clicked, "isClosing" is true
		validate : function(keyboard, value, isClosing) {
		return true;
		}

	});

</script>

<script>
   
	function add_antrian_poli(dokter,nama_dokter,spesialis,nama_spesialis,hari,jam_mulai,jam_selesai,kuota) {
		/* console.log(dokter);
		console.log(spesialis); */
		var dataString = $('#tipe_antrian').val(); 
		if((kuota>0) || (dataString=='online')){
		
		data = [];
		data[0] = dataString;
		data[1] = dokter;
		data[2] = nama_dokter;
		data[3] = spesialis;
		data[4] = nama_spesialis;
		data[5] = hari;
		data[6] = jam_mulai;
		data[7] = jam_selesai;
		console.log(data)
			$.ajax({
				url:"<?php echo base_url(); ?>antrian/loket/process",
				data:{data:data}, 
				dataType: "json", 
				type:"POST",       
				success:function (data) {
				//console.log(data)
				
				no = pad(data['no'], 3);

				$('#klinik_modal').text(data['klinik']);
				$('#dokter_modal').text(data['dokter']);
				$('#no_modal').text(no);

				//$("#modalAntrian").modal();
				
				//window.location.href = "<?php echo base_url(); ?>antrian";

				//   openWin(no,data['klinik'],data['dokter'],data['type'],data['jam_praktek']);
				//setTimeout(function () { window.location.href = "<?php echo base_url(); ?>antrian"; }, 2000);
				}
		});
		event.preventDefault();
		}else{
		$("#modalAntrianPenuh").modal();  

		//setTimeout(function () { window.location.href = "<?php echo base_url(); ?>antrian"; }, 2000);
		}
		
	}

	function add_other() {
		/* console.log(dokter);
		console.log(spesialis); */
		var dataString = $('#tipe_antrian').val(); 
		$.ajax({
			url:"<?php echo base_url(); ?>antrian/loket/process_other",
			data:{type: dataString}, 
			dataType: "json", 
			type:"POST",       
			success:function (data) {
				//console.log(data)
			
				no = pad(data['no'], 3);

				$('#klinik_modal').text(data['klinik']);
				$('#dokter_modal').text(data['dokter']);
				$('#no_modal').text(no);

			}
		});

		event.preventDefault();
		
	}

   	function pad (str, max) {
      str = str.toString();
      return str.length < max ? pad("0" + str, max) : str;
    }

    function openWin(n,klinik,dokter,type,jam_praktek) {
		date = new Date;
		year = date.getFullYear();
		month = date.getMonth();
		months = new Array('Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Augustus', 'September', 'Oktober', 'November', 'Desember');
		short_months = new Array('Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Aug', 'Sep', 'Okt', 'Nov', 'Des');
		d = date.getDate();
		day = date.getDay();
		days = new Array('Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu');
		h = date.getHours();
		if(h<10)
		{
				h = "0"+h;
		}
		m = date.getMinutes();
		if(m<10)
		{
				m = "0"+m;
		}
		s = date.getSeconds();
		if(s<10)
		{
				s = "0"+s;
		}
		result = ''+days[day]+', '+d+' '+months[month]+' '+year+', '+h+':'+m+':'+s;
		current_date = ''+days[day]+', '+d+'/'+short_months[month]+'/'+year;
	
	
		myWindow = window.open("", "myWindow", "width=2,height=1");

		if(type=='bpjs'){
		var type_antrian = 'A';
		var text_title = 'BPJS';
		}else{
		var type_antrian = 'B';
		var text_title = 'UMUM';
		}

		var html = 
			'<div style="font-family: calibri" class="center">\
				<center>\
				<table align="center" border="0" width="100%">\
				<tr>\
				<td colspan="2" align="center"><span style="font-size:150% !important"><?php echo COMP_LONG; ?></span><br><small style="font-size:9px !important"><?php echo COMP_ADDRESS; ?></small><hr></td>\
				</tr>\
				<tr>\
				<td align="center" colspan="2"><span style="font-size:11px;margin-top:0">PENDAFTARAN PASIEN '+text_title+'</span><br><span style="font-size:300%;"> '+type_antrian+' '+n+' <small style="font-size:10px !important;margin-top:0"><br>Nomor Antrian</small><br><span style="font-size:20% !important;margin-top:0"><br>'+klinik.toUpperCase()+'<br>'+dokter+'<br>'+current_date+', '+jam_praktek+'</span> </td>\
				</tr>\
				</table>\
				<table align="center" width="100%">\
				<tr style="font-size:11px;">\
				<td><br><br></td>\
				</tr>\
			';

		myWindow.document.write(html);

		
		myWindow.print();
		myWindow.close();
    }


  </script>

</body>
</html>


