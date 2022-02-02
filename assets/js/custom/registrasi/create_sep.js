$(document).ready(function () {

      $.getJSON("ws_bpjs/Ws_index/getRef?ref=RefKelasRawat", '', function (data) {
                $('#select_option option').remove();
                $('<option value="">-Silahkan Pilih-</option>').appendTo($('#select_option'));
                $.each(data, function (i, o) {
                    $('<option value="' + o.kode + '">' + o.nama + '</option>').appendTo($('#select_option'));
                });

      });

      $('#inputKeyFaskes').typeahead({
          source: function (query, result) {
              $.ajax({
                  url: "ws_bpjs/Ws_index/getRef?ref=RefFaskes",
                  data: { keyword:query,jf:$('input[name=jenis_faskes]:checked').val() },            
                  dataType: "json",
                  type: "POST",
                  success: function (response) {
                    result($.map(response, function (item) {
                        return item;
                    }));
                  }
              });
          },
          afterSelect: function (item) {
            // do what is needed with item
            var val_item=item.split(':')[0];
            console.log(val_item);
            $('#kodeFaskesHidden').val(val_item);
          }
      });

      $('#InputKeydokterDPJP').typeahead({
          source: function (query, result) {
              $.ajax({
                  url: "ws_bpjs/Ws_index/getRef?ref=RefDokterDPJP",
                  data: { spesialis:$('#kodePoliHidden').val(),jp:$('input[name=jnsPelayanan]:checked').val(),tgl:$('#tglSEP').val() },            
                  dataType: "json",
                  type: "POST",
                  success: function (response) {
                    result($.map(response, function (item) {
                        return item;
                    }));
                  }
              });
          },
          afterSelect: function (item) {
            // do what is needed with item
            var val_item=item.split(':')[0];
            console.log(val_item);
            $('#KodedokterDPJP').val(val_item);
          }
      });

      $('input[name=jnsPelayanan]').click(function(e){
        var field = $('input[name=jnsPelayanan]:checked').val();
        if ( field == '1' ) {
          $('#selectKelasRawatInap').show('fast');
        }else if (field == '2') {
          $('#selectKelasRawatInap').hide('fast');
        }
      });

      $('input[name=penjaminKLL]').click(function(e){
        var field = $('input[name=penjaminKLL]:checked').val();
        if ( field == '1' ) {
          $('#showFormPenjaminKLL').show('fast');
        }else if (field == '0') {
          $('#showFormPenjaminKLL').hide('fast');
        }
      });

      $('#btnSearchMember').click(function (e) {

          $('#showFormPenjaminKLL').hide('fast');
          
          e.preventDefault();
          var field = $('input[name=find_member_by]:checked').val();
          if ( field == 'noKartu' ) {
            var jenis_kartu = 'bpjs';
            var nokartu = $('#noKartu').val();
          }else if (field == 'nik') {
            var jenis_kartu = 'nik';
            var nokartu = $('#noNik').val();
          }

          e.preventDefault();
          $.ajax({
            url: 'ws_bpjs/ws_index/searchMember',
            type: "post",
            data: {nokartu:nokartu,jenis_kartu:jenis_kartu,tglSEP:$('#tglSEP').val()},
            dataType: "json",
            beforeSend: function() {
              achtungShowLoader();  
            },
            success: function(data) {
              achtungHideLoader();
              if(data.status==200){
                
                $('#showResultData').show('fast');
                $('#formDetailInsertSEP').show('fast');

                $('#tglKunjungan').removeAttr('readonly');
                $('#noRujukan').removeAttr('readonly');

                $('#noKartuHidden').val(data.result.noKartu);
                $('#noMR').val(data.result.noMR);

                
                $('#noKartuFromNik').text(data.result.noKartu);
                $('#nama').text(data.result.nama);
                $('#noMR').text(data.result.noMR);
                $('#nik').text(data.result.nik);
                $('#tglLahir').text(data.result.tglLahir);
                $('#umur_p_bpjs').text(data.result.umur);
                $('#hakKelas').text(data.result.hakKelas);
                $('#jenisPeserta').text(data.result.jenisPeserta);
                $('#statusPeserta').text(data.result.statusPeserta);
                $('#inputKeyFaskes').val(data.result.ppkAsalRujukan);
                $('#kodeFaskesHidden').val(data.result.kodePpkAsalRujukanHidden);
              }else{
                $.achtung({message: data.message, timeout:5});
              }
              
            }
          });

      });

      $('#btnSearchRujukan').click(function (e) {
          e.preventDefault();
          var jenis_faskes = $('input[name=jenis_faskes]:checked').val();
          var jenis_pencarian = $('#find_member_by').val();
          var inputValue = $('#keyvalue').val();

          e.preventDefault();
          $.ajax({
            url: 'ws_bpjs/ws_index/searchRujukan',
            type: "post",
            data: {flag: jenis_pencarian, keyvalue: inputValue, jenis_faskes: jenis_faskes},
            dataType: "json",
            beforeSend: function() {
              achtungShowLoader();  
            },
            success: function(data) {
              achtungHideLoader();

              if(data.status==200){

                var rujukan = data.result.rujukan;
                var peserta = data.result.peserta;
                var diagnosa = data.result.diagnosa;
                var pelayanan = data.result.pelayanan;
                var poliRujukan = data.result.poliRujukan;
                var provPerujuk = data.result.provPerujuk;

                /*show hidden*/
                $('#showFormPenjaminKLL').hide('fast');
                $('#showResultData').show('fast');
                $('#formDetailInsertSEP').show('fast');

                /*text*/
                $('#noKartuFromNik').text(peserta.noKartu);
                $('#nama').text(peserta.nama);
                $('#nik').text(peserta.nik);
                $('#tglLahir').text(peserta.tglLahir);
                $('#umur_p_bpjs').text(peserta.umur.umurSekarang);
                $('#jenisPeserta').text(peserta.jenisPeserta.keterangan);
                $('#hakKelas').text(peserta.hakKelas.keterangan);
                $('#statusPeserta').text(peserta.statusPeserta.keterangan);

                /*form*/
                $('#noKartuHidden').val(peserta.noKartu);
                $('#noMR').val(peserta.mr.noMR);
                $('#inputKeyPoli').val(poliRujukan.nama);
                $('#kodePoliHidden').val(poliRujukan.kode);
                $('#inputKeyFaskes').val(provPerujuk.nama);
                $('#kodeFaskesHidden').val(provPerujuk.kode);
                $('#noRujukan').val(rujukan.noKunjungan);
                $('#tglKunjungan').val(rujukan.tglKunjungan);
                $('#inputKeyDiagnosa').val(diagnosa.nama);
                $('#kodeDiagnosaHidden').val(diagnosa.kode);
                $('#noTelp').val(peserta.mr.noTelepon);
                $('#catatan').val(rujukan.keluhan);

                /*reset form*/
                // $('#InputKeydokterDPJP').val('');
                // $('#KodedokterDPJP').val('');

                /*show dokter DPJP*/
                $.getJSON("ws_bpjs/Ws_index/getRef?ref=GetRefDokterDPJPRandom&spesialis="+$('#kodePoliHidden').val()+"&jp="+$('input[name=jnsPelayanan]:checked').val()+"&tgl="+$('#tglSEP').val()+"", '' , function (row) {
                    $('#KodedokterDPJP').val(row.kode);
                    $('#InputKeydokterDPJP').val(row.nama.toUpperCase());    
                    // $('#show_dpjp').val(row.nama.toUpperCase());    

                });
                
                $("input[name=jnsPelayanan][value="+pelayanan.kode+"]").attr('checked', true);

              }else{
                $('#showFormPenjaminKLL').hide('fast');
                $('#showResultData').hide('fast');
                $('#formDetailInsertSEP').hide('fast');
                $.achtung({message: data.message, timeout:5, className: 'achtungFail'});
              }
              
            }
          });

      });

      $('#btnSearchSep').click(function (e) {
          e.preventDefault();
          var noSEP = $('#noSEPVal').val();

          e.preventDefault();
          $.ajax({
            url: 'ws_bpjs/ws_index/searchSep',
            type: "post",
            data: {sep:noSEP},
            dataType: "json",
            beforeSend: function() {
              achtungShowLoader();  
            },
            success: function(data) {
              achtungHideLoader();
              if(data.status==200){

                var value = data.result.value;
                var peserta = data.result.peserta;

                /*show hidden*/
                $('#showFormPenjaminKLL').hide('fast');
                $('#showResultData').show('fast');
                $('#formDetailInsertSEP').show('fast');

                /*text*/
                $('#noKartuFromNik').text(peserta.noKartu);
                $('#nama').text(peserta.nama);
                $('#nik').text(peserta.nik);
                $('#tglLahir').text(peserta.tglLahir);
                $('#umur_p_bpjs').text(peserta.umur.umurSekarang);
                $('#jenisPeserta').text(peserta.jenisPeserta.keterangan);
                $('#hakKelas').text(peserta.hakKelas.keterangan);
                $('#statusPeserta').text(peserta.statusPeserta.keterangan);

                /*form*/
                $('#tglSEP').val(value.tglSep);
                $('#noKartuHidden').val(peserta.noKartu);
                $('#noMR').val(peserta.mr.noMR);
                $('#inputKeyPoli').val(value.nama);
                $('#kodePoliHidden').val(value.kodePoli);
                $('#inputKeyFaskes').val(peserta.provUmum.nmProvider);
                $('#kodeFaskesHidden').val(peserta.provUmum.kdProvider);
                $('#noSuratSKDP').val(value.noSuratSKDP);
                $('#InputKeydokterDPJP').val(value.namaDokterDPJP);
                $('#KodedokterDPJP').val(value.KodedokterDPJP);
                $('#noRujukan').val(value.noRujukan);
                $('#tglKunjungan').val(value.tglRujukan);
                $('#inputKeyDiagnosa').val(value.diagnosa);
                $('#kodeDiagnosaHidden').val(value.kodeDiagnosa);
                $('#noTelp').val(peserta.mr.noTelepon);
                $('#catatan').val(value.catatan);
                $('#prosesId').val('update');

                $("input[name=jnsPelayanan][value="+value.kodeJnsPelayanan+"]").attr('checked', true);
                $("input[name=jenis_faskes][value="+value.asalRujukan+"]").attr('checked', true);

              }else{
                $.achtung({message: data.message, timeout:5});
              }
              
            }
          });

      });


      $('#inputKeyDiagnosa').typeahead({
          source: function (query, result) {
              $.ajax({
                  url: "ws_bpjs/Ws_index/getRef?ref=RefDiagnosa",
        data: 'keyword=' + query,            
                  dataType: "json",
                  type: "POST",
                  success: function (response) {
                    result($.map(response, function (item) {
                        return item;
                    }));
                  }
              });
          },
          afterSelect: function (item) {
            // do what is needed with item
            var val_item=item.split(':')[0];
            console.log(val_item);
            $('#kodeDiagnosaHidden').val(val_item);
          }
      });

      $('#inputKeyPoli').typeahead({
          source: function (query, result) {
              $.ajax({
                  url: "ws_bpjs/Ws_index/getRef?ref=RefPoli",
                  data: 'keyword=' + query,            
                  dataType: "json",
                  type: "POST",
                  success: function (response) {
                    result($.map(response, function (item) {
                        return item;
                    }));
                  }
              });
          },
          afterSelect: function (item) {
            // do what is needed with item
            var val_item=item.split(':')[0];
            var strValue = $.trim(val_item.toString());
            console.log(strValue);
            $('#kodePoliHidden').val(strValue);
            if( strValue == 'IGD' ){
              $('#formRujukan').hide('fast');
              $('#inputKeyDiagnosa').focus();
            }else{
              $('#formRujukan').show('fast');
            }
          }
      });

      $('#formInsertSep').ajaxForm({
        beforeSend: function() {
          achtungShowLoader();  
        },
        uploadProgress: function(event, position, total, percentComplete) {
        },
        complete: function(xhr) {     
          var data=xhr.responseText;
          var jsonResponse = JSON.parse(data);

          if(jsonResponse.status == 200){

            $.achtung({message: jsonResponse.message, timeout:5});
            
            $('#noSep').val(jsonResponse.no_sep);
            getMenu('ws_bpjs/ws_index?modWs=InsertSEP');
            /*load sep untuk di print*/
            show_modal_medium("ws_bpjs/ws_index/view_sep/"+jsonResponse.no_sep+"", 'SURAT ELEGIBILITAS PASIEN (SEP)');

          }else{
            $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
          }
          achtungHideLoader();
        }
      }); 

      $( "#noNik" )
          .keypress(function(event) {
            var keycode =(event.keyCode?event.keyCode:event.which); 
            if(keycode ==13){
              event.preventDefault();
              if($(this).valid()){
                $('.btnSearchMember').focus();
              }
              return false;       
          }
      });

      $( "#keyvalue" )
        .keypress(function(event) {
          var keycode =(event.keyCode?event.keyCode:event.which); 
          if(keycode ==13){
            event.preventDefault();
            if($(this).valid()){
              $('#btnSearchRujukan').click();
            }
            return false;       
          }
      });

      $('select[name="provinceId"]').change(function () {
        if ($(this).val()) {
            $.getJSON("Templates/References/getRegencyByProvince/" + $(this).val(), '', function (data) {
                $('#regencyId option').remove();
                $('<option value="">-Pilih Kab/Kota-</option>').appendTo($('#regencyId'));
                $.each(data, function (i, o) {
                    $('<option value="' + o.id + '">' + o.name + '</option>').appendTo($('#regencyId'));
                });

            });
        } else {
            $('#regencyId option').remove()
        }
      });

      $('select[name="regencyId"]').change(function () {
          if ($(this).val()) {
              $.getJSON("Templates/References/getDistrictByRegency/" + $(this).val(), '', function (data) {
                  $('#districtId option').remove();
                  $('<option value="">-Pilih Kecamatan-</option>').appendTo($('#districtId'));
                  $.each(data, function (i, o) {
                      $('<option value="' + o.id + '">' + o.name + '</option>').appendTo($('#districtId'));
                  });

              });
          } else {
              $('#regencyId option').remove()
          }
      });

      $('.date-picker').datepicker({
        autoclose: true,
        todayHighlight: true,
        dateFormat: 'yyyy-MM-dd'
      })
      //show datepicker when clicking on the icon
      .next().on(ace.click_event, function(){
        $(this).prev().focus();
      });

});

