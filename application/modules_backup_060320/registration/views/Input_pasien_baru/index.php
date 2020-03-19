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

 
$(document).ready(function(){
  $('#btn_search_data').click(function (e) {      

    e.preventDefault();      


    if( $("#nama_pasien").val() == "" && $("#dob").val() == "" ){

      alert('Masukan keyword minimal 3 Karakter !');

      return $("#nama_pasien").focus();

    }else{

      achtungShowLoader();

      find_pasien_by_keyword( $("#dob").val(), $("#nama_pasien").val());

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

function find_pasien_by_keyword(dob,nama){


  $.getJSON("<?php echo site_url('registration/Input_pasien_baru/search_pasien') ?>?dob=" + dob + "&name=" + nama, '', function (data) {              
    
    achtungHideLoader();
   
  
      $("#result_pasien_data tr").remove();

      $.each(data.result, function (i, o) {                  

          d = new Date(o.tgl_lhr);
          
          e = formatDate(d);
          
          penjamin = (o.nama_perusahaan==null)?'-':o.nama_perusahaan;
          
          pob = (o.tempat_lahir==null)?'-':o.tempat_lahir;

          if((o.no_ktp==null) || (o.no_ktp=='undefined')){
            nik = '-';
          }else{
            nik = o.no_ktp;
          };

          $('<tr><td><div class="center"><div class="hidden-sm hidden-xs action-buttons"><button class="btn btn-xs btn-info" onclick="getMenu('+"'"+'registration/Input_pasien_baru/show/'+o.no_mr+"'"+')"><i class="ace-icon fa fa-eye bigger-50"></i></button>&nbsp<button class="btn btn-xs btn-success" onclick="getMenu('+"'"+'registration/Input_pasien_baru/form/'+o.no_mr+"'"+')"><i class="ace-icon fa fa-edit bigger-50"></i></button></div></td><td>'+o.no_mr+'</td><td>'+nik+'</td><td>'+o.nama_pasien+'</td><td>'+o.jen_kelamin+'</td><td>'+e+'</td><td>'+pob+'</td><td>'+o.almt_ttp_pasien+'</td></tr>').appendTo($('#table_pasien'));                    

      }); 

      $('#table_pasien_').show('fast');    

  }); 
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

    <form class="form-horizontal" method="post" id="form_search" action="#" autocomplete="off">

    <div class="col-md-12">

      <center><h4>PENCARIAN DATA PASIEN<br><small style="font-size:12px">Pastikan Data Pasien Belum Ada Dalam Sistem</small></h4></center>
      <br>

      <div class="form-group">
        <label class="control-label col-md-2">Tanggal Lahir</label>
          <div class="col-md-2">
            <div class="input-group">
              <input class="form-control date-picker" name="dob" id="dob" type="text" data-date-format="yyyy-mm-dd" value=""/>
              <span class="input-group-addon">
                <i class="fa fa-calendar bigger-110"></i>
              </span>
            </div>
          </div>
      </div>

      <div class="form-group">
        <label class="control-label col-md-2">Nama</label>
        <div class="col-md-4">
          <input id="nama_pasien" class="form-control" name="nama_pasien" type="text" value="" />
        </div>
      </div>

      <div class="form-group">
        <label class="control-label col-md-2 ">&nbsp;</label>
        <div class="col-md-10">
          <a href="#" id="btn_search_data" class="btn btn-xs btn-default">
            <i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
            Search
          </a>
          <a href="#" onclick="getMenu('registration/Input_pasien_baru/form')" id="btn_reset_data" class="btn btn-xs btn-primary">
            <i class="ace-icon fa fa-user icon-on-right bigger-110"></i>
            Buat Pasien Baru
          </a>
          <a href="#" onclick="getMenu('registration/Input_pasien_baru/form_bayi_rs')" id="btn_reset_data" class="btn btn-xs btn-success">
            <i class="ace-icon fa fa-user icon-on-right bigger-110"></i>
            Input Bayi RS
          </a>
        </div>
      </div><br>

    </div>
    </form>

    <div id="table_pasien_" style="display:none;">
    <table class="table table-bordered table-hover" id="table_pasien">

      <thead>

        <th style="background-image:linear-gradient(to bottom, #195651 90%, #ddb909 20%)" width="80px"></th>

        <th style="background-image:linear-gradient(to bottom, #195651 90%, #ddb909 20%)">No MR</th>

        <th style="background-image:linear-gradient(to bottom, #195651 90%, #ddb909 20%)">NIK</th>

        <th style="background-image:linear-gradient(to bottom, #195651 90%, #ddb909 20%)">Nama Pasien</th>

        <th style="background-image:linear-gradient(to bottom, #195651 90%, #ddb909 20%)">JK</th>

        <th style="background-image:linear-gradient(to bottom, #195651 90%, #ddb909 20%)">Tanggal Lahir</th>

        <th style="background-image:linear-gradient(to bottom, #195651 90%, #ddb909 20%)">Tempat Lahir</th>

        <th style="background-image:linear-gradient(to bottom, #195651 90%, #ddb909 20%)">Alamat</th>


      </thead>

      <tbody id="result_pasien_data">


      </tbody>
      <span style="color:red;margin-top:-5%;display:none" id="alert_complate_data_pasien"><i>Silahkan lengkapi data pasien terlebih dahulu</i></span>

    </table>
    </div>

    

  </div><!-- /.col -->
</div><!-- /.row -->

<!--<script src="<?php //echo base_url().'assets/js/custom/als_datatable.js'?>"></script>-->

