<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>

<script>

$(document).ready(function () {

  $.getJSON("../ws_bpjs/Ws_index/getRef?ref=RefKelasRawat", '', function (data) {
            $('#select_option option').remove();
            $('<option value="">-Silahkan Pilih-</option>').appendTo($('#select_option'));
            $.each(data, function (i, o) {
                $('<option value="' + o.kode + '">' + o.nama + '</option>').appendTo($('#select_option'));
            });

  });

  $('#inputKeyFaskes').typeahead({
      source: function (query, result) {
          $.ajax({
              url: "../ws_bpjs/Ws_index/getRef?ref=RefFaskes",
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
              url: "../ws_bpjs/Ws_index/getRef?ref=RefDokterDPJP",
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


  $('input[name=find_member_by]').click(function(e){
    var field = $('input[name=find_member_by]:checked').val();
    $('#showResultData').hide('fast');
    $('#formDetailInsertSEP').hide('fast');

    if ( field == 'noKartu' ) {
      $('#searchByNoKartu').show('fast');
      $('#searchBySEP').hide('fast');
      $('#showResultData').hide('fast');
      $('#noRujukanField').hide('fast');
      $('#byJenisFaskesId').show('fast');

    }else if (field == 'sep') {
      $('#searchByNoKartu').hide('fast');
      $('#searchBySEP').show('fast');
      $('#showResultData').hide('fast');
      $('#noRujukanField').hide('fast');
      $('#byJenisFaskesId').hide('fast');
      
    }else if (field == 'noRujukan') {
      $('#searchByNoKartu').hide('fast');
      $('#searchBySEP').hide('fast');
      $('#showResultData').hide('fast');
      $('#noRujukanField').show('fast');
      $('#byJenisFaskesId').show('fast');
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
        url: '../ws_bpjs/ws_index/searchMember',
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

  $('#btnSearchNoRujukan').click(function (e) {
      e.preventDefault();
      var field = $('input[name=find_member_by]:checked').val();
      var jenis_faskes = $('input[name=jenis_faskes]:checked').val();
      var flag = $('input[name=find_member_by]:checked').val();
      var noRujukan = $('#noRujukanVal').val();

      e.preventDefault();
      $.ajax({
        url: '../ws_bpjs/ws_index/searchRujukan',
        type: "post",
        data: {flag:flag,noRujukan:noRujukan,jenis_faskes:jenis_faskes},
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
            $('#result-dt-rujukan').show('fast');
            $('#showFormPenjaminKLL').hide('fast');
            $('#showResultData').show('fast');
            $('#formDetailInsertSEP').show('fast');

            /*text*/
            $('#noKartuFromNik').text(peserta.noKartu);
            $('#nama').text(peserta.nama);
            $('#user').val(peserta.nama);
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

            /*show dokter DPJP*/
            $.getJSON("../ws_bpjs/Ws_index/getRef?ref=GetRefDokterDPJPRandom", { spesialis:$('#kodePoliHidden').val(),jp:$('input[name=jnsPelayanan]:checked').val(),tgl:$('#tglSEP').val() }, function (row) {
                  $('#KodedokterDPJP').val(row.kode);
                  $('#InputKeydokterDPJP').val(row.nama.toUpperCase());    
                  $('#show_dpjp').val(row.nama.toUpperCase());    

            });

            $("input[name=jnsPelayanan][value="+pelayanan.kode+"]").attr('checked', true);

          }else{
            $.achtung({message: data.message, timeout:5});
          }
          
        }
      });

  });

  $('#btnSearchSep').click(function (e) {
      e.preventDefault();
      var noSEP = $('#noSEPVal').val();

      e.preventDefault();
      $.ajax({
        url: '../ws_bpjs/ws_index/searchSep',
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
              url: "../ws_bpjs/Ws_index/getRef?ref=RefDiagnosa",
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
              url: "../ws_bpjs/Ws_index/getRef?ref=RefPoli",
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
          $('#page-area-content').load(jsonResponse.redirect);
          $('#noSep').val(jsonResponse.no_sep);
          /*load sep untuk di print*/
          // window.open("../ws_bpjs/Ws_index/view_sep/"+jsonResponse.result+"", '_blank');
          

      }else{
          // $.achtung({message: jsonResponse.message, timeout:5});
          $('#message-result').html('<div class="alert alert-danger">'+jsonResponse.message+'</div>');
      }

      $("html, body").animate({ scrollTop: "1250px" });
      $('#show_sep').load("../ws_bpjs/Ws_index/view_sep/0112R0340621V003929?PPKPerujuk="+jsonResponse.perujuk+"&DPJP="+jsonResponse.dpjp+"");

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

  $( "#noKartu" )
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

  $('#btnSearchKodeBooking').click(function (e) {
      e.preventDefault();
      var kodeBooking = $('#kodeBooking').val();

      e.preventDefault();
      $.ajax({
        url: '../Templates/References/findKodeBooking',
        type: "post",
        data: {kode:kodeBooking},
        dataType: "json",
        beforeSend: function() {
          achtungShowLoader();  
        },
        success: function(response) {
          achtungHideLoader();
          if(response.status==200){

            var no_mr = response.data.no_mr;

            /*show hidden*/
            $('#resultSearchKodeBooking').show('fast');

            /*text*/
            $('#kb_no_mr').text(response.data.no_mr);
            $('#kb_nama_pasien').text(response.data.nama);
            $('#kb_tgl_kunjungan').text(response.data.tgl_kunjungan);
            $('#kb_poli_tujuan').text(response.data.poli);
            $('#kb_dokter').text(response.data.nama_dr);
            $('#kb_jam_praktek').text(response.data.jam_praktek);

            $('#noSuratSKDP').val(kodeBooking);
          //   $('#noMR').val(response.data.no_mr);

          }else{
            $.achtung({message: data.message, timeout:5});
          }
          
        }
      });

  });


});

jQuery(function($) {

    $('.date-picker').datepicker({
        autoclose: true,
        todayHighlight: true,
        dateFormat: 'yyyy-MM-dd'
    }).next().on(ace.click_event, function(){
        $(this).prev().focus();
    });

});

function show_form_rujukan(){
    $("html, body").animate({ scrollTop: "600px" });
    $('#form-search-rujukan').show('fast');
}

</script>

<style>
    .list-group-item{
        padding: 10px !important;
    }
    .infobox-small > .infobox-data{
        min-width: 100%;
    }

    .infobox-small {
        width: 100%;
        height: 30px;
        text-align: left;
        padding-bottom: 10px;
    }

    .infobox .infobox-content {
        max-width: 100% !important;
        color: grey;
        font-weight: bold;
        font-size: 18px
    }

    .infobox-green.infobox-dark {
        /* background-color: linen; */
        background: linear-gradient(0deg, #7cc70fe0, #c7f35db8);
        border-color: #9abc32;
    }

    .profile-info-name{
      background: white;
    }

    

    
</style>

<form class="form-horizontal" method="post" id="formInsertSep" action="<?php echo base_url().'ws_bpjs/ws_index/insertSep'?>" enctype="Application/x-www-form-urlencoded" autocomplete="off">   
  
      <div id="form-search-perjanjian" style="margin-top: 5%">
        <div class="row">
          <div class="col-md-2">&nbsp;</div>
          <div class="col-md-8">
              <div class="row">
                  <div id="searchKodeBooking">
                      <label style="font-size: 25px; font-weight: bold">MASUKAN KODE BOOKING ATAU KODE PERJANJIAN</label>
                      <div class="input-group" style="width: 100%">
                          <input type="text" class="form-control" style="height: 80px !important;font-size: 55px;text-transform: uppercase; text-align: left" id="kodeBooking" name="kodeBooking">
                          <span class="input-group-btn">
                          <button class="btn btn-sm btn-primary" type="button" id="btnSearchKodeBooking" style="height: 80px !important; font-size: 50px">
                              &nbsp;<i class="fa fa-angle-double-right bigger-150"></i>&nbsp;
                          </button>
                          </span>
                      <div>
                  </div>
              </div>

              <div class="row">
                <div id="resultSearchKodeBooking" style="padding-left: 12px; padding-top:20px; font-size: 14px">
                  <div class="profile-user-info profile-user-info-striped" style="background: white">
                    <div class="profile-info-row">
                      <div class="profile-info-name"> No. Rekam Medis </div>

                      <div class="profile-info-value">
                        <span class="editable editable-click" id="kb_no_mr"></span>
                      </div>
                    </div>

                    <div class="profile-info-row">
                      <div class="profile-info-name"> Nama Pasien </div>

                      <div class="profile-info-value">
                      <span class="editable editable-click" id="kb_nama_pasien"></span>
                      </div>
                    </div>

                    <div class="profile-info-row">
                      <div class="profile-info-name"> Tanggal Kunjungan </div>

                      <div class="profile-info-value">
                        <span class="editable editable-click" id="kb_tgl_kunjungan"></span>
                      </div>
                    </div>

                    <div class="profile-info-row">
                      <div class="profile-info-name"> Poli/Klinik Tujuan </div>

                      <div class="profile-info-value">
                        <span class="editable editable-click" id="kb_poli_tujuan"></span>
                      </div>
                    </div>

                    <div class="profile-info-row">
                      <div class="profile-info-name"> Dokter </div>

                      <div class="profile-info-value">
                        <span class="editable editable-click" id="kb_dokter"></span>
                      </div>
                    </div>

                    <div class="profile-info-row">
                      <div class="profile-info-name"> Jam Praktek </div>

                      <div class="profile-info-value">
                        <span class="editable editable-click" id="kb_jam_praktek"></span>
                      </div>
                    </div>

                  </div>
                </div>
                  <div style="padding-top: 10px; text-align: center">
                      <a href="#" onclick="show_form_rujukan()" class="btn btn-lg btn-success">
                          LANJUTKAN <i class="fa fa-arrow-right"></i> 
                      </a>
                    </div>
              </div>

          </div>
          </div>
          <div class="col-md-2">&nbsp;</div>
        </div>
      </div>

    <!-- CONTENT PENCARIAN RUJUKAN-->
      <div id="form-search-rujukan" style="display: none; min-height: 950px; padding-top: 80px">
          <input name="find_member_by" type="radio" class="ace" value="noRujukan" checked>

          <div class="row">
              <div class="col-md-2">&nbsp;</div>
              <div class="col-md-8">
                  <div class="row">
                      <div class="col-md-12">
                          <div style="font-size: 30px;font-weight: 600;">
                              
                              <div id="noRujukanField">
                                  <label style="font-size: 25px; font-weight: bold">MASUKAN NOMOR RUJUKAN PUSKESMAS</label>
                                  <div class="input-group" style="width: 100%">
                                  <input type="text" class="form-control" style="height: 80px !important;font-size: 55px;vertical-align: middle;text-transform: uppercase; text-align: center" id="noRujukanVal" name="noRujukanVal">
                                  <span class="input-group-btn">
                                      <button class="btn btn-sm btn-primary" type="button" id="btnSearchNoRujukan" style="height: 80px !important; font-size: 50px">
                                      <i class="fa fa-list"></i>
                                      </button>
                                  </span>
                                  <div>
                              </div>

                              <div id="searchByNoKartu" style="display: none">
                                  <div class="input-group" style="width: 100%">
                                  <input type="text" class="form-control" style="height: 80px !important;font-size: 55px;vertical-align: middle;text-transform: uppercase; text-align: center" id="noKartu" name="noKartu">
                                  <span class="input-group-btn">
                                  <button class="btn btn-sm btn-primary" type="button" id="btnSearchMember" style="height: 80px !important; font-size: 50px">
                                      <i class="fa fa-list"></i>
                                  </button>
                                  </span>
                                  </div>
                              </div>
                              
                          </div>
                      </div>
                  </div>

                  <!-- CONTENT PROFILE PASIEN -->
                  <div class="row" id="result-dt-rujukan" style="display: none; padding-top: 20px">
                      <div class="col-md-3">
                          <div class="box box-primary">

                              <ul class="list-group list-group-unbordered">

                                  <li class="list-group-item">
                                  <small style="color: blue; font-weight: bold; font-size:11px">No Kartu BPJS : </small> <div id="noKartuFromNik"></div>
                                  </li>

                                  <li class="list-group-item">
                                  <small style="color: blue; font-weight: bold; font-size:11px">Nama Peserta : </small> <div id="nama"></div>
                                  </li>

                                  <li class="list-group-item">
                                  <small style="color: blue; font-weight: bold; font-size:11px">NIK : </small> <div id="nik"></div>
                                  </li>
                                  <li class="list-group-item">
                                  <small style="color: blue; font-weight: bold; font-size:11px">Tanggal Lahir : </small> <div id="tglLahir"></div>
                                  </li>
                                  <li class="list-group-item">
                                  <small style="color: blue; font-weight: bold; font-size:11px">Umur : </small> <div id="umur_p_bpjs"></div>
                                  </li>
                                  <li class="list-group-item">
                                  <small style="color: blue; font-weight: bold; font-size:11px">Jenis Peserta : </small> <div id="jenisPeserta"></div>
                                  </li>
                                  <li class="list-group-item">
                                  <small style="color: blue; font-weight: bold; font-size:11px">Hak Kelas : </small> <div id="hakKelas"></div>
                                  </li>
                                  <li class="list-group-item">
                                  <small style="color: blue; font-weight: bold; font-size:11px">Status Kepesertaan : </small> <div id="statusPeserta"></div>
                                  </li>
                              </ul>

                          </div>
                      </div>

                      <!-- CONTENT FORM SEP -->
                      <div class="col-md-9">
                          <div class="widget-body">
                              <div class="widget-main no-padding">

                                  <!-- hidden form -->
                                  <input name="tglSEP" id="tglSEP" value="<?php echo date('m/d/Y')?>" placeholder="mm/dd/YYYY" class="form-control date-picker" type="hidden">
                                  <input name="jenis_faskes" type="radio" class="ace" value="1" checked/>
                                  <input type="hidden" class="form-control" id="noKartuHidden" name="noKartuHidden" readonly>
                                  <input name="jnsPelayanan" type="radio" class="ace" value="2" checked/>
                                  <input name="lakalantas" type="radio" class="ace" value="0" checked/>
                                  <input name="penjaminKLL" type="radio" class="ace" value="0" checked/>
                                  <input type="hidden" class="form-control" name="catatan" id="catatan" value="">
                                  <input type="hidden" class="form-control" id="noSuratSKDP" name="noSuratSKDP" value="">
                                  <input type="hidden" class="form-control" id="user" name="user" value="" readonly>
                                  <input id="InputKeydokterDPJP" class="form-control" name="dokterDPJP" type="hidden" placeholder="Masukan keyword minimal 3 karakter" />
                                  <input type="hidden" name="KodedokterDPJP" value="" id="KodedokterDPJP">

                                  <div id="formDetailInsertSEP" style="padding-top:0px; background: white;padding: 18px;">
                                  
                                    <p><b>Hasil Pencarian Data Pasien</b></p>

                                    <div class="form-group">
                                        <label class="col-md-3 control-label">PPK Asal Rujukan</label>
                                        <div class="col-md-5 col-sm-5 col-xs-12">
                                            <input id="inputKeyFaskes" class="form-control" name="ppkRujukan" type="text" placeholder="Masukan keyword minimal 3 karakter" value="" readonly/>
                                            <input type="hidden" name="kodeFaskesHidden" value="" id="kodeFaskesHidden">
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <label class="control-label col-md-3">No MR </label>
                                        <div class="col-md-2">
                                          <input type="text" class="form-control" id="noMR" name="noMR">
                                        </div>
                                        
                                        <div class="col-md-7">
                                          <div class="checkbox">
                                              <label>
                                              <input name="cob" type="checkbox" class="ace" value="1">
                                              <span class="lbl"> Peserta COB</span>
                                              </label>
                                          </div>
                                        </div>
                                    </div>

                                    <!-- Form Rujukan, tidak ditampilkan untuk poli IGD -->

                                    <div id="formRujukan">

                                        <div class="form-group">
                                        <label class="control-label col-md-3">No Rujukan </label>
                                        <div class="col-md-3">
                                            <input type="text" class="form-control" id="noRujukan" name="noRujukan" readonly>
                                        </div>

                                        <label class="control-label col-md-1">Tanggal</label>
                                        <div class="col-md-2">
                                            <div class="input-group">
                                                <input name="tglRujukan" id="tglKunjungan" value="" placeholder="dd/mm/YYYY" class="form-control date-picker" type="text" readonly>
                                                <span class="input-group-addon">
                                            <i class="ace-icon fa fa-calendar"></i>
                                            </span>
                                            </div>
                                        </div>

                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-3 col-sm-3 col-xs-12 control-label">Spesialis/SubSpesialis </label>
                                            <div class="col-md-5 col-sm-5 col-xs-12">
                                                <input id="inputKeyPoli" class="form-control" name="tujuan" type="text" placeholder="Masukan keyword minimal 3 karakter" readonly/>
                                                <input type="hidden" name="kodePoliHidden" value="" id="kodePoliHidden">
                                            </div>

                                            <div class="col-md-3">
                                            <div class="checkbox">
                                                <label>
                                                <input name="eksekutif" type="checkbox" class="ace" value="1">
                                                <span class="lbl"> Eksekutif</span>
                                                </label>
                                            </div>
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-md-3 col-sm-3 col-xs-12 control-label">Diagnosa </label>
                                            <div class="col-md-8 col-sm-8 col-xs-12">
                                                <input id="inputKeyDiagnosa" class="form-control" name="diagAwal" type="text" placeholder="Masukan keyword minimal 3 karakter" style="text-transform: uppercase" readonly/>
                                                <input type="hidden" name="kodeDiagnosaHidden" value="" id="kodeDiagnosaHidden">
                                            </div>
                                        </div>

                                        <div class="form-group">
                                            <label class="control-label col-md-3">No Telp </label>
                                            <div class="col-md-3">
                                                <input type="text" class="form-control" id="noTelp" name="noTelp">
                                            </div>
                                        </div>
                                        
                                        <div class="form-group">
                                            <label class="control-label col-md-3">Dokter DPJP </label>
                                            <div class="col-md-6">
                                            <input id="show_dpjp" class="form-control" name="show_dpjp" type="text" readonly/>
                                            </div>
                                        </div>

                                    </div>

                                    <div id="message-result"></div>

                                    <div class="col-md-12 no-padding" style="padding-top: 40px !important">
                                        <button type="submit" class="btn btn-inverse btn-sm" style="height: 50px !important; font-size: 25px">
                                            <span class="ace-icon fa fa-check-circle icon-on-right bigger-110"></span>
                                            PROSES PENDAFTARAN
                                        </button>
                                    </div>

                                  </div>


                              </div>
                          </div>
                          
                      </div>
                  </div>

              </div>
              <div class="col-md-2">&nbsp;</div>
          </div>

          <div class="row">
              <div class="col-md-12">
                <div id="show_sep" style="min-height: 950px; padding-top: 250px; padding: 20px"></div>
              </div>
          </div>

      </div>


</form>
