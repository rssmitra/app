<script>

$(document).ready(function(){

  $.getJSON("<?php echo site_url('Templates/References/getPaketMCU') ?>", '', function (data) {              

    $('#mcu_paket_tindakan option').remove();            

    $('<option value="">-Pilih Tindakan-</option>').appendTo($('#mcu_paket_tindakan'));                

    $.each(data, function (i, o) {                  

        $('<option value="' + o.kode_tarif + '">' + o.nama_tarif + '</option>').appendTo($('#mcu_paket_tindakan'));                        

    });           

  })

})

$('#InputKeyMcu').typeahead({
      source: function (query, result) {
          $.ajax({
              url: "templates/references/getPaketMCU",
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
        $('#mcu_paket_tindakan').val(val_item);
      }
  });

</script>

<p><b><i class="fa fa-edit"></i> PENDAFTARAN PASIEN MCU</b></p>

<div class="form-group">
  <label class="control-label col-sm-3">Status Pasien</label>
  <div class="col-md-8">
    <div class="radio">
        <label>
          <input name="mcu_status_pasien" type="radio" class="ace" value="Lama" />
          <span class="lbl"> Pasien Lama</span>
        </label>

        <label>
          <input name="mcu_status_pasien" type="radio" class="ace" value="Baru" checked="checked" />
          <span class="lbl"> Pasien Baru</span>
        </label>
    </div>
  </div>
</div>

<div class="form-group">
    <label class="control-label col-sm-3">*Paket Tindakan</label>
    <div class="col-sm-8">
      <?php //echo $this->master->get_change($params = array('table' => 'mt_master_tarif', 'id' => 'kode_tarif', 'name' => 'nama_tarif', 'where' => array()), '' , 'mcu_paket_tindakan', 'mcu_paket_tindakan', 'form-control', '', '') ?>
      <input id="InputKeyMcu" class="form-control" name="mcu" type="text" placeholder="Masukan keyword minimal 3 karakter" />
      <input type="hidden" name="mcu_paket_tindakan" value="" id="mcu_paket_tindakan">
    </div>
</div>

<div class="form-group">
      <label class="control-label col-sm-3">*Dokter MCU</label>
      <div class="col-sm-6">
          <?php echo $this->master->custom_selection($params = array('table' => 'mt_dokter_v', 'id' => 'kode_dokter', 'name' => 'nama_pegawai', 'where' => array('kd_bagian' => '010901') ), '' , 'mcu_kode_dokter', 'mcu_kode_dokter', 'form-control', '', '') ?>
      </div>
</div>


