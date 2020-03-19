<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>

<script>

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

      $('input[name=find_member_by]').click(function(e){
        var field = $('input[name=find_member_by]:checked').val();
        if ( field == 'noKartu' ) {
          $('#searchByNoKartu').show('fast');
          $('#searchByNik').hide('fast');
          $('#showResultData').hide('fast');
        }else if (field == 'nik') {
          $('#searchByNoKartu').hide('fast');
          $('#searchByNik').show('fast');
          $('#showResultData').hide('fast');
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

      $('.btnSearchMember').click(function (e) {
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
                $('#noKartuHidden').val(data.result.noKartu);
                $('#noKartuFromNik').text(data.result.noKartu);
                $('#nama').text(data.result.nama);
                $('#noMR').text(data.result.noMR);
                $('#nik').text(data.result.nik);
                $('#tglLahir').text(data.result.tglLahir);
                $('#umur').text(data.result.umur);
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
          $('#page-area-content').load('ws_bpjs/ws_index?modWs=MonitoringDataKunjungan');
          /*load sep untuk di print*/
          window.open("ws_bpjs/Ws_index/view_sep/"+jsonResponse.noSep+"", '_blank');

        }else{
          $.achtung({message: jsonResponse.message, timeout:5});
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

  });

jQuery(function($) {

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

</script>
<div class="page-header">
  <h1>
    <?php echo $title?>
    <small>
      <i class="ace-icon fa fa-angle-double-right"></i>
      <?php echo $breadcrumbs?>
    </small>
  </h1>
</div><!-- /.page-header -->

<!-- <?php echo '<pre>'; print_r($peserta); echo '</pre>'?> -->
<div class="row">
  <div class="col-xs-12">
    <!-- PAGE CONTENT BEGINS -->
      <div class="widget-body">
        <div class="widget-main no-padding">
          <form class="form-horizontal" method="post" id="formInsertSep" action="<?php echo base_url().'ws_bpjs/ws_index/insertSep'?>" enctype="Application/x-www-form-urlencoded" autocomplete="off">
            <br>

            <div class="form-group">
              <label class="control-label col-md-2">Tanggal SEP</label>
              <div class="col-md-2">
                <div class="input-group">
                    <span class="input-group-addon">
                      <i class="ace-icon fa fa-calendar"></i>
                    </span>
                    <input name="tglSEP" id="tglSEP" value="<?php echo isset($value)?$this->tanggal->formatDateForm($value->tglSep):''?>" placeholder="Format : yyyy-MM-dd" class="form-control date-picker" type="text" readonly>
                    
                  </div>
              </div>

              <label class="control-label col-md-2">No SEP</label>
              <div class="col-md-2">
                <input type="text" name="noSep" class="form-control" value="<?php echo isset($value)?$value->noSep:''?>" readonly>
              </div>

            </div>

            <!-- <div class="form-group">
              <label class="control-label col-md-2">Cari Peserta Berdasarkan</label>
              <div class="col-md-6">
                <div class="radio">
                      <label>
                        <input name="find_member_by" type="radio" class="ace" value="noKartu" checked/>
                        <span class="lbl"> No Kartu BPS</span>
                      </label>
                      <label>
                        <input name="find_member_by" type="radio" class="ace" value="nik" />
                        <span class="lbl"> NIK </span>
                      </label>
                </div>
              </div>
            </div> -->

            <!-- <div class="form-group" id="searchByNoKartu" style="display:block">
              <label class="control-label col-md-2">No Kartu BPJS </label>
              <div class="col-md-3">
                <input type="text" class="form-control" id="noKartu" name="noKartu" value="<?php echo isset($value)?$value->noKartu:''?>">
              </div>
              <div class="col-md-2">
                <a href="#" class="btn btn-xs btn-primary btnSearchMember" style="margin-left:-20px">Search</a>
              </div>
            </div> -->

            <!-- <div class="form-group" id="searchByNik" style="display:block">
              <label class="control-label col-md-2">NIK </label>
              <div class="col-md-3">
                <input type="text" class="form-control" id="noNik" name="noNik">
              </div>
              <div class="col-md-2">
                <a href="#" class="btn btn-xs btn-primary btnSearchMember" style="margin-left:-20px">Search</a>
              </div>
            </div> -->

            <div id="showResultData" style="display:block">
              <div class="form-group">
                <label class="control-label col-md-2">Nomor Kartu BPJS </label>
                <div class="col-md-3">
                  <input type="text" class="form-control" id="noKartuHidden" name="noKartuHidden" value="<?php echo isset($value)?$value->noKartu:''?>" readonly>
                </div>
              </div>

              <div class="form-group">
                <div class="col-md-12">
                <table class="table table-bordered table-hover">
                  <thead>
                    <th style="background-image:linear-gradient(to bottom, #195651 90%, #ddb909 20%)">No Kartu</th>
                    <th style="background-image:linear-gradient(to bottom, #195651 90%, #ddb909 20%)">Nama Peserta</th>
                    <th style="background-image:linear-gradient(to bottom, #195651 90%, #ddb909 20%)">NIK</th>
                    <th style="background-image:linear-gradient(to bottom, #195651 90%, #ddb909 20%)">Tanggal Lahir</th>
                    <th style="background-image:linear-gradient(to bottom, #195651 90%, #ddb909 20%)">Umur</th>
                    <th style="background-image:linear-gradient(to bottom, #195651 90%, #ddb909 20%)">Jenis Peserta</th>
                    <th style="background-image:linear-gradient(to bottom, #195651 90%, #ddb909 20%)">Hak Kelas</th>
                    <th style="background-image:linear-gradient(to bottom, #195651 90%, #ddb909 20%)">Status Kepesertaan</th>
                  </thead>
                  <tbody>
                    <td><?php echo isset($peserta)?$peserta->noKartu:''?></td>
                    <td><?php echo isset($peserta)?$peserta->nama:''?></td>
                    <td><?php echo isset($peserta)?$peserta->nik:''?></td>
                    <td><?php echo isset($peserta)?$peserta->tglLahir:''?></td>
                    <td><?php echo isset($peserta)?$peserta->umur->umurSekarang:''?></td>
                    <td><?php echo isset($peserta)?$peserta->jenisPeserta->keterangan:''?></td>
                    <td><?php echo isset($peserta)?$peserta->hakKelas->keterangan:''?></td>
                    <td><?php echo isset($peserta)?$peserta->statusPeserta->keterangan:''?></td>
                  </tbody>
                </table>
                </div>
              </div>
            </div>
            

            <div class="form-group">
              <label class="control-label col-md-2">No MR </label>
              <div class="col-md-2">
                <input type="text" class="form-control" id="noMR" name="noMR" value="<?php echo isset($value)?$value->noMr:''?>">
              </div>
              
              <div class="col-md-2">
                <div class="checkbox">
                  <label>
                    <input name="cob" type="checkbox" class="ace" value="1" <?php echo isset($value)?($value->cob==1)?'checked':'':''?>>
                    <span class="lbl"> Peserta COB</span>
                  </label>
                </div>
              </div>
            </div>

            <div class="form-group">
                <label class="col-md-2 col-sm-2 col-xs-12 control-label">Poli </label>
                <div class="col-md-5 col-sm-5 col-xs-12">
                    <input id="inputKeyPoli" class="form-control" name="tujuan" type="text" placeholder="Masukan keyword minimal 3 karakter" value="<?php echo isset($value)?$value->poli:''?>" disabled/>
                    <input type="hidden" name="kodePoliHidden" value="<?php echo isset($value)?$value->kodePoli:''?>" id="kodePoliHidden" readonly>
                </div>

                <div class="col-md-2">
                <div class="checkbox">
                  <label>
                    <input name="eksekutif" type="checkbox" class="ace" value="1" <?php echo isset($value)?($value->poliEksekutif==1)?'checked':'':''?> >
                    <span class="lbl"> Eksekutif</span>
                  </label>
                </div>
              </div>

            </div>

            <!-- Form Rujukan, tidak ditampilkan untuk poli IGD -->
            <div id="formRujukan">
                <div class="form-group">
                  <label class="control-label col-md-2">Asal Rujukan</label>
                  <div class="col-md-6">
                    <div class="radio">
                          <label>
                            <input name="jenis_faskes" type="radio" class="ace" value="1" <?php echo isset($value)?($value->asalRujukan==1)?'checked':'':''?>/>
                            <span class="lbl"> Faskes 1 / Puskesmas</span>
                          </label>
                          <label>
                            <input name="jenis_faskes" type="radio" class="ace" value="2" <?php echo isset($value)?($value->asalRujukan==2)?'checked':'':''?>/>
                            <span class="lbl"> Faskes 2 / RS </span>
                          </label>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                    <label class="col-md-2 control-label">PPK Asal Rujukan</label>
                    <div class="col-md-5 col-sm-5 col-xs-12">
                        <input id="inputKeyFaskes" class="form-control" name="ppkRujukan" type="text" placeholder="Masukan keyword minimal 3 karakter" value="<?php echo isset($value)?$value->PPKPerujuk:''?>" disbaled/>
                        <input type="hidden" name="kodeFaskesHidden" value="<?php echo isset($value)?$value->kodePPPKPerujuk:''?>" id="kodeFaskesHidden">
                    </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-2">Tanggal Rujukan</label>
                  <div class="col-md-2">
                    <div class="input-group">
                        <span class="input-group-addon">
                          <i class="ace-icon fa fa-calendar"></i>
                        </span>
                        <input name="tglRujukan" id="tglRujukan" value="<?php echo isset($value)?$value->tglRujukan:''?>" placeholder="Format : yyyy-MM-dd" class="form-control date-picker" type="text" readonly>
                        
                      </div>
                  </div>
                </div>
                <div class="form-group">
                  <label class="control-label col-md-2">No Rujukan </label>
                  <div class="col-md-2">
                    <input type="text" class="form-control" id="noRujukan" name="noRujukan" value="<?php echo isset($value)?$value->noRujukan:''?>" readonly>
                  </div>
                </div>
            
                <div class="form-group">
                  <label class="control-label col-md-2">Jenis Pelayanan</label>
                  <div class="col-md-6">
                    <div class="radio">
                          <label>
                            <input name="jnsPelayanan" type="radio" class="ace" value="1" <?php echo isset($value)?($value->kodeJnsPelayanan==1)?'checked':'':''?>/>
                            <span class="lbl"> Rawat Inap</span>
                          </label>
                          <label>
                            <input name="jnsPelayanan" type="radio" class="ace" value="2" <?php echo isset($value)?($value->kodeJnsPelayanan==2)?'checked':'':''?>/>
                            <span class="lbl"> Rawat Jalan </span>
                          </label>
                    </div>
                  </div>
                </div>

                <div class="form-group" id="selectKelasRawatInap" <?php echo isset($value)?($value->kodeJnsPelayanan==1)?'style="display:block"':'style="display:none"':'style="display:none"'?> >
                    <label class="control-label col-md-2">Kelas Rawat</label>
                    <div class="col-md-4">
                        <select name="kelasRawat" id="select_option" class="form-control">
                          <option value="">- Silahkan Pilih -</option>
                        </select>
                    </div>
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-2 col-sm-2 col-xs-12 control-label">Diagnosa </label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <input id="inputKeyDiagnosa" class="form-control" name="diagAwal" type="text" placeholder="Masukan keyword minimal 3 karakter" value="<?php echo isset($value)?$value->kodeDiagnosa.' : '.$value->diagnosa:''?>" />
                    <input type="hidden" name="kodeDiagnosaHidden" value="<?php echo isset($value)?$value->kodeDiagnosa:''?>" id="kodeDiagnosaHidden">
                </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-2">No Telp </label>
              <div class="col-md-2">
                <input type="text" class="form-control" id="noTelp" name="noTelp" value="<?php echo isset($value)?$value->noTelp:''?>">
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-2">Catatan </label>
              <div class="col-md-6">
                <textarea class="form-control" name="catatan" style="height:50px !important" placeholder="Masukan catatan"><?php echo isset($value)?$value->catatan:''?></textarea>
              </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-2">Kecelakaan Lalin?</label>
                <div class="col-md-6">
                  <div class="radio">
                        <label>
                          <input name="lakalantas" type="radio" class="ace" value="1" />
                          <span class="lbl"> Ya</span>
                        </label>
                        <label>
                          <input name="lakalantas" type="radio" class="ace" value="0" />
                          <span class="lbl"> Tidak </span>
                        </label>
                  </div>
                </div>
            </div>

            <div class="form-group">
                <label class="control-label col-md-2">Penjamin KLL</label>
                <div class="col-md-6">
                  <div class="radio">
                        <label>
                          <input name="penjaminKLL" type="radio" class="ace" value="1" />
                          <span class="lbl"> Ya</span>
                        </label>
                        <label>
                          <input name="penjaminKLL" type="radio" class="ace" value="0" />
                          <span class="lbl"> Tidak </span>
                        </label>
                  </div>
                </div>
            </div>

            <!-- penjamin kll -->
            <div id="showFormPenjaminKLL" style="display:none">

              <div class="form-group">
                <label class="control-label col-md-2">Penjamin</label>
                <div class="col-md-8">
                  <div class="checkbox">
                    <label>
                      <input name="penjamin" type="checkbox" class="ace" value="1">
                      <span class="lbl"> Jasa Raharja</span>
                    </label>
                  </div>
                  <div class="checkbox">
                    <label>
                      <input name="penjamin" type="checkbox" class="ace" value="2">
                      <span class="lbl"> BPJS Ketenagakerjaan</span>
                    </label>
                  </div>
                  <div class="checkbox">
                    <label>
                      <input name="penjamin" type="checkbox" class="ace" value="3">
                      <span class="lbl"> TASPEN PT</span>
                    </label>
                  </div>
                  <div class="checkbox">
                    <label>
                      <input name="penjamin" type="checkbox" class="ace" value="4">
                      <span class="lbl"> ASABRI PT</span>
                    </label>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <label class="control-label col-md-2">Tanggal Kejadian</label>
                <div class="col-md-2">
                  <div class="input-group">
                      <span class="input-group-addon">
                        <i class="ace-icon fa fa-calendar"></i>
                      </span>
                      <input name="tglKejadian" id="tglKejadian" value="" placeholder="Format : yyyy-MM-dd" class="form-control date-picker" type="text">
                    </div>
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-2">Lokasi Kejadian </label>
                <div class="col-md-4">
                  <input type="text" class="form-control" id="lokasiLaka" name="lokasiLaka" value="">
                </div>
              </div>
              <div class="form-group">
                <label class="control-label col-md-2">Keterangan Kejadian </label>
                <div class="col-md-6">
                  <textarea class="form-control" name="" style="height:50px !important" placeholder="Masukan keterangan kejadian"></textarea>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-2">Pengguna </label>
              <div class="col-md-2">
                <input type="text" class="form-control" id="user" name="user" value="<?php echo $this->session->userdata('user')->fullname?>" readonly>
              </div>
            </div>

            <div class="form-group">
            <!-- flag hidden -->
            <input type="hidden" name="proses" value="update">
            
              <label class="control-label col-md-2">&nbsp;</label>
              <div class="col-md-3">
                <button type="submit" class="btn btn-primary btn-sm">
                      <span class="ace-icon fa fa-save icon-on-right bigger-110"></span>
                      Submit
                    </button>
              </div>
            </div>

            <h4>Keterangan : </h4>

            Fungsi : Update SEP <br>

            Method : PUT <br>

            Format : Json <br>

            Content-Type: Application/x-www-form-urlencoded <br>

        </form>
      </div>
    </div>
    
    <!-- PAGE CONTENT ENDS -->
  </div><!-- /.col -->
</div><!-- /.row -->


