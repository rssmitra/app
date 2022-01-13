<script src="<?php echo base_url()?>assets/js/date-time/bootstrap-datepicker.js"></script>

<link rel="stylesheet" href="<?php echo base_url()?>assets/css/datepicker.css" />

<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>

<script>

$(document).ready(function(){

  $('#form_pindah_ruangan_').ajaxForm({      

      beforeSend: function() {        

        achtungShowFadeIn();          

      },      

      uploadProgress: function(event, position, total, percentComplete) {        

      },      

      complete: function(xhr) {             

        var data=xhr.responseText;        

        var jsonResponse = JSON.parse(data);        

        if(jsonResponse.status === 200){          

          $.achtung({message: jsonResponse.message, timeout:5});          

          setTimeout(function(){

            /*window.open(jsonResponse.redirect, '_blank');*/
            $("#modalPindahRuangan").modal('hide');
            //$('#page-area-content').load(jsonResponse.redirect);

          },1800);


        }else{
                      $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
                    }        

        achtungHideLoader();        

      }      

    });     

    $('select[name="ri_klas_ruangan"]').change(function () {      


      /*hide value*/
      $('#ri_no_bed_hidden').val('');
      $('#ri_no_ruangan').val('');
      $('#ri_deposit').val('');
      $('#ri_harga_ruangan_hidden').val('');
      $('#ri_harga_ruangan_bpjs_hidden').val('');
      
      if ($(this).val()) {          

          /*get ruangan by klas*/
          $.getJSON("<?php echo site_url('Templates/References/getRuanganByKlas') ?>/" + $(this).val(), '', function (data) {              

              $('#ri_ruangan option').remove();                

              $('<option value="">-Pilih Ruangan-</option>').appendTo($('#ri_ruangan'));                

              $.each(data, function (i, o) {                  

                  $('<option value="' + o.kode_bagian + '">' + o.nama_bagian + '</option>').appendTo($('#ri_ruangan'));                    

              });                

          }); 

      }    

    });  

    $('select[name="ri_ruangan"]').change(function () {      

      if ($(this).val()) {          

          /*get ruangan by klas*/
          $.getJSON("<?php echo site_url('Templates/References/getBedByKlas') ?>/" + $(this).val() + "/" +  $("#ri_klas_ruangan").val(), '', function (data) {              

              $('#ri_kamar option').remove();                

              $('<option value="">-Pilih Ruangan-</option>').appendTo($('#ri_kamar'));                

              $.each(data, function (i, o) {                  

                  $('<option value="' + o.kode_ruangan + '">Kamar:' + o.no_kamar + ' (Bed : ' + o.no_bed + ')</option>').appendTo($('#ri_kamar'));                    

              });                

          }); 

          $.getJSON("<?php echo site_url('Templates/References/getDokterByBagian_') ?>/" + $(this).val(), '', function (data) {              

            $('#ri_dokter_ruangan option').remove();                

            $('<option value="">-Pilih Dokter-</option>').appendTo($('#ri_dokter_ruangan'));                

            $.each(data, function (i, o) {                  

                $('<option value="' + o.kode_dokter + '">' + o.nama_pegawai + '</option>').appendTo($('#ri_dokter_ruangan'));                    

            });                

          });            

      }    

    });  

    $('#inputDokterMerawat').typeahead({
      source: function (query, result) {
              $.ajax({
                  url: "templates/references/getDokterByKeyword",
                  data: 'keyword=' + query + '&bag=' + $('#ri_ruangan').val(),         
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
          $('#ri_dokter_ruangan').val(val_item);
          
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



</script>

<div class="row">

  <div class="col-xs-12">  

    <!-- div.dataTables_borderWrap -->

    <div>    

    <div id="user-profile-1" class="user-profile row">
      
      <div class="col-xs-12 col-sm-12">
        

        <form class="form-horizontal" method="post" id="form_pindah_ruangan_" action="<?php echo site_url('registration/Pindah_ruangan/process')?>" enctype="multipart/form-data">   

          <!-- hidden form  -->
          <input type="hidden" name="no_mr" value="<?php echo $value->no_mr?>" id="no_mr">
          <input type="hidden" name="nama_pasien" value="<?php echo $value->nama_pasien?>" id="nama_pasien">
          <input type="hidden" name="kode_ri" value="<?php echo $rawatinap->kode_ri?>" id="no_registrasi">
          <input type="hidden" name="no_registrasi" value="<?php echo $rawatinap->no_registrasi?>" id="no_registrasi">
          <input type="hidden" name="no_kunjungan" value="<?php echo $rawatinap->no_kunjungan?>" id="no_kunjungan">
          <input type="hidden" name="kode_ruangan" value="<?php echo $rawatinap->kode_ruangan?>" id="kode_ruangan">
          <input type="hidden" name="kode_bagian" value="<?php echo $rawatinap->bag_pas?>" id="kode_bagian">
          <input type="hidden" name="kelas_pas" id="kelas_pas" value="<?php echo $rawatinap->kelas_pas?>">
          <input type="hidden" name="kode_kelompok" id="kode_kelompok" value="<?php echo $rawatinap->kode_kelompok?>">
          <input type="hidden" name="kode_perusahaan" id="kode_perusahaan" value="<?php echo $rawatinap->kode_perusahaan?>">

          <p><b><i class="fa fa-edit"></i> Pilih Ruangan Tujuan </b></p>

          <div class="form-group">

            <label class="control-label col-sm-2">*Kelas Pasien</label>

            <div class="col-sm-3">
                
                <?php echo $this->master->custom_selection($params = array('table' => 'mt_klas', 'id' => 'kode_klas', 'name' => 'nama_klas', 'where' => array()), '' , 'ri_klas_ruangan', 'ri_klas_ruangan', 'form-control', '', '') ?>

            </div>

            <label class="control-label col-sm-1">*Ruangan</label>

            <div class="col-sm-3">
                
                <?php echo $this->master->get_change($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array('validasi' => '0300', 'pelayanan' => 1)), '' , 'ri_ruangan', 'ri_ruangan', 'form-control', '', '') ?>

            </div>

          </div>

          <div  class="form-group">

            <label class="control-label col-sm-2">Kode Ruangan</label>            

            <div class="col-sm-3">
                
                <?php echo $this->master->get_change($params = array('table' => 'mt_ruangan', 'id' => 'kode_ruangan', 'name' => 'no_kamar', 'where' => array('flag_cad' => 0)), '' , 'ri_kamar', 'ri_kamar', 'form-control', '', '') ?>
                <input type="hidden" name="ri_no_kamar" id="ri_no_kamar" value="<?php echo $rawatinap->kode_perusahaan?>">
                <input type="hidden" name="ri_no_bed" id="ri_no_bed" value="<?php echo $rawatinap->kode_perusahaan?>">

            </div>

          </div>

          <div class="form-group">
              <label class="control-label col-sm-2">*Dokter</label>
              <div class="col-sm-4">
                  
                  <input id="inputDokterMerawat" class="form-control"  type="text" placeholder="Masukan keyword minimal 3 karakter" />

                  <input type="hidden" name="ri_dokter_ruangan" id="ri_dokter_ruangan" class="form-control">
              </div>
          </div>

          <div class="form-actions center">
              <button type="button" class="btn btn-sm btn-danger" data-dismiss="modal" aria-hidden="true"><i class="fa fa-times"></i> Close</button>
              <button type="submit" id="btnSave" name="submit" class="btn btn-sm btn-primary">
                  <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>
                  Submit
              </button>
          </div>
       
          

        </form>

      </div>

    </div>

    

    </div>

  </div><!-- /.col -->

</div><!-- /.row -->

