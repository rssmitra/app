<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>

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
  
  $('#pob_pasien').typeahead({
    source: function (query, result) {
        $.ajax({
            url: "Templates/References/getRegenciesPob",
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
      var val_item=item.split(':')[1];
       
      $('#pob_pasien').val(item);    
 
    }
  });

  $('#InputKeyPenjamin_').typeahead({
      source: function (query, result) {
          $.ajax({
              url: "templates/references/getPerusahaan",
              data: { keyword:query },            
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
        $('#kode_perusahaan_').val(val_item);
        $('#kode_perusahaan_').val(val_item);
        if(val_item==120){
          $('#no_kartu_bpjs_div').show();
        }else{
          $('#no_kartu_bpjs_div').hide();
        }
      }
  });

  $('#inputProvinsi').typeahead({
      source: function (query, result) {
          $.ajax({
              url: "templates/References/getProvince",
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
        $('#provinsiHidden').val(val_item);
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

        $.getJSON("<?php echo site_url('templates/References/getDistrictsById') ?>/" + val_item, '', function (data) {                                       

          $('#provinsiHidden').val(data.province_id);
          $('#inputProvinsi').val(data.province_name);
          $('#kotaHidden').val(data.regency_id);
          $('#inputKota').val(data.regency_name);           
          /*reset kelurahan after searching */
          $('#inputKelurahan').val('');           
          $('#kelurahanHidden').val('');  
          $('#zipcode').val('');    

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
</script>

<br>
<!-- hidden form -->
<input type="hidden" value="<?php echo isset($value)?$value->no_mr:''?>" name="noMrHiddenPasien" id="noMrHiddenPasien">
<!-- <input type="hidden" name="kode_perusahaan_hidden" value="" id="kode_perusahaan_hidden">
<input type="hidden" name="kode_kelompok_hidden" value="" id="kode_kelompok_hidden">
<input type="hidden" name="umur_saat_pelayanan_hidden" value="" id="umur_saat_pelayanan_hidden">
<input type="hidden" name="nama_pasien_hidden" value="" id="nama_pasien_hidden">
<input name="noKartuBpjs" id="noKartuBpjs" class="form-control" type="hidden" value=""> -->
      
<div id="data_pribadi">
    <p><b><i class="fa fa-user"></i> DATA PRIBADI(*) </b></p>

    <div class="form-group">
      <label class="control-label col-md-2">Nama Pasien</label> 
      <div class="col-md-4">  
        <input type="text" name="nama_pasien" id="nama_pasien" class="form-control" style="width:73%;display:inline" value="<?php echo isset($value)?$value->nama_pasien:''?>" >
        <span style="display:inline;float:left;width:20%">
          <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'gelar_nama')), isset($value)?$value->title:''  , 'gelar_nama', 'gelar_nama', 'form-control', '', '') ?> 
        </span>
      </div>
      <label class="control-label col-md-1">Foto</label>
      <div class="col-md-3">      
        <input type="file" name="path_foto" class="form-control" id="photo_profile">
        <small>Maksimal File 100Kb</small>
      </div>
    </div>

    <div class="form-group" >
      <label class="control-label col-md-2">NIK</label> 
      <div class="col-md-3">   
        <input type="text" name="nik_pasien" id="nik" class="form-control" value="<?php echo isset($value)?$value->no_ktp:''?>" >
      </div>
    </div>

    <div class="form-group" >
      <label class="control-label col-md-2">Tempat Lahir</label>  
      <div class="col-md-3">   
        <input id="pob_pasien" name="pob_pasien" class="form-control" name="pob" type="text" placeholder="Masukan keyword minimal 3 karakter" value="<?php echo isset($value)?$value->tempat_lahir:''?>" />
      </div>
      <label class="control-label col-md-2">Tanggal Lahir</label>
      <div class="col-md-3">
          <div class="input-group">
              <input name="dob_pasien" id="dob_pasien" value="<?php echo isset($value)?$this->tanggal->formatDateForm($value->tgl_lhr):''?>"  class="form-control date-picker" type="text">
              <span class="input-group-addon">
              <i class="ace-icon fa fa-calendar"></i>
              </span>
          </div>
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-md-2">Tipe Pasien</label>
      <div class="col-md-5">
        <div class="radio">
            <label>
              <input name="tipe_pasien_baru" type="radio" class="ace" value="dewasa" checked="checked"/>
              <span class="lbl"> Dewasa </span>
            </label>
            <label>
              <input name="tipe_pasien_baru" type="radio" class="ace" value="bayi" />
              <span class="lbl"> Bayi </span>
            </label>
        </div>
      </div>
    </div>

    <div id="data_bayi" <?php echo isset($value) ? ($value->pekerjaan_ayah != NULL) ? '' : 'style="display:none"' : 'style="display:none"'; ?>>
      <div class="form-group">
        <label class="control-label col-md-2">Nama Ayah</label> 
        <div class="col-md-2">            
          <div class="input-group">
            <input type="text" name="nama_ayah_pasien" id="nama_ayah_pasien" class="form-control" value="<?php echo isset($value)?$value->nama_ayah:''?>" >
          </div>
        </div>
        <label class="control-label col-md-2">Nama Ibu</label>    
        <div class="col-md-2">            
          <div class="input-group">
            <input type="text" name="nama_ibu_pasien" id="nama_ibu_pasien" class="form-control" value="<?php echo isset($value)?$value->nama_ibu:''?>" >
          </div>
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-md-2">Pekerjaan Ayah</label>
        <div class="col-md-4">
          <?php echo $this->master->custom_selection($params = array('table' => 'mst_job', 'id' => 'job_id', 'name' => 'job_name', 'where' => array()), isset($value)?$value->pekerjaan_ayah:'' , 'job', 'job', 'form-control', '', '') ?> 
        </div>
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-md-2">Alamat</label>
      <div class="col-md-3">
        <textarea name="alamat_pasien" class="form-control" style="height:50px !important" ><?php echo isset($value)?$value->almt_ttp_pasien:''?></textarea>
      </div>      
    </div>

    <div class="form-group">
        <div id="prov" <?php echo isset($value->id_dc_propinsi) ?'':'style="display:none"'; ?>>
          <label class="control-label col-md-2">Provinsi</label>
          <div class="col-md-3">
              <input id="inputProvinsi" style="margin-left:-9px;margin-bottom:3px;" class="form-control" name="provinsi" type="text" placeholder="Masukan keyword minimal 3 karakter" value="<?php echo isset($value)?($value->id_dc_propinsi!=null)?"$value->id_dc_propinsi : $value->provinsi":'':''?>" readonly/>
              <input type="hidden" name="provinsiHidden" value="<?php echo isset($value)?$value->id_dc_propinsi:'' ?>" id="provinsiHidden">
          </div>
          <label class="control-label col-md-2" style="margin-left:-13px;">Kota / Kabupaten</label>
          <div class="col-md-3">
              <input id="inputKota" style="margin-left:-9px" class="form-control" name="kota" type="text" placeholder="Masukan keyword minimal 3 karakter" value="<?php echo isset($value)?($value->id_dc_kota!=null)?"$value->id_dc_kota : $value->kota":'':''?>" readonly/>
              <input type="hidden" name="kotaHidden" value="<?php echo isset($value)?$value->id_dc_kota:'' ?>" id="kotaHidden">
          </div>
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-md-2">Kecamatan</label>
        <div class="col-md-3">
            <input id="inputKecamatan" class="form-control" name="kecamatan" type="text" placeholder="Masukan keyword minimal 3 karakter" value="<?php echo isset($value)?($value->id_dc_kecamatan!=null)?"$value->id_dc_kecamatan : $value->kecamatan":'':''?>"  />
            <input type="hidden" name="kecamatanHidden" value="<?php echo isset($value)?$value->id_dc_kecamatan:''?>" id="kecamatanHidden">
        </div>
        
        <div id="village" <?php echo isset($value->id_dc_kelurahan) ?'':'style="display:none"'; ?>>
          <label class="control-label col-md-2">Kelurahan</label>
          <div class="col-md-3">
              <input id="inputKelurahan" style="margin-left:-9px" class="form-control" name="kelurahan" type="text" placeholder="Masukan keyword minimal 3 karakter" value="<?php echo isset($value)?($value->id_dc_kelurahan!=null)?"$value->id_dc_kelurahan : $value->kelurahan":'':''?>" /> 
              <input type="hidden" name="kelurahanHidden" value="<?php echo isset($value)?$value->id_dc_kelurahan:''?>" id="kelurahanHidden">
          </div>
        </div>
      </div>

    <div class="form-group">
      <label class="control-label col-md-2">Kode Pos</label>
      <div class="col-md-2">
          <input id="zipcode" class="form-control" name="zipcode" type="text" value="<?php echo isset($value)?$value->kode_pos:''?>" />
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

    <div class="form-group" id="data_dewasa">

      <label class="control-label col-md-2">Suku *</label>

      <div class="col-md-2">

        <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'suku')), isset($value)?$value->suku:'' , 'suku', 'suku', 'form-control', '', '') ?> 

      </div>

      <label class="control-label col-md-2">Kewarganegaraan *</label>

      <div class="col-md-2">

      <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'kewarganegaraan')), isset($value)?$value->kebangsaan:'' , 'kebangsaan', 'kebangsaan', 'form-control', '', '') ?> 

      </div>

    </div>

    <div class="form-group">
      <label class="control-label col-md-2">Pendidikan Terakhir *</label>
      <div class="col-md-2">
        <?php echo $this->master->custom_selection($params = array('table' => 'mst_education', 'id' => 'education_id', 'name' => 'education_name', 'where' => array()), isset($value)?$value->kode_pendidikan:'' , 'kode_pendidikan', 'kode_pendidikan', 'form-control', '', '') ?> 
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-md-2">Telp</label>
      <div class="col-md-2">
        <input type="text" name="tlp_almt_ttp" id="tlp_almt_ttp" class="form-control" value="<?php echo $value->tlp_almt_ttp; ?>" >
      </div>
      <label class="control-label col-md-2">HP</label>
      <div class="col-md-2">
        <input type="text" name="telp_pasien" id="telp_pasien" class="form-control" value="<?php echo $value->no_hp; ?>" >
      </div>
    </div>

    <div class="form-group">
      <label class="control-label col-md-2">Catatan Pasien</label>
      <div class="col-md-4">
        <textarea name="keterangan_pasien" class="form-control" style="height:50px !important" ><?php echo isset($value)?$value->keterangan:''?></textarea>
      </div>
    </div>
</div>
<br>

<div id="data_kepemilikan">
    <p><b><i class="fa fa-credit-card"></i> DATA KEPEMILIKAN </b></p>

    <div class="form-group">
      <label class="control-label col-md-2">*Nasabah</label>
      <div class="col-md-4">
        <?php echo $this->master->custom_selection($params = array('table' => 'mt_nasabah', 'id' => 'kode_kelompok', 'name' => 'nama_kelompok', 'where' => array()), isset($value)?$value->kode_kelompok:'' , 'kelompok_pasien', 'kelompok_pasien', 'form-control', '', '') ?> 
      </div>
    </div>

    <div class="form-group" id="kode_perusahaan" style="<?php isset($value)?$value->kode_kelompok:'' ?>">
      <label class="control-label col-md-2" for="Province">*Nama Perusahaan</label>
      <div class="col-sm-4">
          <input id="InputKeyPenjamin_" class="form-control" name="penjamin" type="text" placeholder="Masukan keyword minimal 3 karakter" value="<?php echo isset($value)?($value->kode_perusahaan!=null)?"$value->kode_perusahaan : $value->nama_perusahaan":'':''?>"/>
          <input type="hidden" name="kode_perusahaan" value="<?php echo isset($value)?$value->kode_perusahaan:''?>" id="kode_perusahaan_">
      </div>
    </div>

    <div class="form-group" id="no_kartu_bpjs_div" style="<?php echo isset($value)?($value->kode_perusahaan==120)?'':'display:none':'display:none' ?>">
      <label class="control-label col-md-2" for="Province">*No Kartu BPJS</label>
      <div class="col-sm-2">
          <input id="no_kartu_bpjs" class="form-control" name="no_kartu_bpjs" type="text" placeholder="Masukan No Kartu BPJS" value="<?php echo isset($value)?($value->no_kartu_bpjs!=null)?"$value->no_kartu_bpjs":'':''?>"/>
      </div>
    </div>

    <div id="member" style="display:none;">
      <div class="form-group">
        <label class="control-label col-md-2">No Member</label>
        <div class="col-md-2">
          <input type="text" name="no_member" id="no_member" class="form-control">
        </div>
        <label class="control-label col-md-2">*Jatah Kelas</label>
        <div class="col-md-4">
          <?php echo $this->master->custom_selection($params = array('table' => 'mt_klas', 'id' => 'kode_klas', 'name' => 'nama_klas', 'where' => array()), '' , 'jatah_klas', 'jatah_klas', 'form-control', '', '') ?> 
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-md-2">Kepemilikan</label>
        <div class="col-md-4">
          <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'hubungan_keluarga')), '' , 'kepemilikan', 'kepemilikan', 'form-control', '', '') ?> 
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-md-2">Tanggal Mulai</label>
        <div class="col-md-3">
            <div class="input-group">
                <input name="tgl_mulai_kepemilikan" id="tgl_mulai_kepemilikan" value=""  class="form-control date-picker" type="text">
                <span class="input-group-addon">
                <i class="ace-icon fa fa-calendar"></i>
                </span>
            </div>
        </div>

        <label class="control-label col-md-2">Tanggal Berakhir</label>
        <div class="col-md-3">
            <div class="input-group">
                <input name="tgl_akhir_kepemilikan" id="tgl_akhir_kepemilikan" value=""  class="form-control date-picker" type="text">
                <span class="input-group-addon">
                <i class="ace-icon fa fa-calendar"></i>
                </span>
            </div>
        </div>
      </div>

    </div> <!--end member-->

    <div class="form-group" id="karyawan" style="display:none;">
      <label class="control-label col-md-2">*Bagian</label>
      <div class="col-md-4">
        <?php echo $this->master->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array()), '' , 'bagian_karyawan', 'bagian_karyawan', 'form-control', '', '') ?> 
      </div>
    </div>
</div>
    






<!-- end form create SEP