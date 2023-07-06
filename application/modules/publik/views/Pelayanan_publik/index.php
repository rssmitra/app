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
    
      <div id="page-area-content" style="padding: 25px !important;">
        <div class="row">
          <div class="col-xs-12">
            <div class="center" style="padding:10px 10px 10px 10px;">
              <img class="center" src="<?php echo base_url().COMP_ICON; ?>" width="150px">
            </div>
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
          </div>
        </div>
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
    
    

    <script type="text/javascript">
      
      $('.format_number').number( true, 2 );

      function changeTotalBiaya(field, id){
        //alert(field); return false;
        /*harga * diskon*/
        var harga_awal = $('#'+field+'_'+id).val();
        var input_persen = $('#diskon_'+field+'_'+id).val();

        // if bill dr 
        if( field == 'bill_dr1' || field == 'bill_dr2' || field == 'bill_dr3'){
          var result_bill_dr = (parseInt(harga_awal) * input_persen/100) * (70/100);
          var result_pendapatan_rs = (parseInt(result_bill_dr) * input_persen/100) * (30/100);

          // last price dookter
          var last_price = parseInt(result_bill_dr) + parseInt(harga_awal);
          // last price pendapatan rs
          var harga_awal_pendapatan = $('#pendapatan_rs_'+id).val();
          var pendapatan_rs = parseInt(harga_awal_pendapatan) + parseInt(result_pendapatan_rs);
          // console.log(harga_awal_pendapatan);
          // console.log(last_price);
          // console.log(pendapatan_rs);
          $('#total_diskon_pendapatan_rs_'+id+'').val( pendapatan_rs );
          $('#pendapatan_rs_'+id+'').val( pendapatan_rs );
          format_pendapatan_rs = formatMoney(pendapatan_rs);
          $('#text_total_diskon_pendapatan_rs_'+id).text( format_pendapatan_rs );

        }else{
            kenaikan_tarif = harga_awal * input_persen/100;
            var last_price = parseInt(harga_awal) + parseInt(kenaikan_tarif);
        }
      
        $('#total_diskon_'+field+'_'+id).val( last_price );
        var formatTxt = formatMoney(last_price);
        $('#text_total_diskon_'+field+'_'+id).text( formatTxt );

        /*sum class name*/
        sum = sumClass('total_diskon_'+id+'');
        sumFormat = formatMoney(sum);
        $('#total_biaya_'+id+'').text( sumFormat );

        console.log(field+'|'+id+'|'+harga_awal);

      }
      
      function show_modal(url, title){  

          preventDefault();
          
          $('#text_title').text(title);

          $('#global_modal_content_detail').load(url); 

          $("#globalModalView").modal();
          
      }

      function show_modal_with_iframe(url, title){  

        preventDefault();

        $('#text_title_iframe').text(title);

        $('#content_iframe').attr('src', url); 

        $("#globalModalViewWithiFrame").modal();

      }

      function show_modal_medium(url, title){  

        preventDefault();

        $('#text_title_medium').text(title);

        $('#global_modal_content_detail_medium').load(url); 

        $("#globalModalViewMedium").modal();

      }

      function show_modal_small(url, title){  

        preventDefault();

        $('#text_title_small').text(title);

        $('#global_modal_content_detail_small').load(url); 

        $("#globalModalViewSmall").modal();

      }

      function show_modal_medium_return_json(url, title){  

        preventDefault();

        $.getJSON(url, '' , function (data) {
          $('#global_modal_content_detail_medium').html(data.html);
        })
        
        $('#text_title_medium').text(title);

        $("#globalModalViewMedium").modal();

      }

      function PopupCenter(url, title, w, h) {
          // Fixes dual-screen position                         Most browsers      Firefox
          var dualScreenLeft = window.screenLeft != undefined ? window.screenLeft : window.screenX;
          var dualScreenTop = window.screenTop != undefined ? window.screenTop : window.screenY;

          var width = window.innerWidth ? window.innerWidth : document.documentElement.clientWidth ? document.documentElement.clientWidth : screen.width;
          var height = window.innerHeight ? window.innerHeight : document.documentElement.clientHeight ? document.documentElement.clientHeight : screen.height;

          var left = ((width / 2) - (w / 2)) + dualScreenLeft;
          var top = ((height / 2) - (h / 2)) + dualScreenTop;
          var newWindow = window.open(url, title, 'scrollbars=yes, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

          // Puts focus on the newWindow
          if (window.focus) {
              newWindow.focus();
          }

          /*custom hide after show popup*/
          $('#modalCetakTracer').modal('hide');
      }

      function copyToClipboard(text) {
        var selected = false;
        var el = document.createElement('textarea');
        el.value = text;
        el.setAttribute('readonly', '');
        el.style.position = 'absolute';
        el.style.left = '-9999px';
        document.body.appendChild(el);
        if (document.getSelection().rangeCount > 0) {
            selected = document.getSelection().getRangeAt(0)
        }
        el.select();
        document.execCommand('copy');
        document.body.removeChild(el);
        if (selected) {
            document.getSelection().removeAllRanges();
            document.getSelection().addRange(selected);
        }
      }

      function preventDefault(e) {
        e = e || window.event;
        if (e.preventDefault)
            e.preventDefault();
        e.returnValue = false;  
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

      function getAge(paramsDate, style) {

        var dateString = getFormattedDate(paramsDate);

        var now = new Date();
        var today = new Date(now.getYear(),now.getMonth(),now.getDate());

        var yearNow = now.getYear();
        var monthNow = now.getMonth();
        var dateNow = now.getDate();

        var dob = new Date(dateString.substring(6,10),
                           dateString.substring(0,2)-1,                   
                           dateString.substring(3,5)                  
                           );

        var yearDob = dob.getYear();
        var monthDob = dob.getMonth();
        var dateDob = dob.getDate();
        var age = {};
        var ageString = "";
        var yearString = "";
        var monthString = "";
        var dayString = "";


        yearAge = yearNow - yearDob;

        if (monthNow >= monthDob)
          var monthAge = monthNow - monthDob;
        else {
          yearAge--;
          var monthAge = 12 + monthNow -monthDob;
        }

        if (dateNow >= dateDob)
          var dateAge = dateNow - dateDob;
        else {
          monthAge--;
          var dateAge = 31 + dateNow - dateDob;

          if (monthAge < 0) {
            monthAge = 11;
            yearAge--;
          }
        }

        age = {
            years: yearAge,
            months: monthAge,
            days: dateAge
            };

        if ( age.years > 1 ) yearString = " thn";
        else yearString = " thn";
        if ( age.months> 1 ) monthString = " bln";
        else monthString = " bln";
        if ( age.days > 1 ) dayString = " hr";
        else dayString = " hr";


        if ( (age.years > 0) && (age.months > 0) && (age.days > 0) )
          ageString = age.years + yearString + ", " + age.months + monthString + ", " + age.days + dayString + " ";
        else if ( (age.years == 0) && (age.months == 0) && (age.days > 0) )
          ageString = "" + age.days + dayString + " ";
        else if ( (age.years > 0) && (age.months == 0) && (age.days == 0) )
          ageString = age.years + yearString + " Happy Birthday!!";
        else if ( (age.years > 0) && (age.months > 0) && (age.days == 0) )
          ageString = age.years + yearString + ",  " + age.months + monthString + " ";
        else if ( (age.years == 0) && (age.months > 0) && (age.days > 0) )
          ageString = age.months + monthString + ", " + age.days + dayString + " ";
        else if ( (age.years > 0) && (age.months == 0) && (age.days > 0) )
          ageString = age.years + yearString + ", " + age.days + dayString + " ";
        else if ( (age.years == 0) && (age.months > 0) && (age.days == 0) )
          ageString = age.months + monthString + " ";
        else ageString = "Oops! Could not calculate age!";

        if(style==1){
          return ageString;
        }else{
          return age.years;
        }

      }

      function getFormattedDate(paramsDate) {
          var date = new Date(paramsDate);
          let year = date.getFullYear();
          let month = (1 + date.getMonth()).toString().padStart(2, '0');
          let day = date.getDate().toString().padStart(2, '0');        
          return day + '/' + month + '/' + year;
      }

      function getDateToday(){
        var today = new Date();
        var dd = String(today.getDate()).padStart(2, '0');
        var mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
        var yyyy = today.getFullYear();

        today = dd + '/' + mm + '/' + yyyy;
        return today;
      }

      function getFormatSqlDate(paramsDate) {
          var date = new Date(paramsDate);
          let year = date.getFullYear();
          let month = (1 + date.getMonth()).toString().padStart(2, '0');
          let day = date.getDate().toString().padStart(2, '0');        
          return year + '-' + month + '-' + date;
      }

      function changeDiscount(field, id){
        //alert(field); return false;
        /*harga * diskon*/
        var harga_awal = $('#'+field+'_'+id).val();
        var discount = $('#diskon_'+field+'_'+id).val();
        /*modulus*/
        
        if(discount > 100){

          var modulus = discount % 100;
          disc = harga_awal * modulus/100;
          console.log(disc);
          var last_price = parseInt(harga_awal) + parseInt(disc);

        }else{
          disc = harga_awal * discount/100;
          var last_price = harga_awal - disc;
        }

        $('#total_diskon_'+field+'_'+id).val( last_price );
        format = formatMoney(last_price);
        $('#text_total_diskon_'+field+'_'+id).text( format );
        /*sum class name*/
        sum = sumClass('total_diskon_'+id+'');
        sumFormat = formatMoney(sum);
        $('#total_biaya_'+id+'').text( sumFormat );
      }

      function formatMoney(number){
        money = new Intl.NumberFormat().format(number);
        format = '' +money+ '';
        return format;
      }

      function sumClass(classname){

        var sum = 0;

        $("."+classname).each(function() {
            var val = $.trim( $(this).val() );
            
            if ( val ) {
                val = parseFloat( val.replace( /^\$/, "" ) );
            
                sum += !isNaN( val ) ? val : 0;
            }
        });


        return sum;
      }

      function checkAll(elm) {

        if($(elm).prop("checked") == true){
          $('table .ace').each(function(){
              $('table .ace').prop("checked", true);
          });
        }else{
          $('table .ace').prop("checked", false);
        }

      }

      function submitUpdateTransaksi(kode_trans_pelayanan){

        preventDefault();
        achtungShowLoader();
        $.ajax({
            url: "pelayanan/Pl_pelayanan/updateBilling?kode="+kode_trans_pelayanan+"",
            data: $('#form_update_billing_'+kode_trans_pelayanan+'').serialize(),            
            dataType: "json",
            type: "POST",
            complete: function (xhr) {
              var data=xhr.responseText;  
              var jsonResponse = JSON.parse(data);  
              if(jsonResponse.status === 200){  
                $.achtung({message: jsonResponse.message, timeout:5});
                reset_table();
              }else{          
                $.achtung({message: jsonResponse.message, timeout:5});  
              } 
              achtungHideLoader();
            }
        });

      }

      function format_currency(div_id){
        $('#'+div_id+'').number(true, 0);
      }

      function formatNumberFromCurrency(yourNumber) {
          //Seperates the components of the number
          var components = yourNumber.toString().split(/[ .:;?!~,`"&|()<>{}\[\]\r\n/\\]+/);
          //Comma-fies the first part
          components [0] = components [0].replace(/\B(?=(\d{3})+(?!\d))/g, "");
          //Combines the two sections
          return components.join("");
      }

      function getLiburNasional(year){

        if(year == 2023){
            var dataLiburNasional = ["1-1-2023", "22-1-2023", "18-2-2023", "22-3-2023", "7-4-2023", "22-4-2023", "23-4-2023", "1-5-2023", "18-5-2023", "1-6-2023", "4-6-2023", "29-6-2023","19-7-2023","17-8-2023","28-9-2023", "25-12-2023"];
        }

        return dataLiburNasional;

      }

      function syntaxHighlight(json) {
        json = json.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
        return json.replace(/("(\\u[a-zA-Z0-9]{4}|\\[^u]|[^\\"])*"(\s*:)?|\b(true|false|null)\b|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?)/g, function (match) {
            var cls = 'number';
            if (/^"/.test(match)) {
                if (/:$/.test(match)) {
                    cls = 'key';
                } else {
                    cls = 'string';
                }
            } else if (/true|false/.test(match)) {
                cls = 'boolean';
            } else if (/null/.test(match)) {
                cls = 'null';
            }
            return '<span class="' + cls + '">' + match + '</span>';
        });
      }

      function reprint(jd_id, id_tc_pesanan, printerName){
        preventDefault();
        $.ajax({
            url: "registration/Reg_pasien/print_booking/"+jd_id+"/"+id_tc_pesanan+"",
            dataType: "json",
            data: {printer : printerName},
            type: "POST",
            success: function (response) {
              // no action
              console.log(response);
            }
        });
      }

      function reprintSEP(no_sep, no_antrian, printerName){
        preventDefault();
        $.ajax({
            url: "ws_bpjs/Ws_index/print_sep/"+no_sep+"",
            dataType: "json",
            data: {printer : printerName, no_antrian : no_antrian},
            type: "POST",
            success: function (response) {
              // no action
              console.log(response);
            }
        });
      }
      function duplicateFieldValue(fieldId, valueId){
        $('#'+valueId+'').val($('#'+fieldId+'').val());
      }

      function startStopWatch(){
        // $('#startCount').attr({'disabled':'disbaled'});
        // $('#pauseCount').removeAttr("disabled");
        // $('#resetCount').removeAttr("disabled");
        
        $.post('ws/AntrianOnline/updateTask', {kodebooking : $('#kode_perjanjian').val(), taskId : $('#taskId').val() },
          function(response){
            console.log(response);
          }
        );

        minutessetInterval = setInterval(function () {
            minutesCount += 1
            minutes.innerHTML = minutesCount
        }, 60000)

        secondsetInterval = setInterval(function () {
            secondCount += 1
            if(secondCount > 59){
                secondCount = 1
            }
            second.innerHTML = secondCount
        }, 1000)

        centiSecondsetInterval = setInterval(function () {
            centiSecondCount += 1
            if(centiSecondCount > 99){
                centiSecondCount = 1
            }
            centiSecond.innerHTML = centiSecondCount
        }, 10)
    }

    function pauseStopWatch(){
        // $('#pauseCount').attr({'disabled':'disbaled'});
        // $('#startCount').removeAttr("disabled");
        clearInterval(minutessetInterval)
        clearInterval(secondsetInterval)
        clearInterval(centiSecondsetInterval)
    }

    function resetStopWatch(){
        // $('#pauseCount').attr({'disabled':'disbaled'});
        // $('#resetCount').attr({'disabled':'disbaled'});
        // $('#startCount').removeAttr("disabled");

        clearInterval(minutessetInterval)
        clearInterval(secondsetInterval)
        clearInterval(centiSecondsetInterval)

        minutesCount = 0; secondCount = 0; centiSecondCount = 0;
        minutes.innerHTML = minutesCount;
        second.innerHTML = secondCount;
        centiSecond .innerHTML = centiSecondCount;

    }


    </script>

  </body>
</html>