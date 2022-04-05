<script src="<?php echo base_url().'assets/js/custom/als_datatable.js'?>"></script>

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

  $(document).ready(function(){

      $('input[name="tipe_pasien_baru"]').click(function (e) {
        var value = $(this).val();
        if (value=='dewasa') {
          $('#data_bayi').hide('fast');
          $('#data_dewasa').show('fast');
        }

        if (value=='bayi') {
          $('#data_bayi').show('fast');
          $('#data_dewasa').hide('fast');
        }

      }); 

      $('#decline_warning').click(function (e) {   
        if (($(this).is(':checked'))) {
          $('#div_load_after_selected_pasien').show('fast');
        }  else{
          $('#div_load_after_selected_pasien').hide('fast');
        }
      });

      /*declare*/
      var kode_booking_val = $("#form_cari_pasien_by_kode_booking_id").val();

      $('#register_now_btn_id').click(function (e) {     
        $('#div_load_after_selected_pasien').show('fast');
        $('#div_riwayat_pasien').show('fast');
      });

  })

  $('#form_registration').ajaxForm({      

  beforeSend: function() {        

    achtungShowFadeIn();      
    $("input[type=submit]").attr("disabled", "disabled");    

  },      

  uploadProgress: function(event, position, total, percentComplete) {        

  },      

  complete: function(xhr) {             

    var data=xhr.responseText;        

    var jsonResponse = JSON.parse(data);        

    if(jsonResponse.status === 200){          

        $.achtung({message: jsonResponse.message, timeout:5});          

        //   breadcrumb
        $('#breadcrumb_nama_pasien').text(jsonResponse.nama_pasien+' ('+jsonResponse.jen_kelamin+')');
        $('#breadcrumb_description').text(jsonResponse.no_mr+' | '+jsonResponse.almt_ttp_pasien+' | '+getFormattedDate(jsonResponse.tgl_lhr)+'');
        /*show action after success submit form*/
        $('#form-create-pasien').html('<div class="center" style="padding-top: 20px"><span style="font-size: 36px; font-weight: bold; color: green"><i class="fa fa-check-circle green bigger-250"></i><br>PENDAFTARAN BERHASIL DILAKUKAN!</span><br><span style="font-size: 20px">Nomor Rekam Medis Anda <b>'+jsonResponse.no_mr+'</b></span><br><span style="font-size: 16px">Simpan Nomor Rekam Medis Anda Untuk Pendaftaran Pasien Berikutnya.</span></div><br><div class="center"><a href="#" class="btn btn-lg" style="background : green !important; border-color: green" onclick="getMenu('+"'kiosk/Kiosk/main'"+')">Lanjutkan ke Menu Utama <i class="fa fa-arrow-right"></i></a></div>');

        $.getJSON("<?php echo site_url('Templates/References/search_pasien') ?>?keyword=" + jsonResponse.no_mr, '', function (data) {      

          // jika data ditemukan
            
            var obj = data.result[0];
            $('#noKartuBpjs').val(obj.no_kartu_bpjs);

            penjamin = (obj.nama_perusahaan==null)?obj.nama_kelompok:obj.nama_perusahaan;
            kelompok = (obj.nama_kelompok==null)?'-':obj.nama_kelompok;

            // value
            $('#nama_pasien').val(obj.nama_pasien);
            $('#no_mr_val').val(obj.no_mr);
            var umur_pasien = hitung_usia(obj.tgl_lhr);
            $('#umur_saat_pelayanan_hidden').val(umur_pasien);
            $('#penjamin').text(penjamin);
            $('#kode_kelompok_hidden').val(obj.kode_kelompok);
            $('#kode_perusahaan_hidden').val(obj.kode_perusahaan);

        });


    }else{          

      $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});          

    }        

    //achtungHideLoader();        

  }      

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
  
  $('select[name="kelompok_pasien"]').change(function () {  
    var value = $(this).val();
    if (value==3) {
      $('#kode_perusahaan').show('fast');
      $('#member').hide('fast');
      $('#karyawan').hide('fast');
    }else if (value==4) {
      $('#karyawan').show('fast');
      $('#kode_perusahaan').hide('fast');
      $('#member').hide('fast');
    }else {
      $('#kode_perusahaan').hide('fast');
      $('#karyawan').hide('fast');
      if(value!=1 && value!=4 && value!=3){
        $('#member').show('fast');
      }else{
        $('#member').hide('fast');
      }
    }
  }); 

  $('#InputKeyPenjamin').typeahead({
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
        if(val_item==120){
          $('#no_kartu_bpjs_div').show();
        }else{
          $('#no_kartu_bpjs_div').hide();
        }
      }
  });


</script>

<style>
.form-horizontal .control-label {
    height: 34px !important;
}
.form-group > label[class*="col-"] {
    font-size: 16px !important;
}
.form-control{
  height: 34px !important;
  font-size: 14px !important;
}
.btn-sm{
    font-size: 16px !important;
    height: 35px !important;
}

</style>

<div class="row">
  <div class="col-xs-12">
    
    <div style="margin-top:-10px" id="form-create-pasien">    
      <form class="form-horizontal" method="post" id="form_registration" action="kiosk/Kiosk/process_register_pasien" enctype="multipart/form-data" autocomplete="off">
        <br>
        <div id="data_pribadi">
          <p><h3><b><i class="fa fa-user"></i> FORM DATA PASIEN </b></h3></p>

          <div class="form-group">
            <label class="control-label col-md-2">NIK</label>            
            <div class="col-md-2">
              <input type="text" name="nik_pasien" id="nik" class="form-control" value="<?php echo isset($value)?$value->no_ktp:''?>">
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-2">Nama Pasien</label>            
            <div class="col-md-6">            
              <input type="text" name="nama_pasien" id="nama_pasien" class="form-control" style="width:40%;margin-left: 9px;display:inline" value="<?php echo isset($value)?$value->nama_pasien:''?>">
              <span style="display:inline;float:left;width:20%">
                <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'gelar_nama')), isset($value)?$value->title:''  , 'gelar_nama', 'gelar_nama', 'form-control', '', '') ?> 
              </span>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-2">Tempat Lahir</label>            
            <div class="col-md-3">
              <input id="pob_pasien" name="pob_pasien" class="form-control" type="text" placeholder="Masukan keyword" value="<?php echo isset($value)?$value->tempat_lahir:''?>" />
            </div>

            <label class="control-label col-md-2">Tanggal Lahir</label>
            <div class="col-md-2">
                <div class="input-group">
                    <input name="dob_pasien" id="dob_pasien" data-date-format="yyyy-mm-dd" value="<?php echo isset($value)?$value->tgl_lhr:''?>"  class="form-control date-picker" type="text">
                    <span class="input-group-addon">
                    <i class="ace-icon fa fa-calendar"></i>
                    </span>
                </div>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-2">Jenis Kelamin</label>
            <div class="col-md-2">
              <?php echo $this->master->custom_selection($params = array('table' => 'mst_gender', 'id' => 'gender_id', 'name' => 'gender_name', 'where' => array()), isset($value)?($value->jen_kelamin=='L')?1:2:'' , 'gender', 'gender', 'form-control', '', '') ?> 
            </div>

          </div>
          
          <div class="form-group">
            <label class="control-label col-md-2">Alamat</label>
            <div class="col-md-3">
              <textarea name="alamat_pasien" class="form-control" style="height:70px !important"><?php echo isset($value)?$value->almt_ttp_pasien:''?></textarea>
            </div>
          </div>
          
          <div class="form-group" style="padding-top: 3px">
            <label class="control-label col-md-2">No.HP/WA</label>
            <div class="col-md-2">
              <input type="text" name="telp_pasien" id="telp_pasien" class="form-control" value="<?php echo isset($value->no_hp)?$value->no_hp:''; ?>" >
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-2">Jenis Pasien</label>
            <div class="col-md-5">
              <div class="radio" style="padding-top: 8px">
                  <label>
                    <input name="jenis_pasien" type="radio" class="ace" value="bpjs" checked="checked"  />
                    <span class="lbl" style="font-size: 16px"> BPJS </span>
                  </label>
                  <label>
                    <input name="jenis_pasien" type="radio" class="ace" value="umum"/>
                    <span class="lbl" style="font-size: 16px"> Umum </span>
                  </label>
                  <label>
                    <input name="jenis_pasien" type="radio" class="ace" value="asuransi"/>
                    <span class="lbl" style="font-size: 16px"> Asuransi </span>
                  </label>
              </div>
            </div>
          </div>

          <div class="form-group">
            <label class="control-label col-md-2">No Kartu BPJS</label>            
            <div class="col-md-2">
                <input type="text" name="no_kartu_bpjs" id="no_kartu_bpjs" class="form-control" value="<?php echo isset($value)?$value->no_ktp:''?>">
            </div>
          </div>

        </div>
        <br><br>
        <div class="form-group" id="btn_submit">
            <a href="" class="btn btn-sm btn-success">
                <i class="ace-icon fa fa-arrow-left icon-on-right bigger-110"></i>
                Kembali ke Menu Utama
            </a>
            <button type="submit" name="submit" class="btn btn-sm btn-primary">
            <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>
            Simpan Data Pasien
            </button>
        </div>
      </form>
    </div>

  </div>
</div>
