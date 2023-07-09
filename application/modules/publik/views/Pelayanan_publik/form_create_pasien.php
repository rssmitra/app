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
        var html = '<div id="msg-success" class="alert alert-success center">\
                      <h2 class="green" style="font-weight: bold"><i class="fa fa-check-circle green"></i> Berhasil</h2> \
                      Pendaftaran pasien baru anda berhasil diproses dengan No. Rekam Medis<br>\
                      <span style="font-weight: bold; font-size: 30px">'+jsonResponse.no_mr+'</span><br>\
                      Untuk melanjutkan registrasi kunjungan rawat jalan, silahkan klik tombol dibawah ini !<br><br>\
                      <a href="#" onclick="getMenu('+"'publik/Pelayanan_publik/registrasi_rj?mr="+jsonResponse.no_mr+"'"+')" class="btn btn-sm btn-success" style="background: green !important; border-color: green">Registrasi Kunjungan</a>\
                    </div>';
        $('#form-create-pasien').html(html);

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
      var label_item=item.split(':')[1];
      $(this).val(label_item);

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
        $('#kota').show('fast'); 
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

  $('input[name=jenis_pasien]').on('change',function () {
    var val_radio = $(this).filter(':checked').val();
    if(val_radio == 'bpjs'){
      // show no rujukan
      $('#div_bpjs').show();
      $('#div_asuransi').hide();
    }else if(val_radio == 'asuransi'){
      $('#div_bpjs').hide();
      $('#div_asuransi').show();
    }else{
      $('#div_bpjs').hide();
      $('#div_asuransi').hide();
    }
  });

</script>
<style>
  .div-form{
    padding-bottom: 5px !important;
  }
</style>

<div class="row">
  <div class="col-xs-12">
    
    <div style="margin-top:-10px" id="form-create-pasien">    
      <form class="form-horizontal" method="post" id="form_registration" action="publik/Pelayanan_publik/process_register_pasien" enctype="multipart/form-data" autocomplete="off">
      <div class="pull-left">
        <a href="<?php echo base_url().'public'?>" class="btn btn-sm" style="background : green !important; border-color: green"> <i class="fa fa-home"></i> Home</a>
      </div>
      
        <br>
          <p><h3><b><i class="fa fa-user"></i> FORM DATA PASIEN </b></h3></p>
          <div class="div-form">
            <label style="font-weight: bold"> <span class="red">*</span>  NIK : </label><input type="text" name="nik_pasien" id="nik" class="form-control" value="<?php echo isset($value)?$value->no_ktp:''?>">
          </div>
          <div class="div-form">
            <label style="font-weight: bold"> <span class="red">*</span>  Nama Pasien : </label>
            <div>
              <?php echo $this->master->custom_selection($params = array('table' => 'global_parameter', 'id' => 'value', 'name' => 'label', 'where' => array('flag' => 'gelar_nama')), isset($value)?$value->title:''  , 'gelar_nama', 'gelar_nama', 'form-control', '', 'style="width: 100px !important; float: left"') ?> 
              <input type="text" name="nama_pasien" id="nama_pasien" class="form-control" style="width:50%;margin-left: 3px;display:inline;" value="<?php echo isset($value)?$value->nama_pasien:''?>">
            </div>
          </div>
          <div class="div-form">
            <label style="font-weight: bold"> <span class="red">*</span> Tempat Lahir : </label>
            <input id="pob_pasien" name="pob_pasien" class="form-control" type="text" placeholder="Masukan keyword" value="<?php echo isset($value)?$value->tempat_lahir:''?>" />
          </div>
          <div class="div-form">
            <label style="font-weight: bold"> <span class="red">*</span> Tanggal Lahir : </label>
            <div class="input-group">
                <input name="dob_pasien" id="dob_pasien" data-date-format="yyyy-mm-dd" value="<?php echo isset($value)?$value->tgl_lhr:''?>"  class="form-control date-picker" type="text">
                <span class="input-group-addon">
                <i class="ace-icon fa fa-calendar"></i>
                </span>
            </div>
          </div>
          <div class="div-form">
            <label style="font-weight: bold"> <span class="red">*</span> Jenis Kelamin : </label>
            <?php echo $this->master->custom_selection($params = array('table' => 'mst_gender', 'id' => 'gender_id', 'name' => 'gender_name', 'where' => array()), isset($value)?($value->jen_kelamin=='L')?1:2:'' , 'gender', 'gender', 'form-control', '', '') ?> 
          </div>
          <div class="div-form">
            <label style="font-weight: bold"><span class="red">*</span> Alamat : </label>
            <textarea name="alamat_pasien" class="form-control" style="height:70px !important"><?php echo isset($value)?$value->almt_ttp_pasien:''?></textarea>
          </div>


          <div class="div-form" id="prov" style="display:none">
            <label style="font-weight: bold">Provinsi : </label>
            <input id="inputProvinsi"  class="form-control" name="provinsi" type="text" placeholder="Masukan keyword minimal 3 karakter" value=""/>
            <input type="hidden" name="provinsiHidden" value="" id="provinsiHidden">
          </div>

          <div class="div-form" id="kota" style="display:none">
            <label style="font-weight: bold" >Kota / Kabupaten : </label>
            <input id="inputKota" class="form-control" name="kota" type="text" placeholder="Masukan keyword minimal 3 karakter" value=""/>
            <input type="hidden" name="kotaHidden" value="" id="kotaHidden">
          </div>

          <div class="div-form">
            <label style="font-weight: bold"> <span class="red">*</span> Kecamatan :</label>
            <input id="inputKecamatan" class="form-control" name="kecamatan" type="text" placeholder="Masukan keyword minimal 3 karakter" value="" />
            <input type="hidden" name="kecamatanHidden" value="" id="kecamatanHidden">
          </div>
          
          <div class="div-form" id="village" style="display:none">
            <label style="font-weight: bold"> <span class="red">*</span> Kelurahan :</label>
            <input id="inputKelurahan" class="form-control" name="kelurahan" type="text" placeholder="Masukan keyword minimal 3 karakter" value=""/> 
            <input type="hidden" name="kelurahanHidden" value="" id="kelurahanHidden">
          </div>

          <div class="div-form">
            <label style="font-weight: bold"> <span class="red">*</span> Kode Pos :</label>
            <input id="zipcode" class="form-control" name="zipcode" type="text" value=""/>
          </div>
          
          <div class="div-form">
            <label style="font-weight: bold"> <span class="red">*</span> No.HP/WA : </label>
            <input type="text" name="telp_pasien" id="telp_pasien" class="form-control" value="" >
          </div>

          <div class="div-form">
            <label style="font-weight: bold"> <span class="red">*</span> Jenis Pasien :</label>
            <div class="radio">
              <div class="form-group">
                <div class="col-md-5">
                    <label>
                      <input name="jenis_pasien" type="radio" class="ace" value="bpjs" checked="checked"  />
                      <span class="lbl"> BPJS </span>
                    </label>
                    <label>
                      <input name="jenis_pasien" type="radio" class="ace" value="umum"/>
                      <span class="lbl"> Umum </span>
                    </label>
                    <label>
                      <input name="jenis_pasien" type="radio" class="ace" value="asuransi"/>
                      <span class="lbl"> Asuransi </span>
                    </label>
                </div>
              </div>
            </div>
          </div>

          <div class="div-form" id="div_bpjs">
            <label style="font-weight: bold"> <span class="red">*</span> No Kartu BPJS :</label>            
            <input type="text" name="no_kartu_bpjs" id="no_kartu_bpjs" class="form-control" value="<?php echo isset($value)?$value->no_ktp:''?>">
          </div>

          <div class="div-form" id="div_asuransi" style="display: none">
            <br>
            <label style="font-weight: bold"><span class="red">*</span> Pilih Asuransi : </label>
            <input id="InputKeyPenjamin" class="form-control" name="penjamin" type="text" placeholder="Masukan keyword minimal 3 karakter" />
            <input type="hidden" name="kode_perusahaan_hidden" value="" id="kode_perusahaan_hidden">
          </div>
            
        <div id="btn_submit" style="margin-top: 10px">
            <button type="submit" name="submit" class="btn btn-block btn-primary" style="background: green !important; border-color: green; height: 45px !important; font-weight: bold;">
            <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>
            Simpan Data Pasien
            </button>
        </div>
      </form>
    </div>

  </div>
</div>
