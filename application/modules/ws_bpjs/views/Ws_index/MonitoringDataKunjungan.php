<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />
<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>
<script src="<?php echo base_url().'assets/js/custom/als_datatable_custom_url.js'?>"></script>

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

function delete_sep(no_sep){
  if(confirm('Are you sure?')){
    $.ajax({
        url: 'ws_bpjs/ws_index/delete_sep',
        type: "post",
        data: {ID:no_sep,jnsPelayanan:$('input[name=jnsPelayanan]:checked').val(),tglSep:$('#tglSep').val()},
        dataType: "json",
        beforeSend: function() {
          achtungShowLoader();  
        },
        uploadProgress: function(event, position, total, percentComplete) {
        },
        complete: function(xhr) {     
          var data=xhr.responseText;
          var jsonResponse = JSON.parse(data);
          if(jsonResponse.status == 200){
            $('#page-area-content').load('ws_bpjs/ws_index?modWs=MonitoringDataKunjungan');
            $.achtung({message: jsonResponse.message, timeout:5});
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

function view_sep(no_sep){

  $.getJSON("<?php echo site_url('ws_bpjs/ws_index/show_detail_sep/') ?>" + no_sep, '', function (response) { 

    if(response.status==200){
      var sep = response.data;
      $('#modal_title_noSep').text('DATA SEP NOMOR : '+sep.noSep);
      $('#modal_noSep').text(': '+sep.noSep);
      $('#modal_tglSep').text(': '+sep.tglSep);
      $('#modal_jnsRawat').text(': '+sep.jnsRawat);
      $('#modal_kelasRawat').text(': '+sep.kelasRawat);
      $('#modal_catatan').text(': '+sep.catatan);
      $('#modal_diagnosa').text(': '+sep.diagnosa);
      $('#modal_penjamin').text(': '+sep.penjamin);
      $('#modal_poli').text(': '+sep.poli);
      $('#modal_poliEksekutif').text(': '+sep.poliEksekutif);
      $('#modal_jnsPelayanan').text(': '+sep.jnsPelayanan);
      $('#modal_PPKPerujuk').text(': '+sep.PPKPerujuk);
      $('#modal_cob').text(': -');

      $('#modal_noKartu').text(': '+sep.noKartu);
      $('#modal_jnsPeserta').text(': '+sep.jnsPeserta);
      $('#modal_tglLahir').text(': '+sep.tglLahir);
      $('#modal_kelamin').text(': '+sep.kelamin);
      $('#modal_hakKelas').text(': '+sep.hakKelas);
      $('#modal_asuransi').text(': '+sep.asuransi);
      $('#modal_nama').text(': '+sep.nama);
      $('#modal_noMr').text(': '+sep.noMr);
      $('#modal_noTelp').text(': '+sep.noTelp);
      
      /*show modal*/
      $("#modalViewSep").modal(); 

    }

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

    <form class="form-horizontal" method="post" id="form_search">

    <div class="col-md-12">
      <center><h4>FORM PENCARIAN DATA KUNJUNGAN PASIEN<br><small style="font-size:12px">(Silahkan lakukan pencarian data berdasarkan parameter dibawah ini)</small></h4></center>
      <br>

      <div class="form-group">
        <label class="control-label col-md-2">Jenis Pelayanan</label>
        <div class="col-md-3">
          <div class="radio">
                <label>
                  <input name="jnsPelayanan" type="radio" class="ace" value="1" />
                  <span class="lbl"> Rawat Inap</span>
                </label>
                <label>
                  <input name="jnsPelayanan" type="radio" class="ace" value="2" checked />
                  <span class="lbl"> Rawat Jalan </span>
                </label>
          </div>
        </div>
        <label class="control-label col-md-1">Tanggal SEP</label>
        <div class="col-md-2">
          <div class="input-group">
              <input name="tglSep" id="tglSep" value="<?php echo date('m/d/Y')?>" placeholder="ex : yyyy-MM-dd" class="form-control date-picker" type="text">
              <span class="input-group-addon">
                <i class="ace-icon fa fa-calendar"></i>
              </span>
            </div>
        </div>

        <div class="col-md-3">
          <a href="#" id="btn_search_data" class="btn btn-xs btn-default">
            <i class="ace-icon fa fa-search icon-on-right bigger-110"></i>
            Search
          </a>
          <a href="#" id="btn_reset_data" class="btn btn-xs btn-warning">
            <i class="ace-icon fa fa-refresh icon-on-right bigger-110"></i>
            Reset
          </a>
        </div>

      </div>

    </div>
    <p>&nbsp;</p>
    <hr class="separator">
    <div style="margin-top:-30px" id="table-data">
      <table id="dynamic-table" base-url="ws_bpjs/Ws_index/get_data_kunjungan?flag=" class="table table-bordered table-hover">
        <thead>
          <tr>  
            <th width="130px" class="center"></th>
            <th width="120px">Nama</th>
            <th>No Kartu</th>
            <th>No SEP</th>
            <th>Tanggal SEP</th>
            <th>Tanggal Pulang</th>
            <th>Diagnosa</th>
            <th>Jenis Pelayanan</th>
            <th>Kelas Rawat</th>
            <th>Poli</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>

    </form>
  </div><!-- /.col -->
</div><!-- /.row -->

<!-- modal detail sep -->
<div id="modalViewSep" class="modal fade" tabindex="-1">
 <div class="modal-dialog" style="max-height:85%;  margin-top: 50px; margin-bottom:50px;width:80%">
   <div class="modal-content">
     <div class="modal-header no-padding">
       <div class="table-header">
         <button type="button" class="close" data-dismiss="modal" aria-hidden="true">
           <span class="white">&times;</span>
         </button>
         <span id="modal_title_noSep"></span>
       </div>
     </div>
     <div class="modal-body no-padding">
      <div style="margin-left:20px;margin-right:20px;padding-bottom:50px;padding-top:30px">
       
        <style type="text/css">
            /*table {
                font-family: arial;
                font-size: 14px
            };*/
        </style>
        <table border="0">
            <tr>
                <td><img src="<?php echo base_url()?>assets/images/logo-bpjs.png" style="width:200px"></td>
                <td style="padding-left:30px"><b>SURAT ELEGIBILITAS PESERTA<br><?php echo strtoupper(COMP_LONG); ?></b></td>
            </tr>
        </table>
        </br>

        <table border="0">
            <tr>
                <td width="150px">No SEP</td><td colspan="3" id="modal_noSep"></td>
            </tr>
            <tr>
                <td>Tgl SEP</td><td id="modal_tglSep"></td>
                <td style="padding-left:150px">Peserta</td><td id="modal_jnsPeserta"></td>
            </tr>
            <tr>
                <td>No Kartu</td><td id="modal_noKartu"> </td>
                <td style="padding-left:150px">COB</td><td id="modal_cob"></td>
            </tr>
            <tr>
                <td>Nama Peserta</td><td id="modal_nama"></td>
                <td style="padding-left:150px">Jns. Rawat</td><td id="modal_jnsPelayanan"></td>
            </tr>
            <tr>
                <td>Tgl Lahir</td><td id="modal_tglLahir"></td>
                <td style="padding-left:150px">Kls. Rawat</td><td id="modal_kelasRawat"></td>
            </tr>
            <tr>
                <td>No Telepon</td><td id="modal_noTelp"></td>
                <td style="padding-left:150px">Penjamin</td><td id="modal_penjamin"></td>
            </tr>
            <tr>
                <td>Poli Tujuan</td><td id="modal_poli"></td>
            </tr>
            <tr>
                <td>Faskes Perujuk</td><td id="modal_PPKPerujuk"></td>
            </tr>
            <tr>
                <td>Diagnosa Awal</td><td id="modal_diagnosa" colspan="2"></td>
            </tr>
            <tr>
                <td>Catatan</td><td id="modal_catatan"></td>
            </tr>
        </table>

        <table border="0">
            <tr>
                <td>
                <p style="font-size:12px">*Saya menyetujui BPJS Kesehatan menggunakan informasi medis pasien jika diperlukan<br>
                SEP Bukan sebagai bukti penjaminan peserta<br></p>
                <span style="font-size:11px">Cetakan ke 1 <?php echo date('d-m-Y H:i:s')?> wib</span>
                </td>
                <td valign="top" style="padding-left:120px">
                Pasien/Keluarga Pasien<br><br><br><br>____________________
                </td>
            </tr>
        </table>
      </div>

     </div>
     <div class="modal-footer no-margin-top">
       <button class="btn btn-sm btn-danger pull-left" data-dismiss="modal">
         <i class="ace-icon fa fa-times"></i>
         Close
       </button>
     </div>
   </div><!-- /.modal-content -->
 </div><!-- /.modal-dialog -->
</div>



