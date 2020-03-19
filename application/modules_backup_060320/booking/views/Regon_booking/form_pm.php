<p style="margin-left:-1%"><b><i class="fa fa-edit"></i> PENDAFTARAN PENUNJANG MEDIS</b></p>

<div class="form-group">

      <label class="control-label col-sm-2" for="Province">*Asal Pasien</label>

      <div class="col-sm-4">

          <?php echo $this->master_sqlsrv->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array('validasi' => 100, 'status_aktif' => 1, 'pelayanan' => 1)), '' , 'asal_pasien_pm', 'asal_pasien_pm', 'form-control', '', '') ?>

      </div>

      <div class="col-md-2">
        <div class="checkbox">
          <label>
            <input name="is_pasien_luar" type="checkbox" class="ace" value="1">
            <span class="lbl"> Pasien Luar</span>
          </label>
        </div>
      </div>


</div>

<div class="form-group">

      <label class="control-label col-sm-2" for="Province">*Penunjang Medis</label>

      <div class="col-sm-3">

          <?php echo $this->master_sqlsrv->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array('status_aktif' => 1, 'validasi' => '0500')), '' , 'pm_tujuan', 'pm_tujuan', 'form-control', '', '') ?>

      </div>

</div>

<div class="form-group">

    <label class="control-label col-sm-2">Jenis Layanan</label>

    <div class="col-md-2">

      <div class="radio">

          <label>

            <input name="jenis_layanan_pm" type="radio" class="ace" value="0" <?php echo isset($value) ? ($value->is_active == '0') ? 'checked="checked"' : '' : ''; ?>  />

            <span class="lbl"> Biasa</span>

          </label>

          <label>

            <input name="jenis_layanan_pm" type="radio" class="ace" value="1" <?php echo isset($value) ? ($value->is_active == '1') ? 'checked="checked"' : '' : 'checked="checked"'; ?> />

            <span class="lbl">Cito</span>

          </label>

      </div>

    </div>

  </div>
