<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script>
jQuery(function($) {

  $('.date-picker').datepicker({
    autoclose: true,
    todayHighlight: true
  })
  //show datepicker when clicking on the icon
  .next().on(ace.click_event, function(){
    $(this).prev().focus();
  });
});

$(document).ready(function(){
  
    $('#formSearchRujukanByNoKartu').ajaxForm({
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
          $('#nama').text(jsonResponse.result.nama);
          $('#nik').text(jsonResponse.result.nik);
          $('#tglLahir').text(jsonResponse.result.tglLahir);
          $('#umur').text(jsonResponse.result.umur);
          $('#hakKelas').text(jsonResponse.result.hakKelas);
          $('#jenisPeserta').text(jsonResponse.result.jenisPeserta);
          $('#statusPeserta').text(jsonResponse.result.statusPeserta);
          $('#noKunjungan').text(jsonResponse.result.noKunjungan);
          $('#poliPerujuk').text(jsonResponse.result.poliPerujuk);
          $('#provPerujuk').text(jsonResponse.result.provPerujuk);
          $('#diagnosa').text(jsonResponse.result.diagnosa);

        }else{
          $.achtung({message: jsonResponse.message, timeout:5});
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
          <form class="form-horizontal" method="post" id="formSearchRujukanByNoKartu" action="<?php echo base_url().'ws_bpjs/ws_index/searchRujukan'?>" enctype="multipart/form-data" autocomplete="off">
            <br>

            <div class="form-group">
              <label class="control-label col-md-2">Jenis Faskes</label>
              <div class="col-md-6">
                <div class="radio">
                      <label>
                        <input name="jenis_faskes" type="radio" class="ace" value="pcare" />
                        <span class="lbl"> Puskesmas </span>
                      </label>
                      <label>
                        <input name="jenis_faskes" type="radio" class="ace" value="rs" />
                        <span class="lbl"> Rumah Sakit </span>
                      </label>
                </div>
              </div>
            </div>
            
            <div class="form-group">
              <label class="control-label col-md-2">No Kartu BPJS </label>
              <div class="col-md-4">
                <div class="input-group">
                  <input type="text" name="noKartu" class="form-control search-query" placeholder="Masukan Nomor Kartu BPJS">
                  <input type="hidden" name="flag" value="noKartu">
                  <span class="input-group-btn">
                    <button type="submit" class="btn btn-purple btn-sm">
                      <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
                      Search
                    </button>
                  </span>
                </div>
              </div>
            </div>

            <br><br>
            
            <div class="form-group">

              <div class="col-md-3">
                <div class="col-xs-12 col-sm-12 center">
                  <div>
                    <span class="profile-picture">
                      <img id="avatar" class="editable img-responsive editable-click editable-empty" alt="Alex's Avatar" src="<?php echo base_url()?>assets/avatars/profile-pic.jpg">
                    </span>
                  </div>

                </div>
              </div>

              <div class="col-md-9">
                <div class="profile-user-info profile-user-info-striped" style="margin-left:-20px">
                  <div class="profile-info-row">
                    <div class="profile-info-name"> Nama Peserta (Gender)</div>

                    <div class="profile-info-value">
                      <span class="editable"> <div id="nama"></div> </span>
                    </div>
                  </div>

                  <div class="profile-info-row">
                    <div class="profile-info-name"> NIK </div>
                    <div class="profile-info-value">
                      <span class="editable" > <div id="nik"></div> </span>
                    </div>
                  </div>

                  <div class="profile-info-row">
                    <div class="profile-info-name"> Tanggal Lahir </div>

                    <div class="profile-info-value">
                      <span class="editable" > <div id="tglLahir"></div> </span>
                    </div>
                  </div>

                  <div class="profile-info-row">
                    <div class="profile-info-name"> Umur </div>

                    <div class="profile-info-value">
                      <span class="editable" > <div id="umur"></div> </span>
                    </div>
                  </div>

                  <div class="profile-info-row">
                    <div class="profile-info-name"> Jenis Peserta </div>

                    <div class="profile-info-value">
                      <span class="editable" ><div id="jenisPeserta"></div></span>
                    </div>
                  </div>

                  <div class="profile-info-row">
                    <div class="profile-info-name"> Hak Kelas </div>

                    <div class="profile-info-value">
                      <span class="editable" id="about"><div id="hakKelas"></div></span>
                    </div>
                  </div>

                  <div class="profile-info-row">
                    <div class="profile-info-name"> Status Kepesertaan </div>

                    <div class="profile-info-value">
                      <span class="editable" ><div id="statusPeserta"></div></span>
                    </div>
                  </div>

                </div>
              </div>

            </div>
            <h4>Data Rujukan : </h4>
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

            Fungsi : Pencarian data rujukan dari Pcare berdasarkan nomor rujukan <br>

            Method : GET <br>

            Format : Json <br>

            Content-Type: application/json; charset=utf-8 <br>

            Parameter : Nomor Rujukan <br>

        </form>
      </div>
    </div>
    
    <!-- PAGE CONTENT ENDS -->
  </div><!-- /.col -->
</div><!-- /.row -->


