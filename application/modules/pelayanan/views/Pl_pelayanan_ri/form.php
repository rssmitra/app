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

    // list pasien
    get_list_pasien();

    /*when page load find pasien by mr*/
    find_pasien_by_keyword('<?php echo $no_mr?>');
    $('#div_main_form').load('pelayanan/Pl_pelayanan_ri/form_main/<?php echo $id; ?>/<?php echo $no_kunjungan; ?>');
    
    window.filter = function(element)
    {
      var value = $(element).val().toUpperCase();
      $(".list-group > li").each(function() 
      {
        if ($(this).text().toUpperCase().search(value) > -1){
          $(this).show();
        }
        else {
          $(this).hide();
        }
      });
    }

    
 
})

/*format date to m/d/Y*/
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

/*function find pasien*/
function find_pasien_by_keyword(keyword){  

    $.getJSON("<?php echo site_url('registration/reg_klinik/search_pasien_by_mr') ?>?keyword=" + keyword, '', function (data) {      

          /*if count data = 1*/
          if( data.count == 1 )     {

            var obj = data.result[0];

            var pending_data_pasien = data.pending; 
            var umur_pasien = hitung_usia(obj.tgl_lhr);

            $('#no_mr').text(obj.no_mr);

            $('#noMrHidden').val(obj.no_mr);

            $('#no_ktp').text(obj.no_ktp);
            // tambahan
            $('#nikPasien').val(obj.no_ktp);
            $('#hpPasien').val(obj.no_hp);
            $('#noTelpPasien').val(obj.tlp_almt_ttp);

            $('#nama_pasien').text(obj.nama_pasien+' ('+obj.jen_kelamin+')');

            $('#nama_pasien_hidden').val(obj.nama_pasien);

            $('#jk').text(obj.jen_kelamin);

            $('#umur').text(umur_pasien+' Tahun');

            $('#tgl_lhr').text(getFormattedDate(obj.tgl_lhr));
            
            $('#umur_saat_pelayanan_hidden').val(umur_pasien);

            $('#alamat').text(obj.almt_ttp_pasien);

            $('#hp').text(obj.no_hp);

            $('#no_telp').text(obj.tlp_almt_ttp);

            $('#catatan_pasien').text(obj.keterangan);

            $('#ttd_pasien').attr('src', obj.ttd);

            $('#noKartuBpjs').val(obj.no_kartu_bpjs);

            if( obj.url_foto_pasien ){

              $('#avatar').attr('src', '<?php echo base_url()?>uploaded/images/photo/'+obj.url_foto_pasien+'');

            }else{

              if( obj.jen_kelamin == 'L' ){
            
                $('#avatar').attr('src', '<?php echo base_url()?>assets/avatars/boy.jpg');
              
              }else{
                
                $('#avatar').attr('src', '<?php echo base_url()?>assets/avatars/girl.jpg');

              }

            }

            if( obj.kode_perusahaan==120){

              $('#form_sep').show('fast'); 
              $('#no_kartu_bpjs_txt').text('('+obj.no_kartu_bpjs+')');
              
              //showModalFormSep(obj.no_kartu_bpjs,obj.no_mr);
              
            }else{
              
              $('#form_sep').hide('fast'); 
              $('#no_kartu_bpjs_txt').text('');

            }

            penjamin = (obj.nama_perusahaan==null)?obj.nama_kelompok:obj.nama_perusahaan;
            kelompok = (obj.nama_kelompok==null)?'-':obj.nama_kelompok;

            $('#kode_perusahaan').text(penjamin);
            
            $('#kode_perusahaan_hidden').val(obj.kode_perusahaan);
            /*penjamin pasien*/
            $('#kode_kelompok_hidden').val(obj.kode_kelompok);

            $('#InputKeyPenjamin').val(obj.nama_perusahaan);
            $('#InputKeyNasabah').val(obj.nama_kelompok);

            $('#total_kunjungan').text(obj.total_kunjungan);

            $('#full_pasien_data').text(obj.no_mr+' - '+obj.nama_pasien+' ('+obj.jen_kelamin+') | TL. '+getFormattedDate(obj.tgl_lhr)+' ('+ umur_pasien+' Thn)');


            $("#myTab li").removeClass("active");


          }            

    }); 

}

function get_list_pasien(){  

  $('#box_list_pasien').html('Loading...');
  
  var is_icu = ( $('#bag_pas').val() == '031001' ) ? 'Y' : '';
  $.getJSON("<?php echo site_url('pelayanan/Pl_pelayanan_ri/get_list_pasien?is_icu=') ?>"+is_icu, '', function (response) {    
    html = '';  
    // html = '<div class="left" style="padding: 5px; font-size: 12px;background: darkblue; color: white"><b>PASIEN RAWAT INAP</b><br>Pasien dirawat s.d tgl <?php echo date('d/M/Y')?><br>BPJS : 20 <br>Umum/Asuransi : 10</div>';
    html += '<div style="padding-top: 1px; padding-bottom: 10px;"><b>Cari pasien rawat inap:</b> <br><input type="text" id="seacrh_ul_li" value="" placeholder="Masukan keyword..." class="form-control" onkeyup="filter(this);"><a style="margin-top:4px" href="#" onclick="get_list_pasien()" class="btn btn-block btn-primary">Refresh</a></div>';
    html += '<ol class="list-group list-group-unbordered" id="list_pasien" style="background-color:lightblue;height: 650px;overflow: scroll;">';

    $.each(response.data, function( i, v ) {
      var obj = v[0];
      html += '<li class="list-group-item" id="list_group_'+obj.no_mr+'">';
      html += '<small style="font-weight: bold; font-size: 11px; cursor: pointer;" onclick="form_main('+"'pelayanan/Pl_pelayanan_ri/form_main/"+obj.kode_ri+"/"+obj.no_kunjungan+"'"+', '+"'"+obj.no_mr+"'"+')">'+obj.no_mr+' - '+obj.nama_pasien+'</small>';
      html += '</li>';
    });
    html += '</ol>';
    $('#box_list_pasien').html(html);
  }); 

}

function form_main(url, no_mr){
  find_pasien_by_keyword(no_mr);
  $('#div_main_form').html('<span style="padding: 100px; float: left; width: 100% !important">Loading...</span>');
  $('#div_main_form').load(url);
}


</script>

<style type="text/css">
  .pagination{
    margin: 0px 0px !important;
  }
  .well{
    padding: 5px !important;
  }
  .list-group-item {
      background: #0d5280;
      color: white;
  }
</style>

<div class="row">

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

    <div style="margin-top:-10px">   

      <form class="form-horizontal" method="post" id="form_pelayanan" action="#" enctype="multipart/form-data" autocomplete="off" >      
        
          <br>
          <input type="hidden" value="" name="noMrHidden" id="noMrHidden">
          <input type="hidden" name="nama_pasien_hidden" value="" id="nama_pasien_hidden">
          <input type="hidden" value="" name="nikPasien" id="nikPasien">
          <input type="hidden" value="" name="noKartuBpjs" id="noKartuBpjs">
          <input type="hidden" value="3" name="jeniskunjunganbpjs" id="jeniskunjunganbpjs">
          <input type="hidden" value="" name="hpPasien" id="hpPasien">
          <input type="hidden" value="" name="noTelpPasien" id="noTelpPasien">
          <input type="hidden" name="bag_pas" value="<?php echo $value->bag_pas?>" id="bag_pas">

          <!-- profile Pasien -->
          <div class="col-md-2">
            <div class="box box-primary" id='box_list_pasien'></div><br>
            <label class="label label-xs label-success">&nbsp;&nbsp;</label> LA (Lantai Atas)<br>
            <label class="label label-xs label-danger">&nbsp;&nbsp;</label> LB (Lantai Bawah)<br>
            <label class="label label-xs label-primary">&nbsp;&nbsp;</label> VK (Ruang Bersalin dan Nifas)<br>
            <label class="label label-xs label-inverse">&nbsp;&nbsp;</label> Lain-lain<br>
          </div>

          <!-- form pelayanan -->
          <div class="col-md-10 no-padding">
            <!-- informasi pendaftaran pasien -->
            <span class="pull-left" style="font-size: 20px; font-weight: bold; color: #0d5280" id="full_pasien_data"></span><br>
            
            <div id="div_main_form"></div>
          </div>

      </form>

    </div>

</div><!-- /.row -->

<div id="GlobalModal" class="modal fade" tabindex="-1">

  <div class="modal-dialog" style="overflow-y: scroll; max-height:90%;  margin-top: 50px; margin-bottom:50px;width:70%">

    <div class="modal-content">

      <div class="modal-header">

        <div class="table-header">

          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">

            <span class="white">&times;</span>

          </button>

          <span id="result_text_riwayat_medis">PERJANJIAN PASIEN</span>

        </div>

      </div>

      <div class="modal-body">

        <div id="form_modal"></div>

      </div>

      <!-- <div class="modal-footer no-margin-top">

        <button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">

          <i class="ace-icon fa fa-times"></i>

          Close

        </button>

      </div> -->

    </div><!-- /.modal-content -->

  </div><!-- /.modal-dialog -->

</div>



