<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>

<script type="text/javascript">

  $(document).ready(function(){

    $('#form_edit_dokter').ajaxForm({      

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

    $('#inputDokter').typeahead({
        source: function (query, result) {
                $.ajax({
                    url: "templates/references/getAllDokter",
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
            $('#kode_edit_dokter_hidden').val(val_item);
            
        }
    });

  })
  

</script>

<div class="row">

<form class="form-horizontal" method="post" id="form_edit_dokter" action="registration/Reg_pasien/process_edit_dokter_pemeriksa" enctype="multipart/form-data" autocomplete="off">

  <div class="col-sm-12 widget-container-col ui-sortable">
    <div class="widget-box transparent ui-sortable-handle">
      <div class="widget-header">
        <h4 class="widget-title lighter"><strong style="font-size:12px"><?php echo strtoupper($result['registrasi']->nama_pasien)?> (<?php echo $result['registrasi']->no_mr?>) </strong></h4>
      </div>
      <div class="widget-body">

        <div class="col-md-4">
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

        <div class="col-md-8">
          <p><b><i class="fa fa-user"></i> Dokter Pemeriksa </b></p>

          <!-- hidden form -->
          <input class="form-control" name="no_mr_hidden_edit_dokter" type="hidden" value="<?php echo $result['registrasi']->no_mr?>" />
          <input class="form-control" name="no_registrasi_hidden_edit_dokter" type="hidden" value="<?php echo $result['registrasi']->no_registrasi?>" />
          <input class="form-control" name="no_kunjungan_hidden_edit_dokter" type="hidden" value="<?php echo $result['registrasi']->no_kunjungan?>" />

          <div class="form-group">
            <label class="control-label col-md-3">Nama Dokter Pemeriksa</label>
            <div class="col-md-6">
                <input id="inputDokter" class="form-control" name="dokter_pemeriksa" type="text" placeholder="Masukan keyword minimal 3 karakter" />
                <input type="hidden" name="kode_edit_dokter_hidden" value="" id="kode_edit_dokter_hidden">
            </div>
            <div class="col-md-3" style="margin-left: -1.3%">  
              <button type="submit" name="submit" class="btn btn-xs btn-primary">
                <i class="ace-icon fa fa-check-square-o icon-on-right bigger-110"></i>
                Submit
              </button>
            </div>
          </div>

        </div>

      </div>

      </div>
    </div>
  </div>

  </form>
  
</div>



<!-- end form create SEP