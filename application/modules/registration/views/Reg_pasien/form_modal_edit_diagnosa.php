<!-- form_modal_edit_diagnosa.php -->

<script src="<?php echo base_url()?>assets/js/typeahead.js"></script>

<script type="text/javascript">
$(document).ready(function(){

  // Ajax form submission
  $('#form_edit_diagnosa').ajaxForm({      
    beforeSend: function() {        
      achtungShowLoader();          
    },      
    complete: function(xhr) {             
      var data = xhr.responseText;        
      var jsonResponse = JSON.parse(data);        

      if(jsonResponse.status === 200){          
        $.achtung({message: jsonResponse.message, timeout:5});          
        $('#modalEditDiagnosa').modal('hide');

        $("#tabs_detail_pasien").load(
          "registration/reg_pasien/riwayat_kunjungan/"+jsonResponse.no_mr
        );

      } else {
        $.achtung({message: jsonResponse.message, timeout:5, className: 'achtungFail'});
      }        
      achtungHideLoader();        
    }      
  }); 

  // Typeahead untuk Diagnosa Baru
  $('#inputDiagnosaBaru').typeahead({
      source: function (query, result) {
          $.ajax({
              url: "templates/references/getICD10",
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
        var val_item = item.split(':')[0];
        var label_item = item.split(':')[1];
        $('#inputDiagnosaBaru').val(label_item.trim());
        $('#kode_icd10_hidden').val(val_item.trim());
      }
  });

});
</script>

<div class="row">
<form class="form-horizontal" method="post" id="form_edit_diagnosa" action="registration/Reg_pasien/process_edit_diagnosa" autocomplete="off">

  <div class="col-sm-12 widget-container-col ui-sortable">
    <div class="widget-box transparent ui-sortable-handle">
      <div class="widget-header">
        <h4 class="widget-title lighter">
          <strong style="font-size:12px">
            <?php echo strtoupper($result['registrasi']->nama_pasien)?> (<?php echo $result['registrasi']->no_mr?>)
          </strong>
        </h4>
      </div>

      <div class="widget-body">

        <!-- KIRI: Data Pasien -->
        <div class="col-md-4">
          <table border="0" width="100%">
            <tr><td>No. Registrasi</td><td>: <?php echo $result['registrasi']->no_registrasi?></td></tr>
            <tr><td>Tanggal</td><td>: <?php echo $this->tanggal->formatDateTime($result['registrasi']->tgl_jam_masuk)?></td></tr>
            <tr><td>Poli/Klinik</td><td>: <?php echo ucwords($result['registrasi']->poli_tujuan_kunjungan)?></td></tr>
            <tr><td>Dokter</td><td>: <?php echo $result['registrasi']->nama_pegawai?></td></tr>
            <tr><td>Penjamin</td><td>: <?php echo $result['registrasi']->nama_perusahaan?></td></tr>
            <tr><td>Petugas</td><td>: <?php echo $result['petugas']->fullname?></td></tr>
          </table>
          <br>
        </div>

        <!-- KANAN: Diagnosa -->
        <div class="col-md-8">
          
          <!-- hidden -->
          <input type="hidden" name="no_mr" value="<?php echo $result['registrasi']->no_mr?>">
          <input type="hidden" name="no_registrasi" value="<?php echo $result['registrasi']->no_registrasi?>">
          <input type="hidden" name="no_kunjungan" value="<?php echo $result['registrasi']->no_kunjungan?>">
          <input type="hidden" name="kode_icd10" id="kode_icd10_hidden">

          <!-- Diagnosa Saat Ini -->
          <div class="form-group">
            <label class="control-label col-md-3">Diagnosa Saat Ini</label>
            <div class="col-md-6">
              <input type="text" class="form-control" value="<?php echo $result['registrasi']->diagnosa_awal?>" readonly>
            </div>
          </div>

          <!-- Diagnosa Baru -->
          <div class="form-group">
            <label class="control-label col-md-3">Diagnosa Baru</label>
            <div class="col-md-6">
              <input type="text" id="inputDiagnosaBaru" class="form-control" name="diagnosa_baru" placeholder="Masukan keyword minimal 3 karakter" />
            </div>
            <div class="col-md-3">
              <button type="submit" class="btn btn-xs btn-primary">
                <i class="ace-icon fa fa-check-square-o bigger-110"></i> Submit
              </button>
            </div>
          </div>

        </div>

      </div>
    </div>
  </div>

</form>
</div>

<!-- Supaya dropdown typeahead muncul di atas modal -->
<style>
.typeahead.dropdown-menu {
  z-index: 1051;
}
</style>
