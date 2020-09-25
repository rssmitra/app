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

$( "#keyword_pasien" )
  .keypress(function(event) {
    var keycode =(event.keyCode?event.keyCode:event.which); 
    if(keycode ==13){
      event.preventDefault();
      $('#btn_search_pasien').click();
      return false;       
    }
});

function search_pasien_by_keyword(){

  if( $('#keyword_pasien').val() != '' ){
      $.getJSON("<?php echo site_url('templates/references/search_pasien_rj') ?>?search_by="+$('#search_pasien_by').val()+"&keyword="+$('#keyword_pasien').val()+"&tgl_pelayanan="+$('#tgl_pelayanan').val()+"", '' , function (response) {
      
        $('#responseSearchPasienDiv').html(response.html);

        return response;

      })
  }else{
    return alert('Silahkan masukan Keyword !'); 
  }
  
}

function submitPesanResep(no_kunjungan){

  preventDefault();
  var data = {
    no_registrasi : $("#tr_kunjungan_"+no_kunjungan+" input[name=no_registrasi]").val(),
    no_kunjungan : $("#tr_kunjungan_"+no_kunjungan+" input[name=no_kunjungan]").val(),
    no_mr : $("#tr_kunjungan_"+no_kunjungan+" input[name=no_mr]").val(),
    kode_perusahaan : $("#tr_kunjungan_"+no_kunjungan+" input[name=kode_perusahaan]").val(),
    kode_kelompok : $("#tr_kunjungan_"+no_kunjungan+" input[name=kode_kelompok]").val(),
    kode_klas : $("#tr_kunjungan_"+no_kunjungan+" input[name=kode_klas]").val(),
    kode_profit : $("#tr_kunjungan_"+no_kunjungan+" input[name=kode_profit]").val(),
    kode_bagian_asal : $("#tr_kunjungan_"+no_kunjungan+" input[name=kode_bagian_asal]").val(),
    kode_dokter : $("#tr_kunjungan_"+no_kunjungan+" input[name=kode_dokter]").val(),
    jumlah_r : $("#tr_kunjungan_"+no_kunjungan+" input[name=jumlah_r]").val(),
    lokasi_tebus : $("#tr_kunjungan_"+no_kunjungan+" input[name=lokasi_tebus]").val(),
    tgl_pesan : $("#tr_kunjungan_"+no_kunjungan+" input[name=tgl_pesan]").val(),
  };

  if( confirm('Apakah anda yakin?') ){
    $.ajax({
        url: "farmasi/Farmasi_pesan_resep/process",
        data: data,            
        dataType: "json",
        type: "POST",
        beforeSend: function() {        
          achtungShowLoader();          
        },  
        complete: function(xhr) {             

          var data=xhr.responseText;        

          var jsonResponse = JSON.parse(data);        

          if(jsonResponse.status === 200){          

            $.achtung({message: jsonResponse.message, timeout:5});             
            getMenu(jsonResponse.redirect);

          }else{

            $.achtung({message: jsonResponse.message, timeout:5}); 

          }    

          achtungHideLoader();  

        }

    });
  }
  return false;

}

</script>

<h3 class="row header smaller lighter orange">
  <span class="col-sm-8">
    <i class="ace-icon fa fa-bell"></i>
    RESEP RAWAT JALAN
  </span><!-- /.col -->
</h3>

<div class="form-group">
  <label class="control-label col-sm-2">Tanggal Pelayanan</label>
  <div class="col-md-2">
    <div class="input-group">
        <input name="tgl_pelayanan" id="tgl_pelayanan" class="form-control date-picker" type="text" data-date-format="yyyy-mm-dd"  value="">
        <span class="input-group-addon">
          <i class="ace-icon fa fa-calendar"></i>
        </span>
      </div>
  </div>

</div>

<div class="form-group">
  <label class="control-label col-sm-2">Cari Pasien</label>
  <div class="col-md-2">
    <select name="search_pasien_by" id="search_pasien_by" class=form-control>
      <option value="c.no_mr">No MR</option>
      <option value="c.nama_pasien">Nama pasien</option>
    </select>
  </div>

  <label class="control-label col-sm-1">Keyword</label>
  <div class="col-md-2">
    <input type="text" class="form-control" name="keyword_pasien" id="keyword_pasien">
  </div>
  <div class="col-md-4" style="margin-left: -15px">
    <a href="#" id="btn_search_pasien" onclick="search_pasien_by_keyword()" class="btn btn-xs btn-default"><i class="fa fa-search"></i> Cari Pasien</a>
  </div>

</div>

<div id="responseSearchPasienDiv"></div>
<hr>