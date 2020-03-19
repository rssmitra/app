<p style="margin-left:-1%"><b><i class="fa fa-edit"></i> PENDAFTARAN PASIEN ODV VK</b></p>

<div class="form-group">

  <label class="control-label col-sm-2">Status Pasien</label>

  <div class="col-md-6">

    <div class="radio">

        <label>

          <input name="status_pasien" type="radio" class="ace" value="Y" <?php echo isset($value) ? ($value->is_active == 'Y') ? 'checked="checked"' : '' : ''; ?>  />

          <span class="lbl"> Pasien Lama</span>

        </label>

        <label>

          <input name="status_pasien" type="radio" class="ace" value="N" <?php echo isset($value) ? ($value->is_active == 'N') ? 'checked="checked"' : '' : 'checked="checked"'; ?> />

          <span class="lbl"> Pasien Baru</span>

        </label>

    </div>

  </div>

</div>

<div class="form-group">

      <label class="control-label col-sm-2" for="Province">*Klinik</label>

      <div class="col-sm-4">

          <?php echo $this->master_sqlsrv->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array('validasi' => 100, 'status_aktif' => 1)), '' , 'klinikId', 'klinikId', 'form-control', '', '') ?>

      </div>

</div>

<div class="form-group">

  <label class="control-label col-sm-2">Paket ?</label>

  <div class="col-md-6">

    <div class="radio">

        <label>

          <input name="status_pasien" type="radio" class="ace" value="Y" <?php echo isset($value) ? ($value->is_active == 'Y') ? 'checked="checked"' : '' : ''; ?>  />

          <span class="lbl"> Paket</span>

        </label>

        <label>

          <input name="status_pasien" type="radio" class="ace" value="N" <?php echo isset($value) ? ($value->is_active == 'N') ? 'checked="checked"' : '' : 'checked="checked"'; ?> />

          <span class="lbl"> Bukan Paket</span>

        </label>

    </div>

  </div>

</div>

<div class="form-group">

      <label class="control-label col-sm-2" for="Province">*Rencana Tindakan</label>

      <div class="col-sm-4">

          <?php echo $this->master_sqlsrv->custom_selection($params = array('table' => 'mt_dokter_v', 'id' => 'kode_dokter', 'name' => 'nama_pegawai', 'where' => array() ), '' , 'kode_dokter_mcu', 'kode_dokter_mcu', 'form-control', '', '') ?>

      </div>

</div>

<div class="form-group">

      <label class="control-label col-sm-2" for="Province">*Dokter</label>

      <div class="col-sm-4">

          <?php echo $this->master_sqlsrv->custom_selection($params = array('table' => 'mt_dokter_v', 'id' => 'kode_dokter', 'name' => 'nama_pegawai', 'where' => array() ), '' , 'kode_dokter_mcu', 'kode_dokter_mcu', 'form-control', '', '') ?>

      </div>

</div>


