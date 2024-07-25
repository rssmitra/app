<p><b> PENDAFTARAN PENUNJANG MEDIS <i class="fa fa-angle-double-right bigger-120"></i></b></p>

<input type="hidden" id="no_registrasi_rujuk" name="no_registrasi_rujuk" value="<?php echo isset($no_reg)?$no_reg:''?>">
<input type="hidden" id="klas_rujuk" name="klas_rujuk" value="<?php echo isset($klas)?$klas:0?>">

<div class="form-group">
  <label class="control-label col-sm-2">*Asal Pasien</label>
  <div class="col-sm-4">
      <?php echo $this->master->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array('status_aktif' => 1, 'pelayanan' => 1)), isset($bagian_asal)?$bagian_asal:'' , 'asal_pasien_pm', 'asal_pasien_pm', 'form-control', '', '') ?>
  </div>
  <div class="col-md-4">
    <div class="checkbox">
      <label>
        <input name="is_pasien_luar" type="checkbox" class="ace" value="1">
        <span class="lbl"> Pasien Luar</span>
      </label>
    </div>
  </div>
</div>

<div class="form-group">
  <label class="control-label col-sm-2">*Penunjang Medis</label>
  <div class="col-sm-3">
      <?php echo $this->master->custom_selection($params = array('table' => 'mt_bagian', 'id' => 'kode_bagian', 'name' => 'nama_bagian', 'where' => array('status_aktif' => 1, 'validasi' => '0500')), '' , 'pm_tujuan', 'pm_tujuan', 'form-control', '', '') ?>
  </div>
</div>

<div class="form-group">
  <label class="control-label col-sm-2">Jenis Layanan</label>
  <div class="col-md-6">
    <div class="radio">
        <label>
          <input name="jenis_layanan_pm" type="radio" class="ace" value="0"  checked />
          <span class="lbl"> Biasa</span>
        </label>

        <label>
          <input name="jenis_layanan_pm" type="radio" class="ace" value="1" />
          <span class="lbl">Cito</span>
        </label>
    </div>
  </div>
</div>

 <?php if(isset($no_reg)): ?>
    <div class="form-group">
        <div class="col-sm-2">
            <button type="submit" href="#" class="btn btn-xs btn-primary"><i class="fa fa-save"></i> Submit</button>
        </div>
    </div>
    <br>
    <script type="text/javascript">
          
      var type = '<?php echo $type ?>';
      console.log(type)
      var no_reg = '<?php echo $no_reg?>';
      var no_mr = '<?php echo $value->no_mr?>';
      var bagian_asal = '<?php echo $bagian_asal?>';
      if(type=='ranap'){
        $("#tabs_riwayat_kunjungan").load("registration/reg_pasien/riwayat_kunjungan_by_reg/"+no_mr+"/pm/"+no_reg);
        reload_table();
      }else{
        // $("#tabs_riwayat_kunjungan").load("registration/reg_pasien/riwayat_kunjungan/"+no_mr+"/"+bagian_asal);
        $("#tabs_riwayat_kunjungan").load("Pelayanan/Pl_pelayanan_pm/riwayat_kunjungan/"+no_mr+"/"+bagian_asal);
      }
      
    </script>

    <div class="row">
      <div class="col-sm-12">
        <div id="tabs_riwayat_kunjungan"></div>
      </div>
    </div>
 <?php endif ?>

