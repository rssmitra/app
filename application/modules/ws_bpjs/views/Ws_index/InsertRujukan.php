<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>

<script>

$(document).ready(function () {

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
          }
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
          }
      });

      $( "#noSep" )
          .keypress(function(event) {
            var keycode =(event.keyCode?event.keyCode:event.which); 
            if(keycode ==13){
              event.preventDefault();
              if($(this).valid()){
                $('#btnSearchSep').focus();
              }
              return false;       
            }
    });

    $('#btnSearchSep').click(function (e) {
        e.preventDefault();
        var noSep = $('#noSep').val();
        $.ajax({
          url: 'ws_bpjs/ws_index/show_detail_sep/'+noSep,
          type: "post",
          data: {noSep:noSep},
          dataType: "json",
          beforeSend: function() {
            achtungShowLoader();  
          },
          success: function(response) {
            achtungHideLoader();
            if(response.status==200){
              $('#showResultData').show('fast');
              $('#noKartuFromNik').text(response.data.noKartu);
              $('#nama').text(response.data.nama);
              $('#noMr').text(response.data.noMr);
              $('#noTelp').text(response.data.noTelp);
              $('#kelamin').text(response.data.kelamin);
              $('#tglLahir').text(response.data.tglLahir);
              $('#jnsPeserta').text(response.data.jnsPeserta);
              $('#hakKelas').text(response.data.hakKelas);

              /*detail sep*/
              $('#noRujukanSep').text(response.data.noRujukan);
              $('#hakKelasSep').text(response.data.hakKelas);
              $('#kelasRawatSep').text(response.data.kelasRawat);
              $('#jnsPelayananSep').text(response.data.jnsPelayanan);
              $('#poliSep').text(response.data.poli);
              $('#PPKPerujukSep').text(response.data.PPKPerujuk);
              $('#diagnosaSep').text(response.data.diagnosa);
              $('#catatanSep').text(response.data.catatan);
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
  })
  //show datepicker when clicking on the icon
  .next().on(ace.click_event, function(){
    $(this).prev().focus();
  });
});

$(document).ready(function(){
  
    $('#formInsertRujukan').ajaxForm({
      beforeSend: function() {
        achtungShowFadeIn();
      },
      uploadProgress: function(event, position, total, percentComplete) {
      },
      complete: function(xhr) {     
        var data=xhr.responseText;
        var jsonResponse = JSON.parse(data);

        if(jsonResponse.status == 200){
          $.achtung({message: jsonResponse.message, timeout:5});
        }else{
                      $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
                    }
        achtungHideLoader();
      }
    }); 

})

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

<div class="row">
  <div class="col-xs-12">
    <!-- PAGE CONTENT BEGINS -->
      <div class="widget-body">
        <div class="widget-main no-padding">
          <form class="form-horizontal" method="post" id="formInsertRujukan" action="<?php echo base_url().'ws_bpjs/ws_index/insertRujukan'?>" enctype="Application/x-www-form-urlencoded" autocomplete="off">
            <br>

            <div class="form-group">
              <label class="control-label col-md-2">Pencarian SEP </label>
              <div class="col-md-4">
                <div class="input-group">
                  <input type="text" name="noSep" id="noSep" class="form-control search-query" placeholder="Masukan Nomor SEP">
                  <span class="input-group-btn">
                    <button type="submit" id="btnSearchSep" class="btn btn-primary btn-sm">
                      <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
                      Search
                    </button>
                  </span>
                </div>
              </div>
            </div>

            <div id="showResultData" style="display:none">

              <div class="form-group">
                <div class="col-md-12">
                <table class="table table-bordered table-hover">
                  <thead>
                    <th style="background-image:linear-gradient(to bottom, #195651 90%, #ddb909 20%)">No Kartu</th>
                    <th style="background-image:linear-gradient(to bottom, #195651 90%, #ddb909 20%)">Nama Peserta</th>
                    <th style="background-image:linear-gradient(to bottom, #195651 90%, #ddb909 20%)">No Mr</th>
                    <th style="background-image:linear-gradient(to bottom, #195651 90%, #ddb909 20%)">JK</th>
                    <th style="background-image:linear-gradient(to bottom, #195651 90%, #ddb909 20%)">Tanggal Lahir</th>
                    <th style="background-image:linear-gradient(to bottom, #195651 90%, #ddb909 20%)">No Telp</th>
                    <th style="background-image:linear-gradient(to bottom, #195651 90%, #ddb909 20%)">Jenis Peserta</th>
                    <th style="background-image:linear-gradient(to bottom, #195651 90%, #ddb909 20%)">Hak Kelas</th>
                  </thead>
                  <tbody>
                    <tr>
                      <td><div id="noKartuFromNik"></div></td>
                      <td><div id="nama"></div></td>
                      <td><div id="noMr"></div></td>
                      <td><div id="kelamin"></div></td>
                      <td><div id="tglLahir"></div></td>
                      <td><div id="noTelp"></div></td>
                      <td><div id="jnsPeserta"></div></td>
                      <td><div id="hakKelas"></div></td>
                    </tr>
                  </tbody>
                </table>
                <b>DETAIL SEP</b>
                <div class="col-md-12">
                      <div class="profile-user-info profile-user-info-striped" style="margin-left:-20px">
                        <div class="profile-info-row">
                          <div class="profile-info-name"> Jenis Pelayanan </div>
                          <div class="profile-info-value">
                            <span class="editable"> <div id="jnsPelayananSep"></div> </span>
                          </div>
                        </div>
                        <div class="profile-info-row">
                          <div class="profile-info-name"> Kelas Rawat </div>
                          <div class="profile-info-value">
                            <span class="editable"> <div id="kelasRawatSep"></div> </span>
                          </div>
                        </div>
                        <div class="profile-info-row">
                          <div class="profile-info-name"> Poli Tujuan </div>
                          <div class="profile-info-value">
                            <span class="editable"> <div id="poliSep"></div> </span>
                          </div>
                        </div>
                        <div class="profile-info-row">
                          <div class="profile-info-name"> Faskes Perujuk </div>
                          <div class="profile-info-value">
                            <span class="editable"> <div id="PPKPerujukSep"></div> </span>
                          </div>
                        </div>
                        <div class="profile-info-row">
                          <div class="profile-info-name"> Diagnosa Awal </div>
                          <div class="profile-info-value">
                            <span class="editable"> <div id="diagnosaSep"></div> </span>
                          </div>
                        </div>
                        <div class="profile-info-row">
                          <div class="profile-info-name"> Catatan </div>
                          <div class="profile-info-value">
                            <span class="editable"> <div id="catatanSep"></div> </span>
                          </div>
                        </div>

                      </div>
                      <br>
                  </div>

                </div>
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-2">Tanggal Rujukan</label>
              <div class="col-md-2">
                <div class="input-group">
                    <input name="tglRujukan" id="tglRujukan" value="" placeholder="dd/MM/YYYY" class="form-control date-picker" type="text">
                    <span class="input-group-addon">
                      <i class="ace-icon fa fa-calendar"></i>
                    </span>
                    
                  </div>
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-2">Pelayanan</label>
              <div class="col-md-6">
                <div class="radio">
                      <label>
                        <input name="jnsPelayanan" type="radio" class="ace" value="1" />
                        <span class="lbl"> Rawat Inap</span>
                      </label>
                      <label>
                        <input name="jnsPelayanan" type="radio" class="ace" value="2" />
                        <span class="lbl"> Rawat Jalan </span>
                      </label>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-2">Tipe</label>
              <div class="col-md-6">
                <div class="radio">
                      <label>
                        <input name="tipeRujukan" type="radio" class="ace" value="0" />
                        <span class="lbl"> Penuh</span>
                      </label>
                      <label>
                        <input name="tipeRujukan" type="radio" class="ace" value="1" />
                        <span class="lbl"> Partial </span>
                      </label>
                      <label>
                        <input name="tipeRujukan" type="radio" class="ace" value="2" />
                        <span class="lbl"> Rujuk Balik </span>
                      </label>
                </div>
              </div>
            </div>

            <div class="form-group">
                <label class="col-md-2 control-label">Dirujuk ke</label>
                <div class="col-md-5 col-sm-5 col-xs-12">
                    <input id="inputKeyFaskes" class="form-control" name="ppkDirujuk" type="text" placeholder="Masukan keyword minimal 3 karakter" />
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-2 col-sm-2 col-xs-12 control-label">Poli Rujukan  </label>
                <div class="col-md-5 col-sm-5 col-xs-12">
                    <input id="inputKeyPoli" class="form-control" name="poliRujukan" type="text" placeholder="Masukan keyword minimal 3 karakter" />
                </div>
            </div>

            <div class="form-group">
                <label class="col-md-2 col-sm-2 col-xs-12 control-label">Diagnosa Rujukan</label>
                <div class="col-md-8 col-sm-8 col-xs-12">
                    <input id="inputKeyDiagnosa" class="form-control" name="diagRujukan" type="text" placeholder="Masukan keyword minimal 3 karakter" />
                </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-2">Catatan Rujukan </label>
              <div class="col-md-6">
                <input type="text" class="form-control" id="catatan" name="catatan">
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-2">Pengguna </label>
              <div class="col-md-4">
                <input type="text" class="form-control" id="user" name="user" value="<?php echo $this->session->userdata('user')->fullname?>" readonly>
              </div>
            </div>

            <div class="form-group" style="display:none">
              <label class="control-label col-md-2">Jenis Faskes</label>
              <div class="col-md-6">
                <div class="radio">
                      <label>
                        <input name="jenis_faskes" type="radio" class="ace" value="1" />
                        <span class="lbl"> Faskes 1 / Puskesmas</span>
                      </label>
                      <label>
                        <input name="jenis_faskes" type="radio" class="ace" value="2" checked/>
                        <span class="lbl"> Faskes 2 / RS </span>
                      </label>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-2">&nbsp;</label>
              <div class="col-md-3">
                <button type="submit" class="btn btn-primary btn-sm">
                      <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
                      Proses
                    </button>
              </div>
            </div>

            <br>
            <h4>Hasil Input : <span id="result_not_found"></span></h4>
            <div class="col-md-12">
                <div class="profile-user-info profile-user-info-striped" style="margin-left:-20px">
                  
                  <div class="profile-info-row">
                    <div class="profile-info-name"> No Kunjungan </div>
                    <div class="profile-info-value">
                      <span class="editable"> <div id="noKunjungan"></div> </span>
                    </div>
                  </div>
                  <div class="profile-info-row">
                    <div class="profile-info-name"> Poli Rujukan </div>
                    <div class="profile-info-value">
                      <span class="editable"> <div id="poliPerujuk"></div> </span>
                    </div>
                  </div>
                  <div class="profile-info-row">
                    <div class="profile-info-name"> Kota/Kabupaten </div>
                    <div class="profile-info-value">
                      <span class="editable"> <div id="provPerujuk"></div> </span>
                    </div>
                  </div>
                  <div class="profile-info-row">
                    <div class="profile-info-name"> Diagnosa Awal </div>
                    <div class="profile-info-value">
                      <span class="editable"> <div id="diagnosa"></div> </span>
                    </div>
                  </div>

                </div>
                <br>
            </div>

            

            <h4>Keterangan : </h4>

            Fungsi : Insert Rujukan <br>

            Method : POST <br>

            Format : Json <br>

            Content-Type: Application/x-www-form-urlencoded <br>

          </form>
      </div>
    </div>
    
    <!-- PAGE CONTENT ENDS -->
  </div><!-- /.col -->
</div><!-- /.row -->


