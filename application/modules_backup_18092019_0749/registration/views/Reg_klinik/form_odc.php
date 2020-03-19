<script type="text/javascript">
  $('select[name="odc_bagian"]').change(function () {      

    if ($(this).val()) {          

        $.getJSON("<?php echo site_url('Templates/References/getTindakanByBagian') ?>/" + $(this).val(), '', function (data) {              

            $('#odc_paket_tindakan option').remove();                

            $('<option value="">-Pilih Kelas-</option>').appendTo($('#odc_paket_tindakan'));                

            $.each(data, function (i, o) {                  

                $('<option value="' + o.kode_tarif + '">' + o.nama_tarif + '</option>').appendTo($('#odc_paket_tindakan'));                    

            });                

        }); 

    } else {          

        $('#odc_paket_tindakan option').remove()     

    }        

  });  

  $('input[name="is_paket"]').click(function (e) {  
    var value = $(this).val();
    if (value==2) {
      $('#odc_tindakan').hide('fast');
    } else {
      $('#odc_tindakan').show('fast');
    }
  }); 

</script>

<p style="margin-left:-1%"><b><i class="fa fa-edit"></i> PENDAFTARAN PASIEN ODC</b></p>

<div class="form-group">

  <label class="control-label col-sm-2">Status Pasien</label>

  <div class="col-md-6">

    <div class="radio">

        <label>

          <input name="odc_status_pasien" type="radio" class="ace" value="Baru"  />

          <span class="lbl"> Pasien Baru</span>

        </label>

        <label>

          <input name="odc_status_pasien" type="radio" class="ace" value="Lama" checked="checked" />

          <span class="lbl"> Pasien Lama</span>

        </label>

    </div>

  </div>

</div>

<div class="form-group">

      <label class="control-label col-sm-2" for="Province">*Klinik</label>

      <div class="col-sm-4">

          <?php echo $this->master->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where_in' => array('col' => 'kode_bagian','val' => array('012701', '013201'))), '' , 'odc_bagian', 'odc_bagian', 'form-control', '', '') ?>

      </div>

</div>

<div class="form-group">

  <label class="control-label col-sm-2">Paket ?</label>

  <div class="col-md-6">

    <div class="radio">

        <label>

          <input name="is_paket" type="radio" class="ace" value="2" />

          <span class="lbl"> Bukan Paket</span>

        </label>

         <label>

          <input name="is_paket" type="radio" class="ace" value="1" checked="checked" />

          <span class="lbl"> Paket</span>

        </label>

    </div>

  </div>

</div>

<div class="form-group" id="odc_tindakan">

      <label class="control-label col-sm-2" for="Province">*Rencana Tindakan</label>

      <div class="col-sm-4">

          <?php echo $this->master->get_change($params = array('table' => 'mt_master_tarif', 'id' => 'kode_tarif', 'name' => 'nama_tarif', 'where' => array()), '' , 'odc_paket_tindakan', 'odc_paket_tindakan', 'form-control', '', '') ?>
          
      </div>

</div>

<div class="form-group">

      <label class="control-label col-sm-2" for="Province">*Dokter</label>

      <div class="col-sm-4">

          <?php echo $this->master->custom_selection($params = array('table' => 'mt_dokter_v', 'id' => 'kode_dokter', 'name' => 'nama_pegawai', 'where' => array('kd_bagian' => '012701') ), '' , 'odc_kode_dokter', 'odc_kode_dokter', 'form-control', '', '') ?>

      </div>

</div>


