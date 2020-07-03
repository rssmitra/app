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

  $('#form_entry_resep').ajaxForm({      

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

          // kode trans far
          $('#kode_trans_far').val(jsonResponse.kode_trans_far);
          /*reload table*/
          reload_table();
          /*sum total biaya farmasi*/
          sum_total_biaya_farmasi();
          /*renew form*/
          reset_form();

        }else{          

          $.achtung({message: jsonResponse.message, timeout:5});          

        }    

        achtungHideLoader();  
        
      }      

    }); 

});

var kode_trans_far = $('#kode_trans_far').val();


function reset_form(){

  $('#inputKeyObat').focus();
  // $('#form_entry_resep')[0].reset();
  $('#kd_tr_resep').val('0');

  $('#inputKeyObat').val('');
  $('#jumlah_pesan').val('');
  $('#jumlah_tebus').val('');
  $('#harga_r').val(500);

  /*radio*/
  $("input[name=urgensi][value=biasa]").prop('checked', true);

  /*show detail tarif html*/
  $('#div_detail_obat').hide('fast');
  $('#detailObatHtml').html('');

}

function reload_table(){
  var kode_trans_far = $('#kode_trans_far').val();
  table.ajax.url("farmasi/Entry_resep_ri_rj/get_data_temp_pesanan_obat?relationId="+kode_trans_far+"&flag=biasa&tipe_layanan="+$('#flag_trans').val()+"").load();
  sum_total_biaya_farmasi();
}

function sum_total_biaya_farmasi(){

  var kode_trans_far = $('#kode_trans_far').val();
  $.getJSON("<?php echo site_url('farmasi/process_entry_resep/get_total_biaya_farmasi') ?>/"+kode_trans_far, '' , function (response) {

      $('#td_total_biaya_farmasi').html('<b>Rp. '+formatMoney(response.total)+',-</b>');

  })

}


function getDetailObatByKodeBrg(kode_brg,kode_bag){

  $.getJSON("<?php echo site_url('templates/references/getDetailObat') ?>?kode="+kode_brg+"&kode_kelompok=<?php echo isset($value)?$value->kode_kelompok:0?>&bag="+kode_bag+"&type=html&type_layan=Rajal", '' , function (response) {
    if(response.sisa_stok <= 0){
      $('#inputKeyObat').focus();
      $('#btn_add_obat').hide('fast');
      $('#warning_stok_obat').html('<span style="color:red"><b><i>Stok sudah habis !</i></b></span>');
    }else{
      $('#btn_add_obat').show('fast');
      $('#warning_stok_obat').html('');
    }
    /*show detail tarif html*/
    $('#div_detail_obat').show('fast');
    $('#detailObatHtml').html(response.html);

    return response;

  })

}

function edit_obat_resep(kode_brg, kode_tr_resep){

  preventDefault();

  var kode_bag = $('#kode_bagian').val();

  $.getJSON("<?php echo site_url('farmasi/Entry_resep_ri_rj/getDetail') ?>/"+kode_brg+"/"+kode_tr_resep, '' , function (response) {

      getDetailObatByKodeBrg(kode_brg, kode_bag);
      var obj = response.resep_data;
      console.log(obj.kode_brg);
      /*show value form*/
      $('#inputKeyObat').val(kode_brg+' : '+obj.nama_brg);
      $('#jumlah_pesan').val(parseInt(obj.jumlah_pesan));
      $('#jumlah_tebus').val(parseInt(obj.jumlah_tebus));
      $('#harga_r').val(obj.jasa_r);

      /*radio*/
      $("input[name=urgensi][value="+obj.urgensi+"]").prop('checked', true);

      $('#aturan_pakai').val(obj.aturan_pakai_format);
      $('#bentuk_resep').val(obj.bentuk_resep);
      $('#anjuran_pakai').val(obj.anjuran_pakai);
      $('#catatan').val(obj.catatan_lainnya);
      $('#kd_tr_resep').val(obj.relation_id);

  })

}

function create_new_resep(){
    preventDefault();
    $('#form_by_jenis_resep').show();
    $('#div_pencarian_obat').show();
    $('#div_table_riwayat_resep').hide();
    $('#kode_trans_far').val('');

    if( $('#jenis_resep').val() == 'rk' ){
      $('#form_by_jenis_resep').load('farmasi/Entry_resep_ri_rj/form_resep_karyawan');  
    }

    if( $('#jenis_resep').val() == 'rl' || $('#jenis_resep').val() == 'pb' ){
      $('#form_by_jenis_resep').load('farmasi/Entry_resep_ri_rj/form_resep_luar?type='+$('#jenis_resep').val()+''); 
    }

    // load form 
    $('#div_default_form_entry').show();
    $('#div_default_form_entry').load('farmasi/Entry_resep_ri_rj/form_default_entry');  

}
function update_data(kode_trans_far){

  preventDefault();
  $('#form_by_jenis_resep').show();
  $('#div_pencarian_obat').show();
  $('#div_table_riwayat_resep').hide();
  $('#kode_trans_far').val(kode_trans_far);
  
  // get data fr tc far
  $.getJSON("farmasi/Etiket_obat/get_detail_by_kode_trans_far/"+kode_trans_far+"", '' , function (response) {
    console.log(response);
    var obj = response.value;
    $('#nama_pasien').val(obj.nama_pasien);
    $('#dokter_pengirim').val(obj.dokter_pengirim);

  })

  if( $('#jenis_resep').val() == 'rk' ){
    $('#form_by_jenis_resep').load('farmasi/Entry_resep_ri_rj/form_resep_karyawan/'+kode_trans_far+'');  
  }

  if( $('#jenis_resep').val() == 'rl' || $('#jenis_resep').val() == 'pb' ){
    $('#form_by_jenis_resep').load('farmasi/Entry_resep_ri_rj/form_resep_luar/'+kode_trans_far+'?type='+$('#jenis_resep').val()+''); 
  }

  // load form 
  $('#div_default_form_entry').show();
  $('#div_default_form_entry').load('farmasi/Entry_resep_ri_rj/form_default_entry/'+kode_trans_far);  

}

function show_history(){
  $('#div_default_form_entry').hide();
  $('select[name="jenis_resep"]').change();
}

$('select[name="jenis_resep"]').change(function () {      
  
  // reset form with class
  $('.default_value').val('');
  $('#kode_trans_far').val('0');
  // reload_table();
  
  if( $(this).val() == 'rj' ){
    // default value
    $('#form_by_jenis_resep').show('fast');
    $('#form_by_jenis_resep').load('farmasi/Entry_resep_ri_rj/form_resep_rj');  
    $('#kode_profit').val(2000);
    $('#button_action').hide();
    $('#flag_trans').val( $(this).val().toUpperCase() );
    $('#div_pencarian_obat').hide();
    $('#div_default_form_entry').hide();
    $('#div_table_riwayat_resep').hide('fast');
    
  }  

  if( $(this).val() == 'rl' || $(this).val() == 'pb' ){
    // default value
    $('#form_by_jenis_resep').hide();
    $('#flag_trans').val( $(this).val().toUpperCase() );
    kode_profit = ( $(this).val() == 'rl') ? 3000 : 4000 ;
    $('#kode_profit').val(kode_profit);
    $('#div_default_form_entry').hide();
    $('#button_action').show();
    $('#div_pencarian_obat').hide();
    $('#div_table_riwayat_resep').show('fast');
    $('#div_table_riwayat_resep').load('farmasi/Entry_resep_ri_rj/riwayat_resep?type='+$(this).val()+'&profit='+$('#kode_profit').val()+'');
  }  

  if( $(this).val() == 'rk' ){
    // default value
    $('#form_by_jenis_resep').hide();
    $('#kode_profit').val(4000);
    $('#flag_trans').val( $(this).val().toUpperCase() );
    $('#div_default_form_entry').hide();
    $('#button_action').show();
    $('#div_pencarian_obat').hide();
    $('#div_table_riwayat_resep').show('fast');
    $('#div_table_riwayat_resep').load('farmasi/Entry_resep_ri_rj/riwayat_resep?type='+$(this).val()+'&profit='+$('#kode_profit').val()+'');
  }  

});

function rollback(id){
  preventDefault();
  if(confirm('Are you sure?')){
    $.ajax({
        url: 'farmasi/process_entry_resep/rollback_by_kode_trans_far',
        type: "post",
        data: { ID : id },
        dataType: "json",
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
            // show poup cetak resep
            reload_table();

          }else{
            $.achtung({message: jsonResponse.message, timeout:5});
          }
          achtungHideLoader();
        }

      });

  }else{
    return false;
  }
  
}

</script>

<style type="text/css">
  .pagination{
    margin: 0px 0px !important;
  }
  .well{
    padding: 5px !important;
  }
</style>

<div class="row">

  <div class="col-xs-12">

    <!-- breadcrumbs -->
    <div class="page-header">  
      <h1>
        <?php echo $title?>        
        <small> <i class="ace-icon fa fa-angle-double-right"></i> <?php echo isset($breadcrumbs)?$breadcrumbs:''?></small>        
      </h1>
    </div>  
    
    <!-- form -->
    <form class="form-horizontal" method="post" id="form_entry_resep" enctype="multipart/form-data" autocomplete="off" action="farmasi/process_entry_resep/process">      
      
      <!-- form_hidden -->
      <input type="hidden" name="kd_tr_resep" id="kd_tr_resep" value="0">
      <input type="hidden" name="no_registrasi" value="0">
      <input type="hidden" name="no_kunjungan" id="no_kunjungan" class="form-control" value="0" >
      <input type="hidden" name="kode_bagian" value="060101" id="kode_bagian">
      <input type="hidden" name="kode_bagian_asal" value="060101">
      <input type="hidden" name="no_resep" id="no_resep" class="form-control" value="0" >
      <input type="hidden"  name="flag_resep" value="biasa">
      <input type="hidden" name="kode_perusahaan" id="kode_perusahaan" class="form-control" value="0" >
      <input type="hidden" name="kode_kelompok" id="kode_kelompok" class="form-control" value="0" >
      <input type="hidden" class="default_value" name="flag_trans" id="flag_trans" value="">
      <input type="hidden" class="default_value" name="no_mr" id="no_mr" value="">
      <input type="hidden" class="default_value" name="nama_pasien" id="nama_pasien" value="">
      <input type="hidden" class="default_value" name="kode_dokter" id="kode_dokter" value="0">
      <input type="hidden" class="default_value" name="dokter_pengirim" id="dokter_pengirim" value="0">
      <input type="hidden" class="default_value" name="kode_profit" id="kode_profit" value="">
      <input type="hidden" class="default_value" name="kode_trans_far" id="kode_trans_far" value="" readonly>

      <!-- default form -->
      <div class="row">
        
        

        <div class="col-sm-12">
          <div class="widget-header widget-header-small">
            <div class="form-group">
              <label class="col-sm-2" style="margin-top: 5px; color: black; font-weight: 700">Jenis Resep</label>
              <div class="col-sm-2" style="margin-top: 1px">
                <select name="jenis_resep" id="jenis_resep" class="form-control">
                  <option value="">-Silahkan Pilih-</option>
                  <option value="rj">Pasien Rawat Jalan</option>
                  <option value="rl">Resep Luar</option>
                  <option value="pb">Pembelian Bebas</option>
                  <option value="rk">Resep Karyawan</option>
                </select>
              </div>

              <label class="col-sm-1" style="margin-top: 5px; color: black; font-weight: 700">Tanggal</label>
              <div class="col-sm-2" style="margin-top: 1px">
                <div class="input-group">
                    <input name="tgl_resep" id="tgl_resep" class="form-control date-picker" type="text" data-date-format="yyyy-mm-dd" value="<?php echo date('Y-m-d')?>">
                    <span class="input-group-addon">
                      <i class="ace-icon fa fa-calendar"></i>
                    </span>
                  </div>
              </div>
              <div class="col-sm-5" id="button_action" style="display:none">
                <div class="pull-right">
                    <button type="button" class="btn btn-xs btn-primary" onclick="create_new_resep()"><i class="fa fa-plus"></i> Buat Resep</button>
                    <button type="button" class="btn btn-xs btn-success" onclick="show_history()"><i class="fa fa-history"></i> Tampilkan Riwayat</button>
                </div>
                
              </div>
            </div>

          </div>

          <!-- onchange form jenis resep -->
          <div id="form_by_jenis_resep"></div>

        </div>

        <div id="div_table_riwayat_resep" ></div>

      </div>

      <!-- form pencarian obat -->
      <div class="row" id="div_default_form_entry" style="display:none;"></div>
        

    </form>

  </div>

</div><!-- /.row -->

