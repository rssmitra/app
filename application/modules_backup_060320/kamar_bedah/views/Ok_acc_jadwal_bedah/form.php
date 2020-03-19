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

    var kode_tarif_existing = $('#pl_tindakan_pesan_ok').val();
    var kode_klas_existing = $('#kode_klas_val').val();
    getDetailTarifByKodeTarifAndKlas(kode_tarif_existing, kode_klas_existing);

    /*when page load find pasien by mr*/
    find_pasien_by_keyword('<?php echo $no_mr?>');

    /*focus on form input pasien*/
    $('#noMrHidden').focus();    

    /*submit form*/
    $('#form_pelayanan').ajaxForm({      

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
          getMenu('kamar_bedah/Ok_acc_jadwal_bedah');

        }else{          

          $.achtung({message: jsonResponse.message, timeout:5});          

        }        

        achtungHideLoader();        

      }      

    });     
    
     
    $('#btn_search_pasien').click(function (e) {      

      e.preventDefault();  

      if( $("#noMrHidden").val() == "" ){

        alert('Masukan keyword minimal 3 Karakter !');

        return $("#noMrHidden").focus();

      }else{

        achtungShowLoader();

        find_pasien_by_keyword( $("#noMrHidden").val() );

      }    

    });   

    $('select[name=jenis_bedah]').change(function(e){
      e.preventDefault();
      $('#formDetailTarif').hide('fast');
    })

    var kelas = $('#kode_klas_val').val() ;
    $('#inputKeyTindakanBedah').typeahead({
        source: function (query, result) {
            $.ajax({
                url: "templates/references/getTindakanByBagianAutoComplete",
                data: { keyword:query, kode_klas: kelas, kode_bag : '030901', kode_perusahaan : $('#kode_perusahaan_val').val(), jenis_bedah : $('#jenis_bedah').val() },            
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
          $('#pl_tindakan_pesan_ok').val(val_item);
          $('.InputKeyDokterBagian').focus();
          /*get detail tarif by kode tarif and kode klas*/
          getDetailTarifByKodeTarifAndKlas(val_item, kelas);
        }

    });

    // $('#inputKeyTindakanBedah').typeahead({
    //     source: function (query, result) {
    //         $.ajax({
    //             url: "templates/References/getTindakanBedah",
    //             data: 'keyword=' + query,            
    //             dataType: "json",
    //             type: "POST",
    //             success: function (response) {
    //             result($.map(response, function (item) {
    //                     return item;
    //                 }));
                
    //             }
    //         });
    //     },
    //     afterSelect: function (item) {
    //         var label_item=item.split(':')[1];
    //         var val_item=item.split(':')[0];
    //         console.log(val_item);
    //         $('#pl_tindakan_pesan_ok').val(val_item);
    //         $('#inputKeyTindakanBedah').val(label_item);
    //         getDetailTarifByKodeTarifAndKlas(val_item, kelas);
    //     }

    // });

})

function getDetailTarifByKodeTarifAndKlas(kode_tarif, kode_klas){

  $.getJSON("<?php echo site_url('templates/references/getDetailTarif') ?>?kode="+kode_tarif+"&klas="+kode_klas+"&type=html", '' , function (data) {

    /*show detail tarif html*/
    $('#formDetailTarif').show('fast');
    $('#detailTarifHtml').html(data.html);
    $('#jenis_bedah').val(data.jenis_bedah);

  })

}


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

    $.getJSON("<?php echo site_url('registration/reg_klinik/search_pasien') ?>?keyword=" + keyword, '', function (data) {      
          achtungHideLoader();          

          /*if cannot find data show alert*/
          if( data.count == 0){

            $('#div_load_after_selected_pasien').hide('fast');

            $('#div_riwayat_pasien').hide('fast');
            
            $('#div_penangguhan_pasien').hide('fast');

            /*reset all field data*/
            $('#no_mr').text('-');$('#noMrHidden').val('');$('#no_ktp').text('-');$('#nama_pasien').text('-');$('#jk').text('-');$('#umur').text('-');$('#alamat').text('-');$('#noKartuBpjs').val('-');$('#kode_perusahaan').text('-');$('#total_kunjungan').text('-');

            alert('Data tidak ditemukan'); return $("#noMrHidden").focus();

          }

          /*if count data = 1*/
          if( data.count == 1 )     {

            var obj = data.result[0];

            var pending_data_pasien = data.pending; 
            var umur_pasien = hitung_usia(obj.tgl_lhr);
            console.log(pending_data_pasien);
            console.log(hitung_usia(obj.tgl_lhr));

            $('#no_mr').text(obj.no_mr);
            $('#noMrHidden').val(obj.no_mr);
            $('#no_ktp').text(obj.no_ktp);
            $('#nama_pasien').text(obj.nama_pasien);
            $('#nama_pasien_hidden').val(obj.nama_pasien);
            $('#jk').text(obj.jen_kelamin);
            $('#umur').text(umur_pasien+' Tahun');          
            $('#umur_saat_pelayanan_hidden').val(umur_pasien);
            $('#alamat').text(obj.almt_ttp_pasien);
            $('#noKartuBpjs').val(obj.no_kartu_bpjs);

            penjamin = (obj.nama_perusahaan==null)?'-':obj.nama_perusahaan;
            kelompok = (obj.nama_kelompok==null)?'-':obj.nama_kelompok;
            $('#kode_perusahaan').text(kelompok+' '+obj.nama_perusahaan);



            if( obj.url_foto_pasien ){

              $('#avatar').attr('src', '<?php echo base_url()?>uploaded/images/photo/'+obj.url_foto_pasien+'');

            }else{

              if( obj.jen_kelamin == 'L' ){

                $('#avatar').attr('src', '<?php echo base_url()?>assets/avatars/boy.jpg');

              }else{

                $('#avatar').attr('src', '<?php echo base_url()?>assets/avatars/girl.jpg');

              }

            }

            /*default show tabs*/
            $("#tabs_form_pelayanan").load('pelayanan/Pl_pelayanan_ri/tindakan/<?php echo $id?>/<?php echo $no_kunjungan?>');

          }            

    }); 

}

$('select[name="kode_ruangan"]').change(function () {      
      
  /*no kamar*/
  $('#no_kamar_bedah').val( $('#kode_ruangan option:selected').prop('label') );
}); 

</script>

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

      <form class="form-horizontal" method="post" id="form_pelayanan" action="kamar_bedah/Ok_acc_jadwal_bedah/process_acc" enctype="multipart/form-data" autocomplete="off" >      
                  
          <!-- HIDDEN FORM -->
          <input type="hidden" class="form-control" name="kode_klas" value="<?php echo isset($value->kode_klas)?$value->kode_klas:''?>"  id="kode_klas_val">
          <input type="hidden" class="form-control" name="noMrHidden" id="noMrHidden" value="<?php echo isset($value->no_mr)?$value->no_mr:''?>" >
          <input type="hidden" class="form-control" name="no_registrasi" id="no_registrasi" value="<?php echo isset($value->no_registrasi)?$value->no_registrasi:''?>" >
          <input type="hidden" class="form-control" name="no_kunjungan" id="no_kunjungan" value="<?php echo isset($value->no_kunjungan)?$value->no_kunjungan:''?>" >
          <input type="hidden" name="id_pesan_bedah" value="<?php echo isset($id)?$id:0;?>">
          <input type="hidden" name="kode_perusahaan" id="kode_perusahaan_val" value="<?php echo isset($value->kode_perusahaan)?$value->kode_perusahaan:'';?>">


          <!-- profile Pasien -->
          <div class="col-md-2">
            <div class="box box-primary" id='box_identity'>
                <img id="avatar" class="profile-user-img img-responsive center" src="<?php echo base_url().'assets/img/avatar.png'?>" alt="User profile picture" style="width:100%">

                <h3 class="profile-username text-center"><div id="no_mr">No. MR</div></h3>

                <ul class="list-group list-group-unbordered">
                  <li class="list-group-item">
                    <div id="nama_pasien">Nama Pasien</div>
                  </li>
                  <li class="list-group-item">
                    <div id="no_ktp">NIK</div>
                  </li>
                  <li class="list-group-item">
                    <div id="jk">Jenis Kelamin</div>
                  </li>
                  <li class="list-group-item">
                    <div id="umur">Umur</div>
                  </li>
                  <li class="list-group-item">
                    <div id="alamat">Alamat</div>
                  </li>
                </ul>
                <a href="#" id="btn_search_pasien" class="btn btn-inverse btn-block">Tampilkan Pasien</a>
              <!-- /.box-body -->
            </div>
          </div>

          <!-- form pelayanan -->
          <div class="col-md-10">

          <!-- end action form  -->
            <div class="pull-right" style="margin-bottom:3px">
              <button type="submit" class="btn btn-xs btn-primary"><i class="fa fa-angle-double-left"></i> Masukan ke Antrian Bedah</button>
              <a href="#" class="btn btn-xs btn-danger"><i class="fa fa-times-circle"></i> Batalkan Bedah</a>
            </div>
            <!-- informasi pendaftaran pasien -->
            <table class="table table-bordered">
              <tr style="background-color:#f4ae11">
                <th>Kode</th>
                <th>Tanggal Pesan</th>
                <th>Dokter</th>
                <th>Penjamin</th>
                <th>Tindakan</th>
                <th>Biaya</th>
              </tr>

              <tr>
                <td><?php echo isset($value->no_kunjungan)?$value->no_kunjungan:''?></td>
                <td><?php echo isset($value->tgl_pesan)?$this->tanggal->formatDateTime($value->tgl_pesan):''?></td>
                <td><?php echo isset($value->nama_pegawai)?$value->nama_pegawai:'';?></td>
                <td><?php echo isset($value->nama_kelompok)?ucwords($value->nama_kelompok).' / ':'';?>
                <?php echo isset($value->nama_perusahaan)?$value->nama_perusahaan:'';?></td>
                <td><?php echo isset($value->nama_tarif)?$value->nama_tarif:'';?></td>
                <td align="right"><?php echo isset($value->total)?number_format($value->total):'';?></td>
              </tr>

            </table>            

            <p><b><i class="fa fa-angle-double-right"></i> FORM JADWAL BEDAH </b></p>

           

            <div class="form-group">

              <label class="control-label col-sm-2" for="">*Tgl Persetujuan Bedah </label>
                <div class="col-md-3">
                  <div class="input-group">
                      <input name="tgl_jadwal_bedah" id="tgl_jadwal_bedah" placeholder="<?php echo $this->tanggal->formatDateForm(date('Y-m-d'))?>" class="form-control date-picker" type="text" value="<?php echo $this->tanggal->formatDateForm($value->tgl_pesan)?>">
                      <span class="input-group-addon">
                        <i class="ace-icon fa fa-calendar"></i>
                      </span>
                    </div>
                </div>
                <label class="control-label col-sm-1">*Jam</label>
                  <div class="col-sm-2">
                      <div class="input-group">
                          <input name="jam_bedah" id="jam_bedah" placeholder="hh:mm" class="form-control" type="text" value="<?php echo $this->tanggal->formatDateTimeToTime($value->tgl_pesan)?>">
                      </div>
                  </div>
            </div>

            <div class="form-group">
              <label class="control-label col-sm-2" for="">*Pilih Kamar</label>
                <div class="col-md-2">
                  <?php echo $this->master->custom_selection($params = array('table' => 'mt_ruangan', 'id' => 'kode_ruangan', 'name' => 'no_kamar', 'where' => array('kode_bagian' => '030901')), $value->kode_ruangan , 'kode_ruangan', 'kode_ruangan', 'form-control', '', '') ?>   
                  <input type="hidden" name="no_kamar_bedah" id="no_kamar_bedah" value="">
                </div>
            </div>

            <div class="form-group">

              <label class="control-label col-sm-2">Jenis Layanan</label>

              <div class="col-md-2">

                <div class="radio">

                    <label>

                      <input name="jenis_layanan_pesan_ok" type="radio" class="ace" value="0"  <?php echo ($value->jenis_layanan==0) ? 'checked' : '' ; ?> />

                      <span class="lbl"> Biasa</span>

                    </label>

                    <label>

                      <input name="jenis_layanan_pesan_ok" type="radio" class="ace" value="1" <?php echo ($value->jenis_layanan==1) ? 'checked' : '' ; ?> />

                      <span class="lbl">Cito</span>

                    </label>

                </div>

              </div>
              
            </div>
            
            <div class="form-group">
                <label class="control-label col-sm-2" for="">Jenis Bedah</label>
                <div class="col-sm-4">
                <?php echo $this->master->custom_selection(array('table'=>'mt_master_tarif', 'where'=>array('is_active'=>'Y', 'tingkatan' => 3, 'kode_bagian' => '030901'), 'id'=>'kode_tarif', 'name' => 'nama_tarif'),'','jenis_bedah','jenis_bedah','chosen-slect form-control','');?>
                </div>
            </div>

            <div class="form-group">

                <label class="control-label col-sm-2">*Nama Tindakan</label>

                <div class="col-sm-6">

                    <input id="inputKeyTindakanBedah" class="form-control"  type="text" placeholder="Masukan keyword minimal 3 karakter" value="<?php echo isset($value->nama_tarif)?$value->nama_tarif:'';?>"/>

                    <input type="hidden" name="pl_tindakan_pesan_ok" id="pl_tindakan_pesan_ok" class="form-control" value="<?php echo ($value->kode_tarif) ? $value->kode_tarif : '' ; ?>">

                </div>

            </div>

            <div class="form-group">
              <label class="control-label col-sm-2">*Dokter</label>
              <div class="col-sm-4">
                  <?php echo $this->master->custom_selection($params = array('table' => 'mt_dokter_v', 'id' => 'kode_dokter', 'name' => 'nama_pegawai', 'where' => array('kd_bagian' => '030901')), $value->dokter1 , 'pl_dokter_ok', 'pl_dokter_ok', 'form-control', '', '') ?>
              </div>
            </div>

            <div class="form-group" id="formDetailTarif" style="display:none">
                <label class="control-label col-sm-2" for="">&nbsp;</label>
                <div class="col-sm-10" style="margin-left:6px">
                  <div id="detailTarifHtml"></div>
                </div>
            </div>
            
          </div>

        </form>

    </div>

</div><!-- /.row -->