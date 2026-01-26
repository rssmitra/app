<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>

<script type="text/javascript">

  $(document).ready(function(){

    $('#form_edit_penjamin').ajaxForm({      

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

          $('#modalEditPenjamin').modal('hide');
          console.log(jsonResponse);
          /*show action after success submit form*/
          $("#tabs_detail_pasien").load("registration/reg_pasien/riwayat_kunjungan/"+jsonResponse.no_mr);

        }else{
                      $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
                    }        

        achtungHideLoader();        

      }      

    }); 


  });


</script>

<script>

  $(document).ready(function(){

    $('#InputKeyNasabahEditPenjamin').typeahead({
        source: function (query, result) {
            $.ajax({
                url: "templates/references/getKelompokNasabah",
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
          var label_item=item.split(':')[1];
          console.log(val_item);
          $('#InputKeyNasabahEditPenjamin').val(label_item);
          $('#kode_kelompok_hidden_edit_penjamin').val(val_item);
          //$('#kode_perusahaan_hidden_edit_penjamin').val(val_item);

          // selalu tampilkan field perusahaan
          //$('#field_perusahaan').show('fast');

          if (val_item == 1) { // UMUM
            $('#field_perusahaan').hide('fast');
            $('#kode_perusahaan_hidden_edit_penjamin').val('');
            $('#InputKeyPenjaminEdit').val('');
              } else { // SELAIN UMUM
            $('#field_perusahaan').show('fast');
          }

          //if (val_item == 3) {

          //  $('#field_perusahaan').show('fast');

          //}else {

          //  $('#field_perusahaan').hide('fast');

          //}

          // if(val_item !== 3){
          //   $('#kode_perusahaan_hidden_edit_penjamin').val('');
          //   $('#InputKeyPenjaminEdit').val('');
          // }  
        }
    });

    $('#InputKeyPenjaminEdit').typeahead({
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
          var label_item=item.split(':')[1];
          console.log(val_item);
          $('#InputKeyPenjaminEdit').val(label_item);
          $('#kode_perusahaan_hidden_edit_penjamin').val(val_item);
          if( val_item == 120 ){
            $('#form_sep_edit_penjamin').show();
          }else{
            $('#form_sep_edit_penjamin').hide();
          }
        }
    });

  })
  

</script>

<div class="row">

<form class="form-horizontal" method="post" id="form_edit_penjamin" action="registration/Reg_pasien/process_edit_transaksi_penjamin_pasien" enctype="multipart/form-data" autocomplete="off">

  <div class="col-sm-12 widget-container-col ui-sortable">
    <div class="widget-box transparent ui-sortable-handle">
      <div class="widget-header">
        <h4 class="widget-title lighter"><strong style="font-size:12px"><?php echo strtoupper($result['registrasi']->nama_pasien)?> (<?php echo $result['registrasi']->no_mr?>) </strong></h4>
      </div>
      <div class="widget-body">

        <div class="col-md-6">
          <table border="0" width="100%">
            <tr>
              <td>No. Registrasi</td>
              <td colspan="3"> : <?php echo $result['registrasi']->no_registrasi?></td>
            </tr>

             <tr>
              <td>Tanggal</td>
              <td colspan="3"> : <?php echo $this->tanggal->formatDateTime($result['registrasi']->tgl_jam_masuk)?></td>
            </tr>

            <tr>
              <td>Poli/Klinik</td>
              <td colspan="3"> : <?php echo ucwords($result['registrasi']->poli_tujuan_kunjungan)?></td>
            </tr>

            <tr>
              <td>Dokter</td>
              <td colspan="3"> : <?php echo $result['registrasi']->nama_pegawai?></td>
            </tr>

            <tr>
              <td>Penjamin</td>
              <td colspan="3"> : <?php echo $result['registrasi']->nama_perusahaan?></td>
            </tr>

            <tr>
              <td>Petugas</td>
              <td colspan="3"> : <?php echo $result['petugas']->fullname?> </td>
            </tr>
          </table>
          <br>
        </div>

        <div class="col-md-6">
          <?php if( count($result['tindakan']) > 0) :?>
            <div class="alert alert-danger">
              <strong>Perhatian !</strong><br>
              <p style="text-align:justify">Mengubah penjamin pasien, akan mengakibatkan perubahan pada tarif pelayanan pasien selanjutnya.</p>
            </div>
          <?php endif;?>
        </div>

        <div class="col-md-12">
          <p><b><i class="fa fa-user"></i> PENJAMIN PASIEN(*) </b></p>

          <!-- hidden form -->
          <input class="form-control" name="no_mr_hidden_edit_penjamin" type="hidden" value="<?php echo $result['registrasi']->no_mr?>" />
          <input class="form-control" name="no_registrasi_hidden_edit_penjamin" type="hidden" value="<?php echo $result['registrasi']->no_registrasi?>" />

          <div class="form-group">
            <label class="control-label col-md-2">Nasabah</label>
            <div class="col-md-4">
                <input id="InputKeyNasabahEditPenjamin" class="form-control" name="kelompok_nasabah" type="text" placeholder="Masukan keyword minimal 3 karakter" />
                <input type="hidden" name="kode_kelompok_hidden_edit_penjamin" value="" id="kode_kelompok_hidden_edit_penjamin">
            </div>
          </div>

          <div class="form-group" id="field_perusahaan" style="display:none;">
            <label class="control-label col-md-2">Nama Perusahaan</label>
              <div class="col-md-4">
                  <input id="InputKeyPenjaminEdit" class="form-control" name="penjamin" type="text" placeholder="Masukan keyword minimal 3 karakter" />
                  <input type="hidden" name="kode_perusahaan_hidden_edit_penjamin" value="" id="kode_perusahaan_hidden_edit_penjamin">
              </div>
          </div>

          <div class="form-group" id="form_sep_edit_penjamin" style="display:none">
            <label class="control-label col-md-2">Nomor SEP</label>   
             <div class="col-md-4">     
               <div class="input-group">
                 <input name="noSepEditPenjamin" id="noSepEditPenjamin" class="form-control" type="text" placeholder="Masukan No SEP">
               </div>
             </div>   
          </div>

          <div class="form-group">
            <label class="col-md-2">&nbsp;</label>   
            <div class="col-md-4">  
              <button type="submit" name="submit" class="btn btn-xs btn-primary">
                <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>
                Submit
              </button>
            </div>
          </div>

        </div>

        <div class="col-md-12 no-padding">
          <?php echo $html?>
        </div>

      </div>

      </div>
    </div>
  </div>

  </form>
  
</div>



<!-- end form create SEP