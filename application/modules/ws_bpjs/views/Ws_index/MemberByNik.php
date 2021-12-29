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
  
    $('#formRefMemberByNik').ajaxForm({
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
          $('#noKartu').text(jsonResponse.result.noKartu);
          $('#nama').text(jsonResponse.result.nama);
          $('#nik').text(jsonResponse.result.nik);
          $('#tglLahir').text(jsonResponse.result.tglLahir);
          $('#umur').text(jsonResponse.result.umur);
          $('#hakKelas').text(jsonResponse.result.hakKelas);
          $('#jenisPeserta').text(jsonResponse.result.jenisPeserta);
          $('#statusPeserta').text(jsonResponse.result.statusPeserta);
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
          <form class="form-horizontal" method="post" id="formRefMemberByNik" action="<?php echo base_url().'ws_bpjs/ws_index/searchMember'?>" enctype="multipart/form-data" autocomplete="off">
            <br>

            <div class="form-group">
              <label class="control-label col-md-3">Tanggal Pelayanan SEP</label>
              <div class="col-md-2">
                <input type="hidden" name="jenis_kartu" value="nik">
                <div class="input-group">
                    <input name="tglSEP" id="tglSEP" value="" placeholder="m/d/Y" class="form-control date-picker" type="text" >
                    <span class="input-group-addon">
                      <i class="ace-icon fa fa-calendar"></i>
                    </span>
                  </div>
              </div>
              <div class="col-md-3" style="margin-left:-20px">
                <input name="nokartu" id="nokartu" value="" placeholder="Masukan NIK" class="form-control" type="text" >
              </div>
              <div class="col-md-1" style="margin-left:-20px">
                <button type="submit" class="btn btn-primary btn-sm">
                      <span class="ace-icon fa fa-search icon-on-right bigger-110"></span>
                      Search
                    </button>
              </div>
            </div>

            <br>
            
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
                    <div class="profile-info-name"> Nomor Kartu BPJS</div>

                    <div class="profile-info-value">
                      <span class="editable"> <div id="noKartu"></div> </span>
                    </div>
                  </div>
                  <div class="profile-info-row">
                    <div class="profile-info-name"> Nama Peserta (MR)</div>

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

            <h3>Keterangan : </h3>

            Fungsi : Pencarian data peserta BPJS Kesehatan <br>

            Method : GET <br>

            Format : Json <br>

            Content-Type: application/json; charset=utf-8 <br>

            Parameter 1 : Nomor Kartu <br>

            Parameter 2 : Tanggal Pelayanan/SEP - format : yyyy-MM-dd <br>

        </form>
      </div>
    </div>
    
    <!-- PAGE CONTENT ENDS -->
  </div><!-- /.col -->
</div><!-- /.row -->


