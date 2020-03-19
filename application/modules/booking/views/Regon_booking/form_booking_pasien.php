<script src="<?php echo base_url().'assets/js/custom/als_datatable.js'?>"></script>

<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>

<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />

<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>

<script>

jQuery(function($) {  

  
  $("#tgl_kunjungan").datepicker({

    autoclose: true,    

    todayHighlight: true,

    onSelect: function(dateText) {
      $(this).change();
    }
  
  }).on("change", function() {
    
    var str_selected_date = this.value;
    var selected_date = str_selected_date.split("/").join("-");
    var spesialis = $('#klinik_rajal').val();
    var dokter = $('#dokter_rajal').val();
    var jd_id = $('#jd_id').val();
    /*check selected date */

    $.post('<?php echo WS_URL.'Templates/References/CheckSelectedDate' ?>', {date:selected_date, kode_spesialis:spesialis, kode_dokter:dokter, jadwal_id:jd_id} , function(data) {
        // Do something with the request
        if(data.status=='expired'){
           var message = '<div class="alert alert-danger"><strong>Expired Date !</strong><br>Tanggal yang anda pilih sudah lewat atau sedang berjalan.</div>';
           $('#view_msg_kuota').hide('fast');
        }else{
          if(data.day!=$('#selected_day').val() ){
                var message = '<div class="alert alert-danger"><strong>Tidak Sesuai !</strong><br>Tanggal Kunjungan tidak sesuai dengan jadwal Praktek Dokter yang anda pilih !</div>';
                $('#view_msg_kuota').hide('fast');
          }else{
            var message = '<div class="alert alert-block alert-success"><button type="button" class="close" data-dismiss="alert"><i class="ace-icon fa fa-times"></i></button><p><strong><i class="ace-icon fa fa-check"></i> Selesai ! </strong>Apakah anda akan melanjutkan ke proses berikutnya ?</p><p><button type="submit" id="btnSave" class="btn btn-sm btn-success">Lanjutkan</button><a href="#" onclick="getMenu('+"'"+'booking/regon_booking'+"'"+')" class="btn btn-sm btn-danger">Batalkan</a></p></div>';

            if(data.sisa > 0 ){
              var msg_kuota = '*Kuota tersedia pada tanggal ini, '+data.sisa+' pasien';
            }else{
              var msg_kuota = '<span style="color:red"> *Kuota penuh, silahkan cari tanggal lain!</span>';
            }

            $('#view_msg_kuota').show('fast');
            $('#view_msg_kuota').html(msg_kuota);

          }

        }

        $('#view_last_message').show('fast');
        $('#view_last_message').html(message);
        $("html, body").animate({ scrollTop: "700px" }, "slow");  


    }, 'json');

    
  
  });
 

});

$(document).ready(function(){


    $('#jenis_instalasi').focus();    

    $('#form_booking').ajaxForm({      

      beforeSend: function() {        

        achtungShowFadeIn();          

      },      

      uploadProgress: function(event, position, total, percentComplete) {        

      },      

      complete: function(xhr) {             

        var data=xhr.responseText;        

        var jsonResponse = JSON.parse(data);        

        if(jsonResponse.status === 200){          

          $.achtung({message: jsonResponse.message, timeout:5});          

          setTimeout(function(){

            /*window.open(jsonResponse.redirect, '_blank');*/
            $('#page-area-content').load(jsonResponse.redirect);

          },1800);


        }else{          

          $.achtung({message: jsonResponse.message, timeout:5});          

        }        

        achtungHideLoader();        

      }      

    });     

      
    $('select[name="jenis_instalasi"]').change(function () {      

        if ($(this).val()) {          

          /*load modul*/

          $('#change_modul_view').load('booking/Regon_booking/show_modul/'+$(this).val());

          $("html, body").animate({ scrollTop: "700px" }, "slow");  

        } else {          

          /*Eksekusi jika salah*/

        }        

    });

    $('#perusahaan').typeahead({
          source: function (query, result) {
              $.ajax({
                  url: "<?php echo WS_URL?>templates/References/getPerusahaan",
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
            $('#kodePerusahaanHidden').val(val_item);
          }

    });

    $('input[name=jenis_penjamin]').click(function(e){
        var field = $('input[name=jenis_penjamin]:checked').val();
        if ( field == 'Jaminan Perusahaan' ) {
          $('#showFormPerusahaan').show('fast');
        }else if (field == 'Umum') {
          $('#showFormPerusahaan').hide('fast');
        }
      });





})

function formatDate(date) {
  var hours = date.getHours();
  var minutes = date.getMinutes();
  var ampm = hours >= 12 ? 'pm' : 'am';
  hours = hours % 12;
  hours = hours ? hours : 12; // the hour '0' should be '12'
  minutes = minutes < 10 ? '0'+minutes : minutes;
  var strTime = hours + ':' + minutes + ' ' + ampm;
  return date.getMonth()+1 + "/" + date.getDate() + "/" + date.getFullYear();
}



</script>

<div class="row">

  <div class="col-xs-12">  

    <div class="page-header">    

      <h1>      

        <?php echo $title?>        

        <small>        

          <i class="ace-icon fa fa-angle-double-right"></i>          

          <?php echo isset($breadcrumbs)?$breadcrumbs:''?>          

        </small>        

      </h1>      

    </div>  

    <!-- div.dataTables_borderWrap -->

    <div>    

    <div id="user-profile-1" class="user-profile row">
      <div class="col-xs-12 col-sm-3 center">
        <div>
          <!-- #section:pages/profile.picture -->
          <span class="profile-picture">
          <img id="avatar" class="editable img-responsive editable-click editable-empty" style="width:100px" alt="" src="<?php echo isset($pasien->path_foto) ? base_url().PATH_PHOTO_PROFILE_DEFAULT.$pasien->path_foto:base_url().'assets/avatars/user.jpg'?>">
          </span>

          <!-- /section:pages/profile.picture -->
          <div class="space-4"></div>

          <div class="width-80 label label-info label-xlg arrowed-in arrowed-in-right">
            <div class="inline position-relative">
              <a href="#" class="user-title-label dropdown-toggle" data-toggle="dropdown">
                <i class="ace-icon fa fa-circle light-green"></i>
                &nbsp;
                <span class="white"><?php echo $pasien->fullname?></span>
              </a>
            </div>
          </div>

          <br>
          <br>

          <div class="profile-contact-info">
            <div class="profile-contact-links align-left">
              <address>
                <strong><?php echo $pasien->no_mr?></strong>
                <br>
                <?php echo $pasien->pob.', '.$this->tanggal->formatDate($pasien->dob)?>
                <br>
                <?php echo ucwords(strtolower($pasien->address))?>
                <br>
                <abbr title="Phone">P:</abbr>
                <?php echo $pasien->no_hp?>
              </address>
            </div>

          </div>

        </div>
      </div>

      <div class="col-xs-12 col-sm-9">
        

        <form class="form-horizontal" method="post" id="form_booking" action="<?php echo site_url('booking/regon_booking/process')?>" enctype="multipart/form-data">   

        <!-- hidden form  -->
        <input type="hidden" name="no_mr" value="<?php echo $pasien->no_mr?>" id="no_mr">
        <input type="hidden" name="jd_id" id="jd_id">
        <input type="hidden" name="selected_day" id="selected_day">
        <input type="hidden" name="selected_time" id="selected_time">
        <input type="hidden" name="time_start" id="time_start">

        <div class="form-group">

            <label class="control-label col-sm-2">*No.MR</label>

            <div class="col-sm-2">

                <input type="text" name="no_mr_show" class="form-control" id="no_mr_show" value="<?php echo $pasien->no_mr?>" readonly="">

            </div>

        </div>

        <div class="form-group">
          <label class="control-label col-sm-2">Jenis Penjamin</label>
          <div class="col-md-8">
            <div class="radio">
                  <label>
                    <input name="jenis_penjamin" type="radio" class="ace" value="Jaminan Perusahaan" <?php echo isset($value) ? ($value->regon_booking_jenis_penjamin == 'Jaminan Perusahaan') ? 'checked="checked"' : '' : ''; ?> <?php echo ($flag=='read')?'readonly':''?> />
                    <span class="lbl"> Jaminan Perusahaan</span>
                  </label>
                  <label>
                    <input name="jenis_penjamin" type="radio" class="ace" value="Umum" <?php echo isset($value) ? ($value->regon_booking_jenis_penjamin == 'Umum') ? 'checked="checked"' : '' : ''; ?> <?php echo ($flag=='read')?'readonly':''?>/>
                    <span class="lbl"> Umum</span>
                  </label>
            </div>
          </div>
        </div>

        <div class="form-group" id="showFormPerusahaan" style="display:none">

            <label class="control-label col-sm-2">Perusahaan</label>

            <div class="col-sm-6">

                <input id="perusahaan" name="perusahaan" class="form-control"  type="text" placeholder="Masukan keyword minimal 3 karakter" />
                <input id="kodePerusahaanHidden" name="kode_perusahaan" class="form-control"  type="hidden" />

            </div>

        </div>

        <p style="margin-top:5px"><b><i class="fa fa-ambulance"></i> PILIH INSTALASI </b></p>

          <div class="form-group">
              <label class="control-label col-sm-2">Instalasi</label>
              <div class="col-md-3">
                <select name="jenis_instalasi" id="jenis_instalasi" class="form-control">
                  <option>-Silahkan Pilih-</option>
                  <option value="RJ">Rawat Jalan</option>
                  <!-- <option value="3">Penunjang Medis</option>
                  <option value="5">MCU</option>
                  <option value="6">ODC</option>
                  <option value="7">Paket Bedah</option> -->
                </select>
              </div>
            
          </div>

          <div id="change_modul_view"> </div>
            
          <!-- end change modul view -->

          <div id="tgl_kunjungan_form" style="display:none;">
          
            <p><b><i class="fa fa-calendar"></i> TANGGAL KUNJUNGAN </b></p>

            <div class="form-group">
              
              <label class="control-label col-sm-2">Tanggal Kunjungan</label>
              
              <div class="col-md-4">
                
                <div class="input-group">
                    
                    <input name="tanggal_kunjungan" id="tgl_kunjungan" value="" placeholder="<?php echo $this->tanggal->formatDateForm(date('Y-m-d'))?>" class="form-control date-picker" type="text">
                    <span class="input-group-addon">
                      
                      <i class="ace-icon fa fa-calendar"></i>
                    
                    </span>
                  </div>

                  <small id="view_msg_kuota" style="margin-top:1px"></small>

              
              </div>

            </div>

            <div class="form-group">
              
              <label class="control-label col-sm-2">Keterangan</label>
              
              <div class="col-md-5">
                
                <textarea class="form-control" name="keterangan" style="height:50px !important"></textarea>

            </div>

          </div>

          <div id="view_last_message" style="margin-top:5px"></div>
          

      </form>

      </div>

    </div>

    

    </div>

  </div><!-- /.col -->

</div><!-- /.row -->

