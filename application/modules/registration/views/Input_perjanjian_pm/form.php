<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />

<script>

$(document).ready(function(){


    $('#nama_pasien').focus();    
    $('#change_modul_view_perjanjian').load('registration/Reg_pasien/show_modul/PM');

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
            // PopupCenter(jsonResponse.redirect, 'SURAT KONTROL PASIEN', 850, 500);
            // $('#page-area-content').load('registration/Input_perjanjian_pm');
            $("#globalModalView").modal('hide');
            reload_table();
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
            $('#div_no_sep_lama').show();
          }else{
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
        <input type="hidden" name="id_tc_pesanan" id="id_tc_pesanan" value="<?php echo isset($value->id_tc_pesanan)?$value->id_tc_pesanan:''?>">
        <input type="hidden" name="no_kunjungan" id="no_kunjungan" value="<?php echo isset($no_kunjungan)?$no_kunjungan: $value->referensi_no_kunjungan?>">
        <input type="hidden" name="is_no_mr" id="is_no_mr" value="N">
        <input type="hidden" name="no_mr" id="no_mr" value="<?php echo isset($_GET['no_mr'])?$_GET['no_mr']:$value->no_mr?>">

        <p><b>DATA PASIEN</b></p>
        <div class="form-group">
            <label class="control-label col-sm-2">Nama Pasien</label>
            <div class="col-md-6">
              <input type="text" name="nama_pasien" id="nama_pasien" class="form-control" style="width:75%;display:inline; margin-left: 10px" value="<?php echo isset($pasien->nama_pasien)?$pasien->nama_pasien:''?>" >
              <span style="display:inline;float:left;width:18%;">
                <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'gelar_nama')), isset($value->title)?$value->title:'Tn.'  , 'gelar_nama', 'gelar_nama', 'form-control', '', '') ?> 
              </span>
            </div>
        </div>

        <div class="form-group" style="margin-top: 3px">
            <label class="control-label col-sm-2">Telp Rumah</label>
            <div class="col-md-2">
              <input type="text" name="no_telp" id="no_telp" class="form-control" value="<?php echo isset($value)?($value->no_telp!=0 || $value->no_telp!='' )?$value->no_telp:'':'' ?>"  >
            </div>
            <label class="control-label col-sm-1">No HP</label>
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

        <div class="form-group" id="div_no_sep_lama" style="display: none">
          <label class="control-label col-sm-2">No SEP Referensi</label>
          <div class="col-sm-3">
              <input id="no_sep_lama" name="no_sep_lama" class="form-control"  type="text" placeholder="" value=""/>
          </div>
        </div>

        
        <div class="clearfix"></div>
        <p><b><i class="fa fa-ambulance"></i> PILIH INSTALASI </b></p>
        <div class="form-group">
            <label class="control-label col-sm-2">Instalasi</label>
            <div class="col-md-3">
              <select name="jenis_instalasi" id="jenis_instalasi" class="form-control">
                <option>-Silahkan Pilih-</option>
                <option value="PM" selected>Penunjang Medis</option>
              </select>
            </div>
        </div>

        <div id="change_modul_view_perjanjian"> </div>
            
      </form>

      </div>

    </div>

    

    </div>

  </div><!-- /.col -->

</div><!-- /.row -->

