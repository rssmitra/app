<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>

<script>

$(document).ready(function () {

  // $.getJSON("../ws_bpjs/Ws_index/getRef?ref=RefKelasRawat", '', function (data) {
  //           $('#select_option option').remove();
  //           $('<option value="">-Silahkan Pilih-</option>').appendTo($('#select_option'));
  //           $.each(data, function (i, o) {
  //               $('<option value="' + o.kode + '">' + o.nama + '</option>').appendTo($('#select_option'));
  //           });

  // });

  // $('#inputKeyFaskes').typeahead({
  //     source: function (query, result) {
  //         $.ajax({
  //             url: "../ws_bpjs/Ws_index/getRef?ref=RefFaskes",
  //             data: { keyword:query,jf:$('input[name=jenis_faskes]:checked').val() },            
  //             dataType: "json",
  //             type: "POST",
  //             success: function (response) {
  //               result($.map(response, function (item) {
  //                   return item;
  //               }));
  //             }
  //         });
  //     },
  //     afterSelect: function (item) {
  //       // do what is needed with item
  //       var val_item=item.split(':')[0];
  //       console.log(val_item);
  //       $('#kodeFaskesHidden').val(val_item);
  //     }
  // });

  // $('#InputKeydokterDPJP').typeahead({
  //     source: function (query, result) {
  //         $.ajax({
  //             url: "../ws_bpjs/Ws_index/getRef?ref=RefDokterDPJP",
  //             data: { spesialis:$('#kodePoliHidden').val(),jp:$('input[name=jnsPelayanan]').val(),tgl:$('#tglSEP').val() },            
  //             dataType: "json",
  //             type: "POST",
  //             success: function (response) {
  //               result($.map(response, function (item) {
  //                   return item;
  //               }));
  //             }
  //         });
  //     },
  //     afterSelect: function (item) {
  //       // do what is needed with item
  //       var val_item=item.split(':')[0];
  //       console.log(val_item);
  //       $('#KodedokterDPJP').val(val_item);
  //     }
  // });


  // $('input[name=find_member_by]').click(function(e){
  //   var field = $('input[name=find_member_by]:checked').val();
  //   $('#showResultData').hide('fast');
  //   $('#formDetailInsertSEP').hide('fast');

  //   if ( field == 'noKartu' ) {
  //     $('#searchByNoKartu').show('fast');
  //     $('#searchBySEP').hide('fast');
  //     $('#showResultData').hide('fast');
  //     $('#noRujukanField').hide('fast');
  //     $('#byJenisFaskesId').show('fast');

  //   }else if (field == 'sep') {
  //     $('#searchByNoKartu').hide('fast');
  //     $('#searchBySEP').show('fast');
  //     $('#showResultData').hide('fast');
  //     $('#noRujukanField').hide('fast');
  //     $('#byJenisFaskesId').hide('fast');
      
  //   }else if (field == 'noRujukan') {
  //     $('#searchByNoKartu').hide('fast');
  //     $('#searchBySEP').hide('fast');
  //     $('#showResultData').hide('fast');
  //     $('#noRujukanField').show('fast');
  //     $('#byJenisFaskesId').show('fast');
  //   }
  // });

  // $('input[name=jnsPelayanan]').click(function(e){
  //   var field = $('input[name=jnsPelayanan]:checked').val();
  //   if ( field == '1' ) {
  //     $('#selectKelasRawatInap').show('fast');
  //   }else if (field == '2') {
  //     $('#selectKelasRawatInap').hide('fast');
  //   }
  // });

  // $('input[name=penjaminKLL]').click(function(e){
  //   var field = $('input[name=penjaminKLL]:checked').val();
  //   if ( field == '1' ) {
  //     $('#showFormPenjaminKLL').show('fast');
  //   }else if (field == '0') {
  //     $('#showFormPenjaminKLL').hide('fast');
  //   }
  // });

  // $('#btnSearchMember').click(function (e) {

  //     $('#showFormPenjaminKLL').hide('fast');
      
  //     e.preventDefault();
  //     var field = $('input[name=find_member_by]:checked').val();
  //     if ( field == 'noKartu' ) {
  //       var jenis_kartu = 'bpjs';
  //       var nokartu = $('#noKartu').val();
  //     }else if (field == 'nik') {
  //       var jenis_kartu = 'nik';
  //       var nokartu = $('#noNik').val();
  //     }

  //     e.preventDefault();
  //     $.ajax({
  //       url: '../ws_bpjs/ws_index/searchMember',
  //       type: "post",
  //       data: {nokartu:nokartu,jenis_kartu:jenis_kartu,tglSEP:$('#tglSEP').val()},
  //       dataType: "json",
  //       beforeSend: function() {
  //         achtungShowLoader();  
  //       },
  //       success: function(data) {
  //         achtungHideLoader();
  //         if(data.status==200){
            
  //           $('#showResultData').show('fast');
  //           $('#formDetailInsertSEP').show('fast');

  //           $('#tglKunjungan').removeAttr('readonly');
  //           $('#noRujukan').removeAttr('readonly');

  //           $('#noKartuHidden').val(data.result.noKartu);
  //           $('#noMR').val(data.result.noMR);

            
  //           $('#noKartuFromNik').text(data.result.noKartu);
  //           $('#nama').text(data.result.nama);
  //           $('#noMR').text(data.result.noMR);
  //           $('#nik').text(data.result.nik);
  //           $('#tglLahir').text(data.result.tglLahir);
  //           $('#umur_p_bpjs').text(data.result.umur);
  //           $('#hakKelas').text(data.result.hakKelas);
  //           $('#jenisPeserta').text(data.result.jenisPeserta);
  //           $('#statusPeserta').text(data.result.statusPeserta);
  //           $('#inputKeyFaskes').val(data.result.ppkAsalRujukan);
  //           $('#kodeFaskesHidden').val(data.result.kodePpkAsalRujukanHidden);
  //         }else{
  //           $.achtung({message: data.message, timeout:5});
  //         }
          
  //       }
  //     });

  // });

  $('#btnSearchNoRujukan').click(function (e) {
      e.preventDefault();
      // var field = $('input[name=find_member_by]:checked').val();
      var jenis_faskes = $('input[name=jenis_faskes]').val();
      var flag = $('input[name=find_member_by]').val();
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
            $.getJSON("../ws_bpjs/Ws_index/getRef?ref=GetRefDokterDPJPRandom", { spesialis:$('#kodePoliHidden').val(),jp:$('input[name=jnsPelayanan]').val(),tgl:$('#tglSEP').val() }, function (row) {
                  $('#KodedokterDPJP').val(row.kode);
                  $('#InputKeydokterDPJP').val(row.nama.toUpperCase());    
                  $('#show_dpjp').val(row.nama.toUpperCase());    

            });

            // cek apakah no mr sesuai atau sama
            if(peserta.mr.noMR == $('#noMRBooking').val() ){
              console.log('mr sesuai');
            }else{
              console.log('mr tidak sesuai');
            }

            // cek apakah poli sesuai
            if(poliRujukan.kode == $('#kodePoliBpjs').val() ){
              console.log('poli sesuai');
            }else{
              console.log('poli tidak sesuai');
            }

            // show form rujukan
            $('#result-dt-rujukan').show('fast');

          }else{
            $.achtung({message: data.message, timeout:5, className: 'achtungFail'});
          }
          
        }
      });

  });

  // $('#btnSearchSep').click(function (e) {
  //     e.preventDefault();
  //     var noSEP = $('#noSEPVal').val();

  //     e.preventDefault();
  //     $.ajax({
  //       url: '../ws_bpjs/ws_index/searchSep',
  //       type: "post",
  //       data: {sep:noSEP},
  //       dataType: "json",
  //       beforeSend: function() {
  //         achtungShowLoader();  
  //       },
  //       success: function(data) {
  //         achtungHideLoader();
  //         if(data.status==200){

  //           var value = data.result.value;
  //           var peserta = data.result.peserta;

  //           /*show hidden*/
  //           $('#showFormPenjaminKLL').hide('fast');
  //           $('#showResultData').show('fast');
  //           $('#formDetailInsertSEP').show('fast');

  //           /*text*/
  //           $('#noKartuFromNik').text(peserta.noKartu);
  //           $('#nama').text(peserta.nama);
  //           $('#nik').text(peserta.nik);
  //           $('#tglLahir').text(peserta.tglLahir);
  //           $('#umur_p_bpjs').text(peserta.umur.umurSekarang);
  //           $('#jenisPeserta').text(peserta.jenisPeserta.keterangan);
  //           $('#hakKelas').text(peserta.hakKelas.keterangan);
  //           $('#statusPeserta').text(peserta.statusPeserta.keterangan);

  //           /*form*/
  //           $('#tglSEP').val(value.tglSep);
  //           $('#noKartuHidden').val(peserta.noKartu);
  //           $('#noMR').val(peserta.mr.noMR);
  //           $('#inputKeyPoli').val(value.nama);
  //           $('#kodePoliHidden').val(value.kodePoli);
  //           $('#inputKeyFaskes').val(peserta.provUmum.nmProvider);
  //           $('#kodeFaskesHidden').val(peserta.provUmum.kdProvider);
  //           $('#noSuratSKDP').val(value.noSuratSKDP);
  //           $('#InputKeydokterDPJP').val(value.namaDokterDPJP);
  //           $('#KodedokterDPJP').val(value.KodedokterDPJP);
  //           $('#noRujukan').val(value.noRujukan);
  //           $('#tglKunjungan').val(value.tglRujukan);
  //           $('#inputKeyDiagnosa').val(value.diagnosa);
  //           $('#kodeDiagnosaHidden').val(value.kodeDiagnosa);
  //           $('#noTelp').val(peserta.mr.noTelepon);
  //           $('#catatan').val(value.catatan);
  //           $('#prosesId').val('update');

  //           $("input[name=jnsPelayanan][value="+value.kodeJnsPelayanan+"]").attr('checked', true);
  //           $("input[name=jenis_faskes][value="+value.asalRujukan+"]").attr('checked', true);

            

  //         }else{
  //           $.achtung({message: data.message, timeout:5});

  //         }
          
  //       }
  //     });

  // });


  // $('#inputKeyDiagnosa').typeahead({
  //     source: function (query, result) {
  //         $.ajax({
  //             url: "../ws_bpjs/Ws_index/getRef?ref=RefDiagnosa",
  //   data: 'keyword=' + query,            
  //             dataType: "json",
  //             type: "POST",
  //             success: function (response) {
  //               result($.map(response, function (item) {
  //                   return item;
  //               }));
  //             }
  //         });
  //     },
  //     afterSelect: function (item) {
  //       // do what is needed with item
  //       var val_item=item.split(':')[0];
  //       console.log(val_item);
  //       $('#kodeDiagnosaHidden').val(val_item);
  //     }
  // });

  // $('#inputKeyPoli').typeahead({
  //     source: function (query, result) {
  //         $.ajax({
  //             url: "../ws_bpjs/Ws_index/getRef?ref=RefPoli",
  //             data: 'keyword=' + query,            
  //             dataType: "json",
  //             type: "POST",
  //             success: function (response) {
  //               result($.map(response, function (item) {
  //                   return item;
  //               }));
  //             }
  //         });
  //     },
  //     afterSelect: function (item) {
  //       // do what is needed with item
  //       var val_item=item.split(':')[0];
  //       var strValue = $.trim(val_item.toString());
  //       console.log(strValue);
  //       $('#kodePoliHidden').val(strValue);
  //       if( strValue == 'IGD' ){
  //         $('#formRujukan').hide('fast');
  //         $('#inputKeyDiagnosa').focus();
  //       }else{
  //         $('#formRujukan').show('fast');
  //       }
  //     }
  // });

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
          $('#load-content-page').load('Self_service/mandiri_bpjs');
          $('#noSep').val(jsonResponse.no_sep);
          /*load sep untuk di print*/
          window.open("../ws_bpjs/Ws_index/view_sep/0112R0340621V003929", '_blank');
      }else{
          window.open("../ws_bpjs/Ws_index/view_sep/0112R0340621V003929", '_blank');
          $.achtung({message: jsonResponse.message, timeout:5, className:'achtungFail'});
          $('#message-result').html('<div class="alert alert-danger">'+jsonResponse.message+'</div>');
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
.kbw-signature { width: 100% !important; height: 150px; border: 0px }
audio, canvas, progress, video {
    border: 1px solid #cccccc !important;
}
</style>
<script src="<?php echo base_url()?>assets/jSignature/js/jquery.signature.js"></script>
<script>
$(function() {
  var sig = $('#sig').signature({thickness: 4});

	$('#clear').click(function() {
		sig.signature('clear');
    $('#paramsSignature').val('');

  });
  
  $('#jpg').click(function() {
    $('#paramsSignature').val(sig.signature('toDataURL', 'image/png', 1));
    $('#btnPrintSEP').show('fast');
  });
  
});
</script>

<style>

    .form-control{
      height: 35px !important;
      font-size:24px;
    }

    .single-form-field label{
      font-weight: bold;
      padding-left: 7px;
      padding-top: 15px
    }

</style>

<div class="row centered" style="width: 80% !important">
  <div class="widget-box effect8">
    <div class="widget-header">
        <h4 class="widget-title">PENCARIAN RUJUKAN</h4>
    </div>

    <div class="widget-body">
        <div class="widget-main">
            <table class="table table-bordered">
              <thead>
                <tr>
                  <th>Kode</th>
                  <th>No. MR & Nama Pasien</th>
                  <th>Dokter & Tujuan Poli/Klinik</th>
                  <th>Tanggal/Jam Praktek</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td style="vertical-align: middle"><?php echo $kode?></td>
                  <td><?php echo isset($profile['no_mr'])?$profile['no_mr']:'-'?><br><?php echo isset($profile['nama'])?$profile['nama']:'-'?></td>
                  <td><?php echo isset($profile['nama_dr'])?$profile['nama_dr']:'-'?><br><?php echo isset($profile['poli'])?$profile['poli']:'-'?></td>
                  <td style="vertical-align: middle"><?php echo isset($profile['tgl_kunjungan'])?$profile['tgl_kunjungan']:'-'?> / <?php echo isset($profile['jam_praktek'])?$profile['jam_praktek']:'-'?></td>
                </tr>
              </tbody>
            </table>
            <div>
                <label for="form-field-mask-1">
                    Silahkan masukan Nomor Rujukan dari Puskesmas.
                </label>

                <div class="input-group">
                    

                    <input class="form-control" type="text" id="noRujukanVal" name="noRujukanVal" style="font-size:40px;height: 55px !important">
                    <span class="input-group-btn">
                        <button class="btn btn-sm btn-default" type="button" id="btnSearchNoRujukan" style="font-size:25px;height: 55px !important">
                            <i class="ace-icon fa fa-search bigger-110"></i>
                            Cari Rujukan
                        </button>
                    </span>
                </div>
            </div>
        </div>
    </div>
  </div>


  <div class="row" id="result-dt-rujukan" style="padding-top: 0px; display: none">
                      
      <div class="col-md-3 no-padding">
          <div class="box box-primary">

              <ul class="list-group list-group-unbordered" style="margin-left: 0px !important">

                  <li class="list-group-item">
                  <small style="color: blue; font-weight: bold; font-size:11px">Nama Peserta : </small> <div id="nama">-</div>
                  </li>

                  <li class="list-group-item">
                  <small style="color: blue; font-weight: bold; font-size:11px">NIK : </small> <div id="nik">-</div>
                  </li>
                  <li class="list-group-item">
                  <small style="color: blue; font-weight: bold; font-size:11px">Tanggal Lahir : </small> <div id="tglLahir">-</div>
                  </li>
                  <li class="list-group-item">
                  <small style="color: blue; font-weight: bold; font-size:11px">Umur : </small> <div id="umur_p_bpjs">-</div>
                  </li>
                  <!-- <li class="list-group-item">
                  <small style="color: blue; font-weight: bold; font-size:11px">Jenis Peserta : </small> <div id="jenisPeserta">-</div>
                  </li>
                  <li class="list-group-item">
                  <small style="color: blue; font-weight: bold; font-size:11px">Hak Kelas : </small> <div id="hakKelas">-</div>
                  </li>
                  <li class="list-group-item">
                  <small style="color: blue; font-weight: bold; font-size:11px">Status Kepesertaan : </small> <div id="statusPeserta">-</div>
                  </li> -->
              </ul>

          </div>
      </div>

      <div class="col-sm-9 no-padding" style="margin-top:-10px">
          <div class="contact-info-right">  
              <div class="contact-area-contact-field">
                <form class="form-horizontal" method="post" id="formInsertSep" action="<?php echo base_url().'ws_bpjs/ws_index/insertSep'?>" enctype="Application/x-www-form-urlencoded" autocomplete="off">    
                      <!-- form hidden -->
                      <input name="tglSEP" id="tglSEP" value="<?php echo date('m/d/Y')?>" placeholder="mm/dd/YYYY" class="form-control date-picker" type="hidden">
                      <input name="jenis_faskes" type="hidden" class="ace" value="1"/>
                      <input type="hidden" class="form-control" id="noKartuHidden" name="noKartuHidden" readonly>
                      <input name="jnsPelayanan" type="hidden" class="ace" value="2"/>
                      <input name="lakalantas" type="hidden" class="ace" value="0"/>
                      <input name="penjaminKLL" type="hidden" class="ace" value="0"/>
                      <input type="hidden" class="form-control" name="catatan" id="catatan" value="">
                      <input type="hidden" class="form-control" id="noSuratSKDP" name="noSuratSKDP" value="<?php echo isset($kode)?$kode:''?>">
                      <input type="hidden" class="form-control" id="user" name="user" value="" readonly>
                      <input id="InputKeydokterDPJP" class="form-control" name="dokterDPJP" type="hidden" placeholder="" />
                      <input type="hidden" name="KodedokterDPJP" value="" id="KodedokterDPJP">
                      <input type="hidden" class="form-control" id="noRujukan" name="noRujukan" readonly>
                      <input name="eksekutif" type="hidden" class="ace" value="0">
                      <input name="tglRujukan" id="tglKunjungan" value="" placeholder="dd/mm/YYYY" class="form-control date-picker" type="hidden" >
                      <input name="find_member_by" type="hidden" class="ace" value="noRujukan" />
                      <input name="jenis_faskes" type="hidden" class="ace" value="1" />
                      <input type="hidden" class="form-control" id="noMR" name="noMR">
                      <input type="hidden" class="form-control" id="noMRBooking" name="noMRBooking" value="<?php echo isset($profile['no_mr'])?$profile['no_mr']:''?>">
                      <input type="hidden" class="form-control" id="kodePoliBpjs" name="kodePoliBpjs" value="<?php echo isset($profile['kode_poli_bpjs'])?$profile['kode_poli_bpjs']:''?>">
                      <!-- ppk asal rujukan -->
                      <input id="inputKeyFaskes" class="form-control" name="ppkRujukan" type="hidden" placeholder="" value="" />
                      <input type="hidden" name="kodeFaskesHidden" value="" id="kodeFaskesHidden">
                      <!-- diagnosa awal -->
                      <input type="hidden" name="kodeDiagnosaHidden" value="" id="kodeDiagnosaHidden">  
                      <input type="hidden" class="form-control" name="diagAwal" value="" id="inputKeyDiagnosa" >  
                      <input id="show_dpjp" class="form-control" name="show_dpjp" type="hidden" />
                      <input id="inputKeyPoli" class="form-control" name="tujuan" type="hidden" placeholder="" />
                      <input type="hidden" name="kodePoliHidden" value="" id="kodePoliHidden">
                      
                      <!-- <div id="formDetailInsertSEP" style="padding-top:0px; background: white;padding: 18px;">
                        <div id="message-result"></div>
                      </div> -->

                      <div class="row">
                        <div class="col-md-12" style="padding: 20px">
                          <table class="table table-bordered">
                            <thead>
                              <tr>
                                <th>No Kartu BPJS</th>
                                <th>Jenis Peserta</th>
                                <th>Hak Kelas</th>
                                <th>Status Kepesertaan</th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td><span id="noKartuFromNik">-</span></td>
                                <td><span id="jenisPeserta">-</span></td>
                                <td><span id="hakKelas">-</span></td>
                                <td><span id="statusPeserta">-</span></td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                        <div class="col-sm-6" style="margin-top: -30px">
                            <div class="single-form-field">
                                <label>Masukan No. Telp/Hp : </label>
                                <div class="form-group">
                                    <input type="text" class="form-control" id="noTelp" name="noTelp" style="height: 40px !important;font-size: 20px;"> 
                                </div>
                            </div>
                        </div>

                        <div class="col-sm-12">
                            <div class="single-form-field">
                                <label>TTd Pasien/Keluarga Pasien : </label>
                                <div id="sig" style="padding: 5px"></div>
                                <div class="form-group">
                                  <input type="text" value="" name="paramsSignature" class="form-control" id="paramsSignature" style="width: 98.8%; margin-bottom: 10px">
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-sm-12" style="padding: 20px; margin-top: -10px">
                            <div class="col-md-12 no-padding">
                                
                                <button type="button" id="clear" class="btn btn-lg btn-danger" style="height: 50px !important; font-size: 25px"><i class="fa fa-undo"></i> Reset TTD</button> 
                                <button type="button" id="jpg" class="btn btn-lg btn-success" style="height: 50px !important; font-size: 25px"><i class="fa fa-save"></i> Simpan TTD</button>
                                <span id="btnPrintSEP" style="display:none">
                                <button type="submit" class="btn btn-primary btn-lg" style="height: 50px !important; font-size: 25px;">
                                    <span class="ace-icon fa fa-print icon-on-right bigger-110"></span>
                                    Cetak SEP
                                </button>
                                </span>
                            </div>
                        </div>

                      </div>
                      
                  </form>
              
              </div>
          </div>
      </div>

  </div>
</div>

