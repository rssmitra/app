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