<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>

<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />

<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>

<script>

jQuery(function($) {  

  
  var disableDates = getLiburNasional(<?php echo date('Y')?>);

  $("#tgl_kunjungan").datepicker({
    autoclose: true,    
    todayHighlight: true,
    daysOfWeekDisabled: [0],
    format: 'yyyy-mm-dd',
    beforeShowDay: function(date){
        dmy = date.getDate() + "-" + (date.getMonth() + 1) + "-" + date.getFullYear();
        if(disableDates.indexOf(dmy) != -1){
            return false;
        }
        else{
            return true;
        }
    },
  }).on("change", function() {
    var str_selected_date = this.value;
    var selected_date = str_selected_date.split("/").join("-");
    var spesialis = $('#klinik_rajal').val();
    var dokter = $('#dokter_rajal').val();
    var jd_id = $('#jd_id').val();
    /*check selected date */

    $.post('<?php echo site_url('Templates/References/CheckSelectedDate') ?>', {date:selected_date, kode_spesialis:spesialis, kode_dokter:dokter, jadwal_id:jd_id} , function(data) {
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


    $('#nama_pasien').focus();    

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
            PopupCenter(jsonResponse.redirect, 'SURAT KONTROL PASIEN', 850, 500);
            //window.open(jsonResponse.redirect, '_blank');
            $("#modalDaftarPerjanjian").modal('hide');
            $('#page-area-content').load('registration/Input_perjanjian?kode_bagian=<?php echo isset($_GET['kode_bagian'])?$_GET['kode_bagian']:''?>');

          },1800);


        }else{
                      $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
                    }        

        achtungHideLoader();        

      }      

    });     

      
    $('select[name="jenis_instalasi"]').change(function () {      

        if ($(this).val()) {          

          /*load modul*/

          $('#change_modul_view_perjanjian').load('registration/Reg_pasien/show_modul/'+$(this).val());
          $('#tgl_kunjungan_form').hide('fast');
          //$("html, body").animate({ scrollTop: "700px" }, "slow");  

        } else {          

          /*Eksekusi jika salah*/
          $('#tgl_kunjungan_form').hide('fast');

        }        

    });

    $('#perusahaan').typeahead({
        source: function (query, result) {
            $.ajax({
                url: "templates/References/getPerusahaan",
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
          if(val_item == 120){
            $('#div_no_rujukan').show();
            $('#div_no_sep_lama').show();
          }else{
            $('#div_no_rujukan').hide();
            $('#div_no_sep_lama').hide();
          }
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

    $('#nama_pasien').typeahead({
        source: function (query, result) {
            $.ajax({
                url: "templates/References/getPasien",
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
          var label_item=item.split(':')[1];
          var val_item=item.split(':')[0];
          console.log(val_item);
          $('#nama_pasien').val(label_item);
          $('#no_mr').val(val_item);
          $('#is_no_mr').val('N');
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
    </div><!-- /.page-header -->

    <!-- div.dataTables_borderWrap -->

    <div>    

    <div id="user-profile-1" class="user-profile row">
      
      <div class="col-xs-12 col-sm-12">
        

        <form class="form-horizontal" method="post" id="form_booking" action="<?php echo site_url('registration/Reg_pasien/process_perjanjian')?>" enctype="multipart/form-data" autocomplete="off">   

        <!-- hidden form  -->
        <input type="hidden" name="jd_id" id="jd_id">
        <input type="hidden" name="selected_day" id="selected_day">
        <input type="hidden" name="selected_time" id="selected_time">
        <input type="hidden" name="time_start" id="time_start">
        <input type="hidden" name="id_tc_pesanan" id="id_tc_pesanan" value="<?php echo isset($booking_id)?$booking_id:''?>">
        <input type="hidden" name="is_no_mr" id="is_no_mr" value="Y">
        <input type="hidden" name="no_mr" id="no_mr" value="">
        <input type="hidden" name="jenis_perjanjian" id="jenis_perjanjian" value="0">

        <p><b>DATA PASIEN</b></p>
        <div class="form-group">
            <label class="control-label col-sm-2">Nama Pasien</label>
            <div class="col-md-6">
              <input type="text" name="nama_pasien" id="nama_pasien" class="form-control" style="width:75%;display:inline; margin-left: 10px" value="<?php echo isset($value)?$value->nama:''?>" <?php echo ($flag=='read')?'readonly':''?>>
              <span style="display:inline;float:left;width:18%;">
                <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'gelar_nama')), isset($value->title)?$value->title:'Tn.'  , 'gelar_nama', 'gelar_nama', 'form-control', '', '') ?> 
              </span>
            </div>
        </div>

        <div class="form-group">
            <label class="control-label col-sm-2">Alamat</label>
            <div class="col-md-3">
              <textarea name="alamat" class="form-control" style="height:50px !important" <?php echo ($flag=='read')?'readonly':''?>><?php echo isset($value)?$value->almt_ttp_pasien:''?></textarea>
            </div>
        </div>

        <div class="form-group" style="margin-top: 3px">
            <label class="control-label col-sm-2">Telp Rumah</label>
            <div class="col-md-2">
              <input type="text" name="no_telp" id="no_telp" class="form-control" value="<?php echo isset($value)?($value->no_telp!=0 || $value->no_telp!='' )?$value->no_telp:'':'' ?>" <?php echo ($flag=='read')?'readonly':''?> >
            </div>
            <label class="control-label col-sm-1">HP</label>
            <div class="col-md-2">
              <input type="text" name="no_hp" id="no_hp" class="form-control" value="<?php echo isset($value->no_hp)?$value->no_hp:''; ?>" >
            </div>
        </div>
        
        <div class="form-group">
          <label class="control-label col-sm-2">Jenis Penjamin</label>
          <div class="col-md-8">
            <div class="radio">
                  <label>
                    <input name="jenis_penjamin" type="radio" class="ace" value="Jaminan Perusahaan" />
                    <span class="lbl"> Jaminan Perusahaan</span>
                  </label>
                  <label>
                    <input name="jenis_penjamin" type="radio" class="ace" value="Umum" />
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

        <div class="form-group" id="div_no_rujukan" style="display: none">
          <label class="control-label col-sm-2">No Rujukan</label>
          <div class="col-sm-3">
              <input id="no_rujukan" name="no_rujukan" class="form-control"  type="text" placeholder="" value=""/>
          </div>
        </div>

        <div class="form-group" id="div_no_sep_lama" style="display: none">
          <label class="control-label col-sm-2">No SEP Referensi</label>
          <div class="col-sm-3">
          <input id="no_sep_lama" name="no_sep_lama" class="form-control"  type="text" placeholder="" value=""/>
          </div>
        </div>

        <p style="margin-top:5px"><b><i class="fa fa-ambulance"></i> PILIH INSTALASI </b></p>
          <div class="form-group">
              <label class="control-label col-sm-2">Instalasi</label>
              <div class="col-md-3">
                <select name="jenis_instalasi" id="jenis_instalasi" class="form-control">
                  <option>-Silahkan Pilih-</option>
                  <option value="RJ">Rawat Jalan</option>
                  <option value="PM">Penunjang Medis</option>
                  <option value="BD">Bedah</option>
                </select>
              </div>
          </div>

          <div id="change_modul_view_perjanjian"> </div>
            
          <!-- end change modul view -->

          <div id="tgl_kunjungan_form" style="display:none">
          
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

