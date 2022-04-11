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
            umur = peserta.umur.umurSekarang;
            var split_umur = umur.split(',')[0];

            $('#noKartuFromNik').text(peserta.noKartu);
            $('#nama').text(peserta.nama);
            $('#user').val(peserta.nama);
            $('#nik').text(peserta.nik);
            $('#tglLahir').text(peserta.tglLahir);
            $('#umur_p_bpjs').text(peserta.umur.umurSekarang);
            $('#umur_saat_pelayanan_hidden').val(split_umur);
            $('#jenisPeserta').text(peserta.jenisPeserta.keterangan);
            $('#hakKelas').text(peserta.hakKelas.keterangan);
            $('#statusPeserta').text(peserta.statusPeserta.keterangan);

            /*form*/
            $('#noKartuHidden').val(peserta.noKartu);
            $('#nama_pasien_hidden').val(peserta.nama);
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
      // achtungShowLoader();  
      },
      uploadProgress: function(event, position, total, percentComplete) {
      },
      complete: function(xhr) {     
        var data=xhr.responseText;
        var jsonResponse = JSON.parse(data);
        if(jsonResponse.status == 200){
            $.achtung({message: jsonResponse.message, timeout:5});
            var no_sep = '0112R0340122V000062';
            $('#noSep').val(jsonResponse.no_sep);
            /*load sep untuk di print*/
            $('#load-content-page').load("../ws_bpjs/Ws_index/view_sep/"+no_sep+"");
        }else{
            // window.open("../ws_bpjs/Ws_index/view_sep/0112R0340621V003929", '_blank');
            $.achtung({message: jsonResponse.message, timeout:5, className:'achtungFail'});
            $('#message-result').html('<div class="alert alert-danger">'+jsonResponse.message+'</div>');
        }

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
.kbw-signature { min-width: 550px !important;border: 0px }
/* audio, canvas, progress, video {
    border: 1px solid #cccccc !important;
} */
</style>
<script src="<?php echo base_url()?>assets/jSignature/js/jquery.signature.custom.js"></script>
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

function showModalTTD()
{  

  noMr = $('#noMR').val();
  //alert(noMr); return false;
  if (noMr == '') {

    alert('Silahkan cari pasien terlebih dahulu !'); return false;
  
  }else{

    // PopupCenter('registration/reg_pasien/form_modal_ttd/'+noMr+'', 'TANDA TANGAN PASIEN (DIGITAL SIGNATURE)', 900, 500);
    $('#result_text_edit_pasien').text('TANDA TANGAN PASIEN');

    $('#form_pasien_modal_ttd').load('reg_pasien/form_modal_ttd/'+noMr+''); 

    $("#modalTTDPasien").modal();

  }
    
}

</script>

<div class="row">
  <div class="col-xs-1">&nbsp;</div>
    <div class="col-xs-10">

      <div class="widget-box effect8">
        <div class="widget-header">
            <h4 class="widget-title" style="padding-left: 10px; font-weight: bold">CETAK SURAT ELEGIBILITAS PASIEN (SEP)</h4>
        </div>

        <div class="widget-body">
            <div class="widget-main">
                <table class="table table-bordered">
                  <thead>
                    <tr>
                      <th>Kode</th>
                      <th>No. MR</th>
                      <th>Nama Pasien</th>
                      <th>Nama Dokter</th>
                      <th>Tujuan Poli/Klinik</th>
                      <th>Tanggal/Jam Praktek</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr>
                      <td style="vertical-align: middle"><?php echo $kode?></td>
                      <td><?php echo isset($profile['no_mr'])?$profile['no_mr']:'-'?></td>
                      <td><?php echo isset($profile['nama'])?$profile['nama']:'-'?></td>
                      <td><?php echo isset($profile['nama_dr'])?$profile['nama_dr']:'-'?></td>
                      <td><?php echo isset($profile['poli'])?$profile['poli']:'-'?></td>
                      <td style="vertical-align: middle"><?php echo isset($profile['tgl_kunjungan'])?$profile['tgl_kunjungan']:'-'?> / <?php echo isset($profile['jam_praktek'])?$profile['jam_praktek']:'-'?></td>
                    </tr>
                  </tbody>
                </table>
                <br>
                <br>
                <form class="form-horizontal" method="post" id="formInsertSep" action="<?php echo base_url().'registration/Self_service/processCetakSep'?>" enctype="Application/x-www-form-urlencoded" autocomplete="off">   
                    <div class="row" id="result-dt-rujukan" style="padding-top: 0px;">
                        <!-- hidden form -->
                        <input type="hidden" name="kode_booking" id="kode_booking" value="<?php echo isset($_GET['kode'])?$_GET['kode']:0?>">
                        <input type="hidden" name="no_mr" id="no_mr" value="<?php echo isset($profile['no_mr'])?$profile['no_mr']:0?>">
                        <input type="hidden" name="no_registrasi" id="no_registrasi" value="<?php echo isset($is_registered->no_registrasi)?$is_registered->no_registrasi:0?>">
                        <input type="hidden" name="no_antrian" id="no_antrian" value="<?php echo isset($is_registered->no_antrian)?$is_registered->no_antrian:0?>">
                        <input type="hidden" name="no_kunjungan" id="no_kunjungan" value="<?php echo isset($is_registered->no_kunjungan)?$is_registered->no_kunjungan:0?>">
                        <input type="hidden" name="tipe_pasien" id="tipe_pasien" value="bpjs">

                        <div class="col-md-12">
                            <div class="col-sm-12 no-padding">
                                <div class="single-form-field">
                                    <label class="center">TTd Pasien/Keluarga Pasien : </label>
                                    <div id="sig" style="padding: 5px; "></div>
                                    <span style="margin-top:-10px; margin-bottom: 10px"><i>Jangan sampai keluar garis atau keluar kotak..</i></span>
                                    <div class="form-group">
                                        <input type="text" value="" name="paramsSignature" class="form-control" id="paramsSignature" style="width: 98.8%; margin-bottom: 10px">
                                    </div>
                                    <div class="center">
                                        <button type="button" id="clear" class="btn btn-sm btn-danger" style="height: 30px !important; font-size: 14px"><i class="fa fa-undo"></i> Reset TTD</button> 
                                        <button type="button" id="jpg" class="btn btn-sm btn-success" style="height: 30px !important; font-size: 14px"><i class="fa fa-save"></i> Simpan TTD</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-12">
<!-- 
                            <div class="single-form-field">
                                <label>Masukan No. Telp/Hp : </label>
                                <div class="form-group">
                                    <input type="text" class="form-control" id="noTelp" name="noTelp" style="height: 40px !important;font-size: 20px;"> 
                                </div>
                            </div>   -->

                            <div class="col-sm-12 center">
                            <span id="btnPrintSEP" style="display:none">
                                <button type="submit" class="btn btn-success btn-lg" style="height: 50px !important; font-size: 20px; margin-top:15px; background: green !important">
                                    Cetak Surat Elegibilitas Pasien (SEP)
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

  <div class="col-xs-1">&nbsp;</div>
</div>

