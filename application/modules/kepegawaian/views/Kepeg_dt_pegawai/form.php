<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>

<script>
jQuery(function($) {

  $('.date-picker').datepicker({
    autoclose: true,
    todayHighlight: true,
    dateFormat: 'yy-mm-dd',
  })
  //show datepicker when clicking on the icon
  .next().on(ace.click_event, function(){
    $(this).prev().focus();
  });
});

$(document).ready(function(){
  
    $('#form_kepeg_dt_pegawai').ajaxForm({
      beforeSend: function() {
        achtungShowLoader();  
      },
      uploadProgress: function(event, position, total, percentComplete) {
      },
      complete: function(xhr) {     
        var data=xhr.responseText;
        var jsonResponse = JSON.parse(data);

        if(jsonResponse.status === 200){
          $.achtung({message: jsonResponse.message, timeout:5});
          $('#page-area-content').load('kepegawaian/Kepeg_dt_pegawai?_=' + (new Date()).getTime());
        }else{
          $.achtung({message: jsonResponse.message, timeout:5});
        }
        achtungHideLoader();
      }
    }); 

    $('#inputKecamatan').typeahead({
    source: function (query, result) {
        $.ajax({
            url: "Templates/References/getDistricts",
            data: 'keyword=' + query ,         
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

      if (val_item) {          

        $('#provinsiHidden').val('');
        $('#inputProvinsi').val('');
        $('#kotaHidden').val('');
        $('#inputKota').val('');           

        $.getJSON("<?php echo site_url('templates/References/getDistrictsById') ?>/" + val_item, '', function (data) {  
          
          $('#provinsiHidden').val(data.province_id);
          $('#inputProvinsi').val(data.province_name);
          $('#kotaHidden').val(data.regency_id);
          $('#inputKota').val(data.regency_name);           

        }); 
        $('#kecamatanHidden').val(val_item);
        $('#prov').show('fast');
        $('#village').show('fast'); 
      }      
    }
  });

  $('#inputKelurahan').typeahead({
    source: function (query, result) {
        $.ajax({
            url: "Templates/References/getVillage",
            data: 'keyword=' + query + '&district=' + $('#kecamatanHidden').val(),             
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
      $('#kelurahanHidden').val(val_item);

       if (val_item) {          

          $.getJSON("<?php echo site_url('templates/References/getVillagesById') ?>/" + val_item, '', function (data) {                        

            $.each(data, function (i, o) {                  

                console.log(o)
                $('#zipcode').val(o.kode_pos);

            });            

          }); 
        }      
    }
  });

  $('#inputTmpLhr').typeahead({
    source: function (query, result) {
        $.ajax({
            url: "Templates/References/getDistricts",
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
          <form class="form-horizontal" method="post" id="form_kepeg_dt_pegawai" action="<?php echo site_url('kepegawaian/Kepeg_dt_pegawai/process')?>" enctype="multipart/form-data" autocomplete="off">
            <br>

            <!-- biodata pegawai -->

            <p><b>DATA PRIBADI</b></p>
            <div class="form-group">
              <label class="control-label col-md-2">NIK</label>
              <div class="col-md-2">
                <input name="nik" id="nik" value="<?php echo isset($value->ktp_nik)?$value->ktp_nik:''?>" class="form-control" type="text">
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-2">Nama Lengkap</label>
              <div class="col-md-3">
                <input name="nik" id="nik" value="<?php echo isset($value->ktp_nik)?$value->ktp_nik:''?>" class="form-control" type="text">
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-2">Tempat Lahir</label>
              <div class="col-md-3">
                <input name="nik" id="inputTmpLhr" value="<?php echo isset($value->ktp_nik)?$value->ktp_nik:''?>" class="form-control" type="text">
              </div>
              <label class="control-label col-md-1">Tgl Lahir</label>
              <div class="col-md-2">
                <div class="input-group">
                    <input name="dob_pasien" id="dob_pasien" value="<?php echo isset($value)?$this->tanggal->formatDateForm($value->tgl_lhr):''?>"  class="form-control date-picker" type="text">
                    <span class="input-group-addon">
                    <i class="ace-icon fa fa-calendar"></i>
                    </span>
                </div>
              </div>
            </div>

            <div class="form-group" style="padding-bottom: 3px">
              <label class="control-label col-md-2">Alamat</label>
              <div class="col-md-4">
              <textarea name="alamat" class="form-control" <?php echo ($flag=='read')?'readonly':''?> style="height:50px !important"><?php echo isset($value)?$value->alamat:''?></textarea>
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-2">RT/RW</label>
              <div class="col-md-1">
                <input name="name" id="name" style="text-align: center" value="<?php echo isset($value)?$value->name:''?>" placeholder="" class="form-control" type="text" <?php echo ($flag=='read')?'readonly':''?> >
              </div>
              <div class="col-md-1">
                <input name="name" id="name" style="text-align: center" value="<?php echo isset($value)?$value->name:''?>" placeholder="" class="form-control" type="text" <?php echo ($flag=='read')?'readonly':''?> >
              </div>
            </div>

            <div class="form-group">

            <div id="prov" <?php echo isset($value) ?'':'style="display:none"'; ?>>
              <label class="control-label col-md-2">Provinsi</label>

              <div class="col-md-3">
                  <input id="inputProvinsi" style="margin-left:-9px;margin-bottom:3px;" class="form-control" name="provinsi" type="text" placeholder="Masukan keyword minimal 3 karakter" value="<?php echo isset($value)?($value->id_dc_propinsi!=null)?"$value->id_dc_propinsi : $value->provinsi":'':''?>" <?php echo ($flag=='read')?'readonly':''?>/>
                  <input type="hidden" name="provinsiHidden" value="<?php echo isset($value)?$value->id_dc_propinsi:'' ?>" id="provinsiHidden">
              </div>


              <label class="control-label col-md-2" style="margin-left:-13px;">Kota / Kabupaten</label>

              <div class="col-md-3">
                  <input id="inputKota" style="margin-left:-9px" class="form-control" name="kota" type="text" placeholder="Masukan keyword minimal 3 karakter" value="<?php echo isset($value)?($value->id_dc_kota!=null)?"$value->id_dc_kota : $value->kota":'':''?>" <?php echo ($flag=='read')?'readonly':''?>/>
                  <input type="hidden" name="kotaHidden" value="<?php echo isset($value)?$value->id_dc_kota:'' ?>" id="kotaHidden">
              </div>
            </div>

          </div>

          <div class="form-group">
            
            <label class="control-label col-md-2">Kecamatan</label>

            <div class="col-md-3">
                <input id="inputKecamatan" class="form-control" name="kecamatan" type="text" placeholder="Masukan keyword minimal 3 karakter" value="<?php echo isset($value)?($value->id_dc_kecamatan!=null)?"$value->id_dc_kecamatan : $value->kecamatan":'':''?>"  <?php echo ($flag=='read')?'readonly':''?>/>
                <input type="hidden" name="kecamatanHidden" value="<?php echo isset($value)?$value->id_dc_kecamatan:''?>" id="kecamatanHidden">
            </div>
            

            <div id="village" <?php echo isset($value) ?'':'style="display:none"'; ?>>
              <label class="control-label col-md-2">Kelurahan</label>

              <div class="col-md-3">
                  <input id="inputKelurahan" style="margin-left:-9px" class="form-control" name="kelurahan" type="text" placeholder="Masukan keyword minimal 3 karakter" value="<?php echo isset($value)?($value->id_dc_kelurahan!=null)?"$value->id_dc_kelurahan : $value->kelurahan":'':''?>" <?php echo ($flag=='read')?'readonly':''?>/> 
                  <input type="hidden" name="kelurahanHidden" value="<?php echo isset($value)?$value->id_dc_kelurahan:''?>" id="kelurahanHidden">
              </div>
            </div>

          </div>
          
          <div class="form-group">

            <label class="control-label col-md-2">Kode Pos</label>

            <div class="col-md-2">
                <input id="zipcode" class="form-control" name="zipcode" type="text" value="<?php echo isset($value)?$value->kode_pos:''?>" <?php echo ($flag=='read')?'readonly':''?>/>
            </div>

          </div>

          <div class="form-group">

            <label class="control-label col-md-2">Jenis Kelamin</label>

            <div class="col-md-2">
            
              <?php echo $this->master->custom_selection($params = array('table' => 'mst_gender', 'id' => 'gender_id', 'name' => 'gender_name', 'where' => array()), isset($value)?($value->jen_kelamin=='L')?1:2:'' , 'gender', 'gender', 'form-control', '', '') ?> 

            </div>

            <label class="control-label col-md-2">Golongan Darah</label>

            <div class="col-md-2">

              <?php echo $this->master->custom_selection($params = array('table' => 'mst_type_blood', 'id' => 'tb_id', 'name' => 'tb_name', 'where' => array()), isset($value)?$value->id_dc_gol_darah:'' , 'type_blood', 'type_blood', 'form-control', '', '') ?> 

            </div>

          </div>


          <div class="form-group" id="data_dewasa">

            <label class="control-label col-md-2">Status Perkawinan</label>

            <div class="col-md-2">

              <?php echo $this->master->custom_selection($params = array('table' => 'mst_marital_status', 'id' => 'ms_id', 'name' => 'ms_name', 'where' => array()), isset($value)?$value->id_dc_kawin:'' , 'marital_status', 'marital_status', 'form-control', '', '') ?> 

            </div>

            <label class="control-label col-md-2">Agama</label>

            <div class="col-md-2">

              <?php echo $this->master->custom_selection($params = array('table' => 'mst_religion', 'id' => 'religion_id', 'name' => 'religion_name', 'where' => array()), isset($value)?$value->id_dc_agama:'' , 'religion', 'religion', 'form-control', '', '') ?> 

            </div>

          </div>

          <div class="form-group">

            <label class="control-label col-md-2">Telp/HP</label>

            <div class="col-md-2">
              <input type="text" name="tlp_almt_ttp" id="tlp_almt_ttp" class="form-control" value="<?php echo isset($value)?($value->tlp_almt_ttp!=0 || $value->tlp_almt_ttp!='' )?$value->tlp_almt_ttp:'':'' ?>" <?php echo ($flag=='read')?'readonly':''?> >
            </div>

            <label class="control-label col-md-2">HP</label>

            <div class="col-md-2">
              <input type="text" name="telp_pasien" id="telp_pasien" class="form-control" value="<?php echo isset($value->no_hp)?$value->no_hp:''; ?>" >
            </div>


          </div>

            
            <div class="form-group">
              <label class="control-label col-md-2">Is active?</label>
              <div class="col-md-2">
                <div class="radio">
                      <label>
                        <input name="is_active" type="radio" class="ace" value="Y" <?php echo isset($value) ? ($value->is_active == 'Y') ? 'checked="checked"' : '' : 'checked="checked"'; ?> <?php echo ($flag=='read')?'readonly':''?> />
                        <span class="lbl"> Ya</span>
                      </label>
                      <label>
                        <input name="is_active" type="radio" class="ace" value="N" <?php echo isset($value) ? ($value->is_active == 'N') ? 'checked="checked"' : '' : ''; ?> <?php echo ($flag=='read')?'readonly':''?>/>
                        <span class="lbl">Tidak</span>
                      </label>
                </div>
              </div>
            </div>

            <div class="form-group">
              <label class="control-label col-md-2">Last update</label>
              <div class="col-md-8" style="padding-top:8px;font-size:11px">
                  <i class="fa fa-calendar"></i> <?php echo isset($value->updated_date)?$this->tanggal->formatDateTime($value->updated_date):isset($value)?$this->tanggal->formatDateTime($value->created_date):date('d-M-Y H:i:s')?> - 
                  by : <i class="fa fa-user"></i> <?php echo isset($value->updated_by)?$value->updated_by:isset($value->created_by)?$value->created_by:$this->session->userdata('user')->username?>
              </div>
            </div>


            <div class="form-actions center">

              <!--hidden field-->
              <!-- <input type="text" name="id" value="<?php echo isset($value)?$value->level_id:0?>"> -->

              <a onclick="getMenu('kepegawaian/Kepeg_dt_pegawai')" href="#" class="btn btn-sm btn-success">
                <i class="ace-icon fa fa-arrow-left icon-on-right bigger-110"></i>
                Kembali ke daftar
              </a>
              <?php if($flag != 'read'):?>
              <button type="reset" id="btnReset" class="btn btn-sm btn-danger">
                <i class="ace-icon fa fa-close icon-on-right bigger-110"></i>
                Reset
              </button>
              <button type="submit" id="btnSave" name="submit" class="btn btn-sm btn-info">
                <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>
                Submit
              </button>
            <?php endif; ?>
            </div>
          </form>
        </div>
      </div>
    
    <!-- PAGE CONTENT ENDS -->

  </div><!-- /.col -->
</div><!-- /.row -->


